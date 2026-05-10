@props(['variant' => 'pulse'])


<div
    {{ $attributes->merge([
        'class' => 'rounded ' . ($variant === 'shimmer'
            ? 'ui-skeleton-shimmer'
            : 'animate-pulse bg-zinc-200 dark:bg-zinc-700'),
    ]) }}
></div>
