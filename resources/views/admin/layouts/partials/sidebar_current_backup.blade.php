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
                    {{-- Admin Menu Items - Enhanced Dynamic Menu System --}}
                    @php
                        $adminMenusLoaded = false;
                        $menuError = null;
                    @endphp
                    
                    @if(class_exists('\App\Helpers\AdminMenuHelper'))
                        @php
                            try {
                                $dynamicMenus = \App\Helpers\AdminMenuHelper::generate('sidebar');
                                $adminMenusLoaded = !empty($dynamicMenus);
                                
                                // Debug output in development
                                if (config('app.debug') && !$adminMenusLoaded) {
                                    echo '<li class="slide__category"><span class="category-name text-warning">⚠️ Dynamic Menu Debug</span></li>';
                                    echo '<li class="slide"><a class="side-menu__item text-warning"><i class="bx bx-error"></i><span class="side-menu__label">Dynamic menu empty - using fallback</span></a></li>';
                                }
                                
                                echo $dynamicMenus;
                            } catch (\Exception $e) {
                                $menuError = $e->getMessage();
                                $adminMenusLoaded = false;
                                
                                // Debug output in development
                                if (config('app.debug')) {
                                    echo '<li class="slide__category"><span class="category-name text-danger">❌ Dynamic Menu Error</span></li>';
                                    echo '<li class="slide"><a class="side-menu__item text-danger"><i class="bx bx-error"></i><span class="side-menu__label">' . htmlspecialchars(substr($e->getMessage(), 0, 50)) . '...</span></a></li>';
                                }
                            }
                        @endphp
                    @endif
                    
                    @if(!$adminMenusLoaded)
                        {{-- Enhanced Fallback Static Menu --}}
                        <li class="slide__category"><span class="category-name">Main Dashboard</span></li>
                        <li class="slide">
                            <a href="{{ route('admin.dashboard') }}" class="side-menu__item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="bx bx-home side-menu__icon"></i>
                                <span class="side-menu__label">Dashboard</span>
                            </a>
                        </li>
                        
                        <li class="slide__category"><span class="category-name">Ecommerce Management</span></li>
                        <li class="slide has-sub {{ request()->routeIs('admin.products.*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                <i class="bx bx-package side-menu__icon"></i>
                                <span class="side-menu__label">Products</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="{{ route('admin.products.index') }}" class="side-menu__item {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                                        <i class="bx bx-list-ul"></i> All Products
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.products.create') }}" class="side-menu__item {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                                        <i class="bx bx-plus"></i> Add Product
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.products.analytics') }}" class="side-menu__item {{ request()->routeIs('admin.products.analytics') ? 'active' : '' }}">
                                        <i class="bx bx-line-chart"></i> Product Analytics
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.products.bulk-import') }}" class="side-menu__item {{ request()->routeIs('admin.products.bulk-import') ? 'active' : '' }}">
                                        <i class="bx bx-import"></i> Bulk Import
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.products.export') }}" class="side-menu__item {{ request()->routeIs('admin.products.export') ? 'active' : '' }}">
                                        <i class="bx bx-export"></i> Export Products
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="slide has-sub {{ request()->routeIs('admin.categories.*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                <i class="bx bx-category side-menu__icon"></i>
                                <span class="side-menu__label">Categories</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="{{ route('admin.categories.index') }}" class="side-menu__item {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                                        <i class="bx bx-list-ul"></i> All Categories
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.categories.create') }}" class="side-menu__item {{ request()->routeIs('admin.categories.create') ? 'active' : '' }}">
                                        <i class="bx bx-plus"></i> Add Category
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.categories.tree') }}" class="side-menu__item {{ request()->routeIs('admin.categories.tree') ? 'active' : '' }}">
                                        <i class="bx bx-sitemap"></i> Category Tree
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.categories.bulk-import') }}" class="side-menu__item {{ request()->routeIs('admin.categories.bulk-import') ? 'active' : '' }}">
                                        <i class="bx bx-import"></i> Bulk Import
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.categories.export') }}" class="side-menu__item {{ request()->routeIs('admin.categories.export') ? 'active' : '' }}">
                                        <i class="bx bx-export"></i> Export Categories
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="slide has-sub {{ request()->routeIs('admin.brands.*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                                <i class="bx bx-bookmark side-menu__icon"></i>
                                <span class="side-menu__label">Brands</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="{{ route('admin.brands.index') }}" class="side-menu__item {{ request()->routeIs('admin.brands.index') ? 'active' : '' }}">
                                        <i class="bx bx-list-ul"></i> All Brands
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.brands.create') }}" class="side-menu__item {{ request()->routeIs('admin.brands.create') ? 'active' : '' }}">
                                        <i class="bx bx-plus"></i> Add Brand
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.brands.featured') }}" class="side-menu__item {{ request()->routeIs('admin.brands.featured') ? 'active' : '' }}">
                                        <i class="bx bx-star"></i> Featured Brands
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.brands.analytics') }}" class="side-menu__item {{ request()->routeIs('admin.brands.analytics') ? 'active' : '' }}">
                                        <i class="bx bx-line-chart"></i> Brand Analytics
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.brands.bulk-import') }}" class="side-menu__item {{ request()->routeIs('admin.brands.bulk-import') ? 'active' : '' }}">
                                        <i class="bx bx-import"></i> Bulk Import
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.brands.export') }}" class="side-menu__item {{ request()->routeIs('admin.brands.export') ? 'active' : '' }}">
                                        <i class="bx bx-export"></i> Export Brands
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="slide has-sub {{ request()->routeIs('admin.attributes.*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}">
                                <i class="bx bx-detail side-menu__icon"></i>
                                <span class="side-menu__label">Attributes</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="{{ route('admin.attributes.index') }}" class="side-menu__item {{ request()->routeIs('admin.attributes.index') ? 'active' : '' }}">
                                        <i class="bx bx-list-ul"></i> All Attributes
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.attributes.create') }}" class="side-menu__item {{ request()->routeIs('admin.attributes.create') ? 'active' : '' }}">
                                        <i class="bx bx-plus"></i> Add Attribute
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.attributes.colors') }}" class="side-menu__item {{ request()->routeIs('admin.attributes.colors') ? 'active' : '' }}">
                                        <i class="bx bx-palette"></i> Colors
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.attributes.sizes') }}" class="side-menu__item {{ request()->routeIs('admin.attributes.sizes') ? 'active' : '' }}">
                                        <i class="bx bx-ruler"></i> Sizes
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.attributes.materials') }}" class="side-menu__item {{ request()->routeIs('admin.attributes.materials') ? 'active' : '' }}">
                                        <i class="bx bx-atom"></i> Materials
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.attributes.groups') }}" class="side-menu__item {{ request()->routeIs('admin.attributes.groups') ? 'active' : '' }}">
                                        <i class="bx bx-group"></i> Attribute Groups
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.attributes.analytics') }}" class="side-menu__item {{ request()->routeIs('admin.attributes.analytics') ? 'active' : '' }}">
                                        <i class="bx bx-line-chart"></i> Analytics
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="slide has-sub {{ request()->routeIs('admin.orders.*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                <i class="bx bx-shopping-bag side-menu__icon"></i>
                                <span class="side-menu__label">Orders</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="{{ route('admin.orders.index') }}" class="side-menu__item {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                                        <i class="bx bx-list-ul"></i> All Orders
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="#" class="side-menu__item">
                                        <i class="bx bx-time"></i> Pending Orders
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="slide">
                            <a href="{{ route('admin.users.index') }}" class="side-menu__item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="bx bx-user side-menu__icon"></i>
                                <span class="side-menu__label">Users</span>
                            </a>
                        </li>
                        
                        <li class="slide has-sub {{ request()->routeIs('admin.customers.*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                                <i class="bx bx-user-check side-menu__icon"></i>
                                <span class="side-menu__label">Customers</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="{{ route('admin.customers.index') }}" class="side-menu__item {{ request()->routeIs('admin.customers.index') ? 'active' : '' }}">
                                        <i class="bx bx-list-ul"></i> All Customers
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.customers.create') }}" class="side-menu__item {{ request()->routeIs('admin.customers.create') ? 'active' : '' }}">
                                        <i class="bx bx-plus"></i> Add Customer
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.customers.analytics') }}" class="side-menu__item {{ request()->routeIs('admin.customers.analytics') ? 'active' : '' }}">
                                        <i class="bx bx-line-chart"></i> Customer Analytics
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.customers.export') }}" class="side-menu__item {{ request()->routeIs('admin.customers.export') ? 'active' : '' }}">
                                        <i class="bx bx-export"></i> Export Customers
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="slide has-sub {{ request()->routeIs('admin.vendors.*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
                                <i class="bx bx-store side-menu__icon"></i>
                                <span class="side-menu__label">Vendors</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="{{ route('admin.vendors.index') }}" class="side-menu__item {{ request()->routeIs('admin.vendors.index') ? 'active' : '' }}">
                                        <i class="bx bx-list-ul"></i> All Vendors
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.vendors.applications') }}" class="side-menu__item {{ request()->routeIs('admin.vendors.applications') ? 'active' : '' }}">
                                        <i class="bx bx-file"></i> Applications
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.vendors.approved') }}" class="side-menu__item {{ request()->routeIs('admin.vendors.approved') ? 'active' : '' }}">
                                        <i class="bx bx-check-circle"></i> Approved Vendors
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.vendors.pending') }}" class="side-menu__item {{ request()->routeIs('admin.vendors.pending') ? 'active' : '' }}">
                                        <i class="bx bx-time"></i> Pending Vendors
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.vendors.suspended') }}" class="side-menu__item {{ request()->routeIs('admin.vendors.suspended') ? 'active' : '' }}">
                                        <i class="bx bx-block"></i> Suspended Vendors
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.vendors.commissions') }}" class="side-menu__item {{ request()->routeIs('admin.vendors.commissions') ? 'active' : '' }}">
                                        <i class="bx bx-money"></i> Commissions
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.vendors.analytics') }}" class="side-menu__item {{ request()->routeIs('admin.vendors.analytics') ? 'active' : '' }}">
                                        <i class="bx bx-line-chart"></i> Vendor Analytics
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="slide__category"><span class="category-name">System Settings</span></li>
                        <li class="slide has-sub {{ request()->routeIs('admin.general-settings.*') || request()->routeIs('admin.settings.*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.general-settings.*') || request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                <i class="bx bx-cog side-menu__icon"></i>
                                <span class="side-menu__label">General Settings</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.index') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.index') ? 'active' : '' }}">
                                        <i class="bx bx-cog"></i> General Settings
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.company-info') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.company-info') ? 'active' : '' }}">
                                        <i class="bx bx-building"></i> Company Info
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.mail-config') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.mail-config') ? 'active' : '' }}">
                                        <i class="bx bx-envelope"></i> Mail Config
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.fee-settings') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.fee-settings') ? 'active' : '' }}">
                                        <i class="bx bx-credit-card"></i> Fee Settings
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.security') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.security') ? 'active' : '' }}">
                                        <i class="bx bx-shield"></i> Security
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.seo') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.seo') ? 'active' : '' }}">
                                        <i class="bx bx-search-alt"></i> SEO Settings
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.theme') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.theme') ? 'active' : '' }}">
                                        <i class="bx bx-palette"></i> Theme
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.media') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.media') ? 'active' : '' }}">
                                        <i class="bx bx-image"></i> Media Settings
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.social-media') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.social-media') ? 'active' : '' }}">
                                        <i class="bx bx-share-alt"></i> Social Media
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.system-info') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.system-info') ? 'active' : '' }}">
                                        <i class="bx bx-info-circle"></i> System Info
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.content') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.content') ? 'active' : '' }}">
                                        <i class="bx bx-file-blank"></i> Content Settings
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.maintenance') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.maintenance') ? 'active' : '' }}">
                                        <i class="bx bx-wrench"></i> Maintenance
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.backup') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.backup') ? 'active' : '' }}">
                                        <i class="bx bx-data"></i> Backup Settings
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.notifications') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.notifications') ? 'active' : '' }}">
                                        <i class="bx bx-bell"></i> Notifications
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.api') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.api') ? 'active' : '' }}">
                                        <i class="bx bx-code"></i> API Settings
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.shipping') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.shipping') ? 'active' : '' }}">
                                        <i class="bx bx-package"></i> Shipping Settings
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.payment') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.payment') ? 'active' : '' }}">
                                        <i class="bx bx-credit-card"></i> Payment Settings
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.analytics') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.analytics') ? 'active' : '' }}">
                                        <i class="bx bx-line-chart"></i> Analytics
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.integrations') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.integrations') ? 'active' : '' }}">
                                        <i class="bx bx-plug"></i> Integrations
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.cache') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.cache') ? 'active' : '' }}">
                                        <i class="bx bx-data"></i> Cache Settings
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.logs') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.logs') ? 'active' : '' }}">
                                        <i class="bx bx-history"></i> System Logs
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.database') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.database') ? 'active' : '' }}">
                                        <i class="bx bx-data"></i> Database
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.import') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.import') ? 'active' : '' }}">
                                        <i class="bx bx-import"></i> Import Data
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.general-settings.export') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.export') ? 'active' : '' }}">
                                        <i class="bx bx-export"></i> Export Data
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        @if($menuError && config('app.debug'))
                            <li class="slide">
                                <a href="#" onclick="showMenuError()" class="side-menu__item text-warning">
                                    <i class="bx bx-error side-menu__icon"></i>
                                    <span class="side-menu__label">Menu Debug</span>
                                </a>
                            </li>
                        @endif
                    @endif
                    
                    {{-- System Tools and Logout (Always Available for Admin) --}}
                    @if(!$adminMenusLoaded)
                        {{-- Only show hardcoded Menu Management if dynamic menus failed to load --}}
                        <li class="slide__category"><span class="category-name">System Management</span></li>
                        
                        <li class="slide has-sub {{ request()->routeIs('admin.menu.*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
                                <i class="bx bx-menu side-menu__icon"></i>
                                <span class="side-menu__label">Menu Management</span>
                                <span class="badge bg-warning ms-auto">Fallback</span>
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
                                <li class="slide">
                                    <a href="#" onclick="showKeyboardShortcuts()" class="side-menu__item">
                                        <i class="bx bx-keyboard"></i> Keyboard Shortcuts
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
                    @endif
                    
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
// Enhanced sidebar functionality
document.addEventListener('DOMContentLoaded', function() {
    initSidebar();
    fixActiveMenuStyling();
});

