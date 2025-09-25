@extends('admin.layouts.app')

@section('title', 'Vendor Dashboard')

@section('page-header')
<h3 class="page-title">Welcome back, {{ Auth::user()->name }}!</h3>
<ul class="breadcrumb">
    <li class="breadcrumb-item active">Dashboard</li>
</ul>
@endsection

@section('content')

<!-- Balance Overview -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card bg-primary text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fas fa-wallet fa-3x"></i>
                    </div>
                    <div class="col">
                        <h3 class="mb-1">Available Balance</h3>
                        <h2 class="mb-0" id="current-balance">৳{{ number_format(Auth::user()->deposit_wallet ?? 0, 2) }}</h2>
                        <small class="opacity-75">Last updated: {{ now()->format('M d, Y H:i') }}</small>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('vendor.transfers.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-paper-plane"></i> Transfer Money
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Total Sent</h6>
                        <h4 class="text-success mb-0" id="total-sent">৳{{ number_format($transferStats['total_sent'] ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Pending Transfers</h6>
                        <h4 class="text-warning mb-0" id="pending-transfers">{{ $transferStats['pending_count'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="fas fa-bolt text-primary"></i> Quick Actions
                </h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('vendor.transfers.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-paper-plane"></i><br>Send Money
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('vendor.products.create') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-plus"></i><br>Add Product
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('vendor.orders.index') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-shopping-cart"></i><br>View Orders
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button onclick="refreshDashboard()" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-sync-alt"></i><br>Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Row -->
<div class="row">
    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-comman w-100">
            <div class="card-body">
                <div class="db-widgets d-flex justify-content-between align-items-center">
                    <div class="db-info">
                        <h6>Total Products</h6>
                        <h3>{{ $stats['total_products'] }}</h3>
                    </div>
                    <div class="db-icon">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-comman w-100">
            <div class="card-body">
                <div class="db-widgets d-flex justify-content-between align-items-center">
                    <div class="db-info">
                        <h6>Active Products</h6>
                        <h3>{{ $stats['active_products'] }}</h3>
                    </div>
                    <div class="db-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-comman w-100">
            <div class="card-body">
                <div class="db-widgets d-flex justify-content-between align-items-center">
                    <div class="db-info">
                        <h6>Total Orders</h6>
                        <h3>{{ $stats['total_orders'] }}</h3>
                    </div>
                    <div class="db-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-comman w-100">
            <div class="card-body">
                <div class="db-widgets d-flex justify-content-between align-items-center">
                    <div class="db-info">
                        <h6>Pending Orders</h6>
                        <h3>{{ $stats['pending_orders'] }}</h3>
                    </div>
                    <div class="db-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Row -->
<div class="row">
    <div class="col-xl-6 col-sm-6 col-12 d-flex">
        <div class="card bg-comman w-100">
            <div class="card-body">
                <div class="db-widgets d-flex justify-content-between align-items-center">
                    <div class="db-info">
                        <h6>Total Revenue</h6>
                        <h3>৳{{ number_format($stats['total_revenue'], 2) }}</h3>
                    </div>
                    <div class="db-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-sm-6 col-12 d-flex">
        <div class="card bg-comman w-100">
            <div class="card-body">
                <div class="db-widgets d-flex justify-content-between align-items-center">
                    <div class="db-info">
                        <h6>Monthly Revenue</h6>
                        <h3>৳{{ number_format($stats['monthly_revenue'], 2) }}</h3>
                    </div>
                    <div class="db-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    
    <!-- Recent Orders -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Recent Orders</h5>
            </div>
            <div class="card-body">
                @if($recent_orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_orders as $order)
                                <tr>
                                    <td><strong>#{{ $order->id }}</strong></td>
                                    <td>{{ $order->customer->name ?? 'Guest' }}</td>
                                    <td>{{ $order->items->count() }} items</td>
                                    <td>৳{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($order->status === 'pending') bg-warning text-dark
                                            @elseif($order->status === 'completed') bg-success text-white
                                            @elseif($order->status === 'cancelled') bg-danger text-white
                                            @else bg-secondary text-white
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Orders Yet</h5>
                        <p class="text-muted">You haven't received any orders yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Low Stock Products -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Low Stock Alert</h5>
            </div>
            <div class="card-body">
                @if($low_stock_products->count() > 0)
                    <div class="activity-groups">
                        @foreach($low_stock_products as $product)
                        <div class="activity-awards">
                            <div class="award-boxs">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('admin-assets/img/product-placeholder.png') }}" alt="Product">
                            </div>
                            <div class="award-list-outs">
                                <h4>{{ Str::limit($product->name, 30) }}</h4>
                                <h5>{{ $product->stock_quantity }} remaining</h5>
                            </div>
                            <div class="award-time-list">
                                <span class="badge bg-danger text-white">Low Stock</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h6 class="text-muted">All Good!</h6>
                        <p class="text-muted">No low stock products.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
</div>

<!-- Shop Info Card -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Shop Information</h5>
                <a href="{{ route('vendor.profile') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit me-1"></i>Edit Profile
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><strong>Shop Name:</strong></label>
                            <p class="mb-0">{{ $vendor->shop_name ?? 'Not set' }}</p>
                        </div>
                        <div class="form-group">
                            <label><strong>Business License:</strong></label>
                            <p class="mb-0">{{ $vendor->business_license ?? 'Not set' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><strong>Tax ID:</strong></label>
                            <p class="mb-0">{{ $vendor->tax_id ?? 'Not set' }}</p>
                        </div>
                        <div class="form-group">
                            <label><strong>Phone:</strong></label>
                            <p class="mb-0">{{ $vendor->phone ?? 'Not set' }}</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><strong>Shop Description:</strong></label>
                            <p class="mb-0">{{ $vendor->shop_description ?? 'No description provided' }}</p>
                        </div>
                        <div class="form-group">
                            <label><strong>Shop Address:</strong></label>
                            <p class="mb-0">{{ $vendor->shop_address ?? 'Not set' }}</p>
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
.bg-comman {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
.db-widgets .db-icon {
    font-size: 2.5rem;
    opacity: 0.8;
}
.activity-awards {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
}
.award-boxs img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
}
.award-list-outs {
    flex: 1;
    margin-left: 15px;
}
.award-list-outs h4 {
    font-size: 14px;
    margin-bottom: 5px;
    font-weight: 600;
}
.award-list-outs h5 {
    font-size: 12px;
    color: #666;
    margin-bottom: 0;
}
.award-time-list {
    margin-left: auto;
}

/* Bootstrap 5 Badge Fix */
.badge {
    font-size: 0.75em;
    font-weight: 600;
    border-radius: 0.375rem;
    padding: 0.35em 0.65em;
    text-transform: capitalize;
}

/* Ensure text contrast for badges */
.badge.bg-warning {
    color: #000 !important;
}

.badge.bg-success,
.badge.bg-danger,
.badge.bg-secondary {
    color: #fff !important;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-refresh dashboard data every 30 seconds
    setInterval(function() {
        refreshBalanceData();
    }, 30000);
    
    function refreshBalanceData() {
        $.ajax({
            url: '{{ route("vendor.dashboard.refresh-balance") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#current-balance').text('৳' + response.balance);
                    $('#total-sent').text('৳' + response.total_sent);
                    $('#pending-transfers').text(response.pending_count);
                }
            },
            error: function() {
                console.log('Failed to refresh balance data');
            }
        });
    }
});

function refreshDashboard() {
    location.reload();
}

// Show loading state for quick action buttons
$('.btn[href]').click(function() {
    const btn = $(this);
    const originalText = btn.html();
    btn.html('<i class="fas fa-spinner fa-spin"></i><br>Loading...');
    
    // Reset after 3 seconds if page doesn't change
    setTimeout(() => {
        btn.html(originalText);
    }, 3000);
});
</script>
@endpush
