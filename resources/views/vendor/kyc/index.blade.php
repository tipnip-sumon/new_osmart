@extends('admin.layouts.app')

@section('title', 'Vendor KYC Verification')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Vendor KYC Verification</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">KYC Verification</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- KYC Status Alert -->
        @if($kyc->status === 'rejected')
            <div class="alert alert-danger">
                <h5><i class="fe fe-alert-triangle"></i> KYC Rejected</h5>
                <p>{{ $kyc->rejection_reason }}</p>
                <small>You can update your information and resubmit for verification.</small>
            </div>
        @elseif($kyc->status === 'pending')
            <div class="alert alert-info">
                <h5><i class="fe fe-clock"></i> KYC Under Review</h5>
                <p>Your KYC documents are being reviewed by our team. This process may take 1-3 business days.</p>
                <small>Submitted on: {{ $kyc->submitted_at->format('M d, Y h:i A') }}</small>
            </div>
        @elseif($kyc->status === 'under_review')
            <div class="alert alert-warning">
                <h5><i class="fe fe-eye"></i> KYC Under Review</h5>
                <p>Your KYC documents are currently being reviewed by our verification team.</p>
                @if($kyc->submitted_at)
                    <small>Submitted on: {{ $kyc->submitted_at->format('M d, Y h:i A') }}</small>
                @endif
            </div>
        @elseif($kyc->status === 'approved')
            <div class="alert alert-success">
                <h5><i class="fe fe-check-circle"></i> KYC Approved</h5>
                <p>Your vendor identity has been successfully verified and approved.</p>
                @if($kyc->approved_at)
                    <small>Approved on: {{ $kyc->approved_at->format('M d, Y h:i A') }}</small>
                @endif
            </div>
        @endif

        <!-- KYC Progress -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Verification Progress
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="text-muted">Completion:</span>
                                <span class="fw-semibold">{{ number_format($kyc->completion_percentage, 2) }}%</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="progress mb-4" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ $kyc->completion_percentage }}%" 
                                 aria-valuenow="{{ $kyc->completion_percentage }}" 
                                 aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>

                        <!-- Step Cards -->
                        <div class="row">
                            @foreach($steps as $stepNumber => $stepInfo)
                                <div class="col-lg-12 mb-3">
                                    <div class="card border {{ $stepInfo['completed'] ? 'border-success' : 'border-light' }}">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <div class="avatar avatar-lg {{ $stepInfo['completed'] ? 'bg-success' : 'bg-light' }} text-{{ $stepInfo['completed'] ? 'white' : 'muted' }}">
                                                        @if($stepInfo['completed'])
                                                            <i class="fe fe-check"></i>
                                                        @else
                                                            <i class="{{ $stepInfo['icon'] }}"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-fill">
                                                    <h6 class="mb-1">Step {{ $stepNumber }}: {{ $stepInfo['title'] }}</h6>
                                                    <p class="text-muted mb-0">{{ $stepInfo['description'] }}</p>
                                                </div>
                                                <div class="ms-3">
                                                    @if($kyc->status !== 'approved' && $kyc->status !== 'pending' && $kyc->status !== 'under_review')
                                                        @if($stepInfo['completed'])
                                                            <a href="{{ route('vendor.kyc.step', $stepNumber) }}" class="btn btn-outline-primary btn-sm">
                                                                <i class="fe fe-edit"></i> Edit
                                                            </a>
                                                        @elseif($stepNumber <= $kyc->current_step || $stepNumber === 1)
                                                            <a href="{{ route('vendor.kyc.step', $stepNumber) }}" class="btn btn-primary btn-sm">
                                                                <i class="fe fe-arrow-right"></i> {{ $stepInfo['completed'] ? 'Edit' : 'Continue' }}
                                                            </a>
                                                        @else
                                                            <span class="badge bg-light text-muted">Locked</span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Profile Mismatches -->
                        @if($kyc->profile_mismatches && is_array($kyc->profile_mismatches) && count($kyc->profile_mismatches) > 0)
                            <div class="alert alert-warning mt-4">
                                <h6><i class="fe fe-alert-triangle"></i> Profile Information Mismatch</h6>
                                <p class="mb-2">The following information doesn't match your profile:</p>
                                <ul class="mb-0">
                                    @foreach($kyc->profile_mismatches as $mismatch)
                                        @if(is_array($mismatch) && isset($mismatch['profile_value']) && isset($mismatch['kyc_value']))
                                            <li><strong>{{ ucwords(str_replace('_', ' ', $mismatch['field'])) }}:</strong> 
                                                Profile: "{{ $mismatch['profile_value'] }}" vs KYC: "{{ $mismatch['kyc_value'] }}"</li>
                                        @endif
                                    @endforeach
                                </ul>
                                @if($kyc->status === 'approved')
                                    <div class="mt-3">
                                        <button class="btn btn-warning btn-sm" onclick="updateProfileFromKyc()">
                                            <i class="fe fe-refresh-cw"></i> Update Profile from KYC
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="text-center mt-4">
                            @if($kyc->status === 'draft' && $kyc->completion_percentage === 100)
                                <a href="{{ route('vendor.kyc.step', 6) }}" class="btn btn-success btn-lg">
                                    <i class="fe fe-send"></i> Submit for Verification
                                </a>
                            @elseif($kyc->status === 'draft')
                                <a href="{{ route('vendor.kyc.step', $kyc->current_step) }}" class="btn btn-primary btn-lg">
                                    <i class="fe fe-arrow-right"></i> Continue KYC
                                </a>
                            @elseif($kyc->status === 'rejected')
                                <a href="{{ route('vendor.kyc.resubmit') }}" class="btn btn-warning btn-lg">
                                    <i class="fe fe-refresh-cw"></i> Resubmit KYC
                                </a>
                            @elseif($kyc->status === 'approved' && $kyc->certificate_generated_at)
                                <a href="{{ route('vendor.kyc.certificate') }}" class="btn btn-success btn-lg">
                                    <i class="fe fe-download"></i> Download Certificate
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Preview -->
        @if($kyc->document_front_image || $kyc->owner_photo || $kyc->business_license)
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Uploaded Documents</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($kyc->document_front_image)
                                    <div class="col-md-4 mb-3">
                                        <div class="text-center">
                                            <img src="{{ asset('storage/' . $kyc->document_front_image) }}" 
                                                 alt="Document Front" class="img-fluid rounded border" style="max-height: 200px;">
                                            <p class="text-muted mt-2">{{ ucwords(str_replace('_', ' ', $kyc->document_type)) }} - Front</p>
                                        </div>
                                    </div>
                                @endif
                                @if($kyc->document_back_image)
                                    <div class="col-md-4 mb-3">
                                        <div class="text-center">
                                            <img src="{{ asset('storage/' . $kyc->document_back_image) }}" 
                                                 alt="Document Back" class="img-fluid rounded border" style="max-height: 200px;">
                                            <p class="text-muted mt-2">{{ ucwords(str_replace('_', ' ', $kyc->document_type)) }} - Back</p>
                                        </div>
                                    </div>
                                @endif
                                @if($kyc->owner_photo)
                                    <div class="col-md-4 mb-3">
                                        <div class="text-center">
                                            <img src="{{ asset('storage/' . $kyc->owner_photo) }}" 
                                                 alt="Owner Photo" class="img-fluid rounded border" style="max-height: 200px;">
                                            <p class="text-muted mt-2">Owner Photo</p>
                                        </div>
                                    </div>
                                @endif
                                @if($kyc->business_license)
                                    <div class="col-md-4 mb-3">
                                        <div class="text-center">
                                            @if(pathinfo($kyc->business_license, PATHINFO_EXTENSION) === 'pdf')
                                                <div class="border rounded p-4" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fe fe-file-text" style="font-size: 3rem; color: #dc3545;"></i>
                                                </div>
                                            @else
                                                <img src="{{ asset('storage/' . $kyc->business_license) }}" 
                                                     alt="Business License" class="img-fluid rounded border" style="max-height: 200px;">
                                            @endif
                                            <p class="text-muted mt-2">Business License</p>
                                        </div>
                                    </div>
                                @endif
                                @if($kyc->tax_certificate)
                                    <div class="col-md-4 mb-3">
                                        <div class="text-center">
                                            @if(pathinfo($kyc->tax_certificate, PATHINFO_EXTENSION) === 'pdf')
                                                <div class="border rounded p-4" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fe fe-file-text" style="font-size: 3rem; color: #dc3545;"></i>
                                                </div>
                                            @else
                                                <img src="{{ asset('storage/' . $kyc->tax_certificate) }}" 
                                                     alt="Tax Certificate" class="img-fluid rounded border" style="max-height: 200px;">
                                            @endif
                                            <p class="text-muted mt-2">Tax Certificate</p>
                                        </div>
                                    </div>
                                @endif
                                @if($kyc->bank_statement)
                                    <div class="col-md-4 mb-3">
                                        <div class="text-center">
                                            @if(pathinfo($kyc->bank_statement, PATHINFO_EXTENSION) === 'pdf')
                                                <div class="border rounded p-4" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fe fe-file-text" style="font-size: 3rem; color: #dc3545;"></i>
                                                </div>
                                            @else
                                                <img src="{{ asset('storage/' . $kyc->bank_statement) }}" 
                                                     alt="Bank Statement" class="img-fluid rounded border" style="max-height: 200px;">
                                            @endif
                                            <p class="text-muted mt-2">Bank Statement</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
@push('scripts')
<script>
function updateProfileFromKyc() {
    if (confirm('This will update your profile with verified KYC information. Continue?')) {
        fetch('{{ route("vendor.kyc.update-profile") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error updating profile');
            console.error(error);
        });
    }
}
</script>
@endpush