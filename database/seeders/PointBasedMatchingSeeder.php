<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommissionSetting;

class PointBasedMatchingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create point-based matching commission setting
        CommissionSetting::updateOrCreate(
            ['name' => 'point_based_matching'],
            [
                'display_name' => 'Point-Based Matching',
                'description' => 'Binary matching bonus based on active points (100 points minimum per leg)',
                'type' => 'matching',
                'calculation_type' => 'percentage',
                'value' => 10.00, // 10% matching bonus
                'min_qualification' => 100.00, // 100 active points minimum
                'max_payout' => 5000.00, // Maximum daily payout
                'max_levels' => 1,
                'is_active' => true,
                'priority' => 1,
                'qualification_basis' => 'points', // Point-based qualification
                'conditions' => [
                    'both_legs_required' => true,
                    'min_leg_points' => 100,
                    'point_value' => 6, // 1 point = 6 Tk
                    'matching_percentage' => 10,
                    'carry_forward_enabled' => true,
                    'carry_forward_days' => 30,
                    'daily_cap_enabled' => false,
                    'weekly_cap_enabled' => false,
                ]
            ]
        );

        // Disable volume-based matching settings
        CommissionSetting::where('name', 'matching_bonus')
            ->update(['is_active' => false]);
        
        CommissionSetting::where('name', 'multi_level_matching')
            ->update(['is_active' => false]);
    }
}
