@extends('admin.layouts.app')

@section('title', 'MLM Genealogy')

@push('styles')
<style>
    .genealogy-tree {
        display: flex;
        flex-direction: column;
        align-items: center;
        font-family: Arial, sans-serif;
    }

    .node {
        position: relative;
        margin: 10px;
        padding: 15px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        min-width: 150px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .node:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }

    .node h6 {
        margin: 0 0 5px 0;
        font-weight: bold;
    }

    .node small {
        opacity: 0.8;
    }

    .level {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        flex-wrap: wrap;
        margin: 20px 0;
    }

    .connector {
        width: 2px;
        height: 30px;
        background: #ddd;
        margin: 0 auto;
    }

    .horizontal-connector {
        height: 2px;
        background: #ddd;
        flex: 1;
        margin: 0 10px;
        align-self: center;
    }

    .member-card {
        transition: transform 0.3s ease;
    }

    .member-card:hover {
        transform: translateY(-5px);
    }

    .stats-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #28a745;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
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
                                <h4 class="fw-semibold mb-1">MLM Genealogy Tree</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="#">MLM</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Genealogy</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="d-flex gap-2">
                                <select class="form-select" id="memberSelect" style="width: 250px;">
                                    <option value="">Select Member to View Tree</option>
                                    <option value="1">John Doe (ID: 1001)</option>
                                    <option value="2">Jane Smith (ID: 1002)</option>
                                    <option value="3">Mike Johnson (ID: 1003)</option>
                                </select>
                                <button type="button" class="btn btn-primary" onclick="loadTree()">
                                    <i class="ri-tree-line me-1"></i> Load Tree
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="refreshTree()">
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
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <span class="avatar bg-primary-transparent">
                                <i class="ri-group-line fs-18"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-1">2,580</h5>
                        <p class="mb-0 text-muted">Total Members</p>
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
                        <p class="mb-0 text-muted">Active Today</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <span class="avatar bg-warning-transparent">
                                <i class="ri-branch-line fs-18"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-1">12</h5>
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
                        <h5 class="fw-semibold mb-1">₹45,250</h5>
                        <p class="mb-0 text-muted">Team Volume</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Genealogy Tree -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Network Genealogy Tree</div>
                        <div class="card-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="expandAll()">
                                <i class="ri-add-circle-line me-1"></i> Expand All
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="collapseAll()">
                                <i class="ri-subtract-circle-line me-1"></i> Collapse All
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="genealogy-tree" id="genealogyTree">
                            <!-- Root Node -->
                            <div class="level">
                                <div class="node" onclick="showMemberDetails(1)">
                                    <div class="stats-badge">8</div>
                                    <h6>John Doe</h6>
                                    <small>ID: 1001</small><br>
                                    <small>Rank: Diamond</small>
                                </div>
                            </div>

                            <div class="connector"></div>

                            <!-- Level 1 -->
                            <div class="level">
                                <div class="node member-card" onclick="showMemberDetails(2)">
                                    <div class="stats-badge">3</div>
                                    <h6>Jane Smith</h6>
                                    <small>ID: 1002</small><br>
                                    <small>Rank: Gold</small>
                                </div>
                                <div class="horizontal-connector"></div>
                                <div class="node member-card" onclick="showMemberDetails(3)">
                                    <div class="stats-badge">5</div>
                                    <h6>Mike Johnson</h6>
                                    <small>ID: 1003</small><br>
                                    <small>Rank: Platinum</small>
                                </div>
                            </div>

                            <div class="connector"></div>

                            <!-- Level 2 -->
                            <div class="level">
                                <div class="node member-card" onclick="showMemberDetails(4)">
                                    <div class="stats-badge">2</div>
                                    <h6>Sarah Wilson</h6>
                                    <small>ID: 1004</small><br>
                                    <small>Rank: Silver</small>
                                </div>
                                <div class="horizontal-connector"></div>
                                <div class="node member-card" onclick="showMemberDetails(5)">
                                    <div class="stats-badge">1</div>
                                    <h6>David Brown</h6>
                                    <small>ID: 1005</small><br>
                                    <small>Rank: Bronze</small>
                                </div>
                                <div class="horizontal-connector"></div>
                                <div class="node member-card" onclick="showMemberDetails(6)">
                                    <div class="stats-badge">4</div>
                                    <h6>Lisa Davis</h6>
                                    <small>ID: 1006</small><br>
                                    <small>Rank: Gold</small>
                                </div>
                                <div class="horizontal-connector"></div>
                                <div class="node member-card" onclick="showMemberDetails(7)">
                                    <div class="stats-badge">2</div>
                                    <h6>Tom Anderson</h6>
                                    <small>ID: 1007</small><br>
                                    <small>Rank: Silver</small>
                                </div>
                            </div>

                            <div class="connector"></div>

                            <!-- Level 3 -->
                            <div class="level">
                                <div class="node member-card" onclick="showMemberDetails(8)">
                                    <h6>Emma Taylor</h6>
                                    <small>ID: 1008</small><br>
                                    <small>Rank: Bronze</small>
                                </div>
                                <div class="horizontal-connector"></div>
                                <div class="node member-card" onclick="showMemberDetails(9)">
                                    <h6>James Miller</h6>
                                    <small>ID: 1009</small><br>
                                    <small>Rank: Starter</small>
                                </div>
                                <div class="horizontal-connector"></div>
                                <div class="node member-card" onclick="showMemberDetails(10)">
                                    <h6>Amy Garcia</h6>
                                    <small>ID: 1010</small><br>
                                    <small>Rank: Bronze</small>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button class="btn btn-outline-primary" onclick="loadMoreLevels()">
                                <i class="ri-arrow-down-line me-1"></i> Load More Levels
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Member Details Panel -->
        <div class="row">
            <div class="col-xl-4">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Member Details</div>
                    </div>
                    <div class="card-body" id="memberDetailsPanel">
                        <div class="text-center text-muted">
                            <i class="ri-user-3-line fs-48 mb-3"></i>
                            <p>Click on any member in the tree to view their details</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Network Statistics</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <canvas id="levelDistributionChart" height="200"></canvas>
                            </div>
                            <div class="col-md-6">
                                <canvas id="rankDistributionChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function loadTree() {
    const selectedMember = document.getElementById('memberSelect').value;
    if (!selectedMember) {
        Swal.fire({
            title: 'Select Member',
            text: 'Please select a member to view their genealogy tree',
            icon: 'info',
            confirmButtonText: 'OK'
        });
        return;
    }

    Swal.fire({
        title: 'Loading Tree...',
        text: 'Building genealogy tree structure',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        timer: 2000,
        didOpen: () => {
            Swal.showLoading();
        }
    }).then(() => {
        // Here you would load the actual tree data
        Swal.fire({
            title: 'Tree Loaded!',
            text: 'Genealogy tree has been updated',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
    });
}

function refreshTree() {
    Swal.fire({
        title: 'Refreshing...',
        text: 'Updating tree with latest data',
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

function showMemberDetails(memberId) {
    const memberData = {
        1: {
            name: 'John Doe',
            id: '1001',
            rank: 'Diamond',
            joinDate: '2023-01-15',
            sponsor: 'Admin',
            directReferrals: 8,
            teamSize: 45,
            teamVolume: '₹45,250',
            totalEarnings: '₹12,500',
            currentLevel: 'Root',
            email: 'john.doe@example.com',
            phone: '+1-234-567-8900',
            status: 'Active'
        },
        2: {
            name: 'Jane Smith',
            id: '1002',
            rank: 'Gold',
            joinDate: '2023-02-20',
            sponsor: 'John Doe',
            directReferrals: 3,
            teamSize: 12,
            teamVolume: '₹18,750',
            totalEarnings: '₹4,200',
            currentLevel: '1',
            email: 'jane.smith@example.com',
            phone: '+1-234-567-8901',
            status: 'Active'
        }
        // Add more member data as needed
    };

    const member = memberData[memberId] || memberData[1];
    
    const detailsHtml = `
        <div class="text-center mb-3">
            <div class="avatar avatar-xl">
                <img src="https://ui-avatars.com/api/?name=${member.name}&background=random" alt="${member.name}" class="rounded-circle">
            </div>
            <h5 class="mt-2 mb-1">${member.name}</h5>
            <span class="badge bg-primary">${member.rank}</span>
        </div>
        
        <div class="list-group list-group-flush">
            <div class="list-group-item d-flex justify-content-between">
                <span><i class="ri-hashtag me-2"></i>Member ID:</span>
                <strong>${member.id}</strong>
            </div>
            <div class="list-group-item d-flex justify-content-between">
                <span><i class="ri-calendar-line me-2"></i>Join Date:</span>
                <strong>${member.joinDate}</strong>
            </div>
            <div class="list-group-item d-flex justify-content-between">
                <span><i class="ri-user-line me-2"></i>Sponsor:</span>
                <strong>${member.sponsor}</strong>
            </div>
            <div class="list-group-item d-flex justify-content-between">
                <span><i class="ri-group-line me-2"></i>Direct Referrals:</span>
                <strong>${member.directReferrals}</strong>
            </div>
            <div class="list-group-item d-flex justify-content-between">
                <span><i class="ri-team-line me-2"></i>Team Size:</span>
                <strong>${member.teamSize}</strong>
            </div>
            <div class="list-group-item d-flex justify-content-between">
                <span><i class="ri-money-dollar-circle-line me-2"></i>Team Volume:</span>
                <strong>${member.teamVolume}</strong>
            </div>
            <div class="list-group-item d-flex justify-content-between">
                <span><i class="ri-coin-line me-2"></i>Total Earnings:</span>
                <strong>${member.totalEarnings}</strong>
            </div>
            <div class="list-group-item d-flex justify-content-between">
                <span><i class="ri-mail-line me-2"></i>Email:</span>
                <strong>${member.email}</strong>
            </div>
            <div class="list-group-item d-flex justify-content-between">
                <span><i class="ri-phone-line me-2"></i>Phone:</span>
                <strong>${member.phone}</strong>
            </div>
            <div class="list-group-item d-flex justify-content-between">
                <span><i class="ri-checkbox-circle-line me-2"></i>Status:</span>
                <span class="badge bg-success">${member.status}</span>
            </div>
        </div>
        
        <div class="mt-3 d-grid gap-2">
            <button class="btn btn-primary btn-sm" onclick="viewFullProfile(${memberId})">
                <i class="ri-user-3-line me-1"></i> View Full Profile
            </button>
            <button class="btn btn-outline-primary btn-sm" onclick="sendMessage(${memberId})">
                <i class="ri-message-line me-1"></i> Send Message
            </button>
        </div>
    `;
    
    document.getElementById('memberDetailsPanel').innerHTML = detailsHtml;
}

function expandAll() {
    Swal.fire({
        title: 'Expanding Tree...',
        text: 'Loading all levels',
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
        text: 'Fetching deeper genealogy levels',
        icon: 'info',
        timer: 1500,
        showConfirmButton: false
    });
}

function viewFullProfile(memberId) {
    Swal.fire({
        title: 'Opening Profile...',
        text: `Loading full profile for member ${memberId}`,
        icon: 'info',
        timer: 1000,
        showConfirmButton: false
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

// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    // Level Distribution Chart
    const levelCtx = document.getElementById('levelDistributionChart').getContext('2d');
    new Chart(levelCtx, {
        type: 'doughnut',
        data: {
            labels: ['Level 1', 'Level 2', 'Level 3', 'Level 4+'],
            datasets: [{
                data: [450, 320, 180, 95],
                backgroundColor: ['#667eea', '#764ba2', '#f093fb', '#f5576c']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Level Distribution'
                }
            }
        }
    });

    // Rank Distribution Chart
    const rankCtx = document.getElementById('rankDistributionChart').getContext('2d');
    new Chart(rankCtx, {
        type: 'bar',
        data: {
            labels: ['Starter', 'Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond'],
            datasets: [{
                label: 'Members',
                data: [450, 280, 185, 95, 45, 12],
                backgroundColor: '#667eea'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Rank Distribution'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
