@extends('admin.layouts.app')

@section('title', 'MLM Bonuses')

@push('styles')
<style>
    .bonus-card {
        transition: transform 0.3s ease;
        border-left: 4px solid #007bff;
    }
    
    .bonus-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    
    .bonus-amount {
        font-size: 1.5rem;
        font-weight: bold;
        color: #28a745;
    }
    
    .bonus-type {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
    }
    
    .stats-widget {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border-radius: 15px;
    }
    
    .bonus-status {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    
    .status-paid { background-color: #28a745; }
    .status-pending { background-color: #ffc107; }
    .status-processing { background-color: #17a2b8; }
    .status-cancelled { background-color: #dc3545; }
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
                                <h4 class="fw-semibold mb-1">MLM Bonuses Management</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="#">MLM</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Bonuses</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#processBonusModal">
                                    <i class="ri-money-dollar-circle-line me-1"></i> Process Bonuses
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
                        <h4 class="mb-1">₹1,24,500</h4>
                        <p class="mb-0">Total Bonuses Paid</p>
                        <small class="opacity-75">This month</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <span class="avatar bg-warning-transparent">
                                <i class="ri-time-line fs-18"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-1">₹18,750</h5>
                        <p class="mb-0 text-muted">Pending Bonuses</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <span class="avatar bg-success-transparent">
                                <i class="ri-check-double-line fs-18"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-1">456</h5>
                        <p class="mb-0 text-muted">Bonuses Processed</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <span class="avatar bg-info-transparent">
                                <i class="ri-gift-line fs-18"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-1">8</h5>
                        <p class="mb-0 text-muted">Bonus Types</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bonus Types Overview -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Bonus Types Overview</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Direct Referral Bonus -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card bonus-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="bonus-type">Direct Referral</div>
                                            <i class="ri-user-add-line fs-24 text-primary"></i>
                                        </div>
                                        <h6 class="fw-semibold mb-2">Direct Referral Bonus</h6>
                                        <p class="text-muted mb-3">Bonus for direct member referrals</p>
                                        <div class="bonus-amount mb-2">₹25,600</div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">This month: 128 bonuses</small>
                                            <span class="badge bg-success">Active</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Level Bonus -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card bonus-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="bonus-type">Level Bonus</div>
                                            <i class="ri-stack-line fs-24 text-warning"></i>
                                        </div>
                                        <h6 class="fw-semibold mb-2">Level Bonus</h6>
                                        <p class="text-muted mb-3">Bonus from downline levels</p>
                                        <div class="bonus-amount mb-2">₹42,300</div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">This month: 285 bonuses</small>
                                            <span class="badge bg-success">Active</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rank Achievement Bonus -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card bonus-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="bonus-type">Rank Achievement</div>
                                            <i class="ri-trophy-line fs-24 text-success"></i>
                                        </div>
                                        <h6 class="fw-semibold mb-2">Rank Achievement Bonus</h6>
                                        <p class="text-muted mb-3">One-time bonus for rank ups</p>
                                        <div class="bonus-amount mb-2">₹15,750</div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">This month: 12 bonuses</small>
                                            <span class="badge bg-success">Active</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Matching Bonus -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card bonus-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="bonus-type">Matching Bonus</div>
                                            <i class="ri-shuffle-line fs-24 text-info"></i>
                                        </div>
                                        <h6 class="fw-semibold mb-2">Matching Bonus</h6>
                                        <p class="text-muted mb-3">Percentage of downline earnings</p>
                                        <div class="bonus-amount mb-2">₹18,900</div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">This month: 89 bonuses</small>
                                            <span class="badge bg-success">Active</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pool Bonus -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card bonus-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="bonus-type">Pool Bonus</div>
                                            <i class="ri-pie-chart-line fs-24 text-danger"></i>
                                        </div>
                                        <h6 class="fw-semibold mb-2">Pool Bonus</h6>
                                        <p class="text-muted mb-3">Share from company pool</p>
                                        <div class="bonus-amount mb-2">₹12,450</div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">This month: 35 bonuses</small>
                                            <span class="badge bg-warning">Pending</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Leadership Bonus -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card bonus-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="bonus-type">Leadership Bonus</div>
                                            <i class="ri-crown-line fs-24 text-primary"></i>
                                        </div>
                                        <h6 class="fw-semibold mb-2">Leadership Bonus</h6>
                                        <p class="text-muted mb-3">Special leadership rewards</p>
                                        <div class="bonus-amount mb-2">₹8,200</div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">This month: 15 bonuses</small>
                                            <span class="badge bg-success">Active</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bonus Transactions -->
        <div class="row">
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Recent Bonus Transactions</div>
                        <div class="card-actions">
                            <select class="form-select form-select-sm" style="width: 150px;">
                                <option value="">All Types</option>
                                <option value="direct">Direct Referral</option>
                                <option value="level">Level Bonus</option>
                                <option value="rank">Rank Achievement</option>
                                <option value="matching">Matching Bonus</option>
                                <option value="pool">Pool Bonus</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Member</th>
                                        <th>Bonus Type</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm me-2">
                                                    <img src="https://ui-avatars.com/api/?name=John+Doe&background=random" alt="John Doe">
                                                </span>
                                                <div>
                                                    <div class="fw-semibold">John Doe</div>
                                                    <small class="text-muted">ID: 1001</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-primary">Direct Referral</span></td>
                                        <td class="fw-semibold text-success">₹2,500</td>
                                        <td>2025-09-10</td>
                                        <td>
                                            <span class="bonus-status status-paid"></span>
                                            <span class="badge bg-success">Paid</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewBonusDetails(1)">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm me-2">
                                                    <img src="https://ui-avatars.com/api/?name=Jane+Smith&background=random" alt="Jane Smith">
                                                </span>
                                                <div>
                                                    <div class="fw-semibold">Jane Smith</div>
                                                    <small class="text-muted">ID: 1002</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-warning">Level Bonus</span></td>
                                        <td class="fw-semibold text-success">₹1,800</td>
                                        <td>2025-09-10</td>
                                        <td>
                                            <span class="bonus-status status-pending"></span>
                                            <span class="badge bg-warning">Pending</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewBonusDetails(2)">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm me-2">
                                                    <img src="https://ui-avatars.com/api/?name=Mike+Johnson&background=random" alt="Mike Johnson">
                                                </span>
                                                <div>
                                                    <div class="fw-semibold">Mike Johnson</div>
                                                    <small class="text-muted">ID: 1003</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">Rank Achievement</span></td>
                                        <td class="fw-semibold text-success">₹5,000</td>
                                        <td>2025-09-09</td>
                                        <td>
                                            <span class="bonus-status status-processing"></span>
                                            <span class="badge bg-info">Processing</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewBonusDetails(3)">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm me-2">
                                                    <img src="https://ui-avatars.com/api/?name=Sarah+Wilson&background=random" alt="Sarah Wilson">
                                                </span>
                                                <div>
                                                    <div class="fw-semibold">Sarah Wilson</div>
                                                    <small class="text-muted">ID: 1004</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-info">Matching Bonus</span></td>
                                        <td class="fw-semibold text-success">₹950</td>
                                        <td>2025-09-09</td>
                                        <td>
                                            <span class="bonus-status status-paid"></span>
                                            <span class="badge bg-success">Paid</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewBonusDetails(4)">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm me-2">
                                                    <img src="https://ui-avatars.com/api/?name=David+Brown&background=random" alt="David Brown">
                                                </span>
                                                <div>
                                                    <div class="fw-semibold">David Brown</div>
                                                    <small class="text-muted">ID: 1005</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-danger">Pool Bonus</span></td>
                                        <td class="fw-semibold text-success">₹750</td>
                                        <td>2025-09-08</td>
                                        <td>
                                            <span class="bonus-status status-pending"></span>
                                            <span class="badge bg-warning">Pending</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewBonusDetails(5)">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <p class="mb-0 text-muted">Showing 1 to 5 of 125 entries</p>
                            <nav aria-label="Bonus pagination">
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
            <div class="col-xl-4">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Bonus Analytics</div>
                    </div>
                    <div class="card-body">
                        <canvas id="bonusChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Process Bonus Modal -->
    <div class="modal fade" id="processBonusModal" tabindex="-1" aria-labelledby="processBonusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="processBonusModalLabel">Process Bonuses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="processBonusForm">
                        <div class="mb-3">
                            <label for="bonusType" class="form-label">Bonus Type</label>
                            <select class="form-select" id="bonusType" name="bonus_type" required>
                                <option value="">Select Bonus Type</option>
                                <option value="all">All Pending Bonuses</option>
                                <option value="direct">Direct Referral</option>
                                <option value="level">Level Bonus</option>
                                <option value="rank">Rank Achievement</option>
                                <option value="matching">Matching Bonus</option>
                                <option value="pool">Pool Bonus</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="processDate" class="form-label">Process Date</label>
                            <input type="date" class="form-control" id="processDate" name="process_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Processing Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add any processing notes..."></textarea>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="ri-information-line me-2"></i>
                            <strong>Note:</strong> Processing bonuses will mark them as paid and send notifications to members.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="processBonuses()">Process Bonuses</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function refreshData() {
    Swal.fire({
        title: 'Refreshing...',
        text: 'Loading latest bonus data',
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

function viewBonusDetails(bonusId) {
    Swal.fire({
        title: 'Bonus Details',
        html: `
            <div class="text-start">
                <h6>Bonus Information</h6>
                <p><strong>Bonus ID:</strong> #BNS${bonusId.toString().padStart(6, '0')}</p>
                <p><strong>Member:</strong> John Doe (ID: 1001)</p>
                <p><strong>Type:</strong> Direct Referral Bonus</p>
                <p><strong>Amount:</strong> ₹2,500</p>
                <p><strong>Generated Date:</strong> 2025-09-10</p>
                <p><strong>Status:</strong> <span class="badge bg-success">Paid</span></p>
                <p><strong>Reference:</strong> New member referral - Jane Smith</p>
            </div>
        `,
        showCloseButton: true,
        showCancelButton: false,
        confirmButtonText: 'Close'
    });
}

function processBonuses() {
    const form = document.getElementById('processBonusForm');
    const formData = new FormData(form);
    
    // Basic validation
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    Swal.fire({
        title: 'Are you sure?',
        text: "This will process all selected bonuses and mark them as paid.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, process bonuses!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processing Bonuses...',
                text: 'Please wait while we process the bonuses',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Simulate API call
            setTimeout(() => {
                Swal.fire({
                    title: 'Success!',
                    text: 'Bonuses processed successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $('#processBonusModal').modal('hide');
                    form.reset();
                    location.reload();
                });
            }, 3000);
        }
    });
}

// Initialize bonus chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('bonusChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            datasets: [{
                label: 'Bonuses Paid (₹)',
                data: [45000, 52000, 48000, 61000, 55000, 67000, 73000, 69000, 74500],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Bonus Trends'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
