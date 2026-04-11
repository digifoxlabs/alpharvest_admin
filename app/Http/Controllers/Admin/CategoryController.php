<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $scope = $this->resolveScope(request());

        $categories = ProductCategory::with('store')
            ->tap(fn (Builder $query) => $this->applyScope($query, $scope))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.categories.index', compact('categories', 'scope'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $validated['slug'] = $this->resolveSlug($validated['slug'] ?? null, $validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['store_id'] = $this->defaultStore()?->id;

        ProductCategory::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(ProductCategory $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, ProductCategory $category): RedirectResponse
    {
        $validated = $request->validate($this->rules($category));
        $validated['slug'] = $this->resolveSlug($validated['slug'] ?? null, $validated['name'], $category->id);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['store_id'] = $category->store_id ?: $this->defaultStore()?->id;

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(ProductCategory $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category archived successfully.');
    }

    public function restore(int $category): RedirectResponse
    {
        ProductCategory::withTrashed()->findOrFail($category)->restore();

        return redirect()->route('admin.categories.index', ['scope' => 'trashed'])->with('success', 'Category restored successfully.');
    }

    public function forceDelete(int $category): RedirectResponse
    {
        ProductCategory::onlyTrashed()->findOrFail($category)->forceDelete();

        return redirect()->route('admin.categories.index', ['scope' => 'trashed'])->with('success', 'Category permanently deleted.');
    }

    private function rules(?ProductCategory $category = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('product_categories', 'slug')->ignore($category?->id)],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    private function defaultStore(): ?Store
    {
        return Store::query()->where('is_active', true)->orderBy('id')->first()
            ?? Store::query()->orderBy('id')->first();
    }

    private function resolveSlug(?string $slug, string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $name);
        $resolved = $base ?: Str::random(8);
        $counter = 1;

        while (
            ProductCategory::query()
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
