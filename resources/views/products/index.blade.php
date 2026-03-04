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
            <!-- Search Results Header -->
            @if(request('search'))
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <span class="text-gray-600">Showing results for "<span class="font-bold text-gray-900">{{ request('search') }}</span>"</span>
                    <a href="{{ route('products.index') }}" class="text-xs font-black text-red-500 uppercase">Clear Search</a>
                </div>
            @endif

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
                                <div class="text-[10px] font-bold text-gray-400 uppercase italic">Base Price</div>
                            </div>
                            
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="space-y-3">
                                @csrf
                                <div class="grid grid-cols-2 gap-2">
                                    <select name="unit_type" class="bg-gray-50 border border-gray-100 rounded-xl px-3 py-2 text-[10px] font-black uppercase tracking-widest text-gray-600 outline-none focus:ring-2 focus:ring-blue-500/20 appearance-none cursor-pointer">
                                        <option value="piece">Piece</option>
                                        <option value="strip">Strip</option>
                                        <option value="box">Box</option>
                                    </select>
                                    <input type="number" name="quantity" value="1" min="1" class="bg-gray-50 border border-gray-100 rounded-xl px-3 py-2 text-[10px] font-black text-center outline-none focus:ring-2 focus:ring-blue-500/20">
                                </div>
                                <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                                    Add to Cart
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
