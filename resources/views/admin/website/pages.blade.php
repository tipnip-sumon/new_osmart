@extends('admin.layouts.app')

@section('title', 'Page Management')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Page Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pages</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Page Stats -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-primary">
                                    <i class="bx bx-file fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Pages</p>
                                        <h4 class="fw-semibold mt-1">24</h4>
                                    </div>
                                    <div class="text-success fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>12.5%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-success">
                                    <i class="bx bx-check-circle fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Published</p>
                                        <h4 class="fw-semibold mt-1">18</h4>
                                    </div>
                                    <div class="text-success fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>8.3%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-warning">
                                    <i class="bx bx-edit fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Draft</p>
                                        <h4 class="fw-semibold mt-1">5</h4>
                                    </div>
                                    <div class="text-warning fw-semibold">
                                        <i class="ri-arrow-down-s-line"></i>2.1%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-info">
                                    <i class="bx bx-show fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Page Views</p>
                                        <h4 class="fw-semibold mt-1">45.2K</h4>
                                    </div>
                                    <div class="text-info fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>15.7%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Management -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Website Pages</div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.website.pages.create') }}" class="btn btn-primary btn-sm">
                                <i class="bx bx-plus"></i> Add New Page
                            </a>
                            <button class="btn btn-success btn-sm" onclick="bulkPublish()">
                                <i class="bx bx-check"></i> Bulk Publish
                            </button>
                            <button class="btn btn-secondary btn-sm" onclick="exportPages()">
                                <i class="bx bx-download"></i> Export
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Search and Filter -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search pages..." id="searchPages">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="bx bx-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                    <option value="private">Private</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="templateFilter">
                                    <option value="">All Templates</option>
                                    <option value="default">Default</option>
                                    <option value="landing">Landing Page</option>
                                    <option value="contact">Contact</option>
                                    <option value="about">About</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100" onclick="applyFilters()">
                                    <i class="bx bx-filter"></i> Filter
                                </button>
                            </div>
                        </div>

                        <!-- Pages Table -->
                        <div class="table-responsive">
                            <table class="table text-nowrap table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Page Title</th>
                                        <th>Slug</th>
                                        <th>Template</th>
                                        <th>Status</th>
                                        <th>Views</th>
                                        <th>Last Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Sample Page Data -->
                                    <tr>
                                        <td><input type="checkbox" class="page-checkbox" value="1"></td>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">Home Page</h6>
                                                <small class="text-muted">Main landing page</small>
                                            </div>
                                        </td>
                                        <td><code>/</code></td>
                                        <td><span class="badge bg-primary-transparent">Landing</span></td>
                                        <td><span class="badge bg-success">Published</span></td>
                                        <td>12,456</td>
                                        <td>July 20, 2025</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-primary-light" onclick="editPage(1)">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info-light" onclick="viewPage(1)">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success-light" onclick="duplicatePage(1)">
                                                    <i class="bx bx-copy"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger-light" onclick="deletePage(1)">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="page-checkbox" value="2"></td>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">About Us</h6>
                                                <small class="text-muted">Company information</small>
                                            </div>
                                        </td>
                                        <td><code>/about</code></td>
                                        <td><span class="badge bg-info-transparent">About</span></td>
                                        <td><span class="badge bg-success">Published</span></td>
                                        <td>3,245</td>
                                        <td>July 18, 2025</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-primary-light" onclick="editPage(2)">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info-light" onclick="viewPage(2)">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success-light" onclick="duplicatePage(2)">
                                                    <i class="bx bx-copy"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger-light" onclick="deletePage(2)">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="page-checkbox" value="3"></td>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">Contact Us</h6>
                                                <small class="text-muted">Contact form and information</small>
                                            </div>
                                        </td>
                                        <td><code>/contact</code></td>
                                        <td><span class="badge bg-success-transparent">Contact</span></td>
                                        <td><span class="badge bg-success">Published</span></td>
                                        <td>2,567</td>
                                        <td>July 15, 2025</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-primary-light" onclick="editPage(3)">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info-light" onclick="viewPage(3)">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success-light" onclick="duplicatePage(3)">
                                                    <i class="bx bx-copy"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger-light" onclick="deletePage(3)">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="page-checkbox" value="4"></td>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">Privacy Policy</h6>
                                                <small class="text-muted">Privacy and data protection</small>
                                            </div>
                                        </td>
                                        <td><code>/privacy</code></td>
                                        <td><span class="badge bg-secondary-transparent">Default</span></td>
                                        <td><span class="badge bg-warning">Draft</span></td>
                                        <td>456</td>
                                        <td>July 22, 2025</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-primary-light" onclick="editPage(4)">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info-light" onclick="viewPage(4)">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success-light" onclick="duplicatePage(4)">
                                                    <i class="bx bx-copy"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger-light" onclick="deletePage(4)">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="text-muted">Showing 1 to 4 of 24 pages</span>
                            </div>
                            <nav>
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <span class="page-link">Previous</span>
                                    </li>
                                    <li class="page-item active">
                                        <span class="page-link">1</span>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">2</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Page Editor Modal -->
