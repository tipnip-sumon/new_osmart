@extends('admin.layouts.app')
@section('top_title','Admin Withdrawals')
@section('title','Withdrawal Requests')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="row my-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Withdrawal Requests Management
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-light btn-sm" id="bulkApproveBtn" disabled>
                        <i class="fas fa-check me-1"></i>
                        Bulk Approve
                    </button>
                    <button class="btn btn-light btn-sm" id="exportBtn">
                        <i class="fas fa-download me-1"></i>
                        Export
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Pending Requests</h6>
                                        <h4 class="mb-0">{{ $stats['pending_count'] }}</h4>
                                        <small>৳{{ number_format($stats['pending_amount'], 2) }}</small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-clock fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Completed</h6>
                                        <h4 class="mb-0">{{ $stats['completed_count'] }}</h4>
                                        <small>৳{{ number_format($stats['completed_amount'], 2) }}</small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Approved</h6>
                                        <h4 class="mb-0">{{ $stats['approved_count'] }}</h4>
                                        <small>৳{{ number_format($stats['approved_amount'], 2) }}</small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-thumbs-up fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Rejected</h6>
                                        <h4 class="mb-0">{{ $stats['rejected_count'] }}</h4>
                                        <small>৳{{ number_format($stats['rejected_amount'], 2) }}</small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-times-circle fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advanced Filters -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-light">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0 d-flex align-items-center">
                                    <i class="fas fa-filter me-2"></i>
                                    Advanced Filters
                                    <button class="btn btn-sm btn-outline-primary ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </h6>
                            </div>
                            <div class="collapse show" id="filterCollapse">
                                <div class="card-body">
                                    <form id="filterForm" method="GET">
                                        <div class="row g-3">
                                            <!-- Quick Filters -->
                                            <div class="col-md-2">
                                                <label class="form-label">Quick Filter</label>
                                                <select name="filter" class="form-select" id="quickFilter">
                                                    <option value="">All Time</option>
                                                    <option value="today">Today</option>
                                                    <option value="weekly">This Week</option>
                                                    <option value="monthly">This Month</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Status -->
                                            <div class="col-md-2">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="">All Status</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="approved">Approved</option>
                                                    <option value="rejected">Rejected</option>
                                                    <option value="processed">Processed</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Withdrawal Method -->
                                            <div class="col-md-2">
                                                <label class="form-label">Method</label>
                                                <select name="method" class="form-select">
                                                    <option value="">All Methods</option>
                                                    <option value="bank_transfer">Bank Transfer</option>
                                                    <option value="bkash">bKash</option>
                                                    <option value="nagad">Nagad</option>
                                                    <option value="rocket">Rocket</option>
                                                    <option value="upay">Upay</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Amount Range -->
                                            <div class="col-md-2">
                                                <label class="form-label">Amount Range</label>
                                                <select name="amount_range" class="form-select">
                                                    <option value="">All Amounts</option>
                                                    <option value="0-5000">৳0 - ৳5,000</option>
                                                    <option value="5000-20000">৳5,000 - ৳20,000</option>
                                                    <option value="20000-50000">৳20,000 - ৳50,000</option>
                                                    <option value="50000+">৳50,000+</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Search -->
                                            <div class="col-md-3">
                                                <label class="form-label">Search</label>
                                                <input type="text" name="search" class="form-control" placeholder="Search by user, reference...">
                                            </div>
                                            
                                            <!-- Filter Actions -->
                                            <div class="col-md-1">
                                                <label class="form-label">&nbsp;</label>
                                                <div class="d-flex gap-1">
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-secondary btn-sm" id="resetFilters">
                                                        <i class="fas fa-refresh"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Withdrawal Requests Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th width="5%">
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                </th>
                                <th width="10%">Date</th>
                                <th width="15%">User</th>
                                <th width="12%">Amount</th>
                                <th width="10%">Method</th>
                                <th width="15%">Account Details</th>
                                <th width="10%">Status</th>
                                <th width="8%">Priority</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($withdrawals as $withdrawal)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input withdrawal-checkbox" value="{{ $withdrawal->id }}">
                                </td>
                                <td>
                                    <small class="text-muted">{{ $withdrawal->created_at->format('M d, Y') }}</small><br>
                                    <small class="text-muted">{{ $withdrawal->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <span class="fw-semibold">{{ $withdrawal->user->name }}</span><br>
                                            <small class="text-muted">{{ $withdrawal->user->club_id ?? $withdrawal->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-primary fs-6">৳{{ number_format($withdrawal->net_amount, 2) }}</span>
                                    @if($withdrawal->fee > 0)
                                        <br><small class="text-muted">Fee: ৳{{ number_format($withdrawal->fee, 2) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($withdrawal->payment_method == 'bank_transfer')
                                        <span class="badge bg-primary-transparent text-primary">
                                            <i class="fas fa-university me-1"></i>Bank
                                        </span>
                                    @elseif($withdrawal->payment_method == 'bkash')
                                        <span class="badge bg-danger-transparent text-danger">
                                            <i class="fas fa-mobile-alt me-1"></i>bKash
                                        </span>
                                    @elseif($withdrawal->payment_method == 'nagad')
                                        <span class="badge bg-warning-transparent text-warning">
                                            <i class="fas fa-mobile-alt me-1"></i>Nagad
                                        </span>
                                    @elseif($withdrawal->payment_method == 'rocket')
                                        <span class="badge bg-info-transparent text-info">
                                            <i class="fas fa-mobile-alt me-1"></i>Rocket
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $withdrawal->payment_method)) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $withdrawal->sender_number ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    @if($withdrawal->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($withdrawal->status == 'approved')
                                        <span class="badge bg-info">Approved</span>
                                    @elseif($withdrawal->status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($withdrawal->status == 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($withdrawal->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($withdrawal->net_amount >= 50000)
                                        <span class="badge bg-danger-transparent text-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>High
                                        </span>
                                    @elseif($withdrawal->net_amount >= 10000)
                                        <span class="badge bg-primary-transparent text-primary">Normal</span>
                                    @else
                                        <span class="badge bg-secondary-transparent text-secondary">Low</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        @if($withdrawal->status == 'pending')
                                            <button class="btn btn-success btn-sm" onclick="approveWithdrawal({{ $withdrawal->id }})" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="rejectWithdrawal({{ $withdrawal->id }})" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @elseif($withdrawal->status == 'approved')
                                            <button class="btn btn-primary btn-sm" onclick="processWithdrawal({{ $withdrawal->id }})" title="Mark as Completed">
                                                <i class="fas fa-money-check-alt"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-info btn-sm" onclick="viewWithdrawal({{ $withdrawal->id }})" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('admin.users.show', $withdrawal->user_id) }}"><i class="fas fa-user me-2"></i>View User</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-history me-2"></i>History</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-envelope me-2"></i>Send Email</a></li>
                                                @if($withdrawal->status == 'pending')
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="rejectWithdrawal({{ $withdrawal->id }})"><i class="fas fa-ban me-2"></i>Reject Request</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No withdrawal requests found</h5>
                                        <p class="text-muted">There are currently no withdrawal requests to display.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <span class="text-muted">
                            Showing {{ $withdrawals->firstItem() ?? 0 }} to {{ $withdrawals->lastItem() ?? 0 }} of {{ $withdrawals->total() }} results
                        </span>
                    </div>
                    <div>
                        {{ $withdrawals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Withdrawal Details Modal -->
<div class="modal fade" id="withdrawalDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Withdrawal Request Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="withdrawalDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="modalApproveBtn">Approve</button>
                <button type="button" class="btn btn-danger" id="modalRejectBtn">Reject</button>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Reason Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Withdrawal Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="rejectionForm">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason</label>
                        <select name="reason" class="form-select" required>
                            <option value="">Select Reason</option>
                            <option value="insufficient_balance">Insufficient Balance</option>
                            <option value="invalid_account">Invalid Account Details</option>
                            <option value="suspicious_activity">Suspicious Activity</option>
                            <option value="kyc_pending">KYC Verification Pending</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Additional Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Provide additional details..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmRejectBtn">Confirm Rejection</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    .bg-primary-transparent {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
    
    .bg-success-transparent {
        background-color: rgba(40, 167, 69, 0.1) !important;
    }
    
    .bg-danger-transparent {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }
    
    .bg-warning-transparent {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
    
    .bg-info-transparent {
        background-color: rgba(23, 162, 184, 0.1) !important;
    }
    
    .bg-secondary-transparent {
        background-color: rgba(108, 117, 125, 0.1) !important;
    }
    
    .avatar-sm {
        width: 32px;
        height: 32px;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All Checkbox
    const selectAllCheckbox = document.getElementById('selectAll');
    const withdrawalCheckboxes = document.querySelectorAll('.withdrawal-checkbox');
    const bulkApproveBtn = document.getElementById('bulkApproveBtn');
    
    selectAllCheckbox.addEventListener('change', function() {
        withdrawalCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkButtons();
    });
    
    withdrawalCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkButtons);
    });
    
    function updateBulkButtons() {
        const checkedBoxes = document.querySelectorAll('.withdrawal-checkbox:checked');
        bulkApproveBtn.disabled = checkedBoxes.length === 0;
    }
    
    // Reset Filters
    document.getElementById('resetFilters').addEventListener('click', function() {
        document.getElementById('filterForm').reset();
        document.getElementById('filterForm').submit();
    });
    
    // Export functionality
    document.getElementById('exportBtn').addEventListener('click', function() {
        alert('Export functionality would be implemented here');
    });
    
    // Quick Filter Auto Submit
    document.getElementById('quickFilter').addEventListener('change', function() {
        if (this.value) {
            document.getElementById('filterForm').submit();
        }
    });
    
    // Bulk Approve
    document.getElementById('bulkApproveBtn').addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.withdrawal-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        
        if (ids.length === 0) {
            showAlert('error', 'Please select at least one withdrawal request to approve.');
            return;
        }
        
        if (confirm(`Are you sure you want to approve ${ids.length} withdrawal request(s)?`)) {
            // Show loading state
            const button = this;
            const originalContent = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Approving...';
            button.disabled = true;
            
            fetch('/admin/finance/withdrawals/bulk-approve', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    withdrawal_ids: ids
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    // Reload page to show updated status
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert('error', data.message);
                    button.innerHTML = originalContent;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'An error occurred while processing bulk approval');
                button.innerHTML = originalContent;
                button.disabled = false;
            });
        }
    });
});

// Individual action functions
function approveWithdrawal(id) {
    if (confirm('Are you sure you want to approve this withdrawal request?')) {
        // Show loading state
        const button = event.target.closest('button');
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
        
        fetch(`/admin/finance/withdrawals/${id}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showAlert('success', data.message);
                // Reload page to show updated status
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert('error', data.message);
                button.innerHTML = originalContent;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while approving the withdrawal');
            button.innerHTML = originalContent;
            button.disabled = false;
        });
    }
}

function rejectWithdrawal(id) {
    // Show rejection modal
    const modal = new bootstrap.Modal(document.getElementById('rejectionModal'));
    modal.show();
    
    // Store the withdrawal ID for later use
    document.getElementById('confirmRejectBtn').onclick = function() {
        const form = document.getElementById('rejectionForm');
        const formData = new FormData(form);
        const reason = formData.get('reason');
        
        if (!reason) {
            showAlert('error', 'Please select a rejection reason');
            return;
        }
        
        // Show loading state
        const button = this;
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Rejecting...';
        button.disabled = true;
        
        fetch(`/admin/finance/withdrawals/${id}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                rejection_reason: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                modal.hide();
                form.reset();
                // Reload page to show updated status
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert('error', data.message);
            }
            button.innerHTML = originalContent;
            button.disabled = false;
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while rejecting the withdrawal');
            button.innerHTML = originalContent;
            button.disabled = false;
        });
    };
}

function processWithdrawal(id) {
    if (confirm('Mark this withdrawal as completed? This action confirms that the payment has been sent.')) {
        // Show loading state
        const button = event.target.closest('button');
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
        
        fetch(`/admin/finance/withdrawals/${id}/process`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                // Reload page to show updated status
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert('error', data.message);
                button.innerHTML = originalContent;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while processing the withdrawal');
            button.innerHTML = originalContent;
            button.disabled = false;
        });
    }
}

