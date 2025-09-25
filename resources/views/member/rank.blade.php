@extends('member.layouts.app')

@section('title', 'Binary Rank & Achievements')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Binary Rank & Achievements</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Binary Rank</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Error Message -->
        @if(session('error'))
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fe fe-alert-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
        @endif

        <!-- Current Rank Status -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card custom-card rank-card bg-gradient-primary text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <div class="rank-badge">
                                    <div class="rank-icon mb-3">
                                        @if($rankStatus['current_rank'])
                                            <i class="fe fe-award fs-40 text-warning"></i>
                                        @else
                                            <i class="fe fe-user fs-40 text-light"></i>
                                        @endif
                                    </div>
                                    <h3 class="rank-title text-white">
                                        {{ isset($rankStatus['current_rank']) && $rankStatus['current_rank'] ? $rankStatus['current_rank']->rank_name : 'No Rank' }}
                                    </h3>
                                    <p class="rank-subtitle text-light">Current Binary Rank</p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="rank-info">
                                    <h5 class="mb-3 text-white">
                                        @if(isset($rankStatus['current_rank']) && $rankStatus['current_rank'])
                                            Congratulations! You've achieved {{ $rankStatus['current_rank']->rank_name }} rank
                                        @else
                                            Start building your binary legs to achieve your first rank!
                                            @if(isset($rankStatus['next_rank']) && $rankStatus['next_rank'])
                                                <div class="alert alert-info mt-3 mb-0" style="background: rgba(13, 202, 240, 0.2); border: 1px solid rgba(13, 202, 240, 0.4); color: #fff; backdrop-filter: blur(10px);">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fe fe-info-circle fs-20 me-3" style="color: #0dcaf0;"></i>
                                                        <div>
                                                            <h6 class="text-white mb-1 fw-bold">Ready to start your journey?</h6>
                                                            <p class="mb-0" style="color: #f8f9fa;">Build your team to reach <strong style="color: #0dcaf0;">{{ $rankStatus['next_rank']->rank_name }}</strong> rank!</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="rank-stat">
                                                <h4 class="text-warning">{{ number_format($rankStatus['left_points'] ?? 0) }}</h4>
                                                <p class="text-light">Left Leg Points</p>
                                                @if(isset($rankStatus['remaining_left_points']) && $rankStatus['remaining_left_points'] > 0)
                                                    <div class="need-points-badge">
                                                        <span class="badge bg-warning-transparent text-warning px-3 py-2">
                                                            <i class="fe fe-target me-1"></i>Need: {{ number_format($rankStatus['remaining_left_points']) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="rank-stat">
                                                <h4 class="text-info">{{ number_format($rankStatus['right_points'] ?? 0) }}</h4>
                                                <p class="text-light">Right Leg Points</p>
                                                @if(isset($rankStatus['remaining_right_points']) && $rankStatus['remaining_right_points'] > 0)
                                                    <div class="need-points-badge">
                                                        <span class="badge bg-info-transparent text-info px-3 py-2">
                                                            <i class="fe fe-target me-1"></i>Need: {{ number_format($rankStatus['remaining_right_points']) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="rank-stat">
                                                <h4 class="text-success">{{ number_format($rankStatus['progress_to_next'] ?? 0, 1) }}%</h4>
                                                <p class="text-light">Progress to Next</p>
                                                @if(isset($rankStatus['next_rank']) && $rankStatus['next_rank'])
                                                    <small class="text-success">{{ $rankStatus['next_rank']->rank_name ?? '' }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="rank-stat">
                                                @if($rankStatus['monthly_qualified'] ?? false)
                                                    <h4 class="text-success"><i class="fe fe-check-circle"></i></h4>
                                                    <p class="text-light">Monthly Qualified</p>
                                                @else
                                                    <h4 class="text-danger"><i class="fe fe-x-circle"></i></h4>
                                                    <p class="text-light">Not Qualified</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if(isset($rankStatus['next_rank']) && $rankStatus['next_rank'])
                                        <div class="progress mt-3 mb-2" style="height: 8px;">
                                            <div class="progress-bar bg-warning" style="width: {{ $rankStatus['progress_to_next'] ?? 0 }}%"></div>
                                        </div>
                                        <div class="next-rank-requirements">
                                            <div class="row text-center">
                                                @if(($rankStatus['remaining_left_points'] ?? 0) > 0)
                                                <div class="col-md-6">
                                                    <div class="requirement-box bg-warning-transparent">
                                                        <i class="fe fe-arrow-left text-warning fs-16"></i>
                                                        <span class="text-white fw-bold fs-18">{{ number_format($rankStatus['remaining_left_points']) }}</span>
                                                        <small class="text-light d-block mt-1">Left Points Needed</small>
                                                    </div>
                                                </div>
                                                @endif
                                                @if(($rankStatus['remaining_right_points'] ?? 0) > 0)
                                                <div class="col-md-6">
                                                    <div class="requirement-box bg-info-transparent">
                                                        <i class="fe fe-arrow-right text-info fs-16"></i>
                                                        <span class="text-white fw-bold fs-18">{{ number_format($rankStatus['remaining_right_points']) }}</span>
                                                        <small class="text-light d-block mt-1">Right Points Needed</small>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="text-center mt-2">
                                                <small class="fw-semibold" style="color: #f8f9fa;">
                                                    <i class="fe fe-target me-1" style="color: #0dcaf0;"></i>Next Rank: <span style="color: #0dcaf0; font-weight: bold;">{{ $rankStatus['next_rank']->rank_name }}</span>
                                                </small>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Rank Requirements -->
        @if(isset($rankStatus['next_rank']) && $rankStatus['next_rank'])
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-target me-2 text-primary"></i>Next Rank Requirements - {{ $rankStatus['next_rank']->rank_name }}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="requirement-section">
                                    <h6 class="text-warning"><i class="fe fe-arrow-left me-2"></i>Left Leg Requirements</h6>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>Required Points:</span>
                                        <span class="fw-bold">{{ number_format($rankStatus['next_rank']->required_left_points) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>Current Points:</span>
                                        <span class="text-success">{{ number_format($rankStatus['left_points'] ?? 0) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span>Remaining Needed:</span>
                                        <span class="text-danger fw-bold">{{ number_format($rankStatus['remaining_left_points'] ?? 0) }}</span>
                                    </div>
                                    <div class="progress mb-2" style="height: 6px;">
                                        <div class="progress-bar bg-warning" 
                                             style="width: {{ min(100, ($rankStatus['left_points'] ?? 0) / max(1, $rankStatus['next_rank']->required_left_points) * 100) }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ number_format(min(100, ($rankStatus['left_points'] ?? 0) / max(1, $rankStatus['next_rank']->required_left_points) * 100), 1) }}% Complete</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="requirement-section">
                                    <h6 class="text-info"><i class="fe fe-arrow-right me-2"></i>Right Leg Requirements</h6>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>Required Points:</span>
                                        <span class="fw-bold">{{ number_format($rankStatus['next_rank']->required_right_points) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>Current Points:</span>
                                        <span class="text-success">{{ number_format($rankStatus['right_points'] ?? 0) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span>Remaining Needed:</span>
                                        <span class="text-danger fw-bold">{{ number_format($rankStatus['remaining_right_points'] ?? 0) }}</span>
                                    </div>
                                    <div class="progress mb-2" style="height: 6px;">
                                        <div class="progress-bar bg-info" 
                                             style="width: {{ min(100, ($rankStatus['right_points'] ?? 0) / max(1, $rankStatus['next_rank']->required_right_points) * 100) }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ number_format(min(100, ($rankStatus['right_points'] ?? 0) / max(1, $rankStatus['next_rank']->required_right_points) * 100), 1) }}% Complete</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Overall Progress -->
                        <div class="mt-4 text-center">
                            <h6 class="text-primary">Overall Progress to {{ $rankStatus['next_rank']->rank_name }}</h6>
                            <div class="progress mx-auto mb-3" style="height: 12px; max-width: 500px;">
                                <div class="progress-bar bg-gradient-primary" 
                                     style="width: {{ $rankStatus['progress_to_next'] ?? 0 }}%"></div>
                            </div>
                            <p class="mb-0">
                                <span class="badge bg-primary-transparent text-primary fs-12">
                                    {{ number_format($rankStatus['progress_to_next'] ?? 0, 1) }}% Complete
                                </span>
                            </p>
                            
                            @if(($rankStatus['remaining_left_points'] ?? 0) > 0 || ($rankStatus['remaining_right_points'] ?? 0) > 0)
                            <div class="alert alert-info mt-3">
                                <h6><i class="fe fe-info-circle me-2"></i>What You Need To Do:</h6>
                                <p class="mb-0">
                                    Build your team sales to accumulate 
                                    @if(($rankStatus['remaining_left_points'] ?? 0) > 0)
                                        <strong>{{ number_format($rankStatus['remaining_left_points']) }}</strong> more left points
                                    @endif
                                    @if(($rankStatus['remaining_left_points'] ?? 0) > 0 && ($rankStatus['remaining_right_points'] ?? 0) > 0)
                                        and 
                                    @endif
                                    @if(($rankStatus['remaining_right_points'] ?? 0) > 0)
                                        <strong>{{ number_format($rankStatus['remaining_right_points']) }}</strong> more right points
                                    @endif
                                    to achieve {{ $rankStatus['next_rank']->rank_name }} rank.
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Monthly Salary Status -->
        @if(isset($rankStatus['current_rank']) && $rankStatus['current_rank'] && $rankStatus['current_rank']->is_achieved)
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-dollar-sign me-2 text-success"></i>Monthly Salary Status
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="salary-stat text-center">
                                    <h4 class="text-success">৳{{ number_format($rankStatus['current_rank']->salary_amount) }}</h4>
                                    <p class="text-muted">Monthly Salary</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="salary-stat text-center">
                                    <h4 class="text-info">{{ $rankStatus['current_rank']->remaining_duration }}</h4>
                                    <p class="text-muted">Months Remaining</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="salary-stat text-center">
                                    <h4 class="text-warning">{{ $rankStatus['consecutive_months'] }}</h4>
                                    <p class="text-muted">Consecutive Qualified</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="salary-stat text-center">
                                    <h4 class="text-primary">৳{{ number_format($rankStatus['current_rank']->total_salary_paid) }}</h4>
                                    <p class="text-muted">Total Salary Earned</p>
                                </div>
                            </div>
                        </div>
                        
                        @if(!$rankStatus['monthly_qualified'])
                        <div class="alert alert-warning mt-3">
                            <h6><i class="fe fe-info me-2"></i>Monthly Qualification Requirements</h6>
                            <p class="mb-0">
                                To qualify for this month's salary, you need:
                                <strong>{{ number_format($rankStatus['current_rank']->monthly_left_points) }}</strong> left points 
                                and <strong>{{ number_format($rankStatus['current_rank']->monthly_right_points) }}</strong> right points.
                            </p>
                        </div>
                        @else
                        <div class="alert alert-success mt-3">
                            <h6><i class="fe fe-check me-2"></i>Congratulations!</h6>
                            <p class="mb-0">You are qualified for this month's salary of ৳{{ number_format($rankStatus['current_rank']->salary_amount) }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Binary Rank Structure -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-trending-up me-2"></i>Binary Rank Structure & Requirements
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-info">1 Point = 6 Tk | 10% Matching Bonus</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered rank-structure-table">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Rank</th>
                                        <th>Achievement Requirements</th>
                                        <th>Matching Bonus</th>
                                        <th>Rewards & Salary</th>
                                        <th>Monthly Conditions</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rankStructures as $rank)
                                    @php
                                        $userRank = $userRanks->where('rank_name', $rank->rank_name)->first();
                                        $isAchieved = $userRank && $userRank->is_achieved;
                                        $isCurrent = $userRank && $userRank->is_current_rank;
                                        $leftPoints = $rankStatus['left_points'] ?? 0;
                                        $rightPoints = $rankStatus['right_points'] ?? 0;
                                        $qualificationPercent = $rank->getQualificationPercentage($leftPoints, $rightPoints);
                                        $monthlyPercent = $rank->getMonthlyQualificationPercentage($leftPoints, $rightPoints);
                                    @endphp
                                    <tr class="{{ $isCurrent ? 'table-success' : ($isAchieved ? 'table-light' : '') }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rank-icon-small me-2">
                                                    @if($isCurrent)
                                                        <i class="fe fe-crown text-warning"></i>
                                                    @elseif($isAchieved)
                                                        <i class="fe fe-check-circle text-success"></i>
                                                    @else
                                                        <i class="fe fe-circle text-muted"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $rank->rank_name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="requirements-cell">
                                                <div class="requirement-item">
                                                    <strong>{{ number_format($rank->left_points) }}</strong> Left Points
                                                    <span class="text-success ms-2">({{ number_format($leftPoints) }})</span>
                                                </div>
                                                <div class="requirement-item">
                                                    <strong>{{ number_format($rank->right_points) }}</strong> Right Points
                                                    <span class="text-success ms-2">({{ number_format($rightPoints) }})</span>
                                                </div>
                                                <div class="progress mt-1" style="height: 4px;">
                                                    <div class="progress-bar bg-primary" style="width: {{ $qualificationPercent }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ number_format($qualificationPercent, 1) }}% Complete</small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="matching-bonus">
                                                <h6 class="text-primary mb-0">৳{{ number_format($rank->matching_tk) }}</h6>
                                                <small class="text-muted">{{ number_format($rank->point_10_percent) }} points (10%)</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="rewards-cell">
                                                @if($rank->tour && $rank->tour !== 'N/A')
                                                    <div class="reward-item">
                                                        <i class="fe fe-map-pin text-info me-1"></i>{{ $rank->tour }}
                                                    </div>
                                                @endif
                                                @if($rank->gift && $rank->gift !== 'N/A')
                                                    <div class="reward-item">
                                                        <i class="fe fe-gift text-warning me-1"></i>{{ $rank->gift }}
                                                    </div>
                                                @endif
                                                <div class="reward-item">
                                                    <i class="fe fe-dollar-sign text-success me-1"></i>
                                                    <strong>৳{{ number_format($rank->salary) }}/month</strong>
                                                </div>
                                                <small class="text-muted">for {{ $rank->duration_months }} months</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="monthly-conditions">
                                                <div class="condition-item">
                                                    <strong>{{ number_format($rank->monthly_left_points) }}</strong> Left
                                                    @if($isAchieved && $leftPoints >= $rank->monthly_left_points)
                                                        <i class="fe fe-check text-success ms-1"></i>
                                                    @endif
                                                </div>
                                                <div class="condition-item">
                                                    <strong>{{ number_format($rank->monthly_right_points) }}</strong> Right
                                                    @if($isAchieved && $rightPoints >= $rank->monthly_right_points)
                                                        <i class="fe fe-check text-success ms-1"></i>
                                                    @endif
                                                </div>
                                                @if($isAchieved)
                                                    <div class="progress mt-1" style="height: 3px;">
                                                        <div class="progress-bar bg-success" style="width: {{ $monthlyPercent }}%"></div>
                                                    </div>
                                                    <small class="text-muted">
                                                        @if($monthlyPercent >= 100)
                                                            <span class="text-success">Monthly Qualified</span>
                                                        @else
                                                            {{ number_format($monthlyPercent, 1) }}% Monthly Progress
                                                        @endif
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($isCurrent)
                                                <span class="badge bg-success-transparent text-success">Current Rank</span>
                                                @if($userRank->monthly_qualified)
                                                    <br><span class="badge bg-info-transparent text-info mt-1">Monthly Qualified</span>
                                                @endif
                                            @elseif($isAchieved)
                                                <span class="badge bg-primary-transparent text-primary">Achieved</span>
                                                <br><small class="text-muted">{{ $userRank->achieved_at->format('M Y') }}</small>
                                            @else
                                                <span class="badge bg-secondary-transparent text-secondary">Locked</span>
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

        <!-- Achievements -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-trophy me-2"></i>My Achievements
                        </div>
                    </div>
                    <div class="card-body">
                        @if($userRanks->where('is_achieved', true)->count() > 0)
                            <div class="row">
                                @foreach($userRanks->where('is_achieved', true) as $achievement)
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="achievement-card">
                                        <div class="achievement-icon">
                                            <i class="fe fe-trophy text-warning"></i>
                                        </div>
                                        <div class="achievement-info">
                                            <h6>{{ $achievement->rank_name }} Achieved</h6>
                                            <p class="text-muted">
                                                Salary: ৳{{ number_format($achievement->salary_amount) }}/month<br>
                                                Duration: {{ $achievement->duration_months }} months
                                            </p>
                                            <small class="text-success">{{ $achievement->achieved_at ? $achievement->achieved_at->format('M d, Y') : 'Recently' }}</small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="avatar avatar-xl avatar-rounded bg-light mb-3">
                                    <i class="fe fe-trophy fs-24 text-muted"></i>
                                </div>
                                <h6 class="fw-semibold mb-1">No Achievements Yet</h6>
                                <p class="text-muted mb-3">Start building your binary legs to unlock rank achievements</p>
                                <a href="{{ route('member.binary') }}" class="btn btn-primary">
                                    <i class="fe fe-git-branch me-1"></i>View Binary Tree
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Conditions for All Ranks -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-calendar me-2 text-warning"></i>Monthly Conditions for All Ranks
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-warning">NEW Points Required Each Month</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-4">
                            <h6 class="mb-2"><i class="fe fe-info-circle me-2"></i>Important: Monthly Qualification System</h6>
                            <p class="mb-0">After achieving any rank, you must generate <strong>NEW points</strong> every month to qualify for salary payment and matching bonus. These are separate from your achievement points.</p>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover monthly-conditions-table">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">Rank Level</th>
                                        <th class="text-center">Monthly Left Points (NEW)</th>
                                        <th class="text-center">Monthly Right Points (NEW)</th>
                                        <th class="text-center">Matching Bonus (TK)</th>
                                        <th class="text-center">Total Monthly Earning</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success">
                                        <td class="text-center fw-bold">Level 1</td>
                                        <td class="text-center">
                                            <span class="badge bg-warning-transparent text-warning px-3 py-2">
                                                <strong>500</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-transparent text-info px-3 py-2">
                                                <strong>500</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-transparent text-success px-3 py-2">
                                                <strong>৳300</strong>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-success fw-bold">Rank Salary + ৳300</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center fw-bold">Level 2</td>
                                        <td class="text-center">
                                            <span class="badge bg-warning-transparent text-warning px-3 py-2">
                                                <strong>1,000</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-transparent text-info px-3 py-2">
                                                <strong>1,000</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-transparent text-success px-3 py-2">
                                                <strong>৳600</strong>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-success fw-bold">Rank Salary + ৳600</span>
                                        </td>
                                    </tr>
                                    <tr class="table-light">
                                        <td class="text-center fw-bold">Level 3</td>
                                        <td class="text-center">
                                            <span class="badge bg-warning-transparent text-warning px-3 py-2">
                                                <strong>3,000</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-transparent text-info px-3 py-2">
                                                <strong>3,000</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-transparent text-success px-3 py-2">
                                                <strong>৳1,800</strong>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-success fw-bold">Rank Salary + ৳1,800</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center fw-bold">Level 4</td>
                                        <td class="text-center">
                                            <span class="badge bg-warning-transparent text-warning px-3 py-2">
                                                <strong>6,000</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-transparent text-info px-3 py-2">
                                                <strong>6,000</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-transparent text-success px-3 py-2">
                                                <strong>৳3,600</strong>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-success fw-bold">Rank Salary + ৳3,600</span>
                                        </td>
                                    </tr>
                                    <tr class="table-light">
                                        <td class="text-center fw-bold">Level 5</td>
                                        <td class="text-center">
                                            <span class="badge bg-warning-transparent text-warning px-3 py-2">
                                                <strong>9,000</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-transparent text-info px-3 py-2">
                                                <strong>9,000</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-transparent text-success px-3 py-2">
                                                <strong>৳5,400</strong>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-success fw-bold">Rank Salary + ৳5,400</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center fw-bold">Level 6</td>
                                        <td class="text-center">
                                            <span class="badge bg-warning-transparent text-warning px-3 py-2">
                                                <strong>20,000</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-transparent text-info px-3 py-2">
                                                <strong>20,000</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-transparent text-success px-3 py-2">
                                                <strong>৳12,000</strong>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-success fw-bold">Rank Salary + ৳12,000</span>
                                        </td>
                                    </tr>
                                    <tr class="table-light">
                                        <td class="text-center fw-bold">Level 7</td>
                                        <td class="text-center">
                                            <span class="badge bg-warning-transparent text-warning px-3 py-2">
                                                <strong>50,000</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-transparent text-info px-3 py-2">
                                                <strong>50,000</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-transparent text-success px-3 py-2">
                                                <strong>৳30,000</strong>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-success fw-bold">Rank Salary + ৳30,000</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center fw-bold">Level 8</td>
                                        <td class="text-center">
                                            <span class="badge bg-warning-transparent text-warning px-3 py-2">
                                                <strong>100,000</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-transparent text-info px-3 py-2">
                                                <strong>100,000</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-transparent text-success px-3 py-2">
                                                <strong>৳60,000</strong>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-success fw-bold">Rank Salary + ৳60,000</span>
                                        </td>
                                    </tr>
                                    <tr class="table-light">
                                        <td class="text-center fw-bold">Level 9</td>
                                        <td class="text-center">
                                            <span class="badge bg-warning-transparent text-warning px-3 py-2">
                                                <strong>150,000</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-transparent text-info px-3 py-2">
                                                <strong>150,000</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-transparent text-success px-3 py-2">
                                                <strong>৳90,000</strong>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-success fw-bold">Rank Salary + ৳90,000</span>
                                        </td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td class="text-center fw-bold">Level 10</td>
                                        <td class="text-center">
                                            <span class="badge bg-warning-transparent text-warning px-3 py-2">
                                                <strong>200,000</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-transparent text-info px-3 py-2">
                                                <strong>200,000</strong> Points
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-transparent text-success px-3 py-2">
                                                <strong>৳120,000</strong>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-success fw-bold">Rank Salary + ৳120,000</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="alert alert-primary">
                                    <h6><i class="fe fe-info me-2"></i>Point Calculation:</h6>
                                    <ul class="mb-0">
                                        <li><strong>1 Point = ৳6</strong> in team sales</li>
                                        <li>500 Points = ৳3,000 team sales</li>
                                        <li>Points must be <strong>NEW</strong> (generated within 30 days)</li>
                                        <li>Both left and right legs must meet requirements</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-success">
                                    <h6><i class="fe fe-dollar-sign me-2"></i>Earning Structure:</h6>
                                    <ul class="mb-0">
                                        <li><strong>Rank Salary:</strong> Fixed monthly amount based on your achieved rank</li>
                                        <li><strong>Matching Bonus:</strong> Additional bonus based on monthly qualification level</li>
                                        <li><strong>Higher Levels = Higher Bonuses</strong></li>
                                        <li>Example: Level 10 = ৳120,000 extra per month!</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning mt-3">
                            <h6><i class="fe fe-alert-triangle me-2"></i>Monthly Qualification Rules:</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="mb-0">
                                        <li>You can qualify at any level you can achieve monthly</li>
                                        <li>Higher levels give higher matching bonuses</li>
                                        <li>Qualification resets every month (30 days)</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="mb-0">
                                        <li>Must achieve both left AND right point requirements</li>
                                        <li>Points must be generated by your team's NEW sales</li>
                                        <li>Each rank has its own qualification requirements</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- How to Earn Salary Guide -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-help-circle me-2 text-info"></i>How to Earn Monthly Salary - Step by Step Guide
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6 class="mb-3"><i class="fe fe-info-circle me-2"></i>Example: How to Get ৳2,000/month Salary with T-Shirt Reward</h6>
                            <p class="mb-3">To earn the ৳2,000 monthly salary, you need to achieve a specific rank. Here's exactly how:</p>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="step-card">
                                    <div class="step-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="fe fe-check-circle me-2"></i>Step 1: Achievement Requirements</h6>
                                    </div>
                                    <div class="step-body">
                                        <p><strong>Build Your Binary Team:</strong></p>
                                        <ul class="list-unstyled">
                                            <li><i class="fe fe-arrow-right text-warning me-2"></i>Accumulate required <strong>Left Leg Points</strong></li>
                                            <li><i class="fe fe-arrow-right text-info me-2"></i>Accumulate required <strong>Right Leg Points</strong></li>
                                            <li><i class="fe fe-users text-success me-2"></i>Build balanced teams on both sides</li>
                                        </ul>
                                        <div class="alert alert-warning mt-3">
                                            <small><strong>Note:</strong> Check the rank table above to see exact points needed for each salary level.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="step-card">
                                    <div class="step-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fe fe-calendar me-2"></i>Step 2: Monthly Qualification</h6>
                                    </div>
                                    <div class="step-body">
                                        <p><strong>Maintain Monthly Activity:</strong></p>
                                        <ul class="list-unstyled">
                                            <li><i class="fe fe-check text-success me-2"></i>Meet monthly left points requirement</li>
                                            <li><i class="fe fe-check text-success me-2"></i>Meet monthly right points requirement</li>
                                            <li><i class="fe fe-refresh-cw text-info me-2"></i>Maintain team activity every month</li>
                                        </ul>
                                        <div class="alert alert-success mt-3">
                                            <small><strong>Reward:</strong> Once qualified, receive salary for the specified duration (e.g., 2 months).</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="how-points-work mt-4">
                            <h6 class="text-primary mb-3"><i class="fe fe-info me-2"></i>How Points Work:</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="point-explanation">
                                        <div class="point-icon bg-primary-transparent text-primary">
                                            <i class="fe fe-shopping-cart"></i>
                                        </div>
                                        <h6>Sales Generate Points</h6>
                                        <p>Every ৳6 in team sales = 1 point</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="point-explanation">
                                        <div class="point-icon bg-warning-transparent text-warning">
                                            <i class="fe fe-git-branch"></i>
                                        </div>
                                        <h6>Binary System</h6>
                                        <p>Left and right leg points count separately</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="point-explanation">
                                        <div class="point-icon bg-success-transparent text-success">
                                            <i class="fe fe-award"></i>
                                        </div>
                                        <h6>Balance Required</h6>
                                        <p>Need both legs to meet minimum requirements</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="salary-example mt-4 p-3 bg-light rounded">
                            <h6 class="text-success mb-2"><i class="fe fe-dollar-sign me-2"></i>Salary Payment Example:</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>If you achieve a rank with ৳2,000/month for 2 months:</strong></p>
                                    <div class="alert alert-warning mb-3">
                                        <h6 class="mb-2"><i class="fe fe-info-circle me-2"></i>Monthly Qualification Required:</h6>
                                        <p class="mb-1"><strong>NEW Points Each Month:</strong></p>
                                        <ul class="list-unstyled mb-0">
                                            <li>• <strong>500</strong> Left Points (NEW)</li>
                                            <li>• <strong>500</strong> Right Points (NEW)</li>
                                            <li>• <strong>Matching Bonus:</strong> ৳300</li>
                                        </ul>
                                    </div>
                                    <ul class="list-unstyled">
                                        <li><i class="fe fe-calendar text-primary me-2"></i><strong>Achievement Date:</strong> Rank achieved</li>
                                        <li><i class="fe fe-check text-success me-2"></i><strong>Month 1:</strong> Need 500|500 NEW points = Get ৳2,000</li>
                                        <li><i class="fe fe-check text-success me-2"></i><strong>Month 2:</strong> Need 500|500 NEW points = Get ৳2,000</li>
                                        <li>🎁 <strong>Bonus:</strong> T-Shirt reward included</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <div class="total-earning bg-success-transparent p-3 rounded">
                                        <h6 class="text-success mb-1">Total Potential Earnings:</h6>
                                        <h4 class="text-success">৳4,000 + T-Shirt</h4>
                                        <small class="text-muted">Over 2 months (if qualified both months)</small>
                                    </div>
                                    
                                    <div class="mt-3 p-3 bg-info-transparent rounded">
                                        <h6 class="text-info mb-2">Monthly Qualification Pattern:</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr class="bg-primary text-white">
                                                        <th style="font-size: 11px;">Left (NEW)</th>
                                                        <th style="font-size: 11px;">Right (NEW)</th>
                                                        <th style="font-size: 11px;">Matching</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><strong>500</strong></td>
                                                        <td><strong>500</strong></td>
                                                        <td><strong>৳300</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td>1,000</td>
                                                        <td>1,000</td>
                                                        <td>৳600</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3,000</td>
                                                        <td>3,000</td>
                                                        <td>৳1,800</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" class="text-center">
                                                            <small class="text-muted">...and higher levels</small>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            <strong>Note:</strong> 1 Point = ৳6 | Monthly points must be NEW (generated within 30 days)
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-primary mt-3">
                                <h6><i class="fe fe-alert-circle me-2"></i>Important Conditions:</h6>
                                <ul class="mb-0">
                                    <li><strong>Achievement Period:</strong> From rank achievement date, you have 30 days to generate required NEW points</li>
                                    <li><strong>Monthly Renewal:</strong> Each month requires fresh NEW points (not cumulative)</li>
                                    <li><strong>Both Legs Required:</strong> Must meet BOTH left and right point requirements</li>
                                    <li><strong>Matching Bonus:</strong> Additional earning based on monthly qualification level</li>
                                </ul>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <div class="alert alert-primary">
                                <h6><i class="fe fe-target me-2"></i>Ready to Start?</h6>
                                <p class="mb-3">Check the rank table above to see exactly what you need for your desired salary level!</p>
                                <a href="{{ route('member.binary') }}" class="btn btn-primary me-2">
                                    <i class="fe fe-git-branch me-1"></i>View My Binary Tree
                                </a>
                                <a href="{{ route('member.dashboard') }}" class="btn btn-outline-primary">
                                    <i class="fe fe-home me-1"></i>Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rank Benefits -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-gift me-2"></i>Rank Benefits
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="benefits-grid">
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fe fe-percent text-primary"></i>
                                </div>
                                <div class="benefit-content">
                                    <h6>Commission Bonus</h6>
                                    <p>Higher ranks earn increased commission rates</p>
                                </div>
                            </div>
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fe fe-users text-success"></i>
                                </div>
                                <div class="benefit-content">
                                    <h6>Team Recognition</h6>
                                    <p>Special recognition and leadership status</p>
                                </div>
                            </div>
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fe fe-book text-info"></i>
                                </div>
                                <div class="benefit-content">
                                    <h6>Training Access</h6>
                                    <p>Exclusive training materials and resources</p>
                                </div>
                            </div>
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fe fe-calendar text-warning"></i>
                                </div>
                                <div class="benefit-content">
                                    <h6>Events & Rewards</h6>
                                    <p>Access to exclusive events and rewards</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
.rank-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    position: relative;
}

.rank-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, 
        rgba(102, 126, 234, 0.9) 0%, 
        rgba(118, 75, 162, 0.9) 100%);
    z-index: 1;
}

.rank-card .card-body {
    position: relative;
    z-index: 2;
}

.rank-badge {
    text-align: center;
}

.rank-icon i {
    background: rgba(255, 255, 255, 0.2);
    padding: 20px;
    border-radius: 50%;
    margin-bottom: 15px;
}

.rank-title {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.rank-subtitle {
    opacity: 0.8;
    margin-bottom: 0;
}

.rank-info h5 {
    color: white;
}

.rank-stat h4 {
    color: white !important;
}

.need-points-badge {
    margin-top: 8px;
}

.need-points-badge .badge {
    font-size: 13px;
    font-weight: 700;
    border-radius: 20px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    backdrop-filter: blur(15px);
    animation: pulse-glow 2s ease-in-out infinite alternate;
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    padding: 8px 16px;
}

.need-points-badge .badge.bg-warning-transparent {
    background: rgba(255, 193, 7, 0.15) !important;
    color: #ffc107 !important;
    border-color: rgba(255, 193, 7, 0.3);
}

.need-points-badge .badge.bg-info-transparent {
    background: rgba(13, 202, 240, 0.15) !important;
    color: #0dcaf0 !important;
    border-color: rgba(13, 202, 240, 0.3);
}

/* Enhanced text styling */
.rank-info h5 {
    color: white !important;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    font-weight: 600;
}

.rank-stat h4 {
    color: white !important;
    text-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
    font-weight: 700;
    font-size: 2.2rem;
}

.rank-stat p {
    color: rgba(255, 255, 255, 0.9) !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    font-weight: 500;
}

.rank-title {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 5px;
    text-shadow: 0 3px 8px rgba(0, 0, 0, 0.4);
}

.rank-subtitle {
    opacity: 0.9;
    margin-bottom: 0;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    font-weight: 500;
}

@keyframes pulse-glow {
    0% {
        transform: scale(1);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    100% {
        transform: scale(1.05);
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
    }
}

.next-rank-requirements {
    margin-top: 15px;
    padding: 15px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    backdrop-filter: blur(10px);
}

.requirement-box {
    padding: 12px 16px;
    border-radius: 10px;
    margin-bottom: 8px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.requirement-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-color: rgba(255, 255, 255, 0.5);
}

.requirement-box i {
    margin-right: 8px;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
}

.requirement-box span {
    font-size: 18px;
    margin-left: 5px;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
}

/* Rank Table Styles */
.rank-table tbody tr {
    border-bottom: 1px solid #f0f0f0;
}

.rank-number {
    background: linear-gradient(45deg, #6c5ce7, #a55eea);
    color: white;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
    box-shadow: 0 2px 10px rgba(108, 92, 231, 0.3);
}

.rank-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 5px;
}

.rank-rewards {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.rank-rewards .badge {
    font-size: 11px;
    padding: 4px 8px;
}

.qualification-points {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.point-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8f9fa;
    padding: 8px 12px;
    border-radius: 6px;
    border-left: 3px solid #007bff;
}

.point-item.left {
    border-left-color: #28a745;
}

.point-item.right {
    border-left-color: #dc3545;
}

.monthly-conditions {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.condition-item {
    background: #fff3cd;
    padding: 6px 10px;
    border-radius: 4px;
    border-left: 3px solid #ffc107;
    font-size: 13px;
}

.badge.current-rank {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.achievement-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s ease;
}

.achievement-card:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.achievement-icon i {
    font-size: 24px;
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.benefit-item:hover {
    background: #e9ecef;
}

.benefit-icon i {
    font-size: 24px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .rank-table {
        font-size: 14px;
    }
    
    .qualification-points {
        gap: 5px;
    }
    
    .point-item {
        padding: 6px 8px;
        font-size: 12px;
    }
    
    .benefits-grid {
        grid-template-columns: 1fr;
    }
    
    .rank-rewards .badge {
        font-size: 10px;
        padding: 3px 6px;
    }
}

/* Monthly conditions table styling */
.monthly-conditions-table {
    font-size: 14px;
}

.monthly-conditions-table th {
    font-weight: 600;
    font-size: 13px;
    padding: 12px 8px;
    vertical-align: middle;
}

.monthly-conditions-table td {
    padding: 12px 8px;
    vertical-align: middle;
}

.monthly-conditions-table .badge {
    font-size: 12px;
    font-weight: 600;
    border-radius: 6px;
}

.monthly-conditions-table .table-success {
    background-color: rgba(25, 135, 84, 0.1);
}

.monthly-conditions-table .table-light {
    background-color: rgba(248, 249, 250, 0.8);
}

.monthly-conditions-table .table-warning {
    background-color: rgba(255, 193, 7, 0.2);
}

/* Enhanced step cards */
.step-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 20px;
    overflow: hidden;
}

.step-header {
    padding: 12px 16px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

.step-body {
    padding: 16px;
}

.step-body ul li {
    padding: 5px 0;
    border-bottom: 1px solid #f8f9fa;
}

.step-body ul li:last-child {
    border-bottom: none;
}

/* Point explanation cards */
.point-explanation {
    text-align: center;
    padding: 20px 15px;
}

.point-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 20px;
}

.point-explanation h6 {
    margin-bottom: 8px;
    font-weight: 600;
}

.point-explanation p {
    margin-bottom: 0;
    font-size: 14px;
    color: #666;
}

/* Salary example styling */
.salary-example {
    border-left: 4px solid #28a745;
}

.total-earning {
    text-align: center;
    border: 2px solid rgba(40, 167, 69, 0.3);
}

/* Loading animation for table */
.table-loading {
    position: relative;
}

.table-loading:after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: none;
}

.table-loading.loading:after {
    display: block;
}
</style>
@endpush
