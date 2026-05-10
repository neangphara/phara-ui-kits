@props([
    'level' => '1',
    'size' => 'default',
    'variant' => 'default',
])

@php
    $tag = 'h' . $level;

    $sizeClasses = [
        'default' => match($level) {
            '1' => 'text-4xl md:text-5xl font-bold',
            '2' => 'text-3xl md:text-4xl font-bold',
            '3' => 'text-2xl md:text-3xl font-semibold',
            '4' => 'text-xl md:text-2xl font-semibold',
            '5' => 'text-lg md:text-xl font-medium',
            '6' => 'text-base md:text-lg font-medium',
            default => 'text-4xl md:text-5xl font-bold',
        },
        'xs' => 'text-xs font-semibold',
        'sm' => 'text-sm font-semibold',
        'base' => 'text-base font-semibold',
        'lg' => 'text-lg font-semibold',
        'xl' => 'text-xl font-bold',
        '2xl' => 'text-2xl font-bold',
        '3xl' => 'text-3xl font-bold',
        '4xl' => 'text-4xl font-bold',
        '5xl' => 'text-5xl font-bold',
    ];

    $variantClasses = [
        'default' => 'text-gray-900 dark:text-white',
        'muted' => 'text-gray-600 dark:text-gray-400',
        'primary' => 'text-primary-600 dark:text-primary-400',
        'danger' => 'text-red-600 dark:text-red-400',
    ];

    $classes = ($sizeClasses[$size] ?? $sizeClasses['default']) . ' ' . ($variantClasses[$variant] ?? $variantClasses['default']);
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $tag }}>
