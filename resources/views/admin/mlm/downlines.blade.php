@extends('admin.layouts.app')

@section('title', 'MLM Downlines')

@push('styles')
<style>
    .member-card {
        transition: transform 0.3s ease;
        border-left: 4px solid #007bff;
    }
    
    .member-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    
    .level-indicator {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: bold;
    }
    
    .rank-badge {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
    }
    
    .stats-widget {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border-radius: 15px;
    }
    
    .activity-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    
    .activity-online { background-color: #28a745; }
    .activity-away { background-color: #ffc107; }
    .activity-offline { background-color: #6c757d; }
    
    .downline-tree {
        padding-left: 20px;
        border-left: 2px solid #e9ecef;
        margin-left: 10px;
    }
    
    .tree-item {
        position: relative;
        margin: 10px 0;
    }
    
    .tree-item::before {
        content: '';
        position: absolute;
        left: -22px;
        top: 50%;
        width: 20px;
        height: 2px;
        background: #e9ecef;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="fw-semibold mb-1">MLM Downlines Management</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="#">MLM</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Downlines</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="d-flex gap-2">
                                <select class="form-select" id="memberSelect" style="width: 250px;">
                                    <option value="">Select Member to View Downlines</option>
                                    <option value="1">John Doe (ID: 1001)</option>
                                    <option value="2">Jane Smith (ID: 1002)</option>
                                    <option value="3">Mike Johnson (ID: 1003)</option>
                                </select>
                                <button type="button" class="btn btn-primary" onclick="loadDownlines()">
                                    <i class="ri-group-line me-1"></i> Load Downlines
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="refreshData()">
                                    <i class="ri-refresh-line me-1"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Row -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card custom-card stats-widget">
                    <div class="card-body text-center">
                        <h4 class="mb-1">1,245</h4>
                        <p class="mb-0">Total Downlines</p>
                        <small class="opacity-75">Across all levels</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <span class="avatar bg-success-transparent">
                                <i class="ri-user-add-line fs-18"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-1">156</h5>
                        <p class="mb-0 text-muted">Direct Downlines</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <span class="avatar bg-warning-transparent">
                                <i class="ri-stack-line fs-18"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-1">8</h5>
                        <p class="mb-0 text-muted">Max Depth Level</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <span class="avatar bg-info-transparent">
                                <i class="ri-money-dollar-circle-line fs-18"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-1">₹2,45,750</h5>
                        <p class="mb-0 text-muted">Team Volume</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="levelFilter" class="form-label">Filter by Level</label>
                                <select class="form-select" id="levelFilter">
                                    <option value="">All Levels</option>
                                    <option value="1">Level 1 (Direct)</option>
                                    <option value="2">Level 2</option>
                                    <option value="3">Level 3</option>
                                    <option value="4">Level 4</option>
                                    <option value="5">Level 5+</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="rankFilter" class="form-label">Filter by Rank</label>
                                <select class="form-select" id="rankFilter">
                                    <option value="">All Ranks</option>
                                    <option value="starter">Starter</option>
                                    <option value="bronze">Bronze</option>
                                    <option value="silver">Silver</option>
                                    <option value="gold">Gold</option>
                                    <option value="platinum">Platinum</option>
                                    <option value="diamond">Diamond</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="activityFilter" class="form-label">Filter by Activity</label>
                                <select class="form-select" id="activityFilter">
                                    <option value="">All Members</option>
                                    <option value="online">Online</option>
                                    <option value="active">Active (7 days)</option>
                                    <option value="inactive">Inactive (30+ days)</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="searchMember" class="form-label">Search Member</label>
                                <input type="text" class="form-control" id="searchMember" placeholder="Name or ID...">
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary" onclick="applyFilters()">
                                <i class="ri-filter-line me-1"></i> Apply Filters
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                                <i class="ri-close-line me-1"></i> Clear
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="exportDownlines()">
                                <i class="ri-download-line me-1"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Downlines Display -->
        <div class="row">
            <!-- Tree View -->
            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Downline Tree View</div>
                        <div class="card-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="expandAll()">
                                <i class="ri-add-circle-line me-1"></i> Expand All
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="collapseAll()">
                                <i class="ri-subtract-circle-line me-1"></i> Collapse
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="downlineTree">
                            <!-- Root Member -->
                            <div class="tree-item">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="avatar avatar-sm me-2">
                                        <img src="https://ui-avatars.com/api/?name=John+Doe&background=random" alt="John Doe">
                                    </span>
                                    <div class="flex-fill">
                                        <div class="fw-semibold">John Doe</div>
                                        <small class="text-muted">ID: 1001 • Root</small>
                                    </div>
                                    <span class="level-indicator">Root</span>
                                </div>
                                
                                <!-- Level 1 Downlines -->
                                <div class="downline-tree">
                                    <div class="tree-item">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="avatar avatar-sm me-2">
                                                <img src="https://ui-avatars.com/api/?name=Jane+Smith&background=random" alt="Jane Smith">
                                            </span>
                                            <div class="flex-fill">
                                                <div class="fw-semibold">Jane Smith</div>
                                                <small class="text-muted">ID: 1002 • Gold Rank</small>
                                            </div>
                                            <span class="level-indicator">L1</span>
                                        </div>
                                        
                                        <!-- Level 2 Downlines -->
                                        <div class="downline-tree">
                                            <div class="tree-item">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="avatar avatar-sm me-2">
                                                        <img src="https://ui-avatars.com/api/?name=Sarah+Wilson&background=random" alt="Sarah Wilson">
                                                    </span>
                                                    <div class="flex-fill">
                                                        <div class="fw-semibold">Sarah Wilson</div>
                                                        <small class="text-muted">ID: 1004 • Silver</small>
                                                    </div>
                                                    <span class="level-indicator">L2</span>
                                                </div>
                                            </div>
                                            <div class="tree-item">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="avatar avatar-sm me-2">
                                                        <img src="https://ui-avatars.com/api/?name=David+Brown&background=random" alt="David Brown">
                                                    </span>
                                                    <div class="flex-fill">
                                                        <div class="fw-semibold">David Brown</div>
                                                        <small class="text-muted">ID: 1005 • Bronze</small>
                                                    </div>
                                                    <span class="level-indicator">L2</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="tree-item">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="avatar avatar-sm me-2">
                                                <img src="https://ui-avatars.com/api/?name=Mike+Johnson&background=random" alt="Mike Johnson">
                                            </span>
                                            <div class="flex-fill">
                                                <div class="fw-semibold">Mike Johnson</div>
                                                <small class="text-muted">ID: 1003 • Platinum</small>
                                            </div>
                                            <span class="level-indicator">L1</span>
                                        </div>
                                        
                                        <!-- Level 2 Downlines -->
                                        <div class="downline-tree">
                                            <div class="tree-item">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="avatar avatar-sm me-2">
                                                        <img src="https://ui-avatars.com/api/?name=Lisa+Davis&background=random" alt="Lisa Davis">
                                                    </span>
                                                    <div class="flex-fill">
                                                        <div class="fw-semibold">Lisa Davis</div>
                                                        <small class="text-muted">ID: 1006 • Gold</small>
                                                    </div>
                                                    <span class="level-indicator">L2</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <button class="btn btn-outline-primary" onclick="loadMoreLevels()">
                                <i class="ri-arrow-down-line me-1"></i> Load More Levels
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- List View -->
            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Downlines List View</div>
                        <div class="card-actions">
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="viewMode" id="gridView" checked>
                                <label class="btn btn-outline-primary btn-sm" for="gridView">
                                    <i class="ri-grid-line"></i>
                                </label>
                                <input type="radio" class="btn-check" name="viewMode" id="listView">
                                <label class="btn btn-outline-primary btn-sm" for="listView">
                                    <i class="ri-list-check"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="downlinesList">
                            <!-- Member Card 1 -->
                            <div class="card member-card mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-md me-3">
                                            <img src="https://ui-avatars.com/api/?name=Jane+Smith&background=random" alt="Jane Smith">
                                        </span>
                                        <div class="flex-fill">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h6 class="fw-semibold mb-1">Jane Smith</h6>
                                                    <div class="d-flex align-items-center">
                                                        <span class="activity-indicator activity-online"></span>
                                                        <small class="text-muted">ID: 1002 • Online</small>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <span class="level-indicator">Level 1</span>
                                                    <br>
                                                    <span class="badge bg-warning mt-1">Gold</span>
                                                </div>
                                            </div>
                                            <div class="row text-center">
                                                <div class="col-4">
                                                    <small class="text-muted">Downlines</small>
                                                    <div class="fw-semibold">8</div>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted">Sales</small>
                                                    <div class="fw-semibold">₹25,600</div>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted">Joined</small>
                                                    <div class="fw-semibold">Jan 2024</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewMemberDetails(1002)">
                                            <i class="ri-eye-line me-1"></i> View
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="sendMessage(1002)">
                                            <i class="ri-message-line me-1"></i> Message
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" onclick="viewDownlines(1002)">
                                            <i class="ri-group-line me-1"></i> Downlines
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Member Card 2 -->
                            <div class="card member-card mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-md me-3">
                                            <img src="https://ui-avatars.com/api/?name=Mike+Johnson&background=random" alt="Mike Johnson">
                                        </span>
                                        <div class="flex-fill">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h6 class="fw-semibold mb-1">Mike Johnson</h6>
                                                    <div class="d-flex align-items-center">
                                                        <span class="activity-indicator activity-away"></span>
                                                        <small class="text-muted">ID: 1003 • Away</small>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <span class="level-indicator">Level 1</span>
                                                    <br>
                                                    <span class="badge bg-info mt-1">Platinum</span>
                                                </div>
                                            </div>
                                            <div class="row text-center">
                                                <div class="col-4">
                                                    <small class="text-muted">Downlines</small>
                                                    <div class="fw-semibold">12</div>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted">Sales</small>
                                                    <div class="fw-semibold">₹45,200</div>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted">Joined</small>
                                                    <div class="fw-semibold">Dec 2023</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewMemberDetails(1003)">
                                            <i class="ri-eye-line me-1"></i> View
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="sendMessage(1003)">
                                            <i class="ri-message-line me-1"></i> Message
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" onclick="viewDownlines(1003)">
                                            <i class="ri-group-line me-1"></i> Downlines
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Member Card 3 -->
                            <div class="card member-card mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-md me-3">
                                            <img src="https://ui-avatars.com/api/?name=Sarah+Wilson&background=random" alt="Sarah Wilson">
                                        </span>
                                        <div class="flex-fill">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h6 class="fw-semibold mb-1">Sarah Wilson</h6>
                                                    <div class="d-flex align-items-center">
                                                        <span class="activity-indicator activity-offline"></span>
                                                        <small class="text-muted">ID: 1004 • Offline</small>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <span class="level-indicator">Level 2</span>
                                                    <br>
                                                    <span class="badge bg-secondary mt-1">Silver</span>
                                                </div>
                                            </div>
                                            <div class="row text-center">
                                                <div class="col-4">
                                                    <small class="text-muted">Downlines</small>
                                                    <div class="fw-semibold">3</div>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted">Sales</small>
                                                    <div class="fw-semibold">₹12,800</div>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted">Joined</small>
                                                    <div class="fw-semibold">Feb 2024</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewMemberDetails(1004)">
                                            <i class="ri-eye-line me-1"></i> View
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="sendMessage(1004)">
                                            <i class="ri-message-line me-1"></i> Message
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" onclick="viewDownlines(1004)">
                                            <i class="ri-group-line me-1"></i> Downlines
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <p class="mb-0 text-muted">Showing 1 to 3 of 156 downlines</p>
                            <nav aria-label="Downlines pagination">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <span class="page-link">Previous</span>
                                    </li>
                                    <li class="page-item active">
                                        <span class="page-link">1</span>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">2</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function loadDownlines() {
    const selectedMember = document.getElementById('memberSelect').value;
    if (!selectedMember) {
        Swal.fire({
            title: 'Select Member',
            text: 'Please select a member to view their downlines',
            icon: 'info',
            confirmButtonText: 'OK'
        });
        return;
    }

    Swal.fire({
        title: 'Loading Downlines...',
        text: 'Fetching downline structure',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        timer: 2000,
        didOpen: () => {
            Swal.showLoading();
        }
    }).then(() => {
        Swal.fire({
            title: 'Downlines Loaded!',
            text: 'Downline structure has been updated',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
    });
}

function refreshData() {
    Swal.fire({
        title: 'Refreshing...',
        text: 'Loading latest downlines data',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        timer: 1500,
        didOpen: () => {
            Swal.showLoading();
        }
    }).then(() => {
        location.reload();
    });
}

function applyFilters() {
    Swal.fire({
        title: 'Applying Filters...',
        text: 'Filtering downlines based on selected criteria',
        icon: 'info',
        timer: 1000,
        showConfirmButton: false
    });
}

function clearFilters() {
    document.getElementById('levelFilter').value = '';
    document.getElementById('rankFilter').value = '';
    document.getElementById('activityFilter').value = '';
    document.getElementById('searchMember').value = '';
    
    Swal.fire({
        title: 'Filters Cleared!',
        text: 'All filters have been reset',
        icon: 'success',
        timer: 1000,
        showConfirmButton: false
    });
}

function exportDownlines() {
    Swal.fire({
        title: 'Export Downlines',
        text: 'Choose export format',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Excel',
        cancelButtonText: 'PDF',
        showDenyButton: true,
        denyButtonText: 'CSV'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Exporting to Excel...', '', 'info');
        } else if (result.isDenied) {
            Swal.fire('Exporting to CSV...', '', 'info');
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Exporting to PDF...', '', 'info');
        }
    });
}

function viewMemberDetails(memberId) {
    Swal.fire({
        title: 'Member Details',
        html: `
            <div class="text-start">
                <h6>Member Information</h6>
                <p><strong>Member ID:</strong> ${memberId}</p>
                <p><strong>Name:</strong> Jane Smith</p>
                <p><strong>Rank:</strong> Gold</p>
                <p><strong>Level:</strong> 1 (Direct)</p>
                <p><strong>Join Date:</strong> January 15, 2024</p>
                <p><strong>Sponsor:</strong> John Doe (1001)</p>
                <p><strong>Direct Downlines:</strong> 8</p>
                <p><strong>Total Team:</strong> 25</p>
                <p><strong>Team Volume:</strong> ₹25,600</p>
                <p><strong>Total Earnings:</strong> ₹8,750</p>
            </div>
        `,
        showCloseButton: true,
        showCancelButton: false,
        confirmButtonText: 'View Full Profile'
    });
}

function sendMessage(memberId) {
    Swal.fire({
        title: 'Send Message',
        input: 'textarea',
        inputLabel: 'Message',
        inputPlaceholder: 'Type your message here...',
        showCancelButton: true,
        confirmButtonText: 'Send',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            Swal.fire('Message Sent!', 'Your message has been sent successfully', 'success');
        }
    });
}

function viewDownlines(memberId) {
    Swal.fire({
        title: 'Loading Downlines...',
        text: `Loading downlines for member ${memberId}`,
        icon: 'info',
        timer: 1500,
        showConfirmButton: false
    });
}

function expandAll() {
    Swal.fire({
        title: 'Expanding Tree...',
        text: 'Loading all downline levels',
        icon: 'info',
        timer: 1000,
        showConfirmButton: false
    });
}

function collapseAll() {
    Swal.fire({
        title: 'Collapsing Tree...',
        text: 'Hiding lower levels',
        icon: 'info',
        timer: 1000,
        showConfirmButton: false
    });
}

function loadMoreLevels() {
    Swal.fire({
        title: 'Loading More Levels...',
        text: 'Fetching deeper downline levels',
        icon: 'info',
        timer: 1500,
        showConfirmButton: false
    });
}
</script>
@endpush
