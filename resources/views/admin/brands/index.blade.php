@extends('admin.layouts.app')

@section('title', 'Brands Management')

@php
use Illuminate\Support\Str;
@endphp

@push('styles')
<style>
    .brand-badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    .brand-logo {
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
    .brand-stats-card {
        transition: all 0.3s ease;
    }
    .brand-stats-card:hover {
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
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Brands Management</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Brands</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bx bx-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card custom-card brand-stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-fill">
                                <p class="mb-1 text-muted">Total Brands</p>
                                <h4 class="mb-0 text-primary">{{ $brands->total() }}</h4>
                            </div>
                            <div class="ms-3">
                                <span class="avatar avatar-md bg-primary-transparent">
                                    <i class="bx bx-crown fs-18"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card custom-card brand-stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-fill">
                                <p class="mb-1 text-muted">Active Brands</p>
                                <h4 class="mb-0 text-success">{{ $brands->where('status', 'Active')->count() }}</h4>
                            </div>
                            <div class="ms-3">
                                <span class="avatar avatar-md bg-success-transparent">
                                    <i class="bx bx-check-circle fs-18"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card custom-card brand-stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-fill">
                                <p class="mb-1 text-muted">Featured Brands</p>
                                <h4 class="mb-0 text-warning">{{ $brands->where('is_featured', true)->count() }}</h4>
                            </div>
                            <div class="ms-3">
                                <span class="avatar avatar-md bg-warning-transparent">
                                    <i class="bx bx-star fs-18"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card custom-card brand-stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-fill">
                                <p class="mb-1 text-muted">Total Products</p>
                                <h4 class="mb-0 text-info">0</h4>
                            </div>
                            <div class="ms-3">
                                <span class="avatar avatar-md bg-info-transparent">
                                    <i class="bx bx-package fs-18"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">Brands List</div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.brands.create') }}" class="btn btn-primary btn-sm">
                                <i class="bx bx-plus me-1"></i>Add Brand
                            </a>
                            <button class="btn btn-secondary btn-sm" onclick="window.location.reload()">
                                <i class="bx bx-refresh me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                    
                    <!-- Filters -->
                    <div class="card-body">
                        <div class="filter-section">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Search</label>
                                    <input type="text" class="form-control" id="searchInput" placeholder="Search brands...">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" id="statusFilter">
                                        <option value="">All Status</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Featured</label>
                                    <select class="form-select" id="featuredFilter">
                                        <option value="">All</option>
                                        <option value="yes">Featured</option>
                                        <option value="no">Not Featured</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Sort By</label>
                                    <select class="form-select" id="sortBy">
                                        <option value="name">Name</option>
                                        <option value="created_at">Date Created</option>
                                        <option value="products_count">Product Count</option>
                                        <option value="sort_order">Sort Order</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button class="btn btn-outline-primary" id="applyFilters">
                                            <i class="bx bx-filter-alt me-1"></i>Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bulk Actions -->
                        <div class="bulk-actions" id="bulkActions">
                            <div class="d-flex align-items-center gap-3">
                                <span class="fw-semibold">Bulk Actions:</span>
                                <button class="btn btn-sm btn-success" onclick="bulkAction('activate')">
                                    <i class="bx bx-check"></i> Activate
                                </button>
                                <button class="btn btn-sm btn-warning" onclick="bulkAction('deactivate')">
                                    <i class="bx bx-x"></i> Deactivate
                                </button>
                                <button class="btn btn-sm btn-info" onclick="bulkAction('feature')">
                                    <i class="bx bx-star"></i> Feature
                                </button>
                                <button class="btn btn-sm btn-secondary" onclick="bulkAction('unfeature')">
                                    <i class="bx bx-star-outline"></i> Unfeature
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="bulkAction('delete')">
                                    <i class="bx bx-trash"></i> Delete
                                </button>
                                <span class="text-muted ms-auto">
                                    <span id="selectedCount">0</span> items selected
                                </span>
                            </div>
                        </div>

                        <!-- Brands Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap w-100" id="brandsTable">
                                <thead>
                                    <tr>
                                        <th width="30">
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th width="60">Logo</th>
                                        <th>Brand Name</th>
                                        <th>Description</th>
                                        <th>Website</th>
                                        <th>Products</th>
                                        <th>Status</th>
                                        <th>Featured</th>
                                        <th>Created</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($brands as $brand) 
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input brand-checkbox" value="{{ $brand->id }}">
                                        </td>
                                        <td>
                                            <span class="avatar avatar-md">
                                                @if($brand->logo)
                                                    @php
                                                        $logoUrl = null;
                                                        $logoPath = null;
                                                        
                                                        // Handle logo_data whether it's array or JSON string
                                                        $logoData = $brand->logo_data;
                                                        if (is_string($logoData)) {
                                                            $logoData = json_decode($logoData, true);
                                                        }
                                                        
                                                        // Try to get from logo_data first (new system)
                                                        if ($logoData && is_array($logoData) && isset($logoData['sizes']['medium']['url'])) {
                                                            $logoUrl = $logoData['sizes']['medium']['url'];
                                                            $logoPath = $logoData['sizes']['medium']['path'] ?? $brand->logo;
                                                        } elseif ($logoData && is_array($logoData) && isset($logoData['sizes']['medium']['path'])) {
                                                            // If we have path but no URL, construct it
                                                            $logoPath = $logoData['sizes']['medium']['path'];
                                                            $logoUrl = asset('storage/' . $logoPath);
                                                        } else {
                                                            // Fallback to simple logo field (old system)
                                                            $logoPath = $brand->logo;
                                                            $logoUrl = asset('storage/' . $logoPath);
                                                        }
                                                        
                                                        $publicPath = public_path('storage/' . $logoPath);
                                                        $storageExists = file_exists(storage_path('app/public/' . $logoPath));
                                                        $publicExists = file_exists($publicPath);
                                                    @endphp
                                                    <img src="{{ $logoUrl }}" 
                                                         alt="{{ $brand->name }}" 
                                                         class="brand-logo"
                                                         data-debug-path="{{ $logoPath }}"
                                                         data-logo-data="{{ $brand->logo_data ? 'exists' : 'null' }}"
                                                         data-storage-exists="{{ $storageExists ? 'true' : 'false' }}"
                                                         data-public-exists="{{ $publicExists ? 'true' : 'false' }}"
                                                         data-full-url="{{ $logoUrl }}"
                                                         onerror="handleBrandImageError(this, '{{ $logoPath }}', '{{ $logoUrl }}', '{{ $brand->name }}');">
                                                @else
                                                    <div class="brand-placeholder d-flex align-items-center justify-content-center bg-light text-muted" 
                                                         style="width: 50px; height: 50px; border-radius: 4px; font-weight: bold; font-size: 14px;">
                                                        {{ strtoupper(substr($brand->name, 0, 2)) }}
                                                    </div>
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold">{{ $brand->name }}</span>
                                                <small class="text-muted">{{ $brand->slug }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted" style="max-width: 200px;">
                                                @if($brand->description)
                                                    {{ Str::limit($brand->description, 50) }}
                                                @else
                                                    <em class="text-muted">No description</em>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($brand->website_url)
                                                <a href="{{ $brand->website_url }}" target="_blank" class="text-primary">
                                                    <i class="bx bx-link-external"></i> Visit
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">0 Products</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $brand->status === 'Active' ? 'success' : 'danger' }} brand-badge status-toggle" 
                                                  data-id="{{ $brand->id }}" data-status="{{ $brand->status }}">
                                                {{ $brand->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $brand->is_featured ? 'warning' : 'secondary' }} brand-badge featured-toggle" 
                                                  data-id="{{ $brand->id }}" data-featured="{{ $brand->is_featured ? 'true' : 'false' }}">
                                                {{ $brand->is_featured ? 'Featured' : 'Regular' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $brand->created_at->format('M d, Y') }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group table-actions" role="group">
                                                <a href="{{ route('admin.brands.show', $brand->id) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="deleteBrand({{ $brand->id }})" title="Delete">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bx bx-crown fs-1 text-muted mb-3"></i>
                                                <h6 class="text-muted">No brands found</h6>
                                                <p class="text-muted mb-3">Start by adding your first brand</p>
                                                <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                                                    <i class="bx bx-plus me-1"></i>Add First Brand
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        Showing {{ $brands->firstItem() ?? 0 }} to {{ $brands->lastItem() ?? 0 }} of {{ $brands->total() }} results
                                    </small>
                                </div>
                                <div>
                                    {{ $brands->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Advanced brand image debugging function
function handleBrandImageError(img, logoPath, fullUrl, brandName) {
    console.group('🏷️ Brand Logo Load Error Debug');
    console.log('Logo Path:', logoPath);
    console.log('Full URL:', fullUrl);
    console.log('Brand Name:', brandName);
    console.log('Storage Exists:', img.dataset.storageExists);
    console.log('Public Exists:', img.dataset.publicExists);
    console.log('Current Domain:', window.location.origin);
    
    // Test different path variations
    const testPaths = [
        fullUrl,
        window.location.origin + '/storage/' + logoPath,
        window.location.origin + '/public/storage/' + logoPath,
        '{{ asset("") }}' + 'storage/' + logoPath,
        '/storage/' + logoPath
    ];
    
    console.log('Testing alternative paths:');
    testPaths.forEach((path, index) => {
        console.log(`Path ${index + 1}:`, path);
    });
    
    console.groupEnd();
    
    // Replace with placeholder
    const placeholder = document.createElement('div');
    placeholder.className = 'brand-placeholder d-flex align-items-center justify-content-center bg-light text-muted';
    placeholder.style.cssText = 'width: 50px; height: 50px; border-radius: 4px; font-weight: bold; font-size: 14px;';
    placeholder.textContent = brandName.substring(0, 2).toUpperCase();
    
    img.parentNode.replaceChild(placeholder, img);
}

$(document).ready(function() {
    // Select All functionality
    $('#selectAll').change(function() {
        $('.brand-checkbox').prop('checked', this.checked);
        updateBulkActions();
    });

    // Individual checkbox change
    $('.brand-checkbox').change(function() {
        updateBulkActions();
        
        // Update select all checkbox state
        const totalCheckboxes = $('.brand-checkbox').length;
        const checkedCheckboxes = $('.brand-checkbox:checked').length;
        
        $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
        $('#selectAll').prop('checked', checkedCheckboxes === totalCheckboxes);
    });

    // Update bulk actions visibility
    function updateBulkActions() {
        const checkedCount = $('.brand-checkbox:checked').length;
        $('#selectedCount').text(checkedCount);
        
        if (checkedCount > 0) {
            $('#bulkActions').show();
        } else {
            $('#bulkActions').hide();
        }
    }

    // Status toggle functionality
    $('.status-toggle').click(function() {
        const $this = $(this);
        const brandId = $this.data('id');
        const currentStatus = $this.data('status');
        
        toggleStatus(brandId, currentStatus, $this);
    });

    // Featured toggle functionality
    $('.featured-toggle').click(function() {
        const $this = $(this);
        const brandId = $this.data('id');
        const currentFeatured = $this.data('featured') === 'true';
        
        console.log('Featured toggle clicked:', {
            element: $this[0],
            brandId: brandId,
            currentFeatured: currentFeatured,
            dataAttributes: $this.data()
        });
        
        toggleFeatured(brandId, currentFeatured, $this);
    });
});

// Global functions for brand management
function toggleStatus(id, currentStatus, element) {
    console.log('toggleStatus called with:', { id, currentStatus });
    
    Swal.fire({
        title: 'Change Status',
        text: `Are you sure you want to toggle this brand's status?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, toggle it!'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Making AJAX request to toggle status for brand:', id);
            
            // Show loading state
            const originalText = element.text();
            element.html('<i class="bx bx-loader-alt bx-spin"></i>');
            
            // Make AJAX request to toggle status
            fetch(`/admin/brands/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Update the badge
                    const newStatus = data.status;
                    element.removeClass('bg-success bg-danger')
                           .addClass(newStatus === 'Active' ? 'bg-success' : 'bg-danger')
                           .text(newStatus)
                           .data('status', newStatus);
                    
                    Swal.fire('Success!', data.message, 'success');
                } else {
                    // Restore original state
                    element.text(originalText);
                    Swal.fire('Error!', data.message || 'Failed to update status.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Restore original state
                element.text(originalText);
                Swal.fire('Error!', 'An error occurred while updating status.', 'error');
            });
        }
    });
}

function toggleFeatured(id, currentFeatured, element) {
    console.log('toggleFeatured called with:', { id, currentFeatured });
    
    // Validate that ID is present
    if (!id) {
        console.error('No brand ID provided to toggleFeatured function');
        Swal.fire('Error!', 'Brand ID is missing. Please refresh the page and try again.', 'error');
        return;
    }
    
    Swal.fire({
        title: 'Change Featured Status',
        text: `Are you sure you want to toggle this brand's featured status?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, toggle it!'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Making AJAX request to toggle featured for brand:', id);
            
            // Show loading state
            const originalText = element.text();
            element.html('<i class="bx bx-loader-alt bx-spin"></i>');
            
            // Construct the URL and log it for debugging
            const url = `/admin/brands/${id}/toggle-featured`;
            console.log('Request URL:', url);
            
            // Make AJAX request to toggle featured status
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Update the badge
                    const isFeatured = data.featured;
                    element.removeClass('bg-warning bg-secondary')
                           .addClass(isFeatured ? 'bg-warning' : 'bg-secondary')
                           .text(isFeatured ? 'Featured' : 'Regular')
                           .data('featured', isFeatured ? 'true' : 'false');
                    
                    Swal.fire('Success!', data.message, 'success');
                } else {
                    // Restore original state
                    element.text(originalText);
                    Swal.fire('Error!', data.message || 'Failed to update featured status.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Restore original state
                element.text(originalText);
                Swal.fire('Error!', 'An error occurred while updating featured status.', 'error');
            });
        }
    });
}

function deleteBrand(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this! All products associated with this brand will also be affected.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Make AJAX request to delete brand
            fetch(`/admin/brands/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', 'Brand has been deleted.', 'success');
                    window.location.reload();
                } else {
                    Swal.fire('Error!', 'Failed to delete brand.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'An error occurred while deleting brand.', 'error');
            });
        }
    });
}

// Bulk actions
function bulkAction(action) {
    const checkedBoxes = document.querySelectorAll('.brand-checkbox:checked');
    const ids = Array.from(checkedBoxes).map(checkbox => checkbox.value);
    
    if (ids.length === 0) {
        Swal.fire('No Selection', 'Please select brands first.', 'warning');
        return;
    }
    
    let title, text, confirmText;
    
    switch(action) {
        case 'activate':
            title = 'Activate Brands';
            text = `Are you sure you want to activate ${ids.length} brands?`;
            confirmText = 'Yes, activate them!';
            break;
        case 'deactivate':
            title = 'Deactivate Brands';
            text = `Are you sure you want to deactivate ${ids.length} brands?`;
            confirmText = 'Yes, deactivate them!';
            break;
        case 'feature':
            title = 'Feature Brands';
            text = `Are you sure you want to feature ${ids.length} brands?`;
            confirmText = 'Yes, feature them!';
            break;
        case 'unfeature':
            title = 'Unfeature Brands';
            text = `Are you sure you want to unfeature ${ids.length} brands?`;
            confirmText = 'Yes, unfeature them!';
            break;
        case 'delete':
            title = 'Delete Brands';
            text = `Are you sure you want to delete ${ids.length} brands? This action cannot be undone!`;
            confirmText = 'Yes, delete them!';
            break;
    }
    
    Swal.fire({
        title: title,
        text: text,
        icon: action === 'delete' ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonColor: action === 'delete' ? '#d33' : '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: confirmText
    }).then((result) => {
        if (result.isConfirmed) {
            // Make AJAX request for bulk action
            fetch(`/admin/brands/bulk-action`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    action: action,
                    ids: ids 
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success!', data.message, 'success');
                    window.location.reload();
                } else {
                    Swal.fire('Error!', data.message || `Failed to perform bulk ${action}.`, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', `An error occurred during bulk ${action}.`, 'error');
            });
        }
    });
}
</script>
@endpush
