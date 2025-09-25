@extends('member.layouts.app')

@section('title', 'My Wallet')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row my-4">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">My Wallet</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Wallet</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Wallet Overview Cards -->
    <div class="row">
        <!-- Balance Card -->
        <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="mb-2">
                                <span class="d-block fw-medium">Main Balance</span>
                            </div>
                            <h4 class="fw-semibold mb-2">৳{{ number_format($user->balance ?? 0, 2) }}</h4>
                            <div>
                                <span class="text-primary me-1"><i class="bx bx-wallet align-middle"></i></span>
                                <span class="text-muted">Available</span>
                            </div>
                        </div>
                        <div class="ms-2">
                            <span class="avatar avatar-md bg-primary-transparent">
                                <i class="bx bx-wallet fs-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deposit Wallet -->
        <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="mb-2">
                                <span class="d-block fw-medium">Deposit Wallet</span>
                            </div>
                            <h4 class="fw-semibold mb-2">৳{{ number_format($user->deposit_wallet ?? 0, 2) }}</h4>
                            <div>
                                <span class="text-success me-1"><i class="bx bx-plus-circle align-middle"></i></span>
                                <span class="text-muted">Deposits</span>
                            </div>
                        </div>
                        <div class="ms-2">
                            <span class="avatar avatar-md bg-success-transparent">
                                <i class="bx bx-plus-circle fs-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Interest Wallet -->
        <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="mb-2">
                                <span class="d-block fw-medium">Interest Wallet</span>
                            </div>
                            <h4 class="fw-semibold mb-2">৳{{ number_format($user->interest_wallet ?? 0, 2) }}</h4>
                            <div>
                                <span class="text-info me-1"><i class="bx bx-trending-up align-middle"></i></span>
                                <span class="text-muted">Earnings</span>
                            </div>
                        </div>
                        <div class="ms-2">
                            <span class="avatar avatar-md bg-info-transparent">
                                <i class="bx bx-trending-up fs-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="mb-2">
                                <span class="d-block fw-medium">Total Earnings</span>
                            </div>
                            <h4 class="fw-semibold mb-2">৳{{ number_format($totalEarnings, 2) }}</h4>
                            <div>
                                <span class="text-warning me-1"><i class="bx bx-trophy align-middle"></i></span>
                                <span class="text-muted">All Time</span>
                            </div>
                        </div>
                        <div class="ms-2">
                            <span class="avatar avatar-md bg-warning-transparent">
                                <i class="bx bx-trophy fs-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        <h6 class="mb-0">Quick Actions</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <a href="{{ route('member.invest.create') }}" class="btn btn-primary w-100">
                                <i class="bx bx-plus-circle me-1"></i>
                                Invest
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <a href="#" class="btn btn-success w-100" onclick="showWithdrawModal()">
                                <i class="bx bx-minus-circle me-1"></i>
                                Withdraw
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <a href="#" class="btn btn-info w-100" onclick="alert('Transfer feature coming soon!')">
                                <i class="bx bx-transfer me-1"></i>
                                Transfer
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <a href="#" class="btn btn-warning w-100" onclick="alert('Deposit feature coming soon!')">
                                <i class="bx bx-credit-card me-1"></i>
                                Deposit
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <a href="{{ route('member.invest.index') }}" class="btn btn-secondary w-100">
                                <i class="bx bx-history me-1"></i>
                                History
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <a href="{{ route('member.dashboard') }}" class="btn btn-light w-100">
                                <i class="bx bx-chart me-1"></i>
                                Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">
                        <h6 class="mb-0">Recent Transactions</h6>
                    </div>
                    <div>
                        <a href="#" class="btn btn-sm btn-primary">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    @if($walletTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Transaction ID</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($walletTransactions as $transaction)
                                        <tr>
                                            <td>
                                                <span class="fw-medium">{{ $transaction->created_at->format('M d, Y') }}</span><br>
                                                <small class="text-muted">{{ $transaction->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <span class="fw-medium text-primary">#{{ $transaction->id }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $typeClass = '';
                                                    $typeIcon = '';
                                                    switch($transaction->type) {
                                                        case 'deposit':
                                                            $typeClass = 'success';
                                                            $typeIcon = 'bx-plus-circle';
                                                            break;
                                                        case 'withdrawal':
                                                            $typeClass = 'danger';
                                                            $typeIcon = 'bx-minus-circle';
                                                            break;
                                                        case 'transfer':
                                                            $typeClass = 'info';
                                                            $typeIcon = 'bx-transfer';
                                                            break;
                                                        case 'investment':
                                                            $typeClass = 'primary';
                                                            $typeIcon = 'bx-trending-up';
                                                            break;
                                                        default:
                                                            $typeClass = 'secondary';
                                                            $typeIcon = 'bx-circle';
                                                    }
                                                @endphp
                                                <span class="badge bg-{{ $typeClass }}-transparent">
                                                    <i class="bx {{ $typeIcon }} me-1"></i>
                                                    {{ ucfirst($transaction->type) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold 
                                                    @if(in_array($transaction->type, ['deposit', 'investment_return'])) text-success 
                                                    @else text-danger @endif">
                                                    @if(in_array($transaction->type, ['deposit', 'investment_return'])) + @else - @endif
                                                    ৳{{ number_format($transaction->amount, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = '';
                                                    switch($transaction->status ?? 'completed') {
                                                        case 'completed':
                                                            $statusClass = 'success';
                                                            break;
                                                        case 'pending':
                                                            $statusClass = 'warning';
                                                            break;
                                                        case 'failed':
                                                            $statusClass = 'danger';
                                                            break;
                                                        default:
                                                            $statusClass = 'secondary';
                                                    }
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ ucfirst($transaction->status ?? 'Completed') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $transaction->description ?? 'N/A' }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $walletTransactions->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bx bx-wallet fs-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">No Transactions Yet</h5>
                            <p class="text-muted">Your wallet transactions will appear here once you start making deposits or investments.</p>
                            <a href="{{ route('member.invest.create') }}" class="btn btn-primary mt-2">
                                <i class="bx bx-plus me-1"></i>
                                Make Your First Investment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Wallet Statistics -->
    <div class="row">
        <div class="col-md-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        <h6 class="mb-0">Monthly Overview</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="fw-semibold text-success">৳{{ number_format($user->total_earnings ?? 0, 2) }}</h4>
                                <p class="text-muted mb-0">Total Earned</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="fw-semibold text-danger">৳{{ number_format($totalWithdrawals, 2) }}</h4>
                                <p class="text-muted mb-0">Total Withdrawn</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        <h6 class="mb-0">Wallet Summary</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Available Balance:</span>
                                <span class="fw-semibold">৳{{ number_format(($user->balance ?? 0) + ($user->available_balance ?? 0), 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Pending Balance:</span>
                                <span class="fw-semibold">৳{{ number_format($user->pending_balance ?? 0, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Total Wallet:</span>
                                <span class="fw-semibold text-primary">৳{{ number_format($user->total_wallet_balance ?? 0, 2) }}</span>
                            </div>
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
    // Auto-refresh every 30 seconds
    setTimeout(function() {
        location.reload();
    }, 30000);
</script>
@endpush
