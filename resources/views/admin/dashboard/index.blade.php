@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Admin Dashboard</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Track order progress, review delivery details, and export the current order list.</p>
        </div>

        @can('view dashboard')
        <a href="{{ route('admin.dashboard.export.orders', request()->query()) }}"
            class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
            Export to Excel
        </a>
        @endcan
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Pending Orders</p>
            <h3 class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white/90">{{ $stats['pending'] }}</h3>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Processing Orders</p>
            <h3 class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white/90">{{ $stats['processing'] }}</h3>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Dispatched Orders</p>
            <h3 class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white/90">{{ $stats['dispatched'] }}</h3>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Delivered Orders</p>
            <h3 class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white/90">{{ $stats['delivered'] }}</h3>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[220px]">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Status Filter</label>
                <select name="status" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">All statuses</option>
                    @foreach (['pending', 'processing', 'dispatched', 'delivered', 'cancelled'] as $status)
                        <option value="{{ $status }}" @selected($filters['status'] === $status)>{{ \Illuminate\Support\Str::headline($status) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Apply Filter</button>
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300">Reset</a>
        </form>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                    <tr>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Sl No</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Order Number</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Customer Name</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Payment Status</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Delivery Address</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Total Amt</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        @php($delivery = (array) data_get($order->metadata, 'delivery', []))
                        <tr class="border-b border-gray-100 align-top dark:border-gray-800">
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-brand-600 hover:underline">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $order->customer?->name ?: $order->customer?->phone ?: 'Guest' }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ \Illuminate\Support\Str::headline($order->status) }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ \Illuminate\Support\Str::headline($order->payment_status) }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                                {{ implode(', ', array_filter([$delivery['address'] ?? null, $delivery['city'] ?? null, $delivery['pincode'] ?? null])) ?: 'Not available' }}
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ number_format((float) $order->total, 2) }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ optional($order->placed_at ?: $order->created_at)->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">No orders found for the selected filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection

@push('scripts')
<script>
    window.pageXData = {
        page: 'dashboard',
    };
</script>
@endpush
