@props([
    'color' => 'zinc',
    'variant' => 'default', // default | solid
    'size' => 'md',
    'icon' => null,
    'rounded' => 'md',
    'iconPosition' => 'left',
])

@php
// 🎨 Real Tailwind colors
$colors = [
    'zinc' => [
        'default' => 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300',
        'solid' => 'bg-zinc-700 text-white'
    ],
    'lime' => [
        'default' => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400',
        'solid' => 'bg-lime-500 text-white'
    ],
    'green' => [
        'default' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
        'solid' => 'bg-green-600 text-white'
    ],
    'yellow' => [
        'default' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
        'solid' => 'bg-yellow-400 text-white'
    ],
    'red' => [
        'default' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
        'solid' => 'bg-red-600 text-white'
    ],
    'sky' => [
        'default' => 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-400',
        'solid' => 'bg-sky-500 text-white'
    ],
];

// 📏 Sizes
$sizes = [
    'sm' => 'text-xs px-2 py-0.5',
    'md' => 'text-sm px-2.5 py-0.5',
    'lg' => 'text-sm px-3 py-1',
];

// 🔘 Rounded
$roundedMap = [
    'md' => 'rounded-md',
    'full' => 'rounded-full',
];

$base = 'inline-flex items-center gap-1.5 font-medium';

$colorClasses = $colors[$color][$variant] ?? $colors['zinc']['default'];
$sizeClasses = $sizes[$size] ?? $sizes['md'];
$rounded = $roundedMap[$rounded] ?? 'rounded-md';

$classes = "$base $colorClasses $sizeClasses $rounded";
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon && $iconPosition == "left")
        <x-ui::icon :name="$icon" variant="solid" class="w-3.5 h-3.5" />
    @endif

    {{ $slot }}

    @if($icon && $iconPosition == "right")
        <x-ui::icon :name="$icon" variant="solid" class="w-3.5 h-3.5" />
    @endif
</span>