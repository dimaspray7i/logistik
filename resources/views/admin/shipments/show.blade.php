<x-app-layout>
    <div class="space-y-6">

        <!-- Header Actions & Navigation -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <a href="{{ route('admin.shipments.index') }}" class="btn-ghost text-xs self-start">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali ke Daftar Pengiriman</span>
            </a>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.shipments.route.edit', $shipment) }}" class="btn-secondary">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5-4V4l5 4m0 0l6-4 5 4v12l-5-4m-6 4V8m6 12V8"></path></svg>
                    <span>Kelola Rute</span>
                </a>
                <a href="{{ route('admin.shipments.edit', $shipment) }}" class="btn-secondary">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Ubah Data</span>
                </a>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $shipment->shipment_number }}</h1>
                    <x-badge :status="$shipment->status" />
                </div>
                <p class="text-sm text-gray-500 mt-1 font-normal">
                    Rute: <span class="font-semibold text-gray-800">{{ $shipment->origin }} &rarr; {{ $shipment->destination }}</span>
                </p>
            </div>
        </div>

        <!-- 1. Informasi Utama Pengiriman Card -->
        <div class="crm-card space-y-4">
            <div class="border-b border-gray-100 pb-3">
                <h2 class="font-poppins font-bold text-base text-gray-900">Informasi Pengiriman</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs sm:text-sm">
                <div>
                    <p class="text-gray-400 font-medium">Pelanggan</p>
                    <a href="{{ route('admin.customers.show', $shipment->customer_id) }}" class="font-bold text-info hover:underline mt-0.5 block">
                        {{ $shipment->customer->company_name ?? '-' }}
                    </a>
                </div>
                <div>
                    <p class="text-gray-400 font-medium">Nomor Pesanan</p>
                    <a href="{{ route('admin.orders.show', $shipment->order_id) }}" class="font-bold text-info hover:underline mt-0.5 block">
                        {{ $shipment->order->order_number ?? '-' }}
                    </a>
                </div>
                <div>
                    <p class="text-gray-400 font-medium">Total Berat</p>
                    <p class="font-semibold text-gray-900 mt-0.5">{{ number_format($shipment->total_weight, 0) }} Kg</p>
                </div>
                <div>
                    <p class="text-gray-400 font-medium">Kendaraan</p>
                    <p class="font-semibold text-gray-900 mt-0.5">
                        {{ $shipment->vehicle ? $shipment->vehicle->plate_number . ' (' . $shipment->vehicle->vehicle_type . ')' : 'Belum diassign' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-400 font-medium">Supir / Driver</p>
                    <p class="font-semibold text-gray-900 mt-0.5">{{ $shipment->driver->name ?? 'Belum diassign' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 font-medium">Tanggal Berangkat</p>
                    <p class="font-semibold text-gray-900 mt-0.5">{{ $shipment->departure_date ? $shipment->departure_date->format('d M Y H:i') : '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 font-medium">Estimasi Tiba</p>
                    <p class="font-semibold text-gray-900 mt-0.5">{{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y H:i') : '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 font-medium">Tiba Aktual</p>
                    <p class="font-semibold text-gray-900 mt-0.5">{{ $shipment->actual_arrival ? $shipment->actual_arrival->format('d M Y H:i') : '-' }}</p>
                </div>
            </div>

            @if ($shipment->notes)
                <div class="pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-400 font-medium">Catatan</p>
                    <p class="text-xs text-gray-700 mt-1 bg-gray-50 p-2.5 rounded-btn border border-gray-100">{{ $shipment->notes }}</p>
                </div>
            @endif
        </div>

        <!-- 2. Informasi Pembayaran Card -->
        <div class="crm-card space-y-4">
            <div class="border-b border-gray-100 pb-3">
                <h2 class="font-poppins font-bold text-base text-gray-900">Informasi Pembayaran</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm">
                <div>
                    <p class="text-gray-400 font-medium">Status Pencairan Invoice</p>
                    <div class="mt-1.5">
                        @php
                            $pStatus = is_object($shipment->invoice_payment_status) ? $shipment->invoice_payment_status->value : ($shipment->invoice_payment_status ?? 'Belum Dibayar');
                        @endphp
                        @if($pStatus === 'Sudah Dibayar')
                            <span class="inline-flex items-center px-3 py-1 rounded-badge text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                Sudah Dibayar
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-badge text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                Belum Dibayar
                            </span>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-gray-400 font-medium">Tanggal Pencairan</p>
                    <p class="font-semibold text-gray-900 mt-1.5">
                        {{ $shipment->invoice_payment_date ? $shipment->invoice_payment_date->translatedFormat('d F Y') : '-' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- 3. Item Pengiriman Table Card -->
        <div class="crm-card p-0 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <h2 class="font-poppins font-bold text-sm text-gray-900">Item Pengiriman</h2>
            </div>
            <div class="crm-table-container">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>SKU</th>
                            <th>Qty</th>
                            <th>Unit</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shipment->items as $item)
                            <tr>
                                <td class="font-bold text-gray-900">{{ $item->product->name ?? '-' }}</td>
                                <td>
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-badge bg-gray-100 text-gray-700">{{ $item->product->sku ?? '-' }}</span>
                                </td>
                                <td class="text-xs text-gray-800 font-medium">{{ number_format($item->quantity, 0) }}</td>
                                <td class="text-xs text-gray-600 font-medium">{{ $item->unit }}</td>
                                <td class="text-xs text-gray-500">{{ $item->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-6 text-center text-xs text-gray-400">Tidak ada item pengiriman.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. Rencana Rute Stepper Card -->
        <div class="crm-card">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                <h2 class="font-poppins font-bold text-base text-gray-900">Rencana Rute & Perjalanan</h2>
                @if($shipment->route)
                    <span class="text-xs text-gray-500 font-medium">
                        @if($shipment->route->distance) {{ number_format($shipment->route->distance, 0) }} Km &middot; @endif
                        @if($shipment->route->duration) {{ $shipment->route->duration }} Jam @endif
                    </span>
                @endif
            </div>

            @if($shipment->route && $shipment->route->points->count() > 0)
                <div class="space-y-0 relative pl-4 border-l-2 border-gray-200">
                    @foreach($shipment->route->points as $point)
                        <div class="relative pl-6 pb-6 last:pb-0">
                            <span class="absolute -left-[25px] top-0 w-6 h-6 rounded-full {{ $point->status === 'ARRIVED' ? 'bg-success' : ($loop->first ? 'bg-primary' : 'bg-info') }} text-white text-[11px] font-bold flex items-center justify-center ring-4 ring-white">
                                {{ $loop->iteration }}
                            </span>
                            <div>
                                <p class="text-xs font-bold text-gray-900">{{ $point->location_name }}</p>
                                @if($point->address)
                                    <p class="text-[11px] text-gray-500 mt-0.5">{{ $point->address }}</p>
                                @endif
                                <p class="text-[11px] font-semibold mt-1 {{ $point->status === 'ARRIVED' ? 'text-success' : 'text-info' }}">
                                    @if($point->actual_arrival)
                                        Tiba: {{ $point->actual_arrival->format('d M Y H:i') }}
                                    @elseif($point->estimated_arrival)
                                        Estimasi: {{ $point->estimated_arrival->format('d M Y H:i') }}
                                    @else
                                        Menunggu Status
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-6 text-center text-gray-400 text-xs">
                    Belum ada rencana rute tersimpan. Klik tombol <a href="{{ route('admin.shipments.route.edit', $shipment) }}" class="text-info underline">Kelola Rute</a> untuk membuat rute baru.
                </div>
            @endif
        </div>

        <!-- 4. Riwayat Tracking & Form Update -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Timeline Tracking Card -->
            <div class="crm-card space-y-4">
                <div class="border-b border-gray-100 pb-3 flex items-center justify-between">
                    <h2 class="font-poppins font-bold text-base text-gray-900">Riwayat Tracking Live</h2>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>

                @php $trackings = $shipment->trackingUpdates->sortBy('tracked_at'); @endphp
                @if($trackings->count() > 0)
                    <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200">
                        @foreach($trackings as $tracking)
                            <div class="relative">
                                @php
                                    $dotBg = match($tracking->status->value) {
                                        'IN_TRANSIT' => 'bg-info',
                                        'ARRIVED', 'DELIVERED' => 'bg-success',
                                        'DELAYED' => 'bg-primary',
                                        default => 'bg-gray-400',
                                    };
                                @endphp
                                <span class="absolute -left-6 top-1 w-4 h-4 rounded-full border-2 border-white ring-2 ring-gray-100 {{ $dotBg }}"></span>
                                
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <p class="text-xs font-bold text-gray-900">{{ $tracking->location }}</p>
                                        <div class="mt-1">
                                            <x-badge :status="$tracking->status" />
                                        </div>
                                        @if($tracking->description)
                                            <p class="text-xs text-gray-600 mt-1 bg-gray-50 p-2 rounded-btn border border-gray-100">{{ $tracking->description }}</p>
                                        @endif
                                        <p class="text-[10px] text-gray-400 mt-1">
                                            {{ $tracking->tracked_at->format('d M Y H:i') }} &middot; {{ $tracking->user->name ?? 'Sistem' }}
                                        </p>
                                    </div>
                                    <form action="{{ route('admin.shipments.tracking.destroy', [$shipment, $tracking]) }}" method="POST"
                                          onsubmit="return confirm('Hapus update tracking ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-gray-400 hover:text-primary transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-xs text-gray-400 py-6">Belum ada riwayat tracking.</p>
                @endif
            </div>

            <!-- Form Tambah Tracking Card -->
            <div class="crm-card space-y-4">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="font-poppins font-bold text-base text-gray-900">Tambah Update Tracking</h2>
                    <p class="text-xs text-gray-500">Perbarui posisi dan status fisik barang pengiriman.</p>
                </div>

                <form method="POST" action="{{ route('admin.shipments.tracking.store', $shipment) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="status" class="crm-label">Status <span class="text-primary">*</span></label>
                        <select id="status" name="status" required class="crm-input">
                            @foreach (\App\Enums\ShipmentStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected(old('status') == $status->value)>{{ $status->label() }}</option>
                            @endforeach
                        </select>
                        @error('status') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="location" class="crm-label">Lokasi <span class="text-primary">*</span></label>
                        <input id="location" type="text" name="location" value="{{ old('location') }}" placeholder="Contoh: Hub Cikampek" required class="crm-input">
                        @error('location') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="crm-label">Keterangan Catatan</label>
                        <textarea id="description" name="description" rows="2" placeholder="Catatan posisi atau kondisi..." class="crm-input">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        + Simpan Update Tracking
                    </button>
                </form>
            </div>

        </div>

        <!-- 5. Dokumen Pengiriman & Upload Form -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Daftar Dokumen Card -->
            <div class="crm-card space-y-4">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="font-poppins font-bold text-base text-gray-900">Dokumen Pengiriman</h2>
                </div>

                @if($shipment->documents->count() > 0)
                    <div class="space-y-3">
                        @foreach($shipment->documents as $document)
                            <div class="flex items-center justify-between p-3 rounded-card border border-gray-100 bg-gray-50">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-btn bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-gray-900 truncate">{{ $document->file_name }}</p>
                                        <p class="text-[10px] text-gray-500">
                                            {{ is_object($document->type) && method_exists($document->type, 'label') ? $document->type->label() : $document->type }} &middot; {{ number_format(($document->file_size ?? 0) / 1024, 0) }} KB
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="{{ route('documents.show', $document) }}" target="_blank" class="btn-ghost text-xs text-info px-2 py-1">
                                        Lihat
                                    </a>
                                    <form action="{{ route('admin.shipments.documents.destroy', [$shipment, $document]) }}" method="POST"
                                          onsubmit="return confirm('Hapus dokumen ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-ghost text-xs text-primary px-2 py-1">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-xs text-gray-400 py-6">Belum ada dokumen yang diunggah.</p>
                @endif
            </div>

            <!-- Form Upload Dokumen Card -->
            <div class="crm-card space-y-4">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="font-poppins font-bold text-base text-gray-900">Unggah Dokumen Baru</h2>
                </div>

                <form method="POST" action="{{ route('admin.shipments.documents.store', $shipment) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label for="doc_title" class="crm-label">Judul Dokumen <span class="text-primary">*</span></label>
                        <input id="doc_title" type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Surat Jalan TTD / Invoice" required class="crm-input">
                        @error('title') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="doc_type" class="crm-label">Tipe Dokumen <span class="text-primary">*</span></label>
                        <select id="doc_type" name="type" required class="crm-input">
                            @foreach (\App\Enums\DocumentType::cases() as $type)
                                <option value="{{ $type->value }}" @selected(old('type') == $type->value)>{{ $type->label() }}</option>
                            @endforeach
                        </select>
                        @error('type') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="doc_file" class="crm-label">Pilih File (PDF, JPG, PNG) <span class="text-primary">*</span></label>
                        <input id="doc_file" type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required class="crm-input text-xs">
                        @error('file') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        + Upload Dokumen
                    </button>
                </form>
            </div>

        </div>

    </div>
</x-app-layout>