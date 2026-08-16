<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between item-center">
            <h2 class="font-poppins font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Contacts / PIC') }}
            </h2>
            <a href="{{ route('admin.contacts.create') }}" 
               class="inline-flex item-center px-4 py-2 bg-primary border border-transparent rounded-btn font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition">
                + Tambah Kontak
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Messages -->
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-success p-4 rounded-card">
                    <p class="text-success font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Filter Bar -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100 p-4">
                <form method="GET" action="{{ route('admin.contacts.index') }}" class="flex flex-col md:flex-row gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama, email, atau phone..."
                           class="flex-1 rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                    
                    <select name="customer_id" class="rounded-btn border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                        <option value="">Semua Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" @selected(request('customer_id') == $customer->id)>
                                {{ $customer->company_name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-btn font-semibold hover:bg-red-700 transition">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Contacts Table -->
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-card border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Info</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($contacts as $contact)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex item-center gap-2">
                                            <span class="text-sm font-medium text-gray-900">{{ $contact->name }}</span>
                                            @if ($contact->is_primary)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-badge bg-yellow-100 text-yellow-700">
                                                    Primary
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $contact->position ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $contact->customer->company_name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $contact->phone }}</div>
                                        <div class="text-xs text-gray-500">{{ $contact->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.contacts.edit', $contact) }}" class="text-warning hover:underline">Ubah</a>
                                        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus contact ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-primary hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Tidak ada data contact.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $contacts->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>