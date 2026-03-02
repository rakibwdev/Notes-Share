@extends('layouts.admin')

@section('title', 'Medicine Inventory')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-2xl font-bold text-gray-800">Products List</h3>
        <a href="{{ route('admin.products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition-colors">
            + Add New Product
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 text-green-700 font-medium rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 text-red-700 font-medium rounded shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Product or Generic name..." class="w-full border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Category</label>
                <select name="category_id" class="w-full border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg font-bold hover:bg-gray-700 transition-colors text-sm w-full">Filter</button>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-4 border-b">Product Info</th>
                    <th class="px-6 py-4 border-b">Category</th>
                    <th class="px-6 py-4 border-b">Manufacturer</th>
                    <th class="px-6 py-4 border-b">Stock Status</th>
                    <th class="px-6 py-4 border-b">Status</th>
                    <th class="px-6 py-4 border-b text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900">{{ $product->name }}</div>
                        <div class="text-gray-500 italic text-xs">{{ $product->generic_name }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">{{ $product->category->name }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $product->manufacturer ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        @php
                            $stock = $product->total_stock;
                            $stockColor = $stock < 10 ? 'text-red-600 font-bold' : ($stock < 50 ? 'text-orange-500' : 'text-green-600');
                        @endphp
                        <span class="{{ $stockColor }}">
                            {{ number_format($stock) }} Units
                        </span>
                        @if($stock < 10)
                            <div class="text-[10px] text-red-500 font-bold uppercase tracking-tight mt-1">Low Stock!</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($product->status)
                            <span class="text-green-600 flex items-center"><span class="w-2 h-2 bg-green-600 rounded-full mr-2"></span> Active</span>
                        @else
                            <span class="text-gray-400 flex items-center"><span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span> Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded" title="Edit">✏️</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded" title="Delete">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 italic">No medicines found in catalog.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($products->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
