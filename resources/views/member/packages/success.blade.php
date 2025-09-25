@extends('member.layouts.app')

@section('title', 'Package Activation Successful')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Activation Successful</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.packages.index') }}">Packages</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Success</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <!-- Success Card -->
                <div class="card custom-card border-success">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="bx bx-check-circle text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h2 class="text-success mb-3">Package Activated Successfully!</h2>
                        <p class="text-muted mb-4">
                            Congratulations! Your package has been activated using your accumulated points. Points are now active and earning commissions.
                        </p>
                        
                        <!-- Success Details -->
                        <div class="row">
                            <div class="col-md-6 mx-auto">
                                <div class="card bg-success bg-opacity-10 border-success">
                                    <div class="card-body">
                                        <h5 class="card-title text-success mb-3">Package Details</h5>
                                        <div class="row text-start">
                                            <div class="col-6">
                                                <strong>Package:</strong>
                                            </div>
                                            <div class="col-6">
                                                {{ $successData['package_name'] ?? 'Package' }}
                                            </div>
                                            <div class="col-6">
                                                <strong>Package Tier:</strong>
                                            </div>
                                            <div class="col-6">
                                                {{ $successData['package_tier'] ?? 0 }} Points
                                            </div>
                                            <div class="col-6">
                                                <strong>Points Used:</strong>
                                            </div>
                                            <div class="col-6">
                                                {{ $successData['points_used'] ?? 0 }} Points
                                            </div>
                                            <div class="col-6">
                                                <strong>Points Now Active:</strong>
                                            </div>
                                            <div class="col-6">
                                                {{ $successData['points_activated'] ?? 0 }} Points
                                            </div>
                                            <div class="col-6">
                                                <strong>Next Payout:</strong>
                                            </div>
                                            <div class="col-6">
                                                {{ $successData['next_payout_date'] ? \Carbon\Carbon::parse($successData['next_payout_date'])->format('M d, Y') : 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Summary -->
                        <div class="row mt-4">
                            <div class="col-md-8 mx-auto">
                                <div class="card bg-primary bg-opacity-10 border-primary">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary mb-3">Your Account Summary</h5>
                                        <div class="row text-start">
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <strong>Reserve Points:</strong>
                                                    <span class="float-end">{{ number_format($user->reserve_points ?? 0) }}</span>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Active Points:</strong>
                                                    <span class="float-end">{{ number_format($user->active_points ?? 0) }}</span>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Total Packages:</strong>
                                                    <span class="float-end">{{ $user->activePackages()->count() ?? 0 }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <strong>Current Tier:</strong>
                                                    <span class="float-end">{{ $user->current_package_tier ?? 0 }} Points</span>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Total Points Earned:</strong>
                                                    <span class="float-end">{{ number_format($user->total_points_earned ?? 0) }}</span>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Points Used for Packages:</strong>
                                                    <span class="float-end">{{ number_format($user->points_used_for_packages ?? 0) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="mt-4">
                            <a href="{{ route('member.packages.index') }}" class="btn btn-primary btn-lg me-3">
                                <i class="bx bx-package me-2"></i>
                                View My Packages
                            </a>
                            <a href="{{ route('member.dashboard') }}" class="btn btn-secondary btn-lg">
                                <i class="bx bx-home me-2"></i>
                                Go to Dashboard
                            </a>
                        </div>
                        
                        <!-- Next Steps -->
                        <div class="alert alert-info mt-4 text-start">
                            <h6 class="alert-heading">What's Next?</h6>
                            <ul class="mb-0">
                                <li>Your points are now active and earning commissions</li>
                                <li>Purchase more products to accumulate additional points</li>
                                <li>Activate higher tier packages for increased earnings</li>
                                <li>Monitor your payout eligibility in the Packages section</li>
                                <li>Share your referral link to earn additional commissions</li>
                                <li>Points will be invalidated after payout processing</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-redirect to packages page after 30 seconds (optional)
    // setTimeout(() => {
    //     if (confirm('Would you like to view your packages now?')) {
    //         window.location.href = '{{ route("member.packages.index") }}';
    //     }
    // }, 30000);
    
    // Celebration effect (optional)
    if (typeof confetti !== 'undefined') {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });
    }
});
</script>
@endsection
