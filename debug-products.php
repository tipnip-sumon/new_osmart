<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

// Boot Laravel
$app = new Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Load environment
$app->loadEnvironmentFrom('.env');

// Boot the application
$app->boot();

// Get a sample product to debug
$product = Product::with(['category', 'brand', 'vendor'])->first();

if ($product) {
    echo "Product ID: {$product->id}\n";
    echo "Product Name: {$product->name}\n";
    echo "Images (raw): " . print_r($product->images, true) . "\n";
    echo "Image URL Accessor: {$product->image_url}\n";
    echo "Image Attribute: " . print_r($product->image, true) . "\n";
    echo "Variants: " . print_r($product->variants, true) . "\n";
} else {
    echo "No products found in database\n";
}