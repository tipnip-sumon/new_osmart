@extends('layouts.app')

@section('title', 'Customer Login')

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
                        <img src="{{ asset('assets/images/logo/osmart-logo-transparent.svg') }}" alt="{{ siteName() }}" class="logo-img">
                    </a>
                </div>

                <!-- Login Card -->
                <div class="auth-card">
                    <div class="card-header">
                        <div class="text-center">
                            <div class="auth-icon">
                                <i class="ti ti-shopping-cart"></i>
                            </div>
                            <h3 class="auth-title">Customer Login</h3>
                            <p class="auth-subtitle">Welcome back! Sign in to your account</p>
                        </div>

                        <!-- Login Type Switcher -->
                        <div class="login-switcher">
                            <a href="{{ route('affiliate.login') }}" class="switch-btn affiliate-switch">
                                <i class="ti ti-crown me-2"></i>
                                <span>Affiliate Login</span>
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

                        @if (session('info'))
                            <div class="alert alert-info alert-modern">
                                <i class="ti ti-info-circle me-2"></i>
                                <div>{{ session('info') }}</div>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-modern">
                                <i class="ti ti-alert-circle me-2"></i>
                                <div>{{ session('error') }}</div>
                            </div>
                        @endif

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}" class="auth-form">
                            @csrf

                            <div class="form-floating mb-3">
                                <input type="email"
                                       class="form-control modern-input @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="Enter your email"
                                       required
                                       autocomplete="email"
                                       autofocus>
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
                                       required
                                       autocomplete="current-password">
                                <label for="password">
                                    <i class="ti ti-lock me-2"></i>Password
                                </label>
                                <button type="button" 
                                        class="password-toggle-btn" 
                                        id="passwordToggle" 
                                        title="Show/Hide Password"
                                        style="position: absolute !important; top: 50% !important; right: 12px !important; transform: translateY(-50%) !important; z-index: 1000 !important; background: none !important; border: none !important; color: #6c757d !important; cursor: pointer !important; padding: 8px !important; border-radius: 6px !important; width: 40px !important; height: 40px !important; display: flex !important; align-items: center !important; justify-content: center !important; margin: 0 !important; float: none !important; line-height: 1 !important;">
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
                                           id="remember"
                                           {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Remember me
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="forgot-link">
                                        Forgot Password?
                                    </a>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary auth-btn w-100 mb-3">
                                <i class="ti ti-login me-2"></i>
                                Sign In
                            </button>
                        </form>

                        <!-- Additional Links -->
                        <div class="auth-footer">
                            <div class="text-center">
                                <p class="mb-3">Don't have an account?</p>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">
                                        <i class="ti ti-user-plus me-2"></i>
                                        Create Account
                                    </a>
                                @endif
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
@push('styles')
<style>
/* Auth Wrapper */
.auth-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
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
    overflow: hidden;
    z-index: 1;
}

.shape-1, .shape-2, .shape-3 {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    width: 200px;
    height: 200px;
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 150px;
    height: 150px;
    top: 60%;
    right: 15%;
    animation-delay: 2s;
}

.shape-3 {
    width: 100px;
    height: 100px;
    bottom: 20%;
    left: 20%;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

/* Logo */
.auth-logo .logo-img {
    max-height: 60px;
    height: auto;
    width: auto;
    max-width: 200px;
    object-fit: contain;
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
}

@media (max-width: 768px) {
    .auth-logo .logo-img {
        max-height: 50px;
        max-width: 150px;
    }
}

@media (max-width: 576px) {
    .auth-logo .logo-img {
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
    background: linear-gradient(90deg, #007bff, #6610f2, #6f42c1, #e83e8c);
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
    background: linear-gradient(135deg, #007bff, #0056b3);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 2rem;
    box-shadow: 0 10px 20px rgba(0, 123, 255, 0.3);
}

/* Titles */
.auth-title {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 0.5rem;
    font-size: 1.75rem;
}

.auth-subtitle {
    color: #6c757d;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
}

/* Login Switcher */
.login-switcher {
    text-align: center;
    margin-top: 1.5rem;
}

.switch-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.affiliate-switch {
    background: linear-gradient(135deg, #ffc107, #ff8c00);
    color: white;
}

.affiliate-switch:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
    color: white;
}

/* Modern Forms */
.auth-form {
    margin-top: 1rem;
}

.form-floating {
    position: relative;
}

.modern-input {
    border: 2px solid #e9ecef;
    border-radius: 15px;
    padding: 1rem 1rem 1rem 3rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.9);
    height: 60px;
}

.modern-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.1);
    background: white;
}

/* Password input needs extra right padding for toggle button */
.password-field-wrapper .modern-input {
    padding-right: 3.5rem;
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
    color: #6c757d !important;
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
    color: #007bff !important;
    background: rgba(0, 123, 255, 0.1) !important;
    transform: translateY(-50%) scale(1.05) !important;
}

.form-floating.password-field-wrapper .password-toggle-btn:focus,
.password-field-wrapper .password-toggle-btn:focus {
    outline: none !important;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.3) !important;
}

.form-floating.password-field-wrapper .password-toggle-btn i,
.password-field-wrapper .password-toggle-btn i {
    font-size: 18px !important;
}

.form-floating > label {
    padding: 1rem 1rem 1rem 3rem;
    color: #6c757d;
    font-weight: 500;
}

.form-floating > label i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #007bff;
}

/* Modern Checkbox */
.modern-check .form-check-input {
    border-radius: 6px;
    border: 2px solid #e9ecef;
    width: 1.2rem;
    height: 1.2rem;
}

.modern-check .form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.modern-check .form-check-label {
    font-size: 0.9rem;
    color: #6c757d;
    margin-left: 0.5rem;
}

/* Forgot Link */
.forgot-link {
    color: #007bff;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: color 0.3s ease;
}

.forgot-link:hover {
    color: #0056b3;
    text-decoration: underline;
}

/* Auth Button */
.auth-btn {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
    border-radius: 15px;
    padding: 0.875rem 1.5rem;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

.auth-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
    background: linear-gradient(135deg, #0056b3, #004085);
}

/* Alerts */
.alert-modern {
    border: none;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: flex-start;
    font-size: 0.9rem;
}

.alert-modern i {
    margin-top: 0.1rem;
    font-size: 1.1rem;
}

/* Auth Footer */
.auth-footer {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e9ecef;
}

.auth-footer p {
    color: #6c757d;
    font-size: 0.9rem;
}

.auth-footer .btn-outline-primary {
    border: 2px solid #007bff;
    color: #007bff;
    border-radius: 15px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.auth-footer .btn-outline-primary:hover {
    background: #007bff;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

/* Back to Home */
.back-home-link {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.back-home-link:hover {
    color: white;
    transform: translateY(-2px);
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

    .auth-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }

    .auth-title {
        font-size: 1.5rem;
    }

    .login-switcher {
        margin-top: 1rem;
    }

    .switch-btn {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }
}

@media (max-width: 576px) {
    .auth-wrapper {
        padding: 1rem 0;
    }

    .shape-1, .shape-2, .shape-3 {
        display: none;
    }
}
</style>
@endpush

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
                this.style.color = '#007bff !important';
                this.style.backgroundColor = 'rgba(0, 123, 255, 0.1) !important';
                this.style.transform = 'translateY(-50%) scale(1.05) !important';
            });
            
            passwordToggle.addEventListener('mouseleave', function() {
                this.style.color = '#6c757d !important';
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
</script>
@endpush
