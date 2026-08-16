<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between item-center">
            <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pengiriman') }}
            </h2>
            <a href="{{ route('admin.shipments.create') }}" 
               class="inline-flex item-center px-4 py-2 bg-primary border border-transparent rounded-btn font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition">
                + Buat Pengiriman
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-success p-4 rounded-card">
                    <p class="text-success font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-primary p-4 rounded-card">
                    <p class="text-primary font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Filter Bar -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-4">
                <form method="GET" action="{{ route('admin.shipments.index') }}" class="flex flex-col md:flex-row gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nomor pengiriman, asal, tujuan, atau customer..."
                           class="flex-1 rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                    
                    <select name="customer_id" class="rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                        <option value="">Semua Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" @selected(request('customer_id') == $customer->id)>
                                {{ $customer->company_name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" class="rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                        <option value="">Semua Status</option>
                        <option value="DRAFT" @selected(request('status') == 'DRAFT')>Draf</option>
                        <option value="READY" @selected(request('status') == 'READY')>Siap Kirim</option>
                        <option value="IN_TRANSIT" @selected(request('status') == 'IN_TRANSIT')>Dalam Perjalanan</option>
                        <option value="ARRIVED" @selected(request('status') == 'ARRIVED')>Tiba</option>
                        <option value="DELIVERED" @selected(request('status') == 'DELIVERED')>Terkirim</option>
                        <option value="DELAYED" @selected(request('status') == 'DELAYED')>Terlambat</option>
                        <option value="CANCELLED" @selected(request('status') == 'CANCELLED')>Dibatalkan</option>
                    </select>

                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Shipments Table -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Pengiriman</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rute</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kendaraan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estimasi</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($shipments as $shipment)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $shipment->shipment_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $shipment->customer->company_name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $shipment->origin }} → {{ $shipment->destination }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $shipment->vehicle->plate_number ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColor = match($shipment->status->value) {
                                                'DRAFT' => 'bg-gray-100 text-gray-600',
                                                'READY' => 'bg-blue-100 text-info',
                                                'IN_TRANSIT' => 'bg-blue-100 text-info',
                                                'ARRIVED' => 'bg-green-100 text-success',
                                                'DELIVERED' => 'bg-green-100 text-success',
                                                'DELAYED' => 'bg-yellow-100 text-yellow-700',
                                                'CANCELLED' => 'bg-red-100 text-primary',
                                                default => 'bg-gray-100 text-gray-600',
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-badge {{ $statusColor }}">
                                            {{ $shipment->status->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.shipments.show', $shipment) }}" class="text-info hover:underline">Lihat</a>
                                        <a href="{{ route('admin.shipments.edit', $shipment) }}" class="text-warning hover:underline">Ubah</a>
                                        <form action="{{ route('admin.shipments.destroy', $shipment) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus pengiriman ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-primary hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        Tidak ada data pengiriman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $shipments->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>