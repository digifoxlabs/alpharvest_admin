<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesJsonInput;
use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StoreController extends Controller
{
    use HandlesJsonInput;

    public function index(): View
    {
        $stores = Store::latest()->paginate(10);

        return view('admin.stores.index', compact('stores'));
    }

    public function create(): View
    {
        return view('admin.stores.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $validated['slug'] = $this->resolveSlug($validated['slug'] ?? null, $validated['name'], 'stores');
        $validated['settings'] = $this->decodeOptionalJson($request, 'settings');
        $validated['is_active'] = $request->boolean('is_active');

        Store::create($validated);

        return redirect()->route('admin.stores.index')->with('success', 'Store created successfully.');
    }

    public function edit(Store $store): View
    {
        return view('admin.stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store): RedirectResponse
    {
        $validated = $request->validate($this->rules($store));
        $validated['slug'] = $this->resolveSlug($validated['slug'] ?? null, $validated['name'], 'stores', $store->id);
        $validated['settings'] = $this->decodeOptionalJson($request, 'settings');
        $validated['is_active'] = $request->boolean('is_active');

        $store->update($validated);

        return redirect()->route('admin.stores.index')->with('success', 'Store updated successfully.');
    }

    public function destroy(Store $store): RedirectResponse
    {
        $store->delete();

        return redirect()->route('admin.stores.index')->with('success', 'Store deleted successfully.');
    }

    private function rules(?Store $store = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('stores', 'slug')->ignore($store?->id)],
            'support_phone' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'currency' => ['required', 'string', 'max:10'],
            'whatsapp_phone_number_id' => ['nullable', 'string', 'max:255'],
            'whatsapp_business_account_id' => ['nullable', 'string', 'max:255'],
            'meta_catalog_id' => ['nullable', 'string', 'max:255'],
            'meta_access_token' => ['nullable', 'string'],
            'whatsapp_brand_name' => ['nullable', 'string', 'max:255'],
            'whatsapp_welcome_text' => ['nullable', 'string'],
            'whatsapp_store_intro' => ['nullable', 'string'],
            'whatsapp_contact_text' => ['nullable', 'string'],
            'whatsapp_store_image_path' => ['nullable', 'string', 'max:255'],
            'settings' => ['nullable', 'string'],
        ];
    }

    private function resolveSlug(?string $slug, string $name, string $table, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $name);
        $resolved = $base ?: Str::random(8);
        $counter = 1;

        while (
            Store::query()
                ->where('slug', $resolved)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $resolved = "{$base}-{$counter}";
            $counter++;
        }

        return $resolved;
    }
}
