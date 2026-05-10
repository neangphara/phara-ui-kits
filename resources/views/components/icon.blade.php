@props([
    'name',
    'variant' => 'outline',
    'size' => 'md',
])

@php
$base = 'shrink-0';

$sizes = [
    'xs' => 'size-3',
    'sm' => 'size-4',
    'md' => 'size-5',
    'lg' => 'size-6',
    'xl' => 'size-8',
];

$classes = $base . ' ' . ($sizes[$size] ?? $sizes['md']);

$path = app(\Phara\UIKit\Components\Icon::class, [
    'name' => $name,
    'variant' => $variant,
    'size' => $size,
])->getPath();

$viewBox = app(\Phara\UIKit\Components\Icon::class, [
    'name' => $name,
    'variant' => $variant,
    'size' => $size,
])->getViewBox();

$strokeWidth = app(\Phara\UIKit\Components\Icon::class, [
    'name' => $name,
    'variant' => $variant,
    'size' => $size,
])->getStrokeWidth();
@endphp

@if ($variant === 'solid')
    <svg
        {{ $attributes->merge(['class' => $classes]) }}
        xmlns="http://www.w3.org/2000/svg"
        viewBox="{{ $viewBox }}"
        fill="currentColor"
        aria-hidden="true"
        data-slot="icon"
    >
        <path fill-rule="evenodd" d="{{ $path }}" clip-rule="evenodd" />
    </svg>
@else
    <svg
        {{ $attributes->merge(['class' => $classes]) }}
        xmlns="http://www.w3.org/2000/svg"
        viewBox="{{ $viewBox }}"
        fill="none"
        stroke="currentColor"
        stroke-width="{{ $strokeWidth }}"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true"
        data-slot="icon"
    >
        <path d="{{ $path }}" />
    </svg>
@endif
