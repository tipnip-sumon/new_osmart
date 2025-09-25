@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Page Header -->
<div class="d-sm-flex d-block align-items-center justify-content-between page-header-breadcrumb">
    <h4 class="fw-medium mb-0">Dashboard</h4>
    <div class="ms-sm-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Overview</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header Close -->

<!-- Start:: Row-1 -->
<div class="row">
    <!-- Sales Statistics -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Total Sales</span>
                        </div>
                        <h4 class="fw-semibold mb-2">${{ number_format($stats['total_sales'], 2) }}</h4>
                        <div>
                            <span class="text-success me-1"><i class="ri-arrow-up-line align-middle"></i>12.5%</span>
                            <span class="text-muted">vs last month</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-primary-transparent">
                            <i class="bx bx-dollar fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Orders -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Total Orders</span>
                        </div>
                        <h4 class="fw-semibold mb-2">{{ number_format($stats['total_orders']) }}</h4>
                        <div>
                            <span class="text-success me-1"><i class="ri-arrow-up-line align-middle"></i>8.2%</span>
                            <span class="text-muted">vs last month</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-success-transparent">
                            <i class="bx bx-shopping-bag fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Products -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Total Products</span>
                        </div>
                        <h4 class="fw-semibold mb-2">{{ number_format($stats['total_products']) }}</h4>
                        <div>
                            <span class="text-warning me-1"><i class="ri-arrow-up-line align-middle"></i>2.1%</span>
                            <span class="text-muted">vs last month</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-warning-transparent">
                            <i class="bx bx-package fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Customers -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Total Customers</span>
                        </div>
                        <h4 class="fw-semibold mb-2">{{ number_format($stats['total_customers']) }}</h4>
                        <div>
                            <span class="text-success me-1"><i class="ri-arrow-up-line align-middle"></i>15.3%</span>
                            <span class="text-muted">vs last month</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-info-transparent">
                            <i class="bx bx-user fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End:: Row-1 -->

<!-- Start:: Row-1.5 - Invoice Statistics -->
<div class="row">
    <!-- Total Invoices -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card border-start border-primary border-3">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Total Invoices</span>
                        </div>
                        <h4 class="fw-semibold mb-2">{{ number_format(\App\Models\Order::count()) }}</h4>
                        <div>
                            <span class="text-info me-1"><i class="ri-arrow-up-line align-middle"></i>Invoice System</span>
                            <span class="text-muted">Professional PDFs</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-primary-transparent">
                            <i class="bx bx-receipt fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Paid Invoices -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card border-start border-success border-3">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Paid Invoices</span>
                        </div>
                        <h4 class="fw-semibold mb-2">{{ number_format(\App\Models\Order::where('payment_status', 'paid')->count()) }}</h4>
                        <div>
                            <span class="text-success me-1"><i class="ri-check-line align-middle"></i>{{ number_format((\App\Models\Order::where('payment_status', 'paid')->count() / max(1, \App\Models\Order::count())) * 100, 1) }}%</span>
                            <span class="text-muted">payment rate</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-success-transparent">
                            <i class="bx bx-check-circle fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pending Invoices -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card border-start border-warning border-3">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Pending Payments</span>
                        </div>
                        <h4 class="fw-semibold mb-2">{{ number_format(\App\Models\Order::where('payment_status', 'pending')->count()) }}</h4>
                        <div>
                            <span class="text-warning me-1"><i class="ri-time-line align-middle"></i>{{ number_format((\App\Models\Order::where('payment_status', 'pending')->count() / max(1, \App\Models\Order::count())) * 100, 1) }}%</span>
                            <span class="text-muted">awaiting payment</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-warning-transparent">
                            <i class="bx bx-time fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Monthly Revenue -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card border-start border-info border-3">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">This Month Revenue</span>
                        </div>
                        <h4 class="fw-semibold mb-2">Tk {{ number_format(\App\Models\Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total_amount'), 2) }}</h4>
                        <div>
                            <span class="text-info me-1"><i class="ri-calendar-line align-middle"></i>{{ now()->format('M Y') }}</span>
                            <span class="text-muted">invoiced amount</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-info-transparent">
                            <i class="bx bx-money fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End:: Row-1.5 -->

<!-- Start:: Row-2 -->
<div class="row">
    <!-- Quick Actions -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Quick Actions</div>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-receipt me-1"></i>Create Invoice
                    </a>
                    <a href="{{ route('admin.invoices.index') }}?status=pending" class="btn btn-warning btn-sm">
                        <i class="bx bx-time me-1"></i>Pending Payments ({{ \App\Models\Order::where('payment_status', 'pending')->count() }})
                    </a>
                    <a href="{{ route('admin.invoices.analytics') }}" class="btn btn-info btn-sm">
                        <i class="bx bx-bar-chart me-1"></i>Invoice Analytics
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-success btn-sm">
                        <i class="bx bx-plus me-1"></i>Add Product
                    </a>
                    <a href="{{ route('admin.orders.pending') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-shopping-bag me-1"></i>Pending Orders ({{ $stats['pending_orders'] }})
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sales Chart -->
    <div class="col-xxl-9 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">Monthly Sales Overview</div>
                <div class="dropdown">
                    <a href="javascript:void(0);" class="btn btn-outline-light btn-sm text-muted" data-bs-toggle="dropdown" aria-expanded="false">
                        This Year <i class="ri-arrow-down-s-line align-middle ms-1"></i>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a class="dropdown-item" href="javascript:void(0);">This Month</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);">This Year</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);">Last Year</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <canvas id="salesChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- End:: Row-2 -->

<!-- Start:: Row-3 -->
<div class="row">
    <!-- Recent Orders -->
    <div class="col-xxl-8 col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">Recent Orders</div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Vendor</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ $order->id }}</span>
                                </td>
                                <td>{{ $order->customer }}</td>
                                <td>{{ $order->vendor }}</td>
                                <td>${{ number_format($order->amount, 2) }}</td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'shipped' => 'primary',
                                            'delivered' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusClasses[$order->status] ?? 'secondary' }}">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td>{{ $order->date }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-primary-light">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <button class="btn btn-sm btn-secondary-light">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Products -->
    <div class="col-xxl-4 col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">Top Selling Products</div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @foreach($topProducts as $product)
                <div class="d-flex align-items-center mb-3 {{ !$loop->last ? 'pb-3 border-bottom border-block-end-dashed' : '' }}">
                    <div class="me-2">
                        <span class="avatar avatar-md avatar-rounded">
                            <img src="{{ asset('assets/img/products/' . $product->image) }}" alt="{{ $product->name }}" 
                                 onerror="this.src='{{ asset('assets/img/logo.png') }}'">
                        </span>
                    </div>
                    <div class="flex-fill">
                        <p class="mb-0 fw-semibold">{{ Str::limit($product->name, 25) }}</p>
                        <p class="text-muted fs-12 mb-0">by {{ $product->vendor }}</p>
                        <p class="text-success fs-12 mb-0">{{ $product->sales }} sales</p>
                    </div>
                    <div>
                        <span class="text-default fw-semibold">${{ number_format($product->revenue, 0) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- End:: Row-3 -->

<!-- Start:: Row-3.5 - Recent Invoices -->
<div class="row">
    <!-- Recent Invoices -->
    <div class="col-xxl-12 col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    <i class="bx bx-receipt me-2"></i>Recent Invoices
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.invoices.create') }}" class="btn btn-sm btn-success">
                        <i class="bx bx-plus me-1"></i>Create Invoice
                    </a>
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-sm btn-primary">
                        <i class="bx bx-list-ul me-1"></i>View All
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table text-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice #</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Order::with(['customer'])->latest()->take(5)->get() as $invoice)
                            <tr>
                                <td>
                                    <span class="fw-semibold text-primary">INV-{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-sm avatar-rounded me-2">
                                            <i class="bx bx-user"></i>
                                        </span>
                                        <div>
                                            <p class="mb-0 fw-semibold">{{ $invoice->customer->name ?? 'Guest' }}</p>
                                            <p class="mb-0 text-muted fs-12">{{ $invoice->customer->email ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $invoice->created_at->format('M d, Y') }}</span>
                                    <br>
                                    <small class="text-muted">{{ $invoice->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <span class="fw-semibold">Tk {{ number_format($invoice->total_amount, 2) }}</span>
                                </td>
                                <td>
                                    @switch($invoice->payment_status)
                                        @case('paid')
                                            <span class="badge bg-success-transparent">
                                                <i class="bx bx-check me-1"></i>Paid
                                            </span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning-transparent">
                                                <i class="bx bx-time me-1"></i>Pending
                                            </span>
                                            @break
                                        @case('failed')
                                            <span class="badge bg-danger-transparent">
                                                <i class="bx bx-x me-1"></i>Failed
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary-transparent">
                                                {{ ucfirst($invoice->payment_status) }}
                                            </span>
                                    @endswitch
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-primary-light" title="View Invoice">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('admin.invoices.professional', $invoice->id) }}" class="btn btn-success-light" title="Professional PDF" target="_blank">
                                            <i class="bx bx-file-pdf"></i>
                                        </a>
                                        <a href="{{ route('admin.invoices.professional.download', $invoice->id) }}" class="btn btn-info-light" title="Download PDF">
                                            <i class="bx bx-download"></i>
                                        </a>
                                        @if($invoice->payment_status === 'pending')
                                            <button type="button" class="btn btn-warning-light" title="Send Reminder" onclick="sendInvoiceReminder({{ $invoice->id }})">
                                                <i class="bx bx-bell"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bx bx-receipt fs-48 d-block mb-2"></i>
                                        <p class="mb-1">No invoices found</p>
                                        <a href="{{ route('admin.invoices.create') }}" class="btn btn-sm btn-primary">Create First Invoice</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(\App\Models\Order::count() > 5)
                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                    <span class="text-muted">
                        Showing {{ min(5, \App\Models\Order::count()) }} of {{ \App\Models\Order::count() }} invoices
                    </span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.invoices.analytics') }}" class="btn btn-sm btn-outline-info">
                            <i class="bx bx-bar-chart me-1"></i>View Analytics
                        </a>
                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-list-ul me-1"></i>View All Invoices
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- End:: Row-3.5 -->

<!-- Start:: Row-4 -->
<div class="row">
    <!-- Vendor Statistics -->
    <div class="col-xxl-6 col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Vendor Statistics</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 border-end border-inline-end-dashed">
                        <div class="text-center">
                            <h3 class="fw-semibold mb-1 text-primary">{{ $stats['total_vendors'] }}</h3>
                            <p class="text-muted mb-1">Total Vendors</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h3 class="fw-semibold mb-1 text-warning">{{ $stats['new_vendors'] }}</h3>
                            <p class="text-muted mb-1">Pending Approval</p>
                        </div>
                    </div>
                </div>
                <div class="d-grid mt-3">
                    <a href="{{ route('admin.vendors.index') }}" class="btn btn-primary btn-sm">Manage Vendors</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="col-xxl-6 col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Recent Activity</div>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 crm-recent-activity">
                    <li class="crm-recent-activity-content">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-xs bg-primary-transparent avatar-rounded">
                                    <i class="bi bi-circle-fill fs-8"></i>
                                </span>
                            </div>
                            <div class="crm-timeline-content">
                                <span class="fw-semibold">New order received</span> from John Doe
                                <span class="d-block text-muted fs-12">2 minutes ago</span>
                            </div>
                        </div>
                    </li>
                    <li class="crm-recent-activity-content">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-xs bg-secondary-transparent avatar-rounded">
                                    <i class="bi bi-circle-fill fs-8"></i>
                                </span>
                            </div>
                            <div class="crm-timeline-content">
                                <span class="fw-semibold">Product updated</span> by TechStore vendor
                                <span class="d-block text-muted fs-12">15 minutes ago</span>
                            </div>
                        </div>
                    </li>
                    <li class="crm-recent-activity-content">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-xs bg-success-transparent avatar-rounded">
                                    <i class="bi bi-circle-fill fs-8"></i>
                                </span>
                            </div>
                            <div class="crm-timeline-content">
                                <span class="fw-semibold">New vendor registered</span> - FashionHub
                                <span class="d-block text-muted fs-12">1 hour ago</span>
                            </div>
                        </div>
                    </li>
                    <li class="crm-recent-activity-content">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-xs bg-warning-transparent avatar-rounded">
                                    <i class="bi bi-circle-fill fs-8"></i>
                                </span>
                            </div>
                            <div class="crm-timeline-content">
                                <span class="fw-semibold">Low stock alert</span> for Wireless Headphones
                                <span class="d-block text-muted fs-12">3 hours ago</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End:: Row-4 -->
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($monthlySales['labels']),
            datasets: [{
                label: 'Sales ($)',
                data: @json($monthlySales['data']),
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});

// Invoice reminder functionality
function sendInvoiceReminder(invoiceId) {
    if (confirm('Send payment reminder for this invoice?')) {
        // Show loading state
        const button = event.target.closest('button');
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i>';
        button.disabled = true;
        
        fetch(`/admin/invoices/${invoiceId}/reminder`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                toastr.success('Payment reminder sent successfully!');
            } else {
                toastr.error(data.message || 'Failed to send reminder');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Failed to send reminder. Please try again.');
        })
        .finally(() => {
            // Restore button state
            button.innerHTML = originalContent;
            button.disabled = false;
        });
    }
}

// Auto-refresh invoice stats every 30 seconds
setInterval(function() {
    // Refresh invoice counts in Quick Actions
    fetch('/admin/api/invoice-stats', {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.pending_count !== undefined) {
                const pendingLink = document.querySelector('a[href*="status=pending"]');
                if (pendingLink) {
                    pendingLink.innerHTML = pendingLink.innerHTML.replace(/\(\d+\)/, `(${data.pending_count})`);
                }
            }
        })
        .catch(error => {
            console.log('Stats refresh failed:', error);
        });
}, 30000);
</script>

@endpush
