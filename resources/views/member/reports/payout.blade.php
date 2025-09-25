@extends('member.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient-warning text-dark">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-money-bill-wave me-3 fs-4"></i>
                            <div>
                                <h4 class="mb-0">Payout Report</h4>
                                <p class="mb-0 opacity-75">Track your payout history and earnings distribution</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-dark btn-sm" onclick="exportReport('pdf')">
                                <i class="fas fa-file-pdf me-1"></i> PDF
                            </button>
                            <button class="btn btn-dark btn-sm" onclick="exportReport('excel')">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payout Summary Cards -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-success bg-opacity-10 text-success mx-auto mb-3">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <h3 class="fw-bold text-success">৳{{ number_format($payoutData->sum('amount'), 2) }}</h3>
                    <p class="text-muted mb-0">Total Payouts</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary mx-auto mb-3">
                        <i class="fas fa-calendar-month"></i>
                    </div>
                    @php
                        $thisMonthPayouts = $payoutData->where('created_at', '>=', now()->startOfMonth())->sum('amount');
                    @endphp
                    <h3 class="fw-bold text-primary">৳{{ number_format($thisMonthPayouts, 2) }}</h3>
                    <p class="text-muted mb-0">This Month</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-info bg-opacity-10 text-info mx-auto mb-3">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3 class="fw-bold text-info">৳{{ $payoutData->count() > 0 ? number_format($payoutData->sum('amount') / $payoutData->count(), 2) : '0.00' }}</h3>
                    <p class="text-muted mb-0">Average Payout</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning mx-auto mb-3">
                        <i class="fas fa-clock"></i>
                    </div>
                    @php
                        $pendingPayouts = \App\Models\Transaction::where('user_id', $user->id)
                            ->where('type', 'payout')
                            ->where('status', 'pending')
                            ->sum('amount');
                    @endphp
                    <h3 class="fw-bold text-warning">৳{{ number_format($pendingPayouts, 2) }}</h3>
                    <p class="text-muted mb-0">Pending</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payout Status Breakdown -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie text-warning me-2"></i>
                        Payout Status Distribution
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $statusGroups = $payoutData->groupBy('status');
                        $totalAmount = $payoutData->sum('amount');
                    @endphp
                    @if($statusGroups->count() > 0)
                        @foreach(['completed', 'pending', 'cancelled', 'failed'] as $status)
                            @php
                                $statusData = $statusGroups->get($status, collect());
                                $statusAmount = $statusData->sum('amount');
                                $percentage = $totalAmount > 0 ? ($statusAmount / $totalAmount) * 100 : 0;
                            @endphp
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="status-icon me-3">
                                        @switch($status)
                                            @case('completed')
                                                <i class="fas fa-check-circle text-success"></i>
                                                @break
                                            @case('pending')
                                                <i class="fas fa-clock text-warning"></i>
                                                @break
                                            @case('cancelled')
                                                <i class="fas fa-times-circle text-danger"></i>
                                                @break
                                            @case('failed')
                                                <i class="fas fa-exclamation-triangle text-danger"></i>
                                                @break
                                        @endswitch
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ ucfirst($status) }}</div>
                                        <small class="text-muted">{{ $statusData->count() }} transactions</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">৳{{ number_format($statusAmount, 2) }}</div>
                                    <small class="text-muted">{{ number_format($percentage, 1) }}%</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-chart-pie text-muted mb-2"></i>
                            <p class="text-muted mb-0">No payout data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-line text-info me-2"></i>
                        Recent Payout Trend
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="payoutTrendChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" 
                                   value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" 
                                   value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Payout Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-warning me-2">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('member.reports.payout') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Payout Data Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-table text-warning me-2"></i>
                        Payout History
                    </h5>
                    <small class="text-muted">Showing {{ $payoutData->count() }} entries</small>
                </div>
                <div class="card-body">
                    @if($payoutData->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Processing Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payoutData as $payout)
                                        <tr>
                                            <td>
                                                <span class="fw-semibold">#{{ $payout->transaction_id ?? 'PO' . $payout->id }}</span>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $payout->created_at->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $payout->created_at->format('g:i A') }}</small>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">৳{{ number_format($payout->amount, 2) }}</span>
                                                @if($payout->fee && $payout->fee > 0)
                                                    <br><small class="text-muted">Fee: ৳{{ number_format($payout->fee, 2) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $payout->payment_method ? ucfirst(str_replace('_', ' ', $payout->payment_method)) : 'Bank Transfer' }}
                                                </span>
                                            </td>
                                            <td>
                                                @switch($payout->status)
                                                    @case('completed')
                                                        <span class="badge bg-success">Completed</span>
                                                        @break
                                                    @case('pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                        @break
                                                    @case('failed')
                                                        <span class="badge bg-danger">Failed</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ ucfirst($payout->status) }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($payout->processed_at)
                                                    <span class="text-success">{{ $payout->created_at->diffInHours($payout->processed_at) }}h</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" onclick="viewPayoutDetails({{ $payout->id }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if($payout->status === 'completed')
                                                        <button class="btn btn-outline-success" onclick="downloadReceipt({{ $payout->id }})">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td class="fw-bold">Total</td>
                                        <td>-</td>
                                        <td><span class="fw-bold text-success">৳{{ number_format($payoutData->sum('amount'), 2) }}</span></td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $payoutData->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-money-bill-wave text-muted mb-3" style="font-size: 4rem;"></i>
                            <h5 class="text-muted">No Payout History Found</h5>
                            <p class="text-muted">Start earning commissions to see your payouts here</p>
                            <a href="{{ route('member.commissions') }}" class="btn btn-warning">
                                <i class="fas fa-chart-line me-1"></i> View Commissions
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payout Details Modal -->
<div class="modal fade" id="payoutDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payout Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="payoutDetailsContent">
                <!-- Dynamic content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Payout Trend Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('payoutTrendChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Payouts (৳)',
                data: [5000, 8000, 12000, 15000, 18000, 22000],
                backgroundColor: 'rgba(255, 193, 7, 0.8)',
                borderColor: '#ffc107',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '৳' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});

// View payout details
function viewPayoutDetails(payoutId) {
    const modal = new bootstrap.Modal(document.getElementById('payoutDetailsModal'));
    document.getElementById('payoutDetailsContent').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    modal.show();
    
    // Simulate API call - replace with actual implementation
    setTimeout(() => {
        document.getElementById('payoutDetailsContent').innerHTML = `
            <div class="alert alert-info">
                <h6>Payout ID: ${payoutId}</h6>
                <p>Detailed payout information including breakdown, fees, and processing details will be displayed here.</p>
                <small class="text-muted">Feature implementation in progress.</small>
            </div>
        `;
    }, 1000);
}

// Download receipt
function downloadReceipt(payoutId) {
    // Implement receipt download
    alert('Receipt download for payout ID: ' + payoutId + ' will be implemented');
}

// Export report
function exportReport(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('export', format);
    
    // Create download link
    const url = window.location.pathname + '?' + params.toString();
    window.open(url, '_blank');
    
    // Show notification
    const toast = document.createElement('div');
    toast.className = 'toast position-fixed top-0 end-0 m-3';
    toast.innerHTML = `
        <div class="toast-body bg-warning text-dark">
            <i class="fas fa-download me-2"></i>
            Downloading ${format.toUpperCase()} report...
        </div>
    `;
    document.body.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    setTimeout(() => {
        document.body.removeChild(toast);
    }, 3000);
}
</script>
@endpush

@push('styles')
<style>
.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.icon-box {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.status-icon {
    width: 30px;
    text-align: center;
    font-size: 1.2rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(255, 193, 7, 0.05);
}

.form-control:focus,
.form-select:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    border: none;
    color: #212529;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e0a800 0%, #dc6900 100%);
    transform: translateY(-1px);
    color: #212529;
}

.badge {
    font-size: 0.75rem;
    padding: 0.4em 0.65em;
}

.toast {
    z-index: 1055;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}
</style>
@endpush
@endsection
