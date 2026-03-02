<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Pharmacy Store') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900" x-data="{ mobileMenuOpen: false }">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex-shrink-0 flex items-center gap-2">
                        <span class="text-2xl">💊</span>
                        <span class="text-xl font-bold text-blue-900 tracking-tight hidden sm:block">MedStore BD</span>
                    </a>
                </div>

                <!-- Search Bar (Desktop) -->
                <div class="hidden md:flex items-center flex-1 max-w-md mx-8">
                    <form action="{{ route('products.index') }}" method="GET" class="w-full relative">
                        <input type="text" name="search" placeholder="Search medicine, generic..." class="w-full bg-gray-100 border-transparent rounded-full py-2 px-4 pl-10 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
                    </form>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-blue-600 transition-colors">
                        <span class="text-2xl">🛒</span>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full ring-2 ring-white">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>
                    
                    @auth
                        <a href="{{ route('profile') }}" class="hidden sm:block text-sm font-bold text-gray-700 hover:text-blue-600">My Account</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:block text-sm font-bold text-gray-700 hover:text-blue-600">Login</a>
                        <a href="{{ route('register') }}" class="hidden sm:block bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-bold hover:bg-blue-700 transition-all">Register</a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-600">
                        <span x-show="!mobileMenuOpen">☰</span>
                        <span x-show="mobileMenuOpen">✕</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" class="md:hidden bg-white border-t border-gray-100 p-4 space-y-4 shadow-xl">
            <form action="{{ route('products.index') }}" method="GET" class="relative">
                <input type="text" name="search" placeholder="Search..." class="w-full bg-gray-100 border-transparent rounded-lg py-2 px-4 focus:ring-2 focus:ring-blue-500">
            </form>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('products.index') }}" class="flex items-center justify-center p-3 bg-gray-50 rounded-lg text-sm font-bold">📂 Catalog</a>
                <a href="{{ route('cart.index') }}" class="flex items-center justify-center p-3 bg-gray-50 rounded-lg text-sm font-bold">🛒 Cart</a>
            </div>
            @guest
                <a href="{{ route('login') }}" class="block w-full text-center py-3 border border-blue-600 text-blue-600 rounded-lg font-bold">Login</a>
                <a href="{{ route('register') }}" class="block w-full text-center py-3 bg-blue-600 text-white rounded-lg font-bold">Create Account</a>
            @endguest
        </div>
    </nav>

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-blue-900 text-white mt-12 py-12">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">💊</span>
                    <span class="text-xl font-bold tracking-tight">MedStore BD</span>
                </div>
                <p class="text-blue-200 text-sm">Reliable pharmaceutical marketplace in Bangladesh. Delivering health to your doorstep.</p>
            </div>
            <div>
                <h4 class="font-bold mb-4 uppercase text-xs tracking-widest">Quick Links</h4>
                <ul class="text-blue-200 text-sm space-y-2">
                    <li><a href="{{ route('products.index') }}" class="hover:text-white">Shop Medicines</a></li>
                    <li><a href="#" class="hover:text-white">Track Order</a></li>
                    <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4 uppercase text-xs tracking-widest">Help Line</h4>
                <ul class="text-blue-200 text-sm space-y-2">
                    <li>📞 +880 1234 567 890</li>
                    <li>✉️ support@medstore.com</li>
                    <li>🕒 24/7 Available</li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4 uppercase text-xs tracking-widest">Payment Methods</h4>
                <div class="flex gap-2 text-2xl">
                    <span>💳</span> <span>💵</span> <span>📱</span>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 border-t border-blue-800 mt-12 pt-8 text-center text-blue-400 text-xs">
            © {{ date('Y') }} MedStore Bangladesh. All rights reserved.
        </div>
    </footer>
</body>
</html>
