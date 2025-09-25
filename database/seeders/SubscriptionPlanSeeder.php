<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic Plan',
                'slug' => 'basic-plan',
                'description' => 'Perfect for small businesses getting started',
                'price' => 29.99,
                'billing_period' => 'monthly',
                'trial_days' => 7,
                'features' => json_encode([
                    'Up to 100 products',
                    'Basic analytics',
                    'Email support',
                    'Standard commission rate',
                ]),
                'is_active' => true,
                'max_products' => 100,
                'max_orders' => 1000,
                'commission_rate' => 0.05,
                'priority_support' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Professional Plan',
                'slug' => 'professional-plan',
                'description' => 'Ideal for growing businesses',
                'price' => 79.99,
                'billing_period' => 'monthly',
                'trial_days' => 14,
                'features' => json_encode([
                    'Up to 1000 products',
                    'Advanced analytics',
                    'Priority email support',
                    'Reduced commission rate',
                    'Custom branding',
                ]),
                'is_active' => true,
                'max_products' => 1000,
                'max_orders' => 10000,
                'commission_rate' => 0.03,
                'priority_support' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enterprise Plan',
                'slug' => 'enterprise-plan',
                'description' => 'For large-scale operations',
                'price' => 199.99,
                'billing_period' => 'monthly',
                'trial_days' => 30,
                'features' => json_encode([
                    'Unlimited products',
                    'Full analytics suite',
                    '24/7 phone support',
                    'Lowest commission rate',
                    'Custom branding',
                    'API access',
                    'Dedicated account manager',
                ]),
                'is_active' => true,
                'max_products' => null,
                'max_orders' => null,
                'commission_rate' => 0.02,
                'priority_support' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('subscription_plans')->insert($plans);
    }
}
