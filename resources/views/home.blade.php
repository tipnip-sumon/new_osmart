@extends('layouts.app')

@section('title', 'Home - ' . config('app.name'))
@section('description', 'Welcome to ' . config('app.name') . ' - Your premier multivendor ecommerce destination')

@push('styles')
<style>
.welcome-card {
    border-radius: 15px !important;
    overflow: hidden;
}

.user-avatar {
    transition: all 0.3s ease;
}

.user-avatar:hover {
    transform: scale(1.1);
}

.welcome-content h4, .welcome-content h5 {
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.user-details span {
    font-size: 0.9rem;
    background: rgba(255,255,255,0.1);
    padding: 4px 8px;
    border-radius: 20px;
    backdrop-filter: blur(10px);
}

.btn-light {
    border: none;
    border-radius: 25px;
    padding: 8px 16px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-light:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* Mobile optimizations */
@media (max-width: 767.98px) {
    .welcome-card .card-body {
        padding: 1rem !important;
    }
    
    .user-avatar {
        width: 40px !important;
        height: 40px !important;
        font-size: 1.2rem !important;
    }
    
    .welcome-content h5 {
        font-size: 1.1rem;
    }
    
    .welcome-content small {
        font-size: 0.75rem;
    }
}

/* Animation */
.welcome-card {
    animation: slideInDown 0.6s ease-out;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@section('header')
<!-- Search Form-->
<div class="container">
    <div class="search-form pt-3 rtl-flex-d-row-r">
        <form action="{{ route('search') }}" method="GET">
            <input class="form-control" type="search" name="q" placeholder="Search in {{ config('app.name') }}" value="{{ request('q') }}">
            <button type="submit"><i class="ti ti-search"></i></button>
        </form>
        <!-- Alternative Search Options -->
        <div class="alternative-search-options">
            <div class="dropdown">
                <a class="btn btn-primary dropdown-toggle" id="altSearchOption" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-adjustments-horizontal"></i>
                </a>
                <!-- Dropdown Menu -->
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="altSearchOption">
                    <li><a class="dropdown-item" href="#"><i class="ti ti-microphone"></i>Voice</a></li>
                    <li><a class="dropdown-item" href="#"><i class="ti ti-layout-collage"></i>Image</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
@php
// Helper function to safely get product image using comprehensive legacy format handling
function getProductImageSrc($product, $defaultImage = 'assets/img/product/1.png') {
    // First try to get the images array
    if (isset($product->images) && $product->images) {
        $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
        
        if (is_array($images) && !empty($images)) {
            $image = $images[0]; // Get first image
            
            // Handle legacy format with type checking
            $legacyImageUrl = '';
            if (is_string($image)) {
                // Try storage path first, then uploads
                $legacyImageUrl = asset('storage/' . $image);
            } elseif (is_array($image) && isset($image['url']) && is_string($image['url'])) {
                $legacyImageUrl = $image['url'];
            } elseif (is_array($image) && isset($image['path']) && is_string($image['path'])) {
                $legacyImageUrl = asset('storage/' . $image['path']);
            } else {
                $legacyImageUrl = asset($defaultImage); // Use provided default
            }
            
            return $legacyImageUrl;
        }
    }
    
    // Fallback to the image accessor
    $productImage = $product->image;
    if ($productImage && $productImage !== 'products/product1.jpg') {
        // Use actual product image
        return str_starts_with($productImage, 'http') ? $productImage : asset('storage/' . $productImage);
    }
    
    // Final fallback to default image
    return asset($defaultImage);
}
@endphp

@auth
<!-- User Welcome Section -->
<div class="user-welcome-wrapper py-3">
    <div class="container">
        <div class="card welcome-card shadow-sm border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="user-avatar d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; color: white; font-size: 1.5rem;">
                            <i class="ti ti-user"></i>
                        </div>
                    </div>
                    <div class="col">
                        <div class="welcome-content">
                            <h6 class="text-white mb-1">
                                <strong>Welcome back,</strong>
                            </h6>
                            <!-- Mobile Version - Compact Display -->
                            <div class="d-block d-md-none">
                                <h5 class="text-white mb-1 fw-bold">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h5>
                                <small class="text-white-50 d-block">
                                    {{ Auth::user()->username ?? 'N/A' }}
                                </small>
                            </div>
                            <!-- Desktop Version - Full Display -->
                            <div class="d-none d-md-block">
                                <h4 class="text-white mb-1 fw-bold">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h4>
                                <div class="user-details d-flex align-items-center flex-wrap gap-3">
                                    <span class="text-white-50">
                                        <i class="ti ti-at me-1"></i>{{ Auth::user()->username ?? 'N/A' }}
                                    </span>
                                    {{-- <span class="text-white-50">
                                        <i class="ti ti-id me-1"></i>ID: #{{ str_pad(Auth::user()->id, 6, '0', STR_PAD_LEFT) }}
                                    </span> --}}
                                    <span class="text-white-50">
                                        <i class="ti ti-mail me-1"></i>{{ Auth::user()->email }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto d-none d-md-block">
                        <div class="welcome-actions">
                            <a href="{{ route('profile.show') }}" class="btn btn-light btn-sm">
                                <i class="ti ti-user-circle me-1"></i>Profile
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Mobile Actions Row -->
                <div class="row mt-2 d-block d-md-none">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-white-50">
                                <i class="ti ti-mail me-1"></i>{{ Str::limit(Auth::user()->email, 25) }}
                            </small>
                            <a href="{{ route('profile.show') }}" class="btn btn-light btn-sm">
                                <i class="ti ti-user-circle me-1"></i>Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endauth

<!-- Hero Wrapper -->
<div class="hero-wrapper">
    <div class="container">
        <div class="pt-3">
            <!-- Hero Slides-->
            <div class="hero-slides owl-carousel">
                @forelse($banners ?? [] as $banner)
                <!-- Single Hero Slide-->
                <div class="single-hero-slide" style="background-image: url('{{ asset('storage/' . $banner->image) }}')">
                    <div class="slide-content h-100 d-flex align-items-center">
                        <div class="slide-text">
                            <h4 class="text-white mb-0" data-animation="fadeInUp" data-delay="100ms" data-duration="1000ms">{{ $banner->title }}</h4>
                            <p class="text-white" data-animation="fadeInUp" data-delay="400ms" data-duration="1000ms">{{ $banner->description }}</p>
                            <a class="btn btn-primary" href="{{ $banner->link }}" data-animation="fadeInUp" data-delay="800ms" data-duration="1000ms">{{ $banner->button_text }}</a>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Default Hero Banner -->
                <div class="single-hero-slide" style="background-image: url('{{ asset('assets/img/bg-img/1.jpg') }}')">
                    <div class="slide-content h-100 d-flex align-items-center">
                        <div class="slide-text">
                            <h4 class="text-white mb-0" data-animation="fadeInUp" data-delay="100ms" data-duration="1000ms">Welcome to {{ config('app.name') }}</h4>
                            <p class="text-white" data-animation="fadeInUp" data-delay="400ms" data-duration="1000ms">Your Premier Shopping Destination</p>
                            <a class="btn btn-primary" href="{{ route('shop.grid') }}" data-animation="fadeInUp" data-delay="800ms" data-duration="1000ms">Shop Now</a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Product Categories -->
<div class="product-catagories-wrapper py-3">
    <div class="container">
        <div class="row g-2 rtl-flex-d-row-r">
            @if(isset($categories) && $categories->count() > 0)
                @foreach($categories as $category)
                <!-- Dynamic Category Card -->
                <div class="col-3">
                    <div class="card catagory-card">
                        <div class="card-body px-2">
                            <a href="{{ route('categories.show', $category->slug) }}">
                                <img src="{{ 
                                    $category->image ? 
                                        (str_starts_with($category->image, 'http') ? 
                                            $category->image : 
                                            asset('storage/' . $category->image)
                                        ) : 
                                        asset('assets/img/core-img/tv-table.png') 
                                }}" 
                                alt="{{ $category->name }}"
                                onerror="this.src='{{ asset('assets/img/core-img/tv-table.png') }}'">
                                <span>{{ $category->name }}</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
            <!-- Default Categories (Fallback) -->
            <div class="col-3">
                <div class="card catagory-card">
                    <div class="card-body px-2">
                        <a href="{{ route('shop.grid') }}">
                            <img src="{{ asset('assets/img/core-img/woman-clothes.png') }}" alt="Women's Fashion">
                            <span>Women's Fashion</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card catagory-card">
                    <div class="card-body px-2">
                        <a href="{{ route('shop.grid') }}">
                            <img src="{{ asset('assets/img/core-img/grocery.png') }}" alt="Groceries & Pets">
                            <span>Groceries & Pets</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card catagory-card">
                    <div class="card-body px-2">
                        <a href="{{ route('shop.grid') }}">
                            <img src="{{ asset('assets/img/core-img/shampoo.png') }}" alt="Health & Beauty">
                            <span>Health & Beauty</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card catagory-card">
                    <div class="card-body px-2">
                        <a href="{{ route('shop.grid') }}">
                            <img src="{{ asset('assets/img/core-img/rowboat.png') }}" alt="Sports & Outdoors">
                            <span>Sports & Outdoors</span>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Flash Sale Wrapper -->
<div class="flash-sale-wrapper">
    <div class="container">
        <div class="section-heading d-flex align-items-center justify-content-between rtl-flex-d-row-r">
            <h6>Flash Sale</h6>
            <a class="btn p-0" href="{{ route('flash-sale') }}">View All<i class="ms-1 ti ti-arrow-right"></i></a>
        </div>
        <!-- Flash Sale Slide -->
        <div class="flash-sale-slide owl-carousel">
            @forelse($flashSaleProducts ?? [] as $product)
            <!-- Flash Sale Card -->
            <div class="card flash-sale-card">
                <div class="card-body">
                    <a href="{{ route('products.show', $product->slug) }}">
                        @php
                            // Dynamic image handling for flash sale products
                            $legacyImageUrl = '';
                            
                            // First try images array
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
                                    $legacyImageUrl = asset('assets/img/product/1.png'); // Default for flash sale
                                }
                            }
                        @endphp
                        <img src="{{ $legacyImageUrl }}" 
                             alt="{{ $product->name }}"
                             style="width: 100%; height: 120px; object-fit: cover;"
                             onerror="this.src='{{ asset('assets/img/product/1.png') }}'">
                        <span class="product-title">{{ Str::limit($product->name, 15) }}</span>
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <p class="sale-price">৳{{ number_format($product->sale_price, 0) }}</p>
                            <p class="real-price">৳{{ number_format($product->price, 0) }}</p>
                        @else
                            <p class="sale-price">৳{{ number_format($product->price, 0) }}</p>
                        @endif
                    </a>
                </div>
            </div>
            @empty
            <!-- No flash sale products -->
            <div class="col-12 text-center py-4">
                <p class="text-muted">No flash sale products available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Weekly Best Sellers-->
