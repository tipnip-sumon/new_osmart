@extends('admin.layouts.app')

@section('title', 'Image Upload Component - Usage Guide')

@push('styles')
<style>
    .bulk-resize-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
    }
    
    .image-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 10px;
    }
    
    .size-configuration {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .size-configuration:hover {
        border-color: #007bff;
        background-color: #f8f9fa;
    }
    
    .resize-progress-card {
        box-shadow: 0 4px 6px rgba(0, 123, 255, 0.1);
    }
    
    .resize-results-card {
        box-shadow: 0 4px 6px rgba(40, 167, 69, 0.1);
    }
    
    .aspect-ratio-display {
        font-family: 'Courier New', monospace;
        font-size: 0.75rem;
        color: #6c757d;
    }
    
    .preset-size-btn {
        transition: all 0.2s ease;
    }
    
    .preset-size-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .file-drop-zone {
        border: 2px dashed #007bff;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background: #f8f9ff;
        transition: all 0.3s ease;
    }
    
    .file-drop-zone:hover {
        background: #e7f1ff;
        border-color: #0056b3;
    }
    
    .resize-log {
        background: #000;
        color: #00ff00;
        font-family: 'Courier New', monospace;
        font-size: 0.85rem;
        border-radius: 4px;
    }
    
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .processing {
        animation: pulse 2s infinite;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div>
                <h1 class="page-title fw-semibold fs-18 mb-0">
                    <i class="bx bx-book-open me-2"></i>Image Upload Component Usage
                </h1>
                <p class="text-muted mb-0">Learn how to integrate and use the image upload component</p>
            </div>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.image-upload.demo') }}">Image Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Usage Guide</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Quick Navigation -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Quick Navigation</h6>
                        <div class="btn-group flex-wrap" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="scrollToSection('examples')">
                                <i class="bx bx-code-alt"></i> Live Examples
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="scrollToSection('bulk-resize')">
                                <i class="bx bx-resize"></i> Bulk Resize
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="scrollToSection('parameters')">
                                <i class="bx bx-cog"></i> Parameters
                            </button>

                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="scrollToSection('best-practices')">
                                <i class="bx bx-check-shield"></i> Best Practices
                            </button>
                            <a href="{{ route('admin.image-upload.demo') }}" class="btn btn-primary btn-sm">
                                <i class="bx bx-play"></i> Try Demo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overview Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-info-circle me-2"></i>Component Overview
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info border-0">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="bx bx-bulb fs-2 text-info"></i>
                                </div>
                                <div>
                                    <h6 class="alert-heading">What is the Image Upload Component?</h6>
                                    <p class="mb-2">A reusable, feature-rich component that provides drag-and-drop image upload functionality with preview, validation, and management capabilities.</p>
                                    <hr class="my-3">
                                    <h6>Perfect for:</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="list-unstyled">
                                                <li><i class="bx bx-check text-success me-2"></i>Product image galleries</li>
                                                <li><i class="bx bx-check text-success me-2"></i>Category thumbnails</li>
                                                <li><i class="bx bx-check text-success me-2"></i>User profile pictures</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="list-unstyled">
                                                <li><i class="bx bx-check text-success me-2"></i>Banner management</li>
                                                <li><i class="bx bx-check text-success me-2"></i>Brand logos</li>
                                                <li><i class="bx bx-check text-success me-2"></i>Any image upload needs</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Examples Section -->
        <div id="examples" class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-code-alt me-2"></i>Live Examples
                        </h5>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary" onclick="resetAllForms()">
                                <i class="bx bx-refresh"></i> Reset All
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="showAllFormData()">
                                <i class="bx bx-data"></i> Show Form Data
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="exampleForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Example Grid -->
                            <div class="row g-4">
                                <!-- Example 1: Multiple Product Images -->
                                <div class="col-lg-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0">
                                                <span class="badge bg-primary me-2">1</span>Multiple Product Images
                                            </h6>
                                            <small class="text-muted">Max: 8 files</small>
                                        </div>
                                        @include('admin.components.image-upload', [
                                            'name' => 'product_images[]',
                                            'multiple' => true,
                                            'label' => 'Product Images',
                                            'required' => true,
                                            'maxFiles' => 8,
                                            'folder' => 'products',
                                            'existing' => []
                                        ])
                                        <small class="text-muted">
                                            <i class="bx bx-info-circle"></i> Perfect for e-commerce product galleries
                                        </small>
                                    </div>
                                </div>

                                <!-- Example 2: Single Category Thumbnail -->
                                <div class="col-lg-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0">
                                                <span class="badge bg-success me-2">2</span>Single Category Thumbnail
                                            </h6>
                                            <small class="text-muted">Max: 1 file</small>
                                        </div>
                                        @include('admin.components.image-upload', [
                                            'name' => 'category_thumbnail',
                                            'multiple' => false,
                                            'label' => 'Category Thumbnail',
                                            'required' => false,
                                            'maxFiles' => 1,
                                            'folder' => 'categories',
                                            'existing' => []
                                        ])
                                        <small class="text-muted">
                                            <i class="bx bx-info-circle"></i> Ideal for category icons and thumbnails
                                        </small>
                                    </div>
                                </div>

                                <!-- Example 3: Banner Images with Existing -->
                                <div class="col-lg-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0">
                                                <span class="badge bg-warning me-2">3</span>With Existing Images
                                            </h6>
                                            <small class="text-muted">Max: 5 files</small>
                                        </div>
                                        @include('admin.components.image-upload', [
                                            'name' => 'banner_images[]',
                                            'multiple' => true,
                                            'label' => 'Banner Images',
                                            'required' => false,
                                            'maxFiles' => 5,
                                            'folder' => 'banners',
                                            'existing' => []
                                        ])
                                        <small class="text-muted">
                                            <i class="bx bx-info-circle"></i> Shows how to handle existing images
                                        </small>
                                    </div>
                                </div>

                                <!-- Example 4: Profile Picture -->
                                <div class="col-lg-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0">
                                                <span class="badge bg-info me-2">4</span>Profile Picture
                                            </h6>
                                            <small class="text-muted">Max: 1 file</small>
                                        </div>
                                        @include('admin.components.image-upload', [
                                            'name' => 'profile_picture',
                                            'multiple' => false,
                                            'label' => 'Profile Picture',
                                            'required' => false,
                                            'maxFiles' => 1,
                                            'folder' => 'profiles',
                                            'existing' => []
                                        ])
                                        <small class="text-muted">
                                            <i class="bx bx-info-circle"></i> For user avatars and profile photos
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="mt-4 text-center">
                                <div class="btn-group" role="group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save me-2"></i>Save Examples
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="showFormData()">
                                        <i class="bx bx-data me-2"></i>Preview Data
                                    </button>
                                    <button type="button" class="btn btn-outline-info" onclick="validateForm()">
                                        <i class="bx bx-check-circle me-2"></i>Validate
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Resize Section -->
        <div id="bulk-resize" class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-resize me-2"></i>Bulk Resize Functionality
                        </h5>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-primary" onclick="loadSampleImages()">
                                <i class="bx bx-download"></i> Load Samples
                            </button>
                            <button type="button" class="btn btn-outline-success" onclick="addResizePreset()">
                                <i class="bx bx-plus"></i> Add Preset
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info border-0">
                            <i class="bx bx-info-circle me-2"></i>
                            <strong>Bulk Resize</strong> allows you to resize multiple images simultaneously with different dimensions. 
                            Perfect for creating thumbnails, different display sizes, or optimizing images for various platforms.
                        </div>

                        <!-- Image Selection Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="bx bx-images me-2"></i>Image Selection
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Upload New Images -->
                                        <div class="mb-3">
                                            <label class="form-label">Upload Images for Resizing</label>
                                            <input type="file" 
                                                class="form-control" 
                                                id="bulkResizeImages" 
                                                accept="image/*" 
                                                multiple>
                                            <div class="form-text">Select multiple images to resize</div>
                                        </div>

                                        <!-- Existing Image Paths -->
                                        <div class="mb-3">
                                            <label class="form-label">Or Use Existing Image Paths</label>
                                            <textarea class="form-control" 
                                                id="existingImagePaths" 
                                                rows="4" 
                                                placeholder="uploads/image1.jpg&#10;uploads/image2.jpg&#10;uploads/image3.jpg"></textarea>
                                            <div class="form-text">Enter image paths (one per line)</div>
                                        </div>

                                        <!-- Selected Images Preview -->
                                        <div id="selectedImagesPreview" class="border rounded p-3" style="min-height: 100px; display: none;">
                                            <h6>Selected Images:</h6>
                                            <div id="imagePreviewContainer" class="d-flex flex-wrap gap-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="bx bx-cog me-2"></i>Resize Configuration
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Preset Sizes -->
                                        <div class="mb-3">
                                            <label class="form-label">Quick Presets</label>
                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addPresetSize(150, 150, 'Thumbnail')">
                                                    150x150 (Thumbnail)
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addPresetSize(300, 300, 'Small')">
                                                    300x300 (Small)
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addPresetSize(800, 600, 'Medium')">
                                                    800x600 (Medium)
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addPresetSize(1920, 1080, 'HD')">
                                                    1920x1080 (HD)
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Custom Sizes Container -->
                                        <div class="mb-3">
                                            <label class="form-label">Custom Sizes</label>
                                            <div id="resizeSizesContainer">
                                                <!-- Dynamic size inputs will be added here -->
                                            </div>
                                            <button type="button" class="btn btn-outline-secondary btn-sm mt-2" onclick="addCustomSize()">
                                                <i class="bx bx-plus"></i> Add Size
                                            </button>
                                        </div>

                                        <!-- Output Options -->
                                        <div class="mb-3">
                                            <label class="form-label">Output Options</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <select class="form-select form-select-sm" id="outputFormat">
                                                        <option value="same">Keep Original Format</option>
                                                        <option value="jpg">Convert to JPG</option>
                                                        <option value="png">Convert to PNG</option>
                                                        <option value="webp">Convert to WebP</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="range" class="form-range" id="qualitySlider" min="10" max="100" value="85">
                                                    <div class="form-text">Quality: <span id="qualityValue">85</span>%</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-primary" onclick="startBulkResize()" id="bulkResizeBtn">
                                                <i class="bx bx-resize me-2"></i>Start Bulk Resize
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearBulkResize()">
                                                <i class="bx bx-trash me-2"></i>Clear All
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Section -->
                        <div id="resizeProgressSection" class="row mb-4" style="display: none;">
                            <div class="col-12">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">
                                            <i class="bx bx-loader-alt bx-spin me-2"></i>Resize Progress
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="progress mb-3">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                                id="resizeProgressBar" 
                                                role="progressbar" 
                                                style="width: 0%">
                                                0%
                                            </div>
                                        </div>
                                        <div id="resizeStatusText" class="text-center">Preparing to resize images...</div>
                                        <div id="resizeLog" class="mt-3" style="max-height: 200px; overflow-y: auto; font-family: monospace; font-size: 0.9em;">
                                            <!-- Progress logs will appear here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Results Section -->
                        <div id="resizeResultsSection" class="row" style="display: none;">
                            <div class="col-12">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">
                                            <i class="bx bx-check-circle me-2"></i>Resize Results
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="resizeResultsContent">
                                            <!-- Results will be populated here -->
                                        </div>
                                        <div class="mt-3">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="downloadResizedImages()">
                                                <i class="bx bx-download me-2"></i>Download All
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="copyResultsPaths()">
                                                <i class="bx bx-copy me-2"></i>Copy Paths
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Component Parameters Section -->
        <div id="parameters" class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-cog me-2"></i>Component Parameters
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%">Parameter</th>
                                        <th style="width: 15%">Type</th>
                                        <th style="width: 15%">Default</th>
                                        <th style="width: 10%">Required</th>
                                        <th style="width: 40%">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code class="text-primary">name</code></td>
                                        <td><span class="badge bg-info">string</span></td>
                                        <td><code>'images[]'</code></td>
                                        <td><span class="badge bg-success">Yes</span></td>
                                        <td>The name attribute for the file input field</td>
                                    </tr>
                                    <tr>
                                        <td><code class="text-primary">multiple</code></td>
                                        <td><span class="badge bg-info">boolean</span></td>
                                        <td><code>true</code></td>
                                        <td><span class="badge bg-secondary">No</span></td>
                                        <td>Allow multiple file selection and upload</td>
                                    </tr>
                                    <tr>
                                        <td><code class="text-primary">existing</code></td>
                                        <td><span class="badge bg-info">array</span></td>
                                        <td><code>[]</code></td>
                                        <td><span class="badge bg-secondary">No</span></td>
                                        <td>Array of existing image paths to display as thumbnails</td>
                                    </tr>
                                    <tr>
                                        <td><code class="text-primary">label</code></td>
                                        <td><span class="badge bg-info">string</span></td>
                                        <td><code>'Images'</code></td>
                                        <td><span class="badge bg-secondary">No</span></td>
                                        <td>Display label for the upload field</td>
                                    </tr>
                                    <tr>
                                        <td><code class="text-primary">required</code></td>
                                        <td><span class="badge bg-info">boolean</span></td>
                                        <td><code>false</code></td>
                                        <td><span class="badge bg-secondary">No</span></td>
                                        <td>Whether the field is required for form submission</td>
                                    </tr>
                                    <tr>
                                        <td><code class="text-primary">maxFiles</code></td>
                                        <td><span class="badge bg-info">integer</span></td>
                                        <td><code>10</code></td>
                                        <td><span class="badge bg-secondary">No</span></td>
                                        <td>Maximum number of files that can be selected</td>
                                    </tr>
                                    <tr>
                                        <td><code class="text-primary">folder</code></td>
                                        <td><span class="badge bg-info">string</span></td>
                                        <td><code>'uploads'</code></td>
                                        <td><span class="badge bg-secondary">No</span></td>
                                        <td>Storage folder name for organizing uploaded images</td>
                                    </tr>
                                    <tr>
                                        <td><code class="text-primary">maxFileSize</code></td>
                                        <td><span class="badge bg-info">string</span></td>
                                        <td><code>'10MB'</code></td>
                                        <td><span class="badge bg-secondary">No</span></td>
                                        <td>Maximum file size per image (e.g., '5MB', '2GB')</td>
                                    </tr>
                                    <tr>
                                        <td><code class="text-primary">acceptedTypes</code></td>
                                        <td><span class="badge bg-info">string</span></td>
                                        <td><code>'image/*'</code></td>
                                        <td><span class="badge bg-secondary">No</span></td>
                                        <td>Accepted file types (MIME types or extensions)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-light border-start border-4 border-info">
                            <h6 class="alert-heading">
                                <i class="bx bx-lightbulb me-2"></i>Pro Tips
                            </h6>
                            <ul class="mb-0">
                                <li>Use <code>multiple: false</code> for single image uploads like avatars or thumbnails</li>
                                <li>Set appropriate <code>maxFiles</code> limits to prevent server overload</li>
                                <li>Organize uploads with descriptive <code>folder</code> names</li>
                                <li>Always validate file types and sizes on the backend as well</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Best Practices Section -->
        <div id="best-practices" class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-check-shield me-2"></i>Best Practices & Tips
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-success">✅ Do's</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item border-0 px-0">
                                        <i class="bx bx-check text-success me-2"></i>
                                        <strong>Validate on both frontend and backend</strong>
                                        <br><small class="text-muted">Always validate file types, sizes, and dimensions</small>
                                    </li>
                                    <li class="list-group-item border-0 px-0">
                                        <i class="bx bx-check text-success me-2"></i>
                                        <strong>Use descriptive folder names</strong>
                                        <br><small class="text-muted">Organize uploads: 'products/thumbnails', 'users/avatars'</small>
                                    </li>
                                    <li class="list-group-item border-0 px-0">
                                        <i class="bx bx-check text-success me-2"></i>
                                        <strong>Set appropriate file limits</strong>
                                        <br><small class="text-muted">Balance user experience with server resources</small>
                                    </li>
                                    <li class="list-group-item border-0 px-0">
                                        <i class="bx bx-check text-success me-2"></i>
                                        <strong>Provide visual feedback</strong>
                                        <br><small class="text-muted">Show upload progress and validation errors</small>
                                    </li>
                                    <li class="list-group-item border-0 px-0">
                                        <i class="bx bx-check text-success me-2"></i>
                                        <strong>Handle existing images properly</strong>
                                        <br><small class="text-muted">Show current images in edit forms</small>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-danger">❌ Don'ts</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item border-0 px-0">
                                        <i class="bx bx-x text-danger me-2"></i>
                                        <strong>Don't skip server-side validation</strong>
                                        <br><small class="text-muted">Client-side validation can be bypassed</small>
                                    </li>
                                    <li class="list-group-item border-0 px-0">
                                        <i class="bx bx-x text-danger me-2"></i>
                                        <strong>Don't allow unlimited file sizes</strong>
                                        <br><small class="text-muted">This can crash your server or exhaust storage</small>
                                    </li>
                                    <li class="list-group-item border-0 px-0">
                                        <i class="bx bx-x text-danger me-2"></i>
                                        <strong>Don't accept all file types</strong>
                                        <br><small class="text-muted">Restrict to safe image formats only</small>
                                    </li>
                                    <li class="list-group-item border-0 px-0">
                                        <i class="bx bx-x text-danger me-2"></i>
                                        <strong>Don't ignore error handling</strong>
                                        <br><small class="text-muted">Always handle upload failures gracefully</small>
                                    </li>
                                    <li class="list-group-item border-0 px-0">
                                        <i class="bx bx-x text-danger me-2"></i>
                                        <strong>Don't store files in public folders directly</strong>
                                        <br><small class="text-muted">Use Laravel's storage system for security</small>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="alert alert-warning border-0">
                                <h6 class="alert-heading">
                                    <i class="bx bx-error me-2"></i>Security Considerations
                                </h6>
                                <ul class="mb-0">
                                    <li>Always validate file types on the server-side</li>
                                    <li>Scan uploaded files for malware if possible</li>
                                    <li>Store uploaded files outside the web root when possible</li>
                                    <li>Use unique filenames to prevent conflicts and direct access</li>
                                    <li>Implement rate limiting to prevent abuse</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Reference Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-gradient-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bx bx-rocket me-2"></i>Quick Reference
                        </h5>
                        <div class="row">
                            <div class="col-md-4">
                                <h6>Single Image</h6>
                                <code class="text-success">multiple: false, maxFiles: 1</code>
                            </div>
                            <div class="col-md-4">
                                <h6>Multiple Images</h6>
                                <code class="text-success">multiple: true, maxFiles: 10</code>
                            </div>
                            <div class="col-md-4">
                                <h6>Required Field</h6>
                                <code class="text-success">required: true</code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Function to scroll to a specific section
function scrollToSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        const offsetTop = section.offsetTop - 100; // Account for fixed header
        window.scrollTo({
            top: offsetTop,
            behavior: 'smooth'
        });
    }
}

