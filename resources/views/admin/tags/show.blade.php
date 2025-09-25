@extends('admin.layouts.app')

@section('title', 'View Tag')

@php
$tag = $tag ?? [
    'id' => 1,
    'name' => 'Technology',
    'slug' => 'technology',
    'description' => 'Technology related products and accessories',
    'color' => '#007bff',
    'sort_order' => 0,
    'is_active' => true,
    'meta_title' => 'Technology Products',
    'meta_description' => 'Browse our comprehensive technology products collection',
    'meta_keywords' => 'technology, gadgets, electronics, computers',
    'created_at' => now(),
    'updated_at' => now()
];
@endphp

@push('styles')
<style>
    .info-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .info-section h6 {
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e9ecef;
    }
    .tag-preview {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        color: white;
        font-weight: 500;
        font-size: 1rem;
    }
    .stat-card {
        text-align: center;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .meta-preview {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 15px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .meta-title {
        color: #1a0dab;
        font-size: 18px;
        line-height: 1.2;
        margin-bottom: 5px;
        text-decoration: none;
        cursor: pointer;
    }
    .meta-url {
        color: #006621;
        font-size: 14px;
        margin-bottom: 5px;
    }
    .meta-description {
        color: #545454;
        font-size: 13px;
        line-height: 1.4;
    }
    .action-buttons .btn {
        margin-right: 8px;
        margin-bottom: 8px;
    }
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -23px;
        top: 5px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #007bff;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">View Tag</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tags.index') }}">Tags</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $tag['name'] }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Main Content -->
        <div class="row">
            <!-- Basic Information -->
            <div class="col-lg-8">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">Tag Details</div>
                        <div class="action-buttons">
                            <a href="{{ route('admin.tags.edit', $tag['id']) }}" class="btn btn-warning btn-sm">
                                <i class="bx bx-edit me-1"></i>Edit Tag
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteTag({{ $tag['id'] }})">
                                <i class="bx bx-trash me-1"></i>Delete
                            </button>
                            <button type="button" class="btn btn-{{ $tag['is_active'] ? 'success' : 'secondary' }} btn-sm" 
                                    onclick="toggleStatus({{ $tag['id'] }}, '{{ $tag['is_active'] ? 'active' : 'inactive' }}')">
                                <i class="bx bx-{{ $tag['is_active'] ? 'check' : 'x' }} me-1"></i>
                                {{ $tag['is_active'] ? 'Active' : 'Inactive' }}
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Basic Information Section -->
                        <div class="info-section">
                            <h6><i class="bx bx-info-circle me-2"></i>Basic Information</h6>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tag Name:</label>
                                        <p class="form-control-static">{{ $tag['name'] }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Slug:</label>
                                        <p class="form-control-static">
                                            <code>{{ $tag['slug'] }}</code>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Description:</label>
                                        <p class="form-control-static">
                                            {{ $tag['description'] ?: 'No description provided' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tag Preview:</label>
                                        <div>
                                            <span class="tag-preview" style="background-color: {{ $tag['color'] }};">
                                                {{ $tag['name'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Color Code:</label>
                                        <p class="form-control-static">
                                            <span class="d-flex align-items-center">
                                                <span class="badge" style="background-color: {{ $tag['color'] }}; width: 20px; height: 20px; margin-right: 8px;"></span>
                                                {{ $tag['color'] }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Sort Order:</label>
                                        <p class="form-control-static">{{ $tag['sort_order'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Information Section -->
                        <div class="info-section">
                            <h6><i class="bx bx-search-alt me-2"></i>SEO Information</h6>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Meta Title:</label>
                                        <p class="form-control-static">
                                            {{ $tag['meta_title'] ?: $tag['name'] }}
                                            @if(!$tag['meta_title'])
                                                <small class="text-muted">(using tag name)</small>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Meta Keywords:</label>
                                        <p class="form-control-static">
                                            {{ $tag['meta_keywords'] ?: 'Not specified' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Meta Description:</label>
                                        <p class="form-control-static">
                                            {{ $tag['meta_description'] ?: 'Not specified' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Search Engine Preview -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Search Engine Preview:</label>
                                <div class="meta-preview">
                                    <div class="meta-title">{{ $tag['meta_title'] ?: $tag['name'] }}</div>
                                    <div class="meta-url">{{ url('/tags/' . $tag['slug']) }}</div>
                                    @if($tag['meta_description'])
                                        <div class="meta-description">{{ $tag['meta_description'] }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Activity Timeline -->
                        <div class="info-section">
                            <h6><i class="bx bx-time me-2"></i>Activity Timeline</h6>
                            
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="fw-semibold">Tag Created</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($tag['created_at'])->format('F j, Y \a\t g:i A') }}</small>
                                </div>
                                <div class="timeline-item">
                                    <div class="fw-semibold">Last Updated</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($tag['updated_at'])->format('F j, Y \a\t g:i A') }}</small>
                                </div>
                                @if($tag['is_active'])
                                <div class="timeline-item">
                                    <div class="fw-semibold text-success">Tag is Active</div>
                                    <small class="text-muted">Visible on frontend</small>
                                </div>
                                @else
                                <div class="timeline-item">
                                    <div class="fw-semibold text-warning">Tag is Inactive</div>
                                    <small class="text-muted">Hidden from frontend</small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Sidebar -->
            <div class="col-lg-4">
                <!-- Usage Statistics -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Usage Statistics</div>
                    </div>
                    <div class="card-body">
                        <div class="stat-card bg-primary-transparent">
                            <h3 class="text-primary mb-2">0</h3>
                            <p class="text-muted mb-0">Total Products</p>
                        </div>
                        
                        <div class="stat-card bg-success-transparent">
                            <h3 class="text-success mb-2">0</h3>
                            <p class="text-muted mb-0">Active Products</p>
                        </div>
                        
                        <div class="stat-card bg-info-transparent">
                            <h3 class="text-info mb-2">0</h3>
                            <p class="text-muted mb-0">Page Views</p>
                        </div>
                        
                        <div class="stat-card bg-warning-transparent">
                            <h3 class="text-warning mb-2">{{ \Carbon\Carbon::parse($tag['created_at'])->diffInDays(now()) }}</h3>
                            <p class="text-muted mb-0">Days Active</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Quick Actions</div>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.tags.edit', $tag['id']) }}" class="btn btn-outline-warning">
                                <i class="bx bx-edit me-2"></i>Edit Tag
                            </a>
                            
                            <button type="button" class="btn btn-outline-{{ $tag['is_active'] ? 'secondary' : 'success' }}" 
                                    onclick="toggleStatus({{ $tag['id'] }}, '{{ $tag['is_active'] ? 'active' : 'inactive' }}')">
                                <i class="bx bx-{{ $tag['is_active'] ? 'x' : 'check' }} me-2"></i>
                                {{ $tag['is_active'] ? 'Deactivate' : 'Activate' }}
                            </button>
                            
                            <a href="#" class="btn btn-outline-info" onclick="viewOnFrontend('{{ $tag['slug'] }}')">
                                <i class="bx bx-show me-2"></i>View on Frontend
                            </a>
                            
                            <button type="button" class="btn btn-outline-primary" onclick="copyTagUrl()">
                                <i class="bx bx-copy me-2"></i>Copy URL
                            </button>
                            
                            <hr>
                            
                            <button type="button" class="btn btn-outline-danger" onclick="deleteTag({{ $tag['id'] }})">
                                <i class="bx bx-trash me-2"></i>Delete Tag
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Related Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Related Information</div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Tag ID:</span>
                            <code>#{{ $tag['id'] }}</code>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">URL Slug:</span>
                            <code>{{ $tag['slug'] }}</code>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Status:</span>
                            <span class="badge bg-{{ $tag['is_active'] ? 'success' : 'danger' }}">
                                {{ $tag['is_active'] ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Created:</span>
                            <span>{{ \Carbon\Carbon::parse($tag['created_at'])->format('M j, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function toggleStatus(id, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    const action = newStatus === 'active' ? 'activate' : 'deactivate';
    
    Swal.fire({
        title: `${action.charAt(0).toUpperCase() + action.slice(1)} Tag`,
        text: `Are you sure you want to ${action} this tag?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: newStatus === 'active' ? '#28a745' : '#6c757d',
        cancelButtonColor: '#d33',
        confirmButtonText: `Yes, ${action} it!`
    }).then((result) => {
        if (result.isConfirmed) {
            // Make AJAX request to toggle status
            fetch(`/admin/tags/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success!', data.message, 'success').then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error!', data.message || 'Failed to update status.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'An error occurred while updating status.', 'error');
            });
        }
    });
}

function deleteTag(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this! All products associated with this tag will be affected.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Make AJAX request to delete tag
            fetch(`/admin/tags/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', 'Tag has been deleted.', 'success').then(() => {
                        window.location.href = '{{ route("admin.tags.index") }}';
                    });
                } else {
                    Swal.fire('Error!', 'Failed to delete tag.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'An error occurred while deleting tag.', 'error');
            });
        }
    });
}

function viewOnFrontend(slug) {
    const frontendUrl = `{{ url('/tags') }}/${slug}`;
    window.open(frontendUrl, '_blank');
}

function copyTagUrl() {
    const tagUrl = '{{ url("/tags/" . $tag["slug"]) }}';
    
    if (navigator.clipboard) {
        navigator.clipboard.writeText(tagUrl).then(() => {
            Swal.fire({
                title: 'Copied!',
                text: 'Tag URL has been copied to clipboard.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = tagUrl;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        Swal.fire({
            title: 'Copied!',
            text: 'Tag URL has been copied to clipboard.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    }
}
</script>
@endpush
