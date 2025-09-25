@extends('admin.layouts.app')

@section('title', 'Edit Product Specifications - ' . $product->name)

@push('styles')
<style>
    .specification-group {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .spec-item {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 10px;
    }
    .add-spec-btn {
        border: 2px dashed #dee2e6;
        background: transparent;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    .add-spec-btn:hover {
        border-color: #007bff;
        color: #007bff;
        background: #f8f9ff;
    }
    .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }
    .spec-item {
        position: relative;
    }
    .feature-item, .item-item, .compatibility-item {
        position: relative;
        margin-bottom: 10px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.specifications.index') }}">Specifications</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Product Specifications</h4>
                <p class="text-muted">Update specifications for: <strong>{{ $product->name }}</strong></p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.products.specifications.update', $product->id) }}">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Basic Product Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-info-circle me-2"></i>Basic Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Weight (kg)</label>
                                    <input type="number" name="weight" class="form-control" 
                                           step="0.001" min="0" 
                                           value="{{ old('weight', $product->weight) }}"
                                           placeholder="Enter weight in kg">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Material</label>
                                    <input type="text" name="material" class="form-control" 
                                           value="{{ old('material', $product->material) }}"
                                           placeholder="e.g., Cotton, Plastic, Metal">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Length (cm)</label>
                                    <input type="number" name="length" class="form-control" 
                                           step="0.1" min="0" 
                                           value="{{ old('length', $product->length) }}"
                                           placeholder="Length">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Width (cm)</label>
                                    <input type="number" name="width" class="form-control" 
                                           step="0.1" min="0" 
                                           value="{{ old('width', $product->width) }}"
                                           placeholder="Width">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Height (cm)</label>
                                    <input type="number" name="height" class="form-control" 
                                           step="0.1" min="0" 
                                           value="{{ old('height', $product->height) }}"
                                           placeholder="Height">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Shipping Weight (kg)</label>
                                    <input type="number" name="shipping_weight" class="form-control" 
                                           step="0.001" min="0" 
                                           value="{{ old('shipping_weight', $product->shipping_weight) }}"
                                           placeholder="Shipping weight">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Model Number</label>
                                    <input type="text" name="model_number" class="form-control" 
                                           value="{{ old('model_number', $product->model_number) }}"
                                           placeholder="Model/Part number">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Barcode</label>
                                    <input type="text" name="barcode" class="form-control" 
                                           value="{{ old('barcode', $product->barcode) }}"
                                           placeholder="Product barcode/UPC">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technical Specifications -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-settings me-2"></i>Technical Specifications
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="specifications-container">
                            @php
                                $specifications = old('specifications', $product->specifications ?? []);
                            @endphp
                            @if(!empty($specifications))
                                @foreach($specifications as $key => $value)
                                    <div class="spec-item">
                                        <button type="button" class="btn btn-danger btn-sm remove-btn" onclick="removeSpecItem(this)">
                                            <i class="ti ti-x"></i>
                                        </button>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input type="text" name="spec_keys[]" class="form-control" 
                                                       placeholder="Specification name" value="{{ $key }}">
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="spec_values[]" class="form-control" 
                                                       placeholder="Specification value" value="{{ $value }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn add-spec-btn w-100" onclick="addSpecification()">
                            <i class="ti ti-plus me-2"></i>Add Specification
                        </button>
                    </div>
                </div>

                <!-- Product Features -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-star me-2"></i>Product Features
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="features-container">
                            @php
                                $features = old('features', $product->features ?? []);
                            @endphp
                            @if(!empty($features))
                                @foreach($features as $feature)
                                    <div class="feature-item">
                                        <div class="input-group">
                                            <input type="text" name="features[]" class="form-control" 
                                                   placeholder="Enter feature" value="{{ $feature }}">
                                            <button type="button" class="btn btn-outline-danger" onclick="removeItem(this)">
                                                <i class="ti ti-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn add-spec-btn w-100" onclick="addFeature()">
                            <i class="ti ti-plus me-2"></i>Add Feature
                        </button>
                    </div>
                </div>

                <!-- Included Items -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-package me-2"></i>What's in the Box
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="included-items-container">
                            @php
                                $includedItems = old('included_items', $product->included_items ?? []);
                            @endphp
                            @if(!empty($includedItems))
                                @foreach($includedItems as $item)
                                    <div class="item-item">
                                        <div class="input-group">
                                            <input type="text" name="included_items[]" class="form-control" 
                                                   placeholder="Enter included item" value="{{ $item }}">
                                            <button type="button" class="btn btn-outline-danger" onclick="removeItem(this)">
                                                <i class="ti ti-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn add-spec-btn w-100" onclick="addIncludedItem()">
                            <i class="ti ti-plus me-2"></i>Add Included Item
                        </button>
                    </div>
                </div>

                <!-- Compatibility -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-puzzle me-2"></i>Compatibility
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="compatibility-container">
                            @php
                                $compatibility = old('compatibility', $product->compatibility ?? []);
                            @endphp
                            @if(!empty($compatibility))
                                @foreach($compatibility as $item)
                                    <div class="compatibility-item">
                                        <div class="input-group">
                                            <input type="text" name="compatibility[]" class="form-control" 
                                                   placeholder="Enter compatibility info" value="{{ $item }}">
                                            <button type="button" class="btn btn-outline-danger" onclick="removeItem(this)">
                                                <i class="ti ti-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn add-spec-btn w-100" onclick="addCompatibility()">
                            <i class="ti ti-plus me-2"></i>Add Compatibility Info
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Product Preview -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Preview</h5>
                    </div>
                    <div class="card-body text-center">
                        @if($product->images || $product->image)
                            @php
                                $imageUrl = asset('assets/img/product/default.png');
                                
                                // First try to get image from images array
                                if ($product->images) {
                                    $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                                    $firstImage = is_array($images) && !empty($images) ? $images[0] : null;
                                    
                                    if ($firstImage) {
                                        if (is_array($firstImage) && isset($firstImage['sizes']['medium']['storage_url'])) {
                                            $imageUrl = $firstImage['sizes']['medium']['storage_url'];
                                        } elseif (is_string($firstImage)) {
                                            $imageUrl = str_starts_with($firstImage, 'http') ? $firstImage : asset('assets/img/product/' . $firstImage);
                                        }
                                    }
                                }
                                // Fallback to single image field
                                elseif ($product->image) {
                                    $imageUrl = str_starts_with($product->image, 'http') ? $product->image : asset('assets/img/product/' . $product->image);
                                }
                            @endphp
                            <img src="{{ $imageUrl }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid rounded mb-3"
                                 style="max-height: 200px; object-fit: cover;"
                                 onerror="this.src='{{ asset('assets/img/product/default.png') }}'">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 200px;">
                                <i class="ti ti-photo text-muted" style="font-size: 4rem;"></i>
                            </div>
                        @endif
                        
                        <h6>{{ $product->name }}</h6>
                        <p class="text-muted small">
                            <strong>SKU:</strong> {{ $product->sku ?: 'N/A' }}<br>
                            <strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}<br>
                            <strong>Brand:</strong> {{ $product->brand->name ?? 'N/A' }}<br>
                            <strong>Price:</strong> ৳{{ number_format($product->price, 2) }}
                        </p>
                    </div>
                </div>

                <!-- Support & Warranty -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-headset me-2"></i>Support & Warranty
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Warranty Period</label>
                            <input type="text" name="warranty_period" class="form-control" 
                                   value="{{ old('warranty_period', $product->warranty_period) }}"
                                   placeholder="e.g., 1 Year, 6 Months">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Support Email</label>
                            <input type="email" name="support_email" class="form-control" 
                                   value="{{ old('support_email', $product->support_email) }}"
                                   placeholder="support@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Support Phone</label>
                            <input type="text" name="support_phone" class="form-control" 
                                   value="{{ old('support_phone', $product->support_phone) }}"
                                   placeholder="+880-1234-567890">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Warranty Terms</label>
                            <textarea name="warranty_terms" class="form-control" rows="3"
                                      placeholder="Warranty terms and conditions...">{{ old('warranty_terms', $product->warranty_terms) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i>Save Specifications
                            </button>
                            <a href="{{ route('admin.products.specifications.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-left me-1"></i>Back to List
                            </a>
                            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-info" target="_blank">
                                <i class="ti ti-external-link me-1"></i>View Product
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function addSpecification() {
    const container = document.getElementById('specifications-container');
    const html = `
        <div class="spec-item">
            <button type="button" class="btn btn-danger btn-sm remove-btn" onclick="removeSpecItem(this)">
                <i class="ti ti-x"></i>
            </button>
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="spec_keys[]" class="form-control" 
                           placeholder="Specification name">
                </div>
                <div class="col-md-8">
                    <input type="text" name="spec_values[]" class="form-control" 
                           placeholder="Specification value">
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function addFeature() {
    const container = document.getElementById('features-container');
    const html = `
        <div class="feature-item">
            <div class="input-group">
                <input type="text" name="features[]" class="form-control" 
                       placeholder="Enter feature">
                <button type="button" class="btn btn-outline-danger" onclick="removeItem(this)">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function addIncludedItem() {
    const container = document.getElementById('included-items-container');
    const html = `
        <div class="item-item">
            <div class="input-group">
                <input type="text" name="included_items[]" class="form-control" 
                       placeholder="Enter included item">
                <button type="button" class="btn btn-outline-danger" onclick="removeItem(this)">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function addCompatibility() {
    const container = document.getElementById('compatibility-container');
    const html = `
        <div class="compatibility-item">
            <div class="input-group">
                <input type="text" name="compatibility[]" class="form-control" 
                       placeholder="Enter compatibility info">
                <button type="button" class="btn btn-outline-danger" onclick="removeItem(this)">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function removeSpecItem(button) {
    button.closest('.spec-item').remove();
}

function removeItem(button) {
    button.closest('.feature-item, .item-item, .compatibility-item').remove();
}
</script>
@endpush
