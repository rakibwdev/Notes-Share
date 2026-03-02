@extends('layouts.admin')

@section('title', 'Registered Customers')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-800">Customer Directory</h3>
        <form action="{{ route('admin.customers.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or Phone..." class="border-gray-200 rounded-lg text-sm">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold">Search</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-600 text-[10px] uppercase font-bold tracking-widest border-b">
                <tr>
                    <th class="px-6 py-4">Customer Details</th>
                    <th class="px-6 py-4">Phone</th>
                    <th class="px-6 py-4">Orders</th>
                    <th class="px-6 py-4">Last Address</th>
                    <th class="px-6 py-4">Joined At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $customer->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $customer->phone }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter">{{ $customer->orders_count }} Orders</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500 italic max-w-xs truncate">{{ $customer->address ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-gray-600 text-xs">{{ $customer->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">No customers registered yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($customers->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50">
            {{ $customers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
