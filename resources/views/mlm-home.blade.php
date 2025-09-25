@extends('layouts.app')

@section('title', 'Multi-Vendor Ecommerce - Shop Quality Products')

@section('content') 
<!-- Hero Section -->
<div class="hero-wrapper">
    <div class="hero-slides owl-carousel">
        @foreach($heroBanners as $banner)
        <div class="single-hero-slide" style="background-image: url('{{ asset('assets/img/bg-img/' . $banner->image) }}')">
            <div class="slide-content h-100 d-flex align-items-center">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            <div class="hero-slides-content" data-animation="fadeInUp" data-delay="100ms">
                                <div class="line"></div>
                                <h2 data-animation="fadeInUp" data-delay="300ms">{{ $banner->title }}</h2>
                                <h1 data-animation="fadeInUp" data-delay="500ms">{{ $banner->subtitle }}</h1>
                                <p data-animation="fadeInUp" data-delay="700ms">{{ $banner->description }}</p>
                                <a href="{{ $banner->cta_link }}" class="btn suha-btn" data-animation="fadeInUp" data-delay="900ms">{{ $banner->cta_text }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Store Statistics Section -->
<div class="top-products-area py-3">
    <div class="container">
        <div class="section-heading d-flex align-items-center justify-content-between">
            <h6>Why Shop With Us</h6>
        </div>
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="card feature-card h-100">
                    <div class="card-body text-center">
                        <i class="ti ti-truck-delivery text-primary fs-2 mb-2"></i>
                        <h5>Free Shipping</h5>
                        <p class="mb-0 text-muted">On orders over ৳5000</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card feature-card h-100">
                    <div class="card-body text-center">
                        <i class="ti ti-shield-check text-success fs-2 mb-2"></i>
                        <h5>Secure Payment</h5>
                        <p class="mb-0 text-muted">100% secure transactions</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card feature-card h-100">
                    <div class="card-body text-center">
                        <i class="ti ti-refresh text-warning fs-2 mb-2"></i>
                        <h5>Easy Returns</h5>
                        <p class="mb-0 text-muted">30-day return policy</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card feature-card h-100">
                    <div class="card-body text-center">
                        <i class="ti ti-headphones text-info fs-2 mb-2"></i>
                        <h5>24/7 Support</h5>
                        <p class="mb-0 text-muted">Customer service</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Categories Section -->
<div class="product-catagories-area py-3">
    <div class="container">
        <div class="section-heading d-flex align-items-center justify-content-between">
            <h6>Product Categories</h6>
            <a class="btn btn-danger btn-sm" href="{{ route('categories.index') }}">View All</a>
        </div>
        <div class="row g-3">
            @foreach($categories as $category)
            <div class="col-6 col-md-3">
                <div class="card product-category-card h-100">
                    <div class="card-body text-center">
                        <img src="{{ asset('assets/img/core-img/' . $category->image) }}" alt="{{ $category->name }}" 
                             onerror="this.src='{{ asset('assets/img/logo.png') }}'">
                        <h6>{{ $category->name }}</h6>
                        <p class="text-muted mb-1">{{ $category->products_count }} Products</p>
                        <a href="{{ route('categories.show', $category->slug) }}" class="btn btn-outline-primary btn-sm mt-2">Shop Now</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Flash Sale Section -->
<div class="weekly-best-seller-area py-3 bg-img" style="background-image: url('{{ asset('assets/img/core-img/dot.png') }}')">
    <div class="container">
        <div class="section-heading d-flex align-items-center justify-content-between">
            <h6>Flash Sale</h6>
            <a class="btn btn-danger btn-sm" href="{{ route('flash-sale') }}">View All Deals</a>
        </div>
        <div class="row g-3">
            @if(isset($flashSaleProducts) && $flashSaleProducts->count() > 0)
                @foreach($flashSaleProducts as $product)
                <div class="col-6 col-md-4">
                    <div class="card sale-card h-100">
                        <div class="card-body">
                            <div class="sale-badge">
                                <span class="badge bg-danger">{{ $product->discount_percentage }}% OFF</span>
                            </div>
                            <a href="{{ route('products.show', $product->slug) }}">
                                <img src="{{ $product->image ? (str_contains($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : asset('assets/img/logo.png') }}" alt="{{ $product->name }}" 
                                     class="product-img">
                            </a>
                            <h6 class="mt-2">{{ $product->name }}</h6>
                            <div class="price-section">
                                <span class="sale-price text-danger fw-bold">৳{{ number_format($product->sale_price, 2) }}</span>
                                <span class="original-price text-muted text-decoration-line-through">৳{{ number_format($product->price, 2) }}</span>
                            </div>
                            <div class="countdown-timer text-center mt-2">
                                <small class="text-muted">Sale ends in: <span class="text-danger fw-bold">{{ $product->sale_end_time ?? '2 days' }}</span></small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-6 col-md-4">
                    <div class="card sale-card h-100">
                        <div class="card-body text-center">
                            <div class="sale-badge">
                                <span class="badge bg-danger">50% OFF</span>
                            </div>
                            <i class="ti ti-flame fs-2 text-danger mb-2"></i>
                            <h5 class="mt-2">Limited Time Offer</h5>
                            <p class="text-muted">Amazing deals on selected items</p>
                            <div class="price-section">
                                <span class="sale-price text-danger fw-bold">From ৳999</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="card sale-card h-100">
                        <div class="card-body text-center">
                            <div class="sale-badge">
                                <span class="badge bg-warning">30% OFF</span>
                            </div>
                            <i class="ti ti-gift fs-2 text-warning mb-2"></i>
                            <h5 class="mt-2">Special Bundle</h5>
                            <p class="text-muted">Buy 2 get 1 free on electronics</p>
                            <div class="price-section">
                                <span class="sale-price text-warning fw-bold">Bundle Deals</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="card sale-card h-100">
                        <div class="card-body text-center">
                            <div class="sale-badge">
                                <span class="badge bg-success">25% OFF</span>
                            </div>
                            <i class="ti ti-star fs-2 text-success mb-2"></i>
                            <h5 class="mt-2">Premium Collection</h5>
                            <p class="text-muted">Exclusive items at discounted prices</p>
                            <div class="price-section">
                                <span class="sale-price text-success fw-bold">Premium Range</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Top Vendors Section -->
<div class="flash-sale-area py-3">
    <div class="container">
        <div class="section-heading d-flex align-items-center justify-content-between">
            <h6>Top Vendors</h6>
            <a class="btn btn-danger btn-sm" href="{{ route('vendors.index') }}">View All Vendors</a>
        </div>
        <div class="row g-3">
            @if(isset($topVendors) && $topVendors->count() > 0)
                @foreach($topVendors as $vendor)
                <div class="col-12 col-md-4">
                    <div class="card vendor-card h-100">
                        <div class="card-body text-center">
                            @if($vendor->avatar)
                                <img src="{{ asset('storage/' . $vendor->avatar) }}" alt="{{ $vendor->shop_name ?? $vendor->name }}" 
                                     class="rounded-circle mb-3" width="80" height="80">
                            @else
                                <div class="vendor-avatar rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" 
                                     style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 1.5rem; font-weight: 600;">
                                    {{ strtoupper(substr($vendor->shop_name ?? $vendor->name, 0, 1)) }}
                                </div>
                            @endif
                            <h5>{{ $vendor->shop_name ?? $vendor->name }}</h5>
                            <span class="badge bg-primary mb-2">{{ ucfirst($vendor->status) }}</span>
                            <div class="vendor-stats">
                                <div class="row">
                                    <div class="col-6">
                                        <h6 class="text-success">{{ $vendor->products_count ?? 0 }}</h6>
                                        <small class="text-muted">Products</small>
                                    </div>
                                    <div class="col-6">
                                        <h6 class="text-info">{{ $vendor->orders_count ?? 0 }}</h6>
                                        <small class="text-muted">Orders</small>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('vendors.show', $vendor->id) }}" class="btn btn-outline-primary btn-sm mt-2">
                                View Shop
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12 col-md-4">
                    <div class="card vendor-card h-100">
                        <div class="card-body text-center">
                            <div class="vendor-avatar rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 1.5rem; font-weight: 600;">
                                TS
                            </div>
                            <h5>TechStore</h5>
                            <span class="badge bg-primary mb-2">Premium</span>
                            <div class="vendor-stats">
                                <div class="row">
                                    <div class="col-6">
                                        <h6 class="text-success">150+</h6>
                                        <small class="text-muted">Products</small>
                                    </div>
                                    <div class="col-6">
                                        <h6 class="text-info">500+</h6>
                                        <small class="text-muted">Orders</small>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted small mt-2">"Quality electronics and gadgets"</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card vendor-card h-100">
                        <div class="card-body text-center">
                            <div class="vendor-avatar rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; font-size: 1.5rem; font-weight: 600;">
                                FB
                            </div>
                            <h5>FashionBoutique</h5>
                            <span class="badge bg-success mb-2">Verified</span>
                            <div class="vendor-stats">
                                <div class="row">
                                    <div class="col-6">
                                        <h6 class="text-success">200+</h6>
                                        <small class="text-muted">Products</small>
                                    </div>
                                    <div class="col-6">
                                        <h6 class="text-info">800+</h6>
                                        <small class="text-muted">Orders</small>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted small mt-2">"Trendy fashion for everyone"</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card vendor-card h-100">
                        <div class="card-body text-center">
                            <div class="vendor-avatar rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; font-size: 1.5rem; font-weight: 600;">
                                HH
                            </div>
                            <h5>HomeHub</h5>
                            <span class="badge bg-warning mb-2">Popular</span>
                            <div class="vendor-stats">
                                <div class="row">
                                    <div class="col-6">
                                        <h6 class="text-success">120+</h6>
                                        <small class="text-muted">Products</small>
                                    </div>
                                    <div class="col-6">
                                        <h6 class="text-info">350+</h6>
                                        <small class="text-muted">Orders</small>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted small mt-2">"Home essentials and decor"</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<div class="top-products-area py-3">
    <div class="container">
        <div class="section-heading d-flex align-items-center justify-content-between">
            <h6>Featured Products</h6>
            <a class="btn btn-danger btn-sm" href="{{ route('products.featured') }}">View All</a>
        </div>
        <div class="row g-3">
            @foreach($featuredProducts as $product)
            <div class="col-6 col-md-3">
                <div class="card product-card h-100">
                    <div class="card-body">
                        <div class="product-thumbnail-side">
                            @if($product->old_price > $product->price)
                            <span class="badge bg-danger">Save ৳{{ number_format($product->old_price - $product->price, 2) }}</span>
                            @endif
                        </div>
                        <a class="product-thumbnail d-block" href="{{ route('products.show', $product->slug) }}">
                            <img src="{{ $product->image ? (str_contains($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : asset('assets/img/logo.png') }}" alt="{{ $product->name }}">
                        </a>
                        <div class="product-description">
                            <span class="product-category">{{ $product->category }}</span>
                            <a href="{{ route('products.show', $product->slug) }}">
                                <h6>{{ $product->name }}</h6>
                            </a>
                            <div class="product-price">
                                <span class="sale-price">৳{{ number_format($product->price, 2) }}</span>
                                @if($product->old_price > $product->price)
                                <span class="regular-price">৳{{ number_format($product->old_price, 2) }}</span>
                                @endif
                            </div>
                            <div class="product-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($product->rating))
                                        <i class="lni lni-star-filled text-warning"></i>
                                    @elseif($i <= ceil($product->rating))
                                        <i class="lni lni-star text-warning"></i>
                                    @else
                                        <i class="lni lni-star text-muted"></i>
                                    @endif
                                @endfor
                                <span class="ms-1">{{ $product->rating }} ({{ $product->reviews_count }})</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('cart.add', $product->id) }}" class="btn btn-success btn-sm">
                            <i class="lni lni-plus"></i> Add to Cart
                        </a>
                        <a href="{{ route('wishlist.toggle', $product->id) }}" class="btn btn-outline-danger btn-sm">
                            <i class="lni lni-heart"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Newsletter Subscription Section -->
