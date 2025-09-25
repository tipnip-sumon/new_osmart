@extends('admin.layouts.app')

@section('title', 'Image Upload Demo')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Image Upload Demo</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Image Upload Demo</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Start::row-1 -->
        <div class="row">
            <div class="col-xl-6">
                <!-- Single Image Upload -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Single Image Upload</div>
                    </div>
                    <div class="card-body">
                        <form id="singleUploadForm" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Select Image</label>
                                <input type="file" class="form-control" name="image" accept="image/*" required>
                                <small class="text-muted">Supported formats: JPEG, PNG, JPG, GIF, WebP (Max: 10MB)</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Folder (Optional)</label>
                                <input type="text" class="form-control" name="folder" placeholder="uploads" value="demo">
                                <small class="text-muted">Images will be stored in this folder</small>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-upload-line me-2"></i>Upload Image
                            </button>
                        </form>
                        
                        <!-- Upload Result -->
                        <div id="singleUploadResult" class="mt-3" style="display: none;">
                            <div class="alert alert-success">
                                <strong>Upload Successful!</strong>
                                <div id="singleImagePreview"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <!-- Multiple Image Upload -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Multiple Image Upload</div>
                    </div>
                    <div class="card-body">
                        <form id="multipleUploadForm" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Select Images</label>
                                <input type="file" class="form-control" name="images[]" accept="image/*" multiple required>
                                <small class="text-muted">Select up to 10 images at once</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Folder (Optional)</label>
                                <input type="text" class="form-control" name="folder" placeholder="uploads" value="demo">
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="ri-upload-line me-2"></i>Upload Images
                            </button>
                        </form>
                        
                        <!-- Upload Result -->
                        <div id="multipleUploadResult" class="mt-3" style="display: none;">
                            <div class="alert alert-success">
                                <strong>Upload Successful!</strong>
                                <div id="multipleImagePreview"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End::row-1 -->

        <!-- Image Management Tools -->
        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Image Management Tools</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center p-3 border rounded">
                                    <i class="ri-image-line fs-2 text-primary mb-2"></i>
                                    <h6>Resize Images</h6>
                                    <p class="text-muted small">Resize images to specific dimensions</p>
                                    <button class="btn btn-outline-primary btn-sm" onclick="resizeDemo()">
                                        Try Resize
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 border rounded">
                                    <i class="ri-thumbnail-line fs-2 text-success mb-2"></i>
                                    <h6>Generate Thumbnails</h6>
                                    <p class="text-muted small">Create thumbnails for images</p>
                                    <button class="btn btn-outline-success btn-sm" onclick="thumbnailDemo()">
                                        Generate Thumbnails
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 border rounded">
                                    <i class="ri-speed-line fs-2 text-warning mb-2"></i>
                                    <h6>Optimize Images</h6>
                                    <p class="text-muted small">Compress and optimize images</p>
                                    <button class="btn btn-outline-warning btn-sm" onclick="optimizeDemo()">
                                        Optimize Images
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 border rounded">
                                    <i class="ri-information-line fs-2 text-info mb-2"></i>
                                    <h6>Image Info</h6>
                                    <p class="text-muted small">Get detailed image information</p>
                                    <button class="btn btn-outline-info btn-sm" onclick="imageInfoDemo()">
                                        Get Image Info
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Documentation -->
        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">API Documentation</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Endpoint</th>
                                        <th>Method</th>
                                        <th>Description</th>
                                        <th>Parameters</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>/admin/image-upload/single</code></td>
                                        <td><span class="badge bg-primary">POST</span></td>
                                        <td>Upload a single image</td>
                                        <td><code>image</code>, <code>folder</code> (optional)</td>
                                    </tr>
                                    <tr>
                                        <td><code>/admin/image-upload/multiple</code></td>
                                        <td><span class="badge bg-primary">POST</span></td>
                                        <td>Upload multiple images</td>
                                        <td><code>images[]</code>, <code>folder</code> (optional)</td>
                                    </tr>
                                    <tr>
                                        <td><code>/admin/image-upload/resize</code></td>
                                        <td><span class="badge bg-primary">POST</span></td>
                                        <td>Resize images</td>
                                        <td><code>images[]</code>, <code>width</code>, <code>height</code></td>
                                    </tr>
                                    <tr>
                                        <td><code>/admin/image-upload/thumbnails</code></td>
                                        <td><span class="badge bg-primary">POST</span></td>
                                        <td>Generate thumbnails</td>
                                        <td><code>images[]</code>, <code>sizes[]</code></td>
                                    </tr>
                                    <tr>
                                        <td><code>/admin/image-upload/optimize</code></td>
                                        <td><span class="badge bg-primary">POST</span></td>
                                        <td>Optimize images</td>
                                        <td><code>images[]</code>, <code>quality</code></td>
                                    </tr>
                                    <tr>
                                        <td><code>/admin/image-upload/info</code></td>
                                        <td><span class="badge bg-success">GET</span></td>
                                        <td>Get image information</td>
                                        <td><code>path</code></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Global functions for demo buttons - full functionality
