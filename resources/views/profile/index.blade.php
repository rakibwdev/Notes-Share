@extends('layouts.app')

@section('content')
<div class="bg-slate-50/50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Profile Header -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white p-8 mb-12 flex flex-col md:flex-row items-center gap-8">
            <div class="w-24 h-24 rounded-[2rem] bg-indigo-600 flex items-center justify-center text-4xl text-white shadow-2xl shadow-indigo-100 ring-8 ring-indigo-50">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div class="flex-grow text-center md:text-left">
                <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase italic">{{ $user->name }}</h1>
                <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-[10px] mt-1 italic">{{ $user->email }}</p>
                <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-4">
                    <span class="px-4 py-1.5 rounded-full bg-slate-50 border border-slate-100 text-[10px] font-black uppercase tracking-widest text-slate-400 italic">Member since {{ $user->created_at->format('M Y') }}</span>
                    @if($user->is_admin)
                        <span class="px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-[10px] font-black uppercase tracking-widest text-indigo-600 italic">Administrator</span>
                    @endif
                </div>
            </div>
            <div class="flex gap-4">
                <a href="#" class="px-6 py-3 rounded-2xl bg-white border border-slate-200 text-xs font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-all">Edit Profile</a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Sidebar / Stats -->
            <div class="space-y-8">
                <div class="bg-white p-8 rounded-[2rem] shadow-lg shadow-slate-200/40 border border-white">
                    <h3 class="text-xs font-black text-indigo-600 uppercase tracking-[0.3em] mb-6 italic">Account Overview</h3>
                    <div class="space-y-6">
                        <div class="flex justify-between items-center p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Total Orders</span>
                            <span class="text-xl font-black text-slate-900">{{ $orders->total() }}</span>
                        </div>
                        <div class="flex justify-between items-center p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Pending</span>
                            <span class="text-xl font-black text-indigo-600">{{ $orders->where('status', 'pending')->count() }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-600 p-8 rounded-[2rem] shadow-2xl shadow-indigo-200 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.3em] mb-4 opacity-60">Customer Support</h3>
                        <p class="text-sm font-bold leading-relaxed mb-6">Need help with an order? Our 24/7 team is here for you.</p>
                        <a href="#" class="inline-block bg-white text-indigo-600 px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-50 transition-all">Contact Us</a>
                    </div>
                    <div class="absolute -bottom-4 -right-4 text-8xl opacity-10">💊</div>
                </div>
            </div>

            <!-- Orders Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden">
                    <div class="p-8 border-b border-slate-50">
                        <h3 class="text-xl font-black text-slate-900 tracking-tighter uppercase italic">Order History & Tracking</h3>
                    </div>
                    
                    @if($orders->isEmpty())
                        <div class="p-20 text-center">
                            <div class="text-6xl mb-6 opacity-20">📦</div>
                            <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest italic">No orders found yet</h4>
                            <a href="{{ route('products.index') }}" class="mt-6 inline-block text-indigo-600 font-black text-xs uppercase tracking-widest hover:underline italic">Start Shopping →</a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                                    <tr>
                                        <th class="px-8 py-6">Order ID</th>
                                        <th class="px-8 py-6">Date</th>
                                        <th class="px-8 py-6">Status</th>
                                        <th class="px-8 py-6">Total</th>
                                        <th class="px-8 py-6"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach($orders as $order)
                                    <tr class="group hover:bg-slate-50/50 transition-all">
                                        <td class="px-8 py-6">
                                            <span class="font-black text-slate-900 italic tracking-tighter">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        </td>
                                        <td class="px-8 py-6">
                                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ $order->created_at->format('d M, Y') }}</span>
                                        </td>
                                        <td class="px-8 py-6">
                                            @php
                                                $statusClasses = [
                                                    'pending' => 'bg-amber-50 text-amber-600 ring-amber-100',
                                                    'processing' => 'bg-indigo-50 text-indigo-600 ring-indigo-100',
                                                    'shipped' => 'bg-blue-50 text-blue-600 ring-blue-100',
                                                    'delivered' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                                                    'cancelled' => 'bg-rose-50 text-rose-600 ring-rose-100',
                                                ];
                                                $statusClass = $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-600 ring-slate-200';
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 {{ $statusClass }}">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 font-black text-slate-900 tracking-tighter">
                                            ৳{{ number_format($order->total_amount) }}
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <a href="{{ route('profile.order-details', $order) }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-indigo-600 group-hover:translate-x-1 transition-transform italic">
                                                Track Order <span class="text-xs">→</span>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($orders->hasPages())
                        <div class="p-8 bg-slate-50/50 border-t border-slate-50">
                            {{ $orders->links() }}
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
