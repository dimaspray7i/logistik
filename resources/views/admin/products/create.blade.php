<x-app-layout>
    <x-slot name="header">
        <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Product') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-6">
                
                <form method="POST" action="{{ route('admin.products.store') }}">
                    @csrf

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="sku" class="block text-sm font-medium text-gray-700">SKU <span class="text-primary">*</span></label>
                                <input id="sku" type="text" name="sku" value="{{ old('sku') }}" placeholder="Contoh: SMN-001" required
                                       class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('sku') border-primary @enderror">
                                @error('sku') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700">Unit <span class="text-primary">*</span></label>
                                <select id="unit" name="unit" required
                                        class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                    @foreach (['Kg', 'Pcs', 'Box', 'Liter', 'Unit'] as $unit)
                                        <option value="{{ $unit }}" @selected(old('unit') == $unit)>{{ $unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Product <span class="text-primary">*</span></label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required
                                   class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('name') border-primary @enderror">
                            @error('name') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="3"
                                      class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex item-center justify-end gap-4">
                        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-btn font-semibold hover:bg-gray-300 transition">Batal</a>
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">Save Product</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>