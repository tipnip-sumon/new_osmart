@extends('admin.layouts.app')

@section('title', 'Order Details - ' . $order['id'])

@section('content')
    <!-- Invoice Header - Complete Dedicated Print Layout -->
    <div class="invoice-header d-none d-print-block">
        <div class="company-header">
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <!-- Company Logo -->
                <img src="{{ asset('admin-assets/images/brand-logos/desktop-logo.png') }}" alt="Company Logo" style="height: 60px; margin-right: 15px;">
                <div>
                    <h2 style="margin: 0; font-size: 24px; font-weight: bold;">Your Company Name</h2>
                    <p style="margin: 0; color: #666;">Multi-Vendor E-commerce Platform</p>
                </div>
            </div>
        </div>
        
        <div class="invoice-title">INVOICE</div>
        <div class="invoice-details">Invoice #: {{ $order['id'] }}</div>
        <div class="invoice-details">Date: {{ date('F j, Y', strtotime($order['order_date'])) }}</div>
        
        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
            <div style="width: 48%;">
                <strong>FROM:</strong><br>
                <div style="border: 1px solid #ddd; padding: 10px; background: #f8f9fa;">
                    <strong>Your Company Name</strong><br>
                    123 Business Street<br>
                    Dhaka, Bangladesh 1207<br>
                    Phone: +880 1700-000000<br>
                    Email: info@company.com<br>
                    Website: www.company.com
                </div>
            </div>
            <div style="width: 48%;">
                <strong>TO:</strong><br>
                <div style="border: 1px solid #ddd; padding: 10px; background: #f8f9fa;">
                    {{ $order['customer'] }}<br>
                    {{ $order['billing_address']['street'] ?? $order['billing_address']['address'] ?? 'N/A' }}<br>
                    {{ $order['billing_address']['city'] ?? 'N/A' }}, {{ $order['billing_address']['state'] ?? 'N/A' }} {{ $order['billing_address']['zip'] ?? $order['billing_address']['postal_code'] ?? 'N/A' }}<br>
                    {{ $order['billing_address']['country'] ?? 'N/A' }}<br>
                    Email: {{ $order['customer_email'] }}<br>
                    Phone: {{ $order['customer_phone'] }}
                </div>
            </div>
        </div>
        
        <!-- Invoice Items Table for Print -->
        <div style="margin-top: 30px;">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #000; padding: 8px; background: #f8f9fa; text-align: left;">Product</th>
                        <th style="border: 1px solid #000; padding: 8px; background: #f8f9fa; text-align: center;">Price</th>
                        <th style="border: 1px solid #000; padding: 8px; background: #f8f9fa; text-align: center;">Qty</th>
                        <th style="border: 1px solid #000; padding: 8px; background: #f8f9fa; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order['items'] as $item)
                    <tr>
                        <td style="border: 1px solid #000; padding: 8px;">
                            <strong>{{ $item['product_name'] }}</strong><br>
                            <small>Product ID: {{ $item['product_id'] }}</small>
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">৳{{ number_format($item['price'], 2) }}</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $item['quantity'] }}</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: right;">৳{{ number_format($item['total'], 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="border: 1px solid #000; padding: 8px; text-align: center;">No items found</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="border: 1px solid #000; padding: 8px; text-align: right; font-weight: bold;">Subtotal:</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: right; font-weight: bold;">৳{{ number_format($order['subtotal'], 2) }}</td>
                    </tr>
                    @if($order['discount'] > 0)
                    <tr>
                        <td colspan="3" style="border: 1px solid #000; padding: 8px; text-align: right; font-weight: bold; color: #dc3545;">Discount:</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: right; font-weight: bold; color: #dc3545;">-৳{{ number_format($order['discount'], 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="3" style="border: 1px solid #000; padding: 8px; text-align: right; font-weight: bold;">Tax:</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: right; font-weight: bold;">৳{{ number_format($order['tax'], 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border: 1px solid #000; padding: 8px; text-align: right; font-weight: bold;">Shipping:</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: right; font-weight: bold;">৳{{ number_format($order['shipping'], 2) }}</td>
                    </tr>
                    <tr style="background: #f8f9fa;">
                        <td colspan="3" style="border: 2px solid #000; padding: 12px; text-align: right; font-weight: bold; font-size: 16px;">TOTAL:</td>
                        <td style="border: 2px solid #000; padding: 12px; text-align: right; font-weight: bold; font-size: 16px;">৳{{ number_format($order['total'], 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <!-- Payment Information -->
        <div style="margin-top: 20px; display: flex; justify-content: space-between;">
            <div style="width: 48%;">
                <strong>Payment Information:</strong><br>
                <div style="border: 1px solid #ddd; padding: 10px; background: #f8f9fa; margin-top: 5px;">
                    <strong>Method:</strong> {{ $order['payment_method'] }}<br>
                    <strong>Status:</strong> {{ $order['status'] }}<br>
                    @if($order['tracking_number'])
                    <strong>Tracking:</strong> {{ $order['tracking_number'] }}<br>
                    @endif
                    <strong>Order Date:</strong> {{ date('F j, Y', strtotime($order['order_date'])) }}
                </div>
            </div>
            @if($order['notes'])
            <div style="width: 48%;">
                <strong>Notes:</strong><br>
                <div style="border: 1px solid #ddd; padding: 10px; background: #f8f9fa; margin-top: 5px; min-height: 80px;">
                    {{ $order['notes'] }}
                </div>
            </div>
            @endif
        </div>
        
        <!-- Thank You Message -->
        <div style="text-align: center; margin-top: 30px; padding: 20px; border-top: 2px solid #000;">
            <h3 style="margin: 0; color: #000;">Thank You for Your Business!</h3>
            <p style="margin: 5px 0 0 0; color: #666;">We appreciate your trust in our services.</p>
        </div>
    </div>

    <div class="container-fluid">`
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Order Details</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $order['id'] }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-check-line me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="row">
            <!-- Order Summary -->
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Order #{{ $order['id'] }}
                        </div>
                        <div class="d-flex align-items-center">
                            <!-- Currency Setting -->
                            <div class="me-3">
                                <small class="text-muted">Currency:</small>
                                <select class="form-select form-select-sm d-inline-block" style="width: auto;" id="currencySelect">
                                    <option value="BDT">৳ BDT</option>
                                    <option value="USD">$ USD</option>
                                    <option value="EUR">€ EUR</option>
                                </select>
                            </div>
                            <button class="btn btn-primary btn-sm me-2" onclick="printProfessionalInvoice()">
                                <i class="ri-printer-line"></i> Print Invoice
                            </button>
                            <a href="{{ route('admin.orders.printable-invoice', $order['id']) }}" class="btn btn-secondary btn-sm me-2" target="_blank">
                                <i class="ri-file-text-line"></i> Printable Invoice
                            </a>
                            <a href="{{ route('admin.orders.printable-invoice.download', $order['id']) }}" class="btn btn-outline-secondary btn-sm me-2">
                                <i class="ri-download-line"></i> Download Printable PDF
                            </a>
                            <a href="{{ route('admin.orders.professional-invoice', $order['id']) }}" class="btn btn-success btn-sm me-2" target="_blank">
                                <i class="ri-file-pdf-line"></i> Professional PDF
                            </a>
                            <a href="{{ route('admin.orders.professional-invoice.download', $order['id']) }}" class="btn btn-info btn-sm me-2">
                                <i class="ri-download-line"></i> Download Professional PDF
                            </a>
                            <a href="{{ route('admin.orders.simple-invoice', $order['id']) }}" class="btn btn-outline-success btn-sm me-2" target="_blank">
                                <i class="ri-file-text-line"></i> Simple PDF
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="dropdown">
                                    <i class="ri-more-2-fill"></i> Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="editPrices()">Edit Prices</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="showSendEmailModal()">Send Invoice Email</a></li>
                                    <li><a class="dropdown-item" href="#">Add Tracking Number</a></li>
                                    <li><a class="dropdown-item" href="#">Add Note</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#">Cancel Order</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Order Items -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>PV Points</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($order['items'] as $index => $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if(!empty($item['product_image']))
                                                    @php
                                                        $imagePath = $item['product_image'];
                                                        // Handle different image formats
                                                        if (is_string($imagePath) && !str_starts_with($imagePath, 'http')) {
                                                            $imagePath = asset('storage/' . $imagePath);
                                                        } elseif (is_array($imagePath) && isset($imagePath[0])) {
                                                            $imagePath = asset('storage/' . $imagePath[0]);
                                                        } else {
                                                            $imagePath = asset('admin-assets/images/ecommerce/1.jpg');
                                                        }
                                                    @endphp
                                                    <img src="{{ $imagePath }}" 
                                                         alt="Product" class="me-2 rounded" 
                                                         style="width: 40px; height: 40px; object-fit: cover;"
                                                         onerror="this.src='{{ asset('admin-assets/images/ecommerce/1.jpg') }}'">
                                                @else
                                                    <img src="{{ asset('admin-assets/images/ecommerce/1.jpg') }}" 
                                                         alt="No Image" class="me-2 rounded" 
                                                         style="width: 40px; height: 40px; object-fit: cover; opacity: 0.7;">
                                                @endif
                                                <div>
                                                    <div class="fw-semibold">{{ $item['product_name'] }}</div>
                                                    <small class="text-muted">Product ID: {{ $item['product_id'] }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="price-display">৳{{ number_format($item['price'], 2) }}</span>
                                            <input type="number" class="form-control form-control-sm price-edit d-none" value="{{ $item['price'] }}" step="0.01">
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-transparent">{{ number_format($item['pv_points']) }} PV</span>
                                        </td>
                                        <td>
                                            <span class="qty-display">{{ $item['quantity'] }}</span>
                                            <input type="number" class="form-control form-control-sm qty-edit d-none" value="{{ $item['quantity'] }}" min="1">
                                        </td>
                                        <td class="fw-semibold">
                                            <span class="total-display">৳{{ number_format($item['total'], 2) }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">
                                            No items found for this order.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end fw-semibold">Subtotal:</td>
                                        <td class="fw-semibold">
                                            <span class="subtotal-display">৳{{ number_format($order['subtotal'], 2) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end fw-semibold">
                                            Less / Discount:
                                            <button class="btn btn-sm btn-outline-secondary ms-1" onclick="editDiscount()" title="Edit Discount">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                        </td>
                                        <td class="fw-semibold text-danger">
                                            <span class="discount-display">-৳{{ number_format($order['discount'] ?? 0, 2) }}</span>
                                            <input type="number" class="form-control form-control-sm discount-edit d-none" value="{{ $order['discount'] ?? 0 }}" step="0.01" min="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end fw-semibold">Tax:</td>
                                        <td class="fw-semibold">
                                            <span class="tax-display">৳{{ number_format($order['tax'], 2) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end fw-semibold">Shipping:</td>
                                        <td class="fw-semibold">
                                            <span class="shipping-display">৳{{ number_format($order['shipping'], 2) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="table-active">
                                        <td colspan="4" class="text-end fw-bold">Total:</td>
                                        <td class="fw-bold fs-16">
                                            <span class="grand-total-display">৳{{ number_format($order['total'], 2) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end fw-semibold">Total PV Points:</td>
                                        <td class="fw-semibold">
                                            <span class="badge bg-primary">{{ $order['pv_points'] }} PV</span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- MLM Commission Info -->
                        <div class="row mt-4">
                            <div class="col-xl-12">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading"><i class="ri-information-line me-2"></i>MLM Commission Breakdown</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Direct Commission:</strong><br>
                                            <span class="text-success">${{ number_format($order['commission_info']['direct_commission'], 2) }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Level 2 Commission:</strong><br>
                                            <span class="text-success">${{ number_format($order['commission_info']['level_2_commission'], 2) }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Total Commissions:</strong><br>
                                            <span class="text-success fw-bold">${{ number_format($order['commission_info']['total_commission'], 2) }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>PV Points:</strong><br>
                                            <span class="badge bg-primary">{{ $order['commission_info']['pv_points'] }} PV</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Info Sidebar -->
            <div class="col-xl-4">
                <!-- Order Status -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Order Status</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.orders.update-status', $order['id']) }}" method="POST" id="statusUpdateForm">
                            @csrf
                            <!-- Hidden fields for dynamic values -->
                            <input type="hidden" name="discount_amount" id="discount_amount" value="{{ $order['discount'] ?? 0 }}">
                            <input type="hidden" name="updated_items" id="updated_items" value="">
                            
                            <div class="mb-3">
                                <label class="form-label">Current Status</label>
                                <select class="form-select" name="status">
                                    <option value="pending" {{ $order['status'] == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $order['status'] == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="processing" {{ $order['status'] == 'Processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order['status'] == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order['status'] == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order['status'] == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="refunded" {{ $order['status'] == 'Refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Notes (optional)</label>
                                <textarea class="form-control" name="note" rows="4" placeholder="Add a note about this status change or any additional information..."></textarea>
                                @if($order['notes'])
                                <div class="mt-2">
                                    <small class="text-muted">Previous notes:</small>
                                    <div class="border rounded p-2 bg-light">
                                        <small>{{ $order['notes'] }}</small>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
                        </form>

                        <div class="mt-3">
                            <h6>Order Timeline</h6>
                            <ul class="list-unstyled">
                                <li class="d-flex mb-2">
                                    <span class="avatar avatar-xs bg-primary me-2 mt-1">
                                        <i class="ri-shopping-cart-line fs-10"></i>
                                    </span>
                                    <div>
                                        <div class="fw-semibold">Order Placed</div>
                                        <div class="text-muted fs-12">{{ date('M d, Y h:i A', strtotime($order['order_date'])) }}</div>
                                    </div>
                                </li>
                                @if($order['shipped_date'])
                                <li class="d-flex mb-2">
                                    <span class="avatar avatar-xs bg-warning me-2 mt-1">
                                        <i class="ri-truck-line fs-10"></i>
                                    </span>
                                    <div>
                                        <div class="fw-semibold">Order Shipped</div>
                                        <div class="text-muted fs-12">{{ date('M d, Y h:i A', strtotime($order['shipped_date'])) }}</div>
                                    </div>
                                </li>
                                @endif
                                @if($order['delivered_date'])
                                <li class="d-flex mb-2">
                                    <span class="avatar avatar-xs bg-success me-2 mt-1">
                                        <i class="ri-check-line fs-10"></i>
                                    </span>
                                    <div>
                                        <div class="fw-semibold">Order Delivered</div>
                                        <div class="text-muted fs-12">{{ date('M d, Y h:i A', strtotime($order['delivered_date'])) }}</div>
                                    </div>
                                </li>
                                @endif
                                @if($order['cancelled_date'])
                                <li class="d-flex mb-2">
                                    <span class="avatar avatar-xs bg-danger me-2 mt-1">
                                        <i class="ri-close-line fs-10"></i>
                                    </span>
                                    <div>
                                        <div class="fw-semibold">Order Cancelled</div>
                                        <div class="text-muted fs-12">{{ date('M d, Y h:i A', strtotime($order['cancelled_date'])) }}</div>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title">Customer Information</div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleCustomerEdit()">
                            <i class="ri-edit-line"></i> Edit
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">Name</label>
                            <div class="customer-display fw-semibold">{{ $order['customer'] }}</div>
                            <input type="text" class="form-control customer-edit d-none" name="customer_name" value="{{ $order['customer'] }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Email</label>
                            <div class="customer-email-display">{{ $order['customer_email'] }}</div>
                            <input type="email" class="form-control customer-edit d-none" name="customer_email" value="{{ $order['customer_email'] }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Phone</label>
                            <div class="customer-phone-display">{{ $order['customer_phone'] }}</div>
                            <input type="text" class="form-control customer-edit d-none" name="customer_phone" value="{{ $order['customer_phone'] }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Payment Method</label>
                            <div class="payment-method-display">{{ $order['payment_method'] }}</div>
                            <select class="form-select customer-edit d-none" name="payment_method">
                                <option value="Cash on Delivery" {{ $order['payment_method'] == 'Cash on Delivery' ? 'selected' : '' }}>Cash on Delivery</option>
                                <option value="bKash" {{ $order['payment_method'] == 'bKash' ? 'selected' : '' }}>bKash</option>
                                <option value="Rocket" {{ $order['payment_method'] == 'Rocket' ? 'selected' : '' }}>Rocket</option>
                                <option value="Nagad" {{ $order['payment_method'] == 'Nagad' ? 'selected' : '' }}>Nagad</option>
                                <option value="Upay" {{ $order['payment_method'] == 'Upay' ? 'selected' : '' }}>Upay</option>
                                <option value="SureCash" {{ $order['payment_method'] == 'SureCash' ? 'selected' : '' }}>SureCash</option>
                                <option value="Mcash" {{ $order['payment_method'] == 'Mcash' ? 'selected' : '' }}>Mcash</option>
                                <option value="Bank Transfer" {{ $order['payment_method'] == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="Credit Card" {{ $order['payment_method'] == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                                <option value="Debit Card" {{ $order['payment_method'] == 'Debit Card' ? 'selected' : '' }}>Debit Card</option>
                                <option value="DBBL Nexus Pay" {{ $order['payment_method'] == 'DBBL Nexus Pay' ? 'selected' : '' }}>DBBL Nexus Pay</option>
                                <option value="BRAC Bank bPay" {{ $order['payment_method'] == 'BRAC Bank bPay' ? 'selected' : '' }}>BRAC Bank bPay</option>
                                <option value="City Bank TouchPay" {{ $order['payment_method'] == 'City Bank TouchPay' ? 'selected' : '' }}>City Bank TouchPay</option>
                                <option value="EBL SkyBanking" {{ $order['payment_method'] == 'EBL SkyBanking' ? 'selected' : '' }}>EBL SkyBanking</option>
                                <option value="Prime Bank PrimePay" {{ $order['payment_method'] == 'Prime Bank PrimePay' ? 'selected' : '' }}>Prime Bank PrimePay</option>
                                <option value="PayPal" {{ $order['payment_method'] == 'PayPal' ? 'selected' : '' }}>PayPal</option>
                                <option value="Stripe" {{ $order['payment_method'] == 'Stripe' ? 'selected' : '' }}>Stripe</option>
                                <option value="Visa" {{ $order['payment_method'] == 'Visa' ? 'selected' : '' }}>Visa</option>
                                <option value="Mastercard" {{ $order['payment_method'] == 'Mastercard' ? 'selected' : '' }}>Mastercard</option>
                                <option value="American Express" {{ $order['payment_method'] == 'American Express' ? 'selected' : '' }}>American Express</option>
                            </select>
                        </div>
                        @if($order['tracking_number'])
                        <div class="mb-3">
                            <label class="form-label text-muted">Tracking Number</label>
                            <div class="tracking-display fw-semibold">{{ $order['tracking_number'] }}</div>
                            <input type="text" class="form-control customer-edit d-none" name="tracking_number" value="{{ $order['tracking_number'] }}">
                        </div>
                        @else
                        <div class="mb-3">
                            <label class="form-label text-muted">Tracking Number</label>
                            <div class="tracking-display text-muted">Not assigned</div>
                            <input type="text" class="form-control customer-edit d-none" name="tracking_number" placeholder="Enter tracking number">
                        </div>
                        @endif
                        <div class="customer-edit d-none">
                            <button type="button" class="btn btn-success btn-sm me-2" onclick="saveCustomerInfo()">Save Changes</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="cancelCustomerEdit()">Cancel</button>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title">Shipping Address</div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleShippingEdit()">
                            <i class="ri-edit-line"></i> Edit
                        </button>
                    </div>
                    <div class="card-body">
                        @if($order['shipping_address'] && is_array($order['shipping_address']))
                        <div class="shipping-display">
                            <address class="mb-0">
                                <strong>{{ $order['shipping_address']['name'] ?? 'N/A' }}</strong><br>
                                {{ $order['shipping_address']['street'] ?? $order['shipping_address']['address'] ?? 'N/A' }}<br>
                                {{ $order['shipping_address']['city'] ?? 'N/A' }}, {{ $order['shipping_address']['state'] ?? 'N/A' }} {{ $order['shipping_address']['zip'] ?? $order['shipping_address']['postal_code'] ?? 'N/A' }}<br>
                                {{ $order['shipping_address']['country'] ?? 'N/A' }}
                            </address>
                        </div>
                        <div class="shipping-edit d-none">
                            <div class="mb-2">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="shipping_name" value="{{ $order['shipping_address']['name'] ?? '' }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="shipping_address" value="{{ $order['shipping_address']['street'] ?? $order['shipping_address']['address'] ?? '' }}">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="shipping_city" value="{{ $order['shipping_address']['city'] ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">State</label>
                                    <input type="text" class="form-control" name="shipping_state" value="{{ $order['shipping_address']['state'] ?? '' }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Postal Code</label>
                                    <input type="text" class="form-control" name="shipping_postal_code" value="{{ $order['shipping_address']['zip'] ?? $order['shipping_address']['postal_code'] ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Country</label>
                                    <input type="text" class="form-control" name="shipping_country" value="{{ $order['shipping_address']['country'] ?? '' }}">
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-success btn-sm me-2" onclick="saveShippingInfo()">Save Changes</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="cancelShippingEdit()">Cancel</button>
                            </div>
                        </div>
                        @else
                        <div class="shipping-display">
                            <p class="text-muted mb-0">No shipping address provided</p>
                        </div>
                        <div class="shipping-edit d-none">
                            <div class="mb-2">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="shipping_name" placeholder="Enter name">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="shipping_address" placeholder="Enter address">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="shipping_city" placeholder="Enter city">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">State</label>
                                    <input type="text" class="form-control" name="shipping_state" placeholder="Enter state">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Postal Code</label>
                                    <input type="text" class="form-control" name="shipping_postal_code" placeholder="Enter postal code">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Country</label>
                                    <input type="text" class="form-control" name="shipping_country" placeholder="Enter country">
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-success btn-sm me-2" onclick="saveShippingInfo()">Save Changes</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="cancelShippingEdit()">Cancel</button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Billing Address -->
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title">Billing Address</div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleBillingEdit()">
                            <i class="ri-edit-line"></i> Edit
                        </button>
                    </div>
                    <div class="card-body">
                        @if($order['billing_address'] && is_array($order['billing_address']))
                        <div class="billing-display">
                            <address class="mb-0">
                                <strong>{{ $order['billing_address']['name'] ?? 'N/A' }}</strong><br>
                                {{ $order['billing_address']['street'] ?? $order['billing_address']['address'] ?? 'N/A' }}<br>
                                {{ $order['billing_address']['city'] ?? 'N/A' }}, {{ $order['billing_address']['state'] ?? 'N/A' }} {{ $order['billing_address']['zip'] ?? $order['billing_address']['postal_code'] ?? 'N/A' }}<br>
                                {{ $order['billing_address']['country'] ?? 'N/A' }}
                            </address>
                        </div>
                        <div class="billing-edit d-none">
                            <div class="mb-2">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="billing_name" value="{{ $order['billing_address']['name'] ?? '' }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="billing_address" value="{{ $order['billing_address']['street'] ?? $order['billing_address']['address'] ?? '' }}">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="billing_city" value="{{ $order['billing_address']['city'] ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">State</label>
                                    <input type="text" class="form-control" name="billing_state" value="{{ $order['billing_address']['state'] ?? '' }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Postal Code</label>
                                    <input type="text" class="form-control" name="billing_postal_code" value="{{ $order['billing_address']['zip'] ?? $order['billing_address']['postal_code'] ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Country</label>
                                    <input type="text" class="form-control" name="billing_country" value="{{ $order['billing_address']['country'] ?? '' }}">
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-success btn-sm me-2" onclick="saveBillingInfo()">Save Changes</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="cancelBillingEdit()">Cancel</button>
                            </div>
                        </div>
                        @else
                        <div class="billing-display">
                            <p class="text-muted mb-0">Same as shipping address</p>
                        </div>
                        <div class="billing-edit d-none">
                            <div class="mb-2">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="billing_name" placeholder="Enter name">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="billing_address" placeholder="Enter address">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="billing_city" placeholder="Enter city">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">State</label>
                                    <input type="text" class="form-control" name="billing_state" placeholder="Enter state">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Postal Code</label>
                                    <input type="text" class="form-control" name="billing_postal_code" placeholder="Enter postal code">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Country</label>
                                    <input type="text" class="form-control" name="billing_country" placeholder="Enter country">
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-success btn-sm me-2" onclick="saveBillingInfo()">Save Changes</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="cancelBillingEdit()">Cancel</button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Vendor Information -->
                @if($order['vendor'] !== 'N/A')
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Vendor Information</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="form-label text-muted">Vendor Name</label>
                            <div class="fw-semibold">{{ $order['vendor'] }}</div>
                        </div>
                        @if($order['vendor_shop'] !== 'N/A')
                        <div class="mb-2">
                            <label class="form-label text-muted">Shop Name</label>
                            <div>{{ $order['vendor_shop'] }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Payment History -->
                @if(!empty($order['payment_history']) && count($order['payment_history']) > 0)
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Payment History</div>
                    </div>
                    <div class="card-body">
                        @foreach($order['payment_history'] as $payment)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <div>
                                <div class="fw-semibold">${{ number_format($payment['amount'], 2) }}</div>
                                <small class="text-muted">{{ $payment['method'] }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $payment['status'] === 'completed' ? 'success' : ($payment['status'] === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($payment['status']) }}
                                </span>
                                <div class="text-muted fs-11">{{ date('M d, Y', strtotime($payment['date'])) }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Send Invoice Email Modal -->
    <div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sendEmailModalLabel">Send Invoice Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="sendEmailForm">
                        <div class="mb-3">
                            <label for="recipientEmail" class="form-label">Recipient Email</label>
                            <input type="email" class="form-control" id="recipientEmail" value="{{ $order['customer_email'] }}" required>
                            <div class="form-text">Invoice will be sent to this email address</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Invoice Details</label>
                            <div class="card border-light">
                                <div class="card-body p-2">
                                    <small class="text-muted">
                                        <strong>Invoice #:</strong> {{ $order['id'] }}<br>
                                        <strong>Customer:</strong> {{ $order['customer'] }}<br>
                                        <strong>Total:</strong> ৳{{ number_format($order['total'], 2) }}<br>
                                        <strong>Date:</strong> {{ date('F j, Y', strtotime($order['order_date'])) }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="sendInvoiceEmail()">
                        <i class="ri-mail-send-line"></i> Send Email
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Complete Print Invoice Styles - Dedicated Print Layout */
    @media print {
        @page {
            size: A4;
            margin: 15mm;
            background: white;
        }
        
        /* Reset body for clean print */
        body {
            background: white !important;
            color: #000 !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
            font-size: 12px !important;
            line-height: 1.4 !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        /* Hide all non-invoice elements */
        body > *:not(.invoice-header) {
            display: none !important;
        }
        
        .main-sidebar,
        .navbar,
        .header,
        .app-header,
        .main-header,
        .page-header-breadcrumb,
        .breadcrumb,
        .btn,
        .dropdown,
        .alert,
        .card-header .d-flex,
        .sidebar,
        .app-sidebar,
        .modern-sidebar,
        .sidebar-overlay,
        .footer,
        .container-fluid,
        .row,
        .card,
        .card-header,
        .card-body,
        .table-responsive,
        .modal,
        aside,
        nav,
        .navigation,
        .sidemenu-toggle,
        .header-link,
        .main-header-container,
        .col-xl-4,
        .col-xl-8 {
            display: none !important;
        }
        
        /* Show and position invoice content */
        .invoice-header {
            display: block !important;
            position: relative !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            background: white !important;
            visibility: visible !important;
            z-index: 1 !important;
        }
        
        /* Override Bootstrap d-none class for printing */
        .invoice-header.d-none {
            display: block !important;
        }
        
        /* Ensure UTF-8 characters like ৳ display correctly */
        .invoice-header * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif, 'Arial Unicode MS' !important;
        }
        
        /* Force all text to be black */
        .invoice-header,
        .invoice-header * {
            color: #000 !important;
            background: transparent !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        /* Company logo styling */
        .invoice-header img {
            max-height: 50px !important;
            width: auto !important;
        }
        
        /* Invoice title styling */
        .invoice-title {
            font-size: 28px !important;
            font-weight: bold !important;
            text-align: center !important;
            margin: 15px 0 !important;
            color: #000 !important;
            text-transform: uppercase !important;
            letter-spacing: 2px !important;
        }
        
        /* Invoice details */
        .invoice-details {
            font-size: 14px !important;
            margin: 5px 0 !important;
            color: #000 !important;
            text-align: center !important;
        }
        
        /* Company header */
        .company-header {
            margin-bottom: 20px !important;
        }
        
        .company-header h2 {
            font-size: 24px !important;
            font-weight: bold !important;
            margin: 0 !important;
            color: #000 !important;
        }
        
        .company-header p {
            margin: 0 !important;
            color: #333 !important;
            font-size: 14px !important;
        }
        
        /* Address blocks */
        .invoice-header div[style*="display: flex"] {
            display: flex !important;
            justify-content: space-between !important;
        }
        
        .invoice-header div[style*="width: 48%"] {
            width: 48% !important;
            display: inline-block !important;
            vertical-align: top !important;
        }
        
        .invoice-header div[style*="border: 1px solid #ddd"] {
            border: 2px solid #000 !important;
            padding: 12px !important;
            background: #f9f9f9 !important;
            font-size: 12px !important;
            line-height: 1.4 !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        /* Strong labels */
        .invoice-header strong {
            color: #000 !important;
            font-weight: bold !important;
        }
        
        /* Table styling */
        .invoice-header table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 20px 0 !important;
            font-size: 12px !important;
        }
        
        .invoice-header table th,
        .invoice-header table td {
            border: 2px solid #000 !important;
            padding: 8px !important;
            text-align: left !important;
            color: #000 !important;
            background: white !important;
        }
        
        .invoice-header table th {
            background: #f0f0f0 !important;
            font-weight: bold !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .invoice-header table td[style*="text-align: center"] {
            text-align: center !important;
        }
        
        .invoice-header table td[style*="text-align: right"] {
            text-align: right !important;
        }
        
        /* Total row styling */
        .invoice-header table tr[style*="background: #f8f9fa"] td {
            background: #e9ecef !important;
            font-weight: bold !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .invoice-header table td[style*="border: 2px solid #000"] {
            border: 3px solid #000 !important;
            font-weight: bold !important;
            font-size: 16px !important;
            color: #000 !important;
        }
        
        /* Discount row color fix */
        .invoice-header table td[style*="color: #dc3545"] {
            color: #000 !important;
        }
        
        /* Payment info and notes section */
        .invoice-header div[style*="margin-top: 20px; display: flex"] {
            display: flex !important;
            justify-content: space-between !important;
            margin-top: 20px !important;
        }
        
        /* Payment info box */
        .invoice-header div[style*="margin-top: 5px"] {
            border: 2px solid #000 !important;
            padding: 12px !important;
            background: #f9f9f9 !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        /* Thank you message */
        .invoice-header div[style*="text-align: center; margin-top: 30px"] {
            text-align: center !important;
            margin-top: 25px !important;
            padding: 15px !important;
            border-top: 3px solid #000 !important;
        }
        
        .invoice-header h3 {
            margin: 0 !important;
            font-size: 18px !important;
            color: #000 !important;
            font-weight: bold !important;
        }
        
        .invoice-header p {
            margin: 5px 0 0 0 !important;
            font-size: 14px !important;
            color: #333 !important;
        }
        
        /* Small text visibility */
        .invoice-header small {
            color: #000 !important;
            font-size: 10px !important;
        }
        
        /* Ensure single page */
        .invoice-header {
            page-break-inside: avoid !important;
        }
        
        /* Force all backgrounds to white or light gray */
        .invoice-header * {
            background: white !important;
        }
        
        .invoice-header div[style*="background: #f8f9fa"],
        .invoice-header table th[style*="background: #f8f9fa"],
        .invoice-header table tr[style*="background: #f8f9fa"] td {
            background: #f5f5f5 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        /* Override any colored text */
        .invoice-header div[style*="color: #666"] {
            color: #333 !important;
        }
    }
    
    /* Regular screen styles */
    @media screen {
        .invoice-header {
            display: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let editMode = false;
    let discountEditMode = false;
    let currentCurrency = 'BDT';
    const currencySymbols = {
        'BDT': '৳', // Keep ৳ for HTML display since browsers handle Unicode well
        'USD': '$',
        'EUR': '€'
    };

    function printInvoice() {
        // Get the invoice header
        const invoiceHeader = document.querySelector('.invoice-header');
        
        if (invoiceHeader) {
            console.log('Invoice header found, showing for print...');
            
            // Temporarily show the invoice header
            invoiceHeader.classList.remove('d-none', 'd-print-block');
            invoiceHeader.classList.add('d-block');
            invoiceHeader.style.display = 'block !important';
            invoiceHeader.style.visibility = 'visible !important';
            invoiceHeader.style.position = 'relative !important';
            
            // Set document title for print
            const originalTitle = document.title;
            document.title = 'Invoice-{{ $order["id"] }}';
            
            // Add print-ready class to body
            document.body.classList.add('printing');
            
            // Print after a short delay to ensure styles are applied
            setTimeout(() => {
                window.print();
                
                // Restore original state after print dialog
                setTimeout(() => {
                    invoiceHeader.classList.remove('d-block');
                    invoiceHeader.classList.add('d-none', 'd-print-block');
                    invoiceHeader.style.display = '';
                    invoiceHeader.style.visibility = '';
                    invoiceHeader.style.position = '';
                    document.body.classList.remove('printing');
                    document.title = originalTitle;
                    console.log('Print completed, restored original state');
                }, 500);
            }, 100);
        } else {
            console.log('Invoice header not found, using fallback print');
            // Fallback - just print the current page
            document.title = 'Invoice-{{ $order["id"] }}';
            window.print();
            setTimeout(() => {
                document.title = 'Order Details - {{ $order["id"] }}';
            }, 1000);
        }
    }

    function printProfessionalInvoice() {
        // Use the new printable invoice method for better print formatting
        // Open the printable invoice in a new window
        const printUrl = '{{ route("admin.orders.printable-invoice", $order["id"]) }}';
        
        // Open in new window for printing
        const printWindow = window.open(printUrl, '_blank', 'width=800,height=600');
        
        // Focus on the new window
        if (printWindow) {
            printWindow.focus();
        } else {
            // Fallback if popup is blocked - redirect to print URL
            window.location.href = printUrl;
        }
    }

    function editPrices() {
        editMode = !editMode;
        
        if (editMode) {
            // Show edit inputs
            document.querySelectorAll('.price-edit, .qty-edit').forEach(input => {
                input.classList.remove('d-none');
            });
            
            // Hide display spans
            document.querySelectorAll('.price-display, .qty-display').forEach(span => {
                span.classList.add('d-none');
            });
            
            // Change button text
            event.target.innerHTML = 'Save Changes';
            event.target.classList.remove('dropdown-item');
            event.target.classList.add('btn', 'btn-success', 'btn-sm');
        } else {
            // Save changes and update totals
            updateTotals();
            
            // Hide edit inputs
            document.querySelectorAll('.price-edit, .qty-edit').forEach(input => {
                input.classList.add('d-none');
            });
            
            // Show display spans
            document.querySelectorAll('.price-display, .qty-display').forEach(span => {
                span.classList.remove('d-none');
            });
            
            // Reset button
            event.target.innerHTML = 'Edit Prices';
            event.target.classList.remove('btn', 'btn-success', 'btn-sm');
            event.target.classList.add('dropdown-item');
        }
    }

    function editDiscount() {
        discountEditMode = !discountEditMode;
        
        if (discountEditMode) {
            // Show discount edit input
            document.querySelector('.discount-edit').classList.remove('d-none');
            document.querySelector('.discount-display').classList.add('d-none');
        } else {
            // Hide discount edit input and update totals
            document.querySelector('.discount-edit').classList.add('d-none');
            document.querySelector('.discount-display').classList.remove('d-none');
            updateTotals();
            
            // Update hidden field
            const discountValue = document.querySelector('.discount-edit').value;
            document.getElementById('discount_amount').value = discountValue;
        }
    }

    function updateTotals() {
        let subtotal = 0;
        const rows = document.querySelectorAll('tbody tr');
        let updatedItems = [];
        
        rows.forEach((row, index) => {
            const priceInput = row.querySelector('.price-edit');
            const qtyInput = row.querySelector('.qty-edit');
            const priceDisplay = row.querySelector('.price-display');
            const qtyDisplay = row.querySelector('.qty-display');
            const totalDisplay = row.querySelector('.total-display');
            
            if (priceInput && qtyInput) {
                const price = parseFloat(priceInput.value);
                const qty = parseInt(qtyInput.value);
                const total = price * qty;
                
                // Store updated item data
                updatedItems.push({
                    index: index,
                    price: price,
                    quantity: qty,
                    total: total
                });
                
                // Update displays
                priceDisplay.textContent = currencySymbols[currentCurrency] + price.toFixed(2);
                qtyDisplay.textContent = qty;
                totalDisplay.textContent = currencySymbols[currentCurrency] + total.toFixed(2);
                
                subtotal += total;
            }
        });
        
        // Get discount value
        const discountInput = document.querySelector('.discount-edit');
        const discount = parseFloat(discountInput.value) || 0;
        
        // Calculate totals with discount
        const afterDiscount = subtotal - discount;
        const tax = afterDiscount * 0.087; // Assuming 8.7% tax on discounted amount
        const shipping = 0; // Free shipping
        const grandTotal = afterDiscount + tax + shipping;
        
        // Update displays
        document.querySelector('.subtotal-display').textContent = currencySymbols[currentCurrency] + subtotal.toFixed(2);
        document.querySelector('.discount-display').textContent = '-' + currencySymbols[currentCurrency] + discount.toFixed(2);
        document.querySelector('.tax-display').textContent = currencySymbols[currentCurrency] + tax.toFixed(2);
        document.querySelector('.shipping-display').textContent = currencySymbols[currentCurrency] + shipping.toFixed(2);
        document.querySelector('.grand-total-display').textContent = currencySymbols[currentCurrency] + grandTotal.toFixed(2);
        
        // Update hidden form fields
        document.getElementById('discount_amount').value = discount;
        document.getElementById('updated_items').value = JSON.stringify(updatedItems);
    }

    // Currency switcher
    document.getElementById('currencySelect').addEventListener('change', function() {
        currentCurrency = this.value;
        const symbol = currencySymbols[currentCurrency];
        
        // Update all price displays
        document.querySelectorAll('.price-display, .total-display, .subtotal-display, .tax-display, .shipping-display, .grand-total-display').forEach(element => {
            let text = element.textContent;
            // Remove old currency symbol and add new one
            text = text.replace(/[৳$€]/, symbol);
            element.textContent = text;
        });
        
        // Update discount display
        const discountElement = document.querySelector('.discount-display');
        let discountText = discountElement.textContent;
        discountText = discountText.replace(/[৳$€]/, symbol);
        discountElement.textContent = discountText;
    });

    // Auto-calculate on input change
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('price-edit') || 
            e.target.classList.contains('qty-edit') || 
            e.target.classList.contains('discount-edit')) {
            if (editMode || discountEditMode) {
                updateTotals();
            }
        }
    });

    // Update hidden fields before form submission
    document.getElementById('statusUpdateForm').addEventListener('submit', function(e) {
        // Ensure all current values are captured
        if (editMode || discountEditMode) {
            updateTotals();
        }
        
        // Update discount field if it's being edited
        const discountInput = document.querySelector('.discount-edit');
        if (!discountInput.classList.contains('d-none')) {
            document.getElementById('discount_amount').value = discountInput.value;
        }
    });

    // Save discount on Enter key
    document.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && e.target.classList.contains('discount-edit')) {
            editDiscount();
        }
    });

    // Customer Info Edit Functions
    function toggleCustomerEdit() {
        const displays = document.querySelectorAll('.customer-display, .customer-email-display, .customer-phone-display, .payment-method-display, .tracking-display');
        const edits = document.querySelectorAll('.customer-edit');
        
        displays.forEach(el => el.classList.toggle('d-none'));
        edits.forEach(el => el.classList.toggle('d-none'));
    }

    function saveCustomerInfo() {
        // Collect customer info data
        const customerData = {
            customer_name: document.querySelector('input[name="customer_name"]').value,
            customer_email: document.querySelector('input[name="customer_email"]').value,
            customer_phone: document.querySelector('input[name="customer_phone"]').value,
            payment_method: document.querySelector('select[name="payment_method"]').value,
            tracking_number: document.querySelector('input[name="tracking_number"]').value,
        };

        // Send AJAX request to update customer info
        fetch('{{ route("admin.orders.update-customer-info", $order["id"]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(customerData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update displays
                document.querySelector('.customer-display').textContent = customerData.customer_name;
                document.querySelector('.customer-email-display').textContent = customerData.customer_email;
                document.querySelector('.customer-phone-display').textContent = customerData.customer_phone;
                document.querySelector('.payment-method-display').textContent = customerData.payment_method;
                document.querySelector('.tracking-display').textContent = customerData.tracking_number || 'Not assigned';
                
                toggleCustomerEdit();
                showSuccessMessage('Customer information updated successfully');
            } else {
                alert('Failed to update customer information: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update customer information');
        });
    }

    function cancelCustomerEdit() {
        toggleCustomerEdit();
    }

    // Shipping Address Edit Functions
    function toggleShippingEdit() {
        document.querySelector('.shipping-display').classList.toggle('d-none');
        document.querySelector('.shipping-edit').classList.toggle('d-none');
    }

    function saveShippingInfo() {
        const shippingData = {
            shipping_name: document.querySelector('input[name="shipping_name"]').value,
            shipping_address: document.querySelector('input[name="shipping_address"]').value,
            shipping_city: document.querySelector('input[name="shipping_city"]').value,
            shipping_state: document.querySelector('input[name="shipping_state"]').value,
            shipping_postal_code: document.querySelector('input[name="shipping_postal_code"]').value,
            shipping_country: document.querySelector('input[name="shipping_country"]').value,
        };

        fetch('{{ route("admin.orders.update-shipping-address", $order["id"]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(shippingData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update display
                const addressDisplay = document.querySelector('.shipping-display address');
                addressDisplay.innerHTML = `
                    <strong>${shippingData.shipping_name}</strong><br>
                    ${shippingData.shipping_address}<br>
                    ${shippingData.shipping_city}, ${shippingData.shipping_state} ${shippingData.shipping_postal_code}<br>
                    ${shippingData.shipping_country}
                `;
                
                toggleShippingEdit();
                showSuccessMessage('Shipping address updated successfully');
            } else {
                alert('Failed to update shipping address: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update shipping address');
        });
    }

    function cancelShippingEdit() {
        toggleShippingEdit();
    }

    // Billing Address Edit Functions
    function toggleBillingEdit() {
        document.querySelector('.billing-display').classList.toggle('d-none');
        document.querySelector('.billing-edit').classList.toggle('d-none');
    }

    function saveBillingInfo() {
        const billingData = {
            billing_name: document.querySelector('input[name="billing_name"]').value,
            billing_address: document.querySelector('input[name="billing_address"]').value,
            billing_city: document.querySelector('input[name="billing_city"]').value,
            billing_state: document.querySelector('input[name="billing_state"]').value,
            billing_postal_code: document.querySelector('input[name="billing_postal_code"]').value,
            billing_country: document.querySelector('input[name="billing_country"]').value,
        };

        fetch('{{ route("admin.orders.update-billing-address", $order["id"]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(billingData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update display
                const addressDisplay = document.querySelector('.billing-display address');
                addressDisplay.innerHTML = `
                    <strong>${billingData.billing_name}</strong><br>
                    ${billingData.billing_address}<br>
                    ${billingData.billing_city}, ${billingData.billing_state} ${billingData.billing_postal_code}<br>
                    ${billingData.billing_country}
                `;
                
                toggleBillingEdit();
                showSuccessMessage('Billing address updated successfully');
            } else {
                alert('Failed to update billing address: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update billing address');
        });
    }

    function cancelBillingEdit() {
        toggleBillingEdit();
    }

    // Success message function
    function showSuccessMessage(message) {
        // Create or update success alert
        let alertDiv = document.getElementById('success-alert');
        if (!alertDiv) {
            alertDiv = document.createElement('div');
            alertDiv.id = 'success-alert';
            alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alertDiv.style.top = '20px';
            alertDiv.style.right = '20px';
            alertDiv.style.zIndex = '9999';
            document.body.appendChild(alertDiv);
        }
        
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (alertDiv) {
                alertDiv.remove();
            }
        }, 3000);
    }

    // Email functionality
    function showSendEmailModal() {
        const modal = new bootstrap.Modal(document.getElementById('sendEmailModal'));
        modal.show();
    }

    function sendInvoiceEmail() {
        const email = document.getElementById('recipientEmail').value;
        const button = event.target;
        const originalText = button.innerHTML;
        
        if (!email) {
            alert('Please enter a valid email address');
            return;
        }

        // Show loading state
        button.innerHTML = '<i class="ri-loader-4-line"></i> Sending...';
        button.disabled = true;

        // Send AJAX request
        fetch('{{ route("admin.orders.send-invoice-email", $order["id"]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: email
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert('✅ ' + data.message);
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('sendEmailModal'));
                modal.hide();
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Failed to send email. Please try again.');
        })
        .finally(() => {
            // Reset button state
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
</script>
@endpush
