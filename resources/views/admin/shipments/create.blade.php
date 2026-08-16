<x-app-layout>
    <x-slot name="header">
        <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Pengiriman Baru') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-6">
                
                <form method="POST" action="{{ route('admin.shipments.store') }}" 
                      x-data="{
                          orders: {{ Js::from($orders->map(fn($o) => [
                              'id' => $o->id,
                              'order_number' => $o->order_number,
                              'customer_id' => $o->customer_id,
                              'customer_name' => $o->customer->company_name ?? '-',
                              'items_count' => $o->items->count(),
                              'total_weight' => $o->items->sum('weight'),
                          ])) }},
                          selectedOrderId: '{{ old('order_id', $selectedOrder->id ?? '') }}',
                          get selectedOrder() {
                              return this.orders.find(o => o.id == this.selectedOrderId);
                          }
                      }">
                    @csrf

                    <!-- Pilih Order -->
                    <div class="space-y-4 mb-6">
                        <h3 class="font-poppins font-semibold text-lg text-gray-800 border-b pb-2">Pilih Order</h3>
                        
                        <div>
                            <label for="order_id" class="block text-sm font-medium text-gray-700">Order <span class="text-primary">*</span></label>
                            <select id="order_id" name="order_id" x-model="selectedOrderId" required
                                    class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('order_id') border-primary @enderror">
                                <option value="">-- Pilih Order --</option>
                                @foreach ($orders as $order)
                                    <option value="{{ $order->id }}" @selected(old('order_id', $selectedOrder->id ?? '') == $order->id)>
                                        {{ $order->order_number }} - {{ $order->customer->company_name ?? '-' }} ({{ $order->items->count() }} item, {{ number_format($order->items->sum('weight'), 0) }} Kg)
                                    </option>
                                @endforeach
                            </select>
                            @error('order_id') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Info Order yang Dipilih -->
                        <div x-show="selectedOrder" x-transition class="bg-blue-50 border-l-4 border-info p-4 rounded-card">
                            <template x-if="selectedOrder">
                                <div class="space-y-2">
                                    <p class="text-sm"><strong>Customer:</strong> <span x-text="selectedOrder.customer_name"></span></p>
                                    <p class="text-sm"><strong>Jumlah Item:</strong> <span x-text="selectedOrder.items_count"></span></p>
                                    <p class="text-sm"><strong>Total Berat:</strong> <span x-text="selectedOrder.total_weight"></span> Kg</p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Detail Pengiriman -->
                    <div class="space-y-4 mb-6">
                        <h3 class="font-poppins font-semibold text-lg text-gray-800 border-b pb-2">Detail Pengiriman</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="origin" class="block text-sm font-medium text-gray-700">Asal <span class="text-primary">*</span></label>
                                <input id="origin" type="text" name="origin" value="{{ old('origin') }}" placeholder="Kota asal" required
                                       class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('origin') border-primary @enderror">
                                @error('origin') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="destination" class="block text-sm font-medium text-gray-700">Tujuan <span class="text-primary">*</span></label>
                                <input id="destination" type="text" name="destination" value="{{ old('destination') }}" placeholder="Kota tujuan" required
                                       class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('destination') border-primary @enderror">
                                @error('destination') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="vehicle_id" class="block text-sm font-medium text-gray-700">Kendaraan</label>
                                <select id="vehicle_id" name="vehicle_id"
                                        class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                    <option value="">-- Tanpa Kendaraan --</option>
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}" @selected(old('vehicle_id') == $vehicle->id)>
                                            {{ $vehicle->plate_number }} ({{ $vehicle->vehicle_type }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="driver_id" class="block text-sm font-medium text-gray-700">Supir</label>
                                <select id="driver_id" name="driver_id"
                                        class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                    <option value="">-- Tanpa Supir --</option>
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}" @selected(old('driver_id') == $driver->id)>
                                            {{ $driver->name }} ({{ $driver->phone }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="departure_date" class="block text-sm font-medium text-gray-700">Tanggal Berangkat</label>
                                <input id="departure_date" type="datetime-local" name="departure_date" value="{{ old('departure_date') }}"
                                       class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                            </div>

                            <div>
                                <label for="estimated_arrival" class="block text-sm font-medium text-gray-700">Estimasi Tiba</label>
                                <input id="estimated_arrival" type="datetime-local" name="estimated_arrival" value="{{ old('estimated_arrival') }}"
                                       class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-primary">*</span></label>
                            <select id="status" name="status" required
                                    class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                <option value="DRAFT" @selected(old('status') == 'DRAFT')>Draf</option>
                                <option value="READY" @selected(old('status') == 'READY')>Siap Kirim</option>
                                <option value="IN_TRANSIT" @selected(old('status') == 'IN_TRANSIT')>Dalam Perjalanan</option>
                            </select>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea id="notes" name="notes" rows="2"
                                      class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex item-center justify-end gap-4">
                        <a href="{{ route('admin.shipments.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-btn font-semibold hover:bg-gray-300 transition">Batal</a>
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">Simpan Pengiriman</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>