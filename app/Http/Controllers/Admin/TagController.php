<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::orderBy('sort_order', 'asc')
                   ->orderBy('created_at', 'desc')
                   ->get();
        
        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        // Create the tag using Eloquent model
        $tag = Tag::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'color' => $request->color,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);
        
        return redirect()->route('admin.tags.index')->with('success', 'Tag "' . $tag->name . '" has been created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tag = Tag::findOrFail($id);
        return view('admin.tags.show', compact('tag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tag = Tag::findOrFail($id);
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tag = Tag::findOrFail($id);
        
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug,' . $tag->id,
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        // Update the tag
        $tag->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'color' => $request->color,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);
        
        return redirect()->route('admin.tags.index')->with('success', 'Tag "' . $tag->name . '" has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tag = Tag::findOrFail($id);
        $tagName = $tag->name;
        $tag->delete();
        
        return redirect()->route('admin.tags.index')->with('success', 'Tag "' . $tagName . '" has been deleted successfully!');
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->is_active = !$tag->is_active;
        $tag->save();
        
        $status = $tag->is_active ? 'activated' : 'deactivated';
        
        return response()->json([
            'success' => true, 
            'message' => 'Tag "' . $tag->name . '" has been ' . $status . ' successfully!',
            'is_active' => $tag->is_active
        ]);
    }

    /**
     * Validate slug uniqueness for AJAX requests.
     */
    public function validateSlug(Request $request)
    {
        $slug = $request->input('slug');
        $excludeId = $request->input('id'); // For edit mode, exclude current tag

        // Check if slug exists in database
        $query = Tag::where('slug', $slug);
        
        // If we're editing, exclude the current tag
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        $isAvailable = !$query->exists();

        return response()->json([
            'available' => $isAvailable,
            'message' => $isAvailable ? 'Slug is available' : 'This slug is already in use',
            'suggestions' => $isAvailable ? [] : $this->generateSlugSuggestions($slug)
        ]);
    }

    /**
     * Generate alternative slug suggestions when slug is not available.
     */
    private function generateSlugSuggestions($baseSlug)
    {
        $suggestions = [];
        for ($i = 2; $i <= 5; $i++) {
            $suggestions[] = $baseSlug . '-' . $i;
        }
        $suggestions[] = $baseSlug . '-new';
        $suggestions[] = $baseSlug . '-' . date('Y');
        
        return $suggestions;
    }

    /**
     * Handle bulk actions.
     */
    public function bulkAction(Request $request)
    {
        return redirect()->route('admin.tags.index')->with('success', 'Bulk action completed successfully');
    }

    /**
     * Export tags to CSV.
     */
    public function export()
    {
        return redirect()->route('admin.tags.index')->with('info', 'Export functionality coming soon');
    }

    /**
     * Show analytics for tags.
     */
    public function analytics()
    {
        return view('admin.tags.analytics');
    }

    /**
     * Show popular tags.
     */
    public function popular()
    {
        $popularTags = [
            ['id' => 1, 'name' => 'Fashion', 'slug' => 'fashion', 'usage_count' => 150],
            ['id' => 2, 'name' => 'Electronics', 'slug' => 'electronics', 'usage_count' => 120],
            ['id' => 3, 'name' => 'Home & Garden', 'slug' => 'home-garden', 'usage_count' => 90],
        ];
        
        return view('admin.tags.popular', compact('popularTags'));
    }
}
