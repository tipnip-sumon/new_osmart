<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannerCollection;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BannerCollectionController extends Controller
{
    use HandlesImageUploads;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = BannerCollection::ordered()->paginate(10);
        return view('admin.banner-collections.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banner-collections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'button_text' => 'required|string|max:100',
            'button_url' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // Increased to 10MB
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'show_countdown' => 'boolean',
            'countdown_end_date' => 'nullable|date|after:now',
            'background_color' => 'nullable|string|max:20',
            'text_color' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        // Handle single image upload (backward compatibility)
        if ($request->hasFile('image')) {
            $imageData = $this->processImageUpload(
                $request->file('image'), 
                'banner-collections',
                [
                    'original' => ['width' => 1200, 'height' => 800],
                    'large' => ['width' => 800, 'height' => 600],
                    'medium' => ['width' => 400, 'height' => 300],
                    'thumbnail' => ['width' => 200, 'height' => 150]
                ],
                85
            );
            
            $data['image'] = $imageData['sizes']['large']['path'];
            $data['image_data'] = json_encode($imageData);
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $imagesData = [];
            foreach ($request->file('images') as $file) {
                $imageData = $this->processImageUpload(
                    $file, 
                    'banner-collections',
                    [
                        'original' => ['width' => 1200, 'height' => 800],
                        'large' => ['width' => 800, 'height' => 600],
                        'medium' => ['width' => 400, 'height' => 300],
                        'thumbnail' => ['width' => 200, 'height' => 150]
                    ],
                    85
                );
                $imagesData[] = $imageData;
            }
            
            // Use the first image as the main image if no single image was uploaded
            if (!$request->hasFile('image') && !empty($imagesData)) {
                $data['image'] = $imagesData[0]['sizes']['large']['path'];
            }
            
            $data['images_data'] = json_encode($imagesData);
        }

        // Handle checkbox values
        $data['show_countdown'] = $request->boolean('show_countdown');
        $data['is_active'] = $request->boolean('is_active');

        BannerCollection::create($data);

        return redirect()->route('admin.banner-collections.index')
            ->with('success', 'Banner collection created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BannerCollection $bannerCollection)
    {
        return view('admin.banner-collections.show', compact('bannerCollection'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BannerCollection $bannerCollection)
    {
        return view('admin.banner-collections.edit', compact('bannerCollection'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BannerCollection $bannerCollection)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'button_text' => 'required|string|max:100',
            'button_url' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // Increased to 10MB
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'show_countdown' => 'boolean',
            'countdown_end_date' => 'nullable|date|after:now',
            'background_color' => 'nullable|string|max:20',
            'text_color' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        // Handle single image upload (backward compatibility)
        if ($request->hasFile('image')) {
            // Delete old image files if they exist
            if ($bannerCollection->image && Storage::disk('public')->exists($bannerCollection->image)) {
                Storage::disk('public')->delete($bannerCollection->image);
            }
            
            // Also clean up old image data files if they exist
            if ($bannerCollection->image_data) {
                foreach ($bannerCollection->image_data['sizes'] ?? [] as $size => $sizeData) {
                    if (isset($sizeData['path']) && Storage::disk('public')->exists($sizeData['path'])) {
                        Storage::disk('public')->delete($sizeData['path']);
                    }
                }
            }
            
            $imageData = $this->processImageUpload(
                $request->file('image'), 
                'banner-collections',
                [
                    'original' => ['width' => 1200, 'height' => 800],
                    'large' => ['width' => 800, 'height' => 600],
                    'medium' => ['width' => 400, 'height' => 300],
                    'thumbnail' => ['width' => 200, 'height' => 150]
                ],
                85
            );
            
            $data['image'] = $imageData['sizes']['large']['path'];
            $data['image_data'] = json_encode($imageData);
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            // Clean up old multiple images if they exist
            if ($bannerCollection->images_data) {
                foreach ($bannerCollection->images_data as $oldImageData) {
                    foreach ($oldImageData['sizes'] ?? [] as $size => $sizeData) {
                        if (isset($sizeData['path']) && Storage::disk('public')->exists($sizeData['path'])) {
                            Storage::disk('public')->delete($sizeData['path']);
                        }
                    }
                }
            }
            
            $imagesData = [];
            foreach ($request->file('images') as $file) {
                $imageData = $this->processImageUpload(
                    $file, 
                    'banner-collections',
                    [
                        'original' => ['width' => 1200, 'height' => 800],
                        'large' => ['width' => 800, 'height' => 600],
                        'medium' => ['width' => 400, 'height' => 300],
                        'thumbnail' => ['width' => 200, 'height' => 150]
                    ],
                    85
                );
                $imagesData[] = $imageData;
            }
            
            // Use the first image as the main image if no single image was uploaded
            if (!$request->hasFile('image') && !empty($imagesData)) {
                $data['image'] = $imagesData[0]['sizes']['large']['path'];
            }
            
            $data['images_data'] = json_encode($imagesData);
        }

        // Handle checkbox values
        $data['show_countdown'] = $request->boolean('show_countdown');
        $data['is_active'] = $request->boolean('is_active');

        $bannerCollection->update($data);

        return redirect()->route('admin.banner-collections.index')
            ->with('success', 'Banner collection updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BannerCollection $bannerCollection)
    {
        // Delete main image
        if ($bannerCollection->image && Storage::disk('public')->exists($bannerCollection->image)) {
            Storage::disk('public')->delete($bannerCollection->image);
        }
        
        // Delete processed image files from image_data
        if ($bannerCollection->image_data) {
            foreach ($bannerCollection->image_data['sizes'] ?? [] as $size => $sizeData) {
                if (isset($sizeData['path']) && Storage::disk('public')->exists($sizeData['path'])) {
                    Storage::disk('public')->delete($sizeData['path']);
                }
            }
        }
        
        // Delete multiple images from images_data
        if ($bannerCollection->images_data) {
            foreach ($bannerCollection->images_data as $imageData) {
                foreach ($imageData['sizes'] ?? [] as $size => $sizeData) {
                    if (isset($sizeData['path']) && Storage::disk('public')->exists($sizeData['path'])) {
                        Storage::disk('public')->delete($sizeData['path']);
                    }
                }
            }
        }

        $bannerCollection->delete();

        return redirect()->route('admin.banner-collections.index')
            ->with('success', 'Banner collection deleted successfully.');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(BannerCollection $bannerCollection)
    {
        $bannerCollection->update(['is_active' => !$bannerCollection->is_active]);

        return redirect()->back()
            ->with('success', 'Banner collection status updated successfully.');
    }

    /**
     * Update sort order
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:banner_collections,id',
            'items.*.sort_order' => 'required|integer|min:0'
        ]);

        foreach ($request->items as $item) {
            BannerCollection::where('id', $item['id'])
                ->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}