<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lacak Pengiriman Ekspedisi — LogistikCRM</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-[#F5F1E6] text-gray-900 min-h-screen flex flex-col font-sans">

    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white font-bold shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <span class="font-poppins font-extrabold text-base text-gray-900">LOGISTIK<span class="text-primary">CRM</span></span>
            </a>
            <a href="{{ url('/') }}" class="btn-ghost !text-xs">
                &larr; Beranda
            </a>
        </div>
    </header>

    <main class="flex-1 max-w-4xl w-full mx-auto px-4 py-12 space-y-8">

        <!-- Header Hero Box -->
        <div class="text-center space-y-3">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary border border-primary/20 uppercase tracking-wider">
                Public Tracking Portal
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 font-poppins tracking-tight">
                Lacak Status Pengiriman Kargo
            </h1>
            <p class="text-sm text-gray-600 max-w-xl mx-auto">
                Masukkan nomor resi ekspedisi untuk memantau perjalanan dan status pengiriman secara real-time.
            </p>
        </div>

        <!-- Not Found Alert -->
        @if (isset($notFound) && $notFound)
            <div class="p-4 rounded-card bg-red-50 border border-red-200 text-red-800 text-sm flex items-start gap-3 shadow-xs">
                <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div>
                    <p class="font-bold text-red-900">Nomor Resi Tidak Ditemukan</p>
                    <p class="text-xs text-red-700 mt-0.5">Data pengiriman dengan resi <strong class="font-mono text-red-950">{{ $searchedNumber ?? '' }}</strong> tidak ditemukan dalam sistem. Mohon periksa kembali nomor resi Anda.</p>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-md space-y-6">
            <form action="{{ route('tracking.search') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="tracking_number" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Nomor Resi Pengiriman <span class="text-primary">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input id="tracking_number" type="text" name="tracking_number"
                               value="{{ old('tracking_number', $searchedNumber ?? '') }}"
                               placeholder="Contoh: AEI123456789" required
                               class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-base font-mono text-gray-900 focus:ring-2 focus:ring-primary focus:border-primary uppercase placeholder:normal-case @error('tracking_number') border-red-500 @enderror">
                    </div>
                    @error('tracking_number')
                        <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary w-full py-3 text-sm font-semibold rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>Lacak Pengiriman</span>
                </button>
            </form>

            <div class="pt-4 border-t border-gray-100 space-y-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Mitra Ekspedisi Terintegrasi:</p>
                <div class="flex flex-wrap gap-2 text-xs">
                    <span class="px-2.5 py-1 rounded-lg bg-red-50 text-red-700 font-bold border border-red-100">AEI — PT. Antar Exprindo Indah</span>
                    <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold">JNE</span>
                    <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold">J&T Express</span>
                    <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold">SiCepat</span>
                    <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold">Pos Indonesia</span>
                </div>
            </div>
        </div>

    </main>

    <footer class="bg-white border-t border-gray-200 py-6 mt-12 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} Logistik CRM — Pelacakan Ekspedisi Eksternal Terintegrasi.
    </footer>

</body>
</html>