window.resizeDemo = function() {
    // Show modal for resize options
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Resize Images</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="resizeForm">
                        <div class="mb-3">
                            <label class="form-label">Select Images</label>
                            <input type="file" class="form-control" name="images[]" accept="image/*" multiple required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Width (px)</label>
                                <input type="number" class="form-control" name="width" value="800" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Height (px)</label>
                                <input type="number" class="form-control" name="height" value="600" required>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Maintain Aspect Ratio</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="maintain_ratio" checked>
                                <label class="form-check-label">Yes, maintain aspect ratio</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="processResize()">Resize Images</button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    new bootstrap.Modal(modal).show();
};

window.thumbnailDemo = function() {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Generate Thumbnails</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="thumbnailForm">
                        <div class="mb-3">
                            <label class="form-label">Select Images</label>
                            <input type="file" class="form-control" name="images[]" accept="image/*" multiple required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Thumbnail Sizes</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sizes" value="150x150" checked>
                                <label class="form-check-label">Small (150x150)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sizes" value="300x300" checked>
                                <label class="form-check-label">Medium (300x300)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sizes" value="500x500">
                                <label class="form-check-label">Large (500x500)</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="processThumbnails()">Generate Thumbnails</button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    new bootstrap.Modal(modal).show();
};

window.optimizeDemo = function() {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Optimize Images</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="optimizeForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Select Images</label>
                            <input type="file" class="form-control" name="images[]" accept="image/*" multiple required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quality (1-100)</label>
                            <input type="range" class="form-range" name="quality" min="1" max="100" value="85" oninput="document.getElementById('qualityValue').textContent = this.value">
                            <div class="text-center">
                                <span id="qualityValue">85</span>%
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Output Folder</label>
                            <input type="text" class="form-control" name="folder" value="optimized" placeholder="Output folder name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Optimization Options</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="progressive" checked>
                                <label class="form-check-label">Progressive JPEG</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="strip_metadata" checked>
                                <label class="form-check-label">Strip Metadata</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" onclick="processOptimize()">Optimize Images</button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    new bootstrap.Modal(modal).show();
};

window.imageInfoDemo = function() {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Get Image Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="imageInfoForm">
                        <div class="mb-3">
                            <label class="form-label">Select Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*" required onchange="previewImageForInfo(this)">
                        </div>
                        <div id="imagePreview" class="mb-3" style="display: none;">
                            <img id="previewImg" class="img-thumbnail" style="max-width: 300px;">
                        </div>
                        <div id="imageInfoResult" class="mt-3" style="display: none;">
                            <!-- Image info will be displayed here -->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info" onclick="processImageInfo()">Get Image Info</button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    new bootstrap.Modal(modal).show();
};

// Processing functions
window.processResize = function() {
    const form = document.getElementById('resizeForm');
    const formData = new FormData(form);
    
    // Handle checkbox properly - FormData doesn't include unchecked checkboxes
    const maintainRatioCheckbox = form.querySelector('input[name="maintain_ratio"]');
    if (maintainRatioCheckbox && maintainRatioCheckbox.checked) {
        formData.set('maintain_ratio', '1');
    } else {
        formData.set('maintain_ratio', '0');
    }
    
    // Add folder if not set
    if (!formData.has('folder')) {
        formData.set('folder', 'resized');
    }
    
    fetch('{{ route("admin.image-upload.resize") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showResultModal('Resize Complete', 'Images have been successfully resized!', data.data);
        } else {
            alert('Resize failed: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Resize failed: ' + error.message);
    });
};

window.processThumbnails = function() {
    const form = document.getElementById('thumbnailForm');
    const formData = new FormData(form);
    
    // Get selected sizes
    const sizeCheckboxes = form.querySelectorAll('input[name="sizes"]:checked');
    const sizes = Array.from(sizeCheckboxes).map(cb => cb.value);
    
    sizes.forEach(size => {
        formData.append('sizes[]', size);
    });
    
    fetch('{{ route("admin.image-upload.thumbnails") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Thumbnail response:', data); // Debug log
        if (data.success) {
            showResultModal('Thumbnails Generated', 'Thumbnails have been successfully generated!', data.data);
        } else {
            alert('Thumbnail generation failed: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Thumbnail generation failed: ' + error.message);
    });
};

window.processOptimize = function() {
    const form = document.getElementById('optimizeForm');
    const formData = new FormData(form);
    
    fetch('{{ route("admin.image-upload.optimize") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Non-JSON response received:', text);
                throw new Error(`HTTP ${response.status}: ${text.substring(0, 100)}...`);
            });
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Optimization response:', data);
        if (data.success) {
            showResultModal('Optimization Complete', 'Images have been successfully optimized!', data.data);
        } else {
            alert('Optimization failed: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Optimization failed: ' + error.message);
    });
};

