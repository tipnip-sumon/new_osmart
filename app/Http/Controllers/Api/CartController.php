<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Get current prices for products
     */
    public function getPrices(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_ids' => 'required|array',
            'product_ids.*' => 'required|integer|exists:products,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid product IDs',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $products = Product::whereIn('id', $request->product_ids)
                              ->where('status', 'active')
                              ->get(['id', 'name', 'price', 'status']);

            $priceData = [];
            foreach ($products as $product) {
                $priceData[$product->id] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'status' => $product->status
                ];
            }

            return response()->json([
                'success' => true,
                'prices' => $priceData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch product prices'
            ], 500);
        }
    }

    /**
     * Update cart with current prices
     */
    public function updatePrices(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_items' => 'required|array',
            'cart_items.*.product_id' => 'required|integer|exists:products,id',
            'cart_items.*.quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid cart data',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $cartItems = $request->cart_items;
            $productIds = array_column($cartItems, 'product_id');
            
            $products = Product::whereIn('id', $productIds)
                              ->where('status', 'active')
                              ->get()
                              ->keyBy('id');

            $updatedCart = [];
            $priceChanges = [];

            foreach ($cartItems as $item) {
                $product = $products->get($item['product_id']);
                
                if (!$product) {
                    return response()->json([
                        'success' => false,
                        'message' => "Product ID {$item['product_id']} is no longer available"
                    ], 422);
                }

                $oldPrice = isset($item['price']) ? floatval($item['price']) : 0;
                $newPrice = $product->price;
                $quantity = intval($item['quantity']);

                // Track price changes
                if (abs($oldPrice - $newPrice) > 0.01) {
                    $priceChanges[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'old_price' => $oldPrice,
                        'new_price' => $newPrice,
                        'difference' => $newPrice - $oldPrice
                    ];
                }

                $updatedCart[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $newPrice,
                    'quantity' => $quantity,
                    'total' => $newPrice * $quantity,
                    'size' => $item['size'] ?? '',
                    'color' => $item['color'] ?? '',
                    'image' => $item['image'] ?? 'products/default.jpg' // Keep existing image or use default
                ];
            }

            return response()->json([
                'success' => true,
                'updated_cart' => $updatedCart,
                'price_changes' => $priceChanges,
                'has_changes' => count($priceChanges) > 0
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart prices'
            ], 500);
        }
    }
}
