@extends('admin.layouts.app')

@section('content')
<div class="container-fluid my-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-window-restore text-primary me-2"></i>
                Popup Management
            </h1>
            <p class="text-muted mb-0">Create and manage beautiful popups for your users</p>
        </div>
        <a href="{{ route('admin.popups.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            Create New Popup
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Popups</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $popups->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-window-restore fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Popups</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $popups->where('is_active', true)->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Views</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $popups->sum('view_count') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Clicks</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $popups->sum('click_count') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-mouse-pointer fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popups Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Popups</h6>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="?filter=active">Active Only</a></li>
                    <li><a class="dropdown-item" href="?filter=inactive">Inactive Only</a></li>
                    <li><a class="dropdown-item" href="?">All Popups</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            @if($popups->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Views</th>
                                <th>Clicks</th>
                                <th>CTR</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($popups as $popup)
                            <tr data-popup-id="{{ $popup->id }}">
                                <td>{{ $popup->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($popup->image || $popup->image_data)
                                            @php
                                                $imageUrl = '';
                                                if ($popup->image_data) {
                                                    // Handle JSON format from new image upload system
                                                    $imageData = is_string($popup->image_data) ? json_decode($popup->image_data, true) : $popup->image_data;
                                                    if ($imageData && isset($imageData['sizes'])) {
                                                        // New format with sizes array
                                                        if (isset($imageData['sizes']['small'])) {
                                                            $imageUrl = $imageData['sizes']['small']['url'];
                                                        } elseif (isset($imageData['sizes']['medium'])) {
                                                            $imageUrl = $imageData['sizes']['medium']['url'];
                                                        } elseif (isset($imageData['sizes']['original'])) {
                                                            $imageUrl = $imageData['sizes']['original']['url'];
                                                        }
                                                    } elseif ($imageData && isset($imageData['small'])) {
                                                        // Old format (backward compatibility)
                                                        $imageUrl = asset('storage/' . $imageData['small']);
                                                    } elseif ($imageData && isset($imageData['original'])) {
                                                        $imageUrl = asset('storage/' . $imageData['original']);
                                                    }
                                                } elseif ($popup->image) {
                                                    // Handle direct file path (legacy)
                                                    $imageUrl = asset('storage/' . $popup->image);
                                                }
                                            @endphp
                                            @if($imageUrl)
                                                <img src="{{ $imageUrl }}" class="rounded me-2" width="40" height="40" style="object-fit: cover;">
                                            @endif
                                        @endif
                                        <div>
                                            <strong>{{ Str::limit($popup->title, 30) }}</strong>
                                            @if($popup->content)
                                                <br><small class="text-muted">{{ Str::limit(strip_tags($popup->content), 50) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $popup->type === 'promotion' ? 'success' : ($popup->type === 'warning' ? 'warning' : ($popup->type === 'announcement' ? 'info' : 'secondary')) }}">
                                        {{ ucfirst($popup->type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ ucfirst($popup->size) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $popup->priority }}</span>
                                </td>
                                <td>
                                                                    <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle" 
                                               type="checkbox" 
                                               data-id="{{ $popup->id }}"
                                               {{ $popup->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label text-{{ $popup->is_active ? 'success' : 'danger' }}" data-id="{{ $popup->id }}">
                                            {{ $popup->is_active ? 'Active' : 'Inactive' }}
                                        </label>
                                    </div>
                                </td>
                                </td>
                                <td>
                                    <i class="fas fa-eye text-info me-1"></i>
                                    {{ number_format($popup->view_count) }}
                                </td>
                                <td>
                                    <i class="fas fa-mouse-pointer text-warning me-1"></i>
                                    {{ number_format($popup->click_count) }}
                                </td>
                                <td>
                                    @php
                                        $ctr = $popup->view_count > 0 ? round(($popup->click_count / $popup->view_count) * 100, 2) : 0;
                                    @endphp
                                    <span class="text-{{ $ctr > 5 ? 'success' : ($ctr > 2 ? 'warning' : 'danger') }}">
                                        {{ $ctr }}%
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.popups.show', $popup) }}" class="btn btn-outline-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.popups.preview', $popup) }}" class="btn btn-outline-success" title="Preview" target="_blank">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.popups.edit', $popup) }}" class="btn btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-secondary duplicate-btn" 
                                                data-id="{{ $popup->id }}" 
                                                data-name="{{ $popup->name ?? $popup->title }}"
                                                title="Duplicate">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger delete-btn" 
                                                data-id="{{ $popup->id }}" 
                                                data-name="{{ $popup->name ?? $popup->title }}"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $popups->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-window-restore fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No Popups Found</h5>
                    <p class="text-muted">Create your first popup to engage with your users!</p>
                    <a href="{{ route('admin.popups.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Create Your First Popup
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// CSRF Token for AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Show toast notification
function showToast(message, type = 'success') {
    // Remove existing toasts
    document.querySelectorAll('.toast-container').forEach(container => container.remove());
    
    const toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
    toastContainer.style.zIndex = '9999';
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${type === 'success' ? 'check-circle' : (type === 'danger' ? 'exclamation-circle' : 'info-circle')} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    document.body.appendChild(toastContainer);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toastContainer.parentNode) {
            toastContainer.remove();
        }
    }, 5000);
}

