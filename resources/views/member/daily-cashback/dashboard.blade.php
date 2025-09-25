@extends('member.layouts.app')

@section('title', 'Daily Cashback Dashboard')

@section('styles')
<style>
    .cashback-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        transition: transform 0.3s ease;
    }
    
    .cashback-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border-left: 4px solid;
    }
    
    .stat-card.earned { border-left-color: #10b981; }
    .stat-card.pending { border-left-color: #f59e0b; }
    .stat-card.today { border-left-color: #3b82f6; }
    .stat-card.average { border-left-color: #8b5cf6; }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    
    .progress-ring {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: conic-gradient(from 0deg, #10b981 0deg, #10b981 var(--percentage), #e5e7eb var(--percentage), #e5e7eb 360deg);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    
    .progress-ring::before {
        content: '';
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: white;
        position: absolute;
    }
    
    .progress-ring .percentage {
        position: relative;
        z-index: 1;
        font-weight: 600;
        font-size: 12px;
    }
    
    .package-card {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        border-radius: 12px;
        border: 1px solid rgba(252, 182, 159, 0.3);
    }
    
    .requirement-item {
        background: #f8fafc;
        border-radius: 8px;
        border-left: 4px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .requirement-item.completed {
        background: #f0fdf4;
        border-left-color: #10b981;
    }
    
    .requirement-item.in-progress {
        background: #fffbeb;
        border-left-color: #f59e0b;
    }
    
    .transaction-item {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .transaction-item:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .transaction-item.paid { border-left-color: #10b981; }
    .transaction-item.pending { border-left-color: #f59e0b; }
    
    .cashback-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .cashback-badge.paid {
        background: #dcfce7;
        color: #15803d;
    }
    
    .cashback-badge.pending {
        background: #fef3c7;
        color: #d97706;
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <div class="container-fluid my-4">
        
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
            <div>
                <nav>
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item">
                            <a href="{{ route('member.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Daily Cashback</li>
                    </ol>
                </nav>
                <p class="fw-semibold fs-15 mb-0">Monitor your daily cashback earnings and progress</p>
            </div>
            <div class="btn-list">
                <a href="{{ route('member.daily-cashback.history') }}" class="btn btn-primary-light btn-wave">
                    <i class="ri-history-line me-1"></i> View History
                </a>
                <a href="{{ route('member.daily-cashback.pending') }}" class="btn btn-warning-light btn-wave">
                    <i class="ri-time-line me-1"></i> Pending Cashbacks
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card stat-card earned">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-success-transparent">
                                    <i class="ti ti-coins fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Earned</p>
                                        <h4 class="fw-semibold mt-1">৳{{ number_format($stats['total_earned'], 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card stat-card pending">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-warning-transparent">
                                    <i class="ti ti-clock fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Pending</p>
                                        <h4 class="fw-semibold mt-1">৳{{ number_format($stats['total_pending'], 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card stat-card today">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-primary-transparent">
                                    <i class="ti ti-calendar-today fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Today's Earnings</p>
                                        <h4 class="fw-semibold mt-1">৳{{ number_format($stats['today_earned'], 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card stat-card average">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-purple-transparent">
                                    <i class="ti ti-trending-up fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Daily Average</p>
                                        <h4 class="fw-semibold mt-1">৳{{ number_format($stats['average_daily'], 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Active Cashback Packages -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="ri-gift-line me-2"></i>Active Cashback Packages
                        </div>
                    </div>
                    <div class="card-body">
                        @if($cashbackPackages->count() > 0)
                            <div class="row">
                                @foreach($cashbackPackages as $package)
                                    <div class="col-md-6 mb-4">
                                        <div class="package-card p-4">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <h5 class="mb-0">{{ $package->plan->name }}</h5>
                                                <span class="badge bg-success-transparent">Active</span>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <small class="text-muted">Daily Range</small>
                                                        <p class="mb-0 fw-semibold">৳{{ $package->plan->daily_cashback_min }} - ৳{{ $package->plan->daily_cashback_max }}</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted">Duration</small>
                                                        <p class="mb-0 fw-semibold">{{ $package->plan->cashback_duration_days ?? 'Unlimited' }} days</p>
                                                    </div>
                                                </div>
                                            </div>

                                            @if(isset($referralProgress[$package->plan_id]))
                                                <div class="referral-progress">
                                                    <h6 class="mb-2">Referral Progress</h6>
                                                    @php $progress = $referralProgress[$package->plan_id] @endphp
                                                    
                                                    @if(isset($progress['overall']))
                                                        <div class="progress mb-2" style="height: 6px;">
                                                            <div class="progress-bar bg-success" 
                                                                 style="width: {{ $progress['overall']['percentage'] }}%"></div>
                                                        </div>
                                                        <small class="text-muted">
                                                            {{ $progress['overall']['completed_conditions'] }}/{{ $progress['overall']['total_conditions'] }} requirements completed ({{ number_format($progress['overall']['percentage'], 1) }}%)
                                                        </small>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <img src="{{ asset('admin-assets/images/media/media-67.svg') }}" alt="" class="w-25">
                                <h5 class="mt-3">No Active Cashback Packages</h5>
                                <p class="text-muted">Purchase a cashback-enabled package to start earning daily rewards!</p>
                                <a href="{{ route('member.packages.index') }}" class="btn btn-primary">Browse Packages</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Progress -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="ri-dashboard-line me-2"></i>Quick Overview
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h6 class="mb-1">This Month</h6>
                                <h4 class="text-success mb-0">৳{{ number_format($stats['this_month_earned'], 2) }}</h4>
                            </div>
                            <div class="progress-ring" style="--percentage: {{ $stats['total_days'] > 0 ? min(360, ($stats['this_month_earned'] / ($stats['average_daily'] * 30)) * 360) : 0 }}deg">
                                <span class="percentage">{{ $stats['total_days'] > 0 ? number_format(($stats['this_month_earned'] / ($stats['average_daily'] * 30)) * 100, 0) : 0 }}%</span>
                            </div>
                        </div>

                        <div class="border-top pt-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-muted">Active Days</span>
                                <span class="fw-semibold">{{ $stats['total_days'] }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-muted">Packages Active</span>
                                <span class="fw-semibold">{{ $cashbackPackages->count() }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-muted">Success Rate</span>
                                <span class="fw-semibold text-success">{{ $stats['total_days'] > 0 ? number_format(($stats['total_earned'] / ($stats['total_days'] * 12.5)) * 100, 1) : 0 }}%</span>
                            </div>
                        </div>

                        @if($stats['total_pending'] > 0)
                            <div class="alert alert-warning-transparent mt-3">
                                <div class="d-flex align-items-start">
                                    <i class="ri-information-line me-2 mt-1"></i>
                                    <div>
                                        <strong>Pending Rewards</strong><br>
                                        <small>Complete referral requirements to unlock ৳{{ number_format($stats['total_pending'], 2) }} in pending cashbacks!</small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Cashbacks -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title">
                            <i class="ri-history-line me-2"></i>Recent Cashbacks
                        </div>
                        <a href="{{ route('member.daily-cashback.history') }}" class="btn btn-sm btn-primary-light">
                            View All <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        @if($allCashbacks->count() > 0)
                            <div class="row">
                                @foreach($allCashbacks->take(6) as $cashback)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="transaction-item p-3 {{ $cashback->status }}">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span class="cashback-badge {{ $cashback->status }}">
                                                    @if($cashback->status == 'paid')
                                                        <i class="ri-check-line"></i> Paid
                                                    @else
                                                        <i class="ri-time-line"></i> Pending
                                                    @endif
                                                </span>
                                                <small class="text-muted">{{ $cashback->cashback_date->format('M j, Y') }}</small>
                                            </div>
                                            <h6 class="mb-1">৳{{ number_format($cashback->cashback_amount, 2) }}</h6>
                                            <small class="text-muted">{{ $cashback->plan->name }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ri-inbox-line fs-48 text-muted"></i>
                                <h6 class="mt-2">No cashback records yet</h6>
                                <small class="text-muted">Your cashback earnings will appear here once you have active packages.</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto refresh every 5 minutes to get latest cashback status
    setTimeout(function() {
        location.reload();
    }, 300000);
    
    // Tooltip initialization
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endsection