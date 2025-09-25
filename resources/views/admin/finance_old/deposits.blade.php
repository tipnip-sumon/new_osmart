@extends('admin.layouts.app')

@section('title', 'Fund Deposits Management')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Fund Deposits</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.finance.dashboard') }}">Finance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Deposits</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar avatar-md avatar-rounded bg-primary">
                                    <i class="fe fe-trending-up fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <h6 class="fw-semibold mb-0">Total Deposits</h6>
                                <span class="text-muted">৳{{ number_format($stats['total_deposits'] ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar avatar-md avatar-rounded bg-warning">
                                    <i class="fe fe-clock fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <h6 class="fw-semibold mb-0">Pending Deposits</h6>
                                <span class="text-muted">৳{{ number_format($stats['pending_deposits'] ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar avatar-md avatar-rounded bg-success">
                                    <i class="fe fe-check-circle fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <h6 class="fw-semibold mb-0">Approved Today</h6>
                                <span class="text-muted">৳{{ number_format($stats['approved_today'] ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar avatar-md avatar-rounded bg-info">
                                    <i class="fe fe-users fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <h6 class="fw-semibold mb-0">Total Requests</h6>
                                <span class="text-muted">{{ $stats['total_requests'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-filter me-2"></i>Filter Deposits
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.finance.deposits') }}" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_method" class="form-select">
                                    <option value="">All Methods</option>
                                    <option value="bkash" {{ request('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                                    <option value="nagad" {{ request('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                                    <option value="rocket" {{ request('payment_method') == 'rocket' ? 'selected' : '' }}>Rocket</option>
                                    <option value="upay" {{ request('payment_method') == 'upay' ? 'selected' : '' }}>Upay</option>
                                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">From Date</label>
                                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">To Date</label>
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fe fe-search me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('admin.finance.deposits') }}" class="btn btn-secondary">
                                        <i class="fe fe-refresh-cw me-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deposits Table -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">
                            <i class="fe fe-list me-2"></i>Deposit Requests
                            @if($deposits->total() > 0)
                                <span class="badge bg-primary ms-2">{{ $deposits->total() }} Total</span>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-success btn-sm" onclick="bulkApprove()" id="bulkApproveBtn" style="display: none;">
                                <i class="fe fe-check me-1"></i>Approve Selected
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="bulkReject()" id="bulkRejectBtn" style="display: none;">
                                <i class="fe fe-x me-1"></i>Reject Selected
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($deposits->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                            </th>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Amount</th>
                                            <th>Method</th>
                                            <th>Transaction ID</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($deposits as $deposit)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="deposit-checkbox" value="{{ $deposit->id }}" onchange="updateBulkButtons()">
                                            </td>
                                            <td><span class="fw-semibold">#{{ $deposit->id }}</span></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $deposit->user->avatar_url ?? asset('admin-assets/images/faces/1.jpg') }}" alt="user" class="avatar avatar-sm rounded-circle me-2">
                                                    <div>
                                                        <p class="mb-0 fw-semibold">{{ $deposit->user->name }}</p>
                                                        <p class="mb-0 text-muted fs-12">{{ $deposit->user->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">৳{{ number_format($deposit->amount, 2) }}</span>
                                                @if($deposit->fee > 0)
                                                    <br><small class="text-muted">Fee: ৳{{ number_format($deposit->fee, 2) }}</small>
                                                    <br><small class="text-success">Net: ৳{{ number_format($deposit->net_amount, 2) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-primary-transparent">
                                                    {{ ucfirst(str_replace('_', ' ', $deposit->payment_method)) }}
                                                </span>
                                                @if($deposit->sender_number)
                                                    <br><small class="text-muted">{{ $deposit->sender_number }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-semibold">{{ $deposit->transaction_id }}</span>
                                            </td>
                                            <td>
                                                @if($deposit->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($deposit->status == 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($deposit->status == 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $deposit->created_at->format('M d, Y') }}</span>
                                                <br><small class="text-muted">{{ $deposit->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-primary" onclick="viewDeposit({{ $deposit->id }})">
                                                        <i class="fe fe-eye"></i>
                                                    </button>
                                                    @if($deposit->status == 'pending')
                                                        <button type="button" class="btn btn-sm btn-success" onclick="approveDeposit({{ $deposit->id }})">
                                                            <i class="fe fe-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="rejectDeposit({{ $deposit->id }})">
                                                            <i class="fe fe-x"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <p class="text-muted mb-0">
                                        Showing {{ $deposits->firstItem() }} to {{ $deposits->lastItem() }} of {{ $deposits->total() }} results
                                    </p>
                                </div>
                                <div>
                                    {{ $deposits->appends(request()->query())->links() }}
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <img src="{{ asset('admin-assets/images/svgs/not-found.svg') }}" alt="No deposits" class="w-25 mx-auto mb-3">
                                <h5 class="text-muted">No deposit requests found</h5>
                                <p class="text-muted">No deposit requests match your current filters.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deposit Details Modal -->
<div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="depositModalLabel">Deposit Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="depositModalBody">
                <!-- Deposit details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Reject Reason Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Deposit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm">
                <div class="modal-body">
                    <input type="hidden" id="rejectDepositId">
                    <div class="mb-3">
                        <label for="rejectReason" class="form-label">Rejection Reason *</label>
                        <textarea class="form-control" id="rejectReason" name="reason" rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Deposit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function viewDeposit(id) {
    $.ajax({
        url: `{{ route('admin.finance.deposits') }}/${id}`,
        type: 'GET',
        success: function(response) {
            $('#depositModalBody').html(response.html);
            $('#depositModal').modal('show');
        },
        error: function() {
            Swal.fire('Error', 'Failed to load deposit details', 'error');
        }
    });
}

function approveDeposit(id) {
    Swal.fire({
        title: 'Approve Deposit?',
        text: 'This will credit the amount to user\'s wallet.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Yes, Approve!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `{{ route('admin.finance.deposits') }}/${id}/approve`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Approved!', response.message, 'success');
                        location.reload();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to approve deposit', 'error');
                }
            });
        }
    });
}

function rejectDeposit(id) {
    $('#rejectDepositId').val(id);
    $('#rejectModal').modal('show');
}

$('#rejectForm').submit(function(e) {
    e.preventDefault();
    
    const id = $('#rejectDepositId').val();
    const reason = $('#rejectReason').val();
    
    $.ajax({
        url: `{{ route('admin.finance.deposits') }}/${id}/reject`,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            reason: reason
        },
        success: function(response) {
            $('#rejectModal').modal('hide');
            if (response.success) {
                Swal.fire('Rejected!', response.message, 'success');
                location.reload();
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Failed to reject deposit', 'error');
        }
    });
});

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.deposit-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkButtons();
}

function updateBulkButtons() {
    const checkboxes = document.querySelectorAll('.deposit-checkbox:checked');
    const bulkApproveBtn = document.getElementById('bulkApproveBtn');
    const bulkRejectBtn = document.getElementById('bulkRejectBtn');
    
    if (checkboxes.length > 0) {
        bulkApproveBtn.style.display = 'inline-block';
        bulkRejectBtn.style.display = 'inline-block';
    } else {
        bulkApproveBtn.style.display = 'none';
        bulkRejectBtn.style.display = 'none';
    }
}

function bulkApprove() {
    const selectedIds = Array.from(document.querySelectorAll('.deposit-checkbox:checked')).map(cb => cb.value);
    
    if (selectedIds.length === 0) {
        Swal.fire('Error', 'Please select deposits to approve', 'error');
        return;
    }
    
    Swal.fire({
        title: `Approve ${selectedIds.length} Deposits?`,
        text: 'This will credit the amounts to users\' wallets.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Yes, Approve All!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `{{ route('admin.finance.deposits.bulk-approve') }}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    deposit_ids: selectedIds
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Approved!', response.message, 'success');
                        location.reload();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to approve deposits', 'error');
                }
            });
        }
    });
}

function bulkReject() {
    const selectedIds = Array.from(document.querySelectorAll('.deposit-checkbox:checked')).map(cb => cb.value);
    
    if (selectedIds.length === 0) {
        Swal.fire('Error', 'Please select deposits to reject', 'error');
        return;
    }
    
    Swal.fire({
        title: `Reject ${selectedIds.length} Deposits?`,
        input: 'textarea',
        inputPlaceholder: 'Enter rejection reason...',
        inputValidator: (value) => {
            if (!value) {
                return 'Rejection reason is required!'
            }
        },
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Reject All!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `{{ route('admin.finance.deposits.bulk-reject') }}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    deposit_ids: selectedIds,
                    reason: result.value
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Rejected!', response.message, 'success');
                        location.reload();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to reject deposits', 'error');
                }
            });
        }
    });
}
</script>
@endpush
