<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create default warehouse if none exists
        if (Warehouse::count() === 0) {
            Warehouse::create([
                'name' => 'Main Warehouse',
                'code' => 'MAIN-001',
                'description' => 'Default main warehouse for product storage',
                'address' => '123 Main Street',
                'city' => 'Dhaka',
                'state' => 'Dhaka Division',
                'country' => 'Bangladesh',
                'postal_code' => '1000',
                'phone' => '+880-1234567890',
                'email' => 'warehouse@company.com',
                'manager_name' => 'Warehouse Manager',
                'capacity' => 10000.00,
                'current_utilization' => 0.00,
                'storage_type' => 'general',
                'operating_hours' => [
                    'monday' => ['open' => '08:00', 'close' => '18:00'],
                    'tuesday' => ['open' => '08:00', 'close' => '18:00'],
                    'wednesday' => ['open' => '08:00', 'close' => '18:00'],
                    'thursday' => ['open' => '08:00', 'close' => '18:00'],
                    'friday' => ['open' => '08:00', 'close' => '18:00'],
                    'saturday' => ['open' => '08:00', 'close' => '14:00'],
                    'sunday' => ['closed' => true]
                ],
                'is_active' => true,
                'is_default' => true,
                'latitude' => 23.8103,
                'longitude' => 90.4125
            ]);
        }
    }
}
