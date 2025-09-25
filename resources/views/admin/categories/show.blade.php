@extends('admin.layouts.app')

@section('title', 'Category Details')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Category Details</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $category['name'] }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <!-- Category Information -->
            <div class="col-xl-8">
                <!-- Basic Information -->
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">Category Information</div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.categories.edit', $category['id']) }}" class="btn btn-sm btn-success">
                                <i class="ri-edit-line me-1"></i> Edit
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteCategory()">
                                <i class="ri-delete-bin-line me-1"></i> Delete
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-semibold text-muted" width="30%">Name:</td>
                                        <td>{{ $category['name'] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Slug:</td>
                                        <td><code>{{ $category['slug'] }}</code></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Status:</td>
                                        <td>
                                            @if($category['status'] == 'Active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Parent Category:</td>
                                        <td>
                                            @if($category['parent_id'])
                                                <span class="badge bg-light text-dark">Parent Category Name</span>
                                            @else
                                                <span class="text-muted">Root Category</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Sort Order:</td>
                                        <td><span class="badge bg-light">{{ $category['sort_order'] }}</span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-semibold text-muted" width="30%">Featured:</td>
                                        <td>
                                            @if($category['is_featured'])
                                                <span class="badge bg-warning"><i class="ri-star-fill"></i> Featured</span>
                                            @else
                                                <span class="text-muted">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Show in Menu:</td>
                                        <td>
                                            @if($category['show_in_menu'])
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Show in Footer:</td>
                                        <td>
                                            @if($category['show_in_footer'])
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Created:</td>
                                        <td>{{ date('M d, Y H:i', strtotime($category['created_at'])) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Updated:</td>
                                        <td>{{ date('M d, Y H:i', strtotime($category['updated_at'])) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if($category['description'])
                        <div class="mt-3">
                            <h6 class="fw-semibold mb-2">Description:</h6>
                            <p class="text-muted">{{ $category['description'] }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- SEO Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">SEO Information</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-semibold text-muted" width="20%">Meta Title:</td>
                                        <td>{{ $category['meta_title'] ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Meta Description:</td>
                                        <td>{{ $category['meta_description'] ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Meta Keywords:</td>
                                        <td>
                                            @if($category['meta_keywords'])
                                                @foreach(explode(',', $category['meta_keywords']) as $keyword)
                                                    <span class="badge bg-light text-dark me-1">{{ trim($keyword) }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subcategories -->
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">Subcategories ({{ count($subcategories) }})</div>
                        <a href="{{ route('admin.categories.create') }}?parent={{ $category['id'] }}" class="btn btn-sm btn-primary">
                            <i class="ri-add-line me-1"></i> Add Subcategory
                        </a>
                    </div>
                    <div class="card-body">
                        @if(count($subcategories) > 0)
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Products</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subcategories as $subcategory)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.categories.show', $subcategory['id']) }}" class="fw-semibold text-primary">
                                                {{ $subcategory['name'] }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-transparent">{{ $subcategory['products_count'] }}</span>
                                        </td>
                                        <td>
                                            @if($subcategory['status'] == 'Active')
                                                <span class="badge bg-success-transparent">Active</span>
                                            @else
                                                <span class="badge bg-danger-transparent">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.categories.show', $subcategory['id']) }}" class="btn btn-icon btn-sm btn-info-transparent">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('admin.categories.edit', $subcategory['id']) }}" class="btn btn-icon btn-sm btn-success-transparent">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="ri-folder-line fs-48 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold mb-1">No Subcategories</h6>
                            <p class="text-muted mb-3">This category doesn't have any subcategories yet.</p>
                            <a href="{{ route('admin.categories.create') }}?parent={{ $category['id'] }}" class="btn btn-primary btn-sm">
                                <i class="ri-add-line me-1"></i> Create First Subcategory
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4">
                <!-- Category Image -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Category Image</div>
                    </div>
                    <div class="card-body text-center">
                        @if($category['image'])
                            <img src="{{ $category['image'] }}" alt="{{ $category['name'] }}" 
                                 class="img-fluid rounded" style="max-height: 300px;">
                        @else
                            <div class="py-5">
                                <i class="ri-image-line fs-48 text-muted"></i>
                                <p class="text-muted mt-2">No image uploaded</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Statistics -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Statistics</div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-center">
                                    <h4 class="fw-semibold mb-1">{{ $category['products_count'] }}</h4>
                                    <p class="text-muted mb-0">Products</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h4 class="fw-semibold mb-1">{{ count($subcategories) }}</h4>
                                    <p class="text-muted mb-0">Subcategories</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h4 class="fw-semibold mb-1">{{ $category['views_count'] ?? 0 }}</h4>
                                    <p class="text-muted mb-0">Views</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h4 class="fw-semibold mb-1">${{ number_format($category['total_sales'] ?? 0, 2) }}</h4>
                                    <p class="text-muted mb-0">Sales</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Quick Actions</div>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.categories.edit', $category['id']) }}" class="btn btn-success">
                                <i class="ri-edit-line me-2"></i> Edit Category
                            </a>
                            <a href="{{ route('admin.categories.create') }}?parent={{ $category['id'] }}" class="btn btn-primary">
                                <i class="ri-add-line me-2"></i> Add Subcategory
                            </a>
                            <button type="button" class="btn btn-info" onclick="window.open('/category/{{ $category['slug'] }}', '_blank')">
                                <i class="ri-external-link-line me-2"></i> View on Store
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="duplicateCategory()">
                                <i class="ri-file-copy-line me-2"></i> Duplicate
                            </button>
                            <button type="button" class="btn btn-danger" onclick="deleteCategory()">
                                <i class="ri-delete-bin-line me-2"></i> Delete Category
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Category Tree (if has parent or children) -->
                @if($category['parent_id'] || count($subcategories) > 0)
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Category Hierarchy</div>
                    </div>
                    <div class="card-body">
                        <div class="category-tree">
                            @if($category['parent_id'])
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ri-arrow-up-line text-muted me-2"></i>
                                    <a href="{{ route('admin.categories.show', $category['parent_id']) }}" class="text-primary">
                                        Parent Category
                                    </a>
                                </div>
                            @endif
                            
                            <div class="d-flex align-items-center mb-2 fw-semibold">
                                <i class="ri-folder-fill text-warning me-2"></i>
                                {{ $category['name'] }}
                            </div>
                            
                            @foreach($subcategories as $subcategory)
                                <div class="d-flex align-items-center mb-1 ms-3">
                                    <i class="ri-arrow-right-s-line text-muted me-1"></i>
                                    <a href="{{ route('admin.categories.show', $subcategory['id']) }}" class="text-primary">
                                        {{ $subcategory['name'] }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body d-flex justify-content-between">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-light">
                            <i class="ri-arrow-left-line me-1"></i> Back to Categories
                        </a>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.categories.edit', $category['id']) }}" class="btn btn-success">
                                <i class="ri-edit-line me-1"></i> Edit Category
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the category "<strong>{{ $category['name'] }}</strong>"?</p>
                <div class="alert alert-warning">
                    <i class="ri-alert-line me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone and will also delete:
                    <ul class="mb-0 mt-2">
                        <li>All subcategories ({{ count($subcategories) }})</li>
                        <li>All products in this category ({{ $category['products_count'] }})</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.categories.destroy', $category['id']) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Category</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function deleteCategory() {
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    function duplicateCategory() {
        if (confirm('Are you sure you want to duplicate this category?')) {
            // Here you would make an AJAX call to duplicate the category
            alert('Category duplicated successfully!');
            window.location.reload();
        }
    }
</script>
@endpush
@endsection
