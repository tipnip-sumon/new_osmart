<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function pages()
    {
        return view('admin.website.pages');
    }

    public function menus()
    {
        return view('admin.website.menus');
    }

    public function themes()
    {
        return view('admin.website.themes');
    }

    public function seo()
    {
        return view('admin.website.seo');
    }

    public function createPage()
    {
        return view('admin.website.pages.create');
    }

    public function storePage(Request $request)
    {
        // Store page logic
        return redirect()->route('admin.website.pages')->with('success', 'Page created successfully!');
    }

    public function updateSeo(Request $request)
    {
        // Update SEO settings logic
        return response()->json(['success' => true]);
    }
}
