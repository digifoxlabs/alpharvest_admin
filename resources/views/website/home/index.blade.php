@extends('layouts.website')

@section('title','Home')
@section('meta_title', 'Organic Rice, Mustard Oil & Pickles in Guwahati | Alp Harvest')
@section('meta_description', 'Order organic ethnic rice, Majuli mustard oil and Assamese pickles in Guwahati. 100% natural foods sourced directly from Northeast farmers by Alp Harvest.')
@section('canonical_url', route('home'))
@section('meta_image', asset('images/logo.jpeg'))
@section('structured_data')
<script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "Alp Harvest Home",
    "url": "{{ route('home') }}",
    "description": "Featured products and category-wise listings of Alp Harvest organic food products."
  }
</script>
@endsection

@section('content')
@php
  $productFallbackImage = asset('images/products/pickle.jpeg');

  $categoryStyles = [
      'rice' => ['icon' => '🌾', 'iconBg' => 'bg-green-800', 'lineBg' => 'bg-green-200', 'badgeClass' => 'text-green-600'],
      'oil' => ['icon' => '🫒', 'iconBg' => 'bg-amber-700', 'lineBg' => 'bg-amber-200', 'badgeClass' => 'text-amber-600'],
      'pickle' => ['icon' => '🫙', 'iconBg' => 'bg-red-700', 'lineBg' => 'bg-red-200', 'badgeClass' => 'text-red-600'],
      'default' => ['icon' => '🛍️', 'iconBg' => 'bg-green-900', 'lineBg' => 'bg-green-200', 'badgeClass' => 'text-green-700'],
  ];
@endphp

<section id="home" class="hero-pattern min-h-screen flex items-center justify-center pt-20 pb-16 px-4 relative overflow-hidden">
  <div class="absolute top-20 right-0 w-96 h-96 rounded-full bg-white/5 -translate-y-1/4 translate-x-1/3"></div>
  <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full bg-amber-500/10 translate-y-1/3 -translate-x-1/3"></div>
  <div class="text-center text-white max-w-3xl mx-auto relative z-10">
    <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-4 py-1.5 text-sm text-green-100 mb-6 backdrop-blur-sm border border-white/10">
      <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
      Browse organic foods from Assam and place your order on WhatsApp
    </div>
    <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight" style="font-family:'Playfair Display',serif">
     Alp Harvest,<br/><span class="text-amber-400">Taste of Assam</span>
    </h1>
    <p class="text-green-100 text-lg md:text-xl mb-10 max-w-xl mx-auto leading-relaxed">
      Alp Harvest brings you organic foods from Assam. Taste of Assam lives in every grain, bottle, and jar we pack.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="#products" class="bg-amber-500 hover:bg-amber-400 text-white px-8 py-3.5 rounded-full font-semibold transition-colors shadow-lg shadow-amber-900/30">Explore Products</a>
      <a href="#contact" class="bg-white/10 hover:bg-white/20 text-white px-8 py-3.5 rounded-full font-semibold transition-colors backdrop-blur-sm border border-white/20">Contact Us</a>
      <a href="/files/brochure.pdf" download="AlpHarvest-Catalog.pdf"
        class="bg-green-600 hover:bg-green-500 text-white px-8 py-3.5 rounded-full font-semibold transition-colors shadow-lg shadow-green-900/30">
        Download Brochure
      </a>
    </div>
    <div class="mt-16 grid grid-cols-3 gap-6 max-w-lg mx-auto">
      <div class="text-center"><div class="text-3xl font-bold text-amber-400" style="font-family:'Playfair Display',serif">12+</div><div class="text-xs text-green-200 mt-1">Ethnic Varieties</div></div>
      <div class="text-center border-x border-white/10"><div class="text-3xl font-bold text-amber-400" style="font-family:'Playfair Display',serif">100%</div><div class="text-xs text-green-200 mt-1">Organic &amp; Pure</div></div>
      <div class="text-center"><div class="text-3xl font-bold text-amber-400" style="font-family:'Playfair Display',serif">NE</div><div class="text-xs text-green-200 mt-1">India's Best</div></div>
    </div>
  </div>
  <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1 text-white/50 text-xs animate-bounce">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    Scroll
  </div>
</section>

