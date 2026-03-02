<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['category', 'primaryImage']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('generic_name', 'like', "%$search%");
            });
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('manufacturer')) {
            $query->where('manufacturer', 'like', "%{$request->manufacturer}%");
        }

        $products = $query->latest()->paginate(10);

        // Add calculated stock to each product
        $products->getCollection()->transform(function ($product) {
            $product->total_stock = $product->total_stock;

            return $product;
        });

        return response()->json($products);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'generic_name' => 'nullable|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'manufacturer' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json($product->load(['category', 'images', 'batches']));
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:200',
            'generic_name' => 'nullable|string|max:200',
            'category_id' => 'sometimes|required|exists:categories,id',
            'manufacturer' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $product->update($validated);

        return response()->json($product);
    }

    public function destroy(Product $product): JsonResponse
    {
        if ($product->batches()->exists()) {
            return response()->json(['message' => 'Cannot delete product with existing batches'], 422);
        }
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
