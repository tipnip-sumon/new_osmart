@extends('admin.layouts.app')

@section('title', 'Create Category')
@push('styles')
<style>
    /* Image Upload Styles */
    .image-upload-container {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .image-upload-container:hover {
        border-color: #007bff;
        background: #e7f3ff;
    .image-upload-container.drag-over {
        border-color: #28a745;
        background: #d4edda;
    .image-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 20px;
    .image-preview-item {
        position: relative;
        border: 1px solid #dee2e6;
        overflow: hidden;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    .image-preview-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    .image-preview-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    .image-remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    .image-remove-btn:hover {
        background: #dc3545;
        transform: scale(1.1);
    /* Enhanced SweetAlert Styling for Keyword Notifications */
    .swal-keyword-notification {
        font-size: 14px !important;
    
    .swal-reload-check {
        border-left: 4px solid #f39c12 !important;
    /* Enhanced Keyword Highlighting */
    .keyword-highlight {
        border-color: #28a745 !important;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
    .keyword-excellent {
        background-color: rgba(40, 167, 69, 0.05) !important;
    .keyword-good {
        border-color: #20c997 !important;
        background-color: rgba(32, 201, 151, 0.05) !important;
    .keyword-basic {
        border-color: #ffc107 !important;
        background-color: rgba(255, 193, 7, 0.05) !important;
    /* Field Feedback Messages */
    .field-feedback {
        font-size: 12px;
        margin-top: 5px;
        padding: 4px 8px;
        border-radius: 4px;
    .field-feedback.success {
        color: #155724;
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
    .field-feedback.warning {
        color: #856404;
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
    .field-feedback.error {
        color: #721c24;
        background-color: #f8d7da;
        border: 1px solid #f1b0b7;
    /* Dynamic Loading States */
    .form-select:disabled {
        background-color: #f8f9fa;
        opacity: 0.7;
        cursor: wait;
    .loading-state {
    .loading-state::after {
        content: '';
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #007bff;
        animation: spin 1s linear infinite;
    @keyframes spin {
        0% { transform: translateY(-50%) rotate(0deg); }
        100% { transform: translateY(-50%) rotate(360deg); }
    /* Enhanced Dropdown Styling */
    .form-select option[disabled] {
        color: #6c757d;
        font-weight: bold;
    .form-select option[data-level="2"] {
        color: #495057;
        font-style: italic;
    .category-analysis {
        text-align: left;
        padding: 15px;
        border-radius: 6px;
        margin: 10px 0;
    .category-analysis p {
        margin: 5px 0;
        font-size: 14px;
    .confirmation-details {
    .category-preview {
        background: #e3f2fd;
        padding: 12px;
        border-left: 4px solid #2196f3;
    .creation-progress {
    .progress {
        height: 20px;
        background-color: #e9ecef;
        border-radius: 10px;
    .progress-bar {
        background: linear-gradient(45deg, #007bff, #0056b3);
        transition: width 0.3s ease;
    .error-details ul {
        padding-left: 20px;
    .error-details li {
    /* Keyword input highlighting */
    .form-control.keyword-highlight {
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    /* Real-time feedback styles */
        padding: 5px 10px;
        border: 1px solid #f5c6cb;
</style>
@endpush
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Create Category</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Basic Information -->
                <div class="col-xl-8">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Basic Information</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" placeholder="Enter category name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" name="slug" value="{{ old('slug') }}" placeholder="Auto-generated from name">
                                    @error('slug')
                                    <div class="form-text">Leave empty to auto-generate from category name</div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" 
                                          placeholder="Enter category description">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                    <label for="parent_id" class="form-label">Parent Category</label>
                                    <div class="input-group">
                                        <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                            <option value="">Select Parent Category (Optional)</option>
                                            <!-- Dynamic options will be populated by JavaScript -->
                                        </select>
                                        <button type="button" class="btn btn-outline-primary" id="reload-categories" title="Reload Categories">
                                            <i class="ri-refresh-line"></i>
                                        </button>
                                    </div>
                                    @error('parent_id')
                                    <div class="form-text">
                                        <small class="text-muted">
                                            <i class="ri-information-line"></i> Loading categories from database...
                                        </small>
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                    @error('sort_order')
                                    <div class="form-text">Higher number = appears first</div>
                    </div>
                    <!-- SEO Information -->
                            <div class="card-title">SEO Information</div>
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                       id="meta_title" name="meta_title" value="{{ old('meta_title') }}" 
                                       placeholder="SEO friendly title">
                                @error('meta_title')
                                <div class="form-text">Recommended length: 50-60 characters</div>
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                          id="meta_description" name="meta_description" rows="3" 
                                          placeholder="SEO friendly description">{{ old('meta_description') }}</textarea>
                                @error('meta_description')
                                <div class="form-text">Recommended length: 150-160 characters</div>
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                       id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}" 
                                       placeholder="keyword1, keyword2, keyword3">
                                @error('meta_keywords')
                                <div class="form-text">Separate keywords with commas</div>
                </div>
                <!-- Settings & Media -->
                <div class="col-xl-4">
                    <!-- Category Images -->
                            <div class="card-title">Category Images</div>
                            <small class="text-muted">Upload high-quality category images. First image will be the main category image.</small>
                            <div class="image-upload-container" id="image-upload-area">
                                <div class="upload-content">
                                    <i class="ti ti-cloud-upload" style="font-size: 48px; color: #6c757d; margin-bottom: 15px;"></i>
                                    <h5>Drop image here or click to upload</h5>
                                    <p class="text-muted">Supports: JPG, PNG, GIF, WEBP (Max: 10MB)</p>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button type="button" class="btn btn-primary" onclick="document.getElementById('image-input').click()">
                                            <i class="ti ti-upload"></i> Choose File
                                        <button type="button" class="btn btn-info" onclick="openResizeOptions()">
                                            <i class="ti ti-crop"></i> Resize Options
                                        <a href="{{ route('admin.image-upload.demo') }}" target="_blank" class="btn btn-success">
                                            <i class="ti ti-external-link"></i> Image Demo
                                        </a>
                            
                            <input type="file" id="image-input" name="image" accept="image/*" style="display: none;">
                            <!-- Resize Options Panel -->
                            <div id="resize-options-panel" class="mt-3" style="display: none;">
                                <div class="alert alert-info">
                                    <h6><i class="ti ti-info-circle me-2"></i>Image Resize Options</h6>
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <label class="form-label">Width (px)</label>
                                            <input type="number" class="form-control form-control-sm" id="resize-width" value="800" min="50" max="2000">
                                        </div>
                                            <label class="form-label">Height (px)</label>
                                            <input type="number" class="form-control form-control-sm" id="resize-height" value="600" min="50" max="2000">
                                            <label class="form-label">Quality (%)</label>
                                            <input type="number" class="form-control form-control-sm" id="resize-quality" value="85" min="10" max="100">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox" id="maintain-ratio" checked>
                                                <label class="form-check-label" for="maintain-ratio">
                                                    Maintain Aspect Ratio
                                                </label>
                                            </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-primary" onclick="resizeUploadedImages()">
                                            <i class="ti ti-crop me-1"></i>Resize Images
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="generateThumbnails()">
                                            <i class="ti ti-photo me-1"></i>Generate Thumbnails
                                        <button type="button" class="btn btn-sm btn-warning" onclick="optimizeImages()">
                                            <i class="ti ti-zap me-1"></i>Optimize Images
                            <!-- Image Preview Grid -->
                            <div id="image-preview-grid" class="image-preview-grid"></div>
                            @error('images.*')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                    <!-- Category Settings -->
                            <div class="card-title">Category Settings</div>
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" 
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Featured Category
                                </label>
                                <div class="form-text">Featured categories appear in special sections</div>
                                <input class="form-check-input" type="checkbox" id="show_in_menu" name="show_in_menu" value="1" 
                                       {{ old('show_in_menu', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_in_menu">
                                    Show in Navigation Menu
                                <input class="form-check-input" type="checkbox" id="show_in_footer" name="show_in_footer" value="1" 
                                       {{ old('show_in_footer') ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_in_footer">
                                    Show in Footer Links
                    <!-- Affiliate Commission Settings -->
                            <div class="card-title">
                                <i class="ri-money-dollar-circle-line me-2"></i>Affiliate Commission Settings
                                <label for="commission_type" class="form-label">Commission Type</label>
                                <select class="form-select @error('commission_type') is-invalid @enderror" id="commission_type" name="commission_type">
                                    <option value="">Use Default System Rate</option>
                                    <option value="percentage" {{ old('commission_type') == 'percentage' ? 'selected' : '' }}>Custom Percentage Rate</option>
                                    <option value="fixed" {{ old('commission_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount per Sale</option>
                                    <option value="disabled" {{ old('commission_type') == 'disabled' ? 'selected' : '' }}>Disable Commissions</option>
                                <div class="form-text">
                                    Choose how affiliate commissions are calculated for products in this category
                                @error('commission_type')
                            <div class="mb-3" id="commission_rate_section" style="display: none;">
                                <label for="commission_rate" class="form-label">
                                    Commission Rate 
                                    <span id="commission_rate_unit">(%)</span>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('commission_rate') is-invalid @enderror" 
                                           id="commission_rate" name="commission_rate" value="{{ old('commission_rate') }}" 
                                           step="0.01" min="0" max="100" placeholder="0.00">
                                    <span class="input-group-text" id="commission_rate_symbol">%</span>
                                <div class="form-text" id="commission_help_text">
                                    Enter commission percentage (e.g., 5.50 for 5.5%)
                                @error('commission_rate')
                            <!-- Commission Preview -->
                            <div class="alert alert-info" id="commission_preview" style="display: none;">
                                <h6 class="alert-heading mb-2">
                                    <i class="ri-information-line me-1"></i>Commission Preview
                                </h6>
                                <div id="commission_example"></div>
                            <!-- Commission inheritance info -->
                            <div class="alert alert-secondary" id="commission_inheritance_info">
                                <small>
                                    <i class="ri-information-line me-1"></i>
                                    <strong>Default Behavior:</strong> Products in this category will use the system default commission rate 
                                    ({{ config('affiliate.default_commission_rate', 5) }}%) unless overridden at product level.
                                </small>
                    <!-- Category Statistics (Mock Data) -->
                            <div class="card-title">Category Info</div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Products:</span>
                                <span class="fw-semibold">0</span>
                                <span class="text-muted">Subcategories:</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Created:</span>
                                <span class="fw-semibold">{{ date('M d, Y') }}</span>
            <!-- Action Buttons -->
                <div class="col-xl-12">
                        <div class="card-body d-flex justify-content-between">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-light">
                                <i class="ri-arrow-left-line me-1"></i> Back to Categories
                            </a>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="ri-refresh-line me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i> Create Category
        </form>
    </div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('admin-assets/js/ajax-manager.js') }}"></script>
<script>
// Global commission configuration
window.defaultCommissionRate = {{ config('affiliate.default_commission_rate', 5) }};
class CategoryCreateManager {
    constructor() {
        this.form = document.querySelector('form');
        this.nameField = document.getElementById('name');
        this.slugField = document.getElementById('slug');
        this.parentField = document.getElementById('parent_id');
        this.imageField = document.getElementById('image-input');
        this.previewContainer = document.getElementById('image-preview-grid');
        
        // Image upload variables
        this.selectedImages = [];
        // Track input state to prevent unnecessary notifications
        this.lastInputValue = '';
        this.isInputting = false;
        this.initializeComponents(); 
        this.setupEventListeners();
        this.loadParentCategories();
        this.setupValidation();
        this.setupImageUpload();
        this.setupCommissionSettings(); // Add commission settings functionality
    initializeComponents() {
        // Setup character counters
        this.setupCharacterCounter('meta_title', 60);
        this.setupCharacterCounter('meta_description', 160);
        // Setup slug validation
        this.setupSlugValidation();
        // Initialize form validation
        this.initializeFormValidation();
        // Show welcome message
        this.showWelcomeMessage();
    showWelcomeMessage() {
        Swal.fire({
            title: 'Create New Category',
            text: 'Fill in the form below to create a new category. All fields marked with * are required.',
            icon: 'info',
            timer: 3000,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timerProgressBar: true
        });
    setupEventListeners() {
        // Simple and reliable slug auto-generation
        this.nameField.addEventListener('input', (e) => {
            const name = e.target.value.trim();
            
            // Generate slug immediately for better UX
            if (name.length > 0) {
                const slug = this.generateSlug(name);
                this.slugField.value = slug;
                console.log('Generated slug:', slug, 'from name:', name);
            } else {
                this.slugField.value = '';
            }
            // Visual feedback
            const keywordCount = name.split(/\s+/).filter(word => word.length > 0).length;
            this.addFieldVisualFeedback(name, keywordCount);
        // Debounced handler for API calls and notifications
        this.nameField.addEventListener('input', this.debounce(async (e) => {
            // Only show notifications for meaningful input (at least 3 characters)
            if (name.length >= 3) {
                // Show keyword count notification (less frequent)
                Swal.fire({
                    title: `Category: "${name}"`,
                    text: `Keywords: ${keywordCount} | Characters: ${name.length}`,
                    icon: keywordCount >= 2 ? 'success' : 'info',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'swal-keyword-notification'
                    }
                });
                // Validate slug in background without blocking input
                const slug = this.slugField.value;
                if (slug) {
                    this.validateSlug(slug).catch(error => {
                        console.warn('Slug validation failed:', error);
                    });
                }
                
                // Check for reload status (non-blocking)
                this.checkReloadStatus(name);
            } else if (name.length === 0) {
                // Remove visual feedback when empty
                this.removeFieldVisualFeedback();
                // Only show empty notification if field was previously filled
                if (this.lastInputValue && this.lastInputValue.length > 0) {
                    Swal.fire({
                        title: 'Category Name Cleared',
                        text: 'Category name field is now empty',
                        icon: 'warning',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: true
            // Store last input value for comparison
            this.lastInputValue = name;
        }, 1000)); // Longer debounce for API calls
        // Manual slug editing with validation (improved debounce)
        this.slugField.addEventListener('input', this.debounce(async (e) => {
            const slug = this.generateSlug(e.target.value);
            this.slugField.value = slug;
            // Non-blocking validation
            this.validateSlug(slug).catch(error => {
                console.warn('Slug validation failed:', error);
            });
        }, 600)); // Increased debounce for better UX
        // Image preview with validation
        this.imageField.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                this.handleImageFile(e.target.files[0]); // Handle single file upload
        // Form submission with AJAX and SweetAlert
        this.form.addEventListener('submit', async (e) => {
            e.preventDefault();
            // Check if AJAX is available, otherwise use normal submission
            if (typeof window.ajax === 'undefined') {
                console.log('AJAX not available, using normal form submission');
                    title: 'Submitting...',
                    text: 'Creating category using fallback method',
                    icon: 'info',
                    timer: 2000
                }).then(() => {
                    // Remove event listener and submit normally
                    this.form.removeEventListener('submit', arguments.callee);
                    this.form.submit();
                return;
            await this.handleFormSubmission();
        // Parent category change
        this.parentField.addEventListener('change', (e) => {
            this.validateParentSelection(e.target.value);
        // Reload categories button
        const reloadButton = document.getElementById('reload-categories');
        if (reloadButton) {
            reloadButton.addEventListener('click', () => {
                console.log('Reload button clicked');
                    title: 'Reloading Categories',
                    text: 'Manually reloading parent categories...',
                    timer: 2000,
                    timerProgressBar: true
                this.loadParentCategories();
        }
    async loadParentCategories() {
        try {
            // Check if AJAX is available
                console.log('AJAX not available - skipping parent categories load');
                this.showNoDataMessage();
            // Show loading state in the dropdown
            this.setLoadingState(true);
            // Show loading toast
            const loadingToast = Swal.fire({
                title: 'Loading Categories...',
                text: 'Fetching parent categories from database',
                icon: 'info',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            // Use correct admin route for categories with enhanced debugging
            console.log('Making AJAX call to:', '/admin/categories/api/parent-categories');
            const response = await ajax.get('/admin/categories/api/parent-categories');
            console.log('Raw response:', response);
            if (response.success) {
                // Handle nested response structure from AJAX manager
                const categories = response.data?.data?.categories || response.data?.categories || response.data || [];
                console.log('Extracted categories:', categories);
                if (Array.isArray(categories) && categories.length > 0) {
                    this.populateParentOptions(categories);
                    
                        title: 'Categories Loaded!',
                        text: `${categories.length} parent categories loaded successfully`,
                        icon: 'success',
                        timer: 2000,
                } else {
                    console.warn('No categories found or invalid data structure');
                    this.showNoDataMessage();
                console.error('Response not successful:', response);
                throw new Error('Failed to load categories');
        } catch (error) {
            console.error('Failed to load parent categories:', error);
            this.handleLoadingError();
        } finally {
            this.setLoadingState(false);
    setLoadingState(isLoading) {
        const helpText = this.parentField.parentNode.querySelector('.form-text small');
        if (isLoading) {
            this.parentField.disabled = true;
            if (helpText) {
                helpText.innerHTML = '<i class="ri-loader-4-line"></i> Loading categories...';
                helpText.className = 'text-info';
        } else {
            this.parentField.disabled = false;
                helpText.innerHTML = '<i class="ri-information-line"></i> Select a parent category to create a subcategory';
                helpText.className = 'text-muted';
    populateParentOptions(categories) {
        // Ensure categories is an array
        if (!Array.isArray(categories)) {
            console.warn('Categories data is not an array:', categories);
            return;
        // Store old value if exists (for form validation errors)
        const oldValue = '{{ old("parent_id") }}';
        // Clear existing options except the first one
        const firstOption = this.parentField.querySelector('option[value=""]');
        this.parentField.innerHTML = '';
        if (firstOption) {
            this.parentField.appendChild(firstOption);
            // Create default option if it doesn't exist
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Select Parent Category (Optional)';
            this.parentField.appendChild(defaultOption);
        // Group categories by level for better organization
        const rootCategories = categories.filter(cat => !cat.parent_id || cat.level === 1);
        // Add root categories
        rootCategories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            option.dataset.level = category.level || 1;
            option.dataset.slug = category.slug || '';
            // Mark as selected if it matches old value
            if (oldValue && oldValue == category.id) {
                option.selected = true;
            this.parentField.appendChild(option);
        // Add separator if there are subcategories
        const subCategories = categories.filter(cat => cat.parent_id && cat.level > 1);
        if (subCategories.length > 0) {
            const separator = document.createElement('option');
            separator.disabled = true;
            separator.textContent = 'â”€â”€ Subcategories â”€â”€';
            this.parentField.appendChild(separator);
            // Add subcategories with indentation
            subCategories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                const indent = '  '.repeat((category.level || 1) - 1);
                option.textContent = `${indent}â””â”€ ${category.name}`;
                option.dataset.level = category.level || 2;
                option.dataset.slug = category.slug || '';
                // Mark as selected if it matches old value
                if (oldValue && oldValue == category.id) {
                    option.selected = true;
                this.parentField.appendChild(option);
        // Update help text with count
        if (helpText) {
            helpText.innerHTML = `<i class="ri-check-line"></i> ${categories.length} categories available`;
            helpText.className = 'text-success';
    showNoDataMessage() {
        // Show message when no categories are available
        const option = document.createElement('option');
        option.disabled = true;
        option.textContent = 'No parent categories available';
        this.parentField.appendChild(option);
            helpText.innerHTML = '<i class="ri-information-line"></i> No categories found. This will be a root category.';
            helpText.className = 'text-warning';
            title: 'No Categories Found',
            text: 'No parent categories available. This category will be created as a root category.',
    handleLoadingError() {
        // Show error message and provide fallback
        this.parentField.innerHTML = '<option value="">Select Parent Category (Optional)</option>';
            helpText.innerHTML = '<i class="ri-error-warning-line"></i> Failed to load categories. Using offline mode.';
            helpText.className = 'text-danger';
            title: 'Loading Failed',
            text: 'Could not load parent categories. You can still create a root category.',
            icon: 'warning',
            timer: 4000,
    generateSlug(text) {
        return text.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    async validateSlug(slug) {
        if (!slug) return;
        // Check if AJAX is available
        if (typeof window.ajax === 'undefined') {
            console.log('AJAX not available - skipping slug validation');
            const response = await ajax.get('/admin/categories/validate-slug', { slug });
            const slugField = this.slugField;
                const validationData = response.data?.data || response.data;
                if (validationData.available) {
                    this.setFieldStatus(slugField, 'valid', 'Slug is available');
                    // Show success toast
                        title: 'Slug Available!',
                        text: `"${slug}" is available for use`,
                        timer: 1500,
                    this.setFieldStatus(slugField, 'invalid', 'Slug already exists');
                    // Show error toast
                        title: 'Slug Unavailable',
                        text: `"${slug}" is already in use. Please choose another.`,
                        icon: 'error',
                        timer: 3000,
            console.error('Slug validation error:', error);
            Swal.fire({
                title: 'Validation Error',
                text: 'Could not validate slug. Please try again.',
                icon: 'warning',
    checkReloadStatus(name) {
        // Check if user is potentially reloading or retyping
        const lastInput = localStorage.getItem('category_last_input');
        const currentTime = Date.now();
        const lastTime = localStorage.getItem('category_last_time') || 0;
        // Store current input and time
        localStorage.setItem('category_last_input', name);
        localStorage.setItem('category_last_time', currentTime);
        // Check if similar input was entered recently (within 30 seconds)
        if (lastInput && name === lastInput && (currentTime - lastTime) < 30000) {
                title: 'Reload Detected!',
                text: `You entered "${name}" recently. Are you reloading the page?`,
                icon: 'question',
                timerProgressBar: true,
                customClass: {
                    popup: 'swal-reload-check'
        // Check for common reload patterns
        const reloadPatterns = ['test', 'example', 'sample', 'demo', 'new category'];
        if (reloadPatterns.some(pattern => name.toLowerCase().includes(pattern))) {
                title: 'Testing Mode Detected',
                text: `"${name}" appears to be a test entry. Continue testing?`,
                timer: 2500,
    addFieldVisualFeedback(name, keywordCount) {
        // Throttle visual feedback to prevent flickering during fast typing
        if (this.feedbackTimeout) {
            clearTimeout(this.feedbackTimeout);
        this.feedbackTimeout = setTimeout(() => {
            // Remove existing feedback
            this.removeFieldVisualFeedback();
            // Add visual highlighting based on keyword count
            if (keywordCount >= 3) {
                this.nameField.classList.add('keyword-highlight', 'keyword-excellent');
                this.addFieldFeedbackMessage('success', `âœ“ Excellent! ${keywordCount} keywords detected`);
            } else if (keywordCount >= 2) {
                this.nameField.classList.add('keyword-highlight', 'keyword-good');
                this.addFieldFeedbackMessage('success', `âœ“ Good! ${keywordCount} keywords detected`);
            } else if (keywordCount === 1) {
                this.nameField.classList.add('keyword-highlight', 'keyword-basic');
                this.addFieldFeedbackMessage('warning', `âš  Consider adding more keywords (currently ${keywordCount})`);
            // Show character count feedback
            if (name.length > 50) {
                this.addFieldFeedbackMessage('warning', `âš  Name is quite long (${name.length} characters)`);
            } else if (name.length > 0 && name.length < 3) {
                this.addFieldFeedbackMessage('error', `âœ— Name too short (minimum 3 characters)`);
        }, 50); // Small delay to prevent flickering
    removeFieldVisualFeedback() {
        // Clear feedback timeout if pending
        // Remove all keyword highlight classes
        this.nameField.classList.remove('keyword-highlight', 'keyword-excellent', 'keyword-good', 'keyword-basic');
        // Remove existing feedback messages
        const existingFeedback = this.nameField.parentNode.querySelectorAll('.field-feedback');
        existingFeedback.forEach(el => el.remove());
    addFieldFeedbackMessage(type, message) {
        const feedbackEl = document.createElement('div');
        feedbackEl.className = `field-feedback ${type}`;
        feedbackEl.textContent = message;
        this.nameField.parentNode.appendChild(feedbackEl);
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (feedbackEl.parentNode) {
                feedbackEl.remove();
        }, 5000);
    validateParentSelection(parentId) {
        if (parentId) {
            // Additional validation logic for parent selection
            this.checkParentDepth(parentId);
    async checkParentDepth(parentId) {
            // Use correct admin route for category details
            const response = await ajax.get(`/admin/categories/${parentId}/details`);
                const parent = response.data?.data?.category || response.data?.category;
                if (parent.level >= 2) { // Assuming max 3 levels
                    this.setFieldStatus(this.parentField, 'invalid', 'Maximum nesting level reached');
                        title: 'Nesting Limit Reached',
                        text: 'Maximum category nesting level has been reached. Please select a different parent category.',
                        confirmButtonText: 'Understood'
                    this.setFieldStatus(this.parentField, 'valid', 'Valid parent category');
                        title: 'Valid Parent Selected',
                        text: `Selected "${parent.name}" as parent category`,
            console.error('Parent validation error:', error);
                text: 'Could not validate parent category selection.',
                icon: 'error',
    handleImagePreview(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file
            if (!this.validateImageFile(file)) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.previewImg.src = e.target.result;
                this.previewContainer.style.display = 'block';
                this.setFieldStatus(this.imageField, 'valid', 'Image loaded successfully');
                // Show success message
                    title: 'Image Loaded!',
                    text: `${file.name} loaded successfully`,
                    icon: 'success',
            };
            reader.readAsDataURL(file);
            this.previewContainer.style.display = 'none';
            this.setFieldStatus(this.imageField, '', '');
    validateImageFile(file) {
        // Check file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            this.setFieldStatus(this.imageField, 'invalid', 'Invalid file type. Use JPG, PNG, GIF, or WebP');
                title: 'Invalid File Type',
                text: 'Please select a valid image file (JPG, PNG, GIF, or WebP)',
                confirmButtonText: 'Choose Again'
            return false;
        // Check file size (2MB = 2097152 bytes)
        if (file.size > 2097152) {
            this.setFieldStatus(this.imageField, 'invalid', 'File size must be less than 2MB');
                title: 'File Too Large',
                text: `File size is ${(file.size / 1024 / 1024).toFixed(2)}MB. Maximum allowed size is 2MB.`,
                confirmButtonText: 'Choose Smaller File'
        return true;
    async handleFormSubmission() {
            // Get current form data for enhanced validation
            const categoryName = this.nameField.value.trim();
            const categorySlug = this.slugField.value.trim();
            const keywordCount = categoryName.split(/\s+/).filter(word => word.length > 0).length;
            // Show pre-submission analysis
                title: 'Analyzing Category Data',
                html: `
                    <div class="category-analysis">
                        <p><strong>Name:</strong> ${categoryName}</p>
                        <p><strong>Slug:</strong> ${categorySlug}</p>
                        <p><strong>Keywords:</strong> ${keywordCount}</p>
                        <p><strong>Length:</strong> ${categoryName.length} characters</p>
                `,
                timer: 2000,
            // Validate form before submission
            if (!this.validateForm()) {
            // Enhanced confirmation dialog with reload check
            const result = await Swal.fire({
                title: 'Create Category?',
                    <div class="confirmation-details">
                        <p>Are you sure you want to create this category?</p>
                        <div class="category-preview mt-3">
                            <strong>Category Name:</strong> ${categoryName}<br>
                            <strong>URL Slug:</strong> ${categorySlug}<br>
                            <strong>Keywords:</strong> ${keywordCount}<br>
                            ${keywordCount >= 3 ? '<span class="text-success">âœ“ Good keyword density</span>' : '<span class="text-warning">âš  Consider adding more keywords</span>'}
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Create Category!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: async () => {
                    // Show detailed progress
                    Swal.update({
                        title: 'Creating Category...',
                        html: `
                            <div class="creation-progress">
                                <p>Processing: ${categoryName}</p>
                                <div class="progress mt-2">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                         style="width: 100%"></div>
                        `,
                        allowOutsideClick: false
                    return this.submitFormData();
                },
                allowOutsideClick: () => !Swal.isLoading()
            if (result.isConfirmed && result.value) {
                this.handleSuccess(result.value);
                // Clear localStorage after successful submission
                localStorage.removeItem('category_last_input');
                localStorage.removeItem('category_last_time');
            console.error('Form submission error:', error);
                title: 'Submission Failed',
                    <div class="error-details">
                        <p>Failed to create category. Please check the following:</p>
                        <ul class="text-left mt-2">
                            <li>Internet connection</li>
                            <li>Category name is unique</li>
                            <li>All required fields are filled</li>
                            <li>Image file size (if uploading)</li>
                        </ul>
                confirmButtonText: 'Retry',
                cancelButtonText: 'Reload Page',
                reverseButtons: true
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    // User chose to reload page
                        title: 'Reloading Page...',
                        text: 'The page will reload to reset the form',
                        icon: 'info',
                    }).then(() => {
                        window.location.reload();
    async submitFormData() {
        const formData = new FormData(this.form);
        // Add progress tracking for image upload
        const hasImage = this.imageField.files.length > 0;
        let response;
        if (hasImage) {
            response = await ajax.uploadFile(
                this.form.action,
                formData,
                (progress) => {
                        title: 'Uploading...',
                        text: `Upload progress: ${Math.round(progress)}%`,
                        showConfirmButton: false
            );
            response = await ajax.submitForm(this.form);
        if (!response.success) {
            throw new Error(response.error || 'Failed to create category');
        return response.data;
    validateForm() {
        let isValid = true;
        const errors = [];
        // Validate required fields
        const requiredFields = ['name', 'status'];
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                this.setFieldStatus(field, 'invalid', `${fieldName.charAt(0).toUpperCase() + fieldName.slice(1)} is required`);
                errors.push(`${fieldName.charAt(0).toUpperCase() + fieldName.slice(1)} is required`);
                isValid = false;
        // Validate slug
        if (!this.slugField.value.trim()) {
            this.setFieldStatus(this.slugField, 'invalid', 'Slug is required');
            errors.push('Slug is required');
            isValid = false;
        if (!isValid) {
                title: 'Validation Errors',
                html: '<ul style="text-align: left;">' + errors.map(error => `<li>${error}</li>`).join('') + '</ul>',
                confirmButtonText: 'Fix Errors'
        return isValid;
    handleSuccess(data) {
        // Trigger cache refresh for frontend
        if (typeof localStorage !== 'undefined') {
            localStorage.setItem('categories_updated', Date.now());
        // Clear service worker cache if available
        if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
            navigator.serviceWorker.controller.postMessage({
                type: 'PRODUCT_UPDATED'
        // Show enhanced success message
            title: 'Category Created Successfully!',
            html: `
                <div class="success-details text-center">
                    <div class="mb-3">
                        <i class="ri-check-double-line text-success" style="font-size: 48px;"></i>
                    <p class="mb-2"><strong>Category Name:</strong> ${data.data?.name || 'Unknown'}</p>
                    <p class="mb-2"><strong>Slug:</strong> ${data.data?.slug || 'Unknown'}</p>
                    <p class="mb-3"><strong>Status:</strong> <span class="badge bg-success">${data.data?.status || 'Active'}</span></p>
                    <div class="text-muted">
                        <small>Created on ${new Date().toLocaleString()}</small>
            `,
            icon: 'success',
            showConfirmButton: true,
            confirmButtonText: '<i class="ri-eye-line me-1"></i> View Categories',
            showCancelButton: true,
            cancelButtonText: '<i class="ri-add-line me-1"></i> Create Another',
            reverseButtons: true,
            customClass: {
                popup: 'swal-wide',
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-success'
            },
            buttonsStyling: false,
            allowOutsideClick: false,
            focusConfirm: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading message before redirect
                    title: 'Redirecting...',
                    text: 'Taking you to categories list',
                    allowOutsideClick: false
                    // Redirect to categories list
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        window.location.href = '{{ route("admin.categories.index") }}';
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Reset form for new category
                this.resetFormWithConfirmation();
    handleError(error) {
        console.error('Category creation error:', error);
        if (typeof error === 'object' && error.errors) {
            // Handle validation errors with enhanced display
            const errorMessages = [];
            const fieldErrors = {};
            Object.keys(error.errors).forEach(field => {
                const fieldElement = document.getElementById(field);
                const errorList = Array.isArray(error.errors[field]) ? error.errors[field] : [error.errors[field]];
                if (fieldElement) {
                    this.setFieldStatus(fieldElement, 'invalid', errorList[0]);
                    fieldErrors[field] = errorList;
                errorList.forEach(err => {
                    errorMessages.push(`<strong>${field.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}:</strong> ${err}`);
                title: 'Validation Errors Found',
                    <div class="error-details text-left">
                        <p class="mb-3">Please correct the following errors:</p>
                        <div class="alert alert-danger text-left" style="border-radius: 6px;">
                            <ul class="mb-0">
                                ${errorMessages.map(msg => `<li>${msg}</li>`).join('')}
                            </ul>
                        <p class="text-muted mt-2"><small>Fields with errors have been highlighted in red.</small></p>
                confirmButtonText: '<i class="ri-edit-line me-1"></i> Fix Errors',
                    popup: 'swal-wide',
                    confirmButton: 'btn btn-danger'
                buttonsStyling: false,
                allowOutsideClick: false,
                didOpen: () => {
                    // Auto-focus first error field
                    const firstErrorField = Object.keys(fieldErrors)[0];
                    if (firstErrorField) {
                        const element = document.getElementById(firstErrorField);
                        if (element) {
                            setTimeout(() => element.focus(), 100);
                        }
            // Handle general errors
            const errorMessage = error.message || error || 'An unexpected error occurred while creating the category';
                title: 'Category Creation Failed',
                    <div class="error-details text-center">
                        <div class="mb-3">
                            <i class="ri-error-warning-line text-danger" style="font-size: 48px;"></i>
                        <p class="mb-3">${errorMessage}</p>
                        <div class="text-muted">
                            <small>Please try again or contact support if the problem persists.</small>
                showConfirmButton: true,
                confirmButtonText: '<i class="ri-refresh-line me-1"></i> Try Again',
                cancelButtonText: '<i class="ri-refresh-line me-1"></i> Reload Page',
                    confirmButton: 'btn btn-warning',
                    cancelButton: 'btn btn-danger'
                buttonsStyling: false
                        timerProgressBar: true,
    setupImageUpload() {
        // Setup drag and drop functionality
        const uploadContainer = document.getElementById('image-upload-area');
        const imageInput = document.getElementById('image-input');
        const previewGrid = document.getElementById('image-preview-grid');
        if (!uploadContainer || !imageInput || !previewGrid) {
            console.log('Image upload elements not found, skipping image upload setup');
        // Drag and drop events
        uploadContainer.addEventListener('dragover', (e) => {
            uploadContainer.classList.add('drag-over');
        uploadContainer.addEventListener('dragleave', (e) => {
            uploadContainer.classList.remove('drag-over');
        uploadContainer.addEventListener('drop', (e) => {
            const files = Array.from(e.dataTransfer.files);
            if (files.length > 0) {
                this.handleImageFile(files[0]); // Only handle first file
        uploadContainer.addEventListener('click', () => {
            imageInput.click();
        // Handle file input change
        imageInput.addEventListener('change', (e) => {
                this.handleImageFile(e.target.files[0]); // Only handle first file
    handleImageFile(file) {
        if (file.type.startsWith('image/')) {
            if (file.size <= 10 * 1024 * 1024) { // 10MB limit
                this.selectedImages = [file]; // Replace with single file
                this.createImagePreview(file, 0);
                    title: 'File Too Large',
                    text: `File ${file.name} is too large. Maximum size is 10MB.`,
                    icon: 'warning',
                    timer: 3000,
                text: `File ${file.name} is not a valid image.`,
    createImagePreview(file, index) {
        // Clear existing preview for single image upload
        this.previewContainer.innerHTML = '';
        const reader = new FileReader();
        reader.onload = (e) => {
            const previewItem = document.createElement('div');
            previewItem.className = 'image-preview-item';
            previewItem.innerHTML = `
                <img src="${e.target.result}" alt="Category Image Preview">
                <button type="button" class="image-remove-btn" onclick="categoryManager.removeImage(0)">
                    <i class="ti ti-x"></i>
                </button>
            `;
            this.previewContainer.appendChild(previewItem);
        };
        reader.readAsDataURL(file);
    removeImage(index) {
        this.selectedImages = []; // Clear all images for single upload
        this.previewContainer.innerHTML = ''; // Clear preview
        // Reset file input
        if (imageInput) {
            imageInput.value = '';
            title: 'Image Removed',
            text: 'Image has been removed from upload list',
            timer: 2000,
    updateImagePreviews() {
        this.selectedImages.forEach((file, index) => {
            this.createImagePreview(file, index);
    updateProgress(progress) {
        // SweetAlert handles progress in the preConfirm function
        // This method is kept for compatibility
        console.log(`Upload progress: ${progress}%`);
    // Image processing functions
    openResizeOptions() {
        const panel = document.getElementById('resize-options-panel');
        if (panel) {
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    resizeUploadedImages() {
        const fileInput = document.getElementById('image-input');
        const files = fileInput.files;
        if (files.length === 0) {
                title: 'No Images Selected',
                text: 'Please select images first',
        const width = document.getElementById('resize-width').value;
        const height = document.getElementById('resize-height').value;
        const quality = document.getElementById('resize-quality').value;
        const maintainRatio = document.getElementById('maintain-ratio').checked;
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        formData.append('width', width);
        formData.append('height', height);
        formData.append('quality', quality);
        formData.append('maintain_ratio', maintainRatio ? '1' : '0');
        formData.append('folder', 'categories/resized');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            title: 'Resizing Images...',
            text: 'Processing your images',
        fetch('/admin/image-upload/resize', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                    title: 'Images Resized!',
                    text: `Successfully resized ${data.count} images`,
                console.log('Resized images:', data.data);
                    title: 'Resize Failed',
                    text: 'Error: ' + data.message,
                    icon: 'error',
        .catch(error => {
            console.error('Error:', error);
                title: 'Resize Failed',
                text: 'Failed to resize images',
    generateThumbnails() {
        formData.append('sizes[]', '150x150');
        formData.append('sizes[]', '300x300');
        formData.append('sizes[]', '500x500');
        formData.append('folder', 'categories/thumbnails');
            title: 'Generating Thumbnails...',
            text: 'Creating thumbnail versions',
        fetch('/admin/image-upload/thumbnails', {
                    title: 'Thumbnails Generated!',
                    text: `Successfully generated ${data.count} thumbnails`,
                console.log('Thumbnails:', data.data);
                    title: 'Thumbnail Generation Failed',
                title: 'Thumbnail Generation Failed',
                text: 'Failed to generate thumbnails',
    optimizeImages() {
        formData.append('folder', 'categories/optimized');
            title: 'Optimizing Images...',
            text: 'Compressing images for web',
        fetch('/admin/image-upload/optimize', {
                    title: 'Images Optimized!',
                    text: `Successfully optimized ${data.count} images`,
                // Show savings info
                const totalSavings = data.data.reduce((sum, img) => sum + (img.savings || 0), 0);
                const savingsKB = Math.round(totalSavings / 1024);
                if (savingsKB > 0) {
                        title: 'Space Saved!',
                        text: `Saved ${savingsKB}KB of space`,
                console.log('Optimized images:', data.data);
                    title: 'Optimization Failed',
                title: 'Optimization Failed',
                text: 'Failed to optimize images',
    setFieldStatus(field, status, message) {
        // Remove existing classes
        field.classList.remove('is-valid', 'is-invalid');
        // Remove existing feedback
        const existingFeedback = field.parentNode.querySelector('.valid-feedback, .invalid-feedback');
        if (existingFeedback) {
            existingFeedback.remove();
        if (status) {
            field.classList.add(`is-${status}`);
            if (message) {
                const feedback = document.createElement('div');
                feedback.className = `${status}-feedback`;
                feedback.textContent = message;
                field.parentNode.appendChild(feedback);
    setupCharacterCounter(fieldId, maxLength) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        const counter = document.createElement('div');
        counter.className = 'form-text text-end';
        field.parentNode.appendChild(counter);
        const updateCounter = () => {
            const remaining = maxLength - field.value.length;
            counter.textContent = `${field.value.length}/${maxLength} characters`;
            counter.className = remaining < 0 ? 'form-text text-end text-danger' : 'form-text text-end';
            // Show warning when approaching limit
            if (remaining <= 10 && remaining > 0) {
                    title: 'Character Limit Warning',
                    text: `Only ${remaining} characters remaining for ${fieldId.replace('_', ' ')}`,
        field.addEventListener('input', updateCounter);
        updateCounter();
    setupSlugValidation() {
        // Real-time slug format validation
        this.slugField.addEventListener('input', (e) => {
            const slug = e.target.value;
            const isValid = /^[a-z0-9-]*$/.test(slug) && !slug.startsWith('-') && !slug.endsWith('-');
            if (!isValid && slug) {
                this.setFieldStatus(this.slugField, 'invalid', 'Slug can only contain lowercase letters, numbers, and hyphens');
            } else if (slug) {
                this.setFieldStatus(this.slugField, 'valid', 'Valid slug format');
    initializeFormValidation() {
        // Add Bootstrap validation classes
        this.form.classList.add('needs-validation');
        this.form.noValidate = true;
    async resetFormWithConfirmation() {
        const result = await Swal.fire({
            title: 'Reset Form?',
            text: 'This will clear all entered data. Are you sure?',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Reset!',
            cancelButtonText: 'Keep Data',
            reverseButtons: true
        if (result.isConfirmed) {
            this.form.reset();
            // Clear all validation states
            document.querySelectorAll('.is-valid, .is-invalid').forEach(field => {
                field.classList.remove('is-valid', 'is-invalid');
            // Clear all feedback messages
            document.querySelectorAll('.valid-feedback, .invalid-feedback').forEach(feedback => {
                feedback.remove();
                title: 'Form Reset!',
                text: 'All form data has been cleared.',
                icon: 'success',
    setupValidation() {
        // Initialize form validation system
        this.validationRules = {
            name: {
                required: true,
                minLength: 3,
                maxLength: 100
            slug: {
                pattern: /^[a-z0-9-]+$/,
            description: {
                maxLength: 500
            meta_title: {
                maxLength: 60
            meta_description: {
                maxLength: 160
        // Setup real-time validation
        this.setupRealTimeValidation();
    setupRealTimeValidation() {
        // Add validation listeners to form fields
        Object.keys(this.validationRules).forEach(fieldName => {
            if (field) {
                field.addEventListener('blur', (e) => {
                    this.validateField(fieldName, e.target.value);
    validateField(fieldName, value) {
        const rules = this.validationRules[fieldName];
        const field = document.getElementById(fieldName);
        if (!rules || !field) return true;
        let message = '';
        // Required validation
        if (rules.required && !value.trim()) {
            message = `${fieldName} is required`;
        // Length validation
        if (isValid && rules.minLength && value.length < rules.minLength) {
            message = `${fieldName} must be at least ${rules.minLength} characters`;
        if (isValid && rules.maxLength && value.length > rules.maxLength) {
            message = `${fieldName} must not exceed ${rules.maxLength} characters`;
        // Pattern validation
        if (isValid && rules.pattern && !rules.pattern.test(value)) {
            message = `${fieldName} format is invalid`;
        // Update field status
        this.setFieldStatus(field, isValid ? 'valid' : 'invalid', message);
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func.apply(this, args);
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
}
// Global functions for image processing
window.openResizeOptions = function() {
    if (window.categoryManager) {
        window.categoryManager.openResizeOptions();
};
window.resizeUploadedImages = function() {
        window.categoryManager.resizeUploadedImages();
window.generateThumbnails = function() {
        window.categoryManager.generateThumbnails();
window.optimizeImages = function() {
        window.categoryManager.optimizeImages();
// Additional utility functions with SweetAlert
async function resetForm() {
    const result = await Swal.fire({
        title: 'Reset Form?',
        text: 'Are you sure you want to reset the form? All entered data will be lost.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, Reset!',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        focusCancel: true
    });
    if (result.isConfirmed) {
        document.querySelector('form').reset();
        document.getElementById('imagePreview').style.display = 'none';
        // Clear all validation states
        document.querySelectorAll('.is-valid, .is-invalid').forEach(field => {
            field.classList.remove('is-valid', 'is-invalid');
        // Clear all feedback messages
        document.querySelectorAll('.valid-feedback, .invalid-feedback').forEach(feedback => {
            feedback.remove();
            title: 'Reset Complete!',
            text: 'Form has been reset successfully.',
    setupCommissionSettings() {
        const commissionTypeSelect = document.getElementById('commission_type');
        const commissionRateSection = document.getElementById('commission_rate_section');
        const commissionRateInput = document.getElementById('commission_rate');
        const commissionRateSymbol = document.getElementById('commission_rate_symbol');
        const commissionRateUnit = document.getElementById('commission_rate_unit');
        const commissionHelpText = document.getElementById('commission_help_text');
        const commissionPreview = document.getElementById('commission_preview');
        const commissionExample = document.getElementById('commission_example');
        const inheritanceInfo = document.getElementById('commission_inheritance_info');
        if (!commissionTypeSelect) return;
        // Handle commission type change
        commissionTypeSelect.addEventListener('change', (e) => {
            const type = e.target.value;
            this.updateCommissionSettings(type);
        // Handle commission rate input
        if (commissionRateInput) {
            commissionRateInput.addEventListener('input', (e) => {
                this.updateCommissionPreview();
        // Initialize with current value
        this.updateCommissionSettings(commissionTypeSelect.value);
    updateCommissionSettings(type) {
        // Show/hide rate section
        if (type === 'percentage' || type === 'fixed') {
            commissionRateSection.style.display = 'block';
            inheritanceInfo.style.display = 'none';
            if (type === 'percentage') {
                commissionRateSymbol.textContent = '%';
                commissionRateUnit.textContent = '(%)';
                commissionHelpText.textContent = 'Enter commission percentage (e.g., 5.50 for 5.5%)';
                commissionRateInput.setAttribute('max', '100');
                commissionRateInput.setAttribute('placeholder', '5.00');
            } else if (type === 'fixed') {
                commissionRateSymbol.textContent = 'à§³';
                commissionRateUnit.textContent = '(à§³)';
                commissionHelpText.textContent = 'Enter fixed commission amount in BDT (e.g., 50 for à§³50 per sale)';
                commissionRateInput.removeAttribute('max');
                commissionRateInput.setAttribute('placeholder', '50.00');
            commissionRateSection.style.display = 'none';
            if (type === 'disabled') {
                inheritanceInfo.innerHTML = `
                    <small>
                        <i class="ri-close-circle-line me-1 text-danger"></i>
                        <strong>Commissions Disabled:</strong> No affiliate commissions will be earned for products in this category.
                    </small>
                `;
                inheritanceInfo.className = 'alert alert-warning';
                        <i class="ri-information-line me-1"></i>
                        <strong>Default Behavior:</strong> Products in this category will use the system default commission rate 
                        (${window.defaultCommissionRate || 5}%) unless overridden at product level.
                inheritanceInfo.className = 'alert alert-secondary';
            inheritanceInfo.style.display = 'block';
        this.updateCommissionPreview();
    updateCommissionPreview() {
        const type = commissionTypeSelect.value;
        const rate = parseFloat(commissionRateInput.value) || 0;
        if ((type === 'percentage' || type === 'fixed') && rate > 0) {
            commissionPreview.style.display = 'block';
            let exampleHtml = '';
                const examplePrice = 1000;
                const commission = (examplePrice * rate) / 100;
                exampleHtml = `
                    <div class="d-flex justify-content-between mb-2">
                        <span>Commission Rate:</span>
                        <strong>${rate}%</strong>
                        <span>Example Product Price:</span>
                        <span>à§³${examplePrice.toLocaleString()}</span>
                    <div class="d-flex justify-content-between">
                        <span>Affiliate Earns:</span>
                        <strong class="text-success">à§³${commission.toFixed(2)}</strong>
                        <span>Fixed Commission:</span>
                        <strong>à§³${rate.toFixed(2)}</strong>
                        <span>Affiliate Earns Per Sale:</span>
                        <strong class="text-success">à§³${rate.toFixed(2)}</strong>
                    <small class="text-muted">Regardless of product price</small>
            commissionExample.innerHTML = exampleHtml;
            commissionPreview.style.display = 'none';
// Show help information
function showFormHelp() {
    Swal.fire({
        title: 'Category Creation Help',
        html: `
            <div style="text-align: left;">
                <h6><i class="ti ti-info-circle"></i> Form Guidelines:</h6>
                <ul>
                    <li><strong>Name:</strong> Enter a descriptive category name</li>
                    <li><strong>Slug:</strong> Auto-generated URL-friendly version</li>
                    <li><strong>Parent:</strong> Optional parent category for hierarchy</li>
                    <li><strong>Image:</strong> Max 2MB, JPG/PNG/GIF/WebP formats</li>
                    <li><strong>SEO:</strong> Meta title (50-60 chars), description (150-160 chars)</li>
                </ul>
                <hr>
                <small class="text-muted">All fields marked with * are required</small>
        `,
        icon: 'info',
        confirmButtonText: 'Got it!',
        width: '600px'
// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Test if ajax manager is available
    console.log('DOM loaded, checking ajax manager:', typeof window.ajax);
    // Always initialize CategoryCreateManager - it will handle AJAX availability internally
    console.log('Initializing CategoryCreateManager');
    window.categoryManager = new CategoryCreateManager();
    // Check AJAX availability after a short delay
    setTimeout(() => {
        console.log('After timeout, ajax manager:', typeof window.ajax);
        if (!window.ajax) {
            console.warn('AJAX manager not available - using fallback form submission');
                title: 'Fallback Mode',
                text: 'Using standard form submission. Some features may be limited.',
            console.log('AJAX manager is ready');
    }, 500);
    // Add help button functionality
    if (document.querySelector('.btn-help')) {
        document.querySelector('.btn-help').addEventListener('click', showFormHelp);
});
// Global error handler with SweetAlert
window.addEventListener('unhandledrejection', function(event) {
    console.error('Unhandled promise rejection:', event.reason);
        title: 'Unexpected Error',
        text: 'An unexpected error occurred. Please refresh the page and try again.',
        icon: 'error',
        confirmButtonText: 'Refresh Page',
        cancelButtonText: 'Continue'
    }).then((result) => {
            window.location.reload();
</script>
