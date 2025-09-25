@extends('admin.layouts.app')

@section('title', 'Subcategories Management')

@push('styles')
<style>
    .subcategory-badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    .subcategory-image {
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
    .subcategory-stats-card {
        transition: all 0.3s ease;
    }
    .subcategory-stats-card:hover {
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
    .hierarchy-indicator {
        color: #28a745;
        margin-right: 5px;
    }
    .category-info {
        font-size: 0.85rem;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Subcategories Management</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Subcategories</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card custom-card subcategory-stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-fill">
                                <p class="mb-1 text-muted">Total Subcategories</p>
                                <h4 class="mb-0 text-primary">{{ isset($stats['total']) ? $stats['total'] : 0 }}</h4>
                            </div>
                            <div class="ms-3">
                                <span class="avatar avatar-md bg-primary-transparent">
                                    <i class="ri-list-check-3 fs-18"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card custom-card subcategory-stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-fill">
                                <p class="mb-1 text-muted">Active Subcategories</p>
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
                <div class="card custom-card subcategory-stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-fill">
                                <p class="mb-1 text-muted">Featured Subcategories</p>
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
                <div class="card custom-card subcategory-stats-card">
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
            <form method="GET" action="{{ route('admin.subcategories.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Search Subcategories</label>
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
                            <option value="">All Subcategories</option>
                            <option value="yes" {{ request('featured') == 'yes' ? 'selected' : '' }}>Featured</option>
                            <option value="no" {{ request('featured') == 'no' ? 'selected' : '' }}>Not Featured</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Category</label>
                        <select class="form-select" name="category">
                            <option value="">All Categories</option>
                            @if(isset($categories))
                                @foreach($categories as $category)
                                    <option value="{{ $category['id'] }}" {{ request('category') == $category['id'] ? 'selected' : '' }}>
                                        {{ $category['name'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label fw-semibold">Per Page</label>
                        <select class="form-select" name="per_page">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-search-line me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.subcategories.index') }}" class="btn btn-secondary">
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
                    <span id="selectedCount">0</span> subcategories selected
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
                                Showing {{ isset($pagination['from']) ? $pagination['from'] : 1 }}-{{ isset($pagination['to']) ? $pagination['to'] : count($subcategories ?? []) }} 
                                of {{ isset($pagination['total']) ? $pagination['total'] : count($subcategories ?? []) }} subcategories
                            </h6>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-secondary btn-sm" onclick="window.location.reload()">
                                <i class="ri-refresh-line me-1"></i> Refresh
                            </button>
                            <a href="{{ route('admin.subcategories.create') }}" class="btn btn-primary btn-sm">
                                <i class="ri-add-line me-1"></i> Add Subcategory
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subcategories List -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">Subcategories List</div>
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
                                        <th>Subcategory</th>
                                        <th>Parent Category</th>
                                        <th>Products</th>
                                        <th>Status</th>
                                        <th>Featured</th>
                                        <th>Sort Order</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($subcategories ?? [] as $subcategory)
                                    <tr>
                                        <td>
                                            <input class="form-check-input subcategory-checkbox" type="checkbox" value="{{ $subcategory['id'] }}">
                                        </td>
                                        <td>
                                            <span class="avatar avatar-md">
                                                @if(isset($subcategory['image']) && $subcategory['image'])
                                                    @php
                                                        // Check if image path is already a full URL
                                                        if (filter_var($subcategory['image'], FILTER_VALIDATE_URL)) {
                                                            // It's already a full URL - use it directly
                                                            $imagePath = $subcategory['image'];
                                                            // But also fix the domain to current domain
                                                            $imagePath = str_replace(['http://localhost', 'https://localhost'], request()->getSchemeAndHttpHost(), $imagePath);
                                                            
                                                            // Extract relative path for file existence checks
                                                            $relativePath = str_replace([
                                                                'http://localhost/storage/',
                                                                'http://127.0.0.1:8000/storage/',
                                                                'https://localhost/storage/',
                                                                url('/storage/') . '/',
                                                                request()->getSchemeAndHttpHost() . '/storage/'
                                                            ], '', $subcategory['image']);
                                                        } else {
                                                            // It's a relative path
                                                            $imagePath = asset('storage/' . $subcategory['image']);
                                                            $relativePath = $subcategory['image'];
                                                        }
                                                        
                                                        $publicPath = public_path('storage/' . $relativePath);
                                                        $storageExists = file_exists(storage_path('app/public/' . $relativePath));
                                                        $publicExists = file_exists($publicPath);
                                                    @endphp
                                                    <img src="{{ $imagePath }}" 
                                                         alt="{{ $subcategory['name'] ?? 'Subcategory' }}" 
                                                         class="subcategory-image"
                                                         data-debug-path="{{ $subcategory['image'] }}"
                                                         data-relative-path="{{ $relativePath ?? 'N/A' }}"
                                                         data-storage-exists="{{ $storageExists ? 'true' : 'false' }}"
                                                         data-public-exists="{{ $publicExists ? 'true' : 'false' }}"
                                                         data-full-url="{{ $imagePath }}"
                                                         onerror="handleImageError(this, '{{ $subcategory['image'] }}', '{{ $imagePath }}');">
                                                @else
                                                    <img src="https://via.placeholder.com/50x50/e8f5e8/28a745?text=SUB" 
                                                         alt="No Image" 
                                                         class="subcategory-image">
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <a href="{{ route('admin.subcategories.show', $subcategory['id']) }}" class="fw-semibold text-primary">
                                                        {{ $subcategory['name'] ?? 'Unnamed Subcategory' }}
                                                    </a>
                                                    <div class="text-muted fs-12">{{ $subcategory['slug'] ?? 'no-slug' }}</div>
                                                    @if(isset($subcategory['description']) && $subcategory['description'])
                                                        <div class="text-muted fs-11">{{ Str::limit($subcategory['description'], 50) }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if(isset($subcategory['category']))
                                                <span class="parent-category">{{ $subcategory['category']['name'] ?? 'Unknown' }}</span>
                                                <div class="category-info">
                                                    <i class="ri-folder-line"></i> {{ $subcategory['category']['slug'] ?? '' }}
                                                </div>
                                            @else
                                                <span class="text-muted">No Parent</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $subcategory['products_count'] ?? 0 }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $status = $subcategory['status'] ?? 'inactive';
                                            @endphp
                                            <span class="badge bg-{{ $status == 'active' || $status == 'Active' ? 'success' : 'danger' }} status-toggle" 
                                                  data-id="{{ $subcategory['id'] }}" data-status="{{ $status }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $isFeatured = $subcategory['is_featured'] ?? false;
                                            @endphp
                                            <span class="badge bg-{{ $isFeatured ? 'warning' : 'secondary' }} featured-toggle" 
                                                  data-id="{{ $subcategory['id'] }}" data-featured="{{ $isFeatured ? 'true' : 'false' }}">
                                                {{ $isFeatured ? 'Featured' : 'Not Featured' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $subcategory['sort_order'] ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">
                                                @if(isset($subcategory['created_at']))
                                                    {{ date('M d, Y', strtotime($subcategory['created_at'])) }}
                                                @else
                                                    N/A
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 table-actions">
                                                <a href="{{ route('admin.subcategories.show', $subcategory['id']) }}" 
                                                   class="btn btn-primary-light btn-sm" title="View">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('admin.subcategories.edit', $subcategory['id']) }}" 
                                                   class="btn btn-warning-light btn-sm" title="Edit">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger-light btn-sm" 
                                                        onclick="deleteSubcategory({{ $subcategory['id'] }})" title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="{{ route('admin.subcategories.show', $subcategory['id']) }}">
                                                            <i class="ri-eye-line me-2"></i>View Details</a></li>
                                                        <li><a class="dropdown-item" href="{{ route('admin.subcategories.edit', $subcategory['id']) }}">
                                                            <i class="ri-edit-line me-2"></i>Edit Subcategory</a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-warning" href="#" onclick="toggleFeatured({{ $subcategory['id'] }}, {{ ($subcategory['is_featured'] ?? false) ? 'false' : 'true' }})">
                                                            <i class="ri-star-line me-2"></i>{{ ($subcategory['is_featured'] ?? false) ? 'Remove from Featured' : 'Mark as Featured' }}</a></li>
                                                        <li><a class="dropdown-item text-info" href="#" onclick="toggleStatus({{ $subcategory['id'] }}, '{{ ($status == 'active' || $status == 'Active') ? 'inactive' : 'active' }}')">
                                                            <i class="ri-toggle-line me-2"></i>{{ ($status == 'active' || $status == 'Active') ? 'Deactivate' : 'Activate' }}</a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteSubcategory({{ $subcategory['id'] }})">
                                                            <i class="ri-delete-bin-line me-2"></i>Delete Subcategory</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ri-folder-3-line fs-48 text-muted mb-2"></i>
                                                <h6 class="fw-semibold mb-1">No Subcategories Found</h6>
                                                <p class="text-muted mb-3">No subcategories match your current filters.</p>
                                                <a href="{{ route('admin.subcategories.create') }}" class="btn btn-primary btn-sm">
                                                    <i class="ri-add-line me-1"></i> Create First Subcategory
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
                        <nav aria-label="Subcategories pagination">
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
    console.group('🖼️ Subcategory Image Load Error Debug');
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
    img.src = 'https://via.placeholder.com/50x50/e8f5e8/28a745?text=SUB';
    img.onerror = null;
    
    // Add visual indicator for debugging
    img.style.border = '2px solid red';
    img.title = 'Subcategory image failed to load: ' + imagePath;
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
        console.group('📋 Subcategory Image Debug Summary');
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

// Subcategory management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Select all functionality
    const selectAll = document.getElementById('selectAll');
    const subcategoryCheckboxes = document.querySelectorAll('.subcategory-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            subcategoryCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }

    subcategoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.subcategory-checkbox:checked');
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
            selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < subcategoryCheckboxes.length;
            selectAll.checked = checkedBoxes.length === subcategoryCheckboxes.length;
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
            const newFeatured = !currentFeatured;
            toggleFeatured(id, newFeatured);
        });
    });
});

// Global functions for subcategory management
function toggleStatus(id, status) {
    console.log('toggleStatus called with:', { id, status });
    
    Swal.fire({
        title: 'Change Status',
        text: `Are you sure you want to toggle this subcategory's status?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, toggle it!'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Making AJAX request to toggle status for subcategory:', id);
            
            // Make AJAX request to toggle status
            fetch(`/admin/subcategories/${id}/toggle-status`, {
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
                    Swal.fire('Success!', data.message, 'success');
                    window.location.reload();
                } else {
                    Swal.fire('Error!', data.error || data.message || 'Failed to update status.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'An error occurred while updating status.', 'error');
            });
        }
    });
}

function toggleFeatured(id, featured) {
    const action = featured ? 'feature' : 'unfeature';
    Swal.fire({
        title: 'Change Featured Status',
        text: `Are you sure you want to toggle this subcategory's featured status?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, toggle it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Make AJAX request to toggle featured status
            fetch(`/admin/subcategories/${id}/toggle-featured`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success!', data.message, 'success');
                    window.location.reload();
                } else {
                    Swal.fire('Error!', data.error || 'Failed to update featured status.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'An error occurred while updating featured status.', 'error');
            });
        }
    });
}

