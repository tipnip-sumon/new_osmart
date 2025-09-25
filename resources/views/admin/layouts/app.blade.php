<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="dark" data-toggled="close">
<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name') }}</title>
    <meta name="Description" content="Admin Dashboard for {{ config('app.name') }}">
    <meta name="Author" content="{{ config('app.name') }}">
    <meta name="keywords" content="admin, dashboard, ecommerce, multivendor">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ siteFavicon() }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('img/icons/icon-180x180.svg') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('img/icons/icon-152x152.svg') }}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('img/icons/icon-167x167.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/icons/icon-180x180.svg') }}">
    
    <!-- Progressive Web App -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#3b82f6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="O-Smart BD">
    <meta name="application-name" content="O-Smart BD">
    <meta name="msapplication-TileColor" content="#3b82f6">
    <meta name="msapplication-TileImage" content="{{ asset('assets/img/icons/icon-144x144.png') }}">
    
    <!-- Choices JS -->
    <script src="{{ asset('admin-assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>

    <!-- Main Theme Js -->
    <script src="{{ asset('admin-assets/js/main.js') }}"></script>
    
    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('admin-assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Style Css -->
    <link href="{{ asset('admin-assets/css/styles.min.css') }}" rel="stylesheet">

    <!-- Icons Css -->
    <link href="{{ asset('admin-assets/css/icons.css') }}" rel="stylesheet">

    <!-- Node Waves Css -->
    <link href="{{ asset('admin-assets/libs/node-waves/waves.min.css') }}" rel="stylesheet"> 

    <!-- Simplebar Css -->
    <link href="{{ asset('admin-assets/libs/simplebar/simplebar.min.css') }}" rel="stylesheet">
    
    <!-- Color Picker Css -->
    <link rel="stylesheet" href="{{ asset('admin-assets/libs/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/libs/@simonwep/pickr/themes/nano.min.css') }}">
    
    <!-- Choices Css -->
    <link rel="stylesheet" href="{{ asset('admin-assets/libs/choices.js/public/assets/styles/choices.min.css') }}">

    <!-- Modern Header CSS -->
    <link href="{{ asset('admin-assets/css/modern-header.css') }}" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    
    <!-- Custom Flash Message Styles -->
    <style>
        /* SweetAlert2 Custom Styles */
        .swal-wide {
            width: 600px !important;
            max-width: 90vw !important;
        }
        
        /* Enhanced Alert Styles */
        .alert {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f1b0b7 100%);
            border-left: 4px solid #dc3545;
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left: 4px solid #ffc107;
        }
        
        .alert-info {
            background: linear-gradient(135deg, #cce7ff 0%, #b8daff 100%);
            border-left: 4px solid #17a2b8;
        }
        
        /* Toast positioning improvements */
        .swal2-toast .swal2-title {
            font-size: 14px !important;
            font-weight: 600 !important;
        }
        
        .swal2-toast .swal2-html-container {
            font-size: 13px !important;
        }
        
        /* Animation improvements */
        .alert {
            animation: slideInRight 0.3s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>

    <!-- Additional CSS -->
    @stack('styles')
</head>

<body>
    <!-- Start Switcher -->
    @include('admin.layouts.partials.switcher')
    <!-- End Switcher -->

    <!-- Loader -->
    @include('admin.layouts.partials.loader')
    <!-- Loader -->

    <div class="page">
        <!-- app-header -->
        @include('admin.layouts.partials.header')
        <!-- /app-header -->

        <!-- Start::app-sidebar -->
        @include('admin.layouts.partials.sidebar')
        <!-- End::app-sidebar -->

        <!-- Start::app-content -->
        <div class="main-content app-content">
            <div class="container-fluid">
                <!-- Flash messages handled by JavaScript SweetAlert toasts below -->

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ri-error-warning-line me-1"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
        <!-- End::app-content -->

        <!-- Footer Start -->
        @include('admin.layouts.partials.footer')
        <!-- Footer End -->
    </div>

    <!-- Scroll To Top -->
    <div class="scrollToTop">
        <span class="arrow"><i class="fe fe-arrow-up"></i></span>
    </div>
    <div id="responsive-overlay"></div>
    <!-- Scroll To Top End -->

    <!-- jQuery JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="{{ asset('admin-assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('admin-assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Defaultmenu JS -->
    <script src="{{ asset('admin-assets/js/defaultmenu.min.js') }}"></script>

    <!-- Node Waves JS-->
    <script src="{{ asset('admin-assets/libs/node-waves/waves.min.js') }}"></script>

    <!-- Sticky JS -->
    <script src="{{ asset('admin-assets/js/sticky.js') }}"></script>

    <!-- Simplebar JS -->
    <script src="{{ asset('admin-assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/simplebar.js') }}"></script>

    <!-- Color Picker JS -->
    <script src="{{ asset('admin-assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    
    <!-- Toastr Configuration -->
    <script>
        // Configure toastr options
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
            "timeOut": "4000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    </script>

    <!-- Flash Messages with SweetAlert2 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
            @endif

            @if(session('warning'))
                Swal.fire({
                    title: 'Warning!',
                    text: '{{ session('warning') }}',
                    icon: 'warning',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4500,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
            @endif

            @if(session('info'))
                Swal.fire({
                    title: 'Info',
                    text: '{{ session('info') }}',
                    icon: 'info',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
            @endif

            @if($errors->any())
                let errorMessages = [
                    @foreach($errors->all() as $error)
                        '{{ addslashes($error) }}',
                    @endforeach
                ];
                
                Swal.fire({
                    title: 'Validation Errors',
                    html: '<ul style="text-align: left; margin: 0; padding-left: 20px;">' + 
                          errorMessages.map(error => '<li>' + error + '</li>').join('') + 
                          '</ul>',
                    icon: 'error',
                    confirmButtonText: 'Fix Errors',
                    customClass: {
                        popup: 'swal-wide'
                    }
                });
            @endif
        });
    </script>

    <!-- Modern Header JS -->
    <script src="{{ asset('admin-assets/js/modern-header.js') }}"></script>

    <!-- Custom-Switcher JS -->
    <script>
        // Defensive wrapper for custom-switcher to prevent null element errors
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Only load custom-switcher if required elements exist
                const switcherElements = document.querySelectorAll('[data-bs-toggle="switcher"]');
                if (switcherElements.length > 0) {
                    // Load the script only if switcher elements exist
                    const script = document.createElement('script');
                    script.src = "{{ asset('admin-assets/js/custom-switcher.min.js') }}";
                    script.onerror = function() {
                        console.warn('Custom switcher script could not be loaded');
                    };
                    document.head.appendChild(script);
                }
            } catch (error) {
                console.warn('Custom switcher initialization failed:', error);
            }
        });
    </script>

    <!-- KYC Statistics Modal -->
    <div class="modal fade" id="kycStatsModal" tabindex="-1" aria-labelledby="kycStatsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kycStatsModalLabel">
                        <i class="bx bx-stats me-2"></i>Live KYC Statistics
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="kycStatsContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading statistics...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="refreshKycStats()">
                        <i class="bx bx-refresh me-1"></i>Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- KYC Management JavaScript -->
    <script>
        // Load KYC Statistics
        function loadKycStats() {
            const statsContent = document.getElementById('kycStatsContent');
            statsContent.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading statistics...</p>
                </div>
            `;
            
            fetch('{{ route("admin.kyc.dashboard.stats") }}')
                .then(response => response.json())
                .then(data => {
                    statsContent.innerHTML = `
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h3 class="mb-1">${data.total || 0}</h3>
                                        <p class="mb-0">Total KYC Applications</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h3 class="mb-1">${data.pending || 0}</h3>
                                        <p class="mb-0">Pending Review</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h3 class="mb-1">${data.approved || 0}</h3>
                                        <p class="mb-0">Approved</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h3 class="mb-1">${data.rejected || 0}</h3>
                                        <p class="mb-0">Rejected</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">Processing Time Analytics</h6>
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <h5 class="text-info">${data.avg_processing_time || 'N/A'}</h5>
                                                <small class="text-muted">Avg Processing Time</small>
                                            </div>
                                            <div class="col-md-4">
                                                <h5 class="text-success">${data.completion_rate || '0%'}</h5>
                                                <small class="text-muted">Completion Rate</small>
                                            </div>
                                            <div class="col-md-4">
                                                <h5 class="text-primary">${data.today_submissions || 0}</h5>
                                                <small class="text-muted">Today's Submissions</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Error loading KYC stats:', error);
                    statsContent.innerHTML = `
                        <div class="alert alert-danger text-center">
                            <i class="bx bx-error me-2"></i>
                            Failed to load statistics. Please try again.
                        </div>
                    `;
                });
        }

        // Refresh KYC Statistics
        function refreshKycStats() {
            loadKycStats();
        }

        // Auto-refresh sidebar badges every 30 seconds
        setInterval(function() {
            if (window.location.pathname.includes('/admin/')) {
                fetch('{{ route("admin.kyc.dashboard.stats") }}')
                    .then(response => response.json())
                    .then(data => {
                        // Update sidebar badges
                        const badges = document.querySelectorAll('[data-kyc-count]');
                        badges.forEach(badge => {
                            const type = badge.getAttribute('data-kyc-count');
                            if (data[type] !== undefined) {
                                badge.textContent = data[type];
                                badge.style.display = data[type] > 0 ? 'inline' : 'none';
                            }
                        });
                    })
                    .catch(error => console.warn('Auto-refresh failed:', error));
            }
        }, 30000);
    </script>

    <!-- Additional JavaScript -->
    @stack('scripts')
    @yield('scripts')

    <!-- Custom JS -->
    <script src="{{ asset('admin-assets/js/custom.js') }}"></script>
    
    <!-- Service Worker Registration -->
    <script>
        // Register Service Worker for vendor dashboard and admin areas
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('{{ asset('service-worker.js') }}')
                    .then(function(registration) {
                        // Check for updates
                        registration.addEventListener('updatefound', function() {
                            const newWorker = registration.installing;
                            if (newWorker) {
                                newWorker.addEventListener('statechange', function() {
                                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                        // New service worker is available
                                        Swal.fire({
                                            title: 'Update Available',
                                            text: 'A new version is available. Refresh to update?',
                                            icon: 'info',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#6c757d',
                                            confirmButtonText: 'Refresh Now',
                                            cancelButtonText: 'Later'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.reload();
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    })
                    .catch(function(error) {
                        // Service worker registration failed silently
                    });
            });
        }
        
        // PWA install prompt disabled
        
        // PWA install tracking disabled
        
        // Enhanced logout handling for both header and sidebar
        document.addEventListener('DOMContentLoaded', function() {
            // Handle header logout button
            const headerLogoutBtn = document.querySelector('.logout-btn');
            if (headerLogoutBtn) {
                headerLogoutBtn.addEventListener('click', function(e) {
                    handleVendorLogout(e, this);
                });
            }
            
            // Handle sidebar logout button
            const sidebarLogoutBtn = document.querySelector('.vendor-logout-btn');
            if (sidebarLogoutBtn) {
                sidebarLogoutBtn.addEventListener('click', function(e) {
                    handleVendorLogout(e, this);
                });
            }
            
            // Common logout handler
            function handleVendorLogout(event, button) {
                event.preventDefault();
                
                // Use SweetAlert for better UX
                Swal.fire({
                    title: 'Log Out?',
                    text: 'Are you sure you want to log out from your vendor account?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, log out!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading indicator
                        Swal.fire({
                            title: 'Logging out...',
                            text: 'Clearing cache and ending session',
                            icon: 'info',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Clear service worker cache before logout
                        if ('serviceWorker' in navigator) {
                            navigator.serviceWorker.getRegistrations().then(function(registrations) {
                                for (let registration of registrations) {
                                    registration.unregister();
                                }
                            });
                            
                            // Clear all caches
                            if ('caches' in window) {
                                caches.keys().then(function(cacheNames) {
                                    return Promise.all(
                                        cacheNames.map(function(cacheName) {
                                            return caches.delete(cacheName);
                                        })
                                    );
                                }).then(function() {
                                    // Submit the form after cache cleanup
                                    const form = button.closest('form');
                                    if (form) {
                                        form.submit();
                                    }
                                }).catch(function(error) {
                                    // Still submit form even if cache cleanup fails
                                    const form = button.closest('form');
                                    if (form) {
                                        form.submit();
                                    }
                                });
                            } else {
                                // No cache API, just submit form
                                const form = button.closest('form');
                                if (form) {
                                    form.submit();
                                }
                            }
                        } else {
                            // No service worker support, just submit form
                            const form = button.closest('form');
                            if (form) {
                                form.submit();
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
