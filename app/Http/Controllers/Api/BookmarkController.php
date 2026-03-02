<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreBookmarkRequest;
use App\Http\Requests\Api\UpdateBookmarkRequest;
use App\Models\Bookmark;
use Illuminate\Http\JsonResponse;

class BookmarkController extends Controller
{
    public function index(): JsonResponse
    {
        $bookmarks = Bookmark::with(['user', 'brand', 'unit'])->latest()->paginate(10);

        return response()->json($bookmarks);
    }

    public function store(StoreBookmarkRequest $request): JsonResponse
    {
        $bookmark = Bookmark::create($request->validated());

        return response()->json($bookmark, 201);
    }

    public function show(Bookmark $bookmark): JsonResponse
    {
        return response()->json($bookmark->load(['user', 'brand', 'unit']));
    }

    public function update(UpdateBookmarkRequest $request, Bookmark $bookmark): JsonResponse
    {
        $bookmark->update($request->validated());

        return response()->json($bookmark);
    }

    public function destroy(Bookmark $bookmark): JsonResponse
    {
        $bookmark->delete();

        return response()->json(['message' => 'Bookmark deleted successfully']);
    }
}
