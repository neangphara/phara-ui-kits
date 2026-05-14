@props([
    'items'       => [],
    'placeholder' => 'Search...',
    'name'        => null,
])

<div
    x-data="autocomplete({ items: @js($items) })"
    x-init="init()"
    @click.away="open = false"
    @resize.window="updatePosition()"
    @scroll.window.passive="updatePosition()"
    class="relative w-full"
>
    <input
        x-ref="trigger"
        x-model="query"
        @focus="onFocus()"
        @input="onFocus()"
        @keydown.arrow-down.prevent="next()"
        @keydown.arrow-up.prevent="prev()"
        @keydown.enter.prevent="select(activeIndex)"
        @keydown.escape="open = false"
        type="text"
        placeholder="{{ $placeholder }}"
        {{ $attributes->whereDoesntStartWith('wire:model')->merge(['class' => 'w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none']) }}
    />

    @if($name || $attributes->whereStartsWith('wire:model')->isNotEmpty())
        <input
            type="hidden"
            x-ref="valueInput"
            :value="selected"
            @if($name) name="{{ $name }}" @endif
            {{ $attributes->whereStartsWith('wire:model') }}
        />
    @endif

    <div
        x-ref="dropdown"
        x-show="open && filtered.length > 0"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="fixed z-[9999] max-h-60 overflow-y-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-lg py-1"
        style="display: none;"
    >
        <template x-for="(item, index) in filtered" :key="index">
            <div
                @click="select(index)"
                :data-index="index"
                :class="activeIndex === index
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800'"
                class="px-4 py-2 cursor-pointer text-sm transition"
                x-text="item"
            ></div>
        </template>
    </div>
</div>
