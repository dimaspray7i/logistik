@props([
    'title' => 'Tidak ada data',
    'description' => 'Belum terdapat data pada sistem.',
])

<div {{ $attributes->merge(['class' => 'crm-card text-center py-12 px-6 flex flex-col items-center justify-center']) }}>
    <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mb-4">
        @if (isset($icon))
            {{ $icon }}
        @else
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
        @endif
    </div>

    <h3 class="font-poppins font-semibold text-lg text-gray-900 mb-1">{{ $title }}</h3>
    <p class="text-sm text-gray-500 max-w-sm mb-6">{{ $description }}</p>

    @if (isset($action))
        <div>
            {{ $action }}
        </div>
    @endif
</div>
