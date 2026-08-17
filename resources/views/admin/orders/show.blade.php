<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Order') }} — {{ $order->order_number }}
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.orders.edit', $order) }}" class="btn-secondary">Ubah Order</a>
                <a href="{{ route('admin.orders.index') }}" class="btn-ghost">Kembali</a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">

        <x-page-header title="Detail Order {{ $order->order_number }}" description="Informasi detail pesanan dan daftar pengiriman terkait.">
            <x-slot name="actions">
                <x-badge :status="$order->status" />
                <a href="{{ route('admin.orders.index') }}" class="btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali</span>
                </a>
            </x-slot>
        </x-page-header>

        <!-- Order Info Card -->
        <div class="crm-card space-y-4">
            <div class="border-b border-gray-100 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="font-poppins font-bold text-base text-gray-900">{{ $order->order_number }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Tanggal Order: {{ $order->order_date ? $order->order_date->format('d M Y') : '-' }}</p>
                </div>
                <x-badge :status="$order->status" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400 font-medium">Pelanggan</p>
                    <a href="{{ route('admin.customers.show', $order->customer_id) }}" class="font-bold text-info hover:underline mt-0.5 block">
                        {{ $order->customer->company_name ?? '-' }}
                    </a>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium">Total Item</p>
                    <p class="font-semibold text-gray-900 mt-0.5">{{ $order->items->count() }} item</p>
                </div>
                @if($order->notes)
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-400 font-medium">Catatan</p>
                        <p class="text-sm text-gray-700 mt-0.5">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Item Table -->
        <div class="crm-card p-0 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-poppins font-bold text-base text-gray-900">Daftar Barang (Order Items)</h3>
            </div>
            <div class="crm-table-container">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>SKU</th>
                            <th>Jumlah</th>
                            <th>Berat Total</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($order->items as $item)
                            <tr>
                                <td class="font-semibold text-gray-900">{{ $item->product->name ?? '-' }}</td>
                                <td class="text-xs text-gray-600">{{ $item->product->sku ?? '-' }}</td>
                                <td class="text-xs text-gray-800 font-medium">{{ number_format($item->quantity, 0) }} {{ $item->unit }}</td>
                                <td class="text-xs text-gray-800 font-medium">{{ number_format($item->weight, 0) }} kg</td>
                                <td class="text-xs text-gray-500">{{ $item->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-400">Tidak ada item barang dalam pesanan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Shipments Created for this Order -->
        @if ($order->shipments && $order->shipments->count() > 0)
        <div class="crm-card p-0 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-poppins font-bold text-base text-gray-900">Pengiriman Terkait</h3>
            </div>
            <div class="crm-table-container">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>No. Pengiriman</th>
                            <th>Rute</th>
                            <th>Kendaraan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->shipments as $shipment)
                            <tr>
                                <td class="font-bold text-gray-900">
                                    <a href="{{ route('admin.shipments.show', $shipment) }}" class="hover:text-primary transition">
                                        {{ $shipment->shipment_number }}
                                    </a>
                                </td>
                                <td class="text-xs text-gray-600">{{ $shipment->origin }} &rarr; {{ $shipment->destination }}</td>
                                <td class="text-xs text-gray-600">{{ $shipment->vehicle->plate_number ?? '-' }}</td>
                                <td>
                                    <x-badge :status="$shipment->status" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</x-app-layout>