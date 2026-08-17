<x-app-layout>
    <div class="space-y-6">

        <x-page-header title="Kelola Rute & Transit" description="Pengiriman {{ $shipment->shipment_number }} ({{ $shipment->origin }} → {{ $shipment->destination }})">
            <x-slot name="actions">
                <a href="{{ route('admin.shipments.show', $shipment) }}" class="btn-secondary">
                    &larr; Kembali ke Pengiriman
                </a>
            </x-slot>
        </x-page-header>

        <!-- Info Shipment Card -->
        <div class="crm-card p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-red-50/40 border-l-4 border-primary">
            <div>
                <p class="text-xs font-semibold text-muted uppercase tracking-wider">Informasi Pengiriman</p>
                <p class="text-base font-bold text-gray-900 mt-0.5">
                    {{ $shipment->origin }} &rarr; {{ $shipment->destination }}
                </p>
                <p class="text-xs text-gray-600">Pelanggan: <span class="font-semibold text-gray-900">{{ $shipment->customer->company_name ?? '-' }}</span></p>
            </div>
            <div class="shrink-0">
                <x-badge :status="$shipment->status" />
            </div>
        </div>

        <!-- Form Rute Card -->
        <div class="crm-card p-6">
            <form method="POST" action="{{ route('admin.shipments.route.store', $shipment) }}"
                  x-data="{
                      points: {{ Js::from(
                          $shipment->route && $shipment->route->points->count() > 0
                          ? $shipment->route->points->map(fn($p) => [
                              'location_name' => $p->location_name,
                              'address' => $p->address ?? '',
                              'latitude' => $p->latitude ?? '',
                              'longitude' => $p->longitude ?? '',
                              'estimated_arrival' => $p->estimated_arrival?->format('Y-m-d\TH:i') ?? '',
                          ])
                          : [
                              ['location_name' => $shipment->origin, 'address' => '', 'latitude' => '', 'longitude' => '', 'estimated_arrival' => ''],
                              ['location_name' => $shipment->destination, 'address' => '', 'latitude' => '', 'longitude' => '', 'estimated_arrival' => ''],
                          ]
                      ) }},
                      addPoint() {
                          const lastIndex = this.points.length - 1;
                          this.points.splice(lastIndex, 0, {
                              location_name: '', address: '', latitude: '', longitude: '', estimated_arrival: ''
                          });
                      },
                      removePoint(index) {
                          if (this.points.length > 2) {
                              this.points.splice(index, 1);
                          }
                      },
                      moveUp(index) {
                          if (index > 0) {
                              const temp = this.points[index - 1];
                              this.points[index - 1] = this.points[index];
                              this.points[index] = temp;
                          }
                      },
                      moveDown(index) {
                          if (index < this.points.length - 1) {
                              const temp = this.points[index + 1];
                              this.points[index + 1] = this.points[index];
                              this.points[index] = temp;
                          }
                      }
                  }}">
                @csrf

                <!-- Distance & Duration -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="distance" class="crm-label">Total Jarak (Km)</label>
                        <input id="distance" type="number" step="0.01" min="0" name="distance" 
                               value="{{ old('distance', $shipment->route->distance ?? '') }}"
                               placeholder="Contoh: 450"
                               class="crm-input">
                    </div>
                    <div>
                        <label for="duration" class="crm-label">Estimasi Durasi (Jam)</label>
                        <input id="duration" type="number" min="0" name="duration" 
                               value="{{ old('duration', $shipment->route->duration ?? '') }}"
                               placeholder="Contoh: 12"
                               class="crm-input">
                    </div>
                </div>

                <!-- Route Points -->
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <h3 class="font-poppins font-bold text-base text-gray-900">Titik Pemberhentian & Transit</h3>
                        <button type="button" @click="addPoint()" class="btn-ghost text-info hover:bg-blue-50">
                            + Tambah Titik Transit
                        </button>
                    </div>

                    <template x-for="(point, index) in points" :key="index">
                        <div class="bg-gray-50/70 p-4 rounded-card border border-gray-200 space-y-3">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-primary text-white text-xs font-bold flex items-center justify-center" x-text="index + 1"></span>
                                    <span class="text-xs font-bold text-gray-900 uppercase tracking-wider" x-text="index === 0 ? 'Titik Asal (Origin)' : (index === points.length - 1 ? 'Titik Tujuan (Destination)' : 'Transit #' + index)"></span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="moveUp(index)" :disabled="index === 0"
                                            class="p-1 text-xs text-gray-500 hover:text-gray-900 disabled:opacity-30 disabled:cursor-not-allowed">▲</button>
                                    <button type="button" @click="moveDown(index)" :disabled="index === points.length - 1"
                                            class="p-1 text-xs text-gray-500 hover:text-gray-900 disabled:opacity-30 disabled:cursor-not-allowed">▼</button>
                                    <button type="button" @click="removePoint(index)" x-show="points.length > 2"
                                            class="p-1 text-xs text-primary font-semibold hover:underline">Hapus</button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="crm-label text-xs">Nama Lokasi <span class="text-primary">*</span></label>
                                    <input type="text" :name="'points[' + index + '][location_name]'" x-model="point.location_name" required
                                           placeholder="Contoh: Gudang Medan, Checkpoint Palembang"
                                           class="crm-input text-xs">
                                </div>

                                <div>
                                    <label class="crm-label text-xs">Alamat</label>
                                    <input type="text" :name="'points[' + index + '][address]'" x-model="point.address"
                                           placeholder="Alamat lengkap lokasi"
                                           class="crm-input text-xs">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="crm-label text-xs">Latitude</label>
                                    <input type="number" step="any" :name="'points[' + index + '][latitude]'" x-model="point.latitude"
                                           placeholder="Contoh: -6.2088"
                                           class="crm-input text-xs">
                                </div>
                                <div>
                                    <label class="crm-label text-xs">Longitude</label>
                                    <input type="number" step="any" :name="'points[' + index + '][longitude]'" x-model="point.longitude"
                                           placeholder="Contoh: 106.8456"
                                           class="crm-input text-xs">
                                </div>
                                <div>
                                    <label class="crm-label text-xs">Estimasi Tiba</label>
                                    <input type="datetime-local" :name="'points[' + index + '][estimated_arrival]'" x-model="point.estimated_arrival"
                                           class="crm-input text-xs">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.shipments.show', $shipment) }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        Simpan Rute
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>