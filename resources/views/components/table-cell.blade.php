@props([
    'align'   => 'start',
    'variant' => 'default',
    'sticky'  => false,
])

@php
$alignMap = [
    'start'  => 'text-left',
    'center' => 'text-center',
    'end'    => 'text-right',
];

$variantMap = [
    'default' => 'text-zinc-600 dark:text-zinc-300',
    'strong'  => 'font-medium text-zinc-900 dark:text-zinc-100',
];

$base        = 'px-4 py-3 whitespace-nowrap';
$alignClass  = $alignMap[$align] ?? 'text-left';
$variantClass = $variantMap[$variant] ?? $variantMap['default'];
$stickyClass = $sticky ? 'sticky left-0 bg-white dark:bg-zinc-900 z-10' : '';

$classes = trim("$base $alignClass $variantClass $stickyClass");
@endphp

<td {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</td>