<section class="py-8 md:py-12 bg-white">
  <div class="max-w-6xl mx-auto px-4">
    <div class="carousel-wrapper relative overflow-hidden rounded-3xl shadow-2xl" style="height:360px">
      <div class="carousel-track h-full" id="carousel-track">
        <div class="carousel-slide relative" style="background:linear-gradient(135deg,#1a4a15,#2d5a27,#4a7c42)">
          <div class="absolute inset-0 flex items-center">
            <div class="pl-8 md:pl-16 max-w-md z-10">
              <p class="text-amber-300 text-sm font-medium uppercase tracking-widest mb-2">Heritage Grains</p>
              <h2 class="text-white text-3xl md:text-5xl font-bold mb-4 leading-tight" style="font-family:'Playfair Display',serif">Rare Ethnic Rice<br/>from Assam</h2>
              <p class="text-green-200 text-sm md:text-base mb-6">Ancient varieties preserving centuries of tradition.</p>
              <a href="#products" class="inline-block bg-amber-500 text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-amber-400 transition-colors">Shop Rice →</a>
            </div>
            <div class="absolute right-4 bottom-0 opacity-10 text-9xl md:text-[160px] leading-none select-none pointer-events-none">🌾</div>
          </div>
        </div>
        <div class="carousel-slide relative" style="background:linear-gradient(135deg,#7c4a00,#a36200,#c8922a)">
          <div class="absolute inset-0 flex items-center">
            <div class="pl-8 md:pl-16 max-w-md z-10">
              <p class="text-yellow-200 text-sm font-medium uppercase tracking-widest mb-2">Cold Pressed</p>
              <h2 class="text-white text-3xl md:text-5xl font-bold mb-4 leading-tight" style="font-family:'Playfair Display',serif">Majuli Pure<br/>Mustard Oil</h2>
              <p class="text-amber-100 text-sm md:text-base mb-6">Traditional cold-press from the island of Majuli.</p>
              <a href="#products" class="inline-block bg-green-700 text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-green-600 transition-colors">Shop Oil →</a>
            </div>
            <div class="absolute right-4 bottom-0 opacity-10 text-9xl md:text-[160px] leading-none select-none pointer-events-none">🫒</div>
          </div>
        </div>
        <div class="carousel-slide relative" style="background:linear-gradient(135deg,#4a1515,#7c2020,#a33030)">
          <div class="absolute inset-0 flex items-center">
            <div class="pl-8 md:pl-16 max-w-md z-10">
              <p class="text-red-200 text-sm font-medium uppercase tracking-widest mb-2">Traditional Recipes</p>
              <h2 class="text-white text-3xl md:text-5xl font-bold mb-4 leading-tight" style="font-family:'Playfair Display',serif">Handcrafted<br/>Pickles</h2>
              <p class="text-red-100 text-sm md:text-base mb-6">Bold flavours with no preservatives and all the nostalgia of home.</p>
              <a href="#products" class="inline-block bg-amber-500 text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-amber-400 transition-colors">Shop Pickles →</a>
            </div>
            <div class="absolute right-4 bottom-0 opacity-10 text-9xl md:text-[160px] leading-none select-none pointer-events-none">🫙</div>
          </div>
        </div>
      </div>
      <button onclick="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition-colors border border-white/20" aria-label="Previous">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </button>
      <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition-colors border border-white/20" aria-label="Next">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </button>
      <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
        <button onclick="goToSlide(0)" class="carousel-dot active" aria-label="Slide 1"></button>
        <button onclick="goToSlide(1)" class="carousel-dot" aria-label="Slide 2"></button>
        <button onclick="goToSlide(2)" class="carousel-dot" aria-label="Slide 3"></button>
      </div>
    </div>
  </div>
</section>

