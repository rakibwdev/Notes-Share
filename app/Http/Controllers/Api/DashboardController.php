<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Get analytics for the pharmacy dashboard.
     */
    public function stats(): JsonResponse
    {
        $totalOrders = Order::count();
        $totalSales = Order::where('status', 'Delivered')->sum('total_price');
        $totalProducts = Product::count();

        // Low stock products (total stock < 10)
        $lowStockProducts = Product::all()->filter(function ($product) {
            return $product->total_stock < 10;
        })->count();

        // Expired stock count (batches where expiry_date < today)
        $expiredBatches = Batch::where('expiry_date', '<', now())->count();

        return response()->json([
            'total_orders' => $totalOrders,
            'total_sales' => round($totalSales, 2),
            'total_products' => $totalProducts,
            'low_stock_products' => $lowStockProducts,
            'expired_stock_count' => $expiredBatches,
            'order_status_breakdown' => [
                'pending' => Order::where('status', 'Pending')->count(),
                'confirmed' => Order::where('status', 'Confirmed')->count(),
                'delivered' => Order::where('status', 'Delivered')->count(),
                'cancelled' => Order::where('status', 'Cancelled')->count(),
            ],
        ]);
    }
}
