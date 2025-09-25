<!-- Start::app-sidebar -->
<aside class="app-sidebar sticky" id="sidebar">
    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        @if(Auth::user()->role === 'vendor')
        <a href="{{ route('vendor.dashboard') }}" class="header-logo">
        @else
        <a href="{{ route('admin.dashboard') }}" class="header-logo">
        @endif
            <img src="{{ asset('admin-assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
            <img src="{{ asset('admin-assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
            <img src="{{ asset('admin-assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
            <img src="{{ asset('admin-assets/images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">
        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path> </svg>
            </div>
            <ul class="main-menu">
                @if(Auth::user()->role === 'vendor')
                    {{-- Vendor Menu Items (Static for now - can be made dynamic later) --}}
                    <!-- Dashboard -->
                    <li class="slide__category"><span class="category-name">Vendor Panel</span></li>
                    <li class="slide">
                        <a href="{{ route('vendor.dashboard') }}" class="side-menu__item {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                            <i class="bx bx-home side-menu__icon"></i>
                            <span class="side-menu__label">Dashboard</span>
                        </a>
                    </li>

                    <!-- Profile -->
                    <li class="slide">
                        <a href="{{ route('vendor.profile') }}" class="side-menu__item {{ request()->routeIs('vendor.profile*') ? 'active' : '' }}">
                            <i class="bx bx-user side-menu__icon"></i>
                            <span class="side-menu__label">My Profile</span>
                        </a>
                    </li>

                    <!-- Products -->
                    <li class="slide has-sub {{ request()->routeIs('vendor.products.*') ? 'open' : '' }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('vendor.products.*') ? 'active' : '' }}">
                            <i class="bx bx-box side-menu__icon"></i>
                            <span class="side-menu__label">Products</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ route('vendor.products.index') }}" class="side-menu__item {{ request()->routeIs('vendor.products.index') ? 'active' : '' }}">
                                    <i class="bx bx-list-ul"></i> All Products
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('vendor.products.create') }}" class="side-menu__item {{ request()->routeIs('vendor.products.create') ? 'active' : '' }}">
                                    <i class="bx bx-plus"></i> Add Product
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Orders -->
                    <li class="slide has-sub {{ request()->routeIs('vendor.orders.*') ? 'open' : '' }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('vendor.orders.*') ? 'active' : '' }}">
                            <i class="bx bx-shopping-bag side-menu__icon"></i>
                            <span class="side-menu__label">Orders</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ route('vendor.orders.index') }}" class="side-menu__item {{ request()->routeIs('vendor.orders.index') ? 'active' : '' }}">
                                    <i class="bx bx-list-ul"></i> All Orders
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('vendor.orders.pending') }}" class="side-menu__item {{ request()->routeIs('vendor.orders.pending') ? 'active' : '' }}">
                                    <i class="bx bx-time"></i> Pending Orders
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('vendor.orders.completed') }}" class="side-menu__item {{ request()->routeIs('vendor.orders.completed') ? 'active' : '' }}">
                                    <i class="bx bx-check"></i> Completed Orders
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Reports -->
                    <li class="slide">
                        <a href="{{ route('vendor.reports.index') }}" class="side-menu__item {{ request()->routeIs('vendor.reports.*') ? 'active' : '' }}">
                            <i class="bx bx-bar-chart side-menu__icon"></i>
                            <span class="side-menu__label">Sales Reports</span>
                        </a>
                    </li>

                    <!-- Settings -->
                    <li class="slide">
                        <a href="{{ route('vendor.settings.index') }}" class="side-menu__item {{ request()->routeIs('vendor.settings.*') ? 'active' : '' }}">
                            <i class="bx bx-cog side-menu__icon"></i>
                            <span class="side-menu__label">Settings</span>
                        </a>
                    </li>

                    <!-- Logout -->
                    <li class="slide">
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="side-menu__item w-100 text-start border-0 bg-transparent">
                                <i class="bx bx-power-off side-menu__icon"></i>
                                <span class="side-menu__label">Logout</span>
                            </button>
                        </form>
                    </li>
                @else
                    {{-- Admin Menu Items - Dynamic Menu System --}}
                    @php
                        use App\Helpers\AdminMenuHelper;
                    @endphp
                    
                    {!! AdminMenuHelper::generate('sidebar') !!}
                    
                    {{-- Admin Menu Management (Always available) --}}
                    <li class="slide__category"><span class="category-name">System</span></li>
                    
                    <!-- Menu Management -->
                    <li class="slide has-sub {{ request()->routeIs('admin.menu.*') ? 'open' : '' }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
                            <i class="bx bx-menu side-menu__icon"></i>
                            <span class="side-menu__label">Menu Management</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ route('admin.menu.index') }}" class="side-menu__item {{ request()->routeIs('admin.menu.index') ? 'active' : '' }}">
                                    <i class="bx bx-list-ul"></i> All Menus
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.menu.create') }}" class="side-menu__item {{ request()->routeIs('admin.menu.create') ? 'active' : '' }}">
                                    <i class="bx bx-plus"></i> Add Menu Item
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.menu.builder') }}" class="side-menu__item {{ request()->routeIs('admin.menu.builder') ? 'active' : '' }}">
                                    <i class="bx bx-customize"></i> Menu Builder
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Logout -->
                    <li class="slide">
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="side-menu__item w-100 text-start border-0 bg-transparent">
                                <i class="bx bx-power-off side-menu__icon"></i>
                                <span class="side-menu__label">Logout</span>
                            </button>
                        </form>
                    </li>
                @endif
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
        </nav>
        <!-- End::nav -->
    </div>
    <!-- End::main-sidebar -->
</aside>
<!-- End::app-sidebar -->
