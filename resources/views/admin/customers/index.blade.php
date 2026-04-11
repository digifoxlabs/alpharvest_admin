@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Customers</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Review all customers and drill into their profile, addresses, and order activity.</p>
        <div class="mt-4 flex flex-wrap gap-2">
            <a href="{{ route('admin.customers.index', ['scope' => 'active']) }}" class="rounded-lg px-4 py-2 text-sm font-medium {{ $scope === 'active' ? 'bg-brand-500 text-white' : 'border border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300' }}">Active</a>
            <a href="{{ route('admin.customers.index', ['scope' => 'trashed']) }}" class="rounded-lg px-4 py-2 text-sm font-medium {{ $scope === 'trashed' ? 'bg-brand-500 text-white' : 'border border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300' }}">Trash</a>
        </div>
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
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Actions</th>
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
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @if ($scope !== 'trashed')
                                        @can('view customers')
                                            <a href="{{ route('admin.customers.show', $customer) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">View</a>
                                        @endcan
                                        @can('delete customers')
                                            <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Archive this customer?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">Archive</button>
                                            </form>
                                        @endcan
                                    @else
                                        @can('delete customers')
                                            <form action="{{ route('admin.customers.restore', $customer->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="rounded-lg border border-green-200 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-50">Restore</button>
                                            </form>
                                            <form action="{{ route('admin.customers.force-delete', $customer->id) }}" method="POST" onsubmit="return confirm('Permanently delete this customer? This cannot be undone.')">
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
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">{{ $scope === 'trashed' ? 'Trash is empty.' : 'No customers found.' }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $customers->links() }}</div>
</div>
@endsection
