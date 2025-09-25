@extends('admin.layouts.app')

@section('title', 'Direct Commissions')

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
                    <li class="breadcrumb-item active" aria-current="page">Direct Commissions</li>
                </ol>
            </nav>
            <h1 class="page-title fw-medium fs-18 mb-0">Direct Commissions</h1>
        </div>
        <div class="btn-list">
            <a href="{{ route('admin.commissions.export', ['type' => 'referral']) }}" class="btn btn-success-light btn-wave me-2">
                <i class="bx bx-download me-1"></i> Export All Data
            </a>
            <button class="btn btn-primary-light btn-wave me-0" onclick="window.location.reload()">
                <i class="bx bx-refresh me-1"></i> Refresh Page
            </button>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="row">
        <!-- Direct Commission Statistics -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Direct Commission Statistics</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden bg-primary-transparent">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-primary">
                                                <i class="bx bx-user-plus fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Total Direct</p>
                                                    <h4 class="fw-semibold mt-1"><span class="currency-symbol">৳</span>{{ number_format($stats['total_direct'], 2) }}</h4>
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
                                                    <p class="text-muted mb-0">Paid Direct</p>
                                                    <h4 class="fw-semibold mt-1"><span class="currency-symbol">৳</span>{{ number_format($stats['paid_direct'], 2) }}</h4>
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
                                                    <p class="text-muted mb-0">Pending Direct</p>
                                                    <h4 class="fw-semibold mt-1"><span class="currency-symbol">৳</span>{{ number_format($stats['pending_direct'], 2) }}</h4>
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
                                                    <h4 class="fw-semibold mt-1"><span class="currency-symbol">৳</span>{{ number_format($stats['this_month_direct'], 2) }}</h4>
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

        <!-- Direct Commissions Table -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title">Direct Commission Records</div>
                    <div class="d-flex align-items-center gap-2">
                        <!-- Filters -->
                        <form method="GET" action="{{ route('admin.commissions.direct') }}" class="d-flex gap-2">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" onchange="this.form.submit()">
                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" onchange="this.form.submit()">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search user..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bx bx-search me-1"></i> Search
                            </button>
                                <i class="bx bx-search"></i>
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
                                    <th>Referred User</th>
                                    <th>Order Amount</th>
                                    <th>Commission Rate</th>
                                    <th>Commission Amount</th>
                                    <th>Level</th>
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
                                            @if($commission->referredUser)
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar avatar-sm avatar-rounded">
                                                        {{ substr($commission->referredUser->name, 0, 1) }}
                                                    </span>
                                                    <div class="ms-2">
                                                        <p class="mb-0 fw-semibold">{{ $commission->referredUser->name }}</p>
                                                        <p class="mb-0 text-muted fs-12">{{ $commission->referredUser->email }}</p>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-semibold"><span class="currency-symbol">৳</span>{{ number_format($commission->order_amount ?? 0, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-transparent">{{ number_format(($commission->commission_rate ?? 0) * 100, 1) }}%</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-success"><span class="currency-symbol">৳</span>{{ number_format($commission->commission_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-transparent">Level {{ $commission->level ?? '1' }}</span>
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
                                            <div class="action-buttons">
                                                <button type="button" class="btn btn-sm btn-info" onclick="viewCommission({{ $commission->id }})">
                                                    <i class="bx bx-eye me-1"></i> View
                                                </button>
                                                @if($commission->status !== 'paid')
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-bs-toggle="dropdown">
                                                            <i class="bx bx-edit me-1"></i> Update
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @if($commission->status !== 'approved')
                                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="updateStatus({{ $commission->id }}, 'approved')">
                                                                    <i class="bx bx-check me-2"></i>Mark as Approved
                                                                </a></li>
                                                            @endif
                                                            @if($commission->status !== 'paid')
                                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="updateStatus({{ $commission->id }}, 'paid')">
                                                                    <i class="bx bx-money me-2"></i>Mark as Paid
                                                                </a></li>
                                                            @endif
                                                            @if($commission->status !== 'cancelled')
                                                                <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="updateStatus({{ $commission->id }}, 'cancelled')">
                                                                    <i class="bx bx-x me-2"></i>Cancel
                                                                </a></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">No direct commissions found</td>
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

    <!-- Bulk Actions -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card custom-card bulk-actions-container">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <span class="fw-semibold">Bulk Actions:</span>
                        <button class="btn btn-sm btn-success-light" onclick="bulkUpdateStatus('approved')" id="bulkActions" style="display: none;">
                            <i class="bx bx-check me-1"></i> Approve Selected
                        </button>
                        <button class="btn btn-sm btn-primary-light" onclick="bulkUpdateStatus('paid')" id="bulkActionsPaid" style="display: none;">
                            <i class="bx bx-money me-1"></i> Mark as Paid
                        </button>
                        <button class="btn btn-sm btn-danger-light" onclick="bulkUpdateStatus('cancelled')" id="bulkActionsCancel" style="display: none;">
                            <i class="bx bx-x me-1"></i> Cancel Selected
                        </button>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Commission Details Modal -->
