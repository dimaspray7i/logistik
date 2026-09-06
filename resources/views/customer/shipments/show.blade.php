<x-customer-layout>
    <div class="space-y-5">

        <!-- Header Card -->
        <div class="bg-white rounded-[16px] border border-gray-100 shadow-soft px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('customer.shipments.index') }}" class="btn-secondary !p-2 shrink-0" title="Kembali ke Pengiriman">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-xl font-bold text-gray-900 tracking-tight leading-snug font-mono">{{ $shipment->display_code }}</h1>
                        @if ($shipment->isExternal())
                            <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                Ekspedisi: {{ $shipment->carrier_label }}
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                Armada Perusahaan
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $shipment->origin }} &rarr; {{ $shipment->destination }}</p>
                </div>
            </div>
            @php
                $statusBadge = match($shipment->status->value) {
                    'DRAFT' => 'badge-draft',
                    'READY' => 'badge-ready',
                    'IN_TRANSIT' => 'badge-in-transit',
                    'ARRIVED' => 'badge-arrived',
                    'DELIVERED' => 'badge-delivered',
                    'DELAYED' => 'badge-delayed',
                    'CANCELLED' => 'badge-cancelled',
                    default => 'badge-draft',
                };
                $progress = match($shipment->status->value) {
                    'DRAFT' => 10, 'READY' => 25, 'IN_TRANSIT' => 60, 'ARRIVED' => 85, 'DELIVERED' => 100, 'DELAYED' => 60, 'CANCELLED' => 0, default => 0,
                };
            @endphp
            <span class="badge-pill {{ $statusBadge }} !text-xs !px-3 !py-1 shrink-0">{{ $shipment->status->label() }}</span>
        </div>

        <!-- Info Pengiriman + Progress -->
        <div class="crm-card">
            <h3 class="font-poppins font-bold text-base text-gray-900 border-b border-gray-100 pb-3 mb-4">Informasi & Progress Pengiriman</h3>

            <!-- Progress Bar -->
            <div class="mb-5">
                <div class="flex justify-between text-xs font-semibold text-gray-500 mb-2">
                    <span>Kemajuan Pengiriman</span>
                    <span class="text-primary">{{ $progress }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                    <div class="bg-primary h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                </div>
                <!-- Step Labels -->
                <div class="flex justify-between text-[10px] text-gray-400 mt-1.5 font-medium">
                    <span>Draft</span><span>Siap</span><span>Transit</span><span>Tiba</span><span>Selesai</span>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 border-t border-gray-100 pt-4">
                @if ($shipment->isExternal())
                    <div class="p-3 bg-blue-50/70 rounded-btn border border-blue-100">
                        <p class="text-[11px] text-blue-700 font-medium">Jasa Pengiriman</p>
                        <p class="text-sm font-bold text-blue-900 mt-0.5">{{ $shipment->carrier_label }}</p>
                    </div>
                    <div class="p-3 bg-blue-50/70 rounded-btn border border-blue-100">
                        <p class="text-[11px] text-blue-700 font-medium">Nomor Resi</p>
                        <p class="text-sm font-bold text-blue-950 mt-0.5 font-mono">{{ $shipment->tracking_number }}</p>
                    </div>
                @else
                    <div class="p-3 bg-gray-50/80 rounded-btn border border-gray-100">
                        <p class="text-[11px] text-gray-500 font-medium">Jenis Pengiriman</p>
                        <p class="text-sm font-bold text-gray-900 mt-0.5">Armada Perusahaan</p>
                    </div>
                    <div class="p-3 bg-gray-50/80 rounded-btn border border-gray-100">
                        <p class="text-[11px] text-gray-500 font-medium">Kode Internal</p>
                        <p class="text-sm font-bold text-gray-900 mt-0.5 font-mono">{{ $shipment->shipment_number }}</p>
                    </div>
                @endif
                <div class="p-3 bg-gray-50/80 rounded-btn border border-gray-100">
                    <p class="text-[11px] text-gray-500 font-medium">Total Berat</p>
                    <p class="text-sm font-bold text-gray-900 mt-0.5">{{ number_format($shipment->total_weight, 0) }} Kg</p>
                </div>
                <div class="p-3 bg-gray-50/80 rounded-btn border border-gray-100">
                    <p class="text-[11px] text-gray-500 font-medium">Tanggal Berangkat</p>
                    <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $shipment->departure_date ? $shipment->departure_date->format('d M Y') : '-' }}</p>
                </div>
                <div class="p-3 bg-gray-50/80 rounded-btn border border-gray-100">
                    <p class="text-[11px] text-gray-500 font-medium">Estimasi Tiba</p>
                    <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y') : '-' }}</p>
                </div>
                <div class="p-3 bg-gray-50/80 rounded-btn border border-gray-100">
                    <p class="text-[11px] text-gray-500 font-medium">Tiba Aktual</p>
                    <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $shipment->actual_arrival ? $shipment->actual_arrival->format('d M Y') : 'Belum tiba' }}</p>
                </div>
                <div class="p-3 bg-gray-50/80 rounded-btn border border-gray-100">
                    <p class="text-[11px] text-gray-500 font-medium">Kendaraan</p>
                    <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $shipment->vehicle ? $shipment->vehicle->plate_number : '-' }}</p>
                </div>
                <div class="p-3 bg-gray-50/80 rounded-btn border border-gray-100">
                    <p class="text-[11px] text-gray-500 font-medium">Supir</p>
                    <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $shipment->driver->name ?? '-' }}</p>
                </div>
                @if($shipment->notes)
                    <div class="col-span-2 sm:col-span-3 p-3 bg-gray-50/80 rounded-btn border border-gray-100">
                        <p class="text-[11px] text-gray-500 font-medium">Catatan</p>
                        <p class="text-xs text-gray-700 mt-1">{{ $shipment->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pelacakan Pengiriman (Peta Interaktif & Timeline Perjalanan) -->
        <x-shipment-tracking :shipment="$shipment" :isAdmin="false" />

        <!-- Dokumen Pengiriman Card -->
        <div class="crm-card">
            <div class="border-b border-gray-100 pb-3 mb-4">
                <h3 class="font-poppins font-bold text-base text-gray-900">Dokumen Pengiriman</h3>
                <p class="text-xs text-gray-400">File dan surat resmi terkait pengiriman ini</p>
            </div>
            @if($shipment->documents->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($shipment->documents as $document)
                        <div class="flex items-center justify-between p-3 bg-gray-50/80 rounded-btn border border-gray-100 hover:border-primary/40 hover:bg-white transition group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-btn bg-primary/10 flex items-center justify-center shrink-0 border border-primary/10">
                                    @if(str_starts_with($document->mime_type, 'image/'))
                                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @else
                                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-gray-900 truncate">{{ $document->file_name }}</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">
                                        {{ is_object($document->type) && method_exists($document->type, 'label') ? $document->type->label() : $document->type }}
                                        &middot; {{ number_format(($document->file_size ?? 0) / 1024, 0) }} KB
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('documents.show', $document) }}" target="_blank" class="btn-ghost !text-xs !py-1 !px-2.5 text-primary shrink-0">
                                Lihat
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-400 text-xs">
                    Belum ada dokumen untuk pengiriman ini.
                </div>
            @endif
        </div>

        <!-- Informasi Pembayaran -->
        <div class="crm-card">
            <h3 class="font-poppins font-bold text-base text-gray-900 border-b border-gray-100 pb-3 mb-4">Informasi Pembayaran</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-3 bg-gray-50/80 rounded-btn border border-gray-100">
                    <p class="text-[11px] text-gray-500 font-medium">Status Pencairan Invoice</p>
                    <div class="mt-1.5">
                        @php
                            $pStatus = is_object($shipment->invoice_payment_status) ? $shipment->invoice_payment_status->value : ($shipment->invoice_payment_status ?? 'Belum Dibayar');
                        @endphp
                        @if($pStatus === 'Sudah Dibayar')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                Sudah Dibayar
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                Belum Dibayar
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-3 bg-gray-50/80 rounded-btn border border-gray-100">
                    <p class="text-[11px] text-gray-500 font-medium">Tanggal Pencairan</p>
                    <p class="text-sm font-bold text-gray-900 mt-1">
                        {{ $shipment->invoice_payment_date ? $shipment->invoice_payment_date->translatedFormat('d F Y') : '-' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Item Pengiriman Table -->
        <div class="crm-card !p-0 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-white">
                <h3 class="font-poppins font-bold text-base text-gray-900">Item Muatan Pengiriman</h3>
                <p class="text-xs text-gray-500">Daftar produk dan barang yang dibawa dalam pengiriman ini</p>
            </div>
            <div class="overflow-x-auto">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Unit</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shipment->items as $item)
                            <tr>
                                <td class="font-semibold text-gray-900">{{ $item->product->name ?? '-' }}</td>
                                <td class="text-xs text-gray-700 font-medium">{{ number_format($item->quantity, 0) }}</td>
                                <td class="text-xs text-gray-700 font-medium">{{ $item->unit }}</td>
                                <td class="text-xs text-gray-500">{{ $item->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-400 text-xs">
                                    Tidak ada item muatan untuk pengiriman ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-customer-layout>