// Function to reset all forms
function resetAllForms() {
    const form = document.getElementById('exampleForm');
    if (form) {
        form.reset();
        
        // Reset any file upload components
        const fileInputs = form.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            // Clear file input
            input.value = '';
            
            // Clear preview containers if they exist
            const container = input.closest('.image-upload-container');
            if (container) {
                const previews = container.querySelectorAll('.preview-container');
                previews.forEach(preview => preview.remove());
            }
        });
        
        // Show success message
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
        alert.style.top = '20px';
        alert.style.right = '20px';
        alert.style.zIndex = '9999';
        alert.innerHTML = `
            <i class="bx bx-check me-2"></i>All forms have been reset successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);
        
        // Auto-remove alert after 3 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 3000);
    }
}

// Function to show all form data
function showAllFormData() {
    const form = document.getElementById('exampleForm');
    if (form) {
        const formData = new FormData(form);
        
        let dataPreview = 'Complete Form Data Preview:\n\n';
        let fileCount = 0;
        let hasData = false;
        
        for (let [key, value] of formData.entries()) {
            hasData = true;
            if (value instanceof File) {
                dataPreview += `${key}: ${value.name} (${value.type}, ${formatFileSize(value.size)})\n`;
                fileCount++;
            } else {
                dataPreview += `${key}: ${value}\n`;
            }
        }
        
        if (!hasData) {
            dataPreview = 'No data found in the form. Please select some files or fill in the form fields.';
        } else {
            dataPreview += `\nTotal files selected: ${fileCount}`;
        }
        
        // Create a modal to show the data
        const modalHtml = `
            <div class="modal fade" id="formDataModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bx bx-data me-2"></i>Form Data Preview
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <pre class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;">${dataPreview}</pre>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="copyToClipboard('${dataPreview.replace(/'/g, "\\'")}')">
                                <i class="bx bx-copy me-1"></i>Copy Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing modal if any
        const existingModal = document.getElementById('formDataModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Add modal to page and show it
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        const modal = new bootstrap.Modal(document.getElementById('formDataModal'));
        modal.show();
        
        // Clean up modal after it's hidden
        document.getElementById('formDataModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
    }
}

