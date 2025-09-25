@extends('admin.layouts.app')

@section('title', 'Product Affiliate Performance')

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
                                <i class="fas fa-chart-bar text-info me-2"></i>
                                Product Affiliate Performance
                            </h2>
                            <p class="text-muted mb-0">
                                Detailed analytics for: <strong>{{ $product->name ?? 'Unknown Product' }}</strong>
                            </p>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.affiliate-links.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Back to List
                                </a>
                                <button type="button" class="btn btn-outline-info" onclick="copyAffiliateLink()">
                                    <i class="fas fa-link me-1"></i>Copy Link
                                </button>
                                <a href="{{ route('admin.affiliate-links.export') }}?product_id={{ $product->id ?? 0 }}" class="btn btn-primary">
                                    <i class="fas fa-download me-1"></i>Export Data
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Product Information --}}
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-box me-2"></i>Product Information
                    </h5>
                </div>
                <div class="card-body">
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
                    
                    @if($legacyImageUrl)
                        <img src="{{ $legacyImageUrl }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded mb-3"
                             style="max-height: 300px; width: 100%; object-fit: cover;"
                             onerror="this.src='{{ asset('assets/img/product/1.png') }}'">
                    @endif
                    <h5>{{ $product->name ?? 'Unknown Product' }}</h5>
                    <p class="text-muted">{{ $product->description ?? 'No description available' }}</p>
                    
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="h4 mb-0 text-success">${{ number_format($product->price ?? 0, 2) }}</div>
                                <small class="text-muted">Price</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-0 text-primary">{{ $product->affiliate_commission_rate ?? 0 }}%</div>
                            <small class="text-muted">Commission Rate</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <div class="badge bg-secondary">{{ $product->category->name ?? 'Uncategorized' }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">SKU</label>
                        <code>{{ $product->sku ?? 'N/A' }}</code>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Affiliate Link</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="affiliateLink" value="{{ url('/product/' . ($product->id ?? 0)) }}?affiliate_id=" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <small class="text-muted">Affiliates append their ID to this link</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Performance Statistics --}}
        <div class="col-lg-8">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-mouse-pointer fa-2x text-primary mb-2"></i>
                            <h4 class="card-title">{{ number_format($analytics['total_clicks'] ?? 0) }}</h4>
                            <p class="card-text text-muted small">Total Clicks</p>
                            <small class="text-{{ ($analytics['clicks_growth'] ?? 0) >= 0 ? 'success' : 'danger' }}">
                                <i class="fas fa-arrow-{{ ($analytics['clicks_growth'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                {{ abs($analytics['clicks_growth'] ?? 0) }}% this month
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-shopping-cart fa-2x text-success mb-2"></i>
                            <h4 class="card-title">{{ number_format($analytics['total_conversions'] ?? 0) }}</h4>
                            <p class="card-text text-muted small">Conversions</p>
                            <small class="text-info">
                                {{ $analytics['conversion_rate'] ?? 0 }}% rate
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-dollar-sign fa-2x text-warning mb-2"></i>
                            <h4 class="card-title">${{ number_format($analytics['total_revenue'] ?? 0, 0) }}</h4>
                            <p class="card-text text-muted small">Revenue</p>
                            <small class="text-success">
                                From affiliate sales
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-hand-holding-usd fa-2x text-info mb-2"></i>
                            <h4 class="card-title">${{ number_format($analytics['total_commissions'] ?? 0, 0) }}</h4>
                            <p class="card-text text-muted small">Commissions</p>
                            <small class="text-muted">
                                Paid to affiliates
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Performance Charts --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Performance Over Time</h5>
                </div>
                <div class="card-body">
                    <canvas id="performanceChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Affiliate Performance Table --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>Affiliate Performance for This Product
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($affiliatePerformance) && $affiliatePerformance->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Affiliate</th>
                                        <th>Clicks</th>
                                        <th>Conversions</th>
                                        <th>Conversion Rate</th>
                                        <th>Revenue Generated</th>
                                        <th>Commission Earned</th>
                                        <th>Last Click</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($affiliatePerformance as $performance)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $performance->name }}</strong>
                                                    <br><small class="text-muted">{{ $performance->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ number_format($performance->clicks_count ?? 0) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">{{ number_format($performance->conversions_count ?? 0) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $conversionRate = ($performance->clicks_count ?? 0) > 0 ? round((($performance->conversions_count ?? 0) / ($performance->clicks_count ?? 1)) * 100, 2) : 0;
                                            @endphp
                                            <span class="badge bg-{{ $conversionRate > 5 ? 'success' : ($conversionRate > 2 ? 'warning' : 'danger') }}">
                                                {{ $conversionRate }}%
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-success">${{ number_format($performance->total_revenue ?? 0, 2) }}</strong>
                                        </td>
                                        <td>
                                            <strong class="text-primary">${{ number_format($performance->total_commission ?? 0, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if($performance->last_click_at)
                                                {{ \Carbon\Carbon::parse($performance->last_click_at)->format('M d, Y') }}
                                                <br><small class="text-muted">{{ \Carbon\Carbon::parse($performance->last_click_at)->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">Never</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.affiliates.show', $performance->id) }}" class="btn btn-outline-primary" title="View Affiliate">
                                                    <i class="fas fa-user"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-info" onclick="viewClickHistory({{ $performance->id }})" title="Click History">
                                                    <i class="fas fa-history"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No affiliate activity yet</h5>
                            <p class="text-muted">This product hasn't been promoted by any affiliates yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="row">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>Recent Clicks
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($recentClicks) && $recentClicks->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentClicks->take(5) as $click)
                            <div class="list-group-item px-0">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $click->user->name ?? 'Unknown' }}</h6>
                                    <small class="text-muted">{{ $click->clicked_at ? $click->clicked_at->diffForHumans() : 'N/A' }}</small>
                                </div>
                                <p class="mb-1">
                                    <small class="text-muted">
                                        <i class="fas fa-globe me-1"></i>{{ $click->country ?? 'Unknown' }}
                                        <i class="fab fa-{{ strtolower($click->browser_name ?? 'question') }} ms-2 me-1"></i>{{ $click->browser_name ?? 'Unknown' }}
                                    </small>
                                </p>
                                @if($click->converted_at)
                                    <span class="badge bg-success">Converted</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-mouse-pointer fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No recent clicks</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>Recent Conversions
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($recentConversions) && $recentConversions->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentConversions->take(5) as $conversion)
                            <div class="list-group-item px-0">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $conversion->user->name ?? 'Unknown' }}</h6>
                                    <small class="text-muted">{{ $conversion->earned_at ? $conversion->earned_at->diffForHumans() : 'N/A' }}</small>
                                </div>
                                <p class="mb-1">
                                    <span class="text-success fw-bold">${{ number_format($conversion->commission_amount ?? 0, 2) }}</span>
                                    <small class="text-muted">commission from ${{ number_format($conversion->order_amount ?? 0, 2) }} sale</small>
                                </p>
                                <span class="badge bg-{{ $conversion->status == 'approved' ? 'success' : ($conversion->status == 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($conversion->status ?? 'unknown') }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-shopping-cart fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No recent conversions</p>
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
        labels: {!! json_encode($chartLabels ?? []) !!},
        datasets: [
            {
                label: 'Clicks',
                data: {!! json_encode($clicksData ?? []) !!},
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                yAxisID: 'y'
            },
            {
                label: 'Conversions',
                data: {!! json_encode($conversionsData ?? []) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                yAxisID: 'y'
            },
            {
                label: 'Revenue ($)',
                data: {!! json_encode($revenueData ?? []) !!},
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

// Copy affiliate link function
function copyAffiliateLink() {
    copyToClipboard();
}

// Copy to clipboard
function copyToClipboard() {
    const linkInput = document.getElementById('affiliateLink');
    linkInput.select();
    linkInput.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    // Show success notification
    showNotification('Affiliate link copied to clipboard!', 'success');
}

// View click history
function viewClickHistory(affiliateId) {
    window.open(`/admin/affiliate-clicks?affiliate_id=${affiliateId}&product_id={{ $product->id ?? 0 }}`, '_blank');
}

// Show notification
function showNotification(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}
</script>
@endpush
