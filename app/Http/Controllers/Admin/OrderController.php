<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesJsonInput;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    use HandlesJsonInput;

    public function index(): View
    {
        $orders = Order::with(['store', 'customer'])->withCount('items')->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function create(): View
    {
        $products = Product::orderBy('name')->get();

        return view('admin.orders.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $payload = $this->preparePayload($request, $validated);

        DB::transaction(function () use ($payload) {
            $order = Order::create($payload['order']);
            $order->items()->createMany($payload['items']);
        });

        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
    }

    public function edit(Order $order): View
    {
        $order->load('items');
        $products = Product::orderBy('name')->get();

        return view('admin.orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate($this->rules($order));
        $payload = $this->preparePayload($request, $validated, $order);

        DB::transaction(function () use ($order, $payload) {
            $order->update($payload['order']);
            $order->items()->delete();
            $order->items()->createMany($payload['items']);
        });

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }

    private function rules(?Order $order = null): array
    {
        return [
            'customer_id' => ['nullable', 'integer', 'min:1'],
            'conversation_id' => ['nullable', 'integer', 'min:1'],
            'cart_id' => ['nullable', 'integer', 'min:1'],
            'order_number' => ['nullable', 'string', 'max:255', Rule::unique('orders', 'order_number')->ignore($order?->id)],
            'status' => ['required', 'string', 'max:50'],
            'payment_status' => ['required', 'string', 'max:50'],
            'currency' => ['required', 'string', 'max:10'],
            'subtotal' => ['nullable', 'numeric', 'min:0'],
            'total' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'metadata' => ['nullable', 'string'],
            'placed_at' => ['nullable', 'date'],
            'paid_at' => ['nullable', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['nullable', 'integer', 'exists:products,id'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.sku' => ['nullable', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.total_price' => ['required', 'numeric', 'min:0'],
        ];
    }

    private function preparePayload(Request $request, array $validated, ?Order $order = null): array
    {
        $items = collect($validated['items'])->map(function (array $item) {
            return [
                'product_id' => $item['product_id'] ?: null,
                'product_name' => $item['product_name'],
                'sku' => $item['sku'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
            ];
        })->values()->all();

        $orderData = $validated;
        unset($orderData['items']);

        $orderData['metadata'] = $this->decodeOptionalJson($request, 'metadata');
        $orderData['order_number'] = $orderData['order_number'] ?: $this->generateOrderNumber($order?->id);
        $orderData['subtotal'] = $orderData['subtotal'] ?? collect($items)->sum('total_price');
        $orderData['total'] = $orderData['total'] ?? $orderData['subtotal'];
        $orderData['store_id'] = $order?->store_id ?: $this->resolveStoreIdFromItems($items);

        return [
            'order' => $orderData,
            'items' => $items,
        ];
    }

    private function resolveStoreIdFromItems(array $items): ?int
    {
        $productIds = collect($items)
            ->pluck('product_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values();

        if ($productIds->isNotEmpty()) {
            $storeId = Product::query()
                ->whereIn('id', $productIds)
                ->whereNotNull('store_id')
                ->value('store_id');

            if ($storeId) {
                return (int) $storeId;
            }
        }

        return Store::query()->where('is_active', true)->orderBy('id')->value('id')
            ?? Store::query()->orderBy('id')->value('id');
    }

    private function generateOrderNumber(?int $ignoreId = null): string
    {
        do {
            $number = 'ORD-' . strtoupper(Str::random(8));
        } while (
            Order::query()
                ->where('order_number', $number)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        );

        return $number;
    }
}
