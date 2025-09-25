<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index()
    {
        $taxes = [
            [
                'id' => 1,
                'name' => 'Standard VAT',
                'code' => 'VAT_STD',
                'rate' => 20.00,
                'type' => 'Percentage',
                'description' => 'Standard VAT rate for most products',
                'country' => 'United Kingdom',
                'state' => null,
                'is_compound' => false,
                'status' => 'Active',
                'products_count' => 85,
                'created_at' => '2024-12-01'
            ],
            [
                'id' => 2,
                'name' => 'Reduced VAT',
                'code' => 'VAT_RED',
                'rate' => 5.00,
                'type' => 'Percentage',
                'description' => 'Reduced VAT rate for essential goods',
                'country' => 'United Kingdom',
                'state' => null,
                'is_compound' => false,
                'status' => 'Active',
                'products_count' => 25,
                'created_at' => '2024-12-01'
            ],
            [
                'id' => 3,
                'name' => 'US Sales Tax',
                'code' => 'US_SALES',
                'rate' => 8.50,
                'type' => 'Percentage',
                'description' => 'Sales tax for California',
                'country' => 'United States',
                'state' => 'California',
                'is_compound' => false,
                'status' => 'Active',
                'products_count' => 62,
                'created_at' => '2024-12-02'
            ],
            [
                'id' => 4,
                'name' => 'GST',
                'code' => 'GST_CAN',
                'rate' => 5.00,
                'type' => 'Percentage',
                'description' => 'Goods and Services Tax for Canada',
                'country' => 'Canada',
                'state' => null,
                'is_compound' => false,
                'status' => 'Active',
                'products_count' => 42,
                'created_at' => '2024-12-03'
            ],
            [
                'id' => 5,
                'name' => 'PST Ontario',
                'code' => 'PST_ON',
                'rate' => 8.00,
                'type' => 'Percentage',
                'description' => 'Provincial Sales Tax for Ontario',
                'country' => 'Canada',
                'state' => 'Ontario',
                'is_compound' => true,
                'status' => 'Active',
                'products_count' => 38,
                'created_at' => '2024-12-03'
            ],
            [
                'id' => 6,
                'name' => 'Luxury Tax',
                'code' => 'LUX_TAX',
                'rate' => 15.00,
                'type' => 'Fixed',
                'description' => 'Fixed luxury tax for high-end products',
                'country' => 'Global',
                'state' => null,
                'is_compound' => false,
                'status' => 'Inactive',
                'products_count' => 8,
                'created_at' => '2024-12-04'
            ]
        ];

        return view('admin.taxes.index', compact('taxes'));
    }

    public function create()
    {
        $countries = [
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'CA' => 'Canada',
            'AU' => 'Australia',
            'DE' => 'Germany',
            'FR' => 'France',
            'Global' => 'Global (All Countries)'
        ];

        $states = [
            'US' => [
                'CA' => 'California',
                'NY' => 'New York',
                'TX' => 'Texas',
                'FL' => 'Florida'
            ],
            'CA' => [
                'ON' => 'Ontario',
                'BC' => 'British Columbia',
                'AB' => 'Alberta',
                'QC' => 'Quebec'
            ]
        ];

        return view('admin.taxes.create', compact('countries', 'states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:taxes',
            'rate' => 'required|numeric|min:0|max:100',
            'type' => 'required|in:Percentage,Fixed',
            'description' => 'nullable|string',
            'country' => 'required|string',
            'state' => 'nullable|string',
            'is_compound' => 'boolean',
            'status' => 'required|in:Active,Inactive'
        ]);

        // Here you would save to database
        // Tax::create($request->all());

        return redirect()->route('admin.taxes.index')->with('success', 'Tax created successfully!');
    }

    public function show($id)
    {
        $tax = [
            'id' => $id,
            'name' => 'Standard VAT',
            'code' => 'VAT_STD',
            'rate' => 20.00,
            'type' => 'Percentage',
            'description' => 'Standard VAT rate for most products',
            'country' => 'United Kingdom',
            'state' => null,
            'is_compound' => false,
            'status' => 'Active',
            'products_count' => 85,
            'created_at' => '2024-12-01 10:30:00',
            'updated_at' => '2025-07-20 15:45:00'
        ];

        return view('admin.taxes.show', compact('tax'));
    }

    public function edit($id)
    {
        $tax = [
            'id' => $id,
            'name' => 'Standard VAT',
            'code' => 'VAT_STD',
            'rate' => 20.00,
            'type' => 'Percentage',
            'description' => 'Standard VAT rate for most products',
            'country' => 'United Kingdom',
            'state' => null,
            'is_compound' => false,
            'status' => 'Active'
        ];

        $countries = [
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'CA' => 'Canada',
            'AU' => 'Australia',
            'DE' => 'Germany',
            'FR' => 'France',
            'Global' => 'Global (All Countries)'
        ];

        $states = [
            'US' => [
                'CA' => 'California',
                'NY' => 'New York',
                'TX' => 'Texas',
                'FL' => 'Florida'
            ],
            'CA' => [
                'ON' => 'Ontario',
                'BC' => 'British Columbia',
                'AB' => 'Alberta',
                'QC' => 'Quebec'
            ]
        ];

        return view('admin.taxes.edit', compact('tax', 'countries', 'states'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:taxes,code,' . $id,
            'rate' => 'required|numeric|min:0|max:100',
            'type' => 'required|in:Percentage,Fixed',
            'description' => 'nullable|string',
            'country' => 'required|string',
            'state' => 'nullable|string',
            'is_compound' => 'boolean',
            'status' => 'required|in:Active,Inactive'
        ]);

        // Here you would update in database
        // Tax::find($id)->update($request->all());

        return redirect()->route('admin.taxes.index')->with('success', 'Tax updated successfully!');
    }

    public function destroy($id)
    {
        // Here you would delete from database
        // Tax::find($id)->delete();

        return redirect()->route('admin.taxes.index')->with('success', 'Tax deleted successfully!');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'tax_ids' => 'required|array',
            'tax_ids.*' => 'exists:taxes,id'
        ]);

        // Here you would calculate tax based on the rules
        $taxAmount = $request->amount * 0.20; // Example calculation
        $totalAmount = $request->amount + $taxAmount;

        return response()->json([
            'success' => true,
            'original_amount' => $request->amount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount
        ]);
    }
}
