<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\CouponUsage;
use App\Models\User;
use App\Models\Commission;
use App\Models\CommissionSetting;
use App\Services\ShippingCalculationService;
use App\Services\TaxCalculationService;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $taxCalculationService;
    protected $pointService;

    public function __construct(TaxCalculationService $taxCalculationService, PointService $pointService)
    {
        $this->taxCalculationService = $taxCalculationService;
        $this->pointService = $pointService;
    }

    /**
     * Validate and apply coupon code
     */
    public function applyCoupon(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'coupon_code' => 'required|string|max:50',
                'subtotal' => 'required|numeric|min:0',
                'cart_items' => 'required|array',
                'shipping_cost' => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data provided',
                    'errors' => $validator->errors()
                ], 422);
            }

            $couponCode = strtoupper(trim($request->coupon_code));
            $subtotal = $request->subtotal;
            $cartItems = $request->cart_items;
            $shippingCost = $request->shipping_cost ?? 0;
            $userId = Auth::id();

            // Find the coupon
            $coupon = Coupon::where('code', $couponCode)
                           ->active()
                           ->first();

            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid coupon code'
                ], 404);
            }

            // Check if coupon is valid for this user
            if (!$this->isCouponValidForUser($coupon, $userId, $subtotal, $cartItems)) {
                return response()->json([
                    'success' => false,
                    'message' => $this->getCouponValidationMessage($coupon, $userId, $subtotal, $cartItems)
                ], 400);
            }

            // Calculate discount
            $discountData = $this->calculateCouponDiscount($coupon, $subtotal, $cartItems, $shippingCost);

            return response()->json([
                'success' => true,
                'coupon' => [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'name' => $coupon->name,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'discount_text' => $coupon->discount_text,
                ],
                'discount' => $discountData,
                'message' => "Coupon '{$coupon->code}' applied successfully!"
            ]);

        } catch (\Exception $e) {
            Log::error('Coupon application error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error applying coupon',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove applied coupon
     */
    public function removeCoupon(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Coupon removed successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing coupon'
            ], 500);
        }
    }

    /**
     * Get available coupons for user
     */
    public function getAvailableCoupons(Request $request)
    {
        try {
            $userId = Auth::id();
            $subtotal = $request->subtotal ?? 0;

            $coupons = Coupon::active()
                            ->where(function($query) use ($userId) {
                                $query->whereNull('user_restrictions')
                                      ->orWhereJsonContains('user_restrictions', $userId);
                            })
                            ->where(function($query) use ($subtotal) {
                                $query->whereNull('minimum_amount')
                                      ->orWhere('minimum_amount', '<=', $subtotal);
                            })
                            ->orderBy('priority', 'desc')
                            ->orderBy('value', 'desc')
                            ->limit(10)
                            ->get();

            return response()->json([
                'success' => true,
                'coupons' => $coupons->map(function($coupon) {
                    return [
                        'id' => $coupon->id,
                        'code' => $coupon->code,
                        'name' => $coupon->name,
                        'description' => $coupon->description,
                        'type' => $coupon->type,
                        'value' => $coupon->value,
                        'minimum_amount' => $coupon->minimum_amount,
                        'discount_text' => $coupon->discount_text,
                        'end_date' => $coupon->end_date?->format('Y-m-d'),
                        'days_remaining' => $coupon->days_remaining,
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching coupons'
            ], 500);
        }
    }

    /**
     * Check if coupon is valid for user
     */
    private function isCouponValidForUser($coupon, $userId, $subtotal, $cartItems)
    {
        // Check minimum amount
        if ($coupon->minimum_amount && $subtotal < $coupon->minimum_amount) {
            return false;
        }

        // Check usage limits
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return false;
        }

        // Check per-user usage limit
        if ($coupon->usage_limit_per_user && $userId) {
            $userUsageCount = CouponUsage::where('coupon_id', $coupon->id)
                                       ->where('user_id', $userId)
                                       ->count();
            
            if ($userUsageCount >= $coupon->usage_limit_per_user) {
                return false;
            }
        }

        // Check if first order only
        if ($coupon->first_order_only && $userId) {
            $previousOrders = Order::where('customer_id', $userId)->count();
            if ($previousOrders > 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get coupon validation error message
     */
    private function getCouponValidationMessage($coupon, $userId, $subtotal, $cartItems)
    {
        if ($coupon->minimum_amount && $subtotal < $coupon->minimum_amount) {
            return "Minimum order amount of ৳{$coupon->minimum_amount} required for this coupon";
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return "This coupon has reached its usage limit";
        }

        if ($coupon->usage_limit_per_user && $userId) {
            $userUsageCount = CouponUsage::where('coupon_id', $coupon->id)
                                       ->where('user_id', $userId)
                                       ->count();
            
            if ($userUsageCount >= $coupon->usage_limit_per_user) {
                return "You have already used this coupon the maximum number of times";
            }
        }

        if ($coupon->first_order_only && $userId) {
            $previousOrders = Order::where('customer_id', $userId)->count();
            if ($previousOrders > 0) {
                return "This coupon is only valid for first-time customers";
            }
        }

        return "Coupon is not valid for this order";
    }

    /**
     * Calculate coupon discount
     */
    private function calculateCouponDiscount($coupon, $subtotal, $cartItems, $shippingCost)
    {
        $discountAmount = 0;
        $freeShipping = false;
        $description = '';

        switch ($coupon->type) {
            case 'percentage':
                $discountAmount = ($subtotal * $coupon->value) / 100;
                if ($coupon->maximum_discount && $discountAmount > $coupon->maximum_discount) {
                    $discountAmount = $coupon->maximum_discount;
                }
                $description = "{$coupon->value}% discount applied";
                break;

            case 'fixed':
                $discountAmount = min($coupon->value, $subtotal);
                $description = "৳{$coupon->value} discount applied";
                break;

            case 'free_shipping':
                $freeShipping = true;
                $discountAmount = $shippingCost; // The amount saved on shipping
                $description = "Free shipping applied";
                break;

            case 'buy_x_get_y':
                // Complex logic - simplified for now
                $discountAmount = 0;
                $description = "Buy X Get Y discount applied";
                break;

            case 'bulk_discount':
                $totalQty = array_sum(array_column($cartItems, 'quantity'));
                if ($totalQty >= 5) { // Example bulk threshold
                    $discountAmount = ($subtotal * $coupon->value) / 100;
                    $description = "Bulk discount applied";
                }
                break;
        }

        return [
            'amount' => round($discountAmount, 2),
            'formatted_amount' => '৳' . number_format($discountAmount, 2),
            'free_shipping' => $freeShipping,
            'description' => $description,
            'savings_text' => $discountAmount > 0 ? "You saved ৳" . number_format($discountAmount, 2) : null,
            'shipping_discount' => $freeShipping ? $shippingCost : 0
        ];
    }

    /**
     * Calculate dynamic tax for AJAX requests
     */
    public function calculateTax(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'subtotal' => 'required|numeric|min:0',
                'location' => 'nullable|string',
                'cart_items' => 'required|array',
                'cart_items.*.price' => 'required|numeric|min:0',
                'cart_items.*.quantity' => 'required|integer|min:1',
                'cart_items.*.category' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data provided',
                    'errors' => $validator->errors()
                ], 422);
            }

            $subtotal = $request->subtotal;
            $location = strtolower($request->location);
            $cartItems = $request->cart_items;
            $userId = Auth::id();

            // Calculate tax using the service
            $taxInfo = $this->taxCalculationService->getTaxInfo($subtotal, $location, $cartItems, $userId);

            return response()->json([
                'success' => true,
                'tax' => $taxInfo,
                'formatted' => [
                    'rate_display' => number_format($taxInfo['rate'], 1),
                    'amount_display' => $taxInfo['formatted_amount'],
                    'description' => $this->getTaxDescription($taxInfo),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Tax calculation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error calculating tax',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user-friendly tax description
     */
    private function getTaxDescription($taxInfo)
    {
        if ($taxInfo['amount'] == 0) {
            return 'Tax-free order';
        }

        if (!empty($taxInfo['breakdown'])) {
            $primary = $taxInfo['breakdown'][0];
            return $primary['description'];
        }

        return $taxInfo['label'] . ' on total amount';
    }

    /**
     * Show the orders page
     */
    public function orders()
    {
        // Get orders for the current user (or all orders for testing)
        $userId = Auth::id() ?? 1;
        $orders = Order::with(['items.product'])
                      ->where('customer_id', $userId)
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the checkout page
     */
    public function index()
    {
        return view('checkout');
    }

    /**
     * Process the checkout
     */
    public function store(Request $request)
    {
        // Debug: log the request data
        Log::info('Checkout request data:', $request->all());
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'state' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255', // Accept both area and state
            'payment_method' => 'required|string|in:cash_on_delivery,bank_transfer,online_payment',
            'shipping_method' => 'required|string|in:inside_dhaka,outside_dhaka,across_country,free',
            'cart_items' => 'required|array|min:1', // Should be array, not string
            'cart_items.*.product_id' => 'required|integer',
            'cart_items.*.quantity' => 'required|integer|min:1',
            'cart_items.*.price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'order_notes' => 'nullable|string|max:1000',
            'coupon_code' => 'nullable|string|max:50',
            'discount_amount' => 'nullable|numeric|min:0',
            // Online payment fields
            'online_payment_type' => 'required_if:payment_method,online_payment|string|in:bkash,nagad,rocket',
            'transaction_id' => 'required_if:payment_method,online_payment|string|max:100',
            // Bank transfer fields
            'bank_transaction_ref' => 'required_if:payment_method,bank_transfer|string|max:100',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Validate cart items and calculate totals
            $cartItems = $request->cart_items; // Already an array from validation
            
            Log::info('Cart items from request:', $cartItems);
            
            if (!$cartItems || !is_array($cartItems)) {
                Log::error('Cart items validation failed: not array');
                throw new \Exception('Invalid cart items data');
            }
            
            $calculatedSubtotal = 0;
            $orderItems = [];

            foreach ($cartItems as $index => $item) {
                Log::info("Processing cart item {$index}:", $item);
                
                if (!isset($item['product_id'], $item['quantity'], $item['price'])) {
                    Log::error("Cart item structure validation failed for item {$index}:", [
                        'has_product_id' => isset($item['product_id']),
                        'has_quantity' => isset($item['quantity']),
                        'has_price' => isset($item['price']),
                        'item' => $item
                    ]);
                    throw new \Exception('Invalid cart item structure');
                }
                
                $product = Product::find($item['product_id']);
                Log::info("Product lookup for ID {$item['product_id']}:", [
                    'found' => $product !== null,
                    'status' => $product ? $product->status : 'N/A'
                ]);
                
                if (!$product || $product->status !== 'active') {
                    $productName = $product ? $product->name : 'Unknown';
                    throw new \Exception("Product not available: {$productName}");
                }

                $itemTotal = $product->price * $item['quantity'];
                $calculatedSubtotal += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'total' => $itemTotal,
                    'size' => $item['size'] ?? null,
                    'color' => $item['color'] ?? null,
                ];
            }

            Log::info('All cart items processed successfully');

            // Validate coupon if provided
            $discountAmount = 0;
            $couponId = null;
            
            if ($request->filled('coupon_code')) {
                $coupon = Coupon::where('code', $request->coupon_code)->first();
                
                // For now, just use user_id = 1 if not authenticated (for testing)
                $userId = Auth::id() ?? 1;
                
                if ($coupon && $coupon->isValidForUser($userId)) {
                    // Create a mock order object for discount calculation
                    $mockOrder = (object) [
                        'subtotal' => $calculatedSubtotal,
                        'shipping_cost' => floatval($request->shipping_cost),
                        'user_id' => $userId,
                        'vendor_id' => null, // For multi-vendor support later
                        'country' => 'US' // Default country, can be made dynamic
                    ];
                    
                    $backendDiscount = $coupon->calculateDiscount($mockOrder);
                    $frontendDiscount = floatval($request->discount_amount);
                    
                    // Use the frontend discount if it matches backend calculation within tolerance
                    if (abs($backendDiscount - $frontendDiscount) <= 0.01) {
                        $discountAmount = $frontendDiscount;
                    } else {
                        $discountAmount = $backendDiscount;
                        Log::warning('Discount amount mismatch:', [
                            'backend_calculated' => $backendDiscount,
                            'frontend_sent' => $frontendDiscount,
                            'using_backend' => true
                        ]);
                    }
                    
                    $couponId = $coupon->id;
                } else {
                    throw new \Exception('Invalid or expired coupon code');
                }
            } else if ($request->filled('discount_amount') && floatval($request->discount_amount) > 0) {
                // If discount amount is sent but no coupon code, this is an error
                throw new \Exception('Discount amount provided without valid coupon code');
            }

            // Calculate final total
            $requestedShippingCost = floatval($request->shipping_cost);
            $shippingMethod = $request->shipping_method ?? config('shipping.default_method', 'inside_dhaka');
            
            // Use shipping calculation service for more sophisticated logic
            $shippingService = new ShippingCalculationService();
            $userId = Auth::id() ?? 1;
            $expectedShippingCost = $shippingService->calculateShippingCost(
                $shippingMethod, 
                $cartItems, 
                $calculatedSubtotal, 
                $userId
            );
            
            // If coupon provides free shipping, override
            $shippingCost = $expectedShippingCost;
            if ($couponId) {
                $coupon = Coupon::find($couponId);
                if ($coupon && $coupon->free_shipping) {
                    $shippingCost = 0;
                }
            }
            
            // Validate shipping cost matches expected
            if (abs($shippingCost - $requestedShippingCost) > 0.01) {
                Log::warning('Shipping cost mismatch:', [
                    'calculated_shipping' => $shippingCost,
                    'frontend_shipping' => $requestedShippingCost,
                    'subtotal' => $calculatedSubtotal,
                    'coupon_free_shipping' => $couponId ? ($coupon->free_shipping ?? false) : false
                ]);
                // Use calculated shipping cost instead of frontend value
                $shippingCost = $expectedShippingCost;
                if ($couponId && $coupon->free_shipping) {
                    $shippingCost = 0;
                }
            }
            
            // Calculate tax amount (5%)
            $taxAmount = floatval($request->tax_amount ?? ($calculatedSubtotal * 0.05));
            $finalTotal = $calculatedSubtotal + $shippingCost + $taxAmount - $discountAmount;

            // Debug: Log the calculation details
            Log::info('Total calculation debug:', [
                'calculated_subtotal' => $calculatedSubtotal,
                'shipping_cost' => $shippingCost,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'calculated_total' => $finalTotal,
                'frontend_total' => floatval($request->total),
                'difference' => abs($finalTotal - floatval($request->total)),
            ]);

            // Verify the total matches what was sent from frontend
            $totalDifference = abs($finalTotal - floatval($request->total));
            if ($totalDifference > 0.01) {
                // Instead of rejecting, provide option to proceed with updated total
                $errorDetails = [
                    'message' => 'Cart prices have been updated since you added items.',
                    'details' => [
                        'original_total' => floatval($request->total),
                        'updated_total' => $finalTotal,
                        'difference' => $totalDifference,
                        'reason' => 'Product prices have been updated since you added them to your cart.'
                    ],
                    'action_required' => 'confirm_updated_total'
                ];
                
                Log::warning('Price mismatch detected - offering to proceed with updated total:', $errorDetails);
                
                // For now, let's auto-proceed with the updated total but notify the user
                // In a production app, you might want to require explicit user confirmation
                Log::info('Auto-proceeding with updated total for better UX');
                
                // Continue with order creation but we'll return special response
                $proceedWithUpdatedTotal = true;
            } else {
                $proceedWithUpdatedTotal = false;
            }

            // Create the order using the correct Order model field names
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            
            $orderData = [
                'order_number' => $orderNumber,
                'customer_id' => Auth::id() ?? 1, // Use 1 for testing if not authenticated
                'vendor_id' => 1, // Add vendor_id field (use 1 for testing)
                'status' => 'pending',
                'payment_status' => $request->payment_method === 'cash_on_delivery' ? 'pending' : 
                                   ($request->payment_method === 'online_payment' ? 'pending' : 'pending'),
                'shipping_status' => 'not_shipped', // Use correct shipping status
                'total_amount' => $finalTotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingCost,
                'discount_amount' => $discountAmount,
                'subtotal' => $calculatedSubtotal,
                'currency' => 'BDT',
                'payment_method' => $request->payment_method,
                'shipping_method' => $shippingMethod,
                'shipping_address' => [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state ?? $request->area, // Use state or area field
                    'zip_code' => $request->zip_code,
                ],
                'billing_address' => [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state ?? $request->area, // Use state or area field
                    'zip_code' => $request->zip_code,
                ],
                'payment_details' => [
                    'payment_method' => $request->payment_method,
                    'online_payment_type' => $request->online_payment_type ?? null,
                    'transaction_id' => $request->transaction_id ?? null,
                    'bank_transaction_ref' => $request->bank_transaction_ref ?? null,
                    'payment_gateway' => $request->payment_method === 'online_payment' ? $request->online_payment_type : null,
                ],
                'notes' => $request->order_notes ?? ('Items: ' . json_encode($orderItems)),
            ];

            // Create the order
            $order = Order::create($orderData);
            
            Log::info('Order created successfully:', ['order_id' => $order->id, 'order_number' => $orderNumber]);

            // Record coupon usage
            if ($couponId) {
                $userId = Auth::id() ?? 1;
                CouponUsage::create([
                    'coupon_id' => $couponId,
                    'user_id' => $userId,
                    'order_id' => $order->id,
                    'discount_amount' => $discountAmount,
                    'order_amount' => $finalTotal,
                    'user_ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'order_details' => $orderItems,
                    'used_at' => now()
                ]);
                
                // Increment the coupon's used_count
                $coupon = Coupon::find($couponId);
                if ($coupon) {
                    $coupon->increment('used_count');
                }
            }

            DB::commit();

            // Prepare response
            $response = [
                'success' => true,
                'message' => 'Order placed successfully!',
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $orderNumber,
                    'total' => $finalTotal,
                    'payment_method' => $request->payment_method,
                ]
            ];

            // Add price update notification if prices were adjusted
            if ($proceedWithUpdatedTotal) {
                $response['price_updated'] = true;
                $response['price_update_message'] = sprintf(
                    'Note: Your order total was updated from $%.2f to $%.2f due to recent price changes.',
                    floatval($request->total),
                    $finalTotal
                );
                $response['data']['original_total'] = floatval($request->total);
                $response['data']['price_difference'] = $totalDifference;
            }

            return response()->json($response);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Checkout failed:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Process and store order
     */
    public function processOrder(Request $request)
    {
        try {
            // Log incoming request for debugging
            Log::info('ProcessOrder method called', [
                'auth_check' => Auth::check(),
                'user_id' => Auth::id(),
                'checkout_type' => $request->checkout_type ?? 'not_provided',
                'customer_email' => $request->customer_email ?? 'not_provided',
                'request_size' => strlen(json_encode($request->all())),
                'user_agent' => $request->userAgent()
            ]);
            
            // Validate request data
            $validationRules = [
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:20',
                'shipping_address' => 'required|array',
                'shipping_address.address' => 'required|string',
                'shipping_address.city' => 'required|string',
                'shipping_address.state' => 'sometimes|string',
                'shipping_address.postal_code' => 'sometimes|string',
                'shipping_address.country' => 'sometimes|string',
                'billing_address' => 'sometimes|array',
                'payment_method' => 'required|string',
                'shipping_method' => 'required|string',
                'cart_items' => 'required|array|min:1',
                'cart_items.*.product_id' => 'required|integer',
                'cart_items.*.quantity' => 'required|integer|min:1',
                'cart_items.*.price' => 'required|numeric|min:0',
                'subtotal' => 'required|numeric|min:0',
                'shipping_cost' => 'required|numeric|min:0',
                'tax_amount' => 'sometimes|numeric|min:0',
                'discount_amount' => 'sometimes|numeric|min:0',
                'total_amount' => 'required|numeric|min:0',
                'coupon_code' => 'sometimes|string',
                // Checkout type - allow 'authenticated' for logged-in users
                'checkout_type' => 'required|in:guest,register,authenticated',
            ];

            // Only add registration validation if user is not authenticated and checkout_type is register
            if (!Auth::check() && $request->checkout_type === 'register') {
                $validationRules['username'] = 'required|string|min:3|max:20|regex:/^[a-zA-Z0-9_]+$/|unique:users,username';
                $validationRules['password'] = 'required|string|min:6|confirmed';
                $validationRules['password_confirmation'] = 'required|string|min:6';
            }

            $validator = Validator::make($request->all(), $validationRules);

            if ($validator->fails()) {
                Log::error('Order validation failed:', [
                    'errors' => $validator->errors()->toArray(),
                    'request_data' => $request->all()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . $validator->errors()->first(),
                    'errors' => $validator->errors(),
                    'debug_data' => $request->all()
                ], 422);
            }

            // Validate stock availability before processing order
            foreach ($request->cart_items as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    throw new \Exception("Product with ID {$item['product_id']} not found");
                }

                // Check if sufficient stock is available
                if ($product->track_quantity && $product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product '{$product->name}'. Available: {$product->stock_quantity}, Requested: {$item['quantity']}");
                }
            }

            DB::beginTransaction();

            // Determine customer ID based on authentication status and checkout type
            if (Auth::check()) {
                // User is already logged in - use their ID
                $customerId = Auth::id();
                Log::info('Checkout by authenticated user:', [
                    'user_id' => $customerId,
                    'email' => Auth::user()->email,
                    'checkout_type' => $request->checkout_type
                ]);
            } elseif ($request->checkout_type === 'register') {
                // For register checkout type, user should already be registered via affiliate registration
                // during the frontend flow and be authenticated. If not, return error.
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Registration required. Please complete your account registration first.'
                ], 401);
            } else {
                // Guest checkout - use a default guest customer ID
                $customerId = 1; // Default guest customer ID
                Log::info('Guest checkout:', [
                    'customer_email' => $request->customer_email,
                    'checkout_type' => 'guest'
                ]);
            }

            // Generate unique order number
            $orderNumber = $this->generateOrderNumber();

            // Prepare payment details based on payment method
            $paymentDetails = [
                'method' => $request->payment_method,
                'status' => 'pending'
            ];

            // Add specific payment details based on method
            if ($request->payment_method === 'online_payment') {
                $paymentDetails['online_payment_type'] = $request->online_payment_type ?? null;
                $paymentDetails['transaction_id'] = $request->transaction_id ?? null;
            } elseif ($request->payment_method === 'bank_transfer') {
                $paymentDetails['bank_reference'] = $request->bank_reference ?? null;
            }

            // Add customer IP and user agent for security
            $paymentDetails['customer_ip'] = $request->ip();
            $paymentDetails['user_agent'] = $request->userAgent();
            $paymentDetails['created_at'] = now()->toDateTimeString();

            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $customerId,
                'vendor_id' => 1, // Default vendor - adjust based on your multi-vendor logic
                'status' => 'pending',
                'payment_status' => $request->payment_method === 'online_payment' ? 'pending' : 'pending',
                'shipping_status' => 'not_shipped',
                'subtotal' => $request->subtotal,
                'tax_amount' => $request->tax_amount ?? 0,
                'shipping_amount' => $request->shipping_cost,
                'discount_amount' => $request->discount_amount ?? 0,
                'total_amount' => $request->total_amount,
                'currency' => 'BDT',
                'payment_method' => $request->payment_method,
                'shipping_method' => $request->shipping_method,
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address ?? $request->shipping_address,
                'payment_details' => $paymentDetails,
                'notes' => $request->notes ?? null,
            ]);

            // Create order items and update inventory
            foreach ($request->cart_items as $item) {
                // Validate stock availability before processing
                $product = Product::find($item['product_id']);
                if (!$product) {
                    throw new \Exception("Product with ID {$item['product_id']} not found");
                }

                // Check if sufficient stock is available
                if ($product->track_quantity && $product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product '{$product->name}'. Available: {$product->stock_quantity}, Requested: {$item['quantity']}");
                }

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity']
                ]);

                // Update product stock if tracking is enabled
                if ($product->track_quantity) {
                    $previousQuantity = $product->stock_quantity;
                    
                    // Decrement stock quantity
                    $product->decrement('stock_quantity', $item['quantity']);
                    $newQuantity = $product->fresh()->stock_quantity;
                    
                    // Update stock status
                    $product->in_stock = $newQuantity > 0;
                    $product->save();
                    
                    // Log inventory movement if InventoryMovement model exists
                    if (class_exists('\App\Models\InventoryMovement')) {
                        // Get default warehouse or use first available
                        $warehouseId = null;
                        if (class_exists('\App\Models\Warehouse')) {
                            $warehouse = \App\Models\Warehouse::first();
                            $warehouseId = $warehouse ? $warehouse->id : null;
                        }
                        
                        // Create inventory movement record
                        if ($warehouseId) {
                            \App\Models\InventoryMovement::create([
                                'product_id' => $product->id,
                                'warehouse_id' => $warehouseId,
                                'type' => 'sold',
                                'quantity' => $item['quantity'],
                                'remaining_quantity' => $newQuantity,
                                'unit_cost' => $product->cost_price ?? $item['price'],
                                'previous_quantity' => $previousQuantity,
                                'new_quantity' => $newQuantity,
                                'reason' => 'Order creation - Order #' . $order->order_number,
                                'reference_id' => $order->id,
                                'reference_type' => 'order_creation',
                                'user_id' => $customerId,
                                'order_id' => $order->id,
                                'created_by' => $customerId,
                                'notes' => 'Stock sold via customer order',
                                'is_approved' => true,
                                'approved_by' => $customerId,
                                'approved_at' => now(),
                                'movement_date' => now(),
                                'reference_number' => 'MOV-' . now()->format('Ymd') . '-' . rand(1000, 9999)
                            ]);
                        }
                    }
                    
                    // Update inventory record if it exists
                    $inventory = $product->inventory;
                    if ($inventory) {
                        $oldInventoryQuantity = $inventory->quantity;
                        $inventory->quantity = max(0, $inventory->quantity - $item['quantity']);
                        // Note: available_quantity is auto-calculated as (quantity - reserved_quantity)
                        $inventory->last_updated_by = $customerId;
                        $inventory->save();
                        
                        // Check for low stock alerts
                        if (method_exists($inventory, 'checkAndCreateAlerts')) {
                            $inventory->checkAndCreateAlerts();
                        }
                    }
                    
                    // Check if product needs low stock alert
                    if ($newQuantity <= ($product->min_stock_level ?? 5)) {
                        Log::warning("Low stock alert for product {$product->name} (ID: {$product->id}). Current stock: {$newQuantity}, Minimum level: " . ($product->min_stock_level ?? 5));
                        
                        // Create low stock alert if InventoryAlert model exists
                        if (class_exists('\App\Models\InventoryAlert') && $inventory) {
                            try {
                                \App\Models\InventoryAlert::firstOrCreate([
                                    'inventory_id' => $inventory->id,
                                    'type' => 'low_stock',
                                    'is_resolved' => false
                                ], [
                                    'message' => "Low stock: {$product->name} has {$newQuantity} units remaining",
                                    'severity' => $newQuantity == 0 ? 'critical' : 'warning',
                                    'triggered_at' => now(),
                                    'threshold_value' => $product->min_stock_level ?? 5,
                                    'current_value' => $newQuantity
                                ]);
                            } catch (\Exception $e) {
                                Log::error("Failed to create low stock alert: " . $e->getMessage());
                            }
                        }
                    }
                    
                    Log::info("Stock updated for product {$product->name} (ID: {$product->id}): {$previousQuantity} → {$newQuantity} (sold: {$item['quantity']})");
                }
            }

            // Process coupon usage if coupon was applied
            if ($request->has('coupon_code') && $request->coupon_code) {
                $coupon = Coupon::where('code', $request->coupon_code)->first();
                if ($coupon) {
                    // Increment coupon usage
                    $coupon->increment('used_count');
                    
                    // Record coupon usage for all users (including guests)
                    CouponUsage::create([
                        'coupon_id' => $coupon->id,
                        'user_id' => $customerId, // Track for all users including guests
                        'order_id' => $order->id,
                        'discount_amount' => $request->discount_amount ?? 0,
                        'order_amount' => $request->total_amount ?? 0,
                        'user_ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'used_at' => now(),
                    ]);
                    
                    Log::info('Coupon usage tracked', [
                        'coupon_code' => $request->coupon_code,
                        'discount_amount' => $request->discount_amount ?? 0,
                        'order_amount' => $request->total_amount ?? 0,
                        'order_id' => $order->id,
                        'user_id' => $customerId
                    ]);
                }
            }

            // Process affiliate commission if user came through affiliate link
            $this->processAffiliateCommission($order, $request);

            // Process point allocation if payment status is 'paid'
            if ($order->payment_status === 'paid') {
                $this->processPointAllocation($order, $request->cart_items);
            }

            // Clear cart session
            session()->forget(['cart', 'applied_coupon']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order->order_number,
                'order_db_id' => $order->id,
                'redirect_url' => route('orders.success', ['id' => $order->order_number])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            // Determine error type and provide appropriate HTTP status code
            $statusCode = 500;
            $errorType = 'server_error';
            
            if (strpos($e->getMessage(), 'validation') !== false || strpos($e->getMessage(), 'required') !== false) {
                $statusCode = 422;
                $errorType = 'validation_error';
            } elseif (strpos($e->getMessage(), 'authentication') !== false || strpos($e->getMessage(), 'login') !== false) {
                $statusCode = 401;
                $errorType = 'authentication_error';
            } elseif (strpos($e->getMessage(), 'permission') !== false || strpos($e->getMessage(), 'access') !== false) {
                $statusCode = 403;
                $errorType = 'permission_error';
            }
            
            Log::error('Order processing failed:', [
                'error' => $e->getMessage(),
                'error_type' => $errorType,
                'status_code' => $statusCode,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'auth_check' => Auth::check(),
                'user_id' => Auth::id(),
                'checkout_type' => $request->checkout_type ?? 'not_provided',
                'customer_email' => $request->customer_email ?? 'not_provided',
                'trace' => $e->getTraceAsString(),
                'request_data_keys' => array_keys($request->all())
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process order: ' . $e->getMessage(),
                'error_type' => $errorType
            ], $statusCode);
        }
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        } while (Order::where('order_number', $orderNumber)->exists());
        
        return $orderNumber;
    }

    /**
     * Show order success page
     */
    public function orderSuccess($orderNumber = null)
    {
        $order = null;
        
        if ($orderNumber) {
            $order = Order::with(['items.product', 'customer'])
                         ->where('order_number', $orderNumber)
                         ->first();
        }

        return view('orders.success', compact('order', 'orderNumber'));
    }

    /**
     * Check username availability
     */
    public function checkUsername(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|min:3|max:20|regex:/^[a-zA-Z0-9_]+$/',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'available' => false,
                    'message' => 'Invalid username format'
                ], 422);
            }

            $username = $request->username;
            
            // Check if username exists in users table
            $exists = DB::table('users')->where('username', $username)->exists();

            return response()->json([
                'available' => !$exists,
                'message' => $exists ? 'Username is already taken' : 'Username is available'
            ]);

        } catch (\Exception $e) {
            Log::error('Username check error: ' . $e->getMessage());
            
            return response()->json([
                'available' => false,
                'message' => 'Error checking username availability'
            ], 500);
        }
    }

    /**
     * Process affiliate commission for order
     */
    protected function processAffiliateCommission(Order $order, Request $request)
    {
        try {
            // Check for affiliate info in session first (immediate tracking)
            $affiliateInfo = session('affiliate_info');
            
            // If no session info, check persistent cookie (extended attribution)
            if (!$affiliateInfo || !isset($affiliateInfo['affiliate_id'])) {
                $affiliateInfo = $this->getAffiliateInfoFromCookie();
            }
            
            if (!$affiliateInfo || !isset($affiliateInfo['affiliate_id'])) {
                Log::info('No affiliate info found in session or cookie for order: ' . $order->order_number);
                return;
            }

            $affiliateId = $affiliateInfo['affiliate_id'];
            $productId = $affiliateInfo['product_id'] ?? null;
            
            // Verify affiliate user exists
            $affiliate = User::find($affiliateId);
            if (!$affiliate) {
                Log::warning('Affiliate user not found: ' . $affiliateId);
                return;
            }

            // Log affiliate commission attribution source
            $attributionSource = session('affiliate_info') ? 'session' : 'cookie';
            Log::info('Affiliate commission attributed from: ' . $attributionSource, [
                'order_id' => $order->id,
                'affiliate_id' => $affiliateId,
                'attribution_source' => $attributionSource
            ]);

            // Check if the purchased products include the shared product or any product from order
            $orderProductIds = $order->items->pluck('product_id')->toArray();
            $commissionProducts = [];
            
            if ($productId && in_array($productId, $orderProductIds)) {
                // Specific product was shared and purchased
                $commissionProducts = [$productId];
                Log::info('Commission for specific shared product', [
                    'affiliate_id' => $affiliateId,
                    'product_id' => $productId,
                    'order_id' => $order->id
                ]);
            } else {
                // Commission on all products in order (general affiliate)
                $commissionProducts = $orderProductIds;
                Log::info('Commission for all products in order', [
                    'affiliate_id' => $affiliateId,
                    'product_ids' => $orderProductIds,
                    'order_id' => $order->id
                ]);
            }

            // Calculate and create commissions for each product
            foreach ($commissionProducts as $productId) {
                $orderItem = $order->items->where('product_id', $productId)->first();
                if (!$orderItem) continue;

                $product = Product::find($productId);
                if (!$product) continue;

                // Calculate commission amount
                $itemTotal = $orderItem->quantity * $orderItem->price;
                $commissionRate = $this->getAffiliateCommissionRate($affiliate, $product);
                $commissionAmount = $itemTotal * ($commissionRate / 100);

                // Only create commission if amount is greater than 0
                if ($commissionAmount > 0) {
                    Commission::create([
                        'user_id' => $affiliateId,
                        'referred_user_id' => $order->customer_id,
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'commission_type' => 'affiliate',
                        'level' => 1,
                        'order_amount' => $itemTotal,
                        'commission_rate' => $commissionRate / 100, // Store as decimal
                        'commission_amount' => $commissionAmount,
                        'status' => 'pending',
                        'earned_at' => now(),
                        'notes' => "Affiliate commission for product: {$product->name}"
                    ]);

                    Log::info('Affiliate commission created', [
                        'affiliate_id' => $affiliateId,
                        'affiliate_username' => $affiliate->username,
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'product_id' => $productId,
                        'product_name' => $product->name,
                        'item_total' => $itemTotal,
                        'commission_rate' => $commissionRate . '%',
                        'commission_amount' => $commissionAmount,
                        'customer_id' => $order->customer_id
                    ]);
                }
            }

            // Clear affiliate info from session after processing
            session()->forget('affiliate_info');
            
            // Note: We don't clear the cookie here to allow for multiple purchases
            // within the attribution window. Cookie will expire naturally.
            
            Log::info('Affiliate commission processing completed for order: ' . $order->order_number);

        } catch (\Exception $e) {
            Log::error('Failed to process affiliate commission for order ' . $order->order_number . ': ' . $e->getMessage());
            // Don't throw error to avoid breaking the order process
        }
    }

    /**
     * Get affiliate commission rate for user and product
     */
    protected function getAffiliateCommissionRate(User $affiliate, Product $product)
    {
        // Default commission rate
        $defaultRate = 5.0; // 5%

        // First, check for specific affiliate commission settings
        $affiliateCommissionSetting = CommissionSetting::where('type', 'affiliate')
                                                      ->where('is_active', true)
                                                      ->orderBy('priority', 'desc')
                                                      ->first();

        if ($affiliateCommissionSetting) {
            // If it's a percentage type, use the value directly
            if ($affiliateCommissionSetting->calculation_type === 'percentage') {
                $defaultRate = $affiliateCommissionSetting->value;
            } else {
                // For fixed amount, we'll use the default percentage for now
                // You could extend this to calculate percentage based on product price
                $defaultRate = $affiliateCommissionSetting->value;
            }
        }

        // Check if user has a specific commission rate
        if (isset($affiliate->commission_rate) && $affiliate->commission_rate > 0) {
            return $affiliate->commission_rate * 100; // Convert decimal to percentage
        }

        // Check if product has specific affiliate commission rate
        if (isset($product->affiliate_commission_rate) && $product->affiliate_commission_rate > 0) {
            return $product->affiliate_commission_rate;
        }

        // You can add more complex logic here:
        // - Commission rates based on user level/rank
        // - Commission rates based on product category
        // - Commission rates based on order amount
        // - Different rates for different user types

        return $defaultRate;
    }

    /**
     * Get affiliate info from persistent cookie
     */
    protected function getAffiliateInfoFromCookie()
    {
        try {
            $cookieName = config('affiliate.cookie_name', 'affiliate_tracking');
            $cookieValue = request()->cookie($cookieName);
            
            if (!$cookieValue) {
                return null;
            }

            // Decrypt and decode cookie data
            $cookieData = json_decode(decrypt($cookieValue), true);
            
            if (!$cookieData || !is_array($cookieData)) {
                return null;
            }

            // Check if cookie has expired
            if (isset($cookieData['expires_at']) && time() > $cookieData['expires_at']) {
                Log::info('Affiliate tracking cookie expired', $cookieData);
                return null;
            }

            // Validate required fields
            if (!isset($cookieData['affiliate_id']) || !isset($cookieData['tracked_at'])) {
                return null;
            }

            Log::info('Retrieved affiliate info from cookie', [
                'affiliate_id' => $cookieData['affiliate_id'],
                'days_since_click' => round((time() - $cookieData['tracked_at']) / 86400, 1)
            ]);

            return $cookieData;
            
        } catch (\Exception $e) {
            Log::warning('Failed to retrieve affiliate info from cookie: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Process point allocation for paid orders
     */
    protected function processPointAllocation(Order $order, $orderItems)
    {
        try {
            // Only allocate points if payment status is 'paid'
            if ($order->payment_status !== 'paid') {
                Log::info('Skipping point allocation - order not paid', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'payment_status' => $order->payment_status
                ]);
                return;
            }

            // Get customer
            $customer = User::find($order->customer_id);
            if (!$customer) {
                Log::warning('Customer not found for point allocation', [
                    'order_id' => $order->id,
                    'customer_id' => $order->customer_id
                ]);
                return;
            }

            $totalPointsAllocated = 0;

            // Process each order item for point allocation
            foreach ($orderItems as $item) {
                if (!isset($item['product_id'], $item['quantity'], $item['price'])) {
                    continue;
                }

                $product = Product::find($item['product_id']);
                if (!$product) {
                    Log::warning('Product not found for point allocation', [
                        'product_id' => $item['product_id'],
                        'order_id' => $order->id
                    ]);
                    continue;
                }

                // Use the PointService to allocate points for this product
                $result = $this->pointService->allocatePointsForPurchase(
                    $customer,
                    $product,
                    $item['quantity']
                );

                if ($result['success']) {
                    $totalPointsAllocated += $result['points_allocated'];

                    Log::info('Points allocated for product', [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $item['quantity'],
                        'points_allocated' => $result['points_allocated'],
                        'customer_id' => $customer->id,
                        'order_id' => $order->id
                    ]);
                } else {
                    Log::warning('Failed to allocate points for product', [
                        'product_id' => $product->id,
                        'error' => $result['error'] ?? 'Unknown error',
                        'order_id' => $order->id
                    ]);
                }
            }

            if ($totalPointsAllocated > 0) {
                Log::info('Total points allocated for order', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_id' => $customer->id,
                    'customer_username' => $customer->username,
                    'total_points' => $totalPointsAllocated
                ]);
            } else {
                Log::info('No points allocated for order', [
                    'order_id' => $order->id,
                    'order_total' => $order->total_amount
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to process point allocation', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Don't throw error to avoid breaking the order process
        }
    }
}
