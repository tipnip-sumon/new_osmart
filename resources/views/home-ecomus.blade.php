@extends('layouts.ecomus')

@section('title', 'Home - ' . config('app.name'))
@section('description', 'Welcome to ' . config('app.name') . ' - Your premier multivendor ecommerce destination with modern design and seamless shopping experience')

@push('styles')
<style>
/* Custom styles for Laravel integration */
.announcement-bar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.tf-product-card {
    transition: all 0.3s ease;
}

.tf-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .slider-effect .box-content h1 {
        font-size: 2rem;
    }
    
    .slider-effect .box-content .desc {
        font-size: 0.9rem;
    }
}

/* Quick View Modal Fix */
.modal#quick_view {
    z-index: 9999 !important;
}

.modal#quick_view .modal-dialog {
    max-width: 90vw;
    width: 100%;
    margin: 20px auto;
}

.modal#quick_view .modal-content {
    border: none;
    border-radius: 10px;
    min-height: 500px;
}

.modal#quick_view .header {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 10;
}

.modal#quick_view .icon-close-popup {
    background: rgba(255,255,255,0.9);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 18px;
    color: #333;
}

.modal#quick_view .icon-close-popup:hover {
    background: rgba(255,255,255,1);
}

/* Flash Sale Styles */
.flash-sale-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    margin: 10px;
    overflow: hidden;
}

.flash-sale-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.flash-sale-card .card-body {
    padding: 15px;
    text-align: center;
}

.flash-sale-card .product-title {
    display: block;
    font-weight: 600;
    margin: 10px 0 5px;
    color: #333;
    text-decoration: none;
}

.flash-sale-card .sale-price {
    color: #e74c3c;
    font-weight: bold;
    font-size: 16px;
    margin-bottom: 5px;
}

.flash-sale-card .real-price {
    color: #999;
    text-decoration: line-through;
    font-size: 14px;
    margin-bottom: 0;
}

.flash-sale-card a {
    text-decoration: none;
    color: inherit;
}

.owl-carousel .owl-item {
    padding: 5px;
}
</style>
@endpush

