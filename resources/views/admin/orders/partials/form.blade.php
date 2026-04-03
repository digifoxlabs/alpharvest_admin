@php
    $existingItems = old('items', isset($order) && $order ? $order->items->map(fn ($item) => [
        'product_id' => $item->product_id,
        'product_name' => $item->product_name,
        'sku' => $item->sku,
        'quantity' => $item->quantity,
        'unit_price' => $item->unit_price,
        'total_price' => $item->total_price,
    ])->values()->all() : [['product_id' => '', 'product_name' => '', 'sku' => '', 'quantity' => 1, 'unit_price' => 0, 'total_price' => 0]]);
@endphp

<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
    <form action="{{ $action }}" method="POST" class="space-y-5" x-data="orderForm({{ \Illuminate\Support\Js::from($existingItems) }}, {{ \Illuminate\Support\Js::from($products->map(fn ($product) => ['id' => $product->id, 'name' => $product->name, 'sku' => $product->sku, 'price' => (float) ($product->sale_price ?: $product->price)])->values()) }})">
        @csrf
        @if ($method !== 'POST') @method($method) @endif

        <div class="grid gap-5 md:grid-cols-3">
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Customer ID</label><input type="number" name="customer_id" value="{{ old('customer_id', $order?->customer_id) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Conversation ID</label><input type="number" name="conversation_id" value="{{ old('conversation_id', $order?->conversation_id) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Cart ID</label><input type="number" name="cart_id" value="{{ old('cart_id', $order?->cart_id) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
        </div>

        <div class="grid gap-5 md:grid-cols-4">
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Order Number</label><input type="text" name="order_number" value="{{ old('order_number', $order?->order_number) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label><input type="text" name="status" value="{{ old('status', $order?->status ?? 'pending') }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Status</label><input type="text" name="payment_status" value="{{ old('payment_status', $order?->payment_status ?? 'pending') }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Currency</label><input type="text" name="currency" value="{{ old('currency', $order?->currency ?? 'INR') }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
        </div>

        <div class="grid gap-5 md:grid-cols-4">
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Subtotal</label><input type="number" step="0.01" name="subtotal" x-model="subtotal" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Total</label><input type="number" step="0.01" name="total" x-model="total" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Placed At</label><input type="datetime-local" name="placed_at" value="{{ old('placed_at', optional($order?->placed_at)->format('Y-m-d\\TH:i')) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Paid At</label><input type="datetime-local" name="paid_at" value="{{ old('paid_at', optional($order?->paid_at)->format('Y-m-d\\TH:i')) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
        </div>

        <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label><textarea name="notes" rows="3" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('notes', $order?->notes) }}</textarea></div>
        <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Metadata JSON</label><textarea name="metadata" rows="4" class="w-full rounded-xl border border-gray-200 px-4 py-3 font-mono text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('metadata', isset($order) && $order?->metadata ? json_encode($order->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '') }}</textarea>@error('metadata')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</div>

        <div class="space-y-4 rounded-2xl border border-gray-200 p-4 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Order Items</h3>
                <button type="button" @click="addItem()" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">Add Item</button>
            </div>

            <template x-for="(item, index) in items" :key="index">
                <div class="grid gap-4 rounded-xl border border-gray-200 p-4 dark:border-gray-700 lg:grid-cols-6">
                    <div>
                        <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-300">Product</label>
                        <select :name="`items[${index}][product_id]`" x-model="item.product_id" @change="fillFromProduct(index)" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="">Manual</option>
                            <template x-for="product in products" :key="product.id">
                                <option :value="product.id" x-text="product.name"></option>
                            </template>
                        </select>
                    </div>
                    <div><label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-300">Product Name</label><input :name="`items[${index}][product_name]`" x-model="item.product_name" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
                    <div><label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-300">SKU</label><input :name="`items[${index}][sku]`" x-model="item.sku" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
                    <div><label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-300">Qty</label><input type="number" min="1" :name="`items[${index}][quantity]`" x-model.number="item.quantity" @input="recalculateItem(index)" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
                    <div><label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-300">Unit Price</label><input type="number" step="0.01" min="0" :name="`items[${index}][unit_price]`" x-model.number="item.unit_price" @input="recalculateItem(index)" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
                    <div>
                        <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-300">Total Price</label>
                        <div class="flex gap-2">
                            <input type="number" step="0.01" min="0" :name="`items[${index}][total_price]`" x-model.number="item.total_price" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <button type="button" @click="removeItem(index)" class="rounded-lg border border-red-200 px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50">Remove</button>
                        </div>
                    </div>
                </div>
            </template>

            @error('items')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            @error('items.*.product_name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-3"><button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Save Order</button><a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300">Cancel</a></div>
    </form>
</div>

@push('scripts')
<script>
    function orderForm(initialItems, products) {
        const normalizeItem = (item) => ({
            product_id: item.product_id ?? '',
            product_name: item.product_name ?? '',
            sku: item.sku ?? '',
            quantity: Number(item.quantity ?? 1),
            unit_price: Number(item.unit_price ?? 0),
            total_price: Number(item.total_price ?? 0),
        });

        const items = (initialItems?.length ? initialItems : [normalizeItem({})]).map(normalizeItem);
        const subtotal = items.reduce((sum, item) => sum + Number(item.total_price || 0), 0);

        return {
            items,
            products,
            subtotal,
            total: subtotal,
            addItem() {
                this.items.push(normalizeItem({}));
            },
            removeItem(index) {
                if (this.items.length === 1) {
                    this.items = [normalizeItem({})];
                } else {
                    this.items.splice(index, 1);
                }
                this.recalculateTotals();
            },
            fillFromProduct(index) {
                const selected = this.products.find((product) => String(product.id) === String(this.items[index].product_id));
                if (!selected) return;
                this.items[index].product_name = selected.name;
                this.items[index].sku = selected.sku;
                this.items[index].unit_price = Number(selected.price || 0);
                this.recalculateItem(index);
            },
            recalculateItem(index) {
                const item = this.items[index];
                item.total_price = Number(item.quantity || 0) * Number(item.unit_price || 0);
                this.recalculateTotals();
            },
            recalculateTotals() {
                const total = this.items.reduce((sum, item) => sum + Number(item.total_price || 0), 0);
                this.subtotal = total;
                this.total = total;
            }
        }
    }
</script>
@endpush
