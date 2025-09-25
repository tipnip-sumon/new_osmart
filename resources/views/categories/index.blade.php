@extends('layouts.app')

@section('title', 'All Categories - ' . config('app.name'))

@section('content')
<div class="container">
    <div class="section-heading d-flex align-items-center justify-content-between">
        <h6>All Categories</h6>
        <a class="btn p-0" href="{{ route('home') }}">Back to Home<i class="ms-1 ti ti-home"></i></a>
    </div>
    
    <!-- Categories Grid -->
    <div class="row g-4">
        @forelse($allCategories as $category)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card category-card h-100 shadow-sm">
                <div class="card-body text-center d-flex flex-column p-4">
                    <a href="{{ route('categories.show', $category->slug) }}" class="text-decoration-none flex-grow-1 d-flex flex-column">
                        <!-- Category Image/Icon Container -->
                        <div class="category-image-container mb-3">
                            @php
                                $categoryImageUrl = '';
                                
                                // Handle complex image_data structure first
                                if (isset($category->image_data) && $category->image_data) {
                                    $imageData = is_string($category->image_data) ? json_decode($category->image_data, true) : $category->image_data;
                                    if (is_array($imageData)) {
                                        // Handle complex nested structure
                                        if (isset($imageData['sizes']['medium']['storage_url'])) {
                                            // Use medium size storage_url
                                            $categoryImageUrl = $imageData['sizes']['medium']['storage_url'];
                                        } elseif (isset($imageData['sizes']['original']['storage_url'])) {
                                            // Fallback to original if medium not available
                                            $categoryImageUrl = $imageData['sizes']['original']['storage_url'];
                                        } elseif (isset($imageData['sizes']['large']['storage_url'])) {
                                            // Fallback to large if original not available
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
                                    $categoryImageUrl = asset('assets/img/product/default.png');
                                }
                            @endphp
                            
                            @if(!empty($categoryImageUrl))
                                <img src="{{ $categoryImageUrl }}" 
                                     alt="{{ $category->name }}" 
                                     class="category-image"
                                     onerror="this.src='{{ asset('assets/img/product/default.png') }}'; console.log('Fallback image loaded for category: {{ $category->name }}');">
                            @elseif($category->icon)
                                <img src="{{ asset('assets/img/core-img/' . $category->icon) }}" 
                                     alt="{{ $category->name }}" 
                                     class="category-icon">
                            @else
                                <div class="category-placeholder" 
                                     style="background: linear-gradient(135deg, {{ $category->color_code ?? '#6366f1' }}, {{ $category->color_code ? $category->color_code.'80' : '#8b5cf6' }});">
                                    <i class="ti ti-category-2"></i>
                                </div>
                            @endif
                            
                            <!-- Product Count Badge -->
                            <div class="product-count-badge">
                                {{ $category->products_count }}
                            </div>
                        </div>
                        
                        <!-- Category Content -->
                        <div class="category-content flex-grow-1">
                            <h6 class="category-title mb-2">{{ $category->name }}</h6>
                            <p class="category-subtitle">{{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}</p>
                            
                            @if($category->description)
                                <p class="category-description d-none d-lg-block">
                                    {{ Str::limit($category->description, 60) }}
                                </p>
                            @endif
                        </div>
                        
                        <!-- View Category Button -->
                        <div class="category-action mt-auto pt-2">
                            <span class="btn-view-category">
                                View Category <i class="ti ti-arrow-right ms-1"></i>
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <img src="{{ asset('assets/img/core-img/no-data.png') }}" alt="No categories" class="mb-3" style="width: 100px;">
                <h5 class="text-muted">No Categories Found</h5>
                <p class="text-muted">There are no active categories at the moment.</p>
            </div>
        </div>
        @endforelse
    </div>

    @if($allCategories->count() > 0)
    <!-- Featured Categories Section -->
    <div class="mt-5">
        <div class="section-heading mb-4">
            <h5 class="mb-0">Featured Categories</h5>
            <p class="text-muted small">Discover our most popular product categories</p>
        </div>
        <div class="row g-4">
            @foreach($categories->where('is_featured', true)->take(4) as $featured)
            <div class="col-6 col-lg-3">
                <div class="card featured-category-card h-100">
                    <div class="featured-banner-container">
                        @php
                            $featuredBannerUrl = '';
                            
                            // Handle banner_image_data first
                            if (isset($featured->banner_image_data) && $featured->banner_image_data) {
                                $bannerImageData = is_string($featured->banner_image_data) ? json_decode($featured->banner_image_data, true) : $featured->banner_image_data;
                                if (is_array($bannerImageData)) {
                                    if (isset($bannerImageData['sizes']['large']['storage_url'])) {
                                        $featuredBannerUrl = $bannerImageData['sizes']['large']['storage_url'];
                                    } elseif (isset($bannerImageData['sizes']['original']['storage_url'])) {
                                        $featuredBannerUrl = $bannerImageData['sizes']['original']['storage_url'];
                                    } elseif (isset($bannerImageData['sizes']['medium']['storage_url'])) {
                                        $featuredBannerUrl = $bannerImageData['sizes']['medium']['storage_url'];
                                    }
                                }
                            }
                            
                            // Fallback to simple banner_image field
                            if (empty($featuredBannerUrl) && $featured->banner_image) {
                                $featuredBannerUrl = str_starts_with($featured->banner_image, 'http') ? 
                                    $featured->banner_image : 
                                    asset('storage/' . $featured->banner_image);
                            }
                        @endphp
                        
                        @if(!empty($featuredBannerUrl))
                            <img src="{{ $featuredBannerUrl }}" 
                                 alt="{{ $featured->name }}" 
                                 class="featured-banner"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="featured-banner-placeholder" style="display: none; background: linear-gradient(135deg, {{ $featured->color_code ?? '#f59e0b' }}, {{ $featured->color_code ? $featured->color_code.'cc' : '#fbbf24' }});">
                                <i class="ti ti-star-filled"></i>
                            </div>
                        @else
                            <div class="featured-banner-placeholder" 
                                 style="background: linear-gradient(135deg, {{ $featured->color_code ?? '#f59e0b' }}, {{ $featured->color_code ? $featured->color_code.'cc' : '#fbbf24' }});">
                                <i class="ti ti-star-filled"></i>
                            </div>
                        @endif
                        <div class="featured-badge">
                            <i class="ti ti-crown"></i> Featured
                        </div>
                    </div>
                    
                    <div class="card-body text-center p-3">
                        <a href="{{ route('categories.show', $featured->slug) }}" class="text-decoration-none">
                            <h6 class="featured-title mb-1">{{ $featured->name }}</h6>
                            <p class="featured-subtitle mb-2">{{ $featured->products_count }} products</p>
                            
                            @if($featured->children->count() > 0)
                                <div class="featured-subcategories">
                                    <small class="text-primary">
                                        <i class="ti ti-list me-1"></i>{{ $featured->children->count() }} subcategories
                                    </small>
                                </div>
                            @endif
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Category Tree Structure (Optional - for better navigation) -->
    @if($categories->count() > 0)
    <div class="mt-5">
        <div class="section-heading mb-4">
            <h5 class="mb-0">Browse by Category</h5>
            <p class="text-muted small">Explore products organized by main categories and subcategories</p>
        </div>
        <div class="row g-4">
            @foreach($categories->take(6) as $parentCategory)
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card category-tree-card h-100">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 d-flex align-items-center">
                                <div class="category-tree-icon me-2" 
                                     style="background: linear-gradient(135deg, {{ $parentCategory->color_code ?? '#6366f1' }}, {{ $parentCategory->color_code ? $parentCategory->color_code.'80' : '#8b5cf6' }});">
                                    <i class="ti ti-folder"></i>
                                </div>
                                <a href="{{ route('categories.show', $parentCategory->slug) }}" class="text-decoration-none text-dark">
                                    {{ $parentCategory->name }}
                                </a>
                            </h6>
                            <span class="badge bg-primary rounded-pill">{{ $parentCategory->products_count }}</span>
                        </div>
                    </div>
                    @if($parentCategory->children->count() > 0)
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 category-tree-list">
                            @foreach($parentCategory->children->take(5) as $child)
                            <li class="category-tree-item">
                                <a href="{{ route('categories.show', $child->slug) }}" class="text-decoration-none">
                                    <i class="ti ti-chevron-right me-2 text-primary"></i>
                                    <span class="category-tree-name">{{ $child->name }}</span>
                                    <span class="category-tree-count">({{ $child->products_count }})</span>
                                </a>
                            </li>
                            @endforeach
                            @if($parentCategory->children->count() > 5)
                            <li class="category-tree-item-more">
                                <a href="{{ route('categories.show', $parentCategory->slug) }}" class="text-decoration-none text-primary">
                                    <i class="ti ti-dots me-2"></i>
                                    View all {{ $parentCategory->children->count() }} subcategories
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                    @else
                    <div class="card-body">
                        <div class="text-center text-muted py-3">
                            <i class="ti ti-package mb-2" style="font-size: 2rem; opacity: 0.5;"></i>
                            <p class="mb-0 small">No subcategories</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
/* Main Category Cards */
.category-card {
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: #ffffff;
    overflow: hidden;
    position: relative;
}

.category-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border-color: #6366f1;
}

.category-card:hover .category-image-container {
    transform: scale(1.05);
}

.category-image-container {
    position: relative;
    transition: transform 0.3s ease;
    margin-bottom: 1rem;
}

.category-image, .category-icon {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 12px;
    margin: 0 auto;
    display: block;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.category-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.product-count-badge {
    position: absolute;
    top: -8px;
    right: 20px;
    background: linear-gradient(135deg, #f59e0b, #f97316);
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    min-width: 24px;
    text-align: center;
}

.category-content {
    padding: 0 0.5rem;
}

.category-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.category-subtitle {
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.category-description {
    color: #9ca3af;
    font-size: 0.8rem;
    line-height: 1.4;
    margin-bottom: 0;
}

.category-action {
    padding-top: 1rem;
}

.btn-view-category {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
    font-size: 0.85rem;
    font-weight: 500;
    padding: 8px 16px;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: inline-block;
    opacity: 0;
    transform: translateY(10px);
}

.category-card:hover .btn-view-category {
    opacity: 1;
    transform: translateY(0);
}

/* Featured Category Cards */
.featured-category-card {
    border: 2px solid #fbbf24;
    border-radius: 16px;
    background: linear-gradient(135deg, #fffbeb 0%, #ffffff 100%);
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
}

.featured-category-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 32px rgba(251, 191, 36, 0.2);
    border-color: #f59e0b;
}

.featured-banner-container {
    position: relative;
    height: 120px;
    overflow: hidden;
}

.featured-banner {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.featured-banner-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2.5rem;
}

.featured-category-card:hover .featured-banner {
    transform: scale(1.1);
}

.featured-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(251, 191, 36, 0.95);
    color: white;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 12px;
    backdrop-filter: blur(10px);
}

.featured-title {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.featured-subtitle {
    color: #6b7280;
    font-size: 0.8rem;
    font-weight: 500;
}

.featured-subcategories {
    margin-top: 0.5rem;
}

/* Category Tree Cards */
.category-tree-card {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: #ffffff;
    transition: all 0.3s ease;
    overflow: hidden;
}

.category-tree-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
    border-color: #6366f1;
}

.category-tree-card .card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    border-radius: 12px 12px 0 0;
    padding: 1rem;
}

.category-tree-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.category-tree-list {
    padding: 0;
}

.category-tree-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid #f3f4f6;
    transition: all 0.2s ease;
}

.category-tree-item:last-child {
    border-bottom: none;
}

.category-tree-item:hover {
    background-color: #f8fafc;
    padding-left: 0.5rem;
}

.category-tree-item a {
    color: #4b5563;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: color 0.2s ease;
}

.category-tree-item:hover a {
    color: #6366f1;
}

.category-tree-name {
    flex-grow: 1;
}

.category-tree-count {
    color: #9ca3af;
    font-size: 0.8rem;
    font-weight: 500;
}

.category-tree-item-more {
    padding: 1rem 0 0.5rem 0;
    border-top: 1px solid #f3f4f6;
    margin-top: 0.5rem;
}

.category-tree-item-more a {
    color: #6366f1;
    font-size: 0.85rem;
    font-weight: 500;
}

/* Section Headings */
.section-heading {
    text-align: left;
    margin-bottom: 2rem;
}

.section-heading h5 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.section-heading p {
    color: #6b7280;
    margin-bottom: 0;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .category-image, .category-icon, .category-placeholder {
        width: 60px;
        height: 60px;
    }
    
    .category-title {
        font-size: 1rem;
    }
    
    .product-count-badge {
        right: 10px;
        font-size: 0.7rem;
    }
    
    .btn-view-category {
        opacity: 1;
        transform: translateY(0);
        font-size: 0.8rem;
        padding: 6px 12px;
    }
    
    .featured-banner-container {
        height: 100px;
    }
}

/* Loading Animation */
@keyframes shimmer {
    0% { background-position: -468px 0; }
    100% { background-position: 468px 0; }
}

.category-card-loading {
    background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
    background-size: 400% 100%;
    animation: shimmer 1.2s ease-in-out infinite;
}
</style>
@endsection
