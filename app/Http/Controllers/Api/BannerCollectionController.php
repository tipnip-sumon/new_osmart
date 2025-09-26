<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BannerCollection;
use Illuminate\Http\Request;

class BannerCollectionController extends Controller
{
    /**
     * Get active banner collections for frontend
     */
    public function index()
    {
        try {
            $bannerCollections = BannerCollection::active()
                ->ordered()
                ->get()
                ->map(function ($banner) {
                    return [
                        'id' => $banner->id,
                        'title' => $banner->title,
                        'description' => $banner->description,
                        'button_text' => $banner->button_text,
                        'button_url' => $banner->button_url,
                        'image_url' => $banner->image_url,
                        'show_countdown' => $banner->show_countdown,
                        'is_countdown_active' => $banner->is_countdown_active,
                        'countdown_timer' => $banner->countdown_timer,
                        'time_remaining' => $banner->time_remaining,
                        'background_color' => $banner->background_color,
                        'text_color' => $banner->text_color
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $bannerCollections
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch banner collections',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific banner collection
     */
    public function show($id)
    {
        try {
            $bannerCollection = BannerCollection::active()->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $bannerCollection->id,
                    'title' => $bannerCollection->title,
                    'description' => $bannerCollection->description,
                    'button_text' => $bannerCollection->button_text,
                    'button_url' => $bannerCollection->button_url,
                    'image_url' => $bannerCollection->image_url,
                    'show_countdown' => $bannerCollection->show_countdown,
                    'is_countdown_active' => $bannerCollection->is_countdown_active,
                    'countdown_timer' => $bannerCollection->countdown_timer,
                    'time_remaining' => $bannerCollection->time_remaining,
                    'background_color' => $bannerCollection->background_color,
                    'text_color' => $bannerCollection->text_color
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Banner collection not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}