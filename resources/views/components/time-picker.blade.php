@props([
    'label'       => null,
    'name'        => null,
    'value'       => null,
    'format'      => '24',  // '12' | '24'
    'withSeconds' => false,
    'step'        => 30,    // minute interval for the dropdown
])

<div
    x-data="timePicker({
        value: @js($value),
        format: '{{ $format }}',
        withSeconds: {{ $withSeconds ? 'true' : 'false' }},
        step: {{ $step }}
    })"
    x-init="init()"
    @click.away="open = false"
    @resize.window="updatePosition()"
    @scroll.window="updatePosition()"
    class="relative w-full"
>
    {{-- Label --}}
    @if ($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
            {{ $label }}
        </label>
    @endif

    {{-- Input group --}}
    <div x-ref="triggerGroup" class="flex items-center gap-2">

        {{-- Hours --}}
        <input
            type="text"
            inputmode="numeric"
            x-model="hours"
            maxlength="2"
            @input="onHoursInput()"
            @focus="$el.select()"
            x-ref="hoursInput"
            class="w-14 px-2 py-2 text-center rounded-xl border bg-white dark:bg-gray-900 border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none"
            :placeholder="format === '12' ? 'hh' : 'HH'"
        />

        <span class="font-medium text-gray-400 dark:text-gray-500 select-none">:</span>

        {{-- Minutes --}}
        <input
            type="text"
            inputmode="numeric"
            x-model="minutes"
            maxlength="2"
            @input="onMinutesInput()"
            @focus="$el.select()"
            x-ref="minutesInput"
            class="w-14 px-2 py-2 text-center rounded-xl border bg-white dark:bg-gray-900 border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="MM"
        />

        @if ($withSeconds)
            <span class="font-medium text-gray-400 dark:text-gray-500 select-none">:</span>

            {{-- Seconds --}}
            <input
                type="text"
                inputmode="numeric"
                x-model="seconds"
                maxlength="2"
                @input="onSecondsInput()"
                @focus="$el.select()"
                x-ref="secondsInput"
                class="w-14 px-2 py-2 text-center rounded-xl border bg-white dark:bg-gray-900 border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="SS"
            />
        @endif

        @if ($format === '12')
            {{-- AM / PM toggle --}}
            <button
                type="button"
                @click="toggleMeridiem()"
                class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition cursor-pointer select-none"
                x-text="meridiem"
            ></button>
        @endif

        {{-- Clock icon --}}
        <button
            type="button"
            @click="toggleDropdown()"
            class="px-2 cursor-pointer text-gray-500 dark:text-gray-400 hover:scale-105 transition-transform"
            aria-label="Open time picker"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </button>
    </div>

    {{-- Dropdown time list --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        x-ref="dropdown"
        class="fixed z-[9999] w-44 max-h-60 overflow-y-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-lg py-1"
        style="display: none;"
    >
        <template x-for="opt in timeOptions" :key="opt.value">
            <button
                type="button"
                @click="selectTime(opt.value)"
                :class="isSelected(opt.value)
                    ? 'bg-blue-600 text-white font-medium'
                    : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800'"
                :data-selected="isSelected(opt.value) ? 'true' : 'false'"
                class="w-full px-4 py-2 text-left text-sm transition"
                x-text="opt.label"
            ></button>
        </template>
    </div>

    {{-- Hidden input for form & Livewire --}}
    <input
        type="hidden"
        {{ $attributes->merge(['name' => $name]) }}
        x-model="value"
    />
</div>
