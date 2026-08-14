<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Orders') }}
            </h2>
            <a href="{{ route('admin.orders.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-btn font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition">
                + Add Order
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
                <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col md:flex-row gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari order number atau customer..."
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
                        <option value="PENDING" @selected(request('status') == 'PENDING')>Pending</option>
                        <option value="PROCESSING" @selected(request('status') == 'PROCESSING')>Processing</option>
                        <option value="COMPLETED" @selected(request('status') == 'COMPLETED')>Completed</option>
                        <option value="CANCELLED" @selected(request('status') == 'CANCELLED')>Cancelled</option>
                    </select>

                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Orders Table -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($orders as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $order->order_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->customer->company_name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->order_date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->items_count ?? $order->items->count() }} items
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColor = match($order->status->value) {
                                                'PENDING' => 'bg-yellow-100 text-yellow-700',
                                                'PROCESSING' => 'bg-blue-100 text-info',
                                                'COMPLETED' => 'bg-green-100 text-success',
                                                'CANCELLED' => 'bg-gray-100 text-gray-600',
                                                default => 'bg-gray-100 text-gray-600',
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-badge {{ $statusColor }}">
                                            {{ $order->status->value }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-info hover:underline">View</a>
                                        <a href="{{ route('admin.orders.edit', $order) }}" class="text-warning hover:underline">Edit</a>
                                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus order ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-primary hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        Tidak ada data order.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $orders->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>