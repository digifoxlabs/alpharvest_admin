@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Orders</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Review WhatsApp cart orders and maintain order items.</p>
        </div>
        @can('create orders')
            <a href="{{ route('admin.orders.create') }}" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Add Order</a>
        @endcan
    </div>
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                    <tr>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Order</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Payment</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Total</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Items</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-5 py-4"><div class="font-medium text-gray-800 dark:text-white/90"><a href="{{ route('admin.orders.show', $order) }}" class="text-brand-600 hover:underline">{{ $order->order_number }}</a></div><div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->currency }} | {{ optional($order->placed_at)->format('Y-m-d H:i') ?: 'Not placed' }}</div></td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ ucfirst($order->status) }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ ucfirst($order->payment_status) }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ number_format((float) $order->total, 2) }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $order->items_count }}</td>
                            <td class="px-5 py-4"><div class="flex flex-wrap gap-2">@can('view orders')<a href="{{ route('admin.orders.show', $order) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">View</a>@endcan @can('delete orders')<form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Delete this order?')">@csrf @method('DELETE')<button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">Delete</button></form>@endcan</div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">No orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
