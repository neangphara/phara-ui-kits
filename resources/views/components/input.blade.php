@props([
    'name' => null,
    'label' => null,
    'type' => 'text',
    'size' => 'md',
    'variant' => 'default',
    'icon' => null
])

@php
// Size configurations
$sizes = [
    'sm' => [
        'input' => 'px-3 py-1.5 text-sm',
        'icon' => 'left-2.5',
        'iconSize' => 'w-3.5 h-3.5',
        'iconPadding' => 'pl-8',
        'rightPadding' => 'pr-10',
        'actionButton' => 'right-2',
        'actionSize' => 'w-6 h-6',
    ],
    'md' => [
        'input' => 'px-4 py-2',
        'icon' => 'left-3',
        'iconSize' => 'w-4 h-4',
        'iconPadding' => 'pl-10',
        'rightPadding' => 'pr-11',
        'actionButton' => 'right-3',
        'actionSize' => 'w-7 h-7',
    ],
    'lg' => [
        'input' => 'px-5 py-3 text-lg',
        'icon' => 'left-4',
        'iconSize' => 'w-5 h-5',
        'iconPadding' => 'pl-12',
        'rightPadding' => 'pr-12',
        'actionButton' => 'right-3',
        'actionSize' => 'w-8 h-8',
    ],
];

// Variant configurations
$variants = [
    'default' => 'bg-white dark:bg-zinc-950 border border-gray-300 dark:border-zinc-700',
    'filled' => 'bg-gray-100 dark:bg-zinc-950 border border-transparent',
];

// Get the configuration for selected size
$sizeConfig = $sizes[$size] ?? $sizes['md'];
$variantClasses = $variants[$variant] ?? $variants['default'];

// Build base classes
$baseClasses = 'w-full rounded-xl text-gray-900 dark:text-white focus:ring-2 transition';

// Add size-specific padding
$inputClasses = $baseClasses . ' ' . $sizeConfig['input'];

// Add icon padding if icon exists
if ($icon) {
    $inputClasses .= ' ' . $sizeConfig['iconPadding'];
}

// Add variant classes
$inputClasses .= ' ' . $variantClasses;

// Handle disabled state
$isDisabled = $attributes->has('disabled');
if ($isDisabled) {
    $inputClasses .= ' opacity-60 cursor-not-allowed bg-gray-50 dark:bg-zinc-800/50 text-gray-500 dark:text-gray-400';
}

// Handle readonly state
$isReadonly = $attributes->has('readonly');
if ($isReadonly && $variant === 'default') {
    // Auto-apply filled variant for readonly inputs if not explicitly set
    $inputClasses = str_replace($variants['default'], $variants['filled'], $inputClasses);
}

// Check for errors
$hasError = $name ? $errors->has($name) : false;

// Determine if we need an action button
$needsActionButton = ($type === 'search') || ($type === 'password') || $isReadonly;

// Add right padding if action button is needed
if ($needsActionButton) {
    $inputClasses .= ' ' . $sizeConfig['rightPadding'];
}

// Handle error state - red border
if ($hasError) {
    $inputClasses = str_replace('border-gray-300 dark:border-zinc-700', 'border-red-500 dark:border-red-500', $inputClasses);
    $inputClasses = str_replace('focus:ring-primary-500 focus:border-primary-500', 'focus:ring-red-500 focus:border-red-500', $inputClasses);
} else {
    $inputClasses .= ' focus:ring-primary-500 focus:border-primary-500';
}

// Generate unique ID for this input
$uniqueId = 'input_' . ($name ?? uniqid()) . '_' . uniqid();

@endphp

@if ($label)
<label for="{{ $uniqueId }}" class="text-{{ $size }} font-medium inline-block mb-1 text-gray-700 dark:text-gray-300">
    {{ $label }}
</label>
@endif

<div
    x-data="{
        showPassword: false,
        inputValue: '{{ $attributes->get('value', '') }}',
        copyToClipboard() {
            const input = document.getElementById('{{ $uniqueId }}');
            input.select();
            document.execCommand('copy');
            this.$refs.copyBtn.innerHTML = '<span>copied</span>';
            setTimeout(() => {
                this.$refs.copyBtn.innerHTML = 'copied';
            }, 2000);
        },
        clearInput() {
            this.inputValue = '';
            document.getElementById('{{ $uniqueId }}').value = '';
            document.getElementById('{{ $uniqueId }}').focus();
        }
    }"
    class="relative"
>
    @if($icon)
        <span class="absolute inset-y-0 {{ $sizeConfig['icon'] }} flex items-center {{ $isDisabled ? 'text-gray-400 dark:text-gray-600' : 'text-gray-400 dark:text-gray-500' }}">
            <x-ui::icon :name="$icon" class="{{ $sizeConfig['iconSize'] }}" />
        </span>
    @endif

    <input
        id="{{ $uniqueId }}"
        :type="{{ $type === 'password' ? '(showPassword ? \'text\' : \'password\')' : '\'' . $type . '\'' }}"
        @if($name) name="{{ $name }}" @endif
        x-model="inputValue"
        {{ $attributes->merge(['class' => $inputClasses]) }}
    />

    @if($needsActionButton && !$isDisabled)
        <div class="absolute inset-y-0 {{ $sizeConfig['actionButton'] }} flex items-center">

            @if($type === 'search')
                {{-- Clear button for search --}}
                <button
                    type="button"
                    @click="clearInput"
                    x-show="inputValue.length > 0"
                    x-transition
                    class="p-1 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition"
                    title="Clear"
                >
                    <x-ui::icon name="x-mark" class="{{ $sizeConfig['iconSize'] }}" />
                </button>
            @elseif($type === 'password')
                {{-- Password visibility toggle --}}
                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="p-1 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition"
                    :title="showPassword ? 'Hide password' : 'Show password'"
                >
                    <x-ui::icon x-show="!showPassword" name="eye" class="{{ $sizeConfig['iconSize'] }}" />
                    <x-ui::icon x-show="showPassword" name="eye-slash" class="{{ $sizeConfig['iconSize'] }}" />
                </button>
            @elseif($isReadonly)
                {{-- Copy button for readonly --}}
                {{-- <button
                    type="button"
                    @click="copyToClipboard"
                    x-ref="copyBtn"
                    class="p-1 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition"
                    title="Copy to clipboard"
                >
                    <svg class="{{ $sizeConfig['iconSize'] }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8z"/>
                        <path d="M3 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm9 4a1 1 0 10-2 0v2H8a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V9z"/>
                    </svg>
                </button> --}}
                <x-ui::icon :size="$size" name="clipboard-document" @click="copyToClipboard" x-ref="copyBtn" />
            @endif
        </div>
    @endif
</div>

@if($hasError && $name)
    <p class="mt-1 text-sm text-red-600 dark:text-red-400">
        {{ $errors->first($name) }}
    </p>
@endif