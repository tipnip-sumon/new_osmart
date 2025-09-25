@extends('user.layouts.app')

@section('title', 'MLM Dashboard')

@section('content')
<div class="py-3">
    <!-- Welcome Section -->
    <div class="dashboard-card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-8">
                    <h4 class="mb-1">Welcome back, John!</h4>
                    <p class="text-muted mb-0">Here's your MLM business overview</p>
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
            <h5 class="card-title">Rank Advancement Progress</h5>
            <div class="row align-items-center">
                <div class="col-8">
                    <div class="mb-2">
                        <small class="text-muted">Current: {{ $userStats->current_rank }}</small>
                        <small class="text-muted float-end">Next: {{ $userStats->next_rank }}</small>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-gradient" role="progressbar" 
                             style="width: {{ $userStats->rank_progress }}%"></div>
                    </div>
                    <small class="text-muted">
                        {{ number_format($userStats->rank_progress, 1) }}% complete 
                        ({{ number_format($userStats->team_volume) }}/{{ number_format($userStats->next_rank_requirement) }} volume)
                    </small>
                </div>
                <div class="col-4 text-center">
                    <div class="progress-circle bg-primary">
                        {{ number_format($userStats->rank_progress) }}%
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Volume Statistics -->
    <div class="row g-3">
        <div class="col-6">
            <div class="dashboard-card">
                <div class="card-body text-center">
                    <h4 class="text-primary">{{ number_format($userStats->personal_volume) }}</h4>
                    <p class="mb-0 text-muted">Personal Volume</p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="dashboard-card">
                <div class="card-body text-center">
                    <h4 class="text-success">{{ number_format($userStats->team_volume) }}</h4>
                    <p class="mb-0 text-muted">Team Volume</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Commissions -->
    <div class="dashboard-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Recent Commissions</h5>
            <a href="{{ route('user.commissions') }}" class="btn btn-sm btn-primary">View All</a>
        </div>
        <div class="card-body">
            @foreach($recentCommissions as $commission)
            <div class="commission-item {{ strtolower(str_replace(' ', '', $commission->type)) }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">{{ $commission->type }}</h6>
                        <small class="text-muted">{{ $commission->from }} - {{ $commission->product }}</small>
                    </div>
                    <div class="text-end">
                        <strong class="text-success">${{ number_format($commission->amount, 2) }}</strong>
                        <br>
                        <small class="text-muted">{{ $commission->date }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Team Activity -->
    <div class="dashboard-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Team Activity</h5>
            <a href="{{ route('user.genealogy') }}" class="btn btn-sm btn-primary">View Network</a>
        </div>
        <div class="card-body">
            @foreach($teamMembers->take(4) as $member)
            <div class="d-flex align-items-center mb-3 {{ !$loop->last ? 'pb-3 border-bottom' : '' }}">
                <div class="me-3">
                    <div class="avatar avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                        {{ substr($member->name, 0, 1) }}
                    </div>
                </div>
                <div class="flex-fill">
                    <h6 class="mb-0">{{ $member->name }}</h6>
                    <small class="text-muted">{{ $member->level }} • {{ $member->rank }}</small>
                </div>
                <div class="text-end">
                    <small class="text-success">${{ number_format($member->this_month_volume) }}</small>
                    <br>
                    <span class="badge bg-{{ $member->status === 'active' ? 'success' : 'warning' }} badge-sm">
                        {{ ucfirst($member->status) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="dashboard-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Recent Orders</h5>
            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-primary">View All</a>
        </div>
        <div class="card-body">
            @foreach($recentOrders as $order)
            <div class="d-flex justify-content-between align-items-center mb-3 {{ !$loop->last ? 'pb-3 border-bottom' : '' }}">
                <div>
                    <h6 class="mb-1">{{ $order->id }}</h6>
                    <small class="text-muted">{{ $order->products }}</small>
                    <br>
                    <small class="text-muted">{{ $order->date }}</small>
                </div>
                <div class="text-end">
                    <strong>${{ number_format($order->amount, 2) }}</strong>
                    <br>
                    <small class="text-info">{{ $order->pv_points }} PV</small>
                    <br>
                    <span class="badge bg-success">{{ ucfirst($order->status) }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="dashboard-card">
        <div class="card-header">
            <h5 class="mb-0">Quick Actions</h5>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-6">
                    <a href="{{ route('shop.grid') }}" class="btn btn-outline-primary w-100">
                        <i class="lni lni-store"></i><br>
                        Shop Products
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('user.genealogy') }}" class="btn btn-outline-success w-100">
                        <i class="lni lni-network"></i><br>
                        View Network
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('user.training') }}" class="btn btn-outline-info w-100">
                        <i class="lni lni-graduation"></i><br>
                        Training
                    </a>
                </div>
                <div class="col-6">
                    <a href="/referral-link" class="btn btn-outline-warning w-100">
                        <i class="lni lni-share"></i><br>
                        Share Link
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.avatar {
    width: 40px;
    height: 40px;
}

.avatar-sm {
    width: 30px;
    height: 30px;
    font-size: 0.8rem;
}

.badge-sm {
    font-size: 0.7rem;
}
</style>
@endpush
