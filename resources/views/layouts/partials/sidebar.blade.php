<!-- Sidebar -->
<div class="offcanvas offcanvas-start suha-offcanvas-wrap professional-sidebar" tabindex="-1" id="suhaOffcanvas" aria-labelledby="suhaOffcanvasLabel" style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 50%, #2c3e50 100%) !important; border: none !important;">
    <!-- Close button-->
    <button class="btn-close btn-close-white" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    <!-- Offcanvas body-->
    <div class="offcanvas-body">
        <!-- Sidenav Profile-->
        <div class="sidenav-profile">
            <div class="user-profile">
                @auth
                    <img src="{{ Auth::user()->avatar ?? asset('assets/img/bg-img/9.jpg') }}" alt="{{ Auth::user()->name }}" class="profile-img">
                @else
                    <img src="{{ asset('assets/img/bg-img/9.jpg') }}" alt="Guest" class="profile-img">
                @endauth
            </div>
            <div class="user-info">
                @auth
                    <h5 class="user-name mb-1 text-white">{{ Auth::user()->name }}</h5>
                    @if(Auth::user()->role === 'affiliate')
                        <p class="user-role text-warning"><i class="ti ti-crown me-1"></i>Affiliate Member</p>
                        <p class="available-balance text-white-50">Balance: $<span class="counter">{{ Auth::user()->balance ?? 0 }}</span></p>
                    @else
                        <p class="user-role text-success"><i class="ti ti-shopping-cart me-1"></i>Customer</p>
                        <p class="available-balance text-white-50">ID: #{{ str_pad(Auth::user()->id, 6, '0', STR_PAD_LEFT) }}</p>
                    @endif
                @else
                    <h5 class="user-name mb-1 text-white">Welcome Guest!</h5>
                    <p class="available-balance text-white-50">Please login to access your account</p>
                @endauth
            </div>
        </div>
        
        <!-- Sidenav Nav-->
        <ul class="sidenav-nav ps-0">
            @auth
                <!-- === LOGGED IN USER MENU === -->
                <li class="menu-header">
                    <span class="text-white-50 text-uppercase fw-bold fs-6"><i class="ti ti-user-circle me-2"></i>My Account</span>
                </li>
                
                <!-- User Dashboard Link -->
                @if(Auth::user()->role === 'affiliate')
                    <li><a href="{{ route('member.dashboard') }}" class="menu-item text-warning">
                        <i class="ti ti-dashboard"></i>
                        <span>Affiliate Dashboard</span>
                    </a></li>
                    <li><a href="{{ route('member.genealogy') }}" class="menu-item">
                        <i class="ti ti-network"></i>
                        <span>My Network</span>
                    </a></li>
                    <li><a href="{{ route('member.commissions') }}" class="menu-item">
                        <i class="ti ti-money"></i>
                        <span>Commissions</span>
                    </a></li>
                    <li><a href="{{ route('user.training') }}" class="menu-item">
                        <i class="ti ti-school"></i>
                        <span>Training</span>
                    </a></li>
                @else
                    <li><a href="{{ route('user.dashboard') }}" class="menu-item text-success">
                        <i class="ti ti-user"></i>
                        <span>Dashboard</span>
                    </a></li>
                @endif
                
                <!-- Common User Menu Items -->
                <li><a href="{{ route('user.profile') }}" class="menu-item">
                    <i class="ti ti-user-circle"></i>
                    <span>My Profile</span>
                </a></li>
                <li><a href="{{ route('orders.index') }}" class="menu-item">
                    <i class="ti ti-shopping-bag"></i>
                    <span>My Orders</span>
                </a></li>
                <li><a href="{{ route('wishlist.index') }}" class="menu-item">
                    <i class="ti ti-heart"></i>
                    <span>My Wishlist</span>
                </a></li>
                
                <!-- Notifications -->
                <li><a href="{{ route('notifications.index') }}" class="menu-item">
                    <i class="ti ti-bell-ringing lni-tada-effect"></i>
                    <span>Notifications</span>
                    @php
                        $unreadCount = 0;
                        if (Auth::check()) {
                            $unreadCount = \App\Models\AdminNotification::where(function($query) {
                                    $query->where('user_id', Auth::id())
                                          ->orWhereNull('user_id');
                                })
                                ->whereNull('read_at')
                                ->count();
                        }
                    @endphp
                    @if($unreadCount > 0)
                        <span class="ms-auto badge bg-warning text-dark notification-count">{{ $unreadCount }}</span>
                    @endif
                </a></li>
                
                <li class="menu-divider"></li>
                
                <!-- Shopping Menu -->
                <li class="menu-header">
                    <span class="text-white-50 text-uppercase fw-bold fs-6"><i class="ti ti-shopping-cart me-2"></i>Shopping</span>
                </li>
                
                <li class="suha-dropdown-menu">
                    <a href="#" class="menu-item dropdown-toggle">
                        <i class="ti ti-building-store"></i>
                        <span>Shop Pages</span>
                    </a>
                    <ul class="dropdown-submenu">
                        <li><a href="{{ route('shop.grid') }}">Shop Grid</a></li>
                        <li><a href="{{ route('shop.list') }}">Shop List</a></li>
                        <li><a href="{{ route('products.featured') }}">Featured Products</a></li>
                        <li><a href="{{ route('flash-sale') }}">Flash Sale</a></li>
                    </ul>
                </li>
                
                @if(Auth::user()->role !== 'affiliate')
                    <!-- Affiliate Promotion for Customers -->
                    <li class="affiliate-promotion">
                        <a href="{{ route('affiliate.register') }}" class="menu-item promo-item">
                            <i class="ti ti-crown me-2"></i>
                            <div class="promo-info">
                                <span class="fw-bold">Join as Affiliate</span>
                                <small class="d-block">Earn commissions & build your team</small>
                            </div>
                        </a>
                    </li>
                @endif
                
                <li class="menu-divider"></li>
                
                <!-- Settings & Logout -->
                <li class="menu-header">
                    <span class="text-white-50 text-uppercase fw-bold fs-6"><i class="ti ti-settings me-2"></i>Settings</span>
                </li>
                
                <li><a href="{{ route('settings') }}" class="menu-item">
                    <i class="ti ti-adjustments-horizontal"></i>
                    <span>Account Settings</span>
                </a></li>
                
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;" onsubmit="handleLogoutSubmit(this)">
                        @csrf
                        <button type="submit" class="menu-item logout-btn" style="border: none; background: none; width: 100%; text-align: left;">
                            <i class="ti ti-logout"></i>
                            <span>Sign Out</span>
                        </button>
                    </form>
                </li>
                
            @else
                <!-- === GUEST USER MENU === -->
                <li class="menu-header">
                    <span class="text-white-50 text-uppercase fw-bold fs-6"><i class="ti ti-login me-2"></i>Account Access</span>
                </li>
                
                <!-- Customer Access Menu with Submenu -->
                <li class="suha-dropdown-menu customer-menu">
                    <a href="#" class="menu-item customer-main-menu dropdown-toggle">
                        <i class="ti ti-shopping-cart me-2"></i>
                        <div class="login-info">
                            <span class="fw-bold">Customer Access</span>
                            <small class="d-block">Shop and purchase products</small>
                        </div>
                    </a>
                    <ul class="customer-submenu dropdown-submenu">
                        <li><a href="{{ route('login') }}">
                            <i class="ti ti-login me-2"></i>Customer Login
                        </a></li>
                        <li><a href="{{ route('register') }}">
                            <i class="ti ti-user-plus me-2"></i>Customer Registration
                        </a></li>
                        <li class="submenu-divider"></li>
                        <li><a href="{{ route('shop.grid') }}">
                            <i class="ti ti-building-store me-2"></i>Browse Shop
                        </a></li>
                    </ul>
                </li>
                
                <!-- Affiliate Access Menu with Submenu -->
                <li class="suha-dropdown-menu affiliate-menu">
                    <a href="#" class="menu-item affiliate-main-menu dropdown-toggle">
                        <i class="ti ti-crown me-2"></i>
                        <div class="login-info">
                            <span class="fw-bold">Affiliate Access</span>
                            <small class="d-block">MLM Dashboard & Registration</small>
                        </div>
                    </a>
                    <ul class="affiliate-submenu dropdown-submenu">
                        <li><a href="{{ route('affiliate.login') }}">
                            <i class="ti ti-login me-2"></i>Affiliate Login
                        </a></li>
                        <li><a href="{{ route('affiliate.register') }}">
                            <i class="ti ti-user-plus me-2"></i>Affiliate Registration
                        </a></li>
                        <li class="submenu-divider"></li>
                        <li><a href="{{ route('affiliate.info') ?? '#' }}">
                            <i class="ti ti-info-circle me-2"></i>Affiliate Program
                        </a></li>
                    </ul>
                </li>
                
                <!-- Vendor Access Menu with Submenu -->
                <li class="suha-dropdown-menu vendor-menu">
                    <a href="#" class="menu-item vendor-main-menu dropdown-toggle">
                        <i class="ti ti-building-store me-2"></i>
                        <div class="login-info">
                            <span class="fw-bold">Vendor Access</span>
                            <small class="d-block">Sell your products</small>
                        </div>
                    </a>
                    <ul class="vendor-submenu dropdown-submenu">
                        <li><a href="{{ route('vendor.login') ?? '#' }}">
                            <i class="ti ti-login me-2"></i>Vendor Login
                        </a></li>
                        <li><a href="{{ route('vendor.register') ?? '#' }}">
                            <i class="ti ti-user-plus me-2"></i>Vendor Registration
                        </a></li>
                        <li class="submenu-divider"></li>
                        <li><a href="{{ route('vendor.info') ?? '#' }}">
                            <i class="ti ti-info-circle me-2"></i>Become a Vendor
                        </a></li>
                    </ul>
                </li>
                
                <li class="menu-divider"></li>
                
                <!-- Shopping Menu for Guests -->
                <li class="menu-header">
                    <span class="text-white-50 text-uppercase fw-bold fs-6"><i class="ti ti-shopping-cart me-2"></i>Browse Shop</span>
                </li>
                
            @endauth
            
            <!-- Common Menu Items for Both Logged In and Guest Users -->
            <li class="suha-dropdown-menu">
                <a href="#" class="menu-item dropdown-toggle">
                    <i class="ti ti-building-store"></i>
                    <span>Shop Pages</span>
                </a>
                <ul class="dropdown-submenu">
                    <li><a href="{{ route('shop.grid') }}">Shop Grid</a></li>
                    <li><a href="{{ route('shop.list') }}">Shop List</a></li>
                    <li><a href="{{ route('products.featured') }}">Featured Products</a></li>
                    <li><a href="{{ route('flash-sale') }}">Flash Sale</a></li>
                </ul>
            </li>
            
            @guest
                <li><a href="{{ route('wishlist.grid') }}" class="menu-item">
                    <i class="ti ti-heart"></i>
                    <span>Wishlist</span>
                    <span class="ms-auto badge bg-danger wishlist-count">0</span>
                </a></li>
            @endguest
            
            <li class="menu-divider"></li>
            
            <!-- Information Menu -->
            <li class="menu-header">
                <span class="text-white-50 text-uppercase fw-bold fs-6"><i class="ti ti-info-circle me-2"></i>Information</span>
            </li>
            
            <li><a href="{{ route('pages.about') }}" class="menu-item">
                <i class="ti ti-notebook"></i>
                <span>About Us</span>
            </a></li>
            <li><a href="{{ route('contact.show') }}" class="menu-item">
                <i class="ti ti-phone"></i>
                <span>Contact</span>
            </a></li>
        </ul>
    </div>
