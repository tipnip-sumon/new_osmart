<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageLinkSharingSetting;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminPackageLinkSharingController extends Controller
{
    /**
     * Display a listing of package settings.
     */
    public function index()
    {
        $settings = PackageLinkSharingSetting::with('plan')->orderBy('package_name')->paginate(10);
        return view('admin.package-link-sharing.index', compact('settings'));
    }

    /**
     * Show the form for creating a new package setting.
     */
    public function create()
    {
        $plans = Plan::orderBy('name')->get();
        return view('admin.package-link-sharing.create', compact('plans'));
    }

    /**
     * Store a newly created package setting.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'nullable|exists:plans,id',
            'package_name' => 'required_without:plan_id|string|max:255',
            'daily_share_limit' => 'required|integer|min:1|max:1000',
            'click_reward_amount' => 'required|numeric|min:0|max:1000',
            'daily_earning_limit' => 'required|numeric|min:0|max:10000',
            'total_share_limit' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
            'conditions_json' => 'nullable|json'
        ]);

        // Check for duplicate plan_id if provided
        if ($request->plan_id) {
            $existing = PackageLinkSharingSetting::where('plan_id', $request->plan_id)->first();
            if ($existing) {
                return redirect()->back()
                    ->withErrors(['plan_id' => 'Package setting for this plan already exists.'])
                    ->withInput();
            }
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['is_active'] = (bool) $request->input('is_active', false);
        
        // If plan is selected, derive package name from plan
        if ($request->plan_id) {
            $plan = Plan::find($request->plan_id);
            $data['package_name'] = strtolower(str_replace(' ', '_', $plan->name));
        }
        
        // Handle conditions JSON
        if ($request->filled('conditions_json')) {
            try {
                $data['conditions'] = json_decode($request->conditions_json, true);
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withErrors(['conditions_json' => 'Invalid JSON format'])
                    ->withInput();
            }
        }

        PackageLinkSharingSetting::create($data);

        return redirect()->route('admin.package-link-sharing.index')
            ->with('success', 'Package setting created successfully.');
    }

    /**
     * Display the specified package setting.
     */
    public function show(PackageLinkSharingSetting $packageLinkSharingSetting)
    {
        return view('admin.package-link-sharing.show', compact('packageLinkSharingSetting'));
    }

    /**
     * Show the form for editing the specified package setting.
     */
    public function edit(PackageLinkSharingSetting $packageLinkSharingSetting)
    {
        $plans = Plan::orderBy('name')->get();
        return view('admin.package-link-sharing.edit', compact('packageLinkSharingSetting', 'plans'));
    }

    /**
     * Update the specified package setting.
     */
    public function update(Request $request, PackageLinkSharingSetting $packageLinkSharingSetting)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'nullable|exists:plans,id',
            'package_name' => 'required_without:plan_id|string|max:255',
            'daily_share_limit' => 'required|integer|min:1|max:1000',
            'click_reward_amount' => 'required|numeric|min:0|max:1000',
            'daily_earning_limit' => 'required|numeric|min:0|max:10000',
            'total_share_limit' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
            'conditions_json' => 'nullable|json'
        ]);

        // Check for duplicate plan_id if provided and different from current
        if ($request->plan_id && $request->plan_id != $packageLinkSharingSetting->plan_id) {
            $existing = PackageLinkSharingSetting::where('plan_id', $request->plan_id)->first();
            if ($existing) {
                return redirect()->back()
                    ->withErrors(['plan_id' => 'Package setting for this plan already exists.'])
                    ->withInput();
            }
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['is_active'] = (bool) $request->input('is_active', false);
        
        // If plan is selected, derive package name from plan
        if ($request->plan_id) {
            $plan = Plan::find($request->plan_id);
            $data['package_name'] = strtolower(str_replace(' ', '_', $plan->name));
        }
        
        // Handle conditions JSON
        if ($request->filled('conditions_json')) {
            try {
                $data['conditions'] = json_decode($request->conditions_json, true);
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withErrors(['conditions_json' => 'Invalid JSON format'])
                    ->withInput();
            }
        }

        $packageLinkSharingSetting->update($data);

        return redirect()->route('admin.package-link-sharing.index')
            ->with('success', 'Package setting updated successfully.');
    }

    /**
     * Remove the specified package setting.
     */
    public function destroy(PackageLinkSharingSetting $packageLinkSharingSetting)
    {
        $packageLinkSharingSetting->delete();

        return redirect()->route('admin.package-link-sharing.index')
            ->with('success', 'Package setting deleted successfully.');
    }

    /**
     * Toggle active status of package setting.
     */
    public function toggleActive(PackageLinkSharingSetting $packageLinkSharingSetting)
    {
        $packageLinkSharingSetting->update([
            'is_active' => !$packageLinkSharingSetting->is_active
        ]);

        $status = $packageLinkSharingSetting->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('admin.package-link-sharing.index')
            ->with('success', "Package setting {$status} successfully.");
    }

    /**
     * Get statistics for package link sharing
     */
    public function statistics()
    {
        $stats = [
            'total_packages' => PackageLinkSharingSetting::count(),
            'active_packages' => PackageLinkSharingSetting::where('is_active', true)->count(),
            'total_shares_today' => \App\Models\AffiliateLinkShare::whereDate('share_date', today())->count(),
            'total_earnings_today' => \App\Models\DailyLinkSharingStat::whereDate('date', today())->sum('earnings_amount'),
            'top_performers' => \App\Models\DailyLinkSharingStat::with('user')
                ->whereDate('date', today())
                ->orderBy('earnings_amount', 'desc')
                ->limit(10)
                ->get()
        ];

        return view('admin.package-link-sharing.statistics', compact('stats'));
    }
}
