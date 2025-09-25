@extends('admin.layouts.app')

@section('title', 'Commission Overview')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
        <div>
            <nav>
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Commission Overview</li>
                </ol>
            </nav>
            <h1 class="page-title fw-medium fs-18 mb-0">Commission Overview</h1>
        </div>
        <div class="btn-list">
            <a href="{{ route('admin.commissions.export') }}" class="btn btn-success-light btn-wave me-2">
                <i class="bx bx-download me-1"></i> Export All
            </a>
            <button class="btn btn-primary-light btn-wave me-0" onclick="refreshData()">
                <i class="bx bx-refresh me-1"></i> Refresh
            </button>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="row">
        <!-- Commission Statistics -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Commission Statistics</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden bg-primary-transparent">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-primary">
                                                <i class="bx bx-wallet fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Total Commissions</p>
                                                    <h4 class="fw-semibold mt-1">৳{{ number_format($data['total_commissions'], 2) }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden bg-success-transparent">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-success">
                                                <i class="bx bx-check-circle fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Paid Commissions</p>
                                                    <h4 class="fw-semibold mt-1">৳{{ number_format($data['total_paid'], 2) }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden bg-warning-transparent">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-warning">
                                                <i class="bx bx-time fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Pending Commissions</p>
                                                    <h4 class="fw-semibold mt-1">৳{{ number_format($data['total_pending'], 2) }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden bg-danger-transparent">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-danger">
                                                <i class="bx bx-x-circle fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Cancelled Commissions</p>
                                                    <h4 class="fw-semibold mt-1">৳{{ number_format($data['total_cancelled'], 2) }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Comparison -->
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Monthly Comparison</div>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-3 border-end">
                                <h4 class="fw-semibold text-success">৳{{ number_format($data['this_month_commissions'], 2) }}</h4>
                                <p class="text-muted mb-0">This Month</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3">
                                <h4 class="fw-semibold text-primary">৳{{ number_format($data['last_month_commissions'], 2) }}</h4>
                                <p class="text-muted mb-0">Last Month</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        @php
                            $growth = $data['last_month_commissions'] > 0 
                                ? (($data['this_month_commissions'] - $data['last_month_commissions']) / $data['last_month_commissions']) * 100 
                                : 0;
                        @endphp
                        <span class="badge bg-{{ $growth >= 0 ? 'success' : 'danger' }}-transparent">
                            <i class="bx bx-{{ $growth >= 0 ? 'trending-up' : 'trending-down' }} me-1"></i>
                            {{ number_format(abs($growth), 1) }}% {{ $growth >= 0 ? 'Growth' : 'Decline' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commission Types -->
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Commission Types Distribution</div>
                </div>
                <div class="card-body">
                    @if($data['commission_types']->count() > 0)
                        @foreach($data['commission_types'] as $type)
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <span class="fw-semibold">{{ ucfirst($type->commission_type) }}</span>
                                    <br>
                                    <small class="text-muted">{{ $type->count }} transactions</small>
                                </div>
                                <div class="text-end">
                                    <h6 class="fw-semibold mb-0">${{ number_format($type->total, 2) }}</h6>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-4">No commission data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Commissions -->
        <div class="col-xl-8">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title">Recent Commissions</div>
                    <a href="{{ route('admin.commissions.payouts') }}" class="btn btn-sm btn-primary-light">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['recent_commissions'] as $commission)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm avatar-rounded">
                                                    {{ substr($commission->user->name ?? 'N/A', 0, 1) }}
                                                </span>
                                                <div class="ms-2">
                                                    <p class="mb-0 fw-semibold">{{ $commission->user->name ?? 'N/A' }}</p>
                                                    <p class="mb-0 text-muted fs-12">{{ $commission->user->email ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-transparent">{{ ucfirst($commission->commission_type) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-success">${{ number_format($commission->commission_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'approved' => 'info',
                                                    'paid' => 'success',
                                                    'cancelled' => 'danger'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$commission->status] ?? 'secondary' }}">
                                                {{ ucfirst($commission->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $commission->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No recent commissions found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Earners -->
        <div class="col-xl-4">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Top Earners</div>
                </div>
                <div class="card-body">
                    @forelse($data['top_earners'] as $index => $earner)
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-sm avatar-rounded bg-primary me-2">
                                    #{{ $index + 1 }}
                                </span>
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $earner->user->name ?? 'N/A' }}</p>
                                    <p class="mb-0 text-muted fs-12">{{ $earner->user->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="text-end">
                                <h6 class="fw-semibold mb-0 text-success">${{ number_format($earner->total_earned, 2) }}</h6>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center py-4">No top earners data available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Quick Actions</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <a href="{{ route('admin.commissions.direct') }}" class="btn btn-primary-light w-100 mb-3">
                                <i class="bx bx-user-plus me-2"></i> Direct Commissions
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <a href="{{ route('admin.commissions.binary') }}" class="btn btn-info-light w-100 mb-3">
                                <i class="bx bx-network-chart me-2"></i> Binary Commissions
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <a href="{{ route('admin.commissions.matching') }}" class="btn btn-success-light w-100 mb-3">
                                <i class="bx bx-trophy me-2"></i> Matching Bonus
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <a href="{{ route('admin.commissions.leadership') }}" class="btn btn-warning-light w-100 mb-3">
                                <i class="bx bx-crown me-2"></i> Leadership Bonus
                            </a>
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
function refreshData() {
    window.location.reload();
}

// Auto-refresh data every 5 minutes
setInterval(() => {
    window.location.reload();
}, 300000);
</script>
@endpush