</div>

<style>
/* Enhanced Sidebar Styling */
.suha-offcanvas-wrap {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 50%, #2c3e50 100%) !important;
    border: none !important;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
}

/* Profile Section */
.sidenav-profile {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.profile-img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 3px solid rgba(255, 255, 255, 0.3);
    object-fit: cover;
}

.user-name {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 5px !important;
}

.user-role {
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 5px !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.available-balance {
    font-size: 13px;
    margin: 0 !important;
}

/* Menu Headers */
.menu-header {
    margin: 20px 0 10px 0;
    padding: 0 20px;
}

.menu-header span {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
}

/* Menu Items */
.menu-item {
    display: flex !important;
    align-items: center;
    padding: 12px 20px !important;
    color: rgba(255, 255, 255, 0.9) !important;
    text-decoration: none;
    border-radius: 10px;
    margin: 3px 10px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid transparent;
    backdrop-filter: blur(5px);
    position: relative;
    overflow: hidden;
}

.menu-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transition: left 0.5s;
}

.menu-item:hover::before {
    left: 100%;
}

.menu-item:hover {
    background: rgba(255, 255, 255, 0.15) !important;
    color: #fff !important;
    border-color: rgba(255, 255, 255, 0.3);
    transform: translateX(5px) scale(1.02);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.menu-item i {
    font-size: 18px;
    width: 24px;
    text-align: center;
    margin-right: 12px;
}

.menu-item span {
    flex: 1;
    font-weight: 500;
}

/* Special Menu Item Colors */
.menu-item.text-warning:hover {
    background: rgba(127, 140, 141, 0.2) !important;
    border-color: rgba(127, 140, 141, 0.4);
}

.menu-item.text-success:hover {
    background: rgba(52, 73, 94, 0.2) !important;
    border-color: rgba(52, 73, 94, 0.4);
}

.menu-item.logout-btn {
    background: rgba(220, 53, 69, 0.1);
    border-color: rgba(220, 53, 69, 0.3);
}

.menu-item.logout-btn:hover {
    background: rgba(220, 53, 69, 0.2) !important;
    border-color: rgba(220, 53, 69, 0.5);
    color: #ff6b6b !important;
}

/* Login Options */
.customer-login {
    background: rgba(52, 73, 94, 0.15) !important;
    border-color: rgba(52, 73, 94, 0.3) !important;
}

.customer-login:hover {
    background: rgba(52, 73, 94, 0.25) !important;
    border-color: rgba(52, 73, 94, 0.5) !important;
}

.customer-register {
    background: rgba(44, 62, 80, 0.15) !important;
    border-color: rgba(44, 62, 80, 0.3) !important;
}

.customer-register:hover {
    background: rgba(44, 62, 80, 0.25) !important;
    border-color: rgba(44, 62, 80, 0.5) !important;
}

/* Affiliate Menu */
.affiliate-main-menu {
    background: rgba(127, 140, 141, 0.15) !important;
    border-color: rgba(127, 140, 141, 0.3) !important;
}

.affiliate-main-menu:hover {
    background: rgba(127, 140, 141, 0.25) !important;
    border-color: rgba(127, 140, 141, 0.5) !important;
}

/* Customer Menu */
.customer-main-menu {
    background: rgba(52, 73, 94, 0.15) !important;
    border-color: rgba(52, 73, 94, 0.3) !important;
}

.customer-main-menu:hover {
    background: rgba(52, 73, 94, 0.25) !important;
    border-color: rgba(52, 73, 94, 0.5) !important;
}

/* Vendor Menu */
.vendor-main-menu {
    background: rgba(95, 106, 106, 0.15) !important;
    border-color: rgba(95, 106, 106, 0.3) !important;
}

.vendor-main-menu:hover {
    background: rgba(95, 106, 106, 0.25) !important;
    border-color: rgba(95, 106, 106, 0.5) !important;
}

.promo-item {
    background: linear-gradient(135deg, rgba(127, 140, 141, 0.15), rgba(149, 165, 166, 0.15)) !important;
    border-color: rgba(127, 140, 141, 0.4) !important;
}

.promo-item:hover {
    background: linear-gradient(135deg, rgba(127, 140, 141, 0.25), rgba(149, 165, 166, 0.25)) !important;
    transform: translateX(5px) scale(1.05);
}

/* Dropdown Menus */
.dropdown-toggle::after {
    content: '\f107';
    font-family: 'tabler-icons';
    margin-left: auto;
    transition: transform 0.3s ease;
    font-size: 14px;
}

.suha-dropdown-menu:hover .dropdown-toggle::after {
    transform: rotate(180deg);
}

.dropdown-submenu {
    list-style: none;
    padding: 0;
    margin: 5px 0 0 0;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    overflow: hidden;
    max-height: 0;
    transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), padding 0.4s ease;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.suha-dropdown-menu:hover .dropdown-submenu {
    max-height: 300px;
    padding: 10px 0;
}

.dropdown-submenu li a {
    display: flex !important;
    align-items: center;
    padding: 8px 30px !important;
    color: rgba(255, 255, 255, 0.8) !important;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 14px;
    border-radius: 0;
    margin: 0;
    border: none;
}

.dropdown-submenu li a:hover {
    color: #fff !important;
    background: rgba(255, 255, 255, 0.1) !important;
    transform: translateX(10px);
}

/* Menu Dividers */
.menu-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    margin: 15px 20px;
    border: none;
}

