@extends('admin.layouts.app')

@section('content')
<div class="page">
    <!-- Start::app-content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h1 class="page-title fw-medium fs-18 mb-2">Edit Commission Setting</h1>
                    <div class="">
                        <nav>
                            <ol class="breadcrumb breadcrumb-example1 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.commission-settings.index') }}">Commission Settings</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="btn-list">
                    <a href="{{ route('admin.commission-settings.index') }}" class="btn btn-outline-primary">
                        <i class="ri-arrow-left-line me-2"></i>Back to List
                    </a>
                </div>
            </div>
            <!-- Page Header Close -->

            <!-- Start::row-1 -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">Edit Commission Setting: {{ $commissionSetting->display_name ?? $commissionSetting->name }}</div>
                            <div class="d-flex gap-2">
                                <span class="badge bg-{{ $commissionSetting->is_active ? 'success' : 'danger' }}">
                                    {{ $commissionSetting->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $commissionSetting->type)) }}</span>
                            </div>
                        </div>
                        <form action="{{ route('admin.commission-settings.update', $commissionSetting->id) }}" method="POST" id="commissionForm">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Success/Info Messages -->
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <!-- Commission Overview Card -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card border-primary">
                                            <div class="card-header bg-primary-transparent">
                                                <h6 class="mb-0 text-primary">Commission Overview</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="text-center p-3 border rounded">
                                                            <h5 class="text-primary mb-1">{{ $commissionSetting->value }}{{ $commissionSetting->calculation_type == 'percentage' ? '%' : ' ৳' }}</h5>
                                                            <small class="text-muted">Commission Value</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="text-center p-3 border rounded">
                                                            <h5 class="text-success mb-1">{{ $commissionSetting->max_levels ?? 1 }}</h5>
                                                            <small class="text-muted">Max Levels</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="text-center p-3 border rounded">
                                                            <h5 class="text-info mb-1">{{ $commissionSetting->priority ?? 0 }}</h5>
                                                            <small class="text-muted">Priority</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="text-center p-3 border rounded">
                                                            <h5 class="text-warning mb-1">{{ $commissionSetting->enable_multi_level ? 'Multi' : 'Single' }}</h5>
                                                            <small class="text-muted">Level Type</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Basic Information -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Basic Information</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="name" class="form-label">System Name <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                                   id="name" name="name" value="{{ old('name', $commissionSetting->name) }}" 
                                                                   placeholder="e.g., sponsor_commission_5_percent">
                                                            <small class="text-muted">Internal system name (lowercase, underscores only)</small>
                                                            @error('name')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="display_name" class="form-label">Display Name <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                                                   id="display_name" name="display_name" value="{{ old('display_name', $commissionSetting->display_name) }}" 
                                                                   placeholder="e.g., Sponsor Commission 5%">
                                                            @error('display_name')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label for="description" class="form-label">Description</label>
                                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                                      id="description" name="description" rows="2" 
                                                                      placeholder="Optional description for this commission setting">{{ old('description', $commissionSetting->description) }}</textarea>
                                                            @error('description')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" name="description" rows="2" 
                                                      placeholder="Optional description for this commission setting">{{ old('description', $commissionSetting->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Commission Configuration -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="type" class="form-label">Commission Type <span class="text-danger">*</span></label>
                                            <select class="form-select @error('type') is-invalid @enderror" 
                                                    id="type" name="type" onchange="handleTypeChange()">
                                                <option value="">Select Type</option>
                                                @foreach($types as $key => $value)
                                                    <option value="{{ $key }}" {{ old('type', $commissionSetting->type) == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="calculation_type" class="form-label">Calculation Type <span class="text-danger">*</span></label>
                                            <select class="form-select @error('calculation_type') is-invalid @enderror" 
                                                    id="calculation_type" name="calculation_type" onchange="handleCalculationChange()">
                                                <option value="">Select Calculation Type</option>
                                                @foreach($calculationTypes as $key => $value)
                                                    <option value="{{ $key }}" {{ old('calculation_type', $commissionSetting->calculation_type) == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('calculation_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="value" class="form-label">Commission Value <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('value') is-invalid @enderror" 
                                                   id="value" name="value" step="0.01" min="0" 
                                                   value="{{ old('value', $commissionSetting->value) }}" 
                                                   placeholder="Enter commission value">
                                            @error('value')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="min_qualification" class="form-label">Minimum Qualification</label>
                                            <input type="number" class="form-control @error('min_qualification') is-invalid @enderror" 
                                                   id="min_qualification" name="min_qualification" step="0.01" min="0" 
                                                   value="{{ old('min_qualification', $commissionSetting->min_qualification) }}" 
                                                   placeholder="0.00">
                                            @error('min_qualification')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="max_payout" class="form-label">Maximum Payout</label>
                                            <input type="number" class="form-control @error('max_payout') is-invalid @enderror" 
                                                   id="max_payout" name="max_payout" step="0.01" min="0" 
                                                   value="{{ old('max_payout', $commissionSetting->max_payout) }}" 
                                                   placeholder="Leave empty for unlimited">
                                            @error('max_payout')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="priority_edit" class="form-label">Priority</label>
                                            <input type="number" class="form-control" id="priority_edit" name="priority" 
                                                   min="0" value="{{ old('priority', $commissionSetting->priority ?? 0) }}">
                                            <div class="form-text">Higher numbers have higher priority</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="is_active" class="form-label">Status</label>
                                            <select class="form-select" id="is_active" name="is_active">
                                                <option value="1" {{ old('is_active', $commissionSetting->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ old('is_active', $commissionSetting->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $commissionSetting->description) }}</textarea>
                                            <div class="form-text">Optional description for this commission setting</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Multi-Level Configuration -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Commission Level Configuration</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="enable_multi_level" 
                                                           name="enable_multi_level" value="1"
                                                           {{ old('enable_multi_level', $commissionSetting->enable_multi_level ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold" for="enable_multi_level">
                                                        Enable Multi-Level Configuration
                                                    </label>
                                                </div>
                                                
                                                <!-- Explanation Section -->
                                                <div class="alert alert-info mb-3">
                                                    <h6 class="alert-heading mb-2">What does this mean?</h6>
                                                    <div id="single-level-explanation" style="display: {{ old('enable_multi_level', $commissionSetting->enable_multi_level ?? false) ? 'none' : 'block' }};">
                                                        <strong>Single Level (Current):</strong> One commission value applies to all levels.<br>
                                                        <em>Example: 5% commission for levels 1, 2, 3, 4, 5...</em>
                                                    </div>
                                                    <div id="multi-level-explanation" style="display: {{ old('enable_multi_level', $commissionSetting->enable_multi_level ?? false) ? 'block' : 'none' }};">
                                                        <strong>Multi-Level:</strong> Different commission values for each level.<br>
                                                        <em>Example: Level 1: 10%, Level 2: 7%, Level 3: 5%, Level 4: 3%</em>
                                                    </div>
                                                </div>
                                                
                                                <!-- Benefits Section -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="border rounded p-3 mb-3" id="single-benefits" style="display: {{ old('enable_multi_level', $commissionSetting->enable_multi_level ?? false) ? 'none' : 'block' }};">
                                                            <h6 class="text-success mb-2">✓ Single Level Benefits:</h6>
                                                            <ul class="mb-0 small">
                                                                <li>Simple setup and management</li>
                                                                <li>Easy to understand for users</li>
                                                                <li>Consistent rewards structure</li>
                                                                <li>Good for simple commission plans</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="border rounded p-3 mb-3" id="multi-benefits" style="display: {{ old('enable_multi_level', $commissionSetting->enable_multi_level ?? false) ? 'block' : 'none' }};">
                                                            <h6 class="text-primary mb-2">✓ Multi-Level Benefits:</h6>
                                                            <ul class="mb-0 small">
                                                                <li>Higher rewards for direct referrals</li>
                                                                <li>Encourages building deeper networks</li>
                                                                <li>Flexible commission structure</li>
                                                                <li>Better control over payout costs</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4" id="levels_configuration_row" style="display: {{ old('enable_multi_level', $commissionSetting->enable_multi_level ?? false) ? 'flex' : 'none' }};">
                                    <div class="col-md-6">
                                        <label for="max_levels" class="form-label">Maximum Levels <span class="text-danger">*</span></label>
                                        <select class="form-select @error('max_levels') is-invalid @enderror" 
                                                id="max_levels" name="max_levels" onchange="handleLevelsChange()">
                                            <option value="">Select Number of Levels</option>
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ old('max_levels', $commissionSetting->max_levels) == $i ? 'selected' : '' }}>
                                                    {{ $i }} Level{{ $i > 1 ? 's' : '' }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('max_levels')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Single Level Configuration (shown when multi-level is disabled) -->
                                <div class="row mb-4" id="single_level_configuration_row" style="display: {{ old('enable_multi_level', $commissionSetting->enable_multi_level ?? false) ? 'none' : 'flex' }};">
                                    <!-- Hidden field for max_levels when multi-level is disabled -->
                                    <input type="hidden" id="max_levels_hidden" name="max_levels" value="1">
                                </div>

                                <!-- Enhanced Multi-Level Configuration -->
                                <div class="row mt-4" id="multi_level_config_section" style="display: {{ old('enable_multi_level', $commissionSetting->enable_multi_level ?? false) ? 'block' : 'none' }};">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">Multi-Level Configuration</h6>
                                                <button type="button" class="btn btn-sm btn-primary" onclick="addLevel()">
                                                    <i class="ri-add-line me-1"></i>Add Level
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div id="levels-container">
                                                    <!-- Levels will be populated dynamically -->
                                                </div>
                                                <div class="text-muted">
                                                    <small>Multi-level configuration allows different commission values for different levels or conditions.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Type-specific conditions -->
                                <div id="typeSpecificConditions">
                                    <!-- Will be populated by JavaScript based on selected type -->
                                </div>

                                <!-- Dynamic Conditions Section -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5 class="mb-3">Commission Conditions</h5>
                                        
                                        <!-- Enhanced Matching Conditions -->
                                        <div id="matching-conditions" class="condition-section" style="display: {{ $commissionSetting->type == 'matching' ? 'block' : 'none' }};">
                                            <div class="mb-4">
                                                <h6 class="mb-3">Enhanced Matching Bonus Configuration</h6>
                                                
                                                <!-- Basic Matching Settings -->
                                                <div class="card mb-3">
                                                    <div class="card-header">
                                                        <h6 class="mb-0">Basic Matching Settings</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label for="matching_levels" class="form-label">Matching Levels</label>
                                                                <input type="number" min="1" max="10" class="form-control" 
                                                                       id="matching_levels" name="matching_levels" 
                                                                       value="{{ old('matching_levels', $commissionSetting->max_levels ?? 5) }}">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="matching_frequency" class="form-label">Matching Frequency</label>
                                                                <select class="form-select" id="matching_frequency" name="matching_frequency">
                                                                    <option value="daily" {{ old('matching_frequency', $commissionSetting->matching_frequency) == 'daily' ? 'selected' : '' }}>Daily</option>
                                                                    <option value="hourly" {{ old('matching_frequency', $commissionSetting->matching_frequency) == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                                                    <option value="real_time" {{ old('matching_frequency', $commissionSetting->matching_frequency) == 'real_time' ? 'selected' : '' }}>Real Time</option>
                                                                    <option value="weekly" {{ old('matching_frequency', $commissionSetting->matching_frequency) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="matching_time" class="form-label">Matching Time</label>
                                                                <input type="time" class="form-control" id="matching_time" name="matching_time" 
                                                                       value="{{ old('matching_time', $commissionSetting->matching_time ? \Carbon\Carbon::parse($commissionSetting->matching_time)->format('H:i') : '23:59') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Carry Forward Settings -->
                                                <div class="card mb-3">
                                                    <div class="card-header">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="carry_forward_enabled" 
                                                                   name="carry_forward_enabled" value="1" 
                                                                   {{ old('carry_forward_enabled', $commissionSetting->carry_forward_enabled) ? 'checked' : '' }}
                                                                   onchange="toggleCarryForward()">
                                                            <label class="form-check-label" for="carry_forward_enabled">
                                                                <h6 class="mb-0">Enable Carry Forward</h6>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="card-body" id="carry_forward_settings" style="display: {{ $commissionSetting->carry_forward_enabled ? 'block' : 'none' }};">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label for="carry_side" class="form-label">Carry Side</label>
                                                                <select class="form-select" id="carry_side" name="carry_side">
                                                                    <option value="strong" {{ old('carry_side', $commissionSetting->carry_side) == 'strong' ? 'selected' : '' }}>Strong Side</option>
                                                                    <option value="weak" {{ old('carry_side', $commissionSetting->carry_side) == 'weak' ? 'selected' : '' }}>Weak Side</option>
                                                                    <option value="both" {{ old('carry_side', $commissionSetting->carry_side) == 'both' ? 'selected' : '' }}>Both Sides</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="carry_percentage" class="form-label">Carry Percentage (%)</label>
                                                                <input type="number" min="0" max="100" step="0.01" class="form-control" 
                                                                       id="carry_percentage" name="carry_percentage" 
                                                                       value="{{ old('carry_percentage', $commissionSetting->carry_percentage) }}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="carry_max_days" class="form-label">Max Carry Days</label>
                                                                <input type="number" min="1" max="365" class="form-control" 
                                                                       id="carry_max_days" name="carry_max_days" 
                                                                       value="{{ old('carry_max_days', $commissionSetting->carry_max_days) }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Slot Matching Settings -->
                                                <div class="card mb-3">
                                                    <div class="card-header">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="slot_matching_enabled" 
                                                                   name="slot_matching_enabled" value="1" 
                                                                   {{ old('slot_matching_enabled', $commissionSetting->slot_matching_enabled) ? 'checked' : '' }}
                                                                   onchange="toggleSlotMatching()">
                                                            <label class="form-check-label" for="slot_matching_enabled">
                                                                <h6 class="mb-0">Enable Slot Matching</h6>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="card-body" id="slot_matching_settings" style="display: {{ $commissionSetting->slot_matching_enabled ? 'block' : 'none' }};">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label for="slot_size" class="form-label">Slot Size</label>
                                                                <input type="number" min="1" class="form-control" 
                                                                       id="slot_size" name="slot_size" 
                                                                       value="{{ old('slot_size', $commissionSetting->slot_size) }}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="slot_type" class="form-label">Slot Type</label>
                                                                <select class="form-select" id="slot_type" name="slot_type">
                                                                    <option value="volume" {{ old('slot_type', $commissionSetting->slot_type) == 'volume' ? 'selected' : '' }}>Volume Based</option>
                                                                    <option value="count" {{ old('slot_type', $commissionSetting->slot_type) == 'count' ? 'selected' : '' }}>Count Based</option>
                                                                    <option value="mixed" {{ old('slot_type', $commissionSetting->slot_type) == 'mixed' ? 'selected' : '' }}>Mixed</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="min_slot_volume" class="form-label">Min Slot Volume</label>
                                                                <input type="number" min="0" step="0.01" class="form-control" 
                                                                       id="min_slot_volume" name="min_slot_volume" 
                                                                       value="{{ old('min_slot_volume', $commissionSetting->min_slot_volume) }}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="min_slot_count" class="form-label">Min Slot Count</label>
                                                                <input type="number" min="1" class="form-control" 
                                                                       id="min_slot_count" name="min_slot_count" 
                                                                       value="{{ old('min_slot_count', $commissionSetting->min_slot_count) }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Advanced Rules -->
                                                <div class="card mb-3">
                                                    <div class="card-header">
                                                        <h6 class="mb-0">Advanced Matching Rules</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-check mb-3">
                                                                    <input class="form-check-input" type="checkbox" id="auto_balance_enabled" 
                                                                           name="auto_balance_enabled" value="1" 
                                                                           {{ old('auto_balance_enabled', $commissionSetting->auto_balance_enabled) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="auto_balance_enabled">
                                                                        Enable Auto Balance
                                                                    </label>
                                                                </div>
                                                                <div class="form-check mb-3">
                                                                    <input class="form-check-input" type="checkbox" id="spillover_enabled" 
                                                                           name="spillover_enabled" value="1" 
                                                                           {{ old('spillover_enabled', $commissionSetting->spillover_enabled) ? 'checked' : '' }}
                                                                           onchange="toggleSpillover()">
                                                                    <label class="form-check-label" for="spillover_enabled">
                                                                        Enable Spillover
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="balance_ratio" class="form-label">Balance Ratio</label>
                                                                    <input type="number" min="0.1" max="10" step="0.1" class="form-control" 
                                                                           id="balance_ratio" name="balance_ratio" 
                                                                           value="{{ old('balance_ratio', $commissionSetting->balance_ratio ?? 1.0) }}">
                                                                </div>
                                                                <div class="mb-3" id="spillover_direction_group" style="display: {{ $commissionSetting->spillover_enabled ? 'block' : 'none' }};">
                                                                    <label for="spillover_direction" class="form-label">Spillover Direction</label>
                                                                    <select class="form-select" id="spillover_direction" name="spillover_direction">
                                                                        <option value="weaker" {{ old('spillover_direction', $commissionSetting->spillover_direction) == 'weaker' ? 'selected' : '' }}>To Weaker Side</option>
                                                                        <option value="stronger" {{ old('spillover_direction', $commissionSetting->spillover_direction) == 'stronger' ? 'selected' : '' }}>To Stronger Side</option>
                                                                        <option value="alternate" {{ old('spillover_direction', $commissionSetting->spillover_direction) == 'alternate' ? 'selected' : '' }}>Alternate Sides</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Flush & Capping -->
                                                <div class="card mb-3">
                                                    <div class="card-header">
                                                        <h6 class="mb-0">Flush & Capping Settings</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-check mb-3">
                                                                    <input class="form-check-input" type="checkbox" id="flush_enabled" 
                                                                           name="flush_enabled" value="1" 
                                                                           {{ old('flush_enabled', $commissionSetting->flush_enabled) ? 'checked' : '' }}
                                                                           onchange="toggleFlush()">
                                                                    <label class="form-check-label" for="flush_enabled">
                                                                        Enable Flush
                                                                    </label>
                                                                </div>
                                                                <div id="flush_percentage_group" style="display: {{ $commissionSetting->flush_enabled ? 'block' : 'none' }};">
                                                                    <label for="flush_percentage" class="form-label">Flush Percentage (%)</label>
                                                                    <input type="number" min="0" max="100" step="0.01" class="form-control" 
                                                                           id="flush_percentage" name="flush_percentage" 
                                                                           value="{{ old('flush_percentage', $commissionSetting->flush_percentage) }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-check mb-3">
                                                                    <input class="form-check-input" type="checkbox" id="daily_cap_enabled" 
                                                                           name="daily_cap_enabled" value="1" 
                                                                           {{ old('daily_cap_enabled', $commissionSetting->daily_cap_enabled) ? 'checked' : '' }}
                                                                           onchange="toggleDailyCap()">
                                                                    <label class="form-check-label" for="daily_cap_enabled">
                                                                        Enable Daily Cap
                                                                    </label>
                                                                </div>
                                                                <div id="daily_cap_amount_group" style="display: {{ $commissionSetting->daily_cap_enabled ? 'block' : 'none' }};">
                                                                    <label for="daily_cap_amount" class="form-label">Daily Cap Amount (৳)</label>
                                                                    <input type="number" min="0" step="0.01" class="form-control" 
                                                                           id="daily_cap_amount" name="daily_cap_amount" 
                                                                           value="{{ old('daily_cap_amount', $commissionSetting->daily_cap_amount) }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-check mb-3">
                                                                    <input class="form-check-input" type="checkbox" id="weekly_cap_enabled" 
                                                                           name="weekly_cap_enabled" value="1" 
                                                                           {{ old('weekly_cap_enabled', $commissionSetting->weekly_cap_enabled) ? 'checked' : '' }}
                                                                           onchange="toggleWeeklyCap()">
                                                                    <label class="form-check-label" for="weekly_cap_enabled">
                                                                        Enable Weekly Cap
                                                                    </label>
                                                                </div>
                                                                <div id="weekly_cap_amount_group" style="display: {{ $commissionSetting->weekly_cap_enabled ? 'block' : 'none' }};">
                                                                    <label for="weekly_cap_amount" class="form-label">Weekly Cap Amount (৳)</label>
                                                                    <input type="number" min="0" step="0.01" class="form-control" 
                                                                           id="weekly_cap_amount" name="weekly_cap_amount" 
                                                                           value="{{ old('weekly_cap_amount', $commissionSetting->weekly_cap_amount) }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Qualification Requirements -->
                                                <div class="card mb-3">
                                                    <div class="card-header">
                                                        <h6 class="mb-0">Qualification Requirements</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-check mb-3">
                                                                    <input class="form-check-input" type="checkbox" id="personal_volume_required" 
                                                                           name="personal_volume_required" value="1" 
                                                                           {{ old('personal_volume_required', $commissionSetting->personal_volume_required) ? 'checked' : '' }}
                                                                           onchange="togglePersonalVolume()">
                                                                    <label class="form-check-label" for="personal_volume_required">
                                                                        Personal Volume Required
                                                                    </label>
                                                                </div>
                                                                <div id="personal_volume_group" style="display: {{ $commissionSetting->personal_volume_required ? 'block' : 'none' }};">
                                                                    <label for="min_personal_volume" class="form-label">Min Personal Volume (৳)</label>
                                                                    <input type="number" min="0" step="0.01" class="form-control" 
                                                                           id="min_personal_volume" name="min_personal_volume" 
                                                                           value="{{ old('min_personal_volume', $commissionSetting->min_personal_volume) }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-check mb-3">
                                                                    <input class="form-check-input" type="checkbox" id="both_legs_required" 
                                                                           name="both_legs_required" value="1" 
                                                                           {{ old('both_legs_required', $commissionSetting->both_legs_required ?? true) ? 'checked' : '' }}
                                                                           onchange="toggleBothLegs()">
                                                                    <label class="form-check-label" for="both_legs_required">
                                                                        Both Legs Required
                                                                    </label>
                                                                </div>
                                                                <div id="both_legs_group" style="display: {{ $commissionSetting->both_legs_required ?? true ? 'block' : 'none' }};">
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <label for="min_left_volume" class="form-label">Min Left Volume (৳)</label>
                                                                            <input type="number" min="0" step="0.01" class="form-control" 
                                                                                   id="min_left_volume" name="min_left_volume" 
                                                                                   value="{{ old('min_left_volume', $commissionSetting->min_left_volume) }}">
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <label for="min_right_volume" class="form-label">Min Right Volume (৳)</label>
                                                                            <input type="number" min="0" step="0.01" class="form-control" 
                                                                                   id="min_right_volume" name="min_right_volume" 
                                                                                   value="{{ old('min_right_volume', $commissionSetting->min_right_volume) }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Generation Conditions -->
                                        <div id="generation-conditions" class="condition-section" style="display: {{ $commissionSetting->type == 'generation' ? 'block' : 'none' }};">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6 class="mb-0">Generation Bonus Configuration</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Generation Depth</label>
                                                                <input type="number" class="form-control" name="conditions[generation_depth]" 
                                                                       min="1" max="20" value="{{ old('conditions.generation_depth', $commissionSetting->conditions['generation_depth'] ?? '') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Minimum Personal Sales</label>
                                                                <input type="number" class="form-control" name="conditions[min_personal_sales]" 
                                                                       min="0" step="0.01" value="{{ old('conditions.min_personal_sales', $commissionSetting->conditions['min_personal_sales'] ?? '') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Rank Conditions -->
                                        <div id="rank-conditions" class="condition-section" style="display: {{ $commissionSetting->type == 'rank' ? 'block' : 'none' }};">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6 class="mb-0">Rank Bonus Configuration</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Required Rank</label>
                                                                <select class="form-select" name="conditions[required_rank]">
                                                                    <option value="">Select Rank</option>
                                                                    <option value="bronze" {{ old('conditions.required_rank', $commissionSetting->conditions['required_rank'] ?? '') == 'bronze' ? 'selected' : '' }}>Bronze</option>
                                                                    <option value="silver" {{ old('conditions.required_rank', $commissionSetting->conditions['required_rank'] ?? '') == 'silver' ? 'selected' : '' }}>Silver</option>
                                                                    <option value="gold" {{ old('conditions.required_rank', $commissionSetting->conditions['required_rank'] ?? '') == 'gold' ? 'selected' : '' }}>Gold</option>
                                                                    <option value="platinum" {{ old('conditions.required_rank', $commissionSetting->conditions['required_rank'] ?? '') == 'platinum' ? 'selected' : '' }}>Platinum</option>
                                                                    <option value="diamond" {{ old('conditions.required_rank', $commissionSetting->conditions['required_rank'] ?? '') == 'diamond' ? 'selected' : '' }}>Diamond</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Target Achievement (%)</label>
                                                                <input type="number" class="form-control" name="conditions[target_percentage]" 
                                                                       min="0" max="100" value="{{ old('conditions.target_percentage', $commissionSetting->conditions['target_percentage'] ?? '') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Club Conditions -->
                                        <div id="club-conditions" class="condition-section" style="display: {{ $commissionSetting->type == 'club' ? 'block' : 'none' }};">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6 class="mb-0">Club Bonus Configuration</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Club Level</label>
                                                                <select class="form-select" name="conditions[club_level]">
                                                                    <option value="">Select Club Level</option>
                                                                    <option value="basic" {{ old('conditions.club_level', $commissionSetting->conditions['club_level'] ?? '') == 'basic' ? 'selected' : '' }}>Basic Club</option>
                                                                    <option value="premium" {{ old('conditions.club_level', $commissionSetting->conditions['club_level'] ?? '') == 'premium' ? 'selected' : '' }}>Premium Club</option>
                                                                    <option value="elite" {{ old('conditions.club_level', $commissionSetting->conditions['club_level'] ?? '') == 'elite' ? 'selected' : '' }}>Elite Club</option>
                                                                    <option value="vip" {{ old('conditions.club_level', $commissionSetting->conditions['club_level'] ?? '') == 'vip' ? 'selected' : '' }}>VIP Club</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Required Members</label>
                                                                <input type="number" class="form-control" name="conditions[required_members]" 
                                                                       min="1" value="{{ old('conditions.required_members', $commissionSetting->conditions['required_members'] ?? '') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Binary Conditions -->
                                        <div id="binary-conditions" class="condition-section" style="display: {{ $commissionSetting->type == 'binary' ? 'block' : 'none' }};">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6 class="mb-0">Binary Bonus Configuration</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Minimum Left Volume</label>
                                                                <input type="number" class="form-control" name="conditions[min_left_volume]" 
                                                                       min="0" step="0.01" value="{{ old('conditions.min_left_volume', $commissionSetting->conditions['min_left_volume'] ?? '') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Minimum Right Volume</label>
                                                                <input type="number" class="form-control" name="conditions[min_right_volume]" 
                                                                       min="0" step="0.01" value="{{ old('conditions.min_right_volume', $commissionSetting->conditions['min_right_volume'] ?? '') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Flush Percentage (%)</label>
                                                                <input type="number" class="form-control" name="conditions[flush_percentage]" 
                                                                       min="0" max="100" value="{{ old('conditions.flush_percentage', $commissionSetting->conditions['flush_percentage'] ?? '') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Cap Amount</label>
                                                                <input type="number" class="form-control" name="conditions[cap_amount]" 
                                                                       min="0" step="0.01" value="{{ old('conditions.cap_amount', $commissionSetting->conditions['cap_amount'] ?? '') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Leadership Conditions -->
                                        <div id="leadership-conditions" class="condition-section" style="display: {{ $commissionSetting->type == 'leadership' ? 'block' : 'none' }};">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6 class="mb-0">Leadership Bonus Configuration</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Minimum Team Size</label>
                                                                <input type="number" class="form-control" name="conditions[min_team_size]" 
                                                                       min="1" value="{{ old('conditions.min_team_size', $commissionSetting->conditions['min_team_size'] ?? '') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Minimum Team Volume</label>
                                                                <input type="number" class="form-control" name="conditions[min_team_volume]" 
                                                                       min="0" step="0.01" value="{{ old('conditions.min_team_volume', $commissionSetting->conditions['min_team_volume'] ?? '') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sponsor Conditions -->
                                        <div id="sponsor-conditions" class="condition-section" style="display: {{ $commissionSetting->type == 'sponsor' ? 'block' : 'none' }};">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6 class="mb-0">Sponsor Commission Configuration</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Minimum Sales Volume</label>
                                                                <input type="number" class="form-control" name="conditions[min_sales_volume]" 
                                                                       min="0" step="0.01" value="{{ old('conditions.min_sales_volume', $commissionSetting->conditions['min_sales_volume'] ?? '') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Qualification Period (days)</label>
                                                                <input type="number" class="form-control" name="conditions[qualification_period]" 
                                                                       min="1" value="{{ old('conditions.qualification_period', $commissionSetting->conditions['qualification_period'] ?? '') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Multi-Level Configuration -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">Multi-Level Configuration</h6>
                                                <button type="button" class="btn btn-sm btn-primary" onclick="addLevel()">
                                                    <i class="ri-add-line me-1"></i>Add Level
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div id="levels-container">
                                                    @if(old('levels') || (isset($commissionSetting->levels) && count($commissionSetting->levels) > 0))
                                                        @foreach(old('levels', $commissionSetting->levels ?? []) as $index => $level)
                                                            <div class="level-item border rounded p-3 mb-3">
                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                    <h6 class="mb-0">Level {{ $index + 1 }}</h6>
                                                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeLevel(this)">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Level Number</label>
                                                                            <input type="number" class="form-control" name="levels[{{ $index }}][level]" 
                                                                                   value="{{ $level['level'] ?? ($index + 1) }}" min="1" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Value</label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-text level-prefix">{{ $commissionSetting->calculation_type == 'percentage' ? '%' : '৳' }}</span>
                                                                                <input type="number" class="form-control" name="levels[{{ $index }}][value]" 
                                                                                       value="{{ $level['value'] ?? '' }}" step="0.01" min="0" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Condition</label>
                                                                            <input type="text" class="form-control" name="levels[{{ $index }}][condition]" 
                                                                                   value="{{ $level['condition'] ?? '' }}" placeholder="e.g., min_volume:1000">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <div class="text-muted">
                                                    <small>Multi-level configuration allows different commission values for different levels or conditions.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-1"></i>Update Commission Setting
                                    </button>
                                    <a href="{{ route('admin.commission-settings.index') }}" class="btn btn-secondary">
                                        <i class="ri-close-line me-1"></i>Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End::row-1 -->
        </div>
    </div>
    <!-- End::app-content -->
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const calculationTypeSelect = document.getElementById('calculation_type');
    const valuePrefix = document.getElementById('value-prefix');
    
    // Update conditions visibility based on type
    typeSelect.addEventListener('change', function() {
        showConditionsForType(this.value);
    });
    
    // Update value prefix based on calculation type
    calculationTypeSelect.addEventListener('change', function() {
        const prefix = this.value === 'percentage' ? '%' : '৳';
        valuePrefix.textContent = prefix;
        
        // Update all level prefixes
        document.querySelectorAll('.level-prefix').forEach(function(element) {
            element.textContent = prefix;
        });
    });
    
    function showConditionsForType(type) {
        // Hide all condition sections
        document.querySelectorAll('.condition-section').forEach(function(section) {
            section.style.display = 'none';
        });
        
        // Show relevant condition section
        if (type) {
            const conditionSection = document.getElementById(type + '-conditions');
            if (conditionSection) {
                conditionSection.style.display = 'block';
            }
        }
    }
});

let levelIndex = {{ count(old('levels', $commissionSetting->levels ?? [])) }};

function addLevel() {
    const calculationType = document.getElementById('calculation_type').value;
    const prefix = calculationType === 'percentage' ? '%' : '৳';
    
    const levelHtml = `
        <div class="level-item border rounded p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Level ${levelIndex + 1}</h6>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeLevel(this)">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Level Number</label>
                        <input type="number" class="form-control" name="levels[${levelIndex}][level]" 
                               value="${levelIndex + 1}" min="1" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Value</label>
                        <div class="input-group">
                            <span class="input-group-text level-prefix">${prefix}</span>
                            <input type="number" class="form-control" name="levels[${levelIndex}][value]" 
                                   step="0.01" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Condition</label>
                        <input type="text" class="form-control" name="levels[${levelIndex}][condition]" 
                               placeholder="e.g., min_volume:1000">
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('levels-container').insertAdjacentHTML('beforeend', levelHtml);
    levelIndex++;
}

function removeLevel(button) {
    button.closest('.level-item').remove();
    
    // Update level numbers
    document.querySelectorAll('.level-item').forEach(function(item, index) {
        const levelTitle = item.querySelector('h6');
        levelTitle.textContent = `Level ${index + 1}`;
        
        const levelInput = item.querySelector('input[name*="[level]"]');
        if (levelInput) {
            levelInput.value = index + 1;
        }
    });
}

// Enhanced Matching Toggle Functions
function toggleCarryForward() {
    const checkbox = document.getElementById('carry_forward_enabled');
    const settings = document.getElementById('carry_forward_settings');
    if (settings) {
        settings.style.display = checkbox.checked ? 'block' : 'none';
    }
}

function toggleSlotMatching() {
    const checkbox = document.getElementById('slot_matching_enabled');
    const settings = document.getElementById('slot_matching_settings');
    if (settings) {
        settings.style.display = checkbox.checked ? 'block' : 'none';
    }
}

function toggleSpillover() {
    const checkbox = document.getElementById('spillover_enabled');
    const group = document.getElementById('spillover_direction_group');
    if (group) {
        group.style.display = checkbox.checked ? 'block' : 'none';
    }
}

function toggleFlush() {
    const checkbox = document.getElementById('flush_enabled');
    const group = document.getElementById('flush_percentage_group');
    if (group) {
        group.style.display = checkbox.checked ? 'block' : 'none';
    }
}

function toggleDailyCap() {
    const checkbox = document.getElementById('daily_cap_enabled');
    const group = document.getElementById('daily_cap_amount_group');
    if (group) {
        group.style.display = checkbox.checked ? 'block' : 'none';
    }
}

function toggleWeeklyCap() {
    const checkbox = document.getElementById('weekly_cap_enabled');
    const group = document.getElementById('weekly_cap_amount_group');
    if (group) {
        group.style.display = checkbox.checked ? 'block' : 'none';
    }
}

function togglePersonalVolume() {
    const checkbox = document.getElementById('personal_volume_required');
    const group = document.getElementById('personal_volume_group');
    if (group) {
        group.style.display = checkbox.checked ? 'block' : 'none';
    }
}

function handleTypeChange() {
    const type = document.getElementById('type').value;
    
    // Handle condition sections visibility
    const matchingConditions = document.getElementById('matching-conditions');
    
    if (matchingConditions) {
        if (type === 'matching') {
            matchingConditions.style.display = 'block';
            // Enable all matching fields
            enableMatchingFields(true);
        } else {
            matchingConditions.style.display = 'none';
            // Disable all matching fields
            enableMatchingFields(false);
        }
    }
    
    // You can add other type-specific conditions here as needed
    // For example: generation, binary, rank conditions
}

function enableMatchingFields(enable) {
    // List of matching-specific field names
    const matchingFields = [
        'matching_time', 'matching_frequency', 'carry_forward_enabled', 'carry_side',
        'carry_percentage', 'carry_max_days', 'slot_matching_enabled', 'slot_size',
        'slot_type', 'min_slot_volume', 'min_slot_count', 'auto_balance_enabled',
        'balance_ratio', 'spillover_enabled', 'spillover_direction', 'flush_enabled',
        'flush_percentage', 'daily_cap_enabled', 'daily_cap_amount', 'weekly_cap_enabled',
        'weekly_cap_amount', 'personal_volume_required', 'min_personal_volume',
        'both_legs_required', 'min_left_volume', 'min_right_volume'
    ];
    
    matchingFields.forEach(function(fieldName) {
        const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
        if (field) {
            field.disabled = !enable;
            if (!enable) {
                // Clear the value when disabled
                if (field.type === 'checkbox') {
                    field.checked = false;
                } else {
                    field.value = '';
                }
            }
        }
    });
}

function handleCalculationChange() {
    // Implementation for calculation type changes if needed
}

function handleLevelsChange() {
    const maxLevels = parseInt(document.getElementById('max_levels').value) || 1;
    const levelsContainer = document.getElementById('levels-container');
    
    if (levelsContainer) {
        // Clear existing levels
        levelsContainer.innerHTML = '';
        
        // Add level configuration based on selected max levels
        for (let i = 1; i <= maxLevels; i++) {
            addLevelConfiguration(i, levelsContainer);
        }
    }
}

function addLevelConfiguration(level, container) {
    // Add level configuration UI
    // This would be implemented based on your level configuration needs
}

function addLevel() {
    const levelsContainer = document.getElementById('levels-container');
    const currentLevels = levelsContainer.querySelectorAll('.level-item').length;
    const newLevel = currentLevels + 1;
    
    if (newLevel <= 10) {
        addLevelConfiguration(newLevel, levelsContainer);
    }
}

function toggleBothLegs() {
    const checkbox = document.getElementById('both_legs_required');
    const group = document.getElementById('both_legs_group');
    if (group) {
        group.style.display = checkbox.checked ? 'block' : 'none';
    }
}

function toggleMultiLevel() {
    const checkbox = document.getElementById('enable_multi_level');
    const levelsConfigRow = document.getElementById('levels_configuration_row');
    const singleLevelRow = document.getElementById('single_level_configuration_row');
    const multiLevelSection = document.getElementById('multi_level_config_section');
    const maxLevelsHidden = document.getElementById('max_levels_hidden');
    const maxLevelsSelect = document.getElementById('max_levels');
    
    // Toggle explanations
    const singleExplanation = document.getElementById('single-level-explanation');
    const multiExplanation = document.getElementById('multi-level-explanation');
    const singleBenefits = document.getElementById('single-benefits');
    const multiBenefits = document.getElementById('multi-benefits');
    
    if (checkbox.checked) {
        // Show multi-level configuration
        levelsConfigRow.style.display = 'flex';
        singleLevelRow.style.display = 'none';
        multiLevelSection.style.display = 'block';
        if (maxLevelsHidden) maxLevelsHidden.disabled = true; // Disable hidden field
        if (maxLevelsSelect) maxLevelsSelect.disabled = false; // Enable select field
        
        // Toggle explanations
        if (singleExplanation) singleExplanation.style.display = 'none';
        if (multiExplanation) multiExplanation.style.display = 'block';
        if (singleBenefits) singleBenefits.style.display = 'none';
        if (multiBenefits) multiBenefits.style.display = 'block';
    } else {
        // Show single-level configuration
        levelsConfigRow.style.display = 'none';
        singleLevelRow.style.display = 'flex';
        multiLevelSection.style.display = 'none';
        if (maxLevelsHidden) maxLevelsHidden.disabled = false; // Enable hidden field
        if (maxLevelsSelect) {
            maxLevelsSelect.disabled = true; // Disable select field
            maxLevelsSelect.value = ''; // Clear selection
        }
        // Clear the levels container
        const levelsContainer = document.getElementById('levels-container');
        if (levelsContainer) levelsContainer.innerHTML = '';
        
        // Toggle explanations
        if (singleExplanation) singleExplanation.style.display = 'block';
        if (multiExplanation) multiExplanation.style.display = 'none';
        if (singleBenefits) singleBenefits.style.display = 'block';
        if (multiBenefits) multiBenefits.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    handleTypeChange();
    handleCalculationChange();
    handleLevelsChange();
    toggleMultiLevel(); // Initialize multi-level toggle state
    
    // Add event listener for multi-level checkbox
    const multiLevelCheckbox = document.getElementById('enable_multi_level');
    if (multiLevelCheckbox) {
        multiLevelCheckbox.addEventListener('change', toggleMultiLevel);
    }
});
</script>
@endsection
