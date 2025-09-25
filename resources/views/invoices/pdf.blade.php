<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        @page {
            size: A4;
            margin: 12mm 15mm;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            line-height: 1.2;
            color: #333;
        }
        
        .header {
            border-bottom: 2px solid #333;
            margin-bottom: 12px;
            padding-bottom: 8px;
        }
        
        .company-info {
            float: left;
            width: 50%;
        }
        
        .invoice-info {
            float: right;
            width: 50%;
            text-align: right;
        }
        
        .company-info h1 {
            font-size: 18px;
            color: #333;
            margin-bottom: 4px;
        }
        
        .company-info p {
            margin: 1px 0;
            color: #666;
        }
        
        .invoice-info h2 {
            font-size: 16px;
            color: #333;
            margin-bottom: 4px;
        }
        
        .invoice-info p {
            margin: 1px 0;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        .customer-section {
            margin: 5px 0;
        }
        
        .bill-to, .ship-to {
            float: left;
            width: 48%;
            padding: 8px;
            border: 1px solid #ddd;
            background: #f9f9f9;
        }
        
        .ship-to {
            float: right;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 4px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 2px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }
        
        .items-table th {
            background: #333;
            color: white;
            padding: 4px 3px;
            text-align: left;
            font-weight: bold;
            font-size: 8px;
        }
        
        .items-table td {
            padding: 4px;
            border-bottom: 1px solid #ddd;
            font-size: 8px;
        }
        
        .items-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .summary-table {
            float: right;
            width: 250px;
            margin-top: 10px;
            border-collapse: collapse;
        }
        
        .summary-table td {
            padding: 4px 8px;
            border-bottom: 1px solid #ddd;
            font-size: 9px;
        }
        
        .summary-table .total-row {
            background: #333;
            color: white;
            font-weight: bold;
            font-size: 10px;
        }
        
        .footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
        
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            background: #f0f0f0;
            color: #333;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header clearfix">
        <div class="company-info">
            <h1>MultiVendor Store</h1>
            <p>123 Business Street</p>
            <p>Business City, BC 12345</p>
            <p>Phone: (555) 123-4567</p>
            <p>Email: info@multivendorstore.com</p>
        </div>
        <div class="invoice-info">
            <h2>INVOICE</h2>
            <p><strong>Invoice #:</strong> {{ $order->order_number }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
            <p><strong>Due Date:</strong> {{ $order->created_at->addDays(30)->format('M d, Y') }}</p>
            <p><strong>Status:</strong> <span class="status-badge">{{ ucfirst($order->status) }}</span></p>
        </div>
    </div>

    <!-- Customer Details -->
    <div class="customer-section clearfix">
        <div class="bill-to">
            <div class="section-title">Bill To:</div>
            <p><strong>{{ $shipping_address['first_name'] ?? '' }} {{ $shipping_address['last_name'] ?? '' }}</strong></p>
            <p>{{ $shipping_address['email'] ?? 'N/A' }}</p>
            <p>{{ $shipping_address['phone'] ?? 'N/A' }}</p>
            <p>Customer ID: {{ $order->customer_id }}</p>
        </div>
        <div class="ship-to">
            <div class="section-title">Ship To:</div>
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
                <th style="width: 50%">Product Description</th>
                <th style="width: 15%" class="text-center">Qty</th>
                <th style="width: 15%" class="text-right">Unit Price</th>
                <th style="width: 20%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td>
                    <strong>{{ $item['product_name'] ?? $item['name'] ?? 'Product' }}</strong>
                    @if(isset($item['size']) || isset($item['color']))
                    <br><small style="color: #666;">
                        @if(isset($item['size']))Size: {{ $item['size'] }}@endif
                        @if(isset($item['size']) && isset($item['color'])), @endif
                        @if(isset($item['color']))Color: {{ $item['color'] }}@endif
                    </small>
                    @endif
                </td>
                <td class="text-center">{{ $item['quantity'] ?? 1 }}</td>
                <td class="text-right">${{ number_format($item['price'] ?? 0, 2) }}</td>
                <td class="text-right"><strong>${{ number_format($item['total'] ?? ($item['price'] * $item['quantity']), 2) }}</strong></td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center" style="padding: 30px; color: #999;">
                    No items found for this order
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Summary -->
    <div class="clearfix">
        <table class="summary-table">
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">${{ number_format($order->subtotal ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>Shipping:</td>
                <td class="text-right">{{ $order->shipping_amount == 0 ? 'FREE' : '$' . number_format($order->shipping_amount, 2) }}</td>
            </tr>
            @if($order->discount_amount > 0)
            <tr>
                <td>Discount:</td>
                <td class="text-right" style="color: #28a745;">-${{ number_format($order->discount_amount, 2) }}</td>
            </tr>
            @endif
            @if($order->tax_amount > 0)
            <tr>
                <td>Tax:</td>
                <td class="text-right">${{ number_format($order->tax_amount, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td><strong>TOTAL:</strong></td>
                <td class="text-right"><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer clearfix">
        <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
        <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
        <br>
        <p>Thank you for your business!</p>
        <p>If you have any questions, please contact us at info@multivendorstore.com</p>
        <p>Generated on: {{ now()->format('M d, Y H:i:s') }}</p>
    </div>
</body>
</html>
