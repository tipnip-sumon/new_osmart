<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommissionSetting;

class MatchingCommissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create basic matching commission setting
        CommissionSetting::updateOrCreate(
            ['name' => 'matching_bonus'],
            [
                'display_name' => 'Matching Bonus',
                'description' => 'Binary matching bonus based on leg volumes',
                'type' => 'matching',
                'calculation_type' => 'percentage',
                'value' => 10.00, // 10% matching bonus
                'min_qualification' => 1000.00, // Minimum qualification amount
                'max_payout' => 5000.00, // Maximum daily payout
                'max_levels' => 1,
                'is_active' => true,
                'priority' => 1,
                'conditions' => [
                    'personal_volume_required' => true,
                    'personal_volume_amount' => 500.00,
                    'both_legs_required' => true,
                    'both_legs_minimum' => 250.00,
                    'carry_forward_enabled' => true,
                    'carry_forward_days' => 30,
                    'daily_cap_enabled' => true,
                    'daily_cap_amount' => 1000.00,
                    'weekly_cap_enabled' => false,
                    'weekly_cap_amount' => null,
                ]
            ]
        );

        // Create multi-level matching commission setting
        CommissionSetting::updateOrCreate(
            ['name' => 'multi_level_matching'],
            [
                'display_name' => 'Multi-Level Matching',
                'description' => 'Multi-level binary matching bonus',
                'type' => 'matching',
                'calculation_type' => 'percentage',
                'value' => 8.00, // Base percentage
                'min_qualification' => 2000.00,
                'max_payout' => 10000.00,
                'max_levels' => 4,
                'enable_multi_level' => true,
                'is_active' => false, // Disabled by default
                'priority' => 2,
                'levels' => [
                    [
                        'level' => 1,
                        'value' => 10,
                        'min_qualification' => 2000,
                        'max_payout' => 2500
                    ],
                    [
                        'level' => 2,
                        'value' => 7,
                        'min_qualification' => 3000,
                        'max_payout' => 2000
                    ],
                    [
                        'level' => 3,
                        'value' => 5,
                        'min_qualification' => 4000,
                        'max_payout' => 1500
                    ],
                    [
                        'level' => 4,
                        'value' => 3,
                        'min_qualification' => 5000,
                        'max_payout' => 1000
                    ]
                ],
                'conditions' => [
                    'personal_volume_required' => true,
                    'personal_volume_amount' => 1000.00,
                    'both_legs_required' => true,
                    'both_legs_minimum' => 500.00,
                    'carry_forward_enabled' => true,
                    'carry_forward_days' => 45,
                    'daily_cap_enabled' => true,
                    'daily_cap_amount' => 2000.00,
                    'weekly_cap_enabled' => true,
                    'weekly_cap_amount' => 10000.00,
                ]
            ]
        );
    }
}
