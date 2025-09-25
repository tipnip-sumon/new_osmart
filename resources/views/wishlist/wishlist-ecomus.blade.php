@extends('layouts.ecomus')

@section('title', 'Your Wishlist - ' . config('app.name'))
@section('description', 'Your saved products wishlist')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@php
// Helper function to safely get product image using comprehensive legacy format handling
function getWishlistProductImageSrc($product) {
    $productImageUrl = '';
    
    // Handle images array first (for products) - similar to grid.blade.php
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
    
    // Handle complex image_data structure (for categories)
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
    
    return $productImageUrl;
}

// Helper function to get hover image
function getWishlistHoverImageSrc($product, $fallback = null) {
    // Try to get second image from images array
    if (isset($product->images)) {
        $images = is_array($product->images) ? $product->images : (is_string($product->images) ? json_decode($product->images, true) : []);
        
        if (is_array($images) && count($images) > 1) {
            $hoverImage = $images[1]; // Get second image for hover
            
            if (is_string($hoverImage)) {
                return asset('storage/' . $hoverImage);
            } elseif (is_array($hoverImage) && isset($hoverImage['url']) && is_string($hoverImage['url'])) {
                return $hoverImage['url'];
            } elseif (is_array($hoverImage) && isset($hoverImage['path']) && is_string($hoverImage['path'])) {
                return asset('storage/' . $hoverImage['path']);
            }
        }
    }
    
    // If no hover image available, return the main image as fallback
    return $fallback ?: getWishlistProductImageSrc($product);
}
@endphp

@section('content')
<!-- page-title -->
<div class="tf-page-title">
    <div class="container-full">
        <div class="heading text-center">Your wishlist</div>
    </div>
</div>
<!-- /page-title -->

