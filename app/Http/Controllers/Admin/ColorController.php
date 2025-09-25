<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = [
            [
                'id' => 1,
                'name' => 'Red',
                'code' => '#FF0000',
                'hex_code' => 'FF0000',
                'description' => 'Bright red color',
                'category' => 'Primary',
                'sort_order' => 1,
                'status' => 'Active',
                'products_count' => 25,
                'created_at' => '2024-12-01'
            ],
            [
                'id' => 2,
                'name' => 'Blue',
                'code' => '#0000FF',
                'hex_code' => '0000FF',
                'description' => 'Classic blue color',
                'category' => 'Primary',
                'sort_order' => 2,
                'status' => 'Active',
                'products_count' => 32,
                'created_at' => '2024-12-01'
            ],
            [
                'id' => 3,
                'name' => 'Green',
                'code' => '#008000',
                'hex_code' => '008000',
                'description' => 'Natural green color',
                'category' => 'Primary',
                'sort_order' => 3,
                'status' => 'Active',
                'products_count' => 18,
                'created_at' => '2024-12-01'
            ],
            [
                'id' => 4,
                'name' => 'Black',
                'code' => '#000000',
                'hex_code' => '000000',
                'description' => 'Classic black color',
                'category' => 'Neutral',
                'sort_order' => 1,
                'status' => 'Active',
                'products_count' => 45,
                'created_at' => '2024-12-01'
            ],
            [
                'id' => 5,
                'name' => 'White',
                'code' => '#FFFFFF',
                'hex_code' => 'FFFFFF',
                'description' => 'Pure white color',
                'category' => 'Neutral',
                'sort_order' => 2,
                'status' => 'Active',
                'products_count' => 38,
                'created_at' => '2024-12-01'
            ],
            [
                'id' => 6,
                'name' => 'Purple',
                'code' => '#800080',
                'hex_code' => '800080',
                'description' => 'Royal purple color',
                'category' => 'Secondary',
                'sort_order' => 1,
                'status' => 'Active',
                'products_count' => 15,
                'created_at' => '2024-12-02'
            ]
        ];

        return view('admin.colors.index', compact('colors'));
    }

    public function create()
    {
        $categories = [
            'Primary' => 'Primary Colors',
            'Secondary' => 'Secondary Colors',
            'Neutral' => 'Neutral Colors',
            'Pastel' => 'Pastel Colors',
            'Metallic' => 'Metallic Colors'
        ];

        return view('admin.colors.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|regex:/^#[A-Fa-f0-9]{6}$/',
            'hex_code' => 'required|string|regex:/^[A-Fa-f0-9]{6}$/',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'sort_order' => 'required|integer|min:0',
            'status' => 'required|in:Active,Inactive'
        ]);

        // Here you would save to database
        // Color::create($request->all());

        return redirect()->route('admin.colors.index')->with('success', 'Color created successfully!');
    }

    public function show($id)
    {
        $color = [
            'id' => $id,
            'name' => 'Blue',
            'code' => '#0000FF',
            'hex_code' => '0000FF',
            'description' => 'Classic blue color',
            'category' => 'Primary',
            'sort_order' => 2,
            'status' => 'Active',
            'products_count' => 32,
            'created_at' => '2024-12-01 10:30:00',
            'updated_at' => '2025-07-20 15:45:00'
        ];

        return view('admin.colors.show', compact('color'));
    }

    public function edit($id)
    {
        $color = [
            'id' => $id,
            'name' => 'Blue',
            'code' => '#0000FF',
            'hex_code' => '0000FF',
            'description' => 'Classic blue color',
            'category' => 'Primary',
            'sort_order' => 2,
            'status' => 'Active'
        ];

        $categories = [
            'Primary' => 'Primary Colors',
            'Secondary' => 'Secondary Colors',
            'Neutral' => 'Neutral Colors',
            'Pastel' => 'Pastel Colors',
            'Metallic' => 'Metallic Colors'
        ];

        return view('admin.colors.edit', compact('color', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|regex:/^#[A-Fa-f0-9]{6}$/',
            'hex_code' => 'required|string|regex:/^[A-Fa-f0-9]{6}$/',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'sort_order' => 'required|integer|min:0',
            'status' => 'required|in:Active,Inactive'
        ]);

        // Here you would update in database
        // Color::find($id)->update($request->all());

        return redirect()->route('admin.colors.index')->with('success', 'Color updated successfully!');
    }

    public function destroy($id)
    {
        // Here you would delete from database
        // Color::find($id)->delete();

        return redirect()->route('admin.colors.index')->with('success', 'Color deleted successfully!');
    }
}
