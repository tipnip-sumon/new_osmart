@extends('member.layouts.app')

@section('title', 'KYC Verification')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">KYC Verification</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">KYC Verification</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- KYC Status Alert -->
        @if($kyc->status === 'rejected')
            <div class="alert alert-danger">
                <h5><i class="fe fe-alert-triangle"></i> KYC Rejected</h5>
                <p>{{ $kyc->rejection_reason }}</p>
                <div class="mt-3">
                    <small class="d-block mb-2">You can update your information and resubmit for verification.</small>
                    <a href="{{ route('member.kyc.step', 1) }}" class="btn btn-warning btn-sm">
                        <i class="fe fe-edit"></i> Update & Resubmit KYC
                    </a>
                </div>
            </div>
        @elseif($kyc->status === 'pending')
            <div class="alert alert-info">
                <h5><i class="fe fe-clock"></i> KYC Under Review</h5>
                <p>Your KYC documents are being reviewed by our team. This process may take 1-3 business days.</p>
                <small>Submitted on: {{ $kyc->submitted_at->format('M d, Y h:i A') }}</small>
            </div>
        @elseif($kyc->status === 'verified')
            <div class="alert alert-success">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fe fe-check-circle fs-4"></i>
                    </div>
                    <div class="flex-fill">
                        <h5 class="mb-1">KYC Verified <span class="badge bg-success ms-2">✓ Verified</span></h5>
                        <p class="mb-1">Your identity has been successfully verified.</p>
                        <small class="text-muted">Verified on: {{ $kyc->verified_at->format('M d, Y h:i A') }}</small>
                        <div class="mt-2">
                            <span class="badge bg-info me-2">
                                <i class="fe fe-lock me-1"></i>Information Locked
                            </span>
                            <span class="badge bg-warning">
                                <i class="fe fe-shield-check me-1"></i>Identity Verified
                            </span>
                        </div>
                    </div>
                    <div class="ms-3">
                        <a href="{{ route('member.kyc.certificate') }}" class="btn btn-success btn-sm me-2">
                            <i class="fe fe-download me-1"></i>Download Certificate
                        </a>
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#contactUpdateModal">
                            <i class="fe fe-edit me-1"></i>Update Contact Info
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- KYC Progress -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Verification Progress
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="text-muted">Completion:</span>
                                <span class="fw-semibold">{{ $kyc->completion_percentage }}%</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($kyc->status === 'verified')
                            <div class="alert alert-success mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fe fe-shield-check fs-1 text-success"></i>
                                    </div>
                                    <div class="flex-fill">
                                        <h4 class="alert-heading text-success mb-2">
                                            <i class="fe fe-check-circle me-2"></i>KYC Already Verified
                                        </h4>
                                        <p class="mb-1">Your identity verification is complete and all information has been locked for security.</p>
                                        <hr class="my-2">
                                        <p class="mb-0 text-muted">
                                            <small>
                                                <i class="fe fe-info me-1"></i>
                                                You cannot edit KYC information once verified. Only contact details can be updated if needed.
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="progress mb-4" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ $kyc->completion_percentage }}%" 
                                 aria-valuenow="{{ $kyc->completion_percentage }}" 
                                 aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>

                        <!-- Step Cards -->
                        <div class="row">
                            @foreach($steps as $stepNumber => $stepInfo)
                                <div class="col-lg-12 mb-3">
                                    <div class="card border {{ $stepInfo['completed'] ? 'border-success' : 'border-light' }}">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <div class="avatar avatar-lg {{ $stepInfo['completed'] ? 'bg-success' : 'bg-light' }} text-{{ $stepInfo['completed'] ? 'white' : 'muted' }}">
                                                        @if($stepInfo['completed'])
                                                            <i class="fe fe-check"></i>
                                                        @else
                                                            <i class="{{ $stepInfo['icon'] }}"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-fill">
                                                    <h6 class="mb-1">Step {{ $stepNumber }}: {{ $stepInfo['title'] }}</h6>
                                                    <p class="text-muted mb-0">{{ $stepInfo['description'] }}</p>
                                                </div>
                                                <div class="ms-3">
                                                    @if($kyc->status === 'verified')
                                                        <span class="badge bg-success text-white">
                                                            <i class="fe fe-check-circle me-1"></i>Verified & Locked
                                                        </span>
                                                    @elseif($kyc->status === 'pending')
                                                        <span class="badge bg-info text-white">
                                                            <i class="fe fe-clock me-1"></i>Under Review
                                                        </span>
                                                    @elseif($kyc->status === 'rejected')
                                                        @if($stepInfo['completed'])
                                                            <a href="{{ route('member.kyc.step', $stepNumber) }}" class="btn btn-warning btn-sm">
                                                                <i class="fe fe-edit"></i> Update & Resubmit
                                                            </a>
                                                        @else
                                                            <span class="badge bg-danger">Needs Update</span>
                                                        @endif
                                                    @elseif($kyc->status === 'draft')
                                                        @if($stepInfo['completed'])
                                                            <a href="{{ route('member.kyc.step', $stepNumber) }}" class="btn btn-outline-primary btn-sm">
                                                                <i class="fe fe-edit"></i> Edit
                                                            </a>
                                                        @elseif($stepNumber <= $kyc->current_step || $stepNumber === 1)
                                                            <a href="{{ route('member.kyc.step', $stepNumber) }}" class="btn btn-primary btn-sm">
                                                                <i class="fe fe-arrow-right"></i> {{ $stepInfo['completed'] ? 'Edit' : 'Continue' }}
                                                            </a>
                                                        @else
                                                            <span class="badge bg-light text-muted">Locked</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-light text-muted">Not Available</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Profile Mismatches -->
                        @if($kyc->profile_mismatches && is_array($kyc->profile_mismatches) && count($kyc->profile_mismatches) > 0)
                            <div class="alert alert-warning mt-4">
                                <h6><i class="fe fe-alert-triangle"></i> Profile Information Mismatch</h6>
                                <p class="mb-2">The following information doesn't match your profile:</p>
                                <ul class="mb-0">
                                    @foreach($kyc->profile_mismatches as $mismatch)
                                        @if(is_array($mismatch) && isset($mismatch['profile_value']) && isset($mismatch['kyc_value']))
                                            <li><strong>{{ ucwords(str_replace('_', ' ', $mismatch['field'])) }}:</strong> 
                                                Profile: "{{ $mismatch['profile_value'] }}" vs KYC: "{{ $mismatch['kyc_value'] }}"</li>
                                        @endif
                                    @endforeach
                                </ul>
                                @if($kyc->status === 'verified')
                                    <div class="mt-3">
                                        <button class="btn btn-warning btn-sm" onclick="updateProfileFromKyc()">
                                            <i class="fe fe-refresh-cw"></i> Update Profile from KYC
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="text-center mt-4">
                            @if($kyc->status === 'draft' && $kyc->completion_percentage === 100)
                                <a href="{{ route('member.kyc.step', 5) }}" class="btn btn-success btn-lg">
                                    <i class="fe fe-send"></i> Submit for Verification
                                </a>
                            @elseif($kyc->status === 'draft')
                                <a href="{{ route('member.kyc.step', $kyc->current_step) }}" class="btn btn-primary btn-lg">
                                    <i class="fe fe-arrow-right"></i> Continue KYC
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Preview -->
        @if($kyc->document_front_image || $kyc->document_back_image || $kyc->user_photo)
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fe fe-image me-2"></i>Uploaded Documents
                            </div>
                        </div>
                        <div class="card-body">
                            
                            <div class="row">
                                @if($kyc->document_front_image)
                                    <div class="col-md-4 mb-3">
                                        <div class="text-center">
                                            @php
                                                // Dynamic image handling for KYC documents (matching product system)
                                                $frontImageUrl = '';
                                                $frontImageExists = false;
                                                
                                                if ($kyc->document_front_image) {
                                                    // First try images array (if KYC uses complex image structure)
                                                    if (isset($kyc->document_front_image_data) && $kyc->document_front_image_data) {
                                                        $images = is_string($kyc->document_front_image_data) ? json_decode($kyc->document_front_image_data, true) : $kyc->document_front_image_data;
                                                        if (is_array($images) && !empty($images)) {
                                                            $image = $images[0]; // Get first image
                                                            
                                                            // Handle complex nested structure first
                                                            if (is_array($image) && isset($image['sizes']['medium']['storage_url'])) {
                                                                $frontImageUrl = $image['sizes']['medium']['storage_url'];
                                                                $frontImageExists = true;
                                                            } elseif (is_array($image) && isset($image['sizes']['original']['storage_url'])) {
                                                                $frontImageUrl = $image['sizes']['original']['storage_url'];
                                                                $frontImageExists = true;
                                                            } elseif (is_array($image) && isset($image['sizes']['large']['storage_url'])) {
                                                                $frontImageUrl = $image['sizes']['large']['storage_url'];
                                                                $frontImageExists = true;
                                                            } elseif (is_array($image) && isset($image['urls']['medium'])) {
                                                                $frontImageUrl = $image['urls']['medium'];
                                                                $frontImageExists = true;
                                                            } elseif (is_array($image) && isset($image['urls']['original'])) {
                                                                $frontImageUrl = $image['urls']['original'];
                                                                $frontImageExists = true;
                                                            } elseif (is_array($image) && isset($image['url']) && is_string($image['url'])) {
                                                                $frontImageUrl = $image['url'];
                                                                $frontImageExists = true;
                                                            } elseif (is_array($image) && isset($image['path']) && is_string($image['path'])) {
                                                                $frontImageUrl = asset('storage/' . $image['path']);
                                                                $frontImageExists = file_exists(public_path('storage/' . $image['path']));
                                                            } elseif (is_string($image)) {
                                                                $frontImageUrl = asset('storage/' . $image);
                                                                $frontImageExists = file_exists(public_path('storage/' . $image));
                                                            }
                                                        }
                                                    }
                                                    
                                                    // Fallback to simple field accessor (current KYC system)
                                                    if (empty($frontImageUrl)) {
                                                        // Check if it's a complete URL
                                                        if (str_starts_with($kyc->document_front_image, 'http')) {
                                                            $frontImageUrl = $kyc->document_front_image;
                                                            $frontImageExists = true;
                                                        } else {
                                                            // Try storage paths
                                                            $imagePath = $kyc->document_front_image;
                                                            $pathsToTry = [
                                                                'storage/' . $imagePath,
                                                                'storage/' . ltrim($imagePath, '/'),
                                                                $imagePath
                                                            ];
                                                            
                                                            foreach ($pathsToTry as $path) {
                                                                if (file_exists(public_path($path))) {
                                                                    $frontImageUrl = asset($path);
                                                                    $frontImageExists = true;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                // Final fallback
                                                if (empty($frontImageUrl) || !$frontImageExists) {
                                                    $frontImageUrl = asset('assets/img/product/default.png');
                                                }
                                            @endphp
                                            <img src="{{ $frontImageUrl }}" 
                                                 alt="Document Front" 
                                                 class="img-fluid rounded border" 
                                                 style="max-height: 200px; min-height: 150px; object-fit: cover; background-color: #f8f9fa;"
                                                 onerror="this.src='{{ asset('assets/img/product/default.png') }}'; this.style.padding='20px';">
                                            <p class="text-muted mt-2">{{ ucwords(str_replace('_', ' ', $kyc->document_type ?? 'Document')) }} - Front</p>
                                            
                                        </div>
                                    </div>
                                @endif
                                @if($kyc->document_back_image)
                                    <div class="col-md-4 mb-3">
                                        <div class="text-center">
                                            @php
                                                // Dynamic image handling for KYC back documents (matching product system)
                                                $backImageUrl = '';
                                                $backImageExists = false;
                                                
                                                if ($kyc->document_back_image) {
                                                    // First try images array (if KYC uses complex image structure)
                                                    if (isset($kyc->document_back_image_data) && $kyc->document_back_image_data) {
                                                        $images = is_string($kyc->document_back_image_data) ? json_decode($kyc->document_back_image_data, true) : $kyc->document_back_image_data;
                                                        if (is_array($images) && !empty($images)) {
                                                            $image = $images[0]; // Get first image
                                                            
                                                            // Handle complex nested structure first
                                                            if (is_array($image) && isset($image['sizes']['medium']['storage_url'])) {
                                                                $backImageUrl = $image['sizes']['medium']['storage_url'];
                                                                $backImageExists = true;
                                                            } elseif (is_array($image) && isset($image['sizes']['original']['storage_url'])) {
                                                                $backImageUrl = $image['sizes']['original']['storage_url'];
                                                                $backImageExists = true;
                                                            } elseif (is_array($image) && isset($image['sizes']['large']['storage_url'])) {
                                                                $backImageUrl = $image['sizes']['large']['storage_url'];
                                                                $backImageExists = true;
                                                            } elseif (is_array($image) && isset($image['urls']['medium'])) {
                                                                $backImageUrl = $image['urls']['medium'];
                                                                $backImageExists = true;
                                                            } elseif (is_array($image) && isset($image['urls']['original'])) {
                                                                $backImageUrl = $image['urls']['original'];
                                                                $backImageExists = true;
                                                            } elseif (is_array($image) && isset($image['url']) && is_string($image['url'])) {
                                                                $backImageUrl = $image['url'];
                                                                $backImageExists = true;
                                                            } elseif (is_array($image) && isset($image['path']) && is_string($image['path'])) {
                                                                $backImageUrl = asset('storage/' . $image['path']);
                                                                $backImageExists = file_exists(public_path('storage/' . $image['path']));
                                                            } elseif (is_string($image)) {
                                                                $backImageUrl = asset('storage/' . $image);
                                                                $backImageExists = file_exists(public_path('storage/' . $image));
                                                            }
                                                        }
                                                    }
                                                    
                                                    // Fallback to simple field accessor (current KYC system)
                                                    if (empty($backImageUrl)) {
                                                        if (str_starts_with($kyc->document_back_image, 'http')) {
                                                            $backImageUrl = $kyc->document_back_image;
                                                            $backImageExists = true;
                                                        } else {
                                                            $imagePath = $kyc->document_back_image;
                                                            $pathsToTry = [
                                                                'storage/' . $imagePath,
                                                                'storage/' . ltrim($imagePath, '/'),
                                                                $imagePath
                                                            ];
                                                            
                                                            foreach ($pathsToTry as $path) {
                                                                if (file_exists(public_path($path))) {
                                                                    $backImageUrl = asset($path);
                                                                    $backImageExists = true;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                // Final fallback
                                                if (empty($backImageUrl) || !$backImageExists) {
                                                    $backImageUrl = asset('assets/img/product/default.png');
                                                }
                                            @endphp
                                            <img src="{{ $backImageUrl }}" 
                                                 alt="Document Back" 
                                                 class="img-fluid rounded border" 
                                                 style="max-height: 200px; min-height: 150px; object-fit: cover; background-color: #f8f9fa;"
                                                 onerror="this.src='{{ asset('assets/img/product/default.png') }}'; this.style.padding='20px';">
                                            <p class="text-muted mt-2">{{ ucwords(str_replace('_', ' ', $kyc->document_type ?? 'Document')) }} - Back</p>
                                            
                                        </div>
                                    </div>
                                @endif
                                @if($kyc->user_photo)
                                    <div class="col-md-4 mb-3">
                                        <div class="text-center">
                                            @php
                                                // Dynamic image handling for KYC user photos (matching product system)
                                                $userPhotoUrl = '';
                                                $userPhotoExists = false;
                                                
                                                if ($kyc->user_photo) {
                                                    // First try images array (if KYC uses complex image structure)
                                                    if (isset($kyc->user_photo_data) && $kyc->user_photo_data) {
                                                        $images = is_string($kyc->user_photo_data) ? json_decode($kyc->user_photo_data, true) : $kyc->user_photo_data;
                                                        if (is_array($images) && !empty($images)) {
                                                            $image = $images[0]; // Get first image
                                                            
                                                            // Handle complex nested structure first
                                                            if (is_array($image) && isset($image['sizes']['medium']['storage_url'])) {
                                                                $userPhotoUrl = $image['sizes']['medium']['storage_url'];
                                                                $userPhotoExists = true;
                                                            } elseif (is_array($image) && isset($image['sizes']['original']['storage_url'])) {
                                                                $userPhotoUrl = $image['sizes']['original']['storage_url'];
                                                                $userPhotoExists = true;
                                                            } elseif (is_array($image) && isset($image['sizes']['large']['storage_url'])) {
                                                                $userPhotoUrl = $image['sizes']['large']['storage_url'];
                                                                $userPhotoExists = true;
                                                            } elseif (is_array($image) && isset($image['urls']['medium'])) {
                                                                $userPhotoUrl = $image['urls']['medium'];
                                                                $userPhotoExists = true;
                                                            } elseif (is_array($image) && isset($image['urls']['original'])) {
                                                                $userPhotoUrl = $image['urls']['original'];
                                                                $userPhotoExists = true;
                                                            } elseif (is_array($image) && isset($image['url']) && is_string($image['url'])) {
                                                                $userPhotoUrl = $image['url'];
                                                                $userPhotoExists = true;
                                                            } elseif (is_array($image) && isset($image['path']) && is_string($image['path'])) {
                                                                $userPhotoUrl = asset('storage/' . $image['path']);
                                                                $userPhotoExists = file_exists(public_path('storage/' . $image['path']));
                                                            } elseif (is_string($image)) {
                                                                $userPhotoUrl = asset('storage/' . $image);
                                                                $userPhotoExists = file_exists(public_path('storage/' . $image));
                                                            }
                                                        }
                                                    }
                                                    
                                                    // Fallback to simple field accessor (current KYC system)
                                                    if (empty($userPhotoUrl)) {
                                                        if (str_starts_with($kyc->user_photo, 'http')) {
                                                            $userPhotoUrl = $kyc->user_photo;
                                                            $userPhotoExists = true;
                                                        } else {
                                                            $imagePath = $kyc->user_photo;
                                                            $pathsToTry = [
                                                                'storage/' . $imagePath,
                                                                'storage/' . ltrim($imagePath, '/'),
                                                                $imagePath
                                                            ];
                                                            
                                                            foreach ($pathsToTry as $path) {
                                                                if (file_exists(public_path($path))) {
                                                                    $userPhotoUrl = asset($path);
                                                                    $userPhotoExists = true;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                // Final fallback
                                                if (empty($userPhotoUrl) || !$userPhotoExists) {
                                                    $userPhotoUrl = asset('assets/img/default-avatar.svg');
                                                }
                                            @endphp
                                            <img src="{{ $userPhotoUrl }}" 
                                                 alt="User Photo" 
                                                 class="img-fluid rounded border" 
                                                 style="max-height: 200px; min-height: 150px; object-fit: cover; background-color: #f8f9fa;"
                                                 onerror="this.src='{{ asset('assets/img/default-avatar.svg') }}'; this.style.padding='20px';">
                                            <p class="text-muted mt-2">User Photo</p>
                                            @if(config('app.debug'))
                                                <small class="text-muted d-block">Path: {{ $kyc->user_photo }}</small>
                                                <small class="text-muted d-block">URL: {{ $userPhotoUrl }}</small>
                                                <small class="text-muted d-block">File Exists: {{ $userPhotoExists ? 'Yes' : 'No' }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- No documents uploaded yet -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card border-dashed">
                        <div class="card-body text-center py-5">
                            <i class="fe fe-upload fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted mb-2">No Documents Uploaded</h5>
                            <p class="text-muted mb-3">Complete the KYC steps to upload your documents and photos.</p>
                            @if($kyc->status !== 'verified' && $kyc->status !== 'pending')
                                <a href="{{ route('member.kyc.step', 4) }}" class="btn btn-primary">
                                    <i class="fe fe-upload me-1"></i>Upload Documents
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Contact Information Update Modal for Verified KYCs -->
@if($kyc->status === 'verified')
<div class="modal fade" id="contactUpdateModal" tabindex="-1" aria-labelledby="contactUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="contactUpdateModalLabel">
                    <i class="fe fe-edit me-2"></i>Update Contact Information
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="contactUpdateForm">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fe fe-info me-2"></i>
                        <strong>Note:</strong> Only contact information can be updated for verified KYCs. 
                        Core identity information remains locked for security.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" 
                                       value="{{ $kyc->phone_number }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="alternative_phone" class="form-label">Alternative Phone</label>
                                <input type="text" class="form-control" id="alternative_phone" name="alternative_phone" 
                                       value="{{ $kyc->alternative_phone }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email_address" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email_address" name="email_address" 
                               value="{{ $kyc->email_address }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="present_address" class="form-label">Present Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="present_address" name="present_address" rows="3" required>{{ $kyc->present_address }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" 
                               value="{{ $kyc->emergency_contact_phone }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fe fe-x me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="updateContactBtn">
                        <i class="fe fe-save me-1"></i>Update Information
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
function updateProfileFromKyc() {
    if (confirm('This will update your profile with verified KYC information. Continue?')) {
        fetch('{{ route("member.kyc.update-profile") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error updating profile');
            console.error(error);
        });
    }
}

// Contact Information Update Handler
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactUpdateForm');
    const updateBtn = document.getElementById('updateContactBtn');
    const modal = document.getElementById('contactUpdateModal');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            updateBtn.disabled = true;
            updateBtn.innerHTML = '<i class="fe fe-loader me-1 fa-spin"></i>Updating...';
            
            const formData = new FormData(contactForm);
            const data = Object.fromEntries(formData);
            
            fetch('{{ route("member.kyc.update-contact") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    // Show success message
                    alert('✅ ' + result.message);
                    
                    // Close modal
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    modalInstance.hide();
                    
                    // Optionally reload page to show updated info
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert('❌ ' + result.message);
                }
            })
            .catch(error => {
                console.error('Update error:', error);
                alert('❌ An error occurred while updating contact information.');
            })
            .finally(() => {
                // Reset button state
                updateBtn.disabled = false;
                updateBtn.innerHTML = '<i class="fe fe-save me-1"></i>Update Information';
            });
        });
    }
});
</script>
@endsection