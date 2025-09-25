<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    /**
     * Live search for categories, subcategories, brands, and products
     */
    public function liveSearch(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Query too short'
            ]);
        }
        
        $results = [
            'categories' => [],
            'brands' => [],
            'products' => []
        ];
        
        try {
            // Search Categories
            $results['categories'] = Category::where('name', 'LIKE', "%{$query}%")
                ->where('status', 'active')
                ->withCount('products')
                ->select('id', 'name', 'slug', 'image')
                ->limit(5)
                ->get()
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'image' => $category->image,
                        'products_count' => $category->products_count
                    ];
                });
            
            // Search Brands
            $results['brands'] = Brand::where('name', 'LIKE', "%{$query}%")
                ->where('status', 'active')
                ->withCount('products')
                ->select('id', 'name', 'slug', 'image')
                ->limit(5)
                ->get()
                ->map(function ($brand) {
                    return [
                        'id' => $brand->id,
                        'name' => $brand->name,
                        'slug' => $brand->slug,
                        'image' => $brand->image,
                        'products_count' => $brand->products_count
                    ];
                });
            
            // Search Products
            $results['products'] = Product::where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%")
                      ->orWhere('short_description', 'LIKE', "%{$query}%")
                      ->orWhere('sku', 'LIKE', "%{$query}%");
                })
                ->where('status', 'active')
                ->with(['category:id,name', 'brand:id,name'])
                ->select('id', 'name', 'slug', 'image', 'price', 'sale_price', 'category_id', 'brand_id')
                ->limit(8)
                ->get()
                ->map(function ($product) {
                    $price = $product->sale_price && $product->sale_price > 0 
                           ? $product->sale_price 
                           : $product->price;
                    
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'image' => $product->image ? asset('storage/' . $product->image) : null,
                        'price' => $price,
                        'original_price' => $product->price,
                        'sale_price' => $product->sale_price,
                        'category_name' => $product->category->name ?? 'Uncategorized',
                        'brand_name' => $product->brand->name ?? null
                    ];
                });
            
            return response()->json([
                'success' => true,
                'results' => $results,
                'query' => $query,
                'total_results' => collect($results)->sum(function ($items) {
                    return is_array($items) ? count($items) : $items->count();
                })
            ]);
            
        } catch (\Exception $e) {
            Log::error('Live search error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Search temporarily unavailable',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * Full search results page
     */
    public function search(Request $request)
    {
        $query = $request->get('search', '');
        $category = $request->get('category');
        $subcategory = $request->get('subcategory');
        $brand = $request->get('brand');
        $sort = $request->get('sort', 'relevance');
        $perPage = 24;
        
        $products = Product::query();
        
        // Apply search filters
        if ($query) {
            $products->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('short_description', 'LIKE', "%{$query}%")
                  ->orWhere('sku', 'LIKE', "%{$query}%");
            });
        }
        
        if ($category) {
            $products->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }
        
        if ($subcategory) {
            $products->whereHas('subcategory', function ($q) use ($subcategory) {
                $q->where('slug', $subcategory);
            });
        }
        
        if ($brand) {
            $products->whereHas('brand', function ($q) use ($brand) {
                $q->where('slug', $brand);
            });
        }
        
        // Apply sorting
        switch ($sort) {
            case 'price_low':
                $products->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_high':
                $products->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'name':
                $products->orderBy('name', 'ASC');
                break;
            case 'newest':
                $products->orderBy('created_at', 'DESC');
                break;
            default: // relevance
                if ($query) {
                    $products->orderByRaw("CASE 
                        WHEN name LIKE ? THEN 1
                        WHEN name LIKE ? THEN 2
                        WHEN description LIKE ? THEN 3
                        ELSE 4
                    END", ["%{$query}%", "{$query}%", "%{$query}%"]);
                } else {
                    $products->orderBy('created_at', 'DESC');
                }
                break;
        }
        
        $products = $products->where('status', 'active')
            ->with(['category', 'brand'])
            ->paginate($perPage);
        
        // Get filter options for sidebar
        $categories = Category::where('status', 'active')
            ->withCount('products')
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get();
            
        $brands = Brand::where('status', 'active')
            ->withCount('products')
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get();
        
        return view('member.search.results', compact(
            'products', 
            'categories', 
            'brands', 
            'query', 
            'category', 
            'subcategory', 
            'brand', 
            'sort'
        ));
    }
}
