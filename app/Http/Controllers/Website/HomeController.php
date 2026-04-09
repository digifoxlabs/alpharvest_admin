<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProducts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderByDesc('updated_at')
            ->orderBy('name')
            ->get();

        $categories = ProductCategory::query()
            ->where('is_active', true)
            ->with([
                'products' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('name'),
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('website.home.index', compact('featuredProducts', 'categories'));
    }

    public function show(string $slug): View
    {
        $product = Product::query()
            ->with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->whereKeyNot($product->id)
            ->where('product_category_id', $product->product_category_id)
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->limit(4)
            ->get();

        return view('website.products.show', compact('product', 'relatedProducts'));
    }
}