@section('content')
@php
// Helper function to safely get product image using comprehensive legacy format handling
function getProductImageSrc($product, $defaultImage = 'assets/ecomus/images/products/default-product.jpg') {
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

// Helper function for category images
function getCategoryImageSrc($category, $defaultImage = 'assets/ecomus/images/collections/default-category.jpg') {
    if ($category && $category->image) {
        return str_starts_with($category->image, 'http') ? $category->image : asset('storage/' . $category->image);
    }
    return asset($defaultImage);
}
@endphp

@section('content')
<!-- Slider -->
<section class="tf-slideshow slideshow-effect slider-effect-fade position-relative">
    <div dir="ltr" class="swiper tf-sw-effect">
        <div class="swiper-wrapper">
            <div class="swiper-slide" lazy="true">
                <div class="slider-effect wrap-slider">
                    <div class="content-left">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="box-content">
                                        <h1 class="heading fade-item fade-item-1">Summer<br> Escapades</h1>
                                        <p class="desc fade-item fade-item-2">Embrace the sun-kissed season with our collection of breezy</p>
                                        <a href="{{ route('collections.show', 'summer') }}" class="fade-item fade-item-3 tf-btn btn-light-icon animate-hover-btn btn-xl radius-3">
                                            <span>Shop collection</span><i class="icon icon-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="img-slider">
                        <img class="lazyload" data-src="{{ asset('assets/ecomus/images/slider/fashion-06-slide1.jpg') }}" alt="fashion-slideshow" src="{{ asset('assets/ecomus/images/slider/fashion-06-slide1.jpg') }}">
                    </div>
                </div>
            </div>
            <div class="swiper-slide" lazy="true">
                <div class="slider-effect wrap-slider">
                    <div class="content-left">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="box-content">
                                        <h1 class="heading fade-item fade-item-1">Multi-faceted<br> Beauty</h1>
                                        <p class="desc fade-item fade-item-2">Embrace the sun-kissed season with our collection of breezy</p>
                                        <a href="{{ route('collections.show', 'beauty') }}" class="fade-item fade-item-3 tf-btn btn-light-icon animate-hover-btn btn-xl radius-3">
                                            <span>Shop collection</span><i class="icon icon-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="img-slider">
                        <img class="lazyload" data-src="{{ asset('assets/ecomus/images/slider/fashion-06-slide2.jpg') }}" src="{{ asset('assets/ecomus/images/slider/fashion-06-slide2.jpg') }}" alt="fashion-slideshow">
                    </div>
                </div>
            </div>
            <div class="swiper-slide" lazy="true">
                <div class="slider-effect wrap-slider">
                    <div class="content-left">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="box-content">
                                        <h1 class="heading fade-item fade-item-1">Effortless<br> Elegance</h1>
                                        <p class="desc fade-item fade-item-2">Embrace the sun-kissed season with our collection of breezy</p>
                                        <a href="{{ route('collections.show', 'elegance') }}" class="fade-item fade-item-3 tf-btn btn-light-icon animate-hover-btn btn-xl radius-3">
                                            <span>Shop collection</span><i class="icon icon-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="img-slider">
                        <img class="lazyload" data-src="{{ asset('assets/ecomus/images/slider/fashion-06-slide3.jpg') }}" src="{{ asset('assets/ecomus/images/slider/fashion-06-slide3.jpg') }}" alt="fashion-slideshow">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="wrap-pagination">
        <div class="container">
            <div class="sw-dots line-pagination sw-pagination-slider"></div>
        </div>
    </div>
</section>
<!-- /Slider -->

<!-- Collection -->
<section class="flat-spacing-12 bg_grey-3">
    <div class="container">
        <div class="flat-title flex-row justify-content-between align-items-center px-0 wow fadeInUp" data-wow-delay="0s">
            <h3 class="title">Season Collection</h3>
            <a href="{{ route('collections.index') }}" class="tf-btn btn-line">View all categories<i class="icon icon-arrow1-top-left"></i></a>
        </div>
        <div class="hover-sw-nav hover-sw-2">
            <div dir="ltr" class="swiper tf-sw-collection" data-preview="6" data-tablet="3" data-mobile="2" data-space-lg="50" data-space-md="30" data-space="15" data-loop="false" data-auto-play="false">
                <div class="swiper-wrapper">
                    @forelse($categories ?? [] as $category)
                    <div class="swiper-slide" lazy="true">
                        <div class="collection-item-circle hover-img">
                            <a href="{{ route('categories.show', $category->slug) }}" class="collection-image img-style">
                                <img class="lazyload" data-src="{{ getCategoryImageSrc($category, 'assets/ecomus/images/collections/collection-circle-' . (($loop->index % 6) + 1) . '.jpg') }}" src="{{ getCategoryImageSrc($category, 'assets/ecomus/images/collections/collection-circle-' . (($loop->index % 6) + 1) . '.jpg') }}" alt="{{ $category->name }}">
                            </a>
                            <div class="collection-content text-center">
                                <a href="{{ route('categories.show', $category->slug) }}" class="link title fw-5">{{ $category->name }}</a>
                                <div class="count">{{ $category->products_count ?? 0 }} items</div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <!-- Default Categories -->
                    <div class="swiper-slide" lazy="true">
                        <div class="collection-item-circle hover-img">
                            <a href="{{ route('shop.index') }}" class="collection-image img-style">
                                <img class="lazyload" data-src="{{ asset('assets/ecomus/images/collections/collection-circle-1.jpg') }}" src="{{ asset('assets/ecomus/images/collections/collection-circle-1.jpg') }}" alt="collection-img">
                            </a>
                            <div class="collection-content text-center">
                                <a href="{{ route('shop.index') }}" class="link title fw-5">Women's</a>
                                <div class="count">23 items</div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="collection-item-circle hover-img">
                            <a href="{{ route('shop.index') }}" class="collection-image img-style">
                                <img class="lazyload" data-src="{{ asset('assets/ecomus/images/collections/collection-circle-2.jpg') }}" src="{{ asset('assets/ecomus/images/collections/collection-circle-2.jpg') }}" alt="collection-img">
                            </a>
                            <div class="collection-content text-center">
                                <a href="{{ route('shop.index') }}" class="link title fw-5">Men's</a>
                                <div class="count">9 items</div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="collection-item-circle hover-img">
                            <a href="{{ route('shop.index') }}" class="collection-image img-style">
                                <img class="lazyload" data-src="{{ asset('assets/ecomus/images/collections/collection-circle-3.jpg') }}" src="{{ asset('assets/ecomus/images/collections/collection-circle-3.jpg') }}" alt="collection-img">
                            </a>
                            <div class="collection-content text-center">
                                <a href="{{ route('shop.index') }}" class="link title fw-5">Accessories</a>
                                <div class="count">12 items</div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="collection-item-circle hover-img">
                            <a href="{{ route('shop.index') }}" class="collection-image img-style">
                                <img class="lazyload" data-src="{{ asset('assets/ecomus/images/collections/collection-circle-4.jpg') }}" src="{{ asset('assets/ecomus/images/collections/collection-circle-4.jpg') }}" alt="collection-img">
                            </a>
                            <div class="collection-content text-center">
                                <a href="{{ route('shop.index') }}" class="link title fw-5">Shoes</a>
                                <div class="count">16 items</div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="collection-item-circle hover-img">
                            <a href="{{ route('shop.index') }}" class="collection-image img-style">
                                <img class="lazyload" data-src="{{ asset('assets/ecomus/images/collections/collection-circle-5.jpg') }}" src="{{ asset('assets/ecomus/images/collections/collection-circle-5.jpg') }}" alt="collection-img">
                            </a>
                            <div class="collection-content text-center">
                                <a href="{{ route('shop.index') }}" class="link title fw-5">Bags</a>
                                <div class="count">8 items</div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="collection-item-circle hover-img">
                            <a href="{{ route('shop.index') }}" class="collection-image img-style">
                                <img class="lazyload" data-src="{{ asset('assets/ecomus/images/collections/collection-circle-6.jpg') }}" src="{{ asset('assets/ecomus/images/collections/collection-circle-6.jpg') }}" alt="collection-img">
                            </a>
                            <div class="collection-content text-center">
                                <a href="{{ route('shop.index') }}" class="link title fw-5">Jewelry</a>
                                <div class="count">14 items</div>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="nav-sw nav-next-slider nav-next-collection box-icon w_46 round"><span class="icon icon-arrow-left"></span></div>
            <div class="nav-sw nav-prev-slider nav-prev-collection box-icon w_46 round"><span class="icon icon-arrow-right"></span></div>
        </div>
    </div>
</section>
<!-- /Collection -->

@if(isset($featuredProducts) && $featuredProducts->count() > 0)
<!-- Product -->
<section class="flat-spacing-1">
    <div class="container">
        <div class="flat-title wow fadeInUp" data-wow-delay="0s">
            <span class="title">Best Seller</span>
            <p class="sub-title">Shop the Latest Styles: Stay ahead of the curve with our newest arrivals</p>
        </div>
        <div class="hover-sw-nav hover-sw-2">
            <div dir="ltr" class="swiper tf-sw-product-sell wrap-sw-over" data-preview="4" data-tablet="3" data-mobile="2" data-space-lg="30" data-space-md="15" data-pagination="2" data-pagination-md="3" data-pagination-lg="3">
                <div class="swiper-wrapper">
                    @forelse($bestSellingProducts ?? $featuredProducts ?? [] as $product  )
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
                        
                        // Handle hover image (second image from gallery)
                        $hoverImageUrl = $legacyImageUrl; // Default to same image
                        if (isset($product->images) && $product->images) {
                            $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                            if (is_array($images) && count($images) > 1) {
                                $hoverImage = $images[1]; // Get second image
                                
                                // Handle complex nested structure for hover image
                                if (is_array($hoverImage) && isset($hoverImage['sizes']['medium']['storage_url'])) {
                                    $hoverImageUrl = $hoverImage['sizes']['medium']['storage_url'];
                                } elseif (is_array($hoverImage) && isset($hoverImage['sizes']['original']['storage_url'])) {
                                    $hoverImageUrl = $hoverImage['sizes']['original']['storage_url'];
                                } elseif (is_array($hoverImage) && isset($hoverImage['sizes']['large']['storage_url'])) {
                                    $hoverImageUrl = $hoverImage['sizes']['large']['storage_url'];
                                } elseif (is_array($hoverImage) && isset($hoverImage['urls']['medium'])) {
                                    $hoverImageUrl = $hoverImage['urls']['medium'];
                                } elseif (is_array($hoverImage) && isset($hoverImage['urls']['original'])) {
                                    $hoverImageUrl = $hoverImage['urls']['original'];
                                } elseif (is_array($hoverImage) && isset($hoverImage['url']) && is_string($hoverImage['url'])) {
                                    $hoverImageUrl = $hoverImage['url'];
                                } elseif (is_array($hoverImage) && isset($hoverImage['path']) && is_string($hoverImage['path'])) {
                                    $hoverImageUrl = asset('storage/' . $hoverImage['path']);
                                } elseif (is_string($hoverImage)) {
                                    $hoverImageUrl = asset('storage/' . $hoverImage);
                                }
                            }
                        }
                    @endphp
                    <div class="swiper-slide" lazy="true">
                        <div class="card-product" data-product-id="{{ $product->id }}">
                            <div class="card-product-wrapper">
                                <a href="{{ route('products.show', $product->slug) }}" class="product-img">
                                    <img class="lazyload img-product" 
                                         data-src="{{ $legacyImageUrl }}" 
                                         src="{{ $legacyImageUrl }}" 
                                         alt="{{ $product->name }}"
                                         onerror="this.src='{{ asset('assets/ecomus/images/products/default-product.jpg') }}'; this.onerror=null;">
                                    <img class="lazyload img-hover" 
                                         data-src="{{ $hoverImageUrl }}" 
                                         src="{{ $hoverImageUrl }}" 
                                         alt="{{ $product->name }}"
                                         onerror="this.src='{{ asset('assets/ecomus/images/products/default-product.jpg') }}'; this.onerror=null;">
                                </a>
                                <div class="list-product-btn">
                                    <button type="button" class="box-icon bg_white quick-add tf-btn-loading" data-action="add-to-cart" data-product-id="{{ $product->id }}">
                                        <span class="icon icon-bag"></span>
                                        <span class="tooltip">Quick Add</span>
                                    </button>
                                    <button type="button" class="box-icon bg_white wishlist btn-icon-action" data-action="add-to-wishlist" data-product-id="{{ $product->id }}">
                                        <span class="icon icon-heart"></span>
                                        <span class="tooltip">Add to Wishlist</span>
                                        <span class="icon icon-delete"></span>
                                    </button>
                                    <a href="#compare" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft" class="box-icon bg_white compare btn-icon-action">
                                        <span class="icon icon-compare"></span>
                                        <span class="tooltip">Add to Compare</span>
                                        <span class="icon icon-check"></span>
                                    </a>
                                    <button type="button" class="box-icon bg_white quickview tf-btn-loading" 
                                            data-bs-toggle="modal" data-bs-target="#quick_view"
                                            data-action="quick-view" data-product-id="{{ $product->id }}" data-product-slug="{{ $product->slug }}">
                                        <span class="icon icon-view"></span>
                                        <span class="tooltip">Quick View</span>
                                    </button>
                                </div>
                                @if($product->discount > 0)
                                <div class="on-sale-wrap">
                                    <div class="on-sale-item">{{ $product->discount }}% OFF</div>
                                </div>
                                @endif
                            </div>
                            <div class="card-product-info">
                                <a href="{{ route('products.show', $product->slug) }}" class="title link">{{ $product->name }}</a>
                                <span class="price">
                                    @if($product->sale_price)
                                        <span class="compare-at-price">{{ formatCurrency($product->price) }}</span>
                                        <span class="price-on-sale fw-6">{{ formatCurrency($product->sale_price) }}</span>
                                    @else
                                        <span class="fw-6">{{ formatCurrency($product->price) }}</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <!-- No products available -->
                    <div class="swiper-slide">
                        <div class="text-center py-5">
                            <p class="text-muted">No products available at the moment.</p>
                            <a href="{{ route('shop.index') }}" class="tf-btn btn-outline animate-hover-btn">
                                <span>Browse All Products</span>
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="nav-sw nav-next-slider nav-next-product box-icon w_46 round"><span class="icon icon-arrow-left"></span></div>
            <div class="nav-sw nav-prev-slider nav-prev-product box-icon w_46 round"><span class="icon icon-arrow-right"></span></div>
        </div>
    </div>
</section>
<!-- /Product -->
@endif

@if(isset($flashSaleProducts) && $flashSaleProducts->count() > 0)
<!-- Flash Sale Products -->
<section class="flat-spacing-1 bg_grey-3">
    <div class="container">
        <div class="flat-title wow fadeInUp" data-wow-delay="0s">
            <span class="title">Flash Sale</span>
            <p class="sub-title">Limited time offers - Grab them before they're gone!</p>
        </div>
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
</section>
<!-- /Flash Sale Products -->
@endif

<!-- Banner Collection -->
<section class="flat-spacing-8">
    <div class="container">
        <div class="tf-grid-layout md-col-2 tf-img-with-text style-4">
            <div class="tf-content-left has-bg-color-2 wow fadeInLeft" data-wow-delay="0s">
                <div class="text-center">
                    <h2 class="heading">The Summer Sale</h2>
                    <p class="text-paragraph">Discover the hottest trends and must-have styles of the season.</p>
                    <div class="tf-countdown-v2 justify-content-center">
                        <div class="js-countdown" data-timer="1007500" data-labels=" :  :  : ">
                            <div class="countdown__item">
                                <span class="countdown__value countdown-days">0</span>
                                <span class="countdown__label">Days</span>
                            </div>
                            <div class="countdown__item">
                                <span class="countdown__value countdown-hours">0</span>
                                <span class="countdown__label">Hours</span>
                            </div>
                            <div class="countdown__item">
                                <span class="countdown__value countdown-minutes">0</span>
                                <span class="countdown__label">Mins</span>
                            </div>
                            <div class="countdown__item">
                                <span class="countdown__value countdown-seconds">0</span>
                                <span class="countdown__label">Secs</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('collections.show', 'summer-sale') }}" class="tf-btn style-3 fw-6 animate-hover-btn btn-xl radius-3">
                        <span>Shop Collection</span><i class="icon icon-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="tf-image-wrap wow fadeInRight" data-wow-delay="0s">
                <img class="lazyload" data-src="{{ asset('assets/ecomus/images/collections/banner-collection-3.png') }}" src="{{ asset('assets/ecomus/images/collections/banner-collection-3.png') }}" alt="banner-collection">
            </div>
        </div>
    </div>
