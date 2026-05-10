@props([
    'speed'        => 30,      // seconds per full cycle
    'direction'    => 'left',  // left | right | up | down
    'pauseOnHover' => true,
    'gap'          => '1rem',  // CSS gap/padding between items
    'fade'         => true,    // gradient mask on edges
    'repeat'       => 2,       // number of content copies (min 2 for seamless loop)
])

@php
$copies     = max(2, (int) $repeat);
$isVertical = in_array($direction, ['up', 'down']);
$isReverse  = in_array($direction, ['right', 'down']);

$wrapperClass = trim(implode(' ', [
    'overflow-hidden relative',
    $isVertical ? 'flex flex-col' : '',
    $fade ? ($isVertical ? 'ui-mq-fade-y' : 'ui-mq-fade-x') : '',
    $pauseOnHover ? 'ui-mq-pause' : '',
]));

$trackClass = trim(implode(' ', [
    'flex',
    $isVertical ? 'flex-col ui-mq-v' : 'flex-row ui-mq-h',
    $isReverse  ? 'ui-mq-reverse' : '',
]));

// Padding direction that adds the trailing gap for seamless looping
$copyPadding = $isVertical ? "padding-bottom:{$gap}" : "padding-right:{$gap}";
$copyFlex    = $isVertical ? 'flex flex-col items-center' : 'flex flex-row items-center';
@endphp


<div {{ $attributes->merge(['class' => $wrapperClass]) }}>
    <div
        class="{{ $trackClass }}"
        style="--mq-dur:{{ $speed }}s; --mq-n:{{ $copies }}; gap:0"
        aria-hidden="true"
    >
        @for($i = 0; $i < $copies; $i++)
            <div
                class="{{ $copyFlex }} shrink-0"
                style="gap:{{ $gap }};{{ $copyPadding }}"
            >
                {{ $slot }}
            </div>
        @endfor
    </div>
</div>
