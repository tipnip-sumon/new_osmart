@extends('layouts.app')

@section('title', 'Register - ' . config('app.name'))

@section('content')
<div class="auth-wrapper">
    <!-- Background Elements -->
    <div class="auth-bg-shapes">
        <div class="shape-1"></div>
        <div class="shape-2"></div>
        <div class="shape-3"></div>
    </div>

    <div class="container-fluid">
        <div class="row min-vh-100 align-items-center justify-content-center py-4">
            <div class="col-12 col-sm-10 col-md-8 col-lg-10 col-xl-8 col-xxl-7">
                <!-- Logo Section -->
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}" class="auth-logo">
                        <img src="{{ asset('assets/images/logo/osmart-logo-transparent.svg') }}" alt="{{ siteName() }}" class="logo-img">
                    </a>
                </div>

                <!-- Registration Card -->
                <div class="auth-card">
                    <div class="card-header">
                        <div class="text-center">
                            <div class="auth-icon">
                                <i class="ti ti-user-plus"></i>
                            </div>
                            <h3 class="auth-title">Join Our Platform</h3>
                            <p class="auth-subtitle">Create your account to get started</p>
                        </div>

                        <!-- Login Link -->
                        <div class="login-switcher">
                            <a href="{{ route('login') }}" class="switch-btn">
                                <i class="ti ti-login me-2"></i>
                                <span>Already have account?</span>
                            </a>
                        </div>

                        <!-- Registration Steps Progress -->
                        <div class="registration-steps">
                            <div class="step-container">
                                <div class="step active" data-step="1">
                                    <div class="step-number">1</div>
                                    <div class="step-label">Sponsor Info</div>
                                </div>
                                <div class="step-divider"></div>
                                <div class="step" data-step="2">
                                    <div class="step-number">2</div>
                                    <div class="step-label">Account Details</div>
                                </div>
                                <div class="step-divider"></div>
                                <div class="step" data-step="3">
                                    <div class="step-number">3</div>
                                    <div class="step-label">Personal Info</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Real-time Validation Alerts -->
                        <div id="validation-alerts"></div>

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
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" class="auth-form" id="registrationForm">
                            @csrf

                            <!-- Step 1: Sponsor Information -->
                            <div class="form-step active" id="step1">
                                <h5 class="step-title">
                                    <i class="ti ti-users me-2"></i>
                                    Sponsor Information
                                </h5>
                                <p class="step-subtitle">Enter your sponsor details (optional)</p>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Sponsor ID</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="ti ti-user-check"></i>
                                                </span>
                                                <input type="text"
                                                       class="form-control @error('sponsor_id') is-invalid @enderror"
                                                       name="sponsor_id"
                                                       id="sponsor_id"
                                                       value="{{ old('sponsor_id', request('ref')) }}"
                                                       placeholder="Enter sponsor ID or username (optional)">
                                                <button type="button" class="btn btn-outline-secondary" id="validateSponsor">
                                                    <i class="ti ti-search"></i>
                                                </button>
                                            </div>
                                            <div id="sponsor-validation" class="form-text text-muted">
                                                Leave empty to be assigned to default sponsor
                                            </div>
                                            @error('sponsor_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Position</label>
                                            <select class="form-select @error('position') is-invalid @enderror" name="position" id="position">
                                                <option value="">Auto Select (Recommended)</option>
                                                <option value="left" {{ old('position') == 'left' ? 'selected' : '' }}>Left</option>
                                                <option value="right" {{ old('position') == 'right' ? 'selected' : '' }}>Right</option>
                                            </select>
                                            <div class="form-text text-muted">
                                                System will automatically choose best position if not selected
                                            </div>
                                            @error('position')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="sponsor-info-card" id="sponsorInfo" style="display: none;">
                                            <div class="sponsor-avatar">
                                                <img src="" alt="Sponsor Avatar" id="sponsorAvatar">
                                            </div>
                                            <div class="sponsor-details">
                                                <h6 id="sponsorName">-</h6>
                                                <p class="text-muted mb-1" id="sponsorUsername">-</p>
                                                <span class="badge bg-success" id="sponsorStatus">Verified</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-navigation">
                                    <button type="button" class="btn btn-primary next-step" data-next="2">
                                        Continue <i class="ti ti-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 2: Account Details -->
                            <div class="form-step" id="step2">
                                <h5 class="step-title">
                                    <i class="ti ti-user me-2"></i>
                                    Account Details
                                </h5>
                                <p class="step-subtitle">Create your account credentials</p>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Username <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="ti ti-at"></i>
                                                </span>
                                                <input type="text"
                                                       class="form-control @error('username') is-invalid @enderror"
                                                       name="username"
                                                       id="username"
                                                       value="{{ old('username') }}"
                                                       placeholder="Choose a unique username"
                                                       required>
                                                <button type="button" class="btn btn-outline-secondary" id="checkUsername">
                                                    <i class="ti ti-check"></i>
                                                </button>
                                            </div>
                                            <div id="username-validation" class="form-text"></div>
                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="ti ti-mail"></i>
                                                </span>
                                                <input type="email"
                                                       class="form-control @error('email') is-invalid @enderror"
                                                       name="email"
                                                       id="email"
                                                       value="{{ old('email') }}"
                                                       placeholder="Enter your email address"
                                                       required>
                                                <button type="button" class="btn btn-outline-secondary" id="checkEmail">
                                                    <i class="ti ti-check"></i>
                                                </button>
                                            </div>
                                            <div id="email-validation" class="form-text"></div>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="ti ti-phone"></i>
                                                </span>
                                                <input type="text"
                                                       class="form-control @error('phone') is-invalid @enderror"
                                                       name="phone"
                                                       id="phone"
                                                       value="{{ old('phone') }}"
                                                       placeholder="Enter your mobile number"
                                                       required>
                                                <button type="button" class="btn btn-outline-secondary" id="checkPhone">
                                                    <i class="ti ti-check"></i>
                                                </button>
                                            </div>
                                            <div id="phone-validation" class="form-text"></div>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Password <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="ti ti-lock"></i>
                                                </span>
                                                <input type="password"
                                                       class="form-control @error('password') is-invalid @enderror"
                                                       name="password"
                                                       id="password"
                                                       placeholder="Create a strong password"
                                                       required>
                                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                            </div>
                                            <div class="password-strength" id="passwordStrength"></div>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="ti ti-lock"></i>
                                                </span>
                                                <input type="password"
                                                       class="form-control"
                                                       name="password_confirmation"
                                                       id="password_confirmation"
                                                       placeholder="Confirm your password"
                                                       required>
                                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password_confirmation">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                            </div>
                                            <div id="password-match" class="form-text"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-navigation">
                                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="1">
                                        <i class="ti ti-arrow-left me-1"></i> Previous
                                    </button>
                                    <button type="button" class="btn btn-primary next-step" data-next="3">
                                        Continue <i class="ti ti-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 3: Personal Information -->
                            <div class="form-step" id="step3">
                                <h5 class="step-title">
                                    <i class="ti ti-id me-2"></i>
                                    Personal Information
                                </h5>
                                <p class="step-subtitle">Complete your profile information</p>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('firstname') is-invalid @enderror"
                                                   name="firstname"
                                                   id="firstname"
                                                   value="{{ old('firstname') }}"
                                                   placeholder="Enter your first name"
                                                   required>
                                            @error('firstname')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('lastname') is-invalid @enderror"
                                                   name="lastname"
                                                   id="lastname"
                                                   value="{{ old('lastname') }}"
                                                   placeholder="Enter your last name"
                                                   required>
                                            @error('lastname')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Country</label>
                                            <select class="form-select @error('country') is-invalid @enderror" name="country" id="country">
                                                <option value="">Select Country</option>
                                                <option value="BD" {{ old('country') == 'BD' ? 'selected' : '' }}>Bangladesh</option>
                                                <option value="IN" {{ old('country') == 'IN' ? 'selected' : '' }}>India</option>
                                                <option value="PK" {{ old('country') == 'PK' ? 'selected' : '' }}>Pakistan</option>
                                                <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>United States</option>
                                                <option value="UK" {{ old('country') == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                                                <option value="CA" {{ old('country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                                <option value="AU" {{ old('country') == 'AU' ? 'selected' : '' }}>Australia</option>
                                            </select>
                                            @error('country')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror"
                                                      name="address"
                                                      id="address"
                                                      rows="2"
                                                      placeholder="Enter your address">{{ old('address') }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input @error('terms') is-invalid @enderror"
                                                       type="checkbox"
                                                       name="terms"
                                                       id="terms"
                                                       required>
                                                <label class="form-check-label" for="terms">
                                                    I agree to the
                                                    <a href="#" target="_blank" class="text-primary">Terms of Service</a>
                                                    and
                                                    <a href="#" target="_blank" class="text-primary">Privacy Policy</a>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                @error('terms')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-navigation">
                                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="2">
                                        <i class="ti ti-arrow-left me-1"></i> Previous
                                    </button>
                                    <button type="submit" class="btn btn-success btn-lg" id="createAccountBtn">
                                        <i class="ti ti-check me-2"></i>
                                        Create Account
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
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
    position: relative;
    min-height: 100vh;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    overflow: hidden;
}

/* Background Shapes */
.auth-bg-shapes {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
}

.auth-bg-shapes .shape-1 {
    position: absolute;
    top: -10%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

.auth-bg-shapes .shape-2 {
    position: absolute;
    bottom: -15%;
    left: -15%;
    width: 400px;
    height: 400px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
    animation: float 8s ease-in-out infinite reverse;
}

.auth-bg-shapes .shape-3 {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 50%;
    animation: float 10s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

/* Auth Logo */
.auth-logo {
    display: inline-block;
    margin-bottom: 2rem;
}

.logo-img {
    height: 60px;
    filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
}

/* Auth Card */
.auth-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
    z-index: 1;
    overflow: hidden;
}

.auth-card .card-header {
    background: transparent;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    padding: 2rem 2rem 1rem;
}

.auth-card .card-body {
    padding: 2rem;
}

/* Auth Icon */
.auth-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.auth-icon i {
    font-size: 2rem;
    color: white;
}

/* Auth Title */
.auth-title {
    color: #2d3748;
    font-weight: 700;
    margin-bottom: 0.5rem;
    font-size: 1.875rem;
}

.auth-subtitle {
    color: #718096;
    margin-bottom: 0;
    font-size: 1rem;
}

/* Login Switcher */
.login-switcher {
    text-align: center;
    margin: 1.5rem 0;
}

.switch-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
    text-decoration: none;
    border-radius: 25px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 1px solid rgba(102, 126, 234, 0.2);
}

.switch-btn:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

/* Registration Steps */
.registration-steps {
    margin: 2rem 0 1rem;
}

.step-container {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    transition: all 0.3s ease;
    opacity: 0.5;
}

.step.active {
    opacity: 1;
}

.step.completed {
    opacity: 1;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e2e8f0;
    color: #718096;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
}

.step.active .step-number {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
}

.step.completed .step-number {
    background: #48bb78;
    color: white;
}

.step-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #718096;
}

.step.active .step-label {
    color: #2d3748;
    font-weight: 600;
}

.step-divider {
    width: 60px;
    height: 2px;
    background: #e2e8f0;
    margin: 0 1rem;
    flex-shrink: 0;
}

/* Form Steps */
.form-step {
    display: none;
}

.form-step.active {
    display: block;
    animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Step Titles */
.step-title {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.step-subtitle {
    color: #718096;
    margin-bottom: 2rem;
    font-size: 0.9rem;
}

/* Form Groups */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    color: #2d3748;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    background: white;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

.input-group-text {
    background: #f7fafc;
    border: 2px solid #e2e8f0;
    border-right: none;
    color: #718096;
}

.input-group .form-control {
    border-left: none;
}

.input-group .btn {
    border: 2px solid #e2e8f0;
    border-left: none;
}

/* Validation States */
.is-valid {
    border-color: #48bb78;
}

.is-invalid {
    border-color: #f56565;
}

.valid-feedback {
    color: #48bb78;
    font-size: 0.875rem;
}

.invalid-feedback {
    color: #f56565;
    font-size: 0.875rem;
}

/* Password Strength */
.password-strength {
    margin-top: 0.5rem;
}

.strength-bar {
    height: 4px;
    border-radius: 2px;
    background: #e2e8f0;
    overflow: hidden;
}

.strength-fill {
    height: 100%;
    transition: all 0.3s ease;
}

.strength-weak { background: #f56565; width: 25%; }
.strength-fair { background: #ed8936; width: 50%; }
.strength-good { background: #38b2ac; width: 75%; }
.strength-strong { background: #48bb78; width: 100%; }

/* Sponsor Info Card */
.sponsor-info-card {
    background: linear-gradient(135deg, rgba(72, 187, 120, 0.1) 0%, rgba(56, 178, 172, 0.1) 100%);
    border: 1px solid rgba(72, 187, 120, 0.2);
    border-radius: 15px;
    padding: 1rem;
    display: flex;
    align-items: center;
    margin-top: 1rem;
}

.sponsor-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 1rem;
    flex-shrink: 0;
}

.sponsor-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.sponsor-details h6 {
    margin: 0;
    color: #2d3748;
    font-weight: 600;
}

.sponsor-details p {
    margin: 0;
    font-size: 0.875rem;
}

/* Form Navigation */
.form-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e2e8f0;
}

.btn {
    border-radius: 10px;
    padding: 0.75rem 2rem;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.btn-success {
    background: linear-gradient(135deg, #48bb78 0%, #38b2ac 100%);
    color: white;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(72, 187, 120, 0.3);
}

.btn-outline-secondary {
    border: 2px solid #e2e8f0;
    color: #718096;
    background: white;
}

.btn-outline-secondary:hover {
    background: #f7fafc;
    border-color: #cbd5e0;
}

/* Alerts */
.alert-modern {
    border: none;
    border-radius: 10px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
}

.alert-danger {
    background: rgba(245, 101, 101, 0.1);
    color: #c53030;
    border-left: 4px solid #f56565;
}

.alert-success {
    background: rgba(72, 187, 120, 0.1);
    color: #2f855a;
    border-left: 4px solid #48bb78;
}

/* Real-time Validation */
.validation-success {
    color: #48bb78 !important;
}

.validation-error {
    color: #f56565 !important;
}

.validation-loading {
    color: #667eea !important;
}

.validation-loading::before {
    content: "⏳ ";
    animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .auth-card .card-header,
    .auth-card .card-body {
        padding: 1.5rem;
    }

    .step-container {
        flex-direction: column;
        gap: 1rem;
    }

    .step-divider {
        width: 2px;
        height: 30px;
        margin: 0.5rem 0;
    }

    .form-navigation {
        flex-direction: column;
        gap: 1rem;
    }

    .form-navigation .btn {
        width: 100%;
    }

    .logo-img {
        height: 45px;
    }

    .auth-icon {
        width: 60px;
        height: 60px;
    }

    .auth-icon i {
        font-size: 1.5rem;
    }

    .auth-title {
        font-size: 1.5rem;
    }
}

@media (max-width: 576px) {
    .auth-wrapper {
        padding: 1rem;
    }

    .step-label {
        font-size: 0.75rem;
    }

    .step-number {
        width: 35px;
        height: 35px;
        font-size: 0.875rem;
    }
}

/* Registration Confirmation Modal */
.registration-summary {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.registration-summary .row {
    align-items: center;
}

.registration-summary .row:last-child {
    margin-bottom: 0 !important;
}

#registrationLoadingOverlay .loading-content {
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    const steps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.step');
    let currentStep = 1;
    const totalSteps = 3;

    // Initialize first step
    updateStepDisplay();

    // Step Navigation
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('next-step')) {
            const nextStep = parseInt(e.target.dataset.next);
            if (validateCurrentStep()) {
                goToStep(nextStep);
            }
        }

        if (e.target.classList.contains('prev-step')) {
            const prevStep = parseInt(e.target.dataset.prev);
            goToStep(prevStep);
        }
    });

    function goToStep(stepNumber) {
        if (stepNumber >= 1 && stepNumber <= totalSteps) {
            currentStep = stepNumber;
            updateStepDisplay();
        }
    }

    function updateStepDisplay() {
        // Update form steps
        steps.forEach((step, index) => {
            if (index + 1 === currentStep) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });

        // Update progress indicators
        progressSteps.forEach((step, index) => {
            const stepNumber = index + 1;
            if (stepNumber < currentStep) {
                step.classList.add('completed');
                step.classList.remove('active');
            } else if (stepNumber === currentStep) {
                step.classList.add('active');
                step.classList.remove('completed');
            } else {
                step.classList.remove('active', 'completed');
            }
        });
    }

    function validateCurrentStep() {
        const currentStepElement = document.getElementById(`step${currentStep}`);
        const requiredFields = currentStepElement.querySelectorAll('input[required], select[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                showFieldError(field, 'This field is required');
                isValid = false;
            } else {
                clearFieldError(field);
            }
        });

        // Additional validation for specific steps
        if (currentStep === 2) {
            if (!validateEmail()) isValid = false;
            if (!validateUsername()) isValid = false;
            if (!validatePhone()) isValid = false;
            if (!validatePasswordMatch()) isValid = false;
        }

        return isValid;
    }

    function showFieldError(field, message) {
        field.classList.add('is-invalid');
        const feedback = field.parentElement.querySelector('.invalid-feedback') ||
                        field.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = message;
        }
    }

    function clearFieldError(field) {
        field.classList.remove('is-invalid');
    }

    // Real-time validation functions
    function validateEmail() {
        const email = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!email.value.trim()) {
            showValidationMessage('email-validation', '', '');
            return true; // Allow empty during typing
        }

        if (!emailRegex.test(email.value)) {
            showValidationMessage('email-validation', '✗ Please enter a valid email address', 'error');
            email.classList.add('is-invalid');
            email.classList.remove('is-valid');
            return false;
        }

        email.classList.remove('is-invalid');
        return true;
    }

    function validateUsername() {
        const username = document.getElementById('username');
        const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;

        if (!username.value.trim()) {
            showValidationMessage('username-validation', '', '');
            return true; // Allow empty during typing
        }

        if (!usernameRegex.test(username.value)) {
            showValidationMessage('username-validation', '✗ Username must be 3-20 characters (letters, numbers, underscore only)', 'error');
            username.classList.add('is-invalid');
            username.classList.remove('is-valid');
            return false;
        }

        username.classList.remove('is-invalid');
        return true;
    }

    function validatePhone() {
        const phone = document.getElementById('phone');
        const phoneRegex = /^[\+]?[0-9\-\(\)\s]{10,}$/;

        if (!phone.value.trim()) {
            showValidationMessage('phone-validation', '', '');
            return true; // Allow empty during typing
        }

        if (!phoneRegex.test(phone.value)) {
            showValidationMessage('phone-validation', '✗ Please enter a valid phone number', 'error');
            phone.classList.add('is-invalid');
            phone.classList.remove('is-valid');
            return false;
        }

        phone.classList.remove('is-invalid');
        return true;
    }

    function validatePasswordMatch() {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');

        if (password.value !== confirmPassword.value) {
            showFieldError(confirmPassword, 'Passwords do not match');
            return false;
        }

        clearFieldError(confirmPassword);
        return true;
    }

    // Real-time validation events
    document.getElementById('email').addEventListener('blur', validateEmail);
    document.getElementById('username').addEventListener('blur', validateUsername);
    document.getElementById('phone').addEventListener('blur', validatePhone);
    document.getElementById('password_confirmation').addEventListener('input', validatePasswordMatch);

    // Password strength checker
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strengthDiv = document.getElementById('passwordStrength');

        let strength = 0;
        let strengthText = '';
        let strengthClass = '';

        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        switch (strength) {
            case 0:
            case 1:
                strengthText = 'Very Weak';
                strengthClass = 'strength-weak';
                break;
            case 2:
                strengthText = 'Weak';
                strengthClass = 'strength-weak';
                break;
            case 3:
                strengthText = 'Fair';
                strengthClass = 'strength-fair';
                break;
            case 4:
                strengthText = 'Good';
                strengthClass = 'strength-good';
                break;
            case 5:
                strengthText = 'Strong';
                strengthClass = 'strength-strong';
                break;
        }

        strengthDiv.innerHTML = `
            <div class="strength-bar">
                <div class="strength-fill ${strengthClass}"></div>
            </div>
            <small class="text-muted">Password strength: ${strengthText}</small>
        `;
    });

    // Toggle password visibility
    document.addEventListener('click', function(e) {
        if (e.target.closest('.toggle-password')) {
            const button = e.target.closest('.toggle-password');
            const targetId = button.dataset.target;
            const input = document.getElementById(targetId);
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('ti-eye', 'ti-eye-off');
            } else {
                input.type = 'password';
                icon.classList.replace('ti-eye-off', 'ti-eye');
            }
        }
    });

    // Real-time validation timeouts
    let sponsorValidationTimeout;
    let usernameValidationTimeout;
    let emailValidationTimeout;
    let phoneValidationTimeout;

    // Automatic sponsor validation on input
    document.getElementById('sponsor_id').addEventListener('input', function() {
        const sponsorId = this.value.trim();
        
        // Clear previous timeout
        clearTimeout(sponsorValidationTimeout);
        
        if (!sponsorId) {
            showValidationMessage('sponsor-validation', 'Leave empty to be assigned to default sponsor', 'info');
            document.getElementById('sponsorInfo').style.display = 'none';
            return;
        }

        // Show loading after 300ms delay
        sponsorValidationTimeout = setTimeout(() => {
            showValidationMessage('sponsor-validation', 'Validating sponsor...', 'loading');
            
            // AJAX call to validate sponsor using API endpoint
            fetch(`/api/validate-sponsor?sponsor_id=${encodeURIComponent(sponsorId)}`)
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    showValidationMessage('sponsor-validation', 'Sponsor validated successfully!', 'success');
                    document.getElementById('sponsorInfo').style.display = 'flex';
                    document.getElementById('sponsorName').textContent = data.sponsor.name;
                    document.getElementById('sponsorUsername').textContent = '@' + data.sponsor.username;
                    document.getElementById('sponsorAvatar').src = data.sponsor.avatar || '/assets/img/default-avatar.svg';
                } else {
                    showValidationMessage('sponsor-validation', data.message || 'Sponsor not found', 'error');
                    document.getElementById('sponsorInfo').style.display = 'none';
                }
            })
            .catch(error => {
                showValidationMessage('sponsor-validation', 'Error validating sponsor', 'error');
                document.getElementById('sponsorInfo').style.display = 'none';
            });
        }, 500);
    });

    // Automatic username validation on input
    document.getElementById('username').addEventListener('input', function() {
        const username = this.value.trim();
        
        // Clear previous timeout
        clearTimeout(usernameValidationTimeout);
        
        if (!username) {
            showValidationMessage('username-validation', '', '');
            return;
        }

        // Basic format validation first
        if (!validateUsername()) {
            return; // validateUsername() already shows error message
        }

        // Show loading after 500ms delay
        usernameValidationTimeout = setTimeout(() => {
            showValidationMessage('username-validation', 'Checking availability...', 'loading');
            
            // AJAX call to check username using API endpoint
            fetch(`/api/check-username?username=${encodeURIComponent(username)}`)
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    showValidationMessage('username-validation', '✓ Username is available!', 'success');
                    document.getElementById('username').classList.remove('is-invalid');
                    document.getElementById('username').classList.add('is-valid');
                } else {
                    showValidationMessage('username-validation', '✗ Username is already taken', 'error');
                    document.getElementById('username').classList.remove('is-valid');
                    document.getElementById('username').classList.add('is-invalid');
                }
            })
            .catch(error => {
                showValidationMessage('username-validation', 'Error checking username', 'error');
            });
        }, 500);
    });

    // Automatic email validation on input
    document.getElementById('email').addEventListener('input', function() {
        const email = this.value.trim();
        
        // Clear previous timeout
        clearTimeout(emailValidationTimeout);
        
        if (!email) {
            showValidationMessage('email-validation', '', '');
            return;
        }

        // Basic format validation first
        if (!validateEmail()) {
            return; // validateEmail() already shows error message
        }

        // Show loading after 500ms delay
        emailValidationTimeout = setTimeout(() => {
            showValidationMessage('email-validation', 'Checking availability...', 'loading');
            
            // AJAX call to check email using API endpoint
            fetch(`/api/check-email?email=${encodeURIComponent(email)}`)
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    showValidationMessage('email-validation', '✓ Email is available!', 'success');
                    document.getElementById('email').classList.remove('is-invalid');
                    document.getElementById('email').classList.add('is-valid');
                } else {
                    showValidationMessage('email-validation', '✗ Email is already registered', 'error');
                    document.getElementById('email').classList.remove('is-valid');
                    document.getElementById('email').classList.add('is-invalid');
                }
            })
            .catch(error => {
                showValidationMessage('email-validation', 'Error checking email', 'error');
            });
        }, 500);
    });

    // Automatic phone validation on input
    document.getElementById('phone').addEventListener('input', function() {
        const phone = this.value.trim();
        
        // Clear previous timeout
        clearTimeout(phoneValidationTimeout);
        
        if (!phone) {
            showValidationMessage('phone-validation', '', '');
            return;
        }

        // Basic format validation first
        if (!validatePhone()) {
            return; // validatePhone() already shows error message
        }

        // Show loading after 500ms delay
        phoneValidationTimeout = setTimeout(() => {
            showValidationMessage('phone-validation', 'Checking availability...', 'loading');
            
            // AJAX call to check phone using API endpoint
            fetch(`/api/check-phone?phone=${encodeURIComponent(phone)}`)
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    showValidationMessage('phone-validation', '✓ Phone number is available!', 'success');
                    document.getElementById('phone').classList.remove('is-invalid');
                    document.getElementById('phone').classList.add('is-valid');
                } else {
                    showValidationMessage('phone-validation', '✗ Phone number is already registered', 'error');
                    document.getElementById('phone').classList.remove('is-valid');
                    document.getElementById('phone').classList.add('is-invalid');
                }
            })
            .catch(error => {
                showValidationMessage('phone-validation', 'Error checking phone number', 'error');
            });
        }, 500);
    });

    // Keep button click handlers for manual validation (optional)
    document.getElementById('validateSponsor').addEventListener('click', function() {
        document.getElementById('sponsor_id').dispatchEvent(new Event('input'));
    });

    document.getElementById('checkUsername').addEventListener('click', function() {
        document.getElementById('username').dispatchEvent(new Event('input'));
    });

    document.getElementById('checkEmail').addEventListener('click', function() {
        document.getElementById('email').dispatchEvent(new Event('input'));
    });

    document.getElementById('checkPhone').addEventListener('click', function() {
        document.getElementById('phone').dispatchEvent(new Event('input'));
    });

    function showValidationMessage(elementId, message, type) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        // Clear all validation classes
        element.className = 'form-text';
        
        // Add appropriate class based on type
        switch(type) {
            case 'success':
                element.className = 'form-text validation-success';
                break;
            case 'error':
                element.className = 'form-text validation-error';
                break;
            case 'loading':
                element.className = 'form-text validation-loading';
                break;
            case 'info':
                element.className = 'form-text text-muted';
                break;
            default:
                element.className = 'form-text text-muted';
        }
        
        element.textContent = message;
    }

    // Auto-fill name field based on first name and last name
    document.getElementById('firstname').addEventListener('input', updateFullName);
    document.getElementById('lastname').addEventListener('input', updateFullName);

    function updateFullName() {
        const firstname = document.getElementById('firstname').value;
        const lastname = document.getElementById('lastname').value;

        // Create a hidden field for full name if it doesn't exist
        let nameField = document.querySelector('input[name="name"]');
        if (!nameField) {
            nameField = document.createElement('input');
            nameField.type = 'hidden';
            nameField.name = 'name';
            form.appendChild(nameField);
        }

        nameField.value = `${firstname} ${lastname}`.trim();
    }

    // Form submission confirmation
    const submitBtn = document.getElementById('createAccountBtn');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        // Disable submit button to prevent double clicking
        if (submitBtn.disabled) {
            return false;
        }
        
        // Final validation before showing confirmation
        if (!validateCurrentStep()) {
            return false;
        }

        // Disable button temporarily
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Validating...';

        // Get form data for confirmation display
        const firstname = document.getElementById('firstname').value;
        const lastname = document.getElementById('lastname').value;
        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const sponsorId = document.getElementById('sponsor_id').value || 'Auto-assigned';
        const position = document.getElementById('position').value;

        // Re-enable button and show confirmation
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ti ti-check me-2"></i>Create Account';
            
            // Create confirmation dialog
            showRegistrationConfirmation({
                name: `${firstname} ${lastname}`,
                username: username,
                email: email,
                phone: phone,
                sponsor: sponsorId,
                position: position
            });
        }, 800);
    });

    function showRegistrationConfirmation(userData) {
        // Create confirmation modal HTML
        const modalHTML = `
            <div class="modal fade" id="registrationConfirmModal" tabindex="-1" aria-labelledby="registrationConfirmModalLabel" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="registrationConfirmModalLabel">
                                <i class="ti ti-user-check me-2"></i>Confirm Registration
                            </h5>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info mb-4">
                                <i class="ti ti-info-circle me-2"></i>
                                Please confirm your registration details before proceeding.
                            </div>
                            
                            <div class="registration-summary">
                                <div class="row mb-3">
                                    <div class="col-4"><strong>Name:</strong></div>
                                    <div class="col-8">${userData.name}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-4"><strong>Username:</strong></div>
                                    <div class="col-8">${userData.username}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-4"><strong>Email:</strong></div>
                                    <div class="col-8">${userData.email}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-4"><strong>Phone:</strong></div>
                                    <div class="col-8">${userData.phone}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-4"><strong>Sponsor:</strong></div>
                                    <div class="col-8">${userData.sponsor}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-4"><strong>Position:</strong></div>
                                    <div class="col-8">${userData.position}</div>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning">
                                <i class="ti ti-alert-triangle me-2"></i>
                                <strong>Important:</strong> Once you confirm, your account will be created and you cannot change these details easily.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="ti ti-x me-2"></i>Cancel
                            </button>
                            <button type="button" class="btn btn-success" id="confirmRegistrationBtn">
                                <i class="ti ti-check me-2"></i>Yes, Create Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if any
        const existingModal = document.getElementById('registrationConfirmModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Add modal to DOM
        document.body.insertAdjacentHTML('beforeend', modalHTML);

        // Initialize and show modal
        const modal = new bootstrap.Modal(document.getElementById('registrationConfirmModal'));
        modal.show();

        // Handle confirmation
        document.getElementById('confirmRegistrationBtn').addEventListener('click', function() {
            // Show loading state
            this.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Creating Account...';
            this.disabled = true;
            
            // Close modal
            modal.hide();
            
            // Show loading overlay on form
            showLoadingOverlay();
            
            // Submit the form
            setTimeout(() => {
                form.submit();
            }, 500);
        });

        // Clean up modal when closed
        document.getElementById('registrationConfirmModal').addEventListener('hidden.bs.modal', function() {
            // Re-enable submit button if modal was closed without confirming
            const confirmBtn = document.getElementById('confirmRegistrationBtn');
            if (!confirmBtn.disabled || confirmBtn.innerHTML.includes('Creating Account')) {
                // User cancelled, re-enable the form submit button
                const submitBtn = document.getElementById('createAccountBtn');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="ti ti-check me-2"></i>Create Account';
            }
            this.remove();
        });
    }

    function showLoadingOverlay() {
        const overlay = document.createElement('div');
        overlay.id = 'registrationLoadingOverlay';
        overlay.innerHTML = `
            <div class="loading-content">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5>Creating Your Account...</h5>
                <p class="text-muted">Please wait while we set up your account in our system.</p>
            </div>
        `;
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            flex-direction: column;
        `;
        overlay.querySelector('.loading-content').style.cssText = `
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        `;
        
        document.body.appendChild(overlay);
    }
});
</script>
@endpush
