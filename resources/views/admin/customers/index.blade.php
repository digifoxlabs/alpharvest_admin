@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Customers</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Review all customers and drill into their profile, addresses, and order activity.</p>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                    <tr>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Customer</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Store</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Phone</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Language</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Orders</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Last Message</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="font-medium text-brand-600 hover:underline">
                                    {{ $customer->name ?: 'Unnamed customer' }}
                                </a>
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $customer->store?->name ?: 'No store' }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $customer->phone }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ strtoupper($customer->preferred_language ?: 'en') }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $customer->orders_count }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $customer->last_message_at?->format('Y-m-d H:i') ?: 'Never' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $customers->links() }}</div>
</div>
@endsection
