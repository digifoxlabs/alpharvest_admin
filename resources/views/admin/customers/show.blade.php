@extends('layouts.admin')

@section('title', 'Customer Details')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">{{ $customer->name ?: 'Unnamed customer' }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Customer profile, delivery metadata, and recent orders.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.customers.edit', $customer) }}" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Edit Customer</a>
            <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Delete this customer?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">Delete</button>
            </form>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Profile</h3>
            <dl class="mt-4 space-y-3 text-sm text-gray-600 dark:text-gray-300">
                <div><span class="font-medium text-gray-800 dark:text-white/90">Store:</span> {{ $customer->store?->name ?: 'No store' }}</div>
                <div><span class="font-medium text-gray-800 dark:text-white/90">Phone:</span> {{ $customer->phone }}</div>
                <div><span class="font-medium text-gray-800 dark:text-white/90">WhatsApp ID:</span> {{ $customer->whatsapp_id ?: 'Not available' }}</div>
                <div><span class="font-medium text-gray-800 dark:text-white/90">Preferred Language:</span> {{ strtoupper($customer->preferred_language ?: 'en') }}</div>
                <div><span class="font-medium text-gray-800 dark:text-white/90">Pincode:</span> {{ $customer->pincode ?: 'Not available' }}</div>
                <div><span class="font-medium text-gray-800 dark:text-white/90">Last Message:</span> {{ $customer->last_message_at?->format('Y-m-d H:i') ?: 'Never' }}</div>
            </dl>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Delivery Addresses</h3>
            @php($delivery = (array) data_get($customer->metadata, 'delivery', []))
            <div class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                <p><span class="font-medium text-gray-800 dark:text-white/90">Current Address:</span> {{ implode(', ', array_filter([$delivery['address'] ?? null, $delivery['city'] ?? null, $delivery['pincode'] ?? null])) ?: 'Not available' }}</p>
                <div class="mt-4">
                    <p class="font-medium text-gray-800 dark:text-white/90">Saved Address Book</p>
                    <ul class="mt-2 space-y-2">
                        @forelse ($customer->delivery_address_lines as $line)
                            <li>{{ $line }}</li>
                        @empty
                            <li>No saved addresses.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Orders</h3>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-3 py-3 font-medium text-gray-600 dark:text-gray-300">Order Number</th>
                        <th class="px-3 py-3 font-medium text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-3 py-3 font-medium text-gray-600 dark:text-gray-300">Payment</th>
                        <th class="px-3 py-3 font-medium text-gray-600 dark:text-gray-300">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customer->orders as $order)
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-3 py-3"><a href="{{ route('admin.orders.show', $order) }}" class="text-brand-600 hover:underline">{{ $order->order_number }}</a></td>
                            <td class="px-3 py-3 text-gray-600 dark:text-gray-300">{{ \Illuminate\Support\Str::headline($order->status) }}</td>
                            <td class="px-3 py-3 text-gray-600 dark:text-gray-300">{{ \Illuminate\Support\Str::headline($order->payment_status) }}</td>
                            <td class="px-3 py-3 text-gray-600 dark:text-gray-300">{{ number_format((float) $order->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 py-6 text-center text-gray-500 dark:text-gray-400">No orders found for this customer.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
