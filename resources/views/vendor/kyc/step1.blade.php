@extends('admin.layouts.app')

@section('title', 'Vendor KYC Step 1 - Business Information')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Vendor KYC Verification - Step 1</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.kyc.index') }}">KYC</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Business Information</li>
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
                            <h6 class="mb-0">Step 1 of 6: Business Information</h6>
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
                                <div class="step active">
                                    <div class="step-circle bg-primary text-white">1</div>
                                    <span class="step-label">Business Info</span>
                                </div>
                                <div class="step-arrow">→</div>
                                <div class="step">
                                    <div class="step-circle bg-light text-muted">2</div>
                                    <span class="step-label text-muted">Owner Info</span>
                                </div>
                                <div class="step-arrow text-muted">→</div>
                                <div class="step">
                                    <div class="step-circle bg-light text-muted">3</div>
                                    <span class="step-label text-muted">Document Info</span>
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
                        <div class="card-title">Business Information</div>
                    </div>
                    <div class="card-body">
                        <form id="vendorKycStep1Form" action="{{ route('vendor.kyc.save-step', 1) }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Business Basic Information -->
                                <div class="col-md-6 mb-3">
                                    <label for="business_name" class="form-label">Business Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="business_name" name="business_name" 
                                           value="{{ old('business_name', $kyc->business_name) }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="business_type" class="form-label">Business Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="business_type" name="business_type" required>
                                        <option value="">Select Business Type</option>
                                        <option value="sole_proprietorship" {{ old('business_type', $kyc->business_type) == 'sole_proprietorship' ? 'selected' : '' }}>Sole Proprietorship</option>
                                        <option value="partnership" {{ old('business_type', $kyc->business_type) == 'partnership' ? 'selected' : '' }}>Partnership</option>
                                        <option value="private_limited" {{ old('business_type', $kyc->business_type) == 'private_limited' ? 'selected' : '' }}>Private Limited Company</option>
                                        <option value="public_limited" {{ old('business_type', $kyc->business_type) == 'public_limited' ? 'selected' : '' }}>Public Limited Company</option>
                                        <option value="ngo" {{ old('business_type', $kyc->business_type) == 'ngo' ? 'selected' : '' }}>NGO</option>
                                        <option value="cooperative" {{ old('business_type', $kyc->business_type) == 'cooperative' ? 'selected' : '' }}>Cooperative Society</option>
                                        <option value="other" {{ old('business_type', $kyc->business_type) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Registration Information -->
                                <div class="col-md-6 mb-3">
                                    <label for="business_registration_number" class="form-label">Business Registration Number</label>
                                    <input type="text" class="form-control" id="business_registration_number" name="business_registration_number" 
                                           value="{{ old('business_registration_number', $kyc->business_registration_number) }}">
                                    <small class="form-text text-muted">Trade License or Company Registration Number</small>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tax_identification_number" class="form-label">Tax Identification Number (TIN)</label>
                                    <input type="text" class="form-control" id="tax_identification_number" name="tax_identification_number" 
                                           value="{{ old('tax_identification_number', $kyc->tax_identification_number) }}">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="business_license_number" class="form-label">Business License Number</label>
                                    <input type="text" class="form-control" id="business_license_number" name="business_license_number" 
                                           value="{{ old('business_license_number', $kyc->business_license_number) }}">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="establishment_date" class="form-label">Business Establishment Date</label>
                                    <input type="date" class="form-control" id="establishment_date" name="establishment_date" 
                                           value="{{ old('establishment_date', $kyc->establishment_date ? $kyc->establishment_date->format('Y-m-d') : '') }}">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Business Description -->
                                <div class="col-12 mb-3">
                                    <label for="business_description" class="form-label">Business Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="business_description" name="business_description" 
                                              rows="4" required>{{ old('business_description', $kyc->business_description) }}</textarea>
                                    <small class="form-text text-muted">Describe your business activities, products, and services</small>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Website URL -->
                                <div class="col-md-6 mb-3">
                                    <label for="website_url" class="form-label">Website URL</label>
                                    <input type="url" class="form-control" id="website_url" name="website_url" 
                                           value="{{ old('website_url', $kyc->website_url) }}" 
                                           placeholder="https://www.example.com">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('vendor.kyc.index') }}" class="btn btn-light">
                                    <i class="fe fe-arrow-left"></i> Back to KYC Dashboard
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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('vendorKycStep1Form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
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