@extends('admin.layouts.app')

@section('title', 'Vendor KYC Details - ' . $kyc->business_name)

@section('content')
<div class="main-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div>
                <h1 class="page-title fw-semibold fs-18 mb-0">Vendor KYC Details</h1>
                <div>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.vendor-kyc.index') }}">Vendor KYC</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $kyc->business_name ?? 'Details' }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="ms-md-1 ms-0">
                <div class="btn-group">
                    <a href="{{ route('admin.vendor-kyc.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Back to List
                    </a>
                    @if($kyc->status === 'pending')
                        <button type="button" class="btn btn-success" onclick="updateStatus('approved')">
                            <i class="bx bx-check"></i> Approve
                        </button>
                        <button type="button" class="btn btn-danger" onclick="updateStatus('rejected')">
                            <i class="bx bx-x"></i> Reject
                        </button>
                        <button type="button" class="btn btn-info" onclick="updateStatus('under_review')">
                            <i class="bx bx-search-alt"></i> Mark Under Review
                        </button>
                    @endif
                </div>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <!-- KYC Overview -->
            <div class="col-xl-4 col-lg-5">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            KYC Overview
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Status Badge -->
                        <div class="text-center mb-4">
                            @switch($kyc->status)
                                @case('approved')
                                    <span class="badge bg-success fs-14 px-3 py-2">
                                        <i class="bx bx-check me-1"></i> Approved
                                    </span>
                                    @break
                                @case('rejected')
                                    <span class="badge bg-danger fs-14 px-3 py-2">
                                        <i class="bx bx-x me-1"></i> Rejected
                                    </span>
                                    @break
                                @case('pending')
                                    <span class="badge bg-warning fs-14 px-3 py-2">
                                        <i class="bx bx-time me-1"></i> Pending Review
                                    </span>
                                    @break
                                @case('under_review')
                                    <span class="badge bg-info fs-14 px-3 py-2">
                                        <i class="bx bx-search-alt me-1"></i> Under Review
                                    </span>
                                    @break
                                @default
                                    <span class="badge bg-secondary fs-14 px-3 py-2">
                                        <i class="bx bx-edit me-1"></i> Draft
                                    </span>
                            @endswitch
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Completion Progress</span>
                                <span class="fw-semibold">{{ number_format($kyc->completion_percentage, 2) }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" 
                                     style="width: {{ $kyc->completion_percentage }}%" 
                                     aria-valuenow="{{ $kyc->completion_percentage }}" 
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <!-- Key Information -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Vendor ID</label>
                                    <p class="mb-0 fw-semibold">{{ $kyc->vendor_id ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Submitted Date</label>
                                    <p class="mb-0">
                                        @if($kyc->submitted_at)
                                            {{ $kyc->submitted_at->format('M d, Y h:i A') }}
                                        @else
                                            <span class="text-muted">Not submitted</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Last Updated</label>
                                    <p class="mb-0">{{ $kyc->updated_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                            @if($kyc->approved_at)
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Approved Date</label>
                                        <p class="mb-0">{{ $kyc->approved_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Admin Notes -->
                        @if($kyc->admin_notes)
                            <div class="alert alert-info" role="alert">
                                <h6 class="alert-heading">Admin Notes</h6>
                                <p class="mb-0">{{ $kyc->admin_notes }}</p>
                            </div>
                        @endif

                        <!-- Rejection Reason -->
                        @if($kyc->status === 'rejected' && $kyc->rejection_reason)
                            <div class="alert alert-danger" role="alert">
                                <h6 class="alert-heading">Rejection Reason</h6>
                                <p class="mb-0">{{ $kyc->rejection_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- KYC Details -->
            <div class="col-xl-8 col-lg-7">
                <div class="row">
                    <!-- Step 1: Business Information -->
                    <div class="col-xl-6 mb-4">
                        <div class="card custom-card">
                            <div class="card-header">
                                <div class="card-title">
                                    <i class="bx bx-buildings me-2 text-primary"></i>
                                    Business Information
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted">Business Name</label>
                                        <p class="mb-0 fw-semibold">{{ $kyc->business_name ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted">Business Type</label>
                                        <p class="mb-0">{{ $kyc->business_type ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted">Registration Number</label>
                                        <p class="mb-0">{{ $kyc->business_registration_number ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted">Establishment Date</label>
                                        <p class="mb-0">
                                            @if($kyc->business_establishment_date)
                                                {{ \Carbon\Carbon::parse($kyc->business_establishment_date)->format('M d, Y') }}
                                            @else
                                                Not provided
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted">Business Description</label>
                                        <p class="mb-0">{{ Str::limit($kyc->business_description, 100) ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Owner Information -->
                    <div class="col-xl-6 mb-4">
                        <div class="card custom-card">
                            <div class="card-header">
                                <div class="card-title">
                                    <i class="bx bx-user me-2 text-info"></i>
                                    Owner Information
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted">Full Name</label>
                                        <p class="mb-0 fw-semibold">{{ $kyc->owner_full_name ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted">Date of Birth</label>
                                        <p class="mb-0">
                                            @if($kyc->owner_date_of_birth)
                                                {{ \Carbon\Carbon::parse($kyc->owner_date_of_birth)->format('M d, Y') }}
                                            @else
                                                Not provided
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted">Nationality</label>
                                        <p class="mb-0">{{ $kyc->owner_nationality ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted">NID Number</label>
                                        <p class="mb-0">{{ $kyc->owner_nid_number ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Contact Information -->
                    <div class="col-xl-6 mb-4">
                        <div class="card custom-card">
                            <div class="card-header">
                                <div class="card-title">
                                    <i class="bx bx-phone me-2 text-success"></i>
                                    Contact Information
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted">Phone Number</label>
                                        <p class="mb-0">{{ $kyc->phone_number ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted">Email Address</label>
                                        <p class="mb-0">{{ $kyc->email_address ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted">Website</label>
                                        <p class="mb-0">
                                            @if($kyc->website_url)
                                                <a href="{{ $kyc->website_url }}" target="_blank">{{ $kyc->website_url }}</a>
                                            @else
                                                Not provided
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Address Information -->
                    <div class="col-xl-6 mb-4">
                        <div class="card custom-card">
                            <div class="card-header">
                                <div class="card-title">
                                    <i class="bx bx-map me-2 text-warning"></i>
                                    Address Information
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted">Business Address</label>
                                        <p class="mb-0">{{ $kyc->business_address ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label text-muted">Division</label>
                                        <p class="mb-0">{{ $kyc->division ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label text-muted">District</label>
                                        <p class="mb-0">{{ $kyc->district ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label text-muted">Upazila</label>
                                        <p class="mb-0">{{ $kyc->upazila ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label text-muted">Postal Code</label>
                                        <p class="mb-0">{{ $kyc->postal_code ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Document Uploads -->
                    <div class="col-xl-12 mb-4">
                        <div class="card custom-card">
                            <div class="card-header">
                                <div class="card-title">
                                    <i class="bx bx-file me-2 text-danger"></i>
                                    Document Uploads
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Business License -->
                                    <div class="col-xl-4 mb-3">
                                        <label class="form-label">Business License</label>
                                        @if($kyc->business_license_path)
                                            <div class="border p-3 rounded">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-file fs-24 text-primary me-2"></i>
                                                        <span class="text-truncate">Business License</span>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('admin.vendor-kyc.view-document', ['kyc' => $kyc->id, 'document' => 'business_license']) }}" 
                                                           class="btn btn-sm btn-primary" target="_blank">
                                                            <i class="bx bx-show"></i> View
                                                        </a>
                                                        <a href="{{ route('admin.vendor-kyc.download-document', ['kyc' => $kyc->id, 'document' => 'business_license']) }}" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="bx bx-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-muted">Not uploaded</div>
                                        @endif
                                    </div>

                                    <!-- Owner NID -->
                                    <div class="col-xl-4 mb-3">
                                        <label class="form-label">Owner NID Copy</label>
                                        @if($kyc->owner_nid_copy_path)
                                            <div class="border p-3 rounded">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-file fs-24 text-info me-2"></i>
                                                        <span class="text-truncate">NID Copy</span>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('admin.vendor-kyc.view-document', ['kyc' => $kyc->id, 'document' => 'owner_nid_copy']) }}" 
                                                           class="btn btn-sm btn-primary" target="_blank">
                                                            <i class="bx bx-show"></i> View
                                                        </a>
                                                        <a href="{{ route('admin.vendor-kyc.download-document', ['kyc' => $kyc->id, 'document' => 'owner_nid_copy']) }}" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="bx bx-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-muted">Not uploaded</div>
                                        @endif
                                    </div>

                                    <!-- Utility Bill -->
                                    <div class="col-xl-4 mb-3">
                                        <label class="form-label">Utility Bill</label>
                                        @if($kyc->utility_bill_path)
                                            <div class="border p-3 rounded">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-file fs-24 text-success me-2"></i>
                                                        <span class="text-truncate">Utility Bill</span>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('admin.vendor-kyc.view-document', ['kyc' => $kyc->id, 'document' => 'utility_bill']) }}" 
                                                           class="btn btn-sm btn-primary" target="_blank">
                                                            <i class="bx bx-show"></i> View
                                                        </a>
                                                        <a href="{{ route('admin.vendor-kyc.download-document', ['kyc' => $kyc->id, 'document' => 'utility_bill']) }}" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="bx bx-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-muted">Not uploaded</div>
                                        @endif
                                    </div>

                                    <!-- Tax Certificate -->
                                    <div class="col-xl-4 mb-3">
                                        <label class="form-label">Tax Certificate</label>
                                        @if($kyc->tax_certificate_path)
                                            <div class="border p-3 rounded">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-file fs-24 text-warning me-2"></i>
                                                        <span class="text-truncate">Tax Certificate</span>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('admin.vendor-kyc.view-document', ['kyc' => $kyc->id, 'document' => 'tax_certificate']) }}" 
                                                           class="btn btn-sm btn-primary" target="_blank">
                                                            <i class="bx bx-show"></i> View
                                                        </a>
                                                        <a href="{{ route('admin.vendor-kyc.download-document', ['kyc' => $kyc->id, 'document' => 'tax_certificate']) }}" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="bx bx-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-muted">Not uploaded</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 6: Bank Information -->
                    <div class="col-xl-12">
                        <div class="card custom-card">
                            <div class="card-header">
                                <div class="card-title">
                                    <i class="bx bx-credit-card me-2 text-purple"></i>
                                    Bank Information
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-3 mb-3">
                                        <label class="form-label text-muted">Bank Name</label>
                                        <p class="mb-0">{{ $kyc->bank_name ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-xl-3 mb-3">
                                        <label class="form-label text-muted">Branch Name</label>
                                        <p class="mb-0">{{ $kyc->bank_branch ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-xl-3 mb-3">
                                        <label class="form-label text-muted">Account Holder Name</label>
                                        <p class="mb-0">{{ $kyc->bank_account_holder_name ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-xl-3 mb-3">
                                        <label class="form-label text-muted">Account Number</label>
                                        <p class="mb-0">{{ $kyc->bank_account_number ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-xl-3 mb-3">
                                        <label class="form-label text-muted">Routing Number</label>
                                        <p class="mb-0">{{ $kyc->bank_routing_number ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-xl-3 mb-3">
                                        <label class="form-label text-muted">SWIFT Code</label>
                                        <p class="mb-0">{{ $kyc->bank_swift_code ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="statusForm" method="POST" action="{{ route('admin.vendor-kyc.update-status', $kyc->id) }}">
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
                                  placeholder="Optional notes...">{{ $kyc->admin_notes }}</textarea>
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

function updateStatus(status) {
    $('#statusSelect').val(status).trigger('change');
    $('#statusModal').modal('show');
}
</script>
@endpush