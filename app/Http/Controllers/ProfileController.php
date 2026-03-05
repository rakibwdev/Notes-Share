<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        // Ensure user has a linked customer record if they have placed orders before
        // We check for any customer record with this user's email or phone
        if (!$user->customer) {
            $customer = \App\Models\Customer::where('phone', $user->phone)
                ->orWhere('user_id', $user->id)
                ->first();
                
            if ($customer && !$customer->user_id) {
                $customer->update(['user_id' => $user->id]);
            }
            
            // Refresh user to load the customer relationship if it was just linked
            $user->load('customer');
        }

        $orders = collect();
        if ($user->customer) {
            $orders = Order::where('customer_id', $user->customer->id)
                ->latest()
                ->paginate(10, ['*'], 'orders_page');
        } else {
            $orders = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }

        $prescriptions = Prescription::where('user_id', $user->id)
            ->latest()
            ->paginate(5, ['*'], 'rx_page');

        return view('profile.index', compact('user', 'orders', 'prescriptions'));
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
