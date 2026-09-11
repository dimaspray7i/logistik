@props([
    'shipment',
    'isAdmin' => false,
])

@php
    // Helper validasi koordinat numerik & batas wajar (-90 s/d 90, -180 s/d 180, bukan 0.0)
    $isValidCoord = function($lat, $lng) {
        if ($lat === null || $lng === null || $lat === '' || $lng === '') return false;
        if (!is_numeric($lat) || !is_numeric($lng)) return false;
        $latF = (float) $lat;
        $lngF = (float) $lng;
        if ($latF < -90 || $latF > 90 || $lngF < -180 || $lngF > 180) return false;
        if ($latF == 0 && $lngF == 0) return false;
        return true;
    };

    // 1. Ambil data tracking updates yang ada
    // $allTrackings = terurut kronologis (tracked_at ASC) untuk alur riwayat peta
    // $trackingsDesc = terurut terbaru di atas (tracked_at DESC) untuk tampilan timeline card
    $allTrackings = $shipment->trackingUpdates ? $shipment->trackingUpdates->sortBy('tracked_at')->values() : collect();
    $latestTracking = $allTrackings->last();
    $trackingsDesc = $shipment->trackingUpdates ? $shipment->trackingUpdates->sortByDesc('tracked_at')->values() : collect();

    // 2. Ambil data planned route points jika ada
    $routePoints = ($shipment->route && $shipment->route->points) 
        ? $shipment->route->points->sortBy('sequence')->values() 
        : collect();

    // 3. Dapatkan koordinat Titik Asal & Titik Tujuan (dari route_points)
    $originCoord = null;
    $destCoord = null;

    if ($routePoints->count() > 0) {
        $firstRp = $routePoints->first();
        if ($firstRp && $isValidCoord($firstRp->latitude, $firstRp->longitude)) {
            $originCoord = [
                'lat' => (float)$firstRp->latitude,
                'lng' => (float)$firstRp->longitude,
                'name' => $firstRp->location_name,
                'address' => $firstRp->address,
            ];
        }
        $lastRp = $routePoints->last();
        if ($lastRp && $isValidCoord($lastRp->latitude, $lastRp->longitude)) {
            $destCoord = [
                'lat' => (float)$lastRp->latitude,
                'lng' => (float)$lastRp->longitude,
                'name' => $lastRp->location_name,
                'address' => $lastRp->address,
                'eta' => $lastRp->estimated_arrival,
            ];
        }
    }

    // 4. Susun Planned Route Path & Traveled Path
    $plannedPath = [];
    $traveledPath = [];
    $mapMarkers = [];

    // A. Titik Asal
    if ($originCoord) {
        $plannedPath[] = [$originCoord['lat'], $originCoord['lng']];
        $traveledPath[] = [$originCoord['lat'], $originCoord['lng']];
        $mapMarkers[] = [
            'id' => 'origin-point',
            'lat' => $originCoord['lat'],
            'lng' => $originCoord['lng'],
            'type' => 'origin',
            'status_label' => 'Titik Asal (Origin)',
            'location' => $originCoord['name'],
            'address' => $originCoord['address'] ?? '',
            'description' => 'Titik keberangkatan pengiriman',
            'tracked_at' => $shipment->departure_date ? $shipment->departure_date->translatedFormat('d F Y • H:i') . ' WIB' : 'Titik Awal',
            'is_latest' => false,
        ];
    }

    // B. Titik Transit Terjadwal dari Planned Route
    if ($routePoints->count() > 2) {
        for ($i = 1; $i < $routePoints->count() - 1; $i++) {
            $rp = $routePoints[$i];
            if ($isValidCoord($rp->latitude, $rp->longitude)) {
                $latF = (float)$rp->latitude;
                $lngF = (float)$rp->longitude;
                $plannedPath[] = [$latF, $lngF];
                $mapMarkers[] = [
                    'id' => 'rp-' . $rp->id,
                    'lat' => $latF,
                    'lng' => $lngF,
                    'type' => 'transit',
                    'status_label' => 'Transit #' . $i,
                    'location' => $rp->location_name,
                    'address' => $rp->address ?? '',
                    'description' => $rp->estimated_arrival ? 'Estimasi Tiba: ' . $rp->estimated_arrival->translatedFormat('d F Y • H:i') . ' WIB' : 'Checkpoint Transit Terjadwal',
                    'tracked_at' => $rp->estimated_arrival ? $rp->estimated_arrival->translatedFormat('d F Y • H:i') . ' WIB' : 'Rute Terjadwal',
                    'is_latest' => false,
                ];
            }
        }
    }

    // C. Checkpoints dari Tracking Updates (Kronologis)
    $trackedCoordsCount = 0;
    foreach ($allTrackings as $t) {
        if ($isValidCoord($t->latitude, $t->longitude)) {
            $isLatest = ($latestTracking && $latestTracking->id === $t->id);
            $latF = (float) $t->latitude;
            $lngF = (float) $t->longitude;

            $statusVal = is_object($t->status) ? $t->status->value : $t->status;
            $statusLbl = is_object($t->status) ? $t->status->label() : $t->status;
            $statusClr = is_object($t->status) ? $t->status->color() : '#2563EB';

            $traveledPath[] = [$latF, $lngF];

            $mapMarkers[] = [
                'id' => 'track-' . $t->id,
                'lat' => $latF,
                'lng' => $lngF,
                'type' => $isLatest ? 'tracking_latest' : 'tracking',
                'status' => $statusVal,
                'status_label' => $statusLbl,
                'status_color' => $statusClr,
                'location' => $t->location,
                'address' => $t->address ?? '',
                'city' => $t->city ?? '',
                'province' => $t->province ?? '',
                'country' => $t->country ?? '',
                'description' => $t->description ?? '',
                'tracked_at' => $t->tracked_at ? $t->tracked_at->translatedFormat('d F Y • H:i') . ' WIB' : '-',
                'operator' => $t->user->name ?? 'Sistem',
                'is_latest' => $isLatest,
            ];
            $trackedCoordsCount++;
        }
    }

    // D. Titik Tujuan
    if ($destCoord) {
        $plannedPath[] = [$destCoord['lat'], $destCoord['lng']];
        $etaText = isset($destCoord['eta']) && $destCoord['eta'] 
            ? \Illuminate\Support\Carbon::parse($destCoord['eta'])->translatedFormat('d F Y') 
            : ($shipment->estimated_arrival ? $shipment->estimated_arrival->translatedFormat('d F Y') : null);

        $mapMarkers[] = [
            'id' => 'dest-point',
            'lat' => $destCoord['lat'],
            'lng' => $destCoord['lng'],
            'type' => 'destination',
            'status_label' => 'Titik Tujuan (Destination)',
            'location' => $destCoord['name'],
            'address' => $destCoord['address'] ?? '',
            'description' => $etaText ? 'Perkiraan Tiba: ' . $etaText : 'Tujuan Akhir Pengiriman',
            'tracked_at' => $etaText ? 'ETA: ' . $etaText : 'Tujuan Akhir',
            'is_latest' => false,
        ];
    }

    // Hitung total titik unik
    $uniqueMapCoords = [];
    foreach ($mapMarkers as $m) {
        $key = round($m['lat'], 4) . '_' . round($m['lng'], 4);
        $uniqueMapCoords[$key] = true;
    }
    $totalCoordinatedPoints = count($uniqueMapCoords);

    // Status Badge Logic
    $statusValue = is_object($shipment->status) ? $shipment->status->value : $shipment->status;
    $statusBadge = match($statusValue) {
        'DRAFT' => 'badge-draft',
        'READY' => 'badge-ready',
        'IN_TRANSIT' => 'badge-in-transit',
        'ARRIVED' => 'badge-arrived',
        'DELIVERED' => 'badge-delivered',
        'DELAYED' => 'badge-delayed',
        'CANCELLED' => 'badge-cancelled',
        default => 'badge-draft',
    };

    // Dynamic Progress Calculation
    $progressPercent = match($statusValue) {
        'DRAFT' => 15,
        'READY' => 35,
        'IN_TRANSIT' => 65,
        'ARRIVED' => 85,
        'DELIVERED' => 100,
        'DELAYED' => 65,
        'CANCELLED' => 0,
        default => 10,
    };

    // Lokasi & Waktu Terakhir
    $lastLocationName = $latestTracking ? $latestTracking->location : $shipment->origin;
    $lastUpdateFormatted = $latestTracking && $latestTracking->tracked_at 
        ? $latestTracking->tracked_at->translatedFormat('d F Y • H:i') . ' WIB'
        : ($shipment->updated_at ? $shipment->updated_at->translatedFormat('d F Y • H:i') . ' WIB' : '-');

    $mapElementId = 'tracking-map-' . $shipment->id;
    $dataElementId = 'tracking-data-' . $shipment->id;
