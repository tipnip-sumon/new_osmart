<div class="row">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fe fe-user me-2"></i>User Information
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $deposit->user->avatar_url ?? asset('admin-assets/images/faces/1.jpg') }}" alt="user" class="avatar avatar-lg rounded-circle me-3">
                    <div>
                        <h6 class="mb-1">{{ $deposit->user->name }}</h6>
                        <p class="text-muted mb-0">{{ $deposit->user->email }}</p>
                        @if($deposit->user->phone)
                            <p class="text-muted mb-0"><i class="fe fe-phone me-1"></i>{{ $deposit->user->phone }}</p>
                        @endif
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted">User ID</small>
                        <p class="fw-semibold">#{{ $deposit->user->id }}</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Balance</small>
                        <p class="fw-semibold text-success">৳{{ number_format($deposit->user->balance ?? 0, 2) }}</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Join Date</small>
                        <p class="fw-semibold">{{ $deposit->user->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Status</small>
                        <p class="fw-semibold">
                            @if($deposit->user->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fe fe-credit-card me-2"></i>Deposit Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted">Deposit ID</small>
                        <p class="fw-semibold">#{{ $deposit->id }}</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Status</small>
                        <p class="fw-semibold">
                            @if($deposit->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($deposit->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($deposit->status == 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Amount</small>
                        <p class="fw-semibold text-primary">৳{{ number_format($deposit->amount, 2) }}</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Fee</small>
                        <p class="fw-semibold text-warning">৳{{ number_format($deposit->fee, 2) }}</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Net Amount</small>
                        <p class="fw-semibold text-success">৳{{ number_format($deposit->net_amount, 2) }}</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Currency</small>
                        <p class="fw-semibold">{{ $deposit->currency ?? 'BDT' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fe fe-smartphone me-2"></i>Payment Details
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <small class="text-muted">Payment Method</small>
                        <p class="fw-semibold">
                            <span class="badge bg-primary-transparent fs-12">
                                {{ ucfirst(str_replace('_', ' ', $deposit->payment_method)) }}
                            </span>
                        </p>
                    </div>
                    @if($deposit->sender_number)
                    <div class="col-12">
                        <small class="text-muted">Sender Number</small>
                        <p class="fw-semibold">{{ $deposit->sender_number }}</p>
                    </div>
                    @endif
                    <div class="col-12">
                        <small class="text-muted">Transaction ID</small>
                        <p class="fw-semibold">{{ $deposit->transaction_id }}</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Submitted</small>
                        <p class="fw-semibold">{{ $deposit->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @if($deposit->processed_at)
                    <div class="col-6">
                        <small class="text-muted">Processed</small>
                        <p class="fw-semibold">{{ $deposit->processed_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                </div>
                
                @if($deposit->description)
                <hr>
                <small class="text-muted">Description</small>
                <p class="fw-semibold">{{ $deposit->description }}</p>
                @endif
                
                @if($deposit->rejection_reason)
                <hr>
                <small class="text-muted">Rejection Reason</small>
                <div class="alert alert-danger">
                    {{ $deposit->rejection_reason }}
                </div>
                @endif
            </div>
        </div>
    </div>
    
    @if($deposit->receipt_path)
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fe fe-image me-2"></i>Payment Receipt
                </h6>
            </div>
            <div class="card-body text-center">
                <img src="{{ Storage::url($deposit->receipt_path) }}" alt="Receipt" class="img-fluid rounded" style="max-height: 300px;">
                <div class="mt-3">
                    <a href="{{ Storage::url($deposit->receipt_path) }}" target="_blank" class="btn btn-sm btn-primary">
                        <i class="fe fe-external-link me-1"></i>View Full Size
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@if($deposit->status == 'pending')
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fe fe-settings me-2"></i>Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success" onclick="approveDepositFromModal({{ $deposit->id }})">
                        <i class="fe fe-check me-1"></i>Approve Deposit
                    </button>
                    <button type="button" class="btn btn-danger" onclick="rejectDepositFromModal({{ $deposit->id }})">
                        <i class="fe fe-x me-1"></i>Reject Deposit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
function approveDepositFromModal(id) {
    $('#depositModal').modal('hide');
    approveDeposit(id);
}

function rejectDepositFromModal(id) {
    $('#depositModal').modal('hide');
    rejectDeposit(id);
}
</script>
