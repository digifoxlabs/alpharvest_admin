@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Products</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage items that will be synced to your Meta WhatsApp store.</p>
        </div>
        @can('create products')
            <a href="{{ route('admin.products.create') }}" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Add Product</a>
        @endcan
    </div>
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                    <tr>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Product</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Category</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Price</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Inventory</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="border-b border-gray-100 dark:border-gray-800">
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
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ number_format((float) $product->price, 2) }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $product->inventory_quantity }}</td>
                            <td class="px-5 py-4"><div class="flex flex-wrap gap-2">@can('edit products')<a href="{{ route('admin.products.edit', $product) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">Edit</a>@endcan @can('delete products')<form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')">@csrf @method('DELETE')<button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">Delete</button></form>@endcan</div></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">No products found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection
