@extends('admin.layouts.app')

@section('title', 'Marketing Management')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Marketing Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Marketing</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Marketing Overview Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-primary-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">Active Campaigns</h6>
                                <h2 class="text-white mb-0">{{ number_format(12) }}</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-bullhorn text-white fs-18"></i>
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
                                <h6 class="card-title mb-1 text-white">Total Banners</h6>
                                <h2 class="text-white mb-0">{{ number_format(25) }}</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-image text-white fs-18"></i>
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
                                <h6 class="card-title mb-1 text-white">Newsletter Subscribers</h6>
                                <h2 class="text-white mb-0">{{ number_format(8437) }}</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-envelope text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-info-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">Conversion Rate</h6>
                                <h2 class="text-white mb-0">{{ number_format(3.24, 2) }}%</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-trending-up text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Marketing Tools Tabs -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Marketing Tools</div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-justified" id="marketingTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="banners-tab" data-bs-toggle="tab" data-bs-target="#banners" type="button" role="tab">
                                    <i class="bx bx-image me-2"></i>Banners
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="promotions-tab" data-bs-toggle="tab" data-bs-target="#promotions" type="button" role="tab">
                                    <i class="bx bx-gift me-2"></i>Promotions
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="newsletters-tab" data-bs-toggle="tab" data-bs-target="#newsletters" type="button" role="tab">
                                    <i class="bx bx-envelope me-2"></i>Newsletters
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">
                                    <i class="bx bx-search me-2"></i>SEO
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content mt-3" id="marketingTabsContent">
                            <!-- Banners Tab -->
                            <div class="tab-pane fade show active" id="banners" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Banner Management</h5>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBannerModal">
                                        <i class="bx bx-plus"></i> Add Banner
                                    </button>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table text-nowrap table-hover">
                                        <thead>
                                            <tr>
                                                <th>Banner</th>
                                                <th>Title</th>
                                                <th>Position</th>
                                                <th>Status</th>
                                                <th>Views</th>
                                                <th>Clicks</th>
                                                <th>CTR</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <img src="https://via.placeholder.com/80x40" alt="Banner" class="rounded">
                                                </td>
                                                <td>
                                                    <span class="fw-semibold">Summer Sale 2024</span>
                                                    <div class="text-muted fs-12">50% Off Electronics</div>
                                                </td>
                                                <td><span class="badge bg-primary-transparent">Homepage Hero</span></td>
                                                <td><span class="badge bg-success-transparent">Active</span></td>
                                                <td>15,847</td>
                                                <td>1,247</td>
                                                <td>7.87%</td>
                                                <td>
                                                    <div class="hstack gap-2">
                                                        <a href="#" class="text-info fs-14 lh-1" data-bs-toggle="tooltip" title="View">
                                                            <i class="bx bx-show"></i>
                                                        </a>
                                                        <a href="#" class="text-primary fs-14 lh-1" data-bs-toggle="tooltip" title="Edit">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                        <a href="#" class="text-danger fs-14 lh-1" data-bs-toggle="tooltip" title="Delete">
                                                            <i class="bx bx-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <img src="https://via.placeholder.com/80x40" alt="Banner" class="rounded">
                                                </td>
                                                <td>
                                                    <span class="fw-semibold">New Arrivals</span>
                                                    <div class="text-muted fs-12">Latest Fashion Trends</div>
                                                </td>
                                                <td><span class="badge bg-info-transparent">Sidebar</span></td>
                                                <td><span class="badge bg-success-transparent">Active</span></td>
                                                <td>8,235</td>
                                                <td>429</td>
                                                <td>5.21%</td>
                                                <td>
                                                    <div class="hstack gap-2">
                                                        <a href="#" class="text-info fs-14 lh-1" data-bs-toggle="tooltip" title="View">
                                                            <i class="bx bx-show"></i>
                                                        </a>
                                                        <a href="#" class="text-primary fs-14 lh-1" data-bs-toggle="tooltip" title="Edit">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                        <a href="#" class="text-danger fs-14 lh-1" data-bs-toggle="tooltip" title="Delete">
                                                            <i class="bx bx-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Promotions Tab -->
                            <div class="tab-pane fade" id="promotions" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Promotional Campaigns</h5>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPromotionModal">
                                        <i class="bx bx-plus"></i> Create Campaign
                                    </button>
                                </div>
                                
                                <div class="row">
                                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                        <div class="card border border-success">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <span class="badge bg-success-transparent">Active</span>
                                                    <div class="dropdown">
                                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="#">View Details</a></li>
                                                            <li><a class="dropdown-item" href="#">Edit Campaign</a></li>
                                                            <li><a class="dropdown-item" href="#">Duplicate</a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item text-danger" href="#">Stop Campaign</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <h6 class="card-title">Flash Sale Weekend</h6>
                                                <p class="text-muted fs-12 mb-2">24-hour flash sale on selected items</p>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="text-center">
                                                            <div class="fw-semibold text-success">$12,450</div>
                                                            <div class="text-muted fs-11">Revenue</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-center">
                                                            <div class="fw-semibold text-primary">247</div>
                                                            <div class="text-muted fs-11">Orders</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                        <div class="card border border-warning">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <span class="badge bg-warning-transparent">Scheduled</span>
                                                    <div class="dropdown">
                                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="#">View Details</a></li>
                                                            <li><a class="dropdown-item" href="#">Edit Campaign</a></li>
                                                            <li><a class="dropdown-item" href="#">Duplicate</a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item text-danger" href="#">Cancel Campaign</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <h6 class="card-title">Valentine's Day Special</h6>
                                                <p class="text-muted fs-12 mb-2">Romantic gifts and jewelry promotion</p>
                                                <div class="text-center">
                                                    <div class="fw-semibold text-warning">Starts in 5 days</div>
                                                    <div class="text-muted fs-11">Feb 10, 2024</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                        <div class="card border border-secondary">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <span class="badge bg-secondary-transparent">Ended</span>
                                                    <div class="dropdown">
                                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="#">View Report</a></li>
                                                            <li><a class="dropdown-item" href="#">Duplicate</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <h6 class="card-title">New Year Clearance</h6>
                                                <p class="text-muted fs-12 mb-2">Year-end inventory clearance sale</p>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="text-center">
                                                            <div class="fw-semibold text-success">$45,230</div>
                                                            <div class="text-muted fs-11">Total Revenue</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-center">
                                                            <div class="fw-semibold text-primary">892</div>
                                                            <div class="text-muted fs-11">Total Orders</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Newsletters Tab -->
                            <div class="tab-pane fade" id="newsletters" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Newsletter Management</h5>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#createNewsletterModal">
                                        <i class="bx bx-plus"></i> Create Newsletter
                                    </button>
                                </div>
                                
                                <div class="row">
                                    <div class="col-xl-8">
                                        <div class="table-responsive">
                                            <table class="table text-nowrap table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Subject</th>
                                                        <th>Recipients</th>
                                                        <th>Sent Date</th>
                                                        <th>Open Rate</th>
                                                        <th>Click Rate</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-semibold">Weekly Deals & Offers</span>
                                                            <div class="text-muted fs-12">Latest products and promotions</div>
                                                        </td>
                                                        <td>8,437</td>
                                                        <td>Feb 1, 2024</td>
                                                        <td><span class="text-success fw-semibold">24.5%</span></td>
                                                        <td><span class="text-primary fw-semibold">3.2%</span></td>
                                                        <td><span class="badge bg-success-transparent">Sent</span></td>
                                                        <td>
                                                            <div class="hstack gap-2">
                                                                <a href="#" class="text-info fs-14 lh-1" data-bs-toggle="tooltip" title="View">
                                                                    <i class="bx bx-show"></i>
                                                                </a>
                                                                <a href="#" class="text-primary fs-14 lh-1" data-bs-toggle="tooltip" title="Analytics">
                                                                    <i class="bx bx-bar-chart"></i>
                                                                </a>
                                                                <a href="#" class="text-warning fs-14 lh-1" data-bs-toggle="tooltip" title="Duplicate">
                                                                    <i class="bx bx-copy"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-semibold">Product Launch Announcement</span>
                                                            <div class="text-muted fs-12">New arrivals this month</div>
                                                        </td>
                                                        <td>7,891</td>
                                                        <td>Jan 28, 2024</td>
                                                        <td><span class="text-success fw-semibold">31.8%</span></td>
                                                        <td><span class="text-primary fw-semibold">5.7%</span></td>
                                                        <td><span class="badge bg-success-transparent">Sent</span></td>
                                                        <td>
                                                            <div class="hstack gap-2">
                                                                <a href="#" class="text-info fs-14 lh-1" data-bs-toggle="tooltip" title="View">
                                                                    <i class="bx bx-show"></i>
                                                                </a>
                                                                <a href="#" class="text-primary fs-14 lh-1" data-bs-toggle="tooltip" title="Analytics">
                                                                    <i class="bx bx-bar-chart"></i>
                                                                </a>
                                                                <a href="#" class="text-warning fs-14 lh-1" data-bs-toggle="tooltip" title="Duplicate">
                                                                    <i class="bx bx-copy"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">Subscriber Analytics</div>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-center mb-3">
                                                    <h3 class="text-primary">8,437</h3>
                                                    <span class="text-muted">Total Subscribers</span>
                                                </div>
                                                <div class="row text-center">
                                                    <div class="col-6">
                                                        <div class="border-end">
                                                            <div class="fw-semibold text-success">+127</div>
                                                            <div class="text-muted fs-12">This Week</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="fw-semibold text-danger">-23</div>
                                                        <div class="text-muted fs-12">Unsubscribed</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- SEO Tab -->
                            <div class="tab-pane fade" id="seo" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">SEO Management</h5>
                                    <button type="button" class="btn btn-info">
                                        <i class="bx bx-refresh"></i> Refresh Data
                                    </button>
                                </div>
                                
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">SEO Performance</div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row text-center">
                                                    <div class="col-4">
                                                        <div class="mb-3">
                                                            <h4 class="text-primary">4.2</h4>
                                                            <span class="text-muted fs-12">SEO Score</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="mb-3 border-start border-end">
                                                            <h4 class="text-success">15,247</h4>
                                                            <span class="text-muted fs-12">Organic Traffic</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="mb-3">
                                                            <h4 class="text-warning">847</h4>
                                                            <span class="text-muted fs-12">Keywords</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">Meta Tags Status</div>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span>Pages with Meta Titles</span>
                                                    <span class="text-success fw-semibold">98%</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span>Pages with Meta Descriptions</span>
                                                    <span class="text-warning fw-semibold">85%</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Pages with Alt Text</span>
                                                    <span class="text-danger fw-semibold">72%</span>
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
    </div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Tab switching functionality
    $('#marketingTabs button').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
});
</script>
@endpush
@endsection
