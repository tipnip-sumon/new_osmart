<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class BannerController extends Controller
{
    use HandlesImageUploads;
    /**
     * Display a listing of banners.
     */
    public function index(Request $request)
    {
        try {
            $banners = $this->getBannersQuery();
            
            // Apply filters
            if ($request->filled('position')) {
                $banners = $banners->where('position', $request->position);
            }
            
            if ($request->filled('status')) {
                $banners = $banners->where('status', $request->status);
            }
            
            if ($request->filled('type')) {
                $banners = $banners->where('type', $request->type);
            }
            
            if ($request->filled('date_from')) {
                $banners = $banners->where('start_date', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $banners = $banners->where('end_date', '<=', $request->date_to);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $banners = $banners->where(function($query) use ($search) {
                    $query->where('title', 'LIKE', "%{$search}%")
                          ->orWhere('description', 'LIKE', "%{$search}%")
                          ->orWhere('link_text', 'LIKE', "%{$search}%");
                });
            }

            // Paginate the results
            $banners = $banners->paginate(10);
            
            // Get banner statistics
            $stats = $this->getBannerStatistics();
            
            // Get banner positions and types
            $positions = $this->getBannerPositions();
            $types = $this->getBannerTypes();
            
            return view('admin.banners.index', compact('banners', 'stats', 'positions', 'types'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching banners: ' . $e->getMessage());
            return back()->with('error', 'Failed to load banners.');
        }
    }

    /**
     * Show the form for creating a new banner.
     */
    public function create()
    {
        try {
            $positions = $this->getBannerPositions();
            $types = $this->getBannerTypes();
            $devices = $this->getDeviceTypes();
            
            return view('admin.banners.create', compact('positions', 'types', 'devices'));
            
        } catch (\Exception $e) {
            Log::error('Error loading banner create form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load banner form.');
        }
    }

    /**
     * Store a newly created banner in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:promotional,informational,seasonal,product_showcase,newsletter,social_media,announcement',
            'position' => 'required|in:header,hero,sidebar,footer,popup,category_top,category_bottom,product_detail,checkout,floating',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'mobile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link_url' => 'nullable|url',
            'link_text' => 'nullable|string|max:100',
            'background_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'status' => 'nullable|in:active,inactive,scheduled,expired',
            'sort_order' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Handle image upload
            $imagePath = null;
            $imageData = null;
            if ($request->hasFile('image')) {
                try {
                    $imageData = $this->uploadBannerImage($request->file('image'), 'banners');
                    // Store the desktop size path for display
                    $imagePath = $imageData['sizes']['desktop']['path'];
                } catch (\Exception $e) {
                    return back()->withErrors(['image' => 'Failed to upload banner image: ' . $e->getMessage()])->withInput();
                }
            }
            
            // Handle mobile image upload
            $mobileImagePath = null;
            $mobileImageData = null;
            if ($request->hasFile('mobile_image')) {
                try {
                    $mobileImageData = $this->uploadBannerImage($request->file('mobile_image'), 'banners/mobile');
                    // Store the mobile size path for display
                    $mobileImagePath = $mobileImageData['sizes']['mobile']['path'];
                } catch (\Exception $e) {
                    return back()->withErrors(['mobile_image' => 'Failed to upload mobile banner image: ' . $e->getMessage()])->withInput();
                }
            }
            
            $bannerData = [
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'position' => $request->position,
                'image' => $imagePath,
                'mobile_image' => $mobileImagePath,
                'link_url' => $request->link_url,
                'link_text' => $request->link_text,
                'background_color' => $request->background_color,
                'text_color' => $request->text_color,
                'status' => $request->status ?: 'active',
                'sort_order' => $request->sort_order ?: 0,
                'start_date' => $request->start_date ? Carbon::parse($request->start_date) : null,
                'end_date' => $request->end_date ? Carbon::parse($request->end_date) : null,
                'click_count' => 0,
                'impression_count' => 0,
                'conversion_count' => 0,
                'metadata' => json_encode([
                    'image_data' => $imageData,
                    'mobile_image_data' => $mobileImageData
                ])
            ];
            
            $banner = Banner::create($bannerData);
            
            DB::commit();
            
            Log::info('Banner created successfully', ['banner_id' => $banner->id]);
            
            return redirect()->route('admin.banners.index')
                           ->with('success', 'Banner created successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating banner: ' . $e->getMessage());
            return back()->with('error', 'Failed to create banner.')->withInput();
        }
    }

    /**
     * Display the specified banner.
     */
    public function show($id)
    {
        try {
            $banner = $this->findBanner($id);
            
            if (!$banner) {
                return back()->with('error', 'Banner not found.');
            }
            
            // Get banner analytics
            $analytics = $this->getBannerAnalytics($id);
            
            return view('admin.banners.show', compact('banner', 'analytics'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching banner details: ' . $e->getMessage());
            return back()->with('error', 'Failed to load banner details.');
        }
    }

    /**
     * Show the form for editing the specified banner.
     */
    public function edit($id)
    {
        try {
            $banner = $this->findBanner($id);
            
            if (!$banner) {
                return back()->with('error', 'Banner not found.');
            }
            
            $positions = $this->getBannerPositions();
            $types = $this->getBannerTypes();
            $devices = $this->getDeviceTypes();
            
            return view('admin.banners.edit', compact('banner', 'positions', 'types', 'devices'));
            
        } catch (\Exception $e) {
            Log::error('Error loading banner edit form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load banner form.');
        }
    }

    /**
     * Update the specified banner in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:promotional,informational,seasonal,product_showcase,newsletter,social_media,announcement',
            'position' => 'required|in:header,hero,sidebar,footer,popup,category_top,category_bottom,product_detail,checkout,floating',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'mobile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link_url' => 'nullable|url',
            'link_text' => 'nullable|string|max:100',
            'background_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'status' => 'nullable|in:active,inactive,scheduled,expired',
            'sort_order' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $banner = $this->findBanner($id);
            
            if (!$banner) {
                return back()->with('error', 'Banner not found.');
            }
            
            DB::beginTransaction();
            
            // Handle image upload
            $imagePath = $banner->image;
            $imageData = null;
            if ($request->hasFile('image')) {
                try {
                    $imageData = $this->uploadBannerImage($request->file('image'), 'banners');
                    // Store the desktop size path for display
                    $imagePath = $imageData['sizes']['desktop']['path'];
                } catch (\Exception $e) {
                    return back()->withErrors(['image' => 'Failed to upload banner image: ' . $e->getMessage()])->withInput();
                }
            }
            
            // Handle mobile image upload
            $mobileImagePath = $banner->mobile_image;
            $mobileImageData = null;
            if ($request->hasFile('mobile_image')) {
                try {
                    $mobileImageData = $this->uploadBannerImage($request->file('mobile_image'), 'banners/mobile');
                    // Store the mobile size path for display
                    $mobileImagePath = $mobileImageData['sizes']['mobile']['path'];
                } catch (\Exception $e) {
                    return back()->withErrors(['mobile_image' => 'Failed to upload mobile banner image: ' . $e->getMessage()])->withInput();
                }
            }
            
            $bannerData = [
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'position' => $request->position,
                'image' => $imagePath,
                'mobile_image' => $mobileImagePath,
                'link_url' => $request->link_url,
                'link_text' => $request->link_text,
                'background_color' => $request->background_color,
                'text_color' => $request->text_color,
                'status' => $request->status ?: 'active',
                'sort_order' => $request->sort_order ?: 0,
                'start_date' => $request->start_date ? Carbon::parse($request->start_date) : null,
                'end_date' => $request->end_date ? Carbon::parse($request->end_date) : null,
                'metadata' => json_encode([
                    'image_data' => $imageData,
                    'mobile_image_data' => $mobileImageData
                ])
            ];
            
            $this->updateBanner($id, $bannerData);
            
            DB::commit();
            
            Log::info('Banner updated successfully', ['banner_id' => $id]);
            
            return redirect()->route('admin.banners.show', $id)
                           ->with('success', 'Banner updated successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating banner: ' . $e->getMessage());
            return back()->with('error', 'Failed to update banner.')->withInput();
        }
    }

    /**
     * Remove the specified banner from storage.
     */
    public function destroy($id)
    {
        try {
            $banner = $this->findBanner($id);
            
            if (!$banner) {
                return back()->with('error', 'Banner not found.');
            }
            
            DB::beginTransaction();
            
            // Delete images
            if ($banner['image']) {
                $this->deleteImage($banner['image']);
            }
            if ($banner['mobile_image']) {
                $this->deleteImage($banner['mobile_image']);
            }
            
            // Delete the banner
            $this->deleteBanner($id);
            
            DB::commit();
            
            Log::info('Banner deleted successfully', ['banner_id' => $id]);
            
            return redirect()->route('admin.banners.index')
                           ->with('success', 'Banner deleted successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting banner: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete banner.');
        }
    }

    /**
     * Toggle banner status.
     */
    public function toggleStatus($id)
    {
        try {
            $banner = $this->findBanner($id);
            
            if (!$banner) {
                return response()->json(['error' => 'Banner not found.'], 404);
            }
            
            $newStatus = !$banner['is_active'];
            $this->updateBanner($id, ['is_active' => $newStatus]);
            
            Log::info('Banner status toggled', ['banner_id' => $id, 'status' => $newStatus]);
            
            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => 'Banner status updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error toggling banner status: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update status.'], 500);
        }
    }

    /**
     * Bulk actions for banners.
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'banner_ids' => 'required|array|min:1',
            'banner_ids.*' => 'integer'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $bannerIds = $request->banner_ids;
            $action = $request->action;
            $processedCount = 0;
            
            DB::beginTransaction();
            
            foreach ($bannerIds as $bannerId) {
                $banner = $this->findBanner($bannerId);
                if (!$banner) continue;
                
                switch ($action) {
                    case 'activate':
                        $this->updateBanner($bannerId, ['is_active' => true]);
                        $processedCount++;
                        break;
                        
                    case 'deactivate':
                        $this->updateBanner($bannerId, ['is_active' => false]);
                        $processedCount++;
                        break;
                        
                    case 'delete':
                        // Delete images
                        if ($banner['image']) {
                            $this->deleteImage($banner['image']);
                        }
                        if ($banner['mobile_image']) {
                            $this->deleteImage($banner['mobile_image']);
                        }
                        $this->deleteBanner($bannerId);
                        $processedCount++;
                        break;
                }
            }
            
            DB::commit();
            
            Log::info('Bulk action performed on banners', [
                'action' => $action,
                'processed_count' => $processedCount,
                'banner_ids' => $bannerIds
            ]);
            
            return back()->with('success', "Successfully processed {$processedCount} banner(s).");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error performing bulk action: ' . $e->getMessage());
            return back()->with('error', 'Failed to perform bulk action.');
        }
    }

    /**
     * Update sort order (AJAX).
     */
    public function updateSortOrder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'banner_id' => 'required|integer',
                'sort_order' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Invalid data.'], 400);
            }

            $this->updateBanner($request->banner_id, ['sort_order' => $request->sort_order]);
            
            return response()->json([
                'success' => true,
                'message' => 'Sort order updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating sort order: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update sort order.'], 500);
        }
    }

    /**
     * Track banner impression.
     */
    public function trackImpression($id)
    {
        try {
            $banner = $this->findBanner($id);
            
            if (!$banner || !$banner['is_active']) {
                return response()->json(['error' => 'Banner not found or inactive.'], 404);
            }
            
            // Check if banner has reached max impressions
            if ($banner['max_impressions'] && $banner['impressions'] >= $banner['max_impressions']) {
                return response()->json(['error' => 'Banner has reached maximum impressions.'], 400);
            }
            
            // Check if banner is within date range
            if (!$this->isBannerInDateRange($banner)) {
                return response()->json(['error' => 'Banner is not within active date range.'], 400);
            }
            
            $this->incrementImpressions($id);
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            Log::error('Error tracking banner impression: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to track impression.'], 500);
        }
    }

    /**
     * Track banner click.
     */
    public function trackClick($id)
    {
        try {
            $banner = $this->findBanner($id);
            
            if (!$banner || !$banner['is_active']) {
                return response()->json(['error' => 'Banner not found or inactive.'], 404);
            }
            
            // Check if banner has reached max clicks
            if ($banner['max_clicks'] && $banner['clicks'] >= $banner['max_clicks']) {
                return response()->json(['error' => 'Banner has reached maximum clicks.'], 400);
            }
            
            // Check if banner is within date range
            if (!$this->isBannerInDateRange($banner)) {
                return response()->json(['error' => 'Banner is not within active date range.'], 400);
            }
            
            $this->incrementClicks($id);
            
            return response()->json([
                'success' => true,
                'redirect_url' => $banner['link_url'],
                'target_blank' => $banner['target_blank']
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error tracking banner click: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to track click.'], 500);
        }
    }

    /**
     * Get active banners for frontend.
     */
    public function getActiveBanners($position = null, $device = 'desktop')
    {
        try {
            $banners = $this->getActiveBannersQuery($position, $device);
            
            return response()->json([
                'success' => true,
                'banners' => $banners
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching active banners: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch banners.'], 500);
        }
    }

    /**
     * Export banners to CSV.
     */
    public function export()
    {
        try {
            $banners = $this->getBannersQuery();
            $filename = 'banners_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            return $this->generateCsvExport($banners, $filename);
            
        } catch (\Exception $e) {
            Log::error('Error exporting banners: ' . $e->getMessage());
            return back()->with('error', 'Failed to export banners.');
        }
    }

    // Private helper methods

    private function getBannersQuery()
    {
        return Banner::query()
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc');
    }

    private function getBannerStatistics()
    {
        $totalBanners = Banner::count();
        $activeBanners = Banner::where('status', 'active')->count();
        $inactiveBanners = Banner::where('status', 'inactive')->count();
        $totalClicks = Banner::sum('click_count') ?? 0;
        $totalImpressions = Banner::sum('impression_count') ?? 0;
        
        return [
            'total_banners' => $totalBanners,
            'active_banners' => $activeBanners,
            'inactive_banners' => $inactiveBanners,
            'total_impressions' => $totalImpressions,
            'total_clicks' => $totalClicks,
            'average_ctr' => $totalImpressions > 0 ? round(($totalClicks / $totalImpressions) * 100, 2) : 0,
            'position_distribution' => Banner::select('position', DB::raw('count(*) as count'))
                ->groupBy('position')
                ->pluck('count', 'position')
                ->toArray(),
            'type_distribution' => [
                'image' => 12,
                'video' => 2,
                'html' => 1
            ]
        ];
    }

    private function getBannerPositions()
    {
        return [
            'header' => 'Header',
            'hero' => 'Hero Section',
            'sidebar' => 'Sidebar',
            'footer' => 'Footer',
            'popup' => 'Popup',
            'inline' => 'Inline Content'
        ];
    }

    private function getBannerTypes()
    {
        return [
            'image' => 'Image Banner',
            'video' => 'Video Banner',
            'html' => 'HTML Banner',
            'slider' => 'Image Slider'
        ];
    }

    private function getDeviceTypes()
    {
        return [
            'desktop' => 'Desktop',
            'mobile' => 'Mobile',
            'both' => 'Both'
        ];
    }

    private function createBanner($data)
    {
        return Banner::create($data);
    }

    private function findBanner($id)
    {
        return Banner::find($id);
    }

    private function updateBanner($id, $data)
    {
        return Banner::where('id', $id)->update($data);
    }

    private function deleteBanner($id)
    {
        return Banner::destroy($id);
    }

    private function deleteImage($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            Log::info('Banner image deleted', ['path' => $path]);
        }
    }

    private function getBannerAnalytics($bannerId)
    {
        // Mock analytics data - replace with actual analytics query
        return [
            'total_impressions' => 2450,
            'total_clicks' => 127,
            'ctr' => 5.18, // Click-through rate
            'daily_impressions' => [
                '2025-07-20' => 245,
                '2025-07-21' => 312,
                '2025-07-22' => 289,
                '2025-07-23' => 356,
                '2025-07-24' => 334,
                '2025-07-25' => 189
            ],
            'daily_clicks' => [
                '2025-07-20' => 12,
                '2025-07-21' => 18,
                '2025-07-22' => 15,
                '2025-07-23' => 22,
                '2025-07-24' => 19,
                '2025-07-25' => 8
            ]
        ];
    }

    private function isBannerInDateRange($banner)
    {
        $now = now();
        
        if ($banner['start_date'] && $now->lt(Carbon::parse($banner['start_date']))) {
            return false;
        }
        
        if ($banner['end_date'] && $now->gt(Carbon::parse($banner['end_date']))) {
            return false;
        }
        
        return true;
    }

    private function incrementImpressions($bannerId)
    {
        // Mock increment - replace with actual database increment
        Log::info('Banner impression tracked', ['banner_id' => $bannerId]);
    }

    private function incrementClicks($bannerId)
    {
        // Mock increment - replace with actual database increment
        Log::info('Banner click tracked', ['banner_id' => $bannerId]);
    }

    private function getActiveBannersQuery($position = null, $device = 'desktop')
    {
        $banners = $this->getBannersQuery()->where('is_active', true);
        
        if ($position) {
            $banners = $banners->where('position', $position);
        }
        
        // Filter by device
        if ($device === 'mobile') {
            $banners = $banners->where('show_on_mobile', true);
        } elseif ($device === 'desktop') {
            $banners = $banners->where('show_on_desktop', true);
        }
        
        // Filter by date range
        $banners = $banners->filter(function($banner) {
            return $this->isBannerInDateRange($banner);
        });
        
        // Filter by impression/click limits
        $banners = $banners->filter(function($banner) {
            if ($banner['max_impressions'] && $banner['impressions'] >= $banner['max_impressions']) {
                return false;
            }
            if ($banner['max_clicks'] && $banner['clicks'] >= $banner['max_clicks']) {
                return false;
            }
            return true;
        });
        
        return $banners->sortBy('sort_order')->values();
    }

    private function generateCsvExport($banners, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($banners) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID',
                'Title',
                'Type',
                'Position',
                'Status',
                'Impressions',
                'Clicks',
                'CTR (%)',
                'Start Date',
                'End Date',
                'Created At'
            ]);
            
            // CSV Data
            foreach ($banners as $banner) {
                $ctr = $banner['impressions'] > 0 ? round(($banner['clicks'] / $banner['impressions']) * 100, 2) : 0;
                
                fputcsv($file, [
                    $banner['id'],
                    $banner['title'],
                    ucfirst($banner['type']),
                    ucfirst($banner['position']),
                    $banner['is_active'] ? 'Active' : 'Inactive',
                    $banner['impressions'],
                    $banner['clicks'],
                    $ctr . '%',
                    $banner['start_date'] ?? '',
                    $banner['end_date'] ?? '',
                    $banner['created_at']
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
