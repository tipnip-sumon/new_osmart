@extends('admin.layouts.app')

@section('title', 'Subcategory Details')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Subcategory Details</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.subcategories.index') }}">Subcategories</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $subcategory['name'] ?? 'Sample Subcategory' }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <!-- Subcategory Information -->
            <div class="col-xl-8">
                <!-- Basic Information -->
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">Subcategory Information</div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.subcategories.edit', $subcategory['id'] ?? 1) }}" class="btn btn-sm btn-success">
                                <i class="ri-edit-line me-1"></i> Edit
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteSubcategory()">
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
                                        <td>{{ $subcategory['name'] ?? 'Sample Subcategory' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Slug:</td>
                                        <td><code>{{ $subcategory['slug'] ?? 'sample-subcategory' }}</code></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Status:</td>
                                        <td>
                                            @if(($subcategory['status'] ?? 'Active') == 'Active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Parent Category:</td>
                                        <td>
                                            @if($subcategory['category_id'] ?? null)
                                                <a href="{{ route('admin.categories.show', $subcategory['category_id']) }}" class="badge bg-light text-dark">
                                                    {{ $subcategory['category_name'] ?? 'Electronics' }}
                                                </a>
                                            @else
                                                <span class="text-muted">No Parent Category</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Sort Order:</td>
                                        <td><span class="badge bg-light">{{ $subcategory['sort_order'] ?? 0 }}</span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-semibold text-muted" width="30%">Featured:</td>
                                        <td>
                                            @if($subcategory['is_featured'] ?? false)
                                                <span class="badge bg-warning"><i class="ri-star-fill"></i> Featured</span>
                                            @else
                                                <span class="text-muted">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Show in Menu:</td>
                                        <td>
                                            @if($subcategory['show_in_menu'] ?? true)
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Show in Footer:</td>
                                        <td>
                                            @if($subcategory['show_in_footer'] ?? false)
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Created:</td>
                                        <td>{{ date('M d, Y H:i', strtotime($subcategory['created_at'] ?? now())) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Updated:</td>
                                        <td>{{ date('M d, Y H:i', strtotime($subcategory['updated_at'] ?? now())) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if($subcategory['description'] ?? null)
                        <div class="mt-3">
                            <h6 class="fw-semibold mb-2">Description:</h6>
                            <p class="text-muted">{{ $subcategory['description'] ?? 'Sample subcategory description for demonstration purposes.' }}</p>
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
                                        <td>{{ $subcategory['meta_title'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Meta Description:</td>
                                        <td>{{ $subcategory['meta_description'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Meta Keywords:</td>
                                        <td>
                                            @if($subcategory['meta_keywords'] ?? null)
                                                @foreach(explode(',', $subcategory['meta_keywords']) as $keyword)
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

                <!-- Products in this Subcategory -->
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">Products ({{ count($products ?? []) }})</div>
                        <a href="{{ route('admin.products.create') }}?subcategory={{ $subcategory['id'] ?? 1 }}" class="btn btn-sm btn-primary">
                            <i class="ri-add-line me-1"></i> Add Product
                        </a>
                    </div>
                    <div class="card-body">
                        @if(count($products ?? []) > 0)
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products ?? [] as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $product['image'] ?? 'https://via.placeholder.com/40x40/e3f2fd/1976d2?text=IMG' }}" 
                                                     alt="{{ $product['name'] }}" class="avatar avatar-sm me-2">
                                                <div>
                                                    <a href="{{ route('admin.products.show', $product['id']) }}" class="fw-semibold text-primary">
                                                        {{ $product['name'] }}
                                                    </a>
                                                    <div class="text-muted fs-12">{{ $product['sku'] ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">${{ number_format($product['price'] ?? 0, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ ($product['stock'] ?? 0) > 0 ? 'success' : 'danger' }}-transparent">
                                                {{ $product['stock'] ?? 0 }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(($product['status'] ?? 'Active') == 'Active')
                                                <span class="badge bg-success-transparent">Active</span>
                                            @else
                                                <span class="badge bg-danger-transparent">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.products.show', $product['id']) }}" class="btn btn-icon btn-sm btn-info-transparent">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product['id']) }}" class="btn btn-icon btn-sm btn-success-transparent">
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
                                <i class="ri-shopping-bag-line fs-48 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold mb-1">No Products</h6>
                            <p class="text-muted mb-3">This subcategory doesn't have any products yet.</p>
                            <a href="{{ route('admin.products.create') }}?subcategory={{ $subcategory['id'] ?? 1 }}" class="btn btn-primary btn-sm">
                                <i class="ri-add-line me-1"></i> Add First Product
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4">
                <!-- Subcategory Image -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Subcategory Image</div>
                    </div>
                    <div class="card-body text-center">
                        @if($subcategory['image'] ?? null)
                            <img src="{{ $subcategory['image'] }}" alt="{{ $subcategory['name'] ?? 'Subcategory' }}" 
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
                                    <h4 class="fw-semibold mb-1">{{ $subcategory['products_count'] ?? 0 }}</h4>
                                    <p class="text-muted mb-0">Products</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h4 class="fw-semibold mb-1">{{ $subcategory['active_products'] ?? 0 }}</h4>
                                    <p class="text-muted mb-0">Active Products</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h4 class="fw-semibold mb-1">{{ $subcategory['views_count'] ?? 0 }}</h4>
                                    <p class="text-muted mb-0">Views</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h4 class="fw-semibold mb-1">${{ number_format($subcategory['total_sales'] ?? 0, 2) }}</h4>
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
                            <a href="{{ route('admin.subcategories.edit', $subcategory['id'] ?? 1) }}" class="btn btn-success">
                                <i class="ri-edit-line me-2"></i> Edit Subcategory
                            </a>
                            <a href="{{ route('admin.products.create') }}?subcategory={{ $subcategory['id'] ?? 1 }}" class="btn btn-primary">
                                <i class="ri-add-line me-2"></i> Add Product
                            </a>
                            <button type="button" class="btn btn-info" onclick="window.open('/subcategory/{{ $subcategory['slug'] ?? 'sample-subcategory' }}', '_blank')">
                                <i class="ri-external-link-line me-2"></i> View on Store
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="duplicateSubcategory()">
                                <i class="ri-file-copy-line me-2"></i> Duplicate
                            </button>
                            <button type="button" class="btn btn-warning" onclick="toggleStatus()">
                                <i class="ri-toggle-line me-2"></i> Toggle Status
                            </button>
                            <button type="button" class="btn btn-danger" onclick="deleteSubcategory()">
                                <i class="ri-delete-bin-line me-2"></i> Delete Subcategory
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Category Hierarchy -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Category Hierarchy</div>
                    </div>
                    <div class="card-body">
                        <div class="category-tree">
                            @if($subcategory['category_id'] ?? null)
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ri-folder-line text-warning me-2"></i>
                                    <a href="{{ route('admin.categories.show', $subcategory['category_id']) }}" class="text-primary">
                                        {{ $subcategory['category_name'] ?? 'Electronics' }}
                                    </a>
                                </div>
                                
                                <div class="d-flex align-items-center mb-2 ms-3 fw-semibold">
                                    <i class="ri-arrow-right-s-line text-muted me-1"></i>
                                    <i class="ri-folder-3-line text-success me-2"></i>
                                    {{ $subcategory['name'] ?? 'Sample Subcategory' }}
                                </div>
                            @else
                                <div class="d-flex align-items-center mb-2 fw-semibold">
                                    <i class="ri-folder-3-line text-success me-2"></i>
                                    {{ $subcategory['name'] ?? 'Sample Subcategory' }}
                                </div>
                                <p class="text-muted fs-12">This subcategory has no parent category assigned.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Related Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Related Information</div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Category ID:</span>
                            <span class="fw-semibold">{{ $subcategory['id'] ?? 1 }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Parent Category ID:</span>
                            <span class="fw-semibold">{{ $subcategory['category_id'] ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Database Table:</span>
                            <span class="fw-semibold"><code>subcategories</code></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">URL Slug:</span>
                            <span class="fw-semibold"><code>{{ $subcategory['slug'] ?? 'sample-subcategory' }}</code></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body d-flex justify-content-between">
                        <a href="{{ route('admin.subcategories.index') }}" class="btn btn-light">
                            <i class="ri-arrow-left-line me-1"></i> Back to Subcategories
                        </a>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.subcategories.edit', $subcategory['id'] ?? 1) }}" class="btn btn-success">
                                <i class="ri-edit-line me-1"></i> Edit Subcategory
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
                <h5 class="modal-title">Delete Subcategory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the subcategory "<strong>{{ $subcategory['name'] ?? 'Sample Subcategory' }}</strong>"?</p>
                <div class="alert alert-warning">
                    <i class="ri-alert-line me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone and will also delete:
                    <ul class="mb-0 mt-2">
                        <li>All products in this subcategory ({{ $subcategory['products_count'] ?? 0 }})</li>
                        <li>All related data and statistics</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.subcategories.destroy', $subcategory['id'] ?? 1) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Subcategory</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Status Toggle Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Toggle Subcategory Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to change the status of this subcategory?</p>
                <div class="alert alert-info">
                    <i class="ri-information-line me-2"></i>
                    Current Status: <strong>{{ ucfirst($subcategory['status'] ?? 'Active') }}</strong><br>
                    New Status: <strong>{{ ($subcategory['status'] ?? 'Active') == 'Active' ? 'Inactive' : 'Active' }}</strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="confirmStatusToggle()">Change Status</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function deleteSubcategory() {
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    function duplicateSubcategory() {
        Swal.fire({
            title: 'Duplicate Subcategory',
            text: 'Are you sure you want to duplicate this subcategory?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, duplicate it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Here you would make an AJAX call to duplicate the subcategory
                Swal.fire({
                    title: 'Duplicated!',
                    text: 'Subcategory has been duplicated successfully.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            }
        });
    }

    function toggleStatus() {
        new bootstrap.Modal(document.getElementById('statusModal')).show();
    }

    function confirmStatusToggle() {
        const subcategoryId = {{ $subcategory['id'] ?? 1 }};
        const currentStatus = '{{ $subcategory["status"] ?? "Active" }}';
        const newStatus = currentStatus === 'Active' ? 'Inactive' : 'Active';
        
        // Close the modal
        bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
        
        // Make AJAX request to toggle status
        fetch(`/admin/subcategories/${subcategoryId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Status Updated!',
                    text: `Subcategory status changed to ${newStatus}.`,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire('Error!', 'Failed to update status.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error!', 'An error occurred while updating status.', 'error');
        });
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection
