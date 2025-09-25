<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Email</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .content {
            padding: 30px;
        }
        .invoice-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        .detail-item {
            margin-bottom: 15px;
        }
        .detail-label {
            font-weight: 600;
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .detail-value {
            color: #333;
            font-size: 16px;
        }
        .amount-section {
            text-align: center;
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .amount-label {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }
        .amount {
            font-size: 32px;
            font-weight: bold;
            color: #28a745;
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-paid {
            background: #d4edda;
            color: #155724;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .custom-message {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #2196f3;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>📧 Invoice Notification</h1>
        </div>
        
        <div class="content">
            <h2>Hello {{ $order->customer->name ?? 'Valued Customer' }}!</h2>
            
            <p>Thank you for your order! Please find your invoice attached to this email.</p>
            
            @if($customMessage)
            <div class="custom-message">
                <p><strong>Personal Message:</strong></p>
                <p>{{ $customMessage }}</p>
            </div>
            @endif
            
            <div class="invoice-info">
                <h3>📄 Invoice Summary</h3>
                
                <div class="invoice-details">
                    <div class="detail-item">
                        <div class="detail-label">Invoice Number</div>
                        <div class="detail-value"><strong>INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Order Date</div>
                        <div class="detail-value">{{ $order->created_at->format('M d, Y') }}</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Payment Status</div>
                        <div class="detail-value">
                            <span class="status-badge {{ $order->payment_status == 'paid' ? 'status-paid' : 'status-pending' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Payment Method</div>
                        <div class="detail-value">{{ $order->payment_method ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="amount-section">
                    <div class="amount-label">Total Amount</div>
                    <div class="amount">Tk {{ number_format($order->total_amount, 2) }}</div>
                </div>
            </div>
            
            <div class="invoice-info">
                <h3>🛍️ Order Items</h3>
                @if($order->items && $order->items->count() > 0)
                    @foreach($order->items as $item)
                    <div style="border-bottom: 1px solid #eee; padding: 10px 0;">
                        <strong>{{ $item->product->name ?? 'Product' }}</strong> (x{{ $item->quantity }})<br>
                        <span style="color: #666;">Tk {{ number_format($item->price * $item->quantity, 2) }}</span>
                    </div>
                    @endforeach
                @else
                    <p>No items found.</p>
                @endif
            </div>
            
            @if($order->shipping_address)
            <div class="invoice-info">
                <h3>🚚 Shipping Address</h3>
                <div>
                    @if(isset($order->shipping_address['name']))
                        {{ $order->shipping_address['name'] }}<br>
                    @endif
                    @if(isset($order->shipping_address['street']))
                        {{ $order->shipping_address['street'] }}<br>
                    @endif
                    @if(isset($order->shipping_address['street2']) && $order->shipping_address['street2'])
                        {{ $order->shipping_address['street2'] }}<br>
                    @endif
                    @if(isset($order->shipping_address['city']))
                        {{ $order->shipping_address['city'] }}@if(isset($order->shipping_address['state'])), {{ $order->shipping_address['state'] }}@endif @if(isset($order->shipping_address['zip'])){{ $order->shipping_address['zip'] }}@endif<br>
                    @endif
                    @if(isset($order->shipping_address['country']))
                        {{ $order->shipping_address['country'] }}
                    @endif
                </div>
            </div>
            @endif
            
            <p>If you have any questions about this invoice, please don't hesitate to contact us.</p>
            
            <p>Thank you for your business!</p>
            
            <p>Best regards,<br>
            {{ config('app.name', 'MultiVendor Marketplace') }} Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this email address.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'MultiVendor Marketplace') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-label {
            font-weight: 600;
            color: #666;
            font-size: 14px;
        }
        .detail-value {
            font-size: 16px;
            color: #333;
            margin-top: 5px;
        }
        .amount-highlight {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .amount-highlight .amount {
            font-size: 24px;
            font-weight: bold;
            margin: 5px 0;
        }
        .message-box {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }
        .footer p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            margin: 15px 0;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-paid {
            background: #d4edda;
            color: #155724;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .invoice-details {
                grid-template-columns: 1fr;
            }
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>📧 Invoice Ready</h1>
            <p>Your invoice from MultiVendor Marketplace</p>
        </div>

        <div class="content">
            <h2>Hello {{ $order['customer'] }}!</h2>
            
            <p>Thank you for your recent purchase. Please find your invoice attached to this email.</p>

            @if($customMessage)
            <div class="message-box">
                <strong>Message from our team:</strong>
                <p>{{ $customMessage }}</p>
            </div>
            @endif

            <div class="invoice-info">
                <h3 style="margin-top: 0; color: #667eea;">📄 Invoice Details</h3>
                
                <div class="invoice-details">
                    <div class="detail-item">
                        <div class="detail-label">Invoice Number</div>
                        <div class="detail-value"><strong>{{ $order['invoice_number'] }}</strong></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Order Date</div>
                        <div class="detail-value">{{ date('M d, Y', strtotime($order['order_date'])) }}</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Payment Status</div>
                        <div class="detail-value">
                            <span class="status-badge {{ $order['payment_status'] == 'Paid' ? 'status-paid' : 'status-pending' }}">
                                {{ $order['payment_status'] }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Payment Method</div>
                        <div class="detail-value">{{ $order['payment_method'] }}</div>
                    </div>
                </div>
            </div>

            <div class="amount-highlight">
                <div>Total Amount</div>
                <div class="amount">{{ $order['currency_symbol'] }}{{ number_format($order['total'], 2) }}</div>
                @if($order['pv_points'] > 0)
                <div style="font-size: 14px; opacity: 0.9;">
                    🎯 You earned {{ $order['pv_points'] }} PV Points!
                </div>
                @endif
            </div>

            <div style="text-align: center;">
                <a href="#" class="btn">View Order Details</a>
            </div>

            <div style="margin-top: 30px;">
                <h4>📦 Items in this order:</h4>
                <ul style="padding-left: 20px;">
                    @foreach($order['items'] as $item)
                    <li style="margin: 8px 0;">
                        <strong>{{ $item['product_name'] }}</strong> 
                        <span style="color: #666;">× {{ $item['quantity'] }}</span>
                        <span style="float: right; color: #667eea; font-weight: 600;">
                            {{ $order['currency_symbol'] }}{{ number_format($item['total'], 2) }}
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>

            @if($order['shipping_address'])
            <div style="margin-top: 25px;">
                <h4>🚚 Shipping Address:</h4>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; color: #666;">
                    {{ $order['shipping_address']['name'] }}<br>
                    {{ $order['shipping_address']['street'] }}<br>
                    @if($order['shipping_address']['street2'])
                        {{ $order['shipping_address']['street2'] }}<br>
                    @endif
                    {{ $order['shipping_address']['city'] }}, {{ $order['shipping_address']['state'] }} {{ $order['shipping_address']['zip'] }}<br>
                    {{ $order['shipping_address']['country'] }}
                </div>
            </div>
            @endif

            <div style="margin-top: 25px; padding: 20px; background: #e8f5e8; border-radius: 8px; border-left: 4px solid #28a745;">
                <h4 style="color: #28a745; margin-top: 0;">💚 Thank You!</h4>
                <p style="margin-bottom: 0; color: #666;">
                    We appreciate your business and look forward to serving you again. 
                    If you have any questions about this invoice, please don't hesitate to contact us.
                </p>
            </div>
        </div>

        <div class="footer">
            <p><strong>MultiVendor Marketplace</strong></p>
            <p>{{ $order['vendor_info']['address'] ?? '789 Business Ave, Business City, BC 12345' }}</p>
            <p>Email: {{ $order['vendor_info']['email'] ?? 'support@multivendor.com' }} | Phone: {{ $order['vendor_info']['phone'] ?? '+1 (555) 987-6543' }}</p>
            <p style="margin-top: 15px; font-size: 12px; color: #999;">
                This is an automated message. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>
