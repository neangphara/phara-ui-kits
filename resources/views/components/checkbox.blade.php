@props([
    'name' => null,
    'value' => '1',
    'checked' => false,
    'label' => null,
    'description' => null,
    'disabled' => false,
    'variant' => 'default', // default | success | warning | danger
])

@php
    $colors = match ($variant) {
        'success' => 'text-green-600 focus:ring-green-500',
        'warning' => 'text-yellow-600 focus:ring-yellow-500',
        'danger' => 'text-red-600 focus:ring-red-500',
        default => 'text-blue-600 focus:ring-blue-500',
    };
@endphp

<label class="flex items-start gap-3 cursor-pointer select-none">

    <!-- Checkbox -->
    <input
        type="checkbox"
        name="{{ $name }}"
        value="{{ $value }}"
        @checked($checked)
        @disabled($disabled)
        class="mt-0.5 h-4 w-4 rounded border-gray-300 dark:border-gray-600
               bg-white dark:bg-gray-900
               focus:ring-2 {{ $colors }}"
    />

    <!-- Text -->
    <div class="flex flex-col">
        @if($label)
            <span class="text-sm font-medium text-gray-800 dark:text-gray-100">
                {{ $label }}
            </span>
        @endif

        @if($description)
            <span class="text-xs text-gray-500 dark:text-gray-400">
                {{ $description }}
            </span>
        @endif
    </div>

</label>