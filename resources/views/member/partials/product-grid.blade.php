{{-- Product Grid Partial --}}
<div class="row" id="productsGrid">
    @forelse($products as $product)
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
            <div class="product-card" data-product-id="{{ $product->id }}">
                <!-- Product Image -->
                <div class="product-image-container">
                    <a href="{{ route('member.products.show', $product) }}">
                        @php
                            $legacyImageUrl = '';
                            
                            // Check for complex images JSON structure first
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
                                    $legacyImageUrl = asset('assets/img/product/default.png');
                                }
                            }
                        @endphp
                        <img src="{{ $legacyImageUrl }}" 
                             alt="{{ $product->name }}" 
                             class="product-image"
                             onerror="this.src='{{ asset('assets/img/product/default.png') }}'">
                    </a>
                    
                    <!-- Discount Badge -->
                    @if($product->sale_price < $product->price)
                        <div class="discount-badge">
                            {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                        </div>
                    @endif
                    
                    <!-- Favorite Button -->
                    <button class="favorite-btn {{ in_array($product->id, $favoriteProductIds ?? []) ? 'active' : '' }}" 
                            data-product-id="{{ $product->id }}"
                            title="{{ in_array($product->id, $favoriteProductIds ?? []) ? 'Remove from favorites' : 'Add to favorites' }}">
                        <i class="{{ in_array($product->id, $favoriteProductIds ?? []) ? 'ri-heart-fill' : 'ri-heart-line' }}"></i>
                    </button>
                    
                    <!-- Quick Actions -->
                    <div class="product-overlay">
                        <div class="product-actions">
                            <a href="{{ route('member.products.show', $product) }}" 
                               class="btn btn-primary btn-sm" 
                               title="View Details">
                                <i class="ri-eye-line"></i>
                            </a>
                            <button class="btn btn-success btn-sm generate-affiliate" 
                                    data-product-id="{{ $product->id }}"
                                    title="Get Affiliate Link">
                                <i class="ri-share-line"></i>
                            </button>
                            @if($product->stock_quantity > 0)
                                <button class="btn btn-warning btn-sm add-to-cart" 
                                        data-product-id="{{ $product->id }}"
                                        title="Add to Cart">
                                    <i class="ri-shopping-cart-line"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="product-info">
                    <!-- Product Title -->
                    <h6 class="product-title">
                        <a href="{{ route('member.products.show', $product) }}" class="text-decoration-none">
                            {{ Str::limit($product->name, 50) }}
                        </a>
                    </h6>

                    <!-- Product Meta -->
                    <div class="product-meta">
                        @if($product->category)
                            <span class="product-category">
                                <i class="ri-folder-line me-1"></i>{{ $product->category->name }}
                            </span>
                        @endif
                        @if($product->brand)
                            <span class="product-brand">
                                <i class="ri-price-tag-3-line me-1"></i>{{ $product->brand->name }}
                            </span>
                        @endif
                    </div>

                    <!-- Rating -->
                    @if($product->average_rating)
                        <div class="product-rating">
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="ri-star-{{ $i <= $product->average_rating ? 'fill' : 'line' }}"></i>
                                @endfor
                            </div>
                            <span class="rating-text">({{ $product->review_count ?? 0 }})</span>
                        </div>
                    @endif

                    <!-- Price -->
                    <div class="product-price">
                        @if($product->sale_price < $product->price)
                            <span class="original-price">${{ number_format($product->price, 2) }}</span>
                        @endif
                        <span class="sale-price">${{ number_format($product->sale_price, 2) }}</span>
                    </div>

                    <!-- Stock Status -->
                    <div class="stock-status">
                        @if($product->stock_quantity > 0)
                            @if($product->stock_quantity <= 5)
                                <span class="badge bg-warning text-dark">
                                    <i class="ri-error-warning-line me-1"></i>Low Stock
                                </span>
                            @else
                                <span class="badge bg-success">
                                    <i class="ri-check-line me-1"></i>In Stock
                                </span>
                            @endif
                        @else
                            <span class="badge bg-danger">
                                <i class="ri-close-line me-1"></i>Out of Stock
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <div class="empty-state">
                    <img src="{{ asset('assets/img/product/default.png') }}" 
                         alt="No products found" 
                         class="empty-image mb-3"
                         style="max-width: 200px; opacity: 0.7;"
                         onerror="this.style.display='none'">
                    <h5 class="text-muted">No Products Found</h5>
                    <p class="text-muted">
                        @if(request()->has('search') || request()->has('category') || request()->has('brand'))
                            Try adjusting your filters or search terms.
                        @else
                            There are no products available at the moment.
                        @endif
                    </p>
                    @if(request()->has('search') || request()->has('category') || request()->has('brand'))
                        <a href="{{ route('member.products.index') }}" class="btn btn-primary">
                            <i class="ri-refresh-line me-2"></i>Clear Filters
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
    <div class="pagination-wrapper mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="pagination-info">
                <span class="text-muted">
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} 
                    of {{ $products->total() }} results
                </span>
            </div>
            <div class="pagination-links">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endif

