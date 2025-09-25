@extends('admin.layouts.app')

@section('title', 'Commission Settings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
        <div>
            <nav>
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Commission Settings</li>
                </ol>
            </nav>
            <h1 class="page-title fw-medium fs-18 mb-0">Commission Settings</h1>
        </div>
        <div class="btn-list">
            <a href="{{ route('admin.commission-settings.create') }}" class="btn btn-primary btn-wave me-0">
                <i class="bx bx-plus me-1"></i> Add New Setting
            </a>
        </div>
    </div>
    <!-- Page Header Close -->

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Commission Settings Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card overflow-hidden bg-primary-transparent">
                <div class="card-body">
                    <div class="d-flex align-items-top justify-content-between">
                        <div>
                            <span class="avatar avatar-md avatar-rounded bg-primary">
                                <i class="bx bx-cog fs-16"></i>
                            </span>
                        </div>
                        <div class="flex-fill ms-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <div>
                                    <p class="text-muted mb-0">Total Settings</p>
                                    <h4 class="fw-semibold mt-1">{{ $commissionSettings->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card overflow-hidden bg-success-transparent">
                <div class="card-body">
                    <div class="d-flex align-items-top justify-content-between">
                        <div>
                            <span class="avatar avatar-md avatar-rounded bg-success">
                                <i class="bx bx-check-circle fs-16"></i>
                            </span>
                        </div>
                        <div class="flex-fill ms-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <div>
                                    <p class="text-muted mb-0">Active Settings</p>
                                    <h4 class="fw-semibold mt-1">{{ $commissionSettings->where('is_active', true)->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card overflow-hidden bg-warning-transparent">
                <div class="card-body">
                    <div class="d-flex align-items-top justify-content-between">
                        <div>
                            <span class="avatar avatar-md avatar-rounded bg-warning">
                                <i class="bx bx-pause fs-16"></i>
                            </span>
                        </div>
                        <div class="flex-fill ms-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <div>
                                    <p class="text-muted mb-0">Inactive Settings</p>
                                    <h4 class="fw-semibold mt-1">{{ $commissionSettings->where('is_active', false)->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card overflow-hidden bg-info-transparent">
                <div class="card-body">
                    <div class="d-flex align-items-top justify-content-between">
                        <div>
                            <span class="avatar avatar-md avatar-rounded bg-info">
                                <i class="bx bx-percentage fs-16"></i>
                            </span>
                        </div>
                        <div class="flex-fill ms-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <div>
                                    <p class="text-muted mb-0">Percentage Based</p>
                                    <h4 class="fw-semibold mt-1">{{ $commissionSettings->where('calculation_type', 'percentage')->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commission Settings Table -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title">Commission Settings</div>
                    <div class="d-flex align-items-center gap-2">
                        <select class="form-select form-select-sm" id="typeFilter">
                            <option value="">All Types</option>
                            @foreach(\App\Models\CommissionSetting::getTypes() as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <select class="form-select form-select-sm" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap" id="commissionsTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Calculation</th>
                                    <th>Value</th>
                                    <th>Levels</th>
                                    <th>Conditions</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($commissionSettings as $setting)
                                    <tr>
                                        <td>
                                            <div>
                                                <p class="mb-0 fw-semibold">{{ $setting->display_name }}</p>
                                                <p class="mb-0 text-muted fs-12">{{ $setting->name }}</p>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $typeColors = [
                                                    'sponsor' => 'primary',
                                                    'matching' => 'warning',
                                                    'generation' => 'info',
                                                    'rank' => 'success',
                                                    'club' => 'purple',
                                                    'binary' => 'secondary',
                                                    'leadership' => 'dark',
                                                    'affiliate' => 'orange'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $typeColors[$setting->type] ?? 'secondary' }}-transparent">
                                                {{ \App\Models\CommissionSetting::getTypes()[$setting->type] ?? $setting->type }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $setting->calculation_type === 'percentage' ? 'info' : 'success' }}-transparent">
                                                {{ ucfirst($setting->calculation_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-{{ $setting->calculation_type === 'percentage' ? 'info' : 'success' }}">
                                                {{ $setting->formatted_value }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $setting->max_levels }} Level{{ $setting->max_levels > 1 ? 's' : '' }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($setting->conditions_text, 30) }}</small>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input status-toggle" type="checkbox" 
                                                       {{ $setting->is_active ? 'checked' : '' }}
                                                       data-id="{{ $setting->id }}"
                                                       data-url="{{ route('admin.commission-settings.toggle-status', $setting) }}">
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-transparent">
                                                {{ $setting->priority }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.commission-settings.show', $setting) }}" 
                                                   class="btn btn-sm btn-info-light" title="View Details">
                                                    <i class="bx bx-eye me-1"></i>View
                                                </a>
                                                <a href="{{ route('admin.commission-settings.edit', $setting) }}" 
                                                   class="btn btn-sm btn-warning-light" title="Edit">
                                                    <i class="bx bx-edit me-1"></i>Edit
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger-light" 
                                                        onclick="deleteSetting({{ $setting->id }})" title="Delete">
                                                    <i class="bx bx-trash me-1"></i>Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bx bx-search fs-48 text-muted mb-2"></i>
                                                <p class="text-muted mb-2">No commission settings found</p>
                                                <a href="{{ route('admin.commission-settings.create') }}" class="btn btn-primary btn-sm">
                                                    <i class="bx bx-plus me-1"></i>Create First Setting
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
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this commission setting? This action cannot be undone.
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
@endsection

@push('scripts')
<script>
// Status toggle functionality
document.querySelectorAll('.status-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const settingId = this.dataset.id;
        const url = this.dataset.url;
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
            } else {
                showToast('Error updating status', 'error');
                this.checked = !this.checked; // Revert toggle
            }
        })
        .catch(error => {
            showToast('Error updating status', 'error');
            this.checked = !this.checked; // Revert toggle
        });
    });
});

// Delete functionality
function deleteSetting(settingId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/admin/commission-settings/${settingId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Filter functionality
document.getElementById('typeFilter').addEventListener('change', function() {
    filterTable();
});

document.getElementById('statusFilter').addEventListener('change', function() {
    filterTable();
});

function filterTable() {
    const typeFilter = document.getElementById('typeFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const table = document.getElementById('commissionsTable');
    const rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        let showRow = true;
        
        if (typeFilter) {
            const typeCell = row.querySelector('td:nth-child(2) .badge');
            if (typeCell && !typeCell.textContent.toLowerCase().includes(typeFilter.toLowerCase())) {
                showRow = false;
            }
        }
        
        if (statusFilter !== '' && showRow) {
            const statusToggle = row.querySelector('.status-toggle');
            if (statusToggle) {
                const isActive = statusToggle.checked;
                if ((statusFilter === '1' && !isActive) || (statusFilter === '0' && isActive)) {
                    showRow = false;
                }
            }
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

// Toast notification function
function showToast(message, type) {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0 mb-2`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="bx ${type === 'success' ? 'bx-check-circle' : 'bx-error-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast, { delay: 4000 });
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', () => {
        if (toastContainer.contains(toast)) {
            toastContainer.removeChild(toast);
        }
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}
</script>
@endpush

@push('styles')
<style>
.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.375rem 0.75rem;
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}
</style>
@endpush
