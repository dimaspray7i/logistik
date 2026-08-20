<x-customer-layout>
    <div class="space-y-5">

        <!-- Page Header Card -->
        <div class="bg-white rounded-[16px] border border-gray-100 shadow-soft px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight leading-snug">Pengiriman Saya</h1>
                <p class="text-xs text-gray-400 mt-0.5">Pantau seluruh status pengiriman logistik Anda secara real-time</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <span class="text-xs font-semibold text-gray-500 bg-gray-50 px-3 py-1.5 rounded-btn border border-gray-100">
                    Total: {{ $shipments->total() }} Pengiriman
                </span>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="crm-card !p-4">
            <form method="GET" action="{{ route('customer.shipments.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nomor, asal, atau tujuan..."
                           class="crm-input !pl-9">
                </div>
                <div class="w-full sm:w-52">
                    <select name="status" class="crm-input">
                        <option value="">Semua Status</option>
                        @foreach (\App\Enums\ShipmentStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(request('status') == $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary !text-xs !py-2 shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filter</span>
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('customer.shipments.index') }}" class="btn-ghost !text-xs !py-2 shrink-0">Reset</a>
                @endif
            </form>
        </div>

        <!-- Desktop Table -->
        <div class="crm-card !p-0 overflow-hidden hidden md:block">
            <div class="overflow-x-auto">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>No. Pengiriman</th>
                            <th>Rute Pengiriman</th>
                            <th>Status</th>
                            <th>Estimasi Tiba</th>
                            <th>Progress</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($shipments as $shipment)
                            @php
                                $badgeClass = match($shipment->status->value) {
                                    'DRAFT' => 'badge-draft',
                                    'READY' => 'badge-ready',
                                    'IN_TRANSIT' => 'badge-in-transit',
                                    'ARRIVED' => 'badge-arrived',
                                    'DELIVERED' => 'badge-delivered',
                                    'DELAYED' => 'badge-delayed',
                                    'CANCELLED' => 'badge-cancelled',
                                    default => 'badge-draft',
                                };
                                $progress = match($shipment->status->value) {
                                    'DRAFT' => 10, 'READY' => 25, 'IN_TRANSIT' => 60, 'ARRIVED' => 85, 'DELIVERED' => 100, 'CANCELLED' => 0, default => 0,
                                };
                            @endphp
                            <tr>
                                <td class="font-semibold text-gray-900">
                                    {{ $shipment->shipment_number }}
                                </td>
                                <td class="text-gray-600 text-xs">
                                    <span class="font-medium text-gray-900">{{ $shipment->origin }}</span>
                                    <span class="text-gray-400 mx-1">&rarr;</span>
                                    <span class="font-medium text-gray-900">{{ $shipment->destination }}</span>
                                </td>
                                <td>
                                    <span class="badge-pill {{ $badgeClass }}">{{ $shipment->status->label() }}</span>
                                </td>
                                <td class="text-xs text-gray-500">
                                    {{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y') : '-' }}
                                </td>
                                <td class="w-28">
                                    <div class="flex items-center gap-1.5">
                                        <div class="flex-1 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-primary h-1.5 rounded-full" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-semibold text-gray-500 shrink-0">{{ $progress }}%</span>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('customer.shipments.show', $shipment) }}" class="btn-ghost !text-xs !py-1 !px-2.5 text-primary font-medium">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-400 text-xs">
                                    Belum ada data pengiriman yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($shipments->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">{{ $shipments->links() }}</div>
            @endif
        </div>

        <!-- Mobile Card Stack -->
        <div class="space-y-3 md:hidden">
            @forelse ($shipments as $shipment)
                <a href="{{ route('customer.shipments.show', $shipment) }}" class="crm-card block hover:border-primary/40 transition">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-sm font-bold text-gray-900">{{ $shipment->shipment_number }}</span>
                        @php
                            $badgeClass = match($shipment->status->value) {
                                'DRAFT' => 'badge-draft',
                                'READY' => 'badge-ready',
                                'IN_TRANSIT' => 'badge-in-transit',
                                'ARRIVED' => 'badge-arrived',
                                'DELIVERED' => 'badge-delivered',
                                'DELAYED' => 'badge-delayed',
                                'CANCELLED' => 'badge-cancelled',
                                default => 'badge-draft',
                            };
                        @endphp
                        <span class="badge-pill {{ $badgeClass }}">{{ $shipment->status->label() }}</span>
                    </div>
                    <p class="text-xs text-gray-600 font-medium">{{ $shipment->origin }} &rarr; {{ $shipment->destination }}</p>
                    <p class="text-xs text-gray-400 mt-1">Est. tiba: {{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y') : '-' }}</p>
                </a>
            @empty
                <div class="crm-card text-center py-10">
                    <p class="text-xs text-gray-400">Belum ada data pengiriman.</p>
                </div>
            @endforelse
            @if($shipments->hasPages())
                <div class="pt-2">{{ $shipments->links() }}</div>
            @endif
        </div>

    </div>
</x-customer-layout>