<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeliveryManController extends Controller
{
    public function index(): View
    {
        $delivery_men = DeliveryMan::withCount('orders')->get();

        return view('admin.delivery.index', compact('delivery_men'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'phone' => 'required|string|max:20',
            'status' => 'boolean',
        ]);

        DeliveryMan::create($validated);

        return redirect()->route('admin.delivery.index')->with('success', 'Delivery staff added.');
    }

    public function destroy(DeliveryMan $deliveryMan): RedirectResponse
    {
        $deliveryMan->delete();

        return redirect()->route('admin.delivery.index')->with('success', 'Staff removed.');
    }
}
