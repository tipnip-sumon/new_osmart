@extends('admin.layouts.app')

@section('title', 'View Banner Collection')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Banner Collection Details</h1>
        <div>
            <a href="{{ route('admin.banner-collections.edit', $bannerCollection) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit
            </a>
            <a href="{{ route('admin.banner-collections.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm ml-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Banner Preview -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Banner Preview</h6>
                </div>
                <div class="card-body">
                    <!-- Live Preview of Banner -->
                    <div class="tf-grid-layout md-col-2 tf-img-with-text style-4" style="min-height: 300px;">
                        <div class="tf-content-left has-bg-color-2" 
                             style="background-color: {{ $bannerCollection->background_color }}; color: {{ $bannerCollection->text_color }};">
                            <div class="text-center p-4">
                                <h2 class="heading mb-3" style="color: {{ $bannerCollection->text_color }};">{{ $bannerCollection->title }}</h2>
                                <p class="text-paragraph mb-4" style="color: {{ $bannerCollection->text_color }};">{{ $bannerCollection->description }}</p>
                                
                                @if($bannerCollection->show_countdown && $bannerCollection->is_countdown_active)
                                <div class="tf-countdown-v2 justify-content-center mb-4">
                                    <div id="countdown-preview" data-timer="{{ $bannerCollection->countdown_timer }}">
                                        @php $remaining = $bannerCollection->time_remaining; @endphp
                                        <div class="countdown__item">
                                            <span class="countdown__value countdown-days">{{ $remaining['days'] ?? 0 }}</span>
                                            <span class="countdown__label">Days</span>
                                        </div>
                                        <div class="countdown__item">
                                            <span class="countdown__value countdown-hours">{{ $remaining['hours'] ?? 0 }}</span>
                                            <span class="countdown__label">Hours</span>
                                        </div>
                                        <div class="countdown__item">
                                            <span class="countdown__value countdown-minutes">{{ $remaining['minutes'] ?? 0 }}</span>
                                            <span class="countdown__label">Mins</span>
                                        </div>
                                        <div class="countdown__item">
                                            <span class="countdown__value countdown-seconds">{{ $remaining['seconds'] ?? 0 }}</span>
                                            <span class="countdown__label">Secs</span>
                                        </div>
                                    </div>
                                </div>
                                @elseif($bannerCollection->show_countdown && !$bannerCollection->is_countdown_active)
                                <div class="alert alert-warning mb-4">
                                    <i class="fas fa-clock"></i> Countdown has expired
                                </div>
                                @endif
                                
                                <a href="{{ $bannerCollection->button_url ?: '#' }}" 
                                   class="btn btn-dark btn-lg"
                                   @if(!$bannerCollection->button_url) onclick="return false;" @endif>
                                    {{ $bannerCollection->button_text }}
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </div>
                        <div class="tf-image-wrap">
                            <img src="{{ $bannerCollection->image_url }}" alt="{{ $bannerCollection->title }}" 
                                 class="img-fluid" style="width: 100%; height: 300px; object-fit: cover;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Banner Details</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td width="200"><strong>Title:</strong></td>
                                    <td>{{ $bannerCollection->title }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Description:</strong></td>
                                    <td>{{ $bannerCollection->description }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Button Text:</strong></td>
                                    <td>{{ $bannerCollection->button_text }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Button URL:</strong></td>
                                    <td>
                                        @if($bannerCollection->button_url)
                                            <a href="{{ $bannerCollection->button_url }}" target="_blank">
                                                {{ $bannerCollection->button_url }}
                                                <i class="fas fa-external-link-alt ml-1"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Background Color:</strong></td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $bannerCollection->background_color }}; color: {{ $bannerCollection->text_color }};">
                                            {{ $bannerCollection->background_color }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Text Color:</strong></td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $bannerCollection->text_color }}; color: {{ $bannerCollection->background_color }};">
                                            {{ $bannerCollection->text_color }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status & Settings</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <span class="badge badge-{{ $bannerCollection->is_active ? 'success' : 'secondary' }} ml-2">
                            {{ $bannerCollection->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <strong>Sort Order:</strong>
                        <span class="ml-2">{{ $bannerCollection->sort_order }}</span>
                    </div>

                    <div class="mb-3">
                        <strong>Countdown Timer:</strong>
                        @if($bannerCollection->show_countdown)
                            @if($bannerCollection->is_countdown_active)
                            <span class="badge badge-success ml-2">Active</span>
                            <br><small class="text-muted">Ends: {{ $bannerCollection->countdown_end_date->format('M j, Y \a\t H:i') }}</small>
                            @else
                            <span class="badge badge-warning ml-2">Expired</span>
                            @endif
                        @else
                        <span class="badge badge-secondary ml-2">Disabled</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong>Created:</strong>
                        <br><small class="text-muted">{{ $bannerCollection->created_at->format('M j, Y \a\t H:i') }}</small>
                    </div>

                    <div class="mb-3">
                        <strong>Last Updated:</strong>
                        <br><small class="text-muted">{{ $bannerCollection->updated_at->format('M j, Y \a\t H:i') }}</small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4">
                        <a href="{{ route('admin.banner-collections.edit', $bannerCollection) }}" 
                           class="btn btn-primary btn-block">
                            <i class="fas fa-edit"></i> Edit Banner
                        </a>
                        
                        <form method="POST" action="{{ route('admin.banner-collections.toggle-status', $bannerCollection) }}" 
                              class="mt-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $bannerCollection->is_active ? 'warning' : 'success' }} btn-block">
                                <i class="fas fa-toggle-{{ $bannerCollection->is_active ? 'off' : 'on' }}"></i>
                                {{ $bannerCollection->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        
                        <button type="button" class="btn btn-danger btn-block mt-2" 
                                onclick="confirmDelete()">
                            <i class="fas fa-trash"></i> Delete Banner
                        </button>
                    </div>
                </div>
            </div>

            <!-- Image Details -->
            @if($bannerCollection->image)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Image</h6>
                </div>
                <div class="card-body text-center">
                    <img src="{{ $bannerCollection->image_url }}" 
                         alt="{{ $bannerCollection->title }}" 
                         class="img-fluid rounded" 
                         style="max-width: 100%; max-height: 200px; object-fit: cover;">
                    
                    <div class="mt-3">
                        <a href="{{ $bannerCollection->image_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt"></i> View Full Size
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Delete Form -->
    <form id="delete-form" action="{{ route('admin.banner-collections.destroy', $bannerCollection) }}" 
          method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this banner collection? This action cannot be undone.')) {
        document.getElementById('delete-form').submit();
    }
}

// Countdown preview update
@if($bannerCollection->show_countdown && $bannerCollection->is_countdown_active)
function updateCountdown() {
    const timer = {{ $bannerCollection->countdown_timer }};
    const endTime = Date.now() + timer;
    
    function update() {
        const now = Date.now();
        const distance = endTime - now;
        
        if (distance < 0) {
            document.getElementById('countdown-preview').innerHTML = '<span class="text-danger">EXPIRED</span>';
            return;
        }
        
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        document.querySelector('.countdown-days').textContent = days;
        document.querySelector('.countdown-hours').textContent = hours;
        document.querySelector('.countdown-minutes').textContent = minutes;
        document.querySelector('.countdown-seconds').textContent = seconds;
    }
    
    // Update immediately and then every second
    update();
    setInterval(update, 1000);
}

$(document).ready(function() {
    updateCountdown();
});
@endif
</script>
@endpush