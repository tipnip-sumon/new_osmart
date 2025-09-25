<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PointBasedPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter Point Package',
                'minimum' => 1200.00,
                'maximum' => 1200.00,
                'fixed_amount' => 1200.00,
                'interest' => 0,
                'interest_type' => 0,
                'time' => '1',
                'time_name' => 'day',
                'status' => 1,
                'featured' => 0,
                'capital_back' => 0,
                'lifetime' => 0,
                'description' => 'Perfect for beginners! Get 100 points instantly and start earning commission bonuses.',
                'point_based' => 1,
                'points_reward' => 100.00,
                'point_price' => 1200.00,
                'wallet_purchase' => 1,
                'point_purchase' => 0,
                'sponsor_commission' => 1,
                'generation_commission' => 1,
                'binary_matching' => 0,
                'category' => 'starter',
                'features' => json_encode([
                    '50 Instant Points',
                    'Sponsor Commission Bonus',
                    '20-Level Generation Bonus',
                    'Daily Purchase Eligible'
                ]),
                'purchase_type' => 'one_time',
                'point_to_taka_rate' => 6.00,
                'sort_order' => 1,
                'is_popular' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Popular Point Package',
                'minimum' => 100.00,
                'maximum' => 500.00,
                'fixed_amount' => 600.00,
                'interest' => 0,
                'interest_type' => 0,
                'time' => '1',
                'time_name' => 'day',
                'status' => 1,
                'featured' => 1,
                'capital_back' => 0,
                'lifetime' => 0,
                'description' => 'Most popular choice! Get 100 points and maximize your commission earning potential.',
                'point_based' => 1,
                'points_reward' => 100.00,
                'point_price' => 600.00,
                'wallet_purchase' => 1,
                'point_purchase' => 0,
                'sponsor_commission' => 1,
                'generation_commission' => 1,
                'binary_matching' => 1,
                'category' => 'popular',
                'features' => json_encode([
                    '100 Instant Points',
                    'Featured Package Benefits',
                    'Binary Matching Eligible',
                    'Higher Commission Rates',
                    'Priority Support'
                ]),
                'purchase_type' => 'one_time',
                'point_to_taka_rate' => 6.00,
                'sort_order' => 2,
                'is_popular' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium Point Package',
                'minimum' => 200.00,
                'maximum' => 1000.00,
                'fixed_amount' => 1200.00,
                'interest' => 0,
                'interest_type' => 0,
                'time' => '1',
                'time_name' => 'day',
                'status' => 1,
                'featured' => 1,
                'capital_back' => 0,
                'lifetime' => 0,
                'description' => 'Premium package for serious network builders! Get 200 points and unlock maximum earning potential.',
                'point_based' => 1,
                'points_reward' => 200.00,
                'point_price' => 1200.00,
                'wallet_purchase' => 1,
                'point_purchase' => 0,
                'sponsor_commission' => 1,
                'generation_commission' => 1,
                'binary_matching' => 1,
                'category' => 'premium',
                'features' => json_encode([
                    '200 Instant Points',
                    'Premium Package Benefits',
                    'Maximum Commission Rates',
                    'Binary Matching Eligible',
                    'Exclusive Training Access',
                    'Priority Customer Support',
                    'Monthly Bonus Eligible'
                ]),
                'purchase_type' => 'one_time',
                'point_to_taka_rate' => 6.00,
                'sort_order' => 3,
                'is_popular' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'VIP Point Package',
                'minimum' => 500.00,
                'maximum' => 2500.00,
                'fixed_amount' => 3000.00,
                'interest' => 0,
                'interest_type' => 0,
                'time' => '1',
                'time_name' => 'day',
                'status' => 1,
                'featured' => 1,
                'capital_back' => 0,
                'lifetime' => 0,
                'description' => 'VIP package for top performers! Get 500 points and exclusive VIP benefits.',
                'point_based' => 1,
                'points_reward' => 500.00,
                'point_price' => 3000.00,
                'wallet_purchase' => 1,
                'point_purchase' => 0,
                'sponsor_commission' => 1,
                'generation_commission' => 1,
                'binary_matching' => 1,
                'category' => 'vip',
                'features' => json_encode([
                    '500 Instant Points',
                    'VIP Status Benefits',
                    'Maximum Commission Rates',
                    'Binary Matching Eligible',
                    'VIP Training Programs',
                    'Dedicated Account Manager',
                    'Quarterly VIP Events',
                    'Special Recognition'
                ]),
                'purchase_type' => 'one_time',
                'point_to_taka_rate' => 6.00,
                'sort_order' => 4,
                'is_popular' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($plans as $plan) {
            DB::table('plans')->insert($plan);
        }
    }
}
