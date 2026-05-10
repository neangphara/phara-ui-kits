@props([
    'default'  => '',
    'variant'  => 'underline',
    'size'     => 'md',
])

@php
$barClasses = match ($variant) {
    'pills'     => 'flex flex-wrap gap-1',
    'segmented' => 'flex gap-0.5 p-1 bg-zinc-100 dark:bg-zinc-800 rounded-xl',
    default     => 'flex border-b border-gray-200 dark:border-zinc-700', // underline
};
@endphp

@once
<style>[x-cloak] { display: none !important; }</style>
@endonce

<div
    x-data="{
        active: '{{ $default }}',
        variant: '{{ $variant }}',
        size: '{{ $size }}',

        tabClass(name) {
            const on = this.active === name;
            const sz = this.size === 'sm' ? 'px-3 py-1.5 text-xs' : 'px-4 py-2 text-sm';

            if (this.variant === 'underline') {
                return '-mb-px ' + sz + (on
                    ? ' border-b-2 border-primary-500 text-zinc-900 dark:text-white'
                    : ' border-b-2 border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200');
            }
            if (this.variant === 'pills') {
                return sz + (on
                    ? ' bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white rounded-xl'
                    : ' text-zinc-500 hover:text-zinc-700 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/50 rounded-xl');
            }
            if (this.variant === 'segmented') {
                return 'flex-1 text-center ' + sz + (on
                    ? ' bg-white text-zinc-900 shadow-sm dark:bg-zinc-700 dark:text-white rounded-lg'
                    : ' text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200 rounded-lg');
            }
            return sz;
        }
    }"
    {{ $attributes }}
>
    {{-- Tab bar --}}
    <div class="{{ $barClasses }}">
        {{ $tabs }}
    </div>

    {{-- Panels --}}
    <div class="mt-4">
        {{ $slot }}
    </div>
</div>
