<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover, shrink-to-fit=no">
    <meta name="description" content="@yield('description', 'O-Smart - Smart Shopping Experience')">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#4F46E5">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>@yield('title', config('app.name', 'O-Smart'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="{{ siteFavicon() }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/x-icon">
    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" href="{{ asset('img/icons/icon-180x180.svg') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('img/icons/icon-152x152.svg') }}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('img/icons/icon-167x167.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/icons/icon-180x180.svg') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}">
    
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <!-- Additional CSS -->
    @stack('styles')

    <!-- Web App Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
</head>
<body>
    @include('layouts.partials.preloader')

    @include('layouts.partials.sidebar')

    @include('layouts.partials.header')

    <!-- PWA Install Alert - Available on all pages -->
    <div class="toast pwa-install-alert shadow bg-white" id="installWrap" role="alert" aria-live="assertive" aria-atomic="true" style="display: none; position: fixed; top: 70px; right: 20px; z-index: 1055; max-width: 350px;">
        <div class="toast-body">
            <div class="content d-flex align-items-center mb-2">
                <img src="{{ asset('assets/img/icons/icon-72x72.png') }}" alt="{{ config('app.name') }} Icon" style="width: 40px; height: 40px; margin-right: 10px;">
                <h6 class="mb-0">Install {{ config('app.name') }}</h6>
                <button class="btn-close ms-auto" type="button" onclick="dismissPWAPrompt()" aria-label="Close"></button>
            </div>
            <span class="mb-2 d-block">Click <strong>Install Now</strong> to enjoy it like a regular app.</span>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-primary" id="installSuha">
                    <i class="ti ti-download me-1"></i>Install Now
                </button>
                <button class="btn btn-sm btn-outline-secondary" onclick="dismissPWAPrompt('later')">Maybe Later</button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="page-content-wrapper">
        @yield('content')
    </div>

    @include('layouts.partials.footer')

    <!-- All JavaScript Files-->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="{{ asset('assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.passwordstrength.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/theme-switching.js') }}"></script>
    <script src="{{ asset('assets/js/active.js') }}"></script>
    <script src="{{ asset('assets/js/pwa.js') }}"></script>
    
    <!-- Global Wishlist Management -->
    <script src="{{ asset('assets/js/wishlist.js') }}"></script>

    <!-- Global Cart Management -->
    <script>
        // Configure Toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        $(document).ready(function() {
            // Global cart management functions using server-side sessions
            window.updateCartCount = function() {
                return fetch('{{ route("cart.count") }}')
                    .then(response => {
                        return response.json();
                    })
                    .then(data => {
                        const totalItems = data.count || 0;
                        
                        // Update footer cart count
                        const footerBadge = $('#cartCountFooter');
                        if (totalItems > 0) {
                            footerBadge.text(totalItems).show();
                        } else {
                            footerBadge.hide();
                        }

                        // Update header cart count
                        const headerBadge = $('#cartCountHeader, .header-cart-count, .cart-count');
                        if (headerBadge.length) {
                            if (totalItems > 0) {
                                headerBadge.text(totalItems).show();
                                // Add bounce animation for visual feedback
                                headerBadge.addClass('animate__bounce');
                                setTimeout(() => {
                                    headerBadge.removeClass('animate__bounce');
                                }, 600);
                            } else {
                                headerBadge.hide();
                            }
                        }

                        // Update any other cart count elements
                        $('.cart-count-display, .cart-item-count').text(totalItems);

                        return totalItems;
                    })
                    .catch(error => {
                        console.error('❌ Error updating cart count:', error);
                        return 0;
                    });
            };

            // Global add to cart function
            window.globalAddToCart = function(productId, quantity = 1) {
                return fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        updateCartCount();
                        return data;
                    } else {
                        throw new Error(data.message || 'Failed to add to cart');
                    }
                })
                .catch(error => {
                    console.error('❌ Error adding to cart:', error);
                    throw error;
                });
            };

            // Global quick add to cart function with enhanced animations
            window.quickAddToCart = function(productId, productName, showAnimation = true) {
                return globalAddToCart(productId, 1)
                    .then(data => {
                        if (showAnimation) {
                            // Animate footer cart icon
                            const footerCartIcon = $('#cartCountFooter').closest('a').find('.ti-basket');
                            footerCartIcon.addClass('animate__pulse');
                            setTimeout(() => {
                                footerCartIcon.removeClass('animate__pulse');
                            }, 600);
                            
                            // Animate header cart icon
                            const headerCartIcon = $('.cart-icon, .header-cart-count').closest('a').find('i');
                            if (headerCartIcon.length) {
                                headerCartIcon.addClass('animate__pulse');
                                setTimeout(() => {
                                    headerCartIcon.removeClass('animate__pulse');
                                }, 600);
                            }

                            // Show success toast notification
                            if (typeof toastr !== 'undefined') {
                                toastr.success(`${productName || 'Product'} added to cart!`, 'Success');
                            }

                            // Add a subtle shake animation to cart areas for extra feedback
                            $('.cart-icon-wrap, .footer-nav-area li:nth-child(4)').addClass('animate__headShake');
                            setTimeout(() => {
                                $('.cart-icon-wrap, .footer-nav-area li:nth-child(4)').removeClass('animate__headShake');
                            }, 1000);
                        }
                        return data;
                    })
                    .catch(error => {
                        console.error('❌ Error in quickAddToCart:', error);
                        if (typeof toastr !== 'undefined') {
                            toastr.error('Failed to add product to cart. Please try again.', 'Error');
                        }
                        throw error;
                    });
            };

            // Initialize cart count on page load
            updateCartCount();

            // Auto-refresh cart count every 30 seconds (optional)
            setInterval(updateCartCount, 30000);
        });
    </script>

    <!-- Additional JavaScript -->
    @stack('scripts')

    <!-- Service Worker Cache Management -->
    <script>
        // Auto-refresh dynamic cache on page visibility changes
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden && typeof window.clearDynamicCache === 'function') {
                // Clear dynamic cache when user returns to page
                // This ensures fresh content after potential updates
                window.clearDynamicCache().catch(error => {
                    console.warn('Failed to clear dynamic cache on visibility change:', error);
                });
            }
        });

        // Clear dynamic cache when navigating to product/category pages
        function refreshPageCache() {
            if (typeof window.refreshProductCache === 'function') {
                window.refreshProductCache().catch(error => {
                    console.warn('Failed to refresh product cache:', error);
                });
            }
        }

        // Add cache refresh for product/category related pages
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const dynamicPaths = ['/products', '/categories', '/collections', '/home', '/'];
            
            if (dynamicPaths.some(path => currentPath.includes(path) || currentPath === path)) {
                // Small delay to ensure service worker is ready
                setTimeout(refreshPageCache, 1000);
            }
        });

        // Listen for storage events (when admin updates products in another tab)
        window.addEventListener('storage', function(e) {
            if (e.key === 'products_updated' || e.key === 'categories_updated') {
                if (typeof window.refreshProductCache === 'function') {
                    window.refreshProductCache().then(() => {
                        // Refresh the page to show new content
                        setTimeout(() => {
                            if (!document.hidden) {
                                console.log('Refreshing page after cache update');
                                window.location.reload();
                            }
                        }, 500);
                    }).catch(error => {
                        console.warn('Failed to refresh cache after storage event:', error);
                        // Still try to reload the page as fallback
                        setTimeout(() => {
                            if (!document.hidden) {
                                window.location.reload();
                            }
                        }, 1000);
                    });
                }
            }
        });
    </script>
</body>
</html>
