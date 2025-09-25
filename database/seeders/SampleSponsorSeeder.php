<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SampleSponsorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample sponsor user for testing
        $sponsor = User::firstOrCreate(
            ['username' => 'sponsor_admin'],
            [
                'firstname' => 'Sponsor',
                'lastname' => 'Admin',
                'name' => 'Sponsor Admin',
                'email' => 'sponsor.admin@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567890',
                'role' => 'admin',
                'status' => 'active',
                'referral_code' => 'SPADMIN',
                'referral_hash' => 'ABCD1234',
                'ev' => 1,
                'sv' => 1,
                'kv' => 1,
                'email_verified_at' => now(),
                'country' => 'US',
                'address' => '123 Admin Street, Admin City, AC 12345'
            ]
        );

        // Create another sample sponsor
        $sponsor2 = User::firstOrCreate(
            ['username' => 'john_sponsor'],
            [
                'firstname' => 'John',
                'lastname' => 'Doe',
                'name' => 'John Doe',
                'email' => 'john.sponsor@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+1987654321',
                'role' => 'affiliate',
                'status' => 'active',
                'referral_code' => 'JOHN001',
                'referral_hash' => 'EFGH5678',
                'ev' => 1,
                'sv' => 1,
                'kv' => 1,
                'email_verified_at' => now(),
                'country' => 'US',
                'address' => '456 Sponsor Avenue, Sponsor City, SC 67890'
            ]
        );

        $this->command->info('Sample sponsor users created successfully!');
        $this->command->line('Test sponsors:');
        $this->command->line("- Username: {$sponsor->username}, Referral Code: {$sponsor->referral_code}, Hash: {$sponsor->referral_hash}");
        $this->command->line("- Username: {$sponsor2->username}, Referral Code: {$sponsor2->referral_code}, Hash: {$sponsor2->referral_hash}");
    }
}
