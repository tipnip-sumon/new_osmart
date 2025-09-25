@extends('admin.layouts.app')

@section('title', 'View Delivery Charge')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye me-2"></i>View Delivery Charge
        </h1>
        <div>
            <a href="{{ route('admin.delivery-charges.edit', $deliveryCharge) }}" class="btn btn-warning btn-sm shadow-sm me-2">
                <i class="fas fa-edit fa-sm text-white-50 me-1"></i>Edit
            </a>
            <a href="{{ route('admin.delivery-charges.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50 me-1"></i>Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-truck me-2"></i>Delivery Charge Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">
                                        <i class="fas fa-map-marker-alt me-1"></i>Location Information
                                    </h6>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>District:</strong></div>
                                        <div class="col-8">{{ $deliveryCharge->district }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Upazila:</strong></div>
                                        <div class="col-8">{{ $deliveryCharge->upazila ?: '-' }}</div>
                                    </div>
                                    <div class="row mb-0">
                                        <div class="col-4"><strong>Ward:</strong></div>
                                        <div class="col-8">{{ $deliveryCharge->ward ?: '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-success">
                                        <i class="fas fa-money-bill-wave me-1"></i>Pricing & Delivery
                                    </h6>
                                    <div class="row mb-2">
                                        <div class="col-5"><strong>Charge:</strong></div>
                                        <div class="col-7">
                                            <span class="badge bg-success fs-6">৳{{ number_format($deliveryCharge->charge, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="row mb-0">
                                        <div class="col-5"><strong>Est. Time:</strong></div>
                                        <div class="col-7">{{ $deliveryCharge->estimated_delivery_time }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="card-title text-info">
                                <i class="fas fa-clock me-1"></i>Record Information
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>ID:</strong></div>
                                        <div class="col-8">{{ $deliveryCharge->id }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Created:</strong></div>
                                        <div class="col-8">{{ $deliveryCharge->created_at->format('F j, Y \a\t g:i A') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Updated:</strong></div>
                                        <div class="col-8">{{ $deliveryCharge->updated_at->format('F j, Y \a\t g:i A') }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Status:</strong></div>
                                        <div class="col-8">
                                            <span class="badge bg-success">Active</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-danger" onclick="deleteCharge()">
                            <i class="fas fa-trash me-1"></i>Delete Charge
                        </button>
                        <div>
                            <a href="{{ route('admin.delivery-charges.edit', $deliveryCharge) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i>Edit Charge
                            </a>
                        </div>
                    </div>

                    <form id="delete-form" 
                          action="{{ route('admin.delivery-charges.destroy', $deliveryCharge) }}" 
                          method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-layer-group me-2"></i>Priority Level
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $priority = 'District Level';
                        $priorityClass = 'bg-primary';
                        $priorityLevel = 3;
                        
                        if ($deliveryCharge->ward) {
                            $priority = 'Ward Level (Highest)';
                            $priorityClass = 'bg-success';
                            $priorityLevel = 1;
                        } elseif ($deliveryCharge->upazila) {
                            $priority = 'Upazila Level (Medium)';
                            $priorityClass = 'bg-warning';
                            $priorityLevel = 2;
                        }
                    @endphp

                    <div class="text-center mb-3">
                        <span class="badge {{ $priorityClass }} fs-6 p-2">{{ $priority }}</span>
                    </div>

                    <div class="mb-3">
                        <h6>Location Specificity:</h6>
                        <div class="progress mb-2" style="height: 20px;">
                            @if($priorityLevel == 1)
                                <div class="progress-bar bg-success" style="width: 100%">High</div>
                            @elseif($priorityLevel == 2)
                                <div class="progress-bar bg-warning" style="width: 66%">Medium</div>
                            @else
                                <div class="progress-bar bg-primary" style="width: 33%">Basic</div>
                            @endif
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i>How Priority Works:</h6>
                        <p class="mb-0 small">
                            When calculating delivery charges, the system will use this charge for:
                        </p>
                        <ul class="mb-0 small">
                            @if ($deliveryCharge->ward)
                                <li>Orders to {{ $deliveryCharge->ward }}, {{ $deliveryCharge->upazila }}, {{ $deliveryCharge->district }}</li>
                            @elseif ($deliveryCharge->upazila)
                                <li>Orders to any ward in {{ $deliveryCharge->upazila }}, {{ $deliveryCharge->district }}</li>
                                <li>Only if no ward-specific charge exists</li>
                            @else
                                <li>Orders to any location in {{ $deliveryCharge->district }}</li>
                                <li>Only if no upazila/ward-specific charge exists</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">
                        <i class="fas fa-cogs me-2"></i>Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.delivery-charges.edit', $deliveryCharge) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Edit This Charge
                        </a>
                        <a href="{{ route('admin.delivery-charges.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Add New Charge
                        </a>
                        <a href="{{ route('admin.delivery-charges.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-list me-1"></i>View All Charges
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteCharge() {
    if (confirm('Are you sure you want to delete this delivery charge?\n\nThis action cannot be undone and will affect future orders for this location.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endpush