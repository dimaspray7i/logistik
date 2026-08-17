<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Logistics CRM') }}</title>

        <!-- Google Fonts: Inter & Poppins -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

        <!-- Vite Assets -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-cream text-gray-900" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex w-full">
            
            <!-- Reusable Sidebar Component (Auto-detect role or fallback) -->
            <x-sidebar :role="auth()->check() && auth()->user()->isAdmin() ? 'admin' : 'customer'" />

            <!-- Main Area -->
            <div class="flex-1 flex flex-col min-w-0">
                
                <!-- Reusable Topbar Component -->
                <x-topbar />

                <!-- Session Flash Messages -->
                <div class="px-4 sm:px-6 lg:px-8 pt-4">
                    @if (session('success'))
                        <x-alert type="success" :message="session('success')" class="mb-2" />
                    @endif
                    @if (session('error'))
                        <x-alert type="error" :message="session('error')" class="mb-2" />
                    @endif
                    @if (session('warning'))
                        <x-alert type="warning" :message="session('warning')" class="mb-2" />
                    @endif
                    @if (session('info'))
                        <x-alert type="info" :message="session('info')" class="mb-2" />
                    @endif
                </div>

                <!-- Page Header Slot (Optional) -->
                @isset($header)
                    <div class="bg-white/60 backdrop-blur-xs border-b border-gray-100">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            @if (is_string($header))
                                <h2 class="font-poppins font-bold text-xl text-gray-900 leading-tight">
                                    {{ $header }}
                                </h2>
                            @else
                                {{ $header }}
                            @endif
                        </div>
                    </div>
                @endisset

                <!-- Main Content Area -->
                <main class="flex-1 p-4 sm:p-6 lg:p-8 w-full min-w-0">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>