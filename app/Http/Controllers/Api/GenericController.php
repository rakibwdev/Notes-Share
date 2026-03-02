<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Generic;
use Illuminate\Http\Request;

class GenericController extends Controller
{
   /**
     * Display a listing of generics.
     */
    public function index()
    {
        $generics = Generic::latest()->get();

        return response()->json([
            'status' => true,
            'data' => $generics
        ], 200);
    }

    /**
     * Store a newly created generic.
     */
    public function store(Request $request)
    {
        $request->validate([
            'generic_name' => 'required|string|max:255|unique:generics,generic_name'
        ]);

        $generic = Generic::create([
            'generic_name' => $request->generic_name
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Generic created successfully',
            'data' => $generic
        ], 201);
    }

    /**
     * Display the specified generic.
     */
    public function show($id)
    {
        $generic = Generic::find($id);

        if (!$generic) {
            return response()->json([
                'status' => false,
                'message' => 'Generic not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $generic
        ], 200);
    }

    /**
     * Update the specified generic.
     */
    public function update(Request $request, $id)
    {
        $generic = Generic::find($id);

        if (!$generic) {
            return response()->json([
                'status' => false,
                'message' => 'Generic not found'
            ], 404);
        }

        $request->validate([
            'generic_name' => 'required|string|max:255|unique:generics,generic_name,' . $id
        ]);

        $generic->update([
            'generic_name' => $request->generic_name
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Generic updated successfully',
            'data' => $generic
        ], 200);
    }

    /**
     * Remove the specified generic.
     */
    public function destroy($id)
    {
        $generic = Generic::find($id);

        if (!$generic) {
            return response()->json([
                'status' => false,
                'message' => 'Generic not found'
            ], 404);
        }

        $generic->delete();

        return response()->json([
            'status' => true,
            'message' => 'Generic deleted successfully'
        ], 200);
    }
}