window.processImageInfo = function() {
    const form = document.getElementById('imageInfoForm');
    const fileInput = form.querySelector('input[type="file"]');
    
    if (!fileInput.files[0]) {
        alert('Please select an image first.');
        return;
    }
    
    const formData = new FormData();
    formData.append('image', fileInput.files[0]);
    
    fetch('{{ route("admin.image-upload.info") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayImageInfo(data.data);
        } else {
            alert('Failed to get image info: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to get image info: ' + error.message);
    });
};

// Helper functions
window.previewImageForInfo = function(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            const img = document.getElementById('previewImg');
            img.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
};

window.displayImageInfo = function(data) {
    const resultDiv = document.getElementById('imageInfoResult');
    resultDiv.innerHTML = `
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Image Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr><td><strong>File Name:</strong></td><td>${data.filename || 'N/A'}</td></tr>
                            <tr><td><strong>File Size:</strong></td><td>${formatFileSize(data.size || 0)}</td></tr>
                            <tr><td><strong>Dimensions:</strong></td><td>${data.width || 0} x ${data.height || 0} px</td></tr>
                            <tr><td><strong>MIME Type:</strong></td><td>${data.mime_type || 'N/A'}</td></tr>
                            <tr><td><strong>Extension:</strong></td><td>${data.extension || 'N/A'}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr><td><strong>Color Space:</strong></td><td>${data.color_space || 'N/A'}</td></tr>
                            <tr><td><strong>Channels:</strong></td><td>${data.channels || 'N/A'}</td></tr>
                            <tr><td><strong>Bit Depth:</strong></td><td>${data.bit_depth || 'N/A'}</td></tr>
                            <tr><td><strong>Compression:</strong></td><td>${data.compression || 'N/A'}</td></tr>
                            <tr><td><strong>Aspect Ratio:</strong></td><td>${data.aspect_ratio || 'N/A'}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    `;
    resultDiv.style.display = 'block';
};

window.showResultModal = function(title, message, data) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">${title}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success">${message}</div>
                    <div class="row">
                        ${data.map(item => `
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <img src="${item.url || item.path}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <small class="text-muted">${item.filename || item.path}</small><br>
                                        <small class="text-success">${formatFileSize(item.file_size || item.size || item.optimized_size || 0)}</small>
                                        ${item.size ? `<br><small class="text-info">Size: ${item.size}</small>` : ''}
                                        ${item.dimensions ? `<br><small class="text-secondary">${item.dimensions.width}×${item.dimensions.height}</small>` : ''}
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    new bootstrap.Modal(modal).show();
};

window.formatFileSize = function(bytes) {
    if (!bytes || bytes === 0 || isNaN(bytes)) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

document.addEventListener('DOMContentLoaded', function() {
    // Single image upload
    document.getElementById('singleUploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="ri-loader-4-line me-2"></i>Uploading...';
        submitBtn.disabled = true;
        
        fetch('{{ route("admin.image-upload.single") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const resultDiv = document.getElementById('singleUploadResult');
                const previewDiv = document.getElementById('singleImagePreview');
                
                previewDiv.innerHTML = `
                    <div class="mt-2">
                        <img src="${data.data.urls.small || data.data.urls.original}" alt="Uploaded Image" style="max-width: 200px;" class="img-thumbnail">
                        <div class="mt-1">
                            <small><strong>Path:</strong> ${data.data.path}</small><br>
                            <small><strong>Size:</strong> ${data.data.size} bytes</small>
                        </div>
                    </div>
                `;
                
                resultDiv.style.display = 'block';
            } else {
                alert('Upload failed: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Upload failed: ' + error.message);
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
    
    // Multiple image upload
    document.getElementById('multipleUploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="ri-loader-4-line me-2"></i>Uploading...';
        submitBtn.disabled = true;
        
        fetch('{{ route("admin.image-upload.multiple") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const resultDiv = document.getElementById('multipleUploadResult');
                const previewDiv = document.getElementById('multipleImagePreview');
                
                let imagesHtml = '<div class="row mt-2">';
                data.data.forEach(image => {
                    imagesHtml += `
                        <div class="col-md-3 mb-2">
                            <img src="${image.urls.small || image.urls.original}" alt="Uploaded Image" class="img-thumbnail" style="width: 100%;">
                            <small class="text-muted">${image.path}</small>
                        </div>
                    `;
                });
                imagesHtml += '</div>';
                
                previewDiv.innerHTML = imagesHtml;
                resultDiv.style.display = 'block';
            } else {
                alert('Upload failed: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Upload failed: ' + error.message);
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
});
</script>
@endpush
