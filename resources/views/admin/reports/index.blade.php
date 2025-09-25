@extends('admin.layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Reports & Analytics</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reports</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Key Metrics Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-primary-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">Total Revenue</h6>
                                <h2 class="text-white mb-0">${{ number_format(524780, 2) }}</h2>
                                <p class="text-white-50 mb-0"><i class="bx bx-trending-up"></i> +12.5% vs last month</p>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-dollar text-white fs-18"></i>
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
                                <h6 class="card-title mb-1 text-white">Total Orders</h6>
                                <h2 class="text-white mb-0">{{ number_format(3247) }}</h2>
                                <p class="text-white-50 mb-0"><i class="bx bx-trending-up"></i> +8.3% vs last month</p>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-shopping-bag text-white fs-18"></i>
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
                                <h6 class="card-title mb-1 text-white">Active Vendors</h6>
                                <h2 class="text-white mb-0">{{ number_format(148) }}</h2>
                                <p class="text-white-50 mb-0"><i class="bx bx-trending-up"></i> +5.2% vs last month</p>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-store text-white fs-18"></i>
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
                                <h6 class="card-title mb-1 text-white">Avg. Order Value</h6>
                                <h2 class="text-white mb-0">${{ number_format(161.50, 2) }}</h2>
                                <p class="text-white-50 mb-0"><i class="bx bx-trending-up"></i> +3.7% vs last month</p>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-line-chart text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Categories -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Generate Reports</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Sales Reports -->
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
                                <div class="card border border-primary">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="bx bx-line-chart fs-40 text-primary"></i>
                                        </div>
                                        <h5 class="card-title">Sales Reports</h5>
                                        <p class="card-text text-muted">Revenue, orders, and sales performance analytics</p>
                                        <div class="btn-group-vertical w-100">
                                            <button type="button" class="btn btn-primary btn-sm mb-2" onclick="generateReport('sales-summary')">
                                                <i class="bx bx-download"></i> Sales Summary
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm mb-2" onclick="generateReport('daily-sales')">
                                                <i class="bx bx-calendar"></i> Daily Sales
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="generateReport('monthly-sales')">
                                                <i class="bx bx-calendar-alt"></i> Monthly Sales
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Reports -->
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
                                <div class="card border border-success">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="bx bx-package fs-40 text-success"></i>
                                        </div>
                                        <h5 class="card-title">Product Reports</h5>
                                        <p class="card-text text-muted">Product performance and inventory analytics</p>
                                        <div class="btn-group-vertical w-100">
                                            <button type="button" class="btn btn-success btn-sm mb-2" onclick="generateReport('product-performance')">
                                                <i class="bx bx-trophy"></i> Top Products
                                            </button>
                                            <button type="button" class="btn btn-outline-success btn-sm mb-2" onclick="generateReport('inventory-report')">
                                                <i class="bx bx-box"></i> Inventory Status
                                            </button>
                                            <button type="button" class="btn btn-outline-success btn-sm" onclick="generateReport('low-stock')">
                                                <i class="bx bx-error"></i> Low Stock Alert
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Vendor Reports -->
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
                                <div class="card border border-warning">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="bx bx-store fs-40 text-warning"></i>
                                        </div>
                                        <h5 class="card-title">Vendor Reports</h5>
                                        <p class="card-text text-muted">Vendor performance and commission analytics</p>
                                        <div class="btn-group-vertical w-100">
                                            <button type="button" class="btn btn-warning btn-sm mb-2" onclick="generateReport('vendor-performance')">
                                                <i class="bx bx-medal"></i> Top Vendors
                                            </button>
                                            <button type="button" class="btn btn-outline-warning btn-sm mb-2" onclick="generateReport('vendor-sales')">
                                                <i class="bx bx-dollar"></i> Vendor Sales
                                            </button>
                                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="generateReport('commission-report')">
                                                <i class="bx bx-calculator"></i> Commissions
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Reports -->
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
                                <div class="card border border-info">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="bx bx-user fs-40 text-info"></i>
                                        </div>
                                        <h5 class="card-title">Customer Reports</h5>
                                        <p class="card-text text-muted">Customer behavior and demographics</p>
                                        <div class="btn-group-vertical w-100">
                                            <button type="button" class="btn btn-info btn-sm mb-2" onclick="generateReport('customer-analytics')">
                                                <i class="bx bx-group"></i> Customer Analytics
                                            </button>
                                            <button type="button" class="btn btn-outline-info btn-sm mb-2" onclick="generateReport('customer-lifetime')">
                                                <i class="bx bx-heart"></i> Lifetime Value
                                            </button>
                                            <button type="button" class="btn btn-outline-info btn-sm" onclick="generateReport('acquisition-report')">
                                                <i class="bx bx-user-plus"></i> Acquisition
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Financial Reports -->
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
                                <div class="card border border-danger">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="bx bx-money fs-40 text-danger"></i>
                                        </div>
                                        <h5 class="card-title">Financial Reports</h5>
                                        <p class="card-text text-muted">Revenue, profit, and financial analytics</p>
                                        <div class="btn-group-vertical w-100">
                                            <button type="button" class="btn btn-danger btn-sm mb-2" onclick="generateReport('profit-loss')">
                                                <i class="bx bx-trending-up"></i> P&L Statement
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm mb-2" onclick="generateReport('tax-report')">
                                                <i class="bx bx-receipt"></i> Tax Report
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="generateReport('commission-payout')">
                                                <i class="bx bx-transfer"></i> Payouts
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Marketing Reports -->
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
                                <div class="card border border-secondary">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="bx bx-bullhorn fs-40 text-secondary"></i>
                                        </div>
                                        <h5 class="card-title">Marketing Reports</h5>
                                        <p class="card-text text-muted">Campaign performance and ROI analytics</p>
                                        <div class="btn-group-vertical w-100">
                                            <button type="button" class="btn btn-secondary btn-sm mb-2" onclick="generateReport('campaign-performance')">
                                                <i class="bx bx-target-lock"></i> Campaigns
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm mb-2" onclick="generateReport('coupon-usage')">
                                                <i class="bx bx-gift"></i> Coupon Usage
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="generateReport('roi-analysis')">
                                                <i class="bx bx-chart"></i> ROI Analysis
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

        <!-- Custom Report Builder -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Custom Report Builder</div>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row">
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="reportType" class="form-label">Report Type</label>
                                        <select class="form-select" id="reportType">
                                            <option value="">Select report type</option>
                                            <option value="sales">Sales Report</option>
                                            <option value="inventory">Inventory Report</option>
                                            <option value="vendor">Vendor Report</option>
                                            <option value="customer">Customer Report</option>
                                            <option value="financial">Financial Report</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="dateRange" class="form-label">Date Range</label>
                                        <select class="form-select" id="dateRange">
                                            <option value="today">Today</option>
                                            <option value="yesterday">Yesterday</option>
                                            <option value="last7days">Last 7 Days</option>
                                            <option value="last30days">Last 30 Days</option>
                                            <option value="thismonth">This Month</option>
                                            <option value="lastmonth">Last Month</option>
                                            <option value="custom">Custom Range</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="exportFormat" class="form-label">Export Format</label>
                                        <select class="form-select" id="exportFormat">
                                            <option value="pdf">PDF</option>
                                            <option value="excel">Excel</option>
                                            <option value="csv">CSV</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-grid">
                                            <button type="button" class="btn btn-primary" onclick="generateCustomReport()">
                                                <i class="bx bx-download"></i> Generate Report
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Recent Reports</div>
                        <button type="button" class="btn btn-light btn-sm">
                            <i class="bx bx-refresh"></i> Refresh
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Report Name</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Generated By</th>
                                        <th scope="col">Date Range</th>
                                        <th scope="col">Generated On</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-file-blank me-2 text-primary"></i>
                                                <span class="fw-semibold">Monthly Sales Report</span>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-primary-transparent">Sales</span></td>
                                        <td>Admin User</td>
                                        <td>Jan 1 - Jan 31, 2024</td>
                                        <td>Feb 1, 2024 09:30 AM</td>
                                        <td><span class="badge bg-success-transparent">Completed</span></td>
                                        <td>
                                            <div class="hstack gap-2">
                                                <a href="#" class="text-info fs-14 lh-1" data-bs-toggle="tooltip" title="View">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="#" class="text-primary fs-14 lh-1" data-bs-toggle="tooltip" title="Download">
                                                    <i class="bx bx-download"></i>
                                                </a>
                                                <a href="#" class="text-warning fs-14 lh-1" data-bs-toggle="tooltip" title="Email">
                                                    <i class="bx bx-envelope"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-file-blank me-2 text-success"></i>
                                                <span class="fw-semibold">Inventory Status Report</span>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success-transparent">Inventory</span></td>
                                        <td>Stock Manager</td>
                                        <td>Real-time</td>
                                        <td>Feb 1, 2024 02:15 PM</td>
                                        <td><span class="badge bg-success-transparent">Completed</span></td>
                                        <td>
                                            <div class="hstack gap-2">
                                                <a href="#" class="text-info fs-14 lh-1" data-bs-toggle="tooltip" title="View">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="#" class="text-primary fs-14 lh-1" data-bs-toggle="tooltip" title="Download">
                                                    <i class="bx bx-download"></i>
                                                </a>
                                                <a href="#" class="text-warning fs-14 lh-1" data-bs-toggle="tooltip" title="Email">
                                                    <i class="bx bx-envelope"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-file-blank me-2 text-warning"></i>
                                                <span class="fw-semibold">Vendor Performance Report</span>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-warning-transparent">Vendor</span></td>
                                        <td>Admin User</td>
                                        <td>Last 30 days</td>
                                        <td>Jan 31, 2024 04:45 PM</td>
                                        <td><span class="badge bg-warning-transparent">Processing</span></td>
                                        <td>
                                            <div class="hstack gap-2">
                                                <a href="#" class="text-secondary fs-14 lh-1" data-bs-toggle="tooltip" title="Processing">
                                                    <i class="bx bx-loader-alt bx-spin"></i>
                                                </a>
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

@push('scripts')
<script>
function generateReport(reportType) {
    // Show loading state
    Swal.fire({
        title: 'Generating Report...',
        text: 'Please wait while we generate your report.',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Simulate report generation
    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Report Generated!',
            text: 'Your report is ready for download.',
            showCancelButton: true,
            confirmButtonText: 'Download',
            cancelButtonText: 'Close'
        }).then((result) => {
            if (result.isConfirmed) {
                // Trigger download
                window.open(`{{ route('admin.reports.download') }}/${reportType}`, '_blank');
            }
        });
    }, 2000);
}

function generateCustomReport() {
    const reportType = document.getElementById('reportType').value;
    const dateRange = document.getElementById('dateRange').value;
    const exportFormat = document.getElementById('exportFormat').value;
    
    if (!reportType) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Information',
            text: 'Please select a report type.'
        });
        return;
    }
    
    // Show loading state
    Swal.fire({
        title: 'Generating Custom Report...',
        text: 'Please wait while we generate your custom report.',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Simulate custom report generation
    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Custom Report Generated!',
            text: `Your ${reportType} report (${exportFormat.toUpperCase()}) is ready for download.`,
            showCancelButton: true,
            confirmButtonText: 'Download',
            cancelButtonText: 'Close'
        }).then((result) => {
            if (result.isConfirmed) {
                // Trigger download
                window.open(`{{ route('admin.reports.custom') }}?type=${reportType}&range=${dateRange}&format=${exportFormat}`, '_blank');
            }
        });
    }, 2500);
}

$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Handle custom date range selection
    $('#dateRange').change(function() {
        if ($(this).val() === 'custom') {
            // Show custom date picker modal or inputs
            // This would be implemented based on your date picker preference
        }
    });
});
</script>
@endpush
@endsection
