@extends('user.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create New Investment</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.investments.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="plan_id" class="form-label">Select Investment Plan</label>
                            <select name="plan_id" id="plan_id" class="form-select @error('plan_id') is-invalid @enderror" required>
                                <option value="">Choose a plan...</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" 
                                            data-minimum="{{ $plan->minimum }}" 
                                            data-maximum="{{ $plan->maximum }}"
                                            data-interest="{{ $plan->interest }}">
                                        {{ $plan->name }} - {{ $plan->interest }}% for {{ $plan->time }} {{ $plan->time_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Investment Amount</label>
                            <input type="number" 
                                   name="amount" 
                                   id="amount" 
                                   class="form-control @error('amount') is-invalid @enderror" 
                                   step="0.01" 
                                   min="0.01" 
                                   placeholder="Enter amount"
                                   required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <span id="plan-limits" class="text-muted"></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="wallet_type" class="form-label">Payment From</label>
                            <select name="wallet_type" id="wallet_type" class="form-select @error('wallet_type') is-invalid @enderror" required>
                                <option value="">Select wallet...</option>
                                <option value="main">Main Wallet</option>
                                <option value="interest">Interest Wallet</option>
                                <option value="bonus">Bonus Wallet</option>
                            </select>
                            @error('wallet_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="investment-preview" class="alert alert-info d-none">
                            <h6>Investment Preview:</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Investment Amount:</strong> $<span id="preview-amount">0.00</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Expected Interest:</strong> $<span id="preview-interest">0.00</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Total Return:</strong> $<span id="preview-total">0.00</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Profit:</strong> $<span id="preview-profit">0.00</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-chart-line"></i> Create Investment
                            </button>
                            <a href="{{ route('user.investments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Available Plans</h5>
                </div>
                <div class="card-body">
                    @foreach($plans as $plan)
                        <div class="border rounded p-3 mb-3">
                            <h6 class="mb-2">{{ $plan->name }}</h6>
                            <p class="mb-1"><strong>Interest:</strong> {{ $plan->interest }}%</p>
                            <p class="mb-1"><strong>Duration:</strong> {{ $plan->time }} {{ $plan->time_name }}</p>
                            <p class="mb-1"><strong>Min:</strong> ${{ number_format($plan->minimum, 2) }}</p>
                            <p class="mb-0"><strong>Max:</strong> ${{ number_format($plan->maximum, 2) }}</p>
                            @if($plan->capital_back)
                                <small class="text-success"><i class="fas fa-check"></i> Capital Back</small>
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
            const minimum = selectedOption.dataset.minimum;
            const maximum = selectedOption.dataset.maximum;
            planLimits.textContent = `Minimum: $${minimum} - Maximum: $${maximum}`;
            
            amountInput.min = minimum;
            amountInput.max = maximum;
        } else {
            planLimits.textContent = '';
        }
        updatePreview();
    }

    function updatePreview() {
        const selectedOption = planSelect.options[planSelect.selectedIndex];
        const amount = parseFloat(amountInput.value) || 0;
        
        if (selectedOption.value && amount > 0) {
            const interest = parseFloat(selectedOption.dataset.interest);
            const interestAmount = (amount * interest) / 100;
            const total = amount + interestAmount;
            
            document.getElementById('preview-amount').textContent = amount.toFixed(2);
            document.getElementById('preview-interest').textContent = interestAmount.toFixed(2);
            document.getElementById('preview-total').textContent = total.toFixed(2);
            document.getElementById('preview-profit').textContent = interestAmount.toFixed(2);
            
            preview.classList.remove('d-none');
        } else {
            preview.classList.add('d-none');
        }
    }

    planSelect.addEventListener('change', updatePlanLimits);
    amountInput.addEventListener('input', updatePreview);
});
</script>
@endsection
