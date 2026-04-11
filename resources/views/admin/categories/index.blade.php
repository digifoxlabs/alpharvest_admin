@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Product Categories</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Organize WhatsApp store products into browsable groups.</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('admin.categories.index', ['scope' => 'active']) }}" class="rounded-lg px-4 py-2 text-sm font-medium {{ $scope === 'active' ? 'bg-brand-500 text-white' : 'border border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300' }}">Active</a>
                <a href="{{ route('admin.categories.index', ['scope' => 'trashed']) }}" class="rounded-lg px-4 py-2 text-sm font-medium {{ $scope === 'trashed' ? 'bg-brand-500 text-white' : 'border border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300' }}">Trash</a>
            </div>
        </div>
        @can('create categories')
            @if ($scope !== 'trashed')
            <a href="{{ route('admin.categories.create') }}" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Add Category</a>
            @endif
        @endcan
    </div>
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                    <tr>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Category</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Sort Order</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-5 py-4"><div class="font-medium text-gray-800 dark:text-white/90">{{ $category->name }}</div><div class="text-xs text-gray-500 dark:text-gray-400">{{ $category->slug }}</div></td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $category->sort_order }}</td>
                            <td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @if ($scope !== 'trashed')
                                        @can('edit categories')<a href="{{ route('admin.categories.edit', $category) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">Edit</a>@endcan
                                        @can('delete categories')
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Archive this category?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">Archive</button>
                                            </form>
                                        @endcan
                                    @else
                                        @can('delete categories')
                                            <form action="{{ route('admin.categories.restore', $category->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="rounded-lg border border-green-200 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-50">Restore</button>
                                            </form>
                                            <form action="{{ route('admin.categories.force-delete', $category->id) }}" method="POST" onsubmit="return confirm('Permanently delete this category? This cannot be undone.')">
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
                        <tr><td colspan="4" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">{{ $scope === 'trashed' ? 'Trash is empty.' : 'No categories found.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $categories->links() }}</div>
</div>
@endsection
