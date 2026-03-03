@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12 md:py-24">
    <h1 class="text-3xl md:text-5xl font-black text-gray-900 mb-12 uppercase tracking-tighter">Your Cart</h1>

    @if(count($cart) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2 space-y-6">
            @foreach($cart as $id => $details)
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col md:row items-center gap-6">
                <div class="w-24 h-24 bg-gray-50 rounded-2xl shrink-0">
                    @if($details['image'])
                        <img src="{{ $details['image'] }}" class="w-full h-full object-contain p-2" alt="Product">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-3xl opacity-20">💊</div>
                    @endif
                </div>
                <div class="grow text-center md:text-left">
                    <h3 class="font-black text-gray-900 md:text-xl">{{ $details['name'] }}</h3>
                    <p class="text-xs text-blue-600 font-bold italic">{{ $details['generic'] }}</p>
                    <div class="mt-2 text-gray-400 text-sm font-bold uppercase tracking-widest">৳{{ number_format($details['price'], 2) }} / unit</div>
                </div>
                <div class="flex items-center gap-8">
                    <div class="font-black text-gray-900 text-xl">{{ $details['quantity'] }} pcs</div>
                    <form action="{{ route('cart.remove') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $id }}">
                        <button type="submit" class="text-red-500 hover:text-red-700 p-2">🗑️</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="space-y-6">
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-xl">
                <h3 class="font-black text-gray-900 uppercase text-xs tracking-widest mb-6">Order Summary</h3>
                <div class="space-y-4">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span class="font-bold text-gray-900">৳{{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Delivery Fee</span>
                        <span class="font-bold text-gray-900 text-xs uppercase">Calculated at checkout</span>
                    </div>
                    <div class="border-t border-gray-100 pt-4 flex justify-between items-end">
                        <span class="font-black text-gray-900 text-lg uppercase tracking-tighter">Total</span>
                        <span class="text-3xl font-black text-blue-900">৳{{ number_format($total, 2) }}</span>
                    </div>
                </div>
                <a href="{{ route('checkout.index') }}" class="block w-full bg-blue-600 text-white text-center py-4 rounded-2xl font-black text-lg mt-8 hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 uppercase tracking-widest">Checkout</a>
            </div>
            <a href="{{ route('products.index') }}" class="block text-center text-gray-500 font-bold text-sm hover:text-blue-600 transition-colors uppercase tracking-widest italic">← Continue Shopping</a>
        </div>
    </div>
    @else
    <div class="bg-white py-24 rounded-3xl border border-gray-100 shadow-sm text-center space-y-6">
        <div class="text-8xl">🛒</div>
        <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">Your cart is empty</h2>
        <p class="text-gray-500 max-w-xs mx-auto">Looks like you haven't added any medicines yet.</p>
        <a href="{{ route('products.index') }}" class="inline-block bg-blue-600 text-white px-8 py-4 rounded-full font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">Shop Now</a>
    </div>
    @endif
</div>
@endsection
