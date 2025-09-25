@extends('member.layouts.app')

@section('title', 'KYC Step 2 - Document Information')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">KYC Verification - Step 2</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.kyc.index') }}">KYC</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Document Information</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        @if($kyc->status === 'verified')
            <div class="alert alert-success">
                <div class="d-flex align-items-center">
                    <i class="fe fe-check-circle fs-3 text-success me-3"></i>
                    <div>
                        <h5 class="mb-1 text-success">KYC Already Verified</h5>
                        <p class="mb-0">Your identity verification is complete and information is locked.</p>
                        <div class="mt-2">
                            <a href="{{ route('member.kyc.index') }}" class="btn btn-success btn-sm">
                                <i class="fe fe-arrow-left"></i> Back to KYC Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @section('content')
            @stop
        @endif

        @if($kyc->status === 'pending')
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <i class="fe fe-clock fs-3 text-info me-3"></i>
                    <div>
                        <h5 class="mb-1 text-info">KYC Under Review</h5>
                        <p class="mb-0">Your KYC is being reviewed. Editing is temporarily disabled.</p>
                        <div class="mt-2">
                            <a href="{{ route('member.kyc.index') }}" class="btn btn-info btn-sm">
                                <i class="fe fe-arrow-left"></i> Back to KYC Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @section('content')
            @stop
        @endif

        <!-- Progress Bar -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0">Step 2 of 5: Document Information</h6>
                            <span class="badge bg-primary">40% Complete</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
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
                                <div class="step active">
                                    <div class="step-circle bg-primary text-white">2</div>
                                    <span class="step-label">Document Info</span>
                                </div>
                                <div class="step-arrow text-muted">→</div>
                                <div class="step">
                                    <div class="step-circle bg-light text-muted">3</div>
                                    <span class="step-label text-muted">Address</span>
                                </div>
                                <div class="step-arrow text-muted">→</div>
                                <div class="step">
                                    <div class="step-circle bg-light text-muted">4</div>
                                    <span class="step-label text-muted">Documents</span>
                                </div>
                                <div class="step-arrow text-muted">→</div>
                                <div class="step">
                                    <div class="step-circle bg-light text-muted">5</div>
                                    <span class="step-label text-muted">Review</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Identity Document Information</div>
                    </div>
                    <div class="card-body">
                        <form id="kycStep2Form" action="{{ route('member.kyc.save-step', 2) }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Document Type Selection -->
                                <div class="col-md-6 mb-4">
                                    <label for="document_type" class="form-label">Document Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="document_type" name="document_type" required>
                                        <option value="">Select Document Type</option>
                                        <option value="nid" {{ old('document_type', $kyc->document_type) == 'nid' ? 'selected' : '' }}>National ID Card (NID)</option>
                                        <option value="passport" {{ old('document_type', $kyc->document_type) == 'passport' ? 'selected' : '' }}>Passport</option>
                                        <option value="driving_license" {{ old('document_type', $kyc->document_type) == 'driving_license' ? 'selected' : '' }}>Driving License</option>
                                        <option value="birth_certificate" {{ old('document_type', $kyc->document_type) == 'birth_certificate' ? 'selected' : '' }}>Birth Certificate</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- NID Type (shown only for NID) -->
                                <div class="col-md-6 mb-4" id="nid_type_field" style="display: {{ old('document_type', $kyc->document_type) == 'nid' ? 'block' : 'none' }};">
                                    <label for="nid_type" class="form-label">NID Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="nid_type" name="nid_type">
                                        <option value="">Select NID Type</option>
                                        <option value="smart_card" {{ old('nid_type', $kyc->nid_type) == 'smart_card' ? 'selected' : '' }}>Smart NID Card</option>
                                        <option value="old_nid" {{ old('nid_type', $kyc->nid_type) == 'old_nid' ? 'selected' : '' }}>Old NID Card</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Document Number -->
                                <div class="col-md-6 mb-4">
                                    <label for="document_number" class="form-label">Document Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="document_number" name="document_number" 
                                           value="{{ old('document_number', $kyc->document_number) }}" 
                                           placeholder="Enter your document number" required>
                                    <div class="invalid-feedback"></div>
                                    <small class="text-muted" id="document_number_help">
                                        For NID: 10 or 17 digits, For Passport: 8-9 characters
                                    </small>
                                </div>

                                <!-- Voter ID (shown only for old NID) -->
                                <div class="col-md-6 mb-4" id="voter_id_field" style="display: {{ old('nid_type', $kyc->nid_type) == 'old_nid' ? 'block' : 'none' }};">
                                    <label for="voter_id" class="form-label">Voter ID (if different from NID)</label>
                                    <input type="text" class="form-control" id="voter_id" name="voter_id" 
                                           value="{{ old('voter_id', $kyc->voter_id) }}" 
                                           placeholder="Enter voter ID if different">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Passport/Driving License specific fields -->
                                <div class="passport-driving-fields" style="display: {{ in_array(old('document_type', $kyc->document_type), ['passport', 'driving_license']) ? 'block' : 'none' }};">
                                    <div class="col-md-6 mb-4">
                                        <label for="document_issue_date" class="form-label">Issue Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="document_issue_date" name="document_issue_date" 
                                               value="{{ old('document_issue_date', $kyc->document_issue_date ? $kyc->document_issue_date->format('Y-m-d') : '') }}">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label for="document_expiry_date" class="form-label">Expiry Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="document_expiry_date" name="document_expiry_date" 
                                               value="{{ old('document_expiry_date', $kyc->document_expiry_date ? $kyc->document_expiry_date->format('Y-m-d') : '') }}"
                                               min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <label for="document_issuer" class="form-label">Issuing Authority <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="document_issuer" name="document_issuer" 
                                               value="{{ old('document_issuer', $kyc->document_issuer) }}" 
                                               placeholder="e.g., Department of Immigration and Passports, BRTA">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Document Requirements Info -->
                            <div class="alert alert-info">
                                <h6><i class="fe fe-info"></i> Document Requirements</h6>
                                <div id="document_requirements">
                                    <p class="mb-0">Select a document type to see specific requirements.</p>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('member.kyc.step', 1) }}" class="btn btn-outline-secondary">
                                    <i class="fe fe-arrow-left"></i> Previous Step
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    Next Step <i class="fe fe-arrow-right"></i>
                                </button>
                            </div>
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
    const form = document.getElementById('kycStep2Form');
    const submitBtn = document.getElementById('submitBtn');
    const documentType = document.getElementById('document_type');
    const nidType = document.getElementById('nid_type');
    const nidTypeField = document.getElementById('nid_type_field');
    const voterIdField = document.getElementById('voter_id_field');
    const passportDrivingFields = document.querySelector('.passport-driving-fields');
    const documentRequirements = document.getElementById('document_requirements');
    
    // Document type change handler
    documentType.addEventListener('change', function() {
        const selectedType = this.value;
        
        // Hide all conditional fields first
        nidTypeField.style.display = 'none';
        voterIdField.style.display = 'none';
        passportDrivingFields.style.display = 'none';
        
        // Clear requirements field
        nidType.removeAttribute('required');
        document.getElementById('document_issue_date').removeAttribute('required');
        document.getElementById('document_expiry_date').removeAttribute('required');
        document.getElementById('document_issuer').removeAttribute('required');
        
        // Show relevant fields and set requirements
        if (selectedType === 'nid') {
            nidTypeField.style.display = 'block';
            nidType.setAttribute('required', '');
            
            documentRequirements.innerHTML = `
                <ul class="mb-0">
                    <li><strong>Smart NID:</strong> 17-digit number, both front and back photos required</li>
                    <li><strong>Old NID:</strong> 10-digit number, front photo required</li>
                    <li>Make sure the card is clear and all text is readable</li>
                    <li>No laminated or damaged cards</li>
                </ul>
            `;
        } else if (selectedType === 'passport') {
            passportDrivingFields.style.display = 'block';
            document.getElementById('document_issue_date').setAttribute('required', '');
            document.getElementById('document_expiry_date').setAttribute('required', '');
            document.getElementById('document_issuer').setAttribute('required', '');
            
            documentRequirements.innerHTML = `
                <ul class="mb-0">
                    <li><strong>Valid Bangladeshi Passport:</strong> Must not be expired</li>
                    <li>Clear photo of the information page</li>
                    <li>All text must be clearly visible</li>
                    <li>Machine readable zone (MRZ) must be intact</li>
                </ul>
            `;
        } else if (selectedType === 'driving_license') {
            passportDrivingFields.style.display = 'block';
            document.getElementById('document_issue_date').setAttribute('required', '');
            document.getElementById('document_expiry_date').setAttribute('required', '');
            document.getElementById('document_issuer').setAttribute('required', '');
            
            documentRequirements.innerHTML = `
                <ul class="mb-0">
                    <li><strong>Valid Driving License:</strong> Must not be expired</li>
                    <li>Both front and back photos required</li>
                    <li>All text must be clearly visible</li>
                    <li>License must be in good condition</li>
                </ul>
            `;
        } else if (selectedType === 'birth_certificate') {
            documentRequirements.innerHTML = `
                <ul class="mb-0">
                    <li><strong>Government issued Birth Certificate</strong></li>
                    <li>Must be in Bengali or English</li>
                    <li>Clear scan or photo required</li>
                    <li>All text must be readable</li>
                </ul>
            `;
        }
    });
    
    // NID type change handler
    nidType.addEventListener('change', function() {
        if (this.value === 'old_nid') {
            voterIdField.style.display = 'block';
        } else {
            voterIdField.style.display = 'none';
        }
    });
    
    // Initialize on page load
    if (documentType.value) {
        documentType.dispatchEvent(new Event('change'));
    }
    if (nidType.value) {
        nidType.dispatchEvent(new Event('change'));
    }
    
    // Form submission
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fe fe-loader"></i> Saving...';
    });

    // Document number validation
    const documentNumber = document.getElementById('document_number');
    documentNumber.addEventListener('input', function() {
        const type = documentType.value;
        const value = this.value;
        let isValid = false;
        let message = '';
        
        if (type === 'nid') {
            isValid = /^\d{10}$|^\d{17}$/.test(value);
            message = 'NID must be 10 or 17 digits';
        } else if (type === 'passport') {
            isValid = /^[A-Z]{2}\d{7}$/.test(value);
            message = 'Passport format: 2 letters followed by 7 digits (e.g., AB1234567)';
        } else if (type === 'driving_license') {
            isValid = value.length >= 8;
            message = 'Please enter valid driving license number';
        } else if (type === 'birth_certificate') {
            isValid = value.length >= 10;
            message = 'Please enter valid birth certificate number';
        }
        
        if (value && !isValid) {
            this.setCustomValidity(message);
            this.classList.add('is-invalid');
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
        }
    });
});
</script>
@endsection