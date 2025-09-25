@extends('admin.layouts.app')

@section('title', 'Product Specifications Management')

@push('styles')
<style>
    .specification-card {
        transition: all 0.3s ease;
    }
    .specification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .missing-indicator {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 12px;
        height: 12px;
        background: #dc3545;
        border-radius: 50%;
    }
    .complete-indicator {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 12px;
        height: 12px;
        background: #28a745;
        border-radius: 50%;
    }
    .bulk-actions {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        display: none;
    }
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
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
                        <li class="breadcrumb-item active">Specifications</li>
                    </ol>
                </div>
                <h4 class="page-title">Product Specifications Management</h4>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $products->total() }}</h4>
                            <p class="mb-0">Total Products</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ti ti-box text-white-50" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $missingCount }}</h4>
                            <p class="mb-0">Missing Specifications</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ti ti-alert-triangle text-white-50" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $products->total() - $missingCount }}</h4>
                            <p class="mb-0">Complete Specifications</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ti ti-check-circle text-white-50" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ round((($products->total() - $missingCount) / max($products->total(), 1)) * 100) }}%</h4>
                            <p class="mb-0">Completion Rate</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ti ti-percentage text-white-50" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search Products</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by name or SKU..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filter</label>
                    <select name="missing_only" class="form-select">
                        <option value="">All Products</option>
                        <option value="1" {{ request('missing_only') ? 'selected' : '' }}>
                            Missing Specifications Only
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-search me-1"></i>Search
                        </button>
                        <a href="{{ route('admin.products.specifications.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-refresh me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulkActions">
        <form method="POST" action="{{ route('admin.products.specifications.bulk-update') }}" id="bulkForm">
            @csrf
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span class="fw-bold">
                        <span id="selectedCount">0</span> products selected
                    </span>
                </div>
                <div class="col-md-6 text-end">
                    <div class="btn-group">
                        <button type="button" class="btn btn-success" onclick="setBulkAction('auto_generate')">
                            <i class="ti ti-wand me-1"></i>Auto Generate Specifications
                        </button>
                        <button type="button" class="btn btn-warning" onclick="setBulkAction('bulk_edit')">
                            <i class="ti ti-edit me-1"></i>Bulk Edit
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearSelection()">
                            <i class="ti ti-x me-1"></i>Clear Selection
                        </button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="action" id="bulkAction">
        </form>
    </div>

    <!-- Products Grid -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    Products 
                    <span class="badge bg-primary">{{ $products->total() }}</span>
                </h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAll()">
                        <i class="ti ti-check-all me-1"></i>Select All
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="selectNone()">
                        <i class="ti ti-square me-1"></i>Select None
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($products->count() > 0)
                <div class="row">
                    @foreach($products as $product)
                        @php
                            $missingSpecs = !$product->specifications || 
                                          !$product->features || 
                                          !$product->weight || 
                                          !$product->material || 
                                          !$product->warranty_period;
                        @endphp
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="card specification-card h-100 position-relative">
                                @if($missingSpecs)
                                    <div class="missing-indicator" title="Missing specifications"></div>
                                @else
                                    <div class="complete-indicator" title="Complete specifications"></div>
                                @endif
                                
                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input product-checkbox" 
                                               type="checkbox" 
                                               value="{{ $product->id }}" 
                                               id="product_{{ $product->id }}"
                                               onchange="updateBulkActions()">
                                        <label class="form-check-label" for="product_{{ $product->id }}">
                                            Select for bulk action
                                        </label>
                                    </div>
                                    
                                    <div class="text-center mb-3">
                                        @if($product->images)
                                            @php
                                                $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                                                $firstImage = is_array($images) && !empty($images) ? $images[0] : null;
                                                $imageUrl = asset('assets/img/product/default.png');
                                                
                                                if ($firstImage) {
                                                    if (is_array($firstImage) && isset($firstImage['sizes']['medium']['storage_url'])) {
                                                        $imageUrl = $firstImage['sizes']['medium']['storage_url'];
                                                    } elseif (is_string($firstImage)) {
                                                        $imageUrl = str_starts_with($firstImage, 'http') ? $firstImage : asset('storage/' . $firstImage);
                                                    }
                                                }
                                            @endphp
                                            <img src="{{ $imageUrl }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="img-fluid rounded"
                                                 style="height: 120px; object-fit: cover; width: 100%;"
                                                 onerror="this.src='{{ asset('assets/img/product/default.png') }}'">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 120px;">
                                                <i class="ti ti-photo text-muted" style="font-size: 3rem;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <h6 class="card-title">{{ Str::limit($product->name, 40) }}</h6>
                                    <p class="text-muted small mb-2">
                                        <strong>SKU:</strong> {{ $product->sku ?: 'N/A' }}<br>
                                        <strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}<br>
                                        <strong>Brand:</strong> {{ $product->brand->name ?? 'N/A' }}
                                    </p>
                                    
                                    <!-- Specification Status -->
                                    <div class="mb-3">
                                        <small class="text-muted d-block mb-1">Specification Status:</small>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <span class="badge {{ $product->specifications ? 'bg-success' : 'bg-danger' }}">
                                                Specs
                                            </span>
                                            <span class="badge {{ $product->features ? 'bg-success' : 'bg-danger' }}">
                                                Features
                                            </span>
                                            <span class="badge {{ $product->weight ? 'bg-success' : 'bg-danger' }}">
                                                Weight
                                            </span>
                                            <span class="badge {{ $product->material ? 'bg-success' : 'bg-danger' }}">
                                                Material
                                            </span>
                                            <span class="badge {{ $product->warranty_period ? 'bg-success' : 'bg-danger' }}">
                                                Warranty
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <a href="{{ route('admin.products.specifications.edit', $product->id) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="ti ti-edit me-1"></i>Edit Specifications
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $products->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ti ti-package text-muted" style="font-size: 5rem;"></i>
                    <h5 class="text-muted mt-3">No products found</h5>
                    <p class="text-muted">Try adjusting your search criteria or filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    updateBulkActions();
});

function updateBulkActions() {
    const checked = $('.product-checkbox:checked').length;
    const bulkActions = $('#bulkActions');
    const selectedCount = $('#selectedCount');
    
    selectedCount.text(checked);
    
    if (checked > 0) {
        bulkActions.show();
    } else {
        bulkActions.hide();
    }
}

function selectAll() {
    $('.product-checkbox').prop('checked', true);
    updateBulkActions();
}

function selectNone() {
    $('.product-checkbox').prop('checked', false);
    updateBulkActions();
}

function clearSelection() {
    selectNone();
}

function setBulkAction(action) {
    const checked = $('.product-checkbox:checked');
    
    if (checked.length === 0) {
        alert('Please select at least one product.');
        return;
    }
    
    const productIds = [];
    checked.each(function() {
        productIds.push($(this).val());
    });
    
    $('#bulkAction').val(action);
    
    // Add product IDs to form
    $('#bulkForm').find('input[name="product_ids[]"]').remove();
    productIds.forEach(id => {
        $('#bulkForm').append(`<input type="hidden" name="product_ids[]" value="${id}">`);
    });
    
    let message = '';
    if (action === 'auto_generate') {
        message = `Auto-generate specifications for ${productIds.length} selected product(s)?`;
    } else {
        message = `Apply bulk edit to ${productIds.length} selected product(s)?`;
    }
    
    if (confirm(message)) {
        $('#bulkForm').submit();
    }
}
</script>
@endpush
