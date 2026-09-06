<x-app-layout>
    <div class="space-y-6">

        <x-page-header title="Ubah Pengiriman" description="Perbarui data, jenis pengiriman, dan status pengiriman {{ $shipment->display_code }}.">
            <x-slot name="actions">
                <a href="{{ route('admin.shipments.show', $shipment) }}" class="btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali ke Detail</span>
                </a>
            </x-slot>
        </x-page-header>

        <form method="POST" action="{{ route('admin.shipments.update', $shipment) }}"
              x-data="{
                  shippingType: '{{ old('shipping_type', is_object($shipment->shipping_type) ? $shipment->shipping_type->value : ($shipment->shipping_type ?? 'INTERNAL')) }}',
                  carrierSelect: '{{ old('carrier', $shipment->carrier ?: 'JNE') }}',
                  customCarrier: '{{ old('carrier_custom', '') }}'
              }">
            @csrf
            @method('PUT')

            {{-- ===== SECTION 1: Info Order & Identitas ===== --}}
            <div class="crm-card mb-6">
                <div class="border-b border-gray-100 pb-3 mb-4">
                    <h2 class="font-poppins font-bold text-base text-gray-900">Referensi Order & Identitas Kode</h2>
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
                        <p class="text-xs text-gray-400 font-medium">Kode Internal Sistem</p>
                        <p class="font-semibold text-gray-900 mt-0.5 font-mono">{{ $shipment->shipment_number }}</p>
                    </div>
                </div>
            </div>

            {{-- ===== SECTION 2: Jenis & Identitas Pengiriman ===== --}}
            <div class="crm-card space-y-4 mb-6">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="font-poppins font-bold text-base text-gray-900">Metode & Identitas Pengiriman</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="crm-label">Jenis Pengiriman <span class="text-primary">*</span></label>
                        <div class="grid grid-cols-2 gap-3 mt-1">
                            <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer transition"
                                   :class="shippingType === 'INTERNAL' ? 'border-primary bg-primary/5 text-gray-900 font-semibold' : 'border-gray-200 text-gray-600'">
                                <input type="radio" name="shipping_type" value="INTERNAL" x-model="shippingType" class="text-primary focus:ring-primary">
                                <span class="text-xs md:text-sm">Armada Perusahaan (Internal)</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer transition"
                                   :class="shippingType === 'EXTERNAL' ? 'border-primary bg-primary/5 text-gray-900 font-semibold' : 'border-gray-200 text-gray-600'">
                                <input type="radio" name="shipping_type" value="EXTERNAL" x-model="shippingType" class="text-primary focus:ring-primary">
                                <span class="text-xs md:text-sm">Ekspedisi Eksternal</span>
                            </label>
                        </div>
                        @error('shipping_type') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <div x-show="shippingType === 'INTERNAL'" x-transition>
                            <label class="crm-label">Kode Pengiriman Internal</label>
                            <input type="text" value="{{ $shipment->shipment_number }}" readonly
                                   class="crm-input bg-gray-100 text-gray-700 cursor-not-allowed font-mono font-semibold">
                        </div>

                        <div x-show="shippingType === 'EXTERNAL'" x-transition class="space-y-3">
                            <div>
                                <label for="carrier" class="crm-label">Jasa Pengiriman / Carrier <span class="text-primary">*</span></label>
                                <select id="carrier" name="carrier" x-model="carrierSelect" :required="shippingType === 'EXTERNAL'"
                                        class="crm-input @error('carrier') border-primary @enderror">
                                    @foreach ($carriers as $c)
                                        <option value="{{ $c }}">{{ $c }}</option>
                                    @endforeach
                                </select>
                                @error('carrier') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="tracking_number" class="crm-label">Nomor Resi Original <span class="text-primary">*</span></label>
                                <input id="tracking_number" type="text" name="tracking_number" value="{{ old('tracking_number', $shipment->tracking_number) }}"
                                       placeholder="Contoh: JNE123456789"
                                       :required="shippingType === 'EXTERNAL'"
                                       class="crm-input font-mono @error('tracking_number') border-primary @enderror">
                                @error('tracking_number') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== SECTION 3: Detail Pengiriman ===== --}}
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

                <div x-show="shippingType === 'INTERNAL'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50/70 p-3 rounded-lg border border-gray-100">
                    <div>
                        <label for="vehicle_id" class="crm-label">Kendaraan Internal</label>
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
                        <label for="driver_id" class="crm-label">Supir / Driver Internal</label>
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
                            <option value="{{ $s->value }}" @selected(old('status', is_object($shipment->status) ? $shipment->status->value : $shipment->status) === $s->value)>
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

            {{-- ===== SECTION 4: Informasi Pembayaran ===== --}}
            <div class="crm-card space-y-4 mb-6" x-data="{ paymentStatus: '{{ old('invoice_payment_status', is_object($shipment->invoice_payment_status) ? $shipment->invoice_payment_status->value : ($shipment->invoice_payment_status ?? 'Belum Dibayar')) }}' }">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="font-poppins font-bold text-base text-gray-900">Informasi Pembayaran</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Status pembayaran invoice dan tanggal pencairan dana.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="invoice_payment_status" class="crm-label">Status Pencairan Invoice <span class="text-primary">*</span></label>
                        <select id="invoice_payment_status" name="invoice_payment_status" x-model="paymentStatus" required
                                class="crm-input @error('invoice_payment_status') border-primary @enderror">
                            @foreach (\App\Enums\InvoicePaymentStatus::cases() as $s)
                                <option value="{{ $s->value }}" @selected(old('invoice_payment_status', is_object($shipment->invoice_payment_status) ? $shipment->invoice_payment_status->value : ($shipment->invoice_payment_status ?? 'Belum Dibayar')) === $s->value)>
                                    {{ $s->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('invoice_payment_status') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="invoice_payment_date" class="crm-label">
                            Tanggal Pencairan <span x-show="paymentStatus === 'Sudah Dibayar'" class="text-primary">*</span>
                        </label>
                        <input id="invoice_payment_date" type="date" name="invoice_payment_date"
                               value="{{ old('invoice_payment_date', $shipment->invoice_payment_date?->format('Y-m-d')) }}"
                               :required="paymentStatus === 'Sudah Dibayar'"
                               class="crm-input @error('invoice_payment_date') border-primary @enderror">
                        @error('invoice_payment_date') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
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