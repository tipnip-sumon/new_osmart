@extends('admin.layouts.app')

@section('title', 'Website Management')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Website Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Website</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Website Overview Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-primary-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">Total Pages</h6>
                                <h2 class="text-white mb-0">{{ number_format(47) }}</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-file text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-success-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">Menu Items</h6>
                                <h2 class="text-white mb-0">{{ number_format(23) }}</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-menu text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-warning-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">Active Themes</h6>
                                <h2 class="text-white mb-0">{{ number_format(1) }}</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-palette text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-info-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">Page Views</h6>
                                <h2 class="text-white mb-0">{{ number_format(125847) }}</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-show text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Website Management Tabs -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Website Configuration</div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-justified" id="websiteTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pages-tab" data-bs-toggle="tab" data-bs-target="#pages" type="button" role="tab">
                                    <i class="bx bx-file me-2"></i>Pages
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="menus-tab" data-bs-toggle="tab" data-bs-target="#menus" type="button" role="tab">
                                    <i class="bx bx-menu me-2"></i>Menus
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="themes-tab" data-bs-toggle="tab" data-bs-target="#themes" type="button" role="tab">
                                    <i class="bx bx-palette me-2"></i>Themes
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="seo-settings-tab" data-bs-toggle="tab" data-bs-target="#seo-settings" type="button" role="tab">
                                    <i class="bx bx-search me-2"></i>SEO Settings
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content mt-3" id="websiteTabsContent">
                            <!-- Pages Tab -->
                            <div class="tab-pane fade show active" id="pages" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Page Management</h5>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPageModal">
                                        <i class="bx bx-plus"></i> Add Page
                                    </button>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table text-nowrap table-hover">
                                        <thead>
                                            <tr>
                                                <th>Page Title</th>
                                                <th>Slug</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>Views</th>
                                                <th>Last Modified</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-home me-2 text-primary"></i>
                                                        <div>
                                                            <span class="fw-semibold">Homepage</span>
                                                            <div class="text-muted fs-12">Main landing page</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><code>/</code></td>
                                                <td><span class="badge bg-primary-transparent">System</span></td>
                                                <td><span class="badge bg-success-transparent">Published</span></td>
                                                <td>45,892</td>
                                                <td>Feb 1, 2024</td>
                                                <td>
                                                    <div class="hstack gap-2">
                                                        <a href="#" class="text-info fs-14 lh-1" data-bs-toggle="tooltip" title="View">
                                                            <i class="bx bx-show"></i>
                                                        </a>
                                                        <a href="#" class="text-primary fs-14 lh-1" data-bs-toggle="tooltip" title="Edit">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                        <a href="#" class="text-warning fs-14 lh-1" data-bs-toggle="tooltip" title="SEO">
                                                            <i class="bx bx-search"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-info-circle me-2 text-info"></i>
                                                        <div>
                                                            <span class="fw-semibold">About Us</span>
                                                            <div class="text-muted fs-12">Company information</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><code>/about</code></td>
                                                <td><span class="badge bg-info-transparent">Static</span></td>
                                                <td><span class="badge bg-success-transparent">Published</span></td>
                                                <td>12,456</td>
                                                <td>Jan 28, 2024</td>
                                                <td>
                                                    <div class="hstack gap-2">
                                                        <a href="#" class="text-info fs-14 lh-1" data-bs-toggle="tooltip" title="View">
                                                            <i class="bx bx-show"></i>
                                                        </a>
                                                        <a href="#" class="text-primary fs-14 lh-1" data-bs-toggle="tooltip" title="Edit">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                        <a href="#" class="text-warning fs-14 lh-1" data-bs-toggle="tooltip" title="SEO">
                                                            <i class="bx bx-search"></i>
                                                        </a>
                                                        <a href="#" class="text-danger fs-14 lh-1" data-bs-toggle="tooltip" title="Delete">
                                                            <i class="bx bx-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-envelope me-2 text-success"></i>
                                                        <div>
                                                            <span class="fw-semibold">Contact Us</span>
                                                            <div class="text-muted fs-12">Contact information and form</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><code>/contact</code></td>
                                                <td><span class="badge bg-success-transparent">Contact</span></td>
                                                <td><span class="badge bg-success-transparent">Published</span></td>
                                                <td>8,234</td>
                                                <td>Jan 25, 2024</td>
                                                <td>
                                                    <div class="hstack gap-2">
                                                        <a href="#" class="text-info fs-14 lh-1" data-bs-toggle="tooltip" title="View">
                                                            <i class="bx bx-show"></i>
                                                        </a>
                                                        <a href="#" class="text-primary fs-14 lh-1" data-bs-toggle="tooltip" title="Edit">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                        <a href="#" class="text-warning fs-14 lh-1" data-bs-toggle="tooltip" title="SEO">
                                                            <i class="bx bx-search"></i>
                                                        </a>
                                                        <a href="#" class="text-danger fs-14 lh-1" data-bs-toggle="tooltip" title="Delete">
                                                            <i class="bx bx-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Menus Tab -->
                            <div class="tab-pane fade" id="menus" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Navigation Menus</h5>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMenuModal">
                                        <i class="bx bx-plus"></i> Add Menu Item
                                    </button>
                                </div>
                                
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">Main Navigation</div>
                                            </div>
                                            <div class="card-body">
                                                <div class="list-group">
                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-home me-2"></i>
                                                            <span>Home</span>
                                                        </div>
                                                        <div class="hstack gap-2">
                                                            <button class="btn btn-sm btn-light">
                                                                <i class="bx bx-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-light">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-shopping-bag me-2"></i>
                                                            <span>Shop</span>
                                                        </div>
                                                        <div class="hstack gap-2">
                                                            <button class="btn btn-sm btn-light">
                                                                <i class="bx bx-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-light">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="list-group-item d-flex justify-content-between align-items-center ps-4">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-category me-2"></i>
                                                            <span>Categories</span>
                                                        </div>
                                                        <div class="hstack gap-2">
                                                            <button class="btn btn-sm btn-light">
                                                                <i class="bx bx-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-light">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="list-group-item d-flex justify-content-between align-items-center ps-4">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-store me-2"></i>
                                                            <span>Vendors</span>
                                                        </div>
                                                        <div class="hstack gap-2">
                                                            <button class="btn btn-sm btn-light">
                                                                <i class="bx bx-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-light">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-info-circle me-2"></i>
                                                            <span>About</span>
                                                        </div>
                                                        <div class="hstack gap-2">
                                                            <button class="btn btn-sm btn-light">
                                                                <i class="bx bx-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-light">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-envelope me-2"></i>
                                                            <span>Contact</span>
                                                        </div>
                                                        <div class="hstack gap-2">
                                                            <button class="btn btn-sm btn-light">
                                                                <i class="bx bx-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-light">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">Footer Navigation</div>
                                            </div>
                                            <div class="card-body">
                                                <div class="list-group">
                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-shield me-2"></i>
                                                            <span>Privacy Policy</span>
                                                        </div>
                                                        <div class="hstack gap-2">
                                                            <button class="btn btn-sm btn-light">
                                                                <i class="bx bx-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-light">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-file me-2"></i>
                                                            <span>Terms & Conditions</span>
                                                        </div>
                                                        <div class="hstack gap-2">
                                                            <button class="btn btn-sm btn-light">
                                                                <i class="bx bx-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-light">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-support me-2"></i>
                                                            <span>Support</span>
                                                        </div>
                                                        <div class="hstack gap-2">
                                                            <button class="btn btn-sm btn-light">
                                                                <i class="bx bx-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-light">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Themes Tab -->
                            <div class="tab-pane fade" id="themes" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Theme Management</h5>
                                    <button type="button" class="btn btn-warning">
                                        <i class="bx bx-upload"></i> Upload Theme
                                    </button>
                                </div>
                                
                                <div class="row">
                                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                        <div class="card border border-success">
                                            <div class="card-header bg-success text-white text-center">
                                                <span class="badge bg-white text-success">Active</span>
                                            </div>
                                            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Default Theme">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">Default Theme</h6>
                                                <p class="card-text text-muted fs-12">Modern and responsive ecommerce theme</p>
                                                <div class="btn-group w-100">
                                                    <button type="button" class="btn btn-light btn-sm">
                                                        <i class="bx bx-cog"></i> Customize
                                                    </button>
                                                    <button type="button" class="btn btn-light btn-sm">
                                                        <i class="bx bx-show"></i> Preview
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                        <div class="card">
                                            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Minimal Theme">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">Minimal Theme</h6>
                                                <p class="card-text text-muted fs-12">Clean and minimal design theme</p>
                                                <div class="btn-group w-100">
                                                    <button type="button" class="btn btn-primary btn-sm">
                                                        <i class="bx bx-check"></i> Activate
                                                    </button>
                                                    <button type="button" class="btn btn-light btn-sm">
                                                        <i class="bx bx-show"></i> Preview
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                        <div class="card">
                                            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Dark Theme">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">Dark Theme</h6>
                                                <p class="card-text text-muted fs-12">Modern dark mode theme</p>
                                                <div class="btn-group w-100">
                                                    <button type="button" class="btn btn-primary btn-sm">
                                                        <i class="bx bx-check"></i> Activate
                                                    </button>
                                                    <button type="button" class="btn btn-light btn-sm">
                                                        <i class="bx bx-show"></i> Preview
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- SEO Settings Tab -->
                            <div class="tab-pane fade" id="seo-settings" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">SEO Configuration</h5>
                                    <button type="button" class="btn btn-info">
                                        <i class="bx bx-save"></i> Save Settings
                                    </button>
                                </div>
                                
                                <div class="row">
                                    <div class="col-xl-8">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">Global SEO Settings</div>
                                            </div>
                                            <div class="card-body">
                                                <form>
                                                    <div class="mb-3">
                                                        <label for="siteTitle" class="form-label">Site Title</label>
                                                        <input type="text" class="form-control" id="siteTitle" value="Laravel Multivendor Ecommerce">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="siteDescription" class="form-label">Site Description</label>
                                                        <textarea class="form-control" id="siteDescription" rows="3">The best multivendor ecommerce platform for online shopping</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="siteKeywords" class="form-label">Site Keywords</label>
                                                        <input type="text" class="form-control" id="siteKeywords" value="ecommerce, multivendor, online shopping, marketplace">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="googleAnalytics" class="form-label">Google Analytics ID</label>
                                                        <input type="text" class="form-control" id="googleAnalytics" placeholder="G-XXXXXXXXXX">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="googleTagManager" class="form-label">Google Tag Manager ID</label>
                                                        <input type="text" class="form-control" id="googleTagManager" placeholder="GTM-XXXXXXX">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">SEO Tools</div>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-grid gap-2">
                                                    <button type="button" class="btn btn-outline-primary">
                                                        <i class="bx bx-sitemap"></i> Generate Sitemap
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success">
                                                        <i class="bx bx-robot"></i> Update Robots.txt
                                                    </button>
                                                    <button type="button" class="btn btn-outline-warning">
                                                        <i class="bx bx-search"></i> Check SEO Score
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info">
                                                        <i class="bx bx-link"></i> Verify Search Console
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">Social Media</div>
                                            </div>
                                            <div class="card-body">
                                                <form>
                                                    <div class="mb-3">
                                                        <label for="ogTitle" class="form-label">Open Graph Title</label>
                                                        <input type="text" class="form-control" id="ogTitle" value="Laravel Multivendor Ecommerce">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="ogDescription" class="form-label">Open Graph Description</label>
                                                        <textarea class="form-control" id="ogDescription" rows="2">The best multivendor platform</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="ogImage" class="form-label">Open Graph Image</label>
                                                        <input type="file" class="form-control" id="ogImage">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Add Page Modal -->
