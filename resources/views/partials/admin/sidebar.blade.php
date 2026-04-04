<aside :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0">
    <div :class="sidebarToggle ? 'justify-center' : 'justify-between'" class="flex items-center gap-2 pt-8 sidebar-header pb-7">
        <a href="{{ route('admin.dashboard') }}">
            <span class="logo" :class="sidebarToggle ? 'hidden' : ''">
                <img class="dark:hidden" src="{{ asset('images/admin/src/images/logo/logo.svg') }}" alt="Logo" />
                <img class="hidden dark:block" src="{{ asset('images/admin/src/images/logo/logo-dark.svg') }}" alt="Logo" />
            </span>

            <img class="logo-icon" :class="sidebarToggle ? 'lg:block' : 'hidden'" src="{{ asset('images/admin/src/images/logo/logo-icon.svg') }}"
                alt="Logo" />
        </a>
    </div>

    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <nav x-data="{ selected: $persist('dashboard').as('admin-sidebar-selected') }">
            <div>
                <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                    <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">Menu</span>
                </h3>

                <ul class="flex flex-col gap-2 mb-6">
                    @can('view dashboard')
                        <li>
                            <a href="{{ route('admin.dashboard') }}"
                                class="menu-item {{ request()->routeIs('admin.dashboard') ? 'menu-item-active' : 'menu-item-inactive' }}">
                                <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Dashboard</span>
                            </a>
                        </li>
                    @endcan

                    @canany(['view users', 'view roles', 'view permissions'])
                        <li>
                            <a href="#" @click.prevent="selected = selected === 'access' ? '' : 'access'"
                                class="menu-item {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                                <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Access Control</span>
                            </a>

                            <div class="overflow-hidden" :class="selected === 'access' ? 'block' : 'hidden'">
                                <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'" class="mt-2 flex flex-col gap-1 menu-dropdown pl-6">
                                    @can('view users')
                                        <li>
                                            <a href="{{ route('admin.users.index') }}"
                                                class="menu-dropdown-item {{ request()->routeIs('admin.users.*') ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}">
                                                Users
                                            </a>
                                        </li>
                                    @endcan
                                    @can('view roles')
                                        <li>
                                            <a href="{{ route('admin.roles.index') }}"
                                                class="menu-dropdown-item {{ request()->routeIs('admin.roles.*') ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}">
                                                Roles
                                            </a>
                                        </li>
                                    @endcan
                                    @can('view permissions')
                                        <li>
                                            <a href="{{ route('admin.permissions.index') }}"
                                                class="menu-dropdown-item {{ request()->routeIs('admin.permissions.*') ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}">
                                                Permissions
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcanany

                    @canany(['view stores', 'view products', 'view categories', 'view orders', 'view chats', 'view templates'])
                        <li>
                            <a href="#" @click.prevent="selected = selected === 'modules' ? '' : 'modules'"
                                class="menu-item {{ request()->routeIs('admin.stores.*') || request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.orders.*') || request()->routeIs('admin.messages.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                                <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Business Modules</span>
                            </a>

                            <div class="overflow-hidden" :class="selected === 'modules' ? 'block' : 'hidden'">
                                <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'" class="mt-2 flex flex-col gap-1 menu-dropdown pl-6">
                                    @can('view stores')
                                        <li>
                                            <a href="{{ route('admin.stores.index') }}"
                                                class="menu-dropdown-item {{ request()->routeIs('admin.stores.*') ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}">
                                                Stores
                                            </a>
                                        </li>
                                    @endcan
                                    @can('view products')
                                        <li>
                                            <a href="{{ route('admin.products.index') }}"
                                                class="menu-dropdown-item {{ request()->routeIs('admin.products.*') ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}">
                                                Products
                                            </a>
                                        </li>
                                    @endcan
                                    @can('view categories')
                                        <li>
                                            <a href="{{ route('admin.categories.index') }}"
                                                class="menu-dropdown-item {{ request()->routeIs('admin.categories.*') ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}">
                                                Categories
                                            </a>
                                        </li>
                                    @endcan
                                    @can('view orders')
                                        <li>
                                            <a href="{{ route('admin.orders.index') }}"
                                                class="menu-dropdown-item {{ request()->routeIs('admin.orders.*') ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}">
                                                Orders
                                            </a>
                                        </li>
                                    @endcan
                                    @can('view chats')
                                        <li>
                                            <a href="{{ route('admin.messages.index') }}"
                                                class="menu-dropdown-item {{ request()->routeIs('admin.messages.*') ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}">
                                                WhatsApp Chats
                                            </a>
                                        </li>
                                    @endcan
                                    @can('view templates')
                                        <li><span class="menu-dropdown-item menu-dropdown-item-inactive">Message Templates</span></li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcanany

                    @role('admin')
                        <li>
                            <a href="{{ route('admin.system-report') }}"
                                class="menu-item {{ request()->routeIs('admin.system-report') ? 'menu-item-active' : 'menu-item-inactive' }}">
                                <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Admin Only</span>
                            </a>
                        </li>
                    @endrole
                </ul>
            </div>
        </nav>
    </div>
</aside>
