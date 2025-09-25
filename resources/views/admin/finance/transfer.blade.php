@extends('admin.layouts.app')

@section('title', 'Admin Balance Transfer')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div>
                <h1 class="page-title fw-semibold fs-18 mb-0">Admin Balance Transfer</h1>
                <div class="">
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.finance.dashboard') }}">Finance</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Balance Transfer</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.finance.transfer.history') }}" class="btn btn-outline-primary">
                    <i class="fe fe-clock me-1"></i>Transfer History
                </a>
                <a href="{{ route('admin.finance.wallets') }}" class="btn btn-outline-secondary">
                    <i class="fe fe-credit-card me-1"></i>View Wallets
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <!-- Transfer Form Card -->
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary-gradient text-white">
                        <h5 class="mb-0">
                            <i class="fe fe-send me-2"></i>Transfer Balance to User Deposit Wallet
                        </h5>
                        <p class="mb-0 text-white-75 fs-12 mt-1">
                            Transfer balance directly to any user's deposit wallet instantly
                        </p>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Alert Container -->
                        <div id="alertContainer"></div>

                        <!-- Transfer Form -->
                        <form id="transferForm">
                            @csrf
                            <!-- Step 1: User Selection -->
                            <div class="form-step active" id="step1">
                                <div class="step-header mb-4">
                                    <h6 class="step-title text-primary">
                                        <span class="step-number">1</span> Select Recipient User
                                    </h6>
                                    <p class="text-muted fs-13 mb-0">Search and select the user to transfer balance</p>
                                </div>

                                <div class="position-relative mb-4">
                                    <label for="userSearch" class="form-label fw-semibold">
                                        Search User <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="userSearch" class="form-control form-control-lg" 
                                           placeholder="Search by name, email, phone, or user ID..." 
                                           autocomplete="off">
                                    <div id="userSuggestions" class="user-suggestions"></div>
                                </div>

                                <!-- Selected User Card -->
                                <div id="selectedUserCard" class="selected-user-card" style="display: none;">
                                    <div class="card bg-light">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="user-info">
                                                    <h6 class="mb-1 user-name"></h6>
                                                    <p class="text-muted mb-1 fs-13 user-email"></p>
                                                    <p class="text-muted mb-0 fs-12 user-phone"></p>
                                                </div>
                                                <div class="user-balance text-end">
                                                    <p class="mb-1 fs-13">Current Balance:</p>
                                                    <h6 class="text-success mb-0">৳<span class="deposit-wallet-balance"></span></h6>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="clearSelectedUser()">
                                                <i class="fe fe-x me-1"></i>Clear Selection
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" id="selectedUserId" name="user_id">
                            </div>

                            <!-- Step 2: Transfer Amount -->
                            <div class="form-step" id="step2" style="display: none;">
                                <div class="step-header mb-4">
                                    <h6 class="step-title text-primary">
                                        <span class="step-number">2</span> Transfer Amount
                                    </h6>
                                    <p class="text-muted fs-13 mb-0">Enter the amount to transfer to user's deposit wallet</p>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="amount" class="form-label fw-semibold">
                                            Base Transfer Amount (৳) <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">৳</span>
                                            <input type="number" id="amount" name="amount" class="form-control form-control-lg" 
                                                   placeholder="0.00" min="1" max="1000000" step="0.01" onchange="calculateTotal()">
                                        </div>
                                        <small class="text-muted">Minimum: ৳1.00 | Maximum: ৳10,00,000.00</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Quick Amounts</label>
                                        <div class="quick-amounts">
                                            <button type="button" class="btn btn-outline-primary btn-sm me-2 mb-2" onclick="setAmount(100)">৳100</button>
                                            <button type="button" class="btn btn-outline-primary btn-sm me-2 mb-2" onclick="setAmount(500)">৳500</button>
                                            <button type="button" class="btn btn-outline-primary btn-sm me-2 mb-2" onclick="setAmount(1000)">৳1,000</button>
                                            <button type="button" class="btn btn-outline-primary btn-sm me-2 mb-2" onclick="setAmount(5000)">৳5,000</button>
                                            <button type="button" class="btn btn-outline-primary btn-sm me-2 mb-2" onclick="setAmount(10000)">৳10,000</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Commission Section (Only for Vendors) -->
                                <div id="commissionSection" class="mt-4" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="commission_rate" class="form-label fw-semibold">
                                                Commission Rate (%) <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="number" id="commission_rate" name="commission_rate" class="form-control" 
                                                       placeholder="Enter commission %" min="0" max="50" step="0.01" onchange="calculateTotal()" required>
                                                <span class="input-group-text">%</span>
                                            </div>
                                            <small class="text-muted">Commission rate for vendor transfers (0-50%)</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Quick Commission Rates</label>
                                            <div class="quick-commissions">
                                                <button type="button" class="btn btn-outline-success btn-sm me-2 mb-2" onclick="setCommission(5)">5%</button>
                                                <button type="button" class="btn btn-outline-success btn-sm me-2 mb-2" onclick="setCommission(10)">10%</button>
                                                <button type="button" class="btn btn-outline-success btn-sm me-2 mb-2" onclick="setCommission(15)">15%</button>
                                                <button type="button" class="btn btn-outline-success btn-sm me-2 mb-2" onclick="setCommission(20)">20%</button>
                                                <button type="button" class="btn btn-outline-warning btn-sm me-2 mb-2" onclick="setCommission(0)">0% (No Commission)</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Commission Amount</label>
                                            <div class="input-group">
                                                <span class="input-group-text">৳</span>
                                                <input type="text" id="commission_amount" class="form-control" readonly value="0.00">
                                            </div>
                                            <small class="text-muted">Calculated commission amount</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Total Transfer Amount</label>
                                            <div class="input-group">
                                                <span class="input-group-text">৳</span>
                                                <input type="text" id="total_transfer_amount" class="form-control fw-bold text-success" readonly value="0.00">
                                            </div>
                                            <small class="text-muted">Base amount + commission</small>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <div class="alert alert-info">
                                            <h6 class="mb-2"><i class="fe fe-info me-2"></i>Transfer Breakdown</h6>
                                            <div class="d-flex justify-content-between">
                                                <span><strong>Base Amount:</strong> ৳<span id="baseAmountDisplay">0.00</span></span>
                                                <span><strong>Commission (<span id="commissionRateDisplay">0</span>%):</strong> ৳<span id="commissionDisplay">0.00</span></span>
                                                <span class="text-success"><strong>Total Transfer:</strong> ৳<span id="totalAmountDisplay">0.00</span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label for="note" class="form-label fw-semibold">Transfer Note</label>
                                    <textarea id="note" name="note" class="form-control" rows="3" 
                                              placeholder="Optional note for this transfer..."></textarea>
                                    <small class="text-muted">This note will be recorded in the transaction history</small>
                                </div>
                            </div>

                            <!-- Step 3: Confirmation -->
                            <div class="form-step" id="step3" style="display: none;">
                                <div class="step-header mb-4">
                                    <h6 class="step-title text-primary">
                                        <span class="step-number">3</span> Confirm Transfer
                                    </h6>
                                    <p class="text-muted fs-13 mb-0">Review transfer details and confirm with your admin password</p>
                                </div>

                                <!-- Transfer Summary -->
                                <div class="transfer-summary bg-light p-3 rounded mb-4">
                                    <h6 class="mb-3">Transfer Summary</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="mb-2"><strong>Recipient:</strong></p>
                                            <p class="text-muted fs-13 mb-1" id="summaryUserName"></p>
                                            <p class="text-muted fs-12" id="summaryUserEmail"></p>
                                            <p class="text-muted fs-12">Role: <span id="summaryUserRole"></span></p>
                                        </div>
                                        <div class="col-6 text-end">
                                            <p class="mb-2"><strong>Transfer Details:</strong></p>
                                            <div id="summaryAmountBreakdown">
                                                <p class="mb-1">Base Amount: ৳<span id="summaryBaseAmount"></span></p>
                                                <p class="mb-1" id="summaryCommissionRow" style="display: none;">Commission: ৳<span id="summaryCommission"></span></p>
                                                <hr class="my-2">
                                                <h5 class="text-success mb-0">Total: ৳<span id="summaryTotalAmount"></span></h5>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="mb-1"><strong>Note:</strong></p>
                                            <p class="text-muted fs-13 mb-0" id="summaryNote">No note provided</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Admin Password Verification -->
                                <div class="admin-verification">
                                    <label for="adminPassword" class="form-label fw-semibold">
                                        Admin Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" id="adminPassword" name="admin_password" 
                                           class="form-control form-control-lg" 
                                           placeholder="Enter your admin password to confirm">
                                    <small class="text-muted">Your admin password is required to authorize this transfer</small>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="form-navigation mt-4">
                                <div class="d-flex justify-content-between">
                                    <button type="button" id="prevBtn" class="btn btn-outline-secondary" onclick="changeStep(-1)" style="display: none;">
                                        <i class="fe fe-chevron-left me-1"></i>Previous
                                    </button>
                                    <button type="button" id="nextBtn" class="btn btn-primary" onclick="changeStep(1)">
                                        Next <i class="fe fe-chevron-right ms-1"></i>
                                    </button>
                                    <button type="submit" id="submitBtn" class="btn btn-success" style="display: none;">
                                        <i class="fe fe-check me-1"></i>Transfer Balance
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Transfer Progress -->
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="progress mb-2" style="height: 8px;">
                            <div id="progressBar" class="progress-bar bg-primary" role="progressbar" style="width: 33%"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">Select User</small>
                            <small class="text-muted">Enter Amount</small>
                            <small class="text-muted">Confirm Transfer</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-step {
    min-height: 300px;
}

