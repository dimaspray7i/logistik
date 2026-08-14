<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Detail') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.orders.edit', $order) }}" 
                   class="inline-flex items-center px-4 py-2 bg-warning border border-transparent rounded-btn font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 transition">
                    Edit
                </a>
                <a href="{{ route('admin.orders.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-btn font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Order Info Card -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-poppins font-semibold text-lg text-gray-800">{{ $order->order_number }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $order->order_date->format('d M Y') }}</p>
                    </div>
                    @php
                        $statusColor = match($order->status->value) {
                            'PENDING' => 'bg-yellow-100 text-yellow-700',
                            'PROCESSING' => 'bg-blue-100 text-info',
                            'COMPLETED' => 'bg-green-100 text-success',
                            'CANCELLED' => 'bg-gray-100 text-gray-600',
                            default => 'bg-gray-100 text-gray-600',
                        };
                    @endphp
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-badge {{ $statusColor }}">
                        {{ $order->status->value }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-4">
                    <div>
                        <p class="text-sm text-gray-500">Customer</p>
                        <a href="{{ route('admin.customers.show', $order->customer_id) }}" class="text-base font-medium text-info hover:underline">
                            {{ $order->customer->company_name ?? '-' }}
                        </a>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Items</p>
                        <p class="text-base font-medium text-gray-900">{{ $order->items->count() }} items</p>
                    </div>
                    @if($order->notes)
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500">Notes</p>
                        <p class="text-base text-gray-700">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items Table -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-poppins font-semibold text-lg text-gray-800">Order Items</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $item->product->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-badge bg-blue-100 text-info">
                                            {{ $item->product->sku ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($item->weight, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->unit }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $item->notes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada item.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Related Shipments -->
            @if($order->shipments->count() > 0)
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-poppins font-semibold text-lg text-gray-800">Related Shipments</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->shipments as $shipment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $shipment->shipment_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $shipment->origin }} → {{ $shipment->destination }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $sColor = match($shipment->status->value) {
                                                'IN_TRANSIT' => 'bg-blue-100 text-info',
                                                'DELIVERED', 'ARRIVED' => 'bg-green-100 text-success',
                                                'DELAYED' => 'bg-red-100 text-primary',
                                                default => 'bg-gray-100 text-gray-600',
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-badge {{ $sColor }}">
                                            {{ $shipment->status->label() }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>