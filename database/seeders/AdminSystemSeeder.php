<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Payment;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Popup;
use App\Models\WithdrawMethod;
use App\Models\SupportTicket;
use App\Models\TransactionReceipt;
use Illuminate\Support\Facades\Hash;

class AdminSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin users
        $this->createUsers();
        
        // Seed payment methods
        $this->seedPaymentMethods();
        
        // Seed attributes and values
        $this->seedAttributes();
        
        // Seed categories
        $this->seedCategories();
        
        // Seed banners
        $this->seedBanners();
        
        // Seed popups
        $this->seedPopups();
        
        // Seed withdraw methods
        $this->seedWithdrawMethods();
        
        // Seed support tickets
        $this->seedSupportTickets();
        
        // Seed transaction receipts
        $this->seedTransactionReceipts();
    }

    private function createUsers()
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'admin'
            ]
        );

        // Create vendor users
        User::firstOrCreate(
            ['email' => 'vendor1@example.com'],
            [
                'name' => 'Tech Store Vendor',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'vendor'
            ]
        );

        User::firstOrCreate(
            ['email' => 'vendor2@example.com'],
            [
                'name' => 'Fashion Hub Vendor',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'vendor'
            ]
        );

        // Create customer users
        User::firstOrCreate(
            ['email' => 'customer1@example.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'customer'
            ]
        );

        User::firstOrCreate(
            ['email' => 'customer2@example.com'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'customer'
            ]
        );
    }

    private function seedPaymentMethods()
    {
        $payments = [
            [
                'name' => 'Stripe',
                'slug' => 'stripe',
                'type' => 'credit_card',
                'gateway_name' => 'stripe',
                'is_active' => true,
                'is_default' => true,
                'sort_order' => 1,
                'description' => 'Pay securely with your credit or debit card via Stripe',
                'processing_fee' => 2.9,
                'fee_type' => 'percentage',
                'supported_currencies' => ['USD', 'EUR', 'GBP'],
                'test_mode' => true
            ],
            [
                'name' => 'PayPal',
                'slug' => 'paypal',
                'type' => 'digital_wallet',
                'gateway_name' => 'paypal',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 2,
                'description' => 'Pay with your PayPal account',
                'processing_fee' => 3.4,
                'fee_type' => 'percentage',
                'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD'],
                'test_mode' => true
            ],
            [
                'name' => 'Bank Transfer',
                'slug' => 'bank-transfer',
                'type' => 'bank_transfer',
                'is_active' => true,
                'sort_order' => 3,
                'description' => 'Direct bank transfer',
                'processing_fee' => 5.00,
                'fee_type' => 'fixed',
                'supported_currencies' => ['USD'],
                'test_mode' => false
            ]
        ];

        foreach ($payments as $payment) {
            Payment::updateOrCreate(
                ['slug' => $payment['slug']],
                $payment
            );
        }
    }

    private function seedAttributes()
    {
        $attributes = [
            [
                'name' => 'Color',
                'slug' => 'color',
                'type' => 'color',
                'display_name' => 'Product Color',
                'is_required' => false,
                'is_filterable' => true,
                'is_variation' => true,
                'is_global' => true,
                'sort_order' => 1,
                'status' => 'active',
                'frontend_type' => 'color'
            ],
            [
                'name' => 'Size',
                'slug' => 'size',
                'type' => 'select',
                'display_name' => 'Product Size',
                'is_required' => true,
                'is_filterable' => true,
                'is_variation' => true,
                'is_global' => true,
                'sort_order' => 2,
                'status' => 'active',
                'frontend_type' => 'select'
            ],
            [
                'name' => 'Material',
                'slug' => 'material',
                'type' => 'select',
                'display_name' => 'Material Type',
                'is_required' => false,
                'is_filterable' => true,
                'is_variation' => false,
                'is_global' => true,
                'sort_order' => 3,
                'status' => 'active',
                'frontend_type' => 'select'
            ]
        ];

        foreach ($attributes as $attributeData) {
            $attribute = Attribute::updateOrCreate(
                ['slug' => $attributeData['slug']],
                $attributeData
            );

            // Add attribute values
            if ($attribute->slug === 'color') {
                $colorValues = [
                    ['value' => 'red', 'display_name' => 'Red', 'color_code' => '#FF0000'],
                    ['value' => 'blue', 'display_name' => 'Blue', 'color_code' => '#0000FF'],
                    ['value' => 'green', 'display_name' => 'Green', 'color_code' => '#008000'],
                    ['value' => 'black', 'display_name' => 'Black', 'color_code' => '#000000'],
                    ['value' => 'white', 'display_name' => 'White', 'color_code' => '#FFFFFF']
                ];

                foreach ($colorValues as $index => $value) {
                    AttributeValue::updateOrCreate(
                        ['attribute_id' => $attribute->id, 'value' => $value['value']],
                        array_merge($value, [
                            'sort_order' => $index + 1,
                            'status' => 'active'
                        ])
                    );
                }
            }

            if ($attribute->slug === 'size') {
                $sizeValues = [
                    ['value' => 'xs', 'display_name' => 'Extra Small (XS)'],
                    ['value' => 's', 'display_name' => 'Small (S)'],
                    ['value' => 'm', 'display_name' => 'Medium (M)', 'is_default' => true],
                    ['value' => 'l', 'display_name' => 'Large (L)'],
                    ['value' => 'xl', 'display_name' => 'Extra Large (XL)'],
                    ['value' => 'xxl', 'display_name' => 'Double XL (XXL)', 'extra_price' => 5.00]
                ];

                foreach ($sizeValues as $index => $value) {
                    AttributeValue::updateOrCreate(
                        ['attribute_id' => $attribute->id, 'value' => $value['value']],
                        array_merge($value, [
                            'sort_order' => $index + 1,
                            'status' => 'active'
                        ])
                    );
                }
            }
        }
    }

    private function seedCategories()
    {
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Electronic devices and gadgets',
                'sort_order' => 1,
                'status' => 'active',
                'is_featured' => true,
                'commission_rate' => 5.0,
                'commission_type' => 'percentage'
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Clothing and fashion accessories',
                'sort_order' => 2,
                'status' => 'active',
                'is_featured' => true,
                'commission_rate' => 10.0,
                'commission_type' => 'percentage'
            ],
            [
                'name' => 'Books',
                'slug' => 'books',
                'description' => 'Books and educational materials',
                'sort_order' => 3,
                'status' => 'active',
                'is_featured' => false,
                'commission_rate' => 15.0,
                'commission_type' => 'percentage'
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = Category::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );

            // Add subcategories
            if ($category->slug === 'electronics') {
                $subcategories = [
                    ['name' => 'Smartphones', 'slug' => 'smartphones'],
                    ['name' => 'Laptops', 'slug' => 'laptops'],
                    ['name' => 'Tablets', 'slug' => 'tablets']
                ];

                foreach ($subcategories as $index => $sub) {
                    Category::updateOrCreate(
                        ['slug' => $sub['slug']],
                        array_merge($sub, [
                            'parent_id' => $category->id,
                            'sort_order' => $index + 1,
                            'status' => 'active'
                        ])
                    );
                }
            }

            if ($category->slug === 'fashion') {
                $subcategories = [
                    ['name' => "Men's Clothing", 'slug' => 'mens-clothing'],
                    ['name' => "Women's Clothing", 'slug' => 'womens-clothing'],
                    ['name' => 'Accessories', 'slug' => 'accessories']
                ];

                foreach ($subcategories as $index => $sub) {
                    Category::updateOrCreate(
                        ['slug' => $sub['slug']],
                        array_merge($sub, [
                            'parent_id' => $category->id,
                            'sort_order' => $index + 1,
                            'status' => 'active'
                        ])
                    );
                }
            }
        }
    }

    private function seedBanners()
    {
        $banners = [
            [
                'title' => 'Summer Sale 2025',
                'subtitle' => 'Up to 70% Off',
                'description' => 'Limited time offer on all summer products',
                'image' => 'banners/summer-sale.jpg',
                'link_url' => '/summer-sale',
                'link_text' => 'Shop Now',
                'position' => 'hero',
                'type' => 'promotional',
                'status' => 'active',
                'sort_order' => 1,
                'start_date' => now(),
                'end_date' => now()->addMonths(2),
                'background_color' => '#FF6B6B',
                'text_color' => '#FFFFFF'
            ],
            [
                'title' => 'Free Shipping',
                'subtitle' => 'On orders over $50',
                'description' => 'Get free shipping on all orders above $50',
                'image' => 'banners/free-shipping.jpg',
                'position' => 'header',
                'type' => 'informational',
                'status' => 'active',
                'sort_order' => 1,
                'background_color' => '#4ECDC4',
                'text_color' => '#FFFFFF'
            ],
            [
                'title' => 'New Arrivals',
                'subtitle' => 'Latest Fashion Trends',
                'description' => 'Check out our newest fashion collection',
                'image' => 'banners/new-arrivals.jpg',
                'link_url' => '/new-arrivals',
                'link_text' => 'Explore',
                'position' => 'sidebar',
                'type' => 'product_showcase',
                'status' => 'active',
                'sort_order' => 1
            ]
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }

    private function seedPopups()
    {
        $popups = [
            [
                'name' => 'Newsletter Signup',
                'title' => 'Subscribe to Our Newsletter',
                'content' => '<p>Get 10% off your first order when you subscribe to our newsletter!</p><form><input type="email" placeholder="Enter your email" class="form-control mb-2"><button class="btn btn-primary">Subscribe</button></form>',
                'type' => 'newsletter',
                'trigger_type' => 'time_delay',
                'trigger_value' => '5',
                'position' => 'center',
                'size' => 'medium',
                'status' => 'active',
                'delay_seconds' => 5,
                'auto_close' => false,
                'cookie_lifetime' => 7
            ],
            [
                'name' => 'Exit Intent Offer',
                'title' => 'Wait! Don\'t Leave Yet!',
                'content' => '<p>Get 15% off your entire purchase with code <strong>SAVE15</strong></p><p>This offer expires in 24 hours!</p>',
                'type' => 'exit_intent',
                'trigger_type' => 'exit_intent',
                'position' => 'center',
                'size' => 'large',
                'status' => 'active',
                'auto_close' => false,
                'cookie_lifetime' => 1
            ],
            [
                'name' => 'Cookie Consent',
                'title' => 'Cookie Notice',
                'content' => '<p>We use cookies to improve your experience on our website. By browsing this website, you agree to our use of cookies.</p><button class="btn btn-primary btn-sm">Accept</button>',
                'type' => 'cookie_consent',
                'trigger_type' => 'immediate',
                'position' => 'bottom_center',
                'size' => 'small',
                'status' => 'active',
                'show_close_button' => false,
                'cookie_lifetime' => 365
            ]
        ];

        foreach ($popups as $popup) {
            Popup::create($popup);
        }
    }

    private function seedWithdrawMethods()
    {
        $methods = [
            [
                'name' => 'PayPal',
                'slug' => 'paypal',
                'description' => 'Withdraw funds to your PayPal account',
                'type' => 'paypal',
                'processing_time' => '1-2 business days',
                'min_amount' => 10.00,
                'max_amount' => 10000.00,
                'fixed_charge' => 0.30,
                'percentage_charge' => 2.9,
                'is_active' => true,
                'is_instant' => false,
                'auto_approval' => true,
                'required_fields' => [
                    ['name' => 'paypal_email', 'label' => 'PayPal Email', 'type' => 'email', 'required' => true]
                ]
            ],
            [
                'name' => 'Bank Transfer',
                'slug' => 'bank-transfer',
                'description' => 'Direct transfer to your bank account',
                'type' => 'bank_transfer',
                'processing_time' => '3-5 business days',
                'min_amount' => 50.00,
                'fixed_charge' => 5.00,
                'percentage_charge' => 0,
                'is_active' => true,
                'is_instant' => false,
                'requires_verification' => true,
                'auto_approval' => false,
                'required_fields' => [
                    ['name' => 'account_holder', 'label' => 'Account Holder Name', 'type' => 'text', 'required' => true],
                    ['name' => 'account_number', 'label' => 'Account Number', 'type' => 'text', 'required' => true],
                    ['name' => 'routing_number', 'label' => 'Routing Number', 'type' => 'text', 'required' => true],
                    ['name' => 'bank_name', 'label' => 'Bank Name', 'type' => 'text', 'required' => true]
                ]
            ],
            [
                'name' => 'Stripe Express',
                'slug' => 'stripe-express',
                'description' => 'Fast withdrawals via Stripe',
                'type' => 'stripe',
                'processing_time' => 'Instant',
                'min_amount' => 1.00,
                'max_amount' => 5000.00,
                'fixed_charge' => 0.25,
                'percentage_charge' => 1.5,
                'is_active' => true,
                'is_instant' => true,
                'auto_approval' => true
            ]
        ];

        foreach ($methods as $method) {
            WithdrawMethod::updateOrCreate(
                ['slug' => $method['slug']],
                $method
            );
        }
    }

    private function seedSupportTickets()
    {
        $adminUser = User::where('email', 'admin@admin.com')->first();
        $customer1 = User::where('email', 'customer1@example.com')->first();
        $customer2 = User::where('email', 'customer2@example.com')->first();

        if (!$adminUser || !$customer1 || !$customer2) {
            return;
        }

        $tickets = [
            [
                'user_id' => $customer1->id,
                'assigned_to' => $adminUser->id,
                'subject' => 'Issue with payment processing',
                'description' => 'I am having trouble processing my payment for order #1001. The payment keeps failing.',
                'priority' => 'high',
                'status' => 'in_progress',
                'type' => 'payment_issue',
                'source' => 'web',
                'tags' => ['payment', 'order', 'urgent']
            ],
            [
                'user_id' => $customer2->id,
                'subject' => 'Question about return policy',
                'description' => 'I would like to know more about your return policy for electronic items.',
                'priority' => 'normal',
                'status' => 'open',
                'type' => 'general_inquiry',
                'source' => 'web',
                'tags' => ['return', 'policy']
            ],
            [
                'user_id' => $customer1->id,
                'assigned_to' => $adminUser->id,
                'subject' => 'Account login issues',
                'description' => 'I cannot log into my account. I keep getting an error message.',
                'priority' => 'urgent',
                'status' => 'resolved',
                'type' => 'account_issue',
                'source' => 'email',
                'resolved_at' => now()->subHours(2),
                'first_response_at' => now()->subHours(3),
                'tags' => ['login', 'account', 'resolved']
            ]
        ];

        foreach ($tickets as $ticket) {
            SupportTicket::create($ticket);
        }
    }

    private function seedTransactionReceipts()
    {
        $vendor1 = User::where('email', 'vendor1@example.com')->first();
        $vendor2 = User::where('email', 'vendor2@example.com')->first();
        $customer1 = User::where('email', 'customer1@example.com')->first();
        $customer2 = User::where('email', 'customer2@example.com')->first();
        $admin = User::where('email', 'admin@admin.com')->first();

        if (!$vendor1 || !$vendor2 || !$customer1 || !$customer2 || !$admin) {
            return;
        }

        $receipts = [
            [
                'transaction_type' => 'payment',
                'vendor_id' => $vendor1->id,
                'customer_id' => $customer1->id,
                'amount' => 299.99,
                'currency' => 'USD',
                'payment_method' => 'Stripe',
                'transaction_id' => 'TXN_2025_001',
                'reference_number' => 'REF_2025_001',
                'gateway_transaction_id' => 'pi_1234567890',
                'gateway_response' => 'Payment successful',
                'description' => 'Payment for Electronics Order',
                'status' => 'confirmed',
                'transaction_date' => now()->subDays(2),
                'processed_by' => $admin->id,
                'verification_status' => 'verified',
                'verified_by' => $admin->id,
                'verified_at' => now()->subDays(1)
            ],
            [
                'transaction_type' => 'refund',
                'vendor_id' => $vendor2->id,
                'customer_id' => $customer2->id,
                'amount' => 159.50,
                'currency' => 'USD',
                'payment_method' => 'PayPal',
                'transaction_id' => 'TXN_2025_002',
                'reference_number' => 'REF_2025_002',
                'gateway_transaction_id' => 'paypal_ref_0987654321',
                'gateway_response' => 'Refund processed',
                'description' => 'Refund for returned fashion item',
                'status' => 'pending',
                'transaction_date' => now()->subHours(6),
                'processed_by' => $admin->id,
                'verification_status' => 'pending'
            ],
            [
                'transaction_type' => 'payout',
                'vendor_id' => $vendor1->id,
                'amount' => 1250.00,
                'currency' => 'USD',
                'payment_method' => 'Bank Transfer',
                'transaction_id' => 'TXN_2025_003',
                'reference_number' => 'REF_2025_003',
                'description' => 'Weekly vendor payout',
                'status' => 'confirmed',
                'transaction_date' => now()->subDays(1),
                'processed_by' => $admin->id,
                'verification_status' => 'verified',
                'verified_by' => $admin->id,
                'verified_at' => now()
            ]
        ];

        foreach ($receipts as $receipt) {
            TransactionReceipt::create($receipt);
        }
    }
}
