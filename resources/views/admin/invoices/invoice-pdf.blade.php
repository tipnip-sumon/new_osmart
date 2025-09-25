<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            background-color: #fff;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            background: white;
        }
        
        .invoice-header {
            display: table;
            width: 100%;
            margin-bottom: 40px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
        }
        
        .company-info {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        
        .company-logo {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .company-details {
            color: #666;
            line-height: 1.4;
        }
        
        .invoice-title {
            display: table-cell;
            width: 40%;
            text-align: right;
            vertical-align: top;
        }
        
        .invoice-title h1 {
            font-size: 36px;
            color: #667eea;
            margin-bottom: 10px;
            font-weight: 300;
        }
        
        .invoice-meta {
            color: #666;
            font-size: 11px;
        }
        
        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .bill-to {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        
        .invoice-details {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: right;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .customer-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        
        .customer-name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .detail-row {
            margin-bottom: 5px;
        }
        
        .detail-label {
            font-weight: bold;
            color: #666;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .items-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        
        .items-table tr:last-child td {
            border-bottom: none;
        }
        
        .items-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .product-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }
        
        .product-description {
            color: #666;
            font-size: 10px;
            font-style: italic;
        }
        
        .product-sku {
            color: #999;
            font-size: 10px;
            margin-top: 2px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .pv-badge {
            background: #667eea;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .totals-section {
            float: right;
            width: 300px;
            margin-top: 20px;
        }
        
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .totals-table td {
            padding: 8px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .totals-table .total-label {
            text-align: right;
            font-weight: 600;
            color: #666;
        }
        
        .totals-table .total-amount {
            text-align: right;
            font-weight: bold;
            width: 100px;
        }
        
        .grand-total {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 16px;
            font-weight: bold;
        }
        
        .pv-total {
            background: #e3f2fd;
            color: #1976d2;
            font-weight: bold;
        }
        
        .commission-section {
            clear: both;
            margin-top: 40px;
            background: #f0f8ff;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #2196f3;
        }
        
        .commission-grid {
            display: table;
            width: 100%;
        }
        
        .commission-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 10px;
        }
        
        .commission-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .commission-amount {
            font-size: 14px;
            font-weight: bold;
            color: #2196f3;
        }
        
        .notes-section {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }
        
        .notes-title {
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
        }
        
        .terms-section {
            margin-top: 20px;
            padding: 15px;
            background: #fff3cd;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
        }
        
        .terms-title {
            font-weight: bold;
            color: #856404;
            margin-bottom: 8px;
        }
        
        .terms-text {
            color: #856404;
            font-size: 11px;
            line-height: 1.4;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        
        .payment-info {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid #28a745;
        }
        
        .status-paid {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(102, 126, 234, 0.1);
            z-index: -1;
            font-weight: bold;
        }
        
        @media print {
            .invoice-container {
                padding: 0;
            }
            
            .watermark {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="watermark">INVOICE</div>
    
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <div class="company-logo">🛍️ MultiVendor Marketplace</div>
                <div class="company-details">
                    789 Business Avenue, Suite 100<br>
                    Business City, BC 12345<br>
                    📞 +1 (555) 987-6543<br>
                    ✉️ invoices@multivendor.com<br>
                    🌐 www.multivendor.com<br>
                    Tax ID: TAX-123456789
                </div>
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <div class="invoice-meta">
                    <div style="margin-bottom: 5px;">
                        <strong>INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong>
                    </div>
                    <div>Date: {{ $order->created_at->format('M d, Y') }}</div>
                    <div>Due: {{ $order->created_at->addDays(30)->format('M d, Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Invoice Info -->
        <div class="invoice-info">
            <div class="bill-to">
                <div class="section-title">Bill To</div>
                <div class="customer-info">
                    <div class="customer-name">{{ $order->customer->name ?? 'Guest Customer' }}</div>
                    @if($order->billing_address && isset($order->billing_address['company']) && $order->billing_address['company'])
                    <div>{{ $order->billing_address['company'] }}</div>
                    @endif
                    @if($order->billing_address && isset($order->billing_address['street']))
                    <div>{{ $order->billing_address['street'] }}</div>
                    @if(isset($order->billing_address['street2']) && $order->billing_address['street2'])
                    <div>{{ $order->billing_address['street2'] }}</div>
                    @endif
                    <div>{{ $order->billing_address['city'] ?? '' }}, {{ $order->billing_address['state'] ?? '' }} {{ $order->billing_address['zip'] ?? '' }}</div>
                    <div>{{ $order->billing_address['country'] ?? '' }}</div>
                    @endif
                    <div style="margin-top: 8px;">
                        <div>📧 {{ $order->customer->email ?? 'N/A' }}</div>
                        <div>📞 {{ $order->customer->phone ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
            <div class="invoice-details">
                <div class="section-title">Invoice Details</div>
                <div class="detail-row">
                    <span class="detail-label">Customer ID:</span> {{ $order->customer_id ?? 'N/A' }}
                </div>
                <div class="detail-row">
                    <span class="detail-label">Order Status:</span> 
                    <span class="status-{{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Status:</span> 
                    <span class="status-{{ strtolower($order->payment_status) }}">{{ ucfirst($order->payment_status) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Method:</span> {{ $order->payment_method ?? 'N/A' }}
                </div>
                <div class="detail-row">
                    <span class="detail-label">Currency:</span> {{ $order->currency ?? 'TK' }}
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 45%;">Product Details</th>
                    <th style="width: 15%;" class="text-center">Unit Price</th>
                    <th style="width: 10%;" class="text-center">Qty</th>
                    <th style="width: 15%;" class="text-center">PV Points</th>
                    <th style="width: 15%;" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <div class="product-name">{{ $item->product->name ?? 'Product' }}</div>
                        @if($item->product && $item->product->description)
                        <div class="product-description">{{ substr($item->product->description, 0, 100) }}{{ strlen($item->product->description) > 100 ? '...' : '' }}</div>
                        @endif
                        @if($item->product && $item->product->sku)
                        <div class="product-sku">SKU: {{ $item->product->sku }}</div>
                        @endif
                    </td>
                    <td class="text-center">Tk {{ number_format($item->price, 2) }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-center">
                        <span class="pv-badge">0 PV</span>
                    </td>
                    <td class="text-right">Tk {{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="total-label">Subtotal:</td>
                    <td class="total-amount">Tk {{ number_format($order->subtotal ?? 0, 2) }}</td>
                </tr>
                @if($order->discount_amount > 0)
                <tr>
                    <td class="total-label">Discount:</td>
                    <td class="total-amount text-success">-Tk {{ number_format($order->discount_amount, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td class="total-label">Tax:</td>
                    <td class="total-amount">Tk {{ number_format($order->tax_amount ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td class="total-label">Shipping:</td>
                    <td class="total-amount">Tk {{ number_format($order->shipping_amount ?? 0, 2) }}</td>
                </tr>
                <tr class="grand-total">
                    <td class="total-label">TOTAL:</td>
                    <td class="total-amount">Tk {{ number_format($order->total_amount, 2) }}</td>
                </tr>
                                </tr>
                <tr class="pv-total">
                    <td class="total-label">Total PV Points:</td>
                    <td class="total-amount">0 PV</td>
                </tr>
            </table>
        </div>

        <!-- Payment Status -->
        @if($order->payment_status == 'paid')
        <div class="payment-status">
            <div class="section-title">Payment Information</div>
            <div>Thank you! Your payment has been successfully processed on {{ $order->created_at->format('M d, Y') }}.</div>
        </div>
        @endif

        <!-- Notes -->
        @if($order->notes)
        <div class="notes-section">
            <div class="section-title">Notes</div>
            <div>{{ $order->notes }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="footer-content">
                <div class="footer-line">Thank you for your business!</div>
                <div class="footer-line">
                    For questions about this invoice, please contact us at support@multivendor.com
                </div>
            </div>
        </div>
    </div>
</body>
</html>
            </table>
        </div>

        <!-- MLM Commission Section -->
        @if(isset($order['commission_info']))
        <div class="commission-section">
            <div class="section-title">🎯 MLM Commission Breakdown</div>
            <div class="commission-grid">
                <div class="commission-item">
                    <div class="commission-label">Direct Commission</div>
                    <div class="commission-amount">{{ $order['currency_symbol'] }}{{ number_format($order['commission_info']['direct_commission'], 2) }}</div>
                </div>
                <div class="commission-item">
                    <div class="commission-label">Level 2 Commission</div>
                    <div class="commission-amount">{{ $order['currency_symbol'] }}{{ number_format($order['commission_info']['level_2_commission'], 2) }}</div>
                </div>
                <div class="commission-item">
                    <div class="commission-label">Level 3 Commission</div>
                    <div class="commission-amount">{{ $order['currency_symbol'] }}{{ number_format($order['commission_info']['level_3_commission'], 2) }}</div>
                </div>
                <div class="commission-item">
                    <div class="commission-label">Total Commissions</div>
                    <div class="commission-amount">{{ $order['currency_symbol'] }}{{ number_format($order['commission_info']['total_commissions'], 2) }}</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Payment Information -->
        @if($order['payment_status'] == 'Paid')
        <div class="payment-info">
            <div class="notes-title">✅ Payment Confirmed</div>
            <div>Thank you! Your payment has been successfully processed on {{ date('M d, Y', strtotime($order['order_date'])) }}.</div>
        </div>
        @endif

        <!-- Notes Section -->
        @if(isset($order['notes']) && $order['notes'])
        <div class="notes-section">
            <div class="notes-title">📝 Additional Notes</div>
            <div>{{ $order['notes'] }}</div>
        </div>
        @endif

        <!-- Terms and Conditions -->
        @if(isset($order['terms']) && $order['terms'])
        <div class="terms-section">
            <div class="terms-title">📋 Terms & Conditions</div>
            <div class="terms-text">{{ $order['terms'] }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div style="margin-bottom: 10px;">
                <strong>Thank you for your business!</strong>
            </div>
            <div>This invoice was generated on {{ date('M d, Y \a\t h:i A') }}</div>
            <div style="margin-top: 5px;">
                For questions about this invoice, please contact us at {{ $order['vendor_info']['email'] ?? 'support@multivendor.com' }}
            </div>
        </div>
    </div>
</body>
</html>
