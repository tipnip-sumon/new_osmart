@extends('layouts.app')

@section('title', 'Affiliate Login')

@section('content')
<div class="auth-wrapper">
    <!-- Background Elements -->
    <div class="auth-bg-shapes">
        <div class="shape-1"></div>
        <div class="shape-2"></div>
        <div class="shape-3"></div>
    </div>

    <div class="container-fluid vh-100">
        <div class="row h-100 align-items-center justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                <!-- Logo Section -->
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}" class="auth-logo">
                        <img src="{{ asset('assets/images/logo/osmart-logo.svg') }}" alt="{{ siteName() }}" class="logo-img">
                    </a>
                </div>

                <!-- Login Card -->
                <div class="auth-card">
                    <div class="card-header">
                        <div class="text-center">
                            <div class="auth-icon">
                                <i class="ti ti-crown"></i>
                            </div>
                            <h3 class="auth-title">Affiliate Login</h3>
                            <p class="auth-subtitle">Access your affiliate dashboard and earnings</p>
                        </div>

                        <!-- Login Type Switcher -->
                        <div class="login-switcher">
                            <a href="{{ route('general.login') }}" class="switch-btn customer-switch">
                                <i class="ti ti-shopping-cart me-2"></i>
                                <span>Customer Login</span>
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-modern">
                                <i class="ti ti-alert-circle me-2"></i>
                                <div>
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-modern">
                                <i class="ti ti-check-circle me-2"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                        @endif

                        <!-- Login Form -->
                        <form action="{{ route('affiliate.login.submit') }}" method="POST" class="auth-form">
                            @csrf

                            <div class="form-floating mb-3">
                                <input type="email"
                                       class="form-control modern-input @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="Enter your email"
                                       required>
                                <label for="email">
                                    <i class="ti ti-mail me-2"></i>Email Address
                                </label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3 password-field-wrapper">
                                <input type="password"
                                       class="form-control modern-input @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       placeholder="Enter your password"
                                       required>
                                <label for="password">
                                    <i class="ti ti-lock me-2"></i>Password
                                </label>
                                <button type="button" 
                                        class="password-toggle-btn" 
                                        id="passwordToggle" 
                                        title="Show/Hide Password"
                                        style="position: absolute !important; top: 50% !important; right: 12px !important; transform: translateY(-50%) !important; z-index: 1000 !important; background: none !important; border: none !important; color: #718096 !important; cursor: pointer !important; padding: 8px !important; border-radius: 6px !important; width: 40px !important; height: 40px !important; display: flex !important; align-items: center !important; justify-content: center !important; margin: 0 !important; float: none !important; line-height: 1 !important;">
                                    <i class="ti ti-eye" id="passwordToggleIcon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check modern-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="remember"
                                           id="remember">
                                    <label class="form-check-label" for="remember">
                                        Remember me
                                    </label>
                                </div>
                                <a href="{{ route('password.request') }}" class="forgot-link">
                                    Forgot Password?
                                </a>
                            </div>

                            <button type="submit" class="btn btn-primary auth-btn w-100 mb-3">
                                <i class="ti ti-login me-2"></i>
                                Access Dashboard
                            </button>
                        </form>

                        <!-- Additional Links -->
                        <div class="auth-footer">
                            <div class="text-center">
                                <p class="mb-3">Don't have an affiliate account?</p>
                                <a href="{{ route('register') }}" class="btn btn-outline-warning w-100">
                                    <i class="ti ti-user-plus me-2"></i>
                                    Join as Affiliate
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="back-home-link">
                        <i class="ti ti-arrow-left me-2"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
/* Auth Wrapper */
.auth-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

/* Background Shapes */
.auth-bg-shapes {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

.shape-1 {
    position: absolute;
    top: -50px;
    right: -50px;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
    animation: float 6s ease-in-out infinite;
}

.shape-2 {
    position: absolute;
    bottom: -30px;
    left: -30px;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: linear-gradient(-45deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.03));
    animation: float 8s ease-in-out infinite reverse;
}

.shape-3 {
    position: absolute;
    top: 50%;
    left: -100px;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
    animation: float 10s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) rotate(180deg);
    }
}

/* Logo */
.auth-logo {
    position: relative;
    z-index: 10;
    display: inline-block;
    transition: transform 0.3s ease;
}

.auth-logo:hover {
    transform: scale(1.05);
}

.logo-img {
    max-height: 60px;
    max-width: 200px;
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
    transition: transform 0.3s ease;
}

