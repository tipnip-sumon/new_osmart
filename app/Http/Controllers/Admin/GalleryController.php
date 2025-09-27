<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GalleryController extends Controller
{
    use HandlesImageUploads;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $images = GalleryImage::ordered()->paginate(10);
        return view('admin.gallery.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.gallery.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:achievement,event,product,general',
            'rank' => 'nullable|integer|min:1',
            'achiever_name' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $imageData = $this->uploadGalleryImage($request->file('image'), 'gallery');

        GalleryImage::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imageData,
            'type' => $request->type,
            'rank' => $request->rank,
            'achiever_name' => $request->achiever_name,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->route('admin.gallery.index')
                        ->with('success', 'Gallery image uploaded successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(GalleryImage $gallery)
    {
        return view('admin.gallery.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GalleryImage $gallery)
    {
        return view('admin.gallery.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GalleryImage $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:achievement,event,product,general',
            'rank' => 'nullable|integer|min:1',
            'achiever_name' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'rank' => $request->rank,
            'achiever_name' => $request->achiever_name,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active', true)
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($gallery->image_path) {
                $this->deleteImage($gallery->image_path);
            }
            
            $data['image_path'] = $this->uploadGalleryImage($request->file('image'), 'gallery');
        }

        $gallery->update($data);

        return redirect()->route('admin.gallery.index')
                        ->with('success', 'Gallery image updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GalleryImage $gallery)
    {
        // Delete image file
        if ($gallery->image_path) {
            $this->deleteImage($gallery->image_path);
        }

        $gallery->delete();

        return redirect()->route('admin.gallery.index')
                        ->with('success', 'Gallery image deleted successfully!');
    }

    /**
     * Toggle the active status of a gallery image.
     */
    public function toggleStatus(GalleryImage $gallery)
    {
        $gallery->update(['is_active' => !$gallery->is_active]);

        $status = $gallery->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.gallery.index')
                        ->with('success', "Gallery image {$status} successfully!");
    }
}
