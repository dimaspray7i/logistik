<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Contact Detail') }}
            </h2>
            <a href="{{ route('admin.contacts.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-btn font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-6">
                
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-full bg-primary flex items-center justify-center text-white font-poppins font-bold text-xl">
                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-poppins font-semibold text-lg text-gray-900">{{ $contact->name }}</h3>
                            @if ($contact->is_primary)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-badge bg-yellow-100 text-yellow-700">Primary</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500">{{ $contact->position ?? 'No position' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-6">
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="text-base font-medium text-gray-900">{{ $contact->phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-base font-medium text-gray-900">{{ $contact->email ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500">Customer</p>
                        <a href="{{ route('admin.customers.show', $contact->customer_id) }}" class="text-base font-medium text-info hover:underline">
                            {{ $contact->customer->company_name ?? '-' }}
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>