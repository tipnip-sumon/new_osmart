@extends('admin.layouts.app')

@section('title', 'Theme Management')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Theme Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Themes</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Current Theme -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Current Active Theme</div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary btn-sm" onclick="customizeTheme()">
                                <i class="bx bx-palette"></i> Customize
                            </button>
                            <button class="btn btn-success btn-sm" onclick="uploadTheme()">
                                <i class="bx bx-upload"></i> Upload Theme
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <img src="https://via.placeholder.com/200x150/007bff/ffffff?text=Default+Theme" alt="Default Theme" class="img-fluid rounded">
                            </div>
                            <div class="col-md-8">
                                <h5 class="mb-2">Default E-commerce Theme</h5>
                                <p class="text-muted mb-2">A modern, responsive e-commerce theme with clean design and excellent user experience. Features include product showcase, shopping cart, user accounts, and mobile-first design.</p>
                                <div class="d-flex gap-3">
                                    <span class="badge bg-success">Active</span>
                                    <span class="text-muted">Version: 2.1.0</span>
                                    <span class="text-muted">Author: Your Company</span>
                                    <span class="text-muted">Last Updated: July 20, 2025</span>
                                </div>
                            </div>
                            <div class="col-md-2 text-end">
                                <button class="btn btn-outline-primary btn-sm mb-2" onclick="previewTheme('default')">
                                    <i class="bx bx-show"></i> Preview
                                </button>
                                <br>
                                <button class="btn btn-outline-secondary btn-sm" onclick="themeDetails('default')">
                                    <i class="bx bx-info-circle"></i> Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Themes -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Available Themes</div>
                        <div class="text-muted">Choose from available themes or upload your own</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Theme 1 -->
                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="card theme-card">
                                    <div class="theme-preview">
                                        <img src="https://via.placeholder.com/300x200/28a745/ffffff?text=Modern+Shop" alt="Modern Shop" class="card-img-top">
                                        <div class="theme-overlay">
                                            <button class="btn btn-primary btn-sm" onclick="previewTheme('modern-shop')">
                                                <i class="bx bx-show"></i> Preview
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title">Modern Shop</h6>
                                        <p class="card-text text-muted">A sleek and modern e-commerce theme with advanced features and beautiful animations.</p>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <small class="text-muted">v1.5.2</small>
                                            <div class="rating">
                                                <i class="bx bxs-star text-warning"></i>
                                                <i class="bx bxs-star text-warning"></i>
                                                <i class="bx bxs-star text-warning"></i>
                                                <i class="bx bxs-star text-warning"></i>
                                                <i class="bx bx-star text-warning"></i>
                                                <span class="ms-1">4.2</span>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-success btn-sm" onclick="activateTheme('modern-shop')">
                                                <i class="bx bx-check"></i> Activate
                                            </button>
                                            <button class="btn btn-outline-info btn-sm" onclick="themeDetails('modern-shop')">
                                                <i class="bx bx-info-circle"></i> Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Theme 2 -->
                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="card theme-card">
                                    <div class="theme-preview">
                                        <img src="https://via.placeholder.com/300x200/dc3545/ffffff?text=Minimal+Store" alt="Minimal Store" class="card-img-top">
                                        <div class="theme-overlay">
                                            <button class="btn btn-primary btn-sm" onclick="previewTheme('minimal-store')">
                                                <i class="bx bx-show"></i> Preview
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title">Minimal Store</h6>
                                        <p class="card-text text-muted">Clean and minimal design focusing on products with excellent user experience.</p>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <small class="text-muted">v2.0.1</small>
                                            <div class="rating">
                                                <i class="bx bxs-star text-warning"></i>
                                                <i class="bx bxs-star text-warning"></i>
                                                <i class="bx bxs-star text-warning"></i>
                                                <i class="bx bxs-star text-warning"></i>
                                                <i class="bx bxs-star text-warning"></i>
                                                <span class="ms-1">4.8</span>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-success btn-sm" onclick="activateTheme('minimal-store')">
                                                <i class="bx bx-check"></i> Activate
                                            </button>
                                            <button class="btn btn-outline-info btn-sm" onclick="themeDetails('minimal-store')">
                                                <i class="bx bx-info-circle"></i> Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Theme 3 -->
                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="card theme-card">
                                    <div class="theme-preview">
                                        <img src="https://via.placeholder.com/300x200/6f42c1/ffffff?text=Fashion+Hub" alt="Fashion Hub" class="card-img-top">
                                        <div class="theme-overlay">
                                            <button class="btn btn-primary btn-sm" onclick="previewTheme('fashion-hub')">
                                                <i class="bx bx-show"></i> Preview
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title">Fashion Hub</h6>
                                        <p class="card-text text-muted">Perfect for fashion and lifestyle stores with elegant design and smooth animations.</p>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <small class="text-muted">v1.8.0</small>
                                            <div class="rating">
                                                <i class="bx bxs-star text-warning"></i>
                                                <i class="bx bxs-star text-warning"></i>
                                                <i class="bx bxs-star text-warning"></i>
                                                <i class="bx bxs-star text-warning"></i>
                                                <i class="bx bx-star text-warning"></i>
                                                <span class="ms-1">4.5</span>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-success btn-sm" onclick="activateTheme('fashion-hub')">
                                                <i class="bx bx-check"></i> Activate
                                            </button>
                                            <button class="btn btn-outline-info btn-sm" onclick="themeDetails('fashion-hub')">
                                                <i class="bx bx-info-circle"></i> Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Theme 4 -->
                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="card theme-card">
                                    <div class="theme-preview">
                                        <img src="https://via.placeholder.com/300x200/fd7e14/ffffff?text=Tech+Store" alt="Tech Store" class="card-img-top">
                                        <div class="theme-overlay">
                                            <button class="btn btn-primary btn-sm" onclick="previewTheme('tech-store')">
                                                <i class="bx bx-show"></i> Preview
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title">Tech Store</h6>
                                        <p class="card-text text-muted">Designed specifically for electronics and tech products with modern layouts.</p>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <small class="text-muted">v1.3.5</small>
                                            <div class="rating">
                                                <i class="bx bxs-star text-warning"></i>
                                                <i class="bx bxs-star text-warning"></i>
                                                <i class="bx bxs-star text-warning"></i>
                                                <i class="bx bxs-star text-warning"></i>
                                                <i class="bx bx-star text-warning"></i>
                                                <span class="ms-1">4.1</span>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-success btn-sm" onclick="activateTheme('tech-store')">
                                                <i class="bx bx-check"></i> Activate
                                            </button>
                                            <button class="btn btn-outline-info btn-sm" onclick="themeDetails('tech-store')">
                                                <i class="bx bx-info-circle"></i> Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload New Theme -->
                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="card theme-card upload-theme">
                                    <div class="card-body text-center d-flex flex-column justify-content-center" style="min-height: 300px;">
                                        <i class="bx bx-upload fs-48 text-muted mb-3"></i>
                                        <h6 class="card-title">Upload New Theme</h6>
                                        <p class="card-text text-muted">Upload your custom theme as a ZIP file</p>
                                        <button class="btn btn-primary" onclick="uploadTheme()">
                                            <i class="bx bx-upload"></i> Upload Theme
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

