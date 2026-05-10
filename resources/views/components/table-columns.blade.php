@props([
    'sticky' => false,
])

@php
$classes = 'border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50';

if ($sticky) {
    $classes .= ' sticky top-0 z-10';
}
@endphp

<thead>
    <tr {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </tr>
</thead>
