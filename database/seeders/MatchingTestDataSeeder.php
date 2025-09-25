<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BinaryMatching;
use App\Models\BinarySummary;
use Carbon\Carbon;

class MatchingTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (assuming it exists)
        $user = User::first();
        
        if (!$user) {
            $this->command->info('No users found. Please create users first.');
            return;
        }

        // Create some test binary matching records
        BinaryMatching::updateOrCreate(
            [
                'user_id' => $user->id,
                'match_date' => Carbon::today(),
                'period' => 'daily'
            ],
            [
                'left_current_volume' => 5000.00,
                'right_current_volume' => 3000.00,
                'matching_volume' => 3000.00,
                'matching_percentage' => 10.00,
                'matching_bonus' => 300.00,
                'left_carry_next' => 2000.00,
                'right_carry_next' => 0.00,
                'status' => 'processed',
                'is_processed' => true,
                'processed_at' => now(),
            ]
        );

        BinaryMatching::updateOrCreate(
            [
                'user_id' => $user->id,
                'match_date' => Carbon::yesterday(),
                'period' => 'daily'
            ],
            [
                'left_current_volume' => 4000.00,
                'right_current_volume' => 4500.00,
                'matching_volume' => 4000.00,
                'matching_percentage' => 10.00,
                'matching_bonus' => 400.00,
                'left_carry_next' => 0.00,
                'right_carry_next' => 500.00,
                'status' => 'processed',
                'is_processed' => true,
                'processed_at' => now(),
            ]
        );

        // Create binary summary
        BinarySummary::updateOrCreate(
            ['user_id' => $user->id],
            [
                'lifetime_left_volume' => 15000.00,
                'lifetime_right_volume' => 12000.00,
                'lifetime_matching_bonus' => 1200.00,
                'left_carry_balance' => 3000.00,
                'right_carry_balance' => 0.00,
                'current_period_left' => 5000.00,
                'current_period_right' => 3000.00,
                'current_period_bonus' => 300.00,
                'monthly_left_volume' => 9000.00,
                'monthly_right_volume' => 7500.00,
                'monthly_matching_bonus' => 700.00,
                'total_matching_records' => 2,
                'last_calculated_at' => now(),
            ]
        );

        $this->command->info('Test matching data created for user: ' . $user->username);
    }
}
