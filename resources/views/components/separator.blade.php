@props([
    'orientation' => 'horizontal', // horizontal | vertical
    'label' => null,
])

@php
$isVertical = $orientation === 'vertical';

$base = 'bg-gray-200 dark:bg-zinc-700';

// Orientation styles
$styles = $isVertical
    ? 'w-px h-full self-stretch'
    : 'w-full h-px';

// Wrapper needed if label exists
$wrapper = $label
    ? 'flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400'
    : '';
@endphp

@if($label && !$isVertical)
    <div {{ $attributes->merge(['class' => $wrapper]) }}>
        <div class="flex-1 h-px {{ $base }}"></div>

        <span class="whitespace-nowrap">
            {{ $label }}
        </span>

        <div class="flex-1 h-px {{ $base }}"></div>
    </div>
@else
    <div {{ $attributes->merge(['class' => "$base $styles"]) }}></div>
@endif