@extends('layouts.app')

@section('title', $collection->name . ' Collection - ' . config('app.name'))
@section('description', $collection->description ?? 'Browse products in ' . $collection->name . ' collection')

@push('styles')
<style>
.collection-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
}

.collection-info {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-top: -3rem;
    position: relative;
    z-index: 10;
}

.product-card {
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    overflow: hidden;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.product-image {
    width: 100%;
    height: 220px;
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

.badge-sale {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #28a745;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
}

.filters-section {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.sort-select {
    min-width: 180px;
}

.no-products {
    text-align: center;
    padding: 3rem 0;
    color: #6c757d;
}

.pagination-wrapper {
    margin-top: 3rem;
}
</style>
@endpush

@section('content')
<!-- Collection Header -->
<div class="collection-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="display-5 fw-bold mb-3">{{ $collection->name }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-white-50">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('collections.index') }}" class="text-white-50">Collections</a>
                        </li>
                        <li class="breadcrumb-item active text-white" aria-current="page">
                            {{ $collection->name }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Collection Info -->
    <div class="row">
        <div class="col-12">
            <div class="collection-info">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        @if($collection->description)
                            <p class="lead mb-3">{{ $collection->description }}</p>
                        @endif
                        <div class="d-flex gap-3 flex-wrap">
                            <span class="badge bg-primary fs-6">
                                <i class="ti ti-box me-1"></i>{{ $products->total() }} Products
                            </span>
                            @if($collection->created_at)
                                <span class="badge bg-secondary fs-6">
                                    <i class="ti ti-calendar me-1"></i>Created {{ $collection->created_at->format('M Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        @if($collection->image)
                            <img src="{{ asset('storage/' . $collection->image) }}" 
                                 alt="{{ $collection->name }}" 
                                 class="img-fluid rounded" 
                                 style="max-height: 100px;">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Sorting -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="filters-section">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-3 mb-md-0">
                            <i class="ti ti-filter me-2"></i>Filter & Sort Products
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-3 justify-content-md-end">
                            <select class="form-select sort-select" id="sortProducts">
                                <option value="newest">Newest First</option>
                                <option value="oldest">Oldest First</option>
                                <option value="price_low">Price: Low to High</option>
                                <option value="price_high">Price: High to Low</option>
                                <option value="name_asc">Name: A to Z</option>
                                <option value="name_desc">Name: Z to A</option>
                                <option value="rating">Highest Rated</option>
                            </select>
                            <select class="form-select" id="perPage" style="min-width: 100px;">
                                <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>12</option>
                                <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                                <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row mt-4">
        <div class="col-12">
            @if($products->count() > 0)
                <div class="row g-4" id="productsGrid">
                    @foreach($products as $product)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card product-card h-100">
                                <div class="position-relative">
                                    <img src="{{ $product->image_url }}" 
                                         alt="{{ $product->name }}" 
                                         class="product-image"
                                         onerror="this.src='{{ asset('assets/img/product/default.png') }}'">
                                    
                                    <!-- Badges -->
                                    @if($product->created_at->diffInDays() <= 7)
                                        <span class="badge-new">New</span>
                                    @endif
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <span class="badge-sale">
                                            {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="card-body text-center p-3">
                                    <h6 class="product-title mb-2">{{ Str::limit($product->name, 40) }}</h6>
                                    
                                    <!-- Price -->
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
                                    
                                    <!-- Rating -->
                                    @if($product->average_rating > 0)
                                        <div class="mb-3">
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
                                    
                                    <!-- Actions -->
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('products.show', $product->slug) }}" 
                                           class="btn btn-primary btn-sm flex-grow-1">
                                            <i class="ti ti-eye me-1"></i>View Details
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-primary btn-sm add-to-cart" 
                                                data-product-id="{{ $product->id }}"
                                                title="Add to Cart">
                                            <i class="ti ti-shopping-cart" style="font-size: 1rem;"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-outline-secondary btn-sm add-to-wishlist" 
                                                data-product-id="{{ $product->id }}"
                                                title="Add to Wishlist">
                                            <i class="ti ti-heart" style="font-size: 1rem;"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="pagination-wrapper">
                        <div class="d-flex justify-content-center">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="no-products">
                    <i class="ti ti-package" style="font-size: 4rem;"></i>
                    <h4 class="mt-3">No Products Found</h4>
                    <p>This collection doesn't have any products yet.</p>
                    <a href="{{ route('collections.index') }}" class="btn btn-primary">
                        <i class="ti ti-arrow-left me-1"></i>Browse Other Collections
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Back Navigation -->
    <div class="text-center mt-5">
        <a href="{{ route('collections.index') }}" class="btn btn-outline-primary me-3">
            <i class="ti ti-arrow-left me-1"></i>Back to Collections
        </a>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
            <i class="ti ti-home me-1"></i>Home
        </a>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Sort functionality
    $('#sortProducts').on('change', function() {
        const sortValue = $(this).val();
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('sort', sortValue);
        currentUrl.searchParams.delete('page'); // Reset pagination
        window.location.href = currentUrl.toString();
    });

    // Per page functionality
    $('#perPage').on('change', function() {
        const perPageValue = $(this).val();
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('per_page', perPageValue);
        currentUrl.searchParams.delete('page'); // Reset pagination
        window.location.href = currentUrl.toString();
    });

    // Set current sort value
    const urlParams = new URLSearchParams(window.location.search);
    const currentSort = urlParams.get('sort');
    if (currentSort) {
        $('#sortProducts').val(currentSort);
    }

    // Add to cart functionality
    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();
        
        const productId = $(this).data('product-id');
        const button = $(this);
        const originalHtml = button.html();
        
        // Disable button and show loading
        button.prop('disabled', true);
        button.html('<i class="ti ti-loader" style="animation: spin 1s linear infinite; font-size: 1rem;"></i>');
        
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
                    
                    // Update button
                    button.html('<i class="ti ti-check" style="font-size: 1rem;"></i>');
                    button.removeClass('btn-outline-primary').addClass('btn-success');
                    
                    setTimeout(function() {
                        button.html(originalHtml);
                        button.removeClass('btn-success').addClass('btn-outline-primary');
                        button.prop('disabled', false);
                    }, 2000);
                } else {
                    const errorMsg = response.message || 'Failed to add product to cart';
                    toastr.error(errorMsg);
                    button.html(originalHtml);
                    button.prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error('Cart error:', xhr.responseText);
                toastr.error('Something went wrong. Please try again.');
                button.html(originalHtml);
                button.prop('disabled', false);
            }
        });
    });

    // Add to wishlist functionality
    $('.add-to-wishlist').on('click', function(e) {
        e.preventDefault();
        
        const productId = $(this).data('product-id');
        const button = $(this);
        const originalHtml = button.html();
        
        button.prop('disabled', true);
        button.html('<i class="ti ti-loader" style="animation: spin 1s linear infinite; font-size: 1rem;"></i>');
        
        $.ajax({
            url: '{{ route("wishlist.add") }}',
            method: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    // Show success message
                    toastr.success('Product added to wishlist successfully!');
                    
                    // Update button
                    button.html('<i class="ti ti-heart-filled" style="font-size: 1rem;"></i>');
                    button.removeClass('btn-outline-secondary').addClass('btn-danger');
                    
                    setTimeout(function() {
                        button.html(originalHtml);
                        button.removeClass('btn-danger').addClass('btn-outline-secondary');
                        button.prop('disabled', false);
                    }, 2000);
                } else {
                    const errorMsg = response.message || 'Failed to add product to wishlist';
                    toastr.error(errorMsg);
                    button.html(originalHtml);
                    button.prop('disabled', false);
                }
            },
            error: function() {
                toastr.error('Something went wrong. Please try again.');
                button.html(originalHtml);
                button.prop('disabled', false);
            }
        });
    });
});

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

// Add spin animation for loading icon
$('<style>').prop('type', 'text/css').html(`
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
`).appendTo('head');
</script>
@endpush

@endsection