function fixActiveMenuStyling() {
    // Wait a bit for the page to fully load, then fix active menu styling
    setTimeout(() => {
        const activeItems = document.querySelectorAll('.side-menu__item.active');
        activeItems.forEach(item => {
            // Ensure proper styling for active menu items
            item.style.setProperty('background', 'linear-gradient(135deg, #007bff, #0056b3)', 'important');
            item.style.setProperty('color', '#ffffff', 'important');
            item.style.setProperty('border-radius', '6px', 'important');
            
            // Style all child elements
            const childElements = item.querySelectorAll('*');
            childElements.forEach(child => {
                child.style.setProperty('color', '#ffffff', 'important');
            });
            
            // Specific styling for icons and labels
            const icons = item.querySelectorAll('.side-menu__icon, .bx, .fe, i');
            icons.forEach(icon => {
                icon.style.setProperty('color', '#ffffff', 'important');
            });
            
            const labels = item.querySelectorAll('.side-menu__label, span');
            labels.forEach(label => {
                label.style.setProperty('color', '#ffffff', 'important');
                label.style.setProperty('font-weight', '500', 'important');
            });
        });
    }, 100);
}

function initSidebar() {
    // Auto-collapse menu functionality
    const subMenuToggles = document.querySelectorAll('.has-sub > .side-menu__item');
    subMenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parentLi = this.closest('.has-sub');
            const isOpen = parentLi.classList.contains('open');
            
            // Close all other sub-menus at the same level
            const siblings = parentLi.parentElement.children;
            Array.from(siblings).forEach(sibling => {
                if (sibling !== parentLi && sibling.classList.contains('has-sub')) {
                    sibling.classList.remove('open');
                }
            });
            
            // Toggle current menu
            parentLi.classList.toggle('open');
        });
    });

    // Ensure active menu items have proper styling
    document.querySelectorAll('.side-menu__item.active').forEach(item => {
        // Force white text for active items
        item.style.color = '#ffffff';
        const label = item.querySelector('.side-menu__label');
        if (label) label.style.color = '#ffffff';
        const icon = item.querySelector('.side-menu__icon, .bx, .fe');
        if (icon) icon.style.color = '#ffffff';
        
        // Apply to all child elements
        const allElements = item.querySelectorAll('*');
        allElements.forEach(el => {
            el.style.color = '#ffffff';
        });
    });

    // Save menu state in localStorage
    const openMenus = document.querySelectorAll('.has-sub.open');
    const menuState = Array.from(openMenus).map(menu => {
        const label = menu.querySelector('.side-menu__label');
        return label ? label.textContent.trim() : '';
    }).filter(Boolean);
    localStorage.setItem('sidebar_menu_state', JSON.stringify(menuState));
}

