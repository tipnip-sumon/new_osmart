<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\PointService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DirectPointPurchaseController extends Controller
{
    protected $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    /**
     * Show direct point purchase page with package-based products
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get vendor filter if provided
        $vendorFilter = $request->get('vendor_id');
        
        // Build query for products with 100+ points
        $query = \App\Models\Product::with(['vendor:id,name,shop_name'])
            ->where('is_active', 1)
            ->where('is_starter_kit', 0) // Not starter kit products
            ->whereNotNull('pv_points') // Must have points
            ->where('pv_points', '>=', 100); // Only 100+ points products
        
        // Apply vendor filter if specified
        if ($vendorFilter && $vendorFilter !== 'all') {
            if ($vendorFilter === 'direct') {
                $query->whereNull('vendor_id'); // Direct sales only
            } else {
                $query->where('vendor_id', $vendorFilter); // Specific vendor
            }
        }
        
        $activationProducts = $query->orderBy('sort_order', 'asc')
            ->orderBy('price', 'asc')
            ->get();
        
        // Get all vendors that have products with 100+ points
        $vendorsWithProducts = \App\Models\User::where('role', 'vendor')
            ->where('status', 'active')
            ->whereHas('products', function ($q) {
                $q->where('is_active', 1)
                  ->where('is_starter_kit', 0)
                  ->whereNotNull('pv_points')
                  ->where('pv_points', '>=', 100);
            })
            ->select('id', 'name', 'shop_name')
            ->orderBy('name')
            ->get();
        
        // Get products that can be purchased with current wallet balance (product price only)
        // Note: Delivery charges will be validated dynamically on the frontend and backend
        $affordableProducts = $activationProducts->filter(function ($product) use ($user) {
            $requiredAmount = $product->price;
            return $user->deposit_wallet >= $requiredAmount;
        });
        
        // Set category name based on filter
        if ($vendorFilter && $vendorFilter !== 'all') {
            if ($vendorFilter === 'direct') {
                $categoryName = 'Direct Sales - 100+ Point Products';
            } else {
                $vendor = $vendorsWithProducts->find($vendorFilter);
                $categoryName = ($vendor ? $vendor->shop_name ?? $vendor->name : 'Vendor') . ' - 100+ Point Products';
            }
        } else {
            $categoryName = 'All Vendors - 100+ Point Products';
        }
        
        return view('member.direct-point-purchase.index', compact(
            'user',
            'activationProducts',
            'affordableProducts',
            'categoryName',
            'vendorsWithProducts',
            'vendorFilter'
        ));
    }

    /**
     * Show purchase success page
     */
    public function success()
    {
        $user = Auth::user();
        
        // Get success data from session
        $successData = session('success');
        
        // If no success data, get the latest purchase data
        if (!$successData) {
            // Get latest point transaction
            $latestPointTransaction = \App\Models\PointTransaction::where('user_id', $user->id)
                ->where('reference_type', 'product_purchase')
                ->latest()
                ->first();
                
            $latestOrder = \App\Models\Order::where('customer_id', $user->id)
                ->with(['items', 'items.product'])
                ->latest()
                ->first();
                
            // Create fallback data based on latest records
            $successData = [
                'points_purchased' => $latestPointTransaction ? $latestPointTransaction->amount : 0,
                'amount_paid' => $latestOrder ? $latestOrder->total_amount : 0,
                'remaining_balance' => $user->deposit_wallet,
                'product_name' => ($latestOrder && $latestOrder->items && $latestOrder->items->first() && $latestOrder->items->first()->product) 
                    ? $latestOrder->items->first()->product->name 
                    : '100+ Point Product',
                'purchase_type' => 'product_purchase',
                'account_activated' => false,
                'order_number' => $latestOrder ? $latestOrder->order_number : null,
                'order_status' => $latestOrder ? $latestOrder->status : null,
                'shipping_provided' => false,
            ];
        }

        // Get recent commission data if points were purchased
        $recentCommissions = collect([]); // Initialize as empty collection
        if ($successData['points_purchased'] > 0) {
            $recentCommissions = \App\Models\Commission::where('referred_user_id', $user->id)
                ->where('created_at', '>=', now()->subHours(2)) // Last 2 hours
                ->with('user:id,name')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('member.direct-point-purchase.success', [
            'user' => $user,
            'successData' => $successData,
            'recentCommissions' => $recentCommissions
        ]);
    }

    /**
     * Purchase a specific product and get points + account activation
     */
    public function purchaseProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'confirm_purchase' => 'required|accepted',
            'shipping_address.full_name' => 'nullable|string|max:255',
            'shipping_address.phone' => 'nullable|string|max:20',
            'shipping_address.address' => 'nullable|string',
            'shipping_address.city' => 'nullable|string|max:100',
            'shipping_address.district' => 'nullable|string|max:100',
            'shipping_address.upazilla' => 'nullable|string|max:100',
            'shipping_address.postal_code' => 'nullable|string|max:10',
            'shipping_method' => 'nullable|in:standard,express',
        ]);

        $user = Auth::user();
        $product = \App\Models\Product::with('vendor:id,name,shop_name')->findOrFail($request->product_id);
        
        // Check if product is active and has 100+ points
        if (!$product->is_active || !$product->pv_points || $product->pv_points < 100) {
            return back()->withErrors([
                'product_error' => 'This product is not available for purchase or does not meet the 100+ points requirement.'
            ]);
        }

        $pointsToGet = $product->pv_points; // Use PV points from product
        $amountRequired = $product->price;

        // Prepare shipping address if provided
        $shippingAddress = null;
        $shippingMethod = $request->input('shipping_method', 'standard');
        
        if ($request->has('shipping_address') && !empty(array_filter($request->shipping_address))) {
            $shippingAddress = [
                'full_name' => (string) ($request->shipping_address['full_name'] ?? $user->name),
                'phone' => (string) ($request->shipping_address['phone'] ?? $user->phone),
                'address' => (string) ($request->shipping_address['address'] ?? ''),
                'city' => (string) ($request->shipping_address['city'] ?? ''),
                'district' => (string) ($request->shipping_address['district'] ?? ''),
                'upazilla' => (string) ($request->shipping_address['upazilla'] ?? ''),
                'postal_code' => (string) ($request->shipping_address['postal_code'] ?? ''),
                'country' => 'Bangladesh',
                'shipping_method' => $shippingMethod
            ];
        }

        // Calculate delivery charge once using prepared shipping address
        $deliveryCharge = 0;
        if ($shippingAddress && !empty($shippingAddress['address'])) {
            $deliveryCharge = $this->calculateDeliveryCharge($shippingAddress);
        }
        
        $totalAmount = $amountRequired + $deliveryCharge;

        try {
            // Check wallet balance including delivery charge
            if ($user->deposit_wallet < $totalAmount) {
                return back()->withErrors([
                    'product_error' => 'Insufficient balance. Required: ৳' . number_format($totalAmount, 2) . 
                                      ' (Product: ৳' . number_format($amountRequired, 2) . 
                                      ' + Delivery: ৳' . number_format($deliveryCharge, 2) . 
                                      '), Available: ৳' . number_format($user->deposit_wallet, 2)
                ]);
            }

            // Process the product purchase using PointService directly
            try {
                DB::beginTransaction();
                
                // Use already calculated delivery charge and total amount
                // ($deliveryCharge and $totalAmount are already calculated above)
                
                // Final balance check within transaction
                if ($user->deposit_wallet < $totalAmount) {
                    throw new \Exception('Insufficient balance including delivery charge. Required: ৳' . number_format($totalAmount, 2) . ', Available: ৳' . number_format($user->deposit_wallet, 2));
                }
                
                // Deduct total amount from user's wallet
                DB::table('users')
                    ->where('id', $user->id)
                    ->decrement('deposit_wallet', $totalAmount);

                // Create order for the product - Use actual vendor from product
                $order = \App\Models\Order::create([
                    'customer_id' => $user->id,
                    'vendor_id' => $product->vendor_id, // Use product's vendor_id
                    'order_number' => 'ORD-' . strtoupper(uniqid()),
                    'status' => 'pending', // Requires manual activation
                    'payment_status' => 'paid', // Paid from wallet
                    'payment_method' => 'app_balance',
                    'currency' => 'BDT',
                    'total_amount' => $totalAmount,
                    'subtotal' => $amountRequired,
                    'delivery_charge' => $deliveryCharge,
                    'shipping_address' => $shippingAddress ? json_encode($shippingAddress) : null,
                    'billing_address' => $shippingAddress ? json_encode($shippingAddress) : null, // Same as shipping
                    'payment_details' => json_encode([
                        'wallet_type' => 'deposit',
                        'wallet_balance_before' => $user->deposit_wallet,
                        'wallet_balance_after' => $user->deposit_wallet - $totalAmount,
                        'payment_time' => now()
                    ]),
                    'created_by' => $user->id,
                    'notes' => 'Direct point purchase for ' . $pointsToGet . ' points from ' . ($product->vendor ? ($product->vendor->shop_name ?? $product->vendor->name) : 'Direct Sales') . ' - REQUIRES MANUAL ACTIVATION'
                ]);
                
                // Create order item
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => $amountRequired,
                    'total' => $amountRequired
                ]);
                
                // Create wallet transaction record for the deduction
                $vendorName = $product->vendor ? ($product->vendor->shop_name ?? $product->vendor->name) : 'Direct Sales';
                DB::table('transactions')->insert([
                    'user_id' => $user->id,
                    'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                    'type' => 'debit',
                    'amount' => $totalAmount, // Include delivery charge
                    'fee' => $deliveryCharge,
                    'status' => 'completed',
                    'payment_method' => 'wallet',
                    'wallet_type' => 'deposit',
                    'description' => 'Point purchase from ' . $vendorName . ' (Product: ৳' . number_format($amountRequired, 2) . ', Delivery: ৳' . number_format($deliveryCharge, 2) . ') - Order #' . $order->id,
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                    'processed_by' => $user->id, // User processed their own transaction
                    'processed_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                // ALLOCATE POINTS IMMEDIATELY - Payment is successful, no need for manual activation
                $pointAllocationResult = $this->pointService->allocatePointsForPurchase($user, $product, 1);
                
                if (!$pointAllocationResult['success']) {
                    // If point allocation fails, log but don't rollback the entire transaction
                    Log::warning('Point allocation failed after successful payment', [
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                        'error' => $pointAllocationResult['error'],
                        'order_id' => $order->id
                    ]);
                }
                
                DB::commit();
                
                Log::info('Direct point purchase completed with automatic point allocation', [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'points_allocated' => $pointsToGet,
                    'amount_paid' => $amountRequired,
                    'order_id' => $order->id,
                    'point_allocation_success' => $pointAllocationResult['success'] ?? false
                ]);
                
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Product purchase failed', [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return back()->withErrors([
                    'product_error' => 'Purchase failed: ' . $e->getMessage()
                ]);
            }

            // Get updated balance from database
            $remainingBalance = DB::table('users')->where('id', $user->id)->value('deposit_wallet');

            // Get user's updated point balances after allocation
            $user = User::find($user->id);
            
            return redirect()->route('member.direct-point-purchase.success')
                ->with('success', [
                    'points_purchased' => $pointsToGet, // Points allocated to reserve
                    'points_pending' => 0, // No pending points - they are in reserve immediately
                    'amount_paid' => $amountRequired,
                    'remaining_balance' => $remainingBalance,
                    'product_name' => (string) $product->name,
                    'purchase_type' => 'product_purchase_reserve', // Points added to reserve, not active
                    'product_id' => $product->id,
                    'product_description' => (string) ($product->short_description ?? ''),
                    'account_activated' => false, // Points are in reserve, not activated
                    'requires_manual_activation' => false, // Points are allocated immediately to reserve
                    'reserve_points' => $user->reserve_points ?? 0,
                    'active_points' => $user->active_points ?? 0,
                    'order_number' => $order ? (string) $order->order_number : null,
                    'order_id' => $order ? $order->id : null,
                    'order_status' => $order ? (string) $order->status : null,
                    'shipping_provided' => !empty($shippingAddress),
                ]);

        } catch (\Exception $e) {
            return back()->withErrors([
                'product_error' => $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * API endpoint for real-time calculation
     */
    public function calculateCost(Request $request)
    {
        $points = $request->input('points', 0);
        $cost = $points * 6; // 1 Point = 6 Taka
        
        $user = Auth::user();
        $canAfford = $user->deposit_wallet >= $cost;

        return response()->json([
            'points' => $points,
            'cost' => $cost,
            'formatted_cost' => '৳' . number_format($cost, 2),
            'can_afford' => $canAfford,
            'wallet_balance' => $user->deposit_wallet,
            'formatted_wallet_balance' => '৳' . number_format($user->deposit_wallet, 2),
            'remaining_after_purchase' => $user->deposit_wallet - $cost
        ]);
    }

    /**
     * Calculate delivery charge based on shipping address and method using database values
     */
    private function calculateDeliveryCharge($shippingAddress)
    {
        if (!$shippingAddress || empty($shippingAddress['address'])) {
            return 0;
        }

        $district = $shippingAddress['district'] ?? '';
        $upazila = $shippingAddress['upazilla'] ?? null; // Note: using 'upazilla' as per form field
        $ward = $shippingAddress['ward'] ?? null;
        $shippingMethod = $shippingAddress['shipping_method'] ?? 'standard';

        // Get delivery charge from database using the model's method
        $deliveryChargeData = \App\Models\DeliveryCharge::findChargeForLocation($district, $upazila, $ward);
        
        $baseCharge = $deliveryChargeData->charge ?? 100; // Default fallback

        // Express shipping charges
        if ($shippingMethod === 'express') {
            // Express only available for Dhaka area
            if (in_array($district, ['Dhaka', 'Gazipur', 'Narayanganj'])) {
                $charge = $baseCharge + 100; // Add ৳100 for express
            } else {
                // Fall back to standard for non-Dhaka areas
                $charge = $baseCharge;
                Log::warning('Express shipping requested for non-Dhaka area', [
                    'district' => $district,
                    'falling_back_to_standard' => true
                ]);
            }
        } else {
            $charge = $baseCharge;
        }
        
        Log::info('Delivery charge calculated from database', [
            'district' => $district,
            'upazila' => $upazila,
            'ward' => $ward,
            'shipping_method' => $shippingMethod,
            'base_charge_from_db' => $baseCharge,
            'final_charge' => $charge,
            'address' => $shippingAddress
        ]);

        return $charge;
    }
}
