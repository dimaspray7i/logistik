<x-app-layout>
    <x-slot name="header">
        {{ __('Customer Portal') }}
    </x-slot>

    <div class="py-8 space-y-8">

        <!-- WELCOME BANNER -->
        <div class="bg-white rounded-card shadow-soft border border-gray-100 p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-poppins text-2xl font-bold text-gray-900">
                    Welcome back, {{ auth()->user()->name }}!
                </h2>
                <p class="text-gray-500 mt-1">
                    {{ $customer->company_name }} &middot; {{ $customer->city }}, {{ $customer->province }}
                </p>
            </div>
            <span class="inline-flex item-center px-3 py-1 rounded-badge bg-green-100 text-success text-sm font-semibold">
                Akun Terverifikasi
            </span>
        </div>

        <!-- STATISTICS CARDS (DATA MILIK CUSTOMER SAJA) -->
        <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-5 gap-4">

            <div class="bg-white shadow-soft sm:rounded-card p-5 border-l-4 border-info">
                <p class="text-xs font-medium text-gray-500 uppercase">My Orders</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['my_orders'] }}</p>
            </div>

            <div class="bg-white shadow-soft sm:rounded-card p-5 border-l-4 border-gray-400">
                <p class="text-xs font-medium text-gray-500 uppercase">My Shipments</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['my_shipments'] }}</p>
            </div>

            <div class="bg-white shadow-soft sm:rounded-card p-5 border-l-4 border-info">
                <p class="text-xs font-medium text-gray-500 uppercase">Dalam Perjalanan</p>
                <p class="text-2xl font-bold text-info mt-1">{{ $stats['in_transit'] }}</p>
            </div>

            <div class="bg-white shadow-soft sm:rounded-card p-5 border-l-4 border-success">
                <p class="text-xs font-medium text-gray-500 uppercase">Terkirim</p>
                <p class="text-2xl font-bold text-success mt-1">{{ $stats['delivered'] }}</p>
            </div>

            <div class="bg-white shadow-soft sm:rounded-card p-5 border-l-4 border-primary">
                <p class="text-xs font-medium text-gray-500 uppercase">Tertunda</p>
                <p class="text-2xl font-bold text-primary mt-1">{{ $stats['delayed'] }}</p>
            </div>

        </div>

        <!-- RECENT SHIPMENTS (HANYA MILIK CUSTOMER INI) -->
        <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between item-center">
                <h3 class="font-poppins font-semibold text-lg text-gray-800">Pengiriman Terbaru Saya</h3>
                <span class="text-xs text-gray-400">Data terisolasi untuk {{ $customer->company_name }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengiriman</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rute</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estimasi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($recentShipments as $shipment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $shipment->shipment_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $shipment->origin }} &rarr; {{ $shipment->destination }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($shipment->total_weight, 0) }} Kg
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada pengiriman untuk akun Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>