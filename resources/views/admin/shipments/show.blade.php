<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pengiriman') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.shipments.route.edit', $shipment) }}" 
                   class="inline-flex items-center px-4 py-2 bg-info border border-transparent rounded-btn font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 transition">
                    Kelola Rute
                </a>
                <a href="{{ route('admin.shipments.edit', $shipment) }}" 
                   class="inline-flex items-center px-4 py-2 bg-warning border border-transparent rounded-btn font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 transition">
                    Ubah
                </a>
                <a href="{{ route('admin.shipments.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-btn font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-success p-4 rounded-card">
                    <p class="text-success font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Kartu Info Pengiriman -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-poppins font-semibold text-lg text-gray-800">{{ $shipment->shipment_number }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $shipment->origin }} → {{ $shipment->destination }}</p>
                    </div>
                    @php
                        $statusColor = match($shipment->status->value) {
                            'DRAFT' => 'bg-gray-100 text-gray-600',
                            'READY' => 'bg-yellow-100 text-yellow-700',
                            'IN_TRANSIT' => 'bg-blue-100 text-info',
                            'ARRIVED', 'DELIVERED' => 'bg-green-100 text-success',
                            'DELAYED' => 'bg-red-100 text-primary',
                            'CANCELLED' => 'bg-gray-100 text-gray-600',
                            default => 'bg-gray-100 text-gray-600',
                        };
                    @endphp
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-badge {{ $statusColor }}">
                        {{ $shipment->status->label() }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-t pt-4">
                    <div>
                        <p class="text-sm text-gray-500">Customer</p>
                        <a href="{{ route('admin.customers.show', $shipment->customer_id) }}" class="text-base font-medium text-info hover:underline">
                            {{ $shipment->customer->company_name ?? '-' }}
                        </a>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Order</p>
                        <a href="{{ route('admin.orders.show', $shipment->order_id) }}" class="text-base font-medium text-info hover:underline">
                            {{ $shipment->order->order_number ?? '-' }}
                        </a>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Berat</p>
                        <p class="text-base font-medium text-gray-900">{{ number_format($shipment->total_weight, 0) }} Kg</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Kendaraan</p>
                        <p class="text-base font-medium text-gray-900">
                            {{ $shipment->vehicle ? $shipment->vehicle->plate_number . ' (' . $shipment->vehicle->vehicle_type . ')' : 'Belum diassign' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Supir</p>
                        <p class="text-base font-medium text-gray-900">{{ $shipment->driver->name ?? 'Belum diassign' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Berangkat</p>
                        <p class="text-base font-medium text-gray-900">{{ $shipment->departure_date ? $shipment->departure_date->format('d M Y H:i') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Estimasi Tiba</p>
                        <p class="text-base font-medium text-gray-900">{{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y H:i') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tiba Aktual</p>
                        <p class="text-base font-medium text-gray-900">{{ $shipment->actual_arrival ? $shipment->actual_arrival->format('d M Y H:i') : '-' }}</p>
                    </div>
                    @if($shipment->notes)
                    <div>
                        <p class="text-sm text-gray-500">Catatan</p>
                        <p class="text-base text-gray-700">{{ $shipment->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tabel Item Pengiriman -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-poppins font-semibold text-lg text-gray-800">Item Pengiriman</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($shipment->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->product->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-badge bg-blue-100 text-info">{{ $item->product->sku ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($item->weight, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->unit }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada item.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Rencana Rute -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-poppins font-semibold text-lg text-gray-800">Rencana Rute</h3>
                    @if($shipment->route)
                        <span class="text-xs text-gray-500">
                            @if($shipment->route->distance) {{ number_format($shipment->route->distance, 0) }} Km · @endif
                            @if($shipment->route->duration) {{ $shipment->route->duration }} Jam @endif
                        </span>
                    @endif
                </div>
                <div class="p-6">
                    @if($shipment->route && $shipment->route->points->count() > 0)
                        <div class="space-y-0">
                            @foreach($shipment->route->points as $point)
                                <div class="flex gap-4">
                                    <div class="flex flex-col items-center">
                                        <div class="w-8 h-8 rounded-full {{ $point->status === 'ARRIVED' ? 'bg-success' : ($loop->first ? 'bg-primary' : 'bg-info') }} text-white text-xs font-bold flex items-center justify-center">
                                            {{ $loop->iteration }}
                                        </div>
                                        @unless($loop->last)
                                            <div class="w-0.5 h-12 bg-gray-200"></div>
                                        @endunless
                                    </div>
                                    <div class="pb-6">
                                        <p class="text-sm font-semibold text-gray-900">{{ $point->location_name }}</p>
                                        @if($point->address)
                                            <p class="text-xs text-gray-500">{{ $point->address }}</p>
                                        @endif
                                        <p class="text-xs mt-1 {{ $point->status === 'ARRIVED' ? 'text-success' : 'text-info' }}">
                                            @if($point->actual_arrival)
                                                Tiba: {{ $point->actual_arrival->format('d M Y H:i') }}
                                            @elseif($point->estimated_arrival)
                                                Estimasi: {{ $point->estimated_arrival->format('d M Y H:i') }}
                                            @else
                                                Menunggu
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-4">Belum ada rencana rute. Klik "Kelola Rute" untuk membuat.</p>
                    @endif
                </div>
            </div>

            <!-- Riwayat Tracking + Form Tambah -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Timeline Tracking -->
                <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-poppins font-semibold text-lg text-gray-800">Riwayat Tracking</h3>
                    </div>
                    <div class="p-6">
                        @php $trackings = $shipment->trackingUpdates->sortBy('tracked_at'); @endphp
                        @if($trackings->count() > 0)
                            <div class="space-y-0">
                                @foreach($trackings as $tracking)
                                    <div class="flex gap-4">
                                        <div class="flex flex-col items-center">
                                            @php
                                                $dotColor = match($tracking->status->value) {
                                                    'IN_TRANSIT' => 'bg-info',
                                                    'ARRIVED', 'DELIVERED' => 'bg-success',
                                                    'DELAYED' => 'bg-primary',
                                                    default => 'bg-gray-400',
                                                };
                                            @endphp
                                            <div class="w-4 h-4 rounded-full {{ $dotColor }} mt-1"></div>
                                            @unless($loop->last)
                                                <div class="w-0.5 h-14 bg-gray-200"></div>
                                            @endunless
                                        </div>
                                        <div class="pb-6 flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $tracking->location }}</p>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-badge {{ match($tracking->status->value) {
                                                        'IN_TRANSIT' => 'bg-blue-100 text-info',
                                                        'ARRIVED', 'DELIVERED' => 'bg-green-100 text-success',
                                                        'DELAYED' => 'bg-red-100 text-primary',
                                                        default => 'bg-gray-100 text-gray-600',
                                                    } }}">
                                                        {{ $tracking->status->label() }}
                                                    </span>
                                                    @if($tracking->description)
                                                        <p class="text-xs text-gray-500 mt-1">{{ $tracking->description }}</p>
                                                    @endif
                                                    <p class="text-xs text-gray-400 mt-1">
                                                        {{ $tracking->tracked_at->format('d M Y H:i') }} · oleh {{ $tracking->user->name ?? 'Sistem' }}
                                                    </p>
                                                </div>
                                                <form action="{{ route('admin.shipments.tracking.destroy', [$shipment, $tracking]) }}" method="POST"
                                                      onsubmit="return confirm('Hapus update tracking ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs text-primary hover:underline">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-gray-500 py-4">Belum ada update tracking.</p>
                        @endif
                    </div>
                </div>

                <!-- Form Tambah Tracking -->
                <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-poppins font-semibold text-lg text-gray-800">Tambah Update Tracking</h3>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('admin.shipments.tracking.store', $shipment) }}" class="space-y-4">
                            @csrf

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-primary">*</span></label>
                                <select id="status" name="status" required
                                        class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                    @foreach (\App\Enums\ShipmentStatus::cases() as $status)
                                        <option value="{{ $status->value }}" @selected(old('status') == $status->value)>{{ $status->label() }}</option>
                                    @endforeach
                                </select>
                                @error('status') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Lokasi <span class="text-primary">*</span></label>
                                <input id="location" type="text" name="location" value="{{ old('location') }}" placeholder="Contoh: Pool Pekanbaru" required
                                       class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                @error('location') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Keterangan</label>
                                <textarea id="description" name="description" rows="2"
                                          class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">{{ old('description') }}</textarea>
                            </div>

                            @if($shipment->route && $shipment->route->points->count() > 0)
                            <div>
                                <label for="route_point_id" class="block text-sm font-medium text-gray-700">Hubungkan ke Titik Rute (opsional)</label>
                                <select id="route_point_id" name="route_point_id"
                                        class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                    <option value="">-- Tidak dihubungkan --</option>
                                    @foreach($shipment->route->points as $point)
                                        <option value="{{ $point->id }}" @selected(old('route_point_id') == $point->id)>
                                            {{ $point->sequence }}. {{ $point->location_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                                    <input id="latitude" type="number" step="any" name="latitude" value="{{ old('latitude') }}"
                                           class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                </div>
                                <div>
                                    <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                                    <input id="longitude" type="number" step="any" name="longitude" value="{{ old('longitude') }}"
                                           class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                </div>
                            </div>

                            <div>
                                <label for="tracked_at" class="block text-sm font-medium text-gray-700">Waktu Tracking (kosongkan = sekarang)</label>
                                <input id="tracked_at" type="datetime-local" name="tracked_at" value="{{ old('tracked_at') }}"
                                       class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                            </div>

                            <button type="submit" class="w-full px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">
                                Tambah Tracking
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Dokumen Pengiriman -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Daftar Dokumen -->
                <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-poppins font-semibold text-lg text-gray-800">Dokumen Pengiriman</h3>
                    </div>
                    <div class="p-6">
                        @if($shipment->documents->count() > 0)
                            <div class="space-y-3">
                                @foreach($shipment->documents as $document)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-card border border-gray-100">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                                @if(str_starts_with($document->mime_type, 'image/'))
                                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                @else
                                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $document->title }}</p>
                                                <p class="text-xs text-gray-500">
                                                    <span class="px-1.5 py-0.5 rounded bg-gray-200 text-gray-600">{{ $document->type->label() }}</span>
                                                    · {{ number_format($document->size / 1024, 0) }} KB
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank"
                                               class="text-info text-sm hover:underline">Lihat</a>
                                            <form action="{{ route('admin.shipments.documents.destroy', [$shipment, $document]) }}" method="POST"
                                                  onsubmit="return confirm('Hapus dokumen ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-primary text-sm hover:underline">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-gray-500 py-4">Belum ada dokumen.</p>
                        @endif
                    </div>
                </div>

                <!-- Form Upload Dokumen -->
                <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-poppins font-semibold text-lg text-gray-800">Unggah Dokumen</h3>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('admin.shipments.documents.store', $shipment) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <div>
                                <label for="doc_title" class="block text-sm font-medium text-gray-700">Judul Dokumen <span class="text-primary">*</span></label>
                                <input id="doc_title" type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Resi JNE 00123" required
                                       class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                @error('title') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="doc_type" class="block text-sm font-medium text-gray-700">Tipe Dokumen <span class="text-primary">*</span></label>
                                <select id="doc_type" name="type" required
                                        class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                    @foreach (\App\Enums\DocumentType::cases() as $type)
                                        <option value="{{ $type->value }}" @selected(old('type') == $type->value)>{{ $type->label() }}</option>
                                    @endforeach
                                </select>
                                @error('type') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="doc_file" class="block text-sm font-medium text-gray-700">File <span class="text-primary">*</span></label>
                                <input id="doc_file" type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-btn file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-red-700">
                                <p class="text-xs text-gray-400 mt-1">PDF, JPG, atau PNG. Maksimal 5MB.</p>
                                @error('file') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            @if($shipment->trackingUpdates->count() > 0)
                            <div>
                                <label for="doc_tracking" class="block text-sm font-medium text-gray-700">Hubungkan ke Tracking (opsional)</label>
                                <select id="doc_tracking" name="tracking_update_id"
                                        class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                    <option value="">-- Tidak dihubungkan --</option>
                                    @foreach($shipment->trackingUpdates->sortByDesc('tracked_at') as $tracking)
                                        <option value="{{ $tracking->id }}" @selected(old('tracking_update_id') == $tracking->id)>
                                            {{ $tracking->tracked_at->format('d M Y H:i') }} — {{ $tracking->location }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <button type="submit" class="w-full px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">
                                Unggah Dokumen
                            </button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>