// Function to validate the form
function validateForm() {
    const form = document.getElementById('exampleForm');
    if (form) {
        const formData = new FormData(form);
        let validationResults = [];
        let isValid = true;
        
        // Check each file input
        const fileInputs = form.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            const files = input.files;
            const maxFiles = parseInt(input.getAttribute('data-max-files') || '10');
            const required = input.hasAttribute('required');
            
            if (required && files.length === 0) {
                validationResults.push(`❌ ${input.name}: This field is required`);
                isValid = false;
            } else if (files.length > maxFiles) {
                validationResults.push(`❌ ${input.name}: Too many files (${files.length}/${maxFiles})`);
                isValid = false;
            } else if (files.length > 0) {
                validationResults.push(`✅ ${input.name}: ${files.length} file(s) selected`);
                
                // Check individual files
                Array.from(files).forEach((file, index) => {
                    if (!file.type.startsWith('image/')) {
                        validationResults.push(`❌ ${input.name}[${index}]: Invalid file type (${file.type})`);
                        isValid = false;
                    }
                    if (file.size > 10 * 1024 * 1024) { // 10MB
                        validationResults.push(`❌ ${input.name}[${index}]: File too large (${formatFileSize(file.size)})`);
                        isValid = false;
                    }
                });
            } else {
                validationResults.push(`ℹ️ ${input.name}: No files selected (optional)`);
            }
        });
        
        const status = isValid ? 'Valid' : 'Invalid';
        const statusClass = isValid ? 'success' : 'danger';
        const statusIcon = isValid ? 'bx-check-circle' : 'bx-error-circle';
        
        // Show validation results in modal
        const modalHtml = `
            <div class="modal fade" id="validationModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bx ${statusIcon} me-2"></i>Form Validation Results
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-${statusClass} border-0">
                                <h6 class="alert-heading">Status: ${status}</h6>
                                <p class="mb-0">${isValid ? 'All validations passed!' : 'Some validations failed.'}</p>
                            </div>
                            <h6>Validation Details:</h6>
                            <ul class="list-unstyled">
                                ${validationResults.map(result => `<li class="mb-1">${result}</li>`).join('')}
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            ${isValid ? '<button type="button" class="btn btn-success" data-bs-dismiss="modal"><i class="bx bx-check me-1"></i>Proceed</button>' : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing modal if any
        const existingModal = document.getElementById('validationModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Add modal to page and show it
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        const modal = new bootstrap.Modal(document.getElementById('validationModal'));
        modal.show();
        
        // Clean up modal after it's hidden
        document.getElementById('validationModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
    }
}

// Helper function to copy text to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
        alert.style.top = '20px';
        alert.style.right = '20px';
        alert.style.zIndex = '9999';
        alert.innerHTML = `
            <i class="bx bx-check me-2"></i>Data copied to clipboard!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);
        
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
    });
}

