@extends('member.layouts.app')

@section('title', 'Matching Bonus Qualifications')

@section('content')
<div class="page">
    <!-- Start::app-content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h1 class="page-title fw-medium fs-18 mb-2">Matching Bonus Qualifications</h1>
                    <div class="">
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('member.matching.dashboard') }}">Matching Bonus</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Qualifications</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="btn-list">
                    <a href="{{ route('member.matching.dashboard') }}" class="btn btn-outline-primary">
                        <i class="bx bx-arrow-back me-1"></i> Back to Dashboard
                    </a>
                    <a href="{{ route('member.matching.rank.salary.report') }}" class="btn btn-info">
                        <i class="bx bx-bar-chart-alt-2 me-1"></i> Rank Salary Report
                    </a>
                    <a href="{{ route('member.matching.calculator') }}" class="btn btn-primary">
                        <i class="bx bx-calculator me-1"></i> Bonus Calculator
                    </a>
                </div>
            </div>
            <!-- Page Header Close -->

            <!-- Point System Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card custom-card border-primary">
                        <div class="card-header bg-primary-transparent">
                            <h5 class="card-title text-primary mb-0">
                                <i class="bx bx-coin-stack me-2"></i>Point-Based Qualification System
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-3">
                                <h6 class="alert-heading">
                                    <i class="bx bx-info-circle me-2"></i>New Point-Based Matching System
                                </h6>
                                <p class="mb-2">Our matching system now uses a point-based qualification structure:</p>
                                <ul class="mb-0">
                                    <li><strong>1 Point = 6 Tk</strong> - Points earned from product purchases</li>
                                    <li><strong>100 Points Minimum</strong> - Required per leg for qualification</li>
                                    <li><strong>10% Matching Rate</strong> - Bonus percentage on matched points</li>
                                    <li><strong>Auto Activation</strong> - Reserve points activate at 100+ automatically</li>
                                </ul>
                            </div>

                            @if(isset($user))
                                @php
                                    $pointBalance = app(App\Services\PointService::class)->getUserPointBalance($user);
                                @endphp
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="text-center p-3 bg-light rounded">
                                            <h6 class="text-primary">Reserve Points</h6>
                                            <h4 class="fw-bold">{{ number_format($pointBalance['reserve_points'], 0) }}</h4>
                                            <small class="text-muted">From purchases</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center p-3 bg-success-transparent rounded">
                                            <h6 class="text-success">Active Points</h6>
                                            <h4 class="fw-bold">{{ number_format($pointBalance['active_points'], 0) }}</h4>
                                            <small class="text-muted">Available for matching</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center p-3 bg-info-transparent rounded">
                                            <h6 class="text-info">Total Earned</h6>
                                            <h4 class="fw-bold">{{ number_format($pointBalance['total_points_earned'], 0) }}</h4>
                                            <small class="text-muted">All time</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center p-3 bg-warning-transparent rounded">
                                            <h6 class="text-warning">Points Used</h6>
                                            <h4 class="fw-bold">{{ number_format($pointBalance['total_points_used'], 0) }}</h4>
                                            <small class="text-muted">In matching</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($message))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <i class="bx bx-info-circle me-2"></i>
                            {{ $message }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Start::row-1 -->
                <div class="row">
                    <!-- Current Status Overview -->
                    <div class="col-xl-4">
                        <div class="card custom-card">
                            <div class="card-header">
                                <div class="card-title">Your Current Point Status</div>
                            </div>
                            <div class="card-body">
                                <!-- Point Qualification Status -->
                                @php
                                    $leftPoints = $legPoints['left_points'];
                                    $rightPoints = $legPoints['right_points'];
                                    $isQualified = $leftPoints >= 100 && $rightPoints >= 100;
                                @endphp
                                
                                <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded {{ $isQualified ? 'border-success' : 'border-warning' }}">
                                    <div>
                                        <h6 class="mb-1 {{ $isQualified ? 'text-success' : 'text-warning' }}">
                                            <i class="bx {{ $isQualified ? 'bx-check-circle' : 'bx-x-circle' }} me-1"></i>
                                            Matching Qualification
                                        </h6>
                                        <small class="text-muted">Both legs need 100+ points</small>
                                    </div>
                                    <div class="text-end">
                                        @if($isQualified)
                                            <span class="badge bg-success fs-12">QUALIFIED</span>
                                        @else
                                            <span class="badge bg-warning fs-12">NOT QUALIFIED</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Binary Point Summary -->
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="text-center p-2 {{ $leftPoints >= 100 ? 'bg-success-transparent' : 'bg-warning-transparent' }} rounded">
                                            <h6 class="{{ $leftPoints >= 100 ? 'text-success' : 'text-warning' }} mb-1">Left Leg Points</h6>
                                            <h5 class="fw-bold mb-0">{{ number_format($leftPoints, 0) }}</h5>
                                            @if($leftPoints < 100)
                                                <small class="text-warning">Need {{ 100 - $leftPoints }} more</small>
                                            @else
                                                <small class="text-success">Qualified ✓</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center p-2 {{ $rightPoints >= 100 ? 'bg-success-transparent' : 'bg-warning-transparent' }} rounded">
                                            <h6 class="{{ $rightPoints >= 100 ? 'text-success' : 'text-warning' }} mb-1">Right Leg Points</h6>
                                            <h5 class="fw-bold mb-0">{{ number_format($rightPoints, 0) }}</h5>
                                            @if($rightPoints < 100)
                                                <small class="text-warning">Need {{ 100 - $rightPoints }} more</small>
                                            @else
                                                <small class="text-success">Qualified ✓</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Point Matching Potential -->
                                @php
                                    $matchablePoints = min($leftPoints, $rightPoints);
                                    $potentialBonus = $matchablePoints >= 100 ? ($matchablePoints * 6 * 0.10) : 0;
                                @endphp
                                
                                <div class="border rounded p-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Matchable Points:</span>
                                        <span class="fw-semibold">{{ number_format($matchablePoints, 0) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Point Value (6 Tk):</span>
                                        <span class="fw-semibold">৳{{ number_format($matchablePoints * 6, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Potential Bonus (10%):</span>
                                        <span class="fw-semibold text-primary">৳{{ number_format($potentialBonus, 2) }}</span>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <a href="{{ route('member.binary') }}" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="bx bx-git-branch me-1"></i> View Binary Tree
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Point-Based Qualification System -->
                    <div class="col-xl-8">
                        <div class="card custom-card">
                            <div class="card-header">
                                <div class="card-title">Point-Based Qualification Requirements</div>
                            </div>
                            <div class="card-body">
                                <!-- Basic Qualification Card -->
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary-transparent">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-bold text-primary">
                                                <i class="bx bx-award me-2"></i>Basic Matching Qualification
                                            </h6>
                                            @if($isQualified)
                                                <span class="badge bg-success">
                                                    <i class="bx bx-check me-1"></i> Qualified
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="bx bx-x me-1"></i> Not Qualified
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-primary">Requirements:</h6>
                                                <ul class="list-unstyled">
                                                    <li><i class="bx bx-check-circle text-success me-2"></i>100 Points minimum in Left Leg</li>
                                                    <li><i class="bx bx-check-circle text-success me-2"></i>100 Points minimum in Right Leg</li>
                                                    <li><i class="bx bx-check-circle text-success me-2"></i>Points auto-activate from purchases</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-primary">Benefits:</h6>
                                                <ul class="list-unstyled">
                                                    <li><i class="bx bx-coin text-warning me-2"></i>10% matching bonus rate</li>
                                                    <li><i class="bx bx-coin text-warning me-2"></i>1 Point = 6 Tk value</li>
                                                    <li><i class="bx bx-coin text-warning me-2"></i>Unlimited matching potential</li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <h6>Your Current Status:</h6>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="p-3 {{ $leftPoints >= 100 ? 'bg-success-transparent' : 'bg-danger-transparent' }} rounded text-center">
                                                        <h6 class="{{ $leftPoints >= 100 ? 'text-success' : 'text-danger' }}">Left Leg</h6>
                                                        <h4 class="fw-bold">{{ number_format($leftPoints, 0) }} Points</h4>
                                                        @if($leftPoints >= 100)
                                                            <span class="badge bg-success">Qualified ✓</span>
                                                        @else
                                                            <span class="badge bg-danger">Need {{ 100 - $leftPoints }} more</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="p-3 {{ $rightPoints >= 100 ? 'bg-success-transparent' : 'bg-danger-transparent' }} rounded text-center">
                                                        <h6 class="{{ $rightPoints >= 100 ? 'text-success' : 'text-danger' }}">Right Leg</h6>
                                                        <h4 class="fw-bold">{{ number_format($rightPoints, 0) }} Points</h4>
                                                        @if($rightPoints >= 100)
                                                            <span class="badge bg-success">Qualified ✓</span>
                                                        @else
                                                            <span class="badge bg-danger">Need {{ 100 - $rightPoints }} more</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="p-3 {{ $isQualified ? 'bg-primary-transparent' : 'bg-warning-transparent' }} rounded text-center">
                                                        <h6 class="{{ $isQualified ? 'text-primary' : 'text-warning' }}">Status</h6>
                                                        @if($isQualified)
                                                            <h5 class="fw-bold text-success mb-1">QUALIFIED</h5>
                                                            <span class="badge bg-success">Ready for Matching</span>
                                                        @else
                                                            <h5 class="fw-bold text-warning mb-1">NOT QUALIFIED</h5>
                                                            <span class="badge bg-warning">Build Both Legs</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Point Earning Guide -->
                                <div class="card border-info">
                                    <div class="card-header bg-info-transparent">
                                        <h6 class="mb-0 fw-bold text-info">
                                            <i class="bx bx-info-circle me-2"></i>How to Earn Points
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Direct Purchase:</h6>
                                                <p class="text-muted mb-3">When you or your referrals make product purchases, points are automatically allocated based on 1 Point = 6 Tk.</p>
                                                
                                                <h6>Auto Activation:</h6>
                                                <p class="text-muted mb-0">Reserve points automatically activate when you reach 100+ points, making them available for matching.</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Binary Placement:</h6>
                                                <p class="text-muted mb-3">Points from your network are placed in left or right legs based on your binary structure.</p>
                                                
                                                <h6>Matching Bonus:</h6>
                                                <p class="text-muted mb-0">Earn 10% bonus on matched points when both legs have 100+ points available.</p>
                                            </div>
                                        </div>

                                        @if(!$isQualified)
                                            <div class="alert alert-warning mt-3">
                                                <i class="bx bx-info-circle me-2"></i>
                                                <strong>Next Steps:</strong> 
                                                @if($leftPoints < 100 && $rightPoints < 100)
                                                    Build both legs by referring customers or making purchases. You need {{ 100 - $leftPoints }} points in left leg and {{ 100 - $rightPoints }} points in right leg.
                                                @elseif($leftPoints < 100)
                                                    Focus on building your left leg - you need {{ 100 - $leftPoints }} more points to qualify.
                                                @else
                                                    Focus on building your right leg - you need {{ 100 - $rightPoints }} more points to qualify.
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End::row-1 -->

                <!-- Start::row-2 -->
                <div class="row">
                    <!-- Point-Based Requirements -->
                    <div class="col-xl-6">
                        <div class="card custom-card">
                            <div class="card-header">
                                <div class="card-title">Point-Based Requirements</div>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <!-- Left Leg Points Requirement -->
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Left Leg Points</h6>
                                            <small class="text-muted">Minimum 100 points required</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-semibold">{{ number_format($leftPoints, 0) }} / 100</span>
                                            @if($leftPoints >= 100)
                                                <span class="badge bg-success ms-2">✓</span>
                                            @else
                                                <span class="badge bg-danger ms-2">✗</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Right Leg Points Requirement -->
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Right Leg Points</h6>
                                            <small class="text-muted">Minimum 100 points required</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-semibold">{{ number_format($rightPoints, 0) }} / 100</span>
                                            @if($rightPoints >= 100)
                                                <span class="badge bg-success ms-2">✓</span>
                                            @else
                                                <span class="badge bg-danger ms-2">✗</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Point Conversion Rate -->
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Point Conversion Rate</h6>
                                            <small class="text-muted">Fixed rate for all members</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-semibold">1 Point = 6 Tk</span>
                                            <span class="badge bg-info ms-2">Fixed</span>
                                        </div>
                                    </div>

                                    <!-- Matching Bonus Rate -->
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Matching Bonus Rate</h6>
                                            <small class="text-muted">Percentage on matched points</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-semibold">10%</span>
                                            <span class="badge bg-success ms-2">Active</span>
                                        </div>
                                    </div>

                                    <!-- Auto Activation Threshold -->
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Auto Activation</h6>
                                            <small class="text-muted">Reserve points activate automatically</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-semibold">At 100+ Points</span>
                                            <span class="badge bg-warning ms-2">Auto</span>
                                        </div>
                                    </div>

                                    <!-- Overall Status -->
                                    <div class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                        <div>
                                            <h6 class="mb-1 fw-bold">Overall Qualification Status</h6>
                                            <small class="text-muted">Ready for matching bonus</small>
                                        </div>
                                        <div class="text-end">
                                            @if($isQualified)
                                                <span class="fw-semibold text-success">QUALIFIED</span>
                                                <span class="badge bg-success ms-2">✓</span>
                                            @else
                                                <span class="fw-semibold text-danger">NOT QUALIFIED</span>
                                                <span class="badge bg-danger ms-2">✗</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- How to Earn Points & Qualify -->
                    <div class="col-xl-6">
                        <div class="card custom-card">
                            <div class="card-header">
                                <div class="card-title">How to Earn Points & Qualify</div>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-primary"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">1. Purchase Products</h6>
                                            <p class="text-muted mb-2">Every 6 Tk spent on products earns you 1 point. Points are initially added to reserves.</p>
                                            <a href="{{ route('shop.grid') }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bx bx-shopping-bag me-1"></i> Shop Products
                                            </a>
                                        </div>
                                    </div>

                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">2. Auto Point Activation</h6>
                                            <p class="text-muted mb-2">When you reach 100+ reserve points, they automatically activate and become available for matching.</p>
                                            <small class="text-success">
                                                <i class="bx bx-check-circle me-1"></i>
                                                Current Reserve: {{ number_format($pointBalance['reserve_points'], 0) }} points
                                            </small>
                                        </div>
                                    </div>

                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-info"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">3. Build Both Legs</h6>
                                            <p class="text-muted mb-2">Help your referrals make purchases to build points in both left and right legs.</p>
                                            <a href="{{ route('member.binary') }}" class="btn btn-sm btn-outline-info">
                                                <i class="bx bx-git-branch me-1"></i> View Binary Tree
                                            </a>
                                        </div>
                                    </div>

                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-warning"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">4. Earn Matching Bonus</h6>
                                            <p class="text-muted mb-2">Once both legs have 100+ points, earn 10% bonus on matched points automatically.</p>
                                            <a href="{{ route('member.matching.calculator') }}" class="btn btn-sm btn-outline-warning">
                                                <i class="bx bx-calculator me-1"></i> Bonus Calculator
                                            </a>
                                        </div>
                                    </div>

                                    @if(!$isQualified)
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-danger"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1 text-danger">Your Next Action</h6>
                                                @if($leftPoints < 100 && $rightPoints < 100)
                                                    <p class="text-muted mb-2">Build both legs - need {{ 100 - $leftPoints }} left + {{ 100 - $rightPoints }} right points</p>
                                                @elseif($leftPoints < 100)
                                                    <p class="text-muted mb-2">Focus on left leg - need {{ 100 - $leftPoints }} more points to qualify</p>
                                                @else
                                                    <p class="text-muted mb-2">Focus on right leg - need {{ 100 - $rightPoints }} more points to qualify</p>
                                                @endif
                                                <small class="text-warning">
                                                    <i class="bx bx-info-circle me-1"></i>
                                                    Help team members purchase products worth ৳{{ number_format((100 - min($leftPoints, $rightPoints)) * 6, 0) }}
                                                </small>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End::row-2 -->
            @endif
        </div>
    </div>
    <!-- End::app-content -->
</div>
@endsection
@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 9px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
    padding-left: 30px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    padding: 10px 0;
}
</style>
@endpush

@push('scripts')
<script>
// Clear service worker cache for matching pages to prevent caching issues
document.addEventListener('DOMContentLoaded', function() {
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.ready.then(function(registration) {
            if (registration.active) {
                // Send message to clear matching page cache
                registration.active.postMessage({
                    type: 'CLEAR_MATCHING_CACHE'
                });
            }
        });
        
        // Also force a hard refresh if page was loaded from cache
        if (performance.navigation.type === 2) {
            // Page was loaded from back/forward cache
            window.location.reload(true);
        }
    }
});
</script>
@endpush
