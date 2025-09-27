<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Services\PackageLinkSharingService;
use App\Models\User;
use App\Models\Product;
use App\Models\UserActivePackage;
use App\Models\PackageLinkSharingSetting;

Route::get('/debug-products', function () {
    $product = Product::first();
    
    if (!$product) {
        return response()->json(['error' => 'No products found']);
    }
    
    return response()->json([
        'id' => $product->id,
        'name' => $product->name,
        'images' => $product->images,
        'image_url' => $product->image_url,
        'image_attribute' => $product->image,
        'has_variants' => isset($product->variants),
        'variants_count' => is_countable($product->variants) ? count($product->variants) : 'not countable'
    ]);
});

// Temporary debug route - Remove after fixing the issue
Route::get('/debug/storage', function () {
    $debug = [];
    
    // Check storage configuration
    $debug['storage_config'] = [
        'default_disk' => config('filesystems.default'),
        'public_disk_driver' => config('filesystems.disks.public.driver'),
        'public_disk_root' => config('filesystems.disks.public.root'),
        'public_disk_url' => config('filesystems.disks.public.url'),
    ];
    
    // Add direct access to storage files to bypass symlink issues
    Route::get('direct-storage/{path}', function($path) {
        $filePath = storage_path('app/public/' . $path);
        if (file_exists($filePath)) {
            return response()->file($filePath);
        }
        return response()->json(['error' => 'File not found'], 404);
    })->where('path', '.*');
    
    // Check directories
    $debug['directories'] = [
        'storage_app_public_exists' => is_dir(storage_path('app/public')),
        'public_storage_exists' => is_dir(public_path('storage')),
        'public_storage_is_link' => is_link(public_path('storage')),
        'storage_path' => storage_path('app/public'),
        'public_path' => public_path('storage'),
    ];
    
    // Check permissions
    if (is_dir(storage_path('app/public'))) {
        $debug['permissions']['storage_app_public'] = substr(sprintf('%o', fileperms(storage_path('app/public'))), -4);
    }
    if (is_dir(public_path('storage'))) {
        $debug['permissions']['public_storage'] = substr(sprintf('%o', fileperms(public_path('storage'))), -4);
    }
    
    // Check specific category images
    $categories = Category::whereNotNull('image')->take(5)->get();
    $debug['sample_images'] = [];
    
    foreach ($categories as $category) {
        $imagePath = $category->image;
        $debug['sample_images'][] = [
            'category_name' => $category->name,
            'image_path' => $imagePath,
            'storage_exists' => Storage::disk('public')->exists($imagePath),
            'public_exists' => file_exists(public_path('storage/' . $imagePath)),
            'full_storage_path' => storage_path('app/public/' . $imagePath),
            'full_public_path' => public_path('storage/' . $imagePath),
            'asset_url' => asset('storage/' . $imagePath),
        ];
    }
    
    // Check for common files
    $debug['common_checks'] = [
        'htaccess_exists' => file_exists(public_path('.htaccess')),
        'index_php_exists' => file_exists(public_path('index.php')),
        'storage_link_target' => is_link(public_path('storage')) ? readlink(public_path('storage')) : 'Not a symlink',
    ];
    
    return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
})->name('debug.storage');

// Test image access directly
Route::get('/debug/test-image/{category}', function (Category $category) {
    if (!$category->image) {
        return response()->json(['error' => 'No image for this category']);
    }
    
    $imagePath = $category->image;
    $storagePath = storage_path('app/public/' . $imagePath);
    $publicPath = public_path('storage/' . $imagePath);
    
    $result = [
        'category' => $category->name,
        'image_path' => $imagePath,
        'storage_file_exists' => file_exists($storagePath),
        'public_file_exists' => file_exists($publicPath),
        'storage_readable' => file_exists($storagePath) && is_readable($storagePath),
        'public_readable' => file_exists($publicPath) && is_readable($publicPath),
        'storage_size' => file_exists($storagePath) ? filesize($storagePath) : null,
        'public_size' => file_exists($publicPath) ? filesize($publicPath) : null,
        'asset_url' => asset('storage/' . $imagePath),
        'direct_url' => url('storage/' . $imagePath),
    ];
    
    // Try to serve the image directly from storage
    if (file_exists($storagePath) && is_readable($storagePath)) {
        $mimeType = mime_content_type($storagePath);
        return response()->file($storagePath, [
            'Content-Type' => $mimeType,
            'X-Debug-Info' => json_encode($result)
        ]);
    }
    
    return response()->json($result);
})->name('debug.test-image');

