@props([
    'name' => 'User',
    'subtitle' => null,
    'avatar' => null,
    'size' => 'md',
    'shape' => 'circle',
    'status' => null,
    'variant' => 'inline',
    'href' => null,
])

@php
$configs = [
    'sm' => ['name' => 'text-sm font-medium',  'subtitle' => 'text-xs',  'gap' => 'gap-2.5'],
    'md' => ['name' => 'text-sm font-medium',  'subtitle' => 'text-xs',  'gap' => 'gap-3'],
    'lg' => ['name' => 'text-base font-medium', 'subtitle' => 'text-sm', 'gap' => 'gap-3.5'],
    'xl' => ['name' => 'text-lg font-semibold', 'subtitle' => 'text-sm', 'gap' => 'gap-4'],
];
$c = $configs[$size] ?? $configs['md'];

$isStacked = $variant === 'stacked';
$isCard    = $variant === 'card';

$layoutClasses = ($isStacked
    ? 'flex flex-col items-center text-center '
    : 'flex items-center ') . $c['gap'];

$cardClasses  = 'rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-950 p-4';
$hoverClasses = $href ? 'transition-opacity hover:opacity-75' : '';

$textWrapClass = $isStacked ? '' : 'min-w-0 flex-1';
$truncateClass = $isStacked ? '' : 'truncate';
@endphp

{{-- Outer wrapper (card adds an extra border+bg shell) --}}
@if ($isCard)
    <div {{ $attributes->merge(['class' => $cardClasses]) }}>
        @if ($href)
            <a href="{{ $href }}" class="{{ $layoutClasses }} {{ $hoverClasses }}">
        @else
            <div class="{{ $layoutClasses }}">
        @endif
@elseif ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $layoutClasses . ' ' . $hoverClasses]) }}>
@else
    <div {{ $attributes->merge(['class' => $layoutClasses]) }}>
@endif

        <x-ui::avatar
            :src="$avatar"
            :name="$name"
            :size="$size"
            :shape="$shape"
            :status="$status"
        />

        <div class="{{ $textWrapClass }}">
            <p class="{{ $c['name'] }} {{ $truncateClass }} text-zinc-900 dark:text-white">{{ $name }}</p>
            @if ($subtitle)
                <p class="{{ $c['subtitle'] }} {{ $truncateClass }} text-zinc-500 dark:text-zinc-400">{{ $subtitle }}</p>
            @endif
        </div>

        @if ($slot->isNotEmpty())
            <div class="{{ $isStacked ? '' : 'ml-auto shrink-0' }}">{{ $slot }}</div>
        @endif

@if ($isCard)
        @if ($href) </a> @else </div> @endif
    </div>
@elseif ($href)
    </a>
@else
    </div>
@endif
