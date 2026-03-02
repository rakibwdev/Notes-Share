@extends('layouts.app')

@section('content')
<!-- Hero / Banners -->
<div class="relative bg-blue-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-24">
        <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-12">
            <div class="space-y-6 text-center md:text-left">
                <h1 class="text-4xl md:text-6xl font-black text-blue-900 leading-tight">Your Trusted Online <span class="text-blue-600">Pharmacy</span></h1>
                <p class="text-lg text-gray-600">Get authentic medicines delivered at your doorstep across Bangladesh. Safe, fast, and reliable.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                    <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-blue-700 transition-all shadow-xl shadow-blue-200 text-center">Shop Now</a>
                    <a href="#" class="bg-white text-blue-900 border border-blue-100 px-8 py-4 rounded-full font-bold text-lg hover:bg-blue-50 transition-all text-center">Upload Prescription</a>
                </div>
            </div>
            <div class="hidden md:block relative">
                <div class="absolute inset-0 bg-blue-200 rounded-full blur-3xl opacity-30 transform -translate-y-12"></div>
                <img src="https://img.freepik.com/free-photo/pharmacist-working-pharmacy-drugstore_23-2148906306.jpg" class="relative rounded-3xl shadow-2xl border-8 border-white transform rotate-2 hover:rotate-0 transition-transform duration-500" alt="Pharmacy">
            </div>
        </div>
    </div>
</div>

<!-- Categories -->
<div class="max-w-7xl mx-auto px-4 mt-16">
    <h2 class="text-2xl font-black text-gray-900 mb-8 border-l-4 border-blue-600 pl-4 uppercase tracking-wider text-sm">Browse by Category</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($categories as $category)
        <a href="{{ route('products.index', ['category_id' => $category->id]) }}" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:border-blue-200 hover:shadow-md transition-all text-center group">
            <div class="text-3xl mb-3 transform group-hover:scale-110 transition-transform">💊</div>
            <div class="font-bold text-gray-800 text-sm">{{ $category->name }}</div>
        </a>
        @endforeach
    </div>
</div>

<!-- Featured Products -->
<div class="max-w-7xl mx-auto px-4 mt-20">
    <div class="flex justify-between items-end mb-10">
        <div>
            <h2 class="text-2xl font-black text-gray-900 uppercase tracking-wider text-sm border-l-4 border-blue-600 pl-4 mb-2">New Arrivals</h2>
            <p class="text-gray-500 text-sm">Recently added authentic medications</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-blue-600 font-bold text-sm hover:underline">View All →</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8">
        @foreach($featured_products as $product)
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all group flex flex-col">
            <a href="{{ route('products.show', $product) }}" class="relative aspect-square bg-gray-50 overflow-hidden block">
                @if($product->primaryImage)
                    <img src="{{ $product->primaryImage->image_url }}" class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-500" alt="{{ $product->name }}">
                @else
                    <div class="w-full h-full flex items-center justify-center text-6xl opacity-20 grayscale">💊</div>
                @endif
                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest text-blue-900 shadow-sm border border-gray-100">In Stock</div>
            </a>
            <div class="p-4 md:p-6 flex-grow flex flex-col">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $product->category->name }}</div>
                <a href="{{ route('products.show', $product) }}" class="font-bold text-gray-900 md:text-lg mb-1 hover:text-blue-600 truncate block">{{ $product->name }}</a>
                <div class="text-xs text-gray-500 italic mb-4 truncate">{{ $product->generic_name }}</div>
                
                <div class="mt-auto flex items-center justify-between gap-2">
                    @php
                        $price = $product->batches->sortBy('selling_price')->first()->selling_price ?? 0;
                    @endphp
                    <div class="text-lg md:text-2xl font-black text-blue-900">৳{{ number_format($price, 2) }}</div>
                    <form action="{{ route('cart.add', $product) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-600 text-white p-2 md:p-3 rounded-2xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-100">
                            <span class="md:hidden">🛒</span>
                            <span class="hidden md:inline text-xs font-bold px-2 uppercase tracking-widest">Add to Cart</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Features Section -->
<div class="max-w-7xl mx-auto px-4 mt-32 mb-20 grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="flex items-start gap-4 p-8 bg-blue-900 rounded-3xl text-white shadow-2xl">
        <span class="text-4xl">🚚</span>
        <div>
            <h4 class="font-bold text-lg mb-1">Same Day Delivery</h4>
            <p class="text-blue-200 text-sm">In selected cities including Dhaka and Chittagong.</p>
        </div>
    </div>
    <div class="flex items-start gap-4 p-8 bg-white rounded-3xl border border-gray-100 shadow-xl">
        <span class="text-4xl text-blue-600">🛡️</span>
        <div>
            <h4 class="font-bold text-lg mb-1 text-gray-900">100% Authentic</h4>
            <p class="text-gray-500 text-sm">Directly sourced from top pharmaceutical companies.</p>
        </div>
    </div>
    <div class="flex items-start gap-4 p-8 bg-white rounded-3xl border border-gray-100 shadow-xl">
        <span class="text-4xl text-blue-600">📞</span>
        <div>
            <h4 class="font-bold text-lg mb-1 text-gray-900">Pharmacist Support</h4>
            <p class="text-gray-500 text-sm">Professional advice available 24/7 for your needs.</p>
        </div>
    </div>
</div>
@endsection
