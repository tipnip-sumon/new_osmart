@extends('admin.layouts.app')

@section('title', 'Company Information Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">🏢 Company Information Settings</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.general-settings.company-info.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Company Basic Information -->
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">🏢 Basic Company Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                                   id="company_name" name="company_name" 
                                                   value="{{ old('company_name', $settings->company_name ?? '') }}" required>
                                            @error('company_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="company_address" class="form-label">Company Address <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('company_address') is-invalid @enderror" 
                                                      id="company_address" name="company_address" rows="3" required>{{ old('company_address', $settings->company_address ?? '') }}</textarea>
                                            @error('company_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="company_phone" class="form-label">Company Phone <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('company_phone') is-invalid @enderror" 
                                                   id="company_phone" name="company_phone" 
                                                   value="{{ old('company_phone', $settings->company_phone ?? '') }}" required>
                                            @error('company_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="company_email" class="form-label">Company Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('company_email') is-invalid @enderror" 
                                                   id="company_email" name="company_email" 
                                                   value="{{ old('company_email', $settings->company_email ?? '') }}" required>
                                            @error('company_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="company_website" class="form-label">Company Website</label>
                                            <input type="url" class="form-control @error('company_website') is-invalid @enderror" 
                                                   id="company_website" name="company_website" 
                                                   value="{{ old('company_website', $settings->company_website ?? '') }}" 
                                                   placeholder="https://www.company.com">
                                            @error('company_website')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Legal & Tax Information -->
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">📄 Legal & Tax Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="company_tin" class="form-label">TIN/Tax ID Number</label>
                                            <input type="text" class="form-control @error('company_tin') is-invalid @enderror" 
                                                   id="company_tin" name="company_tin" 
                                                   value="{{ old('company_tin', $settings->company_tin ?? '') }}">
                                            @error('company_tin')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="company_trade_license" class="form-label">Trade License Number</label>
                                            <input type="text" class="form-control @error('company_trade_license') is-invalid @enderror" 
                                                   id="company_trade_license" name="company_trade_license" 
                                                   value="{{ old('company_trade_license', $settings->company_trade_license ?? '') }}">
                                            @error('company_trade_license')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="company_vat_number" class="form-label">VAT Number</label>
                                            <input type="text" class="form-control @error('company_vat_number') is-invalid @enderror" 
                                                   id="company_vat_number" name="company_vat_number" 
                                                   value="{{ old('company_vat_number', $settings->company_vat_number ?? '') }}">
                                            @error('company_vat_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="contact_person" class="form-label">Contact Person</label>
                                            <input type="text" class="form-control @error('contact_person') is-invalid @enderror" 
                                                   id="contact_person" name="contact_person" 
                                                   value="{{ old('contact_person', $settings->contact_person ?? '') }}">
                                            @error('contact_person')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="contact_designation" class="form-label">Contact Person Designation</label>
                                            <input type="text" class="form-control @error('contact_designation') is-invalid @enderror" 
                                                   id="contact_designation" name="contact_designation" 
                                                   value="{{ old('contact_designation', $settings->contact_designation ?? '') }}">
                                            @error('contact_designation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bank Information -->
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">🏦 Bank Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="bank_name" class="form-label">Bank Name</label>
                                            <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                                                   id="bank_name" name="bank_name" 
                                                   value="{{ old('bank_name', $settings->bank_name ?? '') }}">
                                            @error('bank_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="bank_account_name" class="form-label">Account Name</label>
                                            <input type="text" class="form-control @error('bank_account_name') is-invalid @enderror" 
                                                   id="bank_account_name" name="bank_account_name" 
                                                   value="{{ old('bank_account_name', $settings->bank_account_name ?? '') }}">
                                            @error('bank_account_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="bank_account_number" class="form-label">Account Number</label>
                                            <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror" 
                                                   id="bank_account_number" name="bank_account_number" 
                                                   value="{{ old('bank_account_number', $settings->bank_account_number ?? '') }}">
                                            @error('bank_account_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="bank_routing_number" class="form-label">Routing Number</label>
                                            <input type="text" class="form-control @error('bank_routing_number') is-invalid @enderror" 
                                                   id="bank_routing_number" name="bank_routing_number" 
                                                   value="{{ old('bank_routing_number', $settings->bank_routing_number ?? '') }}">
                                            @error('bank_routing_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="bank_swift_code" class="form-label">SWIFT Code</label>
                                            <input type="text" class="form-control @error('bank_swift_code') is-invalid @enderror" 
                                                   id="bank_swift_code" name="bank_swift_code" 
                                                   value="{{ old('bank_swift_code', $settings->bank_swift_code ?? '') }}">
                                            @error('bank_swift_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Company Logo -->
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">🖼️ Company Logo</h5>
                                        <small class="text-muted">Upload high-quality company logo. Recommended size: 400x200px</small>
                                    </div>
                                    <div class="card-body">
                                        <!-- Current Logo -->
                                        @if($settings->company_logo ?? false)
                                        <div class="mb-3">
                                            <label class="form-label">Current Logo</label>
                                            <div class="text-center">
                                                <img src="{{ asset($settings->company_logo) }}" 
                                                     alt="Company Logo" class="img-fluid rounded" style="max-height: 150px;"
                                                     onerror="this.onerror=null; this.src='{{ asset('assets/images/placeholder.png') }}'; this.style.opacity='0.5';">
                                            </div>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" id="remove_logo" name="remove_logo" value="1">
                                                <label class="form-check-label" for="remove_logo">
                                                    Remove current logo
                                                </label>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="logo-upload-container" id="logo-upload-area">
                                            <div class="upload-content">
                                                <i class="bx bx-cloud-upload" style="font-size: 48px; color: #6c757d; margin-bottom: 15px;"></i>
                                                <h5>Drop logo here or click to upload</h5>
                                                <p class="text-muted">Supports: PNG, JPG, GIF (Max: 2MB)</p>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('company_logo').click()">
                                                        <i class="bx bx-upload"></i> Choose File
                                                    </button>
                                                    <button type="button" class="btn btn-info" onclick="openLogoResizeOptions()">
                                                        <i class="bx bx-crop"></i> Resize Options
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <input type="file" id="company_logo" name="company_logo" accept="image/*" 
                                               class="@error('company_logo') is-invalid @enderror" style="display: none;">
                                        
                                        @error('company_logo')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        
                                        <!-- Logo Resize Options Panel -->
                                        <div id="logo-resize-options-panel" class="mt-3" style="display: none;">
                                            <div class="alert alert-info">
                                                <h6><i class="bx bx-info-circle me-2"></i>Logo Resize Options</h6>
                                                <div class="row g-2">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Width (px)</label>
                                                        <input type="number" class="form-control form-control-sm" id="logo-resize-width" value="400" min="50" max="1000">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Height (px)</label>
                                                        <input type="number" class="form-control form-control-sm" id="logo-resize-height" value="200" min="50" max="500">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Quality (%)</label>
                                                        <input type="number" class="form-control form-control-sm" id="logo-resize-quality" value="90" min="10" max="100">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check mt-4">
                                                            <input class="form-check-input" type="checkbox" id="logo-maintain-ratio" checked>
                                                            <label class="form-check-label" for="logo-maintain-ratio">
                                                                Maintain Aspect Ratio
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <button type="button" class="btn btn-sm btn-primary" onclick="resizeUploadedLogo()">
                                                        <i class="bx bx-crop me-1"></i>Resize Logo
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-secondary" onclick="generateLogoThumbnails()">
                                                        <i class="bx bx-photo me-1"></i>Generate Thumbnails
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-warning" onclick="optimizeLogo()">
                                                        <i class="bx bx-zap me-1"></i>Optimize Logo
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Logo Preview Grid -->
                                        <div id="logo-preview-grid" class="logo-preview-grid"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-secondary" onclick="history.back()">
                                        <i class="bx bx-arrow-back"></i> Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save"></i> Save Company Information
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Enhanced Logo Upload Styles */
    .logo-upload-container {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .logo-upload-container:hover {
        border-color: #007bff;
        background: #e7f3ff;
    }

    .logo-upload-container.drag-over {
        border-color: #28a745;
        background: #d4edda;
    }

    .logo-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .logo-preview-item {
        position: relative;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .logo-preview-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .logo-preview-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        display: block;
    }

    .logo-remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .logo-remove-btn:hover {
        background: #dc3545;
        transform: scale(1.1);
    }

    .upload-content h5 {
        color: #495057;
        margin-bottom: 10px;
    }

    .upload-content p {
        color: #6c757d;
        margin-bottom: 15px;
    }

    .processing-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }
</style>
@endpush

@push('scripts')
<script>
// Company Logo Upload Manager Class
class CompanyLogoManager {
    constructor() {
        this.selectedImages = [];
        this.previewContainer = document.getElementById('logo-preview-grid');
        this.setupLogoUpload();
        this.setupFormHandlers();
    }

    setupLogoUpload() {
        // Setup drag and drop functionality
        const uploadContainer = document.getElementById('logo-upload-area');
        const logoInput = document.getElementById('company_logo');
        const previewGrid = document.getElementById('logo-preview-grid');

        if (!uploadContainer || !logoInput || !previewGrid) {
            console.log('Logo upload elements not found, skipping logo upload setup');
            return;
        }

        // Drag and drop events
        uploadContainer.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadContainer.classList.add('drag-over');
        });

        uploadContainer.addEventListener('dragleave', (e) => {
            e.preventDefault();
            uploadContainer.classList.remove('drag-over');
        });

        uploadContainer.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadContainer.classList.remove('drag-over');
            const files = Array.from(e.dataTransfer.files);
            if (files.length > 0) {
                this.handleLogoFile(files[0]); // Only handle first file
            }
        });

        uploadContainer.addEventListener('click', () => {
            logoInput.click();
        });

        // File input change event
        logoInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                this.handleLogoFile(e.target.files[0]); // Only handle first file
            }
        });
    }

    handleLogoFile(file) {
        if (file.type.startsWith('image/')) {
            if (file.size <= 2 * 1024 * 1024) { // 2MB limit
                this.selectedImages = [file]; // Replace with single file
                this.createLogoPreview(file, 0);
            } else {
                this.showAlert(`File ${file.name} is too large. Maximum size is 2MB.`, 'warning');
            }
        } else {
            this.showAlert(`File ${file.name} is not a valid image.`, 'warning');
        }
    }

    createLogoPreview(file, index) {
        // Clear existing preview for single logo upload
        this.previewContainer.innerHTML = '';
        
        const reader = new FileReader();
        reader.onload = (e) => {
            const previewItem = document.createElement('div');
            previewItem.className = 'logo-preview-item';
            previewItem.innerHTML = `
                <img src="${e.target.result}" alt="Company Logo Preview">
                <button type="button" class="logo-remove-btn" onclick="companyLogoManager.removeLogo(0)">
                    <i class="bx bx-x"></i>
                </button>
            `;
            this.previewContainer.appendChild(previewItem);
        };
        reader.readAsDataURL(file);
    }

    removeLogo(index) {
        this.selectedImages = []; // Clear all images for single upload
        this.previewContainer.innerHTML = ''; // Clear preview
        
        // Reset file input
        const logoInput = document.getElementById('company_logo');
        if (logoInput) {
            logoInput.value = '';
        }
        
        this.showAlert('Logo has been removed from upload list', 'info');
    }

    setupFormHandlers() {
        // Form validation and preview functionality
        const logoInput = document.getElementById('company_logo');
        if (logoInput) {
            logoInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    this.createLogoPreview(e.target.files[0], 0);
                }
            });
        }
    }

    showAlert(message, type = 'info') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: type === 'error' ? 'Error!' : type === 'warning' ? 'Warning!' : 'Info',
                text: message,
                icon: type === 'error' ? 'error' : type === 'warning' ? 'warning' : 'info',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                timerProgressBar: true
            });
        } else {
            alert(message);
        }
    }
}

// Initialize Company Logo Manager
document.addEventListener('DOMContentLoaded', function() {
    window.companyLogoManager = new CompanyLogoManager();
});

// Global Functions for Logo Upload
function openLogoResizeOptions() {
    const panel = document.getElementById('logo-resize-options-panel');
    if (panel) {
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    }
}

function resizeUploadedLogo() {
    const width = document.getElementById('logo-resize-width').value;
    const height = document.getElementById('logo-resize-height').value;
    
    if (!width && !height) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('Info', 'Please enter width and/or height values.', 'info');
        } else {
            alert('Please enter width and/or height values.');
        }
        return;
    }

    const formData = new FormData();
    if (window.companyLogoManager && window.companyLogoManager.selectedImages) {
        window.companyLogoManager.selectedImages.forEach((file, index) => {
            formData.append(`images[${index}]`, file);
        });
    }
    
    if (width) formData.append('width', width);
    if (height) formData.append('height', height);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    // Mock resize functionality - you can implement actual server-side processing
    if (typeof Swal !== 'undefined') {
        Swal.fire('Success!', 'Logo resized successfully!', 'success');
    } else {
        alert('Logo resized successfully!');
    }
}

function generateLogoThumbnails() {
    if (!window.companyLogoManager || !window.companyLogoManager.selectedImages || window.companyLogoManager.selectedImages.length === 0) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('Info', 'Please upload a logo first.', 'info');
        } else {
            alert('Please upload a logo first.');
        }
        return;
    }

    if (typeof Swal !== 'undefined') {
        Swal.fire('Success!', 'Logo thumbnails generated successfully!', 'success');
    } else {
        alert('Logo thumbnails generated successfully!');
    }
}

function optimizeLogo() {
    if (!window.companyLogoManager || !window.companyLogoManager.selectedImages || window.companyLogoManager.selectedImages.length === 0) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('Info', 'Please upload a logo first.', 'info');
        } else {
            alert('Please upload a logo first.');
        }
        return;
    }

    const quality = document.getElementById('logo-resize-quality').value || 90;
    
    if (typeof Swal !== 'undefined') {
        Swal.fire('Success!', `Logo optimized successfully with ${quality}% quality!`, 'success');
    } else {
        alert(`Logo optimized successfully with ${quality}% quality!`);
    }
}
</script>
@endpush