<div class="weekly-best-seller-area py-3">
    <div class="container">
        <div class="section-heading d-flex align-items-center justify-content-between rtl-flex-d-row-r">
            <h6>Weekly Best Sellers</h6>
            <a class="btn p-0" href="{{ route('products.bestsellers') }}">View All<i class="ms-1 ti ti-arrow-right"></i></a>
        </div>
        <div class="row g-2">
            @forelse($bestSellingProducts ?? $featuredProducts ?? [] as $product)
            <!-- Weekly Product Card -->
            <div class="col-12 col-md-6">
                <div class="card horizontal-product-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="product-thumbnail-side">
                            <a class="product-thumbnail d-block" href="{{ route('products.show', $product->slug) }}">
                                @php
                                    // Dynamic image handling for best sellers
                                    $legacyImageUrl = '';
                                    
                                    // First try images array
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
                                            $legacyImageUrl = asset('assets/img/product/3.png'); // Default for best sellers
                                        }
                                    }
                                @endphp
                                <img src="{{ $legacyImageUrl }}" 
                                     alt="{{ $product->name }}"
                                     style="width: 100%; height: 80px; object-fit: cover;"
                                     onerror="this.src='{{ asset('assets/img/product/3.png') }}'">
                            </a>
                            <a class="wishlist-btn" href="{{ route('wishlist.toggle', $product->id) }}"><i class="ti ti-heart"></i></a>
                        </div>
                        <div class="product-description">
                            <a class="product-title d-block" href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                            <p class="sale-price">
                                <i class="ti ti-currency-taka"></i>
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    {{ number_format($product->sale_price, 0) }}
                                    <span class="real-price">৳{{ number_format($product->price, 0) }}</span>
                                @else
                                    {{ number_format($product->price, 0) }}
                                @endif
                            </p>
                            <div class="product-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="ti ti-star{{ $i <= $product->rating ? '-filled' : '' }}"></i>
                                @endfor
                                <span class="rating-counter">({{ $product->reviews_count }})</span>
                            </div>
                            <button class="btn btn-success btn-sm add-to-cart-btn" 
                                    onclick="quickAddToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ $legacyImageUrl }}')">
                                <i class="ti ti-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <!-- No products -->
            <div class="col-12 text-center py-4">
                <p class="text-muted">No best selling products available.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Featured Products -->
