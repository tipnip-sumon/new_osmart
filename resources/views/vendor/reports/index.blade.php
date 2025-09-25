@extends('admin.layouts.app')

@section('title', 'Sales Reports')

@section('content')
<!-- Start::page-header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div>
        <h2 class="page-title fw-semibold fs-18 mb-0">Sales Reports</h2>
        <p class="fw-medium fs-13 text-muted mb-0">Track your sales performance and analytics</p>
    </div>
    <div class="btn-list">
        <a href="{{ route('vendor.reports.export-sales') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" class="btn btn-success">
            <i class="ri-download-line me-1"></i>Export CSV
        </a>
    </div>
</div>
<!-- End::page-header -->

<!-- Date Range Filter -->
<div class="row mb-4">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-body">
                <form method="GET" class="row align-items-end">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date', $startDate) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date', $endDate) }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Apply Filter</button>
                        <a href="{{ route('vendor.reports.index') }}" class="btn btn-light">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Sales Overview -->
<div class="row">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="avatar avatar-md bg-primary">
                            <i class="ti ti-currency-dollar fs-16"></i>
                        </span>
                    </div>
                    <div class="flex-fill ms-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div>
                                <p class="text-muted mb-0">Total Sales</p>
                                <h4 class="fw-semibold mt-1">${{ number_format($salesData['total_sales'], 2) }}</h4>
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
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="avatar avatar-md bg-success">
                            <i class="ti ti-shopping-cart fs-16"></i>
                        </span>
                    </div>
                    <div class="flex-fill ms-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div>
                                <p class="text-muted mb-0">Total Orders</p>
                                <h4 class="fw-semibold mt-1">{{ number_format($salesData['total_orders']) }}</h4>
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
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="avatar avatar-md bg-warning">
                            <i class="ti ti-package fs-16"></i>
                        </span>
                    </div>
                    <div class="flex-fill ms-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div>
                                <p class="text-muted mb-0">Products Sold</p>
                                <h4 class="fw-semibold mt-1">{{ number_format($salesData['total_products_sold']) }}</h4>
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
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="avatar avatar-md bg-info">
                            <i class="ti ti-chart-line fs-16"></i>
                        </span>
                    </div>
                    <div class="flex-fill ms-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div>
                                <p class="text-muted mb-0">Average Order</p>
                                <h4 class="fw-semibold mt-1">${{ number_format($salesData['average_order_value'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Monthly Sales Chart -->
    <div class="col-xl-8">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Monthly Sales Trend</div>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Selling Products -->
    <div class="col-xl-4">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Top Selling Products</div>
            </div>
            <div class="card-body">
                @if($topProducts->count() > 0)
                    @foreach($topProducts as $product)
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="flex-fill">
                            <p class="mb-0 fw-semibold">{{ $product->name }}</p>
                            <p class="text-muted mb-0 fs-12">SKU: {{ $product->sku }}</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary">{{ $product->total_sold }} sold</span>
                            <div class="text-muted fs-12">${{ number_format($product->total_revenue, 2) }}</div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <p class="text-muted">No sales data available for the selected period.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Product Performance Table -->
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Product Performance</div>
            </div>
            <div class="card-body">
                @if($productPerformance->count() > 0)
                    <div class="table-responsive">
                        <table class="table text-nowrap table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Units Sold</th>
                                    <th>Revenue</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productPerformance as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->sku }}</td>
                                    <td>${{ number_format($product->price, 2) }}</td>
                                    <td>{{ $product->stock_quantity }}</td>
                                    <td>{{ $product->total_sold ?? 0 }}</td>
                                    <td>${{ number_format($product->total_revenue ?? 0, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $product->status == 'active' ? 'success' : ($product->status == 'inactive' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ri-bar-chart-line fs-48 text-muted"></i>
                        <h5 class="mt-3">No Products Found</h5>
                        <p class="text-muted">Add some products to see performance data.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlySales->pluck('month')) !!},
            datasets: [{
                label: 'Sales ($)',
                data: {!! json_encode($monthlySales->pluck('sales')) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Sales: $' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
