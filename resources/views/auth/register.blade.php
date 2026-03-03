@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-20 bg-slate-50/50">
    <div class="max-w-md w-full">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-black text-slate-900 tracking-tighter mb-2">Join MedStore</h1>
            <p class="text-slate-500 font-medium">Create your account to start managing your health today.</p>
        </div>

        <div class="bg-white p-10 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white">
            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic ml-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="John Doe">
                    @error('name') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter italic ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic ml-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="name@example.com">
                    @error('email') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter italic ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic ml-1">Password</label>
                    <input type="password" name="password" required class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="Min. 8 characters">
                    @error('password') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter italic ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic ml-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="Confirm your password">
                </div>

                <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-2xl font-black text-sm uppercase tracking-[0.2em] hover:bg-indigo-600 transition-all shadow-2xl shadow-indigo-100 mt-4">
                    Create Account
                </button>
            </form>
        </div>

        <p class="text-center mt-10 text-sm font-bold text-slate-400 uppercase tracking-widest">
            Already have an account? <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700">Sign In Instead →</a>
        </p>
    </div>
</div>
@endsection
