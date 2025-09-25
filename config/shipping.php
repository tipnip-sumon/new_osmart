<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shipping Configuration
    |--------------------------------------------------------------------------
    |
    | Configure shipping options and rates for the application
    |
    */

    // Default shipping options with rates
    'options' => [
        'standard' => [
            'label' => 'Standard Shipping',
            'rate' => 100.00,
            'delivery_time' => '5-7 business days',
            'description' => 'Standard delivery service'
        ],
        'express' => [
            'label' => 'Express Shipping',
            'rate' => 200.00,
            'delivery_time' => '2-3 business days',
            'description' => 'Fast express delivery'
        ],
        'overnight' => [
            'label' => 'Overnight Shipping',
            'rate' => 500.00,
            'delivery_time' => '1 business day',
            'description' => 'Next day delivery'
        ],
        'free' => [
            'label' => 'Free Shipping',
            'rate' => 0.00,
            'delivery_time' => '7-10 business days',
            'description' => 'Free shipping on eligible orders'
        ],
        // Bangladesh specific options (can be used alternatively)
        'inside_dhaka' => [
            'label' => 'Inside Dhaka/City',
            'rate' => 60.00,  // BDT 60
            'delivery_time' => '1-2 days',
            'description' => 'Fast delivery within Dhaka city'
        ],
        'outside_dhaka' => [
            'label' => 'Outside Dhaka/City',
            'rate' => 120.00, // BDT 120
            'delivery_time' => '3-5 days',
            'description' => 'Delivery outside Dhaka city'
        ],
        'across_country' => [
            'label' => 'Across the Country',
            'rate' => 150.00, // BDT 150
            'delivery_time' => '5-7 days',
            'description' => 'Nationwide delivery service'
        ]
    ],

    // Free shipping conditions
    'free_shipping' => [
        'enabled' => true,
        
        // Location-based free shipping
        'by_location' => [
            'standard' => [
                'enabled' => true,
                'minimum_order' => 1000.00, // Free shipping over BDT 1000 for standard
                'maximum_order' => null,
            ],
            'express' => [
                'enabled' => true,
                'minimum_order' => 2000.00, // Higher threshold for express
                'maximum_order' => null,
            ],
            'overnight' => [
                'enabled' => false, // No free overnight shipping
                'minimum_order' => null,
                'maximum_order' => null,
            ],
            'inside_dhaka' => [
                'enabled' => true,
                'minimum_order' => 500.00, // Free shipping over BDT 500 inside Dhaka
                'maximum_order' => 2000.00, // Free shipping only up to BDT 2000 (premium orders still pay)
            ],
            'outside_dhaka' => [
                'enabled' => true,
                'minimum_order' => 1500.00, // Higher threshold for outside Dhaka
                'maximum_order' => null, // No upper limit
            ],
            'across_country' => [
                'enabled' => false, // No free shipping for nationwide delivery
                'minimum_order' => null,
                'maximum_order' => null,
            ]
        ],
        
        // Product-based free shipping
        'by_product' => [
            'enabled' => true,
            'product_ids' => [], // Specific product IDs that get free shipping
            'categories' => [], // Category IDs that get free shipping
            'tags' => ['free-shipping'], // Product tags that qualify for free shipping
        ],
        
        // User-based free shipping
        'by_user' => [
            'enabled' => true,
            'premium_users' => true, // Premium/VIP users get free shipping
            'first_order' => false, // First-time customers get free shipping
        ],
        
        // Coupon-based free shipping (handled separately in coupon system)
        'by_coupon' => [
            'enabled' => true,
        ],
        
        // Global settings (legacy - kept for backward compatibility)
        'minimum_order' => 1000.00,
        'applies_to' => ['inside_dhaka', 'outside_dhaka'],
    ],

    // Default shipping method
    'default_method' => 'standard',

    // Shipping zones (for future expansion)
    'zones' => [
        'dhaka' => [
            'name' => 'Dhaka Division',
            'areas' => ['Dhaka', 'Gazipur', 'Narayanganj', 'Manikganj', 'Munshiganj'],
            'shipping_method' => 'inside_dhaka'
        ],
        'chittagong' => [
            'name' => 'Chittagong Division',
            'areas' => ['Chittagong', 'Cox\'s Bazar', 'Comilla'],
            'shipping_method' => 'outside_dhaka'
        ],
        'rajshahi' => [
            'name' => 'Rajshahi Division',
            'areas' => ['Rajshahi', 'Bogra', 'Pabna'],
            'shipping_method' => 'across_country'
        ],
        // Add more zones as needed
    ],

    // Currency symbol
    'currency' => '৳', // Bangladeshi Taka
];