// Cache management functions
function clearMenuCache() {
    if (confirm('Are you sure you want to clear the menu cache? This will refresh the dynamic menu system.')) {
        showNotification('Clearing menu cache...', 'info');
        
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
                showNotification('Menu cache cleared successfully!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error clearing cache. Please try again.', 'error');
        });
    }
}

function showKeyboardShortcuts() {
    const shortcuts = `⌨️ Available Keyboard Shortcuts:
    
🏠 Ctrl + D = Navigate to Dashboard
📋 Ctrl + M = Open Menu Management
🔧 Ctrl + B = Open Menu Builder

💡 Tip: These shortcuts work from any admin page!
🔄 Press F5 to refresh and see changes
🔍 Use browser dev tools (F12) for debugging

Would you like to copy these shortcuts for reference?`;

    if (confirm(shortcuts)) {
        const shortcutText = `Admin Panel Keyboard Shortcuts:
- Ctrl + D: Dashboard
- Ctrl + M: Menu Management  
- Ctrl + B: Menu Builder
- F5: Refresh page
- F12: Developer tools`;

        navigator.clipboard.writeText(shortcutText).then(() => {
            showNotification('Keyboard shortcuts copied to clipboard!', 'success');
        }).catch(() => {
            showNotification('Please copy the shortcuts manually from the dialog.', 'info');
        });
    }
}

