@props([
    'icon'    => 'plus',
    'tooltip' => null,
    'active'  => false,
    'variant' => 'default', // default | danger
    'badge'   => null,
])

@php
$variantMap = [
    'default' => 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-700/70',
    'danger'  => 'text-red-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10',
];

$activeClass = 'text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-500/10';

$classes = 'relative inline-flex items-center justify-center w-8 h-8 rounded-xl transition-colors focus:outline-none shrink-0 '
    . ($active ? $activeClass : ($variantMap[$variant] ?? $variantMap['default']));
@endphp

<button
    type="button"
    @if($tooltip) title="{{ $tooltip }}" @endif
    {{ $attributes->merge(['class' => $classes]) }}
>
    <x-ui::icon :name="$icon" class="w-4 h-4" />

    @if($badge !== null)
        <span class="absolute -top-0.5 -right-0.5 min-w-[1rem] h-4 px-0.5 flex items-center justify-center text-[9px] font-bold rounded-full bg-primary-600 text-white leading-none">
            {{ (int)$badge > 9 ? '9+' : $badge }}
        </span>
    @endif
</button>
