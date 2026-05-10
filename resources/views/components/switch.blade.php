@props([
    'name' => null,
    'value' => '1',
    'checked' => false,
    'label' => null,
    'description' => null,
    'disabled' => false,
    'size' => 'md',
    'color' => 'blue',
])

@php
// Track: h × w with flex+padding so the knob floats inside
// translateOn  = inner width - knob size = (w - 2*padding) - knob
$sizes = [
    'sm' => ['track' => 'h-5 w-9',   'pad' => 'p-0.5', 'knob' => 'size-4',  'on' => 'translate-x-4'],
    'md' => ['track' => 'h-6 w-11',  'pad' => 'p-1',   'knob' => 'size-4',  'on' => 'translate-x-5'],
    'lg' => ['track' => 'h-7 w-14',  'pad' => 'p-1',   'knob' => 'size-5',  'on' => 'translate-x-7'],
];

$colors = [
    'blue'   => 'bg-blue-500',
    'green'  => 'bg-green-500',
    'purple' => 'bg-purple-500',
    'red'    => 'bg-red-500',
    'zinc'   => 'bg-zinc-800 dark:bg-zinc-500',
    'yellow' => 'bg-yellow-400',
    'lime'   => 'bg-lime-500',
    'sky'    => 'bg-sky-500',
];

$s    = $sizes[$size] ?? $sizes['md'];
$on   = $colors[$color] ?? $colors['blue'];
$off  = 'bg-zinc-200 dark:bg-zinc-700';

$labelSize = $size === 'lg' ? 'text-base' : 'text-sm';
@endphp

<div
    x-data="{ on: {{ $checked ? 'true' : 'false' }} }"
    {{ $attributes->only('class') }}
    class="{{ $attributes->get('class') }} inline-flex items-start gap-3"
>
    {{-- Hidden checkbox for form & wire:model --}}
    <input
        type="checkbox"
        @if ($name) name="{{ $name }}" @endif
        value="{{ $value }}"
        x-model="on"
        {{ $attributes->except('class') }}
        @disabled($disabled)
        class="sr-only"
    />

    {{-- Visual track --}}
    <button
        type="button"
        role="switch"
        :aria-checked="on.toString()"
        @click="on = !on"
        @if ($disabled) disabled @endif
        :class="on ? '{{ $on }}' : '{{ $off }}'"
        class="inline-flex shrink-0 items-center rounded-full transition-colors duration-200 {{ $s['track'] }} {{ $s['pad'] }} {{ $disabled ? 'cursor-not-allowed opacity-50' : 'cursor-pointer' }} focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2"
    >
        <span
            :class="on ? '{{ $s['on'] }}' : 'translate-x-0'"
            class="rounded-full bg-white shadow-sm transition-transform duration-200 {{ $s['knob'] }}"
        ></span>
    </button>

    {{-- Label / description --}}
    @if ($label || $description || $slot->isNotEmpty())
        <div
            @if (!$disabled) @click="on = !on" @endif
            class="{{ $disabled ? 'cursor-not-allowed opacity-50' : 'cursor-pointer' }} select-none"
        >
            @if ($label)
                <p class="{{ $labelSize }} font-medium text-zinc-800 dark:text-zinc-100 leading-snug">{{ $label }}</p>
            @endif
            @if ($description)
                <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400 leading-snug">{{ $description }}</p>
            @endif
            @if ($slot->isNotEmpty())
                {{ $slot }}
            @endif
        </div>
    @endif
</div>
