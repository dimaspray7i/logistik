<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between item-center">
            <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">{{ __('Sopir') }}</h2>
            <a href="{{ route('admin.drivers.create') }}" class="inline-flex item-center px-4 py-2 bg-primary border border-transparent rounded-btn font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition">+ Tambah Sopir</a>
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
                <form method="GET" action="{{ route('admin.drivers.index') }}" class="flex gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, phone, atau SIM..." class="flex-1 rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">Search</button>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SIM</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($drivers as $driver)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $driver->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $driver->phone }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $driver->license_number ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColor = $driver->status === 'ACTIVE' ? 'bg-green-100 text-success' : 'bg-gray-100 text-gray-600';
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-badge {{ $statusColor }}">{{ $driver->status }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.drivers.edit', $driver) }}" class="text-warning hover:underline">Ubah</a>
                                        <form action="{{ route('admin.drivers.destroy', $driver) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus driver ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-primary hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada data driver.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">{{ $drivers->links() }}</div>
            </div>

        </div>
    </div>
</x-app-layout>