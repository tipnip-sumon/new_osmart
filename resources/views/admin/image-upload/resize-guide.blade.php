@extends('admin.layouts.app')

@section('title', 'Product Image Resize Guide')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Product Image Resize Guide</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Image Resize Guide</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <!-- Overview -->
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">How to Resize Product Images</div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6><i class="ri-information-line me-2"></i>Image Resize Functionality</h6>
                            <p class="mb-2">Our system provides multiple ways to resize product images:</p>
                            <ul class="mb-0">
                                <li><strong>Interactive Demo:</strong> Test resize functionality with a user-friendly interface</li>
                                <li><strong>API Integration:</strong> Direct API calls for custom implementations</li>
                                <li><strong>Component Integration:</strong> Built-in resize tools in form components</li>
                                <li><strong>Batch Processing:</strong> Resize multiple images at once</li>
                            </ul>
                        </div>

                        <!-- Quick Links -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <i class="ri-image-line fs-1 text-primary mb-2"></i>
                                        <h6>Try Interactive Demo</h6>
                                        <p class="text-muted small">Test the resize functionality with a live demo</p>
                                        <a href="{{ route('admin.image-upload.demo') }}" class="btn btn-primary btn-sm">
                                            <i class="ri-external-link-line me-1"></i>Open Demo
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <i class="ri-code-line fs-1 text-success mb-2"></i>
                                        <h6>Component Usage</h6>
                                        <p class="text-muted small">Learn how to use the image upload component</p>
                                        <a href="{{ route('admin.image-upload.usage') }}" class="btn btn-success btn-sm">
                                            <i class="ri-external-link-line me-1"></i>View Examples
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <i class="ri-settings-3-line fs-1 text-warning mb-2"></i>
                                        <h6>API Reference</h6>
                                        <p class="text-muted small">Complete API documentation</p>
                                        <button class="btn btn-warning btn-sm" onclick="scrollToSection('api-reference')">
                                            <i class="ri-arrow-down-line me-1"></i>View API Docs
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Methods Explanation -->
        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Resize Methods Explained</div>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="resizeMethodsAccordion">
                            <!-- Method 1: Interactive Demo -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#method1">
                                        <i class="ri-mouse-line me-2"></i>Method 1: Interactive Demo Interface
                                    </button>
                                </h2>
                                <div id="method1" class="accordion-collapse collapse show" data-bs-parent="#resizeMethodsAccordion">
                                    <div class="accordion-body">
                                        <h6>Step-by-Step Guide:</h6>
                                        <ol>
                                            <li><strong>Navigate to Demo:</strong> Go to <code>/admin/image-upload-demo</code></li>
                                            <li><strong>Find Resize Tool:</strong> Look for "Image Management Tools" section</li>
                                            <li><strong>Click "Try Resize":</strong> This opens a modal with resize options</li>
                                            <li><strong>Select Images:</strong> Choose one or more images to resize</li>
                                            <li><strong>Set Dimensions:</strong> Enter desired width and height in pixels</li>
                                            <li><strong>Aspect Ratio:</strong> Choose whether to maintain original proportions</li>
                                            <li><strong>Process:</strong> Click "Resize Images" to start processing</li>
                                        </ol>
                                        
                                        <div class="alert alert-success mt-3">
                                            <strong>Best Practices:</strong>
                                            <ul class="mb-0">
                                                <li>For product images, use standard sizes like 800x600 or 1200x900</li>
                                                <li>Keep aspect ratio checked to avoid image distortion</li>
                                                <li>Test with a few images first before batch processing</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Method 2: JavaScript API -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#method2">
                                        <i class="ri-code-box-line me-2"></i>Method 2: JavaScript API Integration
                                    </button>
                                </h2>
                                <div id="method2" class="accordion-collapse collapse" data-bs-parent="#resizeMethodsAccordion">
                                    <div class="accordion-body">
                                        <h6>Example Code:</h6>
                                        <pre class="bg-light p-3 rounded"><code>// Resize uploaded images
