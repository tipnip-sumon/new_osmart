@extends('member.layouts.app')

@section('title', 'My Favorites')

@push('styles')
<style>
.favorite-product-card {
    border: 1px solid #eee;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
}

.favorite-product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.product-image-container {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.product-image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.favorite-product-card:hover .product-image-container img {
    transform: scale(1.05);
}

.favorite-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #e74c3c;
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 2;
}

.favorite-badge:hover {
    background: #e74c3c;
    color: white;
    transform: scale(1.1);
}

.discount-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: #e74c3c;
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.product-info {
    padding: 20px;
}

.product-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
    text-decoration: none;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
}

.product-title:hover {
    color: var(--primary);
}

.product-price {
    margin: 15px 0;
}

.original-price {
    color: #999;
    text-decoration: line-through;
    font-size: 0.9rem;
    margin-right: 8px;
}

.sale-price {
    color: var(--primary);
    font-size: 1.2rem;
    font-weight: 600;
}

.product-meta {
    margin-bottom: 15px;
}

.product-category {
    background: #f8f9fa;
    color: #6c757d;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    text-decoration: none;
}

.product-category:hover {
    background: var(--primary);
    color: white;
}

.product-actions {
    display: flex;
    gap: 8px;
    margin-top: 15px;
}

.btn-action {
    flex: 1;
    padding: 8px 12px;
    font-size: 0.85rem;
    border-radius: 6px;
}

.stock-status {
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 10px;
}

.in-stock {
    color: #27ae60;
}

.out-of-stock {
    color: #e74c3c;
}

.low-stock {
    color: #f39c12;
}

.empty-favorites {
    text-align: center;
    padding: 60px 20px;
}

.empty-favorites img {
    max-width: 200px;
    opacity: 0.7;
    margin-bottom: 30px;
}

.favorites-stats {
    background: linear-gradient(135deg, #007bff, #6f42c1);
    color: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.1);
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 5px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    color: #ffffff !important;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.95;
    font-weight: 500;
    color: #ffffff !important;
}

.filters-section {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.filter-group {
    margin-bottom: 15px;
}

.filter-group label {
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
    color: #555;
}

.clear-filters {
    color: var(--primary);
    text-decoration: none;
    font-size: 0.9rem;
}

.clear-filters:hover {
    text-decoration: underline;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 40px;
}

.rating-stars {
    color: #ffc107;
    font-size: 0.9rem;
    margin-bottom: 8px;
}

.favorites-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 25px;
}

.bulk-actions {
    display: none;
    gap: 10px;
    margin-bottom: 20px;
}

.bulk-actions.show {
    display: flex;
}

.product-checkbox {
    position: absolute;
    top: 15px;
    left: 50px;
    width: 20px;
    height: 20px;
    z-index: 2;
}

@media (max-width: 768px) {
    .favorites-stats {
        margin-bottom: 20px;
        padding: 20px 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        background: linear-gradient(135deg, #007bff, #6f42c1) !important;
    }
    
    .stat-item {
        margin-bottom: 20px;
    }
    
    .stat-number {
        font-size: 1.6rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.4);
        font-weight: 800;
        color: #ffffff !important;
    }
    
    .stat-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #ffffff !important;
    }
    
    .filters-section {
        padding: 15px;
    }
    
    .product-actions {
        flex-direction: column;
    }
    
    .btn-action {
        margin-bottom: 5px;
    }
}

