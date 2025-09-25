@extends('member.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-gradient-primary text-white">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="fas fa-history me-3 fs-4"></i>
                    <div>
                        <h4 class="mb-0">Fund History</h4>
                        <p class="mb-0 opacity-75">Track all your fund addition transactions</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('member.add-fund') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus-circle me-1"></i> Add Fund
                    </a>
                    <a href="{{ route('member.wallet') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-wallet me-1"></i> My Wallet
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="icon-box bg-success bg-opacity-10 text-success mx-auto mb-3">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4 class="fw-bold text-success">৳{{ number_format($summary['total_added'], 2) }}</h4>
                    <p class="text-muted mb-0">Total Added</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning mx-auto mb-3">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h4 class="fw-bold text-warning">৳{{ number_format($summary['pending_amount'], 2) }}</h4>
                    <p class="text-muted mb-0">Pending Amount</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="icon-box bg-info bg-opacity-10 text-info mx-auto mb-3">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <h4 class="fw-bold text-info">{{ $summary['total_transactions'] }}</h4>
                    <p class="text-muted mb-0">Total Transactions</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary mx-auto mb-3">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <h4 class="fw-bold text-primary">৳{{ number_format($summary['total_fees'], 2) }}</h4>
                    <p class="text-muted mb-0">Total Fees Paid</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('member.fund-history') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-select">
                        <option value="">All Methods</option>
                        <option value="bkash" {{ request('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                        <option value="nagad" {{ request('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                        <option value="rocket" {{ request('payment_method') == 'rocket' ? 'selected' : '' }}>Rocket</option>
                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="upay" {{ request('payment_method') == 'upay' ? 'selected' : '' }}>Upay</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>

                <div class="col-md-2">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('member.fund-history') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list text-primary me-2"></i>
                    Transaction History
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-success btn-sm" onclick="exportTransactions('excel')">
                        <i class="fas fa-file-excel me-1"></i> Export Excel
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="exportTransactions('pdf')">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($transactions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Date & Time</th>
                                <th>Payment Method</th>
                                <th>Amount</th>
                                <th>Fee</th>
                                <th>Net Amount</th>
                                <th>Transaction ID</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-primary">#{{ $transaction->id }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $transaction->created_at->format('d M Y') }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $transaction->created_at->format('h:i A') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @switch($transaction->payment_method)
                                                @case('bkash')
                                                    <div class="payment-icon bg-pink text-white me-2">
                                                        <i class="fas fa-mobile-alt"></i>
                                                    </div>
                                                    <span>bKash</span>
                                                    @break
                                                @case('nagad')
                                                    <div class="payment-icon bg-orange text-white me-2">
                                                        <i class="fas fa-mobile-alt"></i>
                                                    </div>
                                                    <span>Nagad</span>
                                                    @break
                                                @case('rocket')
                                                    <div class="payment-icon bg-purple text-white me-2">
                                                        <i class="fas fa-rocket"></i>
                                                    </div>
                                                    <span>Rocket</span>
                                                    @break
                                                @case('bank_transfer')
                                                    <div class="payment-icon bg-dark text-white me-2">
                                                        <i class="fas fa-university"></i>
                                                    </div>
                                                    <span>Bank Transfer</span>
                                                    @break
                                                @case('upay')
                                                    <div class="payment-icon bg-teal text-white me-2">
                                                        <i class="fas fa-mobile-alt"></i>
                                                    </div>
                                                    <span>Upay</span>
                                                    @break
                                            @endswitch
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold">৳{{ number_format($transaction->amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-warning">৳{{ number_format($transaction->fee, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">৳{{ number_format($transaction->net_amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <code class="bg-light px-2 py-1 rounded">{{ $transaction->transaction_id }}</code>
                                        @if($transaction->sender_number)
                                            <br><small class="text-muted">From: {{ $transaction->sender_number }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($transaction->status)
                                            @case('pending')
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i>Pending
                                                </span>
                                                @break
                                            @case('approved')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Approved
                                                </span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times-circle me-1"></i>Rejected
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                                    onclick="viewTransaction({{ $transaction->id }})" 
                                                    data-bs-toggle="modal" data-bs-target="#transactionModal">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($transaction->receipt_path)
                                                <a href="{{ Storage::url($transaction->receipt_path) }}" 
                                                   target="_blank" class="btn btn-outline-info btn-sm">
                                                    <i class="fas fa-image"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} 
                                of {{ $transactions->total() }} results
                            </small>
                        </div>
                        <div>
                            {{ $transactions->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="icon-box bg-light text-muted mx-auto mb-3">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h5 class="text-muted">No transactions found</h5>
                    <p class="text-muted">You haven't made any fund addition transactions yet.</p>
                    <a href="{{ route('member.add-fund') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Add Your First Fund
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Transaction Details Modal -->
<div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionModalLabel">
                    <i class="fas fa-receipt me-2"></i>Transaction Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="transactionDetails">
                <!-- Transaction details will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewTransaction(transactionId) {
    // Show loading state
    document.getElementById('transactionDetails').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading transaction details...</p>
        </div>
    `;
    
    // Fetch transaction details (you'll need to implement this endpoint)
    fetch(`/member/fund-history/${transactionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayTransactionDetails(data.transaction);
            } else {
                throw new Error(data.message || 'Failed to load transaction details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('transactionDetails').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Failed to load transaction details. Please try again.
                </div>
            `;
        });
}

function displayTransactionDetails(transaction) {
    const statusBadge = getStatusBadge(transaction.status);
    const paymentMethodInfo = getPaymentMethodInfo(transaction.payment_method);
    
    const html = `
        <div class="row">
            <div class="col-md-6">
                <div class="card border-0 bg-light h-100">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-info-circle me-2 text-primary"></i>Basic Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr><td><strong>Transaction ID:</strong></td><td>${transaction.id}</td></tr>
                            <tr><td><strong>Date & Time:</strong></td><td>${new Date(transaction.created_at).toLocaleString()}</td></tr>
                            <tr><td><strong>Status:</strong></td><td>${statusBadge}</td></tr>
                            <tr><td><strong>Payment Method:</strong></td><td>${paymentMethodInfo}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 bg-light h-100">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-calculator me-2 text-success"></i>Amount Details</h6>
                        <table class="table table-sm table-borderless">
                            <tr><td><strong>Request Amount:</strong></td><td>৳${parseFloat(transaction.amount).toLocaleString('en-BD', {minimumFractionDigits: 2})}</td></tr>
                            <tr><td><strong>Processing Fee:</strong></td><td>৳${parseFloat(transaction.fee).toLocaleString('en-BD', {minimumFractionDigits: 2})}</td></tr>
                            <tr><td><strong>Net Amount:</strong></td><td class="text-success fw-bold">৳${parseFloat(transaction.net_amount).toLocaleString('en-BD', {minimumFractionDigits: 2})}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-mobile-alt me-2 text-info"></i>Payment Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Transaction Reference:</strong><br>
                                <code class="bg-white px-2 py-1 rounded">${transaction.transaction_id}</code></p>
                            </div>
                            ${transaction.sender_number ? `
                                <div class="col-md-6">
                                    <p><strong>Sender Number:</strong><br>
                                    <span class="text-muted">${transaction.sender_number}</span></p>
                                </div>
                            ` : ''}
                        </div>
                        
                        ${transaction.receipt_path ? `
                            <div class="mt-3">
                                <p><strong>Payment Receipt:</strong></p>
                                <a href="${transaction.receipt_url}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-image me-1"></i> View Receipt
                                </a>
                            </div>
                        ` : ''}
                        
                        ${transaction.admin_note ? `
                            <div class="mt-3">
                                <p><strong>Admin Note:</strong></p>
                                <div class="alert alert-info">
                                    <i class="fas fa-sticky-note me-2"></i>
                                    ${transaction.admin_note}
                                </div>
                            </div>
                        ` : ''}
                        
                        ${transaction.processed_at ? `
                            <div class="mt-3">
                                <p><strong>Processed At:</strong><br>
                                <span class="text-muted">${new Date(transaction.processed_at).toLocaleString()}</span></p>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('transactionDetails').innerHTML = html;
}

function getStatusBadge(status) {
    switch(status) {
        case 'pending':
            return '<span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Pending</span>';
        case 'approved':
            return '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Approved</span>';
        case 'rejected':
            return '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Rejected</span>';
        default:
            return '<span class="badge bg-secondary">Unknown</span>';
    }
}

function getPaymentMethodInfo(method) {
    const methods = {
        'bkash': '<i class="fas fa-mobile-alt text-pink me-1"></i>bKash',
        'nagad': '<i class="fas fa-mobile-alt text-orange me-1"></i>Nagad',
        'rocket': '<i class="fas fa-rocket text-purple me-1"></i>Rocket',
        'bank_transfer': '<i class="fas fa-university text-dark me-1"></i>Bank Transfer',
        'upay': '<i class="fas fa-mobile-alt text-teal me-1"></i>Upay'
    };
    return methods[method] || method;
}

function exportTransactions(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('export', format);
    
    window.open(`{{ route('member.fund-history') }}?${params.toString()}`, '_blank');
}

// Auto-refresh pending transactions
setInterval(function() {
    const hasPendingTransactions = document.querySelector('.badge.bg-warning');
    if (hasPendingTransactions) {
        // Only refresh if there are pending transactions
        location.reload();
    }
}, 30000); // Refresh every 30 seconds
</script>
@endpush

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.icon-box {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.payment-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.bg-pink { background-color: #e91e63; }
.bg-orange { background-color: #ff9800; }
.bg-purple { background-color: #9c27b0; }
.bg-teal { background-color: #009688; }

.text-pink { color: #e91e63; }
.text-orange { color: #ff9800; }
.text-purple { color: #9c27b0; }
.text-teal { color: #009688; }

.table th {
    font-weight: 600;
    font-size: 0.9rem;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
    font-size: 0.9rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.04);
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
}

.modal-lg {
    max-width: 900px;
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

code {
    font-size: 0.85rem;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .btn-group .btn {
        padding: 0.2rem 0.4rem;
        font-size: 0.8rem;
    }
    
    .icon-box {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
}

.pagination {
    margin-bottom: 0;
}

.pagination .page-link {
    border-color: #dee2e6;
    color: #007bff;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}
</style>
@endpush
@endsection
