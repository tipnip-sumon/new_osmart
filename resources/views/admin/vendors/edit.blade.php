@extends('admin.layouts.app')

@section('title', 'Edit Vendor')

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

.btn-update {
    background: linear-gradient(135deg, #059669 0%, #065f46 100%);
    border: none;
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-update:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.required-field::after {
    content: ' *';
    color: #dc2626;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
}

.status-active { background: #dcfce7; color: #166534; }
.status-pending { background: #fef3c7; color: #92400e; }
.status-suspended { background: #fecaca; color: #991b1b; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h1 class="page-title fw-semibold fs-18 mb-0">Edit Vendor</h1>
            <div>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">Vendors</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="ms-auto pageheader-btn">
            <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Back to Vendors
            </a>
        </div>
    </div>

    <!-- Vendor Edit Form -->
    <form action="{{ route('admin.vendors.update', $vendor->id) }}" method="POST" id="vendorEditForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Personal Information Section -->
                <div class="form-section">
                    <h4 class="section-title">
                        <i class="bx bx-user text-primary me-2"></i>
                        Personal Information
                    </h4>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $vendor->name) }}" required>
                                <label for="name" class="required-field">Full Name</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $vendor->email) }}" required>
                                <label for="email" class="required-field">Email Address</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $vendor->phone) }}">
                                <label for="phone">Phone Number</label>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status', $vendor->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="pending" {{ old('status', $vendor->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="suspended" {{ old('status', $vendor->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                <label for="status" class="required-field">Status</label>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shop Information Section -->
                <div class="form-section">
                    <h4 class="section-title">
                        <i class="bx bx-store text-success me-2"></i>
                        Shop Information
                    </h4>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('shop_name') is-invalid @enderror" 
                                       id="shop_name" name="shop_name" value="{{ old('shop_name', $vendor->shop_name) }}" required>
                                <label for="shop_name" class="required-field">Shop Name</label>
                                @error('shop_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('business_license') is-invalid @enderror" 
                                       id="business_license" name="business_license" value="{{ old('business_license', $vendor->business_license) }}">
                                <label for="business_license">Business License</label>
                                @error('business_license')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('tax_id') is-invalid @enderror" 
                                       id="tax_id" name="tax_id" value="{{ old('tax_id', $vendor->tax_id) }}">
                                <label for="tax_id">Tax ID</label>
                                @error('tax_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control @error('commission_rate') is-invalid @enderror" 
                                       id="commission_rate" name="commission_rate" 
                                       value="{{ old('commission_rate', ($vendor->commission_rate ?? 0.05) * 100) }}" 
                                       min="0" max="100" step="0.1">
                                <label for="commission_rate">Commission Rate (%)</label>
                                @error('commission_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control @error('shop_description') is-invalid @enderror" 
                                      id="shop_description" name="shop_description" style="height: 100px">{{ old('shop_description', $vendor->shop_description) }}</textarea>
                            <label for="shop_description">Shop Description</label>
                            @error('shop_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control @error('shop_address') is-invalid @enderror" 
                                      id="shop_address" name="shop_address" style="height: 80px">{{ old('shop_address', $vendor->shop_address) }}</textarea>
                            <label for="shop_address">Shop Address</label>
                            @error('shop_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address Information Section -->
                <div class="form-section">
                    <h4 class="section-title">
                        <i class="bx bx-map text-info me-2"></i>
                        Address Information
                    </h4>
                    
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" style="height: 80px">{{ old('address', $vendor->address) }}</textarea>
                            <label for="address">Street Address</label>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city', $vendor->city) }}">
                                <label for="city">City</label>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                       id="state" name="state" value="{{ old('state', $vendor->state) }}">
                                <label for="state">State/Province</label>
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" name="postal_code" value="{{ old('postal_code', $vendor->postal_code) }}">
                                <label for="postal_code">Postal Code</label>
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                       id="country" name="country" value="{{ old('country', $vendor->country) }}">
                                <label for="country">Country</label>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Current Status -->
                <div class="form-section">
                    <h5 class="section-title">Current Status</h5>
                    <div class="text-center">
                        <span class="status-badge status-{{ $vendor->status }}">
                            {{ ucfirst($vendor->status) }}
                        </span>
                        <div class="mt-3">
                            <small class="text-muted">
                                Member since {{ $vendor->created_at->format('M d, Y') }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="form-section">
                    <h5 class="section-title">Quick Stats</h5>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-0">{{ $vendor->products_count ?? 0 }}</h4>
                                <small class="text-muted">Products</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-0">${{ number_format($vendor->total_earnings ?? 0, 2) }}</h4>
                            <small class="text-muted">Earnings</small>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-section">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-update text-white">
                            <i class="bx bx-save me-2"></i>Update Vendor
                        </button>
                        <a href="{{ route('admin.vendors.show', $vendor->id) }}" class="btn btn-outline-primary">
                            <i class="bx bx-show me-2"></i>View Profile
                        </a>
                        <a href="{{ route('admin.vendors.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('vendorEditForm');
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.focus();
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
    
    // Email validation
    const emailField = document.getElementById('email');
    emailField.addEventListener('blur', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (this.value && !emailRegex.test(this.value)) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
    
    // Phone validation
    const phoneField = document.getElementById('phone');
    phoneField.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9+\-\s()]/g, '');
    });
    
    // Commission rate conversion (convert percentage to decimal)
    form.addEventListener('submit', function() {
        const commissionField = document.getElementById('commission_rate');
        if (commissionField.value) {
            commissionField.value = commissionField.value / 100;
        }
    });
});
</script>
@endpush
