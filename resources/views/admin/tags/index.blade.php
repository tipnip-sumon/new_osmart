@extends('admin.layouts.app')

@section('title', 'Tags Management')

@php
use Illuminate\Support\Str;
@endphp

@push('styles')
<style>
    .tag-badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    .status-toggle {
        cursor: pointer;
    }
    .tag-stats-card {
        transition: all 0.3s ease;
    }
    .tag-stats-card:hover {
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
            <h1 class="page-title fw-semibold fs-18 mb-0">Tags Management</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tags</li>
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
                <div class="card custom-card tag-stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-fill">
                                <p class="mb-1 text-muted">Total Tags</p>
                                <h4 class="mb-0 text-primary">{{ is_countable($tags) ? count($tags) : 0 }}</h4>
                            </div>
                            <div class="ms-3">
                                <span class="avatar avatar-md bg-primary-transparent">
                                    <i class="bx bx-tag fs-18"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card custom-card tag-stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-fill">
                                <p class="mb-1 text-muted">Active Tags</p>
                                <h4 class="mb-0 text-success">{{ is_countable($tags) ? collect($tags)->where('is_active', true)->count() : 0 }}</h4>
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
                <div class="card custom-card tag-stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-fill">
                                <p class="mb-1 text-muted">Popular Tags</p>
                                <h4 class="mb-0 text-warning">0</h4>
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
                <div class="card custom-card tag-stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-fill">
                                <p class="mb-1 text-muted">Total Uses</p>
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
                        <div class="card-title">Tags List</div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.tags.create') }}" class="btn btn-primary btn-sm">
                                <i class="bx bx-plus me-1"></i>Add Tag
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
                                    <input type="text" class="form-control" id="searchInput" placeholder="Search tags...">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" id="statusFilter">
                                        <option value="">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Sort By</label>
                                    <select class="form-select" id="sortBy">
                                        <option value="name">Name</option>
                                        <option value="created_at">Date Created</option>
                                        <option value="usage_count">Usage Count</option>
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
                                <button class="btn btn-sm btn-danger" onclick="bulkAction('delete')">
                                    <i class="bx bx-trash"></i> Delete
                                </button>
                                <span class="text-muted ms-auto">
                                    <span id="selectedCount">0</span> items selected
                                </span>
                            </div>
                        </div>

                        <!-- Tags Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap w-100" id="tagsTable">
                                <thead>
                                    <tr>
                                        <th width="30">
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th>Tag Name</th>
                                        <th>Slug</th>
                                        <th>Usage Count</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tags as $tag) 
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input tag-checkbox" value="{{ $tag['id'] }}">
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold">{{ $tag['name'] }}</span>
                                                <small class="text-muted">#{{ $tag['slug'] }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <code>{{ $tag['slug'] }}</code>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">0 uses</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $tag['is_active'] ? 'success' : 'danger' }} tag-badge status-toggle" 
                                                  data-id="{{ $tag['id'] }}" data-status="{{ $tag['is_active'] ? 'active' : 'inactive' }}">
                                                {{ $tag['is_active'] ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ \Carbon\Carbon::parse($tag['created_at'])->format('M d, Y') }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group table-actions" role="group">
                                                <a href="{{ route('admin.tags.show', $tag['id']) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="{{ route('admin.tags.edit', $tag['id']) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="deleteTag({{ $tag['id'] }})" title="Delete">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bx bx-tag fs-1 text-muted mb-3"></i>
                                                <h6 class="text-muted">No tags found</h6>
                                                <p class="text-muted mb-3">Start by adding your first tag</p>
                                                <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
                                                    <i class="bx bx-plus me-1"></i>Add First Tag
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
                                        Showing {{ is_countable($tags) ? count($tags) : 0 }} results
                                    </small>
                                </div>
                                <div>
                                    <!-- Pagination would go here when using actual database pagination -->
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
$(document).ready(function() {
    // Select All functionality
    $('#selectAll').change(function() {
        $('.tag-checkbox').prop('checked', this.checked);
        updateBulkActions();
    });

    // Individual checkbox change
    $('.tag-checkbox').change(function() {
        updateBulkActions();
        
        // Update select all checkbox state
        const totalCheckboxes = $('.tag-checkbox').length;
        const checkedCheckboxes = $('.tag-checkbox:checked').length;
        
        $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
        $('#selectAll').prop('checked', checkedCheckboxes === totalCheckboxes);
    });

    // Update bulk actions visibility
    function updateBulkActions() {
        const checkedCount = $('.tag-checkbox:checked').length;
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
        const tagId = $this.data('id');
        const currentStatus = $this.data('status');
        
        toggleStatus(tagId, currentStatus, $this);
    });
});

// Global functions for tag management
function toggleStatus(id, currentStatus, element) {
    console.log('toggleStatus called with:', { id, currentStatus });
    
    Swal.fire({
        title: 'Change Status',
        text: `Are you sure you want to toggle this tag's status?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, toggle it!'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Making AJAX request to toggle status for tag:', id);
            
            // Show loading state
            const originalText = element.text();
            element.html('<i class="bx bx-loader-alt bx-spin"></i>');
            
            // Make AJAX request to toggle status
            fetch(`/admin/tags/${id}/toggle-status`, {
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
                           .addClass(newStatus === 'active' ? 'bg-success' : 'bg-danger')
                           .text(newStatus === 'active' ? 'Active' : 'Inactive')
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

function deleteTag(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this! All products associated with this tag will be affected.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Make AJAX request to delete tag
            fetch(`/admin/tags/${id}`, {
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
                    Swal.fire('Deleted!', 'Tag has been deleted.', 'success');
                    window.location.reload();
                } else {
                    Swal.fire('Error!', 'Failed to delete tag.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'An error occurred while deleting tag.', 'error');
            });
        }
    });
}

// Bulk actions
function bulkAction(action) {
    const checkedBoxes = document.querySelectorAll('.tag-checkbox:checked');
    const ids = Array.from(checkedBoxes).map(checkbox => checkbox.value);
    
    if (ids.length === 0) {
        Swal.fire('No Selection', 'Please select tags first.', 'warning');
        return;
    }
    
    let title, text, confirmText;
    
    switch(action) {
        case 'activate':
            title = 'Activate Tags';
            text = `Are you sure you want to activate ${ids.length} tags?`;
            confirmText = 'Yes, activate them!';
            break;
        case 'deactivate':
            title = 'Deactivate Tags';
            text = `Are you sure you want to deactivate ${ids.length} tags?`;
            confirmText = 'Yes, deactivate them!';
            break;
        case 'delete':
            title = 'Delete Tags';
            text = `Are you sure you want to delete ${ids.length} tags? This action cannot be undone!`;
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
            fetch(`/admin/tags/bulk-action`, {
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
