@props([
    'href'        => null,
    'static'      => false,      // plain non-interactive div (no hover, no focus)
    'active'      => false,
    'disabled'    => false,
    'variant'     => 'default',  // default | primary | danger | success | warning
    'icon'        => null,        // leading Heroicon name
    'description' => null,        // secondary line below the title
    'badge'       => null,        // quick trailing badge shorthand
    'badgeColor'  => 'zinc',      // badge colour when using the badge prop
    'chevron'     => false,       // trailing chevron (useful for nav items)
])

@php
// ── Variant maps ─────────────────────────────────────────────────────────────
$textMap = [
    'default' => 'text-zinc-700 dark:text-zinc-200',
    'primary' => 'text-primary-700 dark:text-primary-300',
    'danger'  => 'text-red-600   dark:text-red-400',
    'success' => 'text-green-600 dark:text-green-400',
    'warning' => 'text-amber-600 dark:text-amber-400',
];

$hoverMap = [
    'default' => 'hover:bg-zinc-50 dark:hover:bg-zinc-800/60',
    'primary' => 'hover:bg-primary-50 dark:hover:bg-primary-900/20',
    'danger'  => 'hover:bg-red-50   dark:hover:bg-red-900/10',
    'success' => 'hover:bg-green-50 dark:hover:bg-green-900/10',
    'warning' => 'hover:bg-amber-50 dark:hover:bg-amber-900/10',
];

$activeMap = [
    'default' => 'bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300',
    'primary' => 'bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300',
    'danger'  => 'bg-red-50   dark:bg-red-900/20  text-red-700   dark:text-red-300',
    'success' => 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300',
    'warning' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300',
];

$iconColorMap = [
    'default' => 'text-zinc-400 dark:text-zinc-500',
    'primary' => 'text-primary-500 dark:text-primary-400',
    'danger'  => 'text-red-400   dark:text-red-400',
    'success' => 'text-green-500 dark:text-green-400',
    'warning' => 'text-amber-500 dark:text-amber-400',
];

// ── Resolve element and classes ───────────────────────────────────────────────
$isInteractive = !$static || $href;
$tag           = $href ? 'a' : ($static ? 'div' : 'button');

$base = 'group flex w-full items-center gap-3 px-4 py-3 text-sm text-left transition-colors';

if ($disabled) {
    $stateClass = 'opacity-50 cursor-not-allowed pointer-events-none ' . ($textMap[$variant] ?? $textMap['default']);
} elseif ($active) {
    $stateClass = $activeMap[$variant] ?? $activeMap['default'];
} elseif ($isInteractive) {
    $stateClass = ($textMap[$variant] ?? $textMap['default'])
        . ' ' . ($hoverMap[$variant] ?? $hoverMap['default'])
        . ' focus:outline-none cursor-pointer';
} else {
    $stateClass = $textMap[$variant] ?? $textMap['default'];
}

$iconColor = $active
    ? ($iconColorMap[$variant] ?? $iconColorMap['default'])
    : ($disabled
        ? 'text-zinc-300 dark:text-zinc-600'
        : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-600 dark:group-hover:text-zinc-300 transition-colors');

$classes = trim("$base $stateClass");
@endphp

@if($tag === 'a')
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if($disabled) aria-disabled="true" @endif
    >
        @include('ui::components.list-item-inner')
    </a>

@elseif($tag === 'button')
    <button
        type="button"
        {{ $attributes->merge(['class' => $classes]) }}
        @disabled($disabled)
    >
        @include('ui::components.list-item-inner')
    </button>

@else
    <div {{ $attributes->merge(['class' => $classes]) }}>
        @include('ui::components.list-item-inner')
    </div>
@endif
