<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if categories already exist
        if (Category::count() > 0) {
            $this->command->info('Categories already exist. Skipping seeding.');
            return;
        }

        $categories = [
            [
                'name' => 'Health & Wellness',
                'description' => 'Products for health, wellness, and medical needs',
                'icon' => 'bx bx-plus-medical',
                'color_code' => '#28a745',
                'commission_rate' => 10.00,
                'commission_type' => 'percentage',
                'subcategories' => [
                    'Vitamins & Supplements',
                    'Medical Devices',
                    'Health Monitors',
                    'First Aid',
                    'Personal Care'
                ]
            ],
            [
                'name' => 'Beauty & Personal Care',
                'description' => 'Beauty products, cosmetics, and personal care items',
                'icon' => 'bx bx-star',
                'color_code' => '#e83e8c',
                'commission_rate' => 12.00,
                'commission_type' => 'percentage',
                'subcategories' => [
                    'Skincare',
                    'Makeup',
                    'Hair Care',
                    'Fragrances',
                    'Personal Hygiene'
                ]
            ],
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices, gadgets, and tech accessories',
                'icon' => 'bx bx-laptop',
                'color_code' => '#007bff',
                'commission_rate' => 8.00,
                'commission_type' => 'percentage',
                'subcategories' => [
                    'Smartphones',
                    'Computers',
                    'Audio & Video',
                    'Smart Home',
                    'Electronic Accessories'
                ]
            ],
            [
                'name' => 'Food & Beverages',
                'description' => 'Food products, beverages, and nutritional items',
                'icon' => 'bx bx-food-menu',
                'color_code' => '#fd7e14',
                'commission_rate' => 15.00,
                'commission_type' => 'percentage',
                'subcategories' => [
                    'Organic Foods',
                    'Beverages',
                    'Snacks',
                    'Nutrition Bars',
                    'Special Diets'
                ]
            ],
            [
                'name' => 'Sports & Fitness',
                'description' => 'Sports equipment, fitness gear, and athletic wear',
                'icon' => 'bx bx-dumbbell',
                'color_code' => '#20c997',
                'commission_rate' => 11.00,
                'commission_type' => 'percentage',
                                'subcategories' => [
                    'Fitness Equipment',
                    'Outdoor Gear',
                    'Sports Accessories',
                    'Athletic Wear',
                    'Team Sports'
                ]
            ],
            [
                'name' => 'Home & Garden',
                'description' => 'Home improvement, furniture, and garden supplies',
                'icon' => 'bx bx-home',
                'color_code' => '#6f42c1',
                'commission_rate' => 9.00,
                'commission_type' => 'percentage',
                'subcategories' => [
                    'Furniture',
                    'Home Decor',
                    'Garden Tools',
                    'Kitchen & Dining',
                    'Storage Solutions'
                ]
            ],
            [
                'name' => 'Fashion & Accessories',
                'description' => 'Clothing, shoes, jewelry, and fashion accessories',
                'icon' => 'bx bx-closet',
                'color_code' => '#dc3545',
                'commission_rate' => 13.00,
                'commission_type' => 'percentage',
                'subcategories' => [
                    'Clothing',
                    'Shoes',
                    'Jewelry',
                    'Bags & Wallets',
                    'Style Accessories'
                ]
            ],
            [
                'name' => 'Books & Education',
                'description' => 'Books, educational materials, and learning resources',
                'icon' => 'bx bx-book',
                'color_code' => '#6c757d',
                'commission_rate' => 7.00,
                'commission_type' => 'percentage',
                'subcategories' => [
                    'Books',
                    'E-books',
                    'Educational Courses',
                    'Study Materials',
                    'Digital Resources'
                ]
            ]
        ];

        foreach ($categories as $index => $categoryData) {
            // Create parent category
            $parentCategory = Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => $categoryData['description'],
                'icon' => $categoryData['icon'],
                'color_code' => $categoryData['color_code'],
                'sort_order' => $index + 1,
                'status' => 'active',
                'is_featured' => $index < 4, // First 4 categories are featured
                'commission_rate' => $categoryData['commission_rate'],
                'commission_type' => $categoryData['commission_type'],
                'show_in_menu' => true,
                'show_in_footer' => true,
                'meta_title' => $categoryData['name'] . ' - MLM Ecommerce',
                'meta_description' => $categoryData['description']
            ]);

            // Create subcategories
            foreach ($categoryData['subcategories'] as $subIndex => $subcategoryName) {
                Category::create([
                    'name' => $subcategoryName,
                    'slug' => Str::slug($subcategoryName),
                    'parent_id' => $parentCategory->id,
                    'sort_order' => $subIndex + 1,
                    'status' => 'active',
                    'is_featured' => false,
                    'commission_rate' => $categoryData['commission_rate'],
                    'commission_type' => $categoryData['commission_type'],
                    'show_in_menu' => false,
                    'show_in_footer' => false,
                    'meta_title' => $subcategoryName . ' - ' . $categoryData['name'],
                    'meta_description' => 'Browse our collection of ' . strtolower($subcategoryName)
                ]);
            }
        }

        $this->command->info('Categories seeded successfully!');
    }
}
