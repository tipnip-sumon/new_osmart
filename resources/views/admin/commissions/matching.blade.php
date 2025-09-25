@extends('admin.layouts.app')

@section('title', 'Matching Bonus')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
        <div>
            <nav>
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.commissions.overview') }}">Commissions</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Matching Bonus</li>
                </ol>
            </nav>
            <h1 class="page-title fw-medium fs-18 mb-0">Matching Bonus</h1>
        </div>
        <div class="btn-list">
            <a href="{{ route('admin.commissions.export', ['type' => 'tier_bonus']) }}" class="btn btn-success-light btn-wave me-2">
                <i class="bx bx-download me-1"></i> Export Data
            </a>
            <button class="btn btn-primary-light btn-wave me-0" onclick="window.location.reload()">
                <i class="bx bx-refresh me-1"></i> Refresh Page
            </button>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="row">
        <!-- Matching Bonus Statistics -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Matching Bonus Statistics</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden bg-success-transparent">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-success">
                                                <i class="bx bx-trophy fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Total Matching</p>
                                                    <h4 class="fw-semibold mt-1">৳{{ number_format($stats['total_matching'], 2) }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden bg-primary-transparent">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-primary">
                                                <i class="bx bx-check-circle fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Paid Matching</p>
                                                    <h4 class="fw-semibold mt-1">৳{{ number_format($stats['paid_matching'], 2) }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden bg-warning-transparent">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-warning">
                                                <i class="bx bx-time fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Pending Matching</p>
                                                    <h4 class="fw-semibold mt-1">৳{{ number_format($stats['pending_matching'], 2) }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden bg-info-transparent">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-info">
                                                <i class="bx bx-calendar fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">This Month</p>
                                                    <h4 class="fw-semibold mt-1">৳{{ number_format($stats['this_month_matching'], 2) }}</h4>
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
        </div>

        <!-- Matching Bonus Explanation -->
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">How Matching Bonus Works</div>
                </div>
                <div class="card-body">
                    <div class="matching-explanation">
                        <div class="mb-4">
                            <h6><i class="bx bx-info-circle text-primary me-2"></i>Matching Bonus Criteria</h6>
                            <ul class="list-unstyled ms-4">
                                <li class="mb-2"><i class="bx bx-check text-success me-2"></i>Minimum 2 active downlines required</li>
                                <li class="mb-2"><i class="bx bx-check text-success me-2"></i>Must maintain minimum monthly volume</li>
                                <li class="mb-2"><i class="bx bx-check text-success me-2"></i>Balanced left and right leg development</li>
                                <li class="mb-2"><i class="bx bx-check text-success me-2"></i>Active status for the current month</li>
                            </ul>
                        </div>
                        
                        <div class="mb-4">
                            <h6><i class="bx bx-calculator text-info me-2"></i>Commission Calculation</h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-2"><strong>Formula:</strong> (Binary Commission of Downlines) × Matching %</p>
                                <p class="mb-2"><strong>Level 1:</strong> 10% of direct referrals binary commission</p>
                                <p class="mb-2"><strong>Level 2:</strong> 5% of 2nd level referrals binary commission</p>
                                <p class="mb-0"><strong>Level 3:</strong> 3% of 3rd level referrals binary commission</p>
                            </div>
                        </div>

                        <div>
                            <h6><i class="bx bx-award text-warning me-2"></i>Qualification Requirements</h6>
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-center p-3 border rounded">
                                        <i class="bx bx-user-plus fs-24 text-primary"></i>
                                        <p class="mb-0 mt-2"><strong>2+ Active</strong></p>
                                        <small class="text-muted">Direct Referrals</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-3 border rounded">
                                        <i class="bx bx-trending-up fs-24 text-success"></i>
                                        <p class="mb-0 mt-2"><strong>৳50,000</strong></p>
                                        <small class="text-muted">Monthly Volume</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Matching Records -->
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Recent Matching Records</div>
                </div>
                <div class="card-body">
                    @if($matching_records->count() > 0)
                        @foreach($matching_records as $record)
                            <div class="d-flex align-items-center justify-content-between mb-3 p-3 border rounded recent-record-item">
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-sm avatar-rounded bg-success me-3">
                                        {{ substr($record->user->name ?? 'N/A', 0, 1) }}
                                    </span>
                                    <div>
                                        <p class="mb-0 fw-semibold">{{ $record->user->name ?? 'N/A' }}</p>
                                        <p class="mb-0 text-muted fs-12">{{ $record->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="d-flex flex-column align-items-end">
                                        <span class="badge bg-success-transparent mb-1">
                                            ৳{{ number_format($record->matched_amount ?? 0, 2) }}
                                        </span>
                                        <small class="text-muted">Level {{ $record->level ?? 1 }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-4">No matching records available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Matching Bonus Table -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title">Matching Bonus Records</div>
                    <div class="d-flex align-items-center gap-2">
                        <!-- Filters -->
                        <form method="GET" action="{{ route('admin.commissions.matching') }}" class="d-flex gap-2">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" placeholder="From Date" onchange="this.form.submit()">
                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" placeholder="To Date" onchange="this.form.submit()">
                            <button type="submit" class="btn btn-sm btn-primary-light" title="Apply Filters">
                                <i class="bx bx-search me-1"></i>Filter
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                    </th>
                                    <th>User</th>
                                    <th>Level</th>
                                    <th>Downline Commission</th>
                                    <th>Matching %</th>
                                    <th>Matching Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($commissions as $commission)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="commission-checkbox" value="{{ $commission->id }}">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm avatar-rounded">
                                                    {{ substr($commission->user->name ?? 'N/A', 0, 1) }}
                                                </span>
                                                <div class="ms-2">
                                                    <p class="mb-0 fw-semibold">{{ $commission->user->name ?? 'N/A' }}</p>
                                                    <p class="mb-0 text-muted fs-12">{{ $commission->user->email ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $commission->level <= 1 ? 'success' : ($commission->level <= 2 ? 'warning' : 'info') }}-transparent">
                                                Level {{ $commission->level ?? '1' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">৳{{ number_format($commission->order_amount ?? 0, 2) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $matchingRate = $commission->level <= 1 ? 10 : ($commission->level <= 2 ? 5 : 3);
                                            @endphp
                                            <span class="badge bg-info-transparent">{{ $matchingRate }}%</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-success">৳{{ number_format($commission->commission_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'approved' => 'info',
                                                    'paid' => 'success',
                                                    'cancelled' => 'danger'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$commission->status] ?? 'secondary' }}">
                                                {{ ucfirst($commission->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $commission->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-info-light" onclick="viewCommission({{ $commission->id }})" title="View Details">
                                                    <i class="bx bx-eye me-1"></i>View
                                                </button>
                                                @if($commission->status !== 'paid')
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-warning-light dropdown-toggle" data-bs-toggle="dropdown" title="Update Status">
                                                            <i class="bx bx-edit me-1"></i>Update
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @if($commission->status !== 'approved')
                                                                <li><a class="dropdown-item text-info" href="javascript:void(0);" onclick="updateStatus({{ $commission->id }}, 'approved')">
                                                                    <i class="bx bx-check me-1"></i>Mark as Approved
                                                                </a></li>
                                                            @endif
                                                            @if($commission->status !== 'paid')
                                                                <li><a class="dropdown-item text-success" href="javascript:void(0);" onclick="updateStatus({{ $commission->id }}, 'paid')">
                                                                    <i class="bx bx-money me-1"></i>Mark as Paid
                                                                </a></li>
                                                            @endif
                                                            @if($commission->status !== 'cancelled')
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="updateStatus({{ $commission->id }}, 'cancelled')">
                                                                    <i class="bx bx-x me-1"></i>Cancel Commission
                                                                </a></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @else
                                                    <span class="badge bg-success-light text-success">
                                                        <i class="bx bx-check me-1"></i>Completed
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">No matching bonus records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($commissions->hasPages())
                    <div class="card-footer">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                Showing {{ $commissions->firstItem() }} to {{ $commissions->lastItem() }} of {{ $commissions->total() }} results
                            </div>
                            <div>
                                {{ $commissions->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.matching-explanation {
    font-size: 14px;
}

.matching-explanation h6 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 1rem;
}

.matching-explanation .list-unstyled li {
    padding: 4px 0;
    display: flex;
    align-items: center;
}

.matching-explanation .bg-light {
    background: linear-gradient(45deg, #f8f9fa, #e9ecef) !important;
    border-left: 4px solid #007bff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* Qualification boxes styling */
.qualification-box {
    transition: all 0.3s ease;
    background: linear-gradient(45deg, #ffffff, #f8f9fa);
    border: 2px solid #dee2e6 !important;
}

.qualification-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-color: #007bff !important;
}

/* Improve button visibility */
.btn-group .btn {
    font-weight: 500;
}

.dropdown-menu {
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    border: none;
}

.dropdown-item {
    padding: 8px 16px;
    font-weight: 500;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

/* Improve table readability */
.table th {
    font-weight: 600;
    background-color: #f8f9fa;
    border-top: none;
}

.table td {
    vertical-align: middle;
}

/* Badge improvements */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.375rem 0.75rem;
}

/* Level badges specific styling */
.badge.bg-success-transparent {
    background-color: rgba(40, 167, 69, 0.1) !important;
    color: #28a745 !important;
    border: 1px solid rgba(40, 167, 69, 0.2);
}

.badge.bg-warning-transparent {
    background-color: rgba(255, 193, 7, 0.1) !important;
    color: #856404 !important;
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.badge.bg-info-transparent {
    background-color: rgba(23, 162, 184, 0.1) !important;
    color: #17a2b8 !important;
    border: 1px solid rgba(23, 162, 184, 0.2);
}

/* Recent records card improvements */
.recent-record-item {
    transition: all 0.3s ease;
    background: #ffffff;
}

.recent-record-item:hover {
    background: #f8f9fa;
    transform: translateX(2px);
}

/* Enhanced modal styling for matching bonus */
.modal-header.bg-warning {
    border-radius: 0.5rem 0.5rem 0 0;
}

.modal-lg {
    max-width: 950px;
}

/* Timeline styles for bonus history */
.timeline {
    position: relative;
    padding-left: 1.5rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
    padding-left: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: -0.5rem;
    top: 0.25rem;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-title {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #495057;
}

.timeline-text {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

/* Calculation steps styling */
.calculation-steps .step {
    transition: all 0.3s ease;
}

.calculation-steps .step:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.step h6 {
    font-weight: 600;
}

/* Bonus summary card */
.bonus-summary {
    background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%) !important;
    box-shadow: 0 4px 20px rgba(255, 193, 7, 0.3);
    border: none !important;
}

/* Qualification matrix enhancements */
.qualification-matrix .p-2 {
    transition: all 0.3s ease;
}

.qualification-matrix .p-2:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* Card enhancements */
.card.border-0 {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.card.border-0:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

/* Toast enhancements */
#toast-container {
    z-index: 9999 !important;
}

.toast {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Responsive improvements */
@media (max-width: 768px) {
    .modal-lg {
        max-width: 95%;
        margin: 1rem auto;
    }
    
    .timeline {
        padding-left: 1rem;
    }
    
    .timeline-item {
        padding-left: 1rem;
    }
    
    .calculation-steps .step {
        margin-bottom: 1rem !important;
    }
    
    .bonus-summary {
        margin-top: 1rem;
    }
}
}
</style>
@endpush

@push('scripts')
<script>
// Ensure functions are globally available
document.addEventListener('DOMContentLoaded', function() {
    // Make functions globally accessible
    window.viewCommission = viewCommission;
    window.updateStatus = updateStatus;
    window.toggleSelectAll = toggleSelectAll;
    window.showToast = showToast;
    window.printMatchingDetails = printMatchingDetails;
});

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.commission-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function updateStatus(commissionId, status) {
    if (confirm(`Are you sure you want to mark this commission as ${status}?`)) {
        fetch(`/admin/commissions/update-status/${commissionId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showToast(data.message || 'Error updating commission status', 'error');
            }
        })
        .catch(error => {
            showToast('Error updating commission status', 'error');
        });
    }
}

function viewCommission(commissionId) {
    // Enhanced modal for matching bonus details
    const modal = `
        <div class="modal fade" id="commissionModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="bx bx-trophy me-2"></i>Matching Bonus Details
                        </h5>
                        <div class="ms-auto">
                            <button type="button" class="btn btn-sm btn-outline-dark me-2" onclick="printMatchingDetails()">
                                <i class="bx bx-printer me-1"></i> Print
                            </button>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                    </div>
                    <div class="modal-body" id="matchingDetails">
                        <div class="text-center py-4">
                            <div class="spinner-border text-warning" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 mb-0">Loading matching bonus details...</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i>Close
                        </button>
                        <button type="button" class="btn btn-warning" onclick="printMatchingDetails()">
                            <i class="bx bx-printer me-1"></i>Print Details
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('commissionModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body and show
    document.body.insertAdjacentHTML('beforeend', modal);
    const modalInstance = new bootstrap.Modal(document.getElementById('commissionModal'));
    modalInstance.show();
    
    // Load comprehensive matching bonus details
    setTimeout(() => {
        document.getElementById('matchingDetails').innerHTML = `
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-warning">
                                <i class="bx bx-trophy me-2"></i>Matching Bonus Information
                            </h6>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-2"><strong>Bonus ID:</strong> #MBON-${commissionId.toString().padStart(6, '0')}</p>
                                    <p class="mb-2"><strong>Type:</strong> <span class="badge bg-warning-transparent">Matching Bonus</span></p>
                                    <p class="mb-2"><strong>Level:</strong> <span class="badge bg-info-transparent">Level 3</span></p>
                                    <p class="mb-0"><strong>Bonus Rate:</strong> <span class="text-success">15% of Downline</span></p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-2"><strong>Bonus Amount:</strong> <span class="text-success fw-semibold">৳275.00</span></p>
                                    <p class="mb-2"><strong>Status:</strong> <span class="badge bg-warning">Pending</span></p>
                                    <p class="mb-2"><strong>Date Generated:</strong> ${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                                    <p class="mb-0"><strong>Qualification Status:</strong> <span class="badge bg-success">Qualified</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-primary">
                                <i class="bx bx-user me-2"></i>User Information
                            </h6>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-md avatar-rounded bg-warning text-dark me-3">
                                    AS
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold">Ahmed Sumon</p>
                                    <p class="mb-0 text-muted small">ahmed.sumon@example.com</p>
                                    <p class="mb-0 text-muted small">Phone: +88 01756789012</p>
                                </div>
                            </div>
                            <p class="mb-1"><strong>Join Date:</strong> Feb 10, 2024</p>
                            <p class="mb-1"><strong>Current Rank:</strong> <span class="badge bg-warning">Gold</span></p>
                            <p class="mb-0"><strong>Total Matching:</strong> <span class="text-warning fw-semibold">৳3,250.00</span></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-info">
                                <i class="bx bx-network-chart me-2"></i>Matching Qualification
                            </h6>
                            <div class="qualification-matrix">
                                <div class="row text-center mb-3">
                                    <div class="col-4">
                                        <div class="p-2 bg-success-transparent rounded">
                                            <i class="bx bx-check-circle fs-20 text-success"></i>
                                            <p class="mb-1 fw-semibold">Personal</p>
                                            <h6 class="text-success mb-0">2,500 PV</h6>
                                            <small class="text-muted">Required: 2,000</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 bg-success-transparent rounded">
                                            <i class="bx bx-check-circle fs-20 text-success"></i>
                                            <p class="mb-1 fw-semibold">Group</p>
                                            <h6 class="text-success mb-0">8,750 PV</h6>
                                            <small class="text-muted">Required: 5,000</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 bg-success-transparent rounded">
                                            <i class="bx bx-check-circle fs-20 text-success"></i>
                                            <p class="mb-1 fw-semibold">Downlines</p>
                                            <h6 class="text-success mb-0">12 Active</h6>
                                            <small class="text-muted">Required: 8</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-2 bg-warning-transparent rounded text-center">
                                    <p class="mb-1"><strong>Qualification Level:</strong></p>
                                    <h5 class="text-warning mb-0">
                                        <i class="bx bx-medal me-1"></i>Level 3 - Gold Rank
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-success">
                                <i class="bx bx-calculator me-2"></i>Bonus Calculation Breakdown
                            </h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="calculation-steps">
                                        <div class="step mb-3 p-3 bg-white rounded border-start border-4 border-warning">
                                            <h6 class="text-primary mb-2">Step 1: Downline Volume Calculation</h6>
                                            <p class="mb-1">Total Downline Personal Volume: <strong>12,500 PV</strong></p>
                                            <p class="mb-1">Qualifying Downlines: <strong>12 Members</strong></p>
                                            <p class="mb-0">Average PV per Downline: <strong>1,041 PV</strong></p>
                                        </div>
                                        
                                        <div class="step mb-3 p-3 bg-white rounded border-start border-4 border-info">
                                            <h6 class="text-success mb-2">Step 2: Bonus Rate Application</h6>
                                            <p class="mb-1">Level 3 Bonus Rate: <strong>15%</strong></p>
                                            <p class="mb-1">Qualifying Volume: <strong>1,833 PV</strong></p>
                                            <p class="mb-0">Bonus Per PV: <strong>৳0.15</strong></p>
                                        </div>
                                        
                                        <div class="step p-3 bg-warning-transparent rounded border-start border-4 border-warning">
                                            <h6 class="text-warning mb-2">Step 3: Final Calculation</h6>
                                            <p class="mb-1">Qualifying PV × Bonus Rate = Total Bonus</p>
                                            <p class="mb-0"><strong>1,833 PV × ৳0.15 = ৳275.00</strong></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="bonus-summary p-3 bg-gradient bg-warning text-dark rounded">
                                        <h6 class="mb-3 text-center">
                                            <i class="bx bx-trophy me-1"></i>Bonus Summary
                                        </h6>
                                        <div class="text-center">
                                            <div class="mb-2">
                                                <small class="text-muted">Monthly Matching</small>
                                                <h4 class="fw-bold">৳275.00</h4>
                                            </div>
                                            <div class="mb-2">
                                                <small class="text-muted">Previous Total</small>
                                                <p class="fw-semibold mb-0">৳2,975.00</p>
                                            </div>
                                            <hr class="my-2">
                                            <div>
                                                <small class="text-muted">New Total</small>
                                                <h5 class="fw-bold text-dark">৳3,250.00</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-info">
                                <i class="bx bx-history me-2"></i>Bonus Generation History
                            </h6>
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-warning"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Qualification Check Passed</h6>
                                        <p class="timeline-text">User met all requirements for Level 3 matching bonus</p>
                                        <small class="text-muted">${new Date().toLocaleDateString()} - 09:15 AM</small>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Bonus Calculated</h6>
                                        <p class="timeline-text">Matching bonus calculated based on downline performance</p>
                                        <small class="text-muted">${new Date().toLocaleDateString()} - 09:20 AM</small>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Bonus Generated</h6>
                                        <p class="timeline-text">৳275.00 matching bonus added to account</p>
                                        <small class="text-muted">${new Date().toLocaleDateString()} - 09:25 AM</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }, 800);
}

// Print matching bonus details function
function printMatchingDetails() {
    const modalContent = document.getElementById('matchingDetails').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Matching Bonus Details</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { font-family: Arial, sans-serif; }
                    .card { margin-bottom: 1rem; }
                    .timeline { margin-left: 1rem; }
                    .timeline-item { margin-bottom: 1rem; }
                    .timeline-marker { width: 12px; height: 12px; border-radius: 50%; display: inline-block; margin-right: 0.5rem; }
                    .step { border-left: 4px solid #ffc107 !important; }
                    @media print { 
                        .no-print { display: none; }
                        body { -webkit-print-color-adjust: exact; }
                        .bg-gradient { background: #ffc107 !important; }
                    }
                </style>
            </head>
            <body>
                <div class="container mt-4">
                    <h2 class="text-center mb-4">Matching Bonus Details Report</h2>
                    ${modalContent}
                    <div class="text-center mt-4 no-print">
                        <button onclick="window.print()" class="btn btn-warning">Print</button>
                        <button onclick="window.close()" class="btn btn-secondary">Close</button>
                    </div>
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
}

function showToast(message, type) {
    const toastColors = {
        'success': 'success',
        'error': 'danger',
        'warning': 'warning',
        'info': 'info'
    };

    const toastIcons = {
        'success': 'bx-check-circle',
        'error': 'bx-error-circle',
        'warning': 'bx-error',
        'info': 'bx-info-circle'
    };

    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // Create enhanced toast element
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${toastColors[type] || 'secondary'} border-0 mb-2`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="bx ${toastIcons[type] || 'bx-info-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    // Show toast with auto-hide
    const toastInstance = new bootstrap.Toast(toast, { delay: 4000 });
    toastInstance.show();
    
    // Remove toast from DOM after hiding
    toast.addEventListener('hidden.bs.toast', () => {
        if (toastContainer.contains(toast)) {
            toastContainer.removeChild(toast);
        }
    });
}
</script>
@endpush
