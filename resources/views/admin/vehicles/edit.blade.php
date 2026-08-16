<x-app-layout>
    <x-slot name="header"><h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Vehicle') }}</h2></x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-6">
                <form method="POST" action="{{ route('admin.vehicles.update', $vehicle) }}">
                    @csrf @method('PUT')
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="plate_number" class="block text-sm font-medium text-gray-700">Plat Nomor <span class="text-primary">*</span></label>
                                <input id="plate_number" type="text" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" required class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('plate_number') border-primary @enderror">
                                @error('plate_number') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="vehicle_type" class="block text-sm font-medium text-gray-700">Tipe <span class="text-primary">*</span></label>
                                <select id="vehicle_type" name="vehicle_type" required class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                    @foreach (['Truck', 'Van', 'Pickup', 'Container', 'Motor'] as $type)
                                        <option value="{{ $type }}" @selected(old('vehicle_type', $vehicle->vehicle_type) == $type)>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                                <input id="brand" type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}" class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                            </div>
                            <div>
                                <label for="capacity" class="block text-sm font-medium text-gray-700">Kapasitas (Kg) <span class="text-primary">*</span></label>
                                <input id="capacity" type="number" step="0.01" min="0" name="capacity" value="{{ old('capacity', $vehicle->capacity) }}" required class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('capacity') border-primary @enderror">
                                @error('capacity') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-primary">*</span></label>
                            <select id="status" name="status" required class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                <option value="AVAILABLE" @selected(old('status', $vehicle->status->label()) == 'AVAILABLE')>Available</option>
                                <option value="IN_USE" @selected(old('status', $vehicle->status->label()) == 'IN_USE')>In Use</option>
                                <option value="MAINTENANCE" @selected(old('status', $vehicle->status->label()) == 'MAINTENANCE')>Maintenance</option>
                            </select>
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">{{ old('notes', $vehicle->notes) }}</textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex item-center justify-end gap-4">
                        <a href="{{ route('admin.vehicles.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-btn font-semibold hover:bg-gray-300 transition">Batal</a>
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">Update Vehicle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout><x-app-layout>
    <x-slot name="header"><h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Vehicle') }}</h2></x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-6">
                <form method="POST" action="{{ route('admin.vehicles.update', $vehicle) }}">
                    @csrf @method('PUT')
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="plate_number" class="block text-sm font-medium text-gray-700">Plat Nomor <span class="text-primary">*</span></label>
                                <input id="plate_number" type="text" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" required class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('plate_number') border-primary @enderror">
                                @error('plate_number') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="vehicle_type" class="block text-sm font-medium text-gray-700">Tipe <span class="text-primary">*</span></label>
                                <select id="vehicle_type" name="vehicle_type" required class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                    @foreach (['Truck', 'Van', 'Pickup', 'Container', 'Motor'] as $type)
                                        <option value="{{ $type }}" @selected(old('vehicle_type', $vehicle->vehicle_type) == $type)>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                                <input id="brand" type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}" class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                            </div>
                            <div>
                                <label for="capacity" class="block text-sm font-medium text-gray-700">Kapasitas (Kg) <span class="text-primary">*</span></label>
                                <input id="capacity" type="number" step="0.01" min="0" name="capacity" value="{{ old('capacity', $vehicle->capacity) }}" required class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('capacity') border-primary @enderror">
                                @error('capacity') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-primary">*</span></label>
                            <select id="status" name="status" required class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                <option value="AVAILABLE" @selected(old('status', $vehicle->status->label()) == 'AVAILABLE')>Available</option>
                                <option value="IN_USE" @selected(old('status', $vehicle->status->label()) == 'IN_USE')>In Use</option>
                                <option value="MAINTENANCE" @selected(old('status', $vehicle->status->label()) == 'MAINTENANCE')>Maintenance</option>
                            </select>
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">{{ old('notes', $vehicle->notes) }}</textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex item-center justify-end gap-4">
                        <a href="{{ route('admin.vehicles.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-btn font-semibold hover:bg-gray-300 transition">Batal</a>
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">Update Vehicle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>