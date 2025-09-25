@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Fee Settings Management</h4>
                <p class="card-category">Configure transfer, withdrawal, and fund addition fees</p>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('admin.settings.fee.update') }}" method="POST">
                    @csrf
                    
                    <!-- Transfer Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary"><i class="fas fa-exchange-alt"></i> Transfer Settings</h5>
                            <hr>
                        </div>
                        
                        <!-- Transfer from Balance Wallet -->
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Balance Wallet Transfer</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Fee Type</label>
                                        <select name="transfer_balance_fee_type" class="form-select" required>
                                            <option value="fixed" {{ $settings->transfer_balance_fee_type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                            <option value="percentage" {{ $settings->transfer_balance_fee_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Fee Amount</label>
                                        <input type="number" name="transfer_balance_fee_amount" class="form-control" 
                                               value="{{ $settings->transfer_balance_fee_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Minimum Amount</label>
                                        <input type="number" name="transfer_balance_minimum_amount" class="form-control" 
                                               value="{{ $settings->transfer_balance_minimum_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label">Maximum Amount</label>
                                        <input type="number" name="transfer_balance_maximum_amount" class="form-control" 
                                               value="{{ $settings->transfer_balance_maximum_amount }}" step="0.01" min="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Transfer from Deposit Wallet -->
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Deposit Wallet Transfer</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Fee Type</label>
                                        <select name="transfer_deposit_fee_type" class="form-select" required>
                                            <option value="fixed" {{ $settings->transfer_deposit_fee_type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                            <option value="percentage" {{ $settings->transfer_deposit_fee_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Fee Amount</label>
                                        <input type="number" name="transfer_deposit_fee_amount" class="form-control" 
                                               value="{{ $settings->transfer_deposit_fee_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Minimum Amount</label>
                                        <input type="number" name="transfer_deposit_minimum_amount" class="form-control" 
                                               value="{{ $settings->transfer_deposit_minimum_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label">Maximum Amount</label>
                                        <input type="number" name="transfer_deposit_maximum_amount" class="form-control" 
                                               value="{{ $settings->transfer_deposit_maximum_amount }}" step="0.01" min="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Withdrawal Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-success"><i class="fas fa-money-bill-wave"></i> Withdrawal Settings</h5>
                            <hr>
                        </div>
                        
                        <!-- Withdrawal from Balance Wallet -->
                        <div class="col-md-4">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Balance Wallet</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Fee Type</label>
                                        <select name="withdrawal_balance_fee_type" class="form-select" required>
                                            <option value="fixed" {{ $settings->withdrawal_balance_fee_type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                            <option value="percentage" {{ $settings->withdrawal_balance_fee_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Fee Amount</label>
                                        <input type="number" name="withdrawal_balance_fee_amount" class="form-control" 
                                               value="{{ $settings->withdrawal_balance_fee_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Minimum Amount</label>
                                        <input type="number" name="withdrawal_balance_minimum_amount" class="form-control" 
                                               value="{{ $settings->withdrawal_balance_minimum_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label">Maximum Amount</label>
                                        <input type="number" name="withdrawal_balance_maximum_amount" class="form-control" 
                                               value="{{ $settings->withdrawal_balance_maximum_amount }}" step="0.01" min="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Withdrawal from Deposit Wallet -->
                        <div class="col-md-4">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Deposit Wallet</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Fee Type</label>
                                        <select name="withdrawal_deposit_fee_type" class="form-select" required>
                                            <option value="fixed" {{ $settings->withdrawal_deposit_fee_type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                            <option value="percentage" {{ $settings->withdrawal_deposit_fee_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Fee Amount</label>
                                        <input type="number" name="withdrawal_deposit_fee_amount" class="form-control" 
                                               value="{{ $settings->withdrawal_deposit_fee_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Minimum Amount</label>
                                        <input type="number" name="withdrawal_deposit_minimum_amount" class="form-control" 
                                               value="{{ $settings->withdrawal_deposit_minimum_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label">Maximum Amount</label>
                                        <input type="number" name="withdrawal_deposit_maximum_amount" class="form-control" 
                                               value="{{ $settings->withdrawal_deposit_maximum_amount }}" step="0.01" min="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Withdrawal from Interest Wallet -->
                        <div class="col-md-4">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Interest Wallet</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Fee Type</label>
                                        <select name="withdrawal_interest_fee_type" class="form-select" required>
                                            <option value="fixed" {{ $settings->withdrawal_interest_fee_type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                            <option value="percentage" {{ $settings->withdrawal_interest_fee_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Fee Amount</label>
                                        <input type="number" name="withdrawal_interest_fee_amount" class="form-control" 
                                               value="{{ $settings->withdrawal_interest_fee_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Minimum Amount</label>
                                        <input type="number" name="withdrawal_interest_minimum_amount" class="form-control" 
                                               value="{{ $settings->withdrawal_interest_minimum_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label">Maximum Amount</label>
                                        <input type="number" name="withdrawal_interest_maximum_amount" class="form-control" 
                                               value="{{ $settings->withdrawal_interest_maximum_amount }}" step="0.01" min="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fund Addition Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-info"><i class="fas fa-plus-circle"></i> Fund Addition Settings</h5>
                            <hr>
                        </div>
                        
                        <!-- bKash -->
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">bKash</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Fee Type</label>
                                        <select name="fund_bkash_fee_type" class="form-select" required>
                                            <option value="fixed" {{ $settings->fund_bkash_fee_type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                            <option value="percentage" {{ $settings->fund_bkash_fee_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Fee Amount</label>
                                        <input type="number" name="fund_bkash_fee_amount" class="form-control" 
                                               value="{{ $settings->fund_bkash_fee_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Minimum Amount</label>
                                        <input type="number" name="fund_bkash_minimum_amount" class="form-control" 
                                               value="{{ $settings->fund_bkash_minimum_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label">Maximum Amount</label>
                                        <input type="number" name="fund_bkash_maximum_amount" class="form-control" 
                                               value="{{ $settings->fund_bkash_maximum_amount }}" step="0.01" min="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Nagad -->
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Nagad</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Fee Type</label>
                                        <select name="fund_nagad_fee_type" class="form-select" required>
                                            <option value="fixed" {{ $settings->fund_nagad_fee_type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                            <option value="percentage" {{ $settings->fund_nagad_fee_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Fee Amount</label>
                                        <input type="number" name="fund_nagad_fee_amount" class="form-control" 
                                               value="{{ $settings->fund_nagad_fee_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Minimum Amount</label>
                                        <input type="number" name="fund_nagad_minimum_amount" class="form-control" 
                                               value="{{ $settings->fund_nagad_minimum_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label">Maximum Amount</label>
                                        <input type="number" name="fund_nagad_maximum_amount" class="form-control" 
                                               value="{{ $settings->fund_nagad_maximum_amount }}" step="0.01" min="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Rocket -->
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Rocket</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Fee Type</label>
                                        <select name="fund_rocket_fee_type" class="form-select" required>
                                            <option value="fixed" {{ $settings->fund_rocket_fee_type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                            <option value="percentage" {{ $settings->fund_rocket_fee_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Fee Amount</label>
                                        <input type="number" name="fund_rocket_fee_amount" class="form-control" 
                                               value="{{ $settings->fund_rocket_fee_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Minimum Amount</label>
                                        <input type="number" name="fund_rocket_minimum_amount" class="form-control" 
                                               value="{{ $settings->fund_rocket_minimum_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label">Maximum Amount</label>
                                        <input type="number" name="fund_rocket_maximum_amount" class="form-control" 
                                               value="{{ $settings->fund_rocket_maximum_amount }}" step="0.01" min="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bank Transfer -->
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Bank Transfer</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Fee Type</label>
                                        <select name="fund_bank_fee_type" class="form-select" required>
                                            <option value="fixed" {{ $settings->fund_bank_fee_type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                            <option value="percentage" {{ $settings->fund_bank_fee_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Fee Amount</label>
                                        <input type="number" name="fund_bank_fee_amount" class="form-control" 
                                               value="{{ $settings->fund_bank_fee_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Minimum Amount</label>
                                        <input type="number" name="fund_bank_minimum_amount" class="form-control" 
                                               value="{{ $settings->fund_bank_minimum_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label">Maximum Amount</label>
                                        <input type="number" name="fund_bank_maximum_amount" class="form-control" 
                                               value="{{ $settings->fund_bank_maximum_amount }}" step="0.01" min="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Upay -->
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Upay</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Fee Type</label>
                                        <select name="fund_upay_fee_type" class="form-select" required>
                                            <option value="fixed" {{ $settings->fund_upay_fee_type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                            <option value="percentage" {{ $settings->fund_upay_fee_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Fee Amount</label>
                                        <input type="number" name="fund_upay_fee_amount" class="form-control" 
                                               value="{{ $settings->fund_upay_fee_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Minimum Amount</label>
                                        <input type="number" name="fund_upay_minimum_amount" class="form-control" 
                                               value="{{ $settings->fund_upay_minimum_amount }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label">Maximum Amount</label>
                                        <input type="number" name="fund_upay_maximum_amount" class="form-control" 
                                               value="{{ $settings->fund_upay_maximum_amount }}" step="0.01" min="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Fee Settings
                            </button>
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Settings
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.card-header h6 {
    font-weight: 600;
    color: #495057;
}

.form-label {
    font-weight: 500;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.card.border {
    border: 1px solid #e3e6f0 !important;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header.bg-light {
    background-color: #f8f9fc !important;
    border-bottom: 1px solid #e3e6f0;
}

.text-primary {
    color: #5a67d8 !important;
}

.text-success {
    color: #48bb78 !important;
}

.text-info {
    color: #38b2ac !important;
}
</style>
@endsection
