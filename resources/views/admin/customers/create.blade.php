<x-app-layout>
    <div class="space-y-6 max-w-4xl mx-auto">
        
        <!-- Header & Back Navigation -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.customers.index') }}" class="btn-ghost text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali ke Daftar Pelanggan</span>
            </a>
        </div>

        <x-page-header title="Tambah Pelanggan Baru" description="Isi data profil perusahaan dan kontak utama pelanggan." />

        <form method="POST" action="{{ route('admin.customers.store') }}" x-data="{ createAccount: false }">
            @csrf

            <!-- Section 1: Informasi Perusahaan -->
            <div class="crm-card space-y-5">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="font-poppins font-bold text-base text-gray-900">Informasi Perusahaan</h2>
                    <p class="text-xs text-gray-500">Identitas resmi dan alamat perusahaan pelanggan.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="company_name" class="crm-label">Nama Perusahaan <span class="text-primary">*</span></label>
                        <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}" required
                               placeholder="Contoh: PT Logistics Jaya"
                               class="crm-input @error('company_name') border-primary @enderror">
                        @error('company_name') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="name" class="crm-label">Nama PIC Utama <span class="text-primary">*</span></label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required
                               placeholder="Contoh: Budi Santoso"
                               class="crm-input @error('name') border-primary @enderror">
                        @error('name') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="crm-label">Telepon / WhatsApp <span class="text-primary">*</span></label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                               placeholder="081234567890"
                               class="crm-input @error('phone') border-primary @enderror">
                        @error('phone') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="crm-label">Email Perusahaan / PIC</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               placeholder="perusahaan@domain.com"
                               class="crm-input @error('email') border-primary @enderror">
                        @error('email') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="crm-label">Alamat Lengkap <span class="text-primary">*</span></label>
                    <textarea id="address" name="address" rows="3" required
                              placeholder="Jl. Raya Utama No. 123..."
                              class="crm-input @error('address') border-primary @enderror">{{ old('address') }}</textarea>
                    @error('address') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="city" class="crm-label">Kota <span class="text-primary">*</span></label>
                        <input id="city" type="text" name="city" value="{{ old('city') }}" required
                               placeholder="Jakarta Selatan"
                               class="crm-input @error('city') border-primary @enderror">
                        @error('city') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="province" class="crm-label">Provinsi <span class="text-primary">*</span></label>
                        <input id="province" type="text" name="province" value="{{ old('province') }}" required
                               placeholder="DKI Jakarta"
                               class="crm-input @error('province') border-primary @enderror">
                        @error('province') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="postal_code" class="crm-label">Kode Pos</label>
                        <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code') }}"
                               placeholder="12345"
                               class="crm-input @error('postal_code') border-primary @enderror">
                        @error('postal_code') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="notes" class="crm-label">Catatan Tambahan</label>
                    <textarea id="notes" name="notes" rows="2"
                              placeholder="Catatan khusus pelanggan..."
                              class="crm-input">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Section 2: Opsi Akun Customer -->
            <div class="crm-card mt-6 space-y-4">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="font-poppins font-bold text-base text-gray-900">Akses Portal Customer</h2>
                    <p class="text-xs text-gray-500">Buat kredensial login jika pelanggan diizinkan mengakses portal.</p>
                </div>

                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="create_account" value="1" x-model="createAccount"
                           class="rounded-xs border-gray-300 text-primary focus:ring-primary">
                    <span class="text-sm font-semibold text-gray-800">Buat Akun Portal Customer Sekaligus</span>
                </label>

                <div x-show="createAccount" x-transition class="p-4 rounded-btn bg-gray-50 border border-gray-100 space-y-4">
                    <div>
                        <label for="account_email" class="crm-label">Email Akun Login <span class="text-primary">*</span></label>
                        <input id="account_email" type="email" name="account_email" value="{{ old('account_email') }}"
                               :required="createAccount"
                               placeholder="email.login@customer.com"
                               class="crm-input @error('account_email') border-primary @enderror">
                        @error('account_email') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="account_password" class="crm-label">Password Akun <span class="text-primary">*</span></label>
                        <input id="account_password" type="password" name="account_password"
                               :required="createAccount"
                               placeholder="Min. 8 karakter"
                               class="crm-input @error('account_password') border-primary @enderror">
                        @error('account_password') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Actions Footer -->
            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.customers.index') }}" class="btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn-primary">
                    Simpan Pelanggan
                </button>
            </div>
        </form>

    </div>
</x-app-layout>