@extends('member.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-line me-3 fs-4"></i>
                            <div>
                                <h4 class="mb-0">Sales Report</h4>
                                <p class="mb-0 opacity-75">Track your sales performance and order history</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-sm" onclick="exportReport('pdf')">
                                <i class="fas fa-file-pdf me-1"></i> PDF
                            </button>
                            <button class="btn btn-light btn-sm" onclick="exportReport('excel')">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Summary Cards -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary mx-auto mb-3">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="fw-bold text-primary">{{ $salesData->sum('orders') }}</h3>
                    <p class="text-muted mb-0">Total Orders</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-success bg-opacity-10 text-success mx-auto mb-3">
                        <i class="fas fa-taka-sign"></i>
                    </div>
                    <h3 class="fw-bold text-success">৳{{ number_format($salesData->sum('total'), 2) }}</h3>
                    <p class="text-muted mb-0">Total Sales</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-info bg-opacity-10 text-info mx-auto mb-3">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3 class="fw-bold text-info">৳{{ $salesData->count() > 0 ? number_format($salesData->sum('total') / $salesData->sum('orders'), 2) : '0.00' }}</h3>
                    <p class="text-muted mb-0">Average Order</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning mx-auto mb-3">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <h3 class="fw-bold text-warning">{{ $salesData->count() }}</h3>
                    <p class="text-muted mb-0">Active Days</p>
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
                            <label for="status" class="form-label">Order Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('member.reports.sales') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Data Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-table text-primary me-2"></i>
                        Daily Sales Breakdown
                    </h5>
                    <small class="text-muted">Showing {{ $salesData->count() }} entries</small>
                </div>
                <div class="card-body">
                    @if($salesData->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Orders Count</th>
                                        <th>Total Sales</th>
                                        <th>Average Order</th>
                                        <th>Commission Earned</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salesData as $data)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ \Carbon\Carbon::parse($data->date)->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($data->date)->format('l') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $data->orders }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-success">৳{{ number_format($data->total, 2) }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">৳{{ number_format($data->total / $data->orders, 2) }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $commission = $data->total * 0.05; // Assuming 5% commission
                                                @endphp
                                                <span class="fw-semibold text-info">৳{{ number_format($commission, 2) }}</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-outline-primary btn-sm" onclick="viewDayDetails('{{ $data->date }}')">
                                                    <i class="fas fa-eye"></i> Details
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td class="fw-bold">Total</td>
                                        <td><span class="badge bg-primary">{{ $salesData->sum('orders') }}</span></td>
                                        <td><span class="fw-bold text-success">৳{{ number_format($salesData->sum('total'), 2) }}</span></td>
                                        <td><span class="fw-bold">৳{{ $salesData->count() > 0 ? number_format($salesData->sum('total') / $salesData->sum('orders'), 2) : '0.00' }}</span></td>
                                        <td><span class="fw-bold text-info">৳{{ number_format($salesData->sum('total') * 0.05, 2) }}</span></td>
                                        <td>-</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $salesData->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line text-muted mb-3" style="font-size: 4rem;"></i>
                            <h5 class="text-muted">No Sales Data Found</h5>
                            <p class="text-muted">Start making sales to see your performance here</p>
                            <a href="{{ route('member.products.index') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> View Products
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sales Details Modal -->
<div class="modal fade" id="salesDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Daily Sales Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="salesDetailsContent">
                <!-- Dynamic content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// View day details
function viewDayDetails(date) {
    const modal = new bootstrap.Modal(document.getElementById('salesDetailsModal'));
    document.getElementById('salesDetailsContent').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    modal.show();
    
    // Simulate API call - replace with actual implementation
    setTimeout(() => {
        document.getElementById('salesDetailsContent').innerHTML = `
            <div class="alert alert-info">
                <h6>Sales for ${date}</h6>
                <p>Detailed breakdown of orders and transactions will be displayed here.</p>
                <small class="text-muted">Feature implementation in progress.</small>
            </div>
        `;
    }, 1000);
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
        <div class="toast-body bg-primary text-white">
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

// Chart initialization (if you want to add charts)
document.addEventListener('DOMContentLoaded', function() {
    // Chart code can be added here
    console.log('Sales report loaded');
});
</script>
@endpush

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

.table-hover tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.05);
}

.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-1px);
}

.badge {
    font-size: 0.75rem;
    padding: 0.4em 0.65em;
}

.toast {
    z-index: 1055;
}
</style>
@endpush
@endsection
