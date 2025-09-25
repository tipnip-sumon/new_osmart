@extends('admin.layouts.app')

@section('title', 'Edit Delivery Charge')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit me-2"></i>Edit Delivery Charge
        </h1>
        <div>
            <a href="{{ route('admin.delivery-charges.show', $deliveryCharge) }}" class="btn btn-info btn-sm shadow-sm me-2">
                <i class="fas fa-eye fa-sm text-white-50 me-1"></i>View
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
                        <i class="fas fa-truck me-2"></i>Edit Delivery Charge Information
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.delivery-charges.update', $deliveryCharge) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="district" class="form-label">District <span class="text-danger">*</span></label>
                                    <select class="form-control @error('district') is-invalid @enderror" 
                                            id="district" 
                                            name="district" 
                                            required>
                                        <option value="">Select District</option>
                                        @foreach($bangladeshData as $district)
                                            <option value="{{ $district['name'] }}" 
                                                    {{ old('district', $deliveryCharge->district) == $district['name'] ? 'selected' : '' }}>
                                                {{ $district['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('district')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="upazila" class="form-label">Upazila</label>
                                    <select class="form-control @error('upazila') is-invalid @enderror" 
                                            id="upazila" 
                                            name="upazila">
                                        <option value="">Select Upazila</option>
                                    </select>
                                    @error('upazila')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="ward" class="form-label">Ward/Union</label>
                                    <select class="form-control @error('ward') is-invalid @enderror" 
                                            id="ward" 
                                            name="ward">
                                        <option value="">Select Ward/Union</option>
                                    </select>
                                    @error('ward')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="charge" class="form-label">Delivery Charge (৳) <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('charge') is-invalid @enderror" 
                                           id="charge" 
                                           name="charge" 
                                           value="{{ old('charge', $deliveryCharge->charge) }}" 
                                           placeholder="0.00"
                                           step="0.01"
                                           min="0"
                                           max="9999.99"
                                           required>
                                    @error('charge')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="estimated_delivery_time" class="form-label">Estimated Delivery Time</label>
                            <input type="text" 
                                   class="form-control @error('estimated_delivery_time') is-invalid @enderror" 
                                   id="estimated_delivery_time" 
                                   name="estimated_delivery_time" 
                                   value="{{ old('estimated_delivery_time', $deliveryCharge->estimated_delivery_time) }}" 
                                   placeholder="e.g., 3-5 business days">
                            @error('estimated_delivery_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">This will be shown to customers during checkout</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.delivery-charges.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <div>
                                <button type="button" class="btn btn-danger me-2" onclick="deleteCharge()">
                                    <i class="fas fa-trash me-1"></i>Delete
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Update Delivery Charge
                                </button>
                            </div>
                        </div>
                    </form>

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
                        <i class="fas fa-info-circle me-2"></i>Current Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-5"><strong>ID:</strong></div>
                        <div class="col-7">{{ $deliveryCharge->id }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>District:</strong></div>
                        <div class="col-7">{{ $deliveryCharge->district }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Upazila:</strong></div>
                        <div class="col-7">{{ $deliveryCharge->upazila ?: '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Ward:</strong></div>
                        <div class="col-7">{{ $deliveryCharge->ward ?: '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Charge:</strong></div>
                        <div class="col-7">
                            <span class="badge bg-success">৳{{ number_format($deliveryCharge->charge, 2) }}</span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Est. Time:</strong></div>
                        <div class="col-7">{{ $deliveryCharge->estimated_delivery_time }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Created:</strong></div>
                        <div class="col-7">{{ $deliveryCharge->created_at->format('M d, Y H:i') }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Updated:</strong></div>
                        <div class="col-7">{{ $deliveryCharge->updated_at->format('M d, Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-info-circle me-2"></i>Guidelines
                    </h6>
                </div>
                <div class="card-body">
                    <h6><i class="fas fa-lightbulb me-1"></i>Tips:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-1"></i>District is required</li>
                        <li><i class="fas fa-check text-success me-1"></i>Upazila and Ward are optional</li>
                        <li><i class="fas fa-check text-success me-1"></i>More specific locations take priority</li>
                        <li><i class="fas fa-check text-success me-1"></i>Each location combination must be unique</li>
                    </ul>
                    
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-1"></i>Warning:</h6>
                        <p class="mb-0 small">
                            Changing this delivery charge will affect all future orders for this location. Orders that are already placed will not be affected.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Bangladesh location data (passed from controller)
    const bangladeshData = @json($bangladeshData);

    $(document).ready(function() {
        // Get old values for validation error restoration
        const oldDistrict = "{{ old('district', $deliveryCharge->district) }}";
        const oldUpazila = "{{ old('upazila', $deliveryCharge->upazila) }}";
        const oldWard = "{{ old('ward', $deliveryCharge->ward) }}";

        // Initialize dropdowns on page load
        initializeDropdowns();

        // Handle district change
        $('#district').on('change', function() {
            populateUpazilas($(this).val());
            clearWards();
        });

        // Handle upazila change
        $('#upazila').on('change', function() {
            populateWards($('#district').val(), $(this).val());
        });

        function initializeDropdowns() {
            if (oldDistrict) {
                $('#district').val(oldDistrict);
                populateUpazilas(oldDistrict);
                
                if (oldUpazila) {
                    setTimeout(() => {
                        $('#upazila').val(oldUpazila);
                        populateWards(oldDistrict, oldUpazila);
                        
                        if (oldWard) {
                            setTimeout(() => {
                                $('#ward').val(oldWard);
                            }, 100);
                        }
                    }, 100);
                }
            }
        }

        function populateUpazilas(districtName) {
            const upazilaSelect = $('#upazila');
            upazilaSelect.empty().append('<option value="">Select Upazila</option>');
            
            if (!districtName) return;

            const district = bangladeshData.find(d => d.name === districtName);
            if (district && district.upazilas) {
                district.upazilas.forEach(upazila => {
                    upazilaSelect.append(`<option value="${upazila.name}">${upazila.name}</option>`);
                });
            }
        }

        function populateWards(districtName, upazilaName) {
            const wardSelect = $('#ward');
            wardSelect.empty().append('<option value="">Select Ward/Union</option>');
            
            if (!districtName || !upazilaName) return;

            const district = bangladeshData.find(d => d.name === districtName);
            if (district && district.upazilas) {
                const upazila = district.upazilas.find(u => u.name === upazilaName);
                if (upazila && upazila.unions) {
                    upazila.unions.forEach(union => {
                        wardSelect.append(`<option value="${union}">${union}</option>`);
                    });
                }
            }
        }

        function clearWards() {
            $('#ward').empty().append('<option value="">Select Ward/Union</option>');
        }
    });
</script>
@endpush

@push('scripts')
<script>
function deleteCharge() {
    if (confirm('Are you sure you want to delete this delivery charge? This action cannot be undone.\n\nThis will affect future orders for this location.')) {
        document.getElementById('delete-form').submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Auto-capitalize first letter of location names
    const locationInputs = ['district', 'upazila', 'ward'];
    locationInputs.forEach(function(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('blur', function() {
                if (this.value) {
                    this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();
                }
            });
        }
    });

    // Format charge input
    const chargeInput = document.getElementById('charge');
    if (chargeInput) {
        chargeInput.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    }
});
</script>
@endpush