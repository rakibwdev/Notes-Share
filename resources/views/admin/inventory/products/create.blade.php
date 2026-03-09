@extends('layouts.admin')

@section('title', 'Add New Medicine')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 tracking-tight uppercase italic">Product Information</h3>
            <a href="{{ route('admin.products.index') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition-colors">Back to List</a>
        </div>
        
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Name -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Medicine Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="e.g. Napa Extend">
                    @error('name') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter italic">{{ $message }}</p> @enderror
                </div>

                <!-- Generic Name -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Generic Name</label>
                    <input type="text" name="generic_name" list="generic_names_list" value="{{ old('generic_name') }}" class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="e.g. Paracetamol">
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
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter italic">{{ $message }}</p> @enderror
                </div>

                <!-- Manufacturer -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Manufacturer</label>
                    <input type="text" name="manufacturer" list="manufacturers_list" value="{{ old('manufacturer') }}" class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="e.g. Beximco Pharma">
                    <datalist id="manufacturers_list">
                        @foreach($manufacturers as $mf)
                            <option value="{{ $mf }}">
                        @endforeach
                    </datalist>
                    @error('manufacturer') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter italic">{{ $message }}</p> @enderror
                </div>

                <!-- Product Image -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Product Image</label>
                    <input type="file" name="image" class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all">
                    @error('image') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter italic">{{ $message }}</p> @enderror
                </div>

                <!-- Unit Conversions -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Pieces Per Strip <span class="text-red-500">*</span></label>
                    <input type="number" name="pieces_per_strip" value="{{ old('pieces_per_strip', 10) }}" required min="1" class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                    @error('pieces_per_strip') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter italic">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Pieces Per Box <span class="text-red-500">*</span></label>
                    <input type="number" name="pieces_per_box" value="{{ old('pieces_per_box', 100) }}" required min="1" class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                    @error('pieces_per_box') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter italic">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Default Selling Price (Per Piece) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">৳</span>
                        <input type="number" step="0.01" name="price_per_piece" value="{{ old('price_per_piece', 0) }}" required min="0" class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 pl-8 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                    </div>
                    @error('price_per_piece') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter italic">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Low Stock Alert Configuration -->
            <div class="bg-indigo-50/50 p-8 rounded-[2.5rem] border border-indigo-100/50 space-y-6" x-data="{ alertType: 'global' }">
                <div class="flex items-center gap-4 border-b border-indigo-100 pb-4">
                    <span class="text-2xl">🔔</span>
                    <div>
                        <h4 class="text-xs font-black text-indigo-900 uppercase tracking-widest italic">Stock Alert Configuration</h4>
                        <p class="text-[8px] text-indigo-400 font-bold uppercase tracking-tighter">Choose how you want to be notified about low inventory</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Threshold Strategy</label>
                        <div class="flex flex-col gap-3">
                            <label class="flex items-center gap-3 p-4 bg-white rounded-2xl border border-indigo-100 cursor-pointer hover:bg-indigo-50 transition-all group">
                                <input type="radio" name="threshold_type" value="global" x-model="alertType" checked class="w-5 h-5 text-indigo-600 focus:ring-indigo-500/20">
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-indigo-900 uppercase italic">Use Global System Default</span>
                                    <span class="text-[8px] text-slate-400 font-bold uppercase tracking-tighter">Managed via Dashboard Settings</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-4 bg-white rounded-2xl border border-indigo-100 cursor-pointer hover:bg-indigo-50 transition-all group">
                                <input type="radio" name="threshold_type" value="individual" x-model="alertType" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500/20">
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-indigo-900 uppercase italic">Set Individual Threshold</span>
                                    <span class="text-[8px] text-slate-400 font-bold uppercase tracking-tighter">Custom value for this specific medicine</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4" x-show="alertType === 'individual'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Individual Alert Qty (Pieces)</label>
                        <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold') }}" min="1" class="w-full bg-white border-indigo-200 rounded-2xl p-5 text-sm font-black text-indigo-950 focus:ring-4 focus:ring-indigo-500/10 outline-none shadow-inner" placeholder="e.g. 50">
                        <p class="text-[8px] text-indigo-400 font-bold italic">The system will alert you when stock falls below this quantity.</p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Description</label>
                <textarea name="description" rows="4" class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none resize-none" placeholder="Enter medicine details, usage, or precautions...">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter italic">{{ $message }}</p> @enderror
            </div>

            <!-- Status -->
            <div class="flex items-center gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <input type="checkbox" name="status" value="1" id="status" {{ old('status', '1') == '1' ? 'checked' : '' }} class="w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/20 transition-all">
                <label for="status" class="text-xs font-black text-slate-900 uppercase tracking-widest cursor-pointer select-none">Active and available for sale</label>
            </div>

            <div class="pt-8 border-t border-slate-100 flex justify-end gap-4">
                <a href="{{ route('admin.products.index') }}" class="px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                <button type="submit" class="bg-indigo-600 text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100">
                    Save Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
