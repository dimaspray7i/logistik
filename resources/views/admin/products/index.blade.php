<x-app-layout>
    <div class="space-y-6">

        <x-page-header title="Master Produk" description="Kelola data barang dan spesifikasi produk logistik.">
            <x-slot name="actions">
                <a href="{{ route('admin.products.create') }}" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Tambah Produk</span>
                </a>
            </x-slot>
        </x-page-header>

        <!-- Search Card -->
        <div class="crm-card p-4">
            <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama produk atau Kode SKU..."
                           class="crm-input pl-10">
                </div>
                <button type="submit" class="btn-secondary shrink-0">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>Cari</span>
                </button>
            </form>
        </div>

        <!-- Table Card -->
        <div class="crm-card p-0 overflow-hidden">
            <div class="crm-table-container">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Nama Produk</th>
                            <th>Satuan</th>
                            <th>Deskripsi Keterangan</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td>
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-badge bg-gray-100 text-gray-800 border border-gray-200">{{ $product->sku }}</span>
                                </td>
                                <td class="font-bold text-gray-900">{{ $product->name }}</td>
                                <td class="text-xs text-gray-600 font-medium">{{ $product->unit }}</td>
                                <td class="text-xs text-gray-500 max-w-xs truncate">{{ $product->description ?? '-' }}</td>
                                <td class="text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('admin.products.edit', $product) }}" title="Ubah" class="p-1.5 rounded-btn text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
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
                                <td colspan="5" class="py-12 text-center text-gray-500">
                                    <p class="text-sm">Tidak ada data produk yang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($products->hasPages())
                <div class="p-4 border-t border-gray-100 bg-white">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>