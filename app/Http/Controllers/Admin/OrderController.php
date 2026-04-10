<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesJsonInput;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Services\OrderWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    use HandlesJsonInput;

    public function __construct(
        protected OrderWorkflowService $orderWorkflowService
    ) {
    }

    public function index(Request $request): View
    {
        $filters = [
            'search' => trim((string) $request->input('search', '')),
            'status' => (string) $request->input('status', ''),
            'payment_status' => (string) $request->input('payment_status', ''),
        ];

        $orders = $this->filteredOrdersQuery($filters)
            ->with(['store', 'customer'])
            ->withCount('items')
            ->latest('placed_at')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'filters' => $filters,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = [
            'search' => trim((string) $request->input('search', '')),
            'status' => (string) $request->input('status', ''),
            'payment_status' => (string) $request->input('payment_status', ''),
        ];

        $orders = $this->filteredOrdersQuery($filters)
            ->with(['store', 'customer'])
            ->withCount('items')
            ->latest('placed_at')
            ->latest('id')
            ->get();

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Sl No', 'Order Number', 'Customer', 'Status', 'Payment Status', 'Currency', 'Total', 'Items', 'Placed At']);

            foreach ($orders as $index => $order) {
                fputcsv($handle, [
                    $index + 1,
                    $order->order_number,
                    $order->customer?->name ?: $order->customer?->phone ?: 'Guest',
                    $order->status,
                    $order->payment_status,
                    $order->currency,
                    number_format((float) $order->total, 2, '.', ''),
                    $order->items_count,
                    optional($order->placed_at ?: $order->created_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, 'orders-export.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function create(): View
    {
        $products = Product::orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();

        return view('admin.orders.create', compact('products', 'customers'));
    }

    public function show(Order $order): View
    {
        $order->load(['items.product', 'customer', 'store', 'conversation']);
        $this->orderWorkflowService->ensurePublicToken($order);

        return view('admin.orders.show', [
            'order' => $order->fresh(['items.product', 'customer', 'store', 'conversation']),
            'publicUrl' => $this->orderWorkflowService->publicUrl($order),
        ]);
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
        return $this->show($order);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate($this->updateRules());

        $updatedOrder = $this->orderWorkflowService->updateOrder($order, [
            'status' => $validated['status'],
            'payment_status' => $validated['payment_status'],
            'notes' => $validated['notes'] ?? null,
            'delivery_pincode' => $validated['delivery_pincode'] ?? null,
            'delivery_city' => $validated['delivery_city'] ?? null,
            'delivery_address' => $validated['delivery_address'] ?? null,
        ], $validated['cancellation_inventory_action'] ?? null);

        return redirect()->route('admin.orders.show', $updatedOrder)->with('success', 'Order updated successfully.');
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
            'status' => ['required', Rule::in(['pending', 'processing', 'dispatched', 'delivered', 'cancelled'])],
            'payment_status' => ['required', Rule::in(['pending', 'paid', 'failed', 'refunded'])],
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

    private function filteredOrdersQuery(array $filters)
    {
        $search = $filters['search'] ?? '';
        $status = $filters['status'] ?? '';
        $paymentStatus = $filters['payment_status'] ?? '';

        return Order::query()
            ->when($search !== '', fn ($query) => $query->where('order_number', 'like', "%{$search}%"))
            ->when(
                in_array($status, ['pending', 'processing', 'dispatched', 'delivered', 'cancelled'], true),
                fn ($query) => $query->where('status', $status)
            )
            ->when(
                in_array($paymentStatus, ['pending', 'paid', 'failed', 'refunded'], true),
                fn ($query) => $query->where('payment_status', $paymentStatus)
            );
    }

    private function updateRules(): array
    {
        return [
            'status' => ['required', Rule::in(['pending', 'processing', 'dispatched', 'delivered', 'cancelled'])],
            'payment_status' => ['required', Rule::in(['pending', 'paid', 'failed', 'refunded'])],
            'notes' => ['nullable', 'string'],
            'delivery_pincode' => ['nullable', 'string', 'max:20'],
            'delivery_city' => ['nullable', 'string', 'max:100'],
            'delivery_address' => ['nullable', 'string'],
            'cancellation_inventory_action' => ['nullable', Rule::in(['restock', 'damaged'])],
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

        $orderData['metadata'] = $this->decodeOptionalJson($request, 'metadata') ?? [];
        $orderData['status'] = $orderData['status'] ?: 'pending';
        $orderData['payment_status'] = $orderData['payment_status'] ?: 'pending';
        $orderData['subtotal'] = $orderData['subtotal'] ?? collect($items)->sum('total_price');
        $orderData['total'] = $orderData['total'] ?? $orderData['subtotal'];
        $orderData['store_id'] = $order?->store_id ?: $this->resolveStoreIdFromItems($items);

        if (empty($orderData['order_number']) && $orderData['store_id']) {
            $store = Store::query()->find($orderData['store_id']);
            $orderData['order_number'] = $store
                ? $this->orderWorkflowService->generateOrderNumber($store)
                : ('ORD-' . now()->format('YmdHis'));
        }

        $orderData['metadata']['public_token'] = data_get(
            $orderData['metadata'],
            'public_token',
            \Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(32))
        );

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
}