function showFormData() {
    const form = document.getElementById('exampleForm');
    const formData = new FormData(form);
    
    let dataPreview = 'Form Data Preview:\n\n';
    
    for (let [key, value] of formData.entries()) {
        if (value instanceof File) {
            dataPreview += `${key}: ${value.name} (${value.type}, ${formatFileSize(value.size)})\n`;
        } else {
            dataPreview += `${key}: ${value}\n`;
        }
    }
    
    alert(dataPreview);
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Handle form submission
document.getElementById('exampleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Show what would be submitted
    let fileCount = 0;
    for (let [key, value] of formData.entries()) {
        if (value instanceof File) {
            fileCount++;
        }
    }
    
    alert(`Form would be submitted with ${fileCount} files selected.\n\nIn a real application, this would send the data to your backend for processing.`);
});

// Function to try a live example
function tryExample(type) {
    let message = '';
    let icon = '';
    let buttonClass = '';
    
    switch(type) {
        case 'single':
            message = 'Single image upload example would open a modal with a file picker limited to 1 image.';
            icon = 'bx-image';
            buttonClass = 'btn-primary';
            break;
        case 'multiple':
            message = 'Multiple image upload example would open a modal allowing selection of up to 10 images.';
            icon = 'bx-images';
            buttonClass = 'btn-info';
            break;
        case 'gallery':
            message = 'Gallery upload example would show a drag-and-drop area for bulk image uploads.';
            icon = 'bx-photo-album';
            buttonClass = 'btn-success';
            break;
    }
    
    // Create and show a modal
    const modalHtml = `
        <div class="modal fade" id="exampleModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bx ${icon} me-2"></i>Live Example - ${type.charAt(0).toUpperCase() + type.slice(1)} Upload
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="bx bx-info-circle me-2"></i>
                            ${message}
                        </div>
                        <p>This would typically include:</p>
                        <ul>
                            <li>File selection interface</li>
                            <li>Drag and drop functionality</li>
                            <li>Upload progress indicators</li>
                            <li>Image preview thumbnails</li>
                            <li>Validation feedback</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="/admin/image-upload-demo" class="btn ${buttonClass}">
                            <i class="bx bx-rocket me-1"></i>Try Real Demo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('exampleModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to page and show it
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('exampleModal'));
    modal.show();
    
    // Clean up modal after it's hidden
    document.getElementById('exampleModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Smooth scrolling for navigation links
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-link[href^="#"]');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);
            
            if (targetSection) {
                const offsetTop = targetSection.offsetTop - 100; // Account for fixed header
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Highlight current section in navigation
    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('div[id]');
        const navLinks = document.querySelectorAll('.nav-link[href^="#"]');
        
        let currentSection = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 150;
            const sectionHeight = section.offsetHeight;
            
            if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
                currentSection = '#' + section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === currentSection) {
                link.classList.add('active');
            }
        });
    });

    // Add hover effects to parameter rows
    const tableRows = document.querySelectorAll('table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
            this.style.transform = 'scale(1.01)';
            this.style.transition = 'all 0.2s ease';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
            this.style.transform = '';
        });
    });

    // Add click to expand effect for code blocks
    const codeBlocks = document.querySelectorAll('pre');
    codeBlocks.forEach(block => {
        block.style.cursor = 'pointer';
        block.title = 'Click to expand/collapse';
        
        block.addEventListener('click', function() {
            if (this.style.maxHeight && this.style.maxHeight !== 'none') {
                this.style.maxHeight = 'none';
                this.style.overflow = 'visible';
            } else {
                this.style.maxHeight = '300px';
                this.style.overflow = 'auto';
            }
        });
    });
});

// ============= BULK RESIZE FUNCTIONALITY =============
let selectedImages = [];
let resizeSizes = [];
let resizeResults = [];

// Load sample images for testing
function loadSampleImages() {
    const samplePaths = [
        'uploads/sample1.jpg',
        'uploads/sample2.png',
        'uploads/sample3.webp',
        'products/demo-image.jpg'
    ];
    
    document.getElementById('existingImagePaths').value = samplePaths.join('\n');
    updateImageSelection();
    
    showToast('Sample images loaded', 'success');
}

// Add preset size
function addPresetSize(width, height, name) {
    addResizeSize(width, height, name);
}

// Add resize preset
function addResizePreset() {
    const presets = [
        { name: 'Social Media Square', width: 1080, height: 1080 },
        { name: 'Instagram Story', width: 1080, height: 1920 },
        { name: 'Facebook Cover', width: 1200, height: 630 },
        { name: 'Twitter Header', width: 1500, height: 500 },
        { name: 'LinkedIn Post', width: 1200, height: 627 }
    ];
    
    const modalHtml = `
        <div class="modal fade" id="presetModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bx bx-plus me-2"></i>Add Resize Preset
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Popular Presets:</h6>
                        <div class="list-group">
                            ${presets.map(preset => `
                                <button type="button" class="list-group-item list-group-item-action" 
                                    onclick="addResizeSize(${preset.width}, ${preset.height}, '${preset.name}'); bootstrap.Modal.getInstance(document.getElementById('presetModal')).hide();">
                                    <strong>${preset.name}</strong>
                                    <br><small class="text-muted">${preset.width} x ${preset.height}</small>
                                </button>
                            `).join('')}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('presetModal');
    if (existingModal) existingModal.remove();
    
    // Add and show modal
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('presetModal'));
    modal.show();
    
    // Clean up
    document.getElementById('presetModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Add custom size input
function addCustomSize() {
    addResizeSize(800, 600, '');
}

// Add resize size to the container
function addResizeSize(width = 800, height = 600, name = '') {
    const container = document.getElementById('resizeSizesContainer');
    const index = resizeSizes.length;
    
    const sizeHtml = `
        <div class="border rounded p-3 mb-2" id="size-${index}">
            <div class="row g-2 align-items-center">
                <div class="col-md-3">
                    <input type="text" class="form-control form-control-sm" 
                        placeholder="Name (optional)" value="${name}"
                        onchange="updateSizeName(${index}, this.value)">
                </div>
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control" placeholder="Width" 
                            value="${width}" min="50" max="2000"
                            onchange="updateSizeWidth(${index}, this.value)">
                        <span class="input-group-text">px</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control" placeholder="Height" 
                            value="${height}" min="50" max="2000"
                            onchange="updateSizeHeight(${index}, this.value)">
                        <span class="input-group-text">px</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="btn-group btn-group-sm w-100">
                        <button type="button" class="btn btn-outline-secondary" 
                            onclick="aspectRatioLock(${index})" title="Lock Aspect Ratio">
                            <i class="bx bx-link"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger" 
                            onclick="removeSize(${index})" title="Remove">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-1">
                    <small class="text-muted">${(width/height).toFixed(2)}:1</small>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', sizeHtml);
    
    // Add to sizes array
    resizeSizes.push({
        name: name,
        width: parseInt(width),
        height: parseInt(height),
        locked: false
    });
}

// Update size functions
function updateSizeName(index, name) {
    if (resizeSizes[index]) {
        resizeSizes[index].name = name;
    }
}

function updateSizeWidth(index, width) {
    if (resizeSizes[index]) {
        resizeSizes[index].width = parseInt(width);
        if (resizeSizes[index].locked) {
            // Maintain aspect ratio
            const aspectRatio = resizeSizes[index].width / resizeSizes[index].height;
            const newHeight = Math.round(parseInt(width) / aspectRatio);
            resizeSizes[index].height = newHeight;
            document.querySelector(`#size-${index} input[placeholder="Height"]`).value = newHeight;
        }
        updateAspectRatio(index);
    }
}

