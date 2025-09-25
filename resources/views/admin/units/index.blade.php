@extends('admin.layouts.app')

@section('title', 'Measurement Units')

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<style>
.unit-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    border-radius: 12px;
}
.unit-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
.unit-type-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
}
.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
}
.stats-icon {
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.unit-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: white;
}
.weight-icon { background: linear-gradient(45deg, #ff6b6b, #ee5a24); }
.length-icon { background: linear-gradient(45deg, #74b9ff, #0984e3); }
.volume-icon { background: linear-gradient(45deg, #a29bfe, #6c5ce7); }
.area-icon { background: linear-gradient(45deg, #fd79a8, #e84393); }
.default-icon { background: linear-gradient(45deg, #636e72, #2d3436); }

/* Action buttons styling */
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
    min-width: 60px;
    text-align: center;
    white-space: nowrap;
}

.d-flex.gap-1 > .btn {
    margin-right: 0.25rem;
}

.d-flex.gap-1 > .btn:last-child {
    margin-right: 0;
}

/* Ensure icons and text are visible */
.btn i {
    margin-right: 0.25rem;
    display: inline-block;
}

.btn i:only-child {
    margin-right: 0;
}

/* Search form styling */
.input-group .btn {
    border-color: #ced4da;
}

.input-group .btn:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

/* Bulk action buttons */
.btn-outline-success {
    color: #198754;
    border-color: #198754;
}

.btn-outline-success:hover {
    background-color: #198754;
    border-color: #198754;
    color: white;
}

.btn-outline-warning {
    color: #fd7e14;
    border-color: #fd7e14;
}

.btn-outline-warning:hover {
    background-color: #fd7e14;
    border-color: #fd7e14;
    color: white;
}

/* Table action buttons */
.table td .btn {
    font-size: 0.75rem;
    padding: 0.125rem 0.375rem;
}

/* Fallback for missing icons */
.btn:not(:has(i))::before {
    content: attr(title);
    font-size: 0.75rem;
}
</style>
@endpush

@section('content')
<div class="d-sm-flex d-block align-items-center justify-content-between page-header-breadcrumb">
    <h4 class="fw-medium mb-0">Measurement Units</h4>
    <div class="ms-sm-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Units</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon me-3">
                        <i class="ti ti-ruler fs-20"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ $stats['total'] }}</h3>
                        <p class="mb-0 opacity-8">Total Units</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon me-3">
                        <i class="ti ti-scale fs-20"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ $stats['weight'] }}</h3>
                        <p class="mb-0 opacity-8">Weight Units</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon me-3">
                        <i class="ti ti-ruler-2 fs-20"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ $stats['length'] }}</h3>
                        <p class="mb-0 opacity-8">Length Units</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon me-3">
                        <i class="ti ti-check-circle fs-20"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ $stats['active'] }}</h3>
                        <p class="mb-0 opacity-8">Active Units</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Units Management -->
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title">Units Management</div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.units.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus me-1"></i>Add Unit
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="ti ti-filter me-1"></i>Filter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['type' => 'all']) }}">All Types</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['type' => 'weight']) }}">Weight</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['type' => 'length']) }}">Length</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['type' => 'volume']) }}">Volume</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['type' => 'area']) }}">Area</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}">Active</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'inactive']) }}">Inactive</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Search and Filters -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('admin.units.index') }}">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search units...">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="ti ti-search"></i> Search
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('admin.units.index') }}" class="btn btn-outline-secondary">
                                        <i class="ti ti-x"></i> Clear
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-outline-success btn-sm" onclick="bulkAction('activate')" title="Activate Selected">
                                <i class="ti ti-check me-1"></i>Activate
                            </button>
                            <button class="btn btn-outline-warning btn-sm" onclick="bulkAction('deactivate')" title="Deactivate Selected">
                                <i class="ti ti-x me-1"></i>Deactivate
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="bulkAction('delete')" title="Delete Selected">
                                <i class="ti ti-trash me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap w-100" id="unitsTable">
                        <thead>
                            <tr>
                                <th>
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </th>
                                <th>Unit</th>
                                <th>Symbol</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($units as $unit)
                            <tr>
                                <td>
                                    <input class="form-check-input row-checkbox" type="checkbox" value="{{ $unit->id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="unit-icon {{ $unit->type }}-icon me-3">
                                            @switch($unit->type)
                                                @case('weight')
                                                    <i class="ti ti-scale"></i>
                                                    @break
                                                @case('length')
                                                    <i class="ti ti-ruler"></i>
                                                    @break
                                                @case('volume')
                                                    <i class="ti ti-box"></i>
                                                    @break
                                                @case('area')
                                                    <i class="ti ti-square"></i>
                                                    @break
                                                @default
                                                    <i class="ti ti-ruler-2"></i>
                                            @endswitch
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $unit->name }}</h6>
                                            @if($unit->description)
                                                <small class="text-muted">{{ Str::limit($unit->description, 30) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $unit->symbol }}</span>
                                </td>
                                <td>
                                    <span class="badge unit-type-badge bg-{{ 
                                        $unit->type === 'weight' ? 'danger' : 
                                        ($unit->type === 'length' ? 'primary' : 
                                        ($unit->type === 'volume' ? 'info' : 
                                        ($unit->type === 'area' ? 'warning' : 'secondary'))) 
                                    }}-transparent">
                                        {{ ucfirst($unit->type) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle" type="checkbox" 
                                               data-id="{{ $unit->id }}" 
                                               {{ $unit->is_active ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $unit->created_at->format('M d, Y') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.units.show', $unit) }}" class="btn btn-sm btn-primary" title="View">
                                            <i class="ti ti-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-sm btn-success" title="Edit">
                                            <i class="ti ti-edit"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteUnit({{ $unit->id }})" title="Delete">
                                            <i class="ti ti-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="ti ti-ruler fs-48 text-muted mb-3"></i>
                                        <h6 class="text-muted">No units found</h6>
                                        <p class="text-muted mb-3">Get started by adding your first measurement unit</p>
                                        <a href="{{ route('admin.units.create') }}" class="btn btn-primary btn-sm">
                                            <i class="ti ti-plus me-1"></i>Add Unit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($units->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="text-muted mb-0">
                                Showing {{ $units->firstItem() }} to {{ $units->lastItem() }} of {{ $units->total() }} units
                            </p>
                        </div>
                        <div>
                            {{ $units->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Wait for all scripts to load before initializing DataTable
    setTimeout(function() {
        if ($.fn.DataTable) {
            // Initialize DataTable with pagination disabled since we're using Laravel pagination
            $('#unitsTable').DataTable({
                responsive: true,
                paging: false,
                searching: false,
                info: false,
                columnDefs: [
                    { orderable: false, targets: [0, 6] }
                ]
            });
        } else {
            console.warn('DataTables library not loaded, skipping initialization');
        }
    }, 100);

    // Select all checkbox
    $('#selectAll').change(function() {
        $('.row-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Update select all when individual checkboxes change
    $('.row-checkbox').change(function() {
        const totalCheckboxes = $('.row-checkbox').length;
        const checkedCheckboxes = $('.row-checkbox:checked').length;
        $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
    });

    // Status toggle
    $('.status-toggle').change(function() {
        const id = $(this).data('id');
        const isActive = $(this).prop('checked');
        const toggleElement = $(this);
        
        console.log('Toggle Status - ID:', id, 'New Status:', isActive);
        
        $.ajax({
            url: `{{ url('admin/units') }}/${id}/toggle-status`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                is_active: isActive ? 1 : 0
            },
            success: function(response) {
                console.log('Toggle Success Response:', response);
                if (response.success) {
                    showToast('Success', response.message, 'success');
                } else {
                    showToast('Error', response.message, 'error');
                    toggleElement.prop('checked', !isActive);
                }
            },
            error: function(xhr) {
                console.log('Toggle Status Error:', xhr.status, xhr.responseText);
                showToast('Error', 'Failed to update status', 'error');
                toggleElement.prop('checked', !isActive);
            }
        });
    });
});

function deleteUnit(id) {
    console.log('Delete Unit - ID:', id);
    
    if (confirm('Are you sure you want to delete this unit?')) {
        const token = $('meta[name="csrf-token"]').attr('content');
        const url = `{{ url('admin/units') }}/${id}`;
        
        console.log('Delete request - URL:', url, 'Token:', token);
        
        $.ajax({
            url: url,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            data: {
                _token: token,
                _method: 'DELETE'
            },
            success: function(response) {
                console.log('Delete Success Response:', response);
                if (response.success) {
                    showToast('Success', response.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('Error', response.message || 'Failed to delete unit', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.log('Delete Error - Status:', xhr.status, 'Response:', xhr.responseText, 'Error:', error);
                let errorMessage = 'Failed to delete unit';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 404) {
                    errorMessage = 'Unit not found';
                } else if (xhr.status === 403) {
                    errorMessage = 'Access denied';
                } else if (xhr.status === 422) {
                    errorMessage = 'Unit cannot be deleted (may be in use)';
                }
                
                showToast('Error', errorMessage, 'error');
            }
        });
    }
}

function bulkAction(action) {
    const selectedIds = $('.row-checkbox:checked').map(function() {
        return $(this).val();
    }).get();

    if (selectedIds.length === 0) {
        showToast('Warning', 'Please select at least one unit', 'warning');
        return;
    }

    let message = '';
    let confirmMessage = '';
    
    switch(action) {
        case 'activate':
            message = 'activate';
            confirmMessage = `Are you sure you want to activate ${selectedIds.length} unit(s)?`;
            break;
        case 'deactivate':
            message = 'deactivate';
            confirmMessage = `Are you sure you want to deactivate ${selectedIds.length} unit(s)?`;
            break;
        case 'delete':
            message = 'delete';
            confirmMessage = `Are you sure you want to delete ${selectedIds.length} unit(s)? This action cannot be undone.`;
            break;
    }

    if (confirm(confirmMessage)) {
        $.ajax({
            url: '{{ route("admin.units.bulk-action") }}',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                action: action,
                ids: selectedIds
            },
            success: function(response) {
                if (response.success) {
                    showToast('Success', response.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('Error', response.message || `Failed to ${message} units`, 'error');
                }
            },
            error: function() {
                showToast('Error', `Failed to ${message} units`, 'error');
            }
        });
    }
}

function showToast(title, message, type) {
    const bgClass = type === 'success' ? 'bg-success' : 
                   type === 'warning' ? 'bg-warning' : 'bg-danger';
    
    const toast = `
        <div class="toast align-items-center text-white ${bgClass} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}:</strong> ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    let toastContainer = $('.toast-container');
    if (toastContainer.length === 0) {
        $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3"></div>');
        toastContainer = $('.toast-container');
    }
    
    toastContainer.append(toast);
    const newToast = toastContainer.find('.toast').last();
    
    // Initialize and show the toast
    const bsToast = new bootstrap.Toast(newToast[0]);
    bsToast.show();
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        newToast.remove();
    }, 5000);
}
</script>
@endpush
