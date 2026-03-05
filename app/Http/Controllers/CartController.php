<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        $quantity = (int) $request->input('quantity', 1);
        $unitType = $request->input('unit_type', 'piece');
        
        // Stock Validation
        $requestedPieces = $product->convertToBaseUnit($quantity, $unitType);
        $availablePieces = $product->total_stock;

        // Calculate already in cart for this product
        $inCartPieces = 0;
        foreach ($cart as $key => $item) {
            if ($item['product_id'] == $product->id) {
                $inCartPieces += $product->convertToBaseUnit($item['quantity'], $item['unit_type']);
            }
        }

        if (($inCartPieces + $requestedPieces) > $availablePieces) {
            $msg = "Insufficient stock. Only {$availablePieces} pieces available.";
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        $price = $product->getUnitPrice($unitType);
        $cartKey = $product->id . '_' . $unitType;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => $quantity,
                'unit_type' => $unitType,
                'price' => $price,
                'image' => $product->primaryImage->image_url ?? null,
                'generic' => $product->generic_name,
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$product->name} ({$unitType}) added to cart!",
                'cart_count' => count($cart),
                'cart_total' => array_reduce($cart, function($carry, $item) {
                    return $carry + ($item['price'] * $item['quantity']);
                }, 0)
            ]);
        }

        return redirect()->back()->with('success', "{$product->name} ({$unitType}) added to cart!");
    }

    public function remove(Request $request): RedirectResponse
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
        }

        return redirect()->back()->with('success', 'Item removed!');
    }
}
