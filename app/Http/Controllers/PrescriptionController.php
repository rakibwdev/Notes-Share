<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrescriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $path = $request->file('image')->store('prescriptions', 'public');

        Prescription::create([
            'user_id' => auth()->id(),
            'image_path' => Storage::url($path),
            'phone' => $request->phone,
            'address' => $request->address,
            'note' => $request->note,
        ]);

        return back()->with('success', 'Prescription uploaded successfully! Our pharmacist will review it soon.');
    }
}
