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
                                        <h4 class="mb-0">{{ 25 }}</h4>
                                        <small>৳{{ number_format(125000, 2) }}</small>
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
                                        <h6 class="mb-1">Approved Today</h6>
                                        <h4 class="mb-0">{{ 12 }}</h4>
                                        <small>৳{{ number_format(75000, 2) }}</small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle fa-2x"></i>
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
                                        <h4 class="mb-0">{{ 3 }}</h4>
                                        <small>৳{{ number_format(15000, 2) }}</small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-times-circle fa-2x"></i>
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
                                        <h6 class="mb-1">Total This Month</h6>
                                        <h4 class="mb-0">{{ 156 }}</h4>
                                        <small>৳{{ number_format(850000, 2) }}</small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-chart-bar fa-2x"></i>
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
                            @php
                            $sampleWithdrawals = [
                                [
                                    'id' => 'WD-2025-001',
                                    'user_name' => 'John Smith',
                                    'user_email' => 'john.smith@email.com',
                                    'user_id' => 'USR-001',
                                    'amount' => 25000.00,
                                    'method' => 'bank_transfer',
                                    'account_details' => 'ABC Bank - 1234567890',
                                    'status' => 'pending',
                                    'priority' => 'high',
                                    'requested_at' => '2025-07-30 10:30:00',
                                    'note' => 'Urgent withdrawal request'
                                ],
                                [
                                    'id' => 'WD-2025-002',
                                    'user_name' => 'Sarah Johnson',
                                    'user_email' => 'sarah.j@email.com',
                                    'user_id' => 'USR-002',
                                    'amount' => 15000.00,
                                    'method' => 'bkash',
                                    'account_details' => '01712345678',
                                    'status' => 'approved',
                                    'priority' => 'normal',
                                    'requested_at' => '2025-07-30 14:15:00',
                                    'note' => ''
                                ],
                                [
                                    'id' => 'WD-2025-003',
                                    'user_name' => 'Mike Davis',
                                    'user_email' => 'mike.davis@email.com',
                                    'user_id' => 'USR-003',
                                    'amount' => 8000.00,
                                    'method' => 'nagad',
                                    'account_details' => '01798765432',
                                    'status' => 'rejected',
                                    'priority' => 'low',
                                    'requested_at' => '2025-07-29 16:45:00',
                                    'note' => 'Insufficient balance'
                                ],
                                [
                                    'id' => 'WD-2025-004',
                                    'user_name' => 'Emily Wilson',
                                    'user_email' => 'emily.wilson@email.com',
                                    'user_id' => 'USR-004',
                                    'amount' => 35000.00,
                                    'method' => 'bank_transfer',
                                    'account_details' => 'XYZ Bank - 9876543210',
                                    'status' => 'processed',
                                    'priority' => 'high',
                                    'requested_at' => '2025-07-29 11:20:00',
                                    'note' => 'VIP customer priority'
                                ],
                                [
                                    'id' => 'WD-2025-005',
                                    'user_name' => 'David Brown',
                                    'user_email' => 'david.brown@email.com',
                                    'user_id' => 'USR-005',
                                    'amount' => 12000.00,
                                    'method' => 'rocket',
                                    'account_details' => '01687654321',
                                    'status' => 'pending',
                                    'priority' => 'normal',
                                    'requested_at' => '2025-07-28 09:15:00',
                                    'note' => ''
                                ]
                            ];
                            @endphp
                            
                            @foreach($sampleWithdrawals as $withdrawal)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input withdrawal-checkbox" value="{{ $withdrawal['id'] }}">
                                </td>
                                <td>
                                    <small class="text-muted">{{ date('M d, Y', strtotime($withdrawal['requested_at'])) }}</small><br>
                                    <small class="text-muted">{{ date('h:i A', strtotime($withdrawal['requested_at'])) }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <span class="fw-semibold">{{ $withdrawal['user_name'] }}</span><br>
                                            <small class="text-muted">{{ $withdrawal['user_id'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-primary fs-6">৳{{ number_format($withdrawal['amount'], 2) }}</span>
                                </td>
                                <td>
                                    @if($withdrawal['method'] == 'bank_transfer')
                                        <span class="badge bg-primary-transparent text-primary">
                                            <i class="fas fa-university me-1"></i>Bank
                                        </span>
                                    @elseif($withdrawal['method'] == 'bkash')
                                        <span class="badge bg-danger-transparent text-danger">
                                            <i class="fas fa-mobile-alt me-1"></i>bKash
                                        </span>
                                    @elseif($withdrawal['method'] == 'nagad')
                                        <span class="badge bg-warning-transparent text-warning">
                                            <i class="fas fa-mobile-alt me-1"></i>Nagad
                                        </span>
                                    @elseif($withdrawal['method'] == 'rocket')
                                        <span class="badge bg-info-transparent text-info">
                                            <i class="fas fa-mobile-alt me-1"></i>Rocket
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($withdrawal['method']) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $withdrawal['account_details'] }}</small>
                                </td>
                                <td>
                                    @if($withdrawal['status'] == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($withdrawal['status'] == 'approved')
                                        <span class="badge bg-info">Approved</span>
                                    @elseif($withdrawal['status'] == 'processed')
                                        <span class="badge bg-success">Processed</span>
                                    @elseif($withdrawal['status'] == 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if($withdrawal['priority'] == 'high')
                                        <span class="badge bg-danger-transparent text-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>High
                                        </span>
                                    @elseif($withdrawal['priority'] == 'normal')
                                        <span class="badge bg-primary-transparent text-primary">Normal</span>
                                    @else
                                        <span class="badge bg-secondary-transparent text-secondary">Low</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        @if($withdrawal['status'] == 'pending')
                                            <button class="btn btn-success btn-sm" onclick="approveWithdrawal('{{ $withdrawal['id'] }}')" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="rejectWithdrawal('{{ $withdrawal['id'] }}')" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @elseif($withdrawal['status'] == 'approved')
                                            <button class="btn btn-primary btn-sm" onclick="processWithdrawal('{{ $withdrawal['id'] }}')" title="Mark as Processed">
                                                <i class="fas fa-money-check-alt"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-info btn-sm" onclick="viewWithdrawal('{{ $withdrawal['id'] }}')" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>View User</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-history me-2"></i>History</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-envelope me-2"></i>Send Email</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-ban me-2"></i>Block User</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <span class="text-muted">Showing 1 to 5 of 156 results</span>
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                            <li class="page-item active">
                                <span class="page-link">1</span>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
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
        
        if (confirm(`Are you sure you want to approve ${ids.length} withdrawal requests?`)) {
            // Implement bulk approval logic
            alert('Bulk approval functionality would be implemented here');
        }
    });
});

