<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $orderData['id'] }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 15px;
        }
        
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #000;
            margin-bottom: 3px;
        }
        
        .company-info {
            font-size: 10px;
            color: #666;
            line-height: 1.2;
        }
        
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            margin: 15px 0 8px 0;
        }
        
        .invoice-number {
            font-size: 12px;
            color: #666;
        }
        
        .invoice-details {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .invoice-details .row {
            display: table-row;
        }
        
        .invoice-details .col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 3px 0;
        }
        
        .invoice-details .col:last-child {
            text-align: right;
        }
        
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #000;
            margin-bottom: 8px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }
        
        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: left;
        }
        
        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
        }
        
        .items-table .text-center {
            text-align: center;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        .totals-section {
            float: right;
            width: 250px;
            margin-top: 10px;
        }
        
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        
        .totals-table td {
            padding: 4px 8px;
            border-bottom: 1px solid #ddd;
        }
        
        .totals-table .total-label {
            font-weight: bold;
            text-align: right;
        }
        
        .totals-table .total-amount {
            text-align: right;
            font-weight: bold;
        }
        
        .totals-table .grand-total {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            font-size: 12px;
            font-weight: bold;
        }
        
        .payment-info {
            clear: both;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 10px;
        }
        
        .notes-section {
            margin-top: 15px;
            padding: 8px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #000;
            font-size: 10px;
            color: #666;
        }
        
        .clearfix {
            clear: both;
        }
        
        .compact-info {
            font-size: 10px;
            line-height: 1.2;
        }
    </style>
</head>
<body>
    <!-- Invoice Header -->
    <div class="invoice-header">
        <div class="company-name">Multi-Vendor E-commerce</div>
        <div class="company-info">
            123 Business Street, Dhaka, Bangladesh | Phone: +880 1700-000000 | Email: info@company.com
        </div>
        <div class="invoice-title">INVOICE</div>
        <div class="invoice-number">Invoice #{{ $orderData['id'] }}</div>
    </div>

    <!-- Invoice Details -->
    <div class="invoice-details">
        <div class="row">
            <div class="col">
                <div class="section-title">BILL TO:</div>
                <div class="compact-info">
                    <strong>{{ $orderData['customer'] }}</strong><br>
                    @if(isset($orderData['billing_address']) && is_array($orderData['billing_address']))
                        {{ $orderData['billing_address']['street'] ?? 'N/A' }}<br>
                        {{ $orderData['billing_address']['city'] ?? 'N/A' }}, {{ $orderData['billing_address']['state'] ?? 'N/A' }} {{ $orderData['billing_address']['zip'] ?? 'N/A' }}<br>
                        {{ $orderData['billing_address']['country'] ?? 'Bangladesh' }}<br>
                    @else
                        Address not provided<br>
                        Bangladesh<br>
                    @endif
                    <strong>Email:</strong> {{ $orderData['customer_email'] }}<br>
                    <strong>Phone:</strong> {{ $orderData['customer_phone'] }}
                </div>
            </div>
            <div class="col">
                <div class="section-title">INVOICE DETAILS:</div>
                <div class="compact-info">
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($orderData['order_date'])->format('M j, Y') }}<br>
                    <strong>Payment:</strong> {{ $orderData['payment_method'] }}<br>
                    <strong>Currency:</strong> BDT (৳)<br>
                    @if(isset($orderData['pv_points']) && $orderData['pv_points'] > 0)
                    <strong>PV Points:</strong> {{ number_format($orderData['pv_points']) }}<br>
                    @endif
                    <strong>Status:</strong> Paid
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="section-title">ORDER ITEMS:</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 45%;">Product</th>
                <th style="width: 15%;">Price</th>
                <th style="width: 10%;">Qty</th>
                <th style="width: 15%;">PV</th>
                <th style="width: 15%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orderData['items'] as $item)
            <tr>
                <td>
                    <strong>{{ $item['product_name'] }}</strong><br>
                    <small style="color: #666;">ID: {{ $item['product_id'] }}</small>
                </td>
                <td class="text-center">৳{{ number_format($item['price'], 2) }}</td>
                <td class="text-center">{{ $item['quantity'] }}</td>
                <td class="text-center">
                    @if(isset($item['pv_points']) && $item['pv_points'] > 0)
                        {{ $item['pv_points'] }}
                    @else
                        0
                    @endif
                </td>
                <td class="text-right">৳{{ number_format($item['total'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals Section -->
    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td class="total-label">Subtotal:</td>
                <td class="total-amount">৳{{ number_format($orderData['subtotal'], 2) }}</td>
            </tr>
            @if($orderData['discount'] > 0)
            <tr>
                <td class="total-label">Discount:</td>
                <td class="total-amount" style="color: #dc3545;">-৳{{ number_format($orderData['discount'], 2) }}</td>
            </tr>
            @endif
            @if($orderData['tax'] > 0)
            <tr>
                <td class="total-label">Tax:</td>
                <td class="total-amount">৳{{ number_format($orderData['tax'], 2) }}</td>
            </tr>
            @endif
            @if($orderData['shipping'] > 0)
            <tr>
                <td class="total-label">Shipping:</td>
                <td class="total-amount">৳{{ number_format($orderData['shipping'], 2) }}</td>
            </tr>
            @endif
            <tr class="grand-total">
                <td class="total-label">TOTAL:</td>
                <td class="total-amount">৳{{ number_format($orderData['total'], 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="clearfix"></div>

    <!-- Payment Information -->
    <div class="payment-info">
        <div class="section-title">PAYMENT & MLM INFO:</div>
        <div style="display: table; width: 100%;">
            <div style="display: table-row;">
                <div style="display: table-cell; width: 50%;" class="compact-info">
                    <strong>Method:</strong> {{ $orderData['payment_method'] }}<br>
                    <strong>Status:</strong> Paid<br>
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($orderData['order_date'])->format('M j, Y') }}
                </div>
                <div style="display: table-cell; width: 50%; vertical-align: top;" class="compact-info">
                    @if(isset($orderData['pv_points']) && $orderData['pv_points'] > 0)
                    <strong>MLM Benefits:</strong><br>
                    PV Points: {{ number_format($orderData['pv_points']) }}<br>
                    Commission Eligible: Yes
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Notes Section -->
    @if(isset($orderData['notes']) && !empty($orderData['notes']))
    <div class="notes-section">
        <div class="section-title">NOTES:</div>
        {{ $orderData['notes'] }}
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <strong>Thank You for Your Business!</strong><br>
        Multi-Vendor E-commerce Platform | For support: info@company.com | +880 1700-000000
    </div>
</body>
</html>
