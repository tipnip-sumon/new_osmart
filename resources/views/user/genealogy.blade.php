@extends('user.layouts.app')

@section('title', 'My Network - Genealogy')

@section('content')
<div class="py-3">
    <!-- Page Header -->
    <div class="dashboard-card">
        <div class="card-body">
            <h4 class="mb-1">My Network Tree</h4>
            <p class="text-muted mb-0">View and manage your MLM network structure</p>
        </div>
    </div>

    <!-- Network Statistics -->
    <div class="row g-3">
        <div class="col-6">
            <div class="dashboard-card">
                <div class="card-body stat-card text-center">
                    <i class="lni lni-users text-primary fs-2"></i>
                    <h3 class="text-primary">{{ $networkTree->statistics->total_members }}</h3>
                    <p>Total Network</p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="dashboard-card">
                <div class="card-body stat-card text-center">
                    <i class="lni lni-pulse text-success fs-2"></i>
                    <h3 class="text-success">{{ $networkTree->statistics->active_this_month }}</h3>
                    <p>Active This Month</p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="dashboard-card">
                <div class="card-body stat-card text-center">
                    <i class="lni lni-layers text-info fs-2"></i>
                    <h3 class="text-info">{{ $networkTree->statistics->total_levels }}</h3>
                    <p>Network Levels</p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="dashboard-card">
                <div class="card-body stat-card text-center">
                    <i class="lni lni-cart text-warning fs-2"></i>
                    <h3 class="text-warning">${{ number_format($networkTree->statistics->total_volume) }}</h3>
                    <p>Total Volume</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Network Tree Visualization -->
    <div class="dashboard-card">
        <div class="card-header">
            <h5 class="mb-0">Network Structure</h5>
        </div>
        <div class="card-body">
            <!-- Root User (You) -->
            <div class="network-node root-node text-center mb-4">
                <div class="node-avatar mx-auto mb-2">
                    <div class="avatar avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                        You
                    </div>
                </div>
                <h6>{{ $networkTree->user->name }}</h6>
                <span class="rank-badge">{{ $networkTree->user->rank }}</span>
            </div>

            <!-- Level 1 Members -->
            <div class="network-level">
                <h6 class="level-title text-center mb-3">Level 1 - Direct Referrals</h6>
                <div class="row g-3">
                    @foreach($networkTree->level_1 as $member)
                    <div class="col-4">
                        <div class="network-member-card text-center">
                            <div class="node-avatar mx-auto mb-2">
                                <div class="avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center">
                                    {{ substr($member->name, 0, 1) }}
                                </div>
                            </div>
                            <h6 class="mb-1">{{ $member->name }}</h6>
                            <small class="rank-badge-sm bg-{{ $member->rank === 'Silver' ? 'info' : ($member->rank === 'Bronze' ? 'warning' : 'secondary') }}">
                                {{ $member->rank }}
                            </small>
                            <div class="member-stats mt-2">
                                <small class="text-success d-block">${{ number_format($member->volume) }} Volume</small>
                                <small class="text-muted">{{ $member->team_count }} Team Members</small>
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewMemberDetails('{{ $member->name }}')">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Connection Lines Indicator -->
            <div class="text-center my-4">
                <small class="text-muted">
                    <i class="lni lni-more"></i> 
                    Additional levels available in full tree view
                </small>
            </div>
        </div>
    </div>

    <!-- Level Performance -->
    <div class="dashboard-card">
        <div class="card-header">
            <h5 class="mb-0">Level Performance</h5>
        </div>
        <div class="card-body">
            <div class="level-performance">
                @for($i = 1; $i <= 5; $i++)
                @php
                    $levelData = [
                        1 => ['members' => 3, 'volume' => 2240, 'active' => 3],
                        2 => ['members' => 8, 'volume' => 3850, 'active' => 6],
                        3 => ['members' => 12, 'volume' => 2960, 'active' => 9],
                        4 => ['members' => 7, 'volume' => 1540, 'active' => 5],
                        5 => ['members' => 4, 'volume' => 890, 'active' => 3]
                    ];
                @endphp
                <div class="level-item d-flex justify-content-between align-items-center py-3 {{ $i < 5 ? 'border-bottom' : '' }}">
                    <div>
                        <h6 class="mb-1">Level {{ $i }}</h6>
                        <small class="text-muted">{{ $levelData[$i]['members'] }} members</small>
                    </div>
                    <div class="text-center">
                        <div class="text-success fw-bold">${{ number_format($levelData[$i]['volume']) }}</div>
                        <small class="text-muted">Volume</small>
                    </div>
                    <div class="text-center">
                        <div class="text-info fw-bold">{{ $levelData[$i]['active'] }}/{{ $levelData[$i]['members'] }}</div>
                        <small class="text-muted">Active</small>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Recent Team Activity -->
    <div class="dashboard-card">
        <div class="card-header">
            <h5 class="mb-0">Recent Team Activity</h5>
        </div>
        <div class="card-body">
            <div class="activity-timeline">
                <div class="activity-item d-flex mb-3">
                    <div class="activity-icon bg-success text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                        <i class="lni lni-user-plus fs-6"></i>
                    </div>
                    <div class="flex-fill">
                        <h6 class="mb-1">New Member Joined</h6>
                        <p class="mb-1">David Brown joined your network</p>
                        <small class="text-muted">2 hours ago</small>
                    </div>
                </div>
                
                <div class="activity-item d-flex mb-3">
                    <div class="activity-icon bg-info text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                        <i class="lni lni-cart fs-6"></i>
                    </div>
                    <div class="flex-fill">
                        <h6 class="mb-1">Team Purchase</h6>
                        <p class="mb-1">Sarah Johnson made a $299 purchase</p>
                        <small class="text-muted">5 hours ago</small>
                    </div>
                </div>
                
                <div class="activity-item d-flex mb-3">
                    <div class="activity-icon bg-warning text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                        <i class="lni lni-crown fs-6"></i>
                    </div>
                    <div class="flex-fill">
                        <h6 class="mb-1">Rank Advancement</h6>
                        <p class="mb-1">Mike Chen advanced to Silver rank</p>
                        <small class="text-muted">1 day ago</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="dashboard-card">
        <div class="card-header">
            <h5 class="mb-0">Network Tools</h5>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-6">
                    <button class="btn btn-outline-primary w-100" onclick="generateReferralLink()">
                        <i class="lni lni-link"></i><br>
                        Share Referral Link
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-outline-success w-100" onclick="downloadgenealogy()">
                        <i class="lni lni-download"></i><br>
                        Download Report
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-outline-info w-100" onclick="contactTeam()">
                        <i class="lni lni-envelope"></i><br>
                        Contact Team
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-outline-warning w-100" onclick="viewFullTree()">
                        <i class="lni lni-network"></i><br>
                        Full Tree View
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.network-node {
    position: relative;
}

