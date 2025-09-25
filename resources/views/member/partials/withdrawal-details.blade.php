<div class="row">
    <div class="col-md-6">
        <h6 class="text-muted mb-3">Transaction Details</h6>
        <div class="mb-2">
            <small class="text-muted">Transaction ID:</small>
            <div class="fw-semibold">#{{ $withdrawal->transaction_id }}</div>
        </div>
        <div class="mb-2">
            <small class="text-muted">Amount:</small>
            <div class="fw-semibold text-primary">৳{{ number_format($withdrawal->amount, 2) }}</div>
        </div>
        @if($withdrawal->fee > 0)
        <div class="mb-2">
            <small class="text-muted">Processing Fee:</small>
            <div class="fw-semibold text-danger">৳{{ number_format($withdrawal->fee, 2) }}</div>
        </div>
        <div class="mb-2">
            <small class="text-muted">Net Amount:</small>
            <div class="fw-semibold text-success">৳{{ number_format($withdrawal->amount - $withdrawal->fee, 2) }}</div>
        </div>
        @endif
        <div class="mb-2">
            <small class="text-muted">Wallet Type:</small>
            <div class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $withdrawal->wallet_type)) }}</div>
        </div>
        <div class="mb-2">
            <small class="text-muted">Status:</small>
            <div>
                @switch($withdrawal->status)
                    @case('pending')
                        <span class="badge bg-warning">Pending</span>
                        @break
                    @case('approved')
                        <span class="badge bg-info">Approved</span>
                        @break
                    @case('completed')
                        <span class="badge bg-success">Completed</span>
                        @break
                    @case('rejected')
                        <span class="badge bg-danger">Rejected</span>
                        @break
                    @case('cancelled')
                        <span class="badge bg-secondary">Cancelled</span>
                        @break
                    @default
                        <span class="badge bg-light text-dark">{{ ucfirst($withdrawal->status) }}</span>
                @endswitch
            </div>
        </div>
        <div class="mb-2">
            <small class="text-muted">Request Date:</small>
            <div class="fw-semibold">{{ $withdrawal->created_at->format('M d, Y \a\t g:i A') }}</div>
        </div>
        @if($withdrawal->processed_at)
        <div class="mb-2">
            <small class="text-muted">Processed Date:</small>
            <div class="fw-semibold">{{ $withdrawal->processed_at->format('M d, Y \a\t g:i A') }}</div>
        </div>
        @endif
    </div>
    <div class="col-md-6">
        <h6 class="text-muted mb-3">Payment Details</h6>
        <div class="mb-2">
            <small class="text-muted">Payment Method:</small>
            <div class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $withdrawal->payment_method)) }}</div>
        </div>
        <div class="mb-2">
            <small class="text-muted">Account Number:</small>
            <div class="fw-semibold">{{ $withdrawal->account_number }}</div>
        </div>
        <div class="mb-2">
            <small class="text-muted">Account Name:</small>
            <div class="fw-semibold">{{ $withdrawal->account_name }}</div>
        </div>
        @if($withdrawal->note)
        <div class="mb-2">
            <small class="text-muted">Note:</small>
            <div class="fw-semibold">{{ $withdrawal->note }}</div>
        </div>
        @endif
        @if($withdrawal->description)
        <div class="mb-2">
            <small class="text-muted">Description:</small>
            <div class="fw-semibold">{{ $withdrawal->description }}</div>
        </div>
        @endif
    </div>
</div>

@if($withdrawal->status === 'rejected' && $withdrawal->metadata && isset($withdrawal->metadata['rejection_reason']))
<div class="mt-3">
    <div class="alert alert-danger">
        <h6 class="alert-heading">Rejection Reason:</h6>
        <p class="mb-0">{{ $withdrawal->metadata['rejection_reason'] }}</p>
    </div>
</div>
@endif

@if($withdrawal->status === 'pending')
<div class="mt-3">
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        Your withdrawal request is being processed. You will receive an update within 24-48 hours.
    </div>
</div>
@endif

@if($withdrawal->status === 'completed')
<div class="mt-3">
    <div class="alert alert-success">
        <i class="fas fa-check-circle me-2"></i>
        Your withdrawal has been completed successfully. The amount has been transferred to your account.
    </div>
</div>
@endif
