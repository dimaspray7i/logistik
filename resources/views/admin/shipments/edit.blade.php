<x-app-layout>
    <x-slot name="header">
        <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ubah Pengiriman') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-6">
                
                <form method="POST" action="{{ route('admin.shipments.update', $shipment) }}">
                    @csrf
                    @method('PUT')

                    <!-- Info Order (Read-only) -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-card">
                        <p class="text-sm text-gray-500">Pesanan</p>
                        <p class="text-base font-semibold text-gray-900">
                            {{ $shipment->order->order_number ?? '-' }} — {{ $shipment->customer->company_name ?? '-' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Order dan item tidak dapat diubah di sini. Item mengikuti order asal.</p>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="origin" class="block text-sm font-medium text-gray-700">Asal <span class="text-primary">*</span></label>
                                <input id="origin" type="text" name="origin" value="{{ old('origin', $shipment->origin) }}" required
                                       class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('origin') border-primary @enderror">
                                @error('origin') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="destination" class="block text-sm font-medium text-gray-700">Tujuan <span class="text-primary">*</span></label>
                                <input id="destination" type="text" name="destination" value="{{ old('destination', $shipment->destination) }}" required
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
                                        <option value="{{ $vehicle->id }}" @selected(old('vehicle_id', $shipment->vehicle_id) == $vehicle->id)>
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
                                        <option value="{{ $driver->id }}" @selected(old('driver_id', $shipment->driver_id) == $driver->id)>
                                            {{ $driver->name }} ({{ $driver->phone }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="departure_date" class="block text-sm font-medium text-gray-700">Tanggal Berangkat</label>
                                <input id="departure_date" type="datetime-local" name="departure_date" 
                                       value="{{ old('departure_date', $shipment->departure_date?->format('Y-m-d\TH:i')) }}"
                                       class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                            </div>

                            <div>
                                <label for="estimated_arrival" class="block text-sm font-medium text-gray-700">Estimasi Tiba</label>
                                <input id="estimated_arrival" type="datetime-local" name="estimated_arrival" 
                                       value="{{ old('estimated_arrival', $shipment->estimated_arrival?->format('Y-m-d\TH:i')) }}"
                                       class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                            </div>

                            <div>
                                <label for="actual_arrival" class="block text-sm font-medium text-gray-700">Tiba Aktual</label>
                                <input id="actual_arrival" type="datetime-local" name="actual_arrival" 
                                       value="{{ old('actual_arrival', $shipment->actual_arrival?->format('Y-m-d\TH:i')) }}"
                                       class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-primary">*</span></label>
                            <select id="status" name="status" required
                                    class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                @foreach (\App\Enums\ShipmentStatus::cases() as $status)
                                    <option value="{{ $status->label() }}" @selected(old('status', $shipment->status->label()) == $status->label())>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea id="notes" name="notes" rows="2"
                                      class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">{{ old('notes', $shipment->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="flex item-center justify-end gap-4">
                        <a href="{{ route('admin.shipments.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-btn font-semibold hover:bg-gray-300 transition">Batal</a>
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">Perbarui Pengiriman</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>