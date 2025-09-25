<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Wallet & Transfer Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for wallet management and transfer systems
    |
    */

    'vendor_wallet' => [
        'enabled' => true,
        'default_balance' => 0.00,
        'minimum_balance' => 0.00,
        'maximum_daily_transfer' => 50000.00,
        'maximum_single_transfer' => 10000.00,
        'auto_approval_limit' => 1000.00, // Transfers below this amount are auto-approved
        'require_admin_approval' => true,
        'commission_rate' => 0.10, // 10% commission rate for vendors
    ],

    'transfer_limits' => [
        'vendor_to_member' => [
            'enabled' => true,
            'min_amount' => 10.00,
            'max_amount' => 10000.00,
            'daily_limit' => 50000.00,
            'monthly_limit' => 200000.00,
            'auto_approval_limit' => 1000.00,
        ],
        'admin_to_vendor' => [
            'enabled' => true,
            'min_amount' => 50.00,
            'max_amount' => 100000.00,
            'daily_limit' => 500000.00,
            'require_dual_approval' => false,
        ],
        'fund_requests' => [
            'enabled' => true,
            'min_amount' => 100.00,
            'max_amount' => 25000.00,
            'auto_expire_days' => 7,
            'require_vendor_approval' => true,
            'max_pending_per_member' => 3,
        ]
    ],

    'transfer_fees' => [
        'vendor_to_member' => [
            'type' => 'percentage', // 'percentage' or 'fixed'
            'rate' => 0.01, // 1%
            'min_fee' => 5.00,
            'max_fee' => 100.00,
            'free_threshold' => 0.00, // Amount above which transfer is free
        ],
        'fund_request' => [
            'processing_fee' => 0.005, // 0.5%
            'min_fee' => 2.00,
            'max_fee' => 50.00,
        ]
    ],

    'approval_workflows' => [
        'vendor_transfers' => [
            'require_admin_approval' => [
                'amount_threshold' => 5000.00,
                'high_risk_recipients' => true,
                'new_vendors' => true, // Vendors created within last 30 days
            ],
            'auto_approve' => [
                'trusted_vendors' => true,
                'amount_below' => 1000.00,
                'verified_recipients' => true,
            ]
        ],
        'fund_requests' => [
            'vendor_can_approve' => [
                'amount_below' => 5000.00,
                'trusted_members' => true,
            ],
            'admin_approval_required' => [
                'amount_above' => 5000.00,
                'emergency_requests' => true,
                'loan_requests' => true,
            ]
        ]
    ],

    'notifications' => [
        'email_notifications' => true,
        'sms_notifications' => false,
        'dashboard_alerts' => true,
        'notify_on' => [
            'fund_request_created' => ['vendor', 'admin'],
            'transfer_completed' => ['sender', 'recipient'],
            'approval_required' => ['admin'],
            'large_transfer' => ['admin'], // Above certain threshold
        ]
    ],

    'security' => [
        'two_factor_required' => [
            'admin_transfers_above' => 10000.00,
            'vendor_transfers_above' => 5000.00,
        ],
        'password_confirmation_required' => [
            'all_transfers' => true,
            'fund_requests_above' => 1000.00,
        ],
        'ip_whitelist' => [
            'enabled' => false,
            'admin_transfers' => [],
            'vendor_transfers' => [],
        ],
        'rate_limiting' => [
            'max_transfers_per_hour' => 10,
            'max_fund_requests_per_day' => 5,
            'cooldown_period' => 300, // 5 minutes
        ]
    ],

    'wallet_types' => [
        'vendor' => [
            'main_balance' => [
                'label' => 'Main Balance',
                'field' => 'balance',
                'transferable' => true,
            ],
            'deposit_wallet' => [
                'label' => 'Deposit Wallet',
                'field' => 'deposit_wallet',
                'transferable' => true,
            ],
            'commission_wallet' => [
                'label' => 'Commission Wallet',
                'field' => 'commission_wallet',
                'transferable' => true,
            ]
        ],
        'member' => [
            'deposit_wallet' => [
                'label' => 'Deposit Wallet',
                'field' => 'deposit_wallet',
                'can_receive' => true,
            ],
            'interest_wallet' => [
                'label' => 'Interest Wallet',
                'field' => 'interest_wallet',
                'can_receive' => true,
            ],
            'main_balance' => [
                'label' => 'Main Balance',
                'field' => 'balance',
                'can_receive' => true,
            ]
        ]
    ],

    'fund_request_types' => [
        'loan' => [
            'label' => 'Loan Request',
            'description' => 'Request for loan with repayment terms',
            'max_amount' => 25000.00,
            'requires_approval' => true,
            'auto_expire_days' => 7,
        ],
        'advance' => [
            'label' => 'Advance Payment',
            'description' => 'Request for advance against future earnings',
            'max_amount' => 15000.00,
            'requires_approval' => true,
            'auto_expire_days' => 5,
        ],
        'discount' => [
            'label' => 'Discount Request',
            'description' => 'Request for purchase discount',
            'max_amount' => 5000.00,
            'requires_approval' => false,
            'auto_expire_days' => 3,
        ],
        'bonus' => [
            'label' => 'Bonus Request',
            'description' => 'Request for performance bonus',
            'max_amount' => 10000.00,
            'requires_approval' => true,
            'auto_expire_days' => 7,
        ],
        'commission_advance' => [
            'label' => 'Commission Advance',
            'description' => 'Request for advance commission payment',
            'max_amount' => 20000.00,
            'requires_approval' => true,
            'auto_expire_days' => 5,
        ],
        'emergency' => [
            'label' => 'Emergency Fund',
            'description' => 'Emergency financial assistance',
            'max_amount' => 30000.00,
            'requires_approval' => true,
            'auto_expire_days' => 2,
            'priority' => 'urgent',
        ]
    ],

    'vendor_management' => [
        'balance_adjustments' => [
            'allowed_types' => [
                'bonus' => 'Bonus Credit',
                'discount' => 'Discount Credit',
                'commission' => 'Commission Payment',
                'refund' => 'Refund Credit',
                'penalty' => 'Penalty Deduction',
                'correction' => 'Balance Correction',
            ],
            'require_admin_approval' => true,
            'min_amount' => 1.00,
            'max_amount' => 100000.00,
        ],
        'auto_commission' => [
            'enabled' => true,
            'calculation_method' => 'percentage', // 'percentage' or 'fixed'
            'rate' => 0.10, // 10%
            'minimum_payout' => 100.00,
            'payout_frequency' => 'weekly', // 'daily', 'weekly', 'monthly'
        ]
    ],

    'reporting' => [
        'enabled' => true,
        'generate_monthly_reports' => true,
        'email_reports_to' => [
            'admin@osmartbd.com',
        ],
        'include_in_reports' => [
            'transfer_volumes' => true,
            'fund_request_analytics' => true,
            'vendor_performance' => true,
            'fee_collection' => true,
        ]
    ],

    'audit' => [
        'log_all_transactions' => true,
        'log_admin_actions' => true,
        'retain_logs_days' => 365,
        'export_logs' => true,
    ]
];
