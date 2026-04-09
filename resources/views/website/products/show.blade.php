@extends('layouts.website')

@php
  $productMetaTitle = \Illuminate\Support\Str::limit($product->name . ' | Alp Harvest', 60, '');
  $productMetaDescription = \Illuminate\Support\Str::limit(
      $product->description ?: ('Buy ' . $product->name . ' from Alp Harvest. Authentic Assam product with transparent pricing and direct order support.'),
      160
  );
  $productStructuredDescription = $product->description ?: 'Authentic food product from Alp Harvest.';
  $productStructuredImage = $product->image_url ?: asset('images/logo.jpeg');
  $productStructuredUrl = route('products.show', $product->slug);
  $productStructuredPrice = number_format($product->display_price, 2, '.', '');
  $productStructuredAvailability = $product->inventory_quantity > 0
      ? 'https://schema.org/InStock'
      : 'https://schema.org/OutOfStock';
@endphp

@section('title', $product->name)
@section('meta_title', $productMetaTitle)
@section('meta_description', $productMetaDescription)
@section('canonical_url', $productStructuredUrl)
@section('meta_image', $productStructuredImage)
@section('structured_data')
<script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Product",
    "name": @json($product->name),
    "description": @json($productStructuredDescription),
    "image": [@json($productStructuredImage)],
    "sku": @json($product->sku),
    "category": @json($product->category?->name),
    "url": @json($productStructuredUrl),
    "brand": {
      "@type": "Brand",
      "name": "Alp Harvest"
    },
    "offers": {
      "@type": "Offer",
      "priceCurrency": "INR",
      "price": @json($productStructuredPrice),
      "availability": @json($productStructuredAvailability),
      "url": @json($productStructuredUrl)
    }
  }
</script>
@endsection

@section('content')
@php
  $productFallbackImage = asset('images/products/pickle.jpeg');
  $whatsAppMessage = rawurlencode('Hi, I would like to enquire about ' . $product->name . ' (' . $product->sku . ').');
@endphp

