@extends('member.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-plus-circle me-3 fs-4"></i>
                            <div>
                                <h4 class="mb-0">Add Fund</h4>
                                <p class="mb-0 opacity-75">Add money to your wallet using various payment methods</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('member.fund-history') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-history me-1"></i> Fund History
                            </a>
                            <a href="{{ route('member.wallet') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-wallet me-1"></i> My Wallet
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Balance Card -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-success bg-opacity-10 text-success mx-auto mb-3">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3 class="fw-bold text-success">৳{{ number_format($user->total_wallet_balance, 2) }}</h3>
                    <p class="text-muted mb-0">Current Balance</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary mx-auto mb-3">
                        <i class="fas fa-coins"></i>
                    </div>
                    <h3 class="fw-bold text-primary">৳{{ number_format($user->deposit_wallet ?? 0, 2) }}</h3>
                    <p class="text-muted mb-0">Deposit Wallet</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-info bg-opacity-10 text-info mx-auto mb-3">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="fw-bold text-info">৳{{ number_format($user->available_balance ?? 0, 2) }}</h3>
                    <p class="text-muted mb-0">Available Balance</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card text-primary me-2"></i>
                        Select Payment Method
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($paymentMethods as $key => $method)
                            <div class="col-md-4">
                                <div class="payment-method-card" data-method="{{ $key }}" onclick="selectPaymentMethod('{{ $key }}')">
                                    <div class="card h-100 border-2 payment-option">
                                        <div class="card-body text-center">
                                            <div class="payment-icon mb-3">
                                                @switch($key)
                                                    @case('bkash')
                                                        <div class="icon-box bg-pink text-white">
                                                            <i class="fas fa-mobile-alt"></i>
                                                        </div>
                                                        @break
                                                    @case('nagad')
                                                        <div class="icon-box bg-orange text-white">
                                                            <i class="fas fa-mobile-alt"></i>
                                                        </div>
                                                        @break
                                                    @case('rocket')
                                                        <div class="icon-box bg-purple text-white">
                                                            <i class="fas fa-rocket"></i>
                                                        </div>
                                                        @break
                                                    @case('bank_transfer')
                                                        <div class="icon-box bg-dark text-white">
                                                            <i class="fas fa-university"></i>
                                                        </div>
                                                        @break
                                                    @case('upay')
                                                        <div class="icon-box bg-teal text-white">
                                                            <i class="fas fa-mobile-alt"></i>
                                                        </div>
                                                        @break
                                                @endswitch
                                            </div>
                                            <h6 class="fw-bold">{{ $method['name'] }}</h6>
                                            <p class="text-muted small mb-2">{{ $method['description'] }}</p>
                                            <div class="method-details">
                                                <div class="fee-info">
                                                    <span class="badge bg-warning">Fee: {{ $method['fee_amount'] }}%</span>
                                                </div>
                                                <div class="limit-info mt-2">
                                                    <small class="text-muted">
                                                        Min: ৳{{ number_format($method['min_amount']) }} - 
                                                        Max: ৳{{ number_format($method['max_amount']) }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Location & Vendor Information -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-map-marker-alt text-info me-2"></i>
                        Fund Request Options
                    </h5>
                </div>
                <div class="card-body">
                    <!-- User Location Info -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Your Location:</h6>
                        <span class="badge bg-info fs-6">{{ $userLocationString ?: 'Location not set' }}</span>
                        @if(empty($userLocationString))
                            <div class="alert alert-warning mt-2" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Complete your profile:</strong> Please update your country, district, and upazila in your profile to see local vendor options.
                            </div>
                        @endif
                    </div>

                    @if($hasMatchingVendors)
                        <!-- Local Vendors Available -->
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Great news!</strong> We found {{ count($matchingVendors) }} vendor(s) in your area for fund requests.
                        </div>
                        
                        <h6 class="mb-3">Available Fund Request Options:</h6>
                        <div class="row g-3">
                            @foreach($fundOptions as $index => $option)
                                <div class="col-md-6">
                                    <div class="card border-2 vendor-option" data-vendor-id="{{ $option['vendor_id'] }}" onclick="selectVendor('{{ $option['vendor_id'] }}')">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                @if($option['vendor_id'])
                                                    <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                                                        <i class="fas fa-store"></i>
                                                    </div>
                                                @else
                                                    <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                                                        <i class="fas fa-building"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $option['shop_name'] }}</h6>
                                                    <small class="text-muted">{{ $option['vendor_name'] }}</small>
                                                </div>
                                            </div>
                                            @if($option['shop_description'])
                                                <p class="text-muted small mb-2">{{ $option['shop_description'] }}</p>
                                            @endif
                                            <div class="vendor-details">
                                                @if($option['contact_phone'])
                                                    <small class="d-block text-muted"><i class="fas fa-phone me-1"></i> {{ $option['contact_phone'] }}</small>
                                                @endif
                                                <small class="d-block text-muted"><i class="fas fa-map-marker-alt me-1"></i> {{ $option['location'] }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- No Local Vendors -->
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>No local vendors available</strong> in your area. You can submit a fund request directly to the company.
                        </div>
                        
                        <div class="card border-2 company-option selected" data-vendor-id="">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $fundOptions[0]['shop_name'] ?? 'OSmart BD' }}</h6>
                                        <small class="text-muted">Company Direct Fund Request</small>
                                    </div>
                                </div>
                                <p class="text-muted small mt-2 mb-0">Submit your fund request directly to the company for processing.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Fund Addition Form -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-square text-success me-2"></i>
                        Add Fund Details
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Error Message -->
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Validation Errors -->
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('member.add-fund.store') }}" enctype="multipart/form-data" id="addFundForm">
                        @csrf
                        <input type="hidden" name="payment_method" id="selectedMethod" required>
                        <input type="hidden" name="vendor_id" id="selectedVendor" value="{{ $hasMatchingVendors ? '' : '' }}">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Amount (৳) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" 
                                           placeholder="Enter amount" min="10" max="100000" step="0.01" 
                                           value="{{ old('amount') }}" required>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text" id="amountHelp">
                                    <span id="feeCalculation"></span>
                                </div>
                            </div>

                            <div class="col-md-6" id="senderNumberField">
                                <label for="sender_number" class="form-label">Sender Number *</label>
                                <input type="text" class="form-control @error('sender_number') is-invalid @enderror" id="sender_number" name="sender_number" 
                                       placeholder="01XXXXXXXXX" value="{{ old('sender_number') }}" required>
                                @error('sender_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">The number you're sending money from</div>
                            </div>

                            <!-- Bank Transfer Fields -->
                            <div class="col-md-6" id="bankAccountNumberField" style="display: none;">
                                <label for="bank_account_number" class="form-label">Account Number *</label>
                                <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror" id="bank_account_number" name="bank_account_number" 
                                       placeholder="Enter your account number" value="{{ old('bank_account_number') }}">
                                @error('bank_account_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Your bank account number</div>
                            </div>

                            <div class="col-md-6" id="bankAccountNameField" style="display: none;">
                                <label for="bank_account_name" class="form-label">Account Holder Name *</label>
                                <input type="text" class="form-control @error('bank_account_name') is-invalid @enderror" id="bank_account_name" name="bank_account_name" 
                                       placeholder="Enter account holder name" value="{{ old('bank_account_name') }}">
                                @error('bank_account_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Name as per bank account</div>
                            </div>

                            <div class="col-md-6" id="bankBranchField" style="display: none;">
                                <label for="bank_branch" class="form-label">Branch Name</label>
                                <input type="text" class="form-control @error('bank_branch') is-invalid @enderror" id="bank_branch" name="bank_branch" 
                                       placeholder="Enter branch name" value="{{ old('bank_branch') }}">
                                @error('bank_branch')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Bank branch name (optional)</div>
                            </div>

                            <div class="col-md-6">
                                <label for="transaction_id" class="form-label">Transaction ID *</label>
                                <input type="text" class="form-control @error('transaction_id') is-invalid @enderror" id="transaction_id" name="transaction_id" 
                                       placeholder="Enter transaction ID" value="{{ old('transaction_id') }}" required>
                                @error('transaction_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Transaction ID from your payment app (must be unique)</div>
                            </div>

                            <div class="col-md-6">
                                <label for="receipt" class="form-label">Payment Receipt (Optional)</label>
                                <input type="file" class="form-control" id="receipt" name="receipt" 
                                       accept="image/jpeg,image/png,image/jpg">
                                <div class="form-text">Upload screenshot of payment confirmation</div>
                            </div>

                            <div class="col-12">
                                <div class="payment-instructions" id="paymentInstructions" style="display: none;">
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle me-2"></i>Payment Instructions</h6>
                                        <div id="instructionContent"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex gap-3">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-plus-circle me-1"></i> Submit Fund Request
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary">
                                        <i class="fas fa-undo me-1"></i> Reset Form
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-calculator text-info me-2"></i>
                        Transaction Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="summary-item">
                        <div class="d-flex justify-content-between">
                            <span>Payment Method:</span>
                            <span id="summaryMethod">-</span>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="d-flex justify-content-between">
                            <span>Amount:</span>
                            <span id="summaryAmount">৳0.00</span>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="d-flex justify-content-between">
                            <span>Processing Fee:</span>
                            <span id="summaryFee">৳0.00</span>
                        </div>
                    </div>
                    <hr>
                    <div class="summary-item">
                        <div class="d-flex justify-content-between fw-bold">
                            <span>You'll Receive:</span>
                            <span id="summaryNet" class="text-success">৳0.00</span>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Funds will be credited to your deposit wallet after verification
                        </small>
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-shield-alt text-success fs-2 mb-2"></i>
                        <h6 class="text-success">Secure Transaction</h6>
                        <small class="text-muted">
                            Your transaction is protected with bank-level security
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const paymentMethods = @json($paymentMethods);
let selectedMethodKey = null;

function selectPaymentMethod(method) {
    // Remove previous selection
    document.querySelectorAll('.payment-option').forEach(card => {
        card.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10');
    });
    
    // Add selection to clicked method
    const selectedCard = document.querySelector(`[data-method="${method}"] .payment-option`);
    selectedCard.classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
    
    // Update hidden input
    document.getElementById('selectedMethod').value = method;
    selectedMethodKey = method;
    
    // Update summary
    updateSummary();
    
    // Show payment instructions
    showPaymentInstructions(method);
    
    // Update form validation
    updateFormValidation(method);
}

function selectVendor(vendorId) {
    // Remove previous selection
    document.querySelectorAll('.vendor-option, .company-option').forEach(card => {
        card.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10', 'selected');
    });
    
    // Add selection to clicked vendor
    const selectedCard = document.querySelector(`[data-vendor-id="${vendorId}"]`);
    if (selectedCard) {
        selectedCard.classList.add('border-primary', 'bg-primary', 'bg-opacity-10', 'selected');
    }
    
    // Update hidden input
    document.getElementById('selectedVendor').value = vendorId || '';
    
    // Log for debugging
    console.log('Selected vendor ID:', vendorId || 'Company Direct');
}

function updateSummary() {
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    
    if (selectedMethodKey && paymentMethods[selectedMethodKey]) {
        const method = paymentMethods[selectedMethodKey];
        const feeAmount = parseFloat(method.fee_amount) || 0;
        const fee = (amount * feeAmount) / 100;
        const netAmount = amount - fee;
        
        document.getElementById('summaryMethod').textContent = method.name;
        document.getElementById('summaryAmount').textContent = '৳' + amount.toLocaleString('en-BD', {minimumFractionDigits: 2});
        document.getElementById('summaryFee').textContent = '৳' + fee.toLocaleString('en-BD', {minimumFractionDigits: 2});
        document.getElementById('summaryNet').textContent = '৳' + netAmount.toLocaleString('en-BD', {minimumFractionDigits: 2});
        
        // Update fee calculation help text
        if (amount > 0) {
            document.getElementById('feeCalculation').innerHTML = 
                `Fee: ৳${fee.toFixed(2)} (${feeAmount}%) • You'll receive: <strong>৳${netAmount.toFixed(2)}</strong>`;
        }
    }
}

function showPaymentInstructions(method) {
    const instructions = {
        'bkash': `
            <ol>
                <li>Open your bKash app</li>
                <li>Go to "Send Money"</li>
                <li>Enter personal number: <strong>01787909190</strong></li>
                <li>Enter the exact amount: <strong id="bkashAmount">৳0.00</strong></li>
                <li>Complete the transaction</li>
                <li>Copy the transaction ID and enter it below</li>
            </ol>
        `,
        'nagad': `
            <ol>
                <li>Open your Nagad app</li>
                <li>Go to "Send Money"</li>
                <li>Enter personal number: <strong>01787909190</strong></li>
                <li>Enter the exact amount: <strong id="nagadAmount">৳0.00</strong></li>
                <li>Complete the transaction</li>
                <li>Copy the transaction ID and enter it below</li>
            </ol>
        `,
        'rocket': `
            <ol>
                <li>Dial *322# from your mobile</li>
                <li>Choose "Send Money"</li>
                <li>Enter personal number: <strong>01787909190</strong></li>
                <li>Enter the exact amount: <strong id="rocketAmount">৳0.00</strong></li>
                <li>Enter your PIN to confirm</li>
                <li>Copy the transaction ID and enter it below</li>
            </ol>
        `,
        'bank_transfer': `
            <div class="bank-details">
                <h6>Bank Details:</h6>
                <p><strong>Bank:</strong> Brac Bank PLC<br>
                <strong>Account Name:</strong> Md Thamedul Islam <br>
                <strong>Account Number:</strong> 151220 492 0580001<br>
                <strong>Branch:</strong> Rampura Branch<br>
                <strong>Routing Number:</strong> 090250076</p>
                <p>After transfer, enter the bank transaction reference number below.</p>
            </div>
        `,
        'upay': `
            <ol>
                <li>Open your Upay app</li>
                <li>Go to "Send Money"</li>
                <li>Enter personal number: <strong>01787909190</strong></li>
                <li>Enter the exact amount: <strong id="upayAmount">৳0.00</strong></li>
                <li>Complete the transaction</li>
                <li>Copy the transaction ID and enter it below</li>
            </ol>
        `
    };
    
    document.getElementById('instructionContent').innerHTML = instructions[method] || '';
    document.getElementById('paymentInstructions').style.display = 'block';
}

function updateFormValidation(method) {
    const senderNumberField = document.getElementById('senderNumberField');
    const bankAccountNumberField = document.getElementById('bankAccountNumberField');
    const bankAccountNameField = document.getElementById('bankAccountNameField');
    const bankBranchField = document.getElementById('bankBranchField');
    
    const senderNumberInput = document.getElementById('sender_number');
    const bankAccountNumberInput = document.getElementById('bank_account_number');
    const bankAccountNameInput = document.getElementById('bank_account_name');
    
    if (method === 'bank_transfer') {
        // Hide mobile payment fields
        senderNumberField.style.display = 'none';
        senderNumberInput.required = false;
        senderNumberInput.disabled = true;
        senderNumberInput.value = '';
        
        // Show bank transfer fields
        bankAccountNumberField.style.display = 'block';
        bankAccountNameField.style.display = 'block';
        bankBranchField.style.display = 'block';
        
        // Make bank fields required and enable them
        bankAccountNumberInput.required = true;
        bankAccountNameInput.required = true;
        bankAccountNumberInput.disabled = false;
        bankAccountNameInput.disabled = false;
        document.getElementById('bank_branch').disabled = false;
        
        // Update transaction ID label for bank transfer
        document.querySelector('label[for="transaction_id"]').textContent = 'Reference Number *';
        document.getElementById('transaction_id').placeholder = 'Enter bank reference number';
        document.querySelector('#transaction_id').nextElementSibling.nextElementSibling.textContent = 'Bank transfer reference number';
    } else {
        // Show mobile payment fields
        senderNumberField.style.display = 'block';
        senderNumberInput.required = true;
        senderNumberInput.disabled = false;
        
        // Hide bank transfer fields
        bankAccountNumberField.style.display = 'none';
        bankAccountNameField.style.display = 'none';
        bankBranchField.style.display = 'none';
        
        // Make bank fields not required and clear their values
        bankAccountNumberInput.required = false;
        bankAccountNameInput.required = false;
        bankAccountNumberInput.value = '';
        bankAccountNameInput.value = '';
        document.getElementById('bank_branch').value = '';
        
        // Also disable them so they don't get submitted
        bankAccountNumberInput.disabled = true;
        bankAccountNameInput.disabled = true;
        document.getElementById('bank_branch').disabled = true;
        
        // Reset transaction ID label for mobile payments
        document.querySelector('label[for="transaction_id"]').textContent = 'Transaction ID *';
        document.getElementById('transaction_id').placeholder = 'Enter transaction ID';
        document.querySelector('#transaction_id').nextElementSibling.nextElementSibling.textContent = 'Transaction ID from your payment app (must be unique)';
    }
}

// Event listeners
document.getElementById('amount').addEventListener('input', function() {
    const amount = parseFloat(this.value) || 0;
    
    if (selectedMethodKey && paymentMethods[selectedMethodKey]) {
        const method = paymentMethods[selectedMethodKey];
        
        // Validate amount limits
        if (amount < method.min_amount) {
            this.setCustomValidity(`Minimum amount is ৳${method.min_amount}`);
        } else if (amount > method.max_amount) {
            this.setCustomValidity(`Maximum amount is ৳${method.max_amount}`);
        } else {
            this.setCustomValidity('');
        }
    }
    
    updateSummary();
    
    // Update instruction amounts
    document.querySelectorAll('[id$="Amount"]').forEach(el => {
        el.textContent = '৳' + amount.toLocaleString('en-BD', {minimumFractionDigits: 2});
    });
});

// Form submission validation
document.getElementById('addFundForm').addEventListener('submit', function(e) {
    if (!selectedMethodKey) {
        e.preventDefault();
        alert('Please select a payment method');
        return false;
    }
    
    const amount = parseFloat(document.getElementById('amount').value);
    if (amount <= 0) {
        e.preventDefault();
        alert('Please enter a valid amount');
        return false;
    }
    
    return confirm('Are you sure you want to submit this fund request?');
});

// Reset form
document.querySelector('button[type="reset"]').addEventListener('click', function() {
    selectedMethodKey = null;
    document.querySelectorAll('.payment-option').forEach(card => {
        card.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10');
    });
    document.getElementById('paymentInstructions').style.display = 'none';
    
    // Reset field visibility
    document.getElementById('senderNumberField').style.display = 'block';
    document.getElementById('bankAccountNumberField').style.display = 'none';
    document.getElementById('bankAccountNameField').style.display = 'none';
    document.getElementById('bankBranchField').style.display = 'none';
    
    // Reset field requirements and disabled states
    document.getElementById('sender_number').required = true;
    document.getElementById('sender_number').disabled = false;
    document.getElementById('bank_account_number').required = false;
    document.getElementById('bank_account_number').disabled = true;
    document.getElementById('bank_account_name').required = false;
    document.getElementById('bank_account_name').disabled = true;
    document.getElementById('bank_branch').disabled = true;
    
    // Reset labels
    document.querySelector('label[for="transaction_id"]').textContent = 'Transaction ID *';
    document.getElementById('transaction_id').placeholder = 'Enter transaction ID';
    
    updateSummary();
});

// Service Worker Integration for Fund Pages
if ('serviceWorker' in navigator) {
    // Clear fund-related cache when page loads to ensure fresh data
    navigator.serviceWorker.ready.then(function(registration) {
        if (registration.active) {
            registration.active.postMessage({
                type: 'CLEAR_FUND_CACHE'
            });
        }
    }).catch(function(error) {
        console.warn('Service Worker not ready:', error);
    });
    
    // Handle form submission with offline support
    document.getElementById('addFundForm').addEventListener('submit', function(e) {
        // Check if online
        if (!navigator.onLine) {
            e.preventDefault();
            alert('You are currently offline. Please check your internet connection and try again.');
            return false;
        }
        
        // Store form data in case of network failure
        const formData = new FormData(this);
        const fundData = {
            amount: formData.get('amount'),
            payment_method: formData.get('payment_method'),
            timestamp: new Date().toISOString()
        };
        
        // Store in localStorage for potential retry
        localStorage.setItem('pendingFundRequest', JSON.stringify(fundData));
        
        // Clear stored data on successful submission (will be handled by success page)
        setTimeout(() => {
            if (window.location.href.includes('fund-history')) {
                localStorage.removeItem('pendingFundRequest');
            }
        }, 1000);
    });
    
    // Handle online/offline status
    window.addEventListener('online', function() {
        document.body.classList.remove('offline-mode');
        const statusIndicator = document.getElementById('connectionStatus');
        if (statusIndicator) {
            statusIndicator.style.display = 'none';
        }
    });
    
    window.addEventListener('offline', function() {
        document.body.classList.add('offline-mode');
        
        // Show offline indicator
        let statusIndicator = document.getElementById('connectionStatus');
        if (!statusIndicator) {
            statusIndicator = document.createElement('div');
            statusIndicator.id = 'connectionStatus';
            statusIndicator.className = 'alert alert-warning position-fixed';
            statusIndicator.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            statusIndicator.innerHTML = `
                <i class="fas fa-wifi-slash me-2"></i>
                <strong>You're offline</strong><br>
                <small>Some features may not be available until you reconnect.</small>
            `;
            document.body.appendChild(statusIndicator);
        }
        statusIndicator.style.display = 'block';
    });

    // Initialize vendor selection if no matching vendors (company direct)
    @if(!$hasMatchingVendors)
        selectVendor('');
    @endif
}
</script>
@endpush

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.icon-box {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.payment-method-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.payment-method-card:hover .payment-option {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.payment-option {
    transition: all 0.3s ease;
    cursor: pointer;
}

.payment-icon .icon-box {
    width: 50px;
    height: 50px;
    font-size: 1.3rem;
}

.bg-pink { background-color: #e91e63; }
.bg-orange { background-color: #ff9800; }
.bg-purple { background-color: #9c27b0; }
.bg-teal { background-color: #009688; }

.vendor-option, .company-option {
    cursor: pointer;
    transition: all 0.3s ease;
}

.vendor-option:hover, .company-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.vendor-option.selected, .company-option.selected {
    border-color: #007bff !important;
    background-color: rgba(0, 123, 255, 0.1) !important;
}

.summary-item {
    padding: 0.5rem 0;
}

.form-control:focus,
.form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838 0%, #1ea87a 100%);
    transform: translateY(-1px);
}

.alert-info {
    background-color: #e7f3ff;
    border-color: #b3d9ff;
    color: #0066cc;
}

.bank-details {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    margin-top: 0.5rem;
}

/* Offline Mode Styles */
body.offline-mode {
    position: relative;
}

body.offline-mode::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(1px);
    z-index: 1;
    pointer-events: none;
}

body.offline-mode .card {
    opacity: 0.9;
}

body.offline-mode .btn:not(.btn-secondary):not(.btn-light) {
    opacity: 0.7;
    cursor: not-allowed;
}

#connectionStatus {
    animation: slideInRight 0.3s ease;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@media (max-width: 768px) {
    .payment-method-card {
        margin-bottom: 1rem;
    }
    
    .icon-box {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
    
    .card-header h4 {
        font-size: 1.1rem;
    }
}
</style>
@endpush

