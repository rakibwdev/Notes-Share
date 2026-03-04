@extends('layouts.admin')

@section('title', 'View Prescription')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.prescriptions.index') }}" class="bg-white p-2 rounded-xl border border-slate-200 text-slate-400 hover:text-slate-900 transition-colors">←</a>
            <h3 class="text-2xl font-black text-slate-900 tracking-tighter uppercase italic">Prescription #{{ $prescription->id }}</h3>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Current Status:</span>
            @php
                $statusClasses = [
                    'Pending' => 'bg-amber-50 text-amber-600 ring-amber-100',
                    'Reviewed' => 'bg-indigo-50 text-indigo-600 ring-indigo-100',
                    'Processing' => 'bg-blue-50 text-blue-600 ring-blue-100',
                    'Delivered' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                ];
                $statusClass = $statusClasses[$prescription->status] ?? 'bg-slate-100 text-slate-600 ring-slate-200';
            @endphp
            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest ring-1 {{ $statusClass }}">
                {{ $prescription->status }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- RX Image -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-4 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white">
                <a href="{{ $prescription->image_path }}" target="_blank" class="block group relative overflow-hidden rounded-[2rem]">
                    <img src="{{ $prescription->image_path }}" class="w-full h-auto" alt="Prescription Full">
                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <span class="bg-white text-slate-900 px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest">Click to Zoom</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Details & Actions -->
        <div class="space-y-8">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white space-y-8">
                <div>
                    <h4 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em] mb-6 italic">Customer Details</h4>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-xl">👤</div>
                            <div>
                                <p class="text-xs font-black text-slate-900 uppercase italic">{{ $prescription->user->name ?? 'Guest User' }}</p>
                                <p class="text-[10px] font-bold text-slate-400">{{ $prescription->user->email ?? 'No email linked' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-xl">📞</div>
                            <div>
                                <p class="text-xs font-black text-slate-900 uppercase italic">{{ $prescription->phone }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Contact Number</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-xl">📍</div>
                            <div>
                                <p class="text-xs font-black text-slate-900 leading-relaxed">{{ $prescription->address }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mt-1">Delivery Address</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($prescription->note)
                <div class="pt-8 border-t border-slate-50">
                    <h4 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em] mb-4 italic">Customer Note</h4>
                    <p class="text-xs font-bold text-slate-500 bg-slate-50 p-4 rounded-2xl border border-slate-100 leading-relaxed italic">
                        "{{ $prescription->note }}"
                    </p>
                </div>
                @endif

                <div class="pt-8 border-t border-slate-50">
                    <h4 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em] mb-6 italic">Update Workflow</h4>
                    <form action="{{ route('admin.prescriptions.update-status', $prescription) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none">
                            <option value="Pending" {{ $prescription->status == 'Pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="Reviewed" {{ $prescription->status == 'Reviewed' ? 'selected' : '' }}>Mark as Reviewed</option>
                            <option value="Processing" {{ $prescription->status == 'Processing' ? 'selected' : '' }}>Move to Processing</option>
                            <option value="Delivered" {{ $prescription->status == 'Delivered' ? 'selected' : '' }}>Set as Delivered</option>
                        </select>
                        <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-100">
                            Apply Status Update
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
