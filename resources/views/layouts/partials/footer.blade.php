<!-- Footer Nav-->
<div class="footer-nav-area" id="footerNav">
    <div class="suha-footer-nav">
        <ul class="h-100 d-flex align-items-center justify-content-between ps-0 d-flex rtl-flex-d-row-r">
            <li><a href="{{ route('home') }}"><i class="ti ti-home"></i>Home</a></li>
            <li><a href="{{ route('categories.index') }}"><i class="ti ti-category-2"></i>Category</a></li>
            <li><a href="{{ route('shop.grid') }}"><i class="ti ti-building-store"></i>Shop</a></li>
            {{-- <li class="position-relative">
                <a href="{{ route('cart.index') }}">
                    <i class="ti ti-basket"></i>Cart
                    <span class="cart-count-badge position-absolute badge rounded-pill bg-danger text-white" 
                          id="cartCountFooter" 
                          style="display: none; font-size: 10px; min-width: 18px; height: 18px; line-height: 8px; top: -12px; left: 50%; transform: translateX(-50%); z-index: 10;">
                        0
                    </span>
                </a>
            </li> --}}
            @auth
            <li class="position-relative">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="ti ti-logout"></i>Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
            <li><a href="{{ route('settings') }}"><i class="ti ti-adjustments-horizontal"></i>Settings</a></li>
            @else
            <li class="position-relative">
                <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="ti ti-login"></i>Login
                </a>
            </li>
            <li class="position-relative">
                <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal">
                    <i class="ti ti-user-plus"></i>Register
                </a>
            </li>
            @endauth
        </ul>
    </div>
</div>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header border-0 pb-0">
                <div class="w-100 text-center">
                    <div class="login-modal-icon mb-3">
                        <i class="ti ti-shield-check fs-1 text-primary"></i>
                    </div>
                    <h4 class="modal-title fw-bold text-dark" id="loginModalLabel">Choose Login Type</h4>
                    <p class="text-muted mb-0">Select your account type to continue</p>
                </div>
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" 
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body px-4 py-4">
                <div class="row g-3">
                    <!-- Customer Login -->
                    <div class="col-12">
                        <a href="{{ route('login') }}" class="login-type-btn customer-login-btn">
                            <div class="login-btn-content">
                                <div class="login-btn-icon">
                                    <i class="ti ti-shopping-cart"></i>
                                </div>
                                <div class="login-btn-text">
                                    <h6 class="mb-1">Customer Login</h6>
                                    <small class="text-muted">Shop and manage your orders</small>
                                </div>
                                <div class="login-btn-arrow">
                                    <i class="ti ti-chevron-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Affiliate Login -->
                    <div class="col-12">
                        <a href="{{ route('affiliate.login') }}" class="login-type-btn affiliate-login-btn">
                            <div class="login-btn-content">
                                <div class="login-btn-icon">
                                    <i class="ti ti-crown"></i>
                                </div>
                                <div class="login-btn-text">
                                    <h6 class="mb-1">Affiliate Login</h6>
                                    <small class="text-muted">Earn commissions and track performance</small>
                                </div>
                                <div class="login-btn-arrow">
                                    <i class="ti ti-chevron-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Vendor Login -->
                    <div class="col-12">
                        <a href="{{ route('vendor.login') }}" class="login-type-btn vendor-login-btn">
                            <div class="login-btn-content">
                                <div class="login-btn-icon">
                                    <i class="ti ti-building-store"></i>
                                </div>
                                <div class="login-btn-text">
                                    <h6 class="mb-1">Vendor Login</h6>
                                    <small class="text-muted">Manage your store and products</small>
                                </div>
                                <div class="login-btn-arrow">
                                    <i class="ti ti-chevron-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Registration Links -->
                <div class="text-center mt-4 pt-3 border-top">
                    <p class="text-muted mb-2">Don't have an account?</p>
                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm">
                            <i class="ti ti-user-plus me-1"></i>Register
                        </a>
                        <a href="{{ route('affiliate.register') }}" class="btn btn-outline-warning btn-sm">
                            <i class="ti ti-crown me-1"></i>Join Affiliate
                        </a>
                        <a href="{{ route('vendor.register') }}" class="btn btn-outline-success btn-sm">
                            <i class="ti ti-building-store me-1"></i>Become Vendor
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header border-0 pb-0">
                <div class="w-100 text-center">
                    <div class="register-modal-icon mb-3">
                        <i class="ti ti-user-plus fs-1 text-success"></i>
                    </div>
                    <h4 class="modal-title fw-bold text-dark" id="registerModalLabel">Choose Registration Type</h4>
                    <p class="text-muted mb-0">Select your account type to get started</p>
                </div>
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" 
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body px-4 py-4">
                <div class="row g-3">
                    <!-- Customer Registration -->
                    <div class="col-12">
                        <a href="{{ route('register') }}" class="register-type-btn customer-register-btn">
                            <div class="register-btn-content">
                                <div class="register-btn-icon">
                                    <i class="ti ti-shopping-cart"></i>
                                </div>
                                <div class="register-btn-text">
                                    <h6 class="mb-1">Customer Registration</h6>
                                    <small class="text-muted">Join as a customer to shop and place orders</small>
                                </div>
                                <div class="register-btn-arrow">
                                    <i class="ti ti-chevron-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Affiliate Registration -->
                    <div class="col-12">
                        <a href="{{ route('affiliate.register') }}" class="register-type-btn affiliate-register-btn">
                            <div class="register-btn-content">
                                <div class="register-btn-icon">
                                    <i class="ti ti-crown"></i>
                                </div>
                                <div class="register-btn-text">
                                    <h6 class="mb-1">Affiliate Registration</h6>
                                    <small class="text-muted">Earn commissions by promoting products</small>
                                </div>
                                <div class="register-btn-arrow">
                                    <i class="ti ti-chevron-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Vendor Registration -->
                    <div class="col-12">
                        <a href="{{ route('vendor.register') }}" class="register-type-btn vendor-register-btn">
                            <div class="register-btn-content">
                                <div class="register-btn-icon">
                                    <i class="ti ti-building-store"></i>
                                </div>
                                <div class="register-btn-text">
                                    <h6 class="mb-1">Vendor Registration</h6>
                                    <small class="text-muted">Start selling your products online</small>
                                </div>
                                <div class="register-btn-arrow">
                                    <i class="ti ti-chevron-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Login Link -->
                <div class="text-center mt-4 pt-3 border-top">
                    <p class="text-muted mb-2">Already have an account?</p>
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="ti ti-login me-1"></i>Login Instead
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Login Modal Styles */
.login-modal-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.login-modal-icon i {
    color: white !important;
}

