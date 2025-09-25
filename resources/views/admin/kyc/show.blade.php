@extends('admin.layouts.app')
    @section('content')
    <div class="container-fluid">
        <!-- Alert Container for JavaScript alerts -->
        <div class="alert-container"></div>
        
        <!-- Page Header -->
        <div class="row mb-4 my-4">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $pageTitle }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.kyc.index') }}">KYC Management</a></li>
                            <li class="breadcrumb-item active">Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- KYC Details -->
        <div class="row">
            <!-- User Information -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-user"></i> User Information
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            @php
                                // Complex avatar image handling for HandlesImageUploads trait
                                $avatarUrl = asset('assets/images/users/default-avatar.png'); // Default avatar
                                
                                if ($kycVerification->user && is_object($kycVerification->user) && $kycVerification->user->avatar) {
                                    $avatarData = $kycVerification->user->avatar;
                                    
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
                                            $avatarUrl = asset('storage/' . $avatarData);
                                        } else {
                                            $avatarUrl = asset('storage/' . $avatarData);
                                        }
                                    }
                                }
                            @endphp
                            <img src="{{ $avatarUrl }}" 
                                 alt="User Avatar" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                            <h5 class="mt-3 mb-1">{{ $kycVerification->user->firstname ?? 'N/A' }} {{ $kycVerification->user->lastname ?? '' }}</h5>
                            <p class="text-muted mb-0">{{ $kycVerification->user->username ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $kycVerification->user->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $kycVerification->phone_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Registration:</strong></td>
                                    <td>{{ $kycVerification->user->created_at ? $kycVerification->user->created_at->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>KYC Status:</strong></td>
                                    <td>
                                        @if(isset($kycVerification->user->kv))
                                            @if($kycVerification->user->kv == 1)
                                                <span class="badge bg-success">Verified</span>
                                            @elseif($kycVerification->user->kv == 2)
                                                <span class="badge bg-warning">Under Review</span>
                                            @else
                                                <span class="badge bg-danger">Not Verified</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Unknown</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>User ID:</strong></td>
                                    <td>#{{ $kycVerification->user->id ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- KYC Status & Actions -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-shield-alt"></i> KYC Verification Status
                            </h4>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge fs-6
                                    @if($kycVerification->status == 'verified') bg-success
                                    @elseif($kycVerification->status == 'pending') bg-warning  
                                    @elseif($kycVerification->status == 'rejected') bg-danger
                                    @elseif($kycVerification->status == 'under_review') bg-info
                                    @else bg-secondary
                                    @endif
                                ">
                                    {{ ucfirst($kycVerification->status) }}
                                </span>
                                
                                <!-- Quick Status Change Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" 
                                            id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-edit"></i> Change Status
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                                        <li>
                                            <a class="dropdown-item {{ $kycVerification->status == 'pending' ? 'active' : '' }}" 
                                               href="javascript:void(0)" onclick="updateStatus('pending')">
                                                <i class="fas fa-clock text-warning"></i> Pending
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ $kycVerification->status == 'under_review' ? 'active' : '' }}" 
                                               href="javascript:void(0)" onclick="updateStatus('under_review')">
                                                <i class="fas fa-eye text-info"></i> Under Review
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ $kycVerification->status == 'verified' ? 'active' : '' }}" 
                                               href="javascript:void(0)" onclick="updateStatus('verified')">
                                                <i class="fas fa-check-circle text-success"></i> Verified
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ $kycVerification->status == 'rejected' ? 'active' : '' }}" 
                                               href="javascript:void(0)" onclick="updateStatus('rejected')">
                                                <i class="fas fa-times-circle text-danger"></i> Rejected
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td><strong>Document Type:</strong></td>
                                        <td>{{ $kycVerification->document_type ? ucwords(str_replace('_', ' ', $kycVerification->document_type)) : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nationality:</strong></td>
                                        <td>{{ $kycVerification->nationality ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Submitted:</strong></td>
                                        <td>{{ $kycVerification->submitted_at ? $kycVerification->submitted_at->format('M d, Y h:i A') : ($kycVerification->created_at ? $kycVerification->created_at->format('M d, Y h:i A') : 'N/A') }}</td>
                                    </tr>
                                    @if($kycVerification->reviewed_at)
                                    <tr>
                                        <td><strong>Reviewed:</strong></td>
                                        <td>{{ $kycVerification->reviewed_at->format('M d, Y h:i A') }}</td>
                                    </tr>
                                    @endif
                                    @if($kycVerification->reviewed_by)
                                    <tr>
                                        <td><strong>Reviewed By:</strong></td>
                                        <td>Admin #{{ $kycVerification->reviewed_by }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td><strong>Country:</strong></td>
                                        <td>{{ $kycVerification->present_country ?? $kycVerification->permanent_country ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>District:</strong></td>
                                        <td>{{ $kycVerification->present_district ?? $kycVerification->permanent_district ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Upazila:</strong></td>
                                        <td>{{ $kycVerification->present_upazila ?? $kycVerification->permanent_upazila ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Postal Code:</strong></td>
                                        <td>{{ $kycVerification->present_postal_code ?? $kycVerification->permanent_postal_code ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>KYC ID:</strong></td>
                                        <td>#{{ $kycVerification->id }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Address -->
                        @if($kycVerification->present_address || $kycVerification->permanent_address)
                        <div class="mt-3">
                            <strong>Present Address:</strong>
                            @if($kycVerification->present_address)
                            <address class="mt-2 p-3 bg-light rounded">
                                {{ $kycVerification->present_address }}<br>
                                @if($kycVerification->present_upazila || $kycVerification->present_district)
                                {{ $kycVerification->present_upazila ? $kycVerification->present_upazila . ', ' : '' }}{{ $kycVerification->present_district }}<br>
                                @endif
                                @if($kycVerification->present_postal_code)
                                {{ $kycVerification->present_postal_code }}<br>
                                @endif
                                {{ $kycVerification->present_country ?? 'Bangladesh' }}
                            </address>
                            @endif
                            
                            @if($kycVerification->permanent_address && !$kycVerification->same_as_present_address)
                            <strong>Permanent Address:</strong>
                            <address class="mt-2 p-3 bg-light rounded">
                                {{ $kycVerification->permanent_address }}<br>
                                @if($kycVerification->permanent_upazila || $kycVerification->permanent_district)
                                {{ $kycVerification->permanent_upazila ? $kycVerification->permanent_upazila . ', ' : '' }}{{ $kycVerification->permanent_district }}<br>
                                @endif
                                @if($kycVerification->permanent_postal_code)
                                {{ $kycVerification->permanent_postal_code }}<br>
                                @endif
                                {{ $kycVerification->permanent_country ?? 'Bangladesh' }}
                            </address>
                            @endif
                        </div>
                        @endif
                        
                        <!-- Action Buttons -->
                        @if($kycVerification->status == 'pending')
                        <div class="mt-4">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Pending Review:</strong> This KYC verification is awaiting your review. Please examine the documents and take appropriate action.
                            </div>
                            <button type="button" class="btn btn-success me-2" onclick="updateStatus('verified')">
                                <i class="fas fa-check"></i> Approve KYC
                            </button>
                            <button type="button" class="btn btn-danger me-2" onclick="updateStatus('rejected')">
                                <i class="fas fa-times"></i> Reject KYC
                            </button>
                            <button type="button" class="btn btn-warning" onclick="updateStatus('under_review')">
                                <i class="fas fa-eye"></i> Mark Under Review
                            </button>
                        </div>
                        @elseif($kycVerification->status == 'verified')
                        <div class="mt-4">
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <strong>Approved:</strong> This KYC verification has been approved{{ $kycVerification->approved_at ? ' on ' . $kycVerification->approved_at->format('M d, Y h:i A') : '' }}.
                            </div>
                            <button type="button" class="btn btn-warning me-2" onclick="updateStatus('under_review')">
                                <i class="fas fa-eye"></i> Mark Under Review
                            </button>
                            <button type="button" class="btn btn-danger" onclick="updateStatus('rejected')">
                                <i class="fas fa-times"></i> Reject KYC
                            </button>
                        </div>
                        @elseif($kycVerification->status == 'rejected')
                        <div class="mt-4">
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle"></i>
                                <strong>Rejected:</strong> This KYC verification was rejected{{ $kycVerification->rejected_at ? ' on ' . $kycVerification->rejected_at->format('M d, Y h:i A') : '' }}.
                            </div>
                            <button type="button" class="btn btn-success me-2" onclick="updateStatus('verified')">
                                <i class="fas fa-check"></i> Approve KYC
                            </button>
                            <button type="button" class="btn btn-warning" onclick="updateStatus('under_review')">
                                <i class="fas fa-eye"></i> Mark Under Review
                            </button>
                        </div>
                        @elseif($kycVerification->status == 'under_review')
                        <div class="mt-4">
                            <div class="alert alert-info">
                                <i class="fas fa-clock"></i>
                                <strong>Under Review:</strong> This KYC verification is currently under review.
                            </div>
                            <button type="button" class="btn btn-success me-2" onclick="updateStatus('verified')">
                                <i class="fas fa-check"></i> Approve KYC
                            </button>
                            <button type="button" class="btn btn-danger" onclick="updateStatus('rejected')">
                                <i class="fas fa-times"></i> Reject KYC
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Uploaded Documents -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-file-alt"></i> Uploaded Documents
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($kycVerification->document_front_image)
                            <div class="col-xl-4 col-sm-6 mb-4">
                                <div class="card border">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-id-card"></i> Document Front
                                        </h6>
                                    </div>
                                    <div class="card-body p-2 text-center">
                                        @php
                                            $frontExt = strtolower(pathinfo($kycVerification->document_front_image, PATHINFO_EXTENSION));
                                        @endphp
                                        
                                        @if(in_array($frontExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <img src="{{ asset('storage/' . $kycVerification->document_front_image) }}" 
                                                 alt="Document Front" class="img-fluid rounded" style="max-height: 200px; cursor: pointer;"
                                                 onclick="viewImageModal('{{ asset('storage/' . $kycVerification->document_front_image) }}', 'Document Front')">
                                        @else
                                            <div class="p-4" onclick="window.open('{{ route('admin.kyc.document.view', [$kycVerification->id, 'front']) }}', '_blank')" style="cursor: pointer;">
                                                <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                                <p class="mt-2 mb-0">PDF Document</p>
                                                <small class="text-muted">Click to view</small>
                                            </div>
                                        @endif
                                        
                                        <div class="mt-3">
                                            <a href="{{ route('admin.kyc.document.view', [$kycVerification->id, 'front']) }}" 
                                               class="btn btn-sm btn-primary me-1" target="_blank">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('admin.kyc.document.download', [$kycVerification->id, 'front']) }}" 
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($kycVerification->document_back_image)
                            <div class="col-xl-4 col-sm-6 mb-4">
                                <div class="card border">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-id-card"></i> Document Back
                                        </h6>
                                    </div>
                                    <div class="card-body p-2 text-center">
                                        @php
                                            $backExt = strtolower(pathinfo($kycVerification->document_back_image, PATHINFO_EXTENSION));
                                        @endphp
                                        
                                        @if(in_array($backExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <img src="{{ asset('storage/' . $kycVerification->document_back_image) }}" 
                                                 alt="Document Back" class="img-fluid rounded" style="max-height: 200px; cursor: pointer;"
                                                 onclick="viewImageModal('{{ asset('storage/' . $kycVerification->document_back_image) }}', 'Document Back')">
                                        @else
                                            <div class="p-4" onclick="window.open('{{ route('admin.kyc.document.view', [$kycVerification->id, 'back']) }}', '_blank')" style="cursor: pointer;">
                                                <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                                <p class="mt-2 mb-0">PDF Document</p>
                                                <small class="text-muted">Click to view</small>
                                            </div>
                                        @endif
                                        
                                        <div class="mt-3">
                                            <a href="{{ route('admin.kyc.document.view', [$kycVerification->id, 'back']) }}" 
                                               class="btn btn-sm btn-primary me-1" target="_blank">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('admin.kyc.document.download', [$kycVerification->id, 'back']) }}" 
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($kycVerification->user_photo)
                            <div class="col-xl-4 col-sm-6 mb-4">
                                <div class="card border">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-camera"></i> User Photo
                                        </h6>
                                    </div>
                                    <div class="card-body p-2 text-center">
                                        <img src="{{ asset('storage/' . $kycVerification->user_photo) }}" 
                                             alt="User Photo" class="img-fluid rounded" style="max-height: 200px; cursor: pointer;"
                                             onclick="viewImageModal('{{ asset('storage/' . $kycVerification->user_photo) }}', 'User Photo')">
                                        
                                        <div class="mt-3">
                                            <a href="{{ route('admin.kyc.document.view', [$kycVerification->id, 'selfie']) }}" 
                                               class="btn btn-sm btn-primary me-1" target="_blank">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('admin.kyc.document.download', [$kycVerification->id, 'selfie']) }}" 
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($kycVerification->utility_bill)
                            <div class="col-xl-4 col-sm-6 mb-4">
                                <div class="card border">
                                    <div class="card-header bg-warning text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-file-invoice"></i> Utility Bill
                                        </h6>
                                    </div>
                                    <div class="card-body p-2 text-center">
                                        @php
                                            $utilityExt = strtolower(pathinfo($kycVerification->utility_bill, PATHINFO_EXTENSION));
                                        @endphp
                                        
                                        @if(in_array($utilityExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <img src="{{ asset('storage/' . $kycVerification->utility_bill) }}" 
                                                 alt="Utility Bill" class="img-fluid rounded" style="max-height: 200px; cursor: pointer;"
                                                 onclick="viewImageModal('{{ asset('storage/' . $kycVerification->utility_bill) }}', 'Utility Bill')">
                                        @else
                                            <div class="p-4" onclick="window.open('{{ route('admin.kyc.document.view', [$kycVerification->id, 'utility']) }}', '_blank')" style="cursor: pointer;">
                                                <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                                <p class="mt-2 mb-0">PDF Document</p>
                                                <small class="text-muted">Click to view</small>
                                            </div>
                                        @endif
                                        
                                        <div class="mt-3">
                                            <a href="{{ route('admin.kyc.document.view', [$kycVerification->id, 'utility']) }}" 
                                               class="btn btn-sm btn-primary me-1" target="_blank">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('admin.kyc.document.download', [$kycVerification->id, 'utility']) }}" 
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($kycVerification->user_signature)
                            <div class="col-xl-4 col-sm-6 mb-4">
                                <div class="card border">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-signature"></i> Signature
                                        </h6>
                                    </div>
                                    <div class="card-body p-2 text-center">
                                        <img src="{{ asset('storage/' . $kycVerification->user_signature) }}" 
                                             alt="User Signature" class="img-fluid rounded" style="max-height: 200px; cursor: pointer;"
                                             onclick="viewImageModal('{{ asset('storage/' . $kycVerification->user_signature) }}', 'User Signature')">
                                        
                                        <div class="mt-3">
                                            <a href="{{ route('admin.kyc.document.view', [$kycVerification->id, 'signature']) }}" 
                                               class="btn btn-sm btn-primary me-1" target="_blank">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('admin.kyc.document.download', [$kycVerification->id, 'signature']) }}" 
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(!$kycVerification->document_front_image && !$kycVerification->document_back_image && !$kycVerification->user_photo && !$kycVerification->utility_bill && !$kycVerification->user_signature)
                            <div class="col-12">
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                    <h5>No Documents Found</h5>
                                    <p class="mb-0">No documents have been uploaded for this KYC verification.</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Remarks -->
        @if($kycVerification->admin_remarks)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-comment"></i> Admin Remarks
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-quote-left"></i>
                            {{ $kycVerification->admin_remarks }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.kyc.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to KYC List
                    </a>
                    
                    <div>
                        @if($kycVerification->user && $kycVerification->user->email)
                        <a href="{{ route('admin.kyc.index', ['search' => $kycVerification->user->email]) }}" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-search"></i> View User's Other KYC
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusUpdateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusUpdateTitle">Update KYC Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="statusUpdateForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="newStatus" name="status">
                        
                        <div class="mb-3">
                            <label for="adminRemarks" class="form-label">Admin Remarks</label>
                            <textarea class="form-control" id="adminRemarks" name="admin_remarks" 
                                      rows="4" placeholder="Enter your remarks..."></textarea>
                            <small class="text-muted">Provide feedback or reason for your decision</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="confirmStatusUpdate">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image View Modal -->
    <div class="modal fade" id="imageViewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalTitle">Document View</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Document" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')
    <script>
        function updateStatus(status) {
            document.getElementById('newStatus').value = status;
            
            let title = '';
            let buttonText = '';
            let buttonClass = 'btn btn-primary';
            
            switch(status) {
                case 'pending':
                    title = 'Mark KYC as Pending';
                    buttonText = 'Mark Pending';
                    buttonClass = 'btn btn-warning';
                    break;
                case 'verified':
                    title = 'Approve KYC Verification';
                    buttonText = 'Approve';
                    buttonClass = 'btn btn-success';
                    break;
                case 'rejected':
                    title = 'Reject KYC Verification';
                    buttonText = 'Reject';
                    buttonClass = 'btn btn-danger';
                    break;
                case 'under_review':
                    title = 'Mark KYC Under Review';
                    buttonText = 'Update';
                    buttonClass = 'btn btn-warning';
                    break;
                default:
                    title = 'Update KYC Status';
                    buttonText = 'Update';
                    buttonClass = 'btn btn-primary';
                    break;
            }
            
            document.getElementById('statusUpdateTitle').textContent = title;
            document.getElementById('confirmStatusUpdate').textContent = buttonText;
            document.getElementById('confirmStatusUpdate').className = buttonClass;
            
            new bootstrap.Modal(document.getElementById('statusUpdateModal')).show();
        }

        function viewImageModal(imageSrc, title) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModalTitle').textContent = title;
            new bootstrap.Modal(document.getElementById('imageViewModal')).show();
        }

        document.getElementById('statusUpdateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Show loading state
            const submitBtn = document.getElementById('confirmStatusUpdate');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Updating...';
            submitBtn.disabled = true;
            
            fetch('{{ route("admin.kyc.update-status", $kycVerification->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    showAlert('success', "KYC status updated successfully.");
                    
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('statusUpdateModal')).hide();
                    
                    // Reload page after 2 seconds
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    showAlert('danger', 'Error: ' + (data.message || 'Unknown error occurred'));
                }
            })
            .catch(error => {
                showAlert('danger', 'Network error: ' + error.message);
            })
            .finally(() => {
                // Reset button state
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });

        // Auto-hide alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(function(alert) {
            setTimeout(function() {
                if (alert && alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        });

        // Helper function to show alerts
        function showAlert(type, message) {
            // Remove existing alerts
            const existingAlerts = document.querySelectorAll('.alert');
            existingAlerts.forEach(alert => {
                if (alert.classList.contains('alert-success') || alert.classList.contains('alert-danger')) {
                    alert.remove();
                }
            });
            
            // Create new alert
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            // Insert at the top of container - use a more reliable method
            const container = document.querySelector('.container-fluid');
            if (container) {
                // Try to find existing alert containers first
                const existingAlertContainer = container.querySelector('.alert-container');
                if (existingAlertContainer) {
                    existingAlertContainer.appendChild(alertDiv);
                } else {
                    // Create a wrapper div for alerts and insert it at the beginning
                    const alertContainer = document.createElement('div');
                    alertContainer.className = 'alert-container';
                    alertContainer.appendChild(alertDiv);
                    
                    // Insert as first child of container
                    if (container.firstChild) {
                        container.insertBefore(alertContainer, container.firstChild);
                    } else {
                        container.appendChild(alertContainer);
                    }
                }
            }
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                if (alertDiv && alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    </script>
    @endpush