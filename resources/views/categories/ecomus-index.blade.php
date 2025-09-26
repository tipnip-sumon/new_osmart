@extends('layouts.app')

@section('title', 'Shop Categories - ' . config('app.name'))

@section('styles')
<style>
/* Ecomus-inspired Category Styles */
.tf-page-title {
    background: linear-gradient(135deg, #f5f6fa 0%, #e8ecf3 100%);
    padding: 60px 0;
    margin-bottom: 0;
}

.tf-page-title .heading {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.flat-spacing-1 {
    padding: 60px 0;
}

.tf-grid-layout {
    display: grid;
    gap: 30px;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
}

@media (min-width: 768px) {
    .tf-grid-layout.tf-col-2 {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1200px) {
    .tf-grid-layout.xl-col-3 {
        grid-template-columns: repeat(3, 1fr);
    }
}

.collection-item {
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    background: #fff;
}

.collection-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.collection-item.hover-img .collection-image img {
    transition: all 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.collection-item:hover .collection-image img {
    transform: scale(1.08);
}

.collection-inner {
    position: relative;
    height: 100%;
}

.collection-image {
    position: relative;
    display: block;
    overflow: hidden;
    height: 280px;
    background: linear-gradient(45deg, #f8f9fa, #e9ecef);
}

.collection-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

.collection-image::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(0,0,0,0.2), rgba(0,0,0,0.05));
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1;
}

.collection-item:hover .collection-image::before {
    opacity: 1;
}

.collection-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.8) 100%);
    padding: 30px 20px 20px;
    z-index: 2;
}

.collection-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #fff !important;
    text-decoration: none !important;
    font-size: 1.25rem;
    font-weight: 600;
    padding: 12px 20px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 50px;
    transition: all 0.3s ease;
    text-transform: capitalize;
}

.collection-title:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-2px);
}

.collection-title i {
    font-size: 16px;
    transition: transform 0.3s ease;
}

.collection-title:hover i {
    transform: translate(-2px, -2px);
}

.product-count {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255,255,255,0.95);
    color: #2c3e50;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    backdrop-filter: blur(5px);
    z-index: 3;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.category-stats {
    background: #fff;
    padding: 40px 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    margin-bottom: 40px;
}

.stat-item {
    text-align: center;
    padding: 20px;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #3498db;
    display: block;
    margin-bottom: 8px;
}

.stat-label {
    font-size: 0.9rem;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 500;
}

.breadcrumb-section {
    background: #f8f9fa;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
}

.tf-breadcrumb {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}

.tf-breadcrumb li {
    display: flex;
    align-items: center;
}

.tf-breadcrumb li:not(:last-child)::after {
    content: '/';
    margin: 0 12px;
    color: #bbb;
}

.tf-breadcrumb a {
    color: #666;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
}

.tf-breadcrumb a:hover {
    color: #3498db;
}

.tf-breadcrumb .active {
    color: #2c3e50;
    font-weight: 600;
}

.section-header {
    text-align: center;
    margin-bottom: 50px;
}

.section-subtitle {
    color: #3498db;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    font-weight: 600;
    margin-bottom: 15px;
}

.section-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 20px;
    line-height: 1.2;
}

