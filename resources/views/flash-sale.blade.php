@extends('layouts.app')

@section('title', 'Flash Sale - ' . config('app.name'))
@section('description', 'Amazing flash sale deals with up to 70% off on selected products')

@section('header')
<!-- Search Form-->
<div class="container">
    <div class="search-form pt-3 rtl-flex-d-row-r">
        <form action="{{ route('search') }}" method="GET">
            <input class="form-control" type="search" name="q" placeholder="Search flash sale products..." value="{{ request('q') }}">
            <button type="submit"><i class="ti ti-search"></i></button>
        </form>
    </div>
</div>
@endsection

@section('content')
<!-- Page Header -->
<div class="page-content-wrapper py-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header-content">
                    <h1 class="mb-2">⚡ Flash Sale</h1>
                    <p class="text-muted">Limited time offers with amazing discounts!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Flash Sale Products -->
<div class="shop-wrapper py-3">
    <div class="container">
        @if($flashSaleProducts->count() > 0)
            <!-- Products Grid -->
            <div class="row g-2 rtl-flex-d-row-r">
                @foreach($flashSaleProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card product-card h-100">
                        <div class="card-body">
                            <!-- Product Image -->
                            <div class="product-thumbnail-wrapper">
                                <!-- Discount Badge -->
                                {{-- <div class="product-badge">
                                    <span class="badge bg-danger">{{ $product->discount }}% OFF</span>
                                </div>
                                
                                <!-- Wishlist Button -->
                                <div class="wishlist-badge">
                                    <button class="btn btn-sm btn-outline-light wishlist-btn" 
                                            onclick="event.stopPropagation(); toggleWishlist({{ $product->id }}, this)" 
                                            title="Add to Wishlist">
                                        <i class="ti ti-heart"></i>
                                    </button>
                                </div> --}}
                                
                                <a class="product-thumbnail d-block" href="{{ route('products.show', $product->slug) }}">
                                    <img src="{{ $product->image ? (str_contains($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : asset('assets/img/product/1.png') }}" 
                                         alt="{{ $product->name }}" 
                                         class="img-fluid">
                                </a>
                            </div>
                            
                            <!-- Product Info -->
                            <div class="product-description">
                                <!-- Category -->
                                <p class="mb-1">
                                    <small class="text-muted">{{ $product->category }}</small>
                                </p>
                                
                                <!-- Product Title -->
                                <a class="product-title d-block" href="{{ route('products.show', $product->slug) }}">
                                    <h6>{{ $product->name }}</h6>
                                </a>
                                
                                <!-- Price -->
                                <div class="product-price mb-2">
                                    <div class="price-row d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-1 gap-sm-2">
                                        <span class="sale-price text-danger fw-bold">৳{{ number_format($product->price, 2) }}</span>
                                        @if($product->old_price > $product->price)
                                            <span class="regular-price text-muted text-decoration-line-through">৳{{ number_format($product->old_price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Savings -->
                                @if($product->old_price && $product->old_price > $product->price)
                                    <p class="mb-2">
                                        <small class="text-success">Save ৳{{ number_format($product->old_price - $product->price, 2) }}</small>
                                    </p>
                                @endif
                                
                                <!-- Rating -->
                                <div class="product-rating mb-3">
                                    @php
                                        $rating = $product->rating ?? $product->average_rating ?? 0;
                                        $reviewsCount = $product->reviews_count ?? $product->total_reviews ?? 0;
                                        $fullStars = floor($rating);
                                        $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                    @endphp
                                    
                                    @if($rating > 0)
                                        <!-- Full Stars -->
                                        @for($i = 1; $i <= $fullStars; $i++)
                                            <i class="ti ti-star-filled text-warning"></i>
                                        @endfor
                                        
                                        <!-- Half Star -->
                                        @if($hasHalfStar)
                                            <i class="ti ti-star-half-filled text-warning"></i>
                                        @endif
                                        
                                        <!-- Empty Stars -->
                                        @for($i = 1; $i <= $emptyStars; $i++)
                                            <i class="ti ti-star text-warning"></i>
                                        @endfor
                                        
                                        <span class="rating-counter ms-1">
                                            <span class="rating-value">{{ number_format($rating, 1) }}</span>
                                            @if($reviewsCount > 0)
                                                <span class="review-count">({{ $reviewsCount }})</span>
                                            @endif
                                        </span>
                                    @else
                                        <!-- No Rating -->
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="ti ti-star text-muted"></i>
                                        @endfor
                                        <span class="rating-counter ms-1 text-muted">
                                            <small>No reviews yet</small>
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Add to Cart Button -->
                                <div class="d-grid">
                                    <button class="btn btn-success btn-sm add-to-cart-btn" 
                                            onclick="quickAddToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, '{{ $product->image }}')">
                                        <i class="ti ti-shopping-cart-plus me-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $flashSaleProducts->links() }}
            </div>
        @else
            <!-- No Products Found -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="ti ti-discount-off text-muted" style="font-size: 4rem;"></i>
                </div>
                <h4 class="text-muted">No Flash Sale Products Available</h4>
                <p class="text-muted">Check back later for amazing deals!</p>
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="ti ti-home me-1"></i>Back to Home
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Flash Sale Info Banner -->
<div class="flash-sale-info py-3 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-8">
                <h5 class="mb-1">⏰ Limited Time Offers</h5>
                <p class="mb-0 text-muted">Flash sale prices are valid for a limited time. Grab these deals before they're gone!</p>
            </div>
            <div class="col-12 col-md-4 text-md-end mt-2 mt-md-0">
                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                    <i class="ti ti-arrow-left me-1"></i>Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.product-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    z-index: 20;
    pointer-events: none;
}

.wishlist-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    z-index: 20;
}

.wishlist-btn {
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    pointer-events: auto;
}

.wishlist-btn:hover {
    background: rgba(255, 255, 255, 1);
    transform: scale(1.1);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.wishlist-btn i {
    font-size: 1rem;
    color: #6c757d;
    transition: color 0.3s ease;
}

.wishlist-btn:hover i,
.wishlist-btn.active i {
    color: #dc3545;
}

.wishlist-btn.active {
    background: #dc3545;
    border-color: #dc3545;
}

.wishlist-btn.active i {
    color: white;
}

.product-card {
    position: relative;
    height: 100%;
    transition: transform 0.2s ease;
    display: flex;
    flex-direction: column;
    overflow: visible;
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

.product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.product-card .card-body {
    display: flex;
    flex-direction: column;
    height: 100%;
    padding: 1rem;
    position: relative;
}

.product-thumbnail-wrapper {
    position: relative;
    margin-bottom: 1rem;
}

.product-thumbnail {
    display: block;
    position: relative;
}

.product-thumbnail img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 8px;
}

.product-description {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-title {
    margin-bottom: 0.5rem;
}

.product-title h6 {
    font-size: 0.9rem;
    line-height: 1.3;
    margin-bottom: 0;
    height: 2.6rem; /* Fixed height for 2 lines */
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.product-price {
    margin-bottom: 0.5rem;
}

.price-row {
    line-height: 1.2;
}

.sale-price {
    font-size: 1rem;
    font-weight: 600;
    white-space: nowrap;
}

.regular-price {
    font-size: 0.85em;
    white-space: nowrap;
}

.product-rating {
    font-size: 0.85em;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.125rem;
}

.product-rating i {
    font-size: 0.9rem;
    margin-right: 1px;
}

.rating-counter {
    font-size: 0.8em;
    color: #6c757d;
    margin-left: 0.25rem;
    white-space: nowrap;
}

.rating-value {
    font-weight: 500;
    color: #495057;
}

.review-count {
    color: #6c757d;
}

.d-grid {
    margin-top: auto; /* Push button to bottom */
}

.btn-sm {
    font-size: 0.8rem;
    padding: 0.5rem 0.75rem;
}

.add-to-cart-btn {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.add-to-cart-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.add-to-cart-btn i {
    font-size: 1rem;
    transition: transform 0.2s ease;
}

.add-to-cart-btn:hover i {
    transform: scale(1.1);
}

.add-to-cart-btn:active {
    transform: translateY(0);
}

/* Cart icon animation */
@keyframes cartBounce {
    0%, 20%, 60%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-3px);
    }
    80% {
        transform: translateY(-1px);
    }
}

.add-to-cart-btn:active i {
    animation: cartBounce 0.6s ease;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .product-badge {
        top: 6px;
        right: 6px;
    }
    
    .wishlist-badge {
        top: 6px;
        left: 6px;
    }
    
    .product-badge .badge {
        font-size: 0.65rem;
        padding: 0.3rem 0.5rem;
    }
    
    .wishlist-btn {
        width: 30px;
        height: 30px;
    }
    
    .wishlist-btn i {
        font-size: 0.9rem;
    }
    
    .product-thumbnail img {
        height: 160px;
    }
    
    .product-card .card-body {
        padding: 0.875rem;
    }
    
    .product-title h6 {
        font-size: 0.85rem;
        height: 2.4rem;
    }
    
    .sale-price {
        font-size: 0.9rem;
    }
    
    .regular-price {
        font-size: 0.8rem;
    }
    
    .price-row {
        gap: 0.25rem !important;
    }
    
    .product-rating {
        font-size: 0.8em;
    }
    
    .product-rating i {
        font-size: 0.85rem;
    }
    
    .rating-counter {
        font-size: 0.75em;
    }
}

@media (max-width: 576px) {
    .product-thumbnail img {
        height: 140px;
    }
    
    .product-card .card-body {
        padding: 0.75rem;
    }
    
    .product-title h6 {
        font-size: 0.8rem;
        height: 2.2rem;
    }
    
    .sale-price {
        font-size: 0.85rem;
    }
    
    .regular-price {
        font-size: 0.75rem;
    }
    
    .btn-sm {
        font-size: 0.75rem;
        padding: 0.4rem 0.6rem;
    }
    
    .price-row {
        gap: 0.125rem !important;
    }
    
    /* Stack prices vertically on very small screens */
    .price-row.d-flex {
        flex-direction: column !important;
        align-items: flex-start !important;
    }
    
    .product-rating {
        font-size: 0.75em;
    }
    
    .product-rating i {
        font-size: 0.8rem;
    }
    
    .rating-counter {
        font-size: 0.7em;
    }
}

/* Equal height for all cards in a row */
.row.g-2 {
    display: flex;
    flex-wrap: wrap;
}

.row.g-2 > [class*="col-"] {
    display: flex;
    margin-bottom: 1rem;
}

/* Category text styling */
.sale-price small {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Savings text styling */
.text-success small {
    font-weight: 500;
}

/* Badge improvements */
.product-badge .badge {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.35rem 0.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    white-space: nowrap;
    max-width: 75px;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background-color: #dc3545 !important;
    color: white !important;
}

/* Extra small screens */
@media (max-width: 400px) {
    .product-badge {
        top: 6px;
        right: 6px;
    }
    
    .wishlist-badge {
        top: 6px;
        left: 6px;
    }
    
    .product-badge .badge {
        font-size: 0.6rem;
        padding: 0.25rem 0.4rem;
        max-width: 65px;
    }
    
    .wishlist-btn {
        width: 28px;
        height: 28px;
    }
    
    .wishlist-btn i {
        font-size: 0.9rem;
    }
    
    .sale-price {
        font-size: 0.8rem;
    }
    
    .regular-price {
        font-size: 0.7rem;
    }
    
    .product-title h6 {
        font-size: 0.75rem;
        height: 2rem;
    }
    
    .product-card .card-body {
        padding: 0.625rem;
    }
    
    .product-thumbnail img {
        height: 120px;
    }
}

/* Improved text wrapping for Bengali currency */
@media (max-width: 320px) {
    .sale-price,
    .regular-price {
        font-size: 0.75rem;
        word-break: keep-all;
    }
    
    .price-row {
        font-size: 0.9em;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Quick Add to Cart function
function quickAddToCart(productId, productName, productPrice, productImage) {
    try {
        if (!productId || !productName || !productPrice) {
            console.error('Missing required product data');
            return;
        }
        
        const cartData = {
            product_id: productId,
            name: productName,
            price: productPrice,
            quantity: 1,
            total: productPrice,
            image: productImage || ''
        };

        // Use global add to cart function if available
        if (typeof globalAddToCart === 'function') {
            globalAddToCart(cartData);
        } else {
            // Fallback - add to localStorage
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const existingItem = cart.find(item => item.product_id === productId);
            
            if (existingItem) {
                existingItem.quantity += 1;
                existingItem.total = (existingItem.price * existingItem.quantity).toFixed(2);
            } else {
                cart.push(cartData);
            }
            
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Show success message
            showToast(`${productName} added to cart!`, 'success');
            
            // Update cart count if function exists
            if (typeof updateCartCount === 'function') {
                updateCartCount();
            }
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showToast('Error adding item to cart', 'error');
    }
}

// Wishlist functionality
function toggleWishlist(productId, buttonElement) {
    try {
        if (!productId || !buttonElement) {
            console.error('Missing product ID or button element');
            return;
        }
        
        let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        const isInWishlist = wishlist.includes(productId);
        
        if (isInWishlist) {
            // Remove from wishlist
            wishlist = wishlist.filter(id => id !== productId);
            buttonElement.classList.remove('active');
            const icon = buttonElement.querySelector('i');
            if (icon) {
                icon.classList.remove('ti-heart-filled');
                icon.classList.add('ti-heart');
            }
            showToast('Removed from wishlist', 'info');
        } else {
            // Add to wishlist
            wishlist.push(productId);
            buttonElement.classList.add('active');
            const icon = buttonElement.querySelector('i');
            if (icon) {
                icon.classList.remove('ti-heart');
                icon.classList.add('ti-heart-filled');
            }
            showToast('Added to wishlist!', 'success');
        }
        
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
    } catch (error) {
        console.error('Error toggling wishlist:', error);
        showToast('Error updating wishlist', 'error');
    }
}

// Toast notification function
function showToast(message, type = 'info') {
    // Create toast if it doesn't exist
    if (!document.getElementById('flash-toast')) {
        const toastContainer = document.createElement('div');
        toastContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        `;
        toastContainer.innerHTML = `
            <div id="flash-toast" class="alert alert-${type === 'success' ? 'success' : type === 'info' ? 'info' : 'primary'}" 
                 style="display: none; min-width: 250px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                <span id="toast-message"></span>
            </div>
        `;
        document.body.appendChild(toastContainer);
    }
    
    const toast = document.getElementById('flash-toast');
    const messageEl = document.getElementById('toast-message');
    
    messageEl.textContent = message;
    toast.className = `alert alert-${type === 'success' ? 'success' : type === 'info' ? 'info' : 'primary'}`;
    toast.style.display = 'block';
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        toast.style.display = 'none';
    }, 3000);
}

// Initialize wishlist state on page load
document.addEventListener('DOMContentLoaded', function() {
    const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    
    // Set initial state for all wishlist buttons
    document.querySelectorAll('.wishlist-btn').forEach(button => {
        const productId = parseInt(button.getAttribute('onclick').match(/\d+/)[0]);
        if (wishlist.includes(productId)) {
            button.classList.add('active');
            button.querySelector('i').classList.remove('ti-heart');
            button.querySelector('i').classList.add('ti-heart-filled');
        }
    });
});
</script>
@endpush
