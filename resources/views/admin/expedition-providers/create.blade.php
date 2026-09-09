<x-app-layout>
    <div class="space-y-6">

        <x-page-header title="Tambah Penyedia Ekspedisi" description="Tambah penyedia / jasa ekspedisi eksternal baru ke dalam sistem.">
            <x-slot name="actions">
                <a href="{{ route('admin.expedition-providers.index') }}" class="btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali</span>
                </a>
            </x-slot>
        </x-page-header>

        <form method="POST" action="{{ route('admin.expedition-providers.store') }}" class="crm-card max-w-2xl space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="code" class="crm-label">Kode Singkat <span class="text-primary">*</span></label>
                    <input id="code" type="text" name="code" value="{{ old('code') }}" placeholder="Contoh: AEI, JNE, POS" required class="crm-input font-mono uppercase @error('code') border-primary @enderror">
                    @error('code') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="name" class="crm-label">Nama Penyedia Ekspedisi <span class="text-primary">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: PT. Antar Exprindo Indah (AEI)" required class="crm-input @error('name') border-primary @enderror">
                    @error('name') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="crm-label">No. Telepon / CS</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="021-1234567" class="crm-input">
                </div>
                <div>
                    <label for="website_url" class="crm-label">Website / Portal Resi</label>
                    <input id="website_url" type="url" name="website_url" value="{{ old('website_url') }}" placeholder="https://..." class="crm-input">
                </div>
            </div>

            <div>
                <label for="tracking_url_template" class="crm-label">Format Template URL Resi (Opsional)</label>
                <input id="tracking_url_template" type="text" name="tracking_url_template" value="{{ old('tracking_url_template') }}" placeholder="https://tracking.aei.co.id/{resi}" class="crm-input font-mono text-xs">
                <p class="text-[11px] text-gray-500 mt-1">Gunakan placeholder <code class="bg-gray-100 px-1 py-0.5 rounded text-gray-800">{resi}</code> yang akan digantikan otomatis dengan nomor resi pengiriman.</p>
            </div>

            <div>
                <label for="description" class="crm-label">Catatan / Keterangan</label>
                <textarea id="description" name="description" rows="3" class="crm-input">{{ old('description') }}</textarea>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', 1)) class="rounded border-gray-300 text-primary focus:ring-primary">
                <label for="is_active" class="text-xs font-semibold text-gray-700">Aktif (Dapat dipilih dalam form pengiriman)</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.expedition-providers.index') }}" class="btn-ghost">Batal</a>
                <button type="submit" class="btn-primary">
                    <span>Simpan Penyedia</span>
                </button>
            </div>
        </form>

    </div>
</x-app-layout>
