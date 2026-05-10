@props([
    'type' => 'info', // info | success | warning | danger
    'title' => null,
    'icon' => null,
    'dismissible' => false,
])

@php
    $styles = match ($type) {
        'success' => [
            'bg' => 'bg-green-50 dark:bg-green-900/20',
            'border' => 'border-green-200 dark:border-green-800',
            'text' => 'text-green-800 dark:text-green-200',
            'icon' => 'text-green-500',
        ],
        'warning' => [
            'bg' => 'bg-yellow-50 dark:bg-yellow-900/20',
            'border' => 'border-yellow-200 dark:border-yellow-800',
            'text' => 'text-yellow-800 dark:text-yellow-200',
            'icon' => 'text-yellow-500',
        ],
        'danger' => [
            'bg' => 'bg-red-50 dark:bg-red-900/20',
            'border' => 'border-red-200 dark:border-red-800',
            'text' => 'text-red-800 dark:text-red-200',
            'icon' => 'text-red-500',
        ],
        default => [
            'bg' => 'bg-blue-50 dark:bg-blue-900/20',
            'border' => 'border-blue-200 dark:border-blue-800',
            'text' => 'text-blue-800 dark:text-blue-200',
            'icon' => 'text-blue-500',
        ],
    };
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    class="flex gap-3 p-4 border rounded-xl {{ $styles['bg'] }} {{ $styles['border'] }}"
>
    <!-- Icon -->
    <div class="flex-shrink-0 mt-0.5 {{ $styles['icon'] }}">
        @if($icon)
            {!! $icon !!}
        @else
            <!-- default icon -->
            @switch($type)
                @case('success')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                    @break

                @case('warning')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86l-8.3 14.36A1 1 0 003 20h18a1 1 0 00.86-1.5l-8.3-14.36a1 1 0 00-1.72 0z" />
                    </svg>
                    @break

                @case('danger')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    @break

                @default
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 18h.01" />
                    </svg>
            @endswitch
        @endif
    </div>

    <!-- Content -->
    <div class="flex-1 {{ $styles['text'] }}">
        @if($title)
            <div class="font-semibold mb-1">
                {{ $title }}
            </div>
        @endif

        <div class="text-sm">
            {{ $slot }}
        </div>
    </div>

    <!-- Close button -->
    @if($dismissible)
        <button
            @click="show = false"
            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</div>