@extends('admin.layouts.app')

@section('title', 'Finance Dashboard')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Finance Dashboard</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Finance</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar avatar-md avatar-rounded bg-primary">
                                    <i class="fe fe-trending-up fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <h6 class="fw-semibold mb-0">Total Deposits</h6>
                                <span class="text-muted">৳{{ number_format($stats['total_deposits'] ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar avatar-md avatar-rounded bg-warning">
                                    <i class="fe fe-clock fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <h6 class="fw-semibold mb-0">Pending Deposits</h6>
                                <span class="text-muted">৳{{ number_format($stats['pending_deposits'] ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar avatar-md avatar-rounded bg-success">
                                    <i class="fe fe-check-circle fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <h6 class="fw-semibold mb-0">Approved Today</h6>
                                <span class="text-muted">৳{{ number_format($stats['approved_today'] ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar avatar-md avatar-rounded bg-info">
                                    <i class="fe fe-users fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <h6 class="fw-semibold mb-0">Total Requests</h6>
                                <span class="text-muted">{{ $stats['total_requests'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-zap me-2"></i>Quick Actions
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.finance.deposits') }}" class="btn btn-primary">
                                <i class="fe fe-credit-card me-1"></i>Manage Deposits
                            </a>
                            <a href="{{ route('admin.finance.withdrawals') }}" class="btn btn-warning">
                                <i class="fe fe-arrow-up-right me-1"></i>Manage Withdrawals
                            </a>
                            <a href="{{ route('admin.finance.transactions') }}" class="btn btn-info">
                                <i class="fe fe-list me-1"></i>View Transactions
                            </a>
                            <a href="{{ route('admin.finance.wallets') }}" class="btn btn-success">
                                <i class="fe fe-dollar-sign me-1"></i>Wallet Overview
                            </a>
                            <a href="{{ route('admin.finance.transfer') }}" class="btn btn-gradient-primary">
                                <i class="fe fe-send me-1"></i>Transfer Balance
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-activity me-2"></i>Recent Financial Activity
                        </div>
                        <div class="ms-auto">
                            <a href="{{ route('admin.finance.transactions') }}" class="btn btn-sm btn-primary">View All</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-5">
                            <i class="fe fe-info fs-48 text-muted mb-3"></i>
                            <h5 class="text-muted">Recent Activity</h5>
                            <p class="text-muted">Recent financial transactions will appear here.</p>
                            <a href="{{ route('admin.finance.transactions') }}" class="btn btn-primary">
                                <i class="fe fe-eye me-1"></i>View Transactions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-pie-chart me-2"></i>Payment Methods
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">bKash</span>
                                <span class="badge bg-primary">Most Used</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Nagad</span>
                                <span class="badge bg-secondary">Popular</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Rocket</span>
                                <span class="badge bg-secondary">Active</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Bank Transfer</span>
                                <span class="badge bg-secondary">Available</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Upay</span>
                                <span class="badge bg-secondary">Active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
