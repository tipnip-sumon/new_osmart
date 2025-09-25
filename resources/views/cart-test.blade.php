<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .test-product {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 10px;
            border-radius: 8px;
        }
        .cart-count-display {
            background: red;
            color: white;
            padding: 5px 10px;
            border-radius: 50%;
            margin: 10px;
        }
    </style>
</head>
<body>
    <h1>Cart Functionality Test</h1>
    
    <div class="currency-info">
        <p><strong>Currency Symbol:</strong> {{ currencySymbol() }}</p>
        <p><strong>Currency Text:</strong> {{ currencyText() }}</p>
        <p><strong>Sample Price:</strong> {{ formatCurrency(1500) }}</p>
    </div>
    
    <div class="cart-count-display" id="cart-count">0</div>
    
    <div class="test-product card-product" data-product-id="1">
        <h3>Test Product 1</h3>
        <p>Price: $19.99</p>
        <button class="quick-add" data-action="add-to-cart" data-product-id="1">Add to Cart</button>
    </div>
    
    <div class="test-product card-product" data-product-id="2">
        <h3>Test Product 2</h3>
        <p>Price: $29.99</p>
        <button class="quick-add" data-action="add-to-cart" data-product-id="2">Add to Cart</button>
    </div>
    
    <div>
        <h3>Cart Items:</h3>
        <div id="cart-items-container"></div>
    </div>
    
    <script src="{{ asset('assets/ecomus/js/cart-wishlist.js') }}"></script>
    <script>
        // Simple toast function for testing
        window.toastr = {
            success: function(msg) { alert('Success: ' + msg); },
            error: function(msg) { alert('Error: ' + msg); },
            info: function(msg) { alert('Info: ' + msg); }
        };
    </script>
</body>
</html>