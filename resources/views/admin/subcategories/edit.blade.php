@extends('admin.layouts.app')

@section('title', 'Edit Subcategory')

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
        font-weight: 500;
    }

    .upload-content p {
        color: #6c757d;
        margin-bottom: 15px;
        font-size: 0.9rem;
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

    .danger-zone {
        border: 1px solid #dc3545;
        border-radius: 8px;
        background: #f8d7da;
        padding: 15px;
        margin-top: 20px;
    }

    .danger-zone h6 {
        color: #721c24;
        margin-bottom: 10px;
    }

    .danger-zone .text-muted {
        color: #721c24 !important;
        opacity: 0.8;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Edit Subcategory</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.subcategories.index') }}">Subcategories</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <form action="{{ route('admin.subcategories.update', $subcategory['id'] ?? 1) }}" method="POST" enctype="multipart/form-data">
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
                                    <label for="name" class="form-label">Subcategory Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $subcategory['name'] ?? 'Sample Subcategory') }}" placeholder="Enter subcategory name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" name="slug" value="{{ old('slug', $subcategory['slug'] ?? 'sample-subcategory') }}" placeholder="Auto-generated from name">
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
                                          placeholder="Enter subcategory description">{{ old('description', $subcategory['description'] ?? 'Sample subcategory description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">Parent Category <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        @php
                                            // Get the current category ID from the subcategory data
                                            $currentCategoryId = old('category_id', $subcategory['category_id'] ?? ($subcategory['category']['id'] ?? null));
                                        @endphp
                                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                            <option value="">Select Parent Category</option>
                                            <option value="1" {{ $currentCategoryId == '1' || $currentCategoryId == 1 ? 'selected' : '' }}>Electronics</option>
                                            <option value="2" {{ $currentCategoryId == '2' || $currentCategoryId == 2 ? 'selected' : '' }}>Fashion</option>
                                            <option value="3" {{ $currentCategoryId == '3' || $currentCategoryId == 3 ? 'selected' : '' }}>Home & Garden</option>
                                            <option value="4" {{ $currentCategoryId == '4' || $currentCategoryId == 4 ? 'selected' : '' }}>Sports & Fitness</option>
                                            <option value="5" {{ $currentCategoryId == '5' || $currentCategoryId == 5 ? 'selected' : '' }}>Beauty & Health</option>
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
                                            <i class="ri-information-line"></i> Select the parent category for this subcategory
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $subcategory['sort_order'] ?? 0) }}" min="0">
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
                                       id="meta_title" name="meta_title" value="{{ old('meta_title', $subcategory['meta_title'] ?? '') }}" 
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
                                          placeholder="SEO friendly description">{{ old('meta_description', $subcategory['meta_description'] ?? '') }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Recommended length: 150-160 characters</div>
                            </div>

                            <div class="mb-3">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                       id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $subcategory['meta_keywords'] ?? '') }}" 
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
                                    <option value="percentage" {{ old('commission_type', $subcategory['commission_type'] ?? '') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    <option value="fixed" {{ old('commission_type', $subcategory['commission_type'] ?? '') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                    <option value="disabled" {{ old('commission_type', $subcategory['commission_type'] ?? '') == 'disabled' ? 'selected' : '' }}>Disabled</option>
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
                                           id="commission_rate" name="commission_rate" value="{{ old('commission_rate', $subcategory['commission_rate'] ?? '') }}" 
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
                    <!-- Subcategory Image -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Subcategory Images</div>
                            <small class="text-muted">Upload high-quality subcategory images. First image will be the main subcategory image.</small>
                        </div>
                        <div class="card-body">
                            <!-- Current Image -->
                            @if(isset($subcategory['image']) && $subcategory['image'])
                            <div class="mb-3">
                                <label class="form-label">Current Image</label>
                                <div class="text-center">
                                    @php
                                        // Check if image path is already a full URL
                                        if (filter_var($subcategory['image'], FILTER_VALIDATE_URL)) {
                                            // It's already a full URL - use it directly
                                            $imagePath = $subcategory['image'];
                                            // But also fix the domain to current domain
                                            $imagePath = str_replace(['http://localhost', 'https://localhost'], request()->getSchemeAndHttpHost(), $imagePath);
                                        } else {
                                            // It's a relative path
                                            $imagePath = asset('storage/' . $subcategory['image']);
                                        }
                                    @endphp
                                    <img src="{{ $imagePath }}" alt="Current Image" 
                                         class="img-fluid rounded" style="max-height: 200px;"
                                         onerror="this.src='https://via.placeholder.com/200x150/e8f5e8/28a745?text=No+Image';">
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

                    <!-- Subcategory Settings -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Subcategory Settings</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="Active" {{ old('status', $subcategory['status'] ?? 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status', $subcategory['status'] ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" 
                                       {{ old('is_featured', $subcategory['is_featured'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Featured Subcategory
                                </label>
                                <div class="form-text">Featured subcategories appear in special sections</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="show_in_menu" name="show_in_menu" value="1" 
                                       {{ old('show_in_menu', $subcategory['show_in_menu'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_in_menu">
                                    Show in Navigation Menu
                                </label>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="show_in_footer" name="show_in_footer" value="1" 
                                       {{ old('show_in_footer', $subcategory['show_in_footer'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_in_footer">
                                    Show in Footer Links
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Subcategory Statistics -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Subcategory Statistics</div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Products:</span>
                                <span class="fw-semibold">{{ $subcategory['products_count'] ?? 0 }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Parent Category:</span>
                                <span class="fw-semibold">{{ $subcategory['category_name'] ?? 'Electronics' }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Created:</span>
                                <span class="fw-semibold">{{ date('M d, Y', strtotime($subcategory['created_at'] ?? now())) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Last Updated:</span>
                                <span class="fw-semibold">{{ date('M d, Y', strtotime($subcategory['updated_at'] ?? now())) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title text-danger">Danger Zone</div>
                        </div>
                        <div class="card-body">
                            <div class="danger-zone">
                                <h6><i class="ri-error-warning-line me-2"></i>Delete Subcategory</h6>
                                <p class="text-muted mb-3">
                                    Once you delete a subcategory, there is no going back. All products in this subcategory will also be affected. Please be certain.
                                </p>
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete()">
                                    <i class="ri-delete-bin-line me-1"></i> Delete Subcategory
                                </button>
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
                                <a href="{{ route('admin.subcategories.index') }}" class="btn btn-light">
                                    <i class="ri-arrow-left-line me-1"></i> Back to Subcategories
                                </a>
                                <a href="{{ route('admin.subcategories.show', $subcategory['id'] ?? 1) }}" class="btn btn-info">
                                    <i class="ri-eye-line me-1"></i> View Subcategory
                                </a>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="ri-refresh-line me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i> Update Subcategory
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
class SubcategoryEditManager {
    constructor() {
        this.form = document.querySelector('form');
        this.nameField = document.getElementById('name');
        this.slugField = document.getElementById('slug');
        this.categoryField = document.getElementById('category_id');
        this.imageField = document.getElementById('image-input');
        this.previewContainer = document.getElementById('image-preview-grid');
        
        // Track input state to prevent unnecessary notifications
        this.isManuallyEdited = false;
        
        this.initializeComponents(); 
        this.setupEventListeners();
        this.loadParentCategories();
        this.setupImageUpload();
        this.setupCommissionSettings();
    }

    initializeComponents() {
        // Setup character counters
        this.setupCharacterCounter('meta_title', 60);
        this.setupCharacterCounter('meta_description', 160);
        
        // Setup slug validation
        this.setupSlugValidation();
    }

    setupEventListeners() {
        // Auto-generate slug from name with enhanced validation
        this.nameField.addEventListener('input', this.debounce((e) => {
            const name = e.target.value.trim();
            console.log('Name input changed:', name);
            
            if (name && !this.isManuallyEdited) {
                const slug = this.generateSlug(name);
                console.log('Generated slug:', slug);
                this.slugField.value = slug;
                
                // Visual feedback for slug generation
                this.slugField.style.borderColor = '#28a745';
                setTimeout(() => {
                    this.slugField.style.borderColor = '';
                }, 1000);
            }
            
            // Enhanced feedback and validation
            if (name.length >= 3) {
                this.validateSlug(this.slugField.value);
            }
        }, 600));

        // Track manual slug editing
        this.slugField.addEventListener('input', (e) => {
            console.log('Slug manually edited');
            this.isManuallyEdited = true;
            this.slugField.dataset.manuallyEdited = 'true';
        });

        // Image preview with validation
        this.imageField.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                this.handleImageFile(e.target.files[0]);
            }
        });

        // Form submission with AJAX and SweetAlert
        this.form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Check if AJAX is available, otherwise use normal submission
            if (typeof window.ajax === 'undefined') {
                console.log('AJAX not available, using normal form submission');
                Swal.fire({
                    title: 'Updating...',
                    text: 'Updating subcategory using fallback method',
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
                return;
            }
            
            // Show loading state in the dropdown
            this.setLoadingState(true);

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
                }
            } else {
                console.error('Response not successful:', response);
                throw new Error('Failed to load categories');
            }
        } catch (error) {
            console.error('Failed to load parent categories:', error);
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
                helpText.innerHTML = '<i class="ri-information-line"></i> Select the parent category for this subcategory';
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

        // Store current value to maintain selection
        const currentValue = this.categoryField.value;

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
            
            // Restore selection if it matches current value
            if (currentValue && currentValue == category.id) {
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
            const subcategoryId = '{{ $subcategory["id"] ?? 1 }}';
            const response = await ajax.get('/admin/subcategories/validate-slug', { 
                slug: slug,
                id: subcategoryId // Exclude current subcategory from validation
            });
            
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
                text: 'Parent category updated successfully',
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });
        }
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

        // Initialize counter
        field.dispatchEvent(new Event('input'));
    }

    setupSlugValidation() {
        // Initial setup for slug field
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
                uploadArea.classList.add('drag-over');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.classList.remove('drag-over');
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
                    <button type="button" class="image-remove-btn" onclick="subcategoryEditManager.removeImage()">
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
                title: 'Updating Subcategory...',
                text: 'Please wait while we update your subcategory',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const response = await ajax.put(this.form.action, formData);
            
            if (response.success) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Subcategory updated successfully',
                    icon: 'success',
                    confirmButtonText: 'Continue'
                }).then(() => {
                    window.location.href = response.data.redirect || '{{ route("admin.subcategories.index") }}';
                });
            } else {
                throw new Error(response.message || 'Failed to update subcategory');
            }
        } catch (error) {
            console.error('Form submission error:', error);
            Swal.fire({
                title: 'Error!',
                text: error.message || 'Failed to update subcategory',
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
    if (confirm('Are you sure you want to reset the form? All unsaved changes will be lost.')) {
        window.location.reload();
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

function confirmDelete() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this! All products in this subcategory will also be affected.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Make AJAX request to delete subcategory
            const subcategoryId = '{{ $subcategory["id"] ?? 1 }}';
            
            fetch(`/admin/subcategories/${subcategoryId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', 'Subcategory has been deleted.', 'success').then(() => {
                        window.location.href = '{{ route("admin.subcategories.index") }}';
                    });
                } else {
                    Swal.fire('Error!', 'Failed to delete subcategory.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'An error occurred while deleting subcategory.', 'error');
            });
        }
    });
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
    window.subcategoryEditManager = new SubcategoryEditManager();
    
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
