@extends('admin.layouts.app')

@section('title', 'Create Package Link Sharing Setting')

@section('content')
<div class="main-content">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h1 class="page-title fw-semibold fs-18 mb-0">Create Package Link Sharing Setting</h1>
            <div class="">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.package-link-sharing.index') }}">Package Link Sharing</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="ms-auto pageheader-btn">
            <a href="{{ route('admin.package-link-sharing.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Back to List
            </a>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Package Setting Details</div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.package-link-sharing.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Plan Selection -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="plan_id" class="form-label">Select Plan (Recommended)</label>
                                    <select class="form-control @error('plan_id') is-invalid @enderror" 
                                            id="plan_id" name="plan_id">
                                        <option value="">-- Select a Plan --</option>
                                        @foreach($plans as $plan)
                                            <option value="{{ $plan->id }}" 
                                                    data-name="{{ strtolower(str_replace(' ', '_', $plan->name)) }}"
                                                    {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                                {{ $plan->name }} (৳{{ number_format($plan->price, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('plan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Select from existing plans or enter custom package name below</div>
                                </div>
                            </div>

                            <!-- Manual Package Name (fallback) -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="package_name" class="form-label">
                                        Custom Package Name 
                                        <small class="text-muted">(if no plan selected)</small>
                                    </label>
                                    <input type="text" class="form-control @error('package_name') is-invalid @enderror" 
                                           id="package_name" name="package_name" value="{{ old('package_name') }}" 
                                           placeholder="e.g., starter, silver, gold, diamond">
                                    @error('package_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Auto-filled when plan is selected, or enter manually</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="daily_share_limit" class="form-label">Daily Share Limit <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('daily_share_limit') is-invalid @enderror" 
                                           id="daily_share_limit" name="daily_share_limit" value="{{ old('daily_share_limit', 5) }}" 
                                           min="1" max="1000" required>
                                    @error('daily_share_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Maximum links user can share per day</div>
                                </div>
                            </div>

                            <!-- Reward Settings -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="click_reward_amount" class="form-label">Click Reward Amount (TK) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('click_reward_amount') is-invalid @enderror" 
                                           id="click_reward_amount" name="click_reward_amount" 
                                           value="{{ old('click_reward_amount', 2.00) }}" 
                                           min="0" max="1000" step="0.01" required>
                                    @error('click_reward_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Amount earned per unique click</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="daily_earning_limit" class="form-label">Daily Earning Limit (TK) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('daily_earning_limit') is-invalid @enderror" 
                                           id="daily_earning_limit" name="daily_earning_limit" 
                                           value="{{ old('daily_earning_limit', 10.00) }}" 
                                           min="0" max="10000" step="0.01" required>
                                    @error('daily_earning_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Maximum earnings per day (0 for unlimited)</div>
                                </div>
                            </div>

                            <!-- Optional Settings -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="total_share_limit" class="form-label">Total Share Limit (Optional)</label>
                                    <input type="number" class="form-control @error('total_share_limit') is-invalid @enderror" 
                                           id="total_share_limit" name="total_share_limit" 
                                           value="{{ old('total_share_limit') }}" min="1">
                                    @error('total_share_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Total lifetime shares allowed (leave empty for unlimited)</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="is_active" value="0">
                                        <input class="form-check-input" type="checkbox" role="switch" 
                                               id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Status
                                        </label>
                                    </div>
                                    <div class="form-text">Enable/disable this package setting</div>
                                </div>
                            </div>

                            <!-- Advanced Conditions -->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="conditions_json" class="form-label">Additional Conditions (JSON)</label>
                                    <textarea class="form-control @error('conditions_json') is-invalid @enderror" 
                                              id="conditions_json" name="conditions_json" rows="4" 
                                              placeholder='{"min_package_value": 100, "unique_device_only": true, "attribution_hours": 24}'>{{ old('conditions_json') }}</textarea>
                                    @error('conditions_json')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Optional JSON conditions for advanced rules (leave empty if not needed)</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.package-link-sharing.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save"></i> Create Package Setting
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const planSelect = document.getElementById('plan_id');
    const packageNameInput = document.getElementById('package_name');
    const dailyShareLimit = document.getElementById('daily_share_limit');
    const clickReward = document.getElementById('click_reward_amount');
    const dailyEarningLimit = document.getElementById('daily_earning_limit');
    
    // Auto-fill package name when plan is selected
    planSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const planName = selectedOption.getAttribute('data-name');
            packageNameInput.value = planName;
            packageNameInput.readOnly = true;
            packageNameInput.classList.add('bg-light');
        } else {
            packageNameInput.value = '';
            packageNameInput.readOnly = false;
            packageNameInput.classList.remove('bg-light');
        }
    });
    
    // Auto-calculate daily earning limit based on share limit and reward
    function calculateMaxEarning() {
        const shares = parseFloat(dailyShareLimit.value) || 0;
        const reward = parseFloat(clickReward.value) || 0;
        const maxEarning = shares * reward;
        
        if (maxEarning > 0) {
            dailyEarningLimit.placeholder = `Suggested: ${maxEarning.toFixed(2)} TK`;
        }
    }
    
    dailyShareLimit.addEventListener('input', calculateMaxEarning);
    clickReward.addEventListener('input', calculateMaxEarning);
    
    // Initial calculation
    calculateMaxEarning();
    
    // Initialize on page load if plan is pre-selected
    if (planSelect.value) {
        planSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
@endsection
