@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-10">
    <div>
        <h3 class="text-3xl font-black text-slate-900 tracking-tighter uppercase italic">Configuration</h3>
        <p class="text-slate-500 mt-1 uppercase text-[10px] font-bold tracking-widest italic">Manage your pharmacy's global rules and identity</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 text-emerald-700 font-bold rounded-r-xl shadow-sm animate-fade-in">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- General Settings -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex items-center gap-4 bg-slate-50/30">
                <span class="text-2xl">🏢</span>
                <div>
                    <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest italic">General Identity</h4>
                    <p class="text-[8px] text-slate-400 font-bold uppercase tracking-tighter italic">Basic information about your platform</p>
                </div>
            </div>
            <div class="p-10 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Company Name</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $settings['company_name']) }}" required class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Currency Symbol</label>
                        <input type="text" name="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol']) }}" required class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 outline-none">
                    </div>
                </div>

                <!-- Logo Section -->
                <div class="pt-8 border-t border-slate-50 flex flex-col md:flex-row items-center gap-8">
                    <div class="shrink-0">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-4">Current Platform Logo</label>
                        <div class="w-32 h-32 rounded-3xl bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden">
                            @if($settings['logo_url'])
                                <img src="{{ $settings['logo_url'] }}" class="w-full h-full object-contain p-2" alt="Logo">
                            @else
                                <span class="text-xs font-black text-slate-300 uppercase italic">No Logo</span>
                            @endif
                        </div>
                    </div>
                    <div class="grow space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Upload New Logo</label>
                        <input type="file" name="logo" class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all">
                        <p class="text-[8px] text-slate-400 italic font-medium ml-1">Recommended size: 200x50px. Supports PNG, JPG, SVG.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Alerts -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex items-center gap-4 bg-slate-50/30">
                <span class="text-2xl">🔔</span>
                <div>
                    <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest italic">Inventory & Alerts</h4>
                    <p class="text-[8px] text-slate-400 font-bold uppercase tracking-tighter italic">Configure when the system warns you about stock and expiry</p>
                </div>
            </div>
            <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Expiry Warning Period (Days)</label>
                    <input type="number" name="expiry_warning_days" value="{{ old('expiry_warning_days', $settings['expiry_warning_days']) }}" min="1" max="365" required class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 outline-none">
                    <p class="text-[8px] text-slate-400 italic font-medium ml-1 mt-1">Alerts will trigger for batches expiring within these days.</p>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Global Low Stock Threshold</label>
                    <input type="number" name="global_low_stock_threshold" value="{{ old('global_low_stock_threshold', $settings['global_low_stock_threshold']) }}" min="1" required class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 outline-none">
                    <p class="text-[8px] text-slate-400 italic font-medium ml-1 mt-1">Default quantity level to trigger low stock warnings.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-indigo-600 text-white px-12 py-5 rounded-[2rem] font-black text-xs uppercase tracking-[0.3em] shadow-2xl shadow-indigo-100 hover:bg-indigo-700 transition-all transform hover:-translate-y-1">
                Save
            </button>
        </div>
    </form>
</div>
@endsection
