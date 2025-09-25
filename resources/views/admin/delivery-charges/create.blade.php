@extends('admin.layouts.app')

@section('title', 'Add New Delivery Charge')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus me-2"></i>Add New Delivery Charge
        </h1>
        <a href="{{ route('admin.delivery-charges.index') }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 me-1"></i>Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-truck me-2"></i>Delivery Charge Information
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.delivery-charges.store') }}" method="POST">
                        @csrf
                        
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
                                                    {{ old('district') == $district['name'] ? 'selected' : '' }}>
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
                                           value="{{ old('charge') }}" 
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
                                   value="{{ old('estimated_delivery_time', '3-5 business days') }}" 
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
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Save Delivery Charge
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle me-2"></i>Guidelines
                    </h6>
                </div>
                <div class="card-body">
                    <h6><i class="fas fa-lightbulb me-1"></i>Tips:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-1"></i>District is required</li>
                        <li><i class="fas fa-check text-success me-1"></i>Upazila and Ward are optional</li>
                        <li><i class="fas fa-check text-success me-1"></i>More specific locations (with Ward) take priority</li>
                        <li><i class="fas fa-check text-success me-1"></i>Each location combination must be unique</li>
                    </ul>
                    
                    <hr>
                    
                    <h6><i class="fas fa-sort-amount-down me-1"></i>Priority Order:</h6>
                    <ol>
                        <li>District + Upazila + Ward</li>
                        <li>District + Upazila</li>
                        <li>District only</li>
                        <li>Default charge (if no match)</li>
                    </ol>

                    <hr>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-1"></i>Note:</h6>
                        <p class="mb-0 small">
                            The system will automatically use the most specific location match when calculating delivery charges for orders.
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
// Bangladesh location data
const bangladeshData = @json($bangladeshData);

document.addEventListener('DOMContentLoaded', function() {
    const districtSelect = document.getElementById('district');
    const upazilaSelect = document.getElementById('upazila');
    const wardSelect = document.getElementById('ward');

    // Handle district change
    districtSelect.addEventListener('change', function() {
        const selectedDistrict = this.value;
        
        // Clear upazila and ward
        upazilaSelect.innerHTML = '<option value="">Select Upazila</option>';
        wardSelect.innerHTML = '<option value="">Select Ward/Union</option>';
        
        if (selectedDistrict) {
            // Find the selected district data
            const districtData = bangladeshData.find(d => d.name === selectedDistrict);
            
            if (districtData && districtData.upazilas) {
                // Populate upazila dropdown
                districtData.upazilas.forEach(upazila => {
                    const option = document.createElement('option');
                    option.value = upazila.name;
                    option.textContent = upazila.name;
                    upazilaSelect.appendChild(option);
                });
            }
        }
    });

    // Handle upazila change
    upazilaSelect.addEventListener('change', function() {
        const selectedDistrict = districtSelect.value;
        const selectedUpazila = this.value;
        
        // Clear ward
        wardSelect.innerHTML = '<option value="">Select Ward/Union</option>';
        
        if (selectedDistrict && selectedUpazila) {
            // Find the selected district and upazila data
            const districtData = bangladeshData.find(d => d.name === selectedDistrict);
            
            if (districtData && districtData.upazilas) {
                const upazilaData = districtData.upazilas.find(u => u.name === selectedUpazila);
                
                if (upazilaData && upazilaData.unions) {
                    // Populate ward/union dropdown
                    upazilaData.unions.forEach(union => {
                        const option = document.createElement('option');
                        option.value = union;
                        option.textContent = union;
                        wardSelect.appendChild(option);
                    });
                }
            }
        }
    });

    // Auto-capitalize first letter of location names (if manual entry is added later)
    const locationInputs = ['district', 'upazila', 'ward'];
    locationInputs.forEach(function(inputId) {
        const input = document.getElementById(inputId);
        if (input && input.tagName === 'INPUT') {
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

    // Restore selections on page load (for validation errors)
    const oldDistrict = "{{ old('district') }}";
    const oldUpazila = "{{ old('upazila') }}";
    const oldWard = "{{ old('ward') }}";
    
    if (oldDistrict) {
        districtSelect.value = oldDistrict;
        districtSelect.dispatchEvent(new Event('change'));
        
        setTimeout(() => {
            if (oldUpazila) {
                upazilaSelect.value = oldUpazila;
                upazilaSelect.dispatchEvent(new Event('change'));
                
                setTimeout(() => {
                    if (oldWard) {
                        wardSelect.value = oldWard;
                    }
                }, 100);
            }
        }, 100);
    }
});
</script>
@endpush