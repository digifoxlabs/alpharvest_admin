<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Database\Eloquent\Builder;
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
        $scope = $this->resolveScope($request);

        $transactions = InventoryTransaction::query()
            ->with(['product', 'store'])
            ->tap(fn (Builder $query) => $this->applyScope($query, $scope))
            ->when($productId > 0, fn ($query) => $query->where('product_id', $productId))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.inventory.index', [
            'transactions' => $transactions,
            'products' => Product::query()->orderBy('name')->get(),
            'filters' => [
                'product_id' => $productId > 0 ? (string) $productId : '',
                'scope' => $scope,
            ],
            'scope' => $scope,
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

    public function destroy(InventoryTransaction $transaction): RedirectResponse
    {
        $transaction->delete();

        return redirect()->route('admin.inventory.index')->with('success', 'Inventory transaction archived successfully.');
    }

    public function restore(int $transaction): RedirectResponse
    {
        InventoryTransaction::withTrashed()->findOrFail($transaction)->restore();

        return redirect()->route('admin.inventory.index', ['scope' => 'trashed'])->with('success', 'Inventory transaction restored successfully.');
    }

    public function forceDelete(int $transaction): RedirectResponse
    {
        InventoryTransaction::onlyTrashed()->findOrFail($transaction)->forceDelete();

        return redirect()->route('admin.inventory.index', ['scope' => 'trashed'])->with('success', 'Inventory transaction permanently deleted.');
    }

    protected function resolveScope(Request $request): string
    {
        $scope = (string) $request->input('scope', 'active');

        return in_array($scope, ['active', 'trashed', 'all'], true) ? $scope : 'active';
    }

    protected function applyScope(Builder $query, string $scope): Builder
    {
        return match ($scope) {
            'trashed' => $query->onlyTrashed(),
            'all' => $query->withTrashed(),
            default => $query,
        };
    }
}