<!-- Section Product -->
<section class="flat-spacing-2">
    <div class="container">
        @if($products->count() > 0)
            <div class="grid-layout wrapper-shop" data-grid="grid-4">
                @foreach($products as $product)
                    @php
                        $productImageUrl = getWishlistProductImageSrc($product);
                        $hoverImageUrl = getWishlistHoverImageSrc($product, $productImageUrl);
                    @endphp
                    <!-- card product {{ $loop->iteration }} -->
                    <div class="card-product" data-product-id="{{ $product->id }}">
                        <div class="card-product-wrapper">
                            <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="product-img">
                                <img class="lazyload img-product" 
                                     data-src="{{ $productImageUrl }}" 
                                     src="{{ $productImageUrl }}" 
                                     alt="{{ $product->name }}"
                                     style="height: 200px; object-fit: cover;"
                                     onerror="this.src='{{ asset('assets/img/product/1.png') }}'; console.log('Fallback image loaded for: {{ $product->name }}');"
                                     onload="console.log('Image loaded successfully: {{ $productImageUrl }}');">
                                <img class="lazyload img-hover" 
                                     data-src="{{ $hoverImageUrl }}" 
                                     src="{{ $hoverImageUrl }}" 
                                     alt="{{ $product->name }}"
                                     style="height: 200px; object-fit: cover;"
                                     onerror="this.src='{{ asset('assets/img/product/1.png') }}'; console.log('Hover image fallback loaded for: {{ $product->name }}');"
                                     onload="console.log('Hover image loaded successfully: {{ $hoverImageUrl }}');">
                            </a>
                            <div class="list-product-btn type-wishlist">
                                <button type="button" class="box-icon bg_white wishlist-remove-btn" 
                                        data-product-id="{{ $product->id }}" 
                                        title="Remove from Wishlist">
                                    <span class="tooltip">Remove Wishlist</span>
                                    <span class="icon icon-delete"></span>
                                </button>
                            </div>
                            <div class="list-product-btn">
                                <button type="button" class="box-icon bg_white quick-add tf-btn-loading" data-action="add-to-cart" data-product-id="{{ $product->id }}">
                                    <span class="icon icon-bag"></span>
                                    <span class="tooltip">Quick Add</span>
                                </button>
                                <a href="#compare" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"
                                    class="box-icon bg_white compare btn-icon-action">
                                    <span class="icon icon-compare"></span>
                                    <span class="tooltip">Add to Compare</span>
                                    <span class="icon icon-check"></span>
                                </a>
                                <a href="#quick_view" data-bs-toggle="modal"
                                    class="box-icon bg_white quickview tf-btn-loading">
                                    <span class="icon icon-view"></span>
                                    <span class="tooltip">Quick View</span>
                                </a>
                            </div>
                            
                            @if($product->variants && count($product->variants) > 0)
                                <div class="size-list">
                                    @foreach($product->variants as $variant)
                                        @if(isset($variant['size']))
                                            <span>{{ $variant['size'] }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            
                            @if($product->discount > 0)
                                <div class="on-sale-wrap">
                                    <div class="on-sale-item">{{ $product->discount }}% OFF</div>
                                </div>
                            @endif
                        </div>
                        <div class="card-product-info">
                            <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="title link">{{ $product->name }}</a>
                            <span class="price">
                                @if($product->sale_price)
                                    <span class="compare-at-price">{{ formatCurrency($product->price) }}</span>
                                    <span class="price-on-sale fw-6">{{ formatCurrency($product->sale_price) }}</span>
                                @else
                                    <span class="fw-6">{{ formatCurrency($product->price) }}</span>
                                @endif
                            </span>
                            @if($product->variants && count($product->variants) > 0)
                                <ul class="list-color-product">
                                    @foreach($product->variants->take(3) as $index => $variant)
                                        @if(isset($variant['color']))
                                            <li class="list-color-item color-swatch {{ $index == 0 ? 'active' : '' }}">
                                                <span class="tooltip">{{ ucfirst($variant['color']) }}</span>
                                                <span class="swatch-value bg_{{ strtolower(str_replace(' ', '-', $variant['color'])) }}"></span>
                                                @if(isset($variant['image']) && $variant['image'])
                                                    <img class="lazyload" data-src="{{ asset('storage/' . $variant['image']) }}" src="{{ asset('storage/' . $variant['image']) }}" alt="{{ $product->name }}">
                                                @else
                                                    <img class="lazyload" data-src="{{ $productImageUrl }}" src="{{ $productImageUrl }}" alt="{{ $product->name }}">
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty Wishlist State -->
            <div class="empty-wishlist text-center py-5">
                <div class="empty-wishlist-icon mb-4">
                    <i class="icon icon-heart" style="font-size: 4rem; color: #ccc;"></i>
                </div>
                <h3 class="mb-3">Your wishlist is empty</h3>
                <p class="text-muted mb-4">You haven't added any items to your wishlist yet.</p>
                <a href="{{ route('products.index') }}" class="tf-btn btn-fill animate-hover-btn radius-3">
                    <span>Continue Shopping</span>
                    <i class="icon icon-arrow1-top-left"></i>
                </a>
            </div>
        @endif
    </div>
</section>
<!-- /Section Product -->

<style>
/* Wishlist specific styles */
.list-product-btn.type-wishlist {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 5;
}

.list-product-btn.type-wishlist .wishlist {
    background: rgba(255, 255, 255, 0.9);
    border: none;
    padding: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.list-product-btn.type-wishlist .wishlist:hover {
    background: #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    color: #e74c3c;
}

.empty-wishlist {
    min-height: 400px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

/* Grid layout responsive */
@media (max-width: 1199px) {
    .grid-layout[data-grid="grid-4"] {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }
}

@media (max-width: 767px) {
    .grid-layout[data-grid="grid-4"] {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
}

@media (max-width: 479px) {
    .grid-layout[data-grid="grid-4"] {
        grid-template-columns: 1fr;
        gap: 15px;
    }
}

/* Loading states */
.tf-btn-loading {
    position: relative;
}

.tf-btn-loading.loading::after {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    margin: auto;
    border: 2px solid transparent;
    border-top-color: currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Color swatches */
.swatch-value.bg_orange-3 { background-color: #ff6b35; }
.swatch-value.bg_dark { background-color: #333; }
.swatch-value.bg_white { background-color: #fff; border: 1px solid #eee; }
.swatch-value.bg_brown { background-color: #8b4513; }
.swatch-value.bg_purple { background-color: #9b59b6; }
.swatch-value.bg_light-green { background-color: #90EE90; }
.swatch-value.bg_blue-2 { background-color: #3498db; }
.swatch-value.bg_dark-blue { background-color: #2c3e50; }
.swatch-value.bg_light-grey { background-color: #bdc3c7; }
</style>
@endsection
@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle wishlist removal with confirmation
    document.addEventListener('click', function(e) {
        if (e.target.closest('.wishlist-remove-btn')) {
            e.preventDefault();
            e.stopPropagation(); // Prevent event bubbling to other handlers
            
            const button = e.target.closest('.wishlist-remove-btn');
            const productId = button.dataset.productId;
            
            if (!productId) {
                console.error('Product ID not found');
                return;
            }
            
            // Use SweetAlert for confirmation
            Swal.fire({
                title: 'Remove from Wishlist?',
                text: 'Are you sure you want to remove this item from your wishlist?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Add loading state
                    button.classList.add('loading');
                    button.disabled = true;
                    
                    // Create form data
                    const formData = new FormData();
                    formData.append('product_id', productId);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                    
                    // Submit the request
                    fetch('{{ route('wishlist.remove') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            // Remove the product card with animation
                            const productCard = button.closest('.card-product');
                            productCard.style.transition = 'all 0.3s ease';
                            productCard.style.transform = 'scale(0.8)';
                            productCard.style.opacity = '0';
                            
                            setTimeout(() => {
                                productCard.remove();
                                
                                // Check if wishlist is empty
                                const remainingProducts = document.querySelectorAll('.card-product');
                                if (remainingProducts.length === 0) {
                                    // Show empty state without reload
                                    const container = document.querySelector('.grid-layout.wrapper-shop');
                                    container.innerHTML = `
                                        <div class="empty-wishlist text-center py-5" style="grid-column: 1 / -1;">
                                            <div class="empty-wishlist-icon mb-4">
                                                <i class="icon icon-heart" style="font-size: 4rem; color: #ccc;"></i>
                                            </div>
                                            <h3 class="mb-3">Your wishlist is empty</h3>
                                            <p class="text-muted mb-4">You haven't added any items to your wishlist yet.</p>
                                            <a href="{{ route('products.index') }}" class="tf-btn btn-fill animate-hover-btn radius-3">
                                                <span>Continue Shopping</span>
                                                <i class="icon icon-arrow1-top-left"></i>
                                            </a>
                                        </div>
                                    `;
                                }
                            }, 300);
                            
                            // Show success message with SweetAlert
                            Swal.fire({
                                title: 'Removed!',
                                text: data.message || 'Product removed from wishlist',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end'
                            });
                            
                            // Update wishlist count in header
                            if (typeof window.updateWishlistCount === 'function') {
                                window.updateWishlistCount();
                            }
                        } else {
                            throw new Error(data.message || 'Failed to remove item');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        button.classList.remove('loading');
                        button.disabled = false;
                        
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to remove item from wishlist',
                            icon: 'error',
                            confirmButtonColor: '#e74c3c'
                        });
                    });
                }
            });
        }
    });
    
    // Handle quick add to cart (using the same pattern as home page)
    document.addEventListener('click', function(e) {
        if (e.target.closest('[data-action="add-to-cart"]')) {
            e.preventDefault();
            
            const button = e.target.closest('[data-action="add-to-cart"]');
            const productId = button.dataset.productId;
            
            if (!productId) return;
            
            // Add loading state
            button.classList.add('loading');
            button.disabled = true;
            
            // Create form data
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', '1');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            fetch('/cart/add', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message with SweetAlert
                    Swal.fire({
                        title: 'Added to Cart!',
                        text: 'Product added to cart successfully',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                    
                    // Update cart count if available
                    if (typeof window.updateCartCount === 'function') {
                        window.updateCartCount();
                    } else if (data.cartCount) {
                        // Fallback to direct update
                        const cartCount = document.querySelector('#cart-count');
                        if (cartCount) {
                            cartCount.textContent = data.cartCount;
                            if (data.cartCount > 0) {
                                cartCount.style.display = 'inline-block';
                            }
                        }
                    }
                } else {
                    throw new Error(data.message || 'Failed to add to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to add product to cart',
                    icon: 'error',
                    confirmButtonColor: '#e74c3c'
                });
            })
            .finally(() => {
                button.classList.remove('loading');
                button.disabled = false;
            });
        }
    });
});
</script>
@endpush
