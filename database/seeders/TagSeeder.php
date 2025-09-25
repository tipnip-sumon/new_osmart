<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Fashion and style related products',
                'color' => '#e91e63',
                'sort_order' => 1,
                'is_active' => true,
                'meta_title' => 'Fashion Products',
                'meta_description' => 'Discover the latest fashion trends and styles',
                'meta_keywords' => 'fashion, style, clothing, trends',
            ],
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Electronic devices and gadgets',
                'color' => '#2196f3',
                'sort_order' => 2,
                'is_active' => true,
                'meta_title' => 'Electronic Products',
                'meta_description' => 'Latest electronic devices and technology gadgets',
                'meta_keywords' => 'electronics, gadgets, technology, devices',
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Home improvement and garden products',
                'color' => '#4caf50',
                'sort_order' => 3,
                'is_active' => false,
                'meta_title' => 'Home & Garden Products',
                'meta_description' => 'Transform your home and garden with our products',
                'meta_keywords' => 'home, garden, improvement, decoration',
            ],
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'Latest technology and innovation',
                'color' => '#9c27b0',
                'sort_order' => 4,
                'is_active' => true,
                'meta_title' => 'Technology Products',
                'meta_description' => 'Cutting-edge technology and innovation products',
                'meta_keywords' => 'technology, innovation, tech, gadgets',
            ],
            [
                'name' => 'Sports & Fitness',
                'slug' => 'sports-fitness',
                'description' => 'Sports equipment and fitness products',
                'color' => '#ff5722',
                'sort_order' => 5,
                'is_active' => true,
                'meta_title' => 'Sports & Fitness Products',
                'meta_description' => 'Quality sports equipment and fitness gear',
                'meta_keywords' => 'sports, fitness, exercise, equipment',
            ],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
