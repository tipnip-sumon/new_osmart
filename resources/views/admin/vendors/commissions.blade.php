@extends('admin.layouts.app')

@section('title', 'Vendor Commissions')

@push('styles')
<style>
.commission-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #e0e6ed;
    text-align: center;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.stat-card.total { border-left: 4px solid #2563eb; }
.stat-card.pending { border-left: 4px solid #f59e0b; }
.stat-card.approved { border-left: 4px solid #10b981; }
.stat-card.paid { border-left: 4px solid #8b5cf6; }

.stat-amount {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-card.total .stat-amount { color: #2563eb; }
.stat-card.pending .stat-amount { color: #f59e0b; }
.stat-card.approved .stat-amount { color: #10b981; }
.stat-card.paid .stat-amount { color: #8b5cf6; }

.stat-label {
    color: #64748b;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.commission-table {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e0e6ed;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-approved {
    background: #dcfce7;
    color: #166534;
}

.status-paid {
    background: #e0e7ff;
    color: #3730a3;
}

.status-rejected {
    background: #fee2e2;
    color: #991b1b;
}

.commission-type-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
}

.type-direct { background: #dbeafe; color: #1e40af; }
.type-level1 { background: #d1fae5; color: #047857; }
.type-level2 { background: #fde68a; color: #92400e; }
.type-binary { background: #e879f9; color: #86198f; }
.type-unilevel { background: #fed7d7; color: #c53030; }

.filter-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid #e0e6ed;
}

@media (max-width: 768px) {
    .commission-stats {
        grid-template-columns: 1fr;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Vendor Commissions</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">Vendors</a></li>
                    <li class="breadcrumb-item active">Commissions</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-success" onclick="bulkApprove()">
                <i class="ti ti-check me-1"></i>Bulk Approve
            </button>
            <button class="btn btn-primary" onclick="exportCommissions()">
                <i class="ti ti-download me-1"></i>Export
            </button>
        </div>
    </div>

    <!-- Commission Statistics -->
    <div class="commission-stats">
        <div class="stat-card total">
            <div class="stat-amount">${{ number_format($totalCommissions, 2) }}</div>
            <div class="stat-label">Total Commissions</div>
        </div>
        <div class="stat-card pending">
            <div class="stat-amount">${{ number_format($pendingCommissions, 2) }}</div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-card approved">
            <div class="stat-amount">${{ number_format($approvedCommissions, 2) }}</div>
            <div class="stat-label">Approved</div>
        </div>
        <div class="stat-card paid">
            <div class="stat-amount">${{ number_format($paidCommissions, 2) }}</div>
            <div class="stat-label">Paid</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-section">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Commission Type</label>
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="direct" {{ request('type') === 'direct' ? 'selected' : '' }}>Direct</option>
                    <option value="level1" {{ request('type') === 'level1' ? 'selected' : '' }}>Level 1</option>
                    <option value="level2" {{ request('type') === 'level2' ? 'selected' : '' }}>Level 2</option>
                    <option value="binary" {{ request('type') === 'binary' ? 'selected' : '' }}>Binary</option>
                    <option value="unilevel" {{ request('type') === 'unilevel' ? 'selected' : '' }}>Unilevel</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Date From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Date To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.vendors.commissions') }}" class="btn btn-light">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Commissions Table -->
    <div class="commission-table">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="ti ti-coins me-2"></i>Commission Records</h5>
            <span class="badge bg-primary">{{ $commissions->total() }} Total</span>
        </div>
        <div class="card-body p-0">
            @if($commissions->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th>User</th>
                            <th>Referred User</th>
                            <th>Order</th>
                            <th>Type</th>
                            <th>Level</th>
                            <th>Order Amount</th>
                            <th>Rate</th>
                            <th>Commission</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($commissions as $commission)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input commission-checkbox" value="{{ $commission->id }}">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <span class="text-white text-sm">{{ substr($commission->user->name ?? 'U', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $commission->user->name ?? 'Unknown' }}</div>
                                        <small class="text-muted">{{ $commission->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($commission->referredUser)
                                <div>
                                    <div class="fw-medium">{{ $commission->referredUser->name }}</div>
                                    <small class="text-muted">{{ $commission->referredUser->email }}</small>
                                </div>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($commission->order)
                                <a href="{{ route('admin.orders.show', $commission->order_id) }}" class="text-decoration-none">
                                    #{{ $commission->order_id }}
                                </a>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="commission-type-badge type-{{ $commission->commission_type }}">
                                    {{ ucfirst($commission->commission_type) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">Level {{ $commission->level ?? 0 }}</span>
                            </td>
                            <td>${{ number_format($commission->order_amount, 2) }}</td>
                            <td>{{ $commission->commission_rate }}%</td>
                            <td class="fw-bold text-success">${{ number_format($commission->commission_amount, 2) }}</td>
                            <td>
                                <span class="status-badge status-{{ $commission->status }}">
                                    {{ ucfirst($commission->status) }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $commission->earned_at ? $commission->earned_at->format('M d, Y') : $commission->created_at->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $commission->earned_at ? $commission->earned_at->format('h:i A') : $commission->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if($commission->status === 'pending')
                                        <li><a class="dropdown-item text-success" href="#" onclick="approveCommission({{ $commission->id }})">
                                            <i class="ti ti-check me-1"></i>Approve
                                        </a></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="rejectCommission({{ $commission->id }})">
                                            <i class="ti ti-x me-1"></i>Reject
                                        </a></li>
                                        @endif
                                        @if($commission->status === 'approved')
                                        <li><a class="dropdown-item text-primary" href="#" onclick="markAsPaid({{ $commission->id }})">
                                            <i class="ti ti-wallet me-1"></i>Mark as Paid
                                        </a></li>
                                        @endif
                                        <li><a class="dropdown-item" href="#" onclick="viewDetails({{ $commission->id }})">
                                            <i class="ti ti-eye me-1"></i>View Details
                                        </a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="ti ti-coins display-4 text-muted"></i>
                <h5 class="mt-3">No Commissions Found</h5>
                <p class="text-muted">No commission records match your current filters.</p>
            </div>
            @endif
        </div>
        @if($commissions->hasPages())
        <div class="card-footer">
            {{ $commissions->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.commission-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Bulk approve function
function bulkApprove() {
    const selectedIds = Array.from(document.querySelectorAll('.commission-checkbox:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedIds.length === 0) {
        alert('Please select commissions to approve.');
        return;
    }
    
    if (confirm(`Are you sure you want to approve ${selectedIds.length} commission(s)?`)) {
        // Implement bulk approve functionality
        console.log('Bulk approving:', selectedIds);
    }
}

// Individual actions
function approveCommission(id) {
    if (confirm('Are you sure you want to approve this commission?')) {
        // Implement approve functionality
        console.log('Approving commission:', id);
    }
}

function rejectCommission(id) {
    if (confirm('Are you sure you want to reject this commission?')) {
        // Implement reject functionality
        console.log('Rejecting commission:', id);
    }
}

function markAsPaid(id) {
    if (confirm('Are you sure you want to mark this commission as paid?')) {
        // Implement mark as paid functionality
        console.log('Marking as paid:', id);
    }
}

function viewDetails(id) {
    // Implement view details functionality
    console.log('Viewing details for commission:', id);
}

function exportCommissions() {
    // Implement export functionality
    console.log('Exporting commissions');
}
</script>
@endpush
@endsection
