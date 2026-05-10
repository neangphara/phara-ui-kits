@props([
    'href' => null,
    'icon' => null,
    'iconVariant' => 'outline',
    'badge' => null,
    'badgeColor' => 'zinc',
    'current' => null,
])

@php
$base = 'flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors w-full';

$active = 'text-zinc-900 bg-zinc-100 dark:text-white dark:bg-white/10';
$inactive = 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:text-white dark:hover:bg-white/10';

$classes = $base . ' ' . ($isActive ? $active : $inactive);

$badgeColors = [
    'zinc'   => 'bg-zinc-200 text-zinc-700 dark:bg-zinc-700 dark:text-zinc-200',
    'red'    => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
    'green'  => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
    'blue'   => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
    'lime'   => 'bg-lime-100 text-lime-700 dark:bg-lime-900 dark:text-lime-300',
    'amber'  => 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300',
    'purple' => 'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300',
];

$badgeClasses = 'ml-auto inline-flex items-center rounded-full px-1.5 py-0.5 text-xs font-medium ' . ($badgeColors[$badgeColor] ?? $badgeColors['zinc']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} @if($isActive) aria-current="page" @endif>
        @if ($icon)
            <x-ui::icon :name="$icon" :variant="$iconVariant" size="sm" class="shrink-0" />
        @endif

        <span class="truncate">{{ $slot }}</span>

        @if ($badge !== null)
            <span class="{{ $badgeClasses }}">{{ $badge }}</span>
        @endif
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }} @if($isActive) aria-current="page" @endif>
        @if ($icon)
            <x-ui::icon :name="$icon" :variant="$iconVariant" size="sm" class="shrink-0" />
        @endif

        <span class="truncate">{{ $slot }}</span>

        @if ($badge !== null)
            <span class="{{ $badgeClasses }}">{{ $badge }}</span>
        @endif
    </button>
@endif
