<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;

class PointBasedPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing plans if needed
        // Plan::truncate();

        $pointBasedPlans = [
            [
                'name' => 'Starter Package',
                'minimum' => 0,
                'maximum' => 0,
                'fixed_amount' => 0,
                'minimum_points' => 100,
                'points_reward' => 100,
                'point_based' => true,
                'interest' => 5.00,
                'interest_type' => 1, // percentage
                'time' => '30',
                'time_name' => 'days',
                'status' => true,
                'featured' => false,
                'capital_back' => false,
                'lifetime' => false,
                'repeat_time' => '0',
                'description' => 'Entry level package for new members. Activate with 100 points earned from product purchases.',
                'binary_left' => 10.00,
                'binary_right' => 10.00,
                'direct_commission' => 5.00,
                'level_commission' => 3.00,
                'is_active' => true,
            ],
            [
                'name' => 'Growth Package',
                'minimum' => 0,
                'maximum' => 0,
                'fixed_amount' => 0,
                'minimum_points' => 200,
                'points_reward' => 200,
                'point_based' => true,
                'interest' => 7.50,
                'interest_type' => 1, // percentage
                'time' => '30',
                'time_name' => 'days',
                'status' => true,
                'featured' => true,
                'capital_back' => false,
                'lifetime' => false,
                'repeat_time' => '0',
                'description' => 'Intermediate package with better commission rates. Requires 200 points from product purchases.',
                'binary_left' => 15.00,
                'binary_right' => 15.00,
                'direct_commission' => 7.50,
                'level_commission' => 5.00,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Package',
                'minimum' => 0,
                'maximum' => 0,
                'fixed_amount' => 0,
                'minimum_points' => 500,
                'points_reward' => 500,
                'point_based' => true,
                'interest' => 10.00,
                'interest_type' => 1, // percentage
                'time' => '30',
                'time_name' => 'days',
                'status' => true,
                'featured' => true,
                'capital_back' => false,
                'lifetime' => false,
                'repeat_time' => '0',
                'description' => 'High-tier package with maximum earning potential. Activate with 500 points from product purchases.',
                'binary_left' => 20.00,
                'binary_right' => 20.00,
                'direct_commission' => 10.00,
                'level_commission' => 7.50,
                'is_active' => true,
            ],
            [
                'name' => 'Elite Package',
                'minimum' => 0,
                'maximum' => 0,
                'fixed_amount' => 0,
                'minimum_points' => 1000,
                'points_reward' => 1000,
                'point_based' => true,
                'interest' => 12.50,
                'interest_type' => 1, // percentage
                'time' => '30',
                'time_name' => 'days',
                'status' => true,
                'featured' => true,
                'capital_back' => false,
                'lifetime' => false,
                'repeat_time' => '0',
                'description' => 'Ultimate package for serious networkers. Requires 1000 points from product purchases for maximum rewards.',
                'binary_left' => 25.00,
                'binary_right' => 25.00,
                'direct_commission' => 12.50,
                'level_commission' => 10.00,
                'is_active' => true,
            ],
            [
                'name' => 'Diamond Package',
                'minimum' => 0,
                'maximum' => 0,
                'fixed_amount' => 0,
                'minimum_points' => 2000,
                'points_reward' => 2000,
                'point_based' => true,
                'interest' => 15.00,
                'interest_type' => 1, // percentage
                'time' => '30',
                'time_name' => 'days',
                'status' => true,
                'featured' => true,
                'capital_back' => false,
                'lifetime' => false,
                'repeat_time' => '0',
                'description' => 'Exclusive VIP package with premium benefits. Activate with 2000 points for elite-level commissions.',
                'binary_left' => 30.00,
                'binary_right' => 30.00,
                'direct_commission' => 15.00,
                'level_commission' => 12.50,
                'is_active' => true,
            ],
        ];

        foreach ($pointBasedPlans as $plan) {
            Plan::create($plan);
        }

        $this->command->info('Point-based plans seeded successfully!');
    }
}
