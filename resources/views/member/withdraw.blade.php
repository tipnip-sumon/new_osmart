@extends('member.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <!-- Page Header -->
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-money-bill-wave me-3 fs-4"></i>
                        <div>
                            <h4 class="mb-0">Withdraw Funds</h4>
                            <p class="mb-0 opacity-75">Request withdrawal from your wallet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Balance Card -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-wallet text-primary me-2"></i>
                        Available Balance
                    </h5>
                </div>
                <div class="card-body text-center">
                    <h2 class="text-success fw-bold mb-3">৳{{ number_format(($user->deposit_wallet ?? 0) + ($user->interest_wallet ?? 0), 2) }}</h2>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-2">
                                <small class="text-muted d-block">Deposit Wallet</small>
                                <strong class="text-info">৳{{ number_format($user->deposit_wallet ?? 0, 2) }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2">
                                <small class="text-muted d-block">Interest Wallet</small>
                                <strong class="text-success">৳{{ number_format($user->interest_wallet ?? 0, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Withdrawal Limits -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-warning bg-opacity-10">
                    <h6 class="mb-0 text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Withdrawal Limits
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Minimum:</span>
                        <span class="fw-semibold" id="limits-min">৳100.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Maximum:</span>
                        <span class="fw-semibold" id="limits-max">৳50,000.00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Processing Fee:</span>
                        <span class="fw-semibold text-danger" id="limits-fee">৳0.00</span>
                    </div>
                    <small class="text-muted mt-1 d-block">
                        <i class="fas fa-info-circle"></i> Limits & fee update based on wallet type
                    </small>
                </div>
            </div>
        </div>

        <!-- Withdrawal Form -->
        <div class="col-md-8">
            <!-- Verification Status Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-shield-alt text-info me-2"></i>
                        Verification Requirements
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Email Verification -->
                        <div class="col-md-4">
                            <div class="verification-item">
                                <div class="d-flex align-items-center">
                                    @if(isset($verificationStatus) && $verificationStatus['email_verified'])
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span class="text-success fw-semibold">Email Verified</span>
                                    @else
                                        <i class="fas fa-times-circle text-danger me-2"></i>
                                        <span class="text-danger fw-semibold">Email Not Verified</span>
                                    @endif
                                </div>
                                @if(!isset($verificationStatus) || !$verificationStatus['email_verified'])
                                    <small class="text-muted">Please verify your email address</small>
                                @endif
                            </div>
                        </div>
                        
                        <!-- KYC Verification -->
                        <div class="col-md-4">
                            <div class="verification-item">
                                <div class="d-flex align-items-center">
                                    @if(isset($verificationStatus) && $verificationStatus['kyc_verified'])
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span class="text-success fw-semibold">KYC Verified</span>
                                    @else
                                        <i class="fas fa-times-circle text-danger me-2"></i>
                                        <span class="text-danger fw-semibold">KYC Required</span>
                                    @endif
                                </div>
                                @if(!isset($verificationStatus) || !$verificationStatus['kyc_verified'])
                                    <small class="text-muted">Complete KYC verification</small>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Profile Completion -->
                        <div class="col-md-4">
                            <div class="verification-item">
                                <div class="d-flex align-items-center">
                                    @if(isset($verificationStatus) && $verificationStatus['profile_complete'])
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span class="text-success fw-semibold">Profile Complete</span>
                                    @else
                                        <i class="fas fa-times-circle text-danger me-2"></i>
                                        <span class="text-danger fw-semibold">Profile Incomplete</span>
                                    @endif
                                </div>
                                @if(!isset($verificationStatus) || !$verificationStatus['profile_complete'])
                                    <small class="text-muted">Complete your profile information</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if(!isset($verificationStatus) || !$verificationStatus['can_withdraw'])
                        <div class="alert alert-warning mt-3 mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Withdrawal Disabled:</strong> Please complete all verification requirements above before making withdrawal requests.
                            <div class="mt-2">
                                @if(!isset($verificationStatus) || !$verificationStatus['email_verified'])
                                    <a href="{{ route('member.email.verify.notice') }}" class="btn btn-outline-primary btn-sm me-2">Verify Email</a>
                                @endif
                                @if(!isset($verificationStatus) || !$verificationStatus['kyc_verified'])
                                    <a href="{{ route('member.kyc.index') }}" class="btn btn-outline-info btn-sm me-2">Complete KYC</a>
                                @endif
                                @if(!isset($verificationStatus) || !$verificationStatus['profile_complete'])
                                    <a href="{{ route('member.profile') }}" class="btn btn-outline-success btn-sm">Update Profile</a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="alert alert-success mt-3 mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>All Verified!</strong> You can now make withdrawal requests.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-credit-card text-success me-2"></i>
                        Withdrawal Request
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('member.withdraw.store') }}" method="POST" id="withdrawalForm">
                        @csrf
                        
                        @if(!isset($verificationStatus) || !$verificationStatus['can_withdraw'])
                            <div class="overlay-disabled">
                                <div class="text-center p-4">
                                    <i class="fas fa-lock text-muted mb-3" style="font-size: 2rem;"></i>
                                    <h5 class="text-muted">Withdrawal Form Locked</h5>
                                    <p class="text-muted">Complete all verification requirements to unlock the withdrawal form.</p>
                                </div>
                            </div>
                        @endif
                        
                        <div class="withdrawal-form-content {{ (!isset($verificationStatus) || !$verificationStatus['can_withdraw']) ? 'form-disabled' : '' }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="wallet_type" class="form-label">
                                            <i class="fas fa-wallet me-1"></i>
                                            Withdraw From
                                        </label>
                                        <select class="form-select" id="wallet_type" name="wallet_type" required 
                                                {{ (!isset($verificationStatus) || !$verificationStatus['can_withdraw']) ? 'disabled' : '' }}>
                                            <option value="">Select Wallet</option>
                                            <option value="deposit_wallet">Deposit Wallet (৳{{ number_format($user->deposit_wallet ?? 0, 2) }})</option>
                                            <option value="interest_wallet">Interest Wallet (৳{{ number_format($user->interest_wallet ?? 0, 2) }})</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">
                                            <i class="fas fa-taka-sign me-1"></i>
                                            Amount (৳)
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">৳</span>
                                            <input type="number" class="form-control" id="amount" name="amount" 
                                                   placeholder="Enter amount" min="100" max="50000" step="0.01" required
                                                   {{ (!isset($verificationStatus) || !$verificationStatus['can_withdraw']) ? 'disabled' : '' }}>
                                        </div>
                                        <small class="text-muted">
                                            Net amount: <span id="net-amount" class="fw-semibold">৳0.00</span> | 
                                            Fee: <span id="display-fee" class="fw-semibold text-danger">৳0.00</span>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label">
                                            <i class="fas fa-credit-card me-1"></i>
                                            Payment Method
                                        </label>
                                        <select class="form-select" id="payment_method" name="payment_method" required
                                                {{ (!isset($verificationStatus) || !$verificationStatus['can_withdraw']) ? 'disabled' : '' }}>
                                            <option value="">Select Method</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="bkash">bKash</option>
                                            <option value="nagad">Nagad</option>
                                            <option value="rocket">Rocket</option>
                                            <option value="mobile_banking">Mobile Banking</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="account_number" class="form-label">
                                            <i class="fas fa-hashtag me-1"></i>
                                            Account Number/Mobile
                                        </label>
                                        <input type="text" class="form-control" id="account_number" name="account_number" 
                                               placeholder="Enter account number" required
                                               {{ (!isset($verificationStatus) || !$verificationStatus['can_withdraw']) ? 'disabled' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="account_name" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Account Holder Name
                                </label>
                                <input type="text" class="form-control" id="account_name" name="account_name" 
                                       placeholder="Enter account holder name" required
                                       {{ (!isset($verificationStatus) || !$verificationStatus['can_withdraw']) ? 'disabled' : '' }}>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock me-1"></i>
                                    Confirm Password
                                </label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
                                       placeholder="Enter your password to confirm" required
                                       {{ (!isset($verificationStatus) || !$verificationStatus['can_withdraw']) ? 'disabled' : '' }}>
                                <small class="text-muted">Please enter your account password to confirm this withdrawal request</small>
                            </div>

                            <div class="mb-4">
                                <label for="note" class="form-label">
                                    <i class="fas fa-sticky-note me-1"></i>
                                    Note (Optional)
                                </label>
                                <textarea class="form-control" id="note" name="note" rows="3" 
                                          placeholder="Any additional information..."
                                          {{ (!isset($verificationStatus) || !$verificationStatus['can_withdraw']) ? 'disabled' : '' }}></textarea>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="agree_terms" required
                                           {{ (!isset($verificationStatus) || !$verificationStatus['can_withdraw']) ? 'disabled' : '' }}>
                                    <label class="form-check-label" for="agree_terms">
                                        I agree to the <a href="#" class="text-primary">withdrawal terms</a>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg px-4"
                                        {{ (!isset($verificationStatus) || !$verificationStatus['can_withdraw']) ? 'disabled' : '' }}>
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Submit Request
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Withdrawals -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-history text-info me-2"></i>
                        Recent Withdrawal Requests
                    </h5>
                    <a href="{{ route('member.withdraw.history') }}" class="btn btn-outline-primary btn-sm">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($withdrawals) && $withdrawals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Request ID</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($withdrawals->take(5) as $withdrawal)
                                        <tr>
                                            <td class="fw-semibold">#{{ $withdrawal->id }}</td>
                                            <td>
                                                <span class="fw-semibold text-primary">৳{{ number_format($withdrawal->amount, 2) }}</span>
                                                @if($withdrawal->fee > 0)
                                                    <br><small class="text-muted">Fee: ৳{{ number_format($withdrawal->fee, 2) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ ucfirst(str_replace('_', ' ', $withdrawal->payment_method)) }}</span>
                                            </td>
                                            <td>
                                                @switch($withdrawal->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                        @break
                                                    @case('approved')
                                                        <span class="badge bg-success">Approved</span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge bg-primary">Completed</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">Unknown</span>
                                                @endswitch
                                            </td>
                                            <td>{{ $withdrawal->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($withdrawal->status == 'pending')
                                                    <button class="btn btn-outline-danger btn-sm" onclick="cancelWithdrawal({{ $withdrawal->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                                <button class="btn btn-outline-info btn-sm" onclick="viewDetails({{ $withdrawal->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox text-muted mb-3" style="font-size: 3rem;"></i>
                            <h5 class="text-muted">No withdrawal requests yet</h5>
                            <p class="text-muted">Your withdrawal history will appear here</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Withdrawal Details Modal -->
<div class="modal fade" id="withdrawalDetailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Withdrawal Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="withdrawalDetailsContent">
                <!-- Dynamic content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
let debounceTimeout;

// Calculate dynamic withdrawal fee
function calculateWithdrawalFee() {
    const amount = document.getElementById('amount').value;
    const walletType = document.getElementById('wallet_type').value;
    
    if (!amount || !walletType || amount <= 0) {
        updateFeeDisplay(0, 0, 0);
        return;
    }

    // Clear previous timeout
    clearTimeout(debounceTimeout);
    
    // Debounce API call
    debounceTimeout = setTimeout(() => {
        fetch(`{{ route('member.withdraw.fee-info') }}?wallet_type=${walletType}&amount=${amount}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                throw new Error('Invalid JSON response');
            }
        })
        .then(data => {
            if (data.success) {
                const feeInfo = data.fee_info;
                const calculatedFee = parseFloat(feeInfo.fee) || 0;
                const netAmount = parseFloat(amount) - calculatedFee;
                updateFeeDisplay(parseFloat(amount), calculatedFee, netAmount, feeInfo);
            } else {
                updateFeeDisplay(parseFloat(amount), 0, parseFloat(amount));
            }
        })
        .catch(error => {
            updateFeeDisplay(parseFloat(amount), 0, parseFloat(amount));
        });
    }, 300);
}

// Update fee display
function updateFeeDisplay(amount, fee, netAmount, feeInfo = null) {
    const feeValue = parseFloat(fee) || 0;
    const netValue = parseFloat(netAmount) || 0;
    const amountValue = parseFloat(amount) || 0;
    
    document.getElementById('display-fee').textContent = '৳' + feeValue.toFixed(2);
    document.getElementById('net-amount').textContent = '৳' + (netValue > 0 ? netValue.toFixed(2) : '0.00');
    
    // Also update the fee in withdrawal limits section
    const limitsFeElement = document.getElementById('limits-fee');
    if (limitsFeElement) {
        limitsFeElement.textContent = '৳' + feeValue.toFixed(2);
    }
    
    // Update min/max limits if feeInfo is provided
    if (feeInfo) {
        const minElement = document.getElementById('limits-min');
        const maxElement = document.getElementById('limits-max');
        
        if (minElement && feeInfo.min_amount !== undefined) {
            minElement.textContent = '৳' + parseFloat(feeInfo.min_amount || 100).toFixed(2);
        }
        
        if (maxElement && feeInfo.max_amount !== undefined) {
            maxElement.textContent = '৳' + parseFloat(feeInfo.max_amount || 50000).toFixed(2);
        }
    }
}

// Event listeners for dynamic fee calculation
document.getElementById('amount').addEventListener('input', calculateWithdrawalFee);
document.getElementById('wallet_type').addEventListener('change', function() {
    const walletType = this.value;
    const amountInput = document.getElementById('amount');
    
    if (walletType) {
        // Update max amount based on selected wallet
        const balances = {
            'deposit_wallet': {{ $user->deposit_wallet ?? 0 }},
            'interest_wallet': {{ $user->interest_wallet ?? 0 }}
        };
        
        const maxAmount = balances[walletType] || 0;
        
        // Get dynamic limits from API first
        fetch(`{{ route('member.withdraw.fee-info') }}?wallet_type=${walletType}&amount=100`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.fee_info) {
                const feeInfo = data.fee_info;
                const minLimit = parseFloat(feeInfo.min_amount) || 100;
                const maxLimit = parseFloat(feeInfo.max_amount) || 50000;
                
                // Update form input attributes
                amountInput.setAttribute('min', minLimit);
                amountInput.setAttribute('max', Math.min(maxAmount, maxLimit));
                
                // Update limits display
                updateFeeDisplay(0, 0, 0, feeInfo);
                
                if (maxAmount < minLimit) {
                    amountInput.setAttribute('disabled', 'disabled');
                    amountInput.value = '';
                    updateFeeDisplay(0, 0, 0, feeInfo);
                    
                    Swal.fire({
                        icon: 'warning',
                        title: 'Insufficient Balance',
                        text: `Insufficient balance in selected wallet. Minimum withdrawal is ৳${minLimit}`,
                        confirmButtonColor: '#667eea'
                    });
                } else {
                    amountInput.removeAttribute('disabled');
                    calculateWithdrawalFee();
                }
            }
        })
        .catch(error => {
            // Fallback to default values
            amountInput.setAttribute('max', Math.min(maxAmount, 50000));
            
            if (maxAmount < 100) {
                amountInput.setAttribute('disabled', 'disabled');
                amountInput.value = '';
                updateFeeDisplay(0, 0, 0);
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Insufficient Balance',
                    text: 'Insufficient balance in selected wallet. Minimum withdrawal is ৳100',
                    confirmButtonColor: '#667eea'
                });
            } else {
                amountInput.removeAttribute('disabled');
                calculateWithdrawalFee();
            }
        });
    } else {
        // Reset to default limits
        updateFeeDisplay(0, 0, 0);
        document.getElementById('limits-min').textContent = '৳100.00';
        document.getElementById('limits-max').textContent = '৳50,000.00';
        amountInput.setAttribute('min', 100);
        amountInput.setAttribute('max', 50000);
    }
});

// Show sample fee calculation on page load
document.addEventListener('DOMContentLoaded', function() {
    // Show sample fee for ৳1000 from deposit_wallet as default with limits
    setTimeout(() => {
        fetch(`{{ route('member.withdraw.fee-info') }}?wallet_type=deposit_wallet&amount=1000`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.fee_info) {
                const feeInfo = data.fee_info;
                const sampleFee = parseFloat(feeInfo.fee) || 0;
                
                // Update limits display
                updateFeeDisplay(0, 0, 0, feeInfo);
                
                // Update fee with sample text
                const limitsFeElement = document.getElementById('limits-fee');
                if (limitsFeElement) {
                    limitsFeElement.innerHTML = '৳' + sampleFee.toFixed(2) + ' <small class="text-muted">(for ৳1000)</small>';
                }
            }
        })
        .catch(error => {
            // Sample fee calculation failed, ignore silently
        });
    }, 500);

    // Form submission with password confirmation
    const withdrawForm = document.querySelector('form[action*="withdraw"]');
    if (withdrawForm) {
        withdrawForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const amount = parseFloat(document.getElementById('amount').value);
            const walletType = document.getElementById('wallet_type').value;
            const paymentMethod = document.getElementById('payment_method').value;
            const accountNumber = document.getElementById('account_number').value;
            const accountName = document.getElementById('account_name').value;
            const agreeTerms = document.getElementById('agree_terms').checked;
            
            // Basic validation
            if (!amount || !walletType || !paymentMethod || !accountNumber || !accountName || !agreeTerms) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please fill in all required fields and agree to the terms.',
                    confirmButtonColor: '#667eea'
                });
                return;
            }
            
            if (amount < 100) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Amount',
                    text: 'Minimum withdrawal amount is ৳100',
                    confirmButtonColor: '#667eea'
                });
                return;
            }
            
            // Get current fee information
            const feeText = document.getElementById('display-fee').textContent;
            const fee = parseFloat(feeText.replace('৳', '')) || 0;
            const netAmount = amount - fee;
            
            // Check balance
            const balances = {
                'balance': {{ $user->balance ?? 0 }},
                'deposit_wallet': {{ $user->deposit_wallet ?? 0 }}
            };
            
            if (amount > balances[walletType]) {
                Swal.fire({
                    icon: 'error',
                    title: 'Insufficient Balance',
                    text: 'You do not have sufficient balance in the selected wallet.',
                    confirmButtonColor: '#667eea'
                });
                return;
            }
            
            // Password confirmation dialog
            Swal.fire({
                title: 'Confirm Withdrawal',
                html: `
                    <div class="text-start mb-3">
                        <p><strong>Withdrawal Details:</strong></p>
                        <p><strong>Withdrawal Amount:</strong> ৳${amount.toFixed(2)}</p>
                        <p><strong>Processing Fee:</strong> ৳${fee.toFixed(2)}</p>
                        <p><strong>Net Amount:</strong> ৳${netAmount.toFixed(2)}</p>
                        <p><strong>Payment Method:</strong> ${paymentMethod.replace('_', ' ').toUpperCase()}</p>
                        <p><strong>Account:</strong> ${accountNumber}</p>
                        <hr>
                        <p class="text-muted">Please enter your password to confirm this withdrawal:</p>
                    </div>
                    <input type="password" id="withdraw-password" class="form-control" placeholder="Enter your password" autocomplete="current-password">
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Confirm Withdrawal',
                cancelButtonText: 'Cancel',
                focusConfirm: false,
                preConfirm: () => {
                    const password = document.getElementById('withdraw-password').value;
                    if (!password) {
                        Swal.showValidationMessage('Please enter your password');
                        return false;
                    }
                    return password;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const password = result.value;
                    
                    // Add password to form
                    const passwordInput = document.createElement('input');
                    passwordInput.type = 'hidden';
                    passwordInput.name = 'password_confirmation';
                    passwordInput.value = password;
                    withdrawForm.appendChild(passwordInput);
                    
                    // Show loading
                    Swal.fire({
                        title: 'Processing Withdrawal...',
                        text: 'Please wait while we process your withdrawal request.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit the form
                    withdrawForm.submit();
                }
            });
        });
    }
});

// View withdrawal details
function viewDetails(withdrawalId) {
    fetch(`{{ route('member.withdraw.details', ':id') }}`.replace(':id', withdrawalId))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('withdrawalDetailsContent').innerHTML = data.html;
                new bootstrap.Modal(document.getElementById('withdrawalDetailsModal')).show();
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error loading withdrawal details',
                confirmButtonColor: '#667eea'
            });
        });
}

// Cancel withdrawal
function cancelWithdrawal(withdrawalId) {
    Swal.fire({
        title: 'Cancel Withdrawal?',
        text: 'Are you sure you want to cancel this withdrawal request?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Cancel It',
        cancelButtonText: 'No, Keep It'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('member.withdraw.cancel', ':id') }}`.replace(':id', withdrawalId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cancelled',
                        text: 'Withdrawal request has been cancelled successfully.',
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error cancelling withdrawal',
                        confirmButtonColor: '#667eea'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error cancelling withdrawal',
                    confirmButtonColor: '#667eea'
                });
            });
        }
    });
}
</script>
@endpush

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stats-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 12px;
    padding: 1rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.icon-box {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-1px);
}

.table-hover tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.05);
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

/* Verification Status Styles */
.verification-item {
    padding: 0.75rem;
    background: rgba(248, 249, 250, 0.5);
    border-radius: 8px;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.verification-item i {
    font-size: 1.1rem;
}

/* Disabled Form Styles */
.form-disabled {
    position: relative;
    opacity: 0.6;
    pointer-events: none;
}

.overlay-disabled {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.95);
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}

.withdrawal-form-content {
    position: relative;
}

/* Custom button styles for verification links */
.btn-outline-primary, 
.btn-outline-info, 
.btn-outline-success {
    border-width: 1px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
}

.btn-outline-info:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
}

.btn-outline-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
}

/* Alert styling improvements */
.alert {
    border-radius: 10px;
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.alert-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    color: #856404;
}

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #a8e6cf 100%);
    color: #155724;
}
</style>
@endpush

