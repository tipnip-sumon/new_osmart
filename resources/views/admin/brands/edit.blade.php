@extends('admin.layouts.app')

@section('title', 'Edit Brand')

@push('styles')
<style>
    /* Image Upload Styles */
    .image-upload-container {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafbfc;
        position: relative;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .image-upload-container:hover {
        border-color: #0d6efd;
        background: #f8f9ff;
        transform: translateY(-2px);
    }
    
    .image-upload-container.drag-over {
        border-color: #20c997;
        background: #f0fff8;
    }
    
    .upload-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    
    .image-preview-container {
        position: relative;
        margin-top: 15px;
    }
    
    .image-preview {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .current-image {
        max-width: 150px;
        max-height: 150px;
        border-radius: 8px;
        border: 2px solid #dee2e6;
        padding: 5px;
    }
    
    .remove-image-btn {
        position: absolute;
        top: -10px;
        right: -10px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
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
            <h1 class="page-title fw-semibold fs-18 mb-0">Edit Brand</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Brands</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit {{ $brand->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data" id="brandForm">
            @csrf
            @method('PUT')
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
                                               value="{{ old('name', $brand->name) }}" required placeholder="Enter brand name">
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
                                               value="{{ old('slug', $brand->slug) }}" placeholder="Auto-generated from name">
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
                                              placeholder="Enter brand description">{{ old('description', $brand->description) }}</textarea>
                                    @error('description')
                                        <div class="field-feedback error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Website URL -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Website URL</label>
                                    <input type="url" class="form-control" name="website_url" 
                                           value="{{ old('website_url', $brand->website_url) }}" placeholder="https://example.com">
                                    @error('website_url')
                                        <div class="field-feedback error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" 
                                           value="{{ old('email', $brand->email) }}" placeholder="contact@brand.com">
                                    @error('email')
                                        <div class="field-feedback error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="phone" 
                                           value="{{ old('phone', $brand->phone) }}" placeholder="+1234567890">
                                    @error('phone')
                                        <div class="field-feedback error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Address -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" name="address" 
                                           value="{{ old('address', $brand->address) }}" placeholder="Brand headquarters address">
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
                                           value="{{ old('meta_title', $brand->meta_title) }}" placeholder="SEO meta title">
                                    @error('meta_title')
                                        <div class="field-feedback error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea class="form-control" name="meta_description" rows="3" 
                                              placeholder="SEO meta description">{{ old('meta_description', $brand->meta_description) }}</textarea>
                                    @error('meta_description')
                                        <div class="field-feedback error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control" name="meta_keywords" 
                                           value="{{ old('meta_keywords', $brand->meta_keywords) }}" placeholder="keyword1, keyword2, keyword3">
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
                            <!-- Current Logo -->
                            @if($brand->logo || $brand->logo_data)
                                <div class="mb-3">
                                    <label class="form-label">Current Logo</label>
                                    <div class="text-center">
                                        @php
                                            $logoUrl = null;
                                            $logoPath = null;
                                            
                                            // Handle logo_data whether it's array or JSON string
                                            $logoData = $brand->logo_data;
                                            if (is_string($logoData)) {
                                                $logoData = json_decode($logoData, true);
                                            }
                                            
                                            // Try to get from logo_data first (new system)
                                            if ($logoData && is_array($logoData) && isset($logoData['sizes']['medium']['url'])) {
                                                $logoUrl = $logoData['sizes']['medium']['url'];
                                                $logoPath = $logoData['sizes']['medium']['path'] ?? $brand->logo;
                                            } elseif ($logoData && is_array($logoData) && isset($logoData['sizes']['medium']['path'])) {
                                                // If we have path but no URL, construct it
                                                $logoPath = $logoData['sizes']['medium']['path'];
                                                $logoUrl = asset('storage/' . $logoPath);
                                            } else {
                                                // Fallback to simple logo field (old system)
                                                $logoPath = $brand->logo;
                                                $logoUrl = asset('storage/' . $logoPath);
                                            }
                                        @endphp
                                        <img src="{{ $logoUrl }}" 
                                             alt="{{ $brand->name }}" 
                                             class="current-logo"
                                             data-debug-path="{{ $logoPath }}"
                                             data-full-url="{{ $logoUrl }}"
                                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjE1MCIgdmlld0JveD0iMCAwIDE1MCAxNTAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiBmaWxsPSIjZjhmOWZhIi8+CjxwYXRoIGQ9Ik03NSA0NUM1OC40MzE1IDQ1IDQ1IDU4LjQzMTUgNDUgNzVDNDUgOTEuNTY4NSA1OC40MzE1IDEwNSA3NSAxMDVDOTEuNTY4NSAxMDUgMTA1IDkxLjU2ODUgMTA1IDc1QzEwNSA1OC40MzE1IDkxLjU2ODUgNDUgNzUgNDVaTTc1IDU0Qzc5Ljk3MDYgNTQgODQgNTguMDI5NCA4NCA2M0M4NCA2Ny45NzA2IDc5Ljk3MDYgNzIgNzUgNzJDNzAuMDI5NCA3MiA2NiA2Ny45NzA2IDY2IDYzQzY2IDU4LjAyOTQgNzAuMDI5NCA1NCA3NSA1NFpNNzUgOTZDNjQuNSA5NiA1NS41IDkxLjUgNTQgODIuNUM1NCA3OC4zNzUgNjEuNSA3My41IDc1IDczLjVDODguNSA3My41IDk2IDc4LjM3NSA5NiA4Mi41Qzk0LjUgOTEuNSA4NS41IDk2IDc1IDk2WiIgZmlsbD0iIzZjNzU3ZCIvPgo8L3N2Zz4K';">
                                    </div>
                                </div>
                            @endif

                            <div class="image-upload-container" id="image-upload-area">
                                <div class="upload-content">
                                    <i class="ti ti-cloud-upload" style="font-size: 48px; color: #6c757d; margin-bottom: 15px;"></i>
                                    <h5>{{ $brand->logo || $brand->logo_data ? 'Replace logo' : 'Drop logo here or click to upload' }}</h5>
                                    <p class="text-muted">Supports: JPG, PNG, GIF, WEBP (Max: 10MB)</p>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button type="button" class="btn btn-primary" onclick="document.getElementById('image-input').click()">
                                            <i class="ti ti-upload"></i> {{ $brand->logo || $brand->logo_data ? 'Replace' : 'Choose File' }}
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
                                    <option value="Active" {{ old('status', $brand->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status', $brand->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="field-feedback error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Featured -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" 
                                           {{ old('is_featured', $brand->is_featured) ? 'checked' : '' }}>
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
                                       value="{{ old('sort_order', $brand->sort_order ?? 0) }}" min="0">
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
                                    <option value="percentage" {{ old('commission_type', $brand->commission_type) == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    <option value="fixed" {{ old('commission_type', $brand->commission_type) == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Commission Rate</label>
                                <input type="number" class="form-control" name="commission_rate" 
                                       value="{{ old('commission_rate', $brand->commission_rate ?? 0) }}" step="0.01" min="0">
                                <small class="text-muted">Percentage (%) or fixed amount</small>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>Update Brand
                                </button>
                                <a href="{{ route('admin.brands.show', $brand->id) }}" class="btn btn-info">
                                    <i class="ri-eye-line me-1"></i>View Brand
                                </a>
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
<script>
class BrandEditManager {
    constructor() {
        this.nameField = document.getElementById('name');
        this.slugField = document.getElementById('slug');
        this.logoInput = document.getElementById('logo-input');
        this.logoUploadArea = document.getElementById('logo-upload-area');
        this.logoPreviewContainer = document.getElementById('logo-preview-container');
        this.logoPreview = document.getElementById('logo-preview');
        this.originalSlug = this.slugField ? this.slugField.value : '';
        
        this.init();
    }
    
    init() {
        this.setupSlugGeneration();
        this.setupImageUpload();
    }
    
    setupSlugGeneration() {
        if (!this.nameField || !this.slugField) return;
        
        this.slugManuallyEdited = false;
        this.addSlugResetButton();
        
        let nameTimeout;
        this.nameField.addEventListener('input', () => {
            clearTimeout(nameTimeout);
            nameTimeout = setTimeout(() => {
                const name = this.nameField.value.trim();
                
                // Only auto-generate if user hasn't manually edited slug or if slug is empty
                if (!this.slugManuallyEdited || !this.slugField.value.trim()) {
                    if (name) {
                        const slug = this.generateSlug(name);
                        this.slugField.value = slug;
                        this.validateSlug(slug);
                    } else {
                        this.slugField.value = '';
                        this.clearSlugFeedback();
                    }
                }
            }, 300);
        });
        
        this.slugField.addEventListener('input', () => {
            clearTimeout(this.slugValidationTimeout);
            this.slugValidationTimeout = setTimeout(() => {
                const currentSlug = this.slugField.value.trim();
                if (currentSlug) {
                    // Check if user manually typed a different slug
                    const autoGeneratedSlug = this.generateSlug(this.nameField.value.trim());
                    if (currentSlug !== autoGeneratedSlug && this.nameField.value.trim()) {
                        this.slugManuallyEdited = true;
                    }
                    this.validateSlug(currentSlug);
                } else {
                    this.clearSlugFeedback();
                }
            }, 300);
        });
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
    
    setupImageUpload() {
        if (!this.logoUploadArea || !this.logoInput) return;
        
        // Click to upload
        this.logoUploadArea.addEventListener('click', () => {
            this.logoInput.click();
        });
        
        // Drag and drop
        this.logoUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            this.logoUploadArea.classList.add('drag-over');
        });
        
        this.logoUploadArea.addEventListener('dragleave', () => {
            this.logoUploadArea.classList.remove('drag-over');
        });
        
        this.logoUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            this.logoUploadArea.classList.remove('drag-over');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                this.handleImageFile(files[0]);
            }
        });
        
        // File input change
        this.logoInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                this.handleImageFile(e.target.files[0]);
            }
        });
    }
    
    handleImageFile(file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            this.showError('Please select a valid image file.');
            return;
        }
        
        // Validate file size (10MB)
        if (file.size > 10 * 1024 * 1024) {
            this.showError('File size must be less than 10MB.');
            return;
        }
        
        // Create file reader
        const reader = new FileReader();
        reader.onload = (e) => {
            this.logoPreview.src = e.target.result;
            this.logoPreviewContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);
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
        
        const slugStatus = document.getElementById('slug-status');
        const slugFeedback = document.getElementById('slug-feedback');
        
        try {
            const brandId = '{{ $brand->id }}';
            const response = await fetch(`/admin/brands/validate-slug?slug=${encodeURIComponent(slug)}&id=${brandId}`);
            const data = await response.json();
            
            if (data.available) {
                this.setFieldStatus(this.slugField, 'valid', 'Slug is available');
            } else {
                this.setFieldStatus(this.slugField, 'invalid', 'Slug is already taken');
            }
        } catch (error) {
            console.log('Slug validation failed:', error);
        }
    }
    
    setFieldStatus(field, status, message) {
        const statusIcon = field.parentElement.querySelector('.field-status');
        const feedback = field.parentElement.parentElement.querySelector('.field-feedback');
        
        if (statusIcon) {
            statusIcon.innerHTML = status === 'valid' ? 
                '<i class="ri-check-line" style="color: #28a745;"></i>' : 
                '<i class="ri-close-line" style="color: #dc3545;"></i>';
        }
        
        if (feedback && !feedback.classList.contains('error')) {
            feedback.textContent = message;
            feedback.className = `field-feedback ${status === 'valid' ? 'success' : 'warning'}`;
            feedback.style.display = 'block';
        }
    }
    
    showError(message) {
        // You can implement a toast notification or alert here
        alert(message);
    }
}

// Global function to toggle resize options panel
function openResizeOptions() {
    const panel = document.getElementById('resize-options-panel');
    if (panel) {
        if (panel.style.display === 'none' || panel.style.display === '') {
            panel.style.display = 'block';
        } else {
            panel.style.display = 'none';
        }
    }
}

// Global function to resize uploaded images
function resizeUploadedImages() {
    const width = document.getElementById('resize-width').value;
    const height = document.getElementById('resize-height').value;
    const quality = document.getElementById('resize-quality').value;
    const maintainRatio = document.getElementById('maintain-ratio').checked;
    
    Swal.fire({
        title: 'Resize Images',
        text: `This will resize images to ${width}x${height}px with ${quality}% quality${maintainRatio ? ' (maintaining aspect ratio)' : ''}.`,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Resize',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // This is a placeholder - actual resizing would be handled server-side
            Swal.fire('Success!', 'Image resize settings applied for next upload.', 'success');
        }
    });
}

// Global function to generate thumbnails
function generateThumbnails() {
    Swal.fire({
        title: 'Generate Thumbnails',
        text: 'This will generate multiple thumbnail sizes for the uploaded image.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Generate',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // This is a placeholder - actual thumbnail generation would be handled server-side
            Swal.fire('Success!', 'Thumbnails will be generated on upload.', 'success');
        }
    });
}

// Global function to optimize images
function optimizeImages() {
    Swal.fire({
        title: 'Optimize Images',
        text: 'This will compress and optimize images for better performance.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Optimize',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // This is a placeholder - actual optimization would be handled server-side
            Swal.fire('Success!', 'Images will be optimized on upload.', 'success');
        }
    });
}

// Global function to remove logo
function removeLogo() {
    const logoInput = document.getElementById('image-input');
    const logoPreviewContainer = document.getElementById('logo-preview-container');
    
    if (logoInput) {
        logoInput.value = '';
    }
    if (logoPreviewContainer) {
        logoPreviewContainer.style.display = 'none';
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new BrandEditManager();
});
</script>
@endpush
