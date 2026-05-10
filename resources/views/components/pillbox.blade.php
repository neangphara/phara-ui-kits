@props([
    'placeholder' => 'Select options...',
    'searchPlaceholder' => 'Search...',
    'size' => 'md',
    'searchable' => false,
    'disabled' => false,
    'invalid' => false,
    'name' => null,
    'value' => [],
])

@php
$sizes = [
    'sm' => ['container' => 'min-h-8 px-2.5 py-1', 'gap' => 'gap-1',   'pill' => 'text-xs px-2 py-0.5'],
    'md' => ['container' => 'min-h-10 px-3 py-2',  'gap' => 'gap-1.5', 'pill' => 'text-xs px-2.5 py-1'],
];
$s = $sizes[$size] ?? $sizes['md'];

$borderClass = $invalid ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-zinc-700';
$ringClass   = $invalid ? 'ring-red-500' : 'ring-primary-500';
@endphp

<div
    x-data="{
        options: [],
        selected: {{ Js::from($value) }},
        open: false,
        search: '',

        init() {
            this.$nextTick(() => {
                this.$el.querySelectorAll('[data-pillbox-option]').forEach(el => {
                    this.options.push({ value: el.dataset.value, label: el.dataset.label });
                });
            });
        },

        openDropdown() {
            if ({{ $disabled ? 'true' : 'false' }}) return;
            this.open = true;
            this.$nextTick(() => {
                this.updatePosition();
                {{ $searchable ? "if (this.\$refs.search) this.\$refs.search.focus();" : '' }}
            });
        },

        closeDropdown() {
            this.open = false;
            this.search = '';
        },

        toggle(value) {
            if (this.isSelected(value)) {
                this.selected = this.selected.filter(v => v !== value);
            } else {
                this.selected.push(value);
            }
        },

        deselect(value) {
            this.selected = this.selected.filter(v => v !== value);
        },

        isSelected(value) {
            return this.selected.includes(value);
        },

        getLabel(value) {
            return this.options.find(o => o.value === value)?.label || value;
        },

        get hasFilteredResults() {
            if (!this.search) return this.options.length > 0;
            const q = this.search.toLowerCase();
            return this.options.some(o => o.label.toLowerCase().includes(q));
        },

        updatePosition() {
            if (!this.open) return;
            const trigger = this.$refs.trigger;
            const menu = this.$refs.menu;
            if (!trigger || !menu) return;
            const rect = trigger.getBoundingClientRect();
            menu.style.top = (rect.bottom + 4) + 'px';
            menu.style.left = rect.left + 'px';
            menu.style.width = rect.width + 'px';
        }
    }"
    @click.away="closeDropdown()"
    @keydown.escape="closeDropdown()"
    @resize.window="updatePosition()"
    @scroll.window="updatePosition()"
    class="relative"
>
    @if ($name)
        <template x-for="value in selected" :key="value">
            <input type="hidden" name="{{ $name }}[]" :value="value">
        </template>
    @endif

    {{-- Trigger --}}
    <div
        x-ref="trigger"
        tabindex="{{ $disabled ? '-1' : '0' }}"
        role="combobox"
        aria-haspopup="listbox"
        :aria-expanded="open"
        @click="open ? closeDropdown() : openDropdown()"
        @keydown.enter.prevent="open ? closeDropdown() : openDropdown()"
        @keydown.space.prevent="open ? closeDropdown() : openDropdown()"
        :class="open ? 'ring-2 {{ $ringClass }}' : ''"
        class="relative flex w-full flex-wrap items-center {{ $s['gap'] }} {{ $s['container'] }} rounded-xl border {{ $borderClass }} bg-white dark:bg-zinc-950 pr-9 outline-none transition-colors {{ $disabled ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer' }}"
    >
        {{-- Pills --}}
        <template x-for="value in selected" :key="value">
            <span class="inline-flex items-center gap-1 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 {{ $s['pill'] }} font-medium">
                <span x-text="getLabel(value)"></span>
                @unless ($disabled)
                    <button
                        type="button"
                        @click.stop="deselect(value)"
                        class="rounded text-zinc-400 transition-colors hover:text-zinc-600 dark:hover:text-zinc-200"
                        :aria-label="'Remove ' + getLabel(value)"
                    >
                        <svg class="size-3" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M5.28 4.22a.75.75 0 0 0-1.06 1.06L6.94 8l-2.72 2.72a.75.75 0 1 0 1.06 1.06L8 9.06l2.72 2.72a.75.75 0 1 0 1.06-1.06L9.06 8l2.72-2.72a.75.75 0 0 0-1.06-1.06L8 6.94 5.28 4.22Z" />
                        </svg>
                    </button>
                @endunless
            </span>
        </template>

        {{-- Placeholder --}}
        <span
            x-show="selected.length === 0"
            class="select-none text-sm text-zinc-400 dark:text-zinc-500"
        >{{ $placeholder }}</span>

        {{-- Chevron --}}
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg
                :class="open ? 'rotate-180' : ''"
                class="size-4 text-zinc-400 transition-transform duration-200 dark:text-zinc-500"
                viewBox="0 0 16 16"
                fill="currentColor"
            >
                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" />
            </svg>
        </div>
    </div>

    {{-- Dropdown --}}
    <div
        x-ref="menu"
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="fixed z-[9999] overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-black/5 dark:bg-zinc-800 dark:ring-white/10"
        style="display: none;"
        x-cloak
    >
        @if ($searchable)
            <div class="border-b border-gray-100 p-2 dark:border-zinc-700">
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5">
                        <svg class="size-3.5 text-zinc-400" viewBox="0 0 16 16" fill="currentColor">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" />
                        </svg>
                    </div>
                    <input
                        x-ref="search"
                        x-model="search"
                        type="text"
                        placeholder="{{ $searchPlaceholder }}"
                        class="w-full rounded-lg border-0 bg-zinc-50 py-1.5 pl-8 pr-3 text-sm text-zinc-900 placeholder-zinc-400 outline-none focus:ring-2 focus:ring-primary-500 dark:bg-zinc-700 dark:text-white dark:placeholder-zinc-500"
                        @keydown.escape.stop="closeDropdown()"
                    >
                </div>
            </div>
        @endif

        <div class="max-h-60 overflow-y-auto py-1" role="listbox" aria-multiselectable="true">
            {{ $slot }}

            <p
                x-show="!hasFilteredResults"
                x-cloak
                class="px-3 py-6 text-center text-sm text-zinc-400 dark:text-zinc-500"
            >No options found.</p>
        </div>
    </div>
</div>
