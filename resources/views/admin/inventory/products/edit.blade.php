@extends('layouts.admin')

@section('title', 'Edit Medicine')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 tracking-tight uppercase italic">Product Information</h3>
            <a href="{{ route('admin.products.index') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition-colors">Back to List</a>
        </div>
        
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Name -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Medicine Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="e.g. Napa Extend">
                    @error('name') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter italic">{{ $message }}</p> @enderror
                </div>

                <!-- Generic Name -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Generic Name</label>
                    <input type="text" name="generic_name" list="generic_names_list" value="{{ old('generic_name', $product->generic_name) }}" class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="e.g. Paracetamol">
                    <datalist id="generic_names_list">
                        @foreach($generic_names as $gn)
                            <option value="{{ $gn }}">
                        @endforeach
                    </datalist>
                    @error('generic_name') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter italic">{{ $message }}</p> @enderror
                </div>

                <!-- Category -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none appearance-none">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter italic">{{ $message }}</p> @enderror
                </div>

                <!-- Manufacturer -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Manufacturer</label>
                    <input type="text" name="manufacturer" list="manufacturers_list" value="{{ old('manufacturer', $product->manufacturer) }}" class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="e.g. Beximco Pharma">
                    <datalist id="manufacturers_list">
                        @foreach($manufacturers as $mf)
                            <option value="{{ $mf }}">
                        @endforeach
                    </datalist>
                    @error('manufacturer') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter italic">{{ $message }}</p> @enderror
                </div>

                <!-- Product Image -->
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Update Image</label>
                    @if($product->primaryImage)
                        <div class="relative w-24 h-24 group">
                            <img src="{{ $product->primaryImage->image_url }}" class="w-full h-full object-contain rounded-xl border border-slate-100 bg-slate-50" alt="Current">
                            <div class="absolute inset-0 bg-slate-900/40 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-[8px] font-black text-white uppercase tracking-tighter">Current</span>
                            </div>
                        </div>
                    @endif
                    <input type="file" name="image" class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all">
                    @error('image') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter italic">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Description</label>
                <textarea name="description" rows="4" class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none resize-none" placeholder="Enter medicine details, usage, or precautions...">{{ old('description', $product->description) }}</textarea>
                @error('description') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter italic">{{ $message }}</p> @enderror
            </div>

            <!-- Status -->
            <div class="flex items-center gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <input type="checkbox" name="status" value="1" id="status" {{ old('status', $product->status) ? 'checked' : '' }} class="w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/20 transition-all">
                <label for="status" class="text-xs font-black text-slate-900 uppercase tracking-widest cursor-pointer select-none">Active and available for sale</label>
            </div>

            <div class="pt-8 border-t border-slate-100 flex justify-end gap-4">
                <a href="{{ route('admin.products.index') }}" class="px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                <button type="submit" class="bg-indigo-600 text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
