<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Popup;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PopupController extends Controller
{
    use HandlesImageUploads;
    /**
     * Display a listing of popups.
     */
    public function index(Request $request)
    {
        try {
            $query = Popup::query();
            
            // Apply filters
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }
            
            if ($request->filled('status')) {
                $status = $request->status === 'active' ? 1 : 0;
                $query->where('status', $status);
            }
            
            if ($request->filled('trigger')) {
                $query->where('trigger_type', $request->trigger);
            }
            
            if ($request->filled('date_from')) {
                $query->whereDate('start_date', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->whereDate('end_date', '<=', $request->date_to);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%");
                });
            }
            
            // Order by most recent
            $query->orderBy('created_at', 'desc');
            
            // Paginate results
            $popups = $query->paginate(10);
            
            // Get popup statistics
            $stats = $this->getPopupStatistics();
            
            // Get popup types and triggers
            $types = $this->getPopupTypes();
            $triggers = $this->getTriggerTypes();
            
            return view('admin.popups.index', compact('popups', 'stats', 'types', 'triggers'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching popups: ' . $e->getMessage());
            return back()->with('error', 'Failed to load popups.');
        }
    }

    /**
     * Show the form for creating a new popup.
     */
    public function create()
    {
        try {
            $types = $this->getPopupTypes();
            $triggers = $this->getTriggerTypes();
            $pages = $this->getAvailablePages();
            $devices = $this->getDeviceTypes();
            $userTypes = $this->getUserTypes();
            
            return view('admin.popups.create', compact('types', 'triggers', 'pages', 'devices', 'userTypes'));
            
        } catch (\Exception $e) {
            Log::error('Error loading popup create form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load popup form.');
        }
    }

    /**
     * Store a newly created popup in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:newsletter,promotion,announcement,exit_intent,cookie_consent,age_verification,warning,info',
            'content' => 'required|string',
            'trigger_type' => 'required|in:immediate,delay,scroll,exit_intent,page_visit',
            'trigger_value' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'background_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'button_color' => 'nullable|string|max:7',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|url',
            'close_button' => 'boolean',
            'overlay' => 'boolean',
            'modal_size' => 'required|in:small,medium,large,fullscreen',
            'animation' => 'required|in:fade,slide_up,slide_down,slide_left,slide_right,zoom',
            'position' => 'required|in:center,top,bottom,top_left,top_right,bottom_left,bottom_right',
            'is_active' => 'boolean',
            'show_once' => 'boolean',
            'frequency' => 'required|in:always,once_per_session,once_per_day,once_per_week,once_per_month',
            'priority' => 'nullable|integer|min:1|max:10',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'target_pages' => 'nullable|array',
            'target_pages.*' => 'string',
            'exclude_pages' => 'nullable|array',
            'exclude_pages.*' => 'string',
            'target_devices' => 'required|array|min:1',
            'target_devices.*' => 'in:desktop,tablet,mobile',
            'target_users' => 'required|array|min:1',
            'target_users.*' => 'in:all,guests,registered,new_visitors,returning_visitors',
            'max_displays' => 'nullable|integer|min:1',
            'conversion_goal' => 'nullable|in:newsletter_signup,purchase,contact_form,download',
            'auto_close' => 'nullable|integer|min:1'
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
                    $imageData = $this->uploadSingleImage($request->file('image'), 'popups');
                    // Store the original file name for backwards compatibility
                    $imagePath = $imageData['filename'] ?? ($imageData['sizes']['original']['path'] ?? null);
                } catch (\Exception $e) {
                    return back()->withErrors(['image' => 'Failed to upload popup image: ' . $e->getMessage()])->withInput();
                }
            }
            
            $popupData = [
                'name' => $request->name,
                'title' => $request->title,
                'description' => $request->description ?: '',
                'type' => $request->type,
                'content' => $request->content,
                'trigger_type' => $request->trigger_type,
                'trigger_value' => $request->trigger_value,
                'image' => $imagePath,
                'image_data' => $imageData ? json_encode($imageData) : null,
                'background_color' => $request->background_color ?: '#ffffff',
                'text_color' => $request->text_color ?: '#333333',
                'button_color' => $request->button_color ?: '#007bff',
                'button_text' => $request->button_text ?: 'Close',
                'button_url' => $request->button_url,
                'close_button' => $request->boolean('close_button', true),
                'overlay' => $request->boolean('overlay', true),
                'modal_size' => $request->modal_size,
                'animation' => $request->animation,
                'position' => $request->position,
                'is_active' => $request->boolean('is_active', true),
                'show_once' => $request->boolean('show_once', false),
                'frequency' => $request->frequency,
                'priority' => $request->priority ?: 1,
                'start_date' => $request->start_date ? Carbon::parse($request->start_date) : null,
                'end_date' => $request->end_date ? Carbon::parse($request->end_date) : null,
                'target_pages' => $request->target_pages ? json_encode($request->target_pages) : null,
                'exclude_pages' => $request->exclude_pages ? json_encode($request->exclude_pages) : null,
                'target_devices' => json_encode($request->target_devices ?: ['desktop', 'mobile', 'tablet']),
                'target_users' => json_encode($request->target_users ?: ['all']),
                'max_displays' => $request->max_displays,
                'conversion_goal' => $request->conversion_goal,
                'auto_close' => $request->auto_close,
                'displays' => 0,
                'conversions' => 0,
                'clicks' => 0
            ];
            
            $popup = $this->createPopup($popupData);
            
            DB::commit();
            
            Log::info('Popup created successfully', ['popup_id' => $popup['id']]);
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Popup created successfully.',
                    'popup' => $popup,
                    'redirect' => route('admin.popups.show', $popup['id'])
                ]);
            }
            
            return redirect()->route('admin.popups.index')
                           ->with('success', 'Popup created successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating popup: ' . $e->getMessage());
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create popup.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to create popup.')->withInput();
        }
    }

    /**
     * Display the specified popup.
     */
    public function show($id)
    {
        try {
            $popup = $this->findPopup($id);
            
            if (!$popup) {
                return back()->with('error', 'Popup not found.');
            }
            
            // Get popup analytics
            $analytics = $this->getPopupAnalytics($id);
            
            return view('admin.popups.show', compact('popup', 'analytics'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching popup details: ' . $e->getMessage());
            return back()->with('error', 'Failed to load popup details.');
        }
    }

    /**
     * Show the form for editing the specified popup.
     */
    public function edit($id)
    {
        try {
            $popup = $this->findPopup($id);
            
            if (!$popup) {
                return back()->with('error', 'Popup not found.');
            }
            
            $types = $this->getPopupTypes();
            $triggers = $this->getTriggerTypes();
            $pages = $this->getAvailablePages();
            $devices = $this->getDeviceTypes();
            $userTypes = $this->getUserTypes();
            
            return view('admin.popups.edit', compact('popup', 'types', 'triggers', 'pages', 'devices', 'userTypes'));
            
        } catch (\Exception $e) {
            Log::error('Error loading popup edit form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load popup form.');
        }
    }

    /**
     * Update the specified popup in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:newsletter,promotion,announcement,exit_intent,cookie_consent,age_verification',
            'content' => 'required|string',
            'trigger_type' => 'required|in:immediate,delay,scroll,exit_intent,page_visit',
            'trigger_value' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'background_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'button_color' => 'nullable|string|max:7',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|url',
            'close_button' => 'boolean',
            'overlay' => 'boolean',
            'modal_size' => 'required|in:small,medium,large,fullscreen',
            'animation' => 'required|in:fade,slide_up,slide_down,slide_left,slide_right,zoom',
            'position' => 'required|in:center,top,bottom,top_left,top_right,bottom_left,bottom_right',
            'is_active' => 'boolean',
            'show_once' => 'boolean',
            'frequency' => 'required|in:always,once_per_session,once_per_day,once_per_week,once_per_month',
            'priority' => 'nullable|integer|min:1|max:10',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'target_pages' => 'nullable|array',
            'target_pages.*' => 'string',
            'exclude_pages' => 'nullable|array',
            'exclude_pages.*' => 'string',
            'target_devices' => 'required|array|min:1',
            'target_devices.*' => 'in:desktop,tablet,mobile',
            'target_users' => 'required|array|min:1',
            'target_users.*' => 'in:all,guests,registered,new_visitors,returning_visitors',
            'max_displays' => 'nullable|integer|min:1',
            'conversion_goal' => 'nullable|in:newsletter_signup,purchase,contact_form,download',
            'auto_close' => 'nullable|integer|min:1'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $popup = $this->findPopup($id);
            
            if (!$popup) {
                return back()->with('error', 'Popup not found.');
            }
            
            DB::beginTransaction();
            
            // Handle image upload
            $imagePath = $popup['image'];
            $imageData = null;
            if ($request->hasFile('image')) {
                // Delete old image files
                if (isset($popup['image_data']) && $popup['image_data']) {
                    $oldImageData = json_decode($popup['image_data'], true);
                    if ($oldImageData) {
                        $this->deleteImageFiles($oldImageData);
                    }
                } elseif ($imagePath) {
                    $this->deleteLegacyImageFile($imagePath);
                }
                
                try {
                    $imageData = $this->uploadSingleImage($request->file('image'), 'popups');
                    // Store the original file name for backwards compatibility
                    $imagePath = $imageData['filename'] ?? ($imageData['sizes']['original']['path'] ?? null);
                } catch (\Exception $e) {
                    return back()->withErrors(['image' => 'Failed to upload popup image: ' . $e->getMessage()])->withInput();
                }
            }
            
            $popupData = [
                'name' => $request->name,
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'content' => $request->content,
                'trigger_type' => $request->trigger_type,
                'trigger_value' => $request->trigger_value,
                'image' => $imagePath,
                'image_data' => $imageData ? json_encode($imageData) : ($popup['image_data'] ?? null),
                'background_color' => $request->background_color ?: '#ffffff',
                'text_color' => $request->text_color ?: '#333333',
                'button_color' => $request->button_color ?: '#007bff',
                'button_text' => $request->button_text ?: 'Close',
                'button_url' => $request->button_url,
                'close_button' => $request->boolean('close_button', true),
                'overlay' => $request->boolean('overlay', true),
                'modal_size' => $request->modal_size,
                'animation' => $request->animation,
                'position' => $request->position,
                'is_active' => $request->boolean('is_active', true),
                'show_once' => $request->boolean('show_once'),
                'frequency' => $request->frequency,
                'priority' => $request->priority ?: 5,
                'start_date' => $request->start_date ? Carbon::parse($request->start_date) : null,
                'end_date' => $request->end_date ? Carbon::parse($request->end_date) : null,
                'target_pages' => $request->target_pages ? json_encode($request->target_pages) : null,
                'exclude_pages' => $request->exclude_pages ? json_encode($request->exclude_pages) : null,
                'target_devices' => json_encode($request->target_devices),
                'target_users' => json_encode($request->target_users),
                'max_displays' => $request->max_displays,
                'conversion_goal' => $request->conversion_goal,
                'auto_close' => $request->auto_close
            ];
            
            $this->updatePopup($id, $popupData);
            
            DB::commit();
            
            Log::info('Popup updated successfully', ['popup_id' => $id]);
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Popup updated successfully.',
                    'popup' => $this->findPopup($id)
                ]);
            }
            
            return redirect()->route('admin.popups.show', $id)
                           ->with('success', 'Popup updated successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating popup: ' . $e->getMessage());
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update popup.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to update popup.')->withInput();
        }
    }

    /**
     * Remove the specified popup from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $popup = $this->findPopup($id);
            
            if (!$popup) {
                // Return JSON response for AJAX requests
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Popup not found.'
                    ], 404);
                }
                return back()->with('error', 'Popup not found.');
            }
            
            DB::beginTransaction();
            
            // Delete image files if they exist
            if ($popup['image']) {
                if ($popup['image_data'] ?? null) {
                    try {
                        $imageData = json_decode($popup['image_data'], true);
                        $this->deleteImageFiles($imageData);
                    } catch (\Exception $e) {
                        Log::warning('Failed to delete popup image files', ['error' => $e->getMessage()]);
                    }
                }
            }
            
            // Delete the popup
            $this->deletePopup($id);
            
            DB::commit();
            
            Log::info('Popup deleted successfully', ['popup_id' => $id]);
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Popup deleted successfully.',
                    'redirect' => route('admin.popups.index')
                ]);
            }
            
            return redirect()->route('admin.popups.index')
                           ->with('success', 'Popup deleted successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting popup: ' . $e->getMessage());
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete popup.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to delete popup.');
        }
    }

    /**
     * Toggle popup status.
     */
    public function toggleStatus(Request $request, $id)
    {
        try {
            $popup = $this->findPopup($id);
            
            if (!$popup) {
                return response()->json([
                    'success' => false,
                    'message' => 'Popup not found.'
                ], 404);
            }
            
            $newStatus = $request->input('is_active', !$popup['is_active']);
            $newStatus = (bool) $newStatus;
            
            $this->updatePopup($id, ['is_active' => $newStatus]);
            
            Log::info('Popup status toggled', ['popup_id' => $id, 'status' => $newStatus]);
            
            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => 'Popup status updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error toggling popup status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplicate a popup.
     */
    public function duplicate(Request $request, $id)
    {
        try {
            $popup = $this->findPopup($id);
            
            if (!$popup) {
                // Return JSON response for AJAX requests
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Popup not found.'
                    ], 404);
                }
                return back()->with('error', 'Popup not found.');
            }
            
            DB::beginTransaction();
            
            // Convert model to array and prepare data for duplication
            $popupArray = $popup->toArray();
            unset($popupArray['id'], $popupArray['created_at'], $popupArray['updated_at']);
            $popupArray['title'] = $popup->title . ' (Copy)';
            $popupArray['name'] = ($popup->name ?? $popup->title) . ' (Copy)';
            $popupArray['is_active'] = false;
            $popupArray['displays'] = 0;
            $popupArray['conversions'] = 0;
            $popupArray['clicks'] = 0;
            
            // Copy image if exists - for duplication, we can reuse the original image
            if ($popup->image) {
                $popupArray['image'] = $popup->image;
                $popupArray['image_data'] = $popup->image_data;
            }
            
            $newPopup = $this->createPopup($popupArray);
            
            DB::commit();
            
            Log::info('Popup duplicated successfully', ['original_id' => $id, 'new_id' => $newPopup['id']]);
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Popup duplicated successfully.',
                    'popup' => $newPopup,
                    'redirect' => route('admin.popups.edit', $newPopup['id'])
                ]);
            }
            
            return redirect()->route('admin.popups.edit', $newPopup['id'])
                           ->with('success', 'Popup duplicated successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating popup: ' . $e->getMessage());
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to duplicate popup.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to duplicate popup.');
        }
    }

    /**
     * Bulk actions for popups.
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'popup_ids' => 'required|array|min:1',
            'popup_ids.*' => 'integer'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $popupIds = $request->popup_ids;
            $action = $request->action;
            $processedCount = 0;
            
            DB::beginTransaction();
            
            foreach ($popupIds as $popupId) {
                $popup = $this->findPopup($popupId);
                if (!$popup) continue;
                
                switch ($action) {
                    case 'activate':
                        $this->updatePopup($popupId, ['is_active' => true]);
                        $processedCount++;
                        break;
                        
                    case 'deactivate':
                        $this->updatePopup($popupId, ['is_active' => false]);
                        $processedCount++;
                        break;
                        
                    case 'delete':
                        // Delete image files if they exist
                        if ($popup['image']) {
                            if ($popup['image_data'] ?? null) {
                                try {
                                    $imageData = json_decode($popup['image_data'], true);
                                    $this->deleteImageFiles($imageData);
                                } catch (\Exception $e) {
                                    Log::warning('Failed to delete popup image files', ['error' => $e->getMessage()]);
                                }
                            }
                        }
                        $this->deletePopup($popupId);
                        $processedCount++;
                        break;
                }
            }
            
            DB::commit();
            
            Log::info('Bulk action performed on popups', [
                'action' => $action,
                'processed_count' => $processedCount,
                'popup_ids' => $popupIds
            ]);
            
            return back()->with('success', "Successfully processed {$processedCount} popup(s).");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error performing bulk action: ' . $e->getMessage());
            return back()->with('error', 'Failed to perform bulk action.');
        }
    }

    /**
     * Get active popups for frontend.
     */
    public function getActivePopups(Request $request)
    {
        try {
            $page = $request->get('page', '/');
            $device = $this->detectDevice($request);
            $userType = $this->detectUserType($request);
            
            $popups = $this->getActivePopupsQuery($page, $device, $userType);
            
            return response()->json([
                'success' => true,
                'popups' => $popups
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching active popups: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch popups.'], 500);
        }
    }

    /**
     * Track popup display.
     */
    public function trackDisplay($id)
    {
        try {
            $popup = $this->findPopup($id);
            
            if (!$popup || !$popup['is_active']) {
                return response()->json(['error' => 'Popup not found or inactive.'], 404);
            }
            
            // Check if popup has reached max displays
            if ($popup['max_displays'] && $popup['displays'] >= $popup['max_displays']) {
                return response()->json(['error' => 'Popup has reached maximum displays.'], 400);
            }
            
            // Check if popup is within date range
            if (!$this->isPopupInDateRange($popup)) {
                return response()->json(['error' => 'Popup is not within active date range.'], 400);
            }
            
            $this->incrementDisplays($id);
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            Log::error('Error tracking popup display: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to track display.'], 500);
        }
    }

    /**
     * Track popup click.
     */
    public function trackClick($id)
    {
        try {
            $popup = $this->findPopup($id);
            
            if (!$popup || !$popup['is_active']) {
                return response()->json(['error' => 'Popup not found or inactive.'], 404);
            }
            
            $this->incrementClicks($id);
            
            return response()->json([
                'success' => true,
                'redirect_url' => $popup['button_url']
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error tracking popup click: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to track click.'], 500);
        }
    }

    /**
     * Track popup conversion.
     */
    public function trackConversion($id)
    {
        try {
            $popup = $this->findPopup($id);
            
            if (!$popup || !$popup['is_active']) {
                return response()->json(['error' => 'Popup not found or inactive.'], 404);
            }
            
            $this->incrementConversions($id);
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            Log::error('Error tracking popup conversion: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to track conversion.'], 500);
        }
    }

    /**
     * Preview popup.
     */
    public function preview($id)
    {
        try {
            $popup = $this->findPopup($id);
            
            if (!$popup) {
                return back()->with('error', 'Popup not found.');
            }
            
            return view('admin.popups.preview', compact('popup'));
            
        } catch (\Exception $e) {
            Log::error('Error loading popup preview: ' . $e->getMessage());
            return back()->with('error', 'Failed to load popup preview.');
        }
    }

    /**
     * Export popups to CSV.
     */
    public function export()
    {
        try {
            $popups = $this->getPopupsQuery();
            $filename = 'popups_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            return $this->generateCsvExport($popups, $filename);
            
        } catch (\Exception $e) {
            Log::error('Error exporting popups: ' . $e->getMessage());
            return back()->with('error', 'Failed to export popups.');
        }
    }

    // Private helper methods

    private function getPopupsQuery()
    {
        return Popup::query();
    }

    private function getPopupStatistics()
    {
        return [
            'total_popups' => 12,
            'active_popups' => 8,
            'inactive_popups' => 4,
            'total_displays' => 15600,
            'total_clicks' => 1850,
            'total_conversions' => 425,
            'average_ctr' => 11.9, // Click-through rate
            'average_cvr' => 2.7, // Conversion rate
            'type_distribution' => [
                'newsletter' => 4,
                'promotion' => 3,
                'announcement' => 2,
                'exit_intent' => 2,
                'cookie_consent' => 1
            ],
            'trigger_distribution' => [
                'delay' => 5,
                'immediate' => 3,
                'exit_intent' => 2,
                'scroll' => 2
            ]
        ];
    }

    private function getPopupTypes()
    {
        return [
            'newsletter' => 'Newsletter Signup',
            'promotion' => 'Promotional Offer',
            'announcement' => 'Announcement',
            'exit_intent' => 'Exit Intent',
            'cookie_consent' => 'Cookie Consent',
            'age_verification' => 'Age Verification'
        ];
    }

    private function getTriggerTypes()
    {
        return [
            'immediate' => 'Immediate',
            'delay' => 'Time Delay (seconds)',
            'scroll' => 'Scroll Percentage (%)',
            'exit_intent' => 'Exit Intent',
            'page_visit' => 'Page Visit Count'
        ];
    }

    private function getAvailablePages()
    {
        return [
            '/' => 'Homepage',
            '/products' => 'Products Page',
            '/categories' => 'Categories Page',
            '/about' => 'About Page',
            '/contact' => 'Contact Page',
            '/cart' => 'Shopping Cart',
            '/checkout' => 'Checkout Page'
        ];
    }

    private function getDeviceTypes()
    {
        return [
            'desktop' => 'Desktop',
            'tablet' => 'Tablet',
            'mobile' => 'Mobile'
        ];
    }

    private function getUserTypes()
    {
        return [
            'all' => 'All Users',
            'guests' => 'Guest Users',
            'registered' => 'Registered Users',
            'new_visitors' => 'New Visitors',
            'returning_visitors' => 'Returning Visitors'
        ];
    }

    private function createPopup($data)
    {
        return Popup::create($data);
    }

    private function findPopup($id)
    {
        return Popup::find($id);
    }

    private function updatePopup($id, $data)
    {
        $popup = Popup::findOrFail($id);
        $popup->update($data);
        return $popup;
    }

    private function deletePopup($id)
    {
        try {
            $popup = Popup::findOrFail($id);
            $popup->delete();
            Log::info('Popup deleted', ['id' => $id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete popup', ['id' => $id, 'error' => $e->getMessage()]);
            return false;
        }
    }

    private function getPopupAnalytics($popupId)
    {
        // Mock analytics data - replace with actual analytics query
        return [
            'total_displays' => 2450,
            'total_clicks' => 312,
            'total_conversions' => 245,
            'ctr' => 12.73, // Click-through rate
            'cvr' => 10.0, // Conversion rate
            'daily_displays' => [
                '2025-07-20' => 245,
                '2025-07-21' => 312,
                '2025-07-22' => 289,
                '2025-07-23' => 356,
                '2025-07-24' => 334,
                '2025-07-25' => 189
            ],
            'daily_clicks' => [
                '2025-07-20' => 31,
                '2025-07-21' => 42,
                '2025-07-22' => 35,
                '2025-07-23' => 48,
                '2025-07-24' => 41,
                '2025-07-25' => 22
            ],
            'daily_conversions' => [
                '2025-07-20' => 25,
                '2025-07-21' => 32,
                '2025-07-22' => 28,
                '2025-07-23' => 35,
                '2025-07-24' => 30,
                '2025-07-25' => 18
            ]
        ];
    }

    private function isPopupInDateRange($popup)
    {
        $now = now();
        
        if ($popup['start_date'] && $now->lt(Carbon::parse($popup['start_date']))) {
            return false;
        }
        
        if ($popup['end_date'] && $now->gt(Carbon::parse($popup['end_date']))) {
            return false;
        }
        
        return true;
    }

    private function detectDevice($request)
    {
        // Mock device detection - replace with actual device detection
        $userAgent = $request->header('User-Agent');
        if (strpos($userAgent, 'Mobile') !== false) {
            return 'mobile';
        } elseif (strpos($userAgent, 'Tablet') !== false) {
            return 'tablet';
        }
        return 'desktop';
    }

    private function detectUserType($request)
    {
        // Mock user type detection - replace with actual user detection
        if (Auth::check()) {
            return 'registered';
        }
        return 'guests';
    }

    private function getActivePopupsQuery($page, $device, $userType)
    {
        $popups = $this->getPopupsQuery()->where('is_active', true);
        
        // Filter by date range
        $popups = $popups->filter(function($popup) {
            return $this->isPopupInDateRange($popup);
        });
        
        // Filter by device
        $popups = $popups->filter(function($popup) use ($device) {
            $targetDevices = json_decode($popup['target_devices'], true);
            return in_array($device, $targetDevices);
        });
        
        // Filter by user type
        $popups = $popups->filter(function($popup) use ($userType) {
            $targetUsers = json_decode($popup['target_users'], true);
            return in_array('all', $targetUsers) || in_array($userType, $targetUsers);
        });
        
        // Filter by page
        $popups = $popups->filter(function($popup) use ($page) {
            $targetPages = json_decode($popup['target_pages'], true);
            $excludePages = json_decode($popup['exclude_pages'], true);
            
            // Check exclude pages first
            if ($excludePages && in_array($page, $excludePages)) {
                return false;
            }
            
            // Check target pages
            if ($targetPages) {
                return in_array('*', $targetPages) || in_array($page, $targetPages);
            }
            
            return true;
        });
        
        // Filter by display limits
        $popups = $popups->filter(function($popup) {
            if ($popup['max_displays'] && $popup['displays'] >= $popup['max_displays']) {
                return false;
            }
            return true;
        });
        
        return $popups->sortByDesc('priority')->values();
    }

    private function incrementDisplays($popupId)
    {
        $popup = Popup::find($popupId);
        if ($popup) {
            $popup->increment('displays');
        }
        Log::info('Popup display tracked', ['popup_id' => $popupId]);
    }

    private function incrementClicks($popupId)
    {
        $popup = Popup::find($popupId);
        if ($popup) {
            $popup->increment('clicks');
        }
        Log::info('Popup click tracked', ['popup_id' => $popupId]);
    }

    private function incrementConversions($popupId)
    {
        $popup = Popup::find($popupId);
        if ($popup) {
            $popup->increment('conversions');
        }
        Log::info('Popup conversion tracked', ['popup_id' => $popupId]);
    }

    private function generateCsvExport($popups, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($popups) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID',
                'Title',
                'Type',
                'Trigger',
                'Status',
                'Displays',
                'Clicks',
                'Conversions',
                'CTR (%)',
                'CVR (%)',
                'Start Date',
                'End Date',
                'Created At'
            ]);
            
            // CSV Data
            foreach ($popups as $popup) {
                $ctr = $popup['displays'] > 0 ? round(($popup['clicks'] / $popup['displays']) * 100, 2) : 0;
                $cvr = $popup['displays'] > 0 ? round(($popup['conversions'] / $popup['displays']) * 100, 2) : 0;
                
                fputcsv($file, [
                    $popup['id'],
                    $popup['title'],
                    ucfirst($popup['type']),
                    ucfirst($popup['trigger_type']),
                    $popup['is_active'] ? 'Active' : 'Inactive',
                    $popup['displays'],
                    $popup['clicks'],
                    $popup['conversions'],
                    $ctr . '%',
                    $cvr . '%',
                    $popup['start_date'] ?? '',
                    $popup['end_date'] ?? '',
                    $popup['created_at']
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
