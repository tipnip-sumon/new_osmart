@extends('admin.layouts.app')

@section('title', 'Create New User')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">
                <i class="ri ri-user-add-line me-2"></i>Create New User
            </h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Real-time Validation Alerts -->
        <div id="validation-alerts"></div>

        @if ($errors->any())
            <div class="alert alert-danger alert-modern">
                <i class="ri ri-error-warning-line me-2"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-modern">
                <i class="ri ri-check-line me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" id="createUserForm">
            @csrf
            
            <div class="row">
                <div class="col-xl-8">
                    <!-- Sponsor Information -->
                    <div class="card custom-card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="ri ri-links-line me-2"></i>Sponsor Information
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info alert-modern">
                                <i class="ri ri-information-line me-2"></i>
                                <strong>MLM Network Setup:</strong> Enter sponsor details to establish network hierarchy. This is crucial for commission calculations and binary tree placement.
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Sponsor Username <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri ri-user-search-line"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control @error('sponsor_id') is-invalid @enderror" 
                                                   id="sponsor_username"
                                                   value="{{ old('sponsor_username', request('ref')) }}" 
                                                   placeholder="Enter sponsor username (e.g., osm)"
                                                   required>
                                            <!-- Hidden field for actual sponsor ID -->
                                            <input type="hidden" name="sponsor_id" id="sponsor_id" value="{{ old('sponsor_id') }}">
                                        </div>
                                        <div id="sponsor-validation" class="form-text"></div>
                                        @error('sponsor_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Position <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri ri-node-tree"></i>
                                            </span>
                                            <select class="form-select @error('position') is-invalid @enderror" name="position" id="position" required>
                                                <option value="">Select Position</option>
                                                <option value="left" {{ old('position') == 'left' ? 'selected' : '' }}>Left Side</option>
                                                <option value="right" {{ old('position') == 'right' ? 'selected' : '' }}>Right Side</option>
                                                <option value="auto" {{ old('position') == 'auto' ? 'selected' : '' }}>Auto Placement</option>
                                            </select>
                                        </div>
                                        @error('position')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Manual Placement Options (For Left/Right choice) -->
                            <div class="row" id="manualPlacementOptions" style="display: none;">
                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        <i class="ri ri-information-line me-2"></i>
                                        <strong>Manual Placement:</strong> You are placing this user in a specific position. Make sure the selected position is available.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Placement Selection</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri ri-user-location-line"></i>
                                            </span>
                                            <select class="form-select" name="placement_type" id="placement_type">
                                                <option value="direct" {{ old('placement_type', 'direct') == 'direct' ? 'selected' : '' }}>Direct Under Sponsor</option>
                                                <option value="specific" {{ old('placement_type') == 'specific' ? 'selected' : '' }}>Under Specific User</option>
                                            </select>
                                        </div>
                                        <div class="form-text">Choose where to place the new user</div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="specificUserField" style="display: none;">
                                    <div class="form-group">
                                        <label class="form-label">Specific User</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri ri-user-search-line"></i>
                                            </span>
                                            <input type="text" class="form-control" name="placement_under_user" id="placement_under_user" placeholder="Enter username">
                                            <input type="hidden" name="placement_under_user_id" id="placement_under_user_id">
                                        </div>
                                        <div id="placement-user-validation" class="form-text"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Auto Placement Info (For Auto choice) -->
                            <div class="row" id="autoPlacementInfo" style="display: none;">
                                <div class="col-12">
                                    <div class="alert alert-success">
                                        <i class="ri ri-magic-line me-2"></i>
                                        <strong>Auto Placement Enabled:</strong> The system will automatically find the best balanced position in the sponsor's network for optimal team growth.
                                    </div>
                                </div>
                            </div>

                            <!-- Position Status Display (For all position types) -->
                            <div class="row" id="positionStatusDisplay" style="display: none;">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Position Availability</label>
                                        <div class="position-status-display">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="spinner-border spinner-border-sm me-2" id="placementLoader" style="display: none;"></div>
                                                <span id="placementStatusText" class="text-muted">Select sponsor first</span>
                                            </div>
                                            <div class="mt-2" id="availablePositions" style="display: none;">
                                                <small class="text-success d-block" id="leftPositionStatus">
                                                    <i class="ri ri-checkbox-circle-line me-1"></i>Left: Available
                                                </small>
                                                <small class="text-success d-block" id="rightPositionStatus">
                                                    <i class="ri ri-checkbox-circle-line me-1"></i>Right: Available
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sponsor Information Display -->
                            <div class="sponsor-info-card" id="sponsorInfo" style="display: none;">
                                <div class="card border-success mt-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="sponsor-avatar me-3">
                                                <img src="" alt="Sponsor Avatar" id="sponsorAvatar" class="rounded-circle" width="60" height="60">
                                            </div>
                                            <div class="sponsor-details">
                                                <h6 class="mb-1 text-success" id="sponsorName">-</h6>
                                                <p class="text-muted mb-1">
                                                    <i class="ri ri-at-line me-1"></i>
                                                    <span id="sponsorUsername">-</span>
                                                </p>
                                                <div>
                                                    <span class="badge bg-success me-2" id="sponsorStatus">Verified</span>
                                                    <span class="badge bg-primary" id="sponsorRank">Bronze</span>
                                                </div>
                                            </div>
                                            <div class="ms-auto">
                                                <div class="text-end">
                                                    <small class="text-muted d-block">Total Downline</small>
                                                    <span class="h6 text-primary" id="sponsorDownline">0</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Details -->
                    <div class="card custom-card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="ri ri-user-line me-2"></i>Account Details
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Username <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri ri-at-line"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control @error('username') is-invalid @enderror" 
                                                   name="username" 
                                                   id="username"
                                                   value="{{ old('username') }}" 
                                                   placeholder="Choose a unique username"
                                                   required>
                                            <button type="button" class="btn btn-outline-secondary" id="checkUsername">
                                                <i class="ri ri-check-line"></i>
                                            </button>
                                        </div>
                                        <div id="username-validation" class="form-text"></div>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri ri-mail-line"></i>
                                            </span>
                                            <input type="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   name="email" 
                                                   id="email"
                                                   value="{{ old('email') }}" 
                                                   placeholder="Enter email address"
                                                   required>
                                            <button type="button" class="btn btn-outline-secondary" id="checkEmail">
                                                <i class="ri ri-check-line"></i>
                                            </button>
                                        </div>
                                        <div id="email-validation" class="form-text"></div>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri ri-lock-line"></i>
                                            </span>
                                            <input type="password" 
                                                   class="form-control @error('password') is-invalid @enderror" 
                                                   name="password" 
                                                   id="password"
                                                   placeholder="Create a strong password"
                                                   required>
                                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                                                <i class="ri ri-eye-line"></i>
                                            </button>
                                        </div>
                                        <div class="password-strength" id="passwordStrength"></div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri ri-lock-password-line"></i>
                                            </span>
                                            <input type="password" 
                                                   class="form-control" 
                                                   name="password_confirmation" 
                                                   id="password_confirmation"
                                                   placeholder="Confirm your password"
                                                   required>
                                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password_confirmation">
                                                <i class="ri ri-eye-line"></i>
                                            </button>
                                        </div>
                                        <div id="password-match" class="form-text"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="card custom-card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="ri ri-profile-line me-2"></i>Personal Information
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri ri-user-line"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control @error('firstname') is-invalid @enderror" 
                                                   name="firstname" 
                                                   id="firstname"
                                                   value="{{ old('firstname') }}" 
                                                   placeholder="Enter first name"
                                                   required>
                                        </div>
                                        @error('firstname')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri ri-user-line"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control @error('lastname') is-invalid @enderror" 
                                                   name="lastname" 
                                                   id="lastname"
                                                   value="{{ old('lastname') }}" 
                                                   placeholder="Enter last name"
                                                   required>
                                        </div>
                                        @error('lastname')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri ri-phone-line"></i>
                                            </span>
                                            <input type="tel" 
                                                   class="form-control @error('phone') is-invalid @enderror" 
                                                   name="phone" 
                                                   id="phone"
                                                   value="{{ old('phone') }}" 
                                                   placeholder="Enter phone number"
                                                   required>
                                            <button type="button" class="btn btn-outline-secondary" id="checkPhone">
                                                <i class="ri ri-check-line"></i>
                                            </button>
                                        </div>
                                        <div id="phone-validation" class="form-text"></div>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Country</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri ri-global-line"></i>
                                            </span>
                                            <select class="form-select @error('country') is-invalid @enderror" name="country" id="country">
                                                <option value="">Select Country</option>
                                                <option value="BD" {{ old('country') == 'BD' ? 'selected' : '' }}>Bangladesh</option>
                                                <option value="IN" {{ old('country') == 'IN' ? 'selected' : '' }}>India</option>
                                                <option value="PK" {{ old('country') == 'PK' ? 'selected' : '' }}>Pakistan</option>
                                                <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>United States</option>
                                                <option value="UK" {{ old('country') == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                                            </select>
                                        </div>
                                        @error('country')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">City</label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city') }}" placeholder="Enter city">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">State/Province</label>
                                        <input type="text" class="form-control @error('state') is-invalid @enderror" name="state" value="{{ old('state') }}" placeholder="Enter state">
                                        @error('state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Postal Code</label>
                                        <input type="text" class="form-control @error('postal_code') is-invalid @enderror" name="postal_code" value="{{ old('postal_code') }}" placeholder="Enter postal code">
                                        @error('postal_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Address</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="ri ri-map-pin-line"></i>
                                    </span>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              name="address" 
                                              rows="3" 
                                              placeholder="Enter complete address">{{ old('address') }}</textarea>
                                </div>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Marketing -->
                    <div class="card custom-card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="ri ri-shield-check-line me-2"></i>Terms & Preferences
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input @error('terms') is-invalid @enderror" 
                                           type="checkbox" 
                                           name="terms" 
                                           id="terms" 
                                           required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a> <span class="text-danger">*</span>
                                    </label>
                                    @error('terms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="marketing_consent" id="marketing_consent" value="1" {{ old('marketing_consent') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="marketing_consent">
                                        I want to receive marketing emails and promotional offers
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="col-xl-4">
                    <!-- Profile Picture -->
                    <div class="card custom-card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="ri ri-image-line me-2"></i>Profile Picture
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <div class="avatar-preview">
                                    <img id="avatarPreview" src="/assets/img/default-avatar.svg" class="rounded-circle" width="120" height="120" alt="Avatar Preview">
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar" accept="image/*">
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Upload JPG, PNG or GIF. Max size 2MB</div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Settings -->
                    <div class="card custom-card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="ri ri-settings-line me-2"></i>Account Settings
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">User Role <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" name="role" id="role" required>
                                    <option value="">Select Role</option>
                                    <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                                    <option value="vendor" {{ old('role') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                                    <option value="affiliate" {{ old('role') == 'affiliate' ? 'selected' : '' }}>Affiliate</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Account Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="email_verified" name="email_verified" value="1" {{ old('email_verified') ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_verified">
                                    Email Verified
                                </label>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="phone_verified" name="phone_verified" value="1" {{ old('phone_verified') ? 'checked' : '' }}>
                                <label class="form-check-label" for="phone_verified">
                                    Phone Verified
                                </label>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="kyc_verified" name="kyc_verified" value="1" {{ old('kyc_verified') ? 'checked' : '' }}>
                                <label class="form-check-label" for="kyc_verified">
                                    KYC Verified
                                </label>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="send_welcome_email" name="send_welcome_email" value="1" {{ old('send_welcome_email', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="send_welcome_email">
                                    Send Welcome Email
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- MLM Preview -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="ri ri-node-tree me-2"></i>MLM Preview
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <div class="tree-preview">
                                    <div class="tree-node sponsor-node">
                                        <div class="node-avatar">
                                            <i class="ri ri-user-3-line"></i>
                                        </div>
                                        <small class="d-block text-muted">Sponsor</small>
                                        <small id="sponsorNodeName">Not Selected</small>
                                    </div>
                                    <div class="tree-line"></div>
                                    <div class="tree-node new-user-node">
                                        <div class="node-avatar bg-primary text-white">
                                            <i class="ri ri-user-add-line"></i>
                                        </div>
                                        <small class="d-block text-primary">New User</small>
                                        <small id="newUserNodeName">--</small>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-3">
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Position:</span>
                                <span id="positionPreview">Not Selected</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Join Date:</span>
                                <span>{{ now()->format('M d, Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Status:</span>
                                <span id="statusPreview" class="badge bg-success">Active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row">
                <div class="col-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    <i class="ri ri-arrow-left-line me-2"></i>Back to Users
                                </a>
                                <div>
                                    <button type="button" class="btn btn-outline-primary me-2" id="previewBtn">
                                        <i class="ri ri-eye-line me-2"></i>Preview
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ri ri-save-line me-2"></i>Create User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri ri-eye-line me-2"></i>User Preview
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="previewContent">
                    <!-- Preview content will be populated here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="$('#createUserForm').submit()">
                        <i class="ri ri-save-line me-2"></i>Create User
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
/* Modern Alert Styling */
.alert-modern {
    border: none;
    border-radius: 12px;
    padding: 16px 20px;
    border-left: 4px solid;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.alert-info {
    background: rgba(13, 202, 240, 0.1);
    border-left-color: #0dcaf0;
    color: #055160;
}

.alert-success {
    background: rgba(25, 135, 84, 0.1);
    border-left-color: #198754;
    color: #0f5132;
}

.alert-danger {
    background: rgba(220, 53, 69, 0.1);
    border-left-color: #dc3545;
    color: #842029;
}

/* Form Styling */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #495057;
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #6c757d;
    font-size: 1rem;
}

.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

/* Sponsor Info Card */
.sponsor-info-card {
    animation: slideInUp 0.3s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.sponsor-avatar img {
    border: 3px solid #198754;
    object-fit: cover;
}

/* Tree Preview */
.tree-preview {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    padding: 20px 0;
}

.tree-node {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.node-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-bottom: 8px;
    border: 2px solid #e9ecef;
    background-color: #f8f9fa;
    color: #6c757d;
}

.new-user-node .node-avatar {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.tree-line {
    width: 2px;
    height: 25px;
    background-color: #dee2e6;
    margin: 5px 0;
}

.tree-node small {
    font-size: 0.75rem;
    line-height: 1.2;
}

/* Validation Text */
.form-text {
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.text-success {
    color: #198754 !important;
}

.text-danger {
    color: #dc3545 !important;
}

.text-warning {
    color: #ffc107 !important;
}

/* Button Enhancements */
.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Validation Button Styling */
.input-group .btn {
    border-left: 0;
    min-width: 45px;
}

.btn-outline-warning {
    color: #ffc107;
    border-color: #ffc107;
}

.btn-outline-warning:hover {
    background-color: #ffc107;
    color: #000;
}

.btn-success {
    background-color: #198754;
    border-color: #198754;
    color: white;
}

.btn-success:hover {
    background-color: #157347;
    border-color: #146c43;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

/* Card Enhancements */
.custom-card {
    border: 1px solid rgba(0,0,0,0.125);
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: box-shadow 0.2s ease;
}

.custom-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.card-title {
    font-weight: 600;
    color: #495057;
}

/* Password Strength */
.password-strength {
    margin-top: 0.5rem;
    height: 4px;
    background-color: #e9ecef;
    border-radius: 2px;
    overflow: hidden;
}

.password-strength-bar {
    height: 100%;
    transition: width 0.3s ease;
}

.strength-weak { background-color: #dc3545; }
.strength-medium { background-color: #ffc107; }
.strength-strong { background-color: #198754; }

/* Responsive */
@media (max-width: 768px) {
    .tree-preview {
        padding: 15px 0;
    }
    
    .node-avatar {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
}

/* Auto Placement Styles */
.position-status-display {
    padding: 12px;
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
}

#manualPlacementOptions, #autoPlacementInfo, #positionStatusDisplay {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        max-height: 300px;
        transform: translateY(0);
    }
}

.placement-indicator {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.875rem;
}

.placement-available {
    background-color: rgba(25, 135, 84, 0.1);
    color: #198754;
}

.placement-occupied {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

/* MLM Validation Results Styling */
#mlm-validation-results {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-top: 15px;
}

#mlm-validation-results h6 {
    color: #495057;
    margin-bottom: 10px;
    font-weight: 600;
}

.validation-check-item {
    padding: 8px 12px;
    margin-bottom: 8px;
    border-radius: 6px;
    border-left: 4px solid;
}

.validation-check-success {
    background-color: rgba(25, 135, 84, 0.1);
    border-left-color: #198754;
    color: #0f5132;
}

.validation-check-error {
    background-color: rgba(220, 53, 69, 0.1);
    border-left-color: #dc3545;
    color: #842029;
}

.validation-check-warning {
    background-color: rgba(255, 193, 7, 0.1);
    border-left-color: #ffc107;
    color: #664d03;
}

/* Enhanced position status */
.position-detail {
    background-color: white;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 10px;
    margin-bottom: 8px;
}

.position-detail.available {
    border-left: 4px solid #198754;
}

.position-detail.occupied {
    border-left: 4px solid #dc3545;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Real-time validation timers
    let emailTimer, phoneTimer, usernameTimer;

    // Auto-generate display name and username
    $('#firstname, #lastname').on('input', function() {
        const firstname = $('#firstname').val();
        const lastname = $('#lastname').val();
        
        // Update tree preview
        const fullName = firstname + (lastname ? ' ' + lastname : '');
        $('#newUserNodeName').text(fullName || '--');
        
        // Auto-suggest username if empty or auto-generated
        if (firstname && (!$('#username').val() || $('#username').data('auto-generated'))) {
            const suggestedUsername = (firstname + (lastname || '')).toLowerCase().replace(/[^a-z0-9]/g, '');
            $('#username').val(suggestedUsername).data('auto-generated', true);
        }
    });

    // Manual validation button handlers
    $('#checkUsername').click(function() {
        $('#username').trigger('input');
    });

    $('#checkEmail').click(function() {
        $('#email').trigger('input');
    });

    $('#checkPhone').click(function() {
        $('#phone').trigger('input');
    });

    // Real-time username validation
    $('#username').on('input', function() {
        const username = $(this).val();
        const $validation = $('#username-validation');
        const $button = $('#checkUsername');
        
        clearTimeout(usernameTimer);
        $(this).data('auto-generated', false);
        
        if (!username) {
            $validation.text('').removeClass('text-success text-danger text-warning');
            $button.removeClass('btn-success btn-danger').addClass('btn-outline-secondary');
            $button.find('i').removeClass('ri-check-line ri-close-line').addClass('ri-check-line');
            return;
        }

        // Check format
        if (!/^[a-zA-Z0-9_]+$/.test(username)) {
            $validation.html('<i class="ri ri-close-circle-line"></i> Username can only contain letters, numbers, and underscores').removeClass('text-success text-warning').addClass('text-danger');
            $button.removeClass('btn-success btn-outline-secondary').addClass('btn-danger');
            $button.find('i').removeClass('ri-check-line ri-loader-4-line').addClass('ri-close-line');
            return;
        }

        $validation.html('<i class="ri ri-loader-4-line"></i> Checking availability...').removeClass('text-success text-danger').addClass('text-warning');
        $button.removeClass('btn-success btn-danger').addClass('btn-outline-warning');
        $button.find('i').removeClass('ri-check-line ri-close-line').addClass('ri-loader-4-line');

        usernameTimer = setTimeout(function() {
            $.ajax({
                url: '/admin/users/validate-username',
                method: 'POST',
                data: {
                    username: username,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.valid) {
                        $validation.html('<i class="ri ri-check-line"></i> Username is available').removeClass('text-warning text-danger').addClass('text-success');
                        $button.removeClass('btn-outline-warning btn-danger').addClass('btn-success');
                        $button.find('i').removeClass('ri-loader-4-line ri-close-line').addClass('ri-check-line');
                    } else {
                        $validation.html('<i class="ri ri-close-circle-line"></i> ' + response.message).removeClass('text-warning text-success').addClass('text-danger');
                        $button.removeClass('btn-outline-warning btn-success').addClass('btn-danger');
                        $button.find('i').removeClass('ri-loader-4-line ri-check-line').addClass('ri-close-line');
                    }
                },
                error: function() {
                    $validation.html('<i class="ri ri-error-warning-line"></i> Error checking username').removeClass('text-warning text-success').addClass('text-danger');
                    $button.removeClass('btn-outline-warning btn-success').addClass('btn-danger');
                    $button.find('i').removeClass('ri-loader-4-line ri-check-line').addClass('ri-close-line');
                }
            });
        }, 500);
    });

    // Real-time email validation
    $('#email').on('input', function() {
        const email = $(this).val();
        const $validation = $('#email-validation');
        const $button = $('#checkEmail');
        
        clearTimeout(emailTimer);
        
        if (!email) {
            $validation.text('').removeClass('text-success text-danger text-warning');
            $button.removeClass('btn-success btn-danger').addClass('btn-outline-secondary');
            $button.find('i').removeClass('ri-check-line ri-close-line').addClass('ri-check-line');
            return;
        }

        $validation.html('<i class="ri ri-loader-4-line"></i> Checking availability...').removeClass('text-success text-danger').addClass('text-warning');
        $button.removeClass('btn-success btn-danger').addClass('btn-outline-warning');
        $button.find('i').removeClass('ri-check-line ri-close-line').addClass('ri-loader-4-line');

        emailTimer = setTimeout(function() {
            $.ajax({
                url: '/admin/users/validate-email',
                method: 'POST',
                data: {
                    email: email,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.valid) {
                        $validation.html('<i class="ri ri-check-line"></i> Email is available').removeClass('text-warning text-danger').addClass('text-success');
                        $button.removeClass('btn-outline-warning btn-danger').addClass('btn-success');
                        $button.find('i').removeClass('ri-loader-4-line ri-close-line').addClass('ri-check-line');
                    } else {
                        $validation.html('<i class="ri ri-close-circle-line"></i> ' + response.message).removeClass('text-warning text-success').addClass('text-danger');
                        $button.removeClass('btn-outline-warning btn-success').addClass('btn-danger');
                        $button.find('i').removeClass('ri-loader-4-line ri-check-line').addClass('ri-close-line');
                    }
                },
                error: function() {
                    $validation.html('<i class="ri ri-error-warning-line"></i> Error checking email').removeClass('text-warning text-success').addClass('text-danger');
                    $button.removeClass('btn-outline-warning btn-success').addClass('btn-danger');
                    $button.find('i').removeClass('ri-loader-4-line ri-check-line').addClass('ri-close-line');
                }
            });
        }, 500);
    });

    // Real-time phone validation
    $('#phone').on('input', function() {
        const phone = $(this).val();
        const $validation = $('#phone-validation');
        const $button = $('#checkPhone');
        
        clearTimeout(phoneTimer);
        
        if (!phone) {
            $validation.text('').removeClass('text-success text-danger text-warning');
            $button.removeClass('btn-success btn-danger').addClass('btn-outline-secondary');
            $button.find('i').removeClass('ri-check-line ri-close-line').addClass('ri-check-line');
            return;
        }

        $validation.html('<i class="ri ri-loader-4-line"></i> Validating phone...').removeClass('text-success text-danger').addClass('text-warning');
        $button.removeClass('btn-success btn-danger').addClass('btn-outline-warning');
        $button.find('i').removeClass('ri-check-line ri-close-line').addClass('ri-loader-4-line');

        phoneTimer = setTimeout(function() {
            $.ajax({
                url: '/admin/users/validate-mobile',
                method: 'POST',
                data: {
                    mobile: phone,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.valid) {
                        $validation.html('<i class="ri ri-check-line"></i> Phone number is valid').removeClass('text-warning text-danger').addClass('text-success');
                        $button.removeClass('btn-outline-warning btn-danger').addClass('btn-success');
                        $button.find('i').removeClass('ri-loader-4-line ri-close-line').addClass('ri-check-line');
                    } else {
                        $validation.html('<i class="ri ri-close-circle-line"></i> ' + response.message).removeClass('text-warning text-success').addClass('text-danger');
                        $button.removeClass('btn-outline-warning btn-success').addClass('btn-danger');
                        $button.find('i').removeClass('ri-loader-4-line ri-check-line').addClass('ri-close-line');
                    }
                },
                error: function() {
                    $validation.html('<i class="ri ri-error-warning-line"></i> Error validating phone').removeClass('text-warning text-success').addClass('text-danger');
                    $button.removeClass('btn-outline-warning btn-success').addClass('btn-danger');
                    $button.find('i').removeClass('ri-loader-4-line ri-check-line').addClass('ri-close-line');
                }
            });
        }, 500);
    });

    // Real-time sponsor validation
    let sponsorValidationTimeout;
    $('#sponsor_username').on('input', function() {
        const sponsorUsername = $(this).val().trim();
        const $validation = $('#sponsor-validation');
        const $sponsorIdField = $('#sponsor_id');
        
        // Clear previous timeout
        clearTimeout(sponsorValidationTimeout);
        
        if (!sponsorUsername) {
            $validation.html('').removeClass('text-success text-danger text-warning');
            $('#sponsorInfo').hide();
            $sponsorIdField.val(''); // Clear hidden sponsor ID
            return;
        }

        // Show loading state
        $validation.html('<i class="ri ri-loader-4-line"></i> Validating sponsor...').removeClass('text-success text-danger').addClass('text-warning');

        // Debounce the validation
        sponsorValidationTimeout = setTimeout(function() {
            $.ajax({
                url: '/admin/users/validate-sponsor-username',
                method: 'POST',
                data: {
                    sponsor: sponsorUsername,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.valid) {
                        $validation.html('<i class="ri ri-check-line"></i> Valid sponsor found').removeClass('text-warning text-danger').addClass('text-success');
                        $sponsorIdField.val(response.sponsor.id); // Set the actual sponsor ID
                        displaySponsorInfo(response.sponsor);
                        
                        // Check position availability if manual position is selected
                        const selectedPosition = $('#position').val();
                        if (selectedPosition === 'left' || selectedPosition === 'right') {
                            checkPositionAvailability(response.sponsor.id, selectedPosition);
                        }
                    } else {
                        $validation.html('<i class="ri ri-close-circle-line"></i> ' + (response.message || 'Sponsor not found')).removeClass('text-warning text-success').addClass('text-danger');
                        $('#sponsorInfo').hide();
                        $sponsorIdField.val(''); // Clear hidden sponsor ID
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Unable to validate sponsor';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    $validation.html('<i class="ri ri-error-warning-line"></i> ' + errorMessage).removeClass('text-warning text-success').addClass('text-danger');
                    $('#sponsorInfo').hide();
                    $sponsorIdField.val(''); // Clear hidden sponsor ID
                }
            });
        }, 500); // 500ms delay for debouncing
    });

    // Display sponsor information
    function displaySponsorInfo(sponsor) {
        $('#sponsorAvatar').attr('src', sponsor.avatar || '/assets/img/default-avatar.svg');
        $('#sponsorName').text(sponsor.name || 'N/A');
        $('#sponsorUsername').text(sponsor.username || 'N/A');
        $('#sponsorRank').text(sponsor.rank || 'Bronze');
        $('#sponsorDownline').text(sponsor.total_downline || '0');
        $('#sponsorInfo').show();
        
        // Update preview
        $('#sponsorNodeName').text(sponsor.name || sponsor.username || 'Selected Sponsor');
    }

    // Position preview update and placement handling
    $('#position').change(function() {
        const position = $(this).val();
        const $manualPlacementOptions = $('#manualPlacementOptions');
        const $autoPlacementInfo = $('#autoPlacementInfo');
        const $positionStatusDisplay = $('#positionStatusDisplay');
        
        // Hide all placement options first
        $manualPlacementOptions.hide();
        $autoPlacementInfo.hide();
        $positionStatusDisplay.hide();
        
        if (position === 'left' || position === 'right') {
            // Manual placement - show placement options and status
            $manualPlacementOptions.show();
            $positionStatusDisplay.show();
            
            const displayPosition = position.charAt(0).toUpperCase() + position.slice(1) + ' Side';
            $('#positionPreview').text(displayPosition);
            
            // Check position availability if sponsor is selected
            const sponsorId = $('#sponsor_id').val();
            if (sponsorId) {
                checkPositionAvailability(sponsorId, position);
            }
        } else if (position === 'auto') {
            // Auto placement - show info only
            $autoPlacementInfo.show();
            $('#positionPreview').text('Auto Placement (Balanced)');
        } else {
            $('#positionPreview').text('Not Selected');
        }
    });

    // Placement type change handler
    $('#placement_type').change(function() {
        const placementType = $(this).val();
        const $specificUserField = $('#specificUserField');
        
        if (placementType === 'specific') {
            $specificUserField.show();
        } else {
            $specificUserField.hide();
            $('#placement_under_user').val('');
            $('#placement_under_user_id').val('');
            $('#placement-user-validation').text('').removeClass('text-success text-danger text-warning');
        }

        // Re-validate placement if sponsor and position are selected
        const sponsorId = $('#sponsor_id').val();
        const position = $('#position').val();
        if (sponsorId && position && (position === 'left' || position === 'right')) {
            checkPositionAvailability(sponsorId, position);
        }
    });

    // Specific user validation for placement
    let placementUserTimer;
    $('#placement_under_user').on('input', function() {
        const username = $(this).val().trim();
        const $validation = $('#placement-user-validation');
        const $userIdField = $('#placement_under_user_id');
        
        clearTimeout(placementUserTimer);
        
        if (!username) {
            $validation.text('').removeClass('text-success text-danger text-warning');
            $userIdField.val('');
            return;
        }

        $validation.html('<i class="ri ri-loader-4-line"></i> Validating user...').removeClass('text-success text-danger').addClass('text-warning');

        placementUserTimer = setTimeout(function() {
            $.ajax({
                url: '/admin/users/validate-sponsor-username',
                method: 'POST',
                data: {
                    sponsor: username,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.valid) {
                        $validation.html('<i class="ri ri-check-line"></i> Valid user found').removeClass('text-warning text-danger').addClass('text-success');
                        $userIdField.val(response.sponsor.id);
                        
                        // Re-validate placement with the new specific user
                        const sponsorId = $('#sponsor_id').val();
                        const position = $('#position').val();
                        if (sponsorId && position && (position === 'left' || position === 'right')) {
                            checkPositionAvailability(sponsorId, position);
                        }
                    } else {
                        $validation.html('<i class="ri ri-close-circle-line"></i> User not found').removeClass('text-warning text-success').addClass('text-danger');
                        $userIdField.val('');
                    }
                },
                error: function() {
                    $validation.html('<i class="ri ri-error-warning-line"></i> Error validating user').removeClass('text-warning text-success').addClass('text-danger');
                    $userIdField.val('');
                }
            });
        }, 500);
    });

    // Comprehensive MLM placement validation with cross-link and hierarchy checks
    function checkPositionAvailability(sponsorId, selectedPosition) {
        const $loader = $('#placementLoader');
        const $statusText = $('#placementStatusText');
        const $availablePositions = $('#availablePositions');
        
        $loader.show();
        $statusText.text('Running comprehensive MLM validation...');
        $availablePositions.hide();

        // Get placement parameters
        const placementType = $('#placement_type').val() || 'direct';
        let uplineId = sponsorId; // Default to sponsor for direct placement
        
        // For specific placement, use the specific user if selected
        if (placementType === 'specific') {
            const specificUserId = $('#placement_under_user_id').val();
            if (specificUserId) {
                uplineId = specificUserId;
            } else {
                // If specific placement is selected but no user is chosen, show error
                $loader.hide();
                $statusText.html('<i class="ri ri-error-warning-line me-1 text-warning"></i>Please select a specific user for placement')
                          .removeClass('text-success text-danger').addClass('text-warning');
                return;
            }
        }
        
        $.ajax({
            url: '/admin/users/validate-placement',
            method: 'POST',
            data: {
                sponsor_id: sponsorId,
                upline_id: uplineId,
                position: selectedPosition,
                placement_type: placementType,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $loader.hide();
                
                if (response.valid) {
                    $statusText.html('<i class="ri ri-checkbox-circle-line me-1 text-success"></i>' + response.message)
                              .removeClass('text-danger text-warning').addClass('text-success');
                    
                    // Show detailed position availability
                    updatePositionAvailabilityDisplay(response.position_availability);
                    
                    // Show warnings if any
                    if (response.warnings && response.warnings.length > 0) {
                        displayMLMWarnings(response.warnings);
                    }
                } else {
                    $statusText.html('<i class="ri ri-error-warning-line me-1 text-danger"></i>' + response.message)
                              .removeClass('text-success text-warning').addClass('text-danger');
                }

                // Display detailed validation results
                displayValidationChecks(response.checks);
                
            },
            error: function(xhr) {
                $loader.hide();
                let errorMessage = 'Error validating placement';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                $statusText.html('<i class="ri ri-error-warning-line me-1"></i>' + errorMessage)
                          .removeClass('text-success text-warning').addClass('text-danger');
            }
        });
    }

    // Update position availability display with detailed information
    function updatePositionAvailabilityDisplay(positionData) {
        if (!positionData) return;

        const $availablePositions = $('#availablePositions');
        const $leftStatus = $('#leftPositionStatus');
        const $rightStatus = $('#rightPositionStatus');

        $availablePositions.show();

        // Update left position status with detailed info
        if (positionData.left.available) {
            $leftStatus.html('<i class="ri ri-checkbox-circle-line me-1"></i>Left: Available')
                       .removeClass('text-danger').addClass('text-success');
        } else {
            const occupant = positionData.left.occupied_by;
            $leftStatus.html(`<i class="ri ri-close-circle-line me-1"></i>Left: Occupied by ${occupant.name} (@${occupant.username}) - Joined ${occupant.joined}`)
                       .removeClass('text-success').addClass('text-danger');
        }

        // Update right position status with detailed info
        if (positionData.right.available) {
            $rightStatus.html('<i class="ri ri-checkbox-circle-line me-1"></i>Right: Available')
                        .removeClass('text-danger').addClass('text-success');
        } else {
            const occupant = positionData.right.occupied_by;
            $rightStatus.html(`<i class="ri ri-close-circle-line me-1"></i>Right: Occupied by ${occupant.name} (@${occupant.username}) - Joined ${occupant.joined}`)
                        .removeClass('text-success').addClass('text-danger');
        }
    }

    // Display MLM validation check results
    function displayValidationChecks(checks) {
        // Remove existing validation results
        $('#mlm-validation-results').remove();

        if (!checks || Object.keys(checks).length === 0) return;

        let validationHtml = '<div id="mlm-validation-results" class="mt-3"><h6>MLM Validation Results:</h6><div class="row">';

        Object.keys(checks).forEach(function(checkName) {
            const check = checks[checkName];
            const checkLabel = checkName.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            const iconClass = check.valid ? 'ri-checkbox-circle-line text-success' : 'ri-close-circle-line text-danger';
            const statusClass = check.valid ? 'text-success' : 'text-danger';

            validationHtml += `
                <div class="col-md-6 mb-2">
                    <small class="${statusClass}">
                        <i class="${iconClass} me-1"></i>${checkLabel}: ${check.message}
                    </small>
                </div>
            `;
        });

        validationHtml += '</div></div>';
        $('#positionStatusDisplay').append(validationHtml);
    }

    // Display MLM warnings
    function displayMLMWarnings(warnings) {
        if (!warnings || warnings.length === 0) return;

        let warningHtml = '<div class="alert alert-warning mt-2"><h6>MLM Placement Warnings:</h6><ul class="mb-0">';
        warnings.forEach(function(warning) {
            warningHtml += `<li>${warning}</li>`;
        });
        warningHtml += '</ul></div>';

        $('#positionStatusDisplay').append(warningHtml);
    }

    // Status preview update
    $('#status').change(function() {
        const status = $(this).val() || 'active';
        const statusColors = {
            active: 'bg-success',
            inactive: 'bg-secondary',
            pending: 'bg-warning'
        };
        $('#statusPreview').removeClass().addClass('badge ' + statusColors[status]).text(status.charAt(0).toUpperCase() + status.slice(1));
    });

    // Password toggle
    $('.toggle-password').click(function() {
        const target = $(this).data('target');
        const passwordField = $('#' + target);
        const icon = $(this).find('i');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('ri-eye-line').addClass('ri-eye-off-line');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('ri-eye-off-line').addClass('ri-eye-line');
        }
    });

    // Password strength
    $('#password').on('input', function() {
        const password = $(this).val();
        const $strength = $('#passwordStrength');
        
        if (!password) {
            $strength.html('');
            return;
        }

        let score = 0;
        if (password.length >= 8) score++;
        if (/[a-z]/.test(password)) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;

        let strength = 'Weak';
        let className = 'strength-weak';
        let width = '20%';

        if (score >= 4) {
            strength = 'Strong';
            className = 'strength-strong';
            width = '100%';
        } else if (score >= 3) {
            strength = 'Medium';
            className = 'strength-medium';
            width = '60%';
        }

        $strength.html(`
            <div class="password-strength-bar ${className}" style="width: ${width}"></div>
            <small class="text-muted mt-1 d-block">Password strength: ${strength}</small>
        `);
    });

    // Password confirmation match
    $('#password_confirmation').on('input', function() {
        const password = $('#password').val();
        const confirmation = $(this).val();
        const $match = $('#password-match');

        if (!confirmation) {
            $match.text('').removeClass('text-success text-danger');
            return;
        }

        if (password === confirmation) {
            $match.html('<i class="ri ri-check-line"></i> Passwords match').removeClass('text-danger').addClass('text-success');
        } else {
            $match.html('<i class="ri ri-close-circle-line"></i> Passwords do not match').removeClass('text-success').addClass('text-danger');
        }
    });

    // Avatar preview
    $('#avatar').change(function() {
        const file = this.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                $(this).val('');
                return;
            }

            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Only JPG, PNG, and GIF files are allowed');
                $(this).val('');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                $('#avatarPreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // Preview functionality
    $('#previewBtn').click(function() {
        // Validate required fields
        const requiredFields = ['firstname', 'lastname', 'username', 'email', 'password', 'role', 'sponsor_username'];
        let isValid = true;
        let missingFields = [];

        requiredFields.forEach(function(field) {
            if (!$('#' + field).val()) {
                isValid = false;
                missingFields.push($('label[for="' + field + '"]').text().replace('*', '').trim());
            }
        });

        if (!isValid) {
            alert('Please fill in the following required fields:\n- ' + missingFields.join('\n- '));
            return;
        }

        // Generate preview
        let previewHtml = `
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <img src="${$('#avatarPreview').attr('src')}" class="rounded-circle mb-3" width="100" height="100">
                            <h6>${$('#firstname').val()} ${$('#lastname').val()}</h6>
                            <small class="text-muted">@${$('#username').val()}</small><br>
                            <small class="text-muted">${$('#role').val()}</small>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><i class="ri ri-user-line me-2"></i>Personal Information</h6>
                                    <table class="table table-sm">
                                        <tr><td><strong>Email:</strong></td><td>${$('#email').val()}</td></tr>
                                        <tr><td><strong>Phone:</strong></td><td>${$('#phone').val() || 'N/A'}</td></tr>
                                        <tr><td><strong>Country:</strong></td><td>${$('#country').val() || 'N/A'}</td></tr>
                                        <tr><td><strong>City:</strong></td><td>${$('#city').val() || 'N/A'}</td></tr>
                                        <tr><td><strong>Status:</strong></td><td>${$('#status').val()}</td></tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="ri ri-links-line me-2"></i>MLM Information</h6>
                                    <table class="table table-sm">
                                        <tr><td><strong>Sponsor:</strong></td><td>${$('#sponsorName').text() !== '-' ? $('#sponsorName').text() : 'Not Set'}</td></tr>
                                        <tr><td><strong>Position:</strong></td><td>${$('#position').val() || 'Not Selected'}</td></tr>
                                        <tr><td><strong>Email Verified:</strong></td><td>${$('#email_verified').is(':checked') ? 'Yes' : 'No'}</td></tr>
                                        <tr><td><strong>Phone Verified:</strong></td><td>${$('#phone_verified').is(':checked') ? 'Yes' : 'No'}</td></tr>
                                        <tr><td><strong>KYC Verified:</strong></td><td>${$('#kyc_verified').is(':checked') ? 'Yes' : 'No'}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('#previewContent').html(previewHtml);
        $('#previewModal').modal('show');
    });

    // Form submission
    $('#createUserForm').submit(function(e) {
        const password = $('#password').val();
        const confirmPassword = $('#password_confirmation').val();
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match!');
            return false;
        }

        if (!$('#terms').is(':checked')) {
            e.preventDefault();
            alert('Please agree to the Terms of Service and Privacy Policy!');
            return false;
        }

        // Show loading state
        $('#submitBtn').prop('disabled', true).html('<i class="ri ri-loader-4-line"></i> Creating User...');
    });

    // Initialize
    $('#status').trigger('change');
    $('#position').trigger('change');
    $('#placement_type').trigger('change');
    
    // Handle pre-selected values on page load
    const selectedPosition = $('#position').val();
    if (selectedPosition === 'left' || selectedPosition === 'right') {
        $('#manualPlacementOptions').show();
        $('#positionStatusDisplay').show();
        const sponsorId = $('#sponsor_id').val();
        if (sponsorId) {
            checkPositionAvailability(sponsorId, selectedPosition);
        }
    } else if (selectedPosition === 'auto') {
        $('#autoPlacementInfo').show();
    }
});
</script>
@endpush
