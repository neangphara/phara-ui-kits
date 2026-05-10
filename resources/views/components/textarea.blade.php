@props([
    'name'       => null,
    'label'      => null,
    'size'       => 'md',
    'variant'    => 'default',
    'rows'       => 4,
    'autoResize' => false,
    'maxLength'  => null,
    'showCount'  => false,
])

@php
$sizes = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2',
    'lg' => 'px-5 py-3 text-lg',
];

$variants = [
    'default' => 'bg-white dark:bg-zinc-950 border border-gray-300 dark:border-zinc-700',
    'filled'  => 'bg-gray-100 dark:bg-zinc-950 border border-transparent',
];

$sizeClasses    = $sizes[$size] ?? $sizes['md'];
$variantClasses = $variants[$variant] ?? $variants['default'];

$baseClasses     = 'w-full rounded-xl text-gray-900 dark:text-white focus:ring-2 transition';
$textareaClasses = $baseClasses . ' ' . $sizeClasses . ' ' . $variantClasses;

$isDisabled = $attributes->has('disabled');
if ($isDisabled) {
    $textareaClasses .= ' opacity-60 cursor-not-allowed bg-gray-50 dark:bg-zinc-800/50 text-gray-500 dark:text-gray-400';
}

$hasError = $name ? $errors->has($name) : false;

if ($hasError) {
    $textareaClasses = str_replace(
        'border-gray-300 dark:border-zinc-700',
        'border-red-500 dark:border-red-500',
        $textareaClasses
    );
    $textareaClasses .= ' focus:ring-red-500';
} else {
    $textareaClasses .= ' focus:ring-primary-500';
}

if ($autoResize) {
    $textareaClasses .= ' resize-none overflow-hidden';
}

$uniqueId = 'textarea_' . ($name ?? uniqid()) . '_' . uniqid();
@endphp

@if ($label)
    <label for="{{ $uniqueId }}" class="text-{{ $size }} font-medium inline-block mb-1 text-gray-700 dark:text-gray-300">
        {{ $label }}
    </label>
@endif

<div
    x-data="{
        value: '',
        init() {
            this.value = this.$refs.ta.value;
            {{ $autoResize ? '$nextTick(() => this.grow())' : '' }}
        },
        {{ $autoResize ? 'grow() { const el = this.$refs.ta; el.style.height = \'auto\'; el.style.height = el.scrollHeight + \'px\'; },' : '' }}
    }"
    class="relative"
>
    <textarea
        id="{{ $uniqueId }}"
        x-ref="ta"
        x-model="value"
        @if ($autoResize) @input="grow()" @endif
        @if ($name) name="{{ $name }}" @endif
        rows="{{ $rows }}"
        @if ($maxLength) maxlength="{{ $maxLength }}" @endif
        {{ $attributes->merge(['class' => $textareaClasses]) }}
    >{{ $slot }}</textarea>

    @if ($showCount || $maxLength)
        <div class="mt-1.5 flex justify-end">
            <span class="tabular-nums text-xs text-zinc-400 dark:text-zinc-500">
                <span x-text="value.length"></span>@if ($maxLength) <span class="mx-0.5">/</span>{{ $maxLength }}@endif
            </span>
        </div>
    @endif
</div>

@if ($hasError && $name)
    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first($name) }}</p>
@endif
