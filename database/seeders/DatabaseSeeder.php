<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting O-Smart BD Database Seeding...');
        
        $this->call([
            // 1. Core System Settings (Must be first)
            GeneralSettingsSeeder::class,
            EmailTemplateSeeder::class,
            
            // 2. User Management
            AdminSeeder::class,
            UserSeeder::class,
            
            // 3. Product Structure (Categories, Brands, etc.)
            CategorySeeder::class,
            BrandSeeder::class,
            AttributeSeeder::class,
            UnitSeeder::class,
            TagSeeder::class,
            
            // 4. E-commerce Features
            CollectionSeeder::class,
            CouponSeeder::class,
            PaymentMethodSeeder::class,
            WarehouseSeeder::class,
            
            // 5. MLM System (Basic)
            // SubscriptionPlanSeeder::class, // Removed - subscription_plans table doesn't exist
            CommissionSeeder::class,
            // CommissionSettingSeeder::class, // Has column issues
            
            // 6. Admin Interface
            ModalSettingsSeeder::class,
        ]);
        
        $this->command->info('✅ O-Smart BD Database Seeding Completed Successfully!');
        $this->command->info('🏪 Your e-commerce platform is ready with:');
        $this->command->info('   • Admin panel with proper settings');
        $this->command->info('   • Basic MLM system configuration');
        $this->command->info('   • Product categories and brands');
        $this->command->info('   • Coupon system with BDT currency');
        $this->command->info('   • Payment methods and warehouses');
        $this->command->info('   • Email templates and basic settings');
        $this->command->info('   • User and admin accounts');
        $this->command->info('');
        $this->command->warn('⚠️  Some advanced seeders are commented out:');
        $this->command->warn('   • CommissionSettingSeeder (column issues)');
        $this->command->warn('   • AdminSystemSeeder (support ticket issues)');
        $this->command->warn('   • Sample product and order data');
        $this->command->info('');
        $this->command->info('🔑 Default Login Credentials:');
        $this->command->info('   Admin: superadmin@admin.com / SuperAdmin@123');
        $this->command->info('   User: default@example.com / password');
    }
}
