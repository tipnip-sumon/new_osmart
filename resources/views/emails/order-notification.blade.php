<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .order-info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .order-items {
            margin: 20px 0;
        }
        .order-items table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-items th,
        .order-items td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .order-items th {
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-confirmed { background-color: #d4edda; color: #155724; }
        .status-processing { background-color: #cce7ff; color: #004085; }
        .status-shipped { background-color: #e2e3e5; color: #383d41; }
        .status-delivered { background-color: #d1ecf1; color: #0c5460; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .status-refunded { background-color: #ffeaa7; color: #856404; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $subject }}</h1>
        <p><strong>Order #{{ $order->order_number ?? $order->id }}</strong></p>
    </div>

    <div class="message">
        {!! nl2br(e($message)) !!}
    </div>

    <div class="order-info">
        <h3>Order Details</h3>
        <p><strong>Order Number:</strong> {{ $order->order_number ?? '#' . $order->id }}</p>
        <p><strong>Customer:</strong> {{ $order->customer->name ?? 'N/A' }}</p>
        <p><strong>Email:</strong> {{ $order->customer->email ?? 'N/A' }}</p>
        <p><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
        <p><strong>Order Status:</strong> 
            <span class="status-badge status-{{ $order->status }}">{{ $order->status_name }}</span>
        </p>
        <p><strong>Payment Status:</strong> 
            <span class="status-badge status-{{ $order->payment_status }}">{{ $order->payment_status_name }}</span>
        </p>
        <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
    </div>

    @if($order->items && $order->items->count() > 0)
    <div class="order-items">
        <h3>Order Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Product #' . $item->product_id }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><strong>Subtotal:</strong></td>
                    <td><strong>${{ number_format($order->subtotal ?? $order->total_amount, 2) }}</strong></td>
                </tr>
                @if($order->tax_amount > 0)
                <tr>
                    <td colspan="3">Tax:</td>
                    <td>${{ number_format($order->tax_amount, 2) }}</td>
                </tr>
                @endif
                @if($order->shipping_amount > 0)
                <tr>
                    <td colspan="3">Shipping:</td>
                    <td>${{ number_format($order->shipping_amount, 2) }}</td>
                </tr>
                @endif
                @if($order->discount_amount > 0)
                <tr>
                    <td colspan="3">Discount:</td>
                    <td>-${{ number_format($order->discount_amount, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td colspan="3"><strong>Total:</strong></td>
                    <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    @if($order->shipping_address)
    <div class="order-info">
        <h3>Shipping Address</h3>
        @if(is_array($order->shipping_address))
            <p>
                @foreach($order->shipping_address as $key => $value)
                    {{ ucfirst($key) }}: {{ $value }}<br>
                @endforeach
            </p>
        @else
            <p>{{ $order->shipping_address }}</p>
        @endif
    </div>
    @endif

    <div class="footer">
        <p>This email was sent regarding your order. If you have any questions, please contact our support team.</p>
        <p><strong>{{ config('app.name') }}</strong></p>
        <p>Email sent on: {{ now()->format('M d, Y h:i A') }}</p>
    </div>
</body>
</html>
