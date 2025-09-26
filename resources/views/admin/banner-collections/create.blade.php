@extends('admin.layouts.app')

@section('title', 'Create Banner Collection')

@push('styles')
<style>
.image-preview {
    max-width: 200px;
    max-height: 150px;
    object-fit: cover;
    border: 2px dashed #dee2e6;
    padding: 10px;
    border-radius: 8px;
}
.color-input {
    width: 60px;
    height: 40px;
    padding: 0;
    border: none;
    border-radius: 4px;
}
.form-section {
    margin-bottom: 1.5rem;
}
.form-section-header {
    margin-bottom: 1rem;
}
.form-section-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
}
.image-upload-container {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 2rem 1rem;
    text-align: center;
    background: #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
}
.image-upload-container:hover {
    border-color: #007bff;
    background: #e3f2fd;
}
.image-upload-container.dragover {
    border-color: #007bff;
    background: #e3f2fd;
    transform: scale(1.02);
}
.image-preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}
.preview-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
}
.preview-item img {
    width: 100%;
    height: 120px;
    object-fit: cover;
}
.preview-item .remove-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(220, 53, 69, 0.8);
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    font-size: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}
.preview-item .remove-btn:hover {
    background: rgba(220, 53, 69, 1);
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Banner Collection</h1>
        <a href="{{ route('admin.banner-collections.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
        </a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Create Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Banner Collection Details</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.banner-collections.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-lg-8">
                        <!-- Basic Information -->
                        <div class="form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Button Settings -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="button_text">Button Text <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('button_text') is-invalid @enderror" 
                                           id="button_text" name="button_text" value="{{ old('button_text', 'Shop Collection') }}" required>
                                    @error('button_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="button_url">Button URL</label>
                                    <input type="url" class="form-control @error('button_url') is-invalid @enderror" 
                                           id="button_url" name="button_url" value="{{ old('button_url') }}" 
                                           placeholder="https://example.com or /collections/summer">
                                    @error('button_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Countdown Settings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" 
                                           id="show_countdown" name="show_countdown" value="1" 
                                           {{ old('show_countdown') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="show_countdown">
                                        <strong>Show Countdown Timer</strong>
                                    </label>
                                </div>
                            </div>
                            <div class="card-body" id="countdown-settings" style="display: {{ old('show_countdown') ? 'block' : 'none' }};">
                                <div class="form-group">
                                    <label for="countdown_end_date">Countdown End Date & Time</label>
                                    <input type="datetime-local" class="form-control @error('countdown_end_date') is-invalid @enderror" 
                                           id="countdown_end_date" name="countdown_end_date" 
                                           value="{{ old('countdown_end_date') }}" 
                                           min="{{ now()->addHour()->format('Y-m-d\TH:i') }}">
                                    @error('countdown_end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Select when the countdown should end</small>
                                </div>
                            </div>
                        </div>

                        <!-- Styling -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Styling Options</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="background_color">Background Color</label>
                                            <div class="input-group">
                                                <input type="color" class="form-control color-input @error('background_color') is-invalid @enderror" 
                                                       id="background_color" name="background_color" value="{{ old('background_color', '#f8f9fa') }}">
                                                <div class="input-group-append">
                                                    <input type="text" class="form-control" id="bg_color_text" value="{{ old('background_color', '#f8f9fa') }}" readonly>
                                                </div>
                                            </div>
                                            @error('background_color')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="text_color">Text Color</label>
                                            <div class="input-group">
                                                <input type="color" class="form-control color-input @error('text_color') is-invalid @enderror" 
                                                       id="text_color" name="text_color" value="{{ old('text_color', '#333333') }}">
                                                <div class="input-group-append">
                                                    <input type="text" class="form-control" id="text_color_text" value="{{ old('text_color', '#333333') }}" readonly>
                                                </div>
                                            </div>
                                            @error('text_color')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Image Upload Section -->
                        <div class="form-section">
                            <div class="form-section-header">
                                <h4 class="form-section-title">
                                    <i class="ti ti-image text-info me-2"></i>
                                    Banner Images
                                </h4>
                                <small class="text-muted">Upload high-quality banner images</small>
                            </div>
                            <div class="form-section-body">
                                <div class="image-upload-container" id="image-upload-area">
                                    <div class="upload-content text-center">
                                        <i class="ti ti-cloud-upload" style="font-size: 32px; color: #6c757d; margin-bottom: 10px;"></i>
                                        <h6 class="mb-2">Drop images or click to upload</h6>
                                        <p class="text-muted small mb-3">JPG, PNG, GIF, WEBP (Max: 10MB)</p>
                                        <div class="d-grid gap-1">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('image-input').click()">
                                                <i class="ti ti-upload"></i> Choose Files
                                            </button>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-secondary" onclick="openResizeOptions()">
                                                    <i class="ti ti-crop"></i> Resize
                                                </button>
                                                <a href="{{ route('admin.image-upload.demo') }}" target="_blank" class="btn btn-outline-success">
                                                    <i class="ti ti-external-link"></i> Demo
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <input type="file" id="image-input" name="images[]" multiple accept="image/*" style="display: none;">
                                
                                <!-- Single Image Input for backward compatibility -->
                                <input type="file" id="single-image" name="image" accept="image/*" style="display: none;">
                                
                                <!-- Resize Options Panel -->
                                <div id="resize-options-panel" class="mt-3" style="display: none;">
                                    <div class="alert alert-info p-2">
                                        <h6 class="mb-2"><i class="ti ti-info-circle me-1"></i>Resize Options</h6>
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <label class="form-label small">Width</label>
                                                <input type="number" class="form-control form-control-sm" id="resize-width" value="800" min="50" max="2000">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small">Height</label>
                                                <input type="number" class="form-control form-control-sm" id="resize-height" value="600" min="50" max="2000">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small">Quality (%)</label>
                                                <input type="number" class="form-control form-control-sm" id="resize-quality" value="85" min="10" max="100">
                                            </div>
                                            <div class="col-6">
                                                <div class="form-check mt-3">
                                                    <input class="form-check-input" type="checkbox" id="maintain-ratio" checked>
                                                    <label class="form-check-label small" for="maintain-ratio">
                                                        Keep Ratio
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2 d-grid gap-1">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-primary" onclick="resizeUploadedImages()">
                                                    <i class="ti ti-crop"></i> Resize
                                                </button>
                                                <button type="button" class="btn btn-secondary" onclick="generateThumbnails()">
                                                    <i class="ti ti-photo"></i> Thumbnails
                                                </button>
                                                <button type="button" class="btn btn-warning" onclick="optimizeImages()">
                                                    <i class="ti ti-zap"></i> Optimize
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Image Preview Grid -->
                                <div id="image-preview-grid" class="image-preview-grid"></div>
                                
                                @error('images.*')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                                @error('image')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Settings -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Settings</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="sort_order">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Lower numbers appear first</small>
                                </div>

                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" 
                                           id="is_active" name="is_active" value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Banner Collection
                    </button>
                    <a href="{{ route('admin.banner-collections.index') }}" class="btn btn-secondary ml-2">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let uploadedFiles = [];
let resizeOptions = {
    width: 800,
    height: 600,
    quality: 85,
    maintainRatio: true
};

$(document).ready(function() {
    // Show/hide countdown settings
    $('#show_countdown').change(function() {
        if (this.checked) {
            $('#countdown-settings').slideDown();
        } else {
            $('#countdown-settings').slideUp();
        }
    });

    // Color picker sync
    $('#background_color').on('input', function() {
        $('#bg_color_text').val(this.value);
    });

    $('#text_color').on('input', function() {
        $('#text_color_text').val(this.value);
    });

    // Image upload handling
    setupImageUpload();
});

function setupImageUpload() {
    const uploadArea = document.getElementById('image-upload-area');
    const fileInput = document.getElementById('image-input');
    
    // Drag and drop functionality
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        handleFiles(files);
    });
    
    // Click to upload
    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });
    
    // File input change
    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });
}

