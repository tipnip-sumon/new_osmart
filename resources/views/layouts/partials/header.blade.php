<!-- Header Area -->
<div class="header-area" id="headerArea">
    <div class="container h-100 d-flex align-items-center justify-content-between d-flex rtl-flex-d-row-r">
        <!-- Logo Wrapper -->
        <div class="logo-wrapper">
            <a href="{{ route('home') }}">
                <img src="{{ asset('assets/images/logo/osmart-logo.svg') }}" alt="{{ siteName() }}" class="site-logo">
            </a>
        </div>

        <!-- User Info Section (for logged in users) -->
        {{-- @auth
            <div class="user-info-header d-none d-md-flex align-items-center">
                <div class="user-details text-center">
                    <small class="user-greeting text-muted">Welcome back,</small>
                    <div class="user-name fw-bold text-dark">{{ Auth::user()->name }}</div>
                    <div class="user-meta">
                        <small class="text-muted me-2">ID: #{{ str_pad(Auth::user()->id, 6, '0', STR_PAD_LEFT) }}</small>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </div>
                </div>
            </div>
        @endauth --}}

        <div class="navbar-logo-container d-flex align-items-center">
            <!-- Search Form (if needed) -->
            @yield('header-search')

            <!-- Cart Icon -->
            <div class="cart-icon-wrap position-relative me-3">
                <a href="{{ route('cart.index') }}" class="text-decoration-none cart-link">
                    <i class="ti ti-shopping-cart fs-4 text-dark cart-icon"></i>
                    <span class="cart-count-badge position-absolute badge rounded-pill bg-danger text-white header-cart-count"
                            id="cartCountHeader"
                            style="display: none; font-size: 10px; min-width: 18px; height: 18px; line-height: 8px; top: -8px; right: -8px; z-index: 10;">
                        0
                    </span>
                </a>
            </div>

            <!-- Navbar Toggler -->
            <div class="suha-navbar-toggler ms-2" data-bs-toggle="offcanvas" data-bs-target="#suhaOffcanvas" aria-controls="suhaOffcanvas">
                <div><span></span><span></span><span></span></div>
            </div>
        </div>
    </div>
</div>

<style>
.header-area .logo-wrapper .site-logo {
    max-height: 40px;
    height: auto;
    width: auto;
    max-width: 150px;
    object-fit: contain;
}

@media (max-width: 576px) {
    .header-area .logo-wrapper .site-logo {
        max-height: 35px;
        max-width: 120px;
    }
}

.header-area .cart-count-badge {
    font-weight: bold !important;
    border: 2px solid white;
    box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.header-area .cart-icon-wrap:hover i {
    color: #6366f1 !important;
    transition: color 0.3s ease;
}

.header-area .cart-icon-wrap:hover .cart-count-badge {
    background-color: #6366f1 !important;
    transform: scale(1.1);
}

/* Cart icon animation */
.cart-icon.animate__pulse {
    animation: pulse 0.6s ease-in-out;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

/* Cart count badge animation */
.cart-count-badge.animate__bounce {
    animation: bounceIn 0.6s ease-in-out;
}

@keyframes bounceIn {
    0% { transform: scale(0.3); opacity: 0; }
    50% { transform: scale(1.05); }
    70% { transform: scale(0.9); }
    100% { transform: scale(1); opacity: 1; }
}

.user-info-header {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border-radius: 12px;
    padding: 8px 16px;
    border: 1px solid rgba(102, 126, 234, 0.2);
    max-width: 280px;
}

.user-info-header .user-greeting {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #667eea !important;
}

.user-info-header .user-name {
    font-size: 14px;
    color: #1a202c !important;
    margin: 2px 0;
    line-height: 1.2;
}

.user-info-header .user-meta {
    font-size: 10px;
    line-height: 1.1;
}

.user-info-header .user-meta small {
    color: #718096 !important;
}

/* Mobile responsive - hide on small screens */
@media (max-width: 767px) {
    .user-info-header {
        display: none !important;
    }
}

/* Tablet adjustments */
@media (max-width: 991px) {
    .user-info-header {
        max-width: 200px;
        padding: 6px 12px;
    }

    .user-info-header .user-name {
        font-size: 12px;
    }

    .user-info-header .user-meta {
        font-size: 9px;
    }
}
</style>
