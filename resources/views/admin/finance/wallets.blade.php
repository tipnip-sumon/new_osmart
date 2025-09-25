@extends('admin.layouts.app')
@section('top_title','Admin User Wallets')
@section('title','User Wallets Management')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="row my-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-wallet me-2"></i>
                    User Wallets Management
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#adjustBalanceModal">
                        <i class="fas fa-plus me-1"></i>
                        Adjust Balance
                    </button>
                    <button class="btn btn-light btn-sm" id="bulkUpdateBtn" disabled>
                        <i class="fas fa-edit me-1"></i>
                        Bulk Update
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
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Total Wallet Balance</h6>
                                        <h4 class="mb-0">৳{{ number_format(2500000, 2) }}</h4>
                                        <small>{{ 1250 }} Active Wallets</small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-coins fa-2x"></i>
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
                                        <h6 class="mb-1">Today's Transactions</h6>
                                        <h4 class="mb-0">{{ 156 }}</h4>
                                        <small>৳{{ number_format(75000, 2) }} Volume</small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exchange-alt fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Frozen Wallets</h6>
                                        <h4 class="mb-0">{{ 8 }}</h4>
                                        <small>৳{{ number_format(25000, 2) }} Frozen</small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-lock fa-2x"></i>
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
                                        <h6 class="mb-1">Negative Balances</h6>
                                        <h4 class="mb-0">{{ 3 }}</h4>
                                        <small>-৳{{ number_format(5000, 2) }} Total</small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle fa-2x"></i>
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
                                            <!-- User Status -->
                                            <div class="col-md-2">
                                                <label class="form-label">User Status</label>
                                                <select name="user_status" class="form-select">
                                                    <option value="">All Users</option>
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                    <option value="suspended">Suspended</option>
                                                    <option value="verified">Verified</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Wallet Status -->
                                            <div class="col-md-2">
                                                <label class="form-label">Wallet Status</label>
                                                <select name="wallet_status" class="form-select">
                                                    <option value="">All Wallets</option>
                                                    <option value="active">Active</option>
                                                    <option value="frozen">Frozen</option>
                                                    <option value="suspended">Suspended</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Balance Range -->
                                            <div class="col-md-2">
                                                <label class="form-label">Balance Range</label>
                                                <select name="balance_range" class="form-select">
                                                    <option value="">All Balances</option>
                                                    <option value="negative">Negative Balance</option>
                                                    <option value="0-1000">৳0 - ৳1,000</option>
                                                    <option value="1000-10000">৳1,000 - ৳10,000</option>
                                                    <option value="10000-50000">৳10,000 - ৳50,000</option>
                                                    <option value="50000+">৳50,000+</option>
                                                </select>
                                            </div>
                                            
                                            <!-- User Type -->
                                            <div class="col-md-2">
                                                <label class="form-label">User Type</label>
                                                <select name="user_type" class="form-select">
                                                    <option value="">All Types</option>
                                                    <option value="customer">Customer</option>
                                                    <option value="vendor">Vendor</option>
                                                    <option value="affiliate">Affiliate</option>
                                                    <option value="admin">Admin</option>
                                                </select>
                                            </div>
                                            
                                            <!-- KYC Status -->
                                            <div class="col-md-2">
                                                <label class="form-label">KYC Status</label>
                                                <select name="kyc_status" class="form-select">
                                                    <option value="">All KYC</option>
                                                    <option value="verified">Verified</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="rejected">Rejected</option>
                                                    <option value="not_submitted">Not Submitted</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Search -->
                                            <div class="col-md-1">
                                                <label class="form-label">Search</label>
                                                <input type="text" name="search" class="form-control" placeholder="User...">
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

                <!-- Wallets Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th width="5%">
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                </th>
                                <th width="15%">User</th>
                                <th width="10%">User Type</th>
                                <th width="12%">Balance</th>
                                <th width="10%">Last Transaction</th>
                                <th width="8%">Wallet Status</th>
                                <th width="8%">KYC Status</th>
                                <th width="12%">Total Transactions</th>
                                <th width="20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $sampleWallets = [
                                [
                                    'id' => 'WLT-001',
                                    'user_id' => 'USR-001',
                                    'user_name' => 'John Smith',
                                    'user_email' => 'john.smith@email.com',
                                    'user_type' => 'customer',
                                    'balance' => 25000.00,
                                    'wallet_status' => 'active',
                                    'kyc_status' => 'verified',
                                    'last_transaction' => '2025-07-30 10:30:00',
                                    'total_transactions' => 156,
                                    'total_volume' => 125000.00
                                ],
                                [
                                    'id' => 'WLT-002',
                                    'user_id' => 'USR-002',
                                    'user_name' => 'Sarah Johnson',
                                    'user_email' => 'sarah.j@email.com',
                                    'user_type' => 'vendor',
                                    'balance' => 85000.00,
                                    'wallet_status' => 'active',
                                    'kyc_status' => 'verified',
                                    'last_transaction' => '2025-07-30 14:15:00',
                                    'total_transactions' => 289,
                                    'total_volume' => 450000.00
                                ],
                                [
                                    'id' => 'WLT-003',
                                    'user_id' => 'USR-003',
                                    'user_name' => 'Mike Davis',
                                    'user_email' => 'mike.davis@email.com',
                                    'user_type' => 'customer',
                                    'balance' => -2500.00,
                                    'wallet_status' => 'frozen',
                                    'kyc_status' => 'pending',
                                    'last_transaction' => '2025-07-29 16:45:00',
                                    'total_transactions' => 45,
                                    'total_volume' => 25000.00
                                ],
                                [
                                    'id' => 'WLT-004',
                                    'user_id' => 'USR-004',
                                    'user_name' => 'Emily Wilson',
                                    'user_email' => 'emily.wilson@email.com',
                                    'user_type' => 'affiliate',
                                    'balance' => 15000.00,
                                    'wallet_status' => 'active',
                                    'kyc_status' => 'verified',
                                    'last_transaction' => '2025-07-29 11:20:00',
                                    'total_transactions' => 78,
                                    'total_volume' => 65000.00
                                ],
                                [
                                    'id' => 'WLT-005',
                                    'user_id' => 'USR-005',
                                    'user_name' => 'David Brown',
                                    'user_email' => 'david.brown@email.com',
                                    'user_type' => 'customer',
                                    'balance' => 3500.00,
                                    'wallet_status' => 'suspended',
                                    'kyc_status' => 'rejected',
                                    'last_transaction' => '2025-07-28 09:15:00',
                                    'total_transactions' => 23,
                                    'total_volume' => 12000.00
                                ]
                            ];
                            @endphp
                            
                            @foreach($sampleWallets as $wallet)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input wallet-checkbox" value="{{ $wallet['id'] }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                            @if($wallet['user_type'] == 'vendor')
                                                <i class="fas fa-store text-primary"></i>
                                            @elseif($wallet['user_type'] == 'affiliate')
                                                <i class="fas fa-handshake text-success"></i>
                                            @elseif($wallet['user_type'] == 'admin')
                                                <i class="fas fa-user-shield text-danger"></i>
                                            @else
                                                <i class="fas fa-user text-info"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="fw-semibold">{{ $wallet['user_name'] }}</span><br>
                                            <small class="text-muted">{{ $wallet['user_email'] }}</small><br>
                                            <small class="text-muted">{{ $wallet['user_id'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($wallet['user_type'] == 'vendor')
                                        <span class="badge bg-primary-transparent text-primary">
                                            <i class="fas fa-store me-1"></i>Vendor
                                        </span>
                                    @elseif($wallet['user_type'] == 'affiliate')
                                        <span class="badge bg-success-transparent text-success">
                                            <i class="fas fa-handshake me-1"></i>Affiliate
                                        </span>
                                    @elseif($wallet['user_type'] == 'admin')
                                        <span class="badge bg-danger-transparent text-danger">
                                            <i class="fas fa-user-shield me-1"></i>Admin
                                        </span>
                                    @else
                                        <span class="badge bg-info-transparent text-info">
                                            <i class="fas fa-user me-1"></i>Customer
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold fs-6 @if($wallet['balance'] < 0) text-danger @else text-success @endif">
                                        ৳{{ number_format($wallet['balance'], 2) }}
                                    </span><br>
                                    <small class="text-muted">Vol: ৳{{ number_format($wallet['total_volume'], 0) }}</small>
                                </td>
                                <td>
                                    <small class="text-muted">{{ date('M d, Y', strtotime($wallet['last_transaction'])) }}</small><br>
                                    <small class="text-muted">{{ date('h:i A', strtotime($wallet['last_transaction'])) }}</small>
                                </td>
                                <td>
                                    @if($wallet['wallet_status'] == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($wallet['wallet_status'] == 'frozen')
                                        <span class="badge bg-warning">Frozen</span>
                                    @elseif($wallet['wallet_status'] == 'suspended')
                                        <span class="badge bg-danger">Suspended</span>
                                    @endif
                                </td>
                                <td>
                                    @if($wallet['kyc_status'] == 'verified')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Verified
                                        </span>
                                    @elseif($wallet['kyc_status'] == 'pending')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>Pending
                                        </span>
                                    @elseif($wallet['kyc_status'] == 'rejected')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times me-1"></i>Rejected
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-minus me-1"></i>Not Submitted
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $wallet['total_transactions'] }}</span><br>
                                    <small class="text-muted">transactions</small>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <button class="btn btn-primary btn-sm" onclick="adjustBalance('{{ $wallet['id'] }}')" title="Adjust Balance">
                                            <i class="fas fa-plus-minus"></i>
                                        </button>
                                        <button class="btn btn-info btn-sm" onclick="viewWallet('{{ $wallet['id'] }}')" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($wallet['wallet_status'] == 'active')
                                            <button class="btn btn-warning btn-sm" onclick="freezeWallet('{{ $wallet['id'] }}')" title="Freeze Wallet">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        @elseif($wallet['wallet_status'] == 'frozen')
                                            <button class="btn btn-success btn-sm" onclick="unfreezeWallet('{{ $wallet['id'] }}')" title="Unfreeze Wallet">
                                                <i class="fas fa-unlock"></i>
                                            </button>
                                        @endif
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-history me-2"></i>Transaction History</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>View User Profile</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-envelope me-2"></i>Send Email</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-ban me-2"></i>Suspend User</a></li>
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
                        <span class="text-muted">Showing 1 to 5 of 1,250 results</span>
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

<!-- Adjust Balance Modal -->
<div class="modal fade" id="adjustBalanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adjust Wallet Balance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="adjustBalanceForm">
                    <div class="mb-3">
                        <label class="form-label">User Email/ID</label>
                        <input type="text" name="user_identifier" class="form-control" placeholder="Enter email or user ID" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adjustment Type</label>
                        <select name="adjustment_type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="add">Add Balance</option>
                            <option value="subtract">Subtract Balance</option>
                            <option value="set">Set Balance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (৳)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <select name="reason" class="form-select" required>
                            <option value="">Select Reason</option>
                            <option value="admin_adjustment">Admin Adjustment</option>
                            <option value="bonus_credit">Bonus Credit</option>
                            <option value="refund_credit">Refund Credit</option>
                            <option value="error_correction">Error Correction</option>
                            <option value="promotional_credit">Promotional Credit</option>
                            <option value="penalty_deduction">Penalty Deduction</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveAdjustment">Save Adjustment</button>
            </div>
        </div>
    </div>
</div>

<!-- Wallet Details Modal -->
<div class="modal fade" id="walletDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Wallet Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="walletDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="adjustBalanceBtn">Adjust Balance</button>
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
    const walletCheckboxes = document.querySelectorAll('.wallet-checkbox');
    const bulkUpdateBtn = document.getElementById('bulkUpdateBtn');
    
    selectAllCheckbox.addEventListener('change', function() {
        walletCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkButtons();
    });
    
    walletCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkButtons);
    });
    
    function updateBulkButtons() {
        const checkedBoxes = document.querySelectorAll('.wallet-checkbox:checked');
        bulkUpdateBtn.disabled = checkedBoxes.length === 0;
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
    
    // Save Adjustment
    document.getElementById('saveAdjustment').addEventListener('click', function() {
        const form = document.getElementById('adjustBalanceForm');
        const formData = new FormData(form);
        
        // Add AJAX submission logic here
        alert('Balance adjustment functionality would be implemented here');
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('adjustBalanceModal'));
        modal.hide();
        
        // Reset form
        form.reset();
    });
    
    // Bulk Update
    document.getElementById('bulkUpdateBtn').addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.wallet-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        
        if (confirm(`Are you sure you want to perform bulk update on ${ids.length} wallets?`)) {
            alert('Bulk update functionality would be implemented here');
        }
    });
});

