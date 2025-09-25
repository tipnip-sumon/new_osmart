@extends('member.layouts.app')

@section('title', $product->name . ' - Product Details')

@push('styles')
<style>
.product-gallery {
    position: relative;
}

.product-main-image {
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 15px;
}

.product-main-image img {
    width: 100%;
    height: 400px;
    object-fit: cover;
}

.product-thumbnails {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.thumbnail-item {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.thumbnail-item.active,
.thumbnail-item:hover {
    border-color: var(--primary);
}

.thumbnail-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-info {
    padding: 20px 0;
}

.product-price {
    margin: 20px 0;
}

.original-price {
    color: #999;
    text-decoration: line-through;
    font-size: 1.1rem;
    margin-right: 10px;
}

.sale-price {
    color: var(--primary);
    font-size: 1.8rem;
    font-weight: 600;
}

.discount-badge {
    background: #e74c3c;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85rem;
    margin-left: 10px;
}

.product-actions {
    margin: 30px 0;
}

.btn-action {
    margin-right: 10px;
    margin-bottom: 10px;
}

.stock-status {
    margin: 15px 0;
}

.in-stock {
    color: #27ae60;
    font-weight: 600;
}

.out-of-stock {
    color: #e74c3c;
    font-weight: 600;
}

.low-stock {
    color: #f39c12;
    font-weight: 600;
}

.product-meta {
    border-top: 1px solid #eee;
    padding-top: 20px;
    margin-top: 30px;
}

.meta-item {
    margin-bottom: 10px;
}

.affiliate-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
    margin: 30px 0;
}

.affiliate-link-container {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.affiliate-link-input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background: white;
    font-size: 0.9rem;
}

.related-products {
    margin-top: 50px;
}

.related-product-card {
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
}

.related-product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.related-product-image {
    height: 200px;
    overflow: hidden;
}

.related-product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.related-product-info {
    padding: 15px;
}

.favorite-btn {
    background: none;
    border: none;
    color: #ccc;
    font-size: 1.5rem;
    transition: color 0.3s ease;
    cursor: pointer;
}

.favorite-btn.favorited {
    color: #e74c3c;
}

.rating-stars {
    color: #ffc107;
    margin: 10px 0;
}

.product-tabs {
    margin-top: 40px;
}

.tab-content {
    padding: 30px 0;
}

.review-item {
    border-bottom: 1px solid #eee;
    padding: 20px 0;
}

.review-item:last-child {
    border-bottom: none;
}

.reviewer-info {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.reviewer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 15px;
}

.review-rating {
    color: #ffc107;
    margin-bottom: 5px;
}
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Breadcrumb -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Product Details</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($product->name, 30) }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Product Details -->
        <div class="row">
            <!-- Product Images -->
            <div class="col-xl-5 col-lg-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="product-gallery">
                            <div class="product-main-image">
                                @php
                                    $legacyImageUrl = '';
                                    
                                    // Check for complex images JSON structure first
                                    if (isset($product->images) && $product->images) {
                                        $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                                        if (is_array($images) && !empty($images)) {
                                            $image = $images[0]; // Get first image
                                            
                                            // Handle complex nested structure first
                                            if (is_array($image) && isset($image['sizes']['large']['storage_url'])) {
                                                // Use large size for main product image
                                                $legacyImageUrl = $image['sizes']['large']['storage_url'];
                                            } elseif (is_array($image) && isset($image['sizes']['original']['storage_url'])) {
                                                // Fallback to original if large not available
                                                $legacyImageUrl = $image['sizes']['original']['storage_url'];
                                            } elseif (is_array($image) && isset($image['sizes']['medium']['storage_url'])) {
                                                // Fallback to medium if original not available
                                                $legacyImageUrl = $image['sizes']['medium']['storage_url'];
                                            } elseif (is_array($image) && isset($image['urls']['large'])) {
                                                // Legacy complex URL structure - use large size
                                                $legacyImageUrl = $image['urls']['large'];
                                            } elseif (is_array($image) && isset($image['urls']['original'])) {
                                                // Legacy fallback to original if large not available
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
                                            $legacyImageUrl = asset('assets/img/product/default.png');
                                        }
                                    }
                                @endphp
                                <img id="mainProductImage" 
                                     src="{{ $legacyImageUrl }}" 
                                     alt="{{ $product->name }}"
                                     onerror="this.src='{{ asset('assets/img/product/default.png') }}'">
                            </div>
                            
                            @if($product->images)
                                <div class="product-thumbnails">
                                    <!-- Main image thumbnail -->
                                    <div class="thumbnail-item active" data-image="{{ $legacyImageUrl }}">
                                        <img src="{{ $legacyImageUrl }}" 
                                             alt="{{ $product->name }}"
                                             onerror="this.src='{{ asset('assets/img/product/default.png') }}'">
                                    </div>
                                    
                                    <!-- Additional images -->
                                    @if($product->images)
                                        @php
                                            $additionalImages = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                                        @endphp
                                        @if(is_array($additionalImages) && count($additionalImages) > 1)
                                            @foreach(array_slice($additionalImages, 1) as $image)
                                                @php
                                                    $thumbImageUrl = '';
                                                    if (is_array($image) && isset($image['sizes']['small']['storage_url'])) {
                                                        $thumbImageUrl = $image['sizes']['small']['storage_url'];
                                                    } elseif (is_array($image) && isset($image['sizes']['medium']['storage_url'])) {
                                                        $thumbImageUrl = $image['sizes']['medium']['storage_url'];
                                                    } elseif (is_array($image) && isset($image['urls']['small'])) {
                                                        $thumbImageUrl = $image['urls']['small'];
                                                    } elseif (is_array($image) && isset($image['urls']['medium'])) {
                                                        $thumbImageUrl = $image['urls']['medium'];
                                                    } elseif (is_array($image) && isset($image['url'])) {
                                                        $thumbImageUrl = $image['url'];
                                                    } elseif (is_array($image) && isset($image['path'])) {
                                                        $thumbImageUrl = asset('storage/' . $image['path']);
                                                    } elseif (is_string($image)) {
                                                        $thumbImageUrl = asset('storage/' . $image);
                                                    }
                                                @endphp
                                                @if($thumbImageUrl)
                                                    <div class="thumbnail-item" data-image="{{ $thumbImageUrl }}">
                                                        <img src="{{ $thumbImageUrl }}" 
                                                             alt="{{ $product->name }}"
                                                             onerror="this.src='{{ asset('assets/img/product/default.png') }}'">
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Information -->
            <div class="col-xl-7 col-lg-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="product-info">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h2 class="fw-bold mb-0">{{ $product->name }}</h2>
                                <button class="favorite-btn {{ $product->is_favorite ? 'favorited' : '' }}" 
                                        data-product-id="{{ $product->id }}"
                                        title="{{ $product->is_favorite ? 'Remove from favorites' : 'Add to favorites' }}">
                                    <i class="ri-heart-{{ $product->is_favorite ? 'fill' : 'line' }}"></i>
                                </button>
                            </div>

                            @if($product->category || $product->brand)
                                <div class="mb-3">
                                    @if($product->category)
                                        <span class="badge bg-primary-transparent me-2">
                                            <i class="ri-folder-line me-1"></i>{{ $product->category->name }}
                                        </span>
                                    @endif
                                    @if($product->brand)
                                        <span class="badge bg-secondary-transparent">
                                            <i class="ri-price-tag-3-line me-1"></i>{{ $product->brand->name }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <!-- Rating -->
                            @if($product->average_rating)
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="ri-star-{{ $i <= $product->average_rating ? 'fill' : 'line' }}"></i>
                                    @endfor
                                    <span class="ms-2 text-muted">({{ $product->review_count ?? 0 }} reviews)</span>
                                </div>
                            @endif

                            <!-- Price -->
                            <div class="product-price">
                                @if($product->sale_price < $product->price)
                                    <span class="original-price">${{ number_format($product->price, 2) }}</span>
                                @endif
                                <span class="sale-price">${{ number_format($product->sale_price, 2) }}</span>
                                
                                @if($product->sale_price < $product->price)
                                    <span class="discount-badge">
                                        {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                                    </span>
                                @endif
                            </div>

                            <!-- Stock Status -->
                            <div class="stock-status">
                                @if($product->stock_quantity > 0)
                                    @if($product->stock_quantity <= 5)
                                        <span class="low-stock">
                                            <i class="ri-error-warning-line me-1"></i>Only {{ $product->stock_quantity }} left in stock
                                        </span>
                                    @else
                                        <span class="in-stock">
                                            <i class="ri-check-line me-1"></i>In Stock ({{ $product->stock_quantity }} available)
                                        </span>
                                    @endif
                                @else
                                    <span class="out-of-stock">
                                        <i class="ri-close-line me-1"></i>Out of Stock
                                    </span>
                                @endif
                            </div>

                            <!-- Description -->
                            @if($product->description)
                                <div class="mt-4">
                                    <h6 class="fw-semibold mb-3">Description</h6>
                                    <div class="text-muted">
                                        {!! nl2br(e(Str::limit($product->description, 300))) !!}
                                    </div>
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="product-actions">
                                @if($product->stock_quantity > 0)
                                    <button class="btn btn-primary btn-action" id="addToCartBtn" data-product-id="{{ $product->id }}">
                                        <i class="ri-shopping-cart-line me-2"></i>Add to Cart
                                    </button>
                                @endif
                                
                                <button class="btn btn-success btn-action" id="generateAffiliateBtn" data-product-id="{{ $product->id }}">
                                    <i class="ri-share-line me-2"></i>Get Affiliate Link
                                </button>
                                
                                <button class="btn btn-info btn-action" data-bs-toggle="modal" data-bs-target="#shareModal">
                                    <i class="ri-share-2-line me-2"></i>Share Product
                                </button>
                            </div>

                            <!-- Product Meta -->
                            <div class="product-meta">
                                <div class="meta-item">
                                    <strong>SKU:</strong> {{ $product->sku ?? 'N/A' }}
                                </div>
                                @if($product->vendor)
                                    <div class="meta-item">
                                        <strong>Vendor:</strong> {{ $product->vendor->business_name ?? $product->vendor->name }}
                                    </div>
                                @endif
                                <div class="meta-item">
                                    <strong>Added:</strong> {{ $product->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Affiliate Link Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="affiliate-section">
                    <h5 class="fw-semibold mb-3">
                        <i class="ri-link me-2"></i>Your Affiliate Link
                    </h5>
                    <p class="text-muted mb-3">Share this link and earn commission on every sale!</p>
                    <div class="affiliate-link-container">
                        <input type="text" class="affiliate-link-input" id="affiliateLinkInput" 
                               value="{{ $affiliateLink }}" readonly>
                        <button class="btn btn-primary" id="copyAffiliateLink">
                            <i class="ri-file-copy-line me-1"></i>Copy
                        </button>
                        <button class="btn btn-success" id="shareAffiliateLink">
                            <i class="ri-share-line me-1"></i>Share
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="product-tabs">
                            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" 
                                            data-bs-target="#description" type="button" role="tab">
                                        Description
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" 
                                            data-bs-target="#specifications" type="button" role="tab">
                                        Specifications
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" 
                                            data-bs-target="#reviews" type="button" role="tab">
                                        Reviews ({{ $product->reviews->count() }})
                                    </button>
                                </li>
                            </ul>
                            
                            <div class="tab-content" id="productTabsContent">
                                <div class="tab-pane fade show active" id="description" role="tabpanel">
                                    @if($product->description)
                                        {!! nl2br(e($product->description)) !!}
                                    @else
                                        <p class="text-muted">No description available for this product.</p>
                                    @endif
                                </div>
                                
                                <div class="tab-pane fade" id="specifications" role="tabpanel">
                                    @if($product->specifications)
                                        @php
                                            $specs = is_string($product->specifications) ? json_decode($product->specifications, true) : $product->specifications;
                                        @endphp
                                        
                                        @if($specs && is_array($specs))
                                            <table class="table table-bordered">
                                                @foreach($specs as $key => $value)
                                                    <tr>
                                                        <td class="fw-semibold" style="width: 30%;">{{ ucfirst($key) }}</td>
                                                        <td>{{ $value }}</td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @else
                                            <p class="text-muted">No specifications available for this product.</p>
                                        @endif
                                    @else
                                        <p class="text-muted">No specifications available for this product.</p>
                                    @endif
                                </div>
                                
                                <div class="tab-pane fade" id="reviews" role="tabpanel">
                                    @if($product->reviews->count() > 0)
                                        @foreach($product->reviews as $review)
                                            <div class="review-item">
                                                <div class="reviewer-info">
                                                    <img src="{{ $review->user->avatar ? asset('storage/' . $review->user->avatar) : asset('images/default-avatar.png') }}" 
                                                         alt="{{ $review->user->name }}" class="reviewer-avatar">
                                                    <div>
                                                        <h6 class="mb-0">{{ $review->user->name }}</h6>
                                                        <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                                    </div>
                                                </div>
                                                <div class="review-rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="ri-star-{{ $i <= $review->rating ? 'fill' : 'line' }}"></i>
                                                    @endfor
                                                </div>
                                                <p class="mb-0">{{ $review->comment }}</p>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">No reviews yet for this product.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="row related-products">
                <div class="col-12">
                    <h4 class="fw-semibold mb-4">Related Products</h4>
                </div>
                
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                        <div class="related-product-card">
                            <div class="related-product-image">
                                <a href="{{ route('member.products.show', $relatedProduct) }}">
                                    @php
                                        $relatedLegacyImageUrl = '';
                                        
                                        // Check for complex images JSON structure first
                                        if (isset($relatedProduct->images) && $relatedProduct->images) {
                                            $relatedImages = is_string($relatedProduct->images) ? json_decode($relatedProduct->images, true) : $relatedProduct->images;
                                            if (is_array($relatedImages) && !empty($relatedImages)) {
                                                $relatedImage = $relatedImages[0]; // Get first image
                                                
                                                // Handle complex nested structure first
                                                if (is_array($relatedImage) && isset($relatedImage['sizes']['medium']['storage_url'])) {
                                                    // New complex structure - use medium size storage_url
                                                    $relatedLegacyImageUrl = $relatedImage['sizes']['medium']['storage_url'];
                                                } elseif (is_array($relatedImage) && isset($relatedImage['sizes']['original']['storage_url'])) {
                                                    // Fallback to original if medium not available
                                                    $relatedLegacyImageUrl = $relatedImage['sizes']['original']['storage_url'];
                                                } elseif (is_array($relatedImage) && isset($relatedImage['sizes']['large']['storage_url'])) {
                                                    // Fallback to large if original not available
                                                    $relatedLegacyImageUrl = $relatedImage['sizes']['large']['storage_url'];
                                                } elseif (is_array($relatedImage) && isset($relatedImage['urls']['medium'])) {
                                                    // Legacy complex URL structure - use medium size
                                                    $relatedLegacyImageUrl = $relatedImage['urls']['medium'];
                                                } elseif (is_array($relatedImage) && isset($relatedImage['urls']['original'])) {
                                                    // Legacy fallback to original if medium not available
                                                    $relatedLegacyImageUrl = $relatedImage['urls']['original'];
                                                } elseif (is_array($relatedImage) && isset($relatedImage['url']) && is_string($relatedImage['url'])) {
                                                    $relatedLegacyImageUrl = $relatedImage['url'];
                                                } elseif (is_array($relatedImage) && isset($relatedImage['path']) && is_string($relatedImage['path'])) {
                                                    $relatedLegacyImageUrl = asset('storage/' . $relatedImage['path']);
                                                } elseif (is_string($relatedImage)) {
                                                    // Simple string path
                                                    $relatedLegacyImageUrl = asset('storage/' . $relatedImage);
                                                }
                                            }
                                        }
                                        
                                        // Fallback to image accessor
                                        if (empty($relatedLegacyImageUrl)) {
                                            $relatedProductImage = $relatedProduct->image;
                                            if ($relatedProductImage && $relatedProductImage !== 'products/product1.jpg') {
                                                $relatedLegacyImageUrl = str_starts_with($relatedProductImage, 'http') ? $relatedProductImage : asset('storage/' . $relatedProductImage);
                                            } else {
                                                $relatedLegacyImageUrl = asset('assets/img/product/default.png');
                                            }
                                        }
                                    @endphp
                                    <img src="{{ $relatedLegacyImageUrl }}" 
                                         alt="{{ $relatedProduct->name }}"
                                         onerror="this.src='{{ asset('assets/img/product/default.png') }}'">
                                </a>
                            </div>
                            <div class="related-product-info">
                                <h6 class="fw-semibold mb-2">
                                    <a href="{{ route('member.products.show', $relatedProduct) }}" class="text-decoration-none">
                                        {{ Str::limit($relatedProduct->name, 40) }}
                                    </a>
                                </h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($relatedProduct->sale_price < $relatedProduct->price)
                                            <small class="text-muted text-decoration-line-through">
                                                ${{ number_format($relatedProduct->price, 2) }}
                                            </small>
                                        @endif
                                        <span class="fw-semibold text-primary">
                                            ${{ number_format($relatedProduct->sale_price, 2) }}
                                        </span>
                                    </div>
                                    <a href="{{ route('member.products.show', $relatedProduct) }}" 
                                       class="btn btn-sm btn-primary">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">Share Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row text-center">
                    <div class="col-4">
                        <button class="btn btn-primary w-100 mb-2" onclick="shareOnFacebook()">
                            <i class="ri-facebook-fill"></i><br>Facebook
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-info w-100 mb-2" onclick="shareOnTwitter()">
                            <i class="ri-twitter-fill"></i><br>Twitter
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-success w-100 mb-2" onclick="shareOnWhatsApp()">
                            <i class="ri-whatsapp-fill"></i><br>WhatsApp
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Thumbnail image switching
    $('.thumbnail-item').on('click', function() {
        const newImageSrc = $(this).data('image');
        $('#mainProductImage').attr('src', newImageSrc);
        
        $('.thumbnail-item').removeClass('active');
        $(this).addClass('active');
    });

    // Favorite functionality
    $('.favorite-btn').on('click', function() {
        const btn = $(this);
        const productId = btn.data('product-id');
        
        $.ajax({
            url: '{{ route("member.products.favorites.add") }}',
            method: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    if (response.is_favorite) {
                        btn.addClass('favorited');
                        btn.find('i').removeClass('ri-heart-line').addClass('ri-heart-fill');
                        btn.attr('title', 'Remove from favorites');
                    } else {
                        btn.removeClass('favorited');
                        btn.find('i').removeClass('ri-heart-fill').addClass('ri-heart-line');
                        btn.attr('title', 'Add to favorites');
                    }
                    showNotification('success', response.message);
                }
            },
            error: function() {
                showNotification('error', 'Failed to update favorites');
            }
        });
    });

    // Generate affiliate link
    $('#generateAffiliateBtn').on('click', function() {
        const productId = $(this).data('product-id');
        
        $.ajax({
            url: '{{ route("member.products.affiliate.link") }}',
            method: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#affiliateLinkInput').val(response.affiliate_link);
                    showNotification('success', response.message);
                    
                    // Scroll to affiliate section
                    $('html, body').animate({
                        scrollTop: $('.affiliate-section').offset().top - 100
                    }, 500);
                }
            },
            error: function() {
                showNotification('error', 'Failed to generate affiliate link');
            }
        });
    });

    // Copy affiliate link
    $('#copyAffiliateLink').on('click', function() {
        const linkInput = document.getElementById('affiliateLinkInput');
        linkInput.select();
        linkInput.setSelectionRange(0, 99999);
        
        try {
            document.execCommand('copy');
            showNotification('success', 'Affiliate link copied to clipboard!');
        } catch (err) {
            showNotification('error', 'Failed to copy link');
        }
    });

    // Add to cart functionality
    $('#addToCartBtn').on('click', function() {
        const btn = $(this);
        const productId = btn.data('product-id');
        
        btn.prop('disabled', true).html('<i class="ri-loader-4-line me-2 spinner-border spinner-border-sm"></i>Adding...');
        
        // This would need to be implemented based on your cart system
        setTimeout(function() {
            btn.prop('disabled', false).html('<i class="ri-shopping-cart-line me-2"></i>Add to Cart');
            showNotification('success', 'Product added to cart!');
        }, 1000);
    });
});

// Share functions
function shareOnFacebook() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent('{{ $product->name }}');
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${text}`, '_blank');
}

function shareOnTwitter() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent('Check out {{ $product->name }}');
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
}

function shareOnWhatsApp() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent('Check out {{ $product->name }}: ');
    window.open(`https://wa.me/?text=${text}${url}`, '_blank');
}

function showNotification(type, message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: type,
            title: message,
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    } else {
        alert(message);
    }
}
</script>
@endpush
