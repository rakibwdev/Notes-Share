@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12 md:py-24">
    <h1 class="text-3xl md:text-5xl font-black text-gray-900 mb-12 uppercase tracking-tighter text-center">Complete Order</h1>

    <form action="{{ route('checkout.place') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        @csrf
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                <h3 class="font-black text-gray-900 uppercase text-xs tracking-widest border-b border-gray-100 pb-4">Delivery Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $customer->name ?? auth()->user()->name ?? '') }}" required class="w-full border-gray-200 rounded-xl py-3 px-4 focus:ring-2 focus:ring-blue-500" placeholder="John Doe">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $customer->phone ?? auth()->user()->phone ?? '') }}" required class="w-full border-gray-200 rounded-xl py-3 px-4 font-mono focus:ring-2 focus:ring-blue-500" placeholder="+880...">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase">Full Delivery Address</label>
                    <textarea name="address" required rows="3" class="w-full border-gray-200 rounded-xl py-3 px-4 focus:ring-2 focus:ring-blue-500" placeholder="Street, City, Area...">{{ old('address', $customer->address ?? auth()->user()->address ?? '') }}</textarea>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                <h3 class="font-black text-gray-900 uppercase text-xs tracking-widest border-b border-gray-100 pb-4">Payment Method</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:bg-gray-50 transition-colors has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 group">
                        <input type="radio" name="payment_method" value="Cash on Delivery" checked class="hidden">
                        <span class="text-2xl mr-4 group-has-[:checked]:scale-110 transition-transform">💵</span>
                        <div>
                            <div class="font-black text-gray-900">Cash on Delivery</div>
                            <div class="text-xs text-gray-500">Pay when you receive</div>
                        </div>
                    </label>
                    <!-- <label class="relative flex items-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:bg-gray-50 transition-colors has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 group">
                        <input type="radio" name="payment_method" value="bKash/Online" class="hidden">
                        <span class="text-2xl mr-4 group-has-[:checked]:scale-110 transition-transform">📱</span>
                        <div>
                            <div class="font-black text-gray-900">Online / Mobile</div>
                            <div class="text-xs text-gray-500">bKash, Nagad, or Card</div>
                        </div>
                    </label> -->
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-blue-900 p-8 rounded-3xl text-white shadow-2xl sticky top-24">
                <h3 class="font-black uppercase text-xs tracking-widest mb-8 text-blue-300">Final Summary</h3>
                <div class="space-y-4 mb-8">
                    @foreach($cart as $details)
                    <div class="flex justify-between text-sm">
                        <span class="text-blue-200">{{ $details['name'] }} x {{ $details['quantity'] }}</span>
                        <span class="font-bold">৳{{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                    </div>
                    @endforeach
                    <div class="border-t border-blue-800 pt-4 flex justify-between items-end">
                        <span class="font-black uppercase tracking-tighter">Total Amount</span>
                        <span class="text-3xl font-black">৳{{ number_format($total, 2) }}</span>
                    </div>
                </div>
                <button type="submit" class="w-full bg-white text-blue-900 py-4 rounded-2xl font-black text-lg hover:bg-blue-50 transition-all shadow-xl uppercase tracking-widest">Place Order</button>
                <p class="text-center text-[10px] text-blue-400 mt-4 uppercase font-bold tracking-widest">Secure encrypted checkout</p>
            </div>
        </div>
    </form>
</div>
@endsection
