@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Reports</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Sales and customer satisfaction metrics for {{ strtolower($period['label']) }}.</p>
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ $period['display_range'] }}</p>
        </div>

        <form method="GET" action="{{ route('admin.reports.index') }}" class="min-w-[220px]">
            <label for="range" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Date range</label>
            <select id="range" name="range" onchange="this.form.submit()"
                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                @foreach ($rangeOptions as $value => $label)
                    <option value="{{ $value }}" @selected($selectedRange === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</p>
            <h3 class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white/90">Rs {{ number_format($metrics['revenue'], 2) }}</h3>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Transactions</p>
            <h3 class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white/90">{{ number_format($metrics['transactions']) }}</h3>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Average Order Value</p>
            <h3 class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white/90">Rs {{ number_format($metrics['aov'], 2) }}</h3>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Conversion Rate</p>
            <h3 class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white/90">{{ number_format($metrics['conversion_rate'], 2) }}%</h3>
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Based on conversation-to-order conversions.</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Repeat Customers</p>
            <h3 class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white/90">{{ number_format($metrics['repeat_customers']) }}</h3>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Net Promoter Score</p>
            <h3 class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white/90">{{ number_format($metrics['nps'], 2) }}</h3>
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ $metrics['nps_response_count'] }} responses</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Promoters</p>
            <h3 class="mt-2 text-3xl font-semibold text-green-600">{{ number_format($metrics['promoters']) }}</h3>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Detractors</p>
            <h3 class="mt-2 text-3xl font-semibold text-red-600">{{ number_format($metrics['detractors']) }}</h3>
        </div>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Sales Trend</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Revenue over the selected range.</p>
            </div>
            <div class="h-80">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Order Status Mix</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Distribution of active sales orders.</p>
            </div>
            <div class="h-80">
                <canvas id="statusBreakdownChart"></canvas>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Top 10 Products</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Highest revenue contributors.</p>
            </div>
            <div class="h-80">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">NPS Score Distribution</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Customer ratings collected after delivery.</p>
            </div>
            <div class="h-80">
                <canvas id="npsDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Top Performing Products</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Product</th>
                            <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Units Sold</th>
                            <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tables['top_products'] as $product)
                            <tr class="border-t border-gray-100 dark:border-gray-800">
                                <td class="px-5 py-4 text-gray-700 dark:text-gray-300">{{ $product->product_name }}</td>
                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ number_format((int) $product->units_sold) }}</td>
                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">Rs {{ number_format((float) $product->revenue, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">No product sales found for this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Latest NPS Responses</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Order</th>
                            <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Customer</th>
                            <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Score</th>
                            <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Responded At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tables['nps_responses'] as $feedback)
                            <tr class="border-t border-gray-100 dark:border-gray-800">
                                <td class="px-5 py-4 text-gray-700 dark:text-gray-300">{{ $feedback->order?->order_number ?: 'N/A' }}</td>
                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $feedback->customer?->name ?: $feedback->customer?->phone ?: 'Guest' }}</td>
                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $feedback->score }}/10</td>
                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ optional($feedback->responded_at)->format('d M Y, h:i A') ?: 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">No feedback responses found for this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const reportCharts = @json($chartData);

    const sharedGridColor = 'rgba(148, 163, 184, 0.15)';
    const sharedTickColor = '#64748b';

    new Chart(document.getElementById('salesTrendChart'), {
        type: 'line',
        data: {
            labels: reportCharts.salesTrend.labels,
            datasets: [{
                label: 'Revenue',
                data: reportCharts.salesTrend.values,
                borderColor: '#0f766e',
                backgroundColor: 'rgba(15, 118, 110, 0.18)',
                fill: true,
                tension: 0.35,
                pointRadius: 3
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: sharedGridColor }, ticks: { color: sharedTickColor } },
                y: { grid: { color: sharedGridColor }, ticks: { color: sharedTickColor } }
            }
        }
    });

    new Chart(document.getElementById('statusBreakdownChart'), {
        type: 'doughnut',
        data: {
            labels: reportCharts.statusBreakdown.labels,
            datasets: [{
                data: reportCharts.statusBreakdown.values,
                backgroundColor: ['#f59e0b', '#2563eb', '#7c3aed', '#16a34a']
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    new Chart(document.getElementById('topProductsChart'), {
        type: 'bar',
        data: {
            labels: reportCharts.topProducts.labels,
            datasets: [{
                label: 'Revenue',
                data: reportCharts.topProducts.values,
                backgroundColor: '#ea580c',
                borderRadius: 8
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: sharedTickColor } },
                y: { grid: { color: sharedGridColor }, ticks: { color: sharedTickColor } }
            }
        }
    });

    new Chart(document.getElementById('npsDistributionChart'), {
        type: 'bar',
        data: {
            labels: reportCharts.npsDistribution.labels,
            datasets: [{
                label: 'Responses',
                data: reportCharts.npsDistribution.values,
                backgroundColor: '#0891b2',
                borderRadius: 8
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: sharedTickColor } },
                y: { grid: { color: sharedGridColor }, ticks: { color: sharedTickColor }, beginAtZero: true }
            }
        }
    });
</script>
@endpush
