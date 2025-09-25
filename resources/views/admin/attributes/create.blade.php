@extends('admin.layouts.app')

@section('title', 'Create New Attribute')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Attribute</h1>
        <div class="d-flex">
            <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary btn-sm shadow-sm me-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Attributes
            </a>
        </div>
    </div>

    <!-- Create Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Attribute Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.attributes.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="display_name" class="form-label">Display Name</label>
                                    <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                           id="display_name" name="display_name" value="{{ old('display_name') }}">
                                    <small class="form-text text-muted">Leave empty to use name</small>
                                    @error('display_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" name="slug" value="{{ old('slug') }}">
                                    <small class="form-text text-muted">Leave empty to auto-generate from name</small>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        @foreach($attributeTypes as $key => $label)
                                            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="default_value" class="form-label">Default Value</label>
                                    <input type="text" class="form-control @error('default_value') is-invalid @enderror" 
                                           id="default_value" name="default_value" value="{{ old('default_value') }}">
                                    @error('default_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="placeholder" class="form-label">Placeholder</label>
                                    <input type="text" class="form-control @error('placeholder') is-invalid @enderror" 
                                           id="placeholder" name="placeholder" value="{{ old('placeholder') }}">
                                    @error('placeholder')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="help_text" class="form-label">Help Text</label>
                            <textarea class="form-control @error('help_text') is-invalid @enderror" 
                                      id="help_text" name="help_text" rows="2">{{ old('help_text') }}</textarea>
                            <small class="form-text text-muted">Additional help text for users</small>
                            @error('help_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="validation_rules" class="form-label">Validation Rules</label>
                            <input type="text" class="form-control @error('validation_rules') is-invalid @enderror" 
                                   id="validation_rules" name="validation_rules" value="{{ old('validation_rules') }}"
                                   placeholder="e.g., required|min:2|max:50">
                            <small class="form-text text-muted">Laravel validation rules separated by pipes</small>
                            @error('validation_rules')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_required" name="is_required" value="1" 
                                               {{ old('is_required') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_required">Required</label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_filterable" name="is_filterable" value="1" 
                                               {{ old('is_filterable') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_filterable">Filterable</label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_variation" name="is_variation" value="1" 
                                               {{ old('is_variation') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_variation">Product Variation</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_global" name="is_global" value="1" 
                                               {{ old('is_global') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_global">Global Attribute</label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="admin_only" name="admin_only" value="1" 
                                               {{ old('admin_only') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="admin_only">Admin Only</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Attribute
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Attribute Options</h6>
                </div>
                <div class="card-body">
                    <div id="optionsContainer" style="display: none;">
                        <p class="text-muted">Add options for select, multiselect, radio, or checkbox types:</p>
                        <div id="optionsList">
                            <!-- Options will be added here dynamically -->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addOption">
                            <i class="fas fa-plus"></i> Add Option
                        </button>
                    </div>
                    <div id="noOptionsMessage">
                        <p class="text-muted">Select a type that supports options to configure them.</p>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Info</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <strong>Attribute Types:</strong><br>
                        • <strong>Text:</strong> Simple text input<br>
                        • <strong>Number:</strong> Numeric values<br>
                        • <strong>Select:</strong> Single choice dropdown<br>
                        • <strong>Multiselect:</strong> Multiple choice<br>
                        • <strong>Color:</strong> Color picker<br>
                        • <strong>Boolean:</strong> Yes/No values<br>
                        • <strong>Date:</strong> Date picker
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const typeSelect = document.getElementById('type');
    const optionsContainer = document.getElementById('optionsContainer');
    const noOptionsMessage = document.getElementById('noOptionsMessage');
    const addOptionBtn = document.getElementById('addOption');
    const optionsList = document.getElementById('optionsList');
    
    let optionIndex = 0;
    
    // Auto-generate slug from name
    nameInput.addEventListener('input', function() {
        if (slugInput.value === '' || slugInput.dataset.autoGenerated === 'true') {
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            slugInput.value = slug;
            slugInput.dataset.autoGenerated = 'true';
        }
    });
    
    // Manual slug editing
    slugInput.addEventListener('input', function() {
        this.dataset.autoGenerated = 'false';
    });
    
    // Show/hide options based on type
    typeSelect.addEventListener('change', function() {
        const typesWithOptions = ['select', 'multiselect', 'radio', 'checkbox'];
        if (typesWithOptions.includes(this.value)) {
            optionsContainer.style.display = 'block';
            noOptionsMessage.style.display = 'none';
        } else {
            optionsContainer.style.display = 'none';
            noOptionsMessage.style.display = 'block';
        }
    });
    
    // Add option functionality
    addOptionBtn.addEventListener('click', function() {
        const optionHtml = `
            <div class="option-item mb-2" data-index="${optionIndex}">
                <div class="row">
                    <div class="col-5">
                        <input type="text" class="form-control form-control-sm" 
                               name="options[${optionIndex}][value]" 
                               placeholder="Value" required>
                    </div>
                    <div class="col-5">
                        <input type="text" class="form-control form-control-sm" 
                               name="options[${optionIndex}][label]" 
                               placeholder="Label" required>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-option">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="options[${optionIndex}][sort_order]" value="${optionIndex}">
            </div>
        `;
        optionsList.insertAdjacentHTML('beforeend', optionHtml);
        optionIndex++;
    });
    
    // Remove option functionality
    optionsList.addEventListener('click', function(e) {
        if (e.target.closest('.remove-option')) {
            e.target.closest('.option-item').remove();
        }
    });
});
</script>
@endpush
@endsection
