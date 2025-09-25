<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use App\Models\User;
use App\Models\Admin;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skip if coupons already exist
        if (Coupon::count() > 0) {
            $this->command->info('Coupons already exist. Skipping seeder.');
            return;
        }
        
        // Get admin user for relationships
        $adminUser = Admin::first();
        
        // Create a default admin user if none exists
        if (!$adminUser) {
            $adminUser = Admin::create([
                'name' => 'System Admin',
                'email' => 'admin@osmartbd.com',
                'email_verified_at' => now(),
                'password' => bcrypt('admin123'),
                'role' => 'super_admin',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        $vendorUsers = User::where('role', 'vendor')->take(3)->get();

        // Global Coupons (Created by Admin)
        $globalCoupons = [
            [
                'code' => 'WELCOME10',
                'name' => 'Welcome Discount',
                'description' => 'Welcome new customers with 10% off their first order',
                'type' => 'percentage',
                'value' => 10.00,
                'minimum_amount' => 500.00, // ৳500
                'maximum_discount' => 200.00, // ৳200
                'usage_limit' => 1000,
                'usage_limit_per_user' => 1,
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'is_active' => true,
                'auto_apply' => true,
                'first_order_only' => true,
                'priority' => 8,
                'vendor_id' => null,
                'created_by' => $adminUser->id,
                'terms_conditions' => 'Valid for new customers only. Cannot be combined with other offers.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'SAVE200',
                'name' => 'Save ৳200',
                'description' => 'Get ৳200 off on orders above ৳1000',
                'type' => 'fixed',
                'value' => 200.00, // ৳200
                'minimum_amount' => 1000.00, // ৳1000
                'usage_limit' => 500,
                'usage_limit_per_user' => 3,
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
                'is_active' => true,
                'auto_apply' => false,
                'priority' => 7,
                'vendor_id' => null,
                'created_by' => $adminUser->id,
                'terms_conditions' => 'Minimum order value ৳1000. Valid on all products.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Free Shipping',
                'description' => 'Get free shipping on all orders',
                'type' => 'free_shipping',
                'value' => 0.00,
                'minimum_amount' => 250.00, // ৳250
                'usage_limit' => null,
                'usage_limit_per_user' => null,
                'start_date' => now(),
                'end_date' => now()->addYear(),
                'is_active' => true,
                'auto_apply' => true,
                'free_shipping' => true,
                'priority' => 5,
                'vendor_id' => null,
                'created_by' => $adminUser->id,
                'terms_conditions' => 'Free shipping on orders above ৳250. Valid in Bangladesh.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'BUY2GET1',
                'name' => 'Buy 2 Get 1 Free',
                'description' => 'Buy 2 items and get 1 free',
                'type' => 'buy_x_get_y',
                'value' => 100.00, // 100% discount on the free item
                'buy_quantity' => 2,
                'get_quantity' => 1,
                'usage_limit' => 200,
                'usage_limit_per_user' => 5,
                'start_date' => now(),
                'end_date' => now()->addMonths(2),
                'is_active' => true,
                'auto_apply' => false,
                'priority' => 6,
                'vendor_id' => null,
                'created_by' => $adminUser->id,
                'terms_conditions' => 'Buy any 2 items and get the cheapest one free. Limited time offer.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'BULK15',
                'name' => 'Bulk Discount 15%',
                'description' => '15% off when you buy 5 or more items',
                'type' => 'bulk_discount',
                'value' => 15.00,
                'bulk_min_quantity' => 5,
                'usage_limit' => 100,
                'usage_limit_per_user' => 2,
                'start_date' => now(),
                'end_date' => now()->addMonths(4),
                'is_active' => true,
                'auto_apply' => true,
                'priority' => 7,
                'vendor_id' => null,
                'created_by' => $adminUser->id,
                'terms_conditions' => 'Applicable when purchasing 5 or more items in a single order.',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($globalCoupons as $couponData) {
            Coupon::create($couponData);
        }

        // Vendor Specific Coupons
        if ($vendorUsers->count() > 0) {
            $vendorCoupons = [
                [
                    'code' => 'VENDOR10',
                    'name' => 'Vendor Special 10%',
                    'description' => '10% discount on all vendor products',
                    'type' => 'percentage',
                    'value' => 10.00,
                    'minimum_amount' => 30.00,
                    'maximum_discount' => 50.00,
                    'usage_limit' => 300,
                    'usage_limit_per_user' => 5,
                    'start_date' => now(),
                    'end_date' => now()->addMonths(3),
                    'is_active' => true,
                    'auto_apply' => false,
                    'priority' => 6,
                    'vendor_id' => $vendorUsers->first()->id,
                    'created_by' => $vendorUsers->first()->id,
                    'terms_conditions' => 'Valid only on this vendor\'s products.',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'FLASH25',
                    'name' => 'Flash Sale 25%',
                    'description' => 'Limited time flash sale - 25% off everything',
                    'type' => 'percentage',
                    'value' => 25.00,
                    'minimum_amount' => null,
                    'maximum_discount' => 100.00,
                    'usage_limit' => 50,
                    'usage_limit_per_user' => 1,
                    'start_date' => now(),
                    'end_date' => now()->addDays(7),
                    'is_active' => true,
                    'auto_apply' => false,
                    'priority' => 9,
                    'vendor_id' => $vendorUsers->first()->id,
                    'created_by' => $vendorUsers->first()->id,
                    'terms_conditions' => 'Flash sale valid for 7 days only. One use per customer.',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];

            foreach ($vendorCoupons as $couponData) {
                Coupon::create($couponData);
            }
        }

        // Expired Coupons (for testing)
        $expiredCoupons = [
            [
                'code' => 'EXPIRED20',
                'name' => 'Expired Coupon',
                'description' => 'This coupon has expired',
                'type' => 'percentage',
                'value' => 20.00,
                'minimum_amount' => 50.00,
                'usage_limit' => 100,
                'usage_limit_per_user' => 2,
                'start_date' => now()->subMonths(2),
                'end_date' => now()->subDays(7),
                'is_active' => true,
                'auto_apply' => false,
                'priority' => 5,
                'vendor_id' => null,
                'created_by' => $adminUser->id,
                'terms_conditions' => 'This coupon has expired for testing purposes.',
                'created_at' => now()->subMonths(2),
                'updated_at' => now()->subDays(7)
            ]
        ];

        foreach ($expiredCoupons as $couponData) {
            Coupon::create($couponData);
        }

        // Scheduled Coupons (for testing)
        $scheduledCoupons = [
            [
                'code' => 'FUTURE30',
                'name' => 'Future Promotion',
                'description' => 'This coupon will be active next month',
                'type' => 'percentage',
                'value' => 30.00,
                'minimum_amount' => 75.00,
                'maximum_discount' => 150.00,
                'usage_limit' => 200,
                'usage_limit_per_user' => 3,
                'start_date' => now()->addMonth(),
                'end_date' => now()->addMonths(2),
                'is_active' => true,
                'auto_apply' => false,
                'priority' => 8,
                'vendor_id' => null,
                'created_by' => $adminUser->id,
                'terms_conditions' => 'Future promotion for testing scheduled coupons.',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($scheduledCoupons as $couponData) {
            Coupon::create($couponData);
        }

        $this->command->info('Coupon seeding completed successfully!');
        $this->command->info('Created:');
        $this->command->info('- 5 Global coupons');
        $this->command->info('- 2 Vendor-specific coupons');
        $this->command->info('- 1 Expired coupon (for testing)');
        $this->command->info('- 1 Scheduled coupon (for testing)');
        $this->command->info('Total: ' . Coupon::count() . ' coupons');
    }
}
