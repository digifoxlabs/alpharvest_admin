<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class SeoController extends Controller
{
    public function sitemap(): View
    {
        $pages = collect([
            [
                'loc' => route('home'),
                'lastmod' => now()->toDateString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
        ])->merge(
            Product::query()
                ->where('is_active', true)
                ->orderByDesc('updated_at')
                ->get(['slug', 'updated_at'])
                ->map(fn (Product $product) => [
                    'loc' => route('products.show', $product->slug),
                    'lastmod' => Carbon::parse($product->updated_at)->toDateString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ])
        );

        return response()
            ->view('website.seo.sitemap', compact('pages'))
            ->header('Content-Type', 'application/xml');
    }

    public function robotsXml(): Response
    {
        return redirect('/robots.txt', 301);
    }
}
