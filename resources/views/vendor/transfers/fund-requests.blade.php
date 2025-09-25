@extends('admin.layouts.app')

@section('title', 'Fund Requests')

@push('styles')
<style>
    .fund-request-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        border-left: 4px solid #007bff;
        transition: all 0.3s ease;
    }
    
    .fund-request-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    
    .fund-request-card.urgent {
        border-left-color: #dc3545;
    }
    
    .fund-request-card.high-amount {
        border-left-color: #ffc107;
    }
    
    .request-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }
    
    .member-info {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .member-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
    }
    
    .request-details {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .amount-badge {
        font-size: 1.2rem;
        font-weight: bold;
        padding: 8px 15px;
        border-radius: 20px;
    }
    
    .stats-cards {
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        border-top: 3px solid;
    }
    
    .stat-card.pending { border-top-color: #ffc107; }
    .stat-card.completed { border-top-color: #28a745; }
    .stat-card.rejected { border-top-color: #dc3545; }
    
    .no-requests {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    
    .processed-request {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
    }
    
    .loading {
        opacity: 0.6;
        pointer-events: none;
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
                    <h4 class="mb-0">Fund Requests Management</h4>
                    <p class="text-muted mb-0">Review and process member fund requests</p>
                </div>
                <div class="btn-group">
                    <button class="btn btn-success" onclick="refreshRequests()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <a href="{{ route('vendor.transfers.index') }}" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Send Transfer
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row stats-cards">
        <div class="col-md-4">
            <div class="stat-card pending">
                <h6 class="text-muted mb-2">Pending Requests</h6>
                <h3 class="text-warning mb-0" id="pending-count">{{ $pendingFundRequests->total() }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card completed">
                <h6 class="text-muted mb-2">Total Processed Today</h6>
                <h3 class="text-success mb-0">
                    {{ $stats['processed_today'] }}
                </h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card rejected">
                <h6 class="text-muted mb-2">Current Balance</h6>
                <h3 class="text-primary mb-0">৳{{ number_format(Auth::user()->deposit_wallet ?? 0, 2) }}</h3>
            </div>
        </div>
    </div>

    <!-- Pending Requests -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-clock text-warning"></i> Pending Fund Requests
                    </h6>
                </div>
                <div class="card-body">
                    <div id="pending-requests-container">
                        @forelse($pendingFundRequests as $request)
                        <div class="fund-request-card {{ $request->amount >= 1000 ? 'high-amount' : '' }} {{ $request->created_at->diffInHours(now()) > 24 ? 'urgent' : '' }}" 
                             data-request-id="{{ $request->id }}">
                            
                            <!-- Member Info -->
                            <div class="member-info">
                                <div class="member-avatar">
                                    {{ substr($request->member->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $request->member->name }}</h6>
                                    <small class="text-muted">{{ $request->member->email }}</small>
                                </div>
                                <div class="ms-auto">
                                    <span class="amount-badge bg-primary text-white">
                                        ৳{{ number_format($request->amount, 2) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Request Details -->
                            <div class="request-details">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Purpose:</strong> {{ $request->purpose }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Request Type:</strong> 
                                        <span class="badge bg-info">{{ ucfirst($request->request_type ?? 'general') }}</span>
                                    </div>
                                </div>
                                @if($request->notes)
                                <div class="mt-2">
                                    <strong>Notes:</strong> {{ $request->notes }}
                                </div>
                                @endif
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i> Requested {{ $request->created_at->diffForHumans() }}
                                        @if($request->created_at->diffInHours(now()) > 24)
                                            <span class="badge bg-warning ms-2">Urgent</span>
                                        @endif
                                    </small>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="request-actions">
                                <button class="btn btn-success btn-sm" onclick="showApprovalModal({{ $request->id }}, '{{ $request->member->name }}', {{ $request->amount }})">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button class="btn btn-warning btn-sm" onclick="showPartialApprovalModal({{ $request->id }}, '{{ $request->member->name }}', {{ $request->amount }})">
                                    <i class="fas fa-edit"></i> Partial Approve
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="showRejectionModal({{ $request->id }}, '{{ $request->member->name }}')">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                                <button class="btn btn-info btn-sm" onclick="showMemberDetails({{ $request->member->id }})">
                                    <i class="fas fa-user"></i> Member Details
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="no-requests">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <h5>No Pending Requests</h5>
                            <p class="text-muted">All fund requests have been processed</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($pendingFundRequests->hasPages())
                        <div class="mt-4">
                            {{ $pendingFundRequests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recently Processed -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-history text-info"></i> Recently Processed
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($recentlyProcessed as $processed)
                    <div class="processed-request">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>{{ $processed->member->name }}</strong>
                                <br><small class="text-muted">৳{{ number_format($processed->amount, 2) }}</small>
                                @if($processed->approved_amount && $processed->approved_amount != $processed->amount)
                                    <br><small class="text-success">Approved: ৳{{ number_format($processed->approved_amount, 2) }}</small>
                                @endif
                            </div>
                            <span class="badge bg-{{ $processed->status == 'completed' ? 'success' : 'danger' }}">
                                {{ ucfirst($processed->status) }}
                            </span>
                        </div>
                        <small class="text-muted">{{ $processed->processed_at->diffForHumans() }}</small>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No recent activity</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Fund Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="approval-details"></div>
                <div class="mt-3">
                    <label class="form-label">Notes (Optional)</label>
                    <textarea class="form-control" id="approval-notes" rows="3" placeholder="Add any notes for the member..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirm-approval-btn">Approve Request</button>
            </div>
        </div>
    </div>
</div>

<!-- Partial Approval Modal -->
<div class="modal fade" id="partialApprovalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Partial Approval</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="partial-approval-details"></div>
                <div class="mt-3">
                    <label class="form-label">Approved Amount (৳)</label>
                    <input type="number" class="form-control" id="partial-amount" min="1" step="0.01">
                </div>
                <div class="mt-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" id="partial-notes" rows="3" placeholder="Explain the partial approval..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirm-partial-btn">Approve Partial Amount</button>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Fund Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="rejection-details"></div>
                <div class="mt-3">
                    <label class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="rejection-reason" rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-rejection-btn">Reject Request</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentRequestId = null;

function showApprovalModal(requestId, memberName, amount) {
    currentRequestId = requestId;
    const details = `
        <div class="text-center">
            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
            <h5>Approve Fund Request</h5>
            <p>You are about to approve <strong>৳${amount}</strong> for:</p>
            <div class="bg-light p-3 rounded">
                <strong>${memberName}</strong>
            </div>
        </div>
    `;
    $('#approval-details').html(details);
    $('#approval-notes').val('');
    $('#approvalModal').modal('show');
}

function showPartialApprovalModal(requestId, memberName, amount) {
    currentRequestId = requestId;
    const details = `
        <div class="text-center">
            <i class="fas fa-edit fa-3x text-warning mb-3"></i>
            <h5>Partial Approval</h5>
            <p>Requested amount: <strong>৳${amount}</strong> by <strong>${memberName}</strong></p>
        </div>
    `;
    $('#partial-approval-details').html(details);
    $('#partial-amount').val(amount).attr('max', amount);
    $('#partial-notes').val('');
    $('#partialApprovalModal').modal('show');
}

function showRejectionModal(requestId, memberName) {
    currentRequestId = requestId;
    const details = `
        <div class="text-center">
            <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
            <h5>Reject Fund Request</h5>
            <p>You are about to reject the fund request from:</p>
            <div class="bg-light p-3 rounded">
                <strong>${memberName}</strong>
            </div>
        </div>
    `;
    $('#rejection-details').html(details);
    $('#rejection-reason').val('');
    $('#rejectionModal').modal('show');
}

// Approve request
$('#confirm-approval-btn').click(function() {
    const notes = $('#approval-notes').val();
    processRequest(currentRequestId, 'approve', null, notes);
    $('#approvalModal').modal('hide');
});

// Partial approval
$('#confirm-partial-btn').click(function() {
    const amount = $('#partial-amount').val();
    const notes = $('#partial-notes').val();
    
    if (!amount || amount <= 0) {
        showAlert('Please enter a valid amount', 'error');
        return;
    }
    
    processRequest(currentRequestId, 'approve', amount, notes);
    $('#partialApprovalModal').modal('hide');
});

// Reject request
$('#confirm-rejection-btn').click(function() {
    const reason = $('#rejection-reason').val().trim();
    
    if (!reason) {
        showAlert('Please provide a reason for rejection', 'error');
        return;
    }
    
    processRequest(currentRequestId, 'reject', null, reason);
    $('#rejectionModal').modal('hide');
});

function processRequest(requestId, action, amount = null, notes = '') {
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
                $(`.fund-request-card[data-request-id="${requestId}"]`).fadeOut(400, function() {
                    $(this).remove();
                    updatePendingCount();
                });
                
                // Update balance display
                if (response.new_balance) {
                    $('.stat-card.rejected h3').text('৳' + response.new_balance);
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

function updatePendingCount() {
    const currentCount = parseInt($('#pending-count').text());
    const newCount = Math.max(0, currentCount - 1);
    $('#pending-count').text(newCount);
    
    if (newCount === 0) {
        setTimeout(() => {
            $('#pending-requests-container').html(`
                <div class="no-requests">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5>No Pending Requests</h5>
                    <p class="text-muted">All fund requests have been processed</p>
                </div>
            `);
        }, 500);
    }
}

function refreshRequests() {
    location.reload();
}

function showMemberDetails(memberId) {
    // This would typically open a modal with member information
    showAlert('Member details feature coming soon', 'info');
}

// Show alert function
function showAlert(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : type === 'error' ? 'alert-danger' : 'alert-info';
    const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-triangle' : 'fa-info-circle';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="fas ${icon} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Remove existing alerts
    $('.alert').remove();
    
    // Add new alert at the top
    $('body').prepend(alertHtml);
    
    // Auto-hide success alerts
    if (type === 'success') {
        setTimeout(() => {
            $('.alert-success').fadeOut();
        }, 5000);
    }
}
</script>
@endpush
