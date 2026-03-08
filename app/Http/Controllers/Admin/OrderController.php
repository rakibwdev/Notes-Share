<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['customer', 'deliveryMan']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['customer', 'deliveryMan', 'items.product', 'items.batch']);
        $delivery_men = DeliveryMan::where('status', true)->get();

        return view('admin.orders.show', compact('order', 'delivery_men'));
    }

    public function printInvoice(Order $order): View
    {
        $order->load(['customer', 'items.product', 'items.batch']);
        return view('admin.orders.invoice', compact('order'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Confirmed,Processing,Shipped,Delivered,Cancelled',
            'delivery_man_id' => 'nullable|exists:delivery_men,id',
        ]);

        $order->update($validated);

        // Batch reduction logic is handled in Model or Observer ideally,
        // but for this MVP, we can trigger the logic from our existing API logic
        // if we move it to a Service. For now, we'll just update status.

        return back()->with('success', 'Order updated successfully.');
    }
}
