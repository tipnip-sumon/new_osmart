@extends('user.layouts.app')

@section('title', 'Affiliate Dashboard')

@section('content')
<div class="py-3">
    <!-- Welcome Section -->
    <div class="dashboard-card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-8">
                    <h4 class="mb-1">Welcome back, {{ $user->name }}!</h4>
                    <p class="text-muted mb-0">Here's your affiliate business overview</p>
                </div>
                <div class="col-4 text-end">
                    <span class="rank-badge">{{ $userStats->current_rank }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3">
        <div class="col-6">
            <div class="dashboard-card">
                <div class="card-body stat-card text-center">
                    <i class="lni lni-money-protection text-success fs-2"></i>
                    <h3 class="text-success">${{ number_format($userStats->total_earnings, 2) }}</h3>
                    <p>Total Earnings</p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="dashboard-card">
                <div class="card-body stat-card text-center">
                    <i class="lni lni-calendar text-primary fs-2"></i>
                    <h3 class="text-primary">${{ number_format($userStats->this_month_earnings, 2) }}</h3>
                    <p>This Month</p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="dashboard-card">
                <div class="card-body stat-card text-center">
                    <i class="lni lni-users text-info fs-2"></i>
                    <h3 class="text-info">{{ $userStats->team_size }}</h3>
                    <p>Team Size</p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="dashboard-card">
                <div class="card-body stat-card text-center">
                    <i class="lni lni-user text-warning fs-2"></i>
                    <h3 class="text-warning">{{ $userStats->direct_referrals }}</h3>
                    <p>Direct Referrals</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Rank Progress -->
    <div class="dashboard-card">
        <div class="card-body">
            <h5 class="mb-3">
                <i class="lni lni-crown"></i> Rank Progress
            </h5>
            <div class="d-flex justify-content-between mb-2">
                <span>{{ $userStats->current_rank }}</span>
                <span>{{ $userStats->next_rank }}</span>
            </div>
            <div class="progress mb-2" style="height: 8px;">
                <div class="progress-bar bg-gradient" 
                     style="width: {{ $userStats->rank_progress }}%; background: linear-gradient(90deg, #667eea, #764ba2);" 
                     role="progressbar"></div>
            </div>
            <small class="text-muted">
                {{ number_format($userStats->rank_progress, 1) }}% complete 
                ({{ number_format($userStats->team_volume) }}/{{ number_format($userStats->next_rank_requirement) }} volume)
            </small>
        </div>
    </div>

    <!-- Volume Cards -->
    <div class="row g-3">
        <div class="col-6">
            <div class="dashboard-card">
                <div class="card-body text-center">
                    <h4 class="text-primary">{{ number_format($userStats->personal_volume) }}</h4>
                    <p class="mb-0">Personal Volume</p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="dashboard-card">
                <div class="card-body text-center">
                    <h4 class="text-success">{{ number_format($userStats->team_volume) }}</h4>
                    <p class="mb-0">Team Volume</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Commissions -->
    <div class="dashboard-card">
        <div class="card-body">
            <h5 class="mb-3">
                <i class="lni lni-money-protection"></i> Recent Commissions
            </h5>
            @foreach($recentCommissions as $commission)
            <div class="commission-item {{ strtolower(str_replace(' ', '', $commission->type)) }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">{{ $commission->type }}</h6>
                        <small class="text-muted">{{ $commission->from }} - {{ $commission->product }}</small>
                        <br>
                        <small class="text-muted">{{ date('M d, Y', strtotime($commission->date)) }}</small>
                    </div>
                    <div class="text-end">
                        <span class="fw-bold text-success">${{ number_format($commission->amount, 2) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
            
            <div class="text-center mt-3">
                <a href="{{ route('user.commissions') }}" class="btn btn-outline-primary btn-sm">
                    View All Commissions
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-3">
        <div class="col-6">
            <a href="{{ route('user.genealogy') }}" class="dashboard-card d-block text-decoration-none">
                <div class="card-body text-center">
                    <i class="lni lni-network fs-1 text-info mb-2"></i>
                    <h6 class="mb-0">My Network</h6>
                </div>
            </a>
        </div>
        <div class="col-6">
            <a href="{{ route('user.training') }}" class="dashboard-card d-block text-decoration-none">
                <div class="card-body text-center">
                    <i class="lni lni-graduation fs-1 text-warning mb-2"></i>
                    <h6 class="mb-0">Training</h6>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.commission-item {
    border-left: 4px solid;
    padding-left: 15px;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f1f1f1;
}

.commission-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.commission-item.directcommission { 
    border-color: #28a745; 
}

.commission-item.teambonus { 
    border-color: #007bff; 
}

.commission-item.leadershipbonus { 
    border-color: #ffc107; 
}

.dashboard-card:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.progress-bar {
    transition: width 0.6s ease;
}

.rank-badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}
</style>
@endpush