function handleFiles(files) {
    Array.from(files).forEach(file => {
        if (file.type.startsWith('image/')) {
            uploadedFiles.push(file);
            createImagePreview(file);
        }
    });
}

function createImagePreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const previewGrid = document.getElementById('image-preview-grid');
        const previewItem = document.createElement('div');
        previewItem.className = 'preview-item';
        previewItem.innerHTML = `
            <img src="${e.target.result}" alt="Preview">
            <button type="button" class="remove-btn" onclick="removeImage(this, '${file.name}')">
                ×
            </button>
        `;
        previewGrid.appendChild(previewItem);
    };
    reader.readAsDataURL(file);
}

function removeImage(button, filename) {
    // Remove from uploaded files array
    uploadedFiles = uploadedFiles.filter(file => file.name !== filename);
    // Remove preview element
    button.parentElement.remove();
}

function openResizeOptions() {
    const panel = document.getElementById('resize-options-panel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}

function resizeUploadedImages() {
    // Get resize options
    resizeOptions.width = parseInt(document.getElementById('resize-width').value);
    resizeOptions.height = parseInt(document.getElementById('resize-height').value);
    resizeOptions.quality = parseInt(document.getElementById('resize-quality').value);
    resizeOptions.maintainRatio = document.getElementById('maintain-ratio').checked;
    
    console.log('Resize options:', resizeOptions);
    // Note: Actual resizing will be handled on the server side
    alert('Resize options saved. Images will be processed on upload.');
}

function generateThumbnails() {
    if (uploadedFiles.length === 0) {
        alert('Please upload images first.');
        return;
    }
    console.log('Generating thumbnails for', uploadedFiles.length, 'images');
    alert('Thumbnails will be automatically generated on upload.');
}

function optimizeImages() {
    if (uploadedFiles.length === 0) {
        alert('Please upload images first.');
        return;
    }
    console.log('Optimizing', uploadedFiles.length, 'images');
    alert('Images will be optimized during upload process.');
}
</script>
@endpush