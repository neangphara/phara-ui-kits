@props([
    'href' => null,
])

@php
$base = 'hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors';
@endphp

@if($href)
    <tr {{ $attributes->merge(['class' => $base . ' cursor-pointer']) }} x-data @click="window.location.href = '{{ $href }}'">
        {{ $slot }}
    </tr>
@else
    <tr {{ $attributes->merge(['class' => $base]) }}>
        {{ $slot }}
    </tr>
@endif
