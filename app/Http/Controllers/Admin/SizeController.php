<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = [
            [
                'id' => 1,
                'name' => 'Small',
                'code' => 'S',
                'description' => 'Small size for clothing and accessories',
                'category' => 'Clothing',
                'sort_order' => 1,
                'status' => 'Active',
                'products_count' => 45,
                'created_at' => '2024-12-01'
            ],
            [
                'id' => 2,
                'name' => 'Medium',
                'code' => 'M',
                'description' => 'Medium size for clothing and accessories',
                'category' => 'Clothing',
                'sort_order' => 2,
                'status' => 'Active',
                'products_count' => 52,
                'created_at' => '2024-12-01'
            ],
            [
                'id' => 3,
                'name' => 'Large',
                'code' => 'L',
                'description' => 'Large size for clothing and accessories',
                'category' => 'Clothing',
                'sort_order' => 3,
                'status' => 'Active',
                'products_count' => 38,
                'created_at' => '2024-12-01'
            ],
            [
                'id' => 4,
                'name' => 'Extra Large',
                'code' => 'XL',
                'description' => 'Extra large size for clothing and accessories',
                'category' => 'Clothing',
                'sort_order' => 4,
                'status' => 'Active',
                'products_count' => 28,
                'created_at' => '2024-12-01'
            ],
            [
                'id' => 5,
                'name' => '250ml',
                'code' => '250ML',
                'description' => '250 milliliters for liquid products',
                'category' => 'Skincare',
                'sort_order' => 1,
                'status' => 'Active',
                'products_count' => 15,
                'created_at' => '2024-12-02'
            ],
            [
                'id' => 6,
                'name' => '500ml',
                'code' => '500ML',
                'description' => '500 milliliters for liquid products',
                'category' => 'Skincare',
                'sort_order' => 2,
                'status' => 'Active',
                'products_count' => 22,
                'created_at' => '2024-12-02'
            ]
        ];

        return view('admin.sizes.index', compact('sizes'));
    }

    public function create()
    {
        $categories = [
            'Clothing' => 'Clothing & Apparel',
            'Skincare' => 'Skincare & Beauty',
            'Supplements' => 'Health Supplements',
            'Electronics' => 'Electronics & Gadgets'
        ];

        return view('admin.sizes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:sizes',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'sort_order' => 'required|integer|min:0',
            'status' => 'required|in:Active,Inactive'
        ]);

        // Here you would save to database
        // Size::create($request->all());

        return redirect()->route('admin.sizes.index')->with('success', 'Size created successfully!');
    }

    public function show($id)
    {
        $size = [
            'id' => $id,
            'name' => 'Medium',
            'code' => 'M',
            'description' => 'Medium size for clothing and accessories',
            'category' => 'Clothing',
            'sort_order' => 2,
            'status' => 'Active',
            'products_count' => 52,
            'created_at' => '2024-12-01 10:30:00',
            'updated_at' => '2025-07-20 15:45:00'
        ];

        return view('admin.sizes.show', compact('size'));
    }

    public function edit($id)
    {
        $size = [
            'id' => $id,
            'name' => 'Medium',
            'code' => 'M',
            'description' => 'Medium size for clothing and accessories',
            'category' => 'Clothing',
            'sort_order' => 2,
            'status' => 'Active'
        ];

        $categories = [
            'Clothing' => 'Clothing & Apparel',
            'Skincare' => 'Skincare & Beauty',
            'Supplements' => 'Health Supplements',
            'Electronics' => 'Electronics & Gadgets'
        ];

        return view('admin.sizes.edit', compact('size', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:sizes,code,' . $id,
            'description' => 'nullable|string',
            'category' => 'required|string',
            'sort_order' => 'required|integer|min:0',
            'status' => 'required|in:Active,Inactive'
        ]);

        // Here you would update in database
        // Size::find($id)->update($request->all());

        return redirect()->route('admin.sizes.index')->with('success', 'Size updated successfully!');
    }

    public function destroy($id)
    {
        // Here you would delete from database
        // Size::find($id)->delete();

        return redirect()->route('admin.sizes.index')->with('success', 'Size deleted successfully!');
    }
}
