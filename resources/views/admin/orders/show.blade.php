@extends('layouts.admin')

@section('title', 'Order Details #' . $order->id)

@section('content')
<div class="space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.orders.index') }}" class="bg-white p-2 rounded-lg p-1  border border-gray-200 text-gray-500 hover:text-gray-800">⬅️</a>
        <h3 class="text-2xl font-bold text-gray-800">Invoice Details</h3>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Items -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between">
                    <h3 class="font-bold text-gray-800 uppercase text-xs tracking-widest">Ordered Items</h3>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ count($order->items) }} Items</span>
                </div>
                <table class="w-full text-left">
                    <thead class="text-gray-400 text-[10px] uppercase font-bold tracking-widest border-b border-gray-50">
                        <tr>
                            <th class="px-6 py-4">Product</th>
                            <th class="px-6 py-4 text-center">Batch</th>
                            <th class="px-6 py-4 text-center">Qty</th>
                            <th class="px-6 py-4 text-right">Price</th>
                            <th class="px-6 py-4 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $item->product->name }}</div>
                                <div class="text-[10px] text-gray-500 italic">{{ $item->product->generic_name }}</div>
                            </td>
                            <td class="px-6 py-4 text-center text-xs text-gray-600 font-mono">#{{ $item->batch->batch_number }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="font-bold text-gray-900">{{ $item->ordered_quantity }} {{ $item->unit_type }}(s)</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-tighter">({{ $item->quantity }} pieces)</div>
                            </td>
                            <td class="px-6 py-4 text-right text-gray-600">৳{{ number_format($item->price, 2) }}</td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900">৳{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50/50">
                        <tr>
                            <td colspan="4" class="px-6 py-3 text-right text-gray-500 text-xs font-bold uppercase tracking-widest">Order Subtotal</td>
                            <td class="px-6 py-3 text-right font-bold text-gray-900">৳{{ number_format($order->total_price + $order->total_discount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="px-6 py-3 text-right text-red-500 text-xs font-bold uppercase tracking-widest">Discount Applied</td>
                            <td class="px-6 py-3 text-right font-bold text-red-600">- ৳{{ number_format($order->total_discount, 2) }}</td>
                        </tr>
                        <tr class="bg-blue-50/50">
                            <td colspan="4" class="px-6 py-4 text-right text-blue-800 font-black uppercase tracking-widest">Final Total</td>
                            <td class="px-6 py-4 text-right font-black text-blue-900 text-xl">৳{{ number_format($order->total_price, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Order Actions & Info -->
        <div class="space-y-6">
            <!-- Update Status -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-6 uppercase text-xs tracking-widest border-b border-gray-100 pb-2">Status & Delivery</h3>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase">Update Status</label>
                        <select name="status" class="w-full border-gray-200 rounded-lg p-1  focus:ring-blue-500 focus:border-blue-500 text-sm font-bold {{ $order->status === 'Delivered' ? 'text-green-600' : ($order->status === 'Cancelled' ? 'text-red-600' : 'text-blue-600') }}">
                            <option value="Pending" {{ $order->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Confirmed" {{ $order->status === 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="Delivered" {{ $order->status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="Cancelled" {{ $order->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase">Assign Delivery Staff</label>
                        <select name="delivery_man_id" class="w-full border-gray-200 rounded-lg p-1  focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="">Unassigned</option>
                            @foreach($delivery_men as $man)
                                <option value="{{ $man->id }}" {{ $order->delivery_man_id == $man->id ? 'selected' : '' }}>{{ $man->name }} ({{ $man->phone }})</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-blue-900 text-white py-3 rounded-lg p-1  font-bold hover:bg-blue-800 transition-colors shadow-lg shadow-blue-100">
                        Apply Changes
                    </button>
                </form>
            </div>

            <!-- Customer Card -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 uppercase text-xs tracking-widest border-b border-gray-100 pb-2">Customer Info</h3>
                <div class="space-y-3">
                    <div class="font-bold text-gray-900 text-lg">{{ $order->customer->name ?? 'Guest' }}</div>
                    <div class="text-sm text-gray-600 flex items-center gap-2"><span>📞</span> {{ $order->customer->phone ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-600 flex items-start gap-2"><span>📍</span> {{ $order->customer->address ?? 'No address provided' }}</div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-blue-50 p-6 rounded-xl border border-blue-100 text-blue-900">
                <h3 class="font-bold mb-2 uppercase text-[10px] tracking-widest">Payment Method</h3>
                <div class="font-black text-xl flex items-center gap-2">
                    <span class="text-2xl">💳</span> {{ $order->payment_method }}
                </div>
                <div class="mt-4 text-[10px] uppercase font-bold text-blue-700 tracking-tighter italic">Transaction Secure & Verified</div>
            </div>
        </div>
    </div>
</div>
@endsection
