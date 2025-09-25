@extends('admin.layouts.app')

@section('content')
<div class="container-fluid my-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus-circle text-primary me-2"></i>
                Create New Popup
            </h1>
            <p class="text-muted mb-0">Design and configure a new popup for your website</p>
        </div>
        <a href="{{ route('admin.popups.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Back to Popups
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.popups.store') }}" method="POST" enctype="multipart/form-data" id="popupCreateForm">
        @csrf
        
        <div class="row">
            <!-- Main Form -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-info-circle me-2"></i>
                            Basic Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Popup Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="Internal name for identification" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">This name is for internal identification only</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Popup Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="announcement" {{ old('type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                        <option value="promotion" {{ old('type') == 'promotion' ? 'selected' : '' }}>Promotion</option>
                                        <option value="warning" {{ old('type') == 'warning' ? 'selected' : '' }}>Warning</option>
                                        <option value="newsletter" {{ old('type') == 'newsletter' ? 'selected' : '' }}>Newsletter</option>
                                        <option value="survey" {{ old('type') == 'survey' ? 'selected' : '' }}>Survey</option>
                                        <option value="exit_intent" {{ old('type') == 'exit_intent' ? 'selected' : '' }}>Exit Intent</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Popup Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="Enter the title users will see" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="4" 
                                      placeholder="Enter popup content (HTML allowed)">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">You can use HTML tags for formatting</small>
                        </div>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-image me-2"></i>
                            Popup Image
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="image" class="form-label">Upload Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Supported formats: JPEG, PNG, JPG, GIF, WebP. Max size: 2MB</small>
                        </div>
                        
                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <label class="form-label">Preview:</label>
                            <div class="border rounded p-2" style="max-width: 300px;">
                                <img id="previewImage" src="#" alt="Image Preview" class="img-fluid rounded">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Display Settings -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-cog me-2"></i>
                            Display Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="trigger_type" class="form-label">Trigger Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('trigger_type') is-invalid @enderror" id="trigger_type" name="trigger_type" required>
                                        <option value="">Select Trigger</option>
                                        <option value="immediate" {{ old('trigger_type') == 'immediate' ? 'selected' : '' }}>Immediate</option>
                                        <option value="delay" {{ old('trigger_type') == 'delay' ? 'selected' : '' }}>Time Delay</option>
                                        <option value="scroll" {{ old('trigger_type') == 'scroll' ? 'selected' : '' }}>Scroll</option>
                                        <option value="exit_intent" {{ old('trigger_type') == 'exit_intent' ? 'selected' : '' }}>Exit Intent</option>
                                        <option value="click" {{ old('trigger_type') == 'click' ? 'selected' : '' }}>On Click</option>
                                    </select>
                                    @error('trigger_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="trigger_value" class="form-label">Trigger Value</label>
                                    <input type="number" class="form-control @error('trigger_value') is-invalid @enderror" 
                                           id="trigger_value" name="trigger_value" value="{{ old('trigger_value') }}" 
                                           placeholder="e.g., 5 (seconds or %)">
                                    @error('trigger_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">For delay: seconds, for scroll: percentage</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="modal_size" class="form-label">Modal Size</label>
                                    <select class="form-control @error('modal_size') is-invalid @enderror" id="modal_size" name="modal_size">
                                        <option value="small" {{ old('modal_size') == 'small' ? 'selected' : '' }}>Small</option>
                                        <option value="medium" {{ old('modal_size') == 'medium' ? 'selected' : 'selected' }}>Medium</option>
                                        <option value="large" {{ old('modal_size') == 'large' ? 'selected' : '' }}>Large</option>
                                        <option value="fullscreen" {{ old('modal_size') == 'fullscreen' ? 'selected' : '' }}>Fullscreen</option>
                                    </select>
                                    @error('modal_size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="position" class="form-label">Position</label>
                                    <select class="form-control @error('position') is-invalid @enderror" id="position" name="position">
                                        <option value="center" {{ old('position') == 'center' ? 'selected' : 'selected' }}>Center</option>
                                        <option value="top" {{ old('position') == 'top' ? 'selected' : '' }}>Top</option>
                                        <option value="bottom" {{ old('position') == 'bottom' ? 'selected' : '' }}>Bottom</option>
                                        <option value="left" {{ old('position') == 'left' ? 'selected' : '' }}>Left</option>
                                        <option value="right" {{ old('position') == 'right' ? 'selected' : '' }}>Right</option>
                                    </select>
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="animation" class="form-label">Animation</label>
                                    <select class="form-control @error('animation') is-invalid @enderror" id="animation" name="animation">
                                        <option value="fade" {{ old('animation') == 'fade' ? 'selected' : 'selected' }}>Fade</option>
                                        <option value="slide" {{ old('animation') == 'slide' ? 'selected' : '' }}>Slide</option>
                                        <option value="zoom" {{ old('animation') == 'zoom' ? 'selected' : '' }}>Zoom</option>
                                        <option value="bounce" {{ old('animation') == 'bounce' ? 'selected' : '' }}>Bounce</option>
                                    </select>
                                    @error('animation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Button & Action Settings -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-mouse-pointer me-2"></i>
                            Button & Action Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="button_text" class="form-label">Button Text</label>
                                    <input type="text" class="form-control @error('button_text') is-invalid @enderror" 
                                           id="button_text" name="button_text" value="{{ old('button_text', 'Close') }}" 
                                           placeholder="e.g., Close, Learn More">
                                    @error('button_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="button_url" class="form-label">Button URL</label>
                                    <input type="url" class="form-control @error('button_url') is-invalid @enderror" 
                                           id="button_url" name="button_url" value="{{ old('button_url') }}" 
                                           placeholder="https://example.com">
                                    @error('button_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Leave empty to just close the popup</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="button_color" class="form-label">Button Color</label>
                                    <input type="color" class="form-control form-control-color @error('button_color') is-invalid @enderror" 
                                           id="button_color" name="button_color" value="{{ old('button_color', '#007bff') }}">
                                    @error('button_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="background_color" class="form-label">Background Color</label>
                                    <input type="color" class="form-control form-control-color @error('background_color') is-invalid @enderror" 
                                           id="background_color" name="background_color" value="{{ old('background_color', '#ffffff') }}">
                                    @error('background_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Targeting Settings -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-users me-2"></i>
                            Targeting Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Target Devices</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="desktop" id="device_desktop" name="target_devices[]" checked>
                                <label class="form-check-label" for="device_desktop">Desktop</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="tablet" id="device_tablet" name="target_devices[]">
                                <label class="form-check-label" for="device_tablet">Tablet</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="mobile" id="device_mobile" name="target_devices[]">
                                <label class="form-check-label" for="device_mobile">Mobile</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Target Users</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="all" id="user_all" name="target_users[]" checked>
                                <label class="form-check-label" for="user_all">All Users</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="guests" id="user_guests" name="target_users[]">
                                <label class="form-check-label" for="user_guests">Guest Users</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="registered" id="user_registered" name="target_users[]">
                                <label class="form-check-label" for="user_registered">Registered Users</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="new_visitors" id="user_new_visitors" name="target_users[]">
                                <label class="form-check-label" for="user_new_visitors">New Visitors</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="returning_visitors" id="user_returning_visitors" name="target_users[]">
                                <label class="form-check-label" for="user_returning_visitors">Returning Visitors</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="frequency" class="form-label">Display Frequency</label>
                            <select class="form-control @error('frequency') is-invalid @enderror" id="frequency" name="frequency">
                                <option value="always" {{ old('frequency') == 'always' ? 'selected' : 'selected' }}>Every Visit</option>
                                <option value="once" {{ old('frequency') == 'once' ? 'selected' : '' }}>Once Per User</option>
                                <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>Once Per Day</option>
                                <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Once Per Week</option>
                                <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>Once Per Month</option>
                            </select>
                            @error('frequency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Schedule Settings -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-calendar me-2"></i>
                            Schedule Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" value="{{ old('start_date') }}">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Leave empty to start immediately</small>
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" value="{{ old('end_date') }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Leave empty for no end date</small>
                        </div>
                    </div>
                </div>

                <!-- Status & Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-toggle-on me-2"></i>
                            Status & Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', '1') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Active</strong>
                                    <br><small class="text-muted">Enable this popup to display on the website</small>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Create Popup
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="previewPopup()">
                                <i class="fas fa-eye me-2"></i>
                                Preview
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('styles')
<style>
.form-control-color {
    width: 100%;
    height: 38px;
}

.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px 10px 0 0 !important;
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}

#imagePreview img {
    max-height: 200px;
    object-fit: cover;
}
</style>
@endpush

@push('scripts')
<script>
// Image preview functionality
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});

// Trigger value visibility based on trigger type
document.getElementById('trigger_type').addEventListener('change', function() {
    const triggerValue = document.getElementById('trigger_value');
    const triggerValueLabel = document.querySelector('label[for="trigger_value"]');
    
    if (this.value === 'delay') {
        triggerValueLabel.textContent = 'Delay (seconds)';
        triggerValue.placeholder = 'e.g., 5';
    } else if (this.value === 'scroll') {
        triggerValueLabel.textContent = 'Scroll Percentage';
        triggerValue.placeholder = 'e.g., 50';
    } else {
        triggerValueLabel.textContent = 'Trigger Value';
        triggerValue.placeholder = 'e.g., 5';
    }
});

// Form validation
document.getElementById('popupCreateForm').addEventListener('submit', function(e) {
    const requiredFields = ['name', 'title', 'type', 'trigger_type'];
    let isValid = true;
    
    requiredFields.forEach(function(fieldName) {
        const field = document.getElementById(fieldName);
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Please fill in all required fields.');
    }
});

// Preview popup functionality
function previewPopup() {
    const formData = new FormData(document.getElementById('popupCreateForm'));
    
    // Create preview window
    const previewWindow = window.open('', 'popup-preview', 'width=800,height=600');
    previewWindow.document.write(`
        <html>
        <head>
            <title>Popup Preview</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body style="padding: 20px;">
            <div class="modal show" style="display: block; position: static;">
                <div class="modal-dialog modal-${formData.get('modal_size') || 'md'}">
                    <div class="modal-content" style="background-color: ${formData.get('background_color') || '#ffffff'};">
                        <div class="modal-header">
                            <h5 class="modal-title">${formData.get('title') || 'Popup Title'}</h5>
                        </div>
                        <div class="modal-body">
                            ${formData.get('content') || 'Popup content will appear here.'}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" style="background-color: ${formData.get('button_color') || '#007bff'}; color: white;">
                                ${formData.get('button_text') || 'Close'}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-center mt-3"><small>This is a preview. Actual popup may look different based on your website's styling.</small></p>
        </body>
        </html>
    `);
}
</script>
@endpush