<div class="cta-area py-5 bg-primary">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-8">
                <div class="cta-content text-white">
                    <h3>Stay Updated with Our Latest Offers!</h3>
                    <p class="mb-0">Subscribe to our newsletter and never miss out on amazing deals and new product launches.</p>
                </div>
            </div>
            <div class="col-12 col-md-4 text-center">
                <div class="newsletter-form">
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="d-flex">
                        @csrf
                        <input type="email" name="email" class="form-control me-2" placeholder="Enter your email" required>
                        <button type="submit" class="btn btn-light">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.feature-card, .sale-card, .vendor-card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.feature-card:hover, .sale-card:hover, .vendor-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.product-category-card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.product-category-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.product-category-card img {
    width: 60px;
    height: 60px;
    object-fit: contain;
    margin-bottom: 15px;
}

.sale-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 1;
}

.product-img {
    width: 100%;
    height: 150px;
    object-fit: contain;
    border-radius: 8px;
}

.price-section {
    margin: 10px 0;
}

.vendor-stats {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px;
    margin: 15px 0;
}

.vendor-avatar {
    border: 3px solid rgba(255,255,255,0.2);
}

.cta-area {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.newsletter-form .form-control {
    border: none;
    border-radius: 25px 0 0 25px;
}

.newsletter-form .btn {
    border-radius: 0 25px 25px 0;
    min-width: 100px;
}

.countdown-timer {
    background: rgba(220, 53, 69, 0.1);
    padding: 5px;
    border-radius: 5px;
}
</style>
@endpush
