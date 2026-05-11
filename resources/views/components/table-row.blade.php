@props([
    'href'  => null,
    'value' => null,
])

@php
$base = 'transition-colors';
@endphp

@if($href && $value !== null)
    <tr {{ $attributes->merge(['class' => $base . ' cursor-pointer']) }}
        :class="selected.includes('{{ $value }}') ? 'bg-primary-50 dark:bg-primary-900/10' : 'hover:bg-zinc-50 dark:hover:bg-zinc-800/40'"
        x-data @click="window.location.href = '{{ $href }}'">
        <td class="w-10 px-4 py-3">
            <input
                type="checkbox"
                data-row-id="{{ $value }}"
                class="h-4 w-4 rounded border-zinc-300 dark:border-zinc-600
                       bg-white dark:bg-zinc-900 text-primary-600
                       focus:ring-2 focus:ring-primary-500 focus:ring-offset-0 cursor-pointer"
                :checked="selected.includes('{{ $value }}')"
                @click.stop="toggle('{{ $value }}')"
            />
        </td>
        {{ $slot }}
    </tr>
@elseif($value !== null)
    <tr {{ $attributes->merge(['class' => $base]) }}
        :class="selected.includes('{{ $value }}') ? 'bg-primary-50 dark:bg-primary-900/10' : 'hover:bg-zinc-50 dark:hover:bg-zinc-800/40'">
        <td class="w-10 px-4 py-3">
            <input
                type="checkbox"
                data-row-id="{{ $value }}"
                class="h-4 w-4 rounded border-zinc-300 dark:border-zinc-600
                       bg-white dark:bg-zinc-900 text-primary-600
                       focus:ring-2 focus:ring-primary-500 focus:ring-offset-0 cursor-pointer"
                :checked="selected.includes('{{ $value }}')"
                @click.stop="toggle('{{ $value }}')"
            />
        </td>
        {{ $slot }}
    </tr>
@elseif($href)
    <tr {{ $attributes->merge(['class' => $base . ' hover:bg-zinc-50 dark:hover:bg-zinc-800/40 cursor-pointer']) }} x-data @click="window.location.href = '{{ $href }}'">
        {{ $slot }}
    </tr>
@else
    <tr {{ $attributes->merge(['class' => $base . ' hover:bg-zinc-50 dark:hover:bg-zinc-800/40']) }}>
        {{ $slot }}
    </tr>
@endif
