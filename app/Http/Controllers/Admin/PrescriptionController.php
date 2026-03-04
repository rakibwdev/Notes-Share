<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrescriptionController extends Controller
{
    public function index(): View
    {
        $prescriptions = Prescription::with('user')->latest()->paginate(20);
        return view('admin.prescriptions.index', compact('prescriptions'));
    }

    public function show(Prescription $prescription): View
    {
        return view('admin.prescriptions.show', compact('prescription'));
    }

    public function updateStatus(Request $request, Prescription $prescription)
    {
        $request->validate(['status' => 'required|string']);
        $prescription->update(['status' => $request->status]);
        return back()->with('success', 'Prescription status updated.');
    }
}
