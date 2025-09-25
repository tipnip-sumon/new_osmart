@extends('admin.layouts.app')

@section('title', 'Create New Plan')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create New Plan</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.plans.index') }}">Plans</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.plans.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Basic Information -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Plan Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="Enter plan name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fixed_amount" class="form-label">Fixed Amount (BDT) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('fixed_amount') is-invalid @enderror" 
                                           id="fixed_amount" name="fixed_amount" value="{{ old('fixed_amount') }}" 
                                           placeholder="0.00" required>
                                    @error('fixed_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="minimum" class="form-label">Minimum Amount (BDT) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('minimum') is-invalid @enderror" 
                                           id="minimum" name="minimum" value="{{ old('minimum') }}" 
                                           placeholder="0.00" required>
                                    @error('minimum')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="maximum" class="form-label">Maximum Amount (BDT) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('maximum') is-invalid @enderror" 
                                           id="maximum" name="maximum" value="{{ old('maximum') }}" 
                                           placeholder="0.00" required>
                                    @error('maximum')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Enter plan description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Plan Image Upload -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Plan/Package Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <div class="form-text">
                                Upload an image for this plan/package. Supported formats: JPEG, PNG, JPG, GIF, WebP. Max size: 2MB.
                            </div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Image Preview -->
                            <div id="image-preview" class="mt-2" style="display: none;">
                                <img id="preview-img" src="" alt="Image Preview" 
                                     style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Point System -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Point System</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="points" class="form-label">Points Required <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('points') is-invalid @enderror" 
                                           id="points" name="points" value="{{ old('points', 0) }}" 
                                           placeholder="0" required>
                                    @error('points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="point_value" class="form-label">Point Value (BDT) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('point_value') is-invalid @enderror" 
                                           id="point_value" name="point_value" value="{{ old('point_value', 6.00) }}" 
                                           placeholder="6.00" required>
                                    @error('point_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Total Value</label>
                                    <input type="text" class="form-control" id="total_value" readonly 
                                           placeholder="Points × Point Value">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commission System -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Commission System</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="spot_commission_rate" class="form-label">Spot Commission Rate (%) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('spot_commission_rate') is-invalid @enderror" 
                                           id="spot_commission_rate" name="spot_commission_rate" value="{{ old('spot_commission_rate', 15.00) }}" 
                                           placeholder="15.00" required>
                                    @error('spot_commission_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Set to 0 to use fixed sponsor amount instead</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fixed_sponsor" class="form-label">Fixed Sponsor Amount (BDT) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('fixed_sponsor') is-invalid @enderror" 
                                           id="fixed_sponsor" name="fixed_sponsor" value="{{ old('fixed_sponsor', 0.00) }}" 
                                           placeholder="0.00" required>
                                    @error('fixed_sponsor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Used when spot commission rate is 0</small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <strong>Commission Calculation:</strong><br>
                            <span id="commission_preview">If spot commission rate > 0: (Points × Point Value × Spot Commission Rate) / 100<br>Otherwise: Fixed Sponsor Amount</span>
                        </div>
                    </div>
                </div>

                <!-- Interest Settings -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Interest Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="interest" class="form-label">Interest Rate <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('interest') is-invalid @enderror" 
                                           id="interest" name="interest" value="{{ old('interest', 0) }}" 
                                           placeholder="0.00" required>
                                    @error('interest')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="interest_type" class="form-label">Interest Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('interest_type') is-invalid @enderror" 
                                            id="interest_type" name="interest_type" required>
                                        <option value="1" {{ old('interest_type') == '1' ? 'selected' : '' }}>Percentage (%)</option>
                                        <option value="0" {{ old('interest_type') == '0' ? 'selected' : '' }}>Fixed Amount (BDT)</option>
                                    </select>
                                    @error('interest_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="time" class="form-label">Duration <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('time') is-invalid @enderror" 
                                           id="time" name="time" value="{{ old('time', 0) }}" 
                                           placeholder="0" required>
                                    @error('time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="time_name" class="form-label">Time Unit <span class="text-danger">*</span></label>
                                    <select class="form-select @error('time_name') is-invalid @enderror" 
                                            id="time_name" name="time_name" required>
                                        <option value="days" {{ old('time_name') == 'days' ? 'selected' : '' }}>Days</option>
                                        <option value="weeks" {{ old('time_name') == 'weeks' ? 'selected' : '' }}>Weeks</option>
                                        <option value="months" {{ old('time_name') == 'months' ? 'selected' : '' }}>Months</option>
                                        <option value="years" {{ old('time_name') == 'years' ? 'selected' : '' }}>Years</option>
                                    </select>
                                    @error('time_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="repeat_time" class="form-label">Repeat Time</label>
                                    <input type="number" class="form-control @error('repeat_time') is-invalid @enderror" 
                                           id="repeat_time" name="repeat_time" value="{{ old('repeat_time') }}" 
                                           placeholder="0">
                                    @error('repeat_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Plan Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="status" name="status" {{ old('status') ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">Active Status</label>
                            </div>
                            <small class="text-muted">Enable this plan for users</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="featured" name="featured" {{ old('featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="featured">Featured Plan</label>
                            </div>
                            <small class="text-muted">Show as featured plan</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="point_based" name="point_based" {{ old('point_based') ? 'checked' : '' }}>
                                <label class="form-check-label" for="point_based">Point-Based Plan</label>
                            </div>
                            <small class="text-muted">Uses point system instead of money</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="instant_activation" name="instant_activation" {{ old('instant_activation') ? 'checked' : '' }}>
                                <label class="form-check-label" for="instant_activation">Instant Activation</label>
                            </div>
                            <small class="text-muted">Activate immediately upon joining</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="capital_back" name="capital_back" {{ old('capital_back') ? 'checked' : '' }}>
                                <label class="form-check-label" for="capital_back">Capital Back</label>
                            </div>
                            <small class="text-muted">Return capital to user</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="lifetime" name="lifetime" {{ old('lifetime') ? 'checked' : '' }}>
                                <label class="form-check-label" for="lifetime">Lifetime Plan</label>
                            </div>
                            <small class="text-muted">No expiration date</small>
                        </div>
                    </div>
                </div>

                <!-- Daily Cashback Settings -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Daily Cashback Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="daily_cashback_enabled" name="daily_cashback_enabled" {{ old('daily_cashback_enabled') ? 'checked' : '' }}>
                                <label class="form-check-label" for="daily_cashback_enabled">Enable Daily Cashback</label>
                            </div>
                            <small class="text-muted">Enable daily cashback payments for this plan</small>
                        </div>

                        <div id="cashback_settings" style="display: {{ old('daily_cashback_enabled') ? 'block' : 'none' }};">
                            <div class="mb-3">
                                <label for="daily_cashback_min" class="form-label">Minimum Daily Cashback (৳)</label>
                                <input type="number" step="0.01" class="form-control @error('daily_cashback_min') is-invalid @enderror" 
                                       id="daily_cashback_min" name="daily_cashback_min" value="{{ old('daily_cashback_min', 0) }}" 
                                       placeholder="10.00">
                                @error('daily_cashback_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="daily_cashback_max" class="form-label">Maximum Daily Cashback (৳)</label>
                                <input type="number" step="0.01" class="form-control @error('daily_cashback_max') is-invalid @enderror" 
                                       id="daily_cashback_max" name="daily_cashback_max" value="{{ old('daily_cashback_max', 0) }}" 
                                       placeholder="15.00">
                                @error('daily_cashback_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="cashback_duration_days" class="form-label">Cashback Duration (Days)</label>
                                <input type="number" class="form-control @error('cashback_duration_days') is-invalid @enderror" 
                                       id="cashback_duration_days" name="cashback_duration_days" value="{{ old('cashback_duration_days', 365) }}" 
                                       placeholder="365">
                                @error('cashback_duration_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">0 = unlimited duration</small>
                            </div>

                            <div class="mb-3">
                                <label for="cashback_type" class="form-label">Cashback Type</label>
                                <select class="form-select @error('cashback_type') is-invalid @enderror" 
                                        id="cashback_type" name="cashback_type">
                                    <option value="fixed" {{ old('cashback_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                    <option value="random" {{ old('cashback_type') == 'random' ? 'selected' : '' }}>Random Range</option>
                                    <option value="percentage" {{ old('cashback_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                </select>
                                @error('cashback_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="require_referral_for_cashback" name="require_referral_for_cashback" {{ old('require_referral_for_cashback') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="require_referral_for_cashback">Require Referrals</label>
                                </div>
                                <small class="text-muted">Cashback will be pending until referral requirements are met</small>
                            </div>

                            <div id="referral_conditions" style="display: {{ old('require_referral_for_cashback') ? 'block' : 'none' }};">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="required_direct_referrals" class="form-label">Direct Referrals</label>
                                            <input type="number" class="form-control" 
                                                   id="required_direct_referrals" name="referral_conditions[required_direct_referrals]" 
                                                   value="{{ old('referral_conditions.required_direct_referrals', 0) }}" placeholder="5">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="required_team_members" class="form-label">Team Members</label>
                                            <input type="number" class="form-control" 
                                                   id="required_team_members" name="referral_conditions[required_team_members]" 
                                                   value="{{ old('referral_conditions.required_team_members', 0) }}" placeholder="20">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="required_team_investment" class="form-label">Team Investment (৳)</label>
                                    <input type="number" step="0.01" class="form-control" 
                                           id="required_team_investment" name="referral_conditions[required_team_investment]" 
                                           value="{{ old('referral_conditions.required_team_investment', 0) }}" placeholder="1000">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="is_special_package" name="is_special_package" {{ old('is_special_package') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_special_package">Special Package</label>
                            </div>
                            <small class="text-muted">Mark as special promotional package</small>
                        </div>
                    </div>
                </div>

                <!-- Point System Settings -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Point System Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="minimum_points" class="form-label">Minimum Points Required</label>
                            <input type="number" class="form-control @error('minimum_points') is-invalid @enderror" 
                                   id="minimum_points" name="minimum_points" value="{{ old('minimum_points', 0) }}" 
                                   placeholder="500">
                            @error('minimum_points')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="maximum_points" class="form-label">Maximum Points Required</label>
                            <input type="number" class="form-control @error('maximum_points') is-invalid @enderror" 
                                   id="maximum_points" name="maximum_points" value="{{ old('maximum_points', 0) }}" 
                                   placeholder="10000">
                            @error('maximum_points')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="point_to_taka_rate" class="form-label">Point to Taka Rate</label>
                            <input type="number" step="0.01" class="form-control @error('point_to_taka_rate') is-invalid @enderror" 
                                   id="point_to_taka_rate" name="point_to_taka_rate" value="{{ old('point_to_taka_rate', 1) }}" 
                                   placeholder="1.00">
                            @error('point_to_taka_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">How many taka equals 1 point</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="wallet_purchase" name="wallet_purchase" {{ old('wallet_purchase') ? 'checked' : '' }}>
                                <label class="form-check-label" for="wallet_purchase">Wallet Purchase</label>
                            </div>
                            <small class="text-muted">Allow purchase with wallet balance</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="point_purchase" name="point_purchase" {{ old('point_purchase') ? 'checked' : '' }}>
                                <label class="form-check-label" for="point_purchase">Point Purchase</label>
                            </div>
                            <small class="text-muted">Allow purchase with points</small>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line align-bottom me-1"></i> Create Plan
                            </button>
                            <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line align-bottom me-1"></i> Back to List
                            </a>
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
    const pointsInput = document.getElementById('points');
    const pointValueInput = document.getElementById('point_value');
    const totalValueInput = document.getElementById('total_value');
    const spotCommissionInput = document.getElementById('spot_commission_rate');
    const fixedSponsorInput = document.getElementById('fixed_sponsor');
    const commissionPreview = document.getElementById('commission_preview');

    function updateCalculations() {
        // Update total value
        const points = parseFloat(pointsInput.value) || 0;
        const pointValue = parseFloat(pointValueInput.value) || 0;
        const totalValue = points * pointValue;
        totalValueInput.value = `৳${totalValue.toFixed(2)}`;

        // Update commission preview
        const spotCommission = parseFloat(spotCommissionInput.value) || 0;
        const fixedSponsor = parseFloat(fixedSponsorInput.value) || 0;
        
        let preview = '';
        if (spotCommission > 0) {
            const commissionAmount = (points * pointValue * spotCommission) / 100;
            preview = `Percentage-based: (${points} × ৳${pointValue} × ${spotCommission}%) = ৳${commissionAmount.toFixed(2)}`;
        } else {
            preview = `Fixed amount: ৳${fixedSponsor.toFixed(2)}`;
        }
        
        if (spotCommission > 0 && fixedSponsor > 0) {
            preview += `<br><small class="text-warning">Note: Both values set, percentage will be used</small>`;
        }
        
        commissionPreview.innerHTML = preview;
    }

    // Add event listeners
    pointsInput.addEventListener('input', updateCalculations);
    pointValueInput.addEventListener('input', updateCalculations);
    spotCommissionInput.addEventListener('input', updateCalculations);
    fixedSponsorInput.addEventListener('input', updateCalculations);

    // Initial calculation
    updateCalculations();

    // Image preview functionality
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        
        if (file) {
            // Check file size (2MB limit)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                this.value = '';
                preview.style.display = 'none';
                return;
            }
            
            // Check file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert('Please select a valid image file (JPEG, PNG, JPG, GIF, WebP)');
                this.value = '';
                preview.style.display = 'none';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Daily cashback settings toggle
    const dailyCashbackEnabled = document.getElementById('daily_cashback_enabled');
    const cashbackSettings = document.getElementById('cashback_settings');
    const requireReferral = document.getElementById('require_referral_for_cashback');
    const referralConditions = document.getElementById('referral_conditions');

    dailyCashbackEnabled.addEventListener('change', function() {
        cashbackSettings.style.display = this.checked ? 'block' : 'none';
    });

    requireReferral.addEventListener('change', function() {
        referralConditions.style.display = this.checked ? 'block' : 'none';
    });
});
</script>
@endpush