<div class="modal fade" id="addPageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Page</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addPageForm">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Page Title</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status">
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" class="form-control" name="slug">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Page Type</label>
                                <select class="form-control" name="type">
                                    <option value="static">Static Page</option>
                                    <option value="dynamic">Dynamic Page</option>
                                    <option value="contact">Contact Page</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Meta Description</label>
                                <textarea class="form-control" name="meta_description" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <textarea class="form-control" name="content" rows="8"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="savePageBtn">Create Page</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Menu Modal -->
<div class="modal fade" id="addMenuModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Menu Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addMenuForm">
                    <div class="mb-3">
                        <label class="form-label">Menu Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Menu Type</label>
                        <select class="form-control" name="type" id="menuType">
                            <option value="page">Page Link</option>
                            <option value="custom">Custom URL</option>
                            <option value="category">Category</option>
                        </select>
                    </div>
                    <div class="mb-3" id="urlField">
                        <label class="form-label">URL</label>
                        <input type="text" class="form-control" name="url">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Menu Location</label>
                        <select class="form-control" name="location">
                            <option value="main">Main Navigation</option>
                            <option value="footer">Footer Navigation</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon (Optional)</label>
                        <input type="text" class="form-control" name="icon" placeholder="bx bx-home">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="saveMenuBtn">Add Menu Item</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Tab switching functionality
    $('#websiteTabs button').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    
    // Auto-generate slug from title
    $('input[name="title"]').on('keyup', function() {
        const title = $(this).val();
        const slug = title.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '') // Remove invalid chars
            .replace(/\s+/g, '-') // Replace spaces with -
            .replace(/-+/g, '-') // Replace multiple - with single -
            .trim('-'); // Trim - from start and end
        $('input[name="slug"]').val(slug);
    });
    
    // Menu type change handler
    $('#menuType').on('change', function() {
        const type = $(this).val();
        if (type === 'custom') {
            $('#urlField label').text('Custom URL');
            $('#urlField input').attr('placeholder', 'https://example.com');
        } else if (type === 'page') {
            $('#urlField label').text('Page URL');
            $('#urlField input').attr('placeholder', '/about-us');
        } else {
            $('#urlField label').text('Category Slug');
            $('#urlField input').attr('placeholder', 'electronics');
        }
    });
    
    // Save page handler
    $('#savePageBtn').on('click', function() {
        const form = $('#addPageForm');
        const formData = new FormData(form[0]);
        
        // Basic validation
        if (!formData.get('title')) {
            Swal.fire('Error', 'Please enter a page title', 'error');
            return;
        }
        
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Creating...');
        
        // Simulate API call
        setTimeout(() => {
            Swal.fire('Success!', 'Page has been created successfully.', 'success').then(() => {
                $('#addPageModal').modal('hide');
                form[0].reset();
                // Here you would typically reload the page table
                location.reload();
            });
            btn.prop('disabled', false).html('Create Page');
        }, 1500);
    });
    
    // Save menu handler
    $('#saveMenuBtn').on('click', function() {
        const form = $('#addMenuForm');
        const formData = new FormData(form[0]);
        
        // Basic validation
        if (!formData.get('title')) {
            Swal.fire('Error', 'Please enter a menu title', 'error');
            return;
        }
        
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Adding...');
        
        // Simulate API call
        setTimeout(() => {
            Swal.fire('Success!', 'Menu item has been added successfully.', 'success').then(() => {
                $('#addMenuModal').modal('hide');
                form[0].reset();
                // Here you would typically reload the menu list
                location.reload();
            });
            btn.prop('disabled', false).html('Add Menu Item');
        }, 1500);
    });
    
    // SEO Tools handlers
    $('button:contains("Generate Sitemap")').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Generating...');
        
        setTimeout(() => {
            Swal.fire('Success!', 'Sitemap has been generated successfully.', 'success');
            btn.prop('disabled', false).html('<i class="bx bx-sitemap"></i> Generate Sitemap');
        }, 2000);
    });
    
    $('button:contains("Update Robots.txt")').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Updating...');
        
        setTimeout(() => {
            Swal.fire('Success!', 'Robots.txt has been updated successfully.', 'success');
            btn.prop('disabled', false).html('<i class="bx bx-robot"></i> Update Robots.txt');
        }, 1500);
    });
    
    $('button:contains("Check SEO Score")').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Checking...');
        
        setTimeout(() => {
            Swal.fire({
                title: 'SEO Score Analysis',
                html: `
                    <div class="text-start">
                        <div class="mb-2"><strong>Overall Score:</strong> <span class="badge bg-success">85/100</span></div>
                        <div class="mb-2"><strong>Title Tags:</strong> <span class="badge bg-success">Good</span></div>
                        <div class="mb-2"><strong>Meta Descriptions:</strong> <span class="badge bg-warning">Needs Improvement</span></div>
                        <div class="mb-2"><strong>Images Alt Text:</strong> <span class="badge bg-danger">Missing</span></div>
                        <div class="mb-2"><strong>Page Speed:</strong> <span class="badge bg-success">Excellent</span></div>
                    </div>
                `,
                icon: 'info'
            });
            btn.prop('disabled', false).html('<i class="bx bx-search"></i> Check SEO Score');
        }, 2500);
    });
    
    // Theme activation handlers
    $('button:contains("Activate")').on('click', function() {
        const btn = $(this);
        const themeName = btn.closest('.card').find('.card-title').text();
        
        Swal.fire({
            title: 'Activate Theme',
            text: `Are you sure you want to activate "${themeName}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Activate'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Activating...');
                
                setTimeout(() => {
                    Swal.fire('Success!', `${themeName} has been activated successfully.`, 'success');
                    btn.prop('disabled', false).html('<i class="bx bx-check"></i> Activate');
                    location.reload();
                }, 1500);
            }
        });
    });
    
    // Save SEO settings
    $('button:contains("Save Settings")').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Saving...');
        
        setTimeout(() => {
            Swal.fire('Success!', 'SEO settings have been saved successfully.', 'success');
            btn.prop('disabled', false).html('<i class="bx bx-save"></i> Save Settings');
        }, 1500);
    });
});
</script>
@endpush
@endsection
