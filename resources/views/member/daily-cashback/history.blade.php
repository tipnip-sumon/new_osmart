@extends('member.layouts.app')

@section('title', 'Cashback History')

@section('styles')
<style>
    .filter-card {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 1px solid #bae6fd;
        border-radius: 12px;
    }
    
    .history-item {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        margin-bottom: 15px;
    }
    
    .history-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .history-item.paid { border-left-color: #10b981; }
    .history-item.pending { border-left-color: #f59e0b; }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .status-badge.paid {
        background: #dcfce7;
        color: #15803d;
    }
    
    .status-badge.pending {
        background: #fef3c7;
        color: #d97706;
    }
    
    .amount-display {
        font-size: 1.25rem;
        font-weight: 600;
    }
    
    .amount-display.paid { color: #059669; }
    .amount-display.pending { color: #d97706; }
    
    .date-badge {
        background: #f1f5f9;
        color: #64748b;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <div class="container-fluid my-4">
        
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
            <div>
                <nav>
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item">
                            <a href="{{ route('member.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('member.daily-cashback.dashboard') }}">Daily Cashback</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">History</li>
                    </ol>
                </nav>
                <p class="fw-semibold fs-15 mb-0">Complete history of your daily cashback earnings</p>
            </div>
            <div class="btn-list">
                <a href="{{ route('member.daily-cashback.dashboard') }}" class="btn btn-primary-light btn-wave">
                    <i class="ri-dashboard-line me-1"></i> Dashboard
                </a>
                <a href="{{ route('member.daily-cashback.pending') }}" class="btn btn-warning-light btn-wave">
                    <i class="ri-time-line me-1"></i> Pending
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card filter-card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('member.daily-cashback.history') }}" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Package</label>
                                <select name="plan_id" class="form-select">
                                    <option value="">All Packages</option>
                                    @foreach($userPlans as $plan)
                                        <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date From</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date To</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="w-100">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ri-search-line me-1"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Summary -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-success-transparent">
                    <div class="card-body text-center">
                        <i class="ri-check-line fs-24 text-success mb-2"></i>
                        <h4 class="text-success">{{ $cashbacks->where('status', 'paid')->count() }}</h4>
                        <p class="text-muted mb-0">Paid Cashbacks</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning-transparent">
                    <div class="card-body text-center">
                        <i class="ri-time-line fs-24 text-warning mb-2"></i>
                        <h4 class="text-warning">{{ $cashbacks->where('status', 'pending')->count() }}</h4>
                        <p class="text-muted mb-0">Pending Cashbacks</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary-transparent">
                    <div class="card-body text-center">
                        <i class="ri-money-dollar-circle-line fs-24 text-primary mb-2"></i>
                        <h4 class="text-primary">৳{{ number_format($cashbacks->where('status', 'paid')->sum('cashback_amount'), 2) }}</h4>
                        <p class="text-muted mb-0">Total Earned</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-secondary-transparent">
                    <div class="card-body text-center">
                        <i class="ri-calendar-line fs-24 text-secondary mb-2"></i>
                        <h4 class="text-secondary">{{ $cashbacks->count() }}</h4>
                        <p class="text-muted mb-0">Total Records</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cashback History -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="ri-history-line me-2"></i>Cashback History
                            <span class="badge bg-primary-transparent ms-2">{{ $cashbacks->total() }} records</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($cashbacks->count() > 0)
                            <!-- Desktop View -->
                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Package</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Paid Date</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cashbacks as $cashback)
                                            <tr>
                                                <td>
                                                    <div class="date-badge">
                                                        {{ $cashback->cashback_date->format('M j, Y') }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-semibold">{{ $cashback->plan->name }}</span>
                                                        <small class="text-muted">{{ $cashback->plan->daily_cashback_min }}-{{ $cashback->plan->daily_cashback_max }} TK range</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="amount-display {{ $cashback->status }}">
                                                        ৳{{ number_format($cashback->cashback_amount, 2) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="status-badge {{ $cashback->status }}">
                                                        @if($cashback->status == 'paid')
                                                            <i class="ri-check-line"></i> Paid
                                                        @else
                                                            <i class="ri-time-line"></i> Pending
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($cashback->paid_at)
                                                        <small class="text-muted">{{ $cashback->paid_at->format('M j, Y H:i') }}</small>
                                                    @else
                                                        <small class="text-muted">-</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ Str::limit($cashback->remarks, 30) }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile View -->
                            <div class="d-block d-md-none">
                                @foreach($cashbacks as $cashback)
                                    <div class="history-item {{ $cashback->status }} p-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="status-badge {{ $cashback->status }}">
                                                @if($cashback->status == 'paid')
                                                    <i class="ri-check-line"></i> Paid
                                                @else
                                                    <i class="ri-time-line"></i> Pending
                                                @endif
                                            </span>
                                            <div class="date-badge">{{ $cashback->cashback_date->format('M j') }}</div>
                                        </div>
                                        
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <h6 class="mb-0">{{ $cashback->plan->name }}</h6>
                                            <span class="amount-display {{ $cashback->status }}">
                                                ৳{{ number_format($cashback->cashback_amount, 2) }}
                                            </span>
                                        </div>
                                        
                                        <small class="text-muted">{{ Str::limit($cashback->remarks, 40) }}</small>
                                        
                                        @if($cashback->paid_at)
                                            <div class="mt-2">
                                                <small class="text-success">
                                                    <i class="ri-check-line"></i> Paid on {{ $cashback->paid_at->format('M j, Y') }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $cashbacks->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <img src="{{ asset('admin-assets/images/media/media-67.svg') }}" alt="" class="w-25">
                                <h5 class="mt-3">No cashback records found</h5>
                                <p class="text-muted">Try adjusting your filters or check back after your cashback packages are active.</p>
                                <a href="{{ route('member.daily-cashback.dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-submit form when filters change (optional)
    $('.form-select').on('change', function() {
        // Uncomment if you want auto-submit
        // $(this).closest('form').submit();
    });
});
</script>
@endsection