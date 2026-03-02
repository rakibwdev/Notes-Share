<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrderItemRequest;
use App\Http\Requests\Api\UpdateOrderItemRequest;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;

class OrderItemController extends Controller
{
    public function index(): JsonResponse
    {
        $orderItems = OrderItem::with(['order', 'brand', 'unit'])->latest()->paginate(10);

        return response()->json($orderItems);
    }

    public function store(StoreOrderItemRequest $request): JsonResponse
    {
        $orderItem = OrderItem::create($request->validated());

        return response()->json($orderItem, 201);
    }

    public function show(OrderItem $orderItem): JsonResponse
    {
        return response()->json($orderItem->load(['order', 'brand', 'unit']));
    }

    public function update(UpdateOrderItemRequest $request, OrderItem $orderItem): JsonResponse
    {
        $orderItem->update($request->validated());

        return response()->json($orderItem);
    }

    public function destroy(OrderItem $orderItem): JsonResponse
    {
        $orderItem->delete();

        return response()->json(['message' => 'Order item deleted successfully']);
    }
}
