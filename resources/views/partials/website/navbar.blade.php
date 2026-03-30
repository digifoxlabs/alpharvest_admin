<!-- ============================================================ NAVBAR ============================================================ -->
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

          <!-- Ethnic Rice sub-dropdown -->
          <div class="sub-dropdown relative">
            <button class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-semibold text-green-800 hover:bg-green-50 transition-colors bg-transparent border-none cursor-pointer">
              <span>🌾 Ethnic Rice</span>
              <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div class="sub-dropdown-menu bg-white rounded-2xl shadow-2xl border border-green-50 py-2" style="min-width:210px">
              <a href="#ethnic-rice" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors">Titabior Aijung</a>
              <a href="#ethnic-rice" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors">Black Joha</a>
              <a href="#ethnic-rice" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors">Sticky Rice</a>
              <a href="#ethnic-rice" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors">Red Bau Rice</a>
              <a href="#ethnic-rice" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors">Manipuri Black Rice</a>
            </div>
          </div>

          <!-- Mustard Oil sub-dropdown -->
          <div class="sub-dropdown relative">
            <button class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-semibold text-green-800 hover:bg-green-50 transition-colors bg-transparent border-none cursor-pointer">
              <span>🫒 Mustard Oil</span>
              <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div class="sub-dropdown-menu bg-white rounded-2xl shadow-2xl border border-green-50 py-2" style="min-width:210px">
              <a href="#mustard-oil" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors">Majuli Pure Mustard Oil</a>
            </div>
          </div>

          <!-- Pickles sub-dropdown -->
          <div class="sub-dropdown relative">
            <button class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-semibold text-green-800 hover:bg-green-50 transition-colors bg-transparent border-none cursor-pointer">
              <span>🫙 Pickles</span>
              <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div class="sub-dropdown-menu bg-white rounded-2xl shadow-2xl border border-green-50 py-2" style="min-width:210px">
              <a href="#pickles" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors">Green Chilli Pickle</a>
              <a href="#pickles" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors">Ghost Chilli Pickle</a>
              <a href="#pickles" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors">Jujubi Pickle</a>
              <a href="#pickles" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors">Mango Pickle</a>
              <a href="#pickles" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors">Garlic Pickle</a>
              <a href="#pickles" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors">Mix Veg Pickle</a>
            </div>
          </div>

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
        <button onclick="toggleMobile('rice-sub','rice-chev')" class="w-full flex items-center gap-2 px-4 py-2.5 rounded-xl hover:bg-green-50 text-sm font-semibold text-green-800 transition-colors bg-transparent border-none cursor-pointer">
          🌾 Ethnic Rice
          <svg id="rice-chev" class="w-3.5 h-3.5 ml-auto text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div id="rice-sub" class="mobile-sub-2 pl-4">
          <a href="#ethnic-rice" class="block px-3 py-2 text-sm text-gray-600 hover:text-green-800 rounded-lg hover:bg-green-50 transition-colors">Titabior Aijung</a>
          <a href="#ethnic-rice" class="block px-3 py-2 text-sm text-gray-600 hover:text-green-800 rounded-lg hover:bg-green-50 transition-colors">Black Joha</a>
          <a href="#ethnic-rice" class="block px-3 py-2 text-sm text-gray-600 hover:text-green-800 rounded-lg hover:bg-green-50 transition-colors">Sticky Rice</a>
          <a href="#ethnic-rice" class="block px-3 py-2 text-sm text-gray-600 hover:text-green-800 rounded-lg hover:bg-green-50 transition-colors">Red Bau Rice</a>
          <a href="#ethnic-rice" class="block px-3 py-2 text-sm text-gray-600 hover:text-green-800 rounded-lg hover:bg-green-50 transition-colors">Manipuri Black Rice</a>
        </div>

        <button onclick="toggleMobile('oil-sub','oil-chev')" class="w-full flex items-center gap-2 px-4 py-2.5 rounded-xl hover:bg-green-50 text-sm font-semibold text-green-800 transition-colors bg-transparent border-none cursor-pointer">
          🫒 Mustard Oil
          <svg id="oil-chev" class="w-3.5 h-3.5 ml-auto text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div id="oil-sub" class="mobile-sub-2 pl-4">
          <a href="#mustard-oil" class="block px-3 py-2 text-sm text-gray-600 hover:text-green-800 rounded-lg hover:bg-green-50 transition-colors">Majuli Pure Mustard Oil</a>
        </div>

        <button onclick="toggleMobile('pickle-sub','pickle-chev')" class="w-full flex items-center gap-2 px-4 py-2.5 rounded-xl hover:bg-green-50 text-sm font-semibold text-green-800 transition-colors bg-transparent border-none cursor-pointer">
          🫙 Pickles
          <svg id="pickle-chev" class="w-3.5 h-3.5 ml-auto text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div id="pickle-sub" class="mobile-sub-2 pl-4">
          <a href="#pickles" class="block px-3 py-2 text-sm text-gray-600 hover:text-green-800 rounded-lg hover:bg-green-50 transition-colors">Green Chilli</a>
          <a href="#pickles" class="block px-3 py-2 text-sm text-gray-600 hover:text-green-800 rounded-lg hover:bg-green-50 transition-colors">Ghost Chilli</a>
          <a href="#pickles" class="block px-3 py-2 text-sm text-gray-600 hover:text-green-800 rounded-lg hover:bg-green-50 transition-colors">Jujubi</a>
          <a href="#pickles" class="block px-3 py-2 text-sm text-gray-600 hover:text-green-800 rounded-lg hover:bg-green-50 transition-colors">Mango</a>
          <a href="#pickles" class="block px-3 py-2 text-sm text-gray-600 hover:text-green-800 rounded-lg hover:bg-green-50 transition-colors">Garlic</a>
          <a href="#pickles" class="block px-3 py-2 text-sm text-gray-600 hover:text-green-800 rounded-lg hover:bg-green-50 transition-colors">Mix Veg</a>
        </div>
      </div>
    </div>

    <a href="#contact" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-green-50 text-green-900 font-medium transition-colors">📞 Contact Us</a>
  </div>
</aside>
