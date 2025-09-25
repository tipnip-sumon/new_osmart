@extends('admin.layouts.app')

@section('title', 'User Analytics')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">User Analytics</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Analytics</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md bg-primary">
                                    <i class="ti ti-users fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <h6 class="mb-0">Total Users</h6>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="h4 fw-semibold mb-0">{{ number_format($analytics['total_users']) }}</span>
                                    </div>
                                    <div id="total-users-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md bg-success">
                                    <i class="ti ti-user-check fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <h6 class="mb-0">Active Users</h6>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="h4 fw-semibold mb-0">{{ number_format($analytics['active_users']) }}</span>
                                    </div>
                                    <div id="active-users-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md bg-warning">
                                    <i class="ti ti-clock fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <h6 class="mb-0">New This Month</h6>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="h4 fw-semibold mb-0">{{ number_format($analytics['new_users_this_month']) }}</span>
                                    </div>
                                    <div>
                                        <span class="badge bg-success-transparent">+{{ $analytics['growth_rate'] }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md bg-danger">
                                    <i class="ti ti-user-x fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <h6 class="mb-0">Suspended</h6>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="h4 fw-semibold mb-0">{{ number_format($analytics['suspended_users']) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- User Status Distribution -->
            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">User Status Distribution</div>
                    </div>
                    <div class="card-body">
                        <div id="user-status-chart"></div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <div class="text-center">
                                    <p class="mb-1 text-muted">Active</p>
                                    <h5 class="fw-semibold text-success">{{ number_format($analytics['active_users']) }}</h5>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <p class="mb-1 text-muted">Inactive</p>
                                    <h5 class="fw-semibold text-warning">{{ number_format($analytics['inactive_users']) }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rank Distribution -->
            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Rank Distribution</div>
                    </div>
                    <div class="card-body">
                        <div id="rank-distribution-chart"></div>
                        <div class="mt-3">
                            @foreach($analytics['user_ranks'] as $rank => $count)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold">{{ ucfirst($rank) }}</span>
                                    <span class="badge bg-primary">{{ number_format($count) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Top Sponsors -->
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Top Sponsors</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Sponsor Name</th>
                                        <th>Total Referrals</th>
                                        <th>Performance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analytics['top_sponsors'] as $index => $sponsor)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary-transparent">{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        <img src="{{ asset('admin-assets/images/faces/default-avatar.png') }}" alt="avatar">
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 fw-semibold">{{ $sponsor['name'] }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">{{ $sponsor['referrals'] }}</span>
                                            </td>
                                            <td>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar bg-primary" style="width: {{ ($sponsor['referrals'] / 50) * 100 }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-xl-4">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Recent Activity</div>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item border-0 p-0 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="avatar avatar-sm bg-success-transparent text-success">
                                            <i class="ti ti-user-plus fs-12"></i>
                                        </span>
                                    </div>
                                    <div class="flex-fill">
                                        <p class="mb-0 fw-semibold">New user registered</p>
                                        <span class="text-muted fs-11">2 minutes ago</span>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item border-0 p-0 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="avatar avatar-sm bg-primary-transparent text-primary">
                                            <i class="ti ti-crown fs-12"></i>
                                        </span>
                                    </div>
                                    <div class="flex-fill">
                                        <p class="mb-0 fw-semibold">Rank upgrade to Gold</p>
                                        <span class="text-muted fs-11">15 minutes ago</span>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item border-0 p-0 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="avatar avatar-sm bg-warning-transparent text-warning">
                                            <i class="ti ti-user-x fs-12"></i>
                                        </span>
                                    </div>
                                    <div class="flex-fill">
                                        <p class="mb-0 fw-semibold">User account suspended</p>
                                        <span class="text-muted fs-11">1 hour ago</span>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item border-0 p-0 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="avatar avatar-sm bg-info-transparent text-info">
                                            <i class="ti ti-mail fs-12"></i>
                                        </span>
                                    </div>
                                    <div class="flex-fill">
                                        <p class="mb-0 fw-semibold">Welcome email sent</p>
                                        <span class="text-muted fs-11">2 hours ago</span>
                                    </div>
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
<script src="{{ asset('admin-assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
    // User Status Chart
    var statusOptions = {
        series: [{{ $analytics['active_users'] }}, {{ $analytics['inactive_users'] }}, {{ $analytics['suspended_users'] }}, {{ $analytics['pending_users'] }}],
        chart: {
            type: 'donut',
            height: 300
        },
        labels: ['Active', 'Inactive', 'Suspended', 'Pending'],
        colors: ['#28a745', '#ffc107', '#dc3545', '#6c757d'],
        legend: {
            position: 'bottom'
        }
    };
    var statusChart = new ApexCharts(document.querySelector("#user-status-chart"), statusOptions);
    statusChart.render();

    // Rank Distribution Chart
    var rankOptions = {
        series: [{
            data: [{{ implode(',', array_values($analytics['user_ranks'])) }}]
        }],
        chart: {
            type: 'bar',
            height: 300
        },
        xaxis: {
            categories: {!! json_encode(array_keys($analytics['user_ranks'])) !!}
        },
        colors: ['#667eea'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
            },
        },
        dataLabels: {
            enabled: false
        }
    };
    var rankChart = new ApexCharts(document.querySelector("#rank-distribution-chart"), rankOptions);
    rankChart.render();
</script>
@endpush

@push('styles')
<style>
    .avatar {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .progress-sm {
        height: 0.5rem;
    }
    
    .list-group-item {
        background: transparent;
    }
    
    .badge {
        font-size: 0.75rem;
    }
</style>
@endpush
