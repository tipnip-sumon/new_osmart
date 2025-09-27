<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CollectionsController;
use App\Http\Controllers\CurrencyTestController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\LogoutController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Test Routes
Route::get('/cart-modal-test', function () {
    return view('cart-modal-test');
})->name('cart.modal.test');

// Marketing Plan Routes - Public Access (No Authentication Required)
Route::get('/marketing-plan', function() {
    return response()->file(base_path('marketing-plan.html'));
})->name('marketing.plan');

Route::get('/marketing-plan.html', function() {
    return response()->file(base_path('marketing-plan.html'));
});

// Debug route for KYC authentication issue
Route::get('/debug-current-user', function () {
    $kycData = null;
    if (Auth::check()) {
        $user = Auth::user();
        $kyc = App\Models\MemberKycVerification::forUser($user->id)->first();
        $kycData = $kyc ? [
            'kyc_id' => $kyc->id,
            'full_name' => $kyc->full_name,
            'user_id' => $kyc->user_id,
            'has_documents' => !empty($kyc->document_front_image),
        ] : null;
    }
    
    return response()->json([
        'authenticated' => Auth::check(),
        'user_id' => Auth::id(),
        'user_data' => Auth::check() ? [
            'id' => Auth::user()->id,
            'username' => Auth::user()->username,
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'role' => Auth::user()->role,
        ] : null,
        'session_id' => session()->getId(),
        'kyc_data' => $kycData,
    ]);
})->middleware('auth');

// Include debug routes for image troubleshooting
require __DIR__.'/debug.php';

// Include debug routes for image troubleshooting
require __DIR__.'/debug.php';

// Direct storage access route to bypass symlink issues
Route::get('direct-storage/{path}', function($path) {
    $filePath = storage_path('app/public/' . $path);
    if (file_exists($filePath)) {
        return response()->file($filePath);
    }
    return response()->json(['error' => 'File not found'], 404);
})->where('path', '.*');

// Storage debugging routes (commented out - controller missing)
// Route::get('/storage-debug/test', [\App\Http\Controllers\StorageDebugController::class, 'test']);
// Route::get('/storage-debug/fix', [\App\Http\Controllers\StorageDebugController::class, 'fixSymlink']);
// Route::get('/storage-debug/view/{path}', [\App\Http\Controllers\StorageDebugController::class, 'viewFile'])->where('path', '.*');

