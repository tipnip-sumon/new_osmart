<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Currency Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration sets the default currency for the application.
    | All monetary values will be displayed using these settings.
    |
    */

    // Primary currency settings
    'default_currency' => 'BDT',
    'currency_symbol' => '৳',
    'currency_name' => 'Bangladeshi Taka',
    'currency_code' => 'BDT',
    
    // Display formatting
    'symbol_position' => 'before', // 'before' or 'after'
    'decimal_places' => 2,
    'decimal_separator' => '.',
    'thousands_separator' => ',',
    
    // Currency symbol variations
    'symbols' => [
        'BDT' => '৳',
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'INR' => '₹',
        'JPY' => '¥',
    ],
    
    // Currency names
    'names' => [
        'BDT' => 'Bangladeshi Taka',
        'USD' => 'US Dollar',
        'EUR' => 'Euro',
        'GBP' => 'British Pound',
        'INR' => 'Indian Rupee',
        'JPY' => 'Japanese Yen',
    ],
    
    // Exchange rates (base: BDT)
    'exchange_rates' => [
        'BDT' => 1.00,
        'USD' => 0.0091, // 1 BDT = 0.0091 USD (approximate)
        'EUR' => 0.0083, // 1 BDT = 0.0083 EUR (approximate)
        'GBP' => 0.0072, // 1 BDT = 0.0072 GBP (approximate)
        'INR' => 0.76,   // 1 BDT = 0.76 INR (approximate)
        'JPY' => 1.34,   // 1 BDT = 1.34 JPY (approximate)
    ],
    
    // Number formatting options
    'formatting' => [
        'large_numbers' => [
            'use_short_format' => true, // Convert 100000 to 1L, 10000000 to 1Cr
            'crore_threshold' => 10000000, // 1 Crore = 1,00,00,000
            'lakh_threshold' => 100000,   // 1 Lakh = 1,00,000
            'thousand_threshold' => 1000, // 1K = 1,000
        ],
        'show_zero_decimals' => false, // Hide .00 for whole numbers
        'min_fraction_digits' => 0,
        'max_fraction_digits' => 2,
    ],
    
    // Regional settings for Bangladesh
    'locale' => [
        'country_code' => 'BD',
        'language_code' => 'bn',
        'region' => 'Bangladesh',
        'timezone' => 'Asia/Dhaka',
    ],
    
    // Banking and payment settings
    'banking' => [
        'central_bank' => 'Bangladesh Bank',
        'common_banks' => [
            'Dutch Bangla Bank',
            'Brac Bank',
            'Eastern Bank',
            'Mutual Trust Bank',
            'Prime Bank',
            'Standard Chartered',
            'HSBC',
            'City Bank',
        ],
        'mobile_banking' => [
            'bKash',
            'Nagad',
            'Rocket',
            'SureCash',
            'MyBL',
        ],
    ],
];
