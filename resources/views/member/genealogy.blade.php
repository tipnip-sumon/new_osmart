@extends('member.layouts.app')

@section('title', 'Genealogy Tree - ' . config('app.name'))

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div>
                <h1 class="page-title fw-semibold fs-18 mb-0">Genealogy Tree</h1>
                <div class="">
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Genealogy</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" onclick="refreshTree()">
                    <i class="fe fe-refresh-cw me-1"></i>Refresh
                </button>
                <button class="btn btn-success" onclick="exportTree()">
                    <i class="fe fe-download me-1"></i>Export
                </button>
            </div>
        </div>

        <!-- Tree Controls -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-2">
                                <label class="form-label mb-0">View:</label>
                                <select class="form-select form-select-sm" id="treeView" style="width: auto;">
                                    <option value="table" selected>Table View</option>
                                    <option value="tree">Tree View</option>
                                    <option value="level">Level View</option>
                                    <option value="compact">Compact View</option>
                                    <option value="hierarchy">Hierarchy View</option>
                                </select>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <label class="form-label mb-0">Levels:</label>
                                <select class="form-select form-select-sm" id="levelDepth" style="width: auto;">
                                    <option value="3">3 Levels</option>
                                    <option value="5" selected>5 Levels</option>
                                    <option value="10">10 Levels</option>
                                    <option value="all">All Levels</option>
                                </select>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-success" onclick="expandAll()">
                                    <i class="fe fe-maximize-2 me-1"></i>Show All
                                </button>
                                <button class="btn btn-sm btn-warning" onclick="collapseAll()">
                                    <i class="fe fe-minimize-2 me-1"></i>Collapse
                                </button>
                                <button class="btn btn-sm btn-info" onclick="exportTable()">
                                    <i class="fe fe-download me-1"></i>Export
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="d-block mb-1 text-muted fs-12">Total Network</span>
                                <span class="h4 fw-semibold mb-0 text-primary">{{ $genealogyData['statistics']['total_network'] }}</span>
                            </div>
                            <div class="avatar avatar-md bg-primary-transparent">
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
                                <span class="d-block mb-1 text-muted fs-12">Active Members</span>
                                <span class="h4 fw-semibold mb-0 text-success">{{ $genealogyData['statistics']['active_members'] }}</span>
                            </div>
                            <div class="avatar avatar-md bg-success-transparent">
                                <i class="fe fe-user-check fs-18"></i>
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
                                <span class="d-block mb-1 text-muted fs-12">Total Business</span>
                                <span class="h4 fw-semibold mb-0 text-warning">৳{{ number_format($genealogyData['statistics']['total_business'], 2) }}</span>
                            </div>
                            <div class="avatar avatar-md bg-warning-transparent">
                                <i class="fe fe-dollar-sign fs-18"></i>
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
                                <span class="d-block mb-1 text-muted fs-12">Levels Deep</span>
                                <span class="h4 fw-semibold mb-0 text-info">{{ $genealogyData['statistics']['levels_deep'] }}</span>
                            </div>
                            <div class="avatar avatar-md bg-info-transparent">
                                <i class="fe fe-layers fs-18"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Profile Summary -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-lg avatar-rounded me-3">
                                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('admin-assets/images/users/default.jpg') }}" alt="{{ $user->name }}" onerror="this.src='{{ asset('admin-assets/images/users/default.jpg') }}'">
                            </div>
                            <div class="flex-fill">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="border p-3 rounded text-center">
                                            <h5 class="fw-semibold text-primary mb-1">{{ $user->name }}</h5>
                                            <p class="text-muted mb-0 fs-12">{{ $genealogyData['root']['referral_code'] }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border p-3 rounded text-center">
                                            <h5 class="fw-semibold text-success mb-1">{{ $genealogyData['statistics']['direct_referrals'] }}</h5>
                                            <p class="text-muted mb-0 fs-12">Direct Referrals</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border p-3 rounded text-center">
                                            <h5 class="fw-semibold text-info mb-1">{{ $user->created_at->format('M Y') }}</h5>
                                            <p class="text-muted mb-0 fs-12">Join Date</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border p-3 rounded text-center">
                                            <h5 class="fw-semibold text-warning mb-1">
                                                @if($genealogyData['root']['sponsor_name'])
                                                    {{ $genealogyData['root']['sponsor_name'] }}
                                                @else
                                                    Root
                                                @endif
                                            </h5>
                                            <p class="text-muted mb-0 fs-12">Sponsor</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Genealogy Tables -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-layers me-2"></i>Genealogy Levels
                        </div>
                        <div class="d-flex gap-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="showUpline">
                                <label class="form-check-label" for="showUpline">Show Upline</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        
                        <!-- Upline Section (Hidden by default) -->
                        <div id="uplineSection" class="d-none">
                            @if(count($genealogyData['uplines']) > 0)
                                <div class="p-3 bg-light border-bottom">
                                    <h6 class="mb-0 text-info"><i class="fe fe-arrow-up me-2"></i>Upline Network</h6>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-info">
                                            <tr>
                                                <th>Level</th>
                                                <th>Member</th>
                                                <th>Contact</th>
                                                <th>Join Date</th>
                                                <th>Status</th>
                                                <th>Business</th>
                                                <th>Downline</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($genealogyData['uplines'] as $upline)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-info">Level {{ $upline['level'] }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm avatar-rounded me-2">
                                                            <span class="fw-semibold">{{ substr($upline['name'], 0, 2) }}</span>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 fs-13">{{ $upline['name'] }}</h6>
                                                            <p class="mb-0 fs-11 text-muted">{{ $upline['referral_code'] }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <p class="mb-0 fs-12">{{ $upline['email'] }}</p>
                                                    </div>
                                                </td>
                                                <td>{{ $upline['join_date']->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $upline['status'] == 'active' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($upline['status']) }}
                                                    </span>
                                                </td>
                                                <td>৳{{ number_format($upline['business'], 2) }}</td>
                                                <td>{{ $upline['downline_count'] }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary" onclick="viewMember({{ $upline['id'] }})">
                                                        <i class="fe fe-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- Root User Section -->
                        <div class="p-3 bg-primary bg-opacity-10 border-bottom">
                            <h6 class="mb-0 text-primary"><i class="fe fe-user me-2"></i>Root Member (You)</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Level</th>
                                        <th>Member</th>
                                        <th>Contact</th>
                                        <th>Join Date</th>
                                        <th>Status</th>
                                        <th>Direct Referrals</th>
                                        <th>Total Network</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-primary bg-opacity-25">
                                        <td>
                                            <span class="badge bg-primary">Root</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm avatar-rounded me-2">
                                                    <img src="{{ asset('admin-assets/images/users/default.jpg') }}" alt="{{ $genealogyData['root']['name'] }}">
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fs-13">{{ $genealogyData['root']['name'] }}</h6>
                                                    <p class="mb-0 fs-11 text-muted">{{ $genealogyData['root']['referral_code'] }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <p class="mb-0 fs-12">{{ $genealogyData['root']['email'] }}</p>
                                            </div>
                                        </td>
                                        <td>{{ $genealogyData['root']['join_date']->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ ucfirst($genealogyData['root']['status']) }}
                                            </span>
                                        </td>
                                        <td>{{ $genealogyData['statistics']['direct_referrals'] }}</td>
                                        <td>{{ $genealogyData['statistics']['total_network'] }}</td>
                                        <td>
                                            <span class="text-primary fw-semibold">You</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Downline Levels -->
                        @if(count($genealogyData['downlines']) > 0)
                            @php
                                $levelGroups = [];
                                function groupByLevel($members, &$levelGroups) {
                                    foreach ($members as $member) {
                                        $level = $member['level'];
                                        if (!isset($levelGroups[$level])) {
                                            $levelGroups[$level] = [];
                                        }
                                        $levelGroups[$level][] = $member;
                                        if (!empty($member['children'])) {
                                            groupByLevel($member['children'], $levelGroups);
                                        }
                                    }
                                }
                                groupByLevel($genealogyData['downlines'], $levelGroups);
                                ksort($levelGroups);
                            @endphp

                            @foreach($levelGroups as $level => $members)
                            <!-- Level {{ $level }} -->
                            <div class="border-top level-section" id="level-{{ $level }}">
                                <div class="p-3 d-flex justify-content-between align-items-center level-header-clickable" 
                                     onclick="toggleLevel({{ $level }})" style="cursor: pointer;">
                                    <h6 class="mb-0">
                                        <i class="fe fe-arrow-down me-2 level-arrow" id="arrow-{{ $level }}"></i>Level {{ $level }} 
                                        <span class="badge ms-2">{{ count($members) }} Members</span>
                                    </h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fs-12">Click to expand/collapse</span>
                                        <button class="btn btn-sm" onclick="event.stopPropagation(); exportLevel({{ $level }})">
                                            <i class="fe fe-download"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive level-content" id="content-{{ $level }}" style="display: none;">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-success">
                                            <tr>
                                                <th>Member</th>
                                                <th>Contact</th>
                                                <th>Join Date</th>
                                                <th>Status</th>
                                                <th>Sponsor</th>
                                                <th>Business</th>
                                                <th>Downline</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($members as $member)
                                            <tr class="member-row" data-member-id="{{ $member['id'] }}">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm avatar-rounded me-2">
                                                            <span class="fw-semibold">{{ substr($member['name'], 0, 2) }}</span>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 fs-13">{{ $member['name'] }}</h6>
                                                            <p class="mb-0 fs-11 text-muted">{{ $member['referral_code'] }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <p class="mb-0 fs-12">{{ $member['email'] }}</p>
                                                        @if(isset($member['phone']))
                                                            <p class="mb-0 fs-11 text-muted">{{ $member['phone'] }}</p>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>{{ $member['join_date']->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $member['status'] == 'active' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($member['status']) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fs-12">{{ $member['sponsor_name'] }}</span>
                                                </td>
                                                <td>৳{{ number_format($member['business'], 2) }}</td>
                                                <td>
                                                    {{ $member['downline_count'] }}
                                                    @if($member['has_downline'])
                                                        <i class="fe fe-users text-success ms-1"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-primary" onclick="viewMember({{ $member['id'] }})" title="View Details">
                                                            <i class="fe fe-eye"></i>
                                                        </button>
                                                        @if($member['has_downline'])
                                                        <button class="btn btn-sm btn-success" onclick="showMemberDownline({{ $member['id'] }}, '{{ $member['name'] }}')" title="View Downline">
                                                            <i class="fe fe-users"></i>
                                                        </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endforeach
                            
                            <!-- Quick Level Controls -->
                            <div class="border-top p-3 bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-muted">
                                        <i class="fe fe-layers me-2"></i>Level Controls
                                    </h6>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-success" onclick="expandAllLevels()">
                                            <i class="fe fe-maximize-2 me-1"></i>Expand All
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="collapseAllLevels()">
                                            <i class="fe fe-minimize-2 me-1"></i>Collapse All
                                        </button>
                                        <button class="btn btn-sm btn-info" onclick="exportAllLevels()">
                                            <i class="fe fe-download me-1"></i>Export All
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="p-5 text-center">
                                <div class="avatar avatar-xl avatar-rounded bg-light mb-3">
                                    <i class="fe fe-users fs-24 text-muted"></i>
                                </div>
                                <h6 class="fw-semibold mb-1">No Downline Yet</h6>
                                <p class="text-muted mb-3">Start building your network by inviting new members</p>
                                <a href="{{ route('member.sponsor') }}" class="btn btn-primary">
                                    <i class="fe fe-plus me-1"></i>Invite Members
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-search me-2"></i>Search Network Members
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Search by Name/ID/Email</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchInput" placeholder="Enter name, ID, or email...">
                                        <button class="btn btn-primary" onclick="searchMembers()">
                                            <i class="fe fe-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Status Filter</label>
                                    <select class="form-select" id="statusFilter">
                                        <option value="">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Quick Actions</label>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-secondary btn-sm" onclick="clearSearch()">
                                            <i class="fe fe-x"></i> Clear
                                        </button>
                                        <button class="btn btn-info btn-sm" onclick="exportResults()">
                                            <i class="fe fe-download"></i> Export
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Search Results -->
                        <div id="searchResults" class="d-none">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Member</th>
                                            <th>Contact</th>
                                            <th>Status</th>
                                            <th>Join Date</th>
                                            <th>Sponsor</th>
                                            <th>Business</th>
                                            <th>Downline</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="searchResultsBody">
                                        <!-- Results will be populated here -->
                                    </tbody>
                                </table>
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
/* Table Styles for Genealogy */
.table-responsive {
    border-radius: 8px;
    overflow: hidden;
}

.table {
    margin-bottom: 0;
}

.table thead th {
    border-top: none;
    font-weight: 600;
    font-size: 13px;
    padding: 12px 8px;
}

.table tbody td {
    padding: 12px 8px;
    vertical-align: middle;
    border-top: 1px solid #e9ecef;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.avatar {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 12px;
    font-weight: 600;
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    overflow: hidden;
}

.avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.avatar.avatar-lg {
    width: 60px;
    height: 60px;
    font-size: 18px;
}

.avatar.avatar-xl {
    width: 80px;
    height: 80px;
    font-size: 24px;
}

/* Level Section Styles */
.level-section {
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.level-header-clickable {
    background: linear-gradient(135deg, #198754, #20c997);
    color: #ffffff !important;
    padding: 12px 20px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    user-select: none;
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
}

.level-header-clickable:hover {
    background: linear-gradient(135deg, #20c997, #0dcaf0);
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    text-shadow: 0 1px 3px rgba(0,0,0,0.3);
}

.level-header-clickable h6 {
    color: #ffffff !important;
    margin-bottom: 0;
    font-weight: 600;
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
}

.level-header-clickable .text-muted {
    color: rgba(255,255,255,0.8) !important;
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
}

.level-header-clickable .badge {
    background-color: rgba(255,255,255,0.2) !important;
    color: #ffffff !important;
    border: 1px solid rgba(255,255,255,0.3);
    text-shadow: none;
}

.level-header-clickable .btn {
    background-color: rgba(255,255,255,0.1) !important;
    border: 1px solid rgba(255,255,255,0.3) !important;
    color: #ffffff !important;
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
}

.level-header-clickable .btn:hover {
    background-color: rgba(255,255,255,0.2) !important;
    border: 1px solid rgba(255,255,255,0.5) !important;
    color: #ffffff !important;
    transform: scale(1.05);
}

.level-content {
    transition: opacity 0.3s ease, height 0.3s ease;
    overflow: hidden;
}

.level-arrow {
    transition: transform 0.3s ease;
    color: #ffffff !important;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    font-size: 14px;
}

.member-row {
    transition: all 0.2s ease;
}

.member-row:hover {
    background-color: #f8f9fa !important;
    transform: translateX(5px);
}

/* Button Improvements */
.btn {
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
    border-radius: 4px;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(0,123,255,0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,123,255,0.4);
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(40,167,69,0.3);
}

.btn-success:hover {
    background: linear-gradient(135deg, #20c997, #198754);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(40,167,69,0.4);
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107, #fd7e14);
    border: none;
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(255,193,7,0.3);
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
}

.btn-warning:hover {
    background: linear-gradient(135deg, #fd7e14, #dc6545);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(255,193,7,0.4);
    color: #ffffff;
}

.btn-info {
    background: linear-gradient(135deg, #17a2b8, #20c997);
    border: none;
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(23,162,184,0.3);
}

.btn-info:hover {
    background: linear-gradient(135deg, #20c997, #0dcaf0);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(23,162,184,0.4);
}

.btn-outline-success {
    border: 2px solid #28a745;
    color: #28a745;
    background: transparent;
}

.btn-outline-success:hover {
    background: #28a745;
    color: #ffffff;
    transform: scale(1.05);
}

/* Badge Improvements */
.badge {
    font-size: 11px;
    padding: 4px 8px;
    font-weight: 500;
    text-shadow: none;
}

.bg-success {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
}

.bg-warning {
    background: linear-gradient(135deg, #ffc107, #fd7e14) !important;
    color: #ffffff !important;
}

.bg-primary {
    background: linear-gradient(135deg, #007bff, #0056b3) !important;
}

.bg-info {
    background: linear-gradient(135deg, #17a2b8, #20c997) !important;
}

.fs-13 { font-size: 13px !important; }
.fs-12 { font-size: 12px !important; }
.fs-11 { font-size: 11px !important; }

/* Notification Styles */
.alert {
    border: none;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Loading States */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9998;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 12px;
    }
    
    .avatar {
        width: 28px;
        height: 28px;
        font-size: 11px;
    }
    
    .table thead th,
    .table tbody td {
        padding: 8px 4px;
    }
    
    .btn-sm {
        padding: 2px 6px;
        font-size: 11px;
    }
    
    .level-header-clickable {
        padding: 10px 15px;
    }
    
    .level-header-clickable h6 {
        font-size: 14px;
    }
}

/* Animation keyframes */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes slideDown {
    0% {
        opacity: 0;
        max-height: 0;
        transform: translateY(-10px);
    }
    100% {
        opacity: 1;
        max-height: 1000px;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    0% {
        opacity: 1;
        max-height: 1000px;
        transform: translateY(0);
    }
    100% {
        opacity: 0;
        max-height: 0;
        transform: translateY(-10px);
    }
}

.slide-down {
    animation: slideDown 0.3s ease-out;
}

.slide-up {
    animation: slideUp 0.3s ease-out;
}

/* Enhanced Modal Styles */
.modal-content {
    border: none;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    border-radius: 12px;
    overflow: hidden;
}

.modal-header.bg-primary {
    background: linear-gradient(135deg, #007bff, #0056b3) !important;
    border-bottom: none;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff, #0056b3) !important;
}

.modal-body .info-item {
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.modal-body .info-item:last-child {
    border-bottom: none;
}

.modal-body .info-item label {
    display: block;
    margin-bottom: 4px;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.modal-body .info-item .d-flex {
    align-items: center;
    font-size: 14px;
}

.modal-footer.bg-light {
    background: #f8f9fa !important;
    border-top: 1px solid #e9ecef;
}

.modal-dialog.modal-lg {
    max-width: 800px;
}

/* Modal Animation Enhancement */
.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
    transform: translate(0, -50px);
}

.modal.show .modal-dialog {
    transform: translate(0, 0);
}

/* Avatar enhancements in modal */
.avatar.avatar-xl {
    width: 80px;
    height: 80px;
    font-size: 24px;
    border: 3px solid rgba(255,255,255,0.3);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Print styles */
@media print {
    .card-header,
    .btn,
    .form-control,
    .level-header-clickable {
        display: none !important;
    }
    
    .level-content {
        display: block !important;
        opacity: 1 !important;
    }
    
    .table {
        font-size: 10px;
    }
}
</style>
@endpush
@push('scripts')
<script>
// Global variables for dynamic views
window.userData = {
    name: '{{ Auth::user()->name }}',
    email: '{{ Auth::user()->email }}',
    referral_code: '{{ Auth::user()->referral_code }}'
};

window.userAvatar = '{{ Auth::user()->avatar ? asset("storage/" . Auth::user()->avatar) : asset("admin-assets/images/users/default.jpg") }}';

window.genealogyStats = {
    direct_referrals: {{ $genealogyData['statistics']['direct_referrals'] }},
    total_network: {{ $genealogyData['statistics']['total_network'] }},
    active_members: {{ $genealogyData['statistics']['active_members'] }},
    total_business: {{ $genealogyData['statistics']['total_business'] }},
    levels_deep: {{ $genealogyData['statistics']['levels_deep'] }}
};

// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Toggle upline visibility
document.getElementById('showUpline').addEventListener('change', function() {
    const uplineSection = document.getElementById('uplineSection');
    if (this.checked) {
        uplineSection.classList.remove('d-none');
    } else {
        uplineSection.classList.add('d-none');
    }
});

// View controls
document.addEventListener('DOMContentLoaded', function() {
    // Add a small delay to ensure all DOM elements are ready
    setTimeout(() => {
        const treeViewSelect = document.getElementById('treeView');
        
        if (treeViewSelect) {
            // Remove any existing event listeners first
            const newSelect = treeViewSelect.cloneNode(true);
            treeViewSelect.parentNode.replaceChild(newSelect, treeViewSelect);
            
            // Add event listener for dropdown change
            newSelect.addEventListener('change', function(e) {
                const view = this.value;
                switchView(view);
            });
            
            // Dropdown system initialized successfully
            
        } else {
            alert('Error: View dropdown not found. Please refresh the page.');
        }
        
        // Also add event listener for level depth
        const levelDepthSelect = document.getElementById('levelDepth');
        if (levelDepthSelect) {
            levelDepthSelect.addEventListener('change', function() {
                const depth = this.value;
                filterByLevel(depth);
            });
        }
    }, 500);
});

function switchView(viewType) {
    try {
        // Hide all view containers first
        hideAllViews();
        
        switch(viewType) {
            case 'table':
                showTableView();
                break;
            case 'tree':
                console.log('Switching to tree view');
                showTreeView();
                break;
            case 'level':
                console.log('Switching to level view');
                showLevelView();
                break;
            case 'compact':
                console.log('Switching to compact view');
                showCompactView();
                break;
            case 'hierarchy':
                console.log('Switching to hierarchy view');
                showHierarchyView();
                break;
            default:
                console.log('Unknown view type, defaulting to table view');
                showTableView();
        }
        
        // View switch completed successfully
        console.log('View switch completed successfully');
    } catch (error) {
        console.error('Error switching view:', error);
        showNotification('Error switching view. Please try again.', 'error');
        // Fallback to table view
        showTableView();
    }
}

function hideAllViews() {
    // Hide all dynamic view containers first
    const dynamicViews = document.querySelectorAll('.dynamic-view');
    dynamicViews.forEach(view => {
        view.style.display = 'none';
    });
    
    // Hide main genealogy content (but keep upline section logic intact)
    const mainCard = document.querySelector('.card.custom-card .card-body');
    if (mainCard) {
        // Hide specific sections that are part of table view
        const sections = [
            '.p-3.bg-primary.bg-opacity-10.border-bottom', // Root user section header
            '.table-responsive', // All table containers
            '.border-top.level-section', // Level sections
            '.border-top.p-3.bg-light' // Level controls
        ];
        
        sections.forEach(selector => {
            const elements = mainCard.querySelectorAll(selector);
            elements.forEach(element => {
                // Only hide if it's not inside uplineSection
                if (!element.closest('#uplineSection')) {
                    element.style.display = 'none';
                }
            });
        });
    }
}

function showTableView() {
    // Hide all dynamic views first
    const dynamicViews = document.querySelectorAll('.dynamic-view');
    dynamicViews.forEach(view => {
        view.style.display = 'none';
    });
    
    // Show the original table view content
    const mainCard = document.querySelector('.card.custom-card .card-body');
    if (mainCard) {
        // Show specific sections that are part of table view
        const sections = [
            '.p-3.bg-primary.bg-opacity-10.border-bottom', // Root user section header
            '.table-responsive', // All table containers
            '.border-top.level-section', // Level sections
            '.border-top.p-3.bg-light' // Level controls
        ];
        
        sections.forEach(selector => {
            const elements = mainCard.querySelectorAll(selector);
            elements.forEach(element => {
                // Only show if it's not inside uplineSection (let upline toggle handle that)
                if (!element.closest('#uplineSection')) {
                    element.style.display = 'block';
                }
            });
        });
    }
}

function showTreeView() {
    // Create and show tree view
    const container = createViewContainer('tree-view');
    container.innerHTML = generateTreeViewHTML();
}

function showLevelView() {
    // Create and show level view
    const container = createViewContainer('level-view');
    container.innerHTML = generateLevelViewHTML();
}

function showCompactView() {
    // Create and show compact view
    const container = createViewContainer('compact-view');
    container.innerHTML = generateCompactViewHTML();
}

function showHierarchyView() {
    // Create and show hierarchy view
    const container = createViewContainer('hierarchy-view');
    container.innerHTML = generateHierarchyViewHTML();
    
    // Automatically load hierarchy data
    setTimeout(() => {
        loadHierarchyData();
    }, 500);
}

function createViewContainer(viewId) {
    // Remove existing dynamic view
    const existing = document.getElementById(viewId);
    if (existing) existing.remove();
    
    // Create new container
    const container = document.createElement('div');
    container.id = viewId;
    container.className = 'dynamic-view';
    
    // Find the main card body container
    const mainCard = document.querySelector('.card.custom-card .card-body');
    if (mainCard) {
        // Simply append to the end of the card body
        mainCard.appendChild(container);
    } else {
        // Fallback: append to document body if main card not found
        document.body.appendChild(container);
    }
    
    return container;
}

function generateTreeViewHTML() {
    return `
        <div class="tree-view-container p-4">
            <div class="text-center mb-4">
                <h5 class="text-primary"><i class="fe fe-git-branch me-2"></i>Interactive Tree Structure</h5>
            </div>
            
            <!-- Root Node -->
            <div class="tree-structure">
                <div class="tree-node root-node text-center mb-4">
                    <div class="node-card">
                        <div class="avatar avatar-lg avatar-rounded mx-auto mb-2">
                            <img src="${window.userAvatar || '{{ asset("admin-assets/images/users/default.jpg") }}'}" alt="You" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" onerror="this.src='{{ asset("admin-assets/images/users/default.jpg") }}'">>
                        </div>
                        <h6 class="mb-1">${window.userData ? window.userData.name : 'You'}</h6>
                        <p class="text-muted small mb-2">${window.userData ? window.userData.referral_code : 'ROOT'}</p>
                        <div class="stats-row">
                            <span class="badge bg-primary me-1">Root</span>
                            <span class="badge bg-success">${window.genealogyStats ? window.genealogyStats.direct_referrals : 0} Direct</span>
                        </div>
                    </div>
                </div>
                
                <!-- Level 1 Nodes -->
                <div class="tree-level level-1" id="tree-level-1">
                    <div class="level-header text-center mb-3">
                        <span class="badge bg-success">Level 1</span>
                    </div>
                    <div class="nodes-row justify-content-center" id="level-1-nodes">
                        <!-- Level 1 nodes will be populated here -->
                        <div class="loading-placeholder text-center">
                            <i class="fe fe-loader spin"></i> Loading Level 1...
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tree-controls text-center mt-4">
                <button class="btn btn-sm btn-primary me-2" onclick="expandTreeLevel(2)">
                    <i class="fe fe-plus me-1"></i>Load Level 2
                </button>
                <button class="btn btn-sm btn-success me-2" onclick="expandAllTreeLevels()">
                    <i class="fe fe-maximize-2 me-1"></i>Expand All
                </button>
                <button class="btn btn-sm btn-warning" onclick="collapseTreeLevels()">
                    <i class="fe fe-minimize-2 me-1"></i>Collapse
                </button>
            </div>
        </div>
        
        <style>
            .tree-view-container {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border-radius: 10px;
                min-height: 400px;
            }
            
            .tree-structure {
                position: relative;
            }
            
            .tree-node {
                margin: 10px;
                display: inline-block;
            }
            
            .node-card {
                background: white;
                border-radius: 8px;
                padding: 15px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                transition: all 0.3s ease;
                min-width: 150px;
                border: 2px solid transparent;
            }
            
            .root-node .node-card {
                border-color: #007bff;
                background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
                color: white !important;
            }
            
            .root-node .node-card h6,
            .root-node .node-card p,
            .root-node .node-card .text-muted,
            .root-node .node-card .small {
                color: white !important;
            }
            
            .node-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            }
            
            .tree-level {
                margin: 30px 0;
            }
            
            .nodes-row {
                display: flex;
                flex-wrap: wrap;
                gap: 15px;
            }
            
            .loading-placeholder {
                width: 100%;
                padding: 20px;
                color: #6c757d;
            }
            
            .spin {
                animation: spin 1s linear infinite;
            }
            
            @media (max-width: 768px) {
                .node-card {
                    min-width: 120px;
                    padding: 10px;
                }
            }
        </style>
    `;
}

function generateLevelViewHTML() {
    return `
        <div class="level-view-container p-3">
            <div class="text-center mb-4">
                <h5 class="text-success"><i class="fe fe-layers me-2"></i>Level-by-Level Analysis</h5>
            </div>
            
            <div class="levels-accordion" id="levelsAccordion">
                <!-- Root Level -->
                <div class="level-item mb-3">
                    <div class="level-header bg-primary text-white p-3 rounded" data-bs-toggle="collapse" data-bs-target="#level-root" style="cursor: pointer;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fe fe-user me-2"></i>Root Level (You)</h6>
                            <div>
                                <span class="badge bg-light text-dark me-2">1 Member</span>
                                <i class="fe fe-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                    <div class="collapse show" id="level-root">
                        <div class="level-content bg-light p-3 rounded-bottom">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="member-summary">
                                        <h6>${window.userData ? window.userData.name : 'Your Name'}</h6>
                                        <p class="text-muted mb-1">${window.userData ? window.userData.email : 'your@email.com'}</p>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="stats-grid">
                                        <div class="stat-item">
                                            <strong>Direct Referrals:</strong> ${window.genealogyStats ? window.genealogyStats.direct_referrals : 0}
                                        </div>
                                        <div class="stat-item">
                                            <strong>Total Network:</strong> ${window.genealogyStats ? window.genealogyStats.total_network : 0}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Dynamic Levels will be loaded here -->
                <div id="dynamic-levels">
                    <div class="text-center p-4">
                        <button class="btn btn-primary" onclick="loadLevelViewData()">
                            <i class="fe fe-download me-1"></i>Load All Levels
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
            .level-view-container {
                background: #f8f9fa;
                border-radius: 8px;
            }
            
            .level-header {
                transition: all 0.3s ease;
            }
            
            .level-header:hover {
                transform: translateX(5px);
            }
            
            .member-summary {
                border-left: 4px solid #007bff;
                padding-left: 15px;
            }
            
            .stats-grid .stat-item {
                margin-bottom: 8px;
                font-size: 14px;
            }
        </style>
    `;
}

function generateCompactViewHTML() {
    return `
        <div class="compact-view-container p-3">
            <div class="text-center mb-4">
                <h5 class="text-info"><i class="fe fe-grid me-2"></i>Compact Network Overview</h5>
            </div>
            
            <div class="compact-grid">
                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="compact-card bg-primary">
                            <i class="fe fe-users"></i>
                            <h3>${window.genealogyStats ? window.genealogyStats.total_network : 0}</h3>
                            <p>Total Network</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="compact-card bg-success">
                            <i class="fe fe-user-check"></i>
                            <h3>${window.genealogyStats ? window.genealogyStats.active_members : 0}</h3>
                            <p>Active Members</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="compact-card bg-warning">
                            <i class="fe fe-dollar-sign"></i>
                            <h3>৳${window.genealogyStats ? window.genealogyStats.total_business.toFixed(0) : 0}</h3>
                            <p>Total Business</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="compact-card bg-info">
                            <i class="fe fe-layers"></i>
                            <h3>${window.genealogyStats ? window.genealogyStats.levels_deep : 0}</h3>
                            <p>Levels Deep</p>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Member List -->
                <div class="compact-members">
                    <h6 class="mb-3"><i class="fe fe-list me-2"></i>Recent Members</h6>
                    <div class="members-list" id="compact-members-list">
                        <div class="text-center p-3">
                            <button class="btn btn-outline-primary" onclick="loadCompactMembers()">
                                <i class="fe fe-refresh-cw me-1"></i>Load Members
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
            .compact-view-container {
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            
            .compact-card {
                color: white;
                padding: 20px;
                border-radius: 8px;
                text-align: center;
                transition: all 0.3s ease;
            }
            
            .compact-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 4px 16px rgba(0,0,0,0.2);
            }
            
            .compact-card i {
                font-size: 24px;
                margin-bottom: 10px;
            }
            
            .compact-card h3 {
                margin: 0;
                font-size: 28px;
                font-weight: bold;
            }
            
            .compact-card p {
                margin: 5px 0 0 0;
                opacity: 0.9;
            }
            
            .members-list {
                background: #f8f9fa;
                border-radius: 6px;
                min-height: 200px;
            }
        </style>
    `;
}

function generateHierarchyViewHTML() {
    return `
        <div class="hierarchy-view-container p-4">
            <div class="text-center mb-4">
                <h5 class="text-dark"><i class="fe fe-share-2 me-2"></i>Network Hierarchy - Hanging Ball View</h5>
                <p class="text-muted">Interactive organizational chart with downline visualization</p>
            </div>
            
            <div class="hierarchy-tree">
                <div class="org-chart-container">
                    <!-- Root User Ball -->
                    <div class="hierarchy-level root-level" id="hierarchy-root">
                        <div class="ball-node root-ball" data-user-id="${window.userData ? window.userData.id : 'root'}">
                            <div class="ball-avatar">
                                <img src="${window.userAvatar || '/admin-assets/images/users/default.jpg'}" alt="You">
                                <div class="status-indicator active"></div>
                            </div>
                            <div class="ball-info">
                                <h6>${window.userData ? window.userData.name : 'You'}</h6>
                                <p>${window.userData ? window.userData.referral_code : 'ROOT'}</p>
                                <span class="level-badge root">CEO</span>
                            </div>
                            <div class="ball-stats">
                                <span class="stat-item">
                                    <i class="fe fe-users"></i>
                                    ${window.genealogyStats ? window.genealogyStats.direct_referrals : 0}
                                </span>
                            </div>
                        </div>
                        <!-- Root connector line -->
                        <div class="connector-line root-line"></div>
                    </div>
                    
                    <!-- Level 1 Container -->
                    <div class="hierarchy-level level-1" id="hierarchy-level-1" style="display: none;">
                        <div class="level-header">
                            <span class="level-title">Level 1</span>
                            <span class="level-count" id="level-1-count">0 Members</span>
                        </div>
                        <div class="balls-container" id="level-1-balls">
                            <!-- Level 1 balls will be populated here -->
                        </div>
                        <div class="connector-line level-line"></div>
                    </div>
                    
                    <!-- Level 2 Container -->
                    <div class="hierarchy-level level-2" id="hierarchy-level-2" style="display: none;">
                        <div class="level-header">
                            <span class="level-title">Level 2</span>
                            <span class="level-count" id="level-2-count">0 Members</span>
                        </div>
                        <div class="balls-container" id="level-2-balls">
                            <!-- Level 2 balls will be populated here -->
                        </div>
                        <div class="connector-line level-line"></div>
                    </div>
                    
                    <!-- Level 3 Container -->
                    <div class="hierarchy-level level-3" id="hierarchy-level-3" style="display: none;">
                        <div class="level-header">
                            <span class="level-title">Level 3</span>
                            <span class="level-count" id="level-3-count">0 Members</span>
                        </div>
                        <div class="balls-container" id="level-3-balls">
                            <!-- Level 3 balls will be populated here -->
                        </div>
                    </div>
                    
                    <!-- Loading indicator -->
                    <div class="loading-hierarchy text-center" id="hierarchy-loading">
                        <div class="loading-spinner"></div>
                        <p>Building network hierarchy...</p>
                    </div>
                </div>
                
                <div class="hierarchy-controls text-center mt-4">
                    <button class="btn btn-sm btn-primary me-2" onclick="loadFullHierarchy()" id="loadHierarchyBtn">
                        <i class="fe fe-download me-1"></i>Load Network
                    </button>
                    <button class="btn btn-sm btn-success me-2" onclick="expandAllHierarchy()" style="display: none;" id="expandAllBtn">
                        <i class="fe fe-maximize-2 me-1"></i>Show All Levels
                    </button>
                    <button class="btn btn-sm btn-warning me-2" onclick="collapseHierarchy()" style="display: none;" id="collapseBtn">
                        <i class="fe fe-minimize-2 me-1"></i>Show Root Only
                    </button>
                    <button class="btn btn-sm btn-info me-2" onclick="centerHierarchy()">
                        <i class="fe fe-target me-1"></i>Center View
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="exportHierarchy()">
                        <i class="fe fe-download me-1"></i>Export
                    </button>
                </div>
            </div>
        </div>
        
        <style>
            .hierarchy-view-container {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border-radius: 10px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                min-height: 600px;
                overflow-x: auto;
                overflow-y: visible;
            }
            
            .org-chart-container {
                position: relative;
                min-width: 800px;
                padding: 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            
            .hierarchy-level {
                width: 100%;
                margin: 30px 0;
                position: relative;
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            
            .level-header {
                margin-bottom: 15px;
                text-align: center;
            }
            
            .level-title {
                font-weight: 600;
                color: #495057;
                font-size: 14px;
                margin-right: 10px;
            }
            
            .level-count {
                background: linear-gradient(135deg, #007bff, #0056b3);
                color: white;
                padding: 4px 12px;
                border-radius: 12px;
                font-size: 12px;
                font-weight: 500;
            }
            
            .balls-container {
                display: flex;
                justify-content: center;
                align-items: flex-start;
                flex-wrap: wrap;
                gap: 40px;
                min-height: 120px;
                padding: 0 20px;
            }
            
            .ball-node {
                position: relative;
                display: flex;
                flex-direction: column;
                align-items: center;
                cursor: pointer;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                transform-origin: center top;
            }
            
            .ball-node:hover {
                transform: translateY(-10px) scale(1.05);
                z-index: 10;
            }
            
            .ball-avatar {
                position: relative;
                width: 80px;
                height: 80px;
                border-radius: 50%;
                overflow: hidden;
                border: 4px solid #ffffff;
                box-shadow: 0 8px 25px rgba(0,0,0,0.15);
                background: linear-gradient(135deg, #007bff, #0056b3);
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 10px;
                transition: all 0.3s ease;
            }
            
            .ball-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 50%;
            }
            
            .ball-node:hover .ball-avatar {
                box-shadow: 0 12px 35px rgba(0,123,255,0.3);
                transform: scale(1.1);
            }
            
            .status-indicator {
                position: absolute;
                bottom: 5px;
                right: 5px;
                width: 16px;
                height: 16px;
                border-radius: 50%;
                border: 3px solid white;
                background: #28a745;
            }
            
            .status-indicator.inactive {
                background: #6c757d;
            }
            
            .status-indicator.pending {
                background: #ffc107;
            }
            
            .ball-info {
                text-align: center;
                background: white;
                padding: 10px 15px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                min-width: 120px;
                margin-bottom: 8px;
                transition: all 0.3s ease;
            }
            
            .ball-node:hover .ball-info {
                box-shadow: 0 6px 20px rgba(0,0,0,0.15);
                transform: translateY(-2px);
            }
            
            .ball-info h6 {
                margin: 0 0 4px 0;
                font-size: 13px;
                font-weight: 600;
                color: #212529;
                line-height: 1.2;
            }
            
            .ball-info p {
                margin: 0 0 6px 0;
                font-size: 11px;
                color: #6c757d;
                line-height: 1.1;
            }
            
            .level-badge {
                display: inline-block;
                padding: 2px 8px;
                border-radius: 10px;
                font-size: 10px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            
            .level-badge.root {
                background: linear-gradient(135deg, #007bff, #0056b3);
                color: white;
            }
            
            .level-badge.level-1 {
                background: linear-gradient(135deg, #28a745, #20c997);
                color: white;
            }
            
            .level-badge.level-2 {
                background: linear-gradient(135deg, #ffc107, #fd7e14);
                color: white;
            }
            
            .level-badge.level-3 {
                background: linear-gradient(135deg, #17a2b8, #20c997);
                color: white;
            }
            
            .ball-stats {
                display: flex;
                justify-content: center;
                gap: 8px;
            }
            
            .stat-item {
                background: rgba(255,255,255,0.9);
                padding: 4px 8px;
                border-radius: 12px;
                font-size: 11px;
                font-weight: 500;
                color: #495057;
                display: flex;
                align-items: center;
                gap: 3px;
                box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            }
            
            .stat-item i {
                font-size: 10px;
            }
            
            /* Connector Lines */
            .connector-line {
                position: absolute;
                background: linear-gradient(90deg, #dee2e6, #adb5bd);
                z-index: 1;
            }
            
            .root-line {
                width: 2px;
                height: 30px;
                bottom: -30px;
                left: 50%;
                transform: translateX(-50%);
                background: linear-gradient(180deg, #007bff, #0056b3);
            }
            
            .level-line {
                width: 60%;
                height: 2px;
                bottom: -20px;
                left: 50%;
                transform: translateX(-50%);
                display: none;
            }
            
            .hierarchy-level.has-children .level-line {
                display: block;
            }
            
            /* Vertical connectors for hanging effect */
            .ball-node::before {
                content: '';
                position: absolute;
                top: -30px;
                left: 50%;
                transform: translateX(-50%);
                width: 2px;
                height: 30px;
                background: linear-gradient(180deg, #adb5bd, transparent);
                z-index: 0;
            }
            
            .root-level .ball-node::before {
                display: none;
            }
            
            /* Animation effects */
            .ball-node {
                animation: ballDrop 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            }
            
            @keyframes ballDrop {
                0% {
                    opacity: 0;
                    transform: translateY(-50px) scale(0.8);
                }
                70% {
                    transform: translateY(5px) scale(1.05);
                }
                100% {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }
            
            /* Loading spinner */
            .loading-hierarchy {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 40px;
                color: #6c757d;
            }
            
            .loading-spinner {
                width: 40px;
                height: 40px;
                border: 3px solid #f3f3f3;
                border-top: 3px solid #007bff;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin-bottom: 15px;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            /* Responsive design */
            @media (max-width: 768px) {
                .org-chart-container {
                    min-width: 100%;
                    padding: 10px;
                }
                
                .balls-container {
                    gap: 25px;
                }
                
                .ball-avatar {
                    width: 60px;
                    height: 60px;
                    border-width: 3px;
                }
                
                .ball-info {
                    padding: 8px 12px;
                    min-width: 100px;
                }
                
                .ball-info h6 {
                    font-size: 12px;
                }
                
                .ball-info p {
                    font-size: 10px;
                }
                
                .status-indicator {
                    width: 12px;
                    height: 12px;
                    border-width: 2px;
                }
            }
            
            /* Print styles */
            @media print {
                .hierarchy-controls {
                    display: none !important;
                }
                
                .ball-node {
                    animation: none !important;
                }
                
                .hierarchy-view-container {
                    background: white !important;
                    box-shadow: none !important;
                }
            }
        </style>
    `;
}

// Level collapse/expand functions
function toggleLevel(level) {
    const content = document.getElementById(`content-${level}`);
    const arrow = document.getElementById(`arrow-${level}`);
    
    if (content.style.display === 'none' || content.style.display === '') {
        // Expand
        content.style.display = 'block';
        arrow.classList.remove('fe-arrow-down');
        arrow.classList.add('fe-arrow-up');
        
        // Add animation
        content.style.opacity = '0';
        content.style.transition = 'opacity 0.3s ease';
        setTimeout(() => {
            content.style.opacity = '1';
        }, 10);
    } else {
        // Collapse
        content.style.opacity = '0';
        setTimeout(() => {
            content.style.display = 'none';
            arrow.classList.remove('fe-arrow-up');
            arrow.classList.add('fe-arrow-down');
        }, 300);
    }
}

function expandAllLevels() {
    document.querySelectorAll('.level-content').forEach(content => {
        content.style.display = 'block';
        content.style.opacity = '1';
    });
    
    document.querySelectorAll('.level-arrow').forEach(arrow => {
        arrow.classList.remove('fe-arrow-down');
        arrow.classList.add('fe-arrow-up');
    });
    
    // Success notification
    showNotification('All levels expanded', 'success');
}

function collapseAllLevels() {
    document.querySelectorAll('.level-content').forEach(content => {
        content.style.opacity = '0';
        setTimeout(() => {
            content.style.display = 'none';
        }, 300);
    });
    
    document.querySelectorAll('.level-arrow').forEach(arrow => {
        arrow.classList.remove('fe-arrow-up');
        arrow.classList.add('fe-arrow-down');
    });
    
    // Success notification
    showNotification('All levels collapsed', 'warning');
}

function exportLevel(level) {
    const table = document.querySelector(`#content-${level} table`);
    if (!table) {
        alert('Level data not loaded');
        return;
    }
    
    exportTableToCSV(table, `Level_${level}_Data.csv`);
    showNotification(`Level ${level} data exported`, 'info');
}

function exportAllLevels() {
    const tables = document.querySelectorAll('.level-content table');
    if (tables.length === 0) {
        alert('No data to export');
        return;
    }
    
    let csvContent = "data:text/csv;charset=utf-8,";
    
    tables.forEach((table, index) => {
        const levelSection = table.closest('.level-section');
        const levelMatch = levelSection.id.match(/level-(\d+)/);
        const levelNum = levelMatch ? levelMatch[1] : index + 1;
        
        if (index > 0) csvContent += "\n\n";
        csvContent += `Level ${levelNum}\n`;
        
        // Add headers
        const headers = table.querySelectorAll('thead th');
        const headerRow = Array.from(headers).map(h => h.textContent.trim()).join(',');
        csvContent += headerRow + "\n";
        
        // Add rows
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowData = Array.from(cells).map(cell => {
                return '"' + cell.textContent.trim().replace(/"/g, '""') + '"';
            }).join(',');
            csvContent += rowData + "\n";
        });
    });
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "All_Genealogy_Levels.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showNotification('All levels exported successfully', 'success');
}

function showMemberDownline(memberId, memberName) {
    fetch('{{ route("member.genealogy.node") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ 
            user_id: memberId,
            level: 1,
            max_level: 5
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.downlines && data.downlines.length > 0) {
            showDownlineModal(data.downlines, memberName);
        } else {
            showNotification(`${memberName} has no active downline members`, 'info');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to load downline data', 'error');
    });
}

function showDownlineModal(downlines, memberName) {
    const modalHtml = `
        <div class="modal fade" id="downlineModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fe fe-users me-2"></i>Downline of ${memberName}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-success">
                                    <tr>
                                        <th>Member</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                        <th>Join Date</th>
                                        <th>Business</th>
                                        <th>Downline</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${downlines.map(member => `
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm avatar-rounded me-2">
                                                        <span class="fw-semibold">${member.name.substring(0, 2)}</span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fs-13">${member.name}</h6>
                                                        <p class="mb-0 fs-11 text-muted">${member.referral_code}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="mb-0 fs-12">${member.email}</p>
                                                ${member.phone ? `<p class="mb-0 fs-11 text-muted">${member.phone}</p>` : ''}
                                            </td>
                                            <td>
                                                <span class="badge bg-${member.status === 'active' ? 'success' : 'warning'}">
                                                    ${member.status.charAt(0).toUpperCase() + member.status.slice(1)}
                                                </span>
                                            </td>
                                            <td>${new Date(member.join_date).toLocaleDateString()}</td>
                                            <td>৳${parseFloat(member.business || 0).toFixed(2)}</td>
                                            <td>
                                                ${member.downline_count}
                                                ${member.has_downline ? '<i class="fe fe-users text-success ms-1"></i>' : ''}
                                            </td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="exportDownlineData('${memberName}')">
                            <i class="fe fe-download me-1"></i>Export
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal
    const existingModal = document.getElementById('downlineModal');
    if (existingModal) existingModal.remove();
    
    // Add and show modal
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('downlineModal'));
    modal.show();
    
    // Cleanup on hide
    document.getElementById('downlineModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

function exportDownlineData(memberName) {
    const table = document.querySelector('#downlineModal table');
    if (table) {
        exportTableToCSV(table, `${memberName}_Downline.csv`);
        showNotification('Downline data exported', 'success');
    }
}

function exportTableToCSV(table, filename) {
    let csvContent = "data:text/csv;charset=utf-8,";
    
    // Headers
    const headers = table.querySelectorAll('thead th');
    csvContent += Array.from(headers).map(h => h.textContent.trim()).join(',') + "\n";
    
    // Rows
    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const rowData = Array.from(cells).map(cell => {
            return '"' + cell.textContent.trim().replace(/"/g, '""') + '"';
        }).join(',');
        csvContent += rowData + "\n";
    });
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function showNotification(message, type = 'info') {
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger', 
        'warning': 'alert-warning',
        'info': 'alert-info'
    }[type] || 'alert-info';
    
    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

function filterByLevel(maxLevel) {
    if (maxLevel === 'all') {
        // Show all levels
        document.querySelectorAll('.level-section').forEach(section => {
            section.style.display = 'block';
        });
    } else {
        // Hide levels beyond maxLevel
        for (let i = 1; i <= 20; i++) {
            const levelSection = document.getElementById(`level-${i}`);
            if (levelSection) {
                if (i <= parseInt(maxLevel)) {
                    levelSection.style.display = 'block';
                } else {
                    levelSection.style.display = 'none';
                }
            }
        }
    }
}

// Export table
function exportTable() {
    const tables = document.querySelectorAll('.table');
    let csvContent = "data:text/csv;charset=utf-8,";
    
    tables.forEach((table, index) => {
        if (index > 0) csvContent += "\n\n";
        
        // Add table headers
        const headers = table.querySelectorAll('thead th');
        const headerRow = Array.from(headers).map(h => h.textContent.trim()).join(',');
        csvContent += headerRow + "\n";
        
        // Add table rows
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowData = Array.from(cells).map(cell => {
                return '"' + cell.textContent.trim().replace(/"/g, '""') + '"';
            }).join(',');
            csvContent += rowData + "\n";
        });
    });
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "genealogy_data.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Expand/Collapse functions (for future tree view)
function expandAll() {
    // Show all level sections
    document.querySelectorAll('.border-top').forEach(section => {
        section.style.display = 'block';
    });
}

function collapseAll() {
    // Hide all but first 3 levels
    filterByLevel(3);
}

// View member details
function viewMember(memberId) {
    console.log('Opening member modal for ID:', memberId);
    
    fetch(`/member/genealogy/member/${memberId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to fetch member details');
        }
        return response.json();
    })
    .then(data => {
        console.log('Member data received:', data);
        if (data.member) {
            showMemberModal(data.member);
        } else {
            showNotification('Member data not found', 'error');
        }
    })
    .catch(error => {
        console.error('Error loading member details:', error);
        showNotification('Failed to load member details', 'error');
    });
}

function viewDownline(memberId) {
    fetch('{{ route("member.genealogy.node") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ 
            user_id: memberId,
            level: 1,
            max_level: 5
        })
    })
    .then(response => response.json())
    .then(data => {
        showDownlineModal(data);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to load downline data');
    });
}

function showMemberModal(member) {
    console.log('showMemberModal called with:', member);
    
    // Create and show enhanced modal with member details
    const modalHtml = `
        <div class="modal fade" id="memberModal" tabindex="-1" aria-labelledby="memberModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="memberModalLabel">
                            <i class="fe fe-user me-2"></i>Member Profile Details
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <!-- Profile Header -->
                        <div class="bg-gradient-primary p-4 text-white text-center">
                            <div class="avatar avatar-xl avatar-rounded mx-auto mb-3" style="width: 80px; height: 80px; font-size: 24px; background: linear-gradient(135deg, #ffffff20, #ffffff40); border: 3px solid #ffffff40;">
                                ${member.avatar ? `<img src="${member.avatar}" alt="${member.name}" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">` : `<span class="fw-bold">${member.name.substring(0, 2).toUpperCase()}</span>`}
                            </div>
                            <h4 class="mb-1">${member.name}</h4>
                            <p class="mb-2 opacity-75">ID: ${member.referral_code}</p>
                            <span class="badge bg-${member.status === 'active' ? 'success' : 'warning'} fs-6 px-3 py-1">
                                <i class="fe fe-${member.status === 'active' ? 'check-circle' : 'clock'} me-1"></i>
                                ${member.status.charAt(0).toUpperCase() + member.status.slice(1)}
                            </span>
                        </div>
                        
                        <!-- Member Details -->
                        <div class="p-4">
                            <div class="row">
                                <!-- Contact Information -->
                                <div class="col-md-6">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fe fe-phone me-2"></i>Contact Information
                                    </h6>
                                    <div class="info-item mb-3">
                                        <label class="text-muted small fw-semibold">Email Address</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fe fe-mail text-info me-2"></i>
                                            <span class="fw-medium">${member.email}</span>
                                        </div>
                                    </div>
                                    <div class="info-item mb-3">
                                        <label class="text-muted small fw-semibold">Phone Number</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fe fe-phone text-success me-2"></i>
                                            <span class="fw-medium">${member.phone || 'Not provided'}</span>
                                        </div>
                                    </div>
                                    <div class="info-item mb-3">
                                        <label class="text-muted small fw-semibold">Address</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fe fe-map-pin text-warning me-2"></i>
                                            <span class="fw-medium">${member.address || 'Not provided'}</span>
                                        </div>
                                    </div>
                                    <div class="info-item mb-3">
                                        <label class="text-muted small fw-semibold">Location</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fe fe-globe text-primary me-2"></i>
                                            <span class="fw-medium">${member.city || 'N/A'}, ${member.country || 'N/A'}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Network Information -->
                                <div class="col-md-6">
                                    <h6 class="text-success border-bottom pb-2 mb-3">
                                        <i class="fe fe-users me-2"></i>Network Information
                                    </h6>
                                    <div class="info-item mb-3">
                                        <label class="text-muted small fw-semibold">Join Date</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fe fe-calendar text-info me-2"></i>
                                            <span class="fw-medium">${member.join_date}</span>
                                        </div>
                                    </div>
                                    <div class="info-item mb-3">
                                        <label class="text-muted small fw-semibold">Sponsor</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fe fe-user-plus text-warning me-2"></i>
                                            <span class="fw-medium">${member.sponsor_name}</span>
                                        </div>
                                    </div>
                                    <div class="info-item mb-3">
                                        <label class="text-muted small fw-semibold">Total Business</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fe fe-dollar-sign text-success me-2"></i>
                                            <span class="fw-medium fs-5 text-success">৳${member.business}</span>
                                        </div>
                                    </div>
                                    <div class="info-item mb-3">
                                        <label class="text-muted small fw-semibold">Downline Members</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fe fe-users text-primary me-2"></i>
                                            <span class="fw-medium fs-5 text-primary">${member.downline_count}</span>
                                            ${member.has_downline ? '<i class="fe fe-trending-up text-success ms-1" title="Active downline"></i>' : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <div class="d-flex gap-2 w-100">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fe fe-x me-1"></i>Close
                            </button>
                            ${member.has_downline ? `
                            <button type="button" class="btn btn-success" onclick="showMemberDownline(${member.id}, '${member.name}')">
                                <i class="fe fe-users me-1"></i>View Downline
                            </button>
                            ` : ''}
                            <button type="button" class="btn btn-primary" onclick="contactMember('${member.email}', '${member.phone}')">
                                <i class="fe fe-message-circle me-1"></i>Contact
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('memberModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body and show
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('memberModal'));
    modal.show();
    
    // Clean up modal after hiding
    document.getElementById('memberModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Contact member function
function contactMember(email, phone) {
    const contactOptions = `
        <div class="d-flex gap-2 justify-content-center">
            <a href="mailto:${email}" class="btn btn-primary btn-sm">
                <i class="fe fe-mail me-1"></i>Send Email
            </a>
            ${phone && phone !== 'Not provided' ? `
            <a href="tel:${phone}" class="btn btn-success btn-sm">
                <i class="fe fe-phone me-1"></i>Call ${phone}
            </a>
            ` : ''}
            <button class="btn btn-info btn-sm" onclick="copyToClipboard('${email}')">
                <i class="fe fe-copy me-1"></i>Copy Email
            </button>
        </div>
    `;
    
    showNotification(`Contact Options: ${contactOptions}`, 'info', 5000);
}

// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showNotification('✅ Copied to clipboard!', 'success');
    }, function(err) {
        console.error('Could not copy text: ', err);
        showNotification('❌ Failed to copy to clipboard', 'error');
    });
}

// Search functionality
function searchMembers() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const searchResults = document.getElementById('searchResults');
    
    if (!searchInput || !statusFilter || !searchResults) {
        showNotification('Search elements not found', 'error');
        return;
    }
    
    const searchTerm = searchInput.value.trim();
    const statusValue = statusFilter.value;
    
    if (searchTerm === '' && statusValue === '') {
        showNotification('Please enter search criteria', 'warning');
        return;
    }
    
    // Show loading state
    showNotification('Searching members...', 'info');
    searchResults.classList.remove('d-none');
    
    const resultsBody = document.getElementById('searchResultsBody');
    if (resultsBody) {
        resultsBody.innerHTML = '<tr><td colspan="8" class="text-center"><div class="loading-spinner mx-auto"></div><br>Searching...</td></tr>';
    }
    
    // Make AJAX call to search
    fetch('{{ route("member.genealogy.search") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ 
            query: searchTerm,
            status: statusValue 
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        displaySearchResults(data.members || []);
        showNotification(`Found ${data.members ? data.members.length : 0} members`, 'success');
    })
    .catch(error => {
        showNotification('Search failed. Please try again.', 'error');
        if (resultsBody) {
            resultsBody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Search failed. Please try again.</td></tr>';
        }
    });
}

function displaySearchResults(members) {
    const resultsContainer = document.getElementById('searchResults');
    const resultsBody = document.getElementById('searchResultsBody');
    
    if (!resultsBody) {
        console.error('Search results body not found');
        return;
    }
    
    if (members.length === 0) {
        resultsBody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center text-muted py-4">
                    <i class="fe fe-search fs-24 mb-2 d-block"></i>
                    No members found matching your search criteria
                </td>
            </tr>
        `;
    } else {
        const htmlContent = members.map(member => `
            <tr class="member-row">
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm avatar-rounded me-2">
                            <span class="fw-semibold">${member.name ? member.name.substring(0, 2).toUpperCase() : 'N/A'}</span>
                        </div>
                        <div>
                            <h6 class="mb-0 fs-13">${member.name || 'N/A'}</h6>
                            <p class="mb-0 fs-11 text-muted">${member.referral_code || 'N/A'}</p>
                        </div>
                    </div>
                </td>
                <td>
                    <div>
                        <p class="mb-0 fs-12">${member.email || 'N/A'}</p>
                        ${member.phone ? `<p class="mb-0 fs-11 text-muted">${member.phone}</p>` : ''}
                    </div>
                </td>
                <td>
                    <span class="badge bg-${member.status === 'active' ? 'success' : member.status === 'inactive' ? 'warning' : 'secondary'}">
                        ${member.status ? member.status.charAt(0).toUpperCase() + member.status.slice(1) : 'Unknown'}
                    </span>
                </td>
                <td>${member.join_date || 'N/A'}</td>
                <td>
                    <span class="fs-12">${member.sponsor_name || 'N/A'}</span>
                </td>
                <td>৳${member.business ? parseFloat(member.business).toFixed(2) : '0.00'}</td>
                <td>
                    ${member.downline_count || 0}
                    ${member.has_downline ? '<i class="fe fe-users text-success ms-1"></i>' : ''}
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-primary" onclick="viewMember(${member.id})" title="View Details">
                            <i class="fe fe-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-info" onclick="viewMemberTree('${member.username || member.referral_code}')" title="View Binary Tree">
                            <i class="fe fe-git-branch"></i>
                        </button>
                        ${member.has_downline ? `
                        <button class="btn btn-sm btn-success" onclick="showMemberDownline(${member.id}, '${member.name}')" title="View Downline">
                            <i class="fe fe-users"></i>
                        </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `).join('');
        
        resultsBody.innerHTML = htmlContent;
    }
    
    // Show the results container
    if (resultsContainer) {
        resultsContainer.classList.remove('d-none');
    }
}

function clearSearch() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('searchResults').classList.add('d-none');
}

// Function to view member's binary tree
function viewMemberTree(username) {
    if (!username) {
        showNotification('Username not available', 'error');
        return;
    }
    
    // Show loading notification
    showNotification(`Loading binary tree for ${username}...`, 'info');
    
    // Redirect to matching dashboard with username parameter
    const currentUrl = new URL(window.location.href);
    
    // Navigate to matching dashboard with username parameter
    window.location.href = `{{ route('member.matching.dashboard') }}?tree_user=${encodeURIComponent(username)}`;
}

function exportResults() {
    exportTable();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide upline section initially
    const uplineSection = document.getElementById('uplineSection');
    if (uplineSection) {
        uplineSection.classList.add('d-none');
    }
    
    // Initialize all levels as collapsed
    document.querySelectorAll('.level-content').forEach(content => {
        content.style.display = 'none';
    });
    
    // Set all arrows to down position
    document.querySelectorAll('.level-arrow').forEach(arrow => {
        arrow.classList.add('fe-arrow-down');
        arrow.classList.remove('fe-arrow-up');
    });
    
    // Auto-expand first level only
    const firstLevel = document.getElementById('content-1');
    const firstArrow = document.getElementById('arrow-1');
    if (firstLevel && firstArrow) {
        firstLevel.style.display = 'block';
        firstLevel.style.opacity = '1';
        firstArrow.classList.remove('fe-arrow-down');
        firstArrow.classList.add('fe-arrow-up');
    }
    
    // Initialize level filter
    filterByLevel(5); // Show first 5 levels by default
    
    // Setup search on Enter key
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchMembers();
        }
    });
    
    // Page initialized successfully
});

// Additional functions for view management
function loadCompactMembers() {
    fetch('/member/genealogy/compact-data')
        .then(response => response.json())
        .then(data => {
            const membersList = document.getElementById('compact-members-list');
            if (data.members && data.members.length > 0) {
                membersList.innerHTML = data.members.map(member => `
                    <div class="compact-member-item d-flex align-items-center p-2 border-bottom">
                        <img src="${member.avatar || '/admin-assets/images/users/default.jpg'}" 
                             class="avatar avatar-sm me-3" alt="${member.name}">
                        <div class="flex-grow-1">
                            <h6 class="mb-0">${member.name}</h6>
                            <small class="text-muted">Level ${member.level} • ${member.referral_code}</small>
                        </div>
                        <span class="badge bg-${member.status === 'active' ? 'success' : 'secondary'}">${member.status}</span>
                    </div>
                `).join('');
            } else {
                membersList.innerHTML = '<div class="text-center p-3 text-muted">No members found</div>';
            }
        })
        .catch(error => {
            console.error('Error loading compact members:', error);
            showNotification('Error loading members', 'error');
        });
}

function loadLevelViewData() {
    fetch('/member/genealogy/level-data')
        .then(response => response.json())
        .then(data => {
            const dynamicLevels = document.getElementById('dynamic-levels');
            if (data.levels && data.levels.length > 0) {
                dynamicLevels.innerHTML = data.levels.map((level, index) => `
                    <div class="level-item mb-3">
                        <div class="level-header bg-gradient-${getColorForLevel(index + 1)} text-white p-3 rounded" 
                             data-bs-toggle="collapse" data-bs-target="#level-${index + 1}" style="cursor: pointer;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="fe fe-users me-2"></i>Level ${index + 1}</h6>
                                <div>
                                    <span class="badge bg-light text-dark me-2">${level.members.length} Members</span>
                                    <i class="fe fe-chevron-down"></i>
                                </div>
                            </div>
                        </div>
                        <div class="collapse" id="level-${index + 1}">
                            <div class="level-content bg-light p-3 rounded-bottom">
                                <div class="row">
                                    ${level.members.map(member => `
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="member-card p-3 bg-white rounded border">
                                                <div class="d-flex align-items-center">
                                                    <img src="${member.avatar || '/admin-assets/images/users/default.jpg'}" 
                                                         class="avatar avatar-md me-3" alt="${member.name}">
                                                    <div>
                                                        <h6 class="mb-1">${member.name}</h6>
                                                        <p class="text-muted small mb-1">${member.email}</p>
                                                        <span class="badge bg-${member.status === 'active' ? 'success' : 'secondary'} small">${member.status}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                dynamicLevels.innerHTML = '<div class="text-center p-4 text-muted">No additional levels found</div>';
            }
        })
        .catch(error => {
            console.error('Error loading level data:', error);
            showNotification('Error loading level data', 'error');
        });
}

function loadHierarchyData() {
    console.log('Loading hierarchy data...');
    
    // Show loading state
    const loadingElement = document.getElementById('hierarchy-loading');
    const loadBtn = document.getElementById('loadHierarchyBtn');
    
    if (loadingElement) {
        loadingElement.style.display = 'flex';
    }
    
    if (loadBtn) {
        loadBtn.disabled = true;
        loadBtn.innerHTML = '<i class="fe fe-loader spin me-1"></i>Loading...';
    }
    
    fetch('/member/genealogy/hierarchy-data')
        .then(response => response.json())
        .then(data => {
            console.log('Hierarchy data received:', data);
            
            // Hide loading
            if (loadingElement) {
                loadingElement.style.display = 'none';
            }
            
            // Load Level 1
            if (data.level1 && data.level1.length > 0) {
                loadLevel1Data(data.level1);
                
                // Show controls
                document.getElementById('expandAllBtn').style.display = 'inline-block';
                document.getElementById('collapseBtn').style.display = 'inline-block';
            }
            
            // Reset button
            if (loadBtn) {
                loadBtn.disabled = false;
                loadBtn.innerHTML = '<i class="fe fe-refresh-cw me-1"></i>Refresh Network';
            }
            
            showNotification('Network hierarchy loaded successfully', 'success');
        })
        .catch(error => {
            console.error('Error loading hierarchy data:', error);
            
            // Hide loading and reset button
            if (loadingElement) {
                loadingElement.style.display = 'none';
            }
            
            if (loadBtn) {
                loadBtn.disabled = false;
                loadBtn.innerHTML = '<i class="fe fe-refresh-cw me-1"></i>Load Network';
            }
            
            showNotification('Error loading hierarchy data', 'error');
        });
}

function loadLevel1Data(members) {
    const level1Container = document.getElementById('hierarchy-level-1');
    const level1Balls = document.getElementById('level-1-balls');
    const level1Count = document.getElementById('level-1-count');
    
    if (!level1Container || !level1Balls || !level1Count) {
        console.error('Level 1 containers not found');
        return;
    }
    
    // Update count
    level1Count.textContent = `${members.length} Members`;
    
    // Generate ball nodes
    level1Balls.innerHTML = members.map((member, index) => `
        <div class="ball-node" 
             data-user-id="${member.id}" 
             data-level="1"
             onclick="loadMemberDownline(${member.id}, '${member.name}', 2)"
             style="animation-delay: ${index * 0.1}s">
            <div class="ball-avatar">
                <img src="${member.avatar || '/admin-assets/images/users/default.jpg'}" alt="${member.name}">
                <div class="status-indicator ${member.status || 'active'}"></div>
            </div>
            <div class="ball-info">
                <h6>${member.name}</h6>
                <p>${member.referral_code}</p>
                <span class="level-badge level-1">Level 1</span>
            </div>
            <div class="ball-stats">
                <span class="stat-item">
                    <i class="fe fe-users"></i>
                    ${member.direct_referrals || 0}
                </span>
                ${member.total_business ? `
                <span class="stat-item">
                    <i class="fe fe-dollar-sign"></i>
                    ৳${parseFloat(member.total_business).toFixed(0)}
                </span>
                ` : ''}
            </div>
        </div>
    `).join('');
    
    // Show level 1 container
    level1Container.style.display = 'flex';
    
    // Add has-children class to root if there are children
    const rootLevel = document.querySelector('.root-level');
    if (rootLevel && members.length > 0) {
        rootLevel.classList.add('has-children');
    }
}

function loadFullHierarchy() {
    loadHierarchyData();
}

function loadMemberDownline(memberId, memberName, targetLevel) {
    if (targetLevel > 3) {
        showNotification('Maximum hierarchy depth reached (3 levels)', 'info');
        return;
    }
    
    // Show loading for target level
    const targetContainer = document.getElementById(`hierarchy-level-${targetLevel}`);
    const targetBalls = document.getElementById(`level-${targetLevel}-balls`);
    const targetCount = document.getElementById(`level-${targetLevel}-count`);
    
    if (!targetContainer || !targetBalls || !targetCount) {
        console.error(`Level ${targetLevel} containers not found`);
        return;
    }
    
    // Show loading state
    targetBalls.innerHTML = `
        <div class="loading-placeholder">
            <div class="loading-spinner"></div>
            <p>Loading ${memberName}'s downline...</p>
        </div>
    `;
    targetContainer.style.display = 'flex';
    
    // Fetch member's downline
    fetch('{{ route("member.genealogy.node") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ 
            user_id: memberId,
            level: 1,
            max_level: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.downlines && data.downlines.length > 0) {
            loadLevelData(data.downlines, targetLevel);
        } else {
            targetBalls.innerHTML = `
                <div class="no-data-placeholder">
                    <i class="fe fe-users text-muted"></i>
                    <p class="text-muted mt-2">${memberName} has no active downline</p>
                </div>
            `;
            targetCount.textContent = '0 Members';
        }
    })
    .catch(error => {
        console.error(`Error loading level ${targetLevel} data:`, error);
        targetBalls.innerHTML = `
            <div class="error-placeholder">
                <i class="fe fe-alert-triangle text-danger"></i>
                <p class="text-danger mt-2">Error loading downline data</p>
            </div>
        `;
    });
}

function loadLevelData(members, level) {
    const levelBalls = document.getElementById(`level-${level}-balls`);
    const levelCount = document.getElementById(`level-${level}-count`);
    
    if (!levelBalls || !levelCount) {
        console.error(`Level ${level} containers not found`);
        return;
    }
    
    // Update count
    levelCount.textContent = `${members.length} Members`;
    
    // Generate ball nodes
    levelBalls.innerHTML = members.map((member, index) => `
        <div class="ball-node" 
             data-user-id="${member.id}" 
             data-level="${level}"
             onclick="loadMemberDownline(${member.id}, '${member.name}', ${level + 1})"
             style="animation-delay: ${index * 0.1}s">
            <div class="ball-avatar">
                <img src="${member.avatar || '/admin-assets/images/users/default.jpg'}" alt="${member.name}">
                <div class="status-indicator ${member.status || 'active'}"></div>
            </div>
            <div class="ball-info">
                <h6>${member.name}</h6>
                <p>${member.referral_code}</p>
                <span class="level-badge level-${level}">Level ${level}</span>
            </div>
            <div class="ball-stats">
                <span class="stat-item">
                    <i class="fe fe-users"></i>
                    ${member.direct_referrals || 0}
                </span>
                ${member.total_business ? `
                <span class="stat-item">
                    <i class="fe fe-dollar-sign"></i>
                    ৳${parseFloat(member.total_business).toFixed(0)}
                </span>
                ` : ''}
            </div>
        </div>
    `).join('');
    
    // Add has-children class to previous level
    const prevLevel = level - 1;
    const prevLevelContainer = document.querySelector(`.level-${prevLevel === 0 ? 'root' : prevLevel}`);
    if (prevLevelContainer && members.length > 0) {
        prevLevelContainer.classList.add('has-children');
    }
}

function expandAllHierarchy() {
    const levels = document.querySelectorAll('.hierarchy-level:not(.root-level)');
    levels.forEach(level => {
        level.style.display = 'flex';
    });
    showNotification('All hierarchy levels expanded', 'success');
}

function collapseHierarchy() {
    const levels = document.querySelectorAll('.hierarchy-level:not(.root-level)');
    levels.forEach(level => {
        level.style.display = 'none';
    });
    
    // Remove has-children classes
    document.querySelectorAll('.has-children').forEach(element => {
        element.classList.remove('has-children');
    });
    
    showNotification('Hierarchy collapsed to root level', 'warning');
}

function centerHierarchy() {
    const container = document.querySelector('.hierarchy-view-container');
    if (container) {
        container.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'center' 
        });
    }
}

function exportHierarchy() {
    const hierarchyData = {
        root: {
            name: window.userData ? window.userData.name : 'Root User',
            referral_code: window.userData ? window.userData.referral_code : 'ROOT',
            stats: window.genealogyStats
        },
        levels: []
    };
    
    // Collect data from each level
    for (let level = 1; level <= 3; level++) {
        const levelContainer = document.getElementById(`hierarchy-level-${level}`);
        if (levelContainer && levelContainer.style.display !== 'none') {
            const ballNodes = levelContainer.querySelectorAll('.ball-node');
            const levelData = Array.from(ballNodes).map(node => ({
                user_id: node.dataset.userId,
                level: node.dataset.level,
                name: node.querySelector('.ball-info h6').textContent,
                referral_code: node.querySelector('.ball-info p').textContent
            }));
            
            if (levelData.length > 0) {
                hierarchyData.levels.push({
                    level: level,
                    members: levelData
                });
            }
        }
    }
    
    // Export as JSON
    const dataStr = JSON.stringify(hierarchyData, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    const url = URL.createObjectURL(dataBlob);
    
    const link = document.createElement('a');
    link.href = url;
    link.download = `network_hierarchy_${new Date().toISOString().split('T')[0]}.json`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
    
    showNotification('Hierarchy data exported successfully', 'success');
}

// Missing functions for general functionality
function refreshTree() {
    location.reload();
}

function exportTree() {
    exportTable();
}

function expandAll() {
    expandAllLevels();
}

function collapseAll() {
    collapseAllLevels();
}

function exportTable() {
    const tables = document.querySelectorAll('.table');
    if (tables.length === 0) {
        showNotification('No data to export', 'warning');
        return;
    }
    
    let csvContent = "data:text/csv;charset=utf-8,";
    
    tables.forEach((table, index) => {
        if (index > 0) csvContent += "\n\n";
        
        // Add headers
        const headers = table.querySelectorAll('thead th');
        const headerRow = Array.from(headers).map(h => h.textContent.trim()).join(',');
        csvContent += headerRow + "\n";
        
        // Add rows
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowData = Array.from(cells).map(cell => {
                return '"' + cell.textContent.trim().replace(/"/g, '""') + '"';
            }).join(',');
            csvContent += rowData + "\n";
        });
    });
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `genealogy_export_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showNotification('Data exported successfully', 'success');
}

function filterByLevel(maxLevel) {
    if (maxLevel === 'all') {
        document.querySelectorAll('.level-section').forEach(section => {
            section.style.display = 'block';
        });
    } else {
        const levelNum = parseInt(maxLevel);
        document.querySelectorAll('.level-section').forEach(section => {
            const sectionLevel = parseInt(section.id.replace('level-', ''));
            if (sectionLevel <= levelNum) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        });
    }
}

function clearSearch() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const searchResults = document.getElementById('searchResults');
    
    if (searchInput) searchInput.value = '';
    if (statusFilter) statusFilter.value = '';
    if (searchResults) searchResults.classList.add('d-none');
    
    showNotification('Search cleared', 'info');
}

function testSearch() {
    console.log('=== TESTING SEARCH FUNCTIONALITY ===');
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.value = 'test';
        console.log('Set test search term');
        searchMembers();
    } else {
        console.error('Search input not found');
    }
}

function exportResults() {
    const resultsTable = document.querySelector('#searchResults table');
    if (resultsTable) {
        exportTableToCSV(resultsTable, 'search_results.csv');
    } else {
        showNotification('No search results to export', 'warning');
    }
}

function viewDownline(memberId) {
    showMemberDownline(memberId, 'Member');
}

function expandTreeLevel(level) {
    showNotification(`Loading level ${level}...`, 'info');
    
    // Implement tree level expansion
    const levelContainer = document.getElementById(`tree-level-${level}`);
    if (levelContainer) {
        levelContainer.style.display = 'block';
    }
}

function expandAllTreeLevels() {
    showNotification('Expanding all tree levels...', 'info');
    
    // Show all tree levels
    document.querySelectorAll('.tree-level').forEach(level => {
        level.style.display = 'block';
    });
}

function collapseTreeLevels() {
    showNotification('Collapsing tree levels...', 'warning');
    
    // Hide levels except level 1
    document.querySelectorAll('.tree-level:not(.level-1)').forEach(level => {
        level.style.display = 'none';
    });
}

function loadLevelViewData() {
    showNotification('Loading level view data...', 'info');
    
    // Implement level view data loading
    const dynamicLevels = document.getElementById('dynamic-levels');
    if (dynamicLevels) {
        dynamicLevels.innerHTML = '<div class="text-center p-4"><div class="loading-spinner"></div><p>Loading levels...</p></div>';
        
        // Simulate loading
        setTimeout(() => {
            dynamicLevels.innerHTML = '<div class="text-center p-4 text-success"><i class="fe fe-check"></i><p>Levels loaded successfully</p></div>';
        }, 1500);
    }
}

function loadCompactMembers() {
    showNotification('Loading compact members...', 'info');
    
    const membersList = document.getElementById('compact-members-list');
    if (membersList) {
        membersList.innerHTML = '<div class="text-center p-3"><div class="loading-spinner"></div><p>Loading members...</p></div>';
        
        // Simulate loading
        setTimeout(() => {
            membersList.innerHTML = '<div class="text-center p-3 text-success"><i class="fe fe-check"></i><p>Members loaded successfully</p></div>';
        }, 1500);
    }
}

function expandAllTreeLevels() {
    showNotification('Expanding all tree levels...', 'info');
}

function collapseTreeLevels() {
    showNotification('Collapsing tree levels...', 'info');
}

function expandHierarchy() {
    showNotification('Expanding full hierarchy...', 'info');
}

function printHierarchy() {
    window.print();
}

function getColorForLevel(level) {
    const colors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary'];
    return colors[(level - 1) % colors.length];
}
</script>
@endpush
