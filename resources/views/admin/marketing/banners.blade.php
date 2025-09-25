@extends('admin.layouts.app')

@section('title', 'Banner Management')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Banner Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.marketing.index') }}">Marketing</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Banners</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Banner Stats -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-primary">
                                    <i class="bx bx-images fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Banners</p>
                                        <h4 class="fw-semibold mt-1">24</h4>
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
                                    <i class="bx bx-check-circle fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Active Banners</p>
                                        <h4 class="fw-semibold mt-1">18</h4>
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
                                    <i class="bx bx-mouse fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Clicks</p>
                                        <h4 class="fw-semibold mt-1">12,456</h4>
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
                                    <i class="bx bx-target-lock fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">CTR Rate</p>
                                        <h4 class="fw-semibold mt-1">3.42%</h4>
                                    </div>
                                    <div class="text-info fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>2.3%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banner Management -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            Banner Management
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.marketing.banners.create') }}" class="btn btn-primary btn-sm">
                                <i class="bx bx-plus"></i> Add Banner
                            </a>
                            <button class="btn btn-success btn-sm" onclick="exportBanners()">
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
                                        <th>Banner</th>
                                        <th>Position</th>
                                        <th>Status</th>
                                        <th>Clicks</th>
                                        <th>Impressions</th>
                                        <th>CTR</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Sample Banner Data -->
                                    <tr>
                                        <td><input type="checkbox" class="banner-checkbox" value="1"></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/80x40" alt="Banner" class="me-3 rounded">
                                                <div>
                                                    <h6 class="mb-0">Summer Sale 2024</h6>
                                                    <small class="text-muted">Get 50% off on all items</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-primary-transparent">Homepage Hero</span></td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td>2,847</td>
                                        <td>89,234</td>
                                        <td>3.19%</td>
                                        <td>June 1, 2024</td>
                                        <td>Aug 31, 2024</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-primary-light" onclick="viewBanner(1)">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success-light" onclick="editBanner(1)">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning-light" onclick="toggleBanner(1)">
                                                    <i class="bx bx-toggle-right"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger-light" onclick="deleteBanner(1)">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="banner-checkbox" value="2"></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/80x40" alt="Banner" class="me-3 rounded">
                                                <div>
                                                    <h6 class="mb-0">New Arrivals</h6>
                                                    <small class="text-muted">Check out our latest products</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-info-transparent">Sidebar</span></td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td>1,234</td>
                                        <td>45,123</td>
                                        <td>2.74%</td>
                                        <td>July 1, 2024</td>
                                        <td>Dec 31, 2024</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-primary-light" onclick="viewBanner(2)">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success-light" onclick="editBanner(2)">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning-light" onclick="toggleBanner(2)">
                                                    <i class="bx bx-toggle-right"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger-light" onclick="deleteBanner(2)">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="banner-checkbox" value="3"></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/80x40" alt="Banner" class="me-3 rounded">
                                                <div>
                                                    <h6 class="mb-0">Black Friday Deal</h6>
                                                    <small class="text-muted">Up to 70% off everything</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-warning-transparent">Footer</span></td>
                                        <td><span class="badge bg-secondary">Scheduled</span></td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>0%</td>
                                        <td>Nov 24, 2024</td>
                                        <td>Nov 30, 2024</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-primary-light" onclick="viewBanner(3)">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success-light" onclick="editBanner(3)">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning-light" onclick="toggleBanner(3)">
                                                    <i class="bx bx-toggle-right"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger-light" onclick="deleteBanner(3)">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="text-muted">Showing 1 to 3 of 24 banners</span>
                            </div>
                            <nav>
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            document.querySelectorAll('.banner-checkbox').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // Individual checkbox change handler
    document.querySelectorAll('.banner-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allCheckboxes = document.querySelectorAll('.banner-checkbox');
            const checkedCheckboxes = document.querySelectorAll('.banner-checkbox:checked');
            
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length;
                selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;
            }
        });
    });
});

function viewBanner(id) {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Info', `View banner ${id} - Feature coming soon`, 'info');
    } else {
        alert(`View banner ${id} - Feature coming soon`);
    }
}

function editBanner(id) {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Info', `Edit banner ${id} - Feature coming soon`, 'info');
    } else {
        alert(`Edit banner ${id} - Feature coming soon`);
    }
}

function toggleBanner(id) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Toggle Banner Status',
            text: 'Are you sure you want to toggle this banner status?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Toggle'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Success!', 'Banner status has been toggled.', 'success');
            }
        });
    } else {
        if (confirm('Toggle banner status?')) {
            alert('Banner status toggled successfully!');
        }
    }
}

function deleteBanner(id) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete Banner',
            text: 'Are you sure you want to delete this banner? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Deleted!', 'Banner has been deleted.', 'success');
            }
        });
    } else {
        if (confirm('Delete this banner? This action cannot be undone.')) {
            alert('Banner deleted successfully!');
        }
    }
}

function exportBanners() {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Exporting...', 'Preparing banner data for export.', 'info');
        
        setTimeout(() => {
            Swal.fire('Export Complete!', 'Banner data has been exported successfully.', 'success');
        }, 2000);
    } else {
        alert('Exporting banner data...');
    }
}
</script>
@endsection
