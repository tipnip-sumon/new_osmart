<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{{ str_replace('_', '-', app()->getLocale()) }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Home - ' . config('app.name'))</title>

    <meta name="author" content="{{ config('app.name') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="@yield('description', 'Welcome to ' . config('app.name') . ' - Your premier multivendor ecommerce destination')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- font -->
    <link rel="stylesheet" href="{{ asset('assets/ecomus/fonts/fonts.css') }}">
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/ecomus/fonts/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ecomus/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ecomus/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ecomus/css/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/ecomus/css/styles.css') }}" />
    
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
        
        /* Quantity Controls (+ - buttons) */
        .wg-quantity {
            display: flex;
            align-items: center;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            overflow: hidden;
            background: white;
        }
        
        .wg-quantity.small {
            height: 32px;
            max-width: 100px;
        }
        
        .wg-quantity .btn-quantity {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 100%;
            background: #f8f9fa;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #666;
            transition: all 0.2s ease;
            user-select: none;
        }
        
        .wg-quantity .btn-quantity:hover {
            background: #e9ecef;
            color: #000;
        }
        
        .wg-quantity .btn-quantity:active {
            background: #dee2e6;
        }
        
        .wg-quantity input[type="text"] {
            border: none;
            outline: none;
            text-align: center;
            font-size: 14px;
            font-weight: 500;
            width: 44px;
            height: 100%;
            background: white;
            color: #333;
        }
        
        .wg-quantity input[type="text"]:focus {
            outline: none;
            box-shadow: none;
        }
        
        /* Remove button styling */
        .tf-mini-cart-remove {
            color: #999;
            font-size: 12px;
            cursor: pointer;
            margin-top: 4px;
            transition: color 0.2s ease;
        }
        
        .tf-mini-cart-remove:hover {
            color: #dc3545;
        }
        
        /* Mini cart buttons container */
        .tf-mini-cart-btns {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 8px;
        }
    </style>

    <!-- Web App Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
</head>

<body class="preload-wrapper">
    <!-- RTL -->
    <a href="#" id="toggle-rtl" class="tf-btn animate-hover-btn btn-fill">RTL</a>
    <!-- /RTL  -->
    <!-- preload -->
    <div class="preload preload-container">
        <div class="preload-logo">
            <div class="spinner"></div>
        </div>
    </div>
    <!-- /preload -->
    <div id="wrapper">
        @include('layouts.ecomus.header')
        
        @yield('content')
        
        @include('layouts.ecomus.footer')
    </div>
    <!-- /toolbar-bottom -->

    <!-- Mobile Menu -->
    @include('layouts.ecomus.mobile-menu')

    <!-- Modal Search -->
    @include('layouts.ecomus.search-modal')

    <!-- Modal Login -->
    @include('layouts.ecomus.login-modal')

    <!-- Modal Shopping Cart -->
    @include('layouts.ecomus.cart-modal')

    <!-- Additional Product Modals -->
    @include('layouts.ecomus.additional-modals')
    <!-- gotop -->
    <button id="goTop">
        <span class="border-progress"></span>
        <span class="icon icon-arrow-up"></span>
    </button>
    <!-- /gotop -->

    <!-- toolbar-bottom -->
    <div class="tf-toolbar-bottom type-1150">
        <div class="toolbar-item">
            <a href="{{ route('home') }}">
                <div class="toolbar-icon">
                    <i class="icon-home"></i>
                </div>
                <div class="toolbar-label">Home</div>
            </a>
        </div>

        <div class="toolbar-item">
            <a href="{{ route('shop.index') }}">
                <div class="toolbar-icon">
                    <i class="icon-shop"></i>
                </div>
                <div class="toolbar-label">Shop</div>
            </a>
        </div>
        
        <div class="toolbar-item">
            @auth
                <a href="{{ route('member.dashboard') }}">
                    <div class="toolbar-icon">
                        <i class="icon-account"></i>
                    </div>
                    <div class="toolbar-label">Account</div>
                </a>
            @else
                <a href="#login" data-bs-toggle="modal">
                    <div class="toolbar-icon">
                        <i class="icon-account"></i>
                    </div>
                    <div class="toolbar-label">Account</div>
                </a>
            @endauth
        </div>
        
        <div class="toolbar-item">
            <a href="{{ route('wishlist.index') }}">
                <div class="toolbar-icon">
                    <i class="icon-heart"></i>
                    <div class="toolbar-count" id="mobile-wishlist-count" style="display: none;">0</div>
                </div>
                <div class="toolbar-label">Wishlist</div>
            </a>
        </div>
        
        <div class="toolbar-item">
            <a href="#shoppingCart" data-bs-toggle="modal">
                <div class="toolbar-icon">
                    <i class="icon-bag"></i>
                    <div class="toolbar-count" id="mobile-cart-count" style="display: none;">0</div>
                </div>
                <div class="toolbar-label">Cart</div>
            </a>
        </div>
    </div>

    <!-- Javascript -->
    <script src="{{ asset('assets/ecomus/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/carousel.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/lazysize.min.js') }}"></script>
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
                const count = data.count || 0;
                
                // Update header wishlist count
                const wishlistCountElement = document.getElementById('wishlist-count');
                if (wishlistCountElement) {
                    wishlistCountElement.textContent = count;
                    // Show/hide count box based on count
                    if (count > 0) {
                        wishlistCountElement.style.display = 'inline-block';
                        wishlistCountElement.classList.add('has-items');
                    } else {
                        wishlistCountElement.style.display = 'none';
                        wishlistCountElement.classList.remove('has-items');
                    }
                }
                
                // Update mobile toolbar wishlist badge
                const mobileBadge = document.getElementById('mobile-wishlist-count');
                if (mobileBadge) {
                    if (count > 0) {
                        mobileBadge.textContent = count;
                        mobileBadge.style.display = 'flex';
                    } else {
                        mobileBadge.style.display = 'none';
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
                const count = data.count || 0;
                
                // Update header cart count
                const cartCountElement = document.getElementById('cart-count');
                if (cartCountElement) {
                    cartCountElement.textContent = count;
                    // Show/hide count box based on count
                    if (count > 0) {
                        cartCountElement.style.display = 'inline-block';
                        cartCountElement.classList.add('has-items');
                    } else {
                        cartCountElement.style.display = 'none';
                        cartCountElement.classList.remove('has-items');
                    }
                }
                
                // Update mobile toolbar cart badge
                const mobileCartBadge = document.getElementById('mobile-cart-count');
                if (mobileCartBadge) {
                    if (count > 0) {
                        mobileCartBadge.textContent = count;
                        mobileCartBadge.style.display = 'flex';
                    } else {
                        mobileCartBadge.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Error updating cart count:', error);
            });
        }
        
        // Quantity Controls Functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Handle quantity buttons
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-quantity')) {
                    e.preventDefault();
                    
                    const button = e.target;
                    const quantityContainer = button.closest('.wg-quantity');
                    const input = quantityContainer.querySelector('input[type="text"]');
                    let currentValue = parseInt(input.value) || 1;
                    
                    if (button.classList.contains('plus-btn')) {
                        currentValue += 1;
                    } else if (button.classList.contains('minus-btn') && currentValue > 1) {
                        currentValue -= 1;
                    }
                    
                    input.value = currentValue;
                    
                    // Trigger change event for any listeners
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
            
            // Handle direct input changes
            document.addEventListener('input', function(e) {
                if (e.target.matches('.wg-quantity input[type="text"]')) {
                    let value = parseInt(e.target.value) || 1;
                    if (value < 1) value = 1;
                    if (value > 999) value = 999;
                    e.target.value = value;
                }
            });
            
            // Handle remove buttons
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('tf-mini-cart-remove')) {
                    e.preventDefault();
                    
                    const cartItem = e.target.closest('.tf-mini-cart-item');
                    if (cartItem) {
                        // Add fade out animation
                        cartItem.style.transition = 'opacity 0.3s ease';
                        cartItem.style.opacity = '0';
                        
                        setTimeout(() => {
                            cartItem.remove();
                            updateCartCount();
                        }, 300);
                    }
                }
            });
        });
        
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