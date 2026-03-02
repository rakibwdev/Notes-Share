<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BatchController extends Controller
{
    public function index(Request $request): View
    {
        $query = Batch::with('product');

        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filter === 'expired') {
            $query->where('expiry_date', '<', now());
        } elseif ($request->filter === 'expiring_soon') {
            $query->whereBetween('expiry_date', [now(), now()->addDays(60)]);
        }

        $batches = $query->latest()->paginate(15);
        $products = Product::all();

        return view('admin.inventory.batches.index', compact('batches', 'products'));
    }

    public function create(): View
    {
        $products = Product::all();

        return view('admin.inventory.batches.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'batch_number' => 'required|string|max:100',
            'expiry_date' => 'required|date',
            'quantity' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        Batch::updateOrCreate(
            ['product_id' => $validated['product_id'], 'batch_number' => $validated['batch_number']],
            $validated
        );

        return redirect()->route('admin.batches.index')->with('success', 'Batch added/updated successfully.');
    }

    public function destroy(Batch $batch): RedirectResponse
    {
        $batch->delete();

        return redirect()->route('admin.batches.index')->with('success', 'Batch deleted successfully.');
    }
}
