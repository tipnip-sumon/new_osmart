@extends('admin.layouts.app')

@section('title', 'Create Commission Setting')

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
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.commission-settings.index') }}">Commission Settings</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create Setting</li>
                </ol>
            </nav>
            <h1 class="page-title fw-medium fs-18 mb-0">Create Commission Setting</h1>
        </div>
        <div class="btn-list">
            <a href="{{ route('admin.commission-settings.index') }}" class="btn btn-secondary btn-wave me-0">
                <i class="bx bx-arrow-back me-1"></i> Back to Settings
            </a>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Commission Setting Details</div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.commission-settings.store') }}" method="POST" id="commissionForm">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">System Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="e.g., sponsor_commission_5_percent">
                                <small class="text-muted">Internal system name (lowercase, underscores only)</small>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="display_name" class="form-label">Display Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                       id="display_name" name="display_name" value="{{ old('display_name') }}" 
                                       placeholder="e.g., Sponsor Commission 5%">
                                @error('display_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="Brief description of this commission setting">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Calculation System Information -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bx bx-info-circle me-2"></i>
                                        <strong>Commission Calculation Systems</strong>
                                    </div>
                                    <div class="row small">
                                        <div class="col-md-6">
                                            <h6 class="text-primary">Volume-Based System</h6>
                                            <ul class="mb-0 ps-3">
                                                <li><strong>Calculation:</strong> Based on purchase amounts in Taka (৳)</li>
                                                <li><strong>Usage:</strong> Traditional MLM commission structure</li>
                                                <li><strong>Example:</strong> 5% commission on ৳10,000 = ৳500</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-success">Point-Based System</h6>
                                            <ul class="mb-0 ps-3">
                                                <li><strong>Conversion:</strong> 1 Point = 6 Taka</li>
                                                <li><strong>Matching Rate:</strong> 10% on binary matching</li>
                                                <li><strong>Minimum:</strong> 100 points per leg for matching</li>
                                                <li><strong>Example:</strong> 500 points = ৳3,000 equivalent</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Commission Type and Calculation -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="type" class="form-label">Commission Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" name="type">
                                    <option value="">Select Type</option>
                                    @foreach($types as $key => $value)
                                        <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="calculation_type" class="form-label">Calculation Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('calculation_type') is-invalid @enderror" 
                                        id="calculation_type" name="calculation_type">
                                    <option value="">Select Calculation</option>
                                    @foreach($calculationTypes as $key => $value)
                                        <option value="{{ $key }}" {{ old('calculation_type') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('calculation_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="value" class="form-label">Value <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="value-prefix">৳</span>
                                    <input type="number" step="0.01" min="0" 
                                           class="form-control @error('value') is-invalid @enderror" 
                                           id="value" name="value" value="{{ old('value') }}" 
                                           placeholder="0.00">
                                </div>
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Levels Configuration -->
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
                                                   {{ old('enable_multi_level', false) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="enable_multi_level">
                                                Enable Multi-Level Configuration
                                            </label>
                                        </div>
                                        
                                        <!-- Explanation Section -->
                                        <div class="alert alert-info mb-3">
                                            <h6 class="alert-heading mb-2">What does this mean?</h6>
                                            <div id="single-level-explanation" style="display: block;">
                                                <strong>Single Level (Current):</strong> One commission value applies to all levels.<br>
                                                <em>Example: 5% commission for levels 1, 2, 3, 4, 5...</em>
                                            </div>
                                            <div id="multi-level-explanation" style="display: none;">
                                                <strong>Multi-Level:</strong> Different commission values for each level.<br>
                                                <em>Example: Level 1: 10%, Level 2: 7%, Level 3: 5%, Level 4: 3%</em>
                                            </div>
                                        </div>
                                        
                                        <!-- Benefits Section -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="border rounded p-3 mb-3" id="single-benefits">
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
                                                <div class="border rounded p-3 mb-3" id="multi-benefits" style="display: none;">
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

                        <div class="row mb-4" id="levels_configuration_row" style="display: none;">
                            <div class="col-md-6">
                                <label for="max_levels" class="form-label">Maximum Levels <span class="text-danger">*</span></label>
                                <select class="form-select @error('max_levels') is-invalid @enderror" 
                                        id="max_levels" name="max_levels">
                                    <option value="">Select Number of Levels</option>
                                    @for($i = 1; $i <= 20; $i++)
                                        <option value="{{ $i }}" {{ old('max_levels') == $i ? 'selected' : '' }}>
                                            {{ $i }} Level{{ $i > 1 ? 's' : '' }}
                                        </option>
                                    @endfor
                                </select>
                                @error('max_levels')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="priority" class="form-label">Priority</label>
                                <input type="number" min="0" 
                                       class="form-control @error('priority') is-invalid @enderror" 
                                       id="priority" name="priority" value="{{ old('priority', 0) }}" 
                                       placeholder="0">
                                <small class="text-muted">Higher priority settings are processed first</small>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Single Level Configuration (shown when multi-level is disabled) -->
                        <div class="row mb-4" id="single_level_configuration_row">
                            <div class="col-md-6">
                                <label for="priority_single" class="form-label">Priority</label>
                                <input type="number" min="0" 
                                       class="form-control @error('priority') is-invalid @enderror" 
                                       id="priority_single" name="priority" value="{{ old('priority', 0) }}" 
                                       placeholder="0">
                                <small class="text-muted">Higher priority settings are processed first</small>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Hidden field for max_levels when multi-level is disabled -->
                            <input type="hidden" id="max_levels_hidden" name="max_levels" value="1">
                        </div>

                        

                        <!-- Qualification Requirements -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="min_qualification" class="form-label">Minimum Qualification</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" 
                                           class="form-control @error('min_qualification') is-invalid @enderror" 
                                           id="min_qualification" name="min_qualification" value="{{ old('min_qualification', 0) }}" 
                                           placeholder="0.00">
                                    <select class="form-select" style="max-width: 120px;" id="qualification_basis" name="qualification_basis">
                                        <option value="volume" selected>Volume (৳)</option>
                                        <option value="points">Points</option>
                                    </select>
                                </div>
                                <small class="text-muted">Minimum volume/points required for commission eligibility</small>
                                @error('min_qualification')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="max_payout" class="form-label">Maximum Payout (৳)</label>
                                <input type="number" step="0.01" min="0" 
                                       class="form-control @error('max_payout') is-invalid @enderror" 
                                       id="max_payout" name="max_payout" value="{{ old('max_payout') }}" 
                                       placeholder="No limit">
                                @error('max_payout')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Conditions Section -->
                        <div class="mb-4">
                            <h6 class="mb-3">Commission Conditions</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="min_purchase_amount" class="form-label">Minimum Purchase Amount</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" 
                                               class="form-control" id="min_purchase_amount" 
                                               name="min_purchase_amount" value="{{ old('min_purchase_amount') }}" 
                                               placeholder="0.00">
                                        <select class="form-select" style="max-width: 120px;" id="purchase_basis" name="purchase_basis">
                                            <option value="volume" selected>Volume (৳)</option>
                                            <option value="points">Points</option>
                                        </select>
                                    </div>
                                    <small class="text-muted">Minimum purchase amount or points for commission eligibility</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="required_rank" class="form-label">Required Rank</label>
                                    <select class="form-select" id="required_rank" name="required_rank">
                                        <option value="">No rank requirement</option>
                                        <option value="bronze" {{ old('required_rank') == 'bronze' ? 'selected' : '' }}>Bronze</option>
                                        <option value="silver" {{ old('required_rank') == 'silver' ? 'selected' : '' }}>Silver</option>
                                        <option value="gold" {{ old('required_rank') == 'gold' ? 'selected' : '' }}>Gold</option>
                                        <option value="platinum" {{ old('required_rank') == 'platinum' ? 'selected' : '' }}>Platinum</option>
                                        <option value="diamond" {{ old('required_rank') == 'diamond' ? 'selected' : '' }}>Diamond</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label for="required_downlines" class="form-label">Required Active Downlines</label>
                                    <input type="number" min="0" 
                                           class="form-control" id="required_downlines" 
                                           name="required_downlines" value="{{ old('required_downlines') }}" 
                                           placeholder="0">
                                </div>
                                <div class="col-md-6">
                                    <label for="required_pv" class="form-label">Required Personal Volume/Points</label>
                                    <div class="input-group">
                                        <input type="number" min="0" 
                                               class="form-control" id="required_pv" 
                                               name="required_pv" value="{{ old('required_pv') }}" 
                                               placeholder="0">
                                        <select class="form-select" style="max-width: 120px;" id="pv_calculation_basis" name="pv_calculation_basis">
                                            <option value="volume" selected>Volume (৳)</option>
                                            <option value="points">Points</option>
                                        </select>
                                    </div>
                                    <small class="text-muted">Choose calculation basis: Volume in Taka or Points</small>
                                </div>
                            </div>
                        </div>
                        <!-- Enhanced Multi-Level Configuration -->
                        <div class="row mt-4" id="multi_level_config_section" style="display: none;">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Multi-Level Configuration</h6>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-success" onclick="quickSetupMatching()" id="quick-matching-btn" style="display: none;">
                                                <i class="ri-magic-line me-1"></i>Quick Matching Setup
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="addLevel()">
                                                <i class="ri-add-line me-1"></i>Add Level
                                            </button>
                                        </div>
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
                                
                                <!-- Practical Example Section -->
                                <div class="card mt-3" id="practical-example-card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 text-primary">📋 Practical Example</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-success">🎯 What You're Building:</h6>
                                                <div class="example-scenario p-3 bg-light rounded">
                                                    <strong>Scenario:</strong> Sponsor Commission with Multi-Level<br>
                                                    <strong>Type:</strong> Percentage-based<br><br>
                                                    
                                                    <strong>Level Structure:</strong><br>
                                                    • <span class="badge bg-primary">Level 1</span> Direct referrals get <strong>10%</strong><br>
                                                    • <span class="badge bg-secondary">Level 2</span> 2nd level gets <strong>7%</strong><br>
                                                    • <span class="badge bg-secondary">Level 3</span> 3rd level gets <strong>5%</strong><br>
                                                    • <span class="badge bg-secondary">Level 4</span> 4th level gets <strong>3%</strong><br>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-info">💰 How It Works:</h6>
                                                <div class="example-calculation p-3 bg-light rounded">
                                                    <strong>If someone buys $100 product:</strong><br><br>
                                                    
                                                    ✅ <strong>Direct referrer</strong> earns: $10 (10%)<br>
                                                    ✅ <strong>2nd level up</strong> earns: $7 (7%)<br>
                                                    ✅ <strong>3rd level up</strong> earns: $5 (5%)<br>
                                                    ✅ <strong>4th level up</strong> earns: $3 (3%)<br><br>
                                                    
                                                    <div class="border-top pt-2 mt-2">
                                                        <strong>Total paid out:</strong> $25 (25%)<br>
                                                        <strong>Company keeps:</strong> $75 (75%)
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="alert alert-warning mt-3 mb-0">
                                            <small><strong>💡 Pro Tip:</strong> Start with higher percentages for direct referrals to encourage direct sales, then decrease for deeper levels to control costs while still rewarding network building.</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Multi-Level Matching Guide Section -->
                                <div class="card mt-3" id="matching-guide-card" style="display: none;">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0 text-white">🎯 Multi-Level Matching Bonus Setup Guide</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-primary">🔥 Recommended Matching Structure:</h6>
                                                <div class="bg-light p-3 rounded">
                                                    <strong>Binary Matching with Multi-Level:</strong><br><br>
                                                    
                                                    • <span class="badge bg-success">Level 1: 10%</span> - Basic qualification<br>
                                                    • <span class="badge bg-info">Level 2: 7%</span> - Bronze rank required<br>
                                                    • <span class="badge bg-warning">Level 3: 5%</span> - Silver rank required<br>
                                                    • <span class="badge bg-danger">Level 4: 3%</span> - Gold rank required<br><br>
                                                    
                                                    <small class="text-muted">
                                                        <strong>Total Max Earning:</strong> 25% of weaker leg volume/points
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-success">💰 Matching Calculation Examples:</h6>
                                                
                                                <!-- Volume-Based Example -->
                                                <div class="bg-light p-3 rounded mb-3">
                                                    <h6 class="text-primary mb-2">Volume-Based System</h6>
                                                    <strong>Your Binary Tree Volume:</strong><br>
                                                    <code>Left Side: ৳10,000</code><br>
                                                    <code>Right Side: ৳8,000</code><br><br>
                                                    
                                                    <strong>Weaker Leg:</strong> ৳8,000 (Right)<br><br>
                                                    
                                                    <strong>Your Earnings:</strong><br>
                                                    ✅ Level 1: ৳8,000 × 10% = <strong>৳800</strong><br>
                                                    ✅ Level 2: ৳8,000 × 7% = <strong>৳560</strong><br>
                                                    ✅ Level 3: ৳8,000 × 5% = <strong>৳400</strong><br>
                                                </div>
                                                
                                                <!-- Point-Based Example -->
                                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                                    <h6 class="text-success mb-2">Point-Based System</h6>
                                                    <strong>Your Binary Tree Points:</strong><br>
                                                    <code>Left Side: 1,500 Points</code><br>
                                                    <code>Right Side: 1,200 Points</code><br><br>
                                                    
                                                    <strong>Weaker Leg:</strong> 1,200 Points (Right)<br>
                                                    <strong>Matching:</strong> 1,200 × 10% = <strong>120 Points</strong><br>
                                                    <strong>Taka Equivalent:</strong> 120 × 6 = <strong>৳720</strong><br><br>
                                                    
                                                    <small class="text-muted">
                                                        <i class="bx bx-info-circle"></i> Point system: 1 Point = ৳6, 100 points minimum per leg
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <h6 class="text-info">⚙️ Recommended Requirements per Level:</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm">
                                                        <thead class="table-primary">
                                                            <tr>
                                                                <th>Level</th>
                                                                <th>Percentage</th>
                                                                <th>Personal Volume/Points</th>
                                                                <th>Min Rank</th>
                                                                <th>Left/Right Volume/Points</th>
                                                                <th>Condition Code</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><span class="badge bg-success">Level 1</span></td>
                                                                <td><strong>10%</strong></td>
                                                                <td>$1,000</td>
                                                                <td>Any</td>
                                                                <td>$5,000 each</td>
                                                                <td><code>min_personal:1000</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td><span class="badge bg-info">Level 2</span></td>
                                                                <td><strong>7%</strong></td>
                                                                <td>$1,500</td>
                                                                <td>Bronze</td>
                                                                <td>$10,000 each</td>
                                                                <td><code>min_rank:bronze,min_personal:1500</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td><span class="badge bg-warning">Level 3</span></td>
                                                                <td><strong>5%</strong></td>
                                                                <td>$2,000</td>
                                                                <td>Silver</td>
                                                                <td>$20,000 each</td>
                                                                <td><code>min_rank:silver,min_personal:2000</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td><span class="badge bg-danger">Level 4</span></td>
                                                                <td><strong>3%</strong></td>
                                                                <td>$3,000</td>
                                                                <td>Gold</td>
                                                                <td>$50,000 each</td>
                                                                <td><code>min_rank:gold,min_personal:3000</code></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="alert alert-info mt-3 mb-0">
                                            <h6 class="alert-heading">🚀 Quick Setup Steps:</h6>
                                            <ol class="mb-0">
                                                <li><strong>Commission Type:</strong> Select "Matching"</li>
                                                <li><strong>Calculation Type:</strong> Select "Percentage"</li>
                                                <li><strong>Enable Multi-Level:</strong> Check the checkbox</li>
                                                <li><strong>Maximum Levels:</strong> Select "4 Levels"</li>
                                                <li><strong>Add Levels:</strong> Use "Add Level" button 4 times</li>
                                                <li><strong>Configure Matching:</strong> Set personal volume/points, both legs requirements</li>
                                                <li><strong>Calculation Basis:</strong> Choose Volume (৳) or Points for each requirement</li>
                                                <li><strong>Advanced Settings:</strong> Enable carry forward, set caps as needed</li>
                                            </ol>
                                            
                                            <div class="mt-3">
                                                <h6 class="text-primary">💡 Point System Tips:</h6>
                                                <ul class="mb-0 small">
                                                    <li><strong>Point Conversion:</strong> 1 Point = 6 Taka</li>
                                                    <li><strong>Min Requirements:</strong> Use lower values for points (e.g., 100 points vs 1000 ৳)</li>
                                                    <li><strong>Conditions:</strong> Use <code>min_points:100</code> instead of <code>min_volume:1000</code></li>
                                                    <li><strong>Mixed Mode:</strong> You can use both volume and points in different levels</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Type-specific conditions -->
                        <div id="typeSpecificConditions">
                            <!-- Will be populated by JavaScript based on selected type -->
                        </div>

                        <!-- Status -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" 
                                           name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active Setting
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.commission-settings.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Create Setting
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function handleTypeChange() {
    const type = document.getElementById('type').value;
    const conditionsContainer = document.getElementById('typeSpecificConditions');
    
    // Clear existing conditions
    conditionsContainer.innerHTML = '';
    
    // Add type-specific conditions
    switch(type) {
        case 'matching':
            conditionsContainer.innerHTML = `
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
                                           value="{{ old('matching_levels', 5) }}" placeholder="5">
                                </div>
                                <div class="col-md-4">
                                    <label for="matching_frequency" class="form-label">Matching Frequency</label>
                                    <select class="form-select" id="matching_frequency" name="matching_frequency">
                                        <option value="daily">Daily</option>
                                        <option value="hourly">Hourly</option>
                                        <option value="real_time">Real Time</option>
                                        <option value="weekly">Weekly</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="matching_time" class="form-label">Matching Time</label>
                                    <input type="time" class="form-control" id="matching_time" name="matching_time" 
                                           value="{{ old('matching_time', '23:59') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carry Forward Settings -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="carry_forward_enabled" 
                                       name="carry_forward_enabled" value="1" onchange="toggleCarryForward()">
                                <label class="form-check-label" for="carry_forward_enabled">
                                    <h6 class="mb-0">Enable Carry Forward</h6>
                                </label>
                            </div>
                        </div>
                        <div class="card-body" id="carry_forward_settings" style="display: none;">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="carry_side" class="form-label">Carry Side</label>
                                    <select class="form-select" id="carry_side" name="carry_side">
                                        <option value="strong">Strong Side</option>
                                        <option value="weak">Weak Side</option>
                                        <option value="both">Both Sides</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="carry_percentage" class="form-label">Carry Percentage (%)</label>
                                    <input type="number" min="0" max="100" step="0.01" class="form-control" 
                                           id="carry_percentage" name="carry_percentage" placeholder="80">
                                </div>
                                <div class="col-md-3">
                                    <label for="carry_max_days" class="form-label">Max Carry Days</label>
                                    <input type="number" min="1" max="365" class="form-control" 
                                           id="carry_max_days" name="carry_max_days" placeholder="30">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slot Matching Settings -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="slot_matching_enabled" 
                                       name="slot_matching_enabled" value="1" onchange="toggleSlotMatching()">
                                <label class="form-check-label" for="slot_matching_enabled">
                                    <h6 class="mb-0">Enable Slot Matching</h6>
                                </label>
                            </div>
                        </div>
                        <div class="card-body" id="slot_matching_settings" style="display: none;">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="slot_size" class="form-label">Slot Size</label>
                                    <input type="number" min="1" class="form-control" 
                                           id="slot_size" name="slot_size" placeholder="1000">
                                </div>
                                <div class="col-md-3">
                                    <label for="slot_type" class="form-label">Slot Type</label>
                                    <select class="form-select" id="slot_type" name="slot_type">
                                        <option value="volume">Volume Based</option>
                                        <option value="points">Points Based</option>
                                        <option value="count">Count Based</option>
                                        <option value="mixed">Mixed</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="min_slot_volume" class="form-label">Min Slot Volume/Points</label>
                                    <div class="input-group">
                                        <input type="number" min="0" step="0.01" class="form-control" 
                                               id="min_slot_volume" name="min_slot_volume" placeholder="500">
                                        <select class="form-select" style="max-width: 100px;" id="slot_volume_basis" name="slot_volume_basis">
                                            <option value="volume" selected>৳</option>
                                            <option value="points">Pts</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="min_slot_count" class="form-label">Min Slot Count</label>
                                    <input type="number" min="1" class="form-control" 
                                           id="min_slot_count" name="min_slot_count" placeholder="2">
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
                                               name="auto_balance_enabled" value="1">
                                        <label class="form-check-label" for="auto_balance_enabled">
                                            Enable Auto Balance
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="spillover_enabled" 
                                               name="spillover_enabled" value="1" onchange="toggleSpillover()">
                                        <label class="form-check-label" for="spillover_enabled">
                                            Enable Spillover
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="balance_ratio" class="form-label">Balance Ratio</label>
                                        <input type="number" min="0.1" max="10" step="0.1" class="form-control" 
                                               id="balance_ratio" name="balance_ratio" value="1.0" placeholder="1.0">
                                    </div>
                                    <div class="mb-3" id="spillover_direction_group" style="display: none;">
                                        <label for="spillover_direction" class="form-label">Spillover Direction</label>
                                        <select class="form-select" id="spillover_direction" name="spillover_direction">
                                            <option value="weaker">To Weaker Side</option>
                                            <option value="stronger">To Stronger Side</option>
                                            <option value="alternate">Alternate Sides</option>
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
                                               name="flush_enabled" value="1" onchange="toggleFlush()">
                                        <label class="form-check-label" for="flush_enabled">
                                            Enable Flush
                                        </label>
                                    </div>
                                    <div id="flush_percentage_group" style="display: none;">
                                        <label for="flush_percentage" class="form-label">Flush Percentage (%)</label>
                                        <input type="number" min="0" max="100" step="0.01" class="form-control" 
                                               id="flush_percentage" name="flush_percentage" placeholder="80">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="daily_cap_enabled" 
                                               name="daily_cap_enabled" value="1" onchange="toggleDailyCap()">
                                        <label class="form-check-label" for="daily_cap_enabled">
                                            Enable Daily Cap
                                        </label>
                                    </div>
                                    <div id="daily_cap_amount_group" style="display: none;">
                                        <label for="daily_cap_amount" class="form-label">Daily Cap Amount (৳)</label>
                                        <input type="number" min="0" step="0.01" class="form-control" 
                                               id="daily_cap_amount" name="daily_cap_amount" placeholder="10000">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="weekly_cap_enabled" 
                                               name="weekly_cap_enabled" value="1" onchange="toggleWeeklyCap()">
                                        <label class="form-check-label" for="weekly_cap_enabled">
                                            Enable Weekly Cap
                                        </label>
                                    </div>
                                    <div id="weekly_cap_amount_group" style="display: none;">
                                        <label for="weekly_cap_amount" class="form-label">Weekly Cap Amount (৳)</label>
                                        <input type="number" min="0" step="0.01" class="form-control" 
                                               id="weekly_cap_amount" name="weekly_cap_amount" placeholder="50000">
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
                                               name="personal_volume_required" value="1" onchange="togglePersonalVolume()">
                                        <label class="form-check-label" for="personal_volume_required">
                                            Personal Volume Required
                                        </label>
                                    </div>
                                    <div id="personal_volume_group" style="display: none;">
                                        <label for="min_personal_volume" class="form-label">Min Personal Volume/Points</label>
                                        <div class="input-group">
                                            <input type="number" min="0" step="0.01" class="form-control" 
                                                   id="min_personal_volume" name="min_personal_volume" placeholder="1000">
                                            <select class="form-select" style="max-width: 120px;" id="personal_volume_basis" name="personal_volume_basis">
                                                <option value="volume" selected>Volume (৳)</option>
                                                <option value="points">Points</option>
                                            </select>
                                        </div>
                                        <small class="text-muted">Personal volume/points requirement for commission</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="both_legs_required" 
                                               name="both_legs_required" value="1" checked onchange="toggleBothLegs()">
                                        <label class="form-check-label" for="both_legs_required">
                                            Both Legs Required
                                        </label>
                                    </div>
                                    <div id="both_legs_group">
                                        <div class="mb-3">
                                            <label class="form-label">Calculation Basis</label>
                                            <select class="form-select" id="leg_calculation_basis" name="leg_calculation_basis">
                                                <option value="volume" selected>Volume Based (৳)</option>
                                                <option value="points">Points Based</option>
                                            </select>
                                            <small class="text-muted">Choose whether to calculate legs based on volume or points</small>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="min_left_volume" class="form-label" id="left_leg_label">Min Left Volume (৳)</label>
                                                <input type="number" min="0" step="0.01" class="form-control" 
                                                       id="min_left_volume" name="min_left_volume" placeholder="5000">
                                            </div>
                                            <div class="col-6">
                                                <label for="min_right_volume" class="form-label" id="right_leg_label">Min Right Volume (৳)</label>
                                                <input type="number" min="0" step="0.01" class="form-control" 
                                                       id="min_right_volume" name="min_right_volume" placeholder="5000">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            break;
            
        case 'generation':
            conditionsContainer.innerHTML = `
                <div class="mb-4">
                    <h6 class="mb-3">Generation Bonus Conditions</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="generation_depth" class="form-label">Generation Depth</label>
                            <input type="number" min="1" max="15" class="form-control" 
                                   id="generation_depth" name="generation_depth" 
                                   value="{{ old('generation_depth', 7) }}" placeholder="7">
                        </div>
                    </div>
                </div>
            `;
            break;
            
        case 'rank':
            conditionsContainer.innerHTML = `
                <div class="mb-4">
                    <h6 class="mb-3">Rank Achievement Conditions</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="target_rank" class="form-label">Target Rank</label>
                            <select class="form-select" id="target_rank" name="target_rank">
                                <option value="bronze">Bronze</option>
                                <option value="silver">Silver</option>
                                <option value="gold">Gold</option>
                                <option value="platinum">Platinum</option>
                                <option value="diamond">Diamond</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
            break;
            
        case 'club':
            conditionsContainer.innerHTML = `
                <div class="mb-4">
                    <h6 class="mb-3">Club Bonus Conditions</h6>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="club_requirements" class="form-label">Club Requirements</label>
                            <textarea class="form-control" id="club_requirements" 
                                      name="club_requirements" rows="3" 
                                      placeholder="Specify club membership requirements">{{ old('club_requirements') }}</textarea>
                        </div>
                    </div>
                </div>
            `;
            break;
    }
    
    // Show/hide matching guide and quick setup based on type and multi-level setting
    const matchingGuide = document.getElementById('matching-guide-card');
    const quickMatchingBtn = document.getElementById('quick-matching-btn');
    const multiLevelCheckbox = document.getElementById('enable_multi_level');
    
    if (type === 'matching') {
        if (multiLevelCheckbox && multiLevelCheckbox.checked) {
            if (matchingGuide) matchingGuide.style.display = 'block';
            if (quickMatchingBtn) quickMatchingBtn.style.display = 'inline-block';
        }
    } else {
        if (matchingGuide) matchingGuide.style.display = 'none';
        if (quickMatchingBtn) quickMatchingBtn.style.display = 'none';
    }
}

function handleCalculationChange() {
    const calculationType = document.getElementById('calculation_type').value;
    const prefix = document.getElementById('value-prefix');
    
    if (calculationType === 'percentage') {
        prefix.textContent = '%';
    } else {
        prefix.textContent = '৳';
    }
    
    // Update all level prefixes
    document.querySelectorAll('.level-prefix').forEach(function(element) {
        element.textContent = calculationType === 'percentage' ? '%' : '৳';
    });
}

function handleLevelsChange() {
    // This function is kept for compatibility but the new system uses addLevel/removeLevel
    // The new multi-level system is more flexible and user-controlled
}

let levelIndex = 0;

function quickSetupMatching() {
    // Clear existing levels
    document.getElementById('levels-container').innerHTML = '';
    levelIndex = 0;
    
    // Set calculation type to percentage if not already set
    const calculationType = document.getElementById('calculation_type');
    if (calculationType.value !== 'percentage') {
        calculationType.value = 'percentage';
        handleCalculationChange();
    }
    
    // Set max levels to 4
    const maxLevels = document.getElementById('max_levels');
    if (maxLevels) {
        maxLevels.value = '4';
    }
    
    // Create recommended matching levels
    const matchingLevels = [
        { level: 1, value: 10, condition: 'min_personal_points:100' },
        { level: 2, value: 7, condition: 'min_rank:bronze,min_personal_points:250' },
        { level: 3, value: 5, condition: 'min_rank:silver,min_personal_points:500' },
        { level: 4, value: 3, condition: 'min_rank:gold,min_personal_points:750' }
    ];
    
    matchingLevels.forEach(level => {
        const levelHtml = `
            <div class="level-item border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Level ${level.level} <span class="badge bg-success ms-2">${level.value}% Matching</span></h6>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeLevel(this)">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Level Number</label>
                            <input type="number" class="form-control" name="levels[${levelIndex}][level]" 
                                   value="${level.level}" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Value</label>
                            <div class="input-group">
                                <span class="input-group-text level-prefix">%</span>
                                <input type="number" class="form-control" name="levels[${levelIndex}][value]" 
                                       value="${level.value}" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Condition</label>
                            <input type="text" class="form-control" name="levels[${levelIndex}][condition]" 
                                   value="${level.condition}" placeholder="e.g., min_volume:1000 or min_points:100">
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('levels-container').insertAdjacentHTML('beforeend', levelHtml);
        levelIndex++;
    });
    
    // Show success message
    const toast = document.createElement('div');
    toast.className = 'alert alert-success alert-dismissible fade show position-fixed';
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; width: 300px;';
    toast.innerHTML = `
        <strong>✅ Quick Setup Complete!</strong><br>
        4-level matching structure created with recommended percentages and conditions.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);
    
    // Auto-remove toast after 5 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 5000);
}

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
                               placeholder="e.g., min_volume:1000 or min_points:100">
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
    settings.style.display = checkbox.checked ? 'block' : 'none';
}

function toggleSlotMatching() {
    const checkbox = document.getElementById('slot_matching_enabled');
    const settings = document.getElementById('slot_matching_settings');
    settings.style.display = checkbox.checked ? 'block' : 'none';
}

function toggleSpillover() {
    const checkbox = document.getElementById('spillover_enabled');
    const group = document.getElementById('spillover_direction_group');
    group.style.display = checkbox.checked ? 'block' : 'none';
}

function toggleFlush() {
    const checkbox = document.getElementById('flush_enabled');
    const group = document.getElementById('flush_percentage_group');
    group.style.display = checkbox.checked ? 'block' : 'none';
}

function toggleDailyCap() {
    const checkbox = document.getElementById('daily_cap_enabled');
    const group = document.getElementById('daily_cap_amount_group');
    group.style.display = checkbox.checked ? 'block' : 'none';
}

function toggleWeeklyCap() {
    const checkbox = document.getElementById('weekly_cap_enabled');
    const group = document.getElementById('weekly_cap_amount_group');
    group.style.display = checkbox.checked ? 'block' : 'none';
}

function togglePersonalVolume() {
    const checkbox = document.getElementById('personal_volume_required');
    const group = document.getElementById('personal_volume_group');
    group.style.display = checkbox.checked ? 'block' : 'none';
}

function toggleBothLegs() {
    const checkbox = document.getElementById('both_legs_required');
    const group = document.getElementById('both_legs_group');
    group.style.display = checkbox.checked ? 'block' : 'none';
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
    const practicalExample = document.getElementById('practical-example-card');
    const matchingGuide = document.getElementById('matching-guide-card');
    const quickMatchingBtn = document.getElementById('quick-matching-btn');
    
    // Check if commission type is matching
    const commissionType = document.getElementById('type').value;
    
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
        if (practicalExample) practicalExample.style.display = 'block';
        
        // Show matching guide and quick setup if commission type is matching
        if (commissionType === 'matching') {
            if (matchingGuide) matchingGuide.style.display = 'block';
            if (quickMatchingBtn) quickMatchingBtn.style.display = 'inline-block';
        }
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
        if (practicalExample) practicalExample.style.display = 'none';
        if (matchingGuide) matchingGuide.style.display = 'none';
        if (quickMatchingBtn) quickMatchingBtn.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    handleTypeChange();
    handleCalculationChange();
    handleLevelsChange();
    toggleMultiLevel(); // Initialize multi-level toggle state
    
    // Add event listeners to prevent reference errors
    const typeSelect = document.getElementById('type');
    if (typeSelect) {
        typeSelect.addEventListener('change', handleTypeChange);
    }
    
    const calculationTypeSelect = document.getElementById('calculation_type');
    if (calculationTypeSelect) {
        calculationTypeSelect.addEventListener('change', handleCalculationChange);
    }
    
    const maxLevelsSelect = document.getElementById('max_levels');
    if (maxLevelsSelect) {
        maxLevelsSelect.addEventListener('change', handleLevelsChange);
    }
    
    const multiLevelCheckbox = document.getElementById('enable_multi_level');
    if (multiLevelCheckbox) {
        multiLevelCheckbox.addEventListener('change', toggleMultiLevel);
    }
    
    // Handle leg calculation basis change
    const legCalculationBasis = document.getElementById('leg_calculation_basis');
    if (legCalculationBasis) {
        legCalculationBasis.addEventListener('change', function() {
            const leftLabel = document.getElementById('left_leg_label');
            const rightLabel = document.getElementById('right_leg_label');
            const leftInput = document.getElementById('min_left_volume');
            const rightInput = document.getElementById('min_right_volume');
            
            if (this.value === 'points') {
                if (leftLabel) leftLabel.textContent = 'Min Left Points';
                if (rightLabel) rightLabel.textContent = 'Min Right Points';
                if (leftInput) leftInput.placeholder = '500';
                if (rightInput) rightInput.placeholder = '500';
            } else {
                if (leftLabel) leftLabel.textContent = 'Min Left Volume (৳)';
                if (rightLabel) rightLabel.textContent = 'Min Right Volume (৳)';
                if (leftInput) leftInput.placeholder = '5000';
                if (rightInput) rightInput.placeholder = '5000';
            }
        });
    }
    
    // Add tooltip information for point system
    const pointBasedOptions = document.querySelectorAll('option[value="points"]');
    pointBasedOptions.forEach(option => {
        option.title = 'Point-based calculation: 1 Point = 6 Tk, 10% matching rate, 100 points minimum per leg';
    });
});
</script>
@endpush