// Individual action functions
function approveWithdrawal(id) {
    if (confirm('Are you sure you want to approve this withdrawal request?')) {
        // Implement approval logic
        alert(`Approval functionality for ${id} would be implemented here`);
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
        
        // Implement rejection logic
        alert(`Rejection functionality for ${id} would be implemented here`);
        modal.hide();
        form.reset();
    };
}

function processWithdrawal(id) {
    if (confirm('Mark this withdrawal as processed? This action confirms that the payment has been sent.')) {
        // Implement process logic
        alert(`Process functionality for ${id} would be implemented here`);
    }
}

function viewWithdrawal(id) {
    // Load withdrawal details and show modal
    const modalContent = document.getElementById('withdrawalDetailsContent');
    modalContent.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h6>Request Information</h6>
                <p><strong>Request ID:</strong> ${id}</p>
                <p><strong>Amount:</strong> ৳25,000.00</p>
                <p><strong>Method:</strong> Bank Transfer</p>
                <p><strong>Status:</strong> <span class="badge bg-warning">Pending</span></p>
            </div>
            <div class="col-md-6">
                <h6>User Information</h6>
                <p><strong>Name:</strong> John Smith</p>
                <p><strong>Email:</strong> john.smith@email.com</p>
                <p><strong>Phone:</strong> +880 1712345678</p>
                <p><strong>Balance:</strong> ৳75,000.00</p>
            </div>
            <div class="col-12 mt-3">
                <h6>Account Details</h6>
                <p><strong>Bank:</strong> ABC Bank Limited</p>
                <p><strong>Account Name:</strong> John Smith</p>
                <p><strong>Account Number:</strong> 1234567890</p>
                <p><strong>Branch:</strong> Dhanmondi Branch</p>
            </div>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('withdrawalDetailsModal'));
    modal.show();
}
</script>
@endpush
