@extends('admin.layouts.app')

@section('title', 'Affiliate Shared Links')

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
                                <i class="fas fa-link text-warning me-2"></i>
                                Affiliate Shared Links
                            </h2>
                            <p class="text-muted mb-0">Monitor product links shared by affiliates and their performance</p>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.affiliate-links.analytics') }}" class="btn btn-outline-info">
                                    <i class="fas fa-chart-bar me-1"></i>Analytics
                                </a>
                                <a href="{{ route('admin.affiliate-links.export') }}" class="btn btn-primary">
                                    <i class="fas fa-download me-1"></i>Export
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
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-box fa-2x text-primary mb-3"></i>
                    <h4 class="card-title">{{ number_format($totalProducts ?? 0) }}</h4>
                    <p class="card-text text-muted">Products Shared</p>
                    <small class="text-success">
                        <i class="fas fa-users"></i> {{ $activeAffiliates ?? 0 }} affiliates
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-mouse-pointer fa-2x text-success mb-3"></i>
                    <h4 class="card-title">{{ number_format($totalClicks ?? 0) }}</h4>
                    <p class="card-text text-muted">Total Clicks</p>
                    <small class="text-info">
                        <i class="fas fa-arrow-up"></i> {{ $clicksGrowth ?? 0 }}% this month
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-shopping-cart fa-2x text-warning mb-3"></i>
                    <h4 class="card-title">{{ number_format($totalConversions ?? 0) }}</h4>
                    <p class="card-text text-muted">Conversions</p>
                    <small class="text-success">
                        {{ $conversionRate ?? 0 }}% rate
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-dollar-sign fa-2x text-info mb-3"></i>
                    <h4 class="card-title">${{ number_format($totalRevenue ?? 0, 2) }}</h4>
                    <p class="card-text text-muted">Total Revenue</p>
                    <small class="text-muted">
                        Generated via shared links
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Performers --}}
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trophy me-2 text-warning"></i>Top Performing Products
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($topProducts) && $topProducts->count() > 0)
                        @foreach($topProducts->take(5) as $index => $product)
                        <div class="d-flex align-items-center mb-3 {{ $loop->last ? '' : 'border-bottom pb-3' }}">
                            <div class="flex-shrink-0">
                                <div class="bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'info') }} text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <strong>{{ $index + 1 }}</strong>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $product->name }}</h6>
                                <small class="text-muted">{{ $product->clicks_count ?? 0 }} clicks • {{ $product->conversions_count ?? 0 }} conversions</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success">${{ number_format($product->total_revenue ?? 0, 2) }}</div>
                                <small class="text-muted">Revenue</small>
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
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2 text-success"></i>Top Sharing Affiliates
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($topAffiliates) && $topAffiliates->count() > 0)
                        @foreach($topAffiliates->take(5) as $index => $affiliate)
                        <div class="d-flex align-items-center mb-3 {{ $loop->last ? '' : 'border-bottom pb-3' }}">
                            <div class="flex-shrink-0">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $affiliate->name }}</h6>
                                <small class="text-muted">{{ $affiliate->email }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-primary">{{ $affiliate->clicks_count ?? 0 }}</div>
                                <small class="text-muted">Clicks</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-users fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No affiliate sharing data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Products Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">Shared Products Performance</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <select class="form-select form-select-sm" id="filterCategory">
                                    <option value="">All Categories</option>
                                    @if(isset($categories))
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <select class="form-select form-select-sm" id="sortBy">
                                    <option value="clicks">Sort by Clicks</option>
                                    <option value="conversions">Sort by Conversions</option>
                                    <option value="revenue">Sort by Revenue</option>
                                    <option value="commission">Sort by Commission</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($products) && $products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped" id="productsTable">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Commission Rate</th>
                                        <th>Total Clicks</th>
                                        <th>Conversions</th>
                                        <th>Conversion Rate</th>
                                        <th>Revenue</th>
                                        <th>Commission Paid</th>
                                        <th>Top Affiliate</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
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
                                                
                                                @if($legacyImageUrl)
                                                    <img src="{{ $legacyImageUrl }}" 
                                                         alt="{{ $product->name }}" 
                                                         class="rounded me-2" 
                                                         style="width: 40px; height: 40px; object-fit: cover;"
                                                         onerror="this.src='{{ asset('assets/img/product/1.png') }}'">
                                                @else
                                                    <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-box text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $product->name }}</strong>
                                                    <br><small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                                        <td>${{ number_format($product->price ?? 0, 2) }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $product->affiliate_commission_rate ?? 0 }}%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ number_format($product->clicks_count ?? 0) }}</span>
                                            @if(($product->clicks_growth ?? 0) > 0)
                                                <small class="text-success d-block">
                                                    <i class="fas fa-arrow-up"></i> +{{ $product->clicks_growth }}%
                                                </small>
                                            @endif
                                        </td>
                                        <td>{{ number_format($product->conversions_count ?? 0) }}</td>
                                        <td>
                                            @php
                                                $conversionRate = ($product->clicks_count ?? 0) > 0 ? round((($product->conversions_count ?? 0) / ($product->clicks_count ?? 1)) * 100, 2) : 0;
                                            @endphp
                                            <span class="badge bg-{{ $conversionRate > 5 ? 'success' : ($conversionRate > 2 ? 'warning' : 'danger') }}">
                                                {{ $conversionRate }}%
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-success">${{ number_format($product->total_revenue ?? 0, 2) }}</strong>
                                        </td>
                                        <td>
                                            <strong class="text-primary">${{ number_format($product->commission_paid ?? 0, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if($product->top_affiliate)
                                                <div>
                                                    <strong>{{ $product->top_affiliate->name }}</strong>
                                                    <br><small class="text-muted">{{ $product->top_affiliate_clicks ?? 0 }} clicks</small>
                                                </div>
                                            @else
                                                <span class="text-muted">No data</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.affiliate-links.show', $product) }}" class="btn btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-info" onclick="copyAffiliateLink({{ $product->id }})" title="Copy Affiliate Link">
                                                    <i class="fas fa-link"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-success" onclick="viewAnalytics({{ $product->id }})" title="View Analytics">
                                                    <i class="fas fa-chart-bar"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($products, 'links'))
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <small class="text-muted">
                                        Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
                                    </small>
                                </div>
                                <div>
                                    {{ $products->links() }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-link fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No shared products found</h5>
                            <p class="text-muted">Products will appear here as affiliates start sharing them.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Copy Link Modal --}}
<div class="modal fade" id="copyLinkModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Affiliate Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Product Affiliate Link</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="affiliateLink" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard()">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                </div>
                <div class="alert alert-info">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        This is the base affiliate link. Affiliates will add their ID parameter when sharing.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Copy affiliate link function
function copyAffiliateLink(productId) {
    const link = `${window.location.origin}/product/${productId}?affiliate_id=`;
    document.getElementById('affiliateLink').value = link;
    new bootstrap.Modal(document.getElementById('copyLinkModal')).show();
}

// Copy to clipboard
function copyToClipboard() {
    const linkInput = document.getElementById('affiliateLink');
    linkInput.select();
    linkInput.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    // Show success message
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-check"></i> Copied!';
    button.classList.remove('btn-outline-secondary');
    button.classList.add('btn-success');
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.classList.remove('btn-success');
        button.classList.add('btn-outline-secondary');
    }, 2000);
}

// View analytics function
function viewAnalytics(productId) {
    window.open(`/admin/affiliate-links/${productId}`, '_blank');
}

// Initialize DataTables
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#productsTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[4, 'desc']], // Sort by clicks by default
            columnDefs: [{
                targets: [10], // Actions column
                orderable: false
            }]
        });
    }

    // Filter functionality
    $('#filterCategory').on('change', function() {
        $('#productsTable').DataTable().column(1).search(this.value).draw();
    });

    // Sort functionality
    $('#sortBy').on('change', function() {
        const table = $('#productsTable').DataTable();
        const sortBy = this.value;
        
        switch(sortBy) {
            case 'clicks':
                table.order([4, 'desc']).draw();
                break;
            case 'conversions':
                table.order([5, 'desc']).draw();
                break;
            case 'revenue':
                table.order([7, 'desc']).draw();
                break;
            case 'commission':
                table.order([8, 'desc']).draw();
                break;
        }
    });
});
</script>
@endpush
