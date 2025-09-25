@extends('layouts.ecomus')

@section('title', $product->name . ' - ' . config('app.name'))
@section('description', Str::limit($product->description ?? $product->short_description ?? 'Product details', 160))

@push('styles')
<style>
/* Enhanced Product Detail Page Styles */
.tf-product-detail {
    padding: 60px 0;
}

.product-media-wrap {
    position: sticky;
    top: 100px;
}

.product-media-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 80px;
    margin-right: 20px;
}

.product-media-item {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.product-media-item.active,
.product-media-item:hover {
    border-color: var(--primary-color);
}

.product-media-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-media-main {
    flex: 1;
    max-width: calc(100% - 100px);
}

.product-media-main img {
    width: 100%;
    height: auto;
    border-radius: 12px;
}

.product-infor {
    padding-left: 40px;
}

.product-price {
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 20px 0;
}

.price-on-sale {
    font-size: 32px;
    font-weight: 700;
    color: var(--primary-color);
}

.compare-at-price {
    font-size: 24px;
    text-decoration: line-through;
    color: #666;
}

.variant-picker {
    margin: 30px 0;
}

.variant-picker-label {
    font-weight: 600;
    margin-bottom: 10px;
    display: block;
}

.variant-picker-values {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.variant-picker-item {
    padding: 8px 16px;
    border: 2px solid #e5e5e5;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.variant-picker-item:hover,
.variant-picker-item.active {
    border-color: var(--primary-color);
    background: var(--primary-color);
    color: white;
}

/* Color Swatches */
.tf-product-info-variant-picker .variant-picker-item.color-swatch {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    padding: 0;
    position: relative;
    border: 3px solid #e5e5e5;
}

.tf-product-info-variant-picker .variant-picker-item.color-swatch.active {
    border-color: var(--primary-color);
    transform: scale(1.1);
}

.tf-product-info-variant-picker .variant-picker-item.color-swatch::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: inherit;
}

.product-form {
    margin: 30px 0;
}

.product-form-buttons {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.quantity-selector {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    width: fit-content;
}

.quantity-selector button {
    border: none;
    background: #f8f9fa;
    width: 40px;
    height: 40px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.quantity-selector button:hover {
    background: var(--primary-color);
    color: white;
}

.quantity-selector input {
    border: none;
    text-align: center;
    width: 60px;
    height: 40px;
    outline: none;
}

/* Sticky Add to Cart */
.tf-sticky-atc {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    border-top: 1px solid #e5e5e5;
    padding: 15px 20px;
    z-index: 1000;
    transform: translateY(100%);
    transition: transform 0.3s ease;
    box-shadow: 0 -2px 20px rgba(0,0,0,0.1);
}

.tf-sticky-atc.show {
    transform: translateY(0);
}

.tf-sticky-atc-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
}

.tf-sticky-atc-product {
    display: flex;
    align-items: center;
    gap: 15px;
}

.tf-sticky-atc-product img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}

.tf-sticky-atc-actions {
    display: flex;
    align-items: center;
    gap: 15px;
}

.product-tabs {
    margin-top: 80px;
}

.tab-nav {
    display: flex;
    border-bottom: 1px solid #e5e5e5;
    margin-bottom: 40px;
}

.tab-nav-item {
    padding: 15px 20px;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;
}

.tab-nav-item.active {
    border-bottom-color: var(--primary-color);
    color: var(--primary-color);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.related-products {
    margin-top: 80px;
}

/* Zoom Effect */
.tf-image-zoom {
    cursor: zoom-in;
    transition: transform 0.3s ease;
}

.tf-image-zoom:hover {
    transform: scale(1.05);
}

/* Product Info Badges */
.tf-product-info-badges {
    display: flex;
    gap: 8px;
    margin-bottom: 15px;
}

.badges {
    background: var(--primary-color);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.badges.sale {
    background: #e74c3c;
}

.badges-on-sale {
    background: #27ae60;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

/* Product Trust Elements */
.tf-product-info-trust-seal {
    margin-top: 30px;
    padding-top: 30px;
    border-top: 1px solid #e5e5e5;
}

.tf-product-trust-mess {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
}

.tf-payment {
    display: flex;
    gap: 10px;
    align-items: center;
}

.tf-payment img {
    height: 30px;
}

/* Product Delivery Info */
.tf-product-info-delivery-return {
    margin-top: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.tf-product-delivery {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 15px;
}

.tf-product-delivery:last-child {
    margin-bottom: 0;
}

.tf-product-delivery .icon {
    color: var(--primary-color);
    font-size: 20px;
}

@media (max-width: 768px) {
    .product-infor {
        padding-left: 0;
        margin-top: 30px;
    }
    
    .product-media-list {
        flex-direction: row;
        width: 100%;
        margin-right: 0;
        margin-bottom: 15px;
    }
    
    .product-media-main {
        max-width: 100%;
    }
    
    .product-form-buttons {
        flex-direction: column;
    }
    
    .tf-sticky-atc-content {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }
    
    .tf-sticky-atc-actions {
        justify-content: center;
    }
}
</style>
</style>
@endpush

@section('content')
@php
// Enhanced image handling function for product details
function getDetailProductImageSrc($product, $imageIndex = 0, $size = 'large') {
    $legacyImageUrl = '';
    
    // First try images array
    if (isset($product->images) && $product->images) {
        $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
        if (is_array($images) && isset($images[$imageIndex])) {
            $image = $images[$imageIndex];
            
            // Handle complex nested structure first
            if (is_array($image) && isset($image['sizes'][$size]['storage_url'])) {
                $legacyImageUrl = $image['sizes'][$size]['storage_url'];
            } elseif (is_array($image) && isset($image['sizes']['original']['storage_url'])) {
                $legacyImageUrl = $image['sizes']['original']['storage_url'];
            } elseif (is_array($image) && isset($image['urls'][$size])) {
                $legacyImageUrl = $image['urls'][$size];
            } elseif (is_array($image) && isset($image['urls']['original'])) {
                $legacyImageUrl = $image['urls']['original'];
            } elseif (is_array($image) && isset($image['url'])) {
                $legacyImageUrl = $image['url'];
            } elseif (is_array($image) && isset($image['path'])) {
                $legacyImageUrl = asset('storage/' . $image['path']);
            } elseif (is_string($image)) {
                $legacyImageUrl = asset('storage/' . $image);
            }
        }
    }
    
    // Fallback to main image
    if (empty($legacyImageUrl) && $imageIndex === 0) {
        $productImage = $product->image;
        if ($productImage) {
            $legacyImageUrl = str_starts_with($productImage, 'http') ? $productImage : asset('storage/' . $productImage);
        }
    }
    
    // Final fallback
    if (empty($legacyImageUrl)) {
        $legacyImageUrl = asset('assets/ecomus/images/products/default-product.jpg');
    }
    
    return $legacyImageUrl;
}

// Get all product images for gallery
function getProductGalleryImages($product) {
    $galleryImages = [];
    
    if (isset($product->images) && $product->images) {
        $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
        if (is_array($images)) {
            foreach ($images as $index => $image) {
                $galleryImages[] = getDetailProductImageSrc($product, $index, 'large');
            }
        }
    }
    
    // If no gallery images, use main image
    if (empty($galleryImages)) {
        $galleryImages[] = getDetailProductImageSrc($product, 0, 'large');
    }
    
    return $galleryImages;
}

$galleryImages = getProductGalleryImages($product);
@endphp

<!-- Breadcrumb -->
<div class="tf-breadcrumb">
    <div class="container">
        <div class="tf-breadcrumb-wrap d-flex justify-content-between flex-wrap align-items-center">
            <div class="tf-breadcrumb-list">
                <a href="{{ route('home') }}" class="text">Home</a>
                <i class="icon icon-arrow-right"></i>
                <a href="{{ route('shop.index') }}" class="text">Shop</a>
                <i class="icon icon-arrow-right"></i>
                @if($product->category)
                <a href="{{ route('categories.show', $product->category->slug) }}" class="text">{{ $product->category->name }}</a>
                <i class="icon icon-arrow-right"></i>
                @endif
                <span class="text text-primary">{{ $product->name }}</span>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Product Detail -->
<section class="flat-spacing-4 pt_0">
    <div class="tf-main-product section-image-zoom">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="tf-product-media-wrap sticky-top">
                        <div class="thumbs-slider">
                            <div dir="ltr" class="swiper tf-product-media-thumbs other-image-zoom" data-direction="vertical">
                                <div class="swiper-wrapper stagger-wrap">
                                    @foreach($galleryImages as $index => $imageUrl)
                                    <div class="swiper-slide stagger-item" lazy="true">
                                        <div class="item">
                                            <img class="radius-3 lazyload" data-src="{{ $imageUrl }}" src="{{ $imageUrl }}" alt="{{ $product->name }} - Image {{ $index + 1 }}" 
                                                 onerror="this.src='{{ asset('assets/ecomus/images/products/default-product.jpg') }}'">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div dir="ltr" class="swiper tf-product-media-main" id="gallery-swiper-started">
                                <div class="swiper-wrapper">
                                    @foreach($galleryImages as $index => $imageUrl)
                                    <div class="swiper-slide" lazy="true">
                                        <a href="{{ $imageUrl }}" target="_blank" class="item" data-pswp-width="770" data-pswp-height="1075">
                                            <img class="tf-image-zoom lazyload" data-zoom="{{ $imageUrl }}" data-src="{{ $imageUrl }}" src="{{ $imageUrl }}" alt="{{ $product->name }} - Image {{ $index + 1 }}" 
                                                 onerror="this.src='{{ asset('assets/ecomus/images/products/default-product.jpg') }}'">
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="swiper-button-next button-style-arrow thumbs-next"></div>
                                <div class="swiper-button-prev button-style-arrow thumbs-prev"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="tf-product-info-wrap position-relative">
                        <div class="tf-zoom-main"></div>
                        <div class="tf-product-info-list other-image-zoom">
                            <div class="tf-product-info-title">
                                <h3>{{ $product->name }}</h3>
                            </div>
                            <div class="tf-product-info-badges">
                                @if($product->featured)
                                <div class="badges text-uppercase">Featured</div>
                                @endif
                                @if($product->sale_price && $product->sale_price < $product->price)
                                <div class="badges">Sale</div>
                                @endif
                            </div>
                            <div class="tf-product-info-price">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                <div class="price-on-sale">${{ number_format($product->sale_price, 2) }}</div>
                                <div class="compare-at-price">${{ number_format($product->price, 2) }}</div>
                                <div class="badges-on-sale">
                                    <span>{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF</span>
                                </div>
                                @else
                                <div class="price-current">${{ number_format($product->price, 2) }}</div>
                                @endif
                            </div>
                            <div class="tf-product-info-variant-picker">
                                @if($product->variants && count($product->variants) > 0)
                                    @php
                                        $variantTypes = collect($product->variants)->groupBy('type');
                                    @endphp
                                    
                                    @foreach($variantTypes as $type => $variants)
                                    <div class="variant-picker-item">
                                        <div class="variant-picker-label">
                                            Choose {{ ucfirst($type) }}: <span class="selected-variant-{{ $type }}">{{ $variants->first()->value }}</span>
                                        </div>
                                        <div class="variant-picker-values">
                                            @foreach($variants as $index => $variant)
                                                @if(strtolower($type) === 'color')
                                                    <input type="radio" class="btn-check" name="{{ $type }}" id="{{ $type }}_{{ $variant->id }}" value="{{ $variant->id }}" {{ $index === 0 ? 'checked' : '' }}>
                                                    <label class="btn variant-picker-item color-swatch" for="{{ $type }}_{{ $variant->id }}" 
                                                           style="background-color: {{ $variant->color_code ?? $variant->value }};" 
                                                           title="{{ $variant->value }}">
                                                    </label>
                                                @else
                                                    <input type="radio" class="btn-check" name="{{ $type }}" id="{{ $type }}_{{ $variant->id }}" value="{{ $variant->id }}" {{ $index === 0 ? 'checked' : '' }}>
                                                    <label class="btn style-text variant-picker-item" for="{{ $type }}_{{ $variant->id }}">{{ $variant->value }}</label>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <!-- Default variant options if no variants exist -->
                                    <div class="variant-picker-item">
                                        <div class="variant-picker-label">
                                            Choose Size: <span class="selected-variant-size">M</span>
                                        </div>
                                        <div class="variant-picker-values">
                                            <input type="radio" class="btn-check" name="size" id="size_s" value="S">
                                            <label class="btn style-text variant-picker-item" for="size_s">S</label>
                                            <input type="radio" class="btn-check" name="size" id="size_m" value="M" checked>
                                            <label class="btn style-text variant-picker-item" for="size_m">M</label>
                                            <input type="radio" class="btn-check" name="size" id="size_l" value="L">
                                            <label class="btn style-text variant-picker-item" for="size_l">L</label>
                                            <input type="radio" class="btn-check" name="size" id="size_xl" value="XL">
                                            <label class="btn style-text variant-picker-item" for="size_xl">XL</label>
                                        </div>
                                    </div>
                                    <div class="variant-picker-item">
                                        <div class="variant-picker-label">
                                            Choose Color: <span class="selected-variant-color">Black</span>
                                        </div>
                                        <div class="variant-picker-values">
                                            <input type="radio" class="btn-check" name="color" id="color_black" value="Black" checked>
                                            <label class="btn variant-picker-item color-swatch" for="color_black" 
                                                   style="background-color: #000000;" title="Black"></label>
                                            <input type="radio" class="btn-check" name="color" id="color_white" value="White">
                                            <label class="btn variant-picker-item color-swatch" for="color_white" 
                                                   style="background-color: #ffffff;" title="White"></label>
                                            <input type="radio" class="btn-check" name="color" id="color_blue" value="Blue">
                                            <label class="btn variant-picker-item color-swatch" for="color_blue" 
                                                   style="background-color: #007bff;" title="Blue"></label>
                                            <input type="radio" class="btn-check" name="color" id="color_red" value="Red">
                                            <label class="btn variant-picker-item color-swatch" for="color_red" 
                                                   style="background-color: #dc3545;" title="Red"></label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="tf-product-info-quantity">
                                <div class="quantity-title fw-6">Quantity</div>
                                <div class="wg-quantity">
                                    <span class="btn-quantity minus-btn">-</span>
                                    <input type="text" name="number" value="1" id="quantity-input">
                                    <span class="btn-quantity plus-btn">+</span>
                                </div>
                            </div>
                            <div class="tf-product-info-buy-button">
                                <div class="tf-product-form" data-product-id="{{ $product->id }}">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1" id="form-quantity">
                                    <div class="tf-product-info-buy-button-wrap">
                                        <button type="button" class="tf-btn btn-fill justify-content-center fw-6 fs-16 flex-grow-1 animate-hover-btn add-to-cart" data-action="add-to-cart" data-product-id="{{ $product->id }}">
                                            <span>Add to cart -&nbsp;</span>
                                            <span class="tf-qty-price">{{ formatCurrency($product->sale_price ?: $product->price) }}</span>
                                        </button>
                                        <div class="tf-product-btn-wishlist btn-icon-action" data-action="add-to-wishlist" data-product-id="{{ $product->id }}">
                                            <i class="icon-heart"></i>
                                            <i class="icon-delete"></i>
                                        </div>
                                        <div class="tf-product-btn-compare btn-icon-action">
                                            <i class="icon-compare"></i>
                                            <i class="icon-check"></i>
                                        </div>
                                        <div class="tf-product-btn-quickview btn-icon-action">
                                            <i class="icon-view"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tf-product-info-extra-link">
                                <a href="#compare_color" data-bs-toggle="modal" class="tf-product-extra-icon">
                                    <div class="icon">
                                        <i class="icon-compare"></i>
                                    </div>
                                    <div class="text fw-6">Compare color</div>
                                </a>
                                <a href="#ask_question" data-bs-toggle="modal" class="tf-product-extra-icon">
                                    <div class="icon">
                                        <i class="icon-question"></i>
                                    </div>
                                    <div class="text fw-6">Ask a question</div>
                                </a>
                                <a href="#delivery_return" data-bs-toggle="modal" class="tf-product-extra-icon">
                                    <div class="icon">
                                        <i class="icon-delivery2"></i>
                                    </div>
                                    <div class="text fw-6">Delivery & Return</div>
                                </a>
                                <a href="#share_social" data-bs-toggle="modal" class="tf-product-extra-icon">
                                    <div class="icon">
                                        <i class="icon-share"></i>
                                    </div>
                                    <div class="text fw-6">Share</div>
                                </a>
                            </div>
                            <div class="tf-product-info-delivery-return">
                                <div class="row">
                                    <div class="col-xl-6 col-12">
                                        <div class="tf-product-delivery">
                                            <div class="icon">
                                                <i class="icon-delivery"></i>
                                            </div>
                                            <p>Estimate delivery times: <span class="fw-7">12-26 days</span> (International), <span class="fw-7">3-6 days</span> (United States).</p>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-12">
                                        <div class="tf-product-delivery">
                                            <div class="icon">
                                                <i class="icon-return"></i>
                                            </div>
                                            <p>Return within <span class="fw-7">30 days</span> of purchase. Duties & taxes are non-refundable.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($product->category)
                            <div class="tf-product-info-trust-seal">
                                <div class="tf-product-trust-mess">
                                    <i class="icon-safe"></i>
                                    <p class="fw-6">Guarantee Safe <br> Checkout</p>
                                </div>
                                <div class="tf-payment">
                                    <img src="{{ asset('assets/ecomus/images/payments/visa.png') }}" alt="Visa">
                                    <img src="{{ asset('assets/ecomus/images/payments/img-1.png') }}" alt="Mastercard">
                                    <img src="{{ asset('assets/ecomus/images/payments/img-2.png') }}" alt="PayPal">
                                    <img src="{{ asset('assets/ecomus/images/payments/img-3.png') }}" alt="American Express">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /Product Detail -->

<!-- Product Tabs -->
<section class="flat-spacing-17 pt_0">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="widget-tabs style-has-border">
                    <ul class="widget-menu-tab">
                        <li class="item-title active">
                            <span class="inner">Description</span>
                        </li>
                        @if($product->specifications)
                        <li class="item-title">
                            <span class="inner">Additional Information</span>
                        </li>
                        @endif
                        <li class="item-title">
                            <span class="inner">Size Guide</span>
                        </li>
                        <li class="item-title">
                            <span class="inner">Reviews</span>
                        </li>
                    </ul>
                    <div class="widget-content-tab">
                        <div class="widget-content-inner active">
                            <div class="">
                                <p>{{ $product->description ?: 'This product offers exceptional quality and style. Crafted with attention to detail and premium materials.' }}</p>
                                @if($product->short_description)
                                <p>{{ $product->short_description }}</p>
                                @endif
                                <div class="tf-product-des-demo">
                                    <div class="right">
                                        <h3 class="fs-16 fw-5">Features</h3>
                                        <ul>
                                            <li>Premium Quality Materials</li>
                                            <li>Comfortable Fit</li>
                                            <li>Durable Construction</li>
                                            <li>Modern Design</li>
                                            <li>Easy Care Instructions</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($product->specifications)
                        <div class="widget-content-inner">
                            <table class="tf-pr-attrs">
                                <tbody>
                                    @php
                                        $specs = is_string($product->specifications) ? json_decode($product->specifications, true) : $product->specifications;
                                    @endphp
                                    @if(is_array($specs))
                                        @foreach($specs as $key => $value)
                                        <tr class="tf-attr-pa-color">
                                            <td class="tf-attr-label">{{ ucfirst($key) }}</td>
                                            <td class="tf-attr-value">{{ $value }}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @endif
                        <div class="widget-content-inner">
                            <table class="tf-sizeguide-table">
                                <thead>
                                    <tr>
                                        <th>Size</th>
                                        <th>US</th>
                                        <th>Bust</th>
                                        <th>Waist</th>
                                        <th>Low Hip</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>XS</td>
                                        <td>2</td>
                                        <td>32</td>
                                        <td>24 - 25</td>
                                        <td>33 - 34</td>
                                    </tr>
                                    <tr>
                                        <td>S</td>
                                        <td>4</td>
                                        <td>34 - 35</td>
                                        <td>26 - 27</td>
                                        <td>35 - 26</td>
                                    </tr>
                                    <tr>
                                        <td>M</td>
                                        <td>6</td>
                                        <td>36 - 37</td>
                                        <td>28 - 29</td>
                                        <td>38 - 40</td>
                                    </tr>
                                    <tr>
                                        <td>L</td>
                                        <td>8</td>
                                        <td>38 - 29</td>
                                        <td>30 - 31</td>
                                        <td>42 - 44</td>
                                    </tr>
                                    <tr>
                                        <td>XL</td>
                                        <td>10</td>
                                        <td>40 - 41</td>
                                        <td>32 - 33</td>
                                        <td>45 - 47</td>
                                    </tr>
                                    <tr>
                                        <td>XXL</td>
                                        <td>12</td>
                                        <td>42 - 43</td>
                                        <td>34 - 35</td>
                                        <td>48 - 50</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="widget-content-inner">
                            <div class="tf-page-privacy-policy">
                                <div class="title">Customer Reviews</div>
                                <p>Be the first to review "{{ $product->name }}"</p>
                                <div class="tf-product-review-wrap">
                                    <div class="tf-product-review-inner">
                                        <div class="tf-product-review-head">
                                            <div class="tf-product-review-rating">
                                                <i class="icon-start"></i>
                                                <i class="icon-start"></i>
                                                <i class="icon-start"></i>
                                                <i class="icon-start"></i>
                                                <i class="icon-start"></i>
                                                <span>(0 Reviews)</span>
                                            </div>
                                        </div>
                                        <form class="tf-product-review-form">
                                            <h5>Add a review</h5>
                                            <div class="tf-field style-1">
                                                <input class="tf-field-input tf-input" placeholder="Name" type="text" id="property1" name="text">
                                                <label class="tf-field-label fw-4 text_black-2" for="property1">Name</label>
                                            </div>
                                            <div class="tf-field style-1">
                                                <input class="tf-field-input tf-input" placeholder="Email" type="email" id="property2" name="email">
                                                <label class="tf-field-label fw-4 text_black-2" for="property2">Email</label>
                                            </div>
                                            <div class="tf-field style-1">
                                                <textarea class="tf-field-input tf-input" placeholder="Review" name="message" rows="4" id="property3"></textarea>
                                                <label class="tf-field-label fw-4 text_black-2" for="property3">Review</label>
                                            </div>
                                            <div class="tf-field style-1">
                                                <div class="tf-product-review-star-rating">
                                                    <div class="tf-product-review-star-rating-title">Your rating *</div>
                                                    <div class="tf-product-review-star-rating-list">
                                                        <i class="icon-start"></i>
                                                        <i class="icon-start"></i>
                                                        <i class="icon-start"></i>
                                                        <i class="icon-start"></i>
                                                        <i class="icon-start"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tf-field style-1">
                                                <button class="tf-btn btn-fill animate-hover-btn radius-3 w-100 justify-content-center">
                                                    <span>Submit Review</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /Product Tabs -->

@if(isset($relatedProducts) && $relatedProducts->count() > 0)
<!-- Related Products -->
<section class="flat-spacing-1 pt_0">
    <div class="container">
        <div class="flat-title">
            <span class="title">Related Products</span>
        </div>
        <div class="hover-sw-nav hover-sw-2">
            <div dir="ltr" class="swiper tf-sw-product-sell wrap-sw-over" data-preview="4" data-tablet="3" data-mobile="2" data-space-lg="30" data-space-md="15">
                <div class="swiper-wrapper">
                    @foreach($relatedProducts as $relatedProduct)
                    <div class="swiper-slide" lazy="true">
                        <div class="card-product">
                            <div class="card-product-wrapper">
                                <a href="{{ route('products.show', $relatedProduct->slug) }}" class="product-img">
                                    <img class="lazyload img-product" 
                                         data-src="{{ getDetailProductImageSrc($relatedProduct, 0, 'medium') }}" 
                                         src="{{ getDetailProductImageSrc($relatedProduct, 0, 'medium') }}" 
                                         alt="{{ $relatedProduct->name }}"
                                         onerror="this.src='{{ asset('assets/ecomus/images/products/default-product.jpg') }}'">
                                    <img class="lazyload img-hover" 
                                         data-src="{{ getDetailProductImageSrc($relatedProduct, 1, 'medium') }}" 
                                         src="{{ getDetailProductImageSrc($relatedProduct, 1, 'medium') }}" 
                                         alt="{{ $relatedProduct->name }}"
                                         onerror="this.src='{{ asset('assets/ecomus/images/products/default-product.jpg') }}'">
                                </a>
                                <div class="list-product-btn absolute-2">
                                    <a href="#quick_add" data-bs-toggle="modal" class="box-icon bg_white quick-add tf-btn-loading">
                                        <span class="icon icon-bag"></span>
                                        <span class="tooltip">Quick Add</span>
                                    </a>
                                    <a href="javascript:void(0);" class="box-icon bg_white wishlist btn-icon-action">
                                        <span class="icon icon-heart"></span>
                                        <span class="tooltip">Add to Wishlist</span>
                                        <span class="icon icon-delete"></span>
                                    </a>
                                    <a href="#compare" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft" class="box-icon bg_white compare btn-icon-action">
                                        <span class="icon icon-compare"></span>
                                        <span class="tooltip">Add to Compare</span>
                                        <span class="icon icon-check"></span>
                                    </a>
                                    <a href="#quick_view" data-bs-toggle="modal" class="box-icon bg_white quickview tf-btn-loading">
                                        <span class="icon icon-view"></span>
                                        <span class="tooltip">Quick View</span>
                                    </a>
                                </div>
                                @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                <div class="on-sale-wrap text-end">
                                    <div class="on-sale-item">{{ round((($relatedProduct->price - $relatedProduct->sale_price) / $relatedProduct->price) * 100) }}%</div>
                                </div>
                                @endif
                            </div>
                            <div class="card-product-info">
                                <a href="{{ route('products.show', $relatedProduct->slug) }}" class="title link">{{ $relatedProduct->name }}</a>
                                <span class="price">
                                    @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                        <span class="compare-at-price">${{ number_format($relatedProduct->price, 2) }}</span>
                                        <span class="price-on-sale fw-6">${{ number_format($relatedProduct->sale_price, 2) }}</span>
                                    @else
                                        <span class="fw-6">${{ number_format($relatedProduct->price, 2) }}</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="nav-sw nav-next-slider nav-next-product box-icon w_46 round"><span class="icon icon-arrow-left"></span></div>
            <div class="nav-sw nav-prev-slider nav-prev-product box-icon w_46 round"><span class="icon icon-arrow-right"></span></div>
        </div>
    </div>
</section>
<!-- /Related Products -->
@endif

<!-- Sticky Add to Cart -->
<div class="tf-sticky-atc" id="sticky-atc">
    <div class="tf-sticky-atc-content">
        <div class="tf-sticky-atc-product">
            <img src="{{ getDetailProductImageSrc($product, 0, 'medium') }}" alt="{{ $product->name }}" 
                 onerror="this.src='{{ asset('assets/ecomus/images/products/default-product.jpg') }}'">
            <div>
                <h6 class="mb-1">{{ $product->name }}</h6>
                <span class="price">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="price-on-sale fw-6">${{ number_format($product->sale_price, 2) }}</span>
                        <span class="compare-at-price">${{ number_format($product->price, 2) }}</span>
                    @else
                        <span class="fw-6">${{ number_format($product->price, 2) }}</span>
                    @endif
                </span>
            </div>
        </div>
        <div class="tf-sticky-atc-actions">
            <div class="wg-quantity">
                <span class="btn-quantity minus-btn-sticky">-</span>
                <input type="text" name="number" value="1" id="sticky-quantity-input">
                <span class="btn-quantity plus-btn-sticky">+</span>
            </div>
            <button type="button" class="tf-btn btn-fill animate-hover-btn" id="sticky-add-to-cart">
                <span>Add to cart</span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Quantity controls for main form
    $('.minus-btn').on('click', function() {
        let input = $('#quantity-input');
        let currentVal = parseInt(input.val());
        if (currentVal > 1) {
            input.val(currentVal - 1);
            $('#form-quantity').val(currentVal - 1);
            $('#sticky-quantity-input').val(currentVal - 1);
            updatePrice();
        }
    });
    
    $('.plus-btn').on('click', function() {
        let input = $('#quantity-input');
        let currentVal = parseInt(input.val());
        input.val(currentVal + 1);
        $('#form-quantity').val(currentVal + 1);
        $('#sticky-quantity-input').val(currentVal + 1);
        updatePrice();
    });
    
    // Quantity controls for sticky bar
    $('.minus-btn-sticky').on('click', function() {
        let input = $('#sticky-quantity-input');
        let currentVal = parseInt(input.val());
        if (currentVal > 1) {
            input.val(currentVal - 1);
            $('#quantity-input').val(currentVal - 1);
            $('#form-quantity').val(currentVal - 1);
            updatePrice();
        }
    });
    
    $('.plus-btn-sticky').on('click', function() {
        let input = $('#sticky-quantity-input');
        let currentVal = parseInt(input.val());
        input.val(currentVal + 1);
        $('#quantity-input').val(currentVal + 1);
        $('#form-quantity').val(currentVal + 1);
        updatePrice();
    });
    
    $('#quantity-input, #sticky-quantity-input').on('change', function() {
        let val = parseInt($(this).val());
        if (val < 1) val = 1;
        $(this).val(val);
        $('#form-quantity').val(val);
        
        // Sync both quantity inputs
        if ($(this).attr('id') === 'quantity-input') {
            $('#sticky-quantity-input').val(val);
        } else {
            $('#quantity-input').val(val);
        }
        updatePrice();
    });
    
    function updatePrice() {
        let quantity = parseInt($('#quantity-input').val());
        let basePrice = {{ $product->sale_price ?: $product->price }};
        let totalPrice = (quantity * basePrice).toFixed(2);
        $('.tf-qty-price').text('$' + totalPrice);
    }
    
    // Sticky Add to Cart functionality
    function toggleStickyCart() {
        let addToCartSection = $('.tf-product-info-buy-button');
        let stickyCart = $('#sticky-atc');
        
        if (addToCartSection.length && stickyCart.length) {
            let addToCartTop = addToCartSection.offset().top;
            let addToCartBottom = addToCartTop + addToCartSection.outerHeight();
            let scrollTop = $(window).scrollTop();
            let windowHeight = $(window).height();
            let bottomViewport = scrollTop + windowHeight;
            
            // Show sticky cart when original add to cart button is out of view
            if (bottomViewport < addToCartBottom - 100) {
                stickyCart.addClass('show');
            } else {
                stickyCart.removeClass('show');
            }
        }
    }
    
    // Show/hide sticky cart on scroll
    $(window).on('scroll', toggleStickyCart);
    toggleStickyCart(); // Check on page load
    
    // Variant selection
    $('.variant-picker-values input[type="radio"]').on('change', function() {
        let $label = $(this).next('label');
        let $container = $(this).closest('.variant-picker-item');
        
        // Remove active class from all variants in this group
        $container.find('.variant-picker-item label').removeClass('active');
        
        // Add active class to selected variant
        $label.addClass('active');
        
        // Update selected variant info
        updateSelectedVariants();
    });
    
    function updateSelectedVariants() {
        let selectedVariants = {};
        $('.variant-picker-values input[type="radio"]:checked').each(function() {
            let name = $(this).attr('name');
            let value = $(this).next('label').text();
            selectedVariants[name] = value;
        });
        
        console.log('Selected variants:', selectedVariants);
        
        // You can update price based on variants here
        // updatePriceForVariants(selectedVariants);
    }
    
    // Add to cart functionality
    $('.tf-product-form, #sticky-add-to-cart').on('submit click', function(e) {
        e.preventDefault();
        
        // Get selected variants
        let variants = {};
        $('input[type="radio"]:checked').each(function() {
            let name = $(this).attr('name');
            let value = $(this).val();
            variants[name] = value;
        });
        
        // Prepare form data
        let formData = {
            product_id: $('input[name="product_id"]').val(),
            quantity: $('input[name="quantity"]').val(),
            variants: variants,
            _token: $('input[name="_token"]').val()
        };
        
        // Show loading state
        let $button = $(this).is('form') ? $(this).find('button') : $(this);
        let originalText = $button.html();
        $button.html('<span>Adding...</span>').prop('disabled', true);
        
        // Ajax request (you can implement this based on your cart system)
        setTimeout(() => {
            // Reset button
            $button.html(originalText).prop('disabled', false);
            
            // Show success message
            showNotification('Product added to cart successfully!', 'success');
            
            // Update cart count if you have a cart counter
            // updateCartCount();
        }, 1000);
        
        console.log('Adding to cart:', formData);
    });
    
    // Wishlist functionality
    $('.tf-product-btn-wishlist').on('click', function(e) {
        e.preventDefault();
        $(this).toggleClass('active');
        
        let isActive = $(this).hasClass('active');
        let message = isActive ? 'Added to wishlist!' : 'Removed from wishlist!';
        showNotification(message, 'success');
        
        console.log('Wishlist toggled:', isActive);
    });
    
    // Compare functionality
    $('.tf-product-btn-compare').on('click', function(e) {
        e.preventDefault();
        $(this).toggleClass('active');
        
        let isActive = $(this).hasClass('active');
        let message = isActive ? 'Added to compare!' : 'Removed from compare!';
        showNotification(message, 'success');
        
        console.log('Compare toggled:', isActive);
    });
    
    // Image gallery functionality
    $('.tf-product-media-thumbs .swiper-slide').on('click', function() {
        let index = $(this).index();
        
        // Update main image
        $('.tf-product-media-main .swiper-slide').removeClass('active');
        $('.tf-product-media-main .swiper-slide').eq(index).addClass('active');
        
        // Update thumbnail active state
        $('.tf-product-media-thumbs .swiper-slide').removeClass('active');
        $(this).addClass('active');
    });
    
    // Tab functionality
    $('.widget-menu-tab .item-title').on('click', function() {
        let index = $(this).index();
        
        // Remove active class from all tabs and content
        $('.widget-menu-tab .item-title').removeClass('active');
        $('.widget-content-inner').removeClass('active');
        
        // Add active class to clicked tab and corresponding content
        $(this).addClass('active');
        $('.widget-content-inner').eq(index).addClass('active');
    });
    
    // Image zoom functionality
    $('.tf-image-zoom').on('mouseenter', function() {
        $(this).css('cursor', 'zoom-in');
    }).on('click', function() {
        // You can implement a lightbox/zoom modal here
        console.log('Image zoom clicked');
    });
    
    // Notification helper function
    function showNotification(message, type = 'info') {
        // Simple notification - you can enhance this with a proper notification library
        let notification = $(`
            <div class="toast-notification ${type}" style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#28a745' : '#17a2b8'};
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                z-index: 9999;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            ">
                ${message}
            </div>
        `);
        
        $('body').append(notification);
        
        setTimeout(() => {
            notification.fadeOut(() => notification.remove());
        }, 3000);
    }
    
    // Initialize any additional Ecomus components
    initializeEcomusComponents();
    
    function initializeEcomusComponents() {
        // Initialize Swiper if available
        if (typeof Swiper !== 'undefined') {
            new Swiper('.tf-product-media-main', {
                spaceBetween: 10,
                navigation: {
                    nextEl: '.thumbs-next',
                    prevEl: '.thumbs-prev',
                },
                thumbs: {
                    swiper: new Swiper('.tf-product-media-thumbs', {
                        spaceBetween: 10,
                        slidesPerView: 4,
                        direction: 'vertical',
                        freeMode: true,
                        watchSlidesProgress: true,
                    })
                },
            });
        }
        
        // Initialize any other Ecomus-specific features
        console.log('Ecomus product detail components initialized');
    }
});
</script>
@endpush