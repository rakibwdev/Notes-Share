@extends('layouts.admin')

@section('title', 'Delivery Management')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-bold text-gray-800 text-xs uppercase tracking-widest">Delivery Staff List</h3>
        </div>
        <table class="w-full text-left">
            <thead class="text-gray-400 text-[10px] uppercase font-bold tracking-widest border-b">
                <tr>
                    <th class="px-6 py-4">Staff Name</th>
                    <th class="px-6 py-4">Phone</th>
                    <th class="px-6 py-4">Delivered Orders</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($delivery_men as $man)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $man->name }}</td>
                    <td class="px-6 py-4 text-gray-600 font-mono">{{ $man->phone }}</td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-900">{{ $man->orders_count }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $man->status ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $man->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.delivery.destroy', $man) }}" method="POST" onsubmit="return confirm('Remove this staff?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-bold underline">Remove</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">No delivery staff registered.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
        <h3 class="font-bold text-gray-800 mb-6 text-xs uppercase tracking-widest border-b pb-2">Add Delivery Staff</h3>
        <form action="{{ route('admin.delivery.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase">Staff Name</label>
                <input type="text" name="name" required class="w-full border-gray-200 rounded-lg text-sm" placeholder="Full Name">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase">Phone Number</label>
                <input type="text" name="phone" required class="w-full border-gray-200 rounded-lg text-sm font-mono" placeholder="+8801...">
            </div>
            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="status" value="1" checked id="status_man" class="rounded text-blue-600 focus:ring-blue-500 border-gray-300">
                <label for="status_man" class="text-[10px] font-bold text-gray-600 uppercase cursor-pointer tracking-tighter">Active Status</label>
            </div>
            <button type="submit" class="w-full bg-blue-900 text-white py-3 rounded-lg font-bold hover:bg-blue-800 transition-colors text-xs uppercase tracking-widest">Register Staff</button>
        </form>
    </div>
</div>
@endsection
