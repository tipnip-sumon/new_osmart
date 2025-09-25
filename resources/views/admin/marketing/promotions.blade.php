@extends('admin.layouts.app')

@section('title', 'Promotions & Discounts')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Promotions & Discounts</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.marketing.index') }}">Marketing</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Promotions</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Promotion Stats -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-primary">
                                    <i class="bx bx-gift fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Active Promotions</p>
                                        <h4 class="fw-semibold mt-1">12</h4>
                                    </div>
                                    <div class="text-success fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>8.2%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-success">
                                    <i class="bx bx-dollar fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Revenue Generated</p>
                                        <h4 class="fw-semibold mt-1">$45,230</h4>
                                    </div>
                                    <div class="text-success fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>12.5%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-warning">
                                    <i class="bx bx-user-check fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Users</p>
                                        <h4 class="fw-semibold mt-1">2,847</h4>
                                    </div>
                                    <div class="text-warning fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>5.1%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-info">
                                    <i class="bx bx-percentage fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Conversion Rate</p>
                                        <h4 class="fw-semibold mt-1">18.5%</h4>
                                    </div>
                                    <div class="text-info fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>3.2%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promotion Management -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Promotion Management</div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPromotionModal">
                                <i class="bx bx-plus"></i> Add Promotion
                            </button>
                            <button class="btn btn-success btn-sm">
                                <i class="bx bx-download"></i> Export
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Promotion</th>
                                        <th>Type</th>
                                        <th>Discount</th>
                                        <th>Status</th>
                                        <th>Used</th>
                                        <th>Limit</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Sample Promotion Data -->
                                    <tr>
                                        <td><input type="checkbox" class="promotion-checkbox" value="1"></td>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">Summer Sale 2024</h6>
                                                <small class="text-muted">SUMMER2024</small>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-primary-transparent">Percentage</span></td>
                                        <td>25%</td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td>847</td>
                                        <td>1000</td>
                                        <td>June 1, 2024</td>
                                        <td>Aug 31, 2024</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-primary-light" onclick="viewPromotion(1)">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success-light" onclick="editPromotion(1)">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger-light" onclick="deletePromotion(1)">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="promotion-checkbox" value="2"></td>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">New Customer Discount</h6>
                                                <small class="text-muted">WELCOME10</small>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-info-transparent">Fixed Amount</span></td>
                                        <td>$10</td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td>234</td>
                                        <td>∞</td>
                                        <td>Jan 1, 2024</td>
                                        <td>Dec 31, 2024</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-primary-light" onclick="viewPromotion(2)">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success-light" onclick="editPromotion(2)">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger-light" onclick="deletePromotion(2)">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Add Promotion Modal -->
<div class="modal fade" id="addPromotionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Promotion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addPromotionForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Promotion Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Promotion Code</label>
                                <input type="text" class="form-control" name="code" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Discount Type</label>
                                <select class="form-control" name="type">
                                    <option value="percentage">Percentage</option>
                                    <option value="fixed">Fixed Amount</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Discount Value</label>
                                <input type="number" class="form-control" name="value" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Usage Limit</label>
                                <input type="number" class="form-control" name="limit" placeholder="Leave empty for unlimited">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Minimum Order Amount</label>
                                <input type="number" class="form-control" name="min_amount" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="datetime-local" class="form-control" name="start_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <input type="datetime-local" class="form-control" name="end_date">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="savePromotion()">Create Promotion</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewPromotion(id) {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Info', `View promotion ${id} - Feature coming soon`, 'info');
    } else {
        alert(`View promotion ${id} - Feature coming soon`);
    }
}

function editPromotion(id) {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Info', `Edit promotion ${id} - Feature coming soon`, 'info');
    } else {
        alert(`Edit promotion ${id} - Feature coming soon`);
    }
}

function deletePromotion(id) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete Promotion',
            text: 'Are you sure you want to delete this promotion?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Deleted!', 'Promotion has been deleted.', 'success');
            }
        });
    } else {
        if (confirm('Delete this promotion?')) {
            alert('Promotion deleted successfully!');
        }
    }
}

function savePromotion() {
    const form = document.getElementById('addPromotionForm');
    const formData = new FormData(form);
    
    if (!formData.get('name') || !formData.get('code')) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('Error', 'Please fill in all required fields', 'error');
        } else {
            alert('Please fill in all required fields');
        }
        return;
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire('Success!', 'Promotion has been created successfully.', 'success').then(() => {
            $('#addPromotionModal').modal('hide');
            form.reset();
        });
    } else {
        alert('Promotion has been created successfully.');
        $('#addPromotionModal').modal('hide');
        form.reset();
    }
}
</script>
@endsection
