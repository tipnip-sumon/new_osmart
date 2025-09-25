@extends('admin.layouts.app')

@section('title', 'Color Attributes')

@section('content')
<div class="container-fluid">
    <!-- Demo Notice -->
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Demo Mode:</strong> This page displays sample data for demonstration purposes. Actions like edit, delete, and status changes will show feedback but won't persist changes to the database.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Color Attributes</h1>
        <div class="d-flex">
            <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Add New Color
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Colors</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_colors'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-palette fa-2x text-gray-300"></i>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_colors'] }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['inactive_colors'] }}</div>
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
                    <h6 class="m-0 font-weight-bold text-primary">Color Management</h6>
                </div>
                <div class="col-auto">
                    <form method="GET" action="{{ route('admin.attributes.colors') }}" class="d-flex">
                        <input type="text" name="search" class="form-control form-control-sm mr-2" 
                               placeholder="Search colors..." value="{{ request('search') }}" style="width: 200px;">
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.attributes.colors') }}" class="btn btn-outline-secondary btn-sm ml-1">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if($colors->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-palette fa-3x text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No colors found.</p>
                    <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>Create First Color
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Color Preview</th>
                                <th>Hex Value</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>Sort Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($colors as $color)
                                <tr>
                                    <td>{{ $color['id'] }}</td>
                                    <td>
                                        <strong>{{ $color['name'] }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 30px; height: 30px; background-color: {{ $color['value'] }}; border: 1px solid #ddd; border-radius: 4px; margin-right: 10px;"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <code>{{ $color['value'] }}</code>
                                    </td>
                                    <td>{{ $color['code'] }}</td>
                                    <td>
                                        @if($color['is_active'])
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $color['sort_order'] }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-info btn-sm" 
                                                    onclick="viewColor({{ json_encode($color) }})" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm" 
                                                    onclick="editColor({{ json_encode($color) }})" title="Edit Color">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-success btn-sm" 
                                                    onclick="toggleStatus({{ $color['id'] }}, {{ $color['is_active'] ? 'false' : 'true' }})" 
                                                    title="{{ $color['is_active'] ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $color['is_active'] ? 'toggle-off' : 'toggle-on' }}"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="confirmDelete({{ $color['id'] }})" title="Delete">
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
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this color? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
function confirmDelete(id) {
    const form = document.getElementById('deleteForm');
    form.action = `{{ route('admin.attributes.index') }}/${id}`;
    $('#deleteModal').modal('show');
}

function viewColor(color) {
    const modalContent = `
        <div class="modal fade" id="viewColorModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Color Details: ${color.name}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tr><th>ID:</th><td>${color.id}</td></tr>
                            <tr><th>Name:</th><td>${color.name}</td></tr>
                            <tr><th>Color Preview:</th><td>
                                <div style="width: 40px; height: 40px; background-color: ${color.value}; border: 1px solid #ddd; border-radius: 4px; display: inline-block;"></div>
                            </td></tr>
                            <tr><th>Hex Value:</th><td><code>${color.value}</code></td></tr>
                            <tr><th>Code:</th><td>${color.code}</td></tr>
                            <tr><th>Status:</th><td>
                                ${color.is_active ? 
                                    '<span class="badge badge-success">Active</span>' : 
                                    '<span class="badge badge-secondary">Inactive</span>'
                                }
                            </td></tr>
                            <tr><th>Sort Order:</th><td>${color.sort_order}</td></tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-warning" onclick="editColor(${JSON.stringify(color).replace(/"/g, '&quot;')})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('#viewColorModal').remove();
    $('body').append(modalContent);
    $('#viewColorModal').modal('show');
}

function editColor(color) {
    const modalContent = `
        <div class="modal fade" id="editColorModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Color: ${color.name}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <form id="editColorForm">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="colorName">Name</label>
                                <input type="text" class="form-control" id="colorName" value="${color.name}" required>
                            </div>
                            <div class="form-group">
                                <label for="colorValue">Hex Color Value</label>
                                <div class="input-group">
                                    <input type="color" class="form-control" id="colorPicker" value="${color.value}" style="width: 60px; padding: 3px;">
                                    <input type="text" class="form-control" id="colorValue" value="${color.value}" required>
                                </div>
                                <small class="form-text text-muted">Select a color or enter hex value manually</small>
                            </div>
                            <div class="form-group">
                                <label for="colorCode">Code</label>
                                <input type="text" class="form-control" id="colorCode" value="${color.code}" required>
                            </div>
                            <div class="form-group">
                                <label for="sortOrder">Sort Order</label>
                                <input type="number" class="form-control" id="sortOrder" value="${color.sort_order}" required>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="isActive" ${color.is_active ? 'checked' : ''}>
                                    <label class="form-check-label" for="isActive">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;
    
    $('#viewColorModal, #editColorModal').remove();
    $('body').append(modalContent);
    $('#editColorModal').modal('show');
    
    // Sync color picker with text input
    $('#editColorModal').on('input', '#colorPicker', function() {
        $('#colorValue').val($(this).val());
    });
    
    $('#editColorModal').on('input', '#colorValue', function() {
        $('#colorPicker').val($(this).val());
    });
    
    $('#editColorForm').on('submit', function(e) {
        e.preventDefault();
        alert('Color updated successfully! (This is demo data - changes are not persisted)');
        $('#editColorModal').modal('hide');
    });
}

function toggleStatus(id, newStatus) {
    if (confirm(`Are you sure you want to ${newStatus ? 'activate' : 'deactivate'} this color?`)) {
        alert(`Color ${newStatus ? 'activated' : 'deactivated'} successfully! (This is demo data - changes are not persisted)`);
    }
}
</script>
@endpush
@endsection
