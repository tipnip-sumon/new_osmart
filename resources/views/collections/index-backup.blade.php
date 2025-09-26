@extends('layouts.app')

@section('title', 'Collections - ' . config('app.name'))
@section('description', 'Browse our product collections')

@push('styles')
<style>
.collection-card {
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    overflow: hidden;
}

.collection-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.collection-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
}

.collection-stats {
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
}

.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 3rem 0;
    margin-bottom: 2rem;
}

.featured-product-card {
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.featured-product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.product-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.badge-new {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #ff6b6b;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
}
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Discover Our Collections</h1>
                <p class="lead mb-4">Explore curated collections of premium products across various categories</p>
                <div class="d-flex gap-3">
                    <span class="collection-stats">
                        <i class="ti ti-category me-2"></i>{{ $collections->count() }} Collections
                    </span>
                    <span class="collection-stats">
                        <i class="ti ti-box me-2"></i>{{ $collections->sum('products_count') }} Products
                    </span>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <i class="ti ti-shopping-bag" style="font-size: 8rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <!-- Collections Grid -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">Shop by Collection</h2>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">
                    View All Categories <i class="ti ti-arrow-right ms-1"></i>
                </a>
            </div>
            
            <div class="row g-4">
                @forelse($collections as $collection)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card collection-card h-100">
                            <div class="card-body text-center p-3">
                                @php
                                    // Dynamic image handling for collections
                                    $collectionImageUrl = '';
                                    
                                    // First try image_data array (complex structure)
                                    if (isset($collection->image_data) && $collection->image_data) {
                                        $imageData = is_string($collection->image_data) ? json_decode($collection->image_data, true) : $collection->image_data;
                                        if (is_array($imageData)) {
                                            // Handle complex nested structure
                                            if (isset($imageData['sizes']['medium']['storage_url'])) {
                                                // Use medium size storage_url
                                                $collectionImageUrl = $imageData['sizes']['medium']['storage_url'];
                                            } elseif (isset($imageData['sizes']['original']['storage_url'])) {
                                                // Fallback to original if medium not available
                                                $collectionImageUrl = $imageData['sizes']['original']['storage_url'];
                                            } elseif (isset($imageData['sizes']['large']['storage_url'])) {
                                                // Fallback to large if original not available
                                                $collectionImageUrl = $imageData['sizes']['large']['storage_url'];
                                            }
                                        }
                                    }
                                    
                                    // Fallback to simple image field
                                    if (empty($collectionImageUrl) && $collection->image) {
                                        $collectionImageUrl = str_starts_with($collection->image, 'http') ? 
                                            $collection->image : 
                                            asset('storage/' . $collection->image);
                                    }
                                @endphp
                                
                                @if(!empty($collectionImageUrl))
                                    <img src="{{ $collectionImageUrl }}" 
                                         alt="{{ $collection->name }}" 
                                         class="collection-image mb-3"
                                         onerror="this.src='{{ asset('assets/img/collection/default.png') }}'">
                                @else
                                    <div class="collection-image mb-3 d-flex align-items-center justify-content-center bg-light rounded">
                                        <i class="ti ti-category" style="font-size: 3rem; color: #6c757d;"></i>
                                    </div>
                                @endif
                                
                                <h5 class="collection-title mb-2">{{ $collection->name }}</h5>
                                
                                @if($collection->description)
                                    <p class="text-muted small mb-3">{{ Str::limit($collection->description, 60) }}</p>
                                @endif
                                
                                <div class="mb-3">
                                    <span class="badge bg-primary">{{ $collection->products_count }} Products</span>
                                </div>
                                
                                <a href="{{ route('collections.show', $collection->slug) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="ti ti-eye me-1"></i>View Collection
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="ti ti-category" style="font-size: 4rem; color: #6c757d;"></i>
                            <h4 class="mt-3">No Collections Available</h4>
                            <p class="text-muted">Check back later for new collections</p>
                        </div>
                    </div>
                @endforelse
            </div>
    </div>

    <!-- Featured Products -->
    @if($featuredProducts->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">Featured Products</h2>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                    View All Products <i class="ti ti-arrow-right ms-1"></i>
                </a>
            </div>
            
            <div class="row g-4">
                @foreach($featuredProducts as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card featured-product-card h-100">
                            <div class="position-relative">
                                @php
                                    // Dynamic image handling for featured products
                                    $productImageUrl = '';
                                    
                                    // First try images array
                                    if (isset($product->images) && $product->images) {
                                        $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                                        if (is_array($images) && !empty($images)) {
                                            $image = $images[0]; // Get first image
                                            
                                            // Handle complex nested structure first
                                            if (is_array($image) && isset($image['sizes']['medium']['storage_url'])) {
                                                // New complex structure - use medium size storage_url
                                                $productImageUrl = $image['sizes']['medium']['storage_url'];
                                            } elseif (is_array($image) && isset($image['sizes']['original']['storage_url'])) {
                                                // Fallback to original if medium not available
                                                $productImageUrl = $image['sizes']['original']['storage_url'];
                                            } elseif (is_array($image) && isset($image['sizes']['large']['storage_url'])) {
                                                // Fallback to large if original not available
                                                $productImageUrl = $image['sizes']['large']['storage_url'];
                                            } elseif (is_array($image) && isset($image['urls']['medium'])) {
                                                // Legacy complex URL structure - use medium size
                                                $productImageUrl = $image['urls']['medium'];
                                            } elseif (is_array($image) && isset($image['urls']['original'])) {
                                                // Legacy fallback to original if medium not available
                                                $productImageUrl = $image['urls']['original'];
                                            } elseif (is_array($image) && isset($image['url']) && is_string($image['url'])) {
                                                $productImageUrl = $image['url'];
                                            } elseif (is_array($image) && isset($image['path']) && is_string($image['path'])) {
                                                $productImageUrl = asset('storage/' . $image['path']);
                                            } elseif (is_string($image)) {
                                                // Simple string path
                                                $productImageUrl = asset('storage/' . $image);
                                            }
                                        }
                                    }
                                    
                                    // Fallback to image accessor or image_url
                                    if (empty($productImageUrl)) {
                                        if (isset($product->image_url) && $product->image_url) {
                                            $productImageUrl = $product->image_url;
                                        } elseif (isset($product->image) && $product->image) {
                                            $productImageUrl = str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image);
                                        } else {
                                            $productImageUrl = asset('assets/img/product/default.png');
                                        }
                                    }
                                @endphp
                                
                                <img src="{{ $productImageUrl }}" 
                                     alt="{{ $product->name }}" 
                                     class="product-image"
                                     onerror="this.src='{{ asset('assets/img/product/default.png') }}'">
                                
                                @if($product->created_at->diffInDays() <= 7)
                                    <span class="badge-new">New</span>
                                @endif
                            </div>
                            
                            <div class="card-body text-center p-3">
                                <h6 class="product-title mb-2">{{ Str::limit($product->name, 30) }}</h6>
                                
                                <div class="mb-2">
                                    @if($product->price)
                                        @if($product->sale_price && $product->sale_price < $product->price)
                                            <span class="text-muted text-decoration-line-through small me-2">
                                                ${{ number_format($product->price, 2) }}
                                            </span>
                                            <span class="text-danger fw-bold">
                                                ${{ number_format($product->sale_price, 2) }}
                                            </span>
                                        @else
                                            <span class="text-primary fw-bold">
                                                ${{ number_format($product->price, 2) }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">Price not available</span>
                                    @endif
                                </div>
                                
                                @if($product->average_rating > 0)
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-center align-items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $product->average_rating)
                                                    <i class="ti ti-star-filled text-warning small"></i>
                                                @else
                                                    <i class="ti ti-star text-muted small"></i>
                                                @endif
                                            @endfor
                                            <span class="text-muted small ms-2">({{ $product->review_count }})</span>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="d-flex gap-2">
                                    <a href="{{ route('products.show', $product->slug) }}" 
                                       class="btn btn-primary btn-sm flex-grow-1">
                                        <i class="ti ti-eye me-1"></i>View
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-primary btn-sm add-to-cart" 
                                            data-product-id="{{ $product->id }}"
                                            title="Add to Cart">
                                        <i class="ti ti-shopping-cart" style="font-size: 1rem;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Back to Home -->
    <div class="text-center mt-5">
        <a href="{{ route('home') }}" class="btn btn-outline-primary">
            <i class="ti ti-arrow-left"></i> Back to Home
        </a>
    </div>
</div>

@push('scripts')
<script>
// Add to cart functionality
$(document).ready(function() {
    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();
        
        const productId = $(this).data('product-id');
        const button = $(this);
        const originalHtml = button.html();
        
        // Disable button and show loading
        button.prop('disabled', true);
        button.html('<i class="ti ti-loader" style="animation: spin 1s linear infinite;"></i>');
        
        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: 1,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    // Update cart count in header
                    updateCartCount();
                    
                    // Show success message
                    toastr.success('Product added to cart successfully!');
                    
                    // Update button to show success
                    button.html('<i class="ti ti-check" style="font-size: 1rem;"></i>');
                    button.removeClass('btn-outline-primary').addClass('btn-success');
                    
                    // Reset button after 2 seconds
                    setTimeout(function() {
                        button.html(originalHtml);
                        button.removeClass('btn-success').addClass('btn-outline-primary');
                        button.prop('disabled', false);
                    }, 2000);
                } else {
                    // Show error message
                    const errorMsg = response.message || 'Failed to add product to cart';
                    toastr.error(errorMsg);
                    
                    // Reset button
                    button.html(originalHtml);
                    button.prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error('Cart error:', xhr.responseText);
                
                let errorMsg = 'Something went wrong. Please try again.';
                
                // Try to parse error response
                try {
                    const response = JSON.parse(xhr.responseText);
                    errorMsg = response.message || errorMsg;
                } catch(e) {
                    console.log('Could not parse error response');
                }
                
                // Show error message
                toastr.error(errorMsg);
                
                // Reset button
                button.html(originalHtml);
                button.prop('disabled', false);
            }
        });
    });
});

// Update cart count function
function updateCartCount() {
    $.get('{{ route("cart.count") }}', function(data) {
        if (data && typeof data.count !== 'undefined') {
            $('.cart-count').text(data.count);
            $('.cart-badge').text(data.count);
        }
    }).fail(function() {
        console.log('Failed to update cart count');
    });
}
</script>
@endpush

@endsection