/*
|--------------------------------------------------------------------------
| Package Link Sharing Debug Routes
|--------------------------------------------------------------------------
*/

Route::get('/debug/package-system-test', function () {
    $output = [];
    
    // 1. Check Package Settings
    $output[] = "=== Package Link Sharing Settings ===";
    $settings = PackageLinkSharingSetting::all();
    foreach ($settings as $setting) {
        $output[] = "Package: {$setting->package_name}";
        $output[] = "  Daily Share Limit: {$setting->daily_share_limit}";
        $output[] = "  Click Reward: {$setting->click_reward_amount} TK";
        $output[] = "  Daily Earning Limit: {$setting->daily_earning_limit} TK";
        $output[] = "  Active: " . ($setting->is_active ? 'Yes' : 'No');
        $output[] = "---";
    }
    
    // 2. Check Users with Active Packages
    $output[] = "\n=== Users with Active Packages ===";
    $activePackages = UserActivePackage::with(['user', 'plan'])->where('is_active', true)->get();
    foreach ($activePackages as $package) {
        $output[] = "User: {$package->user->name} ({$package->user->username})";
        $output[] = "  Package Amount: {$package->amount_invested} TK";
        $output[] = "  Package Tier: {$package->package_tier}";
        $output[] = "  Plan: {$package->plan->name}";
        $output[] = "---";
    }
    
    // 3. Test Package Detection Service
    $output[] = "\n=== Package Detection Test ===";
    $linkSharingService = new PackageLinkSharingService();
    $testUser = User::whereHas('activePackages', function($q) {
        $q->where('is_active', true);
    })->first();
    
    if ($testUser) {
        $output[] = "Test User: {$testUser->name} ({$testUser->username})";
        
        // Use reflection to test private method
        $reflection = new \ReflectionClass($linkSharingService);
        $method = $reflection->getMethod('getUserPackageSettings');
        $method->setAccessible(true);
        $packageSettings = $method->invoke($linkSharingService, $testUser);
        
        if ($packageSettings) {
            $output[] = "  Detected Package: {$packageSettings->package_name}";
            $output[] = "  Daily Share Limit: {$packageSettings->daily_share_limit}";
            $output[] = "  Click Reward: {$packageSettings->click_reward_amount} TK";
        } else {
            $output[] = "  No package settings found!";
        }
    } else {
        $output[] = "No users with active packages found!";
    }
    
    // 4. Test Product for Sharing
    $output[] = "\n=== Available Products for Sharing ===";
    $products = Product::limit(3)->get();
    foreach ($products as $product) {
        $output[] = "Product: {$product->name} (Slug: {$product->slug})";
    }
    
    return '<pre>' . implode("\n", $output) . '</pre>';
});

// Authentication debug routes
Route::get('/debug-auth', function () {
    $data = [
        'is_authenticated' => Auth::check(),
        'user_id' => Auth::check() ? Auth::user()->id : null,
        'user_role' => Auth::check() ? Auth::user()->role : null,
        'user_email' => Auth::check() ? Auth::user()->email : null,
        'session_id' => session()->getId(),
        'current_url' => request()->fullUrl(),
        'route_name' => request()->route() ? request()->route()->getName() : 'no_route',
    ];
    
    return response()->json($data);
});

Route::get('/debug-member', function () {
    if (!Auth::check()) {
        return response()->json(['error' => 'Not authenticated', 'redirect_to' => 'login']);
    }
    
    $user = Auth::user();
    
    if ($user->role !== 'member' && $user->role !== 'affiliate') {
        return response()->json(['error' => 'Wrong role', 'current_role' => $user->role]);
    }
    
    return response()->json([
        'success' => true,
        'user' => $user->toArray(),
        'message' => 'Member access OK'
    ]);
})->middleware(['auth', 'role.session:customer|affiliate']);

Route::get('/debug/test-link-sharing/{userId}/{productSlug}', function ($userId, $productSlug) {
    $linkSharingService = new PackageLinkSharingService();
    $result = $linkSharingService->shareProductLink($userId, $productSlug, 'web');
    
    return response()->json($result, $result['success'] ? 200 : 400);
});
