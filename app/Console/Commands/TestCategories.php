<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;

class TestCategories extends Command
{
    protected $signature = 'test:categories';
    protected $description = 'Test categories loading for homepage';

    public function handle()
    {
        $this->info('Testing categories loading...');
        
        // Test the same query as in HomeController
        $categories = Category::where('status', 'active')
            ->withCount(['products' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->limit(8)
            ->get();
        
        $this->info("Found {$categories->count()} active categories:");
        
        foreach ($categories as $category) {
            $this->line("ID: {$category->id}, Name: {$category->name}, Slug: {$category->slug}, Image: " . ($category->image ?: 'null') . ", Products: {$category->products_count}");
        }
        
        // Test if the relationships work
        $this->info("\nTesting category relationships...");
        
        $categoryWithProducts = Category::where('status', 'active')
            ->withCount('products')
            ->having('products_count', '>', 0)
            ->first();
            
        if ($categoryWithProducts) {
            $this->info("Category with products: {$categoryWithProducts->name} has {$categoryWithProducts->products_count} products");
        } else {
            $this->warn("No categories with products found");
        }
        
        return 0;
    }
}
