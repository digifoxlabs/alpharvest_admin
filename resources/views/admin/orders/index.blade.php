@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Orders</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Review WhatsApp cart orders and maintain order items.</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('admin.orders.index', ['scope' => 'active']) }}" class="rounded-lg px-4 py-2 text-sm font-medium {{ $scope === 'active' ? 'bg-brand-500 text-white' : 'border border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300' }}">Active</a>
                <a href="{{ route('admin.orders.index', ['scope' => 'trashed']) }}" class="rounded-lg px-4 py-2 text-sm font-medium {{ $scope === 'trashed' ? 'bg-brand-500 text-white' : 'border border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300' }}">Trash</a>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @can('view orders')
                @if ($scope !== 'trashed')
                <a href="{{ route('admin.orders.export', request()->query()) }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">Export to Excel</a>
                @endif
            @endcan
            @can('create orders')
                @if ($scope !== 'trashed')
                <a href="{{ route('admin.orders.create') }}" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Add Order</a>
                @endif
            @endcan
        </div>
    </div>

    <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <input type="hidden" name="scope" value="{{ $scope }}">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Search Order Number</label>
                <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Search by order number" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Order Status</label>
                <select name="status" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">All statuses</option>
                    @foreach (['pending', 'processing', 'dispatched', 'delivered', 'cancelled'] as $status)
                        <option value="{{ $status }}" @selected($filters['status'] === $status)>{{ \Illuminate\Support\Str::headline($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Status</label>
                <select name="payment_status" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">All payment statuses</option>
                    @foreach (['pending', 'paid', 'failed', 'refunded'] as $paymentStatus)
                        <option value="{{ $paymentStatus }}" @selected($filters['payment_status'] === $paymentStatus)>{{ \Illuminate\Support\Str::headline($paymentStatus) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Apply Filters</button>
                <a href="{{ route('admin.orders.index', ['scope' => $scope]) }}" class="text-sm font-medium text-gray-600 dark:text-gray-300">Reset</a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                    <tr>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Sl No</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Order</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Payment</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Total</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Items</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Date</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                            <td class="px-5 py-4"><div class="font-medium text-gray-800 dark:text-white/90"><a href="{{ route('admin.orders.show', $order) }}" class="text-brand-600 hover:underline">{{ $order->order_number }}</a></div><div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->currency }} | {{ optional($order->placed_at)->format('Y-m-d H:i') ?: 'Not placed' }}</div></td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ ucfirst($order->status) }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ ucfirst($order->payment_status) }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ number_format((float) $order->total, 2) }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $order->items_count }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ optional($order->placed_at ?: $order->created_at)->format('Y-m-d H:i') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @if ($scope !== 'trashed')
                                        @can('view orders')
                                            <a href="{{ route('admin.orders.show', $order) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">View</a>
                                        @endcan
                                        @can('delete orders')
                                            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Archive this order?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">Archive</button>
                                            </form>
                                        @endcan
                                    @else
                                        @can('delete orders')
                                            <form action="{{ route('admin.orders.restore', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="rounded-lg border border-green-200 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-50">Restore</button>
                                            </form>
                                            <form action="{{ route('admin.orders.force-delete', $order->id) }}" method="POST" onsubmit="return confirm('Permanently delete this order? This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">Delete Permanently</button>
                                            </form>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">{{ $scope === 'trashed' ? 'Trash is empty.' : 'No orders found for the selected filters.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
