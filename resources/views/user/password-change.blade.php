@extends('layouts.app')

@section('title', 'Change Password - ' . config('app.name'))

@push('styles')
<style>
.password-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
}

.form-floating > .form-control {
    padding: 1rem 0.75rem;
}

.form-floating > label {
    padding: 1rem 0.75rem;
}

.password-strength {
    margin-top: 0.5rem;
}

.strength-meter {
    height: 4px;
    background: #e9ecef;
    border-radius: 2px;
    overflow: hidden;
}

.strength-fill {
    height: 100%;
    transition: all 0.3s ease;
    width: 0%;
}

.strength-weak {
    background: #dc3545;
    width: 25%;
}

.strength-fair {
    background: #fd7e14;
    width: 50%;
}

.strength-good {
    background: #ffc107;
    width: 75%;
}

.strength-strong {
    background: #28a745;
    width: 100%;
}

.password-requirements {
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

.requirement {
    display: flex;
    align-items: center;
    margin-bottom: 0.25rem;
}

.requirement.met {
    color: #28a745;
}

.requirement.unmet {
    color: #6c757d;
}

.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    z-index: 10;
}

.password-toggle:hover {
    color: #495057;
}
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary me-3">
                    <i class="ti ti-arrow-left me-1"></i>Back
                </a>
                <h2 class="h3 mb-0">Change Password</h2>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti ti-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-exclamation-triangle me-2"></i>Please correct the errors below.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Password Change Form -->
            <div class="password-card card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ti ti-key me-2"></i>Change Your Password
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.password.update') }}" method="POST" id="passwordForm">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div class="form-floating mb-3 position-relative">
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" placeholder="Current Password" required>
                            <label for="current_password">Current Password</label>
                            <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                <i class="ti ti-eye" id="current_password_icon"></i>
                            </button>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="form-floating mb-3 position-relative">
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" name="new_password" placeholder="New Password" 
                                   required oninput="checkPasswordStrength(this.value)">
                            <label for="new_password">New Password</label>
                            <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                <i class="ti ti-eye" id="new_password_icon"></i>
                            </button>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Password Strength Meter -->
                            <div class="password-strength">
                                <div class="strength-meter">
                                    <div class="strength-fill" id="strengthMeter"></div>
                                </div>
                                <small class="text-muted" id="strengthText">Enter a password</small>
                            </div>
                        </div>

                        <!-- Password Requirements -->
                        <div class="password-requirements mb-3">
                            <small class="text-muted d-block mb-2">Password must contain:</small>
                            <div class="requirement unmet" id="req-length">
                                <i class="ti ti-circle-check me-2"></i>At least 8 characters
                            </div>
                            <div class="requirement unmet" id="req-uppercase">
                                <i class="ti ti-circle-check me-2"></i>One uppercase letter
                            </div>
                            <div class="requirement unmet" id="req-lowercase">
                                <i class="ti ti-circle-check me-2"></i>One lowercase letter
                            </div>
                            <div class="requirement unmet" id="req-number">
                                <i class="ti ti-circle-check me-2"></i>One number
                            </div>
                            <div class="requirement unmet" id="req-special">
                                <i class="ti ti-circle-check me-2"></i>One special character (!@#$%^&*)
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-floating mb-4 position-relative">
                            <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                   id="new_password_confirmation" name="new_password_confirmation" 
                                   placeholder="Confirm New Password" required oninput="checkPasswordMatch()">
                            <label for="new_password_confirmation">Confirm New Password</label>
                            <button type="button" class="password-toggle" onclick="togglePassword('new_password_confirmation')">
                                <i class="ti ti-eye" id="new_password_confirmation_icon"></i>
                            </button>
                            @error('new_password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback" id="passwordMatchError" style="display: none;">
                                Passwords do not match
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                <i class="ti ti-check me-1"></i>Change Password
                            </button>
                            <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-x me-1"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Tips -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ti ti-shield-check me-2"></i>Security Tips
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="ti ti-check text-success me-2"></i>
                            Use a unique password that you don't use elsewhere
                        </li>
                        <li class="mb-2">
                            <i class="ti ti-check text-success me-2"></i>
                            Avoid personal information in your password
                        </li>
                        <li class="mb-2">
                            <i class="ti ti-check text-success me-2"></i>
                            Consider using a password manager
                        </li>
                        <li class="mb-0">
                            <i class="ti ti-check text-success me-2"></i>
                            Change your password regularly
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'ti ti-eye-off';
    } else {
        field.type = 'password';
        icon.className = 'ti ti-eye';
    }
}

function checkPasswordStrength(password) {
    const meter = document.getElementById('strengthMeter');
    const text = document.getElementById('strengthText');
    
    // Requirements
    const hasLength = password.length >= 8;
    const hasUppercase = /[A-Z]/.test(password);
    const hasLowercase = /[a-z]/.test(password);
    const hasNumber = /\d/.test(password);
    const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    
    // Update requirement indicators
    updateRequirement('req-length', hasLength);
    updateRequirement('req-uppercase', hasUppercase);
    updateRequirement('req-lowercase', hasLowercase);
    updateRequirement('req-number', hasNumber);
    updateRequirement('req-special', hasSpecial);
    
    // Calculate strength
    const score = [hasLength, hasUppercase, hasLowercase, hasNumber, hasSpecial].filter(Boolean).length;
    
    // Update meter and text
    meter.className = 'strength-fill';
    
    if (password.length === 0) {
        text.textContent = 'Enter a password';
        meter.style.width = '0%';
    } else if (score < 3) {
        text.textContent = 'Weak password';
        meter.classList.add('strength-weak');
    } else if (score < 4) {
        text.textContent = 'Fair password';
        meter.classList.add('strength-fair');
    } else if (score < 5) {
        text.textContent = 'Good password';
        meter.classList.add('strength-good');
    } else {
        text.textContent = 'Strong password';
        meter.classList.add('strength-strong');
    }
    
    checkFormValidity();
}

function updateRequirement(reqId, met) {
    const element = document.getElementById(reqId);
    element.className = met ? 'requirement met' : 'requirement unmet';
}

function checkPasswordMatch() {
    const password = document.getElementById('new_password').value;
    const confirm = document.getElementById('new_password_confirmation').value;
    const errorDiv = document.getElementById('passwordMatchError');
    const confirmField = document.getElementById('new_password_confirmation');
    
    if (confirm.length > 0 && password !== confirm) {
        errorDiv.style.display = 'block';
        confirmField.classList.add('is-invalid');
    } else {
        errorDiv.style.display = 'none';
        confirmField.classList.remove('is-invalid');
    }
    
    checkFormValidity();
}

function checkFormValidity() {
    const currentPassword = document.getElementById('current_password').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;
    const submitBtn = document.getElementById('submitBtn');
    
    // Check all requirements
    const hasLength = newPassword.length >= 8;
    const hasUppercase = /[A-Z]/.test(newPassword);
    const hasLowercase = /[a-z]/.test(newPassword);
    const hasNumber = /\d/.test(newPassword);
    const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(newPassword);
    const passwordsMatch = newPassword === confirmPassword;
    
    const allValid = currentPassword.length > 0 && 
                     hasLength && hasUppercase && hasLowercase && 
                     hasNumber && hasSpecial && passwordsMatch;
    
    submitBtn.disabled = !allValid;
}

document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners
    document.getElementById('current_password').addEventListener('input', checkFormValidity);
    document.getElementById('new_password_confirmation').addEventListener('input', checkPasswordMatch);
    
    console.log('Password change page loaded successfully');
});
</script>
@endpush
