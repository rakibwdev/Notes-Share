<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Batch;
use App\Models\Customer;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_sales' => Order::where('status', 'Delivered')->sum('total_price'),
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'total_customers' => Customer::count(),
            'low_stock_count' => Product::all()->filter(fn($p) => $p->total_stock < 10)->count(),
            'expired_stock_count' => Batch::where('expiry_date', '<', now())->count(),
        ];

        $recent_orders = Order::with('customer')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_orders'));
    }
}
