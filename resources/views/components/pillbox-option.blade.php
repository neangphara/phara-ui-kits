@props([
    'value',
    'icon' => null,
    'iconVariant' => 'outline',
])

@php
    $label = trim(strip_tags((string) $slot));
@endphp

<button
    data-pillbox-option
    data-value="{{ $value }}"
    data-label="{{ $label }}"
    type="button"
    x-show="!search || $el.dataset.label.toLowerCase().includes(search.toLowerCase())"
    @click="toggle({{ Js::from($value) }})"
    :class="isSelected({{ Js::from($value) }}) ? 'bg-zinc-50 dark:bg-zinc-700/50 text-zinc-900 dark:text-white font-medium' : 'text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 hover:text-zinc-900 dark:hover:text-white'"
    class="relative flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm transition-colors"
    role="option"
    :aria-selected="isSelected({{ Js::from($value) }})"
>
    @if ($icon)
        <x-ui::icon :name="$icon" :variant="$iconVariant" size="sm" class="shrink-0 text-zinc-400 dark:text-zinc-500" />
    @endif

    <span class="flex-1 truncate">{{ $slot }}</span>

    <svg
        x-show="isSelected({{ Js::from($value) }})"
        class="ml-auto size-4 shrink-0 text-zinc-900 dark:text-white"
        viewBox="0 0 16 16"
        fill="currentColor"
    >
        <path fill-rule="evenodd" clip-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" />
    </svg>
</button>
