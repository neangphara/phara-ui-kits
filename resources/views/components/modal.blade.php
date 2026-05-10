@props([
    'id'       => 'modal',
    'maxWidth' => 'md',   // sm | md | lg | xl | 2xl
    'title'    => null,
])

@php
$maxWidthClass = [
    'sm'  => 'max-w-sm',
    'md'  => 'max-w-md',
    'lg'  => 'max-w-lg',
    'xl'  => 'max-w-xl',
    '2xl' => 'max-w-2xl',
][$maxWidth] ?? 'max-w-md';
@endphp

{{-- Backdrop --}}
<div
    x-data="{ open: false }"
    x-on:open-modal.window="if ($event.detail.id === '{{ $id }}') open = true"
    x-on:close-modal.window="if ($event.detail.id === '{{ $id }}') open = false"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display: none;"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
>
    {{-- Overlay --}}
    <div
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
        x-on:click="open = false"
    ></div>

    {{-- Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="relative w-full {{ $maxWidthClass }} bg-white rounded-2xl shadow-2xl ring-1 ring-black/5 overflow-hidden"
    >
        {{-- Header --}}
        @if ($title)
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 id="{{ $id }}-title" class="text-base font-semibold text-gray-900">
                    {{ $title }}
                </h2>
                <button
                    type="button"
                    x-on:click="open = false"
                    class="rounded-lg p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"
                    aria-label="Close"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @else
            {{-- Close button without header --}}
            <button
                type="button"
                x-on:click="open = false"
                class="absolute top-3 right-3 z-10 rounded-lg p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"
                aria-label="Close"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        @endif

        {{-- Body --}}
        <div class="px-6 py-5 text-sm text-gray-600 leading-relaxed">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        @isset($footer)
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>