</section>
<!-- /Banner Collection -->

<!-- Testimonial -->
<section class="flat-spacing-2 pt_0">
    <div class="container">
        <div class="flat-title wow fadeInUp" data-wow-delay="0s">
            <span class="title">Happy Clients</span>
            <p class="sub-title">Hear what they say about us</p>
        </div>
        <div class="wrap-carousel">
            <div dir="ltr" class="swiper tf-sw-testimonial" data-preview="3" data-tablet="2" data-mobile="1" data-space-lg="30" data-space-md="15">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="testimonial-item style-column wow fadeInUp" data-wow-delay="0s">
                            <div class="rating">
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                            </div>
                            <div class="heading">Best Online Fashion Site</div>
                            <div class="text">
                                " I always find something stylish and affordable on this website "
                            </div>
                            <div class="author">
                                <div class="name">Robert smith</div>
                                <div class="metas">Customer from New York</div>
                            </div>
                            <div class="product">
                                <div class="image">
                                    <a href="#">
                                        <img class="lazyload" data-src="{{ asset('assets/ecomus/images/products/white-3.jpg') }}" src="{{ asset('assets/ecomus/images/products/white-3.jpg') }}" alt="">
                                    </a>
                                </div>
                                <div class="content-wrap">
                                    <div class="product-title">
                                        <a href="#">Jersey thong body</a>
                                    </div>
                                    <div class="price">$105.95</div>
                                </div>
                                <a href="#" class=""><i class="icon-arrow1-top-left"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-item style-column wow fadeInUp" data-wow-delay=".1s">
                            <div class="rating">
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                            </div>
                            <div class="heading">Great Selection and Quality</div>
                            <div class="text">
                                " I love the variety of styles and the high-quality clothing on this site "
                            </div>
                            <div class="author">
                                <div class="name">Allen Lyn</div>
                                <div class="metas">Customer from Chicago</div>
                            </div>
                            <div class="product">
                                <div class="image">
                                    <a href="#">
                                        <img class="lazyload" data-src="{{ asset('assets/ecomus/images/products/brown.jpg') }}" src="{{ asset('assets/ecomus/images/products/brown.jpg') }}" alt="">
                                    </a>
                                </div>
                                <div class="content-wrap">
                                    <div class="product-title">
                                        <a href="#">Ribbed modal T-shirt</a>
                                    </div>
                                    <div class="price">$18.95</div>
                                </div>
                                <a href="#" class=""><i class="icon-arrow1-top-left"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-item style-column wow fadeInUp" data-wow-delay=".2s">
                            <div class="rating">
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                            </div>
                            <div class="heading">Best Customer Service</div>
                            <div class="text">
                                " I finally found a store I can rely on for trendy and quality clothing "
                            </div>
                            <div class="author">
                                <div class="name">Peter Rope</div>
                                <div class="metas">Customer from San Francisco</div>
                            </div>
                            <div class="product">
                                <div class="image">
                                    <a href="#">
                                        <img class="lazyload" data-src="{{ asset('assets/ecomus/images/products/white-2.jpg') }}" src="{{ asset('assets/ecomus/images/products/white-2.jpg') }}" alt="">
                                    </a>
                                </div>
                                <div class="content-wrap">
                                    <div class="product-title">
                                        <a href="#">Oversized Printed T-shirt</a>
                                    </div>
                                    <div class="price">$16.95</div>
                                </div>
                                <a href="#" class=""><i class="icon-arrow1-top-left"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-item style-column wow fadeInUp" data-wow-delay=".2s">
                            <div class="rating">
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                            </div>
                            <div class="heading">Great Selection and Quality</div>
                            <div class="text">
                                " The customer service team is outstanding and very responsive! "
                            </div>
                            <div class="author">
                                <div class="name">Hellen Ase</div>
                                <div class="metas">Customer from Miami</div>
                            </div>
                            <div class="product">
                                <div class="image">
                                    <a href="#">
                                        <img class="lazyload" data-src="{{ asset('assets/ecomus/images/products/pink-1.jpg') }}" src="{{ asset('assets/ecomus/images/products/pink-1.jpg') }}" alt="">
                                    </a>
                                </div>
                                <div class="content-wrap">
                                    <div class="product-title">
                                        <a href="#">Ribbed Tank Top</a>
                                    </div>
                                    <div class="price">$16.95</div>
                                </div>
                                <a href="#" class=""><i class="icon-arrow1-top-left"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sw-dots style-2 sw-pagination-testimonial justify-content-center"></div>
            </div>
        </div>
    </div>
