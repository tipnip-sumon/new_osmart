@extends('admin.layouts.app')

@section('title', 'Gallery Management')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gallery Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Gallery</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Achievement Gallery Images</h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add New Image
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if($images->count() > 0)
                                <div class="row">
                                    @foreach($images as $image)
                                        <div class="col-md-4 col-sm-6 mb-4">
                                            <div class="card h-100">
                                                <img src="{{ $image->image_url }}" 
                                                     class="card-img-top lazyload" 
                                                     data-src="{{ $image->image_url }}"
                                                     alt="{{ $image->title }}" 
                                                     style="height: 200px; object-fit: cover;"
                                                     onerror="this.src='{{ asset('assets/ecomus/images/shop/gallery/gallery-' . (($loop->index % 5) + 3) . '.jpg') }}'; this.onerror=null;">
                                                <div class="card-body">
                                                    <h6 class="card-title">{{ $image->title }}</h6>
                                                    @if($image->achiever_name)
                                                        <p class="card-text"><strong>Achiever:</strong> {{ $image->achiever_name }}</p>
                                                    @endif
                                                    @if($image->rank)
                                                        <p class="card-text"><strong>Rank:</strong> #{{ $image->rank }}</p>
                                                    @endif
                                                    <p class="card-text"><small class="text-muted">{{ $image->type }}</small></p>
                                                    @if($image->description)
                                                        <p class="card-text">{{ Str::limit($image->description, 100) }}</p>
                                                    @endif
                                                </div>
                                                <div class="card-footer d-flex justify-content-between">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.gallery.edit', $image) }}" class="btn btn-sm btn-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="{{ route('admin.gallery.show', $image) }}" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <form action="{{ route('admin.gallery.destroy', $image) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this image?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <form action="{{ route('admin.gallery.toggle-status', $image) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm {{ $image->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                            {{ $image->is_active ? 'Active' : 'Inactive' }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="d-flex justify-content-center">
                                    {{ $images->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                    <h4>No gallery images found</h4>
                                    <p class="text-muted">Start by adding your first achievement gallery image.</p>
                                    <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add New Image
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection