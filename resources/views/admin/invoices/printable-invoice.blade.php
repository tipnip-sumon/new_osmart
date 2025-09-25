<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $orderData['id'] }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            line-height: 1.3;
            color: #000;
            font-size: 11px;
            background: white;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }
        
        .invoice-container {
            width: 100%;
            max-width: none;
            margin: 0;
            padding: 8px;
            background: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Print Preview Controls */
        .print-controls {
            position: fixed;
            top: 10px;
            right: 10px;
            background: #fff;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        
        .print-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }
        
        .print-btn:hover {
            background: #0056b3;
        }
        
        /* Header */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 2px solid #000;
        }
        
        .company-info h1 {
            font-size: 20px;
            font-weight: bold;
            color: #000;
            margin-bottom: 2px;
        }
        
        .company-info p {
            font-size: 9px;
            color: #666;
            margin: 1px 0;
        }
        
        .invoice-title {
            text-align: right;
        }
        
        .invoice-title h2 {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            margin-bottom: 3px;
        }
        
        .invoice-number {
            font-size: 12px;
            font-weight: bold;
            color: #666;
        }
        
        /* Invoice Details */
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        
        .invoice-meta, .bill-to {
            width: 48%;
        }
        
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .detail-item {
            margin-bottom: 3px;
            font-size: 9px;
        }
        
        .detail-label {
            font-weight: bold;
            color: #333;
            display: inline-block;
            width: 60px;
        }
        
        .bill-to-box {
            border: 1px solid #ddd;
            padding: 8px;
            background: #f9f9f9;
            border-radius: 2px;
        }
        
        .customer-name {
            font-size: 11px;
            font-weight: bold;
            color: #000;
            margin-bottom: 4px;
        }
        
        /* Items Table */
        .items-section {
            margin-bottom: 12px;
            flex: 1;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            font-size: 9px;
        }
        
        .items-table th {
            background: #f0f0f0;
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
            color: #000;
        }
        
        .items-table td {
            border: 1px solid #000;
            padding: 4px 3px;
            font-size: 8px;
            vertical-align: top;
        }
        
        .item-description {
            font-weight: 500;
            color: #000;
            font-size: 9px;
        }
        
        .item-code {
            font-size: 7px;
            color: #666;
            margin-top: 1px;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        /* Totals Section */
        .totals-section {
            margin-top: 8px;
            display: flex;
            justify-content: flex-end;
        }
        
        .totals-table {
            width: 250px;
            border-collapse: collapse;
        }
        
        .totals-table td {
            padding: 4px 8px;
            border-bottom: 1px solid #ddd;
            font-size: 9px;
        }
        
        .totals-table .label {
            font-weight: bold;
            text-align: right;
            width: 60%;
        }
        
        .totals-table .amount {
            text-align: right;
            font-weight: bold;
            width: 40%;
        }
        
        .grand-total {
            background: #f0f0f0;
            border: 1px solid #000 !important;
            font-size: 11px !important;
            font-weight: bold;
        }
        
        /* Amount in Words */
        .amount-words {
            margin: 8px 0;
            padding: 8px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 2px;
        }
        
        .amount-words-label {
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 2px;
        }
        
        .amount-words-text {
            font-size: 8px;
            font-style: italic;
            text-transform: capitalize;
        }
        
        /* Footer */
        .invoice-footer {
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid #000;
        }
        
        .footer-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .footer-column {
            width: 30%;
        }
        
        .footer-title {
            font-weight: bold;
            font-size: 8px;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 20px;
            padding-top: 2px;
            text-align: center;
            font-size: 7px;
        }
        
        .thank-you {
            text-align: center;
            margin-top: 8px;
            font-size: 10px;
            font-weight: bold;
            color: #000;
        }
        
        /* Terms */
        .terms {
            margin-top: 8px;
            font-size: 7px;
            color: #666;
            line-height: 1.2;
        }
        
        .terms h4 {
            font-size: 8px;
            color: #000;
            margin-bottom: 2px;
        }
        
        /* Print Styles */
        @media print {
            body { 
                font-size: 9px !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0 !important;
                padding: 0 !important;
                zoom: 100% !important;
                transform: scale(1) !important;
            }
            
            .print-controls {
                display: none !important;
            }
            
            .invoice-container {
                margin: 0 !important;
                padding: 5px !important;
                max-width: none !important;
                min-height: auto !important;
                overflow: visible !important;
            }
            
            @page {
                margin: 0 !important;
                size: A4 !important;
            }
            
            .invoice-header {
                margin-bottom: 5px !important;
                padding-bottom: 5px !important;
            }
            
            .company-info h1 {
                font-size: 16px !important;
            }
            
            .invoice-title h2 {
                font-size: 20px !important;
            }
            
            .invoice-details {
                margin-bottom: 8px !important;
            }
            
            .items-section {
                margin-bottom: 8px !important;
            }
            
            .totals-section {
                margin-top: 5px !important;
            }
            
            .amount-words {
                margin: 5px 0 !important;
                padding: 5px !important;
            }
            
            .invoice-footer {
                margin-top: 5px !important;
                padding-top: 5px !important;
            }
            
            .footer-section {
                margin-bottom: 5px !important;
            }
            
            .signature-line {
                margin-top: 10px !important;
            }
            
            .thank-you {
                margin-top: 5px !important;
            }
            
            .terms {
                margin-top: 5px !important;
            }
        }
        
        @media screen {
            .invoice-container {
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                margin: 10px auto;
            }
        }
    </style>
</head>
<body>
    <!-- Print Controls -->
    <div class="print-controls">
        <button class="print-btn" onclick="window.print()">🖨️ Print</button>
        <button class="print-btn" onclick="window.close()" style="background: #6c757d;">✕ Close</button>
    </div>

    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1>{{ $settings['company_name'] ?? config('app.name', 'Your Company Ltd.') }}</h1>
                <p>Multi-Vendor E-commerce Platform</p>
                <p>📍 {{ $settings['company_address'] ?? 'House #123, Road #456, Dhanmondi, Dhaka-1205, Bangladesh' }}</p>
                <p>📞 {{ $settings['company_phone'] ?? '+880 1700-000000' }} | ✉️ {{ $settings['company_email'] ?? 'info@company.com' }}</p>
                <p>🌐 {{ $settings['company_website'] ?? 'www.company.com' }} | TIN: {{ $settings['company_tin'] ?? '123456789012' }}</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <div class="invoice-number">#{{ $orderData['id'] }}</div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="invoice-meta">
                <div class="section-title">Invoice Details</div>
                <div class="detail-item">
                    <span class="detail-label">Date:</span>
                    {{ date('d M, Y', strtotime($orderData['order_date'])) }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Due Date:</span>
                    {{ date('d M, Y', strtotime($orderData['order_date'] . ' +30 days')) }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status:</span>
                    {{ ucfirst($orderData['status']) }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Payment:</span>
                    {{ $orderData['payment_method'] }}
                </div>
                @if($orderData['tracking_number'])
                <div class="detail-item">
                    <span class="detail-label">Tracking:</span>
                    {{ $orderData['tracking_number'] }}
                </div>
                @endif
            </div>
            
            <div class="bill-to">
                <div class="section-title">Bill To</div>
                <div class="bill-to-box">
                    <div class="customer-name">{{ $orderData['customer'] }}</div>
                    @if($orderData['customer_email'] && $orderData['customer_email'] !== 'N/A')
                    <div class="detail-item">📧 {{ $orderData['customer_email'] }}</div>
                    @endif
                    @if($orderData['customer_phone'] && $orderData['customer_phone'] !== 'N/A')
                    <div class="detail-item">📞 {{ $orderData['customer_phone'] }}</div>
                    @endif
                    @if($orderData['billing_address'] && $orderData['billing_address'] !== 'N/A')
                    <div class="detail-item">📍 {{ $orderData['billing_address'] }}</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="items-section">
            <table class="items-table">
                <thead>
                    <tr>
                        <th width="8%">SL</th>
                        <th width="42%">Description</th>
                        <th width="15%">Unit Price</th>
                        <th width="10%">Qty</th>
                        <th width="25%">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderData['items'] as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <div class="item-description">{{ $item['product_name'] }}</div>
                            <div class="item-code">Product ID: {{ $item['product_id'] }}</div>
                        </td>
                        <td class="text-right">৳ {{ number_format($item['price'], 2) }}</td>
                        <td class="text-center">{{ $item['quantity'] }}</td>
                        <td class="text-right">৳ {{ number_format($item['total'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="amount">৳ {{ number_format($orderData['subtotal'], 2) }}</td>
                </tr>
                @if($orderData['discount'] > 0)
                <tr>
                    <td class="label">Discount:</td>
                    <td class="amount">- ৳ {{ number_format($orderData['discount'], 2) }}</td>
                </tr>
                @endif
                @if($orderData['tax'] > 0)
                <tr>
                    <td class="label">VAT/Tax ({{ round(($orderData['tax'] / $orderData['subtotal']) * 100, 1) }}%):</td>
                    <td class="amount">৳ {{ number_format($orderData['tax'], 2) }}</td>
                </tr>
                @endif
                @if($orderData['shipping'] > 0)
                <tr>
                    <td class="label">Shipping:</td>
                    <td class="amount">৳ {{ number_format($orderData['shipping'], 2) }}</td>
                </tr>
                @endif
                <tr class="grand-total">
                    <td class="label">TOTAL:</td>
                    <td class="amount">৳ {{ number_format($orderData['total'], 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Amount in Words -->
        <div class="amount-words">
            <div class="amount-words-label">Amount in Words:</div>
            <div class="amount-words-text">
                {{ ucfirst(convertNumberToWords($orderData['total'])) }} Taka Only
            </div>
        </div>

        @if($orderData['notes'])
        <!-- Notes -->
        <div class="amount-words">
            <div class="amount-words-label">Notes:</div>
            <div class="amount-words-text">{{ $orderData['notes'] }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="footer-section">
                <div class="footer-column">
                    <div class="footer-title">Payment Terms</div>
                    <p style="font-size: 7px;">Payment due within 30 days.</p>
                </div>
                <div class="footer-column">
                    <div class="footer-title">Return Policy</div>
                    <p style="font-size: 7px;">Items can be returned within 7 days.</p>
                </div>
                <div class="footer-column">
                    <div class="footer-title">Authorized Signature</div>
                    <div class="signature-line">Company Representative</div>
                </div>
            </div>
            
            <div class="thank-you">
                Thank you for your business! 🙏
            </div>
            
            <div class="terms">
                <h4>Terms & Conditions:</h4>
                <p>1. Computer-generated invoice. 2. Dhaka jurisdiction. 3. No returns except defects.</p>
            </div>
        </div>
    </div>

    <script>
        // Auto-print functionality (optional)
        function autoPrint() {
            setTimeout(function() {
                window.print();
            }, 1000);
        }
        
        // Uncomment the line below to enable auto-print
        // window.onload = autoPrint;
        
        // Close window after printing
        window.onafterprint = function() {
            if(confirm('Close this window?')) {
                window.close();
            }
        };
    </script>
</body>
</html>