function resizeImages(files, width, height, maintainRatio = true) {
    const formData = new FormData();
    
    // Add image files
    files.forEach((file, index) => {
        formData.append('images[]', file);
    });
    
    // Add resize parameters
    formData.append('width', width);
    formData.append('height', height);
    formData.append('maintain_ratio', maintainRatio);
    formData.append('folder', 'products/resized');
    
    // Send API request
    fetch('/admin/image-upload/resize', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Resized images:', data.data);
            // Handle success - update UI, show preview, etc.
        } else {
            console.error('Resize failed:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}</code></pre>

                                        <h6 class="mt-3">Usage Example:</h6>
                                        <pre class="bg-light p-3 rounded"><code>// Get files from input
const fileInput = document.getElementById('productImages');
const files = Array.from(fileInput.files);

// Resize to standard product dimensions
resizeImages(files, 800, 600, true);</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Method 3: Component Integration -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#method3">
                                        <i class="ri-puzzle-line me-2"></i>Method 3: Reusable Component Integration
                                    </button>
                                </h2>
                                <div id="method3" class="accordion-collapse collapse" data-bs-parent="#resizeMethodsAccordion">
                                    <div class="accordion-body">
                                        <h6>Blade Component Usage:</h6>
                                        <pre class="bg-light p-3 rounded"><code>{{-- Include the image upload component in your form --}}
@include('admin.components.image-upload', [
    'name' => 'product_images[]',
    'multiple' => true,
    'label' => 'Product Images',
    'required' => true,
    'maxFiles' => 8,
    'folder' => 'products',
    'existing' => $product->images ?? []
])</code></pre>

                                        <p class="mt-3"><strong>Features included:</strong></p>
                                        <ul>
                                            <li>Built-in resize tools in the component</li>
                                            <li>Drag & drop functionality</li>
                                            <li>Image previews and management</li>
                                            <li>Bulk operations support</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Method 4: Batch Processing -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#method4">
                                        <i class="ri-stack-line me-2"></i>Method 4: Batch Processing Existing Images
                                    </button>
                                </h2>
                                <div id="method4" class="accordion-collapse collapse" data-bs-parent="#resizeMethodsAccordion">
                                    <div class="accordion-body">
                                        <h6>For Existing Images:</h6>
                                        <pre class="bg-light p-3 rounded"><code>// Batch resize existing images by their paths
function batchResizeExisting(imagePaths, sizes) {
    const formData = new FormData();
    
    // Add image paths
    imagePaths.forEach(path => {
        formData.append('image_paths[]', path);
    });
    
    // Add size configurations
    sizes.forEach((size, index) => {
        formData.append(`sizes[${index}][width]`, size.width);
        formData.append(`sizes[${index}][height]`, size.height);
    });
    
    fetch('/admin/image-upload/resize', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Batch resize completed:', data);
    });
}

// Example usage
const imagePaths = [
    'products/image1.jpg',
    'products/image2.jpg',
    'products/image3.jpg'
];

const sizes = [
    { width: 800, height: 600 },
    { width: 400, height: 300 },
    { width: 150, height: 150 }
];

batchResizeExisting(imagePaths, sizes);</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Reference -->
        <div class="row mt-4" id="api-reference">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">API Reference</div>
                    </div>
                    <div class="card-body">
                        <h6>Resize Images Endpoint</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Type</th>
                                        <th>Required</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>images[]</code></td>
                                        <td>File Array</td>
                                        <td>Yes*</td>
                                        <td>Image files to resize (for new uploads)</td>
                                    </tr>
                                    <tr>
                                        <td><code>image_paths[]</code></td>
                                        <td>String Array</td>
                                        <td>Yes*</td>
                                        <td>Paths to existing images (for batch resize)</td>
                                    </tr>
                                    <tr>
                                        <td><code>width</code></td>
                                        <td>Integer</td>
                                        <td>Yes</td>
                                        <td>Target width in pixels (50-2000)</td>
                                    </tr>
                                    <tr>
                                        <td><code>height</code></td>
                                        <td>Integer</td>
                                        <td>Yes</td>
                                        <td>Target height in pixels (50-2000)</td>
                                    </tr>
                                    <tr>
                                        <td><code>maintain_ratio</code></td>
                                        <td>Boolean</td>
                                        <td>No</td>
                                        <td>Whether to maintain aspect ratio (default: true)</td>
                                    </tr>
                                    <tr>
                                        <td><code>folder</code></td>
                                        <td>String</td>
                                        <td>No</td>
                                        <td>Storage folder for resized images (default: 'resized')</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted">* Either <code>images[]</code> or <code>image_paths[]</code> is required, not both</small>

                        <h6 class="mt-4">Response Format:</h6>
                        <pre class="bg-light p-3 rounded"><code>{
    "success": true,
    "message": "Images resized successfully",
    "data": [
        {
            "original_name": "product1.jpg",
            "filename": "1693234567_product1.jpg",
            "path": "products/resized/1693234567_product1.jpg",
            "url": "http://localhost/storage/products/resized/1693234567_product1.jpg",
            "size": 45678,
            "dimensions": {
                "width": 800,
                "height": 600
            }
        }
    ],
    "count": 1
}</code></pre>

                        <h6 class="mt-4">Common Use Cases:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">Product Thumbnails</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Dimensions:</strong> 300x300px</p>
                                        <p><strong>Use:</strong> Product listing pages</p>
                                        <p><strong>Aspect Ratio:</strong> Maintain for best quality</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">Product Detail Images</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Dimensions:</strong> 800x600px or 1200x900px</p>
                                        <p><strong>Use:</strong> Product detail pages</p>
                                        <p><strong>Aspect Ratio:</strong> Maintain for clarity</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Demo Section -->
        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Quick Test - Resize Images</div>
                    </div>
                    <div class="card-body">
                        <form id="quickResizeForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Select Images</label>
                                        <input type="file" class="form-control" name="images[]" accept="image/*" multiple required>
                                        <small class="text-muted">Select one or more images to resize</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Width (px)</label>
                                        <input type="number" class="form-control" name="width" value="800" min="50" max="2000" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Height (px)</label>
                                        <input type="number" class="form-control" name="height" value="600" min="50" max="2000" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="maintain_ratio" checked>
                                        <label class="form-check-label">Maintain aspect ratio</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-image-line me-2"></i>Resize Images
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                        <div id="resizeResults" class="mt-4" style="display: none;">
                            <h6>Resized Images:</h6>
                            <div id="resizePreview" class="row">
                                <!-- Results will be displayed here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
function scrollToSection(sectionId) {
    document.getElementById(sectionId).scrollIntoView({ behavior: 'smooth' });
}

// Quick resize form handler
document.getElementById('quickResizeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="ri-loader-4-line me-2"></i>Resizing...';
    submitBtn.disabled = true;
    
    fetch('{{ route("admin.image-upload.resize") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayResizeResults(data.data);
        } else {
            alert('Resize failed: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Resize failed: ' + error.message);
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

function displayResizeResults(images) {
    const resultsDiv = document.getElementById('resizeResults');
    const previewDiv = document.getElementById('resizePreview');
    
    let html = '';
    images.forEach(image => {
        html += `
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="${image.url}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body p-2">
                        <h6 class="card-title small">${image.original_name}</h6>
                        <p class="card-text small mb-1">
                            <strong>Size:</strong> ${image.dimensions.width}x${image.dimensions.height}px<br>
                            <strong>File Size:</strong> ${formatFileSize(image.size)}
                        </p>
                        <a href="${image.url}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="ri-external-link-line me-1"></i>View Full Size
                        </a>
                    </div>
                </div>
            </div>
        `;
    });
    
    previewDiv.innerHTML = html;
    resultsDiv.style.display = 'block';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
</script>
@endsection
