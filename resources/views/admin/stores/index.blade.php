@extends('layouts.admin')

@section('title', 'Stores')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">WhatsApp Stores</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage Meta WhatsApp store credentials and storefront content.</p>
        </div>

        @can('create stores')
            <a href="{{ route('admin.stores.create') }}" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                Add Store
            </a>
        @endcan
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                    <tr>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Store</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Support</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Currency</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stores as $store)
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-5 py-4">
                                <div class="font-medium text-gray-800 dark:text-white/90">{{ $store->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $store->slug }}</div>
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $store->support_phone ?: '-' }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $store->currency }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $store->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $store->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @can('edit stores')
                                        <a href="{{ route('admin.stores.edit', $store) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">Edit</a>
                                    @endcan
                                    @can('delete stores')
                                        <form action="{{ route('admin.stores.destroy', $store) }}" method="POST" onsubmit="return confirm('Delete this store?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">No stores found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $stores->links() }}</div>
</div>
@endsection
