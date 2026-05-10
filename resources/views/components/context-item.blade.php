@props([
    'href'     => null,
    'icon'     => null,
    'variant'  => 'default', // default | danger
    'shortcut' => null,
    'disabled' => false,
])

@php
$base = 'group flex w-full items-center gap-2.5 px-3 py-1.5 text-sm transition-colors';

$variantMap = [
    'default' => 'text-zinc-700 dark:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-700/60',
    'danger'  => 'text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10',
];

$classes = $disabled
    ? $base . ' text-zinc-400 dark:text-zinc-600 cursor-not-allowed pointer-events-none'
    : $base . ' ' . ($variantMap[$variant] ?? $variantMap['default']);

$iconColor = $disabled
    ? 'text-zinc-300 dark:text-zinc-600'
    : ($variant === 'danger' ? 'text-red-400 dark:text-red-500' : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-500 dark:group-hover:text-zinc-400');
@endphp

@if($href && !$disabled)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <x-ui::icon :name="$icon" class="w-4 h-4 shrink-0 {{ $iconColor }}" />
        @endif
        <span class="flex-1 text-left">{{ $slot }}</span>
        @if($shortcut)
            <span class="ml-auto pl-4 text-xs tabular-nums text-zinc-400 dark:text-zinc-500">{{ $shortcut }}</span>
        @endif
    </a>
@else
    <button
        type="button"
        @disabled($disabled)
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if($icon)
            <x-ui::icon :name="$icon" class="w-4 h-4 shrink-0 {{ $iconColor }}" />
        @endif
        <span class="flex-1 text-left">{{ $slot }}</span>
        @if($shortcut)
            <span class="ml-auto pl-4 text-xs tabular-nums text-zinc-400 dark:text-zinc-500">{{ $shortcut }}</span>
        @endif
    </button>
@endif
