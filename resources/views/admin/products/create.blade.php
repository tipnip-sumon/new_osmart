@extends('admin.layouts.app')

@section('title', 'Create Product')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    /* Image Upload Styles */
    .image-upload-container {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .image-upload-container:hover {
        border-color: #007bff;
        background: #e7f3ff;
    }

    .image-upload-container.drag-over {
        border-color: #28a745;
        background: #d4edda;
    }

    .image-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
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

    /* MLM Styles */
    .mlm-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .commission-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .commission-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
    }

    /* Form Enhancements */
    .form-label {
        font-weight: 500;
        color: #2c384e;
        margin-bottom: 8px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .form-control.is-valid,
    .form-select.is-valid {
        border-color: #28a745;
        animation: successPulse 0.6s ease-in-out;
    }

    @keyframes successPulse {
        0% {
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
        }
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom: none;
    }

    .card-header .card-title {
        color: white;
        font-weight: 600;
        margin: 0;
    }

    /* Status badges */
    .status-active {
        background: #28a745;
        color: white;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.75rem;
    }

    .status-inactive {
        background: #6c757d;
        color: white;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.75rem;
    }
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

.points-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    padding: 15px;
    margin: 15px 0;
    color: white;
}

.points-section h6 {
    color: white;
    margin-bottom: 15px;
}

.points-section .form-label {
    color: rgba(255, 255, 255, 0.9);
    font-weight: 500;
}

.points-section .text-muted {
    color: rgba(255, 255, 255, 0.7) !important;
}

.point-calculation-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
}

