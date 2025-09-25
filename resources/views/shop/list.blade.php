@extends('layouts.app')

@section('title', 'Shop - List View')
@section('description', 'Browse all products in list view with detailed information')

@push('styles')
<style>
/* Custom responsive styles for shop list */
@media (max-width: 767.98px) {
    .product-card-mobile {
        text-align: center !important;
    }
    
    .product-card-mobile .btn {
        width: 100% !important;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
    }
    
    .product-card-mobile .btn:last-child {
        margin-bottom: 0;
    }
    
    .badge {
        margin-bottom: 0.25rem;
        margin-right: 0.25rem !important;
        font-size: 0.75rem;
    }
    
    .product-rating {
        text-align: center;
        font-size: 0.875rem;
    }
    
    /* Mobile pagination styles */
    .mobile-pagination {
        padding: 0 0.5rem;
    }
    
    .mobile-pagination .btn {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .mobile-pagination .btn i {
        font-size: 0.875rem;
    }
    
    /* Hide Laravel default pagination on mobile */
    .pagination {
        display: none !important;
    }
    
    /* Responsive filters */
    .form-select-sm,
    .input-group-sm .form-control {
        font-size: 0.875rem;
    }
    
    /* Card spacing */
    .card {
        margin-bottom: 1rem !important;
    }
    
    /* Price display */
    .h5 {
        font-size: 1.1rem;
    }
}

@media (max-width: 575.98px) {
    .h3 {
        font-size: 1.4rem;
    }
    
    .container {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    /* Extra small screen pagination */
    .mobile-pagination .btn {
        font-size: 0.8rem;
        padding: 0.375rem 0.5rem;
    }
    
    .mobile-pagination .btn i {
        font-size: 0.75rem;
    }
    
    .card {
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 0.75rem !important;
    }
    
    .card-body {
        padding: 1rem 0.75rem;
    }
    
    /* Product image adjustments */
    .col-lg-3.col-md-4.col-12 img {
        height: 120px !important;
        border-radius: 0.375rem;
    }
    
    /* Typography adjustments */
    .card-title {
        font-size: 1rem;
        line-height: 1.3;
    }
    
    .text-muted {
        font-size: 0.85rem;
    }
    
    /* Badge adjustments */
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.4rem;
    }
    
    /* Button adjustments */
    .btn-sm {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
    }
    
    /* Filter labels */
    .form-label {
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
    }
}

@media (max-width: 480px) {
    /* Ultra small screens */
    .mobile-pagination .btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.4rem;
    }
    
    .mobile-pagination .btn span {
        display: none;
    }
    
    .mobile-pagination .btn i {
        margin: 0;
    }
    
    .h5 {
        font-size: 1rem;
    }
    
    .card-title {
        font-size: 0.95rem;
    }
}

/* Fix for Bootstrap pagination on desktop */
@media (min-width: 768px) {
    /* Show Laravel default pagination on desktop */
    .pagination {
        display: flex !important;
        margin-bottom: 0;
        justify-content: center;
    }
    
    .pagination .page-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
    
    /* Hide mobile pagination on desktop */
    .mobile-pagination {
        display: none !important;
    }
    
    /* Desktop responsive improvements */
    .container {
        max-width: 1140px;
    }
    
    /* Product card desktop layout */
    .product-card-desktop {
        display: flex;
        align-items: center;
        min-height: 180px;
    }
    
    .product-card-desktop .product-image {
        flex: 0 0 200px;
        max-width: 200px;
    }
    
    .product-card-desktop .product-content {
        flex: 1;
        padding: 0 1.5rem;
    }
    
    .product-card-desktop .product-actions {
        flex: 0 0 180px;
        max-width: 180px;
    }
}

