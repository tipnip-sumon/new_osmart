@extends('layouts.app')

@section('title', 'My Profile - ' . config('app.name'))

@push('styles')
<style>
/* Base Responsive Typography */
.profile-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    color: white;
    margin-bottom: 2rem;
    font-size: clamp(0.875rem, 2.5vw, 1rem);
}

.profile-header h4 {
    font-size: clamp(1.1rem, 4vw, 1.5rem);
}

.profile-avatar {
    width: clamp(50px, 15vw, 100px);
    height: clamp(50px, 15vw, 100px);
    border: 4px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    object-fit: cover;
}

.profile-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    margin-bottom: 1.5rem;
}

.profile-card .card-header h6 {
    font-size: clamp(0.85rem, 2.5vw, 1rem);
    margin-bottom: 0;
}

.profile-card:hover {
    transform: translateY(-5px);
}

.stat-card {
    text-align: center;
    padding: 1rem;
    background: linear-gradient(45deg, #f8f9fa, #e9ecef);
    border-radius: 10px;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: clamp(0.9rem, 3vw, 1.25rem);
    font-weight: bold;
    color: #495057;
    line-height: 1.2;
}

.stat-label {
    color: #6c757d;
    font-size: clamp(0.6rem, 2vw, 0.7rem);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 0.25rem;
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
    font-size: clamp(0.8rem, 2.5vw, 0.9rem);
}

.info-value {
    color: #495057;
    font-weight: 600;
    font-size: clamp(0.8rem, 2.5vw, 0.9rem);
}

.rank-badge {
    background: linear-gradient(45deg, #ff6b6b, #ee5a52);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: clamp(0.65rem, 2vw, 0.75rem);
    font-weight: 600;
}

.referral-input-group {
    position: relative;
}

.copy-success {
    position: absolute;
    top: -30px;
    right: 0;
    background: #28a745;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 5px;
    font-size: clamp(0.7rem, 2vw, 0.8rem);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.copy-success.show {
    opacity: 1;
}

/* Responsive button text */
.btn-sm {
    font-size: clamp(0.7rem, 2vw, 0.875rem);
}

.btn {
    font-size: clamp(0.8rem, 2.5vw, 1rem);
}

/* Responsive text elements */
.text-white-50 {
    font-size: clamp(0.75rem, 2vw, 0.875rem);
}

.small {
    font-size: clamp(0.75rem, 2vw, 0.875rem);
}

/* Tablet Responsiveness */
@media (max-width: 992px) {
    .profile-header h4 {
        font-size: 1.4rem;
    }
    
    .stat-value {
        font-size: 1.15rem;
    }
    
    .info-label, .info-value {
        font-size: 0.95rem;
    }
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .profile-avatar {
        width: 60px;
        height: 60px;
    }
    
    .profile-header {
        margin-bottom: 1rem;
        padding: 1rem !important;
    }
    
    .profile-header h4 {
        font-size: 1.1rem;
    }
    
    .rank-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.6rem;
    }
    
    .text-white-50 {
        font-size: 0.8rem;
    }
    
    .stat-value {
        font-size: 1rem;
    }
    
    .stat-label {
        font-size: 0.65rem;
    }
    
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.75rem 0;
    }
    
    .info-label, .info-value {
        font-size: 0.85rem;
    }
    
    .card-header h6 {
        font-size: 0.9rem;
    }
    
    .btn-sm {
        font-size: 0.75rem;
        padding: 0.375rem 0.5rem;
    }
    
    .small {
        font-size: 0.8rem;
    }
}

/* Extra Small Mobile */
@media (max-width: 576px) {
    .profile-header h4 {
        font-size: 1rem;
    }
    
    .profile-avatar {
        width: 50px;
        height: 50px;
    }
    
    .stat-value {
        font-size: 0.9rem;
    }
    
    .stat-label {
        font-size: 0.6rem;
    }
    
    .info-label, .info-value {
        font-size: 0.8rem;
    }
    
    .card-header h6 {
        font-size: 0.85rem;
    }
    
    .btn-sm {
        font-size: 0.7rem;
        padding: 0.3rem 0.4rem;
    }
    
    .rank-badge {
        font-size: 0.65rem;
    }
    
    .text-white-50 {
        font-size: 0.75rem;
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
                <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('assets/img/default-avatar.svg') }}" 
                     alt="Profile" class="profile-avatar">
            </div>
            <div class="col">
                <h4 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h4>
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
                <a href="{{ route('user.profile.edit') }}" class="btn btn-light">
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
                    <h6 class="mb-0">
                        <i class="ti ti-user me-2"></i>Personal Information
                    </h6>
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
                    <h6 class="mb-0">
                        <i class="ti ti-link me-2"></i>Referral Link
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3 small">Share this link to invite new members and earn commissions:</p>
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
            <div class="profile-card card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ti ti-chart-line me-2"></i>Account Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="stat-card p-2">
                                <div class="stat-value">{{ $user->rank ?? 'Member' }}</div>
                                <div class="stat-label">Rank</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card p-2">
                                <div class="stat-value">{{ \Carbon\Carbon::parse($user->created_at)->diffInDays() }}</div>
                                <div class="stat-label">Days Active</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card p-2">
                                <div class="stat-value">{{ $user->orders_count ?? 0 }}</div>
                                <div class="stat-label">Orders</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card p-2">
                                <div class="stat-value">৳0</div>
                                <div class="stat-label">Earnings</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="profile-card card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ti ti-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('user.profile.edit') }}" class="btn btn-primary btn-sm w-100">
                                <i class="ti ti-edit me-1"></i>Edit
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('user.password.change') }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="ti ti-key me-1"></i>Password
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('user.orders') }}" class="btn btn-outline-success btn-sm w-100">
                                <i class="ti ti-package me-1"></i>Orders
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('user.wallet') }}" class="btn btn-outline-info btn-sm w-100">
                                <i class="ti ti-wallet me-1"></i>Wallet
                            </a>
                        </div>
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
    
    try {
        navigator.clipboard.writeText(linkInput.value).then(function() {
            successMsg.classList.remove('d-none');
            setTimeout(function() {
                successMsg.classList.add('d-none');
            }, 2000);
        }).catch(function(err) {
            // Fallback for older browsers
            document.execCommand('copy');
            successMsg.classList.remove('d-none');
            setTimeout(function() {
                successMsg.classList.add('d-none');
            }, 2000);
        });
    } catch (err) {
        console.error('Failed to copy text: ', err);
    }
}

// Handle responsive behavior
document.addEventListener('DOMContentLoaded', function() {
    console.log('Profile page loaded successfully');
});
</script>
@endpush
