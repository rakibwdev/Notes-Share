@extends('layouts.admin')

@section('title', 'Add New Medicine')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Product Information</h3>
            <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Back to List</a>
        </div>
        
        <form action="{{ route('admin.products.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-gray-700">Medicine Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Napa Extend">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Generic Name -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-gray-700">Generic Name</label>
                    <input type="text" name="generic_name" value="{{ old('generic_name') }}" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Paracetamol">
                </div>

                <!-- Category -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-gray-700">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Manufacturer -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-gray-700">Manufacturer</label>
                    <input type="text" name="manufacturer" value="{{ old('manufacturer') }}" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Beximco Pharma">
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-1">
                <label class="block text-sm font-bold text-gray-700">Description</label>
                <textarea name="description" rows="4" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Enter medicine details, usage, or precautions...">{{ old('description') }}</textarea>
            </div>

            <!-- Status -->
            <div class="flex items-center gap-2">
                <input type="checkbox" name="status" value="1" id="status" {{ old('status', '1') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="status" class="text-sm font-medium text-gray-700 font-bold cursor-pointer">Active and available for sale</label>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">
                    Save Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
