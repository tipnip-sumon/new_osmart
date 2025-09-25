@extends('admin.layouts.app')

@section('title', 'Product Attributes')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin-assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-assets/libs/@simonwep/pickr/themes/nano.min.css') }}">
<style>
.attribute-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    border-radius: 12px;
}
.attribute-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
.attribute-type-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
}
.color-preview {
    width: 25px;
    height: 25px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 1px #dee2e6;
    display: inline-block;
    margin-right: 8px;
}
.value-item {
    display: inline-block;
    margin: 2px;
    padding: 4px 8px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    font-size: 0.85rem;
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
.attribute-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: white;
}
.color-icon { background: linear-gradient(45deg, #ff6b6b, #ee5a24); }
.size-icon { background: linear-gradient(45deg, #74b9ff, #0984e3); }
.material-icon { background: linear-gradient(45deg, #a29bfe, #6c5ce7); }
.weight-icon { background: linear-gradient(45deg, #fd79a8, #e84393); }
.brand-icon { background: linear-gradient(45deg, #fdcb6e, #e17055); }
.default-icon { background: linear-gradient(45deg, #636e72, #2d3436); }
</style>
@endpush

@section('content')
<div class="d-sm-flex d-block align-items-center justify-content-between page-header-breadcrumb">
    <h4 class="fw-medium mb-0">Product Attributes</h4>
    <div class="ms-sm-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Attributes</li>
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
                        <i class="ti ti-tags fs-20"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ $totalAttributes ?? 0 }}</h3>
                        <p class="mb-0 opacity-8">Total Attributes</p>
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
                        <i class="ti ti-list fs-20"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ $totalValues ?? 0 }}</h3>
                        <p class="mb-0 opacity-8">Attribute Values</p>
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
                        <h3 class="fw-bold mb-1">{{ $activeAttributes ?? 0 }}</h3>
                        <p class="mb-0 opacity-8">Active Attributes</p>
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
                        <i class="ti ti-filter fs-20"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ $filterableAttributes ?? 0 }}</h3>
                        <p class="mb-0 opacity-8">Filterable</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attributes Management -->
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title">Attributes Management</div>
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAttributeModal">
                        <i class="ti ti-plus me-1"></i>Add Attribute
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="ti ti-filter me-1"></i>Filter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['type' => 'all']) }}">All Types</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['type' => 'color']) }}">Color</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['type' => 'size']) }}">Size</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['type' => 'material']) }}">Material</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['type' => 'weight']) }}">Weight</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}">Active</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'inactive']) }}">Inactive</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap w-100" id="attributesTable">
                        <thead>
                            <tr>
                                <th>
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </th>
                                <th>Attribute</th>
                                <th>Type</th>
                                <th>Values</th>
                                <th>Properties</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attributes as $attribute)
                            <tr>
                                <td>
                                    <input class="form-check-input row-checkbox" type="checkbox" value="{{ $attribute['id'] }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="attribute-icon {{ $attribute['type'] }}-icon me-3">
                                            @switch($attribute['type'])
                                                @case('color')
                                                    <i class="ti ti-palette"></i>
                                                    @break
                                                @case('size')
                                                    <i class="ti ti-ruler"></i>
                                                    @break
                                                @case('material')
                                                    <i class="ti ti-texture"></i>
                                                    @break
                                                @case('weight')
                                                    <i class="ti ti-scale"></i>
                                                    @break
                                                @case('brand')
                                                    <i class="ti ti-award"></i>
                                                    @break
                                                @default
                                                    <i class="ti ti-tag"></i>
                                            @endswitch
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $attribute['name'] }}</h6>
                                            <small class="text-muted">{{ $attribute['slug'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge attribute-type-badge bg-{{ 
                                        $attribute['type'] === 'color' ? 'danger' : 
                                        ($attribute['type'] === 'size' ? 'primary' : 
                                        ($attribute['type'] === 'material' ? 'info' : 
                                        ($attribute['type'] === 'weight' ? 'warning' : 'secondary'))) 
                                    }}-transparent">
                                        {{ ucfirst($attribute['type']) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap align-items-center">
                                        {{-- Values display - simplified for array data --}}
                                        <span class="text-muted">{{ $attribute['input_type'] ?? 'N/A' }}</span>
                                    </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        @if($attribute['is_required'])
                                            <small class="badge bg-danger-transparent mb-1">Required</small>
                                        @endif
                                        @if($attribute['is_filterable'])
                                            <small class="badge bg-info-transparent mb-1">Filterable</small>
                                        @endif
                                        @if(isset($attribute['is_variation']) && $attribute['is_variation'])
                                            <small class="badge bg-warning-transparent">Variation</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle" type="checkbox" 
                                               data-id="{{ $attribute['id'] }}" 
                                               {{ $attribute['is_active'] ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ date('M d, Y', strtotime($attribute['created_at'])) }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-light" onclick="manageValues({{ $attribute['id'] }})" title="Manage Values">
                                            <i class="ti ti-list"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light" onclick="editAttribute({{ $attribute['id'] }})" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light text-danger" onclick="deleteAttribute({{ $attribute['id'] }})" title="Delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="ti ti-tags fs-48 text-muted mb-3"></i>
                                        <h6 class="text-muted">No attributes found</h6>
                                        <p class="text-muted mb-3">Get started by adding your first product attribute</p>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAttributeModal">
                                            <i class="ti ti-plus me-1"></i>Add Attribute
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination section removed since we're using Collection instead of paginated results --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <p class="text-muted mb-0">
                            Showing {{ $attributes->count() }} attributes
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Attribute Modal -->
<div class="modal fade" id="addAttributeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Add Product Attribute</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addAttributeForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Attribute Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="color">Color</option>
                                    <option value="size">Size</option>
                                    <option value="material">Material</option>
                                    <option value="weight">Weight</option>
                                    <option value="brand">Brand</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Attribute description..."></textarea>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_required">
                                    <label class="form-check-label">Required</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_filterable">
                                    <label class="form-check-label">Filterable</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_variation">
                                    <label class="form-check-label">Product Variation</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" checked>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i>Save Attribute
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Manage Values Modal -->
<div class="modal fade" id="manageValuesModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Manage Attribute Values</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Add New Value</h6>
                            </div>
                            <div class="card-body">
                                <form id="addValueForm">
                                    <input type="hidden" id="valueAttributeId" name="attribute_id">
                                    <div class="mb-3">
                                        <label class="form-label">Value <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="value" required>
                                    </div>
                                    <div class="mb-3" id="colorCodeField" style="display: none;">
                                        <label class="form-label">Color Code</label>
                                        <input type="color" class="form-control form-control-color" name="color_code">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Extra Price</label>
                                        <input type="number" class="form-control" name="extra_price" step="0.01">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Image</label>
                                        <input type="file" class="form-control" name="image" accept="image/*">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ti ti-plus me-1"></i>Add Value
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Existing Values</h6>
                            </div>
                            <div class="card-body">
                                <div id="valuesContainer">
                                    <!-- Values will be loaded here -->
                                </div>
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
<script src="{{ asset('admin-assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#attributesTable').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [0, 7] }
        ],
        language: {
            search: "Search attributes:",
            lengthMenu: "Show _MENU_ attributes per page",
            info: "Showing _START_ to _END_ of _TOTAL_ attributes",
            infoEmpty: "No attributes available",
            emptyTable: "No attributes found"
        }
    });

    // Select all checkbox
    $('#selectAll').change(function() {
        $('.row-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Status toggle
    $('.status-toggle').change(function() {
        const id = $(this).data('id');
        const isActive = $(this).prop('checked');
        
        $.ajax({
            url: `/admin/attributes/${id}/toggle-status`,
            type: 'PATCH',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                is_active: isActive
            },
            success: function(response) {
                if (response.success) {
                    showToast('Success', response.message, 'success');
                } else {
                    showToast('Error', response.message, 'error');
                }
            },
            error: function() {
                showToast('Error', 'Failed to update status', 'error');
                $(this).prop('checked', !isActive);
            }
        });
    });

    // Add attribute form
    $('#addAttributeForm').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("admin.attributes.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#addAttributeModal').modal('hide');
                    showToast('Success', response.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    Object.keys(errors).forEach(key => {
                        showToast('Validation Error', errors[key][0], 'error');
                    });
                } else {
                    showToast('Error', 'Failed to create attribute', 'error');
                }
            }
        });
    });

    // Add value form
    $('#addValueForm').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("admin.attribute-values.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showToast('Success', response.message, 'success');
                    loadAttributeValues($('#valueAttributeId').val());
                    $('#addValueForm')[0].reset();
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    Object.keys(errors).forEach(key => {
                        showToast('Validation Error', errors[key][0], 'error');
                    });
                } else {
                    showToast('Error', 'Failed to add value', 'error');
                }
            }
        });
    });
});

