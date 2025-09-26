<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ShippingController;
use App\Http\Controllers\Api\RealTimeBinaryController;
use App\Http\Controllers\Api\DeliveryChargeController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\Api\PaymentReceiptController;

// Authentication check route for AJAX requests
Route::get('/auth-check', function () {
    return response()->json([
        'authenticated' => Auth::check(),
        'user' => Auth::check() ? [
            'id' => Auth::user()->id,
            'role' => Auth::user()->role,
            'username' => Auth::user()->username,
        ] : null
    ]);
});

// Payment receipt upload routes
Route::post('/upload-payment-receipt', [PaymentReceiptController::class, 'uploadPaymentReceipt']);
Route::post('/upload-bank-receipt', [PaymentReceiptController::class, 'uploadBankReceipt']);

// Product API routes
Route::prefix('products')->group(function () {
    Route::get('/{slug}/quick-view', function ($slug) {
        try {
            // Find product by ID or slug
            $product = null;
            
            if (is_numeric($slug)) {
                $product = App\Models\Product::with(['category', 'brand', 'variants'])
                    ->where('id', $slug)
                    ->where('status', 'active')
                    ->first();
            } else {
                $product = App\Models\Product::with(['category', 'brand', 'variants'])
                    ->where('slug', $slug)
                    ->where('status', 'active')
                    ->first();
            }

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            // Prepare product data for quick view
            $productData = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'short_description' => $product->short_description,
                'description' => $product->description,
                'sku' => $product->sku,
                'stock_quantity' => $product->stock_quantity,
                'images' => $product->images ? (is_string($product->images) ? json_decode($product->images, true) : $product->images) : [],
                'category' => $product->category,
                'brand' => $product->brand,
                'variants' => $product->variants,
                'discount' => $product->discount_percentage ?? 0,
                'featured' => $product->is_featured ?? false,
                'status' => $product->status
            ];

            return response()->json([
                'success' => true,
                'data' => $productData
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Quick view error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching product data'
            ], 500);
        }
    });
});

// Real-time Binary MLM API routes
Route::middleware('auth:sanctum')->prefix('binary')->group(function () {
    Route::get('/volumes', [RealTimeBinaryController::class, 'getBinaryVolumes']);
    Route::get('/tree', [RealTimeBinaryController::class, 'getBinaryTree']);
    Route::get('/user/{userId}/volumes', [RealTimeBinaryController::class, 'getUserBinaryVolumes']);
    Route::post('/user/{userId}/trigger-update', [RealTimeBinaryController::class, 'triggerUpdate']);
});

// Shipping API routes
Route::prefix('shipping')->group(function () {
    Route::post('/calculate', [ShippingController::class, 'calculateShipping']);
    Route::post('/check-free-shipping', [ShippingController::class, 'checkFreeShipping']);
    Route::get('/config', [ShippingController::class, 'getShippingConfig']);
});

// Shipping Cost Calculation
Route::post('/shipping-cost', [ShippingController::class, 'calculateShippingCost']);

// Location API routes
Route::get('/districts', [LocationController::class, 'getDistricts']);
Route::get('/upazilas/{district}', [LocationController::class, 'getUpazilas']);
Route::get('/wards/{district}/{upazila}', [LocationController::class, 'getWards']);

// Coupon API routes
Route::post('/apply-coupon', [CouponController::class, 'validateCoupon']);
Route::post('/validate-coupon', [CouponController::class, 'validateCoupon']);
Route::get('/auto-apply-coupons', [CouponController::class, 'getAutoApplyCoupons']);

// Delivery Charge API routes
Route::prefix('delivery')->group(function () {
    Route::get('/charge', [DeliveryChargeController::class, 'getShippingCharge']);
    Route::get('/districts', [DeliveryChargeController::class, 'getDistricts']);
    Route::get('/upazilas', [DeliveryChargeController::class, 'getUpazilas']);
    Route::get('/wards', [DeliveryChargeController::class, 'getWards']);
});

