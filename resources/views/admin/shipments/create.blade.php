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
                  shippingType: '{{ old('shipping_type', 'INTERNAL') }}',
                  carrierSelect: '{{ old('carrier', 'JNE') }}',
                  customCarrier: '',
                  orders: {{ Js::from($orders->map(fn($o) => [
                      'id' => $o->id,
                      'order_number' => $o->order_number,
                      'customer_id' => $o->customer_id,
                      'customer_name' => $o->customer->company_name ?? '-',
                      'customer_prefix' => $o->customer->shipment_code_prefix ?: config('shipment.prefix', 'PKM'),
                      'next_code_preview' => \App\Services\ShipmentCodeGenerator::generate($o->customer),
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
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
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

            {{-- ===== SECTION 2: Metode & Identitas Pengiriman ===== --}}
            <div class="crm-card space-y-4">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="font-poppins font-bold text-base text-gray-900">2. Metode & Identitas Pengiriman</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Pengiriman menggunakan Jasa Ekspedisi Eksternal mitra (misal: AEI — PT. Antar Exprindo Indah).</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Jenis Pengiriman --}}
                    <div>
                        <label class="crm-label">Jenis Pengiriman <span class="text-primary">*</span></label>
                        <input type="hidden" name="shipping_type" value="EXTERNAL">
                        <input type="text" value="Ekspedisi Eksternal" readonly class="crm-input bg-gray-100 text-gray-700 cursor-not-allowed font-semibold">
                        <p class="text-[11px] text-gray-500 mt-1">Seluruh pengiriman secara otomatis dialokasikan ke Ekspedisi Eksternal.</p>
                    </div>

                    {{-- Penyedia Ekspedisi --}}
                    <div>
                        <label for="expedition_provider_id" class="crm-label">Penyedia Ekspedisi <span class="text-primary">*</span></label>
                        <select id="expedition_provider_id" name="expedition_provider_id" required
                                class="crm-input @error('expedition_provider_id') border-primary @enderror">
                            <option value="">-- Pilih Penyedia Ekspedisi --</option>
                            @foreach ($expeditionProviders as $p)
                                <option value="{{ $p->id }}" @selected(old('expedition_provider_id', $expeditionProviders->firstWhere('code', 'AEI')?->id) == $p->id)>
                                    {{ $p->name }} ({{ $p->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('expedition_provider_id') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nomor Resi --}}
                    <div>
                        <label for="tracking_number" class="crm-label">Nomor Resi <span class="text-primary">*</span></label>
                        <input id="tracking_number" type="text" name="tracking_number" value="{{ old('tracking_number') }}"
                               placeholder="Masukkan nomor resi, contoh: AEI123456789" required
                               class="crm-input font-mono @error('tracking_number') border-primary @enderror">
                        <p class="text-[11px] text-gray-500 mt-1">Nomor resi asli dari penyedia ekspedisi untuk pelacakan internal & publik.</p>
                        @error('tracking_number') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nomor Referensi Pengiriman Sistem --}}
                    <div>
                        <label for="internal_code_display" class="crm-label">Nomor Pengiriman (Referensi Sistem)</label>
                        <div class="relative">
                            <input id="internal_code_display" type="text"
                                   :value="selectedOrder ? selectedOrder.next_code_preview : '{{ $nextInternalCode }}'" readonly
                                   class="crm-input bg-gray-100 text-gray-700 cursor-not-allowed font-mono font-semibold">
                            <span class="absolute right-3 top-2.5 text-xs text-gray-400 font-medium bg-gray-200 px-2 py-0.5 rounded">Auto-Generated</span>
                        </div>
                        <template x-if="selectedOrder">
                            <p class="text-[11px] text-gray-600 mt-1">
                                Awalan Kode Pelanggan: <strong class="font-mono text-gray-900" x-text="selectedOrder.customer_prefix"></strong> | Pratinjau Kode: <strong class="font-mono text-primary" x-text="selectedOrder.next_code_preview"></strong>
                            </p>
                        </template>
                    </div>
                </div>
            </div>

            {{-- ===== SECTION 3: Detail Operasional Pengiriman ===== --}}
            <div class="crm-card space-y-4">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="font-poppins font-bold text-base text-gray-900">3. Detail Operasional & Rute</h2>
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

            {{-- ===== SECTION 4: Informasi Pembayaran ===== --}}
            <div class="crm-card space-y-4" x-data="{ paymentStatus: '{{ old('invoice_payment_status', 'Belum Dibayar') }}' }">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="font-poppins font-bold text-base text-gray-900">4. Informasi Pembayaran</h2>
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