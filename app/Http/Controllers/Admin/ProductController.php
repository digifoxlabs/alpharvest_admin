<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $scope = $this->resolveScope($request);
        $filters = [
            'search' => trim((string) $request->input('search', '')),
            'category_id' => (string) $request->input('category_id', ''),
            'status' => (string) $request->input('status', ''),
            'featured' => (string) $request->input('featured', ''),
            'scope' => $scope,
        ];

        $products = $this->filteredProductsQuery($filters)
            ->with(['category', 'store'])
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => ProductCategory::query()->orderBy('name')->get(),
            'filters' => $filters,
            'scope' => $scope,
            'stats' => [
                'total_products' => Product::query()->count(),
                'total_categories' => ProductCategory::query()->count(),
                'active_products' => Product::query()->where('is_active', true)->count(),
            ],
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = [
            'search' => trim((string) $request->input('search', '')),
            'category_id' => (string) $request->input('category_id', ''),
            'status' => (string) $request->input('status', ''),
            'featured' => (string) $request->input('featured', ''),
        ];

        $products = $this->filteredProductsQuery($filters)
            ->with(['category', 'store'])
            ->latest('id')
            ->get();

        return response()->streamDownload(function () use ($products) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Sl No', 'Product Name', 'SKU', 'Category', 'Status', 'Featured', 'Price', 'Sale Price', 'Inventory', 'Store']);

            foreach ($products as $index => $product) {
                fputcsv($handle, [
                    $index + 1,
                    $product->name,
                    $product->sku,
                    $product->category?->name ?: '',
                    $product->is_active ? 'Active' : 'Inactive',
                    $product->is_featured ? 'Yes' : 'No',
                    number_format((float) $product->price, 2, '.', ''),
                    $product->sale_price !== null ? number_format((float) $product->sale_price, 2, '.', '') : '',
                    $product->inventory_quantity,
                    $product->store?->name ?: '',
                ]);
            }

            fclose($handle);
        }, 'products-export.csv', [
            'Content-Type' => 'text/csv',
        ]);
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
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product archived successfully.');
    }

    public function restore(int $product): RedirectResponse
    {
        Product::withTrashed()->findOrFail($product)->restore();

        return redirect()->route('admin.products.index', ['scope' => 'trashed'])->with('success', 'Product restored successfully.');
    }

    public function forceDelete(int $product): RedirectResponse
    {
        $product = Product::onlyTrashed()->findOrFail($product);
        $this->deleteExistingProductImage($product);
        $product->forceDelete();

        return redirect()->route('admin.products.index', ['scope' => 'trashed'])->with('success', 'Product permanently deleted.');
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

    private function filteredProductsQuery(array $filters)
    {
        $search = $filters['search'] ?? '';
        $categoryId = (int) ($filters['category_id'] ?? 0);
        $status = $filters['status'] ?? '';
        $featured = $filters['featured'] ?? '';

        return Product::query()
            ->tap(fn (Builder $query) => $this->applyScope($query, $filters['scope'] ?? 'active'))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($productQuery) use ($search) {
                    $productQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($categoryId > 0, fn ($query) => $query->where('product_category_id', $categoryId))
            ->when(in_array($status, ['active', 'inactive'], true), fn ($query) => $query->where('is_active', $status === 'active'))
            ->when(in_array($featured, ['featured', 'non_featured'], true), fn ($query) => $query->where('is_featured', $featured === 'featured'));
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

    private function resolveScope(Request $request): string
    {
        $scope = (string) $request->input('scope', 'active');

        return in_array($scope, ['active', 'trashed', 'all'], true) ? $scope : 'active';
    }

    private function applyScope(Builder $query, string $scope): Builder
    {
        return match ($scope) {
            'trashed' => $query->onlyTrashed(),
            'all' => $query->withTrashed(),
            default => $query,
        };
    }
}
