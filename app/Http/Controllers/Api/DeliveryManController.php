<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeliveryManController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(DeliveryMan::all());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'phone' => 'required|string|max:20',
            'status' => 'boolean',
        ]);

        $deliveryMan = DeliveryMan::create($validated);

        return response()->json($deliveryMan, 201);
    }

    public function show(DeliveryMan $deliveryMan): JsonResponse
    {
        return response()->json($deliveryMan->load('orders'));
    }

    public function update(Request $request, DeliveryMan $deliveryMan): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:200',
            'phone' => 'sometimes|required|string|max:20',
            'status' => 'boolean',
        ]);

        $deliveryMan->update($validated);

        return response()->json($deliveryMan);
    }

    public function destroy(DeliveryMan $deliveryMan): JsonResponse
    {
        $deliveryMan->delete();

        return response()->json(['message' => 'Delivery man deleted successfully']);
    }
}
