<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreGenericRequest;
use App\Http\Requests\Api\UpdateGenericRequest;
use App\Models\Api\Generic;
use Illuminate\Http\JsonResponse;

class GenericController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Generic::latest()->paginate(10));
    }

    public function store(StoreGenericRequest $request): JsonResponse
    {
        $generic = Generic::create($request->validated());

        return response()->json($generic, 201);
    }

    public function show(Generic $generic): JsonResponse
    {
        return response()->json($generic);
    }

    public function update(UpdateGenericRequest $request, Generic $generic): JsonResponse
    {
        $generic->update($request->validated());

        return response()->json($generic);
    }

    public function destroy(Generic $generic): JsonResponse
    {
        $generic->delete();

        return response()->json(['message' => 'Generic deleted successfully']);
    }
}
