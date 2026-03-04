@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8 md:py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">
        <!-- Product Images -->
        <div class="space-y-4">
            <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                @if($product->primaryImage)
                    <img src="{{ $product->primaryImage->image_url }}" class="w-full aspect-square object-contain" alt="{{ $product->name }}">
                @else
                    <div class="w-full aspect-square flex items-center justify-center text-9xl opacity-10">💊</div>
                @endif
            </div>
            
            <div class="grid grid-cols-4 gap-4">
                @foreach($product->images as $img)
                <div class="bg-white rounded-xl border border-gray-100 p-2 cursor-pointer hover:border-blue-500 transition-colors">
                    <img src="{{ $img->image_url }}" class="w-full aspect-square object-contain" alt="Preview">
                </div>
                @endforeach
            </div>
        </div>

        <!-- Product Info -->
        <div class="space-y-8" x-data="{ 
            unitType: 'piece', 
            basePrice: {{ $product->price }},
            stripSize: {{ $product->pieces_per_strip }},
            boxSize: {{ $product->pieces_per_box }},
            get currentPrice() {
                if (this.unitType === 'strip') return this.basePrice * this.stripSize;
                if (this.unitType === 'box') return this.basePrice * this.boxSize;
                return this.basePrice;
            }
        }">
            <div>
                <nav class="flex text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 gap-2">
                    <a href="/" class="hover:text-blue-600">Home</a>
                    <span>/</span>
                    <a href="{{ route('products.index', ['category_id' => $product->category_id]) }}" class="hover:text-blue-600">{{ $product->category->name }}</a>
                </nav>
                <h1 class="text-3xl md:text-5xl font-black text-gray-900 mb-2">{{ $product->name }}</h1>
                <div class="text-lg md:text-xl text-blue-600 font-bold italic mb-4">{{ $product->generic_name }}</div>
                <div class="inline-block bg-gray-100 px-3 py-1 rounded-lg p-1  text-sm font-bold text-gray-600 uppercase tracking-widest">
                    {{ $product->manufacturer ?? 'Unknown Manufacturer' }}
                </div>
            </div>

            <div class="space-y-4">
                @php
                    $total_stock = $product->total_stock;
                @endphp
                <div class="flex items-baseline gap-4">
                    <div class="text-4xl md:text-6xl font-black text-blue-900">৳<span x-text="currentPrice.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></div>
                    <div class="text-gray-400 text-sm font-bold uppercase">Per <span x-text="unitType"></span></div>
                </div>
                
                @if($total_stock > 0)
                    <div class="text-green-600 font-black text-xs uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-600 rounded-full animate-pulse"></span>
                        In Stock ({{ $total_stock }} pieces available)
                    </div>
                @else
                    <div class="text-red-600 font-black text-xs uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 bg-red-600 rounded-full"></span>
                        Out of Stock
                    </div>
                @endif
            </div>

            <form action="{{ route('cart.add', $product) }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest italic">Choose Unit</label>
                        <select name="unit_type" x-model="unitType" class="w-full bg-gray-50 border-gray-200 rounded-2xl p-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none appearance-none">
                            <option value="piece">Piece (1 pcs)</option>
                            <option value="strip">Strip ({{ $product->pieces_per_strip }} pcs)</option>
                            <option value="box">Box ({{ $product->pieces_per_box }} pcs)</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest italic">Quantity</label>
                        <div class="flex items-center bg-gray-50 rounded-2xl p-1 border border-gray-200 h-[58px]">
                            <button type="button" @click="$refs.qty.value = Math.max(1, parseInt($refs.qty.value) - 1)" class="w-12 h-12 flex items-center justify-center font-bold text-gray-600 hover:text-blue-600">－</button>
                            <input type="number" name="quantity" x-ref="qty" value="1" min="1" class="w-full bg-transparent border-none text-center font-black text-lg focus:ring-0">
                            <button type="button" @click="$refs.qty.value = parseInt($refs.qty.value) + 1" class="w-12 h-12 flex items-center justify-center font-bold text-gray-600 hover:text-blue-600">＋</button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white h-14 rounded-2xl font-black text-lg hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 uppercase tracking-widest" @if($total_stock <= 0) disabled @endif>
                    Add to Cart
                </button>
            </form>

            <div class="space-y-6 border-t border-gray-100 pt-8">
                <div>
                    <h3 class="font-black text-gray-900 uppercase text-xs tracking-widest mb-3">Description</h3>
                    <div class="text-gray-600 leading-relaxed">
                        {{ $product->description ?? 'No description available for this product.' }}
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-2xl">
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Stock Category</div>
                        <div class="font-bold text-gray-900">{{ $product->category->name }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl">
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Generic</div>
                        <div class="font-bold text-gray-900">{{ $product->generic_name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
