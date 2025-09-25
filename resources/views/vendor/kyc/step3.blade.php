@extends('admin.layouts.app')

@section('title', 'Vendor KYC Step 3 - Document Information')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Vendor KYC Verification - Step 3</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.kyc.index') }}">KYC</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Document Information</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0">Step 3 of 6: Document Information</h6>
                            <span class="badge bg-primary">{{ number_format($kyc->completion_percentage, 2) }}% Complete</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $kyc->completion_percentage }}%" aria-valuenow="{{ $kyc->completion_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
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
                                <div class="step active">
                                    <div class="step-circle bg-primary text-white">3</div>
                                    <span class="step-label">Document Info</span>
                                </div>
                                <div class="step-arrow text-muted">→</div>
                                <div class="step">
                                    <div class="step-circle bg-light text-muted">4</div>
                                    <span class="step-label text-muted">Address</span>
                                </div>
                                <div class="step-arrow text-muted">→</div>
                                <div class="step">
                                    <div class="step-circle bg-light text-muted">5</div>
                                    <span class="step-label text-muted">Documents</span>
                                </div>
                                <div class="step-arrow text-muted">→</div>
                                <div class="step">
                                    <div class="step-circle bg-light text-muted">6</div>
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
                        <div class="card-title">Document Information</div>
                    </div>
                    <div class="card-body">
                        <form id="vendorKycStep3Form" action="{{ route('vendor.kyc.save-step', 3) }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Document Type -->
                                <div class="col-md-6 mb-3">
                                    <label for="document_type" class="form-label">Identity Document Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="document_type" name="document_type" required onchange="toggleDocumentFields()">
                                        <option value="">Select Document Type</option>
                                        <option value="nid" {{ old('document_type', $kyc->document_type) == 'nid' ? 'selected' : '' }}>National ID (NID)</option>
                                        <option value="passport" {{ old('document_type', $kyc->document_type) == 'passport' ? 'selected' : '' }}>Passport</option>
                                        <option value="driving_license" {{ old('document_type', $kyc->document_type) == 'driving_license' ? 'selected' : '' }}>Driving License</option>
                                        <option value="birth_certificate" {{ old('document_type', $kyc->document_type) == 'birth_certificate' ? 'selected' : '' }}>Birth Certificate</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Document Number -->
                                <div class="col-md-6 mb-3">
                                    <label for="document_number" class="form-label">Document Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="document_number" name="document_number" 
                                           value="{{ old('document_number', $kyc->document_number) }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- NID Type (for NID only) -->
                                <div class="col-md-6 mb-3" id="nid_type_group" style="display: {{ old('document_type', $kyc->document_type) == 'nid' ? 'block' : 'none' }}">
                                    <label for="nid_type" class="form-label">NID Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="nid_type" name="nid_type" onchange="toggleVoterIdField()">
                                        <option value="">Select NID Type</option>
                                        <option value="smart" {{ old('nid_type', $kyc->nid_type) == 'smart' ? 'selected' : '' }}>Smart Card NID</option>
                                        <option value="old" {{ old('nid_type', $kyc->nid_type) == 'old' ? 'selected' : '' }}>Old NID Card</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Voter ID (for old NID only) -->
                                <div class="col-md-6 mb-3" id="voter_id_group" style="display: {{ old('nid_type', $kyc->nid_type) == 'old' ? 'block' : 'none' }}">
                                    <label for="voter_id" class="form-label">Voter ID Number</label>
                                    <input type="text" class="form-control" id="voter_id" name="voter_id" 
                                           value="{{ old('voter_id', $kyc->voter_id) }}">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Document Issue Date (for Passport) -->
                                <div class="col-md-6 mb-3" id="issue_date_group" style="display: {{ old('document_type', $kyc->document_type) == 'passport' ? 'block' : 'none' }}">
                                    <label for="document_issue_date" class="form-label">Issue Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="document_issue_date" name="document_issue_date" 
                                           value="{{ old('document_issue_date', $kyc->document_issue_date ? $kyc->document_issue_date->format('Y-m-d') : '') }}">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Document Expiry Date (for Passport) -->
                                <div class="col-md-6 mb-3" id="expiry_date_group" style="display: {{ old('document_type', $kyc->document_type) == 'passport' ? 'block' : 'none' }}">
                                    <label for="document_expiry_date" class="form-label">Expiry Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="document_expiry_date" name="document_expiry_date" 
                                           value="{{ old('document_expiry_date', $kyc->document_expiry_date ? $kyc->document_expiry_date->format('Y-m-d') : '') }}">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Document Issuer (for Passport) -->
                                <div class="col-md-12 mb-3" id="issuer_group" style="display: {{ old('document_type', $kyc->document_type) == 'passport' ? 'block' : 'none' }}">
                                    <label for="document_issuer" class="form-label">Issuing Authority <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="document_issuer" name="document_issuer" 
                                           value="{{ old('document_issuer', $kyc->document_issuer) }}"
                                           placeholder="e.g., Department of Immigration and Passports">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('vendor.kyc.step', 2) }}" class="btn btn-light">
                                    <i class="fe fe-arrow-left"></i> Previous Step
                                </a>
                                <button type="submit" class="btn btn-primary">
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
    gap: 20px;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
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
function toggleDocumentFields() {
    const documentType = document.getElementById('document_type').value;
    const nidTypeGroup = document.getElementById('nid_type_group');
    const voterIdGroup = document.getElementById('voter_id_group');
    const issueDateGroup = document.getElementById('issue_date_group');
    const expiryDateGroup = document.getElementById('expiry_date_group');
    const issuerGroup = document.getElementById('issuer_group');

    // Hide all conditional fields first
    nidTypeGroup.style.display = 'none';
    voterIdGroup.style.display = 'none';
    issueDateGroup.style.display = 'none';
    expiryDateGroup.style.display = 'none';
    issuerGroup.style.display = 'none';

    // Show relevant fields based on document type
    if (documentType === 'nid') {
        nidTypeGroup.style.display = 'block';
        toggleVoterIdField();
    } else if (documentType === 'passport') {
        issueDateGroup.style.display = 'block';
        expiryDateGroup.style.display = 'block';
        issuerGroup.style.display = 'block';
    }
}

function toggleVoterIdField() {
    const nidType = document.getElementById('nid_type').value;
    const voterIdGroup = document.getElementById('voter_id_group');
    
    if (nidType === 'old') {
        voterIdGroup.style.display = 'block';
    } else {
        voterIdGroup.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize document fields
    toggleDocumentFields();
    
    const form = document.getElementById('vendorKycStep3Form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Form submission
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving...';
    });

    // Real-time validation
    const requiredFields = form.querySelectorAll('input[required], select[required]');
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            validateField(this);
        });
    });

    function validateField(field) {
        const isValid = field.value.trim() !== '';
        
        if (isValid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
            const feedback = field.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.textContent = 'This field is required';
            }
        }
        
        return isValid;
    }
});
</script>
@endsection