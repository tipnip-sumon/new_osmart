@extends('member.layouts.app')

@section('title', 'My Profile')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    
    // Safety check to ensure $user is properly defined
    if (!isset($user) || !is_object($user)) {
        $user = auth()->user();
    }
    
    // Get preferences from JSON column or set defaults
    $preferences = [];
    if ($user && is_object($user) && $user->preferences) {
        if (is_string($user->preferences)) {
            $preferences = json_decode($user->preferences, true) ?: [];
        } elseif (is_array($user->preferences)) {
            $preferences = $user->preferences;
        }
    }
    
    // Set preference values from stored data or defaults
    $emailNotifications = $preferences['email_notifications'] ?? true;
    $smsNotifications = $preferences['sms_notifications'] ?? false;
    $marketingEmails = $preferences['marketing_emails'] ?? true;
    $newsletter = $preferences['newsletter'] ?? true;
    $bio = $preferences['bio'] ?? '';
@endphp

<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">My Profile</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row g-4">
            <!-- Profile Information -->
            <div class="col-12 col-lg-4 col-xl-4">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="profile-avatar mb-3">
                            @php
                                // Complex avatar image handling for HandlesImageUploads trait
                                $avatarUrl = 'admin-assets/images/users/default.jpg'; // Default avatar
                                
                                if ($user && is_object($user) && $user->avatar) {
                                    $avatarData = $user->avatar;
                                    
                                    // Handle complex nested structure from HandlesImageUploads trait
                                    if (is_string($avatarData)) {
                                        $decodedAvatar = json_decode($avatarData, true);
                                        if (is_array($decodedAvatar)) {
                                            $avatarData = $decodedAvatar;
                                        }
                                    }
                                    
                                    // Check for complex image structure
                                    if (is_array($avatarData)) {
                                        // First try sizes structure (HandlesImageUploads format)
                                        if (isset($avatarData['sizes']['medium']['storage_url'])) {
                                            $avatarUrl = $avatarData['sizes']['medium']['storage_url'];
                                        } elseif (isset($avatarData['sizes']['original']['storage_url'])) {
                                            $avatarUrl = $avatarData['sizes']['original']['storage_url'];
                                        } elseif (isset($avatarData['sizes']['large']['storage_url'])) {
                                            $avatarUrl = $avatarData['sizes']['large']['storage_url'];
                                        } elseif (isset($avatarData['urls']['medium'])) {
                                            $avatarUrl = $avatarData['urls']['medium'];
                                        } elseif (isset($avatarData['urls']['original'])) {
                                            $avatarUrl = $avatarData['urls']['original'];
                                        } elseif (isset($avatarData['url']) && is_string($avatarData['url'])) {
                                            $avatarUrl = $avatarData['url'];
                                        } elseif (isset($avatarData['path']) && is_string($avatarData['path'])) {
                                            $avatarUrl = asset('storage/' . $avatarData['path']);
                                        }
                                    } elseif (is_string($avatarData)) {
                                        // Simple string path - check if it exists or is a URL
                                        if (str_starts_with($avatarData, 'http')) {
                                            $avatarUrl = $avatarData;
                                        } elseif (Storage::disk('public')->exists($avatarData)) {
                                            $avatarUrl = 'storage/' . $avatarData;
                                        } else {
                                            $avatarUrl = asset('storage/' . $avatarData);
                                        }
                                    }
                                }
                            @endphp
                            <img src="{{ asset($avatarUrl) }}" alt="profile" class="avatar avatar-xxl avatar-rounded" id="profileAvatar">
                            <button class="btn btn-sm btn-primary avatar-edit-btn" data-bs-toggle="modal" data-bs-target="#avatarModal">
                                <i class="fe fe-camera"></i>
                            </button>
                        </div>
                        <h5 class="fw-semibold mb-1">{{ $user && is_object($user) ? ($user->name ?? 'User') : 'User' }}</h5>
                        <p class="text-muted mb-2">{{ $user && is_object($user) ? ($user->email ?? 'No email') : 'No email' }}</p>
                        <span class="badge bg-success-transparent mb-3">{{ $user && is_object($user) ? ucfirst($user->status ?? 'Active') : 'Active' }}</span>
                        
                        <div class="profile-stats">
                            <div class="row">
                                <div class="col-4">
                                    <div class="text-center">
                                        <h5 class="fw-semibold text-primary mb-1">
                                            {{ $user && is_object($user) && $user->created_at ? $user->created_at->format('M Y') : 'Unknown' }}
                                        </h5>
                                        <p class="text-muted mb-0 fs-12">Member Since</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center">
                                        <h5 class="fw-semibold text-success mb-1">
                                            {{ $user && is_object($user) ? ($user->referral_code ?? $user->id ?? 'N/A') : 'N/A' }}
                                        </h5>
                                        <p class="text-muted mb-0 fs-12">ID</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center">
                                        <h5 class="fw-semibold text-warning mb-1">{{ $verificationStatus['completion_percentage'] }}%</h5>
                                        <p class="text-muted mb-0 fs-12">Profile Complete</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verification Status Card -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-shield me-2"></i>Verification Status
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Overall Progress -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold">Overall Progress</span>
                                <span class="badge {{ $verificationStatus['completion_percentage'] >= 100 ? 'bg-success' : 'bg-warning' }}">
                                    {{ $verificationStatus['completion_percentage'] }}%
                                </span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar {{ $verificationStatus['completion_percentage'] >= 100 ? 'bg-success' : 'bg-warning' }}" 
                                     role="progressbar" style="width: {{ $verificationStatus['completion_percentage'] }}%"></div>
                            </div>
                        </div>

                        <!-- Verification Steps -->
                        <div class="verification-steps">
                            @foreach($verificationStatus['verification_steps'] as $step)
                            <div class="d-flex align-items-center mb-3 p-2 rounded {{ $step['status'] === 'completed' ? 'bg-success-transparent' : 'bg-warning-transparent' }}">
                                <div class="me-3">
                                    <div class="avatar avatar-sm {{ $step['status'] === 'completed' ? 'bg-success' : 'bg-warning' }} text-white rounded-circle">
                                        <i class="{{ $step['icon'] }} fs-14"></i>
                                    </div>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="mb-1 fw-semibold">{{ $step['title'] }}</h6>
                                    <p class="mb-0 fs-12 text-muted">{{ $step['description'] }}</p>
                                </div>
                                <div>
                                    @if($step['status'] === 'completed')
                                        <i class="fe fe-check-circle text-success fs-18"></i>
                                    @else
                                        <i class="fe fe-clock text-warning fs-18"></i>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Action Buttons -->
                        @if(!empty($verificationStatus['required_actions']))
                        <div class="alert alert-info">
                            <h6 class="alert-heading fw-semibold">
                                <i class="fe fe-info me-2"></i>Action Required
                            </h6>
                            <p class="mb-0">Complete the following to unlock all features:</p>
                            <ul class="mb-0 mt-2">
                                @foreach($verificationStatus['required_actions'] as $action)
                                <li>{{ $action['description'] }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Quick Verification Actions -->
                        <div class="row g-2">
                            @if(!$user->is_email_verified)
                            <div class="col-12 col-sm-6 mb-2">
                                <button class="btn btn-primary btn-sm w-100" onclick="initiateEmailVerification()">
                                    <i class="fe fe-mail me-1"></i>Verify Email
                                </button>
                            </div>
                            @endif

                            @if(!$user->is_sms_verified)
                            <div class="col-12 col-sm-6 mb-2">
                                <button class="btn btn-warning btn-sm w-100" data-bs-toggle="modal" data-bs-target="#phoneVerificationModal">
                                    <i class="fe fe-phone me-1"></i>Verify Phone
                                </button>
                            </div>
                            @endif

                            @if($user->is_email_verified && $user->is_sms_verified && !$user->is_kyc_verified)
                            <div class="col-12 mb-2">
                                <a href="{{ route('member.kyc.index') }}" class="btn btn-info btn-sm w-100">
                                    <i class="fe fe-file-text me-1"></i>Complete KYC
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Account Restrictions (if not fully verified) -->
                @if(!$verificationStatus['can_withdraw'] || !$verificationStatus['can_transfer'])
                <div class="card custom-card border-danger">
                    <div class="card-header bg-danger-transparent">
                        <div class="card-title text-danger">
                            <i class="fe fe-alert-triangle me-2"></i>Account Restrictions
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger">
                            <h6 class="fw-semibold mb-2">Limited Access</h6>
                            <ul class="mb-0">
                                @if(!$verificationStatus['can_withdraw'])
                                <li><strong>Withdrawals:</strong> Complete verification to enable withdrawals</li>
                                @endif
                                @if(!$verificationStatus['can_transfer'])
                                <li><strong>Transfers:</strong> Complete verification to enable transfers</li>
                                @endif
                            </ul>
                        </div>
                        <p class="text-muted mb-0 fs-12">
                            <i class="fe fe-info me-1"></i>
                            Your account safety is our priority. Complete verification to access all features.
                        </p>
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-zap me-2"></i>Quick Actions
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="fe fe-lock me-2"></i>Change Password
                            </button>
                            <button class="btn btn-success" onclick="downloadProfile()">
                                <i class="fe fe-download me-2"></i>Download Profile
                            </button>
                            <button class="btn btn-info" onclick="shareProfile()">
                                <i class="fe fe-share-2 me-2"></i>Share Profile
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="col-12 col-lg-8 col-xl-8">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-user me-2"></i>Personal Information
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Success Message -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fe fe-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Error Message -->
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fe fe-x-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Validation Errors -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fe fe-alert-triangle me-2"></i>
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('member.profile.update') }}" id="profileForm">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" name="name" value="{{ $user && is_object($user) ? ($user->name ?? '') : '' }}" required>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" name="email" value="{{ $user && is_object($user) ? ($user->email ?? '') : '' }}" required>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" name="phone" value="{{ $user && is_object($user) ? ($user->phone ?? '') : '' }}">
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    @php
                                        $dateOfBirth = '';
                                        if ($user && is_object($user) && $user->date_of_birth) {
                                            // Debug: Log what we're getting
                                            error_log('Date of birth raw value: ' . var_export($user->date_of_birth, true));
                                            error_log('Date of birth type: ' . gettype($user->date_of_birth));
                                            
                                            // Handle different date formats
                                            if ($user->date_of_birth instanceof \Carbon\Carbon) {
                                                $dateOfBirth = $user->date_of_birth->format('Y-m-d');
                                                error_log('Carbon date formatted: ' . $dateOfBirth);
                                            } elseif (is_string($user->date_of_birth)) {
                                                try {
                                                    $carbonDate = \Carbon\Carbon::parse($user->date_of_birth);
                                                    $dateOfBirth = $carbonDate->format('Y-m-d');
                                                    error_log('String date parsed and formatted: ' . $dateOfBirth);
                                                } catch (\Exception $e) {
                                                    $dateOfBirth = $user->date_of_birth; // Use as-is if parsing fails
                                                    error_log('Date parsing failed, using raw: ' . $dateOfBirth);
                                                }
                                            }
                                        } else {
                                            error_log('No date of birth found for user');
                                        }
                                    @endphp
                                    <input type="date" class="form-control" name="date_of_birth" value="{{ $dateOfBirth }}">
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Gender</label>
                                    <select class="form-select" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ ($user && is_object($user) && ($user->gender ?? '') == 'male') ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ ($user && is_object($user) && ($user->gender ?? '') == 'female') ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ ($user && is_object($user) && ($user->gender ?? '') == 'other') ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Country</label>
                                    <select class="form-select" name="country" id="country">
                                        <option value="">Select Country</option>
                                        <option value="Bangladesh" {{ ($user && is_object($user) && ($user->country ?? '') == 'Bangladesh') ? 'selected' : ($user && is_object($user) && empty($user->country) ? 'selected' : '') }}>Bangladesh</option>
                                        <option value="US" {{ ($user && is_object($user) && ($user->country ?? '') == 'US') ? 'selected' : '' }}>United States</option>
                                        <option value="UK" {{ ($user && is_object($user) && ($user->country ?? '') == 'UK') ? 'selected' : '' }}>United Kingdom</option>
                                        <option value="CA" {{ ($user && is_object($user) && ($user->country ?? '') == 'CA') ? 'selected' : '' }}>Canada</option>
                                        <option value="AU" {{ ($user && is_object($user) && ($user->country ?? '') == 'AU') ? 'selected' : '' }}>Australia</option>
                                        <option value="IN" {{ ($user && is_object($user) && ($user->country ?? '') == 'IN') ? 'selected' : '' }}>India</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3" id="districtGroup" style="{{ ($user && is_object($user) && ($user->country ?? 'Bangladesh') != 'Bangladesh') ? 'display: none;' : '' }}">
                                    <label class="form-label">District</label>
                                    <select class="form-select" name="district" id="district">
                                        <option value="">Select District</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3" id="upazilaGroup" style="{{ ($user && is_object($user) && ($user->country ?? 'Bangladesh') != 'Bangladesh') ? 'display: none;' : '' }}">
                                    <label class="form-label">Upazila</label>
                                    <select class="form-select" name="upazila" id="upazila">
                                        <option value="">Select Upazila</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3" id="unionGroup" style="{{ ($user && is_object($user) && ($user->country ?? 'Bangladesh') != 'Bangladesh') ? 'display: none;' : '' }}">
                                    <label class="form-label">Union/Ward</label>
                                    <select class="form-select" name="union_ward" id="union_ward">
                                        <option value="">Select Union/Ward</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" name="address" rows="3">{{ $user && is_object($user) ? ($user->address ?? '') : '' }}</textarea>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="city" value="{{ $user && is_object($user) ? ($user->city ?? '') : '' }}">
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Postal Code</label>
                                    <input type="text" class="form-control" name="postal_code" value="{{ $user && is_object($user) ? ($user->postal_code ?? '') : '' }}">
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="fw-semibold mb-3">Business Information</h6>
                            <div class="row g-3">
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Referral Code</label>
                                    <input type="text" class="form-control" value="{{ $user && is_object($user) ? ($user->referral_code ?? $user->id ?? 'N/A') : 'N/A' }}" readonly>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Sponsor</label>
                                    @php
                                        $sponsorName = 'No Sponsor';
                                        if ($user && is_object($user)) {
                                            try {
                                                if (isset($user->sponsor) && is_object($user->sponsor) && isset($user->sponsor->name)) {
                                                    $sponsorName = $user->sponsor->name;
                                                } elseif (isset($user->sponsor_id) && $user->sponsor_id) {
                                                    // Fallback: try to get sponsor by ID
                                                    $sponsor = \App\Models\User::find($user->sponsor_id);
                                                    $sponsorName = $sponsor ? $sponsor->name : 'Sponsor ID: ' . $user->sponsor_id;
                                                }
                                            } catch (\Exception $e) {
                                                $sponsorName = 'No Sponsor';
                                            }
                                        }
                                    @endphp
                                    <input type="text" class="form-control" value="{{ $sponsorName }}" readonly>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Bio/Description</label>
                                    <textarea class="form-control" name="bio" rows="3" placeholder="Tell us about yourself...">{{ $bio }}</textarea>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="fw-semibold mb-3">Notification Preferences</h6>
                            <div class="row g-3">
                                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="email_notifications" id="emailNotifications" {{ $emailNotifications ? 'checked' : '' }}>
                                        <label class="form-check-label" for="emailNotifications">
                                            Email Notifications
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="sms_notifications" id="smsNotifications" {{ $smsNotifications ? 'checked' : '' }}>
                                        <label class="form-check-label" for="smsNotifications">
                                            SMS Notifications
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="marketing_emails" id="marketingEmails" {{ $marketingEmails ? 'checked' : '' }}>
                                        <label class="form-check-label" for="marketingEmails">
                                            Marketing Emails
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="newsletter" id="newsletter" {{ $newsletter ? 'checked' : '' }}>
                                        <label class="form-check-label" for="newsletter">
                                            Newsletter Subscription
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="button" class="btn btn-light me-2" onclick="resetForm()">Reset</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fe fe-save me-2"></i>Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Avatar Upload Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Change Profile Picture</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="avatarUploadForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-3">
                        @php
                            // Complex avatar image handling for modal preview
                            $previewUrl = 'admin-assets/images/users/default.jpg';
                            
                            if ($user && is_object($user) && $user->avatar) {
                                $avatarData = $user->avatar;
                                
                                // Handle complex nested structure from HandlesImageUploads trait
                                if (is_string($avatarData)) {
                                    $decodedAvatar = json_decode($avatarData, true);
                                    if (is_array($decodedAvatar)) {
                                        $avatarData = $decodedAvatar;
                                    }
                                }
                                
                                // Check for complex image structure
                                if (is_array($avatarData)) {
                                    // First try sizes structure (HandlesImageUploads format)
                                    if (isset($avatarData['sizes']['medium']['storage_url'])) {
                                        $previewUrl = $avatarData['sizes']['medium']['storage_url'];
                                    } elseif (isset($avatarData['sizes']['original']['storage_url'])) {
                                        $previewUrl = $avatarData['sizes']['original']['storage_url'];
                                    } elseif (isset($avatarData['sizes']['large']['storage_url'])) {
                                        $previewUrl = $avatarData['sizes']['large']['storage_url'];
                                    } elseif (isset($avatarData['urls']['medium'])) {
                                        $previewUrl = $avatarData['urls']['medium'];
                                    } elseif (isset($avatarData['urls']['original'])) {
                                        $previewUrl = $avatarData['urls']['original'];
                                    } elseif (isset($avatarData['url']) && is_string($avatarData['url'])) {
                                        $previewUrl = $avatarData['url'];
                                    } elseif (isset($avatarData['path']) && is_string($avatarData['path'])) {
                                        $previewUrl = asset('storage/' . $avatarData['path']);
                                    }
                                } elseif (is_string($avatarData)) {
                                    // Simple string path - check if it exists or is a URL
                                    if (str_starts_with($avatarData, 'http')) {
                                        $previewUrl = $avatarData;
                                    } elseif (Storage::disk('public')->exists($avatarData)) {
                                        $previewUrl = 'storage/' . $avatarData;
                                    } else {
                                        $previewUrl = asset('storage/' . $avatarData);
                                    }
                                }
                            }
                        @endphp
                        <img src="{{ asset($previewUrl) }}" alt="preview" class="avatar avatar-xl avatar-rounded" id="imagePreview">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Choose Image</label>
                        <input type="file" class="form-control" accept="image/*" id="avatarInput" name="avatar" required>
                    </div>
                    <div class="text-muted small">
                        <ul class="mb-0">
                            <li>Maximum file size: 2MB</li>
                            <li>Allowed formats: JPG, PNG, GIF</li>
                            <li>Recommended size: 400x400 pixels</li>
                        </ul>
                    </div>
                    <div id="uploadProgress" class="progress mt-3" style="display: none;">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="uploadBtn">
                        <span class="btn-text">Upload</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Change Password</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="passwordForm">
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="changePassword()">Change Password</button>
            </div>
        </div>
    </div>
