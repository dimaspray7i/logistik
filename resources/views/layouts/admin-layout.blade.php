<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Logistics CRM') }} — Admin</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-cream text-gray-800" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex">
            <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden"></div>

            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed lg:static inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 transform transition-transform duration-200 ease-in-out flex flex-col">
                <div class="h-16 flex items-center px-6 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <span class="font-poppins font-bold text-lg text-gray-900">LogistikCRM</span>
                    </div>
                </div>
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>
                    <x-admin-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6\'></path></svg>'">Dasbor</x-admin-nav-link>

                    <p class="px-4 pt-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Master Data</p>
                    <x-admin-nav-link href="{{ route('admin.customers.index') }}" :active="request()->routeIs('admin.customers.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z\'></path></svg>'">Pelanggan</x-admin-nav-link>
                    <x-admin-nav-link href="{{ route('admin.contacts.index') }}" :active="request()->routeIs('admin.contacts.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z\'></path></svg>'">Kontak / PIC</x-admin-nav-link>
                    <x-admin-nav-link href="{{ route('admin.products.index') }}" :active="request()->routeIs('admin.products.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4\'></path></svg>'">Produk</x-admin-nav-link>
                    <x-admin-nav-link href="{{ route('admin.vehicles.index') }}" :active="request()->routeIs('admin.vehicles.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4\'></path></svg>'">Kendaraan</x-admin-nav-link>
                    <x-admin-nav-link href="{{ route('admin.drivers.index') }}" :active="request()->routeIs('admin.drivers.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\'></path></svg>'">Supir</x-admin-nav-link>

                    <p class="px-4 pt-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Transaksi</p>
                    <x-admin-nav-link href="{{ route('admin.orders.index') }}" :active="request()->routeIs('admin.orders.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2\'></path></svg>'">Pesanan</x-admin-nav-link>
                    <x-admin-nav-link href="{{ route('admin.shipments.index') }}" :active="request()->routeIs('admin.shipments.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0\'></path></svg>'">Pengiriman</x-admin-nav-link>
                </nav>
                <div class="p-4 border-t border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center text-primary font-semibold text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">Administrator</p>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="flex-1 flex flex-col min-w-0">
                <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-20">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div class="flex-1"></div>
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-gray-100 transition">
                            <span class="text-sm font-medium text-gray-700 hidden sm:block">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-card shadow-soft border border-gray-100 py-1 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profil Saya</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-primary hover:bg-gray-50">Keluar</button>
                            </form>
                        </div>
                    </div>
                </header>

                @isset($header)
                    <div class="bg-white border-b border-gray-100">
                        <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">{{ $header }}</div>
                    </div>
                @endisset

                <main class="flex-1">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">{{ $slot }}</div>
                </main>
            </div>
        </div>
    </body>
</html>