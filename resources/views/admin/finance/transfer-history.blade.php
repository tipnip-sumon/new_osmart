@extends('admin.layouts.app')

@section('title', 'Admin Transfer History')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div>
                <h1 class="page-title fw-semibold fs-18 mb-0">Admin Transfer History</h1>
                <div class="">
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.finance.dashboard') }}">Finance</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Transfer History</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.finance.transfer') }}" class="btn btn-primary">
                    <i class="fe fe-plus me-1"></i>New Transfer
                </a>
                <button type="button" class="btn btn-success" onclick="exportTransfers()">
                    <i class="fe fe-download me-1"></i>Export CSV
                </button>
                <a href="{{ route('admin.finance.wallets') }}" class="btn btn-outline-secondary">
                    <i class="fe fe-credit-card me-1"></i>View Wallets
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card bg-primary-gradient">
                    <div class="card-body text-white">
                        <div class="counter-status">
                            <div class="counter-icon">
                                <i class="fe fe-send"></i>
                            </div>
                            <div class="ms-auto">
                                <h5 class="tx-13 tx-white-6 mb-3">Total Transfers</h5>
                                <h2 class="mb-0 tx-white">{{ number_format($stats['total_transfers']) }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card bg-success-gradient">
                    <div class="card-body text-white">
                        <div class="counter-status">
                            <div class="counter-icon">
                                <i class="fe fe-dollar-sign"></i>
                            </div>
                            <div class="ms-auto">
                                <h5 class="tx-13 tx-white-6 mb-3">Total Amount</h5>
                                <h2 class="mb-0 tx-white">৳{{ number_format($stats['total_amount'], 2) }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card bg-warning-gradient">
                    <div class="card-body text-white">
                        <div class="counter-status">
                            <div class="counter-icon">
                                <i class="fe fe-calendar"></i>
                            </div>
                            <div class="ms-auto">
                                <h5 class="tx-13 tx-white-6 mb-3">Today's Transfers</h5>
                                <h2 class="mb-0 tx-white">{{ number_format($stats['today_transfers']) }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card bg-info-gradient">
                    <div class="card-body text-white">
                        <div class="counter-status">
                            <div class="counter-icon">
                                <i class="fe fe-trending-up"></i>
                            </div>
                            <div class="ms-auto">
                                <h5 class="tx-13 tx-white-6 mb-3">Today's Amount</h5>
                                <h2 class="mb-0 tx-white">৳{{ number_format($stats['today_amount'], 2) }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Filter Transfers</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.finance.transfer.history') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="user_search" class="form-label">User Search</label>
                                    <input type="text" id="user_search" name="user_search" class="form-control" 
                                           placeholder="User name or email..." 
                                           value="{{ request('user_search') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="date_from" class="form-label">From Date</label>
                                    <input type="date" id="date_from" name="date_from" class="form-control" 
                                           value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="date_to" class="form-label">To Date</label>
                                    <input type="date" id="date_to" name="date_to" class="form-control" 
                                           value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="amount_min" class="form-label">Min Amount</label>
                                    <input type="number" id="amount_min" name="amount_min" class="form-control" 
                                           placeholder="0.00" step="0.01"
                                           value="{{ request('amount_min') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="amount_max" class="form-label">Max Amount</label>
                                    <input type="number" id="amount_max" name="amount_max" class="form-control" 
                                           placeholder="1000000.00" step="0.01"
                                           value="{{ request('amount_max') }}">
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fe fe-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                        @if(request()->hasAny(['user_search', 'date_from', 'date_to', 'amount_min', 'amount_max']))
                            <div class="mt-3">
                                <a href="{{ route('admin.finance.transfer.history') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fe fe-refresh-cw me-1"></i>Clear Filters
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Transfer History Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Transfer History</h5>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="window.location.reload()" data-bs-toggle="tooltip" title="Refresh">
                                <i class="fe fe-refresh-cw"></i>
                            </button>
                            <span class="badge bg-primary">{{ $transfers->total() }} transfers</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($transfers->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-vcenter text-nowrap table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>User</th>
                                            <th>Role</th>
                                            <th>Amount</th>
                                            <th>Commission</th>
                                            <th>Note</th>
                                            <th>Date & Time</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transfers as $transfer)
                                            <tr>
                                                <td>
                                                    <span class="fw-semibold text-primary">{{ $transfer->transaction_id }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm me-2 bg-primary-transparent rounded-circle d-flex align-items-center justify-content-center">
                                                            <span class="avatar-initials fw-semibold text-primary">
                                                                {{ strtoupper(substr($transfer->user->name ?? 'U', 0, 1)) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $transfer->user->name ?? 'Unknown User' }}</h6>
                                                            <small class="text-muted">{{ $transfer->user->email ?? 'N/A' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        $roleClass = match($transfer->user->role ?? 'user') {
                                                            'vendor' => 'bg-success-transparent text-success',
                                                            'admin' => 'bg-primary-transparent text-primary', 
                                                            'member' => 'bg-info-transparent text-info',
                                                            default => 'bg-secondary-transparent text-secondary'
                                                        };
                                                        $roleIcon = match($transfer->user->role ?? 'user') {
                                                            'vendor' => 'fe-briefcase',
                                                            'admin' => 'fe-shield',
                                                            'member' => 'fe-users',
                                                            default => 'fe-user'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $roleClass }}">
                                                        <i class="fe {{ $roleIcon }} me-1"></i>{{ ucfirst($transfer->user->role ?? 'User') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold text-success fs-16">
                                                        ৳{{ number_format($transfer->amount, 2) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($transfer->commission_amount > 0)
                                                        <div>
                                                            <span class="fw-semibold text-info">
                                                                {{ number_format($transfer->commission_rate, 1) }}%
                                                            </span><br>
                                                            <small class="text-muted">
                                                                ৳{{ number_format($transfer->commission_amount, 2) }}
                                                            </small>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="text-muted">
                                                        {{ Str::limit($transfer->note ?? 'No note', 30) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div>
                                                        <span class="fw-semibold">{{ $transfer->created_at->format('M d, Y') }}</span><br>
                                                        <small class="text-muted">{{ $transfer->created_at->format('h:i A') }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success-transparent text-success">
                                                        <i class="fe fe-check me-1"></i>Completed
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-list">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                                onclick="viewTransferDetails({{ $transfer->id }})"
                                                                data-bs-toggle="tooltip" title="View Details">
                                                            <i class="fe fe-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                                onclick="copyTransactionId('{{ $transfer->transaction_id }}')"
                                                                data-bs-toggle="tooltip" title="Copy Transaction ID">
                                                            <i class="fe fe-copy"></i>
                                                        </button>
                                                        <a href="{{ route('admin.users.show', $transfer->user_id) }}" 
                                                           class="btn btn-sm btn-outline-secondary"
                                                           data-bs-toggle="tooltip" title="View User">
                                                            <i class="fe fe-user"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="card-footer">
                                {{ $transfers->withQueryString()->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fe fe-send display-4 text-muted"></i>
                                    </div>
                                    <h3 class="empty-state-title">No transfers found</h3>
                                    <p class="empty-state-text">
                                        @if(request()->hasAny(['user_search', 'date_from', 'date_to', 'amount_min', 'amount_max']))
                                            No transfers match your current filter criteria.
                                        @else
                                            No admin transfers have been made yet.
                                        @endif
                                    </p>
                                    <div class="empty-state-action">
                                        @if(request()->hasAny(['user_search', 'date_from', 'date_to', 'amount_min', 'amount_max']))
                                            <a href="{{ route('admin.finance.transfer.history') }}" class="btn btn-primary">
                                                <i class="fe fe-refresh-cw me-2"></i>Clear Filters
                                            </a>
                                        @else
                                            <a href="{{ route('admin.finance.transfer') }}" class="btn btn-primary">
                                                <i class="fe fe-plus me-2"></i>Make First Transfer
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transfer Details Modal -->
<div class="modal fade" id="transferDetailsModal" tabindex="-1" aria-labelledby="transferDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="transferDetailsModalLabel">
                    <i class="fe fe-file-text me-2"></i>Transfer Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="transferDetailsContent">
                <div class="d-flex justify-content-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.counter-status {
    display: flex;
    align-items: center;
}

.counter-icon {
    font-size: 2rem;
    opacity: 0.8;
}

.bg-primary-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.bg-success-gradient {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
}

.bg-warning-gradient {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
}

.bg-info-gradient {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
}

.empty-state {
    padding: 3rem 1rem;
}

.empty-state-icon {
    margin-bottom: 1rem;
}

.empty-state-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.empty-state-text {
    color: #6c757d;
    margin-bottom: 1.5rem;
}

.avatar-initials {
    font-size: 12px;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,0.02);
}

@media (max-width: 768px) {
    .counter-status {
        flex-direction: column;
        text-align: center;
    }
    
    .counter-icon {
        margin-bottom: 1rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>

<script>
function viewTransferDetails(transferId) {
    const modal = new bootstrap.Modal(document.getElementById('transferDetailsModal'));
    const content = document.getElementById('transferDetailsContent');
    
    // Show loading state
    content.innerHTML = `
        <div class="d-flex justify-content-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    
    modal.show();
    
    // Fetch transfer details via AJAX
    const detailsUrl = '{{ route("admin.finance.transfer.details", ":id") }}'.replace(':id', transferId);
    fetch(detailsUrl, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const transfer = data.transfer;
            content.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-0 bg-light">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fe fe-file-text me-2"></i>Transaction Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Transaction ID</label>
                                    <div class="form-control-plaintext fw-bold text-primary">${transfer.transaction_id}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Amount</label>
                                    <div class="form-control-plaintext text-success fw-bold fs-5">৳${parseFloat(transfer.amount).toLocaleString()}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Wallet Type</label>
                                    <div class="form-control-plaintext">
                                        <span class="badge bg-info-transparent text-info">${transfer.wallet_type.replace('_', ' ').toUpperCase()}</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Status</label>
                                    <div class="form-control-plaintext">
                                        <span class="badge bg-success-transparent text-success">
                                            <i class="fe fe-check me-1"></i>${transfer.status.toUpperCase()}
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Transfer Date</label>
                                    <div class="form-control-plaintext">${transfer.created_at}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 bg-light mb-3">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fe fe-user me-2"></i>Recipient Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-md me-3 bg-success-transparent rounded-circle d-flex align-items-center justify-content-center">
                                        <span class="avatar-initials fw-semibold text-success">
                                            ${transfer.user.name.charAt(0).toUpperCase()}
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">${transfer.user.name}</h6>
                                        <small class="text-muted">User ID: ${transfer.user.id}</small>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-semibold">Email</label>
                                    <div class="form-control-plaintext">${transfer.user.email}</div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-semibold">Phone</label>
                                    <div class="form-control-plaintext">${transfer.user.phone}</div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-semibold">Member Since</label>
                                    <div class="form-control-plaintext">${transfer.user.member_since}</div>
                                </div>
                                <div class="mt-3">
                                    <a href="#" onclick="window.open('{{ url('admin/users') }}/' + transfer.user.id, '_blank')" class="btn btn-sm btn-outline-primary">
                                        <i class="fe fe-external-link me-1"></i>View User Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card border-0 bg-light">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0"><i class="fe fe-shield me-2"></i>Admin Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label fw-semibold">Processed By</label>
                                    <div class="form-control-plaintext">${transfer.admin.name}</div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-semibold">Admin Email</label>
                                    <div class="form-control-plaintext">${transfer.admin.email}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0"><i class="fe fe-message-square me-2"></i>Transfer Note & Description</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Note</label>
                                    <div class="form-control-plaintext">${transfer.note || 'No note provided'}</div>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label fw-semibold">Description</label>
                                    <div class="form-control-plaintext text-muted">${transfer.description}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else {
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fe fe-alert-circle me-2"></i>
                    ${data.message || 'Failed to load transfer details.'}
                </div>
            `;
        }
    })
    .catch(error => {
        content.innerHTML = `
            <div class="alert alert-danger">
                <i class="fe fe-alert-circle me-2"></i>
                Network error. Please check your connection and try again.
            </div>
        `;
    });
}

// Export transfers to CSV
function exportTransfers() {
    const currentParams = new URLSearchParams(window.location.search);
    let exportUrl = '{{ route("admin.finance.transfer.export") }}';
    
    if (currentParams.toString()) {
        exportUrl += '?' + currentParams.toString();
    }
    
    // Create a temporary link and trigger download
    const link = document.createElement('a');
    link.href = exportUrl;
    link.download = 'admin-transfers-' + new Date().toISOString().split('T')[0] + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Copy transaction ID to clipboard
function copyTransactionId(transactionId) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(transactionId).then(function() {
            // Show success toast
            showToast('success', 'Transaction ID copied to clipboard!');
        }).catch(function() {
            fallbackCopy(transactionId);
        });
    } else {
        fallbackCopy(transactionId);
    }
}

function fallbackCopy(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    document.body.appendChild(textArea);
    textArea.select();
    try {
        document.execCommand('copy');
        showToast('success', 'Transaction ID copied to clipboard!');
    } catch (err) {
        showToast('error', 'Failed to copy transaction ID');
    }
    document.body.removeChild(textArea);
}

// Show toast notification
function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed top-0 end-0 m-3`;
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        <i class="fe fe-${type === 'success' ? 'check' : 'alert-circle'} me-2"></i>
        ${message}
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
