@extends('admin.layouts.app')

@section('title', 'Edit MLM Bonus Setting')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit MLM Bonus Setting</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.mlm-bonus-settings.index') }}">MLM Bonus Settings</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.mlm-bonus-settings.update', $mlmBonusSetting) }}" method="POST" id="settingForm">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Main Form -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Basic Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="setting_name" class="form-label">Setting Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('setting_name') is-invalid @enderror" 
                                           id="setting_name" name="setting_name" value="{{ old('setting_name', $mlmBonusSetting->setting_name) }}" required
                                           {{ !$mlmBonusSetting->is_editable ? 'readonly' : '' }}>
                                    @error('setting_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="setting_key" class="form-label">Setting Key <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('setting_key') is-invalid @enderror" 
                                           id="setting_key" name="setting_key" value="{{ old('setting_key', $mlmBonusSetting->setting_key) }}" required
                                           {{ !$mlmBonusSetting->is_editable ? 'readonly' : '' }}>
                                    <div class="form-text">Unique identifier for this setting</div>
                                    @error('setting_key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3"
                                      {{ !$mlmBonusSetting->is_editable ? 'readonly' : '' }}>{{ old('description', $mlmBonusSetting->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category') is-invalid @enderror" 
                                            id="category" name="category" required
                                            {{ !$mlmBonusSetting->is_editable ? 'disabled' : '' }}>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $key => $name)
                                            <option value="{{ $key }}" {{ old('category', $mlmBonusSetting->category) == $key ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(!$mlmBonusSetting->is_editable)
                                        <input type="hidden" name="category" value="{{ $mlmBonusSetting->category }}">
                                    @endif
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="subcategory" class="form-label">Subcategory</label>
                                    <input type="text" class="form-control @error('subcategory') is-invalid @enderror" 
                                           id="subcategory" name="subcategory" value="{{ old('subcategory', $mlmBonusSetting->subcategory) }}"
                                           {{ !$mlmBonusSetting->is_editable ? 'readonly' : '' }}>
                                    @error('subcategory')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="level" class="form-label">Level</label>
                                    <input type="number" class="form-control @error('level') is-invalid @enderror" 
                                           id="level" name="level" value="{{ old('level', $mlmBonusSetting->level) }}" min="1"
                                           {{ !$mlmBonusSetting->is_editable ? 'readonly' : '' }}>
                                    <div class="form-text">For generation/unilevel levels</div>
                                    @error('level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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
                                    <label for="setting_type" class="form-label">Setting Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('setting_type') is-invalid @enderror" 
                                            id="setting_type" name="setting_type" required
                                            {{ !$mlmBonusSetting->is_editable ? 'disabled' : '' }}>
                                        <option value="">Select Type</option>
                                        @foreach($types as $key => $name)
                                            <option value="{{ $key }}" {{ old('setting_type', $mlmBonusSetting->setting_type) == $key ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(!$mlmBonusSetting->is_editable)
                                        <input type="hidden" name="setting_type" value="{{ $mlmBonusSetting->setting_type }}">
                                    @endif
                                    @error('setting_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="calculation_method" class="form-label">Calculation Method <span class="text-danger">*</span></label>
                                    <select class="form-select @error('calculation_method') is-invalid @enderror" 
                                            id="calculation_method" name="calculation_method" required
                                            {{ !$mlmBonusSetting->is_editable ? 'disabled' : '' }}>
                                        <option value="">Select Method</option>
                                        @foreach($calculationMethods as $key => $name)
                                            <option value="{{ $key }}" {{ old('calculation_method', $mlmBonusSetting->calculation_method) == $key ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(!$mlmBonusSetting->is_editable)
                                        <input type="hidden" name="calculation_method" value="{{ $mlmBonusSetting->calculation_method }}">
                                    @endif
                                    @error('calculation_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="value" class="form-label">Value <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('value') is-invalid @enderror" 
                                           id="value" name="value" value="{{ old('value', $mlmBonusSetting->value) }}" step="0.01" required
                                           {{ !$mlmBonusSetting->is_editable ? 'readonly' : '' }}>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="min_value" class="form-label">Minimum Value</label>
                                    <input type="number" class="form-control @error('min_value') is-invalid @enderror" 
                                           id="min_value" name="min_value" value="{{ old('min_value', $mlmBonusSetting->min_value) }}" step="0.01"
                                           {{ !$mlmBonusSetting->is_editable ? 'readonly' : '' }}>
                                    @error('min_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="max_value" class="form-label">Maximum Value</label>
                                    <input type="number" class="form-control @error('max_value') is-invalid @enderror" 
                                           id="max_value" name="max_value" value="{{ old('max_value', $mlmBonusSetting->max_value) }}" step="0.01"
                                           {{ !$mlmBonusSetting->is_editable ? 'readonly' : '' }}>
                                    @error('max_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thresholds -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thresholds & Requirements</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="threshold_amount" class="form-label">Threshold Amount</label>
                                    <input type="number" class="form-control @error('threshold_amount') is-invalid @enderror" 
                                           id="threshold_amount" name="threshold_amount" value="{{ old('threshold_amount', $mlmBonusSetting->threshold_amount) }}" step="0.01" min="0"
                                           {{ !$mlmBonusSetting->is_editable ? 'readonly' : '' }}>
                                    <div class="form-text">Minimum amount required to qualify</div>
                                    @error('threshold_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="threshold_count" class="form-label">Threshold Count</label>
                                    <input type="number" class="form-control @error('threshold_count') is-invalid @enderror" 
                                           id="threshold_count" name="threshold_count" value="{{ old('threshold_count', $mlmBonusSetting->threshold_count) }}" min="0"
                                           {{ !$mlmBonusSetting->is_editable ? 'readonly' : '' }}>
                                    <div class="form-text">Minimum count required to qualify</div>
                                    @error('threshold_count')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="requires_kyc" name="requires_kyc" value="1" 
                                               {{ old('requires_kyc', $mlmBonusSetting->requires_kyc) ? 'checked' : '' }}
                                               {{ !$mlmBonusSetting->is_editable ? 'disabled' : '' }}>
                                        <label class="form-check-label" for="requires_kyc">
                                            Requires KYC Verification
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="requires_rank" name="requires_rank" value="1" 
                                               {{ old('requires_rank', $mlmBonusSetting->requires_rank) ? 'checked' : '' }}
                                               {{ !$mlmBonusSetting->is_editable ? 'disabled' : '' }}>
                                        <label class="form-check-label" for="requires_rank">
                                            Requires Specific Rank
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3" id="rank_required_field" style="display: {{ old('requires_rank', $mlmBonusSetting->requires_rank) ? 'block' : 'none' }};">
                            <label for="rank_required" class="form-label">Required Rank</label>
                            <input type="text" class="form-control @error('rank_required') is-invalid @enderror" 
                                   id="rank_required" name="rank_required" value="{{ old('rank_required', $mlmBonusSetting->rank_required) }}"
                                   {{ !$mlmBonusSetting->is_editable ? 'readonly' : '' }}>
                            @error('rank_required')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Conditions -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Additional Conditions</h4>
                    </div>
                    <div class="card-body">
                        <div id="conditions-container">
                            @php
                                $conditions = old('condition_keys', $mlmBonusSetting->conditions ? array_keys($mlmBonusSetting->conditions) : ['']);
                                $values = old('condition_values', $mlmBonusSetting->conditions ? array_values($mlmBonusSetting->conditions) : ['']);
                            @endphp
                            @foreach($conditions as $index => $key)
                                <div class="condition-row row mb-2">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="condition_keys[]" placeholder="Condition Key" 
                                               value="{{ $key }}" {{ !$mlmBonusSetting->is_editable ? 'readonly' : '' }}>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="condition_values[]" placeholder="Required Value" 
                                               value="{{ $values[$index] ?? '' }}" {{ !$mlmBonusSetting->is_editable ? 'readonly' : '' }}>
                                    </div>
                                    <div class="col-md-2">
                                        @if($mlmBonusSetting->is_editable)
                                            <button type="button" class="btn btn-outline-danger remove-condition">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($mlmBonusSetting->is_editable)
                            <button type="button" class="btn btn-outline-primary" id="add-condition">
                                <i class="bx bx-plus"></i> Add Condition
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Formula -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Custom Formula</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="formula" class="form-label">Calculation Formula</label>
                            <textarea class="form-control @error('formula') is-invalid @enderror" 
                                      id="formula" name="formula" rows="3" placeholder="Optional custom calculation formula"
                                      {{ !$mlmBonusSetting->is_editable ? 'readonly' : '' }}>{{ old('formula', $mlmBonusSetting->formula) }}</textarea>
                            <div class="form-text">Use variables like {amount}, {level}, {count} etc.</div>
                            @error('formula')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
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
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save"></i> Update Setting
                                </button>
                            @else
                                <div class="alert alert-warning">
                                    <i class="bx bx-lock"></i> This setting is protected and cannot be modified.
                                </div>
                            @endif
                            <a href="{{ route('admin.mlm-bonus-settings.show', $mlmBonusSetting) }}" class="btn btn-info">
                                <i class="bx bx-show"></i> View Details
                            </a>
                            <a href="{{ route('admin.mlm-bonus-settings.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Status</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $mlmBonusSetting->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Information -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <small class="text-muted">Created:</small><br>
                            <span>{{ $mlmBonusSetting->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Last Updated:</small><br>
                            <span>{{ $mlmBonusSetting->updated_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Editable:</small><br>
                            <span class="badge {{ $mlmBonusSetting->is_editable ? 'bg-success' : 'bg-danger' }}">
                                {{ $mlmBonusSetting->is_editable ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mlmBonusSettingEditable = {{ $mlmBonusSetting->is_editable ? 'true' : 'false' }};
    
    if (!mlmBonusSettingEditable) {
        return; // Skip interactive features for non-editable settings
    }

    // Show/hide rank required field
    const requiresRank = document.getElementById('requires_rank');
    const rankRequiredField = document.getElementById('rank_required_field');
    
    requiresRank.addEventListener('change', function() {
        rankRequiredField.style.display = this.checked ? 'block' : 'none';
    });

    // Conditions management
    const conditionsContainer = document.getElementById('conditions-container');
    const addConditionBtn = document.getElementById('add-condition');
    
    if (addConditionBtn) {
        addConditionBtn.addEventListener('click', function() {
            const conditionRow = document.createElement('div');
            conditionRow.className = 'condition-row row mb-2';
            conditionRow.innerHTML = `
                <div class="col-md-5">
                    <input type="text" class="form-control" name="condition_keys[]" placeholder="Condition Key">
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control" name="condition_values[]" placeholder="Required Value">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger remove-condition">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            `;
            conditionsContainer.appendChild(conditionRow);
            updateRemoveButtons();
        });
    }

    conditionsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-condition') || e.target.parentNode.classList.contains('remove-condition')) {
            const row = e.target.closest('.condition-row');
            row.remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const removeButtons = document.querySelectorAll('.remove-condition');
        removeButtons.forEach((btn, index) => {
            btn.disabled = removeButtons.length === 1;
        });
    }

    // Initial state
    updateRemoveButtons();
});
</script>
@endpush
