<x-customer-layout>
    <x-slot name="header">
        <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">{{ $judul }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto text-center">
            <div class="w-16 h-16 mx-auto rounded-full bg-primary/10 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </div>
            <h3 class="font-poppins font-semibold text-lg text-gray-800">{{ $judul }}</h3>
            <p class="text-gray-500 mt-2">Halaman ini sedang dalam pengembangan dan akan segera tersedia.</p>
        </div>
    </div>
</x-customer-layout>