function updateSizeHeight(index, height) {
    if (resizeSizes[index]) {
        resizeSizes[index].height = parseInt(height);
        if (resizeSizes[index].locked) {
            // Maintain aspect ratio
            const aspectRatio = resizeSizes[index].width / resizeSizes[index].height;
            const newWidth = Math.round(parseInt(height) * aspectRatio);
            resizeSizes[index].width = newWidth;
            document.querySelector(`#size-${index} input[placeholder="Width"]`).value = newWidth;
        }
        updateAspectRatio(index);
    }
}

function updateAspectRatio(index) {
    const ratio = (resizeSizes[index].width / resizeSizes[index].height).toFixed(2);
    document.querySelector(`#size-${index} .text-muted`).textContent = `${ratio}:1`;
}

function aspectRatioLock(index) {
    resizeSizes[index].locked = !resizeSizes[index].locked;
    const button = document.querySelector(`#size-${index} .bx-link`).parentElement;
    
    if (resizeSizes[index].locked) {
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-warning');
        button.title = 'Unlock Aspect Ratio';
    } else {
        button.classList.remove('btn-warning');
        button.classList.add('btn-outline-secondary');
        button.title = 'Lock Aspect Ratio';
    }
}

function removeSize(index) {
    document.getElementById(`size-${index}`).remove();
    resizeSizes.splice(index, 1);
    
    // Re-index remaining items
    updateSizeIndexes();
}

