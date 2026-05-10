@props([
    'name'       => null,
    'label'      => null,
    'min'        => 0,
    'max'        => 100,
    'step'       => 25,
    'size'       => 'md',
    'color'      => 'primary',
    'showValue'  => true,
    'showLabels' => true,
])

@php
$sizeMap = [
    'sm' => ['track' => 'h-1',   'thumbW' => 12, 'thumb' => 'w-3 h-3', 'text' => 'text-xs'],
    'md' => ['track' => 'h-1.5', 'thumbW' => 16, 'thumb' => 'w-4 h-4', 'text' => 'text-sm'],
    'lg' => ['track' => 'h-2',   'thumbW' => 20, 'thumb' => 'w-5 h-5', 'text' => 'text-sm'],
];

$colorMap = [
    'primary' => [
        'fill'       => 'bg-primary-600 dark:bg-primary-500',
        'border'     => 'border-primary-600 dark:border-primary-500',
        'activeTick' => 'bg-primary-600 dark:bg-primary-500',
    ],
    'green'  => [
        'fill'       => 'bg-green-500',
        'border'     => 'border-green-500',
        'activeTick' => 'bg-green-500',
    ],
    'red'    => [
        'fill'       => 'bg-red-500',
        'border'     => 'border-red-500',
        'activeTick' => 'bg-red-500',
    ],
    'yellow' => [
        'fill'       => 'bg-yellow-400',
        'border'     => 'border-yellow-400',
        'activeTick' => 'bg-yellow-400',
    ],
    'sky'    => [
        'fill'       => 'bg-sky-500',
        'border'     => 'border-sky-500',
        'activeTick' => 'bg-sky-500',
    ],
];

$sizeConfig  = $sizeMap[$size]   ?? $sizeMap['md'];
$colorConfig = $colorMap[$color] ?? $colorMap['primary'];
$isDisabled  = $attributes->has('disabled');
$uniqueId    = 'slider_tick_' . ($name ?? uniqid()) . '_' . uniqid();
$initial     = (float) $attributes->get('value', $min);
$thumbW      = $sizeConfig['thumbW'];
$trackH      = $thumbW + 8;
$inset       = $thumbW / 2; // aligns tick container with thumb travel range
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
            return `calc(${this.percentage()}% + ${(0.5 - p) * {{ $thumbW }}}px)`;
        },
        ticks() {
            const count = Math.round((this.max - this.min) / {{ $step }});
            return Array.from({ length: count + 1 }, (_, i) => {
                return +((this.min + i * {{ $step }}).toFixed(10));
            });
        },
        tickPercent(tick) {
            return ((tick - this.min) / (this.max - this.min)) * 100;
        },
        tickClass(tick) {
            const isBoundary = tick === this.min || tick === this.max;
            const isActive   = tick <= this.value;
            const height     = isBoundary ? 'h-2.5' : 'h-1.5 mt-0.5';
            const color      = isActive
                ? '{{ $colorConfig['activeTick'] }}'
                : 'bg-zinc-300 dark:bg-zinc-600';
            return `${height} ${color}`;
        },
        labelClass(tick) {
            return tick <= this.value
                ? 'text-zinc-700 dark:text-zinc-200'
                : 'text-zinc-400 dark:text-zinc-500';
        }
    }"
    class="w-full {{ $isDisabled ? 'opacity-60 cursor-not-allowed' : '' }}"
>
    {{-- Label + value display --}}
    @if($label || $showValue)
        <div class="flex items-center justify-between gap-2 mb-2">
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

    {{-- Track + thumb --}}
    <div class="relative" style="height: {{ $trackH }}px">

        {{-- Track --}}
        <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 {{ $sizeConfig['track'] }} rounded-full bg-zinc-200 dark:bg-zinc-700">
            {{-- Fill --}}
            <div
                class="h-full rounded-full {{ $colorConfig['fill'] }}"
                :style="`width: ${percentage()}%`"
            ></div>
        </div>

        {{-- Thumb --}}
        <div
            class="absolute {{ $sizeConfig['thumb'] }} rounded-full bg-white dark:bg-zinc-950 border-2 {{ $colorConfig['border'] }} shadow-md pointer-events-none z-20"
            :style="`left: ${thumbLeft()}; top: 50%; transform: translateX(-50%) translateY(-50%)`"
        ></div>

        {{-- Native range input --}}
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

    {{-- Tick marks — inset by thumbW/2 so they align with the thumb's travel range --}}
    <div class="relative mt-1" style="height: 10px; margin-left: {{ $inset }}px; margin-right: {{ $inset }}px">
        <template x-for="tick in ticks()" :key="tick">
            <div
                class="absolute w-px transition-colors duration-150"
                :class="tickClass(tick)"
                :style="`left: ${tickPercent(tick)}%; transform: translateX(-50%)`"
            ></div>
        </template>
    </div>

    {{-- Tick labels --}}
    @if($showLabels)
        <div class="relative select-none" style="height: 18px; margin-left: {{ $inset }}px; margin-right: {{ $inset }}px">
            <template x-for="tick in ticks()" :key="tick">
                <span
                    class="absolute {{ $sizeConfig['text'] }} font-mono tabular-nums -translate-x-1/2 transition-colors duration-150"
                    :class="labelClass(tick)"
                    :style="`left: ${tickPercent(tick)}%`"
                    x-text="tick"
                ></span>
            </template>
        </div>
    @endif
</div>
