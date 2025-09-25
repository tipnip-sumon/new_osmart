<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Affiliate Tracking Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the affiliate tracking system.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Attribution Window (Days)
    |--------------------------------------------------------------------------
    |
    | Number of days after clicking an affiliate link that the user can make
    | a purchase and still attribute commission to the affiliate.
    | 
    | Default: 30 days (industry standard)
    | Recommended range: 7-90 days
    |
    */
    'attribution_days' => env('AFFILIATE_ATTRIBUTION_DAYS', 30),

    /*
    |--------------------------------------------------------------------------
    | Cookie Name
    |--------------------------------------------------------------------------
    |
    | Name of the cookie used to store affiliate tracking information.
    |
    */
    'cookie_name' => 'affiliate_tracking',

    /*
    |--------------------------------------------------------------------------
    | Default Commission Rate
    |--------------------------------------------------------------------------
    |
    | Default commission percentage for affiliates when no specific rate is set.
    | Value should be decimal (5.0 = 5%)
    |
    */
    'default_commission_rate' => env('AFFILIATE_DEFAULT_RATE', 5.0),

    /*
    |--------------------------------------------------------------------------
    | Click Duplicate Prevention Window (Hours)
    |--------------------------------------------------------------------------
    |
    | Number of hours to prevent duplicate click counting from same IP/browser.
    | This prevents click fraud while allowing legitimate re-visits.
    |
    */
    'duplicate_prevention_hours' => env('AFFILIATE_DUPLICATE_PREVENTION_HOURS', 24),

    /*
    |--------------------------------------------------------------------------
    | Auto Commission Approval
    |--------------------------------------------------------------------------
    |
    | Whether to automatically approve commissions after a certain period.
    | Set to false to require manual approval.
    |
    */
    'auto_approve_enabled' => env('AFFILIATE_AUTO_APPROVE', false),
    'auto_approve_days' => env('AFFILIATE_AUTO_APPROVE_DAYS', 7),

    /*
    |--------------------------------------------------------------------------
    | Commission Statuses
    |--------------------------------------------------------------------------
    |
    | Available commission status options.
    |
    */
    'commission_statuses' => [
        'pending' => 'Pending Review',
        'approved' => 'Approved',
        'paid' => 'Paid',
        'rejected' => 'Rejected',
        'cancelled' => 'Cancelled'
    ],

    /*
    |--------------------------------------------------------------------------
    | UTM Parameters
    |--------------------------------------------------------------------------
    |
    | Default UTM parameters added to affiliate links for tracking.
    |
    */
    'utm_parameters' => [
        'utm_source' => 'affiliate',
        'utm_medium' => 'link',
        'utm_campaign' => 'product_share'
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Sharing Platforms
    |--------------------------------------------------------------------------
    |
    | Supported platforms for social sharing with affiliate links.
    |
    */
    'social_platforms' => [
        'whatsapp' => [
            'name' => 'WhatsApp',
            'url_template' => 'https://wa.me/?text={message}%20{url}',
            'icon' => 'fab fa-whatsapp',
            'color' => '#25D366'
        ],
        'facebook' => [
            'name' => 'Facebook',
            'url_template' => 'https://www.facebook.com/sharer/sharer.php?u={url}',
            'icon' => 'fab fa-facebook',
            'color' => '#1877F2'
        ],
        'twitter' => [
            'name' => 'Twitter',
            'url_template' => 'https://twitter.com/intent/tweet?text={message}&url={url}',
            'icon' => 'fab fa-twitter',
            'color' => '#1DA1F2'
        ],
        'telegram' => [
            'name' => 'Telegram',
            'url_template' => 'https://t.me/share/url?url={url}&text={message}',
            'icon' => 'fab fa-telegram',
            'color' => '#0088CC'
        ],
        'linkedin' => [
            'name' => 'LinkedIn',
            'url_template' => 'https://www.linkedin.com/sharing/share-offsite/?url={url}',
            'icon' => 'fab fa-linkedin',
            'color' => '#0A66C2'
        ]
    ]
];
