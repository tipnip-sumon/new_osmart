<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tax Configuration
    |--------------------------------------------------------------------------
    |
    | Configure tax rates and settings for the application
    |
    */

    // Default tax rate (as percentage, e.g., 15 for 15%)
    'default_rate' => 0, // Bangladesh - Tax-free by default

    // Tax calculation method
    'calculation_method' => 'percentage', // 'percentage' or 'fixed'

    // Whether tax is included in product prices
    'tax_inclusive' => false,

    // Tax label to display
    'label' => 'Tax',

    // Dynamic tax configuration
    'dynamic' => [
        'enabled' => false, // Disabled for tax-free Bangladesh system
        'by_amount' => [
            // Tax-free threshold for all orders in BD
            'threshold' => 0, // All orders are tax-free
            'rates' => [
                ['min' => 0, 'max' => null, 'rate' => 0], // All orders tax-free
            ]
        ],
        'by_location' => [
            'enabled' => false, // Disabled for BD tax-free system
            'dhaka' => 0, // Tax-free
            'chittagong' => 0, // Tax-free
            'other' => 0, // Tax-free for all other areas
        ],
        'by_product_type' => [
            'enabled' => false, // Disabled for BD tax-free system
            'categories' => [
                'electronics' => 0, // Tax-free
                'clothing' => 0, // Tax-free
                'books' => 0, // Tax-free
                'food' => 0, // Tax-free
                'medicine' => 0, // Tax-free
                'other' => 0, // Tax-free
            ]
        ],
        'special_rules' => [
            'bulk_discount' => [
                'enabled' => false, // Not needed in tax-free system
                'min_items' => 10,
                'discount_rate' => 0,
            ],
            'first_time_customer' => [
                'enabled' => false, // Not needed in tax-free system
                'discount_rate' => 0,
            ],
            'premium_customer' => [
                'enabled' => false, // Not needed in tax-free system
                'discount_rate' => 0,
            ]
        ]
    ],

    // Tax rates by location (for location-based tax)
    'rates_by_location' => [
        'BD' => [
            'default' => 0,
            'dhaka' => 0,  // Dhaka
            'chittagong' => 0,  // Chittagong
            'rajshahi' => 0,  // Rajshahi
            'khulna' => 0,  // Khulna
            'barisal' => 0,  // Barisal
            'sylhet' => 0,  // Sylhet
            'rangpur' => 0,  // Rangpur
            'mymensingh' => 0,  // Mymensingh
        ],
        'US' => [
            'default' => 8,
            'CA' => 7.25,  // California
            'NY' => 8.25,  // New York
            'TX' => 6.25,  // Texas
            'FL' => 6,     // Florida
        ],
        'CA' => 13,  // Canada GST+PST average
        'UK' => 20,  // VAT
        'EU' => 21,  // Average EU VAT
    ],

    // Minimum order amount for tax calculation (0 means always apply)
    'minimum_order' => 0,

    // Tax exemption rules for Bangladesh tax-free system
    'exemptions' => [
        'shipping' => true,  // Don't tax shipping
        'discounts' => false, // Apply tax before discounts
        'categories' => ['all'], // All categories are tax-exempt in BD
        'products' => [], // All products are tax-exempt by default
        'countries' => ['BD'], // Bangladesh is completely tax-free
    ],

    // Display settings
    'display' => [
        'show_tax_breakdown' => false, // Hide tax breakdown for tax-free system
        'show_inclusive_message' => false, // Hide tax-inclusive message
        'currency_symbol' => '৳',
        'decimal_places' => 2,
        'tax_free_message' => 'Tax-free shopping in Bangladesh!',
    ],
];
