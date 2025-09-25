@extends('layouts.app')

@section('title', 'Settings')
@section('description', 'User Settings and Preferences')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">User Settings</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Profile Settings -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="ti ti-user-circle" style="font-size: 3rem; color: #007bff;"></i>
                                    <h5 class="mt-3">Profile Settings</h5>
                                    <p class="text-muted">Update your personal information and profile details</p>
                                    <a href="{{ route('user.profile') }}" class="btn btn-primary">
                                        <i class="ti ti-edit"></i> Edit Profile
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Account Security -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="ti ti-shield-lock" style="font-size: 3rem; color: #28a745;"></i>
                                    <h5 class="mt-3">Account Security</h5>
                                    <p class="text-muted">Change password and security settings</p>
                                    <a href="{{ route('password.request') }}" class="btn btn-success">
                                        <i class="ti ti-key"></i> Change Password
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Order History -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="ti ti-package" style="font-size: 3rem; color: #ffc107;"></i>
                                    <h5 class="mt-3">Order History</h5>
                                    <p class="text-muted">View your past orders and track current ones</p>
                                    <a href="{{ route('orders.index') }}" class="btn btn-warning">
                                        <i class="ti ti-list"></i> View Orders
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Wishlist -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="ti ti-heart" style="font-size: 3rem; color: #dc3545;"></i>
                                    <h5 class="mt-3">Wishlist</h5>
                                    <p class="text-muted">Manage your favorite products and wishlist</p>
                                    <a href="{{ route('wishlist.grid') }}" class="btn btn-danger">
                                        <i class="ti ti-heart"></i> View Wishlist
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Notifications -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="ti ti-bell" style="font-size: 3rem; color: #17a2b8;"></i>
                                    <h5 class="mt-3">Notifications</h5>
                                    <p class="text-muted">Manage your notification preferences</p>
                                    <a href="#" class="btn btn-info" onclick="alert('Notification settings coming soon!')">
                                        <i class="ti ti-settings"></i> Notification Settings
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Support -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="ti ti-help-circle" style="font-size: 3rem; color: #6f42c1;"></i>
                                    <h5 class="mt-3">Help & Support</h5>
                                    <p class="text-muted">Get help and contact customer support</p>
                                    <a href="{{ route('contact.show') }}" class="btn btn-secondary">
                                        <i class="ti ti-message"></i> Contact Support
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Role Upgrade Section (Only for customers) -->
                        @auth
                        @if(auth()->user()->role == 'customer')
                        <!-- Become a Vendor -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="ti ti-store" style="font-size: 3rem; color: #ff6b35;"></i>
                                    <h5 class="mt-3">Become a Vendor</h5>
                                    <p class="text-muted">Sell your products on our platform and grow your business</p>
                                    <button class="btn btn-warning" onclick="requestVendorRole()">
                                        <i class="ti ti-store"></i> Request Vendor Access
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Become an Affiliate -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="ti ti-users" style="font-size: 3rem; color: #20bf6b;"></i>
                                    <h5 class="mt-3">Become an Affiliate</h5>
                                    <p class="text-muted">Earn commissions by referring customers and building your network</p>
                                    <button class="btn btn-success" onclick="requestAffiliateRole()">
                                        <i class="ti ti-users"></i> Become Affiliate Now
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endauth
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Quick Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                                            <i class="ti ti-home"></i> Home
                                        </a>
                                        <a href="{{ route('categories.index') }}" class="btn btn-outline-primary btn-sm">
                                            <i class="ti ti-category"></i> Browse Categories
                                        </a>
                                        <a href="{{ route('cart.index') }}" class="btn btn-outline-primary btn-sm">
                                            <i class="ti ti-shopping-cart"></i> View Cart
                                        </a>
                                        @auth
                                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="ti ti-logout"></i> Logout
                                            </button>
                                        </form>
                                        @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-success btn-sm">
                                            <i class="ti ti-login"></i> Login
                                        </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Role Upgrade Modals -->
@auth
@if(auth()->user()->role == 'customer')

<!-- Vendor Application Modal -->
<div class="modal fade" id="vendorApplicationModal" tabindex="-1" aria-labelledby="vendorApplicationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vendorApplicationModalLabel">
                    <i class="ti ti-store me-2"></i>Become a Vendor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="vendorApplicationForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Vendor Benefits:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Sell unlimited products</li>
                            <li>Access to vendor dashboard</li>
                            <li>Manage your own shop</li>
                            <li>Track sales and earnings</li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="business_name" class="form-label">Business Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="business_name" name="business_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contact_person" class="form-label">Contact Person <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="contact_person" name="contact_person" value="{{ auth()->user()->name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="vendor_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="vendor_email" name="email" value="{{ auth()->user()->email }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="vendor_phone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="vendor_phone" name="phone" value="{{ auth()->user()->phone }}" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="business_description" class="form-label">Business Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="business_description" name="business_description" rows="3" placeholder="Tell us about your business..." required></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="website" class="form-label">Website (Optional)</label>
                            <input type="url" class="form-control" id="website" name="website" placeholder="https://example.com">
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="ti ti-clock me-2"></i>
                        <strong>Review Process:</strong> Your vendor application will be reviewed by our admin team within 2-3 business days. You will receive:
                        <ul class="mt-2 mb-0">
                            <li>📧 <strong>Immediate confirmation email</strong> with your application details</li>
                            <li>📧 <strong>Status update email</strong> once your application is reviewed</li>
                            <li>📞 <strong>Phone call or email</strong> if we need additional information</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-send me-2"></i>Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Affiliate Confirmation Modal -->
<div class="modal fade" id="affiliateConfirmModal" tabindex="-1" aria-labelledby="affiliateConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="affiliateConfirmModalLabel">
                    <i class="ti ti-users me-2"></i>Become an Affiliate
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success">
                    <i class="ti ti-check-circle me-2"></i>
                    <strong>Affiliate Benefits:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Earn commissions on referrals</li>
                        <li>Build your network and team</li>
                        <li>Access to MLM binary system</li>
                        <li>Real-time earnings tracking</li>
                        <li>Marketing tools and resources</li>
                    </ul>
                </div>
                <p class="mb-3">Are you ready to become an affiliate? This will instantly upgrade your account and give you access to our affiliate program.</p>
                
                <div class="alert alert-info">
                    <i class="ti ti-mail me-2"></i>
                    <strong>Email Confirmation:</strong> You will receive a welcome email with your affiliate dashboard access, referral link, and earning guidelines immediately after activation.
                </div>
                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="affiliateTerms" required>
                    <label class="form-check-label" for="affiliateTerms">
                        I agree to the <a href="#" target="_blank">Affiliate Terms and Conditions</a>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="confirmAffiliateUpgrade()" id="confirmAffiliateBtn" disabled>
                    <i class="ti ti-users me-2"></i>Yes, Become Affiliate
                </button>
            </div>
        </div>
    </div>
</div>

@endif
@endauth

<script>
// Role upgrade JavaScript functions
function requestVendorRole() {
    $('#vendorApplicationModal').modal('show');
}

function requestAffiliateRole() {
    $('#affiliateConfirmModal').modal('show');
}

// Enable affiliate confirmation button when terms are accepted
document.addEventListener('DOMContentLoaded', function() {
    const affiliateTermsCheckbox = document.getElementById('affiliateTerms');
    const confirmBtn = document.getElementById('confirmAffiliateBtn');
    
    if (affiliateTermsCheckbox && confirmBtn) {
        affiliateTermsCheckbox.addEventListener('change', function() {
            confirmBtn.disabled = !this.checked;
        });
    }
});

// Handle vendor application form submission
document.getElementById('vendorApplicationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ti ti-loader-2 me-2 spinner-border spinner-border-sm"></i>Submitting...';
    
    fetch('{{ route("settings.vendor-application") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#vendorApplicationModal').modal('hide');
            showAlert('success', 'Application Submitted! 📧', 'Your vendor application has been submitted successfully. You will receive a confirmation email shortly with your application details and next steps.');
            this.reset();
        } else {
            showAlert('error', 'Application Failed', data.message || 'Please check your information and try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Network Error', 'Please check your connection and try again.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Handle affiliate role upgrade
function confirmAffiliateUpgrade() {
    const confirmBtn = document.getElementById('confirmAffiliateBtn');
    const originalText = confirmBtn.innerHTML;
    
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<i class="ti ti-loader-2 me-2 spinner-border spinner-border-sm"></i>Processing...';
    
    fetch('{{ route("settings.become-affiliate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#affiliateConfirmModal').modal('hide');
            showAlert('success', 'Welcome to our Affiliate Program! 🎉📧', 'Congratulations! You are now an affiliate. You will receive a welcome email with your dashboard access and referral link details.');
            
            // Refresh page after 3 seconds to show new role
            setTimeout(() => {
                window.location.reload();
            }, 3000);
        } else {
            showAlert('error', 'Upgrade Failed', data.message || 'Something went wrong. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Network Error', 'Please check your connection and try again.');
    })
    .finally(() => {
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = originalText;
    });
}

// Alert notification function with enhanced styling for important messages
function showAlert(type, title, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const iconClass = type === 'success' ? 'ti-check-circle' : 'ti-alert-circle';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show shadow-lg" role="alert" style="border-radius: 10px; border: none;">
            <i class="ti ${iconClass} me-2" style="font-size: 1.2em;"></i>
            <strong>${title}</strong><br>
            <span style="font-size: 0.95em;">${message}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Find container to show alert (try to find existing alert container or create one)
    let alertContainer = document.querySelector('.alert-container');
    if (!alertContainer) {
        alertContainer = document.createElement('div');
        alertContainer.className = 'alert-container';
        alertContainer.style.position = 'fixed';
        alertContainer.style.top = '20px';
        alertContainer.style.right = '20px';
        alertContainer.style.zIndex = '9999';
        alertContainer.style.maxWidth = '450px';
        alertContainer.style.minWidth = '350px';
        document.body.appendChild(alertContainer);
    }
    
    alertContainer.innerHTML = alertHtml;
    
    // Auto remove alert after longer time for important messages (email confirmations)
    const autoRemoveTime = message.includes('email') || message.includes('📧') ? 8000 : 5000;
    setTimeout(() => {
        const alert = alertContainer.querySelector('.alert');
        if (alert) {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 300);
        }
    }, autoRemoveTime);
}
</script>
@endsection
