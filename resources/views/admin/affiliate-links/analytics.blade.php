@extends('admin.layouts.app')

@section('content')
<div class="page">
    <!-- Start::app-content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h1 class="page-title fw-medium fs-18 mb-2">Affiliate Links Analytics</h1>
                    <div class="">
                        <nav>
                            <ol class="breadcrumb breadcrumb-example1 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.affiliate-links.index') }}">Affiliate Links</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Analytics</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="btn-list">
                    <a href="{{ route('admin.affiliate-links.index') }}" class="btn btn-outline-primary">
                        <i class="ri-arrow-left-line me-2"></i>Back to Links
                    </a>
                </div>
            </div>
            <!-- Page Header Close -->

            <!-- Filters -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.affiliate-links.analytics') }}" class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Time Period</label>
                                    <select name="period" class="form-select" onchange="this.form.submit()">
                                        <option value="7" {{ $period == '7' ? 'selected' : '' }}>Last 7 days</option>
                                        <option value="30" {{ $period == '30' ? 'selected' : '' }}>Last 30 days</option>
                                        <option value="90" {{ $period == '90' ? 'selected' : '' }}>Last 3 months</option>
                                        <option value="365" {{ $period == '365' ? 'selected' : '' }}>Last year</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Product Filter</label>
                                    <select name="product_id" class="form-select" onchange="this.form.submit()">
                                        <option value="">All Products</option>
                                        @foreach($top_products as $product)
                                            <option value="{{ $product->id }}" {{ $product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-search-line me-2"></i>Apply Filters
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="row mb-4">
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="avatar avatar-md avatar-rounded bg-primary">
                                        <i class="ri-mouse-line fs-16"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="fw-semibold mb-0">Total Clicks</h6>
                                    <span class="fs-12 text-muted">Last {{ $period }} days</span>
                                </div>
                                <div class="text-end">
                                    <h4 class="fw-semibold mb-0">{{ number_format($total_clicks) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="avatar avatar-md avatar-rounded bg-success">
                                        <i class="ri-product-hunt-line fs-16"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="fw-semibold mb-0">Active Products</h6>
                                    <span class="fs-12 text-muted">With clicks</span>
                                </div>
                                <div class="text-end">
                                    <h4 class="fw-semibold mb-0">{{ number_format($total_products) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="avatar avatar-md avatar-rounded bg-warning">
                                        <i class="ri-line-chart-line fs-16"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="fw-semibold mb-0">Avg. Daily Clicks</h6>
                                    <span class="fs-12 text-muted">Per day</span>
                                </div>
                                <div class="text-end">
                                    <h4 class="fw-semibold mb-0">{{ number_format($total_clicks / max($period, 1)) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="avatar avatar-md avatar-rounded bg-info">
                                        <i class="ri-calendar-line fs-16"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="fw-semibold mb-0">Period</h6>
                                    <span class="fs-12 text-muted">Days analyzed</span>
                                </div>
                                <div class="text-end">
                                    <h4 class="fw-semibold mb-0">{{ $period }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <!-- Clicks Over Time Chart -->
                <div class="col-xl-8">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Clicks Over Time</div>
                        </div>
                        <div class="card-body">
                            <div style="position: relative; height: 300px;">
                                <canvas id="clicksChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Browser Distribution -->
                <div class="col-xl-4">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Browser Distribution</div>
                        </div>
                        <div class="card-body">
                            <div style="position: relative; height: 300px;">
                                <canvas id="browserChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products Table -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Top Products by Clicks</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Rank</th>
                                            <th>Product</th>
                                            <th>Clicks</th>
                                            <th>Performance</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($top_products as $index => $product)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-primary-transparent">{{ $index + 1 }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @php
                                                            $legacyImageUrl = '';
                                                            
                                                            // First try images array
                                                            if (isset($product->images) && $product->images) {
                                                                $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                                                                if (is_array($images) && !empty($images)) {
                                                                    $image = $images[0]; // Get first image
                                                                    
                                                                    // Handle complex nested structure first
                                                                    if (is_array($image) && isset($image['sizes']['medium']['storage_url'])) {
                                                                        // New complex structure - use medium size storage_url
                                                                        $legacyImageUrl = $image['sizes']['medium']['storage_url'];
                                                                    } elseif (is_array($image) && isset($image['sizes']['original']['storage_url'])) {
                                                                        // Fallback to original if medium not available
                                                                        $legacyImageUrl = $image['sizes']['original']['storage_url'];
                                                                    } elseif (is_array($image) && isset($image['sizes']['large']['storage_url'])) {
                                                                        // Fallback to large if original not available
                                                                        $legacyImageUrl = $image['sizes']['large']['storage_url'];
                                                                    } elseif (is_array($image) && isset($image['urls']['medium'])) {
                                                                        // Legacy complex URL structure - use medium size
                                                                        $legacyImageUrl = $image['urls']['medium'];
                                                                    } elseif (is_array($image) && isset($image['urls']['original'])) {
                                                                        // Legacy fallback to original if medium not available
                                                                        $legacyImageUrl = $image['urls']['original'];
                                                                    } elseif (is_array($image) && isset($image['url']) && is_string($image['url'])) {
                                                                        $legacyImageUrl = $image['url'];
                                                                    } elseif (is_array($image) && isset($image['path']) && is_string($image['path'])) {
                                                                        $legacyImageUrl = asset('storage/' . $image['path']);
                                                                    } elseif (is_string($image)) {
                                                                        // Simple string path
                                                                        $legacyImageUrl = asset('storage/' . $image);
                                                                    }
                                                                }
                                                            }
                                                            
                                                            // Fallback to image accessor
                                                            if (empty($legacyImageUrl)) {
                                                                $productImage = $product->image;
                                                                if ($productImage && $productImage !== 'products/product1.jpg') {
                                                                    $legacyImageUrl = str_starts_with($productImage, 'http') ? $productImage : asset('storage/' . $productImage);
                                                                } else {
                                                                    $legacyImageUrl = asset('assets/img/product/1.png'); // Default for flash sale
                                                                }
                                                            }
                                                        @endphp
                                                        
                                                        <img src="{{ $legacyImageUrl }}" 
                                                             alt="{{ $product->name }}" 
                                                             class="avatar avatar-sm me-2"
                                                             style="width: 32px; height: 32px; object-fit: cover;"
                                                             onerror="this.src='{{ asset('assets/img/product/1.png') }}'">
                                                        
                                                        <div>
                                                            <h6 class="mb-0">{{ $product->name }}</h6>
                                                            <small class="text-muted">ID: {{ $product->id }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="fw-semibold">{{ number_format($product->clicks_count) }}</span>
                                                </td>
                                                <td>
                                                    <div class="progress" style="height: 6px;">
                                                        @php
                                                            $maxClicks = $top_products->max('clicks_count');
                                                            $percentage = $maxClicks > 0 ? ($product->clicks_count / $maxClicks) * 100 : 0;
                                                        @endphp
                                                        <div class="progress-bar bg-primary" style="width: {{ $percentage }}%"></div>
                                                    </div>
                                                    <small class="text-muted">{{ number_format($percentage, 1) }}%</small>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.affiliate-links.show', $product->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="ri-eye-line"></i> View Details
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="ri-inbox-line fs-48 d-block mb-2"></i>
                                                        No click data available for the selected period.
                                                    </div>
                                                </td>
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
    </div>
    <!-- End::app-content -->
</div>

<!-- Chart.js CDN - using UMD version instead of ES6 modules -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    // Check if Chart.js loaded
    if (typeof Chart === 'undefined') {
        console.error('Chart.js failed to load');
        document.getElementById('clicksChart').innerHTML = '<p class="text-center text-danger">Chart library failed to load</p>';
        document.getElementById('browserChart').innerHTML = '<p class="text-center text-danger">Chart library failed to load</p>';
        return;
    } else {
        console.log('Chart.js loaded successfully');
    }

    // Debug the data first
    const clicksData = @json($clicks_over_time);
    const browserData = @json($browser_stats);
    
    console.log('Clicks Data:', clicksData);
    console.log('Browser Data:', browserData);

    // Clicks Over Time Chart
    if (clicksData && clicksData.length > 0) {
        const clicksLabels = clicksData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        const clicksValues = clicksData.map(item => parseInt(item.clicks) || 0);

        console.log('Processed clicks labels:', clicksLabels);
        console.log('Processed clicks values:', clicksValues);

        const clicksCtx = document.getElementById('clicksChart');
        if (clicksCtx) {
            new Chart(clicksCtx, {
                type: 'line',
                data: {
                    labels: clicksLabels,
                    datasets: [{
                        label: 'Clicks',
                        data: clicksValues,
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgb(54, 162, 235)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(54, 162, 235)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index'
                        }
                    }
                }
            });
            console.log('Clicks chart created successfully');
        } else {
            console.error('Could not find clicksChart canvas element');
        }
    } else {
        // Show no data message
        const clicksContainer = document.getElementById('clicksChart').parentElement;
        clicksContainer.innerHTML = '<div class="text-center text-muted py-4"><i class="ri-bar-chart-line fs-48 d-block mb-2"></i><p>No click data available for the selected period</p></div>';
    }

    // Browser Distribution Chart
    if (browserData && browserData.length > 0) {
        const browserLabels = browserData.map(item => item.browser);
        const browserValues = browserData.map(item => parseInt(item.count) || 0);

        console.log('Browser labels:', browserLabels);
        console.log('Browser values:', browserValues);

        const browserCtx = document.getElementById('browserChart');
        if (browserCtx) {
            new Chart(browserCtx, {
                type: 'doughnut',
                data: {
                    labels: browserLabels,
                    datasets: [{
                        data: browserValues,
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF',
                            '#FF9F40'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
            console.log('Browser chart created successfully');
        } else {
            console.error('Could not find browserChart canvas element');
        }
    } else {
        // Show no data message
        const browserContainer = document.getElementById('browserChart').parentElement;
        browserContainer.innerHTML = '<div class="text-center text-muted py-4"><i class="ri-pie-chart-line fs-48 d-block mb-2"></i><p>No browser data available for the selected period</p></div>';
    }
});
</script>
@endsection
