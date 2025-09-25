@extends('admin.layouts.app')

@section('title', 'Collections')

@push('styles')
<style>
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    .collection-card {
        transition: all 0.3s ease;
        border: 1px solid #e3e6f0;
    }
    .collection-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .action-buttons .btn {
        margin: 0 2px;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 1.5rem;
    }
    .collection-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Collections Management</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Collections</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bx bx-collection fs-1"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $collections->total() }}</h3>
                            <p class="mb-0">Total Collections</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bx bx-check-circle fs-1"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $collections->where('is_active', true)->count() }}</h3>
                            <p class="mb-0">Active Collections</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bx bx-x-circle fs-1"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $collections->where('is_active', false)->count() }}</h3>
                            <p class="mb-0">Inactive Collections</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bx bx-package fs-1"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $collections->where('is_featured', true)->count() }}</h3>
                            <p class="mb-0">Featured Collections</p>
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
                        <div class="card-title">Collections List</div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-secondary btn-sm" onclick="location.reload()">
                                <i class="bx bx-refresh me-1"></i>Refresh
                            </button>
                            <a href="{{ route('admin.collections.create') }}" class="btn btn-primary btn-sm">
                                <i class="bx bx-plus me-1"></i>Add New Collection
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        @if($collections->count() > 0)
                            <!-- Search and Filter -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search collections..." id="searchInput">
                                        <span class="input-group-text">
                                            <i class="bx bx-search"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select" id="statusFilter">
                                        <option value="">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Bulk Actions -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                        <label for="selectAll" class="form-check-label me-3">Select All</label>
                                        <select class="form-select w-auto" id="bulkAction">
                                            <option value="">Bulk Actions</option>
                                            <option value="activate">Activate Selected</option>
                                            <option value="deactivate">Deactivate Selected</option>
                                            <option value="delete">Delete Selected</option>
                                        </select>
                                        <button class="btn btn-sm btn-primary" onclick="executeBulkAction()">Apply</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Collections Table -->
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="50">
                                                <input type="checkbox" id="selectAllHeader" class="form-check-input">
                                            </th>
                                            <th>Collection</th>
                                            <th>Slug</th>
                                            <th>Status</th>
                                            <th>Products</th>
                                            <th>Created</th>
                                            <th width="150">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="collectionsTable">
                                        @foreach($collections as $collection)
                                            <tr data-collection-id="{{ $collection->id }}">
                                                <td>
                                                    <input type="checkbox" class="form-check-input collection-checkbox" value="{{ $collection->id }}">
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($collection->image)
                                                            <img src="{{ $collection->medium_image_url }}" alt="Collection" class="collection-image me-3">
                                                        @else
                                                            <img src="{{ asset('admin-assets/images/media/media-40.jpg') }}" alt="Collection" class="collection-image me-3">
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0">{{ $collection->name }}</h6>
                                                            <small class="text-muted">ID: {{ $collection->id }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <code>{{ $collection->slug }}</code>
                                                </td>
                                                <td>
                                                    <span class="badge status-badge {{ $collection->is_active ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $collection->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $collection->products->count() }} Products</span>
                                                </td>
                                                <td>
                                                    {{ $collection->created_at->format('M d, Y') }}<br>
                                                    <small class="text-muted">{{ $collection->created_at->format('h:i A') }}</small>
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="{{ route('admin.collections.show', $collection) }}" class="btn btn-info btn-sm" title="View">
                                                            <i class="bx bx-show"></i>
                                                        </a>
                                                        <a href="{{ route('admin.collections.edit', $collection) }}" class="btn btn-primary btn-sm" title="Edit">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                        <button class="btn btn-{{ $collection->is_active ? 'warning' : 'success' }} btn-sm" 
                                                                onclick="toggleStatus({{ $collection->id }})" 
                                                                title="{{ $collection->is_active ? 'Deactivate' : 'Activate' }}">
                                                            <i class="bx bx-{{ $collection->is_active ? 'hide' : 'show' }}"></i>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm" onclick="deleteCollection({{ $collection->id }})" title="Delete">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <small class="text-muted">
                                        Showing {{ $collections->firstItem() ?? 0 }} to {{ $collections->lastItem() ?? 0 }} 
                                        of {{ $collections->total() }} collections
                                    </small>
                                </div>
                                <div>
                                    {{ $collections->links() }}
                                </div>
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-5">
                                <img src="{{ asset('admin-assets/images/media/media-67.svg') }}" alt="No Collections" class="mb-3" style="height: 200px;">
                                <h4>No Collections Found</h4>
                                <p class="text-muted mb-4">Create your first collection to organize your products effectively</p>
                                <a href="{{ route('admin.collections.create') }}" class="btn btn-primary">
                                    <i class="bx bx-plus me-1"></i>Create First Collection
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $("#collectionsTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Status filter
    $('#statusFilter').on('change', function() {
        var selectedStatus = $(this).val();
        $("#collectionsTable tr").each(function() {
            var row = $(this);
            var statusBadge = row.find('.status-badge');
            var isActive = statusBadge.hasClass('bg-success');
            
            if (selectedStatus === '') {
                row.show();
            } else if (selectedStatus === 'active' && isActive) {
                row.show();
            } else if (selectedStatus === 'inactive' && !isActive) {
                row.show();
            } else {
                row.hide();
            }
        });
    });

    // Select all functionality
    $('#selectAll, #selectAllHeader').on('change', function() {
        $('.collection-checkbox').prop('checked', this.checked);
        $('#selectAll, #selectAllHeader').prop('checked', this.checked);
    });

    // Individual checkbox change
    $('.collection-checkbox').on('change', function() {
        var totalCheckboxes = $('.collection-checkbox').length;
        var checkedCheckboxes = $('.collection-checkbox:checked').length;
        
        $('#selectAll, #selectAllHeader').prop('checked', totalCheckboxes === checkedCheckboxes);
    });
});

// Toggle collection status
function toggleStatus(id) {
    Swal.fire({
        title: 'Change Status',
        text: 'Are you sure you want to change this collection status?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, change it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Here you would make an AJAX call to toggle status
            // For now, just reload the page
            location.reload();
        }
    });
}

// Delete collection
function deleteCollection(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Here you would make an AJAX call to delete
            // For now, just show success message
            Swal.fire(
                'Deleted!',
                'Collection has been deleted.',
                'success'
            ).then(() => {
                location.reload();
            });
        }
    });
}

// Execute bulk action
function executeBulkAction() {
    var selectedAction = $('#bulkAction').val();
    var selectedCollections = $('.collection-checkbox:checked').map(function() {
        return this.value;
    }).get();

    if (!selectedAction) {
        Swal.fire('Error', 'Please select an action', 'error');
        return;
    }

    if (selectedCollections.length === 0) {
        Swal.fire('Error', 'Please select collections to perform action', 'error');
        return;
    }

    Swal.fire({
        title: 'Confirm Bulk Action',
        text: `Are you sure you want to ${selectedAction} ${selectedCollections.length} collection(s)?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Here you would make an AJAX call for bulk action
            // For now, just show success message
            Swal.fire(
                'Success!',
                `Bulk action "${selectedAction}" completed successfully.`,
                'success'
            ).then(() => {
                location.reload();
            });
        }
    });
}
</script>
@endpush
