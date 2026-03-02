@extends('layouts.app')

@section('content')
<!-- Premium Hero -->
<div class="relative bg-white pt-16 pb-32 overflow-hidden">
    <div class="absolute top-0 right-0 w-1/2 h-full bg-slate-50 -skew-x-12 translate-x-24 hidden lg:block"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-16">
            <div class="space-y-10 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-700 px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest animate-bounce">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                    Now Delivering Nationwide
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-slate-900 leading-[1.1] tracking-tighter">
                    Healthcare <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Reimagined.</span>
                </h1>
                <p class="text-lg text-slate-500 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium italic">
                    Order authentic medicines and healthcare essentials from the safety of your home. Verified by professional pharmacists.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('products.index') }}" class="bg-slate-900 text-white px-10 py-5 rounded-2xl font-black text-sm uppercase tracking-[0.2em] hover:bg-indigo-600 transition-all shadow-2xl shadow-indigo-100 flex items-center justify-center gap-2 group">
                        Start Shopping
                        <span class="group-hover:translate-x-1 transition-transform">→</span>
                    </a>
                    <a href="#" class="bg-white text-slate-900 border border-slate-200 px-10 py-5 rounded-2xl font-black text-sm uppercase tracking-[0.2em] hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                        <span>📄</span> Upload Rx
                    </a>
                </div>
                <div class="flex items-center gap-8 justify-center lg:justify-start pt-4">
                    <div class="flex flex-col">
                        <span class="text-2xl font-black text-slate-900 leading-none tracking-tighter">10k+</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Users</span>
                    </div>
                    <div class="w-px h-8 bg-slate-200"></div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-black text-slate-900 leading-none tracking-tighter">100%</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Authentic</span>
                    </div>
                </div>
            </div>
            <div class="relative hidden lg:block">
                <div class="absolute -top-20 -right-20 w-96 h-96 bg-indigo-100 rounded-full blur-3xl opacity-50"></div>
                <div class="absolute -bottom-20 -left-20 w-72 h-72 bg-purple-100 rounded-full blur-3xl opacity-50"></div>
                <img src="https://img.freepik.com/free-photo/pharmacist-giving-medicine-customer-pharmacy_23-2148892589.jpg" 
                     class="relative w-full rounded-[3rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.15)] border-[12px] border-white transform hover:scale-[1.02] transition-transform duration-700" 
                     alt="MedStore Hero">
            </div>
        </div>
    </div>
</div>

<!-- Refined Categories -->
<div class="max-w-7xl mx-auto px-4 -mt-16 relative z-20">
    <div class="bg-white/80 backdrop-blur-2xl p-10 rounded-[3rem] shadow-xl shadow-slate-200/50 border border-white">
        <div class="flex flex-col md:flex-row justify-between items-center gap-8 mb-12">
            <div>
                <h2 class="text-xs font-black text-indigo-600 uppercase tracking-[0.3em] mb-2 text-center md:text-left">Categories</h2>
                <p class="text-2xl font-black text-slate-900 tracking-tighter text-center md:text-left">Browse by Healthcare Need</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition-colors">View All Categories →</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('products.index', ['category_id' => $category->id]) }}" 
               class="flex flex-col items-center p-6 bg-slate-50/50 rounded-[2rem] border border-transparent hover:border-indigo-100 hover:bg-white hover:shadow-xl hover:shadow-indigo-50/50 transition-all duration-300 group">
                <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center text-3xl mb-4 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">💊</div>
                <span class="text-sm font-black text-slate-900 tracking-tight text-center">{{ $category->name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<div class="max-w-7xl mx-auto px-4 mt-32">
    <div class="flex flex-col md:flex-row justify-between items-end gap-4 mb-16">
        <div class="max-w-md">
            <h2 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.4em] mb-4">Latest Arrivals</h2>
            <p class="text-4xl font-black text-slate-900 tracking-tighter leading-tight">Authentic Medicines for Your Family</p>
        </div>
        <a href="{{ route('products.index') }}" class="bg-slate-100 text-slate-900 px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all">Explore Full Catalog</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($featured_products as $product)
        <div class="group relative flex flex-col h-full">
            <div class="relative bg-slate-50 rounded-[2.5rem] p-8 aspect-square overflow-hidden mb-6 group-hover:bg-white group-hover:shadow-2xl group-hover:shadow-indigo-100/50 transition-all duration-500">
                @if($product->primaryImage)
                    <img src="{{ $product->primaryImage->image_url }}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition-transform duration-700" alt="{{ $product->name }}">
                @else
                    <div class="w-full h-full flex items-center justify-center text-7xl opacity-10">💊</div>
                @endif
                <div class="absolute top-6 right-6 flex flex-col gap-2">
                    <button class="w-10 h-10 bg-white rounded-full shadow-sm flex items-center justify-center text-xl hover:bg-rose-50 hover:text-rose-500 transition-colors">♥</button>
                </div>
                <div class="absolute bottom-6 left-6 right-6 translate-y-12 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                    <form action="{{ route('cart.add', $product) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-600 shadow-xl transition-all">Quick Add to Cart</button>
                    </form>
                </div>
            </div>
            
            <div class="px-2 flex-grow flex flex-col">
                <div class="flex justify-between items-start mb-2">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest italic">{{ $product->category->name }}</p>
                        <a href="{{ route('products.show', $product) }}" class="text-xl font-black text-slate-900 hover:text-indigo-600 transition-colors tracking-tighter leading-tight block">
                            {{ $product->name }}
                        </a>
                    </div>
                    @php
                        $price = $product->batches->sortBy('selling_price')->first()->selling_price ?? 0;
                    @endphp
                    <div class="text-xl font-black text-slate-900 tracking-tighter">৳{{ number_format($price, 0) }}</div>
                </div>
                <p class="text-sm text-slate-400 font-medium italic mb-4">{{ $product->generic_name }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Trust Strip -->
<div class="bg-indigo-950 mt-40 py-20 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none" 
         style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-16 text-center">
            <div class="space-y-4">
                <div class="text-4xl mb-6">🚚</div>
                <h4 class="text-xl font-black text-white tracking-tight italic">Fastest Home Delivery</h4>
                <p class="text-indigo-300 text-sm leading-relaxed max-w-xs mx-auto">Get your essential medicines delivered in as little as 4 hours within Dhaka city.</p>
            </div>
            <div class="space-y-4">
                <div class="text-4xl mb-6">🔬</div>
                <h4 class="text-xl font-black text-white tracking-tight italic">Pharmacist Verified</h4>
                <p class="text-indigo-300 text-sm leading-relaxed max-w-xs mx-auto">Every order is checked by our licensed pharmacists to ensure accuracy and safety.</p>
            </div>
            <div class="space-y-4">
                <div class="text-4xl mb-6">🔒</div>
                <h4 class="text-xl font-black text-white tracking-tight italic">100% Authentic Products</h4>
                <p class="text-indigo-300 text-sm leading-relaxed max-w-xs mx-auto">We source directly from manufacturers like Beximco, Square, and Incepta.</p>
            </div>
        </div>
    </div>
</div>
@endsection
