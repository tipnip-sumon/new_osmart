@extends('layouts.app')

@section('title', $product->name . ' - ' . config('app.name'))
@section('description', Str::limit($product->description ?? $product->short_description ?? 'Product details', 160))

@push('styles')
<style>
    .product-image-gallery {
        position: relative;
    }
    .product-thumbnails {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }
    .product-thumbnails img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border: 2px solid transparent;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .product-thumbnails img.active,
    .product-thumbnails img:hover {
        border-color: var(--bs-primary);
    }
    .quantity-controls {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
    }
    .quantity-controls button {
        border: none;
        background: #f8f9fa;
        padding: 8px 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .quantity-controls button:hover {
        background: var(--bs-primary);
        color: white;
    }
    .quantity-controls input {
        border: none;
        text-align: center;
        width: 60px;
        padding: 8px;
    }
    .review-stars {
        color: #ffc107;
    }
    .shipping-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
    }
    .product-tabs .nav-link {
        border: none;
        border-bottom: 2px solid transparent;
        color: #666;
    }
    .product-tabs .nav-link.active {
        border-bottom-color: var(--bs-primary);
        color: var(--bs-primary);
    }
    .alert-cart {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        display: none;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Alert for cart actions -->
    <div class="alert alert-success alert-cart" id="cartAlert">
        <i class="ti ti-check-circle"></i> Product added to cart successfully!
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('categories.show', $product->category->slug) }}">{{ $product->category->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
            <!-- Product Images -->
            <div class="product-image-gallery">
                <div class="main-image mb-3">
                    @php
                        $mainImageUrl = asset('assets/img/product/default.png'); // Default fallback
                        $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                        
                        if ($images && is_array($images) && !empty($images)) {
                            $firstImage = $images[0];
                            // Handle complex nested structure first
                            if (is_array($firstImage) && isset($firstImage['sizes']['large']['storage_url'])) {
                                // New complex structure - use large size for main image
                                $mainImageUrl = $firstImage['sizes']['large']['storage_url'];
                            } elseif (is_array($firstImage) && isset($firstImage['sizes']['original']['storage_url'])) {
                                // Fallback to original if large not available
                                $mainImageUrl = $firstImage['sizes']['original']['storage_url'];
                            } elseif (is_array($firstImage) && isset($firstImage['sizes']['medium']['storage_url'])) {
                                // Fallback to medium if original not available
                                $mainImageUrl = $firstImage['sizes']['medium']['storage_url'];
                            } elseif (is_array($firstImage) && isset($firstImage['urls'])) {
                                // Legacy structure support
                                $mainImageUrl = $firstImage['urls']['large'] ?? $firstImage['urls']['original'] ?? asset('assets/img/product/default.png');
                            } elseif (is_string($firstImage)) {
                                $mainImageUrl = str_starts_with($firstImage, 'http') ? $firstImage : asset('storage/' . $firstImage);
                            }
                        }
                    @endphp
                    <img id="mainProductImage" 
                         src="{{ $mainImageUrl }}" 
                         alt="{{ $product->name }}" 
                         class="img-fluid rounded shadow" 
                         style="width: 100%; height: 400px; object-fit: cover;"
                         onerror="this.src='{{ asset('assets/img/product/default.png') }}'">
                </div>
                
                @if($images && is_array($images) && count($images) > 1)
                <div class="product-thumbnails">
                    @foreach($images as $index => $image)
                    @php
                        $thumbnailUrl = asset('assets/img/product/default.png'); // Default fallback
                        $fullUrl = asset('assets/img/product/default.png'); // Default fallback
                        
                        // Handle complex nested structure first
                        if (is_array($image) && isset($image['sizes']['thumbnail']['storage_url'])) {
                            // New complex structure - use thumbnail for thumbnails
                            $thumbnailUrl = $image['sizes']['thumbnail']['storage_url'];
                            $fullUrl = $image['sizes']['large']['storage_url'] ?? $image['sizes']['original']['storage_url'] ?? $thumbnailUrl;
                        } elseif (is_array($image) && isset($image['sizes']['small']['storage_url'])) {
                            // Fallback to small if thumbnail not available
                            $thumbnailUrl = $image['sizes']['small']['storage_url'];
                            $fullUrl = $image['sizes']['large']['storage_url'] ?? $image['sizes']['original']['storage_url'] ?? $thumbnailUrl;
                        } elseif (is_array($image) && isset($image['urls'])) {
                            // Legacy structure support
                            $thumbnailUrl = $image['urls']['thumbnail'] ?? $image['urls']['small'] ?? $image['urls']['original'] ?? asset('assets/img/product/default.png');
                            $fullUrl = $image['urls']['large'] ?? $image['urls']['original'] ?? asset('assets/img/product/default.png');
                        } elseif (is_string($image)) {
                            $thumbnailUrl = str_starts_with($image, 'http') ? $image : asset('storage/' . $image);
                            $fullUrl = str_starts_with($image, 'http') ? $image : asset('storage/' . $image);
                        }
                    @endphp
                    <img src="{{ $thumbnailUrl }}" 
                         alt="{{ $product->name }}" 
                         class="thumbnail-img {{ $index === 0 ? 'active' : '' }}"
                         onclick="changeMainImage('{{ $fullUrl }}', this)"
                         onerror="this.src='{{ asset('assets/img/product/default.png') }}'">
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        
        <div class="col-12 col-md-6">
            <!-- Product Details -->
            <div class="product-details">
                <h1 class="product-title mb-3">{{ $product->name }}</h1>
                
                <!-- Product Rating & Reviews -->
                <div class="product-rating mb-3">
                    <div class="review-stars mb-2">
                        @php
                            $averageRating = $product->average_rating;
                            $totalReviews = $product->total_reviews;
                        @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <i class="ti ti-star{{ $i <= $averageRating ? '-filled' : '' }}"></i>
                        @endfor
                        <span class="ms-2 text-muted">({{ number_format($averageRating, 1) }} out of 5) | <a href="#reviews-section" class="text-decoration-none">{{ $totalReviews }} {{ Str::plural('review', $totalReviews) }}</a></span>
                    </div>
                </div>

                <!-- Product Price -->
                <div class="product-pricing mb-4">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="current-price h3 text-primary" id="currentPrice">৳{{ number_format($product->sale_price, 2) }}</span>
                        <span class="original-price h5 text-muted ms-2"><del>৳{{ number_format($product->price, 2) }}</del></span>
                        <span class="discount-badge badge bg-danger ms-2">
                            {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                        </span>
                    @else
                        <span class="current-price h3 text-primary" id="currentPrice">৳{{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <!-- Product Description -->
                @if($product->short_description)
                <div class="product-description mb-4">
                    <p class="text-muted">{{ $product->short_description }}</p>
                </div>
                @endif

                <!-- Stock Status -->
                <div class="stock-status mb-3">
                    @if($product->stock_quantity > 0)
                        <span class="badge bg-success"><i class="ti ti-check"></i> In Stock ({{ $product->stock_quantity }} available)</span>
                    @else
                        <span class="badge bg-danger"><i class="ti ti-x"></i> Out of Stock</span>
                    @endif
                </div>

                <!-- Vendor Info -->
                @if($product->vendor)
                <div class="vendor-info mb-3">
                    <small class="text-muted">Sold by: <strong>{{ $product->vendor->name }}</strong></small>
                </div>
                @endif

                <!-- Product Variants (if any) -->
                @php
                    $hasVariants = false;
                    $sizeOptions = [];
                    $colorOptions = [];
                    
                    // Check for size options
                    if ($product->size && is_string($product->size)) {
                        $sizeOptions = explode(',', $product->size);
                        $hasVariants = true;
                    }
                    
                    // Check for color options
                    if ($product->color_options) {
                        if (is_array($product->color_options)) {
                            $colorOptions = $product->color_options;
                        } elseif (is_string($product->color_options)) {
                            $colorOptions = explode(',', $product->color_options);
                        }
                        $hasVariants = true;
                    } elseif ($product->color && is_string($product->color)) {
                        $colorOptions = explode(',', $product->color);
                        $hasVariants = true;
                    }
                    
                    // Check for variant attributes
                    if ($product->variant_attributes && is_array($product->variant_attributes)) {
                        foreach ($product->variant_attributes as $attribute => $values) {
                            if ($attribute === 'size' && is_array($values)) {
                                $sizeOptions = array_merge($sizeOptions, $values);
                                $hasVariants = true;
                            } elseif ($attribute === 'color' && is_array($values)) {
                                $colorOptions = array_merge($colorOptions, $values);
                                $hasVariants = true;
                            }
                        }
                    }
                    
                    // Clean and deduplicate options
                    $sizeOptions = array_unique(array_filter(array_map('trim', $sizeOptions)));
                    $colorOptions = array_unique(array_filter(array_map('trim', $colorOptions)));
                @endphp
                
                @if($hasVariants || !empty($sizeOptions) || !empty($colorOptions))
                <div class="product-variants mb-4">
                    <div class="row g-3">
                        @if(!empty($sizeOptions))
                        <div class="col-md-6">
                            <label class="form-label">Size:</label>
                            <select class="form-select" id="productSize" name="size">
                                <option value="">Select Size</option>
                                @foreach($sizeOptions as $index => $size)
                                    <option value="{{ $size }}" {{ $index === 0 ? 'selected' : '' }}>
                                        {{ ucfirst($size) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        
                        @if(!empty($colorOptions))
                        <div class="col-md-6">
                            <label class="form-label">Color:</label>
                            <select class="form-select" id="productColor" name="color">
                                <option value="">Select Color</option>
                                @foreach($colorOptions as $index => $color)
                                    <option value="{{ $color }}" {{ $index === 0 ? 'selected' : '' }}>
                                        {{ ucfirst($color) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        
                        @if($product->variant_attributes && is_array($product->variant_attributes))
                            @foreach($product->variant_attributes as $attribute => $values)
                                @if($attribute !== 'size' && $attribute !== 'color' && is_array($values) && !empty($values))
                                <div class="col-md-6">
                                    <label class="form-label">{{ ucfirst($attribute) }}:</label>
                                    <select class="form-select" id="product{{ ucfirst($attribute) }}" name="{{ $attribute }}">
                                        <option value="">Select {{ ucfirst($attribute) }}</option>
                                        @foreach($values as $index => $value)
                                            <option value="{{ $value }}" {{ $index === 0 ? 'selected' : '' }}>
                                                {{ ucfirst($value) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                @endif

                <!-- Quantity & Add to Cart -->
                <div class="add-to-cart-section mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Quantity:</label>
                            <div class="quantity-controls">
                                <button type="button" onclick="decreaseQuantity()">-</button>
                                <input type="number" id="productQuantity" value="1" min="1" max="{{ $product->stock_quantity }}" readonly>
                                <button type="button" onclick="increaseQuantity()">+</button>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button class="btn btn-primary btn-lg" onclick="addToCart()" {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                    <i class="ti ti-shopping-cart"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons mb-4">
                    <div class="row g-2">
                        <div class="col-6">
                            <button class="btn btn-outline-danger w-100" onclick="toggleWishlist()">
                                <i class="ti ti-heart" id="wishlistIcon"></i> Add to Wishlist
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-outline-primary w-100" onclick="shareProduct()">
                                <i class="ti ti-share"></i> Share
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Shipping Info -->
                <div class="shipping-info">
                    <h6><i class="ti ti-truck"></i> Shipping Information</h6>
                    <ul class="list-unstyled mb-0">
                        <li><i class="ti ti-check text-success"></i> Free shipping on orders over ৳5000</li>
                        <li><i class="ti ti-check text-success"></i> Express delivery available</li>
                        <li><i class="ti ti-check text-success"></i> 30-day return policy</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Tabs -->
    <div class="row mt-5">
        <div class="col-12">
            <nav>
                <div class="nav nav-tabs product-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">Description</button>
                    <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab">Specifications</button>
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">Reviews ({{ $totalReviews }})</button>
                    <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab">Shipping</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <!-- Description Tab -->
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <div class="p-4">
                        <h5>Product Description</h5>
                        <p>{{ $product->description ?: 'Detailed product description will be available soon. This is a high-quality product with excellent features and specifications.' }}</p>
                        
                        @if($product->description)
                        <div class="product-features mt-4">
                            <h6>Key Features:</h6>
                            <ul>
                                <li>Premium quality materials</li>
                                <li>Durable construction</li>
                                <li>Modern design</li>
                                <li>Easy to use</li>
                                <li>Great value for money</li>
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Specifications Tab -->
                <div class="tab-pane fade" id="specifications" role="tabpanel">
                    <div class="p-4">
                        <h5>Product Specifications</h5>
                        
                        @php
                            // Define dynamic specification groups
                            $basicSpecs = [
                                'SKU' => $product->sku,
                                'Brand' => $product->brand->name ?? null,
                                'Model Number' => $product->model_number,
                                'Barcode' => $product->barcode,
                                'MPN' => $product->mpn,
                                'GTIN' => $product->gtin,
                                'Condition' => $product->condition ? ucfirst($product->condition) : null,
                            ];
                            
                            $physicalSpecs = [
                                'Weight' => $product->weight ? $product->weight . ' kg' : null,
                                'Length' => $product->length ? $product->length . ' cm' : null,
                                'Width' => $product->width ? $product->width . ' cm' : null,
                                'Height' => $product->height ? $product->height . ' cm' : null,
                                'Shipping Weight' => $product->shipping_weight ? $product->shipping_weight . ' kg' : null,
                                'Size' => $product->size,
                                'Color' => $product->color,
                                'Material' => $product->material,
                            ];
                            
                            $businessSpecs = [
                                'Stock Quantity' => $product->stock_quantity,
                                'Min Stock Level' => $product->min_stock_level,
                                'Max Stock Level' => $product->max_stock_level,
                                'Cost Price' => $product->cost_price ? '৳' . number_format($product->cost_price, 2) : null,
                                'Wholesale Price' => $product->wholesale_price ? '৳' . number_format($product->wholesale_price, 2) : null,
                                'Tax Rate' => $product->tax_rate ? $product->tax_rate . '%' : null,
                                'Tax Class' => $product->tax_class,
                            ];
                            
                            $shippingSpecs = [
                                'Free Shipping' => $product->free_shipping ? 'Yes' : 'No',
                                'Shipping Cost' => $product->shipping_cost ? '৳' . number_format($product->shipping_cost, 2) : 'Free',
                            ];
                            
                            // Only show these fields if they are true/Yes
                            if ($product->is_digital) {
                                $shippingSpecs['Digital Product'] = 'Yes';
                            }
                            if ($product->is_virtual) {
                                $shippingSpecs['Virtual Product'] = 'Yes';
                            }
                            if ($product->is_downloadable) {
                                $shippingSpecs['Downloadable'] = 'Yes';
                            }
                            
                            $supportSpecs = [
                                'Warranty Period' => $product->warranty_period,
                                'Support Email' => $product->support_email,
                                'Support Phone' => $product->support_phone,
                            ];
                            
                            // Custom specifications from JSON field
                            $customSpecs = [];
                            if ($product->specifications && is_array($product->specifications)) {
                                $customSpecs = $product->specifications;
                            }
                            
                            // Filter out empty values
                            $basicSpecs = array_filter($basicSpecs, function($value) { return !is_null($value) && $value !== ''; });
                            $physicalSpecs = array_filter($physicalSpecs, function($value) { return !is_null($value) && $value !== ''; });
                            $businessSpecs = array_filter($businessSpecs, function($value) { return !is_null($value) && $value !== ''; });
                            $shippingSpecs = array_filter($shippingSpecs, function($value) { return !is_null($value) && $value !== ''; });
                            $supportSpecs = array_filter($supportSpecs, function($value) { return !is_null($value) && $value !== ''; });
                        @endphp
                        
                        @if(count($basicSpecs) > 0 || count($physicalSpecs) > 0 || count($businessSpecs) > 0 || count($shippingSpecs) > 0 || count($supportSpecs) > 0 || count($customSpecs) > 0)
                            <div class="row">
                                <!-- Basic Information -->
                                @if(count($basicSpecs) > 0)
                                <div class="col-md-6 mb-4">
                                    <h6 class="text-primary mb-3"><i class="ti ti-info-circle me-2"></i>Basic Information</h6>
                                    <table class="table table-striped table-sm">
                                        @foreach($basicSpecs as $label => $value)
                                            <tr>
                                                <td><strong>{{ $label }}</strong></td>
                                                <td>{{ $value }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                                @endif
                                
                                <!-- Physical Properties -->
                                @if(count($physicalSpecs) > 0)
                                <div class="col-md-6 mb-4">
                                    <h6 class="text-primary mb-3"><i class="ti ti-ruler me-2"></i>Physical Properties</h6>
                                    <table class="table table-striped table-sm">
                                        @foreach($physicalSpecs as $label => $value)
                                            <tr>
                                                <td><strong>{{ $label }}</strong></td>
                                                <td>{{ $value }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                                @endif
                                
                                <!-- Business Information -->
                                @if(count($businessSpecs) > 0)
                                <div class="col-md-6 mb-4">
                                    <h6 class="text-primary mb-3"><i class="ti ti-building-store me-2"></i>Business Information</h6>
                                    <table class="table table-striped table-sm">
                                        @foreach($businessSpecs as $label => $value)
                                            <tr>
                                                <td><strong>{{ $label }}</strong></td>
                                                <td>{{ $value }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                                @endif
                                
                                <!-- Shipping & Delivery -->
                                @if(count($shippingSpecs) > 0)
                                <div class="col-md-6 mb-4">
                                    <h6 class="text-primary mb-3"><i class="ti ti-truck me-2"></i>Shipping & Delivery</h6>
                                    <table class="table table-striped table-sm">
                                        @foreach($shippingSpecs as $label => $value)
                                            <tr>
                                                <td><strong>{{ $label }}</strong></td>
                                                <td>{{ $value }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                                @endif
                                
                                <!-- Support & Warranty -->
                                @if(count($supportSpecs) > 0)
                                <div class="col-md-6 mb-4">
                                    <h6 class="text-primary mb-3"><i class="ti ti-headset me-2"></i>Support & Warranty</h6>
                                    <table class="table table-striped table-sm">
                                        @foreach($supportSpecs as $label => $value)
                                            <tr>
                                                <td><strong>{{ $label }}</strong></td>
                                                <td>{{ $value }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                                @endif
                                
                                <!-- Custom Specifications -->
                                @if(count($customSpecs) > 0)
                                <div class="col-md-6 mb-4">
                                    <h6 class="text-primary mb-3"><i class="ti ti-settings me-2"></i>Technical Specifications</h6>
                                    <table class="table table-striped table-sm">
                                        @foreach($customSpecs as $label => $value)
                                            <tr>
                                                <td><strong>{{ ucwords(str_replace(['_', '-'], ' ', $label)) }}</strong></td>
                                                <td>{{ is_array($value) ? implode(', ', $value) : $value }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Product Features -->
                            @if($product->features && is_array($product->features) && count($product->features) > 0)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3"><i class="ti ti-star me-2"></i>Key Features</h6>
                                    <ul class="list-group list-group-flush">
                                        @foreach($product->features as $feature)
                                            <li class="list-group-item border-0 px-0">
                                                <i class="ti ti-check text-success me-2"></i>{{ $feature }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Included Items -->
                            @if($product->included_items && is_array($product->included_items) && count($product->included_items) > 0)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3"><i class="ti ti-package me-2"></i>What's in the Box</h6>
                                    <ul class="list-group list-group-flush">
                                        @foreach($product->included_items as $item)
                                            <li class="list-group-item border-0 px-0">
                                                <i class="ti ti-box text-info me-2"></i>{{ $item }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Compatibility -->
                            @if($product->compatibility && is_array($product->compatibility) && count($product->compatibility) > 0)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3"><i class="ti ti-puzzle me-2"></i>Compatibility</h6>
                                    <ul class="list-group list-group-flush">
                                        @foreach($product->compatibility as $item)
                                            <li class="list-group-item border-0 px-0">
                                                <i class="ti ti-check-circle text-success me-2"></i>{{ $item }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="ti ti-info-circle text-muted" style="font-size: 3rem;"></i>
                                <h6 class="text-muted mt-3">No specifications available</h6>
                                <p class="text-muted">Product specifications will be updated soon.</p>
                                @auth
                                    @if(auth()->user()->hasRole('admin') || auth()->user()->id == $product->vendor_id)
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary btn-sm mt-2">
                                            <i class="ti ti-edit me-1"></i>Update Specifications
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="p-4" id="reviews-section">
                        <h5>Customer Reviews</h5>
                        
                        <!-- Review Statistics -->
                        <div class="review-stats mb-4" id="review-stats">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="overall-rating text-center">
                                        <h2 class="text-primary" id="overall-rating">{{ number_format($averageRating, 1) }}</h2>
                                        <div class="review-stars mb-2" id="overall-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="ti ti-star{{ $i <= $averageRating ? '-filled' : '' }}"></i>
                                            @endfor
                                        </div>
                                        <p class="text-muted">Based on <span id="total-reviews-count">{{ $totalReviews }}</span> {{ Str::plural('review', $totalReviews) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="rating-breakdown" id="rating-breakdown">
                                        <!-- Will be populated by JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Review Filters -->
                        <div class="review-filters mb-3">
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="btn btn-sm btn-outline-primary active" data-rating="all">All Reviews</button>
                                <button class="btn btn-sm btn-outline-primary" data-rating="5">5 Stars</button>
                                <button class="btn btn-sm btn-outline-primary" data-rating="4">4 Stars</button>
                                <button class="btn btn-sm btn-outline-primary" data-rating="3">3 Stars</button>
                                <button class="btn btn-sm btn-outline-primary" data-rating="2">2 Stars</button>
                                <button class="btn btn-sm btn-outline-primary" data-rating="1">1 Star</button>
                            </div>
                        </div>

                        <!-- Reviews List -->
                        <div class="reviews-list" id="reviews-list">
                            <div class="text-center py-4" id="reviews-loading">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading reviews...</p>
                            </div>
                        </div>

                        <!-- Load More Button -->
                        <div class="text-center mt-3" id="load-more-container" style="display: none;">
                            <button class="btn btn-outline-primary" id="load-more-btn">Load More Reviews</button>
                        </div>

                        <!-- Add Review Form -->
                        <div class="add-review mt-5">
                            <h6>Write a Review</h6>
                            @auth
                                <form id="reviewForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Rating: <span class="text-danger">*</span></label>
                                        <div class="review-rating-input">
                                            <i class="ti ti-star" data-rating="1"></i>
                                            <i class="ti ti-star" data-rating="2"></i>
                                            <i class="ti ti-star" data-rating="3"></i>
                                            <i class="ti ti-star" data-rating="4"></i>
                                            <i class="ti ti-star" data-rating="5"></i>
                                        </div>
                                        <input type="hidden" name="rating" id="rating-input" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Review Title: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title" placeholder="Summary of your review" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Your Review: <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="comment" rows="4" placeholder="Share your experience with this product..." required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Add Photos (Optional):</label>
                                        <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                                        <small class="text-muted">You can upload up to 5 images (JPEG, PNG, GIF - Max 2MB each)</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary" id="submit-review-btn">
                                        <span class="spinner-border spinner-border-sm me-2" role="status" style="display: none;"></span>
                                        Submit Review
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle me-2"></i>
                                    Please <a href="{{ route('login') }}">login</a> to write a review.
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
                
                <!-- Shipping Tab -->
                <div class="tab-pane fade" id="shipping" role="tabpanel">
                    <div class="p-4">
                        <h5>Shipping Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Delivery Options</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="ti ti-truck text-primary"></i> <strong>Standard Delivery:</strong> 3-5 business days - FREE</li>
                                    <li class="mb-2"><i class="ti ti-bolt text-warning"></i> <strong>Express Delivery:</strong> 1-2 business days - ৳999</li>
                                    <li class="mb-2"><i class="ti ti-clock text-info"></i> <strong>Same Day:</strong> Order before 2PM - ৳1999</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Return Policy</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="ti ti-check text-success"></i> 30-day return window</li>
                                    <li class="mb-2"><i class="ti ti-check text-success"></i> Free return shipping</li>
                                    <li class="mb-2"><i class="ti ti-check text-success"></i> Money-back guarantee</li>
                                    <li class="mb-2"><i class="ti ti-check text-success"></i> Original packaging required</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="mb-4">Related Products</h4>
            <div class="row g-3">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-6 col-md-3">
                    <div class="card product-card h-100">
                        <div class="card-body text-center">
                            <a href="{{ route('products.show', $relatedProduct->slug) }}">
                                @php
                                    $relatedImages = is_string($relatedProduct->images) ? json_decode($relatedProduct->images, true) : $relatedProduct->images;
                                    $relatedFirstImage = is_array($relatedImages) && !empty($relatedImages) ? $relatedImages[0] : null;
                                    $relatedImageUrl = asset('assets/img/product/default.png'); // Default fallback
                                    
                                    if ($relatedFirstImage) {
                                        // Handle complex nested structure first
                                        if (is_array($relatedFirstImage) && isset($relatedFirstImage['sizes']['medium']['storage_url'])) {
                                            // New complex structure - use medium size
                                            $relatedImageUrl = $relatedFirstImage['sizes']['medium']['storage_url'];
                                        } elseif (is_array($relatedFirstImage) && isset($relatedFirstImage['sizes']['original']['storage_url'])) {
                                            // Fallback to original if medium not available
                                            $relatedImageUrl = $relatedFirstImage['sizes']['original']['storage_url'];
                                        } elseif (is_array($relatedFirstImage) && isset($relatedFirstImage['sizes']['small']['storage_url'])) {
                                            // Fallback to small if original not available
                                            $relatedImageUrl = $relatedFirstImage['sizes']['small']['storage_url'];
                                        } elseif (is_string($relatedFirstImage)) {
                                            $relatedImageUrl = str_starts_with($relatedFirstImage, 'http') ? $relatedFirstImage : asset('storage/' . $relatedFirstImage);
                                        }
                                    }
                                @endphp
                                <img src="{{ $relatedImageUrl }}" 
                                     alt="{{ $relatedProduct->name }}" 
                                     class="img-fluid mb-2" 
                                     style="height: 120px; object-fit: cover;"
                                     onerror="this.src='{{ asset('assets/img/product/default.png') }}'">
                            <h6 class="product-title mb-1">
                                <a href="{{ route('products.show', $relatedProduct->slug) }}" class="text-decoration-none">
                                    {{ Str::limit($relatedProduct->name, 15) }}
                                </a>
                            </h6>
                            <p class="product-price mb-2">
                                @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                    <span class="text-primary">৳{{ number_format($relatedProduct->sale_price, 2) }}</span>
                                    <small class="text-muted"><del>৳{{ number_format($relatedProduct->price, 2) }}</del></small>
                                @else
                                    <span class="text-primary">৳{{ number_format($relatedProduct->price, 2) }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize product data
    const productData = {
        id: {{ $product->id }},
        name: '{{ $product->name }}',
        price: {{ $product->sale_price ?? $product->price }},
        originalPrice: {{ $product->price }},
        stock: {{ $product->stock_quantity }},
        slug: '{{ $product->slug }}'
    };

    // Get main image URL for cart
    @php
        $cartImageUrl = asset('assets/img/product/default.png'); // Default fallback
        $jsImages = is_string($product->images) ? json_decode($product->images, true) : $product->images;
        
        if ($jsImages && is_array($jsImages) && !empty($jsImages)) {
            $firstImage = $jsImages[0];
            // Handle complex nested structure first
            if (is_array($firstImage) && isset($firstImage['sizes']['medium']['storage_url'])) {
                // New complex structure - use medium size for cart
                $cartImageUrl = $firstImage['sizes']['medium']['storage_url'];
            } elseif (is_array($firstImage) && isset($firstImage['sizes']['original']['storage_url'])) {
                // Fallback to original if medium not available
                $cartImageUrl = $firstImage['sizes']['original']['storage_url'];
            } elseif (is_array($firstImage) && isset($firstImage['sizes']['small']['storage_url'])) {
                // Fallback to small if original not available
                $cartImageUrl = $firstImage['sizes']['small']['storage_url'];
            } elseif (is_array($firstImage) && isset($firstImage['urls'])) {
                // Legacy structure support
                $cartImageUrl = $firstImage['urls']['medium'] ?? $firstImage['urls']['original'] ?? asset('assets/img/product/default.png');
            } elseif (is_string($firstImage)) {
                $cartImageUrl = str_starts_with($firstImage, 'http') ? $firstImage : asset('storage/' . $firstImage);
            }
        }
    @endphp
    const productImageUrl = '{{ $cartImageUrl }}';

    // Reviews variables
    let currentPage = 1;
    let currentRating = 'all';
    let isLoading = false;

    // Image gallery functionality
    window.changeMainImage = function(imageSrc, thumbnailElement) {
        $('#mainProductImage').attr('src', imageSrc);
        $('.thumbnail-img').removeClass('active');
        $(thumbnailElement).addClass('active');
    };

    // Quantity controls
    window.increaseQuantity = function() {
        const quantityInput = $('#productQuantity');
        const currentValue = parseInt(quantityInput.val());
        const maxValue = parseInt(quantityInput.attr('max'));
        
        if (currentValue < maxValue) {
            quantityInput.val(currentValue + 1);
            updateTotalPrice();
        }
    };

    window.decreaseQuantity = function() {
        const quantityInput = $('#productQuantity');
        const currentValue = parseInt(quantityInput.val());
        
        if (currentValue > 1) {
            quantityInput.val(currentValue - 1);
            updateTotalPrice();
        }
    };

    // Update total price based on quantity
    function updateTotalPrice() {
        const quantity = parseInt($('#productQuantity').val());
        const price = productData.price;
        const total = (price * quantity).toFixed(2);
        $('#currentPrice').text('৳' + total);
    }

    // Add to cart functionality
    window.addToCart = function() {
        const quantity = parseInt($('#productQuantity').val());
        
        // Collect all variant selections dynamically
        const variants = {};
        
        // Get size if available
        const sizeSelect = $('#productSize');
        if (sizeSelect.length && sizeSelect.val()) {
            variants.size = sizeSelect.val();
        }
        
        // Get color if available
        const colorSelect = $('#productColor');
        if (colorSelect.length && colorSelect.val()) {
            variants.color = colorSelect.val();
        }
        
        // Get other dynamic variant attributes
        $('.product-variants select').each(function() {
            const select = $(this);
            const name = select.attr('name');
            const value = select.val();
            
            if (name && value && !variants[name]) {
                variants[name] = value;
            }
        });

        // Prepare cart data
        const cartData = {
            product_id: productData.id,
            name: productData.name,
            price: productData.price,
            quantity: quantity,
            variants: variants,
            total: (productData.price * quantity).toFixed(2),
            image: productImageUrl
        };
        
        // Add individual variant properties for backward compatibility
        if (variants.size) cartData.size = variants.size;
        if (variants.color) cartData.color = variants.color;

        // Use global add to cart function
        globalAddToCart(cartData);

        // Show success alert
        $('#cartAlert').fadeIn().delay(3000).fadeOut();

        // Animate cart icon
        animateCartAdd();

        console.log('Product added to cart:', cartData);
    };

    // Wishlist functionality
    window.toggleWishlist = function() {
        const icon = $('#wishlistIcon');
        const button = icon.closest('button');
        
        if (icon.hasClass('ti-heart-filled')) {
            icon.removeClass('ti-heart-filled').addClass('ti-heart');
            button.removeClass('btn-danger').addClass('btn-outline-danger');
            button.html('<i class="ti ti-heart" id="wishlistIcon"></i> Add to Wishlist');
        } else {
            icon.removeClass('ti-heart').addClass('ti-heart-filled');
            button.removeClass('btn-outline-danger').addClass('btn-danger');
            button.html('<i class="ti ti-heart-filled" id="wishlistIcon"></i> In Wishlist');
        }

        // Save to wishlist
        let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        const productIndex = wishlist.indexOf(productData.id);
        
        if (productIndex > -1) {
            wishlist.splice(productIndex, 1);
        } else {
            wishlist.push(productData.id);
        }
        
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
    };

    // Share functionality
    window.shareProduct = function() {
        if (navigator.share) {
            navigator.share({
                title: productData.name,
                text: 'Check out this amazing product!',
                url: window.location.href
            });
        } else {
            // Fallback: copy to clipboard
            navigator.clipboard.writeText(window.location.href).then(function() {
                alert('Product link copied to clipboard!');
            });
        }
    };

    // Review rating functionality
    $('.review-rating-input i').click(function() {
        const rating = $(this).data('rating');
        $('#rating-input').val(rating);
        $('.review-rating-input i').removeClass('ti-star-filled').addClass('ti-star');
        
        for (let i = 1; i <= rating; i++) {
            $('.review-rating-input i[data-rating="' + i + '"]').removeClass('ti-star').addClass('ti-star-filled');
        }
    });

    // Review form submission
    $('#reviewForm').submit(function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = $('#submit-review-btn');
        const spinner = submitBtn.find('.spinner-border');
        
        // Disable submit button and show spinner
        submitBtn.prop('disabled', true);
        spinner.show();
        
        // Create FormData for file upload
        const formData = new FormData(this);
        
        // Submit review via AJAX
        $.ajax({
            url: '{{ route("products.reviews.store", $product) }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showAlert('success', response.message);
                    
                    // Reset form
                    form[0].reset();
                    $('#rating-input').val('');
                    $('.review-rating-input i').removeClass('ti-star-filled').addClass('ti-star');
                    
                    // Reload reviews
                    loadReviews(true);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response && response.errors) {
                    let errorMessage = 'Please fix the following errors:\n';
                    Object.values(response.errors).forEach(errors => {
                        errors.forEach(error => {
                            errorMessage += '- ' + error + '\n';
                        });
                    });
                    showAlert('danger', errorMessage);
                } else {
                    showAlert('danger', response?.message || 'An error occurred while submitting your review.');
                }
            },
            complete: function() {
                // Re-enable submit button and hide spinner
                submitBtn.prop('disabled', false);
                spinner.hide();
            }
        });
    });

    // Load reviews function
    function loadReviews(reset = false) {
        if (isLoading) return;
        
        isLoading = true;
        
        if (reset) {
            currentPage = 1;
            $('#reviews-list').empty();
        }
        
        $('#reviews-loading').show();
        
        $.ajax({
            url: '{{ route("products.reviews", $product) }}',
            method: 'GET',
            data: {
                page: currentPage,
                rating: currentRating,
                per_page: 5
            },
            success: function(response) {
                if (response.success) {
                    // Update stats
                    updateReviewStats(response.stats);
                    
                    // Render reviews
                    renderReviews(response.reviews, reset);
                    
                    // Update pagination
                    if (response.pagination.has_more) {
                        $('#load-more-container').show();
                        currentPage++;
                    } else {
                        $('#load-more-container').hide();
                    }
                }
            },
            error: function(xhr) {
                console.error('Failed to load reviews:', xhr);
                showAlert('danger', 'Failed to load reviews. Please try again.');
            },
            complete: function() {
                isLoading = false;
                $('#reviews-loading').hide();
            }
        });
    }

    // Update review statistics
    function updateReviewStats(stats) {
        $('#overall-rating').text(stats.average_rating.toFixed(1));
        $('#total-reviews-count').text(stats.total_reviews);
        
        // Update stars
        const starContainer = $('#overall-stars');
        starContainer.empty();
        for (let i = 1; i <= 5; i++) {
            const starClass = i <= stats.average_rating ? 'ti-star-filled' : 'ti-star';
            starContainer.append(`<i class="ti ${starClass}"></i>`);
        }
        
        // Update rating breakdown
        const breakdown = $('#rating-breakdown');
        breakdown.empty();
        
        Object.keys(stats.rating_breakdown).sort((a, b) => b - a).forEach(rating => {
            const data = stats.rating_breakdown[rating];
            breakdown.append(`
                <div class="d-flex align-items-center mb-2">
                    <span class="me-2">${rating}★</span>
                    <div class="progress flex-grow-1 me-2" style="height: 10px;">
                        <div class="progress-bar" style="width: ${data.percentage}%"></div>
                    </div>
                    <span class="text-muted">${data.percentage}%</span>
                </div>
            `);
        });
    }

    // Render reviews
    function renderReviews(reviews, reset = false) {
        const container = $('#reviews-list');
        
        if (reset) {
            container.empty();
        }
        
        if (reviews.length === 0 && reset) {
            container.html(`
                <div class="text-center py-4">
                    <i class="ti ti-message-circle-2 text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">No reviews yet. Be the first to review this product!</p>
                </div>
            `);
            return;
        }
        
        reviews.forEach(review => {
            const reviewHtml = `
                <div class="review-item border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <strong class="me-2">${review.user.name}</strong>
                                ${review.is_verified_purchase ? '<span class="badge bg-success me-2">Verified Purchase</span>' : ''}
                                <div class="review-stars">
                                    ${generateStarsHtml(review.rating)}
                                </div>
                            </div>
                            <h6 class="review-title mb-1">${review.title}</h6>
                            <p class="review-comment">${review.comment}</p>
                            ${review.images.length > 0 ? `
                                <div class="review-images mt-2">
                                    ${review.images.map(img => `<img src="${img}" class="review-image me-2" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;" alt="Review image">`).join('')}
                                </div>
                            ` : ''}
                            <div class="review-actions mt-2">
                                <button class="btn btn-sm btn-outline-secondary" onclick="markHelpful(${review.id})">
                                    <i class="ti ti-thumb-up"></i> Helpful (${review.helpful_count})
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">${review.formatted_date}</small>
                    </div>
                </div>
            `;
            container.append(reviewHtml);
        });
    }

    // Generate stars HTML
    function generateStarsHtml(rating) {
        let starsHtml = '';
        for (let i = 1; i <= 5; i++) {
            const starClass = i <= rating ? 'ti-star-filled' : 'ti-star';
            starsHtml += `<i class="ti ${starClass}"></i>`;
        }
        return starsHtml;
    }

    // Mark review as helpful
    window.markHelpful = function(reviewId) {
        $.ajax({
            url: `/reviews/${reviewId}/helpful`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update helpful count in UI
                    const button = $(`button[onclick="markHelpful(${reviewId})"]`);
                    button.html(`<i class="ti ti-thumb-up"></i> Helpful (${response.helpful_count})`);
                }
            },
            error: function(xhr) {
                console.error('Failed to mark as helpful:', xhr);
            }
        });
    };

    // Review filter buttons
    $('.review-filters button').click(function() {
        const rating = $(this).data('rating');
        currentRating = rating;
        
        // Update active button
        $('.review-filters button').removeClass('active');
        $(this).addClass('active');
        
        // Load filtered reviews
        loadReviews(true);
    });

    // Load more reviews
    $('#load-more-btn').click(function() {
        loadReviews(false);
    });

    // Show alert function
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Add alert above the form
        const container = $('.add-review');
        container.prepend(alertHtml);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            container.find('.alert').fadeOut();
        }, 5000);
    }

    // Load wishlist state
    const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    if (wishlist.includes(productData.id)) {
        // Set the wishlist state without calling toggleWishlist to avoid double-toggle
        const icon = $('#wishlistIcon');
        const button = icon.closest('button');
        icon.removeClass('ti-heart').addClass('ti-heart-filled');
        button.removeClass('btn-outline-danger').addClass('btn-danger');
        button.html('<i class="ti ti-heart-filled" id="wishlistIcon"></i> In Wishlist');
    }

    // Initialize cart count
    updateCartCount();

    // Load reviews when reviews tab is shown
    $('button[data-bs-target="#reviews"]').on('shown.bs.tab', function () {
        if ($('#reviews-list').children().length === 0) {
            loadReviews(true);
        }
    });

    // Load reviews immediately if reviews tab is active
    if ($('#reviews').hasClass('active')) {
        loadReviews(true);
    }
});
</script>
@endpush
