<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
</head>

<body class="bg-slate-50 text-slate-900 antialiased font-sans" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex overflow-hidden">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-slate-900/60 z-40 lg:hidden backdrop-blur-sm"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 shrink-0">
            <div class="h-full flex flex-col">
                <!-- Logo -->
                <div class="h-20 flex items-center px-8 border-b border-slate-100">
                    @php
                    $logo = \App\Models\Setting::getValue('logo_url');
                    $companyName = \App\Models\Setting::getValue('company_name', 'NotesShare');
                    @endphp
                    @if($logo)
                    <img src="{{ $logo }}" class="h-8 w-auto object-contain" alt="{{ $companyName }}">
                    <span class="text-xl font-black tracking-tighter text-indigo-950 uppercase ml-2">{{ $companyName }}</span>
                    @else
                    <span class="text-2xl mr-2">🏥</span>
                    <span class="text-xl font-black tracking-tighter text-indigo-950 uppercase ml-2">{{ $companyName }}</span>
                    @endif
                </div>

                <!-- Nav -->
                <nav class="grow p-6 space-y-1.5 overflow-y-auto">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-4 mb-4">Core Management</p>

                    @php
                    $links = [
                    ['route' => 'admin.dashboard', 'icon' => '📊', 'label' => 'Overview'],
                    ['route' => 'admin.products.index', 'icon' => '📦', 'label' => 'Medicines'],
                    ['route' => 'admin.batches.index', 'icon' => '🔢', 'label' => 'Inventory'],
                    ['route' => 'admin.categories.index', 'icon' => '🏷️', 'label' => 'Categories'],
                    ['route' => 'admin.orders.index', 'icon' => '🛒', 'label' => 'Sales Orders'],
                    ['route' => 'admin.customers.index', 'icon' => '👥', 'label' => 'Customers'],
                    ['route' => 'admin.delivery.index', 'icon' => '🚚', 'label' => 'Delivery Staff'],
                    ['route' => 'admin.prescriptions.index', 'icon' => '📄', 'label' => 'Prescriptions'],
                    ['route' => 'admin.users.index', 'icon' => '👤', 'label' => 'User Control'],
                    ];
                    @endphp

                    @foreach($links as $link)
                    <a href="{{ route($link['route']) }}"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs($link['route'] . '*') ? 'bg-indigo-50 text-indigo-700 font-bold shadow-sm shadow-indigo-100' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span class="text-xl mr-3 opacity-80 group-hover:scale-110 transition-transform">{{ $link['icon'] }}</span>
                        <span class="text-sm tracking-tight">{{ $link['label'] }}</span>
                    </a>
                    @endforeach

                    <div class="pt-8 mt-8 border-t border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-4 mb-4">System</p>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <span class="text-xl mr-3">⚙️</span>
                            <span class="text-sm">Settings</span>
                        </a>
                        <a href="/" class="flex items-center px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 transition-all">
                            <span class="text-xl mr-3">🌐</span>
                            <span class="text-sm">Live Site</span>
                        </a>
                    </div>
                </nav>

                <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">A</div>
                        <div class="grow">
                            <div class="text-xs font-black text-slate-900">Admin User</div>
                            <div class="text-[10px] text-slate-500 uppercase tracking-tighter">Super Administrator</div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="grow flex flex-col min-w-0">
            <!-- Header -->
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-4 sm:px-8 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg p-1 ">
                        <span class="text-2xl">☰</span>
                    </button>
                    <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight uppercase">@yield('title', 'Admin Panel')</h2>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    <button class="hidden sm:flex p-2 text-slate-400 hover:text-indigo-600 transition-colors relative">
                        <span class="text-xl">🔔</span>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>
                    <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="group flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-all shadow-sm hover:shadow-rose-100/50">
                            <span class="text-xs group-hover:translate-x-1 transition-transform">←</span>
                            Sign Out
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="grow overflow-y-auto p-4 sm:p-8">
                <div class="max-w-7xl mx-auto space-y-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>

</html>