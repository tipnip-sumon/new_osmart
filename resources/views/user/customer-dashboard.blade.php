@extends('layouts.app')

@section('title', 'My Account')

@section('content')
<div class="container mt-4 mb-5">
    <div class="row">
        <!-- User Info Card -->
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-2 text-primary">
                                <i class="ti-user me-2"></i>Welcome, {{ $user->name }}!
                            </h4>
                            <p class="text-muted mb-0">Your customer account dashboard</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="user-badge">
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    <i class="ti-shield me-1"></i>Customer
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Personal Information -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">
                        <i class="ti-id-badge me-2 text-primary"></i>Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">User ID</label>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-dark me-2">#{{ str_pad($userInfo['id'], 6, '0', STR_PAD_LEFT) }}</span>
                                <small class="text-muted">Your unique customer ID</small>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Full Name</label>
                            <p class="mb-0 fs-6">{{ $userInfo['name'] }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Email Address</label>
                            <p class="mb-0 fs-6">
                                <i class="ti-email me-1 text-primary"></i>{{ $userInfo['email'] }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Phone Number</label>
                            <p class="mb-0 fs-6">
                                <i class="ti-mobile me-1 text-primary"></i>{{ $userInfo['phone'] }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Member Since</label>
                            <p class="mb-0 fs-6">
                                <i class="ti-calendar me-1 text-primary"></i>{{ $userInfo['joined_date'] }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Account Status</label>
                            <span class="badge bg-success">
                                <i class="ti-check me-1"></i>Active
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">
                        <i class="ti-settings me-2 text-primary"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('shop.grid') }}" class="btn btn-primary">
                            <i class="ti-shopping-cart me-2"></i>Shop Products
                        </a>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                            <i class="ti-package me-2"></i>My Orders
                        </a>
                        <a href="{{ route('wishlist.index') }}" class="btn btn-outline-secondary">
                            <i class="ti-heart me-2"></i>My Wishlist
                        </a>
                        <a href="#" class="btn btn-outline-info">
                            <i class="ti-user me-2"></i>Update Profile
                        </a>
                        <hr>
                        <a href="{{ route('affiliate.login') }}" class="btn btn-warning">
                            <i class="ti-crown me-2"></i>Join as Affiliate
                        </a>
                    </div>
                </div>
            </div>

            <!-- Account Summary -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">
                        <i class="ti-chart-line me-2 text-primary"></i>Account Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="row">
                            <div class="col-6 border-end">
                                <h4 class="text-primary mb-1">{{ $userInfo['total_orders'] }}</h4>
                                <small class="text-muted">Total Orders</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success mb-1">${{ number_format($userInfo['total_spent'], 2) }}</h4>
                                <small class="text-muted">Total Spent</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">
                        <i class="ti-time me-2 text-primary"></i>Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="ti-shopping-bag text-muted" style="font-size: 3rem;"></i>
                        <h6 class="mt-3 text-muted">No recent activity</h6>
                        <p class="text-muted">Start shopping to see your order history here!</p>
                        <a href="{{ route('shop.grid') }}" class="btn btn-primary">
                            <i class="ti-shopping-cart me-2"></i>Start Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.user-badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

.card {
    border-radius: 12px;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.badge {
    border-radius: 8px;
}

.form-label {
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

.text-primary {
    color: #667eea !important;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
}

.btn-warning {
    background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
    border: none;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
}
</style>
@endsection
