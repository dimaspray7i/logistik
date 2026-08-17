@props([
    'interactive' => false,
])

<div {{ $attributes->merge(['class' => $interactive ? 'crm-card-interactive' : 'crm-card']) }}>
    {{ $slot }}
</div>
