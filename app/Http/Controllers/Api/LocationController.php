<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function getDistricts()
    {
        try {
            $districts = DB::table('delivery_charges')
                ->select('district')
                ->distinct()
                ->orderBy('district')
                ->get();

            return response()->json($districts);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load districts'], 500);
        }
    }

    public function getUpazilas($district)
    {
        try {
            $upazilas = DB::table('delivery_charges')
                ->select('upazila')
                ->where('district', $district)
                ->distinct()
                ->orderBy('upazila')
                ->get();

            return response()->json($upazilas);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load upazilas'], 500);
        }
    }

    public function getWards($district, $upazila)
    {
        try {
            $wards = DB::table('delivery_charges')
                ->select('ward')
                ->where('district', $district)
                ->where('upazila', $upazila)
                ->where('ward', '!=', '')
                ->whereNotNull('ward')
                ->distinct()
                ->orderBy('ward')
                ->get();

            return response()->json($wards);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load wards'], 500);
        }
    }
}