@endphp

@once
    <!-- Leaflet CSS & JS Assets -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        .custom-radar-marker {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .radar-pulse {
            position: absolute;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background-color: #D6453D;
            opacity: 0.45;
            animation: radar-ping 1.8s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
        @keyframes radar-ping {
            75%, 100% {
                transform: scale(2.2);
                opacity: 0;
            }
        }
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            padding: 2px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
        .leaflet-popup-content {
            margin: 12px 14px;
            line-height: 1.4;
        }
        .timeline-item-active {
            border-color: #D6453D !important;
            box-shadow: 0 0 0 2px rgba(214, 69, 61, 0.2) !important;
            background-color: #FFFDFD !important;
        }
    </style>
@endonce

<div class="space-y-6">

    <!-- A. CARD UTAMA: PELACAKAN PENGIRIMAN -->
    <div class="crm-card space-y-6">

        <!-- 1. Header Pelacakan & Status Ringkas -->
        <div class="border-b border-gray-100 pb-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2.5">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-btn bg-primary/10 text-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs uppercase tracking-wider font-semibold text-gray-400">
                                    {{ $shipment->isExternal() ? 'Nomor Resi / Pelacakan' : 'Nomor Pengiriman' }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                    Ekspedisi: {{ $shipment->carrier_label }}
                                </span>
                            </div>
                            <h2 class="text-xl sm:text-2xl font-poppins font-bold text-gray-900 tracking-tight leading-none font-mono mt-0.5">
                                {{ $shipment->display_code }}
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-center">
                    <span class="badge-pill {{ $statusBadge }} !text-xs sm:!text-sm !px-3.5 !py-1 shadow-xs">
                        {{ is_object($shipment->status) ? $shipment->status->label() : $shipment->status }}
                    </span>
                </div>
            </div>

            @if ($shipment->isExternal())
                <div class="mt-4 p-3 bg-blue-50/70 border border-blue-100 rounded-btn flex items-start gap-2.5 text-xs text-blue-900">
                    <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <p class="font-semibold">Pengiriman Menggunakan Ekspedisi {{ $shipment->carrier_label }}</p>
                        <p class="text-blue-700 mt-0.5">Nomor Resi: <strong class="font-mono text-blue-950">{{ $shipment->tracking_number }}</strong>. Status & posisi pengiriman dapat dilacak juga melalui layanan resmi {{ $shipment->carrier_label }}.</p>
                    </div>
                </div>
            @endif

            <!-- Summary Chips -->
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 mt-5 pt-4 border-t border-gray-50 text-xs">
                <div class="p-3 bg-[#FAF8F5] rounded-btn border border-gray-100">
                    <span class="text-gray-400 block font-medium">Lokasi Terkini</span>
                    <span class="font-bold text-gray-900 mt-0.5 block truncate text-sm" title="{{ $lastLocationName }}">
                        {{ $lastLocationName }}
                    </span>
                </div>

                <div class="p-3 bg-[#FAF8F5] rounded-btn border border-gray-100">
                    <span class="text-gray-400 block font-medium">Update Terakhir</span>
                    <span class="font-bold text-gray-900 mt-0.5 block truncate text-sm" title="{{ $lastUpdateFormatted }}">
                        {{ $lastUpdateFormatted }}
                    </span>
                </div>

                <div class="p-3 bg-[#FAF8F5] rounded-btn border border-gray-100">
                    <span class="text-gray-400 block font-medium">Tujuan</span>
                    <span class="font-bold text-gray-900 mt-0.5 block truncate text-sm" title="{{ $shipment->destination }}">
                        {{ $shipment->destination }}
                    </span>
                </div>

                <div class="p-3 bg-[#FAF8F5] rounded-btn border border-gray-100">
                    <span class="text-gray-400 block font-medium">Perkiraan Tiba</span>
                    <span class="font-bold {{ $shipment->estimated_arrival ? 'text-primary' : 'text-gray-500' }} mt-0.5 block truncate text-sm">
                        {{ $shipment->estimated_arrival ? $shipment->estimated_arrival->translatedFormat('d F Y') : 'Belum ditentukan' }}
                    </span>
                </div>
            </div>

            <!-- Route Summary Breadcrumb -->
            <div class="mt-3.5 px-3 py-2 bg-white rounded-btn border border-gray-100 flex items-center gap-2 overflow-x-auto text-xs text-gray-600">
                <span class="font-semibold text-gray-400 shrink-0">Rute Perjalanan:</span>
                <button type="button" onclick="window.focusTrackingMapPoint_{{ $shipment->id }}('origin-point')" class="font-bold text-gray-900 hover:text-primary transition shrink-0 underline decoration-dotted">
                    {{ $shipment->origin }}
                </button>
                @foreach($allTrackings as $tr)
                    @if(strtolower(trim($tr->location)) !== strtolower(trim($shipment->origin)) && strtolower(trim($tr->location)) !== strtolower(trim($shipment->destination)))
                        <span class="text-gray-300 shrink-0">&rarr;</span>
                        <button type="button" onclick="window.focusTrackingMapPoint_{{ $shipment->id }}('track-{{ $tr->id }}')" class="font-medium text-gray-700 hover:text-primary transition shrink-0 underline decoration-dotted">
                            {{ $tr->location }}
                        </button>
                    @endif
                @endforeach
                <span class="text-gray-300 shrink-0">&rarr;</span>
                <button type="button" onclick="window.focusTrackingMapPoint_{{ $shipment->id }}('dest-point')" class="font-bold text-primary hover:underline transition shrink-0">
                    {{ $shipment->destination }}
                </button>
            </div>
        </div>

        <!-- 2. Progress Pengiriman Marketplace Style -->
        <div class="space-y-2">
            <div class="flex items-center justify-between text-xs font-semibold text-gray-600">
                <span>Progress Pengiriman</span>
                <span class="text-primary font-bold">{{ $progressPercent }}%</span>
            </div>

            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                <div class="bg-primary h-2.5 rounded-full transition-all duration-700" style="width: {{ $progressPercent }}%"></div>
            </div>

            <!-- Milestone Steps -->
            <div class="grid grid-cols-5 text-center text-[10px] sm:text-xs pt-1">
                @php
                    $steps = [
                        ['key' => 'DRAFT', 'label' => 'Pesanan Dibuat', 'min' => 15],
                        ['key' => 'READY', 'label' => 'Siap Dikirim', 'min' => 35],
                        ['key' => 'IN_TRANSIT', 'label' => 'Dalam Perjalanan', 'min' => 65],
                        ['key' => 'ARRIVED', 'label' => 'Tiba di Hub/Tujuan', 'min' => 85],
                        ['key' => 'DELIVERED', 'label' => 'Terkirim', 'min' => 100],
                    ];
                @endphp
                @foreach($steps as $step)
                    @php
                        $isPassed = $progressPercent >= $step['min'];
                        $isCurrent = false;
                        if ($step['key'] === 'DELIVERED' && $progressPercent === 100) $isCurrent = true;
                        elseif ($step['key'] === 'ARRIVED' && $progressPercent === 85) $isCurrent = true;
                        elseif ($step['key'] === 'IN_TRANSIT' && $progressPercent === 65) $isCurrent = true;
                        elseif ($step['key'] === 'READY' && $progressPercent === 35) $isCurrent = true;
                        elseif ($step['key'] === 'DRAFT' && $progressPercent === 15) $isCurrent = true;
                    @endphp
                    <div class="flex flex-col items-center">
                        <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full flex items-center justify-center mb-1 text-[10px] font-bold transition-all
                            {{ $isCurrent ? 'bg-primary text-white ring-4 ring-primary/20 shadow-xs' : ($isPassed ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400') }}">
                            @if($isPassed && !$isCurrent)
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @elseif($isCurrent)
                                <span class="w-2 h-2 rounded-full bg-white"></span>
                            @else
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                            @endif
                        </div>
                        <span class="hidden sm:block font-medium {{ $isCurrent ? 'text-primary font-bold' : ($isPassed ? 'text-gray-800' : 'text-gray-400') }}">
                            {{ $step['label'] }}
                        </span>
                        <span class="sm:hidden font-medium text-[9px] {{ $isCurrent ? 'text-primary font-bold' : ($isPassed ? 'text-gray-800' : 'text-gray-400') }}">
                            {{ Str::words($step['label'], 1, '') }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 3. Dual Layout: Peta Interaktif & Timeline Perjalanan -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 pt-3">

            <!-- LEFT / TOP: PETA INTERAKTIF (7 Kolom Desktop) -->
            <div class="md:col-span-7 flex flex-col">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <h3 class="font-poppins font-bold text-sm text-gray-900 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5-4V4l5 4m0 0l6-4 5 4v12l-5-4m-6 4V8m6 12V8"/></svg>
                            <span>Peta Keseluruhan Rute & Pelacakan</span>
                        </h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500 font-medium bg-gray-100 px-2 py-0.5 rounded-full">
                            {{ $totalCoordinatedPoints }} Titik Terkoordinat
                        </span>
                    </div>
                </div>

                <!-- Container Peta Leaflet -->
                <div class="relative w-full h-[320px] sm:h-[380px] md:h-[420px] lg:h-[480px] rounded-card border border-gray-200 overflow-hidden bg-gray-100 shadow-inner">
                    <div id="{{ $mapElementId }}" class="w-full h-full z-0"></div>

                    <!-- Quick Map Viewport Controls -->
                    <div class="absolute top-3 right-3 z-10 flex flex-col gap-1.5 shadow-sm">
                        <button type="button" onclick="window.fitAllTrackingRoute_{{ $shipment->id }}()" 
                                class="bg-white/95 hover:bg-white text-gray-800 text-[11px] font-semibold py-1 px-2.5 rounded-btn border border-gray-200 shadow-xs flex items-center gap-1 transition"
                                title="Kembalikan tampilan ke seluruh rute pengiriman">
                            <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                            <span>Lihat Semua Rute</span>
                        </button>
                        @if($latestTracking && $latestTracking->hasCoordinates())
                            <button type="button" onclick="window.focusTrackingMapPoint_{{ $shipment->id }}('track-{{ $latestTracking->id }}')" 
                                    class="bg-white/95 hover:bg-white text-gray-800 text-[11px] font-semibold py-1 px-2.5 rounded-btn border border-gray-200 shadow-xs flex items-center gap-1 transition"
                                    title="Arahkan kamera ke posisi tracking terkini">
                                <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                                <span>Posisi Terkini</span>
                            </button>
                        @endif
                    </div>

                    @if($totalCoordinatedPoints === 0)
                        <div class="absolute inset-0 bg-white/95 backdrop-blur-xs flex flex-col items-center justify-center p-6 text-center z-10">
                            <div class="w-12 h-12 rounded-full bg-red-50 text-primary flex items-center justify-center mb-3 border border-red-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <h4 class="font-poppins font-bold text-gray-900 text-sm mb-1">Rute Belum Memiliki Koordinat</h4>
                            <p class="text-xs text-gray-500 max-w-sm leading-relaxed">
                                Koordinat Belum Lengkap. Pengiriman ini belum memiliki koordinat titik asal, tujuan, maupun update pelacakan. Peta rute akan otomatis tampil setelah koordinat ditambahkan pada menu <strong>Kelola Rute</strong> atau formulir tracking.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Map Legend -->
                <div class="flex flex-wrap items-center justify-between gap-3 text-[11px] text-gray-500 mt-2 px-1">
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 border border-white shadow-xs"></span>
                            <span class="font-medium text-gray-700">Asal</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600 border border-white shadow-xs"></span>
                            <span class="font-medium text-gray-700">Checkpoint / Transit</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-primary border border-white shadow-xs"></span>
                            <span class="font-medium text-gray-700 font-bold">Posisi Terkini</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-600 border border-white shadow-xs"></span>
                            <span class="font-medium text-gray-700">Tujuan</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-4 h-1 bg-primary rounded-full"></span>
                            <span class="font-medium text-gray-700">Jalur Riwayat</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-4 h-0.5 border-b-2 border-dashed border-gray-400"></span>
                            <span class="font-medium text-gray-500">Rute Rencana</span>
                        </span>
                    </div>
                    <span class="text-gray-400">Peta otomatis menampilkan seluruh rute asal &rarr; tujuan.</span>
                </div>
            </div>

            <!-- RIGHT / BOTTOM: TIMELINE PERJALANAN (5 Kolom Desktop) -->
            <div class="md:col-span-5 flex flex-col">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-poppins font-bold text-sm text-gray-900 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Timeline Perjalanan</span>
                    </h3>
                    <span class="text-xs text-gray-400 font-medium">Klik checkpoint untuk fokus di peta</span>
                </div>

                <div class="p-4 bg-[#FAF8F5] rounded-card border border-gray-100 flex-1 overflow-y-auto max-h-[320px] sm:max-h-[400px] md:max-h-[480px]">
                    @php
                        $hasTrackingUpdates = $trackingsDesc->count() > 0;
                        $originHasGps = $originCoord !== null;
                    @endphp

                    <div class="relative pl-6 space-y-5 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200">
                        @if($hasTrackingUpdates)
                            @foreach($trackingsDesc as $tracking)
                                @php
                                    $isTopLatest = $loop->first;
                                    $dotBg = match($tracking->status->value) {
                                        'IN_TRANSIT' => 'bg-info',
                                        'ARRIVED', 'DELIVERED' => 'bg-success',
                                        'DELAYED' => 'bg-amber-500',
                                        'CANCELLED' => 'bg-red-500',
                                        default => 'bg-gray-400',
                                    };
                                    $hasGps = $tracking->hasCoordinates();
                                @endphp
                                <div class="relative group">
                                    <!-- Timeline Node Icon -->
                                    @if($isTopLatest)
                                        <div class="absolute -left-6 top-0 w-4 h-4 rounded-full bg-primary border-2 border-white shadow-sm flex items-center justify-center">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                        </div>
                                    @else
                                        <div class="absolute -left-6 top-1 w-3.5 h-3.5 rounded-full {{ $dotBg }} border-2 border-white shadow-xs flex items-center justify-center">
                                            @if(in_array($tracking->status->value, ['ARRIVED', 'DELIVERED']))
                                                <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Timeline Content Card -->
                                    <div id="timeline-card-{{ $tracking->id }}"
                                         @if($hasGps) onclick="window.focusTrackingMapPoint_{{ $shipment->id }}('track-{{ $tracking->id }}')" @endif
                                         class="bg-white p-3.5 rounded-btn border {{ $isTopLatest ? 'border-primary/40 ring-1 ring-primary/10 shadow-xs' : 'border-gray-100' }} {{ $hasGps ? 'cursor-pointer hover:border-primary/60 hover:shadow-xs transition' : '' }}">
                                        <div class="flex items-start justify-between gap-2">
                                            <div>
                                                <div class="flex flex-wrap items-center gap-1.5 mb-1">
                                                    <span class="font-bold text-xs text-gray-900 group-hover:text-primary transition">{{ $tracking->location }}</span>
                                                    @if($isTopLatest)
                                                        <span class="px-1.5 py-0.5 text-[10px] font-bold rounded bg-primary/10 text-primary uppercase">Posisi Terkini</span>
                                                    @endif
                                                </div>
                                                <div class="mb-1.5">
                                                    <x-badge :status="$tracking->status" />
                                                </div>
                                            </div>

                                            @if($isAdmin)
                                                <form action="{{ route('admin.shipments.tracking.destroy', [$shipment, $tracking]) }}" method="POST"
                                                      onclick="event.stopPropagation()"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat tracking di {{ addslashes($tracking->location) }}?');"
                                                      class="shrink-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1 text-gray-400 hover:text-primary transition rounded hover:bg-red-50" title="Hapus update tracking">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        @if($tracking->address || $tracking->city || $tracking->province || $tracking->country)
                                            <p class="text-[11px] text-gray-500 mb-1">
                                                {{ implode(', ', array_filter([$tracking->address, $tracking->city, $tracking->province, $tracking->country])) }}
                                            </p>
                                        @endif

                                        @if($tracking->clean_description)
                                            <p class="text-xs text-gray-700 bg-gray-50/80 p-2 rounded border border-gray-100 mb-2">
                                                {{ $tracking->clean_description }}
                                            </p>
                                        @endif

                                        <div class="flex flex-wrap items-center justify-between gap-1 text-[10px] text-gray-400 pt-1 border-t border-gray-50">
                                            <span>{{ $tracking->tracked_at ? $tracking->tracked_at->translatedFormat('d F Y • H:i') . ' WIB' : '-' }}</span>
                                            @if($hasGps)
                                                <span class="font-mono text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded flex items-center gap-1 group-hover:bg-primary/10 group-hover:text-primary transition" title="Klik untuk fokus pada peta">
                                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                                    <span>{{ number_format($tracking->latitude, 4) }}, {{ number_format($tracking->longitude, 4) }}</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <!-- Checkpoint Titik Asal / Pickup Milestone (Selalu Ditampilkan Sebagai Awal Perjalanan) -->
                        <div class="relative group">
                            <div class="absolute -left-6 top-1 w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white shadow-xs flex items-center justify-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                            </div>

                            <div id="timeline-card-origin"
                                 @if($originHasGps) onclick="window.focusTrackingMapPoint_{{ $shipment->id }}('origin-point')" @endif
                                 class="bg-white p-3.5 rounded-btn border {{ !$hasTrackingUpdates ? 'border-primary/40 ring-1 ring-primary/10 shadow-xs' : 'border-gray-100' }} {{ $originHasGps ? 'cursor-pointer hover:border-emerald-500/60 hover:shadow-xs transition' : '' }}">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-1.5 mb-1">
                                            <span class="font-bold text-xs text-gray-900 group-hover:text-emerald-600 transition">{{ $shipment->origin }}</span>
                                            <span class="px-1.5 py-0.5 text-[10px] font-bold rounded bg-emerald-50 text-emerald-700 uppercase">Asal Pengiriman</span>
                                            @if(!$hasTrackingUpdates)
                                                <span class="px-1.5 py-0.5 text-[10px] font-bold rounded bg-primary/10 text-primary uppercase">Posisi Terkini</span>
                                            @endif
                                        </div>
                                        <div class="mb-1.5">
                                            <x-badge :status="!$hasTrackingUpdates ? $shipment->status : 'READY'" />
                                        </div>
                                    </div>
                                </div>

                                @if(isset($originCoord['address']) && $originCoord['address'])
                                    <p class="text-[11px] text-gray-500 mb-1">
                                        {{ $originCoord['address'] }}
                                    </p>
                                @endif

                                <p class="text-xs text-gray-600 bg-gray-50/80 p-2 rounded border border-gray-100 mb-2">
                                    Pengiriman dijadwalkan dari {{ $shipment->origin }} menuju {{ $shipment->destination }}.
                                </p>

                                <div class="flex flex-wrap items-center justify-between gap-1 text-[10px] text-gray-400 pt-1 border-t border-gray-50">
                                    <span>{{ $shipment->departure_date ? $shipment->departure_date->translatedFormat('d F Y • H:i') . ' WIB' : ($shipment->created_at ? $shipment->created_at->translatedFormat('d F Y • H:i') . ' WIB' : 'Jadwal Awal') }}</span>
                                    @if($originHasGps)
                                        <span class="font-mono text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded flex items-center gap-1 group-hover:bg-emerald-50 group-hover:text-emerald-700 transition" title="Klik untuk fokus pada titik asal peta">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                            <span>{{ number_format($originCoord['lat'], 4) }}, {{ number_format($originCoord['lng'], 4) }}</span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- JSON Data untuk Peta Leaflet -->
<script id="{{ $dataElementId }}" type="application/json">
    {!! json_encode([
        'markers' => $mapMarkers,
        'plannedPath' => $plannedPath,
        'traveledPath' => $traveledPath,
        'tileUrl' => config('services.maps.tile_url', 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'),
        'attribution' => config('services.maps.attribution', '&copy; OpenStreetMap contributors'),
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!}
</script>

<!-- Script Inisialisasi Peta Leaflet dengan Viewport Rute Utuh & Interaksi Timeline -->
<script>
    (function () {
        var mapInstance = null;
        var markersDict = {};
        var allBounds = null;

        function initTrackingMap() {
            var mapContainer = document.getElementById('{{ $mapElementId }}');
            var dataElement = document.getElementById('{{ $dataElementId }}');
            if (!mapContainer || !dataElement) return;

            // Hindari inisialisasi ganda pada container yang sama
            if (mapContainer._leaflet_id) {
                return;
            }

            var mapData = JSON.parse(dataElement.textContent || '{}');
            var markers = mapData.markers || [];
            var plannedPath = mapData.plannedPath || [];
            var traveledPath = mapData.traveledPath || [];

            if (markers.length === 0) {
                // Tidak ada titik koordinat yang valid
                return;
            }

            // Inisialisasi Leaflet Map
            mapInstance = L.map('{{ $mapElementId }}', {
                zoomControl: true,
                scrollWheelZoom: false,
            });

            // Tambahkan Tile Layer OpenStreetMap
            L.tileLayer(mapData.tileUrl, {
                attribution: mapData.attribution,
                maxZoom: 19,
            }).addTo(mapInstance);

            var allCoords = [];
            markersDict = {};

            // 1. Gambar Planned Route Path (Garis Abu-Abu Putus-Putus Sebagai Konteks Rute Asal -> Tujuan)
            if (plannedPath.length > 1) {
                L.polyline(plannedPath, {
                    color: '#9CA3AF',
                    weight: 3,
                    dashArray: '6, 8',
                    opacity: 0.7,
                    smoothFactor: 1,
                }).addTo(mapInstance);
            }

            // 2. Gambar Traveled Path (Garis Merah Bold Menghubungkan Asal -> Checkpoint Tracking Sesuai Waktu)
            if (traveledPath.length > 1) {
                L.polyline(traveledPath, {
                    color: '#D6453D',
                    weight: 4,
                    opacity: 0.9,
                    smoothFactor: 1,
                }).addTo(mapInstance);
            }

            // 3. Render Marker untuk Setiap Titik (Asal, Transit, Tracking Checkpoints, Tujuan)
            markers.forEach(function (m) {
                var latLng = [m.lat, m.lng];
                allCoords.push(latLng);

                var iconHtml = '';
                var iconSize = [20, 20];
                var iconAnchor = [10, 10];
                var zIndex = 100;

                if (m.type === 'tracking_latest') {
                    // Posisi Terkini (Pulsing Radar Merah)
                    iconHtml = '<div class="custom-radar-marker">' +
                               '<div class="radar-pulse"></div>' +
                               '<div style="width:20px;height:20px;border-radius:50%;background-color:#D6453D;border:3px solid #ffffff;box-shadow:0 2px 8px rgba(0,0,0,0.35);position:relative;z-index:2;"></div>' +
                               '</div>';
                    iconSize = [34, 34];
                    iconAnchor = [17, 17];
                    zIndex = 1000;
                } else if (m.type === 'origin') {
                    // Titik Asal (Hijau Emerald)
                    iconHtml = '<div style="width:16px;height:16px;border-radius:50%;background-color:#10B981;border:2.5px solid #ffffff;box-shadow:0 2px 6px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;"><span style="width:5px;height:5px;border-radius:50%;background-color:#ffffff;"></span></div>';
                    iconSize = [16, 16];
                    iconAnchor = [8, 8];
                    zIndex = 500;
                } else if (m.type === 'destination') {
                    // Titik Tujuan (Merah)
                    iconHtml = '<div style="width:16px;height:16px;border-radius:50%;background-color:#DC2626;border:2.5px solid #ffffff;box-shadow:0 2px 6px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;"><span style="width:5px;height:5px;border-radius:50%;background-color:#ffffff;"></span></div>';
                    iconSize = [16, 16];
                    iconAnchor = [8, 8];
                    zIndex = 500;
                } else if (m.type === 'transit') {
                    // Transit Rute Terjadwal (Cyan / Biru Muda)
                    iconHtml = '<div style="width:14px;height:14px;border-radius:50%;background-color:#0284C7;border:2px solid #ffffff;box-shadow:0 2px 4px rgba(0,0,0,0.25);"></div>';
                    iconSize = [14, 14];
                    iconAnchor = [7, 7];
                    zIndex = 300;
                } else {
                    // Riwayat Tracking Checkpoint (Biru Indigo)
                    iconHtml = '<div style="width:14px;height:14px;border-radius:50%;background-color:#4F46E5;border:2px solid #ffffff;box-shadow:0 2px 4px rgba(0,0,0,0.25);"></div>';
                    iconSize = [14, 14];
                    iconAnchor = [7, 7];
                    zIndex = 400;
                }

                var customIcon = L.divIcon({
                    html: iconHtml,
                    className: '',
                    iconSize: iconSize,
                    iconAnchor: iconAnchor,
                    popupAnchor: [0, -10],
                });

                var badgeBg = '#DBEAFE;color:#1E40AF;';
                if (m.type === 'tracking_latest') {
                    badgeBg = '#FEE2E2;color:#991B1B;';
                } else if (m.type === 'origin') {
                    badgeBg = '#D1FAE5;color:#065F46;';
                } else if (m.type === 'destination') {
                    badgeBg = '#FEE2E2;color:#991B1B;';
                } else if (m.type === 'transit') {
                    badgeBg = '#E0F2FE;color:#0369A1;';
                }

                var popupContent = '<div style="min-width: 190px;">' +
                    '<div style="margin-bottom: 5px;">' +
                        '<span style="display:inline-block;padding:2px 8px;font-size:10px;font-weight:700;border-radius:9999px;background-color:' + badgeBg + '">' +
                            (m.is_latest ? '● Terkini: ' : '') + m.status_label +
                        '</span>' +
                    '</div>' +
                    '<div style="font-weight:700;font-size:13px;color:#111827;margin-bottom:2px;">' + m.location + '</div>';

                if (m.address || m.city) {
                    var fullAddr = [m.address, m.city, m.province, m.country].filter(Boolean).join(', ');
                    popupContent += '<div style="font-size:11px;color:#6B7280;margin-bottom:5px;">' + fullAddr + '</div>';
                }

                if (m.tracked_at) {
                    popupContent += '<div style="font-size:11px;color:#4B5563;margin-bottom:3px;">' +
                                    '<strong>Waktu:</strong> ' + m.tracked_at +
                                    '</div>';
                }

                if (m.description) {
                    popupContent += '<div style="font-size:11px;background-color:#F9FAFB;padding:4px 6px;border-radius:4px;border:1px solid #E5E7EB;margin-top:4px;color:#374151;">' +
                                    m.description +
                                    '</div>';
                }

                popupContent += '</div>';

                var marker = L.marker(latLng, { 
                    icon: customIcon, 
                    zIndexOffset: zIndex 
                }).addTo(mapInstance);

                marker.bindPopup(popupContent, { autoPan: false });
                markersDict[m.id] = marker;
            });

            // 4. Viewport: TAMPILKAN SELURUH RUTE (Asal -> Tracking -> Tujuan) Secara Otomatis
            if (allCoords.length > 1) {
                allBounds = L.latLngBounds(allCoords);
                mapInstance.fitBounds(allBounds, {
                    padding: [45, 45],
                    maxZoom: 14,
                });
            } else if (allCoords.length === 1) {
                allBounds = L.latLngBounds([allCoords[0], allCoords[0]]);
                mapInstance.setView(allCoords[0], 10);
            }

            // Invalidate size setelah render selesai
            setTimeout(function () {
                if (mapInstance) mapInstance.invalidateSize();
            }, 300);

            window.addEventListener('resize', function () {
                if (mapInstance) mapInstance.invalidateSize();
            });
        }

        // Fungsi Global untuk Navigasi & Fokus dari Timeline Item (Pan Ringan Tanpa Zoom Ekstrem)
        window.focusTrackingMapPoint_{{ $shipment->id }} = function (pointId) {
            if (!mapInstance || !markersDict[pointId]) return;

            var marker = markersDict[pointId];
            var latLng = marker.getLatLng();

            // Pan ringan menuju marker tanpa mengubah zoom level secara drastis
            mapInstance.panTo(latLng, {
                animate: true,
                duration: 0.8,
            });

            marker.openPopup();
        };

        // Fungsi Global untuk Mengembalikan Tampilan ke Seluruh Rute
        window.fitAllTrackingRoute_{{ $shipment->id }} = function () {
            if (!mapInstance || !allBounds) return;
            mapInstance.fitBounds(allBounds, {
                padding: [45, 45],
                maxZoom: 14,
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initTrackingMap);
        } else {
            initTrackingMap();
        }
    })();
</script>
