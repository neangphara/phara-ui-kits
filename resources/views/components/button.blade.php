@props([
    'variant' => 'primary',
    'sizes' => 'md',
    'href' => null,
    'iconPosition' => 'left',
    'icon' => null,
])

@php
$base = 'rounded-xl cursor-pointer font-medium transition inline-flex text-center items-center gap-2 justify-center';

$variants = [
    'primary' => 'bg-primary-600 text-white hover:bg-primary-700 dark:bg-primary-600 dark:hover:bg-primary-500',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600',
    'secondary' => 'bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600',
    'outline' => 'border-1 border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800',
    'ghost' => 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800',
    'dark' => 'bg-zinc-800 text-white dark:text-black hover:bg-zinc-700 dark:bg-zinc-50 dark:hover:bg-zinc-200',
];

$sizes = [
    'sm' => 'px-3 py-1 text-sm rounded-lg',
    'md' => 'px-4 py-2',
    'lg' => 'px-6 py-3 text-lg',
    'icon' => 'w-10 h-10 rounded-xl p-0'
];

$classes = $base.' '.($variants[$variant].' '.$sizes[$size] ?? '');
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($iconPosition === 'left' && $icon)
            <x-ui::icon :name="$icon" :size="$size" />
        @endif
        {{ $slot }}
        @if($iconPosition === 'right' && $icon)
            <x-ui::icon :name="$icon" :size="$size" />
        @endif
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }}>
        @if($iconPosition === 'left' && $icon)
            <x-ui::icon :name="$icon" :size="$size" />
        @endif
        {{ $slot }}
        @if($iconPosition === 'right' && $icon)
            <x-ui::icon :name="$icon" :size="$size" />
        @endif
    </button>
@endif