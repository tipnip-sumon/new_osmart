@extends('member.layouts.app')

@section('title', 'KYC Step 4 - Document Upload')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">KYC Verification - Step 4</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.kyc.index') }}">KYC</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Document Upload</li>
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
                            <h6 class="mb-0">Step 4 of 5: Document Upload</h6>
                            <span class="badge bg-primary">80% Complete</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
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
                                <div class="step active">
                                    <div class="step-circle bg-primary text-white">4</div>
                                    <span class="step-label">Documents</span>
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
                        <div class="card-title">Document Upload</div>
                    </div>
                    <div class="card-body">
                        <form id="kycStep4Form" action="{{ route('member.kyc.save-step', 4) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Document Requirements Alert -->
                            <div class="alert alert-info mb-4">
                                <h6><i class="fe fe-info"></i> Upload Requirements</h6>
                                <ul class="mb-0">
                                    <li><strong>Document Front:</strong> Clear photo/scan of your {{ ucwords(str_replace('_', ' ', $kyc->document_type ?? 'identity document')) }} front side</li>
                                    @if($kyc->document_type === 'nid' && $kyc->nid_type === 'smart_card')
                                        <li><strong>Document Back:</strong> Clear photo/scan of your NID back side (required for Smart NID)</li>
                                    @elseif($kyc->document_type === 'driving_license')
                                        <li><strong>Document Back:</strong> Clear photo/scan of your driving license back side</li>
                                    @endif
                                    <li><strong>User Photo:</strong> Clear selfie or passport-style photo</li>
                                    <li><strong>Signature:</strong> Your signature on white paper (optional)</li>
                                    <li><strong>Utility Bill:</strong> Recent utility bill for address verification (optional)</li>
                                </ul>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <strong>File Requirements:</strong> JPEG/PNG/JPG format, Maximum 2MB for images, 3MB for PDFs
                                    </small>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Document Front Image -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">{{ ucwords(str_replace('_', ' ', $kyc->document_type ?? 'Document')) }} - Front <span class="text-danger">*</span></label>
                                    <div class="upload-area" data-field="document_front_image">
                                        @if($kyc->document_front_image)
                                            <div class="uploaded-file">
                                                <img src="{{ asset('storage/' . $kyc->document_front_image) }}" alt="Document Front" class="img-fluid">
                                                <div class="file-actions">
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteDocument('document_front_image')">
                                                        <i class="fe fe-trash-2"></i> Delete
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <div class="upload-placeholder">
                                                <i class="fe fe-upload display-4 text-muted"></i>
                                                <h6 class="mt-2">Drop file here or click to upload</h6>
                                                <p class="text-muted">JPEG, PNG, JPG (Max: 2MB)</p>
                                            </div>
                                        @endif
                                        <input type="file" name="document_front_image" class="file-input" accept=".jpg,.jpeg,.png" {{ !$kyc->document_front_image ? 'required' : '' }}>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Document Back Image (conditional) -->
                                @if($kyc->document_type === 'nid' && $kyc->nid_type === 'smart_card' || $kyc->document_type === 'driving_license')
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">{{ ucwords(str_replace('_', ' ', $kyc->document_type ?? 'Document')) }} - Back <span class="text-danger">*</span></label>
                                        <div class="upload-area" data-field="document_back_image">
                                            @if($kyc->document_back_image)
                                                <div class="uploaded-file">
                                                    <img src="{{ asset('storage/' . $kyc->document_back_image) }}" alt="Document Back" class="img-fluid">
                                                    <div class="file-actions">
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteDocument('document_back_image')">
                                                            <i class="fe fe-trash-2"></i> Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="upload-placeholder">
                                                    <i class="fe fe-upload display-4 text-muted"></i>
                                                    <h6 class="mt-2">Drop file here or click to upload</h6>
                                                    <p class="text-muted">JPEG, PNG, JPG (Max: 2MB)</p>
                                                </div>
                                            @endif
                                            <input type="file" name="document_back_image" class="file-input" accept=".jpg,.jpeg,.png" {{ !$kyc->document_back_image ? 'required' : '' }}>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                @endif

                                <!-- User Photo -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">User Photo <span class="text-danger">*</span></label>
                                    <div class="upload-area" data-field="user_photo">
                                        @if($kyc->user_photo)
                                            <div class="uploaded-file">
                                                <img src="{{ asset('storage/' . $kyc->user_photo) }}" alt="User Photo" class="img-fluid">
                                                <div class="file-actions">
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteDocument('user_photo')">
                                                        <i class="fe fe-trash-2"></i> Delete
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <div class="upload-placeholder">
                                                <i class="fe fe-user display-4 text-muted"></i>
                                                <h6 class="mt-2">Drop file here or click to upload</h6>
                                                <p class="text-muted">Clear selfie or passport photo</p>
                                            </div>
                                        @endif
                                        <input type="file" name="user_photo" class="file-input" accept=".jpg,.jpeg,.png" {{ !$kyc->user_photo ? 'required' : '' }}>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- User Signature -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">User Signature (Optional)</label>
                                    <div class="upload-area" data-field="user_signature">
                                        @if($kyc->user_signature)
                                            <div class="uploaded-file">
                                                <img src="{{ asset('storage/' . $kyc->user_signature) }}" alt="User Signature" class="img-fluid">
                                                <div class="file-actions">
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteDocument('user_signature')">
                                                        <i class="fe fe-trash-2"></i> Delete
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <div class="upload-placeholder">
                                                <i class="fe fe-edit display-4 text-muted"></i>
                                                <h6 class="mt-2">Drop file here or click to upload</h6>
                                                <p class="text-muted">Signature on white paper</p>
                                            </div>
                                        @endif
                                        <input type="file" name="user_signature" class="file-input" accept=".jpg,.jpeg,.png">
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Utility Bill -->
                                <div class="col-md-12 mb-4">
                                    <label class="form-label">Utility Bill / Address Proof (Optional)</label>
                                    <div class="upload-area large" data-field="utility_bill">
                                        @if($kyc->utility_bill)
                                            <div class="uploaded-file">
                                                @if(Str::endsWith($kyc->utility_bill, ['.pdf']))
                                                    <div class="pdf-preview">
                                                        <i class="fe fe-file-text display-4 text-danger"></i>
                                                        <p>{{ basename($kyc->utility_bill) }}</p>
                                                        <a href="{{ asset('storage/' . $kyc->utility_bill) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="fe fe-eye"></i> View PDF
                                                        </a>
                                                    </div>
                                                @else
                                                    <img src="{{ asset('storage/' . $kyc->utility_bill) }}" alt="Utility Bill" class="img-fluid">
                                                @endif
                                                <div class="file-actions">
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteDocument('utility_bill')">
                                                        <i class="fe fe-trash-2"></i> Delete
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <div class="upload-placeholder">
                                                <i class="fe fe-file display-4 text-muted"></i>
                                                <h6 class="mt-2">Drop file here or click to upload</h6>
                                                <p class="text-muted">Gas/Electricity/Water bill for address verification<br>JPEG, PNG, PDF (Max: 3MB)</p>
                                            </div>
                                        @endif
                                        <input type="file" name="utility_bill" class="file-input" accept=".jpg,.jpeg,.png,.pdf">
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Upload Progress -->
                            <div id="uploadProgress" class="progress mb-4" style="display: none; height: 8px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                                     style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('member.kyc.step', 3) }}" class="btn btn-outline-secondary">
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

        <!-- Upload Tips -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card border-success">
                    <div class="card-body">
                        <h6 class="text-success"><i class="fe fe-camera"></i> Photo Tips</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="mb-0">
                                    <li>Take photos in good lighting conditions</li>
                                    <li>Ensure all text is clearly readable</li>
                                    <li>No shadows or reflections on documents</li>
                                    <li>Keep documents flat and straight</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="mb-0">
                                    <li>Use high resolution (at least 300 DPI)</li>
                                    <li>Crop out unnecessary background</li>
                                    <li>Make sure documents are not expired</li>
                                    <li>Upload original documents only</li>
                                </ul>
                            </div>
                        </div>
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

