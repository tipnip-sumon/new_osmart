@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')
<!-- Start::page-header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div>
        <h2 class="page-title fw-semibold fs-18 mb-0">Order #{{ $order->order_number ?? 'ORD-' . $order->id }}</h2>
        <p class="fw-medium fs-13 text-muted mb-0">Detailed information about this order</p>
    </div>
    <div class="btn-list">
        <a href="{{ route('vendor.orders.index') }}" class="btn btn-outline-light">
            <i class="ri-arrow-left-line me-1"></i>Back to Orders
        </a>
        <a href="{{ route('vendor.orders.invoice', $order) }}" class="btn btn-success" target="_blank">
            <i class="ri-download-line me-1"></i>Download Invoice
        </a>
        <button class="btn btn-info" onclick="printOrder()">
            <i class="ri-printer-line me-1"></i>Print
        </button>
    </div>
</div>
<!-- End::page-header -->

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Start::row -->
<div class="row" id="order-details">
    <!-- Order Summary -->
    <div class="col-xxl-8 col-xl-7">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Order Summary</div>
                <div>
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'processing' => 'info',
                            'shipped' => 'primary',
                            'delivered' => 'success',
                            'cancelled' => 'danger'
                        ];
                        $statusColor = $statusColors[$order->status] ?? 'secondary';
                    @endphp
                    <span class="badge bg-{{ $statusColor }}-transparent text-{{ $statusColor }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <!-- Order Timeline -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="timeline">
                            <div class="timeline-item {{ $order->status == 'pending' ? 'active' : ($order->created_at ? 'completed' : '') }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Order Placed</h6>
                                    <p class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                            
                            <div class="timeline-item {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : ($order->status == 'processing' ? 'active' : '') }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Processing</h6>
                                    <p class="text-muted">
                                        @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                                            @if($order->processing_at)
                                                Started {{ $order->processing_at->format('M d, Y h:i A') }}
                                            @else
                                                Order is being processed
                                            @endif
                                        @else
                                            Waiting for processing
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="timeline-item {{ in_array($order->status, ['shipped', 'delivered']) ? 'completed' : ($order->status == 'shipped' ? 'active' : '') }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Shipped</h6>
                                    <p class="text-muted">
                                        @if(in_array($order->status, ['shipped', 'delivered']))
                                            @if($order->shipped_at)
                                                Shipped {{ $order->shipped_at->format('M d, Y h:i A') }}
                                            @else
                                                Order has been shipped
                                            @endif
                                            @if($order->tracking_number)
                                                <br><small>Tracking: {{ $order->tracking_number }}</small>
                                            @endif
                                        @else
                                            Pending shipment
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="timeline-item {{ $order->status == 'delivered' ? 'completed active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Delivered</h6>
                                    <p class="text-muted">
                                        @if($order->status == 'delivered')
                                            @if($order->delivered_at)
                                                Delivered {{ $order->delivered_at->format('M d, Y h:i A') }}
                                            @else
                                                Order delivered successfully
                                            @endif
                                        @else
                                            Pending delivery
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            @if($order->status == 'cancelled')
                            <div class="timeline-item completed" style="border-left-color: #dc3545;">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <h6 class="text-danger">Cancelled</h6>
                                    <p class="text-muted">
                                        @if($order->cancelled_at)
                                            Cancelled {{ $order->cancelled_at->format('M d, Y h:i A') }}
                                        @else
                                            Order has been cancelled
                                        @endif
                                        @if($order->cancellation_reason)
                                            <br><small>Reason: {{ $order->cancellation_reason }}</small>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Your Items in this Order -->
                <h6 class="mb-3">Your Items in this Order</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vendorItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-md me-2">
                                            @if($item->product && $item->product->images)
                                                @php
                                                    // Handle images that might be JSON string or already decoded array
                                                    $images = $item->product->images;
                                                    if (is_string($images)) {
                                                        $images = json_decode($images, true);
                                                    }
                                                    
                                                    $imageUrl = '';
                                                    
                                                    if (!empty($images) && is_array($images)) {
                                                        // Handle both old and new image formats
                                                        $firstImage = $images[0];
                                                        
                                                        if (is_array($firstImage)) {
                                                            // New format with sizes
                                                            if (isset($firstImage['sizes']['thumbnail']['storage_url'])) {
                                                                $imageUrl = $firstImage['sizes']['thumbnail']['storage_url'];
                                                            } elseif (isset($firstImage['sizes']['small']['storage_url'])) {
                                                                $imageUrl = $firstImage['sizes']['small']['storage_url'];
                                                            } elseif (isset($firstImage['sizes']['original']['storage_url'])) {
                                                                $imageUrl = $firstImage['sizes']['original']['storage_url'];
                                                            }
                                                        } else {
                                                            // Old format - direct path
                                                            $imageUrl = asset('storage/' . $firstImage);
                                                        }
                                                    }
                                                @endphp
                                                
                                                @if($imageUrl)
                                                    <img src="{{ $imageUrl }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="rounded order-item-image" 
                                                         style="width: 50px; height: 50px; object-fit: cover;"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="bg-light d-none align-items-center justify-content-center rounded" style="width: 50px; height: 50px;">
                                                        <i class="ri-image-line text-muted"></i>
                                                    </div>
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 50px; height: 50px;">
                                                        <i class="ri-image-line text-muted"></i>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 50px; height: 50px;">
                                                    <i class="ri-image-line text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $item->product->name ?? 'Product Name' }}</h6>
                                            <small class="text-muted">{{ $item->product->sku ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->product->sku ?? 'N/A' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>৳{{ number_format($item->price, 2) }}</td>
                                <td class="fw-semibold">৳{{ number_format($item->price * $item->quantity, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $statusColor }}-transparent text-{{ $statusColor }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="ri-inbox-line fs-24 mb-2 d-block"></i>
                                    No items found for your store in this order.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($vendorItems->count() > 0)
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="4">Your Total Earnings:</th>
                                <th class="text-success">৳{{ number_format($vendorTotal, 2) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>

                <!-- Status Update Form -->
                @if($order->status != 'delivered' && $order->status != 'cancelled')
                <div class="mt-4">
                    <h6>Update Order Status</h6>
                    <form action="{{ route('vendor.orders.update-status', $order) }}" method="POST" class="d-flex gap-2 flex-wrap">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-select" style="max-width: 200px;" required>
                            @php
                                $currentStatus = $order->status;
                                $availableStatuses = [];
                                
                                // Define valid status transitions
                                switch($currentStatus) {
                                    case 'pending':
                                        $availableStatuses = ['pending', 'processing', 'cancelled'];
                                        break;
                                    case 'processing':
                                        $availableStatuses = ['processing', 'shipped', 'cancelled'];
                                        break;
                                    case 'shipped':
                                        $availableStatuses = ['shipped', 'delivered'];
                                        break;
                                    default:
                                        $availableStatuses = [$currentStatus];
                                }
                            @endphp
                            
                            @foreach($availableStatuses as $status)
                                <option value="{{ $status }}" {{ $currentStatus == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        
                        @if(in_array($currentStatus, ['processing', 'shipped']))
                        <input type="text" 
                               name="tracking_number" 
                               class="form-control" 
                               placeholder="Tracking Number (optional)" 
                               style="max-width: 200px;"
                               value="{{ $order->tracking_number ?? '' }}">
                        @endif
                        
                        @if($currentStatus == 'pending' || $currentStatus == 'processing')
                        <textarea name="notes" 
                                  class="form-control" 
                                  placeholder="Status update notes (optional)" 
                                  style="max-width: 300px; min-height: 38px; max-height: 100px;"
                                  rows="1"></textarea>
                        @endif
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-refresh-line me-1"></i>Update Status
                        </button>
                    </form>
                    
                    @if($vendorItems->count() > 1)
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="ri-information-line me-1"></i>
                            This will update the status for all your items in this order.
                        </small>
                    </div>
                    @endif
                </div>
                @else
                <div class="mt-4">
                    <div class="alert alert-info mb-0">
                        <i class="ri-information-line me-1"></i>
                        This order is {{ $order->status }}. No further status updates are allowed.
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Order Information -->
    <div class="col-xxl-4 col-xl-5">
        <!-- Customer Information -->
        <div class="card custom-card mb-3">
            <div class="card-header">
                <div class="card-title">Customer Information</div>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-lg me-3">
                        @if($order->customer && $order->customer->avatar)
                            @php
                                $avatarUrl = '';
                                $avatar = $order->customer->avatar;
                                
                                // Check if avatar is JSON string or already decoded, and handle accordingly
                                if (is_string($avatar) && (str_starts_with($avatar, '[') || str_starts_with($avatar, '{'))) {
                                    $avatarData = json_decode($avatar, true);
                                    if (is_array($avatarData)) {
                                        if (isset($avatarData['sizes']['thumbnail']['storage_url'])) {
                                            $avatarUrl = $avatarData['sizes']['thumbnail']['storage_url'];
                                        } elseif (isset($avatarData['sizes']['small']['storage_url'])) {
                                            $avatarUrl = $avatarData['sizes']['small']['storage_url'];
                                        } elseif (isset($avatarData['sizes']['original']['storage_url'])) {
                                            $avatarUrl = $avatarData['sizes']['original']['storage_url'];
                                        }
                                    }
                                } elseif (is_array($avatar)) {
                                    // Already decoded array format
                                    if (isset($avatar['sizes']['thumbnail']['storage_url'])) {
                                        $avatarUrl = $avatar['sizes']['thumbnail']['storage_url'];
                                    } elseif (isset($avatar['sizes']['small']['storage_url'])) {
                                        $avatarUrl = $avatar['sizes']['small']['storage_url'];
                                    } elseif (isset($avatar['sizes']['original']['storage_url'])) {
                                        $avatarUrl = $avatar['sizes']['original']['storage_url'];
                                    }
                                } elseif (is_string($avatar)) {
                                    // Old format - direct path
                                    $avatarUrl = asset('storage/' . $avatar);
                                }
                            @endphp
                            
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" 
                                     alt="{{ $order->customer->name }}" 
                                     class="rounded-circle" 
                                     style="width: 60px; height: 60px; object-fit: cover;"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="bg-primary d-none align-items-center justify-content-center rounded-circle" style="width: 60px; height: 60px;">
                                    <i class="ri-user-line text-white fs-20"></i>
                                </div>
                            @else
                                <div class="bg-primary d-flex align-items-center justify-content-center rounded-circle" style="width: 60px; height: 60px;">
                                    <i class="ri-user-line text-white fs-20"></i>
                                </div>
                            @endif
                        @else
                            <div class="bg-primary d-flex align-items-center justify-content-center rounded-circle" style="width: 60px; height: 60px;">
                                <i class="ri-user-line text-white fs-20"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $order->customer->name ?? ($order->shipping_name ?? 'Guest Customer') }}</h6>
                        <p class="text-muted mb-0">{{ $order->customer->email ?? ($order->shipping_email ?? 'No email provided') }}</p>
                        <small class="text-muted">Customer since {{ $order->customer ? $order->customer->created_at->format('M Y') : 'N/A' }}</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p class="text-muted mb-1">Total Orders</p>
                        <h6>{{ $order->customer ? $order->customer->orders()->count() : 'N/A' }}</h6>
                    </div>
                    <div class="col-6">
                        <p class="text-muted mb-1">Total Spent</p>
                        <h6>৳{{ $order->customer ? number_format($order->customer->orders()->sum('total_amount'), 2) : '0.00' }}</h6>
                    </div>
                </div>
                <div class="mt-3">
                    @if($order->customer && $order->customer->email)
                        <button class="btn btn-sm btn-outline-primary w-100" onclick="contactCustomer('{{ $order->customer->email }}')">
                            <i class="ri-mail-line me-1"></i>Contact Customer
                        </button>
                    @else
                        <button class="btn btn-sm btn-outline-secondary w-100" disabled>
                            <i class="ri-mail-line me-1"></i>Email not available
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="card custom-card mb-3">
            <div class="card-header">
                <div class="card-title">Shipping Address</div>
            </div>
            <div class="card-body">
                @php
                    // Parse shipping address data - it might be JSON or individual fields
                    $shippingData = [];
                    
                    // Check if shipping_address contains JSON data
                    if ($order->shipping_address && (str_starts_with($order->shipping_address, '{') || str_starts_with($order->shipping_address, '['))) {
                        $shippingData = json_decode($order->shipping_address, true) ?? [];
                    }
                    
                    // Use JSON data if available, otherwise fall back to individual fields
                    $fullName = $shippingData['full_name'] ?? $order->shipping_name ?? ($order->customer->name ?? 'N/A');
                    $phone = $shippingData['phone'] ?? $order->shipping_phone ?? null;
                    $address = $shippingData['address'] ?? $order->shipping_address ?? 'Address not provided';
                    $city = $shippingData['city'] ?? $order->shipping_city ?? null;
                    $district = $shippingData['district'] ?? $order->shipping_state ?? null;
                    $postalCode = $shippingData['postal_code'] ?? $order->shipping_zip ?? null;
                    $country = $shippingData['country'] ?? $order->shipping_country ?? 'Bangladesh';
                    
                    // If address is JSON, don't show it as the address line
                    if (str_starts_with($address, '{')) {
                        $address = 'Address not provided';
                    }
                @endphp
                
                <address class="mb-0">
                    <strong>{{ $fullName }}</strong><br>
                    {{ $address }}<br>
                    @if($city || $district || $postalCode)
                        {{ $city ?? '' }}@if($city && ($district || $postalCode)), @endif
                        {{ $district ?? '' }}@if($district && $postalCode) - @endif
                        {{ $postalCode ?? '' }}<br>
                    @endif
                    {{ $country }}<br>
                    @if($phone)
                        <i class="ri-phone-line"></i> {{ $phone }}
                    @else
                        <i class="ri-phone-line text-muted"></i> <span class="text-muted">Phone not provided</span>
                    @endif
                </address>
                
                @if($order->tracking_number)
                <div class="mt-3">
                    <p class="text-muted mb-1">Tracking Number:</p>
                    <span class="badge bg-info">{{ $order->tracking_number }}</span>
                </div>
                @endif
                
                @if($order->delivery_notes)
                <div class="mt-3">
                    <p class="text-muted mb-1">Delivery Notes:</p>
                    <p class="mb-0">{{ $order->delivery_notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Summary -->
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Order Summary</div>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-6">Order Date:</div>
                    <div class="col-6 text-end">{{ $order->created_at->format('M d, Y') }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">Order Number:</div>
                    <div class="col-6 text-end">#{{ $order->order_number ?? 'ORD-' . $order->id }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">Payment Method:</div>
                    <div class="col-6 text-end">{{ ucfirst($order->payment_method ?? 'N/A') }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">Payment Status:</div>
                    <div class="col-6 text-end">
                        @php
                            $paymentStatusColors = [
                                'paid' => 'success',
                                'pending' => 'warning', 
                                'failed' => 'danger',
                                'refunded' => 'info'
                            ];
                            $paymentColor = $paymentStatusColors[$order->payment_status ?? 'pending'] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $paymentColor }}">{{ ucfirst($order->payment_status ?? 'Pending') }}</span>
                    </div>
                </div>
                @if($order->created_at != $order->updated_at)
                <div class="row mb-2">
                    <div class="col-6">Last Updated:</div>
                    <div class="col-6 text-end">{{ $order->updated_at->format('M d, Y h:i A') }}</div>
                </div>
                @endif
                <hr>
                <div class="row mb-2">
                    <div class="col-6">Your Items:</div>
                    <div class="col-6 text-end">৳{{ number_format($vendorTotal, 2) }}</div>
                </div>
                @php
                    // Get commission rate from settings or default to 10%
                    $commissionRate = config('commission.vendor_rate', 0.10);
                    $commission = $vendorTotal * $commissionRate;
                    $vendorEarnings = $vendorTotal - $commission;
                @endphp
                <div class="row mb-2">
                    <div class="col-6">Commission ({{ number_format($commissionRate * 100, 1) }}%):</div>
                    <div class="col-6 text-end text-danger">-৳{{ number_format($commission, 2) }}</div>
                </div>
                @if($order->shipping_amount > 0)
                <div class="row mb-2">
                    <div class="col-6">Shipping Cost:</div>
                    <div class="col-6 text-end">৳{{ number_format($order->shipping_amount, 2) }}</div>
                </div>
                @endif
                @if($order->discount_amount > 0)
                <div class="row mb-2">
                    <div class="col-6">Discount Applied:</div>
                    <div class="col-6 text-end text-success">-৳{{ number_format($order->discount_amount, 2) }}</div>
                </div>
                @endif
                @if($order->tax_amount > 0)
                <div class="row mb-2">
                    <div class="col-6">Tax:</div>
                    <div class="col-6 text-end">৳{{ number_format($order->tax_amount, 2) }}</div>
                </div>
                @endif
                <hr>
                <div class="row fw-semibold text-success">
                    <div class="col-6">Your Earnings:</div>
                    <div class="col-6 text-end">৳{{ number_format($vendorEarnings, 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End::row -->

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
    padding-left: 25px;
}

.timeline-marker {
    position: absolute;
    left: -12px;
    top: 5px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #e9ecef;
    border: 2px solid #fff;
}

.timeline-item.completed .timeline-marker {
    background: #28a745;
}

.timeline-item.active .timeline-marker {
    background: #007bff;
}

.timeline-marker.bg-danger {
    background: #dc3545 !important;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-size: 14px;
}

.timeline-content p {
    margin-bottom: 0;
    font-size: 12px;
}

/* Order Status Badges */
.badge {
    font-size: 11px;
    padding: 0.375rem 0.75rem;
}

/* Enhanced Order Details */
.order-item-image {
    transition: transform 0.2s ease;
}

.order-item-image:hover {
    transform: scale(1.05);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .btn-list {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .timeline {
        padding-left: 15px;
    }
    
    .timeline-item {
        padding-left: 20px;
    }
}

@media print {
    .btn-list, .card-header, .timeline {
        display: none !important;
    }
    
    .no-print {
        display: none !important;
    }
}

/* Status update form enhancements */
.status-update-form {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;
}

/* Enhanced alerts */
.alert {
    border-left: 4px solid;
    border-radius: 6px;
}

.alert-info {
    border-left-color: #17a2b8;
}

.alert-success {
    border-left-color: #28a745;
}

.alert-warning {
    border-left-color: #ffc107;
}

.alert-danger {
    border-left-color: #dc3545;
}
</style>
@endpush

@push('scripts')
<script>
    function contactCustomer(email) {
        if (email && email !== 'No email provided') {
            const orderNumber = '#{{ $order->order_number ?? "ORD-" . $order->id }}';
            const vendorName = '{{ Auth::user()->shop_name ?? Auth::user()->name }}';
            const subject = `Regarding Your Order ${orderNumber}`;
            const body = `Dear Customer,%0D%0A%0D%0AThank you for your order ${orderNumber}. I wanted to follow up regarding your recent purchase.%0D%0A%0D%0AOrder Status: {{ ucfirst($order->status) }}%0D%0AOrder Date: {{ $order->created_at->format('M d, Y') }}%0D%0A%0D%0AIf you have any questions or concerns, please don't hesitate to reach out.%0D%0A%0D%0ABest regards,%0D%0A${vendorName}`;
            
            window.location.href = `mailto:${email}?subject=${subject}&body=${body}`;
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Email Not Available',
                text: 'Customer email address is not available for this order.',
                confirmButtonColor: '#3085d6'
            });
        }
    }

    function printOrder() {
        // Hide elements that shouldn't be printed
        const hiddenElements = document.querySelectorAll('.btn-list, .no-print, .status-update-form');
        hiddenElements.forEach(el => el.style.display = 'none');
        
        window.print();
        
        // Show elements again after printing
        setTimeout(() => {
            hiddenElements.forEach(el => el.style.display = '');
        }, 1000);
    }

    // Status update form enhancement
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.querySelector('select[name="status"]');
        const trackingInput = document.querySelector('input[name="tracking_number"]');
        const notesTextarea = document.querySelector('textarea[name="notes"]');
        
        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                const selectedStatus = this.value;
                
                // Show/hide tracking number field based on status
                if (trackingInput) {
                    if (selectedStatus === 'shipped' || selectedStatus === 'processing') {
                        trackingInput.style.display = 'block';
                        if (selectedStatus === 'shipped') {
                            trackingInput.setAttribute('placeholder', 'Tracking Number (recommended)');
                        }
                    } else {
                        trackingInput.style.display = 'none';
                    }
                }
                
                // Show confirmation for certain status changes
                if (selectedStatus === 'delivered') {
                    Swal.fire({
                        title: 'Mark as Delivered?',
                        text: 'This will mark the order as completed. This action cannot be undone.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, mark as delivered',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            this.value = '{{ $order->status }}'; // Reset to original status
                        }
                    });
                } else if (selectedStatus === 'cancelled') {
                    Swal.fire({
                        title: 'Cancel Order?',
                        text: 'This will cancel the order. Please provide a reason if possible.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, cancel order',
                        cancelButtonText: 'No, keep order'
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            this.value = '{{ $order->status }}'; // Reset to original status
                        } else if (notesTextarea) {
                            notesTextarea.setAttribute('placeholder', 'Cancellation reason (recommended)');
                            notesTextarea.focus();
                        }
                    });
                }
            });
        }
        
        // Auto-expand textarea
        if (notesTextarea) {
            notesTextarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 100) + 'px';
            });
        }
        
        // Order status polling (every 2 minutes for active orders)
        @if(in_array($order->status, ['pending', 'processing', 'shipped']))
        let pollCount = 0;
        const maxPolls = 30; // Stop after 30 polls (1 hour)
        
        const pollOrderStatus = setInterval(function() {
            if (pollCount >= maxPolls) {
                clearInterval(pollOrderStatus);
                return;
            }
            
            fetch('{{ route("vendor.orders.show", $order) }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status && data.status !== '{{ $order->status }}') {
                    // Status has changed, show notification and reload
                    Swal.fire({
                        icon: 'info',
                        title: 'Order Status Updated',
                        text: `Order status has been updated to: ${data.status}`,
                        timer: 3000,
                        showConfirmButton: false
                    });
                    
                    setTimeout(() => location.reload(), 3000);
                }
            })
            .catch(error => console.log('Status check failed:', error));
            
            pollCount++;
        }, 120000); // Check every 2 minutes
        @endif
    });

    // Success/Error message handling
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#dc3545'
        });
    @endif
</script>
@endpush
@endsection
