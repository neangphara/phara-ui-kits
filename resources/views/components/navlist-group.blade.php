@props([
    'label' => '',
    'icon' => null,
    'iconVariant' => 'outline',
    'collapsible' => false,
    'open' => true,
])

<div {{ $attributes->merge(['class' => 'space-y-0.5']) }}>
    @if ($collapsible)
        <div x-data="{ open: {{ $open ? 'true' : 'false' }} }">
            <button
                @click="open = !open"
                type="button"
                class="flex w-full items-center gap-2 rounded-lg px-3 py-1.5 text-xs font-semibold uppercase tracking-wider text-zinc-400 transition-colors hover:text-zinc-600 dark:text-zinc-500 dark:hover:text-zinc-300"
            >
                @if ($icon)
                    <x-ui::icon :name="$icon" :variant="$iconVariant" size="xs" class="shrink-0" />
                @endif

                <span class="flex-1 text-left">{{ $label }}</span>

                <svg
                    :class="open ? 'rotate-180' : ''"
                    class="size-3.5 shrink-0 transition-transform duration-200"
                    viewBox="0 0 16 16"
                    fill="currentColor"
                >
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" />
                </svg>
            </button>

            <div x-show="open" x-collapse class="space-y-0.5">
                {{ $slot }}
            </div>
        </div>
    @else
        <p class="px-3 pb-1 pt-0.5 text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 flex items-center gap-2">
            @if ($icon)
                <x-ui::icon :name="$icon" :variant="$iconVariant" size="xs" class="shrink-0" />
            @endif
            {{ $label }}
        </p>

        {{ $slot }}
    @endif
</div>
