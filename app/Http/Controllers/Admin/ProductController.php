<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['category', 'store'])->latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $validated['slug'] = $this->resolveSlug($validated['slug'] ?? null, $validated['name']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['store_id'] = $this->resolveStoreId($validated['product_category_id'] ?? null);
        $validated = $this->syncProductImage($request, $validated);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $categories = ProductCategory::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate($this->rules($product));
        $validated['slug'] = $this->resolveSlug($validated['slug'] ?? null, $validated['name'], $product->id);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['store_id'] = $product->store_id ?: $this->resolveStoreId($validated['product_category_id'] ?? null);
        $validated = $this->syncProductImage($request, $validated, $product);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteExistingProductImage($product);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    private function rules(?Product $product = null): array
    {
        return [
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($product?->id)],
            'sku' => ['required', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($product?->id)],
            'meta_retailer_id' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'color' => ['nullable', 'string', 'max:100'],
            'size' => ['nullable', 'string', 'max:100'],
            'shipping_weight' => ['nullable', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'inventory_quantity' => ['required', 'integer', 'min:0'],
            'product_image' => ['nullable', 'image', 'max:4096'],
            'cropped_product_image' => ['nullable', 'string'],
            'remove_product_image' => ['nullable', 'boolean'],
        ];
    }

    private function syncProductImage(Request $request, array $validated, ?Product $product = null): array
    {
        if (($validated['remove_product_image'] ?? false) && $product?->image_path) {
            $this->deleteExistingProductImage($product);
            $validated['image_path'] = null;
        }

        if (! empty($validated['cropped_product_image'])) {
            $validated['image_path'] = $this->storeImageFromBase64($validated['cropped_product_image'], $product);
        } elseif ($request->hasFile('product_image')) {
            $validated['image_path'] = $this->storeUploadedImage($request, $product);
        }

        unset($validated['product_image'], $validated['cropped_product_image'], $validated['remove_product_image']);

        return $validated;
    }

    private function storeUploadedImage(Request $request, ?Product $product = null): string
    {
        $this->deleteExistingProductImage($product);

        $directory = public_path('uploads/products');
        File::ensureDirectoryExists($directory);

        $extension = $request->file('product_image')->getClientOriginalExtension() ?: 'jpg';
        $filename = (string) Str::uuid() . '.' . strtolower($extension);

        $request->file('product_image')->move($directory, $filename);

        return 'uploads/products/' . $filename;
    }

    private function storeImageFromBase64(string $payload, ?Product $product = null): ?string
    {
        if (! preg_match('/^data:image\/(png|jpe?g|webp);base64,/', $payload, $matches)) {
            return null;
        }

        $data = substr($payload, strpos($payload, ',') + 1);
        $binary = base64_decode($data, true);

        if ($binary === false) {
            return null;
        }

        $this->deleteExistingProductImage($product);

        $directory = public_path('uploads/products');
        File::ensureDirectoryExists($directory);

        $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
        $filename = (string) Str::uuid() . '.' . $extension;
        file_put_contents($directory . DIRECTORY_SEPARATOR . $filename, $binary);

        return 'uploads/products/' . $filename;
    }

    private function deleteExistingProductImage(?Product $product): void
    {
        if (! $product?->image_path) {
            return;
        }

        $publicFile = public_path($product->image_path);
        if (File::exists($publicFile)) {
            File::delete($publicFile);
        }

        if (Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }
    }

    private function resolveStoreId(?int $categoryId = null): ?int
    {
        if ($categoryId) {
            $categoryStoreId = ProductCategory::query()->whereKey($categoryId)->value('store_id');

            if ($categoryStoreId) {
                return (int) $categoryStoreId;
            }
        }

        return Store::query()->where('is_active', true)->orderBy('id')->value('id')
            ?? Store::query()->orderBy('id')->value('id');
    }

    private function resolveSlug(?string $slug, string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $name);
        $resolved = $base ?: Str::random(8);
        $counter = 1;

        while (
            Product::query()
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
