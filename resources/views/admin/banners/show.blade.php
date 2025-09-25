@extends('admin.layouts.app')

@section('title', 'View Banner')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Banner Details</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.banners.index') }}">Banners</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <!-- Banner Information -->
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Banner Information</div>
                        <div class="ms-auto">
                            <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-sm btn-primary">
                                <i class="bx bx-edit"></i> Edit Banner
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Title:</label>
                                    <p class="mb-0">{{ $banner->title }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Type:</label>
                                    <p class="mb-0">
                                        <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $banner->type)) }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Position:</label>
                                    <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $banner->position)) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Status:</label>
                                    <p class="mb-0">
                                        <span class="badge bg-{{ $banner->status === 'active' ? 'success' : ($banner->status === 'inactive' ? 'danger' : ($banner->status === 'scheduled' ? 'warning' : 'secondary')) }}">
                                            {{ ucfirst($banner->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            @if($banner->description)
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Description:</label>
                                    <p class="mb-0">{{ $banner->description }}</p>
                                </div>
                            </div>
                            @endif
                            @if($banner->link_url)
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Click URL:</label>
                                    <p class="mb-0">
                                        <a href="{{ $banner->link_url }}" target="_blank" class="text-primary">
                                            {{ $banner->link_url }} <i class="bx bx-link-external fs-12"></i>
                                        </a>
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Banner Images -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Banner Images</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($banner->image_url)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Desktop Image:</label>
                                    <div class="mt-2">
                                        <img src="{{ $banner->image_url }}" alt="Desktop Banner" class="img-fluid rounded border">
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($banner->mobile_image_url)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Mobile Image:</label>
                                    <div class="mt-2">
                                        <img src="{{ $banner->mobile_image_url }}" alt="Mobile Banner" class="img-fluid rounded border">
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4">
                <!-- Banner Statistics -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Banner Statistics</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center">
                                    <h4 class="fw-semibold text-primary mb-1">{{ number_format($banner->impression_count ?? 0) }}</h4>
                                    <p class="text-muted mb-0 fs-12">Impressions</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h4 class="fw-semibold text-success mb-1">{{ number_format($banner->click_count ?? 0) }}</h4>
                                    <p class="text-muted mb-0 fs-12">Clicks</p>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="text-center">
                                    <h4 class="fw-semibold text-info mb-1">
                                        {{ $banner->impression_count > 0 ? round(($banner->click_count / $banner->impression_count) * 100, 2) : 0 }}%
                                    </h4>
                                    <p class="text-muted mb-0 fs-12">Click Through Rate</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Banner Settings -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Banner Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sort Order:</label>
                            <p class="mb-0">{{ $banner->sort_order }}</p>
                        </div>
                        @if($banner->start_date)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Start Date:</label>
                            <p class="mb-0">{{ $banner->start_date->format('M d, Y h:i A') }}</p>
                        </div>
                        @endif
                        @if($banner->end_date)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">End Date:</label>
                            <p class="mb-0">{{ $banner->end_date->format('M d, Y h:i A') }}</p>
                        </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Created:</label>
                            <p class="mb-0">{{ $banner->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Last Updated:</label>
                            <p class="mb-0">{{ $banner->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Actions</div>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-primary">
                                <i class="bx bx-edit"></i> Edit Banner
                            </a>
                            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back"></i> Back to List
                            </a>
                            <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this banner?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bx bx-trash"></i> Delete Banner
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
