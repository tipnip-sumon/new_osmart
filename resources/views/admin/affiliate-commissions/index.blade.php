@extends('admin.layouts.app')

@section('title', 'Affiliate Commission Overview')

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
                                <i class="fas fa-chart-line text-success me-2"></i>
                                Affiliate Commission Overview
                            </h2>
                            <p class="text-muted mb-0">Monitor affiliate performance and commission analytics</p>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.affiliate-commissions.export') }}" class="btn btn-outline-success">
                                    <i class="fas fa-download me-1"></i>Export
                                </a>
                                <a href="{{ route('admin.affiliate-commissions.payout.preview') }}" class="btn btn-primary">
                                    <i class="fas fa-money-bill-wave me-1"></i>Process Payouts
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-hand-holding-usd fa-2x text-success mb-3"></i>
                    <h4 class="card-title text-success">${{ number_format($totalCommissions ?? 0, 2) }}</h4>
                    <p class="card-text text-muted">Total Commissions</p>
                    <small class="text-success">
                        <i class="fas fa-arrow-up"></i> {{ $commissionsGrowth ?? 0 }}% this month
                    </small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-clock fa-2x text-warning mb-3"></i>
                    <h4 class="card-title text-warning">${{ number_format($pendingCommissions ?? 0, 2) }}</h4>
                    <p class="card-text text-muted">Pending Commissions</p>
                    <small class="text-muted">{{ $pendingCount ?? 0 }} transactions</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-2x text-primary mb-3"></i>
                    <h4 class="card-title text-primary">${{ number_format($paidCommissions ?? 0, 2) }}</h4>
                    <p class="card-text text-muted">Paid Commissions</p>
                    <small class="text-muted">{{ $paidCount ?? 0 }} transactions</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-percentage fa-2x text-info mb-3"></i>
                    <h4 class="card-title text-info">{{ $averageCommissionRate ?? 0 }}%</h4>
                    <p class="card-text text-muted">Avg Commission Rate</p>
                    <small class="text-muted">Across all affiliates</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Commission Trends</h5>
                </div>
                <div class="card-body">
                    <canvas id="commissionChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Commission Status</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Performers --}}
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Earning Affiliates</h5>
                </div>
                <div class="card-body">
                    @if(isset($topAffiliates) && $topAffiliates->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($topAffiliates->take(5) as $affiliate)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $affiliate->name }}</h6>
                                    <small class="text-muted">{{ $affiliate->email }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success rounded-pill">
                                        ${{ number_format($affiliate->total_earned ?? 0, 2) }}
                                    </span>
                                    <small class="d-block text-muted">
                                        {{ $affiliate->commissions_count ?? 0 }} transactions
                                    </small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-chart-bar fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No affiliate data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Commission Activity</h5>
                </div>
                <div class="card-body">
                    @if(isset($recentCommissions) && $recentCommissions->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentCommissions->take(5) as $commission)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $commission->user->name ?? 'Unknown' }}</h6>
                                    <small class="text-muted">{{ $commission->earned_at ? $commission->earned_at->diffForHumans() : 'N/A' }}</small>
                                </div>
                                <p class="mb-1">
                                    <span class="badge bg-{{ $commission->status == 'approved' ? 'success' : ($commission->status == 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($commission->status ?? 'unknown') }}
                                    </span>
                                    ${{ number_format($commission->commission_amount ?? 0, 2) }}
                                </p>
                                <small class="text-muted">
                                    @if($commission->product)
                                        Product: {{ $commission->product->name }}
                                    @endif
                                </small>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-clock fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No recent activity</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Commission Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">All Affiliate Commissions</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <select class="form-select form-select-sm" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="paid">Paid</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($commissions) && $commissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped" id="commissionsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Affiliate</th>
                                        <th>Product</th>
                                        <th>Order Amount</th>
                                        <th>Commission</th>
                                        <th>Rate</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($commissions as $commission)
                                    <tr>
                                        <td>{{ $commission->id }}</td>
                                        <td>
                                            <div>
                                                <strong>{{ $commission->user->name ?? 'Unknown' }}</strong>
                                                <br><small class="text-muted">{{ $commission->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $commission->product->name ?? 'N/A' }}</td>
                                        <td>${{ number_format($commission->order_amount ?? 0, 2) }}</td>
                                        <td>${{ number_format($commission->commission_amount ?? 0, 2) }}</td>
                                        <td>{{ $commission->commission_rate ?? 0 }}%</td>
                                        <td>
                                            <span class="badge bg-{{ $commission->status == 'approved' ? 'success' : ($commission->status == 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($commission->status ?? 'unknown') }}
                                            </span>
                                        </td>
                                        <td>{{ $commission->earned_at ? $commission->earned_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.affiliate-commissions.show', $commission) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($commission->status == 'pending')
                                                <button type="button" class="btn btn-outline-success" onclick="updateStatus({{ $commission->id }}, 'approved')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" onclick="updateStatus({{ $commission->id }}, 'rejected')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($commissions, 'links'))
                            {{ $commissions->links() }}
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No commission data found</h5>
                            <p class="text-muted">Commissions will appear here as affiliates generate sales.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        return;
    }

    // Global Chart.js settings for version 2.x
    Chart.defaults.global.animation = false;
    Chart.defaults.global.responsive = false;
    Chart.defaults.global.maintainAspectRatio = false;

    // Commission Trends Chart
    const commissionCanvas = document.getElementById('commissionChart');
    if (commissionCanvas) {
        // Set fixed canvas size
        commissionCanvas.width = 800;
        commissionCanvas.height = 400;
        commissionCanvas.style.width = '100%';
        commissionCanvas.style.height = '400px';
        
        const ctx = commissionCanvas.getContext('2d');
        
        const chartData = {!! json_encode($chartData ?? [10, 20, 15, 25, 30, 35, 20, 40, 35, 45, 50, 55]) !!};
        const chartLabels = {!! json_encode($chartLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']) !!};
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Commission Amount ($)',
                    data: chartData,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    lineTension: 0,
                    pointRadius: 4,
                    pointHoverRadius: 4,
                    pointBackgroundColor: '#28a745',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                animation: {
                    duration: 0
                },
                hover: {
                    animationDuration: 0
                },
                responsiveAnimationDuration: 0,
                legend: {
                    display: true,
                    position: 'top'
                },
                scales: {
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: true,
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        ticks: {
                            beginAtZero: true,
                            stepSize: 10,
                            callback: function(value) {
                                return '$' + value;
                            }
                        },
                        gridLines: {
                            display: true,
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }]
                },
                tooltips: {
                    enabled: true,
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(tooltipItem) {
                            return 'Commission: $' + tooltipItem.yLabel;
                        }
                    }
                }
            }
        });
    }

    // Status Distribution Chart
    const statusCanvas = document.getElementById('statusChart');
    if (statusCanvas) {
        // Set fixed canvas size
        statusCanvas.width = 400;
        statusCanvas.height = 400;
        statusCanvas.style.width = '100%';
        statusCanvas.style.height = '400px';
        
        const ctx2 = statusCanvas.getContext('2d');
        
        const statusData = {!! json_encode($statusData ?? [5, 15, 2, 8]) !!};
        const statusLabels = {!! json_encode($statusLabels ?? ['Pending', 'Approved', 'Rejected', 'Paid']) !!};
        
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: [
                        '#ffc107',
                        '#28a745',
                        '#dc3545',
                        '#007bff'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                animation: {
                    duration: 0
                },
                hover: {
                    animationDuration: 0
                },
                responsiveAnimationDuration: 0,
                legend: {
                    position: 'bottom'
                },
                tooltips: {
                    enabled: true,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            const label = data.labels[tooltipItem.index] || '';
                            const value = data.datasets[0].data[tooltipItem.index] || 0;
                            const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        });
    }
});

// Status update function
function updateStatus(commissionId, status) {
    if (confirm('Are you sure you want to ' + status + ' this commission?')) {
        fetch(`/admin/affiliate-commissions/${commissionId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating status');
            }
        });
    }
}

// Initialize DataTables
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#commissionsTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'desc']]
        });
    }
});
</script>
@endpush
