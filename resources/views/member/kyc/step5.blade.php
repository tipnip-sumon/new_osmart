@extends('member.layouts.app')

@section('title', 'KYC Step 5 - Review & Submit')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">KYC Verification - Step 5</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.kyc.index') }}">KYC</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Review & Submit</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Progress Bar -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0">Step 5 of 5: Review & Submit</h6>
                            <span class="badge bg-success">100% Complete</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step Navigation -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="step-navigation d-flex align-items-center">
                                <div class="step completed">
                                    <div class="step-circle bg-success text-white">✓</div>
                                    <span class="step-label">Personal Info</span>
                                </div>
                                <div class="step-arrow text-success">→</div>
                                <div class="step completed">
                                    <div class="step-circle bg-success text-white">✓</div>
                                    <span class="step-label">Document Info</span>
                                </div>
                                <div class="step-arrow text-success">→</div>
                                <div class="step completed">
                                    <div class="step-circle bg-success text-white">✓</div>
                                    <span class="step-label">Address</span>
                                </div>
                                <div class="step-arrow text-success">→</div>
                                <div class="step completed">
                                    <div class="step-circle bg-success text-white">✓</div>
                                    <span class="step-label">Documents</span>
                                </div>
                                <div class="step-arrow text-success">→</div>
                                <div class="step active">
                                    <div class="step-circle bg-primary text-white">5</div>
                                    <span class="step-label">Review</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Information -->
        <div class="row">
            <!-- Personal Information Review -->
            <div class="col-xl-6 col-lg-12">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title">Personal Information</div>
                        <a href="{{ route('member.kyc.step', 1) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fe fe-edit"></i> Edit
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <strong>Full Name:</strong><br>
                                <span class="text-muted">{{ $kyc->full_name ?: 'Not provided' }}</span>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <strong>Father's Name:</strong><br>
                                <span class="text-muted">{{ $kyc->father_name ?: 'Not provided' }}</span>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <strong>Mother's Name:</strong><br>
                                <span class="text-muted">{{ $kyc->mother_name ?: 'Not provided' }}</span>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <strong>Date of Birth:</strong><br>
                                <span class="text-muted">{{ $kyc->date_of_birth ? $kyc->date_of_birth->format('M d, Y') : 'Not provided' }}</span>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <strong>Gender:</strong><br>
                                <span class="text-muted">{{ ucwords($kyc->gender ?: 'Not provided') }}</span>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <strong>Marital Status:</strong><br>
                                <span class="text-muted">{{ ucwords($kyc->marital_status ?: 'Not provided') }}</span>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <strong>Nationality:</strong><br>
                                <span class="text-muted">{{ $kyc->nationality ?: 'Not provided' }}</span>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <strong>Religion:</strong><br>
                                <span class="text-muted">{{ $kyc->religion ?: 'Not provided' }}</span>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <strong>Profession:</strong><br>
                                <span class="text-muted">{{ $kyc->profession ?: 'Not provided' }}</span>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <strong>Monthly Income:</strong><br>
                                <span class="text-muted">{{ $kyc->monthly_income ? '৳ ' . number_format($kyc->monthly_income) : 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Information Review -->
            <div class="col-xl-6 col-lg-12">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title">Document Information</div>
                        <a href="{{ route('member.kyc.step', 2) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fe fe-edit"></i> Edit
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <strong>Document Type:</strong><br>
                                <span class="text-muted">{{ ucwords(str_replace('_', ' ', $kyc->document_type ?: 'Not selected')) }}</span>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <strong>Document Number:</strong><br>
                                <span class="text-muted">{{ $kyc->document_number ?: 'Not provided' }}</span>
                            </div>
                            @if($kyc->document_type === 'nid')
                                <div class="col-sm-6 mb-2">
                                    <strong>NID Type:</strong><br>
                                    <span class="text-muted">{{ ucwords(str_replace('_', ' ', $kyc->nid_type ?: 'Not selected')) }}</span>
                                </div>
                                @if($kyc->voter_id)
                                    <div class="col-sm-6 mb-2">
                                        <strong>Voter ID:</strong><br>
                                        <span class="text-muted">{{ $kyc->voter_id }}</span>
                                    </div>
                                @endif
                            @endif
                            @if(in_array($kyc->document_type, ['passport', 'driving_license']))
                                <div class="col-sm-6 mb-2">
                                    <strong>Issue Date:</strong><br>
                                    <span class="text-muted">{{ $kyc->document_issue_date ? $kyc->document_issue_date->format('M d, Y') : 'Not provided' }}</span>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <strong>Expiry Date:</strong><br>
                                    <span class="text-muted">{{ $kyc->document_expiry_date ? $kyc->document_expiry_date->format('M d, Y') : 'Not provided' }}</span>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <strong>Issuing Authority:</strong><br>
                                    <span class="text-muted">{{ $kyc->document_issuer ?: 'Not provided' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information Review -->
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title">Address & Contact Information</div>
                        <a href="{{ route('member.kyc.step', 3) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fe fe-edit"></i> Edit
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Present Address</h6>
                                <div class="mb-2">
                                    <strong>Address:</strong><br>
                                    <span class="text-muted">{{ $kyc->present_address ?: 'Not provided' }}</span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-2">
                                        <strong>District:</strong><br>
                                        <span class="text-muted">{{ $kyc->present_district ?: 'Not provided' }}</span>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <strong>Upazila:</strong><br>
                                        <span class="text-muted">{{ $kyc->present_upazila ?: 'Not provided' }}</span>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <strong>Union/Ward:</strong><br>
                                        <span class="text-muted">{{ $kyc->present_union_ward ?: 'Not provided' }}</span>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <strong>Postal Code:</strong><br>
                                        <span class="text-muted">{{ $kyc->present_postal_code ?: 'Not provided' }}</span>
                                    </div>
                                </div>

                                <h6 class="text-primary mb-3 mt-4">Contact Information</h6>
                                <div class="row">
                                    <div class="col-sm-6 mb-2">
                                        <strong>Phone:</strong><br>
                                        <span class="text-muted">{{ $kyc->phone_number ?: 'Not provided' }}</span>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <strong>Alternative Phone:</strong><br>
                                        <span class="text-muted">{{ $kyc->alternative_phone ?: 'Not provided' }}</span>
                                    </div>
                                    <div class="col-sm-12 mb-2">
                                        <strong>Email:</strong><br>
                                        <span class="text-muted">{{ $kyc->email_address ?: 'Not provided' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    Permanent Address
                                    @if($kyc->same_as_present_address)
                                        <span class="badge bg-info ms-2">Same as Present</span>
                                    @endif
                                </h6>
                                <div class="mb-2">
                                    <strong>Address:</strong><br>
                                    <span class="text-muted">{{ $kyc->permanent_address ?: 'Not provided' }}</span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-2">
                                        <strong>District:</strong><br>
                                        <span class="text-muted">{{ $kyc->permanent_district ?: 'Not provided' }}</span>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <strong>Upazila:</strong><br>
                                        <span class="text-muted">{{ $kyc->permanent_upazila ?: 'Not provided' }}</span>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <strong>Union/Ward:</strong><br>
                                        <span class="text-muted">{{ $kyc->permanent_union_ward ?: 'Not provided' }}</span>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <strong>Postal Code:</strong><br>
                                        <span class="text-muted">{{ $kyc->permanent_postal_code ?: 'Not provided' }}</span>
                                    </div>
                                </div>

                                <h6 class="text-primary mb-3 mt-4">Emergency Contact</h6>
                                <div class="mb-2">
                                    <strong>Name:</strong><br>
                                    <span class="text-muted">{{ $kyc->emergency_contact_name ?: 'Not provided' }}</span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-2">
                                        <strong>Relationship:</strong><br>
                                        <span class="text-muted">{{ $kyc->emergency_contact_relationship ?: 'Not provided' }}</span>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <strong>Phone:</strong><br>
                                        <span class="text-muted">{{ $kyc->emergency_contact_phone ?: 'Not provided' }}</span>
                                    </div>
                                    <div class="col-sm-12 mb-2">
                                        <strong>Address:</strong><br>
                                        <span class="text-muted">{{ $kyc->emergency_contact_address ?: 'Not provided' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Uploaded Documents Review -->
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title">Uploaded Documents</div>
                        <a href="{{ route('member.kyc.step', 4) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fe fe-edit"></i> Edit
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($kyc->document_front_image)
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="text-center">
                                        <img src="{{ asset('storage/' . $kyc->document_front_image) }}" 
                                             alt="Document Front" class="img-fluid rounded border" style="max-height: 150px;">
                                        <p class="text-muted mt-2 small">{{ ucwords(str_replace('_', ' ', $kyc->document_type)) }} - Front</p>
                                    </div>
                                </div>
                            @endif
                            @if($kyc->document_back_image)
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="text-center">
                                        <img src="{{ asset('storage/' . $kyc->document_back_image) }}" 
                                             alt="Document Back" class="img-fluid rounded border" style="max-height: 150px;">
                                        <p class="text-muted mt-2 small">{{ ucwords(str_replace('_', ' ', $kyc->document_type)) }} - Back</p>
                                    </div>
                                </div>
                            @endif
                            @if($kyc->user_photo)
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="text-center">
                                        <img src="{{ asset('storage/' . $kyc->user_photo) }}" 
                                             alt="User Photo" class="img-fluid rounded border" style="max-height: 150px;">
                                        <p class="text-muted mt-2 small">User Photo</p>
                                    </div>
                                </div>
                            @endif
                            @if($kyc->user_signature)
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="text-center">
                                        <img src="{{ asset('storage/' . $kyc->user_signature) }}" 
                                             alt="User Signature" class="img-fluid rounded border" style="max-height: 150px;">
                                        <p class="text-muted mt-2 small">Signature</p>
                                    </div>
                                </div>
                            @endif
                            @if($kyc->utility_bill)
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="text-center">
                                        @if(Str::endsWith($kyc->utility_bill, ['.pdf']))
                                            <div class="border rounded p-3" style="height: 150px; display: flex; align-items: center; justify-content: center;">
                                                <div>
                                                    <i class="fe fe-file-text display-4 text-danger"></i>
                                                    <p class="mb-0 small">{{ basename($kyc->utility_bill) }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <img src="{{ asset('storage/' . $kyc->utility_bill) }}" 
                                                 alt="Utility Bill" class="img-fluid rounded border" style="max-height: 150px;">
                                        @endif
                                        <p class="text-muted mt-2 small">Utility Bill</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        @if(!$kyc->document_front_image || !$kyc->user_photo)
                            <div class="alert alert-warning">
                                <i class="fe fe-alert-triangle"></i> 
                                Some required documents are missing. Please upload all required documents before submitting.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Profile Comparison -->
            @if($kyc->profile_mismatches && count($kyc->profile_mismatches) > 0)
                <div class="col-xl-12">
                    <div class="card custom-card border-warning">
                        <div class="card-header">
                            <div class="card-title text-warning">
                                <i class="fe fe-alert-triangle"></i> Profile Information Mismatch
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">The following information doesn't match your current profile:</p>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Field</th>
                                            <th>Profile Value</th>
                                            <th>KYC Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kyc->profile_mismatches as $mismatch)
                                            <tr>
                                                <td><strong>{{ ucwords(str_replace('_', ' ', $mismatch['field'])) }}</strong></td>
                                                <td class="text-muted">{{ $mismatch['profile_value'] }}</td>
                                                <td class="text-primary">{{ $mismatch['kyc_value'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <p class="text-muted mb-0">
                                <small>After KYC verification, you can choose to update your profile with the verified information.</small>
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Submission Form -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Submit for Verification</div>
                    </div>
                    <div class="card-body">
                        <form id="kycStep5Form" action="{{ route('member.kyc.save-step', 5) }}" method="POST">
                            @csrf

                            <!-- Terms and Declaration -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="terms_accepted" name="terms_accepted" required>
                                <label class="form-check-label" for="terms_accepted">
                                    I agree to the <a href="#" target="_blank">Terms and Conditions</a> and confirm that all information provided is accurate and complete. <span class="text-danger">*</span>
                                </label>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="declaration_accepted" name="declaration_accepted" required>
                                <label class="form-check-label" for="declaration_accepted">
                                    I declare that the information and documents provided are genuine and accurate to the best of my knowledge. I understand that providing false information may result in account suspension. <span class="text-danger">*</span>
                                </label>
                            </div>

                            <!-- Information Notice -->
                            <div class="alert alert-info">
                                <h6><i class="fe fe-info"></i> What happens next?</h6>
                                <ul class="mb-0">
                                    <li>Your KYC will be submitted for manual verification</li>
                                    <li>Our team will review all provided information and documents</li>
                                    <li>Verification usually takes 1-3 business days</li>
                                    <li>You will be notified via email about the verification status</li>
                                    <li>Once verified, you'll have full access to all platform features</li>
                                </ul>
                            </div>

                            <!-- Form Actions -->
                            @if($kyc->status === 'verified')
                                <div class="alert alert-success text-center mt-4">
                                    <i class="fe fe-check-circle fs-1 text-success mb-2 d-block"></i>
                                    <h5 class="text-success">KYC Already Verified</h5>
                                    <p class="mb-0">Your identity verification is complete. No further action is required.</p>
                                    <div class="mt-3">
                                        <a href="{{ route('member.kyc.index') }}" class="btn btn-primary">
                                            <i class="fe fe-arrow-left"></i> Back to KYC Dashboard
                                        </a>
                                        <a href="{{ route('member.kyc.certificate') }}" class="btn btn-success ms-2">
                                            <i class="fe fe-download"></i> Download Certificate
                                        </a>
                                    </div>
                                </div>
                            @elseif($kyc->status === 'pending')
                                <div class="alert alert-info text-center mt-4">
                                    <i class="fe fe-clock fs-1 text-info mb-2 d-block"></i>
                                    <h5 class="text-info">KYC Under Review</h5>
                                    <p class="mb-0">Your KYC is currently being reviewed. Please wait for the verification process to complete.</p>
                                    <div class="mt-3">
                                        <a href="{{ route('member.kyc.index') }}" class="btn btn-primary">
                                            <i class="fe fe-arrow-left"></i> Back to KYC Dashboard
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('member.kyc.step', 4) }}" class="btn btn-outline-secondary">
                                        <i class="fe fe-arrow-left"></i> Previous Step
                                    </a>
                                    <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                        <i class="fe fe-send"></i> Submit for Verification
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.step-navigation {
    gap: 1rem;
}
.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    min-width: 80px;
}
.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}
.step-label {
    font-size: 0.85rem;
    text-align: center;
}
.step-arrow {
    font-size: 1.2rem;
    margin-top: -1.5rem;
}
@media (max-width: 768px) {
    .step-navigation {
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .step {
        min-width: 60px;
    }
    .step-circle {
        width: 30px;
        height: 30px;
    }
    .step-label {
        font-size: 0.75rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('kycStep5Form');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!document.getElementById('terms_accepted').checked || !document.getElementById('declaration_accepted').checked) {
            alert('Please accept both terms and declaration to proceed');
            return;
        }
        
        if (confirm('Are you sure you want to submit your KYC for verification? Once submitted, you cannot make changes until the review is complete.')) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fe fe-loader"></i> Submitting...';
            
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('KYC submitted successfully! You will receive an email notification once the verification is complete.');
                    window.location.href = '{{ route("member.kyc.index") }}';
                } else {
                    alert('Error: ' + data.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fe fe-send"></i> Submit for Verification';
                }
            })
            .catch(error => {
                console.error('Submission error:', error);
                alert('Submission failed. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fe fe-send"></i> Submit for Verification';
            });
        }
    });
});
</script>
@endsection