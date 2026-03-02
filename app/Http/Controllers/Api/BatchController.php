<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Batch::with('product');

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('filter')) {
            if ($request->filter === 'expired') {
                $query->where('expiry_date', '<', now());
            } elseif ($request->filter === 'expiring_soon') {
                $query->whereBetween('expiry_date', [now(), now()->addDays(60)]);
            }
        }

        return response()->json($query->latest()->paginate(10));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'batch_number' => 'required|string|max:100',
            'expiry_date' => 'required|date',
            'quantity' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        // Check for existing batch for the same product to either update or create unique
        $batch = Batch::updateOrCreate(
            ['product_id' => $validated['product_id'], 'batch_number' => $validated['batch_number']],
            $validated
        );

        return response()->json($batch, 201);
    }

    public function show(Batch $batch): JsonResponse
    {
        return response()->json($batch->load('product'));
    }

    public function update(Request $request, Batch $batch): JsonResponse
    {
        $validated = $request->validate([
            'expiry_date' => 'sometimes|required|date',
            'quantity' => 'sometimes|required|integer|min:0',
            'purchase_price' => 'sometimes|required|numeric|min:0',
            'selling_price' => 'sometimes|required|numeric|min:0',
        ]);

        $batch->update($validated);

        return response()->json($batch);
    }

    public function destroy(Batch $batch): JsonResponse
    {
        $batch->delete();

        return response()->json(['message' => 'Batch deleted successfully']);
    }
}
