<div
    x-data="{ open: false }"
    class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden"
>
    <!-- Header -->
    <button
        @click="open = !open"
        class="w-full flex justify-between items-center px-4 py-3 text-left bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800"
    >
        <span class="font-medium text-gray-800 dark:text-gray-100">
            {{ $title }}
        </span>

        <svg
            :class="open ? 'rotate-180' : ''"
            class="w-5 h-5 text-gray-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Content -->
    <div
        x-show="open"
        class="px-4 py-3 text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-900"
    >
        {{ $slot }}
    </div>
</div>