.submenu-divider {
    height: 1px;
    background: rgba(255, 255, 255, 0.1);
    margin: 5px 15px;
}

/* Login Info */
.login-info {
    margin-left: 8px;
    line-height: 1.3;
}

.login-info span {
    color: #fff;
    font-size: 14px;
    display: block;
}

.login-info small {
    color: rgba(255, 255, 255, 0.7);
    font-size: 11px;
    display: block;
    margin-top: 2px;
}

.promo-info {
    margin-left: 8px;
    line-height: 1.3;
}

.promo-info span {
    color: #ffc107;
    font-size: 14px;
    display: block;
}

.promo-info small {
    color: rgba(255, 255, 255, 0.8);
    font-size: 11px;
    margin-top: 2px;
}

/* Badges */
.badge {
    font-size: 10px;
    padding: 4px 8px;
    border-radius: 12px;
    font-weight: 600;
}

.bg-warning {
    background-color: #ffc107 !important;
    color: #000 !important;
}

.bg-danger {
    background-color: #dc3545 !important;
    color: #fff !important;
}

.wishlist-count {
    font-size: 9px !important;
    padding: 2px 6px !important;
}

/* Scrollbar */
.offcanvas-body::-webkit-scrollbar {
    width: 6px;
}

.offcanvas-body::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
}

