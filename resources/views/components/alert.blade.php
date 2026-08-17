@props([
    'type' => 'success', // success, error, warning, info
    'message' => null,
])

@php
    $styles = match($type) {
        'error' => 'bg-red-50 text-red-800 border-red-200/80 icon-red-500',
        'warning' => 'bg-amber-50 text-amber-800 border-amber-200/80 icon-amber-500',
        'info' => 'bg-blue-50 text-blue-800 border-blue-200/80 icon-blue-500',
        default => 'bg-emerald-50 text-emerald-800 border-emerald-200/80 icon-emerald-500',
    };
@endphp

<div x-data="{ show: true }" x-show="show" {{ $attributes->merge(['class' => "p-4 rounded-btn border text-sm flex items-start justify-between gap-3 shadow-xs transition-all duration-200 {$styles}"]) }}>
    <div class="flex items-start gap-3">
        @if ($type === 'success')
            <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        @elseif ($type === 'error')
            <svg class="w-5 h-5 text-red-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        @elseif ($type === 'warning')
            <svg class="w-5 h-5 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        @else
            <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        @endif
        <div>
            {{ $message ?? $slot }}
        </div>
    </div>
    <button @click="show = false" type="button" class="text-current opacity-60 hover:opacity-100 p-1 transition-opacity">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
</div>
