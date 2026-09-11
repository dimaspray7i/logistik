<x-app-layout>
    <div class="space-y-6">

        <x-page-header title="Penyedia Ekspedisi" description="Kelola daftar penyedia/jasa ekspedisi eksternal (AEI, JNE, POS, TIKI, dll).">
            <x-slot name="actions">
                <a href="{{ route('admin.expedition-providers.create') }}" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Penyedia</span>
                </a>
            </x-slot>
        </x-page-header>

        <div class="crm-card p-0 overflow-hidden">
            <div class="crm-table-container">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Ekspedisi</th>
                            <th>Website / Tracking URL</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($providers as $provider)
                            <tr>
                                <td class="font-mono font-bold text-gray-900">{{ $provider->code }}</td>
                                <td class="font-semibold text-gray-900">
                                    {{ $provider->name }}
                                    @if($provider->code === 'AEI')
                                        <span class="ml-1.5 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-primary/10 text-primary border border-primary/20">Mitra Utama</span>
                                    @endif
                                </td>
                                <td class="text-xs">
                                    @if($provider->website_url)
                                        <a href="{{ $provider->website_url }}" target="_blank" rel="noopener noreferrer" class="text-info hover:underline flex items-center gap-1">
                                            <span>{{ parse_url($provider->website_url, PHP_URL_HOST) ?? $provider->website_url }}</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="text-xs text-gray-600">{{ $provider->phone ?: '-' }}</td>
                                <td>
                                    @if($provider->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.expedition-providers.edit', $provider) }}" class="btn-ghost !p-1.5 text-gray-500 hover:text-gray-900" title="Ubah">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form method="POST" action="{{ route('admin.expedition-providers.destroy', $provider) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penyedia ekspedisi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-ghost !p-1.5 text-red-500 hover:text-red-700" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-xs text-gray-400">Belum ada penyedia ekspedisi tersimpan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
