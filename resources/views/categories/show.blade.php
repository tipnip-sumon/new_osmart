@extends('layouts.app')

@section('title', 'Category - ' . ($category->name ?? ucfirst(str_replace('-', ' ', $slug))))
@section('description', 'Browse products in ' . ($category->name ?? ucfirst(str_replace('-', ' ', $slug))) . ' category')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name ?? ucfirst(str_replace('-', ' ', $slug)) }}</li>
                </ol>
            </nav>

            <h1 class="mb-4">{{ $category->name ?? ucfirst(str_replace('-', ' ', $slug)) }}</h1>
            
            @if($category && $category->description)
                <p class="text-muted mb-4">{{ $category->description }}</p>
            @endif
            
            <!-- Category Products Grid -->
            <div class="row g-3">
                @forelse($products as $product)
                <!-- Dynamic Product Card -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card product-card h-100">
                        <div class="card-body text-center">
                            @php
                                $productImageUrl = '';
                                
                                // Handle images array first (for products)
                                if (isset($product->images) && is_array($product->images) && !empty($product->images)) {
                                    $firstImage = $product->images[0];
                                    if (is_array($firstImage) && isset($firstImage['sizes'])) {
                                        if (isset($firstImage['sizes']['medium']['storage_url'])) {
                                            $productImageUrl = $firstImage['sizes']['medium']['storage_url'];
                                        } elseif (isset($firstImage['sizes']['original']['storage_url'])) {
                                            $productImageUrl = $firstImage['sizes']['original']['storage_url'];
                                        } elseif (isset($firstImage['sizes']['large']['storage_url'])) {
                                            $productImageUrl = $firstImage['sizes']['large']['storage_url'];
                                        }
                                    }
                                }
                                
                                // Handle complex image_data structure (for categories)
                                if (empty($productImageUrl) && isset($product->image_data) && $product->image_data) {
                                    $imageData = is_string($product->image_data) ? json_decode($product->image_data, true) : $product->image_data;
                                    if (is_array($imageData)) {
                                        if (isset($imageData['sizes']['medium']['storage_url'])) {
                                            $productImageUrl = $imageData['sizes']['medium']['storage_url'];
                                        } elseif (isset($imageData['sizes']['original']['storage_url'])) {
                                            $productImageUrl = $imageData['sizes']['original']['storage_url'];
                                        } elseif (isset($imageData['sizes']['large']['storage_url'])) {
                                            $productImageUrl = $imageData['sizes']['large']['storage_url'];
                                        }
                                    }
                                }
                                
                                // Fallback to simple image field
                                if (empty($productImageUrl) && $product->image) {
                                    $productImageUrl = str_starts_with($product->image, 'http') ? 
                                        $product->image : 
                                        asset('storage/' . $product->image);
                                }
                                
                                // Final fallback to default image
                                if (empty($productImageUrl)) {
                                    $productImageUrl = asset('assets/img/product/1.png');
                                }
                            @endphp
                            
                            <a href="{{ route('products.show', $product->slug) }}">
                                <img src="{{ $productImageUrl }}" 
                                     alt="{{ $product->name }}" 
                                     class="img-fluid mb-2" 
                                     style="height: 150px; object-fit: cover;"
                                     onerror="this.src='{{ asset('assets/img/product/1.png') }}'; console.log('Fallback image loaded for: {{ $product->name }}');">
                            </a>
                            <h6 class="product-title mb-1">
                                <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                                    {{ Str::limit($product->name, 20) }}
                                </a>
                            </h6>
                            <p class="product-price mb-2">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <span class="text-primary">৳{{ number_format($product->sale_price, 2) }}</span>
                                    <small class="text-muted"><del>৳{{ number_format($product->price, 2) }}</del></small>
                                @else
                                    <span class="text-primary">৳{{ number_format($product->price, 2) }}</span>
                                @endif
                            </p>
                            <div class="product-rating mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="ti ti-star{{ $i <= 4 ? '-filled text-warning' : ' text-muted' }}"></i>
                                @endfor
                                <span class="ms-1 small">({{ rand(5, 50) }})</span>
                            </div>
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary btn-sm" 
                                        onclick="quickAddToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->sale_price ?? $product->price }}, '{{ $productImageUrl }}')">
                                    <i class="ti ti-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <!-- No Products Found -->
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="ti ti-package" style="font-size: 4rem; color: #ccc;"></i>
                        <h4 class="mt-3 text-muted">No Products Found</h4>
                        <p class="text-muted">There are no products available in this category at the moment.</p>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">
                            <i class="ti ti-arrow-left"></i> Back to Home
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            @endif

            <!-- Back to Home -->
            @if($products->count() > 0)
                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="ti ti-arrow-left"></i> Back to Home
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
