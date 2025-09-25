<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of all categories
     */
    public function index()
    {
        // Get all active parent categories with their children and product counts
        $categories = Category::active()
            ->parent()
            ->with(['children' => function($query) {
                $query->active()->orderBy('sort_order');
            }])
            ->withCount(['products' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('sort_order')
            ->get();

        // Get all categories with product counts for grid display
        $allCategories = Category::active()
            ->withCount(['products' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('categories.index', compact('categories', 'allCategories'));
    }

    /**
     * Display the specified category with its products
     */
    public function show($slug)
    {
        // Get category by slug
        $category = Category::where('slug', $slug)->active()->first();
        
        if (!$category) {
            abort(404, 'Category not found');
        }

        // Get subcategories if this is a parent category
        $subcategories = $category->children()->active()->get();

        // Get products in this category and its subcategories
        $categoryIds = [$category->id];
        if ($subcategories->count() > 0) {
            $categoryIds = array_merge($categoryIds, $subcategories->pluck('id')->toArray());
        }

        $productsQuery = Product::whereIn('category_id', $categoryIds)
            ->where('status', 'active')
            ->with(['category', 'variants']);

        // Apply filters if provided
        $request = request();
        
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $productsQuery->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $productsQuery->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $productsQuery->orderBy('created_at', 'desc');
                    break;
                case 'popular':
                    $productsQuery->orderBy('view_count', 'desc');
                    break;
                default:
                    $productsQuery->orderBy('name', 'asc');
            }
        } else {
            $productsQuery->orderBy('name', 'asc');
        }

        $products = $productsQuery->paginate(12);

        // Breadcrumb data
        $breadcrumbs = $this->buildBreadcrumbs($category);

        return view('categories.show', compact('category', 'subcategories', 'products', 'breadcrumbs'));
    }

    /**
     * Build breadcrumbs for category navigation
     */
    private function buildBreadcrumbs($category)
    {
        $breadcrumbs = [];
        $current = $category;

        while ($current) {
            array_unshift($breadcrumbs, [
                'name' => $current->name,
                'slug' => $current->slug,
                'url' => route('categories.show', $current->slug)
            ]);
            $current = $current->parent;
        }

        return $breadcrumbs;
    }

    /**
     * API endpoint to get categories for AJAX requests
     */
    public function getCategoriesApi()
    {
        $categories = Category::active()
            ->with(['children' => function($query) {
                $query->active()->orderBy('sort_order');
            }])
            ->withCount(['products' => function($query) {
                $query->where('status', 'active');
            }])
            ->parent()
            ->orderBy('sort_order')
            ->get();

        return response()->json($categories);
    }
}