function showSystemInfo() {
    const info = `System Information:
    
📊 Laravel Version: {{ app()->version() }}
🐘 PHP Version: {{ phpversion() }}
🌍 Environment: {{ app()->environment() }}
🔧 Debug Mode: {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
📅 Current Date: {{ date('Y-m-d H:i:s') }}
👤 Current User: {{ Auth::user()->name ?? 'Unknown' }}
🏷️ User Role: {{ Auth::user()->role ?? 'Unknown' }}

🎯 Admin Menu System: {{ class_exists('\App\Helpers\AdminMenuHelper') ? 'Active' : 'Inactive' }}
📋 Total Routes: {{ count(Route::getRoutes()) }}`;

    if (confirm(info + '\n\nWould you like to copy this information?')) {
        navigator.clipboard.writeText(info).then(() => {
            showNotification('System info copied to clipboard!', 'success');
        });
    }
}

function showMenuError() {
    @if(isset($menuError))
        const errorInfo = `Menu System Error:
        
❌ Error Message: {{ $menuError }}
🔧 Fix: Check AdminMenuHelper class and database connection
📋 Fallback: Static menu is currently active

Troubleshooting Steps:
1. Clear application cache
2. Check database connection
3. Verify AdminMenu model exists
4. Check AdminMenuHelper class
5. Review error logs`;

        alert(errorInfo);
    @endif
}

