<x-app-layout>
    <div class="space-y-6">

        <x-page-header title="Pesanan / Order" description="Kelola daftar pesanan pengiriman dari pelanggan.">
            <x-slot name="actions">
                <a href="{{ route('admin.orders.create') }}" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Tambah Pesanan</span>
                </a>
            </x-slot>
        </x-page-header>

        <!-- Search & Filter Card -->
        <div class="crm-card p-4">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nomor pesanan atau nama pelanggan..."
                           class="crm-input pl-10">
                </div>
                
                <select name="customer_id" class="crm-input md:w-48">
                    <option value="">Semua Pelanggan</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" @selected(request('customer_id') == $customer->id)>
                            {{ $customer->company_name }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="crm-input md:w-44">
                    <option value="">Semua Status</option>
                    <option value="PENDING" @selected(request('status') == 'PENDING')>Menunggu</option>
                    <option value="PROCESSING" @selected(request('status') == 'PROCESSING')>Diproses</option>
                    <option value="COMPLETED" @selected(request('status') == 'COMPLETED')>Selesai</option>
                    <option value="CANCELLED" @selected(request('status') == 'CANCELLED')>Dibatalkan</option>
                </select>

                <button type="submit" class="btn-secondary shrink-0">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>Filter</span>
                </button>
            </form>
        </div>

        <!-- Orders Table Card -->
        <div class="crm-card p-0 overflow-hidden">
            <div class="crm-table-container">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>Nomor Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Jumlah Item</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="font-bold text-gray-900">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-primary transition">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="text-xs text-gray-800 font-medium">
                                    {{ $order->customer->company_name ?? '-' }}
                                </td>
                                <td class="text-xs text-gray-600">
                                    {{ $order->order_date ? $order->order_date->format('d M Y') : '-' }}
                                </td>
                                <td class="text-xs text-gray-600 font-medium">
                                    {{ $order->items_count ?? $order->items->count() }} item
                                </td>
                                <td>
                                    <x-badge :status="$order->status" />
                                </td>
                                <td class="text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('admin.orders.show', $order) }}" title="Detail" class="p-1.5 rounded-btn text-gray-400 hover:text-info hover:bg-blue-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                        <a href="{{ route('admin.orders.edit', $order) }}" title="Ubah" class="p-1.5 rounded-btn text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus pesanan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus" class="p-1.5 rounded-btn text-gray-400 hover:text-primary hover:bg-red-50 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-500">
                                    <p class="text-sm">Tidak ada data pesanan yang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($orders->hasPages())
                <div class="p-4 border-t border-gray-100 bg-white">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>