@extends('admin.layouts.app')

@section('title', 'MLM Ranks Management')

@push('styles')
<style>
    .rank-card {
        transition: transform 0.3s ease;
        border: 2px solid transparent;
    }
    
    .rank-card:hover {
        transform: translateY(-5px);
        border-color: #007bff;
    }
    
    .rank-badge {
        font-size: 1.2rem;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
    }
    
    .rank-progress {
        height: 8px;
        border-radius: 10px;
    }
    
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
    }
    
    .rank-level {
        font-size: 2rem;
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
                                <h4 class="fw-semibold mb-1">MLM Ranks Management</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="#">MLM</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Ranks</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRankModal">
                                    <i class="ri-add-line me-1"></i> Add New Rank
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
                <div class="card custom-card stats-card">
                    <div class="card-body text-center">
                        <div class="rank-level">8</div>
                        <h6 class="mb-0">Total Ranks</h6>
                        <small class="opacity-75">Active rank levels</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <span class="avatar bg-primary-transparent">
                                <i class="ri-user-star-line fs-18"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-1">1,245</h5>
                        <p class="mb-0 text-muted">Members with Ranks</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <span class="avatar bg-success-transparent">
                                <i class="ri-trophy-line fs-18"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-1">Diamond</h5>
                        <p class="mb-0 text-muted">Highest Rank</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <span class="avatar bg-warning-transparent">
                                <i class="ri-award-line fs-18"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-1">₹2,50,000</h5>
                        <p class="mb-0 text-muted">Rank Bonuses Paid</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ranks Grid -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">MLM Rank Structure</div>
                    </div>
                    <div class="card-body">
                        <div class="row" id="ranksContainer">
                            <!-- Starter Rank -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card rank-card">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <span class="rank-badge bg-light text-dark">
                                                <i class="ri-user-line me-1"></i> Starter
                                            </span>
                                        </div>
                                        <h6 class="fw-semibold mb-2">Level 1</h6>
                                        <p class="text-muted mb-3">Entry level rank for new members</p>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">Requirements:</small>
                                            <ul class="list-unstyled mt-2">
                                                <li><i class="ri-check-line text-success me-1"></i> Join the network</li>
                                                <li><i class="ri-check-line text-success me-1"></i> Complete profile</li>
                                            </ul>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">Benefits:</small>
                                            <ul class="list-unstyled mt-2">
                                                <li><i class="ri-gift-line text-warning me-1"></i> 5% commission</li>
                                                <li><i class="ri-gift-line text-warning me-1"></i> Basic support</li>
                                            </ul>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-primary">Active: 450</span>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" onclick="editRank(1)">
                                                    <i class="ri-edit-line"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" onclick="deleteRank(1)">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bronze Rank -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card rank-card">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <span class="rank-badge bg-warning text-dark">
                                                <i class="ri-medal-line me-1"></i> Bronze
                                            </span>
                                        </div>
                                        <h6 class="fw-semibold mb-2">Level 2</h6>
                                        <p class="text-muted mb-3">First achievement rank</p>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">Requirements:</small>
                                            <ul class="list-unstyled mt-2">
                                                <li><i class="ri-check-line text-success me-1"></i> 3 direct referrals</li>
                                                <li><i class="ri-check-line text-success me-1"></i> ₹10,000 team sales</li>
                                            </ul>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">Benefits:</small>
                                            <ul class="list-unstyled mt-2">
                                                <li><i class="ri-gift-line text-warning me-1"></i> 8% commission</li>
                                                <li><i class="ri-gift-line text-warning me-1"></i> ₹500 rank bonus</li>
                                            </ul>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-warning">Active: 280</span>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" onclick="editRank(2)">
                                                    <i class="ri-edit-line"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" onclick="deleteRank(2)">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Silver Rank -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card rank-card">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <span class="rank-badge bg-secondary text-white">
                                                <i class="ri-medal-2-line me-1"></i> Silver
                                            </span>
                                        </div>
                                        <h6 class="fw-semibold mb-2">Level 3</h6>
                                        <p class="text-muted mb-3">Intermediate achievement</p>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">Requirements:</small>
                                            <ul class="list-unstyled mt-2">
                                                <li><i class="ri-check-line text-success me-1"></i> 5 direct referrals</li>
                                                <li><i class="ri-check-line text-success me-1"></i> ₹25,000 team sales</li>
                                            </ul>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">Benefits:</small>
                                            <ul class="list-unstyled mt-2">
                                                <li><i class="ri-gift-line text-warning me-1"></i> 12% commission</li>
                                                <li><i class="ri-gift-line text-warning me-1"></i> ₹1,500 rank bonus</li>
                                            </ul>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-secondary">Active: 185</span>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" onclick="editRank(3)">
                                                    <i class="ri-edit-line"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" onclick="deleteRank(3)">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Gold Rank -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card rank-card">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <span class="rank-badge bg-warning text-white">
                                                <i class="ri-trophy-line me-1"></i> Gold
                                            </span>
                                        </div>
                                        <h6 class="fw-semibold mb-2">Level 4</h6>
                                        <p class="text-muted mb-3">Premium achievement</p>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">Requirements:</small>
                                            <ul class="list-unstyled mt-2">
                                                <li><i class="ri-check-line text-success me-1"></i> 8 direct referrals</li>
                                                <li><i class="ri-check-line text-success me-1"></i> ₹50,000 team sales</li>
                                            </ul>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">Benefits:</small>
                                            <ul class="list-unstyled mt-2">
                                                <li><i class="ri-gift-line text-warning me-1"></i> 15% commission</li>
                                                <li><i class="ri-gift-line text-warning me-1"></i> ₹3,000 rank bonus</li>
                                            </ul>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-warning">Active: 95</span>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" onclick="editRank(4)">
                                                    <i class="ri-edit-line"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" onclick="deleteRank(4)">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Platinum Rank -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card rank-card">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <span class="rank-badge bg-info text-white">
                                                <i class="ri-vip-crown-line me-1"></i> Platinum
                                            </span>
                                        </div>
                                        <h6 class="fw-semibold mb-2">Level 5</h6>
                                        <p class="text-muted mb-3">Elite achievement</p>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">Requirements:</small>
                                            <ul class="list-unstyled mt-2">
                                                <li><i class="ri-check-line text-success me-1"></i> 12 direct referrals</li>
                                                <li><i class="ri-check-line text-success me-1"></i> ₹1,00,000 team sales</li>
                                            </ul>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">Benefits:</small>
                                            <ul class="list-unstyled mt-2">
                                                <li><i class="ri-gift-line text-warning me-1"></i> 18% commission</li>
                                                <li><i class="ri-gift-line text-warning me-1"></i> ₹7,500 rank bonus</li>
                                            </ul>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-info">Active: 45</span>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" onclick="editRank(5)">
                                                    <i class="ri-edit-line"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" onclick="deleteRank(5)">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Diamond Rank -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card rank-card">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <span class="rank-badge bg-primary text-white">
                                                <i class="ri-gem-line me-1"></i> Diamond
                                            </span>
                                        </div>
                                        <h6 class="fw-semibold mb-2">Level 6</h6>
                                        <p class="text-muted mb-3">Ultimate achievement</p>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">Requirements:</small>
                                            <ul class="list-unstyled mt-2">
                                                <li><i class="ri-check-line text-success me-1"></i> 20 direct referrals</li>
                                                <li><i class="ri-check-line text-success me-1"></i> ₹2,50,000 team sales</li>
                                            </ul>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">Benefits:</small>
                                            <ul class="list-unstyled mt-2">
                                                <li><i class="ri-gift-line text-warning me-1"></i> 25% commission</li>
                                                <li><i class="ri-gift-line text-warning me-1"></i> ₹15,000 rank bonus</li>
                                            </ul>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-primary">Active: 12</span>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" onclick="editRank(6)">
                                                    <i class="ri-edit-line"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" onclick="deleteRank(6)">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Rank Modal -->
    <div class="modal fade" id="addRankModal" tabindex="-1" aria-labelledby="addRankModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRankModalLabel">Add New Rank</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addRankForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rankName" class="form-label">Rank Name</label>
                                <input type="text" class="form-control" id="rankName" name="rank_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="rankLevel" class="form-label">Rank Level</label>
                                <input type="number" class="form-control" id="rankLevel" name="rank_level" min="1" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="directReferrals" class="form-label">Direct Referrals Required</label>
                                <input type="number" class="form-control" id="directReferrals" name="direct_referrals" min="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="teamSales" class="form-label">Team Sales Required (₹)</label>
                                <input type="number" class="form-control" id="teamSales" name="team_sales" min="0" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="commissionRate" class="form-label">Commission Rate (%)</label>
                                <input type="number" class="form-control" id="commissionRate" name="commission_rate" min="0" max="100" step="0.1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="rankBonus" class="form-label">Rank Bonus (₹)</label>
                                <input type="number" class="form-control" id="rankBonus" name="rank_bonus" min="0" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rankColor" class="form-label">Rank Color</label>
                                <select class="form-select" id="rankColor" name="rank_color" required>
                                    <option value="">Select Color</option>
                                    <option value="primary">Blue (Primary)</option>
                                    <option value="secondary">Gray (Secondary)</option>
                                    <option value="success">Green (Success)</option>
                                    <option value="warning">Yellow (Warning)</option>
                                    <option value="danger">Red (Danger)</option>
                                    <option value="info">Light Blue (Info)</option>
                                    <option value="dark">Black (Dark)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="rankIcon" class="form-label">Rank Icon</label>
                                <select class="form-select" id="rankIcon" name="rank_icon" required>
                                    <option value="">Select Icon</option>
                                    <option value="ri-user-line">User</option>
                                    <option value="ri-medal-line">Medal</option>
                                    <option value="ri-medal-2-line">Medal 2</option>
                                    <option value="ri-trophy-line">Trophy</option>
                                    <option value="ri-vip-crown-line">Crown</option>
                                    <option value="ri-gem-line">Diamond</option>
                                    <option value="ri-star-line">Star</option>
                                    <option value="ri-award-line">Award</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="rankDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="rankDescription" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="isActive" name="is_active" checked>
                            <label class="form-check-label" for="isActive">
                                Active Status
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveRank()">Save Rank</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function refreshData() {
    Swal.fire({
        title: 'Refreshing...',
        text: 'Loading latest rank data',
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

function editRank(rankId) {
    Swal.fire({
        title: 'Edit Rank',
        text: `Edit rank with ID: ${rankId}`,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Edit',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Here you would typically open an edit modal or redirect to edit page
            console.log('Edit rank:', rankId);
        }
    });
}

function deleteRank(rankId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Here you would make an AJAX call to delete the rank
            Swal.fire(
                'Deleted!',
                'Rank has been deleted.',
                'success'
            );
        }
    });
}

function saveRank() {
    const form = document.getElementById('addRankForm');
    const formData = new FormData(form);
    
    // Basic validation
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    Swal.fire({
        title: 'Creating Rank...',
        text: 'Please wait while we create the new rank',
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
            text: 'Rank created successfully',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            $('#addRankModal').modal('hide');
            form.reset();
            // Reload page or update UI
            location.reload();
        });
    }, 2000);
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