@media (min-width: 992px) {
    /* Large desktop improvements */
    .container {
        max-width: 1200px;
    }
    
    /* Filter section improvements */
    .filter-section {
        background: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 2rem;
    }
    
    /* Product cards spacing */
    .product-list-item {
        margin-bottom: 1.5rem;
    }
    
    .product-list-item .card {
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .product-list-item .card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transform: translateY(-2px);
        border-color: #007bff;
    }
    
    /* Enhanced typography */
    .card-title a {
        font-size: 1.25rem;
        font-weight: 600;
        color: #212529;
        transition: color 0.2s ease;
    }
    
    .card-title a:hover {
        color: #007bff;
    }
    
    /* Better badge spacing */
    .badge {
        margin-right: 0.5rem;
        margin-bottom: 0.25rem;
    }
    
    /* Price display enhancements */
    .price-section {
        text-align: right;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 0.5rem;
    }
    
    .price-section .h5 {
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    /* Button improvements */
    .btn-actions {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .btn-actions .btn {
        width: 100%;
        padding: 0.75rem;
        font-weight: 500;
    }
}

@media (min-width: 1200px) {
    /* Extra large screens */
    .container {
        max-width: 1320px;
    }
    
    /* Wider product layout */
    .product-card-desktop .product-image {
        flex: 0 0 220px;
        max-width: 220px;
    }
    
    .product-card-desktop .product-actions {
        flex: 0 0 200px;
        max-width: 200px;
    }
    
    /* Enhanced filter section */
    .filter-section {
        padding: 1.5rem;
    }
    
    .filter-section .form-select,
    .filter-section .form-control {
        padding: 0.75rem;
        font-size: 1rem;
    }
}

@media (min-width: 1400px) {
    /* Ultra wide screens */
    .container {
        max-width: 1400px;
    }
    
    /* Larger product images */
    .product-card-desktop .product-image img {
        height: 200px !important;
    }
    
    /* More spacious layout */
    .product-list-item {
        margin-bottom: 2rem;
    }
    
    .card-body {
        padding: 2rem;
    }
}

/* General improvements */
.img-fluid {
    transition: transform 0.2s ease;
}

.img-fluid:hover {
    transform: scale(1.02);
}

.btn {
    transition: all 0.2s ease;
}

.card {
    transition: box-shadow 0.2s ease;
}

.card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                <div class="mb-3 mb-md-0">
                    <h1 class="h3 mb-2">Shop - List View</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Shop List</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('shop.grid') }}" class="btn btn-outline-primary btn-sm">
                        <i class="ti ti-grid-dots"></i> 
                        <span class="d-none d-sm-inline">Grid View</span>
                    </a>
                </div>
            </div>

            <!-- Filters and Sort -->
            <div class="row mb-4 g-2 filter-section">
                <div class="col-lg-3 col-md-6 col-12">
                    <label class="form-label d-block d-lg-none small text-muted">Category</label>
                    <select class="form-select form-select-sm" id="categoryFilter" name="category">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <label class="form-label d-block d-lg-none small text-muted">Price Range</label>
                    <select class="form-select form-select-sm" id="priceFilter" name="price_range">
                        <option value="">All Prices</option>
                        @foreach($priceRanges as $range)
                            <option value="{{ $range['min'] }}-{{ $range['max'] ?? 'max' }}" 
                                {{ request('price_min') == $range['min'] ? 'selected' : '' }}>
                                {{ $range['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <label class="form-label d-block d-lg-none small text-muted">Sort By</label>
                    <select class="form-select form-select-sm" id="sortBy" name="sort">
                        <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                        <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <label class="form-label d-block d-lg-none small text-muted">Search</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Search products..." 
                               id="searchInput" name="search" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                            <i class="ti ti-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Products List -->
            <div class="row" id="productsList">
                @forelse($products as $product)
                    <div class="col-12 mb-3 product-list-item">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row align-items-center product-card-desktop">
                                    <div class="col-xl-2 col-lg-3 col-md-4 col-12 mb-3 mb-md-0 product-image">
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
                                             class="img-fluid rounded w-100" 
                                             style="height: 150px; object-fit: cover;"
                                             onerror="this.src='{{ asset('assets/img/product/1.png') }}'; console.log('Fallback image loaded for: {{ $product->name }}');"
                                             onload="console.log('Image loaded successfully: {{ $productImageUrl }}');"
                                             >
                                    </div>
                                    <div class="col-xl-7 col-lg-6 col-md-5 col-12 mb-3 mb-md-0 product-content">
                                        <h5 class="card-title mb-2">
                                            <a href="{{ route('products.show', $product->slug ?? $product->id) }}" 
                                               class="text-decoration-none">{{ $product->name }}</a>
                                        </h5>
                                        <p class="text-muted mb-2 d-none d-lg-block">{{ Str::limit($product->short_description ?? $product->description, 120) }}</p>
                                        <p class="text-muted mb-2 d-none d-md-block d-lg-none">{{ Str::limit($product->short_description ?? $product->description, 80) }}</p>
                                        <p class="text-muted mb-2 d-block d-md-none">{{ Str::limit($product->short_description ?? $product->description, 60) }}</p>
                                        
                                        <div class="mb-2 d-flex flex-wrap gap-1">
                                            @if($product->category)
                                                <span class="badge bg-primary">{{ $product->category->name }}</span>
                                            @endif
                                            @if($product->brand)
                                                <span class="badge bg-secondary">{{ $product->brand->name }}</span>
                                            @endif
                                            @if($product->in_stock && $product->stock_quantity > 0)
                                                @if($product->stock_quantity <= 5)
                                                    <span class="badge bg-warning">Low Stock ({{ $product->stock_quantity }})</span>
                                                @else
                                                    <span class="badge bg-success">In Stock</span>
                                                @endif
                                            @else
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @endif
                                            @if($product->is_featured)
                                                <span class="badge bg-info">Featured</span>
                                            @endif
                                        </div>
                                        
                                        <div class="product-rating mb-2">
                                            @php
                                                $rating = $product->average_rating ?? 0;
                                                $fullStars = floor($rating);
                                                $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                                $reviewsCount = $product->reviews_count ?? 0;
                                            @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $fullStars)
                                                    <i class="ti ti-star-filled text-warning"></i>
                                                @elseif($i == $fullStars + 1 && $hasHalfStar)
                                                    <i class="ti ti-star-half-filled text-warning"></i>
                                                @else
                                                    <i class="ti ti-star text-muted"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-1 small">({{ number_format($rating, 1) }}) {{ $reviewsCount }} reviews</span>
                                        </div>
                                        
                                        <p class="card-text d-none d-xl-block mb-0">
                                            <small class="text-muted">
                                                @if($product->free_shipping)
                                                    <i class="ti ti-truck text-success"></i> Free shipping •
                                                @endif
                                                SKU: {{ $product->sku }}
                                                @if($product->warranty_period)
                                                    • <i class="ti ti-shield-check text-info"></i> {{ $product->warranty_period }} warranty
                                                @endif
                                            </small>
                                        </p>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-12 text-center text-md-end product-card-mobile product-actions">
                                        <div class="mb-3 price-section">
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <div class="h5 text-primary mb-1 fw-bold">৳{{ number_format($product->sale_price, 2) }}</div>
                                                <div>
                                                    <small class="text-muted text-decoration-line-through">৳{{ number_format($product->price, 2) }}</small>
                                                    @php
                                                        $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                                                    @endphp
                                                    <span class="badge bg-danger ms-1">{{ $discount }}% OFF</span>
                                                </div>
                                            @else
                                                <div class="h5 text-primary mb-1 fw-bold">৳{{ number_format($product->price, 2) }}</div>
                                                <small class="text-muted">Regular Price</small>
                                            @endif
                                        </div>
                                        <div class="d-grid gap-2 btn-actions">
                                            @if($product->in_stock && $product->stock_quantity > 0)
                                                <button class="btn btn-primary btn-sm add-to-cart" 
                                                        data-product-id="{{ $product->id }}">
                                                    <i class="ti ti-shopping-cart"></i> Add to Cart
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    <i class="ti ti-ban"></i> Out of Stock
                                                </button>
                                            @endif
                                            <button class="btn btn-outline-danger btn-sm add-to-wishlist" 
                                                    data-product-id="{{ $product->id }}">
                                                <i class="ti ti-heart"></i> Wishlist
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="ti ti-package-off text-muted" style="font-size: 64px;"></i>
                            <h5 class="mt-3">No products found</h5>
                            <p class="text-muted">Try adjusting your filters or search terms.</p>
                            <a href="{{ route('shop.list') }}" class="btn btn-primary">Clear Filters</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="mt-4">
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

            <!-- Results Info -->
            <div class="text-center mt-3">
                <p class="text-muted">
                    Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} 
                    of {{ $products->total() }} products
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get filter elements
    const categoryFilter = document.getElementById('categoryFilter');
    const priceFilter = document.getElementById('priceFilter');
    const sortBy = document.getElementById('sortBy');
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');

    // Handle filter changes
    function applyFilters() {
        const params = new URLSearchParams();
        
        if (categoryFilter.value) params.set('category', categoryFilter.value);
        if (priceFilter.value && priceFilter.value !== '') {
            const [min, max] = priceFilter.value.split('-');
            params.set('price_min', min);
            if (max !== 'max') params.set('price_max', max);
        }
        if (sortBy.value) params.set('sort', sortBy.value);
        if (searchInput.value.trim()) params.set('search', searchInput.value.trim());
        
        // Redirect with new filters
        window.location.href = '{{ route("shop.list") }}?' + params.toString();
    }

    // Add event listeners
    categoryFilter.addEventListener('change', applyFilters);
    priceFilter.addEventListener('change', applyFilters);
    sortBy.addEventListener('change', applyFilters);
    
    // Search functionality
    function handleSearch() {
        applyFilters();
    }
    
    searchBtn.addEventListener('click', handleSearch);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            handleSearch();
        }
    });

    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            addToCart(productId);
        });
    });

    // Add to wishlist functionality
    document.querySelectorAll('.add-to-wishlist').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            addToWishlist(productId);
        });
    });

    // Add to cart function
    function addToCart(productId) {
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
                showToast('Product added to cart successfully!', 'success');
                
                // Use global cart update function
                if (window.updateCartCount) {
                    window.updateCartCount();
                }
            } else {
                showToast(data.message || 'Failed to add product to cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while adding to cart', 'error');
        });
    }

    // Add to wishlist function
    function addToWishlist(productId) {
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
                showToast('Product added to wishlist!', 'success');
                // Update wishlist icon if needed
                const button = document.querySelector(`[data-product-id="${productId}"].add-to-wishlist`);
                if (button) {
                    button.classList.remove('btn-outline-danger');
                    button.classList.add('btn-danger');
                    button.innerHTML = '<i class="ti ti-heart-filled"></i> In Wishlist';
                }
            } else {
                showToast(data.message || 'Failed to add to wishlist', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while adding to wishlist', 'error');
        });
    }

    // Toast notification function
    function showToast(message, type = 'info') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(toast);
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 3000);
    }
});
</script>
@endpush
@endsection
