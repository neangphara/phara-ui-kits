@props([
    'value' => 0,
    'max' => 100,
    'size' => 'md',
    'color' => 'blue',
    'label' => null,
    'showValue' => false,
    'striped' => false,
    'animated' => false,
    'indeterminate' => false,
])

@php
$percent = $max > 0 ? min(100, max(0, ($value / $max) * 100)) : 0;
$displayValue = round($percent) . '%';

$sizes = [
    'xs' => 'h-1',
    'sm' => 'h-1.5',
    'md' => 'h-2.5',
    'lg' => 'h-4',
];

$tracks = [
    'zinc'   => 'bg-zinc-100 dark:bg-zinc-800',
    'blue'   => 'bg-blue-100 dark:bg-blue-950/40',
    'green'  => 'bg-green-100 dark:bg-green-950/40',
    'yellow' => 'bg-yellow-100 dark:bg-yellow-950/40',
    'red'    => 'bg-red-100 dark:bg-red-950/40',
    'purple' => 'bg-purple-100 dark:bg-purple-950/40',
    'lime'   => 'bg-lime-100 dark:bg-lime-950/40',
    'sky'    => 'bg-sky-100 dark:bg-sky-950/40',
];

$bars = [
    'zinc'   => 'bg-zinc-500',
    'blue'   => 'bg-blue-500',
    'green'  => 'bg-green-500',
    'yellow' => 'bg-yellow-400',
    'red'    => 'bg-red-500',
    'purple' => 'bg-purple-500',
    'lime'   => 'bg-lime-500',
    'sky'    => 'bg-sky-500',
];

$sizeClass  = $sizes[$size] ?? $sizes['md'];
$trackClass = $tracks[$color] ?? $tracks['blue'];
$barClass   = $bars[$color] ?? $bars['blue'];

$stripeStyle = ($striped || $animated)
    ? 'background-image: linear-gradient(45deg, rgba(255,255,255,.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,.15) 50%, rgba(255,255,255,.15) 75%, transparent 75%, transparent); background-size: 1rem 1rem;'
    : '';
@endphp


<div {{ $attributes->merge(['class' => 'w-full']) }}>
    {{-- Label / value row --}}
    @if ($label || $showValue)
        <div class="mb-1.5 flex items-center justify-between gap-2">
            @if ($label)
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $label }}</span>
            @endif
            @if ($showValue)
                <span class="text-sm font-medium tabular-nums text-zinc-500 dark:text-zinc-400 {{ !$label ? 'ml-auto' : '' }}">{{ $displayValue }}</span>
            @endif
        </div>
    @endif

    {{-- Track --}}
    <div
        class="relative w-full overflow-hidden rounded-full {{ $sizeClass }} {{ $trackClass }}"
        role="progressbar"
        aria-valuemin="0"
        aria-valuemax="{{ $max }}"
        @if (!$indeterminate) aria-valuenow="{{ $value }}" @endif
    >
        @if ($indeterminate)
            {{-- Sliding bar for unknown progress --}}
            <div class="ui-progress-indeterminate {{ $barClass }}"></div>
        @else
            {{-- Determinate fill --}}
            <div
                class="h-full rounded-full transition-all duration-500 {{ $barClass }} {{ $animated ? 'ui-progress-stripe-animated' : '' }}"
                style="width: {{ $percent }}%; {{ $stripeStyle }}"
            ></div>
        @endif
    </div>
</div>
