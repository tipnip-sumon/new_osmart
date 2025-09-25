@extends('admin.layouts.app')

@section('title', 'Vendor KYC Step 2 - Owner Information')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Vendor KYC Verification - Step 2</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.kyc.index') }}">KYC</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Owner Information</li>
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
                            <h6 class="mb-0">Step 2 of 6: Owner Information</h6>
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
                                <div class="step active">
                                    <div class="step-circle bg-primary text-white">2</div>
                                    <span class="step-label">Owner Info</span>
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
                        <div class="card-title">Owner Information</div>
                    </div>
                    <div class="card-body">
                        <form id="vendorKycStep2Form" action="{{ route('vendor.kyc.save-step', 2) }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6 mb-3">
                                    <label for="owner_full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="owner_full_name" name="owner_full_name" 
                                           value="{{ old('owner_full_name', $kyc->owner_full_name) }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="owner_father_name" class="form-label">Father's Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="owner_father_name" name="owner_father_name" 
                                           value="{{ old('owner_father_name', $kyc->owner_father_name) }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="owner_mother_name" class="form-label">Mother's Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="owner_mother_name" name="owner_mother_name" 
                                           value="{{ old('owner_mother_name', $kyc->owner_mother_name) }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="owner_date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="owner_date_of_birth" name="owner_date_of_birth" 
                                           value="{{ old('owner_date_of_birth', $kyc->owner_date_of_birth ? $kyc->owner_date_of_birth->format('Y-m-d') : '') }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Personal Details -->
                                <div class="col-md-6 mb-3">
                                    <label for="owner_gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select class="form-select" id="owner_gender" name="owner_gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('owner_gender', $kyc->owner_gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('owner_gender', $kyc->owner_gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('owner_gender', $kyc->owner_gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="owner_marital_status" class="form-label">Marital Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="owner_marital_status" name="owner_marital_status" required>
                                        <option value="">Select Marital Status</option>
                                        <option value="single" {{ old('owner_marital_status', $kyc->owner_marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                                        <option value="married" {{ old('owner_marital_status', $kyc->owner_marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                                        <option value="divorced" {{ old('owner_marital_status', $kyc->owner_marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="widowed" {{ old('owner_marital_status', $kyc->owner_marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Nationality and Other Info -->
                                <div class="col-md-6 mb-3">
                                    <label for="owner_nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="owner_nationality" name="owner_nationality" 
                                           value="{{ old('owner_nationality', $kyc->owner_nationality ?? 'Bangladeshi') }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="owner_religion" class="form-label">Religion</label>
                                    <select class="form-select" id="owner_religion" name="owner_religion">
                                        <option value="">Select Religion</option>
                                        <option value="Islam" {{ old('owner_religion', $kyc->owner_religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                        <option value="Hinduism" {{ old('owner_religion', $kyc->owner_religion) == 'Hinduism' ? 'selected' : '' }}>Hinduism</option>
                                        <option value="Christianity" {{ old('owner_religion', $kyc->owner_religion) == 'Christianity' ? 'selected' : '' }}>Christianity</option>
                                        <option value="Buddhism" {{ old('owner_religion', $kyc->owner_religion) == 'Buddhism' ? 'selected' : '' }}>Buddhism</option>
                                        <option value="Other" {{ old('owner_religion', $kyc->owner_religion) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Professional Information -->
                                <div class="col-md-12 mb-3">
                                    <label for="owner_profession" class="form-label">Profession/Occupation <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="owner_profession" name="owner_profession" 
                                           value="{{ old('owner_profession', $kyc->owner_profession) }}" required
                                           placeholder="e.g., Business Owner, Entrepreneur, Manager">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('vendor.kyc.step', 1) }}" class="btn btn-light">
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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('vendorKycStep2Form');
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