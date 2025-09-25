<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ShippingCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ShippingController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingCalculationService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    /**
     * Calculate shipping costs for different methods
     */
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'cart_items' => 'required|array',
            'cart_items.*.product_id' => 'required|integer',
            'cart_items.*.quantity' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0'
        ]);

        // Keep cart items as arrays instead of converting to objects
        $cartItems = $request->cart_items;

        $subtotal = floatval($request->subtotal);
        $userId = Auth::id() ?? 1;

        $shippingOptions = [];
        $shippingMethods = config('shipping.options');

        foreach ($shippingMethods as $method => $config) {
            $cost = $this->shippingService->calculateShippingCost(
                $method, 
                $cartItems, 
                $subtotal, 
                $userId
            );

            $shippingOptions[$method] = [
                'name' => $config['name'],
                'description' => $config['description'],
                'cost' => $cost,
                'currency' => config('shipping.currency'),
                'is_free' => $cost == 0,
                'delivery_time' => $config['delivery_time'] ?? null
            ];
        }

        return response()->json([
            'success' => true,
            'shipping_options' => $shippingOptions,
            'currency_symbol' => config('shipping.currency')
        ]);
    }

    /**
     * Check free shipping eligibility for a specific method
     */
    public function checkFreeShipping(Request $request)
    {
        $request->validate([
            'shipping_method' => 'required|string',
            'cart_items' => 'required|array',
            'subtotal' => 'required|numeric|min:0'
        ]);

        // Keep cart items as arrays instead of converting to objects
        $cartItems = $request->cart_items;

        $subtotal = floatval($request->subtotal);
        $userId = Auth::id() ?? 1;
        $shippingMethod = $request->shipping_method;

        $isEligible = $this->shippingService->isEligibleForFreeShipping(
            $shippingMethod, 
            $cartItems, 
            $subtotal, 
            $userId
        );

        $message = $isEligible ? 
            $this->shippingService->getFreeShippingMessage($shippingMethod, true) :
            $this->shippingService->getFreeShippingMessage($shippingMethod, false);

        // Get amount needed for free shipping if not eligible
        $amountNeeded = null;
        if (!$isEligible) {
            $config = config('shipping.free_shipping.by_location');
            if ($shippingMethod === 'inside_dhaka' && isset($config['inside_dhaka']['minimum_order'])) {
                $amountNeeded = max(0, $config['inside_dhaka']['minimum_order'] - $subtotal);
            } elseif ($shippingMethod === 'outside_dhaka' && isset($config['outside_dhaka']['minimum_order'])) {
                $amountNeeded = max(0, $config['outside_dhaka']['minimum_order'] - $subtotal);
            }
        }

        return response()->json([
            'success' => true,
            'is_eligible' => $isEligible,
            'message' => $message,
            'amount_needed' => $amountNeeded,
            'currency_symbol' => config('shipping.currency')
        ]);
    }

    /**
     * Get shipping configuration for frontend
     */
    public function getShippingConfig()
    {
        $config = config('shipping');
        
        return response()->json([
            'success' => true,
            'currency' => $config['currency'],
            'options' => $config['options'],
            'free_shipping' => [
                'enabled' => $config['free_shipping']['enabled'],
                'by_location' => $config['free_shipping']['by_location']
            ]
        ]);
    }

    public function calculateShippingCost(Request $request)
    {
        try {
            $district = $request->district;
            $upazila = $request->upazila;
            $subtotal = $request->subtotal;

            // Get shipping configuration
            $shippingConfig = config('shipping');
            $freeShippingThreshold = $shippingConfig['free_shipping_threshold'] ?? 1000;
            $defaultShippingCost = $shippingConfig['default_cost'] ?? 60;

            // Check if eligible for free shipping
            $isFreeShipping = $subtotal >= $freeShippingThreshold;
            
            if ($isFreeShipping) {
                session(['shipping_cost' => 0]);
                return response()->json([
                    'shipping_cost' => 0,
                    'is_free_shipping' => true,
                    'message' => 'Free shipping applied'
                ]);
            }

            // Get location-specific shipping cost
            $deliveryCharge = DB::table('delivery_charges')
                ->where('district', $district)
                ->where('upazila', $upazila)
                ->first();

            $shippingCost = $deliveryCharge ? $deliveryCharge->charge : $defaultShippingCost;
            
            session(['shipping_cost' => $shippingCost]);

            return response()->json([
                'shipping_cost' => $shippingCost,
                'is_free_shipping' => false,
                'location' => $district . ', ' . $upazila
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to calculate shipping cost',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
