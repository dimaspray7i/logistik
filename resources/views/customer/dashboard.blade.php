<x-customer-layout>
    <div class="space-y-5">

        {{-- =============================================
             1. WELCOME HEADER — compact & clean
             ============================================= --}}
        <div class="bg-white rounded-[16px] border border-gray-100 shadow-soft px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight leading-snug">
                    Selamat datang kembali, {{ $customer->company_name }} 👋
                </h1>
                <p class="text-xs text-gray-400 mt-0.5">
                    Berikut adalah ringkasan status pengiriman dan pesanan logistik Anda &middot;
                    <span class="font-medium text-gray-500">{{ now()->translatedFormat('l, d F Y') }}</span>
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <a href="{{ route('customer.shipments.index') }}" class="btn-primary !py-2 !text-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    <span>Pengiriman Saya</span>
                </a>
                <a href="{{ route('customer.orders.index') }}" class="btn-secondary !py-2 !text-xs">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Pesanan Saya</span>
                </a>
                <a href="{{ route('customer.profile.edit') }}" class="btn-secondary !py-2 !text-xs">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Profil Perusahaan</span>
                </a>
            </div>
        </div>

        {{-- =============================================
             2. KPI CARDS GRID — 5 compact cards
             ============================================= --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-3">
            {{-- Card 1: Total Pengiriman --}}
            <a href="{{ route('customer.shipments.index') }}" class="kpi-card group block">
                <p class="text-xs font-medium text-gray-500">Total Pengiriman</p>
                <p class="text-2xl font-bold text-primary mt-1 mb-0.5">{{ number_format($stats['my_shipments']) }}</p>
                <p class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1">
                    <span>{{ $stats['my_shipments'] > 0 ? 'Aktif terdata' : 'Belum ada' }}</span>
                </p>
            </a>

            {{-- Card 2: Total Pesanan --}}
            <a href="{{ route('customer.orders.index') }}" class="kpi-card group block">
                <p class="text-xs font-medium text-gray-500">Total Pesanan</p>
                <p class="text-2xl font-bold text-amber-500 mt-1 mb-0.5">{{ number_format($stats['my_orders']) }}</p>
                <p class="text-[11px] font-semibold text-gray-400 flex items-center gap-1">
                    <span>pesanan terdaftar</span>
                </p>
            </a>

            {{-- Card 3: Dalam Transit --}}
            <a href="{{ route('customer.shipments.index', ['status' => 'IN_TRANSIT']) }}" class="kpi-card group block">
                <p class="text-xs font-medium text-gray-500">Dalam Transit</p>
                <p class="text-2xl font-bold text-info mt-1 mb-0.5">{{ number_format($stats['in_transit']) }}</p>
                <p class="text-[11px] font-semibold text-blue-600 flex items-center gap-1">
                    <span>sedang bergerak</span>
                </p>
            </a>

            {{-- Card 4: Terkirim --}}
            <a href="{{ route('customer.shipments.index', ['status' => 'DELIVERED']) }}" class="kpi-card group block">
                <p class="text-xs font-medium text-gray-500">Terkirim</p>
                <p class="text-2xl font-bold text-success mt-1 mb-0.5">{{ number_format($stats['delivered']) }}</p>
                <p class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1">
                    <span>selesai diterima</span>
                </p>
            </a>

            {{-- Card 5: Tertunda --}}
            <a href="{{ route('customer.shipments.index', ['status' => 'DELAYED']) }}" class="kpi-card group block">
                <p class="text-xs font-medium text-gray-500">Tertunda</p>
                <p class="text-2xl font-bold text-primary mt-1 mb-0.5">{{ number_format($stats['delayed']) }}</p>
                <p class="text-[11px] font-semibold text-red-500 flex items-center gap-1">
                    <span>perlu perhatian</span>
                </p>
            </a>
        </div>

        {{-- =============================================
             3. LIVE TRACKING + GRAFIK RIWAYAT PENGIRIMAN
             ============================================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            <!-- Live Tracking Card -->
            <div class="crm-card lg:col-span-1 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                        <h3 class="font-poppins font-bold text-base text-gray-900 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                            Tracking Langsung
                        </h3>
                    </div>

                    @if($liveTracking)
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-gray-900">{{ $liveTracking->shipment_number }}</span>
                                <span class="badge-pill badge-in-transit">Dalam Perjalanan</span>
                            </div>

                            <div class="bg-gray-50/80 p-3 rounded-btn border border-gray-100 flex items-center justify-between text-xs">
                                <span class="font-medium text-gray-800 truncate max-w-[45%]">{{ $liveTracking->origin }}</span>
                                <svg class="w-4 h-4 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                <span class="font-medium text-gray-800 truncate max-w-[45%] text-right">{{ $liveTracking->destination }}</span>
                            </div>

                            @php
                                $progress = match($liveTracking->status->value) {
                                    'DRAFT' => 10, 'READY' => 25, 'IN_TRANSIT' => 60, 'ARRIVED' => 85, 'DELIVERED' => 100, default => 0,
                                };
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs text-gray-500 mb-1.5 font-medium">
                                    <span>Kemajuan Pengiriman</span>
                                    <span class="text-primary font-bold">{{ $progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                    <div class="bg-primary h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>

                            @if($liveTracking->estimated_arrival)
                                <p class="text-xs text-gray-500">
                                    Estimasi tiba: <span class="font-semibold text-gray-800">{{ $liveTracking->estimated_arrival->format('d M Y H:i') }}</span>
                                </p>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center mx-auto mb-3 text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-700">Tidak ada pengiriman aktif</p>
                            <p class="text-xs text-gray-400 mt-1">Seluruh pengiriman Anda telah selesai atau belum dimulai.</p>
                        </div>
                    @endif
                </div>

                @if($liveTracking)
                    <div class="pt-4 mt-4 border-t border-gray-100">
                        <a href="{{ route('customer.shipments.show', $liveTracking) }}" class="btn-primary w-full !text-xs !py-2.5">
                            <span>Lihat Detail Tracking</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Grafik Riwayat Pengiriman 6 Bulan -->
            <div class="crm-card lg:col-span-2 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start border-b border-gray-100 pb-3 mb-4">
                        <div>
                            <h3 class="font-poppins font-bold text-base text-gray-900">Riwayat Pengiriman</h3>
                            <p class="text-xs text-gray-500">Volume pengiriman Anda dalam 6 bulan terakhir</p>
                        </div>
                        <span class="text-xs font-semibold text-gray-400 bg-gray-50 px-2.5 py-1 rounded-btn border border-gray-100">6 Bulan Terakhir</span>
                    </div>

                    @if($monthlyChart['total'] > 0)
                        <!-- Chart SVG bar visualization -->
                        <div class="h-44 flex items-end justify-between gap-3 px-2 border-b border-gray-100 pb-3">
                            @php $maxVal = max(1, $monthlyChart['highest']); @endphp
                            @foreach($monthlyChart['data'] as $idx => $val)
                                @php $height = ($val / $maxVal) * 100; @endphp
                                <div class="flex-1 flex flex-col items-center gap-1.5 group relative">
                                    <div class="w-full bg-primary/85 rounded-t-md transition-all duration-200 group-hover:bg-primary group-hover:shadow-sm relative" style="height: {{ max($height, 6) }}%">
                                        <span class="absolute -top-7 left-1/2 -translate-x-1/2 text-[11px] font-bold text-gray-800 bg-white px-1.5 py-0.5 rounded shadow-xs border border-gray-100 opacity-0 group-hover:opacity-100 transition whitespace-nowrap z-10">{{ $val }} kirim</span>
                                    </div>
                                    <span class="text-[11px] font-medium text-gray-500">{{ \Illuminate\Support\Str::substr($monthlyChart['labels'][$idx], 0, 3) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="h-44 flex flex-col items-center justify-center text-center p-4 border border-dashed border-gray-200 rounded-btn bg-gray-50/50">
                            <p class="text-xs font-semibold text-gray-600">Belum ada data riwayat pengiriman</p>
                            <p class="text-[11px] text-gray-400 mt-1">Grafik volume pengiriman akan muncul secara otomatis setelah ada pengiriman yang dibuat.</p>
                        </div>
                    @endif
                </div>

                <!-- Statistik ringkasan di bawah chart -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4 pt-3 border-t border-gray-100 text-left">
                    <div class="p-2.5 rounded-btn bg-gray-50/80 border border-gray-100">
                        <p class="text-[11px] text-gray-500 font-medium">Total Volume</p>
                        <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $monthlyChart['total'] }} pengiriman</p>
                    </div>
                    <div class="p-2.5 rounded-btn bg-gray-50/80 border border-gray-100">
                        <p class="text-[11px] text-gray-500 font-medium">Rata-rata</p>
                        <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $monthlyChart['average'] }} / bln</p>
                    </div>
                    <div class="p-2.5 rounded-btn bg-gray-50/80 border border-gray-100">
                        <p class="text-[11px] text-gray-500 font-medium">Bulan Tertinggi</p>
                        <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $monthlyChart['highest'] }}</p>
                    </div>
                    <div class="p-2.5 rounded-btn bg-gray-50/80 border border-gray-100">
                        <p class="text-[11px] text-gray-500 font-medium">Tren Aktivitas</p>
                        <p class="text-sm font-bold {{ $monthlyChart['trend'] >= 0 ? 'text-emerald-600' : 'text-primary' }} mt-0.5">
                            {{ $monthlyChart['trend'] >= 0 ? '+' : '' }}{{ $monthlyChart['trend'] }}%
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- =============================================
             4. PENGIRIMAN TERBARU TABLE
             ============================================= --}}
        <div class="crm-card !p-0 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-white">
                <div>
                    <h3 class="font-poppins font-bold text-base text-gray-900">Pengiriman Terbaru</h3>
                    <p class="text-xs text-gray-500">Daftar transaksi logistik terkini akun Anda</p>
                </div>
                <a href="{{ route('customer.shipments.index') }}" class="btn-ghost !text-xs !py-1.5 !px-3">
                    <span>Lihat Semua</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>No. Pengiriman</th>
                            <th>Rute Pengiriman</th>
                            <th>Status</th>
                            <th>Estimasi Tiba</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentShipments as $shipment)
                            <tr>
                                <td class="font-semibold text-gray-900">
                                    {{ $shipment->shipment_number }}
                                </td>
                                <td class="text-gray-600">
                                    <span class="font-medium text-gray-900">{{ $shipment->origin }}</span>
                                    <span class="text-gray-400 mx-1">&rarr;</span>
                                    <span class="font-medium text-gray-900">{{ $shipment->destination }}</span>
                                </td>
                                <td>
                                    @php
                                        $badgeClass = match($shipment->status->value) {
                                            'DRAFT' => 'badge-draft',
                                            'READY' => 'badge-ready',
                                            'IN_TRANSIT' => 'badge-in-transit',
                                            'ARRIVED' => 'badge-arrived',
                                            'DELIVERED' => 'badge-delivered',
                                            'DELAYED' => 'badge-delayed',
                                            'CANCELLED' => 'badge-cancelled',
                                            default => 'badge-draft',
                                        };
                                    @endphp
                                    <span class="badge-pill {{ $badgeClass }}">
                                        {{ $shipment->status->label() }}
                                    </span>
                                </td>
                                <td class="text-gray-500 text-xs">
                                    {{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y H:i') : '-' }}
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('customer.shipments.show', $shipment) }}" class="btn-ghost !text-xs !py-1 !px-2 text-primary">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400 text-xs">
                                    Belum ada transaksi pengiriman. Pengiriman baru akan muncul di sini setelah diproses.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-customer-layout>