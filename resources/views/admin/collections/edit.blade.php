@extends('admin.layouts.app')

@section('title', 'Edit Collection')

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
    }

    .image-remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .image-remove-btn:hover {
        background: #dc3545;
        transform: scale(1.1);
    }

    .current-image {
        max-width: 200px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    /* Form Sections */
    .form-section {
        background: #ffffff;
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .form-section-header {
        background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        border-bottom: 1px solid #e3e6f0;
        font-weight: 600;
        font-size: 16px;
    }

    .form-section-body {
        padding: 20px;
    }

    /* Slug Preview */
    .slug-preview {
        background: #e3f2fd;
        border: 1px solid #90caf9;
        border-radius: 4px;
        padding: 8px 12px;
        font-family: 'Courier New', monospace;
        color: #1565c0;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .slug-preview.updated {
        background: #e8f5e8;
        border-color: #4caf50;
        color: #2e7d32;
        animation: highlightSlug 0.5s ease;
    }

    @keyframes highlightSlug {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }

    /* Auto-generation badge */
    #autoGenBadge {
        animation: pulse 2s infinite;
        font-size: 0.7rem;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }

    /* Enhanced form controls */
    .form-control:focus,
    .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .character-counter {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    .character-counter.warning {
        color: #ffc107;
    }

    .character-counter.danger {
        color: #dc3545;
    }

    /* Validation styling */
    .is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #dc3545;
    }

    .valid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #28a745;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Edit Collection</h1>
        <div class="ms-md-1 ms-0">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.collections.index') }}">Collections</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.collections.show', $collection) }}">{{ $collection->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Form -->
    <form action="{{ route('admin.collections.update', $collection) }}" method="POST" enctype="multipart/form-data" id="collectionForm">
        @csrf
        @method('PUT')
        
        <!-- Basic Information Section -->
        <div class="form-section" id="section-basic">
            <div class="form-section-header">
                <i class="bx bx-info-circle me-2"></i>Basic Information
            </div>
            <div class="form-section-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Collection Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $collection->name) }}" 
                                   placeholder="e.g., Summer Collection 2024"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="character-counter" id="nameCounter">0/255 characters</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug" class="form-label">
                                Slug <span class="text-danger">*</span>
                                <span class="badge bg-info ms-2" id="autoGenBadge">
                                    <i class="bx bx-magic-wand me-1"></i>Auto-Generated
                                </span>
                            </label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control @error('slug') is-invalid @enderror" 
                                       id="slug" 
                                       name="slug" 
                                       value="{{ old('slug', $collection->slug) }}" 
                                       placeholder="Will be auto-generated from collection name..."
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="generateSlugBtn" title="Generate from name">
                                    <i class="bx bx-magic-wand"></i>
                                </button>
                                <button class="btn btn-outline-info" type="button" id="resetAutoBtn" title="Reset to auto-generation">
                                    <i class="bx bx-refresh"></i>
                                </button>
                            </div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <strong>Preview URL:</strong>
                                <div class="slug-preview" id="slugPreview">{{ url('/collections/') }}/{{ $collection->slug }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Enter collection description...">{{ old('description', $collection->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="character-counter" id="descriptionCounter">0/1000 characters</div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" 
                                   class="form-control @error('sort_order') is-invalid @enderror" 
                                   id="sort_order" 
                                   name="sort_order" 
                                   value="{{ old('sort_order', $collection->sort_order) }}" 
                                   min="0"
                                   placeholder="0">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Lower numbers appear first</small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Status *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="active" {{ old('status', $collection->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $collection->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="draft" {{ old('status', $collection->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                            <small class="text-muted">Current collection status</small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Visibility</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $collection->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                            <small class="text-muted">Only active collections will be visible</small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Featured Collection</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_featured" 
                                       name="is_featured" 
                                       value="1" 
                                       {{ old('is_featured', $collection->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Featured
                                </label>
                            </div>
                            <small class="text-muted">Show in featured collections section</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Images Section -->
        <div class="form-section" id="section-images">
            <div class="form-section-header">
                <i class="bx bx-image me-2"></i>Collection Images
            </div>
            <div class="form-section-body">
                <!-- Current Image Display -->
                @if($collection->image)
                    <div class="mb-4">
                        <label class="form-label">Current Image</label>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $collection->image_url }}" alt="{{ $collection->name }}" class="current-image">
                            <div>
                                <p class="mb-1"><strong>Current:</strong> {{ basename($collection->image) }}</p>
                                <p class="text-muted mb-0">Upload a new image to replace the current one</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Collection Image Upload -->
                <div class="mb-4">
                    <label class="form-label">
                        Collection Image 
                        @if(!$collection->image)
                            <span class="text-danger">*</span>
                        @endif
                    </label>
                    <small class="text-muted d-block mb-2">Upload high-quality collection images. The image will be automatically resized to multiple sizes.</small>
                    
                    <div class="image-upload-container" id="image-upload-area">
                        <div class="upload-content">
                            <i class="bx bx-cloud-upload fs-1 text-primary mb-3"></i>
                            <h5>Drop image here or click to upload</h5>
                            <p class="text-muted">Supports: JPG, PNG, GIF, WEBP (Max: 10MB)</p>
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-primary" onclick="document.getElementById('image-input').click()">
                                    <i class="bx bx-upload"></i> Choose File
                                </button>
                                <button type="button" class="btn btn-info" onclick="openResizeOptions()">
                                    <i class="bx bx-crop"></i> Resize Options
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <input type="file" id="image-input" name="image" accept="image/*" style="position: absolute; left: -9999px; opacity: 0;">
                    
                    <!-- Resize Options Panel -->
                    <div id="resize-options-panel" class="mt-3" style="display: none;">
                        <div class="alert alert-info">
                            <h6><i class="bx bx-info-circle me-2"></i>Image Resize Options</h6>
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
                                    <i class="bx bx-crop me-1"></i>Resize Images
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary" onclick="generateThumbnails()">
                                    <i class="bx bx-image me-1"></i>Generate Thumbnails
                                </button>
                                <button type="button" class="btn btn-sm btn-warning" onclick="optimizeImages()">
                                    <i class="bx bx-zap me-1"></i>Optimize Images
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Image Preview Grid -->
                    <div id="image-preview-grid" class="image-preview-grid"></div>
                    
                    @error('image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- SEO & Meta Information Section -->
        <div class="form-section" id="section-seo">
            <div class="form-section-header">
                <i class="bx bx-search-alt me-2"></i>SEO & Meta Information
            </div>
            <div class="form-section-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text" 
                                   class="form-control @error('meta_title') is-invalid @enderror" 
                                   id="meta_title" 
                                   name="meta_title" 
                                   value="{{ old('meta_title', $collection->meta_title) }}" 
                                   placeholder="SEO title for this collection"
                                   maxlength="60">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="character-counter" id="metaTitleCounter">0/60 characters</div>
                            <small class="text-muted">Leave empty to use collection name</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                            <input type="text" 
                                   class="form-control @error('meta_keywords') is-invalid @enderror" 
                                   id="meta_keywords" 
                                   name="meta_keywords" 
                                   value="{{ old('meta_keywords', $collection->meta_keywords) }}" 
                                   placeholder="keyword1, keyword2, keyword3">
                            @error('meta_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Comma-separated keywords for SEO</small>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                      id="meta_description" 
                                      name="meta_description" 
                                      rows="3" 
                                      placeholder="SEO description for this collection"
                                      maxlength="160">{{ old('meta_description', $collection->meta_description) }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="character-counter" id="metaDescCounter">0/160 characters</div>
                            <small class="text-muted">This will appear in search results</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="card">
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            <i class="bx bx-info-circle me-1"></i>
                            Fields marked with <span class="text-danger">*</span> are required
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.collections.show', $collection) }}" class="btn btn-secondary">
                            <i class="bx bx-x me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bx bx-save me-1"></i>Update Collection
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
class CollectionEditManager {
    constructor() {
        this.selectedImages = [];
        this.previewContainer = document.getElementById('image-preview-grid');
        this.init();
    }

    init() {
        this.setupImageUpload();
        this.setupSlugGeneration();
        this.setupCharacterCounters();
        this.setupFormValidation();
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
                alert('File is too large. Maximum size is 10MB.');
            }
        } else {
            alert('Invalid file type. Please select an image.');
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
                <img src="${e.target.result}" alt="Collection Image Preview">
                <button type="button" class="image-remove-btn" onclick="collectionEditManager.removeImage(0)">
                    <i class="bx bx-x"></i>
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
    }

    setupSlugGeneration() {
        let isSlugManuallyEdited = false;
        let debounceTimer;

        $('#name').on('input', function() {
            const name = $(this).val();
            
            if (debounceTimer) {
                clearTimeout(debounceTimer);
            }
            
            if (!isSlugManuallyEdited && name.trim()) {
                debounceTimer = setTimeout(() => {
                    collectionEditManager.generateSlug(name);
                }, 300);
            }
        });

        $('#slug').on('input', function() {
            isSlugManuallyEdited = true;
            $('#autoGenBadge').html('<i class="bx bx-edit me-1"></i>Manual').removeClass('bg-info').addClass('bg-warning');
            collectionEditManager.updateSlugPreview($(this).val());
        });

        $('#generateSlugBtn').on('click', function(e) {
            e.preventDefault();
            const name = $('#name').val();
            
            if (!name.trim()) {
                alert('Please enter a collection name first');
                return;
            }
            
            isSlugManuallyEdited = false;
            collectionEditManager.generateSlug(name);
        });

        $('#resetAutoBtn').on('click', function(e) {
            e.preventDefault();
            isSlugManuallyEdited = false;
            $('#autoGenBadge').html('<i class="bx bx-magic-wand me-1"></i>Auto-Generated').removeClass('bg-warning').addClass('bg-info');
            
            const name = $('#name').val();
            if (name.trim()) {
                collectionEditManager.generateSlug(name);
            } else {
                $('#slug').val('');
                collectionEditManager.updateSlugPreview('');
            }
        });
    }

    generateSlug(name) {
        if (!name || typeof name !== 'string') {
            return;
        }

        const slug = name.toLowerCase()
                         .trim()
                         .replace(/[^a-z0-9\s-]/g, '')
                         .replace(/\s+/g, '-')
                         .replace(/-+/g, '-')
                         .replace(/^-+|-+$/g, '');

        $('#slug').val(slug);
        this.updateSlugPreview(slug);
        
        $('#autoGenBadge').html('<i class="bx bx-check me-1"></i>Generated').removeClass('bg-info').addClass('bg-success');
        
        setTimeout(() => {
            $('#autoGenBadge').html('<i class="bx bx-magic-wand me-1"></i>Auto-Generated').removeClass('bg-success').addClass('bg-info');
        }, 1500);
    }

    updateSlugPreview(slug) {
        const baseUrl = '{{ url("/collections") }}';
        const previewSlug = slug || 'your-collection-slug';
        const fullUrl = `${baseUrl}/${previewSlug}`;
        
        $('#slugPreview').addClass('updated').text(fullUrl);
        
        setTimeout(() => {
            $('#slugPreview').removeClass('updated');
        }, 1000);
    }

    setupCharacterCounters() {
        const fields = [
            { input: '#name', counter: '#nameCounter', max: 255 },
            { input: '#description', counter: '#descriptionCounter', max: 1000 },
            { input: '#meta_title', counter: '#metaTitleCounter', max: 60 },
            { input: '#meta_description', counter: '#metaDescCounter', max: 160 }
        ];

        fields.forEach(field => {
            $(field.input).on('input', function() {
                const length = $(this).val().length;
                const counter = $(field.counter);
                
                counter.text(`${length}/${field.max} characters`);
                
                if (length > field.max * 0.9) {
                    counter.addClass('danger').removeClass('warning');
                } else if (length > field.max * 0.7) {
                    counter.addClass('warning').removeClass('danger');
                } else {
                    counter.removeClass('warning danger');
                }
            });
            
            // Initialize counter
            $(field.input).trigger('input');
        });
    }

    setupFormValidation() {
        $('#collectionForm').on('submit', function(e) {
            const submitBtn = $('#submitBtn');
            const originalText = submitBtn.html();
            
            // Show loading state
            submitBtn.html('<i class="bx bx-loader-alt bx-spin me-1"></i>Updating...').prop('disabled', true);
            
            // Validate required fields
            let isValid = true;
            
            if (!$('#name').val().trim()) {
                $('#name').addClass('is-invalid');
                isValid = false;
            }
            
            if (!$('#slug').val().trim()) {
                $('#slug').addClass('is-invalid');
                isValid = false;
            }
            
            if (!$('#status').val()) {
                $('#status').addClass('is-invalid');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    }
}

// Global functions for button clicks
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
        alert('Please select images first');
        return;
    }
    
    const width = document.getElementById('resize-width').value;
    const height = document.getElementById('resize-height').value;
    const quality = document.getElementById('resize-quality').value;
    const maintainRatio = document.getElementById('maintain-ratio').checked;
    
    console.log(`Resizing to ${width}x${height} with ${quality}% quality, maintain ratio: ${maintainRatio}`);
    alert('Image resize functionality will be processed on the server side.');
}

function generateThumbnails() {
    const fileInput = document.getElementById('image-input');
    if (fileInput.files.length === 0) {
        alert('Please select images first');
        return;
    }
    
    console.log('Generating thumbnails...');
    alert('Thumbnails will be generated automatically on the server side.');
}

function optimizeImages() {
    const fileInput = document.getElementById('image-input');
    if (fileInput.files.length === 0) {
        alert('Please select images first');
        return;
    }
    
    console.log('Optimizing images...');
    alert('Images will be optimized automatically on the server side.');
}

// Initialize collection edit manager when document is ready
$(document).ready(function() {
    window.collectionEditManager = new CollectionEditManager();
});
</script>
@endpush
