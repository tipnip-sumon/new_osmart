@extends('admin.layouts.app')
@section('top_title','Admin Refunds')
@section('title','Refund Management')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="row my-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-undo-alt me-2"></i>
                    Refund Requests Management
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addRefundModal">
                        <i class="fas fa-plus me-1"></i>
                        Manual Refund
                    </button>
                    <button class="btn btn-light btn-sm" id="bulkProcessBtn" disabled>
                        <i class="fas fa-check-double me-1"></i>
                        Bulk Process
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
                                        <h6 class="mb-1">Pending Refunds</h6>
                                        <h4 class="mb-0">{{ 18 }}</h4>
                                        <small>৳{{ number_format(85000, 2) }}</small>
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
                                        <h6 class="mb-1">Processed Today</h6>
                                        <h4 class="mb-0">{{ 7 }}</h4>
                                        <small>৳{{ number_format(32000, 2) }}</small>
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
                                        <h4 class="mb-0">{{ 2 }}</h4>
                                        <small>৳{{ number_format(8500, 2) }}</small>
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
                                        <h4 class="mb-0">{{ 95 }}</h4>
                                        <small>৳{{ number_format(425000, 2) }}</small>
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
                                                    <option value="processed">Processed</option>
                                                    <option value="rejected">Rejected</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Refund Type -->
                                            <div class="col-md-2">
                                                <label class="form-label">Type</label>
                                                <select name="type" class="form-select">
                                                    <option value="">All Types</option>
                                                    <option value="order_cancellation">Order Cancellation</option>
                                                    <option value="product_return">Product Return</option>
                                                    <option value="damaged_product">Damaged Product</option>
                                                    <option value="wrong_product">Wrong Product</option>
                                                    <option value="manual">Manual Refund</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Payment Method -->
                                            <div class="col-md-2">
                                                <label class="form-label">Payment Method</label>
                                                <select name="payment_method" class="form-select">
                                                    <option value="">All Methods</option>
                                                    <option value="credit_card">Credit Card</option>
                                                    <option value="bank_transfer">Bank Transfer</option>
                                                    <option value="bkash">bKash</option>
                                                    <option value="nagad">Nagad</option>
                                                    <option value="rocket">Rocket</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Amount Range -->
                                            <div class="col-md-2">
                                                <label class="form-label">Amount Range</label>
                                                <select name="amount_range" class="form-select">
                                                    <option value="">All Amounts</option>
                                                    <option value="0-1000">৳0 - ৳1,000</option>
                                                    <option value="1000-5000">৳1,000 - ৳5,000</option>
                                                    <option value="5000-20000">৳5,000 - ৳20,000</option>
                                                    <option value="20000+">৳20,000+</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Search -->
                                            <div class="col-md-1">
                                                <label class="form-label">Search</label>
                                                <input type="text" name="search" class="form-control" placeholder="Search...">
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

                <!-- Refunds Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th width="5%">
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                </th>
                                <th width="10%">Date</th>
                                <th width="12%">Order/Reference</th>
                                <th width="15%">Customer</th>
                                <th width="12%">Amount</th>
                                <th width="12%">Type</th>
                                <th width="10%">Payment Method</th>
                                <th width="10%">Status</th>
                                <th width="14%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $sampleRefunds = [
                                [
                                    'id' => 'REF-2025-001',
                                    'order_id' => 'ORD-2025-001',
                                    'customer_name' => 'John Smith',
                                    'customer_email' => 'john.smith@email.com',
                                    'amount' => 15000.00,
                                    'type' => 'product_return',
                                    'payment_method' => 'credit_card',
                                    'status' => 'pending',
                                    'reason' => 'Product not as described',
                                    'requested_at' => '2025-07-30 10:30:00',
                                    'processed_at' => null
                                ],
                                [
                                    'id' => 'REF-2025-002',
                                    'order_id' => 'ORD-2025-002',
                                    'customer_name' => 'Sarah Johnson',
                                    'customer_email' => 'sarah.j@email.com',
                                    'amount' => 8500.00,
                                    'type' => 'damaged_product',
                                    'payment_method' => 'bkash',
                                    'status' => 'approved',
                                    'reason' => 'Product arrived damaged',
                                    'requested_at' => '2025-07-30 14:15:00',
                                    'processed_at' => null
                                ],
                                [
                                    'id' => 'REF-2025-003',
                                    'order_id' => 'ORD-2025-003',
                                    'customer_name' => 'Mike Davis',
                                    'customer_email' => 'mike.davis@email.com',
                                    'amount' => 3200.00,
                                    'type' => 'order_cancellation',
                                    'payment_method' => 'nagad',
                                    'status' => 'processed',
                                    'reason' => 'Customer cancelled order',
                                    'requested_at' => '2025-07-29 16:45:00',
                                    'processed_at' => '2025-07-30 09:30:00'
                                ],
                                [
                                    'id' => 'REF-2025-004',
                                    'order_id' => 'ORD-2025-004',
                                    'customer_name' => 'Emily Wilson',
                                    'customer_email' => 'emily.wilson@email.com',
                                    'amount' => 25000.00,
                                    'type' => 'wrong_product',
                                    'payment_method' => 'bank_transfer',
                                    'status' => 'rejected',
                                    'reason' => 'Wrong product received',
                                    'requested_at' => '2025-07-29 11:20:00',
                                    'processed_at' => '2025-07-30 08:15:00'
                                ],
                                [
                                    'id' => 'REF-2025-005',
                                    'order_id' => 'MAN-2025-001',
                                    'customer_name' => 'David Brown',
                                    'customer_email' => 'david.brown@email.com',
                                    'amount' => 5500.00,
                                    'type' => 'manual',
                                    'payment_method' => 'rocket',
                                    'status' => 'pending',
                                    'reason' => 'Customer service adjustment',
                                    'requested_at' => '2025-07-28 09:15:00',
                                    'processed_at' => null
                                ]
                            ];
                            @endphp
                            
                            @foreach($sampleRefunds as $refund)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input refund-checkbox" value="{{ $refund['id'] }}">
                                </td>
                                <td>
                                    <small class="text-muted">{{ date('M d, Y', strtotime($refund['requested_at'])) }}</small><br>
                                    <small class="text-muted">{{ date('h:i A', strtotime($refund['requested_at'])) }}</small>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $refund['order_id'] }}</span><br>
                                    <small class="text-muted">{{ $refund['id'] }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <span class="fw-semibold">{{ $refund['customer_name'] }}</span><br>
                                            <small class="text-muted">{{ $refund['customer_email'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-danger fs-6">-৳{{ number_format($refund['amount'], 2) }}</span>
                                </td>
                                <td>
                                    @if($refund['type'] == 'product_return')
                                        <span class="badge bg-info-transparent text-info">
                                            <i class="fas fa-undo me-1"></i>Return
                                        </span>
                                    @elseif($refund['type'] == 'order_cancellation')
                                        <span class="badge bg-warning-transparent text-warning">
                                            <i class="fas fa-times me-1"></i>Cancellation
                                        </span>
                                    @elseif($refund['type'] == 'damaged_product')
                                        <span class="badge bg-danger-transparent text-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Damaged
                                        </span>
                                    @elseif($refund['type'] == 'wrong_product')
                                        <span class="badge bg-secondary-transparent text-secondary">
                                            <i class="fas fa-exchange-alt me-1"></i>Wrong Item
                                        </span>
                                    @else
                                        <span class="badge bg-primary-transparent text-primary">
                                            <i class="fas fa-cog me-1"></i>Manual
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($refund['payment_method'] == 'credit_card')
                                        <span class="badge bg-primary-transparent text-primary">
                                            <i class="fas fa-credit-card me-1"></i>Card
                                        </span>
                                    @elseif($refund['payment_method'] == 'bank_transfer')
                                        <span class="badge bg-info-transparent text-info">
                                            <i class="fas fa-university me-1"></i>Bank
                                        </span>
                                    @elseif($refund['payment_method'] == 'bkash')
                                        <span class="badge bg-danger-transparent text-danger">
                                            <i class="fas fa-mobile-alt me-1"></i>bKash
                                        </span>
                                    @elseif($refund['payment_method'] == 'nagad')
                                        <span class="badge bg-warning-transparent text-warning">
                                            <i class="fas fa-mobile-alt me-1"></i>Nagad
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($refund['payment_method']) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($refund['status'] == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($refund['status'] == 'approved')
                                        <span class="badge bg-info">Approved</span>
                                    @elseif($refund['status'] == 'processed')
                                        <span class="badge bg-success">Processed</span>
                                    @elseif($refund['status'] == 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        @if($refund['status'] == 'pending')
                                            <button class="btn btn-success btn-sm" onclick="approveRefund('{{ $refund['id'] }}')" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="rejectRefund('{{ $refund['id'] }}')" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @elseif($refund['status'] == 'approved')
                                            <button class="btn btn-primary btn-sm" onclick="processRefund('{{ $refund['id'] }}')" title="Process Refund">
                                                <i class="fas fa-money-check-alt"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-info btn-sm" onclick="viewRefund('{{ $refund['id'] }}')" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-receipt me-2"></i>View Order</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>View Customer</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-envelope me-2"></i>Send Email</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>Delete</a></li>
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
                        <span class="text-muted">Showing 1 to 5 of 95 results</span>
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

<!-- Add Manual Refund Modal -->
<div class="modal fade" id="addRefundModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Manual Refund</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addRefundForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer Email</label>
                            <input type="email" name="customer_email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Order/Reference ID</label>
                            <input type="text" name="reference_id" class="form-control" placeholder="ORD-2025-001 or Manual">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Refund Amount (৳)</label>
                            <input type="number" name="amount" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Refund Type</label>
                            <select name="type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="order_cancellation">Order Cancellation</option>
                                <option value="product_return">Product Return</option>
                                <option value="damaged_product">Damaged Product</option>
                                <option value="wrong_product">Wrong Product</option>
                                <option value="manual">Manual Adjustment</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">Select Method</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="bkash">bKash</option>
                                <option value="nagad">Nagad</option>
                                <option value="rocket">Rocket</option>
                                <option value="upay">Upay</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-select">
                                <option value="normal">Normal</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Reason for Refund</label>
                            <textarea name="reason" class="form-control" rows="3" required placeholder="Explain the reason for this refund..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Internal Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Internal notes (optional)..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveRefund">Create Refund</button>
            </div>
        </div>
    </div>
</div>

<!-- Refund Details Modal -->
<div class="modal fade" id="refundDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Refund Request Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="refundDetailsContent">
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
                <h5 class="modal-title">Reject Refund Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="rejectionForm">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason</label>
                        <select name="reason" class="form-select" required>
                            <option value="">Select Reason</option>
                            <option value="insufficient_evidence">Insufficient Evidence</option>
                            <option value="policy_violation">Policy Violation</option>
                            <option value="duplicate_request">Duplicate Request</option>
                            <option value="beyond_return_window">Beyond Return Window</option>
                            <option value="ineligible_product">Ineligible Product</option>
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
    const refundCheckboxes = document.querySelectorAll('.refund-checkbox');
    const bulkProcessBtn = document.getElementById('bulkProcessBtn');
    
    selectAllCheckbox.addEventListener('change', function() {
        refundCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkButtons();
    });
    
    refundCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkButtons);
    });
    
    function updateBulkButtons() {
        const checkedBoxes = document.querySelectorAll('.refund-checkbox:checked');
        bulkProcessBtn.disabled = checkedBoxes.length === 0;
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
    
    // Save Refund
    document.getElementById('saveRefund').addEventListener('click', function() {
        const form = document.getElementById('addRefundForm');
        const formData = new FormData(form);
        
        // Add AJAX submission logic here
        alert('Manual refund creation functionality would be implemented here');
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('addRefundModal'));
        modal.hide();
        
        // Reset form
        form.reset();
    });
    
    // Bulk Process
    document.getElementById('bulkProcessBtn').addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.refund-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        
        if (confirm(`Are you sure you want to process ${ids.length} refund requests?`)) {
            alert('Bulk processing functionality would be implemented here');
        }
    });
});

