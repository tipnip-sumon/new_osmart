<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{{ str_replace('_', '-', app()->getLocale()) }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Home - ' . config('app.name'))</title>

    <meta name="author" content="{{ config('app.name') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="@yield('description', 'Welcome to ' . config('app.name') . ' - Your premier multivendor ecommerce destination')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('assets/ecomus/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ecomus/fonts/font-icons.css') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/ecomus/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ecomus/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ecomus/css/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/ecomus/css/styles.css') }}"/>
    
    <!-- Custom Laravel Integration CSS -->
    <link rel="stylesheet" href="{{ asset('assets/ecomus/css/custom-laravel.css') }}">
    
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="{{ siteFavicon() }}" type="image/svg+xml">
    <link rel="apple-touch-icon-precomposed" href="{{ siteFavicon() }}">

    <!-- Additional CSS -->
    @stack('styles')
    
    <style>
        /* Count box styling */
        .count-box {
            display: none; /* Hide by default, will be shown by JS if count > 0 */
        }
        
        .count-box.has-items {
            display: inline-block !important;
        }
    </style>

    <!-- Web App Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
</head>

<body class="preload-wrapper popup-loader">
    <!-- RTL Toggle -->
    <a href="#" id="toggle-rtl" class="tf-btn animate-hover-btn btn-fill">RTL</a>
    <!-- /RTL Toggle -->

    <!-- Preload -->
    <div class="preload preload-container">
        <div class="preload-logo">
            <div class="spinner"></div>
        </div>
    </div>
    <!-- /Preload -->

    <div id="wrapper">
        <!-- Announcement Bar -->
        <div class="announcement-bar bg_violet">
            <div class="wrap-announcement-bar">
                <div class="box-sw-announcement-bar speed-1">
                    <div class="announcement-bar-item">
                        <p>FREE SHIPPING AND RETURNS</p>
                    </div>
                    <div class="announcement-bar-item">
                        <p>NEW SEASON, NEW STYLES: FASHION SALE YOU CAN'T MISS</p>
                    </div>
                    <div class="announcement-bar-item">
                        <p>LIMITED TIME OFFER: FASHION SALE YOU CAN'T RESIST</p>
                    </div>
                    <div class="announcement-bar-item">
                        <p>FREE SHIPPING AND RETURNS</p>
                    </div>
                    <div class="announcement-bar-item">
                        <p>NEW SEASON, NEW STYLES: FASHION SALE YOU CAN'T MISS</p>
                    </div>
                    <div class="announcement-bar-item">
                        <p>LIMITED TIME OFFER: FASHION SALE YOU CAN'T RESIST</p>
                    </div>
                </div>
            </div>
            <span class="icon-close close-announcement-bar"></span>
        </div>
        <!-- /Announcement Bar -->
        <!-- Header -->
        <header id="header" class="header-default header-style-2">
            <div class="main-header line">
                <div class="container-full px_15 lg-px_40">
                    <div class="row wrapper-header align-items-center">
                        <div class="col-xl-5 tf-md-hidden">
                            <div class="tf-cur">
                                <div class="tf-currencies">
                                    <select class="image-select center style-default type-currencies">
                                        <option data-thumbnail="{{ asset('assets/ecomus/images/country/fr.svg') }}">EUR € | France</option>
                                        <option data-thumbnail="{{ asset('assets/ecomus/images/country/de.svg') }}">EUR € | Germany</option>
                                        <option selected data-thumbnail="{{ asset('assets/ecomus/images/country/us.svg') }}">USD $ | United States</option>
                                        <option data-thumbnail="{{ asset('assets/ecomus/images/country/vn.svg') }}">VND ₫ | Vietnam</option>
                                    </select>
                                </div>
                                <div class="tf-languages">
                                    <select class="image-select center style-default type-languages">
                                        <option>English</option>
                                        <option>العربية</option>
                                        <option>简体中文</option>
                                        <option>اردو</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-3 tf-lg-hidden">
                            <a href="#mobileMenu" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" viewBox="0 0 24 16" fill="none">
                                    <path d="M2.00056 2.28571H16.8577C17.1608 2.28571 17.4515 2.16531 17.6658 1.95098C17.8802 1.73665 18.0006 1.44596 18.0006 1.14286C18.0006 0.839753 17.8802 0.549063 17.6658 0.334735C17.4515 0.120408 17.1608 0 16.8577 0H2.00056C1.69745 0 1.40676 0.120408 1.19244 0.334735C0.978109 0.549063 0.857702 0.839753 0.857702 1.14286C0.857702 1.44596 0.978109 1.73665 1.19244 1.95098C1.40676 2.16531 1.69745 2.28571 2.00056 2.28571ZM0.857702 8C0.857702 7.6969 0.978109 7.40621 1.19244 7.19188C1.40676 6.97755 1.69745 6.85714 2.00056 6.85714H22.572C22.8751 6.85714 23.1658 6.97755 23.3801 7.19188C23.5944 7.40621 23.7148 7.6969 23.7148 8C23.7148 8.30311 23.5944 8.59379 23.3801 8.80812C23.1658 9.02245 22.8751 9.14286 22.572 9.14286H2.00056C1.69745 9.14286 1.40676 9.02245 1.19244 8.80812C0.978109 8.59379 0.857702 8.30311 0.857702 8ZM0.857702 14.8571C0.857702 14.554 0.978109 14.2633 1.19244 14.049C1.40676 13.8347 1.69745 13.7143 2.00056 13.7143H12.2863C12.5894 13.7143 12.8801 13.8347 13.0944 14.049C13.3087 14.2633 13.4291 14.554 13.4291 14.8571C13.4291 15.1602 13.3087 15.4509 13.0944 15.6653C12.8801 15.8796 12.5894 16 12.2863 16H2.00056C1.69745 16 1.40676 15.8796 1.19244 15.6653C0.978109 15.4509 0.857702 15.1602 0.857702 14.8571Z" fill="currentColor"></path>
                                </svg>
                            </a>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6 text-center">
                            <a href="{{ route('home') }}" class="logo-header">
                                <img src="{{ siteLogo() }}" alt="{{ config('app.name') }}" class="logo">
                            </a>
                        </div>

                        <div class="col-xl-5 col-md-4 col-3">
                            <ul class="nav-icon d-flex justify-content-end align-items-center gap-20">
                                <li class="nav-search">
                                    <a href="#canvasSearch" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft" class="nav-icon-item">
                                        <i class="icon icon-search"></i>
                                    </a>
                                </li>
                                <li class="nav-account">
                                    @auth
                                        <a href="{{ route('member.dashboard') }}" class="nav-icon-item">
                                            <i class="icon icon-account"></i>
                                        </a>
                                    @else
                                        <a href="#login" data-bs-toggle="modal" class="nav-icon-item">
                                            <i class="icon icon-account"></i>
                                        </a>
                                    @endauth
                                </li>
                                <li class="nav-wishlist">
                                    <a href="{{ route('wishlist.index') }}" class="nav-icon-item">
                                        <i class="icon icon-heart"></i>
                                        <span class="count-box" id="wishlist-count">0</span>
                                    </a>
                                </li>
                                <li class="nav-cart">
                                    <a href="#shoppingCart" data-bs-toggle="modal" class="nav-icon-item">
                                        <i class="icon icon-bag"></i>
                                        <span class="count-box" id="cart-count">0</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-bottom line tf-md-hidden">
                <div class="container-full px_15 lg-px_40">
                    <div class="wrapper-header d-flex justify-content-center align-items-center">
                        <nav class="box-navigation text-center">
                            <ul class="box-nav-ul d-flex align-items-center justify-content-center gap-30">
                                <li class="menu-item">
                                    <a href="{{ route('home') }}" class="item-link">Home</a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('shop.index') }}" class="item-link">Shop<i class="icon icon-arrow-down"></i></a>
                                    <div class="sub-menu">
                                        <ul class="menu-list">
                                            <li><a href="{{ route('shop.index') }}" class="menu-link-text link">All Products</a></li>
                                            <li><a href="{{ route('categories.index') }}" class="menu-link-text link">Categories</a></li>
                                            <li><a href="{{ route('collections.index') }}" class="menu-link-text link">Collections</a></li>
                                            <li><a href="{{ route('brands.index') }}" class="menu-link-text link">Brands</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('pages.about') }}" class="item-link">About</a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('contact.show') }}" class="item-link">Contact</a>
                                </li>
                                @auth
                                <li class="menu-item">
                                    <a href="{{ route('member.dashboard') }}" class="item-link">My Account</a>
                                </li>
                                @endauth
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        <!-- /Header -->
        
        @yield('content')
        
        @include('layouts.ecomus.footer')
    </div>

    <!-- Mobile Menu -->
    @include('layouts.ecomus.mobile-menu')

    <!-- Modal Search -->
    @include('layouts.ecomus.search-modal')

    <!-- Modal Login -->
    @include('layouts.ecomus.login-modal')

    <!-- Modal Shopping Cart -->
    @include('layouts.ecomus.cart-modal')

    <!-- JavaScript -->
    <script src="{{ asset('assets/ecomus/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/lazysize.min.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/carousel.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/count-down.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/multiple-modal.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/main.js') }}"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Cart & Wishlist Management -->
    <script src="{{ asset('assets/ecomus/js/cart-wishlist.js') }}"></script>

    <!-- Shipping Calculator -->
    <script src="{{ asset('assets/ecomus/js/shipping-calculator.js') }}"></script>

    <script>
        // Make currency symbol available to JavaScript
        window.currencySymbol = '{{ currencySymbol() }}';
        window.currencyText = '{{ currencyText() }}';
        
        // Initialize counts on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateWishlistCount();
            updateCartCount();
        });
        
        // Function to update wishlist count
        function updateWishlistCount() {
            fetch('{{ route('wishlist.count') }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                const wishlistCountElement = document.getElementById('wishlist-count');
                if (wishlistCountElement && data.count !== undefined) {
                    wishlistCountElement.textContent = data.count;
                    // Show/hide count box based on count
                    if (data.count > 0) {
                        wishlistCountElement.style.display = 'inline-block';
                        wishlistCountElement.classList.add('has-items');
                    } else {
                        wishlistCountElement.style.display = 'none';
                        wishlistCountElement.classList.remove('has-items');
                    }
                }
            })
            .catch(error => {
                console.error('Error updating wishlist count:', error);
            });
        }
        
        // Function to update cart count
        function updateCartCount() {
            fetch('/cart/count', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                const cartCountElement = document.getElementById('cart-count');
                if (cartCountElement && data.count !== undefined) {
                    cartCountElement.textContent = data.count;
                    // Show/hide count box based on count
                    if (data.count > 0) {
                        cartCountElement.style.display = 'inline-block';
                        cartCountElement.classList.add('has-items');
                    } else {
                        cartCountElement.style.display = 'none';
                        cartCountElement.classList.remove('has-items');
                    }
                }
            })
            .catch(error => {
                console.error('Error updating cart count:', error);
            });
        }
        
        // Make functions globally available
        window.updateWishlistCount = updateWishlistCount;
        window.updateCartCount = updateCartCount;
    </script>

    @stack('scripts')

    <!-- Show toastr messages -->
    @if(session('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif

    @if(session('error'))
        <script>
            toastr.error("{{ session('error') }}");
        </script>
    @endif

    @if(session('info'))
        <script>
            toastr.info("{{ session('info') }}");
        </script>
    @endif

    @if(session('warning'))
        <script>
            toastr.warning("{{ session('warning') }}");
        </script>
    @endif
</body>
</html>