</div>

<!-- Phone Verification Modal -->
<div class="modal fade" id="phoneVerificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Phone Verification</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="phoneVerificationStep1">
                    <div class="text-center mb-3">
                        <div class="avatar avatar-xl bg-primary-transparent mb-3">
                            <i class="fe fe-phone fs-24 text-primary"></i>
                        </div>
                        <h6>Verify Your Phone Number</h6>
                        <p class="text-muted">We'll send a 6-digit verification code to your phone number:</p>
                        <p class="fw-semibold">{{ $user->phone ?? 'No phone number added' }}</p>
                    </div>
                    
                    @if(!$user->phone)
                    <div class="alert alert-warning">
                        <i class="fe fe-alert-triangle me-2"></i>
                        Please add a phone number to your profile first.
                    </div>
                    @endif
                </div>
                
                <div id="phoneVerificationStep2" style="display: none;">
                    <div class="text-center mb-3">
                        <div class="avatar avatar-xl bg-success-transparent mb-3">
                            <i class="fe fe-message-square fs-24 text-success"></i>
                        </div>
                        <h6>Enter Verification Code</h6>
                        <p class="text-muted">Enter the 6-digit code sent to {{ $user->phone }}</p>
                    </div>
                    
                    <form id="phoneVerificationForm">
                        <div class="mb-3">
                            <label class="form-label">Verification Code</label>
                            <input type="text" class="form-control text-center fs-18" name="verification_code" 
                                   maxlength="6" placeholder="000000" style="letter-spacing: 0.5em;">
                            <div class="form-text">Code expires in <span id="codeTimer">10:00</span></div>
                        </div>
                    </form>
                    
                    <div class="text-center">
                        <button type="button" class="btn btn-link btn-sm" onclick="resendPhoneVerification()" id="resendBtn" disabled>
                            Resend Code <span id="resendTimer">(60s)</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="initiatePhoneVerification()" id="sendCodeBtn">
                    <span class="btn-text">Send Code</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
                <button type="button" class="btn btn-success d-none" onclick="verifyPhoneCode()" id="verifyCodeBtn">
                    <span class="btn-text">Verify Code</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Email Verification Success Modal -->
