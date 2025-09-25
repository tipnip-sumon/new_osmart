<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function sales()
    {
        return view('admin.reports.sales');
    }

    public function products()
    {
        return view('admin.reports.products');
    }

    public function vendors()
    {
        return view('admin.reports.vendors');
    }

    public function customers()
    {
        return view('admin.reports.customers');
    }

    public function exportSales(Request $request)
    {
        // Export sales report logic
        return response()->json(['success' => true]);
    }

    public function exportProducts(Request $request)
    {
        // Export products report logic
        return response()->json(['success' => true]);
    }
}
