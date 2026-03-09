@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-10" x-data="{ showExpiringModal: false }">
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h3 class="text-3xl font-black text-slate-900 tracking-tighter">Welcome back, Administrator 👋</h3>
            <p class="text-slate-500 mt-1">Here is a quick overview of your pharmacy's performance today.</p>
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Sales -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col justify-between relative overflow-hidden group hover:shadow-xl hover:shadow-indigo-50 transition-all duration-300">
            <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-bl-[3rem] -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative">
                <div class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-4">Total Revenue</div>
                <div class="text-3xl font-black text-slate-900">৳{{ number_format($stats['total_sales'], 2) }}</div>
            </div>
            <div class="mt-6 flex items-center text-[10px] font-bold text-emerald-600 bg-emerald-50 w-fit px-3 py-1 rounded-full uppercase tracking-tighter">
                Verified Sales
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col justify-between relative overflow-hidden group hover:shadow-xl hover:shadow-blue-50 transition-all duration-300">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-[3rem] -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative">
                <div class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-4">Total Orders</div>
                <div class="text-3xl font-black text-slate-900">{{ number_format($stats['total_orders']) }}</div>
            </div>
            <div class="mt-6 flex items-center text-[10px] font-bold text-blue-600 bg-blue-50 w-fit px-3 py-1 rounded-full uppercase tracking-tighter">
                Processing
            </div>
        </div>

        <!-- Low Stock -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col justify-between relative overflow-hidden group hover:shadow-xl hover:shadow-orange-50 transition-all duration-300 text-orange-950">
            <div class="absolute top-0 right-0 w-24 h-24 bg-orange-50 rounded-bl-[3rem] -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative">
                <div class="text-orange-300 text-[10px] font-black uppercase tracking-widest mb-4">Low Stock Alerts</div>
                <div class="text-3xl font-black">{{ number_format($stats['low_stock_count']) }}</div>
            </div>
            <div class="mt-6 flex items-center text-[10px] font-bold text-orange-600 bg-orange-100 w-fit px-3 py-1 rounded-full uppercase tracking-tighter">
                Restock Needed
            </div>
        </div>

        <!-- Expired -->
        <div class="bg-rose-600 p-8 rounded-[2rem] shadow-xl shadow-rose-100 flex flex-col justify-between relative overflow-hidden group hover:bg-rose-700 transition-all duration-300 text-white">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-bl-[3rem] -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative">
                <div class="text-rose-200 text-[10px] font-black uppercase tracking-widest mb-4">Expired Items</div>
                <div class="text-3xl font-black">{{ number_format($stats['expired_stock_count']) }}</div>
            </div>
            <div class="mt-6 flex items-center text-[10px] font-bold text-rose-600 bg-white w-fit px-3 py-1 rounded-full uppercase tracking-tighter">
                Critical Issue
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Expiring Soon Alert -->
        @if($expiring_soon->isNotEmpty())
        <div class="lg:col-span-3 bg-amber-50 border border-amber-100 p-6 rounded-[2rem] flex items-center justify-between cursor-pointer hover:bg-amber-100/50 transition-all group" @click="showExpiringModal = true">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">⚠️</div>
                <div>
                    <h4 class="font-black text-amber-900 uppercase text-xs tracking-widest italic">Inventory Warning: Items Expiring Soon</h4>
                    <p class="text-xs text-amber-700 font-medium">There are {{ $expiring_soon->count() }} batches set to expire within the next {{ $lowStockDays }} days. <span class="underline font-black">Click to view details</span></p>
                </div>
            </div>
            <div class="flex -space-x-2 overflow-hidden">
                @foreach($expiring_soon->take(5) as $batch)
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-200 flex items-center justify-center text-[10px] font-black uppercase" title="{{ $batch->product->name }} (Exp: {{ $batch->expiry_date }})">
                        {{ substr($batch->product->name, 0, 1) }}
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                <h3 class="font-black text-slate-900 uppercase text-xs tracking-widest">Recent Sales Activity</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 text-xs font-black uppercase hover:underline tracking-widest">View History</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                        <tr>
                            <th class="px-8 py-4">Transaction</th>
                            <th class="px-8 py-4">Client</th>
                            <th class="px-8 py-4">Amount</th>
                            <th class="px-8 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recent_orders as $order)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="font-black text-slate-900 tracking-tight">#INV-{{ $order->id }}</div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $order->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-8 py-5 text-sm font-medium text-slate-600">{{ $order->customer->name ?? 'Guest' }}</td>
                            <td class="px-8 py-5 font-black text-slate-900 tracking-tight">৳{{ number_format($order->total_price, 2) }}</td>
                            <td class="px-8 py-5">
                                @php
                                    $statusColors = [
                                        'Delivered' => 'bg-emerald-100 text-emerald-700',
                                        'Cancelled' => 'bg-rose-100 text-rose-700',
                                        'Pending' => 'bg-amber-100 text-orange-700',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter {{ $statusColors[$order->status] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-8 py-12 text-center text-slate-400 italic">No transactions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Inventory Stats -->
        <div class="space-y-8">
            <div class="bg-indigo-950 p-8 rounded-[2rem] shadow-xl text-white relative overflow-hidden">
                <div class="absolute -bottom-12 -right-12 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>
                <h3 class="font-black uppercase text-xs tracking-widest mb-6 opacity-60">Inventory Health</h3>
                <div class="space-y-6 relative">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium opacity-80 italic">Total SKUs</span>
                        <span class="text-2xl font-black tracking-tight">{{ number_format($stats['total_products']) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium opacity-80 italic">Active Customers</span>
                        <span class="text-2xl font-black tracking-tight">{{ number_format($stats['total_customers']) }}</span>
                    </div>
                    <div class="h-1 bg-white/10 rounded-full overflow-hidden mt-8">
                        <div class="h-full bg-indigo-400 w-3/4"></div>
                    </div>
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-40">System optimized & Healthy</p>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 border-dashed">
                <h3 class="font-black text-slate-900 uppercase text-xs tracking-widest mb-4">Quick Shortcuts</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('admin.products.create') }}" class="p-4 bg-slate-50 rounded-2xl text-center hover:bg-indigo-50 transition-colors group">
                        <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">➕</div>
                        <div class="text-[10px] font-black text-slate-600 uppercase tracking-tighter">Add Drug</div>
                    </a>
                    <a href="{{ route('admin.batches.create') }}" class="p-4 bg-slate-50 rounded-2xl text-center hover:bg-emerald-50 transition-colors group">
                        <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">📥</div>
                        <div class="text-[10px] font-black text-slate-600 uppercase tracking-tighter">Add Stock</div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Expiring Items Modal -->
    <div x-show="showExpiringModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showExpiringModal = false"></div>
        <div class="relative w-full max-w-3xl bg-white rounded-[2.5rem] shadow-2xl overflow-hidden animate-fade-in">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tighter uppercase italic">Expiring Medicine Batches</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Batches expiring within {{ $lowStockDays }} days</p>
                </div>
                <button @click="showExpiringModal = false" class="text-2xl text-slate-300 hover:text-slate-900">✕</button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest sticky top-0">
                        <tr>
                            <th class="px-8 py-4">Medicine</th>
                            <th class="px-8 py-4 text-center">Batch #</th>
                            <th class="px-8 py-4 text-center">Qty Left</th>
                            <th class="px-8 py-4 text-right">Expiry Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm font-bold">
                        @foreach($expiring_soon as $batch)
                        <tr class="hover:bg-amber-50/30 transition-colors">
                            <td class="px-8 py-4">
                                <div class="text-slate-900 uppercase italic">{{ $batch->product->name }}</div>
                                <div class="text-[10px] text-slate-400 italic">{{ $batch->product->generic_name }}</div>
                            </td>
                            <td class="px-8 py-4 text-center font-mono text-xs">{{ $batch->batch_number }}</td>
                            <td class="px-8 py-4 text-center">{{ $batch->quantity }} pcs</td>
                            <td class="px-8 py-4 text-right">
                                <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-[10px] font-black italic">
                                    {{ \Carbon\Carbon::parse($batch->expiry_date)->format('d M, Y') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-8 bg-slate-50/50 border-t border-slate-50 flex justify-end">
                <button @click="showExpiringModal = false" class="px-8 py-3 rounded-xl bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest">Close Overview</button>
            </div>
        </div>
    </div>
</div>
@endsection
