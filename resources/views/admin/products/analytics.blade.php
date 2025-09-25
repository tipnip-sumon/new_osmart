@extends('admin.layouts.app')

@section('title', 'Product Analytics')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Product Analytics</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Analytics</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="fs-18 fw-semibold text-primary">{{ $totalProducts }}</div>
                        <div class="text-muted">Total Products</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="fs-18 fw-semibold text-success">{{ $activeProducts }}</div>
                        <div class="text-muted">Active Products</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="fs-18 fw-semibold text-danger">{{ $inactiveProducts }}</div>
                        <div class="text-muted">Inactive Products</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="fs-18 fw-semibold text-info">{{ $featuredProducts }}</div>
                        <div class="text-muted">Featured Products</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="fs-18 fw-semibold text-warning">{{ $outOfStockProducts }}</div>
                        <div class="text-muted">Out of Stock</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="fs-18 fw-semibold text-secondary">{{ $lowStockProducts }}</div>
                        <div class="text-muted">Low Stock</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Monthly Products Chart -->
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Products Created This Year</div>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyProductsChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            <!-- Status Distribution -->
            <div class="col-xl-4">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Product Status Distribution</div>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Categories -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Top Categories by Product Count</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Category</th>
                                        <th>Product Count</th>
                                        <th>Percentage</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topCategories as $index => $category)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="fw-semibold">
                                                {{ $category->category->name ?? 'Uncategorized' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-transparent">
                                                {{ $category->product_count }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $percentage = $totalProducts > 0 ? round(($category->product_count / $totalProducts) * 100, 1) : 0;
                                            @endphp
                                            <div class="progress" style="width: 100px; height: 6px;">
                                                <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <span class="text-muted small">{{ $percentage }}%</span>
                                        </td>
                                        <td>
                                            @if($category->category)
                                                <a href="{{ route('admin.products.index') }}?category={{ $category->category->name }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    View Products
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @if($topCategories->count() == 0)
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No data available</td>
                                    </tr>
                                    @endif
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
    // Monthly Products Chart
    const monthlyData = @json($monthlyProducts);
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    const monthlyLabels = monthNames;
    const monthlyValues = new Array(12).fill(0);
    
    monthlyData.forEach(item => {
        monthlyValues[item.month - 1] = item.count;
    });

    const monthlyCtx = document.getElementById('monthlyProductsChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Products Created',
                data: monthlyValues,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Inactive', 'Out of Stock'],
            datasets: [{
                data: [{{ $activeProducts }}, {{ $inactiveProducts }}, {{ $outOfStockProducts }}],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(255, 206, 86, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
