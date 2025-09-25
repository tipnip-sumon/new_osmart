@extends('admin.layouts.app')

@section('title', 'MLM Bonus Setting Details')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">MLM Bonus Setting Details</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.mlm-bonus-settings.index') }}">MLM Bonus Settings</a></li>
                        <li class="breadcrumb-item active">{{ $mlmBonusSetting->setting_name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Basic Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Setting Name</label>
                                <p class="fw-semibold">{{ $mlmBonusSetting->setting_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Setting Key</label>
                                <p class="fw-semibold"><code>{{ $mlmBonusSetting->setting_key }}</code></p>
                            </div>
                        </div>
                    </div>

                    @if($mlmBonusSetting->description)
                        <div class="mb-3">
                            <label class="form-label text-muted">Description</label>
                            <p>{{ $mlmBonusSetting->description }}</p>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Category</label>
                                <p>
                                    <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $mlmBonusSetting->category)) }}</span>
                                </p>
                            </div>
                        </div>
                        @if($mlmBonusSetting->subcategory)
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Subcategory</label>
                                    <p>{{ ucfirst($mlmBonusSetting->subcategory) }}</p>
                                </div>
                            </div>
                        @endif
                        @if($mlmBonusSetting->level)
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Level</label>
                                    <p><span class="badge bg-info">Level {{ $mlmBonusSetting->level }}</span></p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Value Configuration -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Value Configuration</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Setting Type</label>
                                <p><span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $mlmBonusSetting->setting_type)) }}</span></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Calculation Method</label>
                                <p><span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $mlmBonusSetting->calculation_method)) }}</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Value</label>
                                <p class="fw-semibold text-success">
                                    @if($mlmBonusSetting->setting_type === 'percentage')
                                        {{ $mlmBonusSetting->value }}%
                                    @else
                                        ${{ number_format($mlmBonusSetting->value, 2) }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        @if($mlmBonusSetting->min_value)
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Minimum Value</label>
                                    <p>
                                        @if($mlmBonusSetting->setting_type === 'percentage')
                                            {{ $mlmBonusSetting->min_value }}%
                                        @else
                                            ${{ number_format($mlmBonusSetting->min_value, 2) }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endif
                        @if($mlmBonusSetting->max_value)
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Maximum Value</label>
                                    <p>
                                        @if($mlmBonusSetting->setting_type === 'percentage')
                                            {{ $mlmBonusSetting->max_value }}%
                                        @else
                                            ${{ number_format($mlmBonusSetting->max_value, 2) }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Thresholds & Requirements -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Thresholds & Requirements</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($mlmBonusSetting->threshold_amount)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Threshold Amount</label>
                                    <p class="fw-semibold">${{ number_format($mlmBonusSetting->threshold_amount, 2) }}</p>
                                </div>
                            </div>
                        @endif
                        @if($mlmBonusSetting->threshold_count)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Threshold Count</label>
                                    <p class="fw-semibold">{{ $mlmBonusSetting->threshold_count }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">KYC Required</label>
                                <p>
                                    @if($mlmBonusSetting->requires_kyc)
                                        <span class="badge bg-warning">Required</span>
                                    @else
                                        <span class="badge bg-secondary">Not Required</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Rank Required</label>
                                <p>
                                    @if($mlmBonusSetting->requires_rank)
                                        <span class="badge bg-warning">{{ $mlmBonusSetting->rank_required ?: 'Any Rank' }}</span>
                                    @else
                                        <span class="badge bg-secondary">Not Required</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conditions -->
            @if($mlmBonusSetting->conditions)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Additional Conditions</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Condition</th>
                                        <th>Required Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mlmBonusSetting->conditions as $key => $value)
                                        <tr>
                                            <td><code>{{ $key }}</code></td>
                                            <td>{{ $value }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formula -->
            @if($mlmBonusSetting->formula)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Custom Formula</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">Calculation Formula</label>
                            <pre class="bg-light p-3 rounded"><code>{{ $mlmBonusSetting->formula }}</code></pre>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Additional Settings -->
            @if($mlmBonusSetting->additional_settings)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Additional Settings</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Setting</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mlmBonusSetting->additional_settings as $key => $value)
                                        <tr>
                                            <td><code>{{ $key }}</code></td>
                                            <td>
                                                @if(is_array($value))
                                                    <pre><code>{{ json_encode($value, JSON_PRETTY_PRINT) }}</code></pre>
                                                @else
                                                    {{ $value }}
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
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Actions</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($mlmBonusSetting->is_editable)
                            <a href="{{ route('admin.mlm-bonus-settings.edit', $mlmBonusSetting) }}" class="btn btn-primary">
                                <i class="bx bx-edit"></i> Edit Setting
                            </a>
                        @else
                            <div class="alert alert-warning">
                                <i class="bx bx-lock"></i> This setting is protected and cannot be modified.
                            </div>
                        @endif
                        <a href="{{ route('admin.mlm-bonus-settings.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-left"></i> Back to List
                        </a>
                        <button type="button" class="btn btn-info" onclick="toggleStatus()">
                            <i class="bx bx-toggle-{{ $mlmBonusSetting->is_active ? 'right' : 'left' }}"></i> 
                            {{ $mlmBonusSetting->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Status</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Current Status</label>
                        <p>
                            <span class="badge {{ $mlmBonusSetting->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $mlmBonusSetting->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Editable</label>
                        <p>
                            <span class="badge {{ $mlmBonusSetting->is_editable ? 'bg-success' : 'bg-danger' }}">
                                {{ $mlmBonusSetting->is_editable ? 'Yes' : 'No' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Information -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Information</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Created</label>
                        <p>{{ $mlmBonusSetting->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Last Updated</label>
                        <p>{{ $mlmBonusSetting->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">ID</label>
                        <p><code>{{ $mlmBonusSetting->id }}</code></p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Quick Statistics</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Category Settings</label>
                        <p>{{ $categoryCount ?? 0 }} settings in this category</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Total Active Settings</label>
                        <p>{{ $activeCount ?? 0 }} active settings</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Toggle Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Status Change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to {{ $mlmBonusSetting->is_active ? 'deactivate' : 'activate' }} this setting?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.mlm-bonus-settings.toggle-status', $mlmBonusSetting) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStatus() {
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

// Auto-refresh status if changed
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        setTimeout(() => {
            location.reload();
        }, 1500);
    @endif
});
</script>
@endpush
