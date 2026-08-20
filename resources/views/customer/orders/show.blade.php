<x-customer-layout>
    <div class="space-y-5">

        <!-- Header Card -->
        <div class="bg-white rounded-[16px] border border-gray-100 shadow-soft px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('customer.orders.index') }}" class="btn-secondary !p-2 shrink-0" title="Kembali ke Pesanan">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight leading-snug">
                        Detail Pesanan: {{ $order->order_number }}
                    </h1>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Dibuat pada {{ $order->order_date->format('d F Y') }}
                    </p>
                </div>
            </div>
            <div>
                @php
                    $badgeClass = match($order->status->value) {
                        'PENDING' => 'badge-pending',
                        'PROCESSING' => 'badge-in-transit',
                        'COMPLETED' => 'badge-delivered',
                        'CANCELLED' => 'badge-cancelled',
                        default => 'badge-draft',
                    };
                @endphp
                <span class="badge-pill {{ $badgeClass }} !text-xs !px-3 !py-1">
                    Status: {{ $order->status->label() }}
                </span>
            </div>
        </div>

        <!-- Ringkasan Info Pesanan -->
        <div class="crm-card">
            <h3 class="font-poppins font-bold text-base text-gray-900 border-b border-gray-100 pb-3 mb-4">
                Informasi & Ringkasan Pesanan
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-3 bg-gray-50/80 rounded-btn border border-gray-100">
                    <p class="text-[11px] text-gray-500 font-medium">Total Item</p>
                    <p class="text-base font-bold text-gray-900 mt-0.5">{{ $order->items_count }} item produk</p>
                </div>
                <div class="p-3 bg-gray-50/80 rounded-btn border border-gray-100">
                    <p class="text-[11px] text-gray-500 font-medium">Total Berat Fisik</p>
                    <p class="text-base font-bold text-gray-900 mt-0.5">{{ number_format($order->items->sum('weight'), 0) }} Kg</p>
                </div>
                <div class="p-3 bg-gray-50/80 rounded-btn border border-gray-100">
                    <p class="text-[11px] text-gray-500 font-medium">Pengiriman Terkait</p>
                    <p class="text-base font-bold text-gray-900 mt-0.5">{{ $order->shipments->count() }} pengiriman</p>
                </div>
                @if($order->notes)
                    <div class="sm:col-span-3 p-3 bg-gray-50/80 rounded-btn border border-gray-100">
                        <p class="text-[11px] text-gray-500 font-medium">Catatan Pesanan</p>
                        <p class="text-xs text-gray-700 mt-1">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Item Pesanan Table -->
        <div class="crm-card !p-0 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-white">
                <h3 class="font-poppins font-bold text-base text-gray-900">Item Pesanan</h3>
                <p class="text-xs text-gray-500">Rincian produk dan spesifikasi muatan dalam pesanan ini</p>
            </div>
            <div class="overflow-x-auto">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>SKU</th>
                            <th>Kuantitas</th>
                            <th>Berat (Kg)</th>
                            <th>Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->items as $item)
                            <tr>
                                <td class="font-semibold text-gray-900">{{ $item->product->name ?? '-' }}</td>
                                <td>
                                    <span class="badge-pill badge-in-transit font-mono text-[11px]">{{ $item->product->sku ?? '-' }}</span>
                                </td>
                                <td class="text-xs text-gray-700 font-medium">{{ $item->quantity }}</td>
                                <td class="text-xs text-gray-700">{{ number_format($item->weight, 2) }}</td>
                                <td class="text-xs text-gray-500">{{ $item->unit }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400 text-xs">Tidak ada item tercatat dalam pesanan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pengiriman Terkait Card -->
        <div class="crm-card">
            <div class="border-b border-gray-100 pb-3 mb-4">
                <h3 class="font-poppins font-bold text-base text-gray-900">Pengiriman Terkait</h3>
                <p class="text-xs text-gray-500">Surat jalan atau shipment logistik yang memuat pesanan ini</p>
            </div>

            @if($order->shipments->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($order->shipments as $shipment)
                        <a href="{{ route('customer.shipments.show', $shipment) }}" 
                           class="flex items-center justify-between p-4 bg-gray-50/80 rounded-btn border border-gray-100 hover:border-primary/40 hover:bg-white transition group">
                            <div>
                                <p class="text-sm font-bold text-gray-900 group-hover:text-primary transition">{{ $shipment->shipment_number }}</p>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <span>{{ $shipment->origin }}</span>
                                    <span class="text-gray-400">&rarr;</span>
                                    <span>{{ $shipment->destination }}</span>
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                @php
                                    $sBadge = match($shipment->status->value) {
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
                                <span class="badge-pill {{ $sBadge }}">{{ $shipment->status->label() }}</span>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-primary group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-gray-400 text-xs">
                    Belum ada jadwal pengiriman fisik yang terkait dengan pesanan ini.
                </div>
            @endif
        </div>

    </div>
</x-customer-layout>