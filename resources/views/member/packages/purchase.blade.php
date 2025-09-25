@extends('member.layouts.app')

@section('title', 'Activate Package')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Activate Package</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.packages.index') }}">Packages</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Activate Package</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="row">
            <!-- Package Details Card -->
            <div class="col-xl-8 col-lg-7">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            {{ $plan->name }} Package
                        </div>
                        <div class="badge bg-success bg-opacity-25 text-success">
                            {{ $plan->minimum_points ?? $plan->points_reward ?? 100 }} Points
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Package Name</label>
                                    <p class="form-control-plaintext">{{ $plan->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Required Points</label>
                                    <p class="form-control-plaintext text-primary">{{ $plan->minimum_points ?? $plan->points_reward ?? 100 }} Points</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Available Points</label>
                                    <p class="form-control-plaintext text-{{ ($pointStatus['reserve_points'] ?? 0) >= ($plan->minimum_points ?? $plan->points_reward ?? 100) ? 'success' : 'danger' }}">
                                        {{ number_format($pointStatus['reserve_points'] ?? 0) }} Points
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Points After Activation</label>
                                    <p class="form-control-plaintext text-info">
                                        {{ number_format(max(0, ($pointStatus['reserve_points'] ?? 0) - ($plan->minimum_points ?? $plan->points_reward ?? 100))) }} Points
                                    </p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <p class="form-control-plaintext">{{ $plan->description ?? 'Point-based package for MLM system activation' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Payout Period</label>
                                    <p class="form-control-plaintext">{{ $plan->time ?? 30 }} Days</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <span class="badge bg-{{ ($pointStatus['reserve_points'] ?? 0) >= ($plan->minimum_points ?? $plan->points_reward ?? 100) ? 'success' : 'warning' }}">
                                        {{ ($pointStatus['reserve_points'] ?? 0) >= ($plan->minimum_points ?? $plan->points_reward ?? 100) ? 'Can Activate' : 'Insufficient Points' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Point Status Overview -->
                        <div class="alert alert-info">
                            <h6><i class="bx bx-info-circle"></i> Point-Based Activation</h6>
                            <div class="row text-center">
                                <div class="col-4">
                                    <strong class="text-primary">{{ number_format($pointStatus['reserve_points'] ?? 0) }}</strong><br>
                                    <small class="text-muted">Available Points</small>
                                </div>
                                <div class="col-4">
                                    <strong class="text-warning">{{ number_format($plan->minimum_points ?? $plan->points_reward ?? 100) }}</strong><br>
                                    <small class="text-muted">Required Points</small>
                                </div>
                                <div class="col-4">
                                    <strong class="text-info">{{ number_format(max(0, ($pointStatus['reserve_points'] ?? 0) - ($plan->minimum_points ?? $plan->points_reward ?? 100))) }}</strong><br>
                                    <small class="text-muted">Remaining Points</small>
                                </div>
                            </div>
                        </div>
                        
                        @if($plan->terms && $plan->terms != '')
                        <div class="alert alert-info">
                            <h6>Terms & Conditions:</h6>
                            <p class="mb-0">{!! nl2br(e($plan->terms)) !!}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Activation Summary Card -->
            <div class="col-xl-4 col-lg-5">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Activation Summary</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('member.packages.store') }}" method="POST" id="activationForm">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            
                            <div class="mb-3">
                                <label class="form-label">Package</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-package"></i></span>
                                    <input type="text" class="form-control" value="{{ $plan->name }}" readonly>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Required Points</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-coin"></i></span>
                                    <input type="text" class="form-control" value="{{ number_format($plan->minimum_points ?? $plan->points_reward ?? 100) }} Points" readonly>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Available Points</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-wallet"></i></span>
                                    <input type="text" class="form-control text-{{ ($pointStatus['reserve_points'] ?? 0) >= ($plan->minimum_points ?? $plan->points_reward ?? 100) ? 'success' : 'danger' }}" value="{{ number_format($pointStatus['reserve_points'] ?? 0) }} Points" readonly>
                                </div>
                            </div>
                            
                            @if(($pointStatus['reserve_points'] ?? 0) < ($plan->minimum_points ?? $plan->points_reward ?? 100))
                            <div class="alert alert-warning">
                                <i class="bx bx-error-circle me-2"></i>
                                <strong>Insufficient Points!</strong><br>
                                You need {{ number_format(($plan->minimum_points ?? $plan->points_reward ?? 100) - ($pointStatus['reserve_points'] ?? 0)) }} more points to activate this package.
                                <br><small>Purchase more products to earn additional points.</small>
                            </div>
                            @endif
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                    <label class="form-check-label" for="agreeTerms">
                                        I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms and conditions</a>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg" {{ ($pointStatus['reserve_points'] ?? 0) < ($plan->minimum_points ?? $plan->points_reward ?? 100) ? 'disabled' : '' }}>
                                    <i class="bx bx-check-circle me-2"></i>
                                    {{ ($pointStatus['reserve_points'] ?? 0) >= ($plan->minimum_points ?? $plan->points_reward ?? 100) ? 'Activate Package' : 'Insufficient Points' }}
                                </button>
                                <a href="{{ route('member.packages.index') }}" class="btn btn-secondary">
                                    <i class="bx bx-arrow-back me-2"></i>
                                    Back to Packages
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Current Packages Info -->
                @if($userPackages->isNotEmpty())
                <div class="card custom-card mt-3">
                    <div class="card-header">
                        <div class="card-title">Your Current Packages</div>
                    </div>
                    <div class="card-body">
                        @foreach($userPackages as $package)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary bg-opacity-25 text-primary">{{ $package->package_tier }} Points</span>
                            <small class="text-muted">{{ $package->points_remaining }} points remaining</small>
                        </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total Packages:</strong>
                            <strong>{{ $userPackages->count() }}</strong>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Point-Based Package Activation Terms:</h6>
                <ul>
                    <li>Packages can only be activated using accumulated points from product purchases</li>
                    <li>You can only activate packages higher than your current highest tier</li>
                    <li>Points will be deducted from your reserve points upon activation</li>
                    <li>Activated points become active points and start earning commissions immediately</li>
                    <li>Points will be invalidated after payout processing</li>
                    <li>Payout eligibility is based on the package duration ({{ $plan->time ?? 30 }} days)</li>
                    <li>Binary matching calculations will differ based on package tier</li>
                    <li>Package activation is non-refundable once completed</li>
                    <li>Commission distribution follows the existing MLM structure</li>
                    <li>You must have sufficient reserve points to activate any package</li>
                </ul>
                
                @if($plan->terms && $plan->terms != '')
                <h6>Specific Package Terms:</h6>
                <p>{!! nl2br(e($plan->terms)) !!}</p>
                @endif
                
                <div class="alert alert-warning">
                    <strong>Important:</strong> By activating this package, you acknowledge that you understand the MLM system and agree to abide by all company policies.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const activationForm = document.getElementById('activationForm');
    const agreeTerms = document.getElementById('agreeTerms');
    
    activationForm.addEventListener('submit', function(e) {
        if (!agreeTerms.checked) {
            e.preventDefault();
            alert('Please agree to the terms and conditions before proceeding.');
            return false;
        }
        
        // Confirm activation
        if (!confirm('Are you sure you want to activate this package? This action cannot be undone.')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Enhanced form styling
    const submitBtn = activationForm.querySelector('button[type="submit"]');
    activationForm.addEventListener('submit', function() {
        submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-2"></i>Processing...';
        submitBtn.disabled = true;
    });
});
</script>
@endsection
