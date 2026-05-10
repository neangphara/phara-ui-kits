@props([
    'src' => null,
    'name' => 'User',
    'size' => 'md',
    'shape' => 'circle',
    'status' => null,
])

@php
    $initials = collect(explode(' ', $name))
        ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
        ->take(2)
        ->implode('');

    $sizeClasses = match ($size) {
        'sm' => 'h-8 w-8 text-xs',
        'md' => 'h-10 w-10 text-sm',
        'lg' => 'h-14 w-14 text-base',
        'xl' => 'h-20 w-20 text-lg',
        default => 'h-10 w-10 text-sm',
    };

    $shapeClasses = $shape === 'square'
        ? 'rounded-md'
        : 'rounded-full';
@endphp

<div class="relative inline-flex">
    <!-- Avatar -->
    <div class="{{ $sizeClasses }} {{ $shapeClasses }}
        flex items-center justify-center
        bg-gray-200 dark:bg-gray-700 overflow-hidden">

        @if($src)
            <img
                src="{{ $src }}"
                alt="{{ $name }}"
                class="h-full w-full object-cover"
            />
        @else
            <span class="font-medium text-gray-700 dark:text-gray-200">
                {{ $initials }}
            </span>
        @endif
    </div>

    <!-- Status Dot -->
    @if($status)
        <span
            class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white dark:border-gray-900
            {{ $status === 'online' ? 'bg-green-500' : 'bg-gray-400' }}"
        ></span>
    @endif
</div>