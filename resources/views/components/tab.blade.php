@props([
    'name',
    'icon'        => null,
    'iconVariant' => 'outline',
    'disabled'    => false,
])

<button
    type="button"
    @if (!$disabled) @click="active = '{{ $name }}'" @endif
    :class="tabClass('{{ $name }}')"
    class="inline-flex items-center gap-1.5 font-medium transition-colors whitespace-nowrap focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 {{ $disabled ? 'pointer-events-none cursor-not-allowed opacity-40' : 'cursor-pointer' }}"
    @if ($disabled) disabled aria-disabled="true" @endif
>
    @if ($icon)
        <x-ui::icon :name="$icon" :variant="$iconVariant" size="sm" class="shrink-0" />
    @endif
    {{ $slot }}
</button>
