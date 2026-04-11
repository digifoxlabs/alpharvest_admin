<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $scope = $this->resolveScope($request);

        $customers = Customer::query()
            ->with('store')
            ->withCount('orders')
            ->tap(fn (Builder $query) => $this->applyScope($query, $scope))
            ->latest('id')
            ->paginate(15);

        return view('admin.customers.index', compact('customers', 'scope'));
    }

    public function show(Customer $customer): View
    {
        $customer->load(['store', 'orders']);

        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        return view('admin.customers.edit', [
            'customer' => $customer,
            'stores' => Store::query()->orderBy('name')->get(),
            'addressBookText' => $this->addressBookText($customer),
        ]);
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'store_id' => ['required', 'exists:stores,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'whatsapp_id' => ['nullable', 'string', 'max:255'],
            'preferred_language' => ['nullable', 'string', 'max:10'],
            'pincode' => ['nullable', 'string', 'max:20'],
            'current_delivery_pincode' => ['nullable', 'string', 'max:20'],
            'current_delivery_city' => ['nullable', 'string', 'max:100'],
            'current_delivery_address' => ['nullable', 'string'],
            'address_book_text' => ['nullable', 'string'],
        ]);

        $metadata = $customer->metadata ?? [];
        $metadata['delivery'] = array_filter([
            'pincode' => $validated['current_delivery_pincode'] ?: null,
            'city' => $validated['current_delivery_city'] ?: null,
            'address' => $validated['current_delivery_address'] ?: null,
            'address_book' => collect(preg_split('/\r\n|\r|\n/', (string) ($validated['address_book_text'] ?? '')) ?: [])
                ->map(function (string $line) {
                    $line = trim($line);

                    if ($line === '') {
                        return null;
                    }

                    $parts = preg_split('/\s*[|]\s*/', $line, 3) ?: [];

                    if (count($parts) < 3) {
                        return null;
                    }

                    return [
                        'id' => \Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(10)),
                        'pincode' => trim((string) $parts[0]),
                        'city' => trim((string) $parts[1]),
                        'address' => trim((string) $parts[2]),
                        'saved_at' => now()->toIso8601String(),
                    ];
                })
                ->filter()
                ->values()
                ->all(),
        ], fn ($value) => $value !== null && $value !== '');

        $customer->update([
            'store_id' => $validated['store_id'],
            'name' => $validated['name'] ?: null,
            'phone' => $validated['phone'],
            'whatsapp_id' => $validated['whatsapp_id'] ?: null,
            'preferred_language' => $validated['preferred_language'] ?: 'en',
            'pincode' => $validated['pincode'] ?: null,
            'metadata' => $metadata,
        ]);

        return redirect()->route('admin.customers.show', $customer)->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Customer archived successfully.');
    }

    public function restore(int $customer): RedirectResponse
    {
        Customer::withTrashed()->findOrFail($customer)->restore();

        return redirect()->route('admin.customers.index', ['scope' => 'trashed'])->with('success', 'Customer restored successfully.');
    }

    public function forceDelete(int $customer): RedirectResponse
    {
        Customer::onlyTrashed()->findOrFail($customer)->forceDelete();

        return redirect()->route('admin.customers.index', ['scope' => 'trashed'])->with('success', 'Customer permanently deleted.');
    }

    protected function addressBookText(Customer $customer): string
    {
        return collect(data_get($customer->metadata, 'delivery.address_book', []))
            ->map(function (array $address) {
                return implode(' | ', array_filter([
                    $address['pincode'] ?? null,
                    $address['city'] ?? null,
                    $address['address'] ?? null,
                ]));
            })
            ->filter()
            ->implode("\n");
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
