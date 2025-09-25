<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Vendor Commission Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the commission rates and settings for vendors
    | in the multi-vendor marketplace system.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Vendor Commission Rate
    |--------------------------------------------------------------------------
    |
    | The percentage of commission charged to vendors on each sale.
    | This is expressed as a decimal (e.g., 0.10 for 10%).
    |
    */

    'vendor_rate' => env('VENDOR_COMMISSION_RATE', 0.10), // 10% default

    /*
    |--------------------------------------------------------------------------
    | Commission Calculation Method
    |--------------------------------------------------------------------------
    |
    | How the commission is calculated:
    | 'gross' - Commission calculated on gross sale amount
    | 'net' - Commission calculated after taxes and shipping
    |
    */

    'calculation_method' => env('COMMISSION_CALCULATION_METHOD', 'gross'),

    /*
    |--------------------------------------------------------------------------
    | Minimum Commission Amount
    |--------------------------------------------------------------------------
    |
    | The minimum commission amount in the base currency.
    | Set to null to disable minimum commission.
    |
    */

    'minimum_amount' => env('MINIMUM_COMMISSION_AMOUNT', 1.00),

    /*
    |--------------------------------------------------------------------------
    | Commission Payment Schedule
    |--------------------------------------------------------------------------
    |
    | How often commissions are paid out to vendors:
    | 'daily', 'weekly', 'biweekly', 'monthly'
    |
    */

    'payment_schedule' => env('COMMISSION_PAYMENT_SCHEDULE', 'monthly'),

    /*
    |--------------------------------------------------------------------------
    | Commission Hold Period
    |--------------------------------------------------------------------------
    |
    | Number of days to hold commission before it becomes available
    | for payout (to handle refunds and disputes).
    |
    */

    'hold_period_days' => env('COMMISSION_HOLD_PERIOD', 7),

    /*
    |--------------------------------------------------------------------------
    | Commission Categories
    |--------------------------------------------------------------------------
    |
    | Different commission rates for different product categories.
    | If not specified, the default vendor_rate is used.
    |
    */

    'category_rates' => [
        // 'electronics' => 0.08,  // 8% for electronics
        // 'clothing' => 0.12,     // 12% for clothing
        // 'books' => 0.15,        // 15% for books
    ],

    /*
    |--------------------------------------------------------------------------
    | Vendor Tier Commission Rates
    |--------------------------------------------------------------------------
    |
    | Different commission rates based on vendor performance tiers.
    | Higher performing vendors get lower commission rates.
    |
    */

    'tier_rates' => [
        'bronze' => 0.12,   // 12% for new/bronze vendors
        'silver' => 0.10,   // 10% for silver vendors
        'gold' => 0.08,     // 8% for gold vendors
        'platinum' => 0.06, // 6% for platinum vendors
    ],

];
