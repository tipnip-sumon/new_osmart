<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commission;
use App\Models\User;
use Carbon\Carbon;

class CommissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users to assign commissions to (or create sample users if none exist)
        $users = User::limit(10)->get();
        
        if ($users->isEmpty()) {
            // Create sample users if none exist
            for ($i = 1; $i <= 5; $i++) {
                User::create([
                    'name' => "Test User $i",
                    'email' => "testuser$i@example.com",
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]);
            }
            $users = User::limit(5)->get();
        }

        $commissionTypes = ['referral', 'bonus', 'tier_bonus', 'performance', 'sales', 'monthly_bonus'];
        $statuses = ['pending', 'approved', 'paid', 'cancelled'];

        // Create 50 sample commission records
        for ($i = 1; $i <= 50; $i++) {
            $user = $users->random();
            $type = $commissionTypes[array_rand($commissionTypes)];
            $status = $statuses[array_rand($statuses)];
            
            $orderAmount = rand(1000, 50000); // Random order amount between 1000-50000 BDT
            $commissionRate = rand(2, 15) / 100; // Random rate between 2-15%
            $commissionAmount = $orderAmount * $commissionRate;
            
            Commission::create([
                'user_id' => $user->id,
                'referred_user_id' => $users->random()->id,
                'order_id' => rand(1000, 9999),
                'commission_type' => $type,
                'level' => rand(1, 5),
                'order_amount' => $orderAmount,
                'commission_rate' => $commissionRate,
                'commission_amount' => $commissionAmount,
                'status' => $status,
                'notes' => "Sample commission for testing - Type: $type",
                'earned_at' => Carbon::now()->subDays(rand(0, 30)),
                'approved_at' => $status === 'approved' || $status === 'paid' ? Carbon::now()->subDays(rand(0, 10)) : null,
                'paid_at' => $status === 'paid' ? Carbon::now()->subDays(rand(0, 5)) : null,
                'created_at' => Carbon::now()->subDays(rand(0, 60)),
                'updated_at' => Carbon::now()->subDays(rand(0, 30)),
            ]);
        }

        $this->command->info('Created 50 sample commission records');
    }
}
