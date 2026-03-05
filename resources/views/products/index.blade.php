@extends('layouts.app')

@section('content')
<div class="bg-blue-900 py-12 mb-12">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-5xl font-black text-white mb-4 uppercase tracking-tighter">Medicine Catalog</h1>
        <p class="text-blue-200">Browse through our extensive collection of authentic medications.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filters -->
        <aside class="space-y-8">
            <div>
                <h3 class="font-black text-gray-900 uppercase text-xs tracking-widest mb-4 border-b border-gray-100 pb-2">Categories</h3>
                <div class="space-y-2">
                    <a href="{{ route('products.index') }}" class="block px-4 py-2 rounded-xl text-sm font-bold {{ !request('category_id') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">All Medicines</a>
                    @foreach($categories as $cat)
                    <a href="{{ route('products.index', ['category_id' => $cat->id]) }}" class="block px-4 py-2 rounded-xl text-sm font-bold {{ request('category_id') == $cat->id ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        {{ $cat->name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </aside>

        <!-- Product Grid -->
        <div class="lg:col-span-3 space-y-8">
            <!-- Search Results & Header -->
            <div class="flex flex-col md:row justify-between items-center gap-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex-grow w-full max-w-md relative group">
                    <form action="{{ route('products.index') }}" method="GET">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search medicine..." class="w-full bg-gray-50 border-gray-100 rounded-2xl py-3 px-4 pl-12 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-blue-500/20 outline-none">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 opacity-40">🔍</span>
                    </form>
                </div>
                @if(request('search'))
                    <a href="{{ route('products.index') }}" class="text-xs font-black text-rose-500 uppercase tracking-widest hover:underline">Clear Search</a>
                @endif
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                @forelse($products as $product)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all group">
                    <a href="{{ route('products.show', $product) }}" class="relative aspect-square bg-gray-50 overflow-hidden block">
                        @if($product->primaryImage)
                            <img src="{{ $product->primaryImage->image_url }}" class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-500" alt="{{ $product->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-6xl opacity-20 grayscale">💊</div>
                        @endif
                    </a>
                    <div class="p-4 md:p-6">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $product->category->name }}</div>
                        <a href="{{ route('products.show', $product) }}" class="font-bold text-gray-900 md:text-lg mb-1 hover:text-blue-600 truncate block">{{ $product->name }}</a>
                        <div class="text-xs text-gray-500 italic mb-4 truncate">{{ $product->generic_name }}</div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between gap-2">
                                <div class="text-lg md:text-xl font-black text-blue-900">৳{{ number_format($product->price, 2) }}</div>
                                @if($product->total_stock > 0)
                                    <div class="text-[10px] font-bold text-emerald-500 uppercase italic">In Stock</div>
                                @else
                                    <div class="text-[10px] font-bold text-rose-500 uppercase italic">Out of Stock</div>
                                @endif
                            </div>
                            
                            <form action="{{ route('cart.add', $product) }}" method="POST" @submit="addToCart($event, $el)" class="space-y-3">
                                @csrf
                                <div class="grid grid-cols-2 gap-2">
                                    <select name="unit_type" class="bg-gray-50 border border-gray-100 rounded-xl px-2 py-2 text-[10px] font-black uppercase tracking-widest text-gray-600 outline-none appearance-none cursor-pointer" @if($product->total_stock <= 0) disabled @endif>
                                        <option value="piece">Piece</option>
                                        <option value="strip">Strip</option>
                                        <option value="box">Box</option>
                                    </select>
                                    <input type="number" name="quantity" value="1" min="1" class="bg-gray-50 border border-gray-100 rounded-xl px-2 py-2 text-[10px] font-black text-center outline-none" @if($product->total_stock <= 0) disabled @endif>
                                </div>
                                <button type="submit" class="w-full {{ $product->total_stock > 0 ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-300 cursor-not-allowed' }} text-white py-2.5 rounded-xl font-black text-[10px] uppercase tracking-[0.2em] transition-all shadow-lg" @if($product->total_stock <= 0) disabled @endif>
                                    {{ $product->total_stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center space-y-4">
                    <div class="text-6xl">🔍</div>
                    <h3 class="text-xl font-bold text-gray-900">No medicines found</h3>
                    <p class="text-gray-500">Try searching for a different generic name or medicine.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