.offcanvas-body::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
}

.offcanvas-body::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}

/* Close Button */
.btn-close-white {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 1000;
    opacity: 0.8;
    transition: all 0.3s ease;
}

.btn-close-white:hover {
    opacity: 1;
    transform: rotate(90deg);
}

/* Responsive Design */
@media (max-width: 768px) {
    .menu-item {
        padding: 10px 15px !important;
        margin: 2px 5px;
    }
    
    .menu-item i {
        font-size: 16px;
        margin-right: 10px;
    }
    
    .login-info span, .promo-info span {
        font-size: 13px;
    }
    
    .login-info small, .promo-info small {
        font-size: 10px;
    }
    
    .sidenav-profile {
        padding: 15px;
    }
    
    .profile-img {
        width: 50px;
        height: 50px;
    }
    
    .user-name {
        font-size: 16px;
    }
}

/* Animation Classes */
.lni-tada-effect {
    animation: tada 2s infinite;
}

@keyframes tada {
    0% { transform: scale(1); }
    10%, 20% { transform: scale(0.9) rotate(-3deg); }
    30%, 50%, 70%, 90% { transform: scale(1.1) rotate(3deg); }
    40%, 60%, 80% { transform: scale(1.1) rotate(-3deg); }
    100% { transform: scale(1) rotate(0); }
}

