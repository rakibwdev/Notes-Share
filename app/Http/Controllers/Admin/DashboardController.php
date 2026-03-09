<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $lowStockDays = (int) Setting::getValue('expiry_warning_days', 30);
        $globalLowStock = (int) Setting::getValue('global_low_stock_threshold', 10);

        $stats = [
            'total_sales' => Order::where('status', 'Delivered')->sum('total_price'),
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'total_customers' => Customer::count(),
            'low_stock_count' => Product::all()->filter(fn ($p) => $p->is_low_stock)->count(),
            'expired_stock_count' => Batch::where('expiry_date', '<', now())->count(),
        ];

        $recent_orders = Order::with('customer')->latest()->take(5)->get();
        $expiring_soon = Batch::with('product')
            ->where('expiry_date', '>', now())
            ->where('expiry_date', '<=', now()->addDays($lowStockDays))
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date')
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'expiring_soon', 'lowStockDays', 'globalLowStock'));
    }
}
