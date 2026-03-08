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
        
        // 1. Link existing customer record if found by phone or email
        if (!$user->customer) {
            $customer = \App\Models\Customer::where('user_id', $user->id)
                ->orWhere(function($q) use ($user) {
                    if ($user->phone) $q->where('phone', $user->phone);
                })
                ->first();
                
            if ($customer) {
                if (!$customer->user_id) {
                    $customer->update(['user_id' => $user->id]);
                }
                $user->load('customer');
            }
        }

        // 2. Fetch orders via customer link
        $orders = collect();
        if ($user->customer) {
            $orders = Order::where('customer_id', $user->customer->id)
                ->latest()
                ->paginate(10, ['*'], 'orders_page');
        } else {
            // Fallback: If no customer record yet, maybe search orders directly by phone?
            // (Only if phone exists)
            if ($user->phone) {
                $customerIds = \App\Models\Customer::where('phone', $user->phone)->pluck('id');
                if ($customerIds->isNotEmpty()) {
                    $orders = Order::whereIn('customer_id', $customerIds)
                        ->latest()
                        ->paginate(10, ['*'], 'orders_page');
                } else {
                    $orders = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
                }
            } else {
                $orders = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            }
        }

        // Debug: Log info if orders are empty but we have a customer
        if ($orders->isEmpty() && $user->customer) {
            \Illuminate\Support\Facades\Log::info("User {$user->id} has customer {$user->customer->id} but 0 orders found.");
        }

        $prescriptions = Prescription::where('user_id', $user->id)
            ->latest()
            ->paginate(5, ['*'], 'rx_page');

        return view('profile.index', compact('user', 'orders', 'prescriptions'));
    }

    public function edit(): View
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($validated);

        // Also update linked customer record if exists
        if ($user->customer) {
            $user->customer->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ]);
        }

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }

    public function showOrder(Order $order): View
    {
        // Ensure the user owns the order through their customer record
        if (!auth()->user()->customer || $order->customer_id !== auth()->user()->customer->id) {
            abort(403);
        }

        $order->load(['items.product', 'deliveryMan']);

        return view('profile.order-details', compact('order'));
    }
}
