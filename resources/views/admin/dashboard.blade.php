<x-app-layout>
    <x-slot name="header">
        <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 space-y-8">
        
        <!-- SECTION 1: STATISTICS CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            
            <!-- Card: Total Customers -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card p-5 border-l-4 border-primary">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Total Customers</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_customers'] }}</p>
                    </div>
                    <div class="p-2 bg-red-50 rounded-lg">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Card: Total Orders -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card p-5 border-l-4 border-info">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_orders'] }}</p>
                    </div>
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-6 h-6 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Card: Total Shipments -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card p-5 border-l-4 border-gray-400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Total Shipments</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_shipments'] }}</p>
                    </div>
                    <div class="p-2 bg-gray-100 rounded-lg">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Card: In Transit -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card p-5 border-l-4 border-info">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">In Transit</p>
                        <p class="text-2xl font-bold text-info mt-1">{{ $stats['in_transit'] }}</p>
                    </div>
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-6 h-6 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Card: Delivered -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card p-5 border-l-4 border-success">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Delivered</p>
                        <p class="text-2xl font-bold text-success mt-1">{{ $stats['delivered'] }}</p>
                    </div>
                    <div class="p-2 bg-green-50 rounded-lg">
                        <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Card: Delayed -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card p-5 border-l-4 border-primary">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Delayed</p>
                        <p class="text-2xl font-bold text-primary mt-1">{{ $stats['delayed'] }}</p>
                    </div>
                    <div class="p-2 bg-red-50 rounded-lg">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

        </div>

        <!-- SECTION 2: RECENT SHIPMENTS TABLE -->
        <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-poppins font-semibold text-lg text-gray-800">Recent Shipments</h3>
                <a href="#" class="text-sm text-info hover:underline">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipment</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estimasi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($recentShipments as $shipment)
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColor = match($shipment->status->value) {
                                            'IN_TRANSIT' => 'bg-blue-100 text-info',
                                            'DELIVERED' => 'bg-green-100 text-success',
                                            'READY' => 'bg-yellow-100 text-warning',
                                            'DELAYED' => 'bg-red-100 text-primary',
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
                                    Belum ada data shipment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>