<section class="py-14" style="background:var(--cream)">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex items-end justify-between mb-8 reveal">
      <div>
        <p class="text-amber-700 text-xs font-semibold uppercase tracking-widest mb-1">Handpicked for you</p>
        <h2 class="text-3xl md:text-4xl font-bold text-green-900" style="font-family:'Playfair Display',serif">Featured Products</h2>
        <div class="leaf-divider mt-3"></div>
      </div>
      <a href="#products" class="text-sm text-green-700 font-medium hover:text-amber-700 transition-colors hidden sm:block">View all →</a>
    </div>

    @if ($featuredProducts->isNotEmpty())
      <div class="featured-scroll">
        @foreach ($featuredProducts as $product)
          <a href="{{ route('products.show', $product->slug) }}" class="featured-card bg-white rounded-2xl overflow-hidden shadow-md product-card block">
            <div class="h-52 bg-gray-50 flex items-center justify-center p-4">
              <img src="{{ $product->image_url ?: $productFallbackImage }}" alt="{{ $product->name }}" class="h-full w-full object-contain drop-shadow-md" />
            </div>
            <div class="p-4">
              <span class="text-xs font-semibold uppercase tracking-wide {{ str_contains(strtolower($product->category?->name ?? ''), 'oil') ? 'text-amber-600' : (str_contains(strtolower($product->category?->name ?? ''), 'pickle') ? 'text-red-600' : 'text-green-600') }}">
                {{ $product->category?->name ?? 'Featured Product' }}
              </span>
              <h3 class="font-bold text-gray-800 mt-1 text-base" style="font-family:'Playfair Display',serif">{{ $product->name }}</h3>
              <p class="text-xs text-gray-500 mt-1">{{ \Illuminate\Support\Str::limit($product->description ?: 'Fresh from our curated selection.', 70) }}</p>
              <div class="mt-3 flex items-end justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                  @if ($product->has_discount)
                    <span class="text-sm text-gray-400 line-through">₹{{ number_format((float) $product->price, 2) }}</span>
                  @endif
                  <span class="text-amber-700 font-bold text-lg">₹{{ number_format($product->display_price, 2) }}</span>
                </div>
                {{-- <span class="text-xs font-semibold text-green-700">View</span> --}}
              </div>
            </div>
          </a>
        @endforeach
      </div>
    @else
      <div class="rounded-3xl border border-dashed border-amber-300 bg-white/70 px-6 py-10 text-center text-gray-600">
        Featured products are not available right now.
      </div>
    @endif
  </div>
</section>

<section id="products" class="py-16" style="background:#f3ede3">
  <div class="max-w-7xl mx-auto px-4">
    <div class="text-center mb-14 reveal">
      <p class="text-amber-700 text-xs font-semibold uppercase tracking-widest mb-2">Our Offerings</p>
      <h2 class="text-4xl md:text-5xl font-bold text-green-900" style="font-family:'Playfair Display',serif">All Products</h2>
      <div class="leaf-divider mx-auto mt-4"></div>
    </div>

    @forelse ($categories as $category)
      @php
        $categoryName = strtolower($category->name);
        $styleKey = 'default';

        foreach (['rice', 'oil', 'pickle'] as $key) {
            if (str_contains($categoryName, $key)) {
                $styleKey = $key;
                break;
            }
        }

        $style = $categoryStyles[$styleKey];
      @endphp

      <div id="{{ $category->slug }}" class="mb-16 reveal">
        <div class="flex items-center gap-4 mb-8">
          <div class="w-12 h-12 {{ $style['iconBg'] }} rounded-xl flex items-center justify-center text-2xl shadow-lg">{{ $style['icon'] }}</div>
          <div>
            <h3 class="text-2xl md:text-3xl font-bold text-green-900" style="font-family:'Playfair Display',serif">{{ $category->name }}</h3>
            <p class="text-sm text-gray-500">{{ $category->description ?: 'Browse our curated products in this category.' }}</p>
          </div>
          <div class="ml-4 hidden sm:block h-px {{ $style['lineBg'] }} flex-1"></div>
        </div>

        @if ($category->products->isNotEmpty())
          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
            @foreach ($category->products as $product)
              <a href="{{ route('products.show', $product->slug) }}" class="product-card bg-white rounded-2xl overflow-hidden shadow-sm block">
                <div class="h-44 bg-gray-50 flex items-center justify-center p-3">
                  <img src="{{ $product->image_url ?: $productFallbackImage }}" alt="{{ $product->name }}" class="h-full w-full object-contain drop-shadow" />
                </div>
                <div class="p-4">
                  <span class="text-[11px] font-semibold uppercase tracking-wide {{ $style['badgeClass'] }}">{{ $category->name }}</span>
                  <h4 class="font-bold text-green-900 text-sm mt-1" style="font-family:'Playfair Display',serif">{{ $product->name }}</h4>
                  <p class="text-xs text-gray-400 mt-1">{{ \Illuminate\Support\Str::limit($product->description ?: ($product->sku ?: 'Freshly packed product'), 48) }}</p>
                  <div class="mt-3 flex flex-wrap items-center gap-x-2 gap-y-1">
                    @if ($product->has_discount)
                      <span class="text-xs text-gray-400 line-through">₹{{ number_format((float) $product->price, 2) }}</span>
                    @endif
                    <span class="text-amber-700 font-bold text-sm">₹{{ number_format($product->display_price, 2) }}</span>
                  </div>
                </div>
              </a>
            @endforeach
          </div>
        @else
          <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-8 text-center text-sm font-medium text-gray-500">
            Products Not Available
          </div>
        @endif
      </div>
    @empty
      <div class="rounded-3xl border border-dashed border-green-300 bg-white px-6 py-12 text-center text-gray-600">
        Product categories are not available right now.
      </div>
    @endforelse
  </div>
