@extends('admin.layouts.app')

@section('title', 'Vendor Profile')

@section('page-header')
<h3 class="page-title">My Profile</h3>
<ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Profile</li>
</ul>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Update Profile Information</h5>
                <p class="text-muted">Update your vendor profile and shop details.</p>
            </div>
            <form method="POST" action="{{ route('vendor.profile.update') }}">
                @csrf
                @method('PUT')
                
                <div class="card-body">
                    
                    <!-- Personal Information -->
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="form-section">Personal Information</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $vendor->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $vendor->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $vendor->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Shop Information -->
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="form-section">Shop Information</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="shop_name">Shop Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('shop_name') is-invalid @enderror" 
                                       id="shop_name" name="shop_name" value="{{ old('shop_name', $vendor->shop_name) }}" required>
                                @error('shop_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="shop_description">Shop Description</label>
                                <textarea class="form-control @error('shop_description') is-invalid @enderror" 
                                          id="shop_description" name="shop_description" rows="4" 
                                          placeholder="Describe your shop and what you sell...">{{ old('shop_description', $vendor->shop_description) }}</textarea>
                                @error('shop_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="shop_address">Shop Address</label>
                                <textarea class="form-control @error('shop_address') is-invalid @enderror" 
                                          id="shop_address" name="shop_address" rows="3" 
                                          placeholder="Enter your shop physical address...">{{ old('shop_address', $vendor->shop_address) }}</textarea>
                                @error('shop_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Business Information -->
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="form-section">Business Information</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="business_license">Business License Number</label>
                                <input type="text" class="form-control @error('business_license') is-invalid @enderror" 
                                       id="business_license" name="business_license" 
                                       value="{{ old('business_license', $vendor->business_license) }}"
                                       placeholder="Enter your business license number">
                                @error('business_license')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tax_id">Tax ID / VAT Number</label>
                                <input type="text" class="form-control @error('tax_id') is-invalid @enderror" 
                                       id="tax_id" name="tax_id" 
                                       value="{{ old('tax_id', $vendor->tax_id) }}"
                                       placeholder="Enter your tax ID or VAT number">
                                @error('tax_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('vendor.dashboard') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Profile
                        </button>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.form-section {
    color: #495057;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 5px;
    border-bottom: 2px solid #e9ecef;
}
.card-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}
</style>
@endpush
