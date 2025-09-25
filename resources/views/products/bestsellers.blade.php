@extends('layouts.app')

@section('title', 'Best Selling Products - ' . config('app.name'))
@section('description', 'Discover our best-selling products that customers love the most')

@section('header')
<!-- Page Header -->
<div class="container">
    <div class="page-header pt-3">
        <div class="d-flex align-items-center">
            <a class="btn btn-primary btn-back" href="{{ url()->previous() }}">
                <i class="ti ti-arrow-left"></i>
            </a>
            <div class="page-title ms-3">
                <h3>Best Selling Products</h3>
                <p class="mb-0">Most popular products among customers</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container">
    <div class="row g-3">
        <div class="col-12">
            <!-- View Toggle -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="view-toggle">
                    <a href="{{ route('products.bestsellers') }}" class="btn btn-sm btn-primary">
                        <i class="ti ti-grid-dots me-1"></i>Grid
                    </a>
                    <a href="{{ route('products.bestsellers') }}?view=list" class="btn btn-sm btn-outline-primary ms-2">
                        <i class="ti ti-list me-1"></i>List
                    </a>
                </div>
                <div class="products-count">
                    <span class="badge bg-primary">{{ $bestsellerProducts->total() }} Products</span>
                </div>
            </div>

            @if($bestsellerProducts->count() > 0)
                <!-- Bestseller Products Grid -->
                <div class="row g-3">
                    @foreach($bestsellerProducts as $product)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card product-card h-100">
                                <div class="card-img-wrapper position-relative">
                                    @php
                                        $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                                        $firstImage = is_array($images) && !empty($images) ? $images[0] : $product->images;
                                        
                                        if (is_string($firstImage)) {
                                            $imageUrl = str_starts_with($firstImage, 'http') ? $firstImage : asset('storage/' . $firstImage);
                                        } else {
                                            $imageUrl = asset('assets/img/product/default.png');
                                        }
                                    @endphp
                                    
                                    <img src="{{ $imageUrl }}" 
                                         class="card-img-top product-img" 
                                         alt="{{ $product->name }}"
                                         onerror="this.src='{{ asset('assets/img/product/default.png') }}'">
                                    
                                    <!-- Bestseller Badge -->
                                    <span class="badge bg-success position-absolute" style="top: 10px; left: 10px;">
                                        <i class="ti ti-trending-up me-1"></i>Bestseller
                                    </span>
                                    
                                    @if($product->sales_count > 0)
                                        <span class="badge bg-info position-absolute text-white" style="top: 50px; left: 10px;">
                                            {{ $product->sales_count }} sold
                                        </span>
                                    @endif
                                    
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        @php
                                            $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                                        @endphp
                                        <span class="badge bg-danger position-absolute" style="top: 10px; right: 10px;">
                                            -{{ $discount }}%
                                        </span>
                                    @endif
                                    
                                    <!-- Wishlist Button -->
                                    <button class="btn btn-outline-light btn-sm wishlist-btn position-absolute" 
                                            style="top: 50px; right: 10px; width: 32px; height: 32px;" 
                                            onclick="toggleWishlist({{ $product->id }})"
                                            title="Add to wishlist">
                                        <i class="ti ti-heart"></i>
                                    </button>
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title mb-2">
                                        <a href="{{ route('products.show', $product->slug ?: $product->id) }}" class="text-decoration-none text-dark">
                                            {{ Str::limit($product->name, 50) }}
                                        </a>
                                    </h6>
                                    
                                    @if($product->brand)
                                        <small class="text-muted mb-2">{{ is_object($product->brand) ? $product->brand->name : $product->brand }}</small>
                                    @endif
                                    
                                    @if($product->category)
                                        <small class="text-primary mb-2">{{ is_object($product->category) ? $product->category->name : $product->category }}</small>
                                    @endif
                                    
                                    <div class="price-section mb-3">
                                        @if($product->sale_price && $product->sale_price < $product->price)
                                            <span class="current-price fw-bold text-primary">৳{{ number_format($product->sale_price, 2) }}</span>
                                            <span class="original-price text-muted text-decoration-line-through ms-2">৳{{ number_format($product->price, 2) }}</span>
                                        @else
                                            <span class="current-price fw-bold text-primary">৳{{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Rating -->
                                    <div class="rating mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= ($product->average_rating ?? 4))
                                                <i class="ti ti-star-filled text-warning"></i>
                                            @else
                                                <i class="ti ti-star text-muted"></i>
                                            @endif
                                        @endfor
                                        <small class="text-muted ms-1">({{ $product->review_count ?? 0 }})</small>
                                    </div>
                                    
                                    <!-- Stock Status -->
                                    @if($product->stock_quantity > 0)
                                        <small class="text-success mb-2">
                                            <i class="ti ti-check-circle me-1"></i>In Stock ({{ $product->stock_quantity }})
                                        </small>
                                    @else
                                        <small class="text-danger mb-2">
                                            <i class="ti ti-x-circle me-1"></i>Out of Stock
                                        </small>
                                    @endif
                                    
                                    <!-- Action Buttons -->
                                    <div class="mt-auto">
                                        <div class="d-flex gap-2">
                                            @if($product->stock_quantity > 0)
                                                <button class="btn btn-primary btn-sm flex-grow-1" 
                                                        onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->sale_price ?? $product->price }})">
                                                    <i class="ti ti-shopping-cart me-1"></i>Add to Cart
                                                </button>
                                            @else
                                                <button class="btn btn-outline-secondary btn-sm flex-grow-1" disabled>
                                                    <i class="ti ti-shopping-cart me-1"></i>Out of Stock
                                                </button>
                                            @endif
                                            <a href="{{ route('products.show', $product->slug ?: $product->id) }}" 
                                               class="btn btn-outline-primary btn-sm" 
                                               title="View details"
                                               style="width: 40px; height: 32px;">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $bestsellerProducts->links() }}
                </div>
            @else
                <!-- No Bestseller Products -->
                <div class="empty-products text-center py-5">
                    <div class="mb-4">
                        <i class="ti ti-trending-up" style="font-size: 4rem; color: #ddd;"></i>
                    </div>
                    <h4 class="text-muted mb-3">No Best Selling Products Found</h4>
                    <p class="text-muted mb-4">We're working on building our bestseller collection!</p>
                    <a href="{{ route('shop.grid') }}" class="btn btn-primary">
                        <i class="ti ti-shopping-bag me-2"></i>Browse All Products
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Add to cart function
function addToCart(productId, productName, price) {
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
            toastr.success(data.message || `${productName} added to cart!`);
            updateCartCount();
        } else {
            toastr.error(data.message || 'Failed to add product to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('Error adding product to cart');
    });
}

