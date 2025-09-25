@extends('admin.layouts.app')

@section('title', 'Vendor KYC Step 6 - Review & Submit')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Vendor KYC Verification - Step 6</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.kyc.index') }}">KYC</a></li>
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
                            <h6 class="mb-0">Step 6 of 6: Review & Submit</h6>
                            <span class="badge bg-success">{{ number_format($kyc->completion_percentage, 2) }}% Complete</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $kyc->completion_percentage }}%" aria-valuenow="{{ $kyc->completion_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
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
                                <div class="step">
                                    <div class="step-circle bg-success text-white">✓</div>
                                    <span class="step-label">Business Info</span>
                                </div>
                                <div class="step-arrow">→</div>
                                <div class="step">
                                    <div class="step-circle bg-success text-white">✓</div>
                                    <span class="step-label">Owner Info</span>
                                </div>
                                <div class="step-arrow">→</div>
                                <div class="step">
                                    <div class="step-circle bg-success text-white">✓</div>
                                    <span class="step-label">Document Info</span>
                                </div>
                                <div class="step-arrow">→</div>
                                <div class="step">
                                    <div class="step-circle bg-success text-white">✓</div>
                                    <span class="step-label">Address</span>
                                </div>
                                <div class="step-arrow">→</div>
                                <div class="step">
                                    <div class="step-circle bg-success text-white">✓</div>
                                    <span class="step-label">Documents</span>
                                </div>
                                <div class="step-arrow">→</div>
                                <div class="step active">
                                    <div class="step-circle bg-primary text-white">6</div>
                                    <span class="step-label">Review</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Sections -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Review Your Information</div>
                    </div>
                    <div class="card-body">
                        
                        <!-- Business Information Review -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3 d-flex justify-content-between align-items-center">
                                    Business Information
                                    <a href="{{ route('vendor.kyc.step', 1) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fe fe-edit"></i> Edit
                                    </a>
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="200"><strong>Business Name:</strong></td>
                                            <td>{{ $kyc->business_name ?: 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Business Type:</strong></td>
                                            <td>{{ $kyc->business_type ?: 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Registration Number:</strong></td>
                                            <td>{{ $kyc->business_registration_number ?: 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Established Year:</strong></td>
                                            <td>{{ $kyc->business_established_year ?: 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Website:</strong></td>
                                            <td>{{ $kyc->website ?: 'Not provided' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Owner Information Review -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3 d-flex justify-content-between align-items-center">
                                    Owner Information
                                    <a href="{{ route('vendor.kyc.step', 2) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fe fe-edit"></i> Edit
                                    </a>
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="200"><strong>Full Name:</strong></td>
                                            <td>{{ $kyc->owner_full_name ?: 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Father's Name:</strong></td>
                                            <td>{{ $kyc->owner_father_name ?: 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Mother's Name:</strong></td>
                                            <td>{{ $kyc->owner_mother_name ?: 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date of Birth:</strong></td>
                                            <td>{{ $kyc->owner_date_of_birth ? \Carbon\Carbon::parse($kyc->owner_date_of_birth)->format('d M Y') : 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Gender:</strong></td>
                                            <td>{{ $kyc->owner_gender ? ucfirst($kyc->owner_gender) : 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nationality:</strong></td>
                                            <td>{{ $kyc->owner_nationality ?: 'Not provided' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Document Information Review -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3 d-flex justify-content-between align-items-center">
                                    Document Information
                                    <a href="{{ route('vendor.kyc.step', 3) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fe fe-edit"></i> Edit
                                    </a>
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="200"><strong>Document Type:</strong></td>
                                            <td>{{ $kyc->document_type ? ucfirst(str_replace('_', ' ', $kyc->document_type)) : 'Not provided' }}</td>
                                        </tr>
                                        @if($kyc->document_type === 'nid')
                                        <tr>
                                            <td><strong>NID Type:</strong></td>
                                            <td>{{ $kyc->nid_type ? ucfirst($kyc->nid_type) : 'Not provided' }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td><strong>Document Number:</strong></td>
                                            <td>{{ $kyc->document_number ?: 'Not provided' }}</td>
                                        </tr>
                                        @if($kyc->passport_expiry_date)
                                        <tr>
                                            <td><strong>Expiry Date:</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($kyc->passport_expiry_date)->format('d M Y') }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Review -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3 d-flex justify-content-between align-items-center">
                                    Contact & Address Information
                                    <a href="{{ route('vendor.kyc.step', 4) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fe fe-edit"></i> Edit
                                    </a>
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-info mb-2">Contact Information</h6>
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td><strong>Phone:</strong></td>
                                                <td>{{ $kyc->phone_number ?: 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email:</strong></td>
                                                <td>{{ $kyc->email_address ?: 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Emergency Contact:</strong></td>
                                                <td>{{ $kyc->emergency_contact_name ?: 'Not provided' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-info mb-2">Bank Information</h6>
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td><strong>Bank Name:</strong></td>
                                                <td>{{ $kyc->bank_name ?: 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Account Number:</strong></td>
                                                <td>{{ $kyc->bank_account_number ? '****' . substr($kyc->bank_account_number, -4) : 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Account Type:</strong></td>
                                                <td>{{ $kyc->bank_account_type ? ucfirst($kyc->bank_account_type) : 'Not provided' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Document Status Review -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3 d-flex justify-content-between align-items-center">
                                    Uploaded Documents
                                    <a href="{{ route('vendor.kyc.step', 5) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fe fe-edit"></i> Edit
                                    </a>
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-info mb-2">Required Documents</h6>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                @if($kyc->document_front_image)
                                                    <i class="fe fe-check-circle text-success me-2"></i>
                                                    <span>Identity Document (Front) ✓</span>
                                                @else
                                                    <i class="fe fe-x-circle text-danger me-2"></i>
                                                    <span class="text-danger">Identity Document (Front) - Missing</span>
                                                @endif
                                            </li>
                                            @if($kyc->document_type === 'nid' && $kyc->nid_type === 'smart' || $kyc->document_type === 'driving_license')
                                            <li class="mb-2">
                                                @if($kyc->document_back_image)
                                                    <i class="fe fe-check-circle text-success me-2"></i>
                                                    <span>Identity Document (Back) ✓</span>
                                                @else
                                                    <i class="fe fe-x-circle text-danger me-2"></i>
                                                    <span class="text-danger">Identity Document (Back) - Missing</span>
                                                @endif
                                            </li>
                                            @endif
                                            <li class="mb-2">
                                                @if($kyc->owner_photo)
                                                    <i class="fe fe-check-circle text-success me-2"></i>
                                                    <span>Owner Photo ✓</span>
                                                @else
                                                    <i class="fe fe-x-circle text-danger me-2"></i>
                                                    <span class="text-danger">Owner Photo - Missing</span>
                                                @endif
                                            </li>
                                            <li class="mb-2">
                                                @if($kyc->business_license)
                                                    <i class="fe fe-check-circle text-success me-2"></i>
                                                    <span>Business License ✓</span>
                                                @else
                                                    <i class="fe fe-x-circle text-danger me-2"></i>
                                                    <span class="text-danger">Business License - Missing</span>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-info mb-2">Optional Documents</h6>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                @if($kyc->owner_signature)
                                                    <i class="fe fe-check-circle text-success me-2"></i>
                                                    <span>Owner Signature ✓</span>
                                                @else
                                                    <i class="fe fe-minus-circle text-muted me-2"></i>
                                                    <span class="text-muted">Owner Signature - Optional</span>
                                                @endif
                                            </li>
                                            <li class="mb-2">
                                                @if($kyc->utility_bill)
                                                    <i class="fe fe-check-circle text-success me-2"></i>
                                                    <span>Utility Bill ✓</span>
                                                @else
                                                    <i class="fe fe-minus-circle text-muted me-2"></i>
                                                    <span class="text-muted">Utility Bill - Optional</span>
                                                @endif
                                            </li>
                                            <li class="mb-2">
                                                @if($kyc->tax_certificate)
                                                    <i class="fe fe-check-circle text-success me-2"></i>
                                                    <span>Tax Certificate ✓</span>
                                                @else
                                                    <i class="fe fe-minus-circle text-muted me-2"></i>
                                                    <span class="text-muted">Tax Certificate - Optional</span>
                                                @endif
                                            </li>
                                            <li class="mb-2">
                                                @if($kyc->bank_statement)
                                                    <i class="fe fe-check-circle text-success me-2"></i>
                                                    <span>Bank Statement ✓</span>
                                                @else
                                                    <i class="fe fe-minus-circle text-muted me-2"></i>
                                                    <span class="text-muted">Bank Statement - Optional</span>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Form -->
                        <form id="vendorKycStep6Form" action="{{ route('vendor.kyc.save-step', 6) }}" method="POST">
                            @csrf

                            <!-- Terms and Conditions -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">Terms & Conditions</h5>
                                    
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="terms_accepted" name="terms_accepted" value="1" required>
                                                <label class="form-check-label" for="terms_accepted">
                                                    I agree to the <a href="#" target="_blank">Terms and Conditions</a> and <a href="#" target="_blank">Privacy Policy</a>
                                                </label>
                                            </div>
                                            
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="declaration_accepted" name="declaration_accepted" value="1" required>
                                                <label class="form-check-label" for="declaration_accepted">
                                                    <strong>Declaration:</strong> I hereby declare that all the information provided above is true and complete to the best of my knowledge. I understand that any false or misleading information may result in the rejection of my application or termination of services.
                                                </label>
                                            </div>

                                            <div class="alert alert-info mb-0">
                                                <i class="fe fe-info me-2"></i>
                                                <strong>What happens next?</strong><br>
                                                Once you submit your KYC application, our team will review your information and documents. You will receive an email notification about the status of your application within 2-3 business days.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('vendor.kyc.step', 5) }}" class="btn btn-secondary">
                                            <i class="fa fa-arrow-left me-1"></i> Previous Step
                                        </a>
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fe fe-send me-2"></i> Submit KYC Application
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Card -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card border-success">
                    <div class="card-body">
                        <h6 class="text-success"><i class="fe fe-check-circle"></i> Final Review Checklist</h6>
                        <ul class="mb-0">
                            <li>Review all information for accuracy before submitting.</li>
                            <li>Ensure all required documents are uploaded and clearly visible.</li>
                            <li>Double-check that personal information matches your identity documents.</li>
                            <li>Verify that business information matches your registration documents.</li>
                            <li>Make sure contact information is current and accurate.</li>
                            <li>Once submitted, you cannot edit your application until reviewed.</li>
                        </ul>
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
    font-size: 16px;
}
.step-label {
    font-size: 12px;
    white-space: nowrap;
}

.step-arrow {
    margin: 0 10px;
    font-size: 18px;
    font-weight: bold;
}

@media (max-width: 768px) {
    .step-navigation {
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .step-arrow {
        display: none;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('vendorKycStep6Form');
    const submitBtn = form.querySelector('button[type="submit"]');
    const termsCheckbox = document.getElementById('terms_accepted');
    const declarationCheckbox = document.getElementById('declaration_accepted');
    
    // Form submission
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            return;
        }
        
        // Show confirmation dialog
        if (!confirm('Are you sure you want to submit your KYC application? You will not be able to edit it after submission.')) {
            e.preventDefault();
            return;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i> Submitting Application...';
    });

    function validateForm() {
        let isValid = true;
        
        // Check terms acceptance
        if (!termsCheckbox.checked) {
            showError(termsCheckbox, 'You must accept the terms and conditions.');
            isValid = false;
        } else {
            clearError(termsCheckbox);
        }
        
        // Check declaration acceptance
        if (!declarationCheckbox.checked) {
            showError(declarationCheckbox, 'You must accept the declaration.');
            isValid = false;
        } else {
            clearError(declarationCheckbox);
        }
        
        return isValid;
    }

    function showError(element, message) {
        element.classList.add('is-invalid');
        
        // Find or create error message element
        let errorElement = element.parentNode.querySelector('.invalid-feedback');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'invalid-feedback';
            element.parentNode.appendChild(errorElement);
        }
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }

    function clearError(element) {
        element.classList.remove('is-invalid');
        const errorElement = element.parentNode.querySelector('.invalid-feedback');
        if (errorElement) {
            errorElement.style.display = 'none';
        }
    }

    // Real-time validation
    termsCheckbox.addEventListener('change', function() {
        if (this.checked) {
            clearError(this);
        }
    });
    
    declarationCheckbox.addEventListener('change', function() {
        if (this.checked) {
            clearError(this);
        }
    });
});
</script>
@endsection