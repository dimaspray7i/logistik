@props([
    'title',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6']) }}>
    <div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $title }}</h1>
        @if ($description)
            <p class="text-sm text-gray-500 mt-1 font-normal">{{ $description }}</p>
        @endif
    </div>

    @if (isset($actions))
        <div class="flex items-center gap-3 shrink-0">
            {{ $actions }}
        </div>
    @endif
</div>