function updateSizeIndexes() {
    const container = document.getElementById('resizeSizesContainer');
    const items = container.children;
    
    Array.from(items).forEach((item, newIndex) => {
        item.id = `size-${newIndex}`;
        // Update all event handlers
        const inputs = item.querySelectorAll('input');
        const buttons = item.querySelectorAll('button');
        
        inputs[0].setAttribute('onchange', `updateSizeName(${newIndex}, this.value)`);
        inputs[1].setAttribute('onchange', `updateSizeWidth(${newIndex}, this.value)`);
        inputs[2].setAttribute('onchange', `updateSizeHeight(${newIndex}, this.value)`);
        
        buttons[0].setAttribute('onclick', `aspectRatioLock(${newIndex})`);
        buttons[1].setAttribute('onclick', `removeSize(${newIndex})`);
    });
}

// Handle file selection for bulk resize
document.getElementById('bulkResizeImages').addEventListener('change', function(e) {
    selectedImages = Array.from(e.target.files);
    updateImageSelection();
});

// Handle existing image paths
document.getElementById('existingImagePaths').addEventListener('input', function(e) {
    updateImageSelection();
});

function updateImageSelection() {
    const preview = document.getElementById('selectedImagesPreview');
    const container = document.getElementById('imagePreviewContainer');
    
    // Clear previous content
    container.innerHTML = '';
    
    // Get selected files
    const files = selectedImages;
    const pathsText = document.getElementById('existingImagePaths').value.trim();
    const paths = pathsText ? pathsText.split('\n').filter(p => p.trim()) : [];
    
    if (files.length > 0 || paths.length > 0) {
        preview.style.display = 'block';
        
        // Show file previews
        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'text-center';
                div.innerHTML = `
                    <img src="${e.target.result}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                    <div class="small text-truncate" style="width: 60px;">${file.name}</div>
                `;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
        
        // Show path items
        paths.forEach(path => {
            const div = document.createElement('div');
            div.className = 'text-center';
            div.innerHTML = `
                <div class="border rounded d-flex align-items-center justify-content-center" 
                    style="width: 60px; height: 60px; background: #f8f9fa;">
                    <i class="bx bx-image fs-4 text-muted"></i>
                </div>
                <div class="small text-truncate" style="width: 60px;" title="${path}">${path.split('/').pop()}</div>
            `;
            container.appendChild(div);
        });
    } else {
        preview.style.display = 'none';
    }
}

// Quality slider
document.getElementById('qualitySlider').addEventListener('input', function(e) {
    document.getElementById('qualityValue').textContent = e.target.value;
});

