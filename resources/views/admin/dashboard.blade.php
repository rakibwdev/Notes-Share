@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
<div class="space-y-8">
    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Sales -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Sales (Delivered)</div>
            <div class="mt-2 flex items-baseline justify-between">
                <div class="text-3xl font-bold text-gray-900">৳ {{ number_format($stats['total_sales'], 2) }}</div>
                <div class="text-green-500 bg-green-50 px-2 py-1 rounded text-xs font-bold">Revenue</div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Orders</div>
            <div class="mt-2 flex items-baseline justify-between">
                <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_orders']) }}</div>
                <div class="text-blue-500 bg-blue-50 px-2 py-1 rounded text-xs font-bold">Volume</div>
            </div>
        </div>

        <!-- Low Stock -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Low Stock Warning</div>
            <div class="mt-2 flex items-baseline justify-between">
                <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['low_stock_count']) }}</div>
                <div class="text-orange-500 bg-orange-50 px-2 py-1 rounded text-xs font-bold">Alert</div>
            </div>
        </div>

        <!-- Expired Stock -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Expired Products</div>
            <div class="mt-2 flex items-baseline justify-between">
                <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['expired_stock_count']) }}</div>
                <div class="text-red-500 bg-red-50 px-2 py-1 rounded text-xs font-bold">Critical</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Orders Table -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Recent Orders</h3>
                <a href="#" class="text-blue-600 text-sm font-medium hover:underline">View All</a>
            </div>
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Order ID</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recent_orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">#{{ $order->id }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $order->customer->name ?? 'Unknown' }}</td>
                        <td class="px-6 py-4 text-gray-900 font-bold">৳{{ number_format($order->total_price, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $order->status === 'Delivered' ? 'bg-green-100 text-green-700' : ($order->status === 'Cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ $order->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 italic">No orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Quick Info Panel -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4">Inventory Summary</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">Total Medicines</span>
                        <span class="font-bold text-gray-900">{{ number_format($stats['total_products']) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">Registered Customers</span>
                        <span class="font-bold text-gray-900">{{ number_format($stats['total_customers']) }}</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-900 p-6 rounded-xl shadow-sm text-white">
                <h3 class="font-bold mb-2">Need Support?</h3>
                <p class="text-blue-200 text-sm mb-4">Access the helpline for any technical assistance.</p>
                <a href="#" class="block text-center bg-blue-700 hover:bg-blue-600 py-2 rounded font-bold transition-colors">Help Center</a>
            </div>
        </div>
    </div>
</div>
@endsection
