@extends('admin.layouts.app')

@section('title', 'My Products')

@section('content')
<!-- Start::page-header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div>
        <h2 class="page-title fw-semibold fs-18 mb-0">My Products</h2>
        <p class="fw-medium fs-13 text-muted mb-0">Manage your product inventory</p>
    </div>
    <div class="btn-list">
        <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-1"></i>Add Product
        </a>
    </div>
</div>
<!-- End::page-header -->

<!-- Start::row -->
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    Products List
                </div>
                <div class="d-flex gap-2">
                    <!-- Search Form -->
                    <form method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($products->count() > 0)
                    <!-- Bulk Actions -->
                    <form id="bulk-action-form" method="POST" action="{{ route('vendor.products.bulk-action') }}">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex gap-2">
                                <select name="action" id="bulk-action" class="form-select" style="width: auto;">
                                    <option value="">Bulk Actions</option>
                                    <option value="activate">Activate</option>
                                    <option value="deactivate">Deactivate</option>
                                    <option value="delete">Delete</option>
                                </select>
                                <button type="button" id="apply-bulk" class="btn btn-sm btn-secondary">Apply</button>
                            </div>
                            <div class="text-muted">
                                Total: {{ $products->total() }} products
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>Image</th>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-checkbox">
                                        </td>
                                        <td>
                                            @if($product->images)
                                                @php 
                                                    // Handle both array and JSON string formats
                                                    if (is_array($product->images)) {
                                                        $images = $product->images;
                                                    } else {
                                                        $images = json_decode($product->images, true);
                                                    }
                                                @endphp
                                                @if(is_array($images) && count($images) > 0)
                                                    @php
                                                        // Handle both old and new image formats
                                                        $imageUrl = '';
                                                        $firstImage = $images[0];
                                                        
                                                        if (is_array($firstImage)) {
                                                            // New format with sizes
                                                            if (isset($firstImage['sizes']['thumbnail']['storage_url'])) {
                                                                $imageUrl = $firstImage['sizes']['thumbnail']['storage_url'];
                                                            } elseif (isset($firstImage['sizes']['small']['storage_url'])) {
                                                                $imageUrl = $firstImage['sizes']['small']['storage_url'];
                                                            } elseif (isset($firstImage['sizes']['original']['storage_url'])) {
                                                                $imageUrl = $firstImage['sizes']['original']['storage_url'];
                                                            }
                                                        } else {
                                                            // Old format - direct path
                                                            $imageUrl = asset('storage/' . $firstImage);
                                                        }
                                                    @endphp
                                                    @if($imageUrl)
                                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                            <i class="ri-image-line"></i>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                        <i class="ri-image-line"></i>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="ri-image-line"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <a href="{{ route('vendor.products.show', $product) }}" class="fw-semibold text-primary">{{ $product->name }}</a>
                                                @if($product->short_description)
                                                    <div class="text-muted fs-12">{{ Str::limit($product->short_description, 50) }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $product->sku }}</td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>৳{{ number_format($product->price, 2) }}</td>
                                        <td>
                                            @if($product->track_quantity)
                                                <span class="badge {{ $product->stock_quantity > 10 ? 'bg-success' : ($product->stock_quantity > 0 ? 'bg-warning' : 'bg-danger') }}">
                                                    {{ $product->stock_quantity }}
                                                </span>
                                            @else
                                                <span class="text-muted">Not tracked</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $product->status == 'active' ? 'success' : ($product->status == 'inactive' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($product->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('vendor.products.show', $product) }}">View</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('vendor.products.edit', $product) }}">Edit</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('vendor.products.destroy', $product) }}" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
                        </div>
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ri-shopping-bag-line fs-48 text-muted"></i>
                        <h5 class="mt-3">No Products Found</h5>
                        <p class="text-muted">You haven't added any products yet.</p>
                        <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">Add Your First Product</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- End::row -->

@push('scripts')
<script>
    // Select all checkbox functionality
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.product-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Bulk action functionality
    document.getElementById('apply-bulk').addEventListener('click', function() {
        const action = document.getElementById('bulk-action').value;
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        
        if (!action) {
            alert('Please select an action.');
            return;
        }
        
        if (checkedBoxes.length === 0) {
            alert('Please select at least one product.');
            return;
        }
        
        if (action === 'delete') {
            if (!confirm('Are you sure you want to delete the selected products? This action cannot be undone.')) {
                return;
            }
        }
        
        document.getElementById('bulk-action-form').submit();
    });
</script>
@endpush
@endsection
