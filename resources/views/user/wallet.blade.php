@extends('layouts.app')

@section('title', 'My Wallet - ' . config('app.name'))

@push('styles')
<style>
.wallet-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
}

.wallet-balance {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    color: white;
    padding: 2rem;
    margin-bottom: 2rem;
}

.balance-amount {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.balance-label {
    opacity: 0.9;
    font-size: 1.1rem;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.action-card {
    background: white;
    border: 1px solid #f1f3f4;
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
}

.action-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    text-decoration: none;
    color: inherit;
}

.action-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
}

.action-add {
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white;
}

.action-withdraw {
    background: linear-gradient(45deg, #dc3545, #fd7e14);
    color: white;
}

.action-transfer {
    background: linear-gradient(45deg, #007bff, #6f42c1);
    color: white;
}

.action-history {
    background: linear-gradient(45deg, #6c757d, #495057);
    color: white;
}

.transaction-item {
    display: flex;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.transaction-item:last-child {
    border-bottom: none;
}

.transaction-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.transaction-credit {
    background: #d4edda;
    color: #155724;
}

.transaction-debit {
    background: #f8d7da;
    color: #721c24;
}

.transaction-details {
    flex-grow: 1;
}

.transaction-amount {
    font-weight: 600;
    text-align: right;
}

.amount-credit {
    color: #28a745;
}

.amount-debit {
    color: #dc3545;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    color: #dee2e6;
}

@media (max-width: 768px) {
    .balance-amount {
        font-size: 2rem;
    }
    
    .quick-actions {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .action-card {
        padding: 1rem;
    }
    
    .action-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
    
    .transaction-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .transaction-amount {
        text-align: left;
        align-self: stretch;
    }
}
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary me-3">
                    <i class="ti ti-arrow-left me-1"></i>Back
                </a>
                <h2 class="h3 mb-0">My Wallet</h2>
            </div>

            <!-- Wallet Balance -->
            <div class="wallet-balance">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="balance-label">Available Balance</div>
                        <div class="balance-amount">৳{{ number_format($walletBalance, 2) }}</div>
                        <div class="balance-label">Updated just now</div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <i class="ti ti-wallet" style="font-size: 4rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <a href="#" class="action-card">
                    <div class="action-icon action-add">
                        <i class="ti ti-plus"></i>
                    </div>
                    <h6>Add Funds</h6>
                    <small class="text-muted">Top up your wallet</small>
                </a>

                <a href="{{ route('member.withdraw') }}" class="action-card">
                    <div class="action-icon action-withdraw">
                        <i class="ti ti-minus"></i>
                    </div>
                    <h6>Withdraw</h6>
                    <small class="text-muted">Cash out your funds</small>
                </a>

                <a href="{{ route('member.transfer') }}" class="action-card">
                    <div class="action-icon action-transfer">
                        <i class="ti ti-arrow-right"></i>
                    </div>
                    <h6>Transfer</h6>
                    <small class="text-muted">Send to other users</small>
                </a>

                <a href="#" class="action-card">
                    <div class="action-icon action-history">
                        <i class="ti ti-history"></i>
                    </div>
                    <h6>History</h6>
                    <small class="text-muted">View all transactions</small>
                </a>
            </div>

            <!-- Recent Transactions -->
            <div class="wallet-card card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ti ti-list me-2"></i>Recent Transactions
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentTransactions->count() > 0)
                        @foreach($recentTransactions as $transaction)
                            <div class="transaction-item">
                                <div class="transaction-icon transaction-{{ $transaction->type }}">
                                    @if($transaction->type === 'credit')
                                        <i class="ti ti-arrow-down"></i>
                                    @else
                                        <i class="ti ti-arrow-up"></i>
                                    @endif
                                </div>
                                <div class="transaction-details">
                                    <h6 class="mb-1">{{ $transaction->description }}</h6>
                                    <small class="text-muted">
                                        {{ $transaction->date->format('M d, Y - h:i A') }} • 
                                        <span class="badge bg-secondary">{{ ucfirst($transaction->status) }}</span>
                                    </small>
                                </div>
                                <div class="transaction-amount amount-{{ $transaction->type }}">
                                    @if($transaction->type === 'credit')
                                        +৳{{ number_format($transaction->amount, 2) }}
                                    @else
                                        -৳{{ number_format($transaction->amount, 2) }}
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-primary">
                                <i class="ti ti-eye me-1"></i>View All Transactions
                            </a>
                        </div>

                    @else
                        <!-- Empty State -->
                        <div class="empty-state">
                            <i class="ti ti-receipt-off"></i>
                            <h4>No Transactions Yet</h4>
                            <p class="mb-3">Your transaction history will appear here once you start using your wallet.</p>
                            <a href="#" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i>Add Funds
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Wallet Information -->
            <div class="wallet-card card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ti ti-info-circle me-2"></i>Wallet Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>How to add funds:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Click "Add Funds" button
                                </li>
                                <li class="mb-2">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Choose payment method
                                </li>
                                <li class="mb-2">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Complete payment process
                                </li>
                                <li class="mb-0">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Funds reflect instantly
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Withdrawal process:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Minimum withdrawal: ৳100
                                </li>
                                <li class="mb-2">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Processing time: 24-48 hours
                                </li>
                                <li class="mb-2">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Bank transfer available
                                </li>
                                <li class="mb-0">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Mobile money supported
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Wallet page loaded successfully');
});
</script>
@endpush
