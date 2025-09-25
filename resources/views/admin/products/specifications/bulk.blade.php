@extends('admin.layouts.app')

@section('title', 'Bulk Update Product Specifications')

@push('styles')
<style>
    .specification-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: #f9f9f9;
    }
    .filter-section {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .product-preview {
        max-height: 400px;
        overflow-y: auto;
    }
    .selected-product {
        background: #e3f2fd;
        border-left: 4px solid #2196f3;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Bulk Update Product Specifications</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.specifications.index') }}">Product Specifications</a></li>
                        <li class="breadcrumb-item active">Bulk Update</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="filter-section">
                <h5>Filter Products</h5>
                <form action="{{ route('admin.products.specifications.bulk') }}" method="GET" id="filterForm">
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Brand</label>
                        <select name="brand_id" class="form-select">
                            <option value="">All Brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="missing_only" value="1" 
                                   id="missing_only" {{ request('missing_only') ? 'checked' : '' }}>
                            <label class="form-check-label" for="missing_only">
                                Only products with missing specifications
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-filter"></i> Apply Filters
                    </button>
                </form>
            </div>

            @if($products->count() > 0)
            <div class="specification-card">
                <h6>Selected Products ({{ $products->count() }})</h6>
                <div class="product-preview">
                    @foreach($products as $product)
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded selected-product">
                        <div>
                            <strong>{{ Str::limit($product->name, 30) }}</strong>
                            <br>
                            <small class="text-muted">{{ $product->category->name ?? 'N/A' }}</small>
                        </div>
                        <input type="checkbox" class="product-checkbox" value="{{ $product->id }}" checked>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-8">
            @if($products->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5>Bulk Update Specifications</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.specifications.bulk-update') }}" method="POST" id="bulkUpdateForm">
                        @csrf
                        
                        <!-- Hidden field for selected products -->
                        <input type="hidden" name="selected_products" id="selectedProducts">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Weight (kg)</label>
                                    <input type="text" class="form-control" name="weight" placeholder="e.g., 1.5">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Material</label>
                                    <input type="text" class="form-control" name="material" placeholder="e.g., Plastic, Metal">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Warranty Period</label>
                                    <input type="text" class="form-control" name="warranty_period" placeholder="e.g., 1 Year">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Country of Origin</label>
                                    <input type="text" class="form-control" name="country_of_origin" placeholder="e.g., Bangladesh">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Features (one per line)</label>
                            <textarea class="form-control" name="features" rows="4" 
                                      placeholder="Enter each feature on a new line"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Technical Specifications (JSON format)</label>
                            <textarea class="form-control" name="specifications" rows="6" 
                                      placeholder='{"dimension": "10x5x2 cm", "battery": "Li-ion"}'></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="only_empty" value="1" id="only_empty" checked>
                                    <label class="form-check-label" for="only_empty">
                                        Only update empty fields
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="backup_data" value="1" id="backup_data" checked>
                                    <label class="form-check-label" for="backup_data">
                                        Backup existing data
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-secondary me-2" onclick="previewChanges()">
                                <i class="bx bx-show"></i> Preview Changes
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="bx bx-save"></i> Update Selected Products
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-body text-center">
                    <i class="bx bx-search" style="font-size: 48px; color: #ccc;"></i>
                    <h5 class="mt-3">No Products Found</h5>
                    <p class="text-muted">Apply filters on the left to select products for bulk update.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Changes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="confirmUpdate()">Confirm Update</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Update selected products when checkboxes change
    updateSelectedProducts();
    
    $('.product-checkbox').change(function() {
        updateSelectedProducts();
    });
    
    // Auto-submit filter form on change
    $('#filterForm select, #filterForm input[type="checkbox"]').change(function() {
        $('#filterForm').submit();
    });
});

function updateSelectedProducts() {
    const selected = [];
    $('.product-checkbox:checked').each(function() {
        selected.push($(this).val());
    });
    $('#selectedProducts').val(JSON.stringify(selected));
}

function previewChanges() {
    const formData = new FormData($('#bulkUpdateForm')[0]);
    
    // Show preview content
    let previewHtml = '<h6>Changes to be applied:</h6><ul>';
    
    if (formData.get('weight')) {
        previewHtml += `<li><strong>Weight:</strong> ${formData.get('weight')} kg</li>`;
    }
    if (formData.get('material')) {
        previewHtml += `<li><strong>Material:</strong> ${formData.get('material')}</li>`;
    }
    if (formData.get('warranty_period')) {
        previewHtml += `<li><strong>Warranty:</strong> ${formData.get('warranty_period')}</li>`;
    }
    if (formData.get('country_of_origin')) {
        previewHtml += `<li><strong>Country of Origin:</strong> ${formData.get('country_of_origin')}</li>`;
    }
    
    previewHtml += '</ul>';
    
    const selectedCount = $('.product-checkbox:checked').length;
    previewHtml += `<p><strong>Products to update:</strong> ${selectedCount} selected</p>`;
    
    $('#previewContent').html(previewHtml);
    $('#previewModal').modal('show');
}

function confirmUpdate() {
    $('#previewModal').modal('hide');
    $('#bulkUpdateForm').submit();
}
</script>
@endpush
