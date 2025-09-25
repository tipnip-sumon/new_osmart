<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;

class CollectionsController extends Controller
{
    /**
     * Display all collections
     */
    public function index()
    {
        // Get all active collections with product counts
        $collections = Collection::withCount(['products' => function ($query) {
                $query->where('status', 'active');
            }])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Get featured products for hero section
        $featuredProducts = Product::where('status', 'active')
            ->where('is_featured', true)
            ->with('category')
            ->take(8)
            ->get();

        return view('collections.index', compact('collections', 'featuredProducts'));
    }

    /**
     * Display products in a specific collection
     */
    public function show(Collection $collection)
    {
        // Ensure collection is active
        if (!$collection->is_active) {
            abort(404);
        }

        // Get products in this collection with sorting and pagination
        $query = $collection->products()
            ->where('status', 'active')
            ->with('category');

        // Apply sorting
        $sort = request('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_low':
                $query->orderBy('sale_price', 'asc')->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('sale_price', 'desc')->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Pagination
        $perPage = request('per_page', 12);
        $products = $query->paginate($perPage);

        return view('collections.show', compact('collection', 'products'));
    }

    /**
     * Get products for a collection via AJAX
     */
    public function getProducts(Collection $collection)
    {
        $products = $collection->products()
            ->where('status', 'active')
            ->with('category')
            ->paginate(12);

        return response()->json([
            'success' => true,
            'products' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
                'per_page' => $products->perPage(),
            ]
        ]);
    }
}
