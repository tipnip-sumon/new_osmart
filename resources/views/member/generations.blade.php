@extends('member.layouts.app')

@section('title', 'Team Generations - ' . config('app.name'))

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div>
                <h1 class="page-title fw-semibold fs-18 mb-0">Team Generations</h1>
                <div class="">
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Generations</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-primary" onclick="refreshGenerations()">
                    <i class="fe fe-refresh-cw me-1"></i>Refresh
                </button>
                <button class="btn btn-sm btn-success" onclick="exportGenerations()">
                    <i class="fe fe-download me-1"></i>Export
                </button>
            </div>
        </div>

        <!-- Statistics Row -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="d-block mb-1 text-muted fs-12">Total Generations</span>
                                <span class="h4 fw-semibold mb-0 text-primary" id="totalGenerations">{{ count($generations) }}</span>
                            </div>
                            <div class="avatar avatar-md bg-primary-transparent">
                                <i class="fe fe-layers fs-18"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="d-block mb-1 text-muted fs-12">Total Members</span>
                                <span class="h4 fw-semibold mb-0 text-success" id="totalMembers">{{ collect($generations)->sum('total_members') }}</span>
                            </div>
                            <div class="avatar avatar-md bg-success-transparent">
                                <i class="fe fe-users fs-18"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="d-block mb-1 text-muted fs-12">Total Earned</span>
                                <span class="h4 fw-semibold mb-0 text-success">৳{{ number_format(collect($generations)->sum('total_earned'), 2) }}</span>
                            </div>
                            <div class="avatar avatar-md bg-success-transparent">
                                <i class="fe fe-check-circle fs-18"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="d-block mb-1 text-muted fs-12">Pending Income</span>
                                <span class="h4 fw-semibold mb-0 text-warning">৳{{ number_format(collect($generations)->sum('total_pending'), 2) }}</span>
                            </div>
                            <div class="avatar avatar-md bg-warning-transparent">
                                <i class="fe fe-clock fs-18"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Generation Income System Overview -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-info me-2"></i>20-Level Generation Income System
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="p-3 border rounded bg-light">
                                    <h6 class="fw-semibold text-primary mb-2">Level 1-2</h6>
                                    <p class="mb-1"><span class="fw-semibold">2%</span> of package value per member</p>
                                    <small class="text-muted">Direct referrals & their downlines</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 border rounded bg-light">
                                    <h6 class="fw-semibold text-success mb-2">Level 3-6</h6>
                                    <p class="mb-1"><span class="fw-semibold">1%</span> of package value per member</p>
                                    <small class="text-muted">Extended network levels</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 border rounded bg-light">
                                    <h6 class="fw-semibold text-warning mb-2">Level 7-20</h6>
                                    <p class="mb-1"><span class="fw-semibold">0.5%</span> of package value per member</p>
                                    <small class="text-muted">Deep network levels</small>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info mt-3" role="alert">
                            <i class="fe fe-info-circle me-2"></i>
                            <strong>Percentage-Based System:</strong> Income varies by package (100pts=৳600, 200pts=৳1200, 500pts=৳3000, etc.). Only First Rank Achievers receive immediate payment to income wallet. Upgraded accounts show as pending until first rank achieved. Free accounts are not eligible.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Generations Network -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="fe fe-users me-2"></i>20-Level Generation Network
                        </div>
                        <div class="d-flex gap-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="showAllLevels" checked>
                                <label class="form-check-label" for="showAllLevels">
                                    Show All Levels
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Level Filter Buttons -->
                        <div class="mb-4">
                            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                                <span class="fw-semibold text-muted me-2">Filter by Level:</span>
                                @if(count($generations) > 1)
                                    <button class="btn btn-outline-primary btn-sm level-filter-btn" onclick="filterByLevel('all')" data-level="all">
                                        <i class="fe fe-layers"></i> All Levels
                                    </button>
                                @endif
                                @foreach($generations as $generation)
                                    <button class="btn btn-outline-primary btn-sm level-filter-btn" onclick="filterByLevel({{ $generation['level'] }})" data-level="{{ $generation['level'] }}">
                                        Level {{ $generation['level'] }}
                                        <span class="badge bg-light text-dark ms-1">{{ $generation['total_members'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                            <div class="text-muted small">
                                <i class="fe fe-info-circle me-1"></i>
                                Click any level button to view only that generation's members. Shows existing levels with member counts.
                            </div>
                        </div>
                        @if(empty($generations) || count($generations) == 0)
                            <div class="text-center py-5">
                                <div class="avatar avatar-xl avatar-rounded bg-light mb-3">
                                    <i class="fe fe-layers fs-24 text-muted"></i>
                                </div>
                                <h6 class="fw-semibold mb-1">No Generation Data Yet</h6>
                                <p class="text-muted mb-3">Start building your network to see generation levels</p>
                                <a href="{{ route('member.sponsor') }}" class="btn btn-primary">
                                    <i class="fe fe-plus me-1"></i>Build Your Network
                                </a>
                            </div>
                        @else
                            <!-- Generation Levels -->
                            @php
                                $colors = ['primary', 'success', 'warning', 'info', 'purple', 'danger', 'dark', 'secondary', 'pink', 'teal', 'orange', 'cyan', 'indigo', 'yellow', 'lime', 'red', 'blue', 'green', 'gray', 'black'];
                            @endphp
                            
                            <div class="accordion accordion-flush" id="generationAccordion">
                                @foreach($generations as $index => $generation)
                                @php
                                    $colorIndex = $index % count($colors);
                                    $color = $colors[$colorIndex];
                                @endphp
                                <div class="accordion-item generation-level" data-level="{{ $generation['level'] }}">
                                    <h2 class="accordion-header" id="generation{{ $generation['level'] }}Heading">
                                        <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#generation{{ $generation['level'] }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="generation{{ $generation['level'] }}">
                                            <div class="d-flex align-items-center w-100">
                                                <div class="avatar avatar-sm bg-{{ $color }}-transparent me-3">
                                                    <span class="fw-semibold">{{ $generation['level'] }}</span>
                                                </div>
                                                <div class="flex-fill">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-0">Generation {{ $generation['level'] }}</h6>
                                                            <small class="text-muted">
                                                                @if($generation['level'] <= 2)
                                                                    2% of package value per member
                                                                @elseif($generation['level'] <= 6)
                                                                    1% of package value per member
                                                                @else
                                                                    0.5% of package value per member
                                                                @endif
                                                            </small>
                                                        </div>
                                                        <div class="text-end me-3">
                                                            <span class="fw-semibold text-primary">{{ $generation['total_members'] }} Members</span>
                                                            <div><small class="text-success">৳{{ number_format($generation['total_earned'], 2) }} Earned</small></div>
                                                            <div><small class="text-warning">৳{{ number_format($generation['total_pending'], 2) }} Pending</small></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="generation{{ $generation['level'] }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="generation{{ $generation['level'] }}Heading" data-bs-parent="#generationAccordion">
                                        <div class="accordion-body">
                            <!-- Generation Statistics -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="border p-3 rounded text-center bg-{{ $color }}-transparent">
                                        <h5 class="fw-semibold text-{{ $color }} mb-1">{{ $generation['total_members'] }}</h5>
                                        <p class="text-muted mb-0 fs-12">Total Members</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border p-3 rounded text-center bg-info-transparent">
                                        <h5 class="fw-semibold text-info mb-1">
                                            @if($generation['level'] <= 2)
                                                2%
                                            @elseif($generation['level'] <= 6)
                                                1%
                                            @else
                                                0.5%
                                            @endif
                                        </h5>
                                        <p class="text-muted mb-0 fs-12">Commission Rate</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border p-3 rounded text-center bg-success-transparent">
                                        <h5 class="fw-semibold text-success mb-1">৳{{ number_format($generation['total_earned'], 2) }}</h5>
                                        <p class="text-muted mb-0 fs-12">Total Earned</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border p-3 rounded text-center bg-warning-transparent">
                                        <h5 class="fw-semibold text-warning mb-1">৳{{ number_format($generation['total_pending'], 2) }}</h5>
                                        <p class="text-muted mb-0 fs-12">Pending Income</p>
                                    </div>
                                </div>
                            </div>                                            <!-- Members in this generation -->
                                            @if(count($generation['members']) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Member</th>
                                                                <th>Contact</th>
                                                                <th>Join Date</th>
                                                                <th>Status</th>
                                                                <th>Income Status</th>
                                                                <th>Generation Income</th>
                                                                <th>Points</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($generation['members'] as $member)
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="avatar avatar-sm avatar-rounded me-2">
                                                                            <span class="fw-semibold">{{ substr($member['name'], 0, 2) }}</span>
                                                                        </div>
                                                                        <div>
                                                                            <span class="fw-semibold">{{ $member['name'] }}</span>
                                                                            @if(isset($member['username']))
                                                                                <div><small class="text-muted">{{ $member['username'] }}</small></div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div>{{ $member['email'] }}</div>
                                                                    @if($member['phone'])
                                                                        <div><small class="text-muted">{{ $member['phone'] }}</small></div>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $member['join_date']->format('M d, Y') }}</td>
                                                                <td>
                                                                    @if($member['status'] == 'active')
                                                                        <span class="badge bg-success">Active</span>
                                                                    @else
                                                                        <span class="badge bg-warning">{{ ucfirst($member['status']) }}</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $status = $member['income_status'] ?? 'no_income';
                                                                    @endphp
                                                                    @if($status === 'paid')
                                                                        <span class="badge bg-success" title="First rank achiever - paid to interest_wallet">Paid</span>
                                                                    @elseif($status === 'pending')
                                                                        <span class="badge bg-warning" title="Upgraded account - pending until first rank achieved">Pending</span>
                                                                    @elseif($status === 'invalid')
                                                                        <span class="badge bg-danger" title="Free account - not eligible for generation income">Not Eligible</span>
                                                                    @else
                                                                        <span class="badge bg-secondary">No Income</span>
                                                                    @endif
                                                                </td>
                                                                <td class="fw-semibold text-success">
                                                                    ৳{{ number_format($member['generation_income'] ?? 0, 2) }}
                                                                    <div><small class="text-muted">Potential: ৳{{ number_format($member['potential_amount'] ?? 0, 2) }}</small></div>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-info">{{ $member['points'] ?? 0 }} Points</span>
                                                                </td>
                                                                <td>
                                                                    @if($member['has_downline'])
                                                                        <button class="btn btn-sm btn-outline-primary" 
                                                                                onclick="showLevelSelector({{ $member['id'] }}, this)"
                                                                                data-user-id="{{ $member['id'] }}"
                                                                                data-bs-toggle="tooltip" 
                                                                                title="View Network Levels">
                                                                            <i class="fe fe-layers"></i> Levels
                                                                        </button>
                                                                    @else
                                                                        <span class="text-muted">No downline</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <!-- Downline Container Row -->
                                                            <tr class="downline-container d-none" id="downline-{{ $member['id'] }}">
                                                                <td colspan="8">
                                                                    <div class="p-3 bg-light border rounded">
                                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                                            <h6 class="mb-0 text-primary">{{ $member['name'] }}'s Network</h6>
                                                                            <button class="btn btn-sm btn-outline-secondary" onclick="closeDownline({{ $member['id'] }})">
                                                                                <i class="fe fe-x"></i> Close
                                                                            </button>
                                                                        </div>
                                                                        
                                                                        <!-- Level Selector -->
                                                                        <div class="level-selector mb-3" id="levelSelector-{{ $member['id'] }}">
                                                                            <div class="text-center">
                                                                                <h6 class="mb-3">Select Level to View</h6>
                                                                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                                                                    <button class="btn btn-outline-primary btn-sm" onclick="loadLevelData({{ $member['id'] }}, 1)">
                                                                                        <i class="fe fe-users"></i> Level 1 (Direct)
                                                                                    </button>
                                                                                    <button class="btn btn-outline-success btn-sm" onclick="loadLevelData({{ $member['id'] }}, 2)">
                                                                                        <i class="fe fe-users"></i> Level 2
                                                                                    </button>
                                                                                    <button class="btn btn-outline-warning btn-sm" onclick="loadLevelData({{ $member['id'] }}, 3)">
                                                                                        <i class="fe fe-users"></i> Level 3
                                                                                    </button>
                                                                                    <button class="btn btn-outline-info btn-sm" onclick="loadLevelData({{ $member['id'] }}, 4)">
                                                                                        <i class="fe fe-users"></i> Level 4
                                                                                    </button>
                                                                                    <button class="btn btn-outline-danger btn-sm" onclick="loadLevelData({{ $member['id'] }}, 5)">
                                                                                        <i class="fe fe-users"></i> Level 5
                                                                                    </button>
                                                                                </div>
                                                                                <div class="mt-2">
                                                                                    <button class="btn btn-secondary btn-sm" onclick="loadLevelData({{ $member['id'] }}, 'all')">
                                                                                        <i class="fe fe-layers"></i> All Levels (1-5)
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <!-- Data Display Area -->
                                                                        <div class="downline-content d-none" id="dataDisplay-{{ $member['id'] }}" data-user-id="{{ $member['id'] }}">
                                                                            <!-- Level data will be loaded here -->
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="text-center py-4">
                                                    <div class="avatar avatar-md bg-light mb-2">
                                                        <i class="fe fe-users text-muted"></i>
                                                    </div>
                                                    <p class="text-muted mb-0">No members in Generation {{ $generation['level'] }} yet</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Load More Button -->
                            @if(count($generations) >= 20)
                                <div class="text-center mt-4">
                                    <button class="btn btn-primary" onclick="loadMoreGenerations()">
                                        <i class="fe fe-plus me-1"></i>Load More Generations
                                    </button>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.generation-level {
    transition: all 0.3s ease;
}

.generation-level:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.downline-container {
    transition: all 0.3s ease;
}

.downline-container .table {
    font-size: 0.875rem;
}

.downline-container .table th {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
    font-weight: 600;
    padding: 0.5rem;
}

.downline-container .table td {
    padding: 0.5rem;
    vertical-align: middle;
}

.downline-container .pagination {
    margin-bottom: 0;
}

.downline-container .pagination .page-link {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.badge-level {
    font-weight: 500;
    font-size: 0.75rem;
}

.search-filters {
    background: #f8f9fa;
    border-radius: 0.375rem;
    padding: 1rem;
    margin-bottom: 1rem;
}

.level-selector {
    background: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 1.5rem;
}

.level-selector .btn {
    min-width: 120px;
    margin: 0.25rem;
    transition: all 0.2s ease;
}

.level-selector .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.level-filter-btn {
    transition: all 0.2s ease;
    border-radius: 20px;
    font-weight: 500;
}

.level-filter-btn.active {
    background-color: #007bff !important;
    color: white !important;
    border-color: #007bff !important;
    box-shadow: 0 2px 4px rgba(0,123,255,0.3);
}

.level-filter-btn:hover:not(.active) {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.generation-level {
    display: none;
}

.generation-level.show-level {
    display: block;
}

.downline-member {
    padding: 10px;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    margin-bottom: 8px;
    background: white;
    transition: all 0.2s ease;
}

.downline-member:hover {
    border-color: #007bff;
    box-shadow: 0 2px 4px rgba(0,123,255,0.1);
}

.member-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 10px;
    margin-top: 10px;
}

.stat-item {
    text-align: center;
    padding: 8px;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    background: #f8f9fa;
}

.loading-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Show level selector
function showLevelSelector(userId, button) {
    const container = document.getElementById(`downline-${userId}`);
    const levelSelector = document.getElementById(`levelSelector-${userId}`);
    const dataDisplay = document.getElementById(`dataDisplay-${userId}`);
    const icon = button.querySelector('i');
    
    if (container.classList.contains('d-none')) {
        // Show level selector
        container.classList.remove('d-none');
        levelSelector.classList.remove('d-none');
        dataDisplay.classList.add('d-none');
        icon.classList.remove('fe-layers');
        icon.classList.add('fe-chevron-up');
        button.innerHTML = '<i class="fe fe-chevron-up"></i> Hide';
    } else {
        // Hide everything
        container.classList.add('d-none');
        icon.classList.remove('fe-chevron-up');
        icon.classList.add('fe-layers');
        button.innerHTML = '<i class="fe fe-layers"></i> Levels';
    }
}

// Load specific level data
function loadLevelData(userId, level) {
    const levelSelector = document.getElementById(`levelSelector-${userId}`);
    const dataDisplay = document.getElementById(`dataDisplay-${userId}`);
    const contentElement = dataDisplay;
    
    // Hide level selector and show data display
    levelSelector.classList.add('d-none');
    dataDisplay.classList.remove('d-none');
    
    // Show loading
    contentElement.innerHTML = `
        <div class="text-center py-3">
            <div class="loading-spinner me-2"></div>
            <span>Loading ${level === 'all' ? 'all levels' : 'level ' + level} data...</span>
        </div>
    `;
    
    // Add back button
    const backButton = `
        <div class="mb-3">
            <button class="btn btn-sm btn-outline-secondary" onclick="showLevelSelector(${userId}, document.querySelector('[data-user-id=\\"${userId}\\"]').closest('tr').querySelector('button'))">
                <i class="fe fe-arrow-left"></i> Back to Level Selection
            </button>
        </div>
    `;
    
    const requestData = {
        user_id: userId,
        page: 1,
        per_page: 20,
        search: '',
        level: level === 'all' ? '' : level,
        status: ''
    };
    
    fetch('{{ route("member.generations.downline") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.downline && data.downline.data && data.downline.data.length > 0) {
            renderSimpleDownlineTable(contentElement, data.downline, userId, level, backButton);
        } else {
            contentElement.innerHTML = backButton + `
                <div class="text-center py-4">
                    <div class="avatar avatar-md bg-light mb-2">
                        <i class="fe fe-users text-muted"></i>
                    </div>
                    <h6 class="text-muted">No members found</h6>
                    <p class="text-muted mb-0 small">No members in ${level === 'all' ? 'any level' : 'level ' + level}</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error loading level data:', error);
        contentElement.innerHTML = backButton + `
            <div class="text-center py-3 text-danger">
                <i class="fe fe-alert-circle me-1"></i>
                <span>Failed to load data</span>
                <button class="btn btn-sm btn-outline-primary ms-2" onclick="loadLevelData(${userId}, '${level}')">
                    <i class="fe fe-refresh-cw"></i> Retry
                </button>
            </div>
        `;
    });
}

// Render simple downline table without search
function renderSimpleDownlineTable(contentElement, paginatedData, userId, level, backButton) {
    const members = paginatedData.data;
    const pagination = paginatedData;
    
    let tableHtml = `
        ${backButton}
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">${level === 'all' ? 'All Levels (1-5)' : 'Level ' + level} Members</h6>
                <span class="badge bg-primary">${pagination.total || 0} Total</span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        ${level === 'all' ? '<th>Level</th>' : ''}
                        <th>Member</th>
                        <th>Contact</th>
                        <th>Join Date</th>
                        <th>Status</th>
                        <th>Business</th>
                        <th>Sponsor</th>
                        <th>Downline</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    members.forEach(member => {
        const statusBadge = member.status === 'active' ? 
            '<span class="badge bg-success">Active</span>' : 
            `<span class="badge bg-warning">${member.status.charAt(0).toUpperCase() + member.status.slice(1)}</span>`;
            
        tableHtml += `
            <tr>
                ${level === 'all' ? `<td><span class="badge bg-primary-transparent">L${member.level || 1}</span></td>` : ''}
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm avatar-rounded me-2">
                            <span class="fw-semibold">${member.name.substring(0, 2).toUpperCase()}</span>
                        </div>
                        <div>
                            <div class="fw-semibold">${member.name}</div>
                            <small class="text-muted">ID: ${member.id}</small>
                            ${member.username ? `<div><small class="text-muted">@${member.username}</small></div>` : ''}
                        </div>
                    </div>
                </td>
                <td>
                    <div>${member.email}</div>
                    ${member.phone ? `<div><small class="text-muted">${member.phone}</small></div>` : ''}
                </td>
                <td>
                    <div>${member.join_date}</div>
                    <small class="text-muted">${member.join_time || ''}</small>
                </td>
                <td>${statusBadge}</td>
                <td>
                    <div class="fw-semibold text-success">৳${parseFloat(member.business || 0).toFixed(2)}</div>
                    <small class="text-muted">${member.orders_count || 0} orders</small>
                </td>
                <td>
                    <div class="fw-semibold">${member.sponsor_name || 'N/A'}</div>
                    ${member.sponsor_id ? `<small class="text-muted">ID: ${member.sponsor_id}</small>` : ''}
                </td>
                <td>
                    <div class="fw-semibold text-primary">${member.downline_count || 0}</div>
                    <small class="text-muted">members</small>
                </td>
            </tr>
        `;
    });
    
    tableHtml += `
                </tbody>
            </table>
        </div>
    `;
    
    // Add simple pagination if needed
    if (pagination.last_page > 1) {
        tableHtml += renderSimplePagination(pagination, userId, level);
    }
    
    contentElement.innerHTML = tableHtml;
}

// Simple pagination for level data
function renderSimplePagination(pagination, userId, level) {
    const currentPage = pagination.current_page;
    const lastPage = pagination.last_page;
    
    let paginationHtml = `
        <div class="d-flex justify-content-center mt-3">
            <nav>
                <ul class="pagination pagination-sm mb-0">
    `;
    
    // Previous button
    if (currentPage > 1) {
        paginationHtml += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="loadLevelDataPage(${userId}, '${level}', ${currentPage - 1}); return false;">
                    <i class="fe fe-chevron-left"></i>
                </a>
            </li>
        `;
    }
    
    // Page numbers (show max 5 pages)
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(lastPage, startPage + 4);
    
    for (let i = startPage; i <= endPage; i++) {
        const activeClass = i === currentPage ? 'active' : '';
        paginationHtml += `
            <li class="page-item ${activeClass}">
                <a class="page-link" href="#" onclick="loadLevelDataPage(${userId}, '${level}', ${i}); return false;">${i}</a>
            </li>
        `;
    }
    
    // Next button
    if (currentPage < lastPage) {
        paginationHtml += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="loadLevelDataPage(${userId}, '${level}', ${currentPage + 1}); return false;">
                    <i class="fe fe-chevron-right"></i>
                </a>
            </li>
        `;
    }
    
    paginationHtml += `
                </ul>
            </nav>
        </div>
    `;
    
    return paginationHtml;
}

// Load specific page of level data
function loadLevelDataPage(userId, level, page) {
    const requestData = {
        user_id: userId,
        page: page,
        per_page: 20,
        search: '',
        level: level === 'all' ? '' : level,
        status: ''
    };
    
    const contentElement = document.getElementById(`dataDisplay-${userId}`);
    
    fetch('{{ route("member.generations.downline") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.downline && data.downline.data) {
            const backButton = `
                <div class="mb-3">
                    <button class="btn btn-sm btn-outline-secondary" onclick="showLevelSelector(${userId}, document.querySelector('[data-user-id=\\"${userId}\\"]').closest('tr').querySelector('button'))">
                        <i class="fe fe-arrow-left"></i> Back to Level Selection
                    </button>
                </div>
            `;
            renderSimpleDownlineTable(contentElement, data.downline, userId, level, backButton);
        }
    })
    .catch(error => {
        console.error('Error loading page:', error);
    });
}

// Close downline view
function closeDownline(userId) {
    const container = document.getElementById(`downline-${userId}`);
    const button = document.querySelector(`button[onclick="showLevelSelector(${userId}, this)"]`);
    const icon = button.querySelector('i');
    
    container.classList.add('d-none');
    icon.classList.remove('fe-chevron-up');
    icon.classList.add('fe-layers');
    button.innerHTML = '<i class="fe fe-layers"></i> Levels';
}

// Refresh generations
function refreshGenerations() {
    location.reload();
}

// Export generations
function exportGenerations() {
    alert('Export functionality will be implemented soon');
}

// Load more generations
function loadMoreGenerations() {
    alert('Load more functionality will be implemented soon');
}

// Filter by specific level
function filterByLevel(level) {
    // Update button states
    document.querySelectorAll('.level-filter-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.classList.contains('btn-primary')) {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline-primary');
        }
    });
    
    // Activate clicked button
    const clickedBtn = document.querySelector(`[data-level="${level}"]`);
    if (clickedBtn) {
        clickedBtn.classList.add('active');
        clickedBtn.classList.remove('btn-outline-primary');
        clickedBtn.classList.add('btn-primary');
    }
    
    // Show/hide generation levels
    const allLevels = document.querySelectorAll('.generation-level');
    
    if (level === 'all') {
        // Show all levels
        allLevels.forEach(levelElement => {
            levelElement.classList.add('show-level');
        });
        
        // Update toggle switch
        document.getElementById('showAllLevels').checked = true;
    } else {
        // Show only selected level
        allLevels.forEach(levelElement => {
            const levelNum = levelElement.getAttribute('data-level');
            if (levelNum == level) {
                levelElement.classList.add('show-level');
            } else {
                levelElement.classList.remove('show-level');
            }
        });
        
        // Update toggle switch
        document.getElementById('showAllLevels').checked = false;
    }
    
    // Scroll to first visible level
    const firstVisible = document.querySelector('.generation-level.show-level');
    if (firstVisible && level !== 'all') {
        firstVisible.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// Initialize with first available level by default
function initializeLevelFilter() {
    // Get the first available level from the existing generation data
    const firstLevelBtn = document.querySelector('.level-filter-btn[data-level]:not([data-level="all"])');
    if (firstLevelBtn) {
        const firstLevel = firstLevelBtn.getAttribute('data-level');
        filterByLevel(firstLevel);
    } else {
        // If no levels available, show all (or nothing)
        filterByLevel('all');
    }
}

// Show/hide levels toggle (update to work with level filter)
document.getElementById('showAllLevels').addEventListener('change', function() {
    if (this.checked) {
        filterByLevel('all');
    } else {
        // If unchecked, show first available level
        const firstLevelBtn = document.querySelector('.level-filter-btn[data-level]:not([data-level="all"])');
        if (firstLevelBtn) {
            const firstLevel = firstLevelBtn.getAttribute('data-level');
            filterByLevel(firstLevel);
        }
    }
});

// Initialize tooltips and level filter
document.addEventListener('DOMContentLoaded', function() {
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
    
    // Initialize with Level 1 as default
    initializeLevelFilter();
});
</script>
@endsection
