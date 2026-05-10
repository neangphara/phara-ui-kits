@props([
    'name'      => null,
    'label'     => null,
    'min'       => 0,
    'max'       => 100,
    'step'      => 1,
    'size'      => 'md',
    'color'     => 'primary',
    'showValue' => true,
    'showRange' => true,
])

@php
$sizeMap = [
    'sm' => ['track' => 'h-1',   'thumbW' => 12, 'thumb' => 'w-3 h-3', 'text' => 'text-xs'],
    'md' => ['track' => 'h-1.5', 'thumbW' => 16, 'thumb' => 'w-4 h-4', 'text' => 'text-sm'],
    'lg' => ['track' => 'h-2',   'thumbW' => 20, 'thumb' => 'w-5 h-5', 'text' => 'text-sm'],
];

$colorMap = [
    'primary' => ['fill' => 'bg-primary-600 dark:bg-primary-500', 'border' => 'border-primary-600 dark:border-primary-500'],
    'green'   => ['fill' => 'bg-green-500',  'border' => 'border-green-500'],
    'red'     => ['fill' => 'bg-red-500',    'border' => 'border-red-500'],
    'yellow'  => ['fill' => 'bg-yellow-400', 'border' => 'border-yellow-400'],
    'sky'     => ['fill' => 'bg-sky-500',    'border' => 'border-sky-500'],
];

$sizeConfig  = $sizeMap[$size]   ?? $sizeMap['md'];
$colorConfig = $colorMap[$color] ?? $colorMap['primary'];
$isDisabled  = $attributes->has('disabled');
$uniqueId    = 'slider_' . ($name ?? uniqid()) . '_' . uniqid();
$initial     = (float) $attributes->get('value', $min);
$thumbW      = $sizeConfig['thumbW'];
$trackH      = $thumbW + 8; // container height gives thumb room
@endphp

<div
    x-data="{
        value: {{ $initial }},
        min: {{ $min }},
        max: {{ $max }},
        percentage() {
            return ((this.value - this.min) / (this.max - this.min)) * 100;
        },
        thumbLeft() {
            const p = this.percentage() / 100;
            return `calc(${this.percentage()}% + ${(0.5 - p) * {{ $thumbW }}  }px)`;
        }
    }"
    class="w-full space-y-2 {{ $isDisabled ? 'opacity-60 cursor-not-allowed' : '' }}"
>
    {{-- Label + value display --}}
    @if($label || $showValue)
        <div class="flex items-center justify-between gap-2">
            @if($label)
                <label
                    for="{{ $uniqueId }}"
                    class="{{ $sizeConfig['text'] }} font-medium text-zinc-700 dark:text-zinc-300 {{ $isDisabled ? 'cursor-not-allowed' : 'cursor-default' }}"
                >
                    {{ $label }}
                </label>
            @endif

            @if($showValue)
                <span
                    x-text="value"
                    class="{{ $sizeConfig['text'] }} font-mono font-semibold tabular-nums text-zinc-900 dark:text-zinc-100 min-w-[2.5rem] text-right"
                ></span>
            @endif
        </div>
    @endif

    {{-- Slider track + thumb --}}
    <div class="relative" style="height: {{ $trackH }}px">

        {{-- Track background --}}
        <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 {{ $sizeConfig['track'] }} rounded-full bg-zinc-200 dark:bg-zinc-700">
            {{-- Filled portion --}}
            <div
                class="h-full rounded-full {{ $colorConfig['fill'] }}"
                :style="`width: ${percentage()}%`"
            ></div>
        </div>

        {{-- Custom thumb --}}
        <div
            class="absolute {{ $sizeConfig['thumb'] }} rounded-full bg-white dark:bg-zinc-950 border-2 {{ $colorConfig['border'] }} shadow-md pointer-events-none"
            :style="`left: ${thumbLeft()}; top: 50%; transform: translateX(-50%) translateY(-50%)`"
        ></div>

        {{-- Native range input (invisible — handles all interaction and accessibility) --}}
        <input
            id="{{ $uniqueId }}"
            type="range"
            min="{{ $min }}"
            max="{{ $max }}"
            step="{{ $step }}"
            x-model.number="value"
            @if($name) name="{{ $name }}" @endif
            {{ $attributes->except(['value', 'class'])->merge([
                'class' => 'absolute inset-0 w-full h-full opacity-0 z-10 ' . ($isDisabled ? 'cursor-not-allowed' : 'cursor-pointer'),
            ]) }}
        />
    </div>

    {{-- Min / max range labels --}}
    @if($showRange)
        <div class="{{ $sizeConfig['text'] }} flex justify-between text-zinc-400 dark:text-zinc-500 tabular-nums select-none">
            <span>{{ $min }}</span>
            <span>{{ $max }}</span>
        </div>
    @endif
</div>
