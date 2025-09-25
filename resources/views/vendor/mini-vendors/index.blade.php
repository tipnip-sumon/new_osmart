@extends('admin.layouts.app')

@section('title', 'Mini Vendors')

@section('page-header')
<h3 class="page-title">Mini Vendors Management</h3>
<ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Mini Vendors</li>
</ul>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Success/Error Messages -->
    <div id="alertContainer"></div>
    
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Mini Vendors Management</h1>
            <p class="text-muted">Manage your assigned mini vendors and track their commission earnings</p>
        </div>
        <a href="{{ route('vendor.mini-vendors.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Assign New Mini Vendor
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Total Mini Vendors</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $miniVendors->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Active Mini Vendors</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $miniVendors->where('status', 'active')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                Total Commission Paid</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                ৳{{ number_format($miniVendors->sum('total_earned_commission'), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                This Month</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $miniVendors->where('created_at', '>=', now()->startOfMonth())->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mini Vendors Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">Mini Vendors List</h6>
        </div>
        <div class="card-body">
            @if($miniVendors->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mini Vendor</th>
                            <th>District</th>
                            <th>Status</th>
                            <th>Commission Rate</th>
                            <th>Total Earned</th>
                            <th>Assigned Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($miniVendors as $miniVendor)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img class="img-profile rounded-circle me-2" 
                                         src="{{ $miniVendor->affiliate->avatar ? asset('uploads/users/' . $miniVendor->affiliate->avatar) : asset('admin-assets/img/undraw_profile.svg') }}"
                                         style="width: 40px; height: 40px;">
                                    <div>
                                        <div class="fw-bold">{{ $miniVendor->affiliate->name }}</div>
                                        <div class="text-muted small">
                                            {{ $miniVendor->affiliate->email }}
                                            <span class="badge bg-info bg-opacity-10 text-info ms-1">{{ ucfirst($miniVendor->affiliate->role) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light">{{ $miniVendor->district ?: 'N/A' }}</span>
                            </td>
                            <td>
                                @if($miniVendor->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($miniVendor->status === 'inactive')
                                    <span class="badge bg-secondary">Inactive</span>
                                @else
                                    <span class="badge bg-danger">Suspended</span>
                                @endif
                            </td>
                            <td>{{ $miniVendor->commission_rate }}%</td>
                            <td>৳{{ number_format($miniVendor->total_earned_commission, 2) }}</td>
                            <td>{{ $miniVendor->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('vendor.mini-vendors.show', $miniVendor) }}" 
                                       class="btn btn-sm btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($miniVendor->status !== 'suspended')
                                    <button type="button" 
                                            class="btn btn-sm btn-warning status-toggle-btn"
                                            data-mini-vendor-id="{{ $miniVendor->id }}"
                                            data-current-status="{{ $miniVendor->status }}"
                                            data-new-status="{{ $miniVendor->status === 'active' ? 'inactive' : 'active' }}"
                                            title="{{ $miniVendor->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas fa-{{ $miniVendor->status === 'active' ? 'pause' : 'play' }}"></i>
                                        <span class="d-none d-sm-inline ms-1">{{ $miniVendor->status === 'active' ? 'Deactivate' : 'Activate' }}</span>
                                    </button>
                                    @endif
                                    <button type="button" 
                                            class="btn btn-sm btn-danger remove-mini-vendor-btn"
                                            data-mini-vendor-id="{{ $miniVendor->id }}"
                                            data-affiliate-name="{{ $miniVendor->affiliate->name }}"
                                            title="Remove Assignment">
                                        <i class="fas fa-trash"></i>
                                        <span class="d-none d-sm-inline ms-1">Remove</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $miniVendors->links() }}
            </div>
            @else
            <div class="text-center py-4">
                <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                <h5 class="text-gray-600">No Mini Vendors Assigned</h5>
                <p class="text-muted">You haven't assigned any mini vendors yet. Click the button above to get started.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Status Change Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Mini Vendor Status</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Are you sure you want to change the status of this mini vendor?</p>
                    <input type="hidden" name="status" id="newStatus">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Remove Modal -->
<div class="modal fade" id="removeModal" tabindex="-1" aria-labelledby="removeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeModalLabel">Remove Mini Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Warning!</strong> This action cannot be undone.
                </div>
                <p>Are you sure you want to remove <strong id="removeAffiliateName"></strong> as your mini vendor?</p>
                <p class="text-muted small">This will:</p>
                <ul class="text-muted small">
                    <li>Remove the mini vendor assignment</li>
                    <li>Stop automatic commission calculations for this user</li>
                    <li>Preserve existing commission history</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmRemoveBtn">
                    <i class="fas fa-trash"></i> Remove Assignment
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Setup CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // Handle status toggle with AJAX
    $('.status-toggle-btn').on('click', function() {
        const btn = $(this);
        const miniVendorId = btn.data('mini-vendor-id');
        const currentStatus = btn.data('current-status');
        const newStatus = btn.data('new-status');
        const row = btn.closest('tr');
        
        // Show loading state
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span class="d-none d-sm-inline ms-1">Updating...</span>');
        
        // Make AJAX request
        $.ajax({
            url: `/vendor/mini-vendors/${miniVendorId}/status`,
            method: 'PUT',
            data: {
                status: newStatus,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Show success message
                showAlert('success', `Mini vendor status updated to ${newStatus} successfully!`);
                
                // Update status badge
                const statusCell = row.find('td').eq(2); // Status column
                let badgeClass = newStatus === 'active' ? 'bg-success' : 'bg-secondary';
                let badgeText = newStatus === 'active' ? 'Active' : 'Inactive';
                statusCell.html(`<span class="badge ${badgeClass}">${badgeText}</span>`);
                
                // Update button
                updateStatusButton(btn, newStatus);
                
                // Update data attributes for next toggle
                btn.data('current-status', newStatus);
                btn.data('new-status', newStatus === 'active' ? 'inactive' : 'active');
            },
            error: function(xhr) {
                let errorMessage = 'Failed to update status. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join(', ');
                }
                
                // Show error message
                showAlert('danger', errorMessage);
                
                // Reset button to original state
                updateStatusButton(btn, currentStatus);
            },
            complete: function() {
                // Re-enable button
                btn.prop('disabled', false);
            }
        });
    });
    
    // Function to update button appearance
    function updateStatusButton(btn, currentStatus) {
        const isActive = currentStatus === 'active';
        const icon = isActive ? 'pause' : 'play';
        const text = isActive ? 'Deactivate' : 'Activate';
        const title = isActive ? 'Deactivate' : 'Activate';
        
        btn.html(`<i class="fas fa-${icon}"></i> <span class="d-none d-sm-inline ms-1">${text}</span>`)
           .attr('title', title);
    }
    
    // Function to show alert messages
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        $('#alertContainer').html(alertHtml);
        
        // Auto-hide success messages after 5 seconds
        if (type === 'success') {
            setTimeout(function() {
                $('#alertContainer .alert').fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        }
        
        // Scroll to top to show the message
        $('html, body').animate({scrollTop: 0}, 300);
    }
    
    // Handle remove mini vendor with AJAX
    $('.remove-mini-vendor-btn').on('click', function() {
        const btn = $(this);
        const miniVendorId = btn.data('mini-vendor-id');
        const affiliateName = btn.data('affiliate-name');
        
        // Update modal content
        $('#removeAffiliateName').text(affiliateName);
        
        // Store the mini vendor ID for the confirm button
        $('#confirmRemoveBtn').data('mini-vendor-id', miniVendorId);
        
        // Show modal
        const removeModal = new bootstrap.Modal(document.getElementById('removeModal'));
        removeModal.show();
    });
    
    // Handle confirm remove button
    $('#confirmRemoveBtn').on('click', function() {
        const btn = $(this);
        const miniVendorId = btn.data('mini-vendor-id');
        const row = $(`.remove-mini-vendor-btn[data-mini-vendor-id="${miniVendorId}"]`).closest('tr');
        
        // Show loading state
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Removing...');
        
        // Make AJAX request
        $.ajax({
            url: `/vendor/mini-vendors/${miniVendorId}`,
            method: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Hide modal
                const removeModal = bootstrap.Modal.getInstance(document.getElementById('removeModal'));
                removeModal.hide();
                
                // Show success message
                showAlert('success', 'Mini vendor assignment removed successfully!');
                
                // Remove the row with animation
                row.fadeOut(400, function() {
                    $(this).remove();
                    
                    // Check if table is empty
                    if ($('#dataTable tbody tr').length === 0) {
                        location.reload(); // Reload to show "no mini vendors" message
                    }
                });
            },
            error: function(xhr) {
                let errorMessage = 'Failed to remove mini vendor assignment. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join(', ');
                }
                
                // Show error message
                showAlert('danger', errorMessage);
                
                // Hide modal
                const removeModal = bootstrap.Modal.getInstance(document.getElementById('removeModal'));
                removeModal.hide();
            },
            complete: function() {
                // Reset button
                btn.prop('disabled', false).html('<i class="fas fa-trash"></i> Remove Assignment');
            }
        });
    });
});

// Legacy functions for backward compatibility
function changeStatus(miniVendorId, newStatus) {
    // This is now handled by AJAX above, keeping for compatibility
    console.log('Legacy changeStatus called, using AJAX implementation instead');
}

function removeMiniVendor(miniVendorId) {
    // This is now handled by AJAX above, keeping for compatibility
    console.log('Legacy removeMiniVendor called, using AJAX implementation instead');
}
</script>
@endpush