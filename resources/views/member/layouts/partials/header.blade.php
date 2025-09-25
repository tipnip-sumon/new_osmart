<!-- app-header --> 
         <header class="app-header">

            <!-- Start::main-header-container -->
            <div class="main-header-container container-fluid">

                <!-- Start::header-content-left -->
                <div class="header-content-left">

                    <!-- Start::header-element -->
                    <div class="header-element" style="display: none;">
                        <div class="horizontal-logo">
                            <a href="{{ route('member.dashboard') }}" class="header-logo">
                                @php
                                    $siteLogo = siteLogo() ?? 'admin-assets/images/brand-logos/desktop-logo.png';
                                    $siteName = siteName();
                                @endphp
                                <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}" class="desktop-logo" style="max-height: 40px;">
                                <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}" class="toggle-logo" style="max-height: 35px;">
                                <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}" class="desktop-dark" style="max-height: 40px;">
                                <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}" class="toggle-dark" style="max-height: 35px;">
                                <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}" class="desktop-white" style="max-height: 40px;">
                                <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}" class="toggle-white" style="max-height: 35px;">
                            </a>
                        </div>
                    </div>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <div class="header-element">
                        <!-- Start::header-link -->
                        <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle" data-bs-toggle="sidebar" href="javascript:void(0);">
                            <span></span>
                        </a>
                        <!-- End::header-link -->
                        <!-- Start::header-search -->
                        <div class="mt-0">
                            <form class="form-inline d-none d-lg-block" onsubmit="performSearch(event)">
                                <div class="search-element">
                                    <input type="search" class="form-control header-search" 
                                           placeholder="Search categories, brands, products..." 
                                           aria-label="Search categories, brands, and products" 
                                           tabindex="1"
                                           id="mainSearchInput"
                                           autocomplete="off"
                                           onclick="showDefaultKeywords()"
                                           onfocus="showDefaultKeywords()">
                                    <button class="btn" type="submit">
                                        <i class="fe fe-search"></i>
                                    </button>
                                    <!-- Search Results Dropdown -->
                                    <div class="search-results-dropdown" id="searchResults" style="display: none;">
                                        <div class="search-results-content" id="searchResultsContent">
                                            <!-- Results will be loaded here -->
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- End::header-search -->
                    </div>
                    <!-- End::header-element -->

                </div>
                <!-- End::header-content-left -->

                <!-- Start::header-content-right -->
                <div class="header-content-right">

                    <!-- Start::header-element -->
                        <div class="header-element header-search d-lg-none">
                            <!-- Start::header-link -->
                            <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                            </a>
            
                            <ul class="main-header-dropdown dropdown-menu dropdown-menu-end" style="min-width: 350px;">
                                <li>
                                    <form class="dropdown-item d-flex align-items-center" onsubmit="performMobileSearch(event)">
                                        <span class="input-group position-relative">
                                            <input type="text" class="form-control" placeholder="Search categories, brands, products by name..." 
                                                   aria-label="Search categories, brands, and products by name" aria-describedby="button-addon2"
                                                   id="mobileSearchInput"
                                                   autocomplete="off"
                                                   onclick="showMobileDefaultKeywords()"
                                                   onfocus="showMobileDefaultKeywords()">
                                            <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
                                            <!-- Mobile Search Results Dropdown -->
                                            <div class="search-results-dropdown position-absolute" id="mobileSearchResults" style="display: none; top: 100%; left: 0; right: 0; z-index: 1050;">
                                                <div class="search-results-content" id="mobileSearchResultsContent">
                                                    <!-- Results will be loaded here -->
                                                </div>
                                            </div>
                                        </span>
                                    </form>
                                </li>
                            </ul>
            
                            <!-- End::header-link -->
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
                                    <p class="mb-0 fs-17 fw-medium">Messages</p>
                                    <span class="badge bg-success-transparent" id="cart-data">0 New</span>
                                </div>
                            </div>
                            <div><hr class="dropdown-divider"></div>
                            <ul class="list-unstyled mb-0" id="header-cart-items-scroll">
                                <li class="dropdown-item">
                                    <div class="text-center py-3">
                                        <i class="fe fe-mail fs-2 text-muted"></i>
                                        <p class="mb-0 text-muted">No messages yet</p>
                                        <small class="text-muted">Messages will appear here when available</small>
                                    </div>
                                </li>
                            </ul>
                            <div class="p-3 empty-header-item border-top">
                                <div class="d-grid">
                                    <a href="{{ route('member.support') }}" class="btn btn-primary">See All Messages</a>
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
                                    <p class="mb-0 fs-17 fw-medium">Quick Access</p>
                                </div>
                            </div>
                            <div class="dropdown-divider mb-0"></div>
                            <div class="main-header-shortcuts p-3" id="header-shortcut-scroll">
                               <div class="row g-2">
                                   <div class="col-4">
                                        <a href="{{ route('member.orders.index') }}" style="text-decoration: none !important; color: inherit !important;">
                                            <div class="text-center p-3 related-app border">
                                                <span class="avatar fs-23 bg-success-transparent p-2 mb-2">
                                                    <i class='bx bx-shopping-bag' style="color: #28a745 !important; font-style: normal !important;"></i>
                                                </span>
                                                <span class="d-block fs-13 fw-normal text-dark">My Orders</span>
                                            </div>
                                        </a>
                                   </div>
                                   <div class="col-4">
                                        <a href="{{ route('member.profile') }}" style="text-decoration: none !important; color: inherit !important;">
                                            <div class="text-center p-3 related-app border">
                                                <span class="avatar fs-23 bg-info-transparent p-2 mb-2">
                                                    <i class='bx bx-user' style="color: #17a2b8 !important; font-style: normal !important;"></i>
                                                </span>
                                                <span class="d-block fs-13 fw-normal text-dark">Profile</span>
                                            </div>
                                        </a>
                                   </div>
                                   <div class="col-4">
                                        <a href="{{ route('member.wallet') }}" style="text-decoration: none !important; color: inherit !important;">
                                            <div class="text-center p-3 related-app border">
                                                <span class="avatar bg-warning-transparent fs-23 p-2 mb-2">
                                                    <i class='bx bx-wallet' style="color: #ffc107 !important; font-style: normal !important;"></i>
                                                </span>
                                                <span class="d-block fs-13 fw-normal text-dark">Wallet</span>
                                            </div>
                                        </a>
                                   </div>
                                   <div class="col-4">
                                        <a href="{{ route('member.support') }}" style="text-decoration: none !important; color: inherit !important;">
                                            <div class="text-center p-3 related-app border">
                                                <span class="avatar bg-primary-transparent fs-23 p-2 mb-2">
                                                    <i class='bx bx-chat' style="color: #007bff !important; font-style: normal !important;"></i>
                                                </span>
                                                <span class="d-block fs-13 fw-normal text-dark">Support</span>
                                            </div>
                                        </a>
                                   </div>
                                   <div class="col-4">
                                        <a href="{{ route('member.genealogy') }}" style="text-decoration: none !important; color: inherit !important;">
                                            <div class="text-center p-3 related-app border">
                                                <span class="avatar bg-dark-transparent fs-23 p-2 mb-2">
                                                    <i class='bx bx-group' style="color: #343a40 !important; font-style: normal !important;"></i>
                                                </span>
                                                <span class="d-block fs-13 fw-normal text-dark">My Team</span>
                                            </div>
                                        </a>
                                   </div>
                                   <div class="col-4">
                                        <a href="{{ route('member.notifications.index') }}" style="text-decoration: none !important; color: inherit !important;">
                                            <div class="text-center p-3 related-app border">
                                                <span class="avatar bg-danger-transparent fs-23 p-2 mb-2">
                                                    <i class='bx bx-bell' style="color: #dc3545 !important; font-style: normal !important;"></i>
                                                </span>
                                                <span class="d-block fs-13 fw-normal text-dark">Notifications</span>
                                            </div>
                                        </a>
                                   </div>
                               </div>
                            </div>
                        </div>
                        <!-- End::main-header-dropdown -->
                    </div>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <div class="header-element header-fullscreen">
                        <!-- Start::header-link -->
                        <a href="javascript:void(0);" class="header-link" id="fullscreen-toggle">
                            <i class="fe fe-maximize full-screen-open header-link-icon"></i>
                            <i class="fe fe-minimize full-screen-close header-link-icon d-none"></i>
                        </a>
                        <!-- End::header-link -->
                    </div>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <div class="header-element notifications-dropdown">
                        <!-- Start::header-link|notification-rights -->
                        <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" id="notificationsDropdown" aria-expanded="false">
                            <i class="fe fe-bell header-link-icon"></i>
                            <span class="badge bg-danger rounded-pill header-icon-badge pulse-animation" id="notification-count">0</span>
                        </a>
                        <!-- End::header-link|notification-rights -->
                        <!-- Start::main-header-dropdown -->
                        <div class="main-header-dropdown dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" style="width: 420px;">
                            <div class="p-3 border-bottom">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="mb-0 fs-17 fw-medium">Notifications</p>
                                    <div>
                                        <span class="badge bg-primary-transparent" id="unread-count">0 New</span>
                                        <button class="btn btn-sm btn-light ms-2" onclick="markAllNotificationsRead()" title="Mark all as read">
                                            <i class="fe fe-check-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Notification Filters -->
                            <div class="p-2 border-bottom">
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="notificationFilter" id="filter-all" autocomplete="off" checked>
                                    <label class="btn btn-outline-primary btn-sm" for="filter-all">All</label>
                                    
                                    <input type="radio" class="btn-check" name="notificationFilter" id="filter-unread" autocomplete="off">
                                    <label class="btn btn-outline-primary btn-sm" for="filter-unread">Unread</label>
                                    
                                    <input type="radio" class="btn-check" name="notificationFilter" id="filter-important" autocomplete="off">
                                    <label class="btn btn-outline-primary btn-sm" for="filter-important">Important</label>
                                </div>
                            </div>

                            <!-- Notifications List -->
                            <ul class="list-unstyled mb-0" id="notifications-list" style="max-height: 450px; overflow-y: auto;">
                                <li class="p-4 text-center" id="no-notifications">
                                    <span class="avatar avatar-xl avatar-rounded bg-info-transparent">
                                        <i class="fe fe-bell fs-2"></i>
                                    </span>
                                    <h6 class="fw-bold mb-1 mt-3">No Notifications</h6>
                                    <span class="mb-3 fw-normal fs-13 d-block">All notifications will appear here</span>
                                </li>
                            </ul>
                            
                            <!-- Footer Actions -->
                            <div class="p-2 border-top bg-light">
                                <div class="d-grid">
                                    <a href="{{ route('member.notifications.index') }}" class="btn btn-light btn-sm">View All Notifications</a>
                                </div>
                            </div>
                        </div>
                        <!-- End::main-header-dropdown -->
                    </div>
                    <!-- End::header-element -->

                    <!-- Start::header-element - Theme Toggle -->
                    <div class="header-element">
                        <a href="javascript:void(0);" class="header-link" id="themeToggle" 
                           onclick="toggleTheme()" title="Toggle Theme">
                            <i class="bx bx-moon header-link-icon" id="themeIcon"></i>
                        </a>
                    </div>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <div class="header-element main-header-profile">
                        <!-- Start::header-link|dropdown-toggle -->
                        <a href="javascript:void(0);" class="header-link dropdown-toggle mx-0 w-100" id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <div>
                                @if(auth()->check() && auth()->user())
                                    @php
                                        $avatarUrl = 'admin-assets/images/users/16.jpg'; // Default
                                        if (auth()->user()->avatar && \Storage::disk('public')->exists(auth()->user()->avatar)) {
                                            $avatarUrl = 'storage/' . auth()->user()->avatar;
                                        }
                                    @endphp
                                    <img src="{{ asset($avatarUrl) }}" 
                                         alt="{{ auth()->user()->name ?? 'User' }}" class="rounded-3 avatar avatar-md">
                                @else
                                    <img src="{{ asset('admin-assets/images/users/16.jpg') }}" 
                                         alt="Guest" class="rounded-3 avatar avatar-md">
                                @endif
                            </div>
                        </a>
                        <!-- End::header-link|dropdown-toggle -->
                        <ul class="main-header-dropdown dropdown-menu pt-0 header-profile-dropdown dropdown-menu-end" aria-labelledby="mainHeaderProfile">
                            <li>
                                <div class="p-3 text-center border-bottom">
                                    <a href="{{ route('member.profile') }}" class="text-center fw-semibold">
                                        @if(auth()->check() && auth()->user())
                                            {{ auth()->user()->name ?? (auth()->user()->firstname . ' ' . auth()->user()->lastname) ?? 'User' }}
                                        @else
                                            Guest User
                                        @endif
                                    </a>
                                    <p class="text-center user-semi-title fs-13 mb-0">
                                        @if(auth()->check() && auth()->user())
                                            {{ ucfirst(auth()->user()->role ?? 'member') }} Member
                                        @else
                                            Guest
                                        @endif
                                    </p>
                                    @if(auth()->check() && auth()->user())
                                        @if(auth()->user()->email_verified_at)
                                            <span class="badge bg-success-transparent fs-11">Verified</span>
                                        @else
                                            <span class="badge bg-warning-transparent fs-11">Unverified</span>
                                        @endif
                                    @endif
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('member.profile') }}">
                                    <i class="fe fe-user me-2"></i>My Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('member.support') }}">
                                    <i class="fe fe-mail me-2"></i>Messages 
                                    <span class="badge bg-success-transparent ms-auto">0</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('member.orders.index') }}">
                                    <i class="fe fe-shopping-bag me-2"></i>My Orders
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('member.wallet') }}">
                                    <i class="fe fe-credit-card me-2"></i>Wallet
                                    <span class="badge bg-info-transparent ms-auto">
                                        @if(auth()->check() && auth()->user())
                                            ${{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}
                                        @else
                                            $0.00
                                        @endif
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('member.genealogy') }}">
                                    <i class="fe fe-users me-2"></i>My Team
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('member.profile') }}">
                                    <i class="fe fe-settings me-2"></i>Settings
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                    <i class="fe fe-edit-3 me-2"></i>Change Password
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('member.support') }}">
                                    <i class="fe fe-headphones me-2"></i>Support
                                </a>
                            </li>
                            <li>
                                @php
                                    // Logout route logic for customer, affiliate, vendor roles
                                    $logoutRoute = 'logout'; // Default logout route
                                    $logoutAction = route('logout'); // Default action
                                    
                                    if (auth()->check() && auth()->user()) {
                                        $userRole = auth()->user()->role;
                                        try {
                                            // Handle role-specific logout routes
                                            if ($userRole === 'affiliate' && Route::has('affiliate.logout')) {
                                                $logoutRoute = 'affiliate.logout';
                                                $logoutAction = route('affiliate.logout');
                                            } elseif ($userRole === 'vendor' && Route::has('vendor.logout.vendor')) {
                                                $logoutRoute = 'vendor.logout.vendor';
                                                $logoutAction = route('vendor.logout.vendor');
                                            } else {
                                                // For customers and other roles, use default logout
                                                $logoutAction = route('logout');
                                            }
                                        } catch (\Exception $e) {
                                            // Fallback to default logout if route doesn't exist
                                            $logoutAction = route('logout');
                                        }
                                    }
                                @endphp
                                <form method="POST" action="{{ $logoutAction }}" class="d-inline" id="header-logout-form">
                                    @csrf
                                    <button type="button" class="dropdown-item d-flex align-items-center border-0 bg-transparent w-100" onclick="confirmLogout()">
                                        <i class="fe fe-power me-2"></i>Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>  
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <div class="header-element" style="display: none;">
                        <!-- Start::header-link|switcher-icon -->
                        <a href="javascript:void(0);" class="header-link switcher-icon" data-bs-toggle="offcanvas" data-bs-target="#switcher-canvas">
                            <i class="fe fe-settings header-link-icon"></i>
                        </a>
                        <!-- End::header-link|switcher-icon -->
                    </div>
                    <!-- End::header-element -->

                </div>
                <!-- End::header-content-right -->

            </div>
            <!-- End::main-header-container -->

        </header>
        <!-- /app-header -->

        <!-- Change Password Modal -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('member.profile.password.update') }}" method="POST" id="changePasswordForm">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('current_password')">
                                        <i class="fe fe-eye" id="current_password_toggle_icon"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="current_password_error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('new_password')">
                                        <i class="fe fe-eye" id="new_password_toggle_icon"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="new_password_error"></div>
                                <div class="form-text">
                                    <small>Password must be at least 8 characters long and contain:</small>
                                    <ul class="password-requirements mt-1" style="font-size: 0.75rem; margin-bottom: 0;">
                                        <li id="length-req" class="text-muted">✗ At least 8 characters</li>
                                        <li id="number-req" class="text-muted">✗ One number</li>
                                        <li id="special-req" class="text-muted">✗ One special character</li>
                                        <li id="uppercase-req" class="text-muted">✓ One uppercase letter (optional)</li>
                                        <li id="lowercase-req" class="text-muted">✓ One lowercase letter (optional)</li>
                                    </ul>
                                </div>
                                <!-- Password Strength Bar -->
                                <div class="password-strength mt-2">
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar" id="password-strength-bar" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <small class="text-muted" id="password-strength-text">Enter a password</small>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm_password" name="new_password_confirmation" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('confirm_password')">
                                        <i class="fe fe-eye" id="confirm_password_toggle_icon"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="confirm_password_error"></div>
                                <div class="valid-feedback" id="confirm_password_success" style="display: none;">
                                    ✓ Passwords match
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <style>
        /* Quick Access Icon Color Protection - Maximum Specificity */
        .main-header-dropdown.header-shortcuts-dropdown .related-app a,
        .main-header-dropdown.header-shortcuts-dropdown .related-app a:hover,
        .main-header-dropdown.header-shortcuts-dropdown .related-app a:focus,
        .main-header-dropdown.header-shortcuts-dropdown .related-app a:active,
        .main-header-dropdown.header-shortcuts-dropdown .related-app a:visited {
            text-decoration: none !important;
            color: inherit !important;
        }
        
        .main-header-dropdown.header-shortcuts-dropdown .related-app i.text-success {
            color: #28a745 !important;
        }
        
        .main-header-dropdown.header-shortcuts-dropdown .related-app i.text-info {
            color: #17a2b8 !important;
        }
        
        .main-header-dropdown.header-shortcuts-dropdown .related-app i.text-warning {
            color: #ffc107 !important;
        }
        
        .main-header-dropdown.header-shortcuts-dropdown .related-app i.text-primary {
            color: #007bff !important;
        }
        
        .main-header-dropdown.header-shortcuts-dropdown .related-app i.text-dark {
            color: #343a40 !important;
        }
        
        .main-header-dropdown.header-shortcuts-dropdown .related-app i.text-danger {
            color: #dc3545 !important;
        }
        
        .main-header-dropdown.header-shortcuts-dropdown .related-app .text-dark {
            color: #343a40 !important;
        }
        
        /* Force icon colors with highest specificity */
        .header-shortcuts-dropdown .bx-shopping-bag {
            color: #28a745 !important;
        }
        
        .header-shortcuts-dropdown .bx-user {
            color: #17a2b8 !important;
        }
        
        .header-shortcuts-dropdown .bx-wallet {
            color: #ffc107 !important;
        }
        
        .header-shortcuts-dropdown .bx-chat {
            color: #007bff !important;
        }
        
        .header-shortcuts-dropdown .bx-group {
            color: #343a40 !important;
        }
        
        .header-shortcuts-dropdown .bx-bell {
            color: #dc3545 !important;
        }
        
        /* Nuclear option - target by specific icon classes directly */
        .main-header-shortcuts .bx-shopping-bag,
        .related-app .bx-shopping-bag {
            color: #28a745 !important;
        }
        
        .main-header-shortcuts .bx-user,
        .related-app .bx-user {
            color: #17a2b8 !important;
        }
        
        .main-header-shortcuts .bx-wallet,
        .related-app .bx-wallet {
            color: #ffc107 !important;
        }
        
        .main-header-shortcuts .bx-chat,
        .related-app .bx-chat {
            color: #007bff !important;
        }
        
        .main-header-shortcuts .bx-group,
        .related-app .bx-group {
            color: #343a40 !important;
        }
        
        .main-header-shortcuts .bx-bell,
        .related-app .bx-bell {
            color: #dc3545 !important;
        }

        /* Search Results Dropdown */
        .search-element {
            position: relative;
        }
        
        .search-results-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            z-index: 1000;
            max-height: 450px;
            overflow-y: auto;
            min-width: 350px;
        }
        
        .search-results-content {
            padding: 0.5rem 0;
        }
        
        .search-result-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        
        .search-result-item:hover {
            background-color: #f8f9fa;
            transform: translateX(2px);
        }
        
        .search-result-item:last-child {
            border-bottom: none;
        }
        
        .search-result-category {
            font-weight: 600;
            color: #6c757d;
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 0.75rem 1rem 0.5rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        
        .search-result-footer {
            padding: 0.75rem 1rem;
            border-top: 1px solid #e9ecef;
            background-color: #f8f9fa;
        }
        
        .search-icon {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background-color: rgba(var(--primary-rgb), 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .search-loading, .search-no-results, .search-error {
            padding: 1.5rem 1rem;
        }
        
        .search-action {
            opacity: 0;
            transition: opacity 0.15s ease;
        }
        
        .search-result-item:hover .search-action {
            opacity: 1;
        }

        /* Dark mode support */
        [data-theme-mode="dark"] .search-results-dropdown {
            background: var(--dark-bg, #1f2937);
            border-color: var(--dark-border, rgba(255, 255, 255, 0.1));
            color: var(--dark-text, #f9fafb);
        }
        
        [data-theme-mode="dark"] .search-result-item:hover {
            background-color: var(--dark-hover, rgba(255, 255, 255, 0.05));
        }
        
        [data-theme-mode="dark"] .search-result-category {
            background-color: var(--dark-card-bg, #374151);
            border-color: var(--dark-border, rgba(255, 255, 255, 0.1));
            color: var(--dark-text-secondary, #9ca3af);
        }
        
        [data-theme-mode="dark"] .search-result-footer {
            background-color: var(--dark-card-bg, #374151);
            border-color: var(--dark-border, rgba(255, 255, 255, 0.1));
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .search-results-dropdown {
                min-width: 300px;
                max-width: 95vw;
            }
        }
        
        /* Search input enhancements */
        .header-search:focus {
            border-color: var(--primary-color, #3b82f6);
            box-shadow: 0 0 0 0.2rem rgba(var(--primary-rgb, 59, 130, 246), 0.25);
        }
        
        .search-element .btn {
            border-left: none;
            border-color: #ced4da;
        }
        
        .search-element .btn:hover {
            background-color: var(--primary-color, #3b82f6);
            border-color: var(--primary-color, #3b82f6);
            color: white;
        }
        
        /* Badge animations */
        .bg-dot {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        /* Password Validation Styles */
        .password-requirements li {
            list-style: none;
            margin-bottom: 2px;
            font-size: 0.75rem;
            transition: color 0.3s ease;
        }
        
        .password-requirements li.valid {
            color: #28a745 !important;
        }
        
        .password-requirements li.invalid {
            color: #dc3545 !important;
        }
        
        .password-strength-bar {
            transition: all 0.3s ease;
        }
        
        .password-strength .progress-bar.weak {
            background-color: #dc3545;
        }
        
        .password-strength .progress-bar.fair {
            background-color: #fd7e14;
        }
        
        .password-strength .progress-bar.good {
            background-color: #ffc107;
        }
        
        .password-strength .progress-bar.strong {
            background-color: #28a745;
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .form-control.is-valid {
            border-color: #28a745;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='m2.3 6.73.4-.12L4.1 3.5l1.4 1.17.46-.14L4.46 2.41z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }
        
        .valid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #28a745;
        }
        
        /* Password Toggle Styles */
        .input-group .btn-outline-secondary {
            border-left: none;
            border-color: #ced4da;
        }
        
        .input-group .form-control:focus + .btn-outline-secondary {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .input-group .form-control.is-invalid + .btn-outline-secondary {
            border-color: #dc3545;
        }
        
        .input-group .form-control.is-valid + .btn-outline-secondary {
            border-color: #28a745;
        }
        
        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            border-color: #6c757d;
        }
        </style>

        <script>
        // Search functionality with enhanced features
        let searchTimeout;
        const mainSearchInput = document.getElementById('mainSearchInput');
        const searchResults = document.getElementById('searchResults');
        const searchResultsContent = document.getElementById('searchResultsContent');
        const mobileSearchInput = document.getElementById('mobileSearchInput');
        const mobileSearchResults = document.getElementById('mobileSearchResults');
        const mobileSearchResultsContent = document.getElementById('mobileSearchResultsContent');
        
        // Default popular keywords and quick access items
        const defaultKeywords = [
            { type: 'popular', text: 'Electronics', icon: 'bx-laptop', action: () => window.location.href = '{{ route("member.products.index") }}?category=electronics' },
            { type: 'popular', text: 'Mobile Phones', icon: 'bx-mobile', action: () => window.location.href = '{{ route("member.products.index") }}?search=mobile' },
            { type: 'popular', text: 'Fashion', icon: 'bx-closet', action: () => window.location.href = '{{ route("member.products.index") }}?category=fashion' },
            { type: 'popular', text: 'Home & Garden', icon: 'bx-home', action: () => window.location.href = '{{ route("member.products.index") }}?category=home' },
            { type: 'popular', text: 'Sports & Outdoors', icon: 'bx-football', action: () => window.location.href = '{{ route("member.products.index") }}?category=sports' },
            { type: 'popular', text: 'Health & Beauty', icon: 'bx-heart', action: () => window.location.href = '{{ route("member.products.index") }}?category=health' },
            { type: 'quick', text: 'My Orders', icon: 'bx-shopping-bag', action: () => window.location.href = '{{ route("member.orders.index") }}' },
            { type: 'quick', text: 'My Wallet', icon: 'bx-wallet', action: () => window.location.href = '{{ route("member.wallet") }}' },
            { type: 'quick', text: 'Team Members', icon: 'bx-group', action: () => window.location.href = '{{ route("member.genealogy") }}' },
            { type: 'quick', text: 'Support Center', icon: 'bx-support', action: () => window.location.href = '{{ route("member.support") }}' }
        ];
        
        if (mainSearchInput) {
            mainSearchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                const query = e.target.value.trim();
                
                if (query.length === 0) {
                    showDefaultKeywords();
                } else if (query.length >= 2) {
                    searchTimeout = setTimeout(() => {
                        performLiveSearch(query);
                    }, 300);
                } else {
                    hideSearchResults();
                }
            });
            
            // Hide search results when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.search-element')) {
                    hideSearchResults();
                }
            });
            
            // Prevent form submission when clicking on suggestions
            mainSearchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    hideSearchResults();
                }
            });
        }
        
        // Mobile search functionality
        if (mobileSearchInput) {
            mobileSearchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                const query = e.target.value.trim();
                
                if (query.length === 0) {
                    showMobileDefaultKeywords();
                } else if (query.length >= 2) {
                    searchTimeout = setTimeout(() => {
                        performMobileLiveSearch(query);
                    }, 300);
                } else {
                    hideMobileSearchResults();
                }
            });
            
            // Hide mobile search results when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#mobileSearchInput') && !e.target.closest('#mobileSearchResults')) {
                    hideMobileSearchResults();
                }
            });
            
            // Prevent form submission when clicking on suggestions
            mobileSearchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    hideMobileSearchResults();
                }
            });
        }
        
        function showDefaultKeywords() {
            let html = '';
            
            // Popular Searches Section
            html += '<div class="search-result-category"><i class="bx bx-trending-up me-1"></i>Popular Searches</div>';
            defaultKeywords.filter(item => item.type === 'popular').forEach(keyword => {
                html += `
                    <div class="search-result-item default-keyword" onclick="executeKeywordAction(this)" data-action="${keyword.action.toString()}">
                        <div class="d-flex align-items-center">
                            <div class="search-icon me-3">
                                <i class="bx ${keyword.icon} text-primary"></i>
                            </div>
                            <div class="flex-fill">
                                <h6 class="mb-0 fs-14 fw-medium">${keyword.text}</h6>
                                <small class="text-muted">View ${keyword.text.toLowerCase()} products</small>
                            </div>
                            <div class="search-action">
                                <i class="bx bx-right-arrow-alt text-muted"></i>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            // Quick Access Section
            html += '<div class="search-result-category"><i class="bx bx-zap me-1"></i>Quick Access</div>';
            defaultKeywords.filter(item => item.type === 'quick').forEach(keyword => {
                html += `
                    <div class="search-result-item default-keyword" onclick="executeKeywordAction(this)" data-action="${keyword.action.toString()}">
                        <div class="d-flex align-items-center">
                            <div class="search-icon me-3">
                                <i class="bx ${keyword.icon} text-success"></i>
                            </div>
                            <div class="flex-fill">
                                <h6 class="mb-0 fs-14 fw-medium">${keyword.text}</h6>
                                <small class="text-muted">Go to ${keyword.text.toLowerCase()}</small>
                            </div>
                            <div class="search-action">
                                <i class="bx bx-right-arrow-alt text-muted"></i>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            // Recent Searches (if available in localStorage)
            const recentSearches = getRecentSearches();
            if (recentSearches.length > 0) {
                html += '<div class="search-result-category"><i class="bx bx-history me-1"></i>Recent Searches</div>';
                recentSearches.forEach(search => {
                    html += `
                        <div class="search-result-item recent-search" onclick="performSearchWithQuery('${search}')">
                            <div class="d-flex align-items-center">
                                <div class="search-icon me-3">
                                    <i class="bx bx-time text-info"></i>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="mb-0 fs-14 fw-medium">${search}</h6>
                                    <small class="text-muted">Previous search</small>
                                </div>
                                <div class="search-action">
                                    <i class="bx bx-x text-muted" onclick="removeRecentSearch('${search}', event)"></i>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            
            searchResultsContent.innerHTML = html;
            searchResults.style.display = 'block';
        }
        
        function executeKeywordAction(element) {
            const actionStr = element.getAttribute('data-action');
            try {
                // Execute the action function
                eval('(' + actionStr + ')')();
                hideSearchResults();
            } catch (error) {
                console.error('Error executing keyword action:', error);
            }
        }

        // Recent searches management
        function getRecentSearches() {
            try {
                return JSON.parse(localStorage.getItem('recentSearches') || '[]').slice(0, 5);
            } catch {
                return [];
            }
        }        function saveRecentSearch(query) {
            try {
                let recentSearches = getRecentSearches();
                // Remove if already exists and add to beginning
                recentSearches = recentSearches.filter(search => search !== query);
                recentSearches.unshift(query);
                // Keep only last 5
                recentSearches = recentSearches.slice(0, 5);
                localStorage.setItem('recentSearches', JSON.stringify(recentSearches));
            } catch (error) {
                console.log('Could not save recent search');
            }
        }
        
        function removeRecentSearch(query, event) {
            event.stopPropagation();
            try {
                let recentSearches = getRecentSearches();
                recentSearches = recentSearches.filter(search => search !== query);
                localStorage.setItem('recentSearches', JSON.stringify(recentSearches));
                showDefaultKeywords(); // Refresh the display
            } catch (error) {
                console.log('Could not remove recent search');
            }
        }
        
        function performSearchWithQuery(query) {
            mainSearchInput.value = query;
            saveRecentSearch(query);
            window.location.href = '{{ route("member.products.index") }}?search=' + encodeURIComponent(query);
        }
        
        function performSearch(event) {
            event.preventDefault();
            const query = mainSearchInput.value.trim();
            if (query) {
                saveRecentSearch(query);
                window.location.href = '{{ route("member.products.index") }}?search=' + encodeURIComponent(query);
            }
        }
        
        function performMobileSearch(event) {
            event.preventDefault();
            const query = document.getElementById('mobileSearchInput').value.trim();
            if (query) {
                saveRecentSearch(query);
                window.location.href = '{{ route("member.products.index") }}?search=' + encodeURIComponent(query);
            }
        }
        
        function performLiveSearch(query) {
            // Show loading indicator
            showSearchLoading();
            
            // Real-time search API call
            fetch(`{{ route('member.search.live') }}?q=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideSearchLoading();
                if (data.success) {
                    displaySearchResults(data.results, query);
                } else {
                    showNoResults(query);
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                hideSearchLoading();
                showSearchError();
            });
        }
        
        function displaySearchResults(data, query) {
            let html = '';
            let hasResults = false;
            
            // Top suggestion based on query
            html += `
                <div class="search-result-item search-all-results bg-light" onclick="performSearchWithQuery('${query}')">
                    <div class="d-flex align-items-center">
                        <div class="search-icon me-3">
                            <i class="bx bx-search-alt-2 text-primary"></i>
                        </div>
                        <div class="flex-fill">
                            <h6 class="mb-0 fs-14 fw-medium">Search for "<span class="text-primary">${query}</span>"</h6>
                            <small class="text-muted">Press Enter or click to search all products</small>
                        </div>
                        <div class="search-action">
                            <kbd class="bg-primary text-white px-2 py-1 rounded">Enter</kbd>
                        </div>
                    </div>
                </div>
            `;
            
            // Categories
            if (data.categories && data.categories.length > 0) {
                hasResults = true;
                html += '<div class="search-result-category"><i class="bx bx-category me-1"></i>Categories</div>';
                data.categories.forEach(category => {
                    html += `
                        <div class="search-result-item" onclick="navigateToCategory('${category.slug}')">
                            <div class="d-flex align-items-center">
                                <div class="search-icon me-3">
                                    <i class="bx bx-category-alt text-info"></i>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="mb-0 fs-14 fw-medium">${category.name}</h6>
                                    <small class="text-muted">${category.products_count || 0} products</small>
                                </div>
                                <div class="search-action">
                                    <i class="bx bx-right-arrow-alt text-muted"></i>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            
            // Brands
            if (data.brands && data.brands.length > 0) {
                hasResults = true;
                html += '<div class="search-result-category"><i class="bx bx-bookmark me-1"></i>Brands</div>';
                data.brands.forEach(brand => {
                    html += `
                        <div class="search-result-item" onclick="navigateToBrand('${brand.slug}')">
                            <div class="d-flex align-items-center">
                                <div class="search-icon me-3">
                                    <i class="bx bx-bookmark-alt text-warning"></i>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="mb-0 fs-14 fw-medium">${brand.name}</h6>
                                    <small class="text-muted">${brand.products_count || 0} products</small>
                                </div>
                                <div class="search-action">
                                    <i class="bx bx-right-arrow-alt text-muted"></i>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            
            // Products (limited to top 5)
            if (data.products && data.products.length > 0) {
                hasResults = true;
                html += '<div class="search-result-category"><i class="bx bx-package me-1"></i>Products</div>';
                data.products.slice(0, 5).forEach(product => {
                    const imageUrl = product.image || '{{ asset("admin-assets/images/default-product.png") }}';
                    const price = product.price ? `৳${parseFloat(product.price).toLocaleString()}` : 'Price on request';
                    
                    html += `
                        <div class="search-result-item" onclick="navigateToProduct(${product.id})">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <img src="${imageUrl}" alt="${product.name}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;" 
                                         onerror="this.src='{{ asset("admin-assets/images/default-product.png") }}'">
                                </div>
                                <div class="flex-fill">
                                    <h6 class="mb-0 fs-14 fw-medium text-truncate">${product.name}</h6>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-muted">${product.category_name || 'Product'}</small>
                                        <span class="badge bg-success-transparent">${price}</span>
                                    </div>
                                </div>
                                <div class="search-action ms-2">
                                    <i class="bx bx-right-arrow-alt text-muted"></i>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                if (data.products.length > 5) {
                    html += `
                        <div class="search-result-item view-more" onclick="performSearchWithQuery('${query}')">
                            <div class="text-center py-2">
                                <small class="text-primary fw-medium">
                                    <i class="bx bx-plus me-1"></i>View ${data.products.length - 5} more products
                                </small>
                            </div>
                        </div>
                    `;
                }
            }
            
            if (!hasResults) {
                html += showNoResults(query);
            }
            
            searchResults.querySelector('.search-results-content').innerHTML = html;
            searchResults.style.display = 'block';
        }
        
        // Navigation functions
        function navigateToCategory(slug) {
            hideSearchResults();
            saveRecentSearch(`category:${slug}`);
            window.location.href = `{{ route('member.products.index') }}?category=${slug}`;
        }
        
        function navigateToBrand(slug) {
            hideSearchResults();
            saveRecentSearch(`brand:${slug}`);
            window.location.href = `{{ route('member.products.index') }}?brand=${slug}`;
        }
        
        function navigateToProduct(id) {
            hideSearchResults();
            saveRecentSearch(`product:${id}`);
            window.location.href = `/member/products/${id}`;
        }
        
        function performSearchWithQuery(query) {
            hideSearchResults();
            saveRecentSearch(query);
            window.location.href = `{{ route('member.products.index') }}?search=${encodeURIComponent(query)}`;
        }
        
        function showSearchLoading() {
            const html = `
                <div class="search-loading text-center p-3">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                    <span class="text-muted">Searching...</span>
                </div>
            `;
            searchResults.querySelector('.search-results-content').innerHTML = html;
            searchResults.style.display = 'block';
        }
        
        function hideSearchLoading() {
            // Loading will be replaced by results or no results message
        }
        
        function showNoResults(query = '') {
            return `
                <div class="search-no-results text-center p-4">
                    <i class="bx bx-search-alt-2 fs-2 text-muted mb-2"></i>
                    <p class="mb-2 fw-semibold">No results found${query ? ` for "${query}"` : ''}</p>
                    <small class="text-muted">Try searching for categories, brands, or product names</small>
                    ${query ? `
                        <div class="mt-3">
                            <button class="btn btn-outline-primary btn-sm" onclick="performSearchWithQuery('${query}')">
                                <i class="bx bx-search me-1"></i>Search all products
                            </button>
                        </div>
                    ` : ''}
                </div>
            `;
        }
        
        function showSearchError() {
            const html = `
                <div class="search-error text-center p-3">
                    <i class="bx bx-error-circle text-danger me-1"></i>
                    <span class="text-muted">Search temporarily unavailable</span>
                </div>
            `;
            searchResults.querySelector('.search-results-content').innerHTML = html;
            searchResults.style.display = 'block';
        }
        
        function hideSearchResults() {
            if (searchResults) {
                searchResults.style.display = 'none';
            }
        }
        
        // Mobile search functions
        function showMobileDefaultKeywords() {
            let html = '';
            
            // Popular Searches Section
            html += '<div class="search-result-category"><i class="bx bx-trending-up me-1"></i>Popular Searches</div>';
            defaultKeywords.filter(item => item.type === 'popular').forEach(keyword => {
                html += `
                    <div class="search-result-item default-keyword" onclick="executeMobileKeywordAction(this)" data-action="${keyword.action.toString()}">
                        <div class="d-flex align-items-center">
                            <div class="search-icon me-3">
                                <i class="${keyword.icon} text-primary"></i>
                            </div>
                            <div class="flex-fill">
                                <h6 class="mb-0 fs-14 fw-medium">${keyword.text}</h6>
                                <small class="text-muted">Popular search</small>
                            </div>
                            <div class="search-action">
                                <i class="bx bx-right-arrow-alt text-muted"></i>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            // Quick Access Section
            html += '<div class="search-result-category"><i class="bx bx-zap me-1"></i>Quick Access</div>';
            defaultKeywords.filter(item => item.type === 'quick').forEach(keyword => {
                html += `
                    <div class="search-result-item default-keyword" onclick="executeMobileKeywordAction(this)" data-action="${keyword.action.toString()}">
                        <div class="d-flex align-items-center">
                            <div class="search-icon me-3">
                                <i class="${keyword.icon} text-success"></i>
                            </div>
                            <div class="flex-fill">
                                <h6 class="mb-0 fs-14 fw-medium">${keyword.text}</h6>
                                <small class="text-muted">Quick access</small>
                            </div>
                            <div class="search-action">
                                <i class="bx bx-right-arrow-alt text-muted"></i>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            // Recent Searches (if available in localStorage)
            const recentSearches = getRecentSearches();
            if (recentSearches.length > 0) {
                html += '<div class="search-result-category"><i class="bx bx-history me-1"></i>Recent Searches</div>';
                recentSearches.forEach(search => {
                    html += `
                        <div class="search-result-item recent-search" onclick="performMobileSearchWithQuery('${search}')">
                            <div class="d-flex align-items-center">
                                <div class="search-icon me-3">
                                    <i class="bx bx-history text-secondary"></i>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="mb-0 fs-14 fw-medium">${search}</h6>
                                    <small class="text-muted">Recent search</small>
                                </div>
                                <div class="search-action d-flex">
                                    <button class="btn btn-sm btn-ghost-secondary me-1" onclick="removeMobileRecentSearch('${search}', event)" title="Remove">
                                        <i class="bx bx-x fs-12"></i>
                                    </button>
                                    <i class="bx bx-right-arrow-alt text-muted"></i>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            
            if (mobileSearchResultsContent) {
                mobileSearchResultsContent.innerHTML = html;
                mobileSearchResults.style.display = 'block';
            }
        }
        
        function executeMobileKeywordAction(element) {
            const actionStr = element.getAttribute('data-action');
            try {
                // Execute the action function
                eval('(' + actionStr + ')')();
                hideMobileSearchResults();
            } catch (error) {
                console.error('Error executing mobile keyword action:', error);
            }
        }
        
        function performMobileLiveSearch(query) {
            // Show loading indicator
            showMobileSearchLoading();
            
            // Real-time search API call
            fetch(`{{ route('member.search.live') }}?q=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideMobileSearchLoading();
                if (data.success) {
                    displayMobileSearchResults(data.results, query);
                } else {
                    showMobileNoResults(query);
                }
            })
            .catch(error => {
                console.error('Mobile search error:', error);
                hideMobileSearchLoading();
                showMobileSearchError();
            });
        }
        
        function displayMobileSearchResults(data, query) {
            let html = '';
            let hasResults = false;
            
            // Top suggestion based on query
            html += `
                <div class="search-result-item search-all-results bg-light" onclick="performMobileSearchWithQuery('${query}')">
                    <div class="d-flex align-items-center">
                        <div class="search-icon me-3">
                            <i class="bx bx-search-alt-2 text-primary"></i>
                        </div>
                        <div class="flex-fill">
                            <h6 class="mb-0 fs-14 fw-medium">Search for "<span class="text-primary">${query}</span>"</h6>
                            <small class="text-muted">Press Enter or click to search all products</small>
                        </div>
                        <div class="search-action">
                            <kbd class="bg-primary text-white px-2 py-1 rounded">Enter</kbd>
                        </div>
                    </div>
                </div>
            `;
            
            // Categories
            if (data.categories && data.categories.length > 0) {
                hasResults = true;
                html += '<div class="search-result-category"><i class="bx bx-category me-1"></i>Categories</div>';
                data.categories.forEach(category => {
                    html += `
                        <div class="search-result-item" onclick="navigateToCategory('${category.slug}')">
                            <div class="d-flex align-items-center">
                                <div class="search-icon me-3">
                                    <i class="bx bx-category-alt text-info"></i>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="mb-0 fs-14 fw-medium">${category.name}</h6>
                                    <small class="text-muted">${category.products_count || 0} products</small>
                                </div>
                                <div class="search-action">
                                    <i class="bx bx-right-arrow-alt text-muted"></i>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            
            // Brands
            if (data.brands && data.brands.length > 0) {
                hasResults = true;
                html += '<div class="search-result-category"><i class="bx bx-bookmark me-1"></i>Brands</div>';
                data.brands.forEach(brand => {
                    html += `
                        <div class="search-result-item" onclick="navigateToBrand('${brand.slug}')">
                            <div class="d-flex align-items-center">
                                <div class="search-icon me-3">
                                    <i class="bx bx-bookmark-alt text-warning"></i>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="mb-0 fs-14 fw-medium">${brand.name}</h6>
                                    <small class="text-muted">${brand.products_count || 0} products</small>
                                </div>
                                <div class="search-action">
                                    <i class="bx bx-right-arrow-alt text-muted"></i>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            
            // Products (limited to top 3 for mobile)
            if (data.products && data.products.length > 0) {
                hasResults = true;
                html += '<div class="search-result-category"><i class="bx bx-package me-1"></i>Products</div>';
                data.products.slice(0, 3).forEach(product => {
                    const imageUrl = product.image || '{{ asset("admin-assets/images/default-product.png") }}';
                    const price = product.price ? `৳${parseFloat(product.price).toLocaleString()}` : 'Price on request';
                    
                    html += `
                        <div class="search-result-item" onclick="navigateToProduct(${product.id})">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <img src="${imageUrl}" alt="${product.name}" class="rounded" style="width: 36px; height: 36px; object-fit: cover;" 
                                         onerror="this.src='{{ asset("admin-assets/images/default-product.png") }}'">
                                </div>
                                <div class="flex-fill">
                                    <h6 class="mb-0 fs-14 fw-medium text-truncate">${product.name}</h6>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-muted">${product.category_name || 'Product'}</small>
                                        <span class="badge bg-success-transparent">${price}</span>
                                    </div>
                                </div>
                                <div class="search-action ms-2">
                                    <i class="bx bx-right-arrow-alt text-muted"></i>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                if (data.products.length > 3) {
                    html += `
                        <div class="search-result-item view-more" onclick="performMobileSearchWithQuery('${query}')">
                            <div class="text-center py-2">
                                <small class="text-primary fw-medium">
                                    <i class="bx bx-plus me-1"></i>View ${data.products.length - 3} more products
                                </small>
                            </div>
                        </div>
                    `;
                }
            }
            
            if (!hasResults) {
                html += showMobileNoResults(query);
            }
            
            if (mobileSearchResultsContent) {
                mobileSearchResultsContent.innerHTML = html;
                mobileSearchResults.style.display = 'block';
            }
        }
        
        function performMobileSearchWithQuery(query) {
            if (mobileSearchInput) {
                mobileSearchInput.value = query;
            }
            hideMobileSearchResults();
            saveRecentSearch(query);
            window.location.href = '{{ route("member.products.index") }}?search=' + encodeURIComponent(query);
        }
        
        function removeMobileRecentSearch(query, event) {
            event.stopPropagation();
            try {
                let recentSearches = getRecentSearches();
                recentSearches = recentSearches.filter(search => search !== query);
                localStorage.setItem('recentSearches', JSON.stringify(recentSearches));
                showMobileDefaultKeywords(); // Refresh the display
            } catch (error) {
                console.log('Could not remove mobile recent search');
            }
        }
        
        function showMobileSearchLoading() {
            const html = `
                <div class="search-loading text-center p-3">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                    <span class="text-muted">Searching...</span>
                </div>
            `;
            if (mobileSearchResultsContent) {
                mobileSearchResultsContent.innerHTML = html;
                mobileSearchResults.style.display = 'block';
            }
        }
        
        function hideMobileSearchLoading() {
            // Loading will be replaced by results or no results message
        }
        
        function showMobileNoResults(query = '') {
            return `
                <div class="search-no-results text-center p-4">
                    <i class="bx bx-search-alt-2 fs-2 text-muted mb-2"></i>
                    <p class="mb-2 fw-semibold">No results found${query ? ` for "${query}"` : ''}</p>
                    <small class="text-muted">Try searching for categories, brands, or product names</small>
                    ${query ? `
                        <div class="mt-3">
                            <button class="btn btn-outline-primary btn-sm" onclick="performMobileSearchWithQuery('${query}')">
                                <i class="bx bx-search me-1"></i>Search all products
                            </button>
                        </div>
                    ` : ''}
                </div>
            `;
        }
        
        function showMobileSearchError() {
            const html = `
                <div class="search-error text-center p-3">
                    <i class="bx bx-error-circle text-danger me-1"></i>
                    <span class="text-muted">Search temporarily unavailable</span>
                </div>
            `;
            if (mobileSearchResultsContent) {
                mobileSearchResultsContent.innerHTML = html;
                mobileSearchResults.style.display = 'block';
            }
        }
        
        function hideMobileSearchResults() {
            if (mobileSearchResults) {
                mobileSearchResults.style.display = 'none';
            }
        }
        
        // Notification functions
        function markNotificationAsRead(notificationId) {
            fetch(`{{ url('member/notifications') }}/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the UI - remove unread styling, update counters
                    const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
                    if (notificationItem) {
                        notificationItem.classList.remove('unread');
                        const newBadge = notificationItem.querySelector('.badge.bg-primary');
                        if (newBadge) newBadge.remove();
                    }
                    
                    // Update notification counts if function exists
                    if (typeof updateNotificationUI === 'function') {
                        updateNotificationUI();
                    }
                }
            })
            .catch(error => {
                console.error('Failed to mark notification as read:', error);
            });
        }

        function markAllNotificationsRead() {
            fetch('{{ route('member.notifications.mark-all-read') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update all notification items
                    document.querySelectorAll('#notifications-list .notification-item').forEach(item => {
                        item.classList.remove('unread');
                        const newBadge = item.querySelector('.badge.bg-primary');
                        if (newBadge) newBadge.remove();
                    });
                    
                    // Update badge and counts
                    const badge = document.getElementById('notification-count');
                    const unreadCount = document.getElementById('unread-count');
                    
                    if (badge) {
                        badge.style.display = 'none';
                        badge.textContent = '0';
                    }
                    if (unreadCount) {
                        unreadCount.textContent = '0 New';
                    }
                    
                    // Show success message
                    if (typeof showToast === 'function') {
                        showToast(data.message || 'All notifications marked as read', 'success');
                    }
                }
            })
            .catch(error => {
                console.error('Failed to mark all notifications as read:', error);
            });
        }

        function filterNotifications(filter) {
            const notifications = document.querySelectorAll('#notifications-list .notification-item');
            let visibleCount = 0;

            notifications.forEach(notification => {
                const isUnread = notification.classList.contains('unread');
                const isImportant = notification.querySelector('.fe-star') !== null;
                let shouldShow = false;

                switch(filter) {
                    case 'all':
                        shouldShow = true;
                        break;
                    case 'unread':
                        shouldShow = isUnread;
                        break;
                    case 'important':
                        shouldShow = isImportant;
                        break;
                }

                if (shouldShow) {
                    notification.style.display = 'block';
                    visibleCount++;
                } else {
                    notification.style.display = 'none';
                }
            });

            // Show/hide no notifications message
            const noNotifications = document.getElementById('no-notifications');
            if (visibleCount === 0 && noNotifications) {
                noNotifications.style.display = 'block';
            } else if (noNotifications) {
                noNotifications.style.display = 'none';
            }
        }

        // Setup notification filter buttons
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('input[name="notificationFilter"]');
            filterButtons.forEach(button => {
                button.addEventListener('change', function() {
                    if (this.checked) {
                        filterNotifications(this.id.replace('filter-', ''));
                    }
                });
            });
        });
        
        function markMessageAsRead(messageId) {
            // For now, just redirect to support since message endpoints don't exist
            window.location.href = '{{ route("member.support") }}';
        }
        
        // Emergency logout function that always works
        function emergencyLogout() {
            console.log('Emergency logout triggered');
            
            // Clear all browser storage
            try {
                localStorage.clear();
                sessionStorage.clear();
            } catch (e) {
                console.log('Storage clear error:', e);
            }
            
            // Direct redirect to logout
            window.location.replace('{{ route("emergency.logout") }}');
        }
        
        // Logout confirmation with vendor system pattern
        function confirmLogout() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will be logged out of your account.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, logout',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading with timeout to prevent hanging
                        Swal.fire({
                            title: 'Logging out...',
                            text: 'Please wait while we log you out safely.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            timer: 10000, // Max 10 seconds
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Enhanced logout with error handling
                        const form = document.getElementById('header-logout-form');
                        if (form) {
                            // Try AJAX logout first for better control
                            const formData = new FormData(form);
                            const xhr = new XMLHttpRequest();
                            
                            xhr.timeout = 8000; // 8 second timeout
                            xhr.onload = function() {
                                if (xhr.status === 200 || xhr.status === 302) {
                                    // Success - redirect to login
                                    @if(auth()->check() && auth()->user()->role === 'affiliate')
                                        window.location.replace('{{ route("affiliate.login") }}');
                                    @else
                                        window.location.replace('{{ route("login") }}');
                                    @endif
                                } else {
                                    // Error - still redirect but show message
                                    console.log('Logout response error:', xhr.status);
                                    @if(auth()->check() && auth()->user()->role === 'affiliate')
                                        window.location.replace('{{ route("affiliate.login") }}');
                                    @else
                                        window.location.replace('{{ route("login") }}');
                                    @endif
                                }
                            };
                            
                            xhr.ontimeout = function() {
                                console.log('Logout timeout - redirecting anyway');
                                @if(auth()->check() && auth()->user()->role === 'affiliate')
                                    window.location.replace('{{ route("affiliate.login") }}');
                                @else
                                    window.location.replace('{{ route("login") }}');
                                @endif
                            };
                            
                            xhr.onerror = function() {
                                console.log('Logout network error - redirecting anyway');
                                @if(auth()->check() && auth()->user()->role === 'affiliate')
                                    window.location.replace('{{ route("affiliate.login") }}');
                                @else
                                    window.location.replace('{{ route("login") }}');
                                @endif
                            };
                            
                            xhr.open('POST', form.action, true);
                            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                            xhr.send(formData);
                            
                        } else {
                            // Direct logout URL as fallback
                            @if(auth()->check() && auth()->user()->role === 'affiliate')
                                window.location.replace('{{ route("affiliate.login") }}');
                            @else
                                window.location.replace('{{ route("login") }}');
                            @endif
                        }
                    }
                });
            } else {
                // Fallback without SweetAlert
                if (confirm('Are you sure you want to logout?')) {
                    const form = document.getElementById('header-logout-form');
                    if (form) {
                        form.submit();
                    } else {
                        @if(auth()->check() && auth()->user()->role === 'affiliate')
                            window.location.href = '{{ route("affiliate.logout") }}';
                        @else
                            window.location.href = '{{ route("logout") }}';
                        @endif
                    }
                }
            }
        }
        
        // Add the vendor logout system pattern for fallback
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
                            
                            @if(Auth::check() && Auth::user()->role === 'affiliate')
                            fetch('{{ route("affiliate.logout") }}', {
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
                                                window.location.href = data.redirect || '{{ route("affiliate.login") }}';
                                            });
                                        } else {
                                            window.location.href = data.redirect || '{{ route("affiliate.login") }}';
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
                            @elseif(Auth::check() && Auth::user()->role === 'vendor')
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
                            // Fallback for non-vendor/affiliate users
                            setTimeout(() => {
                                window.location.href = '/login';
                            }, 1000);
                            @endif
                        }
                    });
                });
            }
        });
        
        // Real-time updates (simplified since API endpoints don't exist yet)
        function updateHeaderCounts() {
            // For now, this is disabled since the endpoints don't exist
            // This can be enhanced later when notification/message API endpoints are created
            console.log('Header counts update skipped - endpoints not available');
        }
        
        // Update counts every 30 seconds
        setInterval(updateHeaderCounts, 30000);
        
        // Password change form handling
        document.getElementById('changePasswordForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Final validation check before submit
            if (!validatePasswordForm()) {
                return;
            }
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            // Disable submit button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Updating...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal and show success message
                    const modal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
                    modal.hide();
                    
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Success!', data.message, 'success');
                    } else {
                        alert(data.message);
                    }
                    
                    // Reset form
                    this.reset();
                    resetPasswordValidation();
                } else {
                    // Show errors
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error!', data.message || 'Something went wrong', 'error');
                    } else {
                        alert(data.message || 'Something went wrong');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                } else {
                    alert('Something went wrong. Please try again.');
                }
            })
            .finally(() => {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });

        // Real-time password validation
        document.addEventListener('DOMContentLoaded', function() {
            const currentPasswordInput = document.getElementById('current_password');
            const newPasswordInput = document.getElementById('new_password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const modal = document.getElementById('changePasswordModal');
            
            // Reset validation when modal is opened
            if (modal) {
                modal.addEventListener('shown.bs.modal', function() {
                    resetPasswordValidation();
                });
            }
            
            if (currentPasswordInput) {
                currentPasswordInput.addEventListener('input', validateCurrentPassword);
                currentPasswordInput.addEventListener('blur', validateCurrentPassword);
            }
            
            if (newPasswordInput) {
                newPasswordInput.addEventListener('input', validateNewPassword);
                newPasswordInput.addEventListener('keyup', validateNewPassword);
            }
            
            if (confirmPasswordInput) {
                confirmPasswordInput.addEventListener('input', validateConfirmPassword);
                confirmPasswordInput.addEventListener('keyup', validateConfirmPassword);
            }
        });

        function validateCurrentPassword() {
            const input = document.getElementById('current_password');
            const errorDiv = document.getElementById('current_password_error');
            
            if (input.value.length === 0) {
                setFieldInvalid(input, errorDiv, 'Current password is required');
                return false;
            } else if (input.value.length < 6) {
                setFieldInvalid(input, errorDiv, 'Current password seems too short');
                return false;
            } else {
                setFieldValid(input, errorDiv);
                return true;
            }
        }

        function validateNewPassword() {
            const input = document.getElementById('new_password');
            const errorDiv = document.getElementById('new_password_error');
            const password = input.value;
            
            // Password requirements
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /\d/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };
            
            // Update requirement indicators (uppercase and lowercase are optional)
            updateRequirement('length-req', requirements.length);
            updateRequirement('number-req', requirements.number);
            updateRequirement('special-req', requirements.special);
            updateOptionalRequirement('uppercase-req', requirements.uppercase);
            updateOptionalRequirement('lowercase-req', requirements.lowercase);
            
            // Calculate password strength
            const strength = calculatePasswordStrength(password, requirements);
            updatePasswordStrength(strength);
            
            // Validate password (only length, number, and special character are required)
            if (password.length === 0) {
                setFieldInvalid(input, errorDiv, 'New password is required');
                return false;
            } else if (password.length < 8) {
                setFieldInvalid(input, errorDiv, 'Password must be at least 8 characters long');
                return false;
            } else if (!requirements.number || !requirements.special) {
                setFieldInvalid(input, errorDiv, 'Password must contain at least one number and one special character');
                return false;
            } else {
                setFieldValid(input, errorDiv);
                // Re-validate confirm password if it has a value
                if (document.getElementById('confirm_password').value) {
                    validateConfirmPassword();
                }
                return true;
            }
        }

        function validateConfirmPassword() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const input = document.getElementById('confirm_password');
            const errorDiv = document.getElementById('confirm_password_error');
            const successDiv = document.getElementById('confirm_password_success');
            
            if (confirmPassword.length === 0) {
                setFieldInvalid(input, errorDiv, 'Please confirm your new password');
                successDiv.style.display = 'none';
                return false;
            } else if (newPassword !== confirmPassword) {
                setFieldInvalid(input, errorDiv, 'Passwords do not match');
                successDiv.style.display = 'none';
                return false;
            } else {
                setFieldValid(input, errorDiv);
                successDiv.style.display = 'block';
                return true;
            }
        }

        function updateRequirement(id, isValid) {
            const element = document.getElementById(id);
            if (element) {
                element.className = isValid ? 'text-success valid' : 'text-muted invalid';
                element.innerHTML = (isValid ? '✓ ' : '✗ ') + element.textContent.substring(2);
            }
        }

        function updateOptionalRequirement(id, isValid) {
            const element = document.getElementById(id);
            if (element) {
                element.className = isValid ? 'text-success valid' : 'text-muted';
                // Keep the ✓ for optional requirements as they're always "valid"
                if (!element.textContent.includes('(optional)')) {
                    element.innerHTML = '✓ ' + element.textContent.substring(2);
                }
            }
        }

        function calculatePasswordStrength(password, requirements) {
            if (password.length === 0) return 0;
            
            let score = 0;
            
            // Length score
            if (password.length >= 8) score += 20;
            if (password.length >= 12) score += 10;
            
            // Character type scores
            if (requirements.lowercase) score += 15;
            if (requirements.uppercase) score += 15;
            if (requirements.number) score += 15;
            if (requirements.special) score += 20;
            
            // Bonus for length
            if (password.length > 15) score += 5;
            
            return Math.min(score, 100);
        }

        function updatePasswordStrength(strength) {
            const bar = document.getElementById('password-strength-bar');
            const text = document.getElementById('password-strength-text');
            
            if (!bar || !text) return;
            
            bar.style.width = strength + '%';
            bar.className = 'progress-bar';
            
            if (strength === 0) {
                text.textContent = 'Enter a password';
                bar.classList.add('weak');
            } else if (strength < 40) {
                text.textContent = 'Weak password';
                bar.classList.add('weak');
            } else if (strength < 60) {
                text.textContent = 'Fair password';
                bar.classList.add('fair');
            } else if (strength < 80) {
                text.textContent = 'Good password';
                bar.classList.add('good');
            } else {
                text.textContent = 'Strong password';
                bar.classList.add('strong');
            }
        }

        function setFieldValid(input, errorDiv) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            if (errorDiv) errorDiv.textContent = '';
        }

        function setFieldInvalid(input, errorDiv, message) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            if (errorDiv) errorDiv.textContent = message;
        }

        function validatePasswordForm() {
            const isCurrentValid = validateCurrentPassword();
            const isNewValid = validateNewPassword();
            const isConfirmValid = validateConfirmPassword();
            
            return isCurrentValid && isNewValid && isConfirmValid;
        }

        function resetPasswordValidation() {
            // Reset all form fields and validation states
            const inputs = ['current_password', 'new_password', 'confirm_password'];
            inputs.forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    input.classList.remove('is-valid', 'is-invalid');
                    input.value = '';
                }
            });
            
            // Reset requirement indicators
            const requiredReqs = ['length-req', 'number-req', 'special-req'];
            requiredReqs.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.className = 'text-muted';
                    const text = element.textContent.substring(2);
                    element.innerHTML = '✗ ' + text;
                }
            });
            
            // Reset optional requirements (keep them as valid with checkmark)
            const optionalReqs = ['uppercase-req', 'lowercase-req'];
            optionalReqs.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.className = 'text-muted';
                    const text = element.textContent.substring(2);
                    element.innerHTML = '✓ ' + text;
                }
            });
            
            // Reset password strength
            updatePasswordStrength(0);
            
            // Hide success message
            const successDiv = document.getElementById('confirm_password_success');
            if (successDiv) successDiv.style.display = 'none';
            
            // Clear error messages
            const errorDivs = ['current_password_error', 'new_password_error', 'confirm_password_error'];
            errorDivs.forEach(id => {
                const div = document.getElementById(id);
                if (div) div.textContent = '';
            });
        }
        
        // Password visibility toggle functionality
        function togglePasswordVisibility(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId + '_toggle_icon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.className = 'fe fe-eye-off';
            } else {
                passwordField.type = 'password';
                toggleIcon.className = 'fe fe-eye';
            }
        }
        
        // Theme Toggle Functionality
        function toggleTheme() {
            const body = document.body;
            const themeIcon = document.getElementById('themeIcon');
            
            // Get current theme from both possible attributes
            const currentTheme = body.getAttribute('data-theme') || body.getAttribute('data-theme-mode') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            // Set both attributes to ensure compatibility
            body.setAttribute('data-theme', newTheme);
            body.setAttribute('data-theme-mode', newTheme);
            
            // Update icon
            if (newTheme === 'dark') {
                themeIcon.className = 'bx bx-sun header-link-icon';
            } else {
                themeIcon.className = 'bx bx-moon header-link-icon';
            }
            
            // Save preference
            localStorage.setItem('theme', newTheme);
        }
        
        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            const body = document.body;
            const themeIcon = document.getElementById('themeIcon');
            
            // Set both attributes
            body.setAttribute('data-theme', savedTheme);
            body.setAttribute('data-theme-mode', savedTheme);
            
            // Update icon
            if (savedTheme === 'dark') {
                themeIcon.className = 'bx bx-sun header-link-icon';
            } else {
                themeIcon.className = 'bx bx-moon header-link-icon';
            }
            
            // Initialize fullscreen toggle
            const fullscreenToggle = document.getElementById('fullscreen-toggle');
            if (fullscreenToggle) {
                fullscreenToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (typeof openFullscreen === 'function') {
                        openFullscreen();
                    } else {
                        console.warn('openFullscreen function not available');
                    }
                });
            }
        });
        </script>