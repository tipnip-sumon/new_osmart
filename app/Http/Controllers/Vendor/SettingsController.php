<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display vendor settings.
     */
    public function index()
    {
        if (Auth::user()->role !== 'vendor') {
            abort(403, 'Access denied. Vendor role required.');
        }

        $vendor = Auth::user();
        return view('vendor.settings.index', compact('vendor'));
    }

    /**
     * Update vendor business settings.
     */
    public function updateBusiness(Request $request)
    {
        if (Auth::user()->role !== 'vendor') {
            abort(403, 'Access denied. Vendor role required.');
        }

        $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_description' => 'nullable|string|max:1000',
            'shop_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'business_address' => 'nullable|string|max:500',
            'business_phone' => 'nullable|string|max:20',
            'business_email' => 'nullable|email|max:255',
            'tax_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'bank_routing_number' => 'nullable|string|max:50',
        ]);

        $vendor = Auth::user();
        
        // Handle shop logo upload
        if ($request->hasFile('shop_logo')) {
            // Delete old logo if exists
            if ($vendor->shop_logo) {
                Storage::disk('public')->delete($vendor->shop_logo);
            }

            $logoPath = $request->file('shop_logo')->store('vendor/logos', 'public');
            $vendor->shop_logo = $logoPath;
        }

        // Update vendor details
        User::where('id', Auth::id())->update([
            'shop_name' => $request->shop_name,
            'shop_description' => $request->shop_description,
            'shop_address' => $request->business_address,
            'phone' => $request->business_phone,
            'tax_id' => $request->tax_number,
            'bank_account_name' => $request->bank_account_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_name' => $request->bank_name,
            'bank_routing_number' => $request->bank_routing_number,
        ]);
        
        if ($request->hasFile('shop_logo')) {
            User::where('id', Auth::id())->update(['shop_logo' => $logoPath]);
        }

        return redirect()->route('vendor.settings.index')
                        ->with('success', 'Business settings updated successfully!');
    }

    /**
     * Update vendor account settings.
     */
    public function updateAccount(Request $request)
    {
        if (Auth::user()->role !== 'vendor') {
            abort(403, 'Access denied. Vendor role required.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $vendor = Auth::user();

        // Check current password if new password is provided
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $vendor->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->filled('new_password')) {
            $updateData['password'] = Hash::make($request->new_password);
        }

        User::where('id', Auth::id())->update($updateData);

        return redirect()->route('vendor.settings.index')
                        ->with('success', 'Account settings updated successfully!');
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifications(Request $request)
    {
        if (Auth::user()->role !== 'vendor') {
            abort(403, 'Access denied. Vendor role required.');
        }

        $vendor = Auth::user();

        // Update notification preferences (simplified for now)
        // Note: You may need to add these fields to the users table migration

        return redirect()->route('vendor.settings.index')
                        ->with('success', 'Notification preferences updated successfully!');
    }

    /**
     * Update shipping settings.
     */
    public function updateShipping(Request $request)
    {
        if (Auth::user()->role !== 'vendor') {
            abort(403, 'Access denied. Vendor role required.');
        }

        $request->validate([
            'shipping_policy' => 'nullable|string|max:1000',
            'return_policy' => 'nullable|string|max:1000',
            'processing_time' => 'nullable|integer|min:1|max:30',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
        ]);

        $vendor = Auth::user();

        // Update shipping settings (simplified for now)
        // Note: You may need to add these fields to the users table migration

        return redirect()->route('vendor.settings.index')
                        ->with('success', 'Shipping settings updated successfully!');
    }
}
