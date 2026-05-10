@props([
    'label' => null,
    'name' => null,
    'value' => null,
    'format' => 'mdy', // mdy | dmy
])

<div
    x-data="datePicker3({
        value: @js($value),
        format: '{{ $format }}'
    })"
    x-init="init()"
    @click.away="open = false"
    @resize.window="updatePosition()"
    @scroll.window="updatePosition()"
    class="relative w-full"
>
    {{-- Label --}}
    @if($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
            {{ $label }}
        </label>
    @endif

    {{-- INPUT GROUP --}}
    <div x-ref="trigger" class="flex items-center gap-2">
        
        <!-- First -->
        <input
            type="text"
            x-model="first"
            maxlength="2"
            @input="updateFromParts()"
            class="w-16 px-2 py-2 text-center rounded-xl border bg-white dark:bg-gray-900 border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
            :placeholder="format === 'mdy' ? 'MM' : 'DD'"
        />

        <span>/</span>

        <!-- Second -->
        <input
            type="text"
            x-model="second"
            maxlength="2"
            @input="updateFromParts()"
            class="w-16 px-2 py-2 text-center rounded-xl border bg-white dark:bg-gray-900 border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
            :placeholder="format === 'mdy' ? 'DD' : 'MM'"
        />

        <span>/</span>

        <!-- Year -->
        <input
            type="text"
            x-model="year"
            maxlength="4"
            @input="updateFromParts()"
            class="w-20 px-2 py-2 text-center rounded-xl border bg-white dark:bg-gray-900 border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
            placeholder="YYYY"
        />

        <!-- Calendar -->
        <button type="button" @click="toggleCalendar()" class="px-2 cursor-pointer hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
              </svg>              
        </button>
    </div>

    {{-- CALENDAR --}}
    <div
        x-show="open"
        x-transition
        x-ref="calendar"
        class="fixed z-[9999] w-72 p-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg"
        style="display: none;"
    >
        <!-- Header -->
        <div class="flex justify-between mb-3">
            <button @click="prev()">‹</button>

            <div class="text-sm font-medium text-gray-700 dark:text-gray-200">
                <span x-text="monthName"></span>
                <span x-text="currentYear"></span>
            </div>

            <button @click="next()">›</button>
        </div>

        <!-- Days -->
        <div class="grid grid-cols-7 text-xs text-center text-gray-500 mb-1">
            <template x-for="d in days"><div x-text="d"></div></template>
        </div>

        <div class="grid grid-cols-7 text-sm text-center">
            <template x-for="blank in blanks"><div></div></template>

            <template x-for="day in monthDays">
                <button
                    @click="pick(day)"
                    class="p-2 rounded-lg hover:bg-blue-100 dark:hover:bg-gray-800 transition"
                    :class="isSelected(day) ? 'bg-blue-600 text-white' : 'text-gray-700 dark:text-gray-200'"
                >
                    <span x-text="day"></span>
                </button>
            </template>
        </div>
    </div>

    {{-- Hidden input (Livewire + form) --}}
    <input
        type="hidden"
        {{ $attributes->merge(['name' => $name]) }}
        x-model="value"
    />
</div>

