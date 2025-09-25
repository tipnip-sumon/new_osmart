<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use Illuminate\Support\Str;

class CheckCategories extends Command
{
    protected $signature = 'categories:check';
    protected $description = 'Check and create sample categories if none exist';

    public function handle()
    {
        $this->info('Checking categories in database...');
        
        $categoriesCount = Category::count();
        $this->info("Found {$categoriesCount} categories in database.");
        
        if ($categoriesCount === 0) {
            $this->info('No categories found. Creating sample categories...');
            
            $sampleCategories = [
                [
                    'name' => 'Electronics',
                    'slug' => 'electronics',
                    'description' => 'Electronic devices and accessories',
                    'image' => 'categories/electronics.jpg',
                    'status' => 'active',
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Fashion',
                    'slug' => 'fashion',
                    'description' => 'Clothing and fashion accessories',
                    'image' => 'categories/fashion.jpg',
                    'status' => 'active',
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Home & Living',
                    'slug' => 'home-living',
                    'description' => 'Home decor and living essentials',
                    'image' => 'categories/home-living.jpg',
                    'status' => 'active',
                    'sort_order' => 3,
                ],
                [
                    'name' => 'Health & Beauty',
                    'slug' => 'health-beauty',
                    'description' => 'Health and beauty products',
                    'image' => 'categories/health-beauty.jpg',
                    'status' => 'active',
                    'sort_order' => 4,
                ],
            ];
            
            foreach ($sampleCategories as $categoryData) {
                Category::create($categoryData);
                $this->info("Created category: {$categoryData['name']}");
            }
            
            $this->info('Sample categories created successfully!');
        } else {
            $this->info('Categories already exist. Showing first 5:');
            
            Category::take(5)->get(['id', 'name', 'slug', 'status'])->each(function ($category) {
                $this->line("ID: {$category->id}, Name: {$category->name}, Slug: {$category->slug}, Status: {$category->status}");
            });
        }
        
        return 0;
    }
}
