<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PlanController extends Controller
{
    use HandlesImageUploads;
    /**
     * Display a listing of the plans.
     */
    public function index()
    {
        $plans = Plan::orderBy('id', 'desc')->paginate(15);
        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new plan.
     */
    public function create()
    {
        return view('admin.plans.create');
    }

    /**
     * Store a newly created plan in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:40',
            'minimum' => 'required|numeric|min:0',
            'maximum' => 'required|numeric|min:0|gte:minimum',
            'fixed_amount' => 'required|numeric|min:0',
            'points' => 'required|integer|min:0',
            'point_value' => 'required|numeric|min:0',
            'spot_commission_rate' => 'required|numeric|min:0|max:100',
            'fixed_sponsor' => 'required|numeric|min:0',
            'interest' => 'required|numeric|min:0',
            'interest_type' => 'required|boolean',
            'time' => 'required|integer|min:0',
            'time_name' => 'required|string|in:days,weeks,months,years',
            'repeat_time' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            // Daily cashback validation rules
            'daily_cashback_min' => 'nullable|numeric|min:0',
            'daily_cashback_max' => 'nullable|numeric|min:0|gte:daily_cashback_min',
            'cashback_duration_days' => 'nullable|integer|min:0',
            'cashback_type' => 'nullable|string|in:fixed,random,percentage',
            'referral_conditions' => 'nullable|array',
            'referral_conditions.required_direct_referrals' => 'nullable|integer|min:0',
            'referral_conditions.required_team_members' => 'nullable|integer|min:0',
            'referral_conditions.required_team_investment' => 'nullable|numeric|min:0',
            // Point system validation rules
            'minimum_points' => 'nullable|integer|min:0',
            'maximum_points' => 'nullable|integer|min:0|gte:minimum_points',
            'point_to_taka_rate' => 'nullable|numeric|min:0',
            'points_reward' => 'nullable|integer|min:0',
            'point_price' => 'nullable|numeric|min:0',
            'point_terms' => 'nullable|string',
            // Commission validation rules
            'sponsor_commission' => 'nullable|numeric|min:0',
            'binary_matching' => 'nullable|numeric|min:0',
            'binary_left' => 'nullable|numeric|min:0',
            'binary_right' => 'nullable|numeric|min:0',
            'direct_commission' => 'nullable|numeric|min:0',
            // Additional fields
            'category' => 'nullable|string',
            'purchase_type' => 'nullable|string|in:both,wallet,point',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Handle image upload
            $imagePath = null;
            $imageData = null;
            if ($request->hasFile('image')) {
                try {
                    $imageData = $this->uploadCategoryImage($request->file('image'), 'plans');
                    // Use the original size path as the main image path
                    $imagePath = $imageData['sizes']['original']['path'] ?? $imageData['filename'];
                } catch (\Exception $e) {
                    return back()->withErrors(['image' => 'Failed to upload image: ' . $e->getMessage()])->withInput();
                }
            }

            // Debug: Log the request data
            Log::info('Plan creation data:', $request->all());

            Plan::create([
                'name' => $request->name,
                'minimum' => $request->minimum,
                'maximum' => $request->maximum,
                'fixed_amount' => $request->fixed_amount,
                'points' => $request->points,
                'point_value' => $request->point_value,
                'spot_commission_rate' => $request->spot_commission_rate,
                'fixed_sponsor' => $request->fixed_sponsor,
                'instant_activation' => $request->has('instant_activation'),
                'point_based' => $request->has('point_based'),
                'interest' => $request->interest,
                'interest_type' => $request->interest_type,
                'time' => $request->time,
                'time_name' => $request->time_name,
                'status' => $request->has('status'),
                'featured' => $request->has('featured'),
                'capital_back' => $request->has('capital_back'),
                'lifetime' => $request->has('lifetime'),
                'repeat_time' => $request->repeat_time,
                'description' => $request->description,
                'image' => $imagePath,
                'image_data' => $imageData ? json_encode($imageData) : null,
                // Daily Cashback Fields
                'daily_cashback_enabled' => $request->has('daily_cashback_enabled'),
                'daily_cashback_min' => $request->daily_cashback_min ?? 0,
                'daily_cashback_max' => $request->daily_cashback_max ?? 0,
                'cashback_duration_days' => $request->cashback_duration_days ?? 0,
                'cashback_type' => $request->cashback_type ?? 'fixed',
                'is_special_package' => $request->has('is_special_package'),
                'referral_conditions' => $request->referral_conditions ? json_encode($request->referral_conditions) : null,
                'require_referral_for_cashback' => $request->has('require_referral_for_cashback'),
                // Point-based fields
                'points_reward' => $request->points_reward ?? 0,
                'point_price' => $request->point_price ?? 0,
                'minimum_points' => $request->minimum_points ?? 0,
                'maximum_points' => $request->maximum_points ?? 0,
                'wallet_purchase' => $request->has('wallet_purchase'),
                'point_purchase' => $request->has('point_purchase'),
                'point_to_taka_rate' => $request->point_to_taka_rate ?? 1,
                'point_terms' => $request->point_terms,
                // Commission fields
                'sponsor_commission' => $request->sponsor_commission ?? 0,
                'generation_commission' => $request->generation_commission ? json_encode($request->generation_commission) : null,
                'binary_matching' => $request->binary_matching ?? 0,
                'binary_left' => $request->binary_left ?? 0,
                'binary_right' => $request->binary_right ?? 0,
                'direct_commission' => $request->direct_commission ?? 0,
                'level_commission' => $request->level_commission ? json_encode($request->level_commission) : null,
                // Additional fields
                'category' => $request->category,
                'features' => $request->features ? json_encode($request->features) : null,
                'purchase_type' => $request->purchase_type ?? 'both',
                'sort_order' => $request->sort_order ?? 0,
                'is_popular' => $request->has('is_popular'),
                'is_active' => $request->has('is_active') ?? true,
            ]);

            return redirect()->route('admin.plans.index')
                           ->with('success', 'Plan created successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create plan. Please try again.'])
                        ->withInput();
        }
    }

    /**
     * Display the specified plan.
     */
    public function show(Plan $plan)
    {
        return view('admin.plans.show', compact('plan'));
    }

    /**
     * Show the form for editing the specified plan.
     */
    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * Update the specified plan in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:40',
            'minimum' => 'required|numeric|min:0',
            'maximum' => 'required|numeric|min:0|gte:minimum',
            'fixed_amount' => 'required|numeric|min:0',
            'points' => 'required|integer|min:0',
            'point_value' => 'required|numeric|min:0',
            'spot_commission_rate' => 'required|numeric|min:0|max:100',
            'fixed_sponsor' => 'required|numeric|min:0',
            'interest' => 'required|numeric|min:0',
            'interest_type' => 'required|boolean',
            'time' => 'required|integer|min:0',
            'time_name' => 'required|string|in:days,weeks,months,years',
            'repeat_time' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Handle image upload and removal
            $imagePath = $plan->image;
            $imageData = null;
            
            // Handle image removal
            if ($request->has('remove_image') && $request->remove_image) {
                // Delete existing image files
                if ($plan->image_data) {
                    $oldImageData = json_decode($plan->image_data, true);
                    if ($oldImageData) {
                        $this->deleteImageFiles($oldImageData);
                    }
                } elseif ($imagePath) {
                    // Handle legacy image deletion
                    $this->deleteLegacyImageFile($imagePath);
                }
                
                $imagePath = null;
                $imageData = null;
            }
            // Handle new image upload
            elseif ($request->hasFile('image')) {
                // Delete old image files
                if ($plan->image_data) {
                    $oldImageData = json_decode($plan->image_data, true);
                    if ($oldImageData) {
                        $this->deleteImageFiles($oldImageData);
                    }
                } elseif ($imagePath) {
                    // Handle legacy image deletion
                    $this->deleteLegacyImageFile($imagePath);
                }
                
                try {
                    $imageData = $this->uploadCategoryImage($request->file('image'), 'plans');
                    // Use the original size path as the main image path
                    $imagePath = $imageData['sizes']['original']['path'] ?? $imageData['filename'];
                } catch (\Exception $e) {
                    return back()->withErrors(['image' => 'Failed to upload image: ' . $e->getMessage()])->withInput();
                }
            }

            // Update data array
            $updateData = [
                'name' => $request->name,
                'minimum' => $request->minimum,
                'maximum' => $request->maximum,
                'fixed_amount' => $request->fixed_amount,
                'points' => $request->points,
                'point_value' => $request->point_value,
                'spot_commission_rate' => $request->spot_commission_rate,
                'fixed_sponsor' => $request->fixed_sponsor,
                'instant_activation' => $request->has('instant_activation'),
                'point_based' => $request->has('point_based'),
                'interest' => $request->interest,
                'interest_type' => $request->interest_type,
                'time' => $request->time,
                'time_name' => $request->time_name,
                'status' => $request->has('status'),
                'featured' => $request->has('featured'),
                'capital_back' => $request->has('capital_back'),
                'lifetime' => $request->has('lifetime'),
                'repeat_time' => $request->repeat_time,
                'description' => $request->description,
            ];

            // Add image fields if they exist
            if ($imagePath !== null) {
                $updateData['image'] = $imagePath;
            }
            
            if ($imageData !== null) {
                $updateData['image_data'] = json_encode($imageData);
            }

            $plan->update($updateData);

            return redirect()->route('admin.plans.index')
                           ->with('success', 'Plan updated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update plan. Please try again.'])
                        ->withInput();
        }
    }

    /**
     * Remove the specified plan from storage.
     */
    public function destroy(Plan $plan)
    {
        try {
            // Check if plan is being used by any investments
            $investmentCount = \App\Models\Invest::where('plan_id', $plan->id)->count();
            
            if ($investmentCount > 0) {
                return back()->withErrors(['error' => "Cannot delete plan. It is being used by {$investmentCount} investment(s)."]);
            }

            $plan->delete();
            
            return redirect()->route('admin.plans.index')
                           ->with('success', 'Plan deleted successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete plan. Please try again.']);
        }
    }

    /**
     * Toggle plan status
     */
    public function toggleStatus(Plan $plan)
    {
        try {
            $plan->update(['status' => !$plan->status]);
            
            $status = $plan->status ? 'activated' : 'deactivated';
            return response()->json([
                'success' => true,
                'message' => "Plan {$status} successfully!",
                'status' => $plan->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update plan status.'
            ], 500);
        }
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Plan $plan)
    {
        try {
            $plan->update(['featured' => !$plan->featured]);
            
            $status = $plan->featured ? 'featured' : 'unfeatured';
            return response()->json([
                'success' => true,
                'message' => "Plan marked as {$status} successfully!",
                'featured' => $plan->featured
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update featured status.'
            ], 500);
        }
    }
}
