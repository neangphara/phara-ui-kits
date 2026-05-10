@props([
    'href' => null,
    'icon' => null,
    'iconVariant' => 'outline',
    'separator' => 'chevron-right',
])

@php
$base = 'inline-flex items-center gap-1.5 transition-colors';

$linkStyle = 'text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white';
$inactiveStyle = 'text-gray-500 dark:text-gray-400 cursor-default';

$classes = $base . ' ' . ($href ? $linkStyle : $inactiveStyle);
@endphp

<li class="inline-flex items-center [&:last-child>span.separator]:hidden">
    @if($href)
        <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
            @if($icon)
                <x-ui::icon :name="$icon" :variant="$iconVariant" size="sm" class="shrink-0" />
            @endif
            {{ $slot }}
        </a>
    @else
        <span {{ $attributes->merge(['class' => $classes]) }}>
            @if($icon)
                <x-ui::icon :name="$icon" :variant="$iconVariant" size="sm" class="shrink-0" />
            @endif
            {{ $slot }}
        </span>
    @endif

    {{-- Separator (hidden on last item) --}}
    <span class="separator inline-flex items-center mx-2 text-gray-400 dark:text-gray-500">
        @if($separator === 'slash')
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        @else
            <x-ui::icon :name="$separator" size="xs" class="shrink-0" />
        @endif
    </span>
</li>