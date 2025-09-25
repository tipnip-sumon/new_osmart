@extends('admin.layouts.app')

@section('title', 'My Orders')

@section('content')
<!-- Start::page-header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div>
        <h2 class="page-title fw-semibold fs-18 mb-0">My Orders</h2>
        <p class="fw-medium fs-13 text-muted mb-0">Manage orders containing your products</p>
    </div>
    <div class="btn-list">
        <a href="{{ route('vendor.orders.pending') }}" class="btn btn-outline-warning">
            <i class="ri-time-line me-1"></i>Pending Orders
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
                    Orders List
                </div>
                <div class="d-flex gap-2">
                    <!-- Filter Form -->
                    <form method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control" placeholder="Search orders..." value="{{ request('search') }}">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
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
                    <div class="table-responsive">
                        <table class="table text-nowrap table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Your Total</th>
                                    <th>Status</th>
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
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $order->vendor_items_count ?? 0 }} items</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">৳{{ number_format($order->vendor_total ?? 0, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $order->status == 'pending' ? 'warning' : 
                                            ($order->status == 'processing' ? 'info' : 
                                            ($order->status == 'shipped' ? 'primary' : 
                                            ($order->status == 'delivered' ? 'success' : 
                                            ($order->status == 'cancelled' ? 'danger' : 'secondary')))) 
                                        }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('vendor.orders.show', $order) }}">View Details</a></li>
                                                @if($order->status != 'cancelled' && $order->status != 'delivered')
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'processing')">Mark as Processing</a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'shipped')">Mark as Shipped</a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'delivered')">Mark as Delivered</a></li>
                                                @endif
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="{{ route('vendor.orders.invoice', $order) }}" target="_blank">Download Invoice</a></li>
                                            </ul>
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
                @else
                    <div class="text-center py-5">
                        <i class="ri-shopping-bag-line fs-48 text-muted"></i>
                        <h5 class="mt-3">No Orders Found</h5>
                        <p class="text-muted">You don't have any orders containing your products yet.</p>
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
        if (confirm('Are you sure you want to update the order status to ' + status + '?')) {
            const form = document.getElementById('status-update-form');
            form.action = '/vendor/orders/' + orderId + '/status';
            document.getElementById('new-status').value = status;
            form.submit();
        }
    }
</script>
@endpush
@endsection
