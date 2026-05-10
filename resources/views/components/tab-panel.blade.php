@props(['name'])

<div
    x-show="active === '{{ $name }}'"
    x-cloak
    {{ $attributes }}
>
    {{ $slot }}
</div>