// Show loading state
function showLoading(button) {
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    return originalText;
}

// Hide loading state
function hideLoading(button, originalText) {
    button.innerHTML = originalText;
    button.disabled = false;
}

// Update status display in table
function updateStatusDisplay(popupId, isActive) {
    const checkbox = document.querySelector(`.status-toggle[data-id="${popupId}"]`);
    const label = document.querySelector(`label[data-id="${popupId}"]`);
    
    if (checkbox && label) {
        checkbox.checked = isActive;
        label.className = `form-check-label text-${isActive ? 'success' : 'danger'}`;
        label.textContent = isActive ? 'Active' : 'Inactive';
    }
}

// Remove row from table
function removeTableRow(popupId) {
    const row = document.querySelector(`tr[data-popup-id="${popupId}"]`);
    if (row) {
        row.style.transition = 'opacity 0.3s ease';
        row.style.opacity = '0';
        setTimeout(() => {
            row.remove();
            
            // Check if table is empty
            const tbody = document.querySelector('tbody');
            if (tbody && tbody.children.length === 0) {
                location.reload(); // Reload to show empty state
            }
        }, 300);
    }
}

$(document).ready(function() {
    // Handle status toggle
    $('.status-toggle').change(function() {
        const checkbox = $(this);
        const popupId = checkbox.data('id');
        const isActive = checkbox.is(':checked');
        const label = checkbox.next('label');
        
        // Prepare form data similar to show page
        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('_method', 'PUT');
        formData.append('is_active', isActive ? '1' : '0');
        
        // We need to add other required fields for the update
        // For now, we'll use a simpler approach with just the status toggle endpoint
        
        fetch(`/admin/popups/${popupId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                is_active: isActive ? 1 : 0
            }),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateStatusDisplay(popupId, data.status);
                showToast(data.message || `Popup ${data.status ? 'activated' : 'deactivated'} successfully!`, 'success');
            } else {
                // Revert toggle
                checkbox.prop('checked', !isActive);
                updateStatusDisplay(popupId, !isActive);
                showToast(data.message || 'Failed to update popup status', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Revert toggle
            checkbox.prop('checked', !isActive);
            updateStatusDisplay(popupId, !isActive);
            showToast('An error occurred while updating status', 'danger');
        });
    });
    
    // Handle duplicate button click
    $('.duplicate-btn').click(function() {
        const button = this;
        const popupId = $(this).data('id');
        const popupName = $(this).data('name');
        
        if (confirm(`Create a copy of "${popupName}"? The duplicate will be inactive by default.`)) {
            const originalText = showLoading(button);
            
            fetch(`/admin/popups/${popupId}/duplicate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading(button, originalText);
                
                if (data.success) {
                    showToast(data.message || 'Popup duplicated successfully!', 'success');
                    
                    // Redirect to edit the new popup or refresh page
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1500);
                    } else {
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                } else {
                    showToast(data.message || 'Failed to duplicate popup', 'danger');
                }
            })
            .catch(error => {
                hideLoading(button, originalText);
                console.error('Error:', error);
                showToast('An error occurred while duplicating popup', 'danger');
            });
        }
    });
    
    // Handle delete button click
    $('.delete-btn').click(function() {
        const button = this;
        const popupId = $(this).data('id');
        const popupName = $(this).data('name');
        
        if (confirm(`Are you sure you want to delete "${popupName}"? This action cannot be undone.`)) {
            const originalText = showLoading(button);
            
            fetch(`/admin/popups/${popupId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    _method: 'DELETE'
                })
            })
            .then(response => response.json())
            .then(data => {
                hideLoading(button, originalText);
                
                if (data.success) {
                    showToast(data.message || 'Popup deleted successfully!', 'success');
                    
                    // Remove the row from table
                    removeTableRow(popupId);
                } else {
                    showToast(data.message || 'Failed to delete popup', 'danger');
                }
            })
            .catch(error => {
                hideLoading(button, originalText);
                console.error('Error:', error);
                showToast('An error occurred while deleting popup', 'danger');
            });
        }
    });
});
</script>
@endpush
@endsection
