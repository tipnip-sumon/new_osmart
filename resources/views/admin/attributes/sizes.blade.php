@extends('admin.layouts.app')

@section('title', 'Size Attributes')

@section('content')
<div class="container-fluid">
    <!-- Demo Notice -->
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Demo Mode:</strong> This page displays sample data for demonstration purposes. You can now add, edit, delete, and toggle status of attributes - these changes will be saved to the database.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Size Attributes</h1>
        <div class="d-flex">
            <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Add New Size
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Sizes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_sizes'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ruler-combined fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_sizes'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Inactive</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['inactive_sizes'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="m-0 font-weight-bold text-primary">Size Management</h6>
                </div>
                <div class="col-auto">
                    <form method="GET" action="{{ route('admin.attributes.sizes') }}" class="d-flex">
                        <input type="text" name="search" class="form-control form-control-sm mr-2" 
                               placeholder="Search sizes..." value="{{ request('search') }}" style="width: 200px;">
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.attributes.sizes') }}" class="btn btn-outline-secondary btn-sm ml-1">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if($sizes->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-ruler-combined fa-3x text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No sizes found.</p>
                    <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>Create First Size
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Value</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>Sort Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sizes as $size)
                                <tr>
                                    <td>{{ $size['id'] }}</td>
                                    <td>
                                        <strong>{{ $size['name'] }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $size['value'] }}</span>
                                    </td>
                                    <td>{{ $size['code'] }}</td>
                                    <td>
                                        @if($size['is_active'])
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $size['sort_order'] }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Size actions">
                                            <button type="button" class="btn btn-outline-info btn-sm" 
                                                    data-action="view" data-size="{{ base64_encode(json_encode($size)) }}"
                                                    title="View Details" data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-warning btn-sm" 
                                                    data-action="edit" data-size="{{ base64_encode(json_encode($size)) }}"
                                                    title="Edit Size" data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-{{ $size['is_active'] ? 'secondary' : 'success' }} btn-sm" 
                                                    data-action="toggle" data-id="{{ $size['id'] }}" data-status="{{ $size['is_active'] ? 'false' : 'true' }}"
                                                    title="{{ $size['is_active'] ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $size['is_active'] ? 'toggle-off' : 'toggle-on' }}"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    data-action="delete" data-id="{{ $size['id'] }}"
                                                    title="Delete" data-bs-toggle="tooltip">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this size? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Add event listeners for action buttons
    document.querySelectorAll('[data-action]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent any default behavior
            e.stopPropagation(); // Stop event bubbling
            
            const action = this.getAttribute('data-action');
            console.log('Action clicked:', action); // Debug log
            
            switch(action) {
                case 'view':
                    const viewSizeData = JSON.parse(atob(this.getAttribute('data-size')));
                    viewSize(viewSizeData);
                    break;
                case 'edit':
                    const editSizeData = JSON.parse(atob(this.getAttribute('data-size')));
                    editSize(editSizeData);
                    break;
                case 'toggle':
                    const id = this.getAttribute('data-id');
                    const status = this.getAttribute('data-status') === 'true';
                    console.log('Toggle clicked - ID:', id, 'New Status:', status); // Debug log
                    toggleStatus(id, status);
                    break;
                case 'delete':
                    const deleteId = this.getAttribute('data-id');
                    confirmDelete(deleteId);
                    break;
            }
        });
    });
});

function confirmDelete(id) {
    // Since this is demo data, we'll handle the delete differently
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    
    // Remove the form action since we're handling it with JavaScript
    deleteForm.removeAttribute('action');
    
    // Show the modal
    const modal = new bootstrap.Modal(deleteModal);
    modal.show();
    
    // Handle the delete form submission
    deleteForm.onsubmit = function(e) {
        e.preventDefault();
        
        // Send AJAX request to delete from database
        fetch(`/admin/attributes/${id}/ajax-delete`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert('Size deleted successfully!');
                
                // Remove the row from the DOM
                deleteSizeFromDOM(id);
                
                // Hide the modal
                modal.hide();
            } else {
                alert('Failed to delete size: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to delete size. Please try again.');
        });
        
        return false;
    };
}

