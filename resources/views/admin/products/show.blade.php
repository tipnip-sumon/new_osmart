@extends('admin.layouts.app')

@section('title', 'Product Details: ' . $product->name)

@push('styles')
<style>
/* Enhanced Product Detail Styles */
.product-image-container {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    background: #f8f9fa;
    margin-bottom: 1.5rem;
}

.product-main-image {
    width: 100%;
    height: 400px;
    object-fit: contain;
    transition: all 0.3s ease;
    background: white;
    cursor: pointer;
    border-radius: 8px;
}

.product-main-image:hover {
    transform: scale(1.02);
}

.image-count-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0,0,0,0.6);
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    z-index: 10;
}

.product-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 10px;
    margin-top: 15px;
}

.gallery-thumb {
    width: 100%;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 2px solid transparent;
}

.gallery-thumb:hover {
    transform: translateY(-3px);
}

.gallery-thumb.active {
    border-color: #5D87FF;
    box-shadow: 0 0 0 2px rgba(93, 135, 255, 0.3);
}

.product-details-container {
    position: relative;
}

.product-title {
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
    color: #1E293B;
    font-weight: 700;
}

.product-price-container {
    background: linear-gradient(to right, #f8f9fa, #ffffff);
    padding: 1.25rem;
    border-radius: 12px;
    margin: 1rem 0;
    border-left: 4px solid #5D87FF;
}

.product-price {
    font-size: 2rem;
    font-weight: 700;
    color: #5D87FF;
}

.product-old-price {
    font-size: 1.2rem;
    color: #94a3b8;
    text-decoration: line-through;
    margin-right: 10px;
}

.product-discount {
    background: #22c55e;
    color: white;
    font-weight: 500;
    font-size: 0.9rem;
    padding: 3px 10px;
    border-radius: 20px;
    margin-left: 10px;
}

.product-status {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 1rem;
}

.product-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin: 1.5rem 0;
}

.info-card {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.info-card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border-color: #cbd5e1;
}

.info-label {
    font-size: 0.875rem;
    color: #64748b;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #334155;
}

.tab-content {
    padding: 1.5rem;
    background: white;
    border-radius: 0 0 10px 10px;
    border: 1px solid #e2e8f0;
    border-top: none;
}

.nav-tabs .nav-link {
    color: #64748b;
    border: 1px solid transparent;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    padding: 0.75rem 1.25rem;
    font-weight: 500;
    background: #f1f5f9;
    margin-right: 5px;
}

.nav-tabs .nav-link.active {
    color: #5D87FF;
    background-color: white;
    border-color: #e2e8f0 #e2e8f0 white;
    border-bottom: 2px solid white;
    font-weight: 600;
}

.lightbox-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.92);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    backdrop-filter: blur(3px);
}

.lightbox-overlay.active {
    opacity: 1;
    visibility: visible;
}

.lightbox-content {
    max-width: 90%;
    max-height: 80%;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    transform: scale(0.95);
    transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.lightbox-overlay.active .lightbox-content {
    transform: scale(1);
}

.lightbox-image {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.lightbox-image.loaded {
    opacity: 1;
}

.lightbox-loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 50px;
    height: 50px;
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top: 4px solid #ffffff;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}

.lightbox-close {
    position: absolute;
    top: -40px;
    right: 0;
    color: white;
    font-size: 30px;
    cursor: pointer;
    background: none;
    border: none;
}

.lightbox-nav {
    position: absolute;
    width: 100%;
    display: flex;
    justify-content: space-between;
    top: 50%;
    transform: translateY(-50%);
}

.lightbox-nav button {
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    padding: 15px 20px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 20px;
}

.lightbox-nav button:hover {
    background: rgba(255,255,255,0.4);
}

.description-content {
    line-height: 1.8;
}

.attribute-label {
    font-weight: 600;
    color: #475569;
    min-width: 140px;
}

.attribute-value {
    color: #334155;
}

.product-attributes .row:nth-child(odd) {
    background: #f8fafc;
}

.product-attributes .row {
    padding: 8px 0;
    margin: 0;
    border-bottom: 1px dashed #e2e8f0;
}

.mlm-points-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.25rem;
}

.point-card {
    background: linear-gradient(135deg, #5D87FF, #3366FF);
    color: white;
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    padding: 1.75rem;
    text-align: center;
    transition: transform 0.2s ease;
}

.point-card:hover {
    transform: translateY(-2px);
}

.point-value {
    font-size: 2.25rem;
    font-weight: bold;
    margin: 0.75rem 0;
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
}

.point-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    cursor: pointer;
}

.point-label {
    font-size: 0.875rem;
    opacity: 0.9;
}

/* Responsive improvements */
@media (min-width: 1400px) {
    .container-fluid {
        max-width: 1600px;
        margin: 0 auto;
    }
    
    .mlm-points-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }
    
    .product-image {
        height: 350px;
    }
    
    .point-card {
        padding: 2rem;
    }
    
    .point-value {
        font-size: 2.5rem;
    }
}

