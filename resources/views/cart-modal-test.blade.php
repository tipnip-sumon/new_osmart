<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cart Modal Test</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/ecomus/css/custom-laravel.css') }}">
    
    <style>
        body {
            background: #f5f5f5;
            padding: 2rem;
        }
        .test-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .test-btn {
            padding: 1rem 2rem;
            background: #4F46E5;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            margin: 0.5rem;
            transition: all 0.3s ease;
        }
        .test-btn:hover {
            background: #4338CA;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>Cart Modal Test</h1>
        <p>Click the button below to test the cart modal with shipping calculator:</p>
        
        <button type="button" class="test-btn" data-bs-toggle="modal" data-bs-target="#shoppingCart">
            Open Cart Modal
        </button>
        
        <div class="mt-4">
            <h5>Test Features:</h5>
            <ul>
                <li>✅ Click "Estimate Shipping" button</li>
                <li>✅ Select district from dropdown</li>
                <li>✅ Click "Calculate Shipping" button</li>
                <li>✅ Use close button (X) in header to go back</li>
                <li>✅ Try "Add Order Note" feature</li>
                <li>✅ Press ESC key to close</li>
            </ul>
        </div>
        
        <div class="mt-4 alert alert-info">
            <strong>DeliveryCharge Data:</strong><br>
            Make sure you have run: <code>php artisan db:seed --class=DeliveryChargeSeeder</code>
        </div>
    </div>

    <!-- Include the cart modal -->
    @include('layouts.ecomus.cart-modal')
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Shipping Calculator -->
    <script src="{{ asset('assets/ecomus/js/shipping-calculator.js') }}"></script>
    
    <script>
        // Make currency variables available
        window.currencySymbol = '৳';
        window.currencyText = 'BDT';
        
        // Test console logging
        console.log('Cart modal test page loaded');
        console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        // Debug close button clicks
        document.addEventListener('click', (e) => {
            if (e.target.closest('.tf-mini-cart-tool-close-btn')) {
                console.log('Close button click detected in global handler');
            }
            console.log('Click detected on:', e.target);
        });
        
        // Add test button functionality
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const testCloseBtn = document.createElement('button');
                testCloseBtn.textContent = 'Test Close All Tools';
                testCloseBtn.style.cssText = 'position: fixed; top: 10px; right: 10px; z-index: 1000; padding: 10px; background: red; color: white; border: none; cursor: pointer;';
                testCloseBtn.onclick = () => {
                    console.log('Test close button clicked');
                    document.querySelectorAll('.tf-mini-cart-tool-openable.active').forEach(section => {
                        section.classList.remove('active');
                    });
                };
                document.body.appendChild(testCloseBtn);
            }, 1000);
        });
    </script>
</body>
</html>