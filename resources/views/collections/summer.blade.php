@extends('layouts.ecomus')

@section('title', 'Summer Collection - ' . config('app.name'))
@section('description', 'Discover the hottest trends and must-have styles of the season in our Summer Collection')

@push('styles')
<style>
/* Summer Collection Styles */
.collection-hero {
    background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%);
    padding: 80px 0;
    position: relative;
    overflow: hidden;
}

.collection-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('{{ asset("assets/ecomus/images/slider/fashion-slideshow-01.jpg") }}') center/cover;
    opacity: 0.2;
    z-index: 1;
}

.collection-hero-content {
    position: relative;
    z-index: 2;
}

.collection-hero h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #333;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.collection-hero p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    color: #666;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.collection-stats {
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(10px);
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    margin-top: 2rem;
}

.collection-filters {
    background: #fff;
    padding: 2rem 0;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 2rem;
}

.filter-group {
    margin-bottom: 1rem;
}

.filter-group:last-child {
    margin-bottom: 0;
}

.filter-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
}

.filter-select {
    border: 2px solid #f0f0f0;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.filter-select:focus {
    border-color: #ff6b6b;
    box-shadow: 0 0 0 3px rgba(255,107,107,0.1);
}

/* Product Grid Styles - matching home page */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.card-product {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.card-product:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.15);
}

.card-product-wrapper {
    position: relative;
    overflow: hidden;
}

.card-product-wrapper img {
    width: 100%;
    height: 280px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.card-product:hover .card-product-wrapper img {
    transform: scale(1.05);
}

.card-product-info {
    padding: 1.5rem;
    text-align: center;
}

.card-product-info .title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
    line-height: 1.4;
}

.card-product-info .title a {
    text-decoration: none;
    color: inherit;
    transition: color 0.3s ease;
}

.card-product-info .title a:hover {
    color: #ff6b6b;
}

.card-product-info .price {
    font-size: 1.2rem;
    font-weight: 700;
    color: #ff6b6b;
    margin-bottom: 1rem;
}

.card-product-info .compare-at-price {
    font-size: 1rem;
    color: #999;
    text-decoration: line-through;
    margin-left: 0.5rem;
}

.product-badges {
    position: absolute;
    top: 15px;
    left: 15px;
    z-index: 5;
}

.product-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
}

.badge-sale {
    background: #ff6b6b;
    color: white;
}

.badge-new {
    background: #4ecdc4;
    color: white;
}

.product-actions {
    position: absolute;
    top: 15px;
    right: 15px;
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.3s ease;
}

.card-product:hover .product-actions {
    opacity: 1;
    transform: translateX(0);
}

.action-btn {
    display: block;
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(10px);
    border: none;
    border-radius: 50%;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.action-btn:hover {
    background: #ff6b6b;
    color: white;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 3rem;
}

.pagination .page-link {
    border: 2px solid #f0f0f0;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    margin: 0 0.25rem;
    color: #666;
    text-decoration: none;
    transition: all 0.3s ease;
}

.pagination .page-link:hover,
.pagination .page-item.active .page-link {
    background: #ff6b6b;
    border-color: #ff6b6b;
    color: white;
}

/* Loading State */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.loading-overlay.active {
    opacity: 1;
    visibility: visible;
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #f0f0f0;
    border-top: 4px solid #ff6b6b;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .collection-hero h1 {
        font-size: 2.5rem;
    }
    
    .collection-hero p {
        font-size: 1rem;
    }
    
    .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .collection-filters {
        padding: 1rem 0;
    }
}

@media (max-width: 576px) {
    .collection-hero {
        padding: 60px 0;
    }
    
    .collection-hero h1 {
        font-size: 2rem;
    }
    
    .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
}
</style>
@endpush

@section('content')
@php
// Helper function for product images - same as home page
function getProductImageSrc($product, $defaultImage = 'assets/ecomus/images/products/default-product.jpg') {
    if (isset($product->images) && $product->images) {
        $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
        
        if (is_array($images) && !empty($images)) {
            $image = $images[0];
            
            $legacyImageUrl = '';
            if (is_string($image)) {
                $legacyImageUrl = asset('storage/' . $image);
            } elseif (is_array($image) && isset($image['url']) && is_string($image['url'])) {
                $legacyImageUrl = $image['url'];
            } elseif (is_array($image) && isset($image['path']) && is_string($image['path'])) {
                $legacyImageUrl = asset('storage/' . $image['path']);
            } else {
                $legacyImageUrl = asset($defaultImage);
            }
            
            return $legacyImageUrl;
        }
    }
    
    $productImage = $product->image;
    if ($productImage && $productImage !== 'products/product1.jpg') {
        return str_starts_with($productImage, 'http') ? $productImage : asset('storage/' . $productImage);
    }
    
    return asset($defaultImage);
}
@endphp

<!-- Collection Hero Section -->
<section class="collection-hero">
    <div class="collection-hero-content">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h1>Summer Collection 2025</h1>
                    <p class="lead">Discover the hottest trends and must-have styles of the season. From breezy sundresses to comfortable swimwear, find everything you need for the perfect summer look.</p>
                    
                    <div class="collection-stats">
                        <div class="row">
                            <div class="col-md-4">
                                <h3 class="mb-1">{{ $products->total() ?? 0 }}</h3>
                                <p class="mb-0 text-muted">Products</p>
                            </div>
                            <div class="col-md-4">
                                <h3 class="mb-1">New Arrivals</h3>
                                <p class="mb-0 text-muted">Fresh Styles</p>
                            </div>
                            <div class="col-md-4">
                                <h3 class="mb-1">Up to 50% Off</h3>
                                <p class="mb-0 text-muted">Summer Sale</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filters Section -->
