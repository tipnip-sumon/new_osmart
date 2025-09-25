@extends('member.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient-success text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exchange-alt me-3 fs-4"></i>
                        <div>
                            <h4 class="mb-0">Transfer Funds</h4>
                            <p class="mb-0 opacity-75">Send money to other members</p>
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
                        <i class="fas fa-wallet text-success me-2"></i>
                        Available Balance
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-3 border-end">
                                <small class="text-muted d-block">Income Wallet</small>
                                <h4 class="text-success fw-bold mb-0">৳{{ number_format($user->interest_wallet ?? 0, 2) }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3">
                                <small class="text-muted d-block">Deposit Wallet</small>
                                <h4 class="text-info fw-bold mb-0">৳{{ number_format($user->deposit_wallet ?? 0, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transfer Limits -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info bg-opacity-10">
                    <h6 class="mb-0 text-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Transfer Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Minimum:</span>
                        <span class="fw-semibold" id="min-amount">৳{{ number_format(10, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Maximum:</span>
                        <span class="fw-semibold" id="max-amount">৳{{ number_format(50000, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Transfer Fee:</span>
                        <span class="fw-semibold text-warning" id="fee-display">Select wallet</span>
                    </div>
                    <hr>
                    <small class="text-muted">
                        <i class="fas fa-lightbulb text-warning me-1"></i>
                        <span id="fee-note">Transfers are processed instantly</span>
                    </small>
                </div>
            </div>

            <!-- Quick User Search -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary bg-opacity-10">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-search me-2"></i>
                        Quick Search
                    </h6>
                </div>
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" class="form-control" id="user-search" 
                               placeholder="Search by name or email">
                        <button class="btn btn-outline-primary" type="button" id="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div id="search-results" class="mt-2"></div>
                </div>
            </div>
        </div>

        <!-- Transfer Form -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-paper-plane text-success me-2"></i>
                        Send Money
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

                    <form action="{{ route('member.transfer.process') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="wallet_type" class="form-label">
                                        <i class="fas fa-wallet me-1"></i>
                                        Transfer From
                                    </label>
                                    <select class="form-select" id="wallet_type" name="wallet_type" required>
                                        <option value="">Select Wallet</option>
                                        <option value="interest_wallet">Income Wallet (৳{{ number_format($user->interest_wallet ?? 0, 2) }}) - FREE Transfer</option>
                                        <option value="deposit_wallet">Deposit Wallet (৳{{ number_format($user->deposit_wallet ?? 0, 2) }})</option>
                                    </select>
                                    <small class="text-muted">
                                        <span id="wallet-fee-info">Select a wallet to see transfer fees</span>
                                    </small>
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
                                               placeholder="Enter amount" min="10" max="50000" step="0.01" required>
                                    </div>
                                    <small class="text-muted">
                                        Total deduction: <span id="total-amount">৳0.00</span>
                                        <br><span id="fee-breakdown">Select wallet type to see fee details</span>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="recipient_identifier" class="form-label">
                                <i class="fas fa-user me-1"></i>
                                Recipient
                            </label>
                            <input type="text" class="form-control" id="recipient_identifier" name="recipient_identifier" 
                                   placeholder="Enter email, phone number, or user ID" required>
                            <small class="text-muted">You can use email address, phone number, or user ID to find the recipient</small>
                        </div>

                        <div class="mb-4">
                            <label for="note" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>
                                Note (Optional)
                            </label>
                            <textarea class="form-control" id="note" name="note" rows="3" 
                                      placeholder="Add a message for the recipient..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="transfer-summary p-3 bg-light rounded">
                                    <h6 class="mb-3">Transfer Summary</h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Transfer Amount:</span>
                                        <span id="summary-amount">৳0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Transfer Fee:</span>
                                        <span id="summary-fee" class="text-warning">৳0.00</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Total Deduction:</span>
                                        <span id="summary-total">৳0.00</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Send Money
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Transfer History Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <ul class="nav nav-tabs card-header-tabs" id="transferTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="sent-tab" data-bs-toggle="tab" data-bs-target="#sent" 
                                    type="button" role="tab">
                                <i class="fas fa-arrow-up text-danger me-1"></i>
                                Sent Transfers
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="received-tab" data-bs-toggle="tab" data-bs-target="#received" 
                                    type="button" role="tab">
                                <i class="fas fa-arrow-down text-success me-1"></i>
                                Received Transfers
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="transferTabsContent">
                        <!-- Sent Transfers -->
                        <div class="tab-pane fade show active" id="sent" role="tabpanel">
                            @if($sentTransfers->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Transaction ID</th>
                                                <th>Recipient</th>
                                                <th>Amount</th>
                                                <th>Fee</th>
                                                <th>Note</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sentTransfers as $transfer)
                                                <tr>
                                                    <td class="fw-semibold">#{{ $transfer->transaction_id }}</td>
                                                    <td>
                                                        @php
                                                            $recipient = \App\Models\User::find($transfer->reference_id);
                                                        @endphp
                                                        @if($recipient)
                                                            <div>
                                                                <div class="fw-semibold">{{ $recipient->name }}</div>
                                                                <small class="text-muted">{{ $recipient->email }}</small>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">User not found</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="fw-semibold text-danger">- ৳{{ number_format($transfer->amount, 2) }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="text-warning">৳{{ number_format($transfer->fee ?? 0, 2) }}</span>
                                                    </td>
                                                    <td>
                                                        @if($transfer->note)
                                                            <span class="badge bg-light text-dark" title="{{ $transfer->note }}">
                                                                {{ Str::limit($transfer->note, 20) }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $transfer->created_at->format('M d, Y g:i A') }}</td>
                                                    <td>
                                                        <span class="badge bg-success">Completed</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-arrow-up text-muted mb-3" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted">No transfers sent yet</h5>
                                    <p class="text-muted">Your outgoing transfers will appear here</p>
                                </div>
                            @endif
                        </div>

                        <!-- Received Transfers -->
                        <div class="tab-pane fade" id="received" role="tabpanel">
                            @if($receivedTransfers->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Transaction ID</th>
                                                <th>Sender</th>
                                                <th>Amount</th>
                                                <th>Note</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($receivedTransfers as $transfer)
                                                <tr>
                                                    <td class="fw-semibold">#{{ $transfer->transaction_id }}</td>
                                                    <td>
                                                        @php
                                                            $sender = \App\Models\User::find($transfer->reference_id);
                                                        @endphp
                                                        @if($sender)
                                                            <div>
                                                                <div class="fw-semibold">{{ $sender->name }}</div>
                                                                <small class="text-muted">{{ $sender->email }}</small>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">User not found</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="fw-semibold text-success">+ ৳{{ number_format($transfer->amount, 2) }}</span>
                                                    </td>
                                                    <td>
                                                        @if($transfer->note)
                                                            <span class="badge bg-light text-dark" title="{{ $transfer->note }}">
                                                                {{ Str::limit($transfer->note, 20) }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $transfer->created_at->format('M d, Y g:i A') }}</td>
                                                    <td>
                                                        <span class="badge bg-success">Completed</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-arrow-down text-muted mb-3" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted">No transfers received yet</h5>
                                    <p class="text-muted">Incoming transfers will appear here</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
// Global variables for fee information
let currentFeeInfo = null;
let walletBalances = {
    'interest_wallet': {{ $user->interest_wallet ?? 0 }},
    'deposit_wallet': {{ $user->deposit_wallet ?? 0 }},
    'balance': {{ $user->balance ?? 0 }}
};

// Calculate total amount with dynamic fee
function updateAmountCalculation() {
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    const fee = currentFeeInfo ? currentFeeInfo.fee : 0;
    const total = amount + fee;
    
    // Always update the display, even if no fee info
    const totalElement = document.getElementById('total-amount');
    const summaryAmountElement = document.getElementById('summary-amount');
    const summaryFeeElement = document.getElementById('summary-fee');
    const summaryTotalElement = document.getElementById('summary-total');
    
    if (totalElement) totalElement.textContent = '৳' + total.toFixed(2);
    if (summaryAmountElement) summaryAmountElement.textContent = '৳' + amount.toFixed(2);
    if (summaryFeeElement) summaryFeeElement.textContent = '৳' + fee.toFixed(2);
    if (summaryTotalElement) summaryTotalElement.textContent = '৳' + total.toFixed(2);
    
    // Update fee breakdown
    const feeBreakdownElement = document.getElementById('fee-breakdown');
    if (feeBreakdownElement) {
        if (currentFeeInfo) {
            if (currentFeeInfo.fee === 0) {
                feeBreakdownElement.innerHTML = '<span class="text-success">✓ No transfer fee for Interest Wallet</span>';
            } else {
                const feeText = currentFeeInfo.fee_type === 'percentage' 
                    ? `${currentFeeInfo.fee_amount}% fee (৳${fee.toFixed(2)})`
                    : `Fixed fee: ৳${fee.toFixed(2)}`;
                feeBreakdownElement.innerHTML = feeText;
            }
        } else {
            feeBreakdownElement.textContent = amount > 0 ? 'Select wallet type to see fee details' : 'Enter amount and select wallet';
        }
    }
}

// Fetch fee information for selected wallet
function fetchFeeInfo(walletType, amount = 100) {
    if (!walletType) return;
    
    fetch(`{{ route('member.transfer.fee-info') }}?wallet_type=${walletType}&amount=${amount}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentFeeInfo = data.fee_info;
                updateFeeDisplay(data);
                updateAmountCalculation();
            }
        })
        .catch(error => {
            console.error('Error fetching fee info:', error);
        });
}

// Update fee display in UI
function updateFeeDisplay(data) {
    const feeInfo = data.fee_info;
    
    // Update fee display in info card
    if (feeInfo.fee === 0) {
        document.getElementById('fee-display').innerHTML = '<span class="text-success">FREE</span>';
        document.getElementById('fee-note').textContent = 'Interest wallet transfers are completely free!';
    } else {
        const feeText = feeInfo.fee_type === 'percentage' 
            ? `${feeInfo.fee_amount}% of amount`
            : `৳${feeInfo.fee_amount} fixed`;
        document.getElementById('fee-display').textContent = feeText;
        document.getElementById('fee-note').textContent = 'Transfers are processed instantly';
    }
    
    // Update wallet fee info
    document.getElementById('wallet-fee-info').innerHTML = data.message;
    
    // Update min/max amounts - Parse as numbers first
    const minAmount = parseFloat(feeInfo.min_amount) || 10;
    const maxAmount = parseFloat(feeInfo.max_amount) || 50000;
    
    document.getElementById('min-amount').textContent = '৳' + minAmount.toFixed(2);
    document.getElementById('max-amount').textContent = '৳' + maxAmount.toFixed(2);
    
    // Update amount input constraints
    const amountInput = document.getElementById('amount');
    amountInput.setAttribute('min', minAmount);
    amountInput.setAttribute('max', maxAmount);
}

// Document ready function
document.addEventListener('DOMContentLoaded', function() {
    // Amount input event listener
    const amountInput = document.getElementById('amount');
    if (amountInput) {
        amountInput.addEventListener('input', function() {
            const walletType = document.getElementById('wallet_type').value;
            const amount = parseFloat(this.value) || 0;
            
            // Always update calculation, even without wallet selected
            updateAmountCalculation();
            
            // If wallet is selected and amount is valid, fetch new fee info
            if (walletType && amount > 0) {
                fetchFeeInfo(walletType, amount);
            }
        });
        
        amountInput.addEventListener('keyup', function() {
            updateAmountCalculation();
        });
    }

    // Wallet type change event listener
    const walletSelect = document.getElementById('wallet_type');
    if (walletSelect) {
        walletSelect.addEventListener('change', function() {
            const walletType = this.value;
            const amountInput = document.getElementById('amount');
            
            if (walletType) {
                // Fetch fee info for selected wallet
                const currentAmount = parseFloat(amountInput.value) || 100;
                fetchFeeInfo(walletType, currentAmount);
                
                // Check balance and enable/disable input
                const availableBalance = walletBalances[walletType] || 0;
                
                if (walletType === 'interest_wallet') {
                    // Interest wallet transfers are free, so only check minimum amount
                    if (availableBalance < 10) {
                        amountInput.setAttribute('disabled', 'disabled');
                        amountInput.value = '';
                        Swal.fire({
                            icon: 'warning',
                            title: 'Insufficient Balance',
                            text: 'Insufficient balance in Interest Wallet. Minimum transfer is ৳10 (FREE transfer)',
                            confirmButtonColor: '#28a745'
                        });
                    } else {
                        amountInput.removeAttribute('disabled');
                        const maxAllowed = Math.min(availableBalance, 50000);
                        amountInput.setAttribute('max', maxAllowed);
                    }
                } else {
                    // Other wallets have fees, so check minimum + fee
                    if (availableBalance < 15) { // Assuming minimum ৳5 fee for now
                        amountInput.setAttribute('disabled', 'disabled');
                        amountInput.value = '';
                        Swal.fire({
                            icon: 'warning',
                            title: 'Insufficient Balance',
                            text: 'Insufficient balance in selected wallet. Please check minimum amount + fees.',
                            confirmButtonColor: '#28a745'
                        });
                    } else {
                        amountInput.removeAttribute('disabled');
                    }
                }
            } else {
                // Reset display when no wallet selected
                currentFeeInfo = null;
                document.getElementById('fee-display').textContent = 'Select wallet';
                document.getElementById('fee-note').textContent = 'Transfers are processed instantly';
                document.getElementById('wallet-fee-info').textContent = 'Select a wallet to see transfer fees';
                document.getElementById('fee-breakdown').textContent = 'Select wallet type to see fee details';
                updateAmountCalculation();
            }
        });
    }

    // User search functionality
    let searchTimeout;
    const userSearchInput = document.getElementById('user-search');
    if (userSearchInput) {
        userSearchInput.addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(searchTimeout);
            
            if (query.length < 3) {
                document.getElementById('search-results').innerHTML = '';
                return;
            }
            
            searchTimeout = setTimeout(() => {
                searchUsers(query);
            }, 500);
        });
    }

    const searchBtn = document.getElementById('search-btn');
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            const query = document.getElementById('user-search').value.trim();
            if (query.length >= 3) {
                searchUsers(query);
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Search Required',
                    text: 'Please enter at least 3 characters to search',
                    confirmButtonColor: '#28a745'
                });
            }
        });
    }
});

function searchUsers(query) {
    const resultsDiv = document.getElementById('search-results');
    resultsDiv.innerHTML = '<div class="text-muted"><i class="fas fa-spinner fa-spin me-1"></i>Searching...</div>';
    
    fetch(`{{ route('member.transfer.search-users') }}?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.users && data.users.length > 0) {
                let html = '<div class="list-group list-group-flush mt-2">';
                data.users.forEach(user => {
                    html += `
                        <a href="javascript:void(0)" class="list-group-item list-group-item-action py-2" 
                           onclick="selectUser('${user.email}', '${user.name}')">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">${user.name}</div>
                                    <small class="text-muted">${user.email}</small>
                                </div>
                                <small class="text-muted">ID: ${user.id}</small>
                            </div>
                        </a>
                    `;
                });
                html += '</div>';
                resultsDiv.innerHTML = html;
            } else {
                resultsDiv.innerHTML = '<div class="text-muted">No users found</div>';
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            resultsDiv.innerHTML = '<div class="text-danger">Error searching users</div>';
        });
}

// Auto-fill recipient when clicking on search results
function selectUser(email, name) {
    document.getElementById('recipient_identifier').value = email;
    document.getElementById('search-results').innerHTML = '';
    document.getElementById('user-search').value = name;
}

// Form submission with password confirmation
document.addEventListener('DOMContentLoaded', function() {
    const transferForm = document.querySelector('form[action*="transfer"]');
    if (transferForm) {
        transferForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const amount = parseFloat(document.getElementById('amount').value);
            const recipientIdentifier = document.getElementById('recipient_identifier').value;
            const walletType = document.getElementById('wallet_type').value;
            const note = document.getElementById('note').value;
            
            // Basic validation
            if (!amount || !recipientIdentifier || !walletType) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please fill in all required fields.',
                    confirmButtonColor: '#28a745'
                });
                return;
            }
            
            if (amount < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Amount',
                    text: 'Minimum transfer amount is ৳1',
                    confirmButtonColor: '#28a745'
                });
                return;
            }
            
            // Get current fee information
            const feeAmount = currentFeeInfo ? currentFeeInfo.fee : 0;
            const total = amount + feeAmount;
            
            // Password confirmation dialog
            Swal.fire({
                title: 'Confirm Transfer',
                html: `
                    <div class="text-start mb-3">
                        <p><strong>Transfer Details:</strong></p>
                        <p><strong>Recipient:</strong> ${recipientIdentifier}</p>
                        <p><strong>Amount:</strong> ৳${amount.toFixed(2)}</p>
                        <p><strong>Transfer Fee:</strong> ৳${feeAmount.toFixed(2)}</p>
                        <p><strong>Total Deduction:</strong> ৳${total.toFixed(2)}</p>
                        ${note ? `<p><strong>Note:</strong> ${note}</p>` : ''}
                        <hr>
                        <p class="text-muted">Please enter your password to confirm this transfer:</p>
                    </div>
                    <input type="password" id="transfer-password" class="form-control" placeholder="Enter your password" autocomplete="current-password">
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Confirm Transfer',
                cancelButtonText: 'Cancel',
                focusConfirm: false,
                preConfirm: () => {
                    const password = document.getElementById('transfer-password').value;
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
                    transferForm.appendChild(passwordInput);
                    
                    // Show loading
                    Swal.fire({
                        title: 'Processing Transfer...',
                        text: 'Please wait while we process your transfer.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit the form
                    transferForm.submit();
                }
            });
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.transfer-summary {
    border: 1px solid #dee2e6;
}

.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
}

.nav-tabs .nav-link.active {
    background-color: transparent;
    border-bottom: 2px solid #007bff;
    color: #007bff;
}

.table-hover tbody tr:hover {
    background-color: rgba(40, 167, 69, 0.05);
}

.form-control:focus,
.form-select:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838 0%, #1abc9c 100%);
    transform: translateY(-1px);
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}
</style>
@endpush

