<!-- Start::app-sidebar -->
<aside class="app-sidebar sticky" id="sidebar">
    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="header-logo">
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
                <!-- Dashboard -->
                <li class="slide__category"><span class="category-name">Main</span></li>
                <li class="slide">
                    <a href="{{ route('admin.dashboard') }}" class="side-menu__item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bx bx-home side-menu__icon"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
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
                    </ul>
                </li>

                <!-- Ecommerce Management -->
                <li class="slide__category"><span class="category-name">Ecommerce</span></li>
                
                <!-- Products -->
                <li class="slide has-sub {{ request()->routeIs('admin.products.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
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
                            <a href="{{ route('admin.image-upload-demo') }}" class="side-menu__item {{ request()->routeIs('admin.image-upload-demo') ? 'active' : '' }}">Image Upload Demo</a>
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
                <li class="slide">
                    <a href="{{ route('admin.orders.index') }}" class="side-menu__item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <i class="bx bx-shopping-bag side-menu__icon"></i>
                        <span class="side-menu__label">Orders</span>
                    </a>
                </li>

                <!-- Invoices -->
                <li class="slide has-sub {{ request()->routeIs('admin.invoices.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                        <i class="bx bx-receipt side-menu__icon"></i>
                        <span class="side-menu__label">Invoices</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.invoices.index') }}" class="side-menu__item {{ request()->routeIs('admin.invoices.index') ? 'active' : '' }}">All Invoices</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.invoices.analytics') }}" class="side-menu__item {{ request()->routeIs('admin.invoices.analytics') ? 'active' : '' }}">Analytics</a>
                        </li>
                    </ul>
                </li>

                <!-- Coupons -->
                <li class="slide has-sub {{ request()->routeIs('admin.coupons.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                        <i class="bx bx-purchase-tag side-menu__icon"></i>
                        <span class="side-menu__label">Coupons</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.coupons.index') }}" class="side-menu__item {{ request()->routeIs('admin.coupons.index') ? 'active' : '' }}">All Coupons</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.coupons.create') }}" class="side-menu__item {{ request()->routeIs('admin.coupons.create') ? 'active' : '' }}">Add Coupon</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.coupons.analytics') }}" class="side-menu__item {{ request()->routeIs('admin.coupons.analytics') ? 'active' : '' }}">Analytics</a>
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
                <li class="slide has-sub {{ request()->routeIs('admin.mlm.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.mlm.*') ? 'active' : '' }}">
                        <i class="bx bx-network-chart side-menu__icon"></i>
                        <span class="side-menu__label">MLM Management</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.mlm.genealogy') }}" class="side-menu__item {{ request()->routeIs('admin.mlm.genealogy') ? 'active' : '' }}">Genealogy Tree</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.mlm.ranks') }}" class="side-menu__item {{ request()->routeIs('admin.mlm.ranks') ? 'active' : '' }}">Rank Management</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.mlm.bonuses') }}" class="side-menu__item {{ request()->routeIs('admin.mlm.bonuses') ? 'active' : '' }}">Bonus Settings</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.mlm.downlines') }}" class="side-menu__item {{ request()->routeIs('admin.mlm.downlines') ? 'active' : '' }}">Downline Management</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.mlm.pv-points') }}" class="side-menu__item {{ request()->routeIs('admin.mlm.pv-points') ? 'active' : '' }}">PV Points System</a>
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
                <li class="slide has-sub {{ request()->routeIs('admin.marketing.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.marketing.*') ? 'active' : '' }}">
                        <i class="bx bx-megaphone side-menu__icon"></i>
                        <span class="side-menu__label">Marketing</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin.marketing.banners') }}" class="side-menu__item {{ request()->routeIs('admin.marketing.banners') ? 'active' : '' }}">Banners</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.marketing.promotions') }}" class="side-menu__item {{ request()->routeIs('admin.marketing.promotions') ? 'active' : '' }}">Promotions</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.marketing.newsletters') }}" class="side-menu__item {{ request()->routeIs('admin.marketing.newsletters') ? 'active' : '' }}">Newsletters</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.marketing.campaigns') }}" class="side-menu__item {{ request()->routeIs('admin.marketing.campaigns') ? 'active' : '' }}">Email Campaigns</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.marketing.seo') }}" class="side-menu__item {{ request()->routeIs('admin.marketing.seo') ? 'active' : '' }}">SEO Tools</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.marketing.social') }}" class="side-menu__item {{ request()->routeIs('admin.marketing.social') ? 'active' : '' }}">Social Media</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.marketing.analytics') }}" class="side-menu__item {{ request()->routeIs('admin.marketing.analytics') ? 'active' : '' }}">Marketing Analytics</a>
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
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
        </nav>
        <!-- End::nav -->
    </div>
    <!-- End::main-sidebar -->
</aside>
<!-- End::app-sidebar -->