function viewWithdrawal(id) {
    // Show loading state
    const modalContent = document.getElementById('withdrawalDetailsContent');
    modalContent.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><br>Loading...</div>';
    
    const modal = new bootstrap.Modal(document.getElementById('withdrawalDetailsModal'));
    modal.show();
    
    // Fetch withdrawal details
    fetch(`/admin/finance/withdrawals/${id}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const withdrawal = data.data;
                modalContent.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Request Information</h6>
                            <p><strong>Transaction ID:</strong> ${withdrawal.transaction_id || 'N/A'}</p>
                            <p><strong>Amount:</strong> ৳${parseFloat(withdrawal.amount).toLocaleString()}</p>
                            <p><strong>Fee:</strong> ৳${parseFloat(withdrawal.fee).toLocaleString()}</p>
                            <p><strong>Net Amount:</strong> ৳${parseFloat(withdrawal.net_amount).toLocaleString()}</p>
                            <p><strong>Method:</strong> ${withdrawal.payment_method.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}</p>
                            <p><strong>Status:</strong> <span class="badge bg-${getStatusColor(withdrawal.status)}">${withdrawal.status.charAt(0).toUpperCase() + withdrawal.status.slice(1)}</span></p>
                            <p><strong>Requested:</strong> ${withdrawal.created_at}</p>
                            ${withdrawal.processed_at ? `<p><strong>Processed:</strong> ${withdrawal.processed_at}</p>` : ''}
                        </div>
                        <div class="col-md-6">
                            <h6>User Information</h6>
                            <p><strong>Name:</strong> ${withdrawal.user.name}</p>
                            <p><strong>Email:</strong> ${withdrawal.user.email}</p>
                            <p><strong>Club ID:</strong> ${withdrawal.user.club_id}</p>
                            ${withdrawal.sender_number ? `<p><strong>Account:</strong> ${withdrawal.sender_number}</p>` : ''}
                        </div>
                        <div class="col-12 mt-3">
                            ${withdrawal.description ? `<h6>Description</h6><p>${withdrawal.description}</p>` : ''}
                            ${withdrawal.notes ? `<h6>Notes</h6><p>${withdrawal.notes}</p>` : ''}
                            ${withdrawal.rejection_reason ? `<h6>Rejection Reason</h6><p class="text-danger">${withdrawal.rejection_reason}</p>` : ''}
                            ${withdrawal.processed_by ? `<h6>Processed By</h6><p>${withdrawal.processed_by.name} (${withdrawal.processed_by.email})</p>` : ''}
                        </div>
                    </div>
                `;
                
                // Update modal buttons based on status
                const modalApproveBtn = document.getElementById('modalApproveBtn');
                const modalRejectBtn = document.getElementById('modalRejectBtn');
                
                if (withdrawal.status === 'pending') {
                    modalApproveBtn.style.display = 'inline-block';
                    modalRejectBtn.style.display = 'inline-block';
                    modalApproveBtn.onclick = () => { modal.hide(); approveWithdrawal(id); };
                    modalRejectBtn.onclick = () => { modal.hide(); rejectWithdrawal(id); };
                } else {
                    modalApproveBtn.style.display = 'none';
                    modalRejectBtn.style.display = 'none';
                }
            } else {
                modalContent.innerHTML = `<div class="text-center py-4 text-danger"><i class="fas fa-exclamation-triangle fa-2x"></i><br>Error loading withdrawal details</div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalContent.innerHTML = `<div class="text-center py-4 text-danger"><i class="fas fa-exclamation-triangle fa-2x"></i><br>Error loading withdrawal details</div>`;
        });
}

function getStatusColor(status) {
    switch(status) {
        case 'pending': return 'warning';
        case 'approved': return 'info';
        case 'completed': return 'success';
        case 'rejected': return 'danger';
        default: return 'secondary';
    }
}

function showAlert(type, message) {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '9999';
    alertDiv.style.minWidth = '300px';
    
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
@endpush
