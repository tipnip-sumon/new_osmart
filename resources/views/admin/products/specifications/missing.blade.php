@extends('admin.layouts.app')

@section('title', 'Products with Missing Specifications')

@push('styles')
<style>
    .missing-spec-badge {
        background: #ffecb3;
        color: #f57c00;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8em;
    }
    .spec-missing {
        background: #ffebee;
        border-left: 4px solid #f44336;
    }
    .action-buttons .btn {
        margin-right: 5px;
        margin-bottom: 5px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Products with Missing Specifications</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.specifications.index') }}">Product Specifications</a></li>
                        <li class="breadcrumb-item active">Missing Specifications</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5>Products Missing Specifications ({{ $products->total() }})</h5>
                        </div>
                        <div class="col-auto">
                            <div class="action-buttons">
                                <a href="{{ route('admin.products.specifications.generate') }}" class="btn btn-success">
                                    <i class="bx bx-magic-wand"></i> Auto Generate
                                </a>
                                <a href="{{ route('admin.products.specifications.bulk') }}?missing_only=1" class="btn btn-primary">
                                    <i class="bx bx-edit-alt"></i> Bulk Update
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select" name="category_filter" onchange="filterProducts()">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="missing_type" onchange="filterProducts()">
                                <option value="">All Missing Types</option>
                                <option value="specifications">Missing Specifications</option>
                                <option value="features">Missing Features</option>
                                <option value="weight">Missing Weight</option>
                                <option value="material">Missing Material</option>
                                <option value="warranty">Missing Warranty</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search products..." name="search" value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="button" onclick="searchProducts()">
                                    <i class="bx bx-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary w-100" onclick="selectAll()">
                                <i class="bx bx-check-square"></i> Select All
                            </button>
                        </div>
                    </div>

                    <!-- Products Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()">
                                    </th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Missing Specifications</th>
                                    <th>Priority</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr class="{{ getMissingSpecCount($product) > 3 ? 'spec-missing' : '' }}">
                                    <td>
                                        <input type="checkbox" class="product-checkbox" value="{{ $product->id }}">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="" class="rounded me-2" width="40" height="40">
                                            @else
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="bx bx-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ Str::limit($product->name, 40) }}</strong>
                                                <br>
                                                <small class="text-muted">SKU: {{ $product->sku ?: 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $product->category->name ?? 'Uncategorized' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $missingSpecs = [];
                                            if (!$product->specifications) $missingSpecs[] = 'Specifications';
                                            if (!$product->features) $missingSpecs[] = 'Features';
                                            if (!$product->weight) $missingSpecs[] = 'Weight';
                                            if (!$product->material) $missingSpecs[] = 'Material';
                                            if (!$product->warranty_period) $missingSpecs[] = 'Warranty';
                                        @endphp
                                        
                                        @foreach($missingSpecs as $spec)
                                            <span class="missing-spec-badge me-1">{{ $spec }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @php $missingCount = count($missingSpecs); @endphp
                                        @if($missingCount >= 4)
                                            <span class="badge bg-danger">High</span>
                                        @elseif($missingCount >= 2)
                                            <span class="badge bg-warning">Medium</span>
                                        @else
                                            <span class="badge bg-success">Low</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.products.specifications.edit', $product->id) }}" 
                                               class="btn btn-primary" title="Edit Specifications">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-success" 
                                                    onclick="autoGenerate({{ $product->id }})" title="Auto Generate">
                                                <i class="bx bx-magic-wand"></i>
                                            </button>
                                            <a href="{{ route('admin.products.show', $product->id) }}" 
                                               class="btn btn-info" title="View Product">
                                                <i class="bx bx-show"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="bx bx-check-circle text-success" style="font-size: 48px;"></i>
                                        <h5 class="mt-2">All Products Have Complete Specifications!</h5>
                                        <p class="text-muted">All your products have the required specifications filled out.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $products->links() }}
                    </div>
                    @endif

                    <!-- Bulk Actions -->
                    @if($products->count() > 0)
                    <div class="border-top pt-3 mt-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <span id="selectedCount">0</span> products selected
                            </div>
                            <div class="col-auto">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success" onclick="bulkAutoGenerate()" disabled id="bulkGenerateBtn">
                                        <i class="bx bx-magic-wand"></i> Auto Generate Selected
                                    </button>
                                    <button type="button" class="btn btn-primary" onclick="bulkEdit()" disabled id="bulkEditBtn">
                                        <i class="bx bx-edit-alt"></i> Bulk Edit Selected
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@php
function getMissingSpecCount($product) {
    $count = 0;
    if (!$product->specifications) $count++;
    if (!$product->features) $count++;
    if (!$product->weight) $count++;
    if (!$product->material) $count++;
    if (!$product->warranty_period) $count++;
    return $count;
}
@endphp

@push('scripts')
<script>
$(document).ready(function() {
    updateSelectedCount();
    
    $('.product-checkbox').change(function() {
        updateSelectedCount();
    });
});

function updateSelectedCount() {
    const count = $('.product-checkbox:checked').length;
    $('#selectedCount').text(count);
    
    // Enable/disable bulk action buttons
    $('#bulkGenerateBtn, #bulkEditBtn').prop('disabled', count === 0);
}

function toggleSelectAll() {
    const isChecked = $('#selectAllCheckbox').is(':checked');
    $('.product-checkbox').prop('checked', isChecked);
    updateSelectedCount();
}

function selectAll() {
    $('.product-checkbox').prop('checked', true);
    $('#selectAllCheckbox').prop('checked', true);
    updateSelectedCount();
}

function filterProducts() {
    const categoryId = $('select[name="category_filter"]').val();
    const missingType = $('select[name="missing_type"]').val();
    const search = $('input[name="search"]').val();
    
    let url = '{{ route("admin.products.specifications.missing") }}?';
    const params = [];
    
    if (categoryId) params.push(`category_id=${categoryId}`);
    if (missingType) params.push(`missing_type=${missingType}`);
    if (search) params.push(`search=${encodeURIComponent(search)}`);
    
    if (params.length > 0) {
        url += params.join('&');
    }
    
    window.location.href = url;
}

function searchProducts() {
    filterProducts();
}

function autoGenerate(productId) {
    if (confirm('Auto-generate specifications for this product?')) {
        fetch('{{ route("admin.products.specifications.generate.post") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                products: [productId],
                generate_features: true,
                generate_specifications: true,
                generate_warranty: true
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error generating specifications');
            }
        });
    }
}

function bulkAutoGenerate() {
    const selected = [];
    $('.product-checkbox:checked').each(function() {
        selected.push(parseInt($(this).val()));
    });
    
    if (selected.length === 0) {
        alert('Please select products first');
        return;
    }
    
    if (confirm(`Auto-generate specifications for ${selected.length} selected products?`)) {
        fetch('{{ route("admin.products.specifications.generate.post") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                products: selected,
                generate_features: true,
                generate_specifications: true,
                generate_warranty: true
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error generating specifications');
            }
        });
    }
}

function bulkEdit() {
    const selected = [];
    $('.product-checkbox:checked').each(function() {
        selected.push($(this).val());
    });
    
    if (selected.length === 0) {
        alert('Please select products first');
        return;
    }
    
    // Redirect to bulk edit page with selected products
    const params = selected.map(id => `products[]=${id}`).join('&');
    window.location.href = `{{ route('admin.products.specifications.bulk') }}?missing_only=1&${params}`;
}
</script>
@endpush
