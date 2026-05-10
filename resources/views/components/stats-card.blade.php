@props([
    'title'       => '',
    'value'       => '',
    'change'      => null,
    'changeLabel' => '',
    'trend'       => null,      // up | down | neutral — auto-detected from change
    'icon'        => null,
    'iconColor'   => 'blue',    // blue | green | yellow | red | purple | sky | pink | zinc
    'description' => null,
    'variant'     => 'default', // default | outline | flat
    'loading'     => false,
])

@php
$variantClass = match ($variant) {
    'outline' => 'bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700',
    'flat'    => 'bg-gray-50 dark:bg-zinc-800/50',
    default   => 'bg-white dark:bg-zinc-900 shadow-sm border border-gray-100 dark:border-zinc-800',
};

$iconColors = [
    'blue'   => ['bg' => 'bg-blue-100 dark:bg-blue-950/50',     'text' => 'text-blue-600 dark:text-blue-400'],
    'green'  => ['bg' => 'bg-green-100 dark:bg-green-950/50',   'text' => 'text-green-600 dark:text-green-400'],
    'yellow' => ['bg' => 'bg-yellow-100 dark:bg-yellow-950/50', 'text' => 'text-yellow-600 dark:text-yellow-400'],
    'red'    => ['bg' => 'bg-red-100 dark:bg-red-950/50',       'text' => 'text-red-600 dark:text-red-400'],
    'purple' => ['bg' => 'bg-purple-100 dark:bg-purple-950/50', 'text' => 'text-purple-600 dark:text-purple-400'],
    'sky'    => ['bg' => 'bg-sky-100 dark:bg-sky-950/50',       'text' => 'text-sky-600 dark:text-sky-400'],
    'pink'   => ['bg' => 'bg-pink-100 dark:bg-pink-950/50',     'text' => 'text-pink-600 dark:text-pink-400'],
    'zinc'   => ['bg' => 'bg-zinc-100 dark:bg-zinc-800',        'text' => 'text-zinc-600 dark:text-zinc-400'],
];

$trendStyles = [
    'up'      => ['color' => 'text-green-600 dark:text-green-400', 'bg' => 'bg-green-50 dark:bg-green-950/40'],
    'down'    => ['color' => 'text-red-600 dark:text-red-400',     'bg' => 'bg-red-50 dark:bg-red-950/40'],
    'neutral' => ['color' => 'text-zinc-500 dark:text-zinc-400',   'bg' => 'bg-zinc-100 dark:bg-zinc-800'],
];

$ic = $iconColors[$iconColor] ?? $iconColors['blue'];
$tr = $trendStyles[$computedTrend] ?? $trendStyles['neutral'];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl p-5 ' . $variantClass]) }}>

    @if($loading)
        {{-- Skeleton --}}
        <div class="animate-pulse">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 space-y-3">
                    <div class="h-3.5 bg-zinc-200 dark:bg-zinc-700 rounded-full w-1/3"></div>
                    <div class="h-8 bg-zinc-200 dark:bg-zinc-700 rounded-lg w-1/2"></div>
                    <div class="h-3 bg-zinc-200 dark:bg-zinc-700 rounded-full w-2/3"></div>
                </div>
                @if($icon)
                    <div class="shrink-0 w-11 h-11 bg-zinc-200 dark:bg-zinc-700 rounded-xl"></div>
                @endif
            </div>
        </div>

    @else
        <div class="flex items-start justify-between gap-4">

            {{-- Left: content --}}
            <div class="flex-1 min-w-0">

                {{-- Title --}}
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400 truncate">
                    {{ $title }}
                </p>

                {{-- Value --}}
                <p class="mt-2 text-3xl font-bold tracking-tight text-zinc-900 dark:text-white tabular-nums truncate">
                    {{ $value }}
                </p>

                {{-- Change badge + label --}}
                @if($change)
                    <div class="mt-3 flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full {{ $tr['bg'] }} {{ $tr['color'] }}">
                            {{-- Trend icon --}}
                            @if($computedTrend === 'up')
                                <svg class="w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                    <path d="M6 2.5l4 5H2l4-5z"/>
                                </svg>
                            @elseif($computedTrend === 'down')
                                <svg class="w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                    <path d="M6 9.5L2 4.5h8L6 9.5z"/>
                                </svg>
                            @else
                                <svg class="w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                    <rect x="1" y="5.25" width="10" height="1.5" rx="0.75"/>
                                </svg>
                            @endif
                            {{ $change }}
                        </span>

                        @if($changeLabel)
                            <span class="text-xs text-zinc-400 dark:text-zinc-500">{{ $changeLabel }}</span>
                        @endif
                    </div>
                @endif

                {{-- Description --}}
                @if($description)
                    <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">
                        {{ $description }}
                    </p>
                @endif

                {{-- Extra slot --}}
                @if($slot->isNotEmpty())
                    <div class="mt-3">{{ $slot }}</div>
                @endif

            </div>

            {{-- Right: icon --}}
            @if($icon)
                <div class="shrink-0 w-11 h-11 rounded-xl flex items-center justify-center {{ $ic['bg'] }}">
                    <x-ui::icon :name="$icon" class="w-5 h-5 {{ $ic['text'] }}" />
                </div>
            @endif

        </div>
    @endif

</div>
