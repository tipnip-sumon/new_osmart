@extends('layouts.app')

@section('title', 'Shop - Grid View')
@section('description', 'Browse all products in grid view with filters and search')

@push('styles')
<style>
/* Grid specific styles */
.shop-filters {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.filter-section {
    margin-bottom: 1.5rem;
}

.filter-section:last-child {
    margin-bottom: 0;
}

.filter-section h6 {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: #374151;
}

.product-grid-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    overflow: hidden;
    height: 100%;
}

.product-grid-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-color: #6366f1;
}

.product-image-container {
    position: relative;
    overflow: hidden;
    background: #f8f9fa;
}

.product-image {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-grid-card:hover .product-image {
    transform: scale(1.05);
}

.product-badge {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    z-index: 2;
}

.product-actions {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    z-index: 2;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-grid-card:hover .product-actions {
    opacity: 1;
}

.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: rgba(255,255,255,0.9);
    color: #6b7280;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-btn:hover {
    background: #6366f1;
    color: white;
    transform: scale(1.1);
}

.product-content {
    padding: 1.25rem;
}

.product-category {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
}

.product-title {
    font-size: 1rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.75rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-title:hover {
    color: #6366f1;
}

.product-rating {
    display: flex;
    align-items: center;
    margin-bottom: 0.75rem;
    font-size: 0.875rem;
}

.rating-stars {
    color: #fbbf24;
    margin-right: 0.5rem;
}

.rating-count {
    color: #6b7280;
}

.product-price {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.current-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin-right: 0.5rem;
}

.original-price {
    font-size: 1rem;
    color: #6b7280;
    text-decoration: line-through;
}

.discount-badge {
    background: #ef4444;
    color: white;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    margin-left: 0.5rem;
}

.add-to-cart-btn {
    width: 100%;
    background: #6366f1;
    border: none;
    color: white;
    padding: 0.75rem;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.add-to-cart-btn:hover {
    background: #4f46e5;
    transform: translateY(-1px);
}

.view-mode-toggle {
    display: flex;
    border-radius: 0.5rem;
    overflow: hidden;
    border: 1px solid #e5e7eb;
}

.view-btn {
    padding: 0.5rem 1rem;
    border: none;
    background: white;
    color: #6b7280;
    transition: all 0.3s ease;
}

.view-btn.active {
    background: #6366f1;
    color: white;
}

.view-btn:hover:not(.active) {
    background: #f3f4f6;
}

.results-info {
    color: #6b7280;
    font-size: 0.875rem;
}

/* Mobile responsive styles */
@media (max-width: 767.98px) {
    .shop-filters {
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .filter-section {
        margin-bottom: 1rem;
    }
    
    .product-image {
        height: 200px;
    }
    
    .product-content {
        padding: 1rem;
    }
    
    .current-price {
        font-size: 1.125rem;
    }
    
    .form-select, .form-control {
        font-size: 0.875rem;
    }
    
    .btn {
        font-size: 0.875rem;
    }
    
    /* Hide advanced filters on mobile */
    .advanced-filters {
        display: none;
    }
    
    /* Mobile pagination */
    .pagination {
        display: none !important;
    }
    
    .mobile-pagination .btn {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
    }
}

@media (max-width: 575.98px) {
    .product-image {
        height: 180px;
    }
    
    .product-title {
        font-size: 0.9rem;
    }
    
    .current-price {
        font-size: 1rem;
    }
}

/* Desktop pagination */
@media (min-width: 768px) {
    .mobile-pagination {
        display: none !important;
    }
    
    .pagination {
        display: flex !important;
        justify-content: center;
        margin: 0;
    }
    
    .pagination .page-link {
        color: #6366f1;
        border-color: #e5e7eb;
        padding: 0.5rem 0.75rem;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #6366f1;
        border-color: #6366f1;
    }
}

/* Loading states */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}
</style>
@endpush

@section('content')
<div class="container-xl py-4">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">Shop</h2>
                <div class="page-pretitle">
                    Discover amazing products from our collection
                </div>
            </div>
            <div class="col-auto">
                <div class="view-mode-toggle">
                    <a href="{{ route('shop.grid') }}" class="view-btn active">
                        <i class="ti ti-grid-dots me-1"></i> Grid
                    </a>
                    <a href="{{ route('shop.list') }}" class="view-btn">
                        <i class="ti ti-list me-1"></i> List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="shop-filters">
        <form method="GET" action="{{ route('shop.grid') }}" id="filterForm">
            <div class="row g-3">
                <!-- Search -->
                <div class="col-md-3">
                    <div class="filter-section">
                        <h6>Search Products</h6>
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search products...">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="ti ti-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="col-md-2">
                    <div class="filter-section">
                        <h6>Category</h6>
                        <select name="category" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Brand Filter -->
                <div class="col-md-2">
                    <div class="filter-section">
                        <h6>Brand</h6>
                        <select name="brand" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" 
                                        {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="col-md-2">
                    <div class="filter-section">
                        <h6>Price Range</h6>
                        <div class="row g-1">
                            <div class="col-6">
                                <input type="number" 
                                       class="form-control form-control-sm" 
                                       name="price_min" 
                                       value="{{ request('price_min') }}" 
                                       placeholder="Min"
                                       min="0">
                            </div>
                            <div class="col-6">
                                <input type="number" 
                                       class="form-control form-control-sm" 
                                       name="price_max" 
                                       value="{{ request('price_max') }}" 
                                       placeholder="Max"
                                       min="0">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sort By -->
                <div class="col-md-2">
                    <div class="filter-section">
                        <h6>Sort By</h6>
                        <select name="sort" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating</option>
                        </select>
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="col-md-1">
                    <div class="filter-section">
                        <h6>&nbsp;</h6>
                        <a href="{{ route('shop.grid') }}" class="btn btn-outline-secondary w-100">
                            <i class="ti ti-refresh"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Advanced Filters (Desktop Only) -->
            <div class="row g-3 mt-2 advanced-filters">
                <div class="col-md-3">
                    <div class="form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               name="in_stock" 
                               value="1" 
                               {{ request('in_stock') ? 'checked' : '' }}
                               onchange="document.getElementById('filterForm').submit()">
                        <label class="form-check-label">
                            In Stock Only
                        </label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               name="featured" 
                               value="1" 
                               {{ request('featured') ? 'checked' : '' }}
                               onchange="document.getElementById('filterForm').submit()">
                        <label class="form-check-label">
                            Featured Only
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Info -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="results-info">
            Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} 
            of {{ $products->total() }} products
        </div>
        <div class="d-none d-md-block">
            <small class="text-muted">
                {{ $products->count() }} products on this page
            </small>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row g-4" id="productsGrid">
        @forelse($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card product-grid-card h-100">
                    <div class="product-image-container">
                        @php
                            $productImageUrl = '';
                            
                            // Handle images array first (for products)
                            if (isset($product->images) && is_array($product->images) && !empty($product->images)) {
                                $firstImage = $product->images[0];
                                if (is_array($firstImage) && isset($firstImage['sizes'])) {
                                    if (isset($firstImage['sizes']['medium']['storage_url'])) {
                                        $productImageUrl = $firstImage['sizes']['medium']['storage_url'];
                                    } elseif (isset($firstImage['sizes']['original']['storage_url'])) {
                                        $productImageUrl = $firstImage['sizes']['original']['storage_url'];
                                    } elseif (isset($firstImage['sizes']['large']['storage_url'])) {
                                        $productImageUrl = $firstImage['sizes']['large']['storage_url'];
                                    }
                                }
                            }
                            
                            // Handle complex image_data structure
                            if (empty($productImageUrl) && isset($product->image_data) && $product->image_data) {
                                $imageData = is_string($product->image_data) ? json_decode($product->image_data, true) : $product->image_data;
                                if (is_array($imageData)) {
                                    if (isset($imageData['sizes']['medium']['storage_url'])) {
                                        $productImageUrl = $imageData['sizes']['medium']['storage_url'];
                                    } elseif (isset($imageData['sizes']['original']['storage_url'])) {
                                        $productImageUrl = $imageData['sizes']['original']['storage_url'];
                                    } elseif (isset($imageData['sizes']['large']['storage_url'])) {
                                        $productImageUrl = $imageData['sizes']['large']['storage_url'];
                                    }
                                }
                            }
                            
                            // Handle string-based images array
                            if (empty($productImageUrl) && $product->images) {
                                $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                                if (is_array($images) && !empty($images)) {
                                    $firstImage = $images[0];
                                    if (is_string($firstImage)) {
                                        $productImageUrl = str_starts_with($firstImage, 'http') ? $firstImage : asset('storage/' . $firstImage);
                                    }
                                }
                            }
                            
                            // Fallback to simple image field
                            if (empty($productImageUrl) && $product->image) {
                                $productImageUrl = str_starts_with($product->image, 'http') ? 
                                    $product->image : 
                                    asset('storage/' . $product->image);
                            }
                            
                            // Final fallback to default image
                            if (empty($productImageUrl)) {
                                $productImageUrl = asset('assets/img/product/1.png');
                            }
                        @endphp
                        
                        <img src="{{ $productImageUrl }}" 
                             alt="{{ $product->name }}" 
                             class="product-image"
                             loading="lazy"
                             onerror="this.src='{{ asset('assets/img/product/1.png') }}'; console.log('Fallback image loaded for: {{ $product->name }}');"
                             onload="console.log('Image loaded successfully: {{ $productImageUrl }}');"
                             >
                        
                        <!-- Product Badges -->
                        <div class="product-badge">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                @php
                                    $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                                @endphp
                                <span class="badge bg-danger">-{{ $discount }}%</span>
                            @endif
                            
                            @if($product->is_featured)
                                <span class="badge bg-warning text-dark">Featured</span>
                            @endif
                            
                            @if(!$product->in_stock || $product->stock_quantity <= 0)
                                <span class="badge bg-secondary">Out of Stock</span>
                            @elseif($product->stock_quantity <= 5)
                                <span class="badge bg-warning text-dark">Low Stock</span>
                            @endif
                        </div>

                        <!-- Product Actions -->
                        <div class="product-actions">
                            <button class="action-btn" 
                                    onclick="addToWishlist({{ $product->id }})"
                                    title="Add to Wishlist">
                                <i class="ti ti-heart"></i>
                            </button>
                            <button class="action-btn" 
                                    onclick="quickView({{ $product->id }})"
                                    title="Quick View">
                                <i class="ti ti-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="product-content">
                        <!-- Category -->
                        @if($product->category)
                            <div class="product-category">
                                {{ $product->category->name }}
                            </div>
                        @endif

                        <!-- Product Title -->
                        <h5 class="product-title">
                            <a href="{{ route('products.show', $product->slug ?? $product->id) }}">
                                {{ $product->name }}
                            </a>
                        </h5>

                        <!-- Brand -->
                        @if($product->brand)
                            <div class="product-brand mb-2">
                                <small class="text-muted">{{ $product->brand->name }}</small>
                            </div>
                        @endif

                        <!-- Rating -->
                        <div class="product-rating">
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= ($product->average_rating ?? 0))
                                        <i class="ti ti-star-filled"></i>
                                    @else
                                        <i class="ti ti-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="rating-count">
                                ({{ $product->reviews_count ?? 0 }})
                            </span>
                        </div>

                        <!-- Price -->
                        <div class="product-price">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="current-price">৳{{ number_format($product->sale_price, 2) }}</span>
                                <span class="original-price">৳{{ number_format($product->price, 2) }}</span>
                            @else
                                <span class="current-price">৳{{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>

                        <!-- Add to Cart Button -->
                        @if($product->in_stock && $product->stock_quantity > 0)
                            <button class="add-to-cart-btn" 
                                    onclick="addToCart({{ $product->id }})">
                                <i class="ti ti-shopping-cart me-2"></i>Add to Cart
                            </button>
                        @else
                            <button class="add-to-cart-btn" disabled>
                                <i class="ti ti-x me-2"></i>Out of Stock
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <img src="{{ asset('assets/img/no-products.svg') }}" 
                         alt="No products found" 
                         style="width: 200px; opacity: 0.5;">
                    <h4 class="mt-3">No products found</h4>
                    <p class="text-muted">Try adjusting your filters or search terms</p>
                    <a href="{{ route('shop.grid') }}" class="btn btn-primary">
                        <i class="ti ti-refresh me-2"></i>Reset Filters
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="mt-5">
            <nav aria-label="Products pagination">
                <!-- Mobile: Simple prev/next buttons -->
                <div class="d-md-none mobile-pagination">
                    <div class="row g-2">
                        <div class="col-6">
                            @if ($products->onFirstPage())
                                <span class="btn btn-outline-secondary disabled w-100">
                                    <i class="ti ti-chevron-left"></i> Previous
                                </span>
                            @else
                                <a href="{{ $products->appends(request()->query())->previousPageUrl() }}" 
                                   class="btn btn-outline-primary w-100">
                                    <i class="ti ti-chevron-left"></i> Previous
                                </a>
                            @endif
                        </div>
                        <div class="col-6">
                            @if ($products->hasMorePages())
                                <a href="{{ $products->appends(request()->query())->nextPageUrl() }}" 
                                   class="btn btn-outline-primary w-100">
                                    Next <i class="ti ti-chevron-right"></i>
                                </a>
                            @else
                                <span class="btn btn-outline-secondary disabled w-100">
                                    Next <i class="ti ti-chevron-right"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <small class="text-muted">
                            Page {{ $products->currentPage() }} of {{ $products->lastPage() }}
                        </small>
                    </div>
                </div>
                
                <!-- Desktop: Full pagination -->
                <div class="d-none d-md-block">
                    <div class="d-flex justify-content-center">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </div>
            </nav>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Add to Cart Function
function addToCart(productId) {
    // Add loading state to button
    const addButton = event.target.closest('.add-to-cart-btn');
    const originalText = addButton.innerHTML;
    addButton.innerHTML = '<i class="ti ti-loader-2 rotating me-2"></i>Adding...';
    addButton.disabled = true;

    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showToast(data.message || 'Product added to cart!', 'success');
            // Update cart count using global function
            if (window.updateCartCount) {
                window.updateCartCount();
            }
            
            // Animate cart icons
            animateCartIcons();
            
            // Update button text temporarily
            addButton.innerHTML = '<i class="ti ti-check me-2"></i>Added!';
            addButton.classList.add('bg-success');
            
            setTimeout(() => {
                addButton.innerHTML = originalText;
                addButton.classList.remove('bg-success');
                addButton.disabled = false;
            }, 2000);
        } else {
            showToast(data.message || 'Error adding to cart', 'error');
            addButton.innerHTML = originalText;
            addButton.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error adding to cart', 'error');
        addButton.innerHTML = originalText;
        addButton.disabled = false;
    });
}

// Add to Wishlist Function
function addToWishlist(productId) {
    const wishlistButton = event.target.closest('.action-btn');
    const heartIcon = wishlistButton.querySelector('i');
    
    fetch('{{ route("wishlist.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            // Toggle heart icon
            heartIcon.classList.toggle('ti-heart');
            heartIcon.classList.toggle('ti-heart-filled');
            wishlistButton.classList.toggle('text-danger');
        } else {
            showToast(data.message || 'Error adding to wishlist', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error adding to wishlist', 'error');
    });
}

// Quick View Function
function quickView(productId) {
    // Implement quick view modal
    showToast('Quick view coming soon!', 'info');
}

// Animate Cart Icons
function animateCartIcons() {
    // Animate header cart icon
    const headerCartIcon = document.querySelector('.header-cart-count');
    if (headerCartIcon) {
        headerCartIcon.parentElement.querySelector('i').classList.add('animate__animated', 'animate__pulse');
        setTimeout(() => {
            headerCartIcon.parentElement.querySelector('i').classList.remove('animate__animated', 'animate__pulse');
        }, 1000);
    }
    
    // Animate footer cart icon (if visible)
    const footerCartIcon = document.querySelector('#cartCountFooter');
    if (footerCartIcon) {
        footerCartIcon.closest('a').querySelector('i').classList.add('animate__animated', 'animate__pulse');
        setTimeout(() => {
            footerCartIcon.closest('a').querySelector('i').classList.remove('animate__animated', 'animate__pulse');
        }, 1000);
    }
}

// Toast Notification
function showToast(message, type = 'info') {
    // Remove existing toasts
    document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed toast-notification`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="ti ti-${type === 'success' ? 'check' : type === 'error' ? 'x' : 'info-circle'} me-2"></i>
            ${message}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 4000);
}

// Filter form improvements
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit price range on change with debounce
    let priceTimeout;
    const priceInputs = document.querySelectorAll('input[name="price_min"], input[name="price_max"]');
    
    priceInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(priceTimeout);
            priceTimeout = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 1000);
        });
    });
    
    // Loading state for filter submissions
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            const productsGrid = document.getElementById('productsGrid');
            if (productsGrid) {
                productsGrid.classList.add('loading');
                
                // Show loading skeleton
                productsGrid.innerHTML = Array(8).fill().map(() => `
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card product-grid-card h-100">
                            <div class="skeleton" style="height: 250px;"></div>
                            <div class="card-body">
                                <div class="skeleton" style="height: 20px; width: 60%; margin-bottom: 10px;"></div>
                                <div class="skeleton" style="height: 20px; width: 80%; margin-bottom: 10px;"></div>
                                <div class="skeleton" style="height: 30px; width: 50%; margin-bottom: 15px;"></div>
                                <div class="skeleton" style="height: 40px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        });
    }
    
    // Initialize cart count on page load
    if (window.updateCartCount) {
        window.updateCartCount();
    }
    
    // Product card hover effects
    const productCards = document.querySelectorAll('.product-grid-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

// CSS for rotating loader
const shopGridStyle = document.createElement('style');
shopGridStyle.textContent = `
    .rotating {
        animation: rotate 1s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .loading {
        opacity: 0.7;
        pointer-events: none;
    }
`;
document.head.appendChild(shopGridStyle);
</script>
@endpush
