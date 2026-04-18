<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Order;
use App\Models\OrderFeedback;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AdminReportService
{
    public function build(string $preset): array
    {
        $period = $this->resolvePeriod($preset);
        $orders = $this->ordersForPeriod($period['start'], $period['end']);
        $feedback = $this->feedbackForPeriod($period['start'], $period['end']);
        $conversations = $this->conversationsForPeriod($period['start'], $period['end']);
        $topProducts = $this->topProductsForPeriod($period['start'], $period['end']);

        $transactionCount = $orders->count();
        $revenue = round($orders->sum(fn (Order $order) => (float) $order->total), 2);
        $averageOrderValue = $transactionCount > 0 ? round($revenue / $transactionCount, 2) : 0.0;

        $conversationCount = $conversations->count();
        $convertedConversationCount = $orders
            ->pluck('conversation_id')
            ->filter()
            ->unique()
            ->count();
        $conversionRate = $conversationCount > 0
            ? round(($convertedConversationCount / $conversationCount) * 100, 2)
            : 0.0;

        $repeatCustomers = $orders
            ->pluck('customer_id')
            ->filter()
            ->countBy()
            ->filter(fn (int $count) => $count > 1)
            ->count();

        $nps = $this->calculateNps($feedback);

        return [
            'selectedRange' => $period['key'],
            'rangeOptions' => [
                'today' => 'Today',
                'yesterday' => 'Yesterday',
                'this_week' => 'This Week',
                'this_month' => 'This Month',
                'this_quarter' => 'This Quarter',
                'this_year' => 'This Year',
            ],
            'period' => $period,
            'metrics' => [
                'revenue' => $revenue,
                'transactions' => $transactionCount,
                'aov' => $averageOrderValue,
                'conversion_rate' => $conversionRate,
                'repeat_customers' => $repeatCustomers,
                'nps' => $nps['score'],
                'nps_response_count' => $nps['responses'],
                'promoters' => $nps['promoters'],
                'passives' => $nps['passives'],
                'detractors' => $nps['detractors'],
            ],
            'topProducts' => $topProducts,
            'chartData' => [
                'salesTrend' => $this->salesTrend($orders, $period),
                'statusBreakdown' => $this->statusBreakdown($orders),
                'topProducts' => [
                    'labels' => $topProducts->pluck('product_name')->all(),
                    'values' => $topProducts->pluck('revenue')->map(fn ($value) => round((float) $value, 2))->all(),
                ],
                'npsDistribution' => [
                    'labels' => range(1, 10),
                    'values' => collect(range(1, 10))
                        ->map(fn (int $score) => $feedback->where('score', $score)->count())
                        ->all(),
                ],
            ],
            'tables' => [
                'top_products' => $topProducts,
                'orders' => $orders
                    ->sortByDesc(fn (Order $order) => $order->placed_at ?: $order->created_at)
                    ->take(10)
                    ->values(),
                'nps_responses' => $feedback
                    ->sortByDesc('responded_at')
                    ->take(10)
                    ->values(),
            ],
        ];
    }

    protected function resolvePeriod(string $preset): array
    {
        $timezone = config('app.timezone');
        $now = now()->timezone($timezone);

        [$key, $label, $start, $end, $granularity] = match ($preset) {
            'yesterday' => ['yesterday', 'Yesterday', $now->copy()->subDay()->startOfDay(), $now->copy()->subDay()->endOfDay(), 'hour'],
            'this_week' => ['this_week', 'This Week', $now->copy()->startOfWeek(), $now->copy()->endOfWeek(), 'day'],
            'this_month' => ['this_month', 'This Month', $now->copy()->startOfMonth(), $now->copy()->endOfMonth(), 'day'],
            'this_quarter' => ['this_quarter', 'This Quarter', $now->copy()->startOfQuarter(), $now->copy()->endOfQuarter(), 'week'],
            'this_year' => ['this_year', 'This Year', $now->copy()->startOfYear(), $now->copy()->endOfYear(), 'month'],
            default => ['today', 'Today', $now->copy()->startOfDay(), $now->copy()->endOfDay(), 'hour'],
        };

        return [
            'key' => $key,
            'label' => $label,
            'start' => $start,
            'end' => $end,
            'granularity' => $granularity,
            'display_range' => $start->format('d M Y, h:i A').' - '.$end->format('d M Y, h:i A'),
        ];
    }

    protected function ordersForPeriod(Carbon $start, Carbon $end): Collection
    {
        return Order::query()
            ->with(['customer'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('placed_at', [$start, $end])
                    ->orWhere(function ($nested) use ($start, $end) {
                        $nested->whereNull('placed_at')
                            ->whereBetween('created_at', [$start, $end]);
                    });
            })
            ->get([
                'id',
                'order_number',
                'customer_id',
                'conversation_id',
                'status',
                'payment_status',
                'total',
                'placed_at',
                'created_at',
            ]);
    }

    protected function feedbackForPeriod(Carbon $start, Carbon $end): Collection
    {
        return OrderFeedback::query()
            ->with(['order', 'customer'])
            ->whereBetween('responded_at', [$start, $end])
            ->get();
    }

    protected function conversationsForPeriod(Carbon $start, Carbon $end): Collection
    {
        return Conversation::query()
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('last_message_at', [$start, $end])
                    ->orWhere(function ($nested) use ($start, $end) {
                        $nested->whereNull('last_message_at')
                            ->whereBetween('created_at', [$start, $end]);
                    });
            })
            ->get(['id']);
    }

    protected function topProductsForPeriod(Carbon $start, Carbon $end): Collection
    {
        return OrderItem::query()
            ->selectRaw('order_items.product_id, order_items.product_name, SUM(order_items.quantity) as units_sold, SUM(order_items.total_price) as revenue')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', '!=', 'cancelled')
            ->whereNull('orders.deleted_at')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('orders.placed_at', [$start, $end])
                    ->orWhere(function ($nested) use ($start, $end) {
                        $nested->whereNull('orders.placed_at')
                            ->whereBetween('orders.created_at', [$start, $end]);
                    });
            })
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();
    }

    protected function calculateNps(Collection $feedback): array
    {
        $responses = $feedback->count();
        $promoters = $feedback->filter(fn (OrderFeedback $item) => $item->score >= 9)->count();
        $passives = $feedback->filter(fn (OrderFeedback $item) => $item->score >= 7 && $item->score <= 8)->count();
        $detractors = $feedback->filter(fn (OrderFeedback $item) => $item->score <= 6)->count();

        $score = $responses > 0
            ? round((($promoters / $responses) * 100) - (($detractors / $responses) * 100), 2)
            : 0.0;

        return compact('score', 'responses', 'promoters', 'passives', 'detractors');
    }

    protected function salesTrend(Collection $orders, array $period): array
    {
        $buckets = collect($this->timelineBuckets($period['start'], $period['end'], $period['granularity']));

        $values = $buckets->map(function (array $bucket) use ($orders, $period) {
            $sum = $orders->filter(function (Order $order) use ($bucket, $period) {
                $timestamp = ($order->placed_at ?: $order->created_at)?->copy()?->timezone(config('app.timezone'));

                if (! $timestamp) {
                    return false;
                }

                return $this->bucketKey($timestamp, $period['granularity']) === $bucket['key'];
            })->sum(fn (Order $order) => (float) $order->total);

            return round((float) $sum, 2);
        })->all();

        return [
            'labels' => $buckets->pluck('label')->all(),
            'values' => $values,
        ];
    }

    protected function statusBreakdown(Collection $orders): array
    {
        $statuses = ['pending', 'processing', 'dispatched', 'delivered'];

        return [
            'labels' => collect($statuses)->map(fn (string $status) => str($status)->headline()->toString())->all(),
            'values' => collect($statuses)->map(fn (string $status) => $orders->where('status', $status)->count())->all(),
        ];
    }

    protected function timelineBuckets(Carbon $start, Carbon $end, string $granularity): array
    {
        $cursor = $start->copy();
        $buckets = [];

        while ($cursor <= $end) {
            $buckets[] = [
                'key' => $this->bucketKey($cursor, $granularity),
                'label' => match ($granularity) {
                    'hour' => $cursor->format('H:00'),
                    'week' => 'Wk '.$cursor->weekOfYear,
                    'month' => $cursor->format('M'),
                    default => $cursor->format('d M'),
                },
            ];

            $cursor = match ($granularity) {
                'hour' => $cursor->addHour(),
                'week' => $cursor->addWeek(),
                'month' => $cursor->addMonth(),
                default => $cursor->addDay(),
            };
        }

        return $buckets;
    }

    protected function bucketKey(Carbon $timestamp, string $granularity): string
    {
        return match ($granularity) {
            'hour' => $timestamp->format('Y-m-d H:00'),
            'week' => $timestamp->format('o-\WW'),
            'month' => $timestamp->format('Y-m'),
            default => $timestamp->format('Y-m-d'),
        };
    }
}
