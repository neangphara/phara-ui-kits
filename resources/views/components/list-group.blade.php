@props([
    'flush'  => false, // removes outer border & border-radius
    'divide' => true,  // separator lines between items
])

@php
$base = 'overflow-hidden bg-white dark:bg-zinc-900';

$border  = $flush ? '' : 'rounded-xl border border-zinc-200 dark:border-zinc-700';
$divider = $divide ? 'divide-y divide-zinc-100 dark:divide-zinc-800' : '';

$classes = trim("$base $border $divider");
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
