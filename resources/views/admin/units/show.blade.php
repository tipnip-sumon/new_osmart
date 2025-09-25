@extends('admin.layouts.app')

@section('title', 'Unit Details')

@section('content')
<div class="d-sm-flex d-block align-items-center justify-content-between page-header-breadcrumb">
    <h4 class="fw-medium mb-0">Unit Details</h4>
    <div class="ms-sm-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.units.index') }}">Units</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $unit->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-xl-8">
        <div class="card custom-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title">Unit Information</div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-edit me-1"></i>Edit Unit
                    </a>
                    <a href="{{ route('admin.units.index') }}" class="btn btn-light btn-sm">
                        <i class="ti ti-arrow-left me-1"></i>Back to Units
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <label class="form-label text-muted">Unit Name</label>
                            <p class="fs-16 fw-semibold">{{ $unit->name }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <label class="form-label text-muted">Symbol</label>
                            <p class="fs-16 fw-semibold">
                                <span class="badge bg-light text-dark fs-14">{{ $unit->symbol }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <label class="form-label text-muted">Type</label>
                            <p class="fs-16 fw-semibold">
                                <span class="badge bg-{{ 
                                    $unit->type === 'weight' ? 'danger' : 
                                    ($unit->type === 'length' ? 'primary' : 
                                    ($unit->type === 'volume' ? 'info' : 
                                    ($unit->type === 'area' ? 'warning' : 'secondary'))) 
                                }}-transparent">{{ ucfirst($unit->type) }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <label class="form-label text-muted">Status</label>
                            <p class="fs-16 fw-semibold">
                                <span class="badge bg-{{ $unit->is_active ? 'success' : 'danger' }}-transparent">
                                    {{ $unit->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    @if($unit->base_unit_id)
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <label class="form-label text-muted">Base Unit</label>
                            <p class="fs-16 fw-semibold">
                                @if($unit->baseUnit)
                                    {{ $unit->baseUnit->name }} ({{ $unit->baseUnit->symbol }})
                                @else
                                    Not specified
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif
                    @if($unit->base_factor && $unit->base_factor != 1)
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <label class="form-label text-muted">Base Factor</label>
                            <p class="fs-16 fw-semibold">{{ $unit->base_factor }}</p>
                        </div>
                    </div>
                    @endif
                    @if($unit->decimal_places !== null)
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <label class="form-label text-muted">Decimal Places</label>
                            <p class="fs-16 fw-semibold">{{ $unit->decimal_places }}</p>
                        </div>
                    </div>
                    @endif
                    @if($unit->description)
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label text-muted">Description</label>
                            <p class="fs-16">{{ $unit->description }}</p>
                        </div>
                    </div>
                    @endif
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <label class="form-label text-muted">Created Date</label>
                            <p class="fs-16">{{ $unit->created_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                    @if($unit->updated_at != $unit->created_at)
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <label class="form-label text-muted">Last Updated</label>
                            <p class="fs-16">{{ $unit->updated_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Quick Actions</div>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-primary">
                        <i class="ti ti-edit me-2"></i>Edit Unit
                    </a>
                    <button type="button" class="btn btn-{{ $unit->is_active ? 'warning' : 'success' }}" onclick="toggleStatus({{ $unit->id }})">
                        <i class="ti ti-{{ $unit->is_active ? 'eye-off' : 'eye' }} me-2"></i>
                        {{ $unit->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                    <button type="button" class="btn btn-danger" onclick="deleteUnit({{ $unit->id }})">
                        <i class="ti ti-trash me-2"></i>Delete Unit
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Unit Statistics</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="fw-bold text-primary">{{ $unit->products->count() ?? 0 }}</h4>
                            <p class="text-muted mb-0 fs-12">Products Using</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="fw-bold text-success">{{ $unit->childUnits->count() ?? 0 }}</h4>
                            <p class="text-muted mb-0 fs-12">Child Units</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleStatus(id) {
    if (confirm('Are you sure you want to change the status of this unit?')) {
        $.ajax({
            url: `{{ route('admin.units.index') }}/${id}/toggle-status`,
            type: 'POST',
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
                showToast('Error', 'Failed to update status', 'error');
            }
        });
    }
}

function deleteUnit(id) {
    if (confirm('Are you sure you want to delete this unit? This action cannot be undone.')) {
        $.ajax({
            url: `{{ route('admin.units.index') }}/${id}`,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showToast('Success', response.message, 'success');
                    setTimeout(() => window.location.href = '{{ route("admin.units.index") }}', 1500);
                }
            },
            error: function() {
                showToast('Error', 'Failed to delete unit', 'error');
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
@endsection
