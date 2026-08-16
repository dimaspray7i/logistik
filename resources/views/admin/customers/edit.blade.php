<x-app-layout>
    <x-slot name="header">
        <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Customer') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-6">
                
                <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
                    @csrf
                    @method('PUT')

                    <!-- Company Information -->
                    <div class="space-y-4">
                        <h3 class="font-poppins font-semibold text-lg text-gray-800 border-b pb-2">Informasi Perusahaan</h3>
                        
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone <span class="text-primary">*</span></label>
                                <input id="phone" type="text" name="phone" value="{{ old('phone', $customer->phone) }}" required
                                       class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('phone') border-primary @enderror">
                                @error('phone') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email', $customer->email) }}"
                                       class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('email') border-primary @enderror">
                                @error('email') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Alamat <span class="text-primary">*</span></label>
                            <textarea id="address" name="address" rows="3" required
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

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea id="notes" name="notes" rows="2"
                                      class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">{{ old('notes', $customer->notes) }}</textarea>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex item-center justify-end gap-4">
                        <a href="{{ route('admin.customers.index') }}" 
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-btn font-semibold hover:bg-gray-300 transition">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">
                            Update Customer
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>