<div class="modal fade" id="emailVerificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Email Verification</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="avatar avatar-xl bg-info-transparent mb-3">
                    <i class="fe fe-mail fs-24 text-info"></i>
                </div>
                <h6>Check Your Email</h6>
                <p class="text-muted">
                    We've sent a verification link to <strong>{{ $user->email }}</strong>. 
                    Please check your email and click the verification link.
                </p>
                <div class="alert alert-info">
                    <small>
                        <i class="fe fe-info me-1"></i>
                        Didn't receive the email? Check your spam folder or click resend below.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="resendEmailVerification()">
                    Resend Email
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Enhanced Responsive Profile Styles */
.profile-avatar {
    position: relative;
    display: inline-block;
}

.avatar-edit-btn {
    position: absolute;
    bottom: 0;
    right: 0;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.profile-stats {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.form-check-switch .form-check-input {
    width: 2.5rem;
    height: 1.25rem;
}

.form-check-switch .form-check-input:checked {
    background-color: #6c5ce7;
    border-color: #6c5ce7;
}

/* Mobile Responsive Improvements */
@media (max-width: 768px) {
    .page-header-breadcrumb {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 1rem;
    }
    
    .profile-stats .col-4 {
        text-align: center;
        margin-bottom: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .verification-steps .d-flex {
        padding: 0.75rem !important;
    }
    
    .form-check-switch {
        margin-bottom: 1rem;
    }
    
    .avatar-xxl {
        width: 80px !important;
        height: 80px !important;
    }
    
    .fs-18 {
        font-size: 1.1rem !important;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .card {
        margin-bottom: 1rem;
    }
    
    .row.g-4 > * {
        margin-bottom: 1rem;
    }
    
    .btn-sm {
        font-size: 0.8rem;
        padding: 0.25rem 0.75rem;
    }
    
    .profile-avatar {
        margin-bottom: 1rem !important;
    }
    
    /* Stack verification buttons vertically on very small screens */
    .verification-actions .row .col-sm-6 {
        margin-bottom: 0.5rem;
    }
}

/* Enhanced Card Styles */
.custom-card {
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 0.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: box-shadow 0.2s ease;
}

.custom-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Progress Bar Enhancements */
.progress {
    height: 8px;
    border-radius: 4px;
}

.progress-bar {
    border-radius: 4px;
}

/* Form Improvements */
.form-control, .form-select {
    border-radius: 0.375rem;
    border: 1px solid #d1d5db;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #6c5ce7;
    box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.25);
}

/* Alert Improvements */
.alert {
    border-radius: 0.5rem;
    border: none;
}

.alert-success {
    background-color: rgba(34, 197, 94, 0.1);
    color: #166534;
}

.alert-danger {
    background-color: rgba(239, 68, 68, 0.1);
    color: #991b1b;
}

.alert-info {
    background-color: rgba(59, 130, 246, 0.1);
    color: #1e40af;
}

.alert-warning {
    background-color: rgba(245, 158, 11, 0.1);
    color: #92400e;
}
</style>
@endpush

@push('scripts')
<script>
// Preview image on file selection
document.getElementById('avatarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Validate file size (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire('Error', 'File size must be less than 2MB', 'error');
            this.value = '';
            return;
        }

        // Validate file type
        if (!file.type.startsWith('image/')) {
            Swal.fire('Error', 'Please select a valid image file', 'error');
            this.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Handle avatar upload form submission
document.getElementById('avatarUploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const fileInput = document.getElementById('avatarInput');
    const uploadBtn = document.getElementById('uploadBtn');
    const btnText = uploadBtn.querySelector('.btn-text');
    const spinner = uploadBtn.querySelector('.spinner-border');
    const progressBar = document.getElementById('uploadProgress');
    
    if (!fileInput.files[0]) {
        Swal.fire('Error', 'Please select an image file', 'error');
        return;
    }

    // Show loading state
    uploadBtn.disabled = true;
    btnText.textContent = 'Uploading...';
    spinner.classList.remove('d-none');
    progressBar.style.display = 'block';

    const formData = new FormData();
    formData.append('avatar', fileInput.files[0]);
    formData.append('_token', '{{ csrf_token() }}');

    // Upload with progress
    const xhr = new XMLHttpRequest();
    
    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            const percentComplete = (e.loaded / e.total) * 100;
            progressBar.querySelector('.progress-bar').style.width = percentComplete + '%';
        }
    });

    xhr.onload = function() {
        // Reset UI state
        uploadBtn.disabled = false;
        btnText.textContent = 'Upload';
        spinner.classList.add('d-none');
        progressBar.style.display = 'none';
        
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Update profile avatar
                    const profileAvatar = document.getElementById('profileAvatar');
                    if (profileAvatar) {
                        profileAvatar.src = response.avatar_url;
                    }
                    
                    // Update header avatar if exists
                    const headerAvatars = document.querySelectorAll('img[alt*="avatar"], img[alt*="profile"]');
                    headerAvatars.forEach(img => {
                        if (img.src.includes('users/') || img.src.includes('avatars/')) {
                            img.src = response.avatar_url;
                        }
                    });

                    // Close modal and show success
                    const modal = bootstrap.Modal.getInstance(document.getElementById('avatarModal'));
                    modal.hide();
                    
                    Swal.fire('Success', response.message, 'success');
                    
                    // Reset form
                    fileInput.value = '';
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Invalid response from server', 'error');
            }
        } else {
            Swal.fire('Error', 'Upload failed. Please try again.', 'error');
        }
    };

    xhr.onerror = function() {
        // Reset UI state
        uploadBtn.disabled = false;
        btnText.textContent = 'Upload';
        spinner.classList.add('d-none');
        progressBar.style.display = 'none';
        
        Swal.fire('Error', 'Network error. Please check your connection.', 'error');
    };

    xhr.open('POST', '{{ route("member.profile.avatar") }}');
    xhr.send(formData);
});

