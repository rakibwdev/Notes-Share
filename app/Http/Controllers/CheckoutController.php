<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View
    {
        $cart = session()->get('cart', []);
        if (count($cart) == 0) {
            return view('cart.index', ['cart' => [], 'total' => 0]);
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout.index', compact('cart', 'total'));
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        if (count($cart) == 0) {
            return redirect()->route('products.index');
        }

        return DB::transaction(function () use ($validated, $cart) {
            // Find or create customer
            $customerData = ['name' => $validated['name'], 'address' => $validated['address']];
            
            if (auth()->check()) {
                $customerData['user_id'] = auth()->id();
            }

            $customer = Customer::updateOrCreate(
                ['phone' => $validated['phone']],
                $customerData
            );

            // If user is logged in but the customer record wasn't linked yet, link it
            if (auth()->check() && !$customer->user_id) {
                $customer->update(['user_id' => auth()->id()]);
            }

            $totalPrice = 0;
            foreach ($cart as $item) {
                $totalPrice += $item['price'] * $item['quantity'];
            }

            $order = Order::create([
                'customer_id' => $customer->id,
                'total_price' => $totalPrice,
                'payment_method' => $validated['payment_method'],
                'status' => 'Pending',
            ]);

            foreach ($cart as $id => $details) {
                $product = Product::findOrFail($id);
                // Simple batch selection: pick the earliest expiring batch with stock
                $batch = $product->batches()
                    ->where('expiry_date', '>', now())
                    ->where('quantity', '>', 0)
                    ->orderBy('expiry_date')
                    ->first();

                if (! $batch) {
                    throw new \Exception('Out of stock for '.$product->name);
                }

                $order->items()->create([
                    'product_id' => $id,
                    'batch_id' => $batch->id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'subtotal' => $details['price'] * $details['quantity'],
                ]);
            }

            session()->forget('cart');

            return redirect()->route('home')->with('success', 'Order placed successfully! ID: #'.$order->id);
        });
    }
}
