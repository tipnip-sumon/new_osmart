@extends('layouts.app')

@section('title', 'My Wishlist - ' . config('app.name'))
@section('description', 'Your saved products wishlist')

@section('header')
<!-- Page Header -->
<div class="container">
    <div class="page-header pt-3">
        <div class="d-flex align-items-center">
            <a class="btn btn-primary btn-back" href="{{ url()->previous() }}">
                <i class="ti ti-arrow-left"></i>
            </a>
            <div class="page-title ms-3">
                <h3>My Wishlist</h3>
                <p class="mb-0">Your saved products</p>
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
                    <a href="{{ route('wishlist.grid') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-grid-dots me-1"></i>Grid
                    </a>
                    <a href="{{ route('wishlist.list') }}" class="btn btn-sm btn-primary ms-2">
                        <i class="ti ti-list me-1"></i>List
                    </a>
                </div>
                <div class="wishlist-count">
                    <span class="badge bg-primary" id="wishlist-count">{{ count(session('wishlist', [])) }}</span>
                </div>
            </div>

            @php
                $wishlistItems = session('wishlist', []);
                
                if (!empty($wishlistItems)) {
                    $wishlistProducts = \App\Models\Product::whereIn('id', $wishlistItems)
                        ->where('status', 'active')
                        ->with('brand') // Load brand relationship
                        ->get();
                } else {
                    $wishlistProducts = collect(); // Empty collection instead of array
                }
            @endphp

            @if($wishlistProducts->count() > 0)
                <!-- Wishlist Products List -->
                <div class="wishlist-list">
                    @foreach($wishlistProducts as $product)
                        <div class="card mb-3 wishlist-item">
                            <div class="row g-0">
                                <div class="col-md-3 col-4">
                                    <div class="position-relative">
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
                                             class="img-fluid rounded-start product-img" 
                                             alt="{{ $product->name }}"
                                             style="height: 150px; width: 100%; object-fit: cover;"
                                             onerror="this.src='{{ asset('assets/img/product/1.png') }}'; console.log('Fallback image loaded for: {{ $product->name }}');"
                                             onload="console.log('Image loaded successfully: {{ $productImageUrl }}');"
                                             >
                                        
                                        @if($product->discount_percentage > 0)
                                            <span class="badge bg-danger position-absolute" style="top: 10px; left: 10px;">
                                                -{{ $product->discount_percentage }}%
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-9 col-8">
                                    <div class="card-body h-100 d-flex flex-column">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title mb-1">
                                                <a href="{{ route('products.show', $product->slug ?: $product->id) }}" class="text-decoration-none text-dark">
                                                    {{ $product->name }}
                                                </a>
                                            </h5>
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    onclick="removeFromWishlist({{ $product->id }})"
                                                    title="Remove from wishlist">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                        
                                        @if($product->brand)
                                            <p class="text-muted mb-2">Brand: {{ is_object($product->brand) ? $product->brand->name : $product->brand }}</p>
                                        @endif
                                        
                                        <!-- Rating -->
                                        @if($product->average_rating)
                                            <div class="rating mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $product->average_rating)
                                                        <i class="ti ti-star-filled text-warning"></i>
                                                    @else
                                                        <i class="ti ti-star text-muted"></i>
                                                    @endif
                                                @endfor
                                                <small class="text-muted ms-1">({{ $product->review_count ?? 0 }} reviews)</small>
                                            </div>
                                        @endif
                                        
                                        <p class="card-text text-muted mb-3">
                                            {{ Str::limit($product->description, 120) }}
                                        </p>
                                        
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="price-section">
                                                    @if($product->sale_price && $product->sale_price < $product->price)
                                                        <span class="current-price fw-bold text-primary h5 mb-0">৳{{ number_format($product->sale_price, 2) }}</span>
                                                        <span class="original-price text-muted text-decoration-line-through ms-2">৳{{ number_format($product->price, 2) }}</span>
                                                    @else
                                                        <span class="current-price fw-bold text-primary h5 mb-0">৳{{ number_format($product->price, 2) }}</span>
                                                    @endif
                                                </div>
                                                
                                                <div class="action-buttons">
                                                    <button class="btn btn-outline-primary btn-sm me-2" 
                                                            onclick="viewProduct('{{ $product->slug ?: $product->id }}')">
                                                        <i class="ti ti-eye me-1"></i>View
                                                    </button>
                                                    <button class="btn btn-primary btn-sm" 
                                                            onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->sale_price ?? $product->price }})">
                                                        <i class="ti ti-shopping-cart me-1"></i>Add to Cart
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Clear All Button -->
                <div class="text-center mt-4">
                    <button class="btn btn-outline-danger" onclick="clearWishlist()">
                        <i class="ti ti-trash me-2"></i>Clear All Wishlist
                    </button>
                </div>
            @else
                <!-- Empty Wishlist -->
                <div class="empty-wishlist text-center py-5">
                    <div class="mb-4">
                        <i class="ti ti-heart" style="font-size: 4rem; color: #ddd;"></i>
                    </div>
                    <h4 class="text-muted mb-3">Your wishlist is empty</h4>
                    <p class="text-muted mb-4">Save your favorite products by clicking the heart icon</p>
                    <a href="{{ route('shop.grid') }}" class="btn btn-primary">
                        <i class="ti ti-shopping-bag me-2"></i>Continue Shopping
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
            // Show success toast
            toastr.success(data.message || `${productName} added to cart!`);
            
            // Update cart count
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

// View product function
function viewProduct(productIdentifier) {
    window.location.href = `/products/${productIdentifier}`;
}

// Remove from wishlist function
function removeFromWishlist(productId) {
    fetch('{{ route("wishlist.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            action: 'remove'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success('Product removed from wishlist');
            // Reload page to update the list
            location.reload();
        } else {
            toastr.error(data.message || 'Failed to remove product from wishlist');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('Error removing product from wishlist');
    });
}

// Clear all wishlist
function clearWishlist() {
    if (confirm('Are you sure you want to clear your entire wishlist?')) {
        fetch('{{ route("wishlist.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                action: 'clear'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastr.success('Wishlist cleared successfully');
                location.reload();
            } else {
                toastr.error(data.message || 'Failed to clear wishlist');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Error clearing wishlist');
        });
    }
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
.wishlist-item {
    transition: transform 0.2s ease-in-out;
    border: 1px solid #e0e0e0;
}

.wishlist-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.btn-back {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-wishlist {
    min-height: 400px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

@media (max-width: 768px) {
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .action-buttons .btn {
        width: 100%;
    }
}
</style>
@endpush
