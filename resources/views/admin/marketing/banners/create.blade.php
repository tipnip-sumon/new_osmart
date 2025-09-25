@extends('admin.layouts.app')

@section('title', 'Create Banner')

@section('styles')
<style>
    .banner-preview {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background: #f8f9fa;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }
    
    .banner-preview img {
        max-width: 100%;
        max-height: 150px;
        border-radius: 4px;
    }
    
    .upload-zone {
        border: 2px dashed #007bff;
        border-radius: 8px;
        padding: 40px;
        text-align: center;
        background: #f8f9ff;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .upload-zone:hover {
        background: #e3f2fd;
        border-color: #0056b3;
    }
    
    .upload-zone.dragover {
        background: #e3f2fd;
        border-color: #0056b3;
    }
</style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Create Banner</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.marketing.index') }}">Marketing</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.marketing.banners') }}">Banners</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>

        <form action="{{ route('admin.marketing.banners.store') }}" method="POST" enctype="multipart/form-data" id="bannerForm">
            @csrf
            <div class="row">
                <!-- Banner Details -->
                <div class="col-xl-8">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Banner Details</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Banner Title *</label>
                                        <input type="text" class="form-control" name="title" required>
                                        <div class="form-text">This will be used for identification purposes</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Banner Type *</label>
                                        <select class="form-control" name="type" required>
                                            <option value="">Select Type</option>
                                            <option value="image">Image Banner</option>
                                            <option value="text">Text Banner</option>
                                            <option value="html">HTML Banner</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="3" placeholder="Brief description of the banner"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Target URL</label>
                                        <input type="url" class="form-control" name="url" placeholder="https://example.com">
                                        <div class="form-text">Where users will be redirected when they click the banner</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Link Target</label>
                                        <select class="form-control" name="target">
                                            <option value="_self">Same Window</option>
                                            <option value="_blank">New Window</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Banner Content -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Banner Content</div>
                        </div>
                        <div class="card-body">
                            <!-- Image Upload -->
                            <div class="mb-4" id="imageUploadSection">
                                <label class="form-label">Banner Image</label>
                                <div class="upload-zone" id="uploadZone">
                                    <i class="bx bx-cloud-upload fs-24 text-primary mb-2"></i>
                                    <h6>Drag & Drop or Click to Upload</h6>
                                    <p class="text-muted mb-0">PNG, JPG, GIF up to 2MB</p>
                                    <input type="file" name="image" id="bannerImage" accept="image/*" style="display: none;">
                                </div>
                                <div class="banner-preview mt-3" id="imagePreview" style="display: none;">
                                    <img id="previewImg" src="" alt="Banner Preview">
                                    <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeImage()">
                                        <i class="bx bx-trash"></i> Remove
                                    </button>
                                </div>
                            </div>

                            <!-- Text Content (for text banners) -->
                            <div class="mb-3" id="textContentSection" style="display: none;">
                                <label class="form-label">Banner Text</label>
                                <textarea class="form-control" name="text_content" rows="4" placeholder="Enter banner text content"></textarea>
                            </div>

                            <!-- HTML Content (for HTML banners) -->
                            <div class="mb-3" id="htmlContentSection" style="display: none;">
                                <label class="form-label">HTML Content</label>
                                <textarea class="form-control" name="html_content" rows="8" placeholder="Enter HTML code for the banner"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Banner Settings -->
                <div class="col-xl-4">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Banner Settings</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Position *</label>
                                <select class="form-control" name="position" required>
                                    <option value="">Select Position</option>
                                    <option value="homepage_hero">Homepage Hero</option>
                                    <option value="homepage_top">Homepage Top</option>
                                    <option value="homepage_middle">Homepage Middle</option>
                                    <option value="homepage_bottom">Homepage Bottom</option>
                                    <option value="sidebar">Sidebar</option>
                                    <option value="footer">Footer</option>
                                    <option value="category_top">Category Top</option>
                                    <option value="product_detail">Product Detail</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Priority</label>
                                <input type="number" class="form-control" name="priority" value="1" min="1" max="100">
                                <div class="form-text">Higher numbers appear first</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="scheduled">Scheduled</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="datetime-local" class="form-control" name="start_date">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <input type="datetime-local" class="form-control" name="end_date">
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="track_clicks" id="trackClicks" checked>
                                    <label class="form-check-label" for="trackClicks">
                                        Track Clicks
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="show_on_mobile" id="showOnMobile" checked>
                                    <label class="form-check-label" for="showOnMobile">
                                        Show on Mobile
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save"></i> Create Banner
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="previewBanner()">
                                    <i class="bx bx-show"></i> Preview
                                </button>
                                <a href="{{ route('admin.marketing.banners') }}" class="btn btn-light">
                                    <i class="bx bx-arrow-back"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadZone = document.getElementById('uploadZone');
    const bannerImage = document.getElementById('bannerImage');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const typeSelect = document.querySelector('select[name="type"]');

    // Handle banner type change
    typeSelect.addEventListener('change', function() {
        const type = this.value;
        const imageSection = document.getElementById('imageUploadSection');
        const textSection = document.getElementById('textContentSection');
        const htmlSection = document.getElementById('htmlContentSection');

        // Hide all sections first
        imageSection.style.display = 'none';
        textSection.style.display = 'none';
        htmlSection.style.display = 'none';

        // Show relevant section
        if (type === 'image') {
            imageSection.style.display = 'block';
        } else if (type === 'text') {
            textSection.style.display = 'block';
        } else if (type === 'html') {
            htmlSection.style.display = 'block';
        }
    });

    // Upload zone click handler
    uploadZone.addEventListener('click', function() {
        bannerImage.click();
    });

    // File input change handler
    bannerImage.addEventListener('change', function(e) {
        handleImageUpload(e.target.files[0]);
    });

    // Drag and drop handlers
    uploadZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadZone.classList.add('dragover');
    });

    uploadZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
    });

    uploadZone.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleImageUpload(files[0]);
        }
    });

    function handleImageUpload(file) {
        if (!file) return;

        // Validate file type
        if (!file.type.startsWith('image/')) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error', 'Please select a valid image file.', 'error');
            } else {
                alert('Please select a valid image file.');
            }
            return;
        }

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error', 'File size should be less than 2MB.', 'error');
            } else {
                alert('File size should be less than 2MB.');
            }
            return;
        }

        // Create FileReader to preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            uploadZone.style.display = 'none';
            imagePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    // Form submission handler
    document.getElementById('bannerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Creating...';
        
        // Simulate form submission
        setTimeout(() => {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Success!', 'Banner has been created successfully.', 'success').then(() => {
                    window.location.href = '{{ route("admin.marketing.banners") }}';
                });
            } else {
                alert('Banner has been created successfully.');
                window.location.href = '{{ route("admin.marketing.banners") }}';
            }
        }, 2000);
    });
});

function removeImage() {
    document.getElementById('bannerImage').value = '';
    document.getElementById('uploadZone').style.display = 'block';
    document.getElementById('imagePreview').style.display = 'none';
}

function previewBanner() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Banner Preview',
            html: '<div class="text-center"><img src="https://via.placeholder.com/600x200" class="img-fluid" alt="Banner Preview"><p class="mt-2 text-muted">This is how your banner will appear</p></div>',
            showCloseButton: true,
            showConfirmButton: false,
            width: '800px'
        });
    } else {
        alert('Preview feature coming soon');
    }
}
</script>
@endsection
