@extends('admin.layouts.app')

@section('title', 'Transfer Management')

@push('styles')
<style>
    .balance-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .balance-amount {
        font-size: 2.5rem;
        font-weight: bold;
        margin: 0;
    }
    
    .stats-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border-left: 4px solid #007bff;
        margin-bottom: 20px;
    }
    
    .transfer-form {
        background: white;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }
    
    .member-search-result {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin: 5px 0;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .member-search-result:hover {
        background: #e7f3ff;
        border-color: #007bff;
    }
    
    .member-search-result.selected {
        background: #007bff;
        color: white;
        border-color: #007bff;
    }
    
    .fund-request-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    
    .fund-request-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .transfer-history-item {
        background: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        border-left: 4px solid #28a745;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }
    
    .retransfer-btn {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    
    .retransfer-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,123,255,0.3);
    }
    
    .recipient-info {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
    }
    
    .retransfer-amount-input {
        font-size: 1.2rem;
        font-weight: 500;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="container-fluid my-4">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-0">Transfer Management</h4>
                    <p class="text-muted mb-0">Send funds to members and manage fund requests</p>
                </div>
                <button class="btn btn-primary" onclick="refreshDashboard()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Balance Overview -->
    <div class="row">
        <div class="col-md-8">
            <div class="balance-card">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fas fa-wallet fa-3x"></i>
                    </div>
                    <div class="col">
                        <h3 class="mb-1">Available Balance</h3>
                        <p class="balance-amount" id="current-balance">৳{{ number_format(Auth::user()->deposit_wallet ?? 0, 2) }}</p>
                        <small class="opacity-75">Last updated: {{ now()->format('M d, Y H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <h6 class="text-muted mb-2">Total Sent</h6>
                <h4 class="text-success mb-0">৳{{ number_format($stats['total_sent'], 2) }}</h4>
            </div>
            <div class="stats-card">
                <h6 class="text-muted mb-2">Pending Requests</h6>
                <h4 class="text-warning mb-0">{{ $stats['fund_requests_count'] }}</h4>
            </div>
        </div>
    </div>

    <!-- Transfer Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="transfer-form">
                <h5 class="mb-4">
                    <i class="fas fa-paper-plane text-primary"></i> Send Money to Member
                </h5>
                
                <form id="transfer-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Search Member</label>
                            <input type="text" class="form-control" id="member-search" 
                                   placeholder="Type member name, email, or phone..." autocomplete="off">
                            <div id="member-search-results" class="mt-2"></div>
                            <input type="hidden" id="selected-member-id" name="member_id">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Amount (৳)</label>
                            <input type="number" class="form-control" id="transfer-amount" name="amount" 
                                   min="1" max="50000" step="0.01" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Purpose</label>
                            <input type="text" class="form-control" name="purpose" 
                                   placeholder="e.g., Sales bonus, Product refund, etc." maxlength="255">
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" name="notes" rows="3" 
                                      placeholder="Additional notes for this transfer..." maxlength="500"></textarea>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> <strong>Note:</strong> All transfers will be sent directly to the member's deposit wallet.
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg" id="transfer-btn">
                                <i class="fas fa-paper-plane"></i> Send Transfer
                            </button>
                            <button type="reset" class="btn btn-secondary btn-lg ms-2">
                                <i class="fas fa-times"></i> Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Fund Requests -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-hand-holding-usd text-warning"></i> Pending Fund Requests
                        <span class="badge bg-warning ms-2" id="requests-count">{{ $pendingFundRequests->count() }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    <div id="fund-requests-container">
                        @forelse($pendingFundRequests as $request)
                        <div class="fund-request-card p-3" data-request-id="{{ $request->id }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <strong>{{ $request->member->name }}</strong>
                                <span class="badge bg-info">৳{{ number_format($request->amount, 2) }}</span>
                            </div>
                            <p class="text-muted small mb-2">{{ $request->purpose }}</p>
                            <p class="text-muted small mb-3">{{ $request->created_at->diffForHumans() }}</p>
                            <div class="btn-group btn-group-sm w-100">
                                <button class="btn btn-success" onclick="processFundRequest({{ $request->id }}, 'approve')">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button class="btn btn-danger" onclick="processFundRequest({{ $request->id }}, 'reject')">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted" id="no-requests">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p class="mb-0">No pending requests</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transfer History -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-history text-info"></i> Transfer History
                    </h6>
                </div>
                <div class="card-body">
                    <div id="transfer-history-container">
                        @forelse($recentTransfers as $transfer)
                        <div class="transfer-history-item">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <strong>{{ $transfer->recipient->name }}</strong>
                                    <br><small class="text-muted">{{ $transfer->recipient->email }}</small>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge bg-{{ $transfer->status == 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($transfer->status) }}
                                    </span>
                                </div>
                                <div class="col-md-2">
                                    <strong>৳{{ number_format($transfer->net_amount, 2) }}</strong>
                                    @if($transfer->fee > 0)
                                        <br><small class="text-muted">Fee: ৳{{ number_format($transfer->fee, 2) }}</small>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    {{ $transfer->purpose ?? 'No purpose specified' }}
                                </div>
                                <div class="col-md-2 text-end">
                                    <small class="text-muted d-block">{{ $transfer->created_at->format('M d, Y H:i') }}</small>
                                    @if($transfer->status == 'completed')
                                        <button class="btn btn-sm btn-outline-primary retransfer-btn mt-1" 
                                                onclick="showRetransferModal('{{ $transfer->recipient->id }}', '{{ $transfer->recipient->name }}', '{{ $transfer->recipient->email }}', '{{ $transfer->amount }}')"
                                                title="Quick retransfer to {{ $transfer->recipient->name }}">
                                            <i class="fas fa-redo"></i> Retransfer
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-history fa-3x mb-3"></i>
                            <h5>No Transfer History</h5>
                            <p>Your transfer history will appear here</p>
                        </div>
                        @endforelse
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $recentTransfers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transfer Confirmation Modal -->
<div class="modal fade" id="transferModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="transfer-confirmation-details"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-transfer-btn">Confirm Transfer</button>
            </div>
        </div>
    </div>
</div>

<!-- Retransfer Modal -->
<div class="modal fade" id="retransferModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-redo me-2"></i>Quick Retransfer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Security Notice:</strong> You'll need to verify your password for this transfer.
                </div>
                
                <div class="recipient-info mb-4 p-3 bg-light rounded">
                    <h6 class="mb-2"><i class="fas fa-user me-1"></i> Recipient Details</h6>
                    <div class="row">
                        <div class="col-sm-4"><strong>Name:</strong></div>
                        <div class="col-sm-8" id="retransfer-recipient-name"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><strong>Email:</strong></div>
                        <div class="col-sm-8" id="retransfer-recipient-email"></div>
                    </div>
                </div>

                <form id="retransferForm">
                    <input type="hidden" id="retransfer-recipient-id" name="recipient_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Transfer Amount (৳)</label>
                        <input type="number" class="form-control form-control-lg retransfer-amount-input" 
                               id="retransfer-amount" name="amount" 
                               min="1" max="50000" step="0.01" required
                               placeholder="Enter amount to transfer">
                        <small class="text-muted">Previous amount: ৳<span id="previous-amount"></span></small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Purpose (Optional)</label>
                        <input type="text" class="form-control" name="purpose" 
                               placeholder="e.g., Quick retransfer, Follow-up payment">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" name="notes" rows="2" 
                                  placeholder="Additional notes for this retransfer..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="processRetransfer()">
                    <i class="fas fa-paper-plane me-1"></i> Process Retransfer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let selectedMember = null;
    let searchTimeout;
    
    // Member search functionality
    $('#member-search').on('input', function() {
        const query = $(this).val();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            $('#member-search-results').empty();
            return;
        }
        
        searchTimeout = setTimeout(() => {
            searchMembers(query);
        }, 300);
    });
    
    // Search members via AJAX
    function searchMembers(query) {
        $.ajax({
            url: '{{ route("vendor.transfers.search-members") }}',
            method: 'GET',
            data: { query: query },
            beforeSend: function() {
                $('#member-search-results').html('<div class="text-center py-2"><i class="fas fa-spinner fa-spin"></i> Searching...</div>');
            },
            success: function(response) {
                if (response.success && response.members && response.members.length > 0) {
                    displayMemberResults(response.members);
                } else {
                    $('#member-search-results').html('<div class="text-muted small">No members found for "' + query + '"</div>');
                }
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Search failed';
                if (xhr.status === 404) {
                    errorMessage = 'Search endpoint not found (Route missing)';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error occurred';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                $('#member-search-results').html('<div class="text-danger small">' + errorMessage + '</div>');
            }
        });
    }
    
    // Display member search results
    function displayMemberResults(members) {
        const container = $('#member-search-results');
        container.empty();
        
        if (members.length === 0) {
            container.html('<div class="text-muted small">No members found</div>');
            return;
        }
        
        members.forEach(member => {
            const resultHtml = `
                <div class="member-search-result" data-member-id="${member.id}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${member.name}</strong>
                            <br><small class="text-muted">${member.email}</small>
                            ${member.username ? `<br><small class="text-info">@${member.username}</small>` : ''}
                            ${member.role ? `<br><small class="badge bg-secondary">${member.role}</small>` : ''}
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Balance:</small>
                            <br><span class="text-success">৳${member.deposit_wallet}</span>
                        </div>
                    </div>
                </div>
            `;
            container.append(resultHtml);
        });
        
        // Handle member selection
        $('.member-search-result').click(function() {
            const memberId = $(this).data('member-id');
            const memberData = members.find(m => m.id == memberId);
            
            $('.member-search-result').removeClass('selected');
            $(this).addClass('selected');
            
            selectedMember = memberData;
            $('#selected-member-id').val(memberId);
            $('#member-search').val(memberData.name + ' (' + memberData.email + ')');
            
            setTimeout(() => {
                $('#member-search-results').hide();
            }, 200);
        });
    }
    
    // Transfer form submission
    $('#transfer-form').submit(function(e) {
        e.preventDefault();
        
        // Validate form
        if (!selectedMember) {
            showAlert('Please select a member first', 'error');
            $('#member-search').focus();
            return;
        }
        
        const amount = $('#transfer-amount').val();
        if (!amount || amount <= 0) {
            showAlert('Please enter a valid amount', 'error');
            $('#transfer-amount').focus();
            return;
        }
        
        const currentBalance = parseFloat($('#current-balance').text().replace(/[৳,]/g, ''));
        if (parseFloat(amount) > currentBalance) {
            showAlert('Insufficient balance for this transfer', 'error');
            $('#transfer-amount').focus();
            return;
        }
        
        const formData = new FormData(this);
        
        // Show confirmation modal
        const confirmationHtml = `
            <div class="text-center">
                <i class="fas fa-paper-plane fa-3x text-primary mb-3"></i>
                <h5>Transfer Confirmation</h5>
                <p>You are about to send <strong>৳${amount}</strong> to:</p>
                <div class="bg-light p-3 rounded mb-3">
                    <strong>${selectedMember.name}</strong><br>
                    <small class="text-muted">${selectedMember.email}</small>
                </div>
                <p class="text-muted">This action cannot be undone.</p>
            </div>
        `;
        
        $('#transfer-confirmation-details').html(confirmationHtml);
        $('#transferModal').modal('show');
        
        $('#confirm-transfer-btn').off('click').on('click', function() {
            $('#transferModal').modal('hide');
            processTransfer(formData);
        });
    });
    
    // Process transfer via AJAX
    function processTransfer(formData) {
        const btn = $('#transfer-btn');
        const originalText = btn.html();
        
        // Ensure CSRF token is included
        if (!formData.has('_token')) {
            formData.append('_token', '{{ csrf_token() }}');
        }
        
        $.ajax({
            url: '{{ route("vendor.transfers.send") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                btn.html('<i class="fas fa-spinner fa-spin"></i> Processing...').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    $('#transfer-form')[0].reset();
                    $('#member-search-results').empty();
                    $('#selected-member-id').val('');
                    selectedMember = null;
                    $('#current-balance').text('৳' + response.new_balance);
                    refreshTransferHistory();
                } else {
                    showAlert(response.message || 'Transfer failed', 'error');
                }
            },
            error: function(xhr, status, error) {
                let message = 'Transfer failed';
                
                if (xhr.status === 422) {
                    // Validation errors
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        message = Object.values(errors).flat().join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                } else if (xhr.status === 400) {
                    // Bad request (insufficient balance, etc.)
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                } else if (xhr.status === 500) {
                    // Server error
                    message = 'Server error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                
                showAlert(message, 'error');
            },
            complete: function() {
                btn.html(originalText).prop('disabled', false);
            }
        });
    }
    
    // Refresh transfer history
    function refreshTransferHistory() {
        $.ajax({
            url: '{{ route("vendor.transfers.history") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    updateTransferHistory(response.transfers);
                }
            }
        });
    }
    
    // Update transfer history display
    function updateTransferHistory(transfers) {
        const container = $('#transfer-history-container');
        container.empty();
        
        if (transfers.length === 0) {
            container.html(`
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-history fa-3x mb-3"></i>
                    <h5>No Transfer History</h5>
                    <p>Your transfer history will appear here</p>
                </div>
            `);
            return;
        }
        
        transfers.forEach(transfer => {
            const transferHtml = `
                <div class="transfer-history-item">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <strong>${transfer.recipient_name}</strong>
                            <br><small class="text-muted">${transfer.recipient_email}</small>
                        </div>
                        <div class="col-md-2">
                            <span class="badge bg-${transfer.status_badge}">${transfer.status}</span>
                        </div>
                        <div class="col-md-2">
                            <strong>৳${transfer.net_amount}</strong>
                            ${transfer.fee > 0 ? `<br><small class="text-muted">Fee: ৳${transfer.fee}</small>` : ''}
                        </div>
                        <div class="col-md-3">
                            ${transfer.purpose || 'No purpose specified'}
                        </div>
                        <div class="col-md-2 text-end">
                            <small class="text-muted">${transfer.created_at}</small>
                        </div>
                    </div>
                </div>
            `;
            container.append(transferHtml);
        });
    }
    
    // Hide search results when clicking outside
    $(document).click(function(e) {
        if (!$(e.target).closest('#member-search, #member-search-results').length) {
            $('#member-search-results').hide();
        }
    });
    
    // Show search results on input focus
    $('#member-search').focus(function() {
        if ($('#member-search-results').children().length > 0) {
            $('#member-search-results').show();
        }
    });
});

// Process fund request
function processFundRequest(requestId, action) {
    let notes = '';
    let amount = null;
    
    if (action === 'reject') {
        notes = prompt('Reason for rejection (optional):');
        if (notes === null) return; // User cancelled
    } else {
        const customAmount = prompt('Enter amount to approve (leave empty for requested amount):');
        if (customAmount !== null && customAmount !== '') {
            amount = parseFloat(customAmount);
            if (isNaN(amount) || amount <= 0) {
                showAlert('Please enter a valid amount', 'error');
                return;
            }
        }
    }
    
    $.ajax({
        url: '{{ route("vendor.transfers.process-fund-request") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            request_id: requestId,
            action: action,
            amount: amount,
            notes: notes
        },
        beforeSend: function() {
            $(`.fund-request-card[data-request-id="${requestId}"]`).addClass('loading');
        },
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 'success');
                $(`.fund-request-card[data-request-id="${requestId}"]`).fadeOut();
                $('#current-balance').text('৳' + response.new_balance);
                
                // Update requests count
                const currentCount = parseInt($('#requests-count').text());
                $('#requests-count').text(currentCount - 1);
                
                if (currentCount <= 1) {
                    setTimeout(() => {
                        $('#fund-requests-container').html(`
                            <div class="text-center py-4 text-muted" id="no-requests">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <p class="mb-0">No pending requests</p>
                            </div>
                        `);
                    }, 500);
                }
            } else {
                showAlert(response.message, 'error');
            }
        },
        error: function(xhr) {
            let message = 'Processing failed';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            showAlert(message, 'error');
        },
        complete: function() {
            $(`.fund-request-card[data-request-id="${requestId}"]`).removeClass('loading');
        }
    });
}

