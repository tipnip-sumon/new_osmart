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
                    {{-- Vendor Menu Items --}}
                    <li class="slide__category"><span class="category-name">Vendor Panel</span></li>
                    <li class="slide">
                        <a href="{{ route('vendor.dashboard') }}" class="side-menu__item {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                            <i class="bx bx-home side-menu__icon"></i>
                            <span class="side-menu__label">Dashboard</span>
                        </a>
                    </li>

                    <li class="slide">
                        <a href="{{ route('vendor.profile') }}" class="side-menu__item {{ request()->routeIs('vendor.profile*') ? 'active' : '' }}">
                            <i class="bx bx-user side-menu__icon"></i>
                            <span class="side-menu__label">My Profile</span>
                        </a>
                    </li>

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

                    <li class="slide">
                        <a href="{{ route('vendor.reports.index') }}" class="side-menu__item {{ request()->routeIs('vendor.reports.*') ? 'active' : '' }}">
                            <i class="bx bx-bar-chart side-menu__icon"></i>
                            <span class="side-menu__label">Sales Reports</span>
                        </a>
                    </li>

                    <li class="slide">
                        <a href="{{ route('vendor.settings.index') }}" class="side-menu__item {{ request()->routeIs('vendor.settings.*') ? 'active' : '' }}">
                            <i class="bx bx-cog side-menu__icon"></i>
                            <span class="side-menu__label">Settings</span>
                        </a>
                    </li>

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
                    @if(class_exists('\App\Helpers\AdminMenuHelper'))
                        {!! \App\Helpers\AdminMenuHelper::generate('sidebar') !!}
                    @else
                        {{-- Fallback static menu --}}
                        <li class="slide__category"><span class="category-name">Main</span></li>
                        <li class="slide">
                            <a href="{{ route('admin.dashboard') }}" class="side-menu__item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="bx bx-home side-menu__icon"></i>
                                <span class="side-menu__label">Dashboard</span>
                            </a>
                        </li>
                        
                        <li class="slide__category"><span class="category-name">Ecommerce</span></li>
                        <li class="slide">
                            <a href="{{ route('admin.products.index') }}" class="side-menu__item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                <i class="bx bx-package side-menu__icon"></i>
                                <span class="side-menu__label">Products</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.orders.index') }}" class="side-menu__item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                <i class="bx bx-shopping-bag side-menu__icon"></i>
                                <span class="side-menu__label">Orders</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.users.index') }}" class="side-menu__item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="bx bx-user side-menu__icon"></i>
                                <span class="side-menu__label">Users</span>
                            </a>
                        </li>
                        
                        <li class="slide__category"><span class="category-name">Settings</span></li>
                        <li class="slide">
                            <a href="{{ route('admin.settings.general') }}" class="side-menu__item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                <i class="bx bx-cog side-menu__icon"></i>
                                <span class="side-menu__label">Settings</span>
                            </a>
                        </li>
                    @endif
                    
                    {{-- Menu Management System (Always Available for Admin) --}}
                    <li class="slide__category"><span class="category-name">System Management</span></li>
                    
                    <li class="slide has-sub {{ request()->routeIs('admin.menu.*') ? 'open' : '' }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
                            <i class="bx bx-menu side-menu__icon"></i>
                            <span class="side-menu__label">Menu Management</span>
                            <span class="badge bg-success ms-auto">New</span>
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
                            <li class="slide">
                                <a href="{{ route('admin.menu.demo') }}" class="side-menu__item {{ request()->routeIs('admin.menu.demo') ? 'active' : '' }}">
                                    <i class="bx bx-show"></i> System Demo
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.menu.settings') }}" class="side-menu__item {{ request()->routeIs('admin.menu.settings') ? 'active' : '' }}">
                                    <i class="bx bx-cog"></i> Sidebar Settings
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="slide has-sub {{ request()->routeIs('admin.system.*') || request()->routeIs('admin.cache.*') || request()->routeIs('admin.logs.*') ? 'open' : '' }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.system.*') || request()->routeIs('admin.cache.*') || request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                            <i class="bx bx-wrench side-menu__icon"></i>
                            <span class="side-menu__label">System Tools</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="#" onclick="clearMenuCache()" class="side-menu__item">
                                    <i class="bx bx-refresh"></i> Clear Menu Cache
                                </a>
                            </li>
                            @if(config('app.debug'))
                            <li class="slide">
                                <a href="#" onclick="showSystemInfo()" class="side-menu__item">
                                    <i class="bx bx-info-circle"></i> System Info
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    
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

<script>
// Cache management functions
function clearMenuCache() {
    if (confirm('Are you sure you want to clear the menu cache?')) {
        fetch('{{ route("admin.menu.clear-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Menu cache cleared successfully!');
                setTimeout(() => location.reload(), 1000);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error clearing cache. Please try again.');
        });
    }
}

function showSystemInfo() {
    alert('System Info:\nLaravel Version: {{ app()->version() }}\nPHP Version: {{ phpversion() }}\nEnvironment: {{ app()->environment() }}');
}
</script>

<style>
.side-menu__item:hover {
    background-color: rgba(0, 123, 255, 0.1);
    transform: translateX(3px);
}

.side-menu__item.active {
    background: linear-gradient(135deg, #007bff, #0056b3);
    box-shadow: 0 2px 10px rgba(0, 123, 255, 0.3);
}

.badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
</style>
