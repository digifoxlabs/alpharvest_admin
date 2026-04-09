@extends('layouts.admin')

@section('title', 'Inventory')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Inventory</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Track all In and Out stock movement across products.</p>
        </div>
        <a href="{{ route('admin.inventory.create') }}" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Add Inventory</a>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[280px]">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Product</label>
                <select name="product_id" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">All products</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected($filters['product_id'] === (string) $product->id)>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Apply Filter</button>
            <a href="{{ route('admin.inventory.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300">Reset</a>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                    <tr>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Product</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Type</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Quantity</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Previous</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">New</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Remarks</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-5 py-4 font-medium text-gray-800 dark:text-white/90">{{ $transaction->product?->name ?: 'Deleted product' }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ strtoupper($transaction->type) }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $transaction->quantity }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $transaction->previous_quantity }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $transaction->new_quantity }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $transaction->remarks ?: 'No remarks' }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $transaction->created_at?->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">No inventory transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $transactions->links() }}</div>
</div>
@endsection
