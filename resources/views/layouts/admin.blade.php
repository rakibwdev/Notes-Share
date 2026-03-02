<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - Pharmacy Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="bg-blue-900 text-white w-64 flex-shrink-0 flex flex-col hidden md:flex">
            <div class="p-6 text-xl font-bold border-b border-blue-800">
                Pharmacy Admin
            </div>
            <nav class="flex-grow py-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-800' : '' }}">
                    <span class="mr-3">📊</span> Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.products.*') ? 'bg-blue-800' : '' }}">
                    <span class="mr-3">📦</span> Medicines
                </a>
                <a href="{{ route('admin.batches.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.batches.*') ? 'bg-blue-800' : '' }}">
                    <span class="mr-3">🔢</span> Batches & Expiry
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.categories.*') ? 'bg-blue-800' : '' }}">
                    <span class="mr-3">🏷️</span> Categories
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.orders.*') ? 'bg-blue-800' : '' }}">
                    <span class="mr-3">🛒</span> Orders
                </a>
                <a href="{{ route('admin.customers.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.customers.*') ? 'bg-blue-800' : '' }}">
                    <span class="mr-3">👥</span> Customers
                </a>
                <a href="{{ route('admin.delivery.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.delivery.*') ? 'bg-blue-800' : '' }}">
                    <span class="mr-3">🚚</span> Delivery Staff
                </a>
                <a href="#" class="flex items-center px-6 py-3 hover:bg-blue-800 border-t border-blue-800 mt-4">
                    <span class="mr-3">⚙️</span> Settings
                </a>
            </nav>
            <div class="p-4 border-t border-blue-800 text-sm text-blue-300">
                v1.0.0
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-grow flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow h-16 flex items-center justify-between px-8">
                <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Admin Panel')</h2>
                <div class="flex items-center gap-4">
                    <span class="text-gray-600">Admin User</span>
                    <button class="bg-gray-200 p-2 rounded-full hover:bg-gray-300">👤</button>
                </div>
            </header>

            <!-- Content -->
            <div class="p-8 flex-grow">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
