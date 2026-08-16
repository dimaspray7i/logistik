<x-app-layout>
    <x-slot name="header">
        <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Contact') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-6">
                
                <form method="POST" action="{{ route('admin.contacts.store') }}">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer <span class="text-primary">*</span></label>
                            <select id="customer_id" name="customer_id" required
                                    class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('customer_id') border-primary @enderror">
                                <option value="">-- Pilih Customer --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" @selected(old('customer_id', $selectedCustomerId) == $customer->id)>
                                        {{ $customer->company_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama <span class="text-primary">*</span></label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required
                                   class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('name') border-primary @enderror">
                            @error('name') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700">Position / Jabatan</label>
                            <input id="position" type="text" name="position" value="{{ old('position') }}" placeholder="Contoh: Manager Logistik"
                                   class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone <span class="text-primary">*</span></label>
                                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                                       class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('phone') border-primary @enderror">
                                @error('phone') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}"
                                       class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('email') border-primary @enderror">
                                @error('email') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <label class="inline-flex item-center cursor-pointer">
                            <input type="checkbox" name="is_primary" value="1" @checked(old('is_primary'))
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm font-medium text-gray-700">Jadikan Primary Contact (PIC utama)</span>
                        </label>
                    </div>

                    <div class="mt-6 flex item-center justify-end gap-4">
                        <a href="{{ route('admin.contacts.index') }}" 
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-btn font-semibold hover:bg-gray-300 transition">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">
                            Save Contact
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>