<div class="modal fade" id="commissionModal" tabindex="-1" aria-labelledby="commissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commissionModalLabel">
                    <i class="bx bx-receipt me-2"></i>Direct Commission Details
                </h5>
                <div class="ms-auto">
                    <button type="button" class="btn btn-sm btn-outline-light me-2" onclick="printCommissionDetails()">
                        <i class="bx bx-printer me-1"></i> Print
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body" id="commissionDetails">
                <!-- Commission details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toggle select all checkboxes
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.commission-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    toggleBulkActions();
}

// Toggle bulk actions visibility
function toggleBulkActions() {
    const checkboxes = document.querySelectorAll('.commission-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const bulkActionsPaid = document.getElementById('bulkActionsPaid');
    const bulkActionsCancel = document.getElementById('bulkActionsCancel');
    
    if (checkboxes.length > 0) {
        bulkActions.style.display = 'inline-block';
        bulkActionsPaid.style.display = 'inline-block';
        bulkActionsCancel.style.display = 'inline-block';
    } else {
        bulkActions.style.display = 'none';
        bulkActionsPaid.style.display = 'none';
        bulkActionsCancel.style.display = 'none';
    }
}

// Add event listeners to checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.commission-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkActions);
    });
});

// Update commission status
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

// Bulk update status
function bulkUpdateStatus(status) {
    const checkboxes = document.querySelectorAll('.commission-checkbox:checked');
    const commissionIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (commissionIds.length === 0) {
        showToast('Please select at least one commission', 'warning');
        return;
    }
    
    if (confirm(`Are you sure you want to mark ${commissionIds.length} commission(s) as ${status}?`)) {
        fetch('/admin/commissions/bulk-update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                commission_ids: commissionIds,
                status: status 
            })
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

// View commission details (enhanced)
function viewCommission(commissionId) {
    document.getElementById('commissionDetails').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 mb-0">Loading commission details...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('commissionModal'));
    modal.show();
    
    // Enhanced commission details display
    setTimeout(() => {
        document.getElementById('commissionDetails').innerHTML = `
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-primary">
                                <i class="bx bx-receipt me-2"></i>Commission Information
                            </h6>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-2"><strong>Commission ID:</strong> #COMM-${commissionId.toString().padStart(6, '0')}</p>
                                    <p class="mb-2"><strong>Type:</strong> <span class="badge bg-primary-transparent">Direct Commission</span></p>
                                    <p class="mb-2"><strong>Level:</strong> <span class="badge bg-info-transparent">Level 1</span></p>
                                    <p class="mb-0"><strong>Commission Rate:</strong> <span class="text-success">5.0%</span></p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-2"><strong>Order Amount:</strong> <span class="text-primary fw-semibold">৳1,000.00</span></p>
                                    <p class="mb-2"><strong>Commission Amount:</strong> <span class="text-success fw-semibold">৳50.00</span></p>
                                    <p class="mb-2"><strong>Status:</strong> <span class="badge bg-warning">Pending</span></p>
                                    <p class="mb-0"><strong>Date Created:</strong> ${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-success">
                                <i class="bx bx-user me-2"></i>Referrer Information
                            </h6>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-md avatar-rounded bg-primary text-white me-3">
                                    JD
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold">John Doe</p>
                                    <p class="mb-0 text-muted small">john.doe@example.com</p>
                                    <p class="mb-0 text-muted small">Phone: +1 (555) 123-4567</p>
                                </div>
                            </div>
                            <p class="mb-1"><strong>Join Date:</strong> Jan 15, 2024</p>
                            <p class="mb-0"><strong>Total Referrals:</strong> <span class="badge bg-info">12</span></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-info">
                                <i class="bx bx-user-plus me-2"></i>Referred User Information
                            </h6>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-md avatar-rounded bg-success text-white me-3">
                                    JS
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold">Jane Smith</p>
                                    <p class="mb-0 text-muted small">jane.smith@example.com</p>
                                    <p class="mb-0 text-muted small">Phone: +1 (555) 987-6543</p>
                                </div>
                            </div>
                            <p class="mb-1"><strong>Join Date:</strong> ${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                            <p class="mb-0"><strong>Order Count:</strong> <span class="badge bg-primary">1</span></p>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }, 1000);
}

// Print commission details function
function printCommissionDetails() {
    const modalContent = document.getElementById('commissionDetails').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Direct Commission Details</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { font-family: Arial, sans-serif; }
                    .card { margin-bottom: 1rem; }
                    @media print { 
                        .no-print { display: none; }
                        body { -webkit-print-color-adjust: exact; }
                    }
                </style>
            </head>
            <body>
                <div class="container mt-4">
                    <h2 class="text-center mb-4">Direct Commission Details</h2>
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

// Toast notification function (enhanced)
function showToast(message, type) {
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

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

    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${toastColors[type] || 'secondary'} border-0 mb-2`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="bx ${toastIcons[type] || 'bx-info-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast, { delay: 4000 });
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', () => {
        if (toastContainer.contains(toast)) {
            toastContainer.removeChild(toast);
        }
    });
}
</script>
@endpush

@push('styles')
<style>
/* Professional styling for direct commissions */
.col-xl-2-4 {
    flex: 0 0 auto;
    width: 20%;
}

@media (max-width: 1199.98px) {
    .col-xl-2-4 {
        width: 50%;
    }
}

@media (max-width: 767.98px) {
    .col-xl-2-4 {
        width: 100%;
    }
}

/* Button and dropdown improvements */
.btn-group .btn {
    font-weight: 500;
}

.dropdown-menu {
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    border: none;
    border-radius: 0.5rem;
}

.dropdown-item {
    padding: 8px 16px;
    font-weight: 500;
    transition: all 0.15s ease-in-out;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

/* Table improvements */
.table th {
    font-weight: 600;
    background-color: #f8f9fa;
    border-top: none;
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 0.025em;
}

.table td {
    vertical-align: middle;
}

.table tbody tr {
    transition: all 0.15s ease-in-out;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
    transform: translateY(-1px);
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
}

/* Badge improvements */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.375rem 0.75rem;
}

/* Statistics cards hover effects */
.card.custom-card {
    transition: all 0.3s ease;
}

.card.custom-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

/* Bulk actions styling */
.bulk-actions-container {
    background: linear-gradient(45deg, #f8f9fa, #e9ecef);
    border: 1px solid #dee2e6;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.bulk-actions-container:hover {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* Modal enhancements */
.modal-content {
    border: none;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    border-radius: 0.5rem;
}

.modal-header {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    border-bottom: none;
    border-radius: 0.5rem 0.5rem 0 0;
}

.modal-header .btn-close {
    filter: invert(1);
}

/* Professional enhancements */
.table-responsive {
    border-radius: 0.375rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.btn {
    transition: all 0.15s ease-in-out;
    border-radius: 0.375rem;
    font-weight: 500;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
}

.form-control, .form-select {
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    border-radius: 0.375rem;
}

.form-control:focus, .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Currency symbol styling */
.currency-symbol {
    font-weight: 600;
    color: #198754;
}

/* Action button group styling */
.action-buttons {
    display: flex;
    gap: 0.25rem;
    align-items: center;
}

.action-buttons .btn {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

/* Toast container positioning */
#toast-container {
    z-index: 9999 !important;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}
</style>
@endpush