// Start bulk resize process
async function startBulkResize() {
    const files = selectedImages;
    const pathsText = document.getElementById('existingImagePaths').value.trim();
    const paths = pathsText ? pathsText.split('\n').filter(p => p.trim()) : [];
    
    if (files.length === 0 && paths.length === 0) {
        showToast('Please select images or enter image paths', 'error');
        return;
    }
    
    if (resizeSizes.length === 0) {
        showToast('Please add at least one resize dimension', 'error');
        return;
    }
    
    // Show progress section
    document.getElementById('resizeProgressSection').style.display = 'block';
    document.getElementById('resizeResultsSection').style.display = 'none';
    document.getElementById('bulkResizeBtn').disabled = true;
    
    // Reset progress
    updateProgress(0, 'Preparing to resize images...');
    clearResizeLog();
    
    try {
        if (files.length > 0) {
            // Handle uploaded files
            await processUploadedFiles(files);
        } else {
            // Handle existing paths
            await processExistingPaths(paths);
        }
    } catch (error) {
        logToResize(`Error: ${error.message}`, 'error');
        showToast('Resize operation failed', 'error');
    } finally {
        document.getElementById('bulkResizeBtn').disabled = false;
    }
}

async function processUploadedFiles(files) {
    const formData = new FormData();
    
    files.forEach(file => {
        formData.append('images[]', file);
    });
    
    // Append sizes array properly for FormData
    resizeSizes.forEach((size, index) => {
        formData.append(`sizes[${index}][width]`, size.width);
        formData.append(`sizes[${index}][height]`, size.height);
        if (size.name) {
            formData.append(`sizes[${index}][name]`, size.name);
        }
    });
    
    formData.append('quality', document.getElementById('qualitySlider').value);
    formData.append('format', document.getElementById('outputFormat').value);
    
    logToResize('Uploading and resizing files...', 'info');
    updateProgress(25, 'Uploading files...');
    
    try {
        const response = await fetch('/admin/image-upload/resize', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        // Check if response is ok
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            const text = await response.text();
            console.error('Non-JSON response received:', text);
            throw new Error("Server returned non-JSON response. Check server logs for errors.");
        }
        
        const result = await response.json();
        
        if (result.success) {
            updateProgress(100, 'Resize completed successfully!');
            logToResize(`Successfully resized ${result.count} image variations`, 'success');
            showResizeResults(result.data);
        } else {
            throw new Error(result.message || 'Resize failed');
        }
    } catch (error) {
        logToResize(`Upload error: ${error.message}`, 'error');
        throw error;
    }
}

async function processExistingPaths(paths) {
    const payload = {
        image_paths: paths,
        sizes: resizeSizes,
        quality: parseInt(document.getElementById('qualitySlider').value),
        format: document.getElementById('outputFormat').value
    };
    
    logToResize(`Processing ${paths.length} existing images...`, 'info');
    updateProgress(25, 'Processing existing images...');
    
    try {
        const response = await fetch('/admin/image-upload/resize', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
        });
        
        const result = await response.json();
        
        if (result.success) {
            updateProgress(100, 'Resize completed successfully!');
            logToResize(`Successfully resized ${result.count} image variations`, 'success');
            showResizeResults(result.data);
        } else {
            throw new Error(result.message || 'Resize failed');
        }
    } catch (error) {
        logToResize(`Processing error: ${error.message}`, 'error');
        throw error;
    }
}

function updateProgress(percent, message) {
    const progressBar = document.getElementById('resizeProgressBar');
    const statusText = document.getElementById('resizeStatusText');
    
    progressBar.style.width = `${percent}%`;
    progressBar.textContent = `${percent}%`;
    statusText.textContent = message;
}

function logToResize(message, type = 'info') {
    const log = document.getElementById('resizeLog');
    const timestamp = new Date().toLocaleTimeString();
    const icon = type === 'error' ? '❌' : type === 'success' ? '✅' : 'ℹ️';
    
    const logEntry = document.createElement('div');
    logEntry.className = `text-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'}`;
    logEntry.innerHTML = `[${timestamp}] ${icon} ${message}`;
    
    log.appendChild(logEntry);
    log.scrollTop = log.scrollHeight;
}

function clearResizeLog() {
    document.getElementById('resizeLog').innerHTML = '';
}

function showResizeResults(results) {
    resizeResults = results;
    
    const resultsSection = document.getElementById('resizeResultsSection');
    const resultsContent = document.getElementById('resizeResultsContent');
    
    let html = `
        <div class="row">
            <div class="col-md-6">
                <h6>Summary</h6>
                <ul class="list-unstyled">
                    <li><strong>Total Variations:</strong> ${results.length}</li>
                    <li><strong>Original Images:</strong> ${new Set(results.map(r => r.original)).size}</li>
                    <li><strong>Size Variants:</strong> ${new Set(results.map(r => r.size)).size}</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>File Sizes</h6>
                <ul class="list-unstyled">
                    <li><strong>Total Size:</strong> ${formatFileSize(results.reduce((sum, r) => sum + (r.file_size || 0), 0))}</li>
                    <li><strong>Average Size:</strong> ${formatFileSize(results.reduce((sum, r) => sum + (r.file_size || 0), 0) / results.length)}</li>
                </ul>
            </div>
        </div>
        
        <h6 class="mt-3">Generated Images</h6>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Preview</th>
                        <th>Original</th>
                        <th>Size</th>
                        <th>Dimensions</th>
                        <th>File Size</th>
                        <th>Path</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    results.forEach((result, index) => {
        html += `
            <tr>
                <td>
                    <img src="${result.url || '/storage/' + result.path}" 
                        class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover;">
                </td>
                <td class="small">${result.original.split('/').pop()}</td>
                <td><span class="badge bg-primary">${result.size}</span></td>
                <td class="small">${result.width}×${result.height}</td>
                <td class="small">${formatFileSize(result.file_size || 0)}</td>
                <td class="small font-monospace">${result.path}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" onclick="viewImage('${result.url || '/storage/' + result.path}')" title="View">
                            <i class="bx bx-show"></i>
                        </button>
                        <button class="btn btn-outline-success" onclick="copyPath('${result.path}')" title="Copy Path">
                            <i class="bx bx-copy"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    resultsContent.innerHTML = html;
    resultsSection.style.display = 'block';
    
    // Hide progress section
    document.getElementById('resizeProgressSection').style.display = 'none';
}

function viewImage(url) {
    window.open(url, '_blank');
}

function copyPath(path) {
    navigator.clipboard.writeText(path).then(() => {
        showToast('Path copied to clipboard', 'success');
    });
}

