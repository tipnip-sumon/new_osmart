@extends('admin.layouts.app')

@section('content')
<div class="container-fluid my-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-eye text-primary me-2"></i>
                Popup Details: {{ $popup->name }}
            </h1>
            <p class="text-muted mb-0">
                <span class="badge bg-{{ $popup->is_active ? 'success' : 'secondary' }} me-2">
                    {{ $popup->is_active ? 'Active' : 'Inactive' }}
                </span>
                Created {{ $popup->created_at ? $popup->created_at->diffForHumans() : 'N/A' }}
            </p>
        </div>
        <div>
            <a href="{{ route('admin.popups.edit', $popup) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-1"></i>
                Edit Popup
            </a>
            <a href="{{ route('admin.popups.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to List
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

    <div class="row">
        <!-- Main Content -->
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
                                <label class="form-label text-muted">Popup Name</label>
                                <p class="h6">{{ $popup->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Type</label>
                                <p class="h6">
                                    <span class="badge bg-{{ $popup->type === 'promotion' ? 'success' : ($popup->type === 'warning' ? 'warning' : ($popup->type === 'announcement' ? 'info' : 'secondary')) }}">
                                        {{ ucfirst($popup->type ?? 'Unknown') }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Title</label>
                        <p class="h5">{{ $popup->title ?? 'No title provided' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Content</label>
                        <div class="border rounded p-3 bg-light">
                            @if($popup->content)
                                {!! $popup->content !!}
                            @else
                                <em class="text-muted">No content provided</em>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Display -->
            @if($popup->image || $popup->image_data)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-image me-2"></i>
                        Popup Image
                    </h6>
                </div>
                <div class="card-body text-center">
                    @php
                        $imageUrl = '';
                        $originalUrl = '';
                        if ($popup->image_data) {
                            // Handle JSON format
                            $imageData = is_string($popup->image_data) ? json_decode($popup->image_data, true) : $popup->image_data;
                            if ($imageData && isset($imageData['medium'])) {
                                $imageUrl = asset('storage/' . $imageData['medium']);
                            } elseif ($imageData && isset($imageData['small'])) {
                                $imageUrl = asset('storage/' . $imageData['small']);
                            } elseif ($imageData && isset($imageData['original'])) {
                                $imageUrl = asset('storage/' . $imageData['original']);
                            }
                            if ($imageData && isset($imageData['original'])) {
                                $originalUrl = asset('storage/' . $imageData['original']);
                            }
                        } elseif ($popup->image) {
                            // Handle direct file path
                            $imageUrl = asset('storage/' . $popup->image);
                            $originalUrl = $imageUrl;
                        }
                    @endphp
                    
                    @if($imageUrl)
                        <div class="position-relative d-inline-block">
                            <img src="{{ $imageUrl }}" alt="Popup image" class="img-fluid rounded shadow" style="max-height: 400px; cursor: pointer;" onclick="openImageModal('{{ $originalUrl ?: $imageUrl }}')">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-dark bg-opacity-75">
                                    <i class="fas fa-expand-alt me-1"></i>
                                    Click to enlarge
                                </span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                @if($popup->image_data)
                                    @php
                                        $imageInfo = is_string($popup->image_data) ? json_decode($popup->image_data, true) : $popup->image_data;
                                    @endphp
                                    @if($imageInfo && isset($imageInfo['original_name']))
                                        Original: {{ $imageInfo['original_name'] }}<br>
                                    @endif
                                    @if($imageInfo && isset($imageInfo['size']))
                                        Size: {{ number_format($imageInfo['size'] / 1024, 2) }} KB<br>
                                    @endif
                                    @if($imageInfo && isset($imageInfo['dimensions']))
                                        Dimensions: {{ $imageInfo['dimensions']['width'] ?? 'Unknown' }} x {{ $imageInfo['dimensions']['height'] ?? 'Unknown' }} px
                                    @endif
                                @endif
                            </small>
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-image fa-3x mb-3"></i>
                            <p>Image data exists but file not found</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

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
                                <label class="form-label text-muted">Trigger Type</label>
                                <p class="h6">
                                    <span class="badge bg-info">{{ ucfirst($popup->trigger_type ?? 'Not set') }}</span>
                                    @if($popup->trigger_value)
                                        <small class="text-muted ms-2">
                                            ({{ $popup->trigger_value }}{{ $popup->trigger_type === 'delay' ? 's' : ($popup->trigger_type === 'scroll' ? '%' : '') }})
                                        </small>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Display Frequency</label>
                                <p class="h6">
                                    <span class="badge bg-secondary">{{ ucfirst($popup->frequency ?? 'Always') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Modal Size</label>
                                <p>{{ ucfirst($popup->modal_size ?? 'Medium') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Position</label>
                                <p>{{ ucfirst($popup->position ?? 'Center') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Animation</label>
                                <p>{{ ucfirst($popup->animation ?? 'Fade') }}</p>
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
                        Button & Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Button Text</label>
                                <p>{{ $popup->button_text ?? 'Close' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Button URL</label>
                                <p>
                                    @if($popup->button_url)
                                        <a href="{{ $popup->button_url }}" target="_blank" class="text-decoration-none">
                                            {{ $popup->button_url }}
                                            <i class="fas fa-external-link-alt ms-1"></i>
                                        </a>
                                    @else
                                        <em class="text-muted">No URL (closes popup)</em>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Button Color</label>
                                <p>
                                    <span class="d-inline-block rounded me-2" style="width: 20px; height: 20px; background-color: {{ $popup->button_color ?? '#007bff' }}; border: 1px solid #ccc;"></span>
                                    {{ $popup->button_color ?? '#007bff' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Background Color</label>
                                <p>
                                    <span class="d-inline-block rounded me-2" style="width: 20px; height: 20px; background-color: {{ $popup->background_color ?? '#ffffff' }}; border: 1px solid #ccc;"></span>
                                    {{ $popup->background_color ?? '#ffffff' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Button -->
                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-primary" onclick="previewPopup()">
                            <i class="fas fa-eye me-2"></i>
                            Preview Popup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>
                        Performance Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h3 class="text-primary mb-1">{{ number_format($popup->displays ?? 0) }}</h3>
                                <small class="text-muted">Total Displays</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h3 class="text-success mb-1">{{ number_format($popup->clicks ?? 0) }}</h3>
                            <small class="text-muted">Total Clicks</small>
                        </div>
                    </div>
                    
                    @if(($popup->displays ?? 0) > 0)
                    <hr class="my-3">
                    <div class="row text-center">
                        <div class="col-12">
                            <h4 class="text-info mb-1">{{ number_format((($popup->clicks ?? 0) / $popup->displays) * 100, 2) }}%</h4>
                            <small class="text-muted">Click-through Rate</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Targeting Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users me-2"></i>
                        Targeting Settings
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Target Devices</label>
                        @php
                            $targetDevices = is_string($popup->target_devices) ? json_decode($popup->target_devices, true) : $popup->target_devices;
                            $targetDevices = $targetDevices ?? ['desktop'];
                        @endphp
                        <div>
                            @foreach($targetDevices as $device)
                                <span class="badge bg-light text-dark me-1">
                                    <i class="fas fa-{{ $device === 'mobile' ? 'mobile-alt' : ($device === 'tablet' ? 'tablet-alt' : 'desktop') }} me-1"></i>
                                    {{ ucfirst($device) }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Target Users</label>
                        @php
                            $targetUsers = is_string($popup->target_users) ? json_decode($popup->target_users, true) : $popup->target_users;
                            $targetUsers = $targetUsers ?? ['all'];
                        @endphp
                        <div>
                            @foreach($targetUsers as $user)
                                <span class="badge bg-light text-dark me-1">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $user === 'all' ? 'All Users' : ($user === 'new' ? 'New Visitors' : ($user === 'returning' ? 'Returning' : ucfirst(str_replace('_', ' ', $user)))) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar me-2"></i>
                        Schedule & Timing
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Start Date</label>
                        <p>
                            @if($popup->start_date)
                                <i class="fas fa-calendar-plus text-success me-1"></i>
                                {{ \Carbon\Carbon::parse($popup->start_date)->format('M d, Y \a\t g:i A') }}
                                <small class="text-muted d-block">{{ \Carbon\Carbon::parse($popup->start_date)->diffForHumans() }}</small>
                            @else
                                <em class="text-muted">Immediate start</em>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">End Date</label>
                        <p>
                            @if($popup->end_date)
                                <i class="fas fa-calendar-times text-warning me-1"></i>
                                {{ \Carbon\Carbon::parse($popup->end_date)->format('M d, Y \a\t g:i A') }}
                                <small class="text-muted d-block">{{ \Carbon\Carbon::parse($popup->end_date)->diffForHumans() }}</small>
                            @else
                                <em class="text-muted">No end date</em>
                            @endif
                        </p>
                    </div>

                    <!-- Status indicator -->
                    <div class="mt-3 p-2 rounded" style="background-color: {{ $popup->is_active ? '#d4edda' : '#f8d7da' }};">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-circle text-{{ $popup->is_active ? 'success' : 'danger' }} me-2"></i>
                            <small class="fw-bold">
                                {{ $popup->is_active ? 'Currently Active' : 'Currently Inactive' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tools me-2"></i>
                        Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.popups.edit', $popup) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>
                            Edit Popup
                        </a>
                        
                        @if($popup->is_active)
                            <form action="{{ route('admin.popups.update', $popup) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="is_active" value="0">
                                <button type="submit" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-pause me-2"></i>
                                    Deactivate
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.popups.update', $popup) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="is_active" value="1">
                                <button type="submit" class="btn btn-outline-success w-100">
                                    <i class="fas fa-play me-2"></i>
                                    Activate
                                </button>
                            </form>
                        @endif

                        <button type="button" class="btn btn-outline-primary" onclick="duplicatePopup()">
                            <i class="fas fa-copy me-2"></i>
                            Duplicate
                        </button>
                        
                        <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                            <i class="fas fa-trash me-2"></i>
                            Delete Popup
                        </button>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info me-2"></i>
                        System Information
                    </h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <strong>Created:</strong><br>
                        {{ $popup->created_at ? $popup->created_at->format('M d, Y \a\t g:i A') : 'N/A' }}<br>
                        
                        <strong>Last Updated:</strong><br>
                        {{ $popup->updated_at ? $popup->updated_at->format('M d, Y \a\t g:i A') : 'N/A' }}<br>
                        
                        <strong>Database ID:</strong><br>
                        #{{ $popup->id }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form id="deleteForm" action="{{ route('admin.popups.destroy', $popup) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Duplicate Form (Hidden) -->
    <form id="duplicateForm" action="{{ route('admin.popups.store') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="name" value="{{ $popup->name }} (Copy)">
        <input type="hidden" name="title" value="{{ $popup->title }}">
        <input type="hidden" name="content" value="{{ $popup->content }}">
        <input type="hidden" name="type" value="{{ $popup->type }}">
        <input type="hidden" name="trigger_type" value="{{ $popup->trigger_type }}">
        <input type="hidden" name="trigger_value" value="{{ $popup->trigger_value }}">
        <input type="hidden" name="modal_size" value="{{ $popup->modal_size }}">
        <input type="hidden" name="position" value="{{ $popup->position }}">
        <input type="hidden" name="animation" value="{{ $popup->animation }}">
        <input type="hidden" name="button_text" value="{{ $popup->button_text }}">
        <input type="hidden" name="button_url" value="{{ $popup->button_url }}">
        <input type="hidden" name="button_color" value="{{ $popup->button_color }}">
        <input type="hidden" name="background_color" value="{{ $popup->background_color }}">
        <input type="hidden" name="target_devices" value="{{ $popup->target_devices }}">
        <input type="hidden" name="target_users" value="{{ $popup->target_users }}">
        <input type="hidden" name="frequency" value="{{ $popup->frequency }}">
        <input type="hidden" name="is_active" value="0">
    </form>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Popup Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Popup Image" class="img-fluid">
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px 10px 0 0 !important;
}

.border-end {
    border-right: 1px solid #dee2e6 !important;
}

.badge {
    font-size: 0.875em;
}

.text-decoration-none:hover {
    text-decoration: underline !important;
}
</style>
@endpush

@push('scripts')
<script>
// Delete confirmation
function confirmDelete() {
    if (confirm('Are you sure you want to delete this popup? This action cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}

// Duplicate popup
function duplicatePopup() {
    if (confirm('This will create a copy of this popup (inactive by default). Continue?')) {
        document.getElementById('duplicateForm').submit();
    }
}

// Preview popup functionality
function previewPopup() {
    const popup = @json($popup);
    
    // Create preview window
    const previewWindow = window.open('', 'popup-preview', 'width=800,height=600');
    previewWindow.document.write(`
        <html>
        <head>
            <title>Popup Preview - ${popup.title || 'Popup Title'}</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body style="padding: 20px;">
            <div class="modal show" style="display: block; position: static;">
                <div class="modal-dialog modal-${popup.modal_size || 'md'}">
                    <div class="modal-content" style="background-color: ${popup.background_color || '#ffffff'};">
                        <div class="modal-header">
                            <h5 class="modal-title">${popup.title || 'Popup Title'}</h5>
                        </div>
                        <div class="modal-body">
                            ${popup.content || 'Popup content will appear here.'}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" style="background-color: ${popup.button_color || '#007bff'}; color: white;">
                                ${popup.button_text || 'Close'}
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

// Open image modal
function openImageModal(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>
@endpush
@endsection
