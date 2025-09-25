@extends('admin.layouts.app')

@section('title', 'Pending Orders')

@section('content')
<!-- Start::page-header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div>
        <h2 class="page-title fw-semibold fs-18 mb-0">Pending Orders</h2>
        <p class="fw-medium fs-13 text-muted mb-0">Orders containing your products awaiting processing</p>
    </div>
    <div class="btn-list">
        <a href="{{ route('vendor.orders.index') }}" class="btn btn-outline-primary">
            <i class="ri-list-line me-1"></i>All Orders
        </a>
        <a href="{{ route('vendor.orders.completed') }}" class="btn btn-outline-success">
            <i class="ri-check-line me-1"></i>Completed Orders
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
                    Pending Orders
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
                            <div class="card bg-warning-transparent">
                                <div class="card-body text-center">
                                    <h4 class="fw-semibold text-warning">{{ $orders->count() }}</h4>
                                    <p class="text-muted mb-0">Pending Orders</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info-transparent">
                                <div class="card-body text-center">
                                    <h4 class="fw-semibold text-info">${{ number_format($orders->sum('vendor_total') ?? 0, 2) }}</h4>
                                    <p class="text-muted mb-0">Total Value</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary-transparent">
                                <div class="card-body text-center">
                                    <h4 class="fw-semibold text-primary">{{ $orders->sum('vendor_items_count') ?? 0 }}</h4>
                                    <p class="text-muted mb-0">Total Items</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-secondary-transparent">
                                <div class="card-body text-center">
                                    <h4 class="fw-semibold text-secondary">${{ $orders->count() > 0 ? number_format(($orders->sum('vendor_total') ?? 0) / $orders->count(), 2) : '0.00' }}</h4>
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
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Your Total</th>
                                    <th>Priority</th>
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
                                        <div>{{ $order->created_at->format('M d, Y') }}</div>
                                        <div class="text-muted fs-12">{{ $order->created_at->format('h:i A') }}</div>
                                        @if($order->created_at->diffInHours() > 24)
                                            <span class="badge bg-danger-transparent text-danger fs-10">{{ $order->created_at->diffForHumans() }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $order->vendor_items_count ?? 0 }} items</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">${{ number_format($order->vendor_total ?? 0, 2) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $hoursOld = $order->created_at->diffInHours();
                                            $priority = $hoursOld > 48 ? 'high' : ($hoursOld > 24 ? 'medium' : 'normal');
                                        @endphp
                                        <span class="badge bg-{{ 
                                            $priority == 'high' ? 'danger' : 
                                            ($priority == 'medium' ? 'warning' : 'success') 
                                        }}">
                                            {{ ucfirst($priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('vendor.orders.show', $order) }}" class="btn btn-sm btn-light" title="View Details">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <button class="btn btn-sm btn-primary" onclick="updateOrderStatus({{ $order->id }}, 'processing')" title="Start Processing">
                                                <i class="ri-play-line"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" onclick="updateOrderStatus({{ $order->id }}, 'shipped')" title="Mark as Shipped">
                                                <i class="ri-truck-line"></i>
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

                    <!-- Quick Actions -->
                    <div class="mt-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Quick Actions</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <button class="btn btn-outline-primary w-100" onclick="markAllAsProcessing()">
                                            <i class="ri-play-line me-1"></i>Mark All as Processing
                                        </button>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="{{ route('vendor.orders.index') }}?status=processing" class="btn btn-outline-info w-100">
                                            <i class="ri-list-line me-1"></i>View Processing Orders
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="{{ route('vendor.reports.index') }}" class="btn btn-outline-success w-100">
                                            <i class="ri-bar-chart-line me-1"></i>View Sales Reports
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ri-shopping-bag-line fs-48 text-warning"></i>
                        <h5 class="mt-3">No Pending Orders</h5>
                        <p class="text-muted">Great! You don't have any pending orders at the moment.</p>
                        <div class="mt-3">
                            <a href="{{ route('vendor.orders.index') }}" class="btn btn-primary me-2">
                                <i class="ri-list-line me-1"></i>View All Orders
                            </a>
                            <a href="{{ route('vendor.products.create') }}" class="btn btn-outline-primary">
                                <i class="ri-add-line me-1"></i>Add New Product
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- End::row -->

<!-- Status Update Form (Hidden) -->
<form id="status-update-form" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="status" id="new-status">
</form>

@push('scripts')
<script>
    function updateOrderStatus(orderId, status) {
        const statusText = status.charAt(0).toUpperCase() + status.slice(1);
        if (confirm(`Are you sure you want to mark this order as ${statusText}?`)) {
            const form = document.getElementById('status-update-form');
            form.action = '/vendor/orders/' + orderId + '/status';
            document.getElementById('new-status').value = status;
            form.submit();
        }
    }

    function markAllAsProcessing() {
        if (confirm('Are you sure you want to mark ALL pending orders as processing?')) {
            // Get all order IDs from the table
            const orderIds = [];
            document.querySelectorAll('table tbody tr').forEach(row => {
                const orderLink = row.querySelector('a[href*="/vendor/orders/"]');
                if (orderLink) {
                    const orderNumber = orderLink.getAttribute('href').split('/').pop();
                    orderIds.push(orderNumber);
                }
            });

            // Update each order (in a real implementation, you'd want a bulk update endpoint)
            if (orderIds.length > 0) {
                alert(`This would update ${orderIds.length} orders. In a full implementation, this would be a bulk operation.`);
                // For now, just refresh the page
                // location.reload();
            }
        }
    }

    // Auto-refresh every 5 minutes to check for new orders
    setTimeout(function() {
        location.reload();
    }, 300000); // 5 minutes
</script>
@endpush
@endsection
