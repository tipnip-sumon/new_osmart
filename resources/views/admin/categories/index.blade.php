@extends('admin.layouts.app')

@section('title', 'Categories Management')

@push('styles')
<style>
    .category-badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    .category-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
    }
    .status-toggle {
        cursor: pointer;
    }
    .featured-toggle {
        cursor: pointer;
    }
    .category-stats-card {
        transition: all 0.3s ease;
    }
    .category-stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .filter-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .table-actions .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .bulk-actions {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 15px;
        display: none;
    }
    .parent-category {
        font-weight: 600;
        color: #007bff;
    }
    .subcategory {
        font-style: italic;
        color: #6c757d;
        padding-left: 20px;
    }
    .hierarchy-indicator {
        color: #28a745;
        margin-right: 5px;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Categories Management</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Categories</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card custom-card category-stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-fill">
                                <p class="mb-1 text-muted">Total Categories</p>
                                <h4 class="mb-0 text-primary">{{ isset($stats['total']) ? $stats['total'] : 0 }}</h4>
                            </div>
                            <div class="ms-3">
                                <span class="avatar avatar-md bg-primary-transparent">
                                    <i class="ri-list-check-2 fs-18"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card custom-card category-stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-fill">
                                <p class="mb-1 text-muted">Active Categories</p>
                                <h4 class="mb-0 text-success">{{ isset($stats['active']) ? $stats['active'] : 0 }}</h4>
                            </div>
                            <div class="ms-3">
                                <span class="avatar avatar-md bg-success-transparent">
                                    <i class="ri-check-line fs-18"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card custom-card category-stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-fill">
                                <p class="mb-1 text-muted">Featured Categories</p>
                                <h4 class="mb-0 text-warning">{{ isset($stats['featured']) ? $stats['featured'] : 0 }}</h4>
                            </div>
                            <div class="ms-3">
                                <span class="avatar avatar-md bg-warning-transparent">
                                    <i class="ri-star-line fs-18"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card custom-card category-stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-fill">
                                <p class="mb-1 text-muted">Total Products</p>
                                <h4 class="mb-0 text-info">{{ isset($stats['total_products']) ? $stats['total_products'] : 0 }}</h4>
                            </div>
                            <div class="ms-3">
                                <span class="avatar avatar-md bg-info-transparent">
                                    <i class="ri-shopping-bag-line fs-18"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.categories.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Search Categories</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                               placeholder="Search by name, slug...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Featured</label>
                        <select class="form-select" name="featured">
                            <option value="">All Categories</option>
                            <option value="yes" {{ request('featured') == 'yes' ? 'selected' : '' }}>Featured</option>
                            <option value="no" {{ request('featured') == 'no' ? 'selected' : '' }}>Not Featured</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Per Page</label>
                        <select class="form-select" name="per_page">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-search-line me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                <i class="ri-refresh-line me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Bulk Actions -->
        <div class="bulk-actions" id="bulkActions">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span id="selectedCount">0</span> categories selected
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-success" onclick="bulkAction('activate')">
                        <i class="ri-check-line me-1"></i> Activate
                    </button>
                    <button type="button" class="btn btn-sm btn-warning" onclick="bulkAction('deactivate')">
                        <i class="ri-close-line me-1"></i> Deactivate
                    </button>
                    <button type="button" class="btn btn-sm btn-info" onclick="bulkAction('feature')">
                        <i class="ri-star-line me-1"></i> Feature
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="bulkAction('delete')">
                        <i class="ri-delete-bin-line me-1"></i> Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <h6 class="fw-semibold mb-0">
                                Showing {{ isset($pagination['from']) ? $pagination['from'] : 1 }}-{{ isset($pagination['to']) ? $pagination['to'] : count($categories) }} 
                                of {{ isset($pagination['total']) ? $pagination['total'] : count($categories) }} categories
                            </h6>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-secondary btn-sm" onclick="window.location.reload()">
                                <i class="ri-refresh-line me-1"></i> Refresh
                            </button>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                                <i class="ri-add-line me-1"></i> Add Category
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories List -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">Categories List</div>
                        <div class="d-flex gap-2">
                            <div class="dropdown">
                                <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="ri-sort-desc me-1"></i> Sort By
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_direction' => 'asc']) }}">Name (A-Z)</a></li>
                                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_direction' => 'desc']) }}">Name (Z-A)</a></li>
                                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_direction' => 'desc']) }}">Newest First</a></li>
                                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_direction' => 'asc']) }}">Oldest First</a></li>
                                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'products_count', 'sort_direction' => 'desc']) }}">Most Products</a></li>
                                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'sort_order', 'sort_direction' => 'asc']) }}">Sort Order</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </th>
                                        <th>Image</th>
                                        <th>Category</th>
                                        <th>Hierarchy</th>
                                        <th>Products</th>
                                        <th>Status</th>
                                        <th>Featured</th>
                                        <th>Sort Order</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $category)
                                    <tr>
                                        <td>
                                            <input class="form-check-input category-checkbox" type="checkbox" value="{{ $category->id }}">
                                        </td>
                                        <td>
                                            <span class="avatar avatar-md">
                                                @if($category->image)
                                                    @php
                                                        $imagePath = asset('storage/' . $category->image);
                                                        $publicPath = public_path('storage/' . $category->image);
                                                        $storageExists = file_exists(storage_path('app/public/' . $category->image));
                                                        $publicExists = file_exists($publicPath);
                                                    @endphp
                                                    <img src="{{ $imagePath }}" 
                                                         alt="{{ $category->name }}" 
                                                         class="category-image"
                                                         data-debug-path="{{ $category->image }}"
                                                         data-storage-exists="{{ $storageExists ? 'true' : 'false' }}"
                                                         data-public-exists="{{ $publicExists ? 'true' : 'false' }}"
                                                         data-full-url="{{ $imagePath }}"
                                                         onerror="handleImageError(this, '{{ $category->image }}', '{{ $imagePath }}');">
                                                @else
                                                    <img src="https://via.placeholder.com/50x50/e3f2fd/1976d2?text=IMG" 
                                                         alt="No Image" 
                                                         class="category-image">
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <a href="{{ route('admin.categories.show', $category->id) }}" class="fw-semibold text-primary">
                                                        {{ $category->name }}
                                                    </a>
                                                    <div class="text-muted fs-12">{{ $category->slug }}</div>
                                                    @if($category->description)
                                                        <div class="text-muted fs-11">{{ Str::limit($category->description, 50) }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($category->parent_id)
                                                <span class="hierarchy-indicator">└─</span>
                                                <span class="subcategory">Subcategory</span>
                                                @if($category->parent)
                                                    <div class="text-muted fs-11">Parent: {{ $category->parent->name }}</div>
                                                @endif
                                            @else
                                                <span class="parent-category">Root Category</span>
                                                @php
                                                    $subcats = $category->children ? $category->children->count() : 0;
                                                @endphp
                                                @if($subcats > 0)
                                                    <div class="text-muted fs-11">{{ $subcats }} subcategories</div>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">0</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $category->status == 'active' ? 'success' : 'danger' }} status-toggle" 
                                                  data-id="{{ $category->id }}" data-status="{{ $category->status }}">
                                                {{ ucfirst($category->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $category->is_featured ? 'warning' : 'secondary' }} featured-toggle" 
                                                  data-id="{{ $category->id }}" data-featured="{{ $category->is_featured ? 'true' : 'false' }}">
                                                {{ $category->is_featured ? 'Featured' : 'Not Featured' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $category->sort_order }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $category->created_at->format('M d, Y') }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 table-actions">
                                                <a href="{{ route('admin.categories.show', $category->id) }}" 
                                                   class="btn btn-primary-light btn-sm" title="View">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                                   class="btn btn-warning-light btn-sm" title="Edit">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger-light btn-sm" 
                                                        onclick="deleteCategory({{ $category->id }})" title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="{{ route('admin.categories.show', $category->id) }}">
                                                            <i class="ri-eye-line me-2"></i>View Details</a></li>
                                                        <li><a class="dropdown-item" href="{{ route('admin.categories.edit', $category->id) }}">
                                                            <i class="ri-edit-line me-2"></i>Edit Category</a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-warning" href="#" onclick="toggleFeatured({{ $category->id }}, {{ $category->is_featured ? 'false' : 'true' }})">
                                                            <i class="ri-star-line me-2"></i>{{ $category->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}</a></li>
                                                        <li><a class="dropdown-item text-info" href="#" onclick="toggleStatus({{ $category->id }}, '{{ $category->status == 'active' ? 'inactive' : 'active' }}')">
                                                            <i class="ri-toggle-line me-2"></i>{{ $category->status == 'active' ? 'Deactivate' : 'Activate' }}</a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteCategory({{ $category->id }})">
                                                            <i class="ri-delete-bin-line me-2"></i>Delete Category</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ri-folder-2-line fs-48 text-muted mb-2"></i>
                                                <h6 class="fw-semibold mb-1">No Categories Found</h6>
                                                <p class="text-muted mb-3">No categories match your current filters.</p>
                                                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                                                    <i class="ri-add-line me-1"></i> Create First Category
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if(isset($pagination) && $pagination['total'] > $pagination['per_page'])
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <nav aria-label="Categories pagination">
                            <ul class="pagination justify-content-center mb-0">
                                @if($pagination['current_page'] > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] - 1]) }}">
                                            <i class="ri-arrow-left-line"></i> Previous
                                        </a>
                                    </li>
                                @endif
                                
                                @for($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['last_page'], $pagination['current_page'] + 2); $i++)
                                    <li class="page-item {{ $i == $pagination['current_page'] ? 'active' : '' }}">
                                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                
                                @if($pagination['current_page'] < $pagination['last_page'])
                                    <li class="page-item">
                                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] + 1]) }}">
                                            Next <i class="ri-arrow-right-line"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                Showing {{ $pagination['from'] }} to {{ $pagination['to'] }} of {{ $pagination['total'] }} results
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Advanced image debugging function
function handleImageError(img, imagePath, fullUrl) {
    console.group('🖼️ Image Load Error Debug');
    console.log('Image Path:', imagePath);
    console.log('Full URL:', fullUrl);
    console.log('Storage Exists:', img.dataset.storageExists);
    console.log('Public Exists:', img.dataset.publicExists);
    console.log('Current Domain:', window.location.origin);
    
    // Test different path variations
    const testPaths = [
        fullUrl,
        window.location.origin + '/storage/' + imagePath,
        window.location.origin + '/public/storage/' + imagePath,
        '{{ asset("") }}' + 'storage/' + imagePath,
        '/storage/' + imagePath
    ];
    
    console.log('Testing alternative paths:');
    testPaths.forEach((path, index) => {
        console.log(`Path ${index + 1}:`, path);
    });
    
    // Show debug info in console
    fetch(fullUrl, { method: 'HEAD' })
        .then(response => {
            console.log('HTTP Status:', response.status);
            console.log('Response Headers:', [...response.headers.entries()]);
            if (response.status === 404) {
                console.error('❌ File not found at:', fullUrl);
            } else if (response.status === 403) {
                console.error('❌ Access forbidden for:', fullUrl);
            }
        })
        .catch(error => {
            console.error('❌ Network error:', error);
        });
    
    console.groupEnd();
    
    // Set placeholder
    img.src = 'https://via.placeholder.com/50x50/e3f2fd/1976d2?text=ERR';
    img.onerror = null;
    
    // Add visual indicator for debugging
    img.style.border = '2px solid red';
    img.title = 'Image failed to load: ' + imagePath;
}

// Check storage link on page load
document.addEventListener('DOMContentLoaded', function() {
    // Test storage link
    fetch('/storage/test.txt')
        .then(response => {
            if (response.status === 404) {
                console.warn('⚠️ Storage link might not be working. Run: php artisan storage:link');
            }
        })
        .catch(error => {
            console.warn('⚠️ Storage directory test failed:', error);
        });
    
    // Log all image debugging info
    const images = document.querySelectorAll('img[data-debug-path]');
    if (images.length > 0) {
        console.group('📋 Image Debug Summary');
        images.forEach((img, index) => {
            console.log(`Image ${index + 1}:`, {
                path: img.dataset.debugPath,
                storageExists: img.dataset.storageExists,
                publicExists: img.dataset.publicExists,
                fullUrl: img.dataset.fullUrl
            });
        });
        console.groupEnd();
    }
});
// Category management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Select all functionality
    const selectAll = document.getElementById('selectAll');
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            categoryCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }

    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.category-checkbox:checked');
        if (selectedCount) {
            selectedCount.textContent = checkedBoxes.length;
        }
        
        if (checkedBoxes.length > 0 && bulkActions) {
            bulkActions.style.display = 'block';
        } else if (bulkActions) {
            bulkActions.style.display = 'none';
        }
        
        // Update select all checkbox
        if (selectAll) {
            selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < categoryCheckboxes.length;
            selectAll.checked = checkedBoxes.length === categoryCheckboxes.length;
        }
    }

    // Status toggle functionality
    document.querySelectorAll('.status-toggle').forEach(badge => {
        badge.addEventListener('click', function() {
            const id = this.dataset.id;
            const currentStatus = this.dataset.status;
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            toggleStatus(id, newStatus);
        });
    });

    // Featured toggle functionality
    document.querySelectorAll('.featured-toggle').forEach(badge => {
        badge.addEventListener('click', function() {
            const id = this.dataset.id;
            const currentFeatured = this.dataset.featured === 'true';
            toggleFeatured(id, !currentFeatured);
        });
    });
});