<div class="featured-products-area py-3">
    <div class="container">
        <div class="section-heading d-flex align-items-center justify-content-between rtl-flex-d-row-r">
            <h6>Featured Products</h6>
            <a class="btn p-0" href="{{ route('products.featured') }}">View All<i class="ms-1 ti ti-arrow-right"></i></a>
        </div>
        <div class="row g-2">
            @forelse($featuredProducts ?? [] as $product)
            <!-- Featured Product Card -->
            <div class="col-12 col-md-6">
                <div class="card horizontal-product-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="product-thumbnail-side">
                            <a class="product-thumbnail d-block" href="{{ route('products.show', $product->slug ?: $product->id) }}">
                                @php
                                    // Dynamic image handling for featured products
                                    $legacyImageUrl = '';
                                    
                                    // First try images array
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
                                            $legacyImageUrl = asset('assets/img/product/5.png'); // Default for featured
                                        }
                                    }
                                @endphp
                                <img src="{{ $legacyImageUrl }}" 
                                     alt="{{ $product->name }}"
                                     style="width: 100%; height: 80px; object-fit: cover;"
                                     onerror="this.src='{{ asset('assets/img/product/5.png') }}'">
                            </a>
                            <a class="wishlist-btn" href="#" onclick="toggleWishlist(event, {{ $product->id }}); return false;">
                                <i class="ti ti-heart"></i>
                            </a>
                        </div>
                        <div class="product-description">
                            <a class="product-title d-block" href="{{ route('products.show', $product->slug ?: $product->id) }}">
                                {{ Str::limit($product->name, 30) }}
                            </a>
                            <p class="sale-price">
                                <i class="ti ti-currency-taka"></i>
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    {{ number_format($product->sale_price, 0) }}
                                    <span class="real-price">৳{{ number_format($product->price, 0) }}</span>
                                @else
                                    {{ number_format($product->price, 0) }}
                                @endif
                            </p>
                            @if($product->brand)
                                <small class="text-muted d-block">{{ is_object($product->brand) ? $product->brand->name : $product->brand }}</small>
                            @endif
                            <div class="product-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="ti ti-star{{ $i <= ($product->average_rating ?? 4) ? '-filled' : '' }}"></i>
                                @endfor
                                <span class="rating-counter">({{ $product->review_count ?? 0 }})</span>
                            </div>
                            <button class="btn btn-success btn-sm add-to-cart-btn" 
                                    onclick="quickAddToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->sale_price ?? $product->price }}, '{{ $legacyImageUrl }}')">
                                <i class="ti ti-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <!-- No products -->
            <div class="col-12 text-center py-4">
                <p class="text-muted">No featured products available.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Collections -->
<div class="collection-wrapper py-3">
    <div class="container">
        <div class="section-heading d-flex align-items-center justify-content-between rtl-flex-d-row-r">
            <h6>Collections</h6>
            <a class="btn p-0" href="{{ route('collections.index') }}">View All<i class="ms-1 ti ti-arrow-right"></i></a>
        </div>
        <div class="row g-2 rtl-flex-d-row-r">
            @forelse($collections ?? [] as $collection)
            <!-- Collection Card -->
            <div class="col-6 col-md-4">
                <div class="card collection-card">
                    <div class="card-body text-center">
                        <a href="{{ route('collections.show', $collection->slug) }}">
                            @php
                                // Dynamic image handling for collections
                                $collectionImageUrl = '';
                                
                                // First try image_data array
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
                                
                                // Final fallback to default image
                                if (empty($collectionImageUrl)) {
                                    $collectionImageUrl = asset('assets/img/product/default.png');
                                }
                            @endphp
                            <img src="{{ $collectionImageUrl }}" 
                                 alt="{{ $collection->name }}"
                                 style="width: 100%; height: 120px; object-fit: cover;"
                                 onerror="this.src='{{ asset('assets/img/product/default.png') }}'">>
                            <h6 class="collection-title">{{ $collection->name }}</h6>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <!-- No collections available -->
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Test toastr on page load
    // Test toastr with a simple message after 2 seconds
    // setTimeout(function() {
    //     if (typeof toastr !== 'undefined') {
    //         toastr.info('Home page loaded successfully!');
    //     } else {
    //         console.error('Toastr is not available');
    //     }
    // }, 2000);
    
    // Note: quickAddToCart function is defined globally in app.blade.php

    // Home page specific initialization

    // Toggle wishlist function
    window.toggleWishlist = function(event, productId) {
        // Prevent default link behavior
        if (event) {
            event.preventDefault();
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            console.error('CSRF token not found');
            if (typeof toastr !== 'undefined') {
                toastr.error('Security token missing. Please refresh the page.');
            } else {
                alert('Security token missing. Please refresh the page.');
            }
            return;
        }
        
        fetch('{{ route("wishlist.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (typeof toastr !== 'undefined') {
                    toastr.success(data.message);
                } else {
                    alert(data.message);
                }
                
                // Toggle heart icon
                if (event && event.target) {
                    const heartIcon = event.target.closest('a').querySelector('i');
                    if (heartIcon) {
                        if (data.action === 'added') {
                            heartIcon.classList.add('ti-heart-filled');
                            heartIcon.classList.remove('ti-heart');
                        } else {
                            heartIcon.classList.add('ti-heart');
                            heartIcon.classList.remove('ti-heart-filled');
                        }
                    }
                }
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error(data.message || 'Failed to update wishlist');
                } else {
                    alert(data.message || 'Failed to update wishlist');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error('Error updating wishlist: ' + error.message);
            } else {
                alert('Error updating wishlist: ' + error.message);
            }
        });
    };

    // Note: updateCartCount function is defined globally in app.blade.php
});
</script>

