<!DOCTYPE html>
<html>
<head>
    <title>Debug Order Submission</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Debug Order Submission</h1>
    
    <div id="result"></div>
    
    <button onclick="testOrderSubmission()">Test Order Submission</button>
    
    <script>
    function testOrderSubmission() {
        const testOrderData = {
            customer_name: 'John Doe',
            customer_email: 'john@example.com',
            customer_phone: '01711111111',
            shipping_address: {
                address: '123 Test Street',
                city: 'Dhaka',
                state: 'Dhaka',
                postal_code: '1000',
                country: 'Bangladesh'
            },
            payment_method: 'cash_on_delivery',
            shipping_method: 'inside_dhaka',
            cart_items: [
                {
                    product_id: 3,
                    quantity: 2,
                    price: 500
                }
            ],
            subtotal: 1000,
            shipping_cost: 60,
            tax_amount: 0,
            discount_amount: 0,
            total_amount: 1060
        };
        
        fetch('/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(testOrderData)
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch(e) {
                    return { error: 'Invalid JSON response', response: text };
                }
            });
        })
        .then(data => {
            console.log('Response data:', data);
            document.getElementById('result').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
        })
        .catch(error => {
            console.error('Fetch error:', error);
            document.getElementById('result').innerHTML = '<pre>Error: ' + error.message + '</pre>';
        });
    }
    </script>
</body>
</html>