</section>
<!-- /Testimonial -->

<!-- Instagram -->
<section class="flat-spacing-1 pt_0">
    <div class="container">
        <div class="flat-title wow fadeInUp" data-wow-delay="0s">
            <span class="title">Shop the look</span>
            <p class="sub-title">Inspire and let yourself be inspired, from one unique fashion to another.</p>
        </div>
        <div class="wrap-carousel wrap-shop-the-look">
            <div dir="ltr" class="swiper tf-sw-shop-gallery" data-preview="5" data-tablet="3" data-mobile="2" data-space-lg="7" data-space-md="7">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" lazy="true">
                        <div class="gallery-item hover-img">
                            <div class="img-style">
                                <img class="lazyload img-hover" data-src="{{ asset('assets/ecomus/images/shop/gallery/gallery-7.jpg') }}" src="{{ asset('assets/ecomus/images/shop/gallery/gallery-7.jpg') }}" alt="gallery-img">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="gallery-item hover-img">
                            <div class="img-style">
                                <img class="lazyload img-hover" data-src="{{ asset('assets/ecomus/images/shop/gallery/gallery-3.jpg') }}" src="{{ asset('assets/ecomus/images/shop/gallery/gallery-3.jpg') }}" alt="gallery-img">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="gallery-item hover-img">
                            <div class="img-style">
                                <img class="lazyload img-hover" data-src="{{ asset('assets/ecomus/images/shop/gallery/gallery-5.jpg') }}" src="{{ asset('assets/ecomus/images/shop/gallery/gallery-5.jpg') }}" alt="gallery-img">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="gallery-item hover-img">
                            <div class="img-style">
                                <img class="lazyload img-hover" data-src="{{ asset('assets/ecomus/images/shop/gallery/gallery-8.jpg') }}" src="{{ asset('assets/ecomus/images/shop/gallery/gallery-8.jpg') }}" alt="gallery-img">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="gallery-item hover-img">
                            <div class="img-style">
                                <img class="lazyload img-hover" data-src="{{ asset('assets/ecomus/images/shop/gallery/gallery-6.jpg') }}" src="{{ asset('assets/ecomus/images/shop/gallery/gallery-6.jpg') }}" alt="gallery-img">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="gallery-item hover-img">
                            <div class="img-style">
                                <img class="lazyload img-hover" data-src="{{ asset('assets/ecomus/images/shop/gallery/gallery-2.jpg') }}" src="{{ asset('assets/ecomus/images/shop/gallery/gallery-2.jpg') }}" alt="gallery-img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sw-dots style-2 sw-pagination-gallery justify-content-center"></div>
        </div>
    </div>
