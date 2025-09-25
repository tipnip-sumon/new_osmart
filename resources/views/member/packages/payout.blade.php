@extends('member.layouts.app')

@section('title', 'Package Payout')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Package Payout</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.packages.index') }}">Packages</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payout</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="row">
            <!-- Eligible Packages -->
            <div class="col-xl-8 col-lg-7">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Packages Eligible for Payout
                        </div>
                        <div class="badge bg-success-transparent">
                            {{ $eligiblePackages->count() }} Package{{ $eligiblePackages->count() != 1 ? 's' : '' }}
                        </div>
                    </div>
                    <div class="card-body">
                        @if($eligiblePackages->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table text-nowrap table-bordered">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Package</th>
                                            <th>Tier</th>
                                            <th>Points Remaining</th>
                                            <th>Investment</th>
                                            <th>Activated</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($eligiblePackages as $package)
                                        <tr>
                                            <td>{{ $package->plan->name ?? 'Package' }}</td>
                                            <td>
                                                <span class="badge bg-primary-transparent">{{ $package->package_tier }}</span>
                                            </td>
                                            <td>
                                                <span class="text-success fw-semibold">{{ number_format($package->points_remaining) }}</span>
                                            </td>
                                            <td>৳{{ number_format($package->amount_invested, 2) }}</td>
                                            <td>{{ $package->activated_at ? $package->activated_at->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-success">Eligible</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Payout Summary -->
                            <div class="alert alert-info mt-3">
                                <h6 class="alert-heading">Payout Information:</h6>
                                <ul class="mb-0">
                                    <li><strong>Total Points Available:</strong> {{ number_format($eligiblePackages->sum('points_remaining')) }}</li>
                                    <li><strong>Total Investment:</strong> ৳{{ number_format($eligiblePackages->sum('amount_invested'), 2) }}</li>
                                    <li>Processing payout will invalidate these points as per company policy</li>
                                    <li>This action cannot be undone once processed</li>
                                </ul>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bx bx-info-circle text-muted" style="font-size: 3rem;"></i>
                                <h5 class="text-muted mt-3">No Packages Eligible for Payout</h5>
                                <p class="text-muted">
                                    You currently don't have any packages that are eligible for payout. 
                                    Packages become eligible based on their activation date and duration.
                                </p>
                                <a href="{{ route('member.packages.index') }}" class="btn btn-primary">
                                    <i class="bx bx-package me-2"></i>
                                    View All Packages
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Payout Action -->
            <div class="col-xl-4 col-lg-5">
                @if($eligiblePackages->isNotEmpty())
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Process Payout</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('member.packages.process-payout') }}" method="POST" id="payoutForm">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Total Eligible Points</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-coin"></i></span>
                                    <input type="text" class="form-control" value="{{ number_format($eligiblePackages->sum('points_remaining')) }}" readonly>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Total Investment</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="text" class="form-control" value="{{ number_format($eligiblePackages->sum('amount_invested'), 2) }}" readonly>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Packages Count</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-package"></i></span>
                                    <input type="text" class="form-control" value="{{ $eligiblePackages->count() }}" readonly>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning">
                                <small>
                                    <strong>Warning:</strong> Processing payout will mark these points as used and packages as completed. This action cannot be reversed.
                                </small>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="confirmPayout" required>
                                    <label class="form-check-label" for="confirmPayout">
                                        I understand that this will invalidate my points after payout
                                    </label>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bx bx-dollar-circle me-2"></i>
                                    Process Payout
                                </button>
                                <a href="{{ route('member.packages.index') }}" class="btn btn-secondary">
                                    <i class="bx bx-arrow-back me-2"></i>
                                    Back to Packages
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
                
                <!-- Payout History -->
                <div class="card custom-card mt-3">
                    <div class="card-header">
                        <div class="card-title">Recent Payouts</div>
                    </div>
                    <div class="card-body">
                        @php
                            $recentPayouts = $user->packageHistories()
                                ->where('action_type', 'payout')
                                ->with('plan:id,name')
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp
                        
                        @if($recentPayouts->isNotEmpty())
                            @foreach($recentPayouts as $payout)
                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                <div>
                                    <small class="text-muted">{{ $payout->plan->name ?? 'Package' }}</small><br>
                                    <small class="text-success">{{ abs($payout->points_changed) }} points</small>
                                </div>
                                <small class="text-muted">{{ $payout->created_at->format('M d') }}</small>
                            </div>
                            @endforeach
                            <div class="text-center mt-2">
                                <a href="{{ route('member.packages.history') }}" class="btn btn-sm btn-outline-primary">
                                    View All History
                                </a>
                            </div>
                        @else
                            <p class="text-muted text-center">No payout history yet</p>
                        @endif
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
    // Form validation
    const payoutForm = document.getElementById('payoutForm');
    const confirmPayout = document.getElementById('confirmPayout');
    
    if (payoutForm) {
        payoutForm.addEventListener('submit', function(e) {
            if (!confirmPayout.checked) {
                e.preventDefault();
                alert('Please confirm that you understand the payout terms before proceeding.');
                return false;
            }
            
            // Final confirmation
            if (!confirm('Are you sure you want to process payout for all eligible packages? This action cannot be undone and will invalidate your points.')) {
                e.preventDefault();
                return false;
            }
        });
        
        // Enhanced form styling
        const submitBtn = payoutForm.querySelector('button[type="submit"]');
        payoutForm.addEventListener('submit', function() {
            submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-2"></i>Processing...';
            submitBtn.disabled = true;
        });
    }
});
</script>
@endsection
