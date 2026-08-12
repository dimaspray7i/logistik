<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card p-6 text-center">
                <p class="text-gray-600 mb-4">Mengalihkan ke dashboard yang sesuai...</p>
                
                <script>
                    // Redirect otomatis berdasarkan role
                    const isAdmin = {{ auth()->user()->isAdmin() ? 'true' : 'false' }};
                    if (isAdmin) {
                        window.location.href = "{{ route('admin.dashboard') }}";
                    } else {
                        window.location.href = "{{ route('customer.dashboard') }}";
                    }
                </script>
                
                <noscript>
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('customer.dashboard') }}" class="text-primary underline">Klik di sini jika tidak diarahkan otomatis.</a>
                </noscript>
            </div>
        </div>
    </div>
</x-app-layout>