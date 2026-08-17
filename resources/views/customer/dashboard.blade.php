<x-app-layout>
    <x-slot name="header">
        {{ __('Customer Portal') }}
    </x-slot>

    <div class="py-6 space-y-6">

        {{-- ============================================================ --}}
        {{-- GREETING BANNER                                               --}}
        {{-- ============================================================ --}}
        <div class="relative overflow-hidden rounded-card shadow-soft"
             style="background: linear-gradient(135deg, #D6453D 0%, #b83530 50%, #8b2520 100%);">
            {{-- Decorative circles --}}
            <div class="absolute -top-10 -right-10 w-48 h-48 rounded-full opacity-10"
                 style="background: rgba(255,255,255,0.4);"></div>
            <div class="absolute -bottom-6 -left-6 w-32 h-32 rounded-full opacity-10"
                 style="background: rgba(255,255,255,0.3);"></div>

            <div class="relative px-6 py-7 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-red-100 text-sm font-medium mb-1">
                        {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    </p>
                    <h2 class="font-poppins text-2xl md:text-3xl font-bold text-white leading-tight">
                        Selamat datang kembali, {{ auth()->user()->name }}! 👋
                    </h2>
                    <p class="text-red-100 mt-1 text-sm">
                        @if ($customer->company_name)
                            <span class="font-semibold text-white">{{ $customer->company_name }}</span>
                            @if ($customer->city)
                                &middot; {{ $customer->city }}{{ $customer->province ? ', ' . $customer->province : '' }}
                            @endif
                        @else
                            Pantau status pesanan dan pengiriman Anda.
                        @endif
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    @if(Route::has('customer.shipments.index'))
                    <a href="{{ route('customer.shipments.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-btn bg-white text-primary text-sm font-semibold
                              hover:bg-red-50 transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Pengiriman Saya
                    </a>
                    @endif
                    @if(Route::has('customer.orders.index'))
                    <a href="{{ route('customer.orders.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-btn bg-white/20 text-white text-sm font-semibold
                              hover:bg-white/30 transition-colors border border-white/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Pesanan Saya
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- KPI CARDS — Data terisolasi milik customer ini                --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">

            {{-- Total Pesanan --}}
            <div class="crm-card p-5 flex flex-col gap-3 hover:shadow-card transition-shadow">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">Total Pesanan</p>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                         style="background: rgba(45,93,168,0.1);">
                        <svg class="w-4 h-4 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold font-poppins text-gray-900">{{ $stats['my_orders'] }}</p>
                <p class="text-xs text-muted">Pesanan tercatat</p>
            </div>

            {{-- Total Pengiriman --}}
            <div class="crm-card p-5 flex flex-col gap-3 hover:shadow-card transition-shadow">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">Pengiriman</p>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                         style="background: rgba(107,114,128,0.1);">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold font-poppins text-gray-900">{{ $stats['my_shipments'] }}</p>
                <p class="text-xs text-muted">Total pengiriman</p>
            </div>

            {{-- Dalam Perjalanan --}}
            <div class="crm-card p-5 flex flex-col gap-3 hover:shadow-card transition-shadow border-l-4 border-info">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">Dalam Perjalanan</p>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                         style="background: rgba(45,93,168,0.1);">
                        <svg class="w-4 h-4 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold font-poppins text-info">{{ $stats['in_transit'] }}</p>
                <p class="text-xs text-muted">Sedang dikirim</p>
            </div>

            {{-- Terkirim --}}
            <div class="crm-card p-5 flex flex-col gap-3 hover:shadow-card transition-shadow border-l-4 border-success">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">Terkirim</p>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                         style="background: rgba(22,143,75,0.1);">
                        <svg class="w-4 h-4 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold font-poppins text-success">{{ $stats['delivered'] }}</p>
                <p class="text-xs text-muted">Sukses terkirim</p>
            </div>

            {{-- Tertunda --}}
            <div class="crm-card p-5 flex flex-col gap-3 hover:shadow-card transition-shadow border-l-4 border-primary">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">Tertunda</p>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                         style="background: rgba(214,69,61,0.1);">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold font-poppins text-primary">{{ $stats['delayed'] }}</p>
                <p class="text-xs text-muted">Perlu penanganan</p>
            </div>

            {{-- Total Berat --}}
            <div class="crm-card p-5 flex flex-col gap-3 hover:shadow-card transition-shadow">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">Total Berat</p>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                         style="background: rgba(242,201,76,0.15);">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold font-poppins text-gray-900">
                    {{ number_format((float)$stats['total_weight'], 0, ',', '.') }}
                </p>
                <p class="text-xs text-muted">kilogram total</p>
            </div>

        </div>

        {{-- ============================================================ --}}
        {{-- ROW 1: Recent Shipments | Live Tracking                       --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- MY RECENT SHIPMENTS --}}
            <div class="lg:col-span-3 crm-card overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-poppins font-semibold text-gray-900">Pengiriman Terbaru Saya</h3>
                        <p class="text-xs text-muted mt-0.5">Data terisolasi untuk akun Anda</p>
                    </div>
                    @if(Route::has('customer.shipments.index'))
                    <a href="{{ route('customer.shipments.index') }}"
                       class="text-xs font-semibold text-primary hover:text-red-700 transition-colors">
                        Lihat Semua →
                    </a>
                    @endif
                </div>

                @if ($recentShipments->isEmpty())
                    <div class="flex-1 flex items-center justify-center p-8">
                        <x-empty-state
                            title="Belum ada pengiriman"
                            description="Belum ada pengiriman yang tercatat untuk akun Anda."
                            class="border-0 shadow-none py-8"
                        />
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="crm-table">
                            <thead>
                                <tr>
                                    <th>No. Pengiriman</th>
                                    <th>Rute</th>
                                    <th>Berat</th>
                                    <th>Status</th>
                                    <th>Estimasi Tiba</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentShipments as $shipment)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="font-mono text-sm font-semibold text-gray-900">
                                            {{ $shipment->shipment_number }}
                                        </td>
                                        <td class="text-sm text-gray-600">
                                            @if ($shipment->origin && $shipment->destination)
                                                <span class="flex items-center gap-1">
                                                    <span class="font-medium">{{ $shipment->origin }}</span>
                                                    <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none"
                                                         stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                    <span>{{ $shipment->destination }}</span>
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="text-sm text-gray-600">
                                            @if ($shipment->total_weight)
                                                {{ number_format((float)$shipment->total_weight, 0, ',', '.') }} kg
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <x-badge :status="$shipment->status" />
                                        </td>
                                        <td class="text-sm text-gray-500">
                                            {{ $shipment->estimated_arrival
                                                ? $shipment->estimated_arrival->format('d M Y')
                                                : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- LIVE TRACKING TIMELINE --}}
            <div class="lg:col-span-2 crm-card flex flex-col">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-poppins font-semibold text-gray-900">Tracking Terbaru</h3>
                        <p class="text-xs text-muted mt-0.5">Pembaruan pengiriman Anda</p>
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs text-success font-semibold">
                        <span class="w-2 h-2 rounded-full bg-success animate-pulse"></span>
                        Live
                    </span>
                </div>

                <div class="flex-1 px-6 py-4">
                    @if ($recentTracking->isEmpty())
                        {{-- Fallback: cek apakah ada shipment aktif --}}
                        @php
                            $activeShipment = $recentShipments->whereIn('status.value', ['IN_TRANSIT', 'READY'])->first();
                        @endphp

                        @if ($activeShipment)
                            {{-- Show active shipment as basic timeline --}}
                            <div class="space-y-4">
                                <p class="text-xs text-muted font-semibold uppercase tracking-wider mb-4">
                                    {{ $activeShipment->shipment_number }}
                                </p>
                                <div class="relative pl-6">
                                    {{-- Pickup --}}
                                    <div class="flex items-start gap-3 mb-5">
                                        <div class="absolute left-0 top-1 w-4 h-4 rounded-full border-2 border-success bg-success
                                                    flex items-center justify-center shrink-0">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                        </div>
                                        <div class="ml-2">
                                            <p class="text-sm font-semibold text-gray-900">Pickup</p>
                                            <p class="text-xs text-muted">{{ $activeShipment->origin ?? '-' }}</p>
                                            @if ($activeShipment->departure_date)
                                                <p class="text-xs text-muted mt-0.5">
                                                    {{ $activeShipment->departure_date->format('d M Y') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- Connector line --}}
                                    <div class="absolute left-1.5 top-5 w-0.5 h-8 bg-gray-200"></div>
                                    {{-- In Transit --}}
                                    <div class="flex items-start gap-3 mb-5">
                                        <div class="absolute left-0 top-14 w-4 h-4 rounded-full border-2 border-info bg-info
                                                    flex items-center justify-center shrink-0 animate-pulse">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                        </div>
                                        <div class="ml-2 mt-9">
                                            <p class="text-sm font-semibold text-info">Dalam Perjalanan</p>
                                            <p class="text-xs text-muted">Menuju tujuan</p>
                                        </div>
                                    </div>
                                    {{-- Connector line --}}
                                    <div class="absolute left-1.5 top-24 w-0.5 h-8 bg-gray-200"></div>
                                    {{-- Destination --}}
                                    <div class="flex items-start gap-3">
                                        <div class="absolute left-0 top-[130px] w-4 h-4 rounded-full border-2 border-gray-300 bg-gray-100
                                                    flex items-center justify-center shrink-0">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        </div>
                                        <div class="ml-2 mt-[76px]">
                                            <p class="text-sm font-semibold text-gray-400">Tujuan</p>
                                            <p class="text-xs text-muted">{{ $activeShipment->destination ?? '-' }}</p>
                                            @if ($activeShipment->estimated_arrival)
                                                <p class="text-xs text-muted mt-0.5">
                                                    Est. {{ $activeShipment->estimated_arrival->format('d M Y') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <x-empty-state
                                title="Belum ada pembaruan tracking"
                                description="Pembaruan tracking akan muncul saat pengiriman Anda diproses."
                                class="border-0 shadow-none py-6"
                            />
                        @endif
                    @else
                        {{-- Real tracking updates --}}
                        <div class="relative pl-5 space-y-0">
                            @foreach ($recentTracking as $index => $tracking)
                                @php
                                    $isLast  = $loop->last;
                                    $statusVal = $tracking->status?->value ?? 'DRAFT';
                                    $dotColor = match($statusVal) {
                                        'DELIVERED', 'ARRIVED' => 'bg-success border-success',
                                        'IN_TRANSIT'           => 'bg-info border-info',
                                        'DELAYED'              => 'bg-warning border-warning',
                                        'CANCELLED'            => 'bg-primary border-primary',
                                        default                => 'bg-gray-300 border-gray-300',
                                    };
                                    $textColor = match($statusVal) {
                                        'DELIVERED', 'ARRIVED' => 'text-success',
                                        'IN_TRANSIT'           => 'text-info',
                                        'DELAYED'              => 'text-yellow-600',
                                        'CANCELLED'            => 'text-primary',
                                        default                => 'text-gray-500',
                                    };
                                @endphp
                                <div class="flex gap-4 {{ $isLast ? '' : 'pb-5' }} relative">
                                    {{-- Vertical line --}}
                                    @if (!$isLast)
                                        <div class="absolute left-[7px] top-5 w-0.5 h-full bg-gray-200"></div>
                                    @endif
                                    {{-- Dot --}}
                                    <div class="shrink-0 mt-1 w-4 h-4 rounded-full border-2 {{ $dotColor }}
                                                flex items-center justify-center z-10">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                    </div>
                                    {{-- Content --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <div>
                                                <p class="text-xs font-mono text-muted">
                                                    {{ $tracking->shipment->shipment_number ?? '-' }}
                                                </p>
                                                <p class="text-sm font-semibold {{ $textColor }}">
                                                    {{ $tracking->status?->label() ?? '-' }}
                                                </p>
                                                @if ($tracking->location)
                                                    <p class="text-xs text-muted mt-0.5 truncate">
                                                        📍 {{ $tracking->location }}
                                                    </p>
                                                @endif
                                                @if ($tracking->description)
                                                    <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">
                                                        {{ $tracking->description }}
                                                    </p>
                                                @endif
                                            </div>
                                            <p class="text-xs text-muted shrink-0 whitespace-nowrap">
                                                {{ $tracking->tracked_at?->diffForHumans() ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- ============================================================ --}}
        {{-- ROW 2: My Recent Orders | Shipment Overview Chart             --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- MY RECENT ORDERS --}}
            <div class="lg:col-span-2 crm-card overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-poppins font-semibold text-gray-900">Pesanan Terbaru</h3>
                        <p class="text-xs text-muted mt-0.5">Pesanan milik Anda</p>
                    </div>
                    @if(Route::has('customer.orders.index'))
                    <a href="{{ route('customer.orders.index') }}"
                       class="text-xs font-semibold text-primary hover:text-red-700 transition-colors">
                        Lihat Semua →
                    </a>
                    @endif
                </div>

                @if ($recentOrders->isEmpty())
                    <div class="flex-1 flex items-center justify-center p-6">
                        <x-empty-state
                            title="Belum ada pesanan"
                            description="Pesanan Anda akan muncul di sini."
                            class="border-0 shadow-none py-6"
                        />
                    </div>
                @else
                    <div class="flex-1 divide-y divide-gray-100">
                        @foreach ($recentOrders as $order)
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-gray-900 font-mono">
                                        {{ $order->order_number }}
                                    </p>
                                    <p class="text-xs text-muted mt-0.5">
                                        {{ $order->order_date?->format('d M Y') ?? '-' }}
                                        @if ($order->items_count > 0)
                                            &middot; {{ $order->items_count }} item
                                        @endif
                                    </p>
                                </div>
                                <div class="ml-3 shrink-0">
                                    <x-badge :status="$order->status" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- SHIPMENT OVERVIEW CHART --}}
            <div class="lg:col-span-3 crm-card flex flex-col">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-poppins font-semibold text-gray-900">Pengiriman Saya — 6 Bulan Terakhir</h3>
                        <p class="text-xs text-muted mt-0.5">Volume pengiriman akun Anda</p>
                    </div>
                    @php $chartTotal = array_sum(array_column($monthlyData, 'count')); @endphp
                    <span class="text-xs font-semibold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-btn">
                        Total: {{ $chartTotal }}
                    </span>
                </div>

                <div class="flex-1 px-6 pb-6 pt-4">
                    @if ($chartTotal === 0)
                        <x-empty-state
                            title="Belum ada data pengiriman"
                            description="Chart akan muncul setelah ada pengiriman dalam 6 bulan terakhir."
                            class="border-0 shadow-none py-6"
                        />
                    @else
                        {{-- SVG Bar Chart --}}
                        <div class="flex items-end gap-2 h-36 w-full">
                            @foreach ($monthlyData as $data)
                                @php
                                    $barHeight = $chartMax > 0
                                        ? max(4, round(($data['count'] / $chartMax) * 100))
                                        : 4;
                                    $isCurrentMonth = (int)now()->format('n') === array_search($data, $monthlyData) + 1
                                        || ($data['month'] === now()->locale('id')->isoFormat('MMM')
                                            && $data['year'] == now()->year);
                                @endphp
                                <div class="flex-1 flex flex-col items-center gap-1.5 group">
                                    {{-- Count label --}}
                                    <span class="text-xs font-semibold text-gray-700 opacity-0 group-hover:opacity-100 transition-opacity">
                                        {{ $data['count'] }}
                                    </span>
                                    {{-- Bar --}}
                                    <div class="w-full relative flex items-end" style="height: 112px;">
                                        <div class="w-full rounded-t-lg transition-all duration-300
                                                    {{ $isCurrentMonth ? 'bg-primary' : 'bg-info/60 hover:bg-info' }}"
                                             style="height: {{ $barHeight }}%; min-height: 4px;">
                                        </div>
                                    </div>
                                    {{-- Month label --}}
                                    <span class="text-xs text-muted font-medium">{{ $data['month'] }}</span>
                                </div>
                            @endforeach
                        </div>
                        {{-- Legend --}}
                        <div class="flex items-center gap-4 mt-3 pt-3 border-t border-gray-100">
                            <span class="flex items-center gap-1.5 text-xs text-muted">
                                <span class="w-3 h-3 rounded bg-primary"></span>
                                Bulan ini
                            </span>
                            <span class="flex items-center gap-1.5 text-xs text-muted">
                                <span class="w-3 h-3 rounded bg-info/60"></span>
                                Bulan lalu
                            </span>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- ============================================================ --}}
        {{-- CUSTOMER PROFILE SUMMARY                                      --}}
        {{-- ============================================================ --}}
        <div class="crm-card p-6">
            <h3 class="font-poppins font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Profil Perusahaan
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs text-muted uppercase tracking-wider font-semibold mb-1">Perusahaan</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $customer->company_name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-muted uppercase tracking-wider font-semibold mb-1">PIC / Nama</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $customer->name ?? auth()->user()->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-muted uppercase tracking-wider font-semibold mb-1">Email</p>
                    <p class="text-sm text-gray-700">{{ $customer->email ?? auth()->user()->email }}</p>
                </div>
                <div>
                    <p class="text-xs text-muted uppercase tracking-wider font-semibold mb-1">Telepon</p>
                    <p class="text-sm text-gray-700">{{ $customer->phone ?? '-' }}</p>
                </div>
            </div>
            @if ($customer->address || $customer->city)
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <p class="text-xs text-muted uppercase tracking-wider font-semibold mb-1">Alamat</p>
                    <p class="text-sm text-gray-700">
                        {{ $customer->address ? $customer->address . ', ' : '' }}
                        {{ $customer->city }}{{ $customer->province ? ', ' . $customer->province : '' }}
                        {{ $customer->postal_code ? ' ' . $customer->postal_code : '' }}
                    </p>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>