function downloadResizedImages() {
    if (!resizeResults || resizeResults.length === 0) {
        showToast('No images to download', 'error');
        return;
    }
    
    // Show download options modal
    const modalHtml = `
        <div class="modal fade" id="downloadModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bx bx-download me-2"></i>Download Options
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info border-0">
                            <i class="bx bx-info-circle me-2"></i>
                            You have <strong>${resizeResults.length}</strong> resized images ready for download.
                        </div>
                        
                        <div class="d-grid gap-3">
                            <button class="btn btn-primary btn-lg" onclick="downloadAsZip()">
                                <i class="bx bx-archive me-2"></i>
                                Download as ZIP File
                                <br><small class="text-white-50">All images in one compressed file</small>
                            </button>
                            
                            <button class="btn btn-outline-primary btn-lg" onclick="downloadIndividually()">
                                <i class="bx bx-download me-2"></i>
                                Download Individually
                                <br><small class="text-muted">Download each image separately</small>
                            </button>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-6">
                                    <button class="btn btn-outline-secondary w-100" onclick="copyAllUrls()">
                                        <i class="bx bx-link me-1"></i>Copy URLs
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-outline-secondary w-100" onclick="copyResultsPaths()">
                                        <i class="bx bx-copy me-1"></i>Copy Paths
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('downloadModal');
    if (existingModal) existingModal.remove();
    
    // Add and show modal
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('downloadModal'));
    modal.show();
    
    // Clean up
    document.getElementById('downloadModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Download all images as ZIP (requires backend endpoint)
async function downloadAsZip() {
    showToast('Preparing ZIP file...', 'info');
    
    try {
        const paths = resizeResults.map(r => r.path);
        
        const response = await fetch('/admin/image-upload/download-zip', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ paths: paths })
        });
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `resized_images_${new Date().getTime()}.zip`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            
            showToast('ZIP file downloaded successfully!', 'success');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('downloadModal'));
            if (modal) modal.hide();
        } else {
            throw new Error('Failed to create ZIP file');
        }
    } catch (error) {
        console.error('ZIP download error:', error);
        showToast('ZIP download failed. Trying individual downloads...', 'warning');
        downloadIndividually();
    }
}

// Download each image individually
function downloadIndividually() {
    if (resizeResults.length === 0) {
        showToast('No images to download', 'error');
        return;
    }
    
    let downloadCount = 0;
    const totalImages = resizeResults.length;
    
    showToast(`Starting download of ${totalImages} images...`, 'info');
    
    // Close modal first
    const modal = bootstrap.Modal.getInstance(document.getElementById('downloadModal'));
    if (modal) modal.hide();
    
    // Create progress toast
    const progressToast = document.createElement('div');
    progressToast.id = 'downloadProgressToast';
    progressToast.className = 'toast show position-fixed';
    progressToast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    progressToast.innerHTML = `
        <div class="toast-header">
            <i class="bx bx-download me-2 text-primary"></i>
            <strong class="me-auto">Downloading Images</strong>
            <button type="button" class="btn-close" onclick="this.closest('.toast').remove()"></button>
        </div>
        <div class="toast-body">
            <div class="progress mb-2">
                <div class="progress-bar" id="downloadProgressBar" style="width: 0%">0%</div>
            </div>
            <small id="downloadStatus">Preparing downloads...</small>
        </div>
    `;
    document.body.appendChild(progressToast);
    
    // Function to update progress
    function updateDownloadProgress(current, total) {
        const percent = Math.round((current / total) * 100);
        const progressBar = document.getElementById('downloadProgressBar');
        const status = document.getElementById('downloadStatus');
        
        if (progressBar && status) {
            progressBar.style.width = `${percent}%`;
            progressBar.textContent = `${percent}%`;
            status.textContent = `Downloaded ${current} of ${total} images`;
        }
    }
    
    // Download each image with a small delay to prevent overwhelming the browser
    resizeResults.forEach((result, index) => {
        setTimeout(() => {
            const url = result.url || `/storage/${result.path}`;
            const filename = result.filename || `resized_image_${index + 1}.jpg`;
            
            // Create temporary download link
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            a.style.display = 'none';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            
            downloadCount++;
            updateDownloadProgress(downloadCount, totalImages);
            
            // When all downloads are complete
            if (downloadCount === totalImages) {
                setTimeout(() => {
                    const toast = document.getElementById('downloadProgressToast');
                    if (toast) toast.remove();
                    showToast(`Successfully downloaded ${totalImages} images!`, 'success');
                }, 1000);
            }
        }, index * 200); // 200ms delay between downloads
    });
}

// Copy all image URLs
function copyAllUrls() {
    const urls = resizeResults.map(r => r.url || `/storage/${r.path}`).join('\n');
    navigator.clipboard.writeText(urls).then(() => {
        showToast('All image URLs copied to clipboard', 'success');
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('downloadModal'));
        if (modal) modal.hide();
    });
}

function copyResultsPaths() {
    const paths = resizeResults.map(r => r.path).join('\n');
    navigator.clipboard.writeText(paths).then(() => {
        showToast('All paths copied to clipboard', 'success');
    });
}

function clearBulkResize() {
    // Clear file input
    document.getElementById('bulkResizeImages').value = '';
    document.getElementById('existingImagePaths').value = '';
    
    // Clear sizes
    document.getElementById('resizeSizesContainer').innerHTML = '';
    resizeSizes = [];
    selectedImages = [];
    
    // Hide sections
    document.getElementById('selectedImagesPreview').style.display = 'none';
    document.getElementById('resizeProgressSection').style.display = 'none';
    document.getElementById('resizeResultsSection').style.display = 'none';
    
    // Reset quality slider
    document.getElementById('qualitySlider').value = 85;
    document.getElementById('qualityValue').textContent = '85';
    
    // Reset format
    document.getElementById('outputFormat').value = 'same';
    
    showToast('Bulk resize cleared', 'success');
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 5000);
}

// Initialize with one default size
document.addEventListener('DOMContentLoaded', function() {
    // Add a default size when page loads
    setTimeout(() => {
        addResizeSize(800, 600, 'Default');
    }, 500);
});
</script>
@endpush
