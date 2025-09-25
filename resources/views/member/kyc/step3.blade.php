@extends('member.layouts.app')

@section('title', 'KYC Step 3 - Address Information')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">KYC Verification - Step 3</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.kyc.index') }}">KYC</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Address Information</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        @if($kyc->status === 'verified')
            <div class="alert alert-success">
                <div class="d-flex align-items-center">
                    <i class="fe fe-check-circle fs-3 text-success me-3"></i>
                    <div>
                        <h5 class="mb-1 text-success">KYC Already Verified</h5>
                        <p class="mb-0">Your identity verification is complete and information is locked.</p>
                        <div class="mt-2">
                            <a href="{{ route('member.kyc.index') }}" class="btn btn-success btn-sm">
                                <i class="fe fe-arrow-left"></i> Back to KYC Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @section('content')
            @stop
        @endif

        @if($kyc->status === 'pending')
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <i class="fe fe-clock fs-3 text-info me-3"></i>
                    <div>
                        <h5 class="mb-1 text-info">KYC Under Review</h5>
                        <p class="mb-0">Your KYC is being reviewed. Editing is temporarily disabled.</p>
                        <div class="mt-2">
                            <a href="{{ route('member.kyc.index') }}" class="btn btn-info btn-sm">
                                <i class="fe fe-arrow-left"></i> Back to KYC Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @section('content')
            @stop
        @endif

        <!-- Progress Bar -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0">Step 3 of 5: Address Information</h6>
                            <span class="badge bg-primary">60% Complete</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
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
                                <div class="step completed">
                                    <div class="step-circle bg-success text-white">✓</div>
                                    <span class="step-label">Personal Info</span>
                                </div>
                                <div class="step-arrow text-success">→</div>
                                <div class="step completed">
                                    <div class="step-circle bg-success text-white">✓</div>
                                    <span class="step-label">Document Info</span>
                                </div>
                                <div class="step-arrow text-success">→</div>
                                <div class="step active">
                                    <div class="step-circle bg-primary text-white">3</div>
                                    <span class="step-label">Address</span>
                                </div>
                                <div class="step-arrow text-muted">→</div>
                                <div class="step">
                                    <div class="step-circle bg-light text-muted">4</div>
                                    <span class="step-label text-muted">Documents</span>
                                </div>
                                <div class="step-arrow text-muted">→</div>
                                <div class="step">
                                    <div class="step-circle bg-light text-muted">5</div>
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
                        <div class="card-title">Address & Contact Information</div>
                    </div>
                    <div class="card-body">
                        <form id="kycStep3Form" action="{{ route('member.kyc.save-step', 3) }}" method="POST">
                            @csrf

                            <!-- Present Address -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3 text-primary">Present Address</h5>
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label for="present_address" class="form-label">Present Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="present_address" name="present_address" 
                                              rows="3" required placeholder="House/Holding No, Road No, Area">{{ old('present_address', $kyc->present_address) }}</textarea>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="present_country" class="form-label">Country <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="present_country" name="present_country" 
                                           value="{{ old('present_country', $kyc->present_country ?: 'Bangladesh') }}" required readonly>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="present_district" class="form-label">District <span class="text-danger">*</span></label>
                                    <select class="form-select" id="present_district" name="present_district" required>
                                        <option value="">Select District</option>
                                        @if($locationData && is_array($locationData))
                                            @foreach($locationData as $district)
                                                <option value="{{ $district['name'] }}" 
                                                        {{ old('present_district', $kyc->present_district) == $district['name'] ? 'selected' : '' }}>
                                                    {{ $district['name'] }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="Dhaka" {{ old('present_district', $kyc->present_district) == 'Dhaka' ? 'selected' : '' }}>Dhaka</option>
                                            <option value="Chittagong" {{ old('present_district', $kyc->present_district) == 'Chittagong' ? 'selected' : '' }}>Chittagong</option>
                                            <option value="Sylhet" {{ old('present_district', $kyc->present_district) == 'Sylhet' ? 'selected' : '' }}>Sylhet</option>
                                            <option value="Rajshahi" {{ old('present_district', $kyc->present_district) == 'Rajshahi' ? 'selected' : '' }}>Rajshahi</option>
                                            <option value="Khulna" {{ old('present_district', $kyc->present_district) == 'Khulna' ? 'selected' : '' }}>Khulna</option>
                                            <option value="Barisal" {{ old('present_district', $kyc->present_district) == 'Barisal' ? 'selected' : '' }}>Barisal</option>
                                            <option value="Rangpur" {{ old('present_district', $kyc->present_district) == 'Rangpur' ? 'selected' : '' }}>Rangpur</option>
                                            <option value="Mymensingh" {{ old('present_district', $kyc->present_district) == 'Mymensingh' ? 'selected' : '' }}>Mymensingh</option>
                                        @endif
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="present_upazila" class="form-label">Upazila/Thana <span class="text-danger">*</span></label>
                                    <select class="form-select" id="present_upazila" name="present_upazila" required>
                                        <option value="">Select District First</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="present_union_ward" class="form-label">Union/Ward <span class="text-danger">*</span></label>
                                    <select class="form-select" id="present_union_ward" name="present_union_ward" required>
                                        <option value="">Select Upazila First</option>
                                        @if(old('present_union_ward', $kyc->present_union_ward))
                                            <option value="{{ old('present_union_ward', $kyc->present_union_ward) }}" selected>
                                                {{ old('present_union_ward', $kyc->present_union_ward) }}
                                            </option>
                                        @endif
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="present_post_office" class="form-label">Post Office</label>
                                    <input type="text" class="form-control" id="present_post_office" name="present_post_office" 
                                           value="{{ old('present_post_office', $kyc->present_post_office) }}" 
                                           placeholder="Post office name">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="present_postal_code" class="form-label">Postal Code</label>
                                    <input type="text" class="form-control" id="present_postal_code" name="present_postal_code" 
                                           value="{{ old('present_postal_code', $kyc->present_postal_code) }}" 
                                           placeholder="e.g., 1000" pattern="[0-9]{4}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Permanent Address -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h5 class="mb-0 text-primary">Permanent Address</h5>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="same_as_present_address" 
                                                   name="same_as_present_address" value="1" 
                                                   {{ old('same_as_present_address', $kyc->same_as_present_address) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="same_as_present_address">
                                                Same as present address
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div id="permanent_address_fields" style="display: {{ old('same_as_present_address', $kyc->same_as_present_address) ? 'none' : 'block' }};">
                                    <div class="col-md-12 mb-3">
                                        <label for="permanent_address" class="form-label">Permanent Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="permanent_address" name="permanent_address" 
                                                  rows="3" placeholder="House/Holding No, Road No, Area">{{ old('permanent_address', $kyc->permanent_address) }}</textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="permanent_country" class="form-label">Country <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="permanent_country" name="permanent_country" 
                                               value="{{ old('permanent_country', $kyc->permanent_country ?: 'Bangladesh') }}" readonly>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="permanent_district" class="form-label">District <span class="text-danger">*</span></label>
                                        <select class="form-select" id="permanent_district" name="permanent_district">
                                            <option value="">Select District</option>
                                            @if($locationData && is_array($locationData))
                                                @foreach($locationData as $district)
                                                    <option value="{{ $district['name'] }}" 
                                                            {{ old('permanent_district', $kyc->permanent_district) == $district['name'] ? 'selected' : '' }}>
                                                        {{ $district['name'] }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="Dhaka" {{ old('permanent_district', $kyc->permanent_district) == 'Dhaka' ? 'selected' : '' }}>Dhaka</option>
                                                <option value="Chittagong" {{ old('permanent_district', $kyc->permanent_district) == 'Chittagong' ? 'selected' : '' }}>Chittagong</option>
                                            @endif
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="permanent_upazila" class="form-label">Upazila/Thana <span class="text-danger">*</span></label>
                                        <select class="form-select" id="permanent_upazila" name="permanent_upazila">
                                            <option value="">Select District First</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>

                        <div class="col-md-6 mb-3">
                            <label for="permanent_union_ward" class="form-label">Union/Ward <span class="text-danger">*</span></label>
                            <select class="form-select" id="permanent_union_ward" name="permanent_union_ward">
                                <option value="">Select Upazila First</option>
                                @if(old('permanent_union_ward', $kyc->permanent_union_ward))
                                    <option value="{{ old('permanent_union_ward', $kyc->permanent_union_ward) }}" selected>
                                        {{ old('permanent_union_ward', $kyc->permanent_union_ward) }}
                                    </option>
                                @endif
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>                                    <div class="col-md-6 mb-3">
                                        <label for="permanent_post_office" class="form-label">Post Office</label>
                                        <input type="text" class="form-control" id="permanent_post_office" name="permanent_post_office" 
                                               value="{{ old('permanent_post_office', $kyc->permanent_post_office) }}" 
                                               placeholder="Post office name">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="permanent_postal_code" class="form-label">Postal Code</label>
                                        <input type="text" class="form-control" id="permanent_postal_code" name="permanent_postal_code" 
                                               value="{{ old('permanent_postal_code', $kyc->permanent_postal_code) }}" 
                                               placeholder="e.g., 1000" pattern="[0-9]{4}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Contact Information -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3 text-primary">Contact Information</h5>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                           value="{{ old('phone_number', $kyc->phone_number) }}" 
                                           placeholder="01XXXXXXXXX" pattern="[0-9]{11}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="alternative_phone" class="form-label">Alternative Phone</label>
                                    <input type="tel" class="form-control" id="alternative_phone" name="alternative_phone" 
                                           value="{{ old('alternative_phone', $kyc->alternative_phone) }}" 
                                           placeholder="01XXXXXXXXX" pattern="[0-9]{11}">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="email_address" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email_address" name="email_address" 
                                           value="{{ old('email_address', $kyc->email_address) }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Emergency Contact -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3 text-primary">Emergency Contact</h5>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="emergency_contact_name" class="form-label">Contact Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" 
                                           value="{{ old('emergency_contact_name', $kyc->emergency_contact_name) }}" 
                                           placeholder="Full name of emergency contact" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="emergency_contact_relationship" class="form-label">Relationship <span class="text-danger">*</span></label>
                                    <select class="form-select" id="emergency_contact_relationship" name="emergency_contact_relationship" required>
                                        <option value="">Select Relationship</option>
                                        <option value="Father" {{ old('emergency_contact_relationship', $kyc->emergency_contact_relationship) == 'Father' ? 'selected' : '' }}>Father</option>
                                        <option value="Mother" {{ old('emergency_contact_relationship', $kyc->emergency_contact_relationship) == 'Mother' ? 'selected' : '' }}>Mother</option>
                                        <option value="Spouse" {{ old('emergency_contact_relationship', $kyc->emergency_contact_relationship) == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                        <option value="Brother" {{ old('emergency_contact_relationship', $kyc->emergency_contact_relationship) == 'Brother' ? 'selected' : '' }}>Brother</option>
                                        <option value="Sister" {{ old('emergency_contact_relationship', $kyc->emergency_contact_relationship) == 'Sister' ? 'selected' : '' }}>Sister</option>
                                        <option value="Son" {{ old('emergency_contact_relationship', $kyc->emergency_contact_relationship) == 'Son' ? 'selected' : '' }}>Son</option>
                                        <option value="Daughter" {{ old('emergency_contact_relationship', $kyc->emergency_contact_relationship) == 'Daughter' ? 'selected' : '' }}>Daughter</option>
                                        <option value="Friend" {{ old('emergency_contact_relationship', $kyc->emergency_contact_relationship) == 'Friend' ? 'selected' : '' }}>Friend</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="emergency_contact_phone" class="form-label">Contact Phone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" 
                                           value="{{ old('emergency_contact_phone', $kyc->emergency_contact_phone) }}" 
                                           placeholder="01XXXXXXXXX" pattern="[0-9]{11}" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="emergency_contact_address" class="form-label">Contact Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="emergency_contact_address" name="emergency_contact_address" 
                                              rows="3" placeholder="Emergency contact address" required>{{ old('emergency_contact_address', $kyc->emergency_contact_address) }}</textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('member.kyc.step', 2) }}" class="btn btn-outline-secondary">
                                    <i class="fe fe-arrow-left"></i> Previous Step
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    Next Step <i class="fe fe-arrow-right"></i>
                                </button>
                            </div>
                        </form>
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
}
.step-label {
    font-size: 0.85rem;
    text-align: center;
}
.step-arrow {
    font-size: 1.2rem;
    margin-top: -1.5rem;
}
@media (max-width: 768px) {
    .step-navigation {
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .step {
        min-width: 60px;
    }
    .step-circle {
        width: 30px;
        height: 30px;
    }
    .step-label {
        font-size: 0.75rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('kycStep3Form');
    const submitBtn = document.getElementById('submitBtn');
    const sameAddressCheckbox = document.getElementById('same_as_present_address');
    const permanentAddressFields = document.getElementById('permanent_address_fields');
    
    // Location data for dropdowns
    const locationData = @json($locationData ?? []);
    
    // Same address checkbox handler
    sameAddressCheckbox.addEventListener('change', function() {
        if (this.checked) {
            // Copy present address values to permanent address fields
            document.getElementById('permanent_address').value = document.getElementById('present_address').value;
            document.getElementById('permanent_country').value = document.getElementById('present_country').value;
            document.getElementById('permanent_district').value = document.getElementById('present_district').value;
            document.getElementById('permanent_post_office').value = document.getElementById('present_post_office').value;
            document.getElementById('permanent_postal_code').value = document.getElementById('present_postal_code').value;
            
            // Copy upazila after populating the dropdown
            const presentUpazila = document.getElementById('present_upazila').value;
            if (presentUpazila) {
                populateUpazilas(
                    document.getElementById('permanent_district'), 
                    document.getElementById('permanent_upazila'),
                    presentUpazila
                );
                
                // Copy union after populating the upazila dropdown
                setTimeout(() => {
                    const presentUnion = document.getElementById('present_union_ward').value;
                    if (presentUnion) {
                        populateUnions(
                            document.getElementById('permanent_district'),
                            document.getElementById('permanent_upazila'),
                            document.getElementById('permanent_union_ward'),
                            presentUnion
                        );
                    }
                }, 200);
            }
            
            permanentAddressFields.style.display = 'none';
            // Remove required attributes for permanent address fields
            permanentAddressFields.querySelectorAll('[required]').forEach(field => {
                field.removeAttribute('required');
            });
        } else {
            permanentAddressFields.style.display = 'block';
            // Add required attributes back
            document.getElementById('permanent_address').setAttribute('required', '');
            document.getElementById('permanent_country').setAttribute('required', '');
            document.getElementById('permanent_district').setAttribute('required', '');
            document.getElementById('permanent_upazila').setAttribute('required', '');
            document.getElementById('permanent_union_ward').setAttribute('required', '');
        }
    });
    
    // District change handlers for upazila population
    function populateUpazilas(districtSelect, upazilaSelect, selectedUpazila = '') {
        const selectedDistrictName = districtSelect.value;
        
        if (selectedDistrictName && locationData && Array.isArray(locationData)) {
            upazilaSelect.innerHTML = '<option value="">Loading...</option>';
            
            // Find the district in location data
            const district = locationData.find(d => d.name === selectedDistrictName);
            
            if (district && district.upazilas) {
                upazilaSelect.innerHTML = '<option value="">Select Upazila</option>';
                
                district.upazilas.forEach(upazila => {
                    const option = document.createElement('option');
                    option.value = upazila.name;
                    option.textContent = upazila.name;
                    if (upazila.name === selectedUpazila) {
                        option.selected = true;
                    }
                    upazilaSelect.appendChild(option);
                });
            } else {
                // Fallback to default upazilas if district not found
                upazilaSelect.innerHTML = '<option value="">Select Upazila</option>';
            }
        } else {
            upazilaSelect.innerHTML = '<option value="">Select District First</option>';
        }
    }

    // Upazila change handlers for union population
    function populateUnions(districtSelect, upazilaSelect, unionSelect, selectedUnion = '') {
        const selectedDistrictName = districtSelect.value;
        const selectedUpazilaName = upazilaSelect.value;
        
        if (selectedDistrictName && selectedUpazilaName && locationData && Array.isArray(locationData)) {
            unionSelect.innerHTML = '<option value="">Loading...</option>';
            
            // Find the district and upazila in location data
            const district = locationData.find(d => d.name === selectedDistrictName);
            
            if (district && district.upazilas) {
                const upazila = district.upazilas.find(u => u.name === selectedUpazilaName);
                
                if (upazila && upazila.unions) {
                    unionSelect.innerHTML = '<option value="">Select Union/Ward</option>';
                    
                    upazila.unions.forEach(union => {
                        const option = document.createElement('option');
                        option.value = union;
                        option.textContent = union;
                        if (union === selectedUnion) {
                            option.selected = true;
                        }
                        unionSelect.appendChild(option);
                    });
                } else {
                    unionSelect.innerHTML = '<option value="">No unions available</option>';
                }
            } else {
                unionSelect.innerHTML = '<option value="">District not found</option>';
            }
        } else {
            unionSelect.innerHTML = '<option value="">Select Upazila First</option>';
        }
    }
    
    // Present address district change
    document.getElementById('present_district').addEventListener('change', function() {
        populateUpazilas(this, document.getElementById('present_upazila'), '{{ old("present_upazila", $kyc->present_upazila) }}');
        // Clear union dropdown when district changes
        document.getElementById('present_union_ward').innerHTML = '<option value="">Select Upazila First</option>';
    });
    
    // Present address upazila change
    document.getElementById('present_upazila').addEventListener('change', function() {
        populateUnions(
            document.getElementById('present_district'), 
            this, 
            document.getElementById('present_union_ward'), 
            '{{ old("present_union_ward", $kyc->present_union_ward) }}'
        );
    });
    
    // Permanent address district change
    document.getElementById('permanent_district').addEventListener('change', function() {
        populateUpazilas(this, document.getElementById('permanent_upazila'), '{{ old("permanent_upazila", $kyc->permanent_upazila) }}');
        // Clear union dropdown when district changes
        document.getElementById('permanent_union_ward').innerHTML = '<option value="">Select Upazila First</option>';
    });
    
    // Permanent address upazila change
    document.getElementById('permanent_upazila').addEventListener('change', function() {
        populateUnions(
            document.getElementById('permanent_district'), 
            this, 
            document.getElementById('permanent_union_ward'), 
            '{{ old("permanent_union_ward", $kyc->permanent_union_ward) }}'
        );
    });
    
    // Initialize upazila dropdowns on page load
    if (document.getElementById('present_district').value) {
        populateUpazilas(
            document.getElementById('present_district'), 
            document.getElementById('present_upazila'),
            '{{ old("present_upazila", $kyc->present_upazila) }}'
        );
        
        // Initialize union dropdown if upazila is also selected
        setTimeout(() => {
            if (document.getElementById('present_upazila').value) {
                populateUnions(
                    document.getElementById('present_district'),
                    document.getElementById('present_upazila'),
                    document.getElementById('present_union_ward'),
                    '{{ old("present_union_ward", $kyc->present_union_ward) }}'
                );
            }
        }, 100);
    }
    
    if (document.getElementById('permanent_district').value) {
        populateUpazilas(
            document.getElementById('permanent_district'), 
            document.getElementById('permanent_upazila'),
            '{{ old("permanent_upazila", $kyc->permanent_upazila) }}'
        );
        
        // Initialize union dropdown if upazila is also selected
        setTimeout(() => {
            if (document.getElementById('permanent_upazila').value) {
                populateUnions(
                    document.getElementById('permanent_district'),
                    document.getElementById('permanent_upazila'),
                    document.getElementById('permanent_union_ward'),
                    '{{ old("permanent_union_ward", $kyc->permanent_union_ward) }}'
                );
            }
        }, 100);
    }
    
    // Form submission
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fe fe-loader"></i> Saving...';
    });
    
    // Phone number validation
    document.querySelectorAll('input[type="tel"]').forEach(phone => {
        phone.addEventListener('input', function() {
            const value = this.value.replace(/\D/g, '');
            if (value.length > 11) {
                this.value = value.substring(0, 11);
            }
            
            if (this.hasAttribute('required') && value.length !== 11) {
                this.setCustomValidity('Phone number must be 11 digits');
            } else if (!this.hasAttribute('required') && value.length > 0 && value.length !== 11) {
                this.setCustomValidity('Phone number must be 11 digits');
            } else {
                this.setCustomValidity('');
            }
        });
    });
});
</script>
@endsection