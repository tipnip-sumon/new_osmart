@extends('member.layouts.app')

@section('title', auth()->user()->email_verified_at && auth()->user()->ev == 1 ? 'Email Management' : 'Email Verification Required')

@section('content')
<div class="container-fluid my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header {{ (auth()->user()->email_verified_at && auth()->user()->ev == 1) ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                    <h4 class="mb-0">
                        @if(auth()->user()->email_verified_at && auth()->user()->ev == 1)
                            <i class="fas fa-check-circle me-2"></i>
                            Email Management
                        @else
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Email Verification Required
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    @if(auth()->user()->email_verified_at && auth()->user()->ev == 1)
                        <!-- For Verified Users -->
                        <div class="alert alert-success" role="alert">
                            <h5 class="alert-heading">
                                <i class="fas fa-check-circle me-2"></i>
                                Email is verified
                            </h5>
                            <p class="mb-3">
                                Your email address <strong>{{ auth()->user()->email }}</strong> is verified and active. 
                                You have access to all account features including withdrawals.
                            </p>
                            <hr>
                            <p class="mb-0">
                                If you need to change your email address, a ৳50 fee will be charged and you'll need to verify the new email.
                            </p>
                        </div>
                    @else
                        <!-- For Unverified Users -->
                        <div class="alert alert-info" role="alert">
                            <h5 class="alert-heading">
                                <i class="fas fa-envelope-open me-2"></i>
                                Please verify your email address
                            </h5>
                            <p class="mb-3">
                                To access all features of your account, including withdrawals and full account functionality, 
                                you need to verify your email address: <strong>{{ auth()->user()->email }}</strong>
                            </p>
                            <hr>
                            <p class="mb-0">
                                Click the button below to send a verification email, or check your inbox if you've already requested one.
                            </p>
                        </div>
                    @endif

                    <!-- User Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded">
                                <h6 class="text-muted mb-2">Account Information</h6>
                                <p class="mb-1"><strong>Name:</strong> {{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</p>
                                <p class="mb-1"><strong>Email:</strong> {{ auth()->user()->email }}</p>
                                <p class="mb-0"><strong>Member Since:</strong> {{ auth()->user()->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded" id="verificationStats">
                                <h6 class="text-muted mb-2">Verification Status</h6>
                                <div class="d-flex align-items-center mb-2">
                                    @if(auth()->user()->email_verified_at && auth()->user()->ev == 1)
                                        <span class="badge bg-success me-2">Verified</span>
                                        <small class="text-muted">Email is verified</small>
                                    @else
                                        <span class="badge bg-danger me-2">Unverified</span>
                                        <small class="text-muted">Email not verified</small>
                                    @endif
                                </div>
                                <div id="statsContent">
                                    <!-- Stats will be loaded via AJAX -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-4">
                        <button type="button" class="btn btn-primary btn-lg" id="sendVerificationBtn">
                            <i class="fas fa-paper-plane me-2"></i>
                            Send Verification Email
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="checkStatusBtn">
                            <i class="fas fa-sync me-2"></i>
                            Check Status
                        </button>
                    </div>

                    <!-- Status Messages -->
                    <div id="alertContainer"></div>

                    <!-- Email Update Section -->
                    <div class="card border-secondary">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0">
                                Need to update your email address?
                                @if(auth()->user()->email_verified_at && auth()->user()->ev == 1)
                                    <span class="badge bg-warning ms-2">
                                        <i class="fas fa-coins me-1"></i>
                                        ৳50 Fee Required
                                    </span>
                                @else
                                    <span class="badge bg-success ms-2">
                                        <i class="fas fa-free-code-camp me-1"></i>
                                        Free Update
                                    </span>
                                @endif
                            </h6>
                        </div>
                        <div class="card-body">
                            @if(auth()->user()->email_verified_at && auth()->user()->ev == 1)
                                <!-- For Verified Users - Require confirmation and fee -->
                                <div class="alert alert-warning">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Email Change Fee:</strong> Since your current email is verified, a ৳50 fee will be charged for email change. 
                                    You must confirm your current email and provide your password.
                                </div>
                                
                                <form id="updateEmailForm">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="current_email_confirmation" class="form-label">
                                                <i class="fas fa-envelope me-1"></i>
                                                Confirm Current Email
                                            </label>
                                            <input type="email" class="form-control" id="current_email_confirmation" 
                                                   name="current_email_confirmation" 
                                                   placeholder="Type your current email to confirm" required>
                                            <small class="text-muted">Current: {{ auth()->user()->email }}</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="password" class="form-label">
                                                <i class="fas fa-lock me-1"></i>
                                                Your Password
                                            </label>
                                            <input type="password" class="form-control" id="password" name="password" 
                                                   placeholder="Enter your account password" required>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-8">
                                            <label for="new_email" class="form-label">
                                                <i class="fas fa-envelope-open me-1"></i>
                                                New Email Address
                                            </label>
                                            <input type="email" class="form-control" id="new_email" name="new_email" 
                                                   placeholder="Enter your new email address" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Available Balance</label>
                                            <div class="bg-light p-2 rounded text-center">
                                                <strong class="text-success">
                                                    ৳{{ number_format((auth()->user()->deposit_wallet ?? 0) + (auth()->user()->interest_wallet ?? 0) + (auth()->user()->balance ?? 0), 2) }}
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-coins me-2"></i>
                                            Update Email (Pay ৳50 Fee)
                                        </button>
                                    </div>
                                </form>
                            @else
                                <!-- For Unverified Users - Free update -->
                                <div class="alert alert-success">
                                    <i class="fas fa-gift me-2"></i>
                                    <strong>Free Email Update:</strong> Since your email is not verified yet, you can update it without any fee.
                                </div>
                                
                                <form id="updateEmailForm">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="new_email" class="form-label">New Email Address</label>
                                                <input type="email" class="form-control" id="new_email" name="new_email" 
                                                       placeholder="Enter your new email address" required>
                                                <small class="text-muted">Current: {{ auth()->user()->email }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 d-flex align-items-end">
                                            <button type="submit" class="btn btn-success w-100">
                                                <i class="fas fa-edit me-2"></i>
                                                Update Email (Free)
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Help Section -->
                    <div class="mt-4">
                        <h6 class="text-muted">Having trouble?</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check-circle text-success me-2"></i>Check your spam/junk folder</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Make sure your email address is correct</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Wait a few minutes between resend attempts</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Contact support if you continue having issues</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function() {
    let isLoading = false;

    // Load verification stats on page load
    loadVerificationStats();

    // Send verification email
    $('#sendVerificationBtn').on('click', function() {
        if (isLoading) return;
        
        sendVerificationEmail();
    });

    // Check verification status
    $('#checkStatusBtn').on('click', function() {
        if (isLoading) return;
        
        checkVerificationStatus();
    });

    // Update email address
    $('#updateEmailForm').on('submit', function(e) {
        e.preventDefault();
        if (isLoading) return;
        
        updateEmailAddress();
    });

    function sendVerificationEmail() {
        isLoading = true;
        const btn = $('#sendVerificationBtn');
        const originalText = btn.html();
        
        btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Sending...').prop('disabled', true);
        
        $.ajax({
            url: '{{ route("member.email.verify.send") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .done(function(response) {
            console.log('Email send response:', response);
            if (response.success) {
                showAlert('success', response.message);
                loadVerificationStats();
                
                if (response.redirect) {
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 2000);
                }
            } else {
                showAlert('danger', response.message || 'Failed to send verification email');
            }
        })
        .fail(function(xhr) {
            console.error('Email send failed:', xhr);
            let errorMessage = 'Failed to send verification email';
            
            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
            } else if (xhr.responseText) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMessage = response.message;
                    }
                } catch (e) {
                    errorMessage = 'Server error occurred. Please try again.';
                }
            }
            
            showAlert('danger', errorMessage);
        })
        .always(function() {
            btn.html(originalText).prop('disabled', false);
            isLoading = false;
        });
    }

    function checkVerificationStatus() {
        isLoading = true;
        const btn = $('#checkStatusBtn');
        const originalText = btn.html();
        
        btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Checking...').prop('disabled', true);
        
        $.get('{{ route("member.email.verify.status") }}')
        .done(function(response) {
            if (response.verified) {
                showAlert('success', 'Your email has been verified! Redirecting to dashboard...');
                setTimeout(function() {
                    window.location.href = '{{ route("member.dashboard") }}';
                }, 2000);
            } else {
                showAlert('info', 'Email is still not verified. Please check your inbox.');
                loadVerificationStats();
            }
        })
        .fail(function() {
            showAlert('danger', 'Failed to check verification status');
        })
        .always(function() {
            btn.html(originalText).prop('disabled', false);
            isLoading = false;
        });
    }

    function updateEmailAddress() {
        isLoading = true;
        const newEmail = $('#new_email').val();
        const btn = $('#updateEmailForm button[type="submit"]');
        const originalText = btn.html();
        
        // Collect form data based on user verification status
        const isVerified = {{ auth()->user()->email_verified_at && auth()->user()->ev == 1 ? 'true' : 'false' }};
        let formData = {
            _token: '{{ csrf_token() }}',
            new_email: newEmail
        };
        
        if (isVerified) {
            const currentEmailConfirmation = $('#current_email_confirmation').val();
            const password = $('#password').val();
            
            if (!currentEmailConfirmation || !password) {
                showAlert('danger', 'Please fill in all required fields for verified email change.');
                isLoading = false;
                return;
            }
            
            // Validate current email confirmation
            if (currentEmailConfirmation !== '{{ auth()->user()->email }}') {
                showAlert('danger', 'Current email confirmation does not match your registered email.');
                isLoading = false;
                return;
            }
            
            formData.current_email_confirmation = currentEmailConfirmation;
            formData.password = password;
            
            // Show fee confirmation for verified users
            if (!confirm('This will charge ৳50 from your wallet balance. Do you want to continue?')) {
                isLoading = false;
                return;
            }
        }
        
        btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...').prop('disabled', true);
        
        $.post('{{ route("member.email.update") }}', formData)
        .done(function(response) {
            if (response.success) {
                showAlert('success', response.message);
                if (response.fee_charged && response.fee_charged > 0) {
                    showAlert('info', `৳${response.fee_charged} has been deducted from your wallet balance.`);
                }
                setTimeout(function() {
                    window.location.reload();
                }, 3000);
            } else {
                showAlert('danger', response.message || 'Failed to update email address');
            }
        })
        .fail(function(xhr) {
            let errorMessage = 'Failed to update email address';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors).flat().join(', ');
            }
            showAlert('danger', errorMessage);
        })
        .always(function() {
            btn.html(originalText).prop('disabled', false);
            isLoading = false;
        });
    }

    function loadVerificationStats() {
        $.get('{{ route("member.email.stats") }}')
        .done(function(response) {
            let lastAttempt = 'Never';
            if (response.last_attempt && response.last_attempt !== 'Never' && response.last_attempt !== 'No recent attempts') {
                // If it's a date string, format it, otherwise use as is
                if (response.last_attempt.includes('Recently')) {
                    lastAttempt = response.last_attempt;
                } else {
                    try {
                        lastAttempt = new Date(response.last_attempt).toLocaleString();
                    } catch (e) {
                        lastAttempt = response.last_attempt;
                    }
                }
            } else {
                lastAttempt = response.last_attempt || 'Never';
            }
            
            let statsHtml = `
                <small class="text-muted d-block">Emails sent today: <strong>${response.attempts_today || 0}</strong></small>
                <small class="text-muted d-block">Last sent: <strong>${lastAttempt}</strong></small>
            `;
            
            if (response.rate_limited) {
                statsHtml += `<small class="text-warning d-block">Rate limited: ${response.reset_in_seconds}s remaining</small>`;
            }
            
            $('#statsContent').html(statsHtml);
        })
        .fail(function(xhr) {
            console.error('Stats loading failed:', xhr);
            $('#statsContent').html('<small class="text-muted">Unable to load stats</small>');
        });
    }

    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('#alertContainer').html(alertHtml);
        
        // Auto-hide success alerts after 5 seconds
        if (type === 'success') {
            setTimeout(function() {
                $('.alert-success').fadeOut();
            }, 5000);
        }
    }
});
</script>
@endpush
