@props([
    'href' => null,
    'icon' => null,
])

@php
$base = 'flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors';
$classes = $base;
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
            <x-ui::icon :name="$icon" size="sm" />
        @endif
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes . ' w-full text-left']) }}>
        @if ($icon)
            <x-ui::icon :name="$icon" size="sm" />
        @endif
        {{ $slot }}
    </button>
@endif
