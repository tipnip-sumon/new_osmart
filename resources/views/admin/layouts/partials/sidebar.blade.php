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
            <img src="{{ asset('admin-assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="toggle-logo">
            <img src="{{ asset('admin-assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-dark">
            <img src="{{ asset('admin-assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="toggle-dark">
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

                    <!-- KYC Verification -->
                    <li class="slide">
                        <a href="{{ route('vendor.kyc.index') }}" class="side-menu__item {{ request()->routeIs('vendor.kyc.*') ? 'active' : '' }}">
                            <i class="bx bx-shield-check side-menu__icon"></i>
                            <span class="side-menu__label">KYC Verification</span>
                            @php
                                $vendorKyc = Auth::user()->vendorKyc ?? null;
                            @endphp
                            @if($vendorKyc)
                                @if($vendorKyc->status === 'approved')
                                    <span class="badge bg-success ms-auto">Verified</span>
                                @elseif($vendorKyc->status === 'rejected')
                                    <span class="badge bg-danger ms-auto">Rejected</span>
                                @elseif($vendorKyc->status === 'pending' || $vendorKyc->status === 'under_review')
                                    <span class="badge bg-warning ms-auto">Pending</span>
                                @else
                                    <span class="badge bg-primary ms-auto">{{ number_format($vendorKyc->completion_percentage, 2) }}%</span>
                                @endif
                            @else
                                <span class="badge bg-light text-dark ms-auto">Start</span>
                            @endif
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
                            <li class="slide">
                                <a href="#" class="side-menu__item text-muted">
                                    <i class="bx bx-category"></i> Categories
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

                    <!-- Transfer Management -->
                    <li class="slide has-sub {{ request()->routeIs('vendor.transfers.*') ? 'open' : '' }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('vendor.transfers.*') ? 'active' : '' }}">
                            <i class="bx bx-transfer side-menu__icon"></i>
                            <span class="side-menu__label">Transfer Management</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ route('vendor.transfers.index') }}" class="side-menu__item {{ request()->routeIs('vendor.transfers.index') ? 'active' : '' }}">
                                    <i class="bx bx-transfer-alt"></i> Send Transfer
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('vendor.transfers.fund-requests') }}" class="side-menu__item {{ request()->routeIs('vendor.transfers.fund-requests') ? 'active' : '' }}">
                                    <i class="bx bx-hand"></i> Fund Requests
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('vendor.transfers.history') }}" class="side-menu__item {{ request()->routeIs('vendor.transfers.history') ? 'active' : '' }}">
                                    <i class="bx bx-history"></i> Transfer History
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Mini Vendor Management -->
                    <li class="slide has-sub {{ request()->routeIs('vendor.mini-vendors.*') ? 'open' : '' }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('vendor.mini-vendors.*') ? 'active' : '' }}">
                            <i class="bx bx-user-plus side-menu__icon"></i>
                            <span class="side-menu__label">Mini Vendors</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ route('vendor.mini-vendors.index') }}" class="side-menu__item {{ request()->routeIs('vendor.mini-vendors.index') ? 'active' : '' }}">
                                    <i class="bx bx-list-ul"></i> All Mini Vendors
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('vendor.mini-vendors.create') }}" class="side-menu__item {{ request()->routeIs('vendor.mini-vendors.create') ? 'active' : '' }}">
                                    <i class="bx bx-plus"></i> Assign New
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Wallet -->
                    <li class="slide">
                        <a href="#" class="side-menu__item">
                            <i class="bx bx-wallet side-menu__icon"></i>
                            <span class="side-menu__label">My Wallet</span>
                            <span class="badge bg-success ms-auto">৳{{ number_format(Auth::user()->deposit_wallet ?? 0, 2) }}</span>
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
                        <form method="POST" action="{{ route('vendor.logout.vendor') }}" style="display: inline;" id="vendor-sidebar-logout-form">
                            @csrf
                            <button type="submit" class="side-menu__item w-100 text-start border-0 bg-transparent vendor-logout-btn" 
                                    title="Log out from vendor account">
                                <i class="bx bx-power-off side-menu__icon"></i>
                                <span class="side-menu__label">Logout</span>
                            </button>
                        </form>
                    </li>
                @else
                    {{-- Admin Menu Items (existing menu) --}}
                <!-- Dashboard -->
                <li class="slide__category"><span class="category-name">Main</span></li>
                <li class="slide">
                    <a href="{{ route('admin.dashboard') }}" class="side-menu__item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bx bx-home side-menu__icon"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>

                <!-- Admin Notices -->
                <li class="slide has-sub {{ request()->routeIs('admin.notices.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.notices.*') ? 'active' : '' }}">
                        <i class="bx bx-broadcast side-menu__icon"></i>
                        <span class="side-menu__label">News Ticker</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.notices.index') }}" class="side-menu__item {{ request()->routeIs('admin.notices.index') ? 'active' : '' }}">
                                <i class="bx bx-list-ul"></i> All Notices
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.notices.create') }}" class="side-menu__item {{ request()->routeIs('admin.notices.create') ? 'active' : '' }}">
                                <i class="bx bx-plus"></i> Add Notice
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Investment Plans -->
                <li class="slide has-sub {{ request()->routeIs('admin.plans.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
                        <i class="bx bx-package side-menu__icon"></i>
                        <span class="side-menu__label">Investment Plans</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.plans.index') }}" class="side-menu__item {{ request()->routeIs('admin.plans.index') ? 'active' : '' }}">
                                <i class="bx bx-list-ul"></i> All Plans
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.plans.create') }}" class="side-menu__item {{ request()->routeIs('admin.plans.create') ? 'active' : '' }}">
                                <i class="bx bx-plus"></i> Create Plan
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.plans.index', ['filter' => 'active']) }}" class="side-menu__item">
                                <i class="bx bx-check-circle"></i> Active Plans
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.plans.index', ['filter' => 'featured']) }}" class="side-menu__item">
                                <i class="bx bx-star"></i> Featured Plans
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Quick Actions -->
                <li class="slide has-sub {{ request()->routeIs('admin.quick.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.quick.*') ? 'active' : '' }}">
                        <i class="bx bx-zap side-menu__icon"></i>
                        <span class="side-menu__label">Quick Actions</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.invoices.create') }}" class="side-menu__item">
                                <i class="bx bx-receipt"></i> Create Invoice
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.products.create') }}" class="side-menu__item">
                                <i class="bx bx-plus"></i> Add Product
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.categories.create') }}" class="side-menu__item">
                                <i class="bx bx-plus"></i> Add Category
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.subcategories.create') }}" class="side-menu__item">
                                <i class="bx bx-plus"></i> Add Subcategory
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.brands.create') }}" class="side-menu__item">
                                <i class="bx bx-plus"></i> Add Brand
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.users.create') }}" class="side-menu__item">
                                <i class="bx bx-plus"></i> Add User
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.coupons.create') }}" class="side-menu__item">
                                <i class="bx bx-plus"></i> Add Coupon
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.invoices.analytics') }}" class="side-menu__item">
                                <i class="bx bx-bar-chart"></i> Invoice Analytics
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Ecommerce Management -->
                <li class="slide__category"><span class="category-name">Ecommerce</span></li>
                
                <!-- Products -->
                <li class="slide has-sub {{ request()->routeIs('admin.products.*') && !request()->routeIs('admin.products.specifications.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.products.*') && !request()->routeIs('admin.products.specifications.*') ? 'active' : '' }}">
                        <i class="bx bx-package side-menu__icon"></i>
                        <span class="side-menu__label">Products</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.products.index') }}" class="side-menu__item {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">All Products</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.products.create') }}" class="side-menu__item {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">Add Product</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.products.bulk-import') }}" class="side-menu__item {{ request()->routeIs('admin.products.bulk-import') ? 'active' : '' }}">Bulk Import</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.products.export') }}" class="side-menu__item {{ request()->routeIs('admin.products.export') ? 'active' : '' }}">Export Products</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.products.analytics') }}" class="side-menu__item {{ request()->routeIs('admin.products.analytics') ? 'active' : '' }}">Product Analytics</a>
                        </li>
                    </ul>
                </li>

                <!-- Product Specifications Management -->
                <li class="slide has-sub {{ request()->routeIs('admin.products.specifications.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.products.specifications.*') ? 'active' : '' }}">
                        <i class="bx bx-detail side-menu__icon"></i>
                        <span class="side-menu__label">Product Specifications</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.products.specifications.index') }}" class="side-menu__item {{ request()->routeIs('admin.products.specifications.index') ? 'active' : '' }}">
                                <i class="bx bx-list-ul"></i> All Specifications
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.products.specifications.missing') }}" class="side-menu__item {{ request()->routeIs('admin.products.specifications.missing') ? 'active' : '' }}">
                                <i class="bx bx-error-circle"></i> Missing Specifications
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.products.specifications.bulk') }}" class="side-menu__item {{ request()->routeIs('admin.products.specifications.bulk') ? 'active' : '' }}">
                                <i class="bx bx-edit-alt"></i> Bulk Update
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.products.specifications.generate') }}" class="side-menu__item {{ request()->routeIs('admin.products.specifications.generate') ? 'active' : '' }}">
                                <i class="bx bx-cog"></i> Auto Generate
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Image Management -->
                <li class="slide has-sub {{ request()->routeIs('admin.image-*') || request()->routeIs('admin.test-upload') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.image-*') || request()->routeIs('admin.test-upload') ? 'active' : '' }}">
                        <i class="bx bx-image side-menu__icon"></i>
                        <span class="side-menu__label">Image Management</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.image-upload.demo') }}" class="side-menu__item {{ request()->routeIs('admin.image-upload.demo') ? 'active' : '' }}">
                                <i class="bx bx-cloud-upload"></i> Image Upload Center
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.image-upload.usage') }}" class="side-menu__item {{ request()->routeIs('admin.image-upload.usage') ? 'active' : '' }}">
                                <i class="bx bx-resize"></i> Bulk Resize & Tools
                            </a>
                        </li>
                        <li class="slide" style="display: none;">
                            <a href="{{ route('admin.image-upload.resize-guide') }}" class="side-menu__item {{ request()->routeIs('admin.image-upload.resize-guide') ? 'active' : '' }}">
                                <i class="bx bx-book-content"></i> Resize Guidelines
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.test-upload') }}" class="side-menu__item {{ request()->routeIs('admin.test-upload') ? 'active' : '' }}">
                                <i class="bx bx-bug"></i> Debug & Testing
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Catalog Management -->
                <li class="slide has-sub {{ request()->routeIs('admin.catalog.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.subcategories.*') || request()->routeIs('admin.brands.*') || request()->routeIs('admin.attributes.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.catalog.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.subcategories.*') || request()->routeIs('admin.brands.*') || request()->routeIs('admin.attributes.*') ? 'active' : '' }}">
                        <i class="bx bx-category side-menu__icon"></i>
                        <span class="side-menu__label">Catalog Management</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <!-- Categories -->
                        <li class="slide has-sub {{ request()->routeIs('admin.categories.*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                <i class="bx bx-grid-alt side-menu__icon"></i>
                                <span class="side-menu__label">Categories</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child2">
                                <li class="slide">
                                    <a href="{{ route('admin.categories.index') }}" class="side-menu__item {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">All Categories</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.categories.create') }}" class="side-menu__item {{ request()->routeIs('admin.categories.create') ? 'active' : '' }}">Add Category</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.categories.tree') }}" class="side-menu__item {{ request()->routeIs('admin.categories.tree') ? 'active' : '' }}">Category Tree</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.categories.bulk-import') }}" class="side-menu__item {{ request()->routeIs('admin.categories.bulk-import') ? 'active' : '' }}">Bulk Import</a>
                                </li>
                            </ul>
                        </li> 

                        <!-- Subcategories -->
                        <li class="slide has-sub {{ request()->routeIs('admin.subcategories.*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.subcategories.*') ? 'active' : '' }}">
                                <i class="bx bx-sitemap side-menu__icon"></i>
                                <span class="side-menu__label">Subcategories</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child2">
                                <li class="slide">
                                    <a href="{{ route('admin.subcategories.index') }}" class="side-menu__item {{ request()->routeIs('admin.subcategories.index') ? 'active' : '' }}">All Subcategories</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.subcategories.create') }}" class="side-menu__item {{ request()->routeIs('admin.subcategories.create') ? 'active' : '' }}">Add Subcategory</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.subcategories.index') }}?featured=1" class="side-menu__item">Featured Subcategories</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.subcategories.bulk-import') }}" class="side-menu__item {{ request()->routeIs('admin.subcategories.bulk-import') ? 'active' : '' }}">Bulk Import</a>
                                </li>
                            </ul>
                        </li>

                        <!-- Brands -->
                        <li class="slide has-sub {{ request()->routeIs('admin.brands.*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                                <i class="bx bx-crown side-menu__icon"></i>
                                <span class="side-menu__label">Brands</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child2">
                                <li class="slide">
                                    <a href="{{ route('admin.brands.index') }}" class="side-menu__item {{ request()->routeIs('admin.brands.index') ? 'active' : '' }}">All Brands</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.brands.create') }}" class="side-menu__item {{ request()->routeIs('admin.brands.create') ? 'active' : '' }}">Add Brand</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.brands.featured') }}" class="side-menu__item {{ request()->routeIs('admin.brands.featured') ? 'active' : '' }}">Featured Brands</a>
                                </li>
                            </ul>
                        </li>

                        <!-- Attributes & Variants -->
                        <li class="slide has-sub {{ request()->routeIs('admin.attributes.*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}">
                                <i class="bx bx-customize side-menu__icon"></i>
                                <span class="side-menu__label">Attributes</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child2">
                                <li class="slide">
                                    <a href="{{ route('admin.attributes.index') }}" class="side-menu__item {{ request()->routeIs('admin.attributes.index') ? 'active' : '' }}">All Attributes</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.attributes.create') }}" class="side-menu__item {{ request()->routeIs('admin.attributes.create') ? 'active' : '' }}">Add Attribute</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.attributes.sizes') }}" class="side-menu__item {{ request()->routeIs('admin.attributes.sizes') ? 'active' : '' }}">Sizes</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.attributes.colors') }}" class="side-menu__item {{ request()->routeIs('admin.attributes.colors') ? 'active' : '' }}">Colors</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.attributes.materials') }}" class="side-menu__item {{ request()->routeIs('admin.attributes.materials') ? 'active' : '' }}">Materials</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.attributes.sets') }}" class="side-menu__item {{ request()->routeIs('admin.attributes.sets') ? 'active' : '' }}">Attribute Sets</a>
                                </li>
                            </ul>
                        </li>

                        <!-- Tags -->
                        <li class="slide has-sub {{ request()->routeIs('admin.tags.*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                                <i class="bx bx-purchase-tag side-menu__icon"></i>
                                <span class="side-menu__label">Tags</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child2">
                                <li class="slide">
                                    <a href="{{ route('admin.tags.index') }}" class="side-menu__item {{ request()->routeIs('admin.tags.index') ? 'active' : '' }}">All Tags</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.tags.create') }}" class="side-menu__item {{ request()->routeIs('admin.tags.create') ? 'active' : '' }}">Add Tag</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.tags.popular') }}" class="side-menu__item {{ request()->routeIs('admin.tags.popular') ? 'active' : '' }}">Popular Tags</a>
                                </li>
                            </ul>
                        </li>

                        <!-- Collections -->
                        <li class="slide has-sub {{ request()->routeIs('admin.collections.*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.collections.*') ? 'active' : '' }}">
                                <i class="bx bx-collection side-menu__icon"></i>
                                <span class="side-menu__label">Collections</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child2">
                                <li class="slide">
                                    <a href="{{ route('admin.collections.index') }}" class="side-menu__item {{ request()->routeIs('admin.collections.index') ? 'active' : '' }}">All Collections</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.collections.create') }}" class="side-menu__item {{ request()->routeIs('admin.collections.create') ? 'active' : '' }}">Add Collection</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.collections.seasonal') }}" class="side-menu__item {{ request()->routeIs('admin.collections.seasonal') ? 'active' : '' }}">Seasonal Collections</a>
                                </li>
                            </ul>
                        </li>

                        <!-- Units & Specifications -->
                        <li class="slide has-sub {{ request()->routeIs('admin.units.*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.units.*') ? 'active' : '' }}">
                                <i class="bx bx-ruler side-menu__icon"></i>
                                <span class="side-menu__label">Units & Specs</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child2">
                                <li class="slide">
                                    <a href="{{ route('admin.units.index') }}" class="side-menu__item {{ request()->routeIs('admin.units.index') ? 'active' : '' }}">Measurement Units</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.units.weight') }}" class="side-menu__item {{ request()->routeIs('admin.units.weight') ? 'active' : '' }}">Weight Units</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.units.dimensions') }}" class="side-menu__item {{ request()->routeIs('admin.units.dimensions') ? 'active' : '' }}">Dimension Units</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <!-- Orders -->
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
                            <a href="{{ route('admin.orders.create') }}" class="side-menu__item {{ request()->routeIs('admin.orders.create') ? 'active' : '' }}">
                                <i class="bx bx-plus-circle"></i> Create Order
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.orders.index') }}?status=pending" class="side-menu__item {{ request()->is('admin/orders?status=pending') ? 'active' : '' }}">
                                <i class="bx bx-time"></i> Pending Orders
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.orders.index') }}?status=processing" class="side-menu__item {{ request()->is('admin/orders?status=processing') ? 'active' : '' }}">
                                <i class="bx bx-loader"></i> Processing
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.orders.index') }}?status=shipped" class="side-menu__item {{ request()->is('admin/orders?status=shipped') ? 'active' : '' }}">
                                <i class="bx bx-package"></i> Shipped Orders
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.orders.index') }}?status=delivered" class="side-menu__item {{ request()->is('admin/orders?status=delivered') ? 'active' : '' }}">
                                <i class="bx bx-check-circle"></i> Delivered
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.orders.index') }}?status=cancelled" class="side-menu__item {{ request()->is('admin/orders?status=cancelled') ? 'active' : '' }}">
                                <i class="bx bx-x-circle"></i> Cancelled
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Delivery Charges -->
                <li class="slide has-sub {{ request()->routeIs('admin.delivery-charges.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.delivery-charges.*') ? 'active' : '' }}">
                        <i class="bx bx-truck side-menu__icon"></i>
                        <span class="side-menu__label">Delivery Charges</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.delivery-charges.index') }}" class="side-menu__item {{ request()->routeIs('admin.delivery-charges.index') ? 'active' : '' }}">
                                <i class="bx bx-list-ul"></i> All Charges
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.delivery-charges.create') }}" class="side-menu__item {{ request()->routeIs('admin.delivery-charges.create') ? 'active' : '' }}">
                                <i class="bx bx-plus-circle"></i> Add New Charge
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Invoices -->
                <li class="slide has-sub {{ request()->routeIs('admin.invoices.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                        <i class="bx bx-receipt side-menu__icon"></i>
                        <span class="side-menu__label">Invoice Management</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <!-- Invoice Dashboard -->
                        <li class="slide">
                            <a href="{{ route('admin.invoices.index') }}" class="side-menu__item {{ request()->routeIs('admin.invoices.index') ? 'active' : '' }}">
                                <i class="bx bx-list-ul"></i> All Invoices
                            </a>
                        </li>
                        
                        <!-- Create Invoice -->
                        <li class="slide">
                            <a href="{{ route('admin.invoices.create') }}" class="side-menu__item {{ request()->routeIs('admin.invoices.create') ? 'active' : '' }}">
                                <i class="bx bx-plus-circle"></i> Create Invoice
                            </a>
                        </li>
                        
                        <!-- Invoice Analytics -->
                        <li class="slide">
                            <a href="{{ route('admin.invoices.analytics') }}" class="side-menu__item {{ request()->routeIs('admin.invoices.analytics') ? 'active' : '' }}">
                                <i class="bx bx-bar-chart"></i> Analytics & Reports
                            </a>
                        </li>
                        
                        <!-- Quick Actions -->
                        <li class="slide has-sub {{ request()->routeIs('admin.invoices.bulk*') || request()->routeIs('admin.invoices.customize*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.invoices.bulk*') || request()->routeIs('admin.invoices.customize*') ? 'active' : '' }}">
                                <i class="bx bx-zap"></i>
                                <span class="side-menu__label">Quick Actions</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child2">
                                <li class="slide">
                                    <a href="{{ route('admin.invoices.index') }}?bulk=1" class="side-menu__item">
                                        <i class="bx bx-layer"></i> Bulk Operations
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.invoices.index') }}?status=pending" class="side-menu__item">
                                        <i class="bx bx-time"></i> Pending Payments
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.invoices.index') }}?status=overdue" class="side-menu__item">
                                        <i class="bx bx-error-circle"></i> Overdue Invoices
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.invoices.index') }}?this_month=1" class="side-menu__item">
                                        <i class="bx bx-calendar"></i> This Month
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <!-- Invoice Templates -->
                        <li class="slide has-sub {{ request()->routeIs('admin.invoices.professional*') || request()->routeIs('admin.invoices.invoice-template*') || request()->routeIs('admin.invoices.print*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.invoices.professional*') || request()->routeIs('admin.invoices.invoice-template*') || request()->routeIs('admin.invoices.print*') ? 'active' : '' }}">
                                <i class="bx bx-file-blank"></i>
                                <span class="side-menu__label">Templates & PDF</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child2">
                                <li class="slide">
                                    <a href="#" class="side-menu__item text-muted" onclick="alert('Select an invoice from the list to view professional PDF')">
                                        <i class="bx bx-crown"></i> Professional PDF
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="#" class="side-menu__item text-muted" onclick="alert('Select an invoice from the list to view template')">
                                        <i class="bx bx-file-alt"></i> Invoice Template
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="#" class="side-menu__item text-muted" onclick="alert('Select an invoice from the list to print')">
                                        <i class="bx bx-printer"></i> Print Version
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="#" class="side-menu__item text-muted" onclick="alert('Select an invoice from the list to customize')">
                                        <i class="bx bx-customize"></i> Customize Layout
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <!-- Email & Communication -->
                        <li class="slide has-sub {{ request()->routeIs('admin.invoices.email*') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.invoices.email*') ? 'active' : '' }}">
                                <i class="bx bx-envelope"></i>
                                <span class="side-menu__label">Email & Notifications</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child2">
                                <li class="slide">
                                    <a href="{{ route('admin.invoices.index') }}?email_sent=1" class="side-menu__item">
                                        <i class="bx bx-check-circle"></i> Sent Invoices
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('admin.invoices.index') }}?email_pending=1" class="side-menu__item">
                                        <i class="bx bx-time-five"></i> Email Pending
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="#" class="side-menu__item text-muted" onclick="alert('Select invoices from the list for bulk email')">
                                        <i class="bx bx-mail-send"></i> Bulk Email
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: Email templates management')">
                                        <i class="bx bx-edit-alt"></i> Email Templates
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <!-- Settings & Configuration -->
                        <li class="slide has-sub">
                            <a href="javascript:void(0);" class="side-menu__item">
                                <i class="bx bx-cog"></i>
                                <span class="side-menu__label">Invoice Settings</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child2">
                                <li class="slide">
                                    <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: Invoice numbering settings')">
                                        <i class="bx bx-hash"></i> Numbering Format
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: Tax configuration')">
                                        <i class="bx bx-calculator"></i> Tax Settings
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: Company information settings')">
                                        <i class="bx bx-building"></i> Company Info
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: Payment gateway settings')">
                                        <i class="bx bx-credit-card"></i> Payment Gateways
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <!-- Users -->
                <li class="slide has-sub {{ request()->routeIs('admin.users.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bx bx-user side-menu__icon"></i>
                        <span class="side-menu__label">Users</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.users.index') }}" class="side-menu__item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">All Users</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.users.create') }}" class="side-menu__item {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">Add User</a>
                        </li>
                    </ul>
                </li>

                <!-- KYC Management -->
                <li class="slide has-sub {{ request()->routeIs('admin.kyc.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.kyc.*') ? 'active' : '' }}">
                        <i class="bx bx-shield-check side-menu__icon"></i>
                        <span class="side-menu__label">KYC Management</span>
                        @php
                            $totalPending = \App\Models\MemberKycVerification::where('status', 'pending')->count();
                            $totalUnderReview = \App\Models\MemberKycVerification::where('status', 'under_review')->count();
                            $totalNotifications = $totalPending + $totalUnderReview;
                        @endphp
                        @if($totalNotifications > 0)
                            <span class="badge bg-danger ms-auto">{{ $totalNotifications }}</span>
                        @endif
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.kyc.index') }}" class="side-menu__item {{ request()->routeIs('admin.kyc.index') ? 'active' : '' }}">
                                <i class="bx bx-grid-alt"></i> Dashboard
                                <span class="badge bg-primary ms-auto">NEW</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.kyc.pending') }}" class="side-menu__item {{ request()->routeIs('admin.kyc.pending') ? 'active' : '' }}">
                                <i class="bx bx-time-five"></i> Pending Review
                                @if($totalPending > 0)
                                    <span class="badge bg-warning ms-auto">{{ $totalPending }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.kyc.under-review') }}" class="side-menu__item {{ request()->routeIs('admin.kyc.under-review') ? 'active' : '' }}">
                                <i class="bx bx-search-alt-2"></i> Under Review
                                @if($totalUnderReview > 0)
                                    <span class="badge bg-info ms-auto">{{ $totalUnderReview }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.kyc.approved') }}" class="side-menu__item {{ request()->routeIs('admin.kyc.approved') ? 'active' : '' }}">
                                <i class="bx bx-check-circle"></i> Approved
                                @php $approvedCount = \App\Models\MemberKycVerification::where('status', 'approved')->count(); @endphp
                                @if($approvedCount > 0)
                                    <span class="badge bg-success ms-auto">{{ $approvedCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.kyc.rejected') }}" class="side-menu__item {{ request()->routeIs('admin.kyc.rejected') ? 'active' : '' }}">
                                <i class="bx bx-x-circle"></i> Rejected
                                @php $rejectedCount = \App\Models\MemberKycVerification::where('status', 'rejected')->count(); @endphp
                                @if($rejectedCount > 0)
                                    <span class="badge bg-danger ms-auto">{{ $rejectedCount }}</span>
                                @endif
                            </a>
                        </li>
                        
                        <!-- Quick Actions -->
                        <li class="slide">
                            <div class="side-menu__item text-muted border-top pt-2 mt-2" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                Quick Actions
                            </div>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.kyc.export.csv') }}" class="side-menu__item" target="_blank">
                                <i class="bx bx-download"></i> Export Data
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.kyc.dashboard.stats') }}" class="side-menu__item" onclick="loadKycStats()" data-bs-toggle="modal" data-bs-target="#kycStatsModal">
                                <i class="bx bx-stats"></i> Live Statistics
                            </a>
                        </li>
                        
                        <!-- Separator -->
                        <li class="slide">
                            <div class="side-menu__item text-muted border-top pt-2 mt-2" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                Vendor KYC
                            </div>
                        </li>
                        
                        <li class="slide">
                            <a href="{{ route('admin.vendor-kyc.index') }}" class="side-menu__item {{ request()->routeIs('admin.vendor-kyc.index') ? 'active' : '' }}">
                                <i class="bx bx-store-alt"></i> All Vendor KYC
                                @php $vendorKycCount = \App\Models\VendorKycVerification::count(); @endphp
                                @if($vendorKycCount > 0)
                                    <span class="badge bg-secondary ms-auto">{{ $vendorKycCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.vendor-kyc.pending') }}" class="side-menu__item {{ request()->routeIs('admin.vendor-kyc.pending') ? 'active' : '' }}">
                                <i class="bx bx-time-five"></i> Pending Approval
                                @php
                                    $pendingVendorKyc = \App\Models\VendorKycVerification::where('status', 'pending')->count();
                                @endphp
                                @if($pendingVendorKyc > 0)
                                    <span class="badge bg-warning ms-auto">{{ $pendingVendorKyc }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.vendor-kyc.approved') }}" class="side-menu__item {{ request()->routeIs('admin.vendor-kyc.approved') ? 'active' : '' }}">
                                <i class="bx bx-check-circle"></i> Approved Vendors
                                @php $approvedVendorKyc = \App\Models\VendorKycVerification::where('status', 'approved')->count(); @endphp
                                @if($approvedVendorKyc > 0)
                                    <span class="badge bg-success ms-auto">{{ $approvedVendorKyc }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.vendor-kyc.rejected') }}" class="side-menu__item {{ request()->routeIs('admin.vendor-kyc.rejected') ? 'active' : '' }}">
                                <i class="bx bx-x-circle"></i> Rejected Vendors
                                @php $rejectedVendorKyc = \App\Models\VendorKycVerification::where('status', 'rejected')->count(); @endphp
                                @if($rejectedVendorKyc > 0)
                                    <span class="badge bg-danger ms-auto">{{ $rejectedVendorKyc }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.vendor-kyc.under-review') }}" class="side-menu__item {{ request()->routeIs('admin.vendor-kyc.under-review') ? 'active' : '' }}">
                                <i class="bx bx-search-alt"></i> Under Review
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Vendors -->
                <li class="slide has-sub {{ request()->routeIs('admin.vendors.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
                        <i class="bx bx-store side-menu__icon"></i>
                        <span class="side-menu__label">Vendors</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.vendors.index') }}" class="side-menu__item {{ request()->routeIs('admin.vendors.index') ? 'active' : '' }}">All Vendors</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.vendors.pending') }}" class="side-menu__item {{ request()->routeIs('admin.vendors.pending') ? 'active' : '' }}">Pending Approval</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.vendors.approved') }}" class="side-menu__item {{ request()->routeIs('admin.vendors.approved') ? 'active' : '' }}">Approved</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.vendors.suspended') }}" class="side-menu__item {{ request()->routeIs('admin.vendors.suspended') ? 'active' : '' }}">Suspended</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.vendors.commissions') }}" class="side-menu__item {{ request()->routeIs('admin.vendors.commissions') ? 'active' : '' }}">Commissions</a>
                        </li>
                    </ul>
                </li>

                <!-- MLM Management -->
                <li class="slide__category"><span class="category-name">MLM System</span></li>
                <li class="slide has-sub {{ request()->routeIs('admin.mlm.*') || request()->routeIs('admin.mlm-bonus-settings.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.mlm.*') || request()->routeIs('admin.mlm-bonus-settings.*') ? 'active' : '' }}">
                        <i class="bx bx-network-chart side-menu__icon"></i>
                        <span class="side-menu__label">MLM Management</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.mlm.genealogy') }}" class="side-menu__item {{ request()->routeIs('admin.mlm.genealogy') ? 'active' : '' }}">
                                <i class="bx bx-sitemap"></i> Genealogy Tree
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.mlm.ranks') }}" class="side-menu__item {{ request()->routeIs('admin.mlm.ranks') ? 'active' : '' }}">
                                <i class="bx bx-medal"></i> Rank Management
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.mlm-bonus-settings.index') }}" class="side-menu__item {{ request()->routeIs('admin.mlm-bonus-settings.*') ? 'active' : '' }}">
                                <i class="bx bx-cog"></i> Bonus Settings
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.mlm.downlines') }}" class="side-menu__item {{ request()->routeIs('admin.mlm.downlines') ? 'active' : '' }}">
                                <i class="bx bx-user-check"></i> Downline Management
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.mlm.pv-points') }}" class="side-menu__item {{ request()->routeIs('admin.mlm.pv-points') ? 'active' : '' }}">
                                <i class="bx bx-trophy"></i> PV Points System
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Commission Management -->
                <li class="slide has-sub {{ request()->routeIs('admin.commissions.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.commissions.*') ? 'active' : '' }}">
                        <i class="bx bx-money side-menu__icon"></i>
                        <span class="side-menu__label">Commissions</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.commissions.overview') }}" class="side-menu__item {{ request()->routeIs('admin.commissions.overview') ? 'active' : '' }}">Overview</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.commissions.direct') }}" class="side-menu__item {{ request()->routeIs('admin.commissions.direct') ? 'active' : '' }}">Direct Sales</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.commissions.binary') }}" class="side-menu__item {{ request()->routeIs('admin.commissions.binary') ? 'active' : '' }}">Binary Bonus</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.commissions.matching') }}" class="side-menu__item {{ request()->routeIs('admin.commissions.matching') ? 'active' : '' }}">Matching Bonus</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.commissions.leadership') }}" class="side-menu__item {{ request()->routeIs('admin.commissions.leadership') ? 'active' : '' }}">Leadership Bonus</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.commissions.payouts') }}" class="side-menu__item {{ request()->routeIs('admin.commissions.payouts') ? 'active' : '' }}">Payouts</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.commission-settings.index') }}" class="side-menu__item {{ request()->routeIs('admin.commission-settings.*') ? 'active' : '' }}">
                                <i class="bx bx-cogs"></i> Commission Settings
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Affiliate Management -->
                <li class="slide has-sub {{ request()->routeIs('admin.affiliates.*') || request()->routeIs('admin.affiliate-clicks.*') || request()->routeIs('admin.affiliate-commissions.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.affiliates.*') || request()->routeIs('admin.affiliate-clicks.*') || request()->routeIs('admin.affiliate-commissions.*') ? 'active' : '' }}">
                        <i class="bx bx-network-chart side-menu__icon"></i>
                        <span class="side-menu__label">Affiliate Management</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.commissions.overview') }}" class="side-menu__item {{ request()->routeIs('admin.commissions.overview') ? 'active' : '' }}">
                                <i class="bx bx-pie-chart-alt"></i> Overview & Analytics
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.affiliates.index') ?? '#' }}" class="side-menu__item {{ request()->routeIs('admin.affiliates.index') ? 'active' : '' }}">
                                <i class="bx bx-users"></i> Affiliate Users
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.affiliate-clicks.index') ?? '#' }}" class="side-menu__item {{ request()->routeIs('admin.affiliate-clicks.index') ? 'active' : '' }}">
                                <i class="bx bx-mouse"></i> Click Tracking
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.affiliate-commissions.index') ?? '#' }}" class="side-menu__item {{ request()->routeIs('admin.affiliate-commissions.index') ? 'active' : '' }}">
                                <i class="bx bx-money"></i> Affiliate Commissions
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.commission-settings.index') }}" class="side-menu__item {{ request()->routeIs('admin.commission-settings.*') ? 'active' : '' }}">
                                <i class="bx bx-cog"></i> Commission Settings
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.affiliate-links.index') ?? '#' }}" class="side-menu__item {{ request()->routeIs('admin.affiliate-links.index') ? 'active' : '' }}">
                                <i class="bx bx-link"></i> Shared Links
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.affiliate-reports.index') ?? '#' }}" class="side-menu__item {{ request()->routeIs('admin.affiliate-reports.index') ? 'active' : '' }}">
                                <i class="bx bx-line-chart"></i> Performance Reports
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.package-link-sharing.index') }}" class="side-menu__item {{ request()->routeIs('admin.package-link-sharing.*') ? 'active' : '' }}">
                                <i class="bx bx-package"></i> Package Link Sharing
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Subscription Plans -->
                <li class="slide has-sub {{ request()->routeIs('admin.subscriptions.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                        <i class="bx bx-credit-card side-menu__icon"></i>
                        <span class="side-menu__label">Subscriptions</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.subscriptions.index') }}" class="side-menu__item {{ request()->routeIs('admin.subscriptions.index') ? 'active' : '' }}">All Plans</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.subscriptions.create') }}" class="side-menu__item {{ request()->routeIs('admin.subscriptions.create') ? 'active' : '' }}">Add Plan</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.subscriptions.subscribers') }}" class="side-menu__item {{ request()->routeIs('admin.subscriptions.subscribers') ? 'active' : '' }}">Subscribers</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.subscriptions.renewals') }}" class="side-menu__item {{ request()->routeIs('admin.subscriptions.renewals') ? 'active' : '' }}">Renewals</a>
                        </li>
                    </ul>
                </li>

                <!-- Customers -->
                <li class="slide">
                    <a href="{{ route('admin.customers.index') }}" class="side-menu__item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                        <i class="bx bx-user side-menu__icon"></i>
                        <span class="side-menu__label">Customers</span>
                    </a>
                </li>

                <!-- Inventory -->
                <li class="slide has-sub {{ request()->routeIs('admin.inventory.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                        <i class="bx bx-box side-menu__icon"></i>
                        <span class="side-menu__label">Inventory</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.inventory.stock') }}" class="side-menu__item {{ request()->routeIs('admin.inventory.stock') ? 'active' : '' }}">Stock Management</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.inventory.low-stock') }}" class="side-menu__item {{ request()->routeIs('admin.inventory.low-stock') ? 'active' : '' }}">Low Stock</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.inventory.out-of-stock') }}" class="side-menu__item {{ request()->routeIs('admin.inventory.out-of-stock') ? 'active' : '' }}">Out of Stock</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.inventory.adjustments') }}" class="side-menu__item {{ request()->routeIs('admin.inventory.adjustments') ? 'active' : '' }}">Stock Adjustments</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.inventory.movements') }}" class="side-menu__item {{ request()->routeIs('admin.inventory.movements') ? 'active' : '' }}">Stock Movements</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.inventory.warehouses') }}" class="side-menu__item {{ request()->routeIs('admin.inventory.warehouses') ? 'active' : '' }}">Warehouses</a>
                        </li>
                    </ul>
                </li>

                <!-- Customer Reviews -->
                <li class="slide has-sub {{ request()->routeIs('admin.reviews.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                        <i class="bx bx-star side-menu__icon"></i>
                        <span class="side-menu__label">Reviews & Ratings</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.reviews.index') }}" class="side-menu__item {{ request()->routeIs('admin.reviews.index') ? 'active' : '' }}">All Reviews</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.reviews.pending') }}" class="side-menu__item {{ request()->routeIs('admin.reviews.pending') ? 'active' : '' }}">Pending Approval</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.reviews.featured') }}" class="side-menu__item {{ request()->routeIs('admin.reviews.featured') ? 'active' : '' }}">Featured Reviews</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.reviews.analytics') }}" class="side-menu__item {{ request()->routeIs('admin.reviews.analytics') ? 'active' : '' }}">Review Analytics</a>
                        </li>
                    </ul>
                </li>

                <!-- Reports & Analytics -->
                <li class="slide__category"><span class="category-name">Analytics</span></li>
                <li class="slide has-sub {{ request()->routeIs('admin.reports.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="bx bx-bar-chart side-menu__icon"></i>
                        <span class="side-menu__label">Reports</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.reports.sales') }}" class="side-menu__item {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">Sales Report</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.reports.products') }}" class="side-menu__item {{ request()->routeIs('admin.reports.products') ? 'active' : '' }}">Product Report</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.reports.vendors') }}" class="side-menu__item {{ request()->routeIs('admin.reports.vendors') ? 'active' : '' }}">Vendor Report</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.reports.customers') }}" class="side-menu__item {{ request()->routeIs('admin.reports.customers') ? 'active' : '' }}">Customer Report</a>
                        </li>
                    </ul>
                </li>

                <!-- Marketing -->
                <li class="slide__category"><span class="category-name">Marketing</span></li>
                <li class="slide has-sub {{ request()->routeIs('admin.banners.*') || request()->routeIs('admin.banner-collections.*') || request()->routeIs('admin.popups.*') || request()->routeIs('admin.marketing.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.banners.*') || request()->routeIs('admin.banner-collections.*') || request()->routeIs('admin.popups.*') || request()->routeIs('admin.marketing.*') ? 'active' : '' }}">
                        <i class="bx bx-megaphone side-menu__icon"></i>
                        <span class="side-menu__label">Marketing</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.banners.index') }}" class="side-menu__item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                                <i class="bx bx-image"></i> Banners
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.banner-collections.index') }}" class="side-menu__item {{ request()->routeIs('admin.banner-collections.*') ? 'active' : '' }}">
                                <i class="bx bx-collection"></i> Banner Collections
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.popups.index') }}" class="side-menu__item {{ request()->routeIs('admin.popups.*') ? 'active' : '' }}">
                                <i class="bx bx-window"></i> Popups
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.coupons.index') }}" class="side-menu__item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                                <i class="bx bx-purchase-tag"></i> Coupons
                            </a>
                        </li>
                        <li class="slide">
                            <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: Email marketing campaigns')">
                                <i class="bx bx-envelope"></i> Email Campaigns
                            </a>
                        </li>
                        <li class="slide">
                            <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: Newsletter management')">
                                <i class="bx bx-news"></i> Newsletters
                            </a>
                        </li>
                        <li class="slide">
                            <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: SEO optimization tools')">
                                <i class="bx bx-search-alt"></i> SEO Tools
                            </a>
                        </li>
                        <li class="slide">
                            <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: Social media integration')">
                                <i class="bx bx-share-alt"></i> Social Media
                            </a>
                        </li>
                        <li class="slide">
                            <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: Marketing analytics dashboard')">
                                <i class="bx bx-bar-chart-alt-2"></i> Analytics
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Content Management -->
                <li class="slide has-sub {{ request()->routeIs('admin.content.*') || request()->routeIs('admin.pages.*') || request()->routeIs('admin.menus.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.content.*') || request()->routeIs('admin.pages.*') || request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                        <i class="bx bx-file side-menu__icon"></i>
                        <span class="side-menu__label">Content</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: Pages management')">
                                <i class="bx bx-file-blank"></i> Pages
                            </a>
                        </li>
                        <li class="slide">
                            <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: Menu management')">
                                <i class="bx bx-menu"></i> Menus
                            </a>
                        </li>
                        <li class="slide">
                            <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: Blog management')">
                                <i class="bx bx-news"></i> Blog
                            </a>
                        </li>
                        <li class="slide">
                            <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: FAQ management')">
                                <i class="bx bx-help-circle"></i> FAQ
                            </a>
                        </li>
                        <li class="slide">
                            <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: Terms & Policies')">
                                <i class="bx bx-file-blank"></i> Terms & Policies
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Support & Communication -->
                <li class="slide has-sub {{ request()->routeIs('admin.support.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
                        <i class="bx bx-support side-menu__icon"></i>
                        <span class="side-menu__label">Support</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.support.index') }}" class="side-menu__item {{ request()->routeIs('admin.support.index') ? 'active' : '' }}">
                                <i class="bx bx-support"></i> Support Tickets
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.support.tickets') }}" class="side-menu__item {{ request()->routeIs('admin.support.tickets') ? 'active' : '' }}">
                                <i class="bx bx-list-ul"></i> All Tickets
                            </a>
                        </li>
                        <li class="slide">
                            <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: Live chat management')">
                                <i class="bx bx-chat"></i> Live Chat
                            </a>
                        </li>
                        <li class="slide">
                            <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: Knowledge base')">
                                <i class="bx bx-book-open"></i> Knowledge Base
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Payment Methods -->
                <li class="slide has-sub {{ request()->routeIs('admin.withdraw-methods.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.withdraw-methods.*') ? 'active' : '' }}">
                        <i class="bx bx-credit-card side-menu__icon"></i>
                        <span class="side-menu__label">Payment Methods</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.withdraw-methods.index') }}" class="side-menu__item {{ request()->routeIs('admin.withdraw-methods.*') ? 'active' : '' }}">
                                <i class="bx bx-wallet"></i> Withdraw Methods
                            </a>
                        </li>
                        <li class="slide">
                            <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: Payment gateways')">
                                <i class="bx bx-credit-card"></i> Payment Gateways
                            </a>
                        </li>
                        <li class="slide">
                            <a href="#" class="side-menu__item text-muted" onclick="alert('Coming soon: Payment settings')">
                                <i class="bx bx-cog"></i> Payment Settings
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Financial Management -->
                <li class="slide__category"><span class="category-name">Financial</span></li>
                <li class="slide has-sub {{ request()->routeIs('admin.finance.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.finance.*') ? 'active' : '' }}">
                        <i class="bx bx-wallet side-menu__icon"></i>
                        <span class="side-menu__label">Financial</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.finance.transactions') }}" class="side-menu__item {{ request()->routeIs('admin.finance.transactions') ? 'active' : '' }}">Transactions</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.finance.wallets') }}" class="side-menu__item {{ request()->routeIs('admin.finance.wallets') ? 'active' : '' }}">User Wallets</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.finance.transfer') }}" class="side-menu__item {{ request()->routeIs('admin.finance.transfer') ? 'active' : '' }}">Balance Transfer</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.finance.transfer.history') }}" class="side-menu__item {{ request()->routeIs('admin.finance.transfer.history') ? 'active' : '' }}">Transfer History</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.finance.withdrawals') }}" class="side-menu__item {{ request()->routeIs('admin.finance.withdrawals') ? 'active' : '' }}">Withdrawal Requests</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.finance.deposits') }}" class="side-menu__item {{ request()->routeIs('admin.finance.deposits') ? 'active' : '' }}">Deposits</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.finance.refunds') }}" class="side-menu__item {{ request()->routeIs('admin.finance.refunds') ? 'active' : '' }}">Refunds</a>
                        </li>
                    </ul>
                </li>

                <!-- Website Management -->
                <li class="slide__category"><span class="category-name">Website</span></li>
                <li class="slide has-sub {{ request()->routeIs('admin.website.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.website.*') ? 'active' : '' }}">
                        <i class="bx bx-world side-menu__icon"></i>
                        <span class="side-menu__label">Website</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.website.pages') }}" class="side-menu__item {{ request()->routeIs('admin.website.pages') ? 'active' : '' }}">Pages</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.website.menus') }}" class="side-menu__item {{ request()->routeIs('admin.website.menus') ? 'active' : '' }}">Menus</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.website.themes') }}" class="side-menu__item {{ request()->routeIs('admin.website.themes') ? 'active' : '' }}">Themes</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.website.seo') }}" class="side-menu__item {{ request()->routeIs('admin.website.seo') ? 'active' : '' }}">SEO Settings</a>
                        </li>
                    </ul>
                </li>
                <!-- KYC Management Menu Start -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);" class="side-menu__item">
                            <i class="fe fe-shield side-menu__icon"></i>
                            <span class="side-menu__label">KYC Management</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide side-menu__label1">
                                <a href="javascript:void(0)">KYC Management</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.kyc.index') }}" class="side-menu__item">
                                    <span class="side-menu__label">All KYC Verifications</span>
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.kyc.index', ['status' => 'pending']) }}" class="side-menu__item">
                                    <span class="side-menu__label">Pending KYC</span>
                                    <span class="badge badge-warning ms-auto" id="pending-kyc-count">0</span>
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.kyc.index', ['status' => 'approved']) }}" class="side-menu__item">
                                    <span class="side-menu__label">Approved KYC</span>
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.kyc.index', ['status' => 'rejected']) }}" class="side-menu__item">
                                    <span class="side-menu__label">Rejected KYC</span>
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.kyc.index', ['status' => 'under_review']) }}" class="side-menu__item">
                                    <span class="side-menu__label">Under Review</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- KYC Management Menu End -->
                    <!-- Sub-Admin Management Menu Start - Only for Super Admins -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);" class="side-menu__item">
                            <i class="fe fe-shield side-menu__icon"></i>
                            <span class="side-menu__label">Sub-Admin Management</span>
                            @php
                                $subAdminCount = \App\Models\Admin::where('is_super_admin', false)->count();
                                $activeSubAdmins = \App\Models\Admin::where('is_super_admin', false)->where('is_active', true)->count();
                            @endphp
                            @if($subAdminCount > 0)
                                <span class="badge badge-info ms-2">{{ $subAdminCount }}</span>
                            @endif
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide side-menu__label1">
                                <a href="javascript:void(0)">Sub-Admin Management</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.sub-admins.index') }}" class="side-menu__item">
                                    <i class="fe fe-list me-2"></i>
                                    <span class="side-menu__label">All Sub-Admins</span>
                                    @if($subAdminCount > 0)
                                        <span class="badge badge-primary ms-auto">{{ $subAdminCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.sub-admins.create') }}" class="side-menu__item">
                                    <i class="fe fe-plus me-2"></i>
                                    <span class="side-menu__label">Create Sub-Admin</span>
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.sub-admins.index', ['status' => '1']) }}" class="side-menu__item">
                                    <i class="fe fe-check-circle me-2"></i>
                                    <span class="side-menu__label">Active Sub-Admins</span>
                                    @if($activeSubAdmins > 0)
                                        <span class="badge badge-success ms-auto">{{ $activeSubAdmins }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.sub-admins.index', ['status' => '0']) }}" class="side-menu__item">
                                    <i class="fe fe-x-circle me-2"></i>
                                    <span class="side-menu__label">Inactive Sub-Admins</span>
                                    @php $inactiveSubAdmins = $subAdminCount - $activeSubAdmins; @endphp
                                    @if($inactiveSubAdmins > 0)
                                        <span class="badge badge-warning ms-auto">{{ $inactiveSubAdmins }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.sub-admins.permissions') }}" class="side-menu__item">
                                    <i class="fe fe-key me-2"></i>
                                    <span class="side-menu__label">Permissions</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Sub-Admin Management Menu End -->
                    <!-- General Settings Menu Start -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);" class="side-menu__item">
                            <i class="fe fe-settings side-menu__icon"></i>
                            <span class="side-menu__label">General Settings</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide side-menu__label1">
                                <a href="javascript:void(0)">General Settings</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.general-settings.general') }}" class="side-menu__item">
                                    <span class="side-menu__label">General Settings</span>
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.general-settings.company-info') }}" class="side-menu__item">
                                    <i class="fe fe-building me-2"></i>
                                    <span class="side-menu__label">Company Information</span>
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.general-settings.mail-config') }}" class="side-menu__item">
                                    <span class="side-menu__label">Mail Configuration</span>
                                </a>
                            </li>
                            <li class="slide">
                                <a href="" class="side-menu__item">
                                    <span class="side-menu__label">Commission Level Setup</span>
                                    <span class="badge badge-info ms-auto">Referral</span>
                                </a>
                            </li>
                            <li class="slide">
                                <a href="" class="side-menu__item">
                                    <i class="fe fe-gift me-2"></i>
                                    <span class="side-menu__label">Referral Benefits System</span>
                                    <span class="badge badge-success ms-auto">🎁 New</span>
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.transfer-withdraw-conditions.index') }}" class="side-menu__item">
                                    <i class="fe fe-shield me-2"></i>
                                    <span class="side-menu__label">Transfer & Withdrawal Conditions</span>
                                    <span class="badge badge-warning ms-auto">Security</span>
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.general-settings.fee-settings') }}" class="side-menu__item {{ request()->routeIs('admin.general-settings.fee-settings') ? 'active' : '' }}">
                                    <i class="fe fe-credit-card me-2"></i>
                                    <span class="side-menu__label">Fee Settings</span>
                                    <span class="badge badge-primary ms-auto">💰 New</span>
                                </a>
                            </li>
                            <li class="slide">
                                <a href="javascript:void(0);" class="side-menu__item" onclick="toggleMaintenanceMode()">
                                    <span class="side-menu__label">Toggle Maintenance</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- General Settings Menu End -->

                    <!-- Modal Management Menu Start -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);" class="side-menu__item">
                            <i class="fe fe-layers side-menu__icon"></i>
                            <span class="side-menu__label">🔧 Modal Management</span>
                            @php
                                try {
                                    $totalModals = \DB::table('modal_settings')->count();
                                    $activeModals = \DB::table('modal_settings')->where('is_active', 1)->count();
                                    $inactiveModals = \DB::table('modal_settings')->where('is_active', 0)->count();
                                } catch (\Exception $e) {
                                    \Log::error('AdminMenu modal data error: ' . $e->getMessage());
                                    $totalModals = 0;
                                    $activeModals = 0;
                                    $inactiveModals = 0;
                                }
                            @endphp
                            @if($inactiveModals > 0)
                                <span class="badge badge-warning ms-2">{{ $inactiveModals }} inactive</span>
                            @elseif($activeModals > 0)
                                <span class="badge badge-success ms-2">{{ $activeModals }} active</span>
                            @endif
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide side-menu__label1">
                                <a href="javascript:void(0)">🔧 Modal Management</a>
                            </li>
                            
                            <!-- Modal Overview -->
                            <li class="slide">
                                <a href="{{ route('admin.modal.index') }}" class="side-menu__item">
                                    <i class="fe fe-grid me-2"></i>
                                    <span class="side-menu__label">All Modals</span>
                                    @if($totalModals > 0)
                                        <span class="badge badge-info ms-auto">{{ $totalModals }}</span>
                                    @endif
                                </a>
                            </li>
                            
                            <!-- Create New Modal -->
                            <li class="slide">
                                <a href="{{ route('admin.modal.create') }}" class="side-menu__item">
                                    <i class="fe fe-plus-circle me-2"></i>
                                    <span class="side-menu__label">Create Modal</span>
                                    <span class="badge badge-primary ms-auto">New</span>
                                </a>
                            </li>
                            
                            <!-- Modal Analytics -->
                            <li class="slide">
                                <a href="{{ route('admin.modal.analytics') }}" class="side-menu__item">
                                    <i class="fe fe-bar-chart me-2"></i>
                                    <span class="side-menu__label">Modal Analytics</span>
                                    <span class="badge badge-success ms-auto">📊 Stats</span>
                                </a>
                            </li>
                            
                            <!-- PWA Install Modals -->
                            <li class="slide">
                                <a href="{{ route('admin.modal.index') }}?filter=pwa" class="side-menu__item">
                                    <i class="fe fe-smartphone me-2"></i>
                                    <span class="side-menu__label">PWA Install Modals</span>
                                    @php
                                        try {
                                            $pwaModals = \DB::table('modal_settings')->where('modal_name', 'like', '%install%')->count();
                                        } catch (\Exception $e) {
                                            \Log::error('AdminMenu PWA modal data error: ' . $e->getMessage());
                                            $pwaModals = 0;
                                        }
                                    @endphp
                                    @if($pwaModals > 0)
                                        <span class="badge badge-info ms-auto">{{ $pwaModals }}</span>
                                    @endif
                                </a>
                            </li>
                            
                            <!-- Quick Actions -->
                            <li class="slide">
                                <a href="javascript:void(0);" class="side-menu__item" onclick="toggleAllModals()">
                                    <i class="fe fe-toggle-right me-2"></i>
                                    <span class="side-menu__label">Toggle All Modals</span>
                                    <span class="badge badge-warning ms-auto">Bulk</span>
                                </a>
                            </li>
                            
                            <!-- Modal Test Page -->
                            <li class="slide">
                                <a href="/modal-test.html" target="_blank" class="side-menu__item">
                                    <i class="fe fe-external-link me-2"></i>
                                    <span class="side-menu__label">Test Modal System</span>
                                    <span class="badge badge-secondary ms-auto">Test</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Modal Management Menu End -->

                <!-- Settings -->
                <li class="slide__category"><span class="category-name">System</span></li>
                <li class="slide has-sub {{ request()->routeIs('admin.settings.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="bx bx-cog side-menu__icon"></i>
                        <span class="side-menu__label">Settings</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.settings.index') }}" class="side-menu__item {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">Overview</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.settings.general') }}" class="side-menu__item {{ request()->routeIs('admin.settings.general') ? 'active' : '' }}">General</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.settings.payment') }}" class="side-menu__item {{ request()->routeIs('admin.settings.payment') ? 'active' : '' }}">Payment Methods</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.settings.shipping') }}" class="side-menu__item {{ request()->routeIs('admin.settings.shipping') ? 'active' : '' }}">Shipping</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.settings.tax') }}" class="side-menu__item {{ request()->routeIs('admin.settings.tax') ? 'active' : '' }}">Tax Settings</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.settings.email') }}" class="side-menu__item {{ request()->routeIs('admin.settings.email') ? 'active' : '' }}">Email Configuration</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.settings.mlm') }}" class="side-menu__item {{ request()->routeIs('admin.settings.mlm') ? 'active' : '' }}">MLM Configuration</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.settings.currencies') }}" class="side-menu__item {{ request()->routeIs('admin.settings.currencies') ? 'active' : '' }}">Currencies</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.settings.localization') }}" class="side-menu__item {{ request()->routeIs('admin.settings.localization') ? 'active' : '' }}">Localization</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.settings.integrations') }}" class="side-menu__item {{ request()->routeIs('admin.settings.integrations') ? 'active' : '' }}">API Integrations</a>
                        </li>
                    </ul>
                </li>

                <!-- System Tools -->
                <li class="slide has-sub {{ request()->routeIs('admin.tools.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.tools.*') ? 'active' : '' }}">
                        <i class="bx bx-wrench side-menu__icon"></i>
                        <span class="side-menu__label">System Tools</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.tools.cache') }}" class="side-menu__item {{ request()->routeIs('admin.tools.cache') ? 'active' : '' }}">Cache Management</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.tools.logs') }}" class="side-menu__item {{ request()->routeIs('admin.tools.logs') ? 'active' : '' }}">System Logs</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.tools.backup') }}" class="side-menu__item {{ request()->routeIs('admin.tools.backup') ? 'active' : '' }}">Database Backup</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.tools.maintenance') }}" class="side-menu__item {{ request()->routeIs('admin.tools.maintenance') ? 'active' : '' }}">Maintenance Mode</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.tools.imports') }}" class="side-menu__item {{ request()->routeIs('admin.tools.imports') ? 'active' : '' }}">Data Import/Export</a>
                        </li>
                    </ul>
                </li>

                <!-- File Manager -->
                <li class="slide">
                    <a href="{{ route('admin.files.index') }}" class="side-menu__item {{ request()->routeIs('admin.files.*') ? 'active' : '' }}">
                        <i class="bx bx-folder side-menu__icon"></i>
                        <span class="side-menu__label">File Manager</span>
                    </a>
                </li>

                <!-- Front-end Link -->
                <li class="slide">
                    <a href="{{ route('home') }}" target="_blank" class="side-menu__item">
                        <i class="bx bx-globe side-menu__icon"></i>
                        <span class="side-menu__label">View Frontend</span>
                        <i class="bx bx-link-external ms-auto"></i>
                    </a>
                </li>
                @endif {{-- End of vendor/admin conditional --}}
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
        </nav>
        <!-- End::nav -->
    </div>
    <!-- End::main-sidebar -->
</aside>
<!-- End::app-sidebar -->
