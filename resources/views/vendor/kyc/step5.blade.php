@extends('admin.layouts.app')

@section('title', 'Vendor KYC Step 5 - Document Upload')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Vendor KYC Verification - Step 5</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.kyc.index') }}">KYC</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Document Upload</li>
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
                            <h6 class="mb-0">Step 5 of 6: Document Upload</h6>
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
                                <div class="step active">
                                    <div class="step-circle bg-primary text-white">5</div>
                                    <span class="step-label">Documents</span>
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
                        <div class="card-title">Document Upload</div>
                    </div>
                    <div class="card-body">
                        <form id="vendorKycStep5Form" action="{{ route('vendor.kyc.save-step', 5) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Identity Documents -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">Identity Documents</h5>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="document_front_image" class="form-label">
                                        {{ $kyc->document_type === 'nid' ? 'NID Front Image' : ($kyc->document_type === 'passport' ? 'Passport Image' : 'Driving License Front Image') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control" id="document_front_image" name="document_front_image" 
                                           accept="image/jpeg,image/png,image/jpg" {{ $kyc->document_front_image ? '' : 'required' }}>
                                    <div class="form-text">Max file size: 2MB. Supported formats: JPG, PNG, JPEG</div>
                                    @if($kyc->document_front_image)
                                        <div class="mt-2" style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                                            <small class="text-success d-block">✓ Previously uploaded: {{ basename($kyc->document_front_image) }}</small>
                                            <a href="{{ Storage::url($kyc->document_front_image) }}" target="_blank" class="btn btn-sm btn-outline-info mt-1">
                                                <i class="fa fa-eye"></i> View Current File
                                            </a>
                                        </div>
                                    @else
                                        <div class="mt-1 small text-danger">* Required file - please upload</div>
                                    @endif
                                    <div class="invalid-feedback"></div>
                                </div>

                                @if($kyc->document_type === 'nid' && $kyc->nid_type === 'smart' || $kyc->document_type === 'driving_license')
                                <div class="col-md-6 mb-3">
                                    <label for="document_back_image" class="form-label">
                                        {{ $kyc->document_type === 'nid' ? 'NID Back Image' : 'Driving License Back Image' }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control" id="document_back_image" name="document_back_image" 
                                           accept="image/jpeg,image/png,image/jpg" {{ $kyc->document_back_image ? '' : 'required' }}>
                                    <div class="form-text">Max file size: 2MB. Supported formats: JPG, PNG, JPEG</div>
                                    @if($kyc->document_back_image)
                                        <div class="mt-2" style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                                            <small class="text-success d-block">✓ Previously uploaded: {{ basename($kyc->document_back_image) }}</small>
                                            <a href="{{ Storage::url($kyc->document_back_image) }}" target="_blank" class="btn btn-sm btn-outline-info mt-1">
                                                <i class="fa fa-eye"></i> View Current File
                                            </a>
                                        </div>
                                    @else
                                        <div class="mt-1 small text-danger">* Required file - please upload</div>
                                    @endif
                                    <div class="invalid-feedback"></div>
                                </div>
                                @endif
                            </div>

                            <!-- Personal Documents -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">Personal Documents</h5>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="owner_photo" class="form-label">Owner Photo <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="owner_photo" name="owner_photo" 
                                           accept="image/jpeg,image/png,image/jpg" {{ $kyc->owner_photo ? '' : 'required' }}>
                                    <div class="form-text">Max file size: 2MB. Passport size photo recommended.</div>
                                    @if($kyc->owner_photo)
                                        <div class="mt-2" style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                                            <small class="text-success d-block">✓ Previously uploaded: {{ basename($kyc->owner_photo) }}</small>
                                            <a href="{{ Storage::url($kyc->owner_photo) }}" target="_blank" class="btn btn-sm btn-outline-info mt-1">
                                                <i class="fa fa-eye"></i> View Current File
                                            </a>
                                        </div>
                                    @else
                                        <div class="mt-1 small text-danger">* Required file - please upload</div>
                                    @endif
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="owner_signature" class="form-label">Owner Signature</label>
                                    <input type="file" class="form-control" id="owner_signature" name="owner_signature" 
                                           accept="image/jpeg,image/png,image/jpg">
                                    <div class="form-text">Max file size: 1MB. Clear signature on white background.</div>
                                    @if($kyc->owner_signature)
                                        <div class="mt-2" style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                                            <small class="text-success d-block">✓ Previously uploaded: {{ basename($kyc->owner_signature) }}</small>
                                            <a href="{{ Storage::url($kyc->owner_signature) }}" target="_blank" class="btn btn-sm btn-outline-info mt-1">
                                                <i class="fa fa-eye"></i> View Current File
                                            </a>
                                        </div>
                                    @else
                                        <div class="mt-1 small text-muted">No file uploaded yet</div>
                                    @endif
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Business Documents -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">Business Documents</h5>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="business_license" class="form-label">Business License/Trade License <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="business_license" name="business_license" 
                                           accept="image/jpeg,image/png,image/jpg,application/pdf" {{ $kyc->business_license ? '' : 'required' }}>
                                    <div class="form-text">Max file size: 3MB. Supported formats: JPG, PNG, PDF</div>
                                    @if($kyc->business_license)
                                        <div class="mt-2" style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                                            <small class="text-success d-block">✓ Previously uploaded: {{ basename($kyc->business_license) }}</small>
                                            <a href="{{ Storage::url($kyc->business_license) }}" target="_blank" class="btn btn-sm btn-outline-info mt-1">
                                                <i class="fa fa-eye"></i> View Current File
                                            </a>
                                        </div>
                                    @else
                                        <div class="mt-1 small text-danger">* Required file - please upload</div>
                                    @endif
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tax_certificate" class="form-label">Tax Certificate</label>
                                    <input type="file" class="form-control" id="tax_certificate" name="tax_certificate" 
                                           accept="image/jpeg,image/png,image/jpg,application/pdf">
                                    <div class="form-text">Max file size: 3MB. TIN certificate or tax return.</div>
                                    @if($kyc->tax_certificate)
                                        <div class="mt-2" style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                                            <small class="text-success d-block">✓ Previously uploaded: {{ basename($kyc->tax_certificate) }}</small>
                                            <a href="{{ Storage::url($kyc->tax_certificate) }}" target="_blank" class="btn btn-sm btn-outline-info mt-1">
                                                <i class="fa fa-eye"></i> View Current File
                                            </a>
                                        </div>
                                    @else
                                        <div class="mt-1 small text-muted">No file uploaded yet</div>
                                    @endif
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Supporting Documents -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">Supporting Documents</h5>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="utility_bill" class="form-label">Utility Bill</label>
                                    <input type="file" class="form-control" id="utility_bill" name="utility_bill" 
                                           accept="image/jpeg,image/png,image/jpg,application/pdf">
                                    <div class="form-text">Max file size: 3MB. Recent electricity/gas/water bill.</div>
                                    
                                    
                                    @if($kyc->utility_bill)
                                        <div class="mt-2" style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                                            <small class="text-success d-block">✓ Previously uploaded: {{ basename($kyc->utility_bill) }}</small>
                                            <a href="{{ Storage::url($kyc->utility_bill) }}" target="_blank" class="btn btn-sm btn-outline-info mt-1">
                                                <i class="fa fa-eye"></i> View Current File
                                            </a>
                                        </div>
                                    @else
                                        <div class="mt-1 small text-muted">No file uploaded yet</div>
                                    @endif
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="bank_statement" class="form-label">Bank Statement</label>
                                    <input type="file" class="form-control" id="bank_statement" name="bank_statement" 
                                           accept="image/jpeg,image/png,image/jpg,application/pdf">
                                    <div class="form-text">Max file size: 3MB. Recent bank statement (last 3 months).</div>
                                    @if($kyc->bank_statement)
                                        <div class="mt-2" style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                                            <small class="text-success d-block">✓ Previously uploaded: {{ basename($kyc->bank_statement) }}</small>
                                            <a href="{{ Storage::url($kyc->bank_statement) }}" target="_blank" class="btn btn-sm btn-outline-info mt-1">
                                                <i class="fa fa-eye"></i> View Current File
                                            </a>
                                        </div>
                                    @else
                                        <div class="mt-1 small text-muted">No file uploaded yet</div>
                                    @endif
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('vendor.kyc.step', 4) }}" class="btn btn-secondary">
                                            <i class="fa fa-arrow-left me-1"></i> Previous Step
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            Save & Continue <i class="fa fa-arrow-right ms-1"></i>
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
                <div class="card custom-card border-info">
                    <div class="card-body">
                        <h6 class="text-info"><i class="fe fe-info"></i> Document Upload Guidelines</h6>
                        <ul class="mb-0">
                            <li>Upload clear, high-quality images or scanned documents.</li>
                            <li>Ensure all text in documents is clearly readable.</li>
                            <li>Documents should be recent and not expired.</li>
                            <li>Business license should match your business name and address.</li>
                            <li>Identity documents must match the owner information provided.</li>
                            <li>File sizes should not exceed the specified limits.</li>
                            <li>Only upload authentic and original documents.</li>
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

.file-upload-preview {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
}

.file-upload-preview:hover {
    border-color: #6c5ce7;
    background-color: #f8f9ff;
}

.file-upload-preview.dragover {
    border-color: #6c5ce7;
    background-color: #e8e6ff;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('vendorKycStep5Form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Form submission
    form.addEventListener('submit', function(e) {
        if (validateForm()) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Uploading...';
        } else {
            e.preventDefault();
        }
    });

    // File validation
    const fileInputs = form.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            validateFile(this);
        });
    });

    function validateFile(input) {
        const file = input.files[0];
        const maxSizes = {
            'owner_signature': 1024 * 1024, // 1MB
            'document_front_image': 2048 * 1024, // 2MB
            'document_back_image': 2048 * 1024, // 2MB
            'owner_photo': 2048 * 1024, // 2MB
            'utility_bill': 3072 * 1024, // 3MB
            'business_license': 3072 * 1024, // 3MB
            'tax_certificate': 3072 * 1024, // 3MB
            'bank_statement': 3072 * 1024 // 3MB
        };

        const allowedTypes = {
            'owner_signature': ['image/jpeg', 'image/png', 'image/jpg'],
            'document_front_image': ['image/jpeg', 'image/png', 'image/jpg'],
            'document_back_image': ['image/jpeg', 'image/png', 'image/jpg'],
            'owner_photo': ['image/jpeg', 'image/png', 'image/jpg'],
            'utility_bill': ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'],
            'business_license': ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'],
            'tax_certificate': ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'],
            'bank_statement': ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf']
        };

        if (!file) return;

        const fieldName = input.name;
        const maxSize = maxSizes[fieldName];
        const allowed = allowedTypes[fieldName];

        // Check file type
        if (!allowed.includes(file.type)) {
            showFileError(input, 'Invalid file type. Please upload a valid image or PDF file.');
            return false;
        }

        // Check file size
        if (file.size > maxSize) {
            const maxSizeMB = Math.round(maxSize / (1024 * 1024));
            showFileError(input, `File size too large. Maximum ${maxSizeMB}MB allowed.`);
            return false;
        }

        // File is valid
        showFileSuccess(input, `${file.name} (${formatFileSize(file.size)})`);
        return true;
    }

    function showFileError(input, message) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        const feedback = input.parentNode.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.textContent = message;
        }
    }

    function showFileSuccess(input, message) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        const feedback = input.parentNode.querySelector('.form-text');
        if (feedback) {
            feedback.innerHTML = `<span class="text-success">✓ ${message}</span>`;
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function validateForm() {
        let isValid = true;
        const requiredInputs = form.querySelectorAll('input[required]');
        
        requiredInputs.forEach(input => {
            if (input.type === 'file') {
                if (!input.files.length) {
                    showFileError(input, 'This file is required.');
                    isValid = false;
                } else if (!validateFile(input)) {
                    isValid = false;
                }
            }
        });

        return isValid;
    }

    // Drag and drop functionality (optional enhancement)
    fileInputs.forEach(input => {
        const container = input.closest('.mb-3');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            container.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            container.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            container.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            container.classList.add('dragover');
        }

        function unhighlight(e) {
            container.classList.remove('dragover');
        }

        container.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length) {
                input.files = files;
                validateFile(input);
            }
        }
    });
});
</script>
@endsection