@extends('admin.layouts.app')

@section('title', 'Create Subcategory')

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
        transition: all 0.3s ease;
    }

    .image-remove-btn:hover {
        background: #dc3545;
        transform: scale(1.1);
    }

    .upload-content h5, .upload-content h6 {
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

    .form-text.text-end {
        font-size: 0.875rem;
    }

    .text-danger {
        color: #dc3545 !important;
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
                                    <label for="category_id" class="form-label">Parent Category <span class="text-danger">*</span></label>
                                    <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                        <option value="">Select Parent Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category['id'] }}" {{ old('category_id') == $category['id'] ? 'selected' : '' }}>
                                                {{ $category['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Subcategory Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" placeholder="Enter subcategory name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                       id="slug" name="slug" value="{{ old('slug') }}" placeholder="Auto-generated from name">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="ri-information-line me-1"></i>
                                    Leave empty to auto-generate from subcategory name. 
                                    <span id="slug-status" class="text-success" style="display: none;">
                                        <i class="ri-check-line"></i> Auto-generating...
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="short_description" class="form-label">Short Description</label>
                                <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                          id="short_description" name="short_description" rows="2" 
                                          placeholder="Brief description for listing...">{{ old('short_description') }}</textarea>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" 
                                          placeholder="Detailed description of the subcategory...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="icon" class="form-label">Icon Class</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                               id="icon" name="icon" value="{{ old('icon') }}" placeholder="e.g. ri-tshirt-line">
                                        <span class="input-group-text">
                                            <i id="iconPreview" class="{{ old('icon') }}"></i>
                                        </span>
                                    </div>
                                    <div class="form-text">FontAwesome or Remixicon class</div>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
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
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                       id="meta_title" name="meta_title" value="{{ old('meta_title') }}" maxlength="255">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                          id="meta_description" name="meta_description" rows="3" maxlength="500">{{ old('meta_description') }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                       id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}" 
                                       placeholder="keyword1, keyword2, keyword3">
                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-xl-4">
                    <!-- Status & Settings -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Status & Settings</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active Status</label>
                                </div>
                                <div class="form-text">Enable or disable this subcategory</div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">Featured</label>
                                </div>
                                <div class="form-text">Mark as featured subcategory</div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Subcategory Image</div>
                        </div>
                        <div class="card-body">
                            <div class="image-upload-container" id="image-upload-area">
                                <div class="upload-content">
                                    <i class="ri-upload-cloud-2-line" style="font-size: 48px; color: #6c757d; margin-bottom: 15px;"></i>
                                    <h5>Drop image here or click to upload</h5>
                                    <p class="text-muted">Supports: JPG, PNG, GIF, WEBP (Max: 5MB)</p>
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('image-input').click()">
                                        <i class="ri-upload-line me-1"></i> Choose File
                                    </button>
                                </div>
                            </div>
                            
                            <input type="file" id="image-input" name="image" accept="image/*" style="display: none;">
                            
                            <!-- Image Preview -->
                            <div id="image-preview-grid" class="image-preview-grid"></div>
                            
                            @error('image')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card custom-card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100 mb-2">
                                <i class="ri-save-line me-1"></i> Create Subcategory
                            </button>
                            <a href="{{ route('admin.subcategories.index') }}" class="btn btn-light w-100">
                                <i class="ri-arrow-left-line me-1"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // Subcategory Create Manager
    class SubcategoryCreateManager {
        constructor() {
            this.selectedImage = null;
            this.previewContainer = document.getElementById('image-preview-grid');
            this.setupImageUpload();
            this.setupFormHandlers();
        }

        setupImageUpload() {
            const uploadContainer = document.getElementById('image-upload-area');
            const imageInput = document.getElementById('image-input');

            if (uploadContainer && imageInput) {
                this.setupDragAndDrop(uploadContainer);
                imageInput.addEventListener('change', (e) => {
                    if (e.target.files.length > 0) {
                        this.handleImageFile(e.target.files[0]);
                    }
                });
            }
        }

        setupDragAndDrop(container) {
            container.addEventListener('dragover', (e) => {
                e.preventDefault();
                container.classList.add('drag-over');
            });

            container.addEventListener('dragleave', (e) => {
                e.preventDefault();
                container.classList.remove('drag-over');
            });

            container.addEventListener('drop', (e) => {
                e.preventDefault();
                container.classList.remove('drag-over');
                const files = Array.from(e.dataTransfer.files);
                if (files.length > 0) {
                    this.handleImageFile(files[0]);
                }
            });
        }

        handleImageFile(file) {
            if (file && file.type.startsWith('image/')) {
                if (file.size <= 5 * 1024 * 1024) { // 5MB limit
                    this.selectedImage = file;
                    this.createImagePreview(file);
                } else {
                    this.showAlert('File is too large. Maximum size is 5MB.', 'warning');
                }
            } else {
                this.showAlert('Please select a valid image file.', 'warning');
            }
        }

        createImagePreview(file) {
            this.previewContainer.innerHTML = '';
            
            const reader = new FileReader();
            reader.onload = (e) => {
                const previewItem = document.createElement('div');
                previewItem.className = 'image-preview-item';
                previewItem.innerHTML = `
                    <img src="${e.target.result}" alt="Subcategory Image Preview">
                    <button type="button" class="image-remove-btn" onclick="subcategoryCreateManager.removeImage()">
                        <i class="ri-close-line"></i>
                    </button>
                `;
                this.previewContainer.appendChild(previewItem);
            };
            reader.readAsDataURL(file);
        }

        removeImage() {
            this.selectedImage = null;
            this.previewContainer.innerHTML = '';
            const imageInput = document.getElementById('image-input');
            if (imageInput) {
                imageInput.value = '';
            }
            this.showAlert('Image removed from upload list', 'info');
        }

        setupFormHandlers() {
            // Auto-generate slug from name
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');
            const slugStatus = document.getElementById('slug-status');
            
            if (nameInput && slugInput) {
                nameInput.addEventListener('input', function() {
                    console.log('Name input changed:', this.value); // Debug log
                    
                    // Always generate slug when name changes (unless user manually edited slug)
                    const currentSlug = slugInput.value;
                    const newSlug = this.value
                        .toLowerCase()
                        .replace(/[^a-z0-9 -]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .replace(/^-+|-+$/g, ''); // Remove leading and trailing dashes
                    
                    console.log('Generated slug:', newSlug); // Debug log
                    
                    // Update slug if it's empty or if it matches the pattern of previous auto-generation
                    if (currentSlug === '' || !slugInput.dataset.manuallyEdited) {
                        slugInput.value = newSlug;
                        console.log('Slug updated to:', newSlug); // Debug log
                        
                        // Show auto-generation status
                        if (slugStatus && newSlug && this.value) {
                            slugStatus.style.display = 'inline';
                            slugStatus.innerHTML = '<i class="ri-check-line"></i> Auto-generated from name';
                        } else if (slugStatus) {
                            slugStatus.style.display = 'none';
                        }
                    }
                });

                // Mark slug as manually edited when user types in it
                slugInput.addEventListener('input', function() {
                    this.dataset.manuallyEdited = 'true';
                    if (slugStatus) {
                        slugStatus.style.display = 'none';
                    }
                });

                // Remove manual edit flag when slug is cleared
                slugInput.addEventListener('blur', function() {
                    if (this.value === '') {
                        delete this.dataset.manuallyEdited;
                        if (slugStatus) {
                            slugStatus.style.display = 'none';
                        }
                    }
                });
            }

            // Icon preview
            const iconInput = document.getElementById('icon');
            const iconPreview = document.getElementById('iconPreview');
            
            if (iconInput && iconPreview) {
                iconInput.addEventListener('input', function() {
                    iconPreview.className = this.value;
                });
            }

            // Form validation
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', (e) => this.validateForm(e));
            }
        }

        validateForm(e) {
            let isValid = true;
            
            // Check required fields
            const requiredFields = ['category_id', 'name'];
            requiredFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    const value = field.value;
                    if (!value || value.trim() === '') {
                        isValid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
                this.showAlert('Please fill in all required fields', 'error');
                return false;
            }
        }

        showAlert(message, type = 'info') {
            // Simple alert for now - can be enhanced with toast notifications
            alert(message);
        }
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        window.subcategoryCreateManager = new SubcategoryCreateManager();
    });
</script>
@endpush
