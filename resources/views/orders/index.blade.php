@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">My Orders</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Orders</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order History</h5>
                </div>
                <div class="card-body">
                    <!-- Success Message -->
                    <div class="alert alert-success">
                        <h5><i class="ti ti-check-circle me-2"></i>Order Placed Successfully!</h5>
                        <p class="mb-0">Thank you for your order. We'll process it shortly and send you a confirmation email.</p>
                    </div>

                    <!-- Orders Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Payment Method</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <span class="fw-semibold text-primary">{{ $order->order_number }}</span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'delivered' ? 'success' : 'info') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">${{ number_format($order->total_amount, 2) }}</span>
                                    </td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewOrderDetails('{{ $order->order_number }}', {{ $order->id }})">
                                                <i class="ti ti-eye me-1"></i>Details
                                            </button>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-success dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="ti ti-file-text me-1"></i>Invoice
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('invoice.show', $order->id) }}" target="_blank">
                                                        <i class="ti ti-eye me-2"></i>View Invoice
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="{{ route('invoice.responsive', $order->id) }}" target="_blank">
                                                        <i class="ti ti-device-mobile me-2"></i>Responsive View
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item" href="{{ route('invoice.view-pdf', $order->id) }}" target="_blank">
                                                        <i class="ti ti-file-pdf me-2"></i>View PDF
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="{{ route('invoice.pdf', $order->id) }}">
                                                        <i class="ti ti-download me-2"></i>Download PDF
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ti ti-shopping-cart-off" style="font-size: 2rem;"></i>
                                            <p class="mt-2 mb-0">No orders found</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($orders->count() === 0)
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="ti ti-shopping-cart-off" style="font-size: 4rem; color: #6c757d;"></i>
                        </div>
                        <h5 class="text-muted">No Orders Found</h5>
                        <p class="text-muted mb-4">You haven't placed any orders yet.</p>
                        <a href="{{ route('shop.grid') }}" class="btn btn-primary">
                            <i class="ti ti-shopping-cart me-2"></i>Start Shopping
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Legend -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Order Status Legend</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-6 mb-2">
                            <span class="badge bg-warning me-2">Pending</span>
                            <small>Order received, waiting for processing</small>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <span class="badge bg-info me-2">Processing</span>
                            <small>Order is being prepared</small>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <span class="badge bg-primary me-2">Shipped</span>
                            <small>Order has been shipped</small>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <span class="badge bg-success me-2">Delivered</span>
                            <small>Order has been delivered</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Order Information -->
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Order Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="fw-semibold">Order Number:</td>
                                <td id="modalOrderNumber">ORD-{{ date('Ymd') }}-0001</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Date:</td>
                                <td>{{ date('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Status:</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Payment Method:</td>
                                <td>Online Payment</td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Shipping Information -->
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Shipping Address</h6>
                        <address class="mb-0">
                            <strong>John Doe</strong><br>
                            123 Main Street<br>
                            City, State 12345<br>
                            Phone: (555) 123-4567<br>
                            Email: john@example.com
                        </address>
                    </div>
                </div>
                
                <hr>
                
                <!-- Order Items -->
                <h6 class="fw-bold mb-3">Order Items</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('assets/img/product/1.png') }}" alt="Product" class="me-2" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                        <div>
                                            <div class="fw-semibold">Sony WH-1000XM5 Headphones</div>
                                            <small class="text-muted">Color: Black, Size: One Size</small>
                                        </div>
                                    </div>
                                </td>
                                <td>1</td>
                                <td>$349.99</td>
                                <td>$349.99</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Order Summary -->
                <div class="row justify-content-end">
                    <div class="col-md-4">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td>Subtotal:</td>
                                <td class="text-end">$349.99</td>
                            </tr>
                            <tr>
                                <td>Shipping:</td>
                                <td class="text-end">FREE</td>
                            </tr>
                            <tr>
                                <td>Discount (SAVE10):</td>
                                <td class="text-end text-success">-$35.00</td>
                            </tr>
                            <tr class="fw-bold border-top">
                                <td>Total:</td>
                                <td class="text-end">$314.99</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <div class="btn-group">
                    <a href="#" class="btn btn-outline-success" onclick="viewFullInvoice()" target="_blank">
                        <i class="ti ti-file-text me-1"></i>View Invoice
                    </a>
                    <a href="#" class="btn btn-outline-info" onclick="viewPdfInvoice()" target="_blank">
                        <i class="ti ti-file-pdf me-1"></i>View PDF
                    </a>
                    <button type="button" class="btn btn-outline-primary" onclick="printInvoice()">
                        <i class="ti ti-printer me-1"></i>Print
                    </button>
                    <button type="button" class="btn btn-primary" onclick="downloadPdfInvoice()">
                        <i class="ti ti-download me-1"></i>Download PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
}

