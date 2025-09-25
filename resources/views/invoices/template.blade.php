<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            background: #fff;
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .company-info h1 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .company-info p {
            color: #6c757d;
            margin: 2px 0;
        }
        
        .invoice-meta {
            text-align: right;
        }
        
        .invoice-meta h2 {
            color: #e74c3c;
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .invoice-meta p {
            margin: 3px 0;
            font-size: 14px;
        }
        
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        
        .bill-to, .ship-to {
            flex: 1;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .bill-to {
            margin-right: 20px;
        }
        
        .detail-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 16px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .items-table th {
            background: #343a40;
            color: #fff;
            padding: 15px 10px;
            text-align: left;
            font-weight: bold;
        }
        
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .items-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .items-table tr:hover {
            background: #e9ecef;
        }
        
        .product-info {
            display: flex;
            align-items: center;
        }
        
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 15px;
            border: 2px solid #dee2e6;
        }
        
        .product-details {
            flex: 1;
        }
        
        .product-name {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 3px;
        }
        
        .product-variant {
            font-size: 12px;
            color: #6c757d;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .invoice-summary {
            margin-left: auto;
            width: 300px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .summary-row:last-child {
            border-bottom: none;
        }
        
        .summary-row.total {
            font-weight: bold;
            font-size: 18px;
            background: #e9ecef;
            margin: 10px -20px -20px -20px;
            padding: 15px 20px;
            border-radius: 0 0 8px 8px;
            color: #2c3e50;
        }
        
        .discount {
            color: #28a745;
        }
        
        .invoice-footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #d1ecf1; color: #0c5460; }
        .status-shipped { background: #d1ecf1; color: #0c5460; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        
        @media print {
            body {
                background: #fff;
            }
            
            .invoice-container {
                padding: 0;
                max-width: none;
                box-shadow: none;
            }
            
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1>MultiVendor Store</h1>
                <p>123 Business Street</p>
                <p>Business City, BC 12345</p>
                <p>Phone: (555) 123-4567</p>
                <p>Email: info@multivendorstore.com</p>
            </div>
            <div class="invoice-meta">
                <h2>INVOICE</h2>
                <p><strong>Invoice #:</strong> {{ $order->order_number }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                <p><strong>Due Date:</strong> {{ $order->created_at->addDays(30)->format('M d, Y') }}</p>
                <p><strong>Status:</strong> 
                    <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                </p>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="bill-to">
                <div class="detail-title">Bill To:</div>
                <p><strong>{{ $shipping_address['first_name'] ?? '' }} {{ $shipping_address['last_name'] ?? '' }}</strong></p>
                <p>{{ $shipping_address['email'] ?? 'N/A' }}</p>
                <p>{{ $shipping_address['phone'] ?? 'N/A' }}</p>
                <p>Customer ID: {{ $order->customer_id }}</p>
            </div>
            <div class="ship-to">
                <div class="detail-title">Ship To:</div>
                <p><strong>{{ $shipping_address['first_name'] ?? '' }} {{ $shipping_address['last_name'] ?? '' }}</strong></p>
                <p>{{ $shipping_address['address'] ?? 'N/A' }}</p>
                <p>{{ $shipping_address['city'] ?? '' }}, {{ $shipping_address['postal_code'] ?? '' }}</p>
                <p>{{ $shipping_address['phone'] ?? '' }}</p>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%">Product</th>
                    <th style="width: 10%" class="text-center">Qty</th>
                    <th style="width: 15%" class="text-right">Unit Price</th>
                    <th style="width: 15%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="{{ asset('assets/img/' . ($item['image'] ?? 'products/default.jpg')) }}" 
                                 alt="Product" class="product-image" 
                                 onerror="this.src='{{ asset('assets/img/products/default.jpg') }}'">
                            <div class="product-details">
                                <div class="product-name">{{ $item['product_name'] ?? $item['name'] ?? 'Product' }}</div>
                                @if(isset($item['size']) || isset($item['color']))
                                <div class="product-variant">
                                    @if(isset($item['size']))Size: {{ $item['size'] }}@endif
                                    @if(isset($item['size']) && isset($item['color'])), @endif
                                    @if(isset($item['color']))Color: {{ $item['color'] }}@endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="text-center">{{ $item['quantity'] ?? 1 }}</td>
                    <td class="text-right">${{ number_format($item['price'] ?? 0, 2) }}</td>
                    <td class="text-right">${{ number_format($item['total'] ?? ($item['price'] * $item['quantity']), 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding: 40px; color: #6c757d;">
                        No items found for this order
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Invoice Summary -->
        <div class="invoice-summary">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>${{ number_format($order->subtotal ?? 0, 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Shipping:</span>
                <span>{{ $order->shipping_amount == 0 ? 'FREE' : '$' . number_format($order->shipping_amount, 2) }}</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="summary-row discount">
                <span>Discount:</span>
                <span>-${{ number_format($order->discount_amount, 2) }}</span>
            </div>
            @endif
            @if($order->tax_amount > 0)
            <div class="summary-row">
                <span>Tax:</span>
                <span>${{ number_format($order->tax_amount, 2) }}</span>
            </div>
            @endif
            <div class="summary-row total">
                <span>Total:</span>
                <span>${{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Invoice Footer -->
        <div class="invoice-footer">
            <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
            <p><strong>Payment Status:</strong> 
                <span class="status-badge status-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span>
            </p>
            <br>
            <p>Thank you for your business!</p>
            <p>If you have any questions about this invoice, please contact us at info@multivendorstore.com</p>
        </div>
    </div>
</body>
</html>