// Individual action functions
function adjustBalance(walletId) {
    // Pre-fill the modal with wallet ID and show
    const modal = new bootstrap.Modal(document.getElementById('adjustBalanceModal'));
    modal.show();
}

function viewWallet(walletId) {
    // Load wallet details and show modal
    const modalContent = document.getElementById('walletDetailsContent');
    modalContent.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h6>Wallet Information</h6>
                <p><strong>Wallet ID:</strong> ${walletId}</p>
                <p><strong>Current Balance:</strong> ৳25,000.00</p>
                <p><strong>Status:</strong> <span class="badge bg-success">Active</span></p>
                <p><strong>Created:</strong> January 15, 2025</p>
            </div>
            <div class="col-md-6">
                <h6>User Information</h6>
                <p><strong>Name:</strong> John Smith</p>
                <p><strong>Email:</strong> john.smith@email.com</p>
                <p><strong>Type:</strong> Customer</p>
                <p><strong>KYC:</strong> <span class="badge bg-success">Verified</span></p>
            </div>
            <div class="col-12 mt-3">
                <h6>Transaction Statistics</h6>
                <div class="row">
                    <div class="col-md-3">
                        <p><strong>Total Transactions:</strong> 156</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Total Volume:</strong> ৳125,000</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Last Transaction:</strong> Today</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Average Transaction:</strong> ৳801</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('walletDetailsModal'));
    modal.show();
}

function freezeWallet(walletId) {
    if (confirm('Are you sure you want to freeze this wallet? User will not be able to make transactions.')) {
        alert(`Freeze wallet functionality for ${walletId} would be implemented here`);
    }
}

function unfreezeWallet(walletId) {
    if (confirm('Are you sure you want to unfreeze this wallet?')) {
        alert(`Unfreeze wallet functionality for ${walletId} would be implemented here`);
    }
}
</script>
@endpush
