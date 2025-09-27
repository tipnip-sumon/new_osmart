@extends('admin.layouts.app')

@section('title', 'View Gallery Image')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gallery Image Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.gallery.index') }}">Gallery</a></li>
                        <li class="breadcrumb-item active">View</li>
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
                            <h3 class="card-title">{{ $gallery->title }}</h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.gallery.destroy', $gallery) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this image?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="text-center mb-4">
                                <img src="{{ $gallery->image_url }}" 
                                     class="img-fluid lazyload" 
                                     data-src="{{ $gallery->image_url }}"
                                     alt="{{ $gallery->title }}"
                                     style="max-height: 500px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);"
                                     onerror="this.src='{{ asset('assets/ecomus/images/shop/gallery/gallery-7.jpg') }}'; this.onerror=null;">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td><strong>Title:</strong></td>
                                                <td>{{ $gallery->title }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Type:</strong></td>
                                                <td>
                                                    <span class="badge badge-{{ $gallery->type == 'achievement' ? 'success' : ($gallery->type == 'event' ? 'info' : 'secondary') }}">
                                                        {{ ucfirst($gallery->type) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td>
                                                    <span class="badge badge-{{ $gallery->is_active ? 'success' : 'secondary' }}">
                                                        {{ $gallery->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Sort Order:</strong></td>
                                                <td>{{ $gallery->sort_order }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tbody>
                                            @if($gallery->achiever_name)
                                            <tr>
                                                <td><strong>Achiever Name:</strong></td>
                                                <td>{{ $gallery->achiever_name }}</td>
                                            </tr>
                                            @endif
                                            @if($gallery->rank)
                                            <tr>
                                                <td><strong>Rank:</strong></td>
                                                <td>
                                                    <span class="badge badge-primary">
                                                        #{{ $gallery->rank }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td><strong>Created:</strong></td>
                                                <td>{{ $gallery->created_at->format('M d, Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Updated:</strong></td>
                                                <td>{{ $gallery->updated_at->format('M d, Y H:i') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if($gallery->description)
                            <div class="mt-4">
                                <h5>Description:</h5>
                                <div class="bg-light p-3 rounded">
                                    {{ $gallery->description }}
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="card-footer">
                            <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Gallery
                            </a>
                            <form action="{{ route('admin.gallery.toggle-status', $gallery) }}" method="POST" class="d-inline ml-2">
                                @csrf
                                <button type="submit" class="btn {{ $gallery->is_active ? 'btn-warning' : 'btn-success' }}">
                                    <i class="fas fa-{{ $gallery->is_active ? 'pause' : 'play' }}"></i>
                                    {{ $gallery->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Quick Actions</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit Image
                                </a>
                                <a href="{{ route('admin.gallery.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Add New Image
                                </a>
                                <form action="{{ route('admin.gallery.toggle-status', $gallery) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $gallery->is_active ? 'btn-warning' : 'btn-success' }} w-100">
                                        <i class="fas fa-{{ $gallery->is_active ? 'pause' : 'play' }}"></i>
                                        {{ $gallery->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <hr>
                                <form action="{{ route('admin.gallery.destroy', $gallery) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this image? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash"></i> Delete Image
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    @if($gallery->type == 'achievement')
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Achievement Details</h3>
                        </div>
                        <div class="card-body text-center">
                            @if($gallery->rank)
                                <div class="achievement-badge mb-3">
                                    <span class="badge badge-primary badge-lg p-3" style="font-size: 1.2rem;">
                                        🏆 Rank #{{ $gallery->rank }}
                                    </span>
                                </div>
                            @endif
                            @if($gallery->achiever_name)
                                <h5 class="text-primary">{{ $gallery->achiever_name }}</h5>
                                <p class="text-muted">Achievement Holder</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection