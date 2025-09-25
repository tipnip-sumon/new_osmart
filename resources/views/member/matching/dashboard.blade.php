@extends('member.layouts.app')

@section('title', 'Matching Bonus Dashboard')

@section('content')
<div class="page">
    <!-- Start::app-content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h1 class="page-title fw-medium fs-18 mb-2">
                        Matching Bonus Dashboard
                        @if($isViewingOtherUser ?? false)
                            <small class="text-info">- Viewing {{ $targetUser->name ?? $targetUser->username }}'s Tree</small>
                        @endif
                    </h1>
                    <div class="">
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Matching Bonus</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="btn-list">
                    @if($isViewingOtherUser ?? false)
                        <a href="{{ route('member.matching.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Back to My Tree
                        </a>
                    @endif
                    <a href="{{ route('member.matching.rank.salary.report') }}" class="btn btn-info">
                        <i class="bx bx-bar-chart-alt-2 me-1"></i> Rank Salary Report
                    </a>
                    <a href="{{ route('member.matching.calculator') }}" class="btn btn-primary">
                        <i class="bx bx-calculator me-1"></i> Bonus Calculator
                    </a>
                </div>
            </div>
            <!-- Page Header Close -->

            <!-- Notifications -->
            @if(session('error'))
                <div class="row mb-4">
                    <div class="col-xl-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bx bx-error-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="row mb-4">
                    <div class="col-xl-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bx bx-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Point Balance Overview -->
            <div class="row mb-4">
                <div class="col-xl-12">
                    <div class="card custom-card border-primary">
                        <div class="card-header bg-primary-transparent">
                            <h5 class="card-title text-primary mb-0">
                                <i class="bx bx-coin-stack me-2"></i>Point-Based Matching System
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h6 class="text-primary">Reserve Points</h6>
                                        <h4 class="fw-bold text-primary">{{ number_format($pointBalance['reserve_points'], 0) }}</h4>
                                        <small class="text-muted">Points from purchases</small>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="text-center p-3 bg-success-transparent rounded">
                                        <h6 class="text-success">Active Points</h6>
                                        <h4 class="fw-bold text-success">{{ number_format($pointBalance['active_points'], 0) }}</h4>
                                        <small class="text-muted">Available for matching</small>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="text-center p-3 bg-info-transparent rounded">
                                        <h6 class="text-info">Ready for Activation</h6>
                                        <h4 class="fw-bold text-info">{{ number_format($pointBalance['points_ready_for_activation'], 0) }}</h4>
                                        <small class="text-muted">Will activate at ≥100</small>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="text-center p-3 bg-warning-transparent rounded">
                                        <h6 class="text-warning">Points Used</h6>
                                        <h4 class="fw-bold text-warning">{{ number_format($pointBalance['total_points_used'], 0) }}</h4>
                                        <small class="text-muted">Total consumed</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="bx bx-info-circle me-2"></i>
                                        <strong>Point System:</strong> 1 Point = 6 Tk | Minimum 100 points per leg required for matching | 10% matching bonus
                                        @if($pointBalance['points_until_activation'] < 100)
                                            | Need {{ $pointBalance['points_until_activation'] }} more points to activate next 100 points
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Start::row-1 -->
            <div class="row">
                <!-- Statistics Cards -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card custom-card {{ ($isViewingOtherUser ?? false) ? 'border-info' : '' }}">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <span class="d-block mb-1">Today's Matching</span>
                                    <h4 class="fw-semibold mb-1">৳{{ number_format($todayMatching, 2) }}</h4>
                                    <span class="fs-12 text-muted">
                                        <i class="ti ti-trending-up text-success me-1"></i>
                                        @if($isViewingOtherUser ?? false)
                                            {{ $targetUser->name ?? $targetUser->username }}'s data
                                        @else
                                            Last 24 hours
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <span class="avatar avatar-md bg-primary-transparent">
                                        <i class="bx bx-trending-up fs-20"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card custom-card {{ ($isViewingOtherUser ?? false) ? 'border-info' : '' }}">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <span class="d-block mb-1">Monthly Matching</span>
                                    <h4 class="fw-semibold mb-1">৳{{ number_format($monthlyMatching, 2) }}</h4>
                                    <span class="fs-12 text-muted">
                                        <i class="ti ti-calendar text-info me-1"></i>
                                        This month
                                    </span>
                                </div>
                                <div>
                                    <span class="avatar avatar-md bg-info-transparent">
                                        <i class="bx bx-calendar fs-20"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card custom-card {{ ($isViewingOtherUser ?? false) ? 'border-info' : '' }}">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <span class="d-block mb-1">Total Matching</span>
                                    <h4 class="fw-semibold mb-1">৳{{ number_format($totalMatching, 2) }}</h4>
                                    <span class="fs-12 text-muted">
                                        <i class="ti ti-wallet text-success me-1"></i>
                                        All time
                                    </span>
                                </div>
                                <div>
                                    <span class="avatar avatar-md bg-success-transparent">
                                        <i class="bx bx-wallet fs-20"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card custom-card {{ ($isViewingOtherUser ?? false) ? 'border-info' : '' }}">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <span class="d-block mb-1">Point Qualification</span>
                                    <h4 class="fw-semibold mb-1">
                                        @if($legPoints['qualification_met'])
                                            <span class="text-success">Qualified</span>
                                        @else
                                            <span class="text-warning">Not Qualified</span>
                                        @endif
                                    </h4>
                                    <span class="fs-12 text-muted">
                                        <i class="ti ti-shield-check text-primary me-1"></i>
                                        @if($legPoints['qualification_met'])
                                            Both legs ≥ 100 points
                                        @else
                                            Need 100+ points per leg
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <span class="avatar avatar-md {{ $legPoints['qualification_met'] ? 'bg-success-transparent' : 'bg-warning-transparent' }}">
                                        <i class="bx {{ $legPoints['qualification_met'] ? 'bx-check-shield' : 'bx-shield-x' }} fs-20"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End::row-1 -->

            <!-- Start::row-2 -->
            <div class="row">
                <!-- Binary Volume & Points Chart -->
                <div class="col-xl-8">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">Binary Leg Analysis (Point-Based Matching)</div>
                            <div class="d-flex">
                                <button class="btn btn-sm btn-outline-primary" onclick="refreshLegVolumes()">
                                    <i class="bx bx-refresh me-1"></i> Refresh
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Points Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="bx bx-coin-stack me-1"></i>Point Distribution (Required for Matching)
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-center p-3 border rounded {{ $legPoints['left_points'] >= 100 ? 'border-success bg-success-transparent' : 'border-warning bg-warning-transparent' }}">
                                        <h5 class="text-primary">Left Leg Points</h5>
                                        <h3 class="fw-bold">{{ number_format($legPoints['left_points'], 0) }}</h3>
                                        <small class="text-muted">
                                            @if($legPoints['left_points'] >= 100)
                                                <i class="bx bx-check-circle text-success me-1"></i>Qualified
                                            @else
                                                <i class="bx bx-x-circle text-warning me-1"></i>Need {{ 100 - $legPoints['left_points'] }} more
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-center p-3 border rounded {{ $legPoints['right_points'] >= 100 ? 'border-success bg-success-transparent' : 'border-warning bg-warning-transparent' }}">
                                        <h5 class="text-success">Right Leg Points</h5>
                                        <h3 class="fw-bold">{{ number_format($legPoints['right_points'], 0) }}</h3>
                                        <small class="text-muted">
                                            @if($legPoints['right_points'] >= 100)
                                                <i class="bx bx-check-circle text-success me-1"></i>Qualified
                                            @else
                                                <i class="bx bx-x-circle text-warning me-1"></i>Need {{ 100 - $legPoints['right_points'] }} more
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Volume Section -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h6 class="text-info mb-3">
                                        <i class="bx bx-chart me-1"></i>Volume Distribution (Traditional View)
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-center p-3 border rounded">
                                        <h5 class="text-primary">Left Leg</h5>
                                        <h3 class="fw-bold">৳{{ number_format($legVolumes['left_volume'], 2) }}</h3>
                                        <small class="text-muted">Total Volume</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-center p-3 border rounded">
                                        <h5 class="text-success">Right Leg</h5>
                                        <h3 class="fw-bold">৳{{ number_format($legVolumes['right_volume'], 2) }}</h3>
                                        <small class="text-muted">Total Volume</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-center p-2 bg-info-transparent rounded">
                                        <h6 class="text-info">Matched Points</h6>
                                        <h4 class="fw-bold">{{ number_format($legPoints['matched_points'], 0) }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-center p-2 bg-warning-transparent rounded">
                                        <h6 class="text-warning">Points Carry Forward</h6>
                                        <h4 class="fw-bold">{{ number_format($legPoints['points_carry_forward'], 0) }}</h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Point Progress Bars -->
                            <div class="mt-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Left Leg Point Progress (Min: 100)</label>
                                        <div class="progress mb-2" style="height: 12px;">
                                            @php
                                                $leftPointPercent = ($legPoints['left_points'] / 100) * 100;
                                                $leftPointPercent = min($leftPointPercent, 100); // Cap at 100%
                                            @endphp
                                            <div class="progress-bar {{ $legPoints['left_points'] >= 100 ? 'bg-success' : 'bg-warning' }}" style="width: {{ $leftPointPercent }}%">
                                                {{ number_format($leftPointPercent, 0) }}%
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $legPoints['left_points'] }} / 100 points</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Right Leg Point Progress (Min: 100)</label>
                                        <div class="progress mb-2" style="height: 12px;">
                                            @php
                                                $rightPointPercent = ($legPoints['right_points'] / 100) * 100;
                                                $rightPointPercent = min($rightPointPercent, 100); // Cap at 100%
                                            @endphp
                                            <div class="progress-bar {{ $legPoints['right_points'] >= 100 ? 'bg-success' : 'bg-warning' }}" style="width: {{ $rightPointPercent }}%">
                                                {{ number_format($rightPointPercent, 0) }}%
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $legPoints['right_points'] }} / 100 points</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Point-Based Qualification Requirements -->
                <div class="col-xl-4">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="bx bx-coin-stack me-2"></i>Point-Based Qualification
                            </div>
                        </div>
                        <div class="card-body">
                            @if($legPoints['qualification_met'])
                                <div class="alert alert-success">
                                    <i class="bx bx-check-circle me-2"></i>
                                    <strong>Qualified!</strong> Both legs meet minimum requirements.
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="bx bx-info-circle me-2"></i>
                                    <strong>Not Qualified:</strong> Need 100+ points per leg.
                                </div>
                            @endif

                            <!-- Point Requirements Breakdown -->
                            <div class="mb-3">
                                <h6 class="mb-3">Point Requirements:</h6>
                                
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Left Leg Points:</span>
                                    <span class="{{ $legPoints['left_points'] >= 100 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($legPoints['left_points'], 0) }} / 100
                                        @if($legPoints['left_points'] >= 100)
                                            <i class="bx bx-check-circle ms-1"></i>
                                        @else
                                            <i class="bx bx-x-circle ms-1"></i>
                                        @endif
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Right Leg Points:</span>
                                    <span class="{{ $legPoints['right_points'] >= 100 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($legPoints['right_points'], 0) }} / 100
                                        @if($legPoints['right_points'] >= 100)
                                            <i class="bx bx-check-circle ms-1"></i>
                                        @else
                                            <i class="bx bx-x-circle ms-1"></i>
                                        @endif
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Active Points Available:</span>
                                    <span class="text-info">{{ number_format($pointBalance['active_points'], 0) }}</span>
                                </div>
                            </div>

                            <!-- Matching Rate Information -->
                            <div class="bg-light p-3 rounded mb-3">
                                <h6 class="text-primary mb-2">Matching Details:</h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-1"><i class="bx bx-check text-success me-1"></i> 10% matching bonus rate</li>
                                    <li class="mb-1"><i class="bx bx-check text-success me-1"></i> 1 Point = 6 Tk value</li>
                                    <li class="mb-1"><i class="bx bx-check text-success me-1"></i> 100 points minimum per leg</li>
                                    <li class="mb-0"><i class="bx bx-check text-success me-1"></i> Instant point activation at 100+</li>
                                </ul>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2">
                                @if(!$legPoints['qualification_met'])
                                    <a href="{{ route('shop.grid') }}" class="btn btn-primary btn-sm">
                                        <i class="bx bx-shopping-bag me-1"></i> Shop to Earn Points
                                    </a>
                                @endif
                                <a href="{{ route('member.matching.calculator') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bx bx-calculator me-1"></i> Calculate Bonus
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End::row-2 -->

            <!-- Start::row-3 -->
            <div class="row">
                <!-- Recent Matching History -->
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">Recent Matching Bonuses</div>
                            <div>
                                <a href="{{ route('member.matching.history') }}" class="btn btn-sm btn-outline-primary">
                                    View All History
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($recentMatching->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Left Volume</th>
                                                <th>Right Volume</th>
                                                <th>Matched Volume</th>
                                                <th>Rate</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentMatching as $matching)
                                                <tr>
                                                    <td>{{ $matching->created_at->format('M d, Y H:i') }}</td>
                                                    <td>৳{{ number_format($matching->left_current_volume, 2) }}</td>
                                                    <td>৳{{ number_format($matching->right_current_volume, 2) }}</td>
                                                    <td>৳{{ number_format($matching->matching_volume, 2) }}</td>
                                                    <td>{{ $matching->matching_percentage }}%</td>
                                                    <td class="fw-semibold text-success">৳{{ number_format($matching->matching_bonus, 2) }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $matching->status === 'completed' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($matching->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="avatar avatar-lg bg-light mb-3">
                                        <i class="bx bx-history fs-24 text-muted"></i>
                                    </div>
                                    <h6 class="fw-semibold">No Matching History</h6>
                                    <p class="text-muted mb-3">You haven't earned any matching bonuses yet.</p>
                                    <a href="{{ route('member.matching.calculator') }}" class="btn btn-primary btn-sm">
                                        <i class="bx bx-calculator me-1"></i> Calculate Potential Bonus
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- End::row-3 -->
        </div>
    </div>
    <!-- End::app-content -->
</div>
@endsection
@push('scripts')
<script>
function refreshLegVolumes() {
    // Show loading
    const refreshBtn = document.querySelector('button[onclick="refreshLegVolumes()"]');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Loading...';
    refreshBtn.disabled = true;

    // Fetch updated data
    fetch('{{ route("member.matching.leg.volumes") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the page with new data
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            // Restore button
            refreshBtn.innerHTML = originalText;
            refreshBtn.disabled = false;
        });
}

// Binary tree search function
function searchBinaryTree() {
    const searchInput = document.getElementById('treeSearchInput');
    const searchTerm = searchInput.value.trim();
    
    if (!searchTerm) {
        alert('Please enter a username or referral code');
        return;
    }
    
    // Redirect to current page with search parameter
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('tree_user', searchTerm);
    window.location.href = currentUrl.toString();
}

// Allow Enter key to trigger search
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('treeSearchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchBinaryTree();
            }
        });
    }
    
    // Service Worker cache clearing for matching pages
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

// Auto-refresh every 5 minutes
setInterval(function() {
    fetch('{{ route("member.matching.binary.summary") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.summary) {
                // Update binary summary display silently
                console.log('Binary summary updated:', data.summary);
            }
        })
        .catch(error => console.error('Auto-refresh error:', error));
}, 300000); // 5 minutes
</script>
@endpush