</section>
<!-- /Instagram -->

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Document ready, jQuery version:', $.fn.jquery); // Debug log
    console.log('Bootstrap version:', typeof bootstrap !== 'undefined' ? 'loaded' : 'not loaded'); // Debug log
    console.log('Quick view modal exists:', $('#quick_view').length > 0); // Debug log
    
    // Test modal manually after 3 seconds (for debugging)
    setTimeout(function() {
        console.log('Testing modal display...');
        const modal = $('#quick_view');
        if (modal.length > 0) {
            console.log('Modal found, trying to show...');
            // Try both Bootstrap 5 and 4 methods
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                console.log('Using Bootstrap 5 Modal');
                const bsModal = new bootstrap.Modal(modal[0]);
                // Uncomment the next line to test modal display
                // bsModal.show();
            } else if (modal.modal) {
                console.log('Using jQuery/Bootstrap 4 Modal');
                // Uncomment the next line to test modal display
                // modal.modal('show');
            } else {
                console.log('No modal method available');
            }
        } else {
            console.log('Modal not found in DOM');
        }
    }, 3000);
    
    // Initialize cart count
    // Use global functions for count updates
    if (typeof window.updateCartCount === 'function') {
        window.updateCartCount();
    }
    if (typeof window.updateWishlistCount === 'function') {
        window.updateWishlistCount();
    }

    // Handle quick view functionality - simplified for testing
    $(document).on('click', '[data-action="quick-view"]', function(e) {
        console.log('Quick view clicked'); // Debug log
        
        const button = $(this);
        const productId = button.data('product-id');
        const productSlug = button.data('product-slug');
        
        console.log('Product ID:', productId, 'Product Slug:', productSlug); // Debug log
        console.log('Button attributes:', {
            'data-bs-toggle': button.attr('data-bs-toggle'),
            'data-bs-target': button.attr('data-bs-target')
        });
        
        // Store product info in modal
        const modal = $('#quick_view');
        modal.data('product-id', productId).data('product-slug', productSlug || '');
        
        console.log('Modal element:', modal.length > 0 ? 'found' : 'not found');
        console.log('Modal visibility before:', modal.is(':visible'));
        
        // Force show modal for testing (uncomment to test)
        // setTimeout(() => {
        //     console.log('Force showing modal...');
        //     if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        //         const bsModal = new bootstrap.Modal(modal[0]);
        //         bsModal.show();
        //     } else {
        //         modal.modal('show');
        //     }
        // }, 100);
    });

    // Handle modal show event to load product data
    $('#quick_view').on('show.bs.modal', function (e) {
        console.log('Modal show event triggered'); // Debug log
        console.log('Modal element:', this); // Debug log
        console.log('Event:', e); // Debug log
        
        const modal = $(this);
        let productId = modal.data('product-id');
        let productSlug = modal.data('product-slug');
        
        console.log('Modal data - ID:', productId, 'Slug:', productSlug); // Debug log
        
        // If no data on modal, try to get from the trigger button
        if (!productId) {
            const relatedTarget = e.relatedTarget; // Button that triggered the modal
            if (relatedTarget) {
                productId = $(relatedTarget).data('product-id');
                productSlug = $(relatedTarget).data('product-slug');
                console.log('Got from trigger button - ID:', productId, 'Slug:', productSlug); // Debug log
                
                // Store in modal for future reference
                modal.data('product-id', productId).data('product-slug', productSlug || '');
            }
        }
        
        console.log('Final product data - ID:', productId, 'Slug:', productSlug); // Debug log
        
        if (!productId) {
            console.log('No product ID found, showing static modal'); // Debug log
            return;
        }
        
        // Reset modal to loading state
        resetModalToLoading();
        
        // Use product ID as fallback if slug is not URL-friendly
        let apiUrl = `/api/products/${productId}/quick-view`;
        if (productSlug && productSlug.match(/^[a-zA-Z0-9-_]+$/)) {
            // Only use slug if it contains valid URL characters
            apiUrl = `/api/products/${productSlug}/quick-view`;
        }
        
        console.log('API URL:', apiUrl); // Debug log
        
        // Fetch product data via AJAX
        $.ajax({
            url: apiUrl,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                console.log('API Response:', response); // Debug log
                
                if (response.success && response.data) {
                    const product = response.data;
                    console.log('Product data received:', product); // Debug log
                    // Update modal content
                    updateQuickViewModal(product);
                } else {
                    console.error('Invalid response from server');
                    // Show error message
                    $('#quick_view .tf-product-info-title h5 a').text('Product not available');
                }
            },
            error: function(xhr, status, error) {
                console.error('Quick view failed:', error, xhr.responseText);
                // Show error message
                $('#quick_view .tf-product-info-title h5 a').text('Failed to load product details');
            }
        });
    });

    function resetModalToLoading() {
        const modal = $('#quick_view');
        // Update the title (it's inside tf-product-info-list)
        modal.find('.tf-product-info-title h5 a').text('Loading...').attr('href', '#');
        // Update the price
        modal.find('.tf-product-info-price').html('<div class="price">Loading...</div>');
        // Update the description
        modal.find('.tf-product-description p').text('Loading product details...');
        
        // Reset images to default
        modal.find('.swiper-wrapper').html(`
            <div class="swiper-slide">
                <div class="item">
                    <img src="/assets/ecomus/images/products/default-product.jpg" alt="Loading..." class="quick-view-image">
                </div>
            </div>
        `);
    }

    function updateQuickViewModal(product) {
        const modal = $('#quick_view');
        console.log('Updating modal with product:', product); // Debug log
        
        if (!modal.length) {
            console.error('Quick view modal not found!');
            return;
        }
        
        console.log('Modal found, checking elements:');
        console.log('- Title element:', modal.find('.tf-product-info-title h5 a').length);
        console.log('- Price element:', modal.find('.tf-product-info-price').length);
        console.log('- Description element:', modal.find('.tf-product-description p').length);
        console.log('- Swiper wrapper:', modal.find('.swiper-wrapper').length);
        
        // Process product images using comprehensive logic
        const processedImages = processProductImages(product);
        console.log('Processed images:', processedImages); // Debug log
        
        // Update product images with comprehensive handling
        let imagesHtml = '';
        
        if (processedImages.length > 0) {
            processedImages.forEach((imageUrl, index) => {
                imagesHtml += `
                    <div class="swiper-slide">
                        <div class="item">
                            <img src="${imageUrl}" alt="${product.name || 'Product'}" 
                                 onerror="this.src='/assets/ecomus/images/products/default-product.jpg'; this.onerror=null;"
                                 loading="lazy">
                        </div>
                    </div>
                `;
            });
        } else {
            // Final fallback
            imagesHtml = `
                <div class="swiper-slide">
                    <div class="item">
                        <img src="/assets/ecomus/images/products/default-product.jpg" alt="${product.name || 'Product'}">
                    </div>
                </div>
            `;
        }
        
        const swiperWrapper = modal.find('.swiper-wrapper');
        if (swiperWrapper.length) {
            swiperWrapper.html(imagesHtml);
            console.log('Updated swiper wrapper with images'); // Debug log
        } else {
            console.error('Swiper wrapper not found in modal');
        }
        
        // Update product title and link
        const productLink = `/products/${product.slug}`;
        modal.find('.tf-product-info-title h5 a')
            .text(product.name || 'Product')
            .attr('href', productLink);
        
        // Update price with proper formatting
        let priceHtml = '';
        if (product.sale_price && parseFloat(product.sale_price) < parseFloat(product.price)) {
            priceHtml = `
                <div class="price-on-sale fw-6">$${parseFloat(product.sale_price).toFixed(2)}</div>
                <div class="compare-at-price">$${parseFloat(product.price).toFixed(2)}</div>
            `;
        } else {
            priceHtml = `<div class="price fw-6">$${parseFloat(product.price || 0).toFixed(2)}</div>`;
        }
        modal.find('.tf-product-info-price').html(priceHtml);
        
        // Update description
        if (product.short_description) {
            modal.find('.tf-product-description p').text(product.short_description);
        } else if (product.description) {
            // Truncate long description for quick view
            const truncatedDesc = product.description.length > 150 ? 
                product.description.substring(0, 150) + '...' : 
                product.description;
            modal.find('.tf-product-description p').text(truncatedDesc);
        } else {
            modal.find('.tf-product-description p').text('No description available.');
        }
        
        // Update variant picker if product has variants
        updateVariantPickers(modal, product);
        
        // Update buy button with product data
        modal.find('.btn-add-to-cart').data('product-id', product.id);
        const finalPrice = product.sale_price || product.price || 0;
        modal.find('.tf-qty-price').text(`$${parseFloat(finalPrice).toFixed(2)}`);
        
        // Update view full details link
        modal.find('.tf-btn.btn-line').attr('href', productLink);
        
        // Update stock status if available
        if (product.stock_quantity !== undefined) {
            if (product.stock_quantity <= 0) {
                modal.find('.btn-add-to-cart').addClass('disabled').text('Out of Stock');
            } else if (product.stock_quantity <= 5) {
                modal.find('.product-status-content p').text(`Only ${product.stock_quantity} left in stock!`);
            }
        }
        
        // Reinitialize Swiper if needed
        if (typeof Swiper !== 'undefined') {
            setTimeout(() => {
                // Destroy existing swiper if any
                const swiperContainer = modal.find('.tf-single-slide')[0];
                if (swiperContainer && swiperContainer.swiper) {
                    swiperContainer.swiper.destroy(true, true);
                }
                
                // Initialize new swiper
                new Swiper(swiperContainer, {
                    navigation: {
                        nextEl: '.single-slide-prev',
                        prevEl: '.single-slide-next',
                    },
                    loop: processedImages.length > 1,
                    spaceBetween: 0,
                    effect: 'slide'
                });
            }, 100);
        }
    }

    function updateVariantPickers(modal, product) {
        const variantSection = modal.find('.tf-product-info-variant-picker');
        
        // For now, hide variant pickers if no variants
        if (!product.variants || product.variants.length === 0) {
            variantSection.hide();
        } else {
            variantSection.show();
            // TODO: Implement variant picker logic based on your product variant structure
        }
    }

    function processProductImages(product) {
        // Process images using the same logic as home page
        let processedImages = [];
        
        console.log('Processing images for product:', product); // Debug log
        
        // First try images array
        if (product.images && product.images) {
            let images = product.images;
            
            // Handle string JSON
            if (typeof images === 'string') {
                try {
                    images = JSON.parse(images);
                } catch (e) {
                    console.error('Failed to parse images JSON:', e);
                    images = [];
                }
            }
            
            console.log('Parsed images:', images); // Debug log
            
            if (Array.isArray(images) && images.length > 0) {
                images.forEach((image, index) => {
                    let legacyImageUrl = '';
                    
                    console.log(`Processing image ${index}:`, image); // Debug log
                    
                    // Handle complex nested structure first - exact same logic as home page
                    if (image && typeof image === 'object' && image.sizes && image.sizes.medium && image.sizes.medium.storage_url) {
                        // New complex structure - use medium size storage_url
                        legacyImageUrl = image.sizes.medium.storage_url;
                    } else if (image && typeof image === 'object' && image.sizes && image.sizes.original && image.sizes.original.storage_url) {
                        // Fallback to original if medium not available
                        legacyImageUrl = image.sizes.original.storage_url;
                    } else if (image && typeof image === 'object' && image.sizes && image.sizes.large && image.sizes.large.storage_url) {
                        // Fallback to large if original not available
                        legacyImageUrl = image.sizes.large.storage_url;
                    } else if (image && typeof image === 'object' && image.urls && image.urls.medium) {
                        // Legacy complex URL structure - use medium size
                        legacyImageUrl = image.urls.medium;
                    } else if (image && typeof image === 'object' && image.urls && image.urls.original) {
                        // Legacy fallback to original if medium not available
                        legacyImageUrl = image.urls.original;
                    } else if (image && typeof image === 'object' && image.url && typeof image.url === 'string') {
                        legacyImageUrl = image.url;
                    } else if (image && typeof image === 'object' && image.path && typeof image.path === 'string') {
                        legacyImageUrl = `/storage/${image.path}`;
                    } else if (typeof image === 'string') {
                        // Simple string path
                        if (image.startsWith('http')) {
                            legacyImageUrl = image;
                        } else {
                            legacyImageUrl = `/storage/${image}`;
                        }
                    }
                    
                    console.log(`Processed image URL:`, legacyImageUrl); // Debug log
                    
                    if (legacyImageUrl) {
                        processedImages.push(legacyImageUrl);
                    }
                });
            }
        }
        
        // Fallback to image accessor if no images processed
        if (processedImages.length === 0) {
            console.log('No images from array, trying product.image:', product.image); // Debug log
            
            const productImage = product.image;
            if (productImage && productImage !== 'products/product1.jpg') {
                const legacyImageUrl = productImage.startsWith('http') ? productImage : `/storage/${productImage}`;
                processedImages.push(legacyImageUrl);
            } else {
                // Final fallback to default image
                processedImages.push('/assets/ecomus/images/products/default-product.jpg');
            }
        }
        
        console.log('Final processed images:', processedImages); // Debug log
        return processedImages;
    }

    function getImageUrl(image) {
        // Handle different image formats using the same logic as home page
        let legacyImageUrl = '';
        
        if (typeof image === 'string') {
            // Simple string path
            legacyImageUrl = image.startsWith('http') ? image : `/storage/${image}`;
        } else if (image && typeof image === 'object') {
            // Handle complex nested structure first - exact same logic as home page
            if (image.sizes && image.sizes.medium && image.sizes.medium.storage_url) {
                // New complex structure - use medium size storage_url
                legacyImageUrl = image.sizes.medium.storage_url;
            } else if (image.sizes && image.sizes.original && image.sizes.original.storage_url) {
                // Fallback to original if medium not available
                legacyImageUrl = image.sizes.original.storage_url;
            } else if (image.sizes && image.sizes.large && image.sizes.large.storage_url) {
                // Fallback to large if original not available
                legacyImageUrl = image.sizes.large.storage_url;
            } else if (image.urls && image.urls.medium) {
                // Legacy complex URL structure - use medium size
                legacyImageUrl = image.urls.medium;
            } else if (image.urls && image.urls.original) {
                // Legacy fallback to original if medium not available
                legacyImageUrl = image.urls.original;
            } else if (image.url && typeof image.url === 'string') {
                legacyImageUrl = image.url;
            } else if (image.path && typeof image.path === 'string') {
                legacyImageUrl = `/storage/${image.path}`;
            }
        }
        
        // Final fallback to default image if nothing found
        if (!legacyImageUrl) {
            legacyImageUrl = '/assets/ecomus/images/products/default-product.jpg';
        }
        
        return legacyImageUrl;
    }


});
</script>
@endpush