.mlm-toggle-switch .form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.commission-badge {
    background: linear-gradient(45deg, #FF6B6B, #4ECDC4);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

/* Starter Kit specific styles */
.starter-kit-section {
    border-left: 4px solid #28a745;
    background: linear-gradient(90deg, rgba(40, 167, 69, 0.05) 0%, transparent 100%);
    border-radius: 0 8px 8px 0;
    padding-left: 15px;
    margin-left: -15px;
    transition: all 0.3s ease;
}

.starter-kit-badge {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.tier-option {
    padding: 10px 15px;
    margin: 5px 0;
    border-radius: 8px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.tier-option:hover {
    border-color: #007bff;
    background: linear-gradient(135deg, #e7f3ff, #cce7ff);
}
/* Primary badge styling fix */
.primary-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
    z-index: 10;
    box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
}

.primary-badge::before {
    content: '★';
    font-size: 12px;
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

/* Image preview item improvements */
.image-preview-item {
    position: relative;
    border: 2px solid #e3e6f0;
    border-radius: 12px;
    overflow: hidden;
    background: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.image-preview-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    border-color: #4e73df;
}

.image-actions {
    position: absolute;
    top: 8px;
    right: 8px;
    display: flex;
    gap: 6px;
    opacity: 1; /* Always visible instead of 0 */
    transition: opacity 0.3s ease;
}

.image-preview-item:hover .image-actions {
    opacity: 1;
}

.btn-primary-image, .btn-remove-image {
    background: rgba(0, 0, 0, 0.8);
    color: white !important;
    border: none;
    border-radius: 50%; /* Make buttons circular */
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px; /* Larger font for better visibility */
    cursor: pointer;
    font-weight: bold;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    line-height: 1;
}

.btn-primary-image {
    background: rgba(40, 167, 69, 0.9) !important;
    color: white !important;
}

.btn-primary-image:hover {
    background: #28a745 !important;
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
}

.btn-remove-image {
    background: rgba(220, 53, 69, 0.9) !important;
    color: white !important;
}

.btn-remove-image:hover {
    background: #dc3545 !important;
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
}

/* Image info styling */
.image-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: white;
    padding: 12px 8px 8px 8px;
    border-radius: 0 0 8px 8px;
    font-size: 11px;
}

.image-info small {
    color: rgba(255,255,255,0.9) !important;
}

/* Select2 Custom Styling */
.select2-container--bootstrap-5 .select2-selection {
    min-height: 38px;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    font-size: 0.875rem;
}

.select2-container--bootstrap-5 .select2-selection--multiple {
    min-height: 42px;
    padding: 2px 8px;
}

.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
    background-color: #007bff;
    border: 1px solid #007bff;
    border-radius: 4px;
    color: #fff;
    font-size: 0.75rem;
    padding: 2px 8px;
    margin: 2px 4px 2px 0;
}

.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
    color: #fff;
    margin-right: 4px;
}

.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #ffdddd;
}

.select2-container--bootstrap-5.select2-container--focus .select2-selection {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.select2-dropdown.select2-dropdown--below {
    border-top: none;
    border-radius: 0 0 6px 6px;
}

.select2-dropdown.select2-dropdown--above {
    border-bottom: none;
    border-radius: 6px 6px 0 0;
}

.select2-results__option {
    padding: 8px 12px;
    font-size: 0.875rem;
}

.select2-results__option--highlighted {
    background-color: #007bff !important;
    color: #fff;
}

.select2-results__option[aria-selected="true"] {
    background-color: #e7f3ff;
    color: #004085;
}

/* Tags specific styling */
.tags-input-wrapper {
    position: relative;
}

.tags-input-wrapper .select2-container {
    width: 100% !important;
}

.tags-select {
    width: 100%;
}

/* Enhanced tag styling */
.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice i {
    font-size: 0.7rem;
}

.select2-results__option .text-success {
    font-weight: 600;
}

</style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Create Product</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            <div class="row">
                <!-- Basic Information -->
                <div class="col-xl-8">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Basic Information</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" placeholder="Enter product name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" name="slug" value="{{ old('slug') }}" placeholder="Auto-generated from name">
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Leave empty to auto-generate from product name</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sku" class="form-label">SKU</label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                           id="sku" name="sku" value="{{ old('sku') }}" placeholder="Auto-generated">
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Stock Keeping Unit - Leave empty to auto-generate</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="barcode" class="form-label">Barcode</label>
                                    <input type="text" class="form-control @error('barcode') is-invalid @enderror" 
                                           id="barcode" name="barcode" value="{{ old('barcode') }}" placeholder="Product barcode">
                                    @error('barcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="short_description" class="form-label">Short Description</label>
                                    <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                              id="short_description" name="short_description" rows="3" 
                                              placeholder="Brief description of the product">{{ old('short_description') }}</textarea>
                                    @error('short_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">This appears in product listings (max 500 characters)</div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="6" 
                                              placeholder="Detailed product description">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Detailed product information for customers</div>
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
                                           id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" placeholder="0.00" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sale_price" class="form-label">Sale Price</label>
                                    <input type="number" class="form-control @error('sale_price') is-invalid @enderror" 
                                           id="sale_price" name="sale_price" value="{{ old('sale_price') }}" step="0.01" min="0" placeholder="0.00">
                                    @error('sale_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Leave empty if no sale price</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cost_price" class="form-label">Cost Price</label>
                                    <input type="number" class="form-control @error('cost_price') is-invalid @enderror" 
                                           id="cost_price" name="cost_price" value="{{ old('cost_price') }}" step="0.01" min="0" placeholder="0.00">
                                    @error('cost_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Your cost for this product</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="compare_price" class="form-label">Compare at Price</label>
                                    <input type="number" class="form-control @error('compare_price') is-invalid @enderror" 
                                           id="compare_price" name="compare_price" value="{{ old('compare_price') }}" step="0.01" min="0" placeholder="0.00">
                                    @error('compare_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Original price before discount</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Management -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Inventory Management</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                           id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0" required>
                                    @error('stock_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="min_stock_level" class="form-label">Minimum Stock Level</label>
                                    <input type="number" class="form-control @error('min_stock_level') is-invalid @enderror" 
                                           id="min_stock_level" name="min_stock_level" value="{{ old('min_stock_level', 5) }}" min="0">
                                    @error('min_stock_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Alert when stock falls below this level</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="track_quantity" name="track_quantity" value="1" 
                                               {{ old('track_quantity', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="track_quantity">
                                            Track Quantity
                                        </label>
                                    </div>
                                    <div class="form-text">Automatically reduce stock when orders are placed</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="allow_backorder" name="allow_backorder" value="1" 
                                               {{ old('allow_backorder') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="allow_backorder">
                                            Allow Backorders
                                        </label>
                                    </div>
                                    <div class="form-text">Allow sales when out of stock</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MLM Commission Settings -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="mlm-badge">
                                    <i class="ti ti-network me-1"></i>
                                    MLM Commission Settings
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="generates_commission" name="generates_commission" value="1" 
                                               {{ old('generates_commission', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="generates_commission">
                                            Generates Commission
                                        </label>
                                    </div>
                                    <div class="form-text">Enable MLM commission for this product</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_starter_kit" name="is_starter_kit" value="1" 
                                               {{ old('is_starter_kit') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_starter_kit">
                                            Starter Kit Product
                                        </label>
                                    </div>
                                    <div class="form-text">Special product for new member registration</div>
                                </div>
                                <div class="col-md-6 mb-3" id="starter_kit_tier_section" style="{{ old('is_starter_kit') ? '' : 'display: none;' }}">
                                    <label for="starter_kit_tier" class="form-label">Starter Kit Tier</label>
                                    <select class="form-select @error('starter_kit_tier') is-invalid @enderror" 
                                            id="starter_kit_tier" name="starter_kit_tier">
                                        <option value="">Select Tier</option>
                                        <option value="basic" {{ old('starter_kit_tier') == 'basic' ? 'selected' : '' }}>Basic (2,000 TK)</option>
                                        <option value="standard" {{ old('starter_kit_tier') == 'standard' ? 'selected' : '' }}>Standard (5,000 TK)</option>
                                        <option value="premium" {{ old('starter_kit_tier') == 'premium' ? 'selected' : '' }}>Premium (10,000 TK)</option>
                                        <option value="platinum" {{ old('starter_kit_tier') == 'platinum' ? 'selected' : '' }}>Platinum (20,000 TK)</option>
                                    </select>
                                    @error('starter_kit_tier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Select the tier for this starter kit</div>
                                </div>
                                <div class="col-md-12 mb-3" id="starter_kit_level_section" style="{{ old('is_starter_kit') ? '' : 'display: none;' }}">
                                    <label for="starter_kit_level" class="form-label">Custom Starter Kit Level</label>
                                    <input type="text" class="form-control @error('starter_kit_level') is-invalid @enderror" 
                                           id="starter_kit_level" name="starter_kit_level" value="{{ old('starter_kit_level') }}" 
                                           placeholder="e.g., Silver Package, Gold Package, etc.">
                                    @error('starter_kit_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Optional: Enter a custom level name for this starter kit</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="pv_points" class="form-label">PV Points</label>
                                    <input type="number" class="form-control @error('pv_points') is-invalid @enderror" 
                                           id="pv_points" name="pv_points" value="{{ old('pv_points') }}" step="0.01" min="0" placeholder="Auto-calculated">
                                    @error('pv_points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Personal Volume points</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="bv_points" class="form-label">BV Points</label>
                                    <input type="number" class="form-control @error('bv_points') is-invalid @enderror" 
                                           id="bv_points" name="bv_points" value="{{ old('bv_points') }}" step="0.01" min="0" placeholder="Auto-calculated">
                                    @error('bv_points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Business Volume points</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="direct_commission_rate" class="form-label">Direct Commission (%)</label>
                                    <input type="number" class="form-control @error('direct_commission_rate') is-invalid @enderror" 
                                           id="direct_commission_rate" name="direct_commission_rate" value="{{ old('direct_commission_rate', 10) }}" 
                                           step="0.01" min="0" max="100">
                                    @error('direct_commission_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Information -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">SEO Information</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                       id="meta_title" name="meta_title" value="{{ old('meta_title') }}" 
                                       placeholder="SEO friendly title">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Recommended length: 50-60 characters</div>
                            </div>

                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                          id="meta_description" name="meta_description" rows="3" 
                                          placeholder="SEO friendly description">{{ old('meta_description') }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Recommended length: 150-160 characters</div>
                            </div>

                            <div class="mb-3">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                       id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}" 
                                       placeholder="keyword1, keyword2, keyword3">
                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Separate keywords with commas</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings & Media -->
                <div class="col-xl-4">
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
                            
                            <!-- Image Preview Grid -->
                            <div id="image-preview-grid" class="image-preview-grid"></div>
                            
                            @error('images.*')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Product Organization -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Product Organization</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="vendor_id" class="form-label">Vendor <span class="text-danger">*</span></label>
                                <select class="form-select @error('vendor_id') is-invalid @enderror" id="vendor_id" name="vendor_id" required>
                                    <option value="">Select Vendor</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->firstname }} {{ $vendor->lastname }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if($brands->isNotEmpty())
                            <div class="mb-3">
                                <label for="brand_id" class="form-label">Brand</label>
                                <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id">
                                    <option value="">Select Brand (Optional)</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif
                            
                            <div class="mb-4">
                                <label for="tags" class="form-label fw-semibold mb-2">
                                    <i class="ti ti-tags me-2 text-primary"></i>Product Tags
                                </label>
                                <div class="tags-input-wrapper">
                                    <select class="form-select tags-select @error('tags') is-invalid @enderror" 
                                            id="tags" name="tags[]" multiple data-placeholder="Search and select tags...">
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}" 
                                                {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
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

                    <!-- Product Settings -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Product Settings</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" 
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Featured Product
                                </label>
                                <div class="form-text">Featured products appear in special sections</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="is_digital" name="is_digital" value="1" 
                                       {{ old('is_digital') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_digital">
                                    Digital Product
                                </label>
                                <div class="form-text">No shipping required for digital products</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="is_downloadable" name="is_downloadable" value="1" 
                                       {{ old('is_downloadable') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_downloadable">
                                    Downloadable
                                </label>
                                <div class="form-text">Customer can download this product</div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="weight" class="form-label">Weight (kg)</label>
                                    <input type="number" class="form-control @error('weight') is-invalid @enderror" 
                                           id="weight" name="weight" value="{{ old('weight') }}" step="0.01" min="0" placeholder="0.00">
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="ti ti-device-floppy me-2"></i>Create Product
                                </button>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                    <i class="ti ti-arrow-left me-2"></i>Back to Products
                                </a>
                            </div>
                        </div>
                    </div>
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
            <h5 class="mt-3">Saving Product...</h5>
            <p class="text-muted">Please wait while we process your product and images.</p>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    let uploadedImages = [];
    let primaryImageIndex = 0;

    // Auto-generate slug from product name
    $('#name').on('input', function() {
        const name = $(this).val();
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        $('#slug').val(slug);
        
        // Auto-generate SKU
        if (name && !$('#sku').val()) {
            const sku = name.replace(/[^a-zA-Z0-9]/g, '').toUpperCase().substring(0, 6) 
                + new Date().getFullYear().toString().substr(-2) +
                Math.floor(Math.random() * 100).toString().padStart(2, '0');
            $('#sku').val(sku);
        }
    });
    
    // Handle starter kit tier visibility and animations
    $('#is_starter_kit').on('change', function() {
        const isChecked = $(this).is(':checked');
        const tierSection = $('#starter_kit_tier_section');
        const levelSection = $('#starter_kit_level_section');
        
        if (isChecked) {
            tierSection.slideDown(300);
            levelSection.slideDown(300);
            tierSection.find('select').focus();
        } else {
            tierSection.slideUp(300);
            levelSection.slideUp(300);
            $('#starter_kit_tier').val('');
            $('#starter_kit_level').val('');
            // Reset price and points when unchecking starter kit
            $('#price, #pv_points, #bv_points').val('');
        }
    });
    
    // Auto-populate price based on starter kit tier with enhanced confirmation
    $('#starter_kit_tier').on('change', function() {
        var tier = $(this).val();
        var suggestedPrice = '';
        var tierName = '';
        
        switch(tier) {
            case 'basic':
                suggestedPrice = '2000';
                tierName = 'Basic';
                break;
            case 'standard':
                suggestedPrice = '5000';
                tierName = 'Standard';
                break;
            case 'premium':
                suggestedPrice = '10000';
                tierName = 'Premium';
                break;
            case 'platinum':
                suggestedPrice = '20000';
                tierName = 'Platinum';
                break;
        }
        
        if (suggestedPrice) {
            // Visual feedback with styling
            const confirmMessage = `Set ${tierName} package price to ${suggestedPrice} TK?\n\nThis will also auto-calculate PV and BV points.`;
            
            if (confirm(confirmMessage)) {
                $('#price').val(suggestedPrice).addClass('is-valid');
                
                // Auto-calculate PV and BV points based on price with visual feedback
                var pv = Math.round(suggestedPrice * 0.7);
                var bv = Math.round(suggestedPrice * 0.5);
                $('#pv_points').val(pv).addClass('is-valid');
                $('#bv_points').val(bv).addClass('is-valid');
                
                // Remove the valid class after a short delay
                setTimeout(function() {
                    $('#price, #pv_points, #bv_points').removeClass('is-valid');
                }, 2000);
                
                // Show success toast
                showToast('Price Set', `${tierName} package configured with ${suggestedPrice} TK and points calculated.`, 'success');
            }
        }
    });

    // MLM Settings Toggle
    $('#generates_commission').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.mlm-settings').toggle(isChecked);
        
        if (isChecked) {
            $('.mlm-settings').slideDown();
        } else {
            $('.mlm-settings').slideUp();
        }
    });

    // Commission type change handler - removed since this field doesn't exist in the form
    // Future enhancement: can add commission type selector if needed

    // Volume calculations - Auto-suggest based on price
    $('#price').on('input', function() {
        const price = parseFloat($(this).val()) || 0;
        
        if (price > 0 && $('#generates_commission').is(':checked')) {
            // Auto-suggest PV (usually 70% of price)
            if (!$('#pv_points').val()) {
                $('#pv_points').val((price * 0.7).toFixed(2));
            }
            
            // Auto-suggest BV (usually 50% of price)
            if (!$('#bv_points').val()) {
                $('#bv_points').val((price * 0.5).toFixed(2));
            }
        }
    });

    // Point calculation - simplified for the actual form fields
    $('#pv_points, #bv_points').on('input', function() {
        // Basic validation to ensure points are not negative
        const value = parseFloat($(this).val()) || 0;
        if (value < 0) {
            $(this).val(0);
        }
    });

    // Category change handler - Load subcategories
    $('#category_id').on('change', function() {
        const categoryId = $(this).val();
        const subcategorySelect = $('#subcategory_id');
        
        // Reset subcategory dropdown
        subcategorySelect.html('<option value="">Select Subcategory (Optional)</option>').prop('disabled', true);
        
        if (categoryId) {
            // Show loading
            subcategorySelect.html('<option value="">Loading subcategories...</option>');
            
            // Fetch subcategories
            $.ajax({
                url: `/admin/products/subcategories/${categoryId}`,
                type: 'GET',
                success: function(subcategories) {
                    subcategorySelect.html('<option value="">Select Subcategory (Optional)</option>');
                    
                    if (subcategories.length > 0) {
                        subcategories.forEach(function(subcategory) {
                            subcategorySelect.append(`<option value="${subcategory.id}">${subcategory.name}</option>`);
                        });
                        subcategorySelect.prop('disabled', false);
                    } else {
                        subcategorySelect.html('<option value="">No subcategories available</option>');
                    }
                },
                error: function() {
                    subcategorySelect.html('<option value="">Error loading subcategories</option>');
                }
            });
        }
    });

    // Price validation
    $('#price, #sale_price, #cost_price').on('input', function() {
        const price = parseFloat($('#price').val()) || 0;
        const salePrice = parseFloat($('#sale_price').val()) || 0;
        const costPrice = parseFloat($('#cost_price').val()) || 0;
        
        // Validate sale price
        if (salePrice > 0 && salePrice >= price) {
            $('#sale_price').addClass('is-invalid');
            $('#sale_price').siblings('.invalid-feedback').remove();
            $('#sale_price').after('<div class="invalid-feedback">Sale price must be less than regular price</div>');
        } else {
            $('#sale_price').removeClass('is-invalid');
            $('#sale_price').siblings('.invalid-feedback').remove();
        }
        
        // Validate cost vs selling price
        if (costPrice > 0 && price > 0 && costPrice >= price) {
            $(this).addClass('is-invalid');
            $(this).siblings('.invalid-feedback').remove();
            $(this).after('<div class="invalid-feedback">Cost price should be less than selling price</div>');
        } else {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').remove();
        }
    });

    // Image Upload Handling
    $('#image-input').on('change', function(e) {
        const files = Array.from(e.target.files);
        files.forEach(function(file, index) {
            if (validateImageFile(file)) {
                previewImage(file, uploadedImages.length + index);
            }
        });
    });

    // Drag and drop for image upload
    const uploadArea = $('#image-upload-area');
    
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
        files.forEach(function(file, index) {
            if (validateImageFile(file)) {
                previewImage(file, uploadedImages.length + index);
            }
        });
    });

    // Click to upload
    uploadArea.on('click', function() {
        $('#image-input').click();
    });

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

    function previewImage(file, index) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imageData = {
                file: file,
                url: e.target.result,
                name: file.name,
                size: file.size,
                index: index
            };
            
            uploadedImages.push(imageData);
            renderImagePreview(imageData, index);
            
            // Make first image primary
            if (uploadedImages.length === 1) {
                primaryImageIndex = 0;
            }
        };
        reader.readAsDataURL(file);
    }

    function renderImagePreview(imageData, index) {
        const isPrimary = index === primaryImageIndex;
        const previewHtml = `
            <div class="image-preview-item" data-index="${index}">
                <img src="${imageData.url}" alt="${imageData.name}">
                <div class="image-actions">
                    <button type="button" class="btn-primary-image" onclick="setPrimaryImage(${index})" 
                            title="Set as primary" ${isPrimary ? 'style="display:none;"' : ''}>
                        ★
                    </button>
                    <button type="button" class="btn-remove-image" onclick="removeImage(${index})" title="Remove">
                        ×
                    </button>
                </div>
                ${isPrimary ? '<div class="primary-badge">Primary</div>' : ''}
                <div class="image-info">
                    <small class="text-muted">${imageData.name}</small>
                    <small class="text-muted d-block">${formatFileSize(imageData.size)}</small>
                </div>
            </div>
        `;
        
        $('#image-preview-grid').append(previewHtml);
    }

    window.setPrimaryImage = function(index) {
        primaryImageIndex = index;
        updateImagePreviews();
    };

    window.removeImage = function(index) {
        uploadedImages = uploadedImages.filter(img => img.index !== index);
        if (primaryImageIndex === index && uploadedImages.length > 0) {
            primaryImageIndex = uploadedImages[0].index;
        }
        updateImagePreviews();
    };

    function updateImagePreviews() {
        $('#image-preview-grid').empty();
        uploadedImages.forEach(function(imageData) {
            renderImagePreview(imageData, imageData.index);
        });
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Attribute handling
    $('.attribute-toggle').on('change', function() {
        const attributeId = $(this).attr('id').replace('attr_', '');
        const valuesContainer = $(`#attr_values_${attributeId}`);
        
        if ($(this).is(':checked')) {
            valuesContainer.show();
            loadAttributeValues(attributeId);
        } else {
            valuesContainer.hide();
        }
    });

    function loadAttributeValues(attributeId) {
        $.ajax({
            url: `/admin/attributes/${attributeId}/values`,
            type: 'GET',
            success: function(response) {
                if (response.success && response.values) {
                    renderAttributeValues(attributeId, response.values);
                }
            },
            error: function() {
                console.error('Failed to load attribute values');
            }
        });
    }

    function renderAttributeValues(attributeId, values) {
        const container = $(`#attr_values_${attributeId}`);
        let html = '<div class="row">';
        
        values.forEach(function(value) {
            html += `
                <div class="col-md-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               name="attributes[${attributeId}][values][]" 
                               value="${value.id}" 
                               id="attr_${attributeId}_val_${value.id}">
                        <label class="form-check-label" for="attr_${attributeId}_val_${value.id}">
                            ${value.value}
                            ${value.color_code ? `<span class="color-preview" style="background-color: ${value.color_code}"></span>` : ''}
                        </label>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        container.html(html);
    }

    // Initialize Select2 for tags
    if ($('#tags').length) {
        $('#tags').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select or add tags...',
            allowClear: true,
            tags: true,
            tokenSeparators: [',', ' '],
            createTag: function (params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                // Check if the tag already exists
                var existingOption = $('#tags option').filter(function() {
                    return $(this).text().toLowerCase() === term.toLowerCase();
                });
                
                if (existingOption.length > 0) {
                    return null; // Tag already exists
                }
                
                return {
                    id: term,
                    text: term,
                    newTag: true
                };
            },
            templateResult: function (data) {
                if (data.loading) return data.text;
                if (data.newTag) {
                    return $('<span class="text-success"><i class="ti ti-plus me-2"></i>' + data.text + ' (create new)</span>');
                }
                return $('<span><i class="ti ti-tag me-2"></i>' + data.text + '</span>');
            },
            templateSelection: function (data) {
                if (data.newTag) {
                    return data.text;
                }
                return data.text;
            },
            escapeMarkup: function (markup) {
                return markup;
            }
        });
    }

    // Form submission
    $('#productForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate images (optional for now)
        // if (uploadedImages.length === 0) {
        //     showToast('Missing Images', 'Please upload at least one product image', 'error');
        //     return false;
        // }
        
        // Show loading
        $('#loading-overlay').show();
        
        // Create FormData with all form fields and images
        const formData = new FormData(this);
        
        // Remove the default file input and add our processed images (if any)
        formData.delete('images[]');
        if (uploadedImages.length > 0) {
            uploadedImages.forEach(function(imageData, index) {
                formData.append('images[]', imageData.file);
            });
            // Add primary image index only if there are images
            formData.append('primary_image_index', primaryImageIndex);
        }
        
        // Submit form
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#loading-overlay').hide();
                showToast('Success', 'Product created successfully!', 'success');
                
                // Trigger cache refresh for frontend
                if (typeof localStorage !== 'undefined') {
                    localStorage.setItem('products_updated', Date.now());
                }
                
                // Clear service worker cache if available
                if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
                    navigator.serviceWorker.controller.postMessage({
                        type: 'PRODUCT_UPDATED'
                    });
                }
                
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
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    showToast('Error', xhr.responseJSON.message, 'error');
                } else {
                    showToast('Error', 'Failed to create product. Please try again.', 'error');
                }
                
                console.error('Product creation error:', xhr);
            }
        });
    });

    // Save as Draft
    $('#save-draft').on('click', function() {
        $('#status').val('draft');
        $('#productForm').submit();
    });

    function showToast(title, message, type) {
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'danger'} border-0" role="alert">
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
    }

    // Character counter for descriptions
    $('#description, #short_description').on('input', function() {
        const maxLength = $(this).attr('id') === 'short_description' ? 500 : 10000;
        const currentLength = $(this).val().length;
        
        let counter = $(this).siblings('.char-counter');
        if (counter.length === 0) {
            counter = $('<small class="char-counter text-muted"></small>');
            $(this).after(counter);
        }
        
        counter.text(`${currentLength}/${maxLength} characters`);
        
        if (currentLength > maxLength * 0.9) {
            counter.removeClass('text-muted').addClass('text-warning');
        } else {
            counter.removeClass('text-warning').addClass('text-muted');
        }
    });
    
    // Image resize functions
    window.openResizeOptions = function() {
        const panel = document.getElementById('resize-options-panel');
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    };

    window.resizeUploadedImages = function() {
        const fileInput = document.getElementById('image-input');
        const files = fileInput.files;
        
        if (files.length === 0) {
            showToast('Please select images first', 'warning');
            return;
        }
        
        const width = document.getElementById('resize-width').value;
        const height = document.getElementById('resize-height').value;
        const quality = document.getElementById('resize-quality').value;
        const maintainRatio = document.getElementById('maintain-ratio').checked;
        
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }
        formData.append('width', width);
        formData.append('height', height);
        formData.append('quality', quality);
        formData.append('maintain_ratio', maintainRatio ? '1' : '0');
        formData.append('folder', 'products/resized');
        formData.append('_token', '{{ csrf_token() }}');
        
        showToast('Resizing images...', 'info');
        
        fetch('/admin/image-upload/resize', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(`Successfully resized ${data.count} images!`, 'success');
                console.log('Resized images:', data.data);
            } else {
                showToast('Error: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to resize images', 'danger');
        });
    };

    window.generateThumbnails = function() {
        const fileInput = document.getElementById('image-input');
        const files = fileInput.files;
        
        if (files.length === 0) {
            showToast('Please select images first', 'warning');
            return;
        }
        
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }
        formData.append('sizes[]', '150x150');
        formData.append('sizes[]', '300x300');
        formData.append('sizes[]', '500x500');
        formData.append('folder', 'products/thumbnails');
        formData.append('_token', '{{ csrf_token() }}');
        
        showToast('Generating thumbnails...', 'info');
        
        fetch('/admin/image-upload/thumbnails', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(`Successfully generated ${data.count} thumbnails!`, 'success');
                console.log('Thumbnails:', data.data);
            } else {
                showToast('Error: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to generate thumbnails', 'danger');
        });
    };

    window.optimizeImages = function() {
        const fileInput = document.getElementById('image-input');
        const files = fileInput.files;
        
        if (files.length === 0) {
            showToast('Please select images first', 'warning');
            return;
        }
        
        const quality = document.getElementById('resize-quality').value;
        
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }
        formData.append('quality', quality);
        formData.append('folder', 'products/optimized');
        formData.append('_token', '{{ csrf_token() }}');
        
        showToast('Optimizing images...', 'info');
        
        fetch('/admin/image-upload/optimize', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(`Successfully optimized ${data.count} images!`, 'success');
                console.log('Optimized images:', data.data);
                
                // Show savings info
                const totalSavings = data.data.reduce((sum, img) => sum + (img.savings || 0), 0);
                const savingsKB = Math.round(totalSavings / 1024);
                showToast(`Space saved: ${savingsKB}KB`, 'info');
            } else {
                showToast('Error: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to optimize images', 'danger');
        });
    };
});
</script>
@endpush
