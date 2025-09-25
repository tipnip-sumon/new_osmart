@extends('admin.layouts.app')

@section('title', 'Leadership Bonus')

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
                    <li class="breadcrumb-item active" aria-current="page">Leadership Bonus</li>
                </ol>
            </nav>
            <h1 class="page-title fw-medium fs-18 mb-0">Leadership Bonus</h1>
        </div>
        <div class="btn-list">
            <a href="{{ route('admin.commissions.export', ['type' => 'performance']) }}" class="btn btn-success-light btn-wave me-2">
                <i class="bx bx-download me-1"></i> Export Data
            </a>
            <button class="btn btn-primary-light btn-wave me-0" onclick="window.location.reload()">
                <i class="bx bx-refresh me-1"></i> Refresh Page
            </button>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="row">
        <!-- Leadership Statistics -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Leadership Bonus Statistics</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden bg-warning-transparent">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-warning">
                                                <i class="bx bx-crown fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Total Leadership</p>
                                                    <h4 class="fw-semibold mt-1">৳{{ number_format($stats['total_leadership'], 2) }}</h4>
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
                                                    <p class="text-muted mb-0">Paid Leadership</p>
                                                    <h4 class="fw-semibold mt-1">৳{{ number_format($stats['paid_leadership'], 2) }}</h4>
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
                                                <i class="bx bx-time fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Pending Leadership</p>
                                                    <h4 class="fw-semibold mt-1">৳{{ number_format($stats['pending_leadership'], 2) }}</h4>
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
                                                    <h4 class="fw-semibold mt-1">৳{{ number_format($stats['this_month_leadership'], 2) }}</h4>
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

        <!-- Leadership Levels -->
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Leadership Levels Distribution</div>
                </div>
                <div class="card-body">
                    @if($leadership_levels->count() > 0)
                        @foreach($leadership_levels as $level)
                            <div class="d-flex align-items-center justify-content-between mb-3 p-3 border rounded level-distribution-item">
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-sm avatar-rounded bg-warning me-3">
                                        {{ $level->level }}
                                    </span>
                                    <div>
                                        <p class="mb-0 fw-semibold">Leadership Level {{ $level->level }}</p>
                                        <p class="mb-0 text-muted fs-12">{{ $level->count }} participants</p>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h6 class="fw-semibold mb-0 text-success">৳{{ number_format($level->total, 2) }}</h6>
                                    <small class="text-muted">Total Earned</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-4">No leadership level data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Leadership Requirements -->
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Leadership Qualification Matrix</div>
                </div>
                <div class="card-body">
                    <div class="leadership-matrix">
                        <div class="row text-center mb-3">
                            <div class="col-3"><strong>Level</strong></div>
                            <div class="col-3"><strong>Team Size</strong></div>
                            <div class="col-3"><strong>Volume</strong></div>
                            <div class="col-3"><strong>Bonus %</strong></div>
                        </div>
                        
                        <div class="row align-items-center mb-2 p-2 border rounded leadership-level-item">
                            <div class="col-3">
                                <span class="badge bg-bronze-transparent">Bronze</span>
                            </div>
                            <div class="col-3">10+ Active</div>
                            <div class="col-3">৳2,00,000</div>
                            <div class="col-3">
                                <span class="badge bg-success-transparent">2%</span>
                            </div>
                        </div>
                        
                        <div class="row align-items-center mb-2 p-2 border rounded leadership-level-item">
                            <div class="col-3">
                                <span class="badge bg-silver-transparent">Silver</span>
                            </div>
                            <div class="col-3">25+ Active</div>
                            <div class="col-3">৳5,00,000</div>
                            <div class="col-3">
                                <span class="badge bg-success-transparent">3%</span>
                            </div>
                        </div>
                        
                        <div class="row align-items-center mb-2 p-2 border rounded leadership-level-item">
                            <div class="col-3">
                                <span class="badge bg-warning-transparent">Gold</span>
                            </div>
                            <div class="col-3">50+ Active</div>
                            <div class="col-3">৳10,00,000</div>
                            <div class="col-3">
                                <span class="badge bg-success-transparent">4%</span>
                            </div>
                        </div>
                        
                        <div class="row align-items-center mb-2 p-2 border rounded leadership-level-item">
                            <div class="col-3">
                                <span class="badge bg-primary-transparent">Platinum</span>
                            </div>
                            <div class="col-3">100+ Active</div>
                            <div class="col-3">৳25,00,000</div>
                            <div class="col-3">
                                <span class="badge bg-success-transparent">5%</span>
                            </div>
                        </div>
                        
                        <div class="row align-items-center p-2 border rounded leadership-level-item">
                            <div class="col-3">
                                <span class="badge bg-dark-transparent">Diamond</span>
                            </div>
                            <div class="col-3">250+ Active</div>
                            <div class="col-3">৳50,00,000</div>
                            <div class="col-3">
                                <span class="badge bg-success-transparent">6%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leadership Bonus Table -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title">Leadership Bonus Records</div>
                    <div class="d-flex align-items-center gap-2">
                        <!-- Filters -->
                        <form method="GET" action="{{ route('admin.commissions.leadership') }}" class="d-flex gap-2">
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
                                    <th>Leader</th>
                                    <th>Leadership Level</th>
                                    <th>Team Volume</th>
                                    <th>Bonus Rate</th>
                                    <th>Bonus Amount</th>
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
                                            @php
                                                $levelNames = [1 => 'Bronze', 2 => 'Silver', 3 => 'Gold', 4 => 'Platinum', 5 => 'Diamond'];
                                                $levelColors = [1 => 'secondary', 2 => 'info', 3 => 'warning', 4 => 'primary', 5 => 'dark'];
                                                $level = $commission->level ?? 1;
                                            @endphp
                                            <span class="badge bg-{{ $levelColors[$level] ?? 'secondary' }}-transparent">
                                                {{ $levelNames[$level] ?? 'Level ' . $level }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">৳{{ number_format($commission->order_amount ?? 0, 2) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $bonusRates = [1 => 2, 2 => 3, 3 => 4, 4 => 5, 5 => 6];
                                            @endphp
                                            <span class="badge bg-success-transparent">{{ $bonusRates[$level] ?? 2 }}%</span>
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
                                        <td colspan="9" class="text-center py-4">No leadership bonus records found</td>
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
.leadership-matrix {
    font-size: 13px;
}

.leadership-matrix .row {
    margin: 0;
}

/* Leadership level item styling */
.leadership-level-item {
    transition: all 0.3s ease;
    background: linear-gradient(45deg, #ffffff, #f8f9fa);
    border: 2px solid #dee2e6 !important;
    margin-bottom: 8px !important;
}

.leadership-level-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-color: #007bff !important;
}

/* Badge color customizations */
.bg-bronze-transparent {
    background-color: rgba(205, 127, 50, 0.1) !important;
    color: #cd7f32 !important;
    border: 1px solid rgba(205, 127, 50, 0.2);
    font-weight: 600;
}

.bg-silver-transparent {
    background-color: rgba(169, 169, 169, 0.1) !important;
    color: #696969 !important;
    border: 1px solid rgba(169, 169, 169, 0.2);
    font-weight: 600;
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

/* Level distribution improvements */
.level-distribution-item {
    transition: all 0.3s ease;
    background: #ffffff;
}

.level-distribution-item:hover {
    background: #f8f9fa;
    transform: translateX(2px);
}

/* Statistics cards hover effects */
.card.custom-card {
    transition: all 0.3s ease;
}

.card.custom-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
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
    // Show a modal with leadership bonus details
    const modal = `
        <div class="modal fade" id="commissionModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bx bx-crown me-2"></i>Leadership Bonus Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Loading leadership bonus details for ID: ${commissionId}...</p>
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
}

function showToast(message, type) {
    // Create professional toast notification
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bx ${type === 'success' ? 'bx-check-circle' : 'bx-error-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
    
    // Add toast to container
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    // Show toast
    const toastElement = toastContainer.lastElementChild;
    const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
    toast.show();
    
    // Remove toast from DOM after hiding
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}
</script>
@endpush
