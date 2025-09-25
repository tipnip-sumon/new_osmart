<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public function index()
    {
        return view('admin.marketing.index');
    }

    public function banners()
    {
        return view('admin.marketing.banners');
    }

    public function promotions()
    {
        return view('admin.marketing.promotions');
    }

    public function newsletters()
    {
        return view('admin.marketing.newsletters');
    }

    public function createBanner()
    {
        return view('admin.marketing.banners.create');
    }

    public function storeBanner(Request $request)
    {
        // Store banner logic
        return redirect()->route('admin.marketing.banners')->with('success', 'Banner created successfully!');
    }

    public function sendNewsletter(Request $request)
    {
        // Send newsletter logic
        return response()->json(['success' => true]);
    }
}