function showNotification(message, type = 'info', duration = 5000) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 450px; font-size: 14px;';
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bx bx-${type === 'success' ? 'check-circle' : type === 'error' ? 'error' : 'info-circle'} me-2"></i>
            <span>${message}</span>
        </div>
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after specified duration
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, duration);
}

// Menu state restoration
function restoreMenuState() {
    const savedState = localStorage.getItem('sidebar_menu_state');
    if (savedState) {
        const menuLabels = JSON.parse(savedState);
        menuLabels.forEach(label => {
            const menuItem = Array.from(document.querySelectorAll('.side-menu__label'))
                .find(el => el.textContent.trim() === label);
            if (menuItem) {
                const parentLi = menuItem.closest('.has-sub');
                if (parentLi) {
                    parentLi.classList.add('open');
                }
            }
        });
    }
}

// Initialize on page load
setTimeout(restoreMenuState, 100);

// Quick menu access shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl + M = Menu Management
    if (e.ctrlKey && e.key === 'm') {
        e.preventDefault();
        window.location.href = '{{ route("admin.menu.index") }}';
    }
    
    // Ctrl + B = Menu Builder
    if (e.ctrlKey && e.key === 'b') {
        e.preventDefault();
        window.location.href = '{{ route("admin.menu.builder") }}';
    }
    
    // Ctrl + D = Dashboard
    if (e.ctrlKey && e.key === 'd') {
        e.preventDefault();
        window.location.href = '{{ route("admin.dashboard") }}';
    }
});

