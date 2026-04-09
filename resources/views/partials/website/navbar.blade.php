<!-- ============================================================ NAVBAR ============================================================ -->
@php
  $navbarCategoryIcons = [
      'rice' => '🌾',
      'oil' => '🫒',
      'pickle' => '🫙',
      'default' => '🛍️',
  ];
@endphp

<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 py-3 px-4 md:px-8">
  <div class="max-w-7xl mx-auto flex items-center justify-between relative">

    <!-- Hamburger (mobile left) -->
    <button id="hamburger" class="md:hidden flex flex-col gap-1.5 p-2 z-10" aria-label="Open menu">
      <span class="hb-bar"></span>
      <span class="hb-bar"></span>
      <span class="hb-bar" style="width:16px"></span>
    </button>

    <!-- Logo (always centred) -->
    <a href="#home" class="absolute left-1/2 -translate-x-1/2 group">
      <img src="images/logo.jpeg" alt="Alp Harvest"
           class="h-14 md:h-20 w-auto shadow-lg object-contain transition-opacity group-hover:opacity-90" />
    </a>

    <!-- Desktop nav links (right) -->
    <div class="hidden md:flex items-center gap-8 ml-auto">
      <a href="#home" class="nav-link">Home</a>

      <!-- Products – level 1 dropdown -->
      <div class="dropdown relative">
        <button class="nav-link flex items-center gap-1 py-2 bg-transparent border-none cursor-pointer">
          Products
          <svg class="nav-chevron w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>

        <!-- Level-1 panel -->
        <div class="dropdown-menu top-full right-0 mt-1 w-56 bg-white rounded-2xl shadow-2xl border border-green-50 py-2" style="overflow:visible">
          @forelse (($navbarCategories ?? collect()) as $category)
            @php
              $categoryName = strtolower($category->name);
              $categoryIcon = $navbarCategoryIcons['default'];

              foreach (['rice', 'oil', 'pickle'] as $iconKey) {
                  if (str_contains($categoryName, $iconKey)) {
                      $categoryIcon = $navbarCategoryIcons[$iconKey];
                      break;
                  }
              }
            @endphp
            <div class="sub-dropdown relative">
              <button class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-semibold text-green-800 hover:bg-green-50 transition-colors bg-transparent border-none cursor-pointer">
                <span>{{ $categoryIcon }} {{ $category->name }}</span>
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </button>
              <div class="sub-dropdown-menu bg-white rounded-2xl shadow-2xl border border-green-50 py-2" style="min-width:210px">
                @forelse ($category->products as $product)
                  <a href="{{ route('products.show', $product->slug) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors">{{ $product->name }}</a>
                @empty
                  <span class="block px-4 py-2 text-sm text-gray-400">Products Not Available</span>
                @endforelse
              </div>
            </div>
          @empty
            <span class="block px-4 py-2 text-sm text-gray-400">Products Not Available</span>
          @endforelse
        </div>
      </div>

      <a href="#contact" class="nav-link">Contact Us</a>
    </div>

    <!-- Mobile right spacer -->
    <div class="w-10 md:hidden"></div>
  </div>
</nav>

<!-- Mobile Drawer Overlay -->
<div id="drawer-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40"></div>

<!-- Mobile Drawer -->
<aside id="mobile-drawer" class="fixed top-0 left-0 h-full w-72 bg-white z-50 shadow-2xl flex flex-col">
  <div class="hero-pattern px-6 pt-8 pb-6 flex items-center gap-3">
    <img src="images/logo.jpeg" alt="Alp Harvest" class="w-10 h-10  object-cover flex-shrink-0" />
    <div>
      <div class="text-white font-bold text-lg" style="font-family:'Playfair Display',serif">Alp Harvest</div>
      <div class="text-xs text-green-200">Pure from the Hills</div>
    </div>
    <button onclick="closeDrawer()" class="ml-auto text-white/70 hover:text-white" aria-label="Close menu">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
  </div>
  <div class="flex-1 overflow-y-auto py-4 px-2">
    <a href="#home" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-green-50 text-green-900 font-medium transition-colors">🏠 Home</a>

    <!-- Products accordion -->
    <div>
      <button onclick="toggleMobile('products-sub','products-chev')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-green-50 text-green-900 font-medium transition-colors bg-transparent border-none cursor-pointer">
        🛒 Products
        <svg id="products-chev" class="w-4 h-4 ml-auto text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
      </button>
      <div id="products-sub" class="mobile-sub pl-4">
        @forelse (($navbarCategories ?? collect()) as $category)
          @php
            $categoryName = strtolower($category->name);
            $categoryIcon = $navbarCategoryIcons['default'];

            foreach (['rice', 'oil', 'pickle'] as $iconKey) {
                if (str_contains($categoryName, $iconKey)) {
                    $categoryIcon = $navbarCategoryIcons[$iconKey];
                    break;
                }
            }

            $subId = 'category-sub-' . $category->id;
            $chevId = 'category-chev-' . $category->id;
          @endphp
          <button onclick="toggleMobile('{{ $subId }}','{{ $chevId }}')" class="w-full flex items-center gap-2 px-4 py-2.5 rounded-xl hover:bg-green-50 text-sm font-semibold text-green-800 transition-colors bg-transparent border-none cursor-pointer">
            {{ $categoryIcon }} {{ $category->name }}
            <svg id="{{ $chevId }}" class="w-3.5 h-3.5 ml-auto text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div id="{{ $subId }}" class="mobile-sub-2 pl-4">
            @forelse ($category->products as $product)
              <a href="{{ route('products.show', $product->slug) }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-green-800 rounded-lg hover:bg-green-50 transition-colors">{{ $product->name }}</a>
            @empty
              <span class="block px-3 py-2 text-sm text-gray-400">Products Not Available</span>
            @endforelse
          </div>
        @empty
          <span class="block px-4 py-2 text-sm text-gray-400">Products Not Available</span>
        @endforelse
      </div>
    </div>

    <a href="#contact" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-green-50 text-green-900 font-medium transition-colors">📞 Contact Us</a>
  </div>
</aside>
