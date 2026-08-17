<x-app-layout>
    <div class="space-y-6">

        <x-page-header title="Ubah Pengiriman" description="Perbarui data dan status pengiriman {{ $shipment->shipment_number }}.">
            <x-slot name="actions">
                <a href="{{ route('admin.shipments.show', $shipment) }}" class="btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali ke Detail</span>
                </a>
            </x-slot>
        </x-page-header>

        <form method="POST" action="{{ route('admin.shipments.update', $shipment) }}">
            @csrf
            @method('PUT')

            {{-- ===== SECTION 1: Info Order (Read-only) ===== --}}
            <div class="crm-card mb-6">
                <div class="border-b border-gray-100 pb-3 mb-4">
                    <h2 class="font-poppins font-bold text-base text-gray-900">Referensi Order</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Order dan item tidak dapat diubah. Item mengikuti order asal.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-gray-50 rounded-card p-4 border border-gray-100">
                    <div>
                        <p class="text-xs text-gray-400 font-medium">No. Order</p>
                        <p class="font-semibold text-gray-900 mt-0.5">{{ $shipment->order->order_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Pelanggan</p>
                        <p class="font-semibold text-gray-900 mt-0.5">{{ $shipment->customer->company_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">No. Pengiriman</p>
                        <p class="font-semibold text-gray-900 mt-0.5">{{ $shipment->shipment_number }}</p>
                    </div>
                </div>
            </div>

            {{-- ===== SECTION 2: Detail Pengiriman ===== --}}
            <div class="crm-card space-y-4 mb-6">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="font-poppins font-bold text-base text-gray-900">Detail Pengiriman</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="origin" class="crm-label">Kota Asal <span class="text-primary">*</span></label>
                        <input id="origin" type="text" name="origin" value="{{ old('origin', $shipment->origin) }}" required
                               class="crm-input @error('origin') border-primary @enderror">
                        @error('origin') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="destination" class="crm-label">Kota Tujuan <span class="text-primary">*</span></label>
                        <input id="destination" type="text" name="destination" value="{{ old('destination', $shipment->destination) }}" required
                               class="crm-input @error('destination') border-primary @enderror">
                        @error('destination') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="vehicle_id" class="crm-label">Kendaraan</label>
                        <select id="vehicle_id" name="vehicle_id" class="crm-input">
                            <option value="">-- Tanpa Kendaraan --</option>
                            @foreach ($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" @selected(old('vehicle_id', $shipment->vehicle_id) == $vehicle->id)>
                                    {{ $vehicle->plate_number }} ({{ $vehicle->vehicle_type }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="driver_id" class="crm-label">Supir / Driver</label>
                        <select id="driver_id" name="driver_id" class="crm-input">
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
                        <label for="departure_date" class="crm-label">Tanggal Berangkat</label>
                        <input id="departure_date" type="datetime-local" name="departure_date"
                               value="{{ old('departure_date', $shipment->departure_date?->format('Y-m-d\TH:i')) }}"
                               class="crm-input">
                    </div>
                    <div>
                        <label for="estimated_arrival" class="crm-label">Estimasi Tiba</label>
                        <input id="estimated_arrival" type="datetime-local" name="estimated_arrival"
                               value="{{ old('estimated_arrival', $shipment->estimated_arrival?->format('Y-m-d\TH:i')) }}"
                               class="crm-input">
                    </div>
                    <div>
                        <label for="actual_arrival" class="crm-label">Tiba Aktual</label>
                        <input id="actual_arrival" type="datetime-local" name="actual_arrival"
                               value="{{ old('actual_arrival', $shipment->actual_arrival?->format('Y-m-d\TH:i')) }}"
                               class="crm-input">
                    </div>
                </div>

                <div>
                    <label for="status" class="crm-label">Status <span class="text-primary">*</span></label>
                    <select id="status" name="status" required class="crm-input">
                        @foreach (\App\Enums\ShipmentStatus::cases() as $s)
                            {{-- PENTING: value harus $s->value (misal: "DELIVERED"), bukan label --}}
                            <option value="{{ $s->value }}" @selected(old('status', $shipment->status->value) === $s->value)>
                                {{ $s->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="notes" class="crm-label">Catatan</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Catatan tambahan mengenai pengiriman..."
                              class="crm-input">{{ old('notes', $shipment->notes) }}</textarea>
                </div>
            </div>

            {{-- ===== ACTION BUTTONS ===== --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.shipments.show', $shipment) }}" class="btn-ghost">Batal</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Perbarui Pengiriman</span>
                </button>
            </div>

        </form>

    </div>
</x-app-layout>