// Display keyboard shortcuts on first visit only
document.addEventListener('DOMContentLoaded', function() {
    // Only show shortcuts popup once per browser session
    const hasSeenShortcuts = sessionStorage.getItem('shortcuts_intro_shown');
    
    if (!hasSeenShortcuts) {
        setTimeout(() => {
            // Show a more subtle notification instead of a blocking confirm dialog
            showNotification('💡 Keyboard Shortcuts: Ctrl+D (Dashboard), Ctrl+M (Menu), Ctrl+B (Builder)', 'info', 8000);
            
            // Mark as shown so it doesn't appear again this session
            sessionStorage.setItem('shortcuts_intro_shown', 'true');
        }, 2000);
    }
});
</script>

<style>
.side-menu__item:hover {
    background-color: rgba(0, 123, 255, 0.1);
    transform: translateX(3px);
    transition: all 0.3s ease;
}

.side-menu__item.active {
    background: linear-gradient(135deg, #007bff, #0056b3) !important;
    box-shadow: 0 2px 10px rgba(0, 123, 255, 0.3);
    color: #ffffff !important;
    border-radius: 6px;
}

.side-menu__item.active .side-menu__label {
    color: #ffffff !important;
    font-weight: 500;
}

.side-menu__item.active .side-menu__icon {
    color: #ffffff !important;
}

.side-menu__item.active i {
    color: #ffffff !important;
}

/* Ensure text visibility in active submenu items */
.slide-menu .side-menu__item.active {
    background: linear-gradient(135deg, #007bff, #0056b3) !important;
    color: #ffffff !important;
    border-radius: 4px;
    margin: 2px 0;
}

.slide-menu .side-menu__item.active * {
    color: #ffffff !important;
}

/* Improve hover states */
.side-menu__item:hover:not(.active) {
    background-color: rgba(0, 123, 255, 0.1);
    color: #007bff;
    border-radius: 6px;
}

.badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Additional text contrast fixes */
.has-sub.open > .side-menu__item.active {
    background: linear-gradient(135deg, #007bff, #0056b3) !important;
    color: #ffffff !important;
}

.has-sub.open > .side-menu__item.active * {
    color: #ffffff !important;
}

/* Ensure icons are visible */
.side-menu__item.active .bx,
.side-menu__item.active .fe {
    color: #ffffff !important;
}
</style>
