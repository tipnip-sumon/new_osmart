@extends('member.layouts.app')

@section('title', 'Payout Successful')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Payout Successful</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.packages.index') }}">Packages</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payout Success</li>
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
                        <h2 class="text-success mb-3">Payout Processed Successfully!</h2>
                        <p class="text-muted mb-4">
                            Your package payout has been processed successfully. The points have been invalidated as per company policy.
                        </p>
                        
                        <!-- Payout Details -->
                        <div class="row">
                            <div class="col-md-6 mx-auto">
                                <div class="card bg-success-transparent border-success">
                                    <div class="card-body">
                                        <h5 class="card-title text-success mb-3">Payout Details</h5>
                                        <div class="row text-start">
                                            <div class="col-6">
                                                <strong>Points Processed:</strong>
                                            </div>
                                            <div class="col-6">
                                                {{ number_format($payoutData['points_processed'] ?? 0) }}
                                            </div>
                                            <div class="col-6">
                                                <strong>Packages Completed:</strong>
                                            </div>
                                            <div class="col-6">
                                                {{ $payoutData['packages_processed'] ?? 0 }}
                                            </div>
                                            <div class="col-6">
                                                <strong>Total Investment:</strong>
                                            </div>
                                            <div class="col-6">
                                                ৳{{ number_format($payoutData['total_investment'] ?? 0, 2) }}
                                            </div>
                                            <div class="col-6">
                                                <strong>Processed At:</strong>
                                            </div>
                                            <div class="col-6">
                                                {{ now()->format('M d, Y H:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Summary -->
                        <div class="row mt-4">
                            <div class="col-md-8 mx-auto">
                                <div class="card bg-primary-transparent border-primary">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary mb-3">Updated Account Summary</h5>
                                        <div class="row text-start">
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <strong>Remaining Active Points:</strong>
                                                    <span class="float-end">{{ number_format($user->active_points ?? 0) }}</span>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Active Packages:</strong>
                                                    <span class="float-end">{{ $user->activePackages()->count() ?? 0 }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <strong>Current Tier:</strong>
                                                    <span class="float-end">{{ $user->current_package_tier ?? 0 }}</span>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Total Packages:</strong>
                                                    <span class="float-end">{{ $user->packageHistories()->where('action_type', 'purchase')->count() ?? 0 }}</span>
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
                            <a href="{{ route('member.packages.history') }}" class="btn btn-secondary btn-lg me-3">
                                <i class="bx bx-history me-2"></i>
                                View History
                            </a>
                            <a href="{{ route('member.dashboard') }}" class="btn btn-outline-primary btn-lg">
                                <i class="bx bx-home me-2"></i>
                                Dashboard
                            </a>
                        </div>
                        
                        <!-- Information -->
                        <div class="alert alert-info mt-4 text-start">
                            <h6 class="alert-heading">What Happened?</h6>
                            <ul class="mb-0">
                                <li>Your eligible packages have been marked as completed</li>
                                <li>{{ number_format($payoutData['points_processed'] ?? 0) }} points have been processed for payout</li>
                                <li>These points are now invalidated as per company policy</li>
                                <li>Commission distributions have been calculated and distributed</li>
                                <li>You can still purchase new packages to continue earning</li>
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
    // Celebration effect (optional)
    if (typeof confetti !== 'undefined') {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });
    }
    
    // Auto-redirect suggestion (optional)
    setTimeout(() => {
        // You can uncomment this if you want auto-redirect
        // if (confirm('Would you like to view your packages now?')) {
        //     window.location.href = '{{ route("member.packages.index") }}';
        // }
    }, 10000);
});
</script>
@endsection
