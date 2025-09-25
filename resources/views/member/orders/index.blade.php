@extends('member.layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">My Orders</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Orders</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Order Statistics -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-md bg-primary-transparent">
                                <i class="fe fe-shopping-cart fs-18"></i>
                            </div>
                            <div class="ms-3 flex-fill">
                                <h6 class="fw-semibold mb-0">{{ $orders->total() }}</h6>
                                <span class="text-muted">Total Orders</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-md bg-success-transparent">
                                <i class="fe fe-check-circle fs-18"></i>
                            </div>
                            <div class="ms-3 flex-fill">
                                <h6 class="fw-semibold mb-0">{{ $completedOrders ?? 0 }}</h6>
                                <span class="text-muted">Completed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-md bg-warning-transparent">
                                <i class="fe fe-clock fs-18"></i>
                            </div>
                            <div class="ms-3 flex-fill">
                                <h6 class="fw-semibold mb-0">{{ $pendingOrders ?? 0 }}</h6>
                                <span class="text-muted">Pending</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-md bg-info-transparent">
                                <i class="fe fe-dollar-sign fs-18"></i>
                            </div>
                            <div class="ms-3 flex-fill">
                                <h6 class="fw-semibold mb-0">৳{{ number_format($totalOrderValue ?? 0, 2) }}</h6>
                                <span class="text-muted">Total Value</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Orders List -->
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="fe fe-shopping-cart me-2"></i>Order History
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('member.orders.create') }}" class="btn btn-primary btn-sm">
                                <i class="fe fe-plus me-1"></i>Create Order
                            </a>
                            <select class="form-select form-select-sm" id="statusFilter" style="width: auto;">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <button class="btn btn-outline-primary btn-sm" onclick="exportOrders()">
                                <i class="fe fe-download me-1"></i>Export
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($orders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Date</th>
                                            <th>Products</th>
                                            <th>Amount</th>
                                            <th>Payment</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                        <tr>
                                            <td>
                                                <span class="fw-semibold">#{{ $order->order_number }}</span>
                                            </td>
                                            <td>
                                                <span>{{ $order->created_at->format('M d, Y') }}</span>
                                                <br>
                                                <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <div class="order-products">
                                                    @if($order->items && $order->items->count() > 0)
                                                        @foreach($order->items->take(2) as $item)
                                                            <span class="badge bg-light text-dark me-1">{{ $item->product->name ?? 'Product' }}</span>
                                                        @endforeach
                                                        @if($order->items->count() > 2)
                                                            <span class="badge bg-secondary">+{{ $order->items->count() - 2 }} more</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">No items</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">৳{{ number_format($order->total_amount, 2) }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $paymentColors = [
                                                        'paid' => 'success',
                                                        'pending' => 'warning',
                                                        'failed' => 'danger',
                                                        'refunded' => 'info'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'secondary' }}-transparent">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ ucfirst($order->payment_method) }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'processing' => 'info',
                                                        'shipped' => 'primary',
                                                        'delivered' => 'success',
                                                        'completed' => 'success',
                                                        'cancelled' => 'danger'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}-transparent">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('member.orders.show', $order) }}" class="btn btn-light" title="View Details">
                                                        <i class="fe fe-eye"></i>
                                                    </a>
                                                    @if(in_array($order->status, ['pending', 'processing']))
                                                    <button class="btn btn-warning" onclick="cancelOrder({{ $order->id }})" title="Cancel Order">
                                                        <i class="fe fe-x"></i>
                                                    </button>
                                                    @endif
                                                    @if(in_array($order->status, ['shipped', 'delivered']))
                                                    <button class="btn btn-info" onclick="trackOrder({{ $order->id }})" title="Track Order">
                                                        <i class="fe fe-map-pin"></i>
                                                    </button>
                                                    @endif
                                                    <button class="btn btn-success" onclick="downloadInvoice({{ $order->id }})" title="Download Invoice">
                                                        <i class="fe fe-download"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $orders->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="avatar avatar-xl avatar-rounded bg-light mb-3">
                                    <i class="fe fe-shopping-cart fs-24 text-muted"></i>
                                </div>
                                <h6 class="fw-semibold mb-1">No Orders Found</h6>
                                <p class="text-muted mb-3">You haven't placed any orders yet</p>
                                <a href="{{ route('member.orders.create') }}" class="btn btn-primary">
                                    <i class="fe fe-plus me-1"></i>Create Your First Order
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4">
                <!-- Quick Actions -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-zap me-2"></i>Quick Actions
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions">
                            <a href="{{ route('member.orders.create') }}" class="btn btn-primary btn-block mb-2">
                                <i class="fe fe-plus me-2"></i>Create New Order
                            </a>
                            <button class="btn btn-success btn-block mb-2" onclick="reorderLast()">
                                <i class="fe fe-repeat me-2"></i>Reorder Last Order
                            </button>
                            <button class="btn btn-info btn-block mb-2" onclick="exportOrders()">
                                <i class="fe fe-download me-2"></i>Export Orders
                            </button>
                            <button class="btn btn-warning btn-block" onclick="contactSupport()">
                                <i class="fe fe-help-circle me-2"></i>Order Support
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Order Analytics -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-bar-chart me-2"></i>Order Analytics
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="analytics-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>This Month</span>
                                <span class="fw-semibold">{{ $monthlyOrders ?? 0 }} orders</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-primary" style="width: {{ min(($monthlyOrders ?? 0) * 10, 100) }}%"></div>
                            </div>
                        </div>
                        <div class="analytics-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Average Order</span>
                                <span class="fw-semibold">৳{{ number_format($averageOrderValue ?? 0, 2) }}</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-success" style="width: {{ min(($averageOrderValue ?? 0) / 10, 100) }}%"></div>
                            </div>
                        </div>
                        <div class="analytics-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Success Rate</span>
                                <span class="fw-semibold">{{ number_format($successRate ?? 0, 1) }}%</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-warning" style="width: {{ $successRate ?? 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-activity me-2"></i>Recent Activity
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="activity-list">
                            @if($orders->take(4)->count() > 0)
                                @foreach($orders->take(4) as $recentOrder)
                                <div class="activity-item">
                                    <div class="activity-icon bg-{{ $statusColors[$recentOrder->status] ?? 'secondary' }}-transparent">
                                        <i class="fe fe-package text-{{ $statusColors[$recentOrder->status] ?? 'secondary' }}"></i>
                                    </div>
                                    <div class="activity-content">
                                        <p class="mb-1">Order #{{ $recentOrder->order_number }} {{ $recentOrder->status }}</p>
                                        <small class="text-muted">{{ $recentOrder->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted">
                                    <p>No recent activity</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-truck me-2"></i>Shipping Info
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="shipping-info">
                            <div class="info-item">
                                <h6 class="mb-2">Default Address</h6>
                                <p class="text-muted mb-3">
                                    {{ auth()->user()->address ?? '123 Main Street' }}<br>
                                    {{ auth()->user()->city ?? 'Dhaka' }}, {{ auth()->user()->state ?? 'Dhaka' }} {{ auth()->user()->zip ?? '1000' }}
                                </p>
                                <a href="{{ route('member.profile') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fe fe-edit me-1"></i>Update Address
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
.order-products {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
}

.analytics-item {
    margin-bottom: 20px;
}

.progress-sm {
    height: 6px;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 15px;
}

.activity-icon {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.quick-actions .btn-block {
    width: 100%;
    text-align: left;
}

.table-responsive {
    border-radius: 8px;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 14px;
    }
    
    .btn-group-sm .btn {
        padding: 0.2rem 0.4rem;
    }
    
    .order-products .badge {
        font-size: 10px;
    }
}
</style>
@endpush

@push('scripts')
<script>
function cancelOrder(orderId) {
    Swal.fire({
        title: 'Cancel Order?',
        text: 'Are you sure you want to cancel this order?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, cancel it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Here you would make an AJAX call to cancel the order
            Swal.fire({
                title: 'Order Cancelled',
                text: 'Your order has been cancelled successfully.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}

function trackOrder(orderId) {
    Swal.fire({
        title: 'Order Tracking',
        html: `
            <div class="text-start">
                <div class="tracking-info">
                    <div class="tracking-step completed">
                        <div class="step-icon"><i class="fe fe-check"></i></div>
                        <div class="step-content">
                            <h6>Order Confirmed</h6>
                            <small class="text-muted">Your order has been confirmed</small>
                        </div>
                    </div>
                    <div class="tracking-step completed">
                        <div class="step-icon"><i class="fe fe-package"></i></div>
                        <div class="step-content">
                            <h6>Processing</h6>
                            <small class="text-muted">Your order is being processed</small>
                        </div>
                    </div>
                    <div class="tracking-step active">
                        <div class="step-icon"><i class="fe fe-truck"></i></div>
                        <div class="step-content">
                            <h6>Shipped</h6>
                            <small class="text-muted">Your order is on the way</small>
                        </div>
                    </div>
                    <div class="tracking-step">
                        <div class="step-icon"><i class="fe fe-home"></i></div>
                        <div class="step-content">
                            <h6>Delivered</h6>
                            <small class="text-muted">Order will be delivered</small>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <strong>Tracking Number:</strong> TRK${orderId}${Math.random().toString(36).substr(2, 6).toUpperCase()}
                </div>
            </div>
        `,
        confirmButtonText: 'Close',
        width: '500px'
    });
}

function downloadInvoice(orderId) {
    Swal.fire({
        title: 'Downloading Invoice...',
        text: `Invoice for Order #${orderId} is being downloaded.`,
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
}

function exportOrders() {
    Swal.fire({
        title: 'Export Orders',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Export Format:</label>
                    <select class="form-select" id="exportFormat">
                        <option value="excel">Excel (.xlsx)</option>
                        <option value="csv">CSV (.csv)</option>
                        <option value="pdf">PDF (.pdf)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date Range:</label>
                    <select class="form-select" id="exportRange">
                        <option value="all">All Orders</option>
                        <option value="month">This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
            </div>
        `,
        confirmButtonText: 'Export',
        showCancelButton: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Export Started',
                text: 'Your order export is being prepared...',
                icon: 'info',
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
}

function reorderLast() {
    Swal.fire({
        title: 'Reorder Last Order',
        text: 'Do you want to create a new order based on your last order?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, reorder!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('member.orders.create') }}";
        }
    });
}

function contactSupport() {
    Swal.fire({
        title: 'Order Support',
        html: `
            <div class="text-start">
                <p>Need help with your orders? Contact our support team:</p>
                <div class="d-flex flex-column gap-2">
                    <button class="btn btn-primary btn-sm" onclick="openChat()">
                        <i class="fe fe-message-circle me-1"></i>Live Chat
                    </button>
                    <button class="btn btn-success btn-sm" onclick="sendEmail()">
                        <i class="fe fe-mail me-1"></i>Send Email
                    </button>
                    <button class="btn btn-info btn-sm" onclick="callSupport()">
                        <i class="fe fe-phone me-1"></i>Call Support
                    </button>
                </div>
                <div class="mt-3 text-center">
                    <small class="text-muted">Support Hours: 9 AM - 6 PM EST</small>
                </div>
            </div>
        `,
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonText: 'Close'
    });
}

// Filter functionality
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    // Implement filter logic here
    console.log('Filtering by status:', status);
});
</script>

<style>
.tracking-info {
    position: relative;
}

.tracking-step {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    position: relative;
}

.tracking-step:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 17px;
    top: 35px;
    width: 2px;
    height: 30px;
    background-color: #e9ecef;
}

.tracking-step.completed::after {
    background-color: #28a745;
}

.tracking-step.active::after {
    background-color: #007bff;
}

.step-icon {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    position: relative;
    z-index: 1;
}

.tracking-step.completed .step-icon {
    background-color: #28a745;
    color: white;
}

.tracking-step.active .step-icon {
    background-color: #007bff;
    color: white;
}

.step-content h6 {
    margin-bottom: 5px;
    font-size: 14px;
}

.step-content small {
    font-size: 12px;
}
</style>
@endpush
