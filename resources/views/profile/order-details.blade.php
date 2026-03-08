@extends('layouts.app')

@section('content')
<div class="bg-slate-50/50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Order Header -->
        <div class="flex justify-between items-end mb-8">
            <div>
                <a href="{{ route('profile') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition-all mb-2 inline-block italic">← Back to Profile</a>
                <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase italic">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h1>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Placed on</p>
                <p class="font-bold text-slate-900">{{ $order->created_at->format('d M, Y \a	 h:i A') }}</p>
            </div>
        </div>

        <!-- Tracking Visual -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white p-10 mb-8">
            <div class="flex justify-between items-center mb-12">
                <h3 class="text-xs font-black text-indigo-600 uppercase tracking-[0.3em] italic">Live tracking status</h3>
                <div class="px-4 py-1.5 rounded-full bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-200">
                    Current Stage: {{ $order->status }}
                </div>
            </div>
            
            <div class="relative px-4">
                <!-- Background Line -->
                <div class="absolute top-6 left-0 w-full h-1 bg-slate-100 rounded-full"></div>
                
                @php
                    $steps = ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered'];
                    $currentIdx = array_search($order->status, $steps);
                    if ($order->status === 'Cancelled') $currentIdx = -1;
                    $progressWidth = $currentIdx >= 0 ? ($currentIdx / (count($steps) - 1)) * 100 : 0;
                @endphp

                <!-- Progress Line -->
                <div class="absolute top-6 left-0 h-1 bg-indigo-600 rounded-full transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(79,70,229,0.5)]" 
                     style="width: {{ $progressWidth }}%"></div>

                <div class="relative flex justify-between">
                    @foreach($steps as $idx => $step)
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shadow-lg transition-all duration-500 z-10
                                {{ $idx < $currentIdx ? 'bg-indigo-600 text-white shadow-indigo-100' : '' }}
                                {{ $idx === $currentIdx ? 'bg-indigo-600 text-white shadow-indigo-300 scale-125 ring-4 ring-indigo-50 animate-pulse' : '' }}
                                {{ $idx > $currentIdx ? 'bg-white text-slate-300 border border-slate-100' : '' }}">
                                
                                @if($idx < $currentIdx)
                                    <span class="text-base">✓</span>
                                @else
                                    @if($step == 'Pending') 📝 @elseif($step == 'Confirmed') 👍 @elseif($step == 'Processing') ⚙️ @elseif($step == 'Shipped') 🚚 @else 📦 @endif
                                @endif
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="text-[10px] font-black uppercase tracking-widest transition-colors duration-500 {{ $idx <= $currentIdx ? 'text-indigo-600' : 'text-slate-400' }} italic text-center">{{ $step }}</span>
                                @if($idx === $currentIdx)
                                    <span class="text-[8px] font-bold text-indigo-400 uppercase tracking-tighter mt-1 italic animate-bounce">Active Now</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($order->status === 'Cancelled')
                <div class="mt-12 p-6 bg-rose-50 rounded-2xl border border-rose-100 text-center flex items-center justify-center gap-4 animate-fade-in">
                    <span class="text-2xl">🚫</span>
                    <div>
                        <p class="text-rose-600 font-black text-xs uppercase tracking-widest italic">Order Status: Cancelled</p>
                        <p class="text-[10px] text-rose-400 font-bold mt-1">This order was cancelled and will not be processed.</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Order Items -->
            <div class="md:col-span-2 space-y-4">
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden">
                    <div class="p-8 border-b border-slate-50">
                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest italic">Order Items</h4>
                    </div>
                    <div class="p-8 space-y-6">
                        @foreach($order->items as $item)
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex-shrink-0 flex items-center justify-center text-2xl border border-slate-100">
                                @if($item->product->primaryImage)
                                    <img src="{{ $item->product->primaryImage->image_url }}" class="w-full h-full object-contain p-2" alt="IMG">
                                @else
                                    💊
                                @endif
                            </div>
                            <div class="flex-grow">
                                <h5 class="font-black text-slate-900 uppercase italic tracking-tight text-sm">{{ $item->product->name }}</h5>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">{{ $item->product->generic_name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-slate-900 italic">৳{{ number_format($item->price, 2) }} × {{ $item->ordered_quantity }} {{ $item->unit_type }}(s)</p>
                                <p class="text-sm font-black text-indigo-600 italic">৳{{ number_format($item->subtotal, 2) }}</p>
                                <p class="text-[8px] text-slate-400 uppercase font-bold tracking-tighter">Batch: {{ $item->batch->batch_number }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="bg-slate-50 p-8 flex justify-between items-center border-t border-slate-100">
                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest italic">Total Amount Paid</span>
                        <span class="text-2xl font-black text-slate-900 italic tracking-tighter">৳{{ number_format($order->total_amount) }}</span>
                    </div>
                </div>
            </div>

            <!-- Delivery Info -->
            <div class="space-y-8">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white">
                    <h4 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em] mb-6 italic">Delivery Details</h4>
                    <div class="space-y-6">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Shipping Address</p>
                            <p class="text-xs font-bold text-slate-900 leading-relaxed">{{ $order->delivery_address }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Contact Number</p>
                            <p class="text-xs font-bold text-slate-900">{{ $order->phone_number }}</p>
                        </div>
                    </div>
                </div>

                @if($order->deliveryMan)
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white">
                    <h4 class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.3em] mb-6 italic">Assigned Courier</h4>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">🚚</div>
                        <div>
                            <p class="text-sm font-black text-slate-900 uppercase italic">{{ $order->deliveryMan->name }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $order->deliveryMan->phone }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