function changePassword() {
    const form = document.getElementById('passwordForm');
    const formData = new FormData(form);
    
    if (formData.get('new_password') !== formData.get('confirm_password')) {
        Swal.fire('Error', 'New passwords do not match', 'error');
        return;
    }

    // Here you would implement the actual password change logic
    Swal.fire('Success', 'Password changed successfully!', 'success');
    document.querySelector('#changePasswordModal .btn-close').click();
    form.reset();
}

function resetForm() {
    document.getElementById('profileForm').reset();
}

function downloadProfile() {
    Swal.fire('Info', 'Profile download feature coming soon!', 'info');
}

function shareProfile() {
    Swal.fire('Info', 'Profile sharing feature coming soon!', 'info');
}

// Location data management
let bangladeshLocations = [];

// Load Bangladesh location data
async function loadLocationData() {
    try {
        const response = await fetch('/data/bangladesh-locations.json');
        bangladeshLocations = await response.json();
        populateDistricts();
        
        // If user has existing location data, populate the dropdowns
        const currentDistrict = '{{ $user && is_object($user) ? ($user->district ?? "") : "" }}';
        const currentUpazila = '{{ $user && is_object($user) ? ($user->upazila ?? "") : "" }}';
        const currentUnionWard = '{{ $user && is_object($user) ? ($user->union_ward ?? "") : "" }}';
        
        if (currentDistrict) {
            setTimeout(() => {
                document.getElementById('district').value = currentDistrict;
                populateUpazilas();
                
                if (currentUpazila) {
                    setTimeout(() => {
                        document.getElementById('upazila').value = currentUpazila;
                        populateUnions();
                        
                        if (currentUnionWard) {
                            setTimeout(() => {
                                document.getElementById('union_ward').value = currentUnionWard;
                            }, 100);
                        }
                    }, 100);
                }
            }, 100);
        }
    } catch (error) {
        console.error('Error loading location data:', error);
    }
}