.step-number {
    display: inline-flex;
    width: 28px;
    height: 28px;
    background: var(--primary-color);
    color: white;
    border-radius: 50%;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 600;
    margin-right: 8px;
}

.user-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #dee2e6;
    border-top: none;
    border-radius: 0 0 8px 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
}

.user-suggestion {
    padding: 12px 16px;
    border-bottom: 1px solid #f1f3f4;
    cursor: pointer;
    transition: background-color 0.2s;
}

.user-suggestion:hover {
    background-color: #f8f9fa;
}

.user-suggestion:last-child {
    border-bottom: none;
}

.user-suggestion.loading {
    text-align: center;
    color: #6c757d;
    cursor: default;
}

.user-suggestion.loading:hover {
    background-color: white;
}

.selected-user-card {
    margin-top: 1rem;
}

.quick-amounts .btn {
    min-width: 80px;
}

.transfer-summary {
    border-left: 4px solid var(--primary-color);
}

.bg-primary-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.form-control-lg {
    padding: 0.75rem 1rem;
    font-size: 1rem;
}

@media (max-width: 768px) {
    .form-navigation .d-flex {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .quick-amounts .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>

<script>
let currentStep = 1;
let selectedUser = null;

// User search functionality
let searchTimeout;
document.getElementById('userSearch').addEventListener('input', function() {
    const query = this.value.trim();
    const suggestions = document.getElementById('userSuggestions');
    
    clearTimeout(searchTimeout);
    
    if (query.length < 2) {
        suggestions.style.display = 'none';
        return;
    }
    
    searchTimeout = setTimeout(function() {
        searchUsers(query);
    }, 300);
});

function searchUsers(query) {
    const suggestions = document.getElementById('userSuggestions');
    
    suggestions.innerHTML = '<div class="user-suggestion loading"><i class="fe fe-loader"></i> Searching users...</div>';
    suggestions.style.display = 'block';
    
    fetch('{{ route("admin.finance.transfer.search-users") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({query: query})
    })
    .then(response => response.json())
    .then(data => {
        if (data.users && data.users.length > 0) {
            suggestions.innerHTML = data.users.map(user => `
                <div class="user-suggestion" onclick="selectUser(${JSON.stringify(user).replace(/"/g, '&quot;')})">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-1">${user.name}</h6>
                            <p class="text-muted mb-0 fs-13">${user.email}</p>
                            ${user.phone ? `<p class="text-muted mb-0 fs-12">${user.phone}</p>` : ''}
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Balance:</small><br>
                            <span class="text-success">৳${parseFloat(user.deposit_wallet || 0).toLocaleString()}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            suggestions.innerHTML = '<div class="user-suggestion loading">No users found matching your search</div>';
        }
    })
    .catch(error => {
        suggestions.innerHTML = '<div class="user-suggestion loading text-danger">Error searching users. Please try again.</div>';
    });
}

function selectUser(user) {
    selectedUser = user;
    console.log('Selected user:', user); // Debug log
    console.log('User role:', user.role); // Debug log
    
    document.getElementById('selectedUserId').value = user.id;
    document.getElementById('userSuggestions').style.display = 'none';
    document.getElementById('userSearch').value = user.name;
    
    // Show selected user card
    const card = document.getElementById('selectedUserCard');
    card.querySelector('.user-name').textContent = user.name;
    card.querySelector('.user-email').textContent = user.email;
    card.querySelector('.user-phone').textContent = user.phone || 'N/A';
    card.querySelector('.deposit-wallet-balance').textContent = parseFloat(user.deposit_wallet || 0).toLocaleString();
    card.style.display = 'block';
    
    // Show/hide commission section based on user role
    const commissionSection = document.getElementById('commissionSection');
    console.log('Commission section element:', commissionSection); // Debug log
    
    if (user.role === 'vendor') {
        console.log('Showing commission section for vendor'); // Debug log
        commissionSection.style.display = 'block';
        // Clear commission rate to require manual input each time
        document.getElementById('commission_rate').value = '';
        calculateTotal();
    } else {
        console.log('Hiding commission section for non-vendor:', user.role); // Debug log
        commissionSection.style.display = 'none';
        document.getElementById('commission_rate').value = '0';
        calculateTotal();
    }
    
    // Enable next button
    document.getElementById('nextBtn').disabled = false;
}

function clearSelectedUser() {
    selectedUser = null;
    document.getElementById('selectedUserId').value = '';
    document.getElementById('userSearch').value = '';
    document.getElementById('selectedUserCard').style.display = 'none';
    document.getElementById('nextBtn').disabled = true;
}

function setAmount(amount) {
    document.getElementById('amount').value = amount;
    calculateTotal();
}

function setCommission(rate) {
    document.getElementById('commission_rate').value = rate;
    calculateTotal();
}

function calculateTotal() {
    const baseAmount = parseFloat(document.getElementById('amount').value) || 0;
    const commissionRate = parseFloat(document.getElementById('commission_rate').value) || 0;
    
    const commissionAmount = (baseAmount * commissionRate) / 100;
    const totalAmount = baseAmount + commissionAmount;
    
    // Update all display fields
    document.getElementById('commission_amount').value = commissionAmount.toFixed(2);
    document.getElementById('total_transfer_amount').value = totalAmount.toFixed(2);
    document.getElementById('baseAmountDisplay').textContent = baseAmount.toLocaleString();
    document.getElementById('commissionRateDisplay').textContent = commissionRate.toFixed(1);
    document.getElementById('commissionDisplay').textContent = commissionAmount.toLocaleString();
    document.getElementById('totalAmountDisplay').textContent = totalAmount.toLocaleString();
}

function changeStep(direction) {
    if (direction === 1 && !validateCurrentStep()) {
        return;
    }
    
    currentStep += direction;
    updateStepDisplay();
}

function validateCurrentStep() {
    if (currentStep === 1) {
        if (!selectedUser) {
            showAlert('danger', 'Please select a user to transfer balance to.');
            return false;
        }
    } else if (currentStep === 2) {
        const amount = parseFloat(document.getElementById('amount').value);
        if (!amount || amount < 1 || amount > 1000000) {
            showAlert('danger', 'Please enter a valid amount between ৳1 and ৳10,00,000.');
            return false;
        }
        
        // Validate commission rate for vendors
        if (selectedUser.role === 'vendor') {
            const commissionRate = document.getElementById('commission_rate').value;
            if (commissionRate === '' || commissionRate === null) {
                showAlert('danger', 'Please enter a commission rate for vendor transfers (0-50%).');
                document.getElementById('commission_rate').focus();
                return false;
            }
            const commissionRateFloat = parseFloat(commissionRate);
            if (isNaN(commissionRateFloat) || commissionRateFloat < 0 || commissionRateFloat > 50) {
                showAlert('danger', 'Commission rate must be between 0% and 50%.');
                document.getElementById('commission_rate').focus();
                return false;
            }
        }
        
        // Calculate commission and total
        const commissionRate = parseFloat(document.getElementById('commission_rate').value) || 0;
        const commissionAmount = (amount * commissionRate) / 100;
        const totalAmount = amount + commissionAmount;
        
        // Update summary
        document.getElementById('summaryUserName').textContent = selectedUser.name;
        document.getElementById('summaryUserEmail').textContent = selectedUser.email;
        document.getElementById('summaryUserRole').textContent = selectedUser.role || 'User';
        document.getElementById('summaryBaseAmount').textContent = amount.toLocaleString();
        document.getElementById('summaryTotalAmount').textContent = totalAmount.toLocaleString();
        
        // Show/hide commission row
        const commissionRow = document.getElementById('summaryCommissionRow');
        if (commissionAmount > 0) {
            document.getElementById('summaryCommission').textContent = commissionAmount.toLocaleString();
            commissionRow.style.display = 'block';
        } else {
            commissionRow.style.display = 'none';
        }
        
        const note = document.getElementById('note').value.trim();
        document.getElementById('summaryNote').textContent = note || 'No note provided';
    }
    
    return true;
}

function updateStepDisplay() {
    // Hide all steps
    document.querySelectorAll('.form-step').forEach(step => {
        step.style.display = 'none';
    });
    
    // Show current step
    document.getElementById('step' + currentStep).style.display = 'block';
    
    // Update navigation buttons
    document.getElementById('prevBtn').style.display = currentStep > 1 ? 'inline-block' : 'none';
    document.getElementById('nextBtn').style.display = currentStep < 3 ? 'inline-block' : 'none';
    document.getElementById('submitBtn').style.display = currentStep === 3 ? 'inline-block' : 'none';
    
    // Update progress bar
    const progress = (currentStep / 3) * 100;
    document.getElementById('progressBar').style.width = progress + '%';
}

// Form submission
document.getElementById('transferForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const adminPassword = document.getElementById('adminPassword').value.trim();
    if (!adminPassword) {
        showAlert('danger', 'Please enter your admin password to confirm the transfer.');
        return;
    }
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fe fe-loader"></i> Processing...';
    submitBtn.disabled = true;
    
    const formData = {
        user_id: selectedUser.id,
        amount: parseFloat(document.getElementById('amount').value),
        commission_rate: parseFloat(document.getElementById('commission_rate').value) || 0,
        note: document.getElementById('note').value.trim(),
        admin_password: adminPassword
    };
    
    fetch('{{ route("admin.finance.transfer.execute") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            
            // Reset form after successful transfer
            setTimeout(() => {
                resetForm();
            }, 3000);
        } else {
            showAlert('danger', data.message || 'Transfer failed. Please try again.');
        }
    })
    .catch(error => {
        showAlert('danger', 'Network error. Please check your connection and try again.');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

function showAlert(type, message) {
    const alertContainer = document.getElementById('alertContainer');
    alertContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        const alert = alertContainer.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}

function resetForm() {
    currentStep = 1;
    selectedUser = null;
    document.getElementById('transferForm').reset();
    document.getElementById('selectedUserCard').style.display = 'none';
    updateStepDisplay();
}

// Hide suggestions when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('#userSearch') && !e.target.closest('#userSuggestions')) {
        document.getElementById('userSuggestions').style.display = 'none';
    }
});

// Initialize
updateStepDisplay();
</script>
@endsection
