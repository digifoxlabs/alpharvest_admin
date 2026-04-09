<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {
    }

    public function index(Request $request): View
    {
        $productId = (int) $request->input('product_id', 0);

        $transactions = InventoryTransaction::query()
            ->with(['product', 'store'])
            ->when($productId > 0, fn ($query) => $query->where('product_id', $productId))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.inventory.index', [
            'transactions' => $transactions,
            'products' => Product::query()->orderBy('name')->get(),
            'filters' => [
                'product_id' => $productId > 0 ? (string) $productId : '',
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.inventory.create', [
            'products' => Product::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', Rule::in(['in', 'out'])],
            'quantity' => ['required', 'integer', 'min:1'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $product = Product::query()->findOrFail($validated['product_id']);

        $this->inventoryService->record(
            $product,
            $validated['type'],
            (int) $validated['quantity'],
            $validated['remarks'] ?? null,
            null,
            [
                'reason' => 'manual_admin_entry',
            ]
        );

        return redirect()->route('admin.inventory.index')->with('success', 'Inventory transaction saved successfully.');
    }
}
