<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Str;

echo "Setting up sample product attributes...\n";

$products = Product::limit(5)->get();

foreach ($products as $product) {
    // Fix slug first
    if (empty($product->slug) || strpos($product->slug, ' ') !== false || strpos($product->slug, 'Md') !== false) {
        $newSlug = Str::slug($product->name);
        
        // Check if slug already exists
        $counter = 1;
        $originalSlug = $newSlug;
        while (Product::where('slug', $newSlug)->where('id', '!=', $product->id)->exists()) {
            $newSlug = $originalSlug . '-' . $counter++;
        }
        
        $product->slug = $newSlug;
        echo "Updated slug for '{$product->name}': {$newSlug}\n";
    }
    
    // Add sample attributes
    $sampleAttributes = [
        'Color' => ['Red', 'Blue', 'Black'],
        'Size' => ['S', 'M', 'L', 'XL']
    ];
    
    $product->attributes = json_encode($sampleAttributes);
    $product->save();
    
    echo "Added attributes to '{$product->name}'\n";
}

echo "Sample setup completed!\n";