<!-- Mobile Menu -->
<div class="offcanvas offcanvas-start canvas-mb" id="mobileMenu">
    <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas" aria-label="Close"></span>
    <div class="mb-canvas-content">
        <div class="mb-body">
            <ul class="nav-ul-mb" id="wrapper-menu-navigation">
                <li class="nav-mb-item">
                    <a href="{{ route('home') }}" class="nav-mb-link">Home</a>
                </li>
                <li class="nav-mb-item">
                    <a href="#" class="nav-mb-link collapsed mb-menu-link current" data-bs-toggle="collapse" data-bs-target="#dropdown-menu-one" aria-expanded="true" aria-controls="dropdown-menu-one">
                        <span>Shop</span>
                        <span class="btn-open-sub"></span>
                    </a>
                    <div id="dropdown-menu-one" class="collapse">
                        <ul class="sub-nav-menu">
                            <li><a href="{{ route('shop.index') }}" class="sub-nav-link">All Products</a></li>
                            <li><a href="{{ route('categories.index') }}" class="sub-nav-link">Categories</a></li>
                            <li><a href="{{ route('collections.index') }}" class="sub-nav-link">Collections</a></li>
                            <li><a href="{{ route('brands.index') }}" class="sub-nav-link">Brands</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-mb-item">
                    <a href="{{ route('pages.about') }}" class="nav-mb-link">About</a>
                </li>
                <li class="nav-mb-item">
                    <a href="{{ route('contact.show') }}" class="nav-mb-link">Contact</a>
                </li>
                @auth
                <li class="nav-mb-item">
                    <a href="#" class="nav-mb-link collapsed mb-menu-link" data-bs-toggle="collapse" data-bs-target="#dropdown-menu-account" aria-expanded="false" aria-controls="dropdown-menu-account">
                        <span>My Account</span>
                        <span class="btn-open-sub"></span>
                    </a>
                    <div id="dropdown-menu-account" class="collapse">
                        <ul class="sub-nav-menu">
                            <li><a href="{{ route('member.dashboard') }}" class="sub-nav-link">Dashboard</a></li>
                            <li><a href="{{ route('member.profile') }}" class="sub-nav-link">Profile</a></li>
                            <li><a href="{{ route('member.orders') }}" class="sub-nav-link">Orders</a></li>
                            <li><a href="{{ route('wishlist.index') }}" class="sub-nav-link">Wishlist</a></li>
                            <li><a href="{{ route('logout') }}" class="sub-nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                        </ul>
                    </div>
                </li>
                @else
                <li class="nav-mb-item">
                    <a href="{{ route('login') }}" class="nav-mb-link">Login</a>
                </li>
                <li class="nav-mb-item">
                    <a href="{{ route('register') }}" class="nav-mb-link">Register</a>
                </li>
                @endauth
            </ul>
            
            @auth
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            @endauth

            <div class="mb-other-content">
                <div class="d-flex group-icon">
                    <a href="{{ route('wishlist.index') }}" class="site-nav-icon"><i class="icon icon-heart"></i>Wishlist</a>
                    <a href="{{ route('compare.index') }}" class="site-nav-icon"><i class="icon icon-shuffle"></i>Compare</a>
                </div>
                <div class="mb-notice">
                    <a href="{{ route('contact.show') }}" class="text-need">Need help?</a>
                </div>
                <ul class="mb-info">
                    <li>Address: {{ generalSettings()->address ?? '1234 Fashion Street, New York' }}</li>
                    <li>Email: <b>{{ generalSettings()->support_email ?? 'support@osmart.com' }}</b></li>
                    <li>Phone: <b>{{ generalSettings()->phone ?? '+1234567890' }}</b></li>
                </ul>
            </div>
        </div>
        
        <div class="mb-bottom">
            <a href="#login" data-bs-toggle="modal" class="site-nav-icon"><i class="icon icon-account"></i>Login</a>
            <div class="bottom-bar-language">
                <div class="tf-currencies">
                    <select class="image-select center style-default type-currencies color-white">
                        <option data-thumbnail="{{ asset('assets/ecomus/images/country/us.svg') }}">USD $ | United States</option>
                        <option data-thumbnail="{{ asset('assets/ecomus/images/country/fr.svg') }}">EUR € | France</option>
                        <option data-thumbnail="{{ asset('assets/ecomus/images/country/de.svg') }}">EUR € | Germany</option>
                        <option data-thumbnail="{{ asset('assets/ecomus/images/country/vn.svg') }}">VND ₫ | Vietnam</option>
                    </select>
                </div>
                <div class="tf-languages">
                    <select class="image-select center style-default type-languages color-white">
                        <option>English</option>
                        <option>العربية</option>
                        <option>简体中文</option>
                        <option>اردو</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Mobile Menu -->