function populateDistricts() {
    const districtSelect = document.getElementById('district');
    districtSelect.innerHTML = '<option value="">Select District</option>';
    
    // Sort districts alphabetically
    const sortedDistricts = bangladeshLocations.slice().sort((a, b) => 
        a.name.localeCompare(b.name, 'en', { sensitivity: 'base' })
    );
    
    sortedDistricts.forEach(district => {
        const option = document.createElement('option');
        option.value = district.name;
        option.textContent = district.name;
        districtSelect.appendChild(option);
    });
}

function populateUpazilas() {
    const districtSelect = document.getElementById('district');
    const upazilaSelect = document.getElementById('upazila');
    const unionSelect = document.getElementById('union_ward');
    
    upazilaSelect.innerHTML = '<option value="">Select Upazila</option>';
    unionSelect.innerHTML = '<option value="">Select Union/Ward</option>';
    
    const selectedDistrict = districtSelect.value;
    if (!selectedDistrict) return;
    
    const district = bangladeshLocations.find(d => d.name === selectedDistrict);
    if (district && district.upazilas) {
        // Sort upazilas alphabetically
        const sortedUpazilas = district.upazilas.slice().sort((a, b) => 
            a.name.localeCompare(b.name, 'en', { sensitivity: 'base' })
        );
        
        sortedUpazilas.forEach(upazila => {
            const option = document.createElement('option');
            option.value = upazila.name;
            option.textContent = upazila.name;
            upazilaSelect.appendChild(option);
        });
    }
}