<style>
.product-card {
    border: 1px solid #eee;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    background: white;
    position: relative;
}

.product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.product-image-container {
    position: relative;
    height: 220px;
    overflow: hidden;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.discount-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: #e74c3c;
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    z-index: 2;
}

.favorite-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ccc;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 2;
}

.favorite-btn:hover,
.favorite-btn.active {
    background: #e74c3c;
    color: white;
    transform: scale(1.1);
}

.product-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    padding: 30px 15px 15px;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.product-card:hover .product-overlay {
    transform: translateY(0);
}

.product-actions {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.product-actions .btn {
    border-radius: 50%;
    width: 36px;
    height: 36px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-info {
    padding: 20px 15px;
}

.product-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 10px;
    line-height: 1.4;
}

.product-title a {
    color: #333;
    transition: color 0.3s ease;
}

.product-title a:hover {
    color: var(--primary, #007bff);
}

.product-meta {
    margin-bottom: 10px;
}

.product-category,
.product-brand {
    display: inline-block;
    background: #f8f9fa;
    color: #6c757d;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    margin-right: 5px;
    margin-bottom: 5px;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.rating-stars {
    color: #ffc107;
    font-size: 0.9rem;
}

.rating-text {
    font-size: 0.8rem;
    color: #6c757d;
}

.product-price {
    margin-bottom: 12px;
}

.original-price {
    color: #999;
    text-decoration: line-through;
    font-size: 0.9rem;
    margin-right: 8px;
}

.sale-price {
    color: var(--primary, #007bff);
    font-size: 1.1rem;
    font-weight: 600;
}

.stock-status .badge {
    font-size: 0.75rem;
}

.empty-state {
    padding: 40px 20px;
}

.pagination-wrapper {
    border-top: 1px solid #eee;
    padding-top: 20px;
}

.pagination-info {
    font-size: 0.9rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .product-image-container {
        height: 180px;
    }
    
    .product-info {
        padding: 15px 12px;
    }
    
    .product-overlay {
        position: static;
        transform: none;
        background: transparent;
        padding: 10px 0 0;
    }
    
    .product-actions {
        justify-content: space-around;
    }
    
    .product-actions .btn {
        width: 32px;
        height: 32px;
        font-size: 0.9rem;
    }
    
    .pagination-wrapper {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
}

@media (max-width: 576px) {
    .product-card {
        margin-bottom: 20px;
    }
    
    .product-image-container {
        height: 160px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Favorite functionality
    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const icon = this.querySelector('i');
            const isFavorited = this.classList.contains('active');
            
            // Optimistic UI update
            if (isFavorited) {
                this.classList.remove('active');
                icon.classList.remove('ri-heart-fill');
                icon.classList.add('ri-heart-line');
                this.title = 'Add to favorites';
            } else {
                this.classList.add('active');
                icon.classList.remove('ri-heart-line');
                icon.classList.add('ri-heart-fill');
                this.title = 'Remove from favorites';
            }
            
            // Send AJAX request
            fetch('/member/products/favorites/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    // Revert UI if request failed
                    if (isFavorited) {
                        this.classList.add('active');
                        icon.classList.remove('ri-heart-line');
                        icon.classList.add('ri-heart-fill');
                        this.title = 'Remove from favorites';
                    } else {
                        this.classList.remove('active');
                        icon.classList.remove('ri-heart-fill');
                        icon.classList.add('ri-heart-line');
                        this.title = 'Add to favorites';
                    }
                    showNotification('error', data.message || 'Failed to update favorites');
                } else {
                    showNotification('success', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Revert UI on error
                if (isFavorited) {
                    this.classList.add('active');
                    icon.classList.remove('ri-heart-line');
                    icon.classList.add('ri-heart-fill');
                    this.title = 'Remove from favorites';
                } else {
                    this.classList.remove('active');
                    icon.classList.remove('ri-heart-fill');
                    icon.classList.add('ri-heart-line');
                    this.title = 'Add to favorites';
                }
                showNotification('error', 'Network error occurred');
            });
        });
    });
    
    // Generate affiliate link
    document.querySelectorAll('.generate-affiliate').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const originalContent = this.innerHTML;
            
            // Show loading state
            this.innerHTML = '<i class="ri-loader-4-line spinner-border spinner-border-sm"></i>';
            this.disabled = true;
            
            fetch('/member/products/affiliate/generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAffiliateModal(data.affiliate_link, data.product_name);
                    showNotification('success', 'Affiliate link generated!');
                } else {
                    showNotification('error', data.message || 'Failed to generate affiliate link');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Network error occurred');
            })
            .finally(() => {
                // Restore button state
                this.innerHTML = originalContent;
                this.disabled = false;
            });
        });
    });
    
    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const originalContent = this.innerHTML;
            
            // Show loading state
            this.innerHTML = '<i class="ri-loader-4-line spinner-border spinner-border-sm"></i>';
            this.disabled = true;
            
            // Simulate add to cart (replace with actual implementation)
            setTimeout(() => {
                showNotification('success', 'Product added to cart!');
                this.innerHTML = originalContent;
                this.disabled = false;
            }, 1000);
        });
    });
});

