<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryCharge;
use Illuminate\Http\Request;

class DeliveryChargeController extends Controller
{
    /**
     * Get shipping charge for location
     */
    public function getShippingCharge(Request $request)
    {
        $district = $request->get('district');
        $upazila = $request->get('upazila');
        $ward = $request->get('ward');

        if (!$district) {
            return response()->json([
                'error' => 'District is required'
            ], 400);
        }

        $deliveryCharge = DeliveryCharge::findChargeForLocation($district, $upazila, $ward);
        
        return response()->json([
            'success' => true,
            'charge' => $deliveryCharge->charge,
            'formatted_charge' => formatCurrency($deliveryCharge->charge),
            'estimated_delivery_time' => $deliveryCharge->estimated_delivery_time ?? '3-5 days',
            'location_display' => property_exists($deliveryCharge, 'location_display') ? $deliveryCharge->location_display : $district
        ]);
    }

    /**
     * Get available districts
     */
    public function getDistricts()
    {
        $districts = DeliveryCharge::active()
            ->select('district')
            ->distinct()
            ->orderBy('district')
            ->pluck('district');

        return response()->json([
            'success' => true,
            'districts' => $districts
        ]);
    }

    /**
     * Get upazilas for a district
     */
    public function getUpazilas(Request $request)
    {
        $district = $request->get('district');
        
        if (!$district) {
            return response()->json([
                'error' => 'District is required'
            ], 400);
        }

        $upazilas = DeliveryCharge::active()
            ->where('district', $district)
            ->whereNotNull('upazila')
            ->select('upazila')
            ->distinct()
            ->orderBy('upazila')
            ->pluck('upazila');

        return response()->json([
            'success' => true,
            'upazilas' => $upazilas
        ]);
    }

    /**
     * Get wards for a district and upazila
     */
    public function getWards(Request $request)
    {
        $district = $request->get('district');
        $upazila = $request->get('upazila');
        
        if (!$district || !$upazila) {
            return response()->json([
                'error' => 'District and upazila are required'
            ], 400);
        }

        $wards = DeliveryCharge::active()
            ->where('district', $district)
            ->where('upazila', $upazila)
            ->whereNotNull('ward')
            ->select('ward')
            ->distinct()
            ->orderBy('ward')
            ->pluck('ward');

        return response()->json([
            'success' => true,
            'wards' => $wards
        ]);
    }
}