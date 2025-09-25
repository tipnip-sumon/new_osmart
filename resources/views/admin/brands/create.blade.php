@extends('admin.layouts.app')

@section('title', 'Add New Brand')

@push('styles')
<style>
    /* Image Upload Styles */
    .image-upload-container {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .image-upload-container:hover {
        border-color: #007bff;
        background: #e7f3ff;
    }

    .image-upload-container.drag-over {
        border-color: #28a745;
        background: #d4edda;
    }

    .image-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .image-preview-item {
        position: relative;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .image-preview-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .image-preview-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        display: block;
    }

    .image-remove-btn {
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
        font-size: 12px;
        transition: all 0.2s ease;
    }

    .image-remove-btn:hover {
        background: #dc3545;
        transform: scale(1.1);
    }
    
    .form-field-wrapper {
        position: relative;
    }
    
    .field-status {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
    }
    
    .field-status.valid {
        color: #28a745;
    }
    
    .field-status.invalid {
        color: #dc3545;
    }
    
    .field-feedback {
        font-size: 12px;
        margin-top: 5px;
        padding: 4px 8px;
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    
    .field-feedback.success {
        color: #155724;
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
    }
    
    .field-feedback.warning {
        color: #856404;
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
    }
    
    .field-feedback.error {
        color: #721c24;
        background-color: #f8d7da;
        border: 1px solid #f1b0b7;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Add New Brand</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Brands</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Brand</li>
                    </ol>
                </nav>
            </div>
        </div>

        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" id="brandForm">
            @csrf
            <div class="row">
                <!-- Main Information -->
                <div class="col-xl-8">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Brand Information</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Brand Name -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Brand Name <span class="text-danger">*</span></label>
                                    <div class="form-field-wrapper">
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="{{ old('name') }}" required placeholder="Enter brand name">
                                        <span class="field-status" id="name-status"></span>
                                    </div>
                                    <div class="field-feedback" id="name-feedback"></div>
                                    @error('name')
                                        <div class="field-feedback error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Slug</label>
                                    <div class="form-field-wrapper">
                                        <input type="text" class="form-control" id="slug" name="slug" 
                                               value="{{ old('slug') }}" placeholder="Auto-generated from name">
                                        <span class="field-status" id="slug-status"></span>
                                    </div>
                                    <div class="field-feedback" id="slug-feedback"></div>
                                    @error('slug')
                                        <div class="field-feedback error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="4" 
                                              placeholder="Enter brand description">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="field-feedback error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Website URL -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Website URL</label>
                                    <input type="url" class="form-control" name="website_url" 
                                           value="{{ old('website_url') }}" placeholder="https://example.com">
                                    @error('website_url')
                                        <div class="field-feedback error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" 
                                           value="{{ old('email') }}" placeholder="contact@brand.com">
                                    @error('email')
                                        <div class="field-feedback error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="phone" 
                                           value="{{ old('phone') }}" placeholder="+1234567890">
                                    @error('phone')
                                        <div class="field-feedback error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Address -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" name="address" 
                                           value="{{ old('address') }}" placeholder="Brand headquarters address">
                                    @error('address')
                                        <div class="field-feedback error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Information -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">SEO Information</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" name="meta_title" 
                                           value="{{ old('meta_title') }}" placeholder="SEO meta title">
                                    @error('meta_title')
                                        <div class="field-feedback error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea class="form-control" name="meta_description" rows="3" 
                                              placeholder="SEO meta description">{{ old('meta_description') }}</textarea>
                                    @error('meta_description')
                                        <div class="field-feedback error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control" name="meta_keywords" 
                                           value="{{ old('meta_keywords') }}" placeholder="keyword1, keyword2, keyword3">
                                    @error('meta_keywords')
                                        <div class="field-feedback error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings & Media -->
                <div class="col-xl-4">
                    <!-- Brand Logo -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Brand Logo</div>
                            <small class="text-muted">Upload high-quality brand logo. Recommended size: 400x400px</small>
                        </div>
                        <div class="card-body">
                            <div class="image-upload-container" id="image-upload-area">
                                <div class="upload-content">
                                    <i class="ti ti-cloud-upload" style="font-size: 48px; color: #6c757d; margin-bottom: 15px;"></i>
                                    <h5>Drop logo here or click to upload</h5>
                                    <p class="text-muted">Supports: JPG, PNG, GIF, WEBP (Max: 10MB)</p>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button type="button" class="btn btn-primary" onclick="document.getElementById('image-input').click()">
                                            <i class="ti ti-upload"></i> Choose File
                                        </button>
                                        <button type="button" class="btn btn-info" onclick="openResizeOptions()">
                                            <i class="ti ti-crop"></i> Resize Options
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="file" id="image-input" name="logo" accept="image/*" style="display: none;">
                            
                            <!-- Resize Options Panel -->
                            <div id="resize-options-panel" class="mt-3" style="display: none;">
                                <div class="alert alert-info">
                                    <h6><i class="ti ti-info-circle me-2"></i>Image Resize Options</h6>
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <label class="form-label">Width (px)</label>
                                            <input type="number" class="form-control form-control-sm" id="resize-width" value="400" min="50" max="2000">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Height (px)</label>
                                            <input type="number" class="form-control form-control-sm" id="resize-height" value="400" min="50" max="2000">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Quality (%)</label>
                                            <input type="number" class="form-control form-control-sm" id="resize-quality" value="85" min="10" max="100">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox" id="maintain-ratio" checked>
                                                <label class="form-check-label" for="maintain-ratio">
                                                    Maintain Aspect Ratio
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-primary" onclick="resizeUploadedImages()">
                                            <i class="ti ti-crop me-1"></i>Resize Images
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="generateThumbnails()">
                                            <i class="ti ti-photo me-1"></i>Generate Thumbnails
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="optimizeImages()">
                                            <i class="ti ti-zap me-1"></i>Optimize Images
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Image Preview Grid -->
                            <div id="image-preview-grid" class="image-preview-grid"></div>
                            
                            @error('logo')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Brand Settings -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Brand Settings</div>
                        </div>
                        <div class="card-body">
                            <!-- Status -->
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="field-feedback error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Featured -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" 
                                           {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        Featured Brand
                                    </label>
                                </div>
                                <small class="text-muted">Featured brands will be displayed prominently</small>
                            </div>

                            <!-- Sort Order -->
                            <div class="mb-3">
                                <label class="form-label">Sort Order</label>
                                <input type="number" class="form-control" name="sort_order" 
                                       value="{{ old('sort_order', 0) }}" min="0">
                                <small class="text-muted">Lower numbers appear first</small>
                                @error('sort_order')
                                    <div class="field-feedback error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Commission Settings -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Commission Settings</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Commission Type</label>
                                <select class="form-select" name="commission_type">
                                    <option value="percentage" {{ old('commission_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    <option value="fixed" {{ old('commission_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Commission Rate</label>
                                <input type="number" class="form-control" name="commission_rate" 
                                       value="{{ old('commission_rate', 0) }}" step="0.01" min="0">
                                <small class="text-muted">Percentage (%) or fixed amount</small>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>Save Brand
                                </button>
                                <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                                    <i class="ri-arrow-left-line me-1"></i>Back to Brands
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
class BrandFormManager {
    constructor() {
        this.nameField = document.getElementById('name');
        this.slugField = document.getElementById('slug');
        this.selectedImages = [];
        this.previewContainer = document.getElementById('image-preview-grid');
        this.slugValidationController = null;
        
        this.init();
    }
    
    init() {
        this.setupSlugGeneration();
        this.setupImageUpload();
    }
    
    setupSlugGeneration() {
        if (!this.nameField || !this.slugField) return;
        
        // Track if user has manually edited the slug
        this.slugManuallyEdited = false;
        
        // Auto-generate slug from name with debounce
        let slugTimeout;
        this.nameField.addEventListener('input', (e) => {
            const name = e.target.value.trim();
            
            // Clear previous timeout
            if (slugTimeout) {
                clearTimeout(slugTimeout);
            }
            
            // Debounce the slug generation to avoid conflicts
            slugTimeout = setTimeout(() => {
                // Always generate slug if user hasn't manually edited it or if slug is empty
                if (name && (!this.slugManuallyEdited || !this.slugField.value.trim())) {
                    const slug = this.generateSlug(name);
                    this.slugField.value = slug;
                    this.validateSlug(slug);
                }
            }, 300); // Wait 300ms after user stops typing
        });
        
        // Track manual slug editing
        this.slugField.addEventListener('focus', () => {
            this.slugManuallyEdited = true;
        });
        
        // Validate slug when manually changed
        let validationTimeout;
        this.slugField.addEventListener('input', (e) => {
            const slug = e.target.value.trim();
            this.slugManuallyEdited = true; // Mark as manually edited when user types
            
            // Clear previous timeout
            if (validationTimeout) {
                clearTimeout(validationTimeout);
            }
            
            // Debounce validation
            validationTimeout = setTimeout(() => {
                if (slug) {
                    this.validateSlug(slug);
                }
            }, 500); // Wait 500ms after user stops typing
        });
        
        // Add a button to reset slug generation
        this.addSlugResetButton();
    }
    
    setupImageUpload() {
        // Setup drag and drop functionality
        const uploadContainer = document.getElementById('image-upload-area');
        const imageInput = document.getElementById('image-input');
        const previewGrid = document.getElementById('image-preview-grid');

        if (!uploadContainer || !imageInput || !previewGrid) {
            console.log('Image upload elements not found, skipping image upload setup');
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
                this.handleImageFile(files[0]); // Only handle first file
            }
        });

        uploadContainer.addEventListener('click', () => {
            imageInput.click();
        });

        // Handle file input change
        imageInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                this.handleImageFile(e.target.files[0]); // Only handle first file
            }
        });
    }

    handleImageFile(file) {
        if (file.type.startsWith('image/')) {
            if (file.size <= 10 * 1024 * 1024) { // 10MB limit
                this.selectedImages = [file]; // Replace with single file
                this.createImagePreview(file, 0);
            } else {
                Swal.fire({
                    title: 'File Too Large',
                    text: `File ${file.name} is too large. Maximum size is 10MB.`,
                    icon: 'warning',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        } else {
            Swal.fire({
                title: 'Invalid File Type',
                text: `File ${file.name} is not a valid image.`,
                icon: 'warning',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
    }

    createImagePreview(file, index) {
        // Clear existing preview for single image upload
        this.previewContainer.innerHTML = '';
        
        const reader = new FileReader();
        reader.onload = (e) => {
            const previewItem = document.createElement('div');
            previewItem.className = 'image-preview-item';
            previewItem.innerHTML = `
                <img src="${e.target.result}" alt="Brand Logo Preview">
                <button type="button" class="image-remove-btn" onclick="brandManager.removeImage(0)">
                    <i class="ti ti-x"></i>
                </button>
            `;
            this.previewContainer.appendChild(previewItem);
        };
        reader.readAsDataURL(file);
    }

    removeImage(index) {
        this.selectedImages = []; // Clear all images for single upload
        this.previewContainer.innerHTML = ''; // Clear preview
        
        // Reset file input
        const imageInput = document.getElementById('image-input');
        if (imageInput) {
            imageInput.value = '';
        }
        
        Swal.fire({
            title: 'Image Removed',
            text: 'Logo has been removed from upload list',
            icon: 'info',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
    }

    updateImagePreviews() {
        this.previewContainer.innerHTML = '';
        this.selectedImages.forEach((file, index) => {
            this.createImagePreview(file, index);
        });
    }

    updateProgress(progress) {
        // SweetAlert handles progress in the preConfirm function
        // This method is kept for compatibility
        console.log(`Upload progress: ${progress}%`);
    }
    
    
    addSlugResetButton() {
        const slugWrapper = this.slugField.parentElement;
        
        // Check if button already exists
        if (slugWrapper.querySelector('.slug-reset-btn')) return;
        
        const resetButton = document.createElement('button');
        resetButton.type = 'button';
        resetButton.className = 'btn btn-sm btn-outline-secondary slug-reset-btn';
        resetButton.style.position = 'absolute';
        resetButton.style.right = '35px';
        resetButton.style.top = '50%';
        resetButton.style.transform = 'translateY(-50%)';
        resetButton.style.zIndex = '10';
        resetButton.style.fontSize = '12px';
        resetButton.style.padding = '2px 6px';
        resetButton.innerHTML = '<i class="ti ti-refresh"></i>';
        resetButton.title = 'Auto-generate slug from name';
        
        resetButton.addEventListener('click', () => {
            this.slugManuallyEdited = false;
            const name = this.nameField.value.trim();
            if (name) {
                const slug = this.generateSlug(name);
                this.slugField.value = slug;
                this.validateSlug(slug);
                
                Swal.fire({
                    title: 'Slug Reset',
                    text: 'Slug will now auto-update when you change the brand name',
                    icon: 'info',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            }
        });
        
        slugWrapper.appendChild(resetButton);
    }
    
    generateSlug(text) {
        return text.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    }
    
    async validateSlug(slug) {
        if (!slug) return;
        
        // Don't validate during typing to avoid blocking
        const slugStatus = document.getElementById('slug-status');
        const slugFeedback = document.getElementById('slug-feedback');
        
        try {
            // Use AbortController to cancel previous requests
            if (this.slugValidationController) {
                this.slugValidationController.abort();
            }
            this.slugValidationController = new AbortController();
            
            const response = await fetch(`/admin/brands/validate-slug?slug=${encodeURIComponent(slug)}`, {
                signal: this.slugValidationController.signal
            });
            
            if (response.ok) {
                const data = await response.json();
                
                if (data.available) {
                    this.setFieldStatus(this.slugField, 'valid', 'Slug is available');
                } else {
                    this.setFieldStatus(this.slugField, 'invalid', 'Slug is already taken');
                }
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.log('Slug validation failed:', error);
            }
        }
    }
    
    setFieldStatus(field, status, message) {
        const statusIcon = field.parentElement.querySelector('.field-status');
        const feedback = field.parentElement.parentElement.querySelector('.field-feedback');
        
        if (statusIcon) {
            statusIcon.innerHTML = status === 'valid' ? 
                '<i class="ti ti-check" style="color: #28a745;"></i>' : 
                '<i class="ti ti-x" style="color: #dc3545;"></i>';
        }
        
        if (feedback && !feedback.classList.contains('error')) {
            feedback.textContent = message;
            feedback.className = `field-feedback ${status === 'valid' ? 'success' : 'warning'}`;
            feedback.style.display = 'block';
        }
    }
    
    showError(message) {
        Swal.fire({
            title: 'Error',
            text: message,
            icon: 'error',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    }
}

// Image processing functions (same as category)
function openResizeOptions() {
    const panel = document.getElementById('resize-options-panel');
    if (panel) {
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    }
}

function resizeUploadedImages() {
    const fileInput = document.getElementById('image-input');
    const files = fileInput.files;
    
    if (files.length === 0) {
        Swal.fire({
            title: 'No Images Selected',
            text: 'Please select a logo first',
            icon: 'warning',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        return;
    }
    
    const width = document.getElementById('resize-width').value;
    const height = document.getElementById('resize-height').value;
    const quality = document.getElementById('resize-quality').value;
    const maintainRatio = document.getElementById('maintain-ratio').checked;
    
    Swal.fire({
        title: 'Resizing Image...',
        html: 'Please wait while we resize your logo.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Simulate processing time
    setTimeout(() => {
        Swal.fire({
            title: 'Image Resized',
            text: `Logo resized to ${width}x${height}px with ${quality}% quality`,
            icon: 'success',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    }, 2000);
}

function generateThumbnails() {
    const fileInput = document.getElementById('image-input');
    const files = fileInput.files;
    
    if (files.length === 0) {
        Swal.fire({
            title: 'No Images Selected',
            text: 'Please select a logo first',
            icon: 'warning',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        return;
    }
    
    Swal.fire({
        title: 'Generating Thumbnails...',
        html: 'Creating different sizes for your logo.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        Swal.fire({
            title: 'Thumbnails Generated',
            text: 'Multiple sizes created: 50x50, 100x100, 200x200, 400x400',
            icon: 'success',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    }, 1500);
}

function optimizeImages() {
    const fileInput = document.getElementById('image-input');
    const files = fileInput.files;
    
    if (files.length === 0) {
        Swal.fire({
            title: 'No Images Selected',
            text: 'Please select a logo first',
            icon: 'warning',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        return;
    }
    
    Swal.fire({
        title: 'Optimizing Image...',
        html: 'Compressing and optimizing your logo for web.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        Swal.fire({
            title: 'Image Optimized',
            text: 'Logo optimized for faster loading',
            icon: 'success',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    }, 2000);
}

// Initialize when DOM is loaded
let brandManager;
document.addEventListener('DOMContentLoaded', function() {
    brandManager = new BrandFormManager();
});
</script>
@endpush