.node-avatar .avatar {
    width: 60px;
    height: 60px;
}

.avatar-lg {
    width: 80px;
    height: 80px;
    font-size: 1.2rem;
}

.network-member-card {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.network-member-card:hover {
    background: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.rank-badge-sm {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: bold;
    color: white;
}

.level-title {
    color: #495057;
    font-weight: 600;
    border-bottom: 2px solid #007bff;
    display: inline-block;
    padding-bottom: 5px;
}

.activity-icon {
    width: 35px;
    height: 35px;
    font-size: 0.8rem;
}

.network-level {
    margin: 30px 0;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
}

.member-stats {
    font-size: 0.8rem;
}
</style>
@endpush

@push('scripts')
<script>
function viewMemberDetails(memberName) {
    alert('Viewing details for: ' + memberName);
    // In real implementation, this would open a modal or navigate to member details
}

function generateReferralLink() {
    const referralLink = window.location.origin + '/register?ref=USER123';
    navigator.clipboard.writeText(referralLink).then(() => {
        alert('Referral link copied to clipboard!\n' + referralLink);
    });
}

function downloadgenealogy() {
    alert('Downloading genealogy report...');
    // In real implementation, this would generate and download a PDF/Excel report
}

function contactTeam() {
    alert('Opening team communication tools...');
    // In real implementation, this would open email composer or messaging system
}

function viewFullTree() {
    alert('Loading full network tree visualization...');
    // In real implementation, this would load an interactive tree view
}
</script>
@endpush
