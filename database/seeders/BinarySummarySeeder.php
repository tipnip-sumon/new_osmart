<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BinarySummarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // Get all users who don't have binary summaries
        $usersWithoutSummary = DB::table('users')
            ->leftJoin('binary_summaries', 'users.id', '=', 'binary_summaries.user_id')
            ->whereNull('binary_summaries.user_id')
            ->select('users.id')
            ->get();

        if ($usersWithoutSummary->count() === 0) {
            $this->command->info('✅ All users already have binary summaries!');
            return;
        }

        $this->command->info('📊 Creating binary summaries for ' . $usersWithoutSummary->count() . ' users...');

        $binarySummariesData = [];
        foreach ($usersWithoutSummary as $user) {
            $binarySummariesData[] = [
                'user_id' => $user->id,
                'left_carry_balance' => 0.00,
                'right_carry_balance' => 0.00,
                'lifetime_left_volume' => 0.00,
                'lifetime_right_volume' => 0.00,
                'lifetime_matching_bonus' => 0.00,
                'lifetime_slot_bonus' => 0.00,
                'lifetime_capped_amount' => 0.00,
                'current_period_left' => 0.00,
                'current_period_right' => 0.00,
                'current_period_bonus' => 0.00,
                'monthly_left_volume' => 0.00,
                'monthly_right_volume' => 0.00,
                'monthly_matching_bonus' => 0.00,
                'monthly_capped_amount' => 0.00,
                'weekly_left_volume' => 0.00,
                'weekly_right_volume' => 0.00,
                'weekly_matching_bonus' => 0.00,
                'weekly_capped_amount' => 0.00,
                'daily_left_volume' => 0.00,
                'daily_right_volume' => 0.00,
                'daily_matching_bonus' => 0.00,
                'daily_capped_amount' => 0.00,
                'total_matching_records' => 0,
                'total_slot_matches' => 0,
                'last_daily_reset' => null,
                'last_weekly_reset' => null,
                'last_monthly_reset' => null,
                'is_active' => true,
                'last_calculated_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Insert in batches of 100 to avoid memory issues
        $chunks = array_chunk($binarySummariesData, 100);
        foreach ($chunks as $chunk) {
            DB::table('binary_summaries')->insert($chunk);
        }

        $this->command->info('✅ Binary summaries created for ' . count($binarySummariesData) . ' users!');
        
        // Show final statistics
        $totalUsers = DB::table('users')->count();
        $totalBinarySummaries = DB::table('binary_summaries')->count();
        
        $this->command->info('📊 Final Statistics:');
        $this->command->info("   Total Users: {$totalUsers}");
        $this->command->info("   Total Binary Summaries: {$totalBinarySummaries}");
        
        if ($totalUsers === $totalBinarySummaries) {
            $this->command->info('✅ All users now have binary summaries!');
        } else {
            $this->command->warn('⚠️  Some users still missing binary summaries');
        }
    }
}