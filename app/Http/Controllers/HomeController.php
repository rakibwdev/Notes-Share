<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $banners = Banner::where('is_active', true)->get();
        $categories = Category::where('status', true)->get();
        $featured_products = Product::where('status', true)
            ->with(['category', 'primaryImage', 'batches'])
            ->latest()
            ->take(8)
            ->get();

        return view('welcome', compact('banners', 'categories', 'featured_products'));
    }
}
