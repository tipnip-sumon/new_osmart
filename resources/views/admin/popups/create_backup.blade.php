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
            <p class="text-muted mb-0">Design a beautiful popup for your users</p>
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
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.popups.store') }}" method="POST" enctype="multipart/form-data" id="popupForm">
        @csrf
        
        <div class="row">
            <!-- Main Form -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle me-2"></i>
                            Basic Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Popup Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" placeholder="Enter popup title" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="type" class="form-label">Popup Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="announcement" {{ old('type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                        <option value="promotion" {{ old('type') == 'promotion' ? 'selected' : '' }}>Promotion</option>
                                        <option value="warning" {{ old('type') == 'warning' ? 'selected' : '' }}>Warning</option>
                                        <option value="info" {{ old('type') == 'info' ? 'selected' : '' }}>Information</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="4" placeholder="Enter popup content" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="trigger_type" class="form-label">Trigger Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('trigger_type') is-invalid @enderror" id="trigger_type" name="trigger_type" required>
                                        <option value="">Select trigger type</option>
                                        <option value="immediate" {{ old('trigger_type') == 'immediate' ? 'selected' : '' }}>Immediate</option>
                                        <option value="delay" {{ old('trigger_type') == 'delay' ? 'selected' : '' }}>Time Delay</option>
                                        <option value="scroll" {{ old('trigger_type') == 'scroll' ? 'selected' : '' }}>Scroll Percentage</option>
                                        <option value="exit_intent" {{ old('trigger_type') == 'exit_intent' ? 'selected' : '' }}>Exit Intent</option>
                                        <option value="page_visit" {{ old('trigger_type') == 'page_visit' ? 'selected' : '' }}>Page Visit Count</option>
                                    </select>
                                    @error('trigger_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="trigger_value" class="form-label">Trigger Value</label>
                                    <input type="number" class="form-control @error('trigger_value') is-invalid @enderror" 
                                           id="trigger_value" name="trigger_value" value="{{ old('trigger_value') }}" 
                                           placeholder="e.g., 5000 for delay (ms), 50 for scroll (%)">
                                    @error('trigger_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">For delay: milliseconds, for scroll: percentage (0-100)</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3" id="imageUpload">
                                    <label for="image" class="form-label">Popup Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Max size: 2MB. Supported formats: JPG, PNG, GIF</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Button Configuration -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-mouse-pointer me-2"></i>
                            Button Configuration
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="button_text" class="form-label">Button Text <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('button_text') is-invalid @enderror" 
                                           id="button_text" name="button_text" value="{{ old('button_text', 'Close') }}" required>
                                    @error('button_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="button_url" class="form-label">Button URL (Optional)</label>
                                    <input type="url" class="form-control @error('button_url') is-invalid @enderror" 
                                           id="button_url" name="button_url" value="{{ old('button_url') }}" placeholder="https://example.com">
                                    @error('button_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appearance Settings -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-palette me-2"></i>
                            Appearance Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="button_color" class="form-label">Button Color</label>
                                    <input type="color" class="form-control form-control-color" 
                                           id="button_color" name="button_color" value="{{ old('button_color', '#007bff') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="background_color" class="form-label">Background Color</label>
                                    <input type="color" class="form-control form-control-color" 
                                           id="background_color" name="background_color" value="{{ old('background_color', '#ffffff') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="text_color" class="form-label">Text Color</label>
                                    <input type="color" class="form-control form-control-color" 
                                           id="text_color" name="text_color" value="{{ old('text_color', '#333333') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="overlay_color" class="form-label">Overlay Color</label>
                                    <input type="text" class="form-control" 
                                           id="overlay_color" name="overlay_color" value="{{ old('overlay_color', 'rgba(0,0,0,0.5)') }}" 
                                           placeholder="rgba(0,0,0,0.5)">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="modal_size" class="form-label">Modal Size <span class="text-danger">*</span></label>
                                    <select class="form-control @error('modal_size') is-invalid @enderror" id="modal_size" name="modal_size" required>
                                        <option value="">Select size</option>
                                        <option value="small" {{ old('modal_size') == 'small' ? 'selected' : '' }}>Small</option>
                                        <option value="medium" {{ old('modal_size', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="large" {{ old('modal_size') == 'large' ? 'selected' : '' }}>Large</option>
                                        <option value="fullscreen" {{ old('modal_size') == 'fullscreen' ? 'selected' : '' }}>Fullscreen</option>
                                    </select>
                                    @error('modal_size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                                    <select class="form-control @error('position') is-invalid @enderror" id="position" name="position" required>
                                        <option value="">Select position</option>
                                        <option value="center" {{ old('position', 'center') == 'center' ? 'selected' : '' }}>Center</option>
                                        <option value="top" {{ old('position') == 'top' ? 'selected' : '' }}>Top</option>
                                        <option value="bottom" {{ old('position') == 'bottom' ? 'selected' : '' }}>Bottom</option>
                                        <option value="top_left" {{ old('position') == 'top_left' ? 'selected' : '' }}>Top Left</option>
                                        <option value="top_right" {{ old('position') == 'top_right' ? 'selected' : '' }}>Top Right</option>
                                        <option value="bottom_left" {{ old('position') == 'bottom_left' ? 'selected' : '' }}>Bottom Left</option>
                                        <option value="bottom_right" {{ old('position') == 'bottom_right' ? 'selected' : '' }}>Bottom Right</option>
                                    </select>
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="animation" class="form-label">Animation <span class="text-danger">*</span></label>
                                    <select class="form-control @error('animation') is-invalid @enderror" id="animation" name="animation" required>
                                        <option value="">Select animation</option>
                                        <option value="fade" {{ old('animation', 'fade') == 'fade' ? 'selected' : '' }}>Fade</option>
                                        <option value="slide_up" {{ old('animation') == 'slide_up' ? 'selected' : '' }}>Slide Up</option>
                                        <option value="slide_down" {{ old('animation') == 'slide_down' ? 'selected' : '' }}>Slide Down</option>
                                        <option value="slide_left" {{ old('animation') == 'slide_left' ? 'selected' : '' }}>Slide Left</option>
                                        <option value="slide_right" {{ old('animation') == 'slide_right' ? 'selected' : '' }}>Slide Right</option>
                                        <option value="zoom" {{ old('animation') == 'zoom' ? 'selected' : '' }}>Zoom</option>
                                    </select>
                                    @error('animation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Settings -->
            <div class="col-lg-4">
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
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label for="delay" class="form-label">Delay (ms)</label>
                                    <input type="number" class="form-control" id="delay" name="delay" 
                                           value="{{ old('delay', 0) }}" min="0" step="100">
                                    <div class="form-text">Time before showing popup</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label for="auto_close" class="form-label">Auto Close (s)</label>
                                    <input type="number" class="form-control" id="auto_close" name="auto_close" 
                                           value="{{ old('auto_close') }}" min="1" placeholder="Never">
                                    <div class="form-text">Auto close after seconds</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="frequency" class="form-label">Show Frequency <span class="text-danger">*</span></label>
                            <select class="form-control @error('frequency') is-invalid @enderror" id="frequency" name="frequency" required>
                                <option value="">Select frequency</option>
                                <option value="always" {{ old('frequency', 'always') == 'always' ? 'selected' : '' }}>Every page load</option>
                                <option value="once_per_session" {{ old('frequency') == 'once_per_session' ? 'selected' : '' }}>Once per session</option>
                                <option value="once_per_day" {{ old('frequency') == 'once_per_day' ? 'selected' : '' }}>Once per day</option>
                                <option value="once_per_week" {{ old('frequency') == 'once_per_week' ? 'selected' : '' }}>Once per week</option>
                                <option value="once_per_month" {{ old('frequency') == 'once_per_month' ? 'selected' : '' }}>Once per month</option>
                            </select>
                            @error('frequency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="priority" class="form-label">Priority (1-10)</label>
                            <input type="number" class="form-control" id="priority" name="priority" 
                                   value="{{ old('priority', 1) }}" min="1" max="10">
                            <div class="form-text">Higher number = higher priority</div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="target_users" class="form-label">Target Users <span class="text-danger">*</span></label>
                            <select class="form-control @error('target_users') is-invalid @enderror" id="target_users" name="target_users[]" multiple required>
                                <option value="all" {{ in_array('all', old('target_users', ['all'])) ? 'selected' : '' }}>All Users</option>
                                <option value="guests" {{ in_array('guests', old('target_users', [])) ? 'selected' : '' }}>Guests</option>
                                <option value="registered" {{ in_array('registered', old('target_users', [])) ? 'selected' : '' }}>Registered Users</option>
                                <option value="new_visitors" {{ in_array('new_visitors', old('target_users', [])) ? 'selected' : '' }}>New Visitors</option>
                                <option value="returning_visitors" {{ in_array('returning_visitors', old('target_users', [])) ? 'selected' : '' }}>Returning Visitors</option>
                            </select>
                            @error('target_users')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Hold Ctrl to select multiple</div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="target_devices" class="form-label">Target Devices <span class="text-danger">*</span></label>
                            <select class="form-control @error('target_devices') is-invalid @enderror" id="target_devices" name="target_devices[]" multiple required>
                                <option value="desktop" {{ in_array('desktop', old('target_devices', ['desktop', 'mobile', 'tablet'])) ? 'selected' : '' }}>Desktop</option>
                                <option value="tablet" {{ in_array('tablet', old('target_devices', ['desktop', 'mobile', 'tablet'])) ? 'selected' : '' }}>Tablet</option>
                                <option value="mobile" {{ in_array('mobile', old('target_devices', ['desktop', 'mobile', 'tablet'])) ? 'selected' : '' }}>Mobile</option>
                            </select>
                            @error('target_devices')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Hold Ctrl to select multiple devices</div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="pages" class="form-label">Specific Pages (Optional)</label>
                            <input type="text" class="form-control" id="pages" name="pages" 
                                   value="{{ old('pages') }}" placeholder="home,dashboard,profile">
                            <div class="form-text">Comma-separated page names</div>
                        </div>

                        <!-- Checkboxes -->
                        <div class="form-check mb-2">
                            <input type="hidden" name="closable" value="0">
                            <input class="form-check-input" type="checkbox" id="closable" name="closable" value="1" {{ old('closable', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="closable">Show close button</label>
                        </div>

                        <div class="form-check mb-2">
                            <input type="hidden" name="backdrop_close" value="0">
                            <input class="form-check-input" type="checkbox" id="backdrop_close" name="backdrop_close" value="1" {{ old('backdrop_close', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="backdrop_close">Close on backdrop click</label>
                        </div>

                        <div class="form-check mb-2">
                            <input type="hidden" name="show_on_mobile" value="0">
                            <input class="form-check-input" type="checkbox" id="show_on_mobile" name="show_on_mobile" value="1" {{ old('show_on_mobile', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_on_mobile">Show on mobile</label>
                        </div>

                        <div class="form-check mb-2">
                            <input type="hidden" name="show_on_desktop" value="0">
                            <input class="form-check-input" type="checkbox" id="show_on_desktop" name="show_on_desktop" value="1" {{ old('show_on_desktop', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_on_desktop">Show on desktop</label>
                        </div>

                        <div class="form-check mb-3">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Activate immediately</label>
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
                        <div class="form-group mb-3">
                            <label for="start_date" class="form-label">Start Date (Optional)</label>
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date" 
                                   value="{{ old('start_date') }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="end_date" class="form-label">End Date (Optional)</label>
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date" 
                                   value="{{ old('end_date') }}">
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Create Popup
                            </button>
                            <button type="button" id="previewBtn" class="btn btn-outline-info">
                                <i class="fas fa-eye me-1"></i>
                                Preview
                            </button>
                            <a href="{{ route('admin.popups.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function() {
    console.log('jQuery loaded and ready');
    console.log('Form exists:', $('#popupForm').length > 0);
    
    // Form submission debugging
    $('#popupForm').on('submit', function(e) {
        console.log('Form is being submitted...');
        console.log('Form data:', $(this).serialize());
        
        // Log all form fields
        const formData = new FormData(this);
        console.log('Form fields:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ':', value);
        }
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Creating...').prop('disabled', true);
        
        // Re-enable after 5 seconds to prevent permanent disable
        setTimeout(function() {
            submitBtn.html(originalText).prop('disabled', false);
        }, 5000);
    });

    // Form validation debugging
    $('#popupForm input, #popupForm select, #popupForm textarea').on('change', function() {
        console.log('Field changed:', $(this).attr('name'), '=', $(this).val());
    });

    // Toggle image upload based on display type
    $('#display_type').change(function() {
        const displayType = $(this).val();
        const imageUpload = $('#imageUpload');
        
        if (displayType === 'text') {
            imageUpload.hide();
        } else {
            imageUpload.show();
        }
    }).trigger('change');

    // Live preview functionality
    $('#previewBtn').click(function() {
        // Create preview popup with current form data
        const formData = new FormData($('#popupForm')[0]);
        
        // Create a simple preview modal
        showPreviewPopup({
            title: $('#title').val() || 'Popup Title',
            content: $('#content').val() || 'Popup content goes here...',
            button_text: $('#button_text').val() || 'Close',
            button_color: $('#button_color').val(),
            background_color: $('#background_color').val(),
            text_color: $('#text_color').val(),
            size: $('#modal_size').val(),
            animation: $('#animation').val()
        });
    });

    function showPreviewPopup(data) {
        // Remove existing preview
        $('#previewPopup').remove();
        
        // Create preview popup
        const popup = $(`
            <div id="previewPopup" class="popup-overlay" style="
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
            ">
                <div class="popup-content" style="
                    background: ${data.background_color};
                    color: ${data.text_color};
                    padding: 30px;
                    border-radius: 10px;
                    max-width: ${data.size === 'small' ? '400px' : data.size === 'large' ? '800px' : '600px'};
                    position: relative;
                    animation: ${data.animation === 'fade' ? 'fadeIn' : data.animation === 'zoom' ? 'zoomIn' : 'slideInUp'} 0.3s ease;
                ">
                    <button type="button" style="
                        position: absolute;
                        top: 15px;
                        right: 15px;
                        background: none;
                        border: none;
                        font-size: 20px;
                        cursor: pointer;
                        color: ${data.text_color};
                    " onclick="$('#previewPopup').remove()">×</button>
                    
                    <h4 style="margin-bottom: 15px; color: ${data.text_color};">${data.title}</h4>
                    <div style="margin-bottom: 20px; color: ${data.text_color};">${data.content}</div>
                    
                    <button type="button" style="
                        background: ${data.button_color};
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 5px;
                        cursor: pointer;
                    " onclick="$('#previewPopup').remove()">${data.button_text}</button>
                    
                    <div style="margin-top: 15px; font-size: 12px; color: #666;">
                        Preview Mode - This is how your popup will look
                    </div>
                </div>
            </div>
        `);
        
        $('body').append(popup);
    }
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes zoomIn {
        from { transform: scale(0.3); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    @keyframes slideInUp {
        from { transform: translateY(100px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
`;
document.head.appendChild(style);
</script>
@endpush
