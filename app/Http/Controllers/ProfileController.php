<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $orders = Order::where('customer_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('profile.index', compact('user', 'orders'));
    }

    public function showOrder(Order $order): View
    {
        // Ensure the user owns the order
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['orderItems.product', 'deliveryMan']);

        return view('profile.order-details', compact('order'));
    }
}
