@extends('member.layouts.app')

@section('title', 'Shared Products')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="page-header-container">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-7 col-sm-12">
                    <h4 class="page-title mb-1">
                        <i class="bx bx-share-alt me-2 text-primary"></i>
                        Shared Products
                    </h4>
                    <nav aria-label="breadcrumb" class="d-none d-sm-block">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('member.products.index') }}">Products</a></li>
                            <li class="breadcrumb-item active">Shared Products</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-4 col-md-5 col-sm-12 text-end text-sm-start text-md-end mt-3 mt-md-0">
                    <a href="{{ route('member.products.index') }}" class="btn btn-outline-primary btn-responsive">
                        <i class="bx bx-arrow-back me-1"></i> <span class="d-none d-sm-inline">Back to</span> Products
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary-light text-primary me-3">
                            <i class="bx bx-share-alt"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="stats-number mb-0">{{ $stats['total_shared'] }}</h3>
                            <p class="stats-label mb-0">Products Shared</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success-light text-success me-3">
                            <i class="bx bx-mouse"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="stats-number mb-0">{{ number_format($stats['total_clicks']) }}</h3>
                            <p class="stats-label mb-0">Total Clicks</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-info-light text-info me-3">
                            <i class="bx bx-trending-up"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="stats-number mb-0">{{ number_format($stats['this_month_clicks']) }}</h3>
                            <p class="stats-label mb-0">This Month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning-light text-warning me-3">
                            <i class="bx bx-money"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="stats-number mb-0">৳{{ number_format($stats['total_commission'], 0) }}</h3>
                            <p class="stats-label mb-0">Total Commission</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Shared Products Grid -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom-0 pb-0">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <h5 class="card-title mb-0 text-center text-md-start">
                    <i class="bx bx-grid-alt me-2"></i>
                    My Shared Products
                </h5>
                <div class="d-flex gap-2 w-100 w-md-auto">
                    <select class="form-select form-select-sm flex-fill" style="min-width: 120px;">
                        <option value="12">12 per page</option>
                        <option value="24">24 per page</option>
                        <option value="36">36 per page</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($sharedProducts instanceof \Illuminate\Pagination\LengthAwarePaginator && $sharedProducts->count() > 0)
                <div class="row">
                    @foreach($sharedProducts as $product)
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="product-card h-100">
                                <div class="product-image-container">
                                    @php
                                        $legacyImageUrl = '';
                                        
                                        // Check for complex images JSON structure first
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
                                                $legacyImageUrl = asset('assets/img/product/default.png');
                                            }
                                        }
                                    @endphp
                                    <img src="{{ $legacyImageUrl }}" 
                                         alt="{{ $product->name }}" 
                                         class="product-image"
                                         onerror="this.src='{{ asset('assets/img/product/default.png') }}'">
                                    
                                    <div class="product-overlay">
                                        <div class="product-actions">
                                            <a href="{{ route('member.products.show', $product) }}" 
                                               class="btn btn-sm btn-outline-light" 
                                               title="View Details">
                                                <i class="bx bx-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-light generate-affiliate" 
                                                    data-product-id="{{ $product->id }}"
                                                    title="Get Affiliate Link">
                                                <i class="bx bx-link"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="product-info">
                                    <div class="product-category">
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </div>
                                    <h6 class="product-title">{{ Str::limit($product->name, 50) }}</h6>
                                    
                                    <div class="product-price">
                                        @if($product->sale_price && $product->sale_price < $product->price)
                                            <span class="current-price">৳{{ number_format($product->sale_price, 0) }}</span>
                                            <span class="original-price">৳{{ number_format($product->price, 0) }}</span>
                                        @else
                                            <span class="current-price">৳{{ number_format($product->price, 0) }}</span>
                                        @endif
                                    </div>
                                    
                                    <div class="product-stats mt-2">
                                        <small class="text-muted">
                                            <i class="bx bx-mouse me-1"></i>
                                            Clicks: <span class="fw-semibold">{{ $product->click_count ?? 0 }}</span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($sharedProducts->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $sharedProducts->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="bx bx-share-alt empty-state-icon"></i>
                        <h5 class="empty-state-title">No Shared Products Yet</h5>
                        <p class="empty-state-text">You haven't shared any products through affiliate links yet. Start sharing products to earn commissions!</p>
                        <a href="{{ route('member.products.index') }}" class="btn btn-primary">
                            <i class="bx bx-plus me-1"></i>
                            Browse Products to Share
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Affiliate Link Modal -->
<div class="modal fade" id="affiliateLinkModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Affiliate Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Your Affiliate Link:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="affiliateLink" readonly>
                        <button class="btn btn-outline-secondary" type="button" id="copyLinkBtn">
                            <i class="bx bx-copy"></i> Copy
                        </button>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        Share this link to earn commissions when someone purchases through it.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.page-header-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    margin: -1.5rem -1.5rem 2rem -1.5rem;
    padding: 2rem 1.5rem;
}

.page-header {
    color: white;
}

.page-header .breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
}

.page-header .breadcrumb-item a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
}

.page-header .breadcrumb-item.active {
    color: white;
}

/* Responsive Header Styles */
@media (max-width: 767.98px) {
    .page-header-container {
        margin: -1rem -1rem 1.5rem -1rem;
        padding: 1.5rem 1rem;
    }
    
    .page-title {
        font-size: 1.25rem;
        text-align: center;
        margin-bottom: 0.5rem;
    }
    
    .page-title i {
        font-size: 1.1rem;
    }
    
    .btn-responsive {
        width: 100%;
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
}

@media (max-width: 575.98px) {
    .page-header-container {
        margin: -0.75rem -0.75rem 1rem -0.75rem;
        padding: 1rem 0.75rem;
    }
    
    .page-title {
        font-size: 1.1rem;
    }
    
    .btn-responsive {
        font-size: 0.85rem;
        padding: 0.45rem 0.9rem;
    }
}

.stats-card {
    transition: transform 0.2s ease;
}

.stats-card:hover {
    transform: translateY(-2px);
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

/* Responsive Stats Cards */
@media (max-width: 767.98px) {
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .stats-icon {
        width: 45px;
        height: 45px;
        font-size: 1.3rem;
    }
    
    .stats-number {
        font-size: 1.5rem !important;
    }
    
    .stats-label {
        font-size: 0.8rem !important;
    }
}

@media (max-width: 575.98px) {
    .stats-icon {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }
    
    .stats-number {
        font-size: 1.3rem !important;
    }
}

.bg-primary-light {
    background-color: rgba(13, 110, 253, 0.1);
}

.bg-success-light {
    background-color: rgba(25, 135, 84, 0.1);
}

.bg-info-light {
    background-color: rgba(13, 202, 240, 0.1);
}

.bg-warning-light {
    background-color: rgba(255, 193, 7, 0.1);
}

.stats-number {
    font-size: 1.75rem;
    font-weight: 700;
    color: #2c3e50;
}

.stats-label {
    color: #6c757d;
    font-size: 0.875rem;
}

.product-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid #f1f3f4;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.product-image-container {
    position: relative;
    height: 200px;
    overflow: hidden;
}

/* Responsive Product Cards */
@media (max-width: 767.98px) {
    .product-image-container {
        height: 180px;
    }
}

@media (max-width: 575.98px) {
    .product-image-container {
        height: 160px;
    }
    
    .product-card {
        margin-bottom: 1rem;
    }
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

/* Mobile touch-friendly overlay */
@media (max-width: 767.98px) {
    .product-overlay {
        opacity: 0.8;
        background: rgba(0, 0, 0, 0.5);
    }
    
    .product-actions .btn {
        padding: 0.4rem 0.6rem;
        font-size: 0.9rem;
    }
}

.product-actions {
    display: flex;
    gap: 0.5rem;
}

.product-info {
    padding: 1rem;
}

/* Responsive Product Info */
@media (max-width: 575.98px) {
    .product-info {
        padding: 0.8rem;
    }
}

.product-category {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.product-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

/* Responsive Typography */
@media (max-width: 575.98px) {
    .product-category {
        font-size: 0.7rem;
    }
    
    .product-title {
        font-size: 0.9rem;
    }
}

.product-price {
    margin-bottom: 0.5rem;
}

.current-price {
    font-size: 1.1rem;
    font-weight: 700;
    color: #e74c3c;
}

.original-price {
    font-size: 0.9rem;
    color: #6c757d;
    text-decoration: line-through;
    margin-left: 0.5rem;
}

@media (max-width: 575.98px) {
    .current-price {
        font-size: 1rem;
    }
    
    .original-price {
        font-size: 0.85rem;
    }
}

.empty-state {
    max-width: 400px;
    margin: 0 auto;
}

.empty-state-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.empty-state-title {
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.empty-state-text {
    color: #adb5bd;
    margin-bottom: 1.5rem;
}

/* Responsive Empty State */
@media (max-width: 575.98px) {
    .empty-state {
        max-width: 300px;
        padding: 0 1rem;
    }
    
    .empty-state-icon {
        font-size: 3rem;
    }
    
    .empty-state-title {
        font-size: 1.1rem;
    }
    
    .empty-state-text {
        font-size: 0.9rem;
    }
}

/* Responsive Card Header */
@media (max-width: 767.98px) {
    .card-header .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .card-header .form-select {
        width: 100% !important;
    }
    
    .card-title {
        text-align: center;
        font-size: 1.1rem;
    }
}

/* Responsive Modal */
@media (max-width: 575.98px) {
    .modal-dialog {
        margin: 1rem 0.5rem;
    }
    
    .modal-body {
        padding: 1rem;
    }
    
    .input-group .btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Generate affiliate link
    $('.generate-affiliate').click(function() {
        const productId = $(this).data('product-id');
        const btn = $(this);
        const originalContent = btn.html();
        
        btn.html('<i class="bx bx-loader-2 bx-spin"></i>').prop('disabled', true);
        
        $.ajax({
            url: '{{ route("member.products.affiliate.link") }}',
            method: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#affiliateLink').val(response.affiliate_link);
                    $('#affiliateLinkModal').modal('show');
                } else {
                    showNotification('error', response.message || 'Failed to generate affiliate link');
                }
            },
            error: function() {
                showNotification('error', 'Failed to generate affiliate link');
            },
            complete: function() {
                btn.html(originalContent).prop('disabled', false);
            }
        });
    });
    
    // Copy affiliate link
    $('#copyLinkBtn').click(function() {
        const linkInput = $('#affiliateLink')[0];
        linkInput.select();
        linkInput.setSelectionRange(0, 99999);
        
        navigator.clipboard.writeText(linkInput.value).then(function() {
            showNotification('success', 'Affiliate link copied to clipboard!');
            $('#affiliateLinkModal').modal('hide');
        });
    });
});

function showNotification(type, message) {
    // Implementation depends on your notification system
    alert(message);
}
</script>
@endpush
