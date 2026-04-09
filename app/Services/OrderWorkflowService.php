<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderWorkflowService
{
    public function __construct(
        protected InventoryService $inventoryService,
        protected WhatsAppCloudApiService $cloudApi
    ) {
    }

    public function ensurePublicToken(Order $order): string
    {
        $metadata = $order->metadata ?? [];
        $token = (string) data_get($metadata, 'public_token', '');

        if ($token !== '') {
            return $token;
        }

        $token = Str::lower(Str::random(32));
        data_set($metadata, 'public_token', $token);

        $order->forceFill([
            'metadata' => $metadata,
        ])->save();

        return $token;
    }

    public function publicUrl(Order $order): string
    {
        return route('orders.track', $this->ensurePublicToken($order));
    }

    public function updateOrder(
        Order $order,
        array $attributes,
        ?string $cancellationInventoryAction = null
    ): Order {
        return DB::transaction(function () use ($order, $attributes, $cancellationInventoryAction) {
            $order->loadMissing(['items.product', 'customer', 'store', 'conversation']);

            $oldStatus = (string) $order->status;
            $oldPaymentStatus = (string) $order->payment_status;
            $newStatus = (string) ($attributes['status'] ?? $oldStatus);
            $newPaymentStatus = (string) ($attributes['payment_status'] ?? $oldPaymentStatus);

            if (
                $newStatus === 'cancelled'
                && ! in_array($oldStatus, ['pending', 'cancelled'], true)
                && ! in_array($cancellationInventoryAction, ['restock', 'damaged'], true)
            ) {
                throw ValidationException::withMessages([
                    'cancellation_inventory_action' => 'Choose whether the cancelled order stock should be restocked or marked as damaged.',
                ]);
            }

            $metadata = $order->metadata ?? [];
            $delivery = array_merge((array) data_get($metadata, 'delivery', []), array_filter([
                'pincode' => $attributes['delivery_pincode'] ?? null,
                'city' => $attributes['delivery_city'] ?? null,
                'address' => $attributes['delivery_address'] ?? null,
            ], fn ($value) => $value !== null));
            data_set($metadata, 'delivery', $delivery);

            $inventoryProcessed = (bool) data_get($metadata, 'inventory.processed', false);

            if ($oldStatus === 'pending' && $newStatus === 'processing' && ! $inventoryProcessed) {
                foreach ($order->items as $item) {
                    $product = $item->product ?: ($item->product_id ? Product::query()->find($item->product_id) : null);

                    if (! $product) {
                        continue;
                    }

                    $this->inventoryService->record(
                        $product,
                        'out',
                        (int) $item->quantity,
                        "Order {$order->order_number} moved to processing.",
                        $order,
                        [
                            'reason' => 'order_processing',
                            'order_item_id' => $item->id,
                        ]
                    );
                }

                data_set($metadata, 'inventory.processed', true);
                data_set($metadata, 'inventory.processed_at', now()->toIso8601String());
            }

            if ($newStatus === 'cancelled' && ! in_array($oldStatus, ['pending', 'cancelled'], true)) {
                data_set($metadata, 'inventory.cancellation_action', $cancellationInventoryAction);
                data_set($metadata, 'inventory.cancelled_at', now()->toIso8601String());

                foreach ($order->items as $item) {
                    $product = $item->product ?: ($item->product_id ? Product::query()->find($item->product_id) : null);

                    if (! $product) {
                        continue;
                    }

                    if ($cancellationInventoryAction === 'restock') {
                        $this->inventoryService->record(
                            $product,
                            'in',
                            (int) $item->quantity,
                            "Cancelled order {$order->order_number} restocked.",
                            $order,
                            [
                                'reason' => 'order_cancelled_restock',
                                'order_item_id' => $item->id,
                            ]
                        );
                    } else {
                        $this->inventoryService->record(
                            $product,
                            'out',
                            (int) $item->quantity,
                            "Damaged return for order {$order->order_number}.",
                            $order,
                            [
                                'reason' => 'order_cancelled_damaged',
                                'order_item_id' => $item->id,
                                'affects_stock' => false,
                            ],
                            false
                        );
                    }
                }
            }

            $updates = Arr::except($attributes, [
                'delivery_pincode',
                'delivery_city',
                'delivery_address',
            ]);

            $updates['metadata'] = $metadata;

            if ($newPaymentStatus === 'paid' && empty($updates['paid_at'])) {
                $updates['paid_at'] = now();
            }

            if ($newPaymentStatus !== 'paid') {
                $updates['paid_at'] = null;
            }

            $order->forceFill($updates)->save();
            $this->ensurePublicToken($order);

            if ($oldStatus !== $newStatus || $oldPaymentStatus !== $newPaymentStatus) {
                $this->notifyCustomer($order->fresh(['customer', 'store', 'conversation', 'items.product']));
            }

            return $order->fresh(['items.product', 'customer', 'store', 'conversation']);
        });
    }

    public function generateOrderNumber(Store $store): string
    {
        return DB::transaction(function () use ($store) {
            /** @var Store $lockedStore */
            $lockedStore = Store::query()->lockForUpdate()->findOrFail($store->id);
            $settings = $lockedStore->settings ?? [];
            $numbering = array_merge([
                'prefix' => strtoupper($lockedStore->slug ?: 'ORD'),
                'start_from' => 1,
                'digits' => 5,
                'type' => 'sequential',
                'next_sequence' => 1,
            ], (array) data_get($settings, 'order_numbering', []));

            $prefix = trim((string) $numbering['prefix']);
            $digits = max((int) $numbering['digits'], 1);
            $type = in_array($numbering['type'], ['sequential', 'random'], true) ? $numbering['type'] : 'sequential';

            if ($type === 'random') {
                do {
                    $maxRandom = (10 ** min($digits, 9)) - 1;
                    $suffix = str_pad((string) random_int(0, $maxRandom), $digits, '0', STR_PAD_LEFT);
                    $candidate = $prefix !== '' ? "{$prefix}-{$suffix}" : $suffix;
                } while (Order::query()->where('order_number', $candidate)->exists());

                return $candidate;
            }

            $currentSequence = max((int) $numbering['next_sequence'], (int) $numbering['start_from'], 1);
            $candidate = $prefix !== ''
                ? "{$prefix}-" . str_pad((string) $currentSequence, $digits, '0', STR_PAD_LEFT)
                : str_pad((string) $currentSequence, $digits, '0', STR_PAD_LEFT);

            $numbering['next_sequence'] = $currentSequence + 1;
            $settings['order_numbering'] = $numbering;

            $lockedStore->forceFill([
                'settings' => $settings,
            ])->save();

            return $candidate;
        });
    }

    protected function notifyCustomer(Order $order): void
    {
        if (! $order->customer || ! $order->store) {
            return;
        }

        $publicUrl = $this->publicUrl($order);
        $messageBody = implode("\n", [
            "Order: {$order->order_number}",
            'Status: ' . Str::headline($order->status),
            'Payment status: ' . Str::headline($order->payment_status),
        ]);

        $dispatch = $this->cloudApi->sendStructuredMessage($order->store, $order->customer, [
            'kind' => 'cta_url',
            'header_text' => 'Order Update',
            'body' => $messageBody,
            'footer' => 'Use the button below to open your order in the browser.',
            'button_text' => 'Track Order',
            'url' => $publicUrl,
        ]);
        $conversation = $order->conversation ?: $this->latestConversationForOrder($order);

        if (! $conversation) {
            return;
        }

        Message::create([
            'conversation_id' => $conversation->id,
            'direction' => 'outbound',
            'type' => 'interactive',
            'whatsapp_message_id' => $dispatch['message_id'] ?? null,
            'body' => implode("\n", [
                'Order Update',
                $messageBody,
                'Track order: ' . $publicUrl,
            ]),
            'payload' => $dispatch,
            'sent_at' => ($dispatch['dispatched'] ?? false) ? now() : null,
        ]);
    }

    protected function latestConversationForOrder(Order $order): ?Conversation
    {
        if (! $order->customer_id || ! $order->store_id) {
            return null;
        }

        return Conversation::query()
            ->where('customer_id', $order->customer_id)
            ->where('store_id', $order->store_id)
            ->latest('id')
            ->first();
    }
}