function populateUnions() {
    const districtSelect = document.getElementById('district');
    const upazilaSelect = document.getElementById('upazila');
    const unionSelect = document.getElementById('union_ward');
    
    unionSelect.innerHTML = '<option value="">Select Union/Ward</option>';
    
    const selectedDistrict = districtSelect.value;
    const selectedUpazila = upazilaSelect.value;
    
    if (!selectedDistrict || !selectedUpazila) return;
    
    const district = bangladeshLocations.find(d => d.name === selectedDistrict);
    if (district && district.upazilas) {
        const upazila = district.upazilas.find(u => u.name === selectedUpazila);
        if (upazila && upazila.unions) {
            // Sort unions with natural sorting for ward numbers
            const sortedUnions = upazila.unions.slice().sort((a, b) => {
                // Check if both are ward numbers
                const aIsWard = a.toLowerCase().includes('ward');
                const bIsWard = b.toLowerCase().includes('ward');
                
                if (aIsWard && bIsWard) {
                    // Extract ward numbers for proper numerical sorting
                    const aNum = parseInt(a.match(/\d+/));
                    const bNum = parseInt(b.match(/\d+/));
                    return aNum - bNum;
                } else {
                    // Regular alphabetical sorting for non-ward items
                    return a.localeCompare(b, 'en', { sensitivity: 'base' });
                }
            });
            
            sortedUnions.forEach(union => {
                const option = document.createElement('option');
                option.value = union;
                option.textContent = union;
                unionSelect.appendChild(option);
            });
        }
    }
}

