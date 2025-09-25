@extends('member.layouts.app')

@section('title', 'Package Upgrade - Link Sharing')

@push('styles')
<style>
    .package-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }
    
    .package-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .package-card.current {
        border-color: #198754;
        background: linear-gradient(45deg, #f8f9fa 0%, #e3f2fd 100%);
    }
    
    .package-card.current::before {
        content: 'Current Package';
        position: absolute;
        top: 10px;
        right: -30px;
        background: #198754;
        color: white;
        padding: 5px 40px;
        font-size: 12px;
        font-weight: 600;
        transform: rotate(45deg);
        text-transform: uppercase;
    }
    
    .package-card.recommended {
        border-color: #fd7e14;
        background: linear-gradient(45deg, #fff3cd 0%, #ffeaa7 100%);
    }
    
    .package-card.recommended::after {
        content: 'Most Popular';
        position: absolute;
        top: 10px;
        right: -30px;
        background: #fd7e14;
        color: white;
        padding: 5px 40px;
        font-size: 12px;
        font-weight: 600;
        transform: rotate(45deg);
        text-transform: uppercase;
    }
    
    .price-tag {
        font-size: 2.5rem;
        font-weight: 700;
        color: #495057;
    }
    
    .feature-list {
        list-style: none;
        padding: 0;
    }
    
    .feature-list li {
        padding: 8px 0;
        position: relative;
        padding-left: 25px;
    }
    
    .feature-list li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: #198754;
        font-weight: bold;
        font-size: 16px;
    }
    
    .upgrade-stats {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        display: block;
    }
    
    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h1 class="page-title fw-semibold fs-18 mb-0">Package Upgrade</h1>
            <div class="">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.link-sharing.dashboard') }}">Link Sharing</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Package Upgrade</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Header Close -->

    @if(auth()->user()->activePlan)
        <!-- Current Package Stats -->
        <div class="upgrade-stats">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">{{ auth()->user()->activePlan->name }}</span>
                        <span class="stat-label">Current Package</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">{{ $currentSettings->max_daily_shares ?? 0 }}</span>
                        <span class="stat-label">Daily Share Limit</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">{{ $currentSettings->reward_per_click ?? 0 }} TK</span>
                        <span class="stat-label">Per Click Reward</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">{{ number_format($totalEarnings ?? 0, 2) }} TK</span>
                        <span class="stat-label">Total Earnings</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Available Packages -->
    <div class="row">
        @forelse($plans as $plan)
            @php
                $settings = $packageSettings->where('plan_id', $plan->id)->first();
                $isCurrentPlan = auth()->user()->activePlan && auth()->user()->activePlan->id == $plan->id;
                $isUpgrade = !$isCurrentPlan && (!auth()->user()->activePlan || $plan->fixed_amount > auth()->user()->activePlan->fixed_amount);
            @endphp
            
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="card package-card {{ $isCurrentPlan ? 'current' : ($plan->featured ? 'recommended' : '') }}">>
                    <div class="card-body p-4">
                        <!-- Package Header -->
                        <div class="text-center mb-4">
                            <h4 class="card-title mb-2">{{ $plan->name }}</h4>
                            <div class="price-tag">
                                <span class="fs-5">৳</span>{{ number_format($plan->fixed_amount, 0) }}
                                <small class="text-muted fs-6 d-block">One Time Payment</small>
                            </div>
                        </div>

                        <!-- Package Features -->
                        @if($settings)
                        <ul class="feature-list mb-4">
                            <li><strong>{{ $settings->max_daily_shares }}</strong> daily link shares</li>
                            <li><strong>{{ $settings->reward_per_click }} TK</strong> per unique click</li>
                            <li><strong>{{ number_format($settings->max_daily_shares * $settings->reward_per_click * 30) }} TK</strong> max monthly earning</li>
                            <li>Real-time click tracking</li>
                            <li>Performance analytics</li>
                            <li>Social media integration</li>
                            @if($plan->generates_commission)
                                <li>MLM commission eligible</li>
                            @endif
                        </ul>
                        @else
                        <div class="alert alert-warning">
                            <small>Package settings not configured yet</small>
                        </div>
                        @endif

                        <!-- Package Benefits -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Package Benefits:</h6>
                            <div class="text-sm text-muted">
                                {{ $plan->description ?? 'Complete MLM package with link sharing opportunities' }}
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="text-center">
                            @if($isCurrentPlan)
                                <button class="btn btn-success btn-w-md" disabled>
                                    <i class="bx bx-check me-1"></i>Current Package
                                </button>
                            @elseif(!auth()->user()->activePlan)
                                <a href="{{ route('member.packages.purchase', $plan->id) }}" class="btn btn-primary btn-w-md">
                                    <i class="bx bx-shopping-bag me-1"></i>Activate Package
                                </a>
                            @elseif($isUpgrade)
                                <a href="{{ route('member.packages.purchase', $plan->id) }}" class="btn btn-warning btn-w-md">
                                    <i class="bx bx-up-arrow-alt me-1"></i>Upgrade Package
                                </a>
                            @else
                                <button class="btn btn-secondary btn-w-md" disabled>
                                    <i class="bx bx-down-arrow-alt me-1"></i>Downgrade
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bx bx-package" style="font-size: 4rem; color: #6c757d;"></i>
                        <h4 class="mt-3">No Packages Available</h4>
                        <p class="text-muted">There are no packages available for upgrade at the moment.</p>
                        <a href="{{ route('member.link-sharing.dashboard') }}" class="btn btn-primary">
                            <i class="bx bx-arrow-back me-1"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Upgrade Benefits -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Why Upgrade Your Package?</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <span class="avatar avatar-md bg-primary-gradient">
                                        <i class="bx bx-trending-up fs-18"></i>
                                    </span>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Higher Earning Limits</h6>
                                    <p class="text-muted mb-0 fs-13">Unlock higher daily sharing limits and increase your earning potential significantly.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <span class="avatar avatar-md bg-success-gradient">
                                        <i class="bx bx-money fs-18"></i>
                                    </span>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Better Rewards</h6>
                                    <p class="text-muted mb-0 fs-13">Get more TK per click with premium packages and maximize your daily earnings.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <span class="avatar avatar-md bg-warning-gradient">
                                        <i class="bx bx-crown fs-18"></i>
                                    </span>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Premium Features</h6>
                                    <p class="text-muted mb-0 fs-13">Access advanced analytics, priority support, and exclusive promotional tools.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Support -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <i class="bx bx-info-circle fs-18 me-2"></i>
                    <div>
                        <strong>Need Help Choosing?</strong> Contact our support team for personalized package recommendations based on your earning goals.
                        <a href="#" class="alert-link ms-2">Contact Support</a>
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
    // Add hover effects for better interactivity
    $('.package-card').hover(
        function() {
            $(this).find('.btn').addClass('pulse');
        },
        function() {
            $(this).find('.btn').removeClass('pulse');
        }
    );
    
    // Smooth scrolling for better UX
    $('a[href^="#"]').on('click', function(event) {
        var target = $($(this).attr('href'));
        if(target.length){
            event.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 80
            }, 500);
        }
    });
});
</script>

<style>
.pulse {
    animation: pulse 1s infinite;
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
</style>
@endpush
