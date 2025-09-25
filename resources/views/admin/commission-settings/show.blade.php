@extends('admin.layouts.app')

@section('content')
<div class="page">
    <!-- Start::app-content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h1 class="page-title fw-medium fs-18 mb-2">Commission Setting Details</h1>
                    <div class="">
                        <nav>
                            <ol class="breadcrumb breadcrumb-example1 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.commission-settings.index') }}">Commission Settings</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $commissionSetting->name }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="btn-list">
                    <a href="{{ route('admin.commission-settings.edit', $commissionSetting->id) }}" class="btn btn-primary">
                        <i class="ri-edit-line me-2"></i>Edit Setting
                    </a>
                    <a href="{{ route('admin.commission-settings.index') }}" class="btn btn-outline-primary">
                        <i class="ri-arrow-left-line me-2"></i>Back to List
                    </a>
                </div>
            </div>
            <!-- Page Header Close -->

            <!-- Start::row-1 -->
            <div class="row">
                <!-- Basic Information -->
                <div class="col-xl-4">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Basic Information</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Name</label>
                                        <p class="mb-0 fw-semibold">{{ $commissionSetting->name }}</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Type</label>
                                        <p class="mb-0">
                                            <span class="badge bg-primary-transparent">
                                                {{ ucfirst(str_replace('_', ' ', $commissionSetting->type)) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Calculation Type</label>
                                        <p class="mb-0">
                                            <span class="badge bg-info-transparent">
                                                {{ ucfirst($commissionSetting->calculation_type) }}
                                                @if($commissionSetting->calculation_type == 'percentage')
                                                    (%)
                                                @else
                                                    (৳)
                                                @endif
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Commission Value</label>
                                        <p class="mb-0 fw-semibold fs-18 text-success">
                                            {{ $commissionSetting->formatted_value }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Priority</label>
                                        <p class="mb-0">{{ $commissionSetting->priority ?? 'Not set' }}</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Status</label>
                                        <p class="mb-0">
                                            @if($commissionSetting->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @if($commissionSetting->description)
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Description</label>
                                        <p class="mb-0">{{ $commissionSetting->description }}</p>
                                    </div>
                                </div>
                                @endif
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Created At</label>
                                        <p class="mb-0">{{ $commissionSetting->created_at->format('M d, Y \a\t H:i') }}</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-0">
                                        <label class="form-label text-muted">Last Updated</label>
                                        <p class="mb-0">{{ $commissionSetting->updated_at->format('M d, Y \a\t H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Matching Features (Only for matching type) -->
                    @if($commissionSetting->type === 'matching')
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Enhanced Matching Configuration</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Basic Matching Info -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Matching Frequency</label>
                                    <p class="mb-0">
                                        <span class="badge bg-info-transparent">
                                            {{ ucfirst(str_replace('_', ' ', $commissionSetting->matching_frequency ?? 'daily')) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Matching Time</label>
                                    <p class="mb-0">{{ $commissionSetting->matching_time ?? 'Not set' }}</p>
                                </div>

                                <!-- Carry Forward Status -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label text-muted">Carry Forward</label>
                                    <p class="mb-0">
                                        @if($commissionSetting->carry_forward_enabled)
                                            <span class="badge bg-success">Enabled</span>
                                            - {{ ucfirst($commissionSetting->carry_side) }} Side
                                            @if($commissionSetting->carry_percentage)
                                                ({{ $commissionSetting->carry_percentage }}%)
                                            @endif
                                            @if($commissionSetting->carry_max_days)
                                                - Max {{ $commissionSetting->carry_max_days }} days
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Disabled</span>
                                        @endif
                                    </p>
                                </div>

                                <!-- Slot Matching Status -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label text-muted">Slot Matching</label>
                                    <p class="mb-0">
                                        @if($commissionSetting->slot_matching_enabled)
                                            <span class="badge bg-success">Enabled</span>
                                            @if($commissionSetting->slot_size)
                                                - Size: {{ number_format($commissionSetting->slot_size) }}
                                            @endif
                                            @if($commissionSetting->slot_type)
                                                ({{ ucfirst($commissionSetting->slot_type) }} Based)
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Disabled</span>
                                        @endif
                                    </p>
                                </div>

                                <!-- Advanced Rules -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Auto Balance</label>
                                    <p class="mb-0">
                                        @if($commissionSetting->auto_balance_enabled)
                                            <span class="badge bg-success">Enabled</span>
                                            @if($commissionSetting->balance_ratio)
                                                (Ratio: {{ $commissionSetting->balance_ratio }})
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Disabled</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Spillover</label>
                                    <p class="mb-0">
                                        @if($commissionSetting->spillover_enabled)
                                            <span class="badge bg-success">Enabled</span>
                                            @if($commissionSetting->spillover_direction)
                                                ({{ ucfirst(str_replace('_', ' ', $commissionSetting->spillover_direction)) }})
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Disabled</span>
                                        @endif
                                    </p>
                                </div>

                                <!-- Flush & Capping -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label text-muted">Flush</label>
                                    <p class="mb-0">
                                        @if($commissionSetting->flush_enabled)
                                            <span class="badge bg-warning">{{ $commissionSetting->flush_percentage }}%</span>
                                        @else
                                            <span class="badge bg-secondary">Disabled</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label text-muted">Daily Cap</label>
                                    <p class="mb-0">
                                        @if($commissionSetting->daily_cap_enabled)
                                            <span class="badge bg-warning">৳{{ number_format($commissionSetting->daily_cap_amount, 2) }}</span>
                                        @else
                                            <span class="badge bg-secondary">No Limit</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label text-muted">Weekly Cap</label>
                                    <p class="mb-0">
                                        @if($commissionSetting->weekly_cap_enabled)
                                            <span class="badge bg-warning">৳{{ number_format($commissionSetting->weekly_cap_amount, 2) }}</span>
                                        @else
                                            <span class="badge bg-secondary">No Limit</span>
                                        @endif
                                    </p>
                                </div>

                                <!-- Qualification Requirements -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Personal Volume Requirement</label>
                                    <p class="mb-0">
                                        @if($commissionSetting->personal_volume_required)
                                            <span class="badge bg-info">Required</span>
                                            @if($commissionSetting->min_personal_volume)
                                                - Min: ৳{{ number_format($commissionSetting->min_personal_volume, 2) }}
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Not Required</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Both Legs Requirement</label>
                                    <p class="mb-0">
                                        @if($commissionSetting->both_legs_required)
                                            <span class="badge bg-info">Required</span>
                                            @if($commissionSetting->min_left_volume || $commissionSetting->min_right_volume)
                                                <br><small class="text-muted">
                                                    Left: ৳{{ number_format($commissionSetting->min_left_volume ?? 0, 2) }} | 
                                                    Right: ৳{{ number_format($commissionSetting->min_right_volume ?? 0, 2) }}
                                                </small>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Not Required</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Quick Summary -->
                            <hr>
                            <div class="alert alert-info">
                                <h6 class="mb-2">Configuration Summary:</h6>
                                <p class="mb-0">{{ $commissionSetting->getMatchingConfigSummary() }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Conditions and Configuration -->
                <div class="col-xl-8">
                    <!-- Conditions -->
                    @if($commissionSetting->conditions && count($commissionSetting->conditions) > 0)
                    <div class="card custom-card mb-4">
                        <div class="card-header">
                            <div class="card-title">Commission Conditions</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Condition</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($commissionSetting->conditions as $key => $value)
                                            <tr>
                                                <td>
                                                    <span class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                                </td>
                                                <td>
                                                    @if(is_numeric($value))
                                                        @if(in_array($key, ['flush_percentage', 'target_percentage']))
                                                            {{ $value }}%
                                                        @elseif(in_array($key, ['min_volume', 'min_personal_sales', 'min_left_volume', 'min_right_volume', 'cap_amount', 'min_team_volume', 'min_sales_volume']))
                                                            ৳{{ number_format($value, 2) }}
                                                        @elseif(in_array($key, ['qualification_period']))
                                                            {{ $value }} days
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary-transparent">{{ ucfirst($value) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Multi-Level Configuration -->
                    @if($commissionSetting->levels && count($commissionSetting->levels) > 0)
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Multi-Level Configuration</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Level</th>
                                            <th>Commission Value</th>
                                            <th>Condition</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($commissionSetting->levels as $level)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-primary">Level {{ $level['level'] }}</span>
                                                </td>
                                                <td>
                                                    <span class="fw-semibold text-success">
                                                        @if($commissionSetting->calculation_type == 'percentage')
                                                            {{ $level['value'] }}%
                                                        @else
                                                            ৳{{ number_format($level['value'], 2) }}
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    @if(isset($level['condition']) && $level['condition'])
                                                        <code>{{ $level['condition'] }}</code>
                                                    @else
                                                        <span class="text-muted">No specific condition</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Summary Card -->
                    <div class="card custom-card mt-4">
                        <div class="card-header">
                            <div class="card-title">Commission Summary</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="bx bx-cog text-primary fs-24"></i>
                                        </div>
                                        <h6 class="mb-1">Configuration Type</h6>
                                        <p class="text-muted mb-0">
                                            @if($commissionSetting->levels && count($commissionSetting->levels) > 0)
                                                Multi-Level Setup
                                            @else
                                                Single-Level Setup
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="bx bx-check-circle text-success fs-24"></i>
                                        </div>
                                        <h6 class="mb-1">Conditions</h6>
                                        <p class="text-muted mb-0">
                                            {{ $commissionSetting->conditions ? count($commissionSetting->conditions) : 0 }} conditions set
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="bx bx-layer text-info fs-24"></i>
                                        </div>
                                        <h6 class="mb-1">Levels</h6>
                                        <p class="text-muted mb-0">
                                            {{ $commissionSetting->levels ? count($commissionSetting->levels) : 0 }} levels configured
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if($commissionSetting->conditions)
                            <hr>
                            <div class="mt-3">
                                <h6 class="mb-2">Condition Text</h6>
                                <p class="text-muted mb-0">{{ $commissionSetting->condition_text }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- End::row-1 -->

            <!-- Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.commission-settings.edit', $commissionSetting->id) }}" class="btn btn-primary">
                                    <i class="ri-edit-line me-1"></i>Edit Setting
                                </a>
                                
                                <button type="button" class="btn btn-{{ $commissionSetting->is_active ? 'warning' : 'success' }}" onclick="toggleStatus()">
                                    <i class="ri-{{ $commissionSetting->is_active ? 'pause' : 'play' }}-line me-1"></i>
                                    <span id="status-text">{{ $commissionSetting->is_active ? 'Deactivate' : 'Activate' }}</span>
                                </button>

                                <form action="{{ route('admin.commission-settings.destroy', $commissionSetting->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this commission setting? This action cannot be undone.')">
                                        <i class="ri-delete-bin-line me-1"></i>Delete Setting
                                    </button>
                                </form>

                                <button type="button" class="btn btn-info" onclick="duplicateSetting()">
                                    <i class="ri-file-copy-line me-1"></i>Duplicate Setting
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
</div>

<script>
function toggleStatus() {
    const button = event.target.closest('button');
    const icon = button.querySelector('i');
    const text = button.querySelector('#status-text');
    const originalContent = button.innerHTML;
    
    // Show loading state
    button.disabled = true;
    button.innerHTML = '<i class="ri-loader-line ri-spin me-1"></i>Updating...';
    
    fetch(`{{ route('admin.commission-settings.toggle-status', $commissionSetting->id) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update button appearance based on new status
            if (data.is_active) {
                button.className = 'btn btn-warning';
                button.innerHTML = '<i class="ri-pause-line me-1"></i><span id="status-text">Deactivate</span>';
            } else {
                button.className = 'btn btn-success';
                button.innerHTML = '<i class="ri-play-line me-1"></i><span id="status-text">Activate</span>';
            }
            
            // Update status badge in the basic information section
            const statusBadge = document.querySelector('.badge');
            if (statusBadge && statusBadge.textContent.includes('Active') || statusBadge.textContent.includes('Inactive')) {
                if (data.is_active) {
                    statusBadge.className = 'badge bg-success';
                    statusBadge.textContent = 'Active';
                } else {
                    statusBadge.className = 'badge bg-danger';
                    statusBadge.textContent = 'Inactive';
                }
            }
            
            // Show success message
            showToast(data.message, 'success');
        } else {
            // Restore original button state
            button.innerHTML = originalContent;
            showToast('Error updating status', 'error');
        }
    })
    .catch(error => {
        // Restore original button state
        button.innerHTML = originalContent;
        showToast('Error updating status', 'error');
        console.error('Error:', error);
    })
    .finally(() => {
        button.disabled = false;
    });
}

function showToast(message, type) {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0 mb-2`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="ri-${type === 'success' ? 'check' : 'error-warning'}-line me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    // Initialize and show toast
    const bsToast = new bootstrap.Toast(toast, { delay: 4000 });
    bsToast.show();
    
    // Remove toast element after it's hidden
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

function duplicateSetting() {
    if (confirm('Create a duplicate of this commission setting?')) {
        // Create a form to duplicate the setting
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.commission-settings.store") }}';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add setting data
        const settingData = @json($commissionSetting->toArray());
        
        // Create hidden inputs for each field
        Object.keys(settingData).forEach(key => {
            if (['id', 'created_at', 'updated_at'].includes(key)) return;
            
            const input = document.createElement('input');
            input.type = 'hidden';
            
            if (key === 'name') {
                input.value = settingData[key] + ' (Copy)';
            } else if (typeof settingData[key] === 'object') {
                input.value = JSON.stringify(settingData[key]);
            } else {
                input.value = settingData[key];
            }
            
            input.name = key;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
