@extends('layouts.app')

@section('title', 'Vendor Login')

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
                                <i class="ti ti-building-store"></i>
                            </div>
                            <h3 class="auth-title">Vendor Login</h3>
                            <p class="auth-subtitle">Access your vendor dashboard and products</p>
                        </div>

                        <!-- Login Type Switcher -->
                        <div class="login-switcher">
                            <a href="{{ route('login') }}" class="switch-btn customer-switch">
                                <i class="ti ti-shopping-cart me-2"></i>
                                <span>Customer Login</span>
                            </a>
                            <a href="{{ route('affiliate.login') }}" class="switch-btn affiliate-switch">
                                <i class="ti ti-crown me-2"></i>
                                <span>Affiliate Login</span>
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-modern animate__animated animate__fadeInDown">
                                <i class="ti ti-check-circle me-2"></i>
                                <div><strong>Success!</strong> {{ session('success') }}</div>
                            </div>
                        @endif

                        @if(session('info'))
                            <div class="alert alert-info alert-modern">
                                <i class="ti ti-info-circle me-2"></i>
                                <div>{{ session('info') }}</div>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-modern">
                                <i class="ti ti-alert-circle me-2"></i>
                                <div>
                                    @foreach($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('vendor.login.submit') }}" method="POST" class="auth-form">
                            @csrf

                            <div class="form-floating mb-4">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" placeholder="Enter your email"
                                       value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <label for="email">
                                    <i class="ti ti-mail me-2"></i>Email Address
                                </label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-4">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" placeholder="Enter password" required>
                                <label for="password">
                                    <i class="ti ti-lock me-2"></i>Password
                                </label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check-wrapper mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                           {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Remember me
                                    </label>
                                </div>
                                <a href="{{ route('password.request') }}" class="forgot-link">
                                    Forgot Password?
                                </a>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
                                <i class="ti ti-login me-2"></i>
                                Sign In to Dashboard
                            </button>
                        </form>

                        <!-- Register Link -->
                        <div class="text-center">
                            <p class="register-text">
                                Don't have a vendor account?
                                <a href="{{ route('vendor.register') }}" class="register-link">Apply Here</a>
                            </p>
                        </div>

                        <!-- Info Message -->
                        <div class="alert alert-warning alert-modern mt-4">
                            <i class="ti ti-info-circle me-2"></i>
                            <div>
                                <strong>Note:</strong> Vendor accounts are currently managed by administrators.
                                Please <a href="{{ route('contact.show') }}" class="alert-link">contact us</a> for vendor partnership opportunities.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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

    // Show SweetAlert for logout success message
    @if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Logged Out Successfully!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Login Again',
            allowOutsideClick: true,
            timer: 6000,
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

    @if(session('info'))
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Information',
            text: '{{ session('info') }}',
            icon: 'info',
            confirmButtonColor: '#17a2b8',
            confirmButtonText: 'Got it!',
            timer: 5000,
            timerProgressBar: true
        });
    });
    @endif

    @if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Login Error',
            html: '<ul style="text-align: left; margin: 0; padding-left: 20px;">' + 
                  @json($errors->all()).map(error => '<li>' + error + '</li>').join('') + 
                  '</ul>',
            icon: 'error',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Try Again',
            customClass: {
                popup: 'swal-wide'
            }
        }).then(() => {
            // Focus on the first input with error
            const errorInput = document.querySelector('.is-invalid');
            if (errorInput) {
                errorInput.focus();
            }
        });
    });
    @endif

    // Enhanced form submission with loading state
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.querySelector('.auth-form');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                const submitBtn = loginForm.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="ti ti-loader-2 me-2"></i>Signing in...';
                    submitBtn.disabled = true;
                }
                
                // Show loading alert after 2 seconds if form is still processing
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        Swal.fire({
                            title: 'Signing In...',
                            text: 'Please wait while we verify your credentials',
                            icon: 'info',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    }
                }, 2000);
            });
        }
    });
</script>
@endsection