.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    position: relative;
    transition: all 0.3s ease;
    cursor: pointer;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.upload-area.large {
    min-height: 150px;
}

.upload-area:hover {
    border-color: var(--bs-primary);
    background-color: rgba(var(--bs-primary-rgb), 0.05);
}

.upload-area.dragover {
    border-color: var(--bs-primary);
    background-color: rgba(var(--bs-primary-rgb), 0.1);
}

.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.uploaded-file {
    position: relative;
    width: 100%;
}

.uploaded-file img {
    max-width: 100%;
    max-height: 200px;
    border-radius: 4px;
    border: 1px solid #dee2e6;
}

.pdf-preview {
    text-align: center;
}

.file-actions {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(255, 255, 255, 0.9);
    padding: 5px;
    border-radius: 4px;
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
    .upload-area {
        min-height: 150px;
        padding: 15px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('kycStep4Form');
    const submitBtn = document.getElementById('submitBtn');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = uploadProgress.querySelector('.progress-bar');
    
    // Handle drag and drop for upload areas
    document.querySelectorAll('.upload-area').forEach(area => {
        const fileInput = area.querySelector('.file-input');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            area.addEventListener(eventName, preventDefaults, false);
        });
        
        ['dragenter', 'dragover'].forEach(eventName => {
            area.addEventListener(eventName, () => area.classList.add('dragover'), false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            area.addEventListener(eventName, () => area.classList.remove('dragover'), false);
        });
        
        area.addEventListener('drop', function(e) {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileUpload(fileInput, files[0]);
            }
        });
        
        area.addEventListener('click', () => fileInput.click());
        
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                handleFileUpload(this, this.files[0]);
            }
        });
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    function handleFileUpload(input, file) {
        const uploadArea = input.closest('.upload-area');
        const field = uploadArea.dataset.field;
        
        // Validate file
        if (!validateFile(file, field)) {
            input.value = '';
            return;
        }
        
        // Show preview
        showFilePreview(uploadArea, file);
    }
    
    function validateFile(file, field) {
        const maxSize = field === 'utility_bill' ? 3 * 1024 * 1024 : 2 * 1024 * 1024; // 3MB for utility bill, 2MB for images
        const allowedTypes = field === 'utility_bill' 
            ? ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf']
            : ['image/jpeg', 'image/png', 'image/jpg'];
        
        if (file.size > maxSize) {
            alert(`File size too large. Maximum allowed: ${maxSize / (1024 * 1024)}MB`);
            return false;
        }
        
        if (!allowedTypes.includes(file.type)) {
            alert('Invalid file type. Please select a valid file.');
            return false;
        }
        
        return true;
    }
    
    function showFilePreview(uploadArea, file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const placeholder = uploadArea.querySelector('.upload-placeholder');
            const uploadedFile = uploadArea.querySelector('.uploaded-file');
            
            if (uploadedFile) {
                uploadedFile.remove();
            }
            
            if (placeholder) {
                placeholder.style.display = 'none';
            }
            
            const preview = document.createElement('div');
            preview.className = 'uploaded-file';
            
            if (file.type === 'application/pdf') {
                preview.innerHTML = `
                    <div class="pdf-preview">
                        <i class="fe fe-file-text display-4 text-danger"></i>
                        <p>${file.name}</p>
                    </div>
                `;
            } else {
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="img-fluid">`;
            }
            
            uploadArea.appendChild(preview);
        };
        
        reader.readAsDataURL(file);
    }
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fe fe-loader"></i> Uploading...';
        uploadProgress.style.display = 'block';
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.next_step) {
                    window.location.href = '{{ route("member.kyc.step", "STEP_PLACEHOLDER") }}'.replace('STEP_PLACEHOLDER', data.next_step);
                } else {
                    window.location.href = '{{ route("member.kyc.index") }}';
                }
            } else {
                alert('Error: ' + data.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Next Step <i class="fe fe-arrow-right"></i>';
                uploadProgress.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            alert('Upload failed. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Next Step <i class="fe fe-arrow-right"></i>';
            uploadProgress.style.display = 'none';
        });
    });
});

function deleteDocument(documentType) {
    if (!confirm('Are you sure you want to delete this document?')) {
        return;
    }
    
    fetch('{{ route("member.kyc.delete-document") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            document_type: documentType
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        alert('Delete failed. Please try again.');
    });
}
</script>
@endsection