/* Responsive logo sizing */
@media (max-width: 992px) {
    .logo-img {
        max-height: 50px;
        max-width: 150px;
    }
}

@media (max-width: 768px) {
    .logo-img {
        max-height: 45px;
        max-width: 120px;
    }
}

/* Auth Card */
.auth-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
    z-index: 10;
    overflow: hidden;
}

.auth-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
    background-size: 400% 400%;
    animation: gradientShift 3s ease infinite;
}

@keyframes gradientShift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.card-header {
    background: transparent;
    border: none;
    padding: 2rem 2rem 1rem;
}

.card-body {
    padding: 0 2rem 2rem;
}

/* Auth Icon */
.auth-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ffc107, #ff8c00);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    box-shadow: 0 10px 30px rgba(255, 193, 7, 0.3);
    animation: pulse 2s infinite;
}

.auth-icon i {
    font-size: 2rem;
    color: white;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Typography */
.auth-title {
    color: #2d3748;
    font-weight: 700;
    font-size: 1.75rem;
    margin-bottom: 0.5rem;
}

.auth-subtitle {
    color: #718096;
    font-size: 1rem;
    margin-bottom: 1.5rem;
}

/* Login Switcher */
.login-switcher {
    text-align: center;
    margin-bottom: 1rem;
}

.switch-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.customer-switch {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.3);
}

.customer-switch:hover {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.15), rgba(32, 201, 151, 0.15));
    color: #1e7e34;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.2);
}

/* Modern Form Controls */
.modern-input {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 1rem 1rem 1rem 3rem;
    background: #f8fafc;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.modern-input:focus {
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
}

/* Password input needs extra right padding for toggle button */
.password-field-wrapper .modern-input {
    padding-right: 3.5rem;
}

/* Password input needs extra right padding for toggle button */
.password-field-wrapper .modern-input {
    padding-right: 3.5rem;
}

.form-floating > label {
    padding-left: 3rem;
    color: #718096;
    font-weight: 500;
}

.form-floating > .modern-input:focus ~ label,
.form-floating > .modern-input:not(:placeholder-shown) ~ label {
    color: #667eea;
    transform: scale(0.85) translateY(-2.5rem) translateX(0.15rem);
}

/* Password Field Wrapper */
.password-field-wrapper {
    position: relative !important;
}

/* Override Bootstrap form-floating for password field */
.password-field-wrapper.form-floating {
    position: relative !important;
    overflow: visible !important;
}

/* Specific selector for password toggle button */
.form-floating.password-field-wrapper .password-toggle-btn,
.password-field-wrapper .password-toggle-btn {
    position: absolute !important;
    top: 50% !important;
    right: 12px !important;
    transform: translateY(-50%) !important;
    z-index: 1000 !important;
    background: none !important;
    border: none !important;
    color: #718096 !important;
    cursor: pointer !important;
    padding: 8px !important;
    border-radius: 6px !important;
    width: 40px !important;
    height: 40px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.3s ease !important;
    margin: 0 !important;
    float: none !important;
    line-height: 1 !important;
}

.form-floating.password-field-wrapper .password-toggle-btn:hover,
.password-field-wrapper .password-toggle-btn:hover {
    color: #667eea !important;
    background: rgba(102, 126, 234, 0.1) !important;
    transform: translateY(-50%) scale(1.05) !important;
}

.form-floating.password-field-wrapper .password-toggle-btn:focus,
.password-field-wrapper .password-toggle-btn:focus {
    outline: none !important;
    box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.3) !important;
}

.form-floating.password-field-wrapper .password-toggle-btn i,
.password-field-wrapper .password-toggle-btn i {
    font-size: 18px !important;
}

/* Modern Checkbox */
.modern-check .form-check-input {
    width: 1.25rem;
    height: 1.25rem;
    border: 2px solid #cbd5e0;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.modern-check .form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
    transform: scale(1.1);
}

.modern-check .form-check-label {
    color: #4a5568;
    font-weight: 500;
    margin-left: 0.5rem;
}

/* Auth Button */
.auth-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 12px;
    padding: 1rem 2rem;
    font-weight: 600;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.auth-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.auth-btn:hover::before {
    left: 100%;
}

.auth-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
}

.auth-btn:active {
    transform: translateY(-1px);
}

