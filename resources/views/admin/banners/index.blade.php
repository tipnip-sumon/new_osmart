@extends('admin.layouts.app')

@section('title', 'Banner Management')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Banner Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Banners</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-primary">
                                    <i class="bx bx-image fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Banners</p>
                                        <h4 class="fw-semibold mb-1">{{ $stats['total_banners'] ?? 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-success">
                                    <i class="bx bx-show fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Active Banners</p>
                                        <h4 class="fw-semibold mb-1">{{ $stats['active_banners'] ?? 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-warning">
                                    <i class="bx bx-mouse fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Clicks</p>
                                        <h4 class="fw-semibold mb-1">{{ number_format($stats['total_clicks'] ?? 0) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-info">
                                    <i class="bx bx-bar-chart fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Click Rate</p>
                                        <h4 class="fw-semibold mb-1">{{ $stats['average_ctr'] ?? 0 }}%</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banner Management Card -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Banners
                        </div>
                        <div class="d-flex">
                            <a href="{{ route('admin.banners.create') }}" class="btn btn-sm btn-primary">
                                <i class="bx bx-plus"></i> Add Banner
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Position</th>
                                        <th>Status</th>
                                        <th>Clicks</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($banners as $banner)
                                    <tr>
                                        <td><input type="checkbox" name="banner_ids[]" value="{{ $banner->id }}"></td>
                                        <td>
                                            @if($banner->image_url)
                                                <img src="{{ $banner->image_url }}" alt="Banner" class="avatar avatar-md">
                                            @else
                                                <div class="avatar avatar-md bg-light d-flex align-items-center justify-content-center">
                                                    <i class="bx bx-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $banner->title ?? 'Untitled Banner' }}</div>
                                            <small class="text-muted">{{ Str::limit($banner->description, 30) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $banner->type === 'image' ? 'primary' : ($banner->type === 'video' ? 'info' : 'secondary') }}">
                                                {{ ucfirst($banner->type) }}
                                            </span>
                                        </td>
                                        <td>{{ ucfirst($banner->position) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $banner->status === 'active' ? 'success' : ($banner->status === 'inactive' ? 'danger' : ($banner->status === 'scheduled' ? 'warning' : 'secondary')) }}">
                                                {{ ucfirst($banner->status) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($banner->click_count ?? 0) }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.banners.show', $banner->id) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this banner?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bx bx-image fs-48 text-muted mb-2"></i>
                                                <h6 class="text-muted">No banners found</h6>
                                                <p class="text-muted mb-3">Start by creating your first banner</p>
                                                <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                                                    <i class="bx bx-plus"></i> Create Banner
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($banners->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="text-muted">
                                    Showing {{ $banners->firstItem() }} to {{ $banners->lastItem() }} of {{ $banners->total() }} results
                                </span>
                            </div>
                            <div>
                                {{ $banners->links() }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="banner_ids[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
</script>
@endpush
