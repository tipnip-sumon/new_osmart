@extends('admin.layouts.app')

@section('title', 'Create New Page')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Create New Page</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.website.pages') }}">Pages</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Page</li>
                    </ol>
                </nav>
            </div>
        </div>

        <form action="{{ route('admin.website.pages.store') }}" method="POST" enctype="multipart/form-data" id="pageForm">
            @csrf
            <div class="row">
                <!-- Main Content Editor -->
                <div class="col-xl-8">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Page Content</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Page Title *</label>
                                <input type="text" class="form-control" name="title" required placeholder="Enter page title">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Page Slug</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ url('/') }}/</span>
                                    <input type="text" class="form-control" name="slug" placeholder="auto-generated">
                                </div>
                                <small class="text-muted">Leave empty to auto-generate from title</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Page Content *</label>
                                <div class="toolbar mb-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('bold')">
                                        <i class="bx bx-bold"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('italic')">
                                        <i class="bx bx-italic"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('underline')">
                                        <i class="bx bx-underline"></i>
                                    </button>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                            Heading
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="formatText('formatBlock', 'h1')">Heading 1</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="formatText('formatBlock', 'h2')">Heading 2</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="formatText('formatBlock', 'h3')">Heading 3</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="formatText('formatBlock', 'p')">Paragraph</a></li>
                                        </ul>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertLink()">
                                        <i class="bx bx-link"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertImage()">
                                        <i class="bx bx-image"></i>
                                    </button>
                                </div>
                                <div contenteditable="true" class="form-control editor-content" id="pageContent" style="min-height: 400px;">
                                    <p>Start writing your page content here...</p>
                                </div>
                                <textarea name="content" id="hiddenContent" style="display: none;"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Excerpt</label>
                                <textarea class="form-control" name="excerpt" rows="3" placeholder="Brief description of the page (optional)"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar Settings -->
                <div class="col-xl-4">
                    <!-- Publish Settings -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Publish Settings</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <option value="private">Private</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Publish Date</label>
                                <input type="datetime-local" class="form-control" name="publish_date">
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="featured" id="featured">
                                    <label class="form-check-label" for="featured">
                                        Featured Page
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="allow_comments" id="allowComments">
                                    <label class="form-check-label" for="allowComments">
                                        Allow Comments
                                    </label>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-secondary" onclick="saveDraft()">
                                    <i class="bx bx-save"></i> Save Draft
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-check"></i> Create Page
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Page Template -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Page Template</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Template</label>
                                <select class="form-control" name="template">
                                    <option value="default">Default</option>
                                    <option value="landing">Landing Page</option>
                                    <option value="contact">Contact</option>
                                    <option value="about">About</option>
                                    <option value="full-width">Full Width</option>
                                    <option value="sidebar">With Sidebar</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Parent Page</label>
                                <select class="form-control" name="parent_id">
                                    <option value="">No Parent</option>
                                    <option value="1">Home</option>
                                    <option value="2">About</option>
                                    <option value="3">Services</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Page Order</label>
                                <input type="number" class="form-control" name="order" value="0" min="0">
                                <small class="text-muted">0 = highest priority</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Featured Image -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Featured Image</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <input type="file" class="form-control" name="featured_image" accept="image/*" id="featuredImage">
                            </div>
                            <div id="imagePreview" class="text-center" style="display: none;">
                                <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeImage()">
                                    <i class="bx bx-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- SEO Settings -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">SEO Settings</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Meta Title</label>
                                <input type="text" class="form-control" name="meta_title" placeholder="Leave empty to use page title">
                                <small class="text-muted">Recommended: 50-60 characters</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Meta Description</label>
                                <textarea class="form-control" name="meta_description" rows="3" placeholder="Brief description for search engines"></textarea>
                                <small class="text-muted">Recommended: 150-160 characters</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control" name="meta_keywords" placeholder="comma, separated, keywords">
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="index" id="index" checked>
                                    <label class="form-check-label" for="index">
                                        Allow search engines to index
                                    </label>
                                </div>
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
// Auto-generate slug from title
document.querySelector('input[name="title"]').addEventListener('input', function() {
    const slugInput = document.querySelector('input[name="slug"]');
    if (!slugInput.value || slugInput.dataset.auto !== 'false') {
        const slug = this.value.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        slugInput.value = slug;
    }
});

// Manual slug editing
document.querySelector('input[name="slug"]').addEventListener('input', function() {
    this.dataset.auto = 'false';
});

// Rich text editor functions
function formatText(command, value = null) {
    document.execCommand(command, false, value);
    document.getElementById('pageContent').focus();
}

function insertLink() {
    const url = prompt('Enter URL:');
    if (url) {
        document.execCommand('createLink', false, url);
    }
}

function insertImage() {
    const url = prompt('Enter image URL:');
    if (url) {
        document.execCommand('insertImage', false, url);
    }
}

// Update hidden textarea before form submission
document.getElementById('pageForm').addEventListener('submit', function() {
    const content = document.getElementById('pageContent').innerHTML;
    document.getElementById('hiddenContent').value = content;
});

// Save draft function
function saveDraft() {
    document.querySelector('select[name="status"]').value = 'draft';
    document.getElementById('pageForm').submit();
}

// Featured image preview
document.getElementById('featuredImage').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

function removeImage() {
    document.getElementById('featuredImage').value = '';
    document.getElementById('imagePreview').style.display = 'none';
}

// Form validation
document.getElementById('pageForm').addEventListener('submit', function(e) {
    const title = document.querySelector('input[name="title"]').value;
    const content = document.getElementById('pageContent').innerHTML;
    
    if (!title.trim()) {
        e.preventDefault();
        if (typeof Swal !== 'undefined') {
            Swal.fire('Error', 'Please enter a page title', 'error');
        } else {
            alert('Please enter a page title');
        }
        return;
    }
    
    if (!content.trim() || content === '<p>Start writing your page content here...</p>') {
        e.preventDefault();
        if (typeof Swal !== 'undefined') {
            Swal.fire('Error', 'Please enter page content', 'error');
        } else {
            alert('Please enter page content');
        }
        return;
    }
    
    // Update hidden content field
    document.getElementById('hiddenContent').value = content;
});

// Template selection preview
document.querySelector('select[name="template"]').addEventListener('change', function() {
    const template = this.value;
    const preview = document.getElementById('templatePreview');
    
    // Show template-specific settings or preview
    if (typeof Swal !== 'undefined') {
        Swal.fire('Template Selected', `Template "${template}" will be applied to this page.`, 'info');
    }
});

// Character count for meta fields
document.querySelector('input[name="meta_title"]')?.addEventListener('input', function() {
    const count = this.value.length;
    const color = count > 60 ? 'text-danger' : count > 50 ? 'text-warning' : 'text-success';
    // Add character count display if needed
});

document.querySelector('textarea[name="meta_description"]')?.addEventListener('input', function() {
    const count = this.value.length;
    const color = count > 160 ? 'text-danger' : count > 150 ? 'text-warning' : 'text-success';
    // Add character count display if needed
});
</script>
@endsection
