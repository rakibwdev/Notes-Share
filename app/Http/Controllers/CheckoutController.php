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

        $user = auth()->user();
        $customer = null;
        
        if ($user) {
            $customer = $user->customer;
            
            // If no linked customer record, check by user's phone number
            if (!$customer && $user->phone) {
                $customer = Customer::where('phone', $user->phone)->first();
                // Optionally link it now
                if ($customer && !$customer->user_id) {
                    $customer->update(['user_id' => $user->id]);
                }
            }
        }

        return view('checkout.index', compact('cart', 'total', 'customer'));
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

            foreach ($cart as $key => $details) {
                $product = Product::findOrFail($details['product_id']);
                $unitType = $details['unit_type'] ?? 'piece';
                $orderedQty = (int) $details['quantity'];
                
                // Convert to base unit (pieces) for stock deduction
                $totalPiecesNeeded = $product->convertToBaseUnit($orderedQty, $unitType);
                $remainingPieces = $totalPiecesNeeded;

                // Deduct from batches (pick earliest expiring first)
                $batches = $product->batches()
                    ->where('expiry_date', '>', now())
                    ->where('quantity', '>', 0)
                    ->orderBy('expiry_date')
                    ->get();

                if ($batches->sum('quantity') < $totalPiecesNeeded) {
                    throw new \Exception("Insufficient stock for {$product->name}. Requested: {$totalPiecesNeeded} pieces.");
                }

                foreach ($batches as $batch) {
                    if ($remainingPieces <= 0) break;

                    $deductQty = min($batch->quantity, $remainingPieces);
                    $batch->decrement('quantity', $deductQty);
                    
                    // Create order item for this batch portion
                    $order->items()->create([
                        'product_id' => $product->id,
                        'batch_id' => $batch->id,
                        'quantity' => $deductQty, // pieces
                        'unit_type' => $unitType,
                        'ordered_quantity' => $orderedQty, // original units
                        'price' => $details['price'],
                        'subtotal' => $details['price'] * $details['quantity'],
                    ]);

                    $remainingPieces -= $deductQty;
                }
            }

            session()->forget('cart');

            return redirect()->route('home')->with('success', 'Order placed successfully! ID: #'.$order->id);
        });
    }
}
