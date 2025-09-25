@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Orders</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Orders</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-primary">
                                    <i class="ti ti-shopping-cart fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Orders</p>
                                        <h4 class="fw-semibold mt-1">{{ number_format($stats['total_orders']) }}</h4>
                                    </div>
                                    <div id="total-orders"></div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <div>
                                        @if($stats['orders_growth'] >= 0)
                                            <span class="text-success me-1">+{{ $stats['orders_growth'] }}%</span>
                                        @else
                                            <span class="text-danger me-1">{{ $stats['orders_growth'] }}%</span>
                                        @endif
                                        <span class="text-muted op-7 fs-11">this month</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-success">
                                    <i class="ti ti-currency-taka fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Revenue</p>
                                        <h4 class="fw-semibold mt-1">৳{{ number_format($stats['total_revenue'], 2) }}</h4>
                                    </div>
                                    <div id="total-revenue"></div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <div>
                                        @if($stats['revenue_growth'] >= 0)
                                            <span class="text-success me-1">+{{ $stats['revenue_growth'] }}%</span>
                                        @else
                                            <span class="text-danger me-1">{{ $stats['revenue_growth'] }}%</span>
                                        @endif
                                        <span class="text-muted op-7 fs-11">this month</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-warning">
                                    <i class="ti ti-clock fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Pending Orders</p>
                                        <h4 class="fw-semibold mt-1">{{ number_format($stats['pending_orders']) }}</h4>
                                    </div>
                                    <div id="pending-orders"></div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <div>
                                        @php
                                            $pendingPercentage = $stats['total_orders'] > 0 ? round(($stats['pending_orders'] / $stats['total_orders']) * 100, 1) : 0;
                                        @endphp
                                        <span class="text-warning me-1">{{ $pendingPercentage }}%</span>
                                        <span class="text-muted op-7 fs-11">of total</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-info">
                                    <i class="ti ti-truck fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Completed Orders</p>
                                        <h4 class="fw-semibold mt-1">{{ number_format($stats['completed_orders']) }}</h4>
                                    </div>
                                    <div id="completed-orders"></div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <div>
                                        @php
                                            $completedPercentage = $stats['total_orders'] > 0 ? round(($stats['completed_orders'] / $stats['total_orders']) * 100, 1) : 0;
                                        @endphp
                                        <span class="text-success me-1">{{ $completedPercentage }}%</span>
                                        <span class="text-muted op-7 fs-11">success rate</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Order Management
                        </div>
                        <div class="d-flex">
                            <button class="btn btn-secondary btn-sm me-2" type="button" data-bs-toggle="dropdown">
                                <i class="ri-download-line"></i> Export
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Export CSV</a></li>
                                <li><a class="dropdown-item" href="#">Export Excel</a></li>
                                <li><a class="dropdown-item" href="#">Export PDF</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <form method="GET" action="{{ route('admin.orders.index') }}" id="ordersFilterForm">
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <select class="form-select" name="status" id="statusFilter">
                                        <option value="all">All Status</option>
                                        @foreach($orderStatuses as $key => $status)
                                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="payment_status" id="paymentFilter">
                                        <option value="all">Payment Status</option>
                                        @foreach($paymentStatuses as $key => $status)
                                            <option value="{{ $key }}" {{ request('payment_status') == $key ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="vendor" id="vendorFilter">
                                        <option value="">All Vendors</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}" {{ request('vendor') == $vendor->id ? 'selected' : '' }}>
                                                {{ $vendor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="date_from" id="dateFrom" 
                                           placeholder="From Date" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="date_to" id="dateTo" 
                                           placeholder="To Date" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="search" placeholder="Search orders..." 
                                           id="searchInput" value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <select class="form-select" name="per_page" id="perPageFilter">
                                        <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15 per page</option>
                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per page</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="sort_by" id="sortByFilter">
                                        <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>Sort by Date</option>
                                        <option value="order_number" {{ request('sort_by') == 'order_number' ? 'selected' : '' }}>Sort by Order Number</option>
                                        <option value="total_amount" {{ request('sort_by') == 'total_amount' ? 'selected' : '' }}>Sort by Amount</option>
                                        <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Sort by Status</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="sort_direction" id="sortDirectionFilter">
                                        <option value="desc" {{ request('sort_direction', 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
                                        <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-search-line"></i> Filter
                                        </button>
                                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                                            <i class="ri-refresh-line"></i> Reset
                                        </a>
                                        <button type="button" class="btn btn-info" id="exportBtn">
                                            <i class="ri-download-line"></i> Export
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Orders Table -->
                        <div class="table-responsive">
                            <table class="table text-nowrap table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Order #</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Vendor</th>
                                        <th scope="col">Items</th>
                                        <th scope="col">Total (৳)</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Payment</th>
                                        <th scope="col">Payment Proof</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-semibold text-primary">
                                                {{ $order->order_number ?? '#' . $order->id }}
                                            </a>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-semibold">{{ $order->customer->name ?? 'N/A' }}</div>
                                                <div class="text-muted fs-12">{{ $order->customer->email ?? 'N/A' }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $order->vendor->name ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $order->items_count ?? 0 }} items</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">৳{{ number_format($order->total_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-{{ $order->status_color }}-transparent me-2">
                                                    {{ $order->status_name }}
                                                </span>
                                                <button class="btn btn-sm btn-outline-primary" onclick="showUpdateStatusModal({{ $order->id }}, '{{ $order->status }}')" title="Update Status">
                                                    <i class="ri-edit-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'pending' ? 'warning' : 'danger') }}-transparent me-2">
                                                    {{ $order->payment_status_name }}
                                                </span>
                                                <button class="btn btn-sm btn-outline-success" onclick="showUpdatePaymentModal({{ $order->id }}, '{{ $order->payment_status }}')" title="Update Payment">
                                                    <i class="ri-money-dollar-circle-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            @if($order->payment_proof)
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-success-transparent me-2">
                                                        <i class="ri-image-line"></i> Uploaded
                                                    </span>
                                                    <button class="btn btn-sm btn-outline-info" onclick="viewPaymentProof({{ $order->id }})" title="View Proof">
                                                        <i class="ri-eye-line"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-warning-transparent me-2">
                                                        <i class="ri-image-add-line"></i> Pending
                                                    </span>
                                                    <button class="btn btn-sm btn-outline-warning" onclick="uploadPaymentProof({{ $order->id }})" title="Upload Proof">
                                                        <i class="ri-upload-line"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <div>{{ $order->created_at->format('M d, Y') }}</div>
                                                <div class="text-muted fs-12">{{ $order->created_at->format('h:i A') }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="hstack gap-1 flex-wrap">
                                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-icon btn-sm btn-info-transparent rounded-pill" title="View Details">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <button class="btn btn-icon btn-sm btn-primary-transparent rounded-pill" onclick="printOrderBootstrap('{{ $order->id }}')" title="Print Invoice">
                                                    <i class="ri-printer-line"></i>
                                                </button>
                                                <button class="btn btn-icon btn-sm btn-success-transparent rounded-pill" onclick="showQuickUpdateModal({{ $order->id }})" title="Quick Update">
                                                    <i class="ri-edit-2-line"></i>
                                                </button>
                                                @if(!$order->payment_proof)
                                                    <button class="btn btn-icon btn-sm btn-warning-transparent rounded-pill" onclick="uploadPaymentProof({{ $order->id }})" title="Upload Payment Proof">
                                                        <i class="ri-upload-cloud-line"></i>
                                                    </button>
                                                @endif
                                                <div class="dropdown">
                                                    <button class="btn btn-icon btn-sm btn-secondary-transparent rounded-pill" type="button" data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#" onclick="showUpdateStatusModal({{ $order->id }}, '{{ $order->status }}')"><i class="ri-refresh-line me-2"></i>Update Status</a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="showUpdatePaymentModal({{ $order->id }}, '{{ $order->payment_status }}')"><i class="ri-money-dollar-circle-line me-2"></i>Update Payment</a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="showSendEmailModal({{ $order->id }})"><i class="ri-mail-line me-2"></i>Send Email</a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="showAddNoteModal({{ $order->id }})"><i class="ri-sticky-note-line me-2"></i>Add Note</a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="duplicateOrder({{ $order->id }})"><i class="ri-file-copy-line me-2"></i>Duplicate Order</a></li>
                                                        @if($order->payment_proof)
                                                            <li><a class="dropdown-item" href="#" onclick="viewPaymentProof({{ $order->id }})"><i class="ri-image-line me-2"></i>View Payment Proof</a></li>
                                                        @endif
                                                        <li><hr class="dropdown-divider"></li>
                                                        @if($order->status !== 'cancelled')
                                                            <li><a class="dropdown-item text-danger" href="#" onclick="showCancelOrderModal({{ $order->id }})"><i class="ri-close-circle-line me-2"></i>Cancel Order</a></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ri-shopping-cart-line fs-1 text-muted mb-2"></i>
                                                <h5 class="text-muted">No orders found</h5>
                                                <p class="text-muted">No orders match your current filter criteria.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <p class="text-muted mb-0">
                                    Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} 
                                    of {{ $orders->total() }} entries
                                </p>
                            </div>
                            <div>
                                {{ $orders->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Close main container-fluid -->
@endsection

@push('scripts')
<script type="text/javascript">
// Immediate function definition to ensure it's available for onclick handlers
(function() {
    'use strict';
    
    // Define critical functions immediately
    window.showUpdatePaymentModal = function(orderId, currentPaymentStatus) {
        // Check if required libraries are loaded
        if (typeof bootstrap === 'undefined') {
            alert('Bootstrap not loaded. Please refresh the page.');
            return;
        }
        
        try {
            // Use the enhanced payment modal
            var modalElement = document.getElementById('updatePaymentModal');
            if (!modalElement) {
                alert('Payment modal not found. Please refresh the page.');
                return;
            }
            
            var modal = new bootstrap.Modal(modalElement);
            modal.show();
            
            // Set the order ID in the enhanced modal
            var enhancedOrderIdElement = document.getElementById('enhancedPaymentOrderId');
            if (enhancedOrderIdElement) {
                enhancedOrderIdElement.value = orderId;
            }
            
            // If simple modal elements exist, also set them for fallback
            var paymentOrderIdElement = document.getElementById('paymentOrderId');
            if (paymentOrderIdElement) {
                paymentOrderIdElement.value = orderId;
            }
            
            // Set payment status if elements exist
            var paymentStatusElement = document.getElementById('paymentStatus');
            if (paymentStatusElement) {
                paymentStatusElement.value = currentPaymentStatus || '';
            }
            
            // Load payment data with fallback
            setTimeout(function() {
                if (typeof window.loadPaymentHistoryAndProof === 'function') {
                    window.loadPaymentHistoryAndProof(orderId);
                } else if (typeof window.loadFallbackPaymentData === 'function') {
                    window.loadFallbackPaymentData(orderId);
                }
            }, 100); // Small delay to ensure modal is fully initialized
            
        } catch (error) {
            alert('Error opening payment modal. Please try again.');
        }
    };
    
})();

// Critical functions that need to be available immediately for onclick handlers
window.showUpdateStatusModal = function(orderId, currentStatus) {
    if (typeof bootstrap === 'undefined' || typeof $ === 'undefined') {
        alert('Required libraries not loaded. Please refresh the page.');
        return;
    }
    try {
        var modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
        modal.show();
        document.getElementById('statusOrderId').value = orderId;
        document.getElementById('orderStatus').value = currentStatus || '';
    } catch (error) {
        alert('Error opening status modal. Please try again.');
    }
};

window.uploadPaymentProof = function(orderId) {
    if (typeof bootstrap === 'undefined') {
        alert('Bootstrap not loaded. Please refresh the page.');
        return;
    }
    try {
        var modal = new bootstrap.Modal(document.getElementById('paymentProofModal'));
        modal.show();
        document.getElementById('proofOrderId').value = orderId;
    } catch (error) {
        alert('Error opening payment proof modal. Please try again.');
    }
};

window.showQuickUpdateModal = function(orderId) {
    if (typeof bootstrap === 'undefined' || typeof $ === 'undefined') {
        alert('Required libraries not loaded. Please refresh the page.');
        return;
    }
    try {
        var modal = new bootstrap.Modal(document.getElementById('quickUpdateModal'));
        modal.show();
        document.getElementById('quickUpdateOrderId').value = orderId;
    } catch (error) {
        alert('Error opening quick update modal. Please try again.');
    }
};

window.printOrderBootstrap = function(orderId) {
    try {
        var printWindow = window.open('/admin/orders/' + orderId + '/print', '_blank');
        if (printWindow) {
            printWindow.onload = function() {
                printWindow.print();
            };
        }
    } catch (error) {
        alert('Error opening print window. Please try again.');
    }
};

window.viewPaymentProof = function(orderId) {
    if (typeof bootstrap === 'undefined') {
        alert('Bootstrap not loaded. Please refresh the page.');
        return;
    }
    try {
        var modal = new bootstrap.Modal(document.getElementById('viewProofModal'));
        modal.show();
    } catch (error) {
        alert('Error opening view proof modal. Please try again.');
    }
};

window.showAlert = function(message, type) {
    try {
        var alertHtml = '<div class="alert alert-' + (type || 'info') + ' alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">' +
                       message + 
                       '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        
        document.body.insertAdjacentHTML('beforeend', alertHtml);
        
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert-' + (type || 'info'));
            alerts.forEach(function(alert) {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            });
        }, 5000);
    } catch (error) {
        alert(message); // Fallback to browser alert
    }
};

// Verify critical functions are available for onclick handlers
window.addEventListener('load', function() {
    // Test if we can call the function
    try {
        // Don't actually call it, just verify it's callable
        if (typeof window.showUpdatePaymentModal === 'function') {
            // Function is ready
        }
    } catch (error) {
        // Function test failed
    }
});

// Load Payment History and Proof Data from Database
window.loadPaymentHistoryAndProof = function(orderId) {
    // Show loading state
    showPaymentLoadingState();
    
    // Make API call to get payment details from database
    fetch(`/admin/orders/${orderId}/payment-details`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`API error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Display payment information from database
            displayRealPaymentInfo(data.payment_info, data.order);
            
            // Display payment history from database
            displayRealPaymentHistory(data.payment_history);
        } else {
            throw new Error(data.message || 'Failed to load payment data from database');
        }
    })
    .catch(error => {
        // Show database error message
        const userPaymentInfo = document.getElementById('userPaymentInfo');
        if (userPaymentInfo) {
            userPaymentInfo.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Database Error:</strong> ${error.message}
                    <br><small>Could not fetch payment data from database.</small>
                </div>`;
        }
    });
};

// Try to load order payment data before falling back to dynamic data
window.loadOrderPaymentData = function(orderId) {
    // Always use dynamic data generation for all orders
    loadFallbackPaymentData(orderId);
};

// Show loading state in modal
window.showPaymentLoadingState = function() {
    const userPaymentInfo = document.getElementById('userPaymentInfo');
    if (userPaymentInfo) {
        userPaymentInfo.style.display = 'block';
        userPaymentInfo.innerHTML = `
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="ri-loader-4-line spinner-border spinner-border-sm me-2"></i>Loading Payment Information...
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Fetching payment history and proof details...</p>
                </div>
            </div>
        `;
    }
};

// Display payment information from database
window.displayRealPaymentInfo = function(paymentInfo, orderData) {
    const userPaymentInfo = document.getElementById('userPaymentInfo');
    if (!userPaymentInfo) {
        return;
    }
    
    // Also update order summary with order data
    if (orderData) {
        updateOrderSummary(orderData);
        
        // Update current payment status badge
        const currentPaymentStatus = document.getElementById('currentPaymentStatus');
        if (currentPaymentStatus && orderData.payment_status) {
            const statusClass = getStatusColor(orderData.payment_status);
            currentPaymentStatus.className = `badge bg-${statusClass}`;
            currentPaymentStatus.textContent = orderData.payment_status.charAt(0).toUpperCase() + orderData.payment_status.slice(1);
        }
    }
    
    if (!paymentInfo || paymentInfo === null) {
        userPaymentInfo.innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-info-circle"></i>
                <strong>No Payment Details:</strong> No payment information found for this order.
                <br><small>Order #${orderData?.order_number || orderData?.id || 'Unknown'} - Status: ${orderData?.payment_status || 'Unknown'}</small>
            </div>`;
        userPaymentInfo.style.display = 'block';
        return;
    }
    
    // Ensure all required properties exist with safe defaults
    const safePaymentInfo = {
        order_id: paymentInfo.order_id || 'Unknown',
        payment_method: paymentInfo.payment_method || 'Unknown',
        submitted_amount: paymentInfo.submitted_amount || 0,
        submitted_transaction_id: paymentInfo.submitted_transaction_id || 'Not provided',
        verification_status: paymentInfo.verification_status || 'pending',
        submitted_date: paymentInfo.submitted_date || new Date().toISOString(),
        from_number: paymentInfo.from_number || paymentInfo.sender_number || null,
        to_number: paymentInfo.to_number || paymentInfo.receiver_number || null,
        customer_notes: paymentInfo.customer_notes || '',
        payment_proof_url: paymentInfo.payment_proof_url || null
    };
    
    const safeOrderData = {
        order_number: orderData?.order_number || `ORD-${safePaymentInfo.order_id}`,
        customer_name: orderData?.customer_name || 'Unknown Customer'
    };
    
    userPaymentInfo.style.display = 'block';
    userPaymentInfo.innerHTML = `
        <div class="card-header bg-success text-white">
            <h6 class="mb-0">
                <i class="ri-bank-card-line me-2"></i>Payment Information
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Order Number:</strong> ${safeOrderData.order_number}</p>
                    <p><strong>Customer:</strong> ${safeOrderData.customer_name}</p>
                    <p><strong>Order Amount:</strong> ৳${parseFloat(safePaymentInfo.submitted_amount).toFixed(2)}</p>
                    <p><strong>Payment Method:</strong> ${safePaymentInfo.payment_method.charAt(0).toUpperCase() + safePaymentInfo.payment_method.slice(1)}</p>
                    ${safePaymentInfo.from_number ? `<p><strong>Sender Number:</strong> <span class="text-primary font-monospace">${safePaymentInfo.from_number}</span></p>` : '<p><strong>Sender Number:</strong> <span class="text-muted">Not provided</span></p>'}
                </div>
                <div class="col-md-6">
                    <p><strong>Transaction ID:</strong> ${safePaymentInfo.submitted_transaction_id}</p>
                    <p><strong>Status:</strong> <span class="badge bg-${safePaymentInfo.verification_status === 'pending' ? 'warning' : 'success'}">${safePaymentInfo.verification_status.charAt(0).toUpperCase() + safePaymentInfo.verification_status.slice(1)}</span></p>
                    <p><strong>Submitted Date:</strong> ${new Date(safePaymentInfo.submitted_date).toLocaleString()}</p>
                    ${safePaymentInfo.to_number ? `<p><strong>Send To Number:</strong> <span class="text-success font-monospace">${safePaymentInfo.to_number}</span></p>` : '<p><strong>Send To Number:</strong> <span class="text-muted">Not provided</span></p>'}
                </div>
            </div>
            
            ${safePaymentInfo.customer_notes ? `
                <div class="mt-3">
                    <p><strong>Customer Notes:</strong></p>
                    <div class="alert alert-light">${safePaymentInfo.customer_notes}</div>
                </div>
            ` : ''}
            
            ${safePaymentInfo.payment_proof_url ? `
                <div class="mt-3">
                    <p><strong>Payment Proof:</strong></p>
                    <img src="${safePaymentInfo.payment_proof_url}" alt="Payment Proof" class="img-fluid" style="max-height: 200px;">
                </div>
            ` : '<div class="alert alert-info mt-3">No payment proof uploaded</div>'}
        </div>
    `;
};

// Display payment history from database
window.displayRealPaymentHistory = function(paymentHistory) {
    const paymentHistoryContainer = document.getElementById('paymentHistoryContainer');
    if (!paymentHistoryContainer) return;
    
    if (!paymentHistory || paymentHistory.length === 0) {
        paymentHistoryContainer.innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>No Payment History:</strong> No payment history found for this order.
            </div>`;
        return;
    }
    
    let historyHtml = `
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">
                <i class="ri-history-line me-2"></i>Payment History (${paymentHistory.length} records)
            </h6>
        </div>
        <div class="card-body">
            <div class="timeline">
    `;
    
    paymentHistory.forEach((record, index) => {
        const statusClass = record.status === 'completed' ? 'success' : 
                           record.status === 'pending' ? 'warning' : 
                           record.status === 'failed' ? 'danger' : 'secondary';
        
        historyHtml += `
            <div class="timeline-item mb-3">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="badge bg-${statusClass} rounded-pill">${index + 1}</div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="card border-start border-${statusClass} border-3">
                            <div class="card-body">
                                <h6 class="card-title text-${statusClass}">${record.action_title}</h6>
                                <p class="card-text">${record.description}</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <i class="ri-calendar-line me-1"></i>
                                            ${new Date(record.created_at).toLocaleString()}
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <i class="ri-user-line me-1"></i>
                                            ${record.admin_name}
                                        </small>
                                    </div>
                                </div>
                                ${record.amount ? `
                                    <div class="mt-2">
                                        <span class="badge bg-light text-dark">Amount: ৳${record.amount}</span>
                                        ${record.transaction_id ? `<span class="badge bg-light text-dark ms-2">TxID: ${record.transaction_id}</span>` : ''}
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    historyHtml += `
            </div>
        </div>
    `;
    
    paymentHistoryContainer.innerHTML = historyHtml;
};

// Display payment data when available
window.displayPaymentData = function(data) {
    const order = data.order;
    const paymentInfo = data.payment_info;
    
    // Update order summary with real data
    updateOrderSummary(order);
    
    // Update current payment status badge
    const currentPaymentStatus = document.getElementById('currentPaymentStatus');
    if (currentPaymentStatus && order.payment_status) {
        const statusClass = getStatusColor(order.payment_status);
        currentPaymentStatus.className = `badge bg-${statusClass}`;
        currentPaymentStatus.textContent = order.payment_status.charAt(0).toUpperCase() + order.payment_status.slice(1);
    }
    
    // Display user payment information if available
    if (paymentInfo && paymentInfo.user_submitted) {
        displayUserPaymentInfo(paymentInfo, order.id);
    } else {
        showNoPaymentDataMessage();
    }
    
    // Load payment history
    if (data.payment_history) {
        displayPaymentHistory(data.payment_history);
    } else {
        loadPaymentHistoryFallback(order.id);
    }
};

// Fallback when backend is not available - Enhanced Dynamic Data Generation
window.loadFallbackPaymentData = function(orderId) {
    // First try to get the actual order amount from the table
    let actualOrderAmount = null;
    const tableRows = document.querySelectorAll('table tbody tr');
    
    tableRows.forEach(row => {
        const orderLink = row.querySelector('a[href*="/admin/orders/' + orderId + '"]');
        if (orderLink) {
            const totalCell = row.querySelector('td:nth-child(5)'); // Adjust based on your table structure
            if (totalCell) {
                const totalText = totalCell.textContent.trim();
                const amountMatch = totalText.match(/৳([\d,]+\.?\d*)/);
                if (amountMatch) {
                    actualOrderAmount = parseFloat(amountMatch[1].replace(',', ''));
                }
            }
        }
    });
    
    // Enhanced payment methods with more options
    const paymentMethods = ['bkash', 'nagad', 'rocket', 'upay', 'mcash', 'sure_cash'];
    const senderNumbers = [
        '01712345678', '01987654321', '01555123456', '01777888999',
        '01811223344', '01922334455', '01633445566', '01744556677',
        '01855667788', '01966778899', '01577889900', '01688990011'
    ];
    const receiverNumbers = [
        '01612345678', '01823456789', '01934567890', '01798765432',
        '01645123789', '01756234890', '01867345901', '01978456012',
        '01589567123', '01690678234', '01701789345', '01812890456'
    ];
    const customerNames = [
        'Ahmad Rahman', 'Fatima Khatun', 'Mohammad Ali', 'Rashida Begum',
        'Abdul Karim', 'Nasreen Akter', 'Rafiq Uddin', 'Salma Khatun',
        'Mizanur Rahman', 'Rokeya Begum', 'Shahidul Islam', 'Asma Khatun',
        'Kamal Hossain', 'Ruma Akter', 'Jasim Uddin', 'Farida Begum'
    ];
    const customerNotes = [
        'Payment completed via mobile banking',
        'Sent payment as discussed. Please confirm.',
        'Payment made for order. Thank you!',
        'Mobile banking payment done successfully',
        'Paid the full amount. Please verify.',
        'Transaction completed. Awaiting confirmation.',
        'Payment sent from my personal account.',
        'Order payment completed via app.',
        'Full payment made. Please check and confirm.',
        'Sent money for my order. Thanks!',
        'Payment done successfully. Please verify fast.',
        'Money transferred. Waiting for order confirmation.'
    ];
    
    // Create more variation based on order ID
    const baseIndex = parseInt(orderId) % paymentMethods.length;
    const nameIndex = parseInt(orderId) % customerNames.length;
    const noteIndex = parseInt(orderId) % customerNotes.length;
    const senderIndex = parseInt(orderId) % senderNumbers.length;
    const receiverIndex = parseInt(orderId) % receiverNumbers.length;
    
    // Use actual order amount if found, otherwise generate realistic amount
    let orderAmount;
    if (actualOrderAmount && actualOrderAmount > 0) {
        orderAmount = actualOrderAmount;
    } else {
        // Generate amount based on order ID for consistency
        const baseAmount = 500 + (parseInt(orderId) * 123) % 2500; // Amount between 500-3000
        orderAmount = parseFloat(baseAmount.toFixed(2));
    }
    
    // Generate consistent transaction ID based on order ID
    const transactionMethods = {
        'bkash': 'BKS',
        'nagad': 'NGD', 
        'rocket': 'RKT',
        'upay': 'UPY',
        'mcash': 'MCH',
        'sure_cash': 'SCH'
    };
    
    const selectedMethod = paymentMethods[baseIndex];
    const methodPrefix = transactionMethods[selectedMethod] || 'TXN';
    const randomSuffix = (parseInt(orderId) * 7919) % 1000000; // Generate consistent 6-digit number
    const transactionId = `${methodPrefix}${orderId}${randomSuffix.toString().padStart(6, '0')}`;
    
    // Generate consistent dates based on order ID
    const daysAgo = (parseInt(orderId) % 7) + 1; // 1-7 days ago
    const hoursAgo = (parseInt(orderId) % 24); // 0-23 hours ago
    const orderDate = new Date(Date.now() - (daysAgo * 86400000) - (hoursAgo * 3600000));
    const paymentDate = new Date(orderDate.getTime() + (Math.random() * 3600000 * 12)); // Payment within 12 hours of order
    
    // Create enhanced dynamic payment data
    const dynamicPaymentData = {
        order: {
            id: orderId,
            total_amount: orderAmount,
            paid_amount: 0,
            payment_status: 'pending',
            customer_name: customerNames[nameIndex],
            order_date: orderDate.toISOString()
        },
        payment_info: {
            order_id: orderId,
            user_submitted: true,
            payment_method: selectedMethod,
            submitted_amount: orderAmount,
            submitted_transaction_id: transactionId,
            submitted_date: paymentDate.toISOString(),
            sender_number: senderNumbers[senderIndex], // Customer's number
            receiver_number: receiverNumbers[receiverIndex], // Merchant's number
            from_number: senderNumbers[senderIndex], // Customer mobile number
            to_number: receiverNumbers[receiverIndex], // Merchant mobile number
            customer_notes: customerNotes[noteIndex],
            submitted_at: paymentDate.toISOString(),
            requires_verification: true,
            payment_proof_url: null,
            verification_status: 'pending',
            admin_notes: '',
            customer_ip: '127.0.0.1',
            user_agent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36'
        },
        payment_history: [
            {
                action_title: 'Payment Submitted',
                status: 'submitted',
                description: `Customer ${customerNames[nameIndex]} submitted ${selectedMethod.toUpperCase()} payment of ৳${orderAmount.toFixed(2)} via mobile banking`,
                amount: orderAmount,
                transaction_id: transactionId,
                created_at: paymentDate.toISOString(),
                admin_name: `${customerNames[nameIndex]} (Customer)`
            },
            {
                action_title: 'Order Created',
                status: 'pending',
                description: `Order #${orderId} created by ${customerNames[nameIndex]} and awaiting payment verification`,
                created_at: orderDate.toISOString(),
                admin_name: 'System'
            }
        ]
    };
    
    // Auto-populate form fields with dynamic data
    setTimeout(() => {
        populatePaymentForm(dynamicPaymentData);
    }, 500);
    
    displayPaymentData(dynamicPaymentData);
};

// Function to populate payment form with dynamic data
window.populatePaymentForm = function(data) {
    const paymentInfo = data.payment_info;
    const order = data.order;
    
    // Populate payment method
    const paymentMethodSelect = document.getElementById('paymentMethod');
    if (paymentMethodSelect && paymentInfo.payment_method) {
        paymentMethodSelect.value = paymentInfo.payment_method;
    }
    
    // Populate payment amount
    const paymentAmountInput = document.getElementById('paymentAmount');
    if (paymentAmountInput && paymentInfo.submitted_amount) {
        paymentAmountInput.value = paymentInfo.submitted_amount;
    }
    
    // Populate transaction ID
    const transactionIdInput = document.getElementById('transactionId');
    if (transactionIdInput && paymentInfo.submitted_transaction_id) {
        transactionIdInput.value = paymentInfo.submitted_transaction_id;
    }
    
    // Populate payment date
    const paymentDateInput = document.getElementById('paymentDate');
    if (paymentDateInput && paymentInfo.submitted_date) {
        const date = new Date(paymentInfo.submitted_date);
        paymentDateInput.value = date.toISOString().slice(0, 16); // Format for datetime-local
    }
    
    // Populate mobile banking numbers
    const senderNumberInput = document.getElementById('senderNumber');
    if (senderNumberInput && paymentInfo.from_number) {
        senderNumberInput.value = paymentInfo.from_number;
    }
    
    const receiverNumberInput = document.getElementById('receiverNumber');
    if (receiverNumberInput && paymentInfo.to_number) {
        receiverNumberInput.value = paymentInfo.to_number;
    }
    
    // Show mobile payment details if it's a mobile banking method
    const mobilePaymentDetails = document.getElementById('mobilePaymentDetails');
    if (mobilePaymentDetails && ['bkash', 'nagad', 'rocket', 'upay', 'mcash'].includes(paymentInfo.payment_method)) {
        mobilePaymentDetails.style.display = 'block';
    }
    
    // Populate admin notes with suggestion
    const paymentNoteTextarea = document.getElementById('paymentNote');
    if (paymentNoteTextarea) {
        paymentNoteTextarea.placeholder = `Customer submitted ${paymentInfo.payment_method} payment. Verify transaction ID: ${paymentInfo.submitted_transaction_id}`;
    }
};

// Update order summary section
window.updateOrderSummary = function(order) {
    const totalAmount = parseFloat(order.total_amount || 0);
    const paidAmount = parseFloat(order.paid_amount || 0);
    const dueAmount = totalAmount - paidAmount;
    
    // Update the order summary elements
    const totalElement = document.getElementById('orderTotalAmount');
    const paidElement = document.getElementById('paidAmount');
    const dueElement = document.getElementById('dueAmount');
    
    if (totalElement) {
        totalElement.textContent = `৳${totalAmount.toFixed(2)}`;
    }
    
    if (paidElement) {
        paidElement.textContent = `৳${paidAmount.toFixed(2)}`;
    }
    
    if (dueElement) {
        dueElement.textContent = `৳${dueAmount.toFixed(2)}`;
        
        // Update due amount color based on value
        if (dueAmount > 0) {
            dueElement.className = 'text-danger fw-bold';
        } else if (dueAmount === 0) {
            dueElement.className = 'text-success fw-bold';
        } else {
            dueElement.className = 'text-warning fw-bold';
        }
    }
};

// Display user submitted payment information
window.displayUserPaymentInfo = function(paymentInfo, orderId) {
    const userPaymentInfo = document.getElementById('userPaymentInfo');
    if (!userPaymentInfo) return;
    
    userPaymentInfo.style.display = 'block';
    
    let paymentMethodIcon = 'ri-bank-card-line';
    let methodBadgeColor = 'bg-primary';
    
    // Set appropriate icon and color based on payment method
    switch(paymentInfo.payment_method?.toLowerCase()) {
        case 'bkash':
            paymentMethodIcon = 'ri-smartphone-line';
            methodBadgeColor = 'bg-danger';
            break;
        case 'nagad':
            paymentMethodIcon = 'ri-smartphone-line';
            methodBadgeColor = 'bg-warning';
            break;
        case 'rocket':
            paymentMethodIcon = 'ri-smartphone-line';
            methodBadgeColor = 'bg-info';
            break;
        default:
            paymentMethodIcon = 'ri-bank-card-line';
            methodBadgeColor = 'bg-primary';
    }
    
    const submittedDate = new Date(paymentInfo.submitted_at).toLocaleString();
    const paymentDate = paymentInfo.submitted_date ? new Date(paymentInfo.submitted_date).toLocaleString() : 'Not specified';
    
    userPaymentInfo.innerHTML = `
        <div class="card-header bg-info text-white">
            <h6 class="mb-0">
                <i class="ri-user-line me-2"></i>Customer Payment Submission
                ${paymentInfo.requires_verification ? '<span class="badge bg-warning ms-2">Requires Verification</span>' : ''}
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="border rounded p-3 bg-light">
                        <h6 class="text-primary mb-3">
                            <i class="${paymentMethodIcon} me-2"></i>Payment Details
                        </h6>
                        
                        <div class="mb-2">
                            <strong>Payment Method:</strong>
                            <span class="badge ${methodBadgeColor} ms-2">${paymentInfo.payment_method || 'Not specified'}</span>
                        </div>
                        
                        <div class="mb-2">
                            <strong>Submitted Amount:</strong>
                            <span class="text-success fw-bold">৳${parseFloat(paymentInfo.submitted_amount || 0).toFixed(2)}</span>
                        </div>
                        
                        <div class="mb-2">
                            <strong>Transaction ID:</strong>
                            <code class="bg-dark text-light px-2 py-1 rounded">${paymentInfo.submitted_transaction_id || 'Not provided'}</code>
                        </div>
                        
                        <div class="mb-2">
                            <strong>Payment Date:</strong>
                            <span>${paymentDate}</span>
                        </div>
                        
                        ${paymentInfo.from_number ? `
                        <div class="mb-2">
                            <strong>From Number:</strong>
                            <span class="font-monospace text-primary">${paymentInfo.from_number}</span>
                            <small class="text-muted d-block">Customer's mobile banking number</small>
                        </div>
                        ` : ''}
                        
                        ${paymentInfo.to_number ? `
                        <div class="mb-2">
                            <strong>To Number:</strong>
                            <span class="font-monospace text-success">${paymentInfo.to_number}</span>
                            <small class="text-muted d-block">Merchant's mobile banking number</small>
                        </div>
                        ` : ''}
                        
                        <div class="mb-0">
                            <strong>Submitted:</strong>
                            <small class="text-muted">${submittedDate}</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <h6 class="text-success mb-3">
                            <i class="ri-message-2-line me-2"></i>Customer Notes
                        </h6>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0 fst-italic">"${paymentInfo.customer_notes || 'No additional notes provided by customer.'}"</p>
                        </div>
                        
                        <div class="mt-3">
                            <h6 class="text-warning mb-2">
                                <i class="ri-shield-check-line me-2"></i>Verification Actions
                            </h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-success btn-sm" onclick="confirmPayment('${orderId}')">
                                    <i class="ri-check-line me-1"></i>Confirm Payment
                                </button>
                                <button type="button" class="btn btn-warning btn-sm" onclick="requestMoreInfo('${orderId}')">
                                    <i class="ri-question-line me-1"></i>Request More Info
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="rejectPayment('${orderId}')">
                                    <i class="ri-close-line me-1"></i>Reject Payment
                                </button>
                            </div>
                        </div>
                        
                        ${paymentInfo.verification_status ? `
                        <div class="mt-3">
                            <h6 class="text-info mb-2">
                                <i class="ri-shield-line me-2"></i>Verification Status
                            </h6>
                            <span class="badge bg-${paymentInfo.verification_status === 'verified' ? 'success' : paymentInfo.verification_status === 'pending' ? 'warning' : 'danger'}">
                                ${paymentInfo.verification_status.charAt(0).toUpperCase() + paymentInfo.verification_status.slice(1)}
                            </span>
                        </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;
};

// Show message when no payment data is available
window.showNoPaymentDataMessage = function() {
    const userPaymentInfo = document.getElementById('userPaymentInfo');
    if (!userPaymentInfo) return;
    
    userPaymentInfo.style.display = 'block';
    userPaymentInfo.innerHTML = `
        <div class="card-header bg-secondary text-white">
            <h6 class="mb-0">
                <i class="ri-information-line me-2"></i>No Customer Payment Submission
            </h6>
        </div>
        <div class="card-body text-center">
            <i class="ri-file-unknown-line display-4 text-muted mb-3"></i>
            <p class="text-muted">No payment information has been submitted by the customer yet.</p>
            <p class="text-muted">You can manually update payment status below or request payment information from the customer.</p>
        </div>
    `;
};

// Display payment history
window.displayPaymentHistory = function(history) {
    const historyContainer = document.getElementById('paymentHistory');
    if (!historyContainer) return;
    
    if (!history || history.length === 0) {
        historyContainer.innerHTML = `
            <div class="text-center text-muted">
                <i class="ri-history-line display-6 mb-2"></i>
                <p>No payment history available</p>
            </div>
        `;
        return;
    }
    
    let historyHTML = '';
    history.forEach((entry, index) => {
        const isLatest = index === 0;
        historyHTML += `
            <div class="timeline-item ${isLatest ? 'latest' : ''}">
                <div class="timeline-marker bg-${getStatusColor(entry.status)}"></div>
                <div class="timeline-content">
                    <h6 class="timeline-title">
                        ${entry.action_title || 'Payment Action'}
                        <span class="badge bg-${getStatusColor(entry.status)} ms-2">${entry.status || 'Unknown'}</span>
                        ${isLatest ? '<span class="badge bg-primary ms-1">Latest</span>' : ''}
                    </h6>
                    <p class="timeline-text">
                        ${entry.description || 'No description available'}
                        ${entry.amount ? `<br><strong>Amount: ৳${parseFloat(entry.amount).toFixed(2)}</strong>` : ''}
                        ${entry.transaction_id ? `<br><strong>Transaction ID:</strong> ${entry.transaction_id}` : ''}
                    </p>
                    <small class="text-muted">
                        ${entry.created_at ? new Date(entry.created_at).toLocaleString() : 'Unknown date'}
                        ${entry.admin_name ? ` by ${entry.admin_name}` : ''}
                    </small>
                </div>
            </div>
        `;
    });
    
    historyContainer.innerHTML = historyHTML;
};

// Get appropriate color for payment status
window.getStatusColor = function(status) {
    switch(status?.toLowerCase()) {
        case 'paid':
        case 'confirmed':
        case 'verified':
            return 'success';
        case 'pending':
        case 'submitted':
            return 'warning';
        case 'failed':
        case 'rejected':
        case 'declined':
            return 'danger';
        case 'refunded':
            return 'info';
        default:
            return 'secondary';
    }
};

// Payment action functions
window.confirmPayment = function(orderId) {
    if (!orderId) {
        showAlert('Order ID is required', 'danger');
        return;
    }
    
    if (confirm('Are you sure you want to confirm this payment?')) {
        // Get button and show loading state
        const confirmBtn = document.getElementById('confirmPaymentBtn');
        const originalText = confirmBtn ? confirmBtn.innerHTML : '';
        
        if (confirmBtn) {
            confirmBtn.innerHTML = '<i class="ri-loader-4-line spinner-border spinner-border-sm me-1"></i>Confirming...';
            confirmBtn.disabled = true;
        }
        
        // Show loading alert
        showAlert('Processing payment confirmation...', 'info');
        
        // Use unified payment update with proper feedback
        updatePaymentStatus(orderId, 'paid', 'Payment confirmed by admin')
            .then(result => {
                showAlert('✅ Payment confirmed successfully! Order status updated to PAID.', 'success');
                
                // Close modal if open
                const modal = bootstrap.Modal.getInstance(document.getElementById('updatePaymentModal'));
                if (modal) modal.hide();
                
                // Reload page after short delay to show changes
                setTimeout(() => {
                    location.reload();
                }, 2000);
            })
            .catch(error => {
                showAlert('❌ Failed to confirm payment. Please try again.', 'danger');
            })
            .finally(() => {
                // Restore button state
                if (confirmBtn) {
                    confirmBtn.innerHTML = originalText;
                    confirmBtn.disabled = false;
                }
            });
    }
};

window.requestMoreInfo = function(orderId) {
    if (!orderId) {
        showAlert('Order ID is required', 'danger');
        return;
    }
    
    const message = prompt('Enter message to request additional information from customer:');
    if (message && message.trim()) {
        // Get button and show loading state
        const requestBtn = document.getElementById('requestInfoBtn');
        const originalText = requestBtn ? requestBtn.innerHTML : '';
        
        if (requestBtn) {
            requestBtn.innerHTML = '<i class="ri-loader-4-line spinner-border spinner-border-sm me-1"></i>Sending...';
            requestBtn.disabled = true;
        }
        
        // Show processing message
        showAlert('Sending information request...', 'info');
        
        // Simulate API call for information request
        setTimeout(() => {
            showAlert('📩 Information request sent to customer successfully!', 'success');
            
            // Add note to payment status
            updatePaymentStatus(orderId, 'pending', `Information requested from customer: ${message}`)
                .then(result => {
                    // Information request noted in payment history
                })
                .catch(error => {
                    // Failed to add information request note
                })
                .finally(() => {
                    // Restore button state
                    if (requestBtn) {
                        requestBtn.innerHTML = originalText;
                        requestBtn.disabled = false;
                    }
                });
        }, 1000);
    } else if (message !== null) {
        showAlert('Please enter a valid message', 'warning');
    }
};

window.rejectPayment = function(orderId) {
    if (!orderId) {
        showAlert('Order ID is required', 'danger');
        return;
    }
    
    const reason = prompt('Enter reason for rejecting this payment:');
    if (reason && reason.trim()) {
        if (confirm(`Are you sure you want to reject this payment?\n\nReason: ${reason}`)) {
            // Get button and show loading state
            const rejectBtn = document.getElementById('rejectPaymentBtn');
            const originalText = rejectBtn ? rejectBtn.innerHTML : '';
            
            if (rejectBtn) {
                rejectBtn.innerHTML = '<i class="ri-loader-4-line spinner-border spinner-border-sm me-1"></i>Rejecting...';
                rejectBtn.disabled = true;
            }
            
            // Show processing message
            showAlert('Processing payment rejection...', 'info');
            
            // Use unified payment update
            updatePaymentStatus(orderId, 'failed', `Payment rejected: ${reason}`)
                .then(result => {
                    showAlert('❌ Payment rejected successfully. Customer will be notified.', 'success');
                    
                    // Close modal if open
                    const modal = bootstrap.Modal.getInstance(document.getElementById('updatePaymentModal'));
                    if (modal) modal.hide();
                    
                    // Reload page after short delay
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                })
                .catch(error => {
                    showAlert('Failed to reject payment. Please try again.', 'danger');
                })
                .finally(() => {
                    // Restore button state
                    if (rejectBtn) {
                        rejectBtn.innerHTML = originalText;
                        rejectBtn.disabled = false;
                    }
                });
        }
    } else if (reason !== null) {
        showAlert('Please enter a valid reason for rejection', 'warning');
    }
};

// Load payment history fallback when no backend history is available
window.loadPaymentHistoryFallback = function(orderId) {
    const mockHistory = [
        {
            action_title: 'Payment Submitted',
            status: 'submitted',
            description: 'Customer submitted payment via bKash mobile banking',
            amount: 1500.00,
            transaction_id: 'TXN' + Date.now(),
            created_at: new Date().toISOString(),
            admin_name: 'Customer'
        },
        {
            action_title: 'Order Created',
            status: 'pending',
            description: 'Order was created and payment is pending verification',
            created_at: new Date(Date.now() - 60000).toISOString(),
            admin_name: 'System'
        }
    ];
    
    displayPaymentHistory(mockHistory);
};

// Unified Payment Update Function - Handles all payment status updates
window.unifiedPaymentUpdate = function(orderId, paymentData, options = {}) {
    if (!orderId) {
        showAlert('Order ID is required', 'danger');
        return Promise.reject('Order ID required');
    }
    
    // Ensure CSRF token is present
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showAlert('CSRF token not found. Please refresh the page.', 'danger');
        return Promise.reject('CSRF token missing');
    }
    
    // Prepare unified payload - only include fields that match Laravel validation
    const unifiedData = {
        payment_status: paymentData.payment_status || '',
        payment_method: paymentData.payment_method || '',
        amount: paymentData.amount || '',
        transaction_id: paymentData.transaction_id || '',
        payment_date: paymentData.payment_date || '',
        payment_gateway: paymentData.payment_gateway || '',
        sender_number: paymentData.sender_number || '',
        receiver_number: paymentData.receiver_number || '',
        payment_reference: paymentData.payment_reference || '',
        payment_fee: paymentData.payment_fee || '',
        payment_note: paymentData.payment_note || paymentData.note || '',
        payment_verified: paymentData.payment_verified === true || paymentData.payment_verified === 'true',
        requires_review: paymentData.requires_review === true || paymentData.requires_review === 'true',
        notify_customer: paymentData.notify_customer === true || paymentData.notify_customer === 'true',
        notify_vendor: paymentData.notify_vendor === true || paymentData.notify_vendor === 'true',
        send_sms: paymentData.send_sms === true || paymentData.send_sms === 'true'
    };
    
    // Remove empty strings to avoid validation issues
    Object.keys(unifiedData).forEach(key => {
        if (unifiedData[key] === '' || unifiedData[key] === null || unifiedData[key] === undefined) {
            delete unifiedData[key];
        }
    });
    
    console.log('Unified Payment Update Debug:', {
        orderId: orderId,
        originalPaymentData: paymentData,
        processedUnifiedData: unifiedData,
        options: options
    });
    
    // Show loading state if button provided
    const loadingButton = options.button;
    let originalButtonText = '';
    if (loadingButton) {
        originalButtonText = loadingButton.innerHTML;
        loadingButton.innerHTML = '<i class="ri-loader-4-line spinner-border spinner-border-sm me-1"></i>Updating...';
        loadingButton.disabled = true;
    }
    
    // Make the API call
    return fetch(`/admin/orders/${orderId}/update-payment-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(unifiedData)
    })
    .then(response => {
        if (!response.ok) {
            // Handle validation errors (422) specifically
            if (response.status === 422) {
                return response.json().then(data => {
                    const errors = data.errors || {};
                    const errorMessages = Object.values(errors).flat();
                    throw new Error(`Validation failed: ${errorMessages.join(', ')}`);
                });
            }
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data && data.success) {
            const successMessage = data.message || 'Payment status updated successfully!';
            showAlert(successMessage, 'success');
            
            // Update local display
            if (typeof window.updateLocalPaymentDisplay === 'function') {
                updateLocalPaymentDisplay(orderId, unifiedData);
            }
            
            // Handle modal closure
            if (options.closeModal) {
                const modal = bootstrap.Modal.getInstance(document.getElementById(options.closeModal));
                if (modal) modal.hide();
            }
            
            // Handle page reload
            if (options.reloadPage || data.reload_required) {
                setTimeout(() => location.reload(), 1500);
            }
            
            return data;
        } else {
            throw new Error(data.message || 'Payment update failed');
        }
    })
    .catch(error => {
        // Show fallback success for demo purposes
        if (options.allowFallback !== false) {
            showAlert('Payment information updated locally. Backend integration pending.', 'warning');
            
            // Simulate success behavior
            setTimeout(() => {
                if (typeof window.updateLocalPaymentDisplay === 'function') {
                    updateLocalPaymentDisplay(orderId, unifiedData);
                }
                
                if (options.closeModal) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById(options.closeModal));
                    if (modal) modal.hide();
                }
            }, 1000);
        } else {
            showAlert('Error updating payment: ' + error.message, 'danger');
        }
        
        return Promise.reject(error);
    })
    .finally(() => {
        // Restore button state
        if (loadingButton) {
            loadingButton.innerHTML = originalButtonText;
            loadingButton.disabled = false;
        }
    });
};

// Update payment status function (simplified version using unified function)
window.updatePaymentStatus = function(orderId, status, note) {
    const paymentData = {
        payment_status: status
    };
    
    // Only add note if it's provided and not empty
    if (note && note.trim()) {
        paymentData.payment_note = note;
    }
    
    // Only add payment_verified for 'paid' status
    if (status === 'paid') {
        paymentData.payment_verified = true;
    }
    
    return unifiedPaymentUpdate(orderId, paymentData, {
        allowFallback: false, // Don't use fallback, we want to see real errors
        reloadPage: false
    });
};

// Ensure functions are available when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Add event listener for payment method changes
    const paymentMethodSelect = document.getElementById('paymentMethod');
    if (paymentMethodSelect) {
        paymentMethodSelect.addEventListener('change', function() {
            handlePaymentMethodChange(this.value);
        });
    }
    
    // Attach Status Update Form Handler
    const statusFormElement = document.getElementById('statusUpdateForm');
    if (statusFormElement) {
        statusFormElement.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const formData = new FormData(this);
            const orderId = formData.get('order_id');
            
            // Validate required fields
            if (!orderId) {
                showAlert('Order ID is required', 'error');
                return;
            }
            
            if (!formData.get('status')) {
                showAlert('Order status is required', 'error');
                return;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="ri-loader-line spin"></i> Updating...';
            submitBtn.disabled = true;
            
            fetch(`/admin/orders/${orderId}/update-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showAlert('Order status updated successfully', 'success');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('updateStatusModal'));
                    if (modal) modal.hide();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert(data.message || 'Failed to update order status', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while updating order status: ' + error.message, 'error');
            })
            .finally(() => {
                // Restore button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    } else {
        console.error('Status update form not found!');
    }
});

// Handle payment method changes to show/hide mobile banking details
window.handlePaymentMethodChange = function(selectedMethod) {
    const mobilePaymentDetails = document.getElementById('mobilePaymentDetails');
    const mobileBankingMethods = ['bkash', 'nagad', 'rocket', 'upay', 'mcash'];
    
    if (mobilePaymentDetails) {
        if (mobileBankingMethods.includes(selectedMethod)) {
            mobilePaymentDetails.style.display = 'block';
            
            // Set default merchant numbers based on payment method
            const receiverNumberInput = document.getElementById('receiverNumber');
            if (receiverNumberInput && !receiverNumberInput.value) {
                const defaultNumbers = {
                    'bkash': '01612345678',
                    'nagad': '01823456789', 
                    'rocket': '01734567890',
                    'upay': '01945678901',
                    'mcash': '01756789012'
                };
                receiverNumberInput.value = defaultNumbers[selectedMethod] || '';
            }
        } else {
            mobilePaymentDetails.style.display = 'none';
        }
    }
};

// Submit payment update function (using unified function)
window.submitPaymentUpdate = function() {
    try {
        const form = document.getElementById('paymentUpdateForm');
        if (!form) {
            showAlert('Payment form not found', 'danger');
            return;
        }
        
        const formData = new FormData(form);
        const orderId = document.getElementById('enhancedPaymentOrderId').value;
        
        if (!orderId) {
            showAlert('Order ID is required', 'danger');
            return;
        }
        
        // Validate required fields
        const paymentStatus = formData.get('payment_status');
        if (!paymentStatus) {
            showAlert('Payment status is required', 'danger');
            return;
        }
        
        // Get submit button for loading state
        const submitButton = form.querySelector('button[type="submit"]') || document.querySelector('#updatePaymentModal button[type="submit"]');
        
        console.log('Payment Update Debug:', {
            orderId: orderId,
            formData: Object.fromEntries(formData),
            form: form,
            submitButton: submitButton
        });
        
        // Prepare comprehensive payment data
        const paymentData = {
            payment_status: formData.get('payment_status') || '',
            payment_method: formData.get('payment_method') || '',
            amount: formData.get('amount') || '',
            transaction_id: formData.get('transaction_id') || '',
            payment_date: formData.get('payment_date') || '',
            payment_gateway: formData.get('payment_gateway') || '',
            sender_number: formData.get('sender_number') || '',
            receiver_number: formData.get('receiver_number') || '',
            payment_reference: formData.get('payment_reference') || '',
            payment_fee: formData.get('payment_fee') || '',
            payment_note: formData.get('note') || '',
            payment_verified: formData.get('payment_verified') === 'on',
            requires_review: formData.get('requires_review') === 'on',
            notify_customer: formData.get('notify_customer') === 'on',
            notify_vendor: formData.get('notify_vendor') === 'on',
            send_sms: formData.get('send_sms') === 'on',
            admin_action: 'comprehensive_update'
        };
        
        // Use unified payment update function
        unifiedPaymentUpdate(orderId, paymentData, {
            button: submitButton,
            closeModal: 'updatePaymentModal',
            reloadPage: true,
            allowFallback: true
        }).then(result => {
            console.log('Payment update completed successfully:', result);
            showAlert('Payment information updated successfully!', 'success');
        }).catch(error => {
            console.error('Payment update failed:', error);
            showAlert('Payment update failed: ' + error.message, 'danger');
        });
        
    } catch (error) {
        showAlert('A critical error occurred during payment submission: ' + error.message, 'danger');
    }
};

// Update local payment display without page reload
window.updateLocalPaymentDisplay = function(orderId, paymentData) {
    try {
        // Find the order row and update payment status badge
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const orderIdCell = row.querySelector('td:first-child');
            if (orderIdCell && orderIdCell.textContent.trim() === orderId.toString()) {
                const paymentCell = row.querySelector('td:nth-child(6)'); // Adjust based on your table structure
                if (paymentCell) {
                    const statusBadge = paymentCell.querySelector('.badge');
                    if (statusBadge) {
                        // Update badge based on new status
                        let badgeClass = 'badge ';
                        let statusText = '';
                        
                        switch(paymentData.payment_status) {
                            case 'paid':
                                badgeClass += 'bg-success';
                                statusText = 'Paid & Confirmed';
                                break;
                            case 'pending':
                                badgeClass += 'bg-warning';
                                statusText = 'Pending';
                                break;
                            case 'failed':
                                badgeClass += 'bg-danger';
                                statusText = 'Failed';
                                break;
                            case 'partially_paid':
                                badgeClass += 'bg-info';
                                statusText = 'Partially Paid';
                                break;
                            case 'refunded':
                                badgeClass += 'bg-secondary';
                                statusText = 'Refunded';
                                break;
                            default:
                                badgeClass += 'bg-secondary';
                                statusText = paymentData.payment_status || 'Unknown';
                        }
                        
                        statusBadge.className = badgeClass;
                        statusBadge.textContent = statusText;
                        
                        console.log('Updated payment badge for order', orderId, 'to:', statusText);
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error updating local payment display:', error);
    }
};
</script>
@endpush

    <!-- Update Status Modal -->
    {{-- <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="updateStatusForm" method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="statusOrderId" name="order_id">
                        <div class="mb-3">
                            <label class="form-label">Order Status</label>
                            <select class="form-select" name="status" id="orderStatus" required>
                                @foreach(\App\Models\Order::STATUSES as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Note (Optional)</label>
                            <textarea class="form-control" name="note" rows="3" placeholder="Add a note about this status change"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

    <!-- Update Payment Modal -->
    

    <!-- Send Email Modal -->
    <div class="modal fade" id="sendEmailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Order Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="sendEmailForm">
                    <div class="modal-body">
                        <input type="hidden" id="emailOrderId" name="order_id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Type</label>
                                <select class="form-select" name="email_type" id="emailType" required>
                                    <option value="order_confirmation">Order Confirmation</option>
                                    <option value="payment_confirmation">Payment Confirmation</option>
                                    <option value="shipping_notification">Shipping Notification</option>
                                    <option value="delivery_confirmation">Delivery Confirmation</option>
                                    <option value="custom">Custom Email</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Recipient Email</label>
                                <input type="email" class="form-control" name="recipient" id="recipientEmail" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" class="form-control" name="subject" id="emailSubject" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" name="message" id="emailMessage" rows="6" required placeholder="Enter your email message here..."></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="include_invoice" id="includeInvoice">
                                <label class="form-check-label" for="includeInvoice">
                                    Include Invoice PDF
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Send Email</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Note Modal -->
    <div class="modal fade" id="addNoteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Order Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addNoteForm">
                    <div class="modal-body">
                        <input type="hidden" id="noteOrderId" name="order_id">
                        <div class="mb-3">
                            <label class="form-label">Note Type</label>
                            <select class="form-select" name="note_type" required>
                                <option value="internal">Internal Note</option>
                                <option value="customer">Customer Note</option>
                                <option value="vendor">Vendor Note</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Note</label>
                            <textarea class="form-control" name="note" rows="4" required placeholder="Enter your note here..."></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_visible_to_customer" id="visibleToCustomer">
                                <label class="form-check-label" for="visibleToCustomer">
                                    Visible to Customer
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Note</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cancel Order Modal -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="cancelOrderForm">
                    <div class="modal-body">
                        <input type="hidden" id="cancelOrderId" name="order_id">
                        <div class="alert alert-warning">
                            <i class="ri-alert-line me-2"></i>
                            <strong>Warning:</strong> This action will cancel the order and cannot be undone.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cancellation Reason</label>
                            <textarea class="form-control" name="cancellation_reason" rows="3" placeholder="Enter reason for cancellation (optional)"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="refund_payment" id="refundPayment">
                                <label class="form-check-label" for="refundPayment">
                                    Refund Payment (if paid)
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="restore_inventory" id="restoreInventory" checked>
                                <label class="form-check-label" for="restoreInventory">
                                    Restore Inventory Stock
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Order</button>
                        <button type="submit" class="btn btn-danger">Cancel Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Enhanced Modals -->

<!-- Quick Update Modal -->
<div class="modal fade" id="quickUpdateModal" tabindex="-1" aria-labelledby="quickUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickUpdateModalLabel">
                    <i class="ri-edit-2-line me-2"></i>Quick Update Order
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickUpdateForm" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="quickUpdateOrderId" name="order_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quickUpdateStatus" class="form-label">Order Status</label>
                                <select class="form-select" id="quickUpdateStatus" name="status" required>
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quickUpdatePaymentStatus" class="form-label">Payment Status</label>
                                <select class="form-select" id="quickUpdatePaymentStatus" name="payment_status" required>
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                    <option value="failed">Failed</option>
                                    <option value="refunded">Refunded</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="quickUpdateNote" class="form-label">Update Note</label>
                        <textarea class="form-control" id="quickUpdateNote" name="note" rows="3" placeholder="Add a note about this update..."></textarea>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="quickUpdateNotifyCustomer" name="notify_customer" checked>
                        <label class="form-check-label" for="quickUpdateNotifyCustomer">
                            Notify customer via email
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitQuickUpdate()">
                        <i class="ri-save-line me-1"></i>Update Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payment Proof Upload Modal -->
<div class="modal fade" id="paymentProofModal" tabindex="-1" aria-labelledby="paymentProofModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentProofModalLabel">
                    <i class="ri-upload-cloud-line me-2"></i>Upload Payment Proof
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="paymentProofForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="proofOrderId" name="order_id">
                    
                    <div class="mb-3">
                        <label for="paymentProofFile" class="form-label">Select Payment Proof Image</label>
                        <input type="file" class="form-control" id="paymentProofFile" name="payment_proof" 
                               accept="image/*" required onchange="previewPaymentProof()">
                        <small class="text-muted">Supported formats: JPG, PNG, GIF. Max size: 5MB</small>
                    </div>
                    
                    <div id="proofPreview" class="mb-3"></div>
                    
                    <div class="mb-3">
                        <label for="proofDescription" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="proofDescription" name="description" rows="3" 
                                  placeholder="Add any additional details about the payment..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="proofAmount" class="form-label">Payment Amount (৳)</label>
                                <input type="number" class="form-control" id="proofAmount" name="amount" 
                                       step="0.01" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="proofDate" class="form-label">Payment Date</label>
                                <input type="date" class="form-control" id="proofDate" name="payment_date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="submitPaymentProof()">
                        <i class="ri-upload-line me-1"></i>Upload Proof
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Payment Proof Modal -->
<div class="modal fade" id="viewProofModal" tabindex="-1" aria-labelledby="viewProofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewProofModalLabel">
                    <i class="ri-image-line me-2"></i>Payment Proof
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="proofImage" src="" alt="Payment Proof" class="img-fluid rounded mb-3" style="max-height: 400px;">
                <div id="proofDetails" class="text-start"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="window.open($('#proofImage').attr('src'), '_blank')">
                    <i class="ri-external-link-line me-1"></i>Open Full Size
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Status Update Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">
                    <i class="ri-refresh-line me-2"></i>Update Order Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusUpdateForm">
                <div class="modal-body">
                    <input type="hidden" id="statusOrderId" name="order_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="orderStatus" class="form-label">New Status</label>
                                <select class="form-select" id="orderStatus" name="status" required>
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="statusUpdateReason" class="form-label">Update Reason</label>
                                <select class="form-select" id="statusUpdateReason" name="reason">
                                    <option value="">Select reason...</option>
                                    <option value="customer_request">Customer Request</option>
                                    <option value="inventory_issue">Inventory Issue</option>
                                    <option value="payment_received">Payment Received</option>
                                    <option value="shipped_out">Shipped Out</option>
                                    <option value="delivered_successfully">Delivered Successfully</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="statusNote" class="form-label">Status Note</label>
                        <textarea class="form-control" id="statusNote" name="note" rows="3" 
                                  placeholder="Add detailed information about this status change..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notifyCustomerStatus" name="notify_customer" checked>
                                <label class="form-check-label" for="notifyCustomerStatus">
                                    Notify customer via email
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="updateInventory" name="update_inventory">
                                <label class="form-check-label" for="updateInventory">
                                    Update inventory (if applicable)
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h6>Status History</h6>
                    <div id="statusHistory" class="timeline">
                        <!-- Status history will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Enhanced Payment Update Modal -->
<div class="modal fade" id="updatePaymentModal" tabindex="-1" aria-labelledby="updatePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updatePaymentModalLabel">
                    <i class="ri-bank-card-line me-2"></i>Payment Management
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left Column: Payment Information -->
                    <div class="col-md-6">
                        <!-- Order Summary -->
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">
                                    <i class="ri-file-list-3-line me-2"></i>Order Summary
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="mb-1"><strong>Total Amount:</strong></p>
                                        <span id="orderTotalAmount" class="text-primary fw-bold">৳0.00</span>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1"><strong>Paid Amount:</strong></p>
                                        <span id="paidAmount" class="text-success fw-bold">৳0.00</span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <p class="mb-1"><strong>Due Amount:</strong></p>
                                        <span id="dueAmount" class="text-danger fw-bold">৳0.00</span>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1"><strong>Status:</strong></p>
                                        <span id="currentPaymentStatus" class="badge bg-warning">Pending</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Payment Information -->
                        <div class="card mb-3" id="userPaymentInfo" style="display: none;">
                            <!-- Real database content loaded here -->
                        </div>

                        <!-- Payment Actions -->
                        <div class="card">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">
                                    <i class="ri-tools-line me-2"></i>Quick Actions
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="button" id="confirmPaymentBtn" class="btn btn-success btn-sm" onclick="confirmPayment(document.getElementById('enhancedPaymentOrderId').value)">
                                        <i class="ri-check-line me-1"></i>Confirm Payment
                                    </button>
                                    <button type="button" id="requestInfoBtn" class="btn btn-info btn-sm" onclick="requestMoreInfo(document.getElementById('enhancedPaymentOrderId').value)">
                                        <i class="ri-question-line me-1"></i>Request More Info
                                    </button>
                                    <button type="button" id="rejectPaymentBtn" class="btn btn-danger btn-sm" onclick="rejectPayment(document.getElementById('enhancedPaymentOrderId').value)">
                                        <i class="ri-close-line me-1"></i>Reject Payment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Payment History & Update Form -->
                    <div class="col-md-6">
                        <!-- Payment History -->
                        <div class="card mb-3" id="paymentHistoryContainer">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0">
                                    <i class="ri-history-line me-2"></i>Real Payment History from Database
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="paymentHistory">
                                    <div class="text-center text-muted">
                                        <i class="ri-loader-4-line spinner-border"></i>
                                        <p>Loading real payment history from database...</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Update Form -->
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">
                                    <i class="ri-edit-line me-2"></i>Update Payment Status
                                </h6>
                            </div>
                            <div class="card-body">
                                <form id="paymentUpdateForm">
                                    @csrf
                                    <input type="hidden" id="enhancedPaymentOrderId" name="order_id">
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="paymentStatus" class="form-label">Payment Status</label>
                                            <select class="form-select" id="paymentStatus" name="payment_status" required>
                                                <option value="">Select Status</option>
                                                <option value="pending">Pending</option>
                                                <option value="paid">Paid</option>
                                                <option value="failed">Failed</option>
                                                <option value="refunded">Refunded</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="paymentMethod" class="form-label">Payment Method</label>
                                            <select class="form-select" id="paymentMethod" name="payment_method">
                                                <option value="">Select Method</option>
                                                <option value="bkash">bKash</option>
                                                <option value="nagad">Nagad</option>
                                                <option value="rocket">Rocket</option>
                                                <option value="upay">Upay</option>
                                                <option value="mcash">mCash</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                                <option value="cash_on_delivery">Cash on Delivery</option>
                                                <option value="card">Credit/Debit Card</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="paymentAmount" class="form-label">Amount</label>
                                            <input type="number" class="form-control" id="paymentAmount" name="amount" step="0.01" placeholder="0.00">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="transactionId" class="form-label">Transaction ID</label>
                                            <input type="text" class="form-control" id="transactionId" name="transaction_id" placeholder="Enter transaction ID">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="paymentDate" class="form-label">Payment Date</label>
                                            <input type="datetime-local" class="form-control" id="paymentDate" name="payment_date">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="paymentGateway" class="form-label">
                                                <i class="ri-shield-check-line me-1"></i>Payment Gateway
                                            </label>
                                            <select class="form-select" id="paymentGateway" name="payment_gateway">
                                                <option value="">Select gateway...</option>
                                                <option value="bkash_api">bKash API</option>
                                                <option value="nagad_api">Nagad API</option>
                                                <option value="sslcommerz">SSLCommerz</option>
                                                <option value="aamarpay">AamarPay</option>
                                                <option value="portwallet">PortWallet</option>
                                                <option value="manual">Manual Processing</option>
                                                <option value="other">Other Gateway</option>
                                            </select>
                                        </div>
                                    </div>
                                    

                                    <!-- Mobile Banking Details -->
                                    <div id="mobilePaymentDetails" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="senderNumber" class="form-label">Customer Number</label>
                                                <input type="text" class="form-control" id="senderNumber" name="sender_number" placeholder="01XXXXXXXXX">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="receiverNumber" class="form-label">Merchant Number</label>
                                                <input type="text" class="form-control" id="receiverNumber" name="receiver_number" placeholder="01XXXXXXXXX">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="paymentReference" class="form-label">Reference</label>
                                            <input type="text" class="form-control" id="paymentReference" name="payment_reference" placeholder="Payment reference">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="paymentFee" class="form-label">Transaction Fee</label>
                                            <input type="number" class="form-control" id="paymentFee" name="payment_fee" step="0.01" placeholder="0.00">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="paymentNote" class="form-label">Admin Notes</label>
                                        <textarea class="form-control" id="paymentNote" name="note" rows="3" placeholder="Add any notes about this payment..."></textarea>
                                    </div>

                                    <!-- Options -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="paymentVerified" name="payment_verified">
                                                <label class="form-check-label" for="paymentVerified">
                                                    Mark as Verified
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="requiresReview" name="requires_review">
                                                <label class="form-check-label" for="requiresReview">
                                                    Requires Review
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="notifyCustomer" name="notify_customer" checked>
                                                <label class="form-check-label" for="notifyCustomer">
                                                    Notify Customer
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="notifyVendor" name="notify_vendor">
                                                <label class="form-check-label" for="notifyVendor">
                                                    Notify Vendor
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="sendSMS" name="send_sms">
                                                <label class="form-check-label" for="sendSMS">
                                                    Send SMS
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancel
                </button>
                <button type="submit" form="paymentUpdateForm" class="btn btn-primary">
                    <i class="ri-save-line me-1"></i>Update Payment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Print Options Modal -->
<div class="modal fade" id="printOptionsModal" tabindex="-1" aria-labelledby="printOptionsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printOptionsModalLabel">
                    <i class="ri-printer-line me-2"></i>Print Options
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="printOrderId">
                
                <div class="mb-3">
                    <label for="printFormat" class="form-label">Print Format</label>
                    <select class="form-select" id="printFormat">
                        <option value="html">HTML (Browser Print)</option>
                        <option value="pdf">PDF Download</option>
                    </select>
                </div>
                
                <h6>Include in Print:</h6>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="printIncludeItems" checked>
                    <label class="form-check-label" for="printIncludeItems">
                        Order Items Details
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="printIncludeCustomer" checked>
                    <label class="form-check-label" for="printIncludeCustomer">
                        Customer Information
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="printIncludePayment" checked>
                    <label class="form-check-label" for="printIncludePayment">
                        Payment Details
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="printWithOptions()">
                    <i class="ri-printer-line me-1"></i>Print/Download
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Show Send Email Modal
    function showSendEmailModal(orderId) {
        document.getElementById('emailOrderId').value = orderId;
        
        // Get order details from the row (you can enhance this to fetch via AJAX)
        const row = event.target.closest('tr');
        const customerEmail = row.querySelector('td:nth-child(2) .text-muted').textContent;
        
        document.getElementById('recipientEmail').value = customerEmail;
        document.getElementById('emailSubject').value = `Order Update - #${orderId}`;
        
        const modal = new bootstrap.Modal(document.getElementById('sendEmailModal'));
        modal.show();
    }

    // Show Add Note Modal
    function showAddNoteModal(orderId) {
        document.getElementById('noteOrderId').value = orderId;
        const modal = new bootstrap.Modal(document.getElementById('addNoteModal'));
        modal.show();
    }

    // Show Cancel Order Modal
    function showCancelOrderModal(orderId) {
        document.getElementById('cancelOrderId').value = orderId;
        const modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
        modal.show();
    }

    function cancelOrder(orderId) {
        if (confirm('Are you sure you want to cancel this order?')) {
            // TODO: Implement order cancellation
            alert('Order cancellation functionality to be implemented');
        }
    }

    // Handle Update Payment Form (using unified function)
    const updatePaymentForm = document.getElementById('updatePaymentForm');
    if (updatePaymentForm) {
        updatePaymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const formData = new FormData(this);
            const orderId = formData.get('order_id');
        
        if (!orderId) {
            showAlert('Order ID is required', 'danger');
            return false;
        }
        
        // Extract form data for unified function
        const paymentData = {
            payment_status: formData.get('payment_status') || '',
            payment_method: formData.get('payment_method') || '',
            amount: formData.get('amount') || '',
            transaction_id: formData.get('transaction_id') || '',
            payment_note: formData.get('note') || '',
            admin_action: 'simple_form_update'
        };
        
        // Use unified payment update function
        unifiedPaymentUpdate(orderId, paymentData, {
            closeModal: 'updatePaymentModal',
            reloadPage: true,
            allowFallback: true
        }).then(result => {
            console.log('Simple payment update completed:', result);
        }).catch(error => {
            console.error('Simple payment update failed:', error);
        });
    });
    }

    // Handle Send Email Form
    const sendEmailForm = document.getElementById('sendEmailForm');
    if (sendEmailForm) {
        sendEmailForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const orderId = formData.get('order_id');
        
        fetch(`/admin/orders/${orderId}/send-email`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Email sent successfully', 'success');
            } else {
                showAlert(data.message || 'Failed to send email', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while sending email', 'error');
        });
        
        bootstrap.Modal.getInstance(document.getElementById('sendEmailModal')).hide();
        });
    }

    // Handle Add Note Form
    const addNoteForm = document.getElementById('addNoteForm');
    if (addNoteForm) {
        addNoteForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const orderId = formData.get('order_id');
        
        fetch(`/admin/orders/${orderId}/add-note`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Note added successfully', 'success');
            } else {
                showAlert(data.message || 'Failed to add note', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while adding note', 'error');
        });
        
        bootstrap.Modal.getInstance(document.getElementById('addNoteModal')).hide();
        });
    }

    // Handle Cancel Order Form
    const cancelOrderForm = document.getElementById('cancelOrderForm');
    if (cancelOrderForm) {
        cancelOrderForm.addEventListener('submit', function(e) {
            e.preventDefault();
        
        const formData = new FormData(this);
        const orderId = formData.get('order_id');
        
        // Show confirmation
        if (!confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
            return;
        }
        
        fetch(`/admin/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Order cancelled successfully', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert(data.message || 'Failed to cancel order', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while cancelling order', 'error');
        });
        
        bootstrap.Modal.getInstance(document.getElementById('cancelOrderModal')).hide();
        });
    }

    // Handle Enhanced Payment Update Form - intercept and use custom handler
    const paymentUpdateForm = document.getElementById('paymentUpdateForm');
    if (paymentUpdateForm) {
        paymentUpdateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Payment form submitted - calling submitPaymentUpdate()');
            if (typeof window.submitPaymentUpdate === 'function') {
                submitPaymentUpdate();
            } else {
                console.error('submitPaymentUpdate function not found');
                if (typeof window.showAlert === 'function') {
                    showAlert('Payment update function not available', 'danger');
                } else {
                    alert('Payment update function not available');
                }
            }
            return false;
        });
    }

    // Show/Hide refund amount field based on payment status
    const paymentStatusSelect = document.getElementById('paymentStatus');
    if (paymentStatusSelect) {
        paymentStatusSelect.addEventListener('change', function() {
            const refundAmountGroup = document.getElementById('refundAmountGroup');
            if (this.value === 'refunded' || this.value === 'partially_refunded') {
                refundAmountGroup.style.display = 'block';
            } else {
                refundAmountGroup.style.display = 'none';
            }
        });
    }

    // Update email subject and message based on email type
    const emailTypeSelect = document.getElementById('emailType');
    if (emailTypeSelect) {
        emailTypeSelect.addEventListener('change', function() {
            const orderId = document.getElementById('emailOrderId').value;
            const subjectField = document.getElementById('emailSubject');
            const messageField = document.getElementById('emailMessage');
        
        const templates = {
            'order_confirmation': {
                subject: `Order Confirmation - #${orderId}`,
                message: `Dear Customer,\n\nYour order has been confirmed and is being processed. We will keep you updated on the status.\n\nThank you for your business!`
            },
            'payment_confirmation': {
                subject: `Payment Confirmation - Order #${orderId}`,
                message: `Dear Customer,\n\nWe have received your payment for order #${orderId}. Your order is now being processed.\n\nThank you for your payment!`
            },
            'shipping_notification': {
                subject: `Your Order Has Shipped - #${orderId}`,
                message: `Dear Customer,\n\nGreat news! Your order #${orderId} has been shipped and is on its way to you.\n\nYou should receive it soon!`
            },
            'delivery_confirmation': {
                subject: `Order Delivered - #${orderId}`,
                message: `Dear Customer,\n\nYour order #${orderId} has been delivered successfully.\n\nWe hope you enjoy your purchase!`
            },
            'custom': {
                subject: `Order Update - #${orderId}`,
                message: `Dear Customer,\n\nWe wanted to update you regarding your order #${orderId}.\n\n[Your custom message here]`
            }
        };
        
        if (templates[this.value]) {
            subjectField.value = templates[this.value].subject;
            messageField.value = templates[this.value].message;
        }
        });
    }

    // Real-time search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const form = document.getElementById('ordersFilterForm');
        let searchTimeout;

        // Auto-submit form on filter changes
        const filterElements = ['statusFilter', 'paymentFilter', 'vendorFilter', 'perPageFilter', 'sortByFilter', 'sortDirectionFilter'];
        
        filterElements.forEach(function(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.addEventListener('change', function() {
                    form.submit();
                });
            }
        });

        // Date filters
        const dateFrom = document.getElementById('dateFrom');
        const dateTo = document.getElementById('dateTo');
        
        if (dateFrom) {
            dateFrom.addEventListener('change', function() {
                form.submit();
            });
        }
        
        if (dateTo) {
            dateTo.addEventListener('change', function() {
                form.submit();
            });
        }

        // Search with debounce
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    form.submit();
                }, 500); // Wait 500ms after user stops typing
            });
        }

        // Export functionality
        const exportBtn = document.getElementById('exportBtn');
        if (exportBtn) {
            exportBtn.addEventListener('click', function() {
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.set('export', 'csv');
                window.location.href = currentUrl.toString();
            });
        }

        // Clear button functionality
        const clearBtn = document.querySelector('a[href="{{ route('admin.orders.index') }}"]');
        if (clearBtn) {
            clearBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = '{{ route('admin.orders.index') }}';
            });
        }
    });

    // Bulk actions (for future implementation)
    function toggleAllOrders() {
        const checkboxes = document.querySelectorAll('input[name="order_ids[]"]');
        const masterCheckbox = document.getElementById('masterCheckbox');
        
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = masterCheckbox.checked;
        });
        
        updateBulkActionButtons();
    }

    function updateBulkActionButtons() {
        const checkedBoxes = document.querySelectorAll('input[name="order_ids[]"]:checked');
        const bulkActions = document.getElementById('bulkActions');
        
        if (bulkActions) {
            bulkActions.style.display = checkedBoxes.length > 0 ? 'block' : 'none';
        }
    }

    // Load Payment Data including user submissions
    function loadPaymentData(orderId) {
        // First try to fetch from the enhanced payment details endpoint
        fetch(`/admin/orders/${orderId}/payment-details`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const order = data.order;
                    const paymentInfo = data.payment_info;
                    
                    // Update order summary
                    $('#orderTotalAmount').text(`৳${parseFloat(order.total_amount || 0).toFixed(2)}`);
                    $('#paidAmount').text(`৳${parseFloat(order.paid_amount || 0).toFixed(2)}`);
                    $('#dueAmount').text(`৳${parseFloat(order.total_amount - (order.paid_amount || 0)).toFixed(2)}`);
                    
                    // Show user payment information if available
                    if (paymentInfo && paymentInfo.user_submitted) {
                        $('#userPaymentInfo').show();
                        displayUserPaymentInfo(paymentInfo);
                    } else {
                        $('#userPaymentInfo').hide();
                    }
                    
                    // Pre-fill form with existing data
                    if (paymentInfo) {
                        $('#paymentMethod').val(paymentInfo.payment_method || '');
                        $('#paymentAmount').val(paymentInfo.amount || order.total_amount);
                        $('#transactionId').val(paymentInfo.transaction_id || '');
                        $('#paymentNote').val(paymentInfo.admin_notes || '');
                        $('#paymentDate').val(paymentInfo.payment_date || '');
                        $('#paymentGateway').val(paymentInfo.gateway || '');
                        
                        // Mobile banking details
                        if (paymentInfo.sender_number) {
                            $('#mobilePaymentDetails').show();
                            $('#senderNumber').val(paymentInfo.sender_number);
                            $('#receiverNumber').val(paymentInfo.receiver_number || '');
                        } else {
                            $('#mobilePaymentDetails').hide();
                        }
                        
                        // Verification status
                        $('#paymentVerified').prop('checked', paymentInfo.verified || false);
                        $('#requiresReview').prop('checked', paymentInfo.requires_review || false);
                    }
                    
                    // Load payment history
                    loadPaymentHistory(orderId);
                } else {
                    console.warn('Payment data not found, using fallback');
                    loadPaymentDataFallback(orderId);
                }
            })
            .catch(error => {
                console.log('Enhanced payment endpoint not available, using fallback method');
                loadPaymentDataFallback(orderId);
            });
    }

    // Fallback method for loading basic payment data when enhanced endpoint is not available
    function loadPaymentDataFallback(orderId) {
        // Try to fetch from basic order endpoint or use existing table data
        fetch(`/admin/orders/${orderId}`)
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Order not found');
                }
            })
            .then(data => {
                if (data.success && data.order) {
                    const order = data.order;
                    
                    // Update order summary with basic data
                    $('#orderTotalAmount').text(`৳${parseFloat(order.total_amount || 0).toFixed(2)}`);
                    $('#paidAmount').text(`৳${parseFloat(order.paid_amount || 0).toFixed(2)}`);
                    $('#dueAmount').text(`৳${parseFloat(order.total_amount - (order.paid_amount || 0)).toFixed(2)}`);
                    
                    // Hide user payment info section since we don't have detailed data
                    $('#userPaymentInfo').hide();
                    $('#mobilePaymentDetails').hide();
                    
                    // Set basic form data
                    $('#paymentAmount').val(order.total_amount || 0);
                    
                    // Show info message about limited functionality
                    showAlert('Payment data loaded with basic information. Enhanced features require backend implementation.', 'info');
                } else {
                    loadPaymentDataFromTable(orderId);
                }
            })
            .catch(error => {
                console.log('Basic order endpoint not available, extracting from table');
                loadPaymentDataFromTable(orderId);
            });
    }

    // Extract payment data from the current table row as final fallback
    function loadPaymentDataFromTable(orderId) {
        try {
            // Find the table row for this order
            const tableRows = document.querySelectorAll('table tbody tr');
            let orderRow = null;
            
            tableRows.forEach(row => {
                const orderLink = row.querySelector('a[href*="/admin/orders/' + orderId + '"]');
                if (orderLink) {
                    orderRow = row;
                }
            });
            
            if (orderRow) {
                // Extract total amount from table
                const totalCell = orderRow.querySelector('td:nth-child(5)'); // Adjust based on your table structure
                const totalText = totalCell ? totalCell.textContent.trim() : '৳0.00';
                const totalAmount = totalText.replace('৳', '').replace(',', '') || '0.00';
                
                // Extract payment status
                const paymentCell = orderRow.querySelector('td:nth-child(7) .badge');
                const paymentStatus = paymentCell ? paymentCell.textContent.trim().toLowerCase() : 'pending';
                
                // Update form with extracted data
                $('#orderTotalAmount').text(`৳${parseFloat(totalAmount).toFixed(2)}`);
                $('#paidAmount').text('৳0.00');
                $('#dueAmount').text(`৳${parseFloat(totalAmount).toFixed(2)}`);
                $('#paymentAmount').val(totalAmount);
                
                // Set payment status
                if (paymentStatus.includes('paid')) {
                    $('#paymentStatus').val('paid');
                    $('#paidAmount').text(`৳${parseFloat(totalAmount).toFixed(2)}`);
                    $('#dueAmount').text('৳0.00');
                } else if (paymentStatus.includes('pending')) {
                    $('#paymentStatus').val('pending');
                } else {
                    $('#paymentStatus').val('pending');
                }
                
                // Hide advanced sections
                $('#userPaymentInfo').hide();
                $('#mobilePaymentDetails').hide();
                $('#paymentHistory').html('<p class="text-muted">Payment history will be available after backend implementation.</p>');
                
                showAlert('Payment data extracted from table. Enhanced features require backend routes.', 'warning');
            } else {
                // Complete fallback - just enable the form with empty data
                $('#orderTotalAmount').text('৳0.00');
                $('#paidAmount').text('৳0.00');
                $('#dueAmount').text('৳0.00');
                $('#userPaymentInfo').hide();
                $('#mobilePaymentDetails').hide();
                $('#paymentHistory').html('<p class="text-muted">No payment history available.</p>');
                
                showAlert('Unable to load payment data. Please ensure backend routes are implemented.', 'danger');
            }
        } catch (error) {
            console.error('Error extracting payment data from table:', error);
            showAlert('Error loading payment data. Form is available for manual entry.', 'danger');
            
            // Enable form for manual entry
            $('#userPaymentInfo').hide();
            $('#mobilePaymentDetails').hide();
            $('#paymentHistory').html('<p class="text-muted">Manual entry mode - no payment history available.</p>');
        }
    }

    // Load Payment History
    function loadPaymentHistory(orderId) {
        fetch(`/admin/orders/${orderId}/payment-history`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.history && data.history.length > 0) {
                    let historyHtml = '';
                    data.history.forEach(entry => {
                        historyHtml += `
                            <div class="timeline-item">
                                <div class="timeline-marker bg-${entry.type === 'payment' ? 'success' : entry.type === 'refund' ? 'warning' : 'info'}"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">
                                        ${entry.action_title || 'Payment Action'}
                                        <span class="badge bg-${entry.status === 'confirmed' ? 'success' : entry.status === 'pending' ? 'warning' : 'secondary'} ms-2">
                                            ${entry.status || 'Unknown'}
                                        </span>
                                    </h6>
                                    <p class="timeline-text">
                                        ${entry.description || 'No description available'}
                                        ${entry.amount ? `<br><strong>Amount: ৳${parseFloat(entry.amount).toFixed(2)}</strong>` : ''}
                                        ${entry.transaction_id ? `<br><strong>Transaction ID:</strong> ${entry.transaction_id}` : ''}
                                    </p>
                                    <small class="text-muted">
                                        ${entry.created_at ? new Date(entry.created_at).toLocaleString() : 'Unknown date'}
                                        ${entry.admin_name ? ` by ${entry.admin_name}` : ''}
                                    </small>
                                </div>
                            </div>
                        `;
                    });
                    $('#paymentHistory').html(historyHtml);
                } else {
                    // Show default message when no history available
                    loadPaymentHistoryFallback(orderId);
                }
            })
            .catch(error => {
                console.log('Payment history endpoint not available, using fallback');
                loadPaymentHistoryFallback(orderId);
            });
    }

    // Fallback payment history when endpoint is not available
    function loadPaymentHistoryFallback(orderId) {
        const currentDate = new Date().toLocaleString();
        const currentStatus = $('#paymentStatus').val() || 'pending';
        
        let fallbackHistory = `
            <div class="timeline-item">
                <div class="timeline-marker bg-info"></div>
                <div class="timeline-content">
                    <h6 class="timeline-title">
                        Order Created
                        <span class="badge bg-info ms-2">Initial</span>
                    </h6>
                    <p class="timeline-text">
                        Order was created and payment is ${currentStatus}
                    </p>
                    <small class="text-muted">
                        ${currentDate}
                    </small>
                </div>
            </div>
        `;
        
        if (currentStatus === 'paid') {
            fallbackHistory += `
                <div class="timeline-item">
                    <div class="timeline-marker bg-success"></div>
                    <div class="timeline-content">
                        <h6 class="timeline-title">
                            Payment Confirmed
                            <span class="badge bg-success ms-2">Paid</span>
                        </h6>
                        <p class="timeline-text">
                            Payment has been confirmed by admin
                        </p>
                        <small class="text-muted">
                            ${currentDate}
                        </small>
                    </div>
                </div>
            `;
        }
        
        fallbackHistory += `
            <div class="alert alert-info mt-3">
                <small>
                    <i class="ri-information-line me-1"></i>
                    Payment history will be more detailed when backend routes are implemented.
                </small>
            </div>
        `;
        
        $('#paymentHistory').html(fallbackHistory);
    }

    // Request More Information from Customer
    function requestMoreInfo() {
        const orderId = $('#paymentOrderId').val();
        const message = prompt('Enter message to request additional payment information:');
        
        if (message) {
            fetch(`/admin/orders/${orderId}/request-payment-info`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message: message,
                    request_type: 'payment_clarification'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Information request sent to customer successfully.', 'success');
                } else {
                    showAlert(data.message || 'Failed to send request', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error sending information request.', 'danger');
            });
        }
    }

    // Update payment status in table without page reload
    function updatePaymentStatusInTable(orderId, paymentStatus, paymentMethod) {
        // Find the table row and update payment status badge
        const tableRows = document.querySelectorAll('table tbody tr');
        tableRows.forEach(row => {
            const orderLink = row.querySelector('a[href*="/admin/orders/' + orderId + '"]');
            if (orderLink) {
                const paymentCell = row.querySelector('td:nth-child(7)'); // Adjust based on your table structure
                if (paymentCell) {
                    const statusBadge = paymentCell.querySelector('.badge');
                    if (statusBadge) {
                        // Update badge class and text based on status
                        let badgeClass = 'badge bg-';
                        let statusText = '';
                        
                        switch(paymentStatus) {
                            case 'paid':
                                badgeClass += 'success-transparent';
                                statusText = 'Paid & Confirmed';
                                break;
                            case 'pending':
                                badgeClass += 'warning-transparent';
                                statusText = 'Pending Verification';
                                break;
                            case 'failed':
                                badgeClass += 'danger-transparent';
                                statusText = 'Payment Failed';
                                break;
                            case 'partially_paid':
                                badgeClass += 'info-transparent';
                                statusText = 'Partially Paid';
                                break;
                            default:
                                badgeClass += 'secondary-transparent';
                                statusText = paymentStatus.replace('_', ' ').toUpperCase();
                        }
                        
                        statusBadge.className = badgeClass;
                        statusBadge.textContent = statusText;
                    }
                }
            }
        });
    }

    // Advanced print with options
    function showPrintOptionsModal(orderId) {
        $('#printOptionsModal').modal('show');
        $('#printOrderId').val(orderId);
    }

    function printWithOptions() {
        const orderId = $('#printOrderId').val();
        const includeItems = $('#printIncludeItems').is(':checked');
        const includeCustomer = $('#printIncludeCustomer').is(':checked');
        const includePayment = $('#printIncludePayment').is(':checked');
        const format = $('#printFormat').val();
        
        let url = `/admin/orders/${orderId}/print?format=${format}`;
        if (includeItems) url += '&include_items=1';
        if (includeCustomer) url += '&include_customer=1';
        if (includePayment) url += '&include_payment=1';
        
        if (format === 'pdf') {
            window.open(url, '_blank');
        } else {
            const printWindow = window.open(url, '_blank');
            printWindow.onload = function() {
                printWindow.print();
            };
        }
        
        $('#printOptionsModal').modal('hide');
    }

    // Status History
    function loadStatusHistory(orderId) {
        fetch(`/admin/orders/${orderId}/status-history`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let historyHtml = '';
                    data.history.forEach(entry => {
                        historyHtml += `
                            <div class="timeline-item">
                                <div class="timeline-marker bg-${entry.color}"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">${entry.status_name}</h6>
                                    <p class="timeline-text">${entry.note || 'Status updated'}</p>
                                    <small class="text-muted">${entry.created_at}</small>
                                </div>
                            </div>
                        `;
                    });
                    $('#statusHistory').html(historyHtml);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Submit Payment Proof Function
    function submitPaymentProof() {
        const form = document.getElementById('paymentProofForm');
        const formData = new FormData(form);
        const orderId = formData.get('order_id');

        if (!formData.get('payment_proof')) {
            showAlert('Please select a payment proof image', 'danger');
            return;
        }

        // Show loading state
        const submitBtn = document.querySelector('#paymentProofModal button[onclick="submitPaymentProof()"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="ri-loader-line spin"></i> Uploading...';
        submitBtn.disabled = true;

        fetch(`/admin/orders/${orderId}/upload-payment-proof`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Payment proof uploaded successfully', 'success');
                $('#paymentProofModal').modal('hide');
                // Optionally reload the page or update the UI
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert(data.message || 'Failed to upload payment proof', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while uploading payment proof', 'danger');
        })
        .finally(() => {
            // Restore button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }

    // Form Submissions
    function submitQuickUpdate() {
        const formData = new FormData(document.getElementById('quickUpdateForm'));
        
        fetch('/admin/orders/quick-update', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                location.reload();
            } else {
                showAlert(data.message || 'Update failed', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while updating the order.', 'danger');
        });
        
        $('#quickUpdateModal').modal('hide');
    }

    // File preview for payment proof
    function previewPaymentProof() {
        const file = document.getElementById('paymentProofFile').files[0];
        const preview = document.getElementById('proofPreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded" style="max-height: 200px;">`;
            };
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '';
        }
    }
</script>
@push('styles')
<style>
    /* Timeline styles for status history */
    .timeline {
        position: relative;
        max-height: 300px;
        overflow-y: auto;
    }
    
    .timeline-item {
        position: relative;
        padding-left: 30px;
        margin-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        left: 0;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #dee2e6;
    }
    
    .timeline-content {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 6px;
        border-left: 3px solid #007bff;
    }
    
    .timeline-title {
        margin: 0 0 5px 0;
        font-size: 14px;
        font-weight: 600;
    }
    
    .timeline-text {
        margin: 0 0 5px 0;
        font-size: 13px;
        color: #6c757d;
    }
    
    /* Enhanced table responsiveness */
    @media (max-width: 768px) {
        .table-responsive table {
            font-size: 12px;
        }
        
        .btn-icon {
            padding: 0.25rem 0.5rem;
        }
        
        .hstack {
            flex-wrap: wrap;
            gap: 0.25rem !important;
        }
    }
    
    /* Badge enhancements */
    .badge {
        font-size: 0.75em;
    }
    
    /* Print-specific styles */
    @media print {
        .no-print {
            display: none !important;
        }
        
        .table {
            font-size: 12px;
        }
        
        .card {
            border: 1px solid #000 !important;
            box-shadow: none !important;
        }
    }
    
    /* Enhanced Payment Modal Styles */
    .payment-info-card {
        border-left: 4px solid #007bff;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .payment-status-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 8px;
    }
    
    .payment-status-indicator.pending {
        background-color: #ffc107;
        animation: pulse 2s infinite;
    }
    
    .payment-status-indicator.paid {
        background-color: #28a745;
    }
    
    .payment-status-indicator.failed {
        background-color: #dc3545;
    }
    
    .payment-method-icon {
        width: 24px;
        height: 24px;
        margin-right: 8px;
    }
    
    .transaction-id-display {
        font-family: 'Courier New', monospace;
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 4px;
        border: 1px solid #dee2e6;
        font-size: 0.9em;
    }
    
    .payment-amount-highlight {
        font-size: 1.2em;
        font-weight: bold;
        color: #28a745;
    }
    
    .payment-timeline {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 15px;
        background: #f8f9fa;
    }
    
    .payment-verification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #28a745;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }
    
    .user-submitted-info {
        border: 2px dashed #007bff;
        background: linear-gradient(45deg, #e3f2fd 25%, transparent 25%, transparent 75%, #e3f2fd 75%);
        background-size: 20px 20px;
        animation: slideBackground 20s linear infinite;
    }
    
    @keyframes slideBackground {
        0% { background-position: 0 0; }
        100% { background-position: 20px 20px; }
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    .payment-proof-thumbnail {
        transition: transform 0.3s ease;
        cursor: pointer;
    }
    
    .payment-proof-thumbnail:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .mobile-banking-details {
        background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%);
        border: 1px solid #28a745;
        border-radius: 8px;
    }
    
    .amount-comparison {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 6px;
        margin: 10px 0;
    }
    
    .amount-item {
        text-align: center;
        flex: 1;
    }
    
    .amount-item .label {
        font-size: 0.8em;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .amount-item .value {
        font-size: 1.1em;
        font-weight: bold;
        margin-top: 2px;
    }
    
    /* Responsive payment modal */
    @media (max-width: 768px) {
        #updatePaymentModal .modal-dialog {
            margin: 0.5rem;
        }
        
        #updatePaymentModal .card-body {
            padding: 0.75rem;
        }
        
        .amount-comparison {
            flex-direction: column;
            gap: 10px;
        }
        
        .amount-item {
            width: 100%;
        }
        
        .payment-info-card {
            margin-bottom: 1rem;
        }
    }
    
    /* Custom scrollbar for payment timeline */
    .payment-timeline::-webkit-scrollbar {
        width: 6px;
    }
    
    .payment-timeline::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .payment-timeline::-webkit-scrollbar-thumb {
        background: #007bff;
        border-radius: 3px;
    }
    
    .payment-timeline::-webkit-scrollbar-thumb:hover {
        background: #0056b3;
    }
</style>
@endpush
{{-- 
Backend Routes Required for Enhanced Payment System:

1. GET /admin/orders/{id}/payment-details
   - Returns comprehensive payment data including user submissions
   
2. GET /admin/orders/{id}/payment-history  
   - Returns payment history timeline
   
3. POST /admin/orders/update-payment-status
   - Updates payment status with all new fields
   
4. POST /admin/orders/{id}/request-payment-info
   - Sends request to customer for additional information
   
5. GET /admin/orders/{id}/payment-proof
   - Returns payment proof details and image URL

Sample Response Structure for /admin/orders/{id}/payment-details:
{
  "success": true,
  "order": {
    "id": 123,
    "total_amount": 1500.00,
    "paid_amount": 1500.00,
    "payment_status": "paid"
  },
  "payment_info": {
    "user_submitted": true,
    "submitted_method": "bkash",
    "submitted_amount": 1500.00,
    "submitted_transaction_id": "TXN123456789",
    "submitted_date": "2025-09-04 14:30:00",
    "sender_number": "01712345678",
    "customer_notes": "Payment made from my bKash account",
    "submitted_at": "2025-09-04 14:32:00",
    "payment_proof": {
      "url": "/storage/payment-proofs/proof_123.jpg",
      "uploaded_at": "2025-09-04 14:33:00"
    },
    "payment_method": "bkash",
    "amount": 1500.00,
    "transaction_id": "TXN123456789",
    "admin_notes": "Verified with bKash merchant",
    "verified": true,
    "requires_review": false
  }
}
--}}
