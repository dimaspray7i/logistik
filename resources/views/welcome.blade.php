<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Logistik CRM — Platform manajemen logistik terintegrasi untuk mengelola pelanggan, pesanan, pengiriman, armada kendaraan, supir, dan live tracking.">

        <title>Logistik CRM — Sistem Manajemen Logistik</title>

        <!-- Google Fonts: Inter & Poppins (Non-blocking async load) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap">
        <link rel="stylesheet" media="print" onload="this.media='all'" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap">
        <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap"></noscript>

        <!-- Styles / Scripts via Vite -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans antialiased bg-[#F5F1E6] text-gray-900 selection:bg-primary/20 selection:text-primary min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false }">

        <!-- ========================================================================= -->
        <!-- NAVBAR -->
        <!-- ========================================================================= -->
        <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-200/80 transition-all duration-200 shadow-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    
                    <!-- Brand Logo -->
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-white shadow-md shadow-primary/20 transition-transform group-hover:scale-105 duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <span class="font-poppins font-extrabold text-xl text-gray-900 tracking-tight">
                            LOGISTIK<span class="text-primary">CRM</span>
                        </span>
                    </a>

                    <!-- Desktop Navigation Links -->
                    <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
                        <a href="#beranda" class="hover:text-primary transition-colors py-1">Beranda</a>
                        <a href="#fitur" class="hover:text-primary transition-colors py-1">Fitur</a>
                        <a href="#cara-kerja" class="hover:text-primary transition-colors py-1">Cara Kerja</a>
                        <a href="#manfaat" class="hover:text-primary transition-colors py-1">Manfaat</a>
                        <a href="#kontak" class="hover:text-primary transition-colors py-1">Kontak</a>
                    </nav>

                    <!-- Auth Action Buttons (Desktop) -->
                    <div class="hidden md:flex items-center gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 rounded-btn text-sm font-semibold shadow-sm hover:shadow transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <span>Buka Dashboard</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary inline-flex items-center justify-center px-5 py-2.5 rounded-btn text-sm font-semibold shadow-sm hover:shadow transition-all">
                                Masuk ke Sistem
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Hamburger Button -->
                    <div class="flex md:hidden items-center">
                        <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Buka navigasi mobile" class="p-2 rounded-btn text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-primary/20">
                            <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                </div>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div x-show="mobileMenuOpen" x-cloak 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="md:hidden border-b border-gray-200 bg-white px-4 pt-2 pb-6 space-y-3 shadow-lg">
                <nav class="flex flex-col space-y-1">
                    <a href="#beranda" @click="mobileMenuOpen = false" class="px-3 py-2 rounded-btn text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary">Beranda</a>
                    <a href="#fitur" @click="mobileMenuOpen = false" class="px-3 py-2 rounded-btn text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary">Fitur</a>
                    <a href="#cara-kerja" @click="mobileMenuOpen = false" class="px-3 py-2 rounded-btn text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary">Cara Kerja</a>
                    <a href="#manfaat" @click="mobileMenuOpen = false" class="px-3 py-2 rounded-btn text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary">Manfaat</a>
                    <a href="#kontak" @click="mobileMenuOpen = false" class="px-3 py-2 rounded-btn text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary">Kontak</a>
                </nav>

                <div class="pt-4 border-t border-gray-100 flex flex-col gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary w-full text-center py-2.5 rounded-btn text-sm font-semibold">
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary w-full text-center py-2.5 rounded-btn text-sm font-semibold">
                            Masuk ke Sistem
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- ========================================================================= -->
        <!-- HERO SECTION -->
        <!-- ========================================================================= -->
        <section id="beranda" class="relative pt-12 pb-20 lg:pt-20 lg:pb-28 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                    
                    <!-- Left: Content -->
                    <div class="lg:col-span-7 text-center lg:text-left space-y-6">
                        
                        <!-- Pill Badge -->
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-badge bg-primary/10 border border-primary/20 text-primary text-xs font-semibold tracking-wide uppercase">
                            <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                            LOGISTIK MANAGEMENT SYSTEM
                        </div>

                        <!-- Main Title -->
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight leading-[1.15] font-poppins">
                            Kelola Logistik Lebih Mudah, <br class="hidden sm:inline" />
                            <span class="text-primary">Lebih Cepat</span>, & Terorganisir.
                        </h1>

                        <!-- Subtitle -->
                        <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                            Platform manajemen logistik untuk membantu mengelola pelanggan, pesanan, pengiriman, kendaraan, driver, dan tracking dalam satu sistem.
                        </p>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn-primary w-full sm:w-auto px-8 py-3.5 rounded-btn text-base font-semibold shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                                    <span>Buka Dashboard</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn-primary w-full sm:w-auto px-8 py-3.5 rounded-btn text-base font-semibold shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                                    <span>Masuk ke Sistem</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                </a>
                                <a href="#fitur" class="btn-secondary w-full sm:w-auto px-7 py-3.5 rounded-btn text-base font-semibold shadow-sm hover:shadow transition-all flex items-center justify-center gap-2">
                                    <span>Pelajari Fitur</span>
                                </a>
                            @endauth
                        </div>

                        <!-- Micro Highlights -->
                        <div class="pt-6 grid grid-cols-3 gap-4 border-t border-gray-200/80 max-w-lg mx-auto lg:mx-0">
                            <div>
                                <p class="text-xl sm:text-2xl font-bold text-gray-900 font-poppins">Real-time</p>
                                <p class="text-xs text-gray-500 font-medium mt-0.5">Tracking Status</p>
                            </div>
                            <div>
                                <p class="text-xl sm:text-2xl font-bold text-gray-900 font-poppins">Multi-Role</p>
                                <p class="text-xs text-gray-500 font-medium mt-0.5">Admin & Customer</p>
                            </div>
                            <div>
                                <p class="text-xl sm:text-2xl font-bold text-gray-900 font-poppins">Aman</p>
                                <p class="text-xs text-gray-500 font-medium mt-0.5">Dokumen Digital</p>
                            </div>
                        </div>

                    </div>

                    <!-- Right: Visual Card Preview -->
                    <div class="lg:col-span-5 w-full">
                        <div class="relative mx-auto max-w-md lg:max-w-none w-full">
                            
                            <!-- Main Decorative Card -->
                            <div class="crm-card bg-white p-6 sm:p-7 shadow-card border border-gray-100/80 relative z-10 space-y-5 rounded-card w-full">
                                
                                <!-- Card Header -->
                                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-gray-900 font-poppins">Status Pengiriman Terkini</h3>
                                            <p class="text-xs text-gray-500">Live Logistics Monitoring</p>
                                        </div>
                                    </div>
                                    <span class="badge-pill badge-in-transit">Dalam Pengiriman</span>
                                </div>

                                <!-- Mini Status Flow -->
                                <div class="space-y-3">
                                    <div class="p-3.5 bg-[#F5F1E6]/60 rounded-xl border border-gray-200/60 flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-semibold text-gray-800 truncate">Kargo Jakarta ke Surabaya</p>
                                                <p class="text-[11px] text-gray-500 truncate">Armada: Truk Tronton (B 9281 KXA)</p>
                                            </div>
                                        </div>
                                        <span class="text-xs font-bold text-gray-700 shrink-0 ml-2">85%</span>
                                    </div>

                                    <div class="p-3.5 bg-white rounded-xl border border-gray-100 shadow-2xs flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-semibold text-gray-800 truncate">POD & Bukti Timbang Selesai</p>
                                                <p class="text-[11px] text-gray-500 truncate">Arsip digital tersimpan otomatis</p>
                                            </div>
                                        </div>
                                        <span class="badge-pill badge-delivered shrink-0 ml-2">Tervalidasi</span>
                                    </div>
                                </div>

                                <!-- Mini Metrics in Card -->
                                <div class="grid grid-cols-2 gap-3 pt-1">
                                    <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                                        <span class="text-[11px] text-gray-500 font-medium">Armada & Supir</span>
                                        <p class="text-base font-bold text-gray-900 mt-0.5 font-poppins">Terkoneksi</p>
                                    </div>
                                    <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                                        <span class="text-[11px] text-gray-500 font-medium">Customer Portal</span>
                                        <p class="text-base font-bold text-gray-900 mt-0.5 font-poppins">24/7 Akses</p>
                                    </div>
                                </div>

                            </div>

                            <!-- Decorative background accent -->
                            <div class="absolute -top-4 -right-4 w-60 h-60 bg-primary/10 rounded-full filter blur-2xl -z-10 pointer-events-none"></div>
                            <div class="absolute -bottom-6 -left-6 w-60 h-60 bg-amber-500/10 rounded-full filter blur-2xl -z-10 pointer-events-none"></div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ========================================================================= -->
        <!-- FEATURE SECTION -->
        <!-- ========================================================================= -->
        <section id="fitur" class="py-20 bg-white border-y border-gray-200/60">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Section Header -->
                <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                    <span class="text-xs font-bold text-primary tracking-wider uppercase font-poppins">Fitur Sistem</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight font-poppins">
                        Dirancang untuk Menjawab Kebutuhan Logistik Modern
                    </h2>
                    <p class="text-base text-gray-600">
                        Kelola seluruh alur logistik dari penerimaan pesanan hingga konfirmasi tiba di tujuan secara terpadu.
                    </p>
                </div>

                <!-- Feature Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    
                    <!-- 1. Pelanggan -->
                    <div class="crm-card bg-white hover:shadow-card hover:-translate-y-1 transition-all duration-200 space-y-4 rounded-card">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 font-poppins">Manajemen Pelanggan</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Kelola data perusahaan pelanggan, kontak PIC, alamat penagihan, dan histori pemesanan secara terpusat dan rapi.
                        </p>
                    </div>

                    <!-- 2. Pesanan -->
                    <div class="crm-card bg-white hover:shadow-card hover:-translate-y-1 transition-all duration-200 space-y-4 rounded-card">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 font-poppins">Manajemen Pesanan</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Pencatatan order kargo terperinci dengan spesifikasi muatan, tarif, rute penjemputan, hingga status operasional.
                        </p>
                    </div>

                    <!-- 3. Pengiriman -->
                    <div class="crm-card bg-white hover:shadow-card hover:-translate-y-1 transition-all duration-200 space-y-4 rounded-card">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 font-poppins">Pengiriman & Manifest</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Alokasikan armada dan pengemudi pada setiap surat jalan pengiriman lengkap dengan nomor referensi dan jadwal.
                        </p>
                    </div>

                    <!-- 4. Live Tracking -->
                    <div class="crm-card bg-white hover:shadow-card hover:-translate-y-1 transition-all duration-200 space-y-4 rounded-card">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 font-poppins">Live Milestone Tracking</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Pantau pergerakan pengiriman berdasarkan update checkpoint lokasi, timestamp, dan catatan perjalanan driver.
                        </p>
                    </div>

                    <!-- 5. Kendaraan & Driver -->
                    <div class="crm-card bg-white hover:shadow-card hover:-translate-y-1 transition-all duration-200 space-y-4 rounded-card">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 font-poppins">Kendaraan & Driver</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Inventarisasi armada truk, kapasitas muatan, plat nomor, dokumen KIR/STNK, dan profil driver yang bertugas.
                        </p>
                    </div>

                    <!-- 6. Portal Pelanggan & Laporan -->
                    <div class="crm-card bg-white hover:shadow-card hover:-translate-y-1 transition-all duration-200 space-y-4 rounded-card">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 font-poppins">Customer Portal & POD</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Akses mandiri bagi customer untuk mengecek status pesanan mereka serta mengunduh berkas bukti terima (POD).
                        </p>
                    </div>

                </div>

            </div>
        </section>

        <!-- ========================================================================= -->
        <!-- HOW IT WORKS -->
        <!-- ========================================================================= -->
        <section id="cara-kerja" class="py-20 bg-[#F5F1E6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                    <span class="text-xs font-bold text-primary tracking-wider uppercase font-poppins">Alur Operasional</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight font-poppins">
                        Cara Kerja Operasional Logistik
                    </h2>
                    <p class="text-base text-gray-600">
                        Proses transparan dan terstruktur dari awal pemesanan hingga kargo sampai di tujuan.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- Step 1 -->
                    <div class="crm-card bg-white p-6 relative flex flex-col justify-between space-y-4 rounded-card">
                        <div class="space-y-3">
                            <span class="w-9 h-9 rounded-xl bg-primary text-white font-poppins font-bold text-sm flex items-center justify-center shadow-xs">
                                01
                            </span>
                            <h3 class="text-base font-bold text-gray-900 font-poppins">Buat Pesanan</h3>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Buat dan kelola pesanan kargo, rute asal, dan tujuan dari pelanggan ke dalam sistem.
                            </p>
                        </div>
                        <div class="pt-2 text-[11px] font-semibold text-primary">Langkah Awal</div>
                    </div>

                    <!-- Step 2 -->
                    <div class="crm-card bg-white p-6 relative flex flex-col justify-between space-y-4 rounded-card">
                        <div class="space-y-3">
                            <span class="w-9 h-9 rounded-xl bg-gray-900 text-white font-poppins font-bold text-sm flex items-center justify-center shadow-xs">
                                02
                            </span>
                            <h3 class="text-base font-bold text-gray-900 font-poppins">Proses Pengiriman</h3>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Atur proses operasional pengiriman, tentukan unit kendaraan, dan tetapkan supir yang bertugas.
                            </p>
                        </div>
                        <div class="pt-2 text-[11px] font-semibold text-gray-700">Penugasan Armada</div>
                    </div>

                    <!-- Step 3 -->
                    <div class="crm-card bg-white p-6 relative flex flex-col justify-between space-y-4 rounded-card">
                        <div class="space-y-3">
                            <span class="w-9 h-9 rounded-xl bg-blue-600 text-white font-poppins font-bold text-sm flex items-center justify-center shadow-xs">
                                03
                            </span>
                            <h3 class="text-base font-bold text-gray-900 font-poppins">Pantau Tracking</h3>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Pantau status pengiriman dan update checkpoint lokasi perjalanan kargo secara berkala.
                            </p>
                        </div>
                        <div class="pt-2 text-[11px] font-semibold text-blue-600">Monitoring Aktif</div>
                    </div>

                    <!-- Step 4 -->
                    <div class="crm-card bg-white p-6 relative flex flex-col justify-between space-y-4 rounded-card">
                        <div class="space-y-3">
                            <span class="w-9 h-9 rounded-xl bg-emerald-600 text-white font-poppins font-bold text-sm flex items-center justify-center shadow-xs">
                                04
                            </span>
                            <h3 class="text-base font-bold text-gray-900 font-poppins">Pesanan Selesai</h3>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Pastikan proses pengiriman selesai dan tercatat lengkap beserta bukti serah terima (POD).
                            </p>
                        </div>
                        <div class="pt-2 text-[11px] font-semibold text-emerald-600">Arsip Digital Selesai</div>
                    </div>

                </div>

            </div>
        </section>

        <!-- ========================================================================= -->
        <!-- BENEFIT SECTION -->
        <!-- ========================================================================= -->
        <section id="manfaat" class="py-20 bg-white border-y border-gray-200/60">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    
                    <div class="lg:col-span-5 space-y-4">
                        <span class="text-xs font-bold text-primary tracking-wider uppercase font-poppins">Keunggulan Sistem</span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight font-poppins">
                            Manfaat Nyata untuk Kelancaran Bisnis Logistik Anda
                        </h2>
                        <p class="text-base text-gray-600 leading-relaxed">
                            Meningkatkan efisiensi koordinasi antara admin operasional, armada pengiriman, dan klien bisnis melalui sistem informasi yang terpusat.
                        </p>
                    </div>

                    <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-5">
                        
                        <div class="p-5 rounded-2xl bg-[#F5F1E6]/60 border border-gray-200/70 space-y-2">
                            <div class="flex items-center gap-2 text-primary font-bold text-sm font-poppins">
                                <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                <span>Data Lebih Terorganisir</span>
                            </div>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Seluruh informasi pesanan, manifest kargo, dan dokumen tersimpan rapi tanpa dokumen tercecer.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-[#F5F1E6]/60 border border-gray-200/70 space-y-2">
                            <div class="flex items-center gap-2 text-primary font-bold text-sm font-poppins">
                                <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                <span>Monitoring Pengiriman Lebih Mudah</span>
                            </div>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Status pengiriman, nomor kendaraan, dan supir dapat dicek seketika oleh tim operasional.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-[#F5F1E6]/60 border border-gray-200/70 space-y-2">
                            <div class="flex items-center gap-2 text-primary font-bold text-sm font-poppins">
                                <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                <span>Informasi Pelanggan Terpusat</span>
                            </div>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Riwayat transaksi dan kontak PIC setiap klien tersimpan aman untuk memudahkan koordinasi.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-[#F5F1E6]/60 border border-gray-200/70 space-y-2">
                            <div class="flex items-center gap-2 text-primary font-bold text-sm font-poppins">
                                <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                <span>Operasional Lebih Efisien</span>
                            </div>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Memangkas waktu pembuatan surat jalan dan mempermudah verifikasi bukti pengiriman (POD).
                            </p>
                        </div>

                    </div>

                </div>

            </div>
        </section>

        <!-- ========================================================================= -->
        <!-- CTA SECTION -->
        <!-- ========================================================================= -->
        <section class="py-16 bg-[#F5F1E6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gray-900 rounded-card p-8 sm:p-12 text-white relative overflow-hidden shadow-card">
                    
                    <div class="max-w-2xl relative z-10 space-y-4 text-center sm:text-left">
                        <span class="badge-pill bg-primary text-white text-xs font-semibold px-3 py-1">
                            Akses Cepat Platform
                        </span>
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight font-poppins leading-snug">
                            Siap Mengelola Logistik dengan Lebih Terorganisir?
                        </h2>
                        <p class="text-sm sm:text-base text-gray-300 leading-relaxed">
                            Gunakan Logistik CRM sekarang untuk mengendalikan alur pengiriman dan memberikan transparansi terbaik bagi pelanggan Anda.
                        </p>
                        
                        <div class="pt-4 flex flex-col sm:flex-row items-center gap-4 justify-center sm:justify-start">
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn-primary w-full sm:w-auto px-8 py-3 rounded-btn text-sm font-semibold shadow-md">
                                    Buka Dasbor Utama
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn-primary w-full sm:w-auto px-8 py-3 rounded-btn text-sm font-semibold shadow-md">
                                    Masuk ke Sistem
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Subtle Decorative Glow -->
                    <div class="absolute -bottom-10 -right-10 w-80 h-80 bg-primary/20 rounded-full filter blur-3xl pointer-events-none"></div>

                </div>
            </div>
        </section>

        <!-- ========================================================================= -->
        <!-- FOOTER -->
        <!-- ========================================================================= -->
        <footer id="kontak" class="mt-auto bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                    
                    <!-- Col 1: Brand Info -->
                    <div class="md:col-span-5 space-y-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white shadow-xs">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            <span class="font-poppins font-bold text-lg text-gray-900 tracking-tight">LOGISTIK<span class="text-primary">CRM</span></span>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed max-w-sm">
                            Solusi sistem informasi manajemen logistik terpadu untuk efisiensi monitoring pengiriman armada dan kepuasan pelanggan.
                        </p>
                    </div>

                    <!-- Col 2: Navigation Links -->
                    <div class="md:col-span-4 space-y-3">
                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider font-poppins">Tautan Navigasi</h4>
                        <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                            <a href="#beranda" class="hover:text-primary transition-colors py-1">Beranda</a>
                            <a href="#fitur" class="hover:text-primary transition-colors py-1">Fitur</a>
                            <a href="#cara-kerja" class="hover:text-primary transition-colors py-1">Cara Kerja</a>
                            <a href="#manfaat" class="hover:text-primary transition-colors py-1">Manfaat</a>
                            <a href="#kontak" class="hover:text-primary transition-colors py-1">Kontak</a>
                            <a href="{{ route('login') }}" class="hover:text-primary transition-colors py-1 font-medium text-gray-900">Masuk</a>
                        </div>
                    </div>

                    <!-- Col 3: System Status / Portal -->
                    <div class="md:col-span-3 space-y-3">
                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider font-poppins">Akses Portal</h4>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Mendukung multi-akses aman untuk Administrator operasional & Customer Portal.
                        </p>
                        <div class="pt-1">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-badge bg-emerald-50 text-emerald-700 text-[11px] font-semibold border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Sistem Aktif & Siap Digunakan
                            </span>
                        </div>
                    </div>

                </div>

                <!-- Bottom Copyright -->
                <div class="mt-10 pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-4">
                    <p>&copy; {{ date('Y') }} Logistik CRM. Hak Cipta Dilindungi.</p>
                    <p class="text-gray-400">Dirancang untuk operasional kargo & ekspedisi terpercaya.</p>
                </div>
            </div>
        </footer>

    </body>
</html>
