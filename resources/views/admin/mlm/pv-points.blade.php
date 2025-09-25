@extends('admin.layouts.app')

@section('title', 'MLM PV Points')

@push('styles')
<style>
    .pv-card {
        transition: transform 0.3s ease;
        border-left: 4px solid #007bff;
    }
    
    .pv-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    
    .pv-amount {
        font-size: 1.8rem;
        font-weight: bold;
        color: #007bff;
    }
    
    .pv-status {
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: bold;
    }
    
    .stats-widget {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
    }
    
    .pv-progress {
        height: 8px;
        border-radius: 10px;
    }
    
    .member-pv-card {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    
    .member-pv-card:hover {
        border-color: #007bff;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .pv-trend {
        font-size: 0.875rem;
    }
    
    .trend-up { color: #28a745; }
    .trend-down { color: #dc3545; }
    .trend-neutral { color: #6c757d; }
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
                                <h4 class="fw-semibold mb-1">MLM PV Points Management</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="#">MLM</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">PV Points</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPvModal">
                                    <i class="ri-add-circle-line me-1"></i> Add PV Points
                                </button>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#calculatePvModal">
                                    <i class="ri-calculator-line me-1"></i> Calculate PV
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
                        <h4 class="mb-1">45,250</h4>
                        <p class="mb-0">Total PV Points</p>
                        <small class="opacity-75">All members combined</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <span class="avatar bg-success-transparent">
                                <i class="ri-arrow-up-line fs-18"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-1">8,750</h5>
                        <p class="mb-0 text-muted">PV This Month</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <span class="avatar bg-warning-transparent">
                                <i class="ri-group-line fs-18"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-1">1,245</h5>
                        <p class="mb-0 text-muted">Active Members</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <span class="avatar bg-info-transparent">
                                <i class="ri-trophy-line fs-18"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-1">₹2,25,000</h5>
                        <p class="mb-0 text-muted">PV Value</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- PV Overview and Top Performers -->
        <div class="row">
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">PV Points Overview</div>
                        <div class="card-actions">
                            <select class="form-select form-select-sm" style="width: 150px;">
                                <option value="monthly">Monthly</option>
                                <option value="weekly">Weekly</option>
                                <option value="daily">Daily</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="pvChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Top PV Performers</div>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex align-items-center">
                                <span class="avatar avatar-sm me-3">
                                    <img src="https://ui-avatars.com/api/?name=John+Doe&background=random" alt="John Doe">
                                </span>
                                <div class="flex-fill">
                                    <div class="fw-semibold">John Doe</div>
                                    <small class="text-muted">ID: 1001</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">2,850 PV</div>
                                    <small class="text-success">+125 this month</small>
                                </div>
                            </div>
                            
                            <div class="list-group-item d-flex align-items-center">
                                <span class="avatar avatar-sm me-3">
                                    <img src="https://ui-avatars.com/api/?name=Jane+Smith&background=random" alt="Jane Smith">
                                </span>
                                <div class="flex-fill">
                                    <div class="fw-semibold">Jane Smith</div>
                                    <small class="text-muted">ID: 1002</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">2,420 PV</div>
                                    <small class="text-success">+98 this month</small>
                                </div>
                            </div>
                            
                            <div class="list-group-item d-flex align-items-center">
                                <span class="avatar avatar-sm me-3">
                                    <img src="https://ui-avatars.com/api/?name=Mike+Johnson&background=random" alt="Mike Johnson">
                                </span>
                                <div class="flex-fill">
                                    <div class="fw-semibold">Mike Johnson</div>
                                    <small class="text-muted">ID: 1003</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">2,180 PV</div>
                                    <small class="text-success">+156 this month</small>
                                </div>
                            </div>
                            
                            <div class="list-group-item d-flex align-items-center">
                                <span class="avatar avatar-sm me-3">
                                    <img src="https://ui-avatars.com/api/?name=Sarah+Wilson&background=random" alt="Sarah Wilson">
                                </span>
                                <div class="flex-fill">
                                    <div class="fw-semibold">Sarah Wilson</div>
                                    <small class="text-muted">ID: 1004</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">1,950 PV</div>
                                    <small class="text-warning">+45 this month</small>
                                </div>
                            </div>
                            
                            <div class="list-group-item d-flex align-items-center">
                                <span class="avatar avatar-sm me-3">
                                    <img src="https://ui-avatars.com/api/?name=David+Brown&background=random" alt="David Brown">
                                </span>
                                <div class="flex-fill">
                                    <div class="fw-semibold">David Brown</div>
                                    <small class="text-muted">ID: 1005</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">1,750 PV</div>
                                    <small class="text-danger">-25 this month</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Member PV Details -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Member PV Points</div>
                        <div class="card-actions">
                            <div class="input-group" style="width: 300px;">
                                <input type="text" class="form-control form-control-sm" placeholder="Search by name or ID...">
                                <button class="btn btn-outline-secondary btn-sm" type="button">
                                    <i class="ri-search-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Member PV Card 1 -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card member-pv-card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar avatar-md me-3">
                                                <img src="https://ui-avatars.com/api/?name=John+Doe&background=random" alt="John Doe">
                                            </span>
                                            <div class="flex-fill">
                                                <h6 class="fw-semibold mb-1">John Doe</h6>
                                                <small class="text-muted">ID: 1001 • Diamond Rank</small>
                                            </div>
                                            <span class="pv-status bg-success text-white">Active</span>
                                        </div>
                                        
                                        <div class="text-center mb-3">
                                            <div class="pv-amount">2,850</div>
                                            <small class="text-muted">Total PV Points</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small>Monthly Progress</small>
                                                <small>85%</small>
                                            </div>
                                            <div class="progress pv-progress">
                                                <div class="progress-bar bg-primary" style="width: 85%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <small class="text-muted">This Month</small>
                                                <div class="fw-semibold pv-trend trend-up">
                                                    <i class="ri-arrow-up-line"></i> +125
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Last Month</small>
                                                <div class="fw-semibold">2,725</div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3 d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-primary flex-fill" onclick="viewPvHistory(1001)">
                                                <i class="ri-history-line me-1"></i> History
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="addPvPoints(1001)">
                                                <i class="ri-add-line me-1"></i> Add PV
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Member PV Card 2 -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card member-pv-card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar avatar-md me-3">
                                                <img src="https://ui-avatars.com/api/?name=Jane+Smith&background=random" alt="Jane Smith">
                                            </span>
                                            <div class="flex-fill">
                                                <h6 class="fw-semibold mb-1">Jane Smith</h6>
                                                <small class="text-muted">ID: 1002 • Gold Rank</small>
                                            </div>
                                            <span class="pv-status bg-success text-white">Active</span>
                                        </div>
                                        
                                        <div class="text-center mb-3">
                                            <div class="pv-amount">2,420</div>
                                            <small class="text-muted">Total PV Points</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small>Monthly Progress</small>
                                                <small>72%</small>
                                            </div>
                                            <div class="progress pv-progress">
                                                <div class="progress-bar bg-warning" style="width: 72%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <small class="text-muted">This Month</small>
                                                <div class="fw-semibold pv-trend trend-up">
                                                    <i class="ri-arrow-up-line"></i> +98
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Last Month</small>
                                                <div class="fw-semibold">2,322</div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3 d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-primary flex-fill" onclick="viewPvHistory(1002)">
                                                <i class="ri-history-line me-1"></i> History
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="addPvPoints(1002)">
                                                <i class="ri-add-line me-1"></i> Add PV
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Member PV Card 3 -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card member-pv-card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar avatar-md me-3">
                                                <img src="https://ui-avatars.com/api/?name=Mike+Johnson&background=random" alt="Mike Johnson">
                                            </span>
                                            <div class="flex-fill">
                                                <h6 class="fw-semibold mb-1">Mike Johnson</h6>
                                                <small class="text-muted">ID: 1003 • Platinum Rank</small>
                                            </div>
                                            <span class="pv-status bg-warning text-dark">Pending</span>
                                        </div>
                                        
                                        <div class="text-center mb-3">
                                            <div class="pv-amount">2,180</div>
                                            <small class="text-muted">Total PV Points</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small>Monthly Progress</small>
                                                <small>95%</small>
                                            </div>
                                            <div class="progress pv-progress">
                                                <div class="progress-bar bg-info" style="width: 95%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <small class="text-muted">This Month</small>
                                                <div class="fw-semibold pv-trend trend-up">
                                                    <i class="ri-arrow-up-line"></i> +156
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Last Month</small>
                                                <div class="fw-semibold">2,024</div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3 d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-primary flex-fill" onclick="viewPvHistory(1003)">
                                                <i class="ri-history-line me-1"></i> History
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="addPvPoints(1003)">
                                                <i class="ri-add-line me-1"></i> Add PV
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Member PV Card 4 -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card member-pv-card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar avatar-md me-3">
                                                <img src="https://ui-avatars.com/api/?name=Sarah+Wilson&background=random" alt="Sarah Wilson">
                                            </span>
                                            <div class="flex-fill">
                                                <h6 class="fw-semibold mb-1">Sarah Wilson</h6>
                                                <small class="text-muted">ID: 1004 • Silver Rank</small>
                                            </div>
                                            <span class="pv-status bg-success text-white">Active</span>
                                        </div>
                                        
                                        <div class="text-center mb-3">
                                            <div class="pv-amount">1,950</div>
                                            <small class="text-muted">Total PV Points</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small>Monthly Progress</small>
                                                <small>58%</small>
                                            </div>
                                            <div class="progress pv-progress">
                                                <div class="progress-bar bg-secondary" style="width: 58%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <small class="text-muted">This Month</small>
                                                <div class="fw-semibold pv-trend trend-up">
                                                    <i class="ri-arrow-up-line"></i> +45
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Last Month</small>
                                                <div class="fw-semibold">1,905</div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3 d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-primary flex-fill" onclick="viewPvHistory(1004)">
                                                <i class="ri-history-line me-1"></i> History
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="addPvPoints(1004)">
                                                <i class="ri-add-line me-1"></i> Add PV
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Member PV Card 5 -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card member-pv-card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar avatar-md me-3">
                                                <img src="https://ui-avatars.com/api/?name=David+Brown&background=random" alt="David Brown">
                                            </span>
                                            <div class="flex-fill">
                                                <h6 class="fw-semibold mb-1">David Brown</h6>
                                                <small class="text-muted">ID: 1005 • Bronze Rank</small>
                                            </div>
                                            <span class="pv-status bg-danger text-white">Inactive</span>
                                        </div>
                                        
                                        <div class="text-center mb-3">
                                            <div class="pv-amount">1,750</div>
                                            <small class="text-muted">Total PV Points</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small>Monthly Progress</small>
                                                <small>35%</small>
                                            </div>
                                            <div class="progress pv-progress">
                                                <div class="progress-bar bg-danger" style="width: 35%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <small class="text-muted">This Month</small>
                                                <div class="fw-semibold pv-trend trend-down">
                                                    <i class="ri-arrow-down-line"></i> -25
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Last Month</small>
                                                <div class="fw-semibold">1,775</div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3 d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-primary flex-fill" onclick="viewPvHistory(1005)">
                                                <i class="ri-history-line me-1"></i> History
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="addPvPoints(1005)">
                                                <i class="ri-add-line me-1"></i> Add PV
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Member PV Card 6 -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card member-pv-card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar avatar-md me-3">
                                                <img src="https://ui-avatars.com/api/?name=Lisa+Davis&background=random" alt="Lisa Davis">
                                            </span>
                                            <div class="flex-fill">
                                                <h6 class="fw-semibold mb-1">Lisa Davis</h6>
                                                <small class="text-muted">ID: 1006 • Gold Rank</small>
                                            </div>
                                            <span class="pv-status bg-success text-white">Active</span>
                                        </div>
                                        
                                        <div class="text-center mb-3">
                                            <div class="pv-amount">1,650</div>
                                            <small class="text-muted">Total PV Points</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small>Monthly Progress</small>
                                                <small>68%</small>
                                            </div>
                                            <div class="progress pv-progress">
                                                <div class="progress-bar bg-warning" style="width: 68%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <small class="text-muted">This Month</small>
                                                <div class="fw-semibold pv-trend trend-up">
                                                    <i class="ri-arrow-up-line"></i> +78
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Last Month</small>
                                                <div class="fw-semibold">1,572</div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3 d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-primary flex-fill" onclick="viewPvHistory(1006)">
                                                <i class="ri-history-line me-1"></i> History
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="addPvPoints(1006)">
                                                <i class="ri-add-line me-1"></i> Add PV
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <p class="mb-0 text-muted">Showing 1 to 6 of 1,245 members</p>
                            <nav aria-label="PV pagination">
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

    <!-- Add PV Modal -->
    <div class="modal fade" id="addPvModal" tabindex="-1" aria-labelledby="addPvModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPvModalLabel">Add PV Points</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPvForm">
                        <div class="mb-3">
                            <label for="memberId" class="form-label">Select Member</label>
                            <select class="form-select" id="memberId" name="member_id" required>
                                <option value="">Choose Member</option>
                                <option value="1001">John Doe (ID: 1001)</option>
                                <option value="1002">Jane Smith (ID: 1002)</option>
                                <option value="1003">Mike Johnson (ID: 1003)</option>
                                <option value="1004">Sarah Wilson (ID: 1004)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="pvPoints" class="form-label">PV Points</label>
                            <input type="number" class="form-control" id="pvPoints" name="pv_points" min="1" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="pvType" class="form-label">PV Type</label>
                            <select class="form-select" id="pvType" name="pv_type" required>
                                <option value="">Select Type</option>
                                <option value="purchase">Purchase</option>
                                <option value="bonus">Bonus</option>
                                <option value="adjustment">Adjustment</option>
                                <option value="promotion">Promotion</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="pvReason" class="form-label">Reason</label>
                            <textarea class="form-control" id="pvReason" name="reason" rows="3" placeholder="Reason for adding PV points..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="pvDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="pvDate" name="pv_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="savePvPoints()">Add PV Points</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Calculate PV Modal -->
    <div class="modal fade" id="calculatePvModal" tabindex="-1" aria-labelledby="calculatePvModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="calculatePvModalLabel">Calculate PV Points</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="calculatePvForm">
                        <div class="mb-3">
                            <label for="calculationType" class="form-label">Calculation Type</label>
                            <select class="form-select" id="calculationType" name="calculation_type" required>
                                <option value="">Select Type</option>
                                <option value="all_members">All Members</option>
                                <option value="specific_rank">Specific Rank</option>
                                <option value="date_range">Date Range</option>
                                <option value="level_based">Level Based</option>
                            </select>
                        </div>
                        
                        <div class="mb-3" id="rankSelection" style="display: none;">
                            <label for="rankFilter" class="form-label">Select Rank</label>
                            <select class="form-select" id="rankFilter" name="rank">
                                <option value="">All Ranks</option>
                                <option value="starter">Starter</option>
                                <option value="bronze">Bronze</option>
                                <option value="silver">Silver</option>
                                <option value="gold">Gold</option>
                                <option value="platinum">Platinum</option>
                                <option value="diamond">Diamond</option>
                            </select>
                        </div>
                        
                        <div class="row" id="dateSelection" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="start_date">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate" name="end_date">
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="ri-information-line me-2"></i>
                            <strong>Note:</strong> This will recalculate PV points based on recent activities and purchases.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="calculatePv()">Calculate PV</button>
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
        text: 'Loading latest PV data',
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

function viewPvHistory(memberId) {
    Swal.fire({
        title: 'PV Points History',
        html: `
            <div class="text-start">
                <h6>Recent PV Transactions</h6>
                <div class="list-group">
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Purchase Bonus</span>
                        <span class="text-success">+50 PV</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Product Purchase</span>
                        <span class="text-success">+125 PV</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Monthly Bonus</span>
                        <span class="text-success">+75 PV</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Rank Achievement</span>
                        <span class="text-success">+200 PV</span>
                    </div>
                </div>
            </div>
        `,
        showCloseButton: true,
        showCancelButton: false,
        confirmButtonText: 'View Full History'
    });
}

function addPvPoints(memberId) {
    // Pre-fill the member selection in the modal
    document.getElementById('memberId').value = memberId;
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('addPvModal'));
    modal.show();
}

function savePvPoints() {
    const form = document.getElementById('addPvForm');
    const formData = new FormData(form);
    
    // Basic validation
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    Swal.fire({
        title: 'Adding PV Points...',
        text: 'Please wait while we add the PV points',
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
            text: 'PV points added successfully',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            $('#addPvModal').modal('hide');
            form.reset();
            location.reload();
        });
    }, 2000);
}

function calculatePv() {
    const form = document.getElementById('calculatePvForm');
    const formData = new FormData(form);
    
    // Basic validation
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    Swal.fire({
        title: 'Are you sure?',
        text: "This will recalculate PV points for selected criteria.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, calculate!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Calculating PV Points...',
                text: 'Please wait while we process the calculations',
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
                    title: 'Calculation Complete!',
                    text: 'PV points have been recalculated successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $('#calculatePvModal').modal('hide');
                    form.reset();
                    location.reload();
                });
            }, 3000);
        }
    });
}

// Handle calculation type change
document.addEventListener('DOMContentLoaded', function() {
    const calculationType = document.getElementById('calculationType');
    const rankSelection = document.getElementById('rankSelection');
    const dateSelection = document.getElementById('dateSelection');
    
    if (calculationType) {
        calculationType.addEventListener('change', function() {
            const value = this.value;
            
            // Hide all conditional fields
            rankSelection.style.display = 'none';
            dateSelection.style.display = 'none';
            
            // Show relevant fields based on selection
            if (value === 'specific_rank') {
                rankSelection.style.display = 'block';
            } else if (value === 'date_range') {
                dateSelection.style.display = 'block';
            }
        });
    }
    
    // Initialize PV Chart
    const ctx = document.getElementById('pvChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            datasets: [{
                label: 'Total PV Points',
                data: [35000, 38000, 41000, 39000, 42000, 45000, 43000, 46000, 45250],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Monthly PV Growth',
                data: [3200, 3500, 3800, 3600, 3900, 4200, 4000, 4300, 4100],
                borderColor: '#f093fb',
                backgroundColor: 'rgba(240, 147, 251, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'PV Points Trends'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' PV';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
