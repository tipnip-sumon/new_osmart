@extends('member.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row my-4">
        <div class="col-lg-8 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create New Investment</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('member.invest.store') }}" method="POST" id="investmentForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="plan_id" class="form-label">Select Investment Plan <span class="text-danger">*</span></label>
                            <select name="plan_id" id="plan_id" class="form-select @error('plan_id') is-invalid @enderror" required>
                                <option value="">Choose a plan...</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" 
                                            data-minimum="{{ $plan->minimum }}" 
                                            data-maximum="{{ $plan->maximum }}"
                                            data-interest="{{ $plan->interest }}"
                                            data-time="{{ $plan->time }}"
                                            data-time-name="{{ $plan->time_name }}"
                                            data-capital-back="{{ $plan->capital_back ? 'true' : 'false' }}"
                                            {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} - {{ $plan->interest }}% for {{ $plan->time }} {{ $plan->time_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Investment Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" 
                                       name="amount" 
                                       id="amount" 
                                       class="form-control @error('amount') is-invalid @enderror" 
                                       step="0.01" 
                                       min="0.01" 
                                       placeholder="Enter amount"
                                       value="{{ old('amount') }}"
                                       required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                <span id="plan-limits" class="text-muted"></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="wallet_type" class="form-label">Payment From <span class="text-danger">*</span></label>
                            <select name="wallet_type" id="wallet_type" class="form-select @error('wallet_type') is-invalid @enderror" required>
                                <option value="">Select wallet...</option>
                                <option value="deposit_wallet" {{ old('wallet_type') == 'deposit_wallet' ? 'selected' : '' }}>
                                    Deposit Wallet (Balance: {{ formatCurrency(auth()->user()->deposit_wallet ?? 0) }})
                                </option>
                                <option value="main" {{ old('wallet_type') == 'main' ? 'selected' : '' }}>
                                    Main Wallet (Balance: {{ formatCurrency(auth()->user()->balance ?? 0) }})
                                </option>
                                <option value="interest" {{ old('wallet_type') == 'interest' ? 'selected' : '' }}>
                                    Interest Wallet (Balance: {{ formatCurrency(auth()->user()->interest_wallet ?? 0) }})
                                </option>
                                <option value="bonus" {{ old('wallet_type') == 'bonus' ? 'selected' : '' }}>
                                    Bonus Wallet (Balance: {{ formatCurrency(auth()->user()->available_balance ?? 0) }})
                                </option>
                            </select>
                            @error('wallet_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small class="text-muted">Select the wallet to deduct investment amount from</small>
                            </div>
                        </div>

                        <div id="investment-preview" class="alert alert-info d-none">
                            <h6><i class="bx bx-info-circle"></i> Investment Preview:</h6>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <strong>Investment Amount:</strong> ৳<span id="preview-amount">0.00</span>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Interest Rate:</strong> <span id="preview-rate">0</span>%
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Total Interest:</strong> ৳<span id="preview-interest">0.00</span>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Total Return:</strong> ৳<span id="preview-total">0.00</span>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Duration:</strong> <span id="preview-duration">-</span>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Capital Back:</strong> <span id="preview-capital">-</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-chart-line"></i> Create Investment
                            </button>
                            <a href="{{ route('member.invest.dashboard') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-12">
            <!-- Wallet Balances -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title"><i class="bx bx-wallet"></i> Wallet Balances</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="border rounded p-2 text-center">
                                <small class="text-muted d-block">Deposit Wallet</small>
                                <strong class="text-primary">{{ formatCurrency(auth()->user()->deposit_wallet ?? 0) }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2 text-center">
                                <small class="text-muted d-block">Main Wallet</small>
                                <strong class="text-success">{{ formatCurrency(auth()->user()->balance ?? 0) }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2 text-center">
                                <small class="text-muted d-block">Interest Wallet</small>
                                <strong class="text-info">{{ formatCurrency(auth()->user()->interest_wallet ?? 0) }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2 text-center">
                                <small class="text-muted d-block">Bonus Wallet</small>
                                <strong class="text-warning">{{ formatCurrency(auth()->user()->available_balance ?? 0) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Plans -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><i class="bx bx-package"></i> Available Plans</h5>
                </div>
                <div class="card-body">
                    @foreach($plans as $plan)
                        <div class="border rounded p-3 mb-3 plan-card" data-plan-id="{{ $plan->id }}">
                            <h6 class="mb-2 text-primary">{{ $plan->name }}</h6>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <small class="text-muted">Interest Rate:</small>
                                    <div class="fw-bold text-success">{{ $plan->interest }}%</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Duration:</small>
                                    <div class="fw-bold">{{ $plan->time }} {{ $plan->time_name }}</div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <small class="text-muted">Minimum:</small>
                                    <div class="fw-bold">{{ formatCurrency($plan->minimum) }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Maximum:</small>
                                    <div class="fw-bold">{{ formatCurrency($plan->maximum) }}</div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                @if($plan->capital_back)
                                    <small class="text-success"><i class="bx bx-check"></i> Capital Back</small>
                                @else
                                    <small class="text-muted">No Capital Back</small>
                                @endif
                                <button type="button" class="btn btn-sm btn-outline-primary select-plan" data-plan-id="{{ $plan->id }}">
                                    Select
                                </button>
                            </div>
                            @if($plan->description)
                                <hr class="my-2">
                                <small class="text-muted">{{ $plan->description }}</small>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const planSelect = document.getElementById('plan_id');
    const amountInput = document.getElementById('amount');
    const planLimits = document.getElementById('plan-limits');
    const preview = document.getElementById('investment-preview');

    function updatePlanLimits() {
        const selectedOption = planSelect.options[planSelect.selectedIndex];
        if (selectedOption.value) {
            const minimum = parseFloat(selectedOption.dataset.minimum);
            const maximum = parseFloat(selectedOption.dataset.maximum);
            planLimits.innerHTML = `<i class="bx bx-info-circle"></i> Minimum: ৳${minimum.toLocaleString()} - Maximum: ৳${maximum.toLocaleString()}`;
            
            amountInput.min = minimum;
            amountInput.max = maximum;
            
            // Highlight selected plan card
            document.querySelectorAll('.plan-card').forEach(card => card.classList.remove('border-primary'));
            const selectedCard = document.querySelector(`[data-plan-id="${selectedOption.value}"]`);
            if (selectedCard) {
                selectedCard.classList.add('border-primary');
            }
        } else {
            planLimits.textContent = '';
            document.querySelectorAll('.plan-card').forEach(card => card.classList.remove('border-primary'));
        }
        updatePreview();
    }

    function updatePreview() {
        const selectedOption = planSelect.options[planSelect.selectedIndex];
        const amount = parseFloat(amountInput.value) || 0;
        
        if (selectedOption.value && amount > 0) {
            const interest = parseFloat(selectedOption.dataset.interest);
            const time = selectedOption.dataset.time;
            const timeName = selectedOption.dataset.timeName;
            const capitalBack = selectedOption.dataset.capitalBack === 'true';
            
            const interestAmount = (amount * interest) / 100;
            const total = amount + interestAmount;
            
            document.getElementById('preview-amount').textContent = amount.toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('preview-rate').textContent = interest;
            document.getElementById('preview-interest').textContent = interestAmount.toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('preview-total').textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('preview-duration').textContent = `${time} ${timeName}`;
            document.getElementById('preview-capital').innerHTML = capitalBack ? 
                '<span class="text-success"><i class="bx bx-check"></i> Yes</span>' : 
                '<span class="text-muted"><i class="bx bx-x"></i> No</span>';
            
            preview.classList.remove('d-none');
        } else {
            preview.classList.add('d-none');
        }
    }

    // Event listeners
    planSelect.addEventListener('change', updatePlanLimits);
    amountInput.addEventListener('input', updatePreview);

    // Plan card selection
    document.querySelectorAll('.select-plan').forEach(button => {
        button.addEventListener('click', function() {
            const planId = this.dataset.planId;
            planSelect.value = planId;
            updatePlanLimits();
        });
    });

    // Form validation
    document.getElementById('investmentForm').addEventListener('submit', function(e) {
        const selectedOption = planSelect.options[planSelect.selectedIndex];
        const amount = parseFloat(amountInput.value) || 0;
        
        if (selectedOption.value && amount > 0) {
            const minimum = parseFloat(selectedOption.dataset.minimum);
            const maximum = parseFloat(selectedOption.dataset.maximum);
            
            if (amount < minimum || amount > maximum) {
                e.preventDefault();
                alert(`Investment amount must be between ৳${minimum.toLocaleString()} and ৳${maximum.toLocaleString()}`);
                return false;
            }
        }
    });

    // Initialize on page load
    updatePlanLimits();
});
</script>

<style>
.plan-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.plan-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.plan-card.border-primary {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}
</style>
@endsection