// Delete category function
function deleteCategory(id) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete Category?',
            text: 'Are you sure you want to delete this category? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Delete!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                performDelete(id);
            }
        });
    } else {
        if (confirm('Are you sure you want to delete this category?')) {
            performDelete(id);
        }
    }
}

function performDelete(id) {
    // Make AJAX request to delete category
    fetch(`/admin/categories/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Deleted!',
                    text: data.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                alert(data.message);
                location.reload();
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Error!',
                    text: data.message,
                    icon: 'error'
                });
            } else {
                alert('Error: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error!',
                text: 'Failed to delete category',
                icon: 'error'
            });
        } else {
            alert('Failed to delete category');
        }
    });
}

// Toggle status function
function toggleStatus(id, newStatus) {
    fetch(`/admin/categories/bulk-action`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: newStatus === 'active' ? 'activate' : 'deactivate',
            ids: [id]
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Success!',
                    text: `Category ${newStatus} successfully`,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                alert(`Category ${newStatus} successfully`);
                location.reload();
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Error!',
                    text: data.message,
                    icon: 'error'
                });
            } else {
                alert('Error: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error!',
                text: 'Failed to update status',
                icon: 'error'
            });
        } else {
            alert('Failed to update status');
        }
    });
}

// Toggle featured function
function toggleFeatured(id, featured) {
    fetch(`/admin/categories/bulk-action`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: featured ? 'feature' : 'unfeature',
            ids: [id]
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Success!',
                    text: `Category ${featured ? 'featured' : 'unfeatured'} successfully`,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                alert(`Category ${featured ? 'featured' : 'unfeatured'} successfully`);
                location.reload();
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Error!',
                    text: data.message,
                    icon: 'error'
                });
            } else {
                alert('Error: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error!',
                text: 'Failed to update featured status',
                icon: 'error'
            });
        } else {
            alert('Failed to update featured status');
        }
    });
}

// Bulk action function
function bulkAction(action) {
    const checkedBoxes = document.querySelectorAll('.category-checkbox:checked');
    const ids = Array.from(checkedBoxes).map(checkbox => parseInt(checkbox.value));
    
    if (ids.length === 0) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'No Selection',
                text: 'Please select at least one category',
                icon: 'warning'
            });
        } else {
            alert('Please select at least one category');
        }
        return;
    }

    let actionText = action.charAt(0).toUpperCase() + action.slice(1);
    if (action === 'feature') actionText = 'Feature';
    if (action === 'unfeature') actionText = 'Unfeature';

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: `${actionText} Categories?`,
            text: `Are you sure you want to ${action} ${ids.length} selected categories?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: action === 'delete' ? '#d33' : '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${actionText}!`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                performBulkAction(action, ids);
            }
        });
    } else {
        if (confirm(`Are you sure you want to ${action} ${ids.length} selected categories?`)) {
            performBulkAction(action, ids);
        }
    }
}

function performBulkAction(action, ids) {
    fetch(`/admin/categories/bulk-action`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: action,
            ids: ids
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Success!',
                    text: data.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                alert(data.message);
                location.reload();
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Error!',
                    text: data.message,
                    icon: 'error'
                });
            } else {
                alert('Error: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error!',
                text: 'Failed to perform bulk action',
                icon: 'error'
            });
        } else {
            alert('Failed to perform bulk action');
        }
    });
}
</script>
@endpush
