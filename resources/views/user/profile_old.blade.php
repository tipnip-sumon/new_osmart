@extends('layouts.app')

@section('title', 'My Profile - ' . config('app.name'))

@push('styles')
<style>
.profile-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    color: white;
    margin-bottom: 2rem;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    border: 4px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    object-fit: cover;
}

.profile-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.profile-card:hover {
    transform: translateY(-5px);
}

.stat-card {
    text-align: center;
    padding: 1.5rem;
    background: linear-gradient(45deg, #f8f9fa, #e9ecef);
    border-radius: 10px;
    margin-bottom: 1rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
    color: #495057;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    color: #6c757d;
    font-weight: 500;
}

.info-value {
    color: #495057;
    font-weight: 600;
}

.rank-badge {
    background: linear-gradient(45deg, #ff6b6b, #ee5a52);
    color: white;
    padding: 0.3rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

@media (max-width: 768px) {
    .profile-avatar {
        width: 80px;
        height: 80px;
    }
    
    .stat-value {
        font-size: 1.5rem;
    }
    
    .profile-header {
        margin-bottom: 1rem;
    }
}
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Profile Header -->
    <div class="profile-header p-4">
        <div class="row align-items-center">
            <div class="col-auto">
                <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('assets/img/bg-img/default-avatar.png') }}" 
                     alt="Profile" class="profile-avatar">
            </div>
            <div class="col">
                <h2 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h2>
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <span class="rank-badge">{{ $user->rank ?? 'Member' }}</span>
                    <span class="text-white-50">
                        <i class="ti ti-id me-1"></i>ID: #{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="text-white-50">
                        <i class="ti ti-calendar me-1"></i>Joined {{ \Carbon\Carbon::parse($user->created_at)->format('M Y') }}
                    </span>
                </div>
            </div>
            <div class="col-auto d-none d-md-block">
                <a href="#" class="btn btn-light">
                    <i class="ti ti-edit me-1"></i>Edit Profile
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Profile Info -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="profile-card card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ti ti-user me-2"></i>Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <span class="info-label">
                            <i class="ti ti-user me-2"></i>Full Name
                        </span>
                        <span class="info-value">{{ $user->first_name }} {{ $user->last_name }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">
                            <i class="ti ti-at me-2"></i>Username
                        </span>
                        <span class="info-value">{{ $user->username ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">
                            <i class="ti ti-mail me-2"></i>Email Address
                        </span>
                        <span class="info-value">{{ $user->email }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">
                            <i class="ti ti-phone me-2"></i>Phone Number
                        </span>
                        <span class="info-value">{{ $user->phone ?? 'Not provided' }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">
                            <i class="ti ti-map-pin me-2"></i>Address
                        </span>
                        <span class="info-value">{{ $user->address ?? 'Not provided' }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">
                            <i class="ti ti-users me-2"></i>Sponsor
                        </span>
                        <span class="info-value">{{ $user->sponsor ?? 'Direct' }}</span>
                    </div>
                </div>
            </div>

            <!-- Referral Link -->
            <div class="profile-card card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ti ti-link me-2"></i>Referral Link
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Share this link to invite new members and earn commissions:</p>
                    <div class="input-group">
                        <input type="text" class="form-control" 
                               value="{{ url('/register?ref=' . ($user->username ?? $user->id)) }}" 
                               readonly id="referralLink">
                        <button class="btn btn-outline-primary" type="button" onclick="copyReferralLink()">
                            <i class="ti ti-copy me-1"></i>Copy
                        </button>
                    </div>
                    <small class="text-success mt-2 d-none" id="copySuccess">
                        <i class="ti ti-check me-1"></i>Link copied to clipboard!
                    </small>
                </div>
            </div>
        </div>

        <!-- Right Column: Stats & Quick Actions -->
        <div class="col-lg-4">
            <!-- Account Stats -->
            <div class="profile-card card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ti ti-chart-line me-2"></i>Account Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="stat-card">
                        <div class="stat-value">{{ $user->rank ?? 'Member' }}</div>
                        <div class="stat-label">Current Rank</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-value">{{ \Carbon\Carbon::parse($user->created_at)->diffInDays() }}</div>
                        <div class="stat-label">Days Active</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-value">{{ $user->orders_count ?? 0 }}</div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="profile-card card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ti ti-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-primary">
                            <i class="ti ti-edit me-2"></i>Edit Profile
                        </a>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="ti ti-key me-2"></i>Change Password
                        </a>
                        <a href="#" class="btn btn-outline-success">
                            <i class="ti ti-package me-2"></i>Order History
                        </a>
                        <a href="#" class="btn btn-outline-info">
                            <i class="ti ti-wallet me-2"></i>Wallet
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyReferralLink() {
    const linkInput = document.getElementById('referralLink');
    const successMsg = document.getElementById('copySuccess');
    
    linkInput.select();
    linkInput.setSelectionRange(0, 99999); // For mobile devices
    
    navigator.clipboard.writeText(linkInput.value).then(function() {
        successMsg.classList.remove('d-none');
        setTimeout(function() {
            successMsg.classList.add('d-none');
        }, 3000);
    }).catch(function(err) {
        console.error('Failed to copy: ', err);
        // Fallback for older browsers
        document.execCommand('copy');
        successMsg.classList.remove('d-none');
        setTimeout(function() {
            successMsg.classList.add('d-none');
        }, 3000);
    });
}
</script>
@endpush
            <p class="text-muted mb-0">Manage your account information and settings</p>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="dashboard-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Profile Information</h5>
            <button class="btn btn-sm btn-primary" onclick="editProfile()">Edit Profile</button>
        </div>
        <div class="card-body">
            <div class="text-center mb-4">
                <img src="{{ asset('assets/img/bg-img/' . $user->profile_image) }}" alt="Profile" 
                     class="profile-image rounded-circle mb-3" width="100" height="100"
                     onerror="this.src='{{ asset('assets/img/logo.png') }}'">
                <h5 class="mb-1">{{ $user->name }}</h5>
                <span class="rank-badge">{{ $user->rank }}</span>
            </div>
            
            <div class="profile-details">
                <div class="detail-item d-flex justify-content-between py-3 border-bottom">
                    <span class="text-muted">Full Name</span>
                    <span class="fw-medium">{{ $user->name }}</span>
                </div>
                <div class="detail-item d-flex justify-content-between py-3 border-bottom">
                    <span class="text-muted">Email Address</span>
                    <span class="fw-medium">{{ $user->email }}</span>
                </div>
                <div class="detail-item d-flex justify-content-between py-3 border-bottom">
                    <span class="text-muted">Phone Number</span>
                    <span class="fw-medium">{{ $user->phone }}</span>
                </div>
                <div class="detail-item d-flex justify-content-between py-3 border-bottom">
                    <span class="text-muted">Address</span>
                    <span class="fw-medium">{{ $user->address }}</span>
                </div>
                <div class="detail-item d-flex justify-content-between py-3 border-bottom">
                    <span class="text-muted">User ID</span>
                    <span class="fw-medium">{{ $user->user_id }}</span>
                </div>
                <div class="detail-item d-flex justify-content-between py-3 border-bottom">
                    <span class="text-muted">Sponsor</span>
                    <span class="fw-medium">{{ $user->sponsor }}</span>
                </div>
                <div class="detail-item d-flex justify-content-between py-3">
                    <span class="text-muted">Member Since</span>
                    <span class="fw-medium">{{ date('M d, Y', strtotime($user->join_date)) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MLM Status -->
    <div class="dashboard-card">
        <div class="card-header">
            <h5 class="mb-0">MLM Status & Achievements</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-6">
                    <div class="achievement-card text-center">
                        <div class="achievement-icon bg-primary text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center">
                            <i class="lni lni-crown"></i>
                        </div>
                        <h6>Current Rank</h6>
                        <span class="rank-badge">{{ $user->rank }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="achievement-card text-center">
                        <div class="achievement-icon bg-success text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center">
                            <i class="lni lni-calendar"></i>
                        </div>
                        <h6>Active Days</h6>
                        <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($user->join_date)->diffInDays() }} days</p>
                    </div>
                </div>
            </div>
            
            <div class="achievement-badges mt-4">
                <h6 class="mb-3">Achievements</h6>
                <div class="row g-2">
                    <div class="col-4">
                        <div class="badge-item text-center">
                            <div class="badge-icon bg-warning text-white rounded-circle mx-auto mb-1">
                                <i class="lni lni-star"></i>
                            </div>
                            <small>First Sale</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="badge-item text-center">
                            <div class="badge-icon bg-info text-white rounded-circle mx-auto mb-1">
                                <i class="lni lni-users"></i>
                            </div>
                            <small>Team Builder</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="badge-item text-center">
                            <div class="badge-icon bg-success text-white rounded-circle mx-auto mb-1">
                                <i class="lni lni-money-protection"></i>
                            </div>
                            <small>Earner</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Information -->
    <div class="dashboard-card">
        <div class="card-header">
            <h5 class="mb-0">Referral Information</h5>
        </div>
        <div class="card-body">
            <div class="referral-link-section">
                <label class="form-label">Your Referral Link</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="referralLink" 
                           value="{{ url('/register?ref=' . $user->user_id) }}" readonly>
                    <button class="btn btn-outline-primary" onclick="copyReferralLink()">
                        <i class="lni lni-copy"></i> Copy
                    </button>
                </div>
                <small class="text-muted">Share this link to earn commissions on new member registrations</small>
            </div>

            <div class="referral-stats mt-4">
                <div class="row g-3">
                    <div class="col-4">
                        <div class="stat-box text-center">
                            <h4 class="text-primary">12</h4>
                            <small class="text-muted">Direct Referrals</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-box text-center">
                            <h4 class="text-success">$1,240</h4>
                            <small class="text-muted">Referral Earnings</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-box text-center">
                            <h4 class="text-info">85%</h4>
                            <small class="text-muted">Conversion Rate</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="dashboard-card">
        <div class="card-header">
            <h5 class="mb-0">Security Settings</h5>
        </div>
        <div class="card-body">
            <div class="security-options">
                <div class="security-item d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div>
                        <h6 class="mb-1">Change Password</h6>
                        <small class="text-muted">Last changed 3 months ago</small>
                    </div>
                    <button class="btn btn-sm btn-outline-primary" onclick="changePassword()">Change</button>
                </div>
                <div class="security-item d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div>
                        <h6 class="mb-1">Two-Factor Authentication</h6>
                        <small class="text-success">Enabled</small>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="manage2FA()">Manage</button>
                </div>
                <div class="security-item d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h6 class="mb-1">Login Sessions</h6>
                        <small class="text-muted">2 active sessions</small>
                    </div>
                    <button class="btn btn-sm btn-outline-warning" onclick="manageSessions()">View</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Preferences -->
    <div class="dashboard-card">
        <div class="card-header">
            <h5 class="mb-0">Notification Preferences</h5>
        </div>
        <div class="card-body">
            <div class="notification-settings">
                <div class="notification-item d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div>
                        <h6 class="mb-1">Email Notifications</h6>
                        <small class="text-muted">Commission alerts, team updates</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                    </div>
                </div>
                <div class="notification-item d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div>
                        <h6 class="mb-1">SMS Notifications</h6>
                        <small class="text-muted">Important account updates</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="smsNotifications" checked>
                    </div>
                </div>
                <div class="notification-item d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h6 class="mb-1">Marketing Emails</h6>
                        <small class="text-muted">Product updates, promotions</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="marketingEmails">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="dashboard-card">
        <div class="card-header">
            <h5 class="mb-0">Account Actions</h5>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-6">
                    <button class="btn btn-outline-primary w-100" onclick="downloadData()">
                        <i class="lni lni-download"></i><br>
                        Download Data
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-outline-info w-100" onclick="supportTicket()">
                        <i class="lni lni-headphone-alt"></i><br>
                        Contact Support
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-outline-warning w-100" onclick="deactivateAccount()">
                        <i class="lni lni-warning"></i><br>
                        Deactivate Account
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-outline-success w-100" onclick="upgradeAccount()">
                        <i class="lni lni-star"></i><br>
                        Upgrade Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.profile-image {
    border: 4px solid #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.achievement-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 15px;
}

.achievement-icon {
    width: 50px;
    height: 50px;
    font-size: 1.2rem;
}

.badge-icon {
    width: 35px;
    height: 35px;
    font-size: 0.9rem;
}

.stat-box {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
}

.security-item, .notification-item {
    transition: background-color 0.3s ease;
}

.security-item:hover, .notification-item:hover {
    background-color: #f8f9fa;
}

.form-switch .form-check-input {
    width: 3rem;
    height: 1.5rem;
}

.referral-link-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
}
</style>
@endpush

@push('scripts')
<script>
function editProfile() {
    alert('Opening profile edit form...');
    // In real implementation, this would open a modal or navigate to edit page
}

function copyReferralLink() {
    const referralLink = document.getElementById('referralLink');
    referralLink.select();
    document.execCommand('copy');
    alert('Referral link copied to clipboard!');
}

function changePassword() {
    alert('Opening password change form...');
    // In real implementation, this would open a secure password change modal
}

function manage2FA() {
    alert('Opening two-factor authentication settings...');
    // In real implementation, this would show 2FA setup/management
}

function manageSessions() {
    alert('Showing active login sessions...');
    // In real implementation, this would show and allow termination of sessions
}

function downloadData() {
    alert('Preparing account data download...');
    // In real implementation, this would generate a data export
}

function supportTicket() {
    alert('Opening support ticket form...');
    // In real implementation, this would open customer support
}

function deactivateAccount() {
    if(confirm('Are you sure you want to deactivate your account? This action can be reversed by contacting support.')) {
        alert('Account deactivation process initiated...');
        // In real implementation, this would start deactivation process
    }
}

function upgradeAccount() {
    alert('Opening account upgrade options...');
    // In real implementation, this would show premium membership options
}

// Handle notification toggle changes
document.addEventListener('DOMContentLoaded', function() {
    const toggles = document.querySelectorAll('.form-check-input[type="checkbox"]');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            console.log(`${this.id} toggled to: ${this.checked}`);
            // In real implementation, this would save the preference via AJAX
        });
    });
});
</script>
@endpush
