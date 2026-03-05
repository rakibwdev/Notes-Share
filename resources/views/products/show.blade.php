@extends('layouts.app')

@section('content')
<div class="bg-slate-50/50 min-h-screen">
    <!-- Breadcrumbs -->
    <div class="max-w-7xl mx-auto px-4 py-6">
        <nav class="flex text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 gap-3">
            <a href="/" class="hover:text-indigo-600 transition-colors">Home</a>
            <span class="opacity-30">/</span>
            <a href="{{ route('products.index') }}" class="hover:text-indigo-600 transition-colors">Medicine</a>
            <span class="opacity-30">/</span>
            <a href="{{ route('products.index', ['category_id' => $product->category_id]) }}" class="hover:text-indigo-600 transition-colors">{{ $product->category->name }}</a>
            <span class="opacity-30">/</span>
            <span class="text-indigo-600 italic">{{ $product->name }}</span>
        </nav>
    </div>

    <div class="max-w-7xl mx-auto px-4 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            <!-- Left: Product Images (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white p-12 relative overflow-hidden group">
                    <!-- Zoom Badge -->
                    <div class="absolute top-6 right-6 bg-slate-50 text-[8px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full border border-slate-100 opacity-0 group-hover:opacity-100 transition-opacity">
                        Hover to Zoom
                    </div>
                    
                    @if($product->primaryImage)
                        <img src="{{ $product->primaryImage->image_url }}" class="w-full aspect-square object-contain transform group-hover:scale-110 transition-transform duration-700" alt="{{ $product->name }}">
                    @else
                        <div class="w-full aspect-square flex items-center justify-center text-9xl opacity-10 grayscale">💊</div>
                    @endif
                </div>
                
                @if($product->images->count() > 1)
                <div class="grid grid-cols-4 gap-4">
                    @foreach($product->images as $img)
                    <button class="bg-white rounded-2xl border-2 {{ $loop->first ? 'border-indigo-600' : 'border-transparent' }} p-2 hover:border-indigo-600 transition-all shadow-sm">
                        <img src="{{ $img->image_url }}" class="w-full aspect-square object-contain" alt="Preview">
                    </button>
                    @endforeach
                </div>
                @endif

                <!-- Mobile Quick Stats -->
                <div class="grid grid-cols-2 gap-4 lg:hidden">
                    <div class="bg-indigo-50 p-4 rounded-2xl border border-indigo-100">
                        <div class="text-[8px] font-black text-indigo-400 uppercase tracking-widest mb-1 italic">Healthcare Need</div>
                        <div class="text-xs font-black text-indigo-900 uppercase italic">{{ $product->category->name }}</div>
                    </div>
                    <div class="bg-slate-900 p-4 rounded-2xl border border-slate-800 text-white">
                        <div class="text-[8px] font-black text-slate-500 uppercase tracking-widest mb-1 italic">Generic</div>
                        <div class="text-xs font-black uppercase italic">{{ $product->generic_name }}</div>
                    </div>
                </div>
            </div>

            <!-- Right: Product Info & Buy (7 cols) -->
            <div class="lg:col-span-7 space-y-10" x-data="{ 
                uType: 'piece', 
                baseP: {{ $product->price }},
                sSize: {{ $product->pieces_per_strip }},
                bSize: {{ $product->pieces_per_box }},
                get curP() {
                    if (this.uType === 'strip') return this.baseP * this.sSize;
                    if (this.uType === 'box') return this.baseP * this.bSize;
                    return this.baseP;
                }
            }">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="bg-emerald-50 text-emerald-600 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest italic border border-emerald-100">Verified Product</span>
                        <span class="text-slate-300 font-bold">|</span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">{{ $product->manufacturer ?? 'Global Manufacturer' }}</span>
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tighter uppercase italic leading-tight">{{ $product->name }}</h1>
                    <p class="text-xl font-bold text-indigo-600 tracking-tight italic">{{ $product->generic_name }}</p>
                </div>

                <div class="flex items-baseline gap-6">
                    <div class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter italic">৳<span x-text="curP.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Price Per <span x-text="uType" class="text-indigo-600 font-black"></span></span>
                        <span class="text-xs font-bold text-emerald-500">Tax Included</span>
                    </div>
                </div>

                <!-- Stock & Fast Delivery -->
                <div class="flex flex-wrap gap-4 items-center">
                    @php $total_stock = $product->total_stock; @endphp
                    <div class="flex items-center gap-3 px-4 py-2 rounded-xl {{ $total_stock > 0 ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100' }} border text-[10px] font-black uppercase tracking-widest italic">
                        <span class="w-2 h-2 {{ $total_stock > 0 ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }} rounded-full"></span>
                        {{ $total_stock > 0 ? 'In Stock ('.$total_stock.' units)' : 'Currently Out of Stock' }}
                    </div>
                    <div class="flex items-center gap-3 px-4 py-2 rounded-xl bg-indigo-50 text-indigo-700 border border-indigo-100 text-[10px] font-black uppercase tracking-widest italic">
                        <span>🚚</span> Delivered in 4-6 Hours
                    </div>
                </div>

                <!-- Buying Form -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white">
                    <form action="{{ route('cart.add', $product) }}" method="POST" @submit="addToCart($event, $el)" class="space-y-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic ml-1">Select Package Unit</label>
                                <div class="relative">
                                    <select name="unit_type" x-model="uType" class="w-full bg-slate-50 border-slate-200 rounded-2xl p-5 text-sm font-black text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none appearance-none cursor-pointer">
                                        <option value="piece">Individual Piece (1 pcs)</option>
                                        <option value="strip">Medicine Strip ({{ $product->pieces_per_strip }} pcs)</option>
                                        <option value="box">Full Packet Box ({{ $product->pieces_per_box }} pcs)</option>
                                    </select>
                                    <span class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none opacity-20">▼</span>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic ml-1">Quantity</label>
                                <div class="flex items-center bg-slate-50 rounded-2xl border border-slate-200 p-1 h-[62px]">
                                    <button type="button" @click="$refs.qty.value = Math.max(1, parseInt($refs.qty.value) - 1)" class="w-14 h-full flex items-center justify-center font-black text-xl text-slate-400 hover:text-indigo-600 transition-colors">－</button>
                                    <input type="number" name="quantity" x-ref="qty" value="1" min="1" class="w-full bg-transparent border-none text-center font-black text-lg focus:ring-0 text-slate-900">
                                    <button type="button" @click="$refs.qty.value = parseInt($refs.qty.value) + 1" class="w-14 h-full flex items-center justify-center font-black text-xl text-slate-400 hover:text-indigo-600 transition-colors">＋</button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full h-20 rounded-[2rem] font-black text-sm uppercase tracking-[0.3em] transition-all shadow-2xl flex items-center justify-center gap-4 group
                                {{ $total_stock > 0 ? 'bg-slate-900 text-white hover:bg-indigo-600 shadow-indigo-100' : 'bg-slate-100 text-slate-400 cursor-not-allowed shadow-none' }}" 
                                @if($total_stock <= 0) disabled @endif>
                            <span class="text-xl group-hover:scale-125 transition-transform">🛒</span>
                            {{ $total_stock > 0 ? 'Secure Add to Cart' : 'Currently Unavailable' }}
                        </button>
                    </form>
                </div>

                <!-- Detailed Information Sections -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                    <div class="space-y-4 bg-white p-8 rounded-[2rem] border border-white shadow-sm">
                        <h3 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em] italic border-b border-slate-50 pb-4">Indications & Usage</h3>
                        <p class="text-sm font-medium text-slate-600 leading-relaxed italic">
                            Used for symptoms related to {{ $product->category->name }}. Consult your healthcare provider for specific dosage and instructions based on your health profile.
                        </p>
                    </div>
                    <div class="space-y-4 bg-white p-8 rounded-[2rem] border border-white shadow-sm">
                        <h3 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em] italic border-b border-slate-50 pb-4">Storage & Safety</h3>
                        <ul class="space-y-3">
                            <li class="text-[10px] font-black uppercase text-slate-400 flex items-center gap-2">
                                <span class="text-indigo-500">✓</span> Keep in a cool, dry place
                            </li>
                            <li class="text-[10px] font-black uppercase text-slate-400 flex items-center gap-2">
                                <span class="text-indigo-500">✓</span> Keep away from children
                            </li>
                            <li class="text-[10px] font-black uppercase text-slate-400 flex items-center gap-2">
                                <span class="text-indigo-500">✓</span> Check expiry before use
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Full Description Section -->
        <div class="mt-24 space-y-12">
            <div class="flex items-center gap-8">
                <h2 class="text-2xl font-black text-slate-900 tracking-tighter uppercase italic shrink-0">Product Overview</h2>
                <div class="h-px bg-slate-200 grow"></div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                <div class="lg:col-span-8 space-y-8">
                    <div class="bg-white p-10 rounded-[3rem] shadow-xl shadow-slate-200/50 border border-white">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 italic">In-depth Information</h4>
                        <div class="prose prose-slate max-w-none text-slate-600 leading-loose italic font-medium">
                            {!! nl2br(e($product->description ?? 'No detailed information has been provided for this medicine yet. We ensure all our medicines are sourced from authorized channels and verified for authenticity.')) !!}
                        </div>
                    </div>
                </div>
                
                <div class="lg:col-span-4 space-y-8">
                    <div class="bg-slate-900 p-10 rounded-[3rem] text-white shadow-2xl shadow-indigo-100 relative overflow-hidden">
                        <div class="relative z-10">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-8 italic">Specifications</h4>
                            <div class="space-y-6">
                                <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic">Category</span>
                                    <span class="text-xs font-black uppercase italic">{{ $product->category->name }}</span>
                                </div>
                                <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic">Composition</span>
                                    <span class="text-xs font-black uppercase italic">{{ $product->generic_name }}</span>
                                </div>
                                <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic">Brand Owner</span>
                                    <span class="text-xs font-black uppercase italic">{{ $product->manufacturer ?? 'Verified Partner' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic">Package</span>
                                    <span class="text-xs font-black uppercase italic">{{ $product->pieces_per_strip }} Pcs / Strip</span>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -bottom-8 -right-8 text-9xl opacity-5">🛡️</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @if(isset($related_products) && count($related_products) > 0)
        <div class="mt-40">
            <div class="flex flex-col md:flex-row justify-between items-end gap-4 mb-16">
                <div>
                    <h2 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.4em] mb-4 italic">Recommended For You</h2>
                    <p class="text-4xl font-black text-slate-900 tracking-tighter uppercase italic leading-tight">Related Medications</p>
                </div>
                <a href="{{ route('products.index', ['category_id' => $product->category_id]) }}" class="bg-white text-slate-900 border border-slate-200 px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-50 transition-all">View All in Category</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($related_products as $rel)
                <div class="group relative flex flex-col h-full">
                    <div class="relative bg-white rounded-[2.5rem] p-8 aspect-square overflow-hidden mb-6 group-hover:shadow-2xl group-hover:shadow-indigo-100/50 transition-all duration-500 border border-transparent hover:border-white">
                        @if($rel->primaryImage)
                            <img src="{{ $rel->primaryImage->image_url }}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition-transform duration-700" alt="{{ $rel->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-7xl opacity-10">💊</div>
                        @endif
                        <div class="absolute bottom-6 left-6 right-6 translate-y-12 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                            <form action="{{ route('cart.add', $rel) }}" method="POST" @submit="addToCart($event, $el)" class="bg-white/90 backdrop-blur-md p-4 rounded-[2rem] shadow-2xl border border-white space-y-3">
                                @csrf
                                <input type="hidden" name="unit_type" value="piece">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full bg-slate-900 text-white py-3 rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-indigo-600 shadow-xl transition-all italic">Quick Add</button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="px-2 grow flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest italic">{{ $rel->category->name }}</p>
                                <a href="{{ route('products.show', $rel) }}" class="text-xl font-black text-slate-900 hover:text-indigo-600 transition-colors tracking-tighter leading-tight block uppercase italic">
                                    {{ $rel->name }}
                                </a>
                            </div>
                            <div class="text-xl font-black text-slate-900 tracking-tighter italic">৳{{ number_format($rel->price, 0) }}</div>
                        </div>
                        <p class="text-sm text-slate-400 font-medium italic mb-4">{{ $rel->generic_name }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
