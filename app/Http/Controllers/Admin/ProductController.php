<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Generic;
use App\Models\Manufacturer;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'batches', 'primaryImage', 'generic', 'manufacturerRelationship']);

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('generic_name', 'like', "%{$request->search}%");
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::all();

        return view('admin.inventory.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::all();
        $generic_names = Generic::pluck('name');
        $manufacturers = Manufacturer::pluck('name');

        return view('admin.inventory.products.create', compact('categories', 'generic_names', 'manufacturers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'generic_name' => 'nullable|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'manufacturer' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'pieces_per_strip' => 'required|integer|min:1',
            'pieces_per_box' => 'required|integer|min:1',
            'price_per_piece' => 'required|numeric|min:0',
            'status' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Dynamic Generic
        if (!empty($validated['generic_name'])) {
            $generic = Generic::firstOrCreate(['name' => $validated['generic_name']]);
            $validated['generic_id'] = $generic->id;
        }

        // Dynamic Manufacturer
        if (!empty($validated['manufacturer'])) {
            $manufacturer = Manufacturer::firstOrCreate(['name' => $validated['manufacturer']]);
            $validated['manufacturer_id'] = $manufacturer->id;
        }

        $product = Product::create($validated);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => Storage::url($path),
                'is_primary' => true,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $product->load(['primaryImage', 'generic', 'manufacturerRelationship']);
        $categories = Category::all();
        $generic_names = Generic::pluck('name');
        $manufacturers = Manufacturer::pluck('name');

        return view('admin.inventory.products.edit', compact('product', 'categories', 'generic_names', 'manufacturers'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'generic_name' => 'nullable|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'manufacturer' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'pieces_per_strip' => 'required|integer|min:1',
            'pieces_per_box' => 'required|integer|min:1',
            'price_per_piece' => 'required|numeric|min:0',
            'status' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Dynamic Generic
        if (!empty($validated['generic_name'])) {
            $generic = Generic::firstOrCreate(['name' => $validated['generic_name']]);
            $validated['generic_id'] = $generic->id;
        }

        // Dynamic Manufacturer
        if (!empty($validated['manufacturer'])) {
            $manufacturer = Manufacturer::firstOrCreate(['name' => $validated['manufacturer']]);
            $validated['manufacturer_id'] = $manufacturer->id;
        }

        $product->update($validated);

        if ($request->hasFile('image')) {
            // Delete old primary image if exists
            $oldImage = $product->primaryImage;
            if ($oldImage) {
                $oldPath = str_replace('/storage/', '', $oldImage->image_url);
                Storage::disk('public')->delete($oldPath);
                $oldImage->delete();
            }

            $path = $request->file('image')->store('products', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => Storage::url($path),
                'is_primary' => true,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->batches()->exists()) {
            return back()->with('error', 'Cannot delete product with existing batches.');
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
