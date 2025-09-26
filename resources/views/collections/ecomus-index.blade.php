@extends('layouts.ecomus')

@section('title', 'Collections - ' . config('app.name'))
@section('description', 'Explore our curated collections with modern design and seamless shopping experience')

@section('styles')
<!-- Ecomus Icon Fonts -->
<link rel="stylesheet" href="{{ asset('assets/ecomus/fonts/font-icons.css') }}">
<!-- FontAwesome for backup icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Ecomus-inspired Collections Styles */
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

/* Collection Grid Layout */
.tf-grid-layout {
    display: grid;
    gap: 30px;
    margin-top: 40px;
}

.grid-3 {
    grid-template-columns: repeat(3, 1fr);
}

@media (max-width: 1200px) {
    .grid-3 {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .grid-3 {
        grid-template-columns: 1fr;
    }
    
    .tf-page-title .heading {
        font-size: 2.2rem;
    }
}

/* Collection Card Styles */
.collection-item {
    position: relative;
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    height: 400px;
    display: flex;
    flex-direction: column;
}

.collection-item:hover {
    transform: translateY(-12px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.15);
}

.collection-item:hover .img-style {
    transform: scale(1.08);
}

.collection-item:hover .collection-content {
    background: rgba(52, 152, 219, 0.95);
}

.collection-item:hover .collection-content h5,
.collection-item:hover .collection-content p {
    color: #fff;
}

.collection-item:hover .tf-btn {
    background: #fff;
    color: #3498db;
    border-color: #fff;
}

.img-style {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: all 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.collection-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 25px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    transition: all 0.4s ease;
}

.collection-content h5 {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 8px;
    transition: color 0.3s ease;
}

.collection-content p {
    color: #7f8c8d;
    margin-bottom: 15px;
    font-size: 0.95rem;
    line-height: 1.4;
    transition: color 0.3s ease;
}

.product-count {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #ecf0f1;
    color: #2c3e50;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 15px;
}

.tf-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: #3498db;
    color: #fff;
    border: 2px solid #3498db;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.tf-btn:hover {
    background: #2980b9;
    border-color: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
}

/* Featured Collections Section */
.tf-section-2 {
    background: #f8f9fa;
    padding: 80px 0;
    margin-top: 60px;
}

.tf-heading-section {
    text-align: center;
    margin-bottom: 50px;
}

