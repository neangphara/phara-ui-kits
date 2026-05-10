@props([
    'name',
    'label' => null,
    'size' => 'md',
    'variant' => 'default',
    'placeholder' => null,
    'options' => [],
])

@php
// Size configurations
$sizes = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2',
    'lg' => 'px-5 py-3 text-lg',
];

// Variant configurations
$variants = [
    'default' => 'bg-white dark:bg-zinc-950 border border-gray-300 dark:border-zinc-700',
    'filled' => 'bg-gray-100 dark:bg-zinc-950 border border-transparent',
];

// Get the configuration for selected size and variant
$sizeClasses = $sizes[$size] ?? $sizes['md'];
$variantClasses = $variants[$variant] ?? $variants['default'];

// Build base classes
$baseClasses = 'w-full rounded-xl text-gray-900 dark:text-white focus:ring-2 transition appearance-none cursor-pointer';

// Build select classes
$selectClasses = $baseClasses . ' ' . $sizeClasses . ' ' . $variantClasses;

// Handle disabled state
$isDisabled = $attributes->has('disabled');
if ($isDisabled) {
    $selectClasses .= ' opacity-60 cursor-not-allowed bg-gray-50 dark:bg-zinc-800/50 text-gray-500 dark:text-gray-400';
}

// Check for errors
$hasError = $errors->has($name);

// Handle error state - red border
if ($hasError) {
    $selectClasses = str_replace('border-gray-300 dark:border-zinc-700', 'border-red-500 dark:border-red-500', $selectClasses);
    $selectClasses = str_replace('focus:ring-primary-500 focus:border-primary-500', 'focus:ring-red-500 focus:border-red-500', $selectClasses);
} else {
    $selectClasses .= ' focus:ring-primary-500 focus:border-primary-500';
}

// Add right padding for dropdown icon
$selectClasses .= ' pr-10';

// Generate unique ID for this select
$uniqueId = 'select_' . $name . '_' . uniqid();

@endphp

@if ($label)
<label for="{{ $uniqueId }}" class="text-{{ $size }} font-medium inline-block mb-1 text-gray-700 dark:text-gray-300">
    {{ $label }}
</label>
@endif

<div class="relative">
    <select
        id="{{ $uniqueId }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => $selectClasses]) }}
    >
        @if($placeholder)
            <option value="" disabled selected>{{ $placeholder }}</option>
        @endif

        @if(!empty($options))
            @foreach($options as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        @else
            {{ $slot }}
        @endif
    </select>

    {{-- Dropdown Icon --}}
    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none {{ $isDisabled ? 'text-gray-400 dark:text-gray-600' : 'text-gray-400 dark:text-gray-500' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
</div>

@if($hasError)
    <p class="mt-1 text-sm text-red-600 dark:text-red-400">
        {{ $errors->first($name) }}
    </p>
@endif
