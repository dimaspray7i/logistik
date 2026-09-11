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
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight font-mono">{{ $shipment->display_code }}</h1>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span>Ekspedisi: {{ $shipment->carrier_label }}</span>
                    </span>
                    <x-badge :status="$shipment->status" />
                </div>
                <p class="text-sm text-gray-500 mt-1 font-normal">
                    Rute: <span class="font-semibold text-gray-800">{{ $shipment->origin }} &rarr; {{ $shipment->destination }}</span>
                </p>
            </div>
        </div>

        <!-- 1. Informasi Utama Pengiriman Card -->
        <div class="crm-card space-y-4">
            <div class="border-b border-gray-100 pb-3 flex items-center justify-between">
                <h2 class="font-poppins font-bold text-base text-gray-900">Informasi Pengiriman</h2>
                <span class="text-xs text-gray-400 font-medium">Metode: <strong class="text-gray-700">Ekspedisi Eksternal</strong></span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs sm:text-sm">
                <div>
                    <p class="text-gray-400 font-medium">Penyedia Ekspedisi</p>
                    <p class="font-bold text-blue-700 mt-0.5">{{ $shipment->carrier_label }}</p>
                </div>
                <div>
                    <p class="text-gray-400 font-medium">Nomor Resi / Pelacakan</p>
                    <p class="font-bold text-gray-900 font-mono mt-0.5">{{ $shipment->tracking_number ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 font-medium">Nomor Pengiriman (Referensi Sistem)</p>
                    <p class="font-semibold text-gray-700 font-mono mt-0.5">{{ $shipment->shipment_number }}</p>
                </div>
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

        <!-- 4. Pelacakan Pengiriman (Peta Interaktif & Timeline Perjalanan) -->
        <x-shipment-tracking :shipment="$shipment" :isAdmin="true" />

        <!-- 5. Form Tambah Update Tracking (Admin Only) -->
        <div class="crm-card space-y-5" x-data="trackingFormHelper()">
            <div class="border-b border-gray-100 pb-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h2 class="font-poppins font-bold text-base text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Tambah Update Tracking Pengiriman</span>
                    </h2>
                    <p class="text-xs text-gray-500">Perbarui posisi, status fisik, dan koordinat geografis barang pengiriman.</p>
                </div>
                <button type="button" @click="detectCoordinates()" :disabled="detecting" class="btn-secondary !text-xs !py-1.5 !px-3 self-start sm:self-auto">
                    <svg class="w-3.5 h-3.5 text-primary" :class="{ 'animate-spin': detecting }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span x-text="detecting ? 'Mencari Koordinat...' : 'Cari Koordinat Otomatis'"></span>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.shipments.tracking.store', $shipment) }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label for="status" class="crm-label">Status Pengiriman <span class="text-primary">*</span></label>
                        <select id="status" name="status" required class="crm-input">
                            @foreach (\App\Enums\ShipmentStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected(old('status') == $status->value)>{{ $status->label() }}</option>
                            @endforeach
                        </select>
                        @error('status') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="location" class="crm-label">Lokasi / Hub <span class="text-primary">*</span></label>
                        <input id="location" type="text" name="location" x-model="location" value="{{ old('location') }}" placeholder="Contoh: Hub Pekanbaru / Gudang Medan" required class="crm-input">
                        @error('location') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tracked_at" class="crm-label">Waktu Update (WIB)</label>
                        <input id="tracked_at" type="datetime-local" name="tracked_at" value="{{ old('tracked_at', now()->format('Y-m-d\TH:i')) }}" class="crm-input text-xs">
                        @error('tracked_at') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="lg:col-span-2">
                        <label for="address" class="crm-label">Alamat / Keterangan Titik</label>
                        <input id="address" type="text" name="address" x-model="address" value="{{ old('address') }}" placeholder="Jl. Soekarno-Hatta No. 45" class="crm-input">
                        @error('address') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="city" class="crm-label">Kota / Wilayah</label>
                        <input id="city" type="text" name="city" x-model="city" value="{{ old('city') }}" placeholder="Pekanbaru" class="crm-input">
                        @error('city') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="country" class="crm-label">Negara</label>
                        <input id="country" type="text" name="country" x-model="country" value="{{ old('country', 'Indonesia') }}" placeholder="Indonesia" class="crm-input">
                        @error('country') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Koordinat Geografis -->
                <div class="p-3.5 bg-gray-50/80 rounded-card border border-gray-100 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-700">Koordinat Peta (Latitude & Longitude)</span>
                        <span class="text-[11px] text-gray-400">Boleh dikosongkan jika ingin sistem geocoding otomatis mengisinya saat disimpan.</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="latitude" class="crm-label text-xs">Latitude (-90 s/d 90)</label>
                            <input id="latitude" type="number" step="any" name="latitude" x-model="lat" value="{{ old('latitude') }}" placeholder="Contoh: 0.5071" class="crm-input font-mono text-xs">
                            @error('latitude') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="longitude" class="crm-label text-xs">Longitude (-180 s/d 180)</label>
                            <input id="longitude" type="number" step="any" name="longitude" x-model="lng" value="{{ old('longitude') }}" placeholder="Contoh: 101.4478" class="crm-input font-mono text-xs">
                            @error('longitude') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div x-show="geoNotice" x-cloak class="text-[11px] text-emerald-600 font-medium pt-1" x-text="geoNotice"></div>
                </div>

                <div>
                    <label for="description" class="crm-label">Catatan Aktivitas / Keterangan Fisik</label>
                    <textarea id="description" name="description" rows="2" placeholder="Contoh: Barang telah tiba di gudang transit Pekanbaru dan sedang disortir..." class="crm-input">{{ old('description') }}</textarea>
                    @error('description') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="btn-primary w-full sm:w-auto px-6">
                        + Simpan Update Tracking
                    </button>
                </div>
            </form>
        </div>

        <script>
            function trackingFormHelper() {
                return {
                    location: '{{ old('location') }}',
                    address: '{{ old('address') }}',
                    city: '{{ old('city') }}',
                    country: '{{ old('country', 'Indonesia') }}',
                    lat: '{{ old('latitude') }}',
                    lng: '{{ old('longitude') }}',
                    detecting: false,
                    geoNotice: '',
                    detectCoordinates() {
                        var q = this.location.trim();
                        if (!q) {
                            alert('Silakan isi kolom Lokasi / Hub terlebih dahulu.');
                            return;
                        }
                        this.detecting = true;
                        this.geoNotice = '';

                        var self = this;
                        // Coba query Nominatim OSM
                        var fullQuery = q;
                        if (self.city && !q.toLowerCase().includes(self.city.toLowerCase())) {
                            fullQuery += ', ' + self.city;
                        }
                        if (self.country && !fullQuery.toLowerCase().includes(self.country.toLowerCase())) {
                            fullQuery += ', ' + self.country;
                        }

                        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(fullQuery) + '&limit=1')
                            .then(function(res) { return res.json(); })
                            .then(function(data) {
                                self.detecting = false;
                                if (data && data.length > 0) {
                                    self.lat = parseFloat(data[0].lat).toFixed(6);
                                    self.lng = parseFloat(data[0].lon).toFixed(6);
                                    self.geoNotice = '✓ Koordinat berhasil ditemukan: ' + self.lat + ', ' + self.lng;
                                } else {
                                    self.geoNotice = 'Lokasi spesifik tidak ditemukan di peta, sistem backend akan mencoba kamus transit otomatis saat disimpan.';
                                }
                            })
                            .catch(function() {
                                self.detecting = false;
                                self.geoNotice = 'Koneksi pencarian peta offline/terbatas. Sistem backend akan otomatis melengkapi koordinat saat disimpan.';
                            });
                    }
                };
            }
        </script>

        <!-- 5. Dokumen Pengiriman & Upload Form -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Daftar Dokumen Card -->
            <div class="crm-card space-y-4">
                <div class="border-b border-gray-100 pb-3 flex items-center justify-between">
                    <div>
                        <h2 class="font-poppins font-bold text-base text-gray-900">Dokumen Pengiriman</h2>
                        <p class="text-xs text-gray-400 mt-0.5">File dan surat resmi terkait pengiriman ini</p>
                    </div>
                    @if($shipment->documents->count() > 0)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-badge text-xs font-bold bg-primary/10 text-primary border border-primary/20">
                            {{ $shipment->documents->count() }} file
                        </span>
                    @endif
                </div>

                @if($shipment->documents->count() > 0)
                    <div class="space-y-3">
                        @foreach($shipment->documents as $document)
                            <x-document-card
                                :document="$document"
                                :showRoute="route('documents.show', $document)"
                                :deleteRoute="route('admin.shipments.documents.destroy', [$shipment, $document])"
                                :canDelete="true"
                            />
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-300 mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-400">Belum ada dokumen</p>
                        <p class="text-xs text-gray-300 mt-1">Upload dokumen pertama melalui form di samping</p>
                    </div>
                @endif
            </div>

            <!-- Form Upload Dokumen (component) -->
            <x-document-upload-form
                :action="route('admin.shipments.documents.store', $shipment)"
                :documentTypes="\App\Enums\DocumentType::cases()"
            />

        </div>

    </div>
</x-app-layout>
