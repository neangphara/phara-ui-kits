@props([
    'size' => 'base',
    'variant' => 'default',
    'tag' => 'p',
])

@php
    $sizeClasses = [
        'xs' => 'text-xs',
        'sm' => 'text-sm',
        'base' => 'text-base',
        'lg' => 'text-lg',
        'xl' => 'text-xl',
    ];

    $variantClasses = [
        'default' => 'text-gray-900 dark:text-white',
        'muted' => 'text-gray-500 dark:text-gray-400',
        'primary' => 'text-primary-600 dark:text-primary-400',
        'danger' => 'text-red-600 dark:text-red-400',
        'success' => 'text-green-600 dark:text-green-400',
    ];

    $classes = ($sizeClasses[$size] ?? $sizeClasses['base']) . ' ' . ($variantClasses[$variant] ?? $variantClasses['default']);
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $tag }}>