.badge {
    font-size: 0.75rem;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.alert-success {
    background-color: #d1f2eb;
    border-color: #a3e4d7;
    color: #0c5460;
}

.text-primary {
    color: #0d6efd !important;
}

.fw-semibold {
    font-weight: 600;
}

/* Toast notification styles */
.toast-notification {
    min-width: 300px;
    max-width: 400px;
}

.toast-notification .alert {
    margin-bottom: 0;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border: none;
}

/* Loading spinner styles */
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

/* Modal improvements */
.modal-lg {
    max-width: 900px;
}

.modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

/* Print styles */
@media print {
    .modal, .modal-backdrop {
        display: none !important;
    }
}

/* Button loading state */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-hide success message after 10 seconds
    setTimeout(function() {
        $('.alert-success').fadeOut('slow');
    }, 10000);
});

// Global variable to store current order ID
let currentOrderId = null;

// View order details function
function viewOrderDetails(orderNumber, orderId) {
    currentOrderId = orderId; // Store for invoice functions
    
    // Show loading in modal
    $('#orderDetailsModal').modal('show');
    $('#orderDetailsModal .modal-body').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading order details...</p></div>');
    
    // Fetch order details from API
    $.ajax({
        url: `/api/orders/${orderId}`,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                populateOrderModal(response.order);
            } else {
                showModalError('Failed to load order details: ' + response.message);
            }
        },
        error: function(xhr) {
            const errorMessage = xhr.responseJSON?.message || 'Failed to load order details';
            showModalError(errorMessage);
        }
    });
}

