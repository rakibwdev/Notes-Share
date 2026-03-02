@extends('layouts.admin')

@section('title', 'Order Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-2xl font-bold text-gray-800">Sales Orders</h3>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 text-green-700 font-medium rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                <select name="status" class="w-full border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">All Statuses</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="md:col-span-2 flex items-end gap-2">
                <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg font-bold hover:bg-gray-700 transition-colors text-sm flex-grow">Filter Orders</button>
                <a href="{{ route('admin.orders.index') }}" class="bg-gray-100 text-gray-600 px-6 py-2 rounded-lg font-bold hover:bg-gray-200 transition-colors text-sm">Reset</a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-4 border-b">Order ID</th>
                    <th class="px-6 py-4 border-b">Customer</th>
                    <th class="px-6 py-4 border-b">Date</th>
                    <th class="px-6 py-4 border-b">Total Price</th>
                    <th class="px-6 py-4 border-b">Delivery Staff</th>
                    <th class="px-6 py-4 border-b">Status</th>
                    <th class="px-6 py-4 border-b text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-bold text-gray-900">#{{ $order->id }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $order->customer->name ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $order->customer->phone ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $order->created_at->format('M d, Y') }}
                        <div class="text-[10px]">{{ $order->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-900 uppercase">
                        ৳{{ number_format($order->total_price, 2) }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $order->deliveryMan->name ?? 'Unassigned' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $order->status === 'Delivered' ? 'bg-green-100 text-green-700' : ($order->status === 'Cancelled' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.orders.show', $order) }}" class="inline-block bg-blue-50 text-blue-600 px-3 py-1 rounded font-bold text-xs hover:bg-blue-100 transition-colors">
                            Manage Order
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500 italic">No sales orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($orders->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