function deleteSizeFromDOM(id) {
    // Find the button that was clicked
    const deleteButton = document.querySelector(`[data-action="delete"][data-id="${id}"]`);
    if (!deleteButton) return;
    
    // Find the parent row
    const row = deleteButton.closest('tr');
    if (!row) return;
    
    // Check if the size was active or inactive before deletion
    const statusBadge = row.querySelector('.badge');
    const wasActive = statusBadge && statusBadge.textContent.trim() === 'Active';
    
    // Remove the row from the table
    row.remove();
    
    // Update statistics
    updateStatisticsAfterDelete(wasActive);
    
    // Check if table is now empty
    const tableBody = document.querySelector('tbody');
    if (tableBody && tableBody.children.length === 0) {
        // Show empty state
        const cardBody = document.querySelector('.card-body');
        if (cardBody) {
            cardBody.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-ruler-combined fa-3x text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No sizes found.</p>
                    <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>Create First Size
                    </a>
                </div>
            `;
        }
    }
}

function updateStatisticsAfterDelete(wasActive) {
    // Get current statistics from the DOM
    const totalCountElement = document.querySelector('.border-left-primary .h5');
    const activeCountElement = document.querySelector('.border-left-success .h5');
    const inactiveCountElement = document.querySelector('.border-left-warning .h5');
    
    if (totalCountElement) {
        let totalCount = parseInt(totalCountElement.textContent);
        totalCount--;
        totalCountElement.textContent = totalCount;
    }
    
    if (wasActive && activeCountElement) {
        let activeCount = parseInt(activeCountElement.textContent);
        activeCount--;
        activeCountElement.textContent = activeCount;
    } else if (!wasActive && inactiveCountElement) {
        let inactiveCount = parseInt(inactiveCountElement.textContent);
        inactiveCount--;
        inactiveCountElement.textContent = inactiveCount;
    }
}

function viewSize(size) {
    const modalContent = `
        <div class="modal fade" id="viewSizeModal" tabindex="-1" aria-labelledby="viewSizeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewSizeModalLabel">Size Details: ${size.name}</h5>
                        <button type="button" class="btn-close" onclick="closeModal('viewSizeModal')" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tr><th>ID:</th><td>${size.id}</td></tr>
                            <tr><th>Name:</th><td>${size.name}</td></tr>
                            <tr><th>Value:</th><td><span class="badge bg-info">${size.value}</span></td></tr>
                            <tr><th>Code:</th><td>${size.code}</td></tr>
                            <tr><th>Status:</th><td>
                                ${size.is_active ? 
                                    '<span class="badge bg-success">Active</span>' : 
                                    '<span class="badge bg-secondary">Inactive</span>'
                                }
                            </td></tr>
                            <tr><th>Sort Order:</th><td>${size.sort_order}</td></tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeModal('viewSizeModal')">Close</button>
                        <button type="button" class="btn btn-warning" onclick="closeAndEditSize(${JSON.stringify(size).replace(/"/g, '&quot;')})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('viewSizeModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add new modal to body
    document.body.insertAdjacentHTML('beforeend', modalContent);
    
    // Show the modal using Bootstrap 5
    const modal = new bootstrap.Modal(document.getElementById('viewSizeModal'), {
        backdrop: 'static',
        keyboard: true
    });
    modal.show();
}

function closeAndEditSize(size) {
    const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewSizeModal'));
    viewModal.hide();
    setTimeout(() => {
        editSize(size);
    }, 300); // Wait for the modal to close before opening edit modal
}

function editSize(size) {
    const modalContent = `
        <div class="modal fade" id="editSizeModal" tabindex="-1" aria-labelledby="editSizeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSizeModalLabel">Edit Size: ${size.name}</h5>
                        <button type="button" class="btn-close" onclick="closeModal('editSizeModal')" aria-label="Close"></button>
                    </div>
                    <form id="editSizeForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="sizeName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="sizeName" value="${size.name}" required>
                            </div>
                            <div class="mb-3">
                                <label for="sizeValue" class="form-label">Value</label>
                                <input type="text" class="form-control" id="sizeValue" value="${size.value}" required>
                            </div>
                            <div class="mb-3">
                                <label for="sizeCode" class="form-label">Code</label>
                                <input type="text" class="form-control" id="sizeCode" value="${size.code}" required>
                            </div>
                            <div class="mb-3">
                                <label for="sortOrder" class="form-label">Sort Order</label>
                                <input type="number" class="form-control" id="sortOrder" value="${size.sort_order}" required>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="isActive" ${size.is_active ? 'checked' : ''}>
                                    <label class="form-check-label" for="isActive">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('editSizeModal')">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modals
    ['viewSizeModal', 'editSizeModal'].forEach(id => {
        const modal = document.getElementById(id);
        if (modal) modal.remove();
    });
    
    // Add new modal to body
    document.body.insertAdjacentHTML('beforeend', modalContent);
    
    // Show the modal using Bootstrap 5
    const modal = new bootstrap.Modal(document.getElementById('editSizeModal'), {
        backdrop: 'static',
        keyboard: true
    });
    modal.show();
    
    // Handle form submission
    document.getElementById('editSizeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form values
        const newName = document.getElementById('sizeName').value;
        const newValue = document.getElementById('sizeValue').value;
        const newCode = document.getElementById('sizeCode').value;
        const newSortOrder = document.getElementById('sortOrder').value;
        const newIsActive = document.getElementById('isActive').checked;
        
        // Update the size object
        const updatedSize = {
            ...size,
            name: newName,
            value: newValue,
            code: newCode,
            sort_order: newSortOrder,
            is_active: newIsActive
        };
        
        // Send AJAX request to update in database
        fetch(`/admin/attributes/${size.id}/ajax-update`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                name: newName,
                value: newValue,
                code: newCode,
                sort_order: newSortOrder,
                is_active: newIsActive
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the DOM with the response data
                updateSizeInDOM(size.id, data.attribute);
                
                // Show success message
                alert('Size updated successfully!');
                
                // Close modal
                closeModal('editSizeModal');
            } else {
                alert('Failed to update size: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update size. Please try again.');
        });
    });
}

function updateSizeInDOM(id, updatedSize) {
    // Find the row for this size
    const editButton = document.querySelector(`[data-action="edit"][data-id="${id}"], [data-action="view"][data-id="${id}"], [data-action="toggle"][data-id="${id}"], [data-action="delete"][data-id="${id}"]`);
    if (!editButton) return;
    
    const row = editButton.closest('tr');
    if (!row) return;
    
    // Get the old status to check if we need to update statistics
    const oldStatusBadge = row.cells[4].querySelector('.badge');
    const oldIsActive = oldStatusBadge && oldStatusBadge.textContent.trim() === 'Active';
    
    // Update table cells
    row.cells[1].innerHTML = `<strong>${updatedSize.name}</strong>`; // Name
    row.cells[2].innerHTML = `<span class="badge bg-info">${updatedSize.value}</span>`; // Value
    row.cells[3].textContent = updatedSize.code; // Code
    row.cells[4].innerHTML = updatedSize.is_active ? 
        '<span class="badge bg-success">Active</span>' : 
        '<span class="badge bg-secondary">Inactive</span>'; // Status
    row.cells[5].textContent = updatedSize.sort_order; // Sort Order
    
    // Update all action buttons with new data
    const allButtons = row.querySelectorAll('[data-action]');
    allButtons.forEach(button => {
        const action = button.getAttribute('data-action');
        
        if (action === 'view' || action === 'edit') {
            // Update data-size attribute with new data
            button.setAttribute('data-size', btoa(JSON.stringify(updatedSize)));
        } else if (action === 'toggle') {
            // Update toggle button
            button.className = `btn btn-outline-${updatedSize.is_active ? 'secondary' : 'success'} btn-sm`;
            button.setAttribute('data-status', updatedSize.is_active ? 'false' : 'true');
            button.title = updatedSize.is_active ? 'Deactivate' : 'Activate';
            button.querySelector('i').className = `fas fa-${updatedSize.is_active ? 'toggle-off' : 'toggle-on'}`;
        }
    });
    
    // Update statistics if status changed
    if (oldIsActive !== updatedSize.is_active) {
        updateStatisticsAfterStatusChange(oldIsActive, updatedSize.is_active);
    }
}

function updateStatisticsAfterStatusChange(oldIsActive, newIsActive) {
    const activeCountElement = document.querySelector('.border-left-success .h5');
    const inactiveCountElement = document.querySelector('.border-left-warning .h5');
    
    if (activeCountElement && inactiveCountElement) {
        let activeCount = parseInt(activeCountElement.textContent);
        let inactiveCount = parseInt(inactiveCountElement.textContent);
        
        if (oldIsActive && !newIsActive) {
            // Changed from active to inactive
            activeCount--;
            inactiveCount++;
        } else if (!oldIsActive && newIsActive) {
            // Changed from inactive to active
            activeCount++;
            inactiveCount--;
        }
        
        activeCountElement.textContent = activeCount;
        inactiveCountElement.textContent = inactiveCount;
    }
}

function toggleStatus(id, newStatus) {
    if (confirm(`Are you sure you want to ${newStatus ? 'activate' : 'deactivate'} this size?`)) {
        // Send AJAX request to update the status in database
        fetch(`/admin/attributes/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Size ${newStatus ? 'activated' : 'deactivated'} successfully!`);
                // Update the DOM to reflect the change
                updateSizeStatusInDOM(id, newStatus);
            } else {
                alert('Failed to update status: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update status. Please try again.');
        });
    }
}

function updateSizeStatusInDOM(id, newStatus) {
    // Find the button that was clicked
    const toggleButton = document.querySelector(`[data-action="toggle"][data-id="${id}"]`);
    if (!toggleButton) return;
    
    // Find the parent row
    const row = toggleButton.closest('tr');
    if (!row) return;
    
    // Update status badge
    const statusCell = row.cells[4]; // Status is the 5th column (index 4)
    const statusBadge = statusCell.querySelector('.badge');
    if (statusBadge) {
        if (newStatus) {
            statusBadge.className = 'badge bg-success';
            statusBadge.textContent = 'Active';
        } else {
            statusBadge.className = 'badge bg-secondary';
            statusBadge.textContent = 'Inactive';
        }
    }
    
    // Update toggle button appearance and data
    if (newStatus) {
        // Size is now active
        toggleButton.className = 'btn btn-outline-secondary btn-sm';
        toggleButton.setAttribute('data-status', 'false'); // Next click will deactivate
        toggleButton.title = 'Deactivate';
        toggleButton.querySelector('i').className = 'fas fa-toggle-off';
    } else {
        // Size is now inactive
        toggleButton.className = 'btn btn-outline-success btn-sm';
        toggleButton.setAttribute('data-status', 'true'); // Next click will activate
        toggleButton.title = 'Activate';
        toggleButton.querySelector('i').className = 'fas fa-toggle-on';
    }
    
    // Update statistics if needed
    updateStatistics(newStatus);
}

function updateStatistics(newStatus) {
    // Get current statistics from the DOM
    const activeCountElement = document.querySelector('.border-left-success .h5');
    const inactiveCountElement = document.querySelector('.border-left-warning .h5');
    
    if (activeCountElement && inactiveCountElement) {
        let activeCount = parseInt(activeCountElement.textContent);
        let inactiveCount = parseInt(inactiveCountElement.textContent);
        
        if (newStatus) {
            // Size was activated
            activeCount++;
            inactiveCount--;
        } else {
            // Size was deactivated
            activeCount--;
            inactiveCount++;
        }
        
        // Update the DOM
        activeCountElement.textContent = activeCount;
        inactiveCountElement.textContent = inactiveCount;
    }
}

function closeModal(modalId) {
    const modalElement = document.getElementById(modalId);
    if (modalElement) {
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();
        }
        setTimeout(() => {
            modalElement.remove();
        }, 300);
    }
}
</script>
@endpush
@endsection
