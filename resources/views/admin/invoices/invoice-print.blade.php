<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order['invoice_number'] }} - Print</title>
    <style>
        @media print {
            @page {
                margin: 0.5in;
                size: A4;
            }
            
            body {
                margin: 0;
                color: #000;
                background: white;
            }
            
            .no-print {
                display: none;
            }
            
            .page-break {
                page-break-before: always;
            }
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: white;
            padding: 20px;
        }
        
        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 1px solid #ddd;
        }
        
        .print-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
            transition: background 0.3s;
        }
        
        .print-btn:hover {
            background: #5a6fd8;
        }
        
        .close-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        
        .close-btn:hover {
            background: #5a6268;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
        }
        
        .invoice-header {
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            padding-bottom: 15px;
        }
        
        .header-row {
            display: table;
            width: 100%;
        }
        
        .company-info {
            display: table-cell;
            width: 65%;
            vertical-align: top;
        }
        
        .company-logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .company-details {
            font-size: 11px;
            line-height: 1.3;
        }
        
        .invoice-title {
            display: table-cell;
            width: 35%;
            text-align: right;
            vertical-align: top;
        }
        
        .invoice-title h1 {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .invoice-meta {
            font-size: 11px;
            text-align: right;
        }
        
        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 25px;
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
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .customer-info {
            border: 1px solid #ddd;
            padding: 12px;
            background: #f9f9f9;
        }
        
        .customer-name {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 4px;
        }
        
        .detail-row {
            margin-bottom: 3px;
            font-size: 11px;
        }
        
        .detail-label {
            font-weight: bold;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #000;
        }
        
        .items-table th {
            background: #f0f0f0;
            border: 1px solid #000;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        
        .items-table td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
            font-size: 11px;
        }
        
        .product-name {
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .product-description {
            color: #666;
            font-size: 10px;
            font-style: italic;
        }
        
        .product-sku {
            color: #666;
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
            border: 1px solid #000;
            padding: 1px 6px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .totals-section {
            float: right;
            width: 300px;
            margin-top: 15px;
        }
        
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }
        
        .totals-table td {
            padding: 6px 10px;
            border: 1px solid #000;
            font-size: 11px;
        }
        
        .total-label {
            text-align: right;
            font-weight: bold;
        }
        
        .total-amount {
            text-align: right;
            font-weight: bold;
            width: 100px;
        }
        
        .grand-total {
            background: #f0f0f0;
            font-size: 13px;
            font-weight: bold;
        }
        
        .pv-total {
            background: #f8f8f8;
            font-weight: bold;
        }
        
        .commission-section {
            clear: both;
            margin-top: 30px;
            border: 1px solid #000;
            padding: 15px;
            background: #f9f9f9;
        }
        
        .commission-grid {
            display: table;
            width: 100%;
        }
        
        .commission-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 8px;
            border-right: 1px solid #ddd;
        }
        
        .commission-item:last-child {
            border-right: none;
        }
        
        .commission-label {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 4px;
        }
        
        .commission-amount {
            font-size: 12px;
            font-weight: bold;
        }
        
        .notes-section {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #000;
        }
        
        .notes-title {
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .terms-section {
            margin-top: 15px;
            padding: 12px;
            border: 1px dashed #666;
            background: #fafafa;
        }
        
        .terms-title {
            font-weight: bold;
            margin-bottom: 6px;
        }
        
        .terms-text {
            font-size: 10px;
            line-height: 1.3;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #000;
            padding-top: 15px;
        }
        
        .payment-info {
            border: 2px solid #000;
            padding: 12px;
            margin-top: 15px;
            background: #f0f8ff;
        }
        
        .status-paid {
            font-weight: bold;
        }
        
        .status-pending {
            font-weight: bold;
        }
        
        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            padding: 20px;
            border: 1px solid #000;
            height: 80px;
            vertical-align: bottom;
            text-align: center;
        }
        
        .signature-box:first-child {
            margin-right: 10px;
        }
        
        .signature-label {
            font-size: 10px;
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <!-- Print Controls -->
    <div class="print-controls no-print">
        <button class="print-btn" onclick="window.print()">🖨️ Print Invoice</button>
        <button class="print-btn" onclick="window.close()">✖️ Close</button>
    </div>

    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="header-row">
                <div class="company-info">
                    <div class="company-logo">🛍️ MultiVendor Marketplace</div>
                    <div class="company-details">
                        {{ $order['vendor_info']['address'] ?? '789 Business Avenue, Suite 100' }}<br>
                        {{ $order['vendor_info']['city'] ?? 'Business City, BC 12345' }}<br>
                        Phone: {{ $order['vendor_info']['phone'] ?? '+1 (555) 987-6543' }}<br>
                        Email: {{ $order['vendor_info']['email'] ?? 'invoices@multivendor.com' }}<br>
                        Website: {{ $order['vendor_info']['website'] ?? 'www.multivendor.com' }}
                        @if(isset($order['vendor_info']['tax_id']))
                        <br>Tax ID: {{ $order['vendor_info']['tax_id'] }}
                        @endif
                    </div>
                </div>
                <div class="invoice-title">
                    <h1>INVOICE</h1>
                    <div class="invoice-meta">
                        <div style="margin-bottom: 4px;">
                            <strong>{{ $order['invoice_number'] }}</strong>
                        </div>
                        <div>Date: {{ date('M d, Y', strtotime($order['order_date'])) }}</div>
                        <div>Due: {{ date('M d, Y', strtotime($order['due_date'])) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Info -->
        <div class="invoice-info">
            <div class="bill-to">
                <div class="section-title">Bill To</div>
                <div class="customer-info">
                    <div class="customer-name">{{ $order['customer'] }}</div>
                    @if(isset($order['billing_address']['company']) && $order['billing_address']['company'])
                    <div>{{ $order['billing_address']['company'] }}</div>
                    @endif
                    <div>{{ $order['billing_address']['street'] }}</div>
                    @if(isset($order['billing_address']['street2']) && $order['billing_address']['street2'])
                    <div>{{ $order['billing_address']['street2'] }}</div>
                    @endif
                    <div>{{ $order['billing_address']['city'] }}, {{ $order['billing_address']['state'] }} {{ $order['billing_address']['zip'] }}</div>
                    <div>{{ $order['billing_address']['country'] }}</div>
                    <div style="margin-top: 6px;">
                        <div>Email: {{ $order['customer_email'] }}</div>
                        <div>Phone: {{ $order['customer_phone'] }}</div>
                    </div>
                </div>
            </div>
            <div class="invoice-details">
                <div class="section-title">Invoice Details</div>
                <div class="detail-row">
                    <span class="detail-label">Customer ID:</span> {{ $order['customer_id'] ?? 'N/A' }}
                </div>
                <div class="detail-row">
                    <span class="detail-label">Order Status:</span> {{ $order['status'] }}
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Status:</span> {{ $order['payment_status'] }}
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Method:</span> {{ $order['payment_method'] }}
                </div>
                <div class="detail-row">
                    <span class="detail-label">Currency:</span> {{ $order['currency'] }}
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
                @foreach($order['items'] as $item)
                <tr>
                    <td>
                        <div class="product-name">{{ $item['product_name'] }}</div>
                        @if(isset($item['description']) && $item['description'])
                        <div class="product-description">{{ $item['description'] }}</div>
                        @endif
                        @if(isset($item['product_sku']) && $item['product_sku'])
                        <div class="product-sku">SKU: {{ $item['product_sku'] }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ $order['currency_symbol'] }}{{ number_format($item['price'], 2) }}</td>
                    <td class="text-center">{{ $item['quantity'] }}</td>
                    <td class="text-center">
                        <span class="pv-badge">{{ $item['pv_points'] }} PV</span>
                    </td>
                    <td class="text-right">{{ $order['currency_symbol'] }}{{ number_format($item['total'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="total-label">Subtotal:</td>
                    <td class="total-amount">{{ $order['currency_symbol'] }}{{ number_format($order['subtotal'], 2) }}</td>
                </tr>
                @if($order['discount'] > 0)
                <tr>
                    <td class="total-label">Discount:</td>
                    <td class="total-amount">-{{ $order['currency_symbol'] }}{{ number_format($order['discount'], 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td class="total-label">Tax ({{ $order['tax_rate'] ?? 0 }}%):</td>
                    <td class="total-amount">{{ $order['currency_symbol'] }}{{ number_format($order['tax'], 2) }}</td>
                </tr>
                <tr>
                    <td class="total-label">Shipping:</td>
                    <td class="total-amount">{{ $order['currency_symbol'] }}{{ number_format($order['shipping'], 2) }}</td>
                </tr>
                <tr class="grand-total">
                    <td class="total-label">TOTAL:</td>
                    <td class="total-amount">{{ $order['currency_symbol'] }}{{ number_format($order['total'], 2) }}</td>
                </tr>
                <tr class="pv-total">
                    <td class="total-label">Total PV Points:</td>
                    <td class="total-amount">{{ $order['pv_points'] }} PV</td>
                </tr>
            </table>
        </div>

        <!-- MLM Commission Section -->
        @if(isset($order['commission_info']))
        <div class="commission-section">
            <div class="section-title">MLM Commission Breakdown</div>
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
            <div class="notes-title">✓ Payment Confirmed</div>
            <div>Thank you! Your payment has been successfully processed on {{ date('M d, Y', strtotime($order['order_date'])) }}.</div>
        </div>
        @endif

        <!-- Notes Section -->
        @if(isset($order['notes']) && $order['notes'])
        <div class="notes-section">
            <div class="notes-title">Additional Notes</div>
            <div>{{ $order['notes'] }}</div>
        </div>
        @endif

        <!-- Terms and Conditions -->
        @if(isset($order['terms']) && $order['terms'])
        <div class="terms-section">
            <div class="terms-title">Terms & Conditions</div>
            <div class="terms-text">{{ $order['terms'] }}</div>
        </div>
        @endif

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-label">Customer Signature</div>
            </div>
            <div class="signature-box">
                <div class="signature-label">Authorized Signature</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div style="margin-bottom: 8px;">
                <strong>Thank you for your business!</strong>
            </div>
            <div>This invoice was generated on {{ date('M d, Y \a\t h:i A') }}</div>
            <div style="margin-top: 4px;">
                For questions about this invoice, please contact us at {{ $order['vendor_info']['email'] ?? 'support@multivendor.com' }}
            </div>
        </div>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            // Optional: Uncomment to auto-print
            // window.print();
        };
        
        // Close window after printing
        window.onafterprint = function() {
            // Optional: Uncomment to auto-close
            // window.close();
        };
    </script>
</body>
</html>
