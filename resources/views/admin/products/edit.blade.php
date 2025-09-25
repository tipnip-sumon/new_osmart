@extends('admin.layouts.app')

@section('title', 'Edit Product')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    /* International Standard Form Styles */
    .form-control:focus, .form-select:focus {
        border-color: #0066cc;
        box-shadow: 0 0 0 0.15rem rgba(0, 102, 204, 0.15);
        transition: all 0.2s ease-in-out;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.15rem rgba(220, 53, 69, 0.15);
    }

    .form-control.is-valid {
        border-color: #28a745;
        box-shadow: 0 0 0 0.15rem rgba(40, 167, 69, 0.15);
    }

    /* Standard Form Section Styling */
    .form-section {
        background: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .form-section-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 1rem 1.25rem;
        border-radius: 8px 8px 0 0;
    }

    .form-section-title {
        font-weight: 600;
        font-size: 1.1rem;
        color: #495057;
        margin: 0;
    }

    .form-section-body {
        padding: 1.25rem;
    }

    /* Field Labels with Standard Marking */
    .field-required {
        color: #dc3545;
        font-weight: 500;
    }

    .field-optional {
        color: #6c757d;
        font-weight: normal;
        font-size: 0.875rem;
    }

    .field-help {
        color: #6c757d;
        font-size: 0.8125rem;
        margin-top: 0.25rem;
    }

    /* Image Upload Styles */
    .image-upload-container {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .image-upload-container:hover {
        border-color: #0066cc;
        background: #e7f3ff;
    }

    .image-upload-container.drag-over {
        border-color: #28a745;
        background: #d4edda;
    }

    .image-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 12px;
        margin-top: 20px;
    }

    .image-preview-item {
        position: relative;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        height: 120px;
    }

    .image-preview-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .image-preview-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .image-remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .image-remove-btn:hover {
        background: #dc3545;
        transform: scale(1.1);
    }

    .primary-badge {
        position: absolute;
        top: 5px;
        left: 5px;
        background: #28a745;
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
    }

    .image-actions {
        position: absolute;
        bottom: 5px;
        right: 5px;
        display: flex;
        gap: 5px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .image-preview-item:hover .image-actions {
        opacity: 1;
    }

    .btn-primary-image, .btn-remove-image {
        background: rgba(0, 0, 0, 0.7);
        color: white;
        border: none;
        border-radius: 4px;
        padding: 4px 8px;
        font-size: 12px;
        cursor: pointer;
    }

    .btn-primary-image:hover {
        background: #007bff;
    }

    .btn-remove-image:hover {
        background: #dc3545;
    }

    .mlm-badge {
        background: linear-gradient(45deg, #3a0ca3, #4361ee);
        color: #fff;
        padding: 5px 10px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
    }
    
    .current-image-item {
        position: relative;
        display: block;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .current-image-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .image-overlay {
        position: absolute;
        top: 8px;
        right: 8px;
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .current-image-item:hover .image-overlay {
        opacity: 1;
    }

    .remove-current-image {
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 4px;
        padding: 6px 8px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .remove-current-image:hover {
        background: #dc3545;
        transform: scale(1.05);
    }

    .remove-current-image i {
        font-size: 14px;
    }
    
    .card-header {
        background: linear-gradient(45deg, #f8f9fa, #e9ecef);
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    
    .card-title {
        margin-bottom: 0;
        font-weight: 600;
        color: #495057;
    }
    
    .card {
        margin-bottom: 1.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .loading-content {
        background: white;
        padding: 30px;
        border-radius: 8px;
        text-align: center;
    }

    /* Standard Button Styling */
    .btn-suggestion {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        margin: 0.125rem;
        border-radius: 4px;
        border: 1px solid #dee2e6;
        background: #ffffff;
        color: #495057;
        transition: all 0.15s ease-in-out;
    }

    .btn-suggestion:hover {
        background: #e9ecef;
        border-color: #adb5bd;
        color: #495057;
    }

    /* Standard Color Display */
    .color-display-area {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.75rem;
    }

    .color-swatch {
        width: 20px;
        height: 20px;
        border: 1px solid #adb5bd;
        border-radius: 3px;
        display: inline-block;
        margin: 0.125rem;
        transition: border-color 0.15s ease-in-out;
    }

    .color-swatch:hover {
        border-color: #495057;
    }

    /* Progress Indicator */
    .form-progress {
        background: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.75rem;
        margin-bottom: 1rem;
    }

    /* International Standard Color System */
    :root {
        --primary-color: #0066cc;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --info-color: #17a2b8;
        --secondary-color: #6c757d;
    }

    /* Modern Tags Input Styling */
    .tags-input-wrapper {
        position: relative;
        margin-bottom: 1rem;
    }

    .tags-select {
        min-height: 50px !important;
        border: 2px solid #e3e6f0 !important;
        border-radius: 15px !important;
        transition: all 0.3s ease !important;
        background: #ffffff !important;
        font-size: 14px !important;
    }

    .tags-select:focus {
        border-color: #4e73df !important;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1) !important;
        background: #ffffff !important;
    }

    /* Select2 Enhanced Styling */
    .select2-container {
        width: 100% !important;
        font-size: 14px !important;
    }

    .select2-container .select2-selection--multiple {
        min-height: 50px !important;
        border: 2px solid #e3e6f0 !important;
        border-radius: 15px !important;
        padding: 10px 15px !important;
        background: #ffffff !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
    }

    .select2-container--focus .select2-selection--multiple {
        border-color: #4e73df !important;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1), 0 2px 8px rgba(0, 0, 0, 0.15) !important;
        background: #ffffff !important;
    }

    .select2-container .select2-selection--multiple .select2-selection__rendered {
        padding: 0 !important;
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 8px !important;
        align-items: center !important;
        min-height: 30px !important;
    }

    .select2-container .select2-selection--multiple .select2-selection__choice {
        background: linear-gradient(135deg, #4e73df, #3653d3) !important;
        border: none !important;
        color: white !important;
        border-radius: 25px !important;
        padding: 8px 15px !important;
        margin: 0 !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        box-shadow: 0 2px 6px rgba(78, 115, 223, 0.3) !important;
        transition: all 0.2s ease !important;
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        max-height: 32px !important;
    }

    .select2-container .select2-selection--multiple .select2-selection__choice:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.4) !important;
        background: linear-gradient(135deg, #3653d3, #2e59d9) !important;
    }

    .select2-container .select2-selection--multiple .select2-selection__choice__remove {
        color: rgba(255, 255, 255, 0.9) !important;
        margin-right: 0 !important;
        margin-left: 8px !important;
        font-size: 16px !important;
        font-weight: bold !important;
        transition: all 0.2s ease !important;
        width: 20px !important;
        height: 20px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 50% !important;
    }

    .select2-container .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #ffffff !important;
        background: rgba(255, 255, 255, 0.2) !important;
        transform: scale(1.1) !important;
    }

    .select2-container .select2-search--inline .select2-search__field {
        margin-top: 0 !important;
        padding: 8px 0 !important;
        border: none !important;
        outline: none !important;
        font-size: 14px !important;
        background: transparent !important;
        min-width: 200px !important;
        height: 32px !important;
        line-height: 32px !important;
    }

    .select2-container .select2-search--inline .select2-search__field::placeholder {
        color: #6c757d !important;
        font-style: italic !important;
    }

    /* Dropdown Styling */
    .select2-dropdown {
        border: 2px solid #4e73df !important;
        border-radius: 15px !important;
        box-shadow: 0 10px 30px rgba(78, 115, 223, 0.2) !important;
        background: white !important;
        overflow: hidden !important;
        margin-top: 5px !important;
    }

    .select2-results {
        padding: 10px 0 !important;
        max-height: 250px !important;
    }

    .select2-results__option {
        padding: 15px 20px !important;
        font-size: 14px !important;
        transition: all 0.2s ease !important;
        border-bottom: 1px solid #f1f3f4 !important;
        cursor: pointer !important;
    }

    .select2-results__option:last-child {
        border-bottom: none !important;
    }

    .select2-results__option--highlighted {
        background: linear-gradient(135deg, #4e73df, #3653d3) !important;
        color: white !important;
    }

    .select2-results__option[aria-selected="true"] {
        background: #e7f0ff !important;
        color: #4e73df !important;
        position: relative !important;
        font-weight: 500 !important;
    }

    .select2-results__option[aria-selected="true"]:before {
        content: '✓' !important;
        position: absolute !important;
        right: 20px !important;
        color: #28a745 !important;
        font-weight: bold !important;
        font-size: 16px !important;
    }

    /* Selected Tags Preview */
    .selected-tags-preview {
        background: linear-gradient(135deg, #f8f9fc, #eaecf4) !important;
        border: 1px solid #e3e6f0 !important;
        border-radius: 15px !important;
        padding: 15px !important;
        margin-top: 15px !important;
    }

    .selected-tags-container {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 10px !important;
    }

    .preview-tag {
        background: linear-gradient(135deg, #1cc88a, #17a673) !important;
        color: white !important;
        padding: 6px 12px !important;
        border-radius: 20px !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        box-shadow: 0 2px 4px rgba(28, 200, 138, 0.2) !important;
    }

    /* Loading state */
    .select2-results__message {
        padding: 20px !important;
        text-align: center !important;
        color: #6c757d !important;
        font-style: italic !important;
        font-size: 14px !important;
    }

    /* No results */
    .select2-results__option--highlighted.select2-results__message {
        background: transparent !important;
        color: #6c757d !important;
    }

    /* Form text styling for tags */
    .tags-input-wrapper .form-text {
        margin-top: 8px !important;
        color: #6c757d !important;
        font-size: 13px !important;
        display: flex !important;
        align-items: center !important;
        gap: 5px !important;
    }
</style>
@endpush
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div>
                <h1 class="page-title fw-semibold fs-20 mb-1 text-dark">Edit Product</h1>
                <p class="text-muted mb-0">Update product information and settings</p>
            </div>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}" class="text-decoration-none">Products</a></li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <!-- Field Legend -->
        <div class="alert alert-light border border-primary-subtle mb-4">
            <div class="d-flex align-items-center">
                <i class="ti ti-info-circle text-primary me-2"></i>
                <div>
                    <strong class="text-dark">Field Requirements:</strong>
                    <span class="field-required ms-3">* Required Field</span>
                    <span class="field-optional ms-3">(Optional)</span>
                    <span class="text-muted ms-3">• Standard fields follow international e-commerce standards</span>
                </div>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Alert Container -->
        <div id="alert-container"></div>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-12 col-md-12">
                    <div class="form-section">
                        <div class="form-section-header">
                            <h4 class="form-section-title">
                                <i class="ti ti-package text-primary me-2"></i>
                                Basic Information
                            </h4>
                        </div>
                        <div class="form-section-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-medium">
                                        Product Name <span class="field-required">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $product->name) }}" placeholder="Enter product name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Enter a clear, descriptive product name</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="slug" class="form-label fw-medium">
                                        URL Slug <span class="field-optional">(Optional)</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                               id="slug" name="slug" value="{{ old('slug', $product->slug) }}" placeholder="Auto-generated from name">
                                        <button class="btn btn-outline-secondary" type="button" id="generate-slug-btn" title="Generate slug from product name">
                                            <i class="ti ti-refresh"></i>
                                        </button>
                                    </div>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Leave empty to auto-generate, or customize (e.g., "my-product-name")</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sku" class="form-label fw-medium">
                                        SKU <span class="field-optional">(Optional)</span>
                                    </label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                           id="sku" name="sku" value="{{ old('sku', $product->sku) }}" placeholder="Auto-generated">
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Stock Keeping Unit - Leave empty to auto-generate</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="barcode" class="form-label fw-medium">
                                        Barcode <span class="field-optional">(Optional)</span>
                                    </label>
                                    <input type="text" class="form-control @error('barcode') is-invalid @enderror" 
                                           id="barcode" name="barcode" value="{{ old('barcode', $product->barcode) }}" placeholder="Product barcode">
                                    @error('barcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Product barcode or UPC code</div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="short_description" class="form-label fw-medium">
                                        Short Description <span class="field-optional">(Optional)</span>
                                    </label>
                                    <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                              id="short_description" name="short_description" rows="3" 
                                              placeholder="Brief product description for listings">{{ old('short_description', $product->short_description) }}</textarea>
                                    @error('short_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Appears in product listings (recommended: 150-300 characters)</div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label fw-medium">
                                        Detailed Description <span class="field-optional">(Optional)</span>
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="6" 
                                              placeholder="Comprehensive product description with features and benefits">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Detailed product information for customers</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Information -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Pricing Information</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Regular Price <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" placeholder="0.00" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sale_price" class="form-label fw-medium">
                                        Sale Price <span class="field-optional">(Optional)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">৳</span>
                                        <input type="number" class="form-control @error('sale_price') is-invalid @enderror" 
                                               id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0" 
                                               placeholder="0.00">
                                    </div>
                                    @error('sale_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Discounted price (must be less than regular price)</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cost_price" class="form-label fw-medium">
                                        Cost Price <span class="field-optional">(Optional)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">৳</span>
                                        <input type="number" class="form-control @error('cost_price') is-invalid @enderror" 
                                               id="cost_price" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" step="0.01" min="0" 
                                               placeholder="0.00">
                                    </div>
                                    @error('cost_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Your cost for this product (for profit calculation)</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="compare_price" class="form-label fw-medium">
                                        Compare at Price <span class="field-optional">(Optional)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">৳</span>
                                        <input type="number" class="form-control @error('compare_price') is-invalid @enderror" 
                                               id="compare_price" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}" step="0.01" min="0" 
                                               placeholder="0.00">
                                    </div>
                                    @error('compare_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Original price to show savings (for marketing)</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Management -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <h4 class="form-section-title">
                                <i class="ti ti-package text-info me-2"></i>
                                Inventory Management
                            </h4>
                        </div>
                        <div class="form-section-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="stock_quantity" class="form-label fw-medium">
                                        Stock Quantity <span class="field-required">*</span>
                                    </label>
                                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                           id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0" required>
                                    @error('stock_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Current available quantity in stock</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="min_stock_level" class="form-label fw-medium">
                                        Minimum Stock Level <span class="field-optional">(Optional)</span>
                                    </label>
                                    <input type="number" class="form-control @error('min_stock_level') is-invalid @enderror" 
                                           id="min_stock_level" name="min_stock_level" value="{{ old('min_stock_level', $product->min_stock_level) }}" min="0">
                                    @error('min_stock_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Alert when stock falls below this level</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="track_quantity" name="track_quantity" value="1" 
                                               {{ old('track_quantity', $product->track_quantity) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium" for="track_quantity">
                                            Track Quantity
                                        </label>
                                    </div>
                                    <div class="field-help">Automatically reduce stock when orders are placed</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="allow_backorder" name="allow_backorder" value="1" 
                                               {{ old('allow_backorder', $product->allow_backorder) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium" for="allow_backorder">
                                            Allow Backorders
                                        </label>
                                    </div>
                                    <div class="field-help">Allow sales when out of stock</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MLM Commission Settings -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <h4 class="form-section-title">
                                <i class="ti ti-network text-purple me-2"></i>
                                MLM Commission Settings
                            </h4>
                        </div>
                        <div class="form-section-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="generates_commission" name="generates_commission" value="1" 
                                               {{ old('generates_commission', $product->generates_commission) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="generates_commission">
                                            Generates Commission
                                        </label>
                                    </div>
                                    <div class="form-text">Enable MLM commission for this product</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_starter_kit" name="is_starter_kit" value="1" 
                                               {{ old('is_starter_kit', $product->is_starter_kit) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_starter_kit">
                                            Starter Kit Product
                                        </label>
                                    </div>
                                    <div class="form-text">Special product for new member registration</div>
                                </div>
                                <div class="col-md-6 mb-3" id="starter_kit_tier_section" style="{{ old('is_starter_kit', $product->is_starter_kit) ? '' : 'display: none;' }}">
                                    <label for="starter_kit_tier" class="form-label fw-medium">
                                        Starter Kit Tier <span class="field-optional">(Optional)</span>
                                    </label>
                                    <select class="form-select @error('starter_kit_tier') is-invalid @enderror" 
                                            id="starter_kit_tier" name="starter_kit_tier">
                                        <option value="">Select Tier</option>
                                        <option value="basic" {{ old('starter_kit_tier', $product->starter_kit_tier) == 'basic' ? 'selected' : '' }}>Basic (2,000 TK)</option>
                                        <option value="standard" {{ old('starter_kit_tier', $product->starter_kit_tier) == 'standard' ? 'selected' : '' }}>Standard (5,000 TK)</option>
                                        <option value="premium" {{ old('starter_kit_tier', $product->starter_kit_tier) == 'premium' ? 'selected' : '' }}>Premium (10,000 TK)</option>
                                        <option value="platinum" {{ old('starter_kit_tier', $product->starter_kit_tier) == 'platinum' ? 'selected' : '' }}>Platinum (20,000 TK)</option>
                                    </select>
                                    @error('starter_kit_tier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Select the tier for this starter kit</div>
                                </div>
                                <div class="col-md-12 mb-3" id="starter_kit_level_section" style="{{ old('is_starter_kit', $product->is_starter_kit) ? '' : 'display: none;' }}">
                                    <label for="starter_kit_level" class="form-label fw-medium">
                                        Custom Starter Kit Level <span class="field-optional">(Optional)</span>
                                    </label>
                                    <input type="text" class="form-control @error('starter_kit_level') is-invalid @enderror" 
                                           id="starter_kit_level" name="starter_kit_level" value="{{ old('starter_kit_level', $product->starter_kit_level) }}" 
                                           placeholder="e.g., Silver Package, Gold Package, etc.">
                                    @error('starter_kit_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Optional: Enter a custom level name for this starter kit</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="pv_points" class="form-label">PV Points</label>
                                    <input type="number" class="form-control @error('pv_points') is-invalid @enderror" 
                                           id="pv_points" name="pv_points" value="{{ old('pv_points', $product->pv_points) }}" step="0.01" min="0" placeholder="Auto-calculated">
                                    @error('pv_points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Personal Volume points</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="bv_points" class="form-label">BV Points</label>
                                    <input type="number" class="form-control @error('bv_points') is-invalid @enderror" 
                                           id="bv_points" name="bv_points" value="{{ old('bv_points', $product->bv_points) }}" step="0.01" min="0" placeholder="Auto-calculated">
                                    @error('bv_points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Business Volume points</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="direct_commission_rate" class="form-label">Direct Commission (%)</label>
                                    <input type="number" class="form-control @error('direct_commission_rate') is-invalid @enderror" 
                                           id="direct_commission_rate" name="direct_commission_rate" value="{{ old('direct_commission_rate', $product->direct_commission_rate) }}" 
                                           step="0.01" min="0" max="100">
                                    @error('direct_commission_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Information -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <h4 class="form-section-title">
                                <i class="ti ti-seo text-warning me-2"></i>
                                SEO Information
                            </h4>
                        </div>
                        <div class="form-section-body">
                            <div class="mb-3">
                                <label for="meta_title" class="form-label fw-medium">
                                    Meta Title <span class="field-optional">(Optional)</span>
                                </label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                       id="meta_title" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" 
                                       placeholder="SEO friendly title">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="field-help">Recommended length: 50-60 characters</div>
                            </div>

                            <div class="mb-3">
                                <label for="meta_description" class="form-label fw-medium">
                                    Meta Description <span class="field-optional">(Optional)</span>
                                </label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                          id="meta_description" name="meta_description" rows="3" 
                                          placeholder="SEO friendly description">{{ old('meta_description', $product->meta_description) }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="field-help">Recommended length: 150-160 characters</div>
                            </div>

                            <div class="mb-3">
                                <label for="meta_keywords" class="form-label fw-medium">
                                    Meta Keywords <span class="field-optional">(Optional)</span>
                                </label>
                                <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                       id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords) }}" 
                                       placeholder="keyword1, keyword2, keyword3">
                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="field-help">Separate keywords with commas</div>
                            </div>
                        </div>
                    </div>

                    <!-- Classifications -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <h4 class="form-section-title">
                                <i class="ti ti-category text-info me-2"></i>
                                Classifications
                            </h4>
                        </div>
                        <div class="form-section-body">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @if($category->children)
                                            @foreach($category->children as $child)
                                                <option value="{{ $child->id }}" 
                                                    {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>
                                                    -- {{ $child->name }}
                                                </option>
                                                @if($child->children)
                                                    @foreach($child->children as $grandchild)
                                                        <option value="{{ $grandchild->id }}" 
                                                            {{ old('category_id', $product->category_id) == $grandchild->id ? 'selected' : '' }}>
                                                            ---- {{ $grandchild->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="brand_id" class="form-label">Brand</label>
                                <select class="form-select @error('brand_id') is-invalid @enderror" 
                                        id="brand_id" name="brand_id">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" 
                                            {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="vendor_id" class="form-label">Vendor <span class="text-danger">*</span></label>
                                <select class="form-select @error('vendor_id') is-invalid @enderror" 
                                        id="vendor_id" name="vendor_id" required>
                                    <option value="">Select Vendor</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" 
                                            {{ old('vendor_id', $product->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->firstname }} {{ $vendor->lastname }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="tags" class="form-label fw-semibold mb-2">
                                    <i class="ti ti-tags me-2 text-primary"></i>Product Tags
                                </label>
                                <div class="tags-input-wrapper">
                                    <select class="form-select tags-select @error('tags') is-invalid @enderror" 
                                            id="tags" name="tags[]" multiple data-placeholder="Search and select tags...">
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}" 
                                                {{ in_array($tag->id, old('tags', $product->tags instanceof \Illuminate\Database\Eloquent\Collection ? $product->tags->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                                                {{ $tag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text mt-2">
                                        <i class="ti ti-info-circle me-1"></i>
                                        Start typing to search for tags or select from the dropdown
                                    </div>
                                </div>
                                <div class="selected-tags-preview mt-3" id="selected-tags-preview" style="display: none;">
                                    <small class="text-muted d-block mb-2">Selected Tags:</small>
                                    <div class="selected-tags-container" id="selected-tags-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status & Visibility -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Status & Visibility</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status">
                                    <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="featured" class="form-label">Featured</label>
                                <select class="form-select @error('featured') is-invalid @enderror" 
                                        id="featured" name="featured">
                                    <option value="1" {{ old('featured', $product->featured) == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('featured', $product->featured) == 0 ? 'selected' : '' }}>No</option>
                                </select>
                                @error('featured')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Show in featured section</div>
                            </div>

                            <div class="mb-3">
                                <label for="is_new" class="form-label">Mark as New</label>
                                <select class="form-select @error('is_new') is-invalid @enderror" 
                                        id="is_new" name="is_new">
                                    <option value="1" {{ old('is_new', $product->is_new) == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('is_new', $product->is_new) == 0 ? 'selected' : '' }}>No</option>
                                </select>
                                @error('is_new')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Show "New" badge on product</div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="has_variations" name="has_variations" value="1" 
                                           {{ old('has_variations', $product->has_variations) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="has_variations">
                                        Has Variations
                                    </label>
                                </div>
                                <div class="form-text">Enable color/size variations</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shipping & Dimensions -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Shipping & Dimensions</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="requires_shipping" name="requires_shipping" value="1" 
                                           {{ old('requires_shipping', $product->requires_shipping) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="requires_shipping">
                                        Requires Shipping
                                    </label>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="weight" class="form-label">Weight (kg)</label>
                                    <input type="number" class="form-control @error('weight') is-invalid @enderror" 
                                           id="weight" name="weight" value="{{ old('weight', $product->weight) }}" step="0.01" min="0">
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_class" class="form-label">Shipping Class</label>
                                    <select class="form-select @error('shipping_class') is-invalid @enderror" 
                                            id="shipping_class" name="shipping_class">
                                        <option value="">Select Class</option>
                                        <option value="standard" {{ old('shipping_class', $product->shipping_class) == 'standard' ? 'selected' : '' }}>Standard</option>
                                        <option value="express" {{ old('shipping_class', $product->shipping_class) == 'express' ? 'selected' : '' }}>Express</option>
                                        <option value="bulky" {{ old('shipping_class', $product->shipping_class) == 'bulky' ? 'selected' : '' }}>Bulky</option>
                                    </select>
                                    @error('shipping_class')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="height" class="form-label">Height (cm)</label>
                                    <input type="number" class="form-control @error('height') is-invalid @enderror" 
                                           id="height" name="height" value="{{ old('height', $product->height) }}" step="0.01" min="0">
                                    @error('height')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="width" class="form-label">Width (cm)</label>
                                    <input type="number" class="form-control @error('width') is-invalid @enderror" 
                                           id="width" name="width" value="{{ old('width', $product->width) }}" step="0.01" min="0">
                                    @error('width')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="depth" class="form-label">Depth (cm)</label>
                                    <input type="number" class="form-control @error('depth') is-invalid @enderror" 
                                           id="depth" name="depth" value="{{ old('depth', $product->depth) }}" step="0.01" min="0">
                                    @error('depth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Images -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Product Images</div>
                            <small class="text-muted">Upload high-quality product images. First image will be the main product image.</small>
                        </div>
                        <div class="card-body">
                            <div class="image-upload-container" id="image-upload-area">
                                <div class="upload-content">
                                    <i class="ti ti-cloud-upload" style="font-size: 48px; color: #6c757d; margin-bottom: 15px;"></i>
                                    <h5>Drop images here or click to upload</h5>
                                    <p class="text-muted">Supports: JPG, PNG, GIF, WEBP (Max: 10MB each)</p>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button type="button" class="btn btn-primary" onclick="document.getElementById('image-input').click()">
                                            <i class="ti ti-upload"></i> Choose Files
                                        </button>
                                        <button type="button" class="btn btn-info" onclick="openResizeOptions()">
                                            <i class="ti ti-crop"></i> Resize Options
                                        </button>
                                        <a href="{{ route('admin.image-upload.demo') }}" target="_blank" class="btn btn-success">
                                            <i class="ti ti-external-link"></i> Image Demo
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="file" id="image-input" name="images[]" multiple accept="image/*" style="display: none;">
                            
                            <!-- Resize Options Panel -->
                            <div id="resize-options-panel" class="mt-3" style="display: none;">
                                <div class="alert alert-info">
                                    <h6><i class="ti ti-info-circle me-2"></i>Image Resize Options</h6>
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <label class="form-label">Width (px)</label>
                                            <input type="number" class="form-control form-control-sm" id="resize-width" value="800" min="50" max="2000">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Height (px)</label>
                                            <input type="number" class="form-control form-control-sm" id="resize-height" value="600" min="50" max="2000">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Quality (%)</label>
                                            <input type="number" class="form-control form-control-sm" id="resize-quality" value="85" min="10" max="100">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox" id="maintain-ratio" checked>
                                                <label class="form-check-label" for="maintain-ratio">
                                                    Maintain Aspect Ratio
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-primary" onclick="resizeUploadedImages()">
                                            <i class="ti ti-crop me-1"></i>Resize Images
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="generateThumbnails()">
                                            <i class="ti ti-photo me-1"></i>Generate Thumbnails
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="optimizeImages()">
                                            <i class="ti ti-zap me-1"></i>Optimize Images
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Current Images Display -->
                            @if($product->images)
                                @php
                                    // Handle both string JSON and array formats
                                    if (is_string($product->images)) {
                                        $existingImages = json_decode($product->images, true);
                                    } else {
                                        $existingImages = $product->images;
                                    }
                                @endphp
                                @if(is_array($existingImages) && count($existingImages) > 0)
                                    <div class="mt-4">
                                        <h6 class="mb-3">
                                            <i class="ti ti-photo me-2"></i>Current Images 
                                            <span class="badge bg-secondary">{{ count($existingImages) }}</span>
                                        </h6>
                                        
                                        <div class="current-images-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px;">
                                            @foreach($existingImages as $index => $image)
                                                <div class="current-image-item" style="position: relative; border: 1px solid #dee2e6; border-radius: 8px; overflow: hidden; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                    @if(is_array($image) && isset($image['sizes']))
                                                        <!-- New format with multiple sizes -->
                                                        @php
                                                            // Safely get image URL with proper type checking for new format
                                                            $imageUrl = '';
                                                            if (isset($image['sizes']) && is_array($image['sizes'])) {
                                                                // Try medium size first (best for display)
                                                                if (isset($image['sizes']['medium']['url']) && is_string($image['sizes']['medium']['url'])) {
                                                                    $imageUrl = $image['sizes']['medium']['url'];
                                                                } elseif (isset($image['sizes']['large']['url']) && is_string($image['sizes']['large']['url'])) {
                                                                    $imageUrl = $image['sizes']['large']['url'];
                                                                } elseif (isset($image['sizes']['original']['url']) && is_string($image['sizes']['original']['url'])) {
                                                                    $imageUrl = $image['sizes']['original']['url'];
                                                                } elseif (isset($image['sizes']['small']['url']) && is_string($image['sizes']['small']['url'])) {
                                                                    $imageUrl = $image['sizes']['small']['url'];
                                                                }
                                                                // Fallback to path-based approach if URL not found
                                                                elseif (isset($image['sizes']['medium']['path']) && is_string($image['sizes']['medium']['path'])) {
                                                                    $imageUrl = asset('storage/' . $image['sizes']['medium']['path']);
                                                                } elseif (isset($image['sizes']['original']['path']) && is_string($image['sizes']['original']['path'])) {
                                                                    $imageUrl = asset('storage/' . $image['sizes']['original']['path']);
                                                                }
                                                            } elseif (isset($image['path']) && is_string($image['path'])) {
                                                                $imageUrl = asset('uploads/' . $image['path']);
                                                            }
                                                            
                                                            // Fallback if no valid URL found
                                                            if (empty($imageUrl)) {
                                                                $imageUrl = asset('admin-assets/images/media/media-43.jpg'); // Default placeholder
                                                            }
                                                        @endphp
                                                        <img src="{{ $imageUrl }}" alt="Product Image {{ $index + 1 }}" 
                                                             style="width: 100%; height: 150px; object-fit: cover;">
                                                        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; padding: 5px; font-size: 0.75rem;">
                                                            @if($index === 0)
                                                                <i class="ti ti-star-filled text-warning"></i> Primary
                                                            @else
                                                                Image {{ $index + 1 }}
                                                            @endif
                                                        </div>
                                                    @else
                                                        <!-- Legacy format - simple path -->
                                                        @php
                                                            // Handle legacy format with type checking
                                                            $legacyImageUrl = '';
                                                            if (is_string($image)) {
                                                                // Try storage path first, then uploads
                                                                $legacyImageUrl = asset('storage/' . $image);
                                                            } elseif (is_array($image) && isset($image['url']) && is_string($image['url'])) {
                                                                $legacyImageUrl = $image['url'];
                                                            } elseif (is_array($image) && isset($image['path']) && is_string($image['path'])) {
                                                                $legacyImageUrl = asset('storage/' . $image['path']);
                                                            } else {
                                                                $legacyImageUrl = asset('admin-assets/images/media/media-43.jpg'); // Default placeholder
                                                            }
                                                        @endphp
                                                        <img src="{{ $legacyImageUrl }}" alt="Product Image {{ $index + 1 }}" 
                                                             style="width: 100%; height: 150px; object-fit: cover;">
                                                        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; padding: 5px; font-size: 0.75rem;">
                                                            @if($index === 0)
                                                                <i class="ti ti-star-filled text-warning"></i> Primary
                                                            @else
                                                                Image {{ $index + 1 }}
                                                            @endif
                                                        </div>
                                                    @endif
                                                    <!-- Remove button -->
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="removeExistingImage({{ $index }})"
                                                            style="position: absolute; top: 5px; right: 5px; border-radius: 50%; width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                                        <i class="ti ti-x" style="font-size: 12px;"></i>
                                                    </button>
                                                    <!-- Undo button (initially hidden) -->
                                                    <button type="button" class="btn btn-sm btn-success undo-remove-btn" 
                                                            onclick="undoRemoveImage({{ $index }})"
                                                            style="position: absolute; top: 40px; right: 5px; border-radius: 4px; padding: 4px 8px; font-size: 10px; display: none;">
                                                        <i class="ti ti-refresh" style="font-size: 10px;"></i> Undo
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="alert alert-info mt-3">
                                            <i class="ti ti-info-circle me-2"></i>
                                            <strong>Note:</strong> To replace images, upload new ones below. Existing images will be kept unless you remove them using the X button.
                                        </div>
                                    </div>
                                @endif
                            @endif
                            
                            <!-- Image Preview Grid -->
                            <div id="image-preview-grid" class="image-preview-grid"></div>
                            
                            @error('images.*')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Hidden input to track removed images -->
            <input type="hidden" id="removed-images" name="removed_images" value="">
            
            <!-- Form Buttons -->
            <div class="row mt-3 mb-5">
                <div class="col-12 text-end">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-danger me-2">
                        <i class="ti ti-x me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Update Product
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay" style="display: none;">
        <div class="loading-content">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5 class="mt-3">Processing Images...</h5>
            <p class="text-muted">Please wait while we process your images.</p>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
// Global variables
let uploadedImages = [];
let primaryImageIndex = 0;
let removedImages = [];

// Global showToast function
window.showToast = function(title, message, type) {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : type === 'info' ? 'info' : 'danger'} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}:</strong> ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    // Create toast container if it doesn't exist
    if ($('.toast-container').length === 0) {
        $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3"></div>');
    }
    
    $('.toast-container').append(toastHtml);
    $('.toast').last().toast('show');
};

// Global functions for image resize options
window.openResizeOptions = function() {
    $('#resize-options-panel').slideToggle();
};

window.resizeUploadedImages = function() {
    showToast('Feature Coming Soon', 'Image resize functionality will be available soon.', 'info');
};

window.generateThumbnails = function() {
    showToast('Feature Coming Soon', 'Thumbnail generation will be available soon.', 'info');
};

window.optimizeImages = function() {
    showToast('Feature Coming Soon', 'Image optimization will be available soon.', 'info');
};

// Function to generate slug from name\nfunction generateSlugFromName(name) {\n    return name.toLowerCase()\n        .replace(/[^a-z0-9\\s-]/g, '')\n        .replace(/\\s+/g, '-')\n        .replace(/-+/g, '-')\n        .trim('-');\n}\n\n$(document).ready(function() {
    // Wait a bit to ensure all scripts are loaded
    setTimeout(function() {
        console.log('Initializing page scripts...');

        // Initialize select2 for tags
        try {
            if (typeof $.fn.select2 !== 'undefined' && $('#tags').length > 0) {
                $('#tags').select2({
                    placeholder: 'Search and select tags...',
                    allowClear: true,
                    width: '100%',
                    minimumInputLength: 0,
                    dropdownParent: $('.tags-input-wrapper')
                });

                $('#tags').on('change', function() {
                    updateTagsPreview();
                });

                updateTagsPreview();
            }
        } catch (error) {
            console.error('Error initializing Select2:', error);
        }

        // Function to update tags preview
        function updateTagsPreview() {
            const selectedTags = $('#tags').select2('data');
            const previewContainer = $('#selected-tags-preview');
            const tagsContainer = $('#selected-tags-container');
            
            if (selectedTags && selectedTags.length > 0) {
                let previewHtml = '';
                selectedTags.forEach(function(tag) {
                    previewHtml += '<span class="preview-tag"><i class="ti ti-tag me-1"></i>' + tag.text + '</span>';
                });
                tagsContainer.html(previewHtml);
                previewContainer.show();
            } else {
                previewContainer.hide();
            }
        }

        // Generate slug from name
        $('#name').on('keyup', function() {
            var slug = $(this).val().toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            $('#slug').val(slug);
        });

        // Image upload functionality
        $('#image-input').on('change', function(e) {
            const files = Array.from(e.target.files);
            handleImageUpload(files);
        });

        // Drag and drop functionality
        const uploadArea = $('#image-upload-area');
        
        uploadArea.on('click', function() {
            $('#image-input').click();
        });

        uploadArea.on('dragover', function(e) {
            e.preventDefault();
            $(this).addClass('drag-over');
        });

        uploadArea.on('dragleave', function(e) {
            e.preventDefault();
            $(this).removeClass('drag-over');
        });

        uploadArea.on('drop', function(e) {
            e.preventDefault();
            $(this).removeClass('drag-over');
            const files = Array.from(e.originalEvent.dataTransfer.files);
            handleImageUpload(files);
        });

        // Image upload handler
        function handleImageUpload(files) {
            files.forEach((file, index) => {
                if (validateImageFile(file)) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        uploadedImages.push({
                            file: file,
                            url: e.target.result,
                            name: file.name,
                            size: file.size,
                            index: uploadedImages.length
                        });
                        displayImagePreview();
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        function validateImageFile(file) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            const maxSize = 10 * 1024 * 1024; // 10MB
            
            if (!allowedTypes.includes(file.type)) {
                showToast('Invalid File Type', 'Please select valid image files (JPG, PNG, GIF, WEBP)', 'error');
                return false;
            }
            
            if (file.size > maxSize) {
                showToast('File Too Large', `${file.name} is larger than 10MB`, 'error');
                return false;
            }
            
            return true;
        }

        function displayImagePreview() {
            const grid = $('#image-preview-grid');
            grid.empty();
            
            uploadedImages.forEach((image, index) => {
                const isPrimary = index === primaryImageIndex;
                const previewHtml = `
                    <div class="image-preview-item" data-index="${index}">
                        <img src="${image.url}" alt="${image.name}">
                        <div class="image-actions">
                            <button type="button" class="btn-primary-image" onclick="setPrimaryImage(${index})" 
                                    ${isPrimary ? 'style="background: #28a745;"' : ''}>
                                ${isPrimary ? 'Primary' : 'Set Primary'}
                            </button>
                            <button type="button" class="btn-remove-image" onclick="removeImage(${index})">Remove</button>
                        </div>
                        ${isPrimary ? '<div class="primary-badge">Primary</div>' : ''}
                    </div>
                `;
                grid.append(previewHtml);
            });
        }

        // Global functions for image management
        window.setPrimaryImage = function(index) {
            primaryImageIndex = index;
            displayImagePreview();
        };

        window.removeImage = function(index) {
            uploadedImages.splice(index, 1);
            if (primaryImageIndex >= uploadedImages.length) {
                primaryImageIndex = 0;
            }
            // Reindex remaining images
            uploadedImages.forEach((img, i) => {
                img.index = i;
            });
            displayImagePreview();
        };

        // Form submission
        $('#productForm').on('submit', function(e) {
            e.preventDefault();
            
            $('#loading-overlay').show();
            
            // Update removed images
            if (typeof window.removedImages !== 'undefined' && window.removedImages.length > 0) {
                $('#removed-images').val(JSON.stringify(window.removedImages));
            }
            
            const formData = new FormData(this);
            
            // Add new images
            uploadedImages.forEach((imageData, index) => {
                formData.append('new_images[]', imageData.file);
            });
            
            if (uploadedImages.length > 0) {
                formData.append('primary_image_index', primaryImageIndex);
            }
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#loading-overlay').hide();
                    showToast('Success', 'Product updated successfully!', 'success');
                    
                    setTimeout(function() {
                        window.location.href = '{{ route("admin.products.index") }}';
                    }, 1500);
                },
                error: function(xhr) {
                    $('#loading-overlay').hide();
                    
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessages = [];
                        Object.keys(errors).forEach(function(key) {
                            errorMessages.push(`${key}: ${errors[key][0]}`);
                        });
                        showToast('Validation Error', errorMessages.join('<br>'), 'error');
                    } else {
                        showToast('Error', 'Failed to update product. Please try again.', 'error');
                    }
                }
            });
        });

        // Remove current image handler
        $(document).on('click', '.remove-current-image', function() {
            const imageData = $(this).data('image');
            
            if (typeof window.removedImages === 'undefined') {
                window.removedImages = [];
            }
            
            window.removedImages.push(imageData);
            $(this).closest('.col-md-3').fadeOut();
            showToast('Image Removed', 'Image will be removed when you save the product.', 'info');
        });

    }, 500);
});

// Additional global functions
window.removeExistingImage = function(index) {
    if (confirm('Are you sure you want to remove this image?')) {
        if (typeof window.removedImages === 'undefined') {
            window.removedImages = [];
        }
        
        // Get the actual image URL/path from the image element
        const imageItems = document.querySelectorAll('.current-image-item');
        const imageItem = imageItems[index];
        
        if (imageItem) {
            const imgElement = imageItem.querySelector('img');
            if (imgElement) {
                // Extract the image path from the src attribute
                let imageSrc = imgElement.src;
                
                // Convert full URL to relative path for backend processing
                if (imageSrc.includes('/storage/')) {
                    imageSrc = imageSrc.substring(imageSrc.indexOf('/storage/'));
                }
                
                // Store the image path/URL instead of just the index
                window.removedImages.push(imageSrc);
                
                // Visual feedback
                imageItem.style.opacity = '0.5';
                imageItem.style.filter = 'grayscale(100%)';
                
                // Add a removed indicator
                imageItem.setAttribute('data-removed', 'true');
                
                // Hide remove button and show undo button
                const removeBtn = imageItem.querySelector('.btn-danger');
                const undoBtn = imageItem.querySelector('.undo-remove-btn');
                if (removeBtn) removeBtn.style.display = 'none';
                if (undoBtn) undoBtn.style.display = 'flex';
                
                console.log('Marked for removal:', imageSrc);
            }
        }
        
        showToast('Image Removed', 'Image will be deleted when you save the product. Click "Undo" to restore.', 'warning');
    }
};

window.undoRemoveImage = function(index) {
    const imageItems = document.querySelectorAll('.current-image-item');
    const imageItem = imageItems[index];
    
    if (imageItem && imageItem.getAttribute('data-removed') === 'true') {
        const imgElement = imageItem.querySelector('img');
        if (imgElement && typeof window.removedImages !== 'undefined') {
            let imageSrc = imgElement.src;
            
            // Convert full URL to relative path
            if (imageSrc.includes('/storage/')) {
                imageSrc = imageSrc.substring(imageSrc.indexOf('/storage/'));
            }
            
            // Remove from the removedImages array
            window.removedImages = window.removedImages.filter(img => img !== imageSrc);
        }
        
        // Restore visual state
        imageItem.style.opacity = '1';
        imageItem.style.filter = 'none';
        imageItem.removeAttribute('data-removed');
        
        // Show remove button and hide undo button
        const removeBtn = imageItem.querySelector('.btn-danger');
        const undoBtn = imageItem.querySelector('.undo-remove-btn');
        if (removeBtn) removeBtn.style.display = 'flex';
        if (undoBtn) undoBtn.style.display = 'none';
        
        showToast('Restored', 'Image removal cancelled.', 'success');
    }
};
</script>
@endpush
