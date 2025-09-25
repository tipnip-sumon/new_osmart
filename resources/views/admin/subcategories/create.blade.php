@extends('admin.layouts.app')

@section('title', 'Create Subcategory')

@push('styles')
<style>
    .image-upload-container {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        position: relative;
        overflow: hidden;
    }

    .image-upload-container:hover {
        border-color: #007bff;
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.1);
    }

    .image-upload-container.dragover {
        border-color: #28a745;
        background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
        transform: scale(1.02);
    }

    .upload-content {
        position: relative;
        z-index: 2;
    }

    .image-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .image-preview-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .image-preview-item:hover {
        transform: scale(1.05);
    }

    .image-preview-item img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
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
        transition: all 0.3s ease;
    }

    .image-remove-btn:hover {
        background: #dc3545;
        transform: scale(1.1);
    }

    .character-counter {
        font-size: 12px;
        color: #6c757d;
        text-align: right;
        margin-top: 5px;
    }

    .character-counter.warning {
        color: #ffc107;
    }

    .character-counter.error {
        color: #dc3545;
    }

    .slug-status {
        display: inline-block;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 500;
        margin-left: 5px;
    }

    .slug-status.available {
        background-color: #d4edda;
        color: #155724;
    }

    .slug-status.unavailable {
        background-color: #f8d7da;
        color: #721c24;
    }

    .slug-status.checking {
        background-color: #fff3cd;
        color: #856404;
    }

    .form-floating {
        position: relative;
    }

    .form-floating > .form-control {
        height: calc(3.5rem + 2px);
        line-height: 1.25;
        padding: 1rem 0.75rem;
    }

    .form-floating > label {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        padding: 1rem 0.75rem;
        pointer-events: none;
        border: 1px solid transparent;
        transform-origin: 0 0;
        transition: opacity 0.1s ease-in-out, transform 0.1s ease-in-out;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        opacity: 0.65;
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    }

    .creation-progress {
        text-align: center;
    }

    .progress {
        height: 20px;
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar {
        background: linear-gradient(45deg, #007bff, #0056b3);
        transition: width 0.3s ease;
    }

    .error-details ul {
        padding-left: 20px;
    }

    .error-details li {
        margin: 5px 0;
        color: #6c757d;
    }

    /* Keyword input highlighting */
    .form-control.keyword-highlight {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    /* Real-time feedback styles */
    .field-feedback {
        font-size: 12px;
        margin-top: 5px;
        padding: 5px 10px;
        border-radius: 4px;
    }

    .field-feedback.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .field-feedback.warning {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .field-feedback.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Create Subcategory</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.subcategories.index') }}">Subcategories</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <form action="{{ route('admin.subcategories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Basic Information -->
                <div class="col-xl-8">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Basic Information</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Subcategory Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" placeholder="Enter subcategory name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" name="slug" value="{{ old('slug') }}" placeholder="Auto-generated from name">
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Leave empty to auto-generate from subcategory name</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" 
                                          placeholder="Enter subcategory description">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">Parent Category <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                            <option value="">Select Parent Category</option>
                                            <!-- Dynamic options will be populated by JavaScript -->
                                        </select>
                                        <button type="button" class="btn btn-outline-primary" id="reload-categories" title="Reload Categories">
                                            <i class="ri-refresh-line"></i>
                                        </button>
                                    </div>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <small class="text-muted">
                                            <i class="ri-information-line"></i> Loading categories from database...
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Higher number = appears first</div>
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
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                       id="meta_title" name="meta_title" value="{{ old('meta_title') }}" 
                                       placeholder="SEO friendly title">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Recommended length: 50-60 characters</div>
                            </div>

                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                          id="meta_description" name="meta_description" rows="3" 
                                          placeholder="SEO friendly description">{{ old('meta_description') }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Recommended length: 150-160 characters</div>
                            </div>

                            <div class="mb-3">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                       id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}" 
                                       placeholder="keyword1, keyword2, keyword3">
                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Separate keywords with commas</div>
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
                                <label for="commission_type" class="form-label">Commission Type</label>
                                <select class="form-select @error('commission_type') is-invalid @enderror" 
                                        id="commission_type" name="commission_type">
                                    <option value="">Select Commission Type</option>
                                    <option value="percentage" {{ old('commission_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    <option value="fixed" {{ old('commission_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                    <option value="disabled" {{ old('commission_type') == 'disabled' ? 'selected' : '' }}>Disabled</option>
                                </select>
                                @error('commission_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Choose how commission is calculated for this subcategory</div>
                            </div>

                            <div class="mb-3" id="commission_rate_container" style="display: none;">
                                <label for="commission_rate" class="form-label">Commission Rate</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('commission_rate') is-invalid @enderror" 
                                           id="commission_rate" name="commission_rate" value="{{ old('commission_rate') }}" 
                                           min="0" max="100" step="0.01" placeholder="0.00">
                                    <span class="input-group-text" id="commission_unit">%</span>
                                </div>
                                @error('commission_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text" id="commission_help">Commission percentage (0-100%)</div>
                            </div>

                            <div class="alert alert-info" id="commission_preview" style="display: none;">
                                <strong>Commission Preview:</strong>
                                <div id="commission_preview_text"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings & Media -->
                <div class="col-xl-4">
                    <!-- Subcategory Images -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Subcategory Images</div>
                            <small class="text-muted">Upload high-quality subcategory images. First image will be the main subcategory image.</small>
                        </div>
                        <div class="card-body">
                            <div class="image-upload-container" id="image-upload-area">
                                <div class="upload-content">
                                    <i class="ti ti-cloud-upload" style="font-size: 48px; color: #6c757d; margin-bottom: 15px;"></i>
                                    <h5>Drop image here or click to upload</h5>
                                    <p class="text-muted">Supports: JPG, PNG, GIF, WEBP (Max: 10MB)</p>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button type="button" class="btn btn-primary" onclick="document.getElementById('image-input').click()">
                                            <i class="ti ti-upload"></i> Choose File
                                        </button>
                                        <button type="button" class="btn btn-info" onclick="openResizeOptions()">
                                            <i class="ti ti-crop"></i> Resize Options
                                        </button>
                                        <a href="{{ route('admin.image-upload.demo') }}" target="_blank" class="btn btn-success">
                                            <i class="ti ti-external-link"></i> Image Demo
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="file" id="image-input" name="image" accept="image/*" style="display: none;">
                            
                            <!-- Resize Options Panel -->
                            <div id="resize-options-panel" class="mt-3" style="display: none;">
                                <div class="alert alert-info">
                                    <h6><i class="ti ti-info-circle me-2"></i>Image Resize Options</h6>
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <label class="form-label">Width (px)</label>
                                            <input type="number" class="form-control form-control-sm" id="resize-width" value="800" min="50" max="2000">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Height (px)</label>
                                            <input type="number" class="form-control form-control-sm" id="resize-height" value="600" min="50" max="2000">
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
                            
                            @error('images.*')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Subcategory Settings -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Subcategory Settings</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" 
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Featured Subcategory
                                </label>
                                <div class="form-text">Featured subcategories appear in special sections</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="show_in_menu" name="show_in_menu" value="1" 
                                       {{ old('show_in_menu', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_in_menu">
                                    Show in Navigation Menu
                                </label>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="show_in_footer" name="show_in_footer" value="1" 
                                       {{ old('show_in_footer') ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_in_footer">
                                    Show in Footer Links
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Subcategory Statistics (Mock Data) -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Subcategory Info</div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Products:</span>
                                <span class="fw-semibold">0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Parent Category:</span>
                                <span class="fw-semibold">None Selected</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Created:</span>
                                <span class="fw-semibold">{{ date('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-body d-flex justify-content-between">
                            <a href="{{ route('admin.subcategories.index') }}" class="btn btn-light">
                                <i class="ri-arrow-left-line me-1"></i> Back to Subcategories
                            </a>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="ri-refresh-line me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i> Create Subcategory
                                </button>
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
<script src="{{ asset('admin-assets/js/ajax-manager.js') }}"></script>
<script>
class SubcategoryCreateManager {
    constructor() {
        this.form = document.querySelector('form');
        this.nameField = document.getElementById('name');
        this.slugField = document.getElementById('slug');
        this.categoryField = document.getElementById('category_id');
        this.imageField = document.getElementById('image-input');
        this.previewContainer = document.getElementById('image-preview-grid');
        
        // Image upload variables
        this.selectedImages = [];
        
        // Track input state to prevent unnecessary notifications
        this.lastInputValue = '';
        this.isInputting = false;
        this.isManuallyEdited = false;
        
        this.initializeComponents(); 
        this.setupEventListeners();
        this.loadParentCategories();
        this.setupValidation();
        this.setupImageUpload();
        this.setupCommissionSettings();
    }

    initializeComponents() {
        // Setup character counters
        this.setupCharacterCounter('meta_title', 60);
        this.setupCharacterCounter('meta_description', 160);
        
        // Setup slug validation
        this.setupSlugValidation();
        
        // Initialize form validation
        this.initializeFormValidation();
    }

    setupEventListeners() {
        // Auto-generate slug from name with enhanced validation
        this.nameField.addEventListener('input', this.debounce((e) => {
            const name = e.target.value.trim();
            console.log('Name input changed:', name);
            
            // Skip if user is just inputting (prevent spam)
            if (this.isInputting) {
                return;
            }
            
            this.isInputting = true;
            setTimeout(() => { this.isInputting = false; }, 1000);
            
            // Store current value for reload detection
            this.lastInputValue = name;
            
            if (name && !this.isManuallyEdited) {
                const slug = this.generateSlug(name);
                console.log('Generated slug:', slug);
                this.slugField.value = slug;
                this.slugField.dispatchEvent(new Event('input'));
                
                // Visual feedback for slug generation
                this.slugField.style.borderColor = '#28a745';
                setTimeout(() => {
                    this.slugField.style.borderColor = '';
                }, 1000);
            }
            
            // Enhanced feedback and validation
            if (name.length >= 3) {
                this.validateSlug(this.slugField.value);
                this.analyzeKeywords(name);
                this.checkReloadStatus(name);
                this.addFieldVisualFeedback(name, this.countKeywords(name));
            } else {
                this.removeFieldVisualFeedback();
            }
        }, 600)); // Increased debounce for better UX

        // Track manual slug editing
        this.slugField.addEventListener('input', (e) => {
            console.log('Slug manually edited');
            this.isManuallyEdited = true;
            this.slugField.dataset.manuallyEdited = 'true';
        });

        // Image preview with validation
        this.imageField.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                this.handleImageFile(e.target.files[0]); // Handle single file upload
            }
        });

        // Form submission with AJAX and SweetAlert
        this.form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Check if AJAX is available, otherwise use normal submission
            if (typeof window.ajax === 'undefined') {
                console.log('AJAX not available, using normal form submission');
                Swal.fire({
                    title: 'Submitting...',
                    text: 'Creating subcategory using fallback method',
                    icon: 'info',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    // Remove event listener and submit normally
                    this.form.removeEventListener('submit', arguments.callee);
                    this.form.submit();
                });
                return;
            }
            
            await this.handleFormSubmission();
        });

        // Parent category change
        this.categoryField.addEventListener('change', (e) => {
            this.validateCategorySelection(e.target.value);
        });

        // Reload categories button
        const reloadButton = document.getElementById('reload-categories');
        if (reloadButton) {
            reloadButton.addEventListener('click', () => {
                console.log('Reload button clicked');
                Swal.fire({
                    title: 'Reloading Categories',
                    text: 'Manually reloading parent categories...',
                    icon: 'info',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
                this.loadParentCategories();
            });
        }
    }

    async loadParentCategories() {
        try {
            // Check if AJAX is available
            if (typeof window.ajax === 'undefined') {
                console.log('AJAX not available - skipping parent categories load');
                this.showNoDataMessage();
                return;
            }
            
            // Show loading state in the dropdown
            this.setLoadingState(true);
            
            // Show loading toast
            const loadingToast = Swal.fire({
                title: 'Loading Categories...',
                text: 'Fetching parent categories from database',
                icon: 'info',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            // Use correct admin route for categories
            console.log('Making AJAX call to:', '/admin/categories/api/parent-categories');
            const response = await ajax.get('/admin/categories/api/parent-categories');
            console.log('Raw response:', response);
            
            if (response.success) {
                // Handle nested response structure from AJAX manager
                const categories = response.data?.data?.categories || response.data?.categories || response.data || [];
                console.log('Extracted categories:', categories);
                
                if (Array.isArray(categories) && categories.length > 0) {
                    this.populateParentOptions(categories);
                    
                    Swal.fire({
                        title: 'Categories Loaded!',
                        text: `${categories.length} parent categories loaded successfully`,
                        icon: 'success',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                } else {
                    console.warn('No categories found or invalid data structure');
                    this.showNoDataMessage();
                }
            } else {
                console.error('Response not successful:', response);
                throw new Error('Failed to load categories');
            }
        } catch (error) {
            console.error('Failed to load parent categories:', error);
            this.handleLoadingError();
        } finally {
            this.setLoadingState(false);
        }
    }

    setLoadingState(isLoading) {
        const helpText = this.categoryField.parentNode.querySelector('.form-text small');
        
        if (isLoading) {
            this.categoryField.disabled = true;
            if (helpText) {
                helpText.innerHTML = '<i class="ri-loader-4-line"></i> Loading categories...';
                helpText.className = 'text-info';
            }
        } else {
            this.categoryField.disabled = false;
            if (helpText) {
                helpText.innerHTML = '<i class="ri-information-line"></i> Select a parent category for this subcategory';
                helpText.className = 'text-muted';
            }
        }
    }

    populateParentOptions(categories) {
        // Ensure categories is an array
        if (!Array.isArray(categories)) {
            console.warn('Categories data is not an array:', categories);
            return;
        }

        // Store old value if exists (for form validation errors)
        const oldValue = '{{ old("category_id") }}';

        // Clear existing options except the first one
        const firstOption = this.categoryField.querySelector('option[value=""]');
        this.categoryField.innerHTML = '';
        if (firstOption) {
            this.categoryField.appendChild(firstOption);
        } else {
            // Create default option if it doesn't exist
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Select Parent Category';
            this.categoryField.appendChild(defaultOption);
        }

        // Add categories
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            option.dataset.slug = category.slug || '';
            
            // Mark as selected if it matches old value
            if (oldValue && oldValue == category.id) {
                option.selected = true;
            }
            
            this.categoryField.appendChild(option);
        });

        // Update help text with count
        const helpText = this.categoryField.parentNode.querySelector('.form-text small');
        if (helpText) {
            helpText.innerHTML = `<i class="ri-check-line"></i> ${categories.length} categories available`;
            helpText.className = 'text-success';
        }
    }

    showNoDataMessage() {
        // Show message when no categories are available
        const option = document.createElement('option');
        option.disabled = true;
        option.textContent = 'No parent categories available';
        this.categoryField.appendChild(option);
        
        const helpText = this.categoryField.parentNode.querySelector('.form-text small');
        if (helpText) {
            helpText.innerHTML = '<i class="ri-information-line"></i> No categories found. Please create categories first.';
            helpText.className = 'text-warning';
        }
        
        Swal.fire({
            title: 'No Categories Found',
            text: 'No parent categories available. Please create categories first.',
            icon: 'warning',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    }

    handleLoadingError() {
        // Show error message and provide fallback
        this.categoryField.innerHTML = '<option value="">Select Parent Category</option>';
        
        const helpText = this.categoryField.parentNode.querySelector('.form-text small');
        if (helpText) {
            helpText.innerHTML = '<i class="ri-error-warning-line"></i> Failed to load categories. Using offline mode.';
            helpText.className = 'text-danger';
        }
        
        Swal.fire({
            title: 'Loading Failed',
            text: 'Could not load parent categories. Please refresh the page.',
            icon: 'error',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        });
    }

    generateSlug(text) {
        return text.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    async validateSlug(slug) {
        if (!slug) return;
        
        // Check if AJAX is available
        if (typeof window.ajax === 'undefined') {
            console.log('AJAX not available - skipping slug validation');
            return;
        }
        
        try {
            const response = await ajax.get('/admin/subcategories/validate-slug', { slug });
            const slugField = this.slugField;
            
            if (response.success) {
                // Handle nested response structure from AJAX manager
                const validationData = response.data?.data || response.data;
                if (validationData.available) {
                    this.setFieldStatus(slugField, 'valid', 'Slug is available');
                } else {
                    this.setFieldStatus(slugField, 'invalid', 'Slug already exists');
                }
            }
        } catch (error) {
            console.error('Slug validation error:', error);
        }
    }

    validateCategorySelection(categoryId) {
        if (categoryId) {
            // Additional validation logic for category selection
            Swal.fire({
                title: 'Category Selected',
                text: 'Parent category selected successfully',
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });
        }
    }

    countKeywords(text) {
        return text.split(/\s+/).filter(word => word.length > 2).length;
    }

    analyzeKeywords(name) {
        const keywords = this.countKeywords(name);
        console.log(`Keyword analysis: ${keywords} keywords found in "${name}"`);
    }

    checkReloadStatus(name) {
        // Check if user is potentially reloading or retyping
        const lastInput = localStorage.getItem('subcategory_last_input');
        const currentTime = Date.now();
        const lastTime = localStorage.getItem('subcategory_last_time') || 0;
        
        // Store current input and time
        localStorage.setItem('subcategory_last_input', name);
        localStorage.setItem('subcategory_last_time', currentTime);
        
        // Check if similar input was entered recently (within 30 seconds)
        if (lastInput && name === lastInput && (currentTime - lastTime) < 30000) {
            console.log('Reload detected for subcategory');
        }
    }

    addFieldVisualFeedback(name, keywordCount) {
        // Throttle visual feedback to prevent flickering during fast typing
        if (this.feedbackTimeout) {
            clearTimeout(this.feedbackTimeout);
        }
        
        this.feedbackTimeout = setTimeout(() => {
            // Remove existing feedback
            this.removeFieldVisualFeedback();
            
            // Add visual highlighting based on keyword count
            if (keywordCount >= 3) {
                this.nameField.classList.add('keyword-highlight');
                this.addFieldFeedbackMessage('success', `✓ Excellent! ${keywordCount} keywords detected`);
            } else if (keywordCount >= 2) {
                this.nameField.classList.add('keyword-highlight');
                this.addFieldFeedbackMessage('success', `✓ Good! ${keywordCount} keywords detected`);
            } else if (keywordCount === 1) {
                this.addFieldFeedbackMessage('warning', `⚠ Consider adding more keywords (currently ${keywordCount})`);
            }
        }, 50);
    }

    removeFieldVisualFeedback() {
        // Clear feedback timeout if pending
        if (this.feedbackTimeout) {
            clearTimeout(this.feedbackTimeout);
        }
        
        // Remove keyword highlight classes
        this.nameField.classList.remove('keyword-highlight');
        
        // Remove existing feedback messages
        const existingFeedback = this.nameField.parentNode.querySelectorAll('.field-feedback');
        existingFeedback.forEach(el => el.remove());
    }

    addFieldFeedbackMessage(type, message) {
        const feedbackEl = document.createElement('div');
        feedbackEl.className = `field-feedback ${type}`;
        feedbackEl.textContent = message;
        
        this.nameField.parentNode.appendChild(feedbackEl);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (feedbackEl.parentNode) {
                feedbackEl.remove();
            }
        }, 5000);
    }

    setupCharacterCounter(fieldId, maxLength) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        const counterDiv = document.createElement('div');
        counterDiv.className = 'character-counter';
        field.parentNode.appendChild(counterDiv);

        field.addEventListener('input', () => {
            const length = field.value.length;
            counterDiv.textContent = `${length}/${maxLength}`;
            
            counterDiv.className = 'character-counter';
            if (length > maxLength * 0.9) {
                counterDiv.classList.add('warning');
            }
            if (length > maxLength) {
                counterDiv.classList.add('error');
            }
        });
    }

    setupSlugValidation() {
        // Initial setup for slug field
    }

    initializeFormValidation() {
        // Setup form validation
    }

    setupValidation() {
        // Additional validation setup
    }

    setupImageUpload() {
        // Image upload functionality
        this.setupDragAndDrop();
    }

    setupDragAndDrop() {
        const uploadArea = document.getElementById('image-upload-area');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, this.preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.classList.remove('dragover');
            }, false);
        });

        uploadArea.addEventListener('drop', this.handleDrop.bind(this), false);
    }

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            this.handleImageFile(files[0]);
        }
    }

    handleImageFile(file) {
        if (!this.validateImageFile(file)) {
            return;
        }

        // Update the file input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        this.imageField.files = dataTransfer.files;

        // Create preview
        this.createImagePreview(file);
    }

    validateImageFile(file) {
        // Check file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                title: 'Invalid File Type',
                text: 'Please select a valid image file (JPG, PNG, GIF, WEBP)',
                icon: 'error'
            });
            return false;
        }

        // Check file size (10MB max)
        if (file.size > 10 * 1024 * 1024) {
            Swal.fire({
                title: 'File Too Large',
                text: 'Image must be less than 10MB',
                icon: 'error'
            });
            return false;
        }

        return true;
    }

    createImagePreview(file) {
        const reader = new FileReader();
        
        reader.onload = (e) => {
            const previewHtml = `
                <div class="image-preview-item">
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="image-remove-btn" onclick="subcategoryManager.removeImage()">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            `;
            
            this.previewContainer.innerHTML = previewHtml;
        };
        
        reader.readAsDataURL(file);
    }

    removeImage() {
        this.previewContainer.innerHTML = '';
        this.imageField.value = '';
    }

    setFieldStatus(field, status, message) {
        // Remove existing status classes
        field.classList.remove('is-valid', 'is-invalid');
        
        // Add new status
        if (status === 'valid') {
            field.classList.add('is-valid');
        } else if (status === 'invalid') {
            field.classList.add('is-invalid');
        }
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    async handleFormSubmission() {
        try {
            const formData = new FormData(this.form);
            
            Swal.fire({
                title: 'Creating Subcategory...',
                text: 'Please wait while we create your subcategory',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const response = await ajax.post(this.form.action, formData);
            
            if (response.success) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Subcategory created successfully',
                    icon: 'success',
                    confirmButtonText: 'Continue'
                }).then(() => {
                    window.location.href = response.data.redirect || '{{ route("admin.subcategories.index") }}';
                });
            } else {
                throw new Error(response.message || 'Failed to create subcategory');
            }
        } catch (error) {
            console.error('Form submission error:', error);
            Swal.fire({
                title: 'Error!',
                text: error.message || 'Failed to create subcategory',
                icon: 'error',
                confirmButtonText: 'Try Again'
            });
        }
    }

    setupCommissionSettings() {
        const commissionType = document.getElementById('commission_type');
        const commissionRate = document.getElementById('commission_rate');
        const rateContainer = document.getElementById('commission_rate_container');
        const commissionUnit = document.getElementById('commission_unit');
        const commissionHelp = document.getElementById('commission_help');
        const commissionPreview = document.getElementById('commission_preview');

        if (!commissionType || !commissionRate || !rateContainer) {
            console.error('Commission elements not found');
            return;
        }

        commissionType.addEventListener('change', (e) => {
            this.handleCommissionTypeChange(e.target.value);
        });

        commissionRate.addEventListener('input', (e) => {
            this.updateCommissionPreview();
        });

        // Initialize on page load
        if (commissionType.value) {
            this.handleCommissionTypeChange(commissionType.value);
        }
    }

    handleCommissionTypeChange(commissionType) {
        const rateContainer = document.getElementById('commission_rate_container');
        const commissionUnit = document.getElementById('commission_unit');
        const commissionHelp = document.getElementById('commission_help');
        const commissionRate = document.getElementById('commission_rate');

        if (commissionType === 'disabled' || commissionType === '') {
            rateContainer.style.display = 'none';
            commissionRate.value = '';
        } else {
            rateContainer.style.display = 'block';
            
            if (commissionType === 'percentage') {
                commissionUnit.textContent = '%';
                commissionHelp.textContent = 'Commission percentage (0-100%)';
                commissionRate.max = '100';
                commissionRate.placeholder = '0.00';
            } else if (commissionType === 'fixed') {
                commissionUnit.textContent = '৳';
                commissionHelp.textContent = 'Fixed commission amount in BDT';
                commissionRate.max = '999999';
                commissionRate.placeholder = '0.00';
            }
        }

        this.updateCommissionPreview();
    }

    updateCommissionPreview() {
        const commissionType = document.getElementById('commission_type').value;
        const commissionRate = document.getElementById('commission_rate').value;
        const commissionPreview = document.getElementById('commission_preview');
        const commissionPreviewText = document.getElementById('commission_preview_text');

        if (!commissionType || commissionType === 'disabled' || !commissionRate) {
            commissionPreview.style.display = 'none';
            return;
        }

        const rate = parseFloat(commissionRate);
        if (isNaN(rate) || rate <= 0) {
            commissionPreview.style.display = 'none';
            return;
        }

        let previewText = '';
        const samplePrice = 1000; // Sample product price for preview

        if (commissionType === 'percentage') {
            const commissionAmount = (samplePrice * rate) / 100;
            previewText = `For a product priced at ৳${samplePrice.toLocaleString()}, affiliate commission will be ৳${commissionAmount.toLocaleString()} (${rate}%)`;
        } else if (commissionType === 'fixed') {
            previewText = `Affiliates will earn a fixed commission of ৳${rate.toLocaleString()} per sale`;
        }

        commissionPreviewText.textContent = previewText;
        commissionPreview.style.display = 'block';
    }
}

// Global functions for onclick handlers
function resetForm() {
    if (confirm('Are you sure you want to reset the form?')) {
        document.querySelector('form').reset();
        document.getElementById('image-preview-grid').innerHTML = '';
    }
}

function openResizeOptions() {
    const panel = document.getElementById('resize-options-panel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}

function resizeUploadedImages() {
    console.log('Resize images functionality');
}

function generateThumbnails() {
    console.log('Generate thumbnails functionality');
}

function optimizeImages() {
    console.log('Optimize images functionality');
}

// Commission standalone handlers (fallback)
function handleCommissionTypeChange(commissionType) {
    const rateContainer = document.getElementById('commission_rate_container');
    const commissionUnit = document.getElementById('commission_unit');
    const commissionHelp = document.getElementById('commission_help');
    const commissionRate = document.getElementById('commission_rate');

    if (!rateContainer || !commissionUnit || !commissionHelp || !commissionRate) return;

    if (commissionType === 'disabled' || commissionType === '') {
        rateContainer.style.display = 'none';
        commissionRate.value = '';
    } else {
        rateContainer.style.display = 'block';
        
        if (commissionType === 'percentage') {
            commissionUnit.textContent = '%';
            commissionHelp.textContent = 'Commission percentage (0-100%)';
            commissionRate.max = '100';
            commissionRate.placeholder = '0.00';
        } else if (commissionType === 'fixed') {
            commissionUnit.textContent = '৳';
            commissionHelp.textContent = 'Fixed commission amount in BDT';
            commissionRate.max = '999999';
            commissionRate.placeholder = '0.00';
        }
    }

    updateCommissionPreview();
}

function updateCommissionPreview() {
    const commissionType = document.getElementById('commission_type')?.value;
    const commissionRate = document.getElementById('commission_rate')?.value;
    const commissionPreview = document.getElementById('commission_preview');
    const commissionPreviewText = document.getElementById('commission_preview_text');

    if (!commissionPreview || !commissionPreviewText) return;

    if (!commissionType || commissionType === 'disabled' || !commissionRate) {
        commissionPreview.style.display = 'none';
        return;
    }

    const rate = parseFloat(commissionRate);
    if (isNaN(rate) || rate <= 0) {
        commissionPreview.style.display = 'none';
        return;
    }

    let previewText = '';
    const samplePrice = 1000;

    if (commissionType === 'percentage') {
        const commissionAmount = (samplePrice * rate) / 100;
        previewText = `For a product priced at ৳${samplePrice.toLocaleString()}, affiliate commission will be ৳${commissionAmount.toLocaleString()} (${rate}%)`;
    } else if (commissionType === 'fixed') {
        previewText = `Affiliates will earn a fixed commission of ৳${rate.toLocaleString()} per sale`;
    }

    commissionPreviewText.textContent = previewText;
    commissionPreview.style.display = 'block';
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.subcategoryManager = new SubcategoryCreateManager();
    
    // Standalone commission event listeners (fallback)
    const commissionType = document.getElementById('commission_type');
    const commissionRate = document.getElementById('commission_rate');
    
    if (commissionType) {
        commissionType.addEventListener('change', (e) => {
            handleCommissionTypeChange(e.target.value);
        });
        
        // Initialize on page load
        if (commissionType.value) {
            handleCommissionTypeChange(commissionType.value);
        }
    }
    
    if (commissionRate) {
        commissionRate.addEventListener('input', updateCommissionPreview);
    }
});
</script>
@endpush