.tf-heading-section .heading {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.tf-heading-section .sub-heading {
    font-size: 1.1rem;
    color: #7f8c8d;
    font-weight: 400;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Stats Cards */
.stats-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 50px;
    padding: 30px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}

.stat-card {
    text-align: center;
    padding: 20px 15px;
    border-right: 1px solid #ecf0f1;
}

.stat-card:last-child {
    border-right: none;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #3498db;
    margin-bottom: 8px;
}

.stat-label {
    font-size: 0.95rem;
    color: #7f8c8d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

@media (max-width: 768px) {
    .stats-container {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .stat-card {
        border-right: none;
        border-bottom: 1px solid #ecf0f1;
        margin-bottom: 10px;
    }
    
    .stat-card:nth-child(even) {
        border-right: 1px solid #ecf0f1;
    }
    
    .stat-card:nth-last-child(-n+2) {
        border-bottom: none;
        margin-bottom: 0;
    }
}

/* Empty State */
.empty-collections {
    text-align: center;
    padding: 80px 20px;
    color: #7f8c8d;
}

.empty-collections-icon {
    font-size: 4rem;
    margin-bottom: 24px;
    color: #bbb;
}

.empty-collections h3 {
    font-size: 1.5rem;
    margin-bottom: 16px;
    color: #2c3e50;
}

.empty-collections p {
    font-size: 1.1rem;
    margin-bottom: 32px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

/* Loading Animation */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.collection-skeleton {
    background: #f8f9fa;
    border-radius: 20px;
    overflow: hidden;
    height: 400px;
    animation: pulse 2s infinite;
}
</style>
@endsection

@section('content')
<!-- Page Title -->
<div class="tf-page-title">
    <div class="container">
        <h1 class="heading">Our Collections</h1>
        <p class="text-2">Discover our curated collections crafted with care</p>
    </div>
</div>

<!-- Collections Section -->
<section class="flat-spacing-2">
    <div class="container">
        <!-- Collections Stats -->
        @if($collections->count() > 0)
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number">{{ $collections->count() }}</div>
                <div class="stat-label">Collections</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $collections->sum('products_count') }}</div>
                <div class="stat-label">Total Products</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $collections->where('is_featured', true)->count() }}</div>
                <div class="stat-label">Featured</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $collections->avg('products_count') ? number_format($collections->avg('products_count'), 1) : '0' }}</div>
                <div class="stat-label">Avg Products</div>
            </div>
        </div>
        @endif

        <!-- Collections Grid -->
        @if($collections->count() > 0)
            <div class="tf-grid-layout grid-3">
                @foreach($collections as $collection)
                <div class="collection-item">
                    @php
                        // Handle collection image using same system as products
                        $collectionImageUrl = asset('assets/ecomus/images/collections/collection-1.jpg'); // Default
                        
                        if ($collection->image) {
                            if (str_starts_with($collection->image, 'http')) {
                                $collectionImageUrl = $collection->image;
                            } else {
                                $collectionImageUrl = asset('storage/' . $collection->image);
                            }
                        }
                    @endphp
                    
                    <img class="lazyload img-style" 
                         data-src="{{ $collectionImageUrl }}" 
                         src="{{ $collectionImageUrl }}" 
                         alt="{{ $collection->name }}"
                         loading="lazy"
                         onerror="this.src='{{ asset('assets/ecomus/images/collections/collection-1.jpg') }}'; this.onerror=null;">
                    
                    <div class="collection-content">
                        <div class="product-count">
                            <i class="icon-bag"></i>
                            {{ $collection->products_count }} {{ Str::plural('Product', $collection->products_count) }}
                        </div>
                        <h5>{{ $collection->name }}</h5>
                        <p>{{ Str::limit($collection->description ?? 'Explore our curated selection of premium products.', 80) }}</p>
                        <a href="{{ route('collections.show', $collection->slug) }}" class="tf-btn">
                            <span>View Collection</span>
                            <i class="icon icon-arrow1-top-left"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-collections">
                <div class="empty-collections-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h3>No Collections Available</h3>
                <p>We're working on curating amazing collections for you. Check back soon!</p>
                <a href="{{ route('shop.grid') }}" class="tf-btn">
                    <i class="fas fa-shopping-bag me-2"></i>Browse All Products
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Featured Products Section -->
@if($featuredProducts && $featuredProducts->count() > 0)
<section class="tf-section-2">
    <div class="container">
        <div class="tf-heading-section">
            <h2 class="heading">Featured Products</h2>
            <p class="sub-heading">Handpicked products from our most popular collections</p>
        </div>
        
        <div class="tf-grid-layout" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px;">
            @foreach($featuredProducts as $product)
            <div class="card-product" style="background: #fff; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: all 0.3s ease;">
                <div style="position: relative; overflow: hidden; height: 250px;">
                    @php
                        // Use same image handling as shop grid
                        $imageUrl = '';
                        
                        if (isset($product->images) && $product->images) {
                            $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                            if (is_array($images) && !empty($images)) {
                                $image = $images[0];
                                if (is_array($image) && isset($image['sizes']['medium']['storage_url'])) {
                                    $imageUrl = $image['sizes']['medium']['storage_url'];
                                } elseif (is_array($image) && isset($image['url']) && is_string($image['url'])) {
                                    $imageUrl = $image['url'];
                                } elseif (is_string($image)) {
                                    $imageUrl = asset('storage/' . $image);
                                }
                            }
                        }
                        
                        if (empty($imageUrl)) {
                            $productImage = $product->image;
                            if ($productImage && $productImage !== 'products/product1.jpg') {
                                $imageUrl = str_starts_with($productImage, 'http') ? $productImage : asset('storage/' . $productImage);
                            } else {
                                $imageUrl = asset('assets/ecomus/images/products/default-product.jpg');
                            }
                        }
                    @endphp
                    
                    <a href="{{ route('products.show', $product->slug) }}">
                        <img style="width: 100%; height: 100%; object-fit: cover; transition: all 0.3s ease;" 
                             src="{{ $imageUrl }}" 
                             alt="{{ $product->name }}"
                             onerror="this.src='{{ asset('assets/ecomus/images/products/default-product.jpg') }}'; this.onerror=null;">
                    </a>
                </div>
                
                <div style="padding: 20px;">
                    <a href="{{ route('products.show', $product->slug) }}" style="font-size: 1rem; font-weight: 600; color: #2c3e50; text-decoration: none; display: block; margin-bottom: 8px;">
                        {{ Str::limit($product->name, 50) }}
                    </a>
                    <div style="font-size: 1.1rem; font-weight: 700; color: #e74c3c;">
                        {{ formatCurrency($product->price) }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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

    // Collection card hover effects
    const collectionItems = document.querySelectorAll('.collection-item');
    collectionItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-12px)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Animate stats on scroll
    const animateStats = () => {
        const statNumbers = document.querySelectorAll('.stat-number');
        statNumbers.forEach(stat => {
            const finalNumber = parseInt(stat.textContent);
            let currentNumber = 0;
            const increment = finalNumber / 50;
            const timer = setInterval(() => {
                currentNumber += increment;
                if (currentNumber >= finalNumber) {
                    stat.textContent = finalNumber;
                    clearInterval(timer);
                } else {
                    stat.textContent = Math.floor(currentNumber);
                }
            }, 30);
        });
    };

    // Trigger animation when stats section is visible
    const statsContainer = document.querySelector('.stats-container');
    if (statsContainer && 'IntersectionObserver' in window) {
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateStats();
                    statsObserver.unobserve(entry.target);
                }
            });
        });
        statsObserver.observe(statsContainer);
    }
});
</script>
@endsection