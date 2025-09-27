@extends('admin.layouts.app')

@section('title', 'Edit Gallery Image')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Gallery Image</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.gallery.index') }}">Gallery</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Edit Gallery Image Details</h3>
                        </div>

                        <form action="{{ route('admin.gallery.update', $gallery) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="title">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $gallery->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $gallery->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <input type="file" class="form-control-file @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    <small class="form-text text-muted">Leave empty to keep current image. Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="type">Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="achievement" {{ old('type', $gallery->type) == 'achievement' ? 'selected' : '' }}>Achievement</option>
                                        <option value="event" {{ old('type', $gallery->type) == 'event' ? 'selected' : '' }}>Event</option>
                                        <option value="product" {{ old('type', $gallery->type) == 'product' ? 'selected' : '' }}>Product</option>
                                        <option value="general" {{ old('type', $gallery->type) == 'general' ? 'selected' : '' }}>General</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="rank">Rank (for achievements)</label>
                                            <input type="number" class="form-control @error('rank') is-invalid @enderror" 
                                                   id="rank" name="rank" value="{{ old('rank', $gallery->rank) }}" min="1">
                                            @error('rank')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sort_order">Sort Order</label>
                                            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                                   id="sort_order" name="sort_order" value="{{ old('sort_order', $gallery->sort_order) }}" min="0">
                                            @error('sort_order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="achiever_name">Achiever Name</label>
                                    <input type="text" class="form-control @error('achiever_name') is-invalid @enderror" 
                                           id="achiever_name" name="achiever_name" value="{{ old('achiever_name', $gallery->achiever_name) }}">
                                    @error('achiever_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $gallery->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Active</label>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Image
                                </button>
                                <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Gallery
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Current Image</h3>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ $gallery->image_url }}" 
                                 class="img-fluid lazyload" 
                                 data-src="{{ $gallery->image_url }}"
                                 alt="{{ $gallery->title }}"
                                 style="max-height: 300px; border-radius: 8px;"
                                 onerror="this.src='{{ asset('assets/ecomus/images/shop/gallery/gallery-7.jpg') }}'; this.onerror=null;">
                            <div class="mt-3">
                                <strong>{{ $gallery->title }}</strong>
                                @if($gallery->achiever_name)
                                    <br><small class="text-muted">{{ $gallery->achiever_name }}</small>
                                @endif
                                @if($gallery->rank)
                                    <br><span class="badge badge-primary">Rank #{{ $gallery->rank }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Show/hide rank field based on type selection
    $('#type').on('change', function() {
        const selectedType = $(this).val();
        const rankField = $('#rank').closest('.form-group');
        const achieverField = $('#achiever_name').closest('.form-group');
        
        if (selectedType === 'achievement') {
            rankField.show();
            achieverField.show();
        } else {
            rankField.hide();
            achieverField.hide();
        }
    });

    // Trigger change event on page load
    $('#type').trigger('change');

    // Preview image before upload
    $('#image').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const currentImg = $('.card-body img');
                currentImg.attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush