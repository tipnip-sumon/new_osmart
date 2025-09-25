<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the wishlist page
     */
    public function index()
    {
        if (Auth::check()) {
            // For authenticated users, get wishlist from database
            $wishlistItems = Auth::user()->wishlists()->with('product')->get();
            $products = $wishlistItems->map(function($item) {
                return $item->product;
            })->filter();
        } else {
            // For guests, get wishlist from session
            $wishlistIds = session()->get('wishlist', []);
            $products = Product::whereIn('id', $wishlistIds)->get();
        }

        return view('wishlist.grid', compact('products'));
    }

    /**
     * Display the wishlist in list view
     */
    public function list()
    {
        if (Auth::check()) {
            // For authenticated users, get wishlist from database
            $wishlistItems = Auth::user()->wishlists()->with('product')->get();
            $products = $wishlistItems->map(function($item) {
                return $item->product;
            })->filter();
        } else {
            // For guests, get wishlist from session
            $wishlistIds = session()->get('wishlist', []);
            $products = Product::whereIn('id', $wishlistIds)->get();
        }

        return view('wishlist.list', compact('products'));
    }

    /**
     * Add product to wishlist
     */
    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        
        if (!$productId) {
            return response()->json(['success' => false, 'message' => 'Product ID is required']);
        }

        // Check if product exists
        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }

        if (Auth::check()) {
            // For authenticated users, store in database
            $exists = Wishlist::where('user_id', Auth::id())
                            ->where('product_id', $productId)
                            ->exists();
            
            if ($exists) {
                return response()->json(['success' => false, 'message' => 'Product already in wishlist']);
            }

            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId
            ]);
        } else {
            // For guests, store in session
            $wishlist = session()->get('wishlist', []);
            
            if (in_array($productId, $wishlist)) {
                return response()->json(['success' => false, 'message' => 'Product already in wishlist']);
            }

            $wishlist[] = $productId;
            session()->put('wishlist', $wishlist);
        }

        return response()->json(['success' => true, 'message' => 'Product added to wishlist']);
    }

    /**
     * Remove product from wishlist
     */
    public function remove(Request $request)
    {
        $productId = $request->input('product_id');
        
        if (!$productId) {
            return response()->json(['success' => false, 'message' => 'Product ID is required']);
        }

        if (Auth::check()) {
            // For authenticated users, remove from database
            Wishlist::where('user_id', Auth::id())
                   ->where('product_id', $productId)
                   ->delete();
        } else {
            // For guests, remove from session
            $wishlist = session()->get('wishlist', []);
            $wishlist = array_filter($wishlist, function($id) use ($productId) {
                return $id != $productId;
            });
            session()->put('wishlist', array_values($wishlist));
        }

        return response()->json(['success' => true, 'message' => 'Product removed from wishlist']);
    }

    /**
     * Toggle product in wishlist
     */
    public function toggle($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }

        if (Auth::check()) {
            // For authenticated users
            $wishlistItem = Wishlist::where('user_id', Auth::id())
                                  ->where('product_id', $id)
                                  ->first();
            
            if ($wishlistItem) {
                $wishlistItem->delete();
                $message = 'Product removed from wishlist';
                $action = 'removed';
            } else {
                Wishlist::create([
                    'user_id' => Auth::id(),
                    'product_id' => $id
                ]);
                $message = 'Product added to wishlist';
                $action = 'added';
            }
        } else {
            // For guests
            $wishlist = session()->get('wishlist', []);
            
            if (in_array($id, $wishlist)) {
                $wishlist = array_filter($wishlist, function($productId) use ($id) {
                    return $productId != $id;
                });
                session()->put('wishlist', array_values($wishlist));
                $message = 'Product removed from wishlist';
                $action = 'removed';
            } else {
                $wishlist[] = $id;
                session()->put('wishlist', $wishlist);
                $message = 'Product added to wishlist';
                $action = 'added';
            }
        }

        return response()->json([
            'success' => true, 
            'message' => $message,
            'action' => $action
        ]);
    }

    /**
     * Clear entire wishlist
     */
    public function clear()
    {
        if (Auth::check()) {
            // For authenticated users, clear database wishlist
            Auth::user()->wishlists()->delete();
        } else {
            // For guests, clear session wishlist
            session()->forget('wishlist');
        }

        return response()->json(['success' => true, 'message' => 'Wishlist cleared successfully']);
    }

    /**
     * Get wishlist count
     */
    public function count()
    {
        if (Auth::check()) {
            $count = Auth::user()->wishlists()->count();
        } else {
            $wishlist = session()->get('wishlist', []);
            $count = count($wishlist);
        }

        return response()->json(['count' => $count]);
    }
}
