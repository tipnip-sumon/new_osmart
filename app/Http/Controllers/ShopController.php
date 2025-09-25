<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ShopController extends Controller
{
    /**
     * Display products in grid view
     */
    public function grid(Request $request)
    {
        $query = $this->buildProductQuery($request);
        $products = $query->paginate(12);
        $categories = Category::active()->get();
        $brands = Brand::active()->get();
        
        return view('shop.grid', compact('products', 'categories', 'brands'));
    }

    /**
     * Display products in list view
     */
    public function list(Request $request)
    {
        $query = $this->buildProductQuery($request);
        $products = $query->paginate(10);
        $categories = Category::active()->get();
        $brands = Brand::active()->get();
        
        // Get price ranges for filter
        $priceRanges = [
            ['min' => 0, 'max' => 100, 'label' => '৳0 - ৳100'],
            ['min' => 100, 'max' => 500, 'label' => '৳100 - ৳500'],
            ['min' => 500, 'max' => 1000, 'label' => '৳500 - ৳1,000'],
            ['min' => 1000, 'max' => 5000, 'label' => '৳1,000 - ৳5,000'],
            ['min' => 5000, 'max' => null, 'label' => '৳5,000+']
        ];
        
        return view('shop.list', compact('products', 'categories', 'brands', 'priceRanges'));
    }

    /**
     * Build product query with filters
     */
    private function buildProductQuery(Request $request)
    {
        $query = Product::with(['category', 'brand', 'vendor'])
            ->where('is_active', true)
            ->where('status', 'active');

        // Search filter
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function (Builder $q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('short_description', 'like', "%{$searchTerm}%")
                  ->orWhere('sku', 'like', "%{$searchTerm}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Brand filter
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->input('brand'));
        }

        // Price range filter
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        // Stock filter
        if ($request->filled('in_stock') && $request->boolean('in_stock')) {
            $query->where('in_stock', true)
                  ->where('stock_quantity', '>', 0);
        }

        // Featured filter
        if ($request->filled('featured') && $request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        // Sorting
        $sortBy = $request->input('sort', 'featured');
        switch ($sortBy) {
            case 'price-low':
                $query->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'featured':
            default:
                $query->orderBy('is_featured', 'desc')
                      ->orderBy('sort_order', 'asc')
                      ->orderBy('created_at', 'desc');
                break;
        }

        return $query;
    }

    /**
     * Get categories for AJAX requests
     */
    public function getCategories()
    {
        $categories = Category::active()
            ->select('id', 'name', 'slug')
            ->orderBy('name')
            ->get();
            
        return response()->json($categories);
    }

    /**
     * Get brands for AJAX requests
     */
    public function getBrands()
    {
        $brands = Brand::active()
            ->select('id', 'name', 'slug')
            ->orderBy('name')
            ->get();
            
        return response()->json($brands);
    }

    /**
     * Search products via AJAX
     */
    public function search(Request $request)
    {
        $query = $this->buildProductQuery($request);
        $products = $query->take(20)->get();
        
        return response()->json([
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'sale_price' => $product->sale_price,
                    'image' => $product->image,
                    'category' => $product->category?->name,
                    'brand' => $product->brand?->name,
                    'in_stock' => $product->in_stock,
                    'stock_quantity' => $product->stock_quantity,
                    'average_rating' => $product->average_rating,
                    'reviews_count' => $product->reviews_count,
                ];
            })
        ]);
    }
}
