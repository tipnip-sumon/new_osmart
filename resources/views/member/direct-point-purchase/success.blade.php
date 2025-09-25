@extends('member.layouts.app')

@section('title', 'Purchase Successful')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Header -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <div class="success-icon mx-auto mb-3">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h2 class="text-success fw-bold">Purchase Successful!</h2>
                        <p class="text-muted lead">Your purchase has been completed successfully</p>
                    </div>

                    <!-- Purchase Summary -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="bg-light rounded p-3">
                                @if(isset($successData['requires_manual_activation']) && $successData['requires_manual_activation'])
                                    <h4 class="text-warning fw-bold mb-1">{{ number_format($successData['points_pending'] ?? 0) }}</h4>
                                    <small class="text-muted">Points Pending Activation</small>
                                    <div class="mt-1">
                                        <span class="badge bg-warning text-dark">Manual Activation Required</span>
                                    </div>
                                @else
                                    <h4 class="text-primary fw-bold mb-1">{{ number_format($successData['points_purchased'] ?? 0) }}</h4>
                                    <small class="text-muted">Points Received</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="bg-light rounded p-3">
                                <h4 class="text-danger fw-bold mb-1">৳{{ number_format($successData['amount_paid'] ?? 0, 2) }}</h4>
                                <small class="text-muted">Amount Paid</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="bg-light rounded p-3">
                                <h4 class="text-success fw-bold mb-1">৳{{ number_format($successData['remaining_balance'] ?? $user->deposit_wallet, 2) }}</h4>
                                <small class="text-muted">Remaining Balance</small>
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="card bg-light border-0">
                                <div class="card-body p-3">
                                    <h6 class="fw-bold text-warning mb-2">
                                        <i class="fas fa-box me-1"></i>{{ $successData['product_name'] ?? 'Activation Package' }}
                                    </h6>
                                    @if($successData['order_number'] ?? null)
                                    <p class="text-success small mb-0">
                                        <i class="fas fa-receipt me-1"></i>Order: {{ $successData['order_number'] }}
                                        @if($successData['order_status'] ?? null)
                                            <span class="badge bg-{{ $successData['order_status'] === 'completed' ? 'success' : ($successData['order_status'] === 'pending' ? 'warning' : 'info') }} ms-2">
                                                {{ ucfirst($successData['order_status']) }}
                                            </span>
                                        @endif
                                    </p>
                                    @else
                                    <p class="text-muted small mb-0">
                                        <i class="fas fa-check me-1"></i>Direct Point Purchase Completed
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commission Benefits -->
            @if($recentCommissions->count() > 0)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>Commission Benefits Distributed
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0 mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Great Job!</strong> Your purchase helped distribute <strong>৳{{ number_format($recentCommissions->sum('commission_amount'), 2) }}</strong> in commissions to {{ $recentCommissions->count() }} upline members.
                    </div>
                    
                    <div class="row">
                        @foreach($recentCommissions->take(4) as $commission)
                        <div class="col-md-6 mb-2">
                            <div class="bg-light rounded p-2 small">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-user text-primary me-1"></i>
                                        <strong>{{ $commission->user->name ?? 'Member' }}</strong>
                                        <div class="text-muted small">{{ ucfirst($commission->commission_type) }} Level {{ $commission->level }}</div>
                                    </div>
                                    <span class="text-success fw-bold">৳{{ number_format($commission->commission_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($recentCommissions->count() > 4)
                        <div class="col-12">
                            <p class="text-muted small text-center mb-0 mt-2">
                                + {{ $recentCommissions->count() - 4 }} more members received commissions
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>Commission Benefits
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Great Job!</strong> Your purchase helped strengthen the MLM network. Commission distribution may take a few minutes to process.
                    </div>
                </div>
            </div>
            @endif

            <!-- Account Activation -->
            @if($successData['account_activated'] ?? false)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-check me-2"></i>Account Activated
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success fa-3x me-3"></i>
                        <div>
                            <h6 class="fw-bold text-success mb-1">Account Successfully Activated!</h6>
                            <p class="text-muted mb-0">Your account has been activated with this purchase. You can now access all member features and start earning commissions.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Shipping Information (if applicable) -->
            @if($successData['shipping_provided'] ?? false)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-shipping-fast me-2"></i>Shipping Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0">
                        <i class="fas fa-truck me-2"></i>
                        <strong>Product will be shipped!</strong> Your activation package will be delivered to your provided address within 3-7 business days. We'll send tracking information via SMS/Email.
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="text-center">
                <a href="{{ route('member.dashboard') }}" class="btn btn-primary me-3">
                    <i class="fas fa-tachometer-alt me-1"></i>Go to Dashboard
                </a>
                <a href="{{ route('member.direct-point-purchase.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-shopping-cart me-1"></i>Make Another Purchase
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.success-icon {
    animation: successPulse 2s ease-in-out infinite;
}

@keyframes successPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
</style>
@endpush
@endsection
