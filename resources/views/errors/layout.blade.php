<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name', 'LogistikCRM') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased" style="background-color: #F5F1E6;">
    <div class="min-h-screen flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Logo Header -->
        <div class="mb-8 flex flex-col items-center">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-[#D6453D] rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">
                    LOGISTIK<span class="text-[#D6453D]">CRM</span>
                </h1>
            </div>
            <p class="text-sm text-gray-600 mt-2">Sistem Manajemen Logistik Terpadu</p>
        </div>

        <!-- Error Card -->
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl border border-gray-100 p-8 sm:p-10 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-50 text-[#D6453D] mb-6">
                @yield('icon')
            </div>

            <div class="text-5xl font-black text-[#D6453D] tracking-tight mb-2">
                @yield('code')
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-3">
                @yield('header')
            </h2>

            <p class="text-gray-600 text-sm leading-relaxed mb-8">
                @yield('message')
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}" 
                   class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D6453D] transition">
                    &larr; Kembali
                </a>
                <a href="{{ route('dashboard') }}" 
                   class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-[#D6453D] hover:bg-[#b83831] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D6453D] shadow-sm transition">
                    Ke Dashboard
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-xs text-gray-500">
            <p>&copy; {{ date('Y') }} LogistikCRM. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