// Test routes for debugging invoice issues
Route::get('/test-pdf/{orderId}', function($orderId) {
    try {
        $order = App\Models\Order::with(['customer', 'items.product', 'vendor'])->findOrFail($orderId);
        
        $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('admin.invoices.invoice-pdf', compact('order'))
            ->setPaper('a4', 'portrait');
            
        return $pdf->stream('test-invoice.pdf');
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Debug route to check user authentication and affiliate tracking
Route::get('/debug-affiliate-tracking', function() {
    $currentUserId = \Illuminate\Support\Facades\Auth::id();
    $currentUser = \Illuminate\Support\Facades\Auth::user();
    
    $debugInfo = [
        'authentication_status' => [
            'is_authenticated' => \Illuminate\Support\Facades\Auth::check(),
            'user_id' => $currentUserId,
            'username' => $currentUser ? $currentUser->username : null,
            'email' => $currentUser ? $currentUser->email : null,
        ],
        'request_info' => [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'current_url' => request()->fullUrl(),
            'referrer' => request()->header('referer') ?: request()->server('HTTP_REFERER') ?: 'No referrer',
        ],
        'affiliate_params' => [
            'aff' => request()->get('aff'),
            'ref' => request()->get('ref'),
            'utm_source' => request()->get('utm_source'),
            'utm_medium' => request()->get('utm_medium'),
            'utm_campaign' => request()->get('utm_campaign'),
            'utm_content' => request()->get('utm_content'),
        ],
        'session_info' => [
            'affiliate_info' => session('affiliate_info'),
            'session_id' => session()->getId(),
        ],
        'cookie_info' => [
            'cookie_exists' => request()->hasCookie(config('affiliate.cookie_name', 'affiliate_tracking')),
            'cookie_data' => class_exists('App\Helpers\AffiliateTracker') 
                ? \App\Helpers\AffiliateTracker::getAffiliateInfoFromCookie() 
                : null,
        ],
        'attribution_info' => class_exists('App\Helpers\AffiliateTracker') 
            ? \App\Helpers\AffiliateTracker::getAttributionInfo() 
            : null,
    ];
    
    // Check recent affiliate clicks for this user/IP
    if ($currentUserId) {
        $recentClicks = DB::table('affiliate_clicks')
            ->where('user_id', $currentUserId)
            ->orderBy('clicked_at', 'desc')
            ->limit(5)
            ->get(['affiliate_id', 'product_id', 'clicked_at', 'ip_address']);
    } else {
        $recentClicks = DB::table('affiliate_clicks')
            ->where('ip_address', request()->ip())
            ->whereNull('user_id')
            ->orderBy('clicked_at', 'desc')
            ->limit(5)
            ->get(['affiliate_id', 'product_id', 'clicked_at', 'user_agent']);
    }
    
    $debugInfo['recent_clicks'] = $recentClicks;
    
    return response()->json($debugInfo, 200, [], JSON_PRETTY_PRINT);
});

// Test route specifically for your affiliate link
Route::get('/test-affiliate-link/{product}', function($product) {
    $affiliateId = request()->get('aff');
    $referralCode = request()->get('ref');
    $currentUserId = \Illuminate\Support\Facades\Auth::id();
    
    // Find the product
    $productModel = \App\Models\Product::where('slug', $product)->first();
    
    if (!$productModel) {
        return response()->json(['error' => 'Product not found'], 404);
    }
    
    // Verify affiliate user exists
    $affiliate = \App\Models\User::where('id', $affiliateId)
        ->where('username', $referralCode)
        ->first();
        
    $testResult = [
        'product_info' => [
            'id' => $productModel->id,
            'name' => $productModel->name,
            'slug' => $productModel->slug,
        ],
        'affiliate_info' => [
            'affiliate_id' => $affiliateId,
            'referral_code' => $referralCode,
            'affiliate_exists' => $affiliate ? true : false,
            'affiliate_username' => $affiliate ? $affiliate->username : null,
        ],
        'current_user' => [
            'is_authenticated' => \Illuminate\Support\Facades\Auth::check(),
            'user_id' => $currentUserId,
            'username' => \Illuminate\Support\Facades\Auth::user() ? \Illuminate\Support\Facades\Auth::user()->username : null,
        ],
        'tracking_logic' => [
            'would_track' => $affiliate && $productModel,
            'tracking_method' => $currentUserId ? 'user_based' : 'ip_based',
            'duplicate_check' => $currentUserId ? 'user_id + product_id + affiliate_id' : 'ip + user_agent + product_id + affiliate_id',
        ],
    ];
    
    // Check for existing clicks
    if ($affiliate && $productModel) {
        if ($currentUserId) {
            $existingClick = DB::table('affiliate_clicks')
                ->where('affiliate_id', $affiliateId)
                ->where('product_id', $productModel->id)
                ->where('user_id', $currentUserId)
                ->where('clicked_at', '>=', now()->subHours(24))
                ->first();
        } else {
            $existingClick = DB::table('affiliate_clicks')
                ->where('affiliate_id', $affiliateId)
                ->where('product_id', $productModel->id)
                ->where('ip_address', request()->ip())
                ->where('user_agent', request()->userAgent())
                ->whereNull('user_id')
                ->where('clicked_at', '>=', now()->subHours(24))
                ->first();
        }
        
        $testResult['duplicate_check_result'] = [
            'duplicate_found' => $existingClick ? true : false,
            'would_prevent_tracking' => $existingClick ? true : false,
            'last_click_time' => $existingClick ? $existingClick->clicked_at : null,
        ];
    }
    
    return response()->json($testResult, 200, [], JSON_PRETTY_PRINT);
});

Route::get('/test-email/{orderId}', function($orderId) {
    try {
        $order = App\Models\Order::with(['customer', 'items.product', 'vendor'])->findOrFail($orderId);
        
        return view('emails.invoice', [
            'order' => $order,
            'customMessage' => 'This is a test email'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Currency testing routes
Route::get('/currency-test', [CurrencyTestController::class, 'index'])->name('currency.test');
Route::get('/api/currency/test-formatting', [CurrencyTestController::class, 'testFormatting'])->name('currency.test.formatting');
Route::get('/api/currency/test-conversion', [CurrencyTestController::class, 'testConversion'])->name('currency.test.conversion');
Route::post('/api/currency/test-parsing', [CurrencyTestController::class, 'testInputParsing'])->name('currency.test.parsing');

// Invoice routes
Route::get('/invoice/{order}', [InvoiceController::class, 'show'])->name('invoice.show');
Route::get('/invoice/{order}/download', [InvoiceController::class, 'download'])->name('invoice.download');
Route::get('/invoice/{order}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoice.pdf');
Route::get('/invoice/{order}/view-pdf', [InvoiceController::class, 'viewPdf'])->name('invoice.view-pdf');
Route::get('/invoice/{order}/responsive', [InvoiceController::class, 'showResponsive'])->name('invoice.responsive');

// Placeholder routes for the template links
Route::get('/search', function () {
    return view('search');
})->name('search');

Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');

Route::get('/categories/{slug}', [App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');

// API route for categories
Route::get('/api/categories', [App\Http\Controllers\CategoryController::class, 'getCategoriesApi'])->name('api.categories');

Route::get('/shop', [App\Http\Controllers\ShopController::class, 'grid'])->name('shop.grid');
Route::get('/shop/list', [App\Http\Controllers\ShopController::class, 'list'])->name('shop.list');

// Add missing shop routes for Ecomus theme
Route::get('/shop/index', [App\Http\Controllers\ShopController::class, 'grid'])->name('shop.index');

// Missing collections routes
Route::get('/collections', [CollectionsController::class, 'index'])->name('collections.index');

// Missing pages routes
Route::get('/pages/returns', function() { return view('pages.returns'); })->name('pages.returns');
Route::get('/pages/shipping', function() { return view('pages.shipping'); })->name('pages.shipping');
Route::get('/pages/faq', function() { return view('pages.faq'); })->name('pages.faq');
Route::get('/pages/store', function() { return view('pages.store'); })->name('pages.store');
Route::get('/pages/terms-conditions', function() { 
    $settings = \App\Models\GeneralSetting::getSettings();
    return view('pages.terms-conditions', compact('settings')); 
})->name('page.terms-conditions');
Route::get('/pages/privacy-policy', function() { 
    $settings = \App\Models\GeneralSetting::getSettings();
    return view('pages.privacy-policy', compact('settings')); 
})->name('page.privacy-policy');

// Missing brand and search routes
Route::get('/brands', function() { return redirect('/shop'); })->name('brands.index');
Route::get('/compare', function() { return view('compare.index'); })->name('compare.index');
Route::get('/search', [App\Http\Controllers\ShopController::class, 'grid'])->name('search');

// Newsletter subscription route
Route::post('/newsletter/subscribe', function(Request $request) {
    // Add newsletter subscription logic here
    return response()->json(['success' => true, 'message' => 'Subscribed successfully!']);
})->name('newsletter.subscribe');

// AJAX routes for shop
Route::get('/api/shop/categories', [App\Http\Controllers\ShopController::class, 'getCategories'])->name('shop.categories');
Route::get('/api/shop/brands', [App\Http\Controllers\ShopController::class, 'getBrands'])->name('shop.brands');
Route::get('/api/shop/search', [App\Http\Controllers\ShopController::class, 'search'])->name('shop.search');

Route::get('/products/featured', function () {
    $featuredProducts = \App\Models\Product::where('is_featured', true)
        ->where('status', 'active')
        ->with('brand', 'category')
        ->orderBy('sort_order', 'asc')
        ->orderBy('created_at', 'desc')
        ->paginate(20);
    
    return view('products.featured', compact('featuredProducts'));
})->name('products.featured');

Route::get('/flash-sale', [App\Http\Controllers\FlashSaleController::class, 'index'])->name('flash-sale');

Route::get('/products/bestsellers', function () {
    $bestsellerProducts = \App\Models\Product::where('status', 'active')
        ->with('brand', 'category')
        ->withCount(['orderItems as sales_count' => function($query) {
            $query->whereHas('order', function($q) {
                $q->where('status', 'completed');
            });
        }])
        ->orderBy('sales_count', 'desc')
        ->orderBy('created_at', 'desc')
        ->paginate(20);
    
    return view('products.bestsellers', compact('bestsellerProducts'));
})->name('products.bestsellers');

Route::get('/products/{identifier}', function ($identifier) {
    // Handle affiliate tracking
    $affiliateId = request()->get('aff');
    $referralCode = request()->get('ref');
    
    // Try to get product by slug first, then by ID if it's numeric
    if (is_numeric($identifier)) {
        $product = \App\Models\Product::where('id', $identifier)
            ->where('status', 'active')
            ->with(['category', 'vendor'])
            ->first();
    } else {
        $product = \App\Models\Product::where('slug', $identifier)
            ->where('status', 'active')
            ->with(['category', 'vendor'])
            ->first();
    }
    
    if (!$product) {
        abort(404, 'Product not found');
    }
    
    // Track affiliate click if parameters exist
    if ($affiliateId && $referralCode) {
        try {
            // Verify affiliate user exists
            $affiliate = \App\Models\User::where('id', $affiliateId)
                ->where('username', $referralCode)
                ->first();
                
            if ($affiliate) {
                $currentUserId = \Illuminate\Support\Facades\Auth::id();
                $duplicateFound = false;
                
                // Enhanced duplicate check for authenticated users
                if ($currentUserId) {
                    // For authenticated users, check by user_id + product + affiliate
                    $existingClick = DB::table('affiliate_clicks')
                        ->where('affiliate_id', $affiliateId)
                        ->where('product_id', $product->id)
                        ->where('user_id', $currentUserId)
                        ->where('clicked_at', '>=', now()->subHours(24))
                        ->first();
                        
                    $duplicateFound = (bool) $existingClick;
                } else {
                    // For guest users, check by IP + user agent + product + affiliate
                    $existingClick = DB::table('affiliate_clicks')
                        ->where('affiliate_id', $affiliateId)
                        ->where('product_id', $product->id)
                        ->where('ip_address', request()->ip())
                        ->where('user_agent', request()->userAgent())
                        ->whereNull('user_id') // Only check guest clicks
                        ->where('clicked_at', '>=', now()->subHours(24))
                        ->first();
                        
                    $duplicateFound = (bool) $existingClick;
                }
                
                // Only track if no duplicate click found
                if (!$duplicateFound) {
                    // Process package-based link sharing reward (this will handle click recording too)
                    if (class_exists('App\Services\PackageLinkSharingService')) {
                        try {
                            // Get IP address with fallbacks for different server configurations
                            $ipAddress = request()->ip();
                            
                            // Additional IP detection for various proxy/load balancer setups
                            if ($ipAddress === '127.0.0.1' || $ipAddress === '::1') {
                                $ipAddress = request()->header('X-Forwarded-For') 
                                    ?: request()->header('X-Real-IP') 
                                    ?: request()->header('HTTP_CLIENT_IP')
                                    ?: request()->header('HTTP_X_FORWARDED_FOR')
                                    ?: request()->ip();
                                    
                                // If multiple IPs in X-Forwarded-For, get the first one
                                if (strpos($ipAddress, ',') !== false) {
                                    $ipAddress = trim(explode(',', $ipAddress)[0]);
                                }
                            }
                            
                            // Get or generate unique clicker ID using cookie
                            $cookieId = request()->cookie('clicker_tracking_id');
                            if (!$cookieId) {
                                $cookieId = 'clk_' . time() . '_' . uniqid();
                                // Set cookie for 365 days to track unique users
                                cookie()->queue('clicker_tracking_id', $cookieId, 365 * 24 * 60);
                            }
                            
                            $clickerInfo = [
                                'user_id' => $currentUserId,
                                'ip_address' => $ipAddress,
                                'cookie_id' => $cookieId,
                                'user_agent' => request()->userAgent(),
                                'referrer' => request()->header('referer'),
                                'shared_url' => request()->fullUrl()
                            ];
                            
                            // Debug logging for cookie tracking
                            Log::info('Affiliate link click detected with cookie tracking', [
                                'affiliate_id' => $affiliateId,
                                'product_slug' => $product->slug,
                                'detected_ip' => $ipAddress,
                                'cookie_id' => $cookieId,
                                'cookie_was_new' => !request()->cookie('clicker_tracking_id'),
                                'user_agent' => substr(request()->userAgent() ?: '', 0, 100),
                                'referrer' => request()->header('referer')
                            ]);
                            
                            $linkSharingService = new \App\Services\PackageLinkSharingService();
                            $rewardResult = $linkSharingService->processAffiliateClick($affiliateId, $product->slug, $clickerInfo);
                            
                            if ($rewardResult['success']) {
                                Log::info('Package link sharing reward processed', [
                                    'affiliate_id' => $affiliateId,
                                    'earning_amount' => $rewardResult['earning_amount'] ?? 0,
                                    'is_unique' => $rewardResult['is_unique'] ?? false,
                                    'message' => $rewardResult['message']
                                ]);
                            } else {
                                Log::warning('Package link sharing failed', [
                                    'affiliate_id' => $affiliateId,
                                    'message' => $rewardResult['message']
                                ]);
                            }
                        } catch (\Exception $e) {
                            Log::warning('Package link sharing reward failed: ' . $e->getMessage());
                        }
                    }
                    
                    Log::info('Affiliate click processing completed', [
                        'affiliate_id' => $affiliateId,
                        'product_id' => $product->id,
                        'user_id' => $currentUserId,
                        'ip' => request()->ip(),
                        'user_type' => $currentUserId ? 'authenticated' : 'guest'
                    ]);
                } else {
                    Log::info('Duplicate affiliate click prevented', [
                        'affiliate_id' => $affiliateId,
                        'product_id' => $product->id,
                        'user_id' => $currentUserId,
                        'ip' => request()->ip(),
                        'user_type' => $currentUserId ? 'authenticated' : 'guest',
                        'duplicate_check' => $currentUserId ? 'user_based' : 'ip_based'
                    ]);
                }
                
                // Enhanced: Use AffiliateTracker helper for consistent tracking
                if (class_exists('App\Helpers\AffiliateTracker')) {
                    \App\Helpers\AffiliateTracker::trackClick($affiliateId, $referralCode, $product->id);
                } else {
                    // Fallback to original method
                    session([
                        'affiliate_info' => [
                            'affiliate_id' => $affiliateId,
                            'referral_code' => $referralCode,
                            'product_id' => $product->id,
                            'tracked_at' => now()
                        ]
                    ]);
                    
                    // Enhanced: Store affiliate info in persistent cookie for longer attribution window
                    $attributionDays = config('affiliate.attribution_days', 30); // Default 30 days
                    $cookieData = [
                        'affiliate_id' => $affiliateId,
                        'referral_code' => $referralCode,
                        'product_id' => $product->id,
                        'tracked_at' => now()->timestamp,
                        'expires_at' => now()->addDays($attributionDays)->timestamp
                    ];
                    
                    // Set encrypted cookie for longer attribution period
                    cookie()->queue('affiliate_tracking', encrypt(json_encode($cookieData)), $attributionDays * 24 * 60);
                    
                    Log::info('Affiliate tracking enhanced', [
                        'affiliate_id' => $affiliateId,
                        'product_id' => $product->id,
                        'attribution_days' => $attributionDays,
                        'session_stored' => true,
                        'cookie_stored' => true
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Log error but don't break the page
            Log::warning('Affiliate tracking failed: ' . $e->getMessage());
        }
    }
    
    // Get related products from same category
    $relatedProducts = \App\Models\Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->where('status', 'active')
        ->with(['category'])
        ->limit(4)
        ->get();
    
    return view('products.show-ecomus', compact('identifier', 'product', 'relatedProducts'));
})->name('products.show');

// Review routes
Route::get('/products/{product}/reviews', [App\Http\Controllers\ReviewController::class, 'index'])->name('products.reviews');
Route::post('/products/{product}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('products.reviews.store');
Route::post('/reviews/{review}/helpful', [App\Http\Controllers\ReviewController::class, 'markHelpful'])->name('reviews.helpful');

Route::get('/collections', [CollectionsController::class, 'index'])->name('collections.index');

// Summer Collection specific route
Route::get('/collections/summer', [CollectionsController::class, 'summer'])->name('collections.summer');

Route::get('/collections/{collection:slug}', [CollectionsController::class, 'show'])->name('collections.show');

// AJAX route for getting products by collection
Route::get('/api/collections/{collection}/products', [CollectionsController::class, 'getProducts'])->name('collections.products');

Route::get('/products', function () {
    $products = \App\Models\Product::limit(5)->get();
    return view('products.index', compact('products'));
})->name('products.index');

// Cart routes
Route::get('/cart', function () {
    $cart = session()->get('cart', []);
    $cartItems = [];
    $subtotal = 0;
    
    foreach ($cart as $productId => $item) {
        // Debug: Log the cart item data
        Log::info('Cart Item Debug', [
            'product_id' => $productId,
            'item_name' => $item['name'] ?? 'N/A',
            'item_image' => $item['image'] ?? 'NULL',
            'image_type' => gettype($item['image'] ?? null),
            'image_structure' => is_array($item['image'] ?? null) ? $item['image'] : 'Not array'
        ]);
        
        $cartItems[] = [
            'id' => $productId,
            'name' => $item['name'],
            'price' => $item['price'],
            'quantity' => $item['quantity'],
            'image' => $item['image'] ?? null,
            'total' => $item['price'] * $item['quantity']
        ];
        $subtotal += $item['price'] * $item['quantity'];
    }
    
    // Debug: Log the final cart items structure
    Log::info('Final Cart Items', [
        'cart_items_count' => count($cartItems),
        'cart_items' => $cartItems
    ]);
    
    // Check if it's an AJAX request
    if (request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
        return response()->json([
            'success' => true,
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'count' => count($cartItems)
        ]);
    }
    
    return view('cart.index', compact('cartItems', 'subtotal'));
})->name('cart.index');

// Cart management routes
Route::post('/cart/add', function (Request $request) {
    // Simple cart add logic - in real app this would use a proper cart service
    $productId = $request->input('product_id');
    $quantity = $request->input('quantity', 1);
    
    // Add to session cart or database
    $cart = session()->get('cart', []);
    
    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] += $quantity;
    } else {
        $product = \App\Models\Product::find($productId);
        if ($product) {
            // Debug: Log product image structure
            Log::info('Product Image Debug', [
                'product_id' => $productId,
                'product_name' => $product->name,
                'images_raw' => $product->images,
                'images_type' => gettype($product->images),
                'images_empty' => empty($product->images)
            ]);
            
            // Extract image URL from the images array - improved extraction
            $imageUrl = null;
            if (!empty($product->images)) {
                if (is_array($product->images)) {
                    $firstImage = $product->images[0];
                    if (is_array($firstImage)) {
                        // Handle complex structure
                        if (isset($firstImage['sizes']['medium']['storage_url'])) {
                            $imageUrl = $firstImage['sizes']['medium']['storage_url'];
                        } elseif (isset($firstImage['sizes']['small']['storage_url'])) {
                            $imageUrl = $firstImage['sizes']['small']['storage_url'];
                        } elseif (isset($firstImage['sizes']['large']['storage_url'])) {
                            $imageUrl = $firstImage['sizes']['large']['storage_url'];
                        } elseif (isset($firstImage['urls'])) {
                            $imageUrl = $firstImage['urls']['medium'] ?? 
                                       $firstImage['urls']['small'] ?? 
                                       $firstImage['urls']['thumbnail'] ?? 
                                       null;
                        } elseif (isset($firstImage['url'])) {
                            $imageUrl = $firstImage['url'];
                        } elseif (isset($firstImage['path'])) {
                            $imageUrl = 'storage/' . $firstImage['path'];
                        }
                    } elseif (is_string($firstImage)) {
                        $imageUrl = $firstImage;
                    }
                } elseif (is_string($product->images)) {
                    $imageUrl = $product->images;
                }
            }
            
            // Debug: Log extracted image URL
            Log::info('Extracted Image URL', [
                'product_id' => $productId,
                'extracted_url' => $imageUrl
            ]);
            
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $product->sale_price ?? $product->price,
                'quantity' => $quantity,
                'image' => $imageUrl
            ];
        }
    }
    
    session()->put('cart', $cart);
    
    // Calculate cart count for response
    $cartCount = array_sum(array_column($cart, 'quantity'));
    
    if ($request->expectsJson()) {
        return response()->json([
            'success' => true, 
            'message' => 'Product added to cart',
            'cartCount' => $cartCount
        ]);
    }
    
    return response()->json(['success' => true, 'message' => 'Product added to cart']);
})->name('cart.add');

Route::get('/cart/count', function () {
    $cart = session()->get('cart', []);
    $count = array_sum(array_column($cart, 'quantity'));
    return response()->json(['count' => $count]);
})->name('cart.count');

Route::get('/cart/items', function () {
    $cart = session()->get('cart', []);
    $cartItems = [];
    $subtotal = 0;
    
    foreach ($cart as $productId => $item) {
        $cartItems[] = [
            'id' => $productId,
            'name' => $item['name'],
            'price' => $item['price'],
            'quantity' => $item['quantity'],
            'image' => $item['image'] ?? null,
            'total' => $item['price'] * $item['quantity']
        ];
        $subtotal += $item['price'] * $item['quantity'];
    }
    
    return response()->json([
        'success' => true,
        'items' => $cartItems,
        'subtotal' => $subtotal,
        'count' => array_sum(array_column($cart, 'quantity'))
    ]);
})->name('cart.items');

Route::post('/cart/update', function (Request $request) {
    $productId = $request->input('product_id');
    $quantity = $request->input('quantity');
    
    $cart = session()->get('cart', []);
    
    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] = $quantity;
        $cart[$productId]['total'] = $cart[$productId]['price'] * $quantity;
        
        session()->put('cart', $cart);
        
        return response()->json([
            'success' => true, 
            'message' => 'Cart updated',
            'item_total' => $cart[$productId]['total'],
            'cart_count' => array_sum(array_column($cart, 'quantity'))
        ]);
    }
    
    return response()->json(['success' => false, 'message' => 'Product not found in cart']);
})->name('cart.update');

Route::post('/cart/remove', function (Request $request) {
    $productId = $request->input('product_id');
    
    $cart = session()->get('cart', []);
    
    if (isset($cart[$productId])) {
        unset($cart[$productId]);
        session()->put('cart', $cart);
        
        return response()->json([
            'success' => true, 
            'message' => 'Product removed from cart',
            'cart_count' => array_sum(array_column($cart, 'quantity'))
        ]);
    }
    
    return response()->json(['success' => false, 'message' => 'Product not found in cart']);
})->name('cart.remove');

Route::post('/cart/clear', function () {
    session()->forget('cart');
    return response()->json([
        'success' => true, 
        'message' => 'Cart cleared',
        'cart_count' => 0
    ]);
})->name('cart.clear');

// Temporary checkout route
Route::get('/checkout', function () {
    $cart = session()->get('cart', []);
    $cartItems = [];
    $subtotal = 0;
    
    foreach ($cart as $productId => $item) {
        $cartItems[] = [
            'id' => $productId,
            'name' => $item['name'],
            'price' => $item['price'],
            'quantity' => $item['quantity'],
            'image' => $item['image'] ?? null,
            'total' => $item['price'] * $item['quantity']
        ];
        $subtotal += $item['price'] * $item['quantity'];
    }
    
    if (empty($cartItems)) {
        return redirect()->route('cart.index')->with('error', 'Your cart is empty');
    }
    
    return view('checkout.index', compact('cartItems', 'subtotal'));
})->name('checkout.index');

// Tax calculation route for dynamic checkout
Route::post('/checkout/calculate-tax', [CheckoutController::class, 'calculateTax'])->name('checkout.calculate-tax');

// Coupon routes for checkout
Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.apply-coupon');
Route::post('/checkout/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('checkout.remove-coupon');
Route::get('/checkout/available-coupons', [CheckoutController::class, 'getAvailableCoupons'])->name('checkout.available-coupons');

// Username availability check
Route::post('/check-username', [CheckoutController::class, 'checkUsername'])->name('check.username');

// Test checkout page
Route::get('/test-checkout', function () {
    return view('test-checkout');
})->name('test-checkout');

// Debug order page
Route::get('/debug-order', function () {
    return view('debug-order');
})->name('debug-order');

// Order routes
Route::post('/orders', [CheckoutController::class, 'processOrder'])->name('orders.store');

Route::get('/orders/success/{id?}', [CheckoutController::class, 'orderSuccess'])->name('orders.success');

// Wishlist routes
Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
Route::post('/wishlist/toggle', function (Request $request) {
    // Simple wishlist toggle for demo - in production use proper controller
    $productId = $request->input('product_id');
    $wishlist = session()->get('wishlist', []);
    
    if (in_array($productId, $wishlist)) {
        $wishlist = array_filter($wishlist, function($id) use ($productId) {
            return $id != $productId;
        });
        $action = 'removed';
        $message = 'Product removed from wishlist';
    } else {
        $wishlist[] = $productId;
        $action = 'added';
        $message = 'Product added to wishlist';
    }
    
    session()->put('wishlist', array_values($wishlist));
    
    return response()->json([
        'success' => true,
        'action' => $action,
        'message' => $message,
        'count' => count($wishlist)
    ]);
})->name('wishlist.toggle');
Route::post('/wishlist/toggle/{id}', [WishlistController::class, 'toggle'])->name('wishlist.toggle.id');
Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');
Route::post('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
Route::get('/wishlist/count', function () {
    $wishlist = session()->get('wishlist', []);
    return response()->json(['count' => count($wishlist)]);
})->name('wishlist.count');

Route::get('/cart/add/{id}', function ($id) {
    return redirect()->back()->with('success', 'Product added to cart');
})->name('cart.add.get');

// Test shipping calculation route
Route::get('/test-shipping', function () {
    $products = \App\Models\Product::take(3)->get();
    return view('test-shipping', compact('products'));
})->name('test.shipping');

// Cart test utility
Route::get('/cart-test', function () {
    return view('cart-test');
});

// Simple cart test page
Route::get('/test-cart-ui', function () {
    return view('cart-test');
})->name('test.cart');

// Add test items to cart
Route::get('/add-test-items', function () {
    return response()->json([
        'success' => true,
        'message' => 'Test items ready to add to cart',
        'items' => [
            [
                'id' => 1,
                'name' => 'Smartphone XYZ',
                'price' => 599.99,
                'image' => '/assets/img/product/product-1.jpg'
            ],
            [
                'id' => 2,
                'name' => 'Laptop ABC',
                'price' => 899.99,
                'image' => '/assets/img/product/product-2.jpg'
            ],
            [
                'id' => 3,
                'name' => 'Headphones DEF',
                'price' => 149.99,
                'image' => '/assets/img/product/product-3.jpg'
            ]
        ]
    ]);
})->name('add.test.items');

// Wishlist routes
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

Route::get('/wishlist/grid', [WishlistController::class, 'index'])->name('wishlist.grid');

Route::get('/wishlist/list', [WishlistController::class, 'list'])->name('wishlist.list');

Route::get('/wishlist/toggle/{id}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

// User routes (require authentication middleware later)
Route::get('/profile', function () {
    $user = Auth::user();
    return view('user.profile', compact('user'));
})->name('profile.show')->middleware('auth');

// Notification routes
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clear-all');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
});

Route::get('/orders', [CheckoutController::class, 'orders'])->name('orders.index');

Route::get('/settings', function () {
    return view('settings');
})->name('settings');

// Role upgrade routes (authenticated users only)
Route::middleware('auth')->group(function () {
    Route::post('/settings/become-affiliate', [App\Http\Controllers\SettingsController::class, 'becomeAffiliate'])->name('settings.become-affiliate');
    Route::post('/settings/vendor-application', [App\Http\Controllers\SettingsController::class, 'submitVendorApplication'])->name('settings.vendor-application');
});

// Static pages
Route::get('/about', function () {
    return view('pages.about-ecomus');
})->name('pages.about');

Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])->name('contact.show');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

// Terms and Privacy pages for registration
Route::get('/terms', function () {
    return view('pages.terms');
})->name('pages.terms');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('pages.privacy');

// Additional utility pages
Route::get('/size-guide', function () {
    return view('pages.size-guide');
})->name('pages.size-guide');

Route::get('/warranty', function () {
    return view('pages.warranty');
})->name('pages.warranty');

Route::get('/delivery-info', function () {
    return view('pages.delivery-info');
})->name('pages.delivery-info');

Route::get('/customer-support', function () {
    return view('pages.customer-support');
})->name('pages.customer-support');

Route::get('/brand-story', function () {
    return view('pages.brand-story');
})->name('pages.brand-story');

Route::get('/careers', function () {
    return view('pages.careers');
})->name('pages.careers');

// Auth routes - using Laravel Breeze or similar
require __DIR__.'/auth.php';

// Alternative basic auth routes if auth.php doesn't exist
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Additional logout routes (not conflicting with main logout)
Route::post('/ajax-logout', [LogoutController::class, 'ajaxLogout'])->name('ajax.logout')->middleware('auth');
Route::post('/logout-redirect/{redirectTo?}', [LogoutController::class, 'logoutAndRedirect'])->name('logout.redirect')->middleware('auth');
Route::post('/force-logout-all', [LogoutController::class, 'forceLogoutAllSessions'])->name('logout.all')->middleware('auth');

// Password Reset Routes
Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.reset.update');



// User/Member Routes (Protected)
Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/genealogy', [UserController::class, 'genealogy'])->name('genealogy');
    Route::get('/commissions', [UserController::class, 'commissions'])->name('commissions');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/password/change', [UserController::class, 'changePassword'])->name('password.change');
    Route::put('/password', [UserController::class, 'updatePassword'])->name('password.update');
    Route::get('/orders', [UserController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [UserController::class, 'orderDetails'])->name('orders.show');
    Route::get('/wallet', [UserController::class, 'wallet'])->name('wallet');
    Route::get('/training', [UserController::class, 'training'])->name('training');
    
    // Investment Routes
    Route::prefix('investments')->name('investments.')->group(function () {
        Route::get('/', [App\Http\Controllers\User\InvestController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\User\InvestController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\User\InvestController::class, 'store'])->name('store');
        Route::get('/{investment}', [App\Http\Controllers\User\InvestController::class, 'show'])->name('show');
        Route::get('/statistics/data', [App\Http\Controllers\User\InvestController::class, 'statistics'])->name('statistics');
    });
});

// Temporary public route for testing member products page
Route::get('/test-member-products', [App\Http\Controllers\Member\ProductController::class, 'index'])->name('test.member.products');

// Public member products route (for demonstration/testing without auth)
Route::get('/public-member-products', [App\Http\Controllers\Member\ProductController::class, 'index'])->name('public.member.products');

// Include Member Routes (Protected - For Affiliates)
// Include Member Routes (Protected - For Affiliates)
require __DIR__.'/member.php';

// MLM specific routes
Route::prefix('mlm')->name('mlm.')->group(function () {
    Route::get('/plan', function () { return view('mlm.compensation-plan'); })->name('plan');
    Route::get('/success-stories', function () { return view('mlm.success-stories'); })->name('success-stories');
    Route::get('/join', function () { return redirect()->route('register'); })->name('join');
});

// Affiliate Program Information Routes
Route::prefix('affiliate')->name('affiliate.')->group(function () {
    // Public information pages
    Route::get('/info', function () {
        return view('affiliate.info', [
            'title' => 'Affiliate Program Information',
            'description' => 'Learn about our affiliate program and how you can earn commissions.'
        ]);
    })->name('info');
    
    Route::get('/commission-structure', function () {
        return view('affiliate.commission-structure', [
            'title' => 'Commission Structure',
            'description' => 'Detailed breakdown of our affiliate commission structure and rewards.'
        ]);
    })->name('commission.structure');
    
    // Redirect old routes for backward compatibility
    Route::get('/program', function () { 
        return redirect()->route('affiliate.info'); 
    })->name('program');
});

// Include debug routes for link sharing testing
if (app()->environment(['local', 'staging'])) {
    require __DIR__ . '/debug-link-sharing.php';
}