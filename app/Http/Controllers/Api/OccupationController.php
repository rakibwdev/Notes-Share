<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOccupationRequest;
use App\Http\Requests\Api\UpdateOccupationRequest;
use App\Models\Occupation;
use Illuminate\Http\JsonResponse;

class OccupationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Occupation::latest()->paginate(10));
    }

    public function store(StoreOccupationRequest $request): JsonResponse
    {
        $occupation = Occupation::create($request->validated());

        return response()->json($occupation, 201);
    }

    public function show(Occupation $occupation): JsonResponse
    {
        return response()->json($occupation);
    }

    public function update(UpdateOccupationRequest $request, Occupation $occupation): JsonResponse
    {
        $occupation->update($request->validated());

        return response()->json($occupation);
    }

    public function destroy(Occupation $occupation): JsonResponse
    {
        $occupation->delete();

        return response()->json(['message' => 'Occupation deleted successfully']);
    }
}
