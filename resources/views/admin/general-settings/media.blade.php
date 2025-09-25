@extends('admin.layouts.app')

@section('content')
<!-- Settings Navigation -->
<x-admin.settings-navigation current="media" />

<!-- Image Processing Info Alert -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="alert alert-info border-0">
            <div class="d-flex align-items-start">
                <i class="fas fa-magic fa-2x text-primary me-3 mt-1"></i>
                <div>
                    <h6 class="alert-heading mb-2">
                        <i class="fas fa-sparkles me-2"></i>Advanced Image Processing Enabled
                    </h6>
                    <p class="mb-2">
                        Your media uploads now include <strong>automatic image optimization</strong> using the Intervention Image library:
                    </p>
                    <ul class="mb-2">
                        <li><strong>Multiple Size Generation:</strong> Original + optimized sizes for different use cases</li>
                        <li><strong>Format Optimization:</strong> JPEG quality compression, PNG optimization, WebP support</li>
                        <li><strong>Responsive Images:</strong> Automatically generates thumbnails and responsive variants</li>
                        <li><strong>Smart Compression:</strong> Reduces file sizes while maintaining visual quality</li>
                        <li><strong>SEO Benefits:</strong> Proper image sizing for social media and search engines</li>
                    </ul>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Images are automatically processed and stored in organized folders with date structure for better management.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4 my-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-images me-2"></i>
                    {{ $pageTitle }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.general-settings.media.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Site Logo -->
                        <div class="col-lg-6 mb-4">
                            <div class="form-group">
                                <label for="logo" class="form-label">
                                    <i class="fas fa-store me-2"></i>Site Logo
                                    <span class="badge bg-success ms-2">Auto-Resize</span>
                                </label>
                                <div class="mb-3">
                                    @if($settings->logo)
                                        <img src="{{ siteLogo() }}" alt="Current Logo" class="img-thumbnail" style="max-height: 100px;">
                                        <p class="text-muted small mt-2">Current Logo</p>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No logo uploaded yet
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                       name="logo" id="logo" accept="image/*">
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-cogs me-1"></i>
                                    <strong>Auto-generates:</strong> 800x400px (original), 400x200px (medium), 200x100px (small)<br>
                                    <i class="fas fa-file-image me-1"></i>
                                    Max size: 5MB, Formats: JPG, PNG, GIF, WebP
                                </small>
                            </div> 
                        </div>

                        <!-- Admin Logo -->
                        <div class="col-lg-6 mb-4">
                            <div class="form-group">
                                <label for="admin_logo" class="form-label">
                                    <i class="fas fa-user-shield me-2"></i>Admin Panel Logo
                                    <span class="badge bg-success ms-2">Auto-Resize</span>
                                </label>
                                <div class="mb-3">
                                    @if($settings->admin_logo)
                                        <img src="{{ adminLogo() }}" alt="Current Admin Logo" class="img-thumbnail" style="max-height: 100px;">
                                        <p class="text-muted small mt-2">Current Admin Logo</p>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No admin logo uploaded (using site logo)
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="form-control @error('admin_logo') is-invalid @enderror" 
                                       name="admin_logo" id="admin_logo" accept="image/*">
                                @error('admin_logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-cogs me-1"></i>
                                    <strong>Auto-generates:</strong> 600x300px (original), 300x150px (medium), 150x75px (small)<br>
                                    <i class="fas fa-file-image me-1"></i>
                                    Max size: 5MB, Formats: JPG, PNG, GIF, WebP
                                </small>
                            </div>
                        </div>

                        <!-- Favicon -->
                        <div class="col-lg-6 mb-4">
                            <div class="form-group">
                                <label for="favicon" class="form-label">
                                    <i class="fas fa-cube me-2"></i>Favicon
                                    <span class="badge bg-success ms-2">Multi-Size</span>
                                </label>
                                <div class="mb-3">
                                    @if($settings->favicon)
                                        <img src="{{ siteFavicon() }}" alt="Current Favicon" class="img-thumbnail" style="max-height: 50px;">
                                        <p class="text-muted small mt-2">Current Favicon</p>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No favicon uploaded yet
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="form-control @error('favicon') is-invalid @enderror" 
                                       name="favicon" id="favicon" accept="image/*,.ico">
                                @error('favicon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-cogs me-1"></i>
                                    <strong>Auto-generates:</strong> 512px, 256px, 128px, 64px, 32px, 16px sizes<br>
                                    <i class="fas fa-file-image me-1"></i>
                                    Max size: 2MB, Formats: ICO, PNG, JPG, WebP
                                </small>
                            </div>
                        </div>

                        <!-- Meta Image -->
                        <div class="col-lg-6 mb-4">
                            <div class="form-group">
                                <label for="meta_image" class="form-label">
                                    <i class="fas fa-share-alt me-2"></i>Social Media Image (Open Graph)
                                    <span class="badge bg-success ms-2">Platform-Optimized</span>
                                </label>
                                <div class="mb-3">
                                    @if($settings->meta_image)
                                        <img src="{{ \App\Models\GeneralSetting::getMetaImage('twitter') }}" 
                                             alt="Current Meta Image" class="img-thumbnail" style="max-height: 100px;">
                                        <p class="text-muted small mt-2">Current Meta Image</p>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No meta image uploaded (using site logo)
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="form-control @error('meta_image') is-invalid @enderror" 
                                       name="meta_image" id="meta_image" accept="image/*">
                                @error('meta_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-cogs me-1"></i>
                                    <strong>Auto-generates:</strong> 1200x630px (Facebook), 1024x512px (Twitter), 1200x627px (LinkedIn)<br>
                                    <i class="fas fa-file-image me-1"></i>
                                    Max size: 5MB, Formats: JPG, PNG, GIF, WebP
                                </small>
                            </div>
                        </div>

                        <!-- Maintenance Image -->
                        <div class="col-lg-6 mb-4">
                            <div class="form-group">
                                <label for="maintenance_image" class="form-label">
                                    <i class="fas fa-tools me-2"></i>Maintenance Page Image
                                    <span class="badge bg-success ms-2">Responsive</span>
                                </label>
                                <div class="mb-3">
                                    @if($settings->maintenance_image)
                                        <img src="{{ siteMaintenanceImage('small') }}" 
                                             alt="Current Maintenance Image" class="img-thumbnail" style="max-height: 100px;">
                                        <p class="text-muted small mt-2">Current Maintenance Image</p>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No maintenance image uploaded
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="form-control @error('maintenance_image') is-invalid @enderror" 
                                       name="maintenance_image" id="maintenance_image" accept="image/*">
                                @error('maintenance_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-cogs me-1"></i>
                                    <strong>Auto-generates:</strong> 1920x1080px (large), 1200x675px (desktop), 800x450px (tablet), 400x225px (mobile)<br>
                                    <i class="fas fa-file-image me-1"></i>
                                    Max size: 5MB, Formats: JPG, PNG, GIF, WebP
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Image Processing Benefits -->
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">
                                        <i class="fas fa-lightbulb me-2"></i>Image Processing Benefits
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="text-center p-2">
                                                <i class="fas fa-tachometer-alt fa-2x text-success mb-2"></i>
                                                <h6>Faster Loading</h6>
                                                <small class="text-muted">Optimized file sizes improve page speed</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center p-2">
                                                <i class="fas fa-mobile-alt fa-2x text-info mb-2"></i>
                                                <h6>Responsive Design</h6>
                                                <small class="text-muted">Perfect images for all device sizes</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center p-2">
                                                <i class="fas fa-search fa-2x text-warning mb-2"></i>
                                                <h6>SEO Optimized</h6>
                                                <small class="text-muted">Proper sizing for search engines</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center p-2">
                                                <i class="fas fa-share-square fa-2x text-danger mb-2"></i>
                                                <h6>Social Ready</h6>
                                                <small class="text-muted">Platform-specific image formats</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-magic me-2"></i>
                            Process & Update Media Settings
                        </button>
                        <a href="{{ route('admin.general-settings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to General Settings
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview image before upload
    function setupImagePreview(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = input.parentElement.querySelector('.img-thumbnail');
                        if (preview) {
                            preview.src = e.target.result;
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    setupImagePreview('logo');
    setupImagePreview('admin_logo');
    setupImagePreview('favicon');
    setupImagePreview('meta_image');
    setupImagePreview('maintenance_image');
});
</script>
@endsection
