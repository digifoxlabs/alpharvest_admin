@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Products</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage items that will be synced to your Meta WhatsApp store.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @can('view products')
                <a href="{{ route('admin.products.export', request()->query()) }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">Export to Excel</a>
            @endcan
            @can('create products')
                <a href="{{ route('admin.products.create') }}" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Add Product</a>
            @endcan
        </div>
    </div>

    <div class="mb-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Products</p>
            <h3 class="mt-2 text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $stats['total_products'] }}</h3>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Categories</p>
            <h3 class="mt-2 text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $stats['total_categories'] }}</h3>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Active Products</p>
            <h3 class="mt-2 text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $stats['active_products'] }}</h3>
        </div>
    </div>

    <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div class="xl:col-span-2">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Search by product name or SKU" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                <select name="category_id" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected($filters['category_id'] === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">All statuses</option>
                    <option value="active" @selected($filters['status'] === 'active')>Active</option>
                    <option value="inactive" @selected($filters['status'] === 'inactive')>Inactive</option>
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Featured</label>
                <select name="featured" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">All products</option>
                    <option value="featured" @selected($filters['featured'] === 'featured')>Featured only</option>
                    <option value="non_featured" @selected($filters['featured'] === 'non_featured')>Non-featured only</option>
                </select>
            </div>
            <div class="flex items-end gap-3 xl:col-span-5">
                <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Apply Filters</button>
                <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300">Reset</a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                    <tr>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Sl No</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Product</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Category</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Price</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Inventory</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 overflow-hidden rounded-xl border border-gray-200 bg-gray-100 dark:border-gray-700 dark:bg-gray-800">
                                        <img src="{{ $product->image_url ?: asset('images/admin/src/images/user/owner.jpg') }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-800 dark:text-white/90">{{ $product->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $product->sku }} | {{ $product->slug }}</div>
                                        @if ($product->is_featured)
                                            <span class="mt-1 inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700">Featured</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $product->category?->name ?: '-' }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ number_format((float) $product->price, 2) }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $product->inventory_quantity }}</td>
                            <td class="px-5 py-4"><div class="flex flex-wrap gap-2">@can('edit products')<a href="{{ route('admin.products.edit', $product) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">Edit</a>@endcan @can('delete products')<form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')">@csrf @method('DELETE')<button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">Delete</button></form>@endcan</div></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">No products found for the selected filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection
