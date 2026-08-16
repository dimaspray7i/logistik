<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between item-center">
            <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">{{ __('Kendaraan') }}</h2>
            <a href="{{ route('admin.vehicles.create') }}" class="inline-flex item-center px-4 py-2 bg-primary border border-transparent rounded-btn font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition">+ Tambah Kendaraan</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-success p-4 rounded-card"><p class="text-success font-medium">{{ session('success') }}</p></div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-primary p-4 rounded-card"><p class="text-primary font-medium">{{ session('error') }}</p></div>
            @endif

            <!-- Search -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-4">
                <form method="GET" action="{{ route('admin.vehicles.index') }}" class="flex gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari plat nomor, tipe, atau brand..." class="flex-1 rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">Search</button>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plat Nomor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($vehicles as $vehicle)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $vehicle->plate_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $vehicle->vehicle_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $vehicle->brand ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($vehicle->capacity, 0) }} Kg</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColor = match($vehicle->status->value) {
                                                'AVAILABLE' => 'bg-green-100 text-success',
                                                'IN_USE' => 'bg-blue-100 text-info',
                                                'MAINTENANCE' => 'bg-red-100 text-primary',
                                                default => 'bg-gray-100 text-gray-600',
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-badge {{ $statusColor }}">{{ $vehicle->status->label() }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="text-warning hover:underline">Ubah</a>
                                        <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus vehicle ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-primary hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Tidak ada data vehicle.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">{{ $vehicles->links() }}</div>
            </div>

        </div>
    </div>
</x-app-layout>