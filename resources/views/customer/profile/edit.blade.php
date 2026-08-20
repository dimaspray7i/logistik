<x-customer-layout>
    <x-slot name="header">
        <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">{{ __('Profil Perusahaan') }}</h2>
    </x-slot>

    <div class="py-6 space-y-6">

        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-success p-4 rounded-card">
                <p class="text-success font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Info Perusahaan -->
        <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-6">
            <h3 class="font-poppins font-semibold text-lg text-gray-800 mb-4 border-b pb-3">Informasi Perusahaan</h3>

            <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700">Nama Perusahaan <span class="text-primary">*</span></label>
                        <input id="company_name" type="text" name="company_name" value="{{ old('company_name', $customer->company_name) }}" required
                               class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('company_name') border-primary @enderror">
                        @error('company_name') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama PIC <span class="text-primary">*</span></label>
                        <input id="name" type="text" name="name" value="{{ old('name', $customer->name) }}" required
                               class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('name') border-primary @enderror">
                        @error('name') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Telepon <span class="text-primary">*</span></label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone', $customer->phone) }}" required
                               class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('phone') border-primary @enderror">
                        @error('phone') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Perusahaan</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $customer->email) }}"
                               class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('email') border-primary @enderror">
                        @error('email') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Alamat <span class="text-primary">*</span></label>
                    <textarea id="address" name="address" rows="2" required
                              class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('address') border-primary @enderror">{{ old('address', $customer->address) }}</textarea>
                    @error('address') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">Kota <span class="text-primary">*</span></label>
                        <input id="city" type="text" name="city" value="{{ old('city', $customer->city) }}" required
                               class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('city') border-primary @enderror">
                        @error('city') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="province" class="block text-sm font-medium text-gray-700">Provinsi <span class="text-primary">*</span></label>
                        <input id="province" type="text" name="province" value="{{ old('province', $customer->province) }}" required
                               class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('province') border-primary @enderror">
                        @error('province') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Kode Pos</label>
                        <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code', $customer->postal_code) }}"
                               class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('postal_code') border-primary @enderror">
                        @error('postal_code') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">Simpan Perubahan</button>
                </div>
            </form>
        </div>

        <!-- Ganti Password -->
        <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-6">
            <h3 class="font-poppins font-semibold text-lg text-gray-800 mb-1 border-b pb-3">Ganti Password Akun</h3>
            <p class="text-xs text-gray-500 mb-4 mt-3">Kosongkan bagian ini jika tidak ingin mengubah password.</p>

            <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <!-- Hidden fields agar validasi info perusahaan tetap lolos saat hanya ganti password -->
                <input type="hidden" name="company_name" value="{{ $customer->company_name }}">
                <input type="hidden" name="name" value="{{ $customer->name }}">
                <input type="hidden" name="phone" value="{{ $customer->phone }}">
                <input type="hidden" name="email" value="{{ $customer->email }}">
                <input type="hidden" name="address" value="{{ $customer->address }}">
                <input type="hidden" name="city" value="{{ $customer->city }}">
                <input type="hidden" name="province" value="{{ $customer->province }}">
                <input type="hidden" name="postal_code" value="{{ $customer->postal_code }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                        <input id="current_password" type="password" name="current_password"
                               class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('current_password') border-primary @enderror">
                        @error('current_password') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                        <input id="new_password" type="password" name="new_password"
                               class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('new_password') border-primary @enderror">
                        @error('new_password') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                        <input id="new_password_confirmation" type="password" name="new_password_confirmation"
                               class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">Perbarui Password</button>
                </div>
            </form>
        </div>

        <!-- Info Akun (Read-only) -->
        <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-6">
            <h3 class="font-poppins font-semibold text-lg text-gray-800 mb-4 border-b pb-3">Informasi Akun Login</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500">Email Login</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $user->email }}</p>
                    <p class="text-xs text-gray-400 mt-1">Email login tidak dapat diubah di sini. Hubungi admin jika perlu perubahan.</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Nama Pengguna</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                </div>
            </div>
        </div>

    </div>
</x-customer-layout>