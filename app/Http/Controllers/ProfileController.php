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
        
        // Ensure user has a linked customer record if they have placed orders before
        if (!$user->customer && $user->phone) {
            $customer = \App\Models\Customer::where('phone', $user->phone)->first();
            if ($customer && !$customer->user_id) {
                $customer->update(['user_id' => $user->id]);
            }
        }

        $orders = collect();
        if ($user->customer) {
            $orders = Order::where('customer_id', $user->customer->id)
                ->latest()
                ->paginate(10);
        } else {
            // Create a fake paginator for empty orders to avoid errors in view
            $orders = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }

        return view('profile.index', compact('user', 'orders'));
    }

    public function showOrder(Order $order): View
    {
        // Ensure the user owns the order through their customer record
        if (!auth()->user()->customer || $order->customer_id !== auth()->user()->customer->id) {
            abort(403);
        }

        $order->load(['orderItems.product', 'deliveryMan']);

        return view('profile.order-details', compact('order'));
    }
}
