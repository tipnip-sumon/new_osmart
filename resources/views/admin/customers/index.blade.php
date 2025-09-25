@extends('admin.layouts.app')

@section('title', 'Customer Management')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Customer Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Customers</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-primary-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">Total Customers</h6>
                                <h2 class="text-white mb-0">{{ number_format(1247) }}</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-user text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-secondary-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">Active Customers</h6>
                                <h2 class="text-white mb-0">{{ number_format(1098) }}</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-user-check text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-success-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">This Month</h6>
                                <h2 class="text-white mb-0">{{ number_format(287) }}</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-user-plus text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-warning-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">Blocked</h6>
                                <h2 class="text-white mb-0">{{ number_format(149) }}</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-user-x text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer List -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">All Customers</div>
                        <div class="d-flex">
                            <div class="me-3">
                                <input class="form-control form-control-sm" type="text" placeholder="Search customers..." aria-label="search">
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-filter"></i> Filter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">All Customers</a></li>
                                    <li><a class="dropdown-item" href="#">Active</a></li>
                                    <li><a class="dropdown-item" href="#">Blocked</a></li>
                                    <li><a class="dropdown-item" href="#">Email Verified</a></li>
                                    <li><a class="dropdown-item" href="#">Unverified</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <input class="form-check-input" type="checkbox" value="" aria-label="Select all">
                                        </th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Contact</th>
                                        <th scope="col">Orders</th>
                                        <th scope="col">Total Spent</th>
                                        <th scope="col">Joined Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox" value="" aria-label="Select">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm me-2 avatar-rounded">
                                                    <img src="https://via.placeholder.com/40" alt="Customer">
                                                </span>
                                                <div>
                                                    <div class="lh-1">
                                                        <span class="fw-semibold">John Smith</span>
                                                    </div>
                                                    <span class="lh-1 text-muted fs-11">Customer ID: #CUS001</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="fw-semibold">john.smith@example.com</span>
                                                <div class="text-muted fs-12">+1 234 567 8900</div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-primary-transparent">15 Orders</span></td>
                                        <td>
                                            <span class="fw-semibold text-success">$2,450.00</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">15 Jan 2024</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success-transparent">Active</span>
                                        </td>
                                        <td>
                                            <div class="hstack gap-2 flex-wrap">
                                                <a href="#" class="text-info fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="#" class="text-primary fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <a href="#" class="text-warning fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Block">
                                                    <i class="bx bx-block"></i>
                                                </a>
                                                <a href="#" class="text-danger fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                    <i class="bx bx-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox" value="" aria-label="Select">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm me-2 avatar-rounded">
                                                    <img src="https://via.placeholder.com/40" alt="Customer">
                                                </span>
                                                <div>
                                                    <div class="lh-1">
                                                        <span class="fw-semibold">Sarah Johnson</span>
                                                    </div>
                                                    <span class="lh-1 text-muted fs-11">Customer ID: #CUS002</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="fw-semibold">sarah.j@example.com</span>
                                                <div class="text-muted fs-12">+1 234 567 8901</div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-primary-transparent">8 Orders</span></td>
                                        <td>
                                            <span class="fw-semibold text-success">$1,280.00</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">22 Jan 2024</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success-transparent">Active</span>
                                        </td>
                                        <td>
                                            <div class="hstack gap-2 flex-wrap">
                                                <a href="#" class="text-info fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="#" class="text-primary fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <a href="#" class="text-warning fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Block">
                                                    <i class="bx bx-block"></i>
                                                </a>
                                                <a href="#" class="text-danger fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                    <i class="bx bx-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox" value="" aria-label="Select">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm me-2 avatar-rounded">
                                                    <img src="https://via.placeholder.com/40" alt="Customer">
                                                </span>
                                                <div>
                                                    <div class="lh-1">
                                                        <span class="fw-semibold">Mike Wilson</span>
                                                    </div>
                                                    <span class="lh-1 text-muted fs-11">Customer ID: #CUS003</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="fw-semibold">mike.w@example.com</span>
                                                <div class="text-muted fs-12">+1 234 567 8902</div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-primary-transparent">3 Orders</span></td>
                                        <td>
                                            <span class="fw-semibold text-success">$750.00</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">05 Feb 2024</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning-transparent">Blocked</span>
                                        </td>
                                        <td>
                                            <div class="hstack gap-2 flex-wrap">
                                                <a href="#" class="text-info fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="#" class="text-primary fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <a href="#" class="text-success fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Unblock">
                                                    <i class="bx bx-check-circle"></i>
                                                </a>
                                                <a href="#" class="text-danger fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                    <i class="bx bx-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                Showing <strong>1</strong> to <strong>3</strong> of <strong>1247</strong> entries
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
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

        <!-- Bulk Actions Bar (Hidden by default) -->
        <div class="row" id="bulk-actions" style="display: none;">
            <div class="col-12">
                <div class="card custom-card bg-light">
                    <div class="card-body py-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="me-3">Selected: <strong id="selected-count">0</strong> customers</span>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-success btn-sm">
                                    <i class="bx bx-check"></i> Activate
                                </button>
                                <button type="button" class="btn btn-warning btn-sm">
                                    <i class="bx bx-block"></i> Block
                                </button>
                                <button type="button" class="btn btn-info btn-sm">
                                    <i class="bx bx-envelope"></i> Send Email
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm">
                                    <i class="bx bx-download"></i> Export
                                </button>
                                <button type="button" class="btn btn-danger btn-sm">
                                    <i class="bx bx-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
$(document).ready(function() {
    // Handle select all checkbox
    $('#select-all').change(function() {
        $('.customer-checkbox').prop('checked', this.checked);
        updateBulkActions();
    });
    
    // Handle individual checkboxes
    $('.customer-checkbox').change(function() {
        updateBulkActions();
    });
    
    function updateBulkActions() {
        const checkedBoxes = $('.customer-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            $('#bulk-actions').show();
            $('#selected-count').text(count);
        } else {
            $('#bulk-actions').hide();
        }
        
        // Update select all checkbox state
        const totalBoxes = $('.customer-checkbox').length;
        const selectAllCheckbox = $('#select-all');
        
        if (count === 0) {
            selectAllCheckbox.prop('indeterminate', false);
            selectAllCheckbox.prop('checked', false);
        } else if (count === totalBoxes) {
            selectAllCheckbox.prop('indeterminate', false);
            selectAllCheckbox.prop('checked', true);
        } else {
            selectAllCheckbox.prop('indeterminate', true);
        }
    }
});
</script>
@endpush
@endsection