function populateOrderModal(order) {
    const shippingAddr = order.shipping_address || {};
    const items = order.items || [];
    
    // Build items HTML
    let itemsHtml = '';
    if (items.length > 0) {
        items.forEach(item => {
            itemsHtml += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${item.image ? '/assets/img/' + item.image : '/assets/img/products/default.jpg'}" alt="Product" class="me-2" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                            <div>
                                <div class="fw-semibold">${item.product_name || item.name || 'Product'}</div>
                                <small class="text-muted">${item.size ? 'Size: ' + item.size + ', ' : ''}${item.color ? 'Color: ' + item.color : ''}</small>
                            </div>
                        </div>
                    </td>
                    <td>${item.quantity}</td>
                    <td>$${parseFloat(item.price).toFixed(2)}</td>
                    <td>$${parseFloat(item.total).toFixed(2)}</td>
                </tr>
            `;
        });
    } else {
        itemsHtml = '<tr><td colspan="4" class="text-center text-muted">No items found</td></tr>';
    }
    
    // Get status badge class
    const getStatusBadge = (status) => {
        switch(status) {
            case 'pending': return 'bg-warning';
            case 'processing': return 'bg-info';
            case 'shipped': return 'bg-primary';
            case 'delivered': return 'bg-success';
            case 'cancelled': return 'bg-danger';
            default: return 'bg-secondary';
        }
    };
    
    const modalContent = `
        <div class="row">
            <!-- Order Information -->
            <div class="col-md-6">
                <h6 class="fw-bold mb-3">Order Information</h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="fw-semibold">Order Number:</td>
                        <td>${order.order_number}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Date:</td>
                        <td>${order.created_at}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Status:</td>
                        <td><span class="badge ${getStatusBadge(order.status)}">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</span></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Payment Method:</td>
                        <td>${order.payment_method.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Payment Status:</td>
                        <td><span class="badge ${getStatusBadge(order.payment_status)}">${order.payment_status.charAt(0).toUpperCase() + order.payment_status.slice(1)}</span></td>
                    </tr>
                </table>
            </div>
            
            <!-- Shipping Information -->
            <div class="col-md-6">
                <h6 class="fw-bold mb-3">Shipping Address</h6>
                <address class="mb-0">
                    <strong>${shippingAddr.first_name || ''} ${shippingAddr.last_name || ''}</strong><br>
                    ${shippingAddr.address || 'N/A'}<br>
                    ${shippingAddr.city || ''}, ${shippingAddr.postal_code || ''}<br>
                    ${shippingAddr.phone ? 'Phone: ' + shippingAddr.phone + '<br>' : ''}
                    ${shippingAddr.email ? 'Email: ' + shippingAddr.email : ''}
                </address>
            </div>
        </div>
        
        <hr>
        
        <!-- Order Items -->
        <h6 class="fw-bold mb-3">Order Items</h6>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsHtml}
                </tbody>
            </table>
        </div>
        
        <!-- Order Summary -->
        <div class="row justify-content-end">
            <div class="col-md-4">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td>Subtotal:</td>
                        <td class="text-end">$${parseFloat(order.subtotal || 0).toFixed(2)}</td>
                    </tr>
                    <tr>
                        <td>Shipping:</td>
                        <td class="text-end">${parseFloat(order.shipping_amount || 0) === 0 ? 'FREE' : '$' + parseFloat(order.shipping_amount).toFixed(2)}</td>
                    </tr>
                    ${parseFloat(order.discount_amount || 0) > 0 ? `
                    <tr>
                        <td>Discount:</td>
                        <td class="text-end text-success">-$${parseFloat(order.discount_amount).toFixed(2)}</td>
                    </tr>
                    ` : ''}
                    ${parseFloat(order.tax_amount || 0) > 0 ? `
                    <tr>
                        <td>Tax:</td>
                        <td class="text-end">$${parseFloat(order.tax_amount).toFixed(2)}</td>
                    </tr>
                    ` : ''}
                    <tr class="fw-bold border-top">
                        <td>Total:</td>
                        <td class="text-end">$${parseFloat(order.total_amount).toFixed(2)}</td>
                    </tr>
                </table>
            </div>
        </div>
    `;
    
    $('#orderDetailsModal .modal-body').html(modalContent);
    $('#orderDetailsModalLabel').text(`Order Details - ${order.order_number}`);
}

function showModalError(message) {
    const errorHtml = `
        <div class="text-center py-4">
            <i class="ti ti-alert-circle text-danger" style="font-size: 3rem;"></i>
            <h5 class="mt-3 text-danger">Error</h5>
            <p class="text-muted">${message}</p>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    `;
        $('#orderDetailsModal .modal-body').html(errorHtml);
}

// View full invoice in new tab
function viewFullInvoice() {
    if (!currentOrderId) {
        alert('No order selected');
        return;
    }
    
    const invoiceUrl = `/invoice/${currentOrderId}`;
    window.open(invoiceUrl, '_blank');
    return false; // Prevent default link behavior
}

// View PDF invoice in new tab
function viewPdfInvoice() {
    if (!currentOrderId) {
        alert('No order selected');
        return;
    }
    
    const pdfUrl = `/invoice/${currentOrderId}/view-pdf`;
    window.open(pdfUrl, '_blank');
    return false; // Prevent default link behavior
}

// Download PDF invoice
function downloadPdfInvoice() {
    if (!currentOrderId) {
        alert('No order selected');
        return;
    }
    
    // Show loading
    const originalText = event.target.innerHTML;
    event.target.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Generating PDF...';
    event.target.disabled = true;
    
    try {
        // Direct download via web route
        const downloadUrl = `/invoice/${currentOrderId}/pdf`;
        
        // Create a temporary link and click it
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showToast('PDF invoice download started!', 'success');
        
        // Reset button after short delay
        setTimeout(() => {
            event.target.innerHTML = originalText;
            event.target.disabled = false;
        }, 1000);
        
    } catch (error) {
        showToast('Failed to download PDF invoice', 'error');
        // Reset button
        event.target.innerHTML = originalText;
        event.target.disabled = false;
    }
}

// Download invoice function (HTML)
function downloadInvoice() {
    if (!currentOrderId) {
        alert('No order selected');
        return;
    }
    
    // Show loading
    const originalText = event.target.innerHTML;
    event.target.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Generating...';
    event.target.disabled = true;
    
    try {
        // Method 1: Direct download via web route
        const downloadUrl = `/invoice/${currentOrderId}/download`;
        
        // Create a temporary link and click it
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.download = `invoice-${currentOrderId}.html`;
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showToast('Invoice download started!', 'success');
        
        // Reset button after short delay
        setTimeout(() => {
            event.target.innerHTML = originalText;
            event.target.disabled = false;
        }, 1000);
        
    } catch (error) {
        // Fallback to API method
        $.ajax({
            url: `/api/orders/${currentOrderId}/invoice`,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Create a blob and download it
                    const blob = new Blob([response.html], { type: 'text/html' });
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = `invoice-${response.order_number}.html`;
                    link.click();
                    window.URL.revokeObjectURL(url);
                    
                    showToast('Invoice downloaded successfully!', 'success');
                } else {
                    showToast('Failed to generate invoice: ' + response.message, 'error');
                }
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON?.message || 'Failed to generate invoice';
                showToast(errorMessage, 'error');
            },
            complete: function() {
                // Reset button
                event.target.innerHTML = originalText;
                event.target.disabled = false;
            }
        });
    }
}

// Print invoice function
function printInvoice() {
    if (!currentOrderId) {
        alert('No order selected');
        return;
    }
    
    // Show loading
    const originalText = event.target.innerHTML;
    event.target.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Loading...';
    event.target.disabled = true;
    
    try {
        // Method 1: Open invoice page in new window for printing
        const printUrl = `/invoice/${currentOrderId}#print`;
        const printWindow = window.open(printUrl, '_blank', 'width=800,height=600');
        
        if (printWindow) {
            showToast('Opening print dialog...', 'success');
            
            // Reset button after short delay
            setTimeout(() => {
                event.target.innerHTML = originalText;
                event.target.disabled = false;
            }, 1000);
        } else {
            throw new Error('Popup blocked');
        }
        
    } catch (error) {
        // Fallback to API method
        $.ajax({
            url: `/api/orders/${currentOrderId}/invoice`,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Create a new window with the invoice content for printing
                    const printWindow = window.open('', '_blank', 'width=800,height=600');
                    printWindow.document.write(response.html);
                    printWindow.document.close();
                    
                    // Wait for the content to load, then print
                    printWindow.onload = function() {
                        printWindow.focus();
                        printWindow.print();
                        
                        // Close the window after printing (with delay for printing to complete)
                        setTimeout(() => {
                            printWindow.close();
                        }, 2000);
                    };
                    
                    showToast('Opening print dialog...', 'success');
                } else {
                    showToast('Failed to generate invoice: ' + response.message, 'error');
                }
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON?.message || 'Failed to generate invoice';
                showToast(errorMessage, 'error');
            },
            complete: function() {
                // Reset button
                event.target.innerHTML = originalText;
                event.target.disabled = false;
            }
        });
    }
}

// Toast notification function
function showToast(message, type = 'info') {
    // Remove existing toasts
    $('.toast-notification').remove();
    
    const toastClass = type === 'success' ? 'alert-success' : (type === 'error' ? 'alert-danger' : 'alert-info');
    const toastIcon = type === 'success' ? 'ti-check-circle' : (type === 'error' ? 'ti-alert-circle' : 'ti-info-circle');
    
    const toast = $(`
        <div class="toast-notification position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
            <div class="alert ${toastClass} alert-dismissible fade show" role="alert">
                <i class="ti ${toastIcon} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    `);
    
    $('body').append(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.fadeOut(() => toast.remove());
    }, 5000);
}
</script>
@endpush
