@extends('admin.layouts.app')

@section('title', 'Edit Category')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Edit Category</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <form action="{{ route('admin.categories.update', $category['id']) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
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
                                    <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $category['name']) }}" placeholder="Enter category name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" name="slug" value="{{ old('slug', $category['slug']) }}" placeholder="Auto-generated from name">
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Leave empty to auto-generate from category name</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" 
                                          placeholder="Enter category description">{{ old('description', $category['description']) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="parent_id" class="form-label">Parent Category</label>
                                    <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                        <option value="">Select Parent Category (Optional)</option>
                                        <option value="1" {{ old('parent_id', $category['parent_id']) == '1' ? 'selected' : '' }}>Electronics</option>
                                        <option value="2" {{ old('parent_id', $category['parent_id']) == '2' ? 'selected' : '' }}>Fashion</option>
                                        <option value="3" {{ old('parent_id', $category['parent_id']) == '3' ? 'selected' : '' }}>Home & Garden</option>
                                        <option value="4" {{ old('parent_id', $category['parent_id']) == '4' ? 'selected' : '' }}>Sports & Fitness</option>
                                        <option value="5" {{ old('parent_id', $category['parent_id']) == '5' ? 'selected' : '' }}>Beauty & Health</option>
                                    </select>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $category['sort_order']) }}" min="0">
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
                                       id="meta_title" name="meta_title" value="{{ old('meta_title', $category['meta_title']) }}" 
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
                                          placeholder="SEO friendly description">{{ old('meta_description', $category['meta_description']) }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Recommended length: 150-160 characters</div>
                            </div>

                            <div class="mb-3">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                       id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $category['meta_keywords']) }}" 
                                       placeholder="keyword1, keyword2, keyword3">
                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Separate keywords with commas</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings & Media -->
                <div class="col-xl-4">
                    <!-- Category Image -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Category Images</div>
                            <small class="text-muted">Upload high-quality category images. First image will be the main category image.</small>
                        </div>
                        <div class="card-body">
                            <!-- Current Image -->
                            @if($category['image'])
                            <div class="mb-3">
                                <label class="form-label">Current Image</label>
                                
                                @php
                                    $imageUrl = $category['image'];
                                    
                                    // Apply the same path fix as products
                                    if (is_string($imageUrl) && preg_match('#/storage/products/\d+/(\d{4}/\d{2}/.+)#', $imageUrl, $matches)) {
                                        $imageUrl = '/storage/products/' . $matches[1];
                                    } elseif (is_string($imageUrl) && preg_match('#/storage/categories/\d+/(\d{4}/\d{2}/.+)#', $imageUrl, $matches)) {
                                        $imageUrl = '/storage/categories/' . $matches[1];
                                    }
                                    
                                    // Ensure URL starts with proper path
                                    if ($imageUrl && !str_starts_with($imageUrl, 'http') && !str_starts_with($imageUrl, '/')) {
                                        $imageUrl = '/storage/' . $imageUrl;
                                    }
                                @endphp
                                
                                <div class="text-center">
                                    <img src="{{ $imageUrl }}" alt="Current Image" 
                                         class="img-fluid rounded" style="max-height: 200px;"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div style="display: none; padding: 20px; text-align: center; background: #f8f9fa; min-height: 200px; align-items: center; justify-content: center; flex-direction: column; border: 1px solid #dee2e6; border-radius: 8px;">
                                        <i class="ti ti-photo-off" style="font-size: 48px; color: #6c757d; margin-bottom: 10px;"></i>
                                        <small class="text-muted">Image not found</small>
                                        <small class="text-muted">{{ $imageUrl }}</small>
                                    </div>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                                    <label class="form-check-label" for="remove_image">
                                        Remove current image
                                    </label>
                                </div>
                            </div>
                            @endif

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

                    <!-- Category Settings -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Category Settings</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="Active" {{ old('status', $category['status']) == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status', $category['status']) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" 
                                       {{ old('is_featured', $category['is_featured']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Featured Category
                                </label>
                                <div class="form-text">Featured categories appear in special sections</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="show_in_menu" name="show_in_menu" value="1" 
                                       {{ old('show_in_menu', $category['show_in_menu']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_in_menu">
                                    Show in Navigation Menu
                                </label>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="show_in_footer" name="show_in_footer" value="1" 
                                       {{ old('show_in_footer', $category['show_in_footer']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_in_footer">
                                    Show in Footer Links
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Affiliate Commission Settings -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="ri-money-dollar-circle-line me-2"></i>Affiliate Commission Settings
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="commission_type" class="form-label">Commission Type</label>
                                <select class="form-select @error('commission_type') is-invalid @enderror" id="commission_type" name="commission_type">
                                    <option value="">Use Default System Rate</option>
                                    <option value="percentage" {{ old('commission_type', $category['commission_type'] ?? '') == 'percentage' ? 'selected' : '' }}>Custom Percentage Rate</option>
                                    <option value="fixed" {{ old('commission_type', $category['commission_type'] ?? '') == 'fixed' ? 'selected' : '' }}>Fixed Amount per Sale</option>
                                    <option value="disabled" {{ old('commission_type', $category['commission_type'] ?? '') == 'disabled' ? 'selected' : '' }}>Disable Commissions</option>
                                </select>
                                <div class="form-text">
                                    Choose how affiliate commissions are calculated for products in this category
                                </div>
                                @error('commission_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="commission_rate_section" style="display: none;">
                                <label for="commission_rate" class="form-label">
                                    Commission Rate 
                                    <span id="commission_rate_unit">(%)</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('commission_rate') is-invalid @enderror" 
                                           id="commission_rate" name="commission_rate" value="{{ old('commission_rate', $category['commission_rate'] ?? '') }}" 
                                           step="0.01" min="0" max="100" placeholder="0.00">
                                    <span class="input-group-text" id="commission_rate_symbol">%</span>
                                </div>
                                <div class="form-text" id="commission_help_text">
                                    Enter commission percentage (e.g., 5.50 for 5.5%)
                                </div>
                                @error('commission_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Commission Preview -->
                            <div class="alert alert-info" id="commission_preview" style="display: none;">
                                <h6 class="alert-heading mb-2">
                                    <i class="ri-information-line me-1"></i>Commission Preview
                                </h6>
                                <div id="commission_example"></div>
                            </div>

                            <!-- Commission inheritance info -->
                            <div class="alert alert-secondary" id="commission_inheritance_info">
                                <small>
                                    <i class="ri-information-line me-1"></i>
                                    <strong>Default Behavior:</strong> Products in this category will use the system default commission rate 
                                    ({{ config('affiliate.default_commission_rate', 5) }}%) unless overridden at product level.
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Category Statistics -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Category Statistics</div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Products:</span>
                                <span class="fw-semibold">{{ $category['products_count'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subcategories:</span>
                                <span class="fw-semibold">{{ $category['subcategories_count'] ?? 0 }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Created:</span>
                                <span class="fw-semibold">{{ date('M d, Y', strtotime($category['created_at'])) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Last Updated:</span>
                                <span class="fw-semibold">{{ date('M d, Y', strtotime($category['updated_at'])) }}</span>
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
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-light">
                                    <i class="ri-arrow-left-line me-1"></i> Back to Categories
                                </a>
                                <a href="{{ route('admin.categories.show', $category['id']) }}" class="btn btn-info">
                                    <i class="ri-eye-line me-1"></i> View Category
                                </a>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="ri-refresh-line me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i> Update Category
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

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

    .form-text.text-end {
        font-size: 0.875rem;
    }

    .text-danger {
        color: #dc3545 !important;
    }
</style>
@endpush

@push('scripts')
<script>
// Global commission configuration
window.defaultCommissionRate = {{ (float) config('affiliate.default_commission_rate', 5) }};

    // Category Edit Manager Class
    class CategoryEditManager {
        constructor() {
            this.selectedImages = [];
            this.previewContainer = document.getElementById('image-preview-grid');
            this.setupImageUpload();
            this.setupFormHandlers();
            this.setupCommissionSettings();
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

            // File input change event
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
                    this.showAlert(`File ${file.name} is too large. Maximum size is 10MB.`, 'warning');
                }
            } else {
                this.showAlert(`File ${file.name} is not a valid image.`, 'warning');
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
                    <img src="${e.target.result}" alt="Category Image Preview">
                    <button type="button" class="image-remove-btn" onclick="categoryEditManager.removeImage(0)">
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
            
            this.showAlert('Image has been removed from upload list', 'info');
        }

        updateImagePreviews() {
            this.previewContainer.innerHTML = '';
            this.selectedImages.forEach((file, index) => {
                this.createImagePreview(file, index);
            });
        }

        setupCommissionSettings() {
            const commissionTypeSelect = document.getElementById('commission_type');
            const commissionRateInput = document.getElementById('commission_rate');

            if (!commissionTypeSelect) {
                return;
            }

            // Handle commission type change
            commissionTypeSelect.addEventListener('change', (e) => {
                this.updateCommissionSettings(e.target.value);
            });

            // Handle commission rate input
            if (commissionRateInput) {
                commissionRateInput.addEventListener('input', (e) => {
                    this.updateCommissionPreview();
                });
            }

            // Initialize with current value
            this.updateCommissionSettings(commissionTypeSelect.value);
        }

        updateCommissionSettings(type) {
            const commissionRateSection = document.getElementById('commission_rate_section');
            const commissionRateSymbol = document.getElementById('commission_rate_symbol');
            const commissionRateUnit = document.getElementById('commission_rate_unit');
            const commissionHelpText = document.getElementById('commission_help_text');
            const inheritanceInfo = document.getElementById('commission_inheritance_info');
            const commissionRateInput = document.getElementById('commission_rate');

            if (!commissionRateSection) {
                return;
            }

            // Show/hide rate section
            if (type === 'percentage' || type === 'fixed') {
                commissionRateSection.style.display = 'block';
                if (inheritanceInfo) inheritanceInfo.style.display = 'none';
                
                if (type === 'percentage') {
                    if (commissionRateSymbol) commissionRateSymbol.textContent = '%';
                    if (commissionRateUnit) commissionRateUnit.textContent = '(%)';
                    if (commissionHelpText) commissionHelpText.textContent = 'Enter commission percentage (e.g., 5.50 for 5.5%)';
                    if (commissionRateInput) {
                        commissionRateInput.setAttribute('max', '100');
                        commissionRateInput.setAttribute('placeholder', '5.00');
                    }
                } else if (type === 'fixed') {
                    if (commissionRateSymbol) commissionRateSymbol.textContent = '৳';
                    if (commissionRateUnit) commissionRateUnit.textContent = '(৳)';
                    if (commissionHelpText) commissionHelpText.textContent = 'Enter fixed commission amount in BDT (e.g., 50 for ৳50 per sale)';
                    if (commissionRateInput) {
                        commissionRateInput.removeAttribute('max');
                        commissionRateInput.setAttribute('placeholder', '50.00');
                    }
                }
            } else {
                commissionRateSection.style.display = 'none';
                if (inheritanceInfo) {
                    if (type === 'disabled') {
                        inheritanceInfo.innerHTML = `
                            <small>
                                <i class="ri-close-circle-line me-1 text-danger"></i>
                                <strong>Commissions Disabled:</strong> No affiliate commissions will be earned for products in this category.
                            </small>
                        `;
                        inheritanceInfo.className = 'alert alert-warning';
                    } else {
                        inheritanceInfo.innerHTML = `
                            <small>
                                <i class="ri-information-line me-1"></i>
                                <strong>Default Behavior:</strong> Products in this category will use the system default commission rate 
                                ({{ config('affiliate.default_commission_rate', 5) }}%) unless overridden at product level.
                            </small>
                        `;
                        inheritanceInfo.className = 'alert alert-secondary';
                    }
                    inheritanceInfo.style.display = 'block';
                }
            }

            this.updateCommissionPreview();
        }

        updateCommissionPreview() {
            const commissionTypeSelect = document.getElementById('commission_type');
            const commissionRateInput = document.getElementById('commission_rate');
            const commissionPreview = document.getElementById('commission_preview');
            const commissionExample = document.getElementById('commission_example');

            if (!commissionTypeSelect || !commissionRateInput || !commissionPreview || !commissionExample) {
                return;
            }

            const type = commissionTypeSelect.value;
            const rate = parseFloat(commissionRateInput.value) || 0;

            if ((type === 'percentage' || type === 'fixed') && rate > 0) {
                commissionPreview.style.display = 'block';
                
                let exampleHtml = '';
                if (type === 'percentage') {
                    const examplePrice = 1000;
                    const commission = (examplePrice * rate) / 100;
                    exampleHtml = `
                        <div class="d-flex justify-content-between mb-2">
                            <span>Commission Rate:</span>
                            <strong>${rate}%</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Example Product Price:</span>
                            <span>৳${examplePrice.toLocaleString()}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Affiliate Earns:</span>
                            <strong class="text-success">৳${commission.toFixed(2)}</strong>
                        </div>
                    `;
                } else if (type === 'fixed') {
                    exampleHtml = `
                        <div class="d-flex justify-content-between mb-2">
                            <span>Fixed Commission:</span>
                            <strong>৳${rate.toFixed(2)}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Affiliate Earns Per Sale:</span>
                            <strong class="text-success">৳${rate.toFixed(2)}</strong>
                        </div>
                        <small class="text-muted">Regardless of product price</small>
                    `;
                }
                
                commissionExample.innerHTML = exampleHtml;
            } else {
                commissionPreview.style.display = 'none';
            }
        }

        setupFormHandlers() {
            // Auto-generate slug from name
            const nameInput = document.getElementById('name');
            if (nameInput) {
                nameInput.addEventListener('input', function() {
                    const name = this.value;
                    const slug = name.toLowerCase()
                        .replace(/[^a-z0-9 -]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .trim('-');
                    document.getElementById('slug').value = slug;
                });
            }

            // Setup character counters
            this.setupCharacterCounter('meta_title', 60);
            this.setupCharacterCounter('meta_description', 160);

            // Form validation
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', (e) => this.validateForm(e));
            }
        }

        setupCharacterCounter(fieldId, maxLength) {
            const field = document.getElementById(fieldId);
            if (!field) return;
            
            const counter = document.createElement('div');
            counter.className = 'form-text text-end';
            field.parentNode.appendChild(counter);
            
            const updateCounter = () => {
                const remaining = maxLength - field.value.length;
                counter.textContent = `${field.value.length}/${maxLength} characters`;
                counter.className = remaining < 0 ? 'form-text text-end text-danger' : 'form-text text-end';
            };
            
            field.addEventListener('input', updateCounter);
            updateCounter();
        }

        validateForm(e) {
            const name = document.getElementById('name').value.trim();
            
            if (!name) {
                e.preventDefault();
                this.showAlert('Category name is required!', 'error');
                document.getElementById('name').focus();
                return false;
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

    // Standalone commission settings handler (fallback)
    function setupCommissionDropdown() {
        const commissionTypeSelect = document.getElementById('commission_type');
        const commissionRateSection = document.getElementById('commission_rate_section');
        const commissionRateInput = document.getElementById('commission_rate');
        const commissionRateSymbol = document.getElementById('commission_rate_symbol');
        const commissionRateUnit = document.getElementById('commission_rate_unit');
        const commissionHelpText = document.getElementById('commission_help_text');
        const commissionPreview = document.getElementById('commission_preview');
        const commissionExample = document.getElementById('commission_example');
        const inheritanceInfo = document.getElementById('commission_inheritance_info');

        if (!commissionTypeSelect) {
            return;
        }

        // Add event listener
        commissionTypeSelect.addEventListener('change', function(e) {
            updateCommissionUI(e.target.value);
        });

        // Add input listener for rate changes
        if (commissionRateInput) {
            commissionRateInput.addEventListener('input', function(e) {
                updateCommissionPreview();
            });
        }

        // Initialize with current value
        updateCommissionUI(commissionTypeSelect.value);

        function updateCommissionUI(type) {
            if (type === 'percentage' || type === 'fixed') {
                if (commissionRateSection) {
                    commissionRateSection.style.display = 'block';
                }
                if (inheritanceInfo) {
                    inheritanceInfo.style.display = 'none';
                }
                
                if (type === 'percentage') {
                    if (commissionRateSymbol) commissionRateSymbol.textContent = '%';
                    if (commissionRateUnit) commissionRateUnit.textContent = '(%)';
                    if (commissionHelpText) commissionHelpText.textContent = 'Enter commission percentage (e.g., 5.50 for 5.5%)';
                    if (commissionRateInput) {
                        commissionRateInput.setAttribute('max', '100');
                        commissionRateInput.setAttribute('placeholder', '5.00');
                    }
                } else if (type === 'fixed') {
                    if (commissionRateSymbol) commissionRateSymbol.textContent = '৳';
                    if (commissionRateUnit) commissionRateUnit.textContent = '(৳)';
                    if (commissionHelpText) commissionHelpText.textContent = 'Enter fixed commission amount in BDT (e.g., 50 for ৳50 per sale)';
                    if (commissionRateInput) {
                        commissionRateInput.removeAttribute('max');
                        commissionRateInput.setAttribute('placeholder', '50.00');
                    }
                }
            } else {
                if (commissionRateSection) {
                    commissionRateSection.style.display = 'none';
                }
                if (inheritanceInfo) {
                    if (type === 'disabled') {
                        inheritanceInfo.innerHTML = `
                            <small>
                                <i class="ri-close-circle-line me-1 text-danger"></i>
                                <strong>Commissions Disabled:</strong> No affiliate commissions will be earned for products in this category.
                            </small>
                        `;
                        inheritanceInfo.className = 'alert alert-warning';
                    } else {
                        inheritanceInfo.innerHTML = `
                            <small>
                                <i class="ri-information-line me-1"></i>
                                <strong>Default Behavior:</strong> Products in this category will use the system default commission rate 
                                (${window.defaultCommissionRate || 5}%) unless overridden at product level.
                            </small>
                        `;
                        inheritanceInfo.className = 'alert alert-secondary';
                    }
                    inheritanceInfo.style.display = 'block';
                }
            }
            
            updateCommissionPreview();
        }

        function updateCommissionPreview() {
            const type = commissionTypeSelect.value;
            const rate = parseFloat(commissionRateInput?.value) || 0;

            if ((type === 'percentage' || type === 'fixed') && rate > 0 && commissionPreview && commissionExample) {
                commissionPreview.style.display = 'block';
                
                let exampleHtml = '';
                if (type === 'percentage') {
                    const examplePrice = 1000;
                    const commission = (examplePrice * rate) / 100;
                    exampleHtml = `
                        <div class="d-flex justify-content-between mb-2">
                            <span>Commission Rate:</span>
                            <strong>${rate}%</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Example Product Price:</span>
                            <span>৳${examplePrice.toLocaleString()}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Affiliate Earns:</span>
                            <strong class="text-success">৳${commission.toFixed(2)}</strong>
                        </div>
                    `;
                } else if (type === 'fixed') {
                    exampleHtml = `
                        <div class="d-flex justify-content-between mb-2">
                            <span>Fixed Commission:</span>
                            <strong>৳${rate.toFixed(2)}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Affiliate Earns Per Sale:</span>
                            <strong class="text-success">৳${rate.toFixed(2)}</strong>
                        </div>
                        <small class="text-muted">Regardless of product price</small>
                    `;
                }
                
                commissionExample.innerHTML = exampleHtml;
            } else if (commissionPreview) {
                commissionPreview.style.display = 'none';
            }
        }
    }

    // Initialize Category Edit Manager
    document.addEventListener('DOMContentLoaded', function() {
        window.categoryEditManager = new CategoryEditManager();
        
        // Setup standalone commission dropdown as fallback
        setupCommissionDropdown();
    });

    // Global Functions
    function openResizeOptions() {
        const panel = document.getElementById('resize-options-panel');
        if (panel) {
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        }
    }

    function resizeUploadedImages() {
        const width = document.getElementById('resize-width').value;
        const height = document.getElementById('resize-height').value;
        
        if (!width && !height) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Info', 'Please enter width and/or height values.', 'info');
            } else {
                alert('Please enter width and/or height values.');
            }
            return;
        }

        const formData = new FormData();
        if (window.categoryEditManager && window.categoryEditManager.selectedImages) {
            window.categoryEditManager.selectedImages.forEach((file, index) => {
                formData.append(`images[${index}]`, file);
            });
        }
        
        if (width) formData.append('width', width);
        if (height) formData.append('height', height);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch('/admin/image-upload/resize', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Success!', 'Images resized successfully!', 'success');
                } else {
                    alert('Images resized successfully!');
                }
            } else {
                throw new Error(data.message || 'Failed to resize images');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error!', 'Failed to resize images: ' + error.message, 'error');
            } else {
                alert('Failed to resize images: ' + error.message);
            }
        });
    }

    function generateThumbnails() {
        if (!window.categoryEditManager || !window.categoryEditManager.selectedImages || window.categoryEditManager.selectedImages.length === 0) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Info', 'Please upload images first.', 'info');
            } else {
                alert('Please upload images first.');
            }
            return;
        }

        const formData = new FormData();
        window.categoryEditManager.selectedImages.forEach((file, index) => {
            formData.append(`images[${index}]`, file);
        });
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch('/admin/image-upload/thumbnails', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Success!', 'Thumbnails generated successfully!', 'success');
                } else {
                    alert('Thumbnails generated successfully!');
                }
            } else {
                throw new Error(data.message || 'Failed to generate thumbnails');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error!', 'Failed to generate thumbnails: ' + error.message, 'error');
            } else {
                alert('Failed to generate thumbnails: ' + error.message);
            }
        });
    }

    function optimizeImages() {
        if (!window.categoryEditManager || !window.categoryEditManager.selectedImages || window.categoryEditManager.selectedImages.length === 0) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Info', 'Please upload images first.', 'info');
            } else {
                alert('Please upload images first.');
            }
            return;
        }

        const quality = document.getElementById('resize-quality').value || 85;
        
        const formData = new FormData();
        window.categoryEditManager.selectedImages.forEach((file, index) => {
            formData.append(`images[${index}]`, file);
        });
        formData.append('quality', quality);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch('/admin/image-upload/optimize', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Success!', 'Images optimized successfully!', 'success');
                } else {
                    alert('Images optimized successfully!');
                }
            } else {
                throw new Error(data.message || 'Failed to optimize images');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error!', 'Failed to optimize images: ' + error.message, 'error');
            } else {
                alert('Failed to optimize images: ' + error.message);
            }
        });
    }

    // Reset form function
    function resetForm() {
        if (confirm('Are you sure you want to reset the form? All changes will be lost.')) {
            location.reload();
        }
    }
</script>
@endpush
@endsection