// Refresh dashboard
function refreshDashboard() {
    location.reload();
}

// Show alert function
function showAlert(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
            <i class="fas ${icon} me-2"></i><span>${message}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Remove existing alerts
    $('.alert[style*="position: fixed"]').remove();
    
    // Add new alert
    $('body').append(alertHtml);
    
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        $('.alert[style*="position: fixed"]').fadeOut();
    }, 5000);
}

// Show retransfer modal
function showRetransferModal(recipientId, recipientName, recipientEmail, previousAmount) {
    $('#retransfer-recipient-id').val(recipientId);
    $('#retransfer-recipient-name').text(recipientName);
    $('#retransfer-recipient-email').text(recipientEmail);
    $('#previous-amount').text(parseFloat(previousAmount).toLocaleString());
    $('#retransfer-amount').val('');
    $('#retransferForm')[0].reset();
    $('#retransfer-recipient-id').val(recipientId); // Set again after reset
    $('#retransferModal').modal('show');
}

// Process retransfer with password confirmation
function processRetransfer() {
    const formData = {
        recipient_id: $('#retransfer-recipient-id').val(),
        amount: $('#retransfer-amount').val(),
        purpose: $('#retransferForm input[name="purpose"]').val(),
        notes: $('#retransferForm textarea[name="notes"]').val()
    };
    
    // Validate amount
    if (!formData.amount || parseFloat(formData.amount) <= 0) {
        showAlert('Please enter a valid amount', 'error');
        return;
    }
    
    // Show single SweetAlert with password field and transfer details
    const recipientName = $('#retransfer-recipient-name').text();
    const amount = parseFloat(formData.amount).toLocaleString();
    const purpose = formData.purpose || 'Quick retransfer';
    
    // Use simple JavaScript prompt for password (most reliable)
    const password = prompt(`🔐 Security Verification Required

Transfer Details:
• Recipient: ${recipientName}
• Amount: ৳${amount}
• Purpose: ${purpose}

Please enter your account password to confirm this transfer:`);
    
    // Check if user cancelled
    if (password === null) {
        return; // User cancelled
    }
    
    // Validate password
    if (!password || password.trim() === '') {
        Swal.fire({
            title: 'Password Required',
            text: 'Password is required for security verification',
            icon: 'warning',
            confirmButtonText: 'Try Again',
            confirmButtonColor: '#007bff'
        }).then(() => {
            // Retry
            processRetransfer();
        });
        return;
    }
    
    // Show loading and process transfer
    Swal.fire({
        title: 'Processing Transfer...',
        text: 'Please wait while we process your transfer',
        icon: 'info',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Process the retransfer
    $.ajax({
        url: '{{ route("vendor.transfers.retransfer") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            ...formData,
            password: password
        },
        success: function(response) {
            console.log('Response:', response);
            if (response.success) {
                Swal.fire({
                    title: 'Transfer Successful!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonColor: '#28a745',
                    timer: 3000,
                    timerProgressBar: true
                }).then(() => {
                    $('#retransferModal').modal('hide');
                    // Update balance display
                    $('#current-balance').text('৳' + response.new_balance);
                    // Refresh page to show updated data
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Transfer Failed',
                    text: response.message,
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            }
        },
        error: function(xhr) {
            console.log('Error:', xhr);
            let message = 'Transfer failed. Please try again.';
            let title = 'Transfer Failed';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            } else if (xhr.status === 401) {
                title = 'Authentication Failed';
                message = 'Invalid password. Please check your password and try again.';
            } else if (xhr.status === 422) {
                title = 'Validation Error';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    message = errors.join(', ');
                }
            }
            
            Swal.fire({
                title: title,
                text: message,
                icon: 'error',
                confirmButtonColor: '#dc3545'
            });
        }
    });
}
</script>
@endpush
