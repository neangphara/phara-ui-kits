@props([
    'color'       => 'zinc',
    'icon'        => null,
    'iconVariant' => 'solid',
    'variant'     => 'solid', // solid | outline | ring
])

@php
$colors = [
    'zinc'   => [
        'solid'   => 'bg-zinc-400 dark:bg-zinc-600 text-white',
        'outline' => 'border-2 border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-zinc-500 dark:text-zinc-400',
        'ring'    => 'bg-zinc-400 dark:bg-zinc-600 text-white ring-4 ring-zinc-100 dark:ring-zinc-800',
    ],
    'blue'   => [
        'solid'   => 'bg-blue-500 text-white',
        'outline' => 'border-2 border-blue-400 bg-white dark:bg-zinc-900 text-blue-500',
        'ring'    => 'bg-blue-500 text-white ring-4 ring-blue-100 dark:ring-blue-900/30',
    ],
    'green'  => [
        'solid'   => 'bg-green-500 text-white',
        'outline' => 'border-2 border-green-400 bg-white dark:bg-zinc-900 text-green-500',
        'ring'    => 'bg-green-500 text-white ring-4 ring-green-100 dark:ring-green-900/30',
    ],
    'yellow' => [
        'solid'   => 'bg-yellow-400 text-white',
        'outline' => 'border-2 border-yellow-400 bg-white dark:bg-zinc-900 text-yellow-500',
        'ring'    => 'bg-yellow-400 text-white ring-4 ring-yellow-100 dark:ring-yellow-900/30',
    ],
    'red'    => [
        'solid'   => 'bg-red-500 text-white',
        'outline' => 'border-2 border-red-400 bg-white dark:bg-zinc-900 text-red-500',
        'ring'    => 'bg-red-500 text-white ring-4 ring-red-100 dark:ring-red-900/30',
    ],
    'purple' => [
        'solid'   => 'bg-purple-500 text-white',
        'outline' => 'border-2 border-purple-400 bg-white dark:bg-zinc-900 text-purple-500',
        'ring'    => 'bg-purple-500 text-white ring-4 ring-purple-100 dark:ring-purple-900/30',
    ],
    'lime'   => [
        'solid'   => 'bg-lime-500 text-white',
        'outline' => 'border-2 border-lime-400 bg-white dark:bg-zinc-900 text-lime-500',
        'ring'    => 'bg-lime-500 text-white ring-4 ring-lime-100 dark:ring-lime-900/30',
    ],
    'sky'    => [
        'solid'   => 'bg-sky-500 text-white',
        'outline' => 'border-2 border-sky-400 bg-white dark:bg-zinc-900 text-sky-500',
        'ring'    => 'bg-sky-500 text-white ring-4 ring-sky-100 dark:ring-sky-900/30',
    ],
];

$colorClass = $colors[$color][$variant] ?? $colors['zinc']['solid'];
@endphp

<span {{ $attributes->merge(['class' => 'flex h-8 w-8 shrink-0 items-center justify-center rounded-full ' . $colorClass]) }}>
    @if ($icon)
        <x-ui::icon :name="$icon" :variant="$iconVariant" class="h-4 w-4" />
    @elseif ($slot->isNotEmpty())
        {{ $slot }}
    @else
        <span class="h-2 w-2 rounded-full {{ $variant === 'outline' ? 'bg-current' : 'bg-white' }}"></span>
    @endif
</span>