@media (max-width: 576px) {
    .favorites-stats {
        padding: 18px 12px;
        border-radius: 10px;
        background: linear-gradient(135deg, #007bff, #6f42c1) !important;
    }
    
    .stat-number {
        font-size: 1.4rem;
        font-weight: 800;
        text-shadow: 0 2px 4px rgba(0,0,0,0.4);
        letter-spacing: 0.5px;
        color: #ffffff !important;
    }
    
    .stat-label {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: #ffffff !important;
    }
    
    .filters-section .row {
        gap: 10px;
    }
    
    .filter-group {
        margin-bottom: 10px;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 8px;
    }
}
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">My Favorites</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Favorites</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if($favorites->count() > 0)
            <!-- Favorites Stats -->
            <div class="row">
                <div class="col-12">
                    <div class="favorites-stats">
                        <div class="row">
                            <div class="col-md-3 col-6">
                                <div class="stat-item">
                                    <div class="stat-number">{{ $stats['total_favorites'] ?? $favorites->total() ?? 0 }}</div>
                                    <div class="stat-label">Total Favorites</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-item">
                                    <div class="stat-number">{{ $stats['categories'] ?? 0 }}</div>
                                    <div class="stat-label">Categories</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-item">
                                    <div class="stat-number">৳{{ number_format($stats['total_value'] ?? 0, 0) }}</div>
                                    <div class="stat-label">Total Value</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-item">
                                    <div class="stat-number">৳{{ number_format($stats['avg_price'] ?? 0, 0) }}</div>
                                    <div class="stat-label">Avg. Price</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="row">
                <div class="col-12">
                    <div class="filters-section">
                        <form method="GET" action="{{ route('member.products.favorites') }}" id="filtersForm">
                            <div class="row align-items-end">
                                <div class="col-md-3">
                                    <div class="filter-group">
                                        <label for="search">Search Products</label>
                                        <input type="text" class="form-control" id="search" name="search" 
                                               value="{{ request('search') }}" placeholder="Search favorites...">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="filter-group">
                                        <label for="category">Category</label>
                                        <select class="form-select" id="category" name="category">
                                            <option value="">All Categories</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
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
                                <div class="col-md-2">
                                    <div class="filter-group">
                                        <label for="per_page">Show</label>
                                        <select class="form-select" id="per_page" name="per_page">
                                            <option value="12" {{ request('per_page') == '12' ? 'selected' : '' }}>12 items</option>
                                            <option value="24" {{ request('per_page') == '24' ? 'selected' : '' }}>24 items</option>
                                            <option value="48" {{ request('per_page') == '48' ? 'selected' : '' }}>48 items</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-search-line me-1"></i>Filter
                                        </button>
                                        <a href="{{ route('member.products.favorites') }}" class="clear-filters btn btn-outline-secondary">
                                            <i class="ri-refresh-line me-1"></i>Clear
                                        </a>
                                        <button type="button" class="btn btn-info" id="toggleBulkActions">
                                            <i class="ri-checkbox-multiple-line me-1"></i>Select
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div class="bulk-actions" id="bulkActions">
                <button type="button" class="btn btn-danger" id="bulkRemoveBtn">
                    <i class="ri-delete-bin-line me-1"></i>Remove Selected
                </button>
                <button type="button" class="btn btn-success" id="bulkGenerateLinksBtn">
                    <i class="ri-share-line me-1"></i>Generate Affiliate Links
                </button>
                <span class="text-muted ms-3">
                    <span id="selectedCount">0</span> items selected
                </span>
            </div>

            <!-- Products Grid -->
            <div class="row" id="favoritesGrid">
                @foreach($favorites as $favorite)
                    @php $product = $favorite->product; @endphp
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                        <div class="favorite-product-card" data-product-id="{{ $product->id }}">
                            <!-- Bulk Select Checkbox -->
                            <input type="checkbox" class="product-checkbox d-none" value="{{ $product->id }}">
                            
                            <!-- Product Image -->
                            <div class="product-image-container">
                                <a href="{{ route('member.products.show', $product) }}">
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
                                         onerror="this.src='{{ asset('assets/img/product/default.png') }}'">
                                </a>
                                
                                <!-- Discount Badge -->
                                @if($product->sale_price < $product->price)
                                    <div class="discount-badge">
                                        {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                                    </div>
                                @endif
                                
                                <!-- Favorite Button -->
                                <button class="favorite-badge remove-favorite" 
                                        data-product-id="{{ $product->id }}"
                                        title="Remove from favorites">
                                    <i class="ri-heart-fill"></i>
                                </button>
                            </div>

                            <!-- Product Info -->
                            <div class="product-info">
                                <!-- Product Title -->
                                <a href="{{ route('member.products.show', $product) }}" class="product-title">
                                    {{ $product->name }}
                                </a>

                                <!-- Product Meta -->
                                <div class="product-meta">
                                    @if($product->category)
                                        <a href="{{ route('member.products.index', ['category' => $product->category->id]) }}" 
                                           class="product-category">
                                            {{ $product->category->name }}
                                        </a>
                                    @endif
                                </div>

                                <!-- Rating -->
                                @if($product->average_rating)
                                    <div class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="ri-star-{{ $i <= $product->average_rating ? 'fill' : 'line' }}"></i>
                                        @endfor
                                        <span class="text-muted ms-1">({{ $product->review_count }})</span>
                                    </div>
                                @endif

                                <!-- Stock Status -->
                                <div class="stock-status">
                                    @if($product->stock_quantity > 0)
                                        @if($product->stock_quantity <= 5)
                                            <span class="low-stock">
                                                <i class="ri-error-warning-line me-1"></i>Low Stock
                                            </span>
                                        @else
                                            <span class="in-stock">
                                                <i class="ri-check-line me-1"></i>In Stock
                                            </span>
                                        @endif
                                    @else
                                        <span class="out-of-stock">
                                            <i class="ri-close-line me-1"></i>Out of Stock
                                        </span>
                                    @endif
                                </div>

                                <!-- Price -->
                                <div class="product-price">
                                    @if($product->sale_price < $product->price)
                                        <span class="original-price">৳{{ number_format($product->price, 0) }}</span>
                                    @endif
                                    <span class="sale-price">৳{{ number_format($product->sale_price, 0) }}</span>
                                </div>

                                <!-- Actions -->
                                <div class="product-actions">
                                    <a href="{{ route('member.products.show', $product) }}" 
                                       class="btn btn-primary btn-action">
                                        <i class="ri-eye-line me-1"></i>View
                                    </a>
                                    <button class="btn btn-success btn-action generate-affiliate" 
                                            data-product-id="{{ $product->id }}">
                                        <i class="ri-share-line me-1"></i>Share
                                    </button>
                                </div>

                                <!-- Added Date -->
                                <div class="text-muted mt-2" style="font-size: 0.75rem;">
                                    Added {{ $favorite->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $favorites->appends(request()->query())->links() }}
            </div>

        @else
            <!-- Empty State -->
            <div class="row">
                <div class="col-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="empty-favorites">
                                <img src="{{ asset('images/empty-favorites.png') }}" alt="No favorites" 
                                     onerror="this.style.display='none'">
                                <h4 class="fw-semibold mb-3">No Favorites Yet</h4>
                                <p class="text-muted mb-4">
                                    You haven't added any products to your favorites list. 
                                    Start browsing and click the heart icon on products you love!
                                </p>
                                <a href="{{ route('member.products.index') }}" class="btn btn-primary">
                                    <i class="ri-shopping-bag-line me-2"></i>Browse Products
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let bulkSelectMode = false;

    // Auto-submit form on filter change
    $('#filtersForm select, #filtersForm input[name="search"]').on('change', function() {
        $('#filtersForm').submit();
    });

    // Search input with debounce
    let searchTimeout;
    $('#search').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            $('#filtersForm').submit();
        }, 500);
    });

    // Toggle bulk selection mode
    $('#toggleBulkActions').on('click', function() {
        bulkSelectMode = !bulkSelectMode;
        
        if (bulkSelectMode) {
            $('.product-checkbox').removeClass('d-none');
            $('#bulkActions').addClass('show');
            $(this).html('<i class="ri-close-line me-1"></i>Cancel');
        } else {
            $('.product-checkbox').addClass('d-none').prop('checked', false);
            $('#bulkActions').removeClass('show');
            $(this).html('<i class="ri-checkbox-multiple-line me-1"></i>Select');
            updateSelectedCount();
        }
    });

    // Handle checkbox selection
    $('.product-checkbox').on('change', function() {
        updateSelectedCount();
    });

    // Update selected count
    function updateSelectedCount() {
        const selectedCount = $('.product-checkbox:checked').length;
        $('#selectedCount').text(selectedCount);
    }

    // Remove from favorites
    $('.remove-favorite').on('click', function(e) {
        e.preventDefault();
        const btn = $(this);
        const productId = btn.data('product-id');
        const productCard = btn.closest('.favorite-product-card');

        if (confirm('Remove this product from your favorites?')) {
            $.ajax({
                url: '{{ route("member.products.favorites.remove") }}',
                method: 'POST',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        productCard.fadeOut(400, function() {
                            $(this).remove();
                            checkEmptyState();
                        });
                        showNotification('success', response.message);
                    }
                },
                error: function() {
                    showNotification('error', 'Failed to remove from favorites');
                }
            });
        }
    });

    // Bulk remove favorites
    $('#bulkRemoveBtn').on('click', function() {
        const selectedProducts = $('.product-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedProducts.length === 0) {
            showNotification('warning', 'Please select products to remove');
            return;
        }

        if (confirm(`Remove ${selectedProducts.length} product(s) from favorites?`)) {
            $.ajax({
                url: '{{ route("member.products.favorites.bulk-remove") }}',
                method: 'POST',
                data: {
                    product_ids: selectedProducts,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        selectedProducts.forEach(function(productId) {
                            $(`.favorite-product-card[data-product-id="${productId}"]`).fadeOut(400, function() {
                                $(this).remove();
                                checkEmptyState();
                            });
                        });
                        showNotification('success', response.message);
                        $('#toggleBulkActions').click(); // Exit bulk mode
                    }
                },
                error: function() {
                    showNotification('error', 'Failed to remove favorites');
                }
            });
        }
    });

    // Generate affiliate links
    $('.generate-affiliate').on('click', function() {
        const btn = $(this);
        const productId = btn.data('product-id');
        const originalText = btn.html();
        
        btn.prop('disabled', true).html('<i class="ri-loader-4-line me-1 spinner-border spinner-border-sm"></i>Generating...');
        
        $.ajax({
            url: '{{ route("member.products.affiliate.link") }}',
            method: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showAffiliateModal(response.affiliate_link);
                    showNotification('success', 'Affiliate link generated!');
                }
            },
            error: function() {
                showNotification('error', 'Failed to generate affiliate link');
            },
            complete: function() {
                btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Bulk generate affiliate links
    $('#bulkGenerateLinksBtn').on('click', function() {
        const selectedProducts = $('.product-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedProducts.length === 0) {
            showNotification('warning', 'Please select products to generate links');
            return;
        }

        const btn = $(this);
        const originalText = btn.html();
        btn.prop('disabled', true).html('<i class="ri-loader-4-line me-1 spinner-border spinner-border-sm"></i>Generating...');

        $.ajax({
            url: '{{ route("member.products.affiliate.bulk-links") }}',
            method: 'POST',
            data: {
                product_ids: selectedProducts,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showBulkAffiliateModal(response.affiliate_links);
                    showNotification('success', `Generated ${selectedProducts.length} affiliate links!`);
                }
            },
            error: function() {
                showNotification('error', 'Failed to generate affiliate links');
            },
            complete: function() {
                btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Check if favorites list is empty after removals
    function checkEmptyState() {
        setTimeout(function() {
            if ($('.favorite-product-card').length === 0) {
                location.reload();
            }
        }, 500);
    }
});

// Show affiliate link modal
function showAffiliateModal(link) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Your Affiliate Link',
            html: `
                <div class="input-group">
                    <input type="text" class="form-control" value="${link}" id="affiliateLinkInput" readonly>
                    <button class="btn btn-primary" onclick="copyAffiliateLink()">Copy</button>
                </div>
                <div class="mt-3">
                    <button class="btn btn-success btn-sm" onclick="shareOnSocial('facebook', '${link}')">Facebook</button>
                    <button class="btn btn-info btn-sm" onclick="shareOnSocial('twitter', '${link}')">Twitter</button>
                    <button class="btn btn-success btn-sm" onclick="shareOnSocial('whatsapp', '${link}')">WhatsApp</button>
                </div>
            `,
            showConfirmButton: false,
            width: '500px'
        });
    }
}

// Show bulk affiliate links modal
function showBulkAffiliateModal(links) {
    let linksHtml = '';
    links.forEach(function(item) {
        linksHtml += `
            <div class="mb-2">
                <small class="text-muted">${item.product_name}</small>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" value="${item.link}" readonly>
                    <button class="btn btn-outline-primary btn-sm" onclick="copyToClipboard('${item.link}')">Copy</button>
                </div>
            </div>
        `;
    });

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Bulk Affiliate Links',
            html: `<div style="max-height: 400px; overflow-y: auto;">${linksHtml}</div>`,
            showConfirmButton: false,
            width: '600px'
        });
    }
}

// Copy affiliate link
function copyAffiliateLink() {
    const input = document.getElementById('affiliateLinkInput');
    input.select();
    input.setSelectionRange(0, 99999);
    document.execCommand('copy');
    showNotification('success', 'Link copied to clipboard!');
}

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showNotification('success', 'Link copied!');
    });
}

// Social sharing
function shareOnSocial(platform, link) {
    const encodedLink = encodeURIComponent(link);
    let url = '';
    
    switch(platform) {
        case 'facebook':
            url = `https://www.facebook.com/sharer/sharer.php?u=${encodedLink}`;
            break;
        case 'twitter':
            url = `https://twitter.com/intent/tweet?url=${encodedLink}&text=Check out this amazing product!`;
            break;
        case 'whatsapp':
            url = `https://wa.me/?text=Check out this product: ${encodedLink}`;
            break;
    }
    
    if (url) {
        window.open(url, '_blank');
    }
}

// Show notification
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
        alert(message);
    }
}
</script>
@endpush
