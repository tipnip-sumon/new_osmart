<!-- app-header -->
<header class="app-header">
    
    <!-- Start::main-header-container -->
    <div class="main-header-container container-fluid">
        
        <!-- Start::header-content-left -->
        <div class="header-content-left">
            
            <!-- Start::header-element -->
            <div class="header-element">
                <div class="horizontal-logo">
                    @if(Auth::guard('admin')->check())
                        <a href="{{ route('admin.dashboard') }}" class="header-logo">
                    @elseif(Auth::user() && Auth::user()->role === 'vendor')
                        <a href="{{ route('vendor.dashboard') }}" class="header-logo">
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="header-logo">
                    @endif
                        <img src="{{ asset('admin-assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
                        <img src="{{ asset('admin-assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
                        <img src="{{ asset('admin-assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
                        <img src="{{ asset('admin-assets/images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark">
                        <img src="{{ asset('admin-assets/images/brand-logos/desktop-white.png') }}" alt="logo" class="desktop-white">
                        <img src="{{ asset('admin-assets/images/brand-logos/toggle-white.png') }}" alt="logo" class="toggle-white">
                    </a>
                </div>
            </div>
            <!-- End::header-element -->
            
            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link -->
                <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle" data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->
            
        </div>
        <!-- End::header-content-left -->
        
        <!-- Start::header-content-right -->
        <div class="header-content-right">
        <!-- Start::header-element -->
        <div class="header-element header-theme-mode">
            <!-- Start::header-link|layout-setting -->
            <a href="javascript:void(0);" class="header-link layout-setting">
                <span class="light-layout lh-1">
                    <!-- Start::header-link-icon -->
                <i class="fe fe-moon header-link-icon"></i>
                    <!-- End::header-link-icon -->
                </span>
                <span class="dark-layout lh-1">
                    <!-- Start::header-link-icon -->
                <i class="fe fe-sun header-link-icon"></i>
                    <!-- End::header-link-icon -->
                </span>
            </a>
            <!-- End::header-link|layout-setting -->
        </div>
        <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <div class="header-element cart-dropdown">
                        <!-- Start::header-link|dropdown-toggle -->
                        <a href="javascript:void(0);" class="header-link dropdown-toggle" id="messageDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <i class="fe fe-mail header-link-icon"></i>
                            <span class="badge bg-success rounded-pill header-icon-badge" id="cart-icon-badge">0</span>
                        </a>
                        <!-- End::header-link|dropdown-toggle -->
                        <!-- Start::main-header-dropdown -->
                        <div class="main-header-dropdown dropdown-menu dropdown-menu-end" aria-labelledby="messageDropdown">
                            <div class="p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    @if(Auth::check() && Auth::user()->role === 'vendor')
                                        <p class="mb-0 fs-17 fw-medium">Vendor Messages</p>
                                    @else
                                        <p class="mb-0 fs-17 fw-medium">Messages</p>
                                    @endif
                                    <span class="badge bg-success-transparent" id="cart-data">0 Messages</span>
                                </div>
                            </div>
                            <div><hr class="dropdown-divider"></div>
                            <div class="p-5 empty-item">
                                <div class="text-center">
                                    <span class="avatar avatar-xl avatar-rounded bg-warning-transparent">
                                        <i class="ri-chat-2-line fs-2"></i>
                                    </span>
                                    <h6 class="fw-bold mb-1 mt-3">No Messages</h6>
                                    <span class="mb-3 fw-normal fs-13 d-block">All messages will appear here</span>
                                </div>
                            </div>
                        </div>
                        <!-- End::main-header-dropdown -->
                    </div>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <div class="header-element header-shortcuts-dropdown d-none d-lg-flex">
                        <!-- Start::header-link|dropdown-toggle -->
                        <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" id="notificationDropdown" aria-expanded="false">
                            <i class="fe fe-grid header-link-icon"></i>
                        </a>
                        <!-- End::header-link|dropdown-toggle -->
                        <!-- Start::main-header-dropdown -->
                        <div class="main-header-dropdown header-shortcuts-dropdown dropdown-menu pb-0 dropdown-menu-end" aria-labelledby="notificationDropdown">
                            <div class="p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    @if(Auth::check() && Auth::user()->role === 'vendor')
                                        <p class="mb-0 fs-17 fw-medium">Quick Access</p>
                                    @else
                                        <p class="mb-0 fs-17 fw-medium">Quick Access</p>
                                    @endif
                                </div>
                            </div>
                            <div class="dropdown-divider mb-0"></div>
                            <div class="main-header-shortcuts p-3" id="header-shortcut-scroll">
                               <div class="row g-2">
                                   @if(Auth::check() && Auth::user()->role === 'vendor')
                                       <!-- Vendor Shortcuts -->
                                       <div class="col-6">
                                            <a href="{{ route('vendor.products.index') }}">
                                                <div class="text-center p-3 related-app border">
                                                    <span class="avatar fs-23 bg-primary-transparent p-2 mb-2">
                                                        <i class='bx bx-package text-primary'></i>
                                                    </span>
                                                    <span class="d-block fs-13 fw-normal">Products</span>
                                                </div>
                                            </a>
                                       </div>
                                       <div class="col-6">
                                            <a href="{{ route('vendor.orders.index') }}">
                                                <div class="text-center p-3 related-app border">
                                                    <span class="avatar fs-23 bg-success-transparent p-2 mb-2">
                                                        <i class='bx bx-shopping-bag text-success'></i>
                                                    </span>
                                                    <span class="d-block fs-13 fw-normal">Orders</span>
                                                </div>
                                            </a>
                                       </div>
                                       <div class="col-6">
                                            <a href="{{ route('vendor.profile') }}">
                                                <div class="text-center p-3 related-app border">
                                                    <span class="avatar bg-warning-transparent fs-23 bg p-2 mb-2">
                                                        <i class='bx bx-user text-warning'></i>
                                                    </span>
                                                    <span class="d-block fs-13 fw-normal">Profile</span>
                                                </div>
                                            </a>
                                       </div>
                                       <div class="col-6">
                                            <a href="{{ route('home') }}" target="_blank">
                                                <div class="text-center p-3 related-app border">
                                                    <span class="avatar bg-teal-transparent fs-23 bg p-2 mb-2">
                                                        <i class='bx bx-store text-teal'></i>
                                                    </span>
                                                    <span class="d-block fs-13 fw-normal">View Store</span>
                                                </div>
                                            </a>
                                       </div>
                                   @else
                                       <!-- Admin Shortcuts -->
                                       <div class="col-6">
                                            <a href="{{ route('admin.invoices.index') }}">
                                                <div class="text-center p-3 related-app border">
                                                    <span class="avatar fs-23 bg-primary-transparent p-2 mb-2">
                                                        <i class='bx bx-receipt text-primary'></i>
                                                    </span>
                                                    <span class="d-block fs-13 fw-normal">Invoices</span>
                                                </div>
                                            </a>
                                       </div>
                                       <div class="col-6">
                                            <a href="{{ route('admin.invoices.create') }}">
                                                <div class="text-center p-3 related-app border">
                                                    <span class="avatar fs-23 bg-success-transparent p-2 mb-2">
                                                        <i class='bx bx-plus-circle text-success'></i>
                                                    </span>
                                                    <span class="d-block fs-13 fw-normal">New Invoice</span>
                                                </div>
                                            </a>
                                       </div>
                                       <div class="col-6">
                                            <a href="{{ route('admin.orders.index') }}">
                                                <div class="text-center p-3 related-app border">
                                                    <span class="avatar bg-warning-transparent fs-23 bg p-2 mb-2">
                                                        <i class='bx bx-shopping-bag text-warning'></i>
                                                    </span>
                                                    <span class="d-block fs-13 fw-normal">Orders</span>
                                                </div>
                                            </a>
                                       </div>
                                       <div class="col-6">
                                            <a href="{{ route('admin.invoices.analytics') }}">
                                                <div class="text-center p-3 related-app border">
                                                    <span class="avatar bg-info-transparent fs-23 bg p-2 mb-2">
                                                        <i class='bx bx-bar-chart text-info'></i>
                                                    </span>
                                                    <span class="d-block fs-13 fw-normal">Analytics</span>
                                                </div>
                                            </a>
                                       </div>
                                       <div class="col-6">
                                            <a href="{{ route('admin.products.index') }}">
                                                <div class="text-center p-3 related-app border">
                                                    <span class="avatar bg-purple-transparent fs-23 bg p-2 mb-2">
                                                        <i class='bx bx-package text-purple'></i>
                                                    </span>
                                                    <span class="d-block fs-13 fw-normal">Products</span>
                                                </div>
                                            </a>
                                       </div>
                                       <div class="col-6">
                                            <a href="{{ route('admin.settings.general') }}">
                                                <div class="text-center p-3 related-app border">
                                                    <span class="avatar bg-teal-transparent fs-23 bg p-2 mb-2">
                                                        <i class='bx bx-cog text-teal'></i>
                                                    </span>
                                                    <span class="d-block fs-13 fw-normal">Settings</span>
                                                </div>
                                            </a>
                                       </div>
                                   @endif
                               </div>
                            </div>
                        </div>
                        <!-- End::main-header-dropdown -->
                    </div>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <div class="header-element header-fullscreen">
                        <!-- Start::header-link -->
                        <a onclick="toggleFullscreen();" href="javascript:void(0);" class="header-link">
                            <i class="fe fe-maximize full-screen-open header-link-icon"></i>
                            <i class="fe fe-minimize full-screen-close header-link-icon d-none"></i>
                        </a>
                        <!-- End::header-link -->
                    </div>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <div class="header-element notifications-dropdown">
                        <!-- Start::header-link|dropdown-toggle -->
                        <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <i class="fe fe-bell header-link-icon"></i>
                            <span class="badge bg-danger rounded-pill header-icon-badge">0</span>
                        </a>
                        <!-- End::header-link|dropdown-toggle -->
                        <!-- Start::main-header-dropdown -->
                        <div class="main-header-dropdown dropdown-menu dropdown-menu-end">
                            <div class="p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="mb-0 fs-17 fw-medium">Notifications</p>
                                    <span class="badge bg-danger-transparent">0 New</span>
                                </div>
                            </div>
                            <div><hr class="dropdown-divider"></div>
                            <div class="p-5 empty-item">
                                <div class="text-center">
                                    <span class="avatar avatar-xl avatar-rounded bg-info-transparent">
                                        <i class="fe fe-bell fs-2"></i>
                                    </span>
                                    <h6 class="fw-bold mb-1 mt-3">No Notifications</h6>
                                    <span class="mb-3 fw-normal fs-13 d-block">All notifications will appear here</span>
                                </div>
                            </div>
                        </div>
                        <!-- End::main-header-dropdown -->
                    </div>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <div class="header-element main-header-profile">
                        <!-- Start::header-link|dropdown-toggle -->
                        <a href="javascript:void(0);" class="header-link dropdown-toggle mx-0 w-100" id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <div>
                                @php
                                    $currentUser = null;
                                    $avatarPath = asset('admin-assets/images/users/16.jpg'); // Default avatar
                                    
                                    if (Auth::guard('admin')->check()) {
                                        $currentUser = Auth::guard('admin')->user();
                                        $avatarPath = $currentUser->avatar ? asset('storage/' . $currentUser->avatar) : asset('admin-assets/images/users/16.jpg');
                                    } elseif (Auth::check()) {
                                        $currentUser = Auth::user();
                                        $avatarPath = $currentUser->avatar ? asset('storage/' . $currentUser->avatar) : asset('admin-assets/images/users/16.jpg');
                                    }
                                @endphp
                                <img src="{{ $avatarPath }}" alt="img" class="rounded-3 avatar avatar-md">
                            </div>
                        </a>
                        <!-- End::header-link|dropdown-toggle -->
                        <ul class="main-header-dropdown dropdown-menu pt-0 header-profile-dropdown dropdown-menu-end" aria-labelledby="mainHeaderProfile">
                            <Li>
                                <div class="p-3 text-center border-bottom">
                                    @php
                                        $displayUser = null;
                                        $displayName = 'User';
                                        $displayRole = 'User';
                                        
                                        if (Auth::guard('admin')->check()) {
                                            $displayUser = Auth::guard('admin')->user();
                                            $displayName = $displayUser->name ?? 'Admin';
                                            $displayRole = 'Admin';
                                        } elseif (Auth::check()) {
                                            $displayUser = Auth::user();
                                            $displayName = $displayUser->name ?? $displayUser->firstname . ' ' . $displayUser->lastname;
                                            $displayRole = ucfirst($displayUser->role ?? 'User');
                                        }
                                    @endphp
                                    <a href="#" class="text-center fw-semibold">{{ $displayName }}</a>
                                    <p class="text-center user-semi-title fs-13 mb-0">
                                        @if($displayUser && isset($displayUser->role) && $displayUser->role === 'vendor')
                                            Vendor - {{ $displayUser->shop_name ?? 'Shop Owner' }}
                                        @else
                                            {{ $displayRole }}
                                        @endif
                                    </p>
                                </div>
                            </Li>
                            @php
                                $menuUser = null;
                                $isAdmin = false;
                                $isVendor = false;
                                
                                if (Auth::guard('admin')->check()) {
                                    $menuUser = Auth::guard('admin')->user();
                                    $isAdmin = true;
                                } elseif (Auth::check()) {
                                    $menuUser = Auth::user();
                                    $isVendor = ($menuUser->role === 'vendor');
                                    $isAdmin = ($menuUser->role === 'admin');
                                }
                            @endphp
                            
                            @if($isVendor)
                                <li><a class="dropdown-item d-flex align-items-center" href="{{ route('vendor.profile') }}"><i class="fe fe-user me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="{{ route('vendor.dashboard') }}"><i class="fe fe-home me-2"></i>Dashboard</a></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="{{ route('vendor.products.index') }}"><i class="fe fe-package me-2"></i>My Products</a></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="{{ route('vendor.orders.index') }}"><i class="fe fe-shopping-cart me-2"></i>Orders</a></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="{{ route('vendor.dashboard') }}"><i class="fe fe-mail me-2"></i>Messages <span class="badge bg-success-transparent ms-auto">0</span></a></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="{{ route('home') }}" target="_blank"><i class="fe fe-external-link me-2"></i>View Store</a></li>
                            @else
                                <li><a class="dropdown-item d-flex align-items-center" href="{{ route('admin.dashboard') }}"><i class="fe fe-user me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="{{ route('admin.dashboard') }}"><i class="fe fe-home me-2"></i>Dashboard</a></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="{{ route('admin.dashboard') }}"><i class="fe fe-users me-2"></i>Manage Users</a></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="{{ route('admin.settings.general') }}"><i class="fe fe-settings me-2"></i>Settings</a></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="{{ route('admin.support.index') }}"><i class="fe fe-mail me-2"></i>Support <span class="badge bg-info-transparent ms-auto">0</span></a></li>
                            @endif
                            <li><a class="dropdown-item d-flex align-items-center" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#changepasswordnmodal"><i class="fe fe-edit-3 me-2"></i>Change Password</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="fe fe-headphones me-2"></i>Support</a></li>
                            <li>
                                @php
                                    $logoutRoute = 'general.logout'; // Default
                                    $user = null;
                                    $userName = 'User';
                                    
                                    // Check admin guard first (admin users don't have roles, they're in admin table)
                                    if (Auth::guard('admin')->check()) {
                                        $logoutRoute = 'admin.logout';
                                        $user = Auth::guard('admin')->user();
                                        $userName = $user->name ?? 'Admin';
                                    } 
                                    // Then check regular user guard (users table with roles)
                                    elseif (Auth::check()) {
                                        $user = Auth::user();
                                        $userName = $user->name ?? $user->firstname . ' ' . $user->lastname;
                                        
                                        if ($user->role === 'affiliate') {
                                            $logoutRoute = 'affiliate.logout';
                                        } elseif ($user->role === 'vendor') {
                                            $logoutRoute = 'vendor.logout.vendor';
                                        }
                                        // Note: Regular users with role 'admin' would be handled here if needed
                                        // but typically admin authentication uses the admin guard
                                    }
                                @endphp
                                
                                <!-- Debug info (remove in production) -->
                                {{-- Debug: Route={{ $logoutRoute }}, User={{ $userName }}, Role={{ $user->role ?? 'none' }} --}}
                                
                                <form method="POST" action="{{ route($logoutRoute) }}" class="d-inline" id="admin-logout-form">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center border-0 bg-transparent w-100 logout-btn" 
                                            title="Click to log out from your {{ $user->role ?? 'admin' }} account">
                                        <i class="fe fe-power me-2"></i>Log Out
                                    </button>
                                </form>
                                
                                <!-- Alternative AJAX logout option -->
                                <div class="dropdown-item d-flex align-items-center border-0 bg-transparent w-100 ajax-logout-btn" 
                                     style="cursor: pointer; display: none;" 
                                     title="Alternative logout method">
                                    <i class="fe fe-power me-2"></i>Log Out (AJAX)
                                </div>
                            </li>
                        </ul>
                    </div>  
                    <!-- End::header-element -->
            
        </div>
        <!-- End::header-content-right -->
        
    </div>
    <!-- End::main-header-container -->
    
