{{-- Modern Interactive Admin Sidebar --}}
<div class="modern-sidebar" id="modernSidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <i class='bx bx-store-alt'></i>
            <span class="brand-text">MultiVendor</span>
        </div>
        <div class="sidebar-toggle" id="sidebarToggle">
            <i class='bx bx-menu'></i>
        </div>
    </div>

    <div class="sidebar-content">
        <div class="sidebar-menu">
            {{-- Quick Stats --}}
            <div class="quick-stats">
                <div class="stat-item">
                    <div class="stat-icon bg-primary">
                        <i class='bx bx-shopping-bag'></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value" id="todayOrders">0</div>
                        <div class="stat-label">Today Orders</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon bg-success">
                        <i class='bx bx-dollar'></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value" id="todayRevenue">$0</div>
                        <div class="stat-label">Today Revenue</div>
                    </div>
                </div>
            </div>

            {{-- Navigation Menu --}}
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <ul class="nav-menu">
                    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">
                            <i class='bx bx-home-alt nav-icon'></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Ecommerce Section --}}
            <div class="nav-section">
                <div class="nav-section-title">E-commerce</div>
                <ul class="nav-menu">
                    <li class="nav-item has-submenu {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <a href="#" class="nav-link">
                            <i class='bx bx-shopping-bag nav-icon'></i>
                            <span class="nav-text">Orders</span>
                            <i class='bx bx-chevron-right nav-arrow'></i>
                            <span class="nav-badge">5</span>
                        </a>
                        <ul class="nav-submenu">
                            <li><a href="{{ route('admin.orders.index') }}">All Orders</a></li>
                            <li><a href="{{ route('admin.orders.pending') }}">Pending</a></li>
                            <li><a href="{{ route('admin.orders.processing') }}">Processing</a></li>
                            <li><a href="{{ route('admin.orders.shipped') }}">Shipped</a></li>
                            <li><a href="{{ route('admin.orders.delivered') }}">Delivered</a></li>
                            <li><a href="{{ route('admin.orders.cancelled') }}">Cancelled</a></li>
                        </ul>
                    </li>

                    <li class="nav-item has-submenu {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <a href="#" class="nav-link">
                            <i class='bx bx-package nav-icon'></i>
                            <span class="nav-text">Products</span>
                            <i class='bx bx-chevron-right nav-arrow'></i>
                        </a>
                        <ul class="nav-submenu">
                            <li><a href="{{ route('admin.products.index') }}">All Products</a></li>
                            <li><a href="{{ route('admin.products.create') }}">Add Product</a></li>
                            <li><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                            <li><a href="{{ route('admin.brands.index') }}">Brands</a></li>
                        </ul>
                    </li>

                    <li class="nav-item has-submenu {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                        <a href="#" class="nav-link">
                            <i class='bx bx-box nav-icon'></i>
                            <span class="nav-text">Inventory</span>
                            <i class='bx bx-chevron-right nav-arrow'></i>
                        </a>
                        <ul class="nav-submenu">
                            <li><a href="{{ route('admin.inventory.stock') }}">Stock Management</a></li>
                            <li><a href="{{ route('admin.inventory.low-stock') }}">Low Stock</a></li>
                            <li><a href="{{ route('admin.inventory.out-of-stock') }}">Out of Stock</a></li>
                        </ul>
                    </li>

                    <li class="nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.customers.index') }}" class="nav-link">
                            <i class='bx bx-user nav-icon'></i>
                            <span class="nav-text">Customers</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Vendors Section --}}
            <div class="nav-section">
                <div class="nav-section-title">Vendors</div>
                <ul class="nav-menu">
                    <li class="nav-item has-submenu {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
                        <a href="#" class="nav-link">
                            <i class='bx bx-store nav-icon'></i>
                            <span class="nav-text">Vendors</span>
                            <i class='bx bx-chevron-right nav-arrow'></i>
                            <span class="nav-badge pending">3</span>
                        </a>
                        <ul class="nav-submenu">
                            <li><a href="{{ route('admin.vendors.index') }}">All Vendors</a></li>
                            <li><a href="{{ route('admin.vendors.pending') }}">Pending Approval</a></li>
                            <li><a href="{{ route('admin.vendors.approved') }}">Approved</a></li>
                            <li><a href="{{ route('admin.vendors.suspended') }}">Suspended</a></li>
                            <li><a href="{{ route('admin.vendors.commissions') }}">Commissions</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            {{-- Marketing Section --}}
            <div class="nav-section">
                <div class="nav-section-title">Marketing</div>
                <ul class="nav-menu">
                    <li class="nav-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.coupons.index') }}" class="nav-link">
                            <i class='bx bx-tag nav-icon'></i>
                            <span class="nav-text">Coupons</span>
                        </a>
                    </li>

                    <li class="nav-item has-submenu {{ request()->routeIs('admin.marketing.*') ? 'active' : '' }}">
                        <a href="#" class="nav-link">
                            <i class='bx bx-bullhorn nav-icon'></i>
                            <span class="nav-text">Marketing</span>
                            <i class='bx bx-chevron-right nav-arrow'></i>
                        </a>
                        <ul class="nav-submenu">
                            <li><a href="{{ route('admin.marketing.banners') }}">Banners</a></li>
                            <li><a href="{{ route('admin.marketing.promotions') }}">Promotions</a></li>
                            <li><a href="{{ route('admin.marketing.newsletters') }}">Newsletters</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            {{-- Reports Section --}}
            <div class="nav-section">
                <div class="nav-section-title">Analytics</div>
                <ul class="nav-menu">
                    <li class="nav-item has-submenu {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <a href="#" class="nav-link">
                            <i class='bx bx-bar-chart-alt-2 nav-icon'></i>
                            <span class="nav-text">Reports</span>
                            <i class='bx bx-chevron-right nav-arrow'></i>
                        </a>
                        <ul class="nav-submenu">
                            <li><a href="{{ route('admin.reports.sales') }}">Sales Report</a></li>
                            <li><a href="{{ route('admin.reports.products') }}">Product Report</a></li>
                            <li><a href="{{ route('admin.reports.vendors') }}">Vendor Report</a></li>
                            <li><a href="{{ route('admin.reports.customers') }}">Customer Report</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            {{-- System Section --}}
            <div class="nav-section">
                <div class="nav-section-title">System</div>
                <ul class="nav-menu">
                    <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.users.index') }}" class="nav-link">
                            <i class='bx bx-user-circle nav-icon'></i>
                            <span class="nav-text">Users</span>
                        </a>
                    </li>

                    <li class="nav-item has-submenu {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <a href="#" class="nav-link">
                            <i class='bx bx-cog nav-icon'></i>
                            <span class="nav-text">Settings</span>
                            <i class='bx bx-chevron-right nav-arrow'></i>
                        </a>
                        <ul class="nav-submenu">
                            <li><a href="{{ route('admin.settings.general') }}">General</a></li>
                            <li><a href="{{ route('admin.settings.payment') }}">Payment</a></li>
                            <li><a href="{{ route('admin.settings.shipping') }}">Shipping</a></li>
                            <li><a href="{{ route('admin.settings.email') }}">Email</a></li>
                        </ul>
                    </li>

                    <li class="nav-item has-submenu {{ request()->routeIs('admin.website.*') ? 'active' : '' }}">
                        <a href="#" class="nav-link">
                            <i class='bx bx-world nav-icon'></i>
                            <span class="nav-text">Website</span>
                            <i class='bx bx-chevron-right nav-arrow'></i>
                        </a>
                        <ul class="nav-submenu">
                            <li><a href="{{ route('admin.website.pages') }}">Pages</a></li>
                            <li><a href="{{ route('admin.website.menus') }}">Menus</a></li>
                            <li><a href="{{ route('admin.website.themes') }}">Themes</a></li>
                            <li><a href="{{ route('admin.website.seo') }}">SEO</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Sidebar Footer --}}
        <div class="sidebar-footer">
            <div class="footer-user">
                <div class="user-avatar">
                    <i class='bx bx-user'></i>
                </div>
                <div class="user-info">
                    <div class="user-name">Admin User</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
            
            <div class="footer-actions">
                <a href="{{ route('admin.profile') }}" class="footer-action" title="Profile">
                    <i class='bx bx-user'></i>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="footer-action" title="Settings">
                    <i class='bx bx-cog'></i>
                </a>
                <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="footer-action" title="Logout">
                        <i class='bx bx-log-out'></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Mobile Overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>
