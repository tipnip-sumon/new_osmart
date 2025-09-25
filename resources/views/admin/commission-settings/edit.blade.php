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

                                <!-- Dual Calculation System Info -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card border-info">
                                            <div class="card-header bg-info-transparent">
                                                <h6 class="mb-0 text-info">💡 Dual Calculation System</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6 class="text-primary">Volume-Based System (₹)</h6>
                                                        <ul class="list-unstyled mb-0 small">
                                                            <li><i class="bx bx-check-circle text-success me-1"></i>Traditional MLM calculations</li>
                                                            <li><i class="bx bx-check-circle text-success me-1"></i>Based on Taka amounts</li>
                                                            <li><i class="bx bx-check-circle text-success me-1"></i>Higher thresholds (e.g., ৳1000)</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6 class="text-warning">Point-Based System</h6>
                                                        <ul class="list-unstyled mb-0 small">
                                                            <li><i class="bx bx-coin text-warning me-1"></i><strong>1 Point = 6 Taka</strong></li>
                                                            <li><i class="bx bx-check-circle text-success me-1"></i>Modern point-based approach</li>
                                                            <li><i class="bx bx-check-circle text-success me-1"></i>Lower thresholds (e.g., 100 points)</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="alert alert-light mt-3 mb-0">
                                                    <small><strong>💡 Tip:</strong> You can use different calculation bases for different requirements (e.g., point-based qualification with volume-based leg requirements)</small>
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

                                <!-- Commission Configuration -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Commission Configuration</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="type" class="form-label">Commission Type <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('type') is-invalid @enderror" 
                                                                    id="type" name="type">
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
                                                                    id="calculation_type" name="calculation_type">
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
                                                            <div class="input-group">
                                                                <input type="number" class="form-control @error('value') is-invalid @enderror" 
                                                                       id="value" name="value" step="0.01" min="0" 
                                                                       value="{{ old('value', $commissionSetting->value) }}" 
                                                                       placeholder="Enter commission value">
                                                                <span class="input-group-text" id="value-unit">%</span>
                                                            </div>
                                                            @error('value')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="qualification_basis" class="form-label">Qualification Calculation</label>
                                                            <select class="form-select @error('qualification_basis') is-invalid @enderror" 
                                                                    id="qualification_basis" name="qualification_basis" onchange="updateQualificationLabel()">
                                                                <option value="volume" {{ old('qualification_basis', $commissionSetting->qualification_basis ?? 'volume') == 'volume' ? 'selected' : '' }}>Volume (৳)</option>
                                                                <option value="points" {{ old('qualification_basis', $commissionSetting->qualification_basis ?? 'volume') == 'points' ? 'selected' : '' }}>Points</option>
                                                            </select>
                                                            @error('qualification_basis')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="min_qualification" class="form-label">
                                                                <span id="qualification_label">Minimum Qualification ({{ old('qualification_basis', $commissionSetting->qualification_basis ?? 'volume') == 'points' ? 'Points' : '৳' }})</span>
                                                            </label>
                                                            <input type="number" class="form-control @error('min_qualification') is-invalid @enderror" 
                                                                   id="min_qualification" name="min_qualification" step="0.01" min="0" 
                                                                   value="{{ old('min_qualification', $commissionSetting->min_qualification) }}" 
                                                                   placeholder="{{ old('qualification_basis', $commissionSetting->qualification_basis ?? 'volume') == 'points' ? 'e.g., 100 points' : 'e.g., 1000.00' }}">
                                                            <div class="form-text">
                                                                <span id="qualification_help">
                                                                    {{ old('qualification_basis', $commissionSetting->qualification_basis ?? 'volume') == 'points' ? 'Points needed for qualification (1 Point = 6৳)' : 'Volume amount needed for qualification' }}
                                                                </span>
                                                            </div>
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
                                                            <label for="priority" class="form-label">Priority</label>
                                                            <input type="number" class="form-control" id="priority" name="priority" 
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Multi-Level Configuration -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="card border-warning">
                                            <div class="card-header bg-warning-transparent">
                                                <h6 class="mb-0 text-warning">🏆 Multi-Level Matching Bonus Configuration</h6>
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
                                                    <h6 class="alert-heading mb-2">💡 What does this mean?</h6>
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

                                                <!-- Quick Setup Button -->
                                                <div class="row" id="quick-setup-section" style="display: {{ old('enable_multi_level', $commissionSetting->enable_multi_level ?? false) ? 'flex' : 'none' }};">
                                                    <div class="col-12">
                                                        <div class="alert alert-warning">
                                                            <h6 class="alert-heading">🚀 Quick Setup Available!</h6>
                                                            <p class="mb-2">Use our recommended structure for instant configuration:</p>
                                                            <button type="button" class="btn btn-warning btn-sm" onclick="quickSetupMultiLevel()">
                                                                <i class="ri-magic-line me-1"></i>Quick Setup: Level 1: 10%, Level 2: 7%, Level 3: 5%, Level 4: 3%
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Level Configuration -->
                                <div class="row mb-4" id="levels_configuration_row" style="display: {{ old('enable_multi_level', $commissionSetting->enable_multi_level ?? false) ? 'flex' : 'none' }};">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Level Settings</h6>
                                            </div>
                                            <div class="card-body">
                                                <label for="max_levels" class="form-label">Maximum Levels <span class="text-danger">*</span></label>
                                                <select class="form-select @error('max_levels') is-invalid @enderror" 
                                                        id="max_levels" name="max_levels">
                                                    <option value="">Select Number of Levels</option>
                                                    @for($i = 1; $i <= 20; $i++)
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
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Recommended Structure</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Level</th>
                                                                <th>Recommended %</th>
                                                                <th>Example Earning</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Level 1</td>
                                                                <td><span class="badge bg-success">10%</span></td>
                                                                <td>৳100 on ৳1000</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Level 2</td>
                                                                <td><span class="badge bg-info">7%</span></td>
                                                                <td>৳70 on ৳1000</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Level 3</td>
                                                                <td><span class="badge bg-warning">5%</span></td>
                                                                <td>৳50 on ৳1000</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Level 4</td>
                                                                <td><span class="badge bg-secondary">3%</span></td>
                                                                <td>৳30 on ৳1000</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
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
                                        <div class="card border-success">
                                            <div class="card-header d-flex justify-content-between align-items-center bg-success-transparent">
                                                <h6 class="mb-0 text-success">🎯 Level-wise Commission Configuration</h6>
                                                <button type="button" class="btn btn-sm btn-success" onclick="addLevel()">
                                                    <i class="ri-add-line me-1"></i>Add Level
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div id="levels-container">
                                                    <!-- Levels will be populated dynamically -->
                                                </div>
                                                
                                                <div class="text-muted mt-2">
                                                    <small><i class="ri-information-line me-1"></i>Multi-level configuration allows different commission values for different levels or conditions.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Enhanced Matching Conditions -->
                                @if($commissionSetting->type == 'matching')
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="card border-primary">
                                            <div class="card-header bg-primary-transparent">
                                                <h6 class="mb-0 text-primary">⚡ Enhanced Matching Bonus Configuration</h6>
                                            </div>
                                            <div class="card-body">
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
                                                                   {{ old('carry_forward_enabled', $commissionSetting->carry_forward_enabled) ? 'checked' : '' }}>
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
                                                                           {{ old('personal_volume_required', $commissionSetting->personal_volume_required) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="personal_volume_required">
                                                                        Personal Volume Required
                                                                    </label>
                                                                </div>
                                                                <div id="personal_volume_group" style="display: {{ $commissionSetting->personal_volume_required ? 'block' : 'none' }};">
                                                                    <div class="mb-2">
                                                                        <label for="personal_volume_basis" class="form-label">Personal Volume Calculation</label>
                                                                        <select class="form-select" id="personal_volume_basis" name="personal_volume_basis" onchange="updatePersonalVolumeLabel()">
                                                                            <option value="volume" {{ old('personal_volume_basis', $commissionSetting->personal_volume_basis ?? 'volume') == 'volume' ? 'selected' : '' }}>Volume (৳)</option>
                                                                            <option value="points" {{ old('personal_volume_basis', $commissionSetting->personal_volume_basis ?? 'volume') == 'points' ? 'selected' : '' }}>Points</option>
                                                                        </select>
                                                                    </div>
                                                                    <label for="min_personal_volume" class="form-label">
                                                                        <span id="personal_volume_label">Min Personal {{ old('personal_volume_basis', $commissionSetting->personal_volume_basis ?? 'volume') == 'points' ? 'Points' : 'Volume (৳)' }}</span>
                                                                    </label>
                                                                    <input type="number" min="0" step="0.01" class="form-control" 
                                                                           id="min_personal_volume" name="min_personal_volume" 
                                                                           value="{{ old('min_personal_volume', $commissionSetting->min_personal_volume) }}"
                                                                           placeholder="{{ old('personal_volume_basis', $commissionSetting->personal_volume_basis ?? 'volume') == 'points' ? 'e.g., 100 points' : 'e.g., 1000.00' }}">
                                                                    <div class="form-text">
                                                                        <span id="personal_volume_help">
                                                                            {{ old('personal_volume_basis', $commissionSetting->personal_volume_basis ?? 'volume') == 'points' ? 'Points required (1 Point = 6৳)' : 'Volume amount required' }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-check mb-3">
                                                                    <input class="form-check-input" type="checkbox" id="both_legs_required" 
                                                                           name="both_legs_required" value="1" 
                                                                           {{ old('both_legs_required', $commissionSetting->both_legs_required ?? true) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="both_legs_required">
                                                                        Both Legs Required
                                                                    </label>
                                                                </div>
                                                                <div id="both_legs_group" style="display: {{ $commissionSetting->both_legs_required ?? true ? 'block' : 'none' }};">
                                                                    <div class="mb-2">
                                                                        <label for="leg_calculation_basis" class="form-label">Leg Volume Calculation</label>
                                                                        <select class="form-select" id="leg_calculation_basis" name="leg_calculation_basis" onchange="updateLegLabels()">
                                                                            <option value="volume" {{ old('leg_calculation_basis', $commissionSetting->leg_calculation_basis ?? 'volume') == 'volume' ? 'selected' : '' }}>Volume (৳)</option>
                                                                            <option value="points" {{ old('leg_calculation_basis', $commissionSetting->leg_calculation_basis ?? 'volume') == 'points' ? 'selected' : '' }}>Points</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <label for="min_left_volume" class="form-label">
                                                                                <span id="left_leg_label">Min Left {{ old('leg_calculation_basis', $commissionSetting->leg_calculation_basis ?? 'volume') == 'points' ? 'Points' : 'Volume (৳)' }}</span>
                                                                            </label>
                                                                            <input type="number" min="0" step="0.01" class="form-control" 
                                                                                   id="min_left_volume" name="min_left_volume" 
                                                                                   value="{{ old('min_left_volume', $commissionSetting->min_left_volume) }}"
                                                                                   placeholder="{{ old('leg_calculation_basis', $commissionSetting->leg_calculation_basis ?? 'volume') == 'points' ? 'e.g., 100' : 'e.g., 1000.00' }}">
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <label for="min_right_volume" class="form-label">
                                                                                <span id="right_leg_label">Min Right {{ old('leg_calculation_basis', $commissionSetting->leg_calculation_basis ?? 'volume') == 'points' ? 'Points' : 'Volume (৳)' }}</span>
                                                                            </label>
                                                                            <input type="number" min="0" step="0.01" class="form-control" 
                                                                                   id="min_right_volume" name="min_right_volume" 
                                                                                   value="{{ old('min_right_volume', $commissionSetting->min_right_volume) }}"
                                                                                   placeholder="{{ old('leg_calculation_basis', $commissionSetting->leg_calculation_basis ?? 'volume') == 'points' ? 'e.g., 100' : 'e.g., 1000.00' }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-text">
                                                                        <span id="leg_help">
                                                                            {{ old('leg_calculation_basis', $commissionSetting->leg_calculation_basis ?? 'volume') == 'points' ? 'Minimum points required in each leg (1 Point = 6৳)' : 'Minimum volume required in each leg' }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Capping Settings -->
                                                <div class="card mb-3">
                                                    <div class="card-header">
                                                        <h6 class="mb-0">Capping Settings</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-check mb-3">
                                                                    <input class="form-check-input" type="checkbox" id="daily_cap_enabled" 
                                                                           name="daily_cap_enabled" value="1" 
                                                                           {{ old('daily_cap_enabled', $commissionSetting->daily_cap_enabled) ? 'checked' : '' }}>
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
                                                            <div class="col-md-6">
                                                                <div class="form-check mb-3">
                                                                    <input class="form-check-input" type="checkbox" id="weekly_cap_enabled" 
                                                                           name="weekly_cap_enabled" value="1" 
                                                                           {{ old('weekly_cap_enabled', $commissionSetting->weekly_cap_enabled) ? 'checked' : '' }}>
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Form Actions -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <button type="submit" class="btn btn-primary btn-lg me-2">
                                                    <i class="ri-save-line me-2"></i>Update Commission Setting
                                                </button>
                                                <a href="{{ route('admin.commission-settings.index') }}" class="btn btn-outline-secondary btn-lg">
                                                    <i class="ri-arrow-left-line me-2"></i>Cancel
                                                </a>
                                            </div>
                                        </div>
                                    </div>
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
// Enhanced JavaScript Functions for Edit Form
function handleTypeChange() {
    const typeSelect = document.getElementById('type');
    const selectedType = typeSelect.value;
    
    // Toggle matching-specific conditions
    const matchingConditions = document.getElementById('matching-conditions');
    if (matchingConditions) {
        matchingConditions.style.display = selectedType === 'matching' ? 'block' : 'none';
    }
    
    // Update value unit based on calculation type
    updateValueUnit();
    
    // Handle type-specific field requirements
    handleTypeSpecificRequirements(selectedType);
}

function handleCalculationChange() {
    updateValueUnit();
}

function updateValueUnit() {
    const calculationType = document.getElementById('calculation_type').value;
    const valueUnit = document.getElementById('value-unit');
    if (valueUnit) {
        valueUnit.textContent = calculationType === 'percentage' ? '%' : '৳';
    }
}

function handleTypeSpecificRequirements(type) {
    // Add any type-specific field requirements or validations here
    console.log('Type changed to:', type);
}

function toggleMultiLevel() {
    const checkbox = document.getElementById('enable_multi_level');
    const isChecked = checkbox.checked;
    
    // Toggle visibility of related sections
    const levelsConfigRow = document.getElementById('levels_configuration_row');
    const singleConfigRow = document.getElementById('single_level_configuration_row');
    const multiConfigSection = document.getElementById('multi_level_config_section');
    const quickSetupSection = document.getElementById('quick-setup-section');
    
    if (levelsConfigRow) levelsConfigRow.style.display = isChecked ? 'flex' : 'none';
    if (singleConfigRow) singleConfigRow.style.display = isChecked ? 'none' : 'flex';
    if (multiConfigSection) multiConfigSection.style.display = isChecked ? 'block' : 'none';
    if (quickSetupSection) quickSetupSection.style.display = isChecked ? 'flex' : 'none';
    
    // Toggle explanations
    const singleExplanation = document.getElementById('single-level-explanation');
    const multiExplanation = document.getElementById('multi-level-explanation');
    const singleBenefits = document.getElementById('single-benefits');
    const multiBenefits = document.getElementById('multi-benefits');
    
    if (singleExplanation) singleExplanation.style.display = isChecked ? 'none' : 'block';
    if (multiExplanation) multiExplanation.style.display = isChecked ? 'block' : 'none';
    if (singleBenefits) singleBenefits.style.display = isChecked ? 'none' : 'block';
    if (multiBenefits) multiBenefits.style.display = isChecked ? 'block' : 'none';
    
    // Handle max_levels field
    const maxLevelsSelect = document.getElementById('max_levels');
    const maxLevelsHidden = document.getElementById('max_levels_hidden');
    
    if (isChecked) {
        if (maxLevelsHidden) maxLevelsHidden.disabled = true;
        if (maxLevelsSelect) maxLevelsSelect.disabled = false;
    } else {
        if (maxLevelsHidden) maxLevelsHidden.disabled = false;
        if (maxLevelsSelect) {
            maxLevelsSelect.disabled = true;
            maxLevelsSelect.value = ''; // Clear selection
        }
        // Clear the levels container
        const levelsContainer = document.getElementById('levels-container');
        if (levelsContainer) levelsContainer.innerHTML = '';
    }
}

function handleLevelsChange() {
    const maxLevels = document.getElementById('max_levels').value;
    const levelsContainer = document.getElementById('levels-container');
    
    if (!levelsContainer || !maxLevels) return;
    
    levelsContainer.innerHTML = '';
    
    // Get existing levels data from PHP
    const existingLevels = @json($commissionSetting->levels ?? []);
    
    for (let i = 1; i <= parseInt(maxLevels); i++) {
        // Get existing data for this level
        const levelData = existingLevels[i - 1] || {};
        const levelValue = levelData.value || '';
        const levelMinQualification = levelData.min_qualification || levelData.minimum_qualification || '';
        const levelMaxPayout = levelData.max_payout || levelData.maximum_payout || '';
        
        const levelHtml = `
            <div class="level-item mb-3 p-3 border rounded" id="level-${i}">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Level ${i}</label>
                    </div>
                    <div class="col-md-3">
                        <label for="level_${i}_value" class="form-label">Commission Value</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="level_${i}_value" 
                                   id="level_${i}_value" step="0.01" min="0" placeholder="Enter value"
                                   value="${levelValue}">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="level_${i}_min_qualification" class="form-label">Min Qualification</label>
                        <input type="number" class="form-control" name="level_${i}_min_qualification" 
                               id="level_${i}_min_qualification" step="0.01" min="0" placeholder="0.00"
                               value="${levelMinQualification}">
                    </div>
                    <div class="col-md-3">
                        <label for="level_${i}_max_payout" class="form-label">Max Payout</label>
                        <input type="number" class="form-control" name="level_${i}_max_payout" 
                               id="level_${i}_max_payout" step="0.01" min="0" placeholder="Unlimited"
                               value="${levelMaxPayout}">
                    </div>
                    <div class="col-md-1">
                        ${i > 1 ? `<button type="button" class="btn btn-danger btn-sm" onclick="removeLevel(${i})" title="Remove Level"><i class="ri-delete-bin-line"></i></button>` : ''}
                    </div>
                </div>
            </div>
        `;
        levelsContainer.insertAdjacentHTML('beforeend', levelHtml);
    }
}

function quickSetupMultiLevel() {
    // Set max levels to 4
    const maxLevelsSelect = document.getElementById('max_levels');
    if (maxLevelsSelect) {
        maxLevelsSelect.value = '4';
        handleLevelsChange();
        
        // Set recommended values
        setTimeout(() => {
            const level1Value = document.getElementById('level_1_value');
            const level2Value = document.getElementById('level_2_value');
            const level3Value = document.getElementById('level_3_value');
            const level4Value = document.getElementById('level_4_value');
            
            if (level1Value) level1Value.value = '10';
            if (level2Value) level2Value.value = '7';
            if (level3Value) level3Value.value = '5';
            if (level4Value) level4Value.value = '3';
            
            // Show success message
            showSuccessMessage('Quick setup applied! Recommended structure has been configured.');
        }, 100);
    }
}

function addLevel() {
    const maxLevelsSelect = document.getElementById('max_levels');
    const currentMax = parseInt(maxLevelsSelect.value) || 0;
    const newMax = currentMax + 1;
    
    if (newMax <= 10) {
        // Add new option to select
        const newOption = document.createElement('option');
        newOption.value = newMax;
        newOption.textContent = `${newMax} Level${newMax > 1 ? 's' : ''}`;
        maxLevelsSelect.appendChild(newOption);
        
        // Select the new option
        maxLevelsSelect.value = newMax;
        
        // Trigger levels change
        handleLevelsChange();
    }
}

function removeLevel(levelNumber) {
    const levelElement = document.getElementById(`level-${levelNumber}`);
    if (levelElement) {
        levelElement.remove();
        
        // Update max levels
        const maxLevelsSelect = document.getElementById('max_levels');
        const currentMax = parseInt(maxLevelsSelect.value);
        if (currentMax > 1) {
            maxLevelsSelect.value = currentMax - 1;
            // Remove the last option
            maxLevelsSelect.removeChild(maxLevelsSelect.lastElementChild);
        }
    }
}

function showSuccessMessage(message) {
    // Create and show a temporary success message
    const alertHtml = `
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const container = document.querySelector('.card-body');
    if (container) {
        container.insertAdjacentHTML('afterbegin', alertHtml);
        
        // Auto-dismiss after 3 seconds
        setTimeout(() => {
            const alert = container.querySelector('.alert-success');
            if (alert) {
                alert.remove();
            }
        }, 3000);
    }
}

// Enhanced toggle functions for matching conditions
function toggleCarryForward() {
    const checkbox = document.getElementById('carry_forward_enabled');
    const settings = document.getElementById('carry_forward_settings');
    if (settings) {
        settings.style.display = checkbox.checked ? 'block' : 'none';
    }
}

function togglePersonalVolume() {
    const checkbox = document.getElementById('personal_volume_required');
    const group = document.getElementById('personal_volume_group');
    if (group) {
        group.style.display = checkbox.checked ? 'block' : 'none';
    }
}

function toggleBothLegs() {
    const checkbox = document.getElementById('both_legs_required');
    const group = document.getElementById('both_legs_group');
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

// Dynamic label update functions for calculation basis
function updateQualificationLabel() {
    const basis = document.getElementById('qualification_basis').value;
    const label = document.getElementById('qualification_label');
    const help = document.getElementById('qualification_help');
    const input = document.getElementById('min_qualification');
    
    if (basis === 'points') {
        label.textContent = 'Minimum Qualification (Points)';
        help.textContent = 'Points needed for qualification (1 Point = 6৳)';
        input.placeholder = 'e.g., 100 points';
    } else {
        label.textContent = 'Minimum Qualification (৳)';
        help.textContent = 'Volume amount needed for qualification';
        input.placeholder = 'e.g., 1000.00';
    }
}

function updatePersonalVolumeLabel() {
    const basis = document.getElementById('personal_volume_basis').value;
    const label = document.getElementById('personal_volume_label');
    const help = document.getElementById('personal_volume_help');
    const input = document.getElementById('min_personal_volume');
    
    if (basis === 'points') {
        label.textContent = 'Min Personal Points';
        help.textContent = 'Points required (1 Point = 6৳)';
        input.placeholder = 'e.g., 100 points';
    } else {
        label.textContent = 'Min Personal Volume (৳)';
        help.textContent = 'Volume amount required';
        input.placeholder = 'e.g., 1000.00';
    }
}

function updateLegLabels() {
    const basis = document.getElementById('leg_calculation_basis').value;
    const leftLabel = document.getElementById('left_leg_label');
    const rightLabel = document.getElementById('right_leg_label');
    const help = document.getElementById('leg_help');
    const leftInput = document.getElementById('min_left_volume');
    const rightInput = document.getElementById('min_right_volume');
    
    if (basis === 'points') {
        leftLabel.textContent = 'Min Left Points';
        rightLabel.textContent = 'Min Right Points';
        help.textContent = 'Minimum points required in each leg (1 Point = 6৳)';
        leftInput.placeholder = 'e.g., 100';
        rightInput.placeholder = 'e.g., 100';
    } else {
        leftLabel.textContent = 'Min Left Volume (৳)';
        rightLabel.textContent = 'Min Right Volume (৳)';
        help.textContent = 'Minimum volume required in each leg';
        leftInput.placeholder = 'e.g., 1000.00';
        rightInput.placeholder = 'e.g., 1000.00';
    }
}

// Initialize existing levels on page load
function initializeExistingLevels() {
    const multiLevelCheckbox = document.getElementById('enable_multi_level');
    const maxLevelsSelect = document.getElementById('max_levels');
    
    // Check if multi-level is enabled and max_levels has a value
    if (multiLevelCheckbox && multiLevelCheckbox.checked && maxLevelsSelect && maxLevelsSelect.value) {
        // Load existing level data
        handleLevelsChange();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    handleTypeChange();
    handleCalculationChange();
    toggleMultiLevel(); // Initialize multi-level toggle state
    
    // Initialize calculation basis labels
    updateQualificationLabel();
    updatePersonalVolumeLabel();
    updateLegLabels();
    
    // Initialize levels on page load if conditions are met
    initializeExistingLevels();
    
    // Initialize matching conditions if type is matching
    if (document.getElementById('type').value === 'matching') {
        toggleCarryForward();
        togglePersonalVolume();
        toggleBothLegs();
        toggleDailyCap();
        toggleWeeklyCap();
    }
    
    // Add event listeners
    const multiLevelCheckbox = document.getElementById('enable_multi_level');
    if (multiLevelCheckbox) {
        multiLevelCheckbox.addEventListener('change', toggleMultiLevel);
    }
    
    const typeSelect = document.getElementById('type');
    if (typeSelect) {
        typeSelect.addEventListener('change', handleTypeChange);
    }
    
    const calculationSelect = document.getElementById('calculation_type');
    if (calculationSelect) {
        calculationSelect.addEventListener('change', handleCalculationChange);
    }
    
    const maxLevelsSelect = document.getElementById('max_levels');
    if (maxLevelsSelect) {
        maxLevelsSelect.addEventListener('change', handleLevelsChange);
        // Initialize levels on page load if max_levels has a value
        if (maxLevelsSelect.value) {
            handleLevelsChange();
        }
    }
    
    // Add event listeners for matching condition toggles
    const carryForwardCheckbox = document.getElementById('carry_forward_enabled');
    if (carryForwardCheckbox) {
        carryForwardCheckbox.addEventListener('change', toggleCarryForward);
    }
    
    const personalVolumeCheckbox = document.getElementById('personal_volume_required');
    if (personalVolumeCheckbox) {
        personalVolumeCheckbox.addEventListener('change', togglePersonalVolume);
    }
    
    const bothLegsCheckbox = document.getElementById('both_legs_required');
    if (bothLegsCheckbox) {
        bothLegsCheckbox.addEventListener('change', toggleBothLegs);
    }
    
    const dailyCapCheckbox = document.getElementById('daily_cap_enabled');
    if (dailyCapCheckbox) {
        dailyCapCheckbox.addEventListener('change', toggleDailyCap);
    }
    
    const weeklyCapCheckbox = document.getElementById('weekly_cap_enabled');
    if (weeklyCapCheckbox) {
        weeklyCapCheckbox.addEventListener('change', toggleWeeklyCap);
    }
});
</script>
@endsection
