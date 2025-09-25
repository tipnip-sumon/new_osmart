<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryChargeController extends Controller
{
    /**
     * Display a listing of delivery charges.
     */
    public function index(Request $request)
    {
        $query = DeliveryCharge::query();
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('district', 'like', "%{$search}%")
                  ->orWhere('upazila', 'like', "%{$search}%")
                  ->orWhere('ward', 'like', "%{$search}%");
            });
        }
        
        // Filter by district
        if ($request->has('district') && !empty($request->district)) {
            $query->where('district', $request->district);
        }
        
        $deliveryCharges = $query->orderBy('district', 'asc')
            ->orderBy('upazila', 'asc')
            ->orderBy('ward', 'asc')
            ->paginate(20)
            ->withQueryString();
            
        // Get unique districts for filter dropdown
        $districts = DeliveryCharge::distinct()
            ->orderBy('district')
            ->pluck('district');
        
        return view('admin.delivery-charges.index', compact('deliveryCharges', 'districts'));
    }

    /**
     * Show the form for creating a new delivery charge.
     */
    public function create()
    {
        // Load Bangladesh location data
        $bangladeshData = json_decode(file_get_contents(public_path('data/bangladesh-locations.json')), true);
        
        return view('admin.delivery-charges.create', compact('bangladeshData'));
    }

    /**
     * Store a newly created delivery charge.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'district' => 'required|string|max:100',
            'upazila' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100',
            'charge' => 'required|numeric|min:0|max:9999.99',
            'estimated_delivery_time' => 'nullable|string|max:100',
        ], [
            'district.required' => 'District is required.',
            'charge.required' => 'Delivery charge is required.',
            'charge.numeric' => 'Delivery charge must be a valid number.',
            'charge.min' => 'Delivery charge cannot be negative.',
            'charge.max' => 'Delivery charge cannot exceed ৳9,999.99.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if combination already exists
        $exists = DeliveryCharge::where('district', $request->district)
            ->where('upazila', $request->upazila)
            ->where('ward', $request->ward)
            ->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withErrors(['district' => 'A delivery charge for this location combination already exists.'])
                ->withInput();
        }

        DeliveryCharge::create([
            'district' => $request->district,
            'upazila' => $request->upazila,
            'ward' => $request->ward,
            'charge' => $request->charge,
            'estimated_delivery_time' => $request->estimated_delivery_time ?: '3-5 business days',
        ]);

        return redirect()
            ->route('admin.delivery-charges.index')
            ->with('success', 'Delivery charge created successfully!');
    }

    /**
     * Display the specified delivery charge.
     */
    public function show(DeliveryCharge $deliveryCharge)
    {
        return view('admin.delivery-charges.show', compact('deliveryCharge'));
    }

    /**
     * Show the form for editing the specified delivery charge.
     */
    public function edit(DeliveryCharge $deliveryCharge)
    {
        // Load Bangladesh location data
        $bangladeshData = json_decode(file_get_contents(public_path('data/bangladesh-locations.json')), true);
        
        return view('admin.delivery-charges.edit', compact('deliveryCharge', 'bangladeshData'));
    }

    /**
     * Update the specified delivery charge.
     */
    public function update(Request $request, DeliveryCharge $deliveryCharge)
    {
        $validator = Validator::make($request->all(), [
            'district' => 'required|string|max:100',
            'upazila' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100',
            'charge' => 'required|numeric|min:0|max:9999.99',
            'estimated_delivery_time' => 'nullable|string|max:100',
        ], [
            'district.required' => 'District is required.',
            'charge.required' => 'Delivery charge is required.',
            'charge.numeric' => 'Delivery charge must be a valid number.',
            'charge.min' => 'Delivery charge cannot be negative.',
            'charge.max' => 'Delivery charge cannot exceed ৳9,999.99.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if combination already exists (excluding current record)
        $exists = DeliveryCharge::where('district', $request->district)
            ->where('upazila', $request->upazila)
            ->where('ward', $request->ward)
            ->where('id', '!=', $deliveryCharge->id)
            ->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withErrors(['district' => 'A delivery charge for this location combination already exists.'])
                ->withInput();
        }

        $deliveryCharge->update([
            'district' => $request->district,
            'upazila' => $request->upazila,
            'ward' => $request->ward,
            'charge' => $request->charge,
            'estimated_delivery_time' => $request->estimated_delivery_time ?: '3-5 business days',
        ]);

        return redirect()
            ->route('admin.delivery-charges.index')
            ->with('success', 'Delivery charge updated successfully!');
    }

    /**
     * Remove the specified delivery charge.
     */
    public function destroy(DeliveryCharge $deliveryCharge)
    {
        try {
            $deliveryCharge->delete();
            
            return redirect()
                ->route('admin.delivery-charges.index')
                ->with('success', 'Delivery charge deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.delivery-charges.index')
                ->with('error', 'Failed to delete delivery charge. Please try again.');
        }
    }

    /**
     * Bulk import delivery charges.
     */
    public function bulkImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        
        // Remove header row
        $header = array_shift($data);
        
        $imported = 0;
        $errors = [];
        
        foreach ($data as $index => $row) {
            $line = $index + 2; // +2 because we removed header and arrays are 0-indexed
            
            if (count($row) < 4) {
                $errors[] = "Line {$line}: Insufficient columns";
                continue;
            }
            
            $district = trim($row[0]);
            $upazila = trim($row[1]) ?: null;
            $ward = trim($row[2]) ?: null;
            $charge = trim($row[3]);
            $estimatedTime = isset($row[4]) ? trim($row[4]) : '3-5 business days';
            
            if (empty($district) || !is_numeric($charge)) {
                $errors[] = "Line {$line}: Invalid district or charge";
                continue;
            }
            
            // Check if exists
            $exists = DeliveryCharge::where('district', $district)
                ->where('upazila', $upazila)
                ->where('ward', $ward)
                ->exists();
                
            if (!$exists) {
                DeliveryCharge::create([
                    'district' => $district,
                    'upazila' => $upazila,
                    'ward' => $ward,
                    'charge' => floatval($charge),
                    'estimated_delivery_time' => $estimatedTime,
                ]);
                $imported++;
            }
        }
        
        $message = "Imported {$imported} delivery charges successfully.";
        if (!empty($errors)) {
            $message .= " " . count($errors) . " errors occurred.";
        }
        
        return redirect()
            ->route('admin.delivery-charges.index')
            ->with('success', $message);
    }
}