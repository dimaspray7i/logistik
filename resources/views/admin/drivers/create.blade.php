<x-app-layout>
    <x-slot name="header"><h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">{{ __('Add New Driver') }}</h2></x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-6">
                <form method="POST" action="{{ route('admin.drivers.store') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama <span class="text-primary">*</span></label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('name') border-primary @enderror">
                            @error('name') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone <span class="text-primary">*</span></label>
                                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('phone') border-primary @enderror">
                                @error('phone') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="license_number" class="block text-sm font-medium text-gray-700">Nomor SIM</label>
                                <input id="license_number" type="text" name="license_number" value="{{ old('license_number') }}" placeholder="SIM-12345" class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                            </div>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-primary">*</span></label>
                            <select id="status" name="status" required class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                <option value="ACTIVE" @selected(old('status') == 'ACTIVE')>Active</option>
                                <option value="INACTIVE" @selected(old('status') == 'INACTIVE')>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex item-center justify-end gap-4">
                        <a href="{{ route('admin.drivers.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-btn font-semibold hover:bg-gray-300 transition">Batal</a>
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">Save Driver</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>