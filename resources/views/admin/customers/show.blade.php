<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Customer Detail') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.customers.edit', $customer) }}" 
                   class="inline-flex items-center px-4 py-2 bg-warning border border-transparent rounded-btn font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 transition">
                    Edit
                </a>
                <a href="{{ route('admin.customers.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-btn font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Customer Info Card -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-6">
                <h3 class="font-poppins font-semibold text-lg text-gray-800 mb-4">Informasi Perusahaan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Nama Perusahaan</p>
                        <p class="text-base font-medium text-gray-900">{{ $customer->company_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Nama PIC</p>
                        <p class="text-base font-medium text-gray-900">{{ $customer->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="text-base font-medium text-gray-900">{{ $customer->phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-base font-medium text-gray-900">{{ $customer->email ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500">Alamat</p>
                        <p class="text-base font-medium text-gray-900">{{ $customer->address }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Kota / Provinsi</p>
                        <p class="text-base font-medium text-gray-900">{{ $customer->city }}, {{ $customer->province }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Postal Code</p>
                        <p class="text-base font-medium text-gray-900">{{ $customer->postal_code ?? '-' }}</p>
                    </div>
                    @if($customer->notes)
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500">Notes</p>
                        <p class="text-base text-gray-700">{{ $customer->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- User Account Info -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-6">
                <h3 class="font-poppins font-semibold text-lg text-gray-800 mb-4">Akun Login</h3>
                
                @if($customer->user)
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email Akun</p>
                            <p class="text-base font-medium text-gray-900">{{ $customer->user->email }}</p>
                            <p class="text-xs text-success mt-1">Aktif · Role: {{ $customer->user->role->label() }}</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-gray-100 rounded-full">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        </div>
                        <div>
                            <p class="text-base font-medium text-gray-700">Belum ada akun login</p>
                            <p class="text-xs text-gray-500 mt-1">Customer ini tidak dapat login ke portal.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white shadow-soft sm:rounded-card p-5 border-l-4 border-info">
                    <p class="text-xs font-medium text-gray-500 uppercase">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $customer->orders->count() }}</p>
                </div>
                <div class="bg-white shadow-soft sm:rounded-card p-5 border-l-4 border-gray-400">
                    <p class="text-xs font-medium text-gray-500 uppercase">Total Shipments</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $customer->shipments->count() }}</p>
                </div>
                <div class="bg-white shadow-soft sm:rounded-card p-5 border-l-4 border-success">
                    <p class="text-xs font-medium text-gray-500 uppercase">Contacts</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $customer->contacts->count() }}</p>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-poppins font-semibold text-lg text-gray-800">Recent Orders</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($customer->orders->take(5) as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->order_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->order_date->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-badge bg-gray-100 text-gray-600">
                                            {{ $order->status->value }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada order.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Shipments -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-poppins font-semibold text-lg text-gray-800">Recent Shipments</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shipment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Route</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($customer->shipments->take(5) as $shipment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $shipment->shipment_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $shipment->origin }} → {{ $shipment->destination }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColor = match($shipment->status->value) {
                                                'IN_TRANSIT' => 'bg-blue-100 text-info',
                                                'DELIVERED', 'ARRIVED' => 'bg-green-100 text-success',
                                                'DELAYED' => 'bg-red-100 text-primary',
                                                default => 'bg-gray-100 text-gray-600',
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-badge {{ $statusColor }}">
                                            {{ $shipment->status->label() }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada shipment.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>