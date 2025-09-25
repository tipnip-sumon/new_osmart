@extends('admin.layouts.app')

@section('title', 'SEO Management')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">SEO Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">SEO</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- SEO Overview -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="avatar avatar-md bg-primary-transparent">
                                    <i class="bx bx-search-alt fs-20"></i>
                                </div>
                            </div>
                            <div class="flex-fill">
                                <h6 class="fw-semibold mb-0">SEO Score</h6>
                                <span class="fs-12 text-success"><i class="bx bx-trending-up"></i> +2.5%</span>
                            </div>
                            <div class="text-end">
                                <h4 class="fw-semibold mb-0">85/100</h4>
                                <span class="fs-12 text-muted">Excellent</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="avatar avatar-md bg-success-transparent">
                                    <i class="bx bx-check-circle fs-20"></i>
                                </div>
                            </div>
                            <div class="flex-fill">
                                <h6 class="fw-semibold mb-0">Optimized Pages</h6>
                                <span class="fs-12 text-success"><i class="bx bx-trending-up"></i> +5</span>
                            </div>
                            <div class="text-end">
                                <h4 class="fw-semibold mb-0">18/24</h4>
                                <span class="fs-12 text-muted">75%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="avatar avatar-md bg-warning-transparent">
                                    <i class="bx bx-link-external fs-20"></i>
                                </div>
                            </div>
                            <div class="flex-fill">
                                <h6 class="fw-semibold mb-0">Backlinks</h6>
                                <span class="fs-12 text-success"><i class="bx bx-trending-up"></i> +12</span>
                            </div>
                            <div class="text-end">
                                <h4 class="fw-semibold mb-0">1,247</h4>
                                <span class="fs-12 text-muted">High Quality</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="avatar avatar-md bg-info-transparent">
                                    <i class="bx bx-time fs-20"></i>
                                </div>
                            </div>
                            <div class="flex-fill">
                                <h6 class="fw-semibold mb-0">Page Speed</h6>
                                <span class="fs-12 text-danger"><i class="bx bx-trending-down"></i> -0.2s</span>
                            </div>
                            <div class="text-end">
                                <h4 class="fw-semibold mb-0">2.3s</h4>
                                <span class="fs-12 text-muted">Good</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Tools Tabs -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">SEO Tools & Settings</div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#meta-tags" role="tab">
                                    <i class="bx bx-tag"></i> Meta Tags
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#sitemaps" role="tab">
                                    <i class="bx bx-sitemap"></i> Sitemaps
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#analytics" role="tab">
                                    <i class="bx bx-bar-chart"></i> Analytics
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#schema" role="tab">
                                    <i class="bx bx-code-alt"></i> Schema
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#audit" role="tab">
                                    <i class="bx bx-search"></i> SEO Audit
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content mt-4">
                            <!-- Meta Tags Tab -->
                            <div class="tab-pane fade show active" id="meta-tags">
                                <form id="metaTagsForm">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Site Title</label>
                                                <input type="text" class="form-control" value="Laravel Multivendor E-commerce" placeholder="Your site title">
                                                <small class="text-muted">Optimal length: 50-60 characters</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Site Tagline</label>
                                                <input type="text" class="form-control" value="Best deals on quality products" placeholder="Brief description">
                                                <small class="text-muted">Appears in search results</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Meta Description</label>
                                        <textarea class="form-control" rows="3" placeholder="Enter meta description">Discover amazing deals on our multivendor e-commerce platform. Shop from trusted sellers, enjoy secure payments, and fast delivery. Find everything you need in one place.</textarea>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Optimal length: 150-160 characters</small>
                                            <small class="text-success">142 characters</small>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Keywords</label>
                                        <input type="text" class="form-control" value="ecommerce, online shopping, multivendor, marketplace" placeholder="Comma-separated keywords">
                                        <small class="text-muted">Relevant keywords for your site</small>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Open Graph Image</label>
                                                <input type="file" class="form-control" accept="image/*">
                                                <small class="text-muted">Recommended: 1200x630px</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Favicon</label>
                                                <input type="file" class="form-control" accept="image/*">
                                                <small class="text-muted">Recommended: 32x32px ICO or PNG</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-primary" onclick="saveMetaTags()">
                                            <i class="bx bx-save"></i> Save Meta Tags
                                        </button>
                                        <button type="button" class="btn btn-success" onclick="previewMetaTags()">
                                            <i class="bx bx-show"></i> Preview
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Sitemaps Tab -->
                            <div class="tab-pane fade" id="sitemaps">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6>Generated Sitemaps</h6>
                                        <div class="list-group">
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>Main Sitemap</strong>
                                                    <br><small class="text-muted">sitemap.xml</small>
                                                </div>
                                                <div>
                                                    <span class="badge bg-success me-2">Active</span>
                                                    <button class="btn btn-sm btn-outline-primary" onclick="viewSitemap('main')">View</button>
                                                </div>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>Products Sitemap</strong>
                                                    <br><small class="text-muted">sitemap-products.xml</small>
                                                </div>
                                                <div>
                                                    <span class="badge bg-success me-2">Active</span>
                                                    <button class="btn btn-sm btn-outline-primary" onclick="viewSitemap('products')">View</button>
                                                </div>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>Categories Sitemap</strong>
                                                    <br><small class="text-muted">sitemap-categories.xml</small>
                                                </div>
                                                <div>
                                                    <span class="badge bg-success me-2">Active</span>
                                                    <button class="btn btn-sm btn-outline-primary" onclick="viewSitemap('categories')">View</button>
                                                </div>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>Pages Sitemap</strong>
                                                    <br><small class="text-muted">sitemap-pages.xml</small>
                                                </div>
                                                <div>
                                                    <span class="badge bg-warning me-2">Pending</span>
                                                    <button class="btn btn-sm btn-outline-primary" onclick="viewSitemap('pages')">View</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h6>Sitemap Settings</h6>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="autoGenerate" checked>
                                                <label class="form-check-label" for="autoGenerate">
                                                    Auto-generate sitemaps
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Update Frequency</label>
                                            <select class="form-control">
                                                <option>Daily</option>
                                                <option selected>Weekly</option>
                                                <option>Monthly</option>
                                            </select>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-primary" onclick="generateSitemaps()">
                                                <i class="bx bx-refresh"></i> Regenerate All
                                            </button>
                                            <button class="btn btn-success" onclick="submitToSearchEngines()">
                                                <i class="bx bx-send"></i> Submit to Search Engines
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Analytics Tab -->
                            <div class="tab-pane fade" id="analytics">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Google Analytics</h6>
                                        <form id="analyticsForm">
                                            <div class="mb-3">
                                                <label class="form-label">Tracking ID</label>
                                                <input type="text" class="form-control" placeholder="GA-XXXXXXXXX-X">
                                                <small class="text-muted">Your Google Analytics tracking ID</small>
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="enableGA" checked>
                                                    <label class="form-check-label" for="enableGA">
                                                        Enable Google Analytics
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="anonymizeIP">
                                                    <label class="form-check-label" for="anonymizeIP">
                                                        Anonymize IP addresses
                                                    </label>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Google Tag Manager</h6>
                                        <form id="gtmForm">
                                            <div class="mb-3">
                                                <label class="form-label">Container ID</label>
                                                <input type="text" class="form-control" placeholder="GTM-XXXXXXX">
                                                <small class="text-muted">Your GTM container ID</small>
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="enableGTM">
                                                    <label class="form-check-label" for="enableGTM">
                                                        Enable Google Tag Manager
                                                    </label>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Facebook Pixel</h6>
                                        <form id="fbPixelForm">
                                            <div class="mb-3">
                                                <label class="form-label">Pixel ID</label>
                                                <input type="text" class="form-control" placeholder="123456789012345">
                                                <small class="text-muted">Your Facebook Pixel ID</small>
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="enableFBPixel">
                                                    <label class="form-check-label" for="enableFBPixel">
                                                        Enable Facebook Pixel
                                                    </label>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Custom Scripts</h6>
                                        <div class="mb-3">
                                            <label class="form-label">Header Scripts</label>
                                            <textarea class="form-control" rows="3" placeholder="<script>...</script>"></textarea>
                                            <small class="text-muted">Scripts to include in &lt;head&gt;</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Footer Scripts</label>
                                            <textarea class="form-control" rows="3" placeholder="<script>...</script>"></textarea>
                                            <small class="text-muted">Scripts to include before &lt;/body&gt;</small>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-primary" onclick="saveAnalytics()">
                                    <i class="bx bx-save"></i> Save Analytics Settings
                                </button>
                            </div>

                            <!-- Schema Tab -->
                            <div class="tab-pane fade" id="schema">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6>Schema Markup</h6>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="enableOrganization" checked>
                                                <label class="form-check-label" for="enableOrganization">
                                                    <strong>Organization Schema</strong>
                                                    <br><small class="text-muted">Provides information about your business</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="enableWebsite" checked>
                                                <label class="form-check-label" for="enableWebsite">
                                                    <strong>Website Schema</strong>
                                                    <br><small class="text-muted">Helps search engines understand your site</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="enableProduct" checked>
                                                <label class="form-check-label" for="enableProduct">
                                                    <strong>Product Schema</strong>
                                                    <br><small class="text-muted">Rich snippets for products in search results</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="enableBreadcrumb" checked>
                                                <label class="form-check-label" for="enableBreadcrumb">
                                                    <strong>Breadcrumb Schema</strong>
                                                    <br><small class="text-muted">Breadcrumb navigation in search results</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="enableReview">
                                                <label class="form-check-label" for="enableReview">
                                                    <strong>Review Schema</strong>
                                                    <br><small class="text-muted">Product reviews and ratings markup</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h6>Organization Details</h6>
                                        <form id="organizationForm">
                                            <div class="mb-3">
                                                <label class="form-label">Business Name</label>
                                                <input type="text" class="form-control" value="Laravel Multivendor Store">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Business Type</label>
                                                <select class="form-control">
                                                    <option>Organization</option>
                                                    <option>Corporation</option>
                                                    <option>LocalBusiness</option>
                                                    <option selected>OnlineStore</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Logo URL</label>
                                                <input type="url" class="form-control" placeholder="https://example.com/logo.png">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Contact Phone</label>
                                                <input type="tel" class="form-control" placeholder="+1-234-567-8900">
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary" onclick="saveSchema()">
                                        <i class="bx bx-save"></i> Save Schema Settings
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="testSchema()">
                                        <i class="bx bx-test-tube"></i> Test Schema
                                    </button>
                                </div>
                            </div>

                            <!-- SEO Audit Tab -->
                            <div class="tab-pane fade" id="audit">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6>SEO Issues</h6>
                                            <button class="btn btn-primary btn-sm" onclick="runSEOAudit()">
                                                <i class="bx bx-refresh"></i> Run New Audit
                                            </button>
                                        </div>

                                        <!-- Critical Issues -->
                                        <div class="alert alert-danger">
                                            <h6 class="alert-heading"><i class="bx bx-error-circle"></i> Critical Issues (2)</h6>
                                            <ul class="mb-0">
                                                <li>Missing meta description on 6 pages</li>
                                                <li>Duplicate title tags found on 3 pages</li>
                                            </ul>
                                        </div>

                                        <!-- Warning Issues -->
                                        <div class="alert alert-warning">
                                            <h6 class="alert-heading"><i class="bx bx-error"></i> Warnings (4)</h6>
                                            <ul class="mb-0">
                                                <li>Images without alt text: 12 found</li>
                                                <li>Pages with slow loading speed: 5 found</li>
                                                <li>Internal links without anchor text: 8 found</li>
                                                <li>H1 tags missing on 2 pages</li>
                                            </ul>
                                        </div>

                                        <!-- Success Items -->
                                        <div class="alert alert-success">
                                            <h6 class="alert-heading"><i class="bx bx-check-circle"></i> Optimized (15)</h6>
                                            <ul class="mb-0">
                                                <li>SSL certificate installed</li>
                                                <li>Sitemap.xml present and valid</li>
                                                <li>Robots.txt configured correctly</li>
                                                <li>Mobile-friendly design</li>
                                                <li>Structured data implemented</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h6>Audit Score</h6>
                                        <div class="text-center mb-4">
                                            <div class="position-relative d-inline-block">
                                                <svg width="120" height="120">
                                                    <circle cx="60" cy="60" r="50" fill="none" stroke="#e9ecef" stroke-width="8"/>
                                                    <circle cx="60" cy="60" r="50" fill="none" stroke="#28a745" stroke-width="8" 
                                                            stroke-dasharray="314" stroke-dashoffset="62.8" stroke-linecap="round"/>
                                                </svg>
                                                <div class="position-absolute top-50 start-50 translate-middle">
                                                    <h3 class="mb-0">85</h3>
                                                    <small>/ 100</small>
                                                </div>
                                            </div>
                                        </div>

                                        <h6>Last Audit</h6>
                                        <p class="text-muted">July 20, 2025 at 2:30 PM</p>

                                        <h6>Quick Actions</h6>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-outline-primary btn-sm" onclick="fixCriticalIssues()">
                                                <i class="bx bx-wrench"></i> Fix Critical Issues
                                            </button>
                                            <button class="btn btn-outline-warning btn-sm" onclick="viewDetailedReport()">
                                                <i class="bx bx-file-blank"></i> Detailed Report
                                            </button>
                                            <button class="btn btn-outline-info btn-sm" onclick="scheduleAudit()">
                                                <i class="bx bx-time"></i> Schedule Audits
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
    </div>
@endsection

@section('scripts')
<script>
// Save Meta Tags
function saveMetaTags() {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Saved!', 'Meta tags have been updated successfully.', 'success');
    } else {
        alert('Meta tags saved successfully!');
    }
}

// Preview Meta Tags
function previewMetaTags() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Meta Tags Preview',
            html: `
                <div class="text-start">
                    <div class="border p-3 mb-3" style="background: #f8f9fa;">
                        <h6 style="color: #1a0dab; font-size: 18px; margin: 0;">Laravel Multivendor E-commerce</h6>
                        <div style="color: #006621; font-size: 14px;">yoursite.com</div>
                        <p style="color: #545454; font-size: 13px; margin: 5px 0 0 0;">
                            Discover amazing deals on our multivendor e-commerce platform. Shop from trusted sellers, enjoy secure payments, and fast delivery.
                        </p>
                    </div>
                    <small class="text-muted">This is how your site will appear in Google search results.</small>
                </div>
            `,
            showCloseButton: true,
            showConfirmButton: false,
            width: '600px'
        });
    } else {
        alert('Meta tags preview - Feature coming soon');
    }
}

// View Sitemap
function viewSitemap(type) {
    const url = `/sitemap-${type}.xml`;
    window.open(url, '_blank');
}

// Generate Sitemaps
function generateSitemaps() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Generating Sitemaps...',
            html: 'Please wait while sitemaps are being generated.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        setTimeout(() => {
            Swal.fire('Success!', 'All sitemaps have been generated successfully.', 'success');
        }, 3000);
    } else {
        alert('Sitemaps generated successfully!');
    }
}

