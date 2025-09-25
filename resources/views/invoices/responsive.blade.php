<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        /* Base Responsive Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #2c3e50;
            background: #f8fafc;
            padding: 20px;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        /* Header Section */
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            position: relative;
            overflow: hidden;
        }
        
        .invoice-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
        }
        
        .header-content {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .company-info h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .company-info p {
            font-size: 14px;
            opacity: 0.9;
            margin: 3px 0;
        }
        
        .invoice-meta {
            text-align: right;
            min-width: 250px;
        }
        
        .invoice-meta h2 {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .invoice-meta p {
            font-size: 14px;
            margin: 5px 0;
            opacity: 0.9;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
        }
        
        /* Customer Section */
        .customer-section {
            padding: 30px;
            background: #f8fafc;
        }
        
        .customer-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .customer-box {
            background: white;
            border-radius: 8px;
            padding: 20px;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .customer-title {
            font-weight: bold;
            color: #4a5568;
            margin-bottom: 15px;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .customer-info {
            font-size: 14px;
            line-height: 1.8;
        }
        
        .customer-info strong {
            color: #2d3748;
            font-weight: 600;
        }
        
        /* Items Section */
        .items-section {
            padding: 30px;
        }
        
        .section-header {
            background: #4a5568;
            color: white;
            padding: 15px 20px;
            margin-bottom: 0;
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 8px 8px 0 0;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 0 0 8px 8px;
            overflow: hidden;
        }
        
        .items-table th {
            background: #667eea;
            color: white;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #5a67d8;
        }
        
        .items-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            vertical-align: top;
        }
        
        .items-table tr:nth-child(even) {
            background: #f7fafc;
        }
        
        .items-table tr:hover {
            background: #edf2f7;
        }
        
        .product-name {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 5px;
            font-size: 15px;
        }
        
        .product-variant {
            font-size: 12px;
            color: #718096;
            font-style: italic;
        }
        
        .quantity-cell {
            background: #e6fffa !important;
            font-weight: 600;
            color: #2c7a7b;
        }
        
        .price-cell {
            font-weight: 600;
            color: #2d3748;
        }
        
        .total-cell {
            font-weight: bold;
            color: #1a202c;
            background: #f0fff4 !important;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        /* Summary Section */
        .summary-section {
            padding: 30px;
            background: #f8fafc;
        }
        
        .summary-wrapper {
            display: flex;
            justify-content: flex-end;
        }
        
        .summary-box {
            width: 100%;
            max-width: 350px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        
        .summary-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .summary-table td {
            padding: 12px 20px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }
        
        .summary-table .label {
            color: #4a5568;
            font-weight: 500;
        }
        
        .summary-table .value {
            text-align: right;
            font-weight: 600;
            color: #2d3748;
        }
        
        .summary-table .discount-row {
            background: #f0fff4;
        }
        
        .summary-table .discount-row .value {
            color: #38a169;
        }
        
        .summary-table .total-row {
            background: #2d3748;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }
        
        .summary-table .total-row td {
            border: none;
            padding: 18px 20px;
        }
        
        /* Footer */
        .invoice-footer {
            padding: 30px;
            background: white;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .payment-info {
            font-size: 14px;
        }
        
        .payment-info strong {
            color: #2d3748;
            font-size: 15px;
        }
        
        .footer-notes {
            text-align: center;
            font-size: 13px;
            color: #718096;
            line-height: 1.6;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
        }
        
        .footer-notes strong {
            color: #4a5568;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #a0aec0;
            font-style: italic;
            font-size: 16px;
        }
        
        .empty-state::before {
            content: "📄";
            display: block;
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
                font-size: 12px;
            }
            
            .invoice-container {
                border-radius: 8px;
            }
            
            .invoice-header {
                padding: 20px;
            }
            
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .invoice-meta {
                text-align: center;
                min-width: auto;
            }
            
            .company-info h1 {
                font-size: 24px;
            }
            
            .invoice-meta h2 {
                font-size: 28px;
            }
            
            .customer-section {
                padding: 20px;
            }
            
            .customer-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .items-section {
                padding: 20px;
            }
            
            .items-table {
                font-size: 12px;
            }
            
            .items-table th,
            .items-table td {
                padding: 10px 8px;
            }
            
            .summary-section {
                padding: 20px;
            }
            
            .summary-box {
                max-width: 100%;
            }
            
            .footer-content {
                flex-direction: column;
                text-align: center;
            }
        }
        
        @media (max-width: 480px) {
            .items-table {
                font-size: 10px;
            }
            
            .items-table th,
            .items-table td {
                padding: 8px 5px;
            }
            
            .product-name {
                font-size: 12px;
            }
            
            .product-variant {
                font-size: 10px;
            }
        }
        
        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
                font-size: 12px;
            }
            
            .invoice-container {
                box-shadow: none;
                border-radius: 0;
                max-width: none;
            }
            
            .invoice-header,
            .summary-header,
            .items-table th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .customer-section,
            .summary-section {
                background: white;
            }
            
            .items-table tr:hover {
                background: transparent;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Enhanced Invoice Header -->
        <div class="invoice-header">
            <div class="header-content">
                <div class="company-info">
                    <h1>🏪 MultiVendor Store</h1>
                    <p>📍 123 Business Street, Business City, BC 12345</p>
                    <p>📞 (555) 123-4567 | ✉️ info@multivendorstore.com</p>
                    <p>🌐 www.multivendorstore.com</p>
                </div>
                <div class="invoice-meta">
                    <h2>INVOICE</h2>
                    <p><strong>Invoice #:</strong> {{ $order->order_number }}</p>
                    <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                    <p><strong>Due Date:</strong> {{ $order->created_at->addDays(30)->format('M d, Y') }}</p>
                    <p><strong>Status:</strong> <span class="status-badge">{{ ucfirst($order->status) }}</span></p>
                </div>
            </div>
        </div>

        <!-- Enhanced Customer Details -->
        <div class="customer-section">
            <div class="customer-grid">
                <div class="customer-box">
                    <div class="customer-title">💳 Bill To</div>
                    <div class="customer-info">
                        <strong>{{ $shipping_address['first_name'] ?? '' }} {{ $shipping_address['last_name'] ?? '' }}</strong><br>
                        📧 {{ $shipping_address['email'] ?? 'N/A' }}<br>
                        📱 {{ $shipping_address['phone'] ?? 'N/A' }}<br>
                        🆔 Customer ID: {{ $order->customer_id }}
                    </div>
                </div>
                <div class="customer-box">
                    <div class="customer-title">🚚 Ship To</div>
                    <div class="customer-info">
                        <strong>{{ $shipping_address['first_name'] ?? '' }} {{ $shipping_address['last_name'] ?? '' }}</strong><br>
                        📍 {{ $shipping_address['address'] ?? 'N/A' }}<br>
                        🏙️ {{ $shipping_address['city'] ?? '' }}, {{ $shipping_address['postal_code'] ?? '' }}<br>
                        📞 {{ $shipping_address['phone'] ?? '' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Items Section -->
        <div class="items-section">
            <div class="section-header">🛍️ Order Items</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 45%">Product Description</th>
                        <th style="width: 15%" class="text-center">Qty</th>
                        <th style="width: 20%" class="text-right">Unit Price</th>
                        <th style="width: 20%" class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td>
                            <div class="product-name">{{ $item['product_name'] ?? $item['name'] ?? 'Product' }}</div>
                            @if(isset($item['size']) || isset($item['color']))
                            <div class="product-variant">
                                @if(isset($item['size']))📏 Size: {{ $item['size'] }}@endif
                                @if(isset($item['size']) && isset($item['color'])) • @endif
                                @if(isset($item['color']))🎨 Color: {{ $item['color'] }}@endif
                            </div>
                            @endif
                        </td>
                        <td class="text-center quantity-cell">{{ $item['quantity'] ?? 1 }}</td>
                        <td class="text-right price-cell">${{ number_format($item['price'] ?? 0, 2) }}</td>
                        <td class="text-right total-cell">${{ number_format($item['total'] ?? ($item['price'] * $item['quantity']), 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="empty-state">
                            No items found for this order
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Enhanced Summary Section -->
        <div class="summary-section">
            <div class="summary-wrapper">
                <div class="summary-box">
                    <div class="summary-header">💰 Order Summary</div>
                    <table class="summary-table">
                        <tr>
                            <td class="label">Subtotal:</td>
                            <td class="value">${{ number_format($order->subtotal ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="label">🚚 Shipping:</td>
                            <td class="value">{{ $order->shipping_amount == 0 ? 'FREE' : '$' . number_format($order->shipping_amount, 2) }}</td>
                        </tr>
                        @if($order->discount_amount > 0)
                        <tr class="discount-row">
                            <td class="label">🎯 Discount:</td>
                            <td class="value">-${{ number_format($order->discount_amount, 2) }}</td>
                        </tr>
                        @endif
                        @if($order->tax_amount > 0)
                        <tr>
                            <td class="label">🧾 Tax:</td>
                            <td class="value">${{ number_format($order->tax_amount, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="total-row">
                            <td><strong>TOTAL:</strong></td>
                            <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Enhanced Footer -->
        <div class="invoice-footer">
            <div class="footer-content">
                <div class="payment-info">
                    <strong>💳 Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}<br>
                    <strong>📊 Payment Status:</strong> 
                    <span class="status-badge">{{ ucfirst($order->payment_status) }}</span>
                </div>
                <div style="text-align: right; font-size: 12px; color: #718096;">
                    Generated on: {{ now()->format('M d, Y H:i:s') }}
                </div>
            </div>
            
            <div class="footer-notes">
                <strong>Thank you for your business! 🙏</strong><br>
                If you have any questions about this invoice, please contact us at info@multivendorstore.com<br>
                <em>This is a computer-generated invoice and does not require a signature.</em>
            </div>
        </div>
    </div>
</body>
</html>
