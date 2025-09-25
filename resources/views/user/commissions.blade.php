@extends('user.layouts.app')

@section('title', 'My Commissions & Earnings')

@section('content')
<div class="py-3">
    <!-- Page Header -->
    <div class="dashboard-card">
        <div class="card-body">
            <h4 class="mb-1">My Commissions & Earnings</h4>
            <p class="text-muted mb-0">Track your MLM income and commission history</p>
        </div>
    </div>

    <!-- Earnings Summary -->
    <div class="row g-3">
        <div class="col-6">
            <div class="dashboard-card">
                <div class="card-body stat-card text-center">
                    <i class="lni lni-money-protection text-success fs-2"></i>
                    <h3 class="text-success">${{ number_format($commissionSummary->total_earned, 2) }}</h3>
                    <p>Total Earned</p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="dashboard-card">
                <div class="card-body stat-card text-center">
                    <i class="lni lni-calendar text-primary fs-2"></i>
                    <h3 class="text-primary">${{ number_format($commissionSummary->this_month, 2) }}</h3>
                    <p>This Month</p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="dashboard-card">
                <div class="card-body stat-card text-center">
                    <i class="lni lni-clock text-warning fs-2"></i>
                    <h3 class="text-warning">${{ number_format($commissionSummary->pending, 2) }}</h3>
                    <p>Pending</p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="dashboard-card">
                <div class="card-body stat-card text-center">
                    <i class="lni lni-timer text-info fs-2"></i>
                    <h3 class="text-info">${{ number_format($commissionSummary->this_week, 2) }}</h3>
                    <p>This Week</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Commission Breakdown -->
    <div class="dashboard-card">
        <div class="card-header">
            <h5 class="mb-0">Commission Breakdown</h5>
        </div>
        <div class="card-body">
            <div class="commission-breakdown">
                <div class="commission-type d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="commission-icon bg-success text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                            <i class="lni lni-handshake fs-6"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Direct Commissions</h6>
                            <small class="text-muted">From direct sales</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <h5 class="mb-0 text-success">${{ number_format($commissionSummary->direct_commissions, 2) }}</h5>
                        <small class="text-muted">66.3% of total</small>
                    </div>
                </div>

                <div class="commission-type d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="commission-icon bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                            <i class="lni lni-users fs-6"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Team Bonuses</h6>
                            <small class="text-muted">From team sales</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <h5 class="mb-0 text-primary">${{ number_format($commissionSummary->team_bonuses, 2) }}</h5>
                        <small class="text-muted">26.3% of total</small>
                    </div>
                </div>

                <div class="commission-type d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center">
                        <div class="commission-icon bg-warning text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                            <i class="lni lni-crown fs-6"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Leadership Bonuses</h6>
                            <small class="text-muted">Leadership rewards</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <h5 class="mb-0 text-warning">${{ number_format($commissionSummary->leadership_bonuses, 2) }}</h5>
                        <small class="text-muted">7.4% of total</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="dashboard-card">
        <div class="card-header">
            <ul class="nav nav-pills" id="commissionTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button" role="tab">
                        All
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="direct-tab" data-bs-toggle="pill" data-bs-target="#direct" type="button" role="tab">
                        Direct
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="team-tab" data-bs-toggle="pill" data-bs-target="#team" type="button" role="tab">
                        Team
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="leadership-tab" data-bs-toggle="pill" data-bs-target="#leadership" type="button" role="tab">
                        Leadership
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="commissionTabsContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    @foreach($commissions as $commission)
                    <div class="commission-item {{ strtolower(str_replace(' ', '', $commission->type)) }} d-flex justify-content-between align-items-center py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex align-items-center">
                            <div class="commission-type-badge me-3">
                                @if($commission->type === 'Direct Commission')
                                    <div class="badge bg-success">DC</div>
                                @elseif($commission->type === 'Team Bonus')
                                    <div class="badge bg-primary">TB</div>
                                @else
                                    <div class="badge bg-warning">LB</div>
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $commission->type }}</h6>
                                <small class="text-muted">{{ $commission->description }}</small>
                                <br>
                                <small class="text-muted">{{ $commission->date }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0 text-success">${{ number_format($commission->amount, 2) }}</h5>
                            <span class="badge bg-{{ $commission->status === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($commission->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="tab-pane fade" id="direct" role="tabpanel">
                    @foreach($commissions->where('type', 'Direct Commission') as $commission)
                    <div class="commission-item direct d-flex justify-content-between align-items-center py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div>
                            <h6 class="mb-1">{{ $commission->description }}</h6>
                            <small class="text-muted">{{ $commission->date }}</small>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0 text-success">${{ number_format($commission->amount, 2) }}</h5>
                            <span class="badge bg-{{ $commission->status === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($commission->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="tab-pane fade" id="team" role="tabpanel">
                    @foreach($commissions->where('type', 'Team Bonus') as $commission)
                    <div class="commission-item team d-flex justify-content-between align-items-center py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div>
                            <h6 class="mb-1">{{ $commission->description }}</h6>
                            <small class="text-muted">{{ $commission->date }}</small>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0 text-primary">${{ number_format($commission->amount, 2) }}</h5>
                            <span class="badge bg-{{ $commission->status === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($commission->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="tab-pane fade" id="leadership" role="tabpanel">
                    @foreach($commissions->where('type', 'Leadership Bonus') as $commission)
                    <div class="commission-item leadership d-flex justify-content-between align-items-center py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div>
                            <h6 class="mb-1">{{ $commission->description }}</h6>
                            <small class="text-muted">{{ $commission->date }}</small>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0 text-warning">${{ number_format($commission->amount, 2) }}</h5>
                            <span class="badge bg-{{ $commission->status === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($commission->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Payout Information -->
    <div class="dashboard-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Payout Information</h5>
            <button class="btn btn-sm btn-primary" onclick="updatePayoutInfo()">Update</button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="payout-method">
                        <h6 class="text-primary">Payment Method</h6>
                        <p class="mb-2">Bank Transfer - Wells Fargo ****1234</p>
                        <small class="text-muted">Payouts are processed every Friday</small>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-6">
                    <div class="payout-schedule">
                        <h6 class="text-info">Next Payout</h6>
                        <p class="mb-0">Friday, Jan 19, 2024</p>
                        <small class="text-muted">Estimated: $67.25</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="payout-minimum">
                        <h6 class="text-warning">Minimum Payout</h6>
                        <p class="mb-0">$50.00</p>
                        <small class="text-success">✓ Minimum reached</small>
                    </div>
                </div>
            </div>
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
                    <button class="btn btn-outline-primary w-100" onclick="downloadStatement()">
                        <i class="lni lni-download"></i><br>
                        Download Statement
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-outline-success w-100" onclick="viewTaxInfo()">
                        <i class="lni lni-files"></i><br>
                        Tax Information
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-outline-info w-100" onclick="requestPayout()">
                        <i class="lni lni-credit-cards"></i><br>
                        Request Payout
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-outline-warning w-100" onclick="viewCommissionPlan()">
                        <i class="lni lni-question-circle"></i><br>
                        Commission Plan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.commission-icon {
    width: 40px;
    height: 40px;
}

.commission-type-badge .badge {
    font-size: 0.7rem;
    font-weight: bold;
}

.nav-pills .nav-link {
    margin-right: 5px;
    border-radius: 20px;
    font-size: 0.9rem;
}

.commission-breakdown .commission-type {
    transition: background-color 0.3s ease;
}

.commission-breakdown .commission-type:hover {
    background-color: #f8f9fa;
}

.payout-method, .payout-schedule, .payout-minimum {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 15px;
}
</style>
@endpush

@push('scripts')
<script>
function updatePayoutInfo() {
    alert('Opening payout information update form...');
    // In real implementation, this would open a modal or navigate to payout settings
}

function downloadStatement() {
    alert('Downloading commission statement...');
    // In real implementation, this would generate and download a PDF statement
}

function viewTaxInfo() {
    alert('Opening tax information and 1099 forms...');
    // In real implementation, this would show tax documents and forms
}

function requestPayout() {
    alert('Opening payout request form...');
    // In real implementation, this would allow early payout requests
}

function viewCommissionPlan() {
    alert('Opening detailed commission plan information...');
    // In real implementation, this would show the compensation plan details
}
</script>
@endpush
