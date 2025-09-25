@extends('admin.layouts.app')

@section('title', 'Create New Vendor')

@push('styles')
<style>
.form-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid #e0e6ed;
}

.section-title {
    color: #1e293b;
    font-weight: 600;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f1f5f9;
}

.form-floating label {
    color: #64748b;
}

.form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
}

.btn-create {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-create:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.required-field::after {
    content: ' *';
    color: #dc2626;
}

.info-text {
    font-size: 0.875rem;
    color: #64748b;
    margin-top: 0.25rem;
}

@media (max-width: 768px) {
    .form-section {
        padding: 1rem;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Create New Vendor</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">Vendors</a></li>
                    <li class="breadcrumb-item active">Create Vendor</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.vendors.index') }}" class="btn btn-light">
            <i class="ti ti-arrow-left me-1"></i>Back to Vendors
        </a>
    </div>

    <!-- Create Vendor Form -->
    <form id="vendorForm" action="{{ route('admin.vendors.store') }}" method="POST">
        @csrf
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <!-- Personal Information -->
        <div class="form-section">
            <h5 class="section-title"><i class="ti ti-user me-2"></i>Personal Information</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" placeholder="Full Name" required>
                        <label for="name" class="required-field">Full Name</label>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required>
                        <label for="email" class="required-field">Email Address</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" placeholder="Password" required>
                        <label for="password" class="required-field">Password</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="info-text">Minimum 8 characters</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                        <label for="password_confirmation" class="required-field">Confirm Password</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}" placeholder="Phone Number">
                        <label for="phone">Phone Number</label>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Shop Information -->
        <div class="form-section">
            <h5 class="section-title"><i class="ti ti-building-store me-2"></i>Shop Information</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control @error('shop_name') is-invalid @enderror" 
                               id="shop_name" name="shop_name" value="{{ old('shop_name') }}" placeholder="Shop Name" required>
                        <label for="shop_name" class="required-field">Shop Name</label>
                        @error('shop_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control @error('business_license') is-invalid @enderror" 
                               id="business_license" name="business_license" value="{{ old('business_license') }}" placeholder="Business License">
                        <label for="business_license">Business License</label>
                        @error('business_license')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control @error('tax_id') is-invalid @enderror" 
                               id="tax_id" name="tax_id" value="{{ old('tax_id') }}" placeholder="Tax ID">
                        <label for="tax_id">Tax ID</label>
                        @error('tax_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <textarea class="form-control @error('shop_description') is-invalid @enderror" 
                                  id="shop_description" name="shop_description" style="height: 100px" 
                                  placeholder="Shop Description">{{ old('shop_description') }}</textarea>
                        <label for="shop_description">Shop Description</label>
                        @error('shop_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <textarea class="form-control @error('shop_address') is-invalid @enderror" 
                                  id="shop_address" name="shop_address" style="height: 80px" 
                                  placeholder="Shop Address">{{ old('shop_address') }}</textarea>
                        <label for="shop_address">Shop Address</label>
                        @error('shop_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="form-section">
            <h5 class="section-title"><i class="ti ti-map-pin me-2"></i>Address Information</h5>
            <div class="row g-3">
                <div class="col-12">
                    <div class="form-floating">
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" style="height: 80px" 
                                  placeholder="Address">{{ old('address') }}</textarea>
                        <label for="address">Address</label>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control @error('city') is-invalid @enderror" 
                               id="city" name="city" value="{{ old('city') }}" placeholder="City">
                        <label for="city">City</label>
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control @error('state') is-invalid @enderror" 
                               id="state" name="state" value="{{ old('state') }}" placeholder="State">
                        <label for="state">State</label>
                        @error('state')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control @error('country') is-invalid @enderror" 
                               id="country" name="country" value="{{ old('country') }}" placeholder="Country">
                        <label for="country">Country</label>
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                               id="postal_code" name="postal_code" value="{{ old('postal_code') }}" placeholder="Postal Code">
                        <label for="postal_code">Postal Code</label>
                        @error('postal_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-end gap-3 mb-4">
            <a href="{{ route('admin.vendors.index') }}" class="btn btn-light px-4">
                <i class="ti ti-x me-1"></i>Cancel
            </a>
            <button type="submit" class="btn btn-create text-white px-4" id="submitBtn">
                <span id="submitSpinner" class="spinner-border spinner-border-sm me-2 d-none"></span>
                <i class="ti ti-plus me-1"></i>Create Vendor
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Form submission handling
document.getElementById('vendorForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const submitSpinner = document.getElementById('submitSpinner');
    
    // Show loading state
    submitBtn.disabled = true;
    submitSpinner.classList.remove('d-none');
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating Vendor...';
});

// Password confirmation validation
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    
    if (password !== confirmation && confirmation.length > 0) {
        this.setCustomValidity('Passwords do not match');
        this.classList.add('is-invalid');
    } else {
        this.setCustomValidity('');
        this.classList.remove('is-invalid');
    }
});

// Auto-generate shop name from full name if empty
document.getElementById('name').addEventListener('input', function() {
    const shopNameField = document.getElementById('shop_name');
    if (!shopNameField.value) {
        shopNameField.value = this.value + "'s Shop";
    }
});
</script>
@endpush
@endsection
