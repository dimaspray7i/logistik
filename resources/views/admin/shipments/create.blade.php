<x-app-layout>
    <div class="space-y-6">

        <x-page-header title="Buat Pengiriman Baru" description="Buat pengiriman baru berdasarkan order yang sudah ada.">
            <x-slot name="actions">
                <a href="{{ route('admin.shipments.index') }}" class="btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali</span>
                </a>
            </x-slot>
        </x-page-header>

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

            {{-- ===== SECTION 1: Pilih Order ===== --}}
            <div class="crm-card space-y-4">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="font-poppins font-bold text-base text-gray-900">1. Pilih Order</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Pilih order yang akan dijadikan dasar pengiriman ini.</p>
                </div>

                <div>
                    <label for="order_id" class="crm-label">Order <span class="text-primary">*</span></label>
                    <select id="order_id" name="order_id" x-model="selectedOrderId" required
                            class="crm-input @error('order_id') border-primary @enderror">
                        <option value="">-- Pilih Order --</option>
                        @foreach ($orders as $order)
                            <option value="{{ $order->id }}" @selected(old('order_id', $selectedOrder->id ?? '') == $order->id)>
                                {{ $order->order_number }} — {{ $order->customer->company_name ?? '-' }} ({{ $order->items->count() }} item, {{ number_format($order->items->sum('weight'), 0) }} Kg)
                            </option>
                        @endforeach
                    </select>
                    @error('order_id') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div x-show="selectedOrder" x-transition class="bg-blue-50 border border-blue-100 rounded-card p-4">
                    <template x-if="selectedOrder">
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <p class="text-xs text-gray-400 font-medium">Customer</p>
                                <p class="font-semibold text-gray-800 mt-0.5" x-text="selectedOrder.customer_name"></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium">Jumlah Item</p>
                                <p class="font-semibold text-gray-800 mt-0.5"><span x-text="selectedOrder.items_count"></span> item</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium">Total Berat</p>
                                <p class="font-semibold text-gray-800 mt-0.5"><span x-text="selectedOrder.total_weight"></span> Kg</p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- ===== SECTION 2: Detail Pengiriman ===== --}}
            <div class="crm-card space-y-4">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="font-poppins font-bold text-base text-gray-900">2. Detail Pengiriman</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="origin" class="crm-label">Kota Asal <span class="text-primary">*</span></label>
                        <input id="origin" type="text" name="origin" value="{{ old('origin') }}" placeholder="Contoh: Surabaya" required
                               class="crm-input @error('origin') border-primary @enderror">
                        @error('origin') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="destination" class="crm-label">Kota Tujuan <span class="text-primary">*</span></label>
                        <input id="destination" type="text" name="destination" value="{{ old('destination') }}" placeholder="Contoh: Jakarta" required
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
                                <option value="{{ $vehicle->id }}" @selected(old('vehicle_id') == $vehicle->id)>
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
                                <option value="{{ $driver->id }}" @selected(old('driver_id') == $driver->id)>
                                    {{ $driver->name }} ({{ $driver->phone }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="departure_date" class="crm-label">Tanggal Berangkat</label>
                        <input id="departure_date" type="datetime-local" name="departure_date" value="{{ old('departure_date') }}"
                               class="crm-input">
                    </div>
                    <div>
                        <label for="estimated_arrival" class="crm-label">Estimasi Tiba</label>
                        <input id="estimated_arrival" type="datetime-local" name="estimated_arrival" value="{{ old('estimated_arrival') }}"
                               class="crm-input">
                    </div>
                </div>

                <div>
                    <label for="status" class="crm-label">Status Awal <span class="text-primary">*</span></label>
                    <select id="status" name="status" required class="crm-input">
                        <option value="DRAFT" @selected(old('status', 'DRAFT') == 'DRAFT')>Draf</option>
                        <option value="READY" @selected(old('status') == 'READY')>Siap Kirim</option>
                        <option value="IN_TRANSIT" @selected(old('status') == 'IN_TRANSIT')>Dalam Perjalanan</option>
                    </select>
                </div>

                <div>
                    <label for="notes" class="crm-label">Catatan</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Catatan tambahan mengenai pengiriman..."
                              class="crm-input">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- ===== SECTION 3: Informasi Pembayaran ===== --}}
            <div class="crm-card space-y-4" x-data="{ paymentStatus: '{{ old('invoice_payment_status', 'Belum Dibayar') }}' }">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="font-poppins font-bold text-base text-gray-900">3. Informasi Pembayaran</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Status pembayaran invoice dan tanggal pencairan dana.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="invoice_payment_status" class="crm-label">Status Pencairan Invoice <span class="text-primary">*</span></label>
                        <select id="invoice_payment_status" name="invoice_payment_status" x-model="paymentStatus" required
                                class="crm-input @error('invoice_payment_status') border-primary @enderror">
                            @foreach (\App\Enums\InvoicePaymentStatus::cases() as $s)
                                <option value="{{ $s->value }}" @selected(old('invoice_payment_status', 'Belum Dibayar') === $s->value)>
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
                               value="{{ old('invoice_payment_date') }}"
                               :required="paymentStatus === 'Sudah Dibayar'"
                               class="crm-input @error('invoice_payment_date') border-primary @enderror">
                        @error('invoice_payment_date') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- ===== ACTION BUTTONS ===== --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.shipments.index') }}" class="btn-ghost">Batal</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Simpan Pengiriman</span>
                </button>
            </div>

        </form>

    </div>
</x-app-layout>