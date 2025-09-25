@extends('admin.layouts.app')

@section('title', 'Package Link Sharing Statistics')

@section('content')
<div class="main-content">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h1 class="page-title fw-semibold fs-18 mb-0">Package Link Sharing Statistics</h1>
            <div class="">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.package-link-sharing.index') }}">Package Link Sharing</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Statistics</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="ms-auto pageheader-btn">
            <a href="{{ route('admin.package-link-sharing.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Back to List
            </a>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="d-block mb-1 text-muted">Total Packages</span>
                            <h4 class="fw-semibold mb-1">{{ $stats['total_packages'] }}</h4>
                        </div>
                        <div>
                            <span class="avatar avatar-md avatar-rounded bg-primary-transparent">
                                <i class="bx bx-package fs-16"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="d-block mb-1 text-muted">Active Packages</span>
                            <h4 class="fw-semibold mb-1 text-success">{{ $stats['active_packages'] }}</h4>
                        </div>
                        <div>
                            <span class="avatar avatar-md avatar-rounded bg-success-transparent">
                                <i class="bx bx-check-circle fs-16"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="d-block mb-1 text-muted">Shares Today</span>
                            <h4 class="fw-semibold mb-1 text-info">{{ $stats['total_shares_today'] }}</h4>
                        </div>
                        <div>
                            <span class="avatar avatar-md avatar-rounded bg-info-transparent">
                                <i class="bx bx-share fs-16"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="d-block mb-1 text-muted">Earnings Today</span>
                            <h4 class="fw-semibold mb-1 text-warning">৳{{ number_format($stats['total_earnings_today'], 2) }}</h4>
                        </div>
                        <div>
                            <span class="avatar avatar-md avatar-rounded bg-warning-transparent">
                                <i class="bx bx-money fs-16"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Performers -->
        <div class="col-xl-8">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Top Performers Today</div>
                </div>
                <div class="card-body">
                    @if($stats['top_performers']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>User</th>
                                        <th>Shares</th>
                                        <th>Clicks</th>
                                        <th>Earnings</th>
                                        <th>Performance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['top_performers'] as $index => $performer)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $index < 3 ? ['primary', 'success', 'warning'][$index] : 'light' }}">
                                                #{{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm avatar-rounded me-2">
                                                    <img src="{{ $performer->user->image ?? asset('admin-assets/images/faces/default.jpg') }}" alt="user">
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $performer->user->name }}</div>
                                                    <div class="text-muted fs-12">{{ $performer->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-transparent">{{ $performer->shares_count }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-transparent">{{ $performer->clicks_count }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-success">৳{{ number_format($performer->earnings_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $clickRate = $performer->shares_count > 0 ? ($performer->clicks_count / $performer->shares_count) : 0;
                                            @endphp
                                            <div class="progress progress-xs">
                                                <div class="progress-bar bg-success" style="width: {{ min($clickRate * 50, 100) }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ number_format($clickRate, 2) }} clicks/share</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bx bx-user-x" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">No activity today</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Package Performance -->
        <div class="col-xl-4">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Package Efficiency</div>
                </div>
                <div class="card-body">
                    @php
                        $packages = \App\Models\PackageLinkSharingSetting::where('is_active', true)->get();
                    @endphp
                    
                    @forelse($packages as $package)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-semibold mb-0">{{ ucfirst($package->package_name) }}</h6>
                            <span class="badge bg-primary-transparent">
                                ৳{{ number_format($package->click_reward_amount, 2) }}
                            </span>
                        </div>
                        <div class="progress progress-sm mb-2">
                            @php
                                $efficiency = ($package->daily_earning_limit > 0) ? 
                                    (($package->daily_share_limit * $package->click_reward_amount) / $package->daily_earning_limit) * 100 : 100;
                                $efficiency = min($efficiency, 100);
                            @endphp
                            <div class="progress-bar bg-{{ $efficiency > 80 ? 'success' : ($efficiency > 60 ? 'warning' : 'danger') }}" 
                                 style="width: {{ $efficiency }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between text-muted fs-12">
                            <span>{{ $package->daily_share_limit }} shares/day</span>
                            <span>{{ number_format($efficiency, 1) }}% efficiency</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="bx bx-package" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="text-muted mt-2">No active packages</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card custom-card mt-3">
                <div class="card-header">
                    <div class="card-title">Quick Actions</div>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.package-link-sharing.create') }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-plus"></i> Add New Package
                        </a>
                        <button class="btn btn-info btn-sm" onclick="refreshStats()">
                            <i class="bx bx-refresh"></i> Refresh Statistics
                        </button>
                        <a href="{{ route('admin.package-link-sharing.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bx bx-list-ul"></i> Manage Packages
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics (Placeholder for future enhancements) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Analytics Dashboard</div>
                    <div class="ms-auto">
                        <span class="badge bg-warning">Coming Soon</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="bx bx-bar-chart-alt-2" style="font-size: 4rem; color: #ccc;"></i>
                        <h5 class="mt-3 mb-2">Advanced Analytics Coming Soon</h5>
                        <p class="text-muted">
                            Detailed charts, performance metrics, user behavior analysis, and more comprehensive reporting features will be available in the next update.
                        </p>
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="bx bx-line-chart text-primary" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0 text-muted">Performance Trends</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="bx bx-pie-chart text-success" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0 text-muted">Package Distribution</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="bx bx-bar-chart text-warning" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0 text-muted">Earnings Analytics</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="bx bx-trending-up text-info" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0 text-muted">User Engagement</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
function refreshStats() {
    // Show loading state
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="bx bx-loader-alt spin"></i> Refreshing...';
    btn.disabled = true;
    
    // Reload the page after a short delay
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}
</script>
@endsection
@endsection
