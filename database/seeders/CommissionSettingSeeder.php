<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommissionSetting;

class CommissionSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = [
            [
                'name' => 'Direct Sponsor Commission',
                'type' => 'sponsor',
                'calculation_type' => 'percentage',
                'value' => 10.00,
                'description' => 'Commission paid to direct sponsor on new member registration',
                'conditions' => [
                    'min_sales_volume' => 1000,
                    'qualification_period' => 30
                ],
                'priority' => 1,
                'status' => 'active'
            ],
            [
                'name' => 'Binary Matching Bonus',
                'type' => 'binary',
                'calculation_type' => 'percentage',
                'value' => 15.00,
                'description' => 'Binary bonus based on left and right leg volume matching',
                'conditions' => [
                    'min_left_volume' => 5000,
                    'min_right_volume' => 5000,
                    'flush_percentage' => 80,
                    'cap_amount' => 50000
                ],
                'priority' => 2,
                'status' => 'active'
            ],
            [
                'name' => 'Generation Bonus - Level 1-5',
                'type' => 'generation',
                'calculation_type' => 'percentage',
                'value' => 5.00,
                'description' => 'Multi-level generation bonus with decreasing percentages',
                'conditions' => [
                    'generation_depth' => 5,
                    'min_personal_sales' => 2000
                ],
                'levels' => [
                    ['level' => 1, 'value' => 5.00, 'condition' => 'min_volume:1000'],
                    ['level' => 2, 'value' => 4.00, 'condition' => 'min_volume:2000'],
                    ['level' => 3, 'value' => 3.00, 'condition' => 'min_volume:3000'],
                    ['level' => 4, 'value' => 2.00, 'condition' => 'min_volume:4000'],
                    ['level' => 5, 'value' => 1.00, 'condition' => 'min_volume:5000']
                ],
                'priority' => 3,
                'status' => 'active'
            ],
            [
                'name' => 'Matching Bonus - 10 Levels',
                'type' => 'matching',
                'calculation_type' => 'percentage',
                'value' => 20.00,
                'description' => 'Matching bonus on downline commissions up to 10 levels',
                'conditions' => [
                    'max_levels' => 10,
                    'min_volume' => 10000
                ],
                'levels' => [
                    ['level' => 1, 'value' => 20.00, 'condition' => 'rank:bronze'],
                    ['level' => 2, 'value' => 15.00, 'condition' => 'rank:silver'],
                    ['level' => 3, 'value' => 10.00, 'condition' => 'rank:gold'],
                    ['level' => 4, 'value' => 8.00, 'condition' => 'rank:platinum'],
                    ['level' => 5, 'value' => 6.00, 'condition' => 'rank:diamond']
                ],
                'priority' => 4,
                'status' => 'active'
            ],
            [
                'name' => 'Bronze Rank Achievement Bonus',
                'type' => 'rank',
                'calculation_type' => 'fixed',
                'value' => 5000.00,
                'description' => 'One-time bonus for achieving Bronze rank',
                'conditions' => [
                    'required_rank' => 'bronze',
                    'target_percentage' => 100
                ],
                'priority' => 5,
                'status' => 'active'
            ],
            [
                'name' => 'Silver Rank Achievement Bonus',
                'type' => 'rank',
                'calculation_type' => 'fixed',
                'value' => 15000.00,
                'description' => 'One-time bonus for achieving Silver rank',
                'conditions' => [
                    'required_rank' => 'silver',
                    'target_percentage' => 100
                ],
                'priority' => 6,
                'status' => 'active'
            ],
            [
                'name' => 'Gold Rank Achievement Bonus',
                'type' => 'rank',
                'calculation_type' => 'fixed',
                'value' => 35000.00,
                'description' => 'One-time bonus for achieving Gold rank',
                'conditions' => [
                    'required_rank' => 'gold',
                    'target_percentage' => 100
                ],
                'priority' => 7,
                'status' => 'active'
            ],
            [
                'name' => 'Premium Club Bonus',
                'type' => 'club',
                'calculation_type' => 'percentage',
                'value' => 5.00,
                'description' => 'Monthly bonus for Premium Club members',
                'conditions' => [
                    'club_level' => 'premium',
                    'required_members' => 25
                ],
                'priority' => 8,
                'status' => 'active'
            ],
            [
                'name' => 'Elite Club Bonus',
                'type' => 'club',
                'calculation_type' => 'percentage',
                'value' => 8.00,
                'description' => 'Monthly bonus for Elite Club members',
                'conditions' => [
                    'club_level' => 'elite',
                    'required_members' => 50
                ],
                'priority' => 9,
                'status' => 'active'
            ],
            [
                'name' => 'Leadership Development Bonus',
                'type' => 'leadership',
                'calculation_type' => 'percentage',
                'value' => 3.00,
                'description' => 'Bonus for developing strong leadership teams',
                'conditions' => [
                    'min_team_size' => 100,
                    'min_team_volume' => 100000
                ],
                'priority' => 10,
                'status' => 'active'
            ]
        ];

        foreach ($defaultSettings as $setting) {
            CommissionSetting::create($setting);
        }
    }
}