// Toggle wishlist function
function toggleWishlist(productId) {
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
            toastr.success(data.message);
            // Toggle heart icon
            const heartIcon = event.target.closest('button').querySelector('i');
            if (data.action === 'added') {
                heartIcon.classList.add('ti-heart-filled');
                heartIcon.classList.remove('ti-heart');
            } else {
                heartIcon.classList.add('ti-heart');
                heartIcon.classList.remove('ti-heart-filled');
            }
        } else {
            toastr.error(data.message || 'Failed to update wishlist');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('Error updating wishlist');
    });
}

// Update cart count
function updateCartCount() {
    fetch('{{ route("cart.count") }}')
        .then(response => response.json())
        .then(data => {
            const cartCountElements = document.querySelectorAll('.cart-count');
            cartCountElements.forEach(element => {
                element.textContent = data.count;
            });
        })
        .catch(error => {
            console.error('Error updating cart count:', error);
        });
}
</script>

<style>
.product-card {
    transition: transform 0.2s ease-in-out;
    border: 1px solid #e0e0e0;
}

.product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.card-img-wrapper {
    overflow: hidden;
    height: 200px;
}

.product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-img {
    transform: scale(1.05);
}

.wishlist-btn {
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.wishlist-btn:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.btn-back {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-products {
    min-height: 400px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

@media (max-width: 768px) {
    .card-img-wrapper {
        height: 160px;
    }
    
    .product-card .card-title {
        font-size: 0.9rem;
    }
}
</style>
@endpush
