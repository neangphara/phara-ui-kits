@props([])

<ul {{ $attributes->merge(['class' => 'relative']) }}>
    {{ $slot }}
</ul>
