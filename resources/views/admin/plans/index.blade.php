@extends('admin.layouts.app')

@section('title', 'Plans Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Plans Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Plans</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Plans</p>
                            <div class="d-flex align-items-center">
                                <h4 class="fw-semibold flex-shrink-0 mb-0">{{ $plans->total() }}</h4>
                            </div>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-primary rounded fs-3">
                                <i class="ri-file-list-3-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Active Plans</p>
                            <div class="d-flex align-items-center">
                                <h4 class="fw-semibold flex-shrink-0 mb-0">{{ $plans->where('status', true)->count() }}</h4>
                            </div>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success rounded fs-3">
                                <i class="ri-checkbox-circle-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Featured Plans</p>
                            <div class="d-flex align-items-center">
                                <h4 class="fw-semibold flex-shrink-0 mb-0">{{ $plans->where('featured', true)->count() }}</h4>
                            </div>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning rounded fs-3">
                                <i class="ri-star-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Point-Based Plans</p>
                            <div class="d-flex align-items-center">
                                <h4 class="fw-semibold flex-shrink-0 mb-0">{{ $plans->where('point_based', true)->count() }}</h4>
                            </div>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info rounded fs-3">
                                <i class="ri-coins-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Plans Table -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Plans List</h5>
                    <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">
                        <i class="ri-add-line align-bottom me-1"></i> Create New Plan
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Points</th>
                                    <th scope="col">Commission</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Featured</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($plans as $plan)
                                    <tr>
                                        <td>{{ $plan->id }}</td>
                                        <td>
                                            <span class="avatar avatar-md rounded">
                                                @php
                                                    // Comprehensive image URL handling for plans
                                                    $mainImageUrl = '/admin-assets/images/media/1.jpg'; // Default fallback
                                                    
                                                    // Convert array to object-like access for consistency
                                                    $planObj = (object) $plan->toArray();
                                                    
                                                    // First try image_data array with comprehensive structure handling
                                                    if (isset($planObj->image_data) && $planObj->image_data) {
                                                        $images = is_string($planObj->image_data) ? json_decode($planObj->image_data, true) : $planObj->image_data;
                                                        if (is_array($images) && !empty($images)) {
                                                            $image = $images; // Get image data
                                                            
                                                            // Handle complex nested structure first
                                                            if (is_array($image) && isset($image['sizes']['medium']['storage_url'])) {
                                                                // New complex structure - use medium size storage_url
                                                                $mainImageUrl = $image['sizes']['medium']['storage_url'];
                                                            } elseif (is_array($image) && isset($image['sizes']['original']['storage_url'])) {
                                                                // Fallback to original if medium not available
                                                                $mainImageUrl = $image['sizes']['original']['storage_url'];
                                                            } elseif (is_array($image) && isset($image['sizes']['large']['storage_url'])) {
                                                                // Fallback to large if original not available
                                                                $mainImageUrl = $image['sizes']['large']['storage_url'];
                                                            } elseif (is_array($image) && isset($image['urls']['medium'])) {
                                                                // Legacy complex URL structure - use medium size
                                                                $mainImageUrl = $image['urls']['medium'];
                                                            } elseif (is_array($image) && isset($image['urls']['original'])) {
                                                                // Legacy fallback to original if medium not available
                                                                $mainImageUrl = $image['urls']['original'];
                                                            } elseif (is_array($image) && isset($image['url']) && is_string($image['url'])) {
                                                                $mainImageUrl = $image['url'];
                                                            } elseif (is_array($image) && isset($image['path']) && is_string($image['path'])) {
                                                                $mainImageUrl = asset('storage/' . $image['path']);
                                                            }
                                                        }
                                                    }
                                                    
                                                    // Fallback to image field if image_data didn't work
                                                    if ($mainImageUrl === '/admin-assets/images/media/1.jpg' && isset($planObj->image) && $planObj->image) {
                                                        $planImage = $planObj->image;
                                                        if ($planImage) {
                                                            $mainImageUrl = str_starts_with($planImage, 'http') ? $planImage : asset('storage/' . $planImage);
                                                        }
                                                    }
                                                    
                                                    // Convert storage URLs to direct-storage for reliability
                                                    if (!str_starts_with($mainImageUrl, 'http') && !str_starts_with($mainImageUrl, '/admin-assets/')) {
                                                        if (str_starts_with($mainImageUrl, '/storage/')) {
                                                            $path = str_replace('/storage/', '', $mainImageUrl);
                                                            $mainImageUrl = '/direct-storage/' . $path;
                                                        } else {
                                                            $mainImageUrl = '/direct-storage/' . ltrim($mainImageUrl, '/');
                                                        }
                                                    }
                                                    
                                                    // Debug information
                                                    $debugInfo = [];
                                                    $debugInfo[] = 'Image_data field: ' . (isset($planObj->image_data) ? 'exists' : 'missing');
                                                    $debugInfo[] = 'Image field: ' . (isset($planObj->image) ? 'exists' : 'missing');
                                                    $debugInfo[] = 'Final URL: ' . $mainImageUrl;
                                                @endphp
                                                
                                                @if($mainImageUrl !== '/admin-assets/images/media/1.jpg')
                                                    <img src="{{ $mainImageUrl }}" alt="{{ $plan->name }}" 
                                                         class="img-fluid rounded" 
                                                         style="width: 50px; height: 50px; object-fit: cover;"
                                                         onerror="this.src='{{ asset('/admin-assets/images/media/1.jpg') }}'"
                                                         title="Debug: {{ implode(' | ', $debugInfo) }}">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="ri-image-line text-muted"></i>
                                                    </div>
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $plan->name }}</div>
                                            <small class="text-muted">{{ Str::limit($plan->description, 50) }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">৳{{ number_format($plan->fixed_amount, 2) }}</span>
                                            @if($plan->minimum > 0 || $plan->maximum > 0)
                                                <br><small class="text-muted">Range: ৳{{ number_format($plan->minimum, 2) }} - ৳{{ number_format($plan->maximum, 2) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $plan->points }} points</span>
                                            <br><small class="text-muted">Value: ৳{{ $plan->point_value }}</small>
                                        </td>
                                        <td>
                                            @if($plan->spot_commission_rate > 0)
                                                <span class="badge bg-success">{{ $plan->spot_commission_rate }}%</span>
                                            @else
                                                <span class="badge bg-warning">৳{{ $plan->fixed_sponsor }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                @if($plan->point_based)
                                                    <span class="badge bg-primary">Point-Based</span>
                                                @endif
                                                @if($plan->instant_activation)
                                                    <span class="badge bg-success">Instant</span>
                                                @endif
                                                @if($plan->lifetime)
                                                    <span class="badge bg-info">Lifetime</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" 
                                                       id="status_{{ $plan->id }}" {{ $plan->status ? 'checked' : '' }}
                                                       onchange="toggleStatus({{ $plan->id }})">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" 
                                                       id="featured_{{ $plan->id }}" {{ $plan->featured ? 'checked' : '' }}
                                                       onchange="toggleFeatured({{ $plan->id }})">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('admin.plans.show', $plan) }}">
                                                        <i class="ri-eye-fill align-bottom me-2 text-primary"></i> View
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="{{ route('admin.plans.edit', $plan) }}">
                                                        <i class="ri-edit-2-fill align-bottom me-2 text-success"></i> Edit
                                                    </a></li>
                                                    <li class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="deletePlan({{ $plan->id }})">
                                                        <i class="ri-delete-bin-5-fill align-bottom me-2"></i> Delete
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ri-file-list-3-line fs-2 text-muted"></i>
                                                <p class="mt-2">No plans found</p>
                                                <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">Create First Plan</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($plans->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $plans->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this plan? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStatus(planId) {
    fetch(`/admin/plans/${planId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showToast(data.message, 'success');
        } else {
            // Revert checkbox
            document.getElementById(`status_${planId}`).checked = !document.getElementById(`status_${planId}`).checked;
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        // Revert checkbox
        document.getElementById(`status_${planId}`).checked = !document.getElementById(`status_${planId}`).checked;
        showToast('Failed to update status', 'error');
    });
}

function toggleFeatured(planId) {
    fetch(`/admin/plans/${planId}/toggle-featured`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
        } else {
            // Revert checkbox
            document.getElementById(`featured_${planId}`).checked = !document.getElementById(`featured_${planId}`).checked;
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        // Revert checkbox
        document.getElementById(`featured_${planId}`).checked = !document.getElementById(`featured_${planId}`).checked;
        showToast('Failed to update featured status', 'error');
    });
}

function deletePlan(planId) {
    document.getElementById('deleteForm').action = `/admin/plans/${planId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function showToast(message, type = 'info') {
    // You can implement your toast notification here
    // For now, we'll use a simple alert
    if (type === 'success') {
        alert('✓ ' + message);
    } else {
        alert('✗ ' + message);
    }
}
</script>
@endpush
