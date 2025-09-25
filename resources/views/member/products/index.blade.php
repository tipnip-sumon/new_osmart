@extends('member.layouts.app')

@section('title', 'Products')

@push('styles')
<style>
.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: white;
}

.stat-label {
    font-size: 1rem;
    opacity: 0.9;
    font-weight: 500;
}

.filters-section {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 20px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    border: 1px solid rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    position: relative;
}

.filters-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #007bff, #6610f2, #e83e8c, #fd7e14);
    border-radius: 20px 20px 0 0;
}

.filter-group {
    margin-bottom: 20px;
}

.filter-group label {
    font-weight: 600;
    margin-bottom: 10px;
    color: #495057;
    display: block;
    font-size: 0.95rem;
}

.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    font-size: 0.95rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(5px);
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
    background: white;
    transform: translateY(-1px);
}

.btn-filter {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
    border-radius: 10px;
    padding: 12px 20px;
    font-weight: 600;
    font-size: 0.95rem;
    color: white;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
    position: relative;
    overflow: hidden;
}

.btn-filter::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.6s;
}

.btn-filter:hover::before {
    left: 100%;
}

.btn-filter:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
    background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);
}

.btn-filter:active {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

.btn-clear {
    background: linear-gradient(135deg, transparent 0%, rgba(108, 117, 125, 0.05) 100%);
    border: 2px solid #e9ecef;
    color: #6c757d;
    border-radius: 10px;
    padding: 10px 15px;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    min-width: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-clear::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.btn-clear:hover {
    border-color: #6c757d;
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(108, 117, 125, 0.3);
}

.btn-clear:hover::before {
    opacity: 1;
}

.btn-clear:hover i {
    position: relative;
    z-index: 1;
}

.btn-clear:active {
    transform: translateY(-1px);
}

.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.loading-overlay.show {
    display: flex;
}

.results-info {
    background: #f8f9fa;
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    border-left: 4px solid var(--primary, #007bff);
}

.results-count {
    font-weight: 600;
    color: #333;
}

.results-meta {
    color: #666;
    font-size: 0.9rem;
    margin-top: 5px;
}

.product-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    margin-bottom: 25px;
    overflow: hidden;
    border: 1px solid #f0f0f0;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.product-image {
    position: relative;
    height: 220px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.product-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #28a745;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.favorite-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(255,255,255,0.9);
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    color: #666;
}

.favorite-btn:hover, .favorite-btn.active {
    background: #dc3545;
    color: white;
}

.product-content {
    padding: 20px;
}

.product-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    line-height: 1.3;
    height: 2.6em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.product-category {
    color: #666;
    font-size: 0.85rem;
    margin-bottom: 10px;
}

.product-price {
    margin-bottom: 15px;
}

.price-current {
    font-size: 1.4rem;
    font-weight: 700;
    color: #28a745;
}

.price-original {
    font-size: 1rem;
    color: #999;
    text-decoration: line-through;
    margin-left: 8px;
}

.discount-badge {
    background: #dc3545;
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.75rem;
    margin-left: 8px;
}

.product-actions {
    display: flex;
    gap: 8px;
}

.btn-view {
    flex: 1;
    background: #007bff;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 6px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-view:hover {
    background: #0056b3;
    color: white;
}

.btn-share {
    background: #6c757d;
    color: white;
    border: none;
    padding: 10px 12px;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.btn-share:hover {
    background: #5a6268;
    color: white;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state img {
    max-width: 200px;
    opacity: 0.5;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: #666;
    margin-bottom: 10px;
}

.empty-state p {
    color: #999;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .filters-section {
        padding: 20px;
    }
    
    .stats-card {
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .filter-group {
        margin-bottom: 20px;
    }
    
    .btn-filter {
        width: 100%;
        margin-bottom: 10px;
        padding: 14px 20px;
        font-size: 1rem;
    }
    
    .btn-clear {
        width: 100%;
        margin-bottom: 10px;
        padding: 14px 20px;
        font-size: 1rem;
        min-width: unset;
    }
    
    .filter-buttons-wrapper {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 100%;
    }
    
    .product-image {
        height: 180px;
    }
    
    .product-grid {
        gap: 15px;
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .btn-filter {
        padding: 12px 18px;
        font-size: 0.9rem;
    }
    
    .btn-clear {
        padding: 10px 12px;
        font-size: 0.9rem;
    }
}

@media (min-width: 1025px) {
    .filter-buttons-wrapper {
        display: flex;
        gap: 8px;
        align-items: stretch;
    }
    
    .btn-filter {
        flex: 1;
        min-width: 100px;
    }
    
    .btn-clear {
        min-width: 50px;
        width: auto;
    }
}
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Products</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Products</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Product Statistics -->
        <div class="row">
            <div class="col-12">
                <div class="stats-card">
                    <div class="row">
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <div class="stat-number">{{ $products->total() ?? 0 }}</div>
                                <div class="stat-label">Total Products</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <div class="stat-number">{{ $favoriteProducts ?? 0 }}</div>
                                <div class="stat-label">My Favorites</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <div class="stat-number">৳{{ number_format($memberCommission ?? 0, 0) }}</div>
                                <div class="stat-label">My Commissions</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <div class="stat-number">{{ $sharedProducts ?? 0 }}</div>
                                <div class="stat-label">Shared Products</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="row">
            <div class="col-12">
                <div class="filters-section">
                    <form method="GET" action="{{ route('member.products.index') }}" id="filtersForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="filter-group">
                                    <label for="search">Search Products</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           value="{{ request('search') }}" 
                                           placeholder="Search by name, description...">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="filter-group">
                                    <label for="category">Category</label>
                                    <select class="form-select" id="category" name="category">
                                        <option value="">All Categories</option>
                                        @if(isset($categories))
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }} @if(isset($category->products_count))({{ $category->products_count }})@endif
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="filter-group">
                                    <label for="brand">Brand</label>
                                    <select class="form-select" id="brand" name="brand">
                                        <option value="">All Brands</option>
                                        @if(isset($brands))
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }} @if(isset($brand->products_count))({{ $brand->products_count }})@endif
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="filter-group">
                                    <label for="sort">Sort By</label>
                                    <select class="form-select" id="sort" name="sort">
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="filter-group">
                                    <label for="per_page">Show</label>
                                    <select class="form-select" id="per_page" name="per_page">
                                        <option value="12" {{ request('per_page') == '12' ? 'selected' : '' }}>12</option>
                                        <option value="24" {{ request('per_page') == '24' ? 'selected' : '' }}>24</option>
                                        <option value="48" {{ request('per_page') == '48' ? 'selected' : '' }}>48</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="filter-group">
                                    <label>&nbsp;</label>
                                    <div class="filter-buttons-wrapper d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-filter flex-fill">
                                            <i class="ri-search-line me-1"></i>
                                            <span class="d-none d-md-inline">Filter</span>
                                            <span class="d-md-none">Apply</span>
                                        </button>
                                        <button type="button" class="btn btn-clear" onclick="clearFilters()">
                                            <i class="ri-refresh-line"></i>
                                            <span class="d-md-none ms-2">Clear</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results Info -->
        @if(isset($products) && $products->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="results-info">
                        <div class="results-count">
                            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
                        </div>
                        @if(request()->hasAny(['search', 'category', 'brand']))
                            <div class="results-meta">
                                @if(request('search'))
                                    <span class="me-3"><strong>Search:</strong> "{{ request('search') }}"</span>
                                @endif
                                @if(request('category') && isset($categories))
                                    <span class="me-3"><strong>Category:</strong> {{ $categories->find(request('category'))->name ?? 'Unknown' }}</span>
                                @endif
                                @if(request('brand') && isset($brands))
                                    <span><strong>Brand:</strong> {{ $brands->find(request('brand'))->name ?? 'Unknown' }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Products Grid -->
        <div class="row">
            <div class="col-12">
                <div id="productsContainer">
                    @if(isset($products) && $products->count() > 0)
                        <div class="row">
                            @foreach($products as $product)
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                                    <div class="product-card">
                                        <div class="product-image">
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
                                                 loading="lazy"
                                                 onerror="this.src='{{ asset('assets/img/product/default.png') }}'">                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <div class="product-badge">
                                                    {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                                                </div>
                                            @endif
                                            
                                            <button class="favorite-btn {{ in_array($product->id, $favoriteProductIds ?? []) ? 'active' : '' }}" data-product-id="{{ $product->id }}">
                                                <i class="{{ in_array($product->id, $favoriteProductIds ?? []) ? 'ri-heart-fill' : 'ri-heart-line' }}"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="product-content">
                                            <h3 class="product-title">{{ $product->name }}</h3>
                                            
                                            @if($product->category)
                                                <div class="product-category">{{ $product->category->name }}</div>
                                            @endif
                                            
                                            <div class="product-price">
                                                @if($product->sale_price && $product->sale_price < $product->price)
                                                    <span class="price-current">৳{{ number_format($product->sale_price, 0) }}</span>
                                                    <span class="price-original">৳{{ number_format($product->price, 0) }}</span>
                                                @else
                                                    <span class="price-current">৳{{ number_format($product->price, 0) }}</span>
                                                @endif
                                            </div>
                                            
                                            <div class="product-actions">
                                                <a href="{{ route('member.products.show', $product) }}" class="btn btn-view">
                                                    View Details
                                                </a>
                                                <button class="btn btn-share" data-product-id="{{ $product->id }}">
                                                    <i class="ri-share-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <img src="{{ asset('assets/img/product/default.png') }}" alt="No products found">
                            <h3>No Products Found</h3>
                            <p>Try adjusting your search criteria or browse all products.</p>
                            <a href="{{ route('member.products.index') }}" class="btn btn-primary">Browse All Products</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if(isset($products) && $products->hasPages())
            <div class="pagination-wrapper">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-submit form on filter change (except search)
    $('#filtersForm select').on('change', function() {
        showLoading();
        submitFiltersAjax();
    });

    // Search with debounce
    let searchTimeout;
    $('#search').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            submitFiltersAjax();
        }, 500);
    });

    // AJAX form submission for dynamic filtering
    function submitFiltersAjax() {
        showLoading();
        
        const formData = $('#filtersForm').serialize();
        
        $.ajax({
            url: '{{ route("member.products.index") }}',
            method: 'GET',
            data: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    // Update products grid
                    $('#productsContainer').html(response.products);
                    
                    // Update pagination
                    if (response.pagination) {
                        $('.pagination-wrapper').html(response.pagination);
                    } else {
                        $('.pagination-wrapper').html('');
                    }
                    
                    // Update results info
                    updateResultsInfo(response.total, response.count);
                    
                    // Update URL without page reload
                    const url = new URL(window.location);
                    const params = new URLSearchParams(formData);
                    params.forEach((value, key) => {
                        if (value) {
                            url.searchParams.set(key, value);
                        } else {
                            url.searchParams.delete(key);
                        }
                    });
                    window.history.pushState({}, '', url);
                    
                    // Show appropriate notification based on results
                    if (response.total > 0) {
                        showNotification('success', `Found ${response.total} products`);
                    } else {
                        showNotification('info', 'No products found matching your criteria');
                    }
                } else {
                    showNotification('error', response.message || 'Failed to load products');
                }
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr);
                showNotification('error', 'Failed to load products');
                // Fallback to normal form submission
                $('#filtersForm').submit();
            },
            complete: function() {
                hideLoading();
            }
        });
    }

    // Update results info
    function updateResultsInfo(total, count) {
        if (total > 0) {
            const info = `Showing 1 to ${count} of ${total} products`;
            $('.results-count').text(info);
        }
    }

    // Show loading overlay
    function showLoading() {
        $('#loadingOverlay').addClass('show');
    }

    // Hide loading overlay
    function hideLoading() {
        $('#loadingOverlay').removeClass('show');
    }

    // Clear filters function
    function clearFilters() {
        showLoading();
        
        // Reset form
        $('#filtersForm')[0].reset();
        
        // Clear URL parameters and reload
        const url = '{{ route("member.products.index") }}';
        window.history.pushState({}, '', url);
        
        // Submit form to reload products
        setTimeout(function() {
            submitFiltersAjax();
        }, 200);
    }

    // Clear all filters (legacy support)
    $('.btn-clear').on('click', function(e) {
        e.preventDefault();
        clearFilters();
    });

    // Handle pagination clicks with AJAX
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        
        if (url) {
            showLoading();
            
            $.ajax({
                url: url,
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (response.success) {
                        $('#productsContainer').html(response.products);
                        $('.pagination-wrapper').html(response.pagination);
                        updateResultsInfo(response.total, response.count);
                        
                        // Scroll to top of products
                        $('html, body').animate({
                            scrollTop: $('#productsContainer').offset().top - 100
                        }, 500);
                        
                        // Update URL
                        window.history.pushState({}, '', url);
                    }
                },
                error: function() {
                    window.location.href = url; // Fallback to normal navigation
                },
                complete: function() {
                    hideLoading();
                }
            });
        }
    });

    // Favorite toggle functionality
    $(document).on('click', '.favorite-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const btn = $(this);
        const productId = btn.data('product-id');
        const icon = btn.find('i');
        const wasActive = btn.hasClass('active');
        
        console.log('Before toggle - Button was active:', wasActive); // Debug log
        
        // Add loading state
        btn.prop('disabled', true);
        icon.removeClass('ri-heart-line ri-heart-fill').addClass('ri-loader-2-line');
        
        $.ajax({
            url: '{{ route("member.products.favorites.toggle") }}',
            method: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('Favorite toggle response:', response); // Debug log
                if (response.success) {
                    // Use the server response to determine new state
                    const isNowFavorited = response.favorited === true;
                    console.log('Server says favorited:', isNowFavorited); // Debug log
                    
                    if (isNowFavorited) {
                        btn.addClass('active');
                        icon.removeClass('ri-loader-2-line ri-heart-line').addClass('ri-heart-fill');
                        btn.attr('title', 'Remove from favorites');
                        showNotification('success', 'Added to favorites ❤️');
                    } else {
                        btn.removeClass('active');
                        icon.removeClass('ri-loader-2-line ri-heart-fill').addClass('ri-heart-line');
                        btn.attr('title', 'Add to favorites');
                        showNotification('success', 'Removed from favorites 💔');
                    }
                } else {
                    // Revert to original state on error
                    if (wasActive) {
                        btn.addClass('active');
                        icon.removeClass('ri-loader-2-line').addClass('ri-heart-fill');
                    } else {
                        btn.removeClass('active');
                        icon.removeClass('ri-loader-2-line').addClass('ri-heart-line');
                    }
                    showNotification('error', response.message || 'Failed to update favorites');
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', xhr.responseText); // Debug log
                // Revert to original state on error
                if (wasActive) {
                    btn.addClass('active');
                    icon.removeClass('ri-loader-2-line').addClass('ri-heart-fill');
                } else {
                    btn.removeClass('active');
                    icon.removeClass('ri-loader-2-line').addClass('ri-heart-line');
                }
                showNotification('error', 'Failed to update favorites');
            },
            complete: function() {
                btn.prop('disabled', false);
            }
        });
    });

    // Share functionality
    $(document).on('click', '.btn-share', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        
        // Get affiliate link
        $.ajax({
            url: '{{ route("member.products.affiliate.link") }}',
            method: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Copy to clipboard
                    navigator.clipboard.writeText(response.affiliate_link).then(function() {
                        showNotification('success', 'Affiliate link copied to clipboard!');
                    }).catch(function() {
                        // Fallback for older browsers
                        const textArea = document.createElement('textarea');
                        textArea.value = response.affiliate_link;
                        document.body.appendChild(textArea);
                        textArea.select();
                        document.execCommand('copy');
                        document.body.removeChild(textArea);
                        showNotification('success', 'Affiliate link copied to clipboard!');
                    });
                } else {
                    showNotification('error', 'Failed to generate affiliate link');
                }
            },
            error: function() {
                showNotification('error', 'Failed to generate affiliate link');
            }
        });
    });
});

// Notification function
function showNotification(type, message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: type,
            title: message,
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    } else {
        // Fallback notification
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alert = `<div class="alert ${alertClass} alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
        $('body').append(alert);
        
        // Auto remove after 3 seconds
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    }
}
</script>
@endpush