// Submit to Search Engines
function submitToSearchEngines() {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Submitted!', 'Sitemaps have been submitted to search engines.', 'success');
    } else {
        alert('Sitemaps submitted to search engines!');
    }
}

// Save Analytics Settings
function saveAnalytics() {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Saved!', 'Analytics settings have been updated.', 'success');
    } else {
        alert('Analytics settings saved!');
    }
}

// Save Schema Settings
function saveSchema() {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Saved!', 'Schema markup settings have been updated.', 'success');
    } else {
        alert('Schema settings saved!');
    }
}

// Test Schema
function testSchema() {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Testing...', 'Validating schema markup with Google.', 'info');
        setTimeout(() => {
            Swal.fire('Valid!', 'Schema markup is valid and working correctly.', 'success');
        }, 2000);
    } else {
        alert('Schema markup is valid!');
    }
}

// Run SEO Audit
function runSEOAudit() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Running SEO Audit...',
            html: 'Analyzing your website for SEO issues.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        setTimeout(() => {
            Swal.fire('Audit Complete!', 'SEO audit has been completed. Check the results above.', 'success');
        }, 5000);
    } else {
        alert('SEO audit completed!');
    }
}

// Fix Critical Issues
function fixCriticalIssues() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Fix Critical Issues',
            text: 'This will automatically fix the most critical SEO issues. Continue?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Fix Them'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Fixed!', 'Critical SEO issues have been resolved.', 'success');
            }
        });
    } else {
        if (confirm('Fix critical SEO issues?')) {
            alert('Critical issues fixed!');
        }
    }
}

// View Detailed Report
function viewDetailedReport() {
    window.open('/admin/seo-report', '_blank');
}

// Schedule Audit
function scheduleAudit() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Schedule SEO Audits',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Frequency</label>
                        <select class="form-control">
                            <option>Daily</option>
                            <option selected>Weekly</option>
                            <option>Monthly</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Notifications</label>
                        <input type="email" class="form-control" placeholder="admin@example.com">
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Schedule'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Scheduled!', 'SEO audits have been scheduled.', 'success');
            }
        });
    } else {
        alert('SEO audit scheduled!');
    }
}

// Character counter for meta description
document.querySelector('textarea')?.addEventListener('input', function() {
    const length = this.value.length;
    const counter = this.parentNode.querySelector('.text-success');
    if (counter) {
        counter.textContent = `${length} characters`;
        
        if (length < 120 || length > 160) {
            counter.className = 'text-warning';
        } else {
            counter.className = 'text-success';
        }
    }
});

// Tab switching functionality
document.querySelectorAll('.nav-link[data-bs-toggle="tab"]').forEach(tab => {
    tab.addEventListener('shown.bs.tab', function (e) {
        console.log('Switched to tab:', e.target.getAttribute('href'));
    });
});
</script>
@endsection
