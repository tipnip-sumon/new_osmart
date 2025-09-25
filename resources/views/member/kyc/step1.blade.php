@extends('member.layouts.app')

@section('title', 'KYC Step 1 - Personal Information')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">KYC Verification - Step 1</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.kyc.index') }}">KYC</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Personal Information</li>
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
                            <h6 class="mb-0">Step 1 of 5: Personal Information</h6>
                            <span class="badge bg-primary">20% Complete</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
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
                                    <span class="step-label">Personal Info</span>
                                </div>
                                <div class="step-arrow">→</div>
                                <div class="step">
                                    <div class="step-circle bg-light text-muted">2</div>
                                    <span class="step-label text-muted">Document Info</span>
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
                        <div class="card-title">Personal Information</div>
                    </div>
                    <div class="card-body">
                        <form id="kycStep1Form" action="{{ route('member.kyc.save-step', 1) }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6 mb-3">
                                    <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" 
                                           value="{{ old('full_name', $kyc->full_name) }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="father_name" class="form-label">Father's Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="father_name" name="father_name" 
                                           value="{{ old('father_name', $kyc->father_name) }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="mother_name" class="form-label">Mother's Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="mother_name" name="mother_name" 
                                           value="{{ old('mother_name', $kyc->mother_name) }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                           value="{{ old('date_of_birth', $kyc->date_of_birth ? $kyc->date_of_birth->format('Y-m-d') : '') }}" 
                                           max="{{ date('Y-m-d', strtotime('-18 years')) }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Personal Details -->
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $kyc->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $kyc->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $kyc->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="marital_status" class="form-label">Marital Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="marital_status" name="marital_status" required>
                                        <option value="">Select Marital Status</option>
                                        <option value="single" {{ old('marital_status', $kyc->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                                        <option value="married" {{ old('marital_status', $kyc->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                                        <option value="divorced" {{ old('marital_status', $kyc->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="widowed" {{ old('marital_status', $kyc->marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nationality" name="nationality" 
                                           value="{{ old('nationality', $kyc->nationality ?: 'Bangladeshi') }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="religion" class="form-label">Religion</label>
                                    <select class="form-select" id="religion" name="religion">
                                        <option value="">Select Religion</option>
                                        <option value="Islam" {{ old('religion', $kyc->religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                        <option value="Hinduism" {{ old('religion', $kyc->religion) == 'Hinduism' ? 'selected' : '' }}>Hinduism</option>
                                        <option value="Buddhism" {{ old('religion', $kyc->religion) == 'Buddhism' ? 'selected' : '' }}>Buddhism</option>
                                        <option value="Christianity" {{ old('religion', $kyc->religion) == 'Christianity' ? 'selected' : '' }}>Christianity</option>
                                        <option value="Others" {{ old('religion', $kyc->religion) == 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Professional Information -->
                                <div class="col-md-8 mb-3">
                                    <label for="profession" class="form-label">Profession/Occupation <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="profession" name="profession" 
                                           value="{{ old('profession', $kyc->profession) }}" 
                                           placeholder="e.g., Teacher, Engineer, Business Owner" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="monthly_income" class="form-label">Monthly Income (BDT)</label>
                                    <input type="number" class="form-control" id="monthly_income" name="monthly_income" 
                                           value="{{ old('monthly_income', $kyc->monthly_income) }}" 
                                           min="0" step="1000" placeholder="50000">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('member.kyc.index') }}" class="btn btn-outline-secondary">
                                    <i class="fe fe-arrow-left"></i> Back to Dashboard
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

        <!-- Help Card -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card border-info">
                    <div class="card-body">
                        <h6 class="text-info"><i class="fe fe-info"></i> Important Information</h6>
                        <ul class="mb-0">
                            <li>Please provide accurate information as it will be verified against your official documents.</li>
                            <li>Make sure your name matches exactly with your identity document (NID/Passport).</li>
                            <li>Date of birth should match your official documents.</li>
                            <li>All required fields marked with * must be filled.</li>
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
    const form = document.getElementById('kycStep1Form');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fe fe-loader"></i> Saving...';
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
            field.nextElementSibling.textContent = 'This field is required';
        }
        
        return isValid;
    }
});
</script>
@endsection