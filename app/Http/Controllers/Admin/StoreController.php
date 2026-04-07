<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\StoreEngineService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function __construct(protected StoreEngineService $storeEngine)
    {
    }

    public function index(): View
    {
        $stores = Store::latest()->paginate(10);

        $stores->getCollection()->transform(function (Store $store) {
            $store->setAttribute('catalog_readiness', $this->storeEngine->whatsappCatalogReadiness($store));

            return $store;
        });

        return view('admin.stores.index', compact('stores'));
    }

    public function create(): View
    {
        return view('admin.stores.create', [
            'deliveryZonesText' => '',
            'undeliverableMessage' => '',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $validated['slug'] = $this->resolveSlug($validated['slug'] ?? null, $validated['name'], 'stores');
        $validated['is_active'] = $request->boolean('is_active');
        $validated = $this->syncStoreImage($request, $validated);
        $validated = $this->syncStoreSettings($validated);

        Store::create($validated);

        return redirect()->route('admin.stores.index')->with('success', 'Store created successfully.');
    }

    public function edit(Store $store): View
    {
        return view('admin.stores.edit', [
            'store' => $store,
            'deliveryZonesText' => $this->deliveryZonesText($store),
            'undeliverableMessage' => (string) data_get($store->settings, 'undeliverable_message', ''),
        ]);
    }

    public function update(Request $request, Store $store): RedirectResponse
    {
        $validated = $request->validate($this->rules($store));
        $validated['slug'] = $this->resolveSlug($validated['slug'] ?? null, $validated['name'], 'stores', $store->id);
        $validated['is_active'] = $request->boolean('is_active');
        $validated = $this->syncStoreImage($request, $validated, $store);
        $validated = $this->syncStoreSettings($validated, $store);

        $store->update($validated);

        return redirect()->route('admin.stores.index')->with('success', 'Store updated successfully.');
    }

    public function destroy(Store $store): RedirectResponse
    {
        $this->deleteExistingStoreImage($store);

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
            'delivery_zones_text' => ['nullable', 'string'],
            'undeliverable_message' => ['nullable', 'string', 'max:1024'],
            'whatsapp_store_image' => ['nullable', 'image', 'max:4096'],
            'cropped_whatsapp_store_image' => ['nullable', 'string'],
            'remove_whatsapp_store_image' => ['nullable', 'boolean'],
        ];
    }

    private function syncStoreSettings(array $validated, ?Store $store = null): array
    {
        $settings = $store?->settings ?? [];

        $settings['delivery_zones'] = collect(preg_split('/\r\n|\r|\n/', (string) ($validated['delivery_zones_text'] ?? '')) ?: [])
            ->map(function (string $line) {
                $line = trim($line);

                if ($line === '') {
                    return null;
                }

                $parts = preg_split('/\s*[|,]\s*/', $line, 2) ?: [];
                $pincode = trim((string) ($parts[0] ?? ''));
                $city = trim((string) ($parts[1] ?? ''));

                if ($pincode === '' || $city === '') {
                    return null;
                }

                return [
                    'pincode' => $pincode,
                    'city' => $city,
                ];
            })
            ->filter()
            ->values()
            ->all();

        $message = trim((string) ($validated['undeliverable_message'] ?? ''));
        $settings['undeliverable_message'] = $message !== '' ? $message : null;

        $validated['settings'] = $settings;

        unset($validated['delivery_zones_text'], $validated['undeliverable_message']);

        return $validated;
    }

    private function deliveryZonesText(Store $store): string
    {
        return collect(data_get($store->settings, 'delivery_zones', []))
            ->map(fn (array $zone) => trim(($zone['pincode'] ?? '') . ' | ' . ($zone['city'] ?? '')))
            ->filter()
            ->implode("\n");
    }

    private function syncStoreImage(Request $request, array $validated, ?Store $store = null): array
    {
        if (($validated['remove_whatsapp_store_image'] ?? false) && $store?->whatsapp_store_image_path) {
            $this->deleteExistingStoreImage($store);
            $validated['whatsapp_store_image_path'] = null;
        }

        if (! empty($validated['cropped_whatsapp_store_image'])) {
            $validated['whatsapp_store_image_path'] = $this->storeImageFromBase64($validated['cropped_whatsapp_store_image'], $store);
        } elseif ($request->hasFile('whatsapp_store_image')) {
            $validated['whatsapp_store_image_path'] = $this->storeUploadedImage($request, $store);
        }

        unset($validated['whatsapp_store_image'], $validated['cropped_whatsapp_store_image'], $validated['remove_whatsapp_store_image']);

        return $validated;
    }

    private function storeUploadedImage(Request $request, ?Store $store = null): string
    {
        $this->deleteExistingStoreImage($store);

        $directory = public_path('uploads/stores');
        File::ensureDirectoryExists($directory);

        $extension = $request->file('whatsapp_store_image')->getClientOriginalExtension() ?: 'jpg';
        $filename = (string) Str::uuid() . '.' . strtolower($extension);

        $request->file('whatsapp_store_image')->move($directory, $filename);

        return 'uploads/stores/' . $filename;
    }

    private function storeImageFromBase64(string $payload, ?Store $store = null): ?string
    {
        if (! preg_match('/^data:image\/(png|jpe?g|webp);base64,/', $payload, $matches)) {
            return null;
        }

        $data = substr($payload, strpos($payload, ',') + 1);
        $binary = base64_decode($data, true);

        if ($binary === false) {
            return null;
        }

        $this->deleteExistingStoreImage($store);

        $directory = public_path('uploads/stores');
        File::ensureDirectoryExists($directory);

        $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
        $filename = (string) Str::uuid() . '.' . $extension;
        file_put_contents($directory . DIRECTORY_SEPARATOR . $filename, $binary);

        return 'uploads/stores/' . $filename;
    }

    private function deleteExistingStoreImage(?Store $store): void
    {
        if (! $store?->whatsapp_store_image_path) {
            return;
        }

        $publicFile = public_path($store->whatsapp_store_image_path);
        if (File::exists($publicFile)) {
            File::delete($publicFile);
        }

        if (Storage::disk('public')->exists($store->whatsapp_store_image_path)) {
            Storage::disk('public')->delete($store->whatsapp_store_image_path);
        }
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