.section-description {
    font-size: 1.1rem;
    color: #7f8c8d;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Loading skeleton */
.collection-skeleton {
    background: #f8f9fa;
    border-radius: 15px;
    overflow: hidden;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.collection-skeleton-image {
    height: 280px;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .tf-page-title .heading {
        font-size: 2rem;
        letter-spacing: 1px;
    }
    
    .tf-grid-layout {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .section-title {
        font-size: 1.8rem;
    }
    
    .collection-image {
        height: 220px;
    }
    
    .flat-spacing-1 {
        padding: 40px 0;
    }
    
    .stat-number {
        font-size: 2rem;
    }
}

@media (max-width: 576px) {
    .collection-title {
        font-size: 1.1rem;
        padding: 10px 16px;
    }
    
    .tf-page-title {
        padding: 40px 0;
    }
    
    .tf-page-title .heading {
        font-size: 1.75rem;
    }
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 80px 20px;
    color: #7f8c8d;
}

.empty-state-icon {
    font-size: 4rem;
    margin-bottom: 24px;
    color: #bbb;
}

.empty-state h3 {
    font-size: 1.5rem;
    margin-bottom: 16px;
    color: #2c3e50;
}

.empty-state p {
    font-size: 1.1rem;
    margin-bottom: 32px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}
</style>
@endsection

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-section">
    <div class="container">
        <ul class="tf-breadcrumb">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><span class="active">Categories</span></li>
        </ul>
    </div>
</div>

<!-- Page Title -->
<div class="tf-page-title">
    <div class="container">
        <div class="section-header">
            <div class="section-subtitle">Discover Our</div>
            <h1 class="heading">Shop Categories</h1>
            <p class="section-description">
                Explore our carefully curated collection of categories, each featuring premium products 
                designed to meet your unique style and needs.
            </p>
        </div>
    </div>
</div>

<!-- Category Statistics -->
<div class="category-stats">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number">{{ $allCategories->count() }}</span>
                    <div class="stat-label">Total Categories</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number">{{ $allCategories->sum('products_count') }}</span>
                    <div class="stat-label">Total Products</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number">{{ $categories->count() }}</span>
                    <div class="stat-label">Main Categories</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number">{{ $allCategories->where('parent_id', '!=', null)->count() }}</span>
                    <div class="stat-label">Subcategories</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Categories Grid -->
<section class="flat-spacing-1">
    <div class="container">
        @if($allCategories->count() > 0)
            <div class="tf-grid-layout xl-col-3 tf-col-2">
                @foreach($allCategories as $category)
                <div class="collection-item hover-img">
                    <div class="collection-inner">
                        <a href="{{ route('categories.show', $category->slug) }}" class="collection-image img-style">
                            @php
                                $categoryImageUrl = '';
                                
                                // Handle complex image_data structure first
                                if (isset($category->image_data) && $category->image_data) {
                                    $imageData = is_string($category->image_data) ? json_decode($category->image_data, true) : $category->image_data;
                                    if (is_array($imageData)) {
                                        // Handle complex nested structure
                                        if (isset($imageData['sizes']['medium']['storage_url'])) {
                                            $categoryImageUrl = $imageData['sizes']['medium']['storage_url'];
                                        } elseif (isset($imageData['sizes']['original']['storage_url'])) {
                                            $categoryImageUrl = $imageData['sizes']['original']['storage_url'];
                                        } elseif (isset($imageData['sizes']['large']['storage_url'])) {
                                            $categoryImageUrl = $imageData['sizes']['large']['storage_url'];
                                        }
                                    }
                                }
                                
                                // Fallback to simple image field
                                if (empty($categoryImageUrl) && $category->image) {
                                    $categoryImageUrl = str_starts_with($category->image, 'http') ? 
                                        $category->image : 
                                        asset('storage/' . $category->image);
                                }
                                
                                // Final fallback to default image
                                if (empty($categoryImageUrl)) {
                                    $categoryImageUrl = asset('assets/images/default-category.jpg');
                                }
                            @endphp
                            
                            <img class="lazyload" 
                                 data-src="{{ $categoryImageUrl }}" 
                                 src="{{ $categoryImageUrl }}" 
                                 alt="{{ $category->name }}"
                                 loading="lazy">
                        </a>
                        
                        <!-- Product Count Badge -->
                        @if($category->products_count > 0)
                        <div class="product-count">
                            {{ $category->products_count }} {{ Str::plural('Product', $category->products_count) }}
                        </div>
                        @endif
                        
                        <div class="collection-content">
                            <a href="{{ route('categories.show', $category->slug) }}" 
                               class="tf-btn collection-title hover-icon">
                                <span>{{ $category->name }}</span>
                                <i class="icon icon-arrow1-top-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3>No Categories Found</h3>
                <p>We're working on adding more categories to our store. Please check back soon!</p>
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-home me-2"></i>Back to Home
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Featured Categories Section (if you want to highlight specific categories) -->
@if($categories->where('is_featured', true)->count() > 0)
<section class="flat-spacing-1" style="background: #f8f9fa;">
    <div class="container">
        <div class="section-header">
            <div class="section-subtitle">Featured</div>
            <h2 class="section-title">Popular Categories</h2>
            <p class="section-description">
                Discover our most popular categories loved by thousands of customers worldwide.
            </p>
        </div>
        
        <div class="tf-grid-layout xl-col-3 tf-col-2">
            @foreach($categories->where('is_featured', true)->take(6) as $category)
            <div class="collection-item hover-img">
                <div class="collection-inner">
                    <a href="{{ route('categories.show', $category->slug) }}" class="collection-image img-style">
                        @php
                            $categoryImageUrl = '';
                            
                            if (isset($category->image_data) && $category->image_data) {
                                $imageData = is_string($category->image_data) ? json_decode($category->image_data, true) : $category->image_data;
                                if (is_array($imageData) && isset($imageData['sizes']['medium']['storage_url'])) {
                                    $categoryImageUrl = $imageData['sizes']['medium']['storage_url'];
                                }
                            }
                            
                            if (empty($categoryImageUrl) && $category->image) {
                                $categoryImageUrl = str_starts_with($category->image, 'http') ? 
                                    $category->image : 
                                    asset('storage/' . $category->image);
                            }
                            
                            if (empty($categoryImageUrl)) {
                                $categoryImageUrl = asset('assets/images/default-category.jpg');
                            }
                        @endphp
                        
                        <img class="lazyload" 
                             data-src="{{ $categoryImageUrl }}" 
                             src="{{ $categoryImageUrl }}" 
                             alt="{{ $category->name }}"
                             loading="lazy">
                    </a>
                    
                    @if($category->products_count > 0)
                    <div class="product-count">
                        {{ $category->products_count }} {{ Str::plural('Product', $category->products_count) }}
                    </div>
                    @endif
                    
                    <div class="collection-content">
                        <a href="{{ route('categories.show', $category->slug) }}" 
                           class="tf-btn collection-title hover-icon">
                            <span>{{ $category->name }}</span>
                            <i class="icon icon-arrow1-top-left"></i>
                        </a>
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

    // Add loading animation for category items
    const categoryItems = document.querySelectorAll('.collection-item');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });

    categoryItems.forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(item);
    });

    // Error handling for images
    document.querySelectorAll('.collection-image img').forEach(img => {
        img.addEventListener('error', function() {
            this.src = '{{ asset("assets/images/default-category.jpg") }}';
            this.alt = 'Category Image';
        });
    });

    // Counter animation for stats
    const counters = document.querySelectorAll('.stat-number');
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.textContent);
                const duration = 2000;
                const increment = target / (duration / 16);
                let current = 0;

                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        counter.textContent = target;
                        clearInterval(timer);
                    } else {
                        counter.textContent = Math.floor(current);
                    }
                }, 16);

                counterObserver.unobserve(counter);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => {
        counterObserver.observe(counter);
    });
});
</script>
@endsection