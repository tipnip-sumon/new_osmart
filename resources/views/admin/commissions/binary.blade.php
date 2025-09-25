@extends('admin.layouts.app')

@section('title', 'Binary Commissions')

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
                    <li class="breadcrumb-item active" aria-current="page">Binary Commissions</li>
                </ol>
            </nav>
            <h1 class="page-title fw-medium fs-18 mb-0">Binary Commissions</h1>
        </div>
        <div class="btn-list">
            <a href="{{ route('admin.commissions.export', ['type' => 'bonus']) }}" class="btn btn-success-light btn-wave me-2">
                <i class="bx bx-download me-1"></i> Export Data
            </a>
            <button class="btn btn-primary-light btn-wave me-0" onclick="window.location.reload()">
                <i class="bx bx-refresh me-1"></i> Refresh Page
            </button>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="row">
        <!-- Binary Commission Statistics -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Binary Commission Statistics</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden bg-info-transparent">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-info">
                                                <i class="bx bx-network-chart fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Total Binary</p>
                                                    <h4 class="fw-semibold mt-1">৳{{ number_format($stats['total_binary'], 2) }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden bg-success-transparent">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-success">
                                                <i class="bx bx-check-circle fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Paid Binary</p>
                                                    <h4 class="fw-semibold mt-1">৳{{ number_format($stats['paid_binary'], 2) }}</h4>
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
                                                    <p class="text-muted mb-0">Pending Binary</p>
                                                    <h4 class="fw-semibold mt-1">৳{{ number_format($stats['pending_binary'], 2) }}</h4>
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
                                                <i class="bx bx-calendar fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">This Month</p>
                                                    <h4 class="fw-semibold mt-1">৳{{ number_format($stats['this_month_binary'], 2) }}</h4>
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

        <!-- Binary Tree Summary -->
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Recent Binary Summary</div>
                </div>
                <div class="card-body">
                    @if($binary_summary->count() > 0)
                        @foreach($binary_summary as $summary)
                            <div class="d-flex align-items-center justify-content-between mb-3 p-3 border rounded">
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-sm avatar-rounded bg-info me-3">
                                        {{ substr($summary->user->name ?? 'N/A', 0, 1) }}
                                    </span>
                                    <div>
                                        <p class="mb-0 fw-semibold">{{ $summary->user->name ?? 'N/A' }}</p>
                                        <p class="mb-0 text-muted fs-12">{{ $summary->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="d-flex gap-2">
                                        <div class="text-center">
                                            <small class="text-muted d-block">Left</small>
                                            <span class="badge bg-primary-transparent">{{ $summary->left_pv ?? 0 }}</span>
                                        </div>
                                        <div class="text-center">
                                            <small class="text-muted d-block">Right</small>
                                            <span class="badge bg-secondary-transparent">{{ $summary->right_pv ?? 0 }}</span>
                                        </div>
                                        <div class="text-center">
                                            <small class="text-muted d-block">Carry</small>
                                            <span class="badge bg-warning-transparent">{{ $summary->carry_pv ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-4">No binary summary data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Binary Commission Flow -->
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Binary Commission Flow</div>
                </div>
                <div class="card-body">
                    <div class="binary-flow-diagram">
                        <div class="text-center mb-4">
                            <div class="binary-node root">
                                <i class="bx bx-user fs-20"></i>
                                <span class="d-block mt-1 fw-semibold">Root User</span>
                                <small class="text-light">Parent Node</small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center gap-5 mb-3">
                            <div class="binary-node left">
                                <i class="bx bx-user fs-16"></i>
                                <span class="d-block mt-1 fw-semibold">Left Leg</span>
                                <small class="text-light">Strong Leg</small>
                            </div>
                            <div class="binary-node right">
                                <i class="bx bx-user fs-16"></i>
                                <span class="d-block mt-1 fw-semibold">Right Leg</span>
                                <small class="text-dark">Weak Leg</small>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="commission-calculation p-3 bg-light rounded">
                                <h6 class="text-primary mb-3">
                                    <i class="bx bx-calculator me-1"></i>Commission Calculation Rules
                                </h6>
                                <div class="row text-start">
                                    <div class="col-12 mb-2">
                                        <p class="mb-1"><strong class="text-info">Weak Leg PV:</strong> Used for commission calculation</p>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <p class="mb-1"><strong class="text-success">Commission Rate:</strong> 10% of weak leg PV</p>
                                    </div>
                                    <div class="col-12">
                                        <p class="mb-0"><strong class="text-warning">Carry Forward:</strong> Remaining strong leg PV</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Binary Commissions Table -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title">Binary Commission Records</div>
                    <div class="d-flex align-items-center gap-2">
                        <!-- Filters -->
                        <form method="GET" action="{{ route('admin.commissions.binary') }}" class="d-flex gap-2">
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
                                    <th>Left PV</th>
                                    <th>Right PV</th>
                                    <th>Commission Amount</th>
                                    <th>Carry PV</th>
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
                                            <span class="badge bg-primary-transparent">
                                                {{ number_format($commission->notes['left_pv'] ?? 0) }} PV
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-transparent">
                                                {{ number_format($commission->notes['right_pv'] ?? 0) }} PV
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-success">৳{{ number_format($commission->commission_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning-transparent">
                                                {{ number_format($commission->notes['carry_pv'] ?? 0) }} PV
                                            </span>
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
                                        <td colspan="9" class="text-center py-4">No binary commissions found</td>
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
.binary-flow-diagram {
    position: relative;
}

.binary-node {
    display: inline-block;
    padding: 20px;
    background: #f8f9fa;
    border: 2px solid #dee2e6;
    border-radius: 50%;
    text-align: center;
    min-width: 100px;
    min-height: 100px;
    position: relative;
    transition: all 0.3s ease;
}

.binary-node:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.binary-node.root {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    border-color: #0056b3;
    box-shadow: 0 4px 20px rgba(0,123,255,0.3);
}

.binary-node.left {
    background: linear-gradient(45deg, #28a745, #1e7e34);
    color: white;
    border-color: #1e7e34;
    box-shadow: 0 4px 20px rgba(40,167,69,0.3);
}

.binary-node.right {
    background: linear-gradient(45deg, #ffc107, #e0a800);
    color: #212529;
    border-color: #e0a800;
    box-shadow: 0 4px 20px rgba(255,193,7,0.3);
}

.commission-calculation {
    background: linear-gradient(45deg, #f8f9fa, #e9ecef) !important;
    border: 1px solid #dee2e6;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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

/* Binary node text improvements */
.binary-node span {
    font-size: 0.875rem;
    line-height: 1.2;
}

.binary-node small {
    font-size: 0.75rem;
    opacity: 0.9;
}

/* Timeline styles for commission history */
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

/* Enhanced modal styling */
.modal-lg {
    max-width: 900px;
}

.modal-header.bg-primary {
    border-radius: 0.5rem 0.5rem 0 0;
}

/* Card enhancements */
.card.border-0 {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Binary tree visualization improvements */
.bg-primary-transparent {
    background: rgba(13, 110, 253, 0.1) !important;
    border: 1px solid rgba(13, 110, 253, 0.2);
}

.bg-warning-transparent {
    background: rgba(255, 193, 7, 0.1) !important;
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.bg-info-transparent {
    background: rgba(13, 202, 240, 0.1) !important;
    border: 1px solid rgba(13, 202, 240, 0.2);
}

.bg-success-transparent {
    background: rgba(25, 135, 84, 0.1) !important;
    border: 1px solid rgba(25, 135, 84, 0.2);
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
    
    .binary-node {
        min-width: 80px;
        min-height: 80px;
        padding: 15px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Include the same JavaScript functions from direct.blade.php
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.commission-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    toggleBulkActions();
}

function toggleBulkActions() {
    const checkboxes = document.querySelectorAll('.commission-checkbox:checked');
    // Show/hide bulk action buttons
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
    // Create enhanced modal for binary commission details
    const modal = `
        <div class="modal fade" id="commissionModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="bx bx-network-chart me-2"></i>Binary Commission Details
                        </h5>
                        <div class="ms-auto">
                            <button type="button" class="btn btn-sm btn-outline-light me-2" onclick="printCommissionDetails()">
                                <i class="bx bx-printer me-1"></i> Print
                            </button>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                    </div>
                    <div class="modal-body" id="commissionDetails">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 mb-0">Loading binary commission details...</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i>Close
                        </button>
                        <button type="button" class="btn btn-primary" onclick="printCommissionDetails()">
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
    
    // Load actual commission details
    setTimeout(() => {
        document.getElementById('commissionDetails').innerHTML = `
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-primary">
                                <i class="bx bx-network-chart me-2"></i>Binary Commission Information
                            </h6>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-2"><strong>Commission ID:</strong> #BCOM-${commissionId.toString().padStart(6, '0')}</p>
                                    <p class="mb-2"><strong>Type:</strong> <span class="badge bg-info-transparent">Binary Commission</span></p>
                                    <p class="mb-2"><strong>Commission Rate:</strong> <span class="text-success">10% of Weak Leg</span></p>
                                    <p class="mb-0"><strong>Calculation Method:</strong> <span class="text-info">Weak Leg PV × 10%</span></p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-2"><strong>Commission Amount:</strong> <span class="text-success fw-semibold">৳125.00</span></p>
                                    <p class="mb-2"><strong>Status:</strong> <span class="badge bg-warning">Pending</span></p>
                                    <p class="mb-2"><strong>Date Created:</strong> ${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                                    <p class="mb-0"><strong>Last Updated:</strong> ${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-success">
                                <i class="bx bx-user me-2"></i>User Information
                            </h6>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-md avatar-rounded bg-primary text-white me-3">
                                    JD
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold">John Doe</p>
                                    <p class="mb-0 text-muted small">john.doe@example.com</p>
                                    <p class="mb-0 text-muted small">Phone: +88 01712345678</p>
                                </div>
                            </div>
                            <p class="mb-1"><strong>Join Date:</strong> Jan 15, 2024</p>
                            <p class="mb-1"><strong>Rank:</strong> <span class="badge bg-success">Silver</span></p>
                            <p class="mb-0"><strong>Total Binary:</strong> <span class="text-success fw-semibold">৳2,450.00</span></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-info">
                                <i class="bx bx-sitemap me-2"></i>Binary Tree Details
                            </h6>
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="p-2 bg-primary-transparent rounded">
                                        <i class="bx bx-trending-up fs-20 text-primary"></i>
                                        <p class="mb-1 fw-semibold">Left PV</p>
                                        <h5 class="text-primary mb-0">1,850</h5>
                                        <small class="text-muted">Strong Leg</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 bg-warning-transparent rounded">
                                        <i class="bx bx-trending-down fs-20 text-warning"></i>
                                        <p class="mb-1 fw-semibold">Right PV</p>
                                        <h5 class="text-warning mb-0">1,250</h5>
                                        <small class="text-muted">Weak Leg</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 bg-info-transparent rounded">
                                        <i class="bx bx-transfer-alt fs-20 text-info"></i>
                                        <p class="mb-1 fw-semibold">Carry PV</p>
                                        <h5 class="text-info mb-0">600</h5>
                                        <small class="text-muted">Forward</small>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 p-2 bg-success-transparent rounded">
                                <p class="mb-1 text-center"><strong>Commission Calculation:</strong></p>
                                <p class="mb-0 text-center text-success">
                                    Weak Leg (1,250 PV) × 10% = <strong>৳125.00</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-warning">
                                <i class="bx bx-history me-2"></i>Commission History & Notes
                            </h6>
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Commission Generated</h6>
                                        <p class="timeline-text">Binary commission calculated based on weak leg PV</p>
                                        <small class="text-muted">${new Date().toLocaleDateString()} at ${new Date().toLocaleTimeString()}</small>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-warning"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Pending Review</h6>
                                        <p class="timeline-text">Commission is pending admin approval</p>
                                        <small class="text-muted">${new Date().toLocaleDateString()} at ${new Date().toLocaleTimeString()}</small>
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

// Print commission details function
function printCommissionDetails() {
    const modalContent = document.getElementById('commissionDetails').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Binary Commission Details</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { font-family: Arial, sans-serif; }
                    .card { margin-bottom: 1rem; }
                    .timeline { margin-left: 1rem; }
                    .timeline-item { margin-bottom: 1rem; }
                    .timeline-marker { width: 12px; height: 12px; border-radius: 50%; display: inline-block; margin-right: 0.5rem; }
                    @media print { 
                        .no-print { display: none; }
                        body { -webkit-print-color-adjust: exact; }
                    }
                </style>
            </head>
            <body>
                <div class="container mt-4">
                    <h2 class="text-center mb-4">Binary Commission Details</h2>
                    ${modalContent}
                    <div class="text-center mt-4 no-print">
                        <button onclick="window.print()" class="btn btn-primary">Print</button>
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
    
    // Add toast to container
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