// Cart API routes
Route::prefix('cart')->group(function () {
    Route::post('/add', function (Request $request) {
        // For now, just return success response
        // In a real app, you'd save to database or session
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'cart_count' => 1 // This would be the actual cart count
        ]);
    });

    Route::get('/count', function (Request $request) {
        // Return cart count
        return response()->json([
            'count' => 0 // This would be the actual cart count from session/database
        ]);
    });

    Route::post('/update-quantity', function (Request $request) {
        // Update cart item quantity
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        
        return response()->json([
            'success' => true,
            'message' => 'Cart quantity updated',
            'quantity' => $quantity
        ]);
    });

    // Cart price update routes
    Route::post('/prices', [CartController::class, 'getPrices']);
    Route::post('/update-prices', [CartController::class, 'updatePrices']);

    Route::delete('/remove/{id}', function ($id) {
        // Remove item from cart
        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart'
        ]);
    });

    Route::delete('/clear', function (Request $request) {
        // Clear entire cart
        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    });

    Route::get('/totals', function (Request $request) {
        // Calculate cart totals
        // This would typically calculate based on session/database cart
        return response()->json([
            'subtotal' => 0.00,
            'shipping' => 5.99,
            'tax' => 0.00,
            'total' => 5.99
        ]);
    });
});

// Coupon API routes
Route::prefix('coupons')->group(function () {
    Route::post('/validate', function (Request $request) {
        $couponCode = $request->input('code');
        $cartTotal = $request->input('cart_total', 0);
        
        // Find coupon by code
        $coupon = \App\Models\Coupon::where('code', $couponCode)
            ->where('is_active', true)
            ->first();
        
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code'
            ], 422);
        }
        
        // Check if coupon is expired
        if ($coupon->end_date && now()->isAfter($coupon->end_date)) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon has expired'
            ], 422);
        }
        
        // Check if coupon has started
        if ($coupon->start_date && now()->isBefore($coupon->start_date)) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon is not yet valid'
            ], 422);
        }
        
        // Check minimum amount
        if ($coupon->minimum_amount && $cartTotal < $coupon->minimum_amount) {
            return response()->json([
                'success' => false,
                'message' => "Minimum order amount of $" . number_format($coupon->minimum_amount, 2) . " required"
            ], 422);
        }
        
        // Check usage limit
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon has reached its usage limit'
            ], 422);
        }
        
        // Calculate discount
        $discount = 0;
        switch ($coupon->type) {
            case 'percentage':
                $discount = ($cartTotal * $coupon->value) / 100;
                break;
            case 'fixed':
                $discount = $coupon->value;
                break;
            case 'free_shipping':
                $discount = 5.99; // Assuming fixed shipping cost
                break;
        }
        
        // Apply maximum discount limit
        if ($coupon->maximum_discount && $discount > $coupon->maximum_discount) {
            $discount = $coupon->maximum_discount;
        }
        
        // Ensure discount doesn't exceed cart total
        if ($discount > $cartTotal) {
            $discount = $cartTotal;
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully',
            'coupon' => [
                'code' => $coupon->code,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'discount_amount' => round($discount, 2),
                'free_shipping' => $coupon->type === 'free_shipping' || $coupon->free_shipping
            ]
        ]);
    });
    
    Route::post('/remove', function (Request $request) {
        return response()->json([
            'success' => true,
            'message' => 'Coupon removed successfully'
        ]);
    });
});

// Wishlist API routes
Route::prefix('wishlist')->group(function () {
    Route::post('/toggle', function (Request $request) {
        $productId = $request->input('product_id');
        
        return response()->json([
            'success' => true,
            'in_wishlist' => true, // This would check actual wishlist status
            'message' => 'Wishlist updated'
        ]);
    });
});

// Reviews API routes
Route::prefix('reviews')->group(function () {
    Route::post('/', function (Request $request) {
        // Validate and save review
        $request->validate([
            'product_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully. It will be published after moderation.'
        ]);
    });
});

// Orders API routes
Route::prefix('orders')->group(function () {
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::get('/{id}/invoice', [OrderController::class, 'downloadInvoice']);
    Route::get('/{id}/pdf', [OrderController::class, 'downloadPdfInvoice']);
});

// Real-time validation routes for registration form
Route::get('/validate-sponsor', function (Request $request) {
    $sponsorId = $request->get('sponsor_id');
    
    if (!$sponsorId) {
        return response()->json(['valid' => false, 'message' => 'Sponsor ID is required']);
    }
    
    // Check by username, referral_code, or referral_hash
    $sponsor = \App\Models\User::where(function($query) use ($sponsorId) {
        $query->where('username', $sponsorId)
              ->orWhere('referral_code', $sponsorId)
              ->orWhere('referral_hash', $sponsorId);
    })->first();
    
    if ($sponsor) {
        return response()->json([
            'valid' => true,
            'sponsor' => [
                'id' => $sponsor->id,
                'name' => $sponsor->name ?? $sponsor->firstname . ' ' . $sponsor->lastname,
                'username' => $sponsor->username,
                'avatar' => $sponsor->avatar ? asset('storage/' . $sponsor->avatar) : asset('assets/img/default-avatar.svg'),
                'status' => $sponsor->status,
                'role' => $sponsor->role
            ]
        ]);
    }
    
    return response()->json(['valid' => false, 'message' => 'Sponsor not found']);
});

Route::get('/check-username', function (Request $request) {
    $username = $request->get('username');
    
    if (!$username) {
        return response()->json(['available' => false, 'message' => 'Username is required']);
    }
    
    // Validate username format
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        return response()->json(['available' => false, 'message' => 'Username can only contain letters, numbers, and underscores']);
    }
    
    // Check if username is available
    $exists = \App\Models\User::where('username', $username)->exists();
    
    return response()->json([
        'available' => !$exists,
        'message' => $exists ? 'Username is already taken' : 'Username is available'
    ]);
});

Route::get('/check-email', function (Request $request) {
    $email = $request->get('email');
    
    if (!$email) {
        return response()->json(['available' => false, 'message' => 'Email is required']);
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return response()->json(['available' => false, 'message' => 'Invalid email format']);
    }
    
    // Check if email is available
    $exists = \App\Models\User::where('email', $email)->exists();
    
    return response()->json([
        'available' => !$exists,
        'message' => $exists ? 'Email is already registered' : 'Email is available'
    ]);
});

Route::get('/check-phone', function (Request $request) {
    $phone = $request->get('phone');
    
    if (!$phone) {
        return response()->json(['available' => false, 'message' => 'Phone number is required']);
    }
    
    // Check if phone is available
    $exists = \App\Models\User::where('phone', $phone)->exists();
    
    return response()->json([
        'available' => !$exists,
        'message' => $exists ? 'Phone number is already registered' : 'Phone number is available'
    ]);
});

// Upline username validation for manual placement
Route::post('/validate-upline-username', function (Request $request) {
    $username = $request->input('username');
    $sponsorId = $request->input('sponsor_id'); // Optional sponsor validation
    
    if (!$username) {
        return response()->json(['valid' => false, 'message' => 'Upline username is required']);
    }
    
    // Find the upline user by username
    $uplineUser = \App\Models\User::where('username', $username)
                                 ->where('status', 'active')
                                 ->first();
    
    if (!$uplineUser) {
        return response()->json(['valid' => false, 'message' => 'Upline username not found or inactive']);
    }
    
    // If sponsor_id is provided, validate MLM hierarchy to prevent cross-linking
    if ($sponsorId) {
        $sponsorUser = \App\Models\User::where('username', $sponsorId)
                                     ->orWhere('id', $sponsorId)
                                     ->where('status', 'active')
                                     ->first();
        
        if ($sponsorUser) {
            // Check if upline user is in the valid hierarchy for the sponsor
            $isValidHierarchy = false;
            
            // Method 1: Allow if upline IS the sponsor (direct placement under sponsor)
            if ($uplineUser->id == $sponsorUser->id || $uplineUser->username == $sponsorUser->username) {
                $isValidHierarchy = true;
            }
            // Method 2: Allow if upline is directly sponsored by the sponsor (same sponsor)
            else if ($uplineUser->sponsor_id == $sponsorUser->id) {
                $isValidHierarchy = true;
            } 
            // Method 3: Check if upline is in sponsor's downline tree (recursive check)
            else {
                $isValidHierarchy = isInDownlineTree($sponsorUser->id, $uplineUser->id);
            }
            
            if (!$isValidHierarchy) {
                return response()->json([
                    'valid' => false, 
                    'message' => 'Cross-linking detected! Upline user "' . $username . '" is not in the downline structure of sponsor "' . $sponsorId . '". Please select an upline from your sponsor\'s downline network, or use your sponsor as the upline.',
                    'cross_link_error' => true
                ]);
            }
        }
    }
    
    return response()->json([
        'valid' => true,
        'upline' => [
            'id' => $uplineUser->id,
            'name' => $uplineUser->name ?? $uplineUser->firstname . ' ' . $uplineUser->lastname,
            'username' => $uplineUser->username,
            'status' => $uplineUser->status,
            'sponsor_id' => $uplineUser->sponsor_id
        ],
        'message' => 'Valid upline username'
    ]);
});

// Check position availability for placement
Route::post('/check-position-availability', function (Request $request) {
    $uplineUsername = $request->input('upline_username');
    $position = $request->input('position'); // 'left' or 'right'
    $sponsorId = $request->input('sponsor_id'); // Optional sponsor validation
    
    if (!$uplineUsername || !$position) {
        return response()->json([
            'available' => false, 
            'message' => 'Upline username and position are required'
        ]);
    }
    
    // Find the upline user
    $uplineUser = \App\Models\User::where('username', $uplineUsername)
                                 ->where('status', 'active')
                                 ->first();
    
    if (!$uplineUser) {
        return response()->json([
            'available' => false, 
            'message' => 'Upline user not found or inactive'
        ]);
    }
    
    // If sponsor_id is provided, validate MLM hierarchy to prevent cross-linking
    if ($sponsorId) {
        $sponsorUser = \App\Models\User::where('username', $sponsorId)
                                     ->orWhere('id', $sponsorId)
                                     ->where('status', 'active')
                                     ->first();
        
        if ($sponsorUser) {
            // Check if upline user is in the valid hierarchy for the sponsor
            $isValidHierarchy = false;
            
            // Method 1: Allow if upline IS the sponsor (direct placement under sponsor)
            if ($uplineUser->id == $sponsorUser->id || $uplineUser->username == $sponsorUser->username) {
                $isValidHierarchy = true;
            }
            // Method 2: Allow if upline is directly sponsored by the sponsor (same sponsor)
            else if ($uplineUser->sponsor_id == $sponsorUser->id) {
                $isValidHierarchy = true;
            } 
            // Method 3: Check if upline is in sponsor's downline tree (recursive check)
            else {
                $isValidHierarchy = isInDownlineTree($sponsorUser->id, $uplineUser->id);
            }
            
            if (!$isValidHierarchy) {
                return response()->json([
                    'available' => false,
                    'message' => 'Cross-linking detected! Upline user "' . $uplineUsername . '" is not in the downline structure of sponsor "' . $sponsorId . '". Please select an upline from your sponsor\'s downline network, or use your sponsor as the upline.',
                    'cross_link_error' => true
                ]);
            }
        }
    }
    
    // Check if the position is already taken
    $existingDownline = \App\Models\User::where('upline_id', $uplineUser->id)
                                       ->where('position', $position)
                                       ->first();
    
    if ($existingDownline) {
        return response()->json([
            'available' => false,
            'message' => ucfirst($position) . ' position is already occupied by ' . $existingDownline->username,
            'occupied_by' => [
                'username' => $existingDownline->username,
                'name' => $existingDownline->name ?? $existingDownline->firstname . ' ' . $existingDownline->lastname,
                'joined_at' => $existingDownline->created_at->format('M d, Y')
            ]
        ]);
    }
    
    return response()->json([
        'available' => true,
        'message' => ucfirst($position) . ' position is available under ' . $uplineUser->username,
        'upline' => [
            'username' => $uplineUser->username,
            'name' => $uplineUser->name ?? $uplineUser->firstname . ' ' . $uplineUser->lastname
        ]
    ]);
});

// Check if user has space for auto placement
Route::post('/check-auto-placement-availability', function (Request $request) {
    $sponsorUsername = $request->input('sponsor_username');
    $preferredPosition = $request->input('position'); // 'left' or 'right'
    
    if (!$sponsorUsername || !$preferredPosition) {
        return response()->json([
            'available' => false,
            'message' => 'Sponsor username and preferred position are required'
        ]);
    }
    
    // Find the sponsor user
    $sponsor = \App\Models\User::where('username', $sponsorUsername)
                              ->orWhere('referral_code', $sponsorUsername)
                              ->orWhere('referral_hash', $sponsorUsername)
                              ->where('status', 'active')
                              ->first();
    
    if (!$sponsor) {
        return response()->json([
            'available' => false,
            'message' => 'Sponsor not found or inactive'
        ]);
    }
    
    // Check if preferred position is available directly under sponsor
    $directPositionTaken = \App\Models\User::where('upline_id', $sponsor->id)
                                          ->where('position', $preferredPosition)
                                          ->exists();
    
    if (!$directPositionTaken) {
        return response()->json([
            'available' => true,
            'placement_type' => 'direct',
            'message' => 'You will be placed directly under your sponsor on the ' . $preferredPosition . ' side',
            'upline' => [
                'username' => $sponsor->username,
                'name' => $sponsor->name ?? $sponsor->firstname . ' ' . $sponsor->lastname
            ]
        ]);
    }
    
    // Find the next available position in the tree using breadth-first search
    $availablePosition = app('App\Http\Controllers\Auth\AffiliateLoginController')->findNextAvailablePositionPublic($sponsor->id, $preferredPosition);
    
    if ($availablePosition) {
        $uplineUser = \App\Models\User::find($availablePosition['upline_id']);
        return response()->json([
            'available' => true,
            'placement_type' => 'auto_deep',
            'message' => 'You will be placed under ' . $uplineUser->username . ' on the ' . $availablePosition['position'] . ' side',
            'upline' => [
                'username' => $uplineUser->username,
                'name' => $uplineUser->name ?? $uplineUser->firstname . ' ' . $uplineUser->lastname,
                'depth' => $availablePosition['depth']
            ]
        ]);
    }
    
    return response()->json([
        'available' => false,
        'message' => 'No available positions found in the ' . $preferredPosition . ' leg. Please try the other position.'
    ]);
});

// Helper function to check if a user is in the downline tree of another user (recursive)
if (!function_exists('isInDownlineTree')) {
    function isInDownlineTree($sponsorId, $targetUserId, $maxDepth = 10, $currentDepth = 0) {
        // Prevent infinite recursion
        if ($currentDepth >= $maxDepth) {
            return false;
        }
        
        // Get all direct downlines of the sponsor
        $downlines = \App\Models\User::where('sponsor_id', $sponsorId)
                                   ->where('status', 'active')
                                   ->get();
        
        foreach ($downlines as $downline) {
            // Direct match found
            if ($downline->id == $targetUserId) {
                return true;
            }
            
            // Recursive check in this downline's subtree
            if (isInDownlineTree($downline->id, $targetUserId, $maxDepth, $currentDepth + 1)) {
                return true;
            }
        }
        
        return false;
    }
}

// Banner Collections API routes
Route::prefix('banner-collections')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\BannerCollectionController::class, 'index']);
    Route::get('/{id}', [App\Http\Controllers\Api\BannerCollectionController::class, 'show']);
});
