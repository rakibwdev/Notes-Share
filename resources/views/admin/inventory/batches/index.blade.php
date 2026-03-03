@extends('layouts.admin')

@section('title', 'Batch & Expiry Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-2xl font-bold text-gray-800">Batch Inventory</h3>
        <a href="{{ route('admin.batches.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg p-1  font-bold hover:bg-blue-700 transition-colors">
            + Add Stock Batch
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 text-green-700 font-medium rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('admin.batches.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Product</label>
                <select name="product_id" class="w-full border-gray-200 rounded-lg p-1  focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">All Products</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status Filter</label>
                <select name="filter" class="w-full border-gray-200 rounded-lg p-1  focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">All Batches</option>
                    <option value="expired" {{ request('filter') == 'expired' ? 'selected' : '' }}>Expired Only</option>
                    <option value="expiring_soon" {{ request('filter') == 'expiring_soon' ? 'selected' : '' }}>Expiring Soon (60d)</option>
                </select>
            </div>
            <div class="md:col-span-2 flex items-end">
                <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg p-1  font-bold hover:bg-gray-700 transition-colors text-sm w-full">Apply Filters</button>
            </div>
        </form>
    </div>

    <!-- Batches Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-4 border-b">Batch Info</th>
                    <th class="px-6 py-4 border-b">Product</th>
                    <th class="px-6 py-4 border-b">Qty</th>
                    <th class="px-6 py-4 border-b">Prices</th>
                    <th class="px-6 py-4 border-b">Expiry</th>
                    <th class="px-6 py-4 border-b text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($batches as $batch)
                @php
                    $isExpired = $batch->expiry_date < now();
                    $isNearExpiry = !$isExpired && $batch->expiry_date < now()->addDays(60);
                @endphp
                <tr class="hover:bg-gray-50 {{ $isExpired ? 'bg-red-50/30' : ($isNearExpiry ? 'bg-orange-50/30' : '') }}">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900">#{{ $batch->batch_number }}</div>
                        <div class="text-gray-500 text-xs">ID: {{ $batch->id }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $batch->product->name }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold {{ $batch->quantity == 0 ? 'text-red-500' : 'text-gray-900' }}">
                            {{ number_format($batch->quantity) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs text-gray-500">Buy: ৳{{ number_format($batch->purchase_price, 2) }}</div>
                        <div class="font-bold text-gray-900">Sell: ৳{{ number_format($batch->selling_price, 2) }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-bold {{ $isExpired ? 'text-red-600' : ($isNearExpiry ? 'text-orange-500' : 'text-gray-900') }}">
                                {{ $batch->expiry_date->format('M d, Y') }}
                            </span>
                            @if($isExpired)
                                <span class="text-[10px] text-red-600 font-bold uppercase tracking-tight">EXPIRED</span>
                            @elseif($isNearExpiry)
                                <span class="text-[10px] text-orange-500 font-bold uppercase tracking-tight">EXPIRING SOON</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.batches.destroy', $batch) }}" method="POST" onsubmit="return confirm('Delete this batch?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded" title="Delete">🗑️</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 italic">No inventory batches found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($batches->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50">
            {{ $batches->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