// Event listeners for location dropdowns
document.getElementById('country').addEventListener('change', function() {
    const districtGroup = document.getElementById('districtGroup');
    const upazilaGroup = document.getElementById('upazilaGroup');
    const unionGroup = document.getElementById('unionGroup');
    
    if (this.value === 'Bangladesh') {
        districtGroup.style.display = 'block';
        upazilaGroup.style.display = 'block';
        unionGroup.style.display = 'block';
        loadLocationData();
    } else {
        districtGroup.style.display = 'none';
        upazilaGroup.style.display = 'none';
        unionGroup.style.display = 'none';
        
        // Clear location dropdowns
        document.getElementById('district').innerHTML = '<option value="">Select District</option>';
        document.getElementById('upazila').innerHTML = '<option value="">Select Upazila</option>';
        document.getElementById('union_ward').innerHTML = '<option value="">Select Union/Ward</option>';
    }
});

document.getElementById('district').addEventListener('change', populateUpazilas);
document.getElementById('upazila').addEventListener('change', populateUnions);

// Initialize location data if Bangladesh is selected or no country is selected
document.addEventListener('DOMContentLoaded', function() {
    const countrySelect = document.getElementById('country');
    if (countrySelect.value === 'Bangladesh' || !countrySelect.value) {
        loadLocationData();
    }
    
    // Reset phone verification modal on close
    const phoneModal = document.getElementById('phoneVerificationModal');
    if (phoneModal) {
        phoneModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('phoneVerificationStep1').style.display = 'block';
            document.getElementById('phoneVerificationStep2').style.display = 'none';
            document.getElementById('sendCodeBtn').classList.remove('d-none');
            document.getElementById('verifyCodeBtn').classList.add('d-none');
            document.getElementById('phoneVerificationForm').reset();
            
            if (phoneVerificationTimer) {
                clearInterval(phoneVerificationTimer);
            }
            if (resendTimer) {
                clearInterval(resendTimer);
            }
        });
    }
});

// Verification system variables
let phoneVerificationTimer = null;
let resendTimer = null;

