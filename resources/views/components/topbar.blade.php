@props([
    'title' => null,
])

<header class="h-16 bg-white border-b border-gray-200/80 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-20 shadow-xs">
    <!-- Left: Mobile Menu Toggle & Title -->
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = !sidebarOpen" aria-label="Buka menu" class="lg:hidden p-2 rounded-btn text-gray-500 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>

        <!-- Mobile Logo & Brand Title -->
        <div class="lg:hidden flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white font-bold text-sm shadow-xs">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <span class="font-poppins font-bold text-base text-gray-900">Logistik<span class="text-primary">CRM</span></span>
        </div>
    </div>

    <!-- Center: Search Input Component (UI Placeholder) -->
    <div class="hidden md:flex flex-1 max-w-md mx-4">
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" placeholder="Cari pengiriman, pelanggan..." class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-btn text-xs sm:text-sm text-gray-900 placeholder:text-gray-400 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-150" />
        </div>
    </div>

    <!-- Right: Action Icons & Profile Dropdown -->
    <div class="flex items-center gap-2 sm:gap-3">
        <!-- Notification Icon (UI Indicator) -->
        <button type="button" aria-label="Notifikasi" class="relative p-2 rounded-btn text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition focus:outline-none focus:ring-2 focus:ring-gray-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-primary ring-2 ring-white"></span>
        </button>

        <!-- User Profile Dropdown Menu -->
        @auth
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open" aria-label="Menu profil" class="flex items-center gap-2.5 p-1 sm:px-3 sm:py-1.5 rounded-btn hover:bg-gray-100 transition focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <div class="w-8 h-8 rounded-full bg-primary/10 text-primary font-bold flex items-center justify-center text-sm border border-primary/20 shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-xs font-semibold text-gray-900 leading-tight truncate max-w-[120px]">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-gray-500 capitalize leading-tight">
                            {{ auth()->user()->isAdmin() ? 'Administrator' : (auth()->user()->customer->company_name ?? 'Customer') }}
                        </p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 hidden sm:block transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <!-- Dropdown Card -->
                <div x-cloak
                     x-show="open" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-52 bg-white rounded-card shadow-card border border-gray-100 py-1.5 z-50">
                    
                    <div class="px-4 py-2 border-b border-gray-100 sm:hidden">
                        <p class="text-xs font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-gray-500">{{ auth()->user()->email }}</p>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span>Profil Saya</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-xs text-primary hover:bg-red-50 font-medium transition text-left">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</header>
