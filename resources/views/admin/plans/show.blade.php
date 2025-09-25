@extends('admin.layouts.app')

@section('title', 'Plan Details - ' . $plan->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Plan Details - {{ $plan->name }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.plans.index') }}">Plans</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Plan Overview -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Plan Overview</h5>
                    <div class="d-flex gap-2">
                        @if($plan->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                        @if($plan->featured)
                            <span class="badge bg-warning">Featured</span>
                        @endif
                        @if($plan->point_based)
                            <span class="badge bg-primary">Point-Based</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3">Basic Information</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" style="width: 140px;">Plan ID:</td>
                                    <td class="fw-medium">{{ $plan->id }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Plan Name:</td>
                                    <td class="fw-medium">{{ $plan->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Fixed Amount:</td>
                                    <td class="fw-medium">৳{{ number_format($plan->fixed_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Amount Range:</td>
                                    <td>৳{{ number_format($plan->minimum, 2) }} - ৳{{ number_format($plan->maximum, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Created:</td>
                                    <td>{{ $plan->created_at->format('M d, Y H:i A') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Updated:</td>
                                    <td>{{ $plan->updated_at->format('M d, Y H:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3">Point System</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" style="width: 140px;">Points Required:</td>
                                    <td class="fw-medium">{{ number_format($plan->points) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Point Value:</td>
                                    <td class="fw-medium">৳{{ number_format($plan->point_value, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Total Value:</td>
                                    <td class="fw-medium">৳{{ number_format($plan->points * $plan->point_value, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Point-Based:</td>
                                    <td>
                                        @if($plan->point_based)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Instant Activation:</td>
                                    <td>
                                        @if($plan->instant_activation)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($plan->description)
                        <div class="mt-4">
                            <h6 class="fw-semibold mb-2">Description</h6>
                            <p class="text-muted">{{ $plan->description }}</p>
                        </div>
                    @endif

                    @if($plan->image || $plan->image_data)
                        <div class="mt-4">
                            <h6 class="fw-semibold mb-2">Plan Image</h6>
                            <div class="text-center">
                                <span class="avatar avatar-xl rounded">
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
                                             class="img-fluid rounded border" 
                                             style="max-width: 300px; max-height: 300px; object-fit: cover;"
                                             onerror="this.src='{{ asset('/admin-assets/images/media/1.jpg') }}'"
                                             title="Debug: {{ implode(' | ', $debugInfo) }}">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center border" 
                                             style="width: 300px; height: 300px;">
                                            <i class="ri-image-line text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                </span>
                                <p class="text-muted small mt-2 mb-0">{{ $plan->name }} Package Image</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Commission System -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Commission System</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3">Commission Settings</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" style="width: 160px;">Spot Commission Rate:</td>
                                    <td class="fw-medium">{{ $plan->spot_commission_rate }}%</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Fixed Sponsor Amount:</td>
                                    <td class="fw-medium">৳{{ number_format($plan->fixed_sponsor, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3">Commission Calculation</h6>
                            @if($plan->spot_commission_rate > 0)
                                @php
                                    $commissionAmount = ($plan->points * $plan->point_value * $plan->spot_commission_rate) / 100;
                                @endphp
                                <div class="alert alert-success">
                                    <strong>Percentage-based:</strong><br>
                                    ({{ number_format($plan->points) }} × ৳{{ number_format($plan->point_value, 2) }} × {{ $plan->spot_commission_rate }}%) = 
                                    <strong>৳{{ number_format($commissionAmount, 2) }}</strong>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <strong>Fixed amount:</strong><br>
                                    <strong>৳{{ number_format($plan->fixed_sponsor, 2) }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interest Settings -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Interest & Duration Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3">Interest Configuration</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" style="width: 120px;">Interest Rate:</td>
                                    <td class="fw-medium">{{ $plan->interest }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Interest Type:</td>
                                    <td>
                                        @if($plan->interest_type)
                                            <span class="badge bg-primary">Percentage (%)</span>
                                        @else
                                            <span class="badge bg-info">Fixed Amount (BDT)</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3">Duration Settings</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" style="width: 120px;">Duration:</td>
                                    <td class="fw-medium">{{ $plan->time }} {{ $plan->time_name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Repeat Time:</td>
                                    <td class="fw-medium">{{ $plan->repeat_time ?: 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Lifetime:</td>
                                    <td>
                                        @if($plan->lifetime)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics & Actions -->
        <div class="col-lg-4">
            <!-- Plan Statistics -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Plan Statistics</h5>
                </div>
                <div class="card-body">
                    @php
                        $investmentCount = \App\Models\Invest::where('plan_id', $plan->id)->count();
                        $totalInvested = \App\Models\Invest::where('plan_id', $plan->id)->sum('amount');
                        $activeInvestments = \App\Models\Invest::where('plan_id', $plan->id)->where('status', true)->count();
                    @endphp
                    
                    <div class="text-center mb-3">
                        <h4 class="fw-bold text-primary">{{ number_format($investmentCount) }}</h4>
                        <p class="text-muted mb-0">Total Investments</p>
                    </div>

                    <hr>

                    <div class="row text-center">
                        <div class="col-6">
                            <h5 class="fw-semibold">৳{{ number_format($totalInvested, 2) }}</h5>
                            <p class="text-muted small mb-0">Total Invested</p>
                        </div>
                        <div class="col-6">
                            <h5 class="fw-semibold">{{ number_format($activeInvestments) }}</h5>
                            <p class="text-muted small mb-0">Active Investments</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Plan Settings Summary -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Plan Settings</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Status</span>
                        @if($plan->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Featured</span>
                        @if($plan->featured)
                            <span class="badge bg-warning">Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Point-Based</span>
                        @if($plan->point_based)
                            <span class="badge bg-primary">Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Instant Activation</span>
                        @if($plan->instant_activation)
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Capital Back</span>
                        @if($plan->capital_back)
                            <span class="badge bg-info">Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Lifetime</span>
                        @if($plan->lifetime)
                            <span class="badge bg-info">Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-primary">
                            <i class="ri-edit-2-line align-bottom me-1"></i> Edit Plan
                        </a>
                        
                        <button type="button" class="btn {{ $plan->status ? 'btn-warning' : 'btn-success' }}" 
                                onclick="toggleStatus({{ $plan->id }})">
                            <i class="ri-toggle-line align-bottom me-1"></i> 
                            {{ $plan->status ? 'Deactivate' : 'Activate' }} Plan
                        </button>
                        
                        <button type="button" class="btn {{ $plan->featured ? 'btn-secondary' : 'btn-warning' }}" 
                                onclick="toggleFeatured({{ $plan->id }})">
                            <i class="ri-star-line align-bottom me-1"></i> 
                            {{ $plan->featured ? 'Remove Featured' : 'Make Featured' }}
                        </button>
                        
                        <a href="{{ route('admin.plans.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line align-bottom me-1"></i> Back to List
                        </a>
                        
                        <hr>
                        
                        <button type="button" class="btn btn-outline-danger" onclick="deletePlan({{ $plan->id }})">
                            <i class="ri-delete-bin-5-line align-bottom me-1"></i> Delete Plan
                        </button>
                    </div>
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
                <div class="alert alert-warning">
                    <strong>Warning:</strong> This plan has {{ $investmentCount }} investment(s). 
                    @if($investmentCount > 0)
                        You cannot delete a plan that has active investments.
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" {{ $investmentCount > 0 ? 'disabled' : '' }}>
                        Delete
                    </button>
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
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
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
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
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