function manageValues(attributeId) {
    $('#valueAttributeId').val(attributeId);
    loadAttributeValues(attributeId);
    $('#manageValuesModal').modal('show');
}

function loadAttributeValues(attributeId) {
    $.ajax({
        url: `/admin/attributes/${attributeId}/values`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#valuesContainer').html(response.html);
                
                // Show/hide color field based on attribute type
                if (response.attribute.type === 'color') {
                    $('#colorCodeField').show();
                } else {
                    $('#colorCodeField').hide();
                }
            }
        },
        error: function() {
            showToast('Error', 'Failed to load values', 'error');
        }
    });
}

function editAttribute(id) {
    window.location.href = `/admin/attributes/${id}/edit`;
}

function deleteAttribute(id) {
    if (confirm('Are you sure you want to delete this attribute? This will also delete all associated values.')) {
        $.ajax({
            url: `/admin/attributes/${id}`,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showToast('Success', response.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                }
            },
            error: function() {
                showToast('Error', 'Failed to delete attribute', 'error');
            }
        });
    }
}

function deleteValue(id) {
    if (confirm('Are you sure you want to delete this value?')) {
        $.ajax({
            url: `/admin/attribute-values/${id}`,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showToast('Success', response.message, 'success');
                    loadAttributeValues($('#valueAttributeId').val());
                }
            },
            error: function() {
                showToast('Error', 'Failed to delete value', 'error');
            }
        });
    }
}

function showToast(title, message, type) {
    const toast = `
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}:</strong> ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    const toastContainer = $('.toast-container');
    if (toastContainer.length === 0) {
        $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3"></div>');
    }
    
    $('.toast-container').append(toast);
    $('.toast').last().toast('show');
}
</script>
@endpush
