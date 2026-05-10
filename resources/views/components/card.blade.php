@props([
    'size' => 'md',
])

@php
    $sizes = [
        'sm' => 'p-4 rounded-xl',
        'md' => 'p-8 rounded-2xl',
    ];
@endphp

<div {{ $attributes->merge([
    'class' => 'bg-white dark:bg-black border border-gray-200 dark:border-white/20 ' . ($sizes[$size] ?? '')
]) }}>
    {{ $slot }}
</div>