<section class="product-hero product-page-shell pt-28 pb-14 px-4 md:pb-20">
  <div class="max-w-7xl mx-auto">
    <nav class="product-breadcrumb mb-6 hidden md:flex items-center flex-wrap gap-2 text-sm text-white/70">
      <a href="{{ route('home') }}" class="transition-colors hover:text-white">Home</a>
      <span>/</span>
      <a href="{{ route('home') }}#products" class="transition-colors hover:text-white">Products</a>
      @if ($product->category)
        <span>/</span>
        <a href="{{ route('home') }}#{{ $product->category->slug }}" class="transition-colors hover:text-white">{{ $product->category->name }}</a>
      @endif
      <span>/</span>
      <span class="text-white">{{ $product->name }}</span>
    </nav>

    <div class="product-page-grid">
      <div class="product-media-shell">
        <div class="product-media-stage">
          <img src="{{ $product->image_url ?: $productFallbackImage }}" alt="{{ $product->name }}" class="product-main-image" />
        </div>

        <div class="product-support-grid">
          <div class="product-support-card">
            <div class="product-support-label">Category</div>
            <div class="product-support-value">{{ $product->category?->name ?? 'Curated Product' }}</div>
          </div>
          <div class="product-support-card">
            <div class="product-support-label">Status</div>
            <div class="product-support-value {{ $product->inventory_quantity > 0 ? 'text-green-800' : 'text-red-600' }}">
              {{ $product->inventory_quantity > 0 ? 'Ready to order' : 'Currently unavailable' }}
            </div>
          </div>
        </div>
      </div>

      <div class="product-panel">
        <div class="flex flex-wrap items-center gap-3 mb-4">
          @if ($product->category)
            <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-green-800">{{ $product->category->name }}</span>
          @endif
          @if ($product->is_featured)
            <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">Featured</span>
          @endif
        </div>

        <h1 class="text-3xl md:text-5xl font-bold leading-tight text-green-950" style="font-family:'Playfair Display',serif">{{ $product->name }}</h1>

        <div class="product-price-wrap mt-6">
          @if ($product->has_discount)
            <span class="product-price-old">₹{{ number_format((float) $product->price, 2) }}</span>
          @endif
          <span class="product-price-current">₹{{ number_format($product->display_price, 2) }}</span>
        </div>

        <p class="mt-4 text-sm font-semibold uppercase tracking-[0.18em] text-green-700">From Alp Harvest</p>

        <p class="mt-4 text-base leading-8 text-gray-600">
          {{ $product->name }} is part of our Assam collection. It is packed with care for freshness and everyday cooking.
        </p>

        <p class="mt-4 text-base leading-8 text-gray-600">
          {{ $product->description ?: 'This product is carefully selected. It is packed for freshness. It is made for everyday cooking and authentic flavor.' }}
        </p>

        <div class="product-stats-grid mt-8">
          <div class="product-stat-card">
            <div class="product-stat-label">SKU</div>
            <div class="product-stat-value">{{ $product->sku }}</div>
          </div>
          <div class="product-stat-card">
            <div class="product-stat-label">Availability</div>
            <div class="product-stat-value {{ $product->inventory_quantity > 0 ? 'text-green-800' : 'text-red-600' }}">
              {{ $product->inventory_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
            </div>
          </div>
          @if ($product->size)
            <div class="product-stat-card">
              <div class="product-stat-label">Size</div>
              <div class="product-stat-value">{{ $product->size }}</div>
            </div>
          @endif
          @if ($product->color)
            <div class="product-stat-card">
              <div class="product-stat-label">Color</div>
              <div class="product-stat-value">{{ $product->color }}</div>
            </div>
          @endif
        </div>

        <div class="product-cta-group mt-8">
          <a href="https://wa.me/919864371720?text={{ $whatsAppMessage }}" target="_blank" rel="noopener noreferrer" class="product-primary-cta">
            Enquire on WhatsApp
          </a>
          <a href="{{ route('home') }}#products" class="product-secondary-cta">
            Back to Products
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

@if ($relatedProducts->isNotEmpty())
  <section class="py-14 px-4 bg-white md:py-18">
    <div class="max-w-7xl mx-auto">
      <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between mb-8">
        <div>
          <p class="text-amber-700 text-xs font-semibold uppercase tracking-widest mb-2">You may also like</p>
          <h2 class="text-3xl md:text-4xl font-bold text-green-900" style="font-family:'Playfair Display',serif">Related Products</h2>
        </div>
        <a href="{{ route('home') }}#{{ $product->category?->slug }}" class="text-sm font-semibold text-green-700 transition-colors hover:text-amber-700">View category →</a>
      </div>

      <div class="grid grid-cols-2 gap-4 sm:grid-cols-2 lg:grid-cols-4 md:gap-6">
        @foreach ($relatedProducts as $relatedProduct)
          <a href="{{ route('products.show', $relatedProduct->slug) }}" class="product-card bg-[#faf6ef] rounded-3xl overflow-hidden shadow-sm border border-[#efe5d6] block">
            <div class="h-52 bg-white flex items-center justify-center p-4">
              <img src="{{ $relatedProduct->image_url ?: $productFallbackImage }}" alt="{{ $relatedProduct->name }}" class="h-full w-full object-contain drop-shadow" />
            </div>
            <div class="p-4">
              <div class="text-[11px] font-semibold uppercase tracking-wide text-green-700">{{ $relatedProduct->category?->name ?? 'Product' }}</div>
              <h3 class="mt-1 text-base font-bold text-green-900" style="font-family:'Playfair Display',serif">{{ $relatedProduct->name }}</h3>
              <div class="mt-3 flex flex-wrap items-center gap-2">
                @if ($relatedProduct->has_discount)
                  <span class="text-xs text-gray-400 line-through">₹{{ number_format((float) $relatedProduct->price, 2) }}</span>
                @endif
                <span class="text-amber-700 font-bold">₹{{ number_format($relatedProduct->display_price, 2) }}</span>
              </div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </section>
@endif
@endsection