/* Loading Animation */
.ti-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Pulse Animation for Badges */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); box-shadow: 0 0 0 5px rgba(255, 193, 7, 0.3); }
    100% { transform: scale(1); }
}

/* Focus States for Accessibility */
.menu-item:focus {
    outline: 2px solid rgba(255, 255, 255, 0.8);
    outline-offset: 2px;
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    .suha-offcanvas-wrap {
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 50%, #8e44ad 100%) !important;
    }
}

/* Professional Theme Overrides */
.professional-sidebar.suha-offcanvas-wrap,
#suhaOffcanvas.professional-sidebar,
.professional-sidebar {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 50%, #2c3e50 100%) !important;
}

/* Force override for any external CSS */
.offcanvas.suha-offcanvas-wrap.professional-sidebar {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 50%, #2c3e50 100%) !important;
    background-color: #2c3e50 !important;
}
</style>

<script>
// Global functions that need to be accessible from anywhere

// Handle logout form submission with service worker cache clearing
function handleLogoutSubmit(form) {
    // Clear service worker cache
    if (typeof window.clearServiceWorkerCache === 'function') {
        window.clearServiceWorkerCache();
    }
    
    // Clear browser caches
    if ('caches' in window) {
        caches.keys().then(names => {
            names.forEach(name => {
                caches.delete(name);
            });
        });
    }
    
    // Clear storage
    try {
        localStorage.clear();
        sessionStorage.clear();
    } catch (e) {
        console.log('Storage clear failed:', e);
    }
    
    // Allow form submission
    return true;
}

