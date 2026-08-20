@props(['active' => false, 'href', 'icon' => null])

<a {{ $attributes->merge(['href' => $href, 'class' => 'flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors ' . ($active ? 'bg-primary text-white shadow-soft' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900')]) }}>
    @if($icon)
        <span class="w-5 h-5 flex-shrink-0">{!! $icon !!}</span>
    @endif
    <span>{{ $slot }}</span>
</a>