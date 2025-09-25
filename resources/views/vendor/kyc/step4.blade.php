@extends('admin.layouts.app')

@section('title', 'Vendor KYC Step 4 - Address Information')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Vendor KYC Verification - Step 4</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.kyc.index') }}">KYC</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Address Information</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Progress Bar -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0">Step 4 of 6: Address Information</h6>
                            <span class="badge bg-primary">{{ number_format($kyc->completion_percentage, 2) }}% Complete</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $kyc->completion_percentage }}%" aria-valuenow="{{ $kyc->completion_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step Navigation -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="step-navigation d-flex align-items-center">
                                <div class="step">
                                    <div class="step-circle bg-success text-white">✓</div>
                                    <span class="step-label">Business Info</span>
                                </div>
                                <div class="step-arrow">→</div>
                                <div class="step">
                                    <div class="step-circle bg-success text-white">✓</div>
                                    <span class="step-label">Owner Info</span>
                                </div>
                                <div class="step-arrow">→</div>
                                <div class="step">
                                    <div class="step-circle bg-success text-white">✓</div>
                                    <span class="step-label">Document Info</span>
                                </div>
                                <div class="step-arrow">→</div>
                                <div class="step active">
                                    <div class="step-circle bg-primary text-white">4</div>
                                    <span class="step-label">Address</span>
                                </div>
                                <div class="step-arrow text-muted">→</div>
                                <div class="step">
                                    <div class="step-circle bg-light text-muted">5</div>
                                    <span class="step-label text-muted">Documents</span>
                                </div>
                                <div class="step-arrow text-muted">→</div>
                                <div class="step">
                                    <div class="step-circle bg-light text-muted">6</div>
                                    <span class="step-label text-muted">Review</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Address Information</div>
                    </div>
                    <div class="card-body">
                        <form id="vendorKycStep4Form" action="{{ route('vendor.kyc.save-step', 4) }}" method="POST">
                            @csrf

                            <!-- Business Present Address -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">Business Present Address</h5>
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label for="business_present_address" class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="business_present_address" name="business_present_address" 
                                              rows="3" required>{{ old('business_present_address', $kyc->business_present_address) }}</textarea>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="business_present_country" class="form-label">Country <span class="text-danger">*</span></label>
                                    <select class="form-control" id="business_present_country" name="business_present_country" required>
                                        <option value="">Select Country</option>
                                        <option value="Bangladesh" {{ old('business_present_country', $kyc->business_present_country) == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="business_present_district" class="form-label">District <span class="text-danger">*</span></label>
                                    <select class="form-control" id="business_present_district" name="business_present_district" required>
                                        <option value="">Select District</option>
                                        @if($locationData)
                                            @foreach($locationData as $district)
                                                <option value="{{ $district['name'] }}" 
                                                    {{ old('business_present_district', $kyc->business_present_district) == $district['name'] ? 'selected' : '' }}>
                                                    {{ $district['name'] }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="business_present_upazila" class="form-label">Upazila <span class="text-danger">*</span></label>
                                    <select class="form-control" id="business_present_upazila" name="business_present_upazila" required>
                                        <option value="">Select Upazila</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="business_present_union_ward" class="form-label">Union/Ward <span class="text-danger">*</span></label>
                                    <select class="form-control" id="business_present_union_ward" name="business_present_union_ward" required>
                                        <option value="">Select Union/Ward</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="business_present_post_office" class="form-label">Post Office</label>
                                    <input type="text" class="form-control" id="business_present_post_office" name="business_present_post_office" 
                                           value="{{ old('business_present_post_office', $kyc->business_present_post_office) }}">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="business_present_postal_code" class="form-label">Postal Code</label>
                                    <input type="text" class="form-control" id="business_present_postal_code" name="business_present_postal_code" 
                                           value="{{ old('business_present_postal_code', $kyc->business_present_postal_code) }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Business Permanent Address -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="text-primary mb-0">Business Permanent Address</h5>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="same_as_business_present_address" 
                                                   name="same_as_business_present_address" value="1"
                                                   {{ old('same_as_business_present_address', $kyc->same_as_business_present_address) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="same_as_business_present_address">
                                                Same as Present Address
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div id="business_permanent_fields">
                                    <div class="col-md-12 mb-3">
                                        <label for="business_permanent_address" class="form-label">Address</label>
                                        <textarea class="form-control" id="business_permanent_address" name="business_permanent_address" 
                                                  rows="3">{{ old('business_permanent_address', $kyc->business_permanent_address) }}</textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="business_permanent_country" class="form-label">Country</label>
                                        <select class="form-control" id="business_permanent_country" name="business_permanent_country">
                                            <option value="">Select Country</option>
                                            <option value="Bangladesh" {{ old('business_permanent_country', $kyc->business_permanent_country) == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="business_permanent_district" class="form-label">District</label>
                                        <select class="form-control" id="business_permanent_district" name="business_permanent_district">
                                            <option value="">Select District</option>
                                            @if($locationData)
                                                @foreach($locationData as $district)
                                                    <option value="{{ $district['name'] }}" 
                                                        {{ old('business_permanent_district', $kyc->business_permanent_district) == $district['name'] ? 'selected' : '' }}>
                                                        {{ $district['name'] }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="business_permanent_upazila" class="form-label">Upazila</label>
                                        <select class="form-control" id="business_permanent_upazila" name="business_permanent_upazila">
                                            <option value="">Select Upazila</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="business_permanent_union_ward" class="form-label">Union/Ward</label>
                                        <select class="form-control" id="business_permanent_union_ward" name="business_permanent_union_ward">
                                            <option value="">Select Union/Ward</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="business_permanent_post_office" class="form-label">Post Office</label>
                                        <input type="text" class="form-control" id="business_permanent_post_office" name="business_permanent_post_office" 
                                               value="{{ old('business_permanent_post_office', $kyc->business_permanent_post_office) }}">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="business_permanent_postal_code" class="form-label">Postal Code</label>
                                        <input type="text" class="form-control" id="business_permanent_postal_code" name="business_permanent_postal_code" 
                                               value="{{ old('business_permanent_postal_code', $kyc->business_permanent_postal_code) }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Owner Present Address -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">Owner Present Address</h5>
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label for="owner_present_address" class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="owner_present_address" name="owner_present_address" 
                                              rows="3" required>{{ old('owner_present_address', $kyc->owner_present_address) }}</textarea>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="owner_present_country" class="form-label">Country <span class="text-danger">*</span></label>
                                    <select class="form-control" id="owner_present_country" name="owner_present_country" required>
                                        <option value="">Select Country</option>
                                        <option value="Bangladesh" {{ old('owner_present_country', $kyc->owner_present_country) == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="owner_present_district" class="form-label">District <span class="text-danger">*</span></label>
                                    <select class="form-control" id="owner_present_district" name="owner_present_district" required>
                                        <option value="">Select District</option>
                                        @if($locationData)
                                            @foreach($locationData as $district)
                                                <option value="{{ $district['name'] }}" 
                                                    {{ old('owner_present_district', $kyc->owner_present_district) == $district['name'] ? 'selected' : '' }}>
                                                    {{ $district['name'] }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="owner_present_upazila" class="form-label">Upazila <span class="text-danger">*</span></label>
                                    <select class="form-control" id="owner_present_upazila" name="owner_present_upazila" required>
                                        <option value="">Select Upazila</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="owner_present_union_ward" class="form-label">Union/Ward <span class="text-danger">*</span></label>
                                    <select class="form-control" id="owner_present_union_ward" name="owner_present_union_ward" required>
                                        <option value="">Select Union/Ward</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="owner_present_post_office" class="form-label">Post Office</label>
                                    <input type="text" class="form-control" id="owner_present_post_office" name="owner_present_post_office" 
                                           value="{{ old('owner_present_post_office', $kyc->owner_present_post_office) }}">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="owner_present_postal_code" class="form-label">Postal Code</label>
                                    <input type="text" class="form-control" id="owner_present_postal_code" name="owner_present_postal_code" 
                                           value="{{ old('owner_present_postal_code', $kyc->owner_present_postal_code) }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Owner Permanent Address -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="text-primary mb-0">Owner Permanent Address</h5>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="same_as_owner_present_address" 
                                                   name="same_as_owner_present_address" value="1"
                                                   {{ old('same_as_owner_present_address', $kyc->same_as_owner_present_address) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="same_as_owner_present_address">
                                                Same as Present Address
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div id="owner_permanent_fields">
                                    <div class="col-md-12 mb-3">
                                        <label for="owner_permanent_address" class="form-label">Address</label>
                                        <textarea class="form-control" id="owner_permanent_address" name="owner_permanent_address" 
                                                  rows="3">{{ old('owner_permanent_address', $kyc->owner_permanent_address) }}</textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="owner_permanent_country" class="form-label">Country</label>
                                        <select class="form-control" id="owner_permanent_country" name="owner_permanent_country">
                                            <option value="">Select Country</option>
                                            <option value="Bangladesh" {{ old('owner_permanent_country', $kyc->owner_permanent_country) == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="owner_permanent_district" class="form-label">District</label>
                                        <select class="form-control" id="owner_permanent_district" name="owner_permanent_district">
                                            <option value="">Select District</option>
                                            @if($locationData)
                                                @foreach($locationData as $district)
                                                    <option value="{{ $district['name'] }}" 
                                                        {{ old('owner_permanent_district', $kyc->owner_permanent_district) == $district['name'] ? 'selected' : '' }}>
                                                        {{ $district['name'] }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="owner_permanent_upazila" class="form-label">Upazila</label>
                                        <select class="form-control" id="owner_permanent_upazila" name="owner_permanent_upazila">
                                            <option value="">Select Upazila</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="owner_permanent_union_ward" class="form-label">Union/Ward</label>
                                        <select class="form-control" id="owner_permanent_union_ward" name="owner_permanent_union_ward">
                                            <option value="">Select Union/Ward</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="owner_permanent_post_office" class="form-label">Post Office</label>
                                        <input type="text" class="form-control" id="owner_permanent_post_office" name="owner_permanent_post_office" 
                                               value="{{ old('owner_permanent_post_office', $kyc->owner_permanent_post_office) }}">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="owner_permanent_postal_code" class="form-label">Postal Code</label>
                                        <input type="text" class="form-control" id="owner_permanent_postal_code" name="owner_permanent_postal_code" 
                                               value="{{ old('owner_permanent_postal_code', $kyc->owner_permanent_postal_code) }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">Contact Information</h5>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                           value="{{ old('phone_number', $kyc->phone_number) }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="alternative_phone" class="form-label">Alternative Phone</label>
                                    <input type="tel" class="form-control" id="alternative_phone" name="alternative_phone" 
                                           value="{{ old('alternative_phone', $kyc->alternative_phone) }}">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email_address" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email_address" name="email_address" 
                                           value="{{ old('email_address', $kyc->email_address) }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="business_phone" class="form-label">Business Phone</label>
                                    <input type="tel" class="form-control" id="business_phone" name="business_phone" 
                                           value="{{ old('business_phone', $kyc->business_phone) }}">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="business_email" class="form-label">Business Email</label>
                                    <input type="email" class="form-control" id="business_email" name="business_email" 
                                           value="{{ old('business_email', $kyc->business_email) }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Emergency Contact -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">Emergency Contact Information</h5>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="emergency_contact_name" class="form-label">Emergency Contact Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" 
                                           value="{{ old('emergency_contact_name', $kyc->emergency_contact_name) }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="emergency_contact_relationship" class="form-label">Relationship <span class="text-danger">*</span></label>
                                    <select class="form-control" id="emergency_contact_relationship" name="emergency_contact_relationship" required>
                                        <option value="">Select Relationship</option>
                                        <option value="Father" {{ old('emergency_contact_relationship', $kyc->emergency_contact_relationship) == 'Father' ? 'selected' : '' }}>Father</option>
                                        <option value="Mother" {{ old('emergency_contact_relationship', $kyc->emergency_contact_relationship) == 'Mother' ? 'selected' : '' }}>Mother</option>
                                        <option value="Spouse" {{ old('emergency_contact_relationship', $kyc->emergency_contact_relationship) == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                        <option value="Brother" {{ old('emergency_contact_relationship', $kyc->emergency_contact_relationship) == 'Brother' ? 'selected' : '' }}>Brother</option>
                                        <option value="Sister" {{ old('emergency_contact_relationship', $kyc->emergency_contact_relationship) == 'Sister' ? 'selected' : '' }}>Sister</option>
                                        <option value="Son" {{ old('emergency_contact_relationship', $kyc->emergency_contact_relationship) == 'Son' ? 'selected' : '' }}>Son</option>
                                        <option value="Daughter" {{ old('emergency_contact_relationship', $kyc->emergency_contact_relationship) == 'Daughter' ? 'selected' : '' }}>Daughter</option>
                                        <option value="Friend" {{ old('emergency_contact_relationship', $kyc->emergency_contact_relationship) == 'Friend' ? 'selected' : '' }}>Friend</option>
                                        <option value="Colleague" {{ old('emergency_contact_relationship', $kyc->emergency_contact_relationship) == 'Colleague' ? 'selected' : '' }}>Colleague</option>
                                        <option value="Other" {{ old('emergency_contact_relationship', $kyc->emergency_contact_relationship) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" 
                                           value="{{ old('emergency_contact_phone', $kyc->emergency_contact_phone) }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="emergency_contact_address" class="form-label">Emergency Contact Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="emergency_contact_address" name="emergency_contact_address" 
                                              rows="3" required>{{ old('emergency_contact_address', $kyc->emergency_contact_address) }}</textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Bank Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">Bank Information</h5>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="bank_account_holder_name" class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="bank_account_holder_name" name="bank_account_holder_name" 
                                           value="{{ old('bank_account_holder_name', $kyc->bank_account_holder_name) }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="bank_name" name="bank_name" 
                                           value="{{ old('bank_name', $kyc->bank_name) }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="bank_branch" class="form-label">Bank Branch <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="bank_branch" name="bank_branch" 
                                           value="{{ old('bank_branch', $kyc->bank_branch) }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="bank_account_number" class="form-label">Account Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" 
                                           value="{{ old('bank_account_number', $kyc->bank_account_number) }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="bank_routing_number" class="form-label">Routing Number</label>
                                    <input type="text" class="form-control" id="bank_routing_number" name="bank_routing_number" 
                                           value="{{ old('bank_routing_number', $kyc->bank_routing_number) }}">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="bank_account_type" class="form-label">Account Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="bank_account_type" name="bank_account_type" required>
                                        <option value="">Select Account Type</option>
                                        <option value="savings" {{ old('bank_account_type', $kyc->bank_account_type) == 'savings' ? 'selected' : '' }}>Savings</option>
                                        <option value="current" {{ old('bank_account_type', $kyc->bank_account_type) == 'current' ? 'selected' : '' }}>Current</option>
                                        <option value="business" {{ old('bank_account_type', $kyc->bank_account_type) == 'business' ? 'selected' : '' }}>Business</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('vendor.kyc.step', 3) }}" class="btn btn-secondary">
                                            <i class="fa fa-arrow-left me-1"></i> Previous Step
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            Save & Continue <i class="fa fa-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Card -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card border-info">
                    <div class="card-body">
                        <h6 class="text-info"><i class="fe fe-info"></i> Important Information</h6>
                        <ul class="mb-0">
                            <li>Provide accurate address information for both business and owner.</li>
                            <li>Business address should match your business registration documents.</li>
                            <li>Owner address should match your identity document (NID/Passport).</li>
                            <li>Contact information will be used for verification purposes.</li>
                            <li>Emergency contact should be someone who can be reached in case of emergency.</li>
                            <li>Bank information will be used for payment processing and verification.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.step-navigation {
    gap: 1rem;
}
.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    min-width: 80px;
}
.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
}
.step-label {
    font-size: 12px;
    white-space: nowrap;
}

.step-arrow {
    margin: 0 10px;
    font-size: 18px;
    font-weight: bold;
}

@media (max-width: 768px) {
    .step-navigation {
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .step-arrow {
        display: none;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('vendorKycStep4Form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Location data
    const locationData = @json($locationData ?? []);
    
    // Form submission
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving...';
    });

    // Address copying functionality
    const businessSameCheckbox = document.getElementById('same_as_business_present_address');
    const ownerSameCheckbox = document.getElementById('same_as_owner_present_address');
    const businessPermanentFields = document.getElementById('business_permanent_fields');
    const ownerPermanentFields = document.getElementById('owner_permanent_fields');

    // Business address copying
    businessSameCheckbox.addEventListener('change', function() {
        if (this.checked) {
            copyBusinessAddress();
            businessPermanentFields.style.display = 'none';
        } else {
            businessPermanentFields.style.display = 'block';
        }
    });

    // Owner address copying
    ownerSameCheckbox.addEventListener('change', function() {
        if (this.checked) {
            copyOwnerAddress();
            ownerPermanentFields.style.display = 'none';
        } else {
            ownerPermanentFields.style.display = 'block';
        }
    });

    // Initialize display
    if (businessSameCheckbox.checked) {
        businessPermanentFields.style.display = 'none';
    }
    if (ownerSameCheckbox.checked) {
        ownerPermanentFields.style.display = 'none';
    }

    // Dynamic location functionality
    setupLocationDropdowns('business_present');
    setupLocationDropdowns('business_permanent');
    setupLocationDropdowns('owner_present');
    setupLocationDropdowns('owner_permanent');

    function setupLocationDropdowns(prefix) {
        const districtSelect = document.getElementById(`${prefix}_district`);
        const upazilaSelect = document.getElementById(`${prefix}_upazila`);
        const unionSelect = document.getElementById(`${prefix}_union_ward`);

        if (!districtSelect || !upazilaSelect || !unionSelect) return;

        // District change handler
        districtSelect.addEventListener('change', function() {
            const selectedDistrict = this.value;
            populateUpazilas(selectedDistrict, upazilaSelect, unionSelect);
        });

        // Upazila change handler
        upazilaSelect.addEventListener('change', function() {
            const selectedDistrict = districtSelect.value;
            const selectedUpazila = this.value;
            populateUnions(selectedDistrict, selectedUpazila, unionSelect);
        });

        // Initialize if values are pre-selected
        if (districtSelect.value) {
            populateUpazilas(districtSelect.value, upazilaSelect, unionSelect);
            
            setTimeout(() => {
                if (upazilaSelect.value) {
                    populateUnions(districtSelect.value, upazilaSelect.value, unionSelect);
                }
            }, 100);
        }
    }

    function populateUpazilas(districtName, upazilaSelect, unionSelect) {
        // Clear existing options
        upazilaSelect.innerHTML = '<option value="">Select Upazila</option>';
        unionSelect.innerHTML = '<option value="">Select Union/Ward</option>';

        if (!districtName) return;

        const district = locationData.find(d => d.name === districtName);
        if (district && district.upazilas) {
            district.upazilas.forEach(upazila => {
                const option = document.createElement('option');
                option.value = upazila.name;
                option.textContent = upazila.name;
                
                // Preserve selected value
                const currentValue = upazilaSelect.getAttribute('data-selected') || '';
                if (upazila.name === currentValue) {
                    option.selected = true;
                }
                
                upazilaSelect.appendChild(option);
            });
        }
    }

    function populateUnions(districtName, upazilaName, unionSelect) {
        // Clear existing options
        unionSelect.innerHTML = '<option value="">Select Union/Ward</option>';

        if (!districtName || !upazilaName) return;

        const district = locationData.find(d => d.name === districtName);
        if (district) {
            const upazila = district.upazilas.find(u => u.name === upazilaName);
            if (upazila && upazila.unions) {
                upazila.unions.forEach(union => {
                    const option = document.createElement('option');
                    option.value = union;
                    option.textContent = union;
                    
                    // Preserve selected value
                    const currentValue = unionSelect.getAttribute('data-selected') || '';
                    if (union === currentValue) {
                        option.selected = true;
                    }
                    
                    unionSelect.appendChild(option);
                });
            }
        }
    }

    function copyBusinessAddress() {
        // Copy text fields
        document.getElementById('business_permanent_address').value = document.getElementById('business_present_address').value;
        document.getElementById('business_permanent_post_office').value = document.getElementById('business_present_post_office').value;
        document.getElementById('business_permanent_postal_code').value = document.getElementById('business_present_postal_code').value;

        // Copy select fields
        const presentCountry = document.getElementById('business_present_country');
        const permanentCountry = document.getElementById('business_permanent_country');
        permanentCountry.value = presentCountry.value;

        const presentDistrict = document.getElementById('business_present_district');
        const permanentDistrict = document.getElementById('business_permanent_district');
        permanentDistrict.value = presentDistrict.value;

        // Populate and set upazila
        const presentUpazila = document.getElementById('business_present_upazila');
        const permanentUpazila = document.getElementById('business_permanent_upazila');
        populateUpazilas(presentDistrict.value, permanentUpazila, document.getElementById('business_permanent_union_ward'));
        setTimeout(() => {
            permanentUpazila.value = presentUpazila.value;
            
            // Populate and set union
            const presentUnion = document.getElementById('business_present_union_ward');
            const permanentUnion = document.getElementById('business_permanent_union_ward');
            populateUnions(presentDistrict.value, presentUpazila.value, permanentUnion);
            setTimeout(() => {
                permanentUnion.value = presentUnion.value;
            }, 100);
        }, 100);
    }

    function copyOwnerAddress() {
        // Copy text fields
        document.getElementById('owner_permanent_address').value = document.getElementById('owner_present_address').value;
        document.getElementById('owner_permanent_post_office').value = document.getElementById('owner_present_post_office').value;
        document.getElementById('owner_permanent_postal_code').value = document.getElementById('owner_present_postal_code').value;

        // Copy select fields
        const presentCountry = document.getElementById('owner_present_country');
        const permanentCountry = document.getElementById('owner_permanent_country');
        permanentCountry.value = presentCountry.value;

        const presentDistrict = document.getElementById('owner_present_district');
        const permanentDistrict = document.getElementById('owner_permanent_district');
        permanentDistrict.value = presentDistrict.value;

        // Populate and set upazila
        const presentUpazila = document.getElementById('owner_present_upazila');
        const permanentUpazila = document.getElementById('owner_permanent_upazila');
        populateUpazilas(presentDistrict.value, permanentUpazila, document.getElementById('owner_permanent_union_ward'));
        setTimeout(() => {
            permanentUpazila.value = presentUpazila.value;
            
            // Populate and set union
            const presentUnion = document.getElementById('owner_present_union_ward');
            const permanentUnion = document.getElementById('owner_permanent_union_ward');
            populateUnions(presentDistrict.value, presentUpazila.value, permanentUnion);
            setTimeout(() => {
                permanentUnion.value = presentUnion.value;
            }, 100);
        }, 100);
    }

    // Set data attributes for preserving selected values
    @if(old('business_present_upazila', $kyc->business_present_upazila))
        document.getElementById('business_present_upazila').setAttribute('data-selected', '{{ old('business_present_upazila', $kyc->business_present_upazila) }}');
    @endif
    @if(old('business_present_union_ward', $kyc->business_present_union_ward))
        document.getElementById('business_present_union_ward').setAttribute('data-selected', '{{ old('business_present_union_ward', $kyc->business_present_union_ward) }}');
    @endif
    @if(old('business_permanent_upazila', $kyc->business_permanent_upazila))
        document.getElementById('business_permanent_upazila').setAttribute('data-selected', '{{ old('business_permanent_upazila', $kyc->business_permanent_upazila) }}');
    @endif
    @if(old('business_permanent_union_ward', $kyc->business_permanent_union_ward))
        document.getElementById('business_permanent_union_ward').setAttribute('data-selected', '{{ old('business_permanent_union_ward', $kyc->business_permanent_union_ward) }}');
    @endif
    @if(old('owner_present_upazila', $kyc->owner_present_upazila))
        document.getElementById('owner_present_upazila').setAttribute('data-selected', '{{ old('owner_present_upazila', $kyc->owner_present_upazila) }}');
    @endif
    @if(old('owner_present_union_ward', $kyc->owner_present_union_ward))
        document.getElementById('owner_present_union_ward').setAttribute('data-selected', '{{ old('owner_present_union_ward', $kyc->owner_present_union_ward) }}');
    @endif
    @if(old('owner_permanent_upazila', $kyc->owner_permanent_upazila))
        document.getElementById('owner_permanent_upazila').setAttribute('data-selected', '{{ old('owner_permanent_upazila', $kyc->owner_permanent_upazila) }}');
    @endif
    @if(old('owner_permanent_union_ward', $kyc->owner_permanent_union_ward))
        document.getElementById('owner_permanent_union_ward').setAttribute('data-selected', '{{ old('owner_permanent_union_ward', $kyc->owner_permanent_union_ward) }}');
    @endif

    // Initialize dropdowns with preserved values after a slight delay
    setTimeout(function() {
        // Initialize business present
        const businessPresentDistrict = document.getElementById('business_present_district').value;
        if (businessPresentDistrict) {
            populateUpazilas(businessPresentDistrict, 
                document.getElementById('business_present_upazila'), 
                document.getElementById('business_present_union_ward'));
                
            setTimeout(function() {
                const businessPresentUpazila = document.getElementById('business_present_upazila');
                const savedUpazila = businessPresentUpazila.getAttribute('data-selected');
                if (savedUpazila) {
                    businessPresentUpazila.value = savedUpazila;
                    
                    populateUnions(businessPresentDistrict, savedUpazila, 
                        document.getElementById('business_present_union_ward'));
                    
                    setTimeout(function() {
                        const businessPresentUnion = document.getElementById('business_present_union_ward');
                        const savedUnion = businessPresentUnion.getAttribute('data-selected');
                        if (savedUnion) {
                            businessPresentUnion.value = savedUnion;
                        }
                    }, 100);
                }
            }, 100);
        }

        // Initialize business permanent
        const businessPermanentDistrict = document.getElementById('business_permanent_district').value;
        if (businessPermanentDistrict) {
            populateUpazilas(businessPermanentDistrict, 
                document.getElementById('business_permanent_upazila'), 
                document.getElementById('business_permanent_union_ward'));
                
            setTimeout(function() {
                const businessPermanentUpazila = document.getElementById('business_permanent_upazila');
                const savedUpazila = businessPermanentUpazila.getAttribute('data-selected');
                if (savedUpazila) {
                    businessPermanentUpazila.value = savedUpazila;
                    
                    populateUnions(businessPermanentDistrict, savedUpazila, 
                        document.getElementById('business_permanent_union_ward'));
                    
                    setTimeout(function() {
                        const businessPermanentUnion = document.getElementById('business_permanent_union_ward');
                        const savedUnion = businessPermanentUnion.getAttribute('data-selected');
                        if (savedUnion) {
                            businessPermanentUnion.value = savedUnion;
                        }
                    }, 100);
                }
            }, 100);
        }

        // Initialize owner present
        const ownerPresentDistrict = document.getElementById('owner_present_district').value;
        if (ownerPresentDistrict) {
            populateUpazilas(ownerPresentDistrict, 
                document.getElementById('owner_present_upazila'), 
                document.getElementById('owner_present_union_ward'));
                
            setTimeout(function() {
                const ownerPresentUpazila = document.getElementById('owner_present_upazila');
                const savedUpazila = ownerPresentUpazila.getAttribute('data-selected');
                if (savedUpazila) {
                    ownerPresentUpazila.value = savedUpazila;
                    
                    populateUnions(ownerPresentDistrict, savedUpazila, 
                        document.getElementById('owner_present_union_ward'));
                    
                    setTimeout(function() {
                        const ownerPresentUnion = document.getElementById('owner_present_union_ward');
                        const savedUnion = ownerPresentUnion.getAttribute('data-selected');
                        if (savedUnion) {
                            ownerPresentUnion.value = savedUnion;
                        }
                    }, 100);
                }
            }, 100);
        }

        // Initialize owner permanent
        const ownerPermanentDistrict = document.getElementById('owner_permanent_district').value;
        if (ownerPermanentDistrict) {
            populateUpazilas(ownerPermanentDistrict, 
                document.getElementById('owner_permanent_upazila'), 
                document.getElementById('owner_permanent_union_ward'));
                
            setTimeout(function() {
                const ownerPermanentUpazila = document.getElementById('owner_permanent_upazila');
                const savedUpazila = ownerPermanentUpazila.getAttribute('data-selected');
                if (savedUpazila) {
                    ownerPermanentUpazila.value = savedUpazila;
                    
                    populateUnions(ownerPermanentDistrict, savedUpazila, 
                        document.getElementById('owner_permanent_union_ward'));
                    
                    setTimeout(function() {
                        const ownerPermanentUnion = document.getElementById('owner_permanent_union_ward');
                        const savedUnion = ownerPermanentUnion.getAttribute('data-selected');
                        if (savedUnion) {
                            ownerPermanentUnion.value = savedUnion;
                        }
                    }, 100);
                }
            }, 100);
        }
    }, 200);

    // Real-time validation
    const requiredFields = form.querySelectorAll('input[required], select[required], textarea[required]');
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            validateField(this);
        });
    });

    function validateField(field) {
        const isValid = field.value.trim() !== '';
        
        if (isValid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
            const feedback = field.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.textContent = 'This field is required';
            }
        }
        
        return isValid;
    }
});
</script>
@endsection