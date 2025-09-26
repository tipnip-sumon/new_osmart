@extends('layouts.ecomus')

@section('title', 'Shop - ' . config('app.name'))
@section('description', 'Browse our complete collection of products with advanced filters and modern shopping experience')

@section('styles')
<!-- Ecomus Icon Fonts -->
<link rel="stylesheet" href="{{ asset('assets/ecomus/fonts/font-icons.css') }}">
<!-- FontAwesome for backup icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Ecomus-inspired Shop Styles */
.tf-page-title {
    background: linear-gradient(135deg, #f8f9fa 0%, #e8ecef 100%);
    padding: 80px 0 60px;
    text-align: center;
}

.tf-page-title .heading {
    font-size: 3rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.tf-page-title .text-2 {
    font-size: 1.1rem;
    color: #7f8c8d;
    font-weight: 400;
    margin: 0;
}

.flat-spacing-2 {
    padding: 60px 0;
}

.tf-shop-control {
    display: grid;
    grid-template-columns: auto 1fr auto;
    align-items: center;
    gap: 20px;
    margin-bottom: 40px;
    padding: 20px 0;
    border-bottom: 1px solid #eee;
}

.tf-btn-filter {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 25px;
    color: #2c3e50;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.tf-btn-filter:hover {
    background: #3498db;
    color: #fff;
    border-color: #3498db;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
}

.tf-control-layout {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
    list-style: none;
    margin: 0;
    padding: 0;
    background: #f8f9fa;
    border-radius: 25px;
    padding: 5px;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
}

.tf-view-layout-switch {
    border-radius: 20px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
}

.tf-view-layout-switch.active .item {
    background: #3498db;
    color: #fff;
    box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
}

.tf-view-layout-switch .item {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: transparent;
    color: #666;
    transition: all 0.3s ease;
    border-radius: 20px;
}

.tf-view-layout-switch:hover .item {
    background: #e8ecf3;
    color: #2c3e50;
}

.tf-dropdown-sort {
    position: relative;
    cursor: pointer;
}

.btn-select {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 25px;
    font-weight: 500;
    color: #2c3e50;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    min-width: 180px;
}

.btn-select:hover {
    border-color: #3498db;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    min-width: 200px;
    z-index: 1000;
    overflow: hidden;
    margin-top: 5px;
}

.select-item {
    padding: 12px 20px;
    cursor: pointer;
    transition: all 0.2s ease;
    border-bottom: 1px solid #f8f9fa;
}

.select-item:last-child {
    border-bottom: none;
}

.select-item:hover,
.select-item.active {
    background: #3498db;
    color: #fff;
}

/* Product Grid Layout */
.tf-grid-layout {
    display: grid;
    gap: 25px;
    margin-top: 30px;
}

.tf-col-4 {
    grid-template-columns: repeat(4, 1fr);
}

.tf-col-3 {
    grid-template-columns: repeat(3, 1fr);
}

.tf-col-2 {
    grid-template-columns: repeat(2, 1fr);
}

.tf-col-5 {
    grid-template-columns: repeat(5, 1fr);
}

.tf-col-6 {
    grid-template-columns: repeat(6, 1fr);
}

@media (max-width: 1200px) {
    .tf-col-4, .tf-col-5, .tf-col-6 {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .tf-col-2, .tf-col-3, .tf-col-4, .tf-col-5, .tf-col-6 {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .tf-shop-control {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 15px;
    }
    
    .tf-page-title .heading {
        font-size: 2.2rem;
    }
}

@media (max-width: 576px) {
    .tf-col-2, .tf-col-3, .tf-col-4, .tf-col-5, .tf-col-6 {
        grid-template-columns: 1fr;
    }
    
    .tf-control-layout {
        flex-wrap: wrap;
    }
}

/* Product Card Styles */
.card-product {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.card-product:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.card-product-wrapper {
    position: relative;
    overflow: hidden;
    background: #f8f9fa;
    height: 300px;
}

.product-img {
    position: relative;
    display: block;
    height: 100%;
    overflow: hidden;
}

.img-product,
.img-hover {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.img-hover {
    position: absolute;
    top: 0;
    left: 0;
    opacity: 0;
}

.card-product:hover .img-hover {
    opacity: 1;
}

.card-product:hover .img-product {
    transform: scale(1.08);
}

.card-product-info {
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    text-decoration: none;
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.3s ease;
}

.title:hover {
    color: #3498db;
}

.price {
    font-size: 1.2rem;
    font-weight: 700;
    color: #e74c3c;
    margin-bottom: 15px;
}

.original-price {
    font-size: 1rem;
    color: #95a5a6;
    text-decoration: line-through;
    margin-right: 8px;
    font-weight: 400;
}

.list-color-product {
    display: flex;
    gap: 8px;
    list-style: none;
    margin: 0 0 15px 0;
    padding: 0;
}

.color-swatch {
    position: relative;
    cursor: pointer;
}

.swatch-value {
    display: block;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 1px #ddd;
    transition: all 0.3s ease;
}

.color-swatch:hover .swatch-value,
.color-swatch.active .swatch-value {
    transform: scale(1.2);
    box-shadow: 0 0 0 2px #3498db;
}

.size-list {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.size-item {
    padding: 6px 12px;
    border: 1px solid #ddd;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
}

.size-item:hover,
.size-item.active {
    background: #3498db;
    color: #fff;
    border-color: #3498db;
}

.list-product-btn {
    display: flex;
    gap: 10px;
    margin-top: auto;
    padding-top: 15px;
    border-top: 1px solid #f8f9fa;
}

.box-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border: 1px solid #eee;
    border-radius: 50%;
    color: #666;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
}

.box-icon:hover {
    background: #3498db;
    color: #fff;
    border-color: #3498db;
    transform: translateY(-2px);
}

.quick-add {
    background: #3498db;
    color: #fff;
    border-color: #3498db;
    flex: 1;
    width: auto;
    border-radius: 25px;
}

.quick-add:hover {
    background: #2980b9;
    border-color: #2980b9;
}

/* Product badges */
.product-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    z-index: 2;
}

.badge-sale {
    background: #e74c3c;
    color: #fff;
}

.badge-new {
    background: #27ae60;
    color: #fff;
}

.badge-hot {
    background: #f39c12;
    color: #fff;
}

/* List layout specific styles */
.list-layout {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 25px;
    align-items: start;
    border-radius: 15px;
    overflow: hidden;
}

.list-layout .card-product-wrapper {
    height: 250px;
}

.list-layout .card-product-info {
    padding: 20px 25px;
}

.list-layout .description {
    color: #7f8c8d;
    line-height: 1.6;
    margin-bottom: 15px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

@media (max-width: 768px) {
    .list-layout {
        grid-template-columns: 1fr;
    }
    
    .list-layout .card-product-wrapper {
        height: 300px;
    }
}

/* Filter control responsiveness */
@media (max-width: 768px) {
    .tf-control-layout {
        order: 2;
        margin-bottom: 20px;
    }
    
    .tf-control-filter,
    .tf-control-sorting {
        order: 1;
    }
}

/* Loading state */
.products-loading {
    display: grid;
    gap: 25px;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
}

.product-skeleton {
    background: #f8f9fa;
    border-radius: 15px;
    overflow: hidden;
    height: 400px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

/* Color swatches */
.bg_orange-3 { background-color: #ff6b35; }
.bg_dark { background-color: #2c3e50; }
.bg_white { background-color: #fff; }
.bg_brown { background-color: #8b4513; }
.bg_purple { background-color: #9b59b6; }
.bg_blue { background-color: #3498db; }
.bg_green { background-color: #27ae60; }
.bg_pink { background-color: #e91e63; }
.bg_yellow { background-color: #f1c40f; }

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 50px;
    padding-top: 30px;
    border-top: 1px solid #eee;
}

/* Empty state */
.empty-products {
    text-align: center;
    padding: 80px 20px;
    color: #7f8c8d;
}

.empty-products-icon {
    font-size: 4rem;
    margin-bottom: 24px;
    color: #bbb;
}

.empty-products h3 {
    font-size: 1.5rem;
    margin-bottom: 16px;
    color: #2c3e50;
}

.empty-products p {
    font-size: 1.1rem;
    margin-bottom: 32px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

/* Filter button responsive */
@media (max-width: 576px) {
    .tf-btn-filter .text {
        display: none;
    }
}
</style>
@endsection

@section('content')
<!-- Page Title -->
<div class="tf-page-title">
    <div class="container">
        <h1 class="heading">New Arrival</h1>
        <p class="text-2">Shop through our latest selection of Fashion</p>
    </div>
</div>

<!-- Section Product -->
<section class="flat-spacing-2">
    <div class="container">
        <!-- Shop Controls -->
        <div class="tf-shop-control grid-3 align-items-center">
            <div class="tf-control-filter">
                <a href="#filterShop" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft" class="tf-btn-filter">
                    <span class="icon icon-filter"></span>
                    <span class="text">Filter</span>
                </a>
            </div>
            
            <ul class="tf-control-layout d-flex justify-content-center">
                <li class="tf-view-layout-switch sw-layout-list list-layout" data-value-layout="list">
                    <div class="item"><span class="icon icon-list"></span></div>
                </li>
                <li class="tf-view-layout-switch sw-layout-2" data-value-layout="tf-col-2">
                    <div class="item"><span class="icon icon-grid-2"></span></div>
                </li>
                <li class="tf-view-layout-switch sw-layout-3" data-value-layout="tf-col-3">
                    <div class="item"><span class="icon icon-grid-3"></span></div>
                </li>
                <li class="tf-view-layout-switch sw-layout-4 active" data-value-layout="tf-col-4">
                    <div class="item"><span class="icon icon-grid-4"></span></div>
                </li>
                <li class="tf-view-layout-switch sw-layout-5" data-value-layout="tf-col-5">
                    <div class="item"><span class="icon icon-grid-5"></span></div>
                </li>
                <li class="tf-view-layout-switch sw-layout-6" data-value-layout="tf-col-6">
                    <div class="item"><span class="icon icon-grid-6"></span></div>
                </li>
            </ul>
            
            <div class="tf-control-sorting d-flex justify-content-end">
                <div class="tf-dropdown-sort" data-bs-toggle="dropdown">
                    <div class="btn-select">
                        <span class="text-sort-value">Featured</span>
                        <span class="icon icon-arrow-down"></span>
                    </div>
                    <div class="dropdown-menu">
                        <div class="select-item active">
                            <span class="text-value-item">Featured</span>
                        </div>
                        <div class="select-item">
                            <span class="text-value-item">Best selling</span>
                        </div>
                        <div class="select-item" data-sort-value="a-z">
                            <span class="text-value-item">Alphabetically, A-Z</span>
                        </div>
                        <div class="select-item" data-sort-value="z-a">
                            <span class="text-value-item">Alphabetically, Z-A</span>
                        </div>
                        <div class="select-item" data-sort-value="price-low-high">
                            <span class="text-value-item">Price, low to high</span>
                        </div>
                        <div class="select-item" data-sort-value="price-high-low">
                            <span class="text-value-item">Price, high to low</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="wrapper-control-shop">
            <div class="meta-filter-shop" style="margin-bottom: 20px;">
                <div class="count-text">
                    Showing <span id="products-showing">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</span> 
                    of <span id="products-total">{{ $products->total() ?? 0 }}</span> products
                </div>
            </div>
            
            @if($products->count() > 0)
                <div class="tf-grid-layout tf-col-4" id="products-container">
                    @foreach($products as $product)
                    <div class="card-product" data-product-id="{{ $product->id }}" data-availability="In stock" data-brand="{{ $product->brand->name ?? 'Default' }}">
                        <div class="card-product-wrapper">
                            <a href="{{ route('products.show', $product->slug) }}" class="product-img">
                                @php
                                    $primaryImage = $product->images->first();
                                    $hoverImage = $product->images->skip(1)->first();
                                    $imageUrl = $primaryImage ? asset('storage/' . $primaryImage->image_path) : asset('assets/ecomus/images/products/default-product.jpg');
                                    $hoverImageUrl = $hoverImage ? asset('storage/' . $hoverImage->image_path) : $imageUrl;
                                @endphp
                                
                                <img class="lazyload img-product" 
                                     data-src="{{ $imageUrl }}" 
                                     src="{{ $imageUrl }}" 
                                     alt="{{ $product->name }}"
                                     loading="lazy">
                                <img class="lazyload img-hover" 
                                     data-src="{{ $hoverImageUrl }}" 
                                     src="{{ $hoverImageUrl }}" 
                                     alt="{{ $product->name }}"
                                     loading="lazy">
                            </a>
                            
                            <!-- Product Badges -->
                            @if($product->is_featured)
                                <div class="product-badge badge-hot">Hot</div>
                            @elseif($product->created_at->gt(now()->subDays(7)))
                                <div class="product-badge badge-new">New</div>
                            @elseif($product->discount_percentage > 0)
                                <div class="product-badge badge-sale">Sale</div>
                            @endif
                        </div>
                        
                        <div class="card-product-info">
                            <a href="{{ route('products.show', $product->slug) }}" class="title link">{{ $product->name }}</a>
                            <div class="price">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <span class="original-price">${{ number_format($product->price, 2) }}</span>
                                    <span class="current-price">${{ number_format($product->sale_price, 2) }}</span>
                                @else
                                    <span class="current-price">${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            
                            <!-- Color Swatches -->
                            @if($product->variants->count() > 0)
                            <ul class="list-color-product">
                                @foreach($product->variants->unique('color')->take(4) as $variant)
                                <li class="list-color-item color-swatch {{ $loop->first ? 'active' : '' }}">
                                    <span class="swatch-value" style="background-color: {{ $variant->color_code ?? '#ccc' }}"></span>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                            
                            <!-- Size Options -->
                            @if($product->variants->whereNotNull('size')->count() > 0)
                            <div class="size-list">
                                @foreach($product->variants->pluck('size')->unique()->take(5) as $size)
                                    <span class="size-item">{{ $size }}</span>
                                @endforeach
                            </div>
                            @endif
                            
                            <div class="list-product-btn">
                                <a href="#" class="box-icon quick-add quick-add-btn" data-product-id="{{ $product->id }}">
                                    <span class="icon icon-bag"></span>
                                    <span>Quick add</span>
                                </a>
                                <a href="#" class="box-icon wishlist wishlist-btn" data-product-id="{{ $product->id }}">
                                    <span class="icon icon-heart"></span>
                                </a>
                                <a href="#" class="box-icon compare compare-btn" data-product-id="{{ $product->id }}">
                                    <span class="icon icon-compare"></span>
                                </a>
                                <a href="{{ route('products.show', $product->slug) }}" class="box-icon quickview">
                                    <span class="icon icon-view"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($products->hasPages())
                <div class="pagination-wrapper">
                    {{ $products->appends(request()->query())->links('pagination.ecomus') }}
                </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="empty-products">
                    <div class="empty-products-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <h3>No Products Found</h3>
                    <p>Sorry, we couldn't find any products matching your criteria. Try adjusting your filters or browse our categories.</p>
                    <a href="{{ route('categories.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-th-large me-2"></i>Browse Categories
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Filter Offcanvas -->
<div class="offcanvas offcanvas-start canvas-filter" id="filterShop">
    <div class="canvas-wrapper">
        <header class="canvas-header">
            <div class="filter-icon">
                <span class="icon icon-filter"></span>
                <span>Filter</span>
            </div>
            <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas"></span>
        </header>
        <div class="canvas-body">
            <div class="widget-facet">
                <!-- Add your filter options here -->
                <div class="facet-item">
                    <h6>Categories</h6>
                    <!-- Category filters would go here -->
                </div>
                <div class="facet-item">
                    <h6>Price Range</h6>
                    <!-- Price range filters would go here -->
                </div>
                <div class="facet-item">
                    <h6>Brands</h6>
                    <!-- Brand filters would go here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Layout switching functionality
    const layoutSwitchers = document.querySelectorAll('.tf-view-layout-switch');
    const productsContainer = document.getElementById('products-container');
    
    layoutSwitchers.forEach(switcher => {
        switcher.addEventListener('click', function() {
            // Remove active class from all switchers
            layoutSwitchers.forEach(s => s.classList.remove('active'));
            // Add active class to clicked switcher
            this.classList.add('active');
            
            // Get layout value
            const layoutValue = this.getAttribute('data-value-layout');
            
            // Update container classes
            productsContainer.className = 'tf-grid-layout ' + layoutValue;
            
            // Handle list layout specifically
            if (layoutValue === 'list') {
                productsContainer.querySelectorAll('.card-product').forEach(card => {
                    card.classList.add('list-layout');
                });
            } else {
                productsContainer.querySelectorAll('.card-product').forEach(card => {
                    card.classList.remove('list-layout');
                });
            }
        });
    });
    
    // Sorting functionality
    const sortDropdown = document.querySelector('.tf-dropdown-sort');
    const sortItems = document.querySelectorAll('.select-item[data-sort-value]');
    const sortValueText = document.querySelector('.text-sort-value');
    
    sortItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            sortItems.forEach(i => i.classList.remove('active'));
            // Add active class to clicked item
            this.classList.add('active');
            
            // Update displayed text
            sortValueText.textContent = this.querySelector('.text-value-item').textContent;
            
            // Get sort value and apply sorting
            const sortValue = this.getAttribute('data-sort-value');
            // Here you would implement the actual sorting logic
            console.log('Sorting by:', sortValue);
        });
    });
    
    // Quick add functionality
    document.querySelectorAll('.quick-add-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            
            // Add loading state
            this.innerHTML = '<span class="icon-loading"></span><span>Adding...</span>';
            
            // Simulate API call
            setTimeout(() => {
                this.innerHTML = '<span class="icon icon-check"></span><span>Added</span>';
                
                // Reset after 2 seconds
                setTimeout(() => {
                    this.innerHTML = '<span class="icon icon-bag"></span><span>Quick add</span>';
                }, 2000);
            }, 1000);
        });
    });
    
    // Wishlist functionality
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.toggle('active');
            
            const icon = this.querySelector('.icon');
            if (this.classList.contains('active')) {
                icon.style.color = '#e74c3c';
            } else {
                icon.style.color = '';
            }
        });
    });
    
    // Compare functionality
    document.querySelectorAll('.compare-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.toggle('active');
            console.log('Added to compare');
        });
    });
    
    // Color swatch functionality
    document.querySelectorAll('.color-swatch').forEach(swatch => {
        swatch.addEventListener('click', function() {
            // Remove active class from siblings
            this.parentNode.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('active'));
            // Add active class to clicked swatch
            this.classList.add('active');
        });
    });
    
    // Size selection functionality
    document.querySelectorAll('.size-item').forEach(size => {
        size.addEventListener('click', function() {
            // Remove active class from siblings
            this.parentNode.querySelectorAll('.size-item').forEach(s => s.classList.remove('active'));
            // Add active class to clicked size
            this.classList.add('active');
        });
    });
    
    // Lazy loading for images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.classList.remove('lazyload');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // Error handling for images
    document.querySelectorAll('.product-img img').forEach(img => {
        img.addEventListener('error', function() {
            this.src = '{{ asset("assets/ecomus/images/products/default-product.jpg") }}';
            this.alt = 'Product Image';
        });
    });
});
</script>
@endsection