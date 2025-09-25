@extends('member.layouts.app')

@section('title', 'My Packages')

@section('content')
<div class="container-fluid">
    <!-- Package Summary Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Package Management System</h4>
                    <p class="card-subtitle">Multiple packages with upgrade restrictions - Higher tier packages only</p>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="bx bx-info-circle"></i> Point-Based Package Activation System</h5>
                        <ul class="mb-0">
                            <li><strong>Point-Based Activation:</strong> Packages can only be activated using accumulated points from product purchases</li>
                            <li><strong>Reserve Points:</strong> Points earned from products are stored as reserve points for package activation</li>
                            <li><strong>Active Points:</strong> Points become active after package activation and start earning commissions</li>
                            <li><strong>Upgrade Restrictions:</strong> Can only activate packages with higher point values than your current highest tier</li>
                            <li><strong>Payout System:</strong> Points are invalidated after payout processing</li>
                            <li><strong>Multiple Packages:</strong> You can have multiple active packages simultaneously</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User's Current Point Status -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ number_format($pointStatus['reserve_points'] ?? 0) }}</h4>
                            <p class="card-text">Available Points</p>
                            <small>For package activation</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bx bx-coin-stack fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ number_format($pointStatus['active_points'] ?? 0) }}</h4>
                            <p class="card-text">Active Points</p>
                            <small>Earning commissions</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bx bx-trophy fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ number_format($pointStatus['total_points_earned'] ?? 0) }}</h4>
                            <p class="card-text">Total Points Earned</p>
                            <small>From product purchases</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bx bx-coin fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ number_format($pointStatus['points_used_for_packages'] ?? 0) }}</h4>
                            <p class="card-text">Points Activated</p>
                            <small>Used for packages</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bx bx-package fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Packages for Purchase -->
    @if(isset($availablePackages) && $availablePackages->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">All Available Packages</h5>
                    <p class="card-subtitle text-muted">All packages are shown - green means you can activate now, yellow means you need more points</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($availablePackages as $package)
                        @php
                            $requiredPoints = $package->minimum_points ?? $package->points_reward ?? 100;
                            $canAfford = ($pointStatus['reserve_points'] ?? 0) >= $requiredPoints;
                            $pointsShort = $canAfford ? 0 : ($requiredPoints - ($pointStatus['reserve_points'] ?? 0));
                        @endphp
                        <div class="col-md-4 mb-3">
                            <div class="card {{ $canAfford ? 'border-success' : 'border-warning' }}">
                                <div class="card-header {{ $canAfford ? 'bg-success' : 'bg-warning' }} text-white">
                                    <h6 class="card-title mb-0">{{ $package->name }}</h6>
                                    @if(!$canAfford)
                                        <small class="d-block">Need {{ number_format($pointsShort) }} more points</small>
                                    @else
                                        <small class="d-block">✓ Can activate now</small>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h4 class="{{ $canAfford ? 'text-success' : 'text-warning' }}">{{ number_format($requiredPoints) }} Points</h4>
                                    <p class="text-muted">Package Value: ৳{{ number_format($package->fixed_amount ?? $package->minimum ?? 100, 2) }}</p>
                                    
                                    @if($package->description)
                                    <p class="card-text small">{{ Str::limit($package->description, 80) }}</p>
                                    @endif
                                    
                                    <!-- Package Benefits -->
                                    <div class="mb-3">
                                        <small class="text-muted">Package Benefits:</small>
                                        <ul class="list-unstyled small mt-1">
                                            <li><i class="bx bx-check text-success"></i> {{ number_format($requiredPoints) }} Active Points</li>
                                            <li><i class="bx bx-check text-success"></i> Commission Eligibility</li>
                                            <li><i class="bx bx-check text-success"></i> Binary Matching</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="mt-3">
                                        @if($canAfford)
                                            <a href="{{ route('member.packages.purchase', $package->id) }}" class="btn btn-success btn-sm w-100">
                                                <i class="bx bx-check-circle"></i> Activate Package
                                            </a>
                                        @else
                                            <button class="btn btn-warning btn-sm w-100" disabled>
                                                <i class="bx bx-lock"></i> Need {{ number_format($pointsShort) }} More Points
                                            </button>
                                            <small class="text-muted d-block mt-2">
                                                <a href="{{ route('member.direct-point-purchase.index') }}" class="text-decoration-none">
                                                    <i class="bx bx-plus-circle"></i> Purchase more points
                                                </a>
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning">
                <h5><i class="bx bx-error-circle"></i> No Packages Available</h5>
                <p class="mb-0">
                    @if($user->current_package_tier)
                        You have reached the highest package tier available. No higher tier packages are currently available for activation.
                    @else
                        No packages are currently available for activation. Please contact support for assistance.
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Active Packages -->
    @if(isset($activePackages) && $activePackages->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">My Active Packages</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Package</th>
                                    <th>Tier</th>
                                    <th>Invested</th>
                                    <th>Points Allocated</th>
                                    <th>Points Remaining</th>
                                    <th>Total Payout</th>
                                    <th>Status</th>
                                    <th>Next Payout</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activePackages as $package)
                                <tr>
                                    <td>{{ $package->plan->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $package->package_tier }} Points</span>
                                    </td>
                                    <td>{{ $package->formatted_amount_invested }}</td>
                                    <td>{{ number_format($package->points_allocated) }}</td>
                                    <td>{{ number_format($package->points_remaining) }}</td>
                                    <td>{{ $package->formatted_total_payout }}</td>
                                    <td>
                                        @if($package->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($package->next_payout_eligible_at)
                                            {{ $package->next_payout_eligible_at->format('d-m-Y') }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Eligible Packages for Payout -->
    @if(isset($eligiblePackages) && $eligiblePackages->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">Packages Eligible for Payout</h5>
                </div>
                <div class="card-body">
                    <p>You have {{ $eligiblePackages->count() }} package(s) eligible for payout.</p>
                    <a href="{{ route('member.packages.payout') }}" class="btn btn-success">
                        <i class="bx bx-money"></i> Process Payout
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Package History -->
    @if(isset($packageHistory) && $packageHistory->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Recent Package Activity</h5>
                    <a href="{{ route('member.packages.history') }}" class="btn btn-outline-primary btn-sm">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Action</th>
                                    <th>Package</th>
                                    <th>Amount</th>
                                    <th>Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($packageHistory->take(5) as $history)
                                <tr>
                                    <td>{{ $history->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $history->action_type == 'purchase' ? 'primary' : ($history->action_type == 'payout' ? 'success' : 'info') }}">
                                            {{ $history->action_type_display }}
                                        </span>
                                    </td>
                                    <td>{{ $history->plan->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($history->amount_paid > 0)
                                            {{ $history->formatted_amount_paid }}
                                        @elseif($history->payout_amount > 0)
                                            {{ $history->formatted_payout_amount }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($history->points_acquired) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-refresh package summary every 30 seconds
    setInterval(function() {
        if (typeof updatePackageSummary === 'function') {
            updatePackageSummary();
        }
    }, 30000);
});

function updatePackageSummary() {
    $.get('{{ route("member.packages.summary") }}')
        .done(function(response) {
            if (response.success && response.summary) {
                // Update summary cards if needed
                console.log('Package summary updated:', response.summary);
            }
        })
        .fail(function() {
            console.log('Failed to update package summary');
        });
}
</script>
@endpush
@endsection