// Individual action functions
function approveRefund(id) {
    if (confirm('Are you sure you want to approve this refund request?')) {
        alert(`Approval functionality for ${id} would be implemented here`);
    }
}

function rejectRefund(id) {
    // Show rejection modal
    const modal = new bootstrap.Modal(document.getElementById('rejectionModal'));
    modal.show();
    
    // Store the refund ID for later use
    document.getElementById('confirmRejectBtn').onclick = function() {
        const form = document.getElementById('rejectionForm');
        const formData = new FormData(form);
        
        alert(`Rejection functionality for ${id} would be implemented here`);
        modal.hide();
        form.reset();
    };
}

function processRefund(id) {
    if (confirm('Process this refund? This will initiate the actual refund payment.')) {
        alert(`Process functionality for ${id} would be implemented here`);
    }
}

function viewRefund(id) {
    // Load refund details and show modal
    const modalContent = document.getElementById('refundDetailsContent');
    modalContent.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h6>Refund Information</h6>
                <p><strong>Refund ID:</strong> ${id}</p>
                <p><strong>Amount:</strong> ৳15,000.00</p>
                <p><strong>Type:</strong> Product Return</p>
                <p><strong>Status:</strong> <span class="badge bg-warning">Pending</span></p>
                <p><strong>Payment Method:</strong> Credit Card</p>
            </div>
            <div class="col-md-6">
                <h6>Customer Information</h6>
                <p><strong>Name:</strong> John Smith</p>
                <p><strong>Email:</strong> john.smith@email.com</p>
                <p><strong>Order ID:</strong> ORD-2025-001</p>
                <p><strong>Order Date:</strong> July 25, 2025</p>
            </div>
            <div class="col-12 mt-3">
                <h6>Refund Reason</h6>
                <p>Product not as described. Customer reported that the item received did not match the product description on the website.</p>
                
                <h6>Internal Notes</h6>
                <p class="text-muted">Customer service confirmed the discrepancy. Refund approved by policy.</p>
            </div>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('refundDetailsModal'));
    modal.show();
}
</script>
@endpush