<style>
/* PWA Install Prompt Styles */
.pwa-install-alert {
    position: fixed;
    top: 10px;
    left: 50%;
    transform: translateX(-50%) translateY(-20px);
    z-index: 1060;
    max-width: 90%;
    width: 400px;
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    opacity: 0;
    transition: all 0.3s ease;
}

.pwa-install-alert.show {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

.pwa-install-alert .toast-body {
    padding: 1.5rem;
}

.pwa-install-alert .content img {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    margin-right: 0.75rem;
}

.pwa-install-alert h6 {
    font-weight: 600;
    color: #2d3748;
    margin: 0;
}

.pwa-install-alert .btn-close {
    background: none;
    border: none;
    opacity: 0.6;
    font-size: 1.2rem;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pwa-install-alert .btn-close:hover {
    opacity: 1;
}

.pwa-install-alert .btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.pwa-install-alert .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.pwa-install-alert .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.pwa-install-alert .btn-success {
    background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
    border: none;
}

.pwa-install-alert .btn-outline-secondary {
    border-color: #e2e8f0;
    color: #4a5568;
}

.pwa-install-alert .btn-outline-secondary:hover {
    background: #f7fafc;
    border-color: #cbd5e0;
    transform: translateY(-1px);
}

/* Mobile responsive */
@media (max-width: 576px) {
    .pwa-install-alert {
        top: 5px;
        width: 95%;
        max-width: none;
    }
    
    .pwa-install-alert .toast-body {
        padding: 1rem;
    }
    
    .pwa-install-alert .content img {
        width: 32px;
        height: 32px;
        margin-right: 0.5rem;
    }
    
    .pwa-install-alert h6 {
        font-size: 0.9rem;
    }
    
    .pwa-install-alert .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
    }
}

/* Custom SweetAlert styles for install guide */
.install-guide-popup {
    border-radius: 15px !important;
}

.install-guide-popup ul {
    text-align: left !important;
    margin: 1rem auto !important;
    display: inline-block !important;
    list-style-type: disc !important;
    padding-left: 1.5rem !important;
}

.install-guide-popup li {
    margin-bottom: 0.5rem !important;
    color: #4a5568 !important;
}
</style>
@endpush
