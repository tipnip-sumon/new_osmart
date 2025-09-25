{{-- Product Image Upload Component --}}
@props([
    'name' => 'images[]',
    'multiple' => true,
    'existing' => [],
    'label' => 'Product Images',
    'required' => false,
    'maxFiles' => 10,
    'folder' => 'products'
])

<div class="image-upload-component" data-name="{{ $name }}" data-folder="{{ $folder }}">
    <div class="mb-3">
        <label class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
        
        {{-- File Input --}}
        <div class="file-input-wrapper">
            <input type="file" 
                class="form-control image-upload-input" 
                name="{{ $name }}" 
                accept="image/*" 
                @if($multiple) multiple @endif
                @if($required) required @endif
                data-max-files="{{ $maxFiles }}">
            <div class="form-text">
                Supported formats: JPEG, PNG, JPG, GIF, WebP (Max: 10MB each)
                @if($multiple)
                    <br>You can select up to {{ $maxFiles }} images at once.
                @endif
            </div>
        </div>
        
        {{-- Upload Progress --}}
        <div class="upload-progress mt-2" style="display: none;">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
            </div>
            <small class="upload-status text-muted">Uploading...</small>
        </div>
        
        {{-- Image Preview Area --}}
        <div class="image-preview-area mt-3">
            {{-- Existing Images --}}
            @if(!empty($existing))
                <div class="existing-images">
                    <h6 class="mb-2">Current Images:</h6>
                    <div class="row">
                        @foreach($existing as $index => $image)
                            <div class="col-md-3 mb-3">
                                <div class="image-item" data-index="{{ $index }}">
                                    <div class="image-wrapper position-relative">
                                        <img src="{{ is_array($image) ? asset('storage/' . $image['path']) : asset('storage/' . $image) }}" 
                                             class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
                                        <div class="image-overlay">
                                            <button type="button" class="btn btn-sm btn-danger remove-existing-image" 
                                                    data-index="{{ $index }}">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary view-image" 
                                                    data-src="{{ is_array($image) ? asset('storage/' . $image['path']) : asset('storage/' . $image) }}">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </div>
                                        <input type="hidden" name="existing_images[]" 
                                               value="{{ is_array($image) ? $image['path'] : $image }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            {{-- New Image Previews --}}
            <div class="new-images" style="display: none;">
                <h6 class="mb-2">New Images:</h6>
                <div class="row" id="newImagePreviews">
                    {{-- New image previews will be added here --}}
                </div>
            </div>
        </div>
        
        {{-- Image Management Tools --}}
        <div class="image-tools mt-3" style="display: none;">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary btn-sm bulk-resize">
                    <i class="ri-image-line me-1"></i>Resize All
                </button>
                <button type="button" class="btn btn-outline-success btn-sm bulk-thumbnail">
                    <i class="ri-thumbnail-line me-1"></i>Generate Thumbnails
                </button>
                <button type="button" class="btn btn-outline-warning btn-sm bulk-optimize">
                    <i class="ri-speed-line me-1"></i>Optimize All
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Add required CSS --}}
@push('styles')
<style>
.image-upload-component .image-wrapper {
    position: relative;
    overflow: hidden;
    border-radius: 0.375rem;
}

.image-upload-component .image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s;
}

.image-upload-component .image-wrapper:hover .image-overlay {
    opacity: 1;
}

.image-upload-component .upload-progress .progress {
    height: 0.5rem;
}

.image-upload-component .drag-over {
    border: 2px dashed #007bff;
    background-color: rgba(0, 123, 255, 0.1);
}
</style>
@endpush

{{-- Add required JavaScript --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize image upload components
    document.querySelectorAll('.image-upload-component').forEach(initImageUpload);
});

