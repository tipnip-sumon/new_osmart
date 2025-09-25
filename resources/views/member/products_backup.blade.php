@extends('member.layouts.app')

@section('title', 'Products')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Products</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Products</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Product Statistics -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-md bg-primary-transparent">
                                <i class="fe fe-shopping-bag fs-18"></i>
                            </div>
                            <div class="ms-3 flex-fill">
                                <h6 class="fw-semibold mb-0">{{ $products->count() }}</h6>
                                <span class="text-muted">Total Products</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-md bg-success-transparent">
                                <i class="fe fe-trending-up fs-18"></i>
                            </div>
                            <div class="ms-3 flex-fill">
                                <h6 class="fw-semibold mb-0">${{ number_format($memberCommission ?? 2500, 2) }}</h6>
                                <span class="text-muted">Product Commissions</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-md bg-warning-transparent">
                                <i class="fe fe-heart fs-18"></i>
                            </div>
                            <div class="ms-3 flex-fill">
                                <h6 class="fw-semibold mb-0">{{ $favoriteProducts ?? 8 }}</h6>
                                <span class="text-muted">Favorite Products</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-md bg-info-transparent">
                                <i class="fe fe-share-2 fs-18"></i>
                            </div>
                            <div class="ms-3 flex-fill">
                                <h6 class="fw-semibold mb-0">{{ $sharedProducts ?? 15 }}</h6>
                                <span class="text-muted">Products Shared</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Products Grid -->
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="fe fe-shopping-bag me-2"></i>Product Catalog
                        </div>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" id="categoryFilter" style="width: auto;">
                                <option value="">All Categories</option>
                                <option value="electronics">Electronics</option>
                                <option value="fashion">Fashion</option>
                                <option value="home">Home & Garden</option>
                                <option value="health">Health & Beauty</option>
                                <option value="sports">Sports</option>
                            </select>
                            <select class="form-select form-select-sm" id="sortBy" style="width: auto;">
                                <option value="name">Sort by Name</option>
                                <option value="price">Sort by Price</option>
                                <option value="commission">Sort by Commission</option>
                                <option value="popularity">Sort by Popularity</option>
                            </select>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary active" id="gridView">
                                    <i class="fe fe-grid"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary" id="listView">
                                    <i class="fe fe-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($products->count() > 0)
                            <div class="products-grid" id="productsContainer">
                                @foreach($products as $product)
                                <div class="product-card">
                                    <div class="product-image">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                        @else
                                            <div class="no-image">
                                                <i class="fe fe-image"></i>
                                            </div>
                                        @endif
                                        <div class="product-actions">
                                            <button class="btn btn-sm btn-light" onclick="addToFavorites({{ $product->id }})">
                                                <i class="fe fe-heart"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light" onclick="shareProduct({{ $product->id }})">
                                                <i class="fe fe-share-2"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light" onclick="viewProduct({{ $product->id }})">
                                                <i class="fe fe-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="product-info">
                                        <h6 class="product-name">{{ $product->name }}</h6>
                                        <p class="product-description">{{ Str::limit($product->description ?? 'High-quality product with excellent features.', 60) }}</p>
                                        <div class="product-pricing">
                                            <span class="price">${{ number_format($product->price, 2) }}</span>
                                            <span class="commission">{{ $product->commission_rate ?? 10 }}% Commission</span>
                                        </div>
                                        <div class="product-rating">
                                            <div class="stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fe fe-star {{ $i <= ($product->rating ?? 4) ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                            </div>
                                            <span class="rating-text">({{ $product->reviews_count ?? rand(10, 100) }} reviews)</span>
                                        </div>
                                        <div class="product-footer">
                                            <button class="btn btn-primary btn-sm" onclick="getAffiliateLink({{ $product->id }})">
                                                <i class="fe fe-link me-1"></i>Get Link
                                            </button>
                                            <button class="btn btn-success btn-sm" onclick="promoteProduct({{ $product->id }})">
                                                <i class="fe fe-megaphone me-1"></i>Promote
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
                                <!-- Sample products if empty -->
                                @if($products->count() < 6)
                                    @for($i = 1; $i <= (6 - $products->count()); $i++)
                                    <div class="product-card">
                                        <div class="product-image">
                                            <div class="no-image">
                                                <i class="fe fe-image"></i>
                                            </div>
                                            <div class="product-actions">
                                                <button class="btn btn-sm btn-light" onclick="addToFavorites({{ 100 + $i }})">
                                                    <i class="fe fe-heart"></i>
                                                </button>
                                                <button class="btn btn-sm btn-light" onclick="shareProduct({{ 100 + $i }})">
                                                    <i class="fe fe-share-2"></i>
                                                </button>
                                                <button class="btn btn-sm btn-light" onclick="viewProduct({{ 100 + $i }})">
                                                    <i class="fe fe-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="product-info">
                                            <h6 class="product-name">Sample Product {{ $i }}</h6>
                                            <p class="product-description">High-quality product with excellent features and great value for money.</p>
                                            <div class="product-pricing">
                                                <span class="price">${{ number_format(rand(20, 500), 2) }}</span>
                                                <span class="commission">{{ rand(5, 20) }}% Commission</span>
                                            </div>
                                            <div class="product-rating">
                                                <div class="stars">
                                                    @for($j = 1; $j <= 5; $j++)
                                                        <i class="fe fe-star {{ $j <= rand(3, 5) ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                                <span class="rating-text">({{ rand(10, 100) }} reviews)</span>
                                            </div>
                                            <div class="product-footer">
                                                <button class="btn btn-primary btn-sm" onclick="getAffiliateLink({{ 100 + $i }})">
                                                    <i class="fe fe-link me-1"></i>Get Link
                                                </button>
                                                <button class="btn btn-success btn-sm" onclick="promoteProduct({{ 100 + $i }})">
                                                    <i class="fe fe-megaphone me-1"></i>Promote
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endfor
                                @endif
                            </div>
                            
                            <!-- Pagination -->
                            @if(method_exists($products, 'links'))
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $products->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <div class="avatar avatar-xl avatar-rounded bg-light mb-3">
                                    <i class="fe fe-shopping-bag fs-24 text-muted"></i>
                                </div>
                                <h6 class="fw-semibold mb-1">No Products Available</h6>
                                <p class="text-muted mb-3">Products will be available soon</p>
                                <button class="btn btn-primary" onclick="refreshProducts()">
                                    <i class="fe fe-refresh-cw me-1"></i>Refresh
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4">
                <!-- Top Performing Products -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-trending-up me-2"></i>Top Performing
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="top-products">
                            <div class="top-product-item">
                                <div class="product-rank">#1</div>
                                <div class="product-details">
                                    <h6 class="mb-1">Wireless Headphones</h6>
                                    <p class="text-muted mb-1">Electronics</p>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-success">$89.99</span>
                                        <span class="badge bg-success-transparent">15% Commission</span>
                                    </div>
                                </div>
                            </div>
                            <div class="top-product-item">
                                <div class="product-rank">#2</div>
                                <div class="product-details">
                                    <h6 class="mb-1">Smart Watch</h6>
                                    <p class="text-muted mb-1">Electronics</p>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-success">$199.99</span>
                                        <span class="badge bg-success-transparent">12% Commission</span>
                                    </div>
                                </div>
                            </div>
                            <div class="top-product-item">
                                <div class="product-rank">#3</div>
                                <div class="product-details">
                                    <h6 class="mb-1">Fitness Tracker</h6>
                                    <p class="text-muted mb-1">Sports</p>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-success">$79.99</span>
                                        <span class="badge bg-success-transparent">18% Commission</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-activity me-2"></i>Recent Activity
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon bg-primary-transparent">
                                    <i class="fe fe-share-2 text-primary"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="mb-1">Shared "Wireless Headphones"</p>
                                    <small class="text-muted">2 hours ago</small>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon bg-success-transparent">
                                    <i class="fe fe-heart text-success"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="mb-1">Added "Smart Watch" to favorites</p>
                                    <small class="text-muted">1 day ago</small>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon bg-warning-transparent">
                                    <i class="fe fe-link text-warning"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="mb-1">Generated affiliate link for "Fitness Tracker"</p>
                                    <small class="text-muted">2 days ago</small>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon bg-info-transparent">
                                    <i class="fe fe-megaphone text-info"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="mb-1">Promoted "Smartphone Case"</p>
                                    <small class="text-muted">3 days ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Marketing Tools -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-tool me-2"></i>Marketing Tools
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="marketing-tools">
                            <button class="btn btn-primary btn-block mb-2" onclick="generateCatalog()">
                                <i class="fe fe-file-text me-2"></i>Generate Product Catalog
                            </button>
                            <button class="btn btn-success btn-block mb-2" onclick="createPromotion()">
                                <i class="fe fe-megaphone me-2"></i>Create Promotion
                            </button>
                            <button class="btn btn-info btn-block mb-2" onclick="bulkShare()">
                                <i class="fe fe-share-2 me-2"></i>Bulk Share Products
                            </button>
                            <button class="btn btn-warning btn-block" onclick="downloadAssets()">
                                <i class="fe fe-download me-2"></i>Download Assets
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.products-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.product-card {
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    background: white;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.product-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    width: 100%;
    height: 100%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    color: #dee2e6;
}

