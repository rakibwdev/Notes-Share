<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Notes Share') }} - Online Pharmacy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
</head>
<body class="bg-white text-slate-900 antialiased font-sans" x-data="{ mobileMenu: false }">
    <!-- Announcement Bar -->
    <div class="bg-indigo-600 text-white py-2 text-center text-[10px] font-black uppercase tracking-[0.2em]">
        Free Delivery on orders over ৳1000 — Dhaka Only
    </div>

    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-xl group-hover:rotate-12 transition-transform shadow-lg shadow-indigo-100">💊</div>
                    <div class="flex flex-col">
                        <span class="text-xl font-black tracking-tighter text-indigo-950 leading-none">Notes<span class="text-indigo-600">Share</span></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Online Pharmacy</span>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center gap-8">
                    <a href="{{ route('products.index') }}" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition-colors">Medicine</a>
                    <a href="#" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition-colors">Healthcare</a>
                    <a href="#" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition-colors">About Us</a>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2 sm:gap-6">
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-slate-600 hover:text-indigo-600 transition-colors group">
                        <span class="text-2xl italic">🛒</span>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-[10px] font-black w-5 h-5 flex items-center justify-center rounded-full ring-4 ring-white">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>

                    <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

                    @auth
                        <a href="{{ route('profile') }}" class="hidden sm:flex items-center gap-2 text-sm font-black text-indigo-950 hover:text-indigo-600 transition-colors uppercase tracking-widest">
                            <span class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs">👤</span>
                            Account
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:block text-sm font-black text-indigo-950 hover:text-indigo-600 transition-colors uppercase tracking-widest">Login</a>
                        <a href="{{ route('register') }}" class="hidden sm:block bg-indigo-600 text-white px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100">Sign Up</a>
                    @endauth

                    <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 text-slate-600 hover:bg-slate-50 rounded-xl">
                        <span class="text-2xl" x-show="!mobileMenu">☰</span>
                        <span class="text-2xl" x-show="mobileMenu">✕</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Nav -->
        <div x-show="mobileMenu" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="lg:hidden bg-white border-t border-slate-100 p-6 space-y-6 shadow-2xl">
            <div class="space-y-4">
                <a href="{{ route('products.index') }}" class="block text-lg font-black text-slate-900 tracking-tight">Medicine Catalog</a>
                <a href="#" class="block text-lg font-black text-slate-900 tracking-tight">Daily Healthcare</a>
                <a href="{{ route('cart.index') }}" class="block text-lg font-black text-slate-900 tracking-tight">Shopping Cart</a>
            </div>
            @guest
            <div class="grid grid-cols-2 gap-4 pt-6 border-t border-slate-100">
                <a href="{{ route('login') }}" class="py-4 text-center text-sm font-black uppercase tracking-widest text-indigo-950 border border-indigo-950 rounded-2xl">Login</a>
                <a href="{{ route('register') }}" class="py-4 text-center text-sm font-black uppercase tracking-widest text-white bg-indigo-600 rounded-2xl">Sign Up</a>
            </div>
            @endguest
        </div>
    </nav>

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Premium Footer -->
    <footer class="bg-slate-950 text-white mt-32 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-500"></div>
        <div class="max-w-7xl mx-auto px-4 py-24 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16">
                <div class="space-y-8 text-center md:text-left">
                    <div class="flex items-center gap-2 justify-center md:justify-start">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-xl">💊</div>
                        <span class="text-2xl font-black tracking-tighter uppercase italic">MedStore<span class="text-indigo-500">BD</span></span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-xs mx-auto md:mx-0">
                        Bangladesh's most trusted digital pharmacy. Dedicated to bringing healthcare accessibility to every doorstep with authenticity guaranteed.
                    </p>
                    <div class="flex gap-4 justify-center md:justify-start">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-900 flex items-center justify-center border border-slate-800 hover:border-indigo-500 transition-colors text-xl">f</a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-900 flex items-center justify-center border border-slate-800 hover:border-indigo-500 transition-colors text-xl">t</a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-900 flex items-center justify-center border border-slate-800 hover:border-indigo-500 transition-colors text-xl">i</a>
                    </div>
                </div>

                <div class="text-center md:text-left">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-500 mb-8">Navigation</h4>
                    <ul class="space-y-4 text-slate-400 text-sm font-medium uppercase tracking-widest">
                        <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors">Catalog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Health Tips</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Prescriptions</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Track Order</a></li>
                    </ul>
                </div>

                <div class="text-center md:text-left">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-500 mb-8">Helpline</h4>
                    <ul class="space-y-6 text-slate-400 text-sm">
                        <li class="flex flex-col">
                            <span class="text-[10px] font-black uppercase tracking-tighter mb-1 opacity-40 italic">Phone Support</span>
                            <span class="text-xl font-black text-white">+880 1234 567 890</span>
                        </li>
                        <li class="flex flex-col">
                            <span class="text-[10px] font-black uppercase tracking-tighter mb-1 opacity-40 italic">Email Us</span>
                            <span class="text-lg font-bold text-white hover:text-indigo-400 transition-colors cursor-pointer">support@medstore.com</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-slate-900/50 p-8 rounded-3xl border border-slate-800 text-center space-y-6">
                    <h4 class="text-xs font-black uppercase tracking-widest text-white">Verified Secure</h4>
                    <div class="flex justify-center gap-4 text-3xl opacity-50">
                        <span>💳</span><span>📱</span><span>💵</span>
                    </div>
                    <p class="text-[10px] text-slate-500 font-bold uppercase leading-relaxed tracking-tighter">Certified by the Drug Administration of Bangladesh</p>
                </div>
            </div>

            <div class="mt-24 pt-8 border-t border-slate-900 flex flex-col md:flex-row justify-between items-center gap-4 text-center">
                <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest italic">
                    © {{ date('Y') }} MedStore Bangladesh — Built for excellence
                </p>
                <div class="flex gap-8 text-[10px] font-black text-slate-600 uppercase tracking-widest">
                    <a href="#" class="hover:text-white">Privacy</a>
                    <a href="#" class="hover:text-white">Terms</a>
                    <a href="#" class="hover:text-white">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
