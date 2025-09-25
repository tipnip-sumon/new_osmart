<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CollectionController extends Controller
{
    use HandlesImageUploads;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collections = Collection::with('products')
                                ->orderBy('sort_order')
                                ->orderBy('created_at', 'desc')
                                ->paginate(15);
        
        return view('admin.collections.index', compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.collections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:collections,slug',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'color_code' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive,draft',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'commission_type' => 'nullable|in:percentage,fixed',
            'show_in_menu' => 'boolean',
            'show_in_footer' => 'boolean',
        ]);

        $data = $request->all();
        
        // Auto-generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $imageData = $this->processImageUpload($request->file('image'), 'collections');
            $data['image'] = $imageData['sizes']['original']['path'] ?? null;
            $data['image_data'] = $imageData;
        }

        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            $bannerImageData = $this->processImageUpload($request->file('banner_image'), 'collections/banners');
            $data['banner_image'] = $bannerImageData['sizes']['original']['path'] ?? null;
        }

        // Handle boolean fields
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');
        $data['show_in_menu'] = $request->has('show_in_menu');
        $data['show_in_footer'] = $request->has('show_in_footer');

        Collection::create($data);

        return redirect()->route('admin.collections.index')
                        ->with('success', 'Collection created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Collection $collection)
    {
        $collection->load('products');
        return view('admin.collections.show', compact('collection'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Collection $collection)
    {
        return view('admin.collections.edit', compact('collection'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Collection $collection)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('collections')->ignore($collection->id)],
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'color_code' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive,draft',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'commission_type' => 'nullable|in:percentage,fixed',
            'show_in_menu' => 'boolean',
            'show_in_footer' => 'boolean',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($collection->image && $collection->image_data) {
                $this->deleteImageFiles($collection->image_data);
            } elseif ($collection->image) {
                $this->deleteLegacyImageFile($collection->image);
            }
            
            $imageData = $this->processImageUpload($request->file('image'), 'collections');
            $data['image'] = $imageData['sizes']['original']['path'] ?? null;
            $data['image_data'] = $imageData;
        }

        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            // Delete old banner image if exists
            if ($collection->banner_image) {
                $this->deleteLegacyImageFile($collection->banner_image);
            }
            
            $bannerImageData = $this->processImageUpload($request->file('banner_image'), 'collections/banners');
            $data['banner_image'] = $bannerImageData['sizes']['original']['path'] ?? null;
        }

        // Handle boolean fields
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');
        $data['show_in_menu'] = $request->has('show_in_menu');
        $data['show_in_footer'] = $request->has('show_in_footer');

        $collection->update($data);

        return redirect()->route('admin.collections.index')
                        ->with('success', 'Collection updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        // Delete associated images
        if ($collection->image && $collection->image_data) {
            $this->deleteImageFiles($collection->image_data);
        } elseif ($collection->image) {
            $this->deleteLegacyImageFile($collection->image);
        }
        
        if ($collection->banner_image) {
            $this->deleteLegacyImageFile($collection->banner_image);
        }

        $collection->delete();

        return redirect()->route('admin.collections.index')
                        ->with('success', 'Collection deleted successfully');
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(Collection $collection)
    {
        $collection->update([
            'is_active' => !$collection->is_active
        ]);

        $status = $collection->is_active ? 'activated' : 'deactivated';
        
        return response()->json([
            'success' => true, 
            'message' => "Collection {$status} successfully",
            'is_active' => $collection->is_active
        ]);
    }

    /**
     * Handle bulk actions.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature',
            'ids' => 'required|array',
            'ids.*' => 'exists:collections,id'
        ]);

        $collections = Collection::whereIn('id', $request->ids);
        $count = $collections->count();

        switch ($request->action) {
            case 'activate':
                $collections->update(['is_active' => true]);
                $message = "{$count} collections activated successfully";
                break;
            case 'deactivate':
                $collections->update(['is_active' => false]);
                $message = "{$count} collections deactivated successfully";
                break;
            case 'feature':
                $collections->update(['is_featured' => true]);
                $message = "{$count} collections featured successfully";
                break;
            case 'unfeature':
                $collections->update(['is_featured' => false]);
                $message = "{$count} collections unfeatured successfully";
                break;
            case 'delete':
                // Delete images for each collection before deleting
                foreach ($collections->get() as $collection) {
                    if ($collection->image && $collection->image_data) {
                        $this->deleteImageFiles($collection->image_data);
                    } elseif ($collection->image) {
                        $this->deleteLegacyImageFile($collection->image);
                    }
                    
                    if ($collection->banner_image) {
                        $this->deleteLegacyImageFile($collection->banner_image);
                    }
                }
                $collections->delete();
                $message = "{$count} collections deleted successfully";
                break;
        }

        return redirect()->route('admin.collections.index')->with('success', $message);
    }

    /**
     * Export collections to CSV.
     */
    public function export()
    {
        $collections = Collection::orderBy('created_at', 'desc')->get();
        
        $filename = 'collections_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($collections) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Name', 'Slug', 'Status', 'Is Active', 'Is Featured', 
                'Sort Order', 'Products Count', 'Created At', 'Updated At'
            ]);
            
            // CSV data
            foreach ($collections as $collection) {
                fputcsv($file, [
                    $collection->id,
                    $collection->name,
                    $collection->slug,
                    $collection->status,
                    $collection->is_active ? 'Yes' : 'No',
                    $collection->is_featured ? 'Yes' : 'No',
                    $collection->sort_order,
                    $collection->products()->count(),
                    $collection->created_at->format('Y-m-d H:i:s'),
                    $collection->updated_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show analytics for collections.
     */
    public function analytics()
    {
        $analytics = [
            'total_collections' => Collection::count(),
            'active_collections' => Collection::active()->count(),
            'featured_collections' => Collection::featured()->count(),
            'inactive_collections' => Collection::where('is_active', false)->count(),
            'collections_with_products' => Collection::has('products')->count(),
            'collections_without_products' => Collection::doesntHave('products')->count(),
            'recent_collections' => Collection::where('created_at', '>=', now()->subDays(30))->count(),
            'top_collections' => Collection::withCount('products')
                                          ->orderBy('products_count', 'desc')
                                          ->take(5)
                                          ->get(),
            'monthly_stats' => Collection::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                                        ->whereYear('created_at', now()->year)
                                        ->groupBy('month')
                                        ->orderBy('month')
                                        ->get()
                                        ->mapWithKeys(function ($item) {
                                            return [date('M', mktime(0, 0, 0, $item->month, 1)) => $item->count];
                                        })
        ];

        return view('admin.collections.analytics', compact('analytics'));
    }

    /**
     * Show featured collections.
     */
    public function featured()
    {
        $featuredCollections = Collection::featured()
                                        ->with(['products'])
                                        ->orderBy('sort_order')
                                        ->orderBy('created_at', 'desc')
                                        ->paginate(12);
        
        return view('admin.collections.featured', compact('featuredCollections'));
    }

    /**
     * Show seasonal collections.
     */
    public function seasonal()
    {
        // For now, we'll filter collections based on name containing season keywords
        // In a more advanced setup, you might have a 'season' field in the database
        $seasonalCollections = Collection::where(function($query) {
                                    $query->where('name', 'like', '%spring%')
                                          ->orWhere('name', 'like', '%summer%')
                                          ->orWhere('name', 'like', '%autumn%')
                                          ->orWhere('name', 'like', '%fall%')
                                          ->orWhere('name', 'like', '%winter%')
                                          ->orWhere('description', 'like', '%seasonal%');
                                })
                                ->with(['products'])
                                ->orderBy('sort_order')
                                ->orderBy('created_at', 'desc')
                                ->paginate(12);
        
        return view('admin.collections.seasonal', compact('seasonalCollections'));
    }

    /**
     * Validate slug for uniqueness (AJAX endpoint)
     */
    public function validateSlug(Request $request)
    {
        $slug = $request->input('slug');
        $id = $request->input('id'); // For edit mode

        $query = Collection::where('slug', $slug);
        
        if ($id) {
            $query->where('id', '!=', $id);
        }
        
        $exists = $query->exists();
        
        return response()->json([
            'valid' => !$exists,
            'message' => $exists ? 'This slug is already taken' : 'Slug is available'
        ]);
    }
}
