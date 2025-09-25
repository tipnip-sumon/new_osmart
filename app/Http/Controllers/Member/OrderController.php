<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    use HandlesImageUploads;
    /**
     * Display a listing of the member's orders.
     */
    public function index(Request $request)
    {
        $orders = Order::where('customer_id', Auth::id())
            ->with(['items.product', 'customer'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate statistics for the authenticated user
        $totalOrders = Order::where('customer_id', Auth::id())->count();
        $completedOrders = Order::where('customer_id', Auth::id())->where('status', 'completed')->count();
        $pendingOrders = Order::where('customer_id', Auth::id())->where('status', 'pending')->count();
        $totalOrderValue = Order::where('customer_id', Auth::id())->sum('total_amount');
        $monthlyOrders = Order::where('customer_id', Auth::id())
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $averageOrderValue = $totalOrders > 0 ? $totalOrderValue / $totalOrders : 0;
        $successRate = $totalOrders > 0 ? ($completedOrders / $totalOrders) * 100 : 0;

        return view('member.orders.index', compact(
            'orders',
            'completedOrders',
            'pendingOrders',
            'totalOrderValue',
            'monthlyOrders',
            'averageOrderValue',
            'successRate'
        ));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create(Request $request)
    {
        // Get products with inventory
        $products = Product::with(['vendor', 'inventory'])
            ->where('status', 'active')
            ->whereHas('inventory', function ($query) {
                $query->where('quantity', '>', 0);
            })
            ->orderBy('name')
            ->get();

        // Get vendors
        $vendors = User::where('role', 'vendor')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        // Get active payment methods
        $paymentMethods = \App\Models\PaymentMethod::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Get categories for search filters
        $categories = \App\Models\Category::where('status', 'active')
            ->orderBy('name')
            ->get();

        // Get tax configuration from config
        $taxConfig = [
            'label' => config('tax.label', 'Tax'),
            'default_rate' => config('tax.default_rate', 0),
            'calculation_method' => config('tax.calculation_method', 'percentage'),
            'tax_inclusive' => config('tax.tax_inclusive', false),
            'minimum_order' => config('tax.minimum_order', 0),
            'display' => config('tax.display', [
                'tax_free_message' => 'Tax-free shopping in Bangladesh!'
            ])
        ];

        // Handle reorder functionality
        $reorderData = null;
        if ($request->has('reorder')) {
            $reorderOrderId = $request->get('reorder');
            $existingOrder = Order::with(['items.product'])
                ->where('id', $reorderOrderId)
                ->where('customer_id', Auth::id()) // Ensure user owns the order
                ->first();
            
            if ($existingOrder) {
                $reorderData = [
                    'order' => $existingOrder,
                    'items' => $existingOrder->items->map(function ($item) {
                        // Handle product image similar to home page
                        $productImage = null;
                        if ($item->product) {
                            // First try images array
                            if ($item->product->images) {
                                $images = is_string($item->product->images) ? json_decode($item->product->images, true) : $item->product->images;
                                if (is_array($images) && !empty($images)) {
                                    $image = $images[0];
                                    
                                    // Handle complex image structure with sizes
                                    if (is_array($image) && isset($image['sizes'])) {
                                        // Try medium size first, then other sizes
                                        if (isset($image['sizes']['medium']['storage_url'])) {
                                            $productImage = $image['sizes']['medium']['storage_url'];
                                        } elseif (isset($image['sizes']['medium']['url'])) {
                                            $productImage = $image['sizes']['medium']['url'];
                                        } elseif (isset($image['sizes']['small']['storage_url'])) {
                                            $productImage = $image['sizes']['small']['storage_url'];
                                        } elseif (isset($image['sizes']['small']['url'])) {
                                            $productImage = $image['sizes']['small']['url'];
                                        } elseif (isset($image['sizes']['original']['storage_url'])) {
                                            $productImage = $image['sizes']['original']['storage_url'];
                                        } elseif (isset($image['sizes']['original']['url'])) {
                                            $productImage = $image['sizes']['original']['url'];
                                        }
                                    } elseif (is_string($image)) {
                                        $productImage = asset('storage/' . $image);
                                    } elseif (is_array($image) && isset($image['url'])) {
                                        $productImage = $image['url'];
                                    } elseif (is_array($image) && isset($image['path'])) {
                                        $productImage = asset('storage/' . $image['path']);
                                    }
                                }
                            }
                            
                            // Fallback to single image field
                            if (!$productImage && $item->product->image && $item->product->image !== 'products/product1.jpg') {
                                $productImage = asset('storage/' . $item->product->image);
                            }
                        }
                        
                        return [
                            'product_id' => $item->product_id,
                            'product_name' => $item->product->name ?? 'Product Not Available',
                            'product_image' => $productImage,
                            'product_sku' => $item->product->sku ?? '',
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'total' => $item->quantity * $item->price,
                            'available' => $item->product && $item->product->status == 'active' && 
                                         $item->product->inventory && $item->product->inventory->quantity > 0
                        ];
                    }),
                    'shipping_address' => $existingOrder->shipping_address,
                    'vendor_id' => $existingOrder->vendor_id,
                    'payment_method' => $existingOrder->payment_method
                ];
            }
        }

        // Get delivery charges for client-side calculation
        $deliveryCharges = \App\Models\DeliveryCharge::orderBy('district', 'asc')
            ->orderBy('upazila', 'asc')
            ->orderBy('ward', 'asc')
            ->get()
            ->keyBy(function($charge) {
                return $charge->district . '|' . ($charge->upazila ?? '') . '|' . ($charge->ward ?? '');
            });

        return view('member.orders.create', compact('products', 'vendors', 'taxConfig', 'paymentMethods', 'categories', 'reorderData', 'deliveryCharges'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        // Get the payment method to determine validation rules
        $paymentMethod = \App\Models\PaymentMethod::where('code', $request->payment_method)->first();
        
        // Base validation rules
        $rules = [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|string|exists:payment_methods,code',
            'shipping_address' => 'required|array',
            'shipping_address.first_name' => 'required|string|max:255',
            'shipping_address.last_name' => 'required|string|max:255',
            'shipping_address.address_line_1' => 'required|string|max:255',
            'shipping_address.city' => 'required|string|max:255',
            'shipping_address.district' => 'required|string|max:255',
            'shipping_address.upazila' => 'nullable|string|max:255',
            'shipping_address.ward' => 'nullable|string|max:255',
            'shipping_address.country' => 'required|string|max:2',
            'shipping_address.phone' => 'nullable|string|max:20',
            'shipping_address.postal_code' => 'nullable|string|max:10',
            'shipping_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
        ];

        // Add payment-specific validation rules
        if ($paymentMethod && $paymentMethod->code !== 'cash') {
            $extraFields = $paymentMethod->extra_fields ?? [];
            
            if (isset($extraFields['sender_number']) && $extraFields['sender_number'] === 'required') {
                $rules['sender_number'] = 'required|string|max:20';
            }
            if (isset($extraFields['transaction_id']) && $extraFields['transaction_id'] === 'required') {
                $rules['transaction_id'] = 'required|string|max:100';
            }
            if (isset($extraFields['payment_proof']) && $extraFields['payment_proof'] === 'required') {
                $rules['payment_proof'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120';
            }
            if (isset($extraFields['sender_bank'])) {
                $rules['sender_bank'] = 'required|string|max:100';
            }
            if (isset($extraFields['sender_account'])) {
                $rules['sender_account'] = 'required|string|max:50';
            }
            
            // Payment notes are optional but can be provided
            $rules['payment_notes'] = 'nullable|string|max:500';
        }

        // Validate the request
        $request->validate($rules);

        try {
            DB::beginTransaction();

            // Calculate subtotal and determine vendor
            $subtotal = 0;
            $vendorId = $request->vendor_id; // Start with the requested vendor
            $vendors = collect(); // Track all vendors in this order
            
            foreach ($request->items as $item) {
                $subtotal += $item['price'] * $item['quantity'];
                
                // Get the product to determine its vendor
                $product = Product::find($item['product_id']);
                if ($product && $product->vendor_id) {
                    $vendors->push($product->vendor_id);
                }
            }
            
            // If no vendor was specified, determine from products
            if (!$vendorId && $vendors->isNotEmpty()) {
                // If all products are from the same vendor, use that vendor
                if ($vendors->unique()->count() === 1) {
                    $vendorId = $vendors->first();
                }
                // If multiple vendors, leave vendor_id as null for marketplace order
            }

            // Calculate delivery charge based on district
            $deliveryCharge = $this->calculateDeliveryCharge($request);
            
            // Get amounts
            $shippingAmount = $request->shipping_amount ?? $deliveryCharge;
            $taxAmount = $request->tax_amount ?? 0;
            $discountAmount = $request->discount_amount ?? 0;
            $totalAmount = $subtotal + $shippingAmount + $taxAmount - $discountAmount;

            // Generate order number
            $orderNumber = 'ORD' . date('Ymd') . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT);

            // Determine payment status based on method
            $paymentStatus = 'pending';
            $user = User::find(Auth::id()); // Get full User model instance
            
            if ($request->payment_method === 'cash') {
                // Cash on Delivery - requires 200 TK advance payment as security
                $advancePayment = 200.00;
                $availableBalance = ($user->deposit_wallet ?? 0) + ($user->interest_wallet ?? 0);
                
                if ($availableBalance < $advancePayment) {
                    return back()
                        ->withInput()
                        ->with('error', 'Insufficient balance for Cash on Delivery security deposit. You need ৳' . number_format($advancePayment, 2) . ' in your wallet but have ৳' . number_format($availableBalance, 2) . '. Please add funds to your wallet or choose a different payment method.');
                }
                
                $paymentStatus = 'pending'; // Order remains pending until delivered
            } elseif ($request->payment_method === 'app_balance') {
                // Check if user has sufficient balance using deposit_wallet and interest_wallet (displayed as income wallet)
                $availableBalance = ($user->deposit_wallet ?? 0) + ($user->interest_wallet ?? 0);
                if ($availableBalance >= $totalAmount) {
                    $paymentStatus = 'paid';
                } else {
                    return back()
                        ->withInput()
                        ->with('error', 'Insufficient balance. Your available balance is ৳' . number_format($availableBalance, 2) . ' but order total is ৳' . number_format($totalAmount, 2));
                }
            }

            // Handle payment proof upload
            $paymentProofPath = null;
            $paymentProofData = null;
            if ($request->hasFile('payment_proof')) {
                try {
                    $paymentProofData = $this->uploadCategoryImage($request->file('payment_proof'), 'orders/payment-proofs');
                    $paymentProofPath = $paymentProofData['sizes']['original']['path'] ?? $paymentProofData['filename'];
                } catch (\Exception $e) {
                    return back()->withErrors(['payment_proof' => 'Failed to upload payment proof: ' . $e->getMessage()])->withInput();
                }
            }

            // Prepare payment details
            $paymentDetails = [
                'method' => $request->payment_method,
                'sender_number' => $request->sender_number,
                'receiver_number' => $paymentMethod ? $paymentMethod->account_number : null,
                'transaction_id' => $request->transaction_id,
                'sender_bank' => $request->sender_bank,
                'sender_account' => $request->sender_account,
                'amount' => $totalAmount,
                'fee' => $paymentMethod ? $paymentMethod->calculateFee($totalAmount) : 0,
                'submitted_at' => now()->toISOString()
            ];

            // Create the order
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => Auth::id(),
                'vendor_id' => $vendorId, // Use the determined vendor_id
                'status' => 'pending',
                'payment_status' => $paymentStatus,
                'payment_method' => $request->payment_method,
                'shipping_method' => $request->shipping_method,
                'currency' => 'BDT',
                'subtotal' => $subtotal,
                'shipping_amount' => $shippingAmount,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address ?? $request->shipping_address,
                'payment_details' => $paymentDetails,
                'sender_number' => $request->sender_number,
                'receiver_number' => $paymentMethod ? $paymentMethod->account_number : null,
                'transaction_id' => $request->transaction_id,
                'payment_proof' => $paymentProofPath,
                'payment_proof_data' => $paymentProofData ? json_encode($paymentProofData) : null,
                'payment_notes' => $request->payment_notes,
                'notes' => $request->notes,
                'created_by' => Auth::id()
            ]);

            // Create order items and update inventory
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'vendor_id' => $product->vendor_id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity']
                ]);

                // Update inventory if exists
                $inventory = Inventory::where('product_id', $product->id)->first();
                if ($inventory) {
                    $oldQuantity = $inventory->quantity;
                    $newQuantity = max(0, $oldQuantity - $item['quantity']);
                    
                    $inventory->update([
                        'quantity' => $newQuantity,
                        'reserved_quantity' => ($inventory->reserved_quantity ?? 0) + $item['quantity']
                    ]);

                    // Log inventory movement
                    try {
                        if (class_exists('\App\Models\InventoryMovement')) {
                            InventoryMovement::create([
                                'product_id' => $product->id,
                                'type' => 'sale',
                                'quantity' => -$item['quantity'],
                                'old_quantity' => $oldQuantity,
                                'new_quantity' => $newQuantity,
                                'reference_type' => 'order',
                                'reference_id' => $order->id,
                                'notes' => "Sale via member order #{$orderNumber}",
                                'performed_by' => Auth::id(),
                                'created_by' => Auth::id(),
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::warning("Could not create inventory movement: " . $e->getMessage());
                    }

                    // Check for low stock alerts
                    $lowStockThreshold = $inventory->low_stock_threshold ?? 10;
                    if ($newQuantity <= $lowStockThreshold) {
                        try {
                            if (class_exists('\App\Models\InventoryAlert')) {
                                \App\Models\InventoryAlert::firstOrCreate([
                                    'product_id' => $product->id,
                                    'type' => 'low_stock',
                                    'status' => 'active'
                                ], [
                                    'message' => "Low stock alert: {$product->name} has only {$newQuantity} units remaining",
                                    'threshold_value' => $lowStockThreshold,
                                    'current_value' => $newQuantity,
                                    'created_by' => Auth::id()
                                ]);
                            }
                        } catch (\Exception $e) {
                            Log::error("Failed to create low stock alert: " . $e->getMessage());
                        }
                    }
                }
            }

            // Deduct balance if payment method is app_balance or cash (advance payment)
            if ($request->payment_method === 'app_balance' && $paymentStatus === 'paid') {
                $originalDepositWallet = $user->deposit_wallet ?? 0;
                $originalInterestWallet = $user->interest_wallet ?? 0;
                $availableBalance = $originalDepositWallet + $originalInterestWallet;
                $remainingAmount = $totalAmount;
                
                $deductedFromDeposit = 0;
                $deductedFromInterest = 0;
                
                // Deduct from deposit_wallet first
                if ($user->deposit_wallet > 0 && $remainingAmount > 0) {
                    $deductedFromDeposit = min($user->deposit_wallet, $remainingAmount);
                    $user->deposit_wallet -= $deductedFromDeposit;
                    $remainingAmount -= $deductedFromDeposit;
                }
                
                // Then deduct from interest_wallet if needed (displayed as income wallet to users)
                if ($user->interest_wallet > 0 && $remainingAmount > 0) {
                    $deductedFromInterest = min($user->interest_wallet, $remainingAmount);
                    $user->interest_wallet -= $deductedFromInterest;
                    $remainingAmount -= $deductedFromInterest;
                }
                
                $user->save();
                
                // Log the detailed balance deduction
                Log::info("App balance deduction for order {$orderNumber} - User {$user->id}:", [
                    'order_total' => $totalAmount,
                    'original_deposit_wallet' => $originalDepositWallet,
                    'original_interest_wallet' => $originalInterestWallet,
                    'deducted_from_deposit' => $deductedFromDeposit,
                    'deducted_from_interest' => $deductedFromInterest,
                    'new_deposit_wallet' => $user->deposit_wallet,
                    'new_interest_wallet' => $user->interest_wallet,
                    'total_deducted' => $deductedFromDeposit + $deductedFromInterest,
                    'note' => 'interest_wallet is displayed as Income Wallet to users'
                ]);
                
                // Create transaction record for app balance payment
                try {
                    $transactionId = 'ORDER-' . $orderNumber . '-' . time();
                    $transaction = \App\Models\Transaction::create([
                        'user_id' => $user->id,
                        'transaction_id' => $transactionId,
                        'type' => 'debit',
                        'amount' => -$totalAmount, // Negative amount for deduction
                        'fee' => 0.00,
                        'status' => 'completed',
                        'payment_method' => 'app_balance',
                        'wallet_type' => 'mixed', // From both deposit and income wallets
                        'description' => "Payment for order #{$orderNumber} via app balance",
                        'note' => "Order payment of ৳{$totalAmount} deducted (Deposit: ৳{$deductedFromDeposit}, Income: ৳{$deductedFromInterest})",
                        'metadata' => [
                            'order_id' => $order->id,
                            'order_number' => $orderNumber,
                            'total_amount' => $totalAmount,
                            'deducted_from_deposit' => $deductedFromDeposit,
                            'deducted_from_interest' => $deductedFromInterest,
                            'wallet_balances_before' => [
                                'deposit_wallet' => $originalDepositWallet,
                                'interest_wallet' => $originalInterestWallet
                            ],
                            'wallet_balances_after' => [
                                'deposit_wallet' => $user->deposit_wallet,
                                'interest_wallet' => $user->interest_wallet
                            ]
                        ],
                        'reference_type' => 'order',
                        'reference_id' => $order->id,
                        'processed_by' => Auth::id(),
                        'processed_at' => now()
                    ]);
                    
                    Log::info("App balance payment transaction created", [
                        'transaction_id' => $transaction->transaction_id,
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'amount' => $totalAmount
                    ]);
                } catch (\Exception $e) {
                    Log::error("Failed to create app balance payment transaction", [
                        'error' => $e->getMessage(),
                        'order_id' => $order->id,
                        'user_id' => $user->id
                    ]);
                }
            } elseif ($request->payment_method === 'cash') {
                // Deduct advance payment for Cash on Delivery
                $advancePayment = 200.00;
                $originalDepositWallet = $user->deposit_wallet ?? 0;
                $originalInterestWallet = $user->interest_wallet ?? 0;
                $remainingAmount = $advancePayment;
                
                $deductedFromDeposit = 0;
                $deductedFromInterest = 0;
                
                // Deduct from deposit_wallet first
                if ($user->deposit_wallet > 0 && $remainingAmount > 0) {
                    $deductedFromDeposit = min($user->deposit_wallet, $remainingAmount);
                    $user->deposit_wallet -= $deductedFromDeposit;
                    $remainingAmount -= $deductedFromDeposit;
                }
                
                // Then deduct from interest_wallet if needed
                if ($user->interest_wallet > 0 && $remainingAmount > 0) {
                    $deductedFromInterest = min($user->interest_wallet, $remainingAmount);
                    $user->interest_wallet -= $deductedFromInterest;
                    $remainingAmount -= $deductedFromInterest;
                }
                
                $user->save();
                
                // Store advance payment info in payment details
                $paymentDetails['advance_payment'] = [
                    'amount' => $advancePayment,
                    'deducted_from_deposit' => $deductedFromDeposit,
                    'deducted_from_interest' => $deductedFromInterest,
                    'deducted_at' => now()->toISOString(),
                    'note' => 'Security deposit for Cash on Delivery'
                ];
                
                // Log the advance payment deduction
                Log::info("COD advance payment deducted for order {$orderNumber} - User {$user->id}:", [
                    'advance_payment' => $advancePayment,
                    'original_deposit_wallet' => $originalDepositWallet,
                    'original_interest_wallet' => $originalInterestWallet,
                    'deducted_from_deposit' => $deductedFromDeposit,
                    'deducted_from_interest' => $deductedFromInterest,
                    'new_deposit_wallet' => $user->deposit_wallet,
                    'new_interest_wallet' => $user->interest_wallet,
                    'total_deducted' => $deductedFromDeposit + $deductedFromInterest,
                    'note' => 'Cash on Delivery security deposit'
                ]);
                
                // Create transaction record for COD security deposit
                try {
                    $transactionId = 'COD-' . $orderNumber . '-' . time();
                    $transaction = \App\Models\Transaction::create([
                        'user_id' => $user->id,
                        'transaction_id' => $transactionId,
                        'type' => 'cod_deposit',
                        'amount' => -$advancePayment, // Negative amount for deduction
                        'fee' => 0.00,
                        'status' => 'completed',
                        'payment_method' => 'app_balance',
                        'wallet_type' => 'mixed', // From both deposit and income wallets
                        'description' => "Cash on Delivery security deposit for order #{$orderNumber}",
                        'note' => "Security deposit of ৳{$advancePayment} deducted (Deposit: ৳{$deductedFromDeposit}, Income: ৳{$deductedFromInterest})",
                        'metadata' => [
                            'order_id' => $order->id,
                            'order_number' => $orderNumber,
                            'advance_payment' => $advancePayment,
                            'deducted_from_deposit' => $deductedFromDeposit,
                            'deducted_from_interest' => $deductedFromInterest,
                            'wallet_balances_before' => [
                                'deposit_wallet' => $originalDepositWallet,
                                'interest_wallet' => $originalInterestWallet
                            ],
                            'wallet_balances_after' => [
                                'deposit_wallet' => $user->deposit_wallet,
                                'interest_wallet' => $user->interest_wallet
                            ]
                        ],
                        'reference_type' => 'order',
                        'reference_id' => $order->id,
                        'processed_by' => Auth::id(),
                        'processed_at' => now()
                    ]);
                    
                    Log::info("COD security deposit transaction created", [
                        'transaction_id' => $transaction->transaction_id,
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'amount' => $advancePayment
                    ]);
                } catch (\Exception $e) {
                    Log::error("Failed to create COD security deposit transaction", [
                        'error' => $e->getMessage(),
                        'order_id' => $order->id,
                        'user_id' => $user->id
                    ]);
                }
            }

            DB::commit();

            // Log the successful order creation
            Log::info("Order {$orderNumber} created successfully by member " . Auth::id(), [
                'order_id' => $order->id,
                'payment_status' => $order->payment_status,
                'total_amount' => $order->total_amount,
                'customer_id' => $order->customer_id
            ]);

            // If order is paid, ensure points are allocated by checking after transaction commit
            if ($order->payment_status === 'paid') {
                // Force fresh model instance to ensure latest data
                $freshOrder = Order::find($order->id);
                $customer = User::find($order->customer_id);
                
                Log::info("Order is paid, verifying point allocation", [
                    'order_id' => $freshOrder->id,
                    'order_number' => $freshOrder->order_number,
                    'user_id' => $customer->id,
                    'username' => $customer->username,
                    'payment_status' => $freshOrder->payment_status
                ]);
                
                // Check if points were allocated by looking at point_transactions
                $pointTransactions = \App\Models\PointTransaction::where('user_id', $customer->id)
                    ->where('reference_type', 'purchase')
                    ->whereDate('created_at', today())
                    ->count();
                
                Log::info("Point transactions check", [
                    'user_id' => $customer->id,
                    'today_point_transactions' => $pointTransactions,
                    'order_id' => $freshOrder->id
                ]);
                
                // If no point transactions found, manually trigger the observer
                if ($pointTransactions == 0) {
                    Log::warning("No point transactions found for paid order, manually triggering allocation", [
                        'order_id' => $freshOrder->id
                    ]);
                    
                    try {
                        $observer = app(\App\Observers\OrderObserver::class);
                        $observer->created($freshOrder);
                        
                        Log::info("Manual observer triggered for order", [
                            'order_id' => $freshOrder->id
                        ]);
                    } catch (\Exception $e) {
                        Log::error("Failed to manually trigger observer", [
                            'order_id' => $freshOrder->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }

            return redirect()->route('member.orders.show', $order)
                ->with('success', "Order #{$orderNumber} created successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to create member order: " . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create order. Please try again.');
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        $order->load(['items.product', 'customer']);
        
        // Load related transactions for this order
        $transactions = \App\Models\Transaction::where('reference_type', 'order')
            ->where('reference_id', $order->id)
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('member.orders.show', compact('order', 'transactions'));
    }

    /**
     * Search products via AJAX for order creation
     */
    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');
        $vendorId = $request->get('vendor_id');
        $categoryId = $request->get('category_id');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        
        $products = Product::query()
            ->with(['vendor', 'inventory', 'category'])
            ->where('status', 'active')
            ->when($vendorId, function ($q) use ($vendorId) {
                return $q->where('vendor_id', $vendorId);
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                return $q->where('category_id', $categoryId);
            })
            ->when($minPrice, function ($q) use ($minPrice) {
                return $q->where(function($query) use ($minPrice) {
                    $query->where('sale_price', '>=', $minPrice)
                          ->orWhere(function($q) use ($minPrice) {
                              $q->whereNull('sale_price')
                                ->where('price', '>=', $minPrice);
                          });
                });
            })
            ->when($maxPrice, function ($q) use ($maxPrice) {
                return $q->where(function($query) use ($maxPrice) {
                    $query->where('sale_price', '<=', $maxPrice)
                          ->orWhere(function($q) use ($maxPrice) {
                              $q->whereNull('sale_price')
                                ->where('price', '<=', $maxPrice);
                          });
                });
            })
            ->when($query, function ($q) use ($query) {
                return $q->where(function ($subQuery) use ($query) {
                    $subQuery->where('name', 'like', "%{$query}%")
                             ->orWhere('sku', 'like', "%{$query}%")
                             ->orWhere('description', 'like', "%{$query}%")
                             ->orWhereHas('category', function($catQuery) use ($query) {
                                 $catQuery->where('name', 'like', "%{$query}%");
                             });
                });
            })
            ->whereHas('inventory', function ($q) {
                $q->where('quantity', '>', 0);
            })
            ->orderBy('name')
            ->limit(50)
            ->get();

        return response()->json([
            'products' => $products->map(function ($product) {
                $finalPrice = $product->sale_price ?? $product->price;
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'sale_price' => $product->sale_price,
                    'final_price' => $finalPrice,
                    'category_name' => $product->category->name ?? 'N/A',
                    'vendor_name' => $product->vendor->name ?? 'N/A',
                    'stock_quantity' => $product->inventory->quantity ?? 0,
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                    'description' => $product->description
                ];
            }),
            'total_found' => $products->count()
        ]);
    }

    /**
     * Get product details for order creation
     */
    public function getProductDetails(Request $request, $productId)
    {
        $product = Product::with(['vendor', 'inventory'])
            ->where('id', $productId)
            ->where('status', 'active')
            ->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->price,
            'sale_price' => $product->sale_price,
            'vendor_name' => $product->vendor->name ?? 'N/A',
            'stock_quantity' => $product->inventory->quantity ?? 0,
            'image' => $product->image ? asset('storage/' . $product->image) : null,
            'description' => $product->description
        ]);
    }

    /**
     * Get payment method details for dynamic form rendering
     */
    public function getPaymentMethodDetails(Request $request, $code)
    {
        $paymentMethod = \App\Models\PaymentMethod::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$paymentMethod) {
            return response()->json(['error' => 'Payment method not found'], 404);
        }

        $response = [
            'code' => $paymentMethod->code,
            'name' => $paymentMethod->name,
            'type' => $paymentMethod->type,
            'description' => $paymentMethod->description,
            'account_number' => $paymentMethod->account_number,
            'account_name' => $paymentMethod->account_name,
            'bank_name' => $paymentMethod->bank_name,
            'branch_name' => $paymentMethod->branch_name,
            'routing_number' => $paymentMethod->routing_number,
            'logo_url' => $paymentMethod->logo_url,
            'requires_verification' => $paymentMethod->requires_verification,
            'min_amount' => $paymentMethod->min_amount,
            'max_amount' => $paymentMethod->max_amount,
            'fee_percentage' => $paymentMethod->fee_percentage,
            'fee_fixed' => $paymentMethod->fee_fixed,
            'extra_fields' => $paymentMethod->extra_fields,
            'instructions' => $paymentMethod->instructions
        ];

        // Add balance information for app_balance and cash payment methods
        if ($code === 'app_balance' || $code === 'cash') {
            $user = User::find(Auth::id());
            if ($user) {
                $totalBalance = ($user->deposit_wallet ?? 0) + ($user->interest_wallet ?? 0);
                $response['user_balance'] = [
                    'deposit_wallet' => $user->deposit_wallet ?? 0,
                    'interest_wallet' => $user->interest_wallet ?? 0, // Backend uses interest_wallet
                    'total_available' => $totalBalance,
                    'formatted' => [
                        'deposit_wallet' => '৳' . number_format($user->deposit_wallet ?? 0, 2),
                        'income_wallet' => '৳' . number_format($user->interest_wallet ?? 0, 2), // Display as Income Wallet
                        'total_available' => '৳' . number_format($totalBalance, 2)
                    ]
                ];
                
                // Add specific message for cash on delivery
                if ($code === 'cash') {
                    $advancePayment = 200.00;
                    $response['advance_payment_required'] = $advancePayment;
                    $response['has_sufficient_balance'] = $totalBalance >= $advancePayment;
                    $response['formatted']['advance_payment'] = '৳' . number_format($advancePayment, 2);
                }
            }
        }

        return response()->json($response);
    }

    /**
     * Get delivery charge for specific location (AJAX endpoint)
     */
    public function getDeliveryCharge(Request $request)
    {
        $district = $request->get('district');
        $upazila = $request->get('upazila');
        $ward = $request->get('ward');
        
        Log::info('Delivery charge request received', [
            'district' => $district,
            'upazila' => $upazila,
            'ward' => $ward,
            'user_id' => Auth::id()
        ]);
        
        if (!$district) {
            Log::warning('Delivery charge request missing district');
            return response()->json([
                'error' => 'District is required'
            ], 422);
        }
        
        try {
            // Find delivery charge using the DeliveryCharge model
            $deliveryCharge = \App\Models\DeliveryCharge::findChargeForLocation($district, $upazila, $ward);
            
            $response = [
                'charge' => $deliveryCharge->charge,
                'formatted_charge' => '৳' . number_format($deliveryCharge->charge, 2),
                'estimated_delivery_time' => $deliveryCharge->estimated_delivery_time,
                'location' => [
                    'district' => $district,
                    'upazila' => $upazila,
                    'ward' => $ward
                ]
            ];
            
            Log::info('Delivery charge response', $response);
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            Log::error('Error calculating delivery charge', [
                'error' => $e->getMessage(),
                'district' => $district,
                'upazila' => $upazila,
                'ward' => $ward
            ]);
            
            return response()->json([
                'error' => 'Failed to calculate delivery charge',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all delivery charges (AJAX endpoint for caching)
     */
    public function getAllDeliveryCharges(Request $request)
    {
        try {
            $deliveryCharges = \App\Models\DeliveryCharge::active()
                ->select('district', 'upazila', 'ward', 'charge', 'estimated_delivery_time')
                ->orderBy('district')
                ->orderBy('upazila')
                ->orderBy('ward')
                ->get();
            
            // Group by district for easier JavaScript consumption
            $grouped = [];
            foreach ($deliveryCharges as $charge) {
                $district = $charge->district;
                if (!isset($grouped[$district])) {
                    $grouped[$district] = [];
                }
                
                $key = $charge->upazila ? ($charge->ward ? "{$charge->upazila}_{$charge->ward}" : $charge->upazila) : 'district_default';
                $grouped[$district][$key] = [
                    'charge' => $charge->charge,
                    'formatted_charge' => '৳' . number_format($charge->charge, 2),
                    'estimated_delivery_time' => $charge->estimated_delivery_time
                ];
            }
            
            return response()->json([
                'charges' => $grouped,
                'success' => true
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching all delivery charges', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'error' => 'Failed to fetch delivery charges',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate delivery charge based on district, upazila, and ward
     */
    private function calculateDeliveryCharge($request)
    {
        // Get location data from shipping address
        $district = $request->input('shipping_address.district');
        $upazila = $request->input('shipping_address.upazila');
        $ward = $request->input('shipping_address.ward');
        
        if (!$district) {
            return 100.00; // Default charge if no district provided
        }
        
        // Find delivery charge using the DeliveryCharge model
        $deliveryCharge = \App\Models\DeliveryCharge::findChargeForLocation($district, $upazila, $ward);
        
        return $deliveryCharge->charge;
    }
}
