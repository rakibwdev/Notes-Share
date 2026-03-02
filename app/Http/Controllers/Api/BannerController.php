<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Banner::where('is_active', true)->get());
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|string', // URL or path
            'title' => 'nullable|string|max:255',
        ]);

        $banner = Banner::create($request->all());

        return response()->json($banner, 201);
    }

    public function show(Banner $banner): JsonResponse
    {
        return response()->json($banner);
    }

    public function update(Request $request, Banner $banner): JsonResponse
    {
        $banner->update($request->all());

        return response()->json($banner);
    }

    public function destroy(Banner $banner): JsonResponse
    {
        $banner->delete();

        return response()->json(['message' => 'Banner deleted successfully']);
    }
}
