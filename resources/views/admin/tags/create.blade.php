@extends('admin.layouts.app')

@section('title', 'Create Tag')

@push('styles')
<style>
    .form-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .form-section h6 {
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e9ecef;
    }
    .slug-preview {
        background: #e3f2fd;
        border: 1px solid #90caf9;
        border-radius: 4px;
        padding: 8px 12px;
        font-family: 'Courier New', monospace;
        color: #1565c0;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .slug-preview.updated {
        background: #e8f5e8;
        border-color: #4caf50;
        color: #2e7d32;
        animation: highlightSlug 0.5s ease;
    }
    @keyframes highlightSlug {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    .validation-errors {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .is-invalid {
        border-color: #dc3545;
    }
    .invalid-feedback {
        display: block;
    }
    #autoGenBadge {
        animation: pulse 2s infinite;
        font-size: 0.7rem;
    }
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
    .slug-generating {
        position: relative;
        overflow: hidden;
    }
    .slug-generating::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        animation: shimmer 1.5s infinite;
    }
    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }
    .manual-edit-mode .slug-preview {
        background: #fff3cd;
        border-color: #ffc107;
        color: #856404;
    }
    .auto-generated-field {
        background: linear-gradient(45deg, #f8f9fa, #e9ecef);
        cursor: not-allowed;
    }
    .auto-generated-field:focus {
        background: linear-gradient(45deg, #e3f2fd, #bbdefb);
        border-color: #2196f3;
    }
    .slug-input-highlight {
        animation: highlightInput 0.6s ease;
    }
    @keyframes highlightInput {
        0% { background-color: #fff; }
        50% { background-color: #e8f5e8; }
        100% { background-color: #fff; }
    }
    .suggestion-btn {
        color: #007bff !important;
        text-decoration: underline !important;
        font-size: 0.9rem;
        margin: 0 2px;
    }
    .suggestion-btn:hover {
        color: #0056b3 !important;
        background: rgba(0, 123, 255, 0.1) !important;
        padding: 2px 4px !important;
        border-radius: 3px !important;
    }
    .valid-feedback {
        display: block !important;
        color: #28a745;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .validation-loading {
        position: relative;
    }
    .validation-loading::after {
        content: '';
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #007bff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: translateY(-50%) rotate(0deg); }
        100% { transform: translateY(-50%) rotate(360deg); }
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Create Tag</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tags.index') }}">Tags</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="validation-errors">
                <h6 class="text-danger mb-3"><i class="bx bx-error-circle me-2"></i>Please fix the following errors:</h6>
                <ul class="mb-0 text-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Form -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">Tag Information</div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bx bx-arrow-back me-1"></i>Back to List
                            </a>
                        </div>
                    </div>
                    
                    <form action="{{ route('admin.tags.store') }}" method="POST" id="tagForm">
                        @csrf
                        <div class="card-body">
                            <!-- Basic Information Section -->
                            <div class="form-section">
                                <h6><i class="bx bx-info-circle me-2"></i>Basic Information</h6>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Tag Name <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" 
                                                   name="name" 
                                                   value="{{ old('name') }}" 
                                                   placeholder="e.g., Technology Products"
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                <i class="bx bx-info-circle me-1"></i>This is the display name for your tag
                                                <button type="button" class="btn btn-link btn-sm p-0 ms-2" id="demoBtn">
                                                    <i class="bx bx-play-circle me-1"></i>Try Demo
                                                </button>
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="slug" class="form-label">
                                                Slug <span class="text-danger">*</span>
                                                <span class="badge bg-info ms-2" id="autoGenBadge">
                                                    <i class="bx bx-magic-wand me-1"></i>Auto-Generated
                                                </span>
                                            </label>
                                            <div class="input-group">
                                                <input type="text" 
                                                       class="form-control @error('slug') is-invalid @enderror" 
                                                       id="slug" 
                                                       name="slug" 
                                                       value="{{ old('slug') }}" 
                                                       placeholder="Will be auto-generated from tag name...">
                                                <button class="btn btn-outline-secondary" type="button" id="editSlugBtn" title="Switch to manual edit mode">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                <button class="btn btn-outline-primary" type="button" id="regenSlugBtn" title="Regenerate from name" style="display: none;">
                                                    <i class="bx bx-refresh"></i>
                                                </button>
                                            </div>
                                            @error('slug')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted" id="slugHelp">
                                                <i class="bx bx-info-circle me-1"></i>URL-friendly version auto-generated from tag name
                                            </small>
                                            <div class="mt-2">
                                                <strong>Preview URL:</strong>
                                                <div class="slug-preview" id="slugPreview">
                                                    {{ url('/tags/') }}/your-tag-slug
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" 
                                                      name="description" 
                                                      rows="4" 
                                                      placeholder="Enter tag description (optional)">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Optional description for the tag (used for SEO and admin reference)
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Settings Section -->
                            <div class="form-section">
                                <h6><i class="bx bx-cog me-2"></i>Settings</h6>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="color" class="form-label">Tag Color</label>
                                            <input type="color" 
                                                   class="form-control form-control-color @error('color') is-invalid @enderror" 
                                                   id="color" 
                                                   name="color" 
                                                   value="{{ old('color', '#007bff') }}" 
                                                   title="Choose your tag color">
                                            @error('color')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Color used to display this tag
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="sort_order" class="form-label">Sort Order</label>
                                            <input type="number" 
                                                   class="form-control @error('sort_order') is-invalid @enderror" 
                                                   id="sort_order" 
                                                   name="sort_order" 
                                                   value="{{ old('sort_order', 0) }}" 
                                                   min="0"
                                                   placeholder="0">
                                            @error('sort_order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Lower numbers appear first
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       id="is_active" 
                                                       name="is_active" 
                                                       value="1" 
                                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    Active
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Only active tags will be visible on the frontend
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SEO Section -->
                            <div class="form-section">
                                <h6><i class="bx bx-search-alt me-2"></i>SEO Information</h6>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="meta_title" class="form-label">Meta Title</label>
                                            <input type="text" 
                                                   class="form-control @error('meta_title') is-invalid @enderror" 
                                                   id="meta_title" 
                                                   name="meta_title" 
                                                   value="{{ old('meta_title') }}" 
                                                   placeholder="SEO title for this tag"
                                                   maxlength="60">
                                            @error('meta_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                <span id="metaTitleCount">0</span>/60 characters. Leave empty to use tag name.
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                            <input type="text" 
                                                   class="form-control @error('meta_keywords') is-invalid @enderror" 
                                                   id="meta_keywords" 
                                                   name="meta_keywords" 
                                                   value="{{ old('meta_keywords') }}" 
                                                   placeholder="keyword1, keyword2, keyword3">
                                            @error('meta_keywords')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Comma-separated keywords for SEO
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="meta_description" class="form-label">Meta Description</label>
                                            <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                                      id="meta_description" 
                                                      name="meta_description" 
                                                      rows="3" 
                                                      placeholder="SEO description for this tag"
                                                      maxlength="160">{{ old('meta_description') }}</textarea>
                                            @error('meta_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                <span id="metaDescCount">0</span>/160 characters. This will appear in search results.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        <i class="bx bx-info-circle me-1"></i>
                                        Fields marked with <span class="text-danger">*</span> are required
                                    </small>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">
                                        <i class="bx bx-x me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="bx bx-save me-1"></i>Create Tag
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let slugController;
    let isSlugManuallyEdited = false;

    // Auto-generate slug from name with visual feedback
    $('#name').on('input', debounce(function() {
        const name = $(this).val();
        
        if (name && !isSlugManuallyEdited) {
            // Show generating animation
            $('#autoGenBadge').html('<i class="bx bx-loader-alt bx-spin me-1"></i>Generating...');
            generateSlug(name);
        } else if (!name) {
            // Clear slug if name is empty
            $('#slug').val('');
            updateSlugPreview('');
            $('#autoGenBadge').html('<i class="bx bx-magic-wand me-1"></i>Auto-Generated');
        }
    }, 500)); // Increased debounce time for better UX

    // Edit slug button functionality
    $('#editSlugBtn').on('click', function() {
        isSlugManuallyEdited = true;
        $('#autoGenBadge').html('<i class="bx bx-edit me-1"></i>Manual Edit').removeClass('bg-info').addClass('bg-warning');
        $('#slugHelp').html('<i class="bx bx-info-circle me-1"></i>You are now editing the slug manually');
        $('#regenSlugBtn').show();
        $(this).hide();
        $('.form-section').addClass('manual-edit-mode');
        updateAutoGenStyling();
        $('#slug').focus();
    });

    // Regenerate slug button functionality
    $('#regenSlugBtn').on('click', function() {
        const name = $('#name').val();
        if (name) {
            isSlugManuallyEdited = false;
            $('#autoGenBadge').html('<i class="bx bx-loader-alt bx-spin me-1"></i>Regenerating...').removeClass('bg-warning').addClass('bg-info');
            $('#slugHelp').html('<i class="bx bx-info-circle me-1"></i>URL-friendly version auto-generated from tag name');
            $('#editSlugBtn').show();
            $(this).hide();
            $('.form-section').removeClass('manual-edit-mode');
            updateAutoGenStyling();
            
            generateSlug(name);
        }
    });

    // Track manual slug editing only when in manual mode
    $('#slug').on('input', function() {
        if (isSlugManuallyEdited) {
            const slug = $(this).val();
            updateSlugPreview(slug);
            
            if (slug) {
                validateSlug(slug);
            }
        }
    });

    // Prevent manual editing when in auto mode
    $('#slug').on('keydown', function(e) {
        if (!isSlugManuallyEdited) {
            // Allow only certain keys (tab, escape, etc.) but prevent typing
            const allowedKeys = [9, 27, 37, 38, 39, 40]; // tab, escape, arrows
            if (allowedKeys.indexOf(e.keyCode) === -1) {
                e.preventDefault();
                // Show visual feedback
                $(this).addClass('slug-input-highlight');
                setTimeout(() => {
                    $(this).removeClass('slug-input-highlight');
                }, 600);
                
                // Show tooltip that it's auto-generated
                showAutoGenTooltip();
            }
        }
    });

    // Add visual styling to indicate auto-generation
    function updateAutoGenStyling() {
        if (!isSlugManuallyEdited) {
            $('#slug').addClass('auto-generated-field');
        } else {
            $('#slug').removeClass('auto-generated-field');
        }
    }

    // Show tooltip for auto-generated field
    function showAutoGenTooltip() {
        const tooltip = $('<div class="auto-gen-tooltip">This field is auto-generated from the tag name. Click the edit button to modify manually.</div>');
        tooltip.css({
            position: 'absolute',
            background: '#333',
            color: '#fff',
            padding: '8px 12px',
            borderRadius: '4px',
            fontSize: '12px',
            zIndex: 1000,
            top: $('#slug').offset().top - 50,
            left: $('#slug').offset().left,
            maxWidth: '300px'
        });
        
        $('body').append(tooltip);
        setTimeout(() => {
            tooltip.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }

    // Initialize styling
    updateAutoGenStyling();

    // Show auto-generation status on focus
    $('#name').on('focus', function() {
        if (!isSlugManuallyEdited) {
            $('#autoGenBadge').addClass('bg-success').html('<i class="bx bx-magic-wand me-1"></i>Will Auto-Generate');
            setTimeout(() => {
                if (!isSlugManuallyEdited) {
                    $('#autoGenBadge').removeClass('bg-success').addClass('bg-info').html('<i class="bx bx-magic-wand me-1"></i>Auto-Generated');
                }
            }, 2000);
        }
    });

    // Demo functionality
    $('#demoBtn').on('click', function() {
        const examples = [
            'Technology Products',
            'Home & Garden',
            'Sports & Fitness',
            'Fashion & Style',
            'Health & Wellness',
            'Books & Education'
        ];
        
        const randomExample = examples[Math.floor(Math.random() * examples.length)];
        
        // Reset to auto mode if in manual mode
        if (isSlugManuallyEdited) {
            $('#regenSlugBtn').click();
        }
        
        // Clear and type the example
        $('#name').val('').focus();
        typeWriter(randomExample, '#name', 100);
    });

    // Typing animation for demo
    function typeWriter(text, element, speed) {
        let i = 0;
        const timer = setInterval(function() {
            if (i < text.length) {
                $(element).val($(element).val() + text.charAt(i));
                $(element).trigger('input'); // Trigger the slug generation
                i++;
            } else {
                clearInterval(timer);
            }
        }, speed);
    }

    // Meta title character counter
    $('#meta_title').on('input', function() {
        const length = $(this).val().length;
        $('#metaTitleCount').text(length);
        
        if (length > 60) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Meta description character counter
    $('#meta_description').on('input', function() {
        const length = $(this).val().length;
        $('#metaDescCount').text(length);
        
        if (length > 160) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Form submission
    $('#tagForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        
        // Show loading state
        submitBtn.html('<i class="bx bx-loader-alt bx-spin me-1"></i>Creating...')
                 .prop('disabled', true);
        
        // Submit the form
        this.submit();
    });

    // Initialize character counters
    $('#meta_title').trigger('input');
    $('#meta_description').trigger('input');
});

// Debounce function to limit API calls
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Generate slug from name with enhanced visual feedback
function generateSlug(name) {
    // Cancel any existing validation request
    if (typeof slugController !== 'undefined') {
        slugController.abort();
    }

    // Check if name is valid
    if (!name || typeof name !== 'string') {
        console.log('Invalid name provided to generateSlug:', name);
        $('#slug').val('');
        updateSlugPreview('');
        return;
    }

    // Create slug with proper formatting
    const slug = name.toLowerCase()
                     .trim()                       // Remove leading/trailing spaces
                     .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                     .replace(/\s+/g, '-')         // Replace spaces with hyphens
                     .replace(/-+/g, '-')          // Replace multiple hyphens with single
                     .replace(/^-+|-+$/g, '');     // Remove leading/trailing hyphens

    console.log('Generating slug from:', name, 'Result:', slug);

    // Update slug field immediately
    $('#slug').val(slug);
    updateSlugPreview(slug);
    
    // Update badge with success state
    $('#autoGenBadge').html('<i class="bx bx-check me-1"></i>Generated').removeClass('bg-info').addClass('bg-success');
    
    // Reset badge after 1.5 seconds
    setTimeout(() => {
        if (!isSlugManuallyEdited) {
            $('#autoGenBadge').html('<i class="bx bx-magic-wand me-1"></i>Auto-Generated').removeClass('bg-success').addClass('bg-info');
        }
    }, 1500);
    
    // Validate slug if it exists
    if (slug) {
        validateSlug(slug);
    }
}

// Update slug preview with enhanced animation
function updateSlugPreview(slug) {
    const baseUrl = '{{ url("/tags") }}';
    const previewSlug = slug || 'your-tag-slug';
    const fullUrl = `${baseUrl}/${previewSlug}`;
    
    console.log('Updating preview URL:', fullUrl);
    
    const previewElement = $('#slugPreview');
    if (previewElement.length) {
        previewElement.addClass('updated').text(fullUrl);
        
        // Remove highlight after animation
        setTimeout(() => {
            previewElement.removeClass('updated');
        }, 1000);
    }
}

// Validate slug uniqueness with enhanced feedback
function validateSlug(slug) {
    // Check if slug is valid
    if (!slug || typeof slug !== 'string') {
        console.log('Invalid slug provided to validateSlug:', slug);
        return;
    }

    // Cancel any existing request
    if (typeof slugController !== 'undefined') {
        slugController.abort();
    }

    // Show loading state
    const slugInput = $('#slug');
    if (!slugInput.length) {
        console.error('Slug input element not found');
        return;
    }
    
    slugInput.addClass('validation-loading');
    
    // Clear previous feedback
    slugInput.siblings('.invalid-feedback, .valid-feedback, .text-warning').remove();

    slugController = new AbortController();

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        slugInput.removeClass('validation-loading');
        slugInput.after('<div class="text-warning">⚠ Security token not found. Please refresh the page.</div>');
        return;
    }

    fetch(`/admin/tags/validate-slug`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        },
        body: JSON.stringify({ slug: slug }),
        signal: slugController.signal
    })
    .then(response => response.json())
    .then(data => {
        console.log('Validation response:', data);
        
        // Remove loading state
        slugInput.removeClass('validation-loading');
        
        if (data.available) {
            slugInput.removeClass('is-invalid').addClass('is-valid');
            
            // Show success message
            slugInput.after('<div class="valid-feedback">✓ This slug is available</div>');
            
        } else {
            slugInput.removeClass('is-valid').addClass('is-invalid');
            
            // Create detailed error message with suggestions
            let errorMessage = 'This slug is already in use.';
            if (data.suggestions && data.suggestions.length > 0) {
                errorMessage += '<br><small>Try these alternatives: ';
                const suggestions = data.suggestions.slice(0, 3).map(suggestion => 
                    `<button type="button" class="suggestion-btn" data-slug="${suggestion}">${suggestion}</button>`
                ).join(' ');
                errorMessage += suggestions + '</small>';
            }
            
            slugInput.after(`<div class="invalid-feedback">${errorMessage}</div>`);
            
            // Add click handlers for suggestion buttons
            $('.suggestion-btn').off('click').on('click', function(e) {
                e.preventDefault();
                const suggestedSlug = $(this).data('slug');
                $('#slug').val(suggestedSlug);
                updateSlugPreview(suggestedSlug);
                
                // Highlight the change
                $('#slug').addClass('slug-input-highlight');
                setTimeout(() => {
                    $('#slug').removeClass('slug-input-highlight');
                }, 600);
                
                validateSlug(suggestedSlug);
            });
        }
    })
    .catch(error => {
        // Remove loading state
        slugInput.removeClass('validation-loading');
        
        if (error.name !== 'AbortError') {
            console.error('Slug validation error:', error);
            
            slugInput.removeClass('is-valid is-invalid');
            slugInput.after('<div class="text-warning">⚠ Unable to validate slug. Please try again.</div>');
        }
    });
}
</script>
@endpush