/* Register Modal Styles */
.register-modal-icon {
    background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 10px 30px rgba(56, 161, 105, 0.3);
}

.register-modal-icon i {
    color: white !important;
}

.modal-content {
    border-radius: 20px;
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg, #f8f9ff 0%, #e8edff 100%);
    padding: 2rem 1.5rem 1rem;
}

/* Login Type Buttons */
.login-type-btn {
    display: block;
    text-decoration: none;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    padding: 1.25rem;
    margin-bottom: 0.75rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.login-type-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.login-type-btn:hover::before {
    left: 100%;
}

.login-type-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    text-decoration: none;
}

.login-btn-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.login-btn-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.login-btn-text {
    flex: 1;
}

.login-btn-text h6 {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.login-btn-text small {
    color: #718096;
    font-size: 0.875rem;
}

.login-btn-arrow {
    color: #a0aec0;
    font-size: 1.25rem;
    transition: all 0.3s ease;
}

/* Customer Login Button */
.customer-login-btn {
    border-color: #3182ce;
}

.customer-login-btn:hover {
    border-color: #2c5aa0;
    background: linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%);
}

.customer-login-btn .login-btn-icon {
    background: linear-gradient(135deg, #3182ce 0%, #2c5aa0 100%);
    color: white;
}

.customer-login-btn:hover .login-btn-arrow {
    color: #3182ce;
    transform: translateX(5px);
}

/* Affiliate Login Button */
.affiliate-login-btn {
    border-color: #d69e2e;
}

.affiliate-login-btn:hover {
    border-color: #b7791f;
    background: linear-gradient(135deg, #fffbeb 0%, #fed7aa 100%);
}

.affiliate-login-btn .login-btn-icon {
    background: linear-gradient(135deg, #d69e2e 0%, #b7791f 100%);
    color: white;
}

.affiliate-login-btn:hover .login-btn-arrow {
    color: #d69e2e;
    transform: translateX(5px);
}

/* Vendor Login Button */
.vendor-login-btn {
    border-color: #38a169;
}

.vendor-login-btn:hover {
    border-color: #2f855a;
    background: linear-gradient(135deg, #f0fff4 0%, #9ae6b4 100%);
}

.vendor-login-btn .login-btn-icon {
    background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
    color: white;
}

.vendor-login-btn:hover .login-btn-arrow {
    color: #38a169;
    transform: translateX(5px);
}

/* Register Type Buttons */
.register-type-btn {
    display: block;
    text-decoration: none;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    padding: 1.25rem;
    margin-bottom: 0.75rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.register-type-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.register-type-btn:hover::before {
    left: 100%;
}

.register-type-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    text-decoration: none;
}

.register-btn-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.register-btn-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.register-btn-text {
    flex: 1;
}

.register-btn-text h6 {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.register-btn-text small {
    color: #718096;
    font-size: 0.875rem;
}

.register-btn-arrow {
    color: #a0aec0;
    font-size: 1.25rem;
    transition: all 0.3s ease;
}

/* Customer Register Button */
.customer-register-btn {
    border-color: #3182ce;
}

.customer-register-btn:hover {
    border-color: #2c5aa0;
    background: linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%);
}

.customer-register-btn .register-btn-icon {
    background: linear-gradient(135deg, #3182ce 0%, #2c5aa0 100%);
    color: white;
}

.customer-register-btn:hover .register-btn-arrow {
    color: #3182ce;
    transform: translateX(5px);
}

/* Affiliate Register Button */
.affiliate-register-btn {
    border-color: #d69e2e;
}

.affiliate-register-btn:hover {
    border-color: #b7791f;
    background: linear-gradient(135deg, #fffbeb 0%, #fed7aa 100%);
}

.affiliate-register-btn .register-btn-icon {
    background: linear-gradient(135deg, #d69e2e 0%, #b7791f 100%);
    color: white;
}

.affiliate-register-btn:hover .register-btn-arrow {
    color: #d69e2e;
    transform: translateX(5px);
}

/* Vendor Register Button */
.vendor-register-btn {
    border-color: #38a169;
}

.vendor-register-btn:hover {
    border-color: #2f855a;
    background: linear-gradient(135deg, #f0fff4 0%, #9ae6b4 100%);
}

.vendor-register-btn .register-btn-icon {
    background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
    color: white;
}

.vendor-register-btn:hover .register-btn-arrow {
    color: #38a169;
    transform: translateX(5px);
}

/* Footer Navigation Updates */
.cart-count-badge {
    font-weight: bold !important;
    border: 2px solid white;
    box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.footer-nav-area .ti-basket {
    transition: all 0.3s ease;
}

.footer-nav-area li:nth-child(3):hover .ti-basket {
    color: #e53e3e !important;
    transform: scale(1.1);
}

.footer-nav-area li:nth-child(3):hover .cart-count-badge {
    background-color: #e53e3e !important;
    transform: scale(1.1);
}

.footer-nav-area .ti-login {
    transition: all 0.3s ease;
}

.footer-nav-area li:nth-child(4):hover .ti-login {
    color: #667eea !important;
    transform: scale(1.1);
}

/* Logout button styling */
.footer-nav-area .ti-logout {
    transition: all 0.3s ease;
}

.footer-nav-area li:nth-child(4):hover .ti-logout {
    color: #e53e3e !important;
    transform: scale(1.1);
}

/* Modal Animation */
.modal.fade .modal-dialog {
    transform: scale(0.8) translateY(-50px);
    transition: all 0.3s ease;
}

.modal.show .modal-dialog {
    transform: scale(1) translateY(0);
}

/* Registration buttons styling */
.btn-outline-primary:hover,
.btn-outline-warning:hover,
.btn-outline-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .modal-header {
        padding: 1.5rem 1rem 0.75rem;
    }
    
    .login-modal-icon {
        width: 60px;
        height: 60px;
    }
    
    .login-btn-content {
        gap: 0.75rem;
    }
    
    .login-btn-icon {
        width: 40px;
        height: 40px;
        font-size: 1.25rem;
    }
    
    .login-type-btn {
        padding: 1rem;
    }
}

/* SweetAlert Custom Styles */
.logout-swal-popup {
    border-radius: 20px !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
}

.logout-swal-title {
    color: #2d3748 !important;
    font-weight: 700 !important;
    font-size: 1.5rem !important;
}

.logout-swal-content {
    color: #4a5568 !important;
    font-size: 1rem !important;
}

.logout-swal-confirm {
    background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%) !important;
    border: none !important;
    border-radius: 12px !important;
    padding: 12px 24px !important;
    font-weight: 600 !important;
    color: white !important;
    transition: all 0.3s ease !important;
}

.logout-swal-confirm:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(229, 62, 62, 0.4) !important;
}

.logout-swal-cancel {
    background: #f7fafc !important;
    border: 2px solid #e2e8f0 !important;
    border-radius: 12px !important;
    padding: 12px 24px !important;
    font-weight: 600 !important;
    color: #4a5568 !important;
    transition: all 0.3s ease !important;
}

.logout-swal-cancel:hover {
    background: #edf2f7 !important;
    border-color: #cbd5e0 !important;
    transform: translateY(-2px) !important;
}

.logout-loading-popup {
    border-radius: 20px !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
}

.logout-loading-popup .swal2-icon.swal2-success {
    border-color: #38a169 !important;
}

.logout-loading-popup .swal2-success-ring {
    border-color: #38a169 !important;
}

.logout-loading-popup .swal2-success-fix {
    background-color: #38a169 !important;
}

.logout-loading-popup .swal2-success-circular-line-left,
.logout-loading-popup .swal2-success-circular-line-right {
    background-color: #38a169 !important;
}
</style>

<script>
// Load SweetAlert if not already loaded
if (typeof Swal === 'undefined') {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    script.onload = function() {
        initializeFooterFunctionality();
    };
    document.head.appendChild(script);
} else {
    initializeFooterFunctionality();
}

function initializeFooterFunctionality() {
    const loginModal = document.getElementById('loginModal');
    const loginButtons = document.querySelectorAll('.login-type-btn');
    
    // Add click sound effect (optional)
    function playClickSound() {
        // You can add audio feedback here if needed
        // const audio = new Audio('/assets/sounds/click.mp3');
        // audio.play().catch(e => console.log('Audio play failed'));
    }
    
    // Enhanced hover effects
    loginButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.02)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
        
        button.addEventListener('click', function() {
            playClickSound();
            // Add a subtle loading state
            const icon = this.querySelector('.login-btn-icon i');
            const originalIcon = icon.className;
            icon.className = 'ti ti-loader-2 animate-spin';
            
            setTimeout(() => {
                icon.className = originalIcon;
            }, 500);
        });
    });
    
    // Modal show animation
    if (loginModal) {
        loginModal.addEventListener('show.bs.modal', function() {
            document.body.style.overflow = 'hidden';
        });
        
        loginModal.addEventListener('hide.bs.modal', function() {
            document.body.style.overflow = 'auto';
        });
        
        // Keyboard navigation
        loginModal.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                bootstrap.Modal.getInstance(this).hide();
            }
        });
    }
    
    // Logout confirmation with SweetAlert
    const logoutLinks = document.querySelectorAll('a[onclick*="logout-form"]');
    logoutLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Show SweetAlert confirmation
            Swal.fire({
                title: 'Logout Confirmation',
                text: 'Are you sure you want to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#e53e3e',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Logout',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'logout-swal-popup',
                    title: 'logout-swal-title',
                    content: 'logout-swal-content',
                    confirmButton: 'logout-swal-confirm',
                    cancelButton: 'logout-swal-cancel'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    const icon = this.querySelector('i');
                    const originalClass = icon.className;
                    
                    icon.className = 'ti ti-loader-2';
                    icon.style.animation = 'spin 1s linear infinite';
                    
                    // Add spin animation
                    const style = document.createElement('style');
                    style.textContent = `
                        @keyframes spin {
                            from { transform: rotate(0deg); }
                            to { transform: rotate(360deg); }
                        }
                    `;
                    document.head.appendChild(style);
                    
                    // Show success message and logout
                    Swal.fire({
                        title: 'Logging Out...',
                        text: 'Please wait while we log you out safely.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        customClass: {
                            popup: 'logout-loading-popup'
                        }
                    }).then(() => {
                        document.getElementById('logout-form').submit();
                    });
                }
            });
        });
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swal !== 'undefined') {
        initializeFooterFunctionality();
    }
});
</script>
