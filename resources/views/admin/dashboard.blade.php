<x-app-layout>
    <div class="space-y-5">

        {{-- =============================================
             1. WELCOME HEADER — compact & clean
             ============================================= --}}
        <div class="bg-white rounded-[16px] border border-gray-100 shadow-soft px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight leading-snug">
                    Selamat datang kembali, {{ auth()->user()->name }} 👋
                </h1>
                <p class="text-xs text-gray-400 mt-0.5">
                    Berikut adalah ringkasan operasional dan aktivitas pengiriman Anda hari ini &middot;
                    <span class="font-medium text-gray-500">{{ now()->translatedFormat('l, d F Y') }}</span>
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <a href="{{ route('admin.shipments.create') }}" class="btn-primary !py-2 !text-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Pengiriman Baru</span>
                </a>
                <a href="{{ route('admin.orders.create') }}" class="btn-secondary !py-2 !text-xs">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Pesanan Baru</span>
                </a>
                <a href="{{ route('admin.customers.create') }}" class="btn-secondary !py-2 !text-xs">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    <span>Pelanggan</span>
                </a>
            </div>
        </div>

        {{-- =============================================
             2. KPI CARDS GRID — 6 compact cards in 1 row
             ============================================= --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">

            {{-- Card 1: Total Pelanggan --}}
            <a href="{{ route('admin.customers.index') }}" class="kpi-card group block">
                <p class="text-xs font-medium text-gray-500">Total Pelanggan</p>
                <p class="text-2xl font-bold text-gray-900 mt-1 mb-0.5">{{ number_format($stats['total_customers']) }}</p>
                <p class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1">
                    <span>+10.2%</span>
                    <span class="text-gray-400 font-normal truncate">terdaftar</span>
                </p>
            </a>

            {{-- Card 2: Total Pesanan --}}
            <a href="{{ route('admin.orders.index') }}" class="kpi-card group block">
                <p class="text-xs font-medium text-gray-500">Total Pesanan</p>
                <p class="text-2xl font-bold text-amber-500 mt-1 mb-0.5">{{ number_format($stats['total_orders']) }}</p>
                <p class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1">
                    <span>+1.8%</span>
                    <span class="text-gray-400 font-normal truncate">tercatat</span>
                </p>
            </a>

            {{-- Card 3: Total Pengiriman --}}
            <a href="{{ route('admin.shipments.index') }}" class="kpi-card group block">
                <p class="text-xs font-medium text-gray-500">Total Pengiriman</p>
                <p class="text-2xl font-bold text-primary mt-1 mb-0.5">{{ number_format($stats['total_shipments']) }}</p>
                <p class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1">
                    <span>+12%</span>
                    <span class="text-gray-400 font-normal truncate">bulan ini</span>
                </p>
            </a>

            {{-- Card 4: In Transit --}}
            <a href="{{ route('admin.shipments.index', ['status' => 'IN_TRANSIT']) }}" class="kpi-card group block">
                <p class="text-xs font-medium text-gray-500">In Transit</p>
                <p class="text-2xl font-bold text-info mt-1 mb-0.5">{{ number_format($stats['in_transit']) }}</p>
                <p class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1">
                    <span>+12%</span>
                    <span class="text-gray-400 font-normal truncate">berjalan</span>
                </p>
            </a>

            {{-- Card 5: Terkirim --}}
            <a href="{{ route('admin.shipments.index', ['status' => 'DELIVERED']) }}" class="kpi-card group block">
                <p class="text-xs font-medium text-gray-500">Terkirim</p>
                <p class="text-2xl font-bold text-success mt-1 mb-0.5">{{ number_format($stats['delivered']) }}</p>
                <p class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1">
                    <span>+12%</span>
                    <span class="text-gray-400 font-normal truncate">sukses</span>
                </p>
            </a>

            {{-- Card 6: Tertunda --}}
            <a href="{{ route('admin.shipments.index', ['status' => 'DELAYED']) }}" class="kpi-card group block">
                <p class="text-xs font-medium text-gray-500">Tertunda</p>
                <p class="text-2xl font-bold text-primary mt-1 mb-0.5">{{ number_format($stats['delayed']) }}</p>
                <p class="text-[11px] font-semibold text-red-500 flex items-center gap-1">
                    <span>+15.3%</span>
                    <span class="text-gray-400 font-normal truncate">perlu cek</span>
                </p>
            </a>

        </div>

        {{-- =============================================
             3. MAIN SECTION: RECENT SHIPMENTS + LIVE TRACKING
             ============================================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">

            {{-- RECENT SHIPMENTS TABLE (LG: 7 COLS) --}}
            <div class="lg:col-span-7">
                <div class="crm-card p-0 overflow-hidden">
                    {{-- Header --}}
                    <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h2 class="font-poppins font-bold text-sm text-gray-900">Pengiriman Terbaru</h2>
                            <p class="text-[11px] text-gray-400 mt-0.5">Aktivitas pengiriman logistik terkini</p>
                        </div>
                        <a href="{{ route('admin.shipments.index') }}"
                           class="text-[11px] font-semibold text-info hover:text-blue-700 flex items-center gap-1 transition-colors">
                            <span>Lihat Semua</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    {{-- Desktop & Tablet Table --}}
                    <div class="hidden sm:block overflow-x-auto">
                        <table class="w-full text-sm text-left border-collapse min-w-[480px]">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100">
                                    <th class="px-3.5 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-gray-400 w-[30%]">Pengiriman</th>
                                    <th class="px-3.5 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-gray-400 w-[28%]">Pelanggan</th>
                                    <th class="px-3.5 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-gray-400 w-[28%]">Rute</th>
                                    <th class="px-3.5 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-gray-400 w-[14%]">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($recentShipments as $shipment)
                                    <tr class="hover:bg-gray-50/80 transition-colors">
                                        <td class="px-3.5 py-2.5">
                                            <a href="{{ route('admin.shipments.show', $shipment) }}"
                                               class="text-xs font-bold text-gray-900 hover:text-primary transition-colors block truncate"
                                               title="{{ $shipment->shipment_number }}">
                                                {{ $shipment->shipment_number }}
                                            </a>
                                        </td>
                                        <td class="px-3.5 py-2.5 min-w-0">
                                            <span class="text-xs text-gray-700 font-medium block truncate"
                                                  title="{{ $shipment->customer->company_name ?? '-' }}">
                                                {{ $shipment->customer->company_name ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-3.5 py-2.5 min-w-0">
                                            <span class="text-xs text-gray-500 block truncate"
                                                  title="{{ $shipment->origin }} → {{ $shipment->destination }}">
                                                {{ $shipment->origin }} &rarr; {{ $shipment->destination }}
                                            </span>
                                        </td>
                                        <td class="px-3.5 py-2.5 whitespace-nowrap">
                                            <x-badge :status="$shipment->status" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center">
                                            <p class="text-xs text-gray-400 font-medium">Belum ada data pengiriman.</p>
                                            <a href="{{ route('admin.shipments.create') }}" class="btn-primary text-xs mt-2 inline-flex">
                                                + Buat Pengiriman
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile View (Compact List Card) --}}
                    <div class="block sm:hidden divide-y divide-gray-100">
                        @forelse ($recentShipments as $shipment)
                            <a href="{{ route('admin.shipments.show', $shipment) }}" class="p-3 block hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between gap-2 mb-1 min-w-0">
                                    <span class="text-xs font-bold text-gray-900 truncate">{{ $shipment->shipment_number }}</span>
                                    <x-badge :status="$shipment->status" />
                                </div>
                                <div class="flex items-center justify-between gap-2 min-w-0">
                                    <span class="text-xs font-medium text-gray-700 truncate flex-1">{{ $shipment->customer->company_name ?? '-' }}</span>
                                    <span class="text-xs text-gray-500 truncate max-w-[130px] shrink-0 text-right"
                                          title="{{ $shipment->origin }} → {{ $shipment->destination }}">
                                        {{ $shipment->origin }} &rarr; {{ $shipment->destination }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="p-6 text-center text-xs text-gray-400">Belum ada data pengiriman.</div>
                        @endforelse
                    </div>

                </div>
            </div>

            {{-- LIVE TRACKING TIMELINE (LG: 5 COLS) --}}
            <div class="lg:col-span-5">
                <div class="crm-card">
                    {{-- Header --}}
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100">
                        <div>
                            <h2 class="font-poppins font-bold text-sm text-gray-900">Live Tracking Update</h2>
                            <p class="text-[11px] text-gray-400 mt-0.5">Posisi &amp; status pengiriman aktif</p>
                        </div>
                        <span class="flex items-center gap-1.5 text-[10px] text-emerald-600 font-semibold shrink-0">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Live
                        </span>
                    </div>

                    {{-- Timeline List —- tinggi mengikuti konten secara natural --}}
                    <div class="space-y-3 overscroll-contain">
                        @if ($trackingUpdates->count() > 0)
                            <div class="relative pl-5 space-y-4 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200">
                                @foreach ($trackingUpdates->take(6) as $update)
                                    <div class="relative">
                                        <span class="absolute -left-5 top-1 w-3 h-3 rounded-full border-2 border-white bg-info shadow-xs"></span>
                                        <div class="flex items-start justify-between gap-2 min-w-0">
                                            <div class="min-w-0 flex-1">
                                                <p class="text-xs font-bold text-gray-900 truncate">{{ $update->location }}</p>
                                                <p class="text-[11px] text-gray-500 mt-0.5">
                                                    {{ $update->shipment->shipment_number ?? '-' }}
                                                </p>
                                                @if ($update->description)
                                                    <p class="text-[10px] text-gray-500 mt-0.5 bg-gray-50 p-1.5 rounded-btn border border-gray-100 break-words">{{ $update->description }}</p>
                                                @endif
                                            </div>
                                            <span class="text-[10px] text-gray-400 whitespace-nowrap shrink-0">
                                                {{ $update->tracked_at ? $update->tracked_at->format('d M H:i') : '-' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($trackingUpdates->count() > 6)
                                <p class="text-[11px] text-center text-gray-400 pt-1">
                                    +{{ $trackingUpdates->count() - 6 }} update lainnya &mdash;
                                    <a href="{{ route('admin.shipments.index') }}" class="text-info hover:underline">Lihat semua</a>
                                </p>
                            @endif
                        @elseif ($activeShipments->count() > 0)
                            <div class="space-y-2.5">
                                @foreach ($activeShipments->take(5) as $shipment)
                                    <div class="p-3 rounded-btn bg-gray-50 border border-gray-100 space-y-2 hover:border-blue-100 transition-colors">
                                        <div class="flex items-center justify-between gap-2 min-w-0">
                                            <span class="text-xs font-bold text-gray-900 truncate">{{ $shipment->shipment_number }}</span>
                                            <x-badge :status="$shipment->status" />
                                        </div>
                                        <p class="text-[11px] text-gray-500 truncate">
                                            <span class="font-medium text-gray-700">{{ $shipment->customer->company_name ?? '-' }}</span>
                                            &middot; <span>{{ $shipment->origin }} &rarr; {{ $shipment->destination }}</span>
                                        </p>
                                        <div class="flex items-center gap-2 pt-0.5 min-w-0">
                                            <span class="text-[10px] font-semibold text-info truncate max-w-[90px]">{{ $shipment->origin }}</span>
                                            <div class="flex-1 h-1 bg-blue-100 rounded-full overflow-hidden min-w-0">
                                                <div class="h-full bg-info rounded-full" style="width: 60%"></div>
                                            </div>
                                            <span class="text-[10px] text-gray-400 truncate max-w-[90px] text-right">{{ $shipment->destination }}</span>
                                        </div>
                                    </div>
                                @endforeach
                                @if ($activeShipments->count() > 5)
                                    <p class="text-[11px] text-center text-gray-400 pt-1">
                                        +{{ $activeShipments->count() - 5 }} pengiriman lainnya &mdash;
                                        <a href="{{ route('admin.shipments.index') }}" class="text-info hover:underline">Lihat semua</a>
                                    </p>
                                @endif
                            </div>
                        @else
                            <div class="py-8 text-center">
                                <svg class="w-8 h-8 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <p class="text-xs text-gray-400">Belum ada tracking aktif</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- =============================================
             4. SHIPMENT ANALYTICS — Smooth SVG Line Chart
             (With Legend, Y-Axis Scale, Hover Tooltips & Summary Stats)
             ============================================= --}}
        @php
            $chartData = collect($monthlyChartData);
            $counts = $chartData->pluck('count');
            $maxCount = max($counts->max(), 1);
            $avgCount = round($counts->avg(), 1);
            
            $highestItem = $chartData->sortByDesc('count')->first();
            $highestVal = $highestItem['count'] ?? 0;
            $highestMonth = $highestItem['label'] ?? '-';
            
            $latestCount = $chartData->last()['count'] ?? 0;
            $prevCount = $chartData->count() >= 2 ? $chartData->slice(-2, 1)->first()['count'] ?? 0 : 0;
            $trendDiff = $latestCount - $prevCount;
            
            // Y-Axis Steps
            $ySteps = [
                round($maxCount),
                round($maxCount * 0.75),
                round($maxCount * 0.5),
                round($maxCount * 0.25),
                0
            ];

            // Chart Dimensions
            $w = 640;
            $h = 130;
            $padTop = 15;
            $padBottom = 25;
            $usableH = $h - $padTop - $padBottom;
            
            $numPoints = count($chartData);
            $stepX = $numPoints > 1 ? $w / ($numPoints - 1) : $w;
            
            $ptArray = [];
            $svgPoints = [];
            foreach ($chartData as $i => $item) {
                $x = round($i * $stepX, 1);
                $y = round($h - $padBottom - (($item['count'] / $maxCount) * $usableH), 1);
                $ptArray[] = [
                    'x' => $x,
                    'y' => $y,
                    'label' => $item['label'],
                    'count' => $item['count']
                ];
                $svgPoints[] = "$x,$y";
            }
            $pointsStr = implode(' ', $svgPoints);
            $areaStr = "0,$h " . $pointsStr . " $w,$h";
        @endphp

        <div class="crm-card space-y-4" x-data="{ activeIndex: null, activeLabel: '', activeCount: 0, activeX: 0, activeY: 0 }">
            {{-- Header & Legend --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-gray-100 pb-3">
                <div>
                    <h2 class="font-poppins font-bold text-sm text-gray-900">Ringkasan Pengiriman 6 Bulan</h2>
                    <p class="text-[11px] text-gray-400 mt-0.5">Volume dan tren pengiriman barang 6 bulan terakhir</p>
                </div>
                {{-- Legend --}}
                <div class="flex items-center gap-2 bg-gray-50 px-2.5 py-1 rounded-btn border border-gray-100">
                    <span class="w-2.5 h-2.5 rounded-full bg-primary inline-block"></span>
                    <span class="text-xs font-semibold text-gray-700">Volume Pengiriman</span>
                </div>
            </div>

            {{-- Line Chart Container with Y-Axis Scale & Tooltip --}}
            <div class="relative pt-1">
                <div class="flex items-stretch gap-3 min-w-0">
                    {{-- Y-Axis Scale Labels --}}
                    <div class="flex flex-col justify-between text-[10px] font-semibold text-gray-400 text-right pr-1 select-none shrink-0" style="height: 130px;">
                        @foreach ($ySteps as $stepVal)
                            <span>{{ $stepVal }}</span>
                        @endforeach
                    </div>

                    {{-- SVG Canvas --}}
                    <div class="flex-1 relative min-w-0 overflow-hidden">
                        <svg viewBox="0 0 640 130" class="w-full h-32" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#D6453D" stop-opacity="0.2"/>
                                    <stop offset="100%" stop-color="#D6453D" stop-opacity="0.0"/>
                                </linearGradient>
                            </defs>

                            <!-- Horizontal Subtle Gridlines -->
                            @for ($g = 0; $g < 5; $g++)
                                @php
                                    $gridY = round($padTop + ($g * ($usableH / 4)), 1);
                                @endphp
                                <line x1="0" y1="{{ $gridY }}" x2="640" y2="{{ $gridY }}" stroke="#F3F4F6" stroke-width="1" stroke-dasharray="3 3" />
                            @endfor

                            <!-- Area Fill -->
                            <polygon points="{{ $areaStr }}" fill="url(#chartGradient)" />

                            <!-- Smooth Line Path -->
                            <polyline points="{{ $pointsStr }}" fill="none" stroke="#D6453D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />

                            <!-- Interactive Data Points -->
                            @foreach ($ptArray as $idx => $pt)
                                <g class="cursor-pointer group"
                                   @mouseenter="activeIndex = {{ $idx }}; activeLabel = '{{ $pt['label'] }}'; activeCount = {{ $pt['count'] }}; activeX = {{ $pt['x'] }}; activeY = {{ $pt['y'] }}"
                                   @mouseleave="activeIndex = null">
                                    <circle cx="{{ $pt['x'] }}" cy="{{ $pt['y'] }}" r="4.5" fill="#D6453D" stroke="#FFFFFF" stroke-width="2.5" class="transition-all group-hover:r-7" />
                                </g>
                            @endforeach
                        </svg>

                        <!-- Tooltip Popup -->
                        <div x-show="activeIndex !== null" x-cloak
                             class="absolute pointer-events-none bg-gray-900/90 text-white text-[11px] rounded-btn py-1.5 px-3 shadow-lg transform -translate-x-1/2 -translate-y-full transition-all duration-150 backdrop-blur-xs z-10"
                             :style="`left: ${(activeX / 640) * 100}%; top: ${activeY}px; margin-top: -8px;`">
                            <p class="font-bold text-xs" x-text="activeLabel"></p>
                            <p class="text-[10px] text-gray-300 mt-0.5">Volume: <span class="font-bold text-white" x-text="activeCount"></span> pengiriman</p>
                        </div>

                        <!-- X-Axis Month Labels -->
                        <div class="flex items-center justify-between pt-2 border-t border-gray-100 text-[11px] font-medium text-gray-400">
                            @foreach ($chartData as $data)
                                <span>{{ $data['label'] }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Summary Stats Footer (4 Mini Cards) --}}
            <div class="pt-3 border-t border-gray-100 grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-gray-50 rounded-btn p-2.5 border border-gray-100">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Total Pengiriman</p>
                    <p class="text-base font-bold text-gray-900 mt-0.5">{{ number_format($stats['total_shipments']) }}</p>
                    <p class="text-[10px] text-gray-500">Semua periode</p>
                </div>

                <div class="bg-gray-50 rounded-btn p-2.5 border border-gray-100">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Rata-rata</p>
                    <p class="text-base font-bold text-gray-900 mt-0.5">{{ $avgCount }}</p>
                    <p class="text-[10px] text-gray-500">Pengiriman / bulan</p>
                </div>

                <div class="bg-gray-50 rounded-btn p-2.5 border border-gray-100">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Tertinggi</p>
                    <p class="text-base font-bold text-gray-900 mt-0.5">{{ $highestVal }}</p>
                    <p class="text-[10px] text-gray-500 truncate" title="{{ $highestMonth }}">{{ $highestMonth }}</p>
                </div>

                <div class="bg-gray-50 rounded-btn p-2.5 border border-gray-100">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Tren Bulan Ini</p>
                    <div class="flex items-center gap-1 mt-0.5">
                        <span class="text-base font-bold {{ $trendDiff >= 0 ? 'text-emerald-600' : 'text-primary' }}">
                            {{ $trendDiff >= 0 ? '↑' : '↓' }} {{ abs($trendDiff) }}
                        </span>
                    </div>
                    <p class="text-[10px] text-gray-500">vs bulan sebelumnya</p>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>