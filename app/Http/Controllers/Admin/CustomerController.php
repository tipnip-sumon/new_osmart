<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.customers.index');
    }

    public function show($id)
    {
        return view('admin.customers.show', compact('id'));
    }

    public function edit($id)
    {
        return view('admin.customers.edit', compact('id'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Status update logic
        return response()->json(['success' => true]);
    }
}