</header>
<!-- /app-header -->
<script>
    function toggleFullscreen() {
        try {
            if (!document.fullscreenElement) {
                // Enter fullscreen
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen();
                } else if (document.documentElement.msRequestFullscreen) {
                    document.documentElement.msRequestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
                }
                
                // Update icons
                document.querySelector('.full-screen-open')?.classList.add('d-none');
                document.querySelector('.full-screen-close')?.classList.remove('d-none');
            } else {
                // Exit fullscreen
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                }
                
                // Update icons
                document.querySelector('.full-screen-open')?.classList.remove('d-none');
                document.querySelector('.full-screen-close')?.classList.add('d-none');
            }
        } catch (error) {
            console.warn('Fullscreen not supported or error occurred:', error);
        }
    }

    // Listen for fullscreen changes to update icons
    document.addEventListener('fullscreenchange', function() {
        if (!document.fullscreenElement) {
            document.querySelector('.full-screen-open')?.classList.remove('d-none');
            document.querySelector('.full-screen-close')?.classList.add('d-none');
        }
    });

    // Handle other browser prefixes
    document.addEventListener('webkitfullscreenchange', function() {
        if (!document.webkitFullscreenElement) {
            document.querySelector('.full-screen-open')?.classList.remove('d-none');
            document.querySelector('.full-screen-close')?.classList.add('d-none');
        }
    });

    document.addEventListener('mozfullscreenchange', function() {
        if (!document.mozFullScreenElement) {
            document.querySelector('.full-screen-open')?.classList.remove('d-none');
            document.querySelector('.full-screen-close')?.classList.add('d-none');
        }
    });

    document.addEventListener('msfullscreenchange', function() {
        if (!document.msFullscreenElement) {
            document.querySelector('.full-screen-open')?.classList.remove('d-none');
            document.querySelector('.full-screen-close')?.classList.add('d-none');
        }
    });
    
    // Logout functionality enhancement
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Header scripts loaded - logout handled by main layout');
        
        // AJAX logout fallback - only show if regular logout fails
        const ajaxLogoutBtn = document.querySelector('.ajax-logout-btn');
        if (ajaxLogoutBtn) {
            // Show AJAX button after 5 seconds as fallback
            setTimeout(function() {
                ajaxLogoutBtn.style.display = 'block';
            }, 5000);
            
            ajaxLogoutBtn.addEventListener('click', function() {
                // Use SweetAlert for AJAX logout confirmation
                Swal.fire({
                    title: 'Alternative Logout',
                    text: 'Use AJAX logout method?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, log out!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('AJAX logout initiated');
                        
                        // Show loading
                        Swal.fire({
                            title: 'Logging out...',
                            text: 'Processing AJAX logout',
                            icon: 'info',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        @if(Auth::check() && Auth::user()->role === 'vendor')
                        fetch('{{ route("vendor.ajax.logout") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                Swal.fire({
                                    title: 'Logged Out!',
                                    text: data.message || 'Successfully logged out',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Clear service worker cache before redirect
                                    if ('serviceWorker' in navigator && 'caches' in window) {
                                        caches.keys().then(function(cacheNames) {
                                            return Promise.all(
                                                cacheNames.map(function(cacheName) {
                                                    return caches.delete(cacheName);
                                                })
                                            );
                                        }).finally(function() {
                                            window.location.href = data.redirect_url || '/vendor/login';
                                        });
                                    } else {
                                        window.location.href = data.redirect_url || '/vendor/login';
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: 'Logout Failed',
                                    text: 'Logout failed: ' + data.message,
                                    icon: 'error'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Logout error:', error);
                            Swal.fire({
                                title: 'Error',
                                text: 'Logout failed. Please try refreshing the page.',
                                icon: 'error'
                            });
                        });
                        @else
                        // Fallback for non-vendor users
                        setTimeout(() => {
                            window.location.href = '/login';
                        }, 1000);
                        @endif
                    }
                });
            });
        }
    });
</script>
