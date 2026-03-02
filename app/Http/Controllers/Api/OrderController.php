<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['customer', 'deliveryMan', 'items.product', 'items.batch']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->has('delivery_man_id')) {
            $query->where('delivery_man_id', $request->delivery_man_id);
        }

        $orders = $query->latest()->paginate(10);

        return response()->json($orders);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'delivery_man_id' => 'nullable|exists:delivery_men,id',
            'payment_method' => 'required|string',
            'total_discount' => 'numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.batch_id' => 'required|exists:batches,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'numeric|min:0',
        ]);

        return DB::transaction(function () use ($validated) {
            $totalPrice = 0;
            foreach ($validated['items'] as $item) {
                $subtotal = ($item['price'] - ($item['discount'] ?? 0)) * $item['quantity'];
                $totalPrice += $subtotal;
            }

            $order = Order::create([
                'customer_id' => $validated['customer_id'],
                'delivery_man_id' => $validated['delivery_man_id'] ?? null,
                'total_price' => $totalPrice - ($validated['total_discount'] ?? 0),
                'total_discount' => $validated['total_discount'] ?? 0,
                'payment_method' => $validated['payment_method'],
                'status' => 'Pending',
            ]);

            foreach ($validated['items'] as $item) {
                $subtotal = ($item['price'] - ($item['discount'] ?? 0)) * $item['quantity'];
                $order->items()->create(array_merge($item, ['subtotal' => $subtotal]));
            }

            return response()->json($order->load('items'), 201);
        });
    }

    public function show(Order $order): JsonResponse
    {
        return response()->json($order->load(['customer', 'deliveryMan', 'items.product', 'items.batch']));
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'sometimes|required|in:Pending,Confirmed,Delivered,Cancelled',
            'delivery_man_id' => 'nullable|exists:delivery_men,id',
        ]);

        return DB::transaction(function () use ($validated, $order) {
            $oldStatus = $order->status;
            $order->update($validated);

            // Stock deduction logic
            if ($order->status === 'Delivered' && $oldStatus !== 'Delivered') {
                foreach ($order->items as $item) {
                    $batch = $item->batch;
                    if ($batch->quantity < $item->quantity) {
                        throw new \Exception("Insufficient stock in batch {$batch->batch_number} for product {$item->product->name}");
                    }
                    $batch->decrement('quantity', $item->quantity);
                }
            }

            return response()->json($order);
        });
    }

    public function destroy(Order $order): JsonResponse
    {
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