/* Alerts */
.alert-modern {
    border: none;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: flex-start;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.alert-danger {
    background: linear-gradient(135deg, #fed7d7, #feb2b2);
    color: #c53030;
}

.alert-success {
    background: linear-gradient(135deg, #c6f6d5, #9ae6b4);
    color: #2f855a;
}

/* Links */
.forgot-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.875rem;
    transition: color 0.3s ease;
}

.forgot-link:hover {
    color: #764ba2;
    text-decoration: underline;
}

.back-home-link {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.back-home-link:hover {
    color: white;
    transform: translateX(-5px);
}

/* Modern Modal */
.modern-modal .modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.modern-modal .modal-header {
    padding: 1.5rem 1.5rem 0;
}

.modern-modal .modal-body {
    padding: 1.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .auth-card {
        margin: 1rem;
        border-radius: 15px;
    }

    .card-header,
    .card-body {
        padding: 1.5rem;
    }

    .auth-title {
        font-size: 1.5rem;
    }

    .auth-icon {
        width: 60px;
        height: 60px;
    }

    .auth-icon i {
        font-size: 1.5rem;
    }

    .modern-input {
        padding: 0.875rem 0.875rem 0.875rem 2.5rem;
    }

    .form-floating > label {
        padding-left: 2.5rem;
    }
}

@media (max-width: 480px) {
    .auth-wrapper {
        padding: 1rem 0;
    }

    .logo-img {
        max-height: 40px;
    }

    .auth-title {
        font-size: 1.25rem;
    }

    .auth-subtitle {
        font-size: 0.875rem;
    }
}

/* Loading Animation */
.auth-btn.loading {
    pointer-events: none;
    opacity: 0.7;
}

.auth-btn.loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    left: 50%;
    margin-left: -10px;
    margin-top: -10px;
    border: 2px solid transparent;
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection

@push('scripts')
<script>
    // Password Toggle Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const passwordToggle = document.getElementById('passwordToggle');
        const passwordField = document.getElementById('password');
        const passwordToggleIcon = document.getElementById('passwordToggleIcon');

        if (passwordToggle && passwordField && passwordToggleIcon) {
            // Add hover effects
            passwordToggle.addEventListener('mouseenter', function() {
                this.style.color = '#667eea !important';
                this.style.backgroundColor = 'rgba(102, 126, 234, 0.1) !important';
                this.style.transform = 'translateY(-50%) scale(1.05) !important';
            });
            
            passwordToggle.addEventListener('mouseleave', function() {
                this.style.color = '#718096 !important';
                this.style.backgroundColor = 'none !important';
                this.style.transform = 'translateY(-50%) !important';
            });
            
            passwordToggle.addEventListener('click', function() {
                // Toggle password field type
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    passwordToggleIcon.className = 'ti ti-eye-off';
                    passwordToggle.title = 'Hide Password';
                } else {
                    passwordField.type = 'password';
                    passwordToggleIcon.className = 'ti ti-eye';
                    passwordToggle.title = 'Show Password';
                }
            });

            // Optional: Hide password when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.password-field-wrapper')) {
                    if (passwordField.type === 'text') {
                        passwordField.type = 'password';
                        passwordToggleIcon.className = 'ti ti-eye';
                        passwordToggle.title = 'Show Password';
                    }
                }
            });
        }
    });

    // Show SweetAlert for role session conflicts
    @if(session('show_alert'))
    document.addEventListener('DOMContentLoaded', function() {
        const alertData = @json(session('show_alert'));
        Swal.fire({
            title: alertData.title,
            text: alertData.message,
            icon: alertData.type,
            confirmButtonColor: '#ffc107',
            confirmButtonText: 'Login Now',
            allowOutsideClick: true,
            timer: alertData.timer || 6000,
            timerProgressBar: true,
            showClass: {
                popup: 'swal2-show'
            }
        }).then((result) => {
            // Focus on email input after closing alert
            const emailInput = document.getElementById('email');
            if (emailInput) {
                emailInput.focus();
            }
        });
    });
    @endif

    // Show other alerts
    @if(session('success') || session('info') || $errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#28a745'
            });
        @endif

        @if(session('info'))
            Swal.fire({
                title: 'Information',
                text: '{{ session('info') }}',
                icon: 'info',
                confirmButtonColor: '#17a2b8'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                title: 'Login Error',
                html: '<ul style="text-align: left; margin: 0; padding-left: 20px;">' + 
                      @json($errors->all()).map(error => '<li>' + error + '</li>').join('') + 
                      '</ul>',
                icon: 'error',
                confirmButtonColor: '#dc3545'
            });
        @endif
    });
    @endif
</script>
@endpush
