@extends('layouts.admin')

@section('title', 'Add Inventory')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Add Inventory</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Select a product, choose whether stock is moving In or Out, and capture remarks for the change.</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <form action="{{ route('admin.inventory.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Product Name</label>
                <select name="product_id" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">Select product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->name }} (Current stock: {{ $product->inventory_quantity }})</option>
                    @endforeach
                </select>
                @error('product_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Transaction Type</label>
                    <select name="type" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="in" @selected(old('type') === 'in')>In</option>
                        <option value="out" @selected(old('type') === 'out')>Out</option>
                    </select>
                    @error('type')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                    <input type="number" min="1" name="quantity" value="{{ old('quantity', 1) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    @error('quantity')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Remarks</label>
                <textarea name="remarks" rows="4" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('remarks') }}</textarea>
                @error('remarks')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Save Transaction</button>
                <a href="{{ route('admin.inventory.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
