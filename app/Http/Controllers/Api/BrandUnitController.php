<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreBrandUnitRequest;
use App\Http\Requests\Api\UpdateBrandUnitRequest;
use App\Models\BrandUnit;
use Illuminate\Http\JsonResponse;

class BrandUnitController extends Controller
{
    public function index(): JsonResponse
    {
        $brandUnits = BrandUnit::with(['brand', 'unit', 'user'])->latest()->paginate(10);

        return response()->json($brandUnits);
    }

    public function store(StoreBrandUnitRequest $request): JsonResponse
    {
        $brandUnit = BrandUnit::create($request->validated());

        return response()->json($brandUnit, 201);
    }

    public function show(BrandUnit $brandunit): JsonResponse
    {
        return response()->json($brandunit->load(['brand', 'unit', 'user']));
    }

    public function update(UpdateBrandUnitRequest $request, BrandUnit $brandunit): JsonResponse
    {
        $brandunit->update($request->validated());

        return response()->json($brandunit);
    }

    public function destroy(BrandUnit $brandunit): JsonResponse
    {
        $brandunit->delete();

        return response()->json(['message' => 'BrandUnit deleted successfully']);
    }
}
