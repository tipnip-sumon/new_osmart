<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $orderData['id'] }}</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px; 
            line-height: 1.4;
            color: #333;
            background: #f5f5f5;
        }
        
        .preview-controls {
            background: #fff;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .btn {
            padding: 8px 16px;
            margin: 0 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: #007bff;
            color: white;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.8;
        }
        
        .invoice-wrapper {
            max-width: 21cm;
            margin: 20px auto;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .invoice-container {
            padding: 2cm;
            min-height: 29.7cm;
            background: white;
        }
        
        /* Header Section */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 20px;
        }
        
        .company-info {
            flex: 1;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .company-details {
            color: #6c757d;
            font-size: 11px;
            line-height: 1.5;
        }
        
        .invoice-meta {
            text-align: right;
            min-width: 200px;
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: 700;
            color: #e74c3c;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .invoice-details table {
            border-collapse: collapse;
            font-size: 11px;
        }
        
        .invoice-details td {
            padding: 4px 8px;
            border: 1px solid #dee2e6;
        }
        
        .invoice-details td:first-child {
            background: #f8f9fa;
            font-weight: 600;
            text-align: right;
            min-width: 80px;
        }
        
        /* Billing Information */
        .billing-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 40px;
        }
        
        .billing-info, .shipping-info {
            flex: 1;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            border-left: 4px solid #007bff;
        }
        
        .billing-info h4, .shipping-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .address-line {
            margin-bottom: 6px;
            color: #495057;
            font-size: 11px;
        }
        
        .address-line strong {
            color: #2c3e50;
            font-weight: 600;
        }
        
        /* Items Table */
        .items-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 8px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }
        
        .items-table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #5a6cb8;
        }
        
        .items-table tbody td {
            padding: 10px 8px;
            border: 1px solid #dee2e6;
            vertical-align: middle;
        }
        
        .items-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .items-table tbody tr:hover {
            background: #e3f2fd;
        }
        
        .product-name {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        /* Totals Section */
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        
        .totals-table {
            width: 350px;
            border-collapse: collapse;
            font-size: 12px;
        }
        
        .totals-table td {
            padding: 8px 12px;
            border: 1px solid #dee2e6;
        }
        
        .totals-table td:first-child {
            background: #f8f9fa;
            font-weight: 600;
            text-align: right;
            color: #495057;
        }
        
        .totals-table td:last-child {
            text-align: right;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .total-row {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            font-size: 14px;
            font-weight: 700;
        }
        
        .total-row td {
            color: white !important;
        }
        
        /* Footer */
        .invoice-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
        }
        
        .terms-section {
            margin-bottom: 20px;
        }
        
        .terms-title {
            font-size: 12px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .terms-content {
            font-size: 10px;
            color: #6c757d;
            line-height: 1.5;
        }
        
        .footer-signature {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }
        
        .signature-box {
            text-align: center;
            min-width: 200px;
        }
        
        .signature-line {
            border-bottom: 1px solid #333;
            margin-bottom: 8px;
            height: 50px;
        }
        
        .signature-label {
            font-size: 10px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .thank-you {
            text-align: center;
            margin-top: 30px;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 6px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Currency Formatting */
        .currency {
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }
        
        /* Print Styles */
        @media print {
            body {
                background: white !important;
                font-size: 10px;
            }
            
            .preview-controls {
                display: none !important;
            }
            
            .invoice-wrapper {
                box-shadow: none !important;
                margin: 0 !important;
                border-radius: 0 !important;
            }
            
            .invoice-container {
                padding: 15mm !important;
                min-height: auto !important;
            }
            
            @page {
                size: A4;
                margin: 15mm;
            }
            
            .items-table {
                page-break-inside: avoid;
            }
            
            .totals-section {
                page-break-inside: avoid;
            }
            
            .invoice-footer {
                page-break-inside: avoid;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .invoice-wrapper {
                margin: 10px;
            }
            
            .invoice-container {
                padding: 15px;
            }
            
            .invoice-header {
                flex-direction: column;
                gap: 20px;
            }
            
            .invoice-meta {
                text-align: left;
            }
            
            .billing-section {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Print Preview Controls -->
    <div class="preview-controls">
        <button class="btn btn-primary" onclick="window.print()">
            🖨️ Print Invoice
        </button>
        {{-- <button class="btn btn-success" onclick="downloadPDF()">
            📄 Download PDF
        </button> --}}
        <button class="btn btn-secondary" onclick="window.close()">
            ❌ Close
        </button>
    </div>

    <div class="invoice-wrapper">
        <div class="invoice-container">
            <!-- Header -->
            <div class="invoice-header">
                <div class="company-info">
                    <div class="company-name">{{ $settings['company_name'] ?? config('app.name', 'Your Company Name') }}</div>
                    <div class="company-details">
                        <div><strong>Address:</strong> {{ $settings['company_address'] ?? '123 Business Street, Dhaka-1205, Bangladesh' }}</div>
                        <div><strong>Phone:</strong> {{ $settings['company_phone'] ?? '+880-2-123456789' }} | <strong>Email:</strong> {{ $settings['company_email'] ?? 'info@company.com' }}</div>
                        <div><strong>Website:</strong> {{ $settings['company_website'] ?? 'www.yourcompany.com' }} | <strong>TIN:</strong> {{ $settings['company_tin'] ?? '123456789012' }}</div>
                        @if($settings['company_trade_license'] ?? null)
                        <div><strong>Trade License:</strong> {{ $settings['company_trade_license'] }} @if($settings['company_vat_number'] ?? null) | <strong>VAT No:</strong> {{ $settings['company_vat_number'] }}@endif</div>
                        @endif
                    </div>
                </div>
                <div class="invoice-meta">
                    <div class="invoice-title">Invoice</div>
                    <table class="invoice-details">
                        <tr>
                            <td>Invoice No:</td>
                            <td><strong>{{ $orderData['id'] }}</strong></td>
                        </tr>
                        <tr>
                            <td>Date:</td>
                            <td>{{ date('d M, Y', strtotime($orderData['order_date'])) }}</td>
                        </tr>
                        <tr>
                            <td>Due Date:</td>
                            <td>{{ date('d M, Y', strtotime($orderData['order_date'] . ' +30 days')) }}</td>
                        </tr>
                        <tr>
                            <td>Status:</td>
                            <td><strong style="color: #28a745;">{{ $orderData['status'] }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Billing Information -->
            <div class="billing-section">
                <div class="billing-info">
                    <h4>📍 Bill To</h4>
                    <div class="address-line"><strong>{{ $orderData['customer'] ?? 'N/A' }}</strong></div>
                    @if(!empty($orderData['customer_email']) && $orderData['customer_email'] !== 'N/A')
                    <div class="address-line">📧 {{ $orderData['customer_email'] }}</div>
                    @endif
                    @if(!empty($orderData['customer_phone']) && $orderData['customer_phone'] !== 'N/A')
                    <div class="address-line">📱 {{ $orderData['customer_phone'] }}</div>
                    @endif
                    @if(!empty($orderData['billing_address']) && $orderData['billing_address'] !== 'N/A')
                    <div class="address-line">🏠 {{ $orderData['billing_address'] }}</div>
                    @endif
                </div>
                
                <div class="shipping-info">
                    <h4>🚚 Payment Method</h4>
                    <div class="address-line"><strong>{{ $orderData['payment_method'] ?? 'Not Specified' }}</strong></div>
                    @if(!empty($orderData['tracking_number']))
                    <div class="address-line">📦 Tracking: {{ $orderData['tracking_number'] }}</div>
                    @endif
                    <div class="address-line">💰 Currency: <strong>BDT (৳)</strong></div>
                    <div class="address-line">🗓️ Generated: {{ date('d M, Y H:i A') }}</div>
                </div>
            </div>

            <!-- Items Section -->
            <div class="items-section">
                <div class="section-title">🛍️ Order Details</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th width="5%">SL</th>
                            <th width="45%">Product Description</th>
                            <th width="15%">Unit Price</th>
                            <th width="10%">Quantity</th>
                            <th width="25%">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderData['items'] as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="product-name">{{ $item['product_name'] }}</td>
                            <td class="text-right currency">৳ {{ number_format($item['price'], 2) }}</td>
                            <td class="text-center">{{ $item['quantity'] }}</td>
                            <td class="text-right currency">৳ {{ number_format($item['total'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals Section -->
            <div class="totals-section">
                <table class="totals-table">
                    @if($orderData['subtotal'] > 0)
                    <tr>
                        <td>Subtotal:</td>
                        <td class="currency">৳ {{ number_format($orderData['subtotal'], 2) }}</td>
                    </tr>
                    @endif
                    @if($orderData['discount'] > 0)
                    <tr>
                        <td>Discount:</td>
                        <td class="currency" style="color: #e74c3c;">- ৳ {{ number_format($orderData['discount'], 2) }}</td>
                    </tr>
                    @endif
                    @if($orderData['tax'] > 0)
                    <tr>
                        <td>VAT/Tax (15%):</td>
                        <td class="currency">৳ {{ number_format($orderData['tax'], 2) }}</td>
                    </tr>
                    @endif
                    @if($orderData['shipping'] > 0)
                    <tr>
                        <td>Shipping Charge:</td>
                        <td class="currency">৳ {{ number_format($orderData['shipping'], 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td>Grand Total:</td>
                        <td class="currency">৳ {{ number_format($orderData['total'], 2) }}</td>
                    </tr>
                </table>
            </div>

            <!-- Amount in Words -->
            <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #28a745;">
                <strong>Amount in Words:</strong> 
                <span style="text-transform: capitalize; font-style: italic;">
                    {{ ucfirst(convertNumberToWords($orderData['total'])) }} Taka Only
                </span>
            </div>

            <!-- Footer -->
            <div class="invoice-footer">
                @if(!empty($orderData['notes']))
                <div class="terms-section">
                    <div class="terms-title">📝 Special Notes:</div>
                    <div class="terms-content">{{ $orderData['notes'] }}</div>
                </div>
                @endif
                
                <div class="terms-section">
                    <div class="terms-title">📋 Terms & Conditions:</div>
                    <div class="terms-content">
                        • Payment is due within 30 days of invoice date. • Please quote invoice number in all correspondence.
                        • Late payment may incur additional charges. • All disputes must be reported within 7 days.
                        • This invoice is computer generated and does not require physical signature.
                    </div>
                </div>

                <div class="footer-signature">
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <div class="signature-label">Customer Signature</div>
                    </div>
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <div class="signature-label">Authorized Signature</div>
                    </div>
                </div>

                <div class="thank-you">
                    🙏 Thank you for your business! 
                </div>
            </div>
        </div>
    </div>

    <script>
        function downloadPDF() {
            // Redirect to PDF download route
            window.location.href = `/admin/orders/{{ $orderData['id'] }}/printable-invoice/download`;
        }
        
        // Auto-focus for print
        window.addEventListener('load', function() {
            // Add slight delay for better print preview
            setTimeout(function() {
                document.body.style.visibility = 'visible';
            }, 100);
        });
    </script>
</body>
</html>