<!-- Theme Customizer Modal -->
<div class="modal fade" id="themeCustomizerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Theme Customizer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="customizer-tabs">
                            <div class="nav flex-column nav-pills">
                                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#colors-tab">
                                    <i class="bx bx-palette"></i> Colors
                                </button>
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#typography-tab">
                                    <i class="bx bx-font"></i> Typography
                                </button>
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#layout-tab">
                                    <i class="bx bx-layout"></i> Layout
                                </button>
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#header-tab">
                                    <i class="bx bx-menu"></i> Header
                                </button>
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#footer-tab">
                                    <i class="bx bx-dock-bottom"></i> Footer
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="tab-content">
                            <!-- Colors Tab -->
                            <div class="tab-pane fade show active" id="colors-tab">
                                <h6>Color Scheme</h6>
                                <div class="mb-3">
                                    <label class="form-label">Primary Color</label>
                                    <input type="color" class="form-control form-control-color" value="#007bff">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Secondary Color</label>
                                    <input type="color" class="form-control form-control-color" value="#6c757d">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Accent Color</label>
                                    <input type="color" class="form-control form-control-color" value="#28a745">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Background Color</label>
                                    <input type="color" class="form-control form-control-color" value="#ffffff">
                                </div>
                            </div>

                            <!-- Typography Tab -->
                            <div class="tab-pane fade" id="typography-tab">
                                <h6>Typography Settings</h6>
                                <div class="mb-3">
                                    <label class="form-label">Primary Font</label>
                                    <select class="form-control">
                                        <option>Inter</option>
                                        <option>Roboto</option>
                                        <option>Open Sans</option>
                                        <option>Lato</option>
                                        <option>Poppins</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Heading Font</label>
                                    <select class="form-control">
                                        <option>Inter</option>
                                        <option>Montserrat</option>
                                        <option>Playfair Display</option>
                                        <option>Oswald</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Base Font Size</label>
                                    <input type="range" class="form-range" min="12" max="18" value="14">
                                    <div class="d-flex justify-content-between">
                                        <small>12px</small>
                                        <small>18px</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Layout Tab -->
                            <div class="tab-pane fade" id="layout-tab">
                                <h6>Layout Options</h6>
                                <div class="mb-3">
                                    <label class="form-label">Container Width</label>
                                    <select class="form-control">
                                        <option>Fluid (100%)</option>
                                        <option>Large (1200px)</option>
                                        <option>Medium (960px)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="boxedLayout">
                                        <label class="form-check-label" for="boxedLayout">
                                            Boxed Layout
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="stickyHeader" checked>
                                        <label class="form-check-label" for="stickyHeader">
                                            Sticky Header
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Header Tab -->
                            <div class="tab-pane fade" id="header-tab">
                                <h6>Header Settings</h6>
                                <div class="mb-3">
                                    <label class="form-label">Header Style</label>
                                    <select class="form-control">
                                        <option>Classic</option>
                                        <option>Modern</option>
                                        <option>Minimal</option>
                                        <option>Centered</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="showSearch" checked>
                                        <label class="form-check-label" for="showSearch">
                                            Show Search Bar
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="showCart" checked>
                                        <label class="form-check-label" for="showCart">
                                            Show Cart Icon
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Tab -->
                            <div class="tab-pane fade" id="footer-tab">
                                <h6>Footer Settings</h6>
                                <div class="mb-3">
                                    <label class="form-label">Footer Columns</label>
                                    <select class="form-control">
                                        <option>3 Columns</option>
                                        <option>4 Columns</option>
                                        <option>5 Columns</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="showSocial" checked>
                                        <label class="form-check-label" for="showSocial">
                                            Show Social Links
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="showCopyright" checked>
                                        <label class="form-check-label" for="showCopyright">
                                            Show Copyright
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveCustomization()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Upload Theme Modal -->
<div class="modal fade" id="uploadThemeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload New Theme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="uploadThemeForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Theme File (ZIP)</label>
                        <input type="file" class="form-control" name="theme_file" accept=".zip" required>
                        <small class="text-muted">Upload a ZIP file containing your theme files</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Theme Name</label>
                        <input type="text" class="form-control" name="theme_name" placeholder="Enter theme name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Brief description of the theme"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="processUpload()">Upload Theme</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.theme-card {
    transition: transform 0.2s;
    height: 100%;
}

