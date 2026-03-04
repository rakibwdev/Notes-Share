@extends('layouts.admin')

@section('title', 'Prescription Review')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-2xl font-black text-slate-900 tracking-tight uppercase italic">Prescriptions</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest italic">Review and process customer uploads</p>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50/50 text-slate-400 text-[10px] uppercase font-black tracking-[0.2em] italic">
                <tr>
                    <th class="px-8 py-6">Customer</th>
                    <th class="px-8 py-6">Contact & Address</th>
                    <th class="px-8 py-6">Status</th>
                    <th class="px-8 py-6">Date</th>
                    <th class="px-8 py-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-sm font-bold text-slate-900">
                @forelse($prescriptions as $rx)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-xl overflow-hidden">
                                <img src="{{ $rx->image_path }}" class="w-full h-full object-cover" alt="RX">
                            </div>
                            <div class="flex flex-col">
                                <span class="tracking-tight">{{ $rx->user->name ?? 'Guest' }}</span>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">User ID: {{ $rx->user_id ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex flex-col">
                            <span class="text-xs">{{ $rx->phone }}</span>
                            <span class="text-[10px] font-bold text-slate-400 truncate max-w-[200px]">{{ $rx->address }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        @php
                            $statusClasses = [
                                'Pending' => 'bg-amber-50 text-amber-600 ring-amber-100',
                                'Reviewed' => 'bg-indigo-50 text-indigo-600 ring-indigo-100',
                                'Processing' => 'bg-blue-50 text-blue-600 ring-blue-100',
                                'Delivered' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                            ];
                            $statusClass = $statusClasses[$rx->status] ?? 'bg-slate-100 text-slate-600 ring-slate-200';
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 {{ $statusClass }}">
                            {{ $rx->status }}
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-xs italic opacity-60">{{ $rx->created_at->format('M d, Y') }}</span>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <a href="{{ route('admin.prescriptions.show', $rx) }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-lg">View Details</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center">
                        <span class="text-4xl block mb-4">📄</span>
                        <span class="text-sm font-black text-slate-400 uppercase tracking-widest italic">No prescriptions found</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($prescriptions->hasPages())
        <div class="p-8 bg-slate-50/50 border-t border-slate-50">
            {{ $prescriptions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
