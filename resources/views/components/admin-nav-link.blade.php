@props([
    'active' => false,
    'href' => '#',
    'icon' => null,
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'group flex items-center gap-3 px-3.5 py-2.5 rounded-btn text-sm font-medium transition-all duration-150 ' . ($active ? 'bg-primary text-white shadow-sm font-semibold' : 'text-gray-600 hover:bg-gray-100/80 hover:text-gray-900')]) }}>
    @if ($icon)
        <span class="w-5 h-5 shrink-0 transition-colors {{ $active ? 'text-white' : 'text-gray-400 group-hover:text-gray-600' }}">{!! $icon !!}</span>
    @endif
    <span class="truncate">{{ $slot }}</span>
</a>