<div class="modal fade" id="pageEditorModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Page</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="pageEditorForm">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Page Title *</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Page Content *</label>
                                <textarea class="form-control" name="content" rows="15" placeholder="Enter page content here..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Page Slug</label>
                                <input type="text" class="form-control" name="slug" placeholder="auto-generated">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Template</label>
                                <select class="form-control" name="template">
                                    <option value="default">Default</option>
                                    <option value="landing">Landing Page</option>
                                    <option value="contact">Contact</option>
                                    <option value="about">About</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <option value="private">Private</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Featured Image</label>
                                <input type="file" class="form-control" name="featured_image" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description</label>
                                <textarea class="form-control" name="meta_description" rows="3" placeholder="SEO meta description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control" name="meta_keywords" placeholder="comma, separated, keywords">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="savePage()">Save Changes</button>
                <button type="button" class="btn btn-primary" onclick="publishPage()">Save & Publish</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Select All Functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.page-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Edit page function
function editPage(pageId) {
    // Sample page data (in real app, fetch from API)
    const pages = {
        1: { title: 'Home Page', slug: '', template: 'landing', status: 'published', content: 'Welcome to our website...' },
        2: { title: 'About Us', slug: 'about', template: 'about', status: 'published', content: 'About our company...' },
        3: { title: 'Contact Us', slug: 'contact', template: 'contact', status: 'published', content: 'Get in touch with us...' },
        4: { title: 'Privacy Policy', slug: 'privacy', template: 'default', status: 'draft', content: 'Our privacy policy...' }
    };
    
    const page = pages[pageId];
    if (page) {
        document.querySelector('input[name="title"]').value = page.title;
        document.querySelector('input[name="slug"]').value = page.slug;
        document.querySelector('select[name="template"]').value = page.template;
        document.querySelector('select[name="status"]').value = page.status;
        document.querySelector('textarea[name="content"]').value = page.content;
        $('#pageEditorModal').modal('show');
    }
}

function savePage() {
    const form = document.getElementById('pageEditorForm');
    const formData = new FormData(form);
    
    if (!formData.get('title') || !formData.get('content')) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('Error', 'Please fill in all required fields', 'error');
        } else {
            alert('Please fill in all required fields');
        }
        return;
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire('Saved!', 'Page has been saved successfully.', 'success').then(() => {
            $('#pageEditorModal').modal('hide');
            // Refresh the page or update the table
            location.reload();
        });
    } else {
        alert('Page saved successfully!');
        $('#pageEditorModal').modal('hide');
        location.reload();
    }
}

function publishPage() {
    const form = document.getElementById('pageEditorForm');
    document.querySelector('select[name="status"]').value = 'published';
    savePage();
}

// View page function
function viewPage(pageId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Info', `Opening page ${pageId} in new tab...`, 'info');
    } else {
        alert(`Opening page ${pageId} in new tab...`);
    }
    // In real app, open the page URL in new tab
}

// Duplicate page function
function duplicatePage(pageId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Duplicate Page',
            text: 'Create a copy of this page?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Duplicate'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Duplicated!', 'Page has been duplicated successfully.', 'success');
            }
        });
    } else {
        if (confirm('Create a copy of this page?')) {
            alert('Page duplicated successfully!');
        }
    }
}

// Delete page function
function deletePage(pageId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete Page',
            text: 'Are you sure you want to delete this page?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Deleted!', 'Page has been deleted.', 'success');
                // Remove row from table or refresh
                location.reload();
            }
        });
    } else {
        if (confirm('Delete this page?')) {
            alert('Page deleted successfully!');
            location.reload();
        }
    }
}

// Bulk publish function
function bulkPublish() {
    const selectedPages = document.querySelectorAll('.page-checkbox:checked');
    
    if (selectedPages.length === 0) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('No Selection', 'Please select pages to publish.', 'warning');
        } else {
            alert('Please select pages to publish.');
        }
        return;
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Bulk Publish',
            text: `Publish ${selectedPages.length} selected pages?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Publish Pages'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Published!', `${selectedPages.length} pages have been published.`, 'success');
            }
        });
    } else {
        if (confirm(`Publish ${selectedPages.length} selected pages?`)) {
            alert(`${selectedPages.length} pages have been published.`);
        }
    }
}

// Export pages function
function exportPages() {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Export Started', 'Pages data export is being prepared.', 'info');
    } else {
        alert('Pages data export started.');
    }
}

// Apply filters function
function applyFilters() {
    const search = document.getElementById('searchPages').value;
    const status = document.getElementById('statusFilter').value;
    const template = document.getElementById('templateFilter').value;
    
    if (typeof Swal !== 'undefined') {
        Swal.fire('Filters Applied', 'Pages filtered successfully.', 'success');
    } else {
        alert('Pages filtered successfully.');
    }
}

// Auto-generate slug from title
document.querySelector('input[name="title"]')?.addEventListener('input', function() {
    const slug = this.value.toLowerCase()
        .replace(/[^a-z0-9 -]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    document.querySelector('input[name="slug"]').value = slug;
});
</script>
@endsection
