@extends('admin.layouts.app')

@section('content')
<div class="container-fluid my-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit text-primary me-2"></i>
                Edit Popup: {{ $popup->name }}
            </h1>
            <p class="text-muted mb-0">Modify popup settings and content</p>
        </div>
        <div>
            <a href="{{ route('admin.popups.show', $popup) }}" class="btn btn-info me-2">
                <i class="fas fa-eye me-1"></i>
                View Details
            </a>
            <a href="{{ route('admin.popups.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Popups
            </a>
        </div>
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

    <form action="{{ route('admin.popups.update', $popup) }}" method="POST" enctype="multipart/form-data" id="popupEditForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Main Form -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle me-2"></i>
                            Basic Information
                        </h6>
                        <small class="text-muted">ID: #{{ $popup->id }}</small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Popup Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $popup->name) }}" 
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
                                        <option value="announcement" {{ old('type', $popup->type) == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                        <option value="promotion" {{ old('type', $popup->type) == 'promotion' ? 'selected' : '' }}>Promotion</option>
                                        <option value="warning" {{ old('type', $popup->type) == 'warning' ? 'selected' : '' }}>Warning</option>
                                        <option value="newsletter" {{ old('type', $popup->type) == 'newsletter' ? 'selected' : '' }}>Newsletter</option>
                                        <option value="survey" {{ old('type', $popup->type) == 'survey' ? 'selected' : '' }}>Survey</option>
                                        <option value="exit_intent" {{ old('type', $popup->type) == 'exit_intent' ? 'selected' : '' }}>Exit Intent</option>
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
                                   id="title" name="title" value="{{ old('title', $popup->title) }}" 
                                   placeholder="Enter the title users will see" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="4" 
                                      placeholder="Enter popup content (HTML allowed)">{{ old('content', $popup->content) }}</textarea>
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
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-image me-2"></i>
                            Popup Image
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Current Image -->
                        @if($popup->image || $popup->image_data)
                            <div class="mb-3">
                                <label class="form-label">Current Image:</label>
                                <div class="border rounded p-2" style="max-width: 300px;">
                                    @php
                                        $imageUrl = '';
                                        if ($popup->image_data) {
                                            // Handle JSON format
                                            $imageData = is_string($popup->image_data) ? json_decode($popup->image_data, true) : $popup->image_data;
                                            if ($imageData && isset($imageData['small'])) {
                                                $imageUrl = asset('storage/' . $imageData['small']);
                                            } elseif ($imageData && isset($imageData['original'])) {
                                                $imageUrl = asset('storage/' . $imageData['original']);
                                            }
                                        } elseif ($popup->image) {
                                            // Handle direct file path
                                            $imageUrl = asset('storage/' . $popup->image);
                                        }
                                    @endphp
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="Current popup image" class="img-fluid rounded" id="currentImage">
                                    @else
                                        <div class="text-center text-muted py-3">
                                            <i class="fas fa-image fa-3x mb-2"></i>
                                            <p>No image available</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCurrentImage()">
                                        <i class="fas fa-trash me-1"></i>
                                        Remove Current Image
                                    </button>
                                </div>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">
                                @if($popup->image || $popup->image_data)
                                    Replace Image
                                @else
                                    Upload Image
                                @endif
                            </label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Supported formats: JPEG, PNG, JPG, GIF, WebP. Max size: 2MB</small>
                        </div>
                        
                        <!-- New Image Preview -->
                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <label class="form-label">New Image Preview:</label>
                            <div class="border rounded p-2" style="max-width: 300px;">
                                <img id="previewImage" src="#" alt="New Image Preview" class="img-fluid rounded">
                            </div>
                        </div>

                        <!-- Hidden field for removing current image -->
                        <input type="hidden" id="remove_image" name="remove_image" value="0">
                    </div>
                </div>

                <!-- Display Settings -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
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
                                        <option value="immediate" {{ old('trigger_type', $popup->trigger_type) == 'immediate' ? 'selected' : '' }}>Immediate</option>
                                        <option value="delay" {{ old('trigger_type', $popup->trigger_type) == 'delay' ? 'selected' : '' }}>Time Delay</option>
                                        <option value="scroll" {{ old('trigger_type', $popup->trigger_type) == 'scroll' ? 'selected' : '' }}>Scroll</option>
                                        <option value="exit_intent" {{ old('trigger_type', $popup->trigger_type) == 'exit_intent' ? 'selected' : '' }}>Exit Intent</option>
                                        <option value="click" {{ old('trigger_type', $popup->trigger_type) == 'click' ? 'selected' : '' }}>On Click</option>
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
                                           id="trigger_value" name="trigger_value" value="{{ old('trigger_value', $popup->trigger_value) }}" 
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
                                        <option value="small" {{ old('modal_size', $popup->modal_size) == 'small' ? 'selected' : '' }}>Small</option>
                                        <option value="medium" {{ old('modal_size', $popup->modal_size) == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="large" {{ old('modal_size', $popup->modal_size) == 'large' ? 'selected' : '' }}>Large</option>
                                        <option value="fullscreen" {{ old('modal_size', $popup->modal_size) == 'fullscreen' ? 'selected' : '' }}>Fullscreen</option>
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
                                        <option value="center" {{ old('position', $popup->position) == 'center' ? 'selected' : '' }}>Center</option>
                                        <option value="top" {{ old('position', $popup->position) == 'top' ? 'selected' : '' }}>Top</option>
                                        <option value="bottom" {{ old('position', $popup->position) == 'bottom' ? 'selected' : '' }}>Bottom</option>
                                        <option value="left" {{ old('position', $popup->position) == 'left' ? 'selected' : '' }}>Left</option>
                                        <option value="right" {{ old('position', $popup->position) == 'right' ? 'selected' : '' }}>Right</option>
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
                                        <option value="fade" {{ old('animation', $popup->animation) == 'fade' ? 'selected' : '' }}>Fade</option>
                                        <option value="slide" {{ old('animation', $popup->animation) == 'slide' ? 'selected' : '' }}>Slide</option>
                                        <option value="zoom" {{ old('animation', $popup->animation) == 'zoom' ? 'selected' : '' }}>Zoom</option>
                                        <option value="bounce" {{ old('animation', $popup->animation) == 'bounce' ? 'selected' : '' }}>Bounce</option>
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
                        <h6 class="m-0 font-weight-bold text-primary">
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
                                           id="button_text" name="button_text" value="{{ old('button_text', $popup->button_text) }}" 
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
                                           id="button_url" name="button_url" value="{{ old('button_url', $popup->button_url) }}" 
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
                                           id="button_color" name="button_color" value="{{ old('button_color', $popup->button_color ?? '#007bff') }}">
                                    @error('button_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="background_color" class="form-label">Background Color</label>
                                    <input type="color" class="form-control form-control-color @error('background_color') is-invalid @enderror" 
                                           id="background_color" name="background_color" value="{{ old('background_color', $popup->background_color ?? '#ffffff') }}">
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
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-users me-2"></i>
                            Targeting Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Target Devices</label>
                            @php
                                $targetDevices = is_string($popup->target_devices) ? json_decode($popup->target_devices, true) : $popup->target_devices;
                                $targetDevices = $targetDevices ?? [];
                            @endphp
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="desktop" id="device_desktop" name="target_devices[]" 
                                       {{ in_array('desktop', $targetDevices) ? 'checked' : '' }}>
                                <label class="form-check-label" for="device_desktop">Desktop</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="tablet" id="device_tablet" name="target_devices[]"
                                       {{ in_array('tablet', $targetDevices) ? 'checked' : '' }}>
                                <label class="form-check-label" for="device_tablet">Tablet</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="mobile" id="device_mobile" name="target_devices[]"
                                       {{ in_array('mobile', $targetDevices) ? 'checked' : '' }}>
                                <label class="form-check-label" for="device_mobile">Mobile</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Target Users</label>
                            @php
                                $targetUsers = is_string($popup->target_users) ? json_decode($popup->target_users, true) : $popup->target_users;
                                $targetUsers = $targetUsers ?? [];
                            @endphp
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="all" id="user_all" name="target_users[]"
                                       {{ in_array('all', $targetUsers) ? 'checked' : '' }}>
                                <label class="form-check-label" for="user_all">All Users</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="new" id="user_new" name="target_users[]"
                                       {{ in_array('new', $targetUsers) ? 'checked' : '' }}>
                                <label class="form-check-label" for="user_new">New Visitors</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="returning" id="user_returning" name="target_users[]"
                                       {{ in_array('returning', $targetUsers) ? 'checked' : '' }}>
                                <label class="form-check-label" for="user_returning">Returning Visitors</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="logged_in" id="user_logged_in" name="target_users[]"
                                       {{ in_array('logged_in', $targetUsers) ? 'checked' : '' }}>
                                <label class="form-check-label" for="user_logged_in">Logged In Users</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="frequency" class="form-label">Display Frequency</label>
                            <select class="form-control @error('frequency') is-invalid @enderror" id="frequency" name="frequency">
                                <option value="always" {{ old('frequency', $popup->frequency) == 'always' ? 'selected' : '' }}>Every Visit</option>
                                <option value="once" {{ old('frequency', $popup->frequency) == 'once' ? 'selected' : '' }}>Once Per User</option>
                                <option value="daily" {{ old('frequency', $popup->frequency) == 'daily' ? 'selected' : '' }}>Once Per Day</option>
                                <option value="weekly" {{ old('frequency', $popup->frequency) == 'weekly' ? 'selected' : '' }}>Once Per Week</option>
                                <option value="monthly" {{ old('frequency', $popup->frequency) == 'monthly' ? 'selected' : '' }}>Once Per Month</option>
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
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-calendar me-2"></i>
                            Schedule Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" 
                                   value="{{ old('start_date', $popup->start_date ? \Carbon\Carbon::parse($popup->start_date)->format('Y-m-d\TH:i') : '') }}">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Leave empty to start immediately</small>
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" 
                                   value="{{ old('end_date', $popup->end_date ? \Carbon\Carbon::parse($popup->end_date)->format('Y-m-d\TH:i') : '') }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Leave empty for no end date</small>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-bar me-2"></i>
                            Statistics
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="text-primary">{{ $popup->displays ?? 0 }}</h4>
                                    <small class="text-muted">Displays</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success">{{ $popup->clicks ?? 0 }}</h4>
                                <small class="text-muted">Clicks</small>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <small class="text-muted">
                                Created: {{ $popup->created_at ? $popup->created_at->format('M d, Y') : 'N/A' }}<br>
                                Last Updated: {{ $popup->updated_at ? $popup->updated_at->format('M d, Y H:i') : 'N/A' }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Status & Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-toggle-on me-2"></i>
                            Status & Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $popup->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Active</strong>
                                    <br><small class="text-muted">Enable this popup to display on the website</small>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Update Popup
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="previewPopup()">
                                <i class="fas fa-eye me-2"></i>
                                Preview Changes
                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                                <i class="fas fa-trash me-2"></i>
                                Delete Popup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Form (Hidden) -->
    <form id="deleteForm" action="{{ route('admin.popups.destroy', $popup) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
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

#imagePreview img, #currentImage {
    max-height: 200px;
    object-fit: cover;
}

.border-end {
    border-right: 1px solid #dee2e6 !important;
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

// Remove current image functionality
function removeCurrentImage() {
    if (confirm('Are you sure you want to remove the current image?')) {
        document.getElementById('remove_image').value = '1';
        const currentImageContainer = document.getElementById('currentImage').parentElement.parentElement;
        currentImageContainer.style.display = 'none';
    }
}

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
document.getElementById('popupEditForm').addEventListener('submit', function(e) {
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

// Delete confirmation
function confirmDelete() {
    if (confirm('Are you sure you want to delete this popup? This action cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}

// Preview popup functionality
function previewPopup() {
    const formData = new FormData(document.getElementById('popupEditForm'));
    
    // Create preview window
    const previewWindow = window.open('', 'popup-preview', 'width=800,height=600');
    previewWindow.document.write(`
        <html>
        <head>
            <title>Popup Preview - ${formData.get('title') || 'Popup Title'}</title>
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
            <p class="text-center mt-3"><small>This is a preview of your changes. Actual popup may look different based on your website's styling.</small></p>
        </body>
        </html>
    `);
}

// Initialize trigger type label on load
document.addEventListener('DOMContentLoaded', function() {
    const triggerType = document.getElementById('trigger_type');
    if (triggerType.value) {
        triggerType.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush

