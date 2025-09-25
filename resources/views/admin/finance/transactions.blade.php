@extends('admin.layouts.app')
@section('top_title','Admin Transactions')
@section('title','Transactions')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="row my-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-credit-card me-2"></i>
                    Financial Transactions
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                        <i class="fas fa-plus me-1"></i>
                        Add Transaction
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
                                        <h6 class="mb-1">Total Income</h6>
                                        <h4 class="mb-0">৳{{ number_format(125000, 2) }}</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-arrow-up fa-2x"></i>
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
                                        <h6 class="mb-1">Total Expense</h6>
                                        <h4 class="mb-0">৳{{ number_format(75000, 2) }}</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-arrow-down fa-2x"></i>
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
                                        <h6 class="mb-1">Net Profit</h6>
                                        <h4 class="mb-0">৳{{ number_format(50000, 2) }}</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-chart-line fa-2x"></i>
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
                                        <h6 class="mb-1">Pending</h6>
                                        <h4 class="mb-0">৳{{ number_format(15000, 2) }}</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-clock fa-2x"></i>
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
                                            <div class="col-md-3">
                                                <label class="form-label">Quick Filter</label>
                                                <select name="filter" class="form-select" id="quickFilter">
                                                    <option value="">All Time</option>
                                                    <option value="today">Today</option>
                                                    <option value="weekly">This Week</option>
                                                    <option value="monthly">This Month</option>
                                                    <option value="yearly">This Year</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Transaction Type -->
                                            <div class="col-md-3">
                                                <label class="form-label">Transaction Type</label>
                                                <select name="type" class="form-select">
                                                    <option value="">All Types</option>
                                                    <option value="income">Income</option>
                                                    <option value="expense">Expense</option>
                                                    <option value="transfer">Transfer</option>
                                                    <option value="refund">Refund</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Status -->
                                            <div class="col-md-3">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="">All Status</option>
                                                    <option value="completed">Completed</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="failed">Failed</option>
                                                    <option value="cancelled">Cancelled</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Amount Range -->
                                            <div class="col-md-3">
                                                <label class="form-label">Amount Range</label>
                                                <select name="amount_range" class="form-select">
                                                    <option value="">All Amounts</option>
                                                    <option value="0-1000">৳0 - ৳1,000</option>
                                                    <option value="1000-5000">৳1,000 - ৳5,000</option>
                                                    <option value="5000-10000">৳5,000 - ৳10,000</option>
                                                    <option value="10000+">৳10,000+</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Date Range -->
                                            <div class="col-md-3">
                                                <label class="form-label">From Date</label>
                                                <input type="date" name="from_date" class="form-control">
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <label class="form-label">To Date</label>
                                                <input type="date" name="to_date" class="form-control">
                                            </div>
                                            
                                            <!-- Search -->
                                            <div class="col-md-4">
                                                <label class="form-label">Search</label>
                                                <input type="text" name="search" class="form-control" placeholder="Search by reference, description...">
                                            </div>
                                            
                                            <!-- Filter Actions -->
                                            <div class="col-md-2">
                                                <label class="form-label">&nbsp;</label>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-secondary" id="resetFilters">
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

                <!-- Transactions Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th width="5%">
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                </th>
                                <th width="12%">Date</th>
                                <th width="15%">Reference</th>
                                <th width="20%">Description</th>
                                <th width="10%">Type</th>
                                <th width="12%">Amount</th>
                                <th width="10%">Status</th>
                                <th width="8%">Method</th>
                                <th width="8%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $sampleTransactions = [
                                [
                                    'id' => 'TXN-2025-001',
                                    'date' => '2025-07-30 10:30:00',
                                    'reference' => 'ORD-2025-001',
                                    'description' => 'Order Payment - Premium Health Supplement',
                                    'type' => 'income',
                                    'amount' => 2500.00,
                                    'status' => 'completed',
                                    'method' => 'Credit Card'
                                ],
                                [
                                    'id' => 'TXN-2025-002',
                                    'date' => '2025-07-30 14:15:00',
                                    'reference' => 'REF-2025-001',
                                    'description' => 'Refund for Order ORD-2025-002',
                                    'type' => 'expense',
                                    'amount' => 1500.00,
                                    'status' => 'pending',
                                    'method' => 'Bank Transfer'
                                ],
                                [
                                    'id' => 'TXN-2025-003',
                                    'date' => '2025-07-29 16:45:00',
                                    'reference' => 'COM-2025-001',
                                    'description' => 'Commission Payment to Vendor',
                                    'type' => 'expense',
                                    'amount' => 500.00,
                                    'status' => 'completed',
                                    'method' => 'Digital Wallet'
                                ],
                                [
                                    'id' => 'TXN-2025-004',
                                    'date' => '2025-07-29 11:20:00',
                                    'reference' => 'ORD-2025-003',
                                    'description' => 'Order Payment - Skincare Bundle',
                                    'type' => 'income',
                                    'amount' => 3500.00,
                                    'status' => 'completed',
                                    'method' => 'bKash'
                                ],
                                [
                                    'id' => 'TXN-2025-005',
                                    'date' => '2025-07-28 09:15:00',
                                    'reference' => 'SUB-2025-001',
                                    'description' => 'Subscription Fee Payment',
                                    'type' => 'income',
                                    'amount' => 1200.00,
                                    'status' => 'failed',
                                    'method' => 'Nagad'
                                ]
                            ];
                            @endphp
                            
                            @foreach($sampleTransactions as $transaction)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input transaction-checkbox" value="{{ $transaction['id'] }}">
                                </td>
                                <td>
                                    <small class="text-muted">{{ date('M d, Y', strtotime($transaction['date'])) }}</small><br>
                                    <small class="text-muted">{{ date('h:i A', strtotime($transaction['date'])) }}</small>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $transaction['reference'] }}</span><br>
                                    <small class="text-muted">{{ $transaction['id'] }}</small>
                                </td>
                                <td>
                                    <span class="text-truncate d-block" style="max-width: 200px;" title="{{ $transaction['description'] }}">
                                        {{ $transaction['description'] }}
                                    </span>
                                </td>
                                <td>
                                    @if($transaction['type'] == 'income')
                                        <span class="badge bg-success-transparent text-success">
                                            <i class="fas fa-arrow-up me-1"></i>Income
                                        </span>
                                    @elseif($transaction['type'] == 'expense')
                                        <span class="badge bg-danger-transparent text-danger">
                                            <i class="fas fa-arrow-down me-1"></i>Expense
                                        </span>
                                    @else
                                        <span class="badge bg-info-transparent text-info">
                                            <i class="fas fa-exchange-alt me-1"></i>{{ ucfirst($transaction['type']) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-semibold @if($transaction['type'] == 'income') text-success @else text-danger @endif">
                                        @if($transaction['type'] == 'income')+@else-@endif৳{{ number_format($transaction['amount'], 2) }}
                                    </span>
                                </td>
                                <td>
                                    @if($transaction['status'] == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($transaction['status'] == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($transaction['status'] == 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($transaction['status']) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $transaction['method'] }}</small>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>View</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Receipt</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                        </ul>
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
                        <span class="text-muted">Showing 1 to 5 of 150 results</span>
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

<!-- Add Transaction Modal -->
<div class="modal fade" id="addTransactionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addTransactionForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Transaction Type</label>
                            <select name="type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="income">Income</option>
                                <option value="expense">Expense</option>
                                <option value="transfer">Transfer</option>
                                <option value="refund">Refund</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount (৳)</label>
                            <input type="number" name="amount" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Method</label>
                            <select name="method" class="form-select" required>
                                <option value="">Select Method</option>
                                <option value="cash">Cash</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="bkash">bKash</option>
                                <option value="nagad">Nagad</option>
                                <option value="rocket">Rocket</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Reference</label>
                            <input type="text" name="reference" class="form-control" placeholder="Reference number">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Transaction Date</label>
                            <input type="datetime-local" name="transaction_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveTransaction">Save Transaction</button>
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
    
    .bg-success-transparent {
        background-color: rgba(40, 167, 69, 0.1) !important;
    }
    
    .bg-danger-transparent {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }
    
    .bg-info-transparent {
        background-color: rgba(23, 162, 184, 0.1) !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All Checkbox
    const selectAllCheckbox = document.getElementById('selectAll');
    const transactionCheckboxes = document.querySelectorAll('.transaction-checkbox');
    
    selectAllCheckbox.addEventListener('change', function() {
        transactionCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    // Reset Filters
    document.getElementById('resetFilters').addEventListener('click', function() {
        document.getElementById('filterForm').reset();
        // Optionally submit the form to clear filters
        document.getElementById('filterForm').submit();
    });
    
    // Export functionality
    document.getElementById('exportBtn').addEventListener('click', function() {
        // Add export logic here
        alert('Export functionality would be implemented here');
    });
    
    // Save Transaction
    document.getElementById('saveTransaction').addEventListener('click', function() {
        const form = document.getElementById('addTransactionForm');
        const formData = new FormData(form);
        
        // Add AJAX submission logic here
        alert('Transaction save functionality would be implemented here');
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('addTransactionModal'));
        modal.hide();
        
        // Reset form
        form.reset();
    });
    
    // Quick Filter Auto Submit
    document.getElementById('quickFilter').addEventListener('change', function() {
        if (this.value) {
            document.getElementById('filterForm').submit();
        }
    });
});
</script>
@endpush
