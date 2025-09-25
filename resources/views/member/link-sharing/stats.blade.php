@extends('member.layouts.app')

@section('title', 'Link Sharing Performance Stats')

@section('content')
<div class="main-content">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h1 class="page-title fw-semibold fs-18 mb-0">Performance Statistics</h1>
            <div class="">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.link-sharing.dashboard') }}">Link Sharing</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Stats</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="ms-auto pageheader-btn">
            <a href="{{ route('member.link-sharing.dashboard') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Back to Dashboard
            </a>
            <a href="{{ route('member.link-sharing.history') }}" class="btn btn-info">
                <i class="bx bx-history"></i> Sharing History
            </a>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Overview Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card custom-card bg-primary-gradient text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bx bx-calendar fs-24"></i>
                        </div>
                        <div class="flex-fill">
                            <h6 class="mb-1 text-white-50">Today's Stats</h6>
                            <h4 class="fw-semibold mb-0">{{ $todayStats->shares_count ?? 0 }} Shares</h4>
                            <small class="text-white-50">৳ {{ number_format($todayStats->earnings_amount ?? 0, 2) }} earned</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card custom-card bg-success-gradient text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bx bx-calendar-week fs-24"></i>
                        </div>
                        <div class="flex-fill">
                            <h6 class="mb-1 text-white-50">This Week</h6>
                            <h4 class="fw-semibold mb-0">{{ $weeklyStats['total_shares'] ?? 0 }} Shares</h4>
                            <small class="text-white-50">৳ {{ number_format($weeklyStats['total_earnings'] ?? 0, 2) }} earned</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card custom-card bg-warning-gradient text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bx bx-calendar-alt fs-24"></i>
                        </div>
                        <div class="flex-fill">
                            <h6 class="mb-1 text-white-50">This Month</h6>
                            <h4 class="fw-semibold mb-0">{{ $monthlyStats['total_shares'] ?? 0 }} Shares</h4>
                            <small class="text-white-50">৳ {{ number_format($monthlyStats['total_earnings'] ?? 0, 2) }} earned</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card custom-card bg-info-gradient text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bx bx-infinite fs-24"></i>
                        </div>
                        <div class="flex-fill">
                            <h6 class="mb-1 text-white-50">All Time</h6>
                            <h4 class="fw-semibold mb-0">{{ $allTimeStats['total_shares'] ?? 0 }} Shares</h4>
                            <small class="text-white-50">৳ {{ number_format($allTimeStats['total_earnings'] ?? 0, 2) }} earned</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Daily Performance Chart -->
        <div class="col-xl-8">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Daily Performance (Last 7 Days)</div>
                </div>
                <div class="card-body">
                    <canvas id="dailyPerformanceChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Platform Distribution -->
        <div class="col-xl-4">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Platform Distribution</div>
                </div>
                <div class="card-body">
                    <canvas id="platformChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Stats Tables -->
    <div class="row">
        <!-- Top Performing Products -->
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Top Performing Products</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Shares</th>
                                    <th>Clicks</th>
                                    <th>Earnings</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProducts as $product)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ Str::limit($product->product_name ?? 'Unknown', 20) }}</span>
                                    </td>
                                    <td><span class="badge bg-primary">{{ $product->share_count }}</span></td>
                                    <td><span class="badge bg-info">{{ $product->total_clicks }}</span></td>
                                    <td><span class="text-success fw-semibold">৳ {{ number_format($product->total_earnings, 2) }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Performance Metrics</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <h4 class="text-primary mb-1">
                                    {{ number_format($performanceMetrics['avg_clicks_per_share'] ?? 0, 1) }}
                                </h4>
                                <p class="text-muted mb-0">Avg Clicks per Share</p>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <h4 class="text-success mb-1">
                                    {{ number_format($performanceMetrics['avg_earnings_per_share'] ?? 0, 2) }}
                                </h4>
                                <p class="text-muted mb-0">Avg Earnings per Share</p>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <h4 class="text-warning mb-1">
                                    {{ number_format($performanceMetrics['click_through_rate'] ?? 0, 1) }}%
                                </h4>
                                <p class="text-muted mb-0">Click Through Rate</p>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <h4 class="text-info mb-1">
                                    {{ number_format($performanceMetrics['conversion_rate'] ?? 0, 1) }}%
                                </h4>
                                <p class="text-muted mb-0">Earning Conversion Rate</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="text-center">
                                <h4 class="text-danger mb-1">
                                    {{ $performanceMetrics['best_day'] ?? 'N/A' }}
                                </h4>
                                <p class="text-muted mb-0">Best Performing Day</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Breakdown -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Monthly Breakdown</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Total Shares</th>
                                    <th>Total Clicks</th>
                                    <th>Unique Clicks</th>
                                    <th>Total Earnings</th>
                                    <th>Avg Daily Shares</th>
                                    <th>Click Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthlyBreakdown as $month)
                                <tr>
                                    <td class="fw-semibold">{{ $month->month_name }}</td>
                                    <td><span class="badge bg-primary">{{ $month->total_shares }}</span></td>
                                    <td><span class="badge bg-info">{{ $month->total_clicks }}</span></td>
                                    <td><span class="badge bg-success">{{ $month->unique_clicks }}</span></td>
                                    <td><span class="text-success fw-semibold">৳ {{ number_format($month->total_earnings, 2) }}</span></td>
                                    <td>{{ number_format($month->avg_daily_shares, 1) }}</td>
                                    <td>{{ number_format($month->click_rate, 1) }}%</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No monthly data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Daily Performance Chart
    const dailyCtx = document.getElementById('dailyPerformanceChart').getContext('2d');
    const dailyChart = new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyChartData['labels'] ?? []) !!},
            datasets: [{
                label: 'Shares',
                data: {!! json_encode($dailyChartData['shares'] ?? []) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }, {
                label: 'Clicks',
                data: {!! json_encode($dailyChartData['clicks'] ?? []) !!},
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.1
            }, {
                label: 'Earnings',
                data: {!! json_encode($dailyChartData['earnings'] ?? []) !!},
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Platform Distribution Chart
    const platformCtx = document.getElementById('platformChart').getContext('2d');
    const platformChart = new Chart(platformCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($platformChartData['labels'] ?? []) !!},
            datasets: [{
                data: {!! json_encode($platformChartData['data'] ?? []) !!},
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endsection
