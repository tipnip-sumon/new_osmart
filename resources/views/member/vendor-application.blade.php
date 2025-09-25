@extends('member.layouts.app')

@section('title', 'Vendor Application - ' . config('app.name'))

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div>
                <h1 class="page-title fw-semibold fs-18 mb-0">Vendor Application</h1>
                <div class="">
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Vendor Application</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="row mb-4">
                <div class="col-xl-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bx bx-check-circle me-2"></i>
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="row mb-4">
                <div class="col-xl-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bx bx-error-circle me-2"></i>
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="row mb-4">
                <div class="col-xl-12">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bx bx-error me-2"></i>
                        <strong>Warning!</strong> {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="row mb-4">
                <div class="col-xl-12">
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bx bx-info-circle me-2"></i>
                        <strong>Info!</strong> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        @if($existingApplication)
            <!-- Existing Application Status -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="bx bx-info-circle me-2"></i>Application Status
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning" role="alert">
                                <i class="bx bx-time-five me-2"></i>
                                <strong>Application Pending Review</strong>
                                <p class="mb-2 mt-2">You have already submitted a vendor application on {{ date('M d, Y', strtotime($existingApplication->applied_at)) }}.</p>
                                <p class="mb-0">Your application is currently under review by our admin team. You will be notified via email once your application has been processed.</p>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary mb-3">Application Details:</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Business Name:</strong> {{ $existingApplication->business_name }}</li>
                                        <li><strong>Contact Person:</strong> {{ $existingApplication->contact_person }}</li>
                                        <li><strong>Applied On:</strong> {{ date('M d, Y H:i A', strtotime($existingApplication->applied_at)) }}</li>
                                        <li><strong>Status:</strong> <span class="badge bg-warning">{{ ucfirst($existingApplication->status) }}</span></li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-info mb-3">What's Next?</h6>
                                    <ul class="list-unstyled">
                                        <li><i class="bx bx-check text-success me-2"></i>Application submitted successfully</li>
                                        <li><i class="bx bx-time text-warning me-2"></i>Under admin review (1-3 business days)</li>
                                        <li><i class="bx bx-envelope text-muted me-2"></i>Email notification upon decision</li>
                                        <li><i class="bx bx-store text-muted me-2"></i>Store setup upon approval</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Application Form -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="bx bx-store me-2"></i>Apply to Become a Vendor
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info" role="alert">
                                <i class="bx bx-info-circle me-2"></i>
                                <strong>Welcome {{ $user->name }}!</strong> As an affiliate member, you can apply to become a vendor and unlock store management features.
                            </div>

                            <form action="{{ route('member.vendor-application.submit') }}" method="POST">
                                @csrf
                                
                                <!-- Store Information -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">Business Information</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="business_name" class="form-label">Business Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="business_name" name="business_name" 
                                                   value="{{ old('business_name') }}" required>
                                            @error('business_name')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contact_person" class="form-label">Contact Person <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="contact_person" name="contact_person" 
                                                   value="{{ old('contact_person', $user->name) }}" required>
                                            @error('contact_person')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="{{ old('email', $user->email) }}" required>
                                            @error('email')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control" id="phone" name="phone" 
                                                   value="{{ old('phone', $user->phone) }}" required>
                                            @error('phone')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="business_description" class="form-label">Business Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="business_description" name="business_description" 
                                                      rows="6" placeholder="Tell us about your business, products/services you plan to offer, your business experience, expected monthly sales volume, etc." required>{{ old('business_description') }}</textarea>
                                            @error('business_description')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="website" class="form-label">Website (Optional)</label>
                                            <input type="url" class="form-control" id="website" name="website" 
                                                   value="{{ old('website') }}" placeholder="https://example.com">
                                            @error('website')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Current Status Info -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="alert alert-light" role="alert">
                                            <h6 class="fw-bold mb-3">Your Current Status:</h6>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <strong>Role:</strong> {{ ucfirst($user->role) }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Points:</strong> {{ number_format($user->points ?? 0) }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Member Since:</strong> {{ $user->created_at->format('M Y') }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Username:</strong> {{ $user->username }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('member.dashboard') }}" class="btn btn-light">
                                                <i class="bx bx-arrow-back me-1"></i>Back to Dashboard
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bx bx-send me-1"></i>Submit Application
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
    
    // Auto-hide success messages after 5 seconds
    const successAlerts = document.querySelectorAll('.alert-success');
    successAlerts.forEach(alert => {
        setTimeout(() => {
            if (alert && alert.classList.contains('show')) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 300);
            }
        }, 5000);
    });
    
    // Auto-hide info messages after 8 seconds
    const infoAlerts = document.querySelectorAll('.alert-info:not(.alert-static)');
    infoAlerts.forEach(alert => {
        if (!alert.closest('.card-body')) { // Don't auto-hide alerts inside forms
            setTimeout(() => {
                if (alert && alert.classList.contains('show')) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 300);
                }
            }, 8000);
        }
    });
});
</script>
@endsection