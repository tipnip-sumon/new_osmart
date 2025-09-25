<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collections = [
            [
                'name' => 'Summer Collection',
                'slug' => 'summer-collection',
                'description' => 'Light and breezy styles perfect for the warm summer months. Features breathable fabrics and vibrant colors.',
                'short_description' => 'Light and breezy styles for summer',
                'status' => 'active',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
                'color_code' => '#FF6B6B',
                'show_in_menu' => true,
                'meta_title' => 'Summer Collection - Fresh Summer Styles',
                'meta_description' => 'Discover our latest summer collection featuring light, comfortable clothing perfect for warm weather.',
            ],
            [
                'name' => 'Winter Collection',
                'slug' => 'winter-collection',
                'description' => 'Warm and cozy pieces designed to keep you comfortable during the cold winter season. Premium quality materials for maximum warmth.',
                'short_description' => 'Warm and cozy pieces for winter',
                'status' => 'active',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
                'color_code' => '#4ECDC4',
                'show_in_menu' => true,
                'meta_title' => 'Winter Collection - Cozy Winter Wear',
                'meta_description' => 'Stay warm and stylish with our winter collection featuring premium quality winter clothing.',
            ],
            [
                'name' => 'Holiday Special',
                'slug' => 'holiday-special',
                'description' => 'Special occasion wear perfect for holidays and celebrations. Elegant designs with a festive touch.',
                'short_description' => 'Special occasion wear for holidays',
                'status' => 'active',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 3,
                'color_code' => '#FFE66D',
                'show_in_menu' => true,
                'meta_title' => 'Holiday Special Collection - Festive Wear',
                'meta_description' => 'Celebrate in style with our holiday special collection featuring elegant festive wear.',
            ],
            [
                'name' => 'Casual Everyday',
                'slug' => 'casual-everyday',
                'description' => 'Comfortable and versatile pieces for your daily wardrobe. Perfect for work, weekend, and everything in between.',
                'short_description' => 'Comfortable pieces for everyday wear',
                'status' => 'active',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 4,
                'color_code' => '#A8E6CF',
                'show_in_menu' => true,
                'meta_title' => 'Casual Everyday Collection - Comfortable Daily Wear',
                'meta_description' => 'Find your perfect everyday pieces in our casual collection designed for comfort and style.',
            ],
            [
                'name' => 'Spring Arrivals',
                'slug' => 'spring-arrivals',
                'description' => 'Fresh new styles for the spring season. Featuring pastel colors and lightweight fabrics perfect for transitional weather.',
                'short_description' => 'Fresh styles for spring season',
                'status' => 'draft',
                'is_featured' => false,
                'is_active' => false,
                'sort_order' => 5,
                'color_code' => '#FFB3BA',
                'show_in_menu' => false,
                'meta_title' => 'Spring Arrivals - New Spring Fashion',
                'meta_description' => 'Welcome spring with our fresh new arrivals featuring beautiful pastel colors and lightweight fabrics.',
            ],
        ];

        foreach ($collections as $collection) {
            Collection::create($collection);
        }
    }
}