function initImageUpload(component) {
    const input = component.querySelector('.image-upload-input');
    const previewArea = component.querySelector('#newImagePreviews');
    const newImagesSection = component.querySelector('.new-images');
    const imageTools = component.querySelector('.image-tools');
    const progressBar = component.querySelector('.upload-progress');
    const folder = component.dataset.folder;
    
    // File input change handler
    input.addEventListener('change', function(e) {
        handleFileSelection(e.target.files, component);
    });
    
    // Drag and drop functionality
    const fileInputWrapper = component.querySelector('.file-input-wrapper');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileInputWrapper.addEventListener(eventName, preventDefaults, false);
    });
    
    ['dragenter', 'dragover'].forEach(eventName => {
        fileInputWrapper.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        fileInputWrapper.addEventListener(eventName, unhighlight, false);
    });
    
    fileInputWrapper.addEventListener('drop', handleDrop, false);
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    function highlight(e) {
        fileInputWrapper.classList.add('drag-over');
    }
    
    function unhighlight(e) {
        fileInputWrapper.classList.remove('drag-over');
    }
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFileSelection(files, component);
    }
    
    // Remove existing image handlers
    component.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-existing-image') || 
            e.target.closest('.remove-existing-image')) {
            e.preventDefault();
            const btn = e.target.closest('.remove-existing-image');
            const imageItem = btn.closest('.image-item');
            imageItem.remove();
        }
        
        if (e.target.classList.contains('view-image') || 
            e.target.closest('.view-image')) {
            e.preventDefault();
            const btn = e.target.closest('.view-image');
            const src = btn.dataset.src;
            showImageModal(src);
        }
    });
    
    // Bulk action handlers
    component.querySelector('.bulk-resize')?.addEventListener('click', function() {
        // Implement bulk resize
        alert('Bulk resize functionality - to be implemented');
    });
    
    component.querySelector('.bulk-thumbnail')?.addEventListener('click', function() {
        // Implement bulk thumbnail generation
        alert('Bulk thumbnail generation - to be implemented');
    });
    
    component.querySelector('.bulk-optimize')?.addEventListener('click', function() {
        // Implement bulk optimization
        alert('Bulk optimization - to be implemented');
    });
}

function handleFileSelection(files, component) {
    const maxFiles = parseInt(component.querySelector('.image-upload-input').dataset.maxFiles);
    const previewArea = component.querySelector('#newImagePreviews');
    const newImagesSection = component.querySelector('.new-images');
    const imageTools = component.querySelector('.image-tools');
    
    if (files.length > maxFiles) {
        alert(`You can only select up to ${maxFiles} images at once.`);
        return;
    }
    
    // Clear previous previews
    previewArea.innerHTML = '';
    
    // Show new images section
    if (files.length > 0) {
        newImagesSection.style.display = 'block';
        imageTools.style.display = 'block';
        
        Array.from(files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                createImagePreview(file, index, previewArea);
            }
        });
    }
}

function createImagePreview(file, index, container) {
    const reader = new FileReader();
    
    reader.onload = function(e) {
        const col = document.createElement('div');
        col.className = 'col-md-3 mb-3';
        
        col.innerHTML = `
            <div class="image-item" data-index="${index}">
                <div class="image-wrapper position-relative">
                    <img src="${e.target.result}" class="img-thumbnail" 
                         style="width: 100%; height: 150px; object-fit: cover;">
                    <div class="image-overlay">
                        <button type="button" class="btn btn-sm btn-danger remove-new-image" 
                                data-index="${index}">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-info get-info" 
                                data-index="${index}">
                            <i class="ri-information-line"></i>
                        </button>
                    </div>
                    <div class="mt-1">
                        <small class="text-muted">${file.name}</small><br>
                        <small class="text-success">${formatFileSize(file.size)}</small>
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(col);
        
        // Add remove handler
        col.querySelector('.remove-new-image').addEventListener('click', function() {
            col.remove();
            // Update file input if needed
        });
        
        // Add info handler
        col.querySelector('.get-info').addEventListener('click', function() {
            getFileInfo(file);
        });
    };
    
    reader.readAsDataURL(file);
}

function showImageModal(src) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="${src}" class="img-fluid" style="max-height: 70vh;">
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    new bootstrap.Modal(modal).show();
    
    modal.addEventListener('hidden.bs.modal', function() {
        modal.remove();
    });
}

function getFileInfo(file) {
    const formData = new FormData();
    formData.append('image', file);
    
    fetch('/admin/image-upload/info', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showImageInfoModal(data.data);
        } else {
            alert('Failed to get image info: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to get image info: ' + error.message);
    });
}

function showImageInfoModal(data) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Image Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm">
                        <tr><td><strong>File Name:</strong></td><td>${data.filename || 'N/A'}</td></tr>
                        <tr><td><strong>File Size:</strong></td><td>${formatFileSize(data.size || 0)}</td></tr>
                        <tr><td><strong>Dimensions:</strong></td><td>${data.width || 0} x ${data.height || 0} px</td></tr>
                        <tr><td><strong>MIME Type:</strong></td><td>${data.mime_type || 'N/A'}</td></tr>
                        <tr><td><strong>Extension:</strong></td><td>${data.extension || 'N/A'}</td></tr>
                        <tr><td><strong>Aspect Ratio:</strong></td><td>${data.aspect_ratio || 'N/A'}</td></tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    new bootstrap.Modal(modal).show();
    
    modal.addEventListener('hidden.bs.modal', function() {
        modal.remove();
    });
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
</script>
@endpush
