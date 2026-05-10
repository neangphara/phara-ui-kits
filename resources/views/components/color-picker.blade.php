@props([
    'label' => null,
    'name' => null,
    'value' => '#3b82f6',
    'colors' => [
        '#ef4444', '#f97316', '#f59e0b', '#eab308',
        '#22c55e', '#10b981', '#06b6d4', '#3b82f6',
        '#6366f1', '#a855f7', '#ec4899', '#000000',
    ],
])

<div
    x-data="{
        open: false,
        value: @js($value),
        setColor(color) {
            this.value = color
            this.open = false
        }
    }"
    class="relative w-full"
>
    <!-- Label -->
    @if($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
            {{ $label }}
        </label>
    @endif

    <!-- Trigger -->
    <button
        type="button"
        @click="open = !open"
        class="w-full flex items-center justify-between px-3 py-2 border rounded-xl
               bg-white dark:bg-gray-900 border-gray-300 dark:border-gray-700
               hover:border-gray-400 dark:hover:border-gray-600 transition"
    >
        <div class="flex items-center gap-2">
            <!-- Color preview -->
            <span
                class="h-5 w-5 rounded-md border border-gray-200 dark:border-gray-700"
                :style="`background-color: ${value}`"
            ></span>

            <!-- Value -->
            <span class="text-sm text-gray-700 dark:text-gray-200">
                <span x-text="value"></span>
            </span>
        </div>

        <!-- Chevron -->
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Dropdown -->
    <div
        x-show="open"
        x-transition
        @click.away="open = false"
        class="absolute z-50 mt-2 w-full p-3 bg-white dark:bg-gray-900
               border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg"
    >
        <div class="grid grid-cols-6 gap-2">
            @foreach($colors as $color)
                <button
                    type="button"
                    @click="setColor('{{ $color }}')"
                    class="h-8 w-8 rounded-lg border border-gray-200 dark:border-gray-700
                           hover:scale-110 transition"
                    :class="value === '{{ $color }}'
                        ? 'ring-2 ring-offset-2 ring-blue-500 dark:ring-offset-gray-900'
                        : ''"
                    style="background-color: {{ $color }}"
                ></button>
            @endforeach
        </div>
    </div>

    <!-- Hidden input (for forms + Livewire fallback) -->
    <input
        type="hidden"
        {{ $attributes->merge([
            'name' => $name,
        ]) }}
        x-model="value"
    />
</div>