function deleteSubcategory(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this! All products in this subcategory will also be affected.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Make AJAX request to delete subcategory
            fetch(`/admin/subcategories/${id}`, {
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
                    Swal.fire('Deleted!', 'Subcategory has been deleted.', 'success');
                    window.location.reload();
                } else {
                    Swal.fire('Error!', 'Failed to delete subcategory.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'An error occurred while deleting subcategory.', 'error');
            });
        }
    });
}

function bulkAction(action) {
    const checkedBoxes = document.querySelectorAll('.subcategory-checkbox:checked');
    const ids = Array.from(checkedBoxes).map(checkbox => checkbox.value);
    
    if (ids.length === 0) {
        Swal.fire('No Selection', 'Please select subcategories first.', 'warning');
        return;
    }
    
    let title, text, confirmText;
    
    switch(action) {
        case 'activate':
            title = 'Activate Subcategories';
            text = `Are you sure you want to activate ${ids.length} subcategories?`;
            confirmText = 'Yes, activate them!';
            break;
        case 'deactivate':
            title = 'Deactivate Subcategories';
            text = `Are you sure you want to deactivate ${ids.length} subcategories?`;
            confirmText = 'Yes, deactivate them!';
            break;
        case 'feature':
            title = 'Feature Subcategories';
            text = `Are you sure you want to feature ${ids.length} subcategories?`;
            confirmText = 'Yes, feature them!';
            break;
        case 'delete':
            title = 'Delete Subcategories';
            text = `Are you sure you want to delete ${ids.length} subcategories? This action cannot be undone!`;
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
            fetch(`/admin/subcategories/bulk-action`, {
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
                    Swal.fire('Success!', `Bulk ${action} completed successfully.`, 'success');
                    window.location.reload();
                } else {
                    Swal.fire('Error!', `Failed to perform bulk ${action}.`, 'error');
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
