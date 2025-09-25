<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class SeedDefaultProducts extends Command
{
    protected $signature = 'products:seed-default';
    protected $description = 'Check products in database (no demo products created)';

    public function handle()
    {
        $productCount = Product::count();
        $this->info("Current products in database: {$productCount}");

        // Only show count, don't create demo products
        if ($productCount == 0) {
            $this->warn("No products found in database. Please add products through admin panel or API.");
            $this->info("Dynamic products will be loaded from database when available.");
        } else {
            $this->info("Found {$productCount} products in database.");
            
            // Show some product info
            $featuredCount = Product::where('is_featured', true)->count();
            $saleCount = Product::whereNotNull('sale_price')->count();
            
            $this->info("Featured products: {$featuredCount}");
            $this->info("Sale products: {$saleCount}");
        }

        return 0;
    }
}