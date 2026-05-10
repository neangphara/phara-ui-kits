@props([
    'size'    => 'md',      // sm | md | lg | xl
    'variant' => 'ring',   // ring | dots | bars | pulse
    'color'   => 'primary', // primary | white | zinc | current
    'label'   => null,      // visible label shown next to the spinner
])

@php
$sizeMap = [
    'sm' => ['icon' => 'w-4 h-4',   'barH' => 'h-4',  'dot' => 'w-1.5 h-1.5', 'bar' => 'w-1',   'text' => 'text-xs'],
    'md' => ['icon' => 'w-5 h-5',   'barH' => 'h-5',  'dot' => 'w-2 h-2',     'bar' => 'w-1',   'text' => 'text-sm'],
    'lg' => ['icon' => 'w-7 h-7',   'barH' => 'h-7',  'dot' => 'w-2.5 h-2.5', 'bar' => 'w-1.5', 'text' => 'text-base'],
    'xl' => ['icon' => 'w-10 h-10', 'barH' => 'h-10', 'dot' => 'w-3 h-3',     'bar' => 'w-2',   'text' => 'text-lg'],
];

$colorMap = [
    'primary' => 'text-primary-600 dark:text-primary-400',
    'white'   => 'text-white',
    'zinc'    => 'text-zinc-500 dark:text-zinc-400',
    'current' => 'text-current',
];

$sc         = $sizeMap[$size]   ?? $sizeMap['md'];
$colorClass = $colorMap[$color] ?? $colorMap['primary'];
@endphp


<span
    role="status"
    aria-label="{{ $label ?? 'Loading' }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 ' . $colorClass]) }}
>
    {{-- ── Ring ────────────────────────────────────────────────────── --}}
    @if($variant === 'ring')
        <svg
            class="{{ $sc['icon'] }} animate-spin shrink-0"
            viewBox="0 0 24 24"
            fill="none"
            aria-hidden="true"
        >
            <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-80" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
        </svg>

    {{-- ── Dots ────────────────────────────────────────────────────── --}}
    @elseif($variant === 'dots')
        <span class="inline-flex items-end gap-1 shrink-0" aria-hidden="true">
            <span class="{{ $sc['dot'] }} rounded-full bg-current animate-bounce" style="animation-duration:.7s;animation-delay:0s"></span>
            <span class="{{ $sc['dot'] }} rounded-full bg-current animate-bounce" style="animation-duration:.7s;animation-delay:.14s"></span>
            <span class="{{ $sc['dot'] }} rounded-full bg-current animate-bounce" style="animation-duration:.7s;animation-delay:.28s"></span>
        </span>

    {{-- ── Bars ────────────────────────────────────────────────────── --}}
    @elseif($variant === 'bars')
        <span class="inline-flex items-end gap-0.5 {{ $sc['barH'] }} shrink-0" aria-hidden="true">
            <span class="{{ $sc['bar'] }} h-full rounded-full bg-current ui-spin-bar" style="animation-delay:0s"></span>
            <span class="{{ $sc['bar'] }} h-full rounded-full bg-current ui-spin-bar" style="animation-delay:.1s"></span>
            <span class="{{ $sc['bar'] }} h-full rounded-full bg-current ui-spin-bar" style="animation-delay:.2s"></span>
            <span class="{{ $sc['bar'] }} h-full rounded-full bg-current ui-spin-bar" style="animation-delay:.3s"></span>
            <span class="{{ $sc['bar'] }} h-full rounded-full bg-current ui-spin-bar" style="animation-delay:.15s"></span>
        </span>

    {{-- ── Pulse ───────────────────────────────────────────────────── --}}
    @elseif($variant === 'pulse')
        <span class="{{ $sc['icon'] }} relative inline-flex shrink-0" aria-hidden="true">
            <span class="absolute inset-0 animate-ping rounded-full bg-current opacity-60"></span>
            <span class="{{ $sc['icon'] }} rounded-full bg-current opacity-30"></span>
        </span>
    @endif

    {{-- ── Optional visible label / sr-only fallback ──────────────── --}}
    @if($label)
        <span class="{{ $sc['text'] }} font-medium">{{ $label }}</span>
    @else
        <span class="sr-only">Loading</span>
    @endif
</span>