// Show notification function
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

// Show affiliate modal function
function showAffiliateModal(link, productName) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Affiliate Link Generated',
            html: `
                <div class="text-start">
                    <p class="mb-3"><strong>Product:</strong> ${productName}</p>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" value="${link}" id="affiliateLinkInput" readonly>
                        <button class="btn btn-primary" onclick="copyAffiliateLink()">Copy</button>
                    </div>
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-success btn-sm" onclick="shareOnSocial('facebook', '${link}', '${productName}')">
                            <i class="ri-facebook-fill me-1"></i>Facebook
                        </button>
                        <button class="btn btn-info btn-sm" onclick="shareOnSocial('twitter', '${link}', '${productName}')">
                            <i class="ri-twitter-fill me-1"></i>Twitter
                        </button>
                        <button class="btn btn-success btn-sm" onclick="shareOnSocial('whatsapp', '${link}', '${productName}')">
                            <i class="ri-whatsapp-fill me-1"></i>WhatsApp
                        </button>
                    </div>
                </div>
            `,
            showConfirmButton: false,
            width: '500px'
        });
    }
}

// Copy affiliate link function
function copyAffiliateLink() {
    const input = document.getElementById('affiliateLinkInput');
    input.select();
    input.setSelectionRange(0, 99999);
    
    try {
        document.execCommand('copy');
        showNotification('success', 'Link copied to clipboard!');
    } catch (err) {
        showNotification('error', 'Failed to copy link');
    }
}

// Social sharing function
function shareOnSocial(platform, link, productName) {
    const encodedLink = encodeURIComponent(link);
    const encodedText = encodeURIComponent(`Check out ${productName}!`);
    let url = '';
    
    switch(platform) {
        case 'facebook':
            url = `https://www.facebook.com/sharer/sharer.php?u=${encodedLink}&quote=${encodedText}`;
            break;
        case 'twitter':
            url = `https://twitter.com/intent/tweet?url=${encodedLink}&text=${encodedText}`;
            break;
        case 'whatsapp':
            url = `https://wa.me/?text=${encodedText} ${encodedLink}`;
            break;
    }
    
    if (url) {
        window.open(url, '_blank', 'width=600,height=400');
    }
}
</script>
