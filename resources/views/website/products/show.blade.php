@extends('layouts.website')

@section('title', $product->name)
@section('meta_title', $product->name . ' | Alp Harvest')
@section('meta_description', \Illuminate\Support\Str::limit($product->description ?: ('Buy ' . $product->name . ' from Alp Harvest. Explore authentic products from Assam with transparent pricing and direct enquiry support.'), 155))
@section('canonical_url', route('products.show', $product->slug))
@section('meta_image', $product->image_url ?: asset('images/logo.jpeg'))
@section('structured_data')
<script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Product",
    "name": @json($product->name),
    "description": @json($product->description ?: 'Authentic food product from Alp Harvest.'),
    "image": [@json($product->image_url ?: asset('images/logo.jpeg'))],
    "sku": @json($product->sku),
    "category": @json($product->category?->name),
    "url": @json(route('products.show', $product->slug)),
    "brand": {
      "@type": "Brand",
      "name": "Alp Harvest"
    },
    "offers": {
      "@type": "Offer",
      "priceCurrency": "INR",
      "price": @json(number_format($product->display_price, 2, '.', '')),
      "availability": @json($product->inventory_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'),
      "url": @json(route('products.show', $product->slug))
    }
  }
</script>
@endsection

@section('content')
@php
  $productFallbackImage = asset('images/products/pickle.jpeg');
  $whatsAppMessage = rawurlencode('Hi, I would like to enquire about ' . $product->name . ' (' . $product->sku . ').');
@endphp

<section class="product-hero pt-28 pb-16 px-4">
  <div class="max-w-7xl mx-auto">
    <nav class="mb-8 text-sm text-gray-500">
      <a href="{{ route('home') }}" class="hover:text-green-800 transition-colors">Home</a>
      <span class="mx-2">/</span>
      <a href="{{ route('home') }}#products" class="hover:text-green-800 transition-colors">Products</a>
      @if ($product->category)
        <span class="mx-2">/</span>
        <a href="{{ route('home') }}#{{ $product->category->slug }}" class="hover:text-green-800 transition-colors">{{ $product->category->name }}</a>
      @endif
      <span class="mx-2">/</span>
      <span class="text-green-900">{{ $product->name }}</span>
    </nav>

    <div class="grid lg:grid-cols-[1.05fr_0.95fr] gap-8 items-start">
      <div class="product-gallery-card rounded-[2rem] border border-white/70 bg-white/80 p-5 backdrop-blur-sm">
        <div class="rounded-[1.5rem] bg-[#f8f4ed] min-h-[420px] flex items-center justify-center p-6">
          <img src="{{ $product->image_url ?: $productFallbackImage }}" alt="{{ $product->name }}" class="max-h-[440px] w-full object-contain drop-shadow-xl" />
        </div>
      </div>

      <div class="product-info-card rounded-[2rem] bg-white p-7 md:p-9 border border-green-100">
        <div class="flex flex-wrap items-center gap-3 mb-4">
          @if ($product->category)
            <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-green-800">{{ $product->category->name }}</span>
          @endif
          @if ($product->is_featured)
            <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">Featured</span>
          @endif
        </div>

        <h1 class="text-4xl md:text-5xl font-bold text-green-950 leading-tight" style="font-family:'Playfair Display',serif">{{ $product->name }}</h1>

        <div class="mt-6 flex flex-wrap items-end gap-3">
          @if ($product->has_discount)
            <span class="text-xl text-gray-400 line-through">₹{{ number_format((float) $product->price, 2) }}</span>
          @endif
          <span class="text-4xl font-bold text-amber-700">₹{{ number_format($product->display_price, 2) }}</span>
        </div>

        <p class="mt-6 text-base leading-8 text-gray-600">
          {{ $product->description ?: 'This product is part of our carefully selected collection, packed to bring authentic flavour and quality to your table.' }}
        </p>

        <div class="mt-8 grid sm:grid-cols-2 gap-4">
          <div class="rounded-2xl bg-[#f8f4ed] p-4">
            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">SKU</div>
            <div class="mt-2 text-base font-semibold text-green-900">{{ $product->sku }}</div>
          </div>
          <div class="rounded-2xl bg-[#f8f4ed] p-4">
            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">Availability</div>
            <div class="mt-2 text-base font-semibold {{ $product->inventory_quantity > 0 ? 'text-green-800' : 'text-red-600' }}">
              {{ $product->inventory_quantity > 0 ? 'In Stock' : 'Currently Unavailable' }}
            </div>
          </div>
          @if ($product->size)
            <div class="rounded-2xl bg-[#f8f4ed] p-4">
              <div class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">Size</div>
              <div class="mt-2 text-base font-semibold text-green-900">{{ $product->size }}</div>
            </div>
          @endif
          @if ($product->color)
            <div class="rounded-2xl bg-[#f8f4ed] p-4">
              <div class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">Color</div>
              <div class="mt-2 text-base font-semibold text-green-900">{{ $product->color }}</div>
            </div>
          @endif
        </div>

        <div class="mt-8 flex flex-col sm:flex-row gap-4">
          <a href="https://wa.me/919864371720?text={{ $whatsAppMessage }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center rounded-full bg-green-800 px-7 py-4 text-sm font-semibold text-white transition-colors hover:bg-green-700">
            Enquire on WhatsApp
          </a>
          <a href="{{ route('home') }}#products" class="inline-flex items-center justify-center rounded-full border border-green-200 px-7 py-4 text-sm font-semibold text-green-900 transition-colors hover:bg-green-50">
            Back to Products
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

@if ($relatedProducts->isNotEmpty())
  <section class="py-16 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
      <div class="flex items-end justify-between gap-4 mb-8">
        <div>
          <p class="text-amber-700 text-xs font-semibold uppercase tracking-widest mb-2">You may also like</p>
          <h2 class="text-3xl md:text-4xl font-bold text-green-900" style="font-family:'Playfair Display',serif">Related Products</h2>
        </div>
        <a href="{{ route('home') }}#{{ $product->category?->slug }}" class="text-sm font-semibold text-green-700 hover:text-amber-700 transition-colors">View category →</a>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
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
