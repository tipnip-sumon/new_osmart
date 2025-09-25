@extends('member.layouts.app')

@section('title', 'Rank Salary Report & Conditions')

@push('styles')
<style>
.text-cyan {
    color: #22d3ee !important;
}

.status-overview-card {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #06b6d4 100%);
    border: none;
    box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);
}

.status-stat {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.status-stat:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-2px);
}

.rank-conditions-table th {
    background: linear-gradient(135deg, #374151 0%, #111827 100%) !important;
    color: #ffffff !important;
    border-color: #374151 !important;
}

.table-success {
    background-color: rgba(34, 197, 94, 0.1) !important;
    border-color: rgba(34, 197, 94, 0.2) !important;
}

.progress-wrapper {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.glass-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
}
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div>
                <h1 class="page-title fw-semibold fs-18 mb-0">Rank Salary Report & Conditions</h1>
                <div class="ms-md-1 ms-0">
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('member.matching.dashboard') }}">Matching Bonus</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Rank Salary Report</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="btn-list">
                <a href="{{ route('member.matching.dashboard') }}" class="btn btn-outline-primary">
                    <i class="bx bx-arrow-back me-1"></i> Back to Dashboard
                </a>
                <a href="{{ route('member.matching.qualifications') }}" class="btn btn-outline-info">
                    <i class="bx bx-list-check me-1"></i> Qualifications
                </a>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Current Status Overview -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card custom-card status-overview-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center">
                                <div class="current-status-icon">
                                    <i class="fe fe-dollar-sign fs-40 text-warning"></i>
                                    <h4 class="text-white mt-2 fw-bold">{{ $userRankData['current_rank'] ? $userRankData['current_rank']->rank_name : 'No Rank' }}</h4>
                                    <p class="text-white-50">Current Rank</p>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="status-stat text-center p-2">
                                            <h3 class="text-warning fw-bold mb-1">৳{{ number_format($userRankData['monthly_salary'] ?? 0) }}</h3>
                                            <p class="text-white-50 mb-0 small">Monthly Salary</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="status-stat text-center p-2">
                                            <h3 class="text-cyan fw-bold mb-1">৳{{ number_format($userRankData['matching_bonus'] ?? 0) }}</h3>
                                            <p class="text-white-50 mb-0 small">This Month Bonus</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="status-stat text-center p-2">
                                            <h3 class="text-success fw-bold mb-1">৳{{ number_format(($userRankData['monthly_salary'] ?? 0) + ($userRankData['matching_bonus'] ?? 0)) }}</h3>
                                            <p class="text-white-50 mb-0 small">Total Monthly</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="status-stat text-center p-2">
                                            @if($userRankData['monthly_qualified'] ?? false)
                                                <h3 class="text-success fw-bold mb-1"><i class="fe fe-check-circle fs-30"></i></h3>
                                                <p class="text-white-50 mb-0 small">Qualified</p>
                                            @else
                                                <h3 class="text-danger fw-bold mb-1"><i class="fe fe-x-circle fs-30"></i></h3>
                                                <p class="text-white-50 mb-0 small">Not Qualified</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Qualification Period Status (if active) -->
        @if($userRankData['current_rank'] && isset($rankConditions))
            @php
                $activeQualificationRank = null;
                foreach($rankConditions as $condition) {
                    if(($condition->is_current ?? false) && isset($condition->qualification_details)) {
                        $qualDetails = $condition->qualification_details;
                        if($qualDetails['qualification_period_active'] ?? false) {
                            $activeQualificationRank = $condition;
                            break;
                        }
                    }
                }
            @endphp
            
            @if($activeQualificationRank)
                <div class="row mb-4">
                    <div class="col-xl-12">
                        <div class="card custom-card border-warning">
                            <div class="card-header bg-warning-transparent">
                                <div class="card-title text-warning">
                                    <i class="fe fe-clock me-2"></i>Active Qualification Period - {{ $activeQualificationRank->rank_name }}
                                </div>
                                <div class="ms-auto">
                                    <span class="badge bg-warning">{{ $activeQualificationRank->qualification_details['qualification_days_remaining'] ?? 0 }} Days Remaining</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="qualification-info">
                                            <h6 class="text-primary mb-3">Qualification Requirements</h6>
                                            <div class="requirement-item mb-2">
                                                <span class="text-muted">Monthly Left Points:</span>
                                                <strong class="text-warning ms-2">{{ number_format($activeQualificationRank->monthly_left_points) }}</strong>
                                            </div>
                                            <div class="requirement-item mb-2">
                                                <span class="text-muted">Monthly Right Points:</span>
                                                <strong class="text-info ms-2">{{ number_format($activeQualificationRank->monthly_right_points) }}</strong>
                                            </div>
                                            <div class="requirement-item">
                                                <span class="text-muted">Must maintain for:</span>
                                                <strong class="text-success ms-2">30 days</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="current-status">
                                            <h6 class="text-primary mb-3">Current Progress</h6>
                                            <div class="progress-item mb-2">
                                                <span class="text-muted">Your Monthly Left:</span>
                                                <strong class="text-warning ms-2">{{ number_format($userRankData['monthly_left_new'] ?? 0) }}</strong>
                                                @if(($userRankData['monthly_left_new'] ?? 0) >= $activeQualificationRank->monthly_left_points)
                                                    <i class="fe fe-check text-success ms-1"></i>
                                                @else
                                                    <i class="fe fe-x text-danger ms-1"></i>
                                                @endif
                                            </div>
                                            <div class="progress-item mb-2">
                                                <span class="text-muted">Your Monthly Right:</span>
                                                <strong class="text-info ms-2">{{ number_format($userRankData['monthly_right_new'] ?? 0) }}</strong>
                                                @if(($userRankData['monthly_right_new'] ?? 0) >= $activeQualificationRank->monthly_right_points)
                                                    <i class="fe fe-check text-success ms-1"></i>
                                                @else
                                                    <i class="fe fe-x text-danger ms-1"></i>
                                                @endif
                                            </div>
                                            <div class="progress-item">
                                                <span class="text-muted">Qualification Status:</span>
                                                @if(($userRankData['monthly_left_new'] ?? 0) >= $activeQualificationRank->monthly_left_points && 
                                                    ($userRankData['monthly_right_new'] ?? 0) >= $activeQualificationRank->monthly_right_points)
                                                    <strong class="text-success ms-2">
                                                        <i class="fe fe-check-circle me-1"></i>On Track
                                                    </strong>
                                                @else
                                                    <strong class="text-danger ms-2">
                                                        <i class="fe fe-alert-triangle me-1"></i>Need More Points
                                                    </strong>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-3 p-3 bg-light rounded">
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <div class="qualification-metric">
                                                <h5 class="text-warning mb-1">{{ $activeQualificationRank->qualification_details['qualification_days_remaining'] ?? 0 }}</h5>
                                                <small class="text-muted">Days Remaining</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="qualification-metric">
                                                <h5 class="text-success mb-1">৳{{ number_format($activeQualificationRank->salary_amount ?? 0) }}</h5>
                                                <small class="text-muted">Monthly Salary When Eligible</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="qualification-metric">
                                                <h5 class="text-info mb-1">
                                                    {{ \Carbon\Carbon::parse($activeQualificationRank->qualification_details['salary_qualification_start_date'])->addDays(30)->format('M d, Y') }}
                                                </h5>
                                                <small class="text-muted">Expected Eligibility Date</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Detailed Rank Conditions Report -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-list me-2 text-primary"></i>Detailed Rank Conditions Report
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-primary">All Ranks Analysis</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover rank-conditions-table">
                                <thead class="table-dark">
                                    <tr>
                                        <th rowspan="2" class="text-center align-middle">Rank</th>
                                        <th colspan="2" class="text-center">Achievement Requirements</th>
                                        <th colspan="2" class="text-center">Monthly Conditions</th>
                                        <th rowspan="2" class="text-center align-middle">Salary</th>
                                        <th rowspan="2" class="text-center align-middle">Matching Bonus</th>
                                        <th rowspan="2" class="text-center align-middle">Status</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Left Points</th>
                                        <th class="text-center">Right Points</th>
                                        <th class="text-center">Monthly Left</th>
                                        <th class="text-center">Monthly Right</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rankConditions as $rank)
                                    @php
                                        $isCurrentRank = $userRankData['current_rank'] && $userRankData['current_rank']->rank_name === $rank->rank_name;
                                        $isAchieved = $rank->user_achieved ?? false;
                                        $achievementProgress = $rank->achievement_progress ?? 0;
                                        $monthlyProgress = $rank->monthly_progress ?? 0;
                                        $canQualifyMonthly = $rank->can_qualify_monthly ?? false;
                                    @endphp
                                    <tr class="{{ $isCurrentRank ? 'table-success' : ($isAchieved ? 'table-light' : '') }}">
                                        <td class="fw-bold">
                                            <div class="d-flex align-items-center">
                                                @if($isCurrentRank)
                                                    <i class="fe fe-crown text-warning me-2"></i>
                                                @elseif($isAchieved)
                                                    <i class="fe fe-check-circle text-success me-2"></i>
                                                @else
                                                    <i class="fe fe-circle text-muted me-2"></i>
                                                @endif
                                                {{ $rank->rank_name }}
                                            </div>
                                        </td>
                                        
                                        <!-- Achievement Requirements -->
                                        <td class="text-center">
                                            <div class="requirement-cell">
                                                <strong>{{ number_format($rank->left_points) }}</strong>
                                                <br>
                                                <small class="text-success">({{ number_format($userRankData['left_points'] ?? 0) }})</small>
                                                <div class="progress mt-1" style="height: 3px;">
                                                    <div class="progress-bar bg-warning" style="width: {{ min(100, ($userRankData['left_points'] ?? 0) / max(1, $rank->left_points) * 100) }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="text-center">
                                            <div class="requirement-cell">
                                                <strong>{{ number_format($rank->right_points) }}</strong>
                                                <br>
                                                <small class="text-success">({{ number_format($userRankData['right_points'] ?? 0) }})</small>
                                                <div class="progress mt-1" style="height: 3px;">
                                                    <div class="progress-bar bg-info" style="width: {{ min(100, ($userRankData['right_points'] ?? 0) / max(1, $rank->right_points) * 100) }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <!-- Monthly Conditions -->
                                        <td class="text-center">
                                            <div class="monthly-cell">
                                                <strong class="text-warning">{{ number_format($rank->monthly_left_points) }}</strong>
                                                @if($isAchieved)
                                                    <br>
                                                    <small class="text-primary">({{ number_format($userRankData['monthly_left_new'] ?? 0) }} NEW)</small>
                                                    @if(($userRankData['monthly_left_new'] ?? 0) >= $rank->monthly_left_points)
                                                        <i class="fe fe-check text-success"></i>
                                                    @else
                                                        <i class="fe fe-x text-danger"></i>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        
                                        <td class="text-center">
                                            <div class="monthly-cell">
                                                <strong class="text-info">{{ number_format($rank->monthly_right_points) }}</strong>
                                                @if($isAchieved)
                                                    <br>
                                                    <small class="text-primary">({{ number_format($userRankData['monthly_right_new'] ?? 0) }} NEW)</small>
                                                    @if(($userRankData['monthly_right_new'] ?? 0) >= $rank->monthly_right_points)
                                                        <i class="fe fe-check text-success"></i>
                                                    @else
                                                        <i class="fe fe-x text-danger"></i>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        
                                        <!-- Salary -->
                                        <td class="text-center">
                                            <div class="salary-cell">
                                                <strong class="text-success">৳{{ number_format($rank->salary) }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $rank->duration_months }} months</small>
                                            </div>
                                        </td>
                                        
                                        <!-- Matching Bonus -->
                                        <td class="text-center">
                                            <div class="bonus-cell">
                                                <strong class="text-primary">৳{{ number_format($rank->matching_tk) }}</strong>
                                                <br>
                                                <small class="text-muted">{{ number_format($rank->point_10_percent) }} pts</small>
                                            </div>
                                        </td>
                                        
                                        <!-- Status -->
                                        <td class="text-center">
                                            <div class="status-cell">
                                                @if($isCurrentRank)
                                                    <span class="badge bg-success mb-1">Current Rank</span>
                                                    <br>
                                                    {{-- Check qualification period status --}}
                                                    @if(isset($rank->qualification_details))
                                                        @php $qualDetails = $rank->qualification_details; @endphp
                                                        @if($qualDetails['qualification_period_active'] ?? false)
                                                            <span class="badge bg-warning mb-1">
                                                                <i class="fe fe-clock me-1"></i>Qualifying
                                                            </span>
                                                            <br>
                                                            <small class="text-primary">
                                                                {{ $qualDetails['qualification_days_remaining'] ?? 0 }} days remaining
                                                            </small>
                                                        @elseif($qualDetails['salary_eligible'] ?? false)
                                                            <span class="badge bg-success mb-1">
                                                                <i class="fe fe-check-circle me-1"></i>Salary Eligible
                                                            </span>
                                                            <br>
                                                            @if($canQualifyMonthly)
                                                                <span class="badge bg-info">Monthly Qualified</span>
                                                            @else
                                                                <span class="badge bg-warning">Need {{ number_format($rank->monthly_left_points - ($userRankData['monthly_left_new'] ?? 0)) }}|{{ number_format($rank->monthly_right_points - ($userRankData['monthly_right_new'] ?? 0)) }}</span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-danger mb-1">
                                                                <i class="fe fe-x-circle me-1"></i>Qualification Failed
                                                            </span>
                                                            <br>
                                                            <small class="text-muted">Need to re-qualify</small>
                                                        @endif
                                                    @else
                                                        @if($canQualifyMonthly)
                                                            <span class="badge bg-info">Monthly Qualified</span>
                                                        @else
                                                            <span class="badge bg-warning">Need {{ number_format($rank->monthly_left_points - ($userRankData['monthly_left_new'] ?? 0)) }}|{{ number_format($rank->monthly_right_points - ($userRankData['monthly_right_new'] ?? 0)) }}</span>
                                                        @endif
                                                    @endif
                                                @elseif($isAchieved)
                                                    <span class="badge bg-primary mb-1">Achieved</span>
                                                    <br>
                                                    {{-- Check qualification period status for achieved ranks --}}
                                                    @if(isset($rank->qualification_details))
                                                        @php $qualDetails = $rank->qualification_details; @endphp
                                                        @if($qualDetails['qualification_period_active'] ?? false)
                                                            <span class="badge bg-warning mb-1">
                                                                <i class="fe fe-clock me-1"></i>Qualifying
                                                            </span>
                                                            <br>
                                                            <small class="text-primary">
                                                                {{ $qualDetails['qualification_days_remaining'] ?? 0 }} days to salary eligibility
                                                            </small>
                                                        @elseif($qualDetails['salary_eligible'] ?? false)
                                                            <span class="badge bg-success mb-1">
                                                                <i class="fe fe-dollar-sign me-1"></i>Can get salary
                                                            </span>
                                                        @else
                                                            <small class="text-muted">Qualification period ended</small>
                                                        @endif
                                                    @else
                                                        <small class="text-muted">Can get salary</small>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary mb-1">Locked</span>
                                                    <br>
                                                    <small class="text-muted">{{ number_format($achievementProgress, 1) }}% complete</small>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No rank data available</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Earnings Projection -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-trending-up me-2 text-success"></i>Monthly Earnings Projection
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($earningsProjection ?? [] as $level => $projection)
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="projection-card {{ $projection['achievable'] ? 'achievable' : 'locked' }}">
                                    <div class="projection-header">
                                        <h6 class="mb-1">{{ $projection['rank_name'] }}</h6>
                                    </div>
                                    <div class="projection-body">
                                        <div class="earning-breakdown">
                                            <div class="earning-item">
                                                <span class="label">Rank Salary:</span>
                                                <span class="value text-success">৳{{ number_format($projection['salary']) }}</span>
                                            </div>
                                            <div class="earning-item">
                                                <span class="label">Matching Bonus:</span>
                                                <span class="value text-primary">৳{{ number_format($projection['matching_bonus']) }}</span>
                                            </div>
                                            <div class="earning-item total">
                                                <span class="label"><strong>Total Monthly:</strong></span>
                                                <span class="value text-warning"><strong>৳{{ number_format($projection['total']) }}</strong></span>
                                            </div>
                                        </div>
                                        <div class="requirements mt-2">
                                            <small class="text-muted">
                                                Monthly: {{ number_format($projection['monthly_left']) }}|{{ number_format($projection['monthly_right']) }} points
                                            </small>
                                        </div>
                                        @if($projection['achievable'])
                                            <div class="achievement-status text-success">
                                                <i class="fe fe-check-circle me-1"></i>Can Achieve
                                            </div>
                                        @else
                                            <div class="achievement-status text-muted">
                                                <i class="fe fe-lock me-1"></i>{{ number_format($projection['progress'], 1) }}% Progress
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Salary Transactions -->
        @if(isset($recentSalaryTransactions) && $recentSalaryTransactions->count() > 0)
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-credit-card me-2 text-success"></i>Recent Salary Payments
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-success">{{ $recentSalaryTransactions->count() }} Transactions</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Transaction ID</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Wallet Type</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSalaryTransactions as $transaction)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $transaction->created_at->format('M d, Y') }}</span>
                                            <br>
                                            <small class="text-muted">{{ $transaction->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            <code class="text-primary">{{ $transaction->transaction_id }}</code>
                                        </td>
                                        <td>
                                            <div class="transaction-description">
                                                <span class="fw-bold">{{ $transaction->description }}</span>
                                                @if($transaction->note)
                                                    <br>
                                                    <small class="text-muted">{{ $transaction->note }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success fs-16">৳{{ number_format($transaction->amount, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $transaction->wallet_type)) }}</span>
                                        </td>
                                        <td>
                                            @if($transaction->status === 'completed')
                                                <span class="badge bg-success">
                                                    <i class="fe fe-check me-1"></i>Completed
                                                </span>
                                            @elseif($transaction->status === 'pending')
                                                <span class="badge bg-warning">
                                                    <i class="fe fe-clock me-1"></i>Pending
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fe fe-x me-1"></i>{{ ucfirst($transaction->status) }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3 p-3 bg-light rounded">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <div class="stat-item">
                                        <h5 class="text-success mb-1">৳{{ number_format($recentSalaryTransactions->sum('amount'), 2) }}</h5>
                                        <small class="text-muted">Total Received</small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="stat-item">
                                        <h5 class="text-primary mb-1">{{ $recentSalaryTransactions->count() }}</h5>
                                        <small class="text-muted">Transactions</small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="stat-item">
                                        <h5 class="text-info mb-1">৳{{ number_format($user->interest_wallet ?? 0, 2) }}</h5>
                                        <small class="text-muted">Current Interest Wallet</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-xl-12 text-center">
                <a href="{{ route('member.rank') }}" class="btn btn-primary me-2">
                    <i class="fe fe-arrow-left me-1"></i>Back to Rank Overview
                </a>
                <a href="{{ route('member.binary') }}" class="btn btn-success me-2">
                    <i class="fe fe-git-branch me-1"></i>View Binary Tree
                </a>
                <button class="btn btn-info" onclick="window.print()">
                    <i class="fe fe-printer me-1"></i>Print Report
                </button>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
/* Salary Report Specific Styles */
.rank-conditions-table {
    font-size: 13px;
}

.rank-conditions-table th {
    font-weight: 600;
    font-size: 12px;
    padding: 10px 8px;
    vertical-align: middle;
    text-align: center;
}

.rank-conditions-table td {
    padding: 10px 8px;
    vertical-align: middle;
}

.requirement-cell {
    min-height: 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.monthly-cell {
    min-height: 50px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.salary-cell, .bonus-cell {
    min-height: 50px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.status-cell {
    min-height: 60px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

/* Projection Cards */
.projection-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    transition: all 0.3s ease;
    background: #fff;
}

.projection-card.achievable {
    border-color: #28a745;
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(40, 167, 69, 0.1) 100%);
}

.projection-card.locked {
    border-color: #6c757d;
    background: linear-gradient(135deg, rgba(108, 117, 125, 0.05) 0%, rgba(108, 117, 125, 0.1) 100%);
}

.projection-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.earning-breakdown {
    margin: 10px 0;
}

.earning-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 0;
    border-bottom: 1px solid #f8f9fa;
}

.earning-item.total {
    border-bottom: 2px solid #ffc107;
    margin-top: 5px;
    padding-top: 8px;
}

.earning-item:last-child {
    border-bottom: none;
}

.achievement-status {
    text-align: center;
    margin-top: 10px;
    font-weight: 600;
    font-size: 12px;
}

/* Status Overview */
.current-status-icon i {
    background: rgba(255, 255, 255, 0.2);
    padding: 15px;
    border-radius: 50%;
    margin-bottom: 10px;
}

.status-stat h3 {
    margin-bottom: 5px;
    font-weight: 700;
}

.status-stat p {
    margin-bottom: 0;
    opacity: 0.9;
}

/* Print Styles */
@media print {
    .page-header-breadcrumb,
    .btn,
    .card-header .ms-auto {
        display: none !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
    
    .table th {
        background-color: #f8f9fa !important;
        color: #000 !important;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .rank-conditions-table {
        font-size: 11px;
    }
    
    .rank-conditions-table th,
    .rank-conditions-table td {
        padding: 6px 4px;
    }
    
    .projection-card {
        margin-bottom: 15px;
    }
    
    .earning-item {
        font-size: 12px;
    }
}

/* Qualification Period Styles */
.qualification-info .requirement-item,
.current-status .progress-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 5px 0;
    border-bottom: 1px solid #f1f3f4;
}

.qualification-info .requirement-item:last-child,
.current-status .progress-item:last-child {
    border-bottom: none;
}

.qualification-metric {
    background: rgba(255, 255, 255, 0.8);
    border-radius: 6px;
    padding: 10px;
    margin: 5px;
}

.bg-warning-transparent {
    background: rgba(255, 193, 7, 0.1) !important;
}

.border-warning {
    border-color: #ffc107 !important;
}

/* Status badges enhancement */
.badge.bg-warning {
    color: #000 !important;
}
</style>
@endpush