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
                        <img src="{{ asset('assets/img/core-img/logo-white.png') }}" alt="Logo" class="logo-img">
                    </a>
                </div>

                <!-- Login Card -->
                <div class="auth-card">
                    <div class="card-header">
                        <div class="text-center">
                            <div class="auth-icon customer-icon">
                                <i class="ti ti-shopping-cart"></i>
                            </div>
                            <h3 class="auth-title">Customer Login</h3>
                            <p class="auth-subtitle">Sign in to shop and purchase products</p>
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

                        <!-- Login Form -->
                        <form action="{{ route('general.login.submit') }}" method="POST" class="auth-form">
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
                            
                            <div class="form-floating mb-3">
                                <input type="password" 
                                       class="form-control modern-input @error('password') is-invalid @enderror" 
                                       id="password"
                                       name="password" 
                                       placeholder="Enter your password"
                                       required>
                                <label for="password">
                                    <i class="ti ti-lock me-2"></i>Password
                                </label>
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
                                <a href="#" class="forgot-link" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                                    Forgot Password?
                                </a>
                            </div>
                            
                            <button type="submit" class="btn btn-primary auth-btn customer-btn w-100 mb-3">
                                <i class="ti ti-login me-2"></i>
                                Start Shopping
                            </button>
                        </form>

                        <!-- Additional Links -->
                        <div class="auth-footer">
                            <div class="text-center">
                                <p class="mb-3">Don't have an account?</p>
                                <a href="{{ route('register') }}" class="btn btn-outline-success w-100">
                                    <i class="ti ti-user-plus me-2"></i>
                                    Create Account
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

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="ti ti-key me-2 text-primary"></i>
                    Reset Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">Enter your email address and we'll send you a link to reset your password.</p>
                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control modern-input" id="forgot-email" name="email" required>
                        <label for="forgot-email">Email Address</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ti ti-send me-2"></i>
                        Send Reset Link
                    </button>
                </form>
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
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
    max-height: 50px;
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
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
    background: linear-gradient(90deg, #28a745, #20c997, #17a2b8, #007bff);
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

/* Customer Icon */
.customer-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #28a745, #20c997);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
    animation: pulse 2s infinite;
}

.customer-icon i {
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

.affiliate-switch {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 152, 0, 0.1));
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.affiliate-switch:hover {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.15), rgba(255, 152, 0, 0.15));
    color: #e0a800;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 193, 7, 0.2);
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
    border-color: #28a745;
    background: white;
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
    transform: translateY(-2px);
}

.form-floating > label {
    padding-left: 3rem;
    color: #718096;
    font-weight: 500;
}

.form-floating > .modern-input:focus ~ label,
.form-floating > .modern-input:not(:placeholder-shown) ~ label {
    color: #28a745;
    transform: scale(0.85) translateY(-2.5rem) translateX(0.15rem);
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
    background-color: #28a745;
    border-color: #28a745;
    transform: scale(1.1);
}

.modern-check .form-check-label {
    color: #4a5568;
    font-weight: 500;
    margin-left: 0.5rem;
}

/* Customer Button */
.customer-btn {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    border-radius: 12px;
    padding: 1rem 2rem;
    font-weight: 600;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.customer-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.customer-btn:hover::before {
    left: 100%;
}

.customer-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(40, 167, 69, 0.4);
}

.customer-btn:active {
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
    color: #28a745;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.875rem;
    transition: color 0.3s ease;
}

.forgot-link:hover {
    color: #1e7e34;
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
    
    .customer-icon {
        width: 60px;
        height: 60px;
    }
    
    .customer-icon i {
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
</style>
@endsection
