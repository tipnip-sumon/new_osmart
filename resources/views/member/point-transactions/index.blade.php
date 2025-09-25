@extends('member.layouts.app')

@section('title', 'Point Transactions History')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="fw-bold text-primary mb-1">
                                <i class="fas fa-history me-2"></i>Point Transactions History
                            </h4>
                            <p class="text-muted mb-0">Complete record of all your point activities</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="d-flex justify-content-end gap-3">
                                <div class="text-center">
                                    <h5 class="text-success fw-bold mb-0">{{ number_format($user->active_points) }}</h5>
                                    <small class="text-muted">Active Points</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="text-warning fw-bold mb-0">{{ number_format($user->reserve_points) }}</h5>
                                    <small class="text-muted">Reserve Points</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="text-info fw-bold mb-0">{{ number_format($user->total_points_earned) }}</h5>
                                    <small class="text-muted">Total Earned</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body py-3">
                    <form method="GET" action="{{ route('member.point-transactions.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <select name="type" class="form-select form-select-sm">
                                <option value="">All Types</option>
                                <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>Credit</option>
                                <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>Debit</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="reference_type" class="form-select form-select-sm">
                                <option value="">All Categories</option>
                                <option value="product_purchase" {{ request('reference_type') === 'product_purchase' ? 'selected' : '' }}>Product Purchase</option>
                                <option value="point_activation" {{ request('reference_type') === 'point_activation' ? 'selected' : '' }}>Point Activation</option>
                                <option value="binary_matching" {{ request('reference_type') === 'binary_matching' ? 'selected' : '' }}>Binary Matching</option>
                                <option value="package_activation" {{ request('reference_type') === 'package_activation' ? 'selected' : '' }}>Package Activation</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}" placeholder="From Date">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}" placeholder="To Date">
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-filter me-1"></i>Filter
                                </button>
                                <a href="{{ route('member.point-transactions.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-times me-1"></i>Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="fas fa-list me-2"></i>Transaction Records
                                @if($transactions->total() > 0)
                                    <span class="badge bg-primary ms-2">{{ $transactions->total() }} Records</span>
                                @endif
                            </h6>
                        </div>
                        <div class="col-auto">
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-download me-1"></i>Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('member.point-transactions.export', ['format' => 'csv'] + request()->all()) }}">
                                        <i class="fas fa-file-csv me-2"></i>Export CSV
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('member.point-transactions.export', ['format' => 'pdf'] + request()->all()) }}">
                                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 ps-4">Date & Time</th>
                                        <th class="border-0">Type</th>
                                        <th class="border-0">Amount</th>
                                        <th class="border-0">Description</th>
                                        <th class="border-0">Category</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0 pe-4">Reference</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium text-dark">{{ $transaction->created_at->format('M d, Y') }}</span>
                                                <small class="text-muted">{{ $transaction->created_at->format('h:i A') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($transaction->type === 'credit')
                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="fas fa-plus me-1"></i>Credit
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">
                                                    <i class="fas fa-minus me-1"></i>Debit
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold {{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                                {{ $transaction->type === 'credit' ? '+' : '-' }}{{ number_format($transaction->amount) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-wrap" style="max-width: 250px;">
                                                {{ $transaction->description }}
                                            </div>
                                        </td>
                                        <td>
                                            @switch($transaction->reference_type)
                                                @case('product_purchase')
                                                    <span class="badge bg-primary-subtle text-primary">
                                                        <i class="fas fa-shopping-cart me-1"></i>Purchase
                                                    </span>
                                                    @break
                                                @case('point_activation')
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="fas fa-bolt me-1"></i>Activation
                                                    </span>
                                                    @break
                                                @case('binary_matching')
                                                    <span class="badge bg-warning-subtle text-warning">
                                                        <i class="fas fa-balance-scale me-1"></i>Binary
                                                    </span>
                                                    @break
                                                @case('package_activation')
                                                    <span class="badge bg-info-subtle text-info">
                                                        <i class="fas fa-box me-1"></i>Package
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary-subtle text-secondary">
                                                        <i class="fas fa-question me-1"></i>{{ ucfirst($transaction->reference_type) }}
                                                    </span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch($transaction->status)
                                                @case('completed')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Completed
                                                    </span>
                                                    @break
                                                @case('pending')
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock me-1"></i>Pending
                                                    </span>
                                                    @break
                                                @case('failed')
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times me-1"></i>Failed
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ ucfirst($transaction->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td class="pe-4">
                                            @if($transaction->reference_id)
                                                <span class="badge bg-light text-dark border">
                                                    ID: {{ $transaction->reference_id }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-history text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                            <h5 class="text-muted mb-2">No Transactions Found</h5>
                            <p class="text-muted mb-4">You don't have any point transactions yet.</p>
                            <a href="{{ route('member.direct-point-purchase.index') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Purchase Points
                            </a>
                        </div>
                    @endif
                </div>
                
                @if($transactions->hasPages())
                <div class="card-footer bg-light border-0">
                    <div class="row align-items-center">
                        <div class="col-sm-12 col-md-5">
                            <div class="text-muted">
                                Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} results
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="d-flex justify-content-end">
                                {{ $transactions->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Transaction Details Modal -->
<div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionModalLabel">Transaction Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="transactionDetails">
                    <!-- Details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.badge {
    font-weight: 500;
}
.table > :not(caption) > * > * {
    padding: 0.75rem;
}
.text-wrap {
    word-wrap: break-word;
}
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-submit form on filter change
    $('select[name="type"], select[name="reference_type"]').change(function() {
        $(this).closest('form').submit();
    });
    
    // Date range validation
    $('input[name="from_date"], input[name="to_date"]').change(function() {
        var fromDate = $('input[name="from_date"]').val();
        var toDate = $('input[name="to_date"]').val();
        
        if (fromDate && toDate && fromDate > toDate) {
            alert('From date cannot be later than To date');
            $(this).val('');
        }
    });
});
</script>
@endsection
