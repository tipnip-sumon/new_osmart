@extends('member.layouts.app')

@section('title', 'Point-Based Matching Calculator')

@section('content')
<div class="page">
    <!-- Start::app-content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h1 class="page-title fw-medium fs-18 mb-2">Point-Based Matching Calculator</h1>
                    <div class="">
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('member.matching.dashboard') }}">Matching Bonus</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Point Calculator</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="btn-list">
                    <a href="{{ route('member.matching.dashboard') }}" class="btn btn-outline-primary">
                        <i class="bx bx-arrow-back me-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>
            <!-- Page Header Close -->

            <!-- Point-Based Calculator -->
            <div class="row">
                <!-- Point Calculator Form -->
                <div class="col-xl-8">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="bx bx-calculator me-2"></i>Calculate Point-Based Matching Bonus
                            </div>
                        </div>
                        <div class="card-body">
                            @php
                                $leftPoints = $legPoints['left_points'];
                                $rightPoints = $legPoints['right_points'];
                            @endphp

                            <!-- Current Point Status -->
                            <div class="alert alert-primary">
                                <h6 class="mb-2">
                                    <i class="bx bx-coin-stack me-1"></i>Your Current Point Status
                                </h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <small class="text-muted">Left Leg Points:</small>
                                        <div class="fw-bold fs-16">{{ number_format($leftPoints, 0) }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Right Leg Points:</small>
                                        <div class="fw-bold fs-16">{{ number_format($rightPoints, 0) }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Active Points:</small>
                                        <div class="fw-bold fs-16">{{ number_format($pointBalance['active_points'], 0) }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Reserve Points:</small>
                                        <div class="fw-bold fs-16">{{ number_format($pointBalance['reserve_points'], 0) }}</div>
                                    </div>
                                </div>
                            </div>

                            <form id="pointCalculatorForm">
                                <div class="row">
                                    <!-- Point Calculator Inputs -->
                                    <div class="col-md-6 mb-3">
                                        <label for="leftPoints" class="form-label">
                                            Left Leg Points <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bx bx-coin"></i>
                                            </span>
                                            <input type="number" class="form-control" id="leftPoints" name="left_points" 
                                                   value="{{ $leftPoints }}" 
                                                   step="1" min="0" required>
                                            <button type="button" class="btn btn-outline-secondary" onclick="useCurrentLeft()">
                                                <i class="bx bx-refresh"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Enter points in your left leg (min 100 to qualify)</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="rightPoints" class="form-label">
                                            Right Leg Points <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bx bx-coin"></i>
                                            </span>
                                            <input type="number" class="form-control" id="rightPoints" name="right_points" 
                                                   value="{{ $rightPoints }}" 
                                                   step="1" min="0" required>
                                            <button type="button" class="btn btn-outline-secondary" onclick="useCurrentRight()">
                                                <i class="bx bx-refresh"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Enter points in your right leg (min 100 to qualify)</small>
                                    </div>

                                    <!-- Point System Info -->
                                    <div class="col-12 mb-3">
                                        <div class="alert alert-info">
                                            <h6 class="mb-2">
                                                <i class="bx bx-info-circle me-1"></i>Point System Rules
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <ul class="list-unstyled mb-0">
                                                        <li><i class="bx bx-check text-success me-1"></i>1 Point = 6 Tk value</li>
                                                        <li><i class="bx bx-check text-success me-1"></i>10% matching rate</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <ul class="list-unstyled mb-0">
                                                        <li><i class="bx bx-check text-success me-1"></i>100 points minimum per leg</li>
                                                        <li><i class="bx bx-check text-success me-1"></i>Auto activation at 100+ points</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quick Point Scenarios -->
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Quick Point Scenarios:</label>
                                        <div class="btn-group flex-wrap" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPointScenario(100, 100)">
                                                100 - 100 Points
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPointScenario(250, 250)">
                                                250 - 250 Points
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPointScenario(500, 500)">
                                                500 - 500 Points
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPointScenario(1000, 1000)">
                                                1K - 1K Points
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetToActual()">
                                                Reset to Current
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Calculate Button -->
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary" id="calculateBtn">
                                            <i class="bx bx-calculator me-1"></i> Calculate Point Bonus
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary ms-2" onclick="clearResults()">
                                            <i class="bx bx-x me-1"></i> Clear Results
                                        </button>
                                    </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Calculation Results -->
                        <div class="card custom-card" id="resultsCard" style="display: none;">
                            <div class="card-header">
                                <div class="card-title">Calculation Results</div>
                            </div>
                            <div class="card-body" id="resultsContent">
                                <!-- Results will be loaded here -->
                            </div>
                        </div>
                    </div>

                    <!-- Info Panel -->
                    <div class="col-xl-4">
                        <!-- Point-Based Qualification -->
                        <div class="card custom-card">
                            <div class="card-header">
                                <div class="card-title">Point-Based Qualification</div>
                            </div>
                            <div class="card-body">
                                @php
                                    $isQualified = $leftPoints >= 100 && $rightPoints >= 100;
                                @endphp
                                
                                <!-- Qualification Status -->
                                <div class="text-center mb-4">
                                    @if($isQualified)
                                        <div class="avatar avatar-lg bg-success-transparent mb-2">
                                            <i class="bx bx-check-circle fs-24 text-success"></i>
                                        </div>
                                        <h5 class="text-success mb-1">QUALIFIED</h5>
                                        <p class="text-muted mb-0">Ready for matching bonus</p>
                                    @else
                                        <div class="avatar avatar-lg bg-warning-transparent mb-2">
                                            <i class="bx bx-x-circle fs-24 text-warning"></i>
                                        </div>
                                        <h5 class="text-warning mb-1">NOT QUALIFIED</h5>
                                        <p class="text-muted mb-0">Build both legs to qualify</p>
                                    @endif
                                </div>

                                <!-- Point Requirements -->
                                <div class="border rounded p-3 mb-3">
                                    <h6 class="mb-3">Requirements:</h6>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted">Left Leg:</span>
                                        <span class="fw-semibold {{ $leftPoints >= 100 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($leftPoints, 0) }} / 100 points
                                            @if($leftPoints >= 100)
                                                <i class="bx bx-check-circle text-success ms-1"></i>
                                            @else
                                                <i class="bx bx-x-circle text-danger ms-1"></i>
                                            @endif
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted">Right Leg:</span>
                                        <span class="fw-semibold {{ $rightPoints >= 100 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($rightPoints, 0) }} / 100 points
                                            @if($rightPoints >= 100)
                                                <i class="bx bx-check-circle text-success ms-1"></i>
                                            @else
                                                <i class="bx bx-x-circle text-danger ms-1"></i>
                                            @endif
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Matching Rate:</span>
                                        <span class="fw-semibold text-primary">10%</span>
                                    </div>
                                </div>

                                <!-- Current Matching Potential -->
                                @php
                                    $matchablePoints = min($leftPoints, $rightPoints);
                                    $pointValue = $matchablePoints * 6;
                                    $potentialBonus = $matchablePoints >= 100 ? ($pointValue * 0.10) : 0;
                                @endphp
                                
                                <div class="bg-light rounded p-3">
                                    <h6 class="text-primary mb-2">Current Matching Potential:</h6>
                                    
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="text-muted">Matchable Points:</small>
                                        <small class="fw-semibold">{{ number_format($matchablePoints, 0) }}</small>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="text-muted">Point Value:</small>
                                        <small class="fw-semibold">৳{{ number_format($pointValue, 2) }}</small>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">Potential Bonus:</small>
                                        <small class="fw-semibold text-primary">৳{{ number_format($potentialBonus, 2) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- How Point Matching Works -->
                        <div class="card custom-card">
                            <div class="card-header">
                                <div class="card-title">How Point Matching Works</div>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item border-0 px-0">
                                        <div class="d-flex">
                                            <div class="avatar avatar-sm bg-primary-transparent me-3">
                                                <i class="bx bx-coin-stack fs-16"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Point Conversion</h6>
                                                <small class="text-muted">1 Point = 6 Tk value for calculation</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="list-group-item border-0 px-0">
                                        <div class="d-flex">
                                            <div class="avatar avatar-sm bg-success-transparent me-3">
                                                <i class="bx bx-git-branch fs-16"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Binary Matching</h6>
                                                <small class="text-muted">Bonus calculated on smaller leg when both have 100+ points</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="list-group-item border-0 px-0">
                                        <div class="d-flex">
                                            <div class="avatar avatar-sm bg-info-transparent me-3">
                                                <i class="bx bx-percentage fs-16"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">10% Matching Rate</h6>
                                                <small class="text-muted">Fixed 10% bonus on matched point value</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="list-group-item border-0 px-0">
                                        <div class="d-flex">
                                            <div class="avatar avatar-sm bg-warning-transparent me-3">
                                                <i class="bx bx-refresh fs-16"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Auto Activation</h6>
                                                <small class="text-muted">Reserve points activate at 100+ automatically</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <a href="{{ route('member.matching.qualifications') }}" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="bx bx-info-circle me-1"></i> View Point Requirements
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End::row-1 -->
        </div>
    </div>
    <!-- End::app-content -->
</div>
@endsection
@push('scripts')
<script>
// Clear service worker cache for matching pages to prevent caching issues
document.addEventListener('DOMContentLoaded', function() {
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.ready.then(function(registration) {
            if (registration.active) {
                // Send message to clear matching page cache
                registration.active.postMessage({
                    type: 'CLEAR_MATCHING_CACHE'
                });
            }
        });
        
        // Also force a hard refresh if page was loaded from cache
        if (performance.navigation.type === 2) {
            // Page was loaded from back/forward cache
            window.location.reload(true);
        }
    }
});

const currentLeftPoints = {{ $leftPoints ?? 0 }};
const currentRightPoints = {{ $rightPoints ?? 0 }};

document.getElementById('pointCalculatorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    calculatePointBonus();
});

function calculatePointBonus() {
    const leftPoints = document.getElementById('leftPoints').value;
    const rightPoints = document.getElementById('rightPoints').value;
    const calculateBtn = document.getElementById('calculateBtn');
    const resultsCard = document.getElementById('resultsCard');
    const resultsContent = document.getElementById('resultsContent');

    // Validate inputs
    if (!leftPoints || !rightPoints) {
        alert('Please enter both left and right leg points');
        return;
    }

    // Show loading
    calculateBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Calculating...';
    calculateBtn.disabled = true;

    // Calculate point-based bonus directly (client-side calculation)
    const leftPointsNum = parseInt(leftPoints);
    const rightPointsNum = parseInt(rightPoints);
    const matchablePoints = Math.min(leftPointsNum, rightPointsNum);
    const isQualified = leftPointsNum >= 100 && rightPointsNum >= 100;
    const pointValue = matchablePoints * 6; // 1 point = 6 Tk
    const bonusAmount = isQualified ? (pointValue * 0.10) : 0; // 10% bonus
    const carryForwardPoints = Math.abs(leftPointsNum - rightPointsNum);
    const carryForwardValue = carryForwardPoints * 6;

    const calculation = {
        left_points: leftPointsNum,
        right_points: rightPointsNum,
        matchable_points: matchablePoints,
        point_value: pointValue,
        bonus_amount: bonusAmount,
        carry_forward_points: carryForwardPoints,
        carry_forward_value: carryForwardValue,
        qualified: isQualified,
        commission_rate: 10
    };

    displayPointResults(calculation);
    resultsCard.style.display = 'block';
    resultsCard.scrollIntoView({ behavior: 'smooth' });

    // Reset button
    calculateBtn.innerHTML = '<i class="bx bx-calculator me-1"></i> Calculate Point Bonus';
    calculateBtn.disabled = false;
}

function displayPointResults(calculation) {
    const resultsContent = document.getElementById('resultsContent');
    
    let qualificationHtml = '';
    if (calculation.qualified) {
        qualificationHtml = `
            <div class="alert alert-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bx bx-check-circle me-2"></i>
                        <strong>Qualified for Point Matching</strong>
                    </div>
                    <span class="badge bg-success">${calculation.commission_rate}% Bonus Rate</span>
                </div>
            </div>
        `;
    } else {
        qualificationHtml = `
            <div class="alert alert-warning">
                <i class="bx bx-info-circle me-2"></i>
                <strong>Not Qualified</strong> - Both legs need minimum 100 points to qualify for matching bonus.
            </div>
        `;
    }

    resultsContent.innerHTML = `
        ${qualificationHtml}
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <h6 class="text-primary mb-1">Left Leg Points</h6>
                        <h4 class="fw-bold mb-0">${Number(calculation.left_points).toLocaleString()}</h4>
                        <small class="text-muted">Value: ৳${Number(calculation.left_points * 6).toLocaleString()}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <h6 class="text-success mb-1">Right Leg Points</h6>
                        <h4 class="fw-bold mb-0">${Number(calculation.right_points).toLocaleString()}</h4>
                        <small class="text-muted">Value: ৳${Number(calculation.right_points * 6).toLocaleString()}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card border-info">
                    <div class="card-body text-center">
                        <h6 class="text-info mb-1">Matchable Points</h6>
                        <h4 class="fw-bold mb-0">${Number(calculation.matchable_points).toLocaleString()}</h4>
                        <small class="text-muted">Smaller leg (Value: ৳${Number(calculation.point_value).toLocaleString()})</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card border-warning">
                    <div class="card-body text-center">
                        <h6 class="text-warning mb-1">Carry Forward</h6>
                        <h4 class="fw-bold mb-0">${Number(calculation.carry_forward_points).toLocaleString()} Points</h4>
                        <small class="text-muted">Unmatched (Value: ৳${Number(calculation.carry_forward_value).toLocaleString()})</small>
                    </div>
                </div>
            </div>
        </div>

        ${calculation.bonus_amount > 0 ? `
            <div class="card bg-success-transparent border-success">
                <div class="card-body text-center">
                    <h5 class="text-success mb-2">🎯 Point-Based Matching Bonus</h5>
                    <h2 class="fw-bold text-success mb-2">৳${Number(calculation.bonus_amount).toLocaleString()}</h2>
                    <p class="mb-1">
                        <span class="badge bg-success">${calculation.commission_rate}% Rate</span>
                        <span class="badge bg-info ms-1">Point System</span>
                    </p>
                    <small class="text-muted">
                        Calculation: ${Number(calculation.matchable_points).toLocaleString()} points × 6 Tk × ${calculation.commission_rate}% = ৳${Number(calculation.bonus_amount).toLocaleString()}
                    </small>
                </div>
            </div>
        ` : `
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="text-muted mb-2">No Bonus Available</h5>
                    <p class="text-muted">
                        ${!calculation.qualified ? 
                            'Both legs need minimum 100 points to qualify.' : 
                            'No matchable points available.'
                        }
                    </p>
                </div>
            </div>
        `}

        <div class="mt-3">
            <div class="card border-info">
                <div class="card-body">
                    <h6 class="text-info mb-2">
                        <i class="bx bx-info-circle me-1"></i>Point System Breakdown
                    </h6>
                    <div class="row text-center">
                        <div class="col-md-3">
                            <small class="text-muted">Point Value</small>
                            <div class="fw-bold">1 Point = 6 Tk</div>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Minimum per Leg</small>
                            <div class="fw-bold">100 Points</div>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Bonus Rate</small>
                            <div class="fw-bold">10%</div>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Qualification</small>
                            <div class="fw-bold ${calculation.qualified ? 'text-success' : 'text-danger'}">
                                ${calculation.qualified ? 'Qualified' : 'Not Qualified'}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3 text-center">
            <small class="text-muted">
                <i class="bx bx-info-circle me-1"></i>
                This calculation is based on the point-based matching system. Points automatically activate when you reach 100+ reserves.
            </small>
        </div>
    `;
}

function setPointScenario(left, right) {
    document.getElementById('leftPoints').value = left;
    document.getElementById('rightPoints').value = right;
    calculatePointBonus();
}

function useCurrentLeft() {
    document.getElementById('leftPoints').value = currentLeftPoints;
}

function useCurrentRight() {
    document.getElementById('rightPoints').value = currentRightPoints;
}

function resetToActual() {
    document.getElementById('leftPoints').value = currentLeftPoints;
    document.getElementById('rightPoints').value = currentRightPoints;
    clearResults();
}

function clearResults() {
    document.getElementById('resultsCard').style.display = 'none';
}

// Auto-calculate when points change (with debounce)
let calculateTimeout;
document.getElementById('leftPoints').addEventListener('input', function() {
    clearTimeout(calculateTimeout);
    calculateTimeout = setTimeout(calculatePointBonus, 1000);
});

document.getElementById('rightPoints').addEventListener('input', function() {
    clearTimeout(calculateTimeout);
    calculateTimeout = setTimeout(calculatePointBonus, 1000);
});
</script>
@endpush
