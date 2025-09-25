@extends('layouts.app')

@section('title', 'Affiliate Registration')

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
                            <h3 class="auth-title">Join as Affiliate</h3>
                            <p class="auth-subtitle">Start your journey to financial freedom</p>
                        </div>

                        <!-- Registration Type Switcher -->
                        <div class="login-switcher">
                            <a href="{{ route('affiliate.login') }}" class="switch-btn affiliate-switch">
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

                        <form method="POST" action="{{ route('affiliate.register.submit') }}" class="auth-form" id="registrationForm">
                            @csrf

                            <!-- Step 1: Sponsor Information -->
                            <div class="form-step active" id="step1">
                                <h5 class="step-title">
                                    <i class="ti ti-users me-2"></i>
                                    Sponsor Information
                                </h5>
                                <p class="step-subtitle">Enter your sponsor details to join the network</p>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Sponsor ID <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="ti ti-user-check"></i>
                                                </span>
                                                <input type="text"
                                                       class="form-control @error('sponsor_id') is-invalid @enderror"
                                                       name="sponsor_id"
                                                       id="sponsor_id"
                                                       value="{{ old('sponsor_id', request('ref')) }}"
                                                       placeholder="Enter sponsor ID, username or referral hash"
                                                       required>
                                                <button type="button" class="btn btn-outline-secondary" id="validateSponsor">
                                                    <i class="ti ti-search"></i>
                                                </button>
                                            </div>
                                            <div id="sponsor-validation" class="form-text"></div>
                                            @error('sponsor_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Position <span class="text-danger">*</span></label>
                                            <select class="form-select @error('position') is-invalid @enderror" name="position" id="position" required>
                                                <option value="">Select Position</option>
                                                <option value="left" {{ old('position') == 'left' ? 'selected' : '' }}>Left</option>
                                                <option value="right" {{ old('position') == 'right' ? 'selected' : '' }}>Right</option>
                                            </select>
                                            @error('position')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Placement Type</label>
                                            <div class="form-check-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="placement" id="placement_auto" value="auto" {{ old('placement', 'auto') == 'auto' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="placement_auto">
                                                        <strong>Auto Placement</strong>
                                                        <small class="d-block text-muted">System will automatically place you in the best available position</small>
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="placement" id="placement_manual" value="manual" {{ old('placement') == 'manual' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="placement_manual">
                                                        <strong>Manual Placement</strong>
                                                        <small class="d-block text-muted">Choose your exact position in the network</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6" id="uplineUsernameField" style="display: none;">
                                        <div class="form-group">
                                            <label class="form-label">Upline Username <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="ti ti-user-check"></i>
                                                </span>
                                                <input type="text"
                                                       class="form-control @error('upline_username') is-invalid @enderror"
                                                       name="upline_username"
                                                       id="upline_username"
                                                       value="{{ old('upline_username') }}"
                                                       placeholder="Enter upline username">
                                                <div class="input-group-text">
                                                    <div class="spinner-border spinner-border-sm d-none" id="uplineLoader"></div>
                                                    <i class="ti ti-check text-success d-none" id="uplineValid"></i>
                                                    <i class="ti ti-x text-danger d-none" id="uplineInvalid"></i>
                                                </div>
                                            </div>
                                            <div id="upline-feedback" class="form-text"></div>
                                            @error('upline_username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="placement-preview" id="placementPreview" style="display: none;">
                                            <div class="alert alert-info">
                                                <i class="ti ti-info-circle me-2"></i>
                                                <span id="placementMessage"></span>
                                            </div>
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

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="ti ti-lock-check"></i>
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
                                        <i class="ti ti-arrow-left me-1"></i> Back
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
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="ti ti-user"></i>
                                                </span>
                                                <input type="text"
                                                       class="form-control @error('firstname') is-invalid @enderror"
                                                       name="firstname"
                                                       value="{{ old('firstname') }}"
                                                       placeholder="Enter your first name"
                                                       required>
                                            </div>
                                            @error('firstname')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="ti ti-user"></i>
                                                </span>
                                                <input type="text"
                                                       class="form-control @error('lastname') is-invalid @enderror"
                                                       name="lastname"
                                                       value="{{ old('lastname') }}"
                                                       placeholder="Enter your last name"
                                                       required>
                                            </div>
                                            @error('lastname')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="ti ti-phone"></i>
                                                </span>
                                                <input type="tel"
                                                       class="form-control @error('phone') is-invalid @enderror"
                                                       name="phone"
                                                       value="{{ old('phone') }}"
                                                       placeholder="Enter your phone number"
                                                       required>
                                            </div>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Country</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="ti ti-world"></i>
                                                </span>
                                                <select class="form-select @error('country') is-invalid @enderror" name="country">
                                                    <option value="">Select Country</option>
                                                    <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>United States</option>
                                                    <option value="CA" {{ old('country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                                    <option value="GB" {{ old('country') == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                                    <option value="AU" {{ old('country') == 'AU' ? 'selected' : '' }}>Australia</option>
                                                    <option value="BD" {{ old('country') == 'BD' ? 'selected' : '' }}>Bangladesh</option>
                                                    <option value="IN" {{ old('country') == 'IN' ? 'selected' : '' }}>India</option>
                                                    <!-- Add more countries as needed -->
                                                </select>
                                            </div>
                                            @error('country')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="ti ti-map-pin"></i>
                                        </span>
                                        <textarea class="form-control @error('address') is-invalid @enderror"
                                                  name="address"
                                                  rows="3"
                                                  placeholder="Enter your complete address">{{ old('address') }}</textarea>
                                    </div>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input @error('terms') is-invalid @enderror"
                                               type="checkbox"
                                               name="terms"
                                               id="terms"
                                               required>
                                        <label class="form-check-label" for="terms">
                                            I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>
                                        </label>
                                        @error('terms')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="marketing" id="marketing" value="1" {{ old('marketing') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="marketing">
                                            I want to receive marketing emails and promotional offers
                                        </label>
                                    </div>
                                </div>

                                <div class="form-navigation">
                                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="2">
                                        <i class="ti ti-arrow-left me-1"></i> Back
                                    </button>
                                    <button type="submit" class="btn btn-success" id="submitBtn">
                                        <i class="ti ti-check me-1"></i> Complete Registration
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
.auth-wrapper {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.auth-bg-shapes {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

.shape-1, .shape-2, .shape-3 {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    width: 80px;
    height: 80px;
    top: 20%;
    left: 10%;
    animation-delay: -2s;
}

.shape-2 {
    width: 120px;
    height: 120px;
    top: 60%;
    right: 10%;
    animation-delay: -4s;
}

.shape-3 {
    width: 60px;
    height: 60px;
    bottom: 20%;
    left: 20%;
    animation-delay: -1s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.auth-logo .logo-img {
    height: 60px;
    filter: brightness(0) invert(1);
}

.auth-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
    z-index: 10;
}

.card-header {
    background: transparent;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    padding: 2rem 2rem 1rem;
}

.auth-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2rem;
    color: white;
}

.auth-title {
    color: #2d3748;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.auth-subtitle {
    color: #718096;
    margin-bottom: 0;
}

.login-switcher {
    margin-top: 1.5rem;
    text-align: center;
}

.switch-btn {
    display: inline-flex;
    align-items: center;
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.switch-btn:hover {
    color: #764ba2;
    transform: translateY(-1px);
}

/* Registration Steps */
.registration-steps {
    margin-top: 2rem;
    padding: 0 1rem;
}

.step-container {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
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
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid #e2e8f0;
}

.step.active .step-number {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #667eea;
}

.step.completed .step-number {
    background: #48bb78;
    color: white;
    border-color: #48bb78;
}

.step-label {
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: #718096;
    font-weight: 500;
    text-align: center;
}

.step.active .step-label {
    color: #667eea;
    font-weight: 600;
}

.step-divider {
    flex: 1;
    height: 2px;
    background: #e2e8f0;
    margin: 0 1rem;
    position: relative;
    top: -10px;
}

.step.completed + .step-divider {
    background: #48bb78;
}

/* Form Steps */
.form-step {
    display: none;
    animation: fadeInUp 0.5s ease;
}

.form-step.active {
    display: block;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

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
    font-size: 0.95rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.input-group .form-control {
    border-left: none;
}

.input-group-text {
    background: #f7fafc;
    border-right: none;
    color: #667eea;
}

.form-control {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-select {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.75rem 1rem;
}

.form-check-group {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-check {
    padding: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.form-check:hover {
    border-color: #667eea;
    background: #f7fafc;
}

.form-check-input:checked + .form-check-label {
    color: #667eea;
}

/* Sponsor Info Card */
.sponsor-info-card {
    background: #f7fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
}

.sponsor-avatar {
    width: 60px;
    height: 60px;
    margin: 0 auto 1rem;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #667eea;
}

.sponsor-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.sponsor-details h6 {
    margin-bottom: 0.25rem;
    color: #2d3748;
}

/* Validation Messages */
.form-text.text-success {
    color: #48bb78 !important;
}

.form-text.text-danger {
    color: #f56565 !important;
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

.strength-weak .strength-fill {
    width: 33%;
    background: #f56565;
}

.strength-medium .strength-fill {
    width: 66%;
    background: #ed8936;
}

.strength-strong .strength-fill {
    width: 100%;
    background: #48bb78;
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
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}

.btn-success {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    border: none;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(72, 187, 120, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .card-header {
        padding: 1.5rem 1rem;
    }

    .step-container {
        flex-direction: column;
        gap: 1rem;
    }

    .step-divider {
        width: 2px;
        height: 30px;
        margin: 0;
        top: 0;
    }

    .form-navigation {
        flex-direction: column;
        gap: 1rem;
    }

    .form-navigation .btn {
        width: 100%;
    }
}

/* Loading States */
.btn.loading {
    position: relative;
    color: transparent;
}

.btn.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 20px;
    height: 20px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: translate(-50%, -50%) rotate(360deg);
    }
}

/* Custom Alert Styles */
.alert-modern {
    border: none;
    border-radius: 12px;
    border-left: 4px solid;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
}

.alert-success {
    border-left-color: #48bb78;
    background: rgba(72, 187, 120, 0.1);
}

.alert-danger {
    border-left-color: #f56565;
    background: rgba(245, 101, 101, 0.1);
}

.alert-info {
    border-left-color: #4299e1;
    background: rgba(66, 153, 225, 0.1);
}

.alert-warning {
    border-left-color: #ed8936;
    background: rgba(237, 137, 54, 0.1);
}

.logo-img {
    height: 50px;
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
}

.auth-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    position: relative;
    z-index: 10;
    overflow: hidden;
}

.card-header {
    background: transparent;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    padding: 2rem 2rem 1rem;
}

.auth-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
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

.auth-title {
    color: #2d3748;
    font-weight: 700;
    margin-bottom: 0.5rem;
    font-size: 1.75rem;
}

.auth-subtitle {
    color: #718096;
    margin-bottom: 0;
    font-size: 1rem;
}

.login-switcher {
    margin-top: 1.5rem;
    text-align: center;
}

.switch-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background: rgba(102, 126, 234, 0.1);
    border: 1px solid rgba(102, 126, 234, 0.2);
    border-radius: 10px;
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.switch-btn:hover {
    background: rgba(102, 126, 234, 0.2);
    color: #5a67d8;
    transform: translateY(-2px);
}

.card-body {
    padding: 2rem;
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.form-section:last-of-type {
    border-bottom: none;
    margin-bottom: 1rem;
}

.section-title {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    font-size: 1rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    color: #4a5568;
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.input-group-text {
    background: rgba(102, 126, 234, 0.1);
    border: 1px solid rgba(0, 0, 0, 0.1);
    color: #667eea;
}

.form-control {
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 0 10px 10px 0;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.input-group .form-control:first-child {
    border-radius: 10px 0 0 10px;
}

.custom-checkbox .form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.btn-auth {
    width: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 12px;
    padding: 0.875rem 1.5rem;
    font-weight: 600;
    font-size: 1rem;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    margin-top: 1rem;
}

.btn-auth:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}

.btn-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.6s;
}

.btn-auth:hover .btn-shine {
    left: 100%;
}

.auth-links {
    margin-top: 2rem;
    text-align: center;
}

.auth-links a {
    text-decoration: none;
    transition: all 0.3s ease;
}

.auth-links a:hover {
    color: #5a67d8 !important;
}

.alert-modern {
    border: none;
    border-radius: 10px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: flex-start;
}

.alert-danger {
    background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%);
    color: #c53030;
}

.alert-success {
    background: linear-gradient(135deg, #c6f6d5 0%, #9ae6b4 100%);
    color: #2d7738;
}

.benefits-section {
    position: relative;
    z-index: 10;
}

.benefit-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 15px;
    padding: 1.5rem 1rem;
    text-align: center;
    transition: all 0.3s ease;
}

.benefit-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.benefit-icon {
    width: 50px;
    height: 50px;
    margin: 0 auto 1rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.benefit-icon i {
    font-size: 1.5rem;
    color: white;
}

.benefit-card h6 {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.benefit-card p {
    color: #718096;
    font-size: 0.9rem;
    margin-bottom: 0;
}

.form-text {
    font-size: 0.85rem;
    color: #718096;
    margin-top: 0.25rem;
}

@media (max-width: 768px) {
    .card-header, .card-body {
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

    .benefits-section {
        margin-top: 2rem;
    }

    .benefit-card {
        margin-bottom: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Multi-step form functionality
    let currentStep = 1;
    const totalSteps = 3;

    // Step navigation
    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.form-step').forEach(el => {
            el.classList.remove('active');
        });

        // Show current step
        document.getElementById(`step${step}`).classList.add('active');

        // Update step indicators
        updateStepIndicators(step);

        currentStep = step;
    }

    function updateStepIndicators(step) {
        document.querySelectorAll('.step').forEach((el, index) => {
            const stepNumber = index + 1;
            el.classList.remove('active', 'completed');

            if (stepNumber < step) {
                el.classList.add('completed');
            } else if (stepNumber === step) {
                el.classList.add('active');
            }
        });
    }

    // Next step buttons
    document.querySelectorAll('.next-step').forEach(btn => {
        btn.addEventListener('click', function() {
            const nextStep = parseInt(this.dataset.next);
            if (validateCurrentStep()) {
                showStep(nextStep);
            }
        });
    });

    // Previous step buttons
    document.querySelectorAll('.prev-step').forEach(btn => {
        btn.addEventListener('click', function() {
            const prevStep = parseInt(this.dataset.prev);
            showStep(prevStep);
        });
    });

    // Validate current step
    function validateCurrentStep() {
        const currentStepEl = document.getElementById(`step${currentStep}`);
        const requiredFields = currentStepEl.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        return isValid;
    }

    // Real-time validation

    // Sponsor validation
    let sponsorValidationTimeout;
    document.getElementById('sponsor_id').addEventListener('input', function() {
        clearTimeout(sponsorValidationTimeout);
        sponsorValidationTimeout = setTimeout(() => {
            validateSponsor(this.value);
        }, 500);
    });

    document.getElementById('validateSponsor').addEventListener('click', function() {
        const sponsorId = document.getElementById('sponsor_id').value;
        if (sponsorId.trim()) {
            validateSponsor(sponsorId);
        }
    });

    function validateSponsor(sponsorId) {
        if (!sponsorId.trim()) return;

        const validationDiv = document.getElementById('sponsor-validation');
        const sponsorInfo = document.getElementById('sponsorInfo');

        validationDiv.innerHTML = '<i class="ti ti-loader-2 ti-spin"></i> Validating sponsor...';
        validationDiv.className = 'form-text text-info';

        // Simulate API call
        fetch(`/api/validate-sponsor?sponsor_id=${encodeURIComponent(sponsorId)}`)
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    validationDiv.innerHTML = '<i class="ti ti-check"></i> Valid sponsor found!';
                    validationDiv.className = 'form-text text-success';

                    // Show sponsor info
                    document.getElementById('sponsorName').textContent = data.sponsor.name;
                    document.getElementById('sponsorUsername').textContent = `@${data.sponsor.username}`;
                    document.getElementById('sponsorAvatar').src = data.sponsor.avatar || '/assets/img/default-avatar.svg';
                    sponsorInfo.style.display = 'block';

                    // Check position availability after validating sponsor
                    checkPositionAvailability();
                } else {
                    validationDiv.innerHTML = '<i class="ti ti-x"></i> Invalid sponsor ID/username/hash';
                    validationDiv.className = 'form-text text-danger';
                    sponsorInfo.style.display = 'none';
                }
            })
            .catch(error => {
                validationDiv.innerHTML = '<i class="ti ti-alert-circle"></i> Error validating sponsor';
                validationDiv.className = 'form-text text-danger';
                sponsorInfo.style.display = 'none';
            });
    }

    // Username validation
    let usernameValidationTimeout;
    document.getElementById('username').addEventListener('input', function() {
        clearTimeout(usernameValidationTimeout);
        usernameValidationTimeout = setTimeout(() => {
            validateUsername(this.value);
        }, 500);
    });

    document.getElementById('checkUsername').addEventListener('click', function() {
        const username = document.getElementById('username').value;
        if (username.trim()) {
            validateUsername(username);
        }
    });

    function validateUsername(username) {
        if (!username.trim()) return;

        const validationDiv = document.getElementById('username-validation');
        validationDiv.innerHTML = '<i class="ti ti-loader-2 ti-spin"></i> Checking availability...';
        validationDiv.className = 'form-text text-info';

        // Simulate API call
        fetch(`/api/check-username?username=${encodeURIComponent(username)}`)
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    validationDiv.innerHTML = '<i class="ti ti-check"></i> Username is available!';
                    validationDiv.className = 'form-text text-success';
                } else {
                    validationDiv.innerHTML = '<i class="ti ti-x"></i> Username is already taken';
                    validationDiv.className = 'form-text text-danger';
                }
            })
            .catch(error => {
                validationDiv.innerHTML = '<i class="ti ti-alert-circle"></i> Error checking username';
                validationDiv.className = 'form-text text-danger';
            });
    }

    // Email validation
    let emailValidationTimeout;
    document.getElementById('email').addEventListener('input', function() {
        clearTimeout(emailValidationTimeout);
        emailValidationTimeout = setTimeout(() => {
            validateEmail(this.value);
        }, 500);
    });

    document.getElementById('checkEmail').addEventListener('click', function() {
        const email = document.getElementById('email').value;
        if (email.trim()) {
            validateEmail(email);
        }
    });

    function validateEmail(email) {
        if (!email.trim()) return;

        const validationDiv = document.getElementById('email-validation');
        validationDiv.innerHTML = '<i class="ti ti-loader-2 ti-spin"></i> Checking email...';
        validationDiv.className = 'form-text text-info';

        // Basic email format validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            validationDiv.innerHTML = '<i class="ti ti-x"></i> Invalid email format';
            validationDiv.className = 'form-text text-danger';
            return;
        }

        // Simulate API call
        fetch(`/api/check-email?email=${encodeURIComponent(email)}`)
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    validationDiv.innerHTML = '<i class="ti ti-check"></i> Email is available!';
                    validationDiv.className = 'form-text text-success';
                } else {
                    validationDiv.innerHTML = '<i class="ti ti-x"></i> Email is already registered';
                    validationDiv.className = 'form-text text-danger';
                }
            })
            .catch(error => {
                validationDiv.innerHTML = '<i class="ti ti-alert-circle"></i> Error checking email';
                validationDiv.className = 'form-text text-danger';
            });
    }

    // Password strength
    document.getElementById('password').addEventListener('input', function() {
        checkPasswordStrength(this.value);
        checkPasswordMatch();
    });

    document.getElementById('password_confirmation').addEventListener('input', function() {
        checkPasswordMatch();
    });

    function checkPasswordStrength(password) {
        const strengthDiv = document.getElementById('passwordStrength');

        if (!password) {
            strengthDiv.innerHTML = '';
            return;
        }

        let strength = 0;
        let feedback = [];

        // Length check
        if (password.length >= 8) strength++;
        else feedback.push('At least 8 characters');

        // Uppercase check
        if (/[A-Z]/.test(password)) strength++;
        else feedback.push('One uppercase letter');

        // Lowercase check
        if (/[a-z]/.test(password)) strength++;
        else feedback.push('One lowercase letter');

        // Number check
        if (/\d/.test(password)) strength++;
        else feedback.push('One number');

        // Special character check
        if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;
        else feedback.push('One special character');

        let strengthClass = '';
        let strengthText = '';

        if (strength <= 2) {
            strengthClass = 'strength-weak';
            strengthText = 'Weak';
        } else if (strength <= 3) {
            strengthClass = 'strength-medium';
            strengthText = 'Medium';
        } else {
            strengthClass = 'strength-strong';
            strengthText = 'Strong';
        }

        strengthDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small>Password strength: <span class="${strengthClass === 'strength-strong' ? 'text-success' : strengthClass === 'strength-medium' ? 'text-warning' : 'text-danger'}">${strengthText}</span></small>
            </div>
            <div class="strength-bar ${strengthClass}">
                <div class="strength-fill"></div>
            </div>
            ${feedback.length > 0 ? `<small class="text-muted">Missing: ${feedback.join(', ')}</small>` : ''}
        `;
    }

    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const matchDiv = document.getElementById('password-match');

        if (!confirmPassword) {
            matchDiv.innerHTML = '';
            return;
        }

        if (password === confirmPassword) {
            matchDiv.innerHTML = '<i class="ti ti-check"></i> Passwords match';
            matchDiv.className = 'form-text text-success';
        } else {
            matchDiv.innerHTML = '<i class="ti ti-x"></i> Passwords do not match';
            matchDiv.className = 'form-text text-danger';
        }
    }

    // Password toggle
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const targetInput = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (targetInput.type === 'password') {
                targetInput.type = 'text';
                icon.className = 'ti ti-eye-off';
            } else {
                targetInput.type = 'password';
                icon.className = 'ti ti-eye';
            }
        });
    });

    // Placement type change
    document.querySelectorAll('input[name="placement"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const positionSelect = document.getElementById('position');
            const uplineField = document.getElementById('uplineUsernameField');
            const uplineInput = document.getElementById('upline_username');
            const placementPreview = document.getElementById('placementPreview');
            const placementMessage = document.getElementById('placementMessage');

            if (this.value === 'auto') {
                // Auto placement logic
                uplineField.style.display = 'none';
                uplineInput.required = false;
                uplineInput.value = '';

                // Position must still be selected for auto placement
                positionSelect.disabled = false;
                positionSelect.required = true;

                // Show placement preview and check availability
                updatePlacementPreview();
                checkPositionAvailability();

            } else if (this.value === 'manual') {
                // Manual placement logic
                uplineField.style.display = 'block';
                uplineInput.required = true;
                positionSelect.disabled = false;
                positionSelect.required = true;

                // Show placement preview and check availability
                updatePlacementPreview();
                checkPositionAvailability();
            }
        });
    });

    // Position change handler with availability checking
    document.getElementById('position').addEventListener('change', function() {
        updatePlacementPreview();
        checkPositionAvailability();
    });

    // Update placement preview message
    function updatePlacementPreview() {
        const position = document.getElementById('position').value;
        const placement = document.querySelector('input[name="placement"]:checked')?.value;
        const placementPreview = document.getElementById('placementPreview');
        const placementMessage = document.getElementById('placementMessage');

        if (position && placement) {
            placementPreview.style.display = 'block';

            if (placement === 'auto') {
                if (position === 'left') {
                    placementMessage.innerHTML = '<i class="ti ti-info-circle me-2"></i>Auto Placement: You will be placed on the LEFT side. System will find the best available position.';
                    placementMessage.className = 'mb-0 text-info';
                } else if (position === 'right') {
                    placementMessage.innerHTML = '<i class="ti ti-info-circle me-2"></i>Auto Placement: You will be placed on the RIGHT side. System will find the best available position.';
                    placementMessage.className = 'mb-0 text-info';
                }
            } else if (placement === 'manual') {
                if (position === 'left') {
                    placementMessage.innerHTML = '<i class="ti ti-info-circle me-2"></i>Manual Placement: You will be placed on the LEFT side under your specified upline username.';
                    placementMessage.className = 'mb-0 text-info';
                } else if (position === 'right') {
                    placementMessage.innerHTML = '<i class="ti ti-info-circle me-2"></i>Manual Placement: You will be placed on the RIGHT side under your specified upline username.';
                    placementMessage.className = 'mb-0 text-info';
                }
            }
        } else {
            placementPreview.style.display = 'none';
        }
    }

    // Check position availability
    function checkPositionAvailability() {
        const position = document.getElementById('position').value;
        const placement = document.querySelector('input[name="placement"]:checked')?.value;
        const sponsorId = document.getElementById('sponsor_id').value;
        const uplineUsername = document.getElementById('upline_username').value;
        const placementMessage = document.getElementById('placementMessage');

        if (!position) return;

        if (placement === 'auto' && sponsorId) {
            // Check auto placement availability
            fetch('/api/check-auto-placement-availability', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    sponsor_username: sponsorId,
                    position: position
                })
            })
            .then(response => response.json())
            .then(data => {
                const placementPreview = document.getElementById('placementPreview');
                placementPreview.style.display = 'block';

                if (data.available) {
                    if (data.placement_type === 'direct') {
                        placementMessage.innerHTML = `<i class="ti ti-check-circle me-2"></i><strong>Available:</strong> ${data.message}`;
                        placementMessage.className = 'mb-0 text-success';
                    } else {
                        placementMessage.innerHTML = `<i class="ti ti-info-circle me-2"></i><strong>Auto Placement:</strong> ${data.message}`;
                        placementMessage.className = 'mb-0 text-warning';
                    }
                } else {
                    placementMessage.innerHTML = `<i class="ti ti-x-circle me-2"></i><strong>Not Available:</strong> ${data.message}`;
                    placementMessage.className = 'mb-0 text-danger';
                }
            })
            .catch(error => {
                console.error('Error checking auto placement:', error);
            });

        } else if (placement === 'manual' && uplineUsername && position) {
            // Check manual placement availability
            fetch('/api/check-position-availability', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    upline_username: uplineUsername,
                    position: position
                })
            })
            .then(response => response.json())
            .then(data => {
                const placementPreview = document.getElementById('placementPreview');
                placementPreview.style.display = 'block';

                if (data.available) {
                    placementMessage.innerHTML = `<i class="ti ti-check-circle me-2"></i><strong>Available:</strong> ${data.message}`;
                    placementMessage.className = 'mb-0 text-success';
                } else {
                    placementMessage.innerHTML = `<i class="ti ti-x-circle me-2"></i><strong>Position Taken:</strong> ${data.message}`;
                    placementMessage.className = 'mb-0 text-danger';

                    if (data.occupied_by) {
                        placementMessage.innerHTML += `<br><small>Occupied by: <strong>${data.occupied_by.username}</strong> (${data.occupied_by.name}) - Joined: ${data.occupied_by.joined_at}</small>`;
                    }
                }
            })
            .catch(error => {
                console.error('Error checking position availability:', error);
            });
        }
    }

    // Upline username validation (for manual placement)
    let uplineValidationTimeout;
    document.getElementById('upline_username').addEventListener('input', function() {
        const username = this.value.trim();
        const loader = document.getElementById('uplineLoader');
        const valid = document.getElementById('uplineValid');
        const invalid = document.getElementById('uplineInvalid');
        const feedback = document.getElementById('upline-feedback');

        // Clear previous timeout
        if (uplineValidationTimeout) {
            clearTimeout(uplineValidationTimeout);
        }

        // Reset icons
        loader.classList.add('d-none');
        valid.classList.add('d-none');
        invalid.classList.add('d-none');
        feedback.textContent = '';

        if (username.length >= 3) {
            loader.classList.remove('d-none');

            uplineValidationTimeout = setTimeout(() => {
                // Validate upline username
                fetch(`/api/validate-upline-username`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ username: username })
                })
                .then(response => response.json())
                .then(data => {
                    loader.classList.add('d-none');

                    if (data.valid) {
                        valid.classList.remove('d-none');
                        feedback.textContent = `✓ Valid upline: ${data.upline.name}`;
                        feedback.className = 'form-text text-success';
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');

                        // Check position availability after validating upline
                        checkPositionAvailability();
                    } else {
                        invalid.classList.remove('d-none');
                        feedback.textContent = data.message || 'Invalid upline username';
                        feedback.className = 'form-text text-danger';
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                    }
                })
                .catch(error => {
                    loader.classList.add('d-none');
                    invalid.classList.remove('d-none');
                    feedback.textContent = 'Error validating upline username';
                    feedback.className = 'form-text text-danger';
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                });
            }, 500);
        }
    });

    // Form validation enhancement
    function validateCurrentStep() {
        const currentStepEl = document.getElementById(`step${currentStep}`);
        const requiredFields = currentStepEl.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Additional validation for step 1
        if (currentStep === 1) {
            const position = document.getElementById('position').value;
            const placement = document.querySelector('input[name="placement"]:checked')?.value;
            const uplineUsername = document.getElementById('upline_username').value;

            // Position is always required
            if (!position) {
                document.getElementById('position').classList.add('is-invalid');
                isValid = false;
            }

            // Upline username required for manual placement
            if (placement === 'manual' && !uplineUsername.trim()) {
                document.getElementById('upline_username').classList.add('is-invalid');
                isValid = false;
            }
        }

        return isValid;
    }

    // Form submission with position availability validation
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate all steps first
        if (!validateCurrentStep()) {
            alert('Please fill in all required fields correctly.');
            return;
        }

        // Check position availability before submission
        const position = document.getElementById('position').value;
        const placement = document.querySelector('input[name="placement"]:checked')?.value;
        const sponsorId = document.getElementById('sponsor_id').value;
        const uplineUsername = document.getElementById('upline_username').value;

        if (!position) {
            alert('Please select a position (Left or Right).');
            return;
        }

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Checking availability...';

        // Final position availability check
        let apiEndpoint = '';
        let apiData = {};

        if (placement === 'auto') {
            apiEndpoint = '/api/check-auto-placement-availability';
            apiData = {
                sponsor_username: sponsorId,
                position: position
            };
        } else if (placement === 'manual') {
            if (!uplineUsername) {
                alert('Please enter an upline username for manual placement.');
                resetSubmitButton();
                return;
            }
            apiEndpoint = '/api/check-position-availability';
            apiData = {
                upline_username: uplineUsername,
                position: position
            };
        }

        fetch(apiEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(apiData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.available) {
                // Position is available, proceed with registration
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating account...';

                setTimeout(() => {
                    this.submit();
                }, 1000);
            } else {
                // Position is not available
                alert(`Position not available: ${data.message}\n\nPlease choose a different position or upline.`);
                resetSubmitButton();

                // Refresh the placement preview to show current status
                checkPositionAvailability();
            }
        })
        .catch(error => {
            console.error('Error checking position availability:', error);
            alert('Error checking position availability. Please try again.');
            resetSubmitButton();
        });

        function resetSubmitButton() {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span class="btn-shine"></span>Create Account';
        }
    });

    // Initialize
    showStep(1);
});
</script>
@endpush
