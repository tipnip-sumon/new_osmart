@extends('admin.layouts.app')

@section('title', $pageTitle)

@section('content')
<div class="main-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">{{ $pageTitle }}</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Vendor KYC</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar avatar-md bg-primary">
                                    <i class="bx bx-file text-white"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="text-muted mb-0">Total</p>
                                        <h4 class="fw-semibold mb-0">{{ number_format($stats['total']) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar avatar-md bg-warning">
                                    <i class="bx bx-time text-white"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="text-muted mb-0">Pending</p>
                                        <h4 class="fw-semibold mb-0">{{ number_format($stats['pending']) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar avatar-md bg-success">
                                    <i class="bx bx-check text-white"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="text-muted mb-0">Approved</p>
                                        <h4 class="fw-semibold mb-0">{{ number_format($stats['approved']) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar avatar-md bg-danger">
                                    <i class="bx bx-x text-white"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="text-muted mb-0">Rejected</p>
                                        <h4 class="fw-semibold mb-0">{{ number_format($stats['rejected']) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar avatar-md bg-info">
                                    <i class="bx bx-search-alt text-white"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="text-muted mb-0">Under Review</p>
                                        <h4 class="fw-semibold mb-0">{{ number_format($stats['under_review']) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar avatar-md bg-secondary">
                                    <i class="bx bx-edit text-white"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="text-muted mb-0">Draft</p>
                                        <h4 class="fw-semibold mb-0">{{ number_format($stats['draft']) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Vendor KYC Verifications
                        </div>
                        <div class="d-flex flex-wrap align-items-center">
                            <!-- Search Form -->
                            <form method="GET" class="d-flex align-items-center me-3">
                                @if(request()->has('status'))
                                    <input type="hidden" name="status" value="{{ request()->status }}">
                                @endif
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Search vendors..." 
                                           value="{{ request()->search }}">
                                    <button class="btn btn-outline-primary" type="submit">
                                        <i class="bx bx-search"></i>
                                    </button>
                                </div>
                            </form>

                            <!-- Status Filter -->
                            <div class="dropdown">
                                <button class="btn btn-outline-light dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-filter me-2"></i>
                                    {{ isset($currentStatus) ? ucfirst($currentStatus) : 'All Status' }}
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.vendor-kyc.index') }}">All Status</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.vendor-kyc.pending') }}">Pending</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.vendor-kyc.approved') }}">Approved</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.vendor-kyc.rejected') }}">Rejected</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.vendor-kyc.under-review') }}">Under Review</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($kycVerifications->count() > 0)
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                            </th>
                                            <th scope="col">Business Info</th>
                                            <th scope="col">Owner Info</th>
                                            <th scope="col">Contact</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Progress</th>
                                            <th scope="col">Submitted</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kycVerifications as $kyc)
                                            <tr>
                                                <td>
                                                    <input class="form-check-input kyc-checkbox" type="checkbox" 
                                                           value="{{ $kyc->id }}" name="kyc_ids[]">
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-md me-3">
                                                            @if($kyc->vendor && $kyc->vendor->profile_image)
                                                                <img src="{{ Storage::url($kyc->vendor->profile_image) }}" alt="vendor">
                                                            @else
                                                                <div class="avatar-initial bg-primary-transparent">
                                                                    {{ substr($kyc->business_name ?? 'V', 0, 1) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <p class="mb-0 fw-semibold">{{ $kyc->business_name ?? 'N/A' }}</p>
                                                            <p class="text-muted fs-12 mb-0">{{ $kyc->business_type ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <p class="mb-0 fw-semibold">{{ $kyc->owner_full_name ?? 'N/A' }}</p>
                                                        <p class="text-muted fs-12 mb-0">{{ $kyc->vendor->email ?? 'N/A' }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <p class="mb-0">{{ $kyc->phone_number ?? 'N/A' }}</p>
                                                        <p class="text-muted fs-12 mb-0">{{ $kyc->email_address ?? 'N/A' }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    @switch($kyc->status)
                                                        @case('approved')
                                                            <span class="badge bg-success">Approved</span>
                                                            @break
                                                        @case('rejected')
                                                            <span class="badge bg-danger">Rejected</span>
                                                            @break
                                                        @case('pending')
                                                            <span class="badge bg-warning">Pending</span>
                                                            @break
                                                        @case('under_review')
                                                            <span class="badge bg-info">Under Review</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">Draft</span>
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-primary" role="progressbar" 
                                                             style="width: {{ $kyc->completion_percentage }}%" 
                                                             aria-valuenow="{{ $kyc->completion_percentage }}" 
                                                             aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="text-muted">{{ number_format($kyc->completion_percentage, 2) }}%</small>
                                                </td>
                                                <td>
                                                    @if($kyc->submitted_at)
                                                        <span>{{ $kyc->submitted_at->format('M d, Y') }}</span>
                                                        <small class="text-muted d-block">{{ $kyc->submitted_at->format('h:i A') }}</small>
                                                    @else
                                                        <span class="text-muted">Not submitted</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('admin.vendor-kyc.show', $kyc->id) }}" 
                                                           class="btn btn-sm btn-primary-light">
                                                            <i class="bx bx-show"></i> View
                                                        </a>
                                                        @if($kyc->status === 'pending')
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" 
                                                                        type="button" data-bs-toggle="dropdown">
                                                                    Actions
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a class="dropdown-item text-success" 
                                                                           href="#" onclick="updateStatus({{ $kyc->id }}, 'approved')">
                                                                            <i class="bx bx-check"></i> Approve
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item text-danger" 
                                                                           href="#" onclick="updateStatus({{ $kyc->id }}, 'rejected')">
                                                                            <i class="bx bx-x"></i> Reject
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item text-info" 
                                                                           href="#" onclick="updateStatus({{ $kyc->id }}, 'under_review')">
                                                                            <i class="bx bx-search-alt"></i> Mark Under Review
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="card-footer">
                                <div class="d-flex align-items-center">
                                    <div>
                                        Showing {{ $kycVerifications->firstItem() ?? 0 }} to {{ $kycVerifications->lastItem() ?? 0 }} 
                                        of {{ $kycVerifications->total() }} results
                                    </div>
                                    <div class="ms-auto">
                                        {{ $kycVerifications->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            </div>
                            
                        @else
                            <div class="text-center py-5">
                                <i class="bx bx-file fs-1 text-muted"></i>
                                <h5 class="mt-3">No Vendor KYC Verifications Found</h5>
                                <p class="text-muted">No vendor KYC verifications match your current filters.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        @if($kycVerifications->count() > 0)
            <div class="row" id="bulkActionsRow" style="display: none;">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span id="selectedCount">0</span> items selected
                                </div>
                                <div>
                                    <button type="button" class="btn btn-success me-2" onclick="bulkApprove()">
                                        <i class="bx bx-check"></i> Bulk Approve
                                    </button>
                                    <button type="button" class="btn btn-danger me-2" onclick="bulkChangeStatus('rejected')">
                                        <i class="bx bx-x"></i> Bulk Reject
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="bulkChangeStatus('under_review')">
                                        <i class="bx bx-search-alt"></i> Mark Under Review
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="statusForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Update KYC Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="statusSelect" class="form-select" required>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="under_review">Under Review</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                    <div class="mb-3" id="rejectionReasonDiv" style="display: none;">
                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Admin Notes</label>
                        <textarea name="admin_notes" class="form-control" rows="3" 
                                  placeholder="Optional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Select all functionality
    $('#selectAll').change(function() {
        $('.kyc-checkbox').prop('checked', this.checked);
        updateBulkActions();
    });
    
    $('.kyc-checkbox').change(function() {
        updateBulkActions();
    });
    
    function updateBulkActions() {
        const selected = $('.kyc-checkbox:checked').length;
        $('#selectedCount').text(selected);
        
        if (selected > 0) {
            $('#bulkActionsRow').show();
        } else {
            $('#bulkActionsRow').hide();
        }
        
        // Update select all checkbox
        const total = $('.kyc-checkbox').length;
        $('#selectAll').prop('checked', selected === total);
    }
    
    // Status change handler
    $('#statusSelect').change(function() {
        if ($(this).val() === 'rejected') {
            $('#rejectionReasonDiv').show();
            $('textarea[name="rejection_reason"]').prop('required', true);
        } else {
            $('#rejectionReasonDiv').hide();
            $('textarea[name="rejection_reason"]').prop('required', false);
        }
    });
});

function updateStatus(kycId, status) {
    $('#statusForm').attr('action', `/admin/vendor-kyc/${kycId}/update-status`);
    $('#statusSelect').val(status).trigger('change');
    $('#statusModal').modal('show');
}

function bulkApprove() {
    const selected = $('.kyc-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (selected.length === 0) {
        alert('Please select at least one item');
        return;
    }
    
    if (confirm(`Are you sure you want to approve ${selected.length} vendor KYC verifications?`)) {
        const form = $('<form>', {
            method: 'POST',
            action: '{{ route("admin.vendor-kyc.bulk-approve") }}'
        });
        
        form.append($('<input>', {
            type: 'hidden',
            name: '_token',
            value: '{{ csrf_token() }}'
        }));
        
        selected.forEach(function(id) {
            form.append($('<input>', {
                type: 'hidden',
                name: 'kyc_ids[]',
                value: id
            }));
        });
        
        $('body').append(form);
        form.submit();
    }
}

function bulkChangeStatus(status) {
    const selected = $('.kyc-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (selected.length === 0) {
        alert('Please select at least one item');
        return;
    }
    
    if (confirm(`Are you sure you want to change ${selected.length} vendor KYC verifications to ${status}?`)) {
        const form = $('<form>', {
            method: 'POST',
            action: '{{ route("admin.vendor-kyc.bulk-change-status") }}'
        });
        
        form.append($('<input>', {
            type: 'hidden',
            name: '_token',
            value: '{{ csrf_token() }}'
        }));
        
        form.append($('<input>', {
            type: 'hidden',
            name: 'status',
            value: status
        }));
        
        selected.forEach(function(id) {
            form.append($('<input>', {
                type: 'hidden',
                name: 'kyc_ids[]',
                value: id
            }));
        });
        
        $('body').append(form);
        form.submit();
    }
}
</script>
@endpush