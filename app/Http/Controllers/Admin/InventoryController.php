<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function stock()
    {
        return view('admin.inventory.stock');
    }

    public function lowStock()
    {
        return view('admin.inventory.low-stock');
    }

    public function outOfStock()
    {
        return view('admin.inventory.out-of-stock');
    }

    public function updateStock(Request $request, $id)
    {
        // Stock update logic
        return response()->json(['success' => true]);
    }

    public function bulkUpdateStock(Request $request)
    {
        // Bulk stock update logic
        return response()->json(['success' => true]);
    }
}
