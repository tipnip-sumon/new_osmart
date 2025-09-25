@extends('admin.layouts.app')

@section('content')
<div class="container-fluid my-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-dark fw-bold">
                <i class="fas fa-eye text-primary me-2"></i>
                Popup Details: <span class="text-primary">{{ $popup->name }}</span>
            </h1>
            <p class="text-secondary mb-0 mt-2">
                <span class="badge bg-{{ $popup->is_active ? 'success' : 'secondary' }} me-2 px-3 py-2">
                    <i class="fas fa-{{ $popup->is_active ? 'check-circle' : 'times-circle' }} me-2"></i>
                    {{ $popup->is_active ? 'Active' : 'Inactive' }}
                </span>
                <span class="text-muted">
                    <i class="fas fa-calendar-alt me-1"></i>
                    Created {{ $popup->created_at ? $popup->created_at->diffForHumans() : 'N/A' }}
                </span>
            </p>
        </div>
        <div>
            <a href="{{ route('admin.popups.edit', $popup) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>
                Edit Popup
            </a>
            <a href="{{ route('admin.popups.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
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
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>
                        Basic Information
                    </h6>
                    <small class="text-white-50">ID: #{{ $popup->id }}</small>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-semibold">Popup Name</label>
                                <p class="h6 text-dark">{{ $popup->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-semibold">Type</label>
                                <p class="h6">
                                    <span class="badge bg-{{ $popup->type === 'promotion' ? 'success' : ($popup->type === 'warning' ? 'warning' : ($popup->type === 'announcement' ? 'info' : 'secondary')) }} px-3 py-2">
                                        <i class="fas fa-{{ $popup->type === 'promotion' ? 'bullhorn' : ($popup->type === 'warning' ? 'exclamation-triangle' : ($popup->type === 'announcement' ? 'bell' : 'tag')) }} me-2"></i>
                                        {{ ucfirst($popup->type ?? 'Unknown') }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Title</label>
                        <p class="h5 text-dark">{{ $popup->title ?? 'No title provided' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Content</label>
                        <div class="border rounded-3 p-3 bg-light">
                            @if($popup->content)
                                <div class="text-dark">{!! $popup->content !!}</div>
                            @else
                                <em class="text-muted">No content provided</em>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Display -->
            @if($popup->image || $popup->image_data)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white py-3">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-image me-2"></i>
                        Popup Image Gallery
                    </h6>
                </div>
                <div class="card-body p-4">
                    @php
                        $imageData = null;
                        $allSizes = [];
                        
                        if ($popup->image_data) {
                            $imageData = is_string($popup->image_data) ? json_decode($popup->image_data, true) : $popup->image_data;
                            if ($imageData && isset($imageData['sizes'])) {
                                // New format with sizes array
                                $allSizes = $imageData['sizes'];
                            } elseif ($imageData && isset($imageData['medium'])) {
                                // Old format (backward compatibility)
                                $allSizes = [
                                    'small' => ['url' => asset('storage/' . ($imageData['small'] ?? $imageData['original']))],
                                    'medium' => ['url' => asset('storage/' . $imageData['medium'])],
                                    'original' => ['url' => asset('storage/' . $imageData['original'])]
                                ];
                            }
                        } elseif ($popup->image) {
                            // Handle direct file path (legacy)
                            $allSizes = [
                                'original' => ['url' => asset('storage/' . $popup->image)]
                            ];
                        }
                        
                        $primaryImage = $allSizes['medium']['url'] ?? $allSizes['small']['url'] ?? $allSizes['original']['url'] ?? '';
                        $originalImage = $allSizes['original']['url'] ?? $primaryImage;
                    @endphp
                    
                    @if($primaryImage)
                        <!-- Main Image Display -->
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <img src="{{ $primaryImage }}" alt="Popup image" 
                                     class="img-fluid rounded-3 shadow-lg border border-light" 
                                     style="max-height: 450px; cursor: pointer; transition: all 0.3s ease;" 
                                     onclick="openImageModal('{{ $originalImage }}')"
                                     onmouseover="this.style.transform='scale(1.02)'" 
                                     onmouseout="this.style.transform='scale(1)'">
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge bg-dark bg-opacity-75 px-3 py-2 rounded-pill">
                                        <i class="fas fa-expand-alt me-2"></i>
                                        Click to enlarge
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Multiple Size Options -->
                        @if(count($allSizes) > 1)
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="text-secondary fw-bold mb-3">
                                    <i class="fas fa-layer-group me-2"></i>
                                    Available Image Sizes
                                </h6>
                            </div>
                            @foreach($allSizes as $sizeName => $sizeData)
                                <div class="col-md-4">
                                    <div class="card bg-light border-0 h-100">
                                        <div class="card-body text-center p-3">
                                            <div class="mb-3">
                                                <img src="{{ $sizeData['url'] }}" alt="{{ ucfirst($sizeName) }} image" 
                                                     class="img-fluid rounded shadow-sm" 
                                                     style="max-height: 120px; cursor: pointer;" 
                                                     onclick="openImageModal('{{ $sizeData['url'] }}')">
                                            </div>
                                            <h6 class="card-title text-primary mb-2">
                                                <i class="fas fa-{{ $sizeName === 'original' ? 'expand' : ($sizeName === 'medium' ? 'compress-alt' : 'compress') }} me-2"></i>
                                                {{ ucfirst($sizeName) }}
                                            </h6>
                                            @if(isset($sizeData['width']) && isset($sizeData['height']))
                                                <p class="card-text small text-muted mb-2">
                                                    {{ $sizeData['width'] }} × {{ $sizeData['height'] }} px
                                                </p>
                                            @endif
                                            @if(isset($sizeData['file_size']))
                                                <p class="card-text small text-muted mb-3">
                                                    {{ number_format($sizeData['file_size'] / 1024, 1) }} KB
                                                </p>
                                            @endif
                                            <a href="{{ $sizeData['url'] }}" target="_blank" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-external-link-alt me-1"></i>
                                                View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- Image Information -->
                        <div class="bg-light rounded-3 p-3">
                            <h6 class="text-secondary fw-bold mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Image Information
                            </h6>
                            <div class="row g-3">
                                @if($imageData && isset($imageData['original_name']))
                                    <div class="col-md-6">
                                        <strong class="text-dark">Original Name:</strong>
                                        <span class="text-muted">{{ $imageData['original_name'] }}</span>
                                    </div>
                                @endif
                                @if($imageData && isset($imageData['file_size']))
                                    <div class="col-md-6">
                                        <strong class="text-dark">File Size:</strong>
                                        <span class="text-muted">{{ number_format($imageData['file_size'] / 1024, 2) }} KB</span>
                                    </div>
                                @endif
                                @if($imageData && isset($imageData['mime_type']))
                                    <div class="col-md-6">
                                        <strong class="text-dark">Type:</strong>
                                        <span class="text-muted">{{ strtoupper(str_replace('image/', '', $imageData['mime_type'])) }}</span>
                                    </div>
                                @endif
                                @if($imageData && isset($imageData['sizes']['original']))
                                    <div class="col-md-6">
                                        <strong class="text-dark">Dimensions:</strong>
                                        <span class="text-muted">{{ $imageData['sizes']['original']['width'] ?? 'Unknown' }} × {{ $imageData['sizes']['original']['height'] ?? 'Unknown' }} px</span>
                                    </div>
                                @elseif($imageData && isset($imageData['dimensions']))
                                    <div class="col-md-6">
                                        <strong class="text-dark">Dimensions:</strong>
                                        <span class="text-muted">{{ $imageData['dimensions']['width'] ?? 'Unknown' }} × {{ $imageData['dimensions']['height'] ?? 'Unknown' }} px</span>
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <strong class="text-dark">Upload Date:</strong>
                                    <span class="text-muted">{{ $popup->updated_at ? $popup->updated_at->format('M j, Y g:i A') : 'Unknown' }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-image fa-4x mb-3 text-secondary"></i>
                            <h5 class="text-secondary">Image Not Available</h5>
                            <p class="mb-0">Image data exists but file not found</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Display Settings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white py-3">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-cog me-2"></i>
                        Display Settings
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-semibold">Trigger Type</label>
                                <p class="h6">
                                    <span class="badge bg-info px-3 py-2">
                                        <i class="fas fa-{{ $popup->trigger_type === 'delay' ? 'clock' : ($popup->trigger_type === 'scroll' ? 'mouse' : 'hand-pointer') }} me-2"></i>
                                        {{ ucfirst($popup->trigger_type ?? 'Not set') }}
                                    </span>
                                    @if($popup->trigger_value)
                                        <small class="text-muted ms-2 fw-medium">
                                            ({{ $popup->trigger_value }}{{ $popup->trigger_type === 'delay' ? 's' : ($popup->trigger_type === 'scroll' ? '%' : '') }})
                                        </small>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-semibold">Display Frequency</label>
                                <p class="h6">
                                    <span class="badge bg-secondary px-3 py-2">
                                        <i class="fas fa-repeat me-2"></i>
                                        {{ ucfirst($popup->frequency ?? 'Always') }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-semibold">Modal Size</label>
                                <p class="text-dark fw-medium">
                                    <i class="fas fa-expand-arrows-alt me-2 text-primary"></i>
                                    {{ ucfirst($popup->modal_size ?? 'Medium') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-semibold">Position</label>
                                <p class="text-dark fw-medium">
                                    <i class="fas fa-arrows-alt me-2 text-primary"></i>
                                    {{ ucfirst($popup->position ?? 'Center') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-semibold">Animation</label>
                                <p class="text-dark fw-medium">
                                    <i class="fas fa-magic me-2 text-primary"></i>
                                    {{ ucfirst($popup->animation ?? 'Fade') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Button & Action Settings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-mouse-pointer me-2"></i>
                        Button & Actions
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-semibold">Button Text</label>
                                <p class="text-dark fw-medium">
                                    <i class="fas fa-tag me-2 text-primary"></i>
                                    {{ $popup->button_text ?? 'Close' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-semibold">Button URL</label>
                                <p>
                                    @if($popup->button_url)
                                        <a href="{{ $popup->button_url }}" target="_blank" 
                                           class="text-decoration-none text-primary fw-medium">
                                            <i class="fas fa-external-link-alt me-2"></i>
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
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white py-3">
                    <h6 class="m-0 fw-bold">
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
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning text-dark py-3">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-users me-2"></i>
                        Targeting Settings
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Target Devices</label>
                        @php
                            $targetDevices = is_string($popup->target_devices) ? json_decode($popup->target_devices, true) : $popup->target_devices;
                            $targetDevices = $targetDevices ?? ['desktop'];
                        @endphp
                        <div>
                            @foreach($targetDevices as $device)
                                <span class="badge bg-primary text-white me-1 px-3 py-2">
                                    <i class="fas fa-{{ $device === 'mobile' ? 'mobile-alt' : ($device === 'tablet' ? 'tablet-alt' : 'desktop') }} me-1"></i>
                                    {{ ucfirst($device) }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Target Users</label>
                        @php
                            $targetUsers = is_string($popup->target_users) ? json_decode($popup->target_users, true) : $popup->target_users;
                            $targetUsers = $targetUsers ?? ['all'];
                        @endphp
                        <div>
                            @foreach($targetUsers as $user)
                                <span class="badge bg-secondary text-white me-1 px-3 py-2">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $user === 'all' ? 'All Users' : ($user === 'new' ? 'New Visitors' : ($user === 'returning' ? 'Returning' : ucfirst(str_replace('_', ' ', $user)))) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-secondary text-white py-3">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-calendar me-2"></i>
                        Schedule & Timing
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Start Date</label>
                        <p>
                            @if($popup->start_date)
                                <i class="fas fa-calendar-plus text-success me-1"></i>
                                <span class="text-dark fw-medium">{{ \Carbon\Carbon::parse($popup->start_date)->format('M d, Y \a\t g:i A') }}</span>
                                <small class="text-muted d-block">{{ \Carbon\Carbon::parse($popup->start_date)->diffForHumans() }}</small>
                            @else
                                <em class="text-muted">Immediate start</em>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">End Date</label>
                        <p>
                            @if($popup->end_date)
                                <i class="fas fa-calendar-times text-danger me-1"></i>
                                <span class="text-dark fw-medium">{{ \Carbon\Carbon::parse($popup->end_date)->format('M d, Y \a\t g:i A') }}</span>
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
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white py-3">
                    <h6 class="m-0 fw-bold">
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
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="toggleStatus(false)">
                                <i class="fas fa-pause me-2"></i>
                                Deactivate
                            </button>
                        @else
                            <button type="button" class="btn btn-outline-success w-100" onclick="toggleStatus(true)">
                                <i class="fas fa-play me-2"></i>
                                Activate
                            </button>
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
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-info me-2"></i>
                        System Information
                    </h6>
                </div>
                <div class="card-body p-4">
                    <small class="text-dark">
                        <div class="mb-3">
                            <strong class="text-secondary">Created:</strong><br>
                            <span class="text-dark">{{ $popup->created_at ? $popup->created_at->format('M d, Y \a\t g:i A') : 'N/A' }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong class="text-secondary">Last Updated:</strong><br>
                            <span class="text-dark">{{ $popup->updated_at ? $popup->updated_at->format('M d, Y \a\t g:i A') : 'N/A' }}</span>
                        </div>
                        
                        <div class="mb-0">
                            <strong class="text-secondary">Database ID:</strong><br>
                            <span class="text-primary fw-bold">#{{ $popup->id }}</span>
                        </div>
                    </small>
                </div>
            </div>
        </div>
    </div>
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
@endsection
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
// CSRF Token for AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Show loading state
function showLoading(button) {
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    button.disabled = true;
    return originalText;
}

// Hide loading state
function hideLoading(button, originalText) {
    button.innerHTML = originalText;
    button.disabled = false;
}

// Show toast notification
function showToast(message, type = 'success') {
    // Remove existing toasts
    document.querySelectorAll('.toast-container').forEach(container => container.remove());
    
    const toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
    toastContainer.style.zIndex = '9999';
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${type === 'success' ? 'check-circle' : (type === 'danger' ? 'exclamation-circle' : 'info-circle')} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    document.body.appendChild(toastContainer);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toastContainer.parentNode) {
            toastContainer.remove();
        }
    }, 5000);
}

// Update status display
function updateStatusDisplay(isActive) {
    // Update header badge
    const headerBadge = document.querySelector('.badge.bg-success, .badge.bg-secondary');
    if (headerBadge) {
        headerBadge.className = `badge bg-${isActive ? 'success' : 'secondary'} me-2 px-3 py-2`;
        headerBadge.innerHTML = `
            <i class="fas fa-${isActive ? 'check-circle' : 'times-circle'} me-2"></i>
            ${isActive ? 'Active' : 'Inactive'}
        `;
    }
    
    // Update sidebar status
    const sidebarStatus = document.querySelector('.card-body .d-flex .fas.fa-circle');
    if (sidebarStatus) {
        sidebarStatus.className = `fas fa-circle text-${isActive ? 'success' : 'danger'} me-2`;
        sidebarStatus.nextElementSibling.textContent = isActive ? 'Currently Active' : 'Currently Inactive';
        sidebarStatus.parentElement.parentElement.style.backgroundColor = isActive ? '#d4edda' : '#f8d7da';
    }
}

// Toggle status (activate/deactivate)
function toggleStatus(newStatus) {
    const button = event.target.closest('button');
    const originalText = showLoading(button);
    
    // Prepare form data
    const formData = new FormData();
    formData.append('_token', csrfToken);
    formData.append('_method', 'PUT');
    formData.append('name', '{{ $popup->name }}');
    formData.append('title', '{{ $popup->title }}');
    formData.append('content', '{{ $popup->content }}');
    formData.append('type', '{{ $popup->type }}');
    formData.append('trigger_type', '{{ $popup->trigger_type }}');
    formData.append('trigger_value', '{{ $popup->trigger_value }}');
    formData.append('modal_size', '{{ $popup->modal_size }}');
    formData.append('position', '{{ $popup->position }}');
    formData.append('animation', '{{ $popup->animation }}');
    formData.append('button_text', '{{ $popup->button_text }}');
    formData.append('button_url', '{{ $popup->button_url }}');
    formData.append('button_color', '{{ $popup->button_color }}');
    formData.append('background_color', '{{ $popup->background_color }}');
    formData.append('frequency', '{{ $popup->frequency }}');
    formData.append('is_active', newStatus ? '1' : '0');
    
    // Add target devices and users
    @php
        $targetDevices = is_string($popup->target_devices) ? json_decode($popup->target_devices, true) : $popup->target_devices;
        $targetUsers = is_string($popup->target_users) ? json_decode($popup->target_users, true) : $popup->target_users;
        $targetDevices = $targetDevices ?? ['desktop'];
        $targetUsers = $targetUsers ?? ['all'];
    @endphp
    
    @foreach($targetDevices as $device)
        formData.append('target_devices[]', '{{ $device }}');
    @endforeach
    
    @foreach($targetUsers as $user)
        formData.append('target_users[]', '{{ $user }}');
    @endforeach
    
    fetch('{{ route('admin.popups.update', $popup) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading(button, originalText);
        
        if (data.success) {
            showToast(data.message || `Popup ${newStatus ? 'activated' : 'deactivated'} successfully!`, 'success');
            updateStatusDisplay(newStatus);
            
            // Update button states
            updateActionButtons(newStatus);
        } else {
            showToast(data.message || 'Operation failed. Please try again.', 'danger');
        }
    })
    .catch(error => {
        hideLoading(button, originalText);
        console.error('Error:', error);
        showToast('An error occurred. Please try again.', 'danger');
    });
}

// Update action buttons based on status
function updateActionButtons(isActive) {
    const actionButtonsContainer = document.querySelector('.card-body .d-grid');
    const currentStatusButton = actionButtonsContainer.querySelector('.btn-outline-secondary, .btn-outline-success');
    
    if (currentStatusButton) {
        if (isActive) {
            // Show deactivate button
            currentStatusButton.className = 'btn btn-outline-secondary w-100';
            currentStatusButton.innerHTML = '<i class="fas fa-pause me-2"></i>Deactivate';
            currentStatusButton.onclick = () => toggleStatus(false);
        } else {
            // Show activate button
            currentStatusButton.className = 'btn btn-outline-success w-100';
            currentStatusButton.innerHTML = '<i class="fas fa-play me-2"></i>Activate';
            currentStatusButton.onclick = () => toggleStatus(true);
        }
    }
}

// Delete confirmation with AJAX
function confirmDelete() {
    if (confirm('Are you sure you want to delete this popup? This action cannot be undone.')) {
        const button = event.target.closest('button');
        const originalText = showLoading(button);
        
        fetch('{{ route('admin.popups.destroy', $popup) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                _method: 'DELETE'
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading(button, originalText);
            
            if (data.success) {
                showToast(data.message || 'Popup deleted successfully!', 'success');
                
                // Redirect to index page after a short delay
                setTimeout(() => {
                    window.location.href = '{{ route('admin.popups.index') }}';
                }, 1500);
            } else {
                showToast(data.message || 'Failed to delete popup. Please try again.', 'danger');
            }
        })
        .catch(error => {
            hideLoading(button, originalText);
            console.error('Error:', error);
            showToast('An error occurred while deleting. Please try again.', 'danger');
        });
    }
}

// Duplicate popup with AJAX
function duplicatePopup() {
    if (confirm('This will create a copy of this popup (inactive by default). Continue?')) {
        const button = event.target.closest('button');
        const originalText = showLoading(button);
        
        // Prepare form data
        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('name', '{{ $popup->name }} (Copy)');
        formData.append('title', '{{ $popup->title }}');
        formData.append('content', '{{ $popup->content }}');
        formData.append('type', '{{ $popup->type }}');
        formData.append('trigger_type', '{{ $popup->trigger_type }}');
        formData.append('trigger_value', '{{ $popup->trigger_value }}');
        formData.append('modal_size', '{{ $popup->modal_size }}');
        formData.append('position', '{{ $popup->position }}');
        formData.append('animation', '{{ $popup->animation }}');
        formData.append('button_text', '{{ $popup->button_text }}');
        formData.append('button_url', '{{ $popup->button_url }}');
        formData.append('button_color', '{{ $popup->button_color }}');
        formData.append('background_color', '{{ $popup->background_color }}');
        formData.append('frequency', '{{ $popup->frequency }}');
        formData.append('is_active', '0');
        
        // Add target devices and users
        @foreach($targetDevices as $device)
            formData.append('target_devices[]', '{{ $device }}');
        @endforeach
        
        @foreach($targetUsers as $user)
            formData.append('target_users[]', '{{ $user }}');
        @endforeach
        
        fetch('{{ route('admin.popups.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading(button, originalText);
            
            if (data.success) {
                showToast(data.message || 'Popup duplicated successfully!', 'success');
                
                // Optionally redirect to the new popup or refresh
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                }
            } else {
                showToast(data.message || 'Failed to duplicate popup. Please try again.', 'danger');
            }
        })
        .catch(error => {
            hideLoading(button, originalText);
            console.error('Error:', error);
            showToast('An error occurred while duplicating. Please try again.', 'danger');
        });
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

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Add CSRF meta tag if not present
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = '{{ csrf_token() }}';
        document.head.appendChild(meta);
    }
});
</script>
@endpush

