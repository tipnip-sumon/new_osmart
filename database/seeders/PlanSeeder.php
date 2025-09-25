<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter Plan',
                'minimum' => 100.00000000,
                'maximum' => 500.00000000,
                'fixed_amount' => 0.00000000,
                'interest' => 5.00000000,
                'interest_type' => 1, // Percentage
                'time' => '30',
                'time_name' => 'days',
                'status' => 1,
                'featured' => 0,
                'capital_back' => 1,
                'lifetime' => 0,
                'repeat_time' => 'daily',
                'description' => 'Perfect for beginners looking to start their investment journey. Low risk with steady returns.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Basic Plan',
                'minimum' => 500.00000000,
                'maximum' => 1000.00000000,
                'fixed_amount' => 0.00000000,
                'interest' => 7.50000000,
                'interest_type' => 1, // Percentage
                'time' => '45',
                'time_name' => 'days',
                'status' => 1,
                'featured' => 0,
                'capital_back' => 1,
                'lifetime' => 0,
                'repeat_time' => 'daily',
                'description' => 'A balanced plan offering moderate risk with good returns for growing your portfolio.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Standard Plan',
                'minimum' => 1000.00000000,
                'maximum' => 5000.00000000,
                'fixed_amount' => 0.00000000,
                'interest' => 10.00000000,
                'interest_type' => 1, // Percentage
                'time' => '60',
                'time_name' => 'days',
                'status' => 1,
                'featured' => 1,
                'capital_back' => 1,
                'lifetime' => 0,
                'repeat_time' => 'daily',
                'description' => 'Our most popular plan! Higher returns with manageable risk for serious investors.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium Plan',
                'minimum' => 5000.00000000,
                'maximum' => 10000.00000000,
                'fixed_amount' => 0.00000000,
                'interest' => 12.50000000,
                'interest_type' => 1, // Percentage
                'time' => '90',
                'time_name' => 'days',
                'status' => 1,
                'featured' => 1,
                'capital_back' => 1,
                'lifetime' => 0,
                'repeat_time' => 'daily',
                'description' => 'Premium returns for substantial investments. Enhanced portfolio growth with higher yields.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gold Plan',
                'minimum' => 10000.00000000,
                'maximum' => 25000.00000000,
                'fixed_amount' => 0.00000000,
                'interest' => 15.00000000,
                'interest_type' => 1, // Percentage
                'time' => '120',
                'time_name' => 'days',
                'status' => 1,
                'featured' => 0,
                'capital_back' => 1,
                'lifetime' => 0,
                'repeat_time' => 'daily',
                'description' => 'Exclusive plan for high-value investors seeking maximum returns over extended periods.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'VIP Plan',
                'minimum' => 25000.00000000,
                'maximum' => 50000.00000000,
                'fixed_amount' => 0.00000000,
                'interest' => 18.00000000,
                'interest_type' => 1, // Percentage
                'time' => '180',
                'time_name' => 'days',
                'status' => 1,
                'featured' => 1,
                'capital_back' => 1,
                'lifetime' => 0,
                'repeat_time' => 'daily',
                'description' => 'VIP-level returns for elite investors. Premium support and highest yield rates.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Diamond Plan',
                'minimum' => 50000.00000000,
                'maximum' => 100000.00000000,
                'fixed_amount' => 0.00000000,
                'interest' => 20.00000000,
                'interest_type' => 1, // Percentage
                'time' => '365',
                'time_name' => 'days',
                'status' => 1,
                'featured' => 0,
                'capital_back' => 1,
                'lifetime' => 0,
                'repeat_time' => 'daily',
                'description' => 'Our flagship plan for serious wealth building. Maximum returns for substantial investments.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fixed Starter',
                'minimum' => 0.00000000,
                'maximum' => 0.00000000,
                'fixed_amount' => 1000.00000000,
                'interest' => 150.00000000,
                'interest_type' => 0, // Fixed amount
                'time' => '30',
                'time_name' => 'days',
                'status' => 1,
                'featured' => 0,
                'capital_back' => 1,
                'lifetime' => 0,
                'repeat_time' => 'monthly',
                'description' => 'Fixed investment amount with guaranteed returns. Perfect for structured investment approach.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lifetime Plan',
                'minimum' => 15000.00000000,
                'maximum' => 100000.00000000,
                'fixed_amount' => 0.00000000,
                'interest' => 8.00000000,
                'interest_type' => 1, // Percentage
                'time' => '0',
                'time_name' => 'lifetime',
                'status' => 1,
                'featured' => 1,
                'capital_back' => 0,
                'lifetime' => 1,
                'repeat_time' => 'daily',
                'description' => 'Lifetime returns without capital back. Continuous daily income for perpetual growth.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Weekly Booster',
                'minimum' => 2000.00000000,
                'maximum' => 10000.00000000,
                'fixed_amount' => 0.00000000,
                'interest' => 25.00000000,
                'interest_type' => 1, // Percentage
                'time' => '4',
                'time_name' => 'weeks',
                'status' => 1,
                'featured' => 0,
                'capital_back' => 1,
                'lifetime' => 0,
                'repeat_time' => 'weekly',
                'description' => 'Short-term high-yield plan with weekly returns. Quick profits for active investors.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        // Insert plans into database
        DB::table('plans')->insert($plans);
    }
}
