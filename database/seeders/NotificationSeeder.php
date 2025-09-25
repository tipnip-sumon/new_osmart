<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AdminNotification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a user for testing (create one if none exists)
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'role' => 'customer'
            ]);
        }

        // Create sample notifications
        $notifications = [
            [
                'user_id' => $user->id,
                'title' => 'New Commission Earned',
                'message' => 'You earned ৳150.00 commission from your referral John Doe\'s purchase.',
                'type' => 'commission',
                'is_read' => false,
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
            [
                'user_id' => $user->id,
                'title' => 'Withdrawal Processed',
                'message' => 'Your withdrawal request of ৳2,500.00 has been successfully processed.',
                'type' => 'payment',
                'is_read' => false,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'user_id' => $user->id,
                'title' => 'New Team Member',
                'message' => 'Sarah Khan joined your team under your left leg.',
                'type' => 'mlm',
                'is_read' => true,
                'read_at' => now()->subHours(5),
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subHours(5),
            ],
            [
                'user_id' => null, // Global notification
                'title' => 'System Maintenance',
                'message' => 'Scheduled maintenance will occur on Sunday from 2:00 AM to 4:00 AM BDT.',
                'type' => 'system',
                'is_read' => true,
                'read_at' => now()->subHours(10),
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subHours(10),
            ],
            [
                'user_id' => $user->id,
                'title' => 'KYC Verification Complete',
                'message' => 'Your KYC verification has been approved. You can now access all features.',
                'type' => 'account',
                'is_read' => true,
                'read_at' => now()->subWeek(),
                'created_at' => now()->subWeek(),
                'updated_at' => now()->subWeek(),
            ],
            [
                'user_id' => $user->id,
                'title' => 'Order Confirmation',
                'message' => 'Your order #ORD-001 has been confirmed and is being processed.',
                'type' => 'order',
                'is_read' => false,
                'created_at' => now()->subMinutes(30),
                'updated_at' => now()->subMinutes(30),
            ],
            [
                'user_id' => $user->id,
                'title' => 'Welcome Bonus',
                'message' => 'Congratulations! You\'ve received a ৳50 welcome bonus to your account.',
                'type' => 'bonus',
                'is_read' => false,
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subHours(1),
            ],
        ];

        foreach ($notifications as $notification) {
            AdminNotification::create($notification);
        }
    }
}
