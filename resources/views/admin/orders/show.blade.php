@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
@php($delivery = (array) data_get($order->metadata, 'delivery', []))
<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">{{ $order->order_number }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Review order details, customer information, stock, and status updates.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ $publicUrl }}" target="_blank" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">Open Public Order Page</a>
            <button type="button" id="copyPublicOrderLink" data-public-url="{{ $publicUrl }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">Copy Link</button>
            <a href="{{ route('admin.orders.index') }}" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Back to Orders</a>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Order Items</h3>
                <div class="mt-4 space-y-4">
                    @foreach ($order->items as $item)
                        <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white/90">{{ $item->product_name }}</p>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">SKU: {{ $item->sku ?: 'Not available' }}</p>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Inventory Quantity: {{ $item->product?->inventory_quantity ?? 'Not available' }}</p>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-300">
                                    <p>Quantity: {{ $item->quantity }}</p>
                                    <p class="mt-1">Unit Price: {{ number_format((float) $item->unit_price, 2) }}</p>
                                    <p class="mt-1">Total: {{ number_format((float) $item->total_price, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Customer Details</h3>
                <div class="mt-4 grid gap-4 md:grid-cols-2 text-sm text-gray-600 dark:text-gray-300">
                    <div><span class="font-medium text-gray-800 dark:text-white/90">Customer:</span> {{ $order->customer?->name ?: 'Guest' }}</div>
                    <div><span class="font-medium text-gray-800 dark:text-white/90">Phone:</span> {{ $order->customer?->phone ?: 'Not available' }}</div>
                    <div><span class="font-medium text-gray-800 dark:text-white/90">Store:</span> {{ $order->store?->name ?: 'Not available' }}</div>
                    <div><span class="font-medium text-gray-800 dark:text-white/90">Placed At:</span> {{ optional($order->placed_at ?: $order->created_at)->format('Y-m-d H:i') }}</div>
                </div>
                <div class="mt-4 rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    <p class="font-medium text-gray-800 dark:text-white/90">Complete Delivery Address</p>
                    <p class="mt-2">Pincode: {{ $delivery['pincode'] ?? 'Not available' }}</p>
                    <p class="mt-1">City: {{ $delivery['city'] ?? 'Not available' }}</p>
                    <p class="mt-1 whitespace-pre-wrap">Address: {{ $delivery['address'] ?? 'Not available' }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Update Order</h3>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="mt-4 space-y-5" id="orderUpdateForm">
                @csrf
                @method('PUT')

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Order Status</label>
                        <select name="status" id="orderStatusField" data-current-status="{{ $order->status }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            @foreach (['pending', 'processing', 'dispatched', 'delivered', 'cancelled'] as $status)
                                <option value="{{ $status }}" @selected(old('status', $order->status) === $status)>{{ \Illuminate\Support\Str::headline($status) }}</option>
                            @endforeach
                        </select>
                        @error('status')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Status</label>
                        <select name="payment_status" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            @foreach (['pending', 'paid', 'failed', 'refunded'] as $paymentStatus)
                                <option value="{{ $paymentStatus }}" @selected(old('payment_status', $order->payment_status) === $paymentStatus)>{{ \Illuminate\Support\Str::headline($paymentStatus) }}</option>
                            @endforeach
                        </select>
                        @error('payment_status')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="space-y-5 rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">Delivery Address</p>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Pincode</label>
                            <input type="text" name="delivery_pincode" value="{{ old('delivery_pincode', $delivery['pincode'] ?? '') }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                            <input type="text" name="delivery_city" value="{{ old('delivery_city', $delivery['city'] ?? '') }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        </div>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                        <textarea name="delivery_address" rows="4" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('delivery_address', $delivery['address'] ?? '') }}</textarea>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                    <textarea name="notes" rows="4" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('notes', $order->notes) }}</textarea>
                </div>

                @error('cancellation_inventory_action')<p class="text-sm text-red-600">{{ $message }}</p>@enderror

                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    <p><span class="font-medium text-gray-800 dark:text-white/90">Subtotal:</span> {{ number_format((float) $order->subtotal, 2) }}</p>
                    <p class="mt-1"><span class="font-medium text-gray-800 dark:text-white/90">Total:</span> {{ number_format((float) $order->total, 2) }}</p>
                </div>

                <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Save Order Update</button>
            </form>
        </div>
    </div>
</div>

<div id="cancelInventoryModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-gray-900/60 px-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Handle Cancelled Order Stock</h3>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">This order already moved beyond pending. Choose how the stock should be handled before saving.</p>

        <div class="mt-5 space-y-3">
            <label class="flex items-start gap-3 rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                <input type="radio" name="cancellation_inventory_action" value="restock" form="orderUpdateForm" checked>
                <span class="text-sm text-gray-600 dark:text-gray-300"><span class="font-medium text-gray-800 dark:text-white/90">Add stock back to inventory</span><br>Creates In inventory transactions and increases product inventory.</span>
            </label>
            <label class="flex items-start gap-3 rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                <input type="radio" name="cancellation_inventory_action" value="damaged" form="orderUpdateForm">
                <span class="text-sm text-gray-600 dark:text-gray-300"><span class="font-medium text-gray-800 dark:text-white/90">Mark as damaged goods</span><br>Creates damaged return Out transactions without adding stock back.</span>
            </label>
        </div>

        <div class="mt-6 flex items-center justify-end gap-3">
            <button type="button" id="cancelInventoryClose" class="text-sm font-medium text-gray-600 dark:text-gray-300">Close</button>
            <button type="button" id="cancelInventoryConfirm" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Continue</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const form = document.getElementById('orderUpdateForm');
    const statusField = document.getElementById('orderStatusField');
    const modal = document.getElementById('cancelInventoryModal');
    const closeButton = document.getElementById('cancelInventoryClose');
    const confirmButton = document.getElementById('cancelInventoryConfirm');
    const copyButton = document.getElementById('copyPublicOrderLink');

    if (!form || !statusField || !modal) {
        return;
    }

    form.addEventListener('submit', (event) => {
        const currentStatus = statusField.dataset.currentStatus;
        const nextStatus = statusField.value;

        if (nextStatus === 'cancelled' && currentStatus !== 'pending' && currentStatus !== 'cancelled' && modal.classList.contains('hidden')) {
            event.preventDefault();
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    });

    closeButton?.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    confirmButton?.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        form.submit();
    });

    copyButton?.addEventListener('click', async () => {
        const originalText = copyButton.textContent;
        const url = copyButton.dataset.publicUrl;

        if (!url) {
            return;
        }

        try {
            await navigator.clipboard.writeText(url);
            copyButton.textContent = 'Copied';
        } catch (error) {
            copyButton.textContent = 'Copy Failed';
        }

        window.setTimeout(() => {
            copyButton.textContent = originalText;
        }, 1500);
    });
})();
</script>
@endpush
