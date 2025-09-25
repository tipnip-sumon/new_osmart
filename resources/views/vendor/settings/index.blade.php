@extends('admin.layouts.app')

@section('title', 'Vendor Settings')

@section('content')
<!-- Start::page-header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div>
        <h2 class="page-title fw-semibold fs-18 mb-0">Settings</h2>
        <p class="fw-medium fs-13 text-muted mb-0">Manage your store settings and preferences</p>
    </div>
</div>
<!-- End::page-header -->

<!-- Start::row -->
<div class="row">
    <div class="col-xl-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Settings Tabs -->
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Vendor Settings</div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="business-tab" data-bs-toggle="tab" data-bs-target="#business" type="button" role="tab">
                            <i class="ri-store-line me-1"></i>Business Info
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab">
                            <i class="ri-user-line me-1"></i>Account
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
                            <i class="ri-notification-line me-1"></i>Notifications
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab">
                            <i class="ri-truck-line me-1"></i>Shipping
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-4" id="settingsTabsContent">
                    <!-- Business Information Tab -->
                    <div class="tab-pane fade show active" id="business" role="tabpanel">
                        <form method="POST" action="{{ route('vendor.settings.business') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label for="shop_name" class="form-label">Shop Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('shop_name') is-invalid @enderror" id="shop_name" name="shop_name" value="{{ old('shop_name', $vendor->shop_name) }}" required>
                                            @error('shop_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-lg-6 mb-3">
                                            <label for="business_email" class="form-label">Business Email</label>
                                            <input type="email" class="form-control @error('business_email') is-invalid @enderror" id="business_email" name="business_email" value="{{ old('business_email', $vendor->business_email ?? '') }}">
                                            @error('business_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-lg-6 mb-3">
                                            <label for="business_phone" class="form-label">Business Phone</label>
                                            <input type="text" class="form-control @error('business_phone') is-invalid @enderror" id="business_phone" name="business_phone" value="{{ old('business_phone', $vendor->phone) }}">
                                            @error('business_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-lg-6 mb-3">
                                            <label for="tax_number" class="form-label">Tax ID/Number</label>
                                            <input type="text" class="form-control @error('tax_number') is-invalid @enderror" id="tax_number" name="tax_number" value="{{ old('tax_number', $vendor->tax_id) }}">
                                            @error('tax_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-lg-12 mb-3">
                                            <label for="shop_description" class="form-label">Shop Description</label>
                                            <textarea class="form-control @error('shop_description') is-invalid @enderror" id="shop_description" name="shop_description" rows="4">{{ old('shop_description', $vendor->shop_description) }}</textarea>
                                            @error('shop_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-lg-12 mb-3">
                                            <label for="business_address" class="form-label">Business Address</label>
                                            <textarea class="form-control @error('business_address') is-invalid @enderror" id="business_address" name="business_address" rows="3">{{ old('business_address', $vendor->shop_address) }}</textarea>
                                            @error('business_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="shop_logo" class="form-label">Shop Logo</label>
                                        <input type="file" class="form-control @error('shop_logo') is-invalid @enderror" id="shop_logo" name="shop_logo" accept="image/*">
                                        @error('shop_logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        
                                        @if($vendor->shop_logo)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $vendor->shop_logo) }}" alt="Current Logo" class="img-thumbnail" style="max-width: 200px;">
                                                <p class="text-muted fs-12 mt-1">Current logo</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Banking Information -->
                            <hr class="my-4">
                            <h6 class="mb-3">Banking Information</h6>
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="bank_name" class="form-label">Bank Name</label>
                                    <input type="text" class="form-control @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name" value="{{ old('bank_name', $vendor->bank_name) }}">
                                    @error('bank_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-6 mb-3">
                                    <label for="bank_account_name" class="form-label">Account Holder Name</label>
                                    <input type="text" class="form-control @error('bank_account_name') is-invalid @enderror" id="bank_account_name" name="bank_account_name" value="{{ old('bank_account_name', $vendor->bank_account_name) }}">
                                    @error('bank_account_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-6 mb-3">
                                    <label for="bank_account_number" class="form-label">Account Number</label>
                                    <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number', $vendor->bank_account_number) }}">
                                    @error('bank_account_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-6 mb-3">
                                    <label for="bank_routing_number" class="form-label">Routing/SWIFT Number</label>
                                    <input type="text" class="form-control @error('bank_routing_number') is-invalid @enderror" id="bank_routing_number" name="bank_routing_number" value="{{ old('bank_routing_number', $vendor->bank_routing_number) }}">
                                    @error('bank_routing_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>Save Business Info
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Account Settings Tab -->
                    <div class="tab-pane fade" id="account" role="tabpanel">
                        <form method="POST" action="{{ route('vendor.settings.account') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $vendor->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-6 mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $vendor->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-6 mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $vendor->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            <h6 class="mb-3">Change Password</h6>
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-4 mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password">
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-4 mb-3">
                                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>Update Account
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Notifications Tab -->
                    <div class="tab-pane fade" id="notifications" role="tabpanel">
                        <form method="POST" action="{{ route('vendor.settings.notifications') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4">
                                <h6 class="mb-3">Email Notifications</h6>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" checked>
                                    <label class="form-check-label" for="email_notifications">
                                        Enable email notifications
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="order_notifications" name="order_notifications" checked>
                                    <label class="form-check-label" for="order_notifications">
                                        New order notifications
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="product_notifications" name="product_notifications">
                                    <label class="form-check-label" for="product_notifications">
                                        Product-related notifications
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="marketing_notifications" name="marketing_notifications">
                                    <label class="form-check-label" for="marketing_notifications">
                                        Marketing and promotional emails
                                    </label>
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>Save Preferences
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Shipping Tab -->
                    <div class="tab-pane fade" id="shipping" role="tabpanel">
                        <form method="POST" action="{{ route('vendor.settings.shipping') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="processing_time" class="form-label">Processing Time (days)</label>
                                    <input type="number" class="form-control @error('processing_time') is-invalid @enderror" id="processing_time" name="processing_time" value="{{ old('processing_time', 1) }}" min="1" max="30">
                                    @error('processing_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-6 mb-3">
                                    <label for="free_shipping_threshold" class="form-label">Free Shipping Threshold ($)</label>
                                    <input type="number" step="0.01" class="form-control @error('free_shipping_threshold') is-invalid @enderror" id="free_shipping_threshold" name="free_shipping_threshold" value="{{ old('free_shipping_threshold') }}">
                                    @error('free_shipping_threshold')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-12 mb-3">
                                    <label for="shipping_policy" class="form-label">Shipping Policy</label>
                                    <textarea class="form-control @error('shipping_policy') is-invalid @enderror" id="shipping_policy" name="shipping_policy" rows="4">{{ old('shipping_policy') }}</textarea>
                                    @error('shipping_policy')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-12 mb-3">
                                    <label for="return_policy" class="form-label">Return Policy</label>
                                    <textarea class="form-control @error('return_policy') is-invalid @enderror" id="return_policy" name="return_policy" rows="4">{{ old('return_policy') }}</textarea>
                                    @error('return_policy')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>Save Shipping Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End::row -->
@endsection
