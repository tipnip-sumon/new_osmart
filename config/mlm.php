<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MLM System Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the MLM (Multi-Level Marketing)
    | binary tree system including placement rules, depth limits, and validation settings.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Hierarchy Limits
    |--------------------------------------------------------------------------
    */
    'max_hierarchy_depth' => env('MLM_MAX_HIERARCHY_DEPTH', 20),
    'max_direct_downline' => env('MLM_MAX_DIRECT_DOWNLINE', 2), // Binary tree limit

    /*
    |--------------------------------------------------------------------------
    | Placement Settings
    |--------------------------------------------------------------------------
    */
    'auto_placement_enabled' => env('MLM_AUTO_PLACEMENT_ENABLED', true),
    'cross_link_validation' => env('MLM_CROSS_LINK_VALIDATION', true),
    'strict_hierarchy_validation' => env('MLM_STRICT_HIERARCHY_VALIDATION', true),

    /*
    |--------------------------------------------------------------------------
    | Commission Settings
    |--------------------------------------------------------------------------
    */
    'default_commission_rate' => env('MLM_DEFAULT_COMMISSION_RATE', 0.05), // 5%
    'binary_bonus_percentage' => env('MLM_BINARY_BONUS_PERCENTAGE', 0.10), // 10%
    'max_binary_bonus_per_day' => env('MLM_MAX_BINARY_BONUS_PER_DAY', 500),

    /*
    |--------------------------------------------------------------------------
    | Volume Tracking
    |--------------------------------------------------------------------------
    */
    'pv_carry_forward' => env('MLM_PV_CARRY_FORWARD', true),
    'max_pv_carry_forward_percentage' => env('MLM_MAX_PV_CARRY_FORWARD_PERCENTAGE', 0.30), // 30%

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */
    'validate_circular_references' => true,
    'validate_cross_links' => true,
    'validate_hierarchy_depth' => true,
    'validate_upline_capacity' => true,

    /*
    |--------------------------------------------------------------------------
    | Performance Settings
    |--------------------------------------------------------------------------
    */
    'cache_hierarchy_calculations' => env('MLM_CACHE_HIERARCHY', true),
    'cache_duration_minutes' => env('MLM_CACHE_DURATION', 60),

    /*
    |--------------------------------------------------------------------------
    | Rank System
    |--------------------------------------------------------------------------
    */
    'ranks' => [
        'starter' => ['min_pv' => 0, 'color' => '#6c757d'],
        'bronze' => ['min_pv' => 500, 'color' => '#cd7f32'],
        'silver' => ['min_pv' => 1000, 'color' => '#c0c0c0'],
        'gold' => ['min_pv' => 2000, 'color' => '#ffd700'],
        'platinum' => ['min_pv' => 3000, 'color' => '#e5e4e2'],
        'diamond' => ['min_pv' => 5000, 'color' => '#b9f2ff'],
    ],
];
