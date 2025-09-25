@extends('member.layouts.app')

@section('title', 'Point-Based Matching History')

@section('content')
<div class="page">
    <!-- Start::app-content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h1 class="page-title fw-medium fs-18 mb-2">Point-Based Matching History</h1>
                    <div class="">
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('member.matching.dashboard') }}">Matching Bonus</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Point History</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="btn-list">
                    <a href="{{ route('member.matching.dashboard') }}" class="btn btn-outline-primary">
                        <i class="bx bx-arrow-back me-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>
            <!-- Page Header Close -->

            <!-- Point System Overview -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card custom-card border-primary">
                        <div class="card-header bg-primary-transparent">
                            <h5 class="card-title text-primary mb-0">
                                <i class="bx bx-coin-stack me-2"></i>Point-Based Matching Overview
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h6 class="text-primary">Current Active Points</h6>
                                        <h4 class="fw-bold">{{ number_format($pointBalance['active_points'], 0) }}</h4>
                                        <small class="text-muted">Available for matching</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-success-transparent rounded">
                                        <h6 class="text-success">Total Points Earned</h6>
                                        <h4 class="fw-bold">{{ number_format($pointBalance['total_points_earned'], 0) }}</h4>
                                        <small class="text-muted">All time</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-warning-transparent rounded">
                                        <h6 class="text-warning">Points Used in Matching</h6>
                                        <h4 class="fw-bold">{{ number_format($pointBalance['total_points_used'], 0) }}</h4>
                                        <small class="text-muted">For bonuses</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-info-transparent rounded">
                                        <h6 class="text-info">Current Qualification</h6>
                                        @php
                                            $isQualified = $legPoints['left_points'] >= 100 && $legPoints['right_points'] >= 100;
                                        @endphp
                                        <h4 class="fw-bold {{ $isQualified ? 'text-success' : 'text-danger' }}">
                                            {{ $isQualified ? 'QUALIFIED' : 'NOT QUALIFIED' }}
                                        </h4>
                                        <small class="text-muted">For point matching</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Start::row-1 -->
            <div class="row">
                <!-- Summary Cards -->
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card custom-card">
                        <div class="card-body text-center">
                            <div class="avatar avatar-lg bg-primary-transparent mb-3">
                                <i class="bx bx-money fs-24"></i>
                            </div>
                            <h4 class="fw-semibold mb-1">৳{{ number_format($totalAmount, 2) }}</h4>
                            <p class="text-muted mb-0">Total Bonus Earned</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card custom-card">
                        <div class="card-body text-center">
                            <div class="avatar avatar-lg bg-success-transparent mb-3">
                                <i class="bx bx-receipt fs-24"></i>
                            </div>
                            <h4 class="fw-semibold mb-1">{{ number_format($totalCount) }}</h4>
                            <p class="text-muted mb-0">Matching Transactions</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card custom-card">
                        <div class="card-body text-center">
                            <div class="avatar avatar-lg bg-info-transparent mb-3">
                                <i class="bx bx-trending-up fs-24"></i>
                            </div>
                            <h4 class="fw-semibold mb-1">৳{{ number_format($avgAmount, 2) }}</h4>
                            <p class="text-muted mb-0">Average per Match</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card custom-card">
                        <div class="card-body text-center">
                            <div class="avatar avatar-lg bg-warning-transparent mb-3">
                                <i class="bx bx-coin fs-24"></i>
                            </div>
                            <h4 class="fw-semibold mb-1">{{ number_format($totalPointsUsed, 0) }}</h4>
                            <p class="text-muted mb-0">Total Points Matched</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End::row-1 -->

            <!-- Start::row-2 -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                <i class="bx bx-coin-stack me-2"></i>Point-Based Matching History
                            </div>
                            <div class="d-flex gap-2">
                                <!-- Filter Button -->
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                                    <i class="bx bx-filter me-1"></i> Filters
                                </button>
                                <!-- Export Button -->
                                <button class="btn btn-outline-success btn-sm" onclick="exportHistory()">
                                    <i class="bx bx-download me-1"></i> Export
                                </button>
                            </div>
                        </div>

                        <!-- Filter Section -->
                        <div class="collapse" id="filterCollapse">
                            <div class="card-body border-bottom">
                                <form method="GET" action="{{ route('member.matching.history') }}" id="filterForm">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label">Date From</label>
                                            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Date To</label>
                                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Status</label>
                                            <select class="form-select" name="status">
                                                <option value="">All Status</option>
                                                <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Processed</option>
                                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end">
                                            <div class="btn-group w-100">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bx bx-search me-1"></i> Apply
                                                </button>
                                                <a href="{{ route('member.matching.history') }}" class="btn btn-outline-secondary">
                                                    <i class="bx bx-refresh me-1"></i> Reset
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-body">
                            @if($matchings->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Transaction ID</th>
                                                <th>Left Leg Points</th>
                                                <th>Right Leg Points</th>
                                                <th>Matched Points</th>
                                                <th>Point Value</th>
                                                <th>Bonus (10%)</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($matchings as $matching)
                                                @php
                                                    // Pure point system - no conversion needed
                                                    $leftPoints = $matching->left_current_volume; // Pure points
                                                    $rightPoints = $matching->right_current_volume; // Pure points
                                                    $matchedPoints = $matching->matching_volume; // Pure points
                                                    $pointValue = $matchedPoints * 6; // Convert to Taka value for display
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <div>{{ $matching->created_at->format('M d, Y') }}</div>
                                                        <small class="text-muted">{{ $matching->created_at->format('H:i:s') }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="fw-semibold">#{{ str_pad($matching->id, 6, '0', STR_PAD_LEFT) }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-xs bg-primary-transparent me-2">
                                                                <i class="bx bx-coin fs-12"></i>
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold">{{ number_format($leftPoints, 0) }} Points</div>
                                                                <small class="text-muted">Value: ৳{{ number_format($leftPoints * 6, 2) }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-xs bg-success-transparent me-2">
                                                                <i class="bx bx-coin fs-12"></i>
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold">{{ number_format($rightPoints, 0) }} Points</div>
                                                                <small class="text-muted">Value: ৳{{ number_format($rightPoints * 6, 2) }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-center">
                                                            <div class="fw-semibold text-info">{{ number_format($matchedPoints, 0) }} Points</div>
                                                            <small class="text-muted">Matched</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="fw-semibold text-primary">৳{{ number_format($pointValue, 2) }}</span>
                                                        <br><small class="text-muted">({{ number_format($matchedPoints, 0) }} × 6)</small>
                                                    </td>
                                                    <td>
                                                        <span class="fw-semibold text-success fs-15">৳{{ number_format($matching->matching_bonus, 2) }}</span>
                                                        <br><small class="badge bg-success-transparent">10% Rate</small>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $statusColor = match($matching->status) {
                                                                'processed' => 'success',
                                                                'completed' => 'success', 
                                                                'paid' => 'primary',
                                                                'pending' => 'warning',
                                                                'cancelled' => 'danger',
                                                                default => 'secondary'
                                                            };
                                                        @endphp
                                                        <span class="badge bg-{{ $statusColor }}">
                                                            {{ ucfirst($matching->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary" onclick="viewDetails({{ $matching->id }})">
                                                            <i class="bx bx-show fs-12"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <small class="text-muted">
                                            Showing {{ $matchings->firstItem() }} to {{ $matchings->lastItem() }} of {{ $matchings->total() }} results
                                        </small>
                                    </div>
                                    <div>
                                        {{ $matchings->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="avatar avatar-xl bg-light mb-3">
                                        <i class="bx bx-history fs-36 text-muted"></i>
                                    </div>
                                    <h5 class="fw-semibold">No Matching History Found</h5>
                                    <p class="text-muted mb-3">You don't have any matching bonus transactions yet.</p>
                                    <div class="btn-group">
                                        <a href="{{ route('member.matching.calculator') }}" class="btn btn-primary">
                                            <i class="bx bx-calculator me-1"></i> Calculate Potential Bonus
                                        </a>
                                        <a href="{{ route('member.matching.qualifications') }}" class="btn btn-outline-primary">
                                            <i class="bx bx-info-circle me-1"></i> View Requirements
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- End::row-2 -->
        </div>
    </div>
    <!-- End::app-content -->
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Matching Bonus Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailsContent">
                <div class="text-center py-3">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function viewDetails(matchingId) {
    const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    const content = document.getElementById('detailsContent');
    
    // Show loading
    content.innerHTML = `
        <div class="text-center py-3">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    
    modal.show();
    
    // Fetch details (you can implement this endpoint)
    fetch(`/member/matching/details/${matchingId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                content.innerHTML = data.html;
            } else {
                content.innerHTML = '<div class="alert alert-danger">Error loading details.</div>';
            }
        })
        .catch(error => {
            content.innerHTML = '<div class="alert alert-danger">Error loading details.</div>';
        });
}

function exportHistory() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    
    // Add export parameter
    params.append('export', 'csv');
    
    // Create download link
    const downloadUrl = `{{ route('member.matching.history') }}?${params.toString()}`;
    window.open(downloadUrl, '_blank');
}

// Clear service worker cache for matching pages to prevent caching issues
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

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('#filterForm input, #filterForm select');
    
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Auto-submit with a small delay
            setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 500);
        });
    });
});
</script>
@endpush