<section class="collection-filters">
    <div class="container">
        <form id="filter-form" method="GET">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <div class="filter-group">
                        <label class="filter-label">Sort by</label>
                        <select name="sort" class="form-control filter-select" id="sort-select">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="filter-group">
                        <label class="filter-label">Show</label>
                        <select name="per_page" class="form-control filter-select" id="per-page-select">
                            <option value="12" {{ request('per_page') == '12' ? 'selected' : '' }}>12 per page</option>
                            <option value="24" {{ request('per_page') == '24' ? 'selected' : '' }}>24 per page</option>
                            <option value="36" {{ request('per_page') == '36' ? 'selected' : '' }}>36 per page</option>
                            <option value="48" {{ request('per_page') == '48' ? 'selected' : '' }}>48 per page</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="filter-group text-md-end">
                        <p class="mb-0 text-muted">
                            Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() ?? 0 }} results
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Products Grid -->
<section class="flat-spacing-1">
    <div class="container">
        <div id="products-container">
            @if(isset($products) && $products->count() > 0)
            <div class="product-grid">
                @foreach($products as $product)
                <div class="card-product">
                    <div class="card-product-wrapper">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <img src="{{ getProductImageSrc($product) }}" 
                                 alt="{{ $product->name }}"
                                 onerror="this.src='{{ asset('assets/ecomus/images/products/default-product.jpg') }}'; this.onerror=null;">
                        </a>
                        
                        <!-- Product Badges -->
                        <div class="product-badges">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                @php
                                    $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                                @endphp
                                <span class="product-badge badge-sale">{{ $discount }}% OFF</span>
                            @endif
                            
                            @if($product->created_at && $product->created_at->gt(now()->subDays(30)))
                                <span class="product-badge badge-new">New</span>
                            @endif
                        </div>
                        
                        <!-- Product Actions -->
                        <div class="product-actions">
                            <button class="action-btn" 
                                    data-action="quick-view" 
                                    data-product-id="{{ $product->id }}"
                                    data-product-slug="{{ $product->slug }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#quick_view"
                                    title="Quick View">
                                <i class="icon-view"></i>
                            </button>
                            <button class="action-btn" 
                                    onclick="addToWishlist({{ $product->id }})"
                                    title="Add to Wishlist">
                                <i class="icon-heart"></i>
                            </button>
                            <button class="action-btn" 
                                    onclick="addToCart({{ $product->id }})"
                                    title="Add to Cart">
                                <i class="icon-shopping-bag"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-product-info">
                        <h6 class="title">
                            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                        </h6>
                        <div class="price">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="price-on-sale">${{ number_format($product->sale_price, 2) }}</span>
                                <span class="compare-at-price">${{ number_format($product->price, 2) }}</span>
                            @else
                                <span class="price-regular">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
            <div class="pagination-wrapper">
                {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
            @endif
            @else
            <div class="text-center py-5">
                <h4 class="mb-3">No products found in Summer Collection</h4>
                <p class="text-muted mb-4">We're working on adding amazing summer products. Check back soon!</p>
                <a href="{{ route('home') }}" class="tf-btn animate-hover-btn">
                    <span>Continue Shopping</span>
                </a>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loading-overlay">
    <div class="loading-spinner"></div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle filter changes
    $('#sort-select, #per-page-select').on('change', function() {
        $('#filter-form').submit();
    });

    // Show loading overlay during navigation
    $('#filter-form').on('submit', function() {
        $('#loading-overlay').addClass('active');
    });

    // Add to Cart functionality
    window.addToCart = function(productId) {
        // Show loading
        $('#loading-overlay').addClass('active');
        
        $.ajax({
            url: '/cart/add',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: 1,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Update cart count
                    if (typeof window.updateCartCount === 'function') {
                        window.updateCartCount();
                    }
                    
                    // Show success message
                    toastr.success('Product added to cart successfully!');
                } else {
                    toastr.error(response.message || 'Failed to add product to cart');
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    window.location.href = '/login';
                } else {
                    toastr.error('Failed to add product to cart');
                }
            },
            complete: function() {
                $('#loading-overlay').removeClass('active');
            }
        });
    };

    // Add to Wishlist functionality
    window.addToWishlist = function(productId) {
        $('#loading-overlay').addClass('active');
        
        $.ajax({
            url: '/wishlist/add',
            method: 'POST',
            data: {
                product_id: productId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Update wishlist count
                    if (typeof window.updateWishlistCount === 'function') {
                        window.updateWishlistCount();
                    }
                    
                    toastr.success('Product added to wishlist successfully!');
                } else {
                    toastr.error(response.message || 'Failed to add product to wishlist');
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    window.location.href = '/login';
                } else {
                    toastr.error('Failed to add product to wishlist');
                }
            },
            complete: function() {
                $('#loading-overlay').removeClass('active');
            }
        });
    };

    // Quick View Modal - reuse from home page
    $(document).on('click', '[data-action="quick-view"]', function(e) {
        const button = $(this);
        const productId = button.data('product-id');
        const productSlug = button.data('product-slug');
        
        // Store product info in modal
        const modal = $('#quick_view');
        modal.data('product-id', productId).data('product-slug', productSlug || '');
    });
});
</script>
@endpush