</section>

<section class="py-12 bg-green-900">
  <div class="max-w-6xl mx-auto px-4">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center text-white">
      <div class="reveal"><div class="text-4xl mb-2">🌿</div><h4 class="font-bold text-base" style="font-family:'Playfair Display',serif">100% Organic</h4><p class="text-xs text-green-300 mt-1">No chemicals, no additives</p></div>
      <div class="reveal"><div class="text-4xl mb-2">🏔️</div><h4 class="font-bold text-base" style="font-family:'Playfair Display',serif">Hill-Sourced</h4><p class="text-xs text-green-300 mt-1">Directly from NE farmers</p></div>
      <div class="reveal"><div class="text-4xl mb-2">📦</div><h4 class="font-bold text-base" style="font-family:'Playfair Display',serif">Fast Delivery</h4><p class="text-xs text-green-300 mt-1">Pan-India shipping</p></div>
      <div class="reveal"><div class="text-4xl mb-2">💚</div><h4 class="font-bold text-base" style="font-family:'Playfair Display',serif">Farmer-First</h4><p class="text-xs text-green-300 mt-1">Fair &amp; ethical sourcing</p></div>
    </div>
  </div>
</section>

<section id="contact" class="py-16" style="background:var(--cream)">
  <div class="max-w-7xl mx-auto px-4">
    <div class="text-center mb-12 reveal">
      <p class="text-amber-700 text-xs font-semibold uppercase tracking-widest mb-2">Get in Touch</p>
      <h2 class="text-4xl md:text-5xl font-bold text-green-900" style="font-family:'Playfair Display',serif">Contact Us</h2>
      <div class="leaf-divider mx-auto mt-4"></div>
    </div>
    <div class="grid md:grid-cols-2 gap-8 items-start">
      <div class="reveal rounded-2xl overflow-hidden shadow-xl border border-green-100">

<iframe
  class="w-full border-0"
  title="Alp Harvest location on Google Maps"
  src="https://www.google.com/maps?q=26.099997,91.715103&hl=en&z=15&output=embed"
  height="380"
  loading="lazy">
</iframe>

        <div class="bg-white p-4 flex items-center gap-3">
          <div class="w-8 h-8 bg-green-800 rounded-full flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
          <div><div class="text-sm font-semibold text-green-900">Alp Harvest HQ</div><div class="text-xs text-gray-500">Guwahati, Assam, India</div></div>
        </div>
      </div>
      <div class="reveal space-y-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-green-50 flex items-center gap-4"><div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0"><svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div><div><div class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Email</div><a href="mailto:as.alpharvest@gmail.com" class="text-green-900 font-semibold hover:text-amber-700 transition-colors">as.alpharvest@gmail.com</a></div></div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-green-50 flex items-center gap-4"><div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0"><svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div><div><div class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Phone</div><a href="tel:+919181081090" class="text-green-900 font-semibold hover:text-amber-700 transition-colors">91-810-810-90</a></div></div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-green-50 flex items-center gap-4"><div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0"><svg class="w-6 h-6 text-green-600" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg></div><div><div class="text-xs font-semibold text-gray-400 uppercase tracking-wide">WhatsApp</div><a href="https://wa.me/919181081090?text=Hi" target="_blank" class="text-green-900 font-semibold hover:text-amber-700 transition-colors">+91 9181081090</a></div></div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-green-50 flex items-center gap-4"><div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:linear-gradient(135deg,#833ab4,#fd1d1d,#fcb045)"><svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></div><div><div class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Instagram</div><a href="https://instagram.com/alpharvest" target="_blank" class="text-green-900 font-semibold hover:text-amber-700 transition-colors">@as.alpharvest</a></div></div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-green-50 flex items-center gap-4"><div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0"><svg class="w-6 h-6 text-blue-700" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></div><div><div class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Facebook</div><a href="https://www.facebook.com/AlpHarvest"
   target="_blank"
   rel="noopener noreferrer external nofollow"
   aria-label="Visit Alp Harvest Facebook page">
   Alp Harvest
</a></div></div>
      </div>
    </div>
  </div>
</section>
@endsection
