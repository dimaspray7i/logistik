<x-customer-layout>
    <div class="space-y-5">

        <!-- Page Header Card -->
        <div class="bg-white rounded-[16px] border border-gray-100 shadow-soft px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight leading-snug">
                    Pesanan Saya
                </h1>
                <p class="text-xs text-gray-400 mt-0.5">
                    Daftar seluruh pesanan barang dan status pengiriman terkait
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-gray-500 bg-gray-50 px-3 py-1.5 rounded-btn border border-gray-100">
                    Total: {{ $orders->total() }} Pesanan
                </span>
            </div>
        </div>

        <!-- Search & Filter Card -->
        <div class="crm-card !p-4">
            <form method="GET" action="{{ route('customer.orders.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nomor pesanan..."
                           class="crm-input !pl-9">
                </div>
                <div class="w-full sm:w-48">
                    <select name="status" class="crm-input">
                        <option value="">Semua Status</option>
                        @foreach (\App\Enums\OrderStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(request('status') == $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary !text-xs !py-2 shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filter</span>
                </button>
            </form>
        </div>

        <!-- Desktop Table -->
        <div class="crm-card !p-0 overflow-hidden hidden md:block">
            <div class="overflow-x-auto">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Tanggal Pesanan</th>
                            <th>Jumlah Item</th>
                            <th>Status</th>
                            <th>Pengiriman Terkait</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="font-semibold text-gray-900">{{ $order->order_number }}</td>
                                <td class="text-gray-600 text-xs">{{ $order->order_date->format('d M Y') }}</td>
                                <td class="text-gray-600 text-xs">{{ $order->items_count }} item</td>
                                <td>
                                    @php
                                        $badgeClass = match($order->status->value) {
                                            'PENDING' => 'badge-pending',
                                            'PROCESSING' => 'badge-in-transit',
                                            'COMPLETED' => 'badge-delivered',
                                            'CANCELLED' => 'badge-cancelled',
                                            default => 'badge-draft',
                                        };
                                    @endphp
                                    <span class="badge-pill {{ $badgeClass }}">{{ $order->status->label() }}</span>
                                </td>
                                <td class="text-xs text-gray-600">
                                    @if($order->shipments->count() > 0)
                                        <span class="font-medium text-gray-900">{{ $order->shipments->count() }} pengiriman</span>
                                    @else
                                        <span class="text-gray-400">Belum dijadwalkan</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('customer.orders.show', $order) }}" class="btn-ghost !text-xs !py-1 !px-2.5 text-primary font-medium">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-xs">
                                    Belum ada transaksi pesanan yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">{{ $orders->links() }}</div>
            @endif
        </div>

        <!-- Mobile Card Stack -->
        <div class="space-y-3 md:hidden">
            @forelse ($orders as $order)
                <a href="{{ route('customer.orders.show', $order) }}" class="crm-card block hover:border-primary/40 transition">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-sm font-bold text-gray-900">{{ $order->order_number }}</span>
                        @php
                            $badgeClass = match($order->status->value) {
                                'PENDING' => 'badge-pending',
                                'PROCESSING' => 'badge-in-transit',
                                'COMPLETED' => 'badge-delivered',
                                'CANCELLED' => 'badge-cancelled',
                                default => 'badge-draft',
                            };
                        @endphp
                        <span class="badge-pill {{ $badgeClass }}">{{ $order->status->label() }}</span>
                    </div>
                    <p class="text-xs text-gray-500">{{ $order->order_date->format('d M Y') }} &middot; {{ $order->items_count }} item</p>
                    <p class="text-xs text-gray-400 mt-2">
                        {{ $order->shipments->count() > 0 ? $order->shipments->count() . ' pengiriman terkait' : 'Belum ada pengiriman' }}
                    </p>
                </a>
            @empty
                <div class="crm-card text-center py-10">
                    <p class="text-xs text-gray-400">Belum ada data pesanan.</p>
                </div>
            @endforelse
            @if($orders->hasPages())
                <div class="pt-2">{{ $orders->links() }}</div>
            @endif
        </div>

    </div>
</x-customer-layout>