.product-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .product-actions {
    opacity: 1;
}

.product-info {
    padding: 20px;
}

.product-name {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
    line-height: 1.3;
}

.product-description {
    font-size: 14px;
    color: #6c757d;
    margin-bottom: 12px;
    line-height: 1.4;
}

.product-pricing {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.price {
    font-size: 18px;
    font-weight: 600;
    color: #28a745;
}

.commission {
    font-size: 12px;
    background: #e3f2fd;
    color: #1976d2;
    padding: 2px 8px;
    border-radius: 12px;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 15px;
}

.stars {
    display: flex;
    gap: 2px;
}

.rating-text {
    font-size: 12px;
    color: #6c757d;
}

.product-footer {
    display: flex;
    gap: 8px;
}

.top-product-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #f1f3f4;
}

.top-product-item:last-child {
    border-bottom: none;
}

.product-rank {
    width: 30px;
    height: 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 15px;
}

.activity-icon {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.marketing-tools .btn-block {
    width: 100%;
    text-align: left;
}

@media (max-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
    }
    
    .product-footer {
        flex-direction: column;
    }
}
</style>
@endpush

@push('scripts')
<script>
function addToFavorites(productId) {
    Swal.fire({
        title: 'Added to Favorites!',
        text: 'Product has been added to your favorites list.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
}

function shareProduct(productId) {
    Swal.fire({
        title: 'Share Product',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Share via:</label>
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-primary btn-sm" onclick="shareVia('facebook')">
                            <i class="fe fe-facebook me-1"></i>Facebook
                        </button>
                        <button class="btn btn-info btn-sm" onclick="shareVia('twitter')">
                            <i class="fe fe-twitter me-1"></i>Twitter
                        </button>
                        <button class="btn btn-success btn-sm" onclick="shareVia('whatsapp')">
                            <i class="fe fe-message-circle me-1"></i>WhatsApp
                        </button>
                        <button class="btn btn-secondary btn-sm" onclick="copyLink()">
                            <i class="fe fe-copy me-1"></i>Copy Link
                        </button>
                    </div>
                </div>
            </div>
        `,
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonText: 'Close'
    });
}

function viewProduct(productId) {
    Swal.fire({
        title: 'Product Details',
        html: `
            <div class="text-center">
                <div class="avatar avatar-lg bg-primary-transparent mb-3">
                    <i class="fe fe-eye fs-24"></i>
                </div>
                <p>Detailed product information would be displayed here.</p>
                <p class="text-muted small">This would typically open a product detail modal or page.</p>
            </div>
        `,
        confirmButtonText: 'View Full Details'
    });
}

function getAffiliateLink(productId) {
    const affiliateLink = `https://example.com/products/${productId}?ref=${Math.random().toString(36).substr(2, 9)}`;
    
    Swal.fire({
        title: 'Your Affiliate Link',
        html: `
            <div class="text-start">
                <label class="form-label">Affiliate Link:</label>
                <div class="input-group">
                    <input type="text" class="form-control" value="${affiliateLink}" id="affiliateLink" readonly>
                    <button class="btn btn-primary" onclick="copyAffiliateLink()">
                        <i class="fe fe-copy"></i>
                    </button>
                </div>
                <small class="text-muted">Share this link to earn commission on sales!</small>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Copy Link',
        cancelButtonText: 'Close'
    }).then((result) => {
        if (result.isConfirmed) {
            copyAffiliateLink();
        }
    });
}

function promoteProduct(productId) {
    Swal.fire({
        title: 'Promote Product',
        html: `
            <div class="text-start">
                <p>Choose how you'd like to promote this product:</p>
                <div class="d-flex flex-column gap-2">
                    <button class="btn btn-primary btn-sm" onclick="createSocialPost()">
                        <i class="fe fe-share-2 me-1"></i>Create Social Media Post
                    </button>
                    <button class="btn btn-success btn-sm" onclick="sendEmail()">
                        <i class="fe fe-mail me-1"></i>Send Email Campaign
                    </button>
                    <button class="btn btn-info btn-sm" onclick="generateBanner()">
                        <i class="fe fe-image me-1"></i>Generate Banner Ad
                    </button>
                    <button class="btn btn-warning btn-sm" onclick="createFlyer()">
                        <i class="fe fe-file-text me-1"></i>Create Product Flyer
                    </button>
                </div>
            </div>
        `,
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonText: 'Close'
    });
}

function copyAffiliateLink() {
    const linkInput = document.getElementById('affiliateLink');
    linkInput.select();
    document.execCommand('copy');
    
    Swal.fire({
        title: 'Link Copied!',
        text: 'Affiliate link has been copied to clipboard.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
}

function generateCatalog() {
    Swal.fire({
        title: 'Generate Product Catalog',
        text: 'Creating a downloadable product catalog with your affiliate links...',
        icon: 'info',
        timer: 3000,
        showConfirmButton: false
    });
}

function createPromotion() {
    Swal.fire({
        title: 'Create Promotion',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Promotion Type:</label>
                    <select class="form-select">
                        <option>Discount Campaign</option>
                        <option>Bundle Offer</option>
                        <option>Flash Sale</option>
                        <option>Limited Time Offer</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Duration:</label>
                    <select class="form-select">
                        <option>1 Week</option>
                        <option>2 Weeks</option>
                        <option>1 Month</option>
                        <option>Custom</option>
                    </select>
                </div>
            </div>
        `,
        confirmButtonText: 'Create Promotion',
        showCancelButton: true
    });
}

function bulkShare() {
    Swal.fire({
        title: 'Bulk Share Products',
        text: 'This will generate sharing content for all your selected products.',
        icon: 'info',
        confirmButtonText: 'Generate Content',
        showCancelButton: true
    });
}

function downloadAssets() {
    Swal.fire({
        title: 'Download Marketing Assets',
        text: 'Downloading product images, banners, and promotional materials...',
        icon: 'info',
        timer: 3000,
        showConfirmButton: false
    });
}

// View toggle functionality
document.getElementById('gridView').addEventListener('click', function() {
    document.getElementById('productsContainer').className = 'products-grid';
    this.classList.add('active');
    document.getElementById('listView').classList.remove('active');
});

document.getElementById('listView').addEventListener('click', function() {
    document.getElementById('productsContainer').className = 'products-list';
    this.classList.add('active');
    document.getElementById('gridView').classList.remove('active');
});

// Filter functionality
document.getElementById('categoryFilter').addEventListener('change', function() {
    const category = this.value;
    console.log('Filtering by category:', category);
    // Implement filter logic here
});

document.getElementById('sortBy').addEventListener('change', function() {
    const sortBy = this.value;
    console.log('Sorting by:', sortBy);
    // Implement sort logic here
});

function refreshProducts() {
    window.location.reload();
}
</script>
@endpush
