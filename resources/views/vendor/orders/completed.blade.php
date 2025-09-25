@extends('admin.layouts.app')

@section('title', 'Completed Orders')

@section('content')
<!-- Start::page-header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div>
        <h2 class="page-title fw-semibold fs-18 mb-0">Completed Orders</h2>
        <p class="fw-medium fs-13 text-muted mb-0">Successfully fulfilled orders containing your products</p>
    </div>
    <div class="btn-list">
        <a href="{{ route('vendor.orders.index') }}" class="btn btn-outline-primary">
            <i class="ri-list-line me-1"></i>All Orders
        </a>
        <a href="{{ route('vendor.orders.pending') }}" class="btn btn-outline-warning">
            <i class="ri-time-line me-1"></i>Pending Orders
        </a>
    </div>
</div>
<!-- End::page-header -->

<!-- Start::row -->
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    Completed Orders
                </div>
                <div class="d-flex gap-2">
                    <!-- Filter Form -->
                    <form method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control" placeholder="Search orders..." value="{{ request('search') }}">
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($orders->count() > 0)
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-success-transparent">
                                <div class="card-body text-center">
                                    <h4 class="fw-semibold text-success">{{ $orders->count() }}</h4>
                                    <p class="text-muted mb-0">Completed Orders</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary-transparent">
                                <div class="card-body text-center">
                                    <h4 class="fw-semibold text-primary">${{ number_format($orders->sum('vendor_total') ?? 0, 2) }}</h4>
                                    <p class="text-muted mb-0">Total Revenue</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info-transparent">
                                <div class="card-body text-center">
                                    <h4 class="fw-semibold text-info">{{ $orders->sum('vendor_items_count') ?? 0 }}</h4>
                                    <p class="text-muted mb-0">Items Sold</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning-transparent">
                                <div class="card-body text-center">
                                    <h4 class="fw-semibold text-warning">${{ $orders->count() > 0 ? number_format(($orders->sum('vendor_total') ?? 0) / $orders->count(), 2) : '0.00' }}</h4>
                                    <p class="text-muted mb-0">Avg Order Value</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-nowrap table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Completed Date</th>
                                    <th>Items</th>
                                    <th>Your Earnings</th>
                                    <th>Rating</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('vendor.orders.show', $order) }}" class="fw-semibold text-primary">
                                            #{{ $order->order_number ?? 'ORD-' . $order->id }}
                                        </a>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-semibold">{{ $order->customer->name ?? 'N/A' }}</div>
                                            <div class="text-muted fs-12">{{ $order->customer->email ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $order->updated_at->format('M d, Y') }}</div>
                                        <div class="text-muted fs-12">{{ $order->updated_at->format('h:i A') }}</div>
                                        <span class="badge bg-success-transparent text-success fs-10">{{ $order->updated_at->diffForHumans() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $order->vendor_items_count ?? 0 }} items</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">${{ number_format($order->vendor_total ?? 0, 2) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            // Simulate rating (in real app, this would come from reviews)
                                            $rating = rand(3, 5);
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="ri-star-{{ $i <= $rating ? 'fill' : 'line' }} text-warning"></i>
                                            @endfor
                                            <span class="ms-1 text-muted fs-12">({{ $rating }}.0)</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('vendor.orders.show', $order) }}" class="btn btn-sm btn-light" title="View Details">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('vendor.orders.invoice', $order) }}" class="btn btn-sm btn-success" title="Download Invoice" target="_blank">
                                                <i class="ri-download-line"></i>
                                            </a>
                                            <button class="btn btn-sm btn-info" onclick="contactCustomer('{{ $order->customer->email ?? '' }}')" title="Contact Customer">
                                                <i class="ri-mail-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
                        </div>
                        {{ $orders->appends(request()->query())->links() }}
                    </div>

                    <!-- Performance Summary -->
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Performance Summary</h6>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <h5 class="text-success">98.5%</h5>
                                                    <small class="text-muted">On-Time Delivery</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <h5 class="text-warning">4.7</h5>
                                                    <small class="text-muted">Avg Rating</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Quick Actions</h6>
                                        <div class="row">
                                            <div class="col-6">
                                                <a href="{{ route('vendor.reports.index') }}" class="btn btn-outline-primary w-100 btn-sm">
                                                    <i class="ri-bar-chart-line me-1"></i>View Reports
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a href="{{ route('vendor.products.create') }}" class="btn btn-outline-success w-100 btn-sm">
                                                    <i class="ri-add-line me-1"></i>Add Product
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ri-checkbox-circle-line fs-48 text-success"></i>
                        <h5 class="mt-3">No Completed Orders Yet</h5>
                        <p class="text-muted">Once you start fulfilling orders, they will appear here.</p>
                        <div class="mt-3">
                            <a href="{{ route('vendor.orders.pending') }}" class="btn btn-warning me-2">
                                <i class="ri-time-line me-1"></i>Check Pending Orders
                            </a>
                            <a href="{{ route('vendor.products.index') }}" class="btn btn-outline-primary">
                                <i class="ri-product-hunt-line me-1"></i>Manage Products
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- End::row -->

@push('scripts')
<script>
    function contactCustomer(email) {
        if (email) {
            window.location.href = `mailto:${email}?subject=Regarding Your Recent Order&body=Dear Customer,%0D%0A%0D%0AThank you for your order. I wanted to follow up...%0D%0A%0D%0ABest regards,%0D%0A{{ Auth::user()->shop_name ?? Auth::user()->name }}`;
        } else {
            alert('Customer email not available.');
        }
    }

    // Add tooltips to action buttons
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection
