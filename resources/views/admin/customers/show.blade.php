<x-app-layout>
    <div class="space-y-6">

        <!-- Top Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <a href="{{ route('admin.customers.index') }}" class="btn-ghost text-xs self-start">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali ke Daftar Pelanggan</span>
            </a>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.customers.edit', $customer) }}" class="btn-secondary">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Ubah Data</span>
                </a>
            </div>
        </div>

        <x-page-header title="{{ $customer->company_name }}" description="Detail profil perusahaan, kontak, dan riwayat pesanan/pengiriman." />

        <!-- 3 Stats KPI Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="crm-card border-l-4 border-info">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Pesanan</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($customer->orders->count()) }}</p>
            </div>
            <div class="crm-card border-l-4 border-primary">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Pengiriman</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($customer->shipments->count()) }}</p>
            </div>
            <div class="crm-card border-l-4 border-success">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Kontak PIC</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($customer->contacts->count()) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Info Card (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Info -->
                <div class="crm-card space-y-4">
                    <div class="border-b border-gray-100 pb-3">
                        <h2 class="font-poppins font-bold text-base text-gray-900">Informasi Perusahaan</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm">
                        <div>
                            <p class="text-gray-400 font-medium">Nama Perusahaan</p>
                            <p class="font-semibold text-gray-900 mt-0.5">{{ $customer->company_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 font-medium">Nama PIC Utama</p>
                            <p class="font-semibold text-gray-900 mt-0.5">{{ $customer->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 font-medium">Telepon / WA</p>
                            <p class="font-semibold text-gray-900 mt-0.5">{{ $customer->phone }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 font-medium">Email</p>
                            <p class="font-semibold text-gray-900 mt-0.5">{{ $customer->email ?? '-' }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-gray-400 font-medium">Alamat Lengkap</p>
                            <p class="font-semibold text-gray-900 mt-0.5">{{ $customer->address }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 font-medium">Kota & Provinsi</p>
                            <p class="font-semibold text-gray-900 mt-0.5">{{ $customer->city }}, {{ $customer->province }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 font-medium">Kode Pos</p>
                            <p class="font-semibold text-gray-900 mt-0.5">{{ $customer->postal_code ?? '-' }}</p>
                        </div>
                    </div>

                    @if ($customer->notes)
                        <div class="pt-3 border-t border-gray-100">
                            <p class="text-xs text-gray-400 font-medium">Catatan Khusus</p>
                            <p class="text-xs text-gray-700 mt-1 bg-gray-50 p-2.5 rounded-btn border border-gray-100">{{ $customer->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Recent Orders Table Card -->
                <div class="crm-card p-0 overflow-hidden">
                    <div class="p-4 border-b border-gray-100">
                        <h2 class="font-poppins font-bold text-sm text-gray-900">Pesanan Terbaru</h2>
                    </div>
                    <div class="crm-table-container">
                        <table class="crm-table">
                            <thead>
                                <tr>
                                    <th>Nomor Order</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customer->orders->take(5) as $order)
                                    <tr>
                                        <td class="font-bold text-gray-900">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-primary transition">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td class="text-xs text-gray-600">{{ $order->order_date ? $order->order_date->format('d M Y') : '-' }}</td>
                                        <td>
                                            <x-badge :status="$order->status" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-6 text-center text-xs text-gray-400">Belum ada data pesanan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar Card (1 Col) -->
            <div class="space-y-6">
                <!-- User Account Info Card -->
                <div class="crm-card space-y-3">
                    <h2 class="font-poppins font-bold text-sm text-gray-900 border-b border-gray-100 pb-2">Status Akun Portal</h2>
                    
                    @if($customer->user)
                        <div class="flex items-center gap-3 p-3 rounded-btn bg-emerald-50 border border-emerald-100">
                            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white font-bold flex items-center justify-center text-xs shrink-0">
                                ✓
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-gray-900 truncate">{{ $customer->user->email }}</p>
                                <p class="text-[10px] text-emerald-700 font-semibold mt-0.5">Akun Portal Aktif</p>
                            </div>
                        </div>
                    @else
                        <div class="p-3 rounded-btn bg-gray-50 border border-gray-100 text-center">
                            <p class="text-xs font-semibold text-gray-700">Belum Memiliki Akun Login</p>
                            <p class="text-[11px] text-gray-400 mt-1">Customer belum dapat login ke portal.</p>
                        </div>
                    @endif
                </div>

                <!-- Recent Shipments Overview -->
                <div class="crm-card p-0 overflow-hidden">
                    <div class="p-4 border-b border-gray-100">
                        <h2 class="font-poppins font-bold text-sm text-gray-900">Pengiriman Terbaru</h2>
                    </div>
                    <div class="crm-table-container">
                        <table class="crm-table">
                            <thead>
                                <tr>
                                    <th>Shipment ID</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customer->shipments->take(5) as $shipment)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.shipments.show', $shipment) }}" class="font-bold text-xs text-gray-900 hover:text-primary transition">
                                                {{ $shipment->shipment_number }}
                                            </a>
                                            <p class="text-[10px] text-gray-500 mt-0.5">{{ $shipment->origin }} → {{ $shipment->destination }}</p>
                                        </td>
                                        <td>
                                            <x-badge :status="$shipment->status" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="py-6 text-center text-xs text-gray-400">Belum ada pengiriman.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>