// Toast animations
if (!document.querySelector('#toast-styles')) {
    const style = document.createElement('style');
    style.id = 'toast-styles';
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        .toast-content {
            display: flex;
            align-items: center;
        }
        .toast-content i {
            margin-right: 8px;
        }
    `;
    document.head.appendChild(style);
}

document.addEventListener('DOMContentLoaded', function() {
    // Enhanced dropdown functionality
    const dropdownMenus = document.querySelectorAll('.suha-dropdown-menu');
    
    dropdownMenus.forEach(menu => {
        const submenu = menu.querySelector('.dropdown-submenu');
        const mainLink = menu.querySelector('.dropdown-toggle');
        let hoverTimeout;

        if (submenu && mainLink) {
            // Mouse enter - show submenu
            menu.addEventListener('mouseenter', function() {
                clearTimeout(hoverTimeout);
                submenu.style.maxHeight = '300px';
                submenu.style.padding = '10px 0';
                
                // Rotate chevron
                const chevron = mainLink.querySelector('::after');
                mainLink.style.setProperty('--chevron-rotation', '180deg');
            });

            // Mouse leave - hide submenu with delay
            menu.addEventListener('mouseleave', function() {
                hoverTimeout = setTimeout(() => {
                    submenu.style.maxHeight = '0';
                    submenu.style.padding = '0';
                    
                    // Reset chevron
                    mainLink.style.setProperty('--chevron-rotation', '0deg');
                }, 200);
            });

            // Prevent submenu from closing when hovering over it
            submenu.addEventListener('mouseenter', function() {
                clearTimeout(hoverTimeout);
            });

            submenu.addEventListener('mouseleave', function() {
                hoverTimeout = setTimeout(() => {
                    submenu.style.maxHeight = '0';
                    submenu.style.padding = '0';
                }, 200);
            });
        }
    });

    // Enhanced menu item animations
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        // Add shimmer effect on hover
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px) scale(1.02)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0) scale(1)';
        });

        // Add ripple effect on click
        item.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                transform: scale(0);
                animation: ripple 0.6s linear;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                pointer-events: none;
            `;
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Update wishlist count for guests
    function updateWishlistCount() {
        const wishlistCountEl = document.querySelector('.wishlist-count');
        if (wishlistCountEl) {
            fetch('/wishlist/count')
                .then(response => response.json())
                .then(data => {
                    wishlistCountEl.textContent = data.count || 0;
                    
                    // Animate badge if count > 0
                    if (data.count > 0) {
                        wishlistCountEl.style.animation = 'pulse 0.5s ease-in-out';
                        setTimeout(() => {
                            wishlistCountEl.style.animation = '';
                        }, 500);
                    }
                })
                .catch(error => console.log('Error updating wishlist count:', error));
        }
    }

    // Update notification count for authenticated users
    function updateNotificationCount() {
        const notificationCountEl = document.querySelector('.notification-count');
        if (notificationCountEl) {
            fetch('/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.count > 0) {
                        notificationCountEl.textContent = data.count;
                        notificationCountEl.style.display = 'inline-block';
                        
                        // Animate badge
                        notificationCountEl.style.animation = 'pulse 0.5s ease-in-out';
                        setTimeout(() => {
                            notificationCountEl.style.animation = '';
                        }, 500);
                    } else {
                        notificationCountEl.style.display = 'none';
                    }
                })
                .catch(error => console.log('Error updating notification count:', error));
        }
    }

    // Update wishlist count on page load
    updateWishlistCount();
    
    // Update notification count on page load
    updateNotificationCount();
    
    // Auto-refresh counts every 30 seconds
    setInterval(() => {
        updateWishlistCount();
        updateNotificationCount();
    }, 30000);

    // Badge animations for notifications
    const badges = document.querySelectorAll('.badge');
    badges.forEach(badge => {
        // Pulse animation every 10 seconds
        setInterval(() => {
            badge.style.animation = 'pulse 1s ease-in-out';
            setTimeout(() => {
                badge.style.animation = '';
            }, 1000);
        }, 10000);
    });

    // Smooth scrolling for sidebar
    const sidebar = document.querySelector('#suhaOffcanvas');
    if (sidebar) {
        // Add smooth opening/closing animations
        sidebar.addEventListener('show.bs.offcanvas', function() {
            this.style.transition = 'transform 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
            
            // Animate menu items one by one
            const menuItems = this.querySelectorAll('.menu-item');
            menuItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';
                
                setTimeout(() => {
                    item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, index * 50);
            });
        });
        
        sidebar.addEventListener('hide.bs.offcanvas', function() {
            // Reset all dropdown states
            const dropdowns = this.querySelectorAll('.dropdown-submenu');
            dropdowns.forEach(dropdown => {
                dropdown.style.maxHeight = '0';
                dropdown.style.padding = '0';
            });
            
            // Reset menu items
            const menuItems = this.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.style.opacity = '';
                item.style.transform = '';
                item.style.transition = '';
            });
        });
    }

    // Keyboard navigation support
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const sidebar = document.querySelector('#suhaOffcanvas');
            if (sidebar && sidebar.classList.contains('show')) {
                const closeBtn = sidebar.querySelector('.btn-close');
                if (closeBtn) closeBtn.click();
            }
        }
    });

    // Focus management for accessibility
    const sidebarToggle = document.querySelector('[data-bs-target="#suhaOffcanvas"]');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            setTimeout(() => {
                const firstMenuItem = document.querySelector('.menu-item');
                if (firstMenuItem) firstMenuItem.focus();
            }, 300);
        });
    }

    // Add loading states for external links
    const externalLinks = document.querySelectorAll('a[href^="http"]:not([href*="' + window.location.hostname + '"])');
    externalLinks.forEach(link => {
        link.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (icon) {
                const originalClass = icon.className;
                icon.className = 'ti ti-external-link ti-spin';
                setTimeout(() => {
                    icon.className = originalClass;
                }, 2000);
            }
        });
    });
});

// Add ripple animation keyframes
const rippleStyle = document.createElement('style');
rippleStyle.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(rippleStyle);

// Global function to update wishlist count (can be called from other pages)
window.updateSidebarWishlistCount = function() {
    const wishlistCountEl = document.querySelector('.wishlist-count');
    if (wishlistCountEl) {
        fetch('/wishlist/count')
            .then(response => response.json())
            .then(data => {
                wishlistCountEl.textContent = data.count || 0;
            })
            .catch(error => console.log('Error updating wishlist count:', error));
    }
};

// Global function to update notification count (can be called from other pages)
window.updateSidebarNotificationCount = function() {
    const notificationCountEl = document.querySelector('.notification-count');
    if (notificationCountEl) {
        fetch('/notifications/unread-count')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.count > 0) {
                    notificationCountEl.textContent = data.count;
                    notificationCountEl.style.display = 'inline-block';
                } else {
                    notificationCountEl.style.display = 'none';
                }
            })
            .catch(error => console.log('Error updating notification count:', error));
    }
};
</script>
