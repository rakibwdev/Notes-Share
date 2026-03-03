@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-2xl font-black text-slate-900 tracking-tight uppercase italic">Platform Users</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Manage administrative privileges and access</p>
    </div>

    @if(session('success'))
        <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 text-indigo-700 font-bold rounded-r-xl shadow-sm animate-fade-in">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border-l-4 border-rose-500 p-4 text-rose-700 font-bold rounded-r-xl shadow-sm animate-fade-in">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50/50 text-slate-400 text-[10px] uppercase font-black tracking-[0.2em] italic">
                <tr>
                    <th class="px-8 py-6">User Info</th>
                    <th class="px-8 py-6">Contact</th>
                    <th class="px-8 py-6">Role</th>
                    <th class="px-8 py-6">Joined Date</th>
                    <th class="px-8 py-6 text-right">Administrative Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-sm font-bold text-slate-900">
                @foreach($users as $user)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center text-lg shadow-inner group-hover:scale-110 transition-transform">👤</div>
                            <div class="flex flex-col">
                                <span class="tracking-tight">{{ $user->name }}</span>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $user->email }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-xs">{{ $user->phone ?? 'N/A' }}</span>
                    </td>
                    <td class="px-8 py-6">
                        @if($user->is_admin)
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest shadow-sm ring-1 ring-indigo-100">
                                <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full mr-2"></span>
                                Administrator
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest">
                                Standard User
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-xs font-bold italic opacity-60">{{ $user->created_at->format('M d, Y') }}</span>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex justify-end gap-3">
                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $user->is_admin ? 'text-rose-500 hover:bg-rose-50 border border-rose-100' : 'text-indigo-600 hover:bg-indigo-50 border border-indigo-100' }} transition-all">
                                    {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                                </button>
                            </form>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Permanently delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-300 hover:text-rose-600 transition-colors">🗑️</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($users->hasPages())
        <div class="p-8 bg-slate-50/50 border-t border-slate-50">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