.theme-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.theme-preview {
    position: relative;
    overflow: hidden;
}

.theme-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.theme-preview:hover .theme-overlay {
    opacity: 1;
}

.upload-theme {
    border: 2px dashed #dee2e6;
    background: #f8f9fa;
}

.upload-theme:hover {
    border-color: #007bff;
    background: #e7f3ff;
}

.rating {
    display: flex;
    align-items: center;
}

.customizer-tabs .nav-link {
    text-align: left;
    border-radius: 0;
    border: none;
    color: #6c757d;
    margin-bottom: 5px;
}

.customizer-tabs .nav-link.active {
    background: #007bff;
    color: white;
}

.form-control-color {
    width: 60px;
    height: 38px;
}
</style>
@endsection

@section('scripts')
<script>
// Activate theme
function activateTheme(themeId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Activate Theme',
            text: 'Are you sure you want to activate this theme?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Activate'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Activated!', 'Theme has been activated successfully.', 'success').then(() => {
                    location.reload();
                });
            }
        });
    } else {
        if (confirm('Activate this theme?')) {
            alert('Theme activated successfully!');
            location.reload();
        }
    }
}

// Preview theme
function previewTheme(themeId) {
    const previewUrl = `/preview-theme/${themeId}`;
    window.open(previewUrl, '_blank');
}

// Theme details
function themeDetails(themeId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Theme Details',
            html: `
                <div class="text-start">
                    <p><strong>Version:</strong> 2.1.0</p>
                    <p><strong>Author:</strong> Theme Developer</p>
                    <p><strong>Compatibility:</strong> Laravel 10+</p>
                    <p><strong>Features:</strong> Responsive, SEO Optimized, Multi-language</p>
                    <p><strong>Last Updated:</strong> July 20, 2025</p>
                </div>
            `,
            showCloseButton: true,
            showConfirmButton: false,
            width: '500px'
        });
    } else {
        alert('Theme details - Feature coming soon');
    }
}

// Customize theme
function customizeTheme() {
    $('#themeCustomizerModal').modal('show');
}

// Save customization
function saveCustomization() {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Saved!', 'Theme customization has been saved.', 'success').then(() => {
            $('#themeCustomizerModal').modal('hide');
        });
    } else {
        alert('Theme customization saved!');
        $('#themeCustomizerModal').modal('hide');
    }
}

// Upload theme
function uploadTheme() {
    $('#uploadThemeModal').modal('show');
}

// Process upload
function processUpload() {
    const form = document.getElementById('uploadThemeForm');
    const formData = new FormData(form);
    
    if (!formData.get('theme_file')) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('Error', 'Please select a theme file to upload', 'error');
        } else {
            alert('Please select a theme file to upload');
        }
        return;
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Uploading...',
            html: 'Please wait while your theme is being uploaded and processed.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Simulate upload process
        setTimeout(() => {
            Swal.fire('Success!', 'Theme has been uploaded successfully.', 'success').then(() => {
                $('#uploadThemeModal').modal('hide');
                form.reset();
                location.reload();
            });
        }, 3000);
    } else {
        alert('Theme uploaded successfully!');
        $('#uploadThemeModal').modal('hide');
        form.reset();
        location.reload();
    }
}

// Real-time color preview
document.querySelectorAll('input[type="color"]').forEach(input => {
    input.addEventListener('change', function() {
        // In a real implementation, this would update the theme preview
        console.log(`Color changed: ${this.value}`);
    });
});

// Font size range slider
document.querySelector('input[type="range"]')?.addEventListener('input', function() {
    const fontSize = this.value + 'px';
    // In a real implementation, this would update the preview
    console.log(`Font size: ${fontSize}`);
});
</script>
@endsection
