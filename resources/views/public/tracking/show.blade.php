<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lacak Pengiriman {{ $shipment->tracking_number ?: $shipment->shipment_number }} — LogistikCRM</title>

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
                <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <span class="font-poppins font-extrabold text-base text-gray-900">LOGISTIK<span class="text-primary">CRM</span></span>
            </a>
            <a href="{{ url('/') }}" class="btn-ghost !text-xs">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </header>

    <main class="flex-1 max-w-4xl w-full mx-auto px-4 py-8 space-y-6">

        <!-- Tracking Header Card -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-md space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Hasil Pelacakan Resi</span>
                    <h1 class="text-2xl font-bold text-gray-900 font-mono mt-0.5">{{ $shipment->tracking_number ?: $shipment->shipment_number }}</h1>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                        {{ $shipment->carrier_label }}
                    </span>
                    <x-badge :status="$shipment->status" />
                </div>
            </div>

            <!-- Route & Info Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                <div>
                    <p class="text-gray-400 font-medium">Kota Asal</p>
                    <p class="font-bold text-gray-900 text-sm mt-0.5">{{ $shipment->origin }}</p>
                </div>
                <div>
                    <p class="text-gray-400 font-medium">Kota Tujuan</p>
                    <p class="font-bold text-gray-900 text-sm mt-0.5">{{ $shipment->destination }}</p>
                </div>
                <div>
                    <p class="text-gray-400 font-medium">Tanggal Berangkat</p>
                    <p class="font-semibold text-gray-800 mt-0.5">{{ $shipment->departure_date ? $shipment->departure_date->format('d M Y') : '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 font-medium">Estimasi Tiba</p>
                    <p class="font-semibold text-gray-800 mt-0.5">{{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y') : '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Tracking Timeline Component -->
        <x-shipment-tracking :shipment="$shipment" :isAdmin="false" />

    </main>

    <footer class="bg-white border-t border-gray-200 py-6 mt-12 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} Logistik CRM — Pelacakan Publik Ekspedisi Terintegrasi.
    </footer>

</body>
</html>
