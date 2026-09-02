@props([
    'role' => 'admin',
])

@php
    $user = auth()->user();
    $isAdmin = $user && $user->isAdmin();
@endphp

<!-- Mobile Backdrop Overlay -->
<div x-cloak
     x-show="sidebarOpen" 
     x-transition:enter="transition-opacity ease-linear duration-150"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false" 
     class="fixed inset-0 z-40 bg-gray-900/40 backdrop-blur-xs lg:hidden"></div>

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" 
       class="fixed lg:sticky top-0 inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200/80 transform transition-transform duration-200 ease-in-out flex flex-col h-screen shadow-xs shrink-0">
    
    <!-- Logo & Header -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100 shrink-0">
        <a href="{{ $isAdmin ? route('admin.dashboard') : route('customer.dashboard') }}" class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white shadow-sm">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <span class="font-poppins font-bold text-lg text-gray-900 tracking-tight">LOGISTIK<span class="text-primary">CRM</span></span>
        </a>
        <button @click="sidebarOpen = false" aria-label="Tutup menu" class="lg:hidden p-1.5 rounded-btn text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Navigation Menu Items -->
    <nav class="flex-1 px-3 py-4 space-y-6 overflow-y-auto overscroll-contain">
        @if ($isAdmin)
            <!-- UTAMA -->
            <div>
                <p class="px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Utama</p>
                <div class="space-y-1">
                    <x-admin-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6\'></path></svg>'">Dasbor</x-admin-nav-link>
                </div>
            </div>

            <!-- MASTER DATA -->
            <div>
                <p class="px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Master Data</p>
                <div class="space-y-1">
                    <x-admin-nav-link href="{{ route('admin.customers.index') }}" :active="request()->routeIs('admin.customers.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z\'></path></svg>'">Pelanggan</x-admin-nav-link>
                    <x-admin-nav-link href="{{ route('admin.contacts.index') }}" :active="request()->routeIs('admin.contacts.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z\'></path></svg>'">Kontak / PIC</x-admin-nav-link>
                    <x-admin-nav-link href="{{ route('admin.products.index') }}" :active="request()->routeIs('admin.products.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4\'></path></svg>'">Produk</x-admin-nav-link>
                    <x-admin-nav-link href="{{ route('admin.vehicles.index') }}" :active="request()->routeIs('admin.vehicles.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4\'></path></svg>'">Kendaraan</x-admin-nav-link>
                    <x-admin-nav-link href="{{ route('admin.drivers.index') }}" :active="request()->routeIs('admin.drivers.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\'></path></svg>'">Supir / Driver</x-admin-nav-link>
                </div>
            </div>

            <!-- OPERASIONAL & LOGISTIK -->
            <div>
                <p class="px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Operasional & Logistik</p>
                <div class="space-y-1">
                    <x-admin-nav-link href="{{ route('admin.orders.index') }}" :active="request()->routeIs('admin.orders.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2\'></path></svg>'">Pesanan / Order</x-admin-nav-link>
                    <x-admin-nav-link href="{{ route('admin.shipments.index') }}" :active="request()->routeIs('admin.shipments.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0\'></path></svg>'">Pengiriman</x-admin-nav-link>
                </div>
            </div>

            <!-- LAPORAN & PENGATURAN -->
            <div>
                <p class="px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Laporan & Pengaturan</p>
                <div class="space-y-1">
                    <x-admin-nav-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z\'></path><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\'></path></svg>'">Pengaturan</x-admin-nav-link>
                </div>
            </div>
        @else
            <!-- UTAMA PELANGGAN -->
            <div>
                <p class="px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Utama</p>
                <div class="space-y-1">
                    <x-admin-nav-link href="{{ route('customer.dashboard') }}" :active="request()->routeIs('customer.dashboard')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6\'></path></svg>'">Dasbor</x-admin-nav-link>
                </div>
            </div>

            <!-- LOGISTIK SAYA -->
            <div>
                <p class="px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Logistik Saya</p>
                <div class="space-y-1">
                    <x-admin-nav-link href="{{ route('customer.orders.index') }}" :active="request()->routeIs('customer.orders.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2\'></path></svg>'">Pesanan Saya</x-admin-nav-link>
                    <x-admin-nav-link href="{{ route('customer.shipments.index') }}" :active="request()->routeIs('customer.shipments.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0\'></path></svg>'">Pengiriman</x-admin-nav-link>
                </div>
            </div>

            <!-- AKUN & PROFIL -->
            <div>
                <p class="px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Akun & Profil</p>
                <div class="space-y-1">
                    <x-admin-nav-link href="{{ route('customer.profile.edit') }}" :active="request()->routeIs('customer.profile.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4\'></path></svg>'">Profil Perusahaan</x-admin-nav-link>
                    <x-admin-nav-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.*')" :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\'></path></svg>'">Pengaturan Akun</x-admin-nav-link>
                </div>
            </div>
        @endif
    </nav>

    <!-- Bottom User Profile Card -->
    @auth
        <div class="p-3 border-t border-gray-100 shrink-0 bg-white">
            <div class="p-2.5 rounded-card bg-gray-50 flex items-center gap-3 border border-gray-100">
                <div class="w-9 h-9 rounded-full bg-primary/10 text-primary font-bold flex items-center justify-center text-sm border border-primary/20 shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                    <p class="text-[11px] text-gray-500 truncate capitalize">{{ $isAdmin ? 'Administrator' : ($user->customer->company_name ?? 'Customer') }}</p>
                </div>
            </div>
        </div>
    @endauth
</aside>
