@extends('layouts.app')

@section('content')
<div class="bg-slate-50/50 min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('profile') }}" class="bg-white p-2 rounded-xl border border-slate-200 text-slate-400 hover:text-slate-900 transition-colors">←</a>
            <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase italic">Edit Profile</h1>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden">
            <div class="p-10">
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Name -->
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic ml-1">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="Enter your name">
                            @error('name') <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase italic ml-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic ml-1">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="name@example.com">
                            @error('email') <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase italic ml-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Phone -->
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic ml-1">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="+880...">
                            @error('phone') <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase italic ml-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic ml-1">Default Delivery Address</label>
                        <textarea name="address" rows="4" class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none resize-none" placeholder="Enter your full address for faster checkout...">{{ old('address', $user->address) }}</textarea>
                        @error('address') <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase italic ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-6 border-t border-slate-50 flex justify-end gap-4">
                        <a href="{{ route('profile') }}" class="px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                        <button type="submit" class="bg-indigo-600 text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
