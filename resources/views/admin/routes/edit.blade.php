<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Rute') }} — {{ $shipment->shipment_number }}
            </h2>
            <a href="{{ route('admin.shipments.show', $shipment) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-btn font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                Kembali ke Pengiriman
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-success p-4 rounded-card">
                    <p class="text-success font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Info Shipment -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-4">
                <p class="text-sm text-gray-500">Pengiriman</p>
                <p class="text-base font-semibold text-gray-900">
                    {{ $shipment->origin }} → {{ $shipment->destination }}
                    <span class="text-gray-400 font-normal">· {{ $shipment->customer->company_name ?? '-' }}</span>
                </p>
            </div>

            <!-- Form Rute -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-6">
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
                              // Sisipkan titik baru sebelum titik terakhir (tujuan)
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
                      }">
                    @csrf

                    <!-- Distance & Duration -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="distance" class="block text-sm font-medium text-gray-700">Total Jarak (Km)</label>
                            <input id="distance" type="number" step="0.01" min="0" name="distance" 
                                   value="{{ old('distance', $shipment->route->distance ?? '') }}"
                                   class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                        </div>
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700">Estimasi Durasi (Jam)</label>
                            <input id="duration" type="number" min="0" name="duration" 
                                   value="{{ old('duration', $shipment->route->duration ?? '') }}"
                                   class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                        </div>
                    </div>

                    <!-- Route Points -->
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center border-b pb-2">
                            <h3 class="font-poppins font-semibold text-lg text-gray-800">Titik Pemberhentian</h3>
                            <button type="button" @click="addPoint()" 
                                    class="text-sm text-info hover:underline font-medium">
                                + Tambah Titik Transit
                            </button>
                        </div>

                        <template x-for="(point, index) in points" :key="index">
                            <div class="bg-gray-50 p-4 rounded-card border border-gray-200">
                                <div class="flex justify-between items-center mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="w-7 h-7 rounded-full bg-primary text-white text-xs font-bold flex items-center justify-center" x-text="index + 1"></span>
                                        <span class="text-sm font-medium text-gray-700" x-text="index === 0 ? 'Titik Asal' : (index === points.length - 1 ? 'Titik Tujuan' : 'Transit #' + index)"></span>
                                    </div>
                                    <div class="flex gap-1">
                                        <button type="button" @click="moveUp(index)" :disabled="index === 0"
                                                class="px-2 py-1 text-xs text-gray-500 hover:text-gray-800 disabled:opacity-30 disabled:cursor-not-allowed">▲</button>
                                        <button type="button" @click="moveDown(index)" :disabled="index === points.length - 1"
                                                class="px-2 py-1 text-xs text-gray-500 hover:text-gray-800 disabled:opacity-30 disabled:cursor-not-allowed">▼</button>
                                        <button type="button" @click="removePoint(index)" x-show="points.length > 2"
                                                class="px-2 py-1 text-xs text-primary hover:underline">Hapus</button>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600">Nama Lokasi <span class="text-primary">*</span></label>
                                        <input type="text" :name="'points[' + index + '][location_name]'" x-model="point.location_name" required
                                               placeholder="Contoh: Gudang Medan, Pool Jambi"
                                               class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm text-sm">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-600">Alamat</label>
                                        <input type="text" :name="'points[' + index + '][address]'" x-model="point.address"
                                               class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm text-sm">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600">Latitude</label>
                                            <input type="number" step="any" :name="'points[' + index + '][latitude]'" x-model="point.latitude"
                                                   class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600">Longitude</label>
                                            <input type="number" step="any" :name="'points[' + index + '][longitude]'" x-model="point.longitude"
                                                   class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600">Estimasi Tiba</label>
                                            <input type="datetime-local" :name="'points[' + index + '][estimated_arrival]'" x-model="point.estimated_arrival"
                                                   class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm text-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('admin.shipments.show', $shipment) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-btn font-semibold hover:bg-gray-300 transition">Batal</a>
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">Simpan Rute</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>