@media (min-width: 1200px) and (max-width: 1399px) {
    .mlm-points-grid {
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.25rem;
    }
    
    .product-image {
        height: 320px;
    }
}

@media (min-width: 992px) and (max-width: 1199px) {
    .mlm-points-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .product-image {
        height: 280px;
    }
}

@media (max-width: 768px) {
    .product-image {
        height: 250px;
    }
    
    .product-main-image {
        height: 300px;
    }
    
    .product-gallery {
        margin-top: 1rem;
        gap: 0.5rem;
    }
    
    .product-gallery img {
        width: 70px;
        height: 70px;
    }
    
    .mlm-points-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.75rem;
    }
    
    .point-card {
        padding: 1rem;
    }
    
    .point-value {
        font-size: 1.5rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .d-flex.gap-2 .btn {
        width: 100%;
    }
    
    .page-header-breadcrumb .d-flex.flex-wrap.gap-2 {
        flex-direction: column;
        align-items: stretch !important;
    }
    
    .page-header-breadcrumb .d-flex.flex-wrap.gap-2 .btn {
        width: 100%;
        justify-content: center;
    }
    
    /* Stack product layout on mobile */
    .row.g-0 .col-lg-6 {
        border-end: none !important;
        border-bottom: 1px solid #dee2e6;
    }
    
    .row.g-0 .col-lg-6:last-child {
        border-bottom: none;
    }
    
    .product-info-grid {
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
}

@media (max-width: 576px) {
    .product-gallery {
        justify-content: flex-start !important;
        flex-wrap: wrap;
    }
    
    .product-gallery img {
        width: 60px;
        height: 60px;
    }
    
    .mlm-points-grid {
        grid-template-columns: 1fr;
    }
    
    .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    /* Tab navigation responsive */
    .nav-tabs {
        flex-wrap: wrap;
        border: none;
    }
    
    .nav-tabs .nav-link {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin: 2px;
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
        white-space: nowrap;
    }
    
    .nav-tabs .nav-link.active {
        background-color: var(--bs-primary);
        color: white;
        border-color: var(--bs-primary);
    }
    
    /* Attribute rows responsive */
    .attribute-label {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .attribute-value {
        margin-bottom: 0.75rem;
        color: #6c757d;
    }
    
    /* Point cards responsive */
    .point-card {
        margin-bottom: 1rem;
        min-height: auto;
    }
    
    /* Tables responsive improvements */
    .table-responsive {
        border-radius: 8px;
        overflow-x: auto;
    }
    
    .table th, .table td {
        white-space: nowrap;
        font-size: 0.875rem;
    }
}

/* Extra small devices improvements */
@media (max-width: 480px) {
    .card-title {
        font-size: 1.125rem;
    }
    
    .product-title {
        font-size: 1.5rem;
        line-height: 1.3;
    }
    
    .product-price {
        font-size: 1.75rem;
    }
    
    .info-card {
        padding: 1rem;
        text-align: center;
    }
    
    .info-label {
        font-size: 0.875rem;
    }
    
    .info-value {
        font-size: 1rem;
        font-weight: 600;
    }
    
    .nav-tabs .nav-item {
        flex: 1;
        min-width: auto;
    }
    
    .nav-tabs .nav-link {
        text-align: center;
        padding: 0.5rem 0.25rem;
        font-size: 0.8rem;
    }
    
    .nav-tabs .nav-link i {
        display: block;
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }
}

/* Loading animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div class="mb-3 mb-md-0">
                <h1 class="page-title fw-semibold fs-18 mb-2">Product Details</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary d-flex align-items-center">
                    <i class="ti ti-edit me-1"></i>Edit Product
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-light d-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Products
                </a>
            </div>
        </div>

        <div class="row g-3">
            <!-- Product Information -->
            <div class="col-xxl-9 col-lg-8 col-12">
                <div class="card custom-card overflow-hidden">
                    <div class="card-header border-bottom">
                        <div class="card-title">Product Information</div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <!-- Product Images -->
                            <div class="col-lg-6 col-md-6 col-12 p-4 border-end">
                                <div class="product-image-container">
                                    @php
                                        // Get image URLs with proper error handling
                                        $images = [];
                                        $mainImageUrl = '/admin-assets/images/media/1.jpg'; // Default fallback
                                        
                                        if(isset($product->images) && !empty($product->images)) {
                                            foreach($product->images as $image) {
                                                $url = '';
                                                
                                                if(is_array($image)) {
                                                    if(isset($image['sizes']) && !empty($image['sizes'])) {
                                                        $url = $image['sizes']['large']['url'] ?? 
                                                               $image['sizes']['medium']['url'] ?? 
                                                               $image['sizes']['original']['url'] ?? '';
                                                    } elseif(isset($image['urls']) && !empty($image['urls'])) {
                                                        $url = $image['urls']['large'] ?? 
                                                               $image['urls']['medium'] ?? 
                                                               $image['urls']['original'] ?? '';
                                                    } else {
                                                        $url = $image['url'] ?? $image['path'] ?? '';
                                                    }
                                                } elseif(is_string($image)) {
                                                    $url = $image;
                                                }
                                                
                                                // Handle different URL formats
                                                if($url) {
                                                    // If it's already a full URL, use as is
                                                    if(str_starts_with($url, 'http')) {
                                                        $images[] = $url;
                                                    }
                                                    // If it starts with /storage/, convert to direct access
                                                    elseif(str_starts_with($url, '/storage/')) {
                                                        $path = str_replace('/storage/', '', $url);
                                                        // Try direct-storage route first
                                                        $directUrl = '/direct-storage/' . $path;
                                                        $images[] = $directUrl;
                                                    }
                                                    // If it's just a path, create storage URL
                                                    else {
                                                        $storageUrl = asset('storage/' . ltrim($url, '/'));
                                                        $images[] = $storageUrl;
                                                    }
                                                }
                                            }
                                            
                                            if(!empty($images)) {
                                                $mainImageUrl = $images[0];
                                            }
                                        }
                                    @endphp
                                    
                                    @if(!empty($images))
                                        <span class="image-count-badge">{{ count($images) }} {{ count($images) > 1 ? 'Images' : 'Image' }}</span>
                                    @endif
                                    
                                    <img id="mainImage" src="{{ $mainImageUrl }}" class="product-main-image" 
                                         alt="{{ $product->name }}" data-index="0" onclick="openLightbox(0)"
                                         onerror="handleImageError(this)">
                                    
                                    @if(!empty($images))
                                        <div class="product-gallery">
                                            @foreach($images as $index => $imageUrl)
                                                <img src="{{ $imageUrl }}" 
                                                     alt="{{ $product->name }} - Image {{ $index + 1 }}" 
                                                     class="gallery-thumb {{ $index === 0 ? 'active' : '' }}" 
                                                     onclick="changeMainImage('{{ $imageUrl }}', {{ $index }}, this)"
                                                     data-index="{{ $index }}"
                                                     onerror="handleImageError(this)">
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-center text-muted mt-3">No product images available</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Product Details -->
                            <div class="col-lg-6 col-md-6 col-12 p-4">
                                <h2 class="product-title">{{ $product->name }}</h2>
                                
                                <div class="product-status">
                                    <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                                        <i class="ti ti-{{ $product->status === 'active' ? 'circle-check' : 'circle-x' }} me-1"></i>
                                        {{ ucfirst($product->status) }}
                                    </span>
                                    
                                    @if($product->is_featured)
                                        <span class="badge bg-warning">
                                            <i class="ti ti-star me-1"></i>Featured
                                        </span>
                                    @endif
                                    
                                    @if($product->is_starter_kit)
                                        <span class="badge bg-info">
                                            <i class="ti ti-package me-1"></i>Starter Kit
                                            @if($product->starter_kit_tier)
                                                - {{ ucfirst($product->starter_kit_tier) }}
                                            @endif
                                        </span>
                                    @endif
                                    
                                    @if($product->stock_quantity <= 0)
                                        <span class="badge bg-danger">
                                            <i class="ti ti-alert-circle me-1"></i>Out of Stock
                                        </span>
                                    @elseif($product->stock_quantity < 10)
                                        <span class="badge bg-warning text-dark">
                                            <i class="ti ti-alert-triangle me-1"></i>Low Stock
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="ti ti-check me-1"></i>In Stock
                                        </span>
                                    @endif
                                </div>

                                <div class="product-price-container">
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <div class="d-flex align-items-center flex-wrap">
                                            <span class="product-old-price">৳{{ number_format($product->price, 2) }}</span>
                                            <span class="product-price">৳{{ number_format($product->sale_price, 2) }}</span>
                                            @php
                                                $discountPercentage = round((($product->price - $product->sale_price) / $product->price) * 100);
                                            @endphp
                                            <span class="product-discount">{{ $discountPercentage }}% OFF</span>
                                        </div>
                                    @else
                                        <span class="product-price">৳{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                
                                @if($product->short_description)
                                    <div class="mb-4">
                                        <h6 class="fw-semibold mb-2">Short Description</h6>
                                        <p class="text-muted mb-0">{{ $product->short_description }}</p>
                                    </div>
                                @endif
                                
                                <div class="product-info-grid">
                                    <div class="info-card">
                                        <div class="info-label">SKU</div>
                                        <div class="info-value">{{ $product->sku }}</div>
                                    </div>
                                    
                                    <div class="info-card">
                                        <div class="info-label">Category</div>
                                        <div class="info-value">{{ $product->category->name ?? 'N/A' }}</div>
                                    </div>
                                    
                                    <div class="info-card">
                                        <div class="info-label">Stock</div>
                                        <div class="info-value text-{{ $product->stock_quantity > 0 ? ($product->stock_quantity < 10 ? 'warning' : 'success') : 'danger' }}">
                                            {{ $product->stock_quantity }} units
                                        </div>
                                    </div>
                                    
                                    <div class="info-card">
                                        <div class="info-label">Brand</div>
                                        <div class="info-value">{{ $product->brand->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                
                                <div class="row g-3 mt-2">
                                    <div class="col-md-6">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary btn-lg w-100">
                                            <i class="ti ti-edit me-2"></i>Edit Product
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-outline-danger btn-lg w-100" 
                                                onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')">
                                            <i class="ti ti-trash me-2"></i>Delete Product
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Product Tabs -->
                <div class="card custom-card mt-3">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="productTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" 
                                        type="button" role="tab" aria-controls="description" aria-selected="true">
                                    <i class="ti ti-file-text me-1"></i>Description
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="attributes-tab" data-bs-toggle="tab" data-bs-target="#attributes" 
                                        type="button" role="tab" aria-controls="attributes" aria-selected="false">
                                    <i class="ti ti-list-details me-1"></i>Specifications
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" 
                                        type="button" role="tab" aria-controls="inventory" aria-selected="false">
                                    <i class="ti ti-box me-1"></i>Inventory
                                </button>
                            </li>
                            @if($product->generates_commission || $product->is_starter_kit || isset($product->pv_points))
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="mlm-tab" data-bs-toggle="tab" data-bs-target="#mlm" 
                                            type="button" role="tab" aria-controls="mlm" aria-selected="false">
                                        <i class="ti ti-businessplan me-1"></i>MLM Details
                                    </button>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="tab-content" id="productTabContent">
                        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                            <div class="card-body">
                                <div class="description-content">
                                    @if($product->description)
                                        {!! nl2br(e($product->description)) !!}
                                    @else
                                        <p class="text-muted">No description provided for this product.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="attributes" role="tabpanel" aria-labelledby="attributes-tab">
                            <div class="card-body">
                                <div class="product-attributes">
                                    <div class="row py-3 fw-bold bg-light rounded mb-3">
                                        <div class="col-sm-4 col-12">Attribute</div>
                                        <div class="col-sm-8 col-12">Value</div>
                                    </div>
                                    
                                    <!-- Basic Attributes -->
                                    <div class="row py-2 border-bottom">
                                        <div class="col-sm-4 col-12 attribute-label">SKU</div>
                                        <div class="col-sm-8 col-12 attribute-value">{{ $product->sku }}</div>
                                    </div>
                                    
                                    <div class="row py-2 border-bottom">
                                        <div class="col-sm-4 col-12 attribute-label">Barcode</div>
                                        <div class="col-sm-8 col-12 attribute-value">{{ $product->barcode ?? 'N/A' }}</div>
                                    </div>
                                    
                                    <div class="row py-2 border-bottom">
                                        <div class="col-sm-4 col-12 attribute-label">Model Number</div>
                                        <div class="col-sm-8 col-12 attribute-value">{{ $product->model_number ?? 'N/A' }}</div>
                                    </div>
                                    
                                    <div class="row py-2 border-bottom">
                                        <div class="col-sm-4 col-12 attribute-label">Weight</div>
                                        <div class="col-sm-8 col-12 attribute-value">
                                            {{ $product->weight ? $product->weight.' g' : 'N/A' }}
                                        </div>
                                    </div>
                                    
                                    @if($product->width || $product->height || $product->length)
                                        <div class="row py-2 border-bottom">
                                            <div class="col-sm-4 col-12 attribute-label">Dimensions</div>
                                            <div class="col-sm-8 col-12 attribute-value">
                                                {{ $product->length ?? 0 }} × {{ $product->width ?? 0 }} × {{ $product->height ?? 0 }} cm
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="row py-2 border-bottom">
                                        <div class="col-sm-4 col-12 attribute-label">Material</div>
                                        <div class="col-sm-8 col-12 attribute-value">{{ $product->material ?? 'N/A' }}</div>
                                    </div>
                                    
                                    <div class="row py-2 border-bottom">
                                        <div class="col-sm-4 col-12 attribute-label">Color</div>
                                        <div class="col-sm-8 col-12 attribute-value">{{ $product->color ?? 'N/A' }}</div>
                                    </div>
                                    
                                    <div class="row py-2 border-bottom">
                                        <div class="col-sm-4 col-12 attribute-label">Size</div>
                                        <div class="col-sm-8 col-12 attribute-value">{{ $product->size ?? 'N/A' }}</div>
                                    </div>
                                    
                                    <div class="row py-2 border-bottom">
                                        <div class="col-sm-4 col-12 attribute-label">Condition</div>
                                        <div class="col-sm-8 col-12 attribute-value">{{ ucfirst($product->condition ?? 'new') }}</div>
                                    </div>
                                    
                                    <!-- Additional Attributes -->
                                    @if(isset($product->specifications) && !empty($product->specifications))
                                        @foreach($product->specifications as $spec => $value)
                                            <div class="row py-2 border-bottom">
                                                <div class="col-sm-4 col-12 attribute-label">{{ ucfirst(str_replace('_', ' ', $spec)) }}</div>
                                                <div class="col-sm-8 col-12 attribute-value">{{ $value }}</div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="info-card">
                                            <div class="info-label">Current Stock</div>
                                            <div class="info-value text-{{ $product->stock_quantity > 0 ? 'success' : 'danger' }}">
                                                {{ $product->stock_quantity }} units
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="info-card">
                                            <div class="info-label">Track Quantity</div>
                                            <div class="info-value">{{ $product->track_quantity ? 'Yes' : 'No' }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="info-card">
                                            <div class="info-label">Allow Backorder</div>
                                            <div class="info-value">{{ $product->allow_backorder ? 'Yes' : 'No' }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="info-card">
                                            <div class="info-label">Minimum Stock Level</div>
                                            <div class="info-value">{{ $product->min_stock_level ?? 0 }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="info-card">
                                            <div class="info-label">Backorder Limit</div>
                                            <div class="info-value">{{ $product->backorder_limit ?? 'Not set' }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="info-card">
                                            <div class="info-label">Maximum Stock Level</div>
                                            <div class="info-value">{{ $product->max_stock_level ?? 'Not set' }}</div>
                                        </div>
                                    </div>
                                </div>
                            
                            <!-- Current Inventory Record -->
                            @if(isset($product->inventory))
                                <div class="mt-4">
                                    <h6 class="fw-semibold mb-3">Inventory Status</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Warehouse</th>
                                                    <th>Quantity</th>
                                                    <th>Reserved</th>
                                                    <th>Available</th>
                                                    <th>Location</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $product->inventory->warehouse->name ?? 'Default' }}</td>
                                                    <td>{{ $product->inventory->quantity }}</td>
                                                    <td>{{ $product->inventory->reserved_quantity }}</td>
                                                    <td>{{ $product->inventory->available_quantity ?? ($product->inventory->quantity - $product->inventory->reserved_quantity) }}</td>
                                                    <td>{{ $product->inventory->location ?? 'Not specified' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Inventory Movement History -->
                            @if(isset($product->inventoryMovements) && count($product->inventoryMovements) > 0)
                                <div class="mt-4">
                                    <h6 class="fw-semibold mb-3">Inventory Movement History</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Type</th>
                                                    <th>Quantity</th>
                                                    <th>Previous</th>
                                                    <th>New</th>
                                                    <th>Reason</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($product->inventoryMovements->sortByDesc('created_at') as $movement)
                                                    <tr>
                                                        <td>{{ $movement->created_at->format('M d, Y H:i') }}</td>
                                                        <td>{{ ucfirst($movement->type) }}</td>
                                                        <td>{{ $movement->quantity }}</td>
                                                        <td>{{ $movement->previous_quantity }}</td>
                                                        <td>{{ $movement->new_quantity }}</td>
                                                        <td>{{ $movement->reason ?: 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            </div>
                        </div>
                        
                        <!-- MLM Tab -->
                        @if($product->generates_commission || $product->is_starter_kit || isset($product->pv_points))
                            <div class="tab-pane fade" id="mlm" role="tabpanel" aria-labelledby="mlm-tab">
                                <div class="card-body">
                                    <div class="mlm-points-grid">
                                        <div class="point-card">
                                            <div class="point-label">Personal Volume</div>
                                            <div class="point-value">{{ number_format($product->pv_points ?? 0, 2) }}</div>
                                            <div class="point-label">PV Points</div>
                                        </div>
                                        
                                        <div class="point-card">
                                            <div class="point-label">Business Volume</div>
                                            <div class="point-value">{{ number_format($product->bv_points ?? 0, 2) }}</div>
                                            <div class="point-label">BV Points</div>
                                        </div>
                                        
                                        <div class="point-card">
                                            <div class="point-label">Commission Volume</div>
                                            <div class="point-value">{{ number_format($product->cv_points ?? 0, 2) }}</div>
                                            <div class="point-label">CV Points</div>
                                        </div>
                                        
                                        <div class="point-card">
                                            <div class="point-label">Qualification Volume</div>
                                            <div class="point-value">{{ number_format($product->qv_points ?? 0, 2) }}</div>
                                            <div class="point-label">QV Points</div>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-4 mt-2">
                                        <div class="col-lg-6 col-12">
                                            <h6 class="fw-semibold mb-3">Commission Settings</h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-medium">Generates Commission</td>
                                                            <td>{{ $product->generates_commission ? 'Yes' : 'No' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-medium">Direct Commission Rate</td>
                                                            <td>{{ $product->direct_commission_rate ?? 0 }}%</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-medium">Level 1 Commission</td>
                                                            <td>{{ $product->level_1_commission ?? 0 }}%</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-medium">Level 2 Commission</td>
                                                            <td>{{ $product->level_2_commission ?? 0 }}%</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-medium">Level 3 Commission</td>
                                                            <td>{{ $product->level_3_commission ?? 0 }}%</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-6 col-12">
                                            <h6 class="fw-semibold mb-3">Product Classification</h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-medium">Is Starter Kit</td>
                                                            <td>{{ $product->is_starter_kit ? 'Yes' : 'No' }}</td>
                                                        </tr>
                                                        @if($product->is_starter_kit)
                                                            <tr>
                                                                <td class="fw-medium">Starter Kit Tier</td>
                                                                <td>{{ ucfirst($product->starter_kit_tier ?? 'Standard') }}</td>
                                                            </tr>
                                                        @endif
                                                        <tr>
                                                            <td class="fw-medium">Autoship Eligible</td>
                                                            <td>{{ $product->is_autoship_eligible ? 'Yes' : 'No' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-medium">Requires Qualification</td>
                                                            <td>{{ $product->requires_qualification ? 'Yes' : 'No' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-medium">Affects Binary Tree</td>
                                                            <td>{{ $product->affects_binary_tree ? 'Yes' : 'No' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Product Sidebar -->
            <div class="col-xxl-3 col-lg-4 col-12">
                <!-- Product Status -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Product Status</div>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="avatar avatar-sm bg-{{ $product->is_active ? 'success' : 'danger' }}">
                                            <i class="ti ti-{{ $product->is_active ? 'check' : 'x' }}"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="d-block fw-semibold">Status</span>
                                        <span class="text-muted">{{ ucfirst($product->status) }}</span>
                                    </div>
                                    <div class="ms-auto">
                                        <form action="{{ route('admin.products.toggle-status', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light">Toggle</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="avatar avatar-sm bg-{{ $product->is_featured ? 'warning' : 'light' }}">
                                            <i class="ti ti-star{{ $product->is_featured ? '' : '-off' }}"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="d-block fw-semibold">Featured</span>
                                        <span class="text-muted">{{ $product->is_featured ? 'Yes' : 'No' }}</span>
                                    </div>
                                    <div class="ms-auto">
                                        <form action="{{ route('admin.products.toggle-featured', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light">Toggle</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="avatar avatar-sm bg-info">
                                            <i class="ti ti-calendar"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="d-block fw-semibold">Created</span>
                                        <span class="text-muted">{{ $product->created_at ? $product->created_at->format('M d, Y') : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="avatar avatar-sm bg-info">
                                            <i class="ti ti-edit"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="d-block fw-semibold">Updated</span>
                                        <span class="text-muted">{{ $product->updated_at ? $product->updated_at->format('M d, Y') : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pricing Information -->
                <div class="card custom-card mt-3">
                    <div class="card-header">
                        <div class="card-title">Pricing Information</div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="fw-semibold">Regular Price</span>
                                <span>৳{{ number_format($product->price, 2) }}</span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="fw-semibold">Sale Price</span>
                                <span>৳{{ $product->sale_price ? number_format($product->sale_price, 2) : 'N/A' }}</span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="fw-semibold">Cost Price</span>
                                <span>৳{{ $product->cost_price ? number_format($product->cost_price, 2) : 'N/A' }}</span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="fw-semibold">Wholesale Price</span>
                                <span>৳{{ $product->wholesale_price ? number_format($product->wholesale_price, 2) : 'N/A' }}</span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="fw-semibold">Profit Margin</span>
                                @php
                                    $costPrice = $product->cost_price ?: 0;
                                    $sellingPrice = $product->sale_price ?: $product->price;
                                    $margin = $costPrice > 0 ? (($sellingPrice - $costPrice) / $sellingPrice) * 100 : 'N/A';
                                @endphp
                                <span class="{{ is_numeric($margin) && $margin > 0 ? 'text-success' : '' }}">
                                    {{ is_numeric($margin) ? number_format($margin, 2) . '%' : $margin }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Categories & Tags -->
                <div class="card custom-card mt-3">
                    <div class="card-header">
                        <div class="card-title">Categories & Tags</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="fw-semibold mb-2">Category</label>
                            <p class="mb-1">
                                @if($product->category)
                                    <span class="badge bg-light text-dark py-1 px-2">
                                        <i class="ti ti-folder me-1"></i>{{ $product->category->name }}
                                    </span>
                                @else
                                    <span class="text-muted">No category assigned</span>
                                @endif
                            </p>
                        </div>
                        
                        @if(isset($product->subcategory_id) && $product->subcategory_id)
                            <div class="mb-3">
                                <label class="fw-semibold mb-2">Subcategory</label>
                                <p class="mb-1">
                                    <span class="badge bg-light text-dark py-1 px-2">
                                        <i class="ti ti-folder me-1"></i>
                                        {{ \App\Models\Category::find($product->subcategory_id)->name ?? 'Unknown' }}
                                    </span>
                                </p>
                            </div>
                        @endif
                        
                        @if(isset($product->tags) && count($product->tags) > 0)
                            <div>
                                <label class="fw-semibold mb-2">Tags</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($product->tags as $tag)
                                        <span class="badge bg-light-blue text-primary py-1 px-2">
                                            <i class="ti ti-tag me-1"></i>{{ is_string($tag) ? $tag : ($tag->name ?? $tag) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Image Lightbox -->
<div id="lightbox" class="lightbox-overlay">
    <div class="lightbox-content">
        <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
        <img id="lightboxImage" class="lightbox-image" src="" alt="Product Image">
        <div class="lightbox-nav">
            <button onclick="prevImage()" id="prevBtn">&lt;</button>
            <button onclick="nextImage()" id="nextBtn">&gt;</button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete <span id="deleteProductName" class="fw-bold"></span>?
                <p class="text-danger mt-2">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Image handling functions
    let currentImageIndex = 0;
    const images = @json($images ?? []);
    
    function changeMainImage(url, index, thumbElement) {
        // Update main image
        document.getElementById('mainImage').src = url;
        document.getElementById('mainImage').setAttribute('data-index', index);
        currentImageIndex = index;
        
        // Update active class on thumbnails
        const thumbs = document.querySelectorAll('.gallery-thumb');
        thumbs.forEach(thumb => thumb.classList.remove('active'));
        
        if (thumbElement) {
            thumbElement.classList.add('active');
        }
    }
    
    function openLightbox(index) {
        if (images.length === 0) return;
        
        currentImageIndex = index;
        const lightbox = document.getElementById('lightbox');
        const lightboxContent = document.querySelector('.lightbox-content');
        const lightboxImg = document.getElementById('lightboxImage');
        
        // Show loading spinner
        let loader = document.querySelector('.lightbox-loader');
        if (!loader) {
            loader = document.createElement('div');
            loader.className = 'lightbox-loader';
            lightboxContent.appendChild(loader);
        }
        
        lightboxImg.classList.remove('loaded');
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Set image source and handle loading
        lightboxImg.onload = function() {
            lightboxImg.classList.add('loaded');
            if (loader) loader.style.display = 'none';
        };
        
        lightboxImg.src = images[index];
        
        // Show/hide nav buttons as needed
        document.getElementById('prevBtn').style.visibility = index === 0 ? 'hidden' : 'visible';
        document.getElementById('nextBtn').style.visibility = index === images.length - 1 ? 'hidden' : 'visible';
    }
    
    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    function prevImage() {
        if (currentImageIndex > 0) {
            currentImageIndex--;
            document.getElementById('lightboxImage').src = images[currentImageIndex];
            
            // Update nav buttons
            document.getElementById('prevBtn').style.visibility = currentImageIndex === 0 ? 'hidden' : 'visible';
            document.getElementById('nextBtn').style.visibility = 'visible';
        }
    }
    
    function nextImage() {
        if (currentImageIndex < images.length - 1) {
            currentImageIndex++;
            document.getElementById('lightboxImage').src = images[currentImageIndex];
            
            // Update nav buttons
            document.getElementById('prevBtn').style.visibility = 'visible';
            document.getElementById('nextBtn').style.visibility = currentImageIndex === images.length - 1 ? 'hidden' : 'visible';
        }
    }
    
    // Keyboard navigation for lightbox
    document.addEventListener('keydown', function(e) {
        const lightbox = document.getElementById('lightbox');
        if (lightbox.classList.contains('active')) {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') prevImage();
            if (e.key === 'ArrowRight') nextImage();
        }
    });
    
    // Close lightbox when clicking outside the image
    document.getElementById('lightbox').addEventListener('click', function(e) {
        if (e.target.id === 'lightbox') {
            closeLightbox();
        }
    });
    
    // Delete confirmation
    function confirmDelete(id, name) {
        document.getElementById('deleteProductName').textContent = name;
        const deleteUrl = "{{ route('admin.products.destroy', ':id') }}".replace(':id', id);
        document.getElementById('deleteForm').action = deleteUrl;
        
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
    
    // Handle image loading errors
    function handleImageError(img) {
        // Try alternative storage path first
        const currentSrc = img.src;
        
        if(currentSrc.includes('/direct-storage/')) {
            // Try regular storage path
            const storagePath = currentSrc.replace('/direct-storage/', '/storage/');
            img.src = storagePath;
        } else if(currentSrc.includes('/storage/')) {
            // Try asset path
            const assetPath = currentSrc.replace('{{ url("/") }}/storage/', '{{ asset("storage/") }}/');
            img.src = assetPath;
        } else {
            // Use fallback image
            img.src = '/admin-assets/images/media/1.jpg';
            img.alt = 'Image not available';
        }
    }
    
    // Add error handlers to all images when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('.product-main-image, .gallery-thumb, .lightbox-image');
        images.forEach(img => {
            img.addEventListener('error', function() {
                handleImageError(this);
            });
        });
    });
</script>
@endpush

@push('scripts')
<script>
function changeMainImage(url, element) {
    document.getElementById('mainImage').src = url;
    
    // Update active state
    document.querySelectorAll('.gallery-thumb').forEach(thumb => {
        thumb.classList.remove('active');
    });
    element.classList.add('active');
}

function toggleStatus(productId) {
    if (confirm('Are you sure you want to change the product status?')) {
        // Show loading state
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="ti ti-loader-2 me-1 animate-spin"></i>Updating...';
        
        // Make AJAX call to toggle status
        fetch(`/admin/products/${productId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            // Check if the response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Received non-JSON response');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message
                showAlert('success', data.message);
                
                // Update button appearance based on new status
                const icon = data.status === 'active' ? 'pause' : 'play';
                const btnClass = data.status === 'active' ? 'warning' : 'success';
                const text = data.status === 'active' ? 'Deactivate' : 'Activate';
                
                button.className = `btn btn-${btnClass}`;
                button.innerHTML = `<i class="ti ti-${icon} me-1"></i>${text} Product`;
                button.setAttribute('onclick', `toggleStatus(${productId})`);
                
                // Update status badge
                const statusBadge = document.querySelector('.status-badge');
                if (statusBadge) {
                    statusBadge.className = `badge bg-${data.status === 'active' ? 'success' : 'secondary'} status-badge`;
                    statusBadge.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                }
            } else {
                showAlert('danger', data.message || 'An error occurred while updating the product status.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred while updating the product status. Please check if you are logged in.');
        })
        .finally(() => {
            button.disabled = false;
            if (button.innerHTML.includes('Updating')) {
                button.innerHTML = originalText;
            }
        });
    }
}

function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        // Show loading state
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="ti ti-loader-2 me-1 animate-spin"></i>Deleting...';
        
        // Make AJAX call to delete
        fetch(`/admin/products/${productId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            // Check if the response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Received non-JSON response');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                // Redirect to products index after short delay
                setTimeout(() => {
                    window.location.href = '{{ route("admin.products.index") }}';
                }, 1500);
            } else {
                showAlert('danger', data.message || 'An error occurred while deleting the product.');
                button.disabled = false;
                button.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred while deleting the product. Please check if you are logged in.');
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }
}

// Alert function
function showAlert(type, message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert at the top of the main content
    const mainContent = document.querySelector('.main-content .container-fluid');
    mainContent.insertBefore(alertDiv, mainContent.firstChild);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
@endpush
