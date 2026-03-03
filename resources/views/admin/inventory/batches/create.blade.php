@extends('layouts.admin')

@section('title', 'Add Stock Batch')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Batch & Price Details</h3>
            <a href="{{ route('admin.batches.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Back to Inventory</a>
        </div>
        
        <form action="{{ route('admin.batches.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-gray-700">Select Product <span class="text-red-500">*</span></label>
                    <select name="product_id" required class="w-full border-gray-300 rounded-lg p-1  focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Search Medicine...</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Batch Number -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-gray-700">Batch Number <span class="text-red-500">*</span></label>
                    <input type="text" name="batch_number" value="{{ old('batch_number') }}" required class="w-full border-gray-300 rounded-lg p-1  focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. B-2024-001">
                </div>

                <!-- Quantity -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-gray-700">Quantity (Units) <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" value="{{ old('quantity') }}" required min="0" class="w-full border-gray-300 rounded-lg p-1  focus:ring-blue-500 focus:border-blue-500" placeholder="0">
                </div>

                <!-- Expiry Date -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-gray-700">Expiry Date <span class="text-red-500">*</span></label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" required class="w-full border-gray-300 rounded-lg p-1  focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Purchase Price -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-gray-700">Purchase Price (Per Unit) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">৳</span>
                        <input type="number" name="purchase_price" value="{{ old('purchase_price') }}" step="0.01" required min="0" class="w-full pl-8 border-gray-300 rounded-lg p-1  focus:ring-blue-500 focus:border-blue-500" placeholder="0.00">
                    </div>
                </div>

                <!-- Selling Price -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-gray-700">Selling Price (Per Unit) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">৳</span>
                        <input type="number" name="selling_price" value="{{ old('selling_price') }}" step="0.01" required min="0" class="w-full pl-8 border-gray-300 rounded-lg p-1  focus:ring-blue-500 focus:border-blue-500" placeholder="0.00">
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg p-1  font-bold hover:bg-green-700 transition-colors shadow-lg shadow-green-200">
                    Add to Inventory
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
