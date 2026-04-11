@extends('layouts.admin')

@section('title', 'Inventory')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Inventory</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Track all In and Out stock movement across products.</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('admin.inventory.index', ['scope' => 'active']) }}" class="rounded-lg px-4 py-2 text-sm font-medium {{ $scope === 'active' ? 'bg-brand-500 text-white' : 'border border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300' }}">Active</a>
                <a href="{{ route('admin.inventory.index', ['scope' => 'trashed']) }}" class="rounded-lg px-4 py-2 text-sm font-medium {{ $scope === 'trashed' ? 'bg-brand-500 text-white' : 'border border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300' }}">Trash</a>
            </div>
        </div>
        @if ($scope !== 'trashed')
            <a href="{{ route('admin.inventory.create') }}" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Add Inventory</a>
        @endif
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <input type="hidden" name="scope" value="{{ $scope }}">
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
            <a href="{{ route('admin.inventory.index', ['scope' => $scope]) }}" class="text-sm font-medium text-gray-600 dark:text-gray-300">Reset</a>
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
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Actions</th>
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
                            <td class="px-5 py-4">
                                @can('delete inventory')
                                    @if ($scope !== 'trashed')
                                        <form action="{{ route('admin.inventory.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Archive this inventory transaction?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">Archive</button>
                                        </form>
                                    @else
                                        <div class="flex flex-wrap gap-2">
                                            <form action="{{ route('admin.inventory.restore', $transaction->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="rounded-lg border border-green-200 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-50">Restore</button>
                                            </form>
                                            <form action="{{ route('admin.inventory.force-delete', $transaction->id) }}" method="POST" onsubmit="return confirm('Permanently delete this inventory transaction? This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">Delete Permanently</button>
                                            </form>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400">No action</span>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">{{ $scope === 'trashed' ? 'Trash is empty.' : 'No inventory transactions found.' }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $transactions->links() }}</div>
</div>
@endsection