// Email Verification Functions
function initiateEmailVerification() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
    
    fetch('{{ route("verification.send") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Verification email sent!', 'success');
            const modal = new bootstrap.Modal(document.getElementById('emailVerificationModal'));
            modal.show();
        } else {
            showAlert(data.message || 'Error sending verification email', 'error');
        }
    })
    .catch(error => {
        showAlert('Network error occurred', 'error');
        console.error('Error:', error);
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function resendEmailVerification() {
    initiateEmailVerification();
}

// Phone Verification Functions
function initiatePhoneVerification() {
    @if(!$user->phone)
        showAlert('Please add a phone number to your profile first', 'warning');
        return;
    @endif
    
    const button = document.getElementById('sendCodeBtn');
    const spinner = button.querySelector('.spinner-border');
    const text = button.querySelector('.btn-text');
    
    button.disabled = true;
    spinner.classList.remove('d-none');
    text.textContent = 'Sending...';
    
    fetch('{{ route("member.phone.verify.send") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Verification code sent!', 'success');
            // Switch to step 2
            document.getElementById('phoneVerificationStep1').style.display = 'none';
            document.getElementById('phoneVerificationStep2').style.display = 'block';
            button.classList.add('d-none');
            document.getElementById('verifyCodeBtn').classList.remove('d-none');
            
            // Start timers
            startCodeTimer();
            startResendTimer();
        } else {
            showAlert(data.message || 'Error sending verification code', 'error');
        }
    })
    .catch(error => {
        showAlert('Network error occurred', 'error');
        console.error('Error:', error);
    })
    .finally(() => {
        button.disabled = false;
        spinner.classList.add('d-none');
        text.textContent = 'Send Code';
    });
}

function verifyPhoneCode() {
    const form = document.getElementById('phoneVerificationForm');
    const formData = new FormData(form);
    const code = formData.get('verification_code');
    
    if (!code || code.length !== 6) {
        showAlert('Please enter a valid 6-digit code', 'warning');
        return;
    }
    
    const button = document.getElementById('verifyCodeBtn');
    const spinner = button.querySelector('.spinner-border');
    const text = button.querySelector('.btn-text');
    
    button.disabled = true;
    spinner.classList.remove('d-none');
    text.textContent = 'Verifying...';
    
    fetch('{{ route("member.phone.verify.confirm") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ verification_code: code })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Phone verified successfully!', 'success');
            document.getElementById('phoneVerificationModal').querySelector('.btn-close').click();
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert(data.message || 'Invalid verification code', 'error');
        }
    })
    .catch(error => {
        showAlert('Network error occurred', 'error');
        console.error('Error:', error);
    })
    .finally(() => {
        button.disabled = false;
        spinner.classList.add('d-none');
        text.textContent = 'Verify Code';
    });
}

function resendPhoneVerification() {
    const button = document.getElementById('resendBtn');
    button.disabled = true;
    
    fetch('{{ route("member.phone.verify.send") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Verification code resent!', 'success');
            startCodeTimer();
            startResendTimer();
        } else {
            showAlert(data.message || 'Error resending code', 'error');
        }
    })
    .catch(error => {
        showAlert('Network error occurred', 'error');
        console.error('Error:', error);
    });
}

// Timer Functions
function startCodeTimer() {
    let timeLeft = 600; // 10 minutes
    const timerElement = document.getElementById('codeTimer');
    
    if (phoneVerificationTimer) {
        clearInterval(phoneVerificationTimer);
    }
    
    phoneVerificationTimer = setInterval(() => {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeLeft <= 0) {
            clearInterval(phoneVerificationTimer);
            timerElement.textContent = 'Expired';
        }
        timeLeft--;
    }, 1000);
}

function startResendTimer() {
    let timeLeft = 60; // 1 minute
    const button = document.getElementById('resendBtn');
    const timerSpan = document.getElementById('resendTimer');
    
    button.disabled = true;
    
    if (resendTimer) {
        clearInterval(resendTimer);
    }
    
    resendTimer = setInterval(() => {
        timerSpan.textContent = `(${timeLeft}s)`;
        
        if (timeLeft <= 0) {
            clearInterval(resendTimer);
            button.disabled = false;
            timerSpan.textContent = '';
        }
        timeLeft--;
    }, 1000);
}

// Update Profile Completion
function updateProfileCompletion(percentage) {
    const progressBar = document.querySelector('.profile-completion .progress-bar');
    const percentageText = document.querySelector('.completion-percentage');
    
    if (progressBar) {
        progressBar.style.width = percentage + '%';
        progressBar.setAttribute('aria-valuenow', percentage);
    }
    
    if (percentageText) {
        percentageText.textContent = percentage + '%';
    }
}

// Alert Function
function showAlert(message, type = 'info') {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.custom-alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create alert element
    const alertClass = {
        success: 'alert-success',
        error: 'alert-danger',
        warning: 'alert-warning',
        info: 'alert-info'
    }[type] || 'alert-info';
    
    const alertIcon = {
        success: 'fe-check-circle',
        error: 'fe-x-circle', 
        warning: 'fe-alert-triangle',
        info: 'fe-info'
    }[type] || 'fe-info';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible custom-alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fe ${alertIcon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        const alert = document.querySelector('.custom-alert');
        if (alert) alert.remove();
    }, 5000);
}

// Form submission
document.getElementById('profileForm').addEventListener('submit', function(e) {
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    
    // Don't prevent default, let the form submit normally
    // Note: The SweetAlert will show, then the page will submit normally
    setTimeout(() => {
        Swal.fire({
            title: 'Saving...',
            text: 'Please wait while we update your profile',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }, 100);
    
    // Reset button state after a delay in case form submission fails
    setTimeout(() => {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }, 10000); // Reset after 10 seconds if no response
});
</script>
@endpush
