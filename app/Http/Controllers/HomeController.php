<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;
use App\Models\User;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        // Get hero banners from database
        $heroBanners = Banner::where('status', 'active')
            ->where('type', 'hero')
            ->orderBy('sort_order')
            ->limit(5)
            ->get()
            ->map(function ($banner) {
                return (object) [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'subtitle' => $banner->subtitle,
                    'description' => $banner->description,
                    'image' => $banner->image ?: '1.jpg',
                    'cta_text' => $banner->cta_text ?: 'Shop Now',
                    'cta_link' => $banner->cta_link ?: '/shop'
                ];
            });

        // No fallback data - only show real banners

        // Get real categories from database (only categories with products)
        $categories = Category::where('status', 'active')
            ->withCount(['products' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->limit(8)
            ->get();

        // No fallback data - only show real categories

        // Get featured products from database
        $featuredProducts = Product::where('status', 'active')
            ->where('is_featured', true)
            ->with(['brand', 'category', 'vendor'])
            ->limit(8)
            ->get();

        // No fallback products - only show real featured products

        // Get best selling products (for Weekly Best Sellers section)
        $bestSellingProducts = Product::where('status', 'active')
            ->with(['brand', 'category', 'vendor'])
            ->withCount(['orderItems as sales_count' => function($query) {
                $query->whereHas('order', function($q) {
                    $q->where('status', 'completed');
                });
            }])
            ->orderBy('sales_count', 'desc')
            ->limit(6)
            ->get();

        // No fallback - only show real best selling products

        // Get flash sale products (products on sale)
        $flashSaleProducts = Product::where('status', 'active')
            ->whereNotNull('sale_price')
            ->where('sale_price', '<', DB::raw('price'))
            ->with(['category', 'vendor'])
            ->limit(6)
            ->get();

        // No fallback flash sale products - only show real sale products

        // Get top vendors
        $topVendors = User::where('role', 'vendor')
            ->where('status', 'active')
            ->withCount('products')
            ->having('products_count', '>', 0)
            ->orderBy('products_count', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($vendor) {
                return (object) [
                    'id' => $vendor->id,
                    'name' => $vendor->shop_name ?: $vendor->name,
                    'slug' => Str::slug($vendor->shop_name ?: $vendor->name),
                    'image' => $vendor->avatar ?: null,
                    'products_count' => $vendor->products_count,
                    'rating' => $vendor->average_rating ?? 0,
                    'reviews_count' => $vendor->review_count ?? 0
                ];
            });

        // Get collections from database
        $collections = Collection::where('status', 'active')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        // Pass heroBanners as banners for the view
        return view('home', [
            'banners' => $heroBanners,
            'categories' => $categories, 
            'flashSaleProducts' => $flashSaleProducts,
            'topVendors' => $topVendors,
            'featuredProducts' => $featuredProducts,
            'bestSellingProducts' => $bestSellingProducts,
            'collections' => $collections
        ]);
    }
}
