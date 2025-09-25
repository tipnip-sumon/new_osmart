@extends('layouts.app')

@section('title', 'Edit Profile - ' . config('app.name'))

@push('styles')
<style>
.edit-profile-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
}

.form-floating > .form-control {
    padding: 1rem 0.75rem;
}

.form-floating > label {
    padding: 1rem 0.75rem;
}

.profile-avatar-edit {
    width: 120px;
    height: 120px;
    border: 4px solid #e9ecef;
    border-radius: 50%;
    object-fit: cover;
}

.avatar-upload {
    position: relative;
    display: inline-block;
}

.avatar-upload-btn {
    position: absolute;
    bottom: 0;
    right: 0;
    background: #007bff;
    color: white;
    border: 2px solid white;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.avatar-upload-btn:hover {
    background: #0056b3;
    transform: scale(1.1);
}

.avatar-upload input[type="file"] {
    display: none;
}

@media (max-width: 768px) {
    .profile-avatar-edit {
        width: 100px;
        height: 100px;
    }
    
    .avatar-upload-btn {
        width: 35px;
        height: 35px;
    }
}
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary me-3">
                    <i class="ti ti-arrow-left me-1"></i>Back
                </a>
                <h2 class="h3 mb-0">Edit Profile</h2>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti ti-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-exclamation-triangle me-2"></i>Please correct the errors below.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Profile Edit Form -->
            <div class="edit-profile-card card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ti ti-user me-2"></i>Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Picture -->
                        <div class="text-center mb-4">
                            <div class="avatar-upload">
                                <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('assets/img/default-avatar.svg') }}" 
                                     alt="Profile" class="profile-avatar-edit" id="profilePreview">
                                <label for="profile_image" class="avatar-upload-btn" title="Change Profile Picture">
                                    <i class="ti ti-camera"></i>
                                </label>
                                <input type="file" id="profile_image" name="profile_image" accept="image/*" onchange="previewImage(this)">
                            </div>
                            <p class="text-muted small mt-2">Click the camera icon to change your profile picture</p>
                            @error('profile_image')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- First Name -->
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" 
                                           placeholder="First Name" required>
                                    <label for="first_name">First Name</label>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" 
                                           placeholder="Last Name" required>
                                    <label for="last_name">Last Name</label>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Email (readonly) -->
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" value="{{ $user->email }}" 
                                   placeholder="Email Address" readonly>
                            <label for="email">Email Address</label>
                            <div class="form-text">Email cannot be changed. Contact support if needed.</div>
                        </div>

                        <!-- Phone -->
                        <div class="form-floating mb-3">
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                                   placeholder="Phone Number">
                            <label for="phone">Phone Number</label>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="form-floating mb-4">
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" placeholder="Address" 
                                      style="height: 100px">{{ old('address', $user->address) }}</textarea>
                            <label for="address">Address</label>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i>Update Profile
                            </button>
                            <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-x me-1"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('profilePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Profile edit page loaded successfully');
});
</script>
@endpush
