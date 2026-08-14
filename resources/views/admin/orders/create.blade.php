<x-app-layout>
    <x-slot name="header">
        <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Order') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-6">
                
                <form method="POST" action="{{ route('admin.orders.store') }}" 
                      x-data="{
                          items: [{ product_id: '', quantity: 1, weight: 0, unit: 'Kg', notes: '' }],
                          products: {{ Js::from($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'unit' => $p->unit])) }},
                          addItem() {
                              this.items.push({ product_id: '', quantity: 1, weight: 0, unit: 'Kg', notes: '' });
                          },
                          removeItem(index) {
                              if (this.items.length > 1) {
                                  this.items.splice(index, 1);
                              }
                          },
                          updateUnit(index) {
                              const productId = this.items[index].product_id;
                              const product = this.products.find(p => p.id == productId);
                              if (product) {
                                  this.items[index].unit = product.unit;
                              }
                          }
                      }">
                    @csrf

                    <!-- Order Info -->
                    <div class="space-y-4 mb-6">
                        <h3 class="font-poppins font-semibold text-lg text-gray-800 border-b pb-2">Informasi Order</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer <span class="text-primary">*</span></label>
                                <select id="customer_id" name="customer_id" required
                                        class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('customer_id') border-primary @enderror">
                                    <option value="">-- Pilih Customer --</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
                                            {{ $customer->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="order_date" class="block text-sm font-medium text-gray-700">Order Date <span class="text-primary">*</span></label>
                                <input id="order_date" type="date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required
                                       class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm @error('order_date') border-primary @enderror">
                                @error('order_date') <p class="text-primary text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-primary">*</span></label>
                            <select id="status" name="status" required
                                    class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                                <option value="PENDING" @selected(old('status') == 'PENDING')>Pending</option>
                                <option value="PROCESSING" @selected(old('status') == 'PROCESSING')>Processing</option>
                                <option value="COMPLETED" @selected(old('status') == 'COMPLETED')>Completed</option>
                                <option value="CANCELLED" @selected(old('status') == 'CANCELLED')>Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea id="notes" name="notes" rows="2"
                                      class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Order Items (Dynamic) -->
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center border-b pb-2">
                            <h3 class="font-poppins font-semibold text-lg text-gray-800">Order Items</h3>
                            <button type="button" @click="addItem()" 
                                    class="text-sm text-info hover:underline font-medium">
                                + Add Item
                            </button>
                        </div>

                        <template x-for="(item, index) in items" :key="index">
                            <div class="bg-gray-50 p-4 rounded-card space-y-3">
                                <div class="flex justify-between items-start">
                                    <span class="text-sm font-medium text-gray-700" x-text="'Item #' + (index + 1)"></span>
                                    <button type="button" @click="removeItem(index)" 
                                            x-show="items.length > 1"
                                            class="text-primary text-sm hover:underline">
                                        Remove
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                                    <div class="md:col-span-4">
                                        <label class="block text-xs font-medium text-gray-600">Product <span class="text-primary">*</span></label>
                                        <select :name="'items[' + index + '][product_id]'" x-model="item.product_id" @change="updateUnit(index)" required
                                                class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm text-sm">
                                            <option value="">-- Pilih Product --</option>
                                            <template x-for="product in products" :key="product.id">
                                                <option :value="product.id" x-text="product.name + ' (' + product.sku + ')'"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-600">Qty <span class="text-primary">*</span></label>
                                        <input type="number" step="0.01" min="0.01" :name="'items[' + index + '][quantity]'" x-model="item.quantity" required
                                               class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm text-sm">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-600">Weight <span class="text-primary">*</span></label>
                                        <input type="number" step="0.01" min="0" :name="'items[' + index + '][weight]'" x-model="item.weight" required
                                               class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm text-sm">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-600">Unit <span class="text-primary">*</span></label>
                                        <input type="text" :name="'items[' + index + '][unit]'" x-model="item.unit" required
                                               class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm text-sm">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-600">Notes</label>
                                        <input type="text" :name="'items[' + index + '][notes]'" x-model="item.notes"
                                               class="mt-1 block w-full rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm text-sm">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-btn font-semibold hover:bg-gray-300 transition">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">Save Order</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>