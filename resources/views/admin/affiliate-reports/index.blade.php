@extends('admin.layouts.app')

@section('title', 'Affiliate Overview & Analytics')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="card-title mb-0">
                                <i class="fas fa-chart-pie text-info me-2"></i>
                                Affiliate Overview & Analytics
                            </h2>
                            <p class="text-muted mb-0">Comprehensive affiliate performance dashboard and insights</p>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.affiliate-reports.detailed') }}" class="btn btn-outline-info">
                                    <i class="fas fa-chart-line me-1"></i>Detailed Report
                                </a>
                                <a href="{{ route('admin.affiliate-reports.export') }}" class="btn btn-primary">
                                    <i class="fas fa-download me-1"></i>Export Data
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Key Performance Indicators --}}
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-primary mb-3"></i>
                    <h4 class="card-title">{{ $overallStats['total_affiliates'] ?? 0 }}</h4>
                    <p class="card-text text-muted small">Total Affiliates</p>
                    <small class="text-success">
                        <i class="fas fa-user-plus"></i> {{ $overallStats['active_affiliates'] ?? 0 }} active
                    </small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-mouse-pointer fa-2x text-success mb-3"></i>
                    <h4 class="card-title">{{ number_format($overallStats['total_clicks'] ?? 0) }}</h4>
                    <p class="card-text text-muted small">Total Clicks</p>
                    <small class="text-info">
                        <i class="fas fa-clock"></i> This period
                    </small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-shopping-cart fa-2x text-warning mb-3"></i>
                    <h4 class="card-title">{{ number_format($overallStats['total_conversions'] ?? 0) }}</h4>
                    <p class="card-text text-muted small">Conversions</p>
                    <small class="text-success">
                        <i class="fas fa-percentage"></i> {{ $overallStats['conversion_rate'] ?? 0 }}% rate
                    </small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-dollar-sign fa-2x text-success mb-3"></i>
                    <h4 class="card-title">${{ number_format($overallStats['total_revenue'] ?? 0, 0) }}</h4>
                    <p class="card-text text-muted small">Total Revenue</p>
                    <small class="text-muted">
                        Generated via affiliates
                    </small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-hand-holding-usd fa-2x text-info mb-3"></i>
                    <h4 class="card-title">${{ number_format($overallStats['total_commissions'] ?? 0, 0) }}</h4>
                    <p class="card-text text-muted small">Commissions Paid</p>
                    <small class="text-success">
                        Avg: ${{ $overallStats['avg_commission'] ?? 0 }}
                    </small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-percentage fa-2x text-primary mb-3"></i>
                    <h4 class="card-title">{{ $overallStats['conversion_rate'] ?? 0 }}%</h4>
                    <p class="card-text text-muted small">Conversion Rate</p>
                    <small class="text-{{ ($overallStats['conversion_rate'] ?? 0) > 5 ? 'success' : 'warning' }}">
                        <i class="fas fa-{{ ($overallStats['conversion_rate'] ?? 0) > 5 ? 'arrow-up' : 'arrow-down' }}"></i>
                        {{ ($overallStats['conversion_rate'] ?? 0) > 5 ? 'Great' : 'Needs work' }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- Performance Charts --}}
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Performance Over Time
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="performanceChart" height="80"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.affiliates.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-users me-2"></i>Manage Affiliates
                        </a>
                        <a href="{{ route('admin.affiliate-commissions.index') }}" class="btn btn-outline-success">
                            <i class="fas fa-dollar-sign me-2"></i>View Commissions
                        </a>
                        <a href="{{ route('admin.affiliate-clicks.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-mouse-pointer me-2"></i>Track Clicks
                        </a>
                        <a href="{{ route('admin.affiliate-links.index') }}" class="btn btn-outline-warning">
                            <i class="fas fa-link me-2"></i>Shared Links
                        </a>
                        <hr>
                        <a href="{{ route('admin.affiliate-commissions.payout.preview') }}" class="btn btn-success">
                            <i class="fas fa-money-bill-wave me-2"></i>Process Payouts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Performers Section --}}
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trophy me-2 text-warning"></i>Top Performing Affiliates
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($topAffiliates) && $topAffiliates->count() > 0)
                        @foreach($topAffiliates->take(5) as $index => $affiliate)
                        <div class="d-flex align-items-center mb-3 {{ $loop->last ? '' : 'border-bottom pb-3' }}">
                            <div class="flex-shrink-0">
                                <div class="bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'info') }} text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <strong>{{ $index + 1 }}</strong>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $affiliate->name }}</h6>
                                <small class="text-muted">{{ $affiliate->email }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success">${{ number_format($affiliate->total_earned ?? 0, 2) }}</div>
                                <small class="text-muted">{{ $affiliate->clicks_count ?? 0 }} clicks</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-users fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No affiliate performance data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-box me-2 text-primary"></i>Top Performing Products
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($topProducts) && $topProducts->count() > 0)
                        @foreach($topProducts->take(5) as $index => $product)
                        <div class="d-flex align-items-center mb-3 {{ $loop->last ? '' : 'border-bottom pb-3' }}">
                            <div class="flex-shrink-0">
                                <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-box"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $product->name }}</h6>
                                <small class="text-muted">{{ $product->clicks_count ?? 0 }} clicks</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success">${{ number_format($product->total_revenue ?? 0, 2) }}</div>
                                <small class="text-muted">{{ $product->commissions_count ?? 0 }} sales</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-box fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No product performance data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Recent Affiliate Activity
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($performanceOverTime) && $performanceOverTime->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Clicks</th>
                                        <th>Unique Affiliates</th>
                                        <th>Conversions</th>
                                        <th>Conversion Rate</th>
                                        <th>Revenue</th>
                                        <th>Commissions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($performanceOverTime->take(10) as $day)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</td>
                                        <td>{{ number_format($day->clicks) }}</td>
                                        <td>{{ number_format($day->unique_affiliates) }}</td>
                                        <td>{{ number_format($day->conversions) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $day->conversion_rate > 5 ? 'success' : ($day->conversion_rate > 2 ? 'warning' : 'danger') }}">
                                                {{ $day->conversion_rate }}%
                                            </span>
                                        </td>
                                        <td>${{ number_format($day->revenue, 2) }}</td>
                                        <td>${{ number_format($day->commission_amount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No performance data available</h5>
                            <p class="text-muted">Activity will appear here as affiliates start generating traffic and sales.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Performance Over Time Chart
const performanceCtx = document.getElementById('performanceChart').getContext('2d');
new Chart(performanceCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(collect($performanceOverTime ?? [])->pluck('date')->map(function($date) { return \Carbon\Carbon::parse($date)->format('M d'); })->toArray()) !!},
        datasets: [
            {
                label: 'Clicks',
                data: {!! json_encode(collect($performanceOverTime ?? [])->pluck('clicks')->toArray()) !!},
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                yAxisID: 'y'
            },
            {
                label: 'Conversions',
                data: {!! json_encode(collect($performanceOverTime ?? [])->pluck('conversions')->toArray()) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                yAxisID: 'y'
            },
            {
                label: 'Revenue ($)',
                data: {!! json_encode(collect($performanceOverTime ?? [])->pluck('revenue')->toArray()) !!},
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        scales: {
            x: {
                display: true,
                title: {
                    display: true,
                    text: 'Date'
                }
            },
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Clicks / Conversions'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Revenue ($)'
                },
                grid: {
                    drawOnChartArea: false,
                },
            }
        }
    }
});
</script>
@endpush
