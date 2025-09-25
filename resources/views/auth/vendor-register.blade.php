@extends('layouts.app')

@section('title', 'Vendor Registration')

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

                @auth
                <!-- Authenticated User - Show Vendor Application Form -->
                <div class="auth-card">
                    <div class="card-header">
                        <div class="text-center">
                            <div class="auth-icon">
                                <i class="ti ti-building-store"></i>
                            </div>
                            <h3 class="auth-title">Become a Vendor</h3>
                            <p class="auth-subtitle">Start selling your products on our platform</p>
                        </div>

                        <!-- User Info Display -->
                        <div class="current-user-info">
                            <div class="alert alert-success alert-modern">
                                <i class="ti ti-user-check me-2"></i>
                                <div>
                                    <strong>Welcome back, {{ Auth::user()->name }}!</strong><br>
                                    <small>You're applying as: {{ Auth::user()->email }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-modern">
                                <i class="ti ti-check-circle me-2"></i>
                                <div>{{ session('success') }}</div>
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

                        <form action="{{ route('vendor.register.submit') }}" method="POST" class="auth-form">
                            @csrf

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('business_name') is-invalid @enderror"
                                       id="business_name" name="business_name" placeholder="Business Name"
                                       value="{{ old('business_name') }}" required>
                                <label for="business_name">
                                    <i class="ti ti-building me-2"></i>Business Name
                                </label>
                                @error('business_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('contact_person') is-invalid @enderror"
                                       id="contact_person" name="contact_person" placeholder="Contact Person"
                                       value="{{ old('contact_person', Auth::user()->name) }}" required>
                                <label for="contact_person">
                                    <i class="ti ti-user me-2"></i>Contact Person Name
                                </label>
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" placeholder="Business Email"
                                       value="{{ old('email', Auth::user()->email) }}" required readonly>
                                <label for="email">
                                    <i class="ti ti-mail me-2"></i>Business Email
                                </label>
                                <small class="text-muted">Using your registered email address</small>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" placeholder="Phone Number"
                                       value="{{ old('phone') }}" required>
                                <label for="phone">
                                    <i class="ti ti-phone me-2"></i>Phone Number
                                </label>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <textarea class="form-control @error('business_description') is-invalid @enderror"
                                          id="business_description" name="business_description"
                                          placeholder="Business Description" style="height: 100px" required>{{ old('business_description') }}</textarea>
                                <label for="business_description">
                                    <i class="ti ti-file-text me-2"></i>Business Description
                                </label>
                                @error('business_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-4">
                                <input type="url" class="form-control @error('website') is-invalid @enderror"
                                       id="website" name="website" placeholder="Website (Optional)"
                                       value="{{ old('website') }}">
                                <label for="website">
                                    <i class="ti ti-world me-2"></i>Website (Optional)
                                </label>
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
                                <i class="ti ti-send me-2"></i>
                                Submit Vendor Application
                            </button>
                        </form>

                        <!-- Logout Option -->
                        <div class="text-center">
                            <p class="register-text">
                                Not you?
                                <a href="{{ route('logout') }}" class="register-link"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                   Logout and use different account
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </p>
                        </div>

                        <!-- Application Process Info -->
                        <div class="alert alert-warning alert-modern mt-4">
                            <i class="ti ti-clock me-2"></i>
                            <div>
                                <strong>Application Process:</strong> All vendor applications are reviewed by our team.
                                You will receive an email with further instructions within 24-48 hours.
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <!-- Guest User - Require Customer Registration First -->
                <div class="auth-card">
                    <div class="card-header">
                        <div class="text-center">
                            <div class="auth-icon">
                                <i class="ti ti-user-exclamation"></i>
                            </div>
                            <h3 class="auth-title">Customer Account Required</h3>
                            <p class="auth-subtitle">Please register as a customer first before applying to become a vendor</p>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-info alert-modern mb-4">
                            <i class="ti ti-info-circle me-2"></i>
                            <div>
                                <strong>Why do I need a customer account?</strong><br>
                                <small>
                                    • We need to verify your basic information<br>
                                    • You'll understand our platform better as a customer<br>
                                    • Your vendor account will be linked to your customer profile<br>
                                    • This helps us provide better support and service
                                </small>
                            </div>
                        </div>

                        <div class="vendor-application-steps">
                            <h6 class="mb-3">Simple 2-Step Process:</h6>

                            <div class="step-item mb-3">
                                <div class="step-number">1</div>
                                <div class="step-content">
                                    <h6>Register as Customer</h6>
                                    <p class="mb-0">Create your basic account with email and password</p>
                                </div>
                            </div>

                            <div class="step-item mb-4">
                                <div class="step-number">2</div>
                                <div class="step-content">
                                    <h6>Apply for Vendor</h6>
                                    <p class="mb-0">Submit your business information for review</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                                <i class="ti ti-user-plus me-2"></i>
                                Register as Customer First
                            </a>

                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                                <i class="ti ti-login me-2"></i>
                                Already have account? Login
                            </a>
                        </div>

                        <!-- Back to Home -->
                        <div class="text-center mt-4">
                            <a href="{{ route('home') }}" class="register-link">
                                <i class="ti ti-arrow-left me-1"></i>
                                Back to Home
                            </a>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- Additional Styles for Steps -->
<style>
.vendor-application-steps .step-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.step-number {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    flex-shrink: 0;
}

.step-content h6 {
    color: #2d3748;
    margin-bottom: 0.25rem;
}

.step-content p {
    color: #718096;
    font-size: 0.875rem;
}

.current-user-info {
    margin-top: 1rem;
}

.auth-icon i.ti-user-exclamation {
